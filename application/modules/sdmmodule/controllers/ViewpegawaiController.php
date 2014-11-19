<?php
require_once 'Zend/Controller/Action.php';
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_Caripegawai_Service.php";
require_once 'Zend/Auth.php';


class Sdm_ViewpegawaiController extends Zend_Controller_Action {
	private $pegawai_serv;
	private $peg_ref_serv;
	private $id;
	private $kdorg;
	private $ssogroup;
		
    public function init() {
		// Local to this controller only; affects all actions, as loaded in init:
		//$this->_helper->viewRenderer->setNoRender(true);
		$registry = Zend_Registry::getInstance();
	    $this->view->basePath = $registry->get('basepath'); 
        $this->view->pathUPLD = $registry->get('pathUPLD');
        $this->view->procPath = $registry->get('procpath');
		$this->sdm_peg_serv = Sdm_Pegawai_Service::getInstance();
		$this->sdm_caripeg_serv = Sdm_Caripegawai_Service::getInstance();
	   //$ssogroup = new Zend_Session_Namespace('ssogroup');
	   //echo "TEST ".$ssogroup->n_user_grp." ".$ssogroup->i_user." ".$ssogroup->i_peg_nip;
	    //$this->usernip = $ssogroup->i_peg_nip;

	   $auth        = Zend_Auth::getInstance();
       $this->id    = $auth->getIdentity();
	   $this->userid  = strtoupper($this->id['userid']);
	   $this->username  = strtoupper($this->id['username']);
	   $this->nip  = strtoupper($this->id['nip']);
	   $this->kdorg = strtoupper($this->id['dept']);
	   $this->mod_cat = $this->id['mod_cat'];
	   //$this->kdorg = $this->ssogroup->i_orgb;
	   $mod_cat = $this->mod_cat;
	   $pembanding = '5';
	   /*  variabel pembanding diatas harus di isi dengan angka modul
		1	PRNC	Perencanaan
		2	KEU	Keuangan
		3	SRT	Persuratan
		4	ARS	Arsip dan Dok
		5	SDM	SDM
		6	PNG	Pengadaan
		7	AST	Aset
		8	ATK	ATK
		9	ATI	Aset TI
		10	HMS	Humas
		11	HUM	Hukum
		12	ASB	Aset BUMN
		13	KOM	KOmunikasi Pengguna
		14	ADM Administrasi
	        */
	   if (is_Array($mod_cat)) {
		 for ($i = 0; $i < count($mod_cat); $i++) {
		   if ($mod_cat[$i]->modul == $pembanding) {
			 $this->modul    = $mod_cat[$i]->modul;
			 $this->category = $mod_cat[$i]->kategori;
		   }
		 }
	   }
	   //echo "MODUL KAT ".$this->modul." ".$this->category;
    }
		
    
	
    public function indexAction() {
	   //echo "Pagu Masuk Controller indexAction";
    }
//========================DATA SEARCH========================================	

    public function pegawaisearchAction() {
	//echo"tessssssssssssst";
		$hapus = $_REQUEST['perintah'];
		$usernip = $this->nip;
		$category = $this->category;
		
		$this->view->dataUserOrg = $this->sdm_peg_serv->getUserOrg($usernip);	 
		$this->view->nip = $this->view->dataUserOrg ['nip'];
		$this->view->userOrg = $this->view->dataUserOrg ['userOrg'];
		$this->view->level = $this->view->dataUserOrg ['level'];
		
		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($usernip);	 
		$this->view->eselon = $this->view->dataPokok ['eselon'];
		
		$userLevel = $this->view->level;
		$userOrg = $this->view->userOrg;
		$eselon = $this->view->eselon;
	
		if ($category == 'A') {
			$org = 'MN';
		}
		else {
			if ($eselon == 'NE') {
				$org = 'NE';
				}
			else{
				if ($userLevel == '1') {
					$org = 'MN';
				}
				else if ($userLevel == '2') {
				    if (substr($userOrg,0,2)=='SK'){
						$org = substr($userOrg,0,2);
					}
					else {
						$org = substr($userOrg,0,3);
					}
				}
				else if ($userLevel == '3') {
				    if (substr($userOrg,0,2)=='SK'){
						$org = substr($userOrg,0,3);
					}
					else {
						$org = substr($userOrg,0,4);
					}
				}
				else if ($userLevel == '4') {
					if (substr($userOrg,0,2)=='SK'){
						$org = substr($userOrg,0,4);
					}
					else {
						$org = substr($userOrg,0,5);
					}
				}
				else if ($userLevel == '5') {
					$org = substr($userOrg,0,6);
				}	
			}
		}

		//echo "userOrg=> ".$userOrg."<br>"."userlevel=> ".$userLevel."<br>"."org=> ".$org."<br>";
		
	   $id = $_POST['id'];	   
	   if ($id == null) {
			$id = $_REQUEST['param1'];
	   }
	   
	   $xxxId = $_POST['xxxId'];
	   if ($xxxId == null) {
			$xxxId = $_REQUEST['param2'];
	   }

		$sort = $_REQUEST['param3']; 
		//echo "sort= ".$sort."<br>";
		$currentPage = $_REQUEST['currentPage']; 

		$param1 = $_REQUEST['param1']; 
		$param2 = $_REQUEST['param2']; 
		$param3 = $_REQUEST['param3']; 
		$param4 = $_REQUEST['param4']; 
		
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		}
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
			
		//$this->view->totPegawai = $this->sdm_caripeg_serv->getPegawaiListAllByUser($id, $org, $usernip, $xxxId, $sort, 0, 0);
		//$this->view->pegawaiList = $this->sdm_caripeg_serv->getPegawaiListAllByUser($id, $org, $usernip, $xxxId, $sort, $currentPage, $numToDisplay);

