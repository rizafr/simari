<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refstatuspegawai_Service.php";

class Sdmmodule_RefstatuspegawaiController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refstatuspegawai_serv = Refstatuspegawai_Service::getInstance();

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
public function statuspegawaijsAction() 
{
	header('content-type : text/javascript');
	$this->render('statuspegawaijs');
}	
	
public function liststatuspegawaiAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refstatuspegawai_serv->getStatuspegawaiList($cari, 0, 0);
	$this->view->listStatuspegawai = $this->refstatuspegawai_serv->getStatuspegawaiList($cari ,$currentPage,$numToDisplay );
	
}

public function statuspegawaiolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_peg_status = $_REQUEST['c_peg_status'];
	$this->view->n_peg_status = $_REQUEST['n_peg_status'];
	
	$masukan = array("c_peg_status" => $this->view->c_peg_status);
	$this->view->detailStatuspegawai = $this->refstatuspegawai_serv->detailStatuspegawai($masukan);
	//$this->view->listStatuspegawai = $this->refstatuspegawai_serv->getStatuspegawaiList($cari);
}

public function insertstatuspegawaiAction(){
	$n_peg_status = $_POST['n_peg_status'];
	$c_peg_status = $_POST['c_peg_status'];
	
	$masukanInsert = array("n_peg_status" => $n_peg_status,
			"c_peg_status" => $c_peg_status,
			"i_entry" => $this->userid);
	$hasil = $this->refstatuspegawai_serv->tambahstatuspegawai($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Status Pegawai';
	$this->view->status	= $hasil;
	$this->liststatuspegawaiAction();
	$this->render(liststatuspegawai);
}

public function updatestatuspegawaiAction(){
	$n_peg_status = $_POST['n_peg_status'];
	$c_peg_status = $_POST['c_peg_status'];
	
	$masukanInsert = array("n_peg_status" => $n_peg_status,
			"c_peg_status" => $c_peg_status,
			"i_entry" => $this->userid);
	$hasil = $this->refstatuspegawai_serv->ubahstatuspegawai($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Status Pegawai';
	$this->view->status	= $hasil;
	$this->liststatuspegawaiAction();
	$this->render(liststatuspegawai);
}	

public function deletestatuspegawaiAction(){
	$c_peg_status = $_REQUEST['c_peg_status'];
	
	$masukanInsert = array("c_peg_status" => $c_peg_status,
			"i_entry" => $this->userid);
	$hasil = $this->refstatuspegawai_serv->hapusstatuspegawai($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Status Pegawai';
	$this->view->status	= $hasil;
	$this->liststatuspegawaiAction();
	$this->render(liststatuspegawai);
}

}
?>
