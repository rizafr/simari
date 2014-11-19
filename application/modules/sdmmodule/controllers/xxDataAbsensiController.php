<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';

class Sdmmodule_DataAbsensiController extends Zend_Controller_Action {
		
    public function init() {

		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		
    }
    public function indexAction() {
	   
    }
	public function dataabsensijsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('dataabsensijs');
    }
	
	public function listdataabsensiAction()
	{
	
	}
	
	public function dataabsensiAction()
	{

	}



}
?>