		if ($sort == null) {
			$this->view->totPegawai = $this->sdm_caripeg_serv->getPegawaiAll($id, $org, $usernip, $xxxId, 0, 0);
			$this->view->pegawaiList = $this->sdm_caripeg_serv->getPegawaiAll($id, $org, $usernip, $xxxId, $currentPage, $numToDisplay);		
		}	
		else if ($sort == 'nip') {
			$this->view->totPegawai = $this->sdm_caripeg_serv->getPegawaiAllByNip($id, $org, $usernip, $xxxId, 0, 0);
			$this->view->pegawaiList = $this->sdm_caripeg_serv->getPegawaiAllByNip($id, $org, $usernip, $xxxId, $currentPage, $numToDisplay);		
		}
		else if ($sort == 'nama') {	
			//echo "sortnama";
			$this->view->totPegawai = $this->sdm_caripeg_serv->getPegawaiAll($id, $org, $usernip, $xxxId, 0, 0);
			$this->view->pegawaiList = $this->sdm_caripeg_serv->getPegawaiAll($id, $org, $usernip, $xxxId, $currentPage, $numToDisplay);		
		}
		else if ($sort == 'gol') {		
			$this->view->totPegawai = $this->sdm_caripeg_serv->getPegawaiAllByGol($id, $org, $usernip, $xxxId, 0, 0);
			$this->view->pegawaiList = $this->sdm_caripeg_serv->getPegawaiAllByGol($id, $org, $usernip, $xxxId, $currentPage, $numToDisplay);		
		}
		else if ($sort == 'stat') {		
			$this->view->totPegawai = $this->sdm_caripeg_serv->getPegawaiAllByStat($id, $org, $usernip, $xxxId, 0, 0);
			$this->view->pegawaiList = $this->sdm_caripeg_serv->getPegawaiAllByStat($id, $org, $usernip, $xxxId, $currentPage, $numToDisplay);		
		}
		else if ($sort == 'uK') {		
			$this->view->totPegawai = $this->sdm_caripeg_serv->getPegawaiAllByUK($id, $org, $usernip, $xxxId, 0, 0);
			$this->view->pegawaiList = $this->sdm_caripeg_serv->getPegawaiAllByUK($id, $org, $usernip, $xxxId, $currentPage, $numToDisplay);		
		}
		else if ($sort == 'eselon') {		
			$this->view->totPegawai = $this->sdm_caripeg_serv->getPegawaiAllByEselon($id, $org, $usernip, $xxxId, 0, 0);
			$this->view->pegawaiList = $this->sdm_caripeg_serv->getPegawaiAllByEselon($id, $org, $usernip, $xxxId, $currentPage, $numToDisplay);		
		}
		
		
		
		
	   if ($_POST['perintah'] == 'CARI'){
		   $id = $_POST['id'];	   
		   if ($id == null) {
				$id = $_REQUEST['param1'];
		   }
		   
		   $xxxId = $_POST['xxxId'];
		   if ($xxxId == null) {
				$xxxId = $_REQUEST['param2'];
		   }
		$sort = $_REQUEST['param3']; 

			if ($org == 'NE') {
			?>
				<script type="text/javascript">	
					alert("Anda Hanya Bisa Melihat Data Anda Sendiri");
				</script> 
			<?
			}

			
			$currentPage = $_REQUEST['currentPage']; 
			$param1 = $_REQUEST['param1']; 
			$param2 = $_REQUEST['param2']; 
			$param3 = $_REQUEST['param3']; 
			$param4 = $_REQUEST['param4']; 

			if(!$currentPage)
			{
				$currentPage = 1;
			}
			
			$numToDisplay = 20;
			$this->view->numToDisplay = $numToDisplay;
			$this->view->currentPage = $currentPage;
			// $this->view->totPegawai = $this->sdm_peg_serv->getPegawaiListAllByUser($id, $org, $usernip, $xxxId, 0, 0);
			// $this->view->pegawaiList = $this->sdm_peg_serv->getPegawaiListAllByUser($id, $org, $usernip, $xxxId, $currentPage, $numToDisplay);
		if ($sort == null) {
			$this->view->totPegawai = $this->sdm_caripeg_serv->getPegawaiAll($id, $org, $usernip, $xxxId, 0, 0);
			$this->view->pegawaiList = $this->sdm_caripeg_serv->getPegawaiAll($id, $org, $usernip, $xxxId, $currentPage, $numToDisplay);		
		}	
		else if ($sort == 'nip') {
			$this->view->totPegawai = $this->sdm_caripeg_serv->getPegawaiAllByNip($id, $org, $usernip, $xxxId, 0, 0);
			$this->view->pegawaiList = $this->sdm_caripeg_serv->getPegawaiAllByNip($id, $org, $usernip, $xxxId, $currentPage, $numToDisplay);		
		}
		else if ($sort == 'nama') {		
			$this->view->totPegawai = $this->sdm_caripeg_serv->getPegawaiAll($id, $org, $usernip, $xxxId, 0, 0);
			$this->view->pegawaiList = $this->sdm_caripeg_serv->getPegawaiAll($id, $org, $usernip, $xxxId, $currentPage, $numToDisplay);		
		}
		else if ($sort == 'gol') {		
			$this->view->totPegawai = $this->sdm_caripeg_serv->getPegawaiAllByGol($id, $org, $usernip, $xxxId, 0, 0);
			$this->view->pegawaiList = $this->sdm_caripeg_serv->getPegawaiAllByGol($id, $org, $usernip, $xxxId, $currentPage, $numToDisplay);		
		}
		else if ($sort == 'stat') {		
			$this->view->totPegawai = $this->sdm_caripeg_serv->getPegawaiAllByStat($id, $org, $usernip, $xxxId, 0, 0);
			$this->view->pegawaiList = $this->sdm_caripeg_serv->getPegawaiAllByStat($id, $org, $usernip, $xxxId, $currentPage, $numToDisplay);		
		}
		else if ($sort == 'uK') {		
			$this->view->totPegawai = $this->sdm_caripeg_serv->getPegawaiAllByUK($id, $org, $usernip, $xxxId, 0, 0);
			$this->view->pegawaiList = $this->sdm_caripeg_serv->getPegawaiAllByUK($id, $org, $usernip, $xxxId, $currentPage, $numToDisplay);		
		}
		else if ($sort == 'eselon') {		
			$this->view->totPegawai = $this->sdm_caripeg_serv->getPegawaiAllByEselon($id, $org, $usernip, $xxxId, 0, 0);
			$this->view->pegawaiList = $this->sdm_caripeg_serv->getPegawaiAllByEselon($id, $org, $usernip, $xxxId, $currentPage, $numToDisplay);		
		}
		
			
			
			$_SESSION['id'] = $id;
	   
	   		}
    }

