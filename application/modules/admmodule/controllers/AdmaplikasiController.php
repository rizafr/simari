<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admaplikasi_Service.php";


class Admmodule_AdmaplikasiController extends Zend_Controller_Action {

		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath');
		$this->view->baseData = $registry->get('baseData');		
		$this->view->leftMenu = $registry->get('leftMenu');
		
		$this->dataPerPage = $registry->get('dataPerPage');
		
		//$this->view->photoPath = $registry->get('photoPath');
		 
		$this->admaplikasi_serv = Adm_Admaplikasi_Service::getInstance();
		
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
	
	public function admaplikasijsAction() 
	{
		header('content-type : text/javascript');
		$this->render('admaplikasijs');
	}	
	
	public function daftaraplikasiAction() {    
		$kategoriCari 	= $_POST['kategoriCari'];
		if(!$kategoriCari){ $kategoriCari = 'semua'; }
		$kataKunciCari	= $_POST['kataKunciCari'];
		
		if($kategoriCari == 'semua'){ 
			$sortBy	= 'i_urut_aplikasi'; 
		}
		else { 
			$sortBy		=$kategoriCari;
		}
		$sortOrder	= 'asc'; 
		
		$pageNumber = $_REQUEST['currentPage'];
		if(!$pageNumber) {$pageNumber = 1;}
		
		$numToDisplay = $_REQUEST['numToDisplay'];
		if(!$numToDisplay) {
			$numToDisplay 	= $this->dataPerPage; // di definisikan di konstanta.php
		}
		
		$dataMasukanTotal = array("pageNumber" 	=> 0,
								"itemPerPage" 	=> 0,
								"kategoriCari" 	=> $kategoriCari,
								"kataKunciCari" => $kataKunciCari,
								"sortBy" 		=> $sortBy,
								"sortOrder" 	=> $sortOrder);

		$this->view->totData = $this->admaplikasi_serv->aplikasiList($dataMasukanTotal);
		
		$dataMasukan = array("pageNumber" 	=> $pageNumber,
								"itemPerPage" 	=> $numToDisplay,
								"kategoriCari" 	=> $kategoriCari,
								"kataKunciCari" => $kataKunciCari,
								"sortBy" 		=> $sortBy,
								"sortOrder" 	=> $sortOrder);
		$this->view->aplikasiList = $this->admaplikasi_serv->aplikasiList($dataMasukan);
		
		$this->view->kategoriCari	= $kategoriCari;
		$this->view->kataKunci		= $kataKunci;
		$this->view->numToDisplay 	= $numToDisplay; 
		$this->view->pageNumber 	= $pageNumber;
	}

	public function admaplikasiolahdataAction() {
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		
		$this->view->noUrutAplikasi = $this->admaplikasi_serv->getMaxNourutAplikasi();
		if($this->view->jenisForm == 'ubah'){
			$iAplikasi = $_REQUEST['iAplikasi'];
			
			$dataMasukan = array("i_aplikasi" => $iAplikasi);
								 
			$this->view->detailAplikasi = $this->admaplikasi_serv->aplikasiDetail($dataMasukan);
		}
    }
	
	public function admaplikasitambahAction() {
		$cAplikasi = $_POST['cAplikasi'];
		$nAplikasi = $_POST['nAplikasi'];
		$eAplikasi = $_POST['eAplikasi'];
		$iUrutAplikasi = $_POST['iUrutAplikasi'];
		
		$dataMasukan = array("c_aplikasi" => $cAplikasi,
							 "n_aplikasi" => $nAplikasi,
							 "e_aplikasi" => $eAplikasi,
							 "i_urut_aplikasi" => $iUrutAplikasi);
							 
		$prosesInsert = $this->admaplikasi_serv->admaplikasiTambah($dataMasukan);
		
		$this->view->proses = "1";	
		$this->view->keterangan = "Aplikasi";
		$this->view->hasil = $prosesInsert;
		
		$this->daftaraplikasiAction();
		$this->render('daftaraplikasi');	
    }
	
	public function admaplikasiubahAction() {
		$iAplikasi = $_POST['iAplikasi'];
		$cAplikasi = $_POST['cAplikasi'];
		$nAplikasi = $_POST['nAplikasi'];
		$eAplikasi = $_POST['eAplikasi'];
		$iUrutAplikasi = $_POST['iUrutAplikasi'];
		
		$dataMasukan = array("i_aplikasi" => $iAplikasi,
							 "c_aplikasi" => $cAplikasi,
							 "n_aplikasi" => $nAplikasi,
							 "e_aplikasi" => $eAplikasi,
							 "i_urut_aplikasi" => $iUrutAplikasi);
							 
		$prosesUbah = $this->admaplikasi_serv->admaplikasiUbah($dataMasukan);
		
		$this->view->proses = "2";	
		$this->view->keterangan = "Aplikasi";
		$this->view->hasil = $prosesUbah;
		
		$this->daftaraplikasiAction();
		$this->render('daftaraplikasi');	
    }
	
	public function admaplikasihapusAction() {
		$iAplikasi = $_REQUEST['iAplikasi'];
		
		$dataMasukan = array("i_aplikasi" => $iAplikasi);
							 
		$prosesHapus = $this->admaplikasi_serv->admaplikasiHapus($dataMasukan);
		
		$this->view->proses = "3";	
		$this->view->keterangan = "Aplikasi";
		$this->view->hasil = $prosesHapus;
		
		$this->daftaraplikasiAction();
		$this->render('daftaraplikasi');	
    }
}
?>