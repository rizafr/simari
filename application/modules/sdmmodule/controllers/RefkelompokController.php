<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refkelompok_Service.php";

class Sdmmodule_RefkelompokController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refkelompok_serv = Refkelompok_Service::getInstance();

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
public function kelompokjsAction() 
{
	header('content-type : text/javascript');
	$this->render('kelompokjs');
}	
	
public function listkelompokAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refkelompok_serv->getKelompokList($cari, 0, 0);
	$this->view->listKelompok = $this->refkelompok_serv->getKelompokList($cari ,$currentPage,$numToDisplay );
	
}

public function kelompokolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_kelompok = $_REQUEST['c_kelompok'];
	$this->view->n_kelompok = $_REQUEST['n_kelompok'];
	
	$this->view->detailKelompok = array();
	if($this->view->par == 'update'){
		$masukan = array("c_kelompok" => $this->view->c_kelompok);
		$this->view->detailKelompok = $this->refkelompok_serv->detailKelompok($masukan);
	}
	
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertkelompokAction(){
	$n_kelompok = $_POST['n_kelompok'];
	$c_kelompok = $_POST['c_kelompok'];
	
	$masukanInsert = array("n_kelompok" => $n_kelompok,
			"c_kelompok" => $c_kelompok);
	$hasil = $this->refkelompok_serv->tambahkelompok($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Kelompok Diklat Teknis';
	$this->view->status	= $hasil;
	$this->listkelompokAction();
	$this->render(listkelompok);
}

public function updatekelompokAction(){
	$n_kelompok = $_POST['n_kelompok'];
	$c_kelompok = $_POST['c_kelompok'];
	
	$masukanInsert = array("n_kelompok" => $n_kelompok,
			"c_kelompok" => $c_kelompok);
	$hasil = $this->refkelompok_serv->ubahkelompok($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Kelompok Diklat Teknis';
	$this->view->status	= $hasil;
	$this->listkelompokAction();
	$this->render(listkelompok);
}	

public function deletekelompokAction(){
	$c_kelompok = $_REQUEST['c_kelompok'];
	
	$masukanInsert = array("c_kelompok" => $c_kelompok);
	$hasil = $this->refkelompok_serv->hapuskelompok($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Kelompok Diklat Teknis';
	$this->view->status	= $hasil;
	$this->listkelompokAction();
	$this->render(listkelompok);
}

}
?>