//========================DATA POKOK========================================	

 
    public function pegawaiviewAction() {
	   //echo "Masuk Controller pegawaiviewAction";
	   $nip = $_REQUEST['nip'];
	   //echo "nip--> ".$nip;
	   $this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);	 
	   $this->view->nip = $this->view->dataPokok ['i_peg_nip'];
	   $this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
	  // $this->view->gelar = $this->view->dataPokok ['n_peg_gelar'];
	   $this->view->gelarDpn = $this->view->dataPokok ['gelarDpn'];
	   $this->view->gelarBlk = $this->view->dataPokok ['gelarBlk'];
	   $this->view->karpeg = $this->view->dataPokok ['i_peg_karpeg'];
	   //$this->view->jbt = $this->view->dataPokok ['n_jabatan'];
	   $this->view->jabatan = $this->view->dataPokok ['jabatan'];
	   $this->view->npwp = $this->view->dataPokok ['npwp'];
	   //$this->view->unitkerja = $this->view->dataPokok ['c_unit_kerja'];
	   $this->view->kdUnitkerja = $this->view->dataPokok ['c_unit_kerja'];
	   $this->view->nmUnitkerja = $this->view->dataPokok ['namaUnitKerja'];
	   $this->view->kdUnitOrg = $this->view->dataPokok ['i_orgb'];
	   $this->view->nUnitOrg = $this->view->dataPokok ['namaOrgb'];
	   $this->view->kdPenempatan = $this->view->dataPokok ['kdPenempatan'];
	   $this->view->namaPenempatan = $this->view->dataPokok ['namaPenempatan'];
	   $this->view->tglTMT = $this->view->dataPokok ['tglTMT'];
	   $this->view->ukAsal = $this->view->dataPokok ['ukAsal'];
	   $this->view->jenisKelamin = $this->view->dataPokok ['c_peg_jeniskelamin'];
	   $this->view->tmpLahir = $this->view->dataPokok ['a_peg_lahir'];
	   $this->view->tglLahir = $this->view->dataPokok ['tglLahir'];
	   //$this->view->kdPendidikan = $this->view->dataPokok ['c_pend'];
	   //$this->view->nmPendidikan = $this->view->dataPokok ['namaPendidikan'];
	   $this->view->pendidikan = $this->view->dataPokok ['pendidikan'];
	   //$this->view->kdAgama = $this->view->dataPokok ['c_agama'];
	   $this->view->nmAgama = $this->view->dataPokok ['namaAgama'];
	   //$this->view->agama = $this->view->dataPokok ['agama'];
	   $this->view->kdGol = $this->view->dataPokok ['c_peg_golongan'];
	   $this->view->nmGol = $this->view->dataPokok ['namaGol'];
	   $this->view->alamat = $this->view->dataPokok ['a_peg_rumah'];
	   $this->view->rt = $this->view->dataPokok ['a_peg_rt'];
	   $this->view->rw = $this->view->dataPokok ['a_peg_rw'];
	   $this->view->kelurahan = $this->view->dataPokok ['a_peg_kelurahan'];
	   $this->view->kecamatan = $this->view->dataPokok ['a_peg_kecamatan'];
	   $this->view->kabupaten = $this->view->dataPokok ['a_peg_kota'];
	   //$this->view->kabupaten = $kabupaten;
	   $this->view->propinsi = $this->view->dataPokok ['a_peg_propinsi'];
	   //$this->view->propinsi = $propinsi;
	   $this->view->kodePos = $this->view->dataPokok ['a_peg_kodepos'];
	   $this->view->teleponRumah = $this->view->dataPokok ['i_peg_telponrumah'];
	   $this->view->tlpGenggam = $this->view->dataPokok ['i_peg_telponhp'];
	   $this->view->stsNikah = $this->view->dataPokok ['c_peg_statusnikah'];
	   //$this->view->kdStatusKerja = $this->view->dataPokok ['c_peg_status'];
	   $this->view->nmStatusKerja = $this->view->dataPokok ['nmStatusKerja'];	   
	   $this->view->statusKerja = $this->view->dataPokok ['statusKerja'];
	   $this->view->jenisIdentitas = $this->view->dataPokok ['c_peg_identitas'];
	   $this->view->nomerIdentitas = $this->view->dataPokok ['i_peg_identitas'];
	   $this->view->kewarganegaraan = $this->view->dataPokok ['n_peg_wn'];
	   $this->view->suku = $this->view->dataPokok ['n_peg_suku'];
	   $this->view->hobi = $this->view->dataPokok ['n_peg_hobi'];	   
	   $this->view->namaOrtu = $this->view->dataPokok ['n_ortu'];
	   $this->view->alamatOrtu = $this->view->dataPokok ['a_ortu_jalan'];
	   $this->view->rtOrtu = $this->view->dataPokok ['a_ortu_rt'];
	   $this->view->rwOrtu = $this->view->dataPokok ['a_ortu_rw'];
	   $this->view->kelurahanOrtu = $this->view->dataPokok ['a_ortu_kelurahan'];
	   $this->view->kecamatanOrtu = $this->view->dataPokok ['a_ortu_kecamatan'];
	   $this->view->kabupatenOrtu = $this->view->dataPokok ['a_ortu_kota'];
	   //$this->view->kabupatenOrtu = $kabupatenOrtu;
	   $this->view->propinsiOrtu = $this->view->dataPokok ['a_ortu_propinsi'];
	   //$this->view->propinsiOrtu = $propinsiOrtu;
	   $this->view->teleponRumahOrtu = $this->view->dataPokok ['i_ortu_telponrumah'];
	   $this->view->tlpGenggamOrtu = $this->view->dataPokok ['i_ortu_telponhp'];	  
	   $this->view->eselon = $this->view->dataPokok ['eselon'];	  
	   $this->view->tglEselon = $this->view->dataPokok ['tglEselon'];
	   $this->view->email = $this->view->dataPokok ['email'];
	   $this->view->email2 = $this->view->dataPokok ['email2'];
	   $this->view->anak = $this->view->dataPokok ['anak'];
	   $this->view->darah = $this->view->dataPokok ['darah'];

	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   $this->view->i_nip_baru = $this->view->dataPokok['i_nip_baru'];
	}
//========================PENDIDIKAN========================================	
    public function pendidikanAction() {
	   //echo "Masuk Controller pegawaiupdateAction";
		   $nip = $_REQUEST['nip'];
		   $kdjenjang = $_REQUEST['jenjang'];
		   $_SESSION['nip'] = $nip;
		//echo "nip".$nip;
		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   
		$this->view->pendList = $this->sdm_peg_serv->getPendidikan($nip);	
		
    }

    public function pendidikanviewAction() {
	   //echo "Masuk Controller pendidikanviewAction";
		   $nip = $_REQUEST['nip'];
		   $kdjenjang = $_REQUEST['jenjang'];
	   $this->view->pendList = $this->sdm_peg_serv->getPendidikan1($nip,$kdjenjang);

		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
		
	   $this->view->nip = $this->view->pendList ['i_peg_nip'];
	   $this->view->nmjenjang = $this->view->pendList ['s_jenjang'];
	   $this->view->pendidikan = $this->view->pendList ['s_pendidikan'];
	   $this->view->tempat = $this->view->pendList ['s_tempat'];
	   $this->view->jurusan = $this->view->pendList ['s_jurusan'];
	   $this->view->mulai = $this->view->pendList ['s_mulai'];
	   $this->view->akhir = $this->view->pendList ['s_akhir'];
	    $this->view->kepSek = $this->view->pendList ['kepSek'];
	    $this->view->ipk = $this->view->pendList ['ipk'];
	    $this->view->skripsi = $this->view->pendList ['skripsi'];
	    $this->view->biaya = $this->view->pendList ['biaya'];
	   //$this->view->dokumen = $this->view->pendList ['s_dokumen'];
	   $this->view->keterangan = $this->view->pendList ['s_keterangan'];
	    $this->view->noIjazah = $this->view->pendList ['noIjazah'];
	    $this->view->tglIjazah = $this->view->pendList ['tglIjazah'];
				
		$nip = $this->view->dataPokok ['i_peg_nip'];
		$_SESSION['nip'] = $nip;
		//echo "Nip=>".$nip;
		
	   if ($_POST['perintah'] == 'KEMBALI'){
		    //$nip = $_SESSION['nip'];
		   //$kdjenjang = $_REQUEST['jenjang'];
			$nip = $_POST['nipH'];
		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   
		$this->view->pendList = $this->sdm_peg_serv->getPendidikan($nip);	
		//$nip = $_POST['nipH'];
		//echo "Nip=".$nip;
			   
		$this->render('pendidikan');
	   }
    }	

	
