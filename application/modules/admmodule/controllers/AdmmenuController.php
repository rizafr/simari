<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admmenu_Service.php";
require_once "service/adm/Adm_Admaplikasi_Service.php";


class Admmodule_AdmmenuController extends Zend_Controller_Action {

		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath');
		$this->view->baseData = $registry->get('baseData');		
		$this->view->leftMenu = $registry->get('leftMenu');
		
		$this->dataPerPage = $registry->get('dataPerPage');
		
		//$this->view->photoPath = $registry->get('photoPath');
		 
		$this->admmenu_serv = Adm_Admmenu_Service::getInstance();
		$this->admaplikasi_serv = Adm_Admaplikasi_Service::getInstance();
		
		$this->view->nama= $this->nama;
		$this->view->nip= $this->nip;
		$this->view->golongan= $this->golongan;
		$this->view->pangkat=$this->pangkat;
		
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
			$this->c_izin			= $ssologin->c_izin;
			$this->thn_anggaran		= $ssologin->thn_anggaran;
		}

    }
	
    public function indexAction() {
    }
	
	public function admmenujsAction() 
	{
		header('content-type : text/javascript');
		$this->render('admmenujs');
	}	
	
	public function daftarmenuAction($iUrutAplikasi=1) {    
		//$iAplikasi = $iAplikasi;
		
		//echo "iAplikasi = $iAplikasi";
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

		//$this->view->totData = $this->admaplikasi_serv->aplikasiList($dataMasukanTotal);
		
		$dataMasukan = array("pageNumber" 	=> $pageNumber,
								"itemPerPage" 	=> $numToDisplay,
								"kategoriCari" 	=> $kategoriCari,
								"kataKunciCari" => $kataKunciCari,
								"sortBy" 		=> $sortBy,
								"sortOrder" 	=> $sortOrder);
		$this->view->aplikasiList = $this->admaplikasi_serv->aplikasiList($dataMasukan);
		$this->view->noUrutAplikasi =  $iUrutAplikasi; //$this->admaplikasi_serv->noUrutAplikasi($iAplikasi);
		$this->view->maxIaplikasi = $this->admaplikasi_serv->getMaxIaplikasi();
		
		$this->view->kategoriCari	= $kategoriCari;
		$this->view->kataKunci		= $kataKunci;
		$this->view->numToDisplay 	= $numToDisplay; 
		$this->view->pageNumber 	= $pageNumber;
	}

	public function admmenuolahdataAction() {
		$this->view->jenisForm 		= $_REQUEST['jenisForm'];
		$this->view->iAplikasi 		= $_REQUEST['iAplikasi'];
		$this->view->cMenuLevelInduk= $_REQUEST['cMenuLevelInduk'];
		
		//echo "level induk = ".$this->view->cMenuLevelInduk;
		/* set levelinduk */
		/*-------------------*/
		if ($this->view->jenisForm == 'tambah') {
			if (strlen($this->view->cMenuLevelInduk)==0){
				$levelInduk = 0;
			} 
			else if(strlen($this->view->cMenuLevelInduk)==2){ 
				$levelInduk = 1;
				$cMenuLevel1 = $this->view->cMenuLevelInduk;
			}
			else if(strlen($this->view->cMenuLevelInduk)==4){ 
				$levelInduk = 2;
				$cMenuLevel1 = substr($this->view->cMenuLevelInduk,0,2);
				$cMenuLevel2 = substr($this->view->cMenuLevelInduk,2,2);
			}
			else if(strlen($this->view->cMenuLevelInduk)==6){ 
				$levelInduk = 3;
				$cMenuLevel1 = substr($this->view->cMenuLevelInduk,0,2);
				$cMenuLevel2 = substr($this->view->cMenuLevelInduk,2,2);
				$cMenuLevel3 = substr($this->view->cMenuLevelInduk,4,2);
			}
			
			$this->view->levelInduk = $levelInduk;
			
			//echo "jenisForm = ".$this->view->jenisForm;		
			//if(!$this->view->jenisForm) {
			/* if($levelInduk = 0){
				// ambil max level 1 
				//----------------------
				$masukan1 = array("i_aplikasi" => $this->view->iAplikasi);
				$levelMenuBaru = $this->admmenu_serv->getMaxLevel($masukan1); 
				
				$this->view->levelMenuBaru = $levelMenuBaru;
			} */
				
			//}
			
			/* menampilkan List Aplikasi*/
			/*---------------------------------*/
			$masukanNAplikasi = array("i_aplikasi" => $this->view->iAplikasi);
			$this->view->detailAplikasi = $this->admaplikasi_serv->aplikasiDetail($masukanNAplikasi); 
			
			/* akhir menampilkan list aplikasi */
			/*----------------------------------------*/
			//echo "$cMenuLevel1,$cMenuLevel2,$cMenuLevel3,";
			
			$dataMasukanMenuLevel1 = array("i_aplikasi" => $this->view->iAplikasi,
										  "c_menu_level1" => $cMenuLevel1,
										  "level" => 1);
			$this->view->nMenulevel1 = $this->admmenu_serv->getMenuLevel($dataMasukanMenuLevel1);

			$dataMasukanMenuLevel2 = array("i_aplikasi" => $this->view->iAplikasi,
										  "c_menu_level1" => $cMenuLevel1,
										  "c_menu_level2" => $cMenuLevel2,
										  "level" => 2);
			$this->view->nMenulevel2 = $this->admmenu_serv->getMenuLevel($dataMasukanMenuLevel2);
//echo "xxxxxxxxxxxxxx = ".$this->view->nMenulevel2;
			
			$dataMasukanMenuLevel3 = array("i_aplikasi" => $this->view->iAplikasi,
										  "c_menu_level1" => $cMenuLevel1,
										  "c_menu_level2" => $cMenuLevel2,
										  "c_menu_level3" => $cMenuLevel3,
										  "level" => 3);
										  
			$this->view->nMenulevel3 = $this->admmenu_serv->getMenuLevel($dataMasukanMenuLevel3);
			
			/* Set level menu  = max +1 */
			/*--------------------------------*/
			$dataMasukanLevelMenu = array("i_aplikasi" => $this->view->iAplikasi,
										  "c_menu_levelinduk" => $this->view->cMenuLevelInduk);
			
			//var_dump($dataMasukanLevelMenu);
			$levelMenuBaru = $this->admmenu_serv->getMaxLevel($dataMasukanLevelMenu);
			$this->view->levelMenuBaru = $cMenuLevel1.$cMenuLevel2.$cMenuLevel3.substr($levelMenuBaru, strlen($levelMenuBaru)-2,2);
			//echo "xxxxxxxxxx".$this->view->levelMenuBaru;
		
		} else if ($this->view->jenisForm == 'ubah'){
			if(strlen($this->view->cMenuLevelInduk)==2){ 
				$levelInduk = 0;
				$cMenuLevel1 = $this->view->cMenuLevelInduk;
			}
			else if(strlen($this->view->cMenuLevelInduk)==4){ 
				$levelInduk = 1;
				$cMenuLevel1 = substr($this->view->cMenuLevelInduk,0,2);
				$cMenuLevel2 = substr($this->view->cMenuLevelInduk,0,4);
			}
			else if(strlen($this->view->cMenuLevelInduk)==6){ 
				$levelInduk = 2;
				$cMenuLevel1 = substr($this->view->cMenuLevelInduk,0,2);
				$cMenuLevel2 = substr($this->view->cMenuLevelInduk,0,4);
				$cMenuLevel3 = substr($this->view->cMenuLevelInduk,0,6);
			}else if(strlen($this->view->cMenuLevelInduk)==8){ 
				$levelInduk = 3;
				$cMenuLevel1 = substr($this->view->cMenuLevelInduk,0,2);
				$cMenuLevel2 = substr($this->view->cMenuLevelInduk,0,4);
				$cMenuLevel3 = substr($this->view->cMenuLevelInduk,0,6);
				$cMenuLevel4 = substr($this->view->cMenuLevelInduk,0,8);
			}
			
			$this->view->levelInduk = $levelInduk;
			$dataMasukan = array("i_aplikasi" => $this->view->iAplikasi,
								 "c_menu_level1" => $cMenuLevel1,
								 "c_menu_level2" => $cMenuLevel2,
								 "c_menu_level3" => $cMenuLevel3,
								 "c_menu_level4" => $cMenuLevel4,
								 "levelInduk" 	 => $levelInduk
								 );
								 
			$this->view->detailAplikasi = $this->admmenu_serv->aplikasiDetail($dataMasukan);
			//var_dump($dataMasukan);
			//echo "<br>";
			//var_dump($this->view->detailAplikasi);
		}
		/* if($this->view->jenisForm == 'ubah'){
			$iAplikasi = $_REQUEST['iAplikasi'];
			
			$dataMasukan = array("i_aplikasi" => $iAplikasi);
								 
			$this->view->detailAplikasi = $this->admmenu_serv->aplikasiDetail($dataMasukan);
		} */
    }
	
	public function admmenutambahAction() {
		$iAplikasi 	 = $_POST['iAplikasi'];
		$cMenulevel1 = $_POST['cMenuLevel1'];
		$nMenulevel1 = $_POST['nMenuLevel1'];
		$cMenulevel2 = $_POST['cMenuLevel2'];
		$nMenulevel2 = $_POST['nMenuLevel2'];
		$cMenulevel3 = $_POST['cMenuLevel3'];
		$nMenulevel3 = $_POST['nMenuLevel3'];
		$cMenulevel4 = $_POST['cMenuLevel4'];
		$nMenulevel4 = $_POST['nMenuLevel4'];
		$eMenu		 = $_POST['eMenu'];
		$nAction	 = $_POST['n_action'];
		
		$cMenuLevel = $cMenulevel1.$cMenulevel2.$cMenulevel3.$cMenulevel4;
		//echo "<br>cMenuLevel = $cMenuLevel<br>";
		if(strlen($cMenuLevel) == 2){
			$nMenu = $nMenulevel1;
		} else if(strlen($cMenuLevel) == 4){
			$nMenu = $nMenulevel2;
		} else if(strlen($cMenuLevel) == 6){
			$nMenu = $nMenulevel3;
		} else if(strlen($cMenuLevel) == 8){
			$nMenu = $nMenulevel4;
		} 
				
				//echo "xxxx=".$cMenulevel1.$cMenulevel2.$cMenulevel3.$cMenulevel4."<br>length = ".strlen($cMenuLevel);
		
			
		if(!$nAction){
			$nAction = 'rencanamodule/development/dev,rencanamodule/development/devjs';
		} 
		
		 if($nAction){
			$cMenuStatuscb = 'Y';
		} else {
			$cMenuStatuscb = 'N';
		} 
		
		$dataMasukan = array("i_aplikasi" 	=> $iAplikasi,
							 "c_menu_level" => $cMenuLevel,
							 "n_menu" 		=> $nMenu,
							 "e_menu" 		=> $eMenu,
							 "c_menu_statuscb" => $cMenuStatuscb,
							 "n_action" 	=> $nAction);
		
//var_dump($dataMasukan);		
		$prosesInsert = $this->admmenu_serv->menuTambah($dataMasukan);
		
		$this->view->proses = "1";	
		$this->view->keterangan = "Menu";
		$this->view->hasil = $prosesInsert;
		
		$i_urut_aplikasi = $this->admaplikasi_serv->noUrutAplikasi($iAplikasi);
		$this->daftarmenuAction($i_urut_aplikasi);
		$this->render('daftarmenu');	
    }
	
	public function admmenuubahAction() {
		$iAplikasi 	 = $_POST['iAplikasi'];
		$cMenulevel1 = $_POST['cMenuLevel1'];
		$nMenulevel1 = $_POST['nMenuLevel1'];
		$cMenulevel2 = $_POST['cMenuLevel2'];
		$nMenulevel2 = $_POST['nMenuLevel2'];
		$cMenulevel3 = $_POST['cMenuLevel3'];
		$nMenulevel3 = $_POST['nMenuLevel3'];
		$cMenulevel4 = $_POST['cMenuLevel4'];
		$nMenulevel4 = $_POST['nMenuLevel4'];
		$eMenu		 = $_POST['eMenu'];
		$nAction	 = $_POST['n_action'];
		
		if(!$cMenulevel4){
			if(!$cMenulevel3){
				if(!$cMenulevel2){
					$cMenuLevel = $cMenulevel1;
					$nMenu = $nMenulevel1;
				} else {
					$cMenuLevel = $cMenulevel2;
					$nMenu = $nMenulevel2;
				}
			} else {
				$cMenuLevel = $cMenulevel3;
				$nMenu = $nMenulevel3;
			}
		} else {
			$cMenuLevel = $cMenulevel4;
			$nMenu = $nMenulevel4;
		}
		
		//echo "<br>cMenuLevel = $cMenuLevel<br>";
		/*$cMenuLevel = $cMenulevel1.$cMenulevel2.$cMenulevel3.$cMenulevel4;
		echo "<br>cMenuLevel = $cMenuLevel<br>";
		if(strlen($cMenuLevel) == 2){
			$nMenu = $nMenulevel1;
		} else if(strlen($cMenuLevel) == 4){
			$nMenu = $nMenulevel2;
		} else if(strlen($cMenuLevel) == 6){
			$nMenu = $nMenulevel3;
		} else if(strlen($cMenuLevel) == 8){
			$nMenu = $nMenulevel4;
		} 
			*/	
				//echo "xxxx=".$cMenulevel1.$cMenulevel2.$cMenulevel3.$cMenulevel4."<br>length = ".strlen($cMenuLevel);
		if(!$nAction){
			$nAction = 'rencanamodule/development/dev,rencanamodule/development/devjs';
		} 		
				
		if($nAction){
			$cMenuStatuscb = 'Y';
		} else {
			$cMenuStatuscb = 'N';
		}
				
		$dataMasukan = array("i_aplikasi" 	=> $iAplikasi,
							 "c_menu_level" => $cMenuLevel,
							 "n_menu" 		=> $nMenu,
							 "e_menu" 		=> $eMenu,
							 "c_menu_statuscb" => $cMenuStatuscb,
							 "n_action" 	=> $nAction);
		
//var_dump($dataMasukan);		
		$prosesUbah = $this->admmenu_serv->menuUbah($dataMasukan);
		
		$this->view->proses = "2";	
		$this->view->keterangan = "Aplikasi";
		$this->view->hasil = $prosesUbah;
		
		$i_urut_aplikasi = $this->admaplikasi_serv->noUrutAplikasi($iAplikasi);
		
		$this->daftarmenuAction($i_urut_aplikasi);
		$this->render('daftarmenu');	
    }
	
	public function admmenuhapusAction() {
		$iAplikasi = $_REQUEST['iAplikasi'];
		$cMenuLevel = $_REQUEST['cMenuLevel'];
		
		$dataMasukan = array("i_aplikasi" => $iAplikasi,
							 "c_menu_level" => $cMenuLevel);
							 
		$prosesHapus = $this->admmenu_serv->menuHapus($dataMasukan);
		
		$this->view->proses = "3";	
		$this->view->keterangan = "Aplikasi";
		$this->view->hasil = $prosesHapus;
		
		$i_urut_aplikasi = $this->admaplikasi_serv->noUrutAplikasi($iAplikasi);
		
		$this->daftarmenuAction($i_urut_aplikasi);
		$this->render('daftarmenu');	
    }

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function admaplikasiubahAction() {
		$iAplikasi = $_POST['iAplikasi'];
		$cAplikasi = $_POST['cAplikasi'];
		$nAplikasi = $_POST['nAplikasi'];
		$eAplikasi = $_POST['eAplikasi'];
		
		$dataMasukan = array("i_aplikasi" => $iAplikasi,
							 "c_aplikasi" => $cAplikasi,
							 "n_aplikasi" => $nAplikasi,
							 "e_aplikasi" => $eAplikasi);
							 
		$prosesUbah = $this->admaplikasi_serv->admaplikasiUbah($dataMasukan);
		
		$this->view->proses = "2";	
		$this->view->keterangan = "Aplikasi";
		$this->view->hasil = $prosesUbah;
		
		$this->daftaraplikasiAction();
		$this->render('daftaraplikasi');	
    }
	
	
    /*public function daftaruserAction() {
		$this->view->daftarUnit = $this->renjaprogram_serv->getListUnit();
		$this->view->cUnitInput = $_REQUEST['cUnit'];
		if(!$this->view->cUnitInput){
			$this->view->cUnitInput = '-';
			$this->view->nUnitInput = 'Semua';
		}
		
		$Masukan = array("cUnit" => $this->view->cUnitInput);
		$this->view->daftarKegiatanPerUnit = $this->renjakegiatan_serv->getKegiatanPerUnit($Masukan);
    }*/
}
?>