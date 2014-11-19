<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Reffungsional_Service.php";

class Sdmmodule_ReffungsionalController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->reffungsional_serv = Reffungsional_Service::getInstance();

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
public function fungsionaljsAction() 
{
	header('content-type : text/javascript');
	$this->render('fungsionaljs');
}	
	
public function listfungsionalAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->reffungsional_serv->getFungsionalList($cari, 0, 0);
	$this->view->listFungsional = $this->reffungsional_serv->getFungsionalList($cari ,$currentPage,$numToDisplay );
	
}

public function fungsionalolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_fungsional = $_REQUEST['c_fungsional'];
	$this->view->n_fungsional = $_REQUEST['n_fungsional'];
	
	$this->view->detailFungsional = array();
	if($this->view->par == 'update'){
		$masukan = array("c_fungsional" => $this->view->c_fungsional);
		$this->view->detailFungsional = $this->reffungsional_serv->detailFungsional($masukan);
	}
	
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertfungsionalAction(){
	$n_fungsional = $_POST['n_fungsional'];
	$c_fungsional = $_POST['c_fungsional'];
	
	$masukanInsert = array("n_fungsional" => $n_fungsional,
			"c_fungsional" => $c_fungsional);
	$hasil = $this->reffungsional_serv->tambahfungsional($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Jenis Dikfungsional';
	$this->view->status	= $hasil;
	$this->listfungsionalAction();
	$this->render(listfungsional);
}

public function updatefungsionalAction(){
	$n_fungsional = $_POST['n_fungsional'];
	$c_fungsional = $_POST['c_fungsional'];
	
	$masukanInsert = array("n_fungsional" => $n_fungsional,
			"c_fungsional" => $c_fungsional);
	$hasil = $this->reffungsional_serv->ubahfungsional($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Jenis Dikfungsional';
	$this->view->status	= $hasil;
	$this->listfungsionalAction();
	$this->render(listfungsional);
}	

public function deletefungsionalAction(){
	$c_fungsional = $_REQUEST['c_fungsional'];
	
	$masukanInsert = array("c_fungsional" => $c_fungsional);
	$hasil = $this->reffungsional_serv->hapusfungsional($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Jenis Dikfungsional';
	$this->view->status	= $hasil;
	$this->listfungsionalAction();
	$this->render(listfungsional);
}

}
?>
