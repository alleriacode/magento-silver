<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento community edition
 * aheadWorks does not guarantee correct work of this extension
 * on any other Magento edition except Magento community edition.
 * aheadWorks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Affiliate
 * @version    1.0.2
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */


class AW_Affiliate_Model_Transaction_Profit extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        $this->_init('awaffiliate/transaction_profit');
    }

    public function createTransaction()
    {
        if ($this->_isValidForCreation()) {
            /** @var $campaign AW_Affiliate_Model_Campaign */
            $campaign = $this->getCampaign();
            
            $affiliate = $this->getAffiliate();
            $profitModel = $campaign->getProfitModel();

            if (!$this->_canBeApplied($profitModel)) return $this;

            $rate = $profitModel->getRateForAffiliate($affiliate);
            if (is_null($rate)) {
                Mage::throwException($this->__('Rate cannot be calculated'));
            }
            $this->setData('rate', $rate);

            $attractedAmount = $this->_getAttractedAmount();
            if (is_null($attractedAmount)) {
                Mage::throwException($this->__('Attracted amount cannot be calculated'));
            }
            $this->setData('attracted_amount', $attractedAmount);

            $amount = $campaign->getProfitModel()->getAmountForAffiliate($affiliate, $attractedAmount);
            $this->setData('amount', $amount);
            
            
            
            $currencyCode = $this->_getCurrencyCode();
            if (is_null($currencyCode)) {
                Mage::throwException($this->__('Currency undefined'));
            }
            $this->setData('currency_code', $currencyCode);
            $this->save();
            
            /*
             * Save Data in brst_calculate_discount table 
             */
            
            $transactionModel = Mage::getModel('awaffiliate/transaction_profit')->load($this->getId());
            $customdiscount=Mage::getModel('brst_calculate/discount');
         
            $affiliatedetail =  Mage::getModel('awaffiliate/affiliate')->load($transactionModel->getAffiliateId())->getData();
            $customerModel = Mage::getModel('customer/customer')->load($affiliatedetail['customer_id']);
            $clientmodel=Mage::getModel('awaffiliate/client')->load($transactionModel->getClientId())->getData();
            $customerModel1 = Mage::getModel('customer/customer')->load($clientmodel['customer_id']);
            
            $customdiscount->setorder_id($transactionModel->getLinkedEntityId());
            $customdiscount->setcustomer_id($clientmodel['customer_id']);
            $customdiscount->setcustomer_name($customerModel1->getFirstname());
            $customdiscount->setaffiliate_name($customerModel->getFirstname());
            $customdiscount->setaffiliate_id($transactionModel->getAffiliateId());
            $customdiscount->setcommission($transactionModel->getAmount());
            $customdiscount->setattracted_amount($transactionModel->getAttractedAmount());
            $memberamount=$transactionModel->getAttractedAmount()-$transactionModel->getAmount();
            $adminamount = $memberamount * 20/100;
            $memebertotalamount = ($memberamount - $adminamount);
            $customdiscount->setmember_amount($memebertotalamount);
            $customdiscount->setadmin_amount($adminamount);
            $customdiscount->setcreated_at($transactionModel->getCreatedAt());
            
            /* For experts collection */
            $orderid = $transactionModel->getLinkedEntityId();
            $order = Mage::getModel('sales/order')->loadByIncrementId($orderid);
            $items = $order->getAllItems();
            //$itemcount=count($items);
            foreach ($items as $itemId => $item)
            {
                $name[] = $item->getName();
                $unitPrice[]=$item->getPrice();
                $sku[]=$item->getSku();
                $ids[]=$item->getProductId();
                $qty[]=$item->getQtyToInvoice();
                $created_at[] = $item->getCreatedAt();
            }
            //echo "<pre>";print_r($created_at);die('here');
            foreach($ids as $key=>$id)
            {
                $id=$ids[$key];
                $item_name=$name[$key];
                $product_price=$unitPrice[$key];
                $date = time($created_at[$key]);
                $admin_price = $product_price * 20/100;
                $total_discount = $admin_price + $rate;
                $expert_price = ($product_price - $total_discount);
                
                $_product = Mage::getModel('catalog/product')->load($id);
                $expertname = $_product->getResource()->getAttribute('member_list')->getFrontend()->getValue($_product);
                
                /* insert every item of order into table 'brst_experts_orders' */
                $connection=Mage::getSingleton("core/resource")->getConnection("core_write");
                $qry="insert into brst_experts_orders(order_id,item_name,item_price,member_name,created_at) values ('$orderid','$item_name','$product_price','$expertname','$date')";
                $connection->query($qry);
                
                $check_model = Mage::getModel('brst_experts/amount')->getCollection()->getData();
                $ifexist=0;
                $adminid='';
                $no_order='';
                $totalamount='';
                $admin_amount='';
                foreach($check_model as $colldta)
                {
                    if($colldta['temp_name']==$expertname)
                    {
                        $ifexist=1;
                        $adminid=$colldta['id'];
                        $totalamount=$colldta['temp_amount'];
                        $admin_amount=$colldta['admin_amount'];
                        $no_order=$colldta['temp_orders'];
                        break;
                    }
                }
                if($ifexist==1)
                {
                    $model = Mage::getModel('brst_experts/amount')->load($adminid);
                    $model->settemp_name($expertname);
                    $model->settemp_orders($no_order + 1);
                    $model->settemp_amount($totalamount + $expert_price);
                    $model->setadmin_amount($admin_amount + $admin_price);
                    $model->save();
                }
                else
                {
                    $model = Mage::getModel('brst_experts/amount');
                    $model->settemp_name($expertname);
                    $model->settemp_orders(1);
                    $model->settemp_amount($expert_price);
                    $model->setadmin_amount($admin_price);
                    $model->save();
                }
            }
            
            $customdiscount->save();
            
        } else {
            Mage::throwException($this->__('Not valid for create transaction'));
        }
        return $this;
    }
    
    protected function _canBeApplied($profitModel)
    {
        switch ($profitModel->getType()) {
            case AW_Affiliate_Model_Source_Profit_Type::TIER_CUR:
            case AW_Affiliate_Model_Source_Profit_Type::FIXED_CUR:
                /** @var $order Mage_Sales_Model_Order */
                if ($order = $this->getData('linked_entity_order')) {
                    return $order->getInvoiceCollection()->getSize() == 0;
                } else {
                    return false;
                }
                break;
        }
        return true;
    }

    public function getAffiliate()
    {
        if (!is_null($this->getData('affiliate_id')) && intval($this->getData('affiliate_id')) > 0) {
            $_affiliate = Mage::getModel('awaffiliate/affiliate')->load($this->getData('affiliate_id'));
            if (!is_null($_affiliate->getId())) {
                return $_affiliate;
            }
        }
        return null;
    }

    public function getCampaign()
    {
        if (!is_null($this->getData('campaign_id')) && intval($this->getData('campaign_id')) > 0) {
            $_campaign = Mage::getModel('awaffiliate/campaign')->load($this->getData('campaign_id'));
            if (!is_null($_campaign->getId())) {
                return $_campaign;
            }
        }
        return null;
    }

    public function getLinkedEntity()
    {
        switch ($this->getData('linked_entity_type')) {
            case AW_Affiliate_Model_Source_Transaction_Profit_Linked::INVOICE_ITEM:
                $entityObject = $this->getData('linked_entity_invoice');
                break;
            case AW_Affiliate_Model_Source_Transaction_Profit_Linked::ORDER_ITEM :
                $entityObject = Mage::getModel('sales/order_item')->load($this->getData('linked_entity_id'));
                break;
            default:
                return null;
        }
        return (isset($entityObject) && $entityObject) ? $entityObject : null;
    }

    protected function _isValidForCreation()
    {
        if (is_null($this->getAffiliate())) {
            return false;
        }
        if (is_null($this->getCampaign())) {
            return false;
        }
        if (is_null($this->getLinkedEntity())) {
            return false;
        }
        $type = Mage::getModel('awaffiliate/source_transaction_profit_type')->getOption($this->getData('type'));
        if ($type === FALSE) {
            return false;
        }
        return true;
    }

    protected function _getAttractedAmount()
    {
        $linkedEntity = $this->getLinkedEntity();
        if (!$linkedEntity) {
            return null;
        }
        $amount = null;
        switch ($this->getData('linked_entity_type')) {
            case AW_Affiliate_Model_Source_Transaction_Profit_Linked::INVOICE_ITEM:
                if (Mage::helper('awaffiliate/config')->isConsiderTax($linkedEntity->getData('store_id'))) {
                    $amount = $linkedEntity->getData('subtotal_incl_tax');
                } else {
                    $amount = $linkedEntity->getData('subtotal');
                }
                if ($_discountAmount = $linkedEntity->getData('discount_amount')) {
                    $amount = max(0, $amount - $_discountAmount);
                }
                break;
            case AW_Affiliate_Model_Source_Transaction_Profit_Linked::ORDER_ITEM :
                if (Mage::helper('awaffiliate/config')->isConsiderTax($linkedEntity->getData('store_id'))) {
                    $amount = $linkedEntity->getData('row_total_incl_tax');
                } else {
                    $amount = $linkedEntity->getData('row_total');
                }
                break;
            default:
                return null;
        }
        return $amount;
    }

    protected function _getCurrencyCode()
    {
        $linkedEntity = $this->getLinkedEntity();
        if (!$linkedEntity) {
            return null;
        }
        $code = null;
        switch ($this->getData('linked_entity_type')) {
            case AW_Affiliate_Model_Source_Transaction_Profit_Linked::INVOICE_ITEM:
            case AW_Affiliate_Model_Source_Transaction_Profit_Linked::ORDER_ITEM:
                $code = $linkedEntity->getOrder()->getData('base_currency_code');
                break;
            default:
                return null;
        }
        return $code;
    }

    protected function _afterLoad()
    {
        if (is_string($this->getData('withdrawal_transaction_ids')))
            $this->setData('withdrawal_transaction_ids', @explode(',', $this->getData('withdrawal_transaction_ids')));
        return parent::_afterLoad();
    }

    protected function _beforeSave()
    {
        $res = parent::_beforeSave();
        if (is_null($this->getData('rate'))) {
            Mage::throwException(Mage::helper('awaffiliate')->__('Rate is not specified'));
        }
        if (is_null($this->getData('currency_code'))) {
            Mage::throwException(Mage::helper('awaffiliate')->__('Currency code is not specified'));
        }
        if ($this->getData('withdrawal_transaction_ids') !== null && is_array($this->getData('withdrawal_transaction_ids')))
            $this->setData('withdrawal_transaction_ids', @implode(',', $this->getData('withdrawal_transaction_ids')));
        return $res;
    }

    protected function _afterSave()
    {
        $res = parent::_afterSave();
        $affiliate = $this->getAffiliate();
        if (!is_null($affiliate)) {
            $affiliate->recollectBalances();
        }
        return $res;
    }
}
