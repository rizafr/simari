<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refjabatankeu_Service.php";

class Sdmmodule_RefjabatankeuController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refjabatankeu_serv = Refjabatankeu_Service::getInstance();

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
public function jabatankeujsAction() 
{
	header('content-type : text/javascript');
	$this->render('jabatankeujs');
}	
	
public function listjabatankeuAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refjabatankeu_serv->getJabatankeuList($cari, 0, 0);
	$this->view->listJabatankeu = $this->refjabatankeu_serv->getJabatankeuList($cari ,$currentPage,$numToDisplay );
	
}

public function jabatankeuolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->id = $_REQUEST['id'];
	$this->view->n_jabatan = $_REQUEST['n_jabatan'];
	$this->view->d_awal = $_REQUEST['d_awal'];
	$this->view->d_akhir = $_REQUEST['d_akhir'];
	$this->view->n_sk = $_REQUEST['n_sk'];
	$this->view->c_statusdelete = $_REQUEST['c_statusdelete'];
	$this->view->d_statusdelete = $_REQUEST['d_statusdelete'];
		
	$this->view->detailJabatankeu = array();
	if($this->view->par == 'update'){
		$masukan = array("id" => $this->view->id);
		$this->view->detailJabatankeu = $this->refjabatankeu_serv->detailJabatankeu($masukan);
	}
}

public function insertjabatankeuAction(){
	$id = $_POST['id'];
	$n_jabatan = $_POST['n_jabatan'];
	$d_awal = $_POST['d_awal'];
	$d_akhir = $_POST['d_akhir'];
	$n_sk = $_POST['n_sk'];
	$c_statusdelete = $_POST['c_statusdelete'];
	$d_statusdelete = $_POST['d_statusdelete'];
	
	$masukanInsert = array("id"	=> $id,
				"n_jabatan"	=> $n_jabatan,
				"d_awal"	=> $d_awal,
				"d_akhir"	=> $d_akhir,
				"n_sk"	=> $n_sk,
				"c_statusdelete"	=> $c_statusdelete,
				"d_statusdelete"	=> $d_statusdelete,
				"i_entry" => 1);
	$hasil = $this->refjabatankeu_serv->tambahjabatankeu($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Jabatan keuangan';
	$this->view->status	= $hasil;
	$this->listjabatankeuAction();
	$this->render(listjabatankeu);
}

public function updatejabatankeuAction(){
	$id = $_POST['id'];
	$n_jabatan = $_POST['n_jabatan'];
	$d_awal = $_POST['d_awal'];
	$d_akhir = $_POST['d_akhir'];
	$n_sk = $_POST['n_sk'];
	$c_statusdelete = $_POST['c_statusdelete'];
	$d_statusdelete = $_POST['d_statusdelete'];
	
	$masukanInsert = array("id"	=> $id,
				"n_jabatan"	=> $n_jabatan,
				"d_awal"	=> $d_awal,
				"d_akhir"	=> $d_akhir,
				"n_sk"	=> $n_sk,
				"c_statusdelete"	=> $c_statusdelete,
				"d_statusdelete"	=> $d_statusdelete,
				"i_entry" => 2);
	$hasil = $this->refjabatankeu_serv->ubahjabatankeu($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Jabatan keuangan';
	$this->view->status	= $hasil;
	$this->listjabatankeuAction();
	$this->render(listjabatankeu);
}	

public function deletejabatankeuAction(){
	$id = $_REQUEST['id'];
	
	$masukanInsert = array("id" => $id);
	$hasil = $this->refjabatankeu_serv->hapusjabatankeu($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Jabatan keuangan';
	$this->view->status	= $hasil;
	$this->listjabatankeuAction();
	$this->render(listjabatankeu);
}

}
?>