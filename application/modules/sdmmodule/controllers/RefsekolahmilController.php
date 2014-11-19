<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refsekolahmil_Service.php";

class Sdmmodule_RefsekolahmilController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refsekolahmil_serv = Refsekolahmil_Service::getInstance();

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
public function sekolahmiljsAction() 
{
	header('content-type : text/javascript');
	$this->render('sekolahmiljs');
}	
	
public function listsekolahmilAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refsekolahmil_serv->getSekolahmilList($cari, 0, 0);
	$this->view->listSekolahmil = $this->refsekolahmil_serv->getSekolahmilList($cari ,$currentPage,$numToDisplay );
	
}

public function sekolahmilolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_sekolahmil = $_REQUEST['c_sekolahmil'];
	$this->view->n_sekolahmil = $_REQUEST['n_sekolahmil'];
	
	$this->view->detailSekolahmil = array();
	if($this->view->par == 'update'){
	$masukan = array("c_sekolahmil" => $this->view->c_sekolahmil);
	$this->view->detailSekolahmil = $this->refsekolahmil_serv->detailSekolahmil($masukan);
	}
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertsekolahmilAction(){
	$c_sekolahmil = $_POST['c_sekolahmil'];
	$n_sekolahmil = $_POST['n_sekolahmil'];
	
	$masukanInsert = array("c_sekolahmil" => $c_sekolahmil,
			"n_sekolahmil" => $n_sekolahmil,
			"i_entry" => $this->userid);
	$hasil = $this->refsekolahmil_serv->tambahsekolahmil($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Sekolah Militer';
	$this->view->status	= $hasil;
	$this->listsekolahmilAction();
	$this->render(listsekolahmil);
}

public function updatesekolahmilAction(){
	$c_sekolahmil = $_POST['c_sekolahmil'];
	$n_sekolahmil = $_POST['n_sekolahmil'];
	
	$masukanInsert = array("c_sekolahmil" => $c_sekolahmil,
			"n_sekolahmil" => $n_sekolahmil,
			"i_entry" => $this->userid);
	$hasil = $this->refsekolahmil_serv->ubahsekolahmil($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Sekolah Militer';
	$this->view->status	= $hasil;
	$this->listsekolahmilAction();
	$this->render(listsekolahmil);
}	

public function deletesekolahmilAction(){
	$c_sekolahmil = $_REQUEST['c_sekolahmil'];
	
	$masukanInsert = array("c_sekolahmil" => $c_sekolahmil);
	$hasil = $this->refsekolahmil_serv->hapussekolahmil($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Sekolah Militer';
	$this->view->status	= $hasil;
	$this->listsekolahmilAction();
	$this->render(listsekolahmil);
}

}
?>
