<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refmiliter_Service.php";

class Sdmmodule_RefmiliterController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refmiliter_serv = Refmiliter_Service::getInstance();

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
public function militerjsAction() 
{
	header('content-type : text/javascript');
	$this->render('militerjs');
}	
	
public function listmiliterAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refmiliter_serv->getMiliterList($cari, 0, 0);
	$this->view->listMiliter = $this->refmiliter_serv->getMiliterList($cari ,$currentPage,$numToDisplay );
	
}

public function militerolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_jenis_kel = $_REQUEST['c_jenis_kel'];
	$this->view->n_jenis_kel = $_REQUEST['n_jenis_kel'];
	
	$this->view->detailMiliter = array();
	if($this->view->par == 'update'){
	$masukan = array("c_jenis_kel" => $this->view->c_jenis_kel);
	$this->view->detailMiliter = $this->refmiliter_serv->detailMiliter($masukan);
	}
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertmiliterAction(){
	$c_jenis_kel = $_POST['c_jenis_kel'];
	$n_jenis_kel = $_POST['n_jenis_kel'];
	
	$masukanInsert = array("c_jenis_kel" => $c_jenis_kel,
			"n_jenis_kel" => $n_jenis_kel,
			"i_entry" => $this->userid);
	$hasil = $this->refmiliter_serv->tambahmiliter($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Militer';
	$this->view->status	= $hasil;
	$this->listmiliterAction();
	$this->render(listmiliter);
}

public function updatemiliterAction(){
	$c_jenis_kel = $_POST['c_jenis_kel'];
	$n_jenis_kel = $_POST['n_jenis_kel'];
	
	$masukanInsert = array("c_jenis_kel" => $c_jenis_kel,
			"n_jenis_kel" => $n_jenis_kel,
			"i_entry" => $this->userid);
	$hasil = $this->refmiliter_serv->ubahmiliter($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Militer';
	$this->view->status	= $hasil;
	$this->listmiliterAction();
	$this->render(listmiliter);
}	

public function deletemiliterAction(){
	$c_jenis_kel = $_REQUEST['c_jenis_kel'];
	
	$masukanInsert = array("c_jenis_kel" => $c_jenis_kel);
	$hasil = $this->refmiliter_serv->hapusmiliter($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Militer';
	$this->view->status	= $hasil;
	$this->listmiliterAction();
	$this->render(listmiliter);
}

}
?>
