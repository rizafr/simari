<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Reftandajasa_Service.php";

class Sdmmodule_ReftandajasaController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->reftandajasa_serv = Reftandajasa_Service::getInstance();

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
public function tandajasajsAction() 
{
	header('content-type : text/javascript');
	$this->render('tandajasajs');
}	
	
public function listtandajasaAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->reftandajasa_serv->getTandajasaList($cari, 0, 0);
	$this->view->listTandajasa = $this->reftandajasa_serv->getTandajasaList($cari ,$currentPage,$numToDisplay );
	
}

public function tandajasaolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_tandajasa = $_REQUEST['c_tandajasa'];
	$this->view->n_tandajasa = $_REQUEST['n_tandajasa'];
	$this->view->e_penerbit = $_REQUEST['e_penerbit'];
	$this->view->e_jnama = $_REQUEST['e_jnama'];
	
	$this->view->detailTandajasa = array();
	if($this->view->par == 'update'){
		$masukan = array("c_tandajasa" => $this->view->c_tandajasa);
		$this->view->detailTandajasa = $this->reftandajasa_serv->detailTandajasa($masukan);
	}
	
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function inserttandajasaAction(){
	$c_tandajasa = $_POST['c_tandajasa'];
	$n_tandajasa = $_POST['n_tandajasa'];
	$e_penerbit = $_POST['e_penerbit'];
	$e_jnama = $_POST['e_jnama'];
	
	$masukanInsert = array("c_tandajasa" => $c_tandajasa,
			"n_tandajasa" => $n_tandajasa,
			"e_penerbit" => $e_penerbit,
			"e_jnama" => $e_jnama);
	$hasil = $this->reftandajasa_serv->tambahtandajasa($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Tanda Jasa';
	$this->view->status	= $hasil;
	$this->listtandajasaAction();
	$this->render(listtandajasa);
}

public function updatetandajasaAction(){
	$c_tandajasa = $_POST['c_tandajasa'];
	$n_tandajasa = $_POST['n_tandajasa'];
	$e_penerbit = $_POST['e_penerbit'];
	$e_jnama = $_POST['e_jnama'];
	
	$masukanInsert = array("c_tandajasa" => $c_tandajasa,
			"n_tandajasa" => $n_tandajasa,
			"e_penerbit" => $e_penerbit,
			"e_jnama" => $e_jnama);
	$hasil = $this->reftandajasa_serv->ubahtandajasa($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Tanda Jasa';
	$this->view->status	= $hasil;
	$this->listtandajasaAction();
	$this->render(listtandajasa);
}	

public function deletetandajasaAction(){
	$c_tandajasa = $_REQUEST['c_tandajasa'];
	
	$masukanInsert = array("c_tandajasa" => $c_tandajasa);
	$hasil = $this->reftandajasa_serv->hapustandajasa($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Tanda Jasa';
	$this->view->status	= $hasil;
	$this->listtandajasaAction();
	$this->render(listtandajasa);
}

}
?>
