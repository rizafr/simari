<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Formulir1_Service.php";
require_once "service/sdm/Sdm_formasi_Service.php";

class Sdmmodule_Formulir1Controller extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->formulir1_serv = Formulir1_Service::getInstance();
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
    
public function formulir1jsAction() 
{
	header('content-type : text/javascript');
	$this->render('formulir1js');
}	
	
public function listformulir1Action() {
	if($_GET['tahun'] != ""){
		$tahun = $_GET['tahun'];
	}
	if($_GET['proses'] != ""){
		$proses = $_GET['proses'];
	}
	$this->view->tahun = $tahun;
	$this->view->thn_sblm = $tahun-1;
	$this->view->proses = $proses;
	if($proses == 'prs') $this->view->listGol = $this->formulir1_serv->getdatagol();
	else if($proses == 'upd') $this->view->listGol = $this->formulir1_serv->getdataformasi($tahun);
	
	
}

public function simpanformulirAction(){
	$listGol = $this->formulir1_serv->getdatagol();
	$tahun	= $_POST["tahun"];
	$this->view->tahun=$tahun;
	$proses	= $_POST["proses"];
		$this->formulir1_serv->hapusformulir($tahun);
			
	foreach ($listGol as $datapkt){
		$c_peg_golongan = $datapkt->c_peg_golongan;
		$bez		= 'bez'.$c_peg_golongan;
		$kpcrt		= 'kpcrt'.$c_peg_golongan;
		$pegnew		= 'pegnew'.$c_peg_golongan;
		$pegmasuk	= 'pegmasuk'.$c_peg_golongan;
		$pegout		= 'pegout'.$c_peg_golongan;
		$pegpensiun	= 'pegpensiun'.$c_peg_golongan;
		$ket		= 'ket'.$c_peg_golongan;
		$bezetting				= $_POST["$bez"];
		$knaikn_pngkt			= $_POST["$kpcrt"];
		$pengangkt_pgwai_br		= $_POST["$pegnew"];
		$pndah_dr_instasi_lain	= $_POST["$pegmasuk"];
		$pndah_ke_instasi_lain	= $_POST["$pegout"];
		$pns_yg_brhnti			= $_POST["$pegpensiun"];
		$keterangan				= $_POST["$ket"];
		
		$masukanInsert = array("c_golongan" => $c_peg_golongan,
			"bezetting" => $bezetting,
			"thn_skrg" => $tahun,
			"knaikn_pngkt" => $knaikn_pngkt,
			"pengangkt_pgwai_br" => $pengangkt_pgwai_br,
			"pndah_dr_instasi_lain" => $pndah_dr_instasi_lain,
			"pndah_ke_instasi_lain" => $pndah_ke_instasi_lain,
			"pns_yg_brhnti" => $pns_yg_brhnti,
			"ket" => $keterangan,
			"i_entry" => $this->userid);
		
			$hasil = $this->formulir1_serv->tambahformulir($masukanInsert);
		
	}
	//$this->view->proses 	= '1';
	$this->view->keterangan = 'Formulir 1';
	$this->view->status	= $hasil;
	$this->listformulir1Action();
	$this->render(listformulir1);
}

}?>