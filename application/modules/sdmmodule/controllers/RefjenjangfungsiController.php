<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refjenjangfungsi_Service.php";

class Sdmmodule_RefjenjangfungsiController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refjenjangfungsi_serv = Refjenjangfungsi_Service::getInstance();

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
  			$this->c_jenjang_fungsional_unitkerja = $ssologin->c_jenjang_fungsional_unitkerja; 
  			$this->c_satker			= $ssologin->c_satker; 
  			$this->n_satker			= $ssologin->n_satker; 
  			$this->c_izin			= $ssologin->view->userIzin; 
  			$this->checkotoritas	= $ssologin->view->checkotoritas; 
  		}	
    }
	
    public function indexAction() {
	   
    }
public function jenjangfungsijsAction() 
{
	header('content-type : text/javascript');
	$this->render('jenjangfungsijs');
}	
	
public function listjenjangfungsiAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refjenjangfungsi_serv->getJenjangfungsiList($cari, 0, 0);
	$this->view->listJenjangfungsi = $this->refjenjangfungsi_serv->getJenjangfungsiList($cari ,$currentPage,$numToDisplay );
	
}

public function jenjangfungsiolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_jenjang_fungsional = $_REQUEST['c_jenjang_fungsional'];
	$this->view->n_jenjang_fungsional = $_REQUEST['n_jenjang_fungsional'];
	$this->view->c_fungsional = $_REQUEST['c_fungsional'];
	
	$this->view->detailJenjangfungsi = array();
	if($this->view->par == 'update'){
		$masukan = array("c_jenjang_fungsional" => $this->view->c_jenjang_fungsional);
		$this->view->detailJenjangfungsi = $this->refjenjangfungsi_serv->detailJenjangfungsi($masukan);
	}
	
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertjenjangfungsiAction(){
	$n_jenjang_fungsional = $_POST['n_jenjang_fungsional'];
	$c_jenjang_fungsional = $_POST['c_jenjang_fungsional'];
	$c_fungsional = $_POST['c_fungsional'];
	
	$masukanInsert = array("n_jenjang_fungsional" => $n_jenjang_fungsional,
			"c_jenjang_fungsional" => $c_jenjang_fungsional,
			"c_fungsional" => $c_fungsional);
	$hasil = $this->refjenjangfungsi_serv->tambahjenjangfungsi($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Penjenjangan Fungsional';
	$this->view->status	= $hasil;
	$this->listjenjangfungsiAction();
	$this->render(listjenjangfungsi);
}

public function updatejenjangfungsiAction(){
	$n_jenjang_fungsional = $_POST['n_jenjang_fungsional'];
	$c_jenjang_fungsional = $_POST['c_jenjang_fungsional'];
	$c_fungsional = $_POST['c_fungsional'];
	
	$masukanInsert = array("n_jenjang_fungsional" => $n_jenjang_fungsional,
			"c_jenjang_fungsional" => $c_jenjang_fungsional,
			"c_fungsional" => $c_fungsional);
	$hasil = $this->refjenjangfungsi_serv->ubahjenjangfungsi($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Penjenjangan Fungsional';
	$this->view->status	= $hasil;
	$this->listjenjangfungsiAction();
	$this->render(listjenjangfungsi);
}	

public function deletejenjangfungsiAction(){
	$c_jenjang_fungsional = $_REQUEST['c_jenjang_fungsional'];
	
	$masukanInsert = array("c_jenjang_fungsional" => $c_jenjang_fungsional);
	$hasil = $this->refjenjangfungsi_serv->hapusjenjangfungsi($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Penjenjangan Fungsional';
	$this->view->status	= $hasil;
	$this->listjenjangfungsiAction();
	$this->render(listjenjangfungsi);
}

}
?>
