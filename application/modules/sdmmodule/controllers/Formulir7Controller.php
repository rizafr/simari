<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Formulir7_Service.php";

class Sdmmodule_Formulir7Controller extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->formulir7_serv = Formulir7_Service::getInstance();

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
    
public function formulir7jsAction() 
{
	header('content-type : text/javascript');
	$this->render('formulir7js');
}	
	
public function listformulir7Action() {
	if($_REQUEST['tahun'] != ""){
	$potong = strpos($_REQUEST['tahun'],"2");
	$tahun = substr($_REQUEST['tahun'],$potong);
	
	}
	$thn_skr = $_REQUEST['thn_skr'];
	$thn_sblm = $_REQUEST['thn_sblm'];
	$this->view->tahun = $tahun;
	$this->view->thn_skr = $thn_skr;
	$this->view->thn_sblm = $thn_sblm;
	$this->view->listFormulir7 = $this->formulir7_serv->getformulir7($tahun);
	$this->view->listJumFormulir7 = $this->formulir7_serv->getJumformulir7($tahun);
}

public function formulir7olahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->perwakilan = $_REQUEST['perwakilan'];
	$this->view->thn_skrg = $_REQUEST['thn_skrg'];
	$this->view->thn_sblm = $_REQUEST['thn_sblm'];
	$this->view->jum_pegawai = $_REQUEST['jum_pegawai'];
	$this->view->formasi = $_REQUEST['formasi'];
	$this->view->ket = $_REQUEST['ket'];
	$this->view->id = $_REQUEST['id'];
	
	$this->view->detailFormulir7 = array();
	if($this->view->par == 'Update'){
		$masukan = array("id" => $this->view->id);
		$this->view->detailFormulir7 = $this->formulir7_serv->detailFormulir7($masukan);
	}
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertformulir7Action(){
	$perwakilan = $_POST['perwakilan'];
	if($_POST['thn_skrg'] != ""){
	$potong = strpos($_POST['thn_skrg'],"2");
	$thn_skrg = substr($_POST['thn_skrg'],$potong);
	
	}
	$thn_sblm = $_POST['thn_sblm'];
	$jum_pegawai = $_POST['jum_pegawai'];
	$formasi = $_POST['formasi'];
	$ket = $_POST['ket'];
	
	$masukanInsert = array("perwakilan" => $perwakilan,
			"thn_skrg" => $thn_skrg,
			"thn_sblm" => $thn_sblm,
			"jum_pegawai" => $jum_pegawai,
			"formasi" => $formasi,
			"ket" => $ket,
			"i_entry" => $this->userid);
	$hasil = $this->formulir7_serv->tambahformulir7($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Formulir 7';
	$this->view->status	= $hasil;
	$this->listformulir7Action();
	$this->render(listformulir7);
}

public function updateformulir7Action(){
	$id = $_POST['id'];
	$perwakilan = $_POST['perwakilan'];
	if($_POST['thn_skrg'] != ""){
	$potong = strpos($_POST['thn_skrg'],"2");
	$thn_skrg = substr($_POST['thn_skrg'],$potong);
	
	}
	$thn_sblm = $_POST['thn_sblm'];
	$jum_pegawai = $_POST['jum_pegawai'];
	$formasi = $_POST['formasi'];
	$ket = $_POST['ket'];
	
	$masukanInsert = array("id" => $id,
			"perwakilan" => $perwakilan,
			"thn_skrg" => $thn_skrg,
			"thn_sblm" => $thn_sblm,
			"jum_pegawai" => $jum_pegawai,
			"formasi" => $formasi,
			"ket" => $ket,
			"i_entry" => $this->userid);
	$hasil = $this->formulir7_serv->ubahformulir7($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Formulir 7';
	$this->view->status	= $hasil;
	$this->listformulir7Action();
	$this->render(listformulir7);
}	

public function deleteformulir7Action(){
	$id = $_REQUEST['id'];
	
	$masukanInsert = array("id" => $id);
	$hasil = $this->formulir7_serv->hapusformulir7($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Formulir 7';
	$this->view->status	= $hasil;
	$this->listformulir7Action();
	$this->render(listformulir7);
}
}?>