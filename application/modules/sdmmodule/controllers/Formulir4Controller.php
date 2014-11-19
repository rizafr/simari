<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Formulir1_Service.php";
require_once "service/refferensi/Formulir4_Service.php";
require_once "service/sdm/Sdm_formasi_Service.php";

class Sdmmodule_Formulir4Controller extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->formulir1_serv = Formulir1_Service::getInstance();
		$this->formulir4_serv = Formulir4_Service::getInstance();
		$this->view->formasi_serv = Sdm_formasi_Service::getInstance();
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
    
public function formulir4jsAction() 
{
	header('content-type : text/javascript');
	$this->render('formulir4js');
}	
	
public function listformulir4Action() {
	if($_GET['tahun'] != ""){
		$tahun = $_GET['tahun'];
	}
	if($_GET['proses'] != ""){
		$proses = $_GET['proses'];
	}
	$this->view->tahun = $tahun;
	$this->view->thn_sblm = $tahun-1;
	
	$this->view->proses = $proses;
	$this->view->listGol = $this->formulir1_serv->getdataformasi($tahun);
	if($proses == 'prs') $this->view->listGol = $this->formulir1_serv->getdataformasi($tahun);
	else if($proses == 'upd') $this->view->listGol = $this->formulir4_serv->getdataformasi($tahun);
	
	
}
public function simpanformulirAction(){
	$listGol = $this->formulir1_serv->getdatagol();
	$tahun	= $_POST["tahun"];
	$this->view->tahun=$tahun;
	$proses	= $_POST["proses"];
		$this->formulir4_serv->hapusformulir($tahun);
			
	foreach ($listGol as $datapkt){
		$c_peg_golongan = $datapkt->c_peg_golongan;
		$sumpegawai	= 'sumpegawai'.$c_peg_golongan;
		$formas		= 'formas'.$c_peg_golongan;
		$ket		= 'ket'.$c_peg_golongan;
		$jum_pegawai= $_POST["$sumpegawai"];
		$formasi	= $_POST["$formas"];
		$keterangan	= $_POST["$ket"];
		
		$masukanInsert = array("c_golongan" => $c_peg_golongan,
			"thn_skrg" => $tahun,
			"jum_pegawai" => $jum_pegawai,
			"formasi" => $formasi,
			"ket" => $keterangan,
			"i_entry" => $this->userid);
		
			$hasil = $this->formulir4_serv->tambahformulir($masukanInsert);
		
	}
	//$this->view->proses 	= '1';
	$this->view->keterangan = 'Formulir 4';
	$this->view->status	= $hasil;
	$this->listformulir4Action();
	$this->render(listformulir4);
}


}?>