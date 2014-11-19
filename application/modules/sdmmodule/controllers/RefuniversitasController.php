<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refuniversitas_Service.php";

class Sdmmodule_RefuniversitasController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refuniversitas_serv = Refuniversitas_Service::getInstance();

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
public function universitasjsAction() 
{
	header('content-type : text/javascript');
	$this->render('universitasjs');
}	
	
public function listuniversitasAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refuniversitas_serv->getUniversitasList($cari, 0, 0);
	$this->view->listUniversitas = $this->refuniversitas_serv->getUniversitasList($cari ,$currentPage,$numToDisplay );
	
}

public function universitasolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_universitas = $_REQUEST['c_universitas'];
	$this->view->n_universitas2 = $_REQUEST['n_universitas2'];
	$this->view->n_rayon_pro = $_REQUEST['n_rayon_pro'];
	$this->view->q_strata = $_REQUEST['q_strata'];
	$this->view->n_universitas = $_REQUEST['n_universitas'];
	
	$this->view->detailUniversitas = array();
	if($this->view->par == 'update'){
		$masukan = array("c_universitas" => $this->view->c_universitas);
		$this->view->detailUniversitas = $this->refuniversitas_serv->detailUniversitas($masukan);
	}
	
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertuniversitasAction(){
	$c_universitas = $_POST['c_universitas'];
	$n_universitas2 = $_POST['n_universitas2'];
	$n_rayon_pro = $_POST['n_rayon_pro'];
	$q_strata = $_POST['q_strata'];
	$n_universitas = $_POST['n_universitas'];
	
	$masukanInsert = array("c_universitas" => $c_universitas,
			"n_universitas2" => $n_universitas2,
			"n_rayon_pro" => $n_rayon_pro,
			"q_strata" => $q_strata,
			"n_universitas" => $n_universitas);
	$hasil = $this->refuniversitas_serv->tambahuniversitas($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Universitas';
	$this->view->status	= $hasil;
	$this->listuniversitasAction();
	$this->render(listuniversitas);
}

public function updateuniversitasAction(){
	$c_universitas = $_POST['c_universitas'];
	$n_universitas2 = $_POST['n_universitas2'];
	$n_rayon_pro = $_POST['n_rayon_pro'];
	$q_strata = $_POST['q_strata'];
	$n_universitas = $_POST['n_universitas'];
	
	$masukanInsert = array("c_universitas" => $c_universitas,
			"n_universitas2" => $n_universitas2,
			"n_rayon_pro" => $n_rayon_pro,
			"q_strata" => $q_strata,
			"n_universitas" => $n_universitas);
	$hasil = $this->refuniversitas_serv->ubahuniversitas($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Universitas';
	$this->view->status	= $hasil;
	$this->listuniversitasAction();
	$this->render(listuniversitas);
}	

public function deleteuniversitasAction(){
	$c_universitas = $_REQUEST['c_universitas'];
	
	$masukanInsert = array("c_universitas" => $c_universitas);
	$hasil = $this->refuniversitas_serv->hapusuniversitas($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Universitas';
	$this->view->status	= $hasil;
	$this->listuniversitasAction();
	$this->render(listuniversitas);
}

}
?>
