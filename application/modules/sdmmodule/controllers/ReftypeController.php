<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Reftype_Service.php";

class Sdmmodule_ReftypeController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->reftype_serv = Reftype_Service::getInstance();

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
public function typejsAction() 
{
	header('content-type : text/javascript');
	$this->render('typejs');
}	
	
public function listtypeAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->reftype_serv->getTypeList($cari, 0, 0);
	$this->view->listType = $this->reftype_serv->getTypeList($cari ,$currentPage,$numToDisplay );
	
}

public function typeolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_type = $_REQUEST['c_type'];
	$this->view->n_type = $_REQUEST['n_type'];
	
	$this->view->detailType = array();
	if($this->view->par == 'update'){
		$masukan = array("c_type" => $this->view->c_type);
		$this->view->detailType = $this->reftype_serv->detailType($masukan);
	}
	
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function inserttypeAction(){
	$n_type = $_POST['n_type'];
	$c_type = $_POST['c_type'];
	
	$masukanInsert = array("n_type" => $n_type,
			"c_type" => $c_type);
	$hasil = $this->reftype_serv->tambahtype($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Type';
	$this->view->status	= $hasil;
	$this->listtypeAction();
	$this->render(listtype);
}

public function updatetypeAction(){
	$n_type = $_POST['n_type'];
	$c_type = $_POST['c_type'];
	
	$masukanInsert = array("n_type" => $n_type,
			"c_type" => $c_type);
	$hasil = $this->reftype_serv->ubahtype($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Type';
	$this->view->status	= $hasil;
	$this->listtypeAction();
	$this->render(listtype);
}	

public function deletetypeAction(){
	$c_type = $_REQUEST['c_type'];
	
	$masukanInsert = array("c_type" => $c_type);
	$hasil = $this->reftype_serv->hapustype($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Type';
	$this->view->status	= $hasil;
	$this->listtypeAction();
	$this->render(listtype);
}

}
?>
