<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refrekonsiliasi_Service.php";

class Sdmmodule_RefrekonsiliasiController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refrekonsiliasi_serv = Refrekonsiliasi_Service::getInstance();

  		$ssologin = new Zend_Session_Namespace('ssologin');
  		
  		if ($ssologin->userid && $ssologin->n_peg){
  			$this->userid  			= $ssologin->userid;
  			$this->password			= $ssologin->password;
  			$this->i_peg_nip  		= $ssologin->i_peg_nip;
  			$this->i_peg_nip_new	= $ssologin->i_peg_nip_new;
  			$this->n_peg  			= $ssologin->n_peg;
  			$this->n_peg_gelardepan = $ssologin->n_peg_gelardepan;
  			$this->n_peg_gelarblkg 	= $ssologin->n_peg_gelarblkg;
  			$this->c_jabatan 		= $ssologin->c_jabatan;
  			$this->c_eselon_i 		= $ssologin->c_eselon_i;
  			$this->c_eselon_ii 		= $ssologin->c_eselon_ii;
  			$this->c_eselon_iii 	= $ssologin->c_eselon_iii;
  			$this->c_eselon_iv 		= $ssologin->c_eselon_iv;
  			$this->c_eselon_v 		= $ssologin->c_eselon_v; 
  			$this->c_lokasi_unitkerja = $ssologin->c_lokasi_unitkerja; 
  			$this->c_satker			= $ssologin->c_satker; 
  			$this->n_satker			= $ssologin->n_satker; 
  			$this->c_izin			= $ssologin->view->userIzin; 
  			$this->checkotoritas	= $ssologin->view->checkotoritas; 
  		}	
    }
	
    public function indexAction() {
	   
    }
    
public function rekonsiliasijsAction() 
{
	header('content-type : text/javascript');
	$this->render('rekonsiliasijs');
}	
	
public function listrekonsiliasiAction() {
	/*$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 7200;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refrekonsiliasi_serv->getRekonsiliasiList($cari, 0, 0);*/
	$tahun = $_REQUEST['tahun'];
	$unit = $_REQUEST['unit'];
	$this->view->tahun = $tahun;
	$this->view->unit = $unit;
	$this->view->listRekonsiliasi = $this->refrekonsiliasi_serv->getRekonsiliasiList($cari);
	//if($this->view->par== 'tampil'){
		//$this->view->listRekonsiliasii = $this->refrekonsiliasi_serv->getRekon($cari,663157,2011);
		$this->view->listRekonsiliasii = $this->refrekonsiliasi_serv->getRekon($cari,$unit,$tahun);
	//}
}
}?>