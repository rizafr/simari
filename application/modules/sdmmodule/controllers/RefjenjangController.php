<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refjenjang_Service.php";

class Sdmmodule_RefjenjangController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refjenjang_serv = Refjenjang_Service::getInstance();

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
public function jenjangjsAction() 
{
	header('content-type : text/javascript');
	$this->render('jenjangjs');
}	
	
public function listjenjangAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refjenjang_serv->getJenjangList($cari, 0, 0);
	$this->view->listJenjang = $this->refjenjang_serv->getJenjangList($cari ,$currentPage,$numToDisplay );
	
}

public function jenjangolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_jenjang = $_REQUEST['c_jenjang'];
	$this->view->n_jenjang = $_REQUEST['n_jenjang'];
	$this->view->q_level = $_REQUEST['q_level'];
	
	$this->view->detailJenjang = array();
	if($this->view->par == 'update'){
		$masukan = array("c_jenjang" => $this->view->c_jenjang);
		$this->view->detailJenjang = $this->refjenjang_serv->detailJenjang($masukan);
	}
	
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertjenjangAction(){
	$n_jenjang = $_POST['n_jenjang'];
	$c_jenjang = $_POST['c_jenjang'];
	$q_level = $_POST['q_level'];
	
	$masukanInsert = array("n_jenjang" => $n_jenjang,
			"c_jenjang" => $c_jenjang,
			"q_level" => $q_level);
	$hasil = $this->refjenjang_serv->tambahjenjang($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Diklat Penjenjangan';
	$this->view->status	= $hasil;
	$this->listjenjangAction();
	$this->render(listjenjang);
}

public function updatejenjangAction(){
	$n_jenjang = $_POST['n_jenjang'];
	$c_jenjang = $_POST['c_jenjang'];
	$q_level = $_POST['q_level'];
	
	$masukanInsert = array("n_jenjang" => $n_jenjang,
			"c_jenjang" => $c_jenjang,
			"q_level" => $q_level);
	$hasil = $this->refjenjang_serv->ubahjenjang($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Diklat Penjenjangan';
	$this->view->status	= $hasil;
	$this->listjenjangAction();
	$this->render(listjenjang);
}	

public function deletejenjangAction(){
	$c_jenjang = $_REQUEST['c_jenjang'];
	
	$masukanInsert = array("c_jenjang" => $c_jenjang);
	$hasil = $this->refjenjang_serv->hapusjenjang($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Diklat Penjenjangan';
	$this->view->status	= $hasil;
	$this->listjenjangAction();
	$this->render(listjenjang);
}

}
?>
