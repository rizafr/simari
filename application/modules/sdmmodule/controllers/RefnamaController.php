<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refnama_Service.php";

class Sdmmodule_RefnamaController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refnama_serv = Refnama_Service::getInstance();

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
  			$this->c_jenjang_unitkerja = $ssologin->c_jenjang_unitkerja; 
  			$this->c_satker			= $ssologin->c_satker; 
  			$this->n_satker			= $ssologin->n_satker; 
  			$this->c_izin			= $ssologin->view->userIzin; 
  			$this->checkotoritas	= $ssologin->view->checkotoritas; 
  		}	
    }
	
    public function indexAction() {
	   
    }
public function namajsAction() 
{
	header('content-type : text/javascript');
	$this->render('namajs');
}	
	
public function listnamaAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refnama_serv->getNamaList($cari, 0, 0);
	$this->view->listNama = $this->refnama_serv->getNamaList($cari ,$currentPage,$numToDisplay );
	
}

public function namaolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_jenjang = $_REQUEST['c_jenjang'];
	$this->view->n_jenjang = $_REQUEST['n_jenjang'];
	$this->view->c_jenis = $_REQUEST['c_jenis'];
	
	$this->view->detailNama = array();
	if($this->view->par == 'update'){
		$masukan = array("c_jenjang" => $this->view->c_jenjang);
		$this->view->detailNama = $this->refnama_serv->detailNama($masukan);
	}
	
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertnamaAction(){
	$n_jenjang = $_POST['n_jenjang'];
	$c_jenjang = $_POST['c_jenjang'];
	$c_jenis = $_POST['c_jenis'];
	
	$masukanInsert = array("n_jenjang" => $n_jenjang,
			"c_jenjang" => $c_jenjang,
			"c_jenis" => $c_jenis);
	$hasil = $this->refnama_serv->tambahnama($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Nama Penjenjangan';
	$this->view->status	= $hasil;
	$this->listnamaAction();
	$this->render(listnama);
}

public function updatenamaAction(){
	$n_jenjang = $_POST['n_jenjang'];
	$c_jenjang = $_POST['c_jenjang'];
	$c_jenis = $_POST['c_jenis'];
	
	$masukanInsert = array("n_jenjang" => $n_jenjang,
			"c_jenjang" => $c_jenjang,
			"c_jenis" => $c_jenis);
	$hasil = $this->refnama_serv->ubahnama($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Nama Penjenjangan';
	$this->view->status	= $hasil;
	$this->listnamaAction();
	$this->render(listnama);
}	

public function deletenamaAction(){
	$c_jenjang = $_REQUEST['c_jenjang'];
	
	$masukanInsert = array("c_jenjang" => $c_jenjang);
	$hasil = $this->refnama_serv->hapusnama($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Nama Penjenjangan';
	$this->view->status	= $hasil;
	$this->listnamaAction();
	$this->render(listnama);
}

}
?>
