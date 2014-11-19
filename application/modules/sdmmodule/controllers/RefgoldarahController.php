<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refgoldarah_Service.php";

class Sdmmodule_RefgoldarahController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refgoldarah_serv = Refgoldarah_Service::getInstance();

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
public function goldarahjsAction() 
{
	header('content-type : text/javascript');
	$this->render('goldarahjs');
}	
	
public function listgoldarahAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refgoldarah_serv->getGoldarahList($cari, 0, 0);
	$this->view->listGoldarah = $this->refgoldarah_serv->getGoldarahList($cari ,$currentPage,$numToDisplay );
	
}

public function goldaraholahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_golongan_darah = $_REQUEST['c_golongan_darah'];
	$this->view->n_golongan_darah = $_REQUEST['n_golongan_darah'];
	
	$this->view->detailGoldarah = array();
	if($this->view->par == 'update'){
		$masukan = array("c_golongan_darah" => $this->view->c_golongan_darah);
		$this->view->detailGoldarah = $this->refgoldarah_serv->detailGoldarah($masukan);
	}
	
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertgoldarahAction(){
	$c_golongan_darah = $_POST['c_golongan_darah'];
	$n_golongan_darah = $_POST['n_golongan_darah'];
	
	$masukanInsert = array("c_golongan_darah" => $c_golongan_darah,
			"n_golongan_darah" => $n_golongan_darah);
	$hasil = $this->refgoldarah_serv->tambahgoldarah($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Golongan darah';
	$this->view->status	= $hasil;
	$this->listgoldarahAction();
	$this->render(listgoldarah);
}

public function updategoldarahAction(){
	$c_golongan_darah = $_POST['c_golongan_darah'];
	$n_golongan_darah = $_POST['n_golongan_darah'];
	
	$masukanInsert = array("c_golongan_darah" => $c_golongan_darah,
			"n_golongan_darah" => $n_golongan_darah);
	$hasil = $this->refgoldarah_serv->ubahgoldarah($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Golongan darah';
	$this->view->status	= $hasil;
	$this->listgoldarahAction();
	$this->render(listgoldarah);
}	

public function deletegoldarahAction(){
	$c_golongan_darah = $_REQUEST['c_golongan_darah'];
	
	$masukanInsert = array("c_golongan_darah" => $c_golongan_darah);
	$hasil = $this->refgoldarah_serv->hapusgoldarah($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Golongan darah';
	$this->view->status	= $hasil;
	$this->listgoldarahAction();
	$this->render(listgoldarah);
}

}
?>
