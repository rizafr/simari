<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Formulir3r_Service.php";

class Sdmmodule_Formulir3rController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->formulir3r_serv = Formulir3r_Service::getInstance();

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
    
public function formulir3rjsAction() 
{
	header('content-type : text/javascript');
	$this->render('formulir3rjs');
}	
	
public function listformulir3rAction() {
	$tahun = $_REQUEST['tahun'];
	$thn_skr = $_REQUEST['thn_skr'];
	//$unit = strstr($_REQUEST['unit_organisasi'],"-");
	
	//$unit_organisasi = substr($unit,1,strlen($unit)-1);
	
	$this->view->tahun = $tahun;
	$this->view->thn_skr = $thn_skr;
	//$this->view->unit_organisasi = $unit_organisasi;
	$this->view->listFormulir3r = $this->formulir3r_serv->getformulir3r($tahun);
	$this->view->listJumFormulir3r = $this->formulir3r_serv->getJumformulir3r($tahun);
	//$this->view->listGol = $this->formulir2_serv->getgol();
}

public function formulir3rolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	//$this->view->unit_organisasi = $_REQUEST['unit_organisasi'];
	$this->view->c_jabatan = $_REQUEST['c_jabatan'];
	$this->view->nm_jabatan = $_REQUEST['nm_jabatan'];
	$this->view->thn_skrg = $_REQUEST['thn_skrg'];
	$this->view->pendidikan = $_REQUEST['pendidikan'];
	$this->view->jurusan = $_REQUEST['jurusan'];
	$this->view->gol_ruang = $_REQUEST['gol_ruang'];
	$this->view->jml = $_REQUEST['jml'];
	$this->view->ket = $_REQUEST['ket'];
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
	
	$this->view->listLokasi = $this->formulir3r_serv->getLokasi();
	if($lokasi[0] != ""){
	$this->view->listEselon1 = $this->formulir3r_serv->getEselon1($lokasi[0]);
	}else{
	$this->view->listEselon1 = $this->formulir3r_serv->getEselon1($_REQUEST['c_lokasi_unit']);
	}
	
	if($lokasi[0] != "" && $c_eselon1[0] != ""){
	$this->view->listEselon2 = $this->formulir3r_serv->getEselon2($lokasi[0],$c_eselon1[0]);
	}else{
	$this->view->listEselon2 = $this->formulir3r_serv->getEselon2($_REQUEST['c_lokasi_unit'],$_REQUEST['c_eselon_i']);
	}
	
	if($lokasi[0] != "" && $c_eselon1[0] != "" && $c_eselon2[0] != "" && $c_eselon2[1] != ""){
	$this->view->listEselon3 = $this->formulir3r_serv->getEselon3($lokasi[0],$c_eselon1[0],$c_eselon2[1],$c_eselon2[0]);
	}else{
	$this->view->listEselon3 = $this->formulir3r_serv->getEselon3($_REQUEST['c_lokasi_unit'],$_REQUEST['c_eselon_i'],$_REQUEST['c_eselon_ii'],$_REQUEST['c_eselon_ii_i']);
	}
	
	$this->view->listNamaJabatan = $this->formulir3r_serv->getNamaJabatan();
	
	//$this->view->listUnit = $this->formulir3r_serv->getUnitList();
	
	$this->view->detailFormulir3r = array();
	if($this->view->par == 'Update'){
		$masukan = array("id" => $this->view->id);
		$this->view->detailFormulir3r = $this->formulir3r_serv->detailFormulir3r($masukan);
	}
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertformulir3rAction(){
	//$unit = strstr($_POST['unit_organisasi'],"-");
	//$unit_organisasi = substr($unit,1,strlen($unit)-1);
	$selected = explode(',', $_POST['nm_jabatan']);
	$c_jabatan = $selected[0];
	$nm_jabatan = $selected[1];
	$thn_skrg = $_POST['thn_skrg'];
	$pendidikan = $_POST['pendidikan'];
	$jurusan = $_POST['jurusan'];
	$gol_ruang = $_POST['gol_ruang'];
	$jml = $_POST['jml'];
	$ket = $_POST['ket'];
	
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
	
	$masukanInsert = array("nm_jabatan" => $nm_jabatan,
			"thn_skrg" => $thn_skrg,
			"pendidikan" => $pendidikan,
			"jurusan" => $jurusan,
			"gol_ruang" => $gol_ruang,
			"jml" => $jml,
			"ket" => $ket,
			"i_entry" => $this->userid,
			"c_jabatan" => $c_jabatan,
			"c_lokasi_unit" => $c_lokasi_unit,
			"n_eselon_i" => $n_eselon_i,
			"c_eselon_i" => $c_eselon_i,
			"n_eselon_ii" => $n_eselon_ii,
			"c_eselon_ii" => $c_eselon_ii,
			"c_eselon_ii_1" => $c_eselon_ii_1,
			"n_eselon_iii" => $n_eselon_iii,
			"c_eselon_ii_iii" => $c_eselon_ii_iii,
			"c_eselon_iii" => $c_eselon_iii,			
			"n_lokasi_unit" => $n_lokasi_unit);
	$hasil = $this->formulir3r_serv->tambahformulir3r($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Formulir 3-R';
	$this->view->status	= $hasil;
	$this->listformulir3rAction();
	$this->render(listformulir3r);
}

public function updateformulir3rAction(){
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
	$nm_jabatan = $_POST['nm_jabatan'];
	$thn_skrg = $_POST['thn_skrg'];
	$pendidikan = $_POST['pendidikan'];
	$jurusan = $_POST['jurusan'];
	$gol_ruang = $_POST['gol_ruang'];
	$jml = $_POST['jml'];
	$ket = $_POST['ket'];
	
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
	
	$masukanInsert = array("id" => $id,
			"nm_jabatan" => $nm_jabatan,
			"thn_skrg" => $thn_skrg,
			"pendidikan" => $pendidikan,
			"jurusan" => $jurusan,
			"gol_ruang" => $gol_ruang,
			"jml" => $jml,
			"ket" => $ket,
			"i_entry" => $this->userid,
			"c_jabatan" => $c_jabatan,
			"c_lokasi_unit" => $c_lokasi_unit,
			"n_eselon_i" => $n_eselon_i,
			"c_eselon_i" => $c_eselon_i,
			"n_eselon_ii" => $n_eselon_ii,
			"c_eselon_ii" => $c_eselon_ii,
			"c_eselon_ii_1" => $c_eselon_ii_1,
			"n_eselon_iii" => $n_eselon_iii,
			"c_eselon_ii_iii" => $c_eselon_ii_iii,
			"c_eselon_iii" => $c_eselon_iii,			
			"n_lokasi_unit" => $n_lokasi_unit);
	$hasil = $this->formulir3r_serv->ubahformulir3r($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Formulir 3-R';
	$this->view->status	= $hasil;
	$this->listformulir3rAction();
	$this->render(listformulir3r);
}

public function deleteformulir3rAction(){
	$id = $_REQUEST['id'];
	
	$masukanInsert = array("id" => $id);
	$hasil = $this->formulir3r_serv->hapusformulir3r($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Formulir 3r';
	$this->view->status	= $hasil;
	$this->listformulir3rAction();
	$this->render(listformulir3r);
}	
}?>