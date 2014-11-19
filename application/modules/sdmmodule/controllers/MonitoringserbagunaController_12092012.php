<?php
require_once 'Zend/Controller/Action.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Monitoring_Service.php";
require_once "service/sdm/Sdm_Pendidikan_Service.php";
require_once "service/sdm/Sdm_Jabatan_Service.php";
require_once "service/sdm/Sdm_Pangkat_Service.php";
require_once "service/sdm/Sdm_Organisasi_Service.php";
require_once "service/sdm/Sdm_Luarnegeri_Service.php";
require_once "service/sdm/Sdm_Seminar_Service.php";
require_once "service/sdm/Sdm_Penghargaan_Service.php";
require_once "service/sdm/Sdm_Hukuman_Service.php";
require_once "service/sdm/Sdm_Angkakredit_Service.php";
require_once "service/sdm/Sdm_Pasangan_Service.php";
require_once "service/sdm/Sdm_Anak_Service.php";
require_once "service/sdm/Sdm_Kerabat_Service.php";
require_once "service/sdm/Sdm_DiklatPenjenjangan_Service.php";
require_once "service/sdm/Sdm_DiklatFungsional_Service.php";
require_once "service/sdm/Sdm_DiklatTeknis_Service.php";
require_once "service/sdm/Sdm_DiklatLain_Service.php";


class Sdmmodule_MonitoringserbagunaController extends Zend_Controller_Action
{
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
	 	$registry = Zend_Registry::getInstance();
	   	$this->view->basePath = $registry->get('basepath');
		$this->view->leftMenu = $registry->get('leftMenu'); 
		$this->reff_serv = sdm_refferensi_Service::getInstance();
		$this->pegawai_serv = Sdm_Monitoring_Service::getInstance();
		$this->pendidikan_serv = Sdm_Pendidikan_Service::getInstance();
		$this->jabatan_serv = Sdm_Jabatan_Service::getInstance();
		$this->pangkat_serv = Sdm_Pangkat_Service::getInstance();
		$this->organisasi_serv = Sdm_Organisasi_Service::getInstance();
		$this->luarnegeri_serv = Sdm_Luarnegeri_Service::getInstance();
		$this->seminar_serv = Sdm_Seminar_Service::getInstance();
		$this->penghargaan_serv = Sdm_Penghargaan_Service::getInstance();
		$this->hukuman_serv = Sdm_Hukuman_Service::getInstance();
		$this->angkakredit_serv = Sdm_Angkakredit_Service::getInstance();
		$this->pasangan_serv = Sdm_Pasangan_Service::getInstance();
		$this->anak_serv = Sdm_Anak_Service::getInstance();
		$this->kerabat_serv = Sdm_Kerabat_Service::getInstance();
		$this->penjenjangan_serv = Sdm_DiklatPenjenjangan_Service::getInstance();
		$this->fungsional_serv = Sdm_DiklatFungsional_Service::getInstance();
		$this->teknis_serv = Sdm_DiklatTeknis_Service::getInstance();
		$this->diklain_serv = Sdm_DiklatLain_Service::getInstance();
		
		
		$this->view->jabatanKelFungList=$this->jabatanKelFungList;
		$seslaporan = new Zend_Session_Namespace('seslaporan');
		$this->jabatanKelFungList= $seslaporan->jabatanKelFungList;
		$this->eselonList= $seslaporan->eselonList;
		$this->lokasiList= $seslaporan->lokasiList;
		$this->statusGolRef= $seslaporan->statusGolRef;
		$this->pendRef= $seslaporan->pendRef;
		$this->agamaRef= $seslaporan->agamaRef;
		$this->eselonRef= $seslaporan->eselonRef;
		$this->golRef= $seslaporan->golRef;
		$this->dikPimRef= $seslaporan->dikPimRef;
		$this->nikahRef= $seslaporan->nikahRef;
		$this->propinsiList= $seslaporan->propinsiList;
		$this->darahList= $seslaporan->darahList;
		$this->jurPendList= $seslaporan->jurPendList;
		$this->universitasList= $seslaporan->universitasList;
		$this->jabatanList= $seslaporan->jabatanList;			
	
    }
	public function indexAction()
	{
	}
	public function monitoringserbagunajsAction() 
	{
		header('content-type : text/javascript');
		$this->render('monitoringserbagunajs');
	}
	public function monitoringserbagunaAction() 
	{
		$seslaporan = new Zend_Session_Namespace('seslaporan');
		$eselonList = $this->reff_serv->getEselon('');
		$this->view->eselonList = $eselonList;
		$lokasiList = $this->reff_serv->getLokasi('');
		$this->view->lokasiList = $lokasiList;
		$statusGolRef = $this->reff_serv->getGolonganPegawai('');
		$this->view->statusGolRef = $statusGolRef;	
		$pendRef = $this->reff_serv->getPendidikan('');
		$this->view->pendRef = $pendRef;
		$agamaRef = $this->reff_serv->getAgamaList('');
		$this->view->agamaRef = $agamaRef;
		$eselonRef = $this->reff_serv->getEselonList('');
		$this->view->eselonRef=$eselonRef;
		$carigol=" and c_peg_tipegolongan ='3' ";
		$golRef = $this->reff_serv->getGolonganPegawai($carigol);
		$this->view->golRef =$golRef;
		$dikPimRef = $this->reff_serv->getTrPenjenjanganList('');
		$this->view->dikPimRef =$dikPimRef;
		$nikahRef = $this->reff_serv->getTrStatusNikahList('');
		$this->view->nikahRef =$nikahRef;
		$propinsiList = $this->reff_serv->getPropinsiListAll('');
		$this->view->propinsiList =$propinsiList;
		$darahList = $this->reff_serv->getTrGolDarah('');
		$this->view->darahList =$darahList;
		$jurPendList = $this->reff_serv->getTrJurusanPendidikan('');
		$this->view->jurPendList =$jurPendList;
		$universitasList = $this->reff_serv->getTrUniversitas('');
		$this->view->universitasList =$universitasList;
		$jabatanList = $this->reff_serv->getJabatan('');
		$this->view->jabatanList = $jabatanList;		
		$jabatanKelFungList = $this->reff_serv->getKelPelatihanFungsional('');		
		$this->view->jabatanKelFungList = $jabatanKelFungList;
///session		
		$seslaporan->jabatanKelFungList= $jabatanKelFungList;
		$seslaporan->eselonList= $eselonList;
		$seslaporan->lokasiList= $lokasiList;
		$seslaporan->statusGolRef= $statusGolRef;
		$seslaporan->pendRef= $pendRef;
		$seslaporan->agamaRef= $agamaRef;
		$seslaporan->eselonRef= $eselonRef;
		$seslaporan->golRef= $golRef;
		$seslaporan->dikPimRef= $dikPimRef;
		$seslaporan->nikahRef= $nikahRef;
		$seslaporan->propinsiList= $propinsiList;
		$seslaporan->darahList= $darahList;
		$seslaporan->jurPendList= $jurPendList;
		$seslaporan->universitasList= $universitasList;
		$seslaporan->jabatanList= $jabatanList;		
	}

	public function combonamajabatanAction() 
	{
	        $c_eselon=$_GET['kd_jbt'];
		$this->view->nmJabatanList = $this->reff_serv->getJabatan(" and c_eselon='$c_eselon'");		
	}	
	public function divunitkerjaAction() 
	{
		$this->view->lokasiList = $this->reff_serv->getLokasi('');
	}

	
	public function divdiklatAction() 
	{
		$this->view->listrpenjenjangan=$this->reff_serv->getTrPenjenjangan('');	
		$this->view->listrkualifikasi=$this->reff_serv->getTrDiklatKualifikasi('');	
		$this->view->listrkelteknis=$this->reff_serv->getTrKelDiklatTeknis('');	
		$this->view->listnegara=$this->reff_serv->getNegara('');
		$this->view->lisjnslatih=$this->reff_serv->getJnsPelatihanFungsional('');		
		$this->view->liskellatih=$this->reff_serv->getKelPelatihanFungsional('');	
		$this->view->lisjenjanglatih=$this->reff_serv->getPenjenjanganFungsional('');
		$this->view->lisnamajenjanglatih=$this->reff_serv->getNamaPenjenjanganFungsional('');	
		$this->view->listnegara=$this->reff_serv->getNegara('');			
	}
	public function divpangkatAction() 
	{
		$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai($cari);
	}	
	public function divjabatanAction() 
	{
	
		$this->view->eselonList = $this->reff_serv->getEselon('');		
		$this->view->lokasiList = $this->reff_serv->getLokasi('');	
		$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1'");
		$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='2'");
		$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='3'");
		$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='4'");
		$this->view->eselonvList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='5'");	
		$this->view->nmJabatanList = $this->reff_serv->getJabatan($cari);		
	}
	public function divseminarAction() 
	{
	}	
	public function divpendidikanAction() 
	{
		$this->view->nmJenjangList = $this->reff_serv->getPendidikan($cari);
	}		
	public function divorganisasiAction() 
	{
		
	}		
	public function divpenghargaanAction() 
	{
		$this->view->negaraList = $this->reff_serv->getNegara(''); 
		$this->view->jnsPenghargaanList = $this->reff_serv->getPenghargaan(''); 
		$this->view->tandaJasaList = $this->reff_serv->getTandaJasa(''); 			
	}		
	public function divhukumanAction() 
	{
			
	}
	public function divluarnegeriAction() 
	{
		$this->view->negaraList = $this->reff_serv->getNegara(''); 
	}	
	public function divkeluargaAction() 
	{
		$this->view->pekerjaanList = $this->reff_serv->getPekerjaan(''); 	
	}	
    // public function combonamajabatanAction() {
		// $c_eselonjabdiv=$_GET['c_eselonjabdiv'];
	// $cari =" and c_eselon ='$c_eselonjabdiv' ";
		// $this->view->nmJabatanList = $this->reff_serv->getJabatan($cari);
	// }
	public function divalamatAction() 
	{
	$this->view->propinsiList = $this->reff_serv->getPropinsiListAll();
	$this->view->kabupatenList = $this->reff_serv->getKabupatenListAll($carikabupaten);
	$this->view->kabupatenListLahir = $this->reff_serv->getKabupatenListAll($carikabupatenlahir);			
	}	
	

public function listcombolaporanAction() {
	$jabatanlengkap="";
	$this->view->c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);
	$this->view->lokasiList = $this->reff_serv->getLokasi('');

	
	$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);
	$this->view->c_eselon_i =trim($_GET['eseloni']);
	$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

	
	$c_eselon_i=$_GET['eseloni'];
	$c_eselon_i=substr($c_eselon_i,0,2);
	$this->view->c_eselon_ii =trim($_GET['eselonii']);
	$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
	if ($_GET['eseloni']){$nesl1=$this->left($_GET['eseloni']); $nesl1=",".$nesl1;}
	
	$c_eselon_ii=$_GET['eselonii'];
	$c_eselon_ii=substr($c_eselon_ii,0,2);
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

}	
function right($string){
    return substr($string,0,2);
}
function left($string){
    return substr($string,3,200);
}	

