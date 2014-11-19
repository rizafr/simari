<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refcuti_Service.php";

class Sdmmodule_RefcutiController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refcuti_serv = Refcuti_Service::getInstance();

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
public function cutijsAction() 
{
	header('content-type : text/javascript');
	$this->render('cutijs');
}	
	
public function listcutiAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refcuti_serv->getCutiList($cari, 0, 0);
	$this->view->listCuti = $this->refcuti_serv->getCutiList($cari ,$currentPage,$numToDisplay );
	
}

public function cutiolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_cuti = $_REQUEST['c_cuti'];
	$this->view->n_cuti = $_REQUEST['n_cuti'];
	
	$masukan = array("c_cuti" => $this->view->c_cuti);
	$this->view->detailCuti = $this->refcuti_serv->detailCuti($masukan);
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertcutiAction(){
	$n_cuti = $_POST['n_cuti'];
	$c_cuti = $_POST['c_cuti'];
	
	$masukanInsert = array("n_cuti" => $n_cuti,
			"c_cuti" => $c_cuti,
			"i_entry" => $this->userid);
	$hasil = $this->refcuti_serv->tambahcuti($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Cuti Pegawai';
	$this->view->status	= $hasil;
	$this->listcutiAction();
	$this->render(listcuti);
}

public function updatecutiAction(){
	$n_cuti = $_POST['n_cuti'];
	$c_cuti = $_POST['c_cuti'];
	
	$masukanInsert = array("n_cuti" => $n_cuti,
			"c_cuti" => $c_cuti,
			"i_entry" => $this->userid);
	$hasil = $this->refcuti_serv->ubahcuti($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Cuti Pegawai';
	$this->view->status	= $hasil;
	$this->listcutiAction();
	$this->render(listcuti);
}	

public function deletecutiAction(){
	$c_cuti = $_REQUEST['c_cuti'];
	
	$masukanInsert = array("c_cuti" => $c_cuti,
			"i_entry" => $this->userid);
	$hasil = $this->refcuti_serv->hapuscuti($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Cuti Pegawai';
	$this->view->status	= $hasil;
	$this->listcutiAction();
	$this->render(listcuti);
}

}
?>
