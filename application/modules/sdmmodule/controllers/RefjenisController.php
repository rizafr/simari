<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refjenis_Service.php";

class Sdmmodule_RefjenisController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refjenis_serv = Refjenis_Service::getInstance();

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
public function jenisjsAction() 
{
	header('content-type : text/javascript');
	$this->render('jenisjs');
}	
	
public function listjenisAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refjenis_serv->getJenisList($cari, 0, 0);
	$this->view->listJenis = $this->refjenis_serv->getJenisList($cari ,$currentPage,$numToDisplay );
	
}

public function jenisolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->i_kode = $_REQUEST['i_kode'];
	$this->view->n_nama = $_REQUEST['n_nama'];
	$this->view->v_sort = $_REQUEST['v_sort'];
	$this->view->c_status = $_REQUEST['c_status'];
	$this->view->d_update = $_REQUEST['d_update'];
	
	$this->view->detailJenis = array();
	if($this->view->par == 'update'){
	$masukan = array("i_kode" => $this->view->i_kode);
	$this->view->detailJenis = $this->refjenis_serv->detailJenis($masukan);
	}
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertjenisAction(){
	$i_kode = $_POST['i_kode'];
	$n_nama = $_POST['n_nama'];
	$v_sort = $_POST['v_sort'];
	$c_status = $_POST['c_status'];
	$d_update = $_POST['d_update'];
	
	$masukanInsert = array("i_kode" => $i_kode,
			"n_nama" => $n_nama,
			"v_sort" => $v_sort,
			"c_status" => $c_status,
			"d_update" => $d_update);
	$hasil = $this->refjenis_serv->tambahjenis($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Jenis Naik Golongan';
	$this->view->status	= $hasil;
	$this->listjenisAction();
	$this->render(listjenis);
}

public function updatejenisAction(){
	$i_kode = $_POST['i_kode'];
	$n_nama = $_POST['n_nama'];
	$v_sort = $_POST['v_sort'];
	$c_status = $_POST['c_status'];
	$d_update = $_POST['d_update'];
	
	$masukanInsert = array("i_kode" => $i_kode,
			"n_nama" => $n_nama,
			"v_sort" => $v_sort,
			"c_status" => $c_status,
			"d_update" => $d_update);
	$hasil = $this->refjenis_serv->ubahjenis($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Jenis Naik Golongan';
	$this->view->status	= $hasil;
	$this->listjenisAction();
	$this->render(listjenis);
}	

public function deletejenisAction(){
	$i_kode = $_REQUEST['i_kode'];
	
	$masukanInsert = array("i_kode" => $i_kode);
	$hasil = $this->refjenis_serv->hapusjenis($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Jenis Naik Golongan';
	$this->view->status	= $hasil;
	$this->listjenisAction();
	$this->render(listjenis);
}

}
?>
