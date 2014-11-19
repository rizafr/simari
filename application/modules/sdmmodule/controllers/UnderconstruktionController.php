<?php
require_once 'Zend/Controller/Action.php';
class Sdmmodule_UnderconstruktionController extends Zend_Controller_Action
{
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
	 	$registry = Zend_Registry::getInstance();
	   	$this->view->basePath = $registry->get('basepath');	


    }
	public function indexAction()
	{
	}

	
	
}

?>