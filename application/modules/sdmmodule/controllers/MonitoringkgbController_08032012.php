<?php
require_once 'Zend/Controller/Action.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Monitoring_Service.php";
class Sdmmodule_MonitoringkgbController extends Zend_Controller_Action
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
	public function monitoringkgbjsAction() 
	{
		header('content-type : text/javascript');
		$this->render('monitoringkgbjs');
	}
	public function monitoringkgbAction() 
	{
	   	$this->view->eselonList = $this->reff_serv->getEselon('');
		
		//$this->view->lokasiList = $this->reff_serv->getLokasi('');
		
		
	   	$c_lokasi_unitkerja=trim($this->view->c_lokasi_unitkerja);
		$c_eselon_i=trim($this->view->c_eselon_i);
		if ($c_eselon_i!='06'){
			$this->view->lokasiList = $this->reff_serv->getLokasi(" and c_lokasi='$c_lokasi_unitkerja'");
			if ($c_lokasi_unitkerja=='1'){
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='1' and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}
			else{
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}
		}
		else{
			$this->view->lokasiList = $this->reff_serv->getLokasi("");
			if ($c_lokasi_unitkerja=='1'){
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}
			else{
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}		
		
		}
		

		$this->view->c_eselon_i=$c_eselon_i;
		$this->view->c_lokasi_unitkerja=$c_lokasi_unitkerja;		
		
		$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai('');
		$this->view->statusKePegRef = $this->reff_serv->getStatusKepegawaian(" and c_status_kepegawaian in ('3','4','5','6')");

	}	
    public function listgolonganAction() {
	$c_status_kepegawaian=$_GET['c_status_kepegawaian'];
	if ($c_status_kepegawaian=='1' || $c_status_kepegawaian=='2' || $c_status_kepegawaian=='3')
	{$carigol=" and c_peg_tipegolongan ='3' ";}
	if ($c_status_kepegawaian=='4')
	{$carigol=" and c_peg_tipegolongan ='4' ";}
	if ($c_status_kepegawaian=='5')
	{$carigol=" and c_peg_tipegolongan ='5' ";}	
	if ($c_status_kepegawaian=='6')
	{$carigol=" and c_peg_tipegolongan ='6' ";}	
	if ($c_status_kepegawaian=='7')
	{$carigol=" and c_peg_tipegolongan ='7' ";}	
	$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai($carigol); 
	$this->view->c_status_kepegawaian=$c_status_kepegawaian;
    }	
	
	public function viewAction() 
	{
		$c_eselon = $_POST['c_eselon'];
		$c_lokasi_unitkerja = $_POST['c_lokasi_unitkerja'];
		$c_eselon_i = $_POST['c_eselon_i'];
		$c_eselon_ii = $_POST['c_eselon_ii'];
		$c_eselon_iii = $_POST['c_eselon_iii'];
		$c_eselon_iv = $_POST['c_eselon_iv'];
		$c_eselon_v = $_POST['c_eselon_v'];
		
		$start=$_POST['start'];
		$limit=$_POST['limit'];		

$html="";
		$currentPage=$_GET['currentPage'];
		if((!$currentPage) || ($currentPage == 'undefined'))
			{$currentPage = $start;}
			//echo $currentPage."  ".$numToDisplay;			
			if (!$limit){$limit=$_GET['limit'];}
			$numToDisplay = $limit;
			$this->view->numToDisplay = $numToDisplay;
			$this->view->currentPage = $currentPage;
		
		//$cari =" and i_peg_nip='150182962' ";
		$this->view->limit=$numToDisplay;
		
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
				
				
		if ($c_lokasi_unitkerja=='1'){
			
			if ($_POST['c_eselon_i']){$c_eselon_i=$_POST['c_eselon_i'];}
			else{$c_eselon_i=$_GET['c_eselon_i'];}
			
			$c_eselon_i=substr($c_eselon_i,0,2);
			if ($_POST['c_eselon_i'] || $_GET['c_eselon_i']){$cari .= " and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'";}

			if ($_POST['c_eselon_ii']){$c_eselon_ii=$_POST['c_eselon_ii'];}
			else{$c_eselon_ii=$_GET['c_eselon_ii'];}
			$c_eselon_ii=substr($c_eselon_ii,0,2);
			if ($_POST['c_eselon_ii'] || $_GET['c_eselon_ii']){$cari .= " and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and c_lokasi_unitkerja='$c_lokasi_unitkerja'";}
			
			if ($_POST['c_eselon_iii']){$c_eselon_i=$_POST['c_eselon_iii'];}
			else{$c_eselon_iii=$_GET['c_eselon_iii'];}
			
			
			$c_eselon_iii=substr($c_eselon_iii,0,2);
			if ($_POST['c_eselon_iii'] || $_GET['c_eselon_iii']){$cari .= " and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_lokasi_unitkerja='$c_lokasi_unitkerja'";}

			if ($_POST['c_eselon_iv']){$c_eselon_iv=$_POST['c_eselon_iv'];}
			else{$c_eselon_iv=$_GET['c_eselon_iv'];}
			
			
			$c_eselon_iv=substr($c_eselon_iv,0,2);
			if ($_POST['c_eselon_iv'] || $_GET['c_eselon_iv']){$cari .= " and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_lokasi_unitkerja='$c_lokasi_unitkerja'";}
		

			$this->view->c_eselon_i = $c_eselon_i;
			

			
			$this->view->lokasiList = $this->reff_serv->getLokasi('');
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			
			
			
			if ($_POST['c_eselon_i']){$this->view->c_eselon_i=$_POST['c_eselon_i'];}
			else{$this->view->c_eselon_i=$_GET['c_eselon_i'];}	
			
			$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	

			if ($_POST['c_eselon_ii']){$this->view->c_eselon_ii=$_POST['c_eselon_ii'];}
			else{$this->view->c_eselon_ii=$_GET['c_eselon_ii'];}
				
			
			$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");


			if ($_POST['c_eselon_iii']){$this->view->c_eselon_iii=$_POST['c_eselon_iii'];}
			else{$this->view->c_eselon_iii=$_GET['c_eselon_iii'];}
				
			
			$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

			if ($_POST['c_eselon_iv']){$this->view->c_eselon_iv=$_POST['c_eselon_iv'];}
			else{$this->view->c_eselon_iv=$_GET['c_eselon_iv'];}
				
			
			$this->view->eselonvList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

		}
		else
		{


			if ($_POST['c_lokasi_unitkerja']){$c_lokasi_unitkerja=trim($_POST['c_lokasi_unitkerja']);}
			else{$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);}
			$this->view->lokasiList = $this->reff_serv->getLokasi('');
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and c_tkt_esl='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

			
			if ($_POST['c_eselon_i']){$c_eselon_i=$_POST['c_eselon_i'];}
			else{$c_eselon_i=trim($_GET['c_eselon_i']);}
			$this->view->c_eselon_i =$c_eselon_i;
			if ($c_eselon_i){
				$expesl1 = explode(";",$c_eselon_i);
				$c_eselon_i=$expesl1[0];
			}
			
			if ($_POST['c_eselon_ii']){$c_eselon_ii=$_POST['c_eselon_ii'];}
			else{$c_eselon_ii=trim($_GET['c_eselon_ii']);}
			$this->view->c_eselon_ii =$c_eselon_ii;
			if ($c_eselon_ii){
			$expesl2 = explode(";",$c_eselon_ii);
			$c_eselon_ii=$expesl2[0];
			$c_parent=$expesl2[1];
			}
			
			
			if ($_POST['c_eselon_iii']){$c_eselon_iii=$_POST['c_eselon_iii'];}
			else{$c_eselon_iii=trim($_GET['c_eselon_iii']);}
			$this->view->c_eselon_iii =trim($c_eselon_iii);		
			if ($c_eselon_iii){
			$expesl3 = explode(";",$c_eselon_iii);	
			$c_eselon_iix=$expesl3[0];
			$c_eselon_iii=$expesl3[1];
			$c_satker=$expesl3[2];			
			}

			if ($_POST['c_eselon_iv']){$c_eselon_iv=$_POST['c_eselon_iv'];}
			else{$c_eselon_iv=trim($_GET['c_eselon_iv']);}
			$this->view->c_eselon_iv =trim($c_eselon_iv);	
			if ($c_eselon_iv){
			$expesl4 = explode(";",$c_eselon_iv);	
			$c_eselon_iv=$expesl4[0];
			}			
			

			
			if ($c_eselon_i){$cari .= " and c_eselon_i='$c_eselon_i'";}
			if ($c_eselon_ii ){$cari .= " and c_eselon_ii='$c_eselon_ii'";}
			if ($c_eselon_iii){$cari .= " and c_eselon_iii='$c_eselon_iii' and c_parent='$c_parent' and c_satker='$c_satker'";}
			if ($c_eselon_iv){$cari .= " and c_eselon_iv='$c_eselon_iv'";}
			
			//echo $cari;
			//echo " and c_eselon_i='$c_eselon_i'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child <> '00' and c_parent ='$c_parent'  and c_lokasi_unitkerja='$c_lokasi_unitkerja'";
			
			$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_iii = '00'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child = '00' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child <> '00' and c_parent ='$c_parent'  and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_iii <> '00' and c_eselon_ii='$c_eselon_iix'  and c_lokasi_unitkerja='$c_lokasi_unitkerja' ");
			
						



		}				
	
