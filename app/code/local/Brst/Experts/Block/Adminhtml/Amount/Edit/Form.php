<?php
class Brst_Experts_Block_Adminhtml_Amount_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Init class
     */
    public function __construct()
    {  
        parent::__construct();
     
        $this->setId('brst_experts_amount_form');
        $this->setTitle($this->__('Amount Information'));
    }  
     
    /**
     * Setup form fields for inserts/updates
     *
     * return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {  
        $model = Mage::registry('brst_experts');
        $oderincermtid =  $model->getOrderId();
      
         $orders = Mage::getModel('sales/order')
          ->getCollection()
          ->addAttributeToFilter('state', array('neq' => Mage_Sales_Model_Order::STATE_CANCELED))
          ->addAttributeToFilter('increment_id', $oderincermtid)
          ->getFirstItem();
         $orderid=$orders['entity_id'];
     
        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method'    => 'post'
        ));
     
        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend'    => Mage::helper('checkout')->__('Amount Information'),
            'class'     => 'fieldset-wide',
        ));
     
        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', array(
                'name' => 'id',
            ));
        }
        
        /*$fieldset->addField('expert_id', 'text', array(
            'name'      => 'expert_id',
            'label'     => Mage::helper('checkout')->__('Expert ID'),
            'title'     => Mage::helper('checkout')->__('Expert ID'),
            'required'  => true,
        ));*/
        $fieldset->addField('order_id', 'text', array(
            'name'      => 'order_id',
            'label'     => Mage::helper('checkout')->__('Order ID'),
            'title'     => Mage::helper('checkout')->__('Order ID'),
            'required'  => true,
        ));
        
        
        $fieldset->addField('expert_name', 'text', array(
            'name'      => 'expert_name',
            'label'     => Mage::helper('checkout')->__('Expert Name'),
            'title'     => Mage::helper('checkout')->__('Expert Name'),
            'required'  => true,
        ));
        
        $fieldset->addField('affiliate_name', 'text', array(
            'name'      => 'affiliate_name',
            'label'     => Mage::helper('checkout')->__('Affiliate Name'),
            'title'     => Mage::helper('checkout')->__('Affiliate Name'),
            'required'  => true,
        ));
        
        
        
        /*$fieldset->addField('admin_amount', 'text', array(
            'name'      => 'admin_amount',
            'label'     => Mage::helper('checkout')->__('Admin Amount'),
            'title'     => Mage::helper('checkout')->__('Admin Amount'),
            'required'  => true,
        ));*/
     
        $form->setValues($model->getData());
       
        $form->setUseContainer(true);
        $this->setForm($form);
        
        return parent::_prepareForm();
    }  
}