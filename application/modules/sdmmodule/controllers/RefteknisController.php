<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refteknis_Service.php";

class Sdmmodule_RefteknisController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refteknis_serv = Refteknis_Service::getInstance();

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
public function teknisjsAction() 
{
	header('content-type : text/javascript');
	$this->render('teknisjs');
}	
	
public function listteknisAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refteknis_serv->getTeknisList($cari, 0, 0);
	$this->view->listTeknis = $this->refteknis_serv->getTeknisList($cari ,$currentPage,$numToDisplay );
	
}

public function teknisolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_diklat_teknis = $_REQUEST['c_diklat_teknis'];
	$this->view->n_diklat_teknis = $_REQUEST['n_diklat_teknis'];
	$this->view->c_kelompok = $_REQUEST['c_kelompok'];
	$this->view->n_kelompok = $_REQUEST['n_kelompok'];
	
	$this->view->detailTeknis = array();
	if($this->view->par == 'update'){
		$masukan = array("c_diklat_teknis" => $this->view->c_diklat_teknis);
		$this->view->detailTeknis = $this->refteknis_serv->detailTeknis($masukan);
	}
	
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertteknisAction(){
	$c_diklat_teknis = $_POST['c_diklat_teknis'];
	$n_diklat_teknis = $_POST['n_diklat_teknis'];
	$c_kelompok = $_POST['c_kelompok'];
	$n_kelompok = $_POST['n_kelompok'];
	
	$masukanInsert = array("c_diklat_teknis" => $c_diklat_teknis,
			"n_diklat_teknis" => $n_diklat_teknis,
			"c_kelompok" => $c_kelompok,
			"n_kelompok" => $n_kelompok);
	$hasil = $this->refteknis_serv->tambahteknis($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Diklat Teknis';
	$this->view->status	= $hasil;
	$this->listteknisAction();
	$this->render(listteknis);
}

public function updateteknisAction(){
	$c_diklat_teknis = $_POST['c_diklat_teknis'];
	$n_diklat_teknis = $_POST['n_diklat_teknis'];
	$c_kelompok = $_POST['c_kelompok'];
	$n_kelompok = $_POST['n_kelompok'];
	
	$masukanInsert = array("c_diklat_teknis" => $c_diklat_teknis,
			"n_diklat_teknis" => $n_diklat_teknis,
			"c_kelompok" => $c_kelompok,
			"n_kelompok" => $n_kelompok);
	$hasil = $this->refteknis_serv->ubahteknis($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Diklat Teknis';
	$this->view->status	= $hasil;
	$this->listteknisAction();
	$this->render(listteknis);
}	

public function deleteteknisAction(){
	$c_diklat_teknis = $_REQUEST['c_diklat_teknis'];
	
	$masukanInsert = array("c_diklat_teknis" => $c_diklat_teknis);
	$hasil = $this->refteknis_serv->hapusteknis($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Diklat Teknis';
	$this->view->status	= $hasil;
	$this->listteknisAction();
	$this->render(listteknis);
}

}
?>
