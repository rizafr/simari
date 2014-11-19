<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refjurusan_Service.php";

class Sdmmodule_RefjurusanController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refjurusan_serv = Refjurusan_Service::getInstance();

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
public function jurusanjsAction() 
{
	header('content-type : text/javascript');
	$this->render('jurusanjs');
}	
	
public function listjurusanAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refjurusan_serv->getJurusanList($cari, 0, 0);
	$this->view->listJurusan = $this->refjurusan_serv->getJurusanList($cari ,$currentPage,$numToDisplay );
	
}

public function jurusanolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_jurusan = $_REQUEST['c_jurusan'];
	$this->view->n_jurusan = $_REQUEST['n_jurusan'];
	$this->view->q_strata = $_REQUEST['q_strata'];
	
	$this->view->detailJurusan = array();
	if($this->view->par == 'update'){
		$masukan = array("c_jurusan" => $this->view->c_jurusan);
		$this->view->detailJurusan = $this->refjurusan_serv->detailJurusan($masukan);
	}
	
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertjurusanAction(){
	$n_jurusan = $_POST['n_jurusan'];
	$c_jurusan = $_POST['c_jurusan'];
	$q_strata = $_POST['q_strata'];
	
	$masukanInsert = array("n_jurusan" => $n_jurusan,
			"c_jurusan" => $c_jurusan,
			"q_strata" => $q_strata);
	$hasil = $this->refjurusan_serv->tambahjurusan($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Jurusan Pendidikan';
	$this->view->status	= $hasil;
	$this->listjurusanAction();
	$this->render(listjurusan);
}

public function updatejurusanAction(){
	$n_jurusan = $_POST['n_jurusan'];
	$c_jurusan = $_POST['c_jurusan'];
	$q_strata = $_POST['q_strata'];
	
	$masukanInsert = array("n_jurusan" => $n_jurusan,
			"c_jurusan" => $c_jurusan,
			"q_strata" => $q_strata);
	$hasil = $this->refjurusan_serv->ubahjurusan($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Jurusan Pendidikan';
	$this->view->status	= $hasil;
	$this->listjurusanAction();
	$this->render(listjurusan);
}	

public function deletejurusanAction(){
	$c_jurusan = $_REQUEST['c_jurusan'];
	
	$masukanInsert = array("c_jurusan" => $c_jurusan);
	$hasil = $this->refjurusan_serv->hapusjurusan($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Jurusan Pendidikan';
	$this->view->status	= $hasil;
	$this->listjurusanAction();
	$this->render(listjurusan);
}

}
?>
