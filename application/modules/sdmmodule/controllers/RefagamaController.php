<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refagama_Service.php";

class Sdmmodule_RefagamaController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refagama_serv = Refagama_Service::getInstance();

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
public function agamajsAction() 
{
	header('content-type : text/javascript');
	$this->render('agamajs');
}	
	
public function listagamaAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refagama_serv->getAgamaList($cari, 0, 0);
	$this->view->listAgama = $this->refagama_serv->getAgamaList($cari ,$currentPage,$numToDisplay );
	
}

public function agamaolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_agama = $_REQUEST['c_agama'];
	$this->view->n_agama = $_REQUEST['n_agama'];
	
	$masukan = array("c_agama" => $this->view->c_agama);
	$this->view->detailAgama = $this->refagama_serv->detailAgama($masukan);
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertagamaAction(){
	$n_agama = $_POST['n_agama'];
	$c_agama = $_POST['c_agama'];
	
	$masukanInsert = array("n_agama" => $n_agama,
			"c_agama" => $c_agama,
			"i_entry" => $this->userid);
	$hasil = $this->refagama_serv->tambahagama($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Agama';
	$this->view->status	= $hasil;
	$this->listagamaAction();
	$this->render(listagama);
}

public function updateagamaAction(){
	$n_agama = $_POST['n_agama'];
	$c_agama = $_POST['c_agama'];
	
	$masukanInsert = array("n_agama" => $n_agama,
			"c_agama" => $c_agama,
			"i_entry" => $this->userid);
	$hasil = $this->refagama_serv->ubahagama($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Agama';
	$this->view->status	= $hasil;
	$this->listagamaAction();
	$this->render(listagama);
}	

public function deleteagamaAction(){
	$c_agama = $_REQUEST['c_agama'];
	
	$masukanInsert = array("c_agama" => $c_agama,
			"i_entry" => $this->userid);
	$hasil = $this->refagama_serv->hapusagama($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Agama';
	$this->view->status	= $hasil;
	$this->listagamaAction();
	$this->render(listagama);
}

}
?>
