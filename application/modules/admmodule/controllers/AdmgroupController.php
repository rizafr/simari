<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admmenu_Service.php";
require_once "service/adm/Adm_Admaplikasi_Service.php";
require_once "service/adm/Adm_Admgroup_Service.php";
require_once "service/sdm/sdm_refferensi_Service.php";

class Admmodule_AdmgroupController extends Zend_Controller_Action {

		
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
		$this->admgroup_serv = Adm_Admgroup_Service::getInstance();
		$this->reff_serv = sdm_refferensi_Service::getInstance();
		
		
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
	
	public function admgroupjsAction() 
	{
		header('content-type : text/javascript');
		$this->render('admgroupjs');
	}	
	
	public function daftargroupAction() {    
		$kategoriCari 	= $_POST['kategoriCari'];
		if(!$kategoriCari){ $kategoriCari = 'semua'; }
		$kataKunciCari	= $_POST['kataKunciCari'];
		
		if(($kategoriCari == 'semua') || (!$kategoriCari)){ 
			$sortBy	= 'i_group'; 
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
								"sortOrder" 	=> $sortOrder,
								"userid"		=> $this->userid);

		//$this->view->totData = $this->admaplikasi_serv->aplikasiList($dataMasukanTotal);
		
		$dataMasukan = array("pageNumber" 	=> $pageNumber,
								"itemPerPage" 	=> $numToDisplay,
								"kategoriCari" 	=> $kategoriCari,
								"kataKunciCari" => $kataKunciCari,
								"sortBy" 		=> $sortBy,
								"sortOrder" 	=> $sortOrder,
								"userid"		=> $this->userid);
								
								//var_dump($dataMasukan);
		$this->view->totalGroupList = $this->admgroup_serv->getGrouplist($dataMasukanTotal);
		$this->view->GroupList = $this->admgroup_serv->getGrouplist($dataMasukan);
		
		$this->view->kategoriCari	= $kategoriCari;
		$this->view->kataKunci		= $kataKunci;
		$this->view->numToDisplay 	= $numToDisplay; 
		$this->view->pageNumber 	= $pageNumber;
	}

