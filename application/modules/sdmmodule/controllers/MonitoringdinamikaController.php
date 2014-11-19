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


class Sdmmodule_MonitoringdinamikaController extends Zend_Controller_Action
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
	public function monitoringdinamikajsAction() 
	{
		header('content-type : text/javascript');
		$this->render('monitoringdinamikajs');
	}
	public function monitoringdinamikaAction() 
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
    public function combonamajabatanAction() {
		$c_eselonjabdiv=$_GET['c_eselonjabdiv'];
	$cari =" and c_eselon ='$c_eselonjabdiv' ";
		$this->view->nmJabatanList = $this->reff_serv->getJabatan($cari);
	}
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

		$query_result=stripslashes($_POST['query_result']);		
		if ($query_result){$cari=" and $query_result ";}
		else{$cari="";}
			
		//echo $cari;
		$start=$_POST['start'];
		$limit=$_POST['limit'];	
		
		$currentPage=$_POST['currentPage'];
		if((!$currentPage) || ($currentPage == 'undefined'))
			{$currentPage = $start;}	
			if (!$limit){$limit=$_POST['limit'];}
			$numToDisplay = $limit;
			
			$this->view->numToDisplay = $numToDisplay;
			$this->view->currentPage = $currentPage;
			$this->view->limit=$numToDisplay;		

$html="";

		$totalpegawaiList = $this->pegawai_serv->getPegawaiList($cari, 0, 0 ,$orderBy);	
		$this->view->totalpegawaiList=$totalpegawaiList;		
		$pegawaiList = $this->pegawai_serv->getPegawaiList($cari,$currentPage,$numToDisplay,$orderBy );
$jdllap=$_POST['judul_lap'];		
$html='<h2 class="title">'.$jdllap.'</h2>
<div style="width: 1000px; height: 470px; overflow: auto; padding: 5px">';
$html=$html.' <table align="center" border="0" cellspacing="1" cellpadding="2" class="sortable">
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
		}	
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
							if (trim($wordChunksLimited[$i])=='Nama Lengkap'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_peg'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='Gelar depan'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_peg_gelardepan'].'</font></td>';}
							if (trim($wordChunksLimited[$i])=='Nama Tp. Gelar'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$i_peg_nipx.'</font></td>';}
							if (trim($wordChunksLimited[$i])=='Gelar Belakang'){$html2=$html2.'<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$pegawaiList[$j]['n_peg_gelarblkg'].'</font></td>';}
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
}


}	




	public function popupdatadiriAction() 
	{
		if ($_GET['par']=='bynama'){$this->render('dtbynama');}
		if ($_GET['par']=='bynip'){$this->render('dtbynip');}
		if ($_GET['par']=='byjnskelamin'){$this->render('dtbyjnskelamin');}
		if ($_GET['par']=='bygoldarah'){$this->render('dtbygoldarah');}	
		if ($_GET['par']=='byagama'){$this->render('dtbyagama');}	
		if ($_GET['par']=='bypendidikan'){$this->render('dtbypendidikan');}
		if ($_GET['par']=='bypimakhir'){$this->view->listrpenjenjangan=$this->reff_serv->getTrPenjenjangan('');	 
						$this->render('dtbypimakhir'); }
		if ($_GET['par']=='bykelfung'){$this->render('dtbykelfungsional');}
		if ($_GET['par']=='bypangkat'){$this->render('dtbypangkat');}			
		if ($_GET['par']=='byeselon'){$this->render('dtbyeselon');}
		
		if ($_GET['par']=='byjabatan'){
			$this->view->eselonList = $this->reff_serv->getEselon('');		
			$this->view->lokasiList = $this->reff_serv->getLokasi('');	
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1'");
			$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='2'");
			$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='3'");
			$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='4'");
			$this->view->eselonvList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='5'");
			$c_eselon=$_GET['c_eselon'];
			if ($c_eselon){$cari=" and c_eselon='$c_eselon' ";}
			$this->view->nmJabatanList = $this->reff_serv->getJabatan($cari);
			$this->render('dtbyjabatan');
			}
		if ($_GET['par']=='byunitkerja'){
			$this->view->eselonList = $this->reff_serv->getEselon('');		
			$this->view->lokasiList = $this->reff_serv->getLokasi('');	
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1'");
			$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='2'");
			$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='3'");
			$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='4'");
			$this->view->eselonvList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='5'");
			$c_eselon=$_GET['c_eselon'];
			if ($c_eselon){$cari=" and c_eselon='$c_eselon' ";}
			$this->view->nmJabatanList = $this->reff_serv->getJabatan($cari);
			$this->render('dtbyunitkerja');
			}
		if ($_GET['par']=='bylahir'){
			$this->view->propinsiList = $this->reff_serv->getPropinsiListAll();
			$this->view->kabupatenListLahir = $this->reff_serv->getKabupatenListAll($carikabupaten);
		}		
		if ($_GET['par']=='byalamat'){
			$this->view->propinsiList = $this->reff_serv->getPropinsiListAll();
			$this->view->kabupatenList = $this->reff_serv->getKabupatenListAll($carikabupaten);
		}			
		if ($_GET['par']=='bynikah'){$this->render('dtbynikah');}
		if ($_GET['par']=='bylahir'){$this->render('dtbylahir');}
		if ($_GET['par']=='byalamat'){$this->render('dtbyalamat');}
		if ($_GET['par']=='bykartu'){$this->render('dtbykartu');}
		if ($_GET['par']=='byidentitas'){$this->render('dtbyidentitas');}
		if ($_GET['par']=='byeseloni'){$this->render('dtbyeseloni');}
		if ($_GET['par']=='byeselonii'){$this->render('dtbyeselonii');}
		if ($_GET['par']=='byeseloniii'){$this->render('dtbyeseloniii');}
		if ($_GET['par']=='byeseloniv'){$this->render('dtbyeseloniv');}
		if ($_GET['par']=='byeselonv'){$this->render('dtbyeseloniv');}
		
		
		
		
		
	}
	
}

?>