<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refgolpangkat_Service.php";

class Sdmmodule_RefgolpangkatController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refgolpangkat_serv = Refgolpangkat_Service::getInstance();

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
public function golpangkatjsAction() 
{
	header('content-type : text/javascript');
	$this->render('golpangkatjs');
}	
	
public function listgolpangkatAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refgolpangkat_serv->getGolpangkatList($cari, 0, 0);
	$this->view->listGolpangkat = $this->refgolpangkat_serv->getGolpangkatList($cari ,$currentPage,$numToDisplay );
	
}

public function golpangkatolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_peg_golongan = $_REQUEST['c_peg_golongan'];
	$this->view->c_peg_tipegolongan = $_REQUEST['c_peg_tipegolongan'];
	$this->view->c_peg_lvlgolongan = $_REQUEST['c_peg_lvlgolongan'];
	$this->view->n_peg_golongan = $_REQUEST['n_peg_golongan'];
	$this->view->n_peg_pangkat = $_REQUEST['n_peg_pangkat'];
	$this->view->c_pph = $_REQUEST['c_pph'];
	
			
	$this->view->detailGolpangkat = array();
	if($this->view->par == 'update'){
		$masukan = array("c_peg_golongan" => $this->view->c_peg_golongan,
				 "c_peg_tipegolongan" => $this->view->c_peg_tipegolongan);
		$this->view->detailGolpangkat = $this->refgolpangkat_serv->detailGolpangkat($masukan);
		
		//var_dump($this->view->detailGolpangkat);
	}
}

public function insertgolpangkatAction(){
	$c_peg_golongan = $_POST['c_peg_golongan'];
	$c_peg_tipegolongan = $_POST['c_peg_tipegolongan'];
	$c_peg_lvlgolongan = $_POST['c_peg_lvlgolongan'];
	$n_peg_golongan = $_POST['n_peg_golongan'];
	$n_peg_pangkat = $_POST['n_peg_pangkat'];
	$c_pph = $_POST['c_pph'];
				
	$masukanInsert = array("c_peg_golongan" => $c_peg_golongan,
			"c_peg_tipegolongan" => $c_peg_tipegolongan,
			"c_peg_lvlgolongan" => $c_peg_lvlgolongan,
			"n_peg_golongan" => $n_peg_golongan,
			"n_peg_pangkat" => $n_peg_pangkat,
			"c_pph" => $c_pph,
			"i_entry" => $this->userid);
	$hasil = $this->refgolpangkat_serv->tambahgolpangkat($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Golongan Pangkat';
	$this->view->status	= $hasil;
	$this->listgolpangkatAction();
	$this->render(listgolpangkat);
}
	
public function updategolpangkatAction(){
	$c_peg_golongan = $_POST['c_peg_golongan'];
	$c_peg_tipegolongan = $_POST['c_peg_tipegolongan'];
	$c_peg_lvlgolongan = $_POST['c_peg_lvlgolongan'];
	$n_peg_golongan = $_POST['n_peg_golongan'];
	$n_peg_pangkat = $_POST['n_peg_pangkat'];
	$c_pph = $_POST['c_pph'];
	
	$masukanInsert = array("c_peg_golongan" => $c_peg_golongan,
			"c_peg_tipegolongan" => $c_peg_tipegolongan,
			"c_peg_lvlgolongan" => $c_peg_lvlgolongan,
			"n_peg_golongan" => $n_peg_golongan,
			"n_peg_pangkat" => $n_peg_pangkat,
			"c_pph" => $c_pph,
			"i_entry" => $this->userid);
	$hasil = $this->refgolpangkat_serv->ubahgolpangkat($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Golongan Pangkat';
	$this->view->status	= $hasil;
	$this->listgolpangkatAction();
	$this->render(listgolpangkat);
}	

public function deletegolpangkatAction(){
	$c_peg_golongan = $_REQUEST['c_peg_golongan'];
	$c_peg_tipegolongan = $_REQUEST['c_peg_tipegolongan'];
	
	$masukanInsert = array("c_peg_golongan" => $c_peg_golongan,
				"c_peg_tipegolongan" => $c_peg_tipegolongan);
	$hasil = $this->refgolpangkat_serv->hapusgolpangkat($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Golongan Pangkat';
	$this->view->status	= $hasil;
	$this->listgolpangkatAction();
	$this->render(listgolpangkat);
}

}
?>