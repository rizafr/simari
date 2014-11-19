<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/sdm_refferensi_Service.php";

class Sdmmodule_PenilaianreffController extends Zend_Controller_Action {
		
public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 	
		$this->reff_serv = sdm_refferensi_Service::getInstance();	
    }
	
public function indexAction() {
}
	
public function penilaianreffjsAction() 
{
	header('content-type : text/javascript');
	$this->render('penilaianreffjs');
}	

public function penilaianreffAction() {

}

	
}
?>