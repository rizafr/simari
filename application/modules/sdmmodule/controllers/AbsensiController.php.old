<?php
require_once 'Zend/Controller/Action.php';
require_once "service/sdm/Sdm_Absensi_Service.php";
require_once "service/sdm/Sdm_Pegawai_Service.php";
/*
require_once "service/sdm/Sdm_Cv_Service.php";
require_once "service/sdm/Sdm_Cuti_Service.php";
*/
require_once 'Zend/Auth.php';
   

class Sdmmodule_AbsensiController extends Zend_Controller_Action {
	private $cv_serv;
	private $pegawai_serv;
	
    public function init() {
		$this->_helper->layout->setLayout('target-column');
 	   $registry = Zend_Registry::getInstance();
	   $this->view->dPath = $registry->get('baseData');
	   $this->view->basePath = $registry->get('basepath'); 

	   $this->sdm_peg_serv = Sdm_Pegawai_Service::getInstance();
	   $this->sdm_absen_serv = Sdm_Absensi_Service::getInstance();
	   $this->view->sdm_absen_serv = Sdm_Absensi_Service::getInstance();
	   
    }
  
    public function indexAction() {
	   $thnHrKrj = $_REQUEST['thnHariKerja'];
	   $blnHrKrj = $_REQUEST['blnHariKerja'];
	   if ((!$thnHrKrj) || ($thnHrKrj == 'undefined')) { $this->view->thnSekarang = date('Y'); }
	   else { $this->view->thnSekarang = $thnHrKrj; }
	   if ((!$blnHrKrj) || ($blnHrKrj == 'undefined')) { $this->view->blnSekarang = date('m'); }
	   else { $this->view->blnSekarang = $blnHrKrj; }
    }
//========================DATA SEARCH========================================	

    public function absensisearchAction() {
	}
	
    public function dataabsensiAction() {
	}	
	
	public function hapusabsenAction() {	
		   $nip = $_REQUEST['nip'];
		
		   $_SESSION['nip'] = $nip;
		   $tglAbsen = $_REQUEST['tglAbsen'];
				$thnAbsen = substr($tglAbsen,0,4);
				$blnAbsen = substr($tglAbsen,5,2);
				$hrAbsen = substr($tglAbsen,8,2);
				$tanggalAbsen = $hrAbsen."-".$blnAbsen."-".$thnAbsen;
		$prmAbsenDelete = array("nip"		=>$_REQUEST['nip'],
								 "tglAbsen"	=>$_REQUEST['tglAbsen']);
		$hasil = $this->sdm_absen_serv->deleteAbsensi($prmAbsenDelete);
		//echo "hasil= ".$hasil;
		if($hasil == 'sukses')
		{
			$this->view->pesan = "Hapus Absensi Tanggal $tanggalAbsen Berhasil";
		}
		else
		{
			$this->view->pesan = "Hapus Absensi Tanggal $tanggalAbsen Gagal";
		}
		
		$this->view->absenList = $this->sdm_absen_serv->getAbsensi($nip);		
		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaUnitKerja = $this->view->dataPokok ['namaUnitKerja'];
		$this->view->absenList = $this->sdm_absen_serv->getAbsensi($nip);		
		
		$this->render('dataabsensi');
	} 
		
	public function catatabsensiAction() {
	   //echo "Masuk Controller pegawaiupdateAction";
		$user = $this->user;
		//echo "user= ".$user;
	   $nip = $_REQUEST['nip'];	  
	   
		$this->view->absenList = $this->sdm_absen_serv->getKodeAbsenList();

	   	$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);	   	   
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaUnitKerja = $this->view->dataPokok ['namaUnitKerja'];
		
		if ($_POST['perintah'] == 'SIMPAN'){
			//echo "nipH =".$_POST['nipH'];
			$hrAbsen = $_POST['hrAbsen'];
			$blnAbsen = $_POST['blnAbsen'];
			$thnAbsen = $_POST['thnAbsen'];
			//echo "mulai= ".$mulai;
			if ($thnAbsen == ' ') {
				$tanggalAbsen = null;
			}else if ($blnAbsen == '#') {
				$tanggalAbsen = null;
			}else if ($hrAbsen == '#') {
				$tanggalAbsen = null;
			} else {
				$tanggalAbsen = $thnAbsen."-".$blnAbsen."-".$hrAbsen;
			}

			$jamMsk = $_POST['jamMsk'];
			$menitMsk = $_POST['menitMsk'];
			$detikMsk = $_POST['detikMsk'];
			$waktuMsk = $jamMsk.":".$menitMsk.":".$detikMsk;
			
			$jamKel = $_POST['jamKel'];
			$menitKel = $_POST['menitKel'];
			$detikKel = $_POST['detikKel'];
			$waktuKel = $jamKel.":".$menitKel.":".$detikKel;
/* 			echo "waktuMasuk= ".$waktuMasuk."<br>";
			echo "waktuKel= ".$waktuKel."<br>";
 */			$nip = $_POST['nipH'];
			$_SESSION['nip'] = $nip;
			
			$prmAbsenInsert = array("nip"			=>$_POST['nipH'],
									"tanggalAbsen"	=>$tanggalAbsen,
									"jenisAbsen"	=>$_POST['kdAbsen'],
									"jamMasuk"		=>$waktuMsk,
									"jamKeluar"		=>$waktuKel,
									"keterangan"	=>$_POST['keterangan'],
									"user"			=>$user);
			if ($tanggalAbsen == null) {
				?>
			     <script type="text/javascript">	
			       alert("Tanggal Absen harus diisi");
				 </script>
				 <?
				$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
				$this->view->nip = $this->view->dataPokok ['i_peg_nip'];
				$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
				$this->view->namaUnitKerja = $this->view->dataPokok ['namaUnitKerja'];
			}
			else {

				$hasil = $this->sdm_absen_serv->insertAbsensi($prmAbsenInsert);
					$nip = $_SESSION['nip'];
					//echo "nip =".$nip;
				$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
				$this->view->nip = $this->view->dataPokok ['i_peg_nip'];
				$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
				$this->view->namaUnitKerja = $this->view->dataPokok ['namaUnitKerja'];
				
				$this->view->absenList = $this->sdm_absen_serv->getAbsensi($nip);
				if ($hasil == 'sukses') {
					$this->view->pesan = 'Simpan Data Absensi Berhasil';
				}
				else {
					$this->view->pesan = 'Simpan Data Absensi Gagal';				
				}
				$this->render('dataabsensi');	
			}
		}
		