//========================PELATIHAN========================================	
    public function pelatihanAction() {
	   //echo "Masuk Controller pegawaiupdateAction";
		   $nip = $_REQUEST['nip'];
		   //$kdjenjang = $_REQUEST['jenjang'];
		
		   $_SESSION['nip'] = $nip;
		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   
		$this->view->latihList = $this->sdm_peg_serv->getPelatihan($nip);		   
	   
	   if ($_POST['perintah'] == 'KEMBALI'){
		   $this->view->junit = $this->sdm_peg_serv->getUnitKerjaListAll();
		   $this->view->pendidikanList = $this->sdm_peg_serv->getPendidikanListAll();
		   //echo $this->view->pendidikanList;
		   $this->view->agamaList = $this->sdm_peg_serv->getAgamaListAll();
		   $this->view->pangkatList = $this->sdm_peg_serv->getPangkatListAll();
		   $this->view->propinsiList = $this->sdm_peg_serv->getPropinsiListAll();
		   $this->view->kabupatenList = $this->sdm_peg_serv->getKabupatenListAll();
		   $this->view->statusPegList = $this->sdm_peg_serv->getStatusPegListAll();
		   	   
		   $nip = $_POST['nipH'];
		   //echo "nip = ".$nip;
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   
		   
		   
		   $this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);	   
		   
		   $this->view->nip = $this->view->dataPokok ['i_peg_nip'];
		   $this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		   $this->view->gelar = $this->view->dataPokok ['n_peg_gelar'];
		   $this->view->karpeg = $this->view->dataPokok ['i_peg_karpeg'];
		   //$this->view->unitkerja = $this->view->dataPokok ['c_unit_kerja'];
		   $this->view->kdUnitkerja = $this->view->dataPokok ['i_orgb'];
		   //echo $this->view->dataPokok ['i_orgb'];
		   $this->view->nmUnitkerja = $this->view->dataPokok ['namaOrgb'];
		   $this->view->tglTMT = $this->view->dataPokok ['tglTMT'];
		   $this->view->jenisKelamin = $this->view->dataPokok ['c_peg_jeniskelamin'];
		   $this->view->tmpLahir = $this->view->dataPokok ['a_peg_lahir'];
		   $this->view->tglLahir = $this->view->dataPokok ['tglLahir'];
		   //$this->view->kdPendidikan = $this->view->dataPokok ['c_pend'];
		   //$this->view->nmPendidikan = $this->view->dataPokok ['namaPendidikan'];
		   $this->view->pendidikan = $this->view->dataPokok ['pendidikan'];
		   $this->view->kdAgama = $this->view->dataPokok ['c_agama'];
		   $this->view->nmAgama = $this->view->dataPokok ['namaAgama'];
		   $this->view->kdGol = $this->view->dataPokok ['c_peg_golongan'];
		   $this->view->nmGol = $this->view->dataPokok ['namaGol'];
		   $this->view->alamat = $this->view->dataPokok ['a_peg_rumah'];
		   $this->view->rt = $this->view->dataPokok ['a_peg_rt'];
		   $this->view->rw = $this->view->dataPokok ['a_peg_rw'];
		   $this->view->kelurahan = $this->view->dataPokok ['a_peg_kelurahan'];
		   $this->view->kecamatan = $this->view->dataPokok ['a_peg_kecamatan'];
		   $this->view->kabupaten = $this->view->dataPokok ['a_peg_kota'];
		   $this->view->propinsi = $this->view->dataPokok ['a_peg_propinsi'];
		   $this->view->kodePos = $this->view->dataPokok ['a_peg_kodepos'];
		   $this->view->teleponRumah = $this->view->dataPokok ['i_peg_telponrumah'];
		   $this->view->tlpGenggam = $this->view->dataPokok ['i_peg_telponhp'];
		   $this->view->stsNikah = $this->view->dataPokok ['c_peg_statusnikah'];
		   $this->view->kdStatusKerja = $this->view->dataPokok ['c_peg_status'];
		   $this->view->nmStatusKerja = $this->view->dataPokok ['namaStatus'];	   
		   $this->view->jenisIdentitas = $this->view->dataPokok ['c_peg_identitas'];
		   $this->view->nomerIdentitas = $this->view->dataPokok ['i_peg_identitas'];
		   $this->view->kewarganegaraan = $this->view->dataPokok ['n_peg_wn'];
		   $this->view->suku = $this->view->dataPokok ['n_peg_suku'];
		   $this->view->hobi = $this->view->dataPokok ['n_peg_hobi'];	   
		   $this->view->namaOrtu = $this->view->dataPokok ['n_ortu'];
		   $this->view->alamatOrtu = $this->view->dataPokok ['a_ortu_jalan'];
		   $this->view->rtOrtu = $this->view->dataPokok ['a_ortu_rt'];
		   $this->view->rwOrtu = $this->view->dataPokok ['a_ortu_rw'];
		   $this->view->kelurahanOrtu = $this->view->dataPokok ['a_ortu_kelurahan'];
		   $this->view->kecamatanOrtu = $this->view->dataPokok ['a_ortu_kecamatan'];
		   $this->view->kabupatenOrtu = $this->view->dataPokok ['a_ortu_kota'];
		   $this->view->propinsiOrtu = $this->view->dataPokok ['a_ortu_propinsi'];
		   $this->view->teleponRumahOrtu = $this->view->dataPokok ['i_ortu_telponrumah'];
		   $this->view->tlpGenggamOrtu = $this->view->dataPokok ['i_ortu_telponhp'];	 		
		
			$this->render('pegawaiupdate');	   
	   }
    }

    public function pelatihanviewAction() {
	   //echo "Masuk Controller pelatihanviewAction";
		   $nip = $_REQUEST['nip'];
		   $nmLatih = $_REQUEST['latih'];
		   $mulai = $_REQUEST['mulai'];   
		   
	   /*$this->view->latihList = $this->sdm_peg_serv->getPelatihanView($nip,$nmLatih,$mulai);
	   
	   $this->view->nip = $this->view->latihList ['i_peg_nip'];
	   $this->view->nmPelatihan = $this->view->latihList ['nmPelatihan'];
	   $this->view->mulai = $this->view->latihList ['mulai'];
	   $this->view->akhir = $this->view->latihList ['akhir'];
	   $this->view->tempat = $this->view->latihList ['tempat'];
	   //$this->view->dokumen = $this->view->pendList ['s_dokumen'];
	   $this->view->keterangan = $this->view->pendList ['keterangan'];*/
	   
		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
	   		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];

		$latih = $_REQUEST['latih'];
		$mulai = $_REQUEST['mulai'];
		//echo "latih= ".$latih."<br>"."mulai= ".$mulai."<br>";
	   $this->view->latihList = $this->sdm_peg_serv->getPelatihanView($nip,$latih,$mulai);	   
		$this->view->nip = $this->view->latihList ['i_peg_nip'];
		$this->view->nmPelatihan = $this->view->latihList ['nmPelatihan'];
		$this->view->mulai = $this->view->latihList ['mulai'];
		$this->view->akhir = $this->view->latihList ['akhir'];
		$this->view->tempat = $this->view->latihList ['tempat'];
		$this->view->jenisLatih = $this->view->latihList ['jenisLatih'];
		$this->view->noSertifikat = $this->view->latihList ['noSertifikat'];
		$this->view->penyelenggara = $this->view->latihList ['penyelenggara'];
		$this->view->tglSertifikat = $this->view->latihList ['tglSertifikat'];
		$this->view->keterangan = $this->view->latihList ['keterangan'];
	   
	   if ($_POST['perintah'] == 'KEMBALI'){
		    //$nip = $_SESSION['nip'];
		$nip = $_POST['nipH'];
		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   
		$this->view->latihList = $this->sdm_peg_serv->getPelatihan($nip);	
			   
		$this->render('pelatihan');	   
	   }
    }

