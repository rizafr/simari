<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refnegara_Service.php";

class Sdmmodule_RefnegaraController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refnegara_serv = Refnegara_Service::getInstance();

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
public function negarajsAction() 
{
	header('content-type : text/javascript');
	$this->render('negarajs');
}	
	
public function listnegaraAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refnegara_serv->getNegaraList($cari, 0, 0);
	$this->view->listNegara = $this->refnegara_serv->getNegaraList($cari ,$currentPage,$numToDisplay );
	
}

public function negaraolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_negara = $_REQUEST['c_negara'];
	$this->view->n_negara = $_REQUEST['n_negara'];
	
	$this->view->detailNegara = array();
	if($this->view->par == 'update'){
	$masukan = array("c_negara" => $this->view->c_negara);
	$this->view->detailNegara = $this->refnegara_serv->detailNegara($masukan);
	}
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertnegaraAction(){
	$c_negara = $_POST['c_negara'];
	$n_negara = $_POST['n_negara'];
	
	$masukanInsert = array("c_negara" => $c_negara,
			"n_negara" => $n_negara,
			"i_entry" => $this->userid);
	$hasil = $this->refnegara_serv->tambahnegara($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Negara';
	$this->view->status	= $hasil;
	$this->listnegaraAction();
	$this->render(listnegara);
}

public function updatenegaraAction(){
	$c_negara = $_POST['c_negara'];
	$n_negara = $_POST['n_negara'];
	
	$masukanInsert = array("c_negara" => $c_negara,
			"n_negara" => $n_negara,
			"i_entry" => $this->userid);
	$hasil = $this->refnegara_serv->ubahnegara($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Negara';
	$this->view->status	= $hasil;
	$this->listnegaraAction();
	$this->render(listnegara);
}	

public function deletenegaraAction(){
	$c_negara = $_REQUEST['c_negara'];
	
	$masukanInsert = array("c_negara" => $c_negara);
	$hasil = $this->refnegara_serv->hapusnegara($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Negara';
	$this->view->status	= $hasil;
	$this->listnegaraAction();
	$this->render(listnegara);
}

}
?>
