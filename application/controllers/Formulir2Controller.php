<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Formulir2_Service.php";

class Sdmmodule_Formulir2Controller extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->formulir2_serv = Formulir2_Service::getInstance();

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
    
public function formulir2jsAction() 
{
	header('content-type : text/javascript');
	$this->render('formulir2js');
}	
	
public function listformulir2Action() {
	$tahun = $_REQUEST['tahun'];
	$thn_skr = $_REQUEST['thn_skr'];
	$thn_sblm = $_REQUEST['thn_sblm'];
	$this->view->tahun = $tahun;
	$this->view->thn_skr = $thn_skr;
	$this->view->thn_sblm = $thn_sblm;
	$this->view->listFormulir2 = $this->formulir2_serv->getformulir2($tahun);
	$this->view->listGol = $this->formulir2_serv->getgol();
}

public function formulir2olahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->gol_ruang_gaji = $_REQUEST['gol_ruang_gaji'];
	$this->view->bezetting = $_REQUEST['bezetting'];
	$this->view->thn_skrg = $_REQUEST['thn_skrg'];
	$this->view->thn_sblm = $_REQUEST['thn_sblm'];
	$this->view->knaikn_pngkt = $_REQUEST['knaikn_pngkt'];
	$this->view->stlh_naik_pgkt = $_REQUEST['stlh_naik_pgkt'];
	$this->view->pengangkt_pgwai_br = $_REQUEST['pengangkt_pgwai_br'];
	$this->view->pndah_dr_instasi_lain = $_REQUEST['pndah_dr_instasi_lain'];
	$this->view->pndah_ke_instasi_lain = $_REQUEST['pndah_ke_instasi_lain'];
	$this->view->formasi_lajur = $_REQUEST['formasi_lajur'];
	$this->view->ket = $_REQUEST['ket'];
	$this->view->id = $_REQUEST['id'];
	
	$this->view->detailFormulir2 = array();
	if($this->view->par == 'Update'){
		$masukan = array("id" => $this->view->id);
		$this->view->detailFormulir2 = $this->formulir2_serv->detailFormulir2($masukan);
	}
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertformulir2Action(){
	$gol_ruang_gaji = $_POST['gol_ruang_gaji'];
	$bezetting = $_POST['bezetting'];
	$thn_skrg = $_POST['thn_skrg'];
	$thn_sblm = $_POST['thn_sblm'];
	$knaikn_pngkt = $_POST['knaikn_pngkt'];
	$stlh_naik_pgkt = $_POST['stlh_naik_pgkt'];
	$pengangkt_pgwai_br = $_POST['pengangkt_pgwai_br'];
	$pndah_dr_instasi_lain = $_POST['pndah_dr_instasi_lain'];
	$pndah_ke_instasi_lain = $_POST['pndah_ke_instasi_lain'];
	$formasi_lajur = $stlh_naik_pgkt + $pengangkt_pgwai_br;
	$ket = $_POST['ket'];
	
	$masukanInsert = array("gol_ruang_gaji" => $gol_ruang_gaji,
			"bezetting" => $bezetting,
			"thn_skrg" => $thn_skrg,
			"thn_sblm" => $thn_sblm,
			"knaikn_pngkt" => $knaikn_pngkt,
			"stlh_naik_pgkt" => $stlh_naik_pgkt,
			"pengangkt_pgwai_br" => $pengangkt_pgwai_br,
			"pndah_dr_instasi_lain" => $pndah_dr_instasi_lain,
			"pndah_ke_instasi_lain" => $pndah_ke_instasi_lain,
			"formasi_lajur" => $formasi_lajur,
			"ket" => $ket,
			"i_entry" => $this->userid);
	$hasil = $this->formulir2_serv->tambahformulir2($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Formulir 2';
	$this->view->status	= $hasil;
	$this->listformulir2Action();
	$this->render(listformulir2);
}

public function updateformulir2Action(){
	$id = $_POST['id'];
	$gol_ruang_gaji = $_POST['gol_ruang_gaji'];
	$bezetting = $_POST['bezetting'];
	$thn_skrg = $_POST['thn_skrg'];
	$thn_sblm = $_POST['thn_sblm'];
	$knaikn_pngkt = $_POST['knaikn_pngkt'];
	$stlh_naik_pgkt = $_POST['stlh_naik_pgkt'];
	$pengangkt_pgwai_br = $_POST['pengangkt_pgwai_br'];
	$pndah_dr_instasi_lain = $_POST['pndah_dr_instasi_lain'];
	$pndah_ke_instasi_lain = $_POST['pndah_ke_instasi_lain'];
	$formasi_lajur = $stlh_naik_pgkt + $pengangkt_pgwai_br;
	$ket = $_POST['ket'];
	
	$masukanInsert = array("id" => $id,
			"gol_ruang_gaji" => $gol_ruang_gaji,
			"bezetting" => $bezetting,
			"thn_skrg" => $thn_skrg,
			"thn_sblm" => $thn_sblm,
			"knaikn_pngkt" => $knaikn_pngkt,
			"stlh_naik_pgkt" => $stlh_naik_pgkt,
			"pengangkt_pgwai_br" => $pengangkt_pgwai_br,
			"pndah_dr_instasi_lain" => $pndah_dr_instasi_lain,
			"pndah_ke_instasi_lain" => $pndah_ke_instasi_lain,
			"formasi_lajur" => $formasi_lajur,
			"ket" => $ket,
			"i_entry" => $this->userid);
	$hasil = $this->formulir2_serv->ubahformulir2($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Formulir 2';
	$this->view->status	= $hasil;
	$this->listformulir2Action();
	$this->render(listformulir2);
}	
}?>