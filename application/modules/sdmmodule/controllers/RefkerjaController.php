<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refkerja_Service.php";

class Sdmmodule_RefkerjaController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refkerja_serv = Refkerja_Service::getInstance();

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
public function kerjajsAction() 
{
	header('content-type : text/javascript');
	$this->render('kerjajs');
}	
	
public function listkerjaAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refkerja_serv->getKerjaList($cari, 0, 0);
	$this->view->listKerja = $this->refkerja_serv->getKerjaList($cari ,$currentPage,$numToDisplay );
	
}

public function kerjaolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_kerja = $_REQUEST['c_kerja'];
	$this->view->n_kerja = $_REQUEST['n_kerja'];
	
	$this->view->detailKerja = array();
	if($this->view->par == 'update'){
		$masukan = array("c_kerja" => $this->view->c_kerja);
		$this->view->detailKerja = $this->refkerja_serv->detailKerja($masukan);
	}
	
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertkerjaAction(){
	$n_kerja = $_POST['n_kerja'];
	$c_kerja = $_POST['c_kerja'];
	
	$masukanInsert = array("n_kerja" => $n_kerja,
			"c_kerja" => $c_kerja);
	$hasil = $this->refkerja_serv->tambahkerja($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Kerja';
	$this->view->status	= $hasil;
	$this->listkerjaAction();
	$this->render(listkerja);
}

public function updatekerjaAction(){
	$n_kerja = $_POST['n_kerja'];
	$c_kerja = $_POST['c_kerja'];
	
	$masukanInsert = array("n_kerja" => $n_kerja,
			"c_kerja" => $c_kerja);
	$hasil = $this->refkerja_serv->ubahkerja($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Kerja';
	$this->view->status	= $hasil;
	$this->listkerjaAction();
	$this->render(listkerja);
}	

public function deletekerjaAction(){
	$c_kerja = $_REQUEST['c_kerja'];
	
	$masukanInsert = array("c_kerja" => $c_kerja);
	$hasil = $this->refkerja_serv->hapuskerja($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Kerja';
	$this->view->status	= $hasil;
	$this->listkerjaAction();
	$this->render(listkerja);
}

}
?>
