<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refpenghargaan_Service.php";

class Sdmmodule_RefpenghargaanController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refpenghargaan_serv = Refpenghargaan_Service::getInstance();

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
public function penghargaanjsAction() 
{
	header('content-type : text/javascript');
	$this->render('penghargaanjs');
}	
	
public function listpenghargaanAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refpenghargaan_serv->getPenghargaanList($cari, 0, 0);
	$this->view->listPenghargaan = $this->refpenghargaan_serv->getPenghargaanList($cari ,$currentPage,$numToDisplay );
	
}

public function penghargaanolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_penghargaan = $_REQUEST['c_penghargaan'];
	$this->view->n_penghargaan = $_REQUEST['n_penghargaan'];
	
	$this->view->detailPenghargaan = array();
	if($this->view->par == 'update'){
		$masukan = array("c_penghargaan" => $this->view->c_penghargaan);
		$this->view->detailPenghargaan = $this->refpenghargaan_serv->detailPenghargaan($masukan);
	}
	
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertpenghargaanAction(){
	$n_penghargaan = $_POST['n_penghargaan'];
	$c_penghargaan = $_POST['c_penghargaan'];
	
	$masukanInsert = array("n_penghargaan" => $n_penghargaan,
			"c_penghargaan" => $c_penghargaan);
	$hasil = $this->refpenghargaan_serv->tambahpenghargaan($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Penghargaan';
	$this->view->status	= $hasil;
	$this->listpenghargaanAction();
	$this->render(listpenghargaan);
}

public function updatepenghargaanAction(){
	$n_penghargaan = $_POST['n_penghargaan'];
	$c_penghargaan = $_POST['c_penghargaan'];
	
	$masukanInsert = array("n_penghargaan" => $n_penghargaan,
			"c_penghargaan" => $c_penghargaan);
	$hasil = $this->refpenghargaan_serv->ubahpenghargaan($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Penghargaan';
	$this->view->status	= $hasil;
	$this->listpenghargaanAction();
	$this->render(listpenghargaan);
}	

public function deletepenghargaanAction(){
	$c_penghargaan = $_REQUEST['c_penghargaan'];
	
	$masukanInsert = array("c_penghargaan" => $c_penghargaan);
	$hasil = $this->refpenghargaan_serv->hapuspenghargaan($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Penghargaan';
	$this->view->status	= $hasil;
	$this->listpenghargaanAction();
	$this->render(listpenghargaan);
}

}
?>