	    if ($_POST['perintah'] == 'KEMBALI'){
			$nip = $_POST['nipH'];
			//echo "nip->".$nip;
		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaUnitKerja = $this->view->dataPokok ['namaUnitKerja'];
		
		$this->view->absenList = $this->sdm_absen_serv->getAbsensi($nip);
		$this->render('dataabsensi');
		} 
	}

	
    public function updateabsensiAction() {
		$user = $this->user;
	   $nip = $_REQUEST['nip'];	   
		$this->view->absenList = $this->sdm_absen_serv->getKodeAbsenList();

	   	$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);	   	   
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaUnitKerja = $this->view->dataPokok ['namaUnitKerja'];

		$tglAbsen = $_REQUEST['tglAbsen'];

		$this->view->dataAbsen = $this->sdm_absen_serv->getAbsensiByKey($nip,$tglAbsen);
	    $this->view->tglAbsen = $this->view->dataAbsen ['tglAbsen'];
		$this->view->jenisAbsen = $this->view->dataAbsen ['jenisAbsen'];
		$this->view->jamMasuk = $this->view->dataAbsen ['jamMasuk'];
		$this->view->jamKeluar = $this->view->dataAbsen ['jamKeluar'];
		$this->view->keterangan = $this->view->dataAbsen ['keterangan'];	
 
		if ($_POST['perintah'] == 'SIMPAN'){
			//echo "nipH =".$_POST['nipH'];
			$hrAbsen = $_POST['hrAbsen'];
			$blnAbsen = $_POST['blnAbsen'];
			$thnAbsen = $_POST['thnAbsen'];
			//echo "mulai= ".$mulai;
			if ($thnAbsen == ' ') {
				$tanggalAbsen = null;
			}else if ($blnAbsen == '#') {
				$tanggalAbsen = null;
			}else if ($hrAbsen == '#') {
				$tanggalAbsen = null;
			} else {
				$tanggalAbsen = $thnAbsen."-".$blnAbsen."-".$hrAbsen;
			}

			$jamMsk = $_POST['jamMsk'];
			$menitMsk = $_POST['menitMsk'];
			$detikMsk = $_POST['detikMsk'];
			$waktuMsk = $jamMsk.":".$menitMsk.":".$detikMsk;
			
			$jamKel = $_POST['jamKel'];
			$menitKel = $_POST['menitKel'];
			$detikKel = $_POST['detikKel'];
			$waktuKel = $jamKel.":".$menitKel.":".$detikKel;
/* 			echo "waktuMasuk= ".$waktuMasuk."<br>";
			echo "waktuKel= ".$waktuKel."<br>";
 */			$nip = $_POST['nipH'];
			$_SESSION['nip'] = $nip;
			
			$prmAbsenUpdate = array("nip"			=>$_POST['nipH'],
									"tglAbsenH"		=>$_POST['tglAbsenH'],
									"tanggalAbsen"	=>$tanggalAbsen,
									"jenisAbsen"	=>$_POST['kdAbsen'],
									"jamMasuk"		=>$waktuMsk,
									"jamKeluar"		=>$waktuKel,
									"keterangan"	=>$_POST['keterangan'],
									"user"			=>$user);
			if ($tanggalAbsen == null) {
				?>
			     <script type="text/javascript">	
			       alert("Tanggal Absen harus diisi");
				 </script>
				 <?
				$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
				$this->view->nip = $this->view->dataPokok ['i_peg_nip'];
				$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
				$this->view->namaUnitKerja = $this->view->dataPokok ['namaUnitKerja'];
			}
			else {
				$hasil = $this->sdm_absen_serv->updateAbsensi($prmAbsenUpdate);
					$nip = $_SESSION['nip'];
					//echo "nip =".$nip;
				$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
				$this->view->nip = $this->view->dataPokok ['i_peg_nip'];
				$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
				$this->view->namaUnitKerja = $this->view->dataPokok ['namaUnitKerja'];
				
				$this->view->absenList = $this->sdm_absen_serv->getAbsensi($nip);
				if ($hasil == 'sukses') {
					$this->view->pesan = 'Ubah Data Absensi Berhasil';
				}
				else {
					$this->view->pesan = 'Ubah Data Absensi Gagal';				
				}
				$this->render('dataabsensi');	
			}
		}
		
	}
	
