<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refnilai_Service.php";

class Sdmmodule_RefnilaiController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refnilai_serv = Refnilai_Service::getInstance();

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
public function nilaijsAction() 
{
	header('content-type : text/javascript');
	$this->render('nilaijs');
}	
	
public function listnilaiAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refnilai_serv->getNilaiList($cari, 0, 0);
	$this->view->listNilai = $this->refnilai_serv->getNilaiList($cari ,$currentPage,$numToDisplay );
	
}

public function nilaiolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_nilai_kinerja = $_REQUEST['c_nilai_kinerja'];
	$this->view->n_faktor_kinerja = $_REQUEST['n_faktor_kinerja'];
	$this->view->n_standar_kinerja = $_REQUEST['n_standar_kinerja'];
	//$this->view->d_nilai_kinerja = $_REQUEST['d_nilai_kinerja'];
	$this->view->q_nilai_dibawah = $_REQUEST['q_nilai_dibawah'];
	$this->view->q_nilai_perbaikan = $_REQUEST['q_nilai_perbaikan'];
	$this->view->q_nilai_sesuai = $_REQUEST['q_nilai_sesuai'];
	$this->view->q_nilai_diatas = $_REQUEST['q_nilai_diatas'];
			
	//$masukan = array("c_nilai" => $c_nilai);
	
	$this->view->detailNilai = array();
	if($this->view->par == 'update'){
		$masukan = array("c_nilai_kinerja" => $this->view->c_nilai_kinerja);
		$this->view->detailNilai = $this->refnilai_serv->detailNilai($masukan);
	}
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertnilaiAction(){
	$c_nilai_kinerja = $_POST['c_nilai_kinerja'];
	$n_faktor_kinerja = $_POST['n_faktor_kinerja'];
	$n_standar_kinerja = $_POST['n_standar_kinerja'];
	$d_nilai_kinerja = $_POST['d_nilai_kinerja'];
	$q_nilai_dibawah = $_POST['q_nilai_dibawah'];
	$q_nilai_perbaikan = $_POST['q_nilai_perbaikan'];
	$q_nilai_sesuai = $_POST['q_nilai_sesuai'];
	$q_nilai_diatas = $_POST['q_nilai_diatas'];
			
	$masukanInsert = array("c_nilai_kinerja" => $c_nilai_kinerja,
			"n_faktor_kinerja" => $n_faktor_kinerja,
			"n_standar_kinerja" => $n_standar_kinerja,
			"d_nilai_kinerja" => $d_nilai_kinerja,
			"q_nilai_dibawah" => $q_nilai_dibawah,
			"q_nilai_perbaikan" => $q_nilai_perbaikan,
			"q_nilai_sesuai" => $q_nilai_sesuai,
			"q_nilai_diatas" => $q_nilai_diatas,
			"i_entry" => $this->userid);
	$hasil = $this->refnilai_serv->tambahnilai($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Form Penilaian';
	$this->view->status	= $hasil;
	$this->listnilaiAction();
	$this->render(listnilai);
}
	
public function updatenilaiAction(){
	$c_nilai_kinerja = $_POST['c_nilai_kinerja'];
	$n_faktor_kinerja = $_POST['n_faktor_kinerja'];
	$n_standar_kinerja = $_POST['n_standar_kinerja'];
	$d_nilai_kinerja = $_POST['d_nilai_kinerja'];
	$q_nilai_dibawah = $_POST['q_nilai_dibawah'];
	$q_nilai_perbaikan = $_POST['q_nilai_perbaikan'];
	$q_nilai_sesuai = $_POST['q_nilai_sesuai'];
	$q_nilai_diatas = $_POST['q_nilai_diatas'];
	
	$masukanInsert = array("c_nilai_kinerja" => $c_nilai_kinerja,
			"n_faktor_kinerja" => $n_faktor_kinerja,
			"n_standar_kinerja" => $n_standar_kinerja,
			"d_nilai_kinerja" => $d_nilai_kinerja,
			"q_nilai_dibawah" => $q_nilai_dibawah,
			"q_nilai_perbaikan" => $q_nilai_perbaikan,
			"q_nilai_sesuai" => $q_nilai_sesuai,
			"q_nilai_diatas" => $q_nilai_diatas,
			"i_entry" => $this->userid);
	$hasil = $this->refnilai_serv->ubahnilai($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Form Penilaian';
	$this->view->status	= $hasil;
	$this->listnilaiAction();
	$this->render(listnilai);
}	

public function deletenilaiAction(){
	$c_nilai_kinerja = $_REQUEST['c_nilai_kinerja'];
	
	$masukanInsert = array("c_nilai_kinerja" => $c_nilai_kinerja);
	$hasil = $this->refnilai_serv->hapusnilai($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Form Penilaian';
	$this->view->status	= $hasil;
	$this->listnilaiAction();
	$this->render(listnilai);
}

}
?>
