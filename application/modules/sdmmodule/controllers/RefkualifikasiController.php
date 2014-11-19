<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refkualifikasi_Service.php";

class Sdmmodule_RefkualifikasiController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refkualifikasi_serv = Refkualifikasi_Service::getInstance();

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
public function kualifikasijsAction() 
{
	header('content-type : text/javascript');
	$this->render('kualifikasijs');
}	
	
public function listkualifikasiAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refkualifikasi_serv->getKualifikasiList($cari, 0, 0);
	$this->view->listKualifikasi = $this->refkualifikasi_serv->getKualifikasiList($cari ,$currentPage,$numToDisplay );
	
}

public function kualifikasiolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_kualifikasi = $_REQUEST['c_kualifikasi'];
	$this->view->n_kualifikasi = $_REQUEST['n_kualifikasi'];
	
	$this->view->detailKualifikasi = array();
	if($this->view->par == 'update'){
		$masukan = array("c_kualifikasi" => $this->view->c_kualifikasi);
		$this->view->detailKualifikasi = $this->refkualifikasi_serv->detailKualifikasi($masukan);
	}
	
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertkualifikasiAction(){
	$n_kualifikasi = $_POST['n_kualifikasi'];
	$c_kualifikasi = $_POST['c_kualifikasi'];
	
	$masukanInsert = array("n_kualifikasi" => $n_kualifikasi,
			"c_kualifikasi" => $c_kualifikasi);
	$hasil = $this->refkualifikasi_serv->tambahkualifikasi($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Diklat Kualifikasi';
	$this->view->status	= $hasil;
	$this->listkualifikasiAction();
	$this->render(listkualifikasi);
}

public function updatekualifikasiAction(){
	$n_kualifikasi = $_POST['n_kualifikasi'];
	$c_kualifikasi = $_POST['c_kualifikasi'];
	
	$masukanInsert = array("n_kualifikasi" => $n_kualifikasi,
			"c_kualifikasi" => $c_kualifikasi);
	$hasil = $this->refkualifikasi_serv->ubahkualifikasi($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Diklat Kualifikasi';
	$this->view->status	= $hasil;
	$this->listkualifikasiAction();
	$this->render(listkualifikasi);
}	

public function deletekualifikasiAction(){
	$c_kualifikasi = $_REQUEST['c_kualifikasi'];
	
	$masukanInsert = array("c_kualifikasi" => $c_kualifikasi);
	$hasil = $this->refkualifikasi_serv->hapuskualifikasi($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Diklat Kualifikasi';
	$this->view->status	= $hasil;
	$this->listkualifikasiAction();
	$this->render(listkualifikasi);
}

}
?>
