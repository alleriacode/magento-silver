<?php
class Brst_Requestaffialiate_Block_Adminhtml_Affialiate_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Init class
     */
    public function __construct()
    {  
         parent::__construct();
     
        $this->setId('brst_requestaffialiate_affialiate_form');
        $this->setTitle($this->__('Affialiate Information'));
    }  
     
    /**
     * Setup form fields for inserts/updates
     *
     * return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {  
       
        $model = Mage::registry('brst_requestaffialiate');
        //$statuses=array('pending'=>'pending','complete'=>'complete');
        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method'    => 'post'
        ));
     
        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend'    => Mage::helper('checkout')->__('Affialiate Information'),
            'class'     => 'fieldset-wide',
        ));
     
        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', array(
                'name' => 'id',
            ));
        }  
        $fieldset->addField('customer_id', 'text', array(
            'name'      => 'customer_id',
            'label'     => Mage::helper('checkout')->__('Customer ID'),
            'title'     => Mage::helper('checkout')->__('Customer ID'),
          
            'required'  => true,
            'readonly' => true,
        ));
     
        $fieldset->addField('email', 'text', array(
           'label'     => Mage::helper('checkout')->__('Email'),
            'name'      =>'email',
            'title'     => Mage::helper('checkout')->__('Email'),
            'required'  => true,
            'readonly' => true,
        ));
        $fieldset->addField('status', 'select', array(
             'label'     => Mage::helper('checkout')->__('Status'),
            'title'     => Mage::helper('checkout')->__('Status'),
            //'values'    => $statuses,
            'name'      =>'status',
            'required'  => true,
        ));
     
        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);
     
        return parent::_prepareForm();
    }  
}