<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Adminuser_Service.php";
require_once "service/rencana/Rencana_Renjaprogram_Service.php";
require_once "service/rencana/Rencana_Referensi_Service.php";
require_once "service/adm/Adm_Admgroup_Service.php";
require_once "service/adm/Adm_Admaplikasi_Service.php";
require_once "service/adm/Adm_Admmenu_Service.php";
require_once "share/ldap.lib.php";


class Admmodule_AdmuserController extends Zend_Controller_Action {

		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath');
		$this->view->baseData = $registry->get('baseData');		
		$this->view->leftMenu = $registry->get('leftMenu');
		$this->view->dataPerPage = $registry->get('dataPerPage');
		
		//$this->view->photoPath = $registry->get('photoPath');
		 
		$this->adminuser_serv = Adm_Adminuser_Service::getInstance();
		$this->referensi_serv = Rencana_Referensi_Service::getInstance();
		$this->admgroup_serv = Adm_Admgroup_Service::getInstance();
		$this->admaplikasi_serv = Adm_Admaplikasi_Service::getInstance();
		$this->admmenu_serv = Adm_Admmenu_Service::getInstance();
		$this->ldap_service = new ldap_services();

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
			$this->id_bpp			= $ssologin->id_bpp; 
			$this->c_parent			= $ssologin->c_parent; 
			$this->c_child			= $ssologin->c_child; 
			$this->c_izin			= $ssologin->view->userIzin; 
			$this->checkotoritas	= $ssologin->view->checkotoritas; 
		}
    }
	
    public function indexAction() {
    }
	
	public function admuserjsAction() 
	{
		header('content-type : text/javascript');
		$this->render('admuserjs');
	}	
	
	public function daftaruserAction() {    
		$kataKunciCari	= $_POST['kataKunciCari'];
		$kategoriCari	= $_POST['kategoriCari'];

		$cari = $_POST['cari'];
		/* List data pegawai dari */
		/*========================*/
		$currentPage = $_REQUEST['currentPage'];
		$param1 = $_REQUEST['param1'];
		if((!$param1) || ($param1 == 'undefined'))
		{
				$kategoriCari = $kategoriCari;
				if(!$kategoriCari)
				{
						$kategoriCari = "userid";
				}
		}
		else
		{
				$kategoriCari = $param1;
		}
		
		$param2 = $_REQUEST['param2'];
		if((!$param2) || ($param2 == 'undefined'))
		{
				$kataKunciCari = $kataKunciCari;
		}
		else
		{
				$kataKunciCari = $param2;
		}
		
		$param3 = $_REQUEST['param3'];
		$param4 = $_REQUEST['param4'];
		$modulAwal = $_REQUEST['modulAwal'];

		if((!$currentPage) || ($currentPage == 'undefined'))
		{
						$currentPage = 1;
		}

		$offset = $currentPage -1;
		//$numToDisplay = 99; //untuk mengeluarkan semua data (tanpa paging)
		$numToDisplay = 20; //untuk mengeluarkan semua data (tanpa paging)

		//echo "$kataKunciCari | $kategoriCari | $cari";
		if($cari)
		{
				if($kategoriCari == "n_peg")
				{
						$i_peg_nip="";
						$n_peg=$kataKunciCari;
						$userid = "";
				}
				else if($kategoriCari == "userid")
				{
						$i_peg_nip="";
						$n_peg="";
						$userid=$kataKunciCari;
				}
		}
		else
		{
				$i_peg_nip="";
				$n_peg="";
		}
		
		/* if($kategoriCari == "userid")
		{
			$inputCariPegawai = array();
			$dataPegawai = $this->adminuser_serv->findPegawai($inputCariPegawai, 1, 20);       
			$jmlDataPegawai = count($dataPegawai);

		} else { */
			$inputCariPegawai = array("kategoriCari" => $kategoriCari,
									  "kataKunciCari" => $kataKunciCari);
			$dataPegawai = $this->adminuser_serv->findUser($inputCariPegawai, $currentPage, $numToDisplay);       
			$jmlDataPegawai = $this->adminuser_serv->findUser($inputCariPegawai, 0, 0); 

		//}
		
		$this->view->kategoriCari = $kategoriCari;
		$this->view->kataKunciCari = $kataKunciCari;
		$this->view->userList = $userList;
		$this->view->totalData = $jmlDataPegawai;
		$this->view->dataPegawai = $dataPegawai;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
	}

	public function admuserolahdataAction() {
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
		
		$this->view->jenisForm 	= $_REQUEST['jenisForm'];
		$this->view->userid		= $_REQUEST['userid'];
		$this->view->userLogin	= $this->userid;
		
		$this->view->iPegNip	= $_REQUEST['iPegNip'];
		
		$dataMasukan = array("pageNumber" 	=> $pageNumber,
								"itemPerPage" 	=> $numToDisplay,
								"kategoriCari" 	=> $kategoriCari,
								"kataKunciCari" => $kataKunciCari,
								"sortBy" 		=> $sortBy,
								"sortOrder" 	=> $sortOrder,
								"userid"		=> $this->view->userLogin);
		$this->view->GroupList = $this->admgroup_serv->getGrouplist($dataMasukan);
		$this->view->izinList = $this->adminuser_serv->getListIzin();
		
		$dataMasukan = array("pageNumber" 	=> 99,
								"itemPerPage" 	=> 99,
								"kategoriCari" 	=> "n_aplikasi",
								"kataKunciCari" => '',
								"sortBy" 		=> 'i_aplikasi',
								"sortOrder" 	=> 'asc');
		$this->view->aplikasiList = $this->admaplikasi_serv->aplikasiList($dataMasukan);
		
		$this->view->menuListAll = $this->admmenu_serv->readAllMenuMap('1','');
		
		$dataMasukanDetailUser = array("userid"	=> $this->view->userid,
										"i_peg_nip"	=> $this->view->iPegNip);
										
		$this->view->detailUserOlahData = $this->adminuser_serv->detailuserolahdata($dataMasukanDetailUser);
		//var_dump($this->view->detailUserOlahData);
    }
	
	public function daftarpegAction(){
		$kategoriCari 	= $_POST['kategoriCari'];
		$katakunciCari 	= $_POST['katakunciCari'];
		
		$this->view->kategoriCari = $kategoriCari;
		$this->view->katakunciCari = $katakunciCari;
		
		$dataMasukan = array("kategoriCari"	=> $kategoriCari,
							"kataKunciCari"	=> $katakunciCari);
		$pageNumber 	= 1;
		$itemPerPage	= $this->view->dataPerPage;
		//$this->view->totaldaftarPenanggungjawab = $this->adminuser_serv->findPegawai($dataMasukan, 0, 0);  
		//$this->view->daftarPenanggungjawab = $this->adminuser_serv->findPegawai($dataMasukan, 1, 20); 
		
		$this->view->totaldaftarPenanggungjawab = $this->adminuser_serv->getPegawaiList($dataMasukan, 0, 0);
		$this->view->daftarPenanggungjawab = $this->adminuser_serv->getPegawaiList($dataMasukan, $pageNumber, $itemPerPage);
		
	}

    public function admusertambahAction() {
		$userid = $_POST['userid'];
		$nPeg = $_POST['nPeg'];
		$iPegNip = $_POST['iPegNip'];
		$cGolongan = $_POST['cGolongan'];
		$nUnitkerja = $_POST['nUnitkerja'];
		$userPasswd = $_POST['userPasswd'];
		$userPasswd2 = $_POST['userPasswd2'];
		$c_kategori_user = $_POST['kategori_user'];
		$iEntry	= $this->userid;
		
		if($userPasswd == $userPasswd2){
			$dataMasukanTambah = array("userid" 	=> $userid,
									   "i_peg_nip" 	=> $iPegNip,
									   "n_password" => $userPasswd,
									   "c_kategori_user" => $c_kategori_user,
									   "i_entry"	=> $iEntry);
									 
			/* $dataMasukanGroup = array("pageNumber" 	=> 9999,
									"itemPerPage" 	=> 9999,
									"kategoriCari" 	=> $kategoriCari,
									"kataKunciCari" => $kataKunciCari,
									"sortBy" 		=> 'i_group',
									"sortOrder" 	=> 'asc');
			$GroupList = $this->admgroup_serv->getGrouplist($dataMasukanGroup);
				
			for($x=0; $x<count($GroupList); $x++){
				$iGroup = $GroupList[$x]['i_group'];
				if($iGroup == 0){
					$dataMasukanTambah['group_'.$iGroup] = 'on';
				} else {
					$dataMasukanTambah['group_'.$iGroup] = $_POST['group_'.$iGroup]; 
				}
			}
			//var_dump($dataMasukanTambah);
			$izinList = $this->adminuser_serv->getListIzin();
			//var_dump($izinList);
			
			//check apakah ada menu yg dipilih
			//-------------------------------------------
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
			
			$dataMasukanTambah['menu'] = $checkBox; */ 
			$dataMasukanTambah['dirsimpan'] = $this->view->baseData;
			//echo "dir = ".$this->view->baseData;
			//end check pilih menu
			
			//var_dump($dataMasukanTambah);
			//$prosesInsert = $this->adminuser_serv->userTambah($dataMasukanTambah);
			$prosesInsert = $this->adminuser_serv->userTambahVLDAP($dataMasukanTambah);
			
			//$prosesInsert = 'sukses';
			$this->view->proses = "1";	
			$this->view->keterangan = "Userid dan Hak Akses";
			$this->view->hasil = $prosesInsert;
			
			$this->daftaruserAction();
			$this->render('daftaruser');	
		} else {
			$prosesInsert = 'Gagal. Password dan Ulangi Password tidak sama.';
			$this->view->proses = "1";	
			$this->view->keterangan = "Aplikasi";
			$this->view->hasil = $prosesInsert;
		}		
    }
	
	public function admuserubahAction() {
		$userid = $_POST['userid'];
		$nPeg = $_POST['nPeg'];
		$iPegNip = $_POST['iPegNip'];
		$cGolongan = $_POST['cGolongan'];
		$nUnitkerja = $_POST['nUnitkerja'];
		//$cIzin = $_POST['izin'];
		$c_kategori_user = $_POST['kategori_user'];
		
		$iEntry	= $this->userid;
		
		$dataMasukanTambah = array("userid" 	=> $userid,
								   "i_peg_nip" 	=> $iPegNip,
								   "n_password" => $userPasswd,
								   "c_kategori_user" => $c_kategori_user,
								   "i_entry"	=> $iEntry);
								 
		/* $dataMasukanGroup = array("pageNumber" 	=> 9999,
								"itemPerPage" 	=> 9999,
								"kategoriCari" 	=> $kategoriCari,
								"kataKunciCari" => $kataKunciCari,
								"sortBy" 		=> 'i_group',
								"sortOrder" 	=> 'asc');
		$GroupList = $this->admgroup_serv->getGrouplist($dataMasukanGroup);
			
		for($x=0; $x<count($GroupList); $x++){
			$iGroup = $GroupList[$x]['i_group'];
			if($iGroup == 0){
				$dataMasukanTambah['group_'.$iGroup] = 'on';
			} else {
				$dataMasukanTambah['group_'.$iGroup] = $_POST['group_'.$iGroup]; 
			}
		}
		//var_dump($dataMasukanTambah);
		$izinList = $this->adminuser_serv->getListIzin();
		//var_dump($izinList);
		
		//check apakah ada menu yg dipilih
		//-------------------------------------------
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
		
		$dataMasukanTambah['menu'] = $checkBox; */ 
		//end check pilih menu
		
		//var_dump($dataMasukanTambah);
		$prosesUbah = $this->adminuser_serv->userUbah($dataMasukanTambah);
		//$prosesInsert = 'sukses';
		$this->view->proses = "2";	
		$this->view->keterangan = "Userid dan Hak Akses";
		$this->view->hasil = $prosesUbah;
		
		$this->daftaruserAction();
		$this->render('daftaruser');	
	
	
    }
	
	/* cdr, 20120408, ubah password masing-masing user
	---------------------------------------------------*/
	public function admuserubahpasswordAction() {
		$this->view->userid = $this->userid;
	}
	
	public function prosesubahpasswordAction() {
		$userid		= $_POST['userid'];
		$i_peg_nip 		= $this->i_peg_nip;
		$userPasswd 		= trim($_POST['userPasswd']);
		$userPasswd2 		= trim($_POST['userPasswd2']);
		$passwordLama		= trim($_POST['userPasswdLama']);
		$dirsimpan		= $this->view->baseData;
		
		$dataMasukanCheckLogin = array("userid"	=> $userid,
						   "password" => $passwordLama);

		$this->view->userid= $userid;
		$this->view->userPasswd = $userPasswd ;
		$this->view->userPasswd2 = $userPasswd2 ;

		$this->view->checkLogin = $this->ldap_service->checkLoginLdap($dataMasukanCheckLogin);
		if(trim($this->view->checkLogin) == '1'){
			if($userPasswd == $userPasswd2){
				$this->view->penanda = 'sama';
				$dataMasukanUbahPassword = array("userid" 	=> $userid,
									"i_peg_nip" 	=> $i_peg_nip,
									"passwordLama"=> $userPasswdLama,
									"userPasswd"	=> $userPasswd,
									"dirsimpan"	=> $dirsimpan,
									"i_entry"	=> $iEntry);
				
				$prosesUbahPassword = $this->adminuser_serv->userGantiPasswordVLDAP($dataMasukanUbahPassword);
				
			} else {
				
				$prosesUbahPassword = 'Password dan konfirmasi password tidak sama.';
			}
		} else {
			$prosesUbahPassword = 'Password semula salah.';
		}
		$this->view->prosesUbahPassword  = $prosesUbahPassword;
		if ($userid && $userPasswd){
			/* catat di session */
			/*------------------*/
			$dataMasukan = array("userid" 	=> $userid,
						 "password"   => $userPasswd);
		
			//check login ke ldap
			//=================
			//$this->view->checklogin = $this->ldap_service->koneksiLdap($dataMasukan);
		
		echo "test ".$this->view->checkLogin ;
		
			if($this->view->checkLogin ){
				//$this->_helper->layout->setLayout('main-layout');	
				$this->view->detailUser = $this->adminuser_serv->getDetailUser($dataMasukan);
				$this->view->userIzin	= $this->adminuser_serv->getUserIzin($dataMasukan);
				
				// Check otoritas ke aplikasi
				//----------------------------------
				$this->view->checkotoritas = $this->adminuser_serv->getOtoritasAplikasi($dataMasukan);
				
				$this->view->xxx = $userid;
				//var_dump($this->view->detailUser);
				//Setting session
				//-------------------
				$ssologin = new Zend_Session_Namespace('ssologin');
				
				$ssologin->userid				= $this->view->detailUser['userid'];	
				$ssologin->password     		= $userPasswd;
				$ssologin->i_peg_nip     		= $this->view->detailUser['i_peg_nip'];
				$ssologin->i_peg_nip_new   		= $this->view->detailUser['i_peg_nip_new'];
				$ssologin->n_peg     			= $this->view->detailUser['n_peg'];
				$ssologin->n_peg_gelardepan     = $this->view->detailUser['n_peg_gelardepan'];
				$ssologin->n_peg_gelarblkg 		= $this->view->detailUser['n_peg_gelarblkg'];
				$ssologin->c_jabatan 			= $this->view->detailUser['c_jabatan']; 
							
				$ssologin->c_eselon_i 			= $this->view->detailUser['c_eselon_i'];
				$ssologin->c_eselon_ii 			= $this->view->detailUser['c_eselon_ii'];
				$ssologin->c_eselon_iii 		= $this->view->detailUser['c_eselon_iii'];
				$ssologin->c_eselon_iv 			= $this->view->detailUser['c_eselon_iv'];
				$ssologin->c_eselon_v 			= $this->view->detailUser['c_eselon_v'];
				$ssologin->c_lokasi_unitkerja 	= $this->view->detailUser['c_lokasi_unitkerja']; 
				$ssologin->c_satker			 	= $this->view->detailUser['c_satker']; 
				$ssologin->n_satker			 	= $this->view->detailUser['n_satker']; 
				$ssologin->c_izin			 	= $this->view->userIzin; 
				$ssologin->checkotoritas		= $this->view->checkotoritas; 
				
				$this->view->xx = $ssologin->password;
				$this->view->ssologin = $ssologin;
				
				//tambah session otoritas user ke aplikasi
				$ssologin->checkotoritas	 	= $this->view->checkotoritas;
				
				
				// ambil list aplikasi
				//----------------------------------
				$dataMasukanAplikasiList = array("pageNumber" => 99,
												"itemPerPage" => 99,
												"kategoriCari" => "semua",
												"katakunciCari" => "",
												"sortBy" => "i_urut_aplikasi",
												"sortOrder" => "asc");
				$this->view->aplikasiList = $this->admaplikasi_serv->aplikasiList($dataMasukanAplikasiList);
			}
		
				/* akhir catat session */
				/*---------------------*/
		}
		$this->view->pesan2 = "<script>
				alert('Proses Ganti password berhasil.');
			   </script>";

	}
	


	public function admuserresetpasswordAction() {
		$userid 		= $_POST['userid'];
		$iPegNip 		= $_POST['iPegNip'];
		$userPasswdBaru	= $_POST['userPasswd'];
		$iEntry			= $this->userid;
			
		$dataMasukan1 = array("userid" 	=> $userid,
							 "password" => $userPasswdLama);

		$dataMasukanResetPassword = array("userid" 	=> $userid,
								   "i_peg_nip" 	=> $iPegNip,
								   "n_password" => $userPasswdBaru,
								   "i_entry"	=> $iEntry,
								   "dirsimpan"	=> $this->view->baseData);
								   
					//var_dump($dataMasukanResetPassword);		 
		$prosesUbahPasswordLDAP = $this->adminuser_serv->userResetPasswordVLDAP($dataMasukanResetPassword);
		
		$this->view->proses = "2";	
		$this->view->keterangan = "Password User '$userid' ";
		$this->view->hasil = $prosesUbahPasswordLDAP;
		
		$this->daftaruserAction();
		$this->render('daftaruser');	
	
    }
	
	public function checkloginAction() {
		$userid 		= $_REQUEST['userid'];
		$passwordLama 	= $_REQUEST['passwordLama'];
		
		$dataMasukanheckPassword = array("userid" 	=> $userid,
						      "password" 	=> $passwordLama);
						//var_dump($dataMasukanheckPassword);		 
		//$this->view->checklogin = $this->adminuser_serv->checkLogin($dataMasukanheckPassword);
		$this->view->checklogin = $this->ldap_service->checkLoginLdap($dataMasukanheckPassword);
		
    }
	
	//Hapus User : menghapus portal di attribute bisnis kategori di ldap
    //========================================================
	public function hapususerAction() {     
		$userid 	= $_REQUEST['i_user'];
		$nPeg 		= $_REQUEST['n_peg'];
		$iPegNip	= $_REQUEST['i_peg_nip'];
		$dirsimpan	= $this->view->baseData;
		$currentPage= $_REQUEST['currentPage'];
		// update data yg sudah ada
		$paramUserInfo = array("userid" 	=> $userid,
							   "i_peg_nip"	=> $iPegNip,
							   "dirsimpan"	=> $dirsimpan);
		
		$deleteUserInfo = $this->adminuser_serv->deleteUserVLDAP($paramUserInfo);
		$this->view->hasil = $deleteUserInfo;
		$this->view->proses = 3;
		
		// Kembali ke daftar User                                             
		//=====================
		unset($_REQUEST);
		$_REQUEST = array("currentPage" => $currentPage);
		$this->daftaruserAction();
		$this->_helper->viewRenderer('daftaruser');
	}
	
	public function gantipasswordolahdataAction() {
		$this->view->userid = $this->userid;
	}
	
	/*public function gantipasswordAction() {
		$userid 		= $_REQUEST['userid'];
		$userPasswd		= $_REQUEST['userPasswd'];
		$passwordLama	= $_REQUEST['passwordLama'];
		$iEntry			= $this->userid;
			
		$dataMasukan1 = array("userid" 	=> $userid,
							 "password" => $userPasswd);

		$dataMasukanGantiPassword = array("userid" 	=> $userid,
								   "userPasswd" 	=> $userPasswd,
								   "passwordLama" 	=> $passwordLama,
								   "i_entry"		=> $iEntry,
								   "dirsimpan"		=> $this->view->baseData);
								   
					//var_dump($dataMasukanResetPassword);		 
		$prosesUbahPasswordLDAP = $this->adminuser_serv->userGantiPasswordVLDAP($dataMasukanGantiPassword);
		
		$this->view->proses = "2";	
		$this->view->keterangan = "Password User '$userid' ";
		$this->view->hasil = $prosesUbahPasswordLDAP;
		
		//$this->daftaruserAction();
		//$this->render('daftaruser');	
	
    }*/
}
?>