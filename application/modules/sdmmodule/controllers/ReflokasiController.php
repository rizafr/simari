<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Reflokasi_Service.php";

class Sdmmodule_ReflokasiController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->reflokasi_serv = Reflokasi_Service::getInstance();

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
public function lokasijsAction() 
{
	header('content-type : text/javascript');
	$this->render('lokasijs');
}	
	
public function listlokasiAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->reflokasi_serv->getLokasiList($cari, 0, 0);
	$this->view->listLokasi = $this->reflokasi_serv->getLokasiList($cari ,$currentPage,$numToDisplay );
	
}

public function lokasiolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_lokasi = $_REQUEST['c_lokasi'];
	$this->view->n_lokasi = $_REQUEST['n_lokasi'];
	$this->view->c_status = $_REQUEST['c_status'];
	
	$this->view->detailLokasi = array();
	if($this->view->par == 'update'){
		$masukan = array("c_lokasi" => $this->view->c_lokasi);
		$this->view->detailLokasi = $this->reflokasi_serv->detailLokasi($masukan);
	}
	
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertlokasiAction(){
	$n_lokasi = $_POST['n_lokasi'];
	$c_lokasi = $_POST['c_lokasi'];
	$c_status = $_POST['c_status'];
	
	$masukanInsert = array("n_lokasi" => $n_lokasi,
			"c_lokasi" => $c_lokasi,
			"c_status" => $c_status);
	$hasil = $this->reflokasi_serv->tambahlokasi($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Lokasi';
	$this->view->status	= $hasil;
	$this->listlokasiAction();
	$this->render(listlokasi);
}

public function updatelokasiAction(){
	$n_lokasi = $_POST['n_lokasi'];
	$c_lokasi = $_POST['c_lokasi'];
	$c_status = $_POST['c_status'];
	
	$masukanInsert = array("n_lokasi" => $n_lokasi,
			"c_lokasi" => $c_lokasi,
			"c_status" => $c_status);
	$hasil = $this->reflokasi_serv->ubahlokasi($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Lokasi';
	$this->view->status	= $hasil;
	$this->listlokasiAction();
	$this->render(listlokasi);
}	

public function deletelokasiAction(){
	$c_lokasi = $_REQUEST['c_lokasi'];
	
	$masukanInsert = array("c_lokasi" => $c_lokasi);
	$hasil = $this->reflokasi_serv->hapuslokasi($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Lokasi';
	$this->view->status	= $hasil;
	$this->listlokasiAction();
	$this->render(listlokasi);
}

}
?>