$c_eselon=$_POST['c_eselon'];
$c_status_kepegawaian=$_POST['c_status_kepegawaian'];
$c_golongan=$_POST['c_golongan'];
$blnawal=$_POST['blnawal'];
$thnawal=$_POST['thnawal'];
$thnakhir=$_POST['thnakhir'];

if ($c_eselon){$cari= $cari." and c_eselon ='$c_eselon'";}
if ($c_eselon && $c_status_kepegawaian){$c_golongan=substr($_POST['c_golongan'],0,2); 
$cari= $cari." and c_golongan ='$c_golongan' and c_status_kepegawaian='$c_status_kepegawaian' ";}
if ($c_eselon){$cari= $cari." and c_eselon ='$c_eselon'";}
if ($blnawal){ 
	$tgla ="01-$blnawal-$thnawal"; 
	$tglb ="01-$blnawal-$thnakhir";
	$cari= $cari." and (EXTRACT(years from AGE('$tgla', d_tmt_kgb))= 2 or EXTRACT(years from AGE('$tgla', d_tmt_kgb))= 4)";
}

$cari= $cari." and (c_eselon !='17' or c_eselon isnull)"; 

		$totalpegawaiList = $this->pegawai_serv->getPegawaiList($cari, 0, 0 ,$orderBy);	
		$this->view->totalpegawaiList=$totalpegawaiList;	
		$pegawaiList = $this->pegawai_serv->getPegawaiList($cari,$currentPage,$numToDisplay,$orderBy );
		$jdllap=$_POST['judul_lap'];	
