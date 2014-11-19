<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refbank_Service.php";

class Sdmmodule_RefbankController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refbank_serv = Refbank_Service::getInstance();

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
public function bankjsAction() 
{
	header('content-type : text/javascript');
	$this->render('bankjs');
}	
	
public function listbankAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refbank_serv->getBankList($cari, 0, 0);
	$this->view->listBank = $this->refbank_serv->getBankList($cari ,$currentPage,$numToDisplay );
	
}

public function bankolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_bank = $_REQUEST['c_bank'];
	$this->view->n_bank = $_REQUEST['n_bank'];
	
	$this->view->detailBank = array();
	if($this->view->par == 'update'){
		$masukan = array("c_bank" => $this->view->c_bank);
		$this->view->detailBank = $this->refbank_serv->detailBank($masukan);
	}
	
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertbankAction(){
	$n_bank = $_POST['n_bank'];
	$c_bank = $_POST['c_bank'];
	
	$masukanInsert = array("n_bank" => $n_bank,
			"c_bank" => $c_bank);
	$hasil = $this->refbank_serv->tambahbank($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Bank';
	$this->view->status	= $hasil;
	$this->listbankAction();
	$this->render(listbank);
}

public function updatebankAction(){
	$n_bank = $_POST['n_bank'];
	$c_bank = $_POST['c_bank'];
	
	$masukanInsert = array("n_bank" => $n_bank,
			"c_bank" => $c_bank);
	$hasil = $this->refbank_serv->ubahbank($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Bank';
	$this->view->status	= $hasil;
	$this->listbankAction();
	$this->render(listbank);
}	

public function deletebankAction(){
	$c_bank = $_REQUEST['c_bank'];
	
	$masukanInsert = array("c_bank" => $c_bank);
	$hasil = $this->refbank_serv->hapusbank($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Bank';
	$this->view->status	= $hasil;
	$this->listbankAction();
	$this->render(listbank);
}

}
?>
