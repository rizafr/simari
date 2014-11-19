<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Formulir4_Service.php";

class Sdmmodule_Formulir4Controller extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->formulir4_serv = Formulir4_Service::getInstance();

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
	$tahun = $_REQUEST['tahun'];
	$thn_skr = $_REQUEST['thn_skr'];
	$thn_sblm = $_REQUEST['thn_sblm'];
	$this->view->tahun = $tahun;
	$this->view->thn_skr = $thn_skr;
	$this->view->thn_sblm = $thn_sblm;
	$this->view->listFormulir4 = $this->formulir4_serv->getformulir4($tahun);
	$this->view->listGol = $this->formulir4_serv->getgol();
	$this->view->listJumFormulir4 = $this->formulir4_serv->getJumformulir4($tahun);
}

public function formulir4olahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->gol_ruang_gaji = $_REQUEST['gol_ruang_gaji'];
	$this->view->pangkat = $_REQUEST['pangkat'];
	$this->view->thn_skrg = $_REQUEST['thn_skrg'];
	$this->view->thn_sblm = $_REQUEST['thn_sblm'];
	$this->view->jum_pegawai = $_REQUEST['jum_pegawai'];
	$this->view->formasi = $_REQUEST['formasi'];
	$this->view->ket = $_REQUEST['ket'];
	$this->view->id = $_REQUEST['id'];
	
	$this->view->detailFormulir4 = array();
	if($this->view->par == 'Update'){
		$masukan = array("id" => $this->view->id);
		$this->view->detailFormulir4 = $this->formulir4_serv->detailFormulir4($masukan);
	}
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertformulir4Action(){
	$gol_ruang_gaji = $_POST['gol_ruang_gaji'];
	$pangkat = $_POST['pangkat'];
	$thn_skrg = $_POST['thn_skrg'];
	$thn_sblm = $_POST['thn_sblm'];
	$jum_pegawai = $_POST['jum_pegawai'];
	$formasi = $_POST['formasi'];
	$ket = $_POST['ket'];
	
	$masukanInsert = array("pangkat" => $pangkat,
			"gol_ruang_gaji" => $gol_ruang_gaji,
			"thn_skrg" => $thn_skrg,
			"thn_sblm" => $thn_sblm,
			"jum_pegawai" => $jum_pegawai,
			"formasi" => $formasi,
			"ket" => $ket,
			"i_entry" => $this->userid);
	$hasil = $this->formulir4_serv->tambahformulir4($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Formulir 4';
	$this->view->status	= $hasil;
	$this->listformulir4Action();
	$this->render(listformulir4);
}

public function updateformulir4Action(){
	$id = $_POST['id'];
	$gol_ruang_gaji = $_POST['gol_ruang_gaji'];
	$pangkat = $_POST['pangkat'];
	$thn_skrg = $_POST['thn_skrg'];
	$thn_sblm = $_POST['thn_sblm'];
	$jum_pegawai = $_POST['jum_pegawai'];
	$formasi = $_POST['formasi'];
	$ket = $_POST['ket'];
	
	$masukanInsert = array("id" => $id,
			"pangkat" => $pangkat,
			"gol_ruang_gaji" => $gol_ruang_gaji,
			"thn_skrg" => $thn_skrg,
			"thn_sblm" => $thn_sblm,
			"jum_pegawai" => $jum_pegawai,
			"formasi" => $formasi,
			"ket" => $ket,
			"i_entry" => $this->userid);
	$hasil = $this->formulir4_serv->ubahformulir4($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Formulir 4';
	$this->view->status	= $hasil;
	$this->listformulir4Action();
	$this->render(listformulir4);
}	
}?>