<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Information_Bank_IndexController extends Mage_Core_Controller_Front_Action
{
   protected function _getSession()
    {       
        return Mage::getSingleton('customer/session');
    }
    public function indexAction()
    {
        $session = $this->_getSession();
       if ($session->isLoggedIn()){
             $this->loadLayout(array('default'));
             $this->getLayout()->getBlock('head')->setTitle('Bank Information');
             $this->renderLayout();
        }
        else
        {
            $this->_redirect('customer/account/index');
            return;
        }
    }
    public function saveAction()
    { 
         $session = $this->_getSession();
         if ($session->isLoggedIn()){
      $arrData=$this->getRequest()->getPost();
    
      if($arrData['bankid']!= '')
      {
          if($arrData['countryid']=='DE' )
          {
              
              $bank_model=Mage::getModel('bank/bank')->load($arrData['bankid']);
              $bank_model->setcustomer_id($arrData['customerid']);
              $bank_model->setbank_name($arrData['bankname']);
              $bank_model->setaccount_name($arrData['accountname']);
              $bank_model->setbank_code($arrData['bankcode']);
              $bank_model->setbic('');
              $bank_model->setiban('');
              $bank_model->setbusinesstype($arrData['radio2']);
              $bank_model->save();
               
           }
         else {
            
              $bank_model=Mage::getModel('bank/bank')->load($arrData['bankid']);
              $bank_model->setcustomer_id($arrData['customerid']);
              $bank_model->setbank_name($arrData['bankname']);
              $bank_model->setaccount_name('');
              $bank_model->setbank_code('');
              $bank_model->setbic($arrData['bic']);
              $bank_model->setiban($arrData['iban']);
              $bank_model->setbusinesstype($arrData['radio2']);
              $bank_model->save();
              
         }
      }
      else {
             if($arrData['countryid']=='DE')
              {
                  $bank_model=Mage::getModel('bank/bank');
                  $bank_model->setcustomer_id($arrData['customerid']);
                  $bank_model->setbank_name($arrData['bankname']);
                  $bank_model->setaccount_name($arrData['accountname']);
                  $bank_model->setbank_code($arrData['bankcode']);
                  $bank_model->setbic('');
                  $bank_model->setiban('');
                  $bank_model->setbusinesstype($arrData['radio2']);
                  $bank_model->save();
                    
              }
             else 
              {
                  $bank_model=Mage::getModel('bank/bank');
                  $bank_model->setcustomer_id($arrData['customerid']);
                  $bank_model->setbank_name($arrData['bankname']);
                  $bank_model->setaccount_name('');
                  $bank_model->setbank_code('');
                  $bank_model->setbic($arrData['bic']);
                  $bank_model->setiban($arrData['iban']);
                  $bank_model->setbusinesstype($arrData['radio2']);
                  $bank_model->save();
                   
             }
           }
           Mage::getSingleton('core/session')->addsuccess('The Bank Information has been saved');
           $this->_redirect('bank/index/index');
             }
        else
        {
            $this->_redirect('customer/account/index');
            return;
        }
     }  
}
?>
