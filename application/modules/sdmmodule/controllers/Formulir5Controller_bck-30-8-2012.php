<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Formulir5_Service.php";

class Sdmmodule_Formulir5Controller extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->formulir5_serv = Formulir5_Service::getInstance();

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
    
public function formulir5jsAction() 
{
	header('content-type : text/javascript');
	$this->render('formulir5js');
}	
	
public function listformulir5Action() {
	$tahun = $_REQUEST['tahun'];
	$thn_skr = $_REQUEST['thn_skr'];
	$thn_sblm = $_REQUEST['thn_sblm'];
	//$unit = strstr($_REQUEST['unit_organisasi'],"-");
	
	//$unit_organisasi = substr($unit,1,strlen($unit)-1);
	
	$this->view->tahun = $tahun;
	$this->view->thn_skr = $thn_skr;
	$this->view->thn_sblm = $thn_sblm;
	//$this->view->unit_organisasi = $unit_organisasi;
	$this->view->listFormulir5 = $this->formulir5_serv->getformulir5($tahun);
	$this->view->listJumFormulir5 = $this->formulir5_serv->getJumformulir5($tahun);
	//$this->view->listGol = $this->formulir2_serv->getgol();
}

public function formulir5olahdataAction() {
	$this->view->par = $_REQUEST['par'];
	//$this->view->unit_organisasi = $_REQUEST['unit_organisasi'];
	$this->view->c_jabatan = $_REQUEST['c_jabatan'];
	$this->view->nm_jabatan = $_REQUEST['nm_jabatan'];
	$this->view->thn_skrg = $_REQUEST['thn_skrg'];
	$this->view->thn_sblm = $_REQUEST['thn_sblm'];
	/*$this->view->iv_a_b = $_REQUEST['iv_a_b'];
	$this->view->iv_a_f = $_REQUEST['iv_a_f'];
	$this->view->iv_b_b = $_REQUEST['iv_b_b'];
	$this->view->iv_b_f = $_REQUEST['iv_b_f'];
	$this->view->iv_c_b = $_REQUEST['iv_c_b'];
	$this->view->iv_c_f = $_REQUEST['iv_c_f'];
	$this->view->iv_d_b = $_REQUEST['iv_d_b'];
	$this->view->iv_d_f = $_REQUEST['iv_d_f'];
	$this->view->iv_e_b = $_REQUEST['iv_e_b'];
	$this->view->iv_e_f = $_REQUEST['iv_e_f'];
	$this->view->ket = $_REQUEST['ket'];*/
	$this->view->id = $_REQUEST['id'];
	
	$this->view->c_lokasi = $_REQUEST['c_lokasi_unit'];
	$this->view->n_lokasi = $_REQUEST['n_lokasi_unit'];
	$lokasi = explode(',' , $_REQUEST['lokasi']);
	$c_eselon1 = explode(',' , $_REQUEST['eselon1']);
	$c_eselon2 = explode(',' , $_REQUEST['eselon2']);
	$c_eselon3 = explode(',' , $_REQUEST['eselon3']);
	$this->view->c_eselon1 = $_REQUEST['c_eselon_i'];
	$this->view->n_eselon1 = $_REQUEST['n_eselon_i'];
	$this->view->c_eselon2 = $_REQUEST['c_eselon_ii'];
	$this->view->c_eselon22 = $_REQUEST['c_eselon_ii_i'];
	$this->view->n_eselon2 = $_REQUEST['n_eselon_ii'];
	$this->view->c_eselon23 = $_REQUEST['c_eselon_ii_iii'];
	$this->view->c_eselon3 = $_REQUEST['c_eselon_iii'];
	$this->view->n_eselon3 = $_REQUEST['n_eselon_iii'];
	$this->view->listLokasi = $this->formulir5_serv->getLokasi();
	if($lokasi[0] != ""){
	$this->view->listEselon1 = $this->formulir5_serv->getEselon1($lokasi[0]);
	}else{
	$this->view->listEselon1 = $this->formulir5_serv->getEselon1($_REQUEST['c_lokasi_unit']);
	}
	
	if($lokasi[0] != "" && $c_eselon1[0] != ""){
	$this->view->listEselon2 = $this->formulir5_serv->getEselon2($lokasi[0],$c_eselon1[0]);
	}else{
	$this->view->listEselon2 = $this->formulir5_serv->getEselon2($_REQUEST['c_lokasi_unit'],$_REQUEST['c_eselon_i']);
	}
	
	if($lokasi[0] != "" && $c_eselon1[0] != "" && $c_eselon2[0] != "" && $c_eselon2[1] != ""){
	$this->view->listEselon3 = $this->formulir5_serv->getEselon3($lokasi[0],$c_eselon1[0],$c_eselon2[1],$c_eselon2[0]);
	}else{
	$this->view->listEselon3 = $this->formulir5_serv->getEselon3($_REQUEST['c_lokasi_unit'],$_REQUEST['c_eselon_i'],$_REQUEST['c_eselon_ii'],$_REQUEST['c_eselon_ii_i']);
	}
	
	if($c_eselon1[0] != ""){
	$this->view->listJumPegEselon1IVE = $this->formulir5_serv->getJumpegEselon1IVE($c_eselon1[0]);
	$this->view->listJumPegEselon1IVD = $this->formulir5_serv->getJumpegEselon1IVD($c_eselon1[0]);
	$this->view->listJumPegEselon1IVC = $this->formulir5_serv->getJumpegEselon1IVC($c_eselon1[0]);
	$this->view->listJumPegEselon1IVB = $this->formulir5_serv->getJumpegEselon1IVB($c_eselon1[0]);
	$this->view->listJumPegEselon1IVA = $this->formulir5_serv->getJumpegEselon1IVA($c_eselon1[0]);
	}
	
	if($c_eselon2[1] != ""){
	$this->view->listJumPegEselon2IVE = $this->formulir5_serv->getJumpegEselon2IVE($c_eselon2[1]);
	$this->view->listJumPegEselon2IVD = $this->formulir5_serv->getJumpegEselon2IVD($c_eselon2[1]);
	$this->view->listJumPegEselon2IVC = $this->formulir5_serv->getJumpegEselon2IVC($c_eselon2[1]);
	$this->view->listJumPegEselon2IVB = $this->formulir5_serv->getJumpegEselon2IVB($c_eselon2[1]);
	$this->view->listJumPegEselon2IVA = $this->formulir5_serv->getJumpegEselon2IVA($c_eselon2[1]);
	}
		
	if($lokasi[0] == "1"){
		if($c_eselon3[1] != ""){
		$this->view->listJumPegEselon3IVE = $this->formulir5_serv->getJumpegEselon3IVE($c_eselon3[1]);
		$this->view->listJumPegEselon3IVD = $this->formulir5_serv->getJumpegEselon3IVD($c_eselon3[1]);
		$this->view->listJumPegEselon3IVC = $this->formulir5_serv->getJumpegEselon3IVC($c_eselon3[1]);
		$this->view->listJumPegEselon3IVB = $this->formulir5_serv->getJumpegEselon3IVB($c_eselon3[1]);
		$this->view->listJumPegEselon3IVA = $this->formulir5_serv->getJumpegEselon3IVA($c_eselon3[1]);
		}
	}else{
		if($c_eselon3[0] != ""){
		$this->view->listJumPegEselon3IVE = $this->formulir5_serv->getJumpegEselon3IVE($c_eselon3[0]);
		$this->view->listJumPegEselon3IVD = $this->formulir5_serv->getJumpegEselon3IVD($c_eselon3[0]);
		$this->view->listJumPegEselon3IVC = $this->formulir5_serv->getJumpegEselon3IVC($c_eselon3[0]);
		$this->view->listJumPegEselon3IVB = $this->formulir5_serv->getJumpegEselon3IVB($c_eselon3[0]);
		$this->view->listJumPegEselon3IVA = $this->formulir5_serv->getJumpegEselon3IVA($c_eselon3[0]);
		}
	}
	
	$this->view->listNamaJabatan = $this->formulir5_serv->getNamaJabatan();
	
	//$this->view->listUnit = $this->formulir5_serv->getUnitList();
	
	$this->view->detailFormulir5 = array();
	if($this->view->par == 'Update'){
		$masukan = array("id" => $this->view->id);
		$this->view->detailFormulir5 = $this->formulir5_serv->detailFormulir5($masukan);
	}
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertformulir5Action(){
	//$unit = strstr($_POST['unit_organisasi'],"-");
	//$unit_organisasi = substr($unit,1,strlen($unit)-1);
	$selected = explode(',', $_POST['nm_jabatan']);
	$c_jabatan = $selected[0];
	$nm_jabatan = $selected[1];
	$thn_skrg = $_POST['thn_skrg'];
	$thn_sblm = $_POST['thn_sblm'];
	$iv_e_b_eselon1 = $_POST['iv_e_b_eselon1'];
	$iv_e_b_eselon2 = $_POST['iv_e_b_eselon2'];
	$iv_e_b_eselon3 = $_POST['iv_e_b_eselon3'];
	$iv_e_f = $_POST['iv_e_f'];
	$iv_d_b_eselon1 = $_POST['iv_d_b_eselon1'];
	$iv_d_b_eselon2 = $_POST['iv_d_b_eselon2'];
	$iv_d_b_eselon3 = $_POST['iv_d_b_eselon3'];
	$iv_d_f = $_POST['iv_d_f'];
	$iv_c_b_eselon1 = $_POST['iv_c_b_eselon1'];
	$iv_c_b_eselon2 = $_POST['iv_c_b_eselon2'];
	$iv_c_b_eselon3 = $_POST['iv_c_b_eselon3'];
	$iv_c_f = $_POST['iv_c_f'];
	$iv_b_b_eselon1 = $_POST['iv_b_b_eselon1'];
	$iv_b_b_eselon2 = $_POST['iv_b_b_eselon2'];
	$iv_b_b_eselon3 = $_POST['iv_b_b_eselon3'];
	$iv_b_f = $_POST['iv_b_f'];
	$iv_a_b_eselon1 = $_POST['iv_a_b_eselon1'];
	$iv_a_b_eselon2 = $_POST['iv_a_b_eselon2'];
	$iv_a_b_eselon3 = $_POST['iv_a_b_eselon3'];
	$iv_a_f = $_POST['iv_a_f'];
	
	$lokasi = explode(',' , $_POST['lokasi']);
	$c_lokasi_unit = $lokasi[0];
	$n_lokasi_unit = $lokasi[1];	
	
	$eselon1 = explode(',' , $_POST['eselon1']);
	$n_eselon_i = $eselon1[1];
	$c_eselon_i = $eselon1[0];
	$eselon2 = explode(',' , $_POST['eselon2']);
	$c_eselon_ii = $eselon2[1];
	$c_eselon_ii_1 = $eselon2[0];
	$n_eselon_ii = $eselon2[2];
	$eselon3 = explode(',' , $_POST['eselon3']);
	$c_eselon_ii_iii = $eselon3[0];
	$c_eselon_iii = $eselon3[1];
	$n_eselon_iii = $eselon3[2];
	
	$ket = $_POST['ket'];
	
	$masukanInsert = array("nm_jabatan" => $nm_jabatan,
			"thn_skrg" => $thn_skrg,
			"thn_sblm" => $thn_sblm,
			"iv_e_b_eselon1" => $iv_e_b_eselon1,
			"iv_e_b_eselon2" => $iv_e_b_eselon2,
			"iv_e_b_eselon3" => $iv_e_b_eselon2,
			"iv_e_f" => $iv_e_f,
			"iv_d_b_eselon1" => $iv_d_b_eselon1,
			"iv_d_b_eselon2" => $iv_d_b_eselon2,
			"iv_d_b_eselon3" => $iv_d_b_eselon2,
			"iv_d_f" => $iv_d_f,
			"iv_c_b_eselon1" => $iv_c_b_eselon1,
			"iv_c_b_eselon2" => $iv_c_b_eselon2,
			"iv_c_b_eselon3" => $iv_c_b_eselon2,
			"iv_c_f" => $iv_c_f,
			"iv_b_b_eselon1" => $iv_b_b_eselon1,
			"iv_b_b_eselon2" => $iv_b_b_eselon2,
			"iv_b_b_eselon3" => $iv_b_b_eselon2,
			"iv_b_f" => $iv_b_f,
			"iv_a_b_eselon1" => $iv_a_b_eselon1,
			"iv_a_b_eselon2" => $iv_a_b_eselon2,
			"iv_a_b_eselon3" => $iv_a_b_eselon2,
			"iv_a_f" => $iv_a_f,
			"c_jabatan" => $c_jabatan,
			"c_lokasi_unit" => $c_lokasi_unit,			
			"n_lokasi_unit" => $n_lokasi_unit,
			"n_eselon_i" => $n_eselon_i,
			"c_eselon_i" => $c_eselon_i,
			"n_eselon_ii" => $n_eselon_ii,
			"c_eselon_ii" => $c_eselon_ii,
			"c_eselon_ii_1" => $c_eselon_ii_1,
			"n_eselon_iii" => $n_eselon_iii,
			"c_eselon_ii_iii" => $c_eselon_ii_iii,
			"c_eselon_iii" => $c_eselon_iii,
			"ket" => $ket,
			"i_entry" => $this->userid);
	$hasil = $this->formulir5_serv->tambahformulir5($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Formulir 5';
	$this->view->status	= $hasil;
	$this->listformulir5Action();
	$this->render(listformulir5);
}

public function updateformulir5Action(){
	$id = $_POST['id'];
	/*$unit = strstr($_POST['unit_organisasi'],"-");
	if($unit != ""){
	$unit_organisasi = substr($unit,1,strlen($unit)-1);
	}else{
	$unit_organisasi = $_POST['unit_organisasi'];
	}*/
	$selected = explode(',', $_POST['nm_jabatan']);
	$c_jabatan = $selected[0];
	$nm_jabatan = $selected[1];
	$thn_skrg = $_POST['thn_skrg'];
	$thn_sblm = $_POST['thn_sblm'];
	$iv_e_b_eselon1 = $_POST['iv_e_b_eselon1'];
	$iv_e_b_eselon2 = $_POST['iv_e_b_eselon2'];
	$iv_e_b_eselon3 = $_POST['iv_e_b_eselon3'];
	$iv_e_f = $_POST['iv_e_f'];
	$iv_d_b_eselon1 = $_POST['iv_d_b_eselon1'];
	$iv_d_b_eselon2 = $_POST['iv_d_b_eselon2'];
	$iv_d_b_eselon3 = $_POST['iv_d_b_eselon3'];
	$iv_d_f = $_POST['iv_d_f'];
	$iv_c_b_eselon1 = $_POST['iv_c_b_eselon1'];
	$iv_c_b_eselon2 = $_POST['iv_c_b_eselon2'];
	$iv_c_b_eselon3 = $_POST['iv_c_b_eselon3'];
	$iv_c_f = $_POST['iv_c_f'];
	$iv_b_b_eselon1 = $_POST['iv_b_b_eselon1'];
	$iv_b_b_eselon2 = $_POST['iv_b_b_eselon2'];
	$iv_b_b_eselon3 = $_POST['iv_b_b_eselon3'];
	$iv_b_f = $_POST['iv_b_f'];
	$iv_a_b_eselon1 = $_POST['iv_a_b_eselon1'];
	$iv_a_b_eselon2 = $_POST['iv_a_b_eselon2'];
	$iv_a_b_eselon3 = $_POST['iv_a_b_eselon3'];
	$iv_a_f = $_POST['iv_a_f'];
	
	$lokasi = explode(',' , $_POST['lokasi']);
	$c_lokasi_unit = $lokasi[0];
	$n_lokasi_unit = $lokasi[1];	
	
	$eselon1 = explode(',' , $_POST['eselon1']);
	$n_eselon_i = $eselon1[1];
	$c_eselon_i = $eselon1[0];
	$eselon2 = explode(',' , $_POST['eselon2']);
	$c_eselon_ii = $eselon2[1];
	$c_eselon_ii_1 = $eselon2[0];
	$n_eselon_ii = $eselon2[2];
	$eselon3 = explode(',' , $_POST['eselon3']);
	$c_eselon_ii_iii = $eselon3[0];
	$c_eselon_iii = $eselon3[1];
	$n_eselon_iii = $eselon3[2];
	
	$ket = $_POST['ket'];
	
	$masukanInsert = array("id" => $id,
			"nm_jabatan" => $nm_jabatan,
			"thn_skrg" => $thn_skrg,
			"thn_sblm" => $thn_sblm,
			"iv_e_b_eselon1" => $iv_e_b_eselon1,
			"iv_e_b_eselon2" => $iv_e_b_eselon2,
			"iv_e_b_eselon3" => $iv_e_b_eselon2,
			"iv_e_f" => $iv_e_f,
			"iv_d_b_eselon1" => $iv_d_b_eselon1,
			"iv_d_b_eselon2" => $iv_d_b_eselon2,
			"iv_d_b_eselon3" => $iv_d_b_eselon2,
			"iv_d_f" => $iv_d_f,
			"iv_c_b_eselon1" => $iv_c_b_eselon1,
			"iv_c_b_eselon2" => $iv_c_b_eselon2,
			"iv_c_b_eselon3" => $iv_c_b_eselon2,
			"iv_c_f" => $iv_c_f,
			"iv_b_b_eselon1" => $iv_b_b_eselon1,
			"iv_b_b_eselon2" => $iv_b_b_eselon2,
			"iv_b_b_eselon3" => $iv_b_b_eselon2,
			"iv_b_f" => $iv_b_f,
			"iv_a_b_eselon1" => $iv_a_b_eselon1,
			"iv_a_b_eselon2" => $iv_a_b_eselon2,
			"iv_a_b_eselon3" => $iv_a_b_eselon2,
			"iv_a_f" => $iv_a_f,
			"c_jabatan" => $c_jabatan,
			"c_lokasi_unit" => $c_lokasi_unit,			
			"n_lokasi_unit" => $n_lokasi_unit,
			"n_eselon_i" => $n_eselon_i,
			"c_eselon_i" => $c_eselon_i,
			"n_eselon_ii" => $n_eselon_ii,
			"c_eselon_ii" => $c_eselon_ii,
			"c_eselon_ii_1" => $c_eselon_ii_1,
			"n_eselon_iii" => $n_eselon_iii,
			"c_eselon_ii_iii" => $c_eselon_ii_iii,
			"c_eselon_iii" => $c_eselon_iii,
			"ket" => $ket,
			"i_entry" => $this->userid);
	$hasil = $this->formulir5_serv->ubahformulir5($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Formulir 5';
	$this->view->status	= $hasil;
	$this->listformulir5Action();
	$this->render(listformulir5);
}

public function deleteformulir5Action(){
	$id = $_REQUEST['id'];
	
	$masukanInsert = array("id" => $id);
	$hasil = $this->formulir5_serv->hapusformulir5($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Formulir 5';
	$this->view->status	= $hasil;
	$this->listformulir5Action();
	$this->render(listformulir5);
}	
}?>