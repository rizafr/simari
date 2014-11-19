<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refdokumen_Service.php";

class Sdmmodule_RefdokumenController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refdokumen_serv = Refdokumen_Service::getInstance();

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
public function dokumenjsAction() 
{
	header('content-type : text/javascript');
	$this->render('dokumenjs');
}	
	
public function listdokumenAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refdokumen_serv->getDokumenList($cari, 0, 0);
	$this->view->listDokumen = $this->refdokumen_serv->getDokumenList($cari ,$currentPage,$numToDisplay );
	
}

public function dokumenolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_dokumen = $_REQUEST['c_dokumen'];
	$this->view->n_dokumen = $_REQUEST['n_dokumen'];
	
	$masukan = array("c_dokumen" => $this->view->c_dokumen);
	$this->view->detailDokumen = $this->refdokumen_serv->detailDokumen($masukan);
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertdokumenAction(){
	$n_dokumen = $_POST['n_dokumen'];
	$c_dokumen = $_POST['c_dokumen'];
	
	$masukanInsert = array("n_dokumen" => $n_dokumen,
			"c_dokumen" => $c_dokumen,
			"i_entry" => $this->userid);
	$hasil = $this->refdokumen_serv->tambahdokumen($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Jenis Dokumen';
	$this->view->status	= $hasil;
	$this->listdokumenAction();
	$this->render(listdokumen);
}

public function updatedokumenAction(){
	$n_dokumen = $_POST['n_dokumen'];
	$c_dokumen = $_POST['c_dokumen'];
	
	$masukanInsert = array("n_dokumen" => $n_dokumen,
			"c_dokumen" => $c_dokumen,
			"i_entry" => $this->userid);
	$hasil = $this->refdokumen_serv->ubahdokumen($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Jenis Dokumen';
	$this->view->status	= $hasil;
	$this->listdokumenAction();
	$this->render(listdokumen);
}	

public function deletedokumenAction(){
	$c_dokumen = $_REQUEST['c_dokumen'];
	
	$masukanInsert = array("c_dokumen" => $c_dokumen);
	$hasil = $this->refdokumen_serv->hapusdokumen($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Jenis Dokumen';
	$this->view->status	= $hasil;
	$this->listdokumenAction();
	$this->render(listdokumen);
}

}
?>
