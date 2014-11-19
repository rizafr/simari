<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refstatusnikah_Service.php";

class Sdmmodule_RefstatusnikahController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refstatusnikah_serv = Refstatusnikah_Service::getInstance();

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
  			$this->c_status_nikah_unitkerja = $ssologin->c_status_nikah_unitkerja; 
  			$this->c_satker			= $ssologin->c_satker; 
  			$this->n_satker			= $ssologin->n_satker; 
  			$this->c_izin			= $ssologin->view->userIzin; 
  			$this->checkotoritas	= $ssologin->view->checkotoritas; 
  		}	
    }
	
    public function indexAction() {
	   
    }
public function statusnikahjsAction() 
{
	header('content-type : text/javascript');
	$this->render('statusnikahjs');
}	
	
public function liststatusnikahAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refstatusnikah_serv->getStatusnikahList($cari, 0, 0);
	$this->view->listStatusnikah = $this->refstatusnikah_serv->getStatusnikahList($cari ,$currentPage,$numToDisplay );
	
}

public function statusnikaholahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_status_nikah = $_REQUEST['c_status_nikah'];
	$this->view->n_status_nikah = $_REQUEST['n_status_nikah'];
	$this->view->filt = $_REQUEST['filt'];
	
	$this->view->detailStatusnikah = array();
	if($this->view->par == 'update'){
		$masukan = array("c_status_nikah" => $this->view->c_status_nikah);
		$this->view->detailStatusnikah = $this->refstatusnikah_serv->detailStatusnikah($masukan);
	}
	
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertstatusnikahAction(){
	$n_status_nikah = $_POST['n_status_nikah'];
	$c_status_nikah = $_POST['c_status_nikah'];
	$filt = $_POST['filt'];
	
	$masukanInsert = array("n_status_nikah" => $n_status_nikah,
			"c_status_nikah" => $c_status_nikah,
			"filt" => $filt);
	$hasil = $this->refstatusnikah_serv->tambahstatusnikah($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Status Nikah';
	$this->view->status	= $hasil;
	$this->liststatusnikahAction();
	$this->render(liststatusnikah);
}

public function updatestatusnikahAction(){
	$n_status_nikah = $_POST['n_status_nikah'];
	$c_status_nikah = $_POST['c_status_nikah'];
	$filt = $_POST['filt'];
	
	$masukanInsert = array("n_status_nikah" => $n_status_nikah,
			"c_status_nikah" => $c_status_nikah,
			"filt" => $filt);
	$hasil = $this->refstatusnikah_serv->ubahstatusnikah($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Status Nikah';
	$this->view->status	= $hasil;
	$this->liststatusnikahAction();
	$this->render(liststatusnikah);
}	

public function deletestatusnikahAction(){
	$c_status_nikah = $_REQUEST['c_status_nikah'];
	
	$masukanInsert = array("c_status_nikah" => $c_status_nikah);
	$hasil = $this->refstatusnikah_serv->hapusstatusnikah($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Status Nikah';
	$this->view->status	= $hasil;
	$this->liststatusnikahAction();
	$this->render(liststatusnikah);
}

}
?>