	public function admgroupolahdataAction() {
		$this->view->jenisForm 	= $_REQUEST['jenisForm'];
		$this->view->iGroup		= $_REQUEST['iGroup'];
		$dataMasukanGroup = array("i_group" => $this->view->iGroup);
		//$this->view->nGroup = $this->admgroup_serv->getNamaGroup($dataMasukanGroup);
		$dataMasukan2 = array("i_group" 	=> $this->view->iGroup);
		$this->view->detailGroup = $this->admgroup_serv->detailgroup($dataMasukan2);
		//var_dump($this->view->detailGroup);
		$dataMasukan = array("pageNumber" 	=> 99,
								"itemPerPage" 	=> 99,
								"kategoriCari" 	=> "n_aplikasi",
								"kataKunciCari" => '',
								"sortBy" 		=> 'i_urut_aplikasi',
								"sortOrder" 	=> 'asc');
		$this->view->aplikasiList = $this->admaplikasi_serv->aplikasiList($dataMasukan);
		$this->view->menuListAll = $this->admmenu_serv->readAllMenuMap();
		
		$this->view->eselonList = $this->reff_serv->getEselon('');		
	
		$this->view->lokasiList = $this->reff_serv->getLokasi('');	
		
		$c_lokasi_unitkerja=trim($this->view->detailGroup['c_lokasi_unitkerja']);
		$this->view->c_lokasi_unitkerja = $c_lokasi_unitkerja;
			
		if($c_lokasi_unitkerja == '1'){
			$this->view->c_eselon_i =trim($this->view->detailGroup['c_eselon_i']);
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			$c_eselon_i=$this->view->detailGroup['c_eselon_i'];
			
			
			$c_eselon_i=substr($c_eselon_i,0,2);
			$this->view->c_eselon_ii =trim($this->view->detailGroup['eselonii']);
			$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and trim(c_tkt_esl)='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
			
			
			$c_eselon_ii=$this->view->detailGroup['c_eselon_ii'];
			$c_eselon_ii=substr($c_eselon_ii,0,3);
			$this->view->c_eselon_iii =trim($this->view->detailGroup['c_eselon_ii']);
			$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
				
			$c_eselon_iii=$this->view->detailGroup['c_eselon_iii'];
			$c_eselon_iii=substr($c_eselon_iii,0,2);
			$this->view->c_eselon_iv =trim($view->detailGroup['c_eselon_iv']);
			$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			
			$c_eselon_iv=$this->view->detailGroup['c_eselon_iv'];
			$c_eselon_iv=substr($c_eselon_iv,0,2);	
			$this->view->eselonvList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii'  and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
		} else {
			$this->view->eselonList = $this->reff_serv->getEselon('');		
			$this->view->lokasiList = $this->reff_serv->getLokasi('');	
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1'");
			$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='2'");
			$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='3'");
			$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='4'");
			$this->view->eselonvList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='5'");					
			
			//var_dump($this->view->detailGroup);
			/* $this->view->c_lokasi_unitkerja=trim($this->view->detailGroup['c_lokasi_unitkerja']);
			$this->view->c_eselon_i=$this->view->detailGroup['c_eselon_i'].";".$this->view->detailGroup['n_eselon_i'];
			$this->view->c_eselon_ii=$this->view->detailGroup['c_eselon_ii'].";".$this->view->detailGroup['n_eselon_ii'];
			$this->view->c_eselon_iii=$this->view->detailGroup['c_eselon_iii'].";".$this->view->detailGroup['n_eselon_iii'];
			$this->view->c_eselon_iv=$this->view->detailGroup['c_eselon_iv'].";".$this->view->detailGroup['n_eselon_iv'];
			$this->view->c_eselon_v=$this->view->detailGroup['c_eselon_v'].";".$this->view->detailGroup['n_eselon_v'];

			$neselon1=trim($this->view->detailGroup['n_eselon_i']);
			$neselon2=trim($this->view->detailGroup['n_eselon_ii']);
			$neselon3=trim($this->view->detailGroup['n_eselon_iii']);
			$neselon4=trim($this->view->detailGroup['n_eselon_iv']);
			$neselon5=trim($this->view->detailGroup['n_eselon_v']); 
			
			$this->view->n_eselon_i=trim($this->view->detailGroup['n_eselon_i']);
			$this->view->n_eselon_ii=trim($this->view->detailGroup['n_eselon_ii']);
			$this->view->n_eselon_iii=trim($this->view->detailGroup['n_eselon_iii']);
			$this->view->n_eselon_iv=trim($this->view->detailGroup['n_eselon_iv']);
			$this->view->n_eselon_v=trim($this->view->detailGroup['n_eselon_v']);
			
			$neselon1=trim($this->view->detailGroup['n_eselon_i']);
			$neselon2=trim($this->view->detailGroup['n_eselon_ii']);
			$neselon3=trim($this->view->detailGroup['n_eselon_iii']);
			$neselon4=trim($this->view->detailGroup['n_eselon_iv']);
			$neselon5=trim($this->view->detailGroup['n_eselon_v']);

			$c_lokasi_unitkerja=trim($this->view->detailGroup['c_lokasi_unitkerja']);
			$this->view->c_lokasi_unitkerja=trim($this->view->detailGroup['c_lokasi_unitkerja']);
			
			*/
			
			$c_eselon_i=trim($this->view->detailGroup['c_eselon_i']);
			$c_eselon_ii=trim($this->view->detailGroup['c_eselon_ii']);
			$c_eselon_iii=trim($this->view->detailGroup['c_eselon_iii']);
			$c_eselon_iv=trim($this->view->detailGroup['c_eselon_iv']);
			$c_eselon_v=trim($this->view->detailGroup['c_eselon_v']);
			$c_satker=trim($this->view->detailGroup['c_satker']);
			$c_parent=trim($this->view->detailGroup['c_parent']);
			$ceselon2=trim($this->view->detailGroup['ceselon2']);
			$n_group=trim($this->view->detailGroup['n_group']);
			
			$n_eselon_i=trim($this->view->detailGroup['n_eselon_i']);
			$n_eselon_ii=trim($this->view->detailGroup['n_eselon_ii']);
			$n_eselon_iii=trim($this->view->detailGroup['n_eselon_iii']);
			$n_eselon_iv=trim($this->view->detailGroup['n_eselon_iv']);
			$n_eselon_v=trim($this->view->detailGroup['n_eselon_v']);
			
			$this->view->c_eselon_i = $c_eselon_i;
			$this->view->c_eselon_ii = $c_eselon_i;
			$this->view->c_eselon_iii = $c_eselon_iii;
			$this->view->c_eselon_iv = $c_eselon_iv;
			$this->view->c_eselon_v = $c_eselon_v;
			$this->view->c_satker = $c_satker;
			$this->view->c_parent = $c_parent;
			$this->view->ceselon2 = $ceselon2;
			$this->view->n_group = $n_group;
			
			//echo "<br>c_eselon_i = $c_eselon_i | c_eselon_ii = $c_eselon_ii<br>";
/* 		$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='3'");
			$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_iii = '00'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child = '00' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");		
			$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child <> '00' and c_parent ='$c_parent'  and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
			$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2'  and c_lokasi_unitkerja='$c_lokasi_unitkerja'"); */
			
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and c_lokasi_unitkerja='3'");
			$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja("and c_level ='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_eselon_i='$c_eselon_i'");
			$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and  c_level ='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_eselon_i='$c_eselon_i'  and c_parent ='$c_parent'");	
			$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_level ='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_parent ='$c_parent'");	

			$dataceselonii = $this->reff_serv->getTrUnitKerja(" and c_level ='2' and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_parent ='$c_parent'");		
			$c_eselon_iix=$dataceselonii[0]->c_eselon_ii;
			
//echo "<br>xxxx ".trim($c_eselon_iix).";".trim($c_parent).";".trim($n_eselon_ii);
		
			//$this->view->eselonvList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii <> '00' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	

			
			
			$this->view->ceseloncpns=substr($this->view->detailGroup['c_eselon'],1,1);


			$this->view->c_eselon_i=trim($this->view->detailGroup['c_eselon_i']).";".trim($this->view->detailGroup['n_eselon_i']);
			//$this->view->c_eselon_ii=trim($datajabatan[0]['c_eselon_ii']).";".trim($datajabatan[0]['c_parent']).";".trim($datajabatan[0]['n_eselon_ii']);
			$this->view->c_eselon_ii=trim($c_eselon_iix).";".trim($c_parent).";".trim($n_eselon_ii);
			$this->view->c_eselon_iii=trim($ceselon2).";".trim($c_eselon_iii).";".trim($c_satker).";".trim($n_eselon_iii);
			//$this->view->c_eselon_iv=trim($c_eselon_iv).";".trim($n_eselon_iv);
			$this->view->c_eselon_iv=trim($c_eselon_iv).";".trim($c_eselon_v).";".trim($n_eselon_v);
			
			$this->view->n_eselon_i=trim($this->view->detailGroup['n_eselon_i']);
			$this->view->n_eselon_ii=trim($this->view->detailGroup['n_eselon_ii']);
			$this->view->n_eselon_iii=trim($this->view->detailGroup['n_eselon_iii']);
			$this->view->n_eselon_iv=trim($this->view->detailGroup['n_eselon_iv']);	
		}
    }
	
	
	public function admgrouptambahAction() {
		$userid		 = $this->userid;
		$nGroupOwner = $userid;
		$nGroup		 = $_POST['n_group'];
		$c_eselon 	 = $_POST['c_eselon'];
		$n_jabatan 	 = $_POST['n_jabatan'];
		$c_jabatan 	 = $_POST['c_jabatan'];
		$c_lokasi_unitkerja 	 = $_POST['c_lokasi_unitkerja'];
		$c_wewenang  = $_POST['c_wewenang'];
		$c_sektoral  = $_POST['c_sektoral'];
			
		if($c_lokasi_unitkerja == '1'){
			$c_eselon_i_lengkap  = $_POST['c_eselon_i'];
			$c_eselon_iArr = explode(";", $c_eselon_i_lengkap);
			$c_eselon_i	 = $c_eselon_iArr[0];
			$c_eselon_ii_lengkap  = $_POST['c_eselon_ii'];
			$c_eselon_iiArr = explode(";", $c_eselon_ii_lengkap);
			$c_eselon_ii	 = $c_eselon_iiArr[0];
			if (!$c_eselon_ii){
				$c_eselon_ii = '000';
			}
			$c_eselon_iii_lengkap  = $_POST['c_eselon_iii'];
			$c_eselon_iiiArr = explode(";", $c_eselon_iii_lengkap);
			$c_eselon_iii	 = $c_eselon_iiiArr[0];
			if (!$c_eselon_iii){
				$c_eselon_iii = '00';
			}
			$c_eselon_iv_lengkap  = $_POST['c_eselon_iv'];
			$c_eselon_ivArr = explode(";", $c_eselon_iv_lengkap);
			$c_eselon_iv	 = $c_eselon_ivArr[0];
			if (!$c_eselon_iv){
				$c_eselon_iv = '00';
			}
			$c_eselon_v_lengkap   = $_POST['c_eselon_v'];
			$c_eselon_vArr = explode(";", $c_eselon_v_lengkap);
			$c_eselon_v	 = $c_eselon_vArr[0];
			if (!$c_eselon_v){
				$c_eselon_v = '00';
			}
			
			$unitKerja= $this->reff_serv->getUnitKerja(" and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and  c_eselon_iv='$c_eselon_iv' ");
			$c_satker= $unitKerja[0]['c_satker'];
			$c_parent= $unitKerja[0]['c_parent'];
			
		} else if ($c_lokasi_unitkerja == '3'){
			$c_eselon_i=$_POST['c_eselon_i'];
			if ($_POST['c_eselon_i']!=''){
				$c_eselon_il=explode(";",$c_eselon_i);
				$c_eselon_i=$c_eselon_il[0];
			} else {$c_eselon_i='00';}
			
			$c_eselon_ii=$_POST['c_eselon_ii'];
			if ($_POST['c_eselon_ii']!=''){			
				$valesl = explode(";",$c_eselon_ii);
				$c_eselon_ii=$valesl[0];
				$c_parent=$valesl[1];
			} else {
				$c_eselon_ii='000';
				$c_parent='00';
			}

			$c_eselon_iii=$_POST['c_eselon_iii'];
			if ($_POST['c_eselon_iii']!=''){			
				$valesliii = explode(";",$c_eselon_iii);
				$c_eselon_ii=$valesliii[0];
				$c_eselon_iii=$valesliii[1];
				$c_satker=$valesliii[2];	
			} else {
				$c_eselon_iii='00';
				$c_satker='00';
			}

			$c_eselon_iv=$_POST['c_eselon_iv'];
			
			if ($_POST['c_eselon_iv']!=''){
				$valesliv = explode(";",$c_eselon_iv);
				$c_eselon_iv=$valesliv[0];
				$c_eselon_v =$valesliv[1];
				//$c_eselon_v ='00';
			} else {
				$c_eselon_iv='00';
				$c_eselon_v ='00';
			}
			
		}
		
		$dataMasukan = array("pageNumber" 	=> 99,
								"itemPerPage" 	=> 99,
								"kategoriCari" 	=> "n_aplikasi",
								"kataKunciCari" => '',
								"sortBy" 		=> 'i_aplikasi',
								"sortOrder" 	=> 'asc');
		$aplikasiList = $this->admaplikasi_serv->aplikasiList($dataMasukan);
		 for($x=0;$x<count($aplikasiList); $x++){
			$i_aplikasi = $aplikasiList[$x]->i_aplikasi;
			
			$data1 = array("i_aplikasi" => $i_aplikasi);
			$daftarMenuPerAplikasi = $this->admmenu_serv->readAllMenuMapPerAplikasi($data1);
			for($y=0; $y<count($daftarMenuPerAplikasi); $y++){
				$c_menu_level = $daftarMenuPerAplikasi[$y]['c_menu_level'];
				//echo "<br>".$_POST[$i_aplikasi.'_'.$c_menu_level];
				if($_POST[$i_aplikasi.'_'.$c_menu_level]) {					
					$checkBox[] = $i_aplikasi.'_'.$c_menu_level;
				}
			}
			unset($data1);
		}
		if(trim($c_eselon_v) == '') $c_eselon_v='00';
		if(trim($c_eselon_iv) == '') $c_eselon_iv='00';
		if(trim($c_eselon_iii) == '') $c_eselon_iii='00';
		if(trim($c_eselon_ii) == '') $c_eselon_ii='000';
		$dataMasukan = array("n_group" 			=> $nGroup,
							 "n_group_owner"	=> $nGroupOwner,
							 "menuArr"			=> $checkBox,
							 "c_eselon"			=> $c_eselon,
							 "c_jabatan"		=> $c_jabatan,
							 "c_lokasi_unitkerja" => $c_lokasi_unitkerja,
							 "c_eselon_i"		=> $c_eselon_i,
							 "c_eselon_ii"		=> $c_eselon_ii,
							 "c_eselon_iii"		=> $c_eselon_iii,
							 "c_eselon_iv"		=> $c_eselon_iv,
							 "c_eselon_v"		=> $c_eselon_v,
							 "c_wewenang"		=> $c_wewenang,
							 "c_sektoral"		=> $c_sektoral,
							 "c_parent"			=> $c_parent,
							 "c_satker"			=> $c_satker,	
							 "i_entry"			=> $userid); 
							 //print_r($dataMasukan);
		$prosesInsert = $this->admgroup_serv->groupTambah($dataMasukan);
		
		$this->view->proses = "1";	
		$this->view->keterangan = "Aplikasi";
		$this->view->hasil = $prosesInsert;
		
		$this->daftargroupAction();
		$this->render('daftargroup');	
    }
	
