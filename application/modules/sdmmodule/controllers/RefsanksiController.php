<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refsanksi_Service.php";

class Sdmmodule_RefsanksiController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->refsanksi_serv = Refsanksi_Service::getInstance();

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
public function sanksijsAction() 
{
	header('content-type : text/javascript');
	$this->render('sanksijs');
}	
	
public function listsanksiAction() {
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 20;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	$this->view->totalData = $this->refsanksi_serv->getSanksiList($cari, 0, 0);
	$this->view->listSanksi = $this->refsanksi_serv->getSanksiList($cari ,$currentPage,$numToDisplay );
	
}

public function sanksiolahdataAction() {
	$this->view->par = $_REQUEST['par'];
	$this->view->c_jns_sanksi = $_REQUEST['c_jns_sanksi'];
	$this->view->n_jns_sanksi = $_REQUEST['n_jns_sanksi'];
	$this->view->MDISIPLINJNS_SORT = $_REQUEST['MDISIPLINJNS_SORT'];
	$this->view->c_status = $_REQUEST['c_status'];
	
	$this->view->detailSanksi = array();
	if($this->view->par == 'update'){
	$masukan = array("c_jns_sanksi" => $this->view->c_jns_sanksi);
	$this->view->detailSanksi = $this->refsanksi_serv->detailSanksi($masukan);
	}
	//$this->view->listAgama = $this->refagama_serv->getAgamaList($cari);
}

public function insertsanksiAction(){
	$c_jns_sanksi = $_POST['c_jns_sanksi'];
	$n_jns_sanksi = $_POST['n_jns_sanksi'];
	$MDISIPLINJNS_SORT = $_POST['MDISIPLINJNS_SORT'];
	$c_status = $_POST['c_status'];
	
	$masukanInsert = array("c_jns_sanksi" => $c_jns_sanksi,
			"n_jns_sanksi" => $n_jns_sanksi,
			"MDISIPLINJNS_SORT" => $MDISIPLINJNS_SORT,
			"c_status" => $c_status);
	$hasil = $this->refsanksi_serv->tambahsanksi($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Jenis Sanksi';
	$this->view->status	= $hasil;
	$this->listsanksiAction();
	$this->render(listsanksi);
}

public function updatesanksiAction(){
	$c_jns_sanksi = $_POST['c_jns_sanksi'];
	$n_jns_sanksi = $_POST['n_jns_sanksi'];
	$MDISIPLINJNS_SORT = $_POST['MDISIPLINJNS_SORT'];
	$c_status = $_POST['c_status'];
	
	$masukanInsert = array("c_jns_sanksi" => $c_jns_sanksi,
			"n_jns_sanksi" => $n_jns_sanksi,
			"MDISIPLINJNS_SORT" => $MDISIPLINJNS_SORT,
			"c_status" => $c_status);
	$hasil = $this->refsanksi_serv->ubahsanksi($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Jenis Sanksi';
	$this->view->status	= $hasil;
	$this->listsanksiAction();
	$this->render(listsanksi);
}	

public function deletesanksiAction(){
	$c_jns_sanksi = $_REQUEST['c_jns_sanksi'];
	
	$masukanInsert = array("c_jns_sanksi" => $c_jns_sanksi);
	$hasil = $this->refsanksi_serv->hapussanksi($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Jenis Sanksi';
	$this->view->status	= $hasil;
	$this->listsanksiAction();
	$this->render(listsanksi);
}

}
?>