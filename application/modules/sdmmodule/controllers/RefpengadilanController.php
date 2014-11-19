<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refpengadilan_Service.php";

class Sdmmodule_RefpengadilanController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refpengadilan_serv = Refpengadilan_Service::getInstance();

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
public function pengadilanjsAction() 
{
	header('content-type : text/javascript');
	$this->render('pengadilanjs');
}	
	
public function listpengadilanAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refpengadilan_serv->getPengadilanList($cari, 0, 0);
	$this->view->listPengadilan = $this->refpengadilan_serv->getPengadilanList($cari ,$currentPage,$numToDisplay );
	
}

public function pengadilanolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_pengadilan = $_REQUEST['c_pengadilan'];
	$this->view->n_pengadilan = $_REQUEST['n_pengadilan'];
	$this->view->c_wilayah = $_REQUEST['c_wilayah'];
	$this->view->n_wilayah = $_REQUEST['n_wilayah'];
	
	$this->view->detailPengadilan = array();
	if($this->view->par == 'update'){
		$masukan = array("c_pengadilan" => $this->view->c_pengadilan);
		$this->view->detailPengadilan = $this->refpengadilan_serv->detailPengadilan($masukan);
	}
	
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertpengadilanAction(){
	$c_pengadilan = $_POST['c_pengadilan'];
	$n_pengadilan = $_POST['n_pengadilan'];
	$c_wilayah = $_POST['c_wilayah'];
	$n_wilayah = $_POST['n_wilayah'];
	
	$masukanInsert = array("c_pengadilan" => $c_pengadilan,
			"n_pengadilan" => $n_pengadilan,
			"c_wilayah" => $c_wilayah,
			"n_wilayah" => $n_wilayah);
	$hasil = $this->refpengadilan_serv->tambahpengadilan($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Pengadilan';
	$this->view->status	= $hasil;
	$this->listpengadilanAction();
	$this->render(listpengadilan);
}

public function updatepengadilanAction(){
	$c_pengadilan = $_POST['c_pengadilan'];
	$n_pengadilan = $_POST['n_pengadilan'];
	$c_wilayah = $_POST['c_wilayah'];
	$n_wilayah = $_POST['n_wilayah'];
	
	$masukanInsert = array("c_pengadilan" => $c_pengadilan,
			"n_pengadilan" => $n_pengadilan,
			"c_wilayah" => $c_wilayah,
			"n_wilayah" => $n_wilayah);
	$hasil = $this->refpengadilan_serv->ubahpengadilan($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Pengadilan';
	$this->view->status	= $hasil;
	$this->listpengadilanAction();
	$this->render(listpengadilan);
}	

public function deletepengadilanAction(){
	$c_pengadilan = $_REQUEST['c_pengadilan'];
	
	$masukanInsert = array("c_pengadilan" => $c_pengadilan);
	$hasil = $this->refpengadilan_serv->hapuspengadilan($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Pengadilan';
	$this->view->status	= $hasil;
	$this->listpengadilanAction();
	$this->render(listpengadilan);
}

}
?>
