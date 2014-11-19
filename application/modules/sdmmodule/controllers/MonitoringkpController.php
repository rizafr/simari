<?php
require_once 'Zend/Controller/Action.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Monitoring_Service.php";
class Sdmmodule_MonitoringkpController extends Zend_Controller_Action
{
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
	 	$registry = Zend_Registry::getInstance();
	   	$this->view->basePath = $registry->get('basepath');	
		$this->view->leftMenu = $registry->get('leftMenu'); 
		$this->reff_serv = sdm_refferensi_Service::getInstance();
		$this->pegawai_serv = Sdm_Monitoring_Service::getInstance();

		$ssologin = new Zend_Session_Namespace('ssologin');
		$this->view->c_lokasi_unitkerja=$ssologin->c_lokasi_unitkerja;
		$this->view->c_eselon_i=$ssologin->c_eselon_i;	
    }
	public function indexAction()
	{
	}
	public function monitoringkpjsAction() 
	{
		header('content-type : text/javascript');
		$this->render('monitoringkpjs');
	}
	public function monitoringkpAction() 
	{
	   	$this->view->eselonList = $this->reff_serv->getEselon('');
		//$this->view->lokasiList = $this->reff_serv->getLokasi('');
		
	   	$c_lokasi_unitkerja=trim($this->view->c_lokasi_unitkerja);
		$c_eselon_i=trim($this->view->c_eselon_i);
		if ($c_eselon_i!='01'){
			$this->view->lokasiList = $this->reff_serv->getLokasi(" and c_lokasi='$c_lokasi_unitkerja'");
			if ($c_lokasi_unitkerja=='1'){
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and trim(c_tkt_esl)='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
			}
			else{
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}
		}
		else{
			$this->view->lokasiList = $this->reff_serv->getLokasi("");
			if ($c_lokasi_unitkerja=='1'){
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}
			else{
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}		
		
		}
		

		$this->view->c_eselon_i=$c_eselon_i;
		$this->view->c_lokasi_unitkerja=$c_lokasi_unitkerja;			
		
		
		
		$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai(" and c_peg_tipegolongan ='3' ");	
		//$this->view->statusKePegRef = $this->reff_serv->getStatusKepegawaian('');      	
		$this->view->statusKePegRef = $this->reff_serv->getStatusKepegawaian(" and c_status_kepegawaian in ('3','4','5','6')");	
	}	
	

	public function viewAction() 
	{
	
		$c_eselon = $_POST['c_eselon'];
		if (!$_POST['c_eselon']){$c_eselon = $_GET['c_eselon'];}
		$c_lokasi_unitkerja = $_POST['c_lokasi_unitkerja'];
		if (!$_POST['c_lokasi_unitkerja']){$c_lokasi_unitkerja = $_GET['c_lokasi_unitkerja'];}
		$c_eselon_i = $_POST['c_eselon_i'];
		if (!$_POST['c_eselon_i']){$c_eselon_i = $_GET['c_eselon_i'];}
		$c_eselon_ii = $_POST['c_eselon_ii'];
		if (!$_POST['c_eselon_ii']){$c_eselon_ii = $_GET['c_eselon_ii'];}
		$c_eselon_iii = $_POST['c_eselon_iii'];
		if (!$_POST['c_eselon_iii']){$c_eselon_iii = $_GET['c_eselon_iii'];}
		$c_eselon_iv = $_POST['c_eselon_iv'];
		if (!$_POST['c_eselon_iv']){$c_eselon_iv = $_GET['c_eselon_iv'];}
		$c_eselon_v = $_POST['c_eselon_v'];
		if (!$_POST['c_eselon_v']){$c_eselon_v = $_GET['c_eselon_v'];}
		
		$start=$_POST['start'];
		if (!$_POST['start']){$start = $_GET['start'];}
		$limit=$_POST['limit'];		
		if (!$_POST['limit']){$limit = $_GET['limit'];}

$html="";
		$currentPage=$_GET['currentPage'];
		if((!$currentPage) || ($currentPage == 'undefined'))
			{$currentPage = $start;}
			//echo $currentPage."  ".$numToDisplay;			
			if (!$limit){$limit=$_GET['limit'];}
			$numToDisplay = $limit;
			$this->view->numToDisplay = $numToDisplay;
			$this->view->currentPage = $currentPage;
	$cari="";	
		//$cari =" and i_peg_nip='150182962' ";
		$this->view->limit=$numToDisplay;

$carix="
and ((c_eselon in('01','02','03') and c_golongan in('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17') 
or (c_eselon = '04' and c_golongan in('04','05','06','07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_eselon = '03' and c_golongan in('02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_eselon = '05' and c_golongan in('04','05','06','07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_eselon = '06' and c_golongan in('05','06','07','08','09','10','11','12','13','14','15','16','17') ) 
or ((c_eselon = '07' or c_eselon = '08') and c_golongan in('05','06','07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_eselon = '15' and ( (c_pend = '29' and c_golongan in('12','13','14','15','16','17') ) 
or (c_pend = '41' and c_golongan in('11','12','13','14','15','16','17') ) 
or (c_pend = '40' and c_golongan in('08','09','10','11','12','13','14','15','16','17') ) 
or ((c_pend = '04' or c_pend = '05') and c_golongan in('08','09','10','11','12','13','14','15','16','17') ) 
or (c_pend = '32' and c_golongan in('07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_pend = '07' and c_golongan in('06','07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_pend = '36' and c_golongan in('06','07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_pend = '09' and c_golongan in('05','06','07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_pend = '10' and c_golongan in('04','05','06','07','08','09','10','11','12','13','14','15','16','17') ) ) ) ))";
		
		//Unit Kerja=====================
				
		if ($c_lokasi_unitkerja=='1'){
			$cari .= " and  c_lokasi_unitkerja='$c_lokasi_unitkerja'";
		$c_eselon_i=trim($this->view->c_eselon_i);	
			if ($c_eselon_i!='01'){
				$c_eselon_i=trim($this->view->c_eselon_i);
				$cari .= " and c_eselon_i='$c_eselon_i' ";
			}
			else{
				if ($_POST['c_eselon_i']){$c_eselon_i=$_POST['c_eselon_i'];}
				else{$c_eselon_i=$_GET['c_eselon_i'];}
				$c_eselon_ix=$c_eselon_i;
				$c_eselon_i=substr($c_eselon_i,0,2);
				$n_eselon_i=substr($c_eselon_ix,2,200);
				if ($_POST['c_eselon_i'] || $_GET['c_eselon_i']){$cari .= " and c_eselon_i='$c_eselon_i' ";}

			}	

			if ($_POST['c_eselon_ii']){$c_eselon_ii=$_POST['c_eselon_ii'];}
			else{$c_eselon_ii=$_GET['c_eselon_ii'];}
			$c_eselon_iix=$c_eselon_ii;
			$c_eselon_ii=substr($c_eselon_ii,0,3);
			$n_eselon_ii=substr($c_eselon_iix,3,200);
			if ($_POST['c_eselon_ii'] || $_GET['c_eselon_ii']){$cari .= " and c_eselon_ii='$c_eselon_ii' ";}
			
			if ($_POST['c_eselon_iii']){$c_eselon_i=$_POST['c_eselon_iii'];}
			else{$c_eselon_iii=$_GET['c_eselon_iii'];}
			
			$c_eselon_iiix=$c_eselon_iii;
			$c_eselon_iii=substr($c_eselon_iii,0,2);
			$n_eselon_iii=substr($c_eselon_iiix,2,200);
			if ($_POST['c_eselon_iii'] || $_GET['c_eselon_iii']){$cari .= "  and c_eselon_iii='$c_eselon_iii' ";}

			if ($_POST['c_eselon_iv']){$c_eselon_iv=$_POST['c_eselon_iv'];}
			else{$c_eselon_iv=$_GET['c_eselon_iv'];}
			
			$c_eselon_ivx=$c_eselon_iv;
			$c_eselon_iv=substr($c_eselon_iv,0,2);
			$n_eselon_iv=substr($c_eselon_ivx,2,200);
			if ($_POST['c_eselon_iv'] || $_GET['c_eselon_iv']){$cari .= " and c_eselon_iv='$c_eselon_iv' ";}
		

			$this->view->c_eselon_i = $c_eselon_i;
			

			
		}
		else
		{


			if ($_POST['c_lokasi_unitkerja']){$c_lokasi_unitkerja=trim($_POST['c_lokasi_unitkerja']);}
			else if (trim($_GET['c_lokasi_unitkerja'])) {$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);}
			$cari .= " and  c_lokasi_unitkerja='$c_lokasi_unitkerja'";
			//$this->view->lokasiList = $this->reff_serv->getLokasi('');
			//$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

			
			if ($_POST['c_eselon_i']){$c_eselon_i=$_POST['c_eselon_i'];}
			else{$c_eselon_i=trim($_GET['c_eselon_i']);}
			$this->view->c_eselon_i =$c_eselon_i;
			if ($c_eselon_i){
				$expesl1 = explode(";",$c_eselon_i);
				$c_eselon_i=$expesl1[0];
				$n_eselon_i=$expesl1[1];
			}
			
			if ($_POST['c_eselon_ii']){$c_eselon_ii=$_POST['c_eselon_ii'];}
			else{$c_eselon_ii=trim($_GET['c_eselon_ii']);}
			$this->view->c_eselon_ii =$c_eselon_ii;
			if ($c_eselon_ii){
			$expesl2 = explode(";",$c_eselon_ii);
			$c_eselon_ii=$expesl2[0];
			$c_parent=$expesl2[1];
			$n_eselon_ii=$expesl2[2];
			}
			
			
			if ($_POST['c_eselon_iii']){$c_eselon_iii=$_POST['c_eselon_iii'];}
			else{$c_eselon_iii=trim($_GET['c_eselon_iii']);}
			$this->view->c_eselon_iii =trim($c_eselon_iii);		
			if ($c_eselon_iii){
			$expesl3 = explode(";",$c_eselon_iii);	
			$c_eselon_iix=$expesl3[0];
			$c_eselon_iii=$expesl3[1];
			$c_satker=$expesl3[2];
			$n_eselon_iii=$expesl3[3];			
			}

			if ($_POST['c_eselon_iv']){$c_eselon_iv=$_POST['c_eselon_iv'];}
			else{$c_eselon_iv=trim($_GET['c_eselon_iv']);}
			$this->view->c_eselon_iv =trim($c_eselon_iv);	
			if ($c_eselon_iv){
			$expesl4 = explode(";",$c_eselon_iv);	
			$c_eselon_iv=$expesl4[0];
			$n_eselon_iv=$expesl4[1];
			}			
			

			
			if ($c_eselon_i){$cari .= " and c_eselon_i='$c_eselon_i'";}
			if ($c_eselon_ii ){$cari .= " and c_eselon_ii='$c_eselon_ii'";}
			if ($c_eselon_iii){$cari .= " and c_eselon_iii='$c_eselon_iii' and c_parent='$c_parent' and c_satker='$c_satker'";}
			if ($c_eselon_iv){$cari .= " and c_eselon_iv='$c_eselon_iv'";}			
			

		}				
	


if ($_POST['c_eselon']){$c_eselon=$_POST['c_eselon'];}
else{$c_eselon=trim($_GET['c_eselon']);}
$this->view->c_eselon=$c_eselon;

if ($_POST['blnawal']){$blnawal=$_POST['blnawal'];}
else{$blnawal=trim($_GET['blnawal']);}
$this->view->blnawal=$blnawal;

if ($_POST['c_golongan']){$c_golongan=$_POST['c_golongan'];}
else{$c_golongan=trim($_GET['c_golongan']);}
$this->view->c_golongan	=$c_golongan;

if ($_POST['c_status_kepegawaian']){$c_status_kepegawaian=$_POST['c_status_kepegawaian'];}
else{$c_status_kepegawaian=trim($_GET['c_status_kepegawaian']);}
$this->view->c_status_kepegawaian=$c_status_kepegawaian;
	
if ($_POST['thnakhir']){$thnakhir=$_POST['thnakhir'];}
else{$thnakhir=trim($_GET['thnakhir']);}
$this->view->thnakhir=$thnakhir;	

if ($_POST['thnawal']){$thnawal=$_POST['thnawal'];}
else{$thnawal=trim($_GET['thnawal']);}
$this->view->thnawal=$thnawal;

if ($_POST['c_lokasi_unitkerja']){$c_lokasi_unitkerja=$_POST['c_lokasi_unitkerja'];}
else{$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);}
$this->view->c_lokasi_unitkerja	=$c_lokasi_unitkerja;

if ($c_eselon){$cari= $cari." and c_eselon ='$c_eselon'";}

if ($c_golongan){$c_golongan=substr($_POST['c_golongan'],0,2); $cari= $cari." and c_golongan ='$c_golongan' ";}
if ($c_eselon){$cari= $cari." and c_eselon ='$c_eselon'";}

if ($blnawal){ 
$thnawalx=$thnawal*1-4;
$cari= $cari." and to_char(d_tmt_golongan,'yyyy-mm-dd') like '$thnawalx-$blnawal%' ";
}
elseif ($thnakhir){ 
$thnakhirx=$thnakhir*1-4;
$thnawalx=$thnawal*1-4;
$cari= $cari." and ((to_char(d_tmt_golongan,'yyyy-mm-dd') like '$thnawalx%') or (to_char(d_tmt_golongan,'yyyy-mm-dd') like '$thnakhirx%'))";
}
else{
$tgla ="01-01-$thnawal"; $tglb ="01-$blnawal-$thnakhir";
$thnawalx=$thnawal*1-4;
$cari= $cari." and to_char(d_tmt_golongan,'yyyy-mm-dd') like '$thnawalx%' ";
}

if ($_POST['c_eselon_i']){$this->view->c_eselon_i=$_POST['c_eselon_i'];}
else{$this->view->c_eselon_i=$_GET['c_eselon_i'];}	
if ($_POST['c_eselon_ii']){$this->view->c_eselon_ii=$_POST['c_eselon_ii'];}
else{$this->view->c_eselon_ii=$_GET['c_eselon_ii'];}
if ($_POST['c_eselon_iii']){$this->view->c_eselon_iii=$_POST['c_eselon_iii'];}
else{$this->view->c_eselon_iii=$_GET['c_eselon_iii'];}
if ($_POST['c_eselon_iv']){$this->view->c_eselon_iv=$_POST['c_eselon_iv'];}
else{$this->view->c_eselon_iv=$_GET['c_eselon_iv'];}
			
$cari= $cari." and (c_eselon !='17')"; 
//echo $cari;//.$carix;
		$totalpegawaiList = $this->pegawai_serv->getPegawaiList($cari.$carix, 0, 0 ,$orderBy);	
		$this->view->totalpegawaiList=$totalpegawaiList;	
		$pegawaiList = $this->pegawai_serv->getPegawaiList($cari.$carix,$currentPage,$numToDisplay,$orderBy );
		
		$jdllap=$_POST['judul_lap'];	
//if ($jdllap){$html='<font face="Bookman Old Style, Book Antiqua, Garamond" size="2"><b>PROYEKSI KENAIKAN PANGKAT REGULER TAHUN '.$_POST['tahun'].'<br>MAHKAMAH AGUNG</b></font>';}
//else{$html='<font face="Bookman Old Style, Book Antiqua, Garamond" size="2"><b>PROYEKSI KENAIKAN PANGKAT REGULER TAHUN '.$_POST['tahun'].'<br>MAHKAMAH AGUNG</b></font>';}
if ($c_lokasi_unitkerja=='1'){$n_lokasi_unitkerja='Kantor Pusat MA';}else{$n_lokasi_unitkerja='Pengadilan';}
if ($n_lokasi_unitkerja){$jdlesl=$n_lokasi_unitkerja;}
if ($n_eselon_i){$jdlesl=$n_lokasi_unitkerja."<br>".$n_eselon_i;}
if ($n_eselon_ii){$jdlesl=$n_lokasi_unitkerja."<br>".$n_eselon_ii."<br>".$n_eselon_i;}
if ($n_eselon_iii){$jdlesl=$n_lokasi_unitkerja."<br>".$n_eselon_iii."<br>".$n_eselon_ii."<br>".$n_eselon_i;}
if ($n_eselon_iv){$jdlesl=$n_lokasi_unitkerja."<br>".$n_eselon_iv." ".$n_eselon_iii."<br>".$n_eselon_ii."<br>".$n_eselon_i;}



if ($_POST['c_eselon_ii']){$c_eselon_ii=$_POST['c_eselon_ii'];}
else{$c_eselon_ii=$_GET['c_eselon_ii'];}
if ($_POST['c_eselon_i']){$c_eselon_i=$_POST['c_eselon_i'];}
else{$c_eselon_i=$_GET['c_eselon_i'];}
if ($_POST['c_eselon_iii']){$c_eselon_iii=$_POST['c_eselon_iii'];}
else{$c_eselon_iii=$_GET['c_eselon_iii'];}
if ($_POST['c_eselon_iv']){$c_eselon_iv=$_POST['c_eselon_iv'];}
else{$c_eselon_iv=$_GET['c_eselon_iv'];}

$htmlx='<font face="Bookman Old Style, Book Antiqua, Garamond" size="2px">PROYEKSI KENAIKAN PANGKAT REGULER TAHUN  '.$thnawal.'<br>LINGKUP '.strtoupper($jdlesl).'<br>MAHKAMAH AGUNG</font>';

$this->view->judul=$htmlx;
//$html=$html.'<div style="height: 470px; overflow: auto; padding: 5px">';
$html=$html.' <table align="center" border="0" cellspacing="1" cellpadding="2" class="sortable">
		<tr>
			<th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">No</font></th>
			<th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">Nama / NIP<br>TGL. LAHIR / UMUR </font></th>
			<th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">JABATAN<br>TMT JABATAN</font></th>
			<th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">PENDIDIKAN</font></th>
			<th colspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">GOL. RUANG & TMT</font></th>
		</tr>
		<tr>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">LAMA</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">BARU</font></th>
		</tr>		
		';
		if (count($pegawaiList)==0)
		{
		$html=$html.' <tr class="event2">
					<td>&nbsp</td>
					<td>&nbsp</td>
					<td>&nbsp</td>
					<td>&nbsp</td>
					<td>&nbsp</td>
					<td>&nbsp</td>
				</tr>';

		}		
	if (count($pegawaiList)!=0){
			for ($j = 0; $j < count($pegawaiList); $j++) 
				{
				
					$i_peg_nip=$pegawaiList[$j]['i_peg_nip_new'];
					$n_peg=$pegawaiList[$j]['n_peg'];
					$d_peg_lahir=$pegawaiList[$j]['d_peg_lahir'];
					$d_peg_lahir2=$pegawaiList[$j]['d_peg_lahir2'];					
					$usiatahun=$pegawaiList[$j]['usiatahun'];
					$usiabulan=$pegawaiList[$j]['usiabulan'];
					$c_golongan=$pegawaiList[$j]['c_golongan'];
					$n_golongan=$pegawaiList[$j]['n_golongan'];
					$d_tmt_golongan=$pegawaiList[$j]['d_tmt_golongan'];
					$c_penjenjangan=$pegawaiList[$j]['c_penjenjangan'];
					$n_pendidikan=$pegawaiList[$j]['n_pendidikan'];
					$jabatanlengkap=$pegawaiList[$j]['jabatanlengkap'];
					$d_mulai_jabat=$pegawaiList[$j]['d_mulai_jabat'];
					$d_tmt_eselon=$pegawaiList[$j]['d_tmt_eselon'];
					$masakerjabulan=$pegawaiList[$j]['masakerjabulan'];
					$masakerjatahun=$pegawaiList[$j]['masakerjatahun'];
					$n_golongan_next=$pegawaiList[$j]['n_golongan_next'];
					
					if ($masakerjatahun){$masakerja=$masakerjatahun.' tahun';}
					else if ($masakerjabulan){$masakerja=$masakerja." ".$masakerjabulan.' bulan';}					
					else {$masakerja="";}

					$d_tmt_kgb=$pegawaiList[$j]['d_tmt_kgb'];

			$d_tmt_golongan1=substr($d_tmt_golongan,0,2);
			$d_tmt_golongan2=substr($d_tmt_golongan,3,2);
			$d_tmt_golongan3=substr($d_tmt_golongan,6,4);
			
			if ($d_tmt_golongan1){
			$d_tmt_kgb_baru	= "$d_tmt_golongan1-$d_tmt_golongan2-".(4 + $d_tmt_golongan3);
			}
					$noUrut = (($currentPage -1)* $numToDisplay) +$j+1;
					if ($j%2==0) {
						  $html=$html.'<tr class="event">';
					 } else if ($j%2==1) { 
						  $html=$html.'<tr class="event2">';
					 }
					 $html=$html.'	<td align="right" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$noUrut.'.</font></td>
					 <td class="clleft" width="30%">
										<font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">
											'.$n_peg.'<br>NIP: '.$i_peg_nip.'<br>Tgl Lahir: '.$d_peg_lahir2.' <br>Usia: '.$usiatahun.' tahun '.$usiabulan.' bulan
										</font>
									</td>';
							if ($jabatanlengkap){
 $html=$html.'								
							<td align="left" width="40%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$jabatanlengkap.'<br>'.$d_mulai_jabat.'</font></td>';
							}else{
 $html=$html.'							
							<td align="left" width="40%">&nbsp;</td>';
							}
$html=$html.'

							<td align="left" width="10%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$n_pendidikan.'</font></td>							
							<td align="center" width="10%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$n_golongan.'<br>'.$d_tmt_golongan.'</font></td>
							<td align="center" width="10%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$n_golongan_next.'<br>'.$d_tmt_kgb_baru.'</font></td>
							</tr>';				 
				}
			}	
	$html=$html.'</table>';
	//</div>';
	$this->view->par=$_GET['par'];
	$this->view->view=$html;

	if ($_GET['par']=='exl'){
if ($_POST['c_golongan']){$c_golongan=$_POST['c_golongan'];}
else{$c_golongan=trim($_GET['c_golongan']);}	
?>
<script>

	//var url = "<?php echo $this->basePath; ?>/sdmmodule/monitoringkp/exl?find=<?echo $cari;?>";
	var url = "<?php echo $this->basePath; ?>/sdmmodule/monitoringkp/exl?limit=<?=$limit?>&c_eselon_i=<?=$c_eselon_i?>&c_eselon_ii=<?=$c_eselon_ii?>&c_eselon_iii=<?=$c_eselon_iii?>&c_eselon_iv=<?=$c_eselon_iv?>&c_eselon_v=<?=$c_eselon_v?>&c_lokasi_unitkerja=<?=$c_lokasi_unitkerja?>&blnawal=<?=$blnawal?>&c_eselon=<?=$c_eselon?>&c_golongan=<?=$c_golongan?>&c_status_kepegawaian=<?=$c_status_kepegawaian?>&thnakhir=<?=$thnakhir?>&thnawal=<?=$thnawal?>";
	var wid='1000';
	var heg='600';
	var w = 0; 
	var h = 0;
	w = screen.availWidth;
	h = screen.availHeight;
	var popW = wid, popH = heg;
	var leftc = (w-popW)/2;
	var topc = (h-popH)/2;
	var selectWindow = window.open(url,'Selection', 'left=' + leftc + ',top=' + topc + ', width='+popW+',height='+popH+',resizable=0,scrollbars=yes');	
</script>
<?	
	$this->_helper->viewRenderer('blank');
	}
	else{
	$this->_helper->viewRenderer('view');			
	}
	}
	


public function exlAction() {
$html=""; 
$cari="";
		
		$c_eselon = $_GET['c_eselon'];
		$c_lokasi_unitkerja = $_GET['c_lokasi_unitkerja'];
		$c_eselon_i = $_GET['c_eselon_i'];
		$c_eselon_ii = $_GET['c_eselon_ii'];
		$c_eselon_iii = $_GET['c_eselon_iii'];
		$c_eselon_iv = $_GET['c_eselon_iv'];
		$c_eselon_v = $_GET['c_eselon_v'];
		
		$start=$_GET['start'];
		$limit=$_GET['limit'];		

$html="";
		$currentPage=$_GET['currentPage'];
		if((!$currentPage) || ($currentPage == 'undefined'))
			{$currentPage = $start;}
			//echo $currentPage."  ".$numToDisplay;			
			if (!$limit){$limit=$_GET['limit'];}
			$numToDisplay = $limit;
			$this->view->numToDisplay = $numToDisplay;
			$this->view->currentPage = $currentPage;
	$cari="";	
		//$cari =" and i_peg_nip='150182962' ";
		$this->view->limit=$numToDisplay;

$carix="
and ((c_eselon in('01','02','03') and c_golongan in('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17') 
or (c_eselon = '04' and c_golongan in('04','05','06','07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_eselon = '03' and c_golongan in('02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_eselon = '05' and c_golongan in('04','05','06','07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_eselon = '06' and c_golongan in('05','06','07','08','09','10','11','12','13','14','15','16','17') ) 
or ((c_eselon = '07' or c_eselon = '08') and c_golongan in('05','06','07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_eselon = '15' and ( (c_pend = '29' and c_golongan in('12','13','14','15','16','17') ) 
or (c_pend = '41' and c_golongan in('11','12','13','14','15','16','17') ) 
or (c_pend = '40' and c_golongan in('08','09','10','11','12','13','14','15','16','17') ) 
or ((c_pend = '04' or c_pend = '05') and c_golongan in('08','09','10','11','12','13','14','15','16','17') ) 
or (c_pend = '32' and c_golongan in('07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_pend = '07' and c_golongan in('06','07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_pend = '36' and c_golongan in('06','07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_pend = '09' and c_golongan in('05','06','07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_pend = '10' and c_golongan in('04','05','06','07','08','09','10','11','12','13','14','15','16','17') ) ) ) ))";
		
		//Unit Kerja=====================
				
		if ($c_lokasi_unitkerja=='1'){
			
		$c_eselon_i=trim($this->view->c_eselon_i);	
			if ($c_eselon_i!='01'){
				$c_eselon_i=trim($this->view->c_eselon_i);
				$cari .= " and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'";
			}
			else{
				if ($_GET['c_eselon_i']){$c_eselon_i=$_GET['c_eselon_i'];}
				else{$c_eselon_i=$_GET['c_eselon_i'];}
				
				$c_eselon_i=substr($c_eselon_i,0,2);
				$n_eselon_i=substr($_GET['c_eselon_i'],2,200);
				if ($_GET['c_eselon_i'] || $_GET['c_eselon_i']){$cari .= " and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'";}

			}	

			if ($_GET['c_eselon_ii']){$c_eselon_ii=$_GET['c_eselon_ii'];}
			else{$c_eselon_ii=$_GET['c_eselon_ii'];}
			$c_eselon_ii=substr($c_eselon_ii,0,3);
			$n_eselon_ii=substr($_GET['c_eselon_ii'],3,200);
			if ($_GET['c_eselon_ii'] || $_GET['c_eselon_ii']){$cari .= " and c_eselon_ii='$c_eselon_ii' ";}
			
			if ($_GET['c_eselon_iii']){$c_eselon_i=$_GET['c_eselon_iii'];}
			else{$c_eselon_iii=$_GET['c_eselon_iii'];}
			
			
			$c_eselon_iii=substr($c_eselon_iii,0,2);
			$n_eselon_iii=substr($_GET['c_eselon_iii'],2,200);
			if ($_GET['c_eselon_iii'] || $_GET['c_eselon_iii']){$cari .= "  and c_eselon_iii='$c_eselon_iii' ";}

			if ($_GET['c_eselon_iv']){$c_eselon_iv=$_GET['c_eselon_iv'];}
			else{$c_eselon_iv=$_GET['c_eselon_iv'];}
			
			
			$c_eselon_iv=substr($c_eselon_iv,0,2);
			$n_eselon_iv=substr($_GET['c_eselon_iv'],2,200);
			if ($_GET['c_eselon_iv'] || $_GET['c_eselon_iv']){$cari .= " and c_eselon_iv='$c_eselon_iv' ";}
		

			$this->view->c_eselon_i = $c_eselon_i;
			
			if ($_GET['c_eselon_i']){$this->view->c_eselon_i=$_GET['c_eselon_i'];}
			else{$this->view->c_eselon_i=$_GET['c_eselon_i'];}	
			if ($_GET['c_eselon_ii']){$this->view->c_eselon_ii=$_GET['c_eselon_ii'];}
			else{$this->view->c_eselon_ii=$_GET['c_eselon_ii'];}
			if ($_GET['c_eselon_iii']){$this->view->c_eselon_iii=$_GET['c_eselon_iii'];}
			else{$this->view->c_eselon_iii=$_GET['c_eselon_iii'];}
			if ($_GET['c_eselon_iv']){$this->view->c_eselon_iv=$_GET['c_eselon_iv'];}
			else{$this->view->c_eselon_iv=$_GET['c_eselon_iv'];}
			
		}
		else
		{


			if ($_GET['c_lokasi_unitkerja']){$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);}
			else{$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);}
			//$this->view->lokasiList = $this->reff_serv->getLokasi('');
			//$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

			
			if ($_GET['c_eselon_i']){$c_eselon_i=$_GET['c_eselon_i'];}
			else{$c_eselon_i=trim($_GET['c_eselon_i']);}
			$this->view->c_eselon_i =$c_eselon_i;
			if ($c_eselon_i){
				$expesl1 = explode(";",$c_eselon_i);
				$c_eselon_i=$expesl1[0];
				$n_eselon_i=$expesl1[1];
			}
			
			if ($_GET['c_eselon_ii']){$c_eselon_ii=$_GET['c_eselon_ii'];}
			else{$c_eselon_ii=trim($_GET['c_eselon_ii']);}
			$this->view->c_eselon_ii =$c_eselon_ii;
			if ($c_eselon_ii){
			$expesl2 = explode(";",$c_eselon_ii);
			$c_eselon_ii=$expesl2[0];
			$c_parent=$expesl2[1];
			$n_eselon_ii=$expesl2[2];
			}
			
			
			if ($_GET['c_eselon_iii']){$c_eselon_iii=$_GET['c_eselon_iii'];}
			else{$c_eselon_iii=trim($_GET['c_eselon_iii']);}
			$this->view->c_eselon_iii =trim($c_eselon_iii);		
			if ($c_eselon_iii){
			$expesl3 = explode(";",$c_eselon_iii);	
			$c_eselon_iix=$expesl3[0];
			$c_eselon_iii=$expesl3[1];
			$c_satker=$expesl3[2];
			$n_eselon_iii=$expesl3[3];			
			}

			if ($_GET['c_eselon_iv']){$c_eselon_iv=$_GET['c_eselon_iv'];}
			else{$c_eselon_iv=trim($_GET['c_eselon_iv']);}
			$this->view->c_eselon_iv =trim($c_eselon_iv);	
			if ($c_eselon_iv){
			$expesl4 = explode(";",$c_eselon_iv);	
			$c_eselon_iv=$expesl4[0];
			$n_eselon_iv=$expesl4[1];
			}			
			

			
			if ($c_eselon_i){$cari .= " and c_eselon_i='$c_eselon_i'";}
			if ($c_eselon_ii ){$cari .= " and c_eselon_ii='$c_eselon_ii'";}
			if ($c_eselon_iii){$cari .= " and c_eselon_iii='$c_eselon_iii' and c_parent='$c_parent' and c_satker='$c_satker'";}
			if ($c_eselon_iv){$cari .= " and c_eselon_iv='$c_eselon_iv'";}			
			

		}				
	
// $c_eselon=$_GET['c_eselon'];
// $c_status_kepegawaian=$_GET['c_status_kepegawaian'];
// $c_golongan=$_GET['c_golongan'];
// $blnawal=$_GET['blnawal'];
// $thnawal=$_GET['thnawal'];
// $thnakhir=$_GET['thnakhir'];
// if ($_GET['blnawal']){$blnawal=$_GET['blnawal'];

if ($_GET['c_eselon']){$c_eselon=$_GET['c_eselon'];}
else{$c_eselon=trim($_GET['c_eselon']);}
$this->view->c_eselon=$c_eselon;

if ($_GET['blnawal']){$blnawal=$_GET['blnawal'];}
else{$blnawal=trim($_GET['blnawal']);}
$this->view->blnawal=$blnawal;



if ($_GET['c_status_kepegawaian']){$c_status_kepegawaian=$_GET['c_status_kepegawaian'];}
else{$c_status_kepegawaian=trim($_GET['c_status_kepegawaian']);}
$this->view->c_status_kepegawaian=$c_status_kepegawaian;
	
if ($_GET['thnakhir']){$thnakhir=$_GET['thnakhir'];}
else{$thnakhir=trim($_GET['thnakhir']);}
$this->view->thnakhir=$thnakhir;	

if ($_GET['thnawal']){$thnawal=$_GET['thnawal'];}
else{$thnawal=trim($_GET['thnawal']);}
$this->view->thnawal=$thnawal;

if ($_GET['c_lokasi_unitkerja']){$c_lokasi_unitkerja=$_GET['c_lokasi_unitkerja'];}
else{$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);}
$this->view->c_lokasi_unitkerja	=$c_lokasi_unitkerja;

if ($c_eselon){$cari= $cari." and c_eselon ='$c_eselon'";}
/* if ($c_eselon && $c_status_kepegawaian){
	$c_golongan=substr($_GET['c_golongan'],0,2); 
	$cari= $cari." and c_golongan ='$c_golongan' and c_status_kepegawaian='$c_status_kepegawaian' ";
} */
if ($_GET['c_golongan']){$c_golongan=$_GET['c_golongan'];}
if ($c_golongan){$c_golongan=substr($_GET['c_golongan'],0,2); $cari= $cari." and c_golongan ='$c_golongan' ";}
if ($c_eselon){$cari= $cari." and c_eselon ='$c_eselon'";}

if ($blnawal){ 
$thnawal=$thnawal*1-4;
$cari= $cari." and to_char(d_tmt_golongan,'yyyy-mm-dd') like '$thnawal-$blnawal%' ";
}
elseif ($thnakhir){ 
$thnakhir=$thnakhir*1-4;
$thnawal=$thnawal*1-4;
$cari= $cari." and ((to_char(d_tmt_golongan,'yyyy-mm-dd') like '$thnawal%') or (to_char(d_tmt_golongan,'yyyy-mm-dd') like '$thnakhir%'))";
}
else{
$tgla ="01-01-$thnawal"; $tglb ="01-$blnawal-$thnakhir";
$thnawal=$thnawal*1-4;
$cari= $cari." and to_char(d_tmt_golongan,'yyyy-mm-dd') like '$thnawal%' ";
}
$cari= $cari." and (c_eselon !='17')";

//echo $cari.$carix;
$pegawaiList = $this->pegawai_serv->getPegawaiListAll($cari.$carix);

if ($c_lokasi_unitkerja=='1'){$n_lokasi_unitkerja='Kantor Pusat MA';}else{$n_lokasi_unitkerja='Pengadilan';}
if ($n_lokasi_unitkerja){$jdlesl=$n_lokasi_unitkerja;}
if ($n_eselon_i){$jdlesl=$n_lokasi_unitkerja."<br>".$n_eselon_i;}
if ($n_eselon_ii){$jdlesl=$n_lokasi_unitkerja."<br>".$n_eselon_ii."<br>".$n_eselon_i;}
if ($n_eselon_iii){$jdlesl=$n_lokasi_unitkerja."<br>".$n_eselon_iii."<br>".$n_eselon_ii."<br>".$n_eselon_i;}
if ($n_eselon_iv){$jdlesl=$n_lokasi_unitkerja."<br>".$n_eselon_iv." ".$n_eselon_iii."<br>".$n_eselon_ii."<br>".$n_eselon_i;}

$html='<center><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">PROYEKSI KENAIKAN PANGKAT REGULER TAHUN  '.$thnawal.'<br>LINGKUP '.strtoupper($jdlesl).'<br>MAHKAMAH AGUNG</font></center>';

$html=$html.' <table align="center" border="1" width="100%">
		<tr>
			<th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">No</th>
			<th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">Nama / NIP<br>TGL. LAHIR / UMUR </th>
			<th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">JABATAN<br>TMT JABATAN</th>
			<th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">PENDIDIKAN</th>
			<th colspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">GOL. RUANG & TMT</th>
		</tr>
		<tr>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">LAMA</th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">BARU</th>
		</tr>		
		';
		if (count($pegawaiList)==0)
		{
		$html=$html.' <tr>
					<td>&nbsp</td>
					<td>&nbsp</td>
					<td>&nbsp</td>
					<td>&nbsp</td>
					<td>&nbsp</td>
				</tr>';

		}		
	if (count($pegawaiList)!=0){
			for ($j = 0; $j < count($pegawaiList); $j++) 
				{
				
					$i_peg_nip=$pegawaiList[$j]['i_peg_nip_new'];
					$n_peg=$pegawaiList[$j]['n_peg'];
					$d_peg_lahir=$pegawaiList[$j]['d_peg_lahir'];
					$d_peg_lahir2=$pegawaiList[$j]['d_peg_lahir2'];					
					$usiatahun=$pegawaiList[$j]['usiatahun'];
					$usiabulan=$pegawaiList[$j]['usiabulan'];
					$c_golongan=$pegawaiList[$j]['c_golongan'];
					$n_golongan=$pegawaiList[$j]['n_golongan'];
					$d_tmt_golongan=$pegawaiList[$j]['d_tmt_golongan'];
					$c_penjenjangan=$pegawaiList[$j]['c_penjenjangan'];
					$n_pendidikan=$pegawaiList[$j]['n_pendidikan'];
					$jabatanlengkap=$pegawaiList[$j]['jabatanlengkap'];
					$d_mulai_jabat=$pegawaiList[$j]['d_mulai_jabat'];
					$d_tmt_eselon=$pegawaiList[$j]['d_tmt_eselon'];
					$masakerjabulan=$pegawaiList[$j]['masakerjabulan'];
					$masakerjatahun=$pegawaiList[$j]['masakerjatahun'];
					$n_golongan_next=$pegawaiList[$j]['n_golongan_next'];
					
					if ($masakerjatahun){$masakerja=$masakerjatahun.' tahun';}
					else if ($masakerjabulan){$masakerja=$masakerja." ".$masakerjabulan.' bulan';}					
					else {$masakerja="";}

					$d_tmt_kgb=$pegawaiList[$j]['d_tmt_kgb'];

			$d_tmt_golongan1=substr($d_tmt_golongan,0,2);
			$d_tmt_golongan2=substr($d_tmt_golongan,3,2);
			$d_tmt_golongan3=substr($d_tmt_golongan,6,4);
			
			$d_tmt_kgb_baru	= "$d_tmt_golongan1-$d_tmt_golongan2-".(4 + $d_tmt_golongan3);
					$no++;	
					$html=$html.'<tr>';
					 $html=$html.'	<td  valign="top" align="left" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">'.$no.'</td>
					 
					 <td  valign="top" align="left" width="30%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">
										
											'.$n_peg.'<br>NIP: '.$i_peg_nip.'<br>Tgl Lahir: '.$d_peg_lahir2.' <br>Usia: '.$usiatahun.' tahun '.$usiabulan.' bulan
										
									</td>';
							if ($jabatanlengkap){
 $html=$html.'								
							<td  valign="top" align="left" width="40%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">'.$jabatanlengkap.'<br>'.$d_mulai_jabat.'</td>';
							}else{
 $html=$html.'							
							<td  valign="top" align="left" width="40%">&nbsp;</td>';
							}
$html=$html.'

							<td  valign="top" align="center" width="10%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">'.$n_pendidikan.'</td>							
							<td  valign="top" align="center" width="10%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">'.$n_golongan.'<br>'.$d_tmt_golongan.'</td>
							<td valign="top" align="center" width="10%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">'.$n_golongan_next.'<br>'.$d_tmt_kgb_baru.'</td>
							</tr>';				 
				}
			}	
	$html=$html.'</table>';
	$this->view->par=$_GET['par'];
	$this->view->view=$html;


}	
	
}

?>