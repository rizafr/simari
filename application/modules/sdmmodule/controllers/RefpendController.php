<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refpend_Service.php";

class Sdmmodule_RefpendController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refpend_serv = Refpend_Service::getInstance();

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
  			$this->c_pend_unitkerja = $ssologin->c_pend_unitkerja; 
  			$this->c_satker			= $ssologin->c_satker; 
  			$this->n_satker			= $ssologin->n_satker; 
  			$this->c_izin			= $ssologin->view->userIzin; 
  			$this->checkotoritas	= $ssologin->view->checkotoritas; 
  		}	
    }
	
    public function indexAction() {
	   
    }
public function pendjsAction() 
{
	header('content-type : text/javascript');
	$this->render('pendjs');
}	
	
public function listpendAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refpend_serv->getPendList($cari, 0, 0);
	$this->view->listPend = $this->refpend_serv->getPendList($cari ,$currentPage,$numToDisplay );
	
}

public function pendolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_pend = $_REQUEST['c_pend'];
	$this->view->n_pend = $_REQUEST['n_pend'];
	$this->view->c_pend_jenis = $_REQUEST['c_pend_jenis'];
	
	$this->view->detailPend = array();
	if($this->view->par == 'update'){
		$masukan = array("c_pend" => $this->view->c_pend);
		$this->view->detailPend = $this->refpend_serv->detailPend($masukan);
	}
	
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertpendAction(){
	$n_pend = $_POST['n_pend'];
	$c_pend = $_POST['c_pend'];
	$c_pend_jenis = $_POST['c_pend_jenis'];
	
	$masukanInsert = array("n_pend" => $n_pend,
			"c_pend" => $c_pend,
			"c_pend_jenis" => $c_pend_jenis,
			"i_entry" => $this->userid);
	$hasil = $this->refpend_serv->tambahpend($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Pendidikan';
	$this->view->status	= $hasil;
	$this->listpendAction();
	$this->render(listpend);
}

public function updatependAction(){
	$n_pend = $_POST['n_pend'];
	$c_pend = $_POST['c_pend'];
	$c_pend_jenis = $_POST['c_pend_jenis'];
	
	$masukanInsert = array("n_pend" => $n_pend,
			"c_pend" => $c_pend,
			"c_pend_jenis" => $c_pend_jenis,
			"i_entry" => $this->userid);
	$hasil = $this->refpend_serv->ubahpend($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Pendidikan';
	$this->view->status	= $hasil;
	$this->listpendAction();
	$this->render(listpend);
}	

public function deletependAction(){
	$c_pend = $_REQUEST['c_pend'];
	
	$masukanInsert = array("c_pend" => $c_pend);
	$hasil = $this->refpend_serv->hapuspend($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Pendidikan';
	$this->view->status	= $hasil;
	$this->listpendAction();
	$this->render(listpend);
}

}
?>