public function cetakviewAction(){

		$cari="";
		$jmldarahList = count($this->darahList);
		for ($j = 0; $j < $jmldarahList; $j++) 
			{
				$nodarah++;
				$c_golongan_darah=$this->darahList[$j]['c_golongan_darah'];$c_golongan_darah="'$c_golongan_darah'";
				if ($_POST['jns_darah'.$nodarah]){$pargold=$c_golongan_darah.",".$pargold;}				
			}
		if ($pargold){$pargold = substr($pargold,0,-1); $cari= " and c_golongan_darah in ($pargold)";}

		$jmlagamaRef = count($this->agamaRef);
		for ($j = 0; $j < $jmlagamaRef; $j++) 
			{
				$noagama++;
				$c_agama=$this->agamaRef[$j]['c_agama'];$c_agama="'$c_agama'";
				if ($_POST['jns_agama'.$noagama]){$paragama=$c_agama.",".$paragama;}				
			}
		if ($paragama){$paragama = substr($paragama,0,-1); $cari= $cari." and c_agama in ($paragama)";}		

		$jmlpendRef = count($this->pendRef);
		for ($j = 0; $j < $jmlpendRef; $j++) 
			{
				$nopend++;
				$c_pend=$this->pendRef[$j]['c_pend'];$c_pend="'$c_pend'";
				if ($_POST['jns_pddk'.$nopend]){$parpend=$c_pend.",".$parpend;}				
			}
		if ($parpend){$parpend = substr($parpend,0,-1); $cari= $cari." and c_pend in ($parpend)";}		

		$jmleselonRef = count($this->eselonRef);
		for ($j = 0; $j < $jmleselonRef; $j++) 
			{
				$noesl++;
				$c_eselon=$this->eselonRef[$j]['c_eselon'];$c_eselon="'$c_eselon'";
				if ($_POST['jns_eselon'.$noesl]){$paresl=$c_eselon.",".$paresl;}				
			}			
		if ($paresl){$paresl = substr($paresl,0,-1); $cari= $cari." and c_eselon in ($paresl)";}	
		
		$jmlgolRef = count($this->golRef);
		for ($j = 0; $j < $jmlgolRef; $j++) 
			{
				$nogol++;
				$c_peg_golongan=$this->golRef[$j]['c_peg_golongan'];$c_peg_golongan="'$c_peg_golongan'";
				if ($_POST['jns_pangkat'.$nogol]){$pargol=$c_peg_golongan.",".$pargol;}				
			}
		if ($pargol){$pargol = substr($pargol,0,-1); $cari= $cari." and c_golongan in ($pargol)";}			

		$jmldikPimRef = count($this->dikPimRef);
		for ($j = 0; $j < $jmldikPimRef; $j++) 
			{
				$nopang++;
				$c_jenjang=$this->dikPimRef[$j]['c_jenjang'];$c_jenjang="'$c_jenjang'";
				if ($_POST['jns_pim'.$nopang]){$parpang=$c_jenjang.",".$parpang;}				
			}
		if ($parpang){$parpang = substr($parpang,0,-1); $cari= $cari." and c_penjenjangan in ($parpang)";}	


		$judul_lap=$_POST['judul_lap'];
		$s_nama=strtoupper($_POST['s_nama']);
		$s_nip=$_POST['s_nip'];
		$jns_sex=$_POST['jns_sex'];
		$bln_cpns=$_POST['bln_cpns'];
		$y_cpns=$_POST['y_cpns'];
		$sk_cpns=$_POST['sk_cpns'];
		$bln_pns=$_POST['bln_pns'];
		$y_pns=$_POST['y_pns'];
		$sk_pns=$_POST['sk_pns'];
		
		$n_jurusan=$_POST['n_jurusan'];
		$n_universitas=$_POST['n_universitas'];
		$y_lulus=$_POST['y_lulus'];
		$d_tmt_eselon=$_POST['d_tmt_eselon'];
		$d_tmt_golongan=$_POST['d_tmt_golongan'];
		$y_diklatpim=$_POST['y_diklatpim'];
		$propinsi_unit=$_POST['propinsi_unit'];
		$kd_jbt=$_POST['kd_jbt'];
		$kd_jbt2=$_POST['kd_jbt2'];
		$jabkelfungsional=$_POST['jabkelfungsional'];
		$d_tmt_jabatan=$_POST['d_tmt_jabatan'];
		
		$d_tgl_lahir=$_POST['d_tgl_lahir'];
		$usia_awl=$_POST['usia_awl'];
		$usia_akh=$_POST['usia_akh'];
		$tptlahir=$_POST['tptlahir'];
		$c_propinsi_lahir=$_POST['c_propinsi_lahir'];
		$a_peg_kota_lahir=$_POST['a_peg_kota_lahir'];
		$hobby=$_POST['hobby'];
		$kd_tlp=$_POST['kd_tlp'];
		$kd_pos=$_POST['kd_pos'];
		$w_kulit=$_POST['w_kulit'];
		$kd_karpeg=$_POST['kd_karpeg'];
		$kd_askes=$_POST['kd_askes'];
		$kd_taspen=$_POST['kd_taspen'];
		$kd_korpri=$_POST['kd_korpri'];
		$kd_ktp=$_POST['kd_ktp'];

		$c_jabatan_jabdiv=$_POST['c_jabatan_jabdiv'];
		
		//if($_POST['start']){$cari= $cari." and c_penjenjangan in ($parpang)";}
		//if($_POST['limit']){$cari= $cari." and c_penjenjangan in ($parpang)";}
		//if($_POST['judul_lap']){$cari= $cari." and c_penjenjangan in ($parpang)";}
		//if($_POST['s_nama']){$cari= $cari." and upper(n_peg) like '%$s_nama%'";}
		
		
	 if($s_nama)
	 {
		$cari .=" AND (";
		$between_s_nama = explode(",",$s_nama);
		for ($s=0;$s < count ($between_s_nama); $s++)
		{
			$namas = $between_s_nama[$s];
			$sambung ="";
			if ( $s < count ($between_s_nama) && ($between_s_nama[$s+1] != "") )
			$sambung="or";
			$cari .=" upper(n_peg) like '%".strtoupper($namas)."%' $sambung";
		}
		$cari .=") ";
	}
	
		
		
		
		//if($_POST['s_nip']){$cari= $cari." and i_peg_nip = '$s_nip'";}

		if($s_nip){
				$q_nip = $_POST['q_nip'];
				$cari .=" AND (";
				$between_nip = explode("-",$s_nip);
	         for ($s=0;$s < count ($between_nip); $s++)
	         {    $nips = $between_nip[$s];
	             $sambung ="";
	            if ( $s < count ($between_nip) && ($between_nip[$s+1] != "") )
	              $sambung="or";
	        	$cari .=" (i_peg_nip like '%$nips%' or i_peg_nip_new like '%$nips%') $sambung";
	         }
				 $cari .=") ";
		}
		
		$jns_sex=trim($jns_sex);
		if($_POST['jns_sex']){$cari= $cari." and c_peg_jeniskelamin ='$jns_sex'";}
		if($_POST['bln_cpns']){$cari= $cari." and to_char(d_tmt_cpns,'m') ='$bln_cpns'";}
		if($_POST['y_cpns']){$cari= $cari." and to_char(d_tmt_cpns,'yyyy') ='$y_cpns'";}
		if($_POST['sk_cpns']){$cari= $cari." and i_sk_cpns  like '%$sk_cpns%'";}
		if($_POST['sk_pns']){$cari= $cari." and i_sk_pns  like '%$sk_pns%'";}
		
		if($_POST['bln_pns']){$cari= $cari." and to_char(d_sk_pns,'m') ='$bln_pns'";}
		if($_POST['y_pns']){$cari= $cari." and to_char(d_tmt_pns,'yyyy') ='$y_pns'";}
		if($_POST['y_lulus']){$cari= $cari." and d_pend_akhir ='$y_lulus'";}
		if($n_jurusan) {$cari= $cari." and lower(n_pend_jurusan) like '%".strtolower($n_jurusan)."%'";}
		if($n_universitas) {$cari= $cari." and lower(n_pend_lembaga) like '%".strtolower($n_universitas)."%'";}
		
		if($_POST['d_tmt_eselon']){$cari= $cari." and to_char(d_tmt_eselon,'dd-mm-yyyy') ='$d_tmt_eselon'";}
		if($_POST['d_tmt_golongan']){$cari= $cari." and to_char(d_tmt_golongan,'dd-mm-yyyy') ='$d_tmt_golongan'";}
		if($_POST['y_diklatpim']){$cari= $cari." and q_tahun = '$y_diklatpim'";} 
		//if($_POST['propinsi_unit']){$cari= $cari." and c_penjenjangan =";} === blm ada di tabel
		if($_POST['kd_jbt']){$cari= $cari." and c_eselon ='$kd_jbt'";}
		if($_POST['c_jabatan_jabdiv']){$cari= $cari." and c_jabatan ='$c_jabatan_jabdiv'";}
		if($_POST['jabkelfungsional']){$cari= $cari." and c_kelfgs = '$jabkelfungsional'";} 
		if($_POST['d_tmt_jabatan']){$cari= $cari." and to_char(d_mulai_jabat,'dd-mm-yyyy') ='$d_tmt_jabatan'";}
		if($_POST['jns_nikah']){$cari= $cari." and c_peg_statusnikah ='$jns_nikah'";}
		

		if ($_POST['jns_nikah1'] || $_POST['jns_nikah2'] || $_POST['jns_nikah3'] || $_POST['jns_nikah4'])
		{
			for ($j = 0; $j < 4; $j++) 
				{
					$nonikah++;
					if ($_POST['jns_nikah'.$nonikah]){$c_nikah=$_POST['jns_nikah'.$nonikah]; $c_nikah="'$c_nikah'"; $parnikah=$c_nikah.",".$parnikah;}				
				}
			if ($parnikah){$parnikah = substr($parnikah,0,-1); $cari= $cari." and c_peg_statusnikah in ($parnikah)";}		
		}

		
		if($_POST['d_tgl_lahir']){$cari= $cari." and to_char(d_peg_lahir,'dd-mm-yyyy') ='$d_tgl_lahir'";}
		$tglc = date('d-m-Y');
		
		if($_POST['usia_awl'] && $_POST['usia_akh'] == ''){$cari= $cari."  and (EXTRACT(years from AGE('$tglc', d_peg_lahir)) = $usia_awl)";}
		else if($_POST['usia_akh'] && $_POST['usia_awl']){$cari= $cari." and (EXTRACT(years from AGE('$tglc', d_peg_lahir)) between $usia_awl and $usia_akh)";}
		//if($_POST['tptlahir']){$cari= $cari." and c_penjenjangan =";}  === blm ada di tabel
		if($_POST['c_propinsi_lahir']){$cari= $cari." and c_peg_propinsi_lahir = '$c_propinsi_lahir'";}
		if($_POST['a_peg_kota_lahir']){$cari= $cari." and a_peg_kota_lahir ='$a_peg_kota_lahir'";}
		if($_POST['hobby']){$hobby=strtoupper($hobby);$cari= $cari." and upper(n_peg_hobi) like '%$hobby%'";}
		//echo $cari;
		if($_POST['kd_tlp']){$cari= $cari." and i_peg_telponrumah like '%$kd_tlp%'";}
		if($_POST['kd_pos']){$cari= $cari." and a_peg_kodepos like '%$kd_pos%'";}
		if($_POST['w_kulit']){$w_kulit=strtoupper($w_kulit);$cari= $cari." and upper(n_peg_warnakulit) like '%$w_kulit%'";}
		if($_POST['kd_karpeg']){$cari= $cari." and i_peg_karpeg like '%$kd_karpeg%'";}
		if($_POST['kd_askes']){$cari= $cari." and i_peg_askes like '%$kd_askes%'";}
		if($_POST['kd_taspen']){$cari= $cari." and i_peg_taspen like '%$kd_taspen%'";}
		if($_POST['kd_korpri']){$cari= $cari." and i_peg_korpri like '%$kd_korpri%'";}
		if($_POST['kd_ktp']){$cari= $cari." and i_peg_ktp like '%$kd_ktp%'";}
		
		//Unit Kerja=====================
/* 		if (trim($_POST['c_lokasi_unitkerja']))
			{ $c_lokasi_unitkerja=trim($_POST['c_lokasi_unitkerja']);
				$cari= $cari." and c_lokasi_unitkerja ='$c_lokasi_unitkerja'";}
		
		if  ($_POST['c_eselon_i'])
			{$c_eselon_i=substr($_POST['c_eselon_i'],0,2);
				$cari= $cari." and c_eselon_i ='$c_eselon_i'";}
		
		if  ($_POST['c_eselon_ii'])
			{$c_eselon_ii=substr($_POST['c_eselon_ii'],0,2);
				$cari= $cari." and c_eselon_ii ='$c_eselon_ii'";}	
	
		if  ($_POST['c_eselon_iii'])
			{$c_eselon_iii=substr($_POST['c_eselon_iii'],0,2);
				$cari= $cari." and c_eselon_iii ='$c_eselon_iii'";}		

		if  ($_POST['c_eselon_iv'])
			{$c_eselon_iv=substr($_POST['c_eselon_iv'],0,2);
				$cari= $cari." and c_eselon_iv ='$c_eselon_iv'";}

		if  ($_POST['c_eselon_v'])
			{$c_eselon_v=substr($_POST['c_eselon_v'],0,2);
				$cari= $cari." and c_eselon_v ='$c_eselon_v'";} */
				
		$c_lokasi_unitkerja=$_POST['c_lokasi_unitkerja'];	
				
		if ($c_lokasi_unitkerja=='1'){
			
			if ($_POST['c_eselon_i']){$c_eselon_i=$_POST['c_eselon_i'];}
			else{$c_eselon_i=$_GET['c_eselon_i'];}
			
			$c_eselon_i=substr($c_eselon_i,0,2);
			$n_eselon_i=substr($_POST['c_eselon_i'],2,200);
			$cari .= " and c_lokasi_unitkerja='$c_lokasi_unitkerja'";
			if ($_POST['c_eselon_i'] || $_GET['c_eselon_i']){$cari .= " and c_eselon_i='$c_eselon_i'";}

			if ($_POST['c_eselon_ii']){$c_eselon_ii=$_POST['c_eselon_ii'];}
			else{$c_eselon_ii=$_GET['c_eselon_ii'];}
			$c_eselon_ii=substr($c_eselon_ii,0,3);
			$n_eselon_ii=substr($_POST['c_eselon_ii'],2,200);
			if ($_POST['c_eselon_ii'] || $_GET['c_eselon_ii']){$cari .= "and c_eselon_ii='$c_eselon_ii' ";}
			
			if ($_POST['c_eselon_iii']){$c_eselon_i=$_POST['c_eselon_iii'];}
			else{$c_eselon_iii=$_GET['c_eselon_iii'];}
			
			
			$c_eselon_iii=substr($c_eselon_iii,0,2);
			$n_eselon_iii=substr($_POST['c_eselon_iii'],2,200);
			if ($_POST['c_eselon_iii'] || $_GET['c_eselon_iii']){$cari .= " and c_eselon_iii='$c_eselon_iii'";}

			if ($_POST['c_eselon_iv']){$c_eselon_iv=$_POST['c_eselon_iv'];}
			else{$c_eselon_iv=$_GET['c_eselon_iv'];}
			
			
			$c_eselon_iv=substr($c_eselon_iv,0,2);
			$n_eselon_iv=substr($_POST['c_eselon_iv'],2,200);
			if ($_POST['c_eselon_iv'] || $_GET['c_eselon_iv']){$cari .= " and c_eselon_iv='$c_eselon_iv' ";}
		

			$this->view->c_eselon_i = $c_eselon_i;

		}
		else
		{


			
			$c_lokasi_unitkerja=trim($_POST['c_lokasi_unitkerja']);
			if(trim($c_lokasi_unitkerja) != '') $cari .= " and c_lokasi_unitkerja='$c_lokasi_unitkerja'";
			
			$c_eselon_i=$_POST['c_eselon_i'];
			if ($c_eselon_i){
				$expesl1 = explode(";",$c_eselon_i);
				$c_eselon_i=$expesl1[0];
				$n_eselon_i=$expesl1[1];
				$cari .= " and c_eselon_i='$c_eselon_i'";
			}
			
			
			$c_eselon_iii=$_POST['c_eselon_iii'];
			if ($c_eselon_iii){
				$expesl3 = explode(";",$c_eselon_iii);	
				$c_eselon_iix=$expesl3[0];
				$c_eselon_iii=$expesl3[1];
				$c_satker=$expesl3[2];
				$n_eselon_iii=$expesl3[3];
				$cari .= " and c_eselon_ii='$c_eselon_iix'";
				$cari .= " and c_eselon_iii='$c_eselon_iii'";	
			}

			$c_eselon_ii=$_POST['c_eselon_ii'];
			if ($c_eselon_ii && !$c_eselon_iix){
				$expesl2 = explode(";",$c_eselon_ii);
				$c_eselon_ii=$expesl2[0];
				$c_parent=$expesl2[1];
				$n_eselon_ii=$expesl2[2];
				$cari .= " and c_eselon_ii='$c_eselon_ii'";
			}			

			$c_eselon_iv=$_POST['c_eselon_iv'];
			if ($c_eselon_iv){
				$expesl4 = explode(";",$c_eselon_iv);	
				$c_eselon_iv=$expesl4[0];
				$n_eselon_iv=$expesl4[1];
				$cari .= " and c_eselon_iv='$c_eselon_iv'";

			}
		}				
			
		//echo $cari;
		$start=$_POST['start'];
		$limit=$_POST['limit'];		
		$currentPage=$_GET['currentPage'];
		if((!$currentPage) || ($currentPage == 'undefined'))
			{$currentPage = $start;}	
			if (!$limit){$limit=$_GET['limit'];}
			$numToDisplay = $limit;
			
			$this->view->numToDisplay = $numToDisplay;
			$this->view->currentPage = $currentPage;
			$this->view->limit=$numToDisplay;		
		
		$this->view->cari=$cari;		
$html="";
$cari .= " and (c_eselon !='17' or c_eselon isnull)";
		$totalpegawaiList = $this->pegawai_serv->getPegawaiList($cari, 0, 0 ,$orderBy);	
		$this->view->totalpegawaiList=$totalpegawaiList;		
		$pegawaiList = $this->pegawai_serv->getPegawaiList($cari,$currentPage,$numToDisplay,$orderBy );
$jdllap=$_POST['judul_lap'];		
$html='<h2 class="title">'.$jdllap.'</h2>
<div style="height: 490px; overflow: auto; padding: 5px">';
 $html=$html.'
  <table align="center" border="0" cellspacing="1" cellpadding="2" class="sortable">
		<tr>
			<th width="5%" rowspan="2">No.</th>
			<th rowspan="2">&nbsp;</th>
			<th rowspan="2">Nama</th>
			<th rowspan="2">Jabatan</th>
			<th rowspan="2">NIP</th>
			<th rowspan="2">Unit Kerja</th>
			<th rowspan="2">Gol / Pangkat</th>
			<th colspan="3">TMT</th>
			<th rowspan="2">Tanggal Lahir</th>
			<th rowspan="2">Umur</th>
			<th rowspan="2">Pendidikan</th>
			<th rowspan="2">Dtype</th>
		</tr> 
		<tr>
			<th>Gol</th>
			<th>Jabatan</th>
			<th>CPNS</th>			
		</tr>';	
 		if ($totalpegawaiList == 0) {
 $html=$html.'		
		<tr class="event">
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>';	
}else{
			for ($j = 0; $j < count($pegawaiList); $j++) 
			{
				if ($key%2==0) { 
 $html=$html.'				<tr class="event">';
				 } else if ($key%2==1) { 
 $html=$html.'				<tr class="event2">';
				 } 
					 $noUrut = (($currentPage -1)* $numToDisplay) +$j+1;
					$pangkat = $pegawaiList[$j]['n_pangkat'];
					$golongan = $pegawaiList[$j]['n_golongan'];
					$golPangkat = $golongan." - ".$pangkat;
					$n_lokasi_unitkerja=$pegawaiList[$j]['n_lokasi_unitkerja'];
					$n_eselon=$pegawaiList[$j]['n_eselon'];
					$n_jabatan=$pegawaiList[$j]['n_jabatan'];
					$tmtkgb = $pegawaiList[$j]['d_tmt_kgb'];
					$d_tmt_golongan = $pegawaiList[$j]['d_tmt_golongan'];
					$d_mulai_jabat = $pegawaiList[$j]['d_mulai_jabat'];
					$d_tmt_cpns = $pegawaiList[$j]['d_tmt_cpns'];
					$d_peg_lahir = $pegawaiList[$j]['d_peg_lahir2'];
					$i_peg_nip=$pegawaiList[$j]['i_peg_nip_new'];
					$usiatahun=$pegawaiList[$j]['usiatahun'];
					$usiabulan=$pegawaiList[$j]['usiabulan'];
					$n_peg=$pegawaiList[$j]['n_peg'];
					if ($usiatahun && $usiatahun!='0'){
					if ($usiabulan && $usiabulan!='0'){
					$Usia="$usiatahun tahun $usiabulan bulan";
					}
					else{$Usia="$usiatahun tahun";
					}
					}
					$n_status_kepegawaian="";
					$n_pendidikan=$pegawaiList[$j]['n_pendidikan'];
					if($pegawaiList[$j]['c_peg_status']=='MIL'){$n_status_kepegawaian="/".$pegawaiList[$j]['n_status_kepegawaian'];}
 $html=$html.'				
					<td class="clcenter">'.$noUrut.'</td>
					
					<td>';
						
						$vFoto="";
						$vFoto = $this->basePath."/upld/Uploaddata/getphoto?f=".trim($pegawaiList[$j]['e_file_photo']);
 $html=$html.'						
						<img src="'.$vFoto.'" width="55" height="60" >
					</td>
					<td>'.$n_peg.'</td>
					<td>'.$n_jabatan.'</td>
					<td>'.$i_peg_nip.'</td>
					<td>'.$n_lokasi_unitkerja.'</td>
					<td>'.$golPangkat.'</td>
					<td>'.$d_tmt_golongan.'</td>
					<td>'.$d_mulai_jabat.'</td>
					<td>'.$d_tmt_cpns.'</td>
					<td>'.$d_peg_lahir.'</td>
					<td>'.$Usia.'</td>
					<td>'.$n_pendidikan.'</td>
					<td></td>';
					
					
					
					
				 }
				 }
	
 $html=$html.'	</table>';
	
/* $html=$html.' <table align="center" border="0" cellspacing="1" cellpadding="2" class="sortable">
		<tr>
			<th>No.</th>
			<th>NIP</th>
			<th>Nama</th>
		</tr>';
if (count($pegawaiList)==0)
{
$html=$html.' <tr class="event2">
			<td>&nbsp</td>
			<td>&nbsp</td>
			<td>&nbsp</td>
		</tr>';

}		
if (count($pegawaiList)!=0){
		for ($j = 0; $j < count($pegawaiList); $j++) 
			{
				$i_peg_nip=$pegawaiList[$j]['i_peg_nip'];
				$n_peg=$pegawaiList[$j]['n_peg'];
				$no++;	
				if ($j%2==0) {
				      $html=$html.'<tr class="event">';
				 } else if ($j%2==1) { 
					  $html=$html.'<tr class="event2">';
				 }
				 $html=$html.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$no.'</font></td>
						<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$i_peg_nip.'</font></td>
						<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$n_peg.'</font></td>
						</tr>';				 
			}
		} */

		
$html=$html.'</table></div>';

$dt_tpl2_get=$_POST['dt_tpl2_get'];
if (!$dt_tpl2_get){
	$this->view->view=$html;
}
else{
				
$html2="";
$html2='<h2 class="title">'.$jdllap.'</h2>';
$html2=$html2.'<div style="width: 1000px; height: 470px; overflow: auto; padding: 5px">
<table align="center" border="0" cellspacing="1" cellpadding="2" class="sortable">
			<tr>
				<th>No</th>';
$wordChunksLimited = explode(" :: ", $dt_tpl2_get);
for($i = 0; $i < count($wordChunksLimited); $i++)
{
	if ($wordChunksLimited[$i]){$html2=$html2.'<th>'.$wordChunksLimited[$i].'</th>';}
}
			
$no=0;
$html2=$html2.'</tr><tr>';
		for ($j = 0; $j < count($pegawaiList); $j++) 
			{
				$no++;	
				if ($j%2==0) {
				      $html2=$html2.'<tr class="event">';
				 } else if ($j%2==1) { 
					  $html2=$html2.'<tr class="event2">';
				 }
					$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$no.'</font></td>';
					$nip="";
					$nip=trim($pegawaiList[$j]['i_peg_nip']);
					for($i = 0; $i < count($wordChunksLimited); $i++)
						{
//						echo $wordChunksLimited[$i]."<br>";
							if (trim($wordChunksLimited[$i])=='Nama/NIP'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_peg'].'<br>'.$pegawaiList[$j]['i_peg_nip'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='Gelar depan'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_peg_gelardepan'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='Nama Tp. Gelar'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$i_peg_nipx.'</font></td>';}
							if (trim($wordChunksLimited[$i])=='Gelar Belakang'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_peg_gelarblkg'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='Nama Lengkap'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_peg'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='NIP Baru'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['i_peg_nip_new'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='NIP Lama'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['i_peg_nip'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='Pangkat/Gol & TMT'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['npangkat'].'<br>'.$pegawaiList[$j]['d_mulai_jabat'].' </font></td>';}
							if (trim($wordChunksLimited[$i])=='Jabatan & TMT'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_jabatan'].'<br>'.$pegawaiList[$j]['d_mulai_jabat'].' </font></td>';}
							if (trim($wordChunksLimited[$i])=='Eselon & TMT'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_eselon'].'<br>'.$pegawaiList[$j]['d_tmt_eselon'].' </font></td>';}
							if (trim($wordChunksLimited[$i])=='Pendidikan'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_pendidikan'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='Fak/Jurusan'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_pend_jurusan'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='Thn. Lulus'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['d_pend_akhir'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='Lembaga Pendidikan'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_eselon'].'<br>'.$pegawaiList[$j]['d_tmt_eselon'].' </font></td>';}
							if (trim($wordChunksLimited[$i])=='Kelamin'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_peg_jeniskelamin'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='Status Kawin'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_peg_statusnikah'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='Agama'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_agama'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='Gol. Darah'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['c_golongan_darah'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='Tempat Lahir'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_peg_propinsi_lahir'].'<br>'.$pegawaiList[$j]['n_peg_kota_lahir'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='Tanggal Lahir'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['d_peg_lahir'].' </font></td>';}
							if (trim($wordChunksLimited[$i])=='Pangkat/Gol CPNS & TMT'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_pangkat_cpns'].'/'.$pegawaiList[$j]['c_gol_cpns'].'<br>'.$pegawaiList[$j]['d_tmt_cpns'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='TMT PNS'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['d_sk_pns'].'</font></td>';}
							//if (trim($wordChunksLimited[$i])=='Masa Kerja'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_eselon'].'<br>'.$pegawaiList[$j]['d_tmt_eselon'].' </font></td>';}
							//if (trim($wordChunksLimited[$i])=='Usia'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_eselon'].'<br>'.$pegawaiList[$j]['d_tmt_eselon'].' </font></td>';}
							if (trim($wordChunksLimited[$i])=='Alamat Rumah'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['a_peg_rumah'].'</font></td>';}
							//if (trim($wordChunksLimited[$i])=='Alamat Kantor'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_eselon'].'<br>'.$pegawaiList[$j]['d_tmt_eselon'].' </font></td>';}
							//if (trim($wordChunksLimited[$i])=='SK Pangkat'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_eselon'].'<br>'.$pegawaiList[$j]['d_tmt_eselon'].' </font></td>';}
							//if (trim($wordChunksLimited[$i])=='SK Jabatan'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_eselon'].'<br>'.$pegawaiList[$j]['d_tmt_eselon'].' </font></td>';}
							//if (trim($wordChunksLimited[$i])=='Unit Kerja'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_eselon'].'<br>'.$pegawaiList[$j]['d_tmt_eselon'].' </font></td>';}
							if (trim($wordChunksLimited[$i])=='Foto'){
							$e_file_photo=$pegawaiList[$j]['e_file_photo'];
							$vFoto = $this->basePath."/upld/Uploaddata/getphoto?f=".$e_file_photo;
							$html2=$html2.'
								<td align="center">
									<img src="'.$vFoto.'" width="40" height="50" >
								</td>';
							}
							if (trim($wordChunksLimited[$i])=='Riwayat Pendidikan'){
								$pendList="";
								$pendList = $this->pendidikan_serv->getPendidikanList(" and i_peg_nip ='$nip'");								
								if (count($pendList)!=0){
									for($xpend = 0; $xpend < count($pendList); $xpend++)
									{
										$pendidikan=$pendList[$xpend]['n_pend']."  ".$pendList[$xpend]['d_pend_mulai']."  ".$pendList[$xpend]['n_pend_lembaga']."<br>";
										$penddetil=$penddetil.$pendidikan;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$penddetil.'</font></td>';
							
							}
							if (trim($wordChunksLimited[$i])=='Riwayat Jabatan'){
								$jabatanList="";
								$jabatan="";
								$jabatandetil="";
								$jabatanList = $this->jabatan_serv->getJabatanList(" and i_peg_nip ='$nip'");								
								if (count($jabatanList)!=0){
									for($xjabatan = 0; $xjabatan < count($jabatanList); $xjabatan++)
									{
										$jabatan=$jabatanList[$xjabatan]['n_jabatan']." pada ".$jabatanList[$xjabatan]['unitkerjalengkap']."  ".$jabatanList[$xjabatan]['d_mulai_jabat']."<br>";
										$jabatandetil=$jabatandetil.$jabatan;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$jabatandetil.'</font></td>';
							
							}						
							if (trim($wordChunksLimited[$i])=='TMT & Jabatan yang sesuai pada Riw. Jabatan'){
								$jabatanList="";
								$jabatan="";
								$jabatandetil="";
								$jabatanList = $this->jabatan_serv->getJabatanList(" and i_peg_nip ='$nip'");								
								if (count($jabatanList)!=0){
									for($xjabatan = 0; $xjabatan < count($jabatanList); $xjabatan++)
									{
										$jabatan=$jabatanList[$xjabatan]['d_mulai_jabat']." s/d ".$jabatanList[$xjabatan]['d_akhir_jabat']."<br>";
										$jabatandetil=$jabatandetil.$jabatan;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$jabatandetil.'</font></td>';
							
							}	
							if (trim($wordChunksLimited[$i])=='Riwayat Pangkat'){
								$pangkatList="";
								$pangkat="";
								$pangkatdetil="";
								$pangkatList = $this->pangkat_serv->getPangkatList(" and i_peg_nip ='$nip'");								
								if (count($pangkatList)!=0){
									for($xpangkat = 0; $xpangkat < count($pangkatList); $xpangkat++)
									{
										$pangkat=$pangkatList[$xpangkat]['c_golongan']."/".$pangkatList[$xpangkat]['n_pangkat']."  ".$pangkatList[$xpangkat]['d_tmt_golongan']."<br>";
										$pangkatdetil=$pangkatdetil.$pangkat;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pangkatdetil.'</font></td>';
							
							}
							if (trim($wordChunksLimited[$i])=='Riwayat Organisasi'){
								$organisasiList="";
								$organisasi="";
								$organisasidetil="";
								$organisasiList = $this->organisasi_serv->getOrganisasiList(" and i_peg_nip ='$nip'");								
								if (count($organisasiList)!=0){
									for($xorganisasi = 0; $xorganisasi < count($organisasiList); $xorganisasi++)
									{
										$organisasi=$organisasiList[$xorganisasi]['n_organisasi']." ".$organisasiList[$xorganisasi]['n_peran_organisasi']." s/d ".$organisasiList[$xorganisasi]['d_daftar_organisasi']."<br>";
										$organisasidetil=$organisasidetil.$organisasi;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$organisasidetil.'</font></td>';
							
							}
							if (trim($wordChunksLimited[$i])=='Riwayat Kunjungan'){
								$luarnegeriList="";
								$luarnegeri="";
								$luarnegeridetil="";
								$luarnegeriList = $this->luarnegeri_serv->getLuarnegeriList(" and i_peg_nip ='$nip'");								
								if (count($luarnegeriList)!=0){
									for($xluarnegeri = 0; $xluarnegeri < count($luarnegeriList); $xluarnegeri++)
									{
										$luarnegeri=$luarnegeriList[$xluarnegeri]['n_negara']." ".$luarnegeriList[$xluarnegeri]['a_tujuan']." ".$luarnegeriList[$xluarnegeri]['n_biaya']." ".$luarnegeriList[$xluarnegeri]['d_barangkat']."<br>";
										$luarnegeridetil=$luarnegeridetil.$luarnegeri;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$luarnegeridetil.'</font></td>';
							
							}

							if (trim($wordChunksLimited[$i])=='Riwayat Seminar'){
								$seminarList="";
								$seminar="";
								$seminardetil="";
								$seminarList = $this->seminar_serv->getSeminarList(" and i_peg_nip ='$nip'");								
								if (count($seminarList)!=0){
									for($xseminar = 0; $xseminar < count($seminarList); $xseminar++)
									{
										$seminar=$seminarList[$xseminar]['n_seminar']." ".$seminarList[$xseminar]['n_seminar_peran']." ".$seminarList[$xseminar]['d_mulai_seminar']."<br>";
										$seminardetil=$seminardetil.$seminar;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$seminardetil.'</font></td>';
							
							}
							if (trim($wordChunksLimited[$i])=='Riwayat Penghargaan'){
								$penghargaanList="";
								$penghargaan="";
								$penghargaandetil="";
								$penghargaanList = $this->penghargaan_serv->getpenghargaanList(" and i_peg_nip ='$nip'");								
								if (count($penghargaanList)!=0){
									for($xpenghargaan = 0; $xpenghargaan < count($penghargaanList); $xpenghargaan++)
									{
										$penghargaan=$penghargaanList[$xpenghargaan]['jenispenghargaan']." ".$penghargaanList[$xpenghargaan]['namapenghargaan']." ".$penghargaanList[$xpenghargaan]['d_tahun_alteratif']."<br>";
										$penghargaandetil=$penghargaandetil.$penghargaan;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$penghargaandetil.'</font></td>';
							
							}
							if (trim($wordChunksLimited[$i])=='Riwayat Sanksi'){
								$hukumanList="";
								$hukuman="";
								$hukumandetil="";
								$hukumanList = $this->hukuman_serv->getHukumanList(" and i_peg_nip ='$nip'");								
								if (count($hukumanList)!=0){
									for($xhukuman = 0; $xhukuman < count($hukumanList); $xhukuman++)
									{
										$hukuman=$hukumanList[$xhukuman]['nsanksi']." ".$hukumanList[$xhukuman]['jnssanksi']." ".$hukumanList[$xhukuman]['e_alasan_sanksi']." ".$hukumanList[$xhukuman]['d_mulai_sanksi']."<br>";
										$hukumandetil=$hukumandetil.$hukuman;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$hukumandetil.'</font></td>';
							
							}							
							if (trim($wordChunksLimited[$i])=='Data Angka Kredit'){
								$angkakreditList="";
								$angkakredit="";
								$angkakreditdetil="";
								$angkakreditList = $this->angkakredit_serv->getAngkaKreditList(" and i_peg_nip ='$nip'");								
								if (count($angkakreditList)!=0){
									for($xangkakredit = 0; $xangkakredit < count($angkakreditList); $xangkakredit++)
									{
										$angkakredit="Utama : ".$angkakreditList[$xangkakredit]['q_totalnilai']." Penunjang : ".$angkakreditList[$xangkakredit]['q_penunjang']."<br>";
										$angkakreditdetil=$angkakreditdetil.$angkakredit;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$angkakreditdetil.'</font></td>';
							
							}
							if (trim($wordChunksLimited[$i])=='Data Pasangan'){
								$pasanganList="";
								$pasangan="";
								$pasangandetil="";
								$pasanganList = $this->pasangan_serv->getPasanganList(" and i_peg_nip ='$nip'");								
								if (count($pasanganList)!=0){
									for($xpasangan = 0; $xpasangan < count($pasanganList); $xpasangan++)
									{
										$pasangan=$pasanganList[$xpasangan]['n_nama']."  ".$pasanganList[$xpasangan]['n_pekerjaan']."<br>";
										$pasangandetil=$pasangandetil.$pasangan;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pasangandetil.'</font></td>';
							
							}
							if (trim($wordChunksLimited[$i])=='Data Anak'){
								$anakList="";
								$anak="";
								$anakdetil="";
								$anakList = $this->anak_serv->getAnakList(" and i_peg_nip ='$nip'");								
								if (count($anakList)!=0){
									for($xanak = 0; $xanak < count($anakList); $xanak++)
									{
										$anak=$anakList[$xanak]['n_nama']." ".$anakList[$xanak]['n_jns_kel']." ".$anakList[$xanak]['n_pekerjaan']."<br>";
										$anakdetil=$anakdetil.$anak;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$anakdetil.'</font></td>';
							
							}
							if (trim($wordChunksLimited[$i])=='Data Orang Tua'){
								$ortuList="";
								$ortu="";
								$ortudetil="";
								$ortuList = $this->kerabat_serv->getKerabatList(" and i_peg_nip ='$nip' and c_kerabat in ('1','2','3','4')");								
								if (count($ortuList)!=0){
									for($xortu = 0; $xortu < count($ortuList); $xortu++)
									{
										$ortu=$ortuList[$xortu]['n_kerabat']." ".$ortuList[$xortu]['n_nama']." ".$ortuList[$xortu]['n_pekerjaan']."<br>";
										$ortudetil=$ortudetil.$ortu;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$ortudetil.'</font></td>';
							
							}							
							if (trim($wordChunksLimited[$i])=='Data Saudara'){
								$sdrList="";
								$sdr="";
								$sdrdetil="";
								$sdrList = $this->kerabat_serv->getKerabatList(" and i_peg_nip ='$nip' and c_kerabat in ('5')");								
								if (count($sdrList)!=0){
									for($xsdr = 0; $xsdr < count($sdrList); $xsdr++)
									{
										$sdr=$sdrList[$xsdr]['n_kerabat']." ".$sdrList[$xsdr]['n_nama']." ".$sdrList[$xsdr]['n_pekerjaan']."<br>";
										$sdrdetil=$sdrdetil.$sdr;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$sdrdetil.'</font></td>';
							
							}								
							if (trim($wordChunksLimited[$i])=='Riwayat Diklat Pim'){
								$penjenjanganList="";
								$penjenjangan="";
								$penjenjangandetil="";
								$penjenjanganList = $this->penjenjangan_serv->getPelatihanList(" and i_peg_nip ='$nip'");								
								if (count($penjenjanganList)!=0){
									for($xpenjenjangan = 0; $xpenjenjangan < count($penjenjanganList); $xpenjenjangan++)
									{
										$penjenjangan=$penjenjanganList[$xpenjenjangan]['n_penjenjangan']." ".$penjenjanganList[$xpenjenjangan]['q_tahun']."<br>";
										$penjenjangandetil=$penjenjangandetil.$penjenjangan;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$penjenjangandetil.'</font></td>';
							
							}							
							if (trim($wordChunksLimited[$i])=='Riwayat Diklat Fungsional'){
								$fungsionalList="";
								$fungsional="";
								$fungsionaldetil="";
								$fungsionalList = $this->fungsional_serv->getPelatihanList(" and i_peg_nip ='$nip'");								
								if (count($fungsionalList)!=0){
									for($xfungsional = 0; $xfungsional < count($fungsionalList); $xfungsional++)
									{
										$fungsional=$fungsionalList[$xfungsional]['n_jns_fungsional']." ".$fungsionalList[$xfungsional]['n_kel_pelatihan']." ".$fungsionalList[$xfungsional]['q_pelatihan']."<br>";
										$fungsionaldetil=$fungsionaldetil.$fungsional;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$fungsionaldetil.'</font></td>';
							
							}
							if (trim($wordChunksLimited[$i])=='Riwayat Diklat Teknis'){
								$teknisList="";
								$teknis="";
								$teknisdetil="";
								$teknisList = $this->teknis_serv->getPelatihanList(" and i_peg_nip ='$nip'");								
								if (count($teknisList)!=0){
									for($xteknis = 0; $xteknis < count($teknisList); $xteknis++)
									{
										$teknis=$teknisList[$xteknis]['n_kelompok']." ".$teknisList[$xteknis]['n_diklat']." ".$teknisList[$xteknis]['q_lama']."<br>";
										$teknisdetil=$teknisdetil.$teknis;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$teknisdetil.'</font></td>';
							
							}
							if (trim($wordChunksLimited[$i])=='Riwayat Diklat Lain'){
								$diklainList="";
								$diklain="";
								$diklaindetil="";
								$diklainList = $this->diklain_serv->getPelatihanList(" and i_peg_nip ='$nip'");								
								if (count($diklainList)!=0){
									for($xdiklain = 0; $xdiklain < count($diklainList); $xdiklain++)
									{
										$diklain=$diklainList[$xdiklain]['n_diklat']." ".$diklainList[$xdiklain]['n_negara']." ".$diklainList[$xdiklain]['d_diklat']."<br>";
										$diklaindetil=$diklaindetil.$diklain;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$diklaindetil.'</font></td>';
							
							}							
							
						}
					
					
					$html2=$html2.'</tr>';				 
			}
		


$html2=$html2.'</table></div>';
$this->view->view=$html2;
$this->view->par=$_GET['par'];
}


$this->view->a_kota_lahir=$_POST['a_kota_lahir'];
$this->view->bln_cpns=$_POST['bln_cpns'];
$this->view->bln_pns=$_POST['bln_pns'];
$this->view->c_eselon_i=$_POST['c_eselon_i'];
$this->view->c_eselon_ii=$_POST['c_eselon_ii'];
$this->view->c_eselon_iii=$_POST['c_eselon_iii'];
$this->view->c_eselon_iv=$_POST['c_eselon_iv'];
$this->view->c_lokasi_unitkerja=$_POST['c_lokasi_unitkerja'];
$this->view->c_propinsi_lahir=$_POST['c_propinsi_lahir'];
$this->view->d_tgl_lahir=$_POST['d_tgl_lahir'];
$this->view->d_tmt_eselon=$_POST['d_tmt_eselon'];
$this->view->d_tmt_golongan=$_POST['d_tmt_golongan'];
$this->view->d_tmt_jabatan=$_POST['d_tmt_jabatan'];
$this->view->dt_tpl2_get=$_POST['dt_tpl2_get'];
$this->view->hobby=$_POST['hobby'];
$this->view->jabkelfungsional=$_POST['jabkelfungsional'];
$this->view->jns_sex=$_POST['jns_sex'];
$this->view->judul_lap=$_POST['judul_lap'];
$this->view->kd_askes=$_POST['kd_askes'];
$this->view->kd_jbt=$_POST['kd_jbt'];
$this->view->kd_jbt2=$_POST['kd_jbt2'];
$this->view->kd_karpeg=$_POST['kd_karpeg'];
$this->view->kd_korpri=$_POST['kd_korpri'];
$this->view->kd_ktp=$_POST['kd_ktp'];
$this->view->kd_pos=$_POST['kd_pos'];
$this->view->kd_taspen=$_POST['kd_taspen'];
$this->view->kd_tlp=$_POST['kd_tlp'];
$this->view->limit=$_POST['limit'];
$this->view->n_jurusan=$_POST['n_jurusan'];
$this->view->n_universitas=$_POST['n_universitas'];
$this->view->orderby2_get=$_POST['orderby2_get'];
$this->view->propinsi_unit=$_POST['propinsi_unit'];
$this->view->s_nama=$_POST['s_nama'];
$this->view->s_nip=$_POST['s_nip'];
$this->view->sk_cpns=$_POST['sk_cpns'];
$this->view->sk_pns=$_POST['sk_pns'];
$this->view->start=$_POST['start'];
$this->view->tptlahir=$_POST['tptlahir'];
$this->view->usia_akh=$_POST['usia_akh'];
$this->view->usia_awl=$_POST['usia_awl'];
$this->view->w_kulit=$_POST['w_kulit'];
$this->view->y_cpns=$_POST['y_cpns'];
$this->view->y_diklatpim=$_POST['y_diklatpim'];
$this->view->y_lulus=$_POST['y_lulus'];
$this->view->y_pns=$_POST['y_pns'];
$this->view->jns_agama3=$_POST['jns_agama3'];
$this->view->c_eselon_v=$_POST['c_eselon_v'];
$this->view->c_jabatan_jabdiv=$_POST['c_jabatan_jabdiv'];

}	




public function cetakviewhalAction(){

		$cari="";
		$jmldarahList = count($this->darahList);
		for ($j = 0; $j < $jmldarahList; $j++) 
			{
				$nodarah++;
				$c_golongan_darah=$this->darahList[$j]['c_golongan_darah'];$c_golongan_darah="'$c_golongan_darah'";
				if ($_GET['jns_darah'.$nodarah]){$pargold=$c_golongan_darah.",".$pargold;}				
			}
		if ($pargold){$pargold = substr($pargold,0,-1); $cari= " and c_golongan_darah in ($pargold)";}

		$jmlagamaRef = count($this->agamaRef);
		for ($j = 0; $j < $jmlagamaRef; $j++) 
			{
				$noagama++;
				$c_agama=$this->agamaRef[$j]['c_agama'];$c_agama="'$c_agama'";
				if ($_GET['jns_agama'.$noagama]){$paragama=$c_agama.",".$paragama;}				
			}
		if ($paragama){$paragama = substr($paragama,0,-1); $cari= $cari." and c_agama in ($paragama)";}		

		$jmlpendRef = count($this->pendRef);
		for ($j = 0; $j < $jmlpendRef; $j++) 
			{
				$nopend++;
				$c_pend=$this->pendRef[$j]['c_pend'];$c_pend="'$c_pend'";
				if ($_GET['jns_pddk'.$nopend]){$parpend=$c_pend.",".$parpend;}				
			}
		if ($parpend){$parpend = substr($parpend,0,-1); $cari= $cari." and c_pend in ($parpend)";}		

		$jmleselonRef = count($this->eselonRef);
		for ($j = 0; $j < $jmleselonRef; $j++) 
			{
				$noesl++;
				$c_eselon=$this->eselonRef[$j]['c_eselon'];$c_eselon="'$c_eselon'";
				if ($_GET['jns_eselon'.$noesl]){$paresl=$c_eselon.",".$paresl;}				
			}			
		if ($paresl){$paresl = substr($paresl,0,-1); $cari= $cari." and c_eselon in ($paresl)";}	
		
		$jmlgolRef = count($this->golRef);
		for ($j = 0; $j < $jmlgolRef; $j++) 
			{
				$nogol++;
				$c_peg_golongan=$this->golRef[$j]['c_peg_golongan'];$c_peg_golongan="'$c_peg_golongan'";
				if ($_GET['jns_pangkat'.$nogol]){$pargol=$c_peg_golongan.",".$pargol;}				
			}
		if ($pargol){$pargol = substr($pargol,0,-1); $cari= $cari." and c_golongan in ($pargol)";}			

		$jmldikPimRef = count($this->dikPimRef);
		for ($j = 0; $j < $jmldikPimRef; $j++) 
			{
				$nopang++;
				$c_jenjang=$this->dikPimRef[$j]['c_jenjang'];$c_jenjang="'$c_jenjang'";
				if ($_GET['jns_pim'.$nopang]){$parpang=$c_jenjang.",".$parpang;}				
			}
		if ($parpang){$parpang = substr($parpang,0,-1); $cari= $cari." and c_penjenjangan in ($parpang)";}	


		$judul_lap=$_GET['judul_lap'];
		$s_nama=strtoupper($_GET['s_nama']);
		$s_nip=$_GET['s_nip'];
		$jns_sex=$_GET['jns_sex'];
		$bln_cpns=$_GET['bln_cpns'];
		$y_cpns=$_GET['y_cpns'];
		$sk_cpns=$_GET['sk_cpns'];
		$bln_pns=$_GET['bln_pns'];
		$y_lulus=$_GET['y_lulus'];
		$d_tmt_eselon=$_GET['d_tmt_eselon'];
		$d_tmt_golongan=$_GET['d_tmt_golongan'];
		$y_diklatpim=$_GET['y_diklatpim'];
		$propinsi_unit=$_GET['propinsi_unit'];
		$kd_jbt=$_GET['kd_jbt'];
		$kd_jbt2=$_GET['kd_jbt2'];
		$jabkelfungsional=$_GET['jabkelfungsional'];
		$d_tmt_jabatan=$_GET['d_tmt_jabatan'];
		
		$d_tgl_lahir=$_GET['d_tgl_lahir'];
		$usia_awl=$_GET['usia_awl'];
		$usia_akh=$_GET['usia_akh'];
		$tptlahir=$_GET['tptlahir'];
		$c_propinsi_lahir=$_GET['c_propinsi_lahir'];
		$a_kota_lahir=$_GET['a_kota_lahir'];
		$hobby=$_GET['hobby'];
		$kd_tlp=$_GET['kd_tlp'];
		$kd_pos=$_GET['kd_pos'];
		$w_kulit=$_GET['w_kulit'];
		$kd_karpeg=$_GET['kd_karpeg'];
		$kd_askes=$_GET['kd_askes'];
		$kd_taspen=$_GET['kd_taspen'];
		$kd_korpri=$_GET['kd_korpri'];
		$kd_ktp=$_GET['kd_ktp'];
		$c_jabatan_jabdiv=$_POST['c_jabatan_jabdiv'];
		
		//if($_GET['start']){$cari= $cari." and c_penjenjangan in ($parpang)";}
		//if($_GET['limit']){$cari= $cari." and c_penjenjangan in ($parpang)";}
		//if($_GET['judul_lap']){$cari= $cari." and c_penjenjangan in ($parpang)";}
		//if($_GET['s_nama']){$cari= $cari." and upper(n_peg) like '%$s_nama%'";}
		
		
	 if($s_nama)
	 {
		$cari .=" AND (";
		$between_s_nama = explode(",",$s_nama);
		for ($s=0;$s < count ($between_s_nama); $s++)
		{
			$namas = $between_s_nama[$s];
			$sambung ="";
			if ( $s < count ($between_s_nama) && ($between_s_nama[$s+1] != "") )
			$sambung="or";
			$cari .=" upper(n_peg) like '%".strtoupper($namas)."%' $sambung";
		}
		$cari .=") ";
	}
	
		
		
		
		//if($_GET['s_nip']){$cari= $cari." and i_peg_nip = '$s_nip'";}

		if($s_nip){
				$q_nip = $_GET['q_nip'];
				$cari .=" AND (";
				$between_nip = explode("-",$s_nip);
	         for ($s=0;$s < count ($between_nip); $s++)
	         {    $nips = $between_nip[$s];
	             $sambung ="";
	            if ( $s < count ($between_nip) && ($between_nip[$s+1] != "") )
	              $sambung="or";
	        	$cari .=" (i_peg_nip like '%$nips%' or i_peg_nip_new like '%$nips%') $sambung";
	         }
				 $cari .=") ";
		}
		
		$jns_sex=trim($jns_sex);
		if($_GET['jns_sex']){$cari= $cari." and c_peg_jeniskelamin ='$jns_sex'";}
		if($_GET['bln_cpns']){$cari= $cari." and to_char(d_tmt_cpns,'m') ='$bln_cpns'";}
		if($_GET['y_cpns']){$cari= $cari." and to_char(d_tmt_cpns,'yyyy') ='$y_cpns'";}
		if($_GET['sk_cpns']){$cari= $cari." and i_sk_cpns like '%$sk_cpns%'";}
		if($_GET['bln_pns']){$cari= $cari." and to_char(d_sk_pns,'m') ='$bln_pns'";}
		if($_GET['y_lulus']){$cari= $cari." and to_char(d_sk_pns,'m') ='$y_lulus'";}
		if($_GET['d_tmt_eselon']){$cari= $cari." and to_char(d_tmt_eselon,'dd-mm-yyyy') ='$d_tmt_eselon'";}
		if($_GET['d_tmt_golongan']){$cari= $cari." and to_char(d_tmt_golongan,'dd-mm-yyyy') ='$d_tmt_golongan'";}
		//if($_GET['y_diklatpim']){$cari= $cari." and c_penjenjangan =";} === blm ada di tabel
		//if($_GET['propinsi_unit']){$cari= $cari." and c_penjenjangan =";} === blm ada di tabel
		//if($_GET['kd_jbt2']){$cari= $cari." and c_jabatan ='$kd_jbt2'";}
		if($_POST['kd_jbt']){$cari= $cari." and c_eselon ='$kd_jbt'";}
		if($_POST['c_jabatan_jabdiv']){$cari= $cari." and c_jabatan ='$c_jabatan_jabdiv'";}
		//if($_GET['jabkelfungsional']){$cari= $cari." and c_penjenjangan =";} === blm ada di tabel
		if($_GET['d_tmt_jabatan']){$cari= $cari." and to_char(d_tmt_jabatan,'dd-mm-yyyy') ='$d_tmt_jabatan'";}
		if($_GET['jns_nikah']){$cari= $cari." and c_peg_statusnikah ='$jns_nikah'";}
		

		if ($_GET['jns_nikah1'] || $_GET['jns_nikah2'] || $_GET['jns_nikah3'] || $_GET['jns_nikah4'])
		{
			for ($j = 0; $j < 4; $j++) 
				{
					$nonikah++;
					if ($_GET['jns_nikah'.$nonikah]){$c_nikah=$_GET['jns_nikah'.$nonikah]; $c_nikah="'$c_nikah'"; $parnikah=$c_nikah.",".$parnikah;}				
				}
			if ($parnikah){$parnikah = substr($parnikah,0,-1); $cari= $cari." and c_peg_statusnikah in ($parnikah)";}		
		}

		
		if($_GET['d_tgl_lahir']){$cari= $cari." and to_char(d_peg_lahir,'dd-mm-yyyy') ='$d_tgl_lahir'";}
		//if($_GET['usia_awl']){$cari= $cari." and c_penjenjangan =";}
		//if($_GET['usia_akh']){$cari= $cari." and c_penjenjangan =";}
		//if($_GET['tptlahir']){$cari= $cari." and c_penjenjangan =";}  === blm ada di tabel
		if($_GET['c_propinsi_lahir']){$cari= $cari." and c_peg_propinsi_lahir = '$c_propinsi_lahir'";}
		if($_GET['a_kota_lahir']){$cari= $cari." and a_peg_kota_lahir ='$a_kota_lahir'";}
		if($_GET['hobby']){$hobby=strtoupper($hobby);$cari= $cari." and upper(n_peg_hobi) like '%$hobby%'";}
		//echo $cari;
		if($_GET['kd_tlp']){$cari= $cari." and i_peg_telponrumah ='$i_peg_telponrumah'";}
		if($_GET['kd_pos']){$cari= $cari." and a_peg_kodepos ='$kd_pos'";}
		if($_GET['w_kulit']){$w_kulit=strtoupper($w_kulit);$cari= $cari." and upper(n_peg_warnakulit) like '%$w_kulit%'";}
		if($_GET['kd_karpeg']){$cari= $cari." and i_peg_karpeg ='$kd_karpeg'";}
		if($_GET['kd_askes']){$cari= $cari." and i_peg_askes ='$kd_askes'";}
		if($_GET['kd_taspen']){$cari= $cari." and i_peg_taspen ='$kd_taspen'";}
		if($_GET['kd_korpri']){$cari= $cari." and i_peg_korpri ='$kd_korpri'";}
		if($_GET['kd_ktp']){$cari= $cari." and i_peg_ktp ='$kd_ktp'";}
		
		//Unit Kerja=====================
				
		$c_lokasi_unitkerja=$_GET['c_lokasi_unitkerja'];	
				
		if ($c_lokasi_unitkerja=='1'){
			
			if ($_GET['c_eselon_i']){$c_eselon_i=$_GET['c_eselon_i'];}
			else{$c_eselon_i=$_GET['c_eselon_i'];}
			
			$c_eselon_i=substr($c_eselon_i,0,2);
			$n_eselon_i=substr($_GET['c_eselon_i'],2,200);
			$cari .= " and c_lokasi_unitkerja='$c_lokasi_unitkerja'";
			if ($_GET['c_eselon_i'] || $_GET['c_eselon_i']){$cari .= " and c_eselon_i='$c_eselon_i'";}

			if ($_GET['c_eselon_ii']){$c_eselon_ii=$_GET['c_eselon_ii'];}
			else{$c_eselon_ii=$_GET['c_eselon_ii'];}
			$c_eselon_ii=substr($c_eselon_ii,0,3);
			$n_eselon_ii=substr($_GET['c_eselon_ii'],2,200);
			if ($_GET['c_eselon_ii'] || $_GET['c_eselon_ii']){$cari .= "and c_eselon_ii='$c_eselon_ii' ";}
			
			if ($_GET['c_eselon_iii']){$c_eselon_i=$_GET['c_eselon_iii'];}
			else{$c_eselon_iii=$_GET['c_eselon_iii'];}
			
			
			$c_eselon_iii=substr($c_eselon_iii,0,2);
			$n_eselon_iii=substr($_GET['c_eselon_iii'],2,200);
			if ($_GET['c_eselon_iii'] || $_GET['c_eselon_iii']){$cari .= " and c_eselon_iii='$c_eselon_iii'";}

			if ($_GET['c_eselon_iv']){$c_eselon_iv=$_GET['c_eselon_iv'];}
			else{$c_eselon_iv=$_GET['c_eselon_iv'];}
			
			
			$c_eselon_iv=substr($c_eselon_iv,0,2);
			$n_eselon_iv=substr($_GET['c_eselon_iv'],2,200);
			if ($_GET['c_eselon_iv'] || $_GET['c_eselon_iv']){$cari .= " and c_eselon_iv='$c_eselon_iv' ";}
		

			$this->view->c_eselon_i = $c_eselon_i;

		}
		else
		{


			$c_lokasi_unitkerja=trim($_POST['c_lokasi_unitkerja']);
			if(trim($c_lokasi_unitkerja) != '') $cari .= " and c_lokasi_unitkerja='$c_lokasi_unitkerja'";
			
			$c_eselon_i=$_GET['c_eselon_i'];
			if ($c_eselon_i){
				$expesl1 = explode(";",$c_eselon_i);
				$c_eselon_i=$expesl1[0];
				$n_eselon_i=$expesl1[1];
				$cari .= " and c_eselon_i='$c_eselon_i'";
			}
			
			
			$c_eselon_iii=$_GET['c_eselon_iii'];
			if ($c_eselon_iii){
				$expesl3 = explode(";",$c_eselon_iii);	
				$c_eselon_iix=$expesl3[0];
				$c_eselon_iii=$expesl3[1];
				$c_satker=$expesl3[2];
				$n_eselon_iii=$expesl3[3];
				$cari .= " and c_eselon_ii='$c_eselon_iix'";
				$cari .= " and c_eselon_iii='$c_eselon_iii'";	
			}

			$c_eselon_ii=$_GET['c_eselon_ii'];
			if ($c_eselon_ii && !$c_eselon_iix){
				$expesl2 = explode(";",$c_eselon_ii);
				$c_eselon_ii=$expesl2[0];
				$c_parent=$expesl2[1];
				$n_eselon_ii=$expesl2[2];
				$cari .= " and c_eselon_ii='$c_eselon_ii'";
			}			

			$c_eselon_iv=$_GET['c_eselon_iv'];
			if ($c_eselon_iv){
				$expesl4 = explode(";",$c_eselon_iv);	
				$c_eselon_iv=$expesl4[0];
				$n_eselon_iv=$expesl4[1];
				$cari .= " and c_eselon_iv='$c_eselon_iv'";

			}
			
		}				
			
		//echo $cari;
		$start=$_GET['start'];
		$limit=$_GET['limit'];		
		$currentPage=$_GET['currentPage'];
		if((!$currentPage) || ($currentPage == 'undefined'))
			{$currentPage = $start;}	
			if (!$limit){$limit=$_GET['limit'];}
			$numToDisplay = $limit;
			
			$this->view->numToDisplay = $numToDisplay;
			$this->view->currentPage = $currentPage;
			$this->view->limit=$numToDisplay;		
$cari .= " and (c_eselon !='17' or c_eselon isnull)";

		$totalpegawaiList = $this->pegawai_serv->getPegawaiList($cari, 0, 0 ,$orderBy);	
		$this->view->totalpegawaiList=$totalpegawaiList;		
		$pegawaiList = $this->pegawai_serv->getPegawaiList($cari,$currentPage,$numToDisplay,$orderBy );
$jdllap=$_GET['judul_lap'];		
$html='<h2 class="title">'.$jdllap.'</h2>
<div style="height: 490px; overflow: auto; padding: 5px">';
 $html=$html.'
  <table align="center" border="0" cellspacing="1" cellpadding="2" class="sortable">
		<tr>
			<th width="5%" rowspan="2">No.</th>
			<th rowspan="2">&nbsp;</th>
			<th rowspan="2">Nama</th>
			<th rowspan="2">Jabatan</th>
			<th rowspan="2">NIP</th>
			<th rowspan="2">Unit Kerja</th>
			<th rowspan="2">Gol / Pangkat</th>
			<th colspan="3">TMT</th>
			<th rowspan="2">Tanggal Lahir</th>
			<th rowspan="2">Umur</th>
			<th rowspan="2">Pendidikan</th>
			<th rowspan="2">Dtype</th>
		</tr> 
		<tr>
			<th>Gol</th>
			<th>Jabatan</th>
			<th>CPNS</th>			
		</tr>';	
 		if ($totalpegawaiList == 0) {
 $html=$html.'		
		<tr class="event">
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>';	
}else{
			for ($j = 0; $j < count($pegawaiList); $j++) 
			{
				if ($key%2==0) { 
 $html=$html.'				<tr class="event">';
				 } else if ($key%2==1) { 
 $html=$html.'				<tr class="event2">';
				 } 
					 $noUrut = (($currentPage -1)* $numToDisplay) +$j+1;
					$pangkat = $pegawaiList[$j]['n_pangkat'];
					$golongan = $pegawaiList[$j]['n_golongan'];
					$golPangkat = $golongan." - ".$pangkat;
					$n_lokasi_unitkerja=$pegawaiList[$j]['n_lokasi_unitkerja'];
					$n_eselon=$pegawaiList[$j]['n_eselon'];
					$n_jabatan=$pegawaiList[$j]['n_jabatan'];
					$tmtkgb = $pegawaiList[$j]['d_tmt_kgb'];
					$d_tmt_golongan = $pegawaiList[$j]['d_tmt_golongan'];
					$d_mulai_jabat = $pegawaiList[$j]['d_mulai_jabat'];
					$d_tmt_cpns = $pegawaiList[$j]['d_tmt_cpns'];
					$d_peg_lahir = $pegawaiList[$j]['d_peg_lahir2'];
					$i_peg_nip=$pegawaiList[$j]['i_peg_nip_new'];
					$usiatahun=$pegawaiList[$j]['usiatahun'];
					$usiabulan=$pegawaiList[$j]['usiabulan'];
					$n_peg=$pegawaiList[$j]['n_peg'];
					if ($usiatahun && $usiatahun!='0'){
					if ($usiabulan && $usiabulan!='0'){
					$Usia="$usiatahun tahun $usiabulan bulan";
					}
					else{$Usia="$usiatahun tahun";
					}
					}
					$n_status_kepegawaian="";
					$n_pendidikan=$pegawaiList[$j]['n_pendidikan'];
					if($pegawaiList[$j]['c_peg_status']=='MIL'){$n_status_kepegawaian="/".$pegawaiList[$j]['n_status_kepegawaian'];}
 $html=$html.'				
					<td class="clcenter">'.$noUrut.'</td>
					
					<td>';
						
						$vFoto="";
						$vFoto = $this->basePath."/upld/Uploaddata/getphoto?f=".trim($pegawaiList[$j]['e_file_photo']);
 $html=$html.'						
						<img src="'.$vFoto.'" width="55" height="60" >
					</td>
					<td>'.$n_peg.'</td>
					<td>'.$n_jabatan.'</td>
					<td>'.$i_peg_nip.'</td>
					<td>'.$n_lokasi_unitkerja.'</td>
					<td>'.$golPangkat.'</td>
					<td>'.$d_tmt_golongan.'</td>
					<td>'.$d_mulai_jabat.'</td>
					<td>'.$d_tmt_cpns.'</td>
					<td>'.$d_peg_lahir.'</td>
					<td>'.$Usia.'</td>
					<td>'.$n_pendidikan.'</td>
					<td></td>';
					
					
					
					
				 }
				 }
	
 $html=$html.'	</table>';
	
/* $html=$html.' <table align="center" border="0" cellspacing="1" cellpadding="2" class="sortable">
		<tr>
			<th>No.</th>
			<th>NIP</th>
			<th>Nama</th>
		</tr>';
if (count($pegawaiList)==0)
{
$html=$html.' <tr class="event2">
			<td>&nbsp</td>
			<td>&nbsp</td>
			<td>&nbsp</td>
		</tr>';

}		
if (count($pegawaiList)!=0){
		for ($j = 0; $j < count($pegawaiList); $j++) 
			{
				$i_peg_nip=$pegawaiList[$j]['i_peg_nip'];
				$n_peg=$pegawaiList[$j]['n_peg'];
				$no++;	
				if ($j%2==0) {
				      $html=$html.'<tr class="event">';
				 } else if ($j%2==1) { 
					  $html=$html.'<tr class="event2">';
				 }
				 $html=$html.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$no.'</font></td>
						<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$i_peg_nip.'</font></td>
						<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$n_peg.'</font></td>
						</tr>';				 
			}
		} */

		
$html=$html.'</table></div>';

$dt_tpl2_get=$_GET['dt_tpl2_get'];
if (!$dt_tpl2_get){
	$this->view->view=$html;
}
else{
				
$html2="";
$html2='<h2 class="title">'.$jdllap.'</h2>';
$html2=$html2.'<div style="width: 1000px; height: 470px; overflow: auto; padding: 5px">
<table align="center" border="0" cellspacing="1" cellpadding="2" class="sortable">
			<tr>
				<th>No</th>';
$wordChunksLimited = explode(" :: ", $dt_tpl2_get);
for($i = 0; $i < count($wordChunksLimited); $i++)
{
	if ($wordChunksLimited[$i]){$html2=$html2.'<th>'.$wordChunksLimited[$i].'</th>';}
}
			
$no=0;
$html2=$html2.'</tr><tr>';
		for ($j = 0; $j < count($pegawaiList); $j++) 
			{
				$no++;	
				if ($j%2==0) {
				      $html2=$html2.'<tr class="event">';
				 } else if ($j%2==1) { 
					  $html2=$html2.'<tr class="event2">';
				 }
					$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$no.'</font></td>';
					$nip="";
					$nip=trim($pegawaiList[$j]['i_peg_nip']);
					for($i = 0; $i < count($wordChunksLimited); $i++)
						{
//						echo $wordChunksLimited[$i]."<br>";
							if (trim($wordChunksLimited[$i])=='Nama/NIP'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_peg'].'<br>'.$pegawaiList[$j]['i_peg_nip'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='Gelar depan'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_peg_gelardepan'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='Nama Tp. Gelar'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$i_peg_nipx.'</font></td>';}
							if (trim($wordChunksLimited[$i])=='Gelar Belakang'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_peg_gelarblkg'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='Nama Lengkap'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_peg'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='NIP Baru'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['i_peg_nip_new'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='NIP Lama'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['i_peg_nip'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='Pangkat/Gol & TMT'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['npangkat'].'<br>'.$pegawaiList[$j]['d_mulai_jabat'].' </font></td>';}
							if (trim($wordChunksLimited[$i])=='Jabatan & TMT'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_jabatan'].'<br>'.$pegawaiList[$j]['d_mulai_jabat'].' </font></td>';}
							if (trim($wordChunksLimited[$i])=='Eselon & TMT'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_eselon'].'<br>'.$pegawaiList[$j]['d_tmt_eselon'].' </font></td>';}
							if (trim($wordChunksLimited[$i])=='Pendidikan'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_pendidikan'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='Fak/Jurusan'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_pend_jurusan'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='Thn. Lulus'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['d_pend_akhir'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='Lembaga Pendidikan'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_eselon'].'<br>'.$pegawaiList[$j]['d_tmt_eselon'].' </font></td>';}
							if (trim($wordChunksLimited[$i])=='Kelamin'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_peg_jeniskelamin'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='Status Kawin'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_peg_statusnikah'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='Agama'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_agama'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='Gol. Darah'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['c_golongan_darah'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='Tempat Lahir'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_peg_propinsi_lahir'].'<br>'.$pegawaiList[$j]['n_peg_kota_lahir'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='Tanggal Lahir'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['d_peg_lahir'].' </font></td>';}
							if (trim($wordChunksLimited[$i])=='Pangkat/Gol CPNS & TMT'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_pangkat_cpns'].'/'.$pegawaiList[$j]['c_gol_cpns'].'<br>'.$pegawaiList[$j]['d_tmt_cpns'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='TMT PNS'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['d_sk_pns'].'</font></td>';}
							//if (trim($wordChunksLimited[$i])=='Masa Kerja'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_eselon'].'<br>'.$pegawaiList[$j]['d_tmt_eselon'].' </font></td>';}
							//if (trim($wordChunksLimited[$i])=='Usia'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_eselon'].'<br>'.$pegawaiList[$j]['d_tmt_eselon'].' </font></td>';}
							if (trim($wordChunksLimited[$i])=='Alamat Rumah'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['a_peg_rumah'].'</font></td>';}
							//if (trim($wordChunksLimited[$i])=='Alamat Kantor'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_eselon'].'<br>'.$pegawaiList[$j]['d_tmt_eselon'].' </font></td>';}
							//if (trim($wordChunksLimited[$i])=='SK Pangkat'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_eselon'].'<br>'.$pegawaiList[$j]['d_tmt_eselon'].' </font></td>';}
							//if (trim($wordChunksLimited[$i])=='SK Jabatan'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_eselon'].'<br>'.$pegawaiList[$j]['d_tmt_eselon'].' </font></td>';}
							//if (trim($wordChunksLimited[$i])=='Unit Kerja'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_eselon'].'<br>'.$pegawaiList[$j]['d_tmt_eselon'].' </font></td>';}
							if (trim($wordChunksLimited[$i])=='Foto'){
							$e_file_photo=$pegawaiList[$j]['e_file_photo'];
							$vFoto = $this->basePath."/upld/Uploaddata/getphoto?f=".$e_file_photo;
							$html2=$html2.'
								<td align="center">
									<img src="'.$vFoto.'" width="40" height="50" >
								</td>';
							}
							if (trim($wordChunksLimited[$i])=='Riwayat Pendidikan'){
								$pendList="";
								$pendList = $this->pendidikan_serv->getPendidikanList(" and i_peg_nip ='$nip'");								
								if (count($pendList)!=0){
									for($xpend = 0; $xpend < count($pendList); $xpend++)
									{
										$pendidikan=$pendList[$xpend]['n_pend']."  ".$pendList[$xpend]['d_pend_mulai']."  ".$pendList[$xpend]['n_pend_lembaga']."<br>";
										$penddetil=$penddetil.$pendidikan;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$penddetil.'</font></td>';
							
							}
							if (trim($wordChunksLimited[$i])=='Riwayat Jabatan'){
								$jabatanList="";
								$jabatan="";
								$jabatandetil="";
								$jabatanList = $this->jabatan_serv->getJabatanList(" and i_peg_nip ='$nip'");								
								if (count($jabatanList)!=0){
									for($xjabatan = 0; $xjabatan < count($jabatanList); $xjabatan++)
									{
										$jabatan=$jabatanList[$xjabatan]['n_jabatan']." pada ".$jabatanList[$xjabatan]['unitkerjalengkap']."  ".$jabatanList[$xjabatan]['d_mulai_jabat']."<br>";
										$jabatandetil=$jabatandetil.$jabatan;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$jabatandetil.'</font></td>';
							
							}						
							if (trim($wordChunksLimited[$i])=='TMT & Jabatan yang sesuai pada Riw. Jabatan'){
								$jabatanList="";
								$jabatan="";
								$jabatandetil="";
								$jabatanList = $this->jabatan_serv->getJabatanList(" and i_peg_nip ='$nip'");								
								if (count($jabatanList)!=0){
									for($xjabatan = 0; $xjabatan < count($jabatanList); $xjabatan++)
									{
										$jabatan=$jabatanList[$xjabatan]['d_mulai_jabat']." s/d ".$jabatanList[$xjabatan]['d_akhir_jabat']."<br>";
										$jabatandetil=$jabatandetil.$jabatan;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$jabatandetil.'</font></td>';
							
							}	
							if (trim($wordChunksLimited[$i])=='Riwayat Pangkat'){
								$pangkatList="";
								$pangkat="";
								$pangkatdetil="";
								$pangkatList = $this->pangkat_serv->getPangkatList(" and i_peg_nip ='$nip'");								
								if (count($pangkatList)!=0){
									for($xpangkat = 0; $xpangkat < count($pangkatList); $xpangkat++)
									{
										$pangkat=$pangkatList[$xpangkat]['c_golongan']."/".$pangkatList[$xpangkat]['n_pangkat']."  ".$pangkatList[$xpangkat]['d_tmt_golongan']."<br>";
										$pangkatdetil=$pangkatdetil.$pangkat;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pangkatdetil.'</font></td>';
							
							}
							if (trim($wordChunksLimited[$i])=='Riwayat Organisasi'){
								$organisasiList="";
								$organisasi="";
								$organisasidetil="";
								$organisasiList = $this->organisasi_serv->getOrganisasiList(" and i_peg_nip ='$nip'");								
								if (count($organisasiList)!=0){
									for($xorganisasi = 0; $xorganisasi < count($organisasiList); $xorganisasi++)
									{
										$organisasi=$organisasiList[$xorganisasi]['n_organisasi']." ".$organisasiList[$xorganisasi]['n_peran_organisasi']." s/d ".$organisasiList[$xorganisasi]['d_daftar_organisasi']."<br>";
										$organisasidetil=$organisasidetil.$organisasi;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$organisasidetil.'</font></td>';
							
							}
							if (trim($wordChunksLimited[$i])=='Riwayat Kunjungan'){
								$luarnegeriList="";
								$luarnegeri="";
								$luarnegeridetil="";
								$luarnegeriList = $this->luarnegeri_serv->getLuarnegeriList(" and i_peg_nip ='$nip'");								
								if (count($luarnegeriList)!=0){
									for($xluarnegeri = 0; $xluarnegeri < count($luarnegeriList); $xluarnegeri++)
									{
										$luarnegeri=$luarnegeriList[$xluarnegeri]['n_negara']." ".$luarnegeriList[$xluarnegeri]['a_tujuan']." ".$luarnegeriList[$xluarnegeri]['n_biaya']." ".$luarnegeriList[$xluarnegeri]['d_barangkat']."<br>";
										$luarnegeridetil=$luarnegeridetil.$luarnegeri;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$luarnegeridetil.'</font></td>';
							
							}

							if (trim($wordChunksLimited[$i])=='Riwayat Seminar'){
								$seminarList="";
								$seminar="";
								$seminardetil="";
								$seminarList = $this->seminar_serv->getSeminarList(" and i_peg_nip ='$nip'");								
								if (count($seminarList)!=0){
									for($xseminar = 0; $xseminar < count($seminarList); $xseminar++)
									{
										$seminar=$seminarList[$xseminar]['n_seminar']." ".$seminarList[$xseminar]['n_seminar_peran']." ".$seminarList[$xseminar]['d_mulai_seminar']."<br>";
										$seminardetil=$seminardetil.$seminar;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$seminardetil.'</font></td>';
							
							}
							if (trim($wordChunksLimited[$i])=='Riwayat Penghargaan'){
								$penghargaanList="";
								$penghargaan="";
								$penghargaandetil="";
								$penghargaanList = $this->penghargaan_serv->getpenghargaanList(" and i_peg_nip ='$nip'");								
								if (count($penghargaanList)!=0){
									for($xpenghargaan = 0; $xpenghargaan < count($penghargaanList); $xpenghargaan++)
									{
										$penghargaan=$penghargaanList[$xpenghargaan]['jenispenghargaan']." ".$penghargaanList[$xpenghargaan]['namapenghargaan']." ".$penghargaanList[$xpenghargaan]['d_tahun_alteratif']."<br>";
										$penghargaandetil=$penghargaandetil.$penghargaan;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$penghargaandetil.'</font></td>';
							
							}
							if (trim($wordChunksLimited[$i])=='Riwayat Sanksi'){
								$hukumanList="";
								$hukuman="";
								$hukumandetil="";
								$hukumanList = $this->hukuman_serv->getHukumanList(" and i_peg_nip ='$nip'");								
								if (count($hukumanList)!=0){
									for($xhukuman = 0; $xhukuman < count($hukumanList); $xhukuman++)
									{
										$hukuman=$hukumanList[$xhukuman]['nsanksi']." ".$hukumanList[$xhukuman]['jnssanksi']." ".$hukumanList[$xhukuman]['e_alasan_sanksi']." ".$hukumanList[$xhukuman]['d_mulai_sanksi']."<br>";
										$hukumandetil=$hukumandetil.$hukuman;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$hukumandetil.'</font></td>';
							
							}							
							if (trim($wordChunksLimited[$i])=='Data Angka Kredit'){
								$angkakreditList="";
								$angkakredit="";
								$angkakreditdetil="";
								$angkakreditList = $this->angkakredit_serv->getAngkaKreditList(" and i_peg_nip ='$nip'");								
								if (count($angkakreditList)!=0){
									for($xangkakredit = 0; $xangkakredit < count($angkakreditList); $xangkakredit++)
									{
										$angkakredit="Utama : ".$angkakreditList[$xangkakredit]['q_totalnilai']." Penunjang : ".$angkakreditList[$xangkakredit]['q_penunjang']."<br>";
										$angkakreditdetil=$angkakreditdetil.$angkakredit;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$angkakreditdetil.'</font></td>';
							
							}
							if (trim($wordChunksLimited[$i])=='Data Pasangan'){
								$pasanganList="";
								$pasangan="";
								$pasangandetil="";
								$pasanganList = $this->pasangan_serv->getPasanganList(" and i_peg_nip ='$nip'");								
								if (count($pasanganList)!=0){
									for($xpasangan = 0; $xpasangan < count($pasanganList); $xpasangan++)
									{
										$pasangan=$pasanganList[$xpasangan]['n_nama']."  ".$pasanganList[$xpasangan]['n_pekerjaan']."<br>";
										$pasangandetil=$pasangandetil.$pasangan;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pasangandetil.'</font></td>';
							
							}
							if (trim($wordChunksLimited[$i])=='Data Anak'){
								$anakList="";
								$anak="";
								$anakdetil="";
								$anakList = $this->anak_serv->getAnakList(" and i_peg_nip ='$nip'");								
								if (count($anakList)!=0){
									for($xanak = 0; $xanak < count($anakList); $xanak++)
									{
										$anak=$anakList[$xanak]['n_nama']." ".$anakList[$xanak]['n_jns_kel']." ".$anakList[$xanak]['n_pekerjaan']."<br>";
										$anakdetil=$anakdetil.$anak;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$anakdetil.'</font></td>';
							
							}
							if (trim($wordChunksLimited[$i])=='Data Orang Tua'){
								$ortuList="";
								$ortu="";
								$ortudetil="";
								$ortuList = $this->kerabat_serv->getKerabatList(" and i_peg_nip ='$nip' and c_kerabat in ('1','2','3','4')");								
								if (count($ortuList)!=0){
									for($xortu = 0; $xortu < count($ortuList); $xortu++)
									{
										$ortu=$ortuList[$xortu]['n_kerabat']." ".$ortuList[$xortu]['n_nama']." ".$ortuList[$xortu]['n_pekerjaan']."<br>";
										$ortudetil=$ortudetil.$ortu;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$ortudetil.'</font></td>';
							
							}							
							if (trim($wordChunksLimited[$i])=='Data Saudara'){
								$sdrList="";
								$sdr="";
								$sdrdetil="";
								$sdrList = $this->kerabat_serv->getKerabatList(" and i_peg_nip ='$nip' and c_kerabat in ('5')");								
								if (count($sdrList)!=0){
									for($xsdr = 0; $xsdr < count($sdrList); $xsdr++)
									{
										$sdr=$sdrList[$xsdr]['n_kerabat']." ".$sdrList[$xsdr]['n_nama']." ".$sdrList[$xsdr]['n_pekerjaan']."<br>";
										$sdrdetil=$sdrdetil.$sdr;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$sdrdetil.'</font></td>';
							
							}								
							if (trim($wordChunksLimited[$i])=='Riwayat Diklat Pim'){
								$penjenjanganList="";
								$penjenjangan="";
								$penjenjangandetil="";
								$penjenjanganList = $this->penjenjangan_serv->getPelatihanList(" and i_peg_nip ='$nip'");								
								if (count($penjenjanganList)!=0){
									for($xpenjenjangan = 0; $xpenjenjangan < count($penjenjanganList); $xpenjenjangan++)
									{
										$penjenjangan=$penjenjanganList[$xpenjenjangan]['n_penjenjangan']." ".$penjenjanganList[$xpenjenjangan]['q_tahun']."<br>";
										$penjenjangandetil=$penjenjangandetil.$penjenjangan;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$penjenjangandetil.'</font></td>';
							
							}							
							if (trim($wordChunksLimited[$i])=='Riwayat Diklat Fungsional'){
								$fungsionalList="";
								$fungsional="";
								$fungsionaldetil="";
								$fungsionalList = $this->fungsional_serv->getPelatihanList(" and i_peg_nip ='$nip'");								
								if (count($fungsionalList)!=0){
									for($xfungsional = 0; $xfungsional < count($fungsionalList); $xfungsional++)
									{
										$fungsional=$fungsionalList[$xfungsional]['n_jns_fungsional']." ".$fungsionalList[$xfungsional]['n_kel_pelatihan']." ".$fungsionalList[$xfungsional]['q_pelatihan']."<br>";
										$fungsionaldetil=$fungsionaldetil.$fungsional;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$fungsionaldetil.'</font></td>';
							
							}
							if (trim($wordChunksLimited[$i])=='Riwayat Diklat Teknis'){
								$teknisList="";
								$teknis="";
								$teknisdetil="";
								$teknisList = $this->teknis_serv->getPelatihanList(" and i_peg_nip ='$nip'");								
								if (count($teknisList)!=0){
									for($xteknis = 0; $xteknis < count($teknisList); $xteknis++)
									{
										$teknis=$teknisList[$xteknis]['n_kelompok']." ".$teknisList[$xteknis]['n_diklat']." ".$teknisList[$xteknis]['q_lama']."<br>";
										$teknisdetil=$teknisdetil.$teknis;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$teknisdetil.'</font></td>';
							
							}
							if (trim($wordChunksLimited[$i])=='Riwayat Diklat Lain'){
								$diklainList="";
								$diklain="";
								$diklaindetil="";
								$diklainList = $this->diklain_serv->getPelatihanList(" and i_peg_nip ='$nip'");								
								if (count($diklainList)!=0){
									for($xdiklain = 0; $xdiklain < count($diklainList); $xdiklain++)
									{
										$diklain=$diklainList[$xdiklain]['n_diklat']." ".$diklainList[$xdiklain]['n_negara']." ".$diklainList[$xdiklain]['d_diklat']."<br>";
										$diklaindetil=$diklaindetil.$diklain;
									}
								}
								$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$diklaindetil.'</font></td>';
							
							}							
							
						}
					
					
					$html2=$html2.'</tr>';				 
			}
		


$html2=$html2.'</table></div>';


}

$this->view->a_kota_lahir=$_GET['a_kota_lahir'];
$this->view->bln_cpns=$_GET['bln_cpns'];
$this->view->bln_pns=$_GET['bln_pns'];
$this->view->c_eselon_i=$_GET['c_eselon_i'];
$this->view->c_eselon_ii=$_GET['c_eselon_ii'];
$this->view->c_eselon_iii=$_GET['c_eselon_iii'];
$this->view->c_eselon_iv=$_GET['c_eselon_iv'];
$this->view->c_lokasi_unitkerja=$_GET['c_lokasi_unitkerja'];
$this->view->c_propinsi_lahir=$_GET['c_propinsi_lahir'];
$this->view->d_tgl_lahir=$_GET['d_tgl_lahir'];
$this->view->d_tmt_eselon=$_GET['d_tmt_eselon'];
$this->view->d_tmt_golongan=$_GET['d_tmt_golongan'];
$this->view->d_tmt_jabatan=$_GET['d_tmt_jabatan'];
$this->view->dt_tpl2_get=$_GET['dt_tpl2_get'];
$this->view->hobby=$_GET['hobby'];
$this->view->jabkelfungsional=$_GET['jabkelfungsional'];
$this->view->jns_sex=$_GET['jns_sex'];
$this->view->judul_lap=$_GET['judul_lap'];
$this->view->kd_askes=$_GET['kd_askes'];
$this->view->kd_jbt=$_GET['kd_jbt'];
$this->view->kd_jbt2=$_GET['kd_jbt2'];
$this->view->kd_karpeg=$_GET['kd_karpeg'];
$this->view->kd_korpri=$_GET['kd_korpri'];
$this->view->kd_ktp=$_GET['kd_ktp'];
$this->view->kd_pos=$_GET['kd_pos'];
$this->view->kd_taspen=$_GET['kd_taspen'];
$this->view->kd_tlp=$_GET['kd_tlp'];
$this->view->limit=$_GET['limit'];
$this->view->n_jurusan=$_GET['n_jurusan'];
$this->view->n_universitas=$_GET['n_universitas'];
$this->view->orderby2_get=$_GET['orderby2_get'];
$this->view->propinsi_unit=$_GET['propinsi_unit'];
$this->view->s_nama=$_GET['s_nama'];
$this->view->s_nip=$_GET['s_nip'];
$this->view->sk_cpns=$_GET['sk_cpns'];
$this->view->sk_pns=$_GET['sk_pns'];
$this->view->start=$_GET['start'];
$this->view->tptlahir=$_GET['tptlahir'];
$this->view->usia_akh=$_GET['usia_akh'];
$this->view->usia_awl=$_GET['usia_awl'];
$this->view->w_kulit=$_GET['w_kulit'];
$this->view->y_cpns=$_GET['y_cpns'];
$this->view->y_diklatpim=$_GET['y_diklatpim'];
$this->view->y_lulus=$_GET['y_lulus'];
$this->view->y_pns=$_GET['y_pns'];
$this->view->jns_agama3=$_GET['jns_agama3'];
$this->view->c_eselon_v=$_GET['c_eselon_v'];
$this->view->c_jabatan_jabdiv=$_GET['c_jabatan_jabdiv'];

$this->render('cetakview');
$this->view->view=$html2;
$this->view->par=$_GET['par'];


}	

	
}

?>