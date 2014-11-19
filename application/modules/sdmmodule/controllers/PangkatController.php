<?php
require_once 'Zend/Controller/Action.php';
//require_once "service/sdm/Sdm_Pangkat_Service.php";

class Sdm_PangkatController extends Zend_Controller_Action {

public function init() {
 	   $registry = Zend_Registry::getInstance();
	   $this->view->basePath = $registry->get('basepath'); 
	   //$this->sdm_lvl_serv = Sdm_Pangkat_Service::getInstance();
}
	
public function indexAction() {
}
 
 
public function usulkpAction() {

}
	

}
?>