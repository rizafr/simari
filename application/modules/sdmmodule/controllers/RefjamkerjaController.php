<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refjamkerja_Service.php";

class Sdmmodule_RefjamkerjaController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refjamkerja_serv = Refjamkerja_Service::getInstance();

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
public function jamkerjajsAction() 
{
	header('content-type : text/javascript');
	$this->render('jamkerjajs');
}	
	
public function listjamkerjaAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refjamkerja_serv->getJamkerjaList($cari, 0, 0);
	$this->view->listJamkerja = $this->refjamkerja_serv->getJamkerjaList($cari ,$currentPage,$numToDisplay );
	
}

public function jamkerjaolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_hari = $_REQUEST['c_hari'];
	$this->view->d_jamkerja_mulai = $_REQUEST['d_jamkerja_mulai'];
	$this->view->d_jamkerja_selesai = $_REQUEST['d_jamkerja_selesai'];
	$this->view->d_jamistrht_mulai = $_REQUEST['d_jamistrht_mulai'];
	$this->view->d_jamistrht_selesai = $_REQUEST['d_jamistrht_selesai'];
	
	$this->view->detailJamkerja = array();
	if($this->view->par == 'update'){
		$masukan = array("c_hari" => $this->view->c_hari);
		$this->view->detailJamkerja = $this->refjamkerja_serv->detailJamkerja($masukan);
	}
	
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertjamkerjaAction(){
	$c_hari = $_POST['c_hari'];
	$d_jamkerja_mulai = $_POST['d_jamkerja_mulai'];
	$d_jamkerja_selesai = $_POST['d_jamkerja_selesai'];
	$d_jamistrht_mulai = $_POST['d_jamistrht_mulai'];
	$d_jamistrht_selesai = $_POST['d_jamistrht_selesai'];
	
	$masukanInsert = array("c_hari" => $c_hari,
			"d_jamkerja_mulai" => $d_jamkerja_mulai,
			"d_jamkerja_selesai" => $d_jamkerja_selesai,
			"d_jamistrht_mulai" => $d_jamistrht_mulai,
			"d_jamistrht_selesai" => $d_jamistrht_selesai,
			"i_entry" => $this->userid);
	$hasil = $this->refjamkerja_serv->tambahjamkerja($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Jam Hari Kerja';
	$this->view->status	= $hasil;
	$this->listjamkerjaAction();
	$this->render(listjamkerja);
}

public function updatejamkerjaAction(){
	$c_hari = $_POST['c_hari'];
	$d_jamkerja_mulai = $_POST['d_jamkerja_mulai'];
	$d_jamkerja_selesai = $_POST['d_jamkerja_selesai'];
	$d_jamistrht_mulai = $_POST['d_jamistrht_mulai'];
	$d_jamistrht_selesai = $_POST['d_jamistrht_selesai'];
	
	$masukanInsert = array("c_hari" => $c_hari,
			"d_jamkerja_mulai" => $d_jamkerja_mulai,
			"d_jamkerja_selesai" => $d_jamkerja_selesai,
			"d_jamistrht_mulai" => $d_jamistrht_mulai,
			"d_jamistrht_selesai" => $d_jamistrht_selesai,
			"i_entry" => $this->userid);
	$hasil = $this->refjamkerja_serv->ubahjamkerja($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Jam Hari Kerja';
	$this->view->status	= $hasil;
	$this->listjamkerjaAction();
	$this->render(listjamkerja);
}	

public function deletejamkerjaAction(){
	$c_hari = $_REQUEST['c_hari'];
	
	$masukanInsert = array("c_hari" => $c_hari);
	$hasil = $this->refjamkerja_serv->hapusjamkerja($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Jam Hari Kerja';
	$this->view->status	= $hasil;
	$this->listjamkerjaAction();
	$this->render(listjamkerja);
}

}
?>
