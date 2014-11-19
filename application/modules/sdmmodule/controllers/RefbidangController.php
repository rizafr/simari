<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refbidang_Service.php";

class Sdmmodule_RefbidangController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refbidang_serv = Refbidang_Service::getInstance();

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
public function bidangjsAction() 
{
	header('content-type : text/javascript');
	$this->render('bidangjs');
}	
	
public function listbidangAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refbidang_serv->getBidangList($cari, 0, 0);
	$this->view->listBidang = $this->refbidang_serv->getBidangList($cari ,$currentPage,$numToDisplay );
	
}

public function bidangolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_bidang = $_REQUEST['c_bidang'];
	$this->view->n_bidang = $_REQUEST['n_bidang'];
	
	$this->view->detailBidang = array();
	if($this->view->par == 'update'){
		$masukan = array("c_bidang" => $this->view->c_bidang);
		$this->view->detailBidang = $this->refbidang_serv->detailBidang($masukan);
	}
	
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertbidangAction(){
	$n_bidang = $_POST['n_bidang'];
	$c_bidang = $_POST['c_bidang'];
	
	$masukanInsert = array("n_bidang" => $n_bidang,
			"c_bidang" => $c_bidang);
	$hasil = $this->refbidang_serv->tambahbidang($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Bidang';
	$this->view->status	= $hasil;
	$this->listbidangAction();
	$this->render(listbidang);
}

public function updatebidangAction(){
	$n_bidang = $_POST['n_bidang'];
	$c_bidang = $_POST['c_bidang'];
	
	$masukanInsert = array("n_bidang" => $n_bidang,
			"c_bidang" => $c_bidang);
	$hasil = $this->refbidang_serv->ubahbidang($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Bidang';
	$this->view->status	= $hasil;
	$this->listbidangAction();
	$this->render(listbidang);
}	

public function deletebidangAction(){
	$c_bidang = $_REQUEST['c_bidang'];
	
	$masukanInsert = array("c_bidang" => $c_bidang);
	$hasil = $this->refbidang_serv->hapusbidang($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Bidang';
	$this->view->status	= $hasil;
	$this->listbidangAction();
	$this->render(listbidang);
}

}
?>
