<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refkelfungsional_Service.php";

class Sdmmodule_RefkelfungsionalController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refkelfungsional_serv = Refkelfungsional_Service::getInstance();

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
public function kelfungsionaljsAction() 
{
	header('content-type : text/javascript');
	$this->render('kelfungsionaljs');
}	
	
public function listkelfungsionalAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refkelfungsional_serv->getKelfungsionalList($cari, 0, 0);
	$this->view->listKelfungsional = $this->refkelfungsional_serv->getKelfungsionalList($cari ,$currentPage,$numToDisplay );
	
}

public function kelfungsionalolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_kelfungsional = $_REQUEST['c_kelfungsional'];
	$this->view->n_kelfungsional = $_REQUEST['n_kelfungsional'];
	$this->view->c_filter = $_REQUEST['c_filter'];
	
	$this->view->detailKelfungsional = array();
	if($this->view->par == 'update'){
		$masukan = array("c_kelfungsional" => $this->view->c_kelfungsional);
		$this->view->detailKelfungsional = $this->refkelfungsional_serv->detailKelfungsional($masukan);
	}
	
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertkelfungsionalAction(){
	$n_kelfungsional = $_POST['n_kelfungsional'];
	$c_kelfungsional = $_POST['c_kelfungsional'];
	$c_filter = $_POST['c_filter'];
	
	$masukanInsert = array("n_kelfungsional" => $n_kelfungsional,
			"c_kelfungsional" => $c_kelfungsional,
			"c_filter" => $c_filter);
	$hasil = $this->refkelfungsional_serv->tambahkelfungsional($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Kelompok Fungsional';
	$this->view->status	= $hasil;
	$this->listkelfungsionalAction();
	$this->render(listkelfungsional);
}

public function updatekelfungsionalAction(){
	$n_kelfungsional = $_POST['n_kelfungsional'];
	$c_kelfungsional = $_POST['c_kelfungsional'];
	$c_filter = $_POST['c_filter'];
	
	$masukanInsert = array("n_kelfungsional" => $n_kelfungsional,
			"c_kelfungsional" => $c_kelfungsional,
			"c_filter" => $c_filter);
	$hasil = $this->refkelfungsional_serv->ubahkelfungsional($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Kelompok Fungsional';
	$this->view->status	= $hasil;
	$this->listkelfungsionalAction();
	$this->render(listkelfungsional);
}	

public function deletekelfungsionalAction(){
	$c_kelfungsional = $_REQUEST['c_kelfungsional'];
	
	$masukanInsert = array("c_kelfungsional" => $c_kelfungsional);
	$hasil = $this->refkelfungsional_serv->hapuskelfungsional($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Kelompok Fungsional';
	$this->view->status	= $hasil;
	$this->listkelfungsionalAction();
	$this->render(listkelfungsional);
}

}
?>
