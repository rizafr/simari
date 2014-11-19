<?php
require_once 'Zend/Controller/Action.php';
class Sdmmodule_MonitoringsjabatanController extends Zend_Controller_Action
{
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
	 	$registry = Zend_Registry::getInstance();
	   	$this->view->basePath = $registry->get('basepath');	


    }
	public function indexAction()
	{
	}
	public function monitoringsjabatanjsAction() 
	{
		header('content-type : text/javascript');
		$this->render('monitoringsjabatanjs');
	}
	public function monitoringsjabatanAction() 
	{
	    	
	}	
	
	
}

?>