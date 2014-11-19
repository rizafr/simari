<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refstatuskepegawaian_Service.php";

class Sdmmodule_RefstatuskepegawaianController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refstatuskepegawaian_serv = Refstatuskepegawaian_Service::getInstance();

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
  			$this->c_status_kepegawaian_unitkerja = $ssologin->c_status_kepegawaian_unitkerja; 
  			$this->c_satker			= $ssologin->c_satker; 
  			$this->n_satker			= $ssologin->n_satker; 
  			$this->c_izin			= $ssologin->view->userIzin; 
  			$this->checkotoritas	= $ssologin->view->checkotoritas; 
  		}	
    }
	
    public function indexAction() {
	   
    }
public function statuskepegawaianjsAction() 
{
	header('content-type : text/javascript');
	$this->render('statuskepegawaianjs');
}	
	
public function liststatuskepegawaianAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refstatuskepegawaian_serv->getStatuskepegawaianList($cari, 0, 0);
	$this->view->listStatuskepegawaian = $this->refstatuskepegawaian_serv->getStatuskepegawaianList($cari ,$currentPage,$numToDisplay );
	
}

public function statuskepegawaianolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_status_kepegawaian = $_REQUEST['c_status_kepegawaian'];
	$this->view->n_status_kepegawaian = $_REQUEST['n_status_kepegawaian'];
	$this->view->c_filter = $_REQUEST['c_filter'];
	
	$this->view->detailStatuskepegawaian = array();
	if($this->view->par == 'update'){
		$masukan = array("c_status_kepegawaian" => $this->view->c_status_kepegawaian);
		$this->view->detailStatuskepegawaian = $this->refstatuskepegawaian_serv->detailStatuskepegawaian($masukan);
	}
	
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertstatuskepegawaianAction(){
	$n_status_kepegawaian = $_POST['n_status_kepegawaian'];
	$c_status_kepegawaian = $_POST['c_status_kepegawaian'];
	$c_filter = $_POST['c_filter'];
	
	$masukanInsert = array("n_status_kepegawaian" => $n_status_kepegawaian,
			"c_status_kepegawaian" => $c_status_kepegawaian,
			"c_filter" => $c_filter);
	$hasil = $this->refstatuskepegawaian_serv->tambahstatuskepegawaian($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Status Kepegawaian';
	$this->view->status	= $hasil;
	$this->liststatuskepegawaianAction();
	$this->render(liststatuskepegawaian);
}

public function updatestatuskepegawaianAction(){
	$n_status_kepegawaian = $_POST['n_status_kepegawaian'];
	$c_status_kepegawaian = $_POST['c_status_kepegawaian'];
	$c_filter = $_POST['c_filter'];
	
	$masukanInsert = array("n_status_kepegawaian" => $n_status_kepegawaian,
			"c_status_kepegawaian" => $c_status_kepegawaian,
			"c_filter" => $c_filter);
	$hasil = $this->refstatuskepegawaian_serv->ubahstatuskepegawaian($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Status Kepegawaian';
	$this->view->status	= $hasil;
	$this->liststatuskepegawaianAction();
	$this->render(liststatuskepegawaian);
}	

public function deletestatuskepegawaianAction(){
	$c_status_kepegawaian = $_REQUEST['c_status_kepegawaian'];
	
	$masukanInsert = array("c_status_kepegawaian" => $c_status_kepegawaian);
	$hasil = $this->refstatuskepegawaian_serv->hapusstatuskepegawaian($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Status Kepegawaian';
	$this->view->status	= $hasil;
	$this->liststatuskepegawaianAction();
	$this->render(liststatuskepegawaian);
}

}
?>
