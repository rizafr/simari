<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refpropinsi_Service.php";

class Sdmmodule_RefpropinsiController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refpropinsi_serv = Refpropinsi_Service::getInstance();

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
public function propinsijsAction() 
{
	header('content-type : text/javascript');
	$this->render('propinsijs');
}	
	
public function listpropinsiAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refpropinsi_serv->getPropinsiList($cari, 0, 0);
	$this->view->listPropinsi = $this->refpropinsi_serv->getPropinsiList($cari ,$currentPage,$numToDisplay );
	
}

public function propinsiolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_propinsi = $_REQUEST['c_propinsi'];
	$this->view->n_propinsi = $_REQUEST['n_propinsi'];
	
	$this->view->detailPropinsi = array();
	if($this->view->par == 'update'){
	$masukan = array("c_propinsi" => $this->view->c_propinsi);
	$this->view->detailPropinsi = $this->refpropinsi_serv->detailPropinsi($masukan);
	}
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertpropinsiAction(){
	$c_propinsi = $_POST['c_propinsi'];
	$n_propinsi = $_POST['n_propinsi'];
	
	$masukanInsert = array("c_propinsi" => $c_propinsi,
			"n_propinsi" => $n_propinsi,
			"i_entry" => $this->userid);
	$hasil = $this->refpropinsi_serv->tambahpropinsi($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Propinsi';
	$this->view->status	= $hasil;
	$this->listpropinsiAction();
	$this->render(listpropinsi);
}

public function updatepropinsiAction(){
	$c_propinsi = $_POST['c_propinsi'];
	$n_propinsi = $_POST['n_propinsi'];
	
	$masukanInsert = array("c_propinsi" => $c_propinsi,
			"n_propinsi" => $n_propinsi,
			"i_entry" => $this->userid);
	$hasil = $this->refpropinsi_serv->ubahpropinsi($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Propinsi';
	$this->view->status	= $hasil;
	$this->listpropinsiAction();
	$this->render(listpropinsi);
}	

public function deletepropinsiAction(){
	$c_propinsi = $_REQUEST['c_propinsi'];
	
	$masukanInsert = array("c_propinsi" => $c_propinsi);
	$hasil = $this->refpropinsi_serv->hapuspropinsi($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Propinsi';
	$this->view->status	= $hasil;
	$this->listpropinsiAction();
	$this->render(listpropinsi);
}

}
?>
