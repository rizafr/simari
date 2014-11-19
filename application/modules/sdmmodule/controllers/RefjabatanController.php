<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refjabatan_Service.php";
require_once "service/sdm/sdm_refferensi_Service.php";

class Sdmmodule_RefjabatanController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refjabatan_serv = Refjabatan_Service::getInstance();
		$this->reff_serv = sdm_refferensi_Service::getInstance();
		
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
public function jabatanjsAction() 
{
	header('content-type : text/javascript');
	$this->render('jabatanjs');
}	
	
public function listjabatanAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refjabatan_serv->getJabatanList($cari, 0, 0);
	$this->view->listJabatan = $this->refjabatan_serv->getJabatanList($cari ,$currentPage,$numToDisplay );
	
}

public function jabatanolahdataAction() {
	$eselonRef = $this->reff_serv->getEselonList('');
		$this->view->eselonRef=$eselonRef;
	$carigol=" and c_peg_tipegolongan ='3' ";
	$golRef = $this->reff_serv->getGolonganPegawai($carigol);
	$this->view->golRef =$golRef;
	$pendRef = $this->reff_serv->getPendidikan('');
	$this->view->pendRef = $pendRef;
	$jabatanKelFungList = $this->reff_serv->getKelPelatihanFungsional('');		
	$this->view->jabatanKelFungList = $jabatanKelFungList;
	$tingkatFungList = $this->reff_serv->getTingkatFungsional('');		
	$this->view->tingkatFungList = $tingkatFungList;
		
	$this->view->par = $_REQUEST['par'];
	$this->view->c_jabatan = $_REQUEST['c_jabatan'];
	// $this->view->n_jabatan = $_REQUEST['n_jabatan'];
	// $this->view->e_keterangan = $_REQUEST['e_keterangan'];
	// $this->view->c_tkfgs = $_REQUEST['c_tkfgs'];
	// $this->view->c_kelfgs = $_REQUEST['c_kelfgs'];
	// $this->view->c_golr = $_REQUEST['c_golr'];
	// $this->view->c_golt = $_REQUEST['c_golt'];
	// $this->view->n_jenjang = $_REQUEST['n_jenjang'];
	// $this->view->c_tanda = $_REQUEST['c_tanda'];
	// $this->view->c_eselon = $_REQUEST['c_eselon'];
	// $this->view->c_strata = $_REQUEST['c_strata'];
	// $this->view->q_tunjangan = $_REQUEST['q_tunjangan'];
	// $this->view->q_usia_pens = $_REQUEST['q_usia_pens'];
	// $this->view->q_tktfgs = $_REQUEST['q_tktfgs'];
	// $this->view->q_ak_minimal = $_REQUEST['q_ak_minimal'];
				
	$this->view->detailJabatan = array();
	if($this->view->par == 'update'){
		$masukan = array("c_jabatan" => $this->view->c_jabatan);
		$this->view->detailJabatan = $this->refjabatan_serv->detailJabatan($masukan);
	}
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertjabatanAction(){
	
	$c_jabatan = $this->refjabatan_serv->getCodeJabatan();
	//$_POST['c_jabatan'];
	
	$n_jabatan = $_POST['n_jabatan'];
	$e_keterangan = $_POST['e_keterangan'];
	$c_kelfgs = $_POST['c_kelfgs'];
	if($_POST['c_tkfgs']){
		list($c_tkfgs,$q_tktfgs) = split('@',$_POST['c_tkfgs']);
	}
	$c_golr = $_POST['c_golr'];
	$c_golt = $_POST['c_golt'];
	$n_jenjang = $_POST['n_jenjang'];
	$c_tanda = $_POST['c_tanda'];
	$c_eselon = $_POST['c_eselon'];
	$c_strata = $_POST['c_strata'];
	$q_tunjangan = $_POST['q_tunjangan'] ? $_POST['q_tunjangan'] : 0;
	$q_usia_pens = $_POST['q_usia_pens'];
	//$q_tktfgs = $_POST['q_tktfgs'];
	$q_ak_minimal = $_POST['q_ak_minimal'] ? $_POST['q_ak_minimal'] : 0;
				
	$masukanInsert = array("c_jabatan" => $c_jabatan,
			"n_jabatan" => $n_jabatan,
			"e_keterangan" => $e_keterangan,
			"c_tkfgs" => $c_tkfgs,
			"c_kelfgs" => $c_kelfgs,
			"c_golr" => $c_golr,
			"c_golt" => $c_golt,
			"n_jenjang" => $n_jenjang,
			"c_tanda" => $c_tanda,
			"c_eselon" => $c_eselon,
			"c_strata" => $c_strata,
			"q_tunjangan" => $q_tunjangan,
			"q_usia_pens" => $q_usia_pens,
			"q_tktfgs" => $q_tktfgs,
			"q_ak_minimal" => $q_ak_minimal);
	$hasil = $this->refjabatan_serv->tambahjabatan($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Jabatan';
	$this->view->status	= $hasil;
	$this->listjabatanAction();
	$this->render(listjabatan);
}

public function updatejabatanAction(){
	$c_jabatan = $_POST['c_jabatan'];
	$n_jabatan = $_POST['n_jabatan'];
	$e_keterangan = $_POST['e_keterangan'];
	$c_kelfgs = $_POST['c_kelfgs'];
	if($_POST['c_tkfgs']){
		list($c_tkfgs,$q_tktfgs) = split('@',$_POST['c_tkfgs']);
	}
	$c_golr = $_POST['c_golr'];
	$c_golt = $_POST['c_golt'];
	$n_jenjang = $_POST['n_jenjang'];
	$c_tanda = $_POST['c_tanda'];
	$c_eselon = $_POST['c_eselon'];
	$c_strata = $_POST['c_strata'];
	$q_tunjangan = $_POST['q_tunjangan'] ? $_POST['q_tunjangan'] : 0;
	$q_usia_pens = $_POST['q_usia_pens'];
	//$q_tktfgs = $_POST['q_tktfgs'];
	$q_ak_minimal = $_POST['q_ak_minimal'] ? $_POST['q_ak_minimal'] : 0;
	
	
	$masukanInsert = array("c_jabatan" => $c_jabatan,
			"n_jabatan" => $n_jabatan,
			"e_keterangan" => $e_keterangan,
			"c_tkfgs" => $c_tkfgs,
			"c_kelfgs" => $c_kelfgs,
			"c_golr" => $c_golr,
			"c_golt" => $c_golt,
			"n_jenjang" => $n_jenjang,
			"c_tanda" => $c_tanda,
			"c_eselon" => $c_eselon,
			"c_strata" => $c_strata,
			"q_tunjangan" => $q_tunjangan,
			"q_usia_pens" => $q_usia_pens,
			"q_tktfgs" => $q_tktfgs,
			"q_ak_minimal" => $q_ak_minimal);
	$hasil = $this->refjabatan_serv->ubahjabatan($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Jabatan';
	$this->view->status	= $hasil;
	$this->listjabatanAction();
	$this->render(listjabatan);
}	

public function deletejabatanAction(){
	$c_jabatan = $_REQUEST['c_jabatan'];
	
	$masukanInsert = array("c_jabatan" => $c_jabatan);
	$hasil = $this->refjabatan_serv->hapusjabatan($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Jabatan';
	$this->view->status	= $hasil;
	$this->listjabatanAction();
	$this->render(listjabatan);
}

}
?>