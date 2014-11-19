<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refhukuman_Service.php";

class Sdmmodule_RefhukumanController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refhukuman_serv = Refhukuman_Service::getInstance();

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
public function hukumanjsAction() 
{
	header('content-type : text/javascript');
	$this->render('hukumanjs');
}	
	
public function listhukumanAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refhukuman_serv->getHukumanList($cari, 0, 0);
	$this->view->listHukuman = $this->refhukuman_serv->getHukumanList($cari ,$currentPage,$numToDisplay );
	
}

public function hukumanolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_hukuman = $_REQUEST['c_hukuman'];
	$this->view->n_hukuman = $_REQUEST['n_hukuman'];
	$this->view->q_tingkat_hukuman = $_REQUEST['q_tingkat_hukuman'];
	
	$this->view->detailHukuman = array();
	if($this->view->par == 'update'){
		$masukan = array("c_hukuman" => $this->view->c_hukuman);
		$this->view->detailHukuman = $this->refhukuman_serv->detailHukuman($masukan);
	}
	
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function inserthukumanAction(){
	$n_hukuman = $_POST['n_hukuman'];
	$c_hukuman = $_POST['c_hukuman'];
	$q_tingkat_hukuman = $_POST['q_tingkat_hukuman'];
	
	$masukanInsert = array("n_hukuman" => $n_hukuman,
			"c_hukuman" => $c_hukuman,
			"q_tingkat_hukuman" => $q_tingkat_hukuman);
	$hasil = $this->refhukuman_serv->tambahhukuman($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Jenis Hukuman';
	$this->view->status	= $hasil;
	$this->listhukumanAction();
	$this->render(listhukuman);
}

public function updatehukumanAction(){
	$n_hukuman = $_POST['n_hukuman'];
	$c_hukuman = $_POST['c_hukuman'];
	$q_tingkat_hukuman = $_POST['q_tingkat_hukuman'];
	
	$masukanInsert = array("n_hukuman" => $n_hukuman,
			"c_hukuman" => $c_hukuman,
			"q_tingkat_hukuman" => $q_tingkat_hukuman);
	$hasil = $this->refhukuman_serv->ubahhukuman($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Jenis Hukuman';
	$this->view->status	= $hasil;
	$this->listhukumanAction();
	$this->render(listhukuman);
}	

public function deletehukumanAction(){
	$c_hukuman = $_REQUEST['c_hukuman'];
	
	$masukanInsert = array("c_hukuman" => $c_hukuman);
	$hasil = $this->refhukuman_serv->hapushukuman($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Jenis Hukuman';
	$this->view->status	= $hasil;
	$this->listhukumanAction();
	$this->render(listhukuman);
}

}
?>