if ($jdllap){$html='<font face="Bookman Old Style, Book Antiqua, Garamond" size="2"><b>PROYEKSI KENAIKAN GAJI BERKALA TAHUN '.$_POST['tahun'].'<br>MAHKAMAH AGUNG</b></font>';}
else{$html='<font face="Bookman Old Style, Book Antiqua, Garamond" size="2"><b>PROYEKSI KENAIKAN GAJI BERKALA TAHUN '.$_POST['tahun'].'<br>MAHKAMAH AGUNG</b></font>';}

$html=$html.'<div style="height: 470px; overflow: auto; padding: 5px">';
$html=$html.' <table align="center" border="0" cellspacing="1" cellpadding="2" class="sortable">
		<tr>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">Nama / NIP<br>TGL. LAHIR / UMUR </font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">GOL./ RUANG<br>T M T</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">JABATAN<br>a.TMT JABATAN<br>b.TMT ESELON</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">LAMA</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">BARU</font></th>
		</tr>';
		if (count($pegawaiList)==0)
		{
		$html=$html.' <tr class="event2">
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
				
					$i_peg_nip=$pegawaiList[$j]['i_peg_nip'];
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
					if ($masakerjatahun){$masakerja=$masakerjatahun.' tahun';}
					else if ($masakerjabulan){$masakerja=$masakerja." ".$masakerjabulan.' bulan';}					
					else {$masakerja="";}

					$d_tmt_kgb=$pegawaiList[$j]['d_tmt_kgb'];

			$d_tmt_kgb1=substr($d_tmt_kgb,0,2);
			$d_tmt_kgb2=substr($d_tmt_kgb,3,2);
			$d_tmt_kgb3=substr($d_tmt_kgb,6,4);
			
			if ($d_tmt_kgb1){
			$d_tmt_kgb_baru	= "$d_tmt_kgb1-$d_tmt_kgb2-".(2 + $d_tmt_kgb3);
			}
					$no++;	
					if ($j%2==0) {
						  $html=$html.'<tr class="event">';
					 } else if ($j%2==1) { 
						  $html=$html.'<tr class="event2">';
					 }
					 $html=$html.'	<td class="clleft">
										<font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">
											'.$n_peg.'<br>NIP: '.$i_peg_nip.'<br>Tgl Lahir: '.$d_peg_lahir2.' <br>Usia: '.$usiatahun.' tahun '.$usiabulan.' bulan
										</font>
									</td>
							<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$n_golongan.'<br>'.$d_tmt_golongan.'</font></td>';
							if ($jabatanlengkap){
 $html=$html.'								
							<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$jabatanlengkap.'<br>a. '.$d_mulai_jabat.'<br>b. '.$d_tmt_eselon.'</font></td>';
							}else{
 $html=$html.'							
							<td class="clleft">&nbsp;</td>';
							}
$html=$html.'							
							<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$d_tmt_kgb.'</font></td>
							<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$d_tmt_kgb_baru.'</font></td>
							</tr>';				 
				}
			}	
	$html=$html.'</table></div>';
	$this->view->par=$_GET['par'];
	$this->view->view=$html;
	if ($_GET['par']=='exl'){
?>
<script>

	var url = "<?php echo $this->basePath; ?>/sdmmodule/monitoringkgb/exl?find=<?echo $cari;?>";
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
$cari=$_GET['find'];
$cari=str_replace('\\', "", $cari);
$pegawaiList = $this->pegawai_serv->getPegawaiListAll($cari);
$jdllap=$_POST['judul_lap'];	
if ($jdllap){$html='<b>PROYEKSI KENAIKAN GAJI BERKALA TAHUN '.$_POST['tahun'].'<br>MAHKAMAH AGUNG</b>';}
else{$html='<b>PROYEKSI KENAIKAN GAJI BERKALA TAHUN '.$_POST['tahun'].'<br>MAHKAMAH AGUNG</b>';}

$html=$html.' <table align="center" border="1" cellspacing="1" cellpadding="2" width="100%">
		<tr>
			<td valign="top" align="center">Nama / NIP<br>TGL. LAHIR / UMUR </td>
			<td valign="top" align="center">GOL./ RUANG<br>T M T</td>
			<td valign="top" align="center">JABATAN<br>a.TMT JABATAN<br>b.TMT ESELON</td>
			<td valign="top" align="center">LAMA</td>
			<td valign="top" align="center">BARU</td>
		</tr>';
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
				
					$i_peg_nip=$pegawaiList[$j]['i_peg_nip'];
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
					if ($masakerjatahun){$masakerja=$masakerjatahun.' tahun';}
					else if ($masakerjabulan){$masakerja=$masakerja." ".$masakerjabulan.' bulan';}					
					else {$masakerja="";}

					$d_tmt_kgb=$pegawaiList[$j]['d_tmt_kgb'];

			$d_tmt_kgb1=substr($d_tmt_kgb,0,2);
			$d_tmt_kgb2=substr($d_tmt_kgb,3,2);
			$d_tmt_kgb3=substr($d_tmt_kgb,6,4);
			
			$d_tmt_kgb_baru	= "$d_tmt_kgb1-$d_tmt_kgb2-".(2 + $d_tmt_kgb3);
					$no++;	
					if ($j%2==0) {
						  $html=$html.'<tr class="event">';
					 } else if ($j%2==1) { 
						  $html=$html.'<tr class="event2">';
					 }
					 $html=$html.'	<td valign="top" align="left">
										
											'.$n_peg.'<br>NIP: '.$i_peg_nip.'<br>Tgl Lahir: '.$d_peg_lahir2.' <br>Usia: '.$usiatahun.' tahun '.$usiabulan.' bulan
										
									</td>
							<td valign="top" align="center">'.$n_golongan.'<br>'.$d_tmt_golongan.'</td>';
							if ($jabatanlengkap){
 $html=$html.'								
							<td valign="top" align="left">'.$jabatanlengkap.'<br>a. '.$d_mulai_jabat.'<br>b. '.$d_tmt_eselon.'</td>';
							}else{
 $html=$html.'							
							<td  valign="top" align="left">&nbsp;</td>';
							}
$html=$html.'							
							<td valign="top" align="center">'.$d_tmt_kgb.'</td>
							<td valign="top" align="center">'.$d_tmt_kgb_baru.'</td>
							</tr>';				 
				}
			}	
	$html=$html.'</table>';
	$this->view->par=$_GET['par'];
	$this->view->view=$html;


}	
	
}

?>