	public function admgroupubahAction() {
		$i_group 	 = $_POST['iGroup'];
		$n_group 	 = $_POST['n_group'];
		$userid		 = $this->userid;
		$nGroupOwner = $userid;
		$c_eselon 	 = $_POST['c_eselon'];
		$n_jabatan 	 = $_POST['n_jabatan'];
		$c_jabatan 	 = $_POST['c_jabatan'];
		$c_wewenang  = $_POST['c_wewenang'];
		$c_sektoral  = $_POST['c_sektoral'];
			
		$c_lokasi_unitkerja 	 = $_POST['c_lokasi_unitkerja'];
		if(!$_POST['c_lokasi_unitkerja']){ $c_lokasi_unitkerja 	 = $_POST['c_lokasi_unitkerjaH']; }
		if($c_lokasi_unitkerja == '1'){
			$c_eselon_i_lengkap  = $_POST['c_eselon_i'];
			$c_eselon_iArr = explode(";", $c_eselon_i_lengkap);
			$c_eselon_i	 = $c_eselon_iArr[0];
			
			$c_eselon_ii_lengkap  = $_POST['c_eselon_ii'];
			$c_eselon_iiArr = explode(";", $c_eselon_ii_lengkap);
			$c_eselon_ii	 = $c_eselon_iiArr[0];
			if (!$c_eselon_ii){
				$c_eselon_ii = '000';
			}
			$c_eselon_iii_lengkap  = $_POST['c_eselon_iii'];
			$c_eselon_iiiArr = explode(";", $c_eselon_iii_lengkap);
			$c_eselon_iii	 = $c_eselon_iiiArr[0];
			if (!$c_eselon_iii){
				$c_eselon_iii = '00';
			}
			$c_eselon_iv_lengkap  = $_POST['c_eselon_iv'];
			$c_eselon_ivArr = explode(";", $c_eselon_iv_lengkap);
			$c_eselon_iv	 = $c_eselon_ivArr[0];
			if (!$c_eselon_iv){
				$c_eselon_iv = '00';
			}
			$c_eselon_v_lengkap   = $_POST['c_eselon_v'];
			$c_eselon_vArr = explode(";", $c_eselon_v_lengkap);
			$c_eselon_v	 = $c_eselon_vArr[0];
			if (!$c_eselon_v){
				$c_eselon_v = '00';
			}
			
			$unitKerja= $this->reff_serv->getUnitKerja(" and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and  c_eselon_iv='$c_eselon_iv' ");
			$c_satker= $unitKerja[0]['c_satker'];
			$c_parent= $unitKerja[0]['c_parent'];
			
		}  else if ($c_lokasi_unitkerja == '3'){
			//echo "<br>Hidden = ".$_POST['c_eselon_iH']."|".$_POST['c_eselon_iiH']."|".$_POST['c_eselon_iiiH']."<br>";
			$c_eselon_i=$_POST['c_eselon_i'];
			if(!$_POST['c_eselon_i']) { $c_eselon_i = $_POST['c_eselon_iH']; }
			
			//if ($_POST['c_eselon_i']!=''){
			if ($c_eselon_i !=''){
				$c_eselon_il=explode(";",$c_eselon_i);
				$c_eselon_i=$c_eselon_il[0];
			} else {$c_eselon_i='00';}
			
			$c_eselon_ii=$_POST['c_eselon_ii'];
			if(!$_POST['c_eselon_ii']) { $c_eselon_ii = $_POST['c_eselon_iiH']; }
			
			//if ($_POST['c_eselon_ii']!=''){			
			if ($c_eselon_ii !=''){			
				$valesl = explode(";",$c_eselon_ii);
				$c_eselon_ii=$valesl[0];
				$c_parent=$valesl[1];
			} else {
				$c_eselon_ii='000';
				$c_parent='00';
			}

			$c_eselon_iii=$_POST['c_eselon_iii'];
			if(!$_POST['c_eselon_iii']) { $c_eselon_iii = $_POST['c_eselon_iiiH']; }
			//echo "<br>c_eselon_iii = $c_eselon_iii<br>";
			
			//if ($_POST['c_eselon_iii']!=''){
			if ($c_eselon_iii !=''){			
				$valesliii = explode(";",$c_eselon_iii);
				$c_eselon_ii=$valesliii[0];
				$c_eselon_iii=$valesliii[1];
				$c_satker=$valesliii[2];	
			} else {
				$c_eselon_iii='00';
				$c_satker='00';
			}

			$c_eselon_iv=$_POST['c_eselon_iv'];
			if(!$_POST['c_eselon_iv']) { $c_eselon_iv = $_POST['c_eselon_ivH']; }
			
			//if ($_POST['c_eselon_iv']!=''){
			if ($c_eselon_iv !=''){
				$valesliv = explode(";",$c_eselon_iv);
				$c_eselon_iv=$valesliv[0];
				$c_eselon_v =$valesliv[1];
				//$c_eselon_v ='00';
			} else {
				$c_eselon_iv='00';
				$c_eselon_v ='00';
			}
			
		}	
		
		$dataMasukan = array("pageNumber" 	=> 99,
								"itemPerPage" 	=> 99,
								"kategoriCari" 	=> "n_aplikasi",
								"kataKunciCari" => '',
								"sortBy" 		=> 'i_aplikasi',
								"sortOrder" 	=> 'asc');
		$aplikasiList = $this->admaplikasi_serv->aplikasiList($dataMasukan);
		 for($x=0;$x<count($aplikasiList); $x++){
			$i_aplikasi = $aplikasiList[$x]->i_aplikasi;
			
			$data1 = array("i_aplikasi" => $i_aplikasi);
			$daftarMenuPerAplikasi = $this->admmenu_serv->readAllMenuMapPerAplikasi($data1);
			for($y=0; $y<count($daftarMenuPerAplikasi); $y++){
				$c_menu_level = $daftarMenuPerAplikasi[$y]['c_menu_level'];
				//echo "<br>".$_POST[$i_aplikasi.'_'.$c_menu_level];
				if($_POST[$i_aplikasi.'_'.$c_menu_level]) {					
					$checkBox[] = $i_aplikasi.'_'.$c_menu_level;
				}
			}
			unset($data1);
		}
		
		$dataMasukan = array("i_group" 	=> $i_group,
							 "n_group" 	=> $n_group,
							 "n_group_owner" => $nGroupOwner,
							 "menuArr"	=> $checkBox,
							 "c_wewenang"		=> $c_wewenang,
							 "c_sektoral"		=> $c_sektoral,
							 "c_parent"		=> $c_parent,
							 "c_satker"		=> $c_satker,
							 "i_entry"	=> $userid); 
							 
							  // "c_eselon"			=> $c_eselon,
							 // "c_jabatan"		=> $c_jabatan,
							 // "c_lokasi_unitkerja" => $c_lokasi_unitkerja,
							 // "c_eselon_i"		=> $c_eselon_i,
							 // "c_eselon_ii"		=> $c_eselon_ii,
							 // "c_eselon_iii"		=> $c_eselon_iii,
							 // "c_eselon_iv"		=> $c_eselon_iv,
							 // "c_eselon_v"		=> $c_eselon_v,
							 
							 //echo "<br>"; var_dump($dataMasukan);
		$prosesUbah = $this->admgroup_serv->groupUbah($dataMasukan);
		
		$this->view->proses = "2";	
		$this->view->keterangan = "Aplikasi";
		$this->view->hasil = $prosesUbah;
		
		$this->daftargroupAction();
		$this->render('daftargroup');	
    }
	
