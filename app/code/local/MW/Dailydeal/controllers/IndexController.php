<?php
class MW_Dailydeal_IndexController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
    {
    	$uri = explode('/dailydeal',$_SERVER['REQUEST_URI']);
    	$uri1 = explode('/dailydeal/index',$_SERVER['REQUEST_URI']);
    	$uri2 = explode('/dailydeal/index/index',$_SERVER['REQUEST_URI']);
    	
    	if((sizeof($uri)>1) || (sizeof($uri1)>1) || (sizeof($uri2)>1)) {
			$this->_redirect(Mage::helper('dailydeal')->getRewriteUrl('dailydeal/index'));
		}else{
    		$this->loadLayout();
                $this->getLayout()->getBlock('head')->setTitle('Special Offer');
			$this->renderLayout();
		}
    }
}