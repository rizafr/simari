<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refkejuruanmil_Service.php";

class Sdmmodule_RefkejuruanmilController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refkejuruanmil_serv = Refkejuruanmil_Service::getInstance();

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
public function kejuruanmiljsAction() 
{
	header('content-type : text/javascript');
	$this->render('kejuruanmiljs');
}	
	
public function listkejuruanmilAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refkejuruanmil_serv->getKejuruanmilList($cari, 0, 0);
	$this->view->listKejuruanmil = $this->refkejuruanmil_serv->getKejuruanmilList($cari ,$currentPage,$numToDisplay );
	
}

public function kejuruanmilolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_kejuruanmil = $_REQUEST['c_kejuruanmil'];
	$this->view->n_kejuruanmil = $_REQUEST['n_kejuruanmil'];
	
	$this->view->detailKejuruanmil = array();
	if($this->view->par == 'update'){
		$masukan = array("c_kejuruanmil" => $this->view->c_kejuruanmil);
		$this->view->detailKejuruanmil = $this->refkejuruanmil_serv->detailKejuruanmil($masukan);
	}
	
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertkejuruanmilAction(){
	$n_kejuruanmil = $_POST['n_kejuruanmil'];
	$c_kejuruanmil = $_POST['c_kejuruanmil'];
	
	$masukanInsert = array("n_kejuruanmil" => $n_kejuruanmil,
			"c_kejuruanmil" => $c_kejuruanmil,
			"i_entry" => $this->userid);
	$hasil = $this->refkejuruanmil_serv->tambahkejuruanmil($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Kejuruan Militer';
	$this->view->status	= $hasil;
	$this->listkejuruanmilAction();
	$this->render(listkejuruanmil);
}

public function updatekejuruanmilAction(){
	$n_kejuruanmil = $_POST['n_kejuruanmil'];
	$c_kejuruanmil = $_POST['c_kejuruanmil'];
	
	$masukanInsert = array("n_kejuruanmil" => $n_kejuruanmil,
			"c_kejuruanmil" => $c_kejuruanmil,
			"i_entry" => $this->userid);
	$hasil = $this->refkejuruanmil_serv->ubahkejuruanmil($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Kejuruan Militer';
	$this->view->status	= $hasil;
	$this->listkejuruanmilAction();
	$this->render(listkejuruanmil);
}	

public function deletekejuruanmilAction(){
	$c_kejuruanmil = $_REQUEST['c_kejuruanmil'];
	
	$masukanInsert = array("c_kejuruanmil" => $c_kejuruanmil);
	$hasil = $this->refkejuruanmil_serv->hapuskejuruanmil($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Kejuruan Militer';
	$this->view->status	= $hasil;
	$this->listkejuruanmilAction();
	$this->render(listkejuruanmil);
}

}
?>