	public function admgrouphapusAction() {
		$iGroup = $_REQUEST['iGroup'];
		
		$dataMasukan = array("i_group" => $iGroup);
							 
		$prosesHapus = $this->admgroup_serv->groupHapus($dataMasukan);
		
		$this->view->proses = "3";	
		$this->view->keterangan = "Aplikasi";
		$this->view->hasil = $prosesHapus;
		
		$this->daftargroupAction();
		$this->render('daftargroup');	
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
	
		public function listcomboAction() {
	//echo "lokasi = ".$_GET['c_lokasi_unitkerja_cpns'];
		if ($_GET['c_lokasi_unitkerja']=='1'){
			$jabatanlengkap="";
			$this->view->c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);
			$this->view->lokasiList = $this->reff_serv->getLokasi('');


			$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);
			$this->view->c_eselon_i =trim($_GET['eseloni']);
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			
			$c_eselon_i=$_GET['eseloni'];
			$c_eselon_i=substr($c_eselon_i,0,2);
			$this->view->c_eselon_ii =trim($_GET['eselonii']);
			$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and trim(c_tkt_esl)='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
			
			if ($_GET['eseloni']){$nesl1=$this->left($_GET['eseloni']); $nesl1=",".$nesl1;}
			$c_eselon_ii=$_GET['eselonii'];
			$c_eselon_ii=substr($c_eselon_ii,0,3);
			$this->view->c_eselon_iii =trim($_GET['eseloniii']);
			$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			
			if ($_GET['eselonii']){$nesl2=$this->left($_GET['eselonii']); $nesl2=",".$nesl2;}
			
			$c_eselon_iii=$_GET['eseloniii'];
			$c_eselon_iii=substr($c_eselon_iii,0,2);
			$this->view->c_eselon_iv =trim($_GET['eseloniv']);
			$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			if ($_GET['eseloniii']){$nesl3=$this->left($_GET['eseloniii']); $nesl3=",".$nesl3;}
			
			$c_eselon_iv=$_GET['eseloniv'];
			$c_eselon_iv=substr($c_eselon_iv,0,2);	
			$this->view->eselonvList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii'  and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			if ($_GET['eseloniv']){$nesl4=$this->left($_GET['eseloniv']); $nesl4=",".$nesl4;}
			
			$jabatanlengkap=$_GET['n_jabatan']." ".$nesl4.$nesl3.$nesl2.$nesl1;
			$this->view->n_group=$jabatanlengkap; 
		} else {
			$this->view->c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);
			$this->view->lokasiList = $this->reff_serv->getLokasi('');	
			$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);
			//$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

			
			$c_eselon_i=$_GET['eseloni'];
			$expesl1 = explode(";",$c_eselon_i);
			$c_eselon_i=$expesl1[0];
			$this->view->c_eselon_i =trim($_GET['eseloni']);
			

			$c_eselon_ii=$_GET['eselonii'];
			$expesl2 = explode(";",$c_eselon_ii);
			$c_eselon_ii=$expesl2[0];
			$c_parent=$expesl2[1];
			$this->view->c_eselon_ii =trim($_GET['eselonii']);
			
			
			$c_eselon_iii=$_GET['eseloniii'];
			if ($c_eselon_iii){
				$expesl3 = explode(";",$c_eselon_iii);	
				$c_eselon_ii=$expesl3[0];
				$this->view->c_eselon_iii =trim($_GET['eseloniii']);	
			}

	
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	

			$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_level ='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_eselon_i='$c_eselon_i' ");
			if ($_GET['eseloni']){$nesl1=$expesl1[1]; $nesl1=$nesl1;}

			$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and  c_level ='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_eselon_i='$c_eselon_i'  and c_parent ='$c_parent'");
			if ($_GET['eselonii']){$nesl2=$expesl2[2]; $nesl2=$nesl2.",";}	
				
			$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_level ='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_parent ='$c_parent'");
			if ($_GET['eseloniii']){$nesl3=$expesl3[3]; $nesl3=$nesl3.",";}		


			$this->view->c_eselon_iv =trim($_GET['eseloniv']);
			if ($_GET['eseloniv']){$nesl4=$this->left($_GET['eseloniv']); $nesl4=$nesl4.",";}	
			$jabatanlengkap=$_GET['n_jabatan']." ".$nesl4.$nesl3.$nesl2.$nesl1;
			$this->view->n_group=$jabatanlengkap;
			$this->view->c_eselon=$_GET['c_eselon'];
			$this->view->eselonList = $this->reff_serv->getEselon('');		
			
			$this->render('listcombo2');
		}
		
		/* $c_eselon_i=trim($this->view->ceseloni);
		echo "<br>$c_eselon_i | $c_lokasi_unitkerja";
		if ($c_eselon_i != '01'){
			
			$this->view->lokasiList = $this->reff_serv->getLokasi(" and c_lokasi='$c_lokasi_unitkerja'");
			if ($c_lokasi_unitkerja=='1'){
			echo "wwwwwwwwwwwwwwww";
				$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='1'");
			} else{
				$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
			}
			$this->view->c_eselon_i="";
		} else {			
						

			$this->view->lokasiList = $this->reff_serv->getLokasi('');	
			if ($c_lokasi_unitkerja=='1'){
				$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='1'");
			}else{
			echo "77777777777777777777777777777s";
				$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
			}
			$this->view->c_eselon_i="";
		}	 */

	}
	
	function right2($string){
		return substr($string,0,3);
	}
	function right($string){
	    return substr($string,0,2);
	}
	function left($string){
	    return substr($string,3,200);
	}

}
?>