//Melihat Absensi
   public function viewabsensisearchAction() {
	   //echo "Masuk Controller viewabsensisearchAction   ";
		$nip = $_POST['nip'];
		if ($nip == null) {
			$nip = $_REQUEST['param1'];	 
		}
		if ($nip == null) {
			$nip = '';
		}
		
		$nama= $_POST['nama'];
		if ($nama == null) {
			$nama = $_REQUEST['param2'];	 
		}
	   if ($nama == null) {
			$nama = '';
		}

	   //$this->view->cvList = $this->sdm_cv_serv->getPegawaiListAll($nip,$nama);
		$usernip = $this->usernip;
//echo "user= ".$usernip;
		$this->view->dataUserOrg = $this->sdm_peg_serv->getUserOrg($usernip);	 
		$this->view->nip = $this->view->dataUserOrg ['nip'];
		$this->view->userOrg = $this->view->dataUserOrg ['userOrg'];
		$this->view->level = $this->view->dataUserOrg ['level'];

	   $this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($usernip);	 
		$this->view->eselon = $this->view->dataPokok ['eselon'];
		
		$userLevel = $this->view->level;
		$userOrg = $this->view->userOrg;
		$eselon = $this->view->eselon;
		
/* 		if ($eselon == 'NE') {
			$org = 'NE';
		}		
		else if ($userLevel == '1') {
			$org = 'MN';
		}
		else if ($userLevel == '2') {
			$org = substr($userOrg,0,3);
		}
		else if ($userLevel == '3') {
			$org = substr($userOrg,0,4);
		}
		else if ($userLevel == '4') {
			$org = substr($userOrg,0,5);
		}
		else if ($userLevel == '5') {
			$org = substr($userOrg,0,6);
		}
		
		else {
			$org = 'NE';
		}
		
 */
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
/* 		$this->view->totPegawai = $this->sdm_cv_serv->getPegawaiList($nip, $nama, 0, 0);
		$this->view->cvList = $this->sdm_cv_serv->getPegawaiList($nip, $nama, $currentPage, $numToDisplay );
 */		
			$this->view->totPegawai = $this->sdm_cv_serv->getPegawaiListByUser($nip, $nama, $org, $usernip, 0, 0);
			$this->view->cvList = $this->sdm_cv_serv->getPegawaiListByUser($nip, $nama, $org, $usernip, $currentPage, $numToDisplay );


		if ($_POST['perintah'] == 'CARI'){
	    //echo "masuk cari";
			$nip = $_POST['nip'];
			if ($nip == null) {
				$nip = $_REQUEST['param1'];	 
			}
			if ($nip == null) {
				$nip = '';
			}
			
			$nama= $_POST['nama'];
			if ($nama == null) {
				$nama = $_REQUEST['param2'];	 
			}
		   if ($nama == null) {
				$nama = '';
			}
			//$this->view->cvList = $this->sdm_cv_serv->getPegawaiListAll($nip,$nama);	
			
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
			
			if((!$currentPage) || ($currentPage == 'undefined'))
			{
				$currentPage = 1;
			}
			
			$numToDisplay = 20;
			$this->view->numToDisplay = $numToDisplay;
			$this->view->currentPage = $currentPage;
/* 			$this->view->totPegawai = $this->sdm_cv_serv->getPegawaiList($nip, $nama, 0, 0);
			$this->view->cvList = $this->sdm_cv_serv->getPegawaiList($nip, $nama, $currentPage, $numToDisplay );
 */			
			$this->view->totPegawai = $this->sdm_cv_serv->getPegawaiListByUser($nip, $nama, $org, $usernip, 0, 0);
			$this->view->cvList = $this->sdm_cv_serv->getPegawaiListByUser($nip, $nama, $org, $usernip, $currentPage, $numToDisplay );
		}
    }
	
	
    public function viewdataabsensiAction() {
	   //echo "Masuk Controller viewdataabsensiAction";
		   $nip = $_REQUEST['nip'];
		
		   $_SESSION['nip'] = $nip;
		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaUnitKerja = $this->view->dataPokok ['namaUnitKerja'];
		
		$this->view->absenList = $this->sdm_absen_serv->getAbsensi($nip);		   

/* 		if ($_REQUEST['perintah'] == 'DELETE'){
			$prmAbsenDelete = array("nip"		=>$_REQUEST['nip'],
								  "tglAbsen"	=>$_REQUEST['tglAbsen']);
		 $hasil = $this->sdm_absen_serv->deleteAbsensi($prmAbsenDelete);
		 $this->view->absenList = $this->sdm_absen_serv->getAbsensi($nip);		
		}
 */
	   if ($_POST['perintah'] == 'CARI'){
	    //echo "masuk cari";
		$nip = $_POST['nipH'];
		if ($nip == null) {
			$nip = '';
		}
		
			$hr1 = $_POST['hr1'];
			$bln1 = $_POST['bln1'];
			$thn1 = $_POST['thn1'];
			$tgl1 = $thn1."-".$bln1."-".$hr1;
			if ($hr1 == '#') {
				$tgl1 = null;
			}
			else if ($bln1 == '#') {
				$tgl1 = null;
			}
			else if ($thn1 == '') {
				$tgl1 =null;
			}
		   
			$hr2 = $_POST['hr2'];
			$bln2 = $_POST['bln2'];
			$thn2 = $_POST['thn2'];
			$tgl2 = $thn2."-".$bln2."-".$hr2;
			if ($hr2 == '#') {
				$tgl2 = null;
			}
			else if ($bln2 == '#') {
				$tgl2 = null;
			}
			else if ($thn2 == '') {
				$tgl2= null;
			}
			
			if (($tgl1 == null) and ($tgl2 == null)) {
				$tgl1 = '01-01-1900';
				$tgl2 = '01-01-2100';				
			}
		//echo "nip= ".$nip."<br>"."tgl1= ".$tgl1."<br>"."tgl2= ".$tgl2."<br>";
		
			$this->view->absenList = $this->sdm_absen_serv->getAbsensiByPeriode($nip,$tgl1,$tgl2);	   	

		$this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($nip);
		$this->view->nip = $this->view->dataPokok ['i_peg_nip'];		
		$this->view->namaPegawai = $this->view->dataPokok ['n_peg'];
		$this->view->namaUnitKerja = $this->view->dataPokok ['namaUnitKerja'];

		}
		
	}
