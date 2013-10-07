<?php
class Brst_Member_Block_Adminhtml_Payment_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Init class
     */
    public function __construct()
    {  
        parent::__construct();
     
        $this->setId('brst_member_payment_form');
        $this->setTitle($this->__('Payment Information'));
    }  
     
    /**
     * Setup form fields for inserts/updates
     *
     * return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {  
        $model = Mage::registry('brst_member');
        $oderincermtid =  $model->getOrderId();
        $statuses=array('paid'=>'paid','unpaid'=>'unpaid');
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
            'legend'    => Mage::helper('checkout')->__('Payment Information'),
            'class'     => 'fieldset-wide',
        ));
        
        
        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', array(
                'name' => 'id',
            ));
        }
        
        $fieldset->addField('member_name', 'text', array(
            'name'      => 'member_name',
            'label'     => Mage::helper('checkout')->__('Member Name'),
            'title'     => Mage::helper('checkout')->__('Member Name'),
            'required'  => true,
        ));
        
        $fieldset->addField('total_earned', 'text', array(
            'name'      => 'total_earned',
            'label'     => Mage::helper('checkout')->__('Total Earned'),
            'title'     => Mage::helper('checkout')->__('Total Earned'),
            'required'  => true,
        ));
        
        $fieldset->addField('total_paid', 'text', array(
            'name'      => 'total_paid',
            'label'     => Mage::helper('checkout')->__('Total Paid'),
            'title'     => Mage::helper('checkout')->__('Total Paid'),
            'required'  => false,
        ));
        
        $fieldset->addField('pending_amount', 'text', array(
            'name'      => 'pending_amount',
            'label'     => Mage::helper('checkout')->__('Pending Amount'),
            'title'     => Mage::helper('checkout')->__('Pending Amount'),
            'required'  => false,
        ));
        
        $fieldset->addField('inprogress', 'text', array(
            'name'      => 'inprogress',
            'label'     => Mage::helper('checkout')->__('Inprogress'),
            'title'     => Mage::helper('checkout')->__('Inprogress'),
            'required'  => false,
        ));
        
        $fieldset->addField('last_invoice_date', 'text', array(
            'name'      => 'last_invoice_date',
            'label'     => Mage::helper('checkout')->__('Last Invoice Date'),
            'title'     => Mage::helper('checkout')->__('Last Invoice Date'),
            'required'  => true,
        ));
        
       $fieldset->addField('status', 'select', array(
            'name'      => 'status',
             'values'    => $statuses,
            
            'label'     => Mage::helper('checkout')->__('status'),
            'title'     => Mage::helper('checkout')->__('status'),
            'required'  => true,
        ));
     
     
        $form->setValues($model->getData());
       
        $form->setUseContainer(true);
        $this->setForm($form);
        
        return parent::_prepareForm();
    }  
}