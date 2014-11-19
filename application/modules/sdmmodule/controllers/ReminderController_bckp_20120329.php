<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/Sdm_Statistik_Service.php";
require_once "service/sdm/Sdm_Monitoring_Service.php";
class Sdmmodule_ReminderController extends Zend_Controller_Action {

		
    public function init() {
	$this->_helper->layout->setLayout('target-column');
	$registry = Zend_Registry::getInstance();
	$this->auth = Zend_Auth::getInstance();
	$this->view->basePath = $registry->get('basepath');
	$this->view->baseData = $registry->get('baseData');
	$this->statistik_serv = Sdm_Statistik_Service::getInstance();
	$this->monitoring_serv = Sdm_Monitoring_Service::getInstance();
    }
	
	public function indexAction() {
	}
	
	public function pensiunAction() {
		$thnpen=date('Y')+1;
		$tgla="$thnpen-01-01";	
		$caripens= "and (EXTRACT(years from AGE('$tgla', d_peg_lahir))= q_usia_pensiun)";
		$this->view->remindPensiun=$this->monitoring_serv->getPegawaiListByNip($caripens);	
	}



}
?>