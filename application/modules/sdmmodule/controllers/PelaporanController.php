<?php
require_once 'Zend/Controller/Action.php';
require_once "service/sdm/sdm_pelaporan_Service.php";

class Sdm_PelaporanController extends Zend_Controller_Action
{
    var $serv = null;
	
	private $pelaporan_serv;
	private $pagu_ref_serv;
		
    public function init() {
	   $registry = Zend_Registry::getInstance();
   	   $this->view->basePath = $registry->get('basepath');
	   $this->pelaporan_serv = sdm_pelaporan_Service::getInstance();

    }
    	
    public function indexAction()
    {

    }

	public function dukjsAction() {
	     header('content-type : text/javascript');
	     $this->render('dukjs');
		 
    }
	
    public function dukAction() {

	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		
		$iOrgb = $_POST['i_orgb'];
		$this->view->orgList = $this->pelaporan_serv->listOrg();	
		$this->view->totalList = $this->pelaporan_serv->getDukList($cari, 0, 0 );		
		$this->view->dukList = $this->pelaporan_serv->getDukList($cari,$currentPage, $numToDisplay);
	}	

	
}

?>