//========================KELUARGA========================================	
    public function keluargaAction() {
	   //echo "Masuk Controller pegawaiupdateAction";
		   $nip = $_REQUEST['nip'];
		   $_SESSION['nip'] = $nip;
		//echo "nip".$nip;
		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   
		
		$this->view->kelList = $this->sdm_peg_serv->getKeluarga($nip);
		
	   if ($_POST['perintah'] == 'KEMBALI'){
	   
	   }
    }


    public function keluargaviewAction() {
		   $nip = $_REQUEST['nip'];
		   $_SESSION['nip'] = $nip;
		   
		   $nmHub = $_REQUEST['hub'];
		   $this->view->dataHub = $this->sdm_peg_serv->getKdHubKel($nmHub);
		   $hub = $this->view->dataHub['c_keluarga_hub'];
		//echo "hub->".$hub;
		
		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		//echo "nip".$nip;
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   
		$this->view->kelList = $this->sdm_peg_serv->getKeluarga1($nip,$hub);
	    $this->view->nama = $this->view->kelList ['nama'];
		$this->view->namaHub = $this->view->kelList ['namaHub'];
		$this->view->tempatLahir = $this->view->kelList ['tempatLahir'];
		$this->view->tglLahir = $this->view->kelList ['tglLahir'];
		$this->view->jenisKelamin = $this->view->kelList ['jenisKelamin'];
		$this->view->tglMenikah = $this->view->kelList ['tglMenikah'];
		$this->view->pekerjaan = $this->view->kelList ['pekerjaan'];
		$this->view->statusTanggungan = $this->view->kelList ['statusTanggungan'];
		$this->view->karis = $this->view->kelList ['karis'];
		$this->view->pendidikan = $this->view->kelList ['namaPendidikan'];
		$this->view->nipSuami = $this->view->kelList ['nipSuami'];
		$this->view->keterangan = $this->view->kelList ['keterangan'];	
		//echo "nip--> ".$this->view->kelList ['nipSuami'];
	}

 
//========================KETERANGAN========================================	
    public function keteranganAction() {
	   //echo "Masuk Controller keterangannAction";
		   $nip = $_REQUEST['nip'];
		   $_SESSION['nip'] = $nip;
		//echo "nip_ket".$nip;
		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   
		$this->view->dataKet = $this->sdm_peg_serv->getKeterangan($nip);	
		//$this->view->nip = $this->view->dataKet ['i_peg_nip'];
		$this->view->tinggi = $this->view->dataKet ['tinggi'];
		$this->view->berat = $this->view->dataKet ['berat'];
		$this->view->rambut = $this->view->dataKet ['rambut'];
		$this->view->bentukMuka = $this->view->dataKet ['bentukMuka'];
		$this->view->warnaKulit = $this->view->dataKet ['warnaKulit'];
		$this->view->ciriKhas = $this->view->dataKet ['ciriKhas'];
		$this->view->cacatTubuh = $this->view->dataKet ['cacatTubuh'];
		
		if ($_POST['perintah'] == 'SIMPAN'){
			$nip = $_POST['nipH'];
			$_SESSION['nip'] = $nip;
			$prmKet = array("nip"			=>$_POST['nipH'],
			                "tinggi"		=>$_POST['tinggi'],
			                "berat"			=>$_POST['berat'],							
							"rambut"		=>$_POST['rambut'],
							"bentukMuka"	=>$_POST['bentukMuka'],
							"warnaKulit"	=>$_POST['warnaKulit'],
							"ciriKhas"		=>$_POST['ciriKhas'],
							"cacatTubuh"	=>$_POST['cacatTubuh']);
		 $hasil = $this->sdm_peg_serv->updateKeterangan($prmKet);
			$nip = $_SESSION['nip'];
		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		//echo "nip2->".$nip;
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   
		$this->view->dataKet = $this->sdm_peg_serv->getKeterangan($nip);
		$this->view->nip = $this->view->dataKet ['i_peg_nip'];
		$this->view->tinggi = $this->view->dataKet ['tinggi'];
		$this->view->berat = $this->view->dataKet ['berat'];
		$this->view->rambut = $this->view->dataKet ['rambut'];
		$this->view->bentukMuka = $this->view->dataKet ['bentukMuka'];
		$this->view->warnaKulit = $this->view->dataKet ['warnaKulit'];
		$this->view->ciriKhas = $this->view->dataKet ['ciriKhas'];
		$this->view->cacatTubuh = $this->view->dataKet ['cacatTubuh'];
		$this->render('keterangan');		 

		}
	
		if ($_POST['perintah'] == 'HAPUS'){
		//echo "hapus";
			$nip = $_POST['nipH'];
			$_SESSION['nip'] = $nip;

			$prmKetHapus = array("nip"			=>$_REQUEST['nip']);
								  //echo "nip =".$nip;
								  //echo "kdjenjang =".$kdjenjang;
		 $hasil = $this->sdm_peg_serv->deleteKeterangan($prmKetHapus);
			$nip = $_SESSION['nip'];
		$this->view->dataKet = $this->sdm_peg_serv->getKeterangan($nip);	
		$this->view->nip = $this->view->dataKet ['i_peg_nip'];
		$this->view->tinggi = $this->view->dataKet ['tinggi'];
		$this->view->berat = $this->view->dataKet ['berat'];
		$this->view->rambut = $this->view->dataKet ['rambut'];
		$this->view->bentukMuka = $this->view->dataKet ['bentukMuka'];
		$this->view->warnaKulit = $this->view->dataKet ['warnaKulit'];
		$this->view->ciriKhas = $this->view->dataKet ['ciriKhas'];
		$this->view->cacatTubuh = $this->view->dataKet ['cacatTubuh'];
						
		}
	}

//========================ORANGTUA========================================	
    public function orangtuaAction() {
	   //echo "Masuk Controller keterangannAction";
		   $nip = $_REQUEST['nip'];
		   $_SESSION['nip'] = $nip;
		//echo "nip_ket".$nip;
	   $this->view->propinsiList = $this->sdm_peg_serv->getPropinsiListAll();
	   $this->view->kabupatenList = $this->sdm_peg_serv->getKabupatenListAll();

		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   
		
/* 	    $nmKabOrtu = $this->view->dataPokok ['a_ortu_kota'];	
	    $this->view->dataKabOrtu = $this->sdm_peg_serv->getKodeKabByNmKab($nmKabOrtu);
	    $kdKabOrtu = $this->view->dataKabOrtu ['c_kabupaten'];
	    $kabupatenOrtu = $kdKabOrtu."|".$nmKabOrtu;
	   
		$nmPropOrtu = $this->view->dataPokok ['a_ortu_propinsi'];	
		$this->view->dataPropOrtu = $this->sdm_peg_serv->getKodePropByNmProp($nmPropOrtu) ;
		$kdPropOrtu = $this->view->dataPropOrtu ['c_propinsi'];
		$propinsiOrtu = $kdPropOrtu."|".$nmPropOrtu;
 */		
		$this->view->namaOrtu = $this->view->dataPokok ['n_ortu'];
		$this->view->alamatOrtu = $this->view->dataPokok ['a_ortu_jalan'];
		$this->view->rtOrtu = $this->view->dataPokok ['a_ortu_rt'];
		$this->view->rwOrtu = $this->view->dataPokok ['a_ortu_rw'];
		$this->view->kelurahanOrtu = $this->view->dataPokok ['a_ortu_kelurahan'];
		$this->view->kecamatanOrtu = $this->view->dataPokok ['a_ortu_kecamatan'];
		$this->view->kabupatenOrtu = $this->view->dataPokok ['a_ortu_kota'];
		//$this->view->kabupatenOrtu = $kabupatenOrtu;
		$this->view->propinsiOrtu = $this->view->dataPokok ['a_ortu_propinsi'];
		//$this->view->propinsiOrtu = $propinsiOrtu;
		$this->view->teleponRumahOrtu = $this->view->dataPokok ['i_ortu_telponrumah'];
		$this->view->tlpGenggamOrtu = $this->view->dataPokok ['i_ortu_telponhp'];	  
			
	}
	