//Author Bambang Riswanto	
	
   public function listpegawaiAction() 
   {
      $paging = $_REQUEST['paging'];
      $jumpPage = $_REQUEST['jumpPage'];
      $iPegNip = $_POST['i_peg_nip'];
      $nPeg = $_POST['n_peg'];	
      
      if(!$nPeg) $nPeg = $_REQUEST['param2'];
      if(!$iPegNip)  $iPegNip = $_REQUEST['param1'];
      if($iPegNip == 'undefined') $iPegNip = '';
      if($nPeg == 'undefined')  $nPeg = '';	
	
      $currentPage = $_REQUEST['currentPage']; 
      if((!$currentPage) || ($currentPage == 'undefined'))
      {  $currentPage = 1;  }
      $numToDisplay = 20;
      $this->view->numToDisplay = $numToDisplay;
      $this->view->currentPage = $currentPage;
      $this->view->dataAwal = $currentPage;
      $this->view->dataAkhir = $numToDisplay;	
      $cariPegawaiPrm = array("i_peg_nip"  =>$iPegNip, "n_peg" =>$nPeg);

      $this->view->iPegNip = $iPegNip;
      $this->view->nPeg = $nPeg;
      $this->view->perintah = 'cari';
      $this->view->totPegawai = $this->dp3_serv->findPegawai($cariPegawaiPrm,0, 0);							
      $this->view->pegawaiList = $this->dp3_serv->findPegawai($cariPegawaiPrm,$currentPage,$numToDisplay);
      $this->_helper->viewRenderer('listPegawai');
   }	
   public function carinamapegawaiAction()
   {
      $this->_helper->viewRenderer->setNoRender(true);
      $nip = $_REQUEST['nip'];
      $namaPegawai = $this->sdm_absen_serv->getNamaPegawai($nip); 
	  echo $namaPegawai;
   }
   public function pegawaibrowseAction()
   {
		$category = $_REQUEST['c'];
        $idORG = $_REQUEST['p'];
		$nip = $_POST['i_peg_nip'];
		$nama = $_POST['n_peg'];
	    $PegPrm = array("i_peg_nip"  =>$nip,
						"n_peg"  =>$nama,
						"idorg" => $idORG);
		if ($idORG!="")
		{
		   if ($category=='A')
		   $this->view->listNama = $this->sdm_absen_serv->getPegawaiList($PegPrm);
		   else
		   $this->view->listNama = $this->sdm_absen_serv->getPegawaiListAll($PegPrm);
		}
		$this->view->serv = $this->sdm_absen_serv;
		$this->view->nip = $nip;
		$this->view->nama = $nama;
		$this->view->idORG = $idORG;
		//$this->view->basePath = $this->basePath;
   }
   public function excelabsensiAction()
   {
    $itNip = $_REQUEST['itNip'];
	$itNama = $_REQUEST['itNama'];
	$idORG = $_REQUEST['idORG'];
    $itThawl = $_REQUEST['sithns'];
	$itBlawl = $_REQUEST['siblns'];
	$itTgawl = $_REQUEST['sitgls'];
	$itThakr = $_REQUEST['sithnf'];
	$itBlakr = $_REQUEST['siblnf'];
	$itTgakr = $_REQUEST['sitglf'];
    $this->view->itThawl = $itThawl;
    $this->view->itBlawl = $itBlawl;
	$this->view->itTgawl = $itTgawl;

	$this->view->itThakr = $itThakr;
	$this->view->itBlakr = $itBlakr;
	$this->view->itTgakr = $itTgakr;
    $this->view->itNip = $itNip;
	$this->view->itNama = $itNama;
       $this->view->org = $idORG;
	   if ($idORG=="") { $idORG = $this->view->unitKerjaList[0]['i_orgb']; }
	   $this->view->serviceE = $this->sdm_absen_serv;
	   
   }
   public function listpegabsensiAction()
   {
	   $paging = $_REQUEST['paging'];
	   $jumpPage = $_REQUEST['jumpPage'];
	   $sKatagori = $_REQUEST['sKatagori'];
	   $sKatagoriVal = $_REQUEST['sKatagoriVal'];
	   $idORG = $_REQUEST['idORG'];
	   $ord = $_REQUEST['ord'];
	   $sitgls = $_REQUEST['sitgls'];
	   $siblns = $_REQUEST['siblns'];
	   $sithns = $_REQUEST['sithns'];
	   $sitglf = $_REQUEST['sitglf'];
	   $siblnf = $_REQUEST['siblnf'];
	   $sithnf = $_REQUEST['sithnf'];
	   if (!$sKatagori) $sKatagori = $_REQUEST['param1'];
	   if (!$sKatagoriVal) $sKatagoriVal = $_REQUEST['param2'];
	   if(!$idORG) $idORG = $_REQUEST['param3'];
	   if(!$ord) $ord = $_REQUEST['param4'];
       if (strlen($ord)>1) 
	   {
	      $bord = (string)$ord;
	      $ord = substr($bord,0,1);
	      $sitgls = substr($bord,1,2);
	      $siblns = substr($bord,3,2);
	      $sithns = substr($bord,5,4);
	      $sitglf = substr($bord,9,2);
	      $siblnf = substr($bord,11,2);
	      $sithnf = substr($bord,13,4);
	   }
	   if ($sKatagori=='undefined') $sKatagori='i_peg_nip';
	   if ($sKatagoriVal=='undefined') $sKatagoriVal='';
	   if($idORG == 'undefined') $idORG = '';	
	   if (($ord == 'undefined') || ($ord == '')) $ord = '1';
	   if ($sKatagori=="i_peg_nip") 
	   { 
	      $idNIP = $sKatagoriVal;
          $idNAMA = "";		  
	   }
	   else 
	   { 
	      $idNIP = "";
	      $idNAMA = $sKatagoriVal; 
	   }
 	   $currentPage = $_REQUEST['currentPage']; 
	   if((!$currentPage) || ($currentPage == 'undefined'))
	   { $currentPage = 1; }
	   $numToDisplay = 20;

	   $this->view->numToDisplay = $numToDisplay;
	   $this->view->currentPage = $currentPage;
	   
	   $this->view->unitKerjaList = $this->sdm_absen_serv->getUnitKerjaListAll();
	   if ($idORG=="") { $idORG = $this->view->unitKerjaList[0]['i_orgb']; }
	   $absensiPegPrm = array("i_peg_nip"  =>$idNIP,
							"n_peg"  =>$idNAMA,
							"c_unit_kerja" => $idORG,
							"ord"=>$ord);

       $this->view->org = $idORG;
	   $this->view->sKatagori = $sKatagori;
	   $this->view->sKatagoriVal = $sKatagoriVal;
       $this->view->ord = $ord;
	   if ($sitgls=="") $this->view->sitgls = date("d");
	   else $this->view->sitgls = $sitgls;
	   if ($siblns=="") $this->view->siblns = date("m");
	   else $this->view->siblns = $siblns;
	   $this->view->sithns = $sithns;
	   if ($sitglf=="") $this->view->sitglf = date("d");
	   else $this->view->sitglf = $sitglf;
	   if ($siblnf=="") $this->view->siblnf = date("m");
	   else $this->view->siblnf = $siblnf;
	   $this->view->sithnf = $sithnf;

	   $this->view->totAbsensiList = $this->sdm_absen_serv->getAbsensiListPP($absensiPegPrm,0,0);
	   $this->view->absensiList = $this->sdm_absen_serv->getAbsensiListPP($absensiPegPrm,$currentPage,$numToDisplay);
	   $this->view->absensiDescList = $this->sdm_absen_serv->getAbsensiDescListAll();
	   $this->view->yearNow = $this->sdm_absen_serv->getTahunSekarang();
	   $this->view->serviceE = $this->sdm_absen_serv;
   }
   public function absensipegawaiAction()
   {
    $usernip = $this->usernip;
    if ($this->category=="A") 
	{
	   $idORG = $_REQUEST['idORG'];
	   $this->view->unitKerjaList = $this->sdm_absen_serv->getUnitKerjaListAll();
	   if ($idORG=="") { $idORG = $this->view->unitKerjaList[0]['i_orgb']; }
       $itNip = $_REQUEST['itNip'];
	   $itNama = $_REQUEST['itNama'];
	   $this->view->nippegAtr = array ("style"=>"width:70px","maxlength"=>"10");
	   $this->view->namapegAtr = array ("style"=>"width:300px","maxlength"=>"50");
	   $this->view->btnAttr = array("style"=>"display:");
	}
	else if ($this->category=="U") 
	{
	   $dataUserOrg = $this->sdm_peg_serv->getUserOrg($usernip);
	   $userLevel = $dataUserOrg['level'];
	   $userOrg = $dataUserOrg ['userOrg'];
	   $dataPokok = $this->sdm_peg_serv->getPegawaiDetil($usernip);
	   $eselon = $dataPokok['eselon'];
	   if ($eselon == 'NE') 
	   { 
		  $idORG = $userOrg;
          $itNip = $usernip;
	      $itNama = $dataPokok['n_peg'];
		  $this->view->nippegAtr = array ("style"=>"width:70px","maxlength"=>"10","disabled"=>true);
		  $this->view->namapegAtr = array ("style"=>"width:300px","maxlength"=>"50","disabled"=>true);
		  $this->view->btnAttr = array("style"=>"display:none");
	   }
	   else 
	   { 
		  $this->view->nippegAtr = array ("style"=>"width:70px","maxlength"=>"10");
		  $this->view->namapegAtr = array ("style"=>"width:300px","maxlength"=>"50");
		  $this->view->btnAttr = array("style"=>"display:");
		  if ($userLevel == '1') { $org = 'MN'; }
		  else if ($userLevel == '2') 
		  {
			 if (substr($userOrg,0,2)=='SK'){ $org = substr($userOrg,0,2); }
			 else { $org = substr($userOrg,0,3); }
		  }
		  else if ($userLevel == '3') 
		  {
			 if (substr($userOrg,0,2)=='SK'){ $org = substr($userOrg,0,3); }
			 else { $org = substr($userOrg,0,4); }
		  }
		  else if ($userLevel == '4') 
		  {
			 if (substr($userOrg,0,2)=='SK'){ $org = substr($userOrg,0,4); }
			 else { $org = substr($userOrg,0,5); }
		  }
		  else if ($userLevel == '5') { $org = substr($userOrg,0,6); }	
		  $idORG = $org;
          $itNip = $_REQUEST['itNip'];
	      $itNama = $_REQUEST['itNama'];
	   }
       $unitKerjaU[0] = array("i_orgb"  =>$idORG,"n_orgb" =>$this->sdm_absen_serv->getUnitKerjaDesc($userOrg));
	   $this->view->unitKerjaList = $unitKerjaU;
	}
	else
	{
	   $this->view->nippegAtr = array ("style"=>"width:70px","maxlength"=>"10","disabled"=>true);
	   $this->view->namapegAtr = array ("style"=>"width:300px","maxlength"=>"50","disabled"=>true);
	   $this->view->btnAttr = array("style"=>"display:none");
	}
    $itThawl = $_REQUEST['itThawl'];
    if ($itThawl=="") $itThawl = date("Y");
	$itBlawl = $_REQUEST['itBlawl'];
	if ($itBlawl=="") $itBlawl = date("m");
	$itThakr = $_REQUEST['itThakr'];
    if ($itThakr=="") $itThakr = date("Y");
	$itBlakr = $_REQUEST['itBlakr'];
	if ($itBlakr=="") $itBlakr = date("m");
	$itTgawl = $_REQUEST['itTgawl'];
	if ($itTgawl=="") { $itTgawl = date("d"); }
	$itTgakr = $_REQUEST['itTgakr'];
	if ($itTgakr=="") { $itTgakr = date("d"); }
    $jmlTgawlLst = cal_days_in_month(CAL_GREGORIAN, $itBlawl, $itThawl); 
	$tgawlLst = array();
    for ($i=1; $i<=$jmlTgawlLst; $i++)
	{
	   if ((int)$i<10) $i="0".$i;
	   $tgawlLst[$i] = $i;
	}
    $jmlTgakrLst = cal_days_in_month(CAL_GREGORIAN, $itBlakr, $itThakr); 
	$tgakrLst = array();
    for ($i=1; $i<=$jmlTgakrLst; $i++)
	{
	   if ((int)$i<10) $i="0".$i;
	   $tgakrLst[$i] = $i;
	}
    $this->view->itThawl = $itThawl;
    $this->view->itBlawl = $itBlawl;
	$this->view->itTgawl = $itTgawl;
    $this->view->itTgawlLst = $tgawlLst;

	$this->view->itThakr = $itThakr;
	$this->view->itBlakr = $itBlakr;
	$this->view->itTgakr = $itTgakr;
	$this->view->itTgakrLst = $tgakrLst;
    $this->view->itNip = $itNip;
	$this->view->itNama = $itNama;
    $this->view->org = $idORG;

	$this->view->serviceE = $this->sdm_absen_serv;
	$this->view->eselonUserId = $this->sdm_absen_serv->getEselonUserid($usernip);
	$this->view->usernip = $usernip;
	$this->view->category = $this->category;
   }
   public function harikerjajsAction()
   {
	  header('content-type : text/javascript');
	  $this->render('harikerjajs');
   }
   public function absensijsAction()
   {
	  header('content-type : text/javascript');
	  $this->render('absensijs');
   }
	public function cetakabsensiAction() 
	{
	   $idORG = $_REQUEST['idORG'];
	   $sitgls = $_REQUEST['sitgls'];
	   $siblns = $_REQUEST['siblns'];
	   $sithns = $_REQUEST['sithns'];
	   $sitglf = $_REQUEST['sitglf'];
	   $siblnf = $_REQUEST['siblnf'];
	   $sithnf = $_REQUEST['sithnf'];
	   $idNIP = $_REQUEST['nip'];
	   $idNAMA = $_REQUEST['nama'];
	   $absensiPegPrm = array("i_peg_nip"  =>$idNIP,
							"n_peg"  =>$idNAMA,
							"c_unit_kerja" => $idORG);

       $this->view->idORG = $idORG;
	   $this->view->sitgls = $sitgls;
	   $this->view->siblns = $siblns;
	   $this->view->sithns = $sithns;
	   $this->view->sitglf = $sitglf;
	   $this->view->siblnf = $siblnf;
	   $this->view->sithnf = $sithnf;
	   $this->view->nip = $idNIP;
	   $this->view->nama = $idNAMA;

	   $this->view->absensiList = $this->sdm_absen_serv->getAbsensiListAll($absensiPegPrm);
	   $this->view->yearNow = $this->sdm_absen_serv->getTahunSekarang();
	   $this->view->absensiDescList = $this->sdm_absen_serv->getAbsensiDescListAll();
	   $this->view->serviceE = $this->sdm_absen_serv;
	   $this->view->pejabat = $this->sdm_absen_serv->getPejabatAbsen('SK1200');
	   $this->view->tglCetak = $this->sdm_absen_serv->getTglSekarang();
	   $this->_helper->viewRenderer('cetakabsensi');
	}
	public function buatubahharikerjaAction()
	{
	   $user = $this->user;
	   $this->_helper->viewRenderer->setNoRender(true);
	   $blnHariKerja = $_REQUEST['blnUbhHariKerja'];
	   $thnHariKerja = $_REQUEST['thnUbhHariKerja'];
       $jmlDataHK = cal_days_in_month(CAL_GREGORIAN, $blnHariKerja, $thnHariKerja);
	   $this->sdm_absen_serv->delHariKerja($thnHariKerja,$blnHariKerja);
	   for ($i=1; $i<=$jmlDataHK; $i++)
	   {
	      if ($_REQUEST['ihiStatus'.$i]=="Masuk")
		  {
		     if ((int)$i<10) { $tglharikerja = "0".$i; }
		     else { $tglharikerja = $i; }
	         $harikerjaData = array("d_tgl_kerja"	  => $thnHariKerja.$blnHariKerja.$tglharikerja,
                              "d_jamkerja_mulai" => $_REQUEST['itMskMulai'.$i],
                              "d_jamkerja_selesai"     => $_REQUEST['itMskSelesai'.$i],
                              "d_jamistrht_mulai" => $_REQUEST['itBreakMulai'.$i],
                              "d_jamistrht_selesai"    => $_REQUEST['itBreakSelesai'.$i],
							  "i_entry"		  => $user);
	         $this->sdm_absen_serv->insHariKerja($harikerjaData);
		  }
	   }
	   echo "<script>alert('Data sudah disimpan...');</script>";
       $this->view->thnSekarang = $thnHariKerja;
       $this->view->blnSekarang = $blnHariKerja;
	   $this->view->srviceE = $this->sdm_absen_serv;
	   $this->render('ubahharikerja');
	}
	public function buatharikerjaAction()
	{
	   $user = $this->user;
	   if ($user=='') { $user = 'autosystem'; }
	   $this->_helper->viewRenderer->setNoRender(true);
	   $blnHariKerja = $_POST['blnHariKerja'];
	   $thnHariKerja = $_POST['thnHariKerja'];
       $jmlDataHK = cal_days_in_month(CAL_GREGORIAN, $blnHariKerja, $thnHariKerja); 
	   $dataygAda = $this->sdm_absen_serv->cekHariKerja($thnHariKerja,$blnHariKerja);
	   if ($dataygAda>0)
	   {
	      $hasil = $this->sdm_absen_serv->delHariKerja($thnHariKerja,$blnHariKerja);
	      for ($i=1; $i<=$jmlDataHK; $i++)
	      {
	         if ($_POST['ihiStatus'.$i]=="Masuk")
		     {
		        if ((int)$i<10) { $tglharikerja = "0".$i; }
		        else { $tglharikerja = $i; }
	            $harikerjaData = array("d_tgl_kerja"	  => $thnHariKerja.$blnHariKerja.$tglharikerja,
                              "d_jamkerja_mulai" => $_POST['itMskMulai'.$i],
                              "d_jamkerja_selesai"     => $_POST['itMskSelesai'.$i],
                              "d_jamistrht_mulai" => $_POST['itBreakMulai'.$i],
                              "d_jamistrht_selesai"    => $_POST['itBreakSelesai'.$i],
							  "i_entry"		  => $user);
	            $hasil = $this->sdm_absen_serv->insHariKerja($harikerjaData);
echo "hasil : ".$hasil;
		     }
	      }
	   }  
	   else
	   {
	      for ($i=1; $i<=$jmlDataHK; $i++)
	      {
	         if ($_POST['ihiStatus'.$i]=="Masuk")
		     {
		        if ((int)$i<10) { $tglharikerja = "0".$i; }
		        else { $tglharikerja = $i; }
	            $harikerjaData = array("d_tgl_kerja"	  => $thnHariKerja.$blnHariKerja.$tglharikerja,
                              "d_jamkerja_mulai" => $_POST['itMskMulai'.$i],
                              "d_jamkerja_selesai"     => $_POST['itMskSelesai'.$i],
                              "d_jamistrht_mulai" => $_POST['itBreakMulai'.$i],
                              "d_jamistrht_selesai"    => $_POST['itBreakSelesai'.$i],
							  "i_entry"		  => $user);
	            $hasil = $this->sdm_absen_serv->insHariKerja($harikerjaData);
		     }
	      }
	      //echo "<script>alert('Data sudah disimpan...');</script>";
	   }  
       //$this->view->thnSekarang = $thnHariKerja;
       //$this->view->blnSekarang = $blnHariKerja;
	   //$this->view->srviceE = $this->sdm_absen_serv;
	   //$this->render('harikerja');
	}
	//bamris
	public function testAction()
	{
       //$this->_helper->viewRenderer->setNoRender(true);
	   //echo "Saya disini";
	}
	public function xabsensimesinAction()
	{
	}
	public function insabsensimesinAction()
	{
	   $user = $_POST['user'];
       $this->_helper->viewRenderer->setNoRender(true);
       $noserver = trim($_POST['noserver']);
       $nip = trim($_POST['nip']);
       $tanggal = trim($_POST['tanggal']);
       $jam = trim($_POST['jam']);
       $kodeabsen = trim($_POST['kodeabsen']);
       $noabsen = trim($_POST['noabsen']);
       $stsabsen = trim($_POST['stsabsen']);
       $terminal = trim($_POST['terminal']);
	   $absenMesinIns = array("c_terminal"	  => $noserver,
                              "i_peg_nip" 	  => $nip,
                              "d_peg_absensi" => $tanggal,
                              "d_peg_jam"     => $jam,
                              "c_absensi_peg" => $kodeabsen,
                              "i_no_absensi"  => $noabsen,
                              "c_status_absn" => $stsabsen,
                              "i_term"        => $terminal,
							  "i_entry"		  => $user);
	   $AbsensiFound = $this->sdm_absen_serv->foundAbsensiMesin($nip,$tanggal,$jam);
	   echo "nilai AbsensiFound : ".$AbsensiFound;
	   if ($AbsensiFound!="")
	   { echo "Data sudah ada"; }
	   else
	   { 
	     $hasilProses = $this->sdm_absen_serv->insAbsensiMesin($absenMesinIns); 
	     echo $hasilProses;
	   }

	}
	public function listtanggalAction()
	{
	   $idTgl = $_REQUEST['ittgl'];
	   $tahun = $_REQUEST['ittahun']; 
	   $bulan = $_REQUEST['itbulan']; 
       $this->_helper->viewRenderer->setNoRender(true);
	   $jmlHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
	   $html = $html ."<select id='".$idTgl."' name='".$idTgl."'>";
       for ($i=1; $i<=$jmlHari; $i++)
	   {
	      if ((int)$i<10) $i="0".$i;
	      $html = $html ."<option value='".$i."'>".$i."</option>";
	   }
	   $html = $html ."</select>";
	   echo $html;
	}
    public function harikerjaAction()
    {
	   $thnSekarang = $_REQUEST['thnHariKerja'];
	   $blnSekarang = $_REQUEST['blnHariKerja'];
	   if ($thnSekarang=="") $thnSekarang = date("Y");
	   if ($blnSekarang=="") $blnSekarang = date("m");
       $this->view->thnSekarang = $thnSekarang;
       $this->view->blnSekarang = $blnSekarang;
	   $this->view->srviceE = $this->sdm_absen_serv;
    }	
    public function ubahharikerjaAction()
    {
	   $thnSekarang = $_REQUEST['thnHariKerja'];
	   $blnSekarang = $_REQUEST['blnHariKerja'];
	   if ($thnSekarang=="") $thnSekarang = date("Y");
	   if ($blnSekarang=="") $blnSekarang = date("m");
       $this->view->thnSekarang = $thnSekarang;
       $this->view->blnSekarang = $blnSekarang;
	   $this->view->srviceE = $this->sdm_absen_serv;
    }	
	
    public function dataijinAction() {
	   //echo "Masuk Controller pegawaiupdateAction";
			$hr1 = $_POST['hr1'];
			$bln1 = $_POST['bln1'];
			$thn1 = $_POST['thn1'];
			$hr2 = $_POST['hr2'];
			$bln2 = $_POST['bln2'];
			$thn2 = $_POST['thn2'];
			$tgl1 = $thn1."-".$bln1."-".$hr1;
			$tgl2 = $thn2."-".$bln2."-".$hr2;
			 
			if (($hr1 == '--') || ($bln1 == '--') || ($thn1 == null)) {
				$tgl1 = '1900-01-01';
			}
			if (($hr2 == '--') || ($bln2 == '--') || ($thn2 == null)) {
				$tgl2 = '2100-01-01';				
			}
 		
		$this->view->ijinList = $this->sdm_absen_serv->getIjinList($tgl1, $tgl2);		   


	   if ($_POST['perintah'] == 'CARI'){
	    //echo "masuk cari";
			$hr1 = $_POST['hr1'];
			$bln1 = $_POST['bln1'];
			$thn1 = $_POST['thn1'];
			$hr2 = $_POST['hr2'];
			$bln2 = $_POST['bln2'];
			$thn2 = $_POST['thn2'];
			$tgl1 = $thn1."-".$bln1."-".$hr1;
			$tgl2 = $thn2."-".$bln2."-".$hr2;
			 
			if (($hr1 == '--') || ($bln1 == '--') || ($thn1 == null)) {
				$tgl1 = '1900-01-01';
			}
			if (($hr2 == '--') || ($bln2 == '--') || ($thn2 == null)) {
				$tgl2 = '2100-01-01';				
			}
 		//echo "nip= ".$nip."<br>"."tgl1= ".$tgl1."<br>"."tgl2= ".$tgl2."<br>";
		
		$this->view->ijinList = $this->sdm_absen_serv->getIjinList($tgl1, $tgl2);		   

		}		
	}	
	
	
	public function tambahijinAction() {
	   //echo "Masuk Controller tambahijinAction";
		$user = $this->user;
		//echo "user= ".$user;
		
		if ($_POST['perintah'] == 'SIMPAN'){
			
			$hrIjin = $_POST['hrIjin'];
			$blnIjin = $_POST['blnIjin'];
			$thnIjin = $_POST['thnIjin'];
			
			if ($thnIjin == ' ') {
				$tanggal = null;
			}else if ($blnIjin == '#') {
				$tanggal = null;
			}else if ($hrIjin == '#') {
				$tanggal = null;
			} else {
				$tanggal = $thnIjin."-".$blnIjin."-".$hrIjin;
			}

			
			$hrIjinA = $_POST['hrIjinA'];
			$blnIjinA = $_POST['blnIjinA'];
			$thnIjinA = $_POST['thnIjinA'];
			
			if ($thnIjinA == ' ') {
				$tanggalA = null;
			}else if ($blnIjinA == '#') {
				$tanggalA = null;
			}else if ($hrIjinA == '#') {
				$tanggalA = null;
			} else {
				$tanggalA = $thnIjinA."-".$blnIjinA."-".$hrIjinA;
			}

			$jamMulai = $_POST['jamMulai'];
			$menitMulai = $_POST['menitMulai'];
			$detikMulai = $_POST['detikMulai'];
			$mulai = $jamMulai.":".$menitMulai.":".$detikMulai;
			
			$jamSelesai = $_POST['jamSelesai'];
			$menitSelesai = $_POST['menitSelesai'];
			$detikSelesai = $_POST['detikSelesai'];
			$selesai = $jamSelesai.":".$menitSelesai.":".$detikSelesai;
			
		if (!$tanggalA == null) {	
		     $rangeIjin = $this->sdm_cuti_serv->getTglharikerja($tanggal,$tanggalA);
			 $jmlDataIjin = count($rangeIjin);

			 for ($j=0; $j<$jmlDataIjin; $j++)
			 {
			    $tgl = $rangeIjin[$j]['d_tgl_kerja'];
				$prmIjinInsert = array("nip"			=>$_POST['nip'],
										"noSurat"		=>$_POST['noSurat'],
										"tanggal"		=>$tgl,
										"jamMulai"		=>$mulai,
										"jamSelesai"	=>$selesai,
										"jenis"			=>$_POST['kdIjin'],
										"keterangan"	=>$_POST['keterangan'],
										"user"			=>$user);
					$hasil = $this->sdm_absen_serv->insertIjin($prmIjinInsert);
			}
		} 
		else {
			$prmIjinInsert = array("nip"			=>$_POST['nip'],
									"noSurat"		=>$_POST['noSurat'],
									"tanggal"		=>$tanggal,
									"jamMulai"		=>$mulai,
									"jamSelesai"	=>$selesai,
									"jenis"			=>$_POST['kdIjin'],
									"keterangan"	=>$_POST['keterangan'],
									"user"			=>$user);
			$hasil = $this->sdm_absen_serv->insertIjin($prmIjinInsert);		
		}
				$tgl1 = '1900-01-01';
				$tgl2 = '2100-01-01';
				$this->view->ijinList = $this->sdm_absen_serv->getIjinList($tgl1, $tgl2);
				if ($hasil == 'sukses') {
					$this->view->pesan = 'Simpan Data Ijin Berhasil';
				}
				else {
					$this->view->pesan = 'Simpan Data Ijin Gagal';				
				}
				$this->render('dataijin');	
			}
		}
		
	
	public function listnamanipAction() {
		
		$nip = $_POST['i_peg_nip'];
		$nama = $_POST['n_peg'];
		//echo "nama= ".$nama."<br>"."nip= ".$nip."<br>";		
		$this->view->listNama = $this->sdm_cv_serv->getPegawaiListAll($nip, $nama);
		
		if ($_POST['perintah'] == 'CARI'){
			$nip = $_POST['i_peg_nip'];
			$nama = $_POST['n_peg'];
			//echo "nama= ".$nama."<br>"."nip= ".$nip."<br>";		
			$this->view->listNama = $this->sdm_cv_serv->getPegawaiListAll($nip, $nama);		
		}
		
	}
	
	public function ubahijinAction() {
	   //echo "Masuk Controller ubahijinAction"."<br>";
		$user = $this->user;
		//echo "user= ".$user;
		$nip = $_REQUEST['nip'];
		$tanggal = $_REQUEST['tanggal'];
		$jamMulai = $_REQUEST['jamMulai'];
		//echo "nip= ".$nip."<br>"."tanggal= ".$tanggal."<br>"."jamMulai= ".$jamMulai."<br>";
		$this->view->dataIjin = $this->sdm_absen_serv->getIjinByKey($nip, $tanggal, $jamMulai);
				
	    $this->view->nip = $this->view->dataIjin ['nip'];
		$this->view->nama = $this->view->dataIjin ['nama'];
		$this->view->tanggal = $this->view->dataIjin ['tanggal'];
		$this->view->noSurat = $this->view->dataIjin ['noSurat'];
		$this->view->jenis = $this->view->dataIjin ['jenis'];
		$this->view->jamMulai = $this->view->dataIjin ['jamMulai'];
		$this->view->jamSelesai = $this->view->dataIjin ['jamSelesai'];
		$this->view->keterangan = $this->view->dataIjin ['keterangan'];	
		
		if ($_POST['perintah'] == 'UBAH'){
			//echo "ubah";
			$hrIjin = $_POST['hrIjin'];
			$blnIjin = $_POST['blnIjin'];
			$thnIjin = $_POST['thnIjin'];
			
			if ($thnIjin == ' ') {
				$tanggal = null;
			}else if ($blnIjin == '#') {
				$tanggal = null;
			}else if ($hrIjin == '#') {
				$tanggal = null;
			} else {
				$tanggal = $thnIjin."-".$blnIjin."-".$hrIjin;
			}

			$jamMulai = $_POST['mulaiJam'];
			$menitMulai = $_POST['menitMulai'];
			$detikMulai = $_POST['detikMulai'];
			$mulai = $jamMulai.":".$menitMulai.":".$detikMulai;
			//echo "mulai= ".$mulai."<br>";
			$jamSelesai = $_POST['jamSelesai'];
			$menitSelesai = $_POST['menitSelesai'];
			$detikSelesai = $_POST['detikSelesai'];
			$selesai = $jamSelesai.":".$menitSelesai.":".$detikSelesai;
			//echo "selesai= ".$selesai."<br>";
			
			$prmIjinUpd = array("nip"				=>$_POST['nip'],
									"nipH"			=>$_POST['nipH'],
									"tanggal"		=>$tanggal,
									"tanggalH"		=>$_POST['tanggalH'],
									"jamMulai"		=>$mulai,
									"jamMulaiH"		=>$_POST['mulaiH'],
									"jamSelesai"	=>$selesai,
									"noSurat"		=>$_POST['noSurat'],
									"jenis"			=>$_POST['kdIjin'],
									"keterangan"	=>$_POST['keterangan'],
									"user"			=>$user);
				$hasil = $this->sdm_absen_serv->updateIjin($prmIjinUpd);
				
				$tgl1 = '1900-01-01';
				$tgl2 = '2100-01-01';
				//echo "vvvvv";
				$this->view->ijinList = $this->sdm_absen_serv->getIjinList($tgl1, $tgl2);
				if ($hasil == 'sukses') {
					$this->view->pesan = 'Ubah Data Ijin Berhasil';
				}
				else {
					$this->view->pesan = 'Ubah Data Ijin Gagal';				
				}
				$this->render('dataijin');	
			}
		}
		
	
	public function hapusijinAction() {	
		$param5	= $_REQUEST['konf'];
		$prmIjinDelete = array("nip"		=>$_REQUEST['nip'],
								 "tanggal"	=>$_REQUEST['tanggal'],
								 "jamMulai"		=>$_REQUEST['mulai']);
		$hasil = $this->sdm_absen_serv->deleteIjin($prmIjinDelete);
		//echo "param5= ".$param5;
		if($hasil == 'sukses')
		{
			$this->view->pesan = "Hapus Ijin $param5 Berhasil";
		}
		else
		{
			$this->view->pesan = "Hapus Ijin $param5 Gagal";
		}
		
				$tgl1 = '1900-01-01';
				$tgl2 = '2100-01-01';
				//echo "vvvvv";
				$this->view->ijinList = $this->sdm_absen_serv->getIjinList($tgl1, $tgl2);
		
		$this->render('dataijin');
	} 
