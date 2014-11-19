<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refpangkat_Service.php";

class Sdmmodule_RefpangkatController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refpangkat_serv = Refpangkat_Service::getInstance();

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
public function pangkatjsAction() 
{
	header('content-type : text/javascript');
	$this->render('pangkatjs');
}	
	
public function listpangkatAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refpangkat_serv->getPangkatList($cari, 0, 0);
	$this->view->listPangkat = $this->refpangkat_serv->getPangkatList($cari ,$currentPage,$numToDisplay );
	
}

public function pangkatolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_jns_kepangkatan = $_REQUEST['c_jns_kepangkatan'];
	$this->view->n_jns_kepangkatan = $_REQUEST['n_jns_kepangkatan'];
	
	$this->view->detailPangkat = array();
	if($this->view->par == 'update'){
		$masukan = array("c_jns_kepangkatan" => $this->view->c_jns_kepangkatan);
		$this->view->detailPangkat = $this->refpangkat_serv->detailPangkat($masukan);
	}
	
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertpangkatAction(){
	$c_jns_kepangkatan = $_POST['c_jns_kepangkatan'];
	$n_jns_kepangkatan = $_POST['n_jns_kepangkatan'];
	
	$masukanInsert = array("c_jns_kepangkatan" => $c_jns_kepangkatan,
			"n_jns_kepangkatan" => $n_jns_kepangkatan);
	$hasil = $this->refpangkat_serv->tambahpangkat($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Jenis Kepangkatan';
	$this->view->status	= $hasil;
	$this->listpangkatAction();
	$this->render(listpangkat);
}

public function updatepangkatAction(){
	$c_jns_kepangkatan = $_POST['c_jns_kepangkatan'];
	$n_jns_kepangkatan = $_POST['n_jns_kepangkatan'];
	
	$masukanInsert = array("c_jns_kepangkatan" => $c_jns_kepangkatan,
			"n_jns_kepangkatan" => $n_jns_kepangkatan);
	$hasil = $this->refpangkat_serv->ubahpangkat($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Jenis Kepangkatan';
	$this->view->status	= $hasil;
	$this->listpangkatAction();
	$this->render(listpangkat);
}	

public function deletepangkatAction(){
	$c_jns_kepangkatan = $_REQUEST['c_jns_kepangkatan'];
	
	$masukanInsert = array("c_jns_kepangkatan" => $c_jns_kepangkatan);
	$hasil = $this->refpangkat_serv->hapuspangkat($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Jenis Kepangkatan';
	$this->view->status	= $hasil;
	$this->listpangkatAction();
	$this->render(listpangkat);
}

}
?>