//========================KEPANGKATAN========================================	
    public function kepangkatanAction() {
	   //echo "Masuk Controller kepangkatanAction";
		   $nip = $_REQUEST['nip'];
		   $kdjenjang = $_REQUEST['jenjang'];
		   $_SESSION['nip'] = $nip;
		//echo "nip_pangkat= ".$nip."<br>";
		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   
		
		$this->view->pangkatList = $this->sdm_peg_serv->getKepangkatan($nip);	
		
		if ($_REQUEST['perintah'] == 'DELETE'){
		//echo "masuk deletekepangkatan";
			$prmPangkatDelete = array("nip"		=>$_REQUEST['nip'],
								  "gol"	=>$_REQUEST['gol'],
								  "tmt" =>$_REQUEST['tmt']);
		 $hasil = $this->sdm_peg_serv->deleteKepangkatan($prmPangkatDelete);
		 $this->view->pangkatList = $this->sdm_peg_serv->getKepangkatan($nip);	
		}
	}

    public function kepangkatanviewAction() {
	   //echo "Masuk Controller kepangkatanupdateAction";
		   $nip = $_REQUEST['nip'];
		   $_SESSION['nip'] = $nip;
		
		$gol = $_REQUEST['gol'];
		$tglTmt = $_REQUEST['tmt'];

		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   
		
		$this->view->dataGol = $this->sdm_peg_serv->getKepangkatanByGolTmt($nip,$gol,$tglTmt);	
		//echo "gol==> ".$this->view->dataGol['gol'];
		$this->view->nip = $this->view->dataGol ['i_peg_nip'];
		$this->view->gol = $this->view->dataGol ['gol'];
		$this->view->tmtGol = $this->view->dataGol ['tmtGol'];
		$this->view->gaji = $this->view->dataGol ['gaji'];
		$this->view->namaSK = $this->view->dataGol ['namaSK'];
		$this->view->nomorSK = $this->view->dataGol ['nomorSK'];
		$this->view->tglSK = $this->view->dataGol ['tglSK'];
		$this->view->jenis = $this->view->dataGol ['jenis'];
		$this->view->kerjaThn = $this->view->dataGol ['kerjaThn'];
		$this->view->kerjaBln = $this->view->dataGol ['kerjaBln'];
		$this->view->keterangan = $this->view->dataGol ['keterangan'];
		
	}
	

//========================ORGANISASI========================================	
    public function organisasiAction() {
	   //echo "Masuk Controller organisasiAction";
		   $nip = $_REQUEST['nip'];
		   $_SESSION['nip'] = $nip;
		//echo "nip".$nip;
		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   
		
		$this->view->orgList = $this->sdm_peg_serv->getOrganisasi($nip);	
		
		if ($_REQUEST['perintah'] == 'DELETE'){
		//echo "masuk deleteorganisasi controller";
			$prmOrgDelete = array("nip"		=>$_REQUEST['nip'],
								  "nmOrg"	=>$_REQUEST['nmOrg']);
		 $hasil = $this->sdm_peg_serv->deleteOrganisasi($prmOrgDelete);
		 $this->view->orgList = $this->sdm_peg_serv->getOrganisasi($nip);	
		}
	
	
	}

 
    public function organisasiviewAction() {
		$nip = $_REQUEST['nip'];
		$_SESSION['nip'] = $nip;
		   
		$nmOrg = $_REQUEST['nmOrg'];

		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   
		
		$this->view->dataOrg = $this->sdm_peg_serv->getOrganisasiByNmOrg($nip,$nmOrg);	
		$this->view->nip = $this->view->dataOrg ['i_peg_nip'];
		$this->view->nmOrg = $this->view->dataOrg ['nmOrg'];
		$this->view->jabatan = $this->view->dataOrg ['jabatan'];
		$this->view->mulai = $this->view->dataOrg ['mulai'];
		$this->view->akhir = $this->view->dataOrg ['akhir'];
		$this->view->tempat = $this->view->dataOrg ['tempat'];
		$this->view->pimpinan = $this->view->dataOrg ['pimpinan'];
		$this->view->keterangan = $this->view->dataOrg ['keterangan'];
	}

//========================SERTIFIKASI========================================	
    public function sertifikasiAction() {
	   //echo "Masuk Controller sertifikasiAction";
		   $nip = $_REQUEST['nip'];
		   $_SESSION['nip'] = $nip;
		//echo "nip".$nip;
		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   
		
		$this->view->serList = $this->sdm_peg_serv->getSertifikasi($nip);			
	}

    public function sertifikasiviewAction() {
	   //echo "Masuk Controller sertifikasiupdateAction";
		   $nip = $_REQUEST['nip'];
		   $_SESSION['nip'] = $nip;
		//echo "nip".$nip;
		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   
		
		$nmSer = $_REQUEST['nmSer'];
		
		$this->view->dataSer = $this->sdm_peg_serv->getSertifikasiByKey($nip,$nmSer);	
		$this->view->nip = $this->view->dataSer ['i_peg_nip'];
		$this->view->nmSer = $this->view->dataSer ['nmSer'];
		$this->view->noSer = $this->view->dataSer ['noSer'];
		$this->view->tglSer = $this->view->dataSer ['tglSer'];
		$this->view->lembaga = $this->view->dataSer ['lembaga'];
		$this->view->mulai = $this->view->dataSer ['mulai'];
		$this->view->akhir = $this->view->dataSer ['akhir'];
		$this->view->keterangan = $this->view->dataSer ['keterangan'];
	}
	
//========================SEMINAR========================================	
    public function seminarAction() {
	   //echo "Masuk Controller seminarAction";
		   $nip = $_REQUEST['nip'];
		   $_SESSION['nip'] = $nip;
		//echo "nip".$nip;
		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   
		
		$this->view->semList = $this->sdm_peg_serv->getSeminar($nip);	
		
		if ($_REQUEST['perintah'] == 'DELETE'){
			$prmSemDelete = array("nip"		=>$_REQUEST['nip'],
								  "nmSem"	=>$_REQUEST['nmSem']);
		 $hasil = $this->sdm_peg_serv->deleteSeminar($prmSemDelete);
		 $this->view->semList = $this->sdm_peg_serv->getSeminar($nip);	
		}	
	}

    public function seminarviewAction() {
		$nip = $_REQUEST['nip'];
		$_SESSION['nip'] = $nip;
		   
		$nmSem = $_REQUEST['nmSem'];

		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   
		
		$this->view->dataSem = $this->sdm_peg_serv->getSeminarByKey($nip,$nmSem);	
		$this->view->nip = $this->view->dataSem ['i_peg_nip'];
		$this->view->nmSem = $this->view->dataSem ['nmSem'];
		$this->view->peran = $this->view->dataSem ['peran'];
		$this->view->mulai = $this->view->dataSem ['mulai'];
		$this->view->akhir = $this->view->dataSem ['akhir'];
		$this->view->penyelenggara = $this->view->dataSem ['penyelenggara'];
		$this->view->tempat = $this->view->dataSem ['tempat'];
		$this->view->keterangan = $this->view->dataSem ['keterangan'];
	}

//
	
