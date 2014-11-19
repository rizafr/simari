<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refkabupaten_Service.php";

class Sdmmodule_RefkabupatenController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refkabupaten_serv = Refkabupaten_Service::getInstance();

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
public function kabupatenjsAction() 
{
	header('content-type : text/javascript');
	$this->render('kabupatenjs');
}	
	
public function listkabupatenAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refkabupaten_serv->getKabupatenList($cari, 0, 0);
	$this->view->listKabupaten = $this->refkabupaten_serv->getKabupatenList($cari ,$currentPage,$numToDisplay );
	
}

public function kabupatenolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_kabupaten = $_REQUEST['c_kabupaten'];
	$this->view->n_kabupaten = $_REQUEST['n_kabupaten'];
	$this->view->c_propinsi = $_REQUEST['c_propinsi'];
	
	$masukan = array("c_kabupaten" => $this->view->c_kabupaten);
	$this->view->detailKabupaten = $this->refkabupaten_serv->detailKabupaten($masukan);
	//$this->view->listkabupaten = $this->refkabupaten_serv->getkabupatenList($cari);
}

public function insertkabupatenAction(){
	$n_kabupaten = $_POST['n_kabupaten'];
	$c_kabupaten = $_POST['c_kabupaten'];
	$c_propinsi = $_POST['c_propinsi'];
	
	$masukanInsert = array("n_kabupaten" => $n_kabupaten,
			"c_kabupaten" => $c_kabupaten,
			"c_propinsi" => $c_propinsi,
			"i_entry" => $this->userid);
	$hasil = $this->refkabupaten_serv->tambahkabupaten($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Kabupaten';
	$this->view->status	= $hasil;
	$this->listkabupatenAction();
	$this->render(listkabupaten);
}

public function updatekabupatenAction(){
	$n_kabupaten = $_POST['n_kabupaten'];
	$c_kabupaten = $_POST['c_kabupaten'];
	$c_propinsi = $_POST['c_propinsi'];
	
	$masukanInsert = array("n_kabupaten" => $n_kabupaten,
			"c_kabupaten" => $c_kabupaten,
			"c_propinsi" => $c_propinsi,
			"i_entry" => $this->userid);
	$hasil = $this->refkabupaten_serv->ubahkabupaten($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Kabupaten';
	$this->view->status	= $hasil;
	$this->listkabupatenAction();
	$this->render(listkabupaten);
}	

public function deletekabupatenAction(){
	$c_kabupaten = $_REQUEST['c_kabupaten'];
	
	$masukanInsert = array("c_kabupaten" => $c_kabupaten,
			"i_entry" => $this->userid);
	$hasil = $this->refkabupaten_serv->hapuskabupaten($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Kabupaten';
	$this->view->status	= $hasil;
	$this->listkabupatenAction();
	$this->render(listkabupaten);
}

}
?>
