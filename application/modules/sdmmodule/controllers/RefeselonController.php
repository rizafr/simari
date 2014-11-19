<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refeselon_Service.php";

class Sdmmodule_RefeselonController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refeselon_serv = Refeselon_Service::getInstance();

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
public function eselonjsAction() 
{
	header('content-type : text/javascript');
	$this->render('eselonjs');
}	
	
public function listeselonAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refeselon_serv->getEselonList($cari, 0, 0);
	$this->view->listEselon = $this->refeselon_serv->getEselonList($cari ,$currentPage,$numToDisplay );
	
}

public function eselonolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_eselon = $_REQUEST['c_eselon'];
	$this->view->n_eselon = $_REQUEST['n_eselon'];
	$this->view->n_eselonb = $_REQUEST['n_eselonb'];
	
	$this->view->detailEselon = array();
	if($this->view->par == 'update'){
		$masukan = array("c_eselon" => $this->view->c_eselon);
		$this->view->detailEselon = $this->refeselon_serv->detailEselon($masukan);
	}
	
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function inserteselonAction(){
	$n_eselon = $_POST['n_eselon'];
	$c_eselon = $_POST['c_eselon'];
	$n_eselonb = $_POST['n_eselonb'];
	
	$masukanInsert = array("n_eselon" => $n_eselon,
			"c_eselon" => $c_eselon,
			"n_eselonb" => $n_eselonb);
	$hasil = $this->refeselon_serv->tambaheselon($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Eselon';
	$this->view->status	= $hasil;
	$this->listeselonAction();
	$this->render(listeselon);
}

public function updateeselonAction(){
	$n_eselon = $_POST['n_eselon'];
	$c_eselon = $_POST['c_eselon'];
	$n_eselonb = $_POST['n_eselonb'];
	
	$masukanInsert = array("n_eselon" => $n_eselon,
			"c_eselon" => $c_eselon,
			"n_eselonb" => $n_eselonb);
	$hasil = $this->refeselon_serv->ubaheselon($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Eselon';
	$this->view->status	= $hasil;
	$this->listeselonAction();
	$this->render(listeselon);
}	

public function deleteeselonAction(){
	$c_eselon = $_REQUEST['c_eselon'];
	
	$masukanInsert = array("c_eselon" => $c_eselon);
	$hasil = $this->refeselon_serv->hapuseselon($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Eselon';
	$this->view->status	= $hasil;
	$this->listeselonAction();
	$this->render(listeselon);
}

}
?>