//========================PENGHARGAAN========================================	
    public function penghargaanAction() {
	   //echo "Masuk Controller seminarAction";
		   $nip = $_REQUEST['nip'];
		   $_SESSION['nip'] = $nip;
		//echo "nip".$nip;
		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   
		
		$this->view->hargaList = $this->sdm_peg_serv->getPenghargaan($nip);	
		
		if ($_REQUEST['perintah'] == 'DELETE'){
			$prmHargaDelete = array("nip"		=>$_REQUEST['nip'],
									"nmHarga"	=>$_REQUEST['nmHarga']);
		 $hasil = $this->sdm_peg_serv->deletePenghargaan($prmHargaDelete);
		 $this->view->hargaList = $this->sdm_peg_serv->getPenghargaan($nip);	
		}		
	}

    public function penghargaanviewAction() {
		$nip = $_REQUEST['nip'];
		$_SESSION['nip'] = $nip;
		   
		$nmHarga = $_REQUEST['nmHarga'];

		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   
		
		$this->view->dataHarga = $this->sdm_peg_serv->getPenghargaanByKey($nip,$nmHarga);	
		$this->view->nip = $this->view->dataHarga ['i_peg_nip'];
		$this->view->nmHarga = $this->view->dataHarga ['nmHarga'];
		$this->view->tahun = $this->view->dataHarga ['tahun'];
		$this->view->lembaga = $this->view->dataHarga ['lembaga'];
		$this->view->noSurat = $this->view->dataHarga ['noSurat'];
		$this->view->tglSurat = $this->view->dataHarga ['tglSurat'];
		$this->view->keterangan = $this->view->dataHarga ['keterangan'];
	}	
 	
//========================JABATAN========================================	
    public function jabatanAction() {
	   //echo "Masuk Controller kepangkatanAction";
		   $nip = $_REQUEST['nip'];
		   $kdjenjang = $_REQUEST['jenjang'];
		   $_SESSION['nip'] = $nip;
		//echo "nip".$nip;
		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   
		
		$this->view->jabatList = $this->sdm_peg_serv->getJabatan($nip);	
		
	}
	
    public function jabatanviewAction() {
		$nip = $_REQUEST['nip'];
		$_SESSION['nip'] = $nip;
		   
		$nmJabat = $_REQUEST['nmJabat'];

		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   
		
		$this->view->dataJabat = $this->sdm_peg_serv->getJabatanByKey($nip,$nmJabat);	
		$this->view->nip = $this->view->dataJabat ['i_peg_nip'];
		$this->view->nmJabat = $this->view->dataJabat ['nmJabat'];
		$this->view->mulai = $this->view->dataJabat ['mulai'];
		$this->view->akhir = $this->view->dataJabat ['akhir'];
		$this->view->gol = $this->view->dataJabat ['gol'];
		$this->view->gaji = $this->view->dataJabat ['gaji'];
		$this->view->nmSK = $this->view->dataJabat ['nmSK'];
		$this->view->noSK = $this->view->dataJabat ['noSK'];
		$this->view->tglSK = $this->view->dataJabat ['tglSK'];
		$this->view->eselon = $this->view->dataJabat ['eselon'];
		$this->view->eselon1 = $this->view->dataJabat ['eselon1'];
		$this->view->eselon2 = $this->view->dataJabat ['eselon2'];
		$this->view->eselon3 = $this->view->dataJabat ['eselon3'];
		$this->view->eselon4 = $this->view->dataJabat ['eselon4'];
		$this->view->noLantik = $this->view->dataJabat ['noLantik'];
		$this->view->tglLantik = $this->view->dataJabat ['tglLantik'];
		$this->view->keterangan = $this->view->dataJabat ['keterangan'];
	}	
 
//========================HUKUMAN========================================	
    public function hukumanAction() {
	   //echo "Masuk Controller hukumanAction";
		   $nip = $_REQUEST['nip'];
		   $_SESSION['nip'] = $nip;
		//echo "nip".$nip;
		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   
		
		$this->view->hukumList = $this->sdm_peg_serv->getHukuman($nip);	
	}

    public function hukumanviewAction() {
		$nip = $_REQUEST['nip'];
		$_SESSION['nip'] = $nip;
		   
		$tanggal = $_REQUEST['tanggal'];

		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   
		
		$this->view->dataHukum = $this->sdm_peg_serv->getHukumanByKey($nip,$tanggal);	
		$this->view->nip = $this->view->dataHukum ['i_peg_nip'];
		$this->view->sp = $this->view->dataHukum ['sp'];
		$this->view->jenisSp = $this->view->dataHukum ['jenisSp'];
		$this->view->tanggal = $this->view->dataHukum ['tanggal'];
		$this->view->dari = $this->view->dataHukum ['dari'];
		$this->view->kasus = $this->view->dataHukum ['kasus'];
		$this->view->tkSanksi = $this->view->dataHukum ['tkSanksi'];
		$this->view->keterangan = $this->view->dataHukum ['keterangan'];
//echo "keterangan= ".$this->view->keterangan."<br>";
	}	
  
//========================LUAR NEGERI========================================	
    public function luarnegeriAction() {
	   //echo "Masuk Controller luarnegeriAction";
		   $nip = $_REQUEST['nip'];
		   $_SESSION['nip'] = $nip;
		//echo "nip".$nip;
		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   
		
		$this->view->lnList = $this->sdm_peg_serv->getLuarNegeri($nip);	
		
		if ($_REQUEST['perintah'] == 'DELETE'){
			$negara = $_REQUEST['negara'];

			$prmHukumDelete = array("nip"		=>$_REQUEST['nip'],
								  "negara"		=>$negara);
		 $hasil = $this->sdm_peg_serv->deleteLuarNegeri($prmHukumDelete);
		 $this->view->lnList = $this->sdm_peg_serv->getLuarNegeri($nip);	
		}
	}
	
    public function luarnegeriviewAction() {
		$nip = $_REQUEST['nip'];
		$_SESSION['nip'] = $nip;
		   
		$negara = $_REQUEST['negara'];
		$tanggal = $_REQUEST['tanggal'];

		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   
		
		$this->view->dataLN = $this->sdm_peg_serv->getLuarNegeriByKey($nip,$negara,$tanggal);	
		//$this->view->dataLN = $this->sdm_peg_serv->getLuarNegeriByKey($nip,$negara);	
		$this->view->nip = $this->view->dataLN ['i_peg_nip'];
		$this->view->negara = $this->view->dataLN ['negara'];
		$this->view->tanggal= $this->view->dataLN ['tanggal'];
		$this->view->tujuan = $this->view->dataLN ['tujuan'];
		$this->view->lama = $this->view->dataLN ['lama'];
		$this->view->biaya = $this->view->dataLN ['biaya'];
		$this->view->keterangan = $this->view->dataLN ['keterangan'];
	}	

