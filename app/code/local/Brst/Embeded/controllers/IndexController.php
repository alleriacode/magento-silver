<?php
class Brst_Embeded_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
       
		$this->loadLayout();     
		$this->renderLayout();
    }
    public function codeAction()
    {
		$this->loadLayout();     
		$this->renderLayout();
    }
 
     public function findAction()
    {
               echo  $this->getRequest()->getParam('q');
               die('helo');
		$this->loadLayout();     
		$this->renderLayout();
    }
}