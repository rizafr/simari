<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/Sdm_Pegawai_Service.php";

class Sdm_RekapitulasistatistikController extends Zend_Controller_Action {
		
	public function init() {
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->basePath = $registry->get('basepath'); 
		$this->view->pathUPLD = $registry->get('pathUPLD');
		$this->view->procPath = $registry->get('procpath');		
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();

	}	
	public function indexAction() {	   
	}	
	public function rekapitulasidemografijsAction() {
		header('content-type : text/javascript');
		$this->render('rekapitulasidemografijs');
	}
	public function rekapitulasidemografiAction() {
		$this->view->unitOList = $this->pegawai_serv->getUnitOrgList();
		$this->view->unitKList = $this->pegawai_serv->getUnitKerjaList($carisatker);
	}	
	public function rekapitulasidemografilistAction() {	
	}

		
}
?>