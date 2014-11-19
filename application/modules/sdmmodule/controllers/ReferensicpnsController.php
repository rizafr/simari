<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/Sdm_Referensicpns_Service.php";
require_once "service/rencana/Rencana_Renjaprogram_Service.php";
require_once "service/rencana/Rencana_Referensi_Service.php";
require_once "service/adm/Adm_Admgroup_Service.php";
require_once "service/adm/Adm_Admaplikasi_Service.php";
require_once "service/adm/Adm_Admmenu_Service.php";
require_once "share/ldap.lib.php";


class Sdmmodule_ReferensicpnsController extends Zend_Controller_Action {

		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath');
		$this->view->baseData = $registry->get('baseData');		
		$this->view->leftMenu = $registry->get('leftMenu');
		$this->view->dataPerPage = $registry->get('dataPerPage');
		
		//$this->view->photoPath = $registry->get('photoPath');
		 
		$this->refcpns_serv = Sdm_Referensicpns_Service::getInstance();
		

		$ssologin = new Zend_Session_Namespace('ssologin');
		
		if ($ssologin->userid && $ssologin->n_peg){
			$this->userid  			= $ssologin->userid;
			$this->password			= $ssologin->password;
			$this->i_peg_nip  		= $ssologin->i_peg_nip;
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
		}
    }
	
    public function indexAction() {
    }
	
	public function referensicpnsjsAction() 
	{
		header('content-type : text/javascript');
		$this->render('referensicpnsjs');
	}	
	
	public function jabatancpnsAction() {    
		$katakunciCari	= $_REQUEST['katakunciCari'];
		$kategoriCari	= $_REQUEST['kategoriCari'];

		$currentPage	= $_REQUEST['currentPage'];
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
						$currentPage = 1;
		}

		$offset = $currentPage -1;
		//$numToDisplay = 99; //untuk mengeluarkan semua data (tanpa paging)
		$numToDisplay = 20; //untuk mengeluarkan semua data (tanpa paging)

		$data = array("katakunciCari" => $katakunciCari,
					  "kategoriCari" => $kategoriCari);
		//untuk menampilkan jenjang pendidikan di panel pencarian
		$this->view->jenjangPendidikan = $this->refcpns_serv->getJenjangPendidikan();
		
		
		$this->view->totalJabatancpns = $this->refcpns_serv->getJabatancpns($data, 0, 0);
		$this->view->dataJabatancpns = $this->refcpns_serv->getJabatancpns($data, $currentPage, $numToDisplay);
		//var_dump($this->view->dataJabatancpns);
		$this->view->kategoriCari = $kategoriCari;
		$this->view->katakunciCari = $katakunciCari;
		$this->view->totData = $this->view->totalJabatancpns;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
	}

	public function referensicpnsolahdataAction() {
		$this->view->jenisForm 	= $_REQUEST['jenisForm'];
		if(!$this->view->jenisForm){
			$this->view->jenisForm = 'tambah';
		}
		$this->view->id 		= $_REQUEST['id'];
		
		$this->view->kualisikasiPend = $this->refcpns_serv->getKualifikasiPendidikan();
		$this->view->detailJabatancpns = array();
		
		//echo "jenis = ".$this->view->jenisForm;
		if($this->view->jenisForm == 'ubah') {
			$dataMasukan = array("id" => $this->view->id);
			$this->view->detailJabatancpns = $this->refcpns_serv->detailJabatancpns($dataMasukan);
		} 
    }
	
	public function maintainjabatancpnsAction() {
		$id 		= $_POST['id'];
		$y_tahun	= $_POST['y_tahun'];
		$c_jabatan	= $_POST['c_jabatan'];
		$n_jabatan 	= $_POST['n_jabatan'];
		$c_kode 	= $_POST['c_kode'];
		$proses		= $_POST['proses'];
		if($proses == 'tambah'){
			$proses	= "insert";
		} else if ($proses == 'ubah') {
			$proses	= "update";
		} else {
			$proses	= "delete";
			$id = $_REQUEST['id'];
		}
		$iEntry		= $this->userid;
		
		$dataMasukan = array("id"		=> $id,
							 "y_tahun"	=> $y_tahun,
							 "c_jabatan"=> $c_jabatan,
							 "n_jabatan"=> $n_jabatan,
							 "c_kode"	=> $c_kode,
							 "i_entry"	=> $iEntry);
							 
		$hasil = $this->refcpns_serv->maintainData($dataMasukan, $proses);
		if(!$proses){
			$this->view->proses = "1";	
		} else if($proses == 'ubah') {
			$this->view->proses = "2";	
		} else {
			$this->view->proses = "3";	
		}
		$this->view->keterangan = "Kode Jabatan";	
		$this->view->hasil = $hasil;
		
		$this->jabatancpnsAction();
		$this->render('jabatancpns');			
    }
}
?>