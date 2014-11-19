<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refstatusfung_Service.php";

class Sdmmodule_RefstatusfungController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refstatusfung_serv = Refstatusfung_Service::getInstance();

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
  			$this->c_statusfung_unitkerja = $ssologin->c_statusfung_unitkerja; 
  			$this->c_satker			= $ssologin->c_satker; 
  			$this->n_satker			= $ssologin->n_satker; 
  			$this->c_izin			= $ssologin->view->userIzin; 
  			$this->checkotoritas	= $ssologin->view->checkotoritas; 
  		}	
    }
	
    public function indexAction() {
	   
    }
public function statusfungjsAction() 
{
	header('content-type : text/javascript');
	$this->render('statusfungjs');
}	
	
public function liststatusfungAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refstatusfung_serv->getStatusfungList($cari, 0, 0);
	$this->view->listStatusfung = $this->refstatusfung_serv->getStatusfungList($cari ,$currentPage,$numToDisplay );
	
}

public function statusfungolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_statusfung = $_REQUEST['c_statusfung'];
	$this->view->n_statusfung = $_REQUEST['n_statusfung'];
	$this->view->c_kelompok = $_REQUEST['c_kelompok'];
	
	$this->view->detailStatusfung = array();
	if($this->view->par == 'update'){
		$masukan = array("c_statusfung" => $this->view->c_statusfung);
		$this->view->detailStatusfung = $this->refstatusfung_serv->detailStatusfung($masukan);
	}
	
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertstatusfungAction(){
	$n_statusfung = $_POST['n_statusfung'];
	$c_statusfung = $_POST['c_statusfung'];
	$c_kelompok = $_POST['c_kelompok'];
	
	$masukanInsert = array("n_statusfung" => $n_statusfung,
			"c_statusfung" => $c_statusfung,
			"c_kelompok" => $c_kelompok);
	$hasil = $this->refstatusfung_serv->tambahstatusfung($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Status Fungsional';
	$this->view->status	= $hasil;
	$this->liststatusfungAction();
	$this->render(liststatusfung);
}

public function updatestatusfungAction(){
	$n_statusfung = $_POST['n_statusfung'];
	$c_statusfung = $_POST['c_statusfung'];
	$c_kelompok = $_POST['c_kelompok'];
	
	$masukanInsert = array("n_statusfung" => $n_statusfung,
			"c_statusfung" => $c_statusfung,
			"c_kelompok" => $c_kelompok);
	$hasil = $this->refstatusfung_serv->ubahstatusfung($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Status Fungsional';
	$this->view->status	= $hasil;
	$this->liststatusfungAction();
	$this->render(liststatusfung);
}	

public function deletestatusfungAction(){
	$c_statusfung = $_REQUEST['c_statusfung'];
	
	$masukanInsert = array("c_statusfung" => $c_statusfung);
	$hasil = $this->refstatusfung_serv->hapusstatusfung($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Status Fungsional';
	$this->view->status	= $hasil;
	$this->liststatusfungAction();
	$this->render(liststatusfung);
}

}
?>