//bamris
    public function statusabsenpegAction()
    {
		$nip = $_REQUEST['nip'];
		if ($nip == null) { $nip = $_REQUEST['param1'];	 }
		if ($nip == null) { $nip = ''; }

		$nama= $_REQUEST['nama'];
		if ($nama == null) { $nama = $_REQUEST['param2']; }
	    if ($nama == null) { $nama = ''; }

		$org= $_REQUEST['org'];
		$this->view->org = $org;

		$usernip = $this->usernip;
		/*
		$this->view->dataUserOrg = $this->sdm_peg_serv->getUserOrg($usernip);	 
		$this->view->nip = $this->view->dataUserOrg ['nip'];
		$this->view->userOrg = $this->view->dataUserOrg ['userOrg'];
		$this->view->level = $this->view->dataUserOrg ['level'];

	    $this->view->dataPokok = $this->sdm_peg_serv->getPegawaiDetil($usernip);	 
		$this->view->eselon = $this->view->dataPokok ['eselon'];
		
		$userLevel = $this->view->level;
		$userOrg = $this->view->userOrg;
		$eselon = $this->view->eselon;
		if ($eselon == 'NE') { $org = 'NE'; }
		else 
		{
			if ($userLevel == '1') { $org = 'MN'; }
			else if ($userLevel == '2') 
			{ 
			    if (substr($userOrg,0,2)=='SK'){ $org = substr($userOrg,0,2); }
				else { $org = substr($userOrg,0,3); }
			}
			else if ($userLevel == '3') 
			{
			    if (substr($userOrg,0,2)=='SK'){ $org = substr($userOrg,0,3); }
				else { $org = substr($userOrg,0,4); }
			}
			else if ($userLevel == '4') 
			{ 
				if (substr($userOrg,0,2)=='SK') { $org = substr($userOrg,0,4); }
				else { $org = substr($userOrg,0,5); }
			}
			else if ($userLevel == '5') { $org = substr($userOrg,0,6); }	
		}
        */
 	    $currentPage = $_REQUEST['currentPage']; 
		$param1 = $_REQUEST['param1']; 
		$param2 = $_REQUEST['param2']; 
		$param3 = $_REQUEST['param3']; 
		$param4 = $_REQUEST['param4']; 
		if((!$currentPage) || ($currentPage == 'undefined'))
		{ $currentPage = 1; }
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		
	    $this->view->unitKerjaList = $this->sdm_absen_serv->getUnitKerjaListAll();
	    if ($org=="") { $org= $this->view->unitKerjaList[0]['i_orgb']; }
		echo "org di controller : ".$org."<br>";
		$this->view->totPegawai = $this->sdm_cv_serv->getPegawaiListByUser($nip, $nama, $org, $usernip, 0, 0);
		$this->view->pegawaiList = $this->sdm_cv_serv->getPegawaiListByUser($nip, $nama, $org, $usernip, $currentPage, $numToDisplay );
	    $this->view->serviceE = $this->sdm_absen_serv;
	   
    }	
	
}
?>