//===========================KERABAT===========================================	
    public function kerabatAction() {
	   //echo "Masuk Controller pegawaiupdateAction";
		   $nip = $_REQUEST['nip'];
		   $_SESSION['nip'] = $nip;
		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   
		
		$this->view->kerList = $this->sdm_peg_serv->getKerabat($nip);

	}
	
    public function kerabatviewAction() {
	   $this->view->nmKelList = $this->sdm_peg_serv->getListKerabat();
	   $this->view->propinsiList = $this->sdm_peg_serv->getPropinsiListAll();
	   $this->view->kabupatenList = $this->sdm_peg_serv->getKabupatenListAll();
	   $nip = $_REQUEST['nip'];	   
	   	$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);	   	   
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   

		$nmHub = $_REQUEST['hub'];
		//echo "nmHub= ".$nmHub."<br>";
		$this->view->dataHub = $this->sdm_peg_serv->getKdHubKer($nmHub);
		$hub = $this->view->dataHub['c_keluarga_hub'];
		//echo "hub= ".$hub."<br>";

		$this->view->kerList = $this->sdm_peg_serv->getKerabatByKey($nip,$hub);
	    $this->view->nama = $this->view->kerList ['nama'];
		$this->view->kdHub = $this->view->kerList ['kdHub'];
		$this->view->namaHub = $this->view->kerList ['namaHub'];
		$this->view->jenisKelamin = $this->view->kerList ['jenisKelamin'];
		$this->view->pekerjaan = $this->view->kerList ['pekerjaan'];
		$this->view->jalan = $this->view->kerList ['a_jalan'];
		$this->view->rt = $this->view->kerList ['a_rt'];
		$this->view->rw = $this->view->kerList ['a_rw'];
		$this->view->kel = $this->view->kerList ['a_kel'];
		$this->view->kec = $this->view->kerList ['a_kec'];
		$this->view->kota = $this->view->kerList ['a_kota'];
		$this->view->prop = $this->view->kerList ['a_prop'];
		$this->view->telp = $this->view->kerList ['telp'];
		$this->view->hp = $this->view->kerList ['hp'];
		$this->view->email = $this->view->kerList ['email'];
		$this->view->keterangan = $this->view->kerList ['keterangan'];	
//================= 
	    $nmKabOrtu = $this->view->kerList ['a_kota'];	
	    $this->view->dataKabOrtu = $this->sdm_peg_serv->getKodeKabByNmKab($nmKabOrtu);
	    $kdKabOrtu = $this->view->dataKabOrtu ['c_kabupaten'];
	    $kabupatenOrtu = $kdKabOrtu."|".$nmKabOrtu;
	   
		$nmPropOrtu = $this->view->kerList ['a_prop'];	
		$this->view->dataPropOrtu = $this->sdm_peg_serv->getKodePropByNmProp($nmPropOrtu) ;
		$kdPropOrtu = $this->view->dataPropOrtu ['c_propinsi'];
		$propinsiOrtu = $kdPropOrtu."|".$nmPropOrtu;
		
		$this->view->kabupatenOrtu = $kabupatenOrtu;
		$this->view->propinsiOrtu = $propinsiOrtu;
	}
	
//========================KESEHATAN========================================	
    public function kesehatanAction() {
	   //echo "Masuk Controller kesehatanAction";
		   $nip = $_REQUEST['nip'];
		   $_SESSION['nip'] = $nip;
		//echo "nip".$nip;
		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   
		
		$this->view->sehatList = $this->sdm_peg_serv->getKesehatan($nip);			
	}
	
    public function kesehatanviewAction() {
	   //echo "Masuk Controller kesehatanviewAction";
		$nip = $_REQUEST['nip'];
		$_SESSION['nip'] = $nip;
		   
		$tanggal = $_REQUEST['tanggal'];

		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];
	   
		
		$this->view->dataSehat = $this->sdm_peg_serv->getKesehatanByKey($nip,$tanggal);	
		$this->view->nip = $this->view->dataSehat ['i_peg_nip'];
		$this->view->namaPenyakit = $this->view->dataSehat ['namaPenyakit'];
		$this->view->tglSakit = $this->view->dataSehat ['tglSakit'];
		$this->view->tglSembuh = $this->view->dataSehat ['tglSembuh'];
		$this->view->namaRS = $this->view->dataSehat ['namaRS'];
		$this->view->alamatRS = $this->view->dataSehat ['alamatRS'];
		$this->view->keterangan = $this->view->dataSehat ['keterangan'];
	}	

//=============================ALAMAT===================================
    public function alamatAction() {
	   //echo "Masuk Controller pegawaiupdateAction";
		   $nip = $_REQUEST['nip'];
		   $_SESSION['nip'] = $nip;
		//echo "nip".$nip;
		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		$this->view->kdGol = $this->view->dataPokok ['c_peg_golongan'];
		$this->view->nmGol = $this->view->dataPokok ['namaGol'];
		
	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];

	   $this->view->alamatList = $this->sdm_peg_serv->getAlamat($nip);

    }
	
    public function alamatviewAction() {
	//echo "masuk alamatupdateAction";
		$user = $this->user;
	   $this->view->propinsiList = $this->sdm_peg_serv->getPropinsiListAll();
	   $this->view->kabupatenList = $this->sdm_peg_serv->getKabupatenListAll();
	   $nip = $_REQUEST['nip'];	   
	   	$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);	   	   
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaJab = $this->view->dataPokok ['namaJab'];
		$this->view->kdGol = $this->view->dataPokok ['c_peg_golongan'];
		$this->view->nmGol = $this->view->dataPokok ['namaGol'];

	   $this->view->dataGolMax = $this->sdm_peg_serv->getMaxGol($nip);	 
	   $this->view->kGol = $this->view->dataGolMax ['kGol'];
	   $this->view->nGol = $this->view->dataGolMax ['nGol'];

	   $tglMulai = $_REQUEST['tanggal'];
	   $jalan = $_REQUEST['jalan'];

		$this->view->dataAlamat = $this->sdm_peg_serv->getAlamatByKey($nip,$tglMulai,$jalan);
	    $this->view->tglMulai = $this->view->dataAlamat ['tglMulai'];
	    //$this->view->tglMulai = $tglMulai;
		$this->view->tglAkhir = $this->view->dataAlamat ['tglAkhir'];
		$this->view->jalan = $this->view->dataAlamat ['jalan'];
		//$this->view->jalan = $jalan;
		$this->view->rt = $this->view->dataAlamat ['rt'];
		$this->view->rw = $this->view->dataAlamat ['rw'];
		$this->view->kel = $this->view->dataAlamat ['kel'];
		$this->view->kec = $this->view->dataAlamat ['kec'];
		$this->view->kodePos = $this->view->dataAlamat ['kodePos'];
		$this->view->telpRumah = $this->view->dataAlamat ['telpRumah'];
		$this->view->keterangan = $this->view->dataAlamat ['keterangan'];	
//================= 
	    $nmKota = $this->view->dataAlamat ['kota'];	
	    $this->view->dataKota = $this->sdm_peg_serv->getKodeKabByNmKab($nmKota);
	    $kdKota = $this->view->dataKota ['c_kabupaten'];
	    $kota = $kdKota."|".$nmKota;
	   
		$nmProp = $this->view->dataAlamat ['prop'];	
		$this->view->dataProp = $this->sdm_peg_serv->getKodePropByNmProp($nmProp) ;
		$kdProp = $this->view->dataProp ['c_propinsi'];
		$prop = $kdProp."|".$nmProp;
		
		$this->view->kota = $kota;
		$this->view->nmKota = $nmKota;
		$this->view->prop = $prop;
		$this->view->nmProp = $nmProp;

	}
	
     public function fotoAction() {
     //$nip = '930000';
	 $nip = $this->_getParam(nip);
	 //$bPath = $this->basePath;
	 //$nfile = $bPath.'/etc/data/sdm/foto/'.$nip.'.jpg';
	 $nfile = "/oa-zend/etc/data/sdm/foto/" .$nip.'.jpg';
	 $file = file_get_contents($nfile);
	 header('Content-Type : image/jpeg');
	 //echo $file;
	 $this->_helper->viewRenderer->setNoRender(true);
	 
	 
	 }
}
?>