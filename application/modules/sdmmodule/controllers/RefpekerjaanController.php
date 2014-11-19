<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refpekerjaan_Service.php";

class Sdmmodule_RefpekerjaanController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refpekerjaan_serv = Refpekerjaan_Service::getInstance();

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
public function pekerjaanjsAction() 
{
	header('content-type : text/javascript');
	$this->render('pekerjaanjs');
}	
	
public function listpekerjaanAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refpekerjaan_serv->getPekerjaanList($cari, 0, 0);
	$this->view->listPekerjaan = $this->refpekerjaan_serv->getPekerjaanList($cari ,$currentPage,$numToDisplay );
	
}

public function pekerjaanolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_pekerjaan = $_REQUEST['c_pekerjaan'];
	$this->view->n_pekerjaan = $_REQUEST['n_pekerjaan'];
	
	$this->view->detailPekerjaan = array();
	if($this->view->par == 'update'){
	$masukan = array("c_pekerjaan" => $this->view->c_pekerjaan);
	$this->view->detailPekerjaan = $this->refpekerjaan_serv->detailPekerjaan($masukan);
	}
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertpekerjaanAction(){
	$c_pekerjaan = $_POST['c_pekerjaan'];
	$n_pekerjaan = $_POST['n_pekerjaan'];
	
	$masukanInsert = array("c_pekerjaan" => $c_pekerjaan,
			"n_pekerjaan" => $n_pekerjaan);
	$hasil = $this->refpekerjaan_serv->tambahpekerjaan($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Pekerjaan';
	$this->view->status	= $hasil;
	$this->listpekerjaanAction();
	$this->render(listpekerjaan);
}

public function updatepekerjaanAction(){
	$c_pekerjaan = $_POST['c_pekerjaan'];
	$n_pekerjaan = $_POST['n_pekerjaan'];
	
	$masukanInsert = array("c_pekerjaan" => $c_pekerjaan,
			"n_pekerjaan" => $n_pekerjaan);
	$hasil = $this->refpekerjaan_serv->ubahpekerjaan($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Pekerjaan';
	$this->view->status	= $hasil;
	$this->listpekerjaanAction();
	$this->render(listpekerjaan);
}	

public function deletepekerjaanAction(){
	$c_pekerjaan = $_REQUEST['c_pekerjaan'];
	
	$masukanInsert = array("c_pekerjaan" => $c_pekerjaan);
	$hasil = $this->refpekerjaan_serv->hapuspekerjaan($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Pekerjaan';
	$this->view->status	= $hasil;
	$this->listpekerjaanAction();
	$this->render(listpekerjaan);
}

}
?>
