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
		$this->view->ceseloni=$ssologin->c_eselon_i;	
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
		$c_eselon_i=trim($this->view->ceseloni);
		if ($c_eselon_i!='01'){
			$this->view->lokasiList = $this->reff_serv->getLokasi(" and c_lokasi='$c_lokasi_unitkerja'");
			if ($c_lokasi_unitkerja=='1'){
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
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
		
		$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai("and c_peg_tipegolongan='3'");
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
		if($_POST['c_lokasi_unitkerja']) {$c_lokasi_unitkerja = $_POST['c_lokasi_unitkerja'];}
		else if ($_POST['c_lokasi_unitkerja']) {$c_lokasi_unitkerja=$_POST['c_lokasi_unitkerja'];}
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


			
			if ($_POST['c_lokasi_unitkerja']){$c_lokasi_unitkerja=trim($_POST['c_lokasi_unitkerja']);}
			else{$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);}
			$cari .= " and c_lokasi_unitkerja='$c_lokasi_unitkerja'";
			
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


$cari= $cari." and (c_eselon !='17')"; 
//tambahan

$m_bulan = $_POST['blnawal'];
	$m_tahun = $_POST['thnawal'];
	$m_tahun2 = $_POST['thnakhir'];
	$urutan = $_POST['urutan'];

	$tglcurent="$m_tahun-$m_bulan-01";
	
	if ($m_bulan) {
		//$cari= $cari." and (MONTHS_BETWEEN('$tglcurent',d_tmt_kgb) = 24 or MONTHS_BETWEEN('$tglcurent',d_tmt_kgb) = 48)";
		$cari= $cari." and ( date_part('Year', age('$tglcurent', d_tmt_kgb)) * 12 + date_part('Month', age('$tglcurent', d_tmt_kgb)) ) = 24";
	} else{
		$tahun1 = $m_tahun - 2;
		$cari= $cari." and (";
		if ($m_tahun2){
			$tahun2 = $m_tahun2 - 2;
		} else {
			$tahun2 = $tahun1;
		}
		$sqlsambung = '';
		for($z =$tahun1; $z < ($tahun2+1); $z++){
				if ($z > $tahun1) $sqlsambung = "or";
				$cari= $cari." $sqlsambung (to_char(d_tmt_kgb,'yyyy-mm-dd') like '$z%')";

		}
		$cari= $cari." ) ";
	}
if ($n_eselon_i){$jdlesl="<br>".$n_eselon_i;}
if ($n_eselon_ii){$jdlesl="<br>".$n_eselon_ii."<br>".$n_eselon_i;}
if ($n_eselon_iii){$jdlesl="<br>".$n_eselon_iii."<br>".$n_eselon_ii."<br>".$n_eselon_i;}
if ($n_eselon_iv){$jdlesl="<br>".$n_eselon_iv." ".$n_eselon_iii."<br>".$n_eselon_ii."<br>".$n_eselon_i;}

//echo $cari;

		$totalpegawaiList = $this->pegawai_serv->getPegawaiList($cari, 0, 0 ,$orderBy);	
		$this->view->totalpegawaiList=$totalpegawaiList;	
		$pegawaiList = $this->pegawai_serv->getPegawaiList($cari,$currentPage,$numToDisplay,$orderBy );
		$jdllap=$_POST['judul_lap'];
		
if ($_POST['c_eselon_ii']){$c_eselon_ii=$_POST['c_eselon_ii'];}
else{$c_eselon_ii=$_GET['c_eselon_ii'];}
if ($_POST['c_eselon_i']){$c_eselon_i=$_POST['c_eselon_i'];}
else{$c_eselon_i=$_GET['c_eselon_i'];}
if ($_POST['c_eselon_iii']){$c_eselon_iii=$_POST['c_eselon_iii'];}
else{$c_eselon_iii=$_GET['c_eselon_iii'];}
if ($_POST['c_eselon_iv']){$c_eselon_iv=$_POST['c_eselon_iv'];}
else{$c_eselon_iv=$_GET['c_eselon_iv'];}
		
if ($jdllap){$html='<center><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">PROYEKSI KENAIKAN GAJI BERKALA TAHUN '.$_POST['thnawal'].strtoupper($jdlesl).'<br>MAHKAMAH AGUNG</font></center>';}
else{$html='<center><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">PROYEKSI KENAIKAN GAJI BERKALA TAHUN '.$_POST['thnawal'].strtoupper($jdlesl).'<br>MAHKAMAH AGUNG</font></center>';}

$html=$html.'<div style="height: 470px; overflow: auto; padding: 5px">';
$html=$html.' <table align="center" border="0" cellspacing="1" cellpadding="2" class="sortable">
		<tr>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">No.</font></th>
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
				
					//$i_peg_nip=$pegawaiList[$j]['i_peg_nip'];
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
					  $noUrut = (($currentPage -1)* $numToDisplay) +$j+1;
					 $html=$html.'	
					 <td valign="top" align="right">'.$noUrut.'</td>
					 <td class="clleft">
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

	//var url = "<?php echo $this->basePath; ?>/sdmmodule/monitoringkgb/exl?find=<?echo $cari;?>";
	var url = "<?php echo $this->basePath; ?>/sdmmodule/monitoringkgb/exl?limit=<?=$limit?>&c_eselon_i=<?=$c_eselon_i?>&c_eselon_ii=<?=$c_eselon_ii?>&c_eselon_iii=<?=$c_eselon_iii?>&c_eselon_iv=<?=$c_eselon_iv?>&c_eselon_v=<?=$c_eselon_v?>&c_lokasi_unitkerja=<?=$c_lokasi_unitkerja?>&blnawal=<?=$blnawal?>&c_eselon=<?=$c_eselon?>&c_golongan=<?=$c_golongan?>&c_status_kepegawaian=<?=$c_status_kepegawaian?>&thnakhir=<?=$thnakhir?>&thnawal=<?=$thnawal?>";
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
		$this->view->limit=$numToDisplay;
		
		//Unit Kerja=====================
				
				
		if ($c_lokasi_unitkerja=='1'){
			
			$c_eselon_i=trim($this->view->c_eselon_i);	
			if ($c_eselon_i!='01'){
				$c_eselon_i=trim($this->view->c_eselon_i);
				$n_eselon_i=substr($_GET['c_eselon_i'],2,200);
				$cari .= " and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'";
			}
			else{
				if ($_GET['c_eselon_i']){$c_eselon_i=$_GET['c_eselon_i'];}
				else{$c_eselon_i=$_GET['c_eselon_i'];}
				
				$c_eselon_i=substr($c_eselon_i,0,2);
				$n_eselon_i=substr($_GET['c_eselon_i'],2,200);
				if ($_GET['c_eselon_i'] || $_GET['c_eselon_i']){$cari .= " and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'";}

			}	

			$c_eselon_ii=$_GET['c_eselon_ii'];
			$c_eselon_ii=substr($c_eselon_ii,0,3);
			$n_eselon_ii=substr($_GET['c_eselon_ii'],3,200);
			if ($_GET['c_eselon_ii']){$cari .= " and c_eselon_ii='$c_eselon_ii' ";}
			
			$c_eselon_iii=$_GET['c_eselon_iii'];		
			
			$c_eselon_iii=substr($c_eselon_iii,0,2);
			$n_eselon_iii=substr($_GET['c_eselon_iii'],2,200);
			if ($_GET['c_eselon_iii']){$cari .= "  and c_eselon_iii='$c_eselon_iii' ";}

			$c_eselon_iv=$_GET['c_eselon_iv'];
			
			
			$c_eselon_iv=substr($c_eselon_iv,0,2);
			$n_eselon_iv=substr($_GET['c_eselon_iv'],2,200);
			if ($_GET['c_eselon_iv']){$cari .= " and c_eselon_iv='$c_eselon_iv' ";}		

		}
		else
		{


			$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);
			
			$c_eselon_i=trim($_GET['c_eselon_i']);
			if ($c_eselon_i){
				$expesl1 = explode(";",$c_eselon_i);
				$c_eselon_i=$expesl1[0];
				$n_eselon_i=$expesl1[1];
			}
			
			$c_eselon_ii=trim($_GET['c_eselon_ii']);
			if ($c_eselon_ii){
			$expesl2 = explode(";",$c_eselon_ii);
			$c_eselon_ii=$expesl2[0];
			$c_parent=$expesl2[1];
			$n_eselon_ii=$expesl2[2];
			}
			
			$c_eselon_iii=trim($_GET['c_eselon_iii']);	
			if ($c_eselon_iii){
			$expesl3 = explode(";",$c_eselon_iii);	
			$c_eselon_iix=$expesl3[0];
			$c_eselon_iii=$expesl3[1];
			$c_satker=$expesl3[2];
			$n_eselon_iii=$expesl3[3];	
			}

			$c_eselon_iv=trim($_GET['c_eselon_iv']);
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
	
$c_eselon=$_GET['c_eselon'];
$c_status_kepegawaian=$_GET['c_status_kepegawaian'];
$c_golongan=$_GET['c_golongan'];
$blnawal=$_GET['blnawal'];
$thnawal=$_GET['thnawal'];
$thnakhir=$_GET['thnakhir'];

if ($c_eselon){$cari= $cari." and c_eselon ='$c_eselon'";}
if ($c_eselon && $c_status_kepegawaian){$c_golongan=substr($_GET['c_golongan'],0,2); 
$cari= $cari." and c_golongan ='$c_golongan' and c_status_kepegawaian='$c_status_kepegawaian' ";}
if ($c_eselon){$cari= $cari." and c_eselon ='$c_eselon'";}


$cari= $cari." and (c_eselon !='17')"; 
//tambahan

$m_bulan = $_GET['blnawal'];
	$m_tahun = $_GET['thnawal'];
	$m_tahun2 = $_GET['thnakhir'];
	$urutan = $_GET['urutan'];

	$tglcurent="01/$m_bulan"."/".$m_tahun;
	
	if ($m_bulan) {
		$cari= $cari." and (MONTHS_BETWEEN('$tglcurent',d_tmt_kgb) = 24 or MONTHS_BETWEEN('$tglcurent',d_tmt_kgb) = 48)";
	} else{
		$tahun1 = $m_tahun - 2;
		$cari= $cari." and (";
		if ($m_tahun2){
			$tahun2 = $m_tahun2 - 2;
		} else {
			$tahun2 = $tahun1;
		}
		$sqlsambung = '';
		for($z =$tahun1; $z < ($tahun2+1); $z++){
				if ($z > $tahun1) $sqlsambung = "or";
				$cari= $cari." $sqlsambung (to_char(d_tmt_kgb,'yyyy-mm-dd') like '$z%')";

		}
		$cari= $cari." ) ";
	}
if ($n_eselon_i){$jdlesl="<br>".$n_eselon_i;}
if ($n_eselon_ii){$jdlesl="<br>".$n_eselon_ii."<br>".$n_eselon_i;}
if ($n_eselon_iii){$jdlesl="<br>".$n_eselon_iii."<br>".$n_eselon_ii."<br>".$n_eselon_i;}
if ($n_eselon_iv){$jdlesl="<br>".$n_eselon_iv." ".$n_eselon_iii."<br>".$n_eselon_ii."<br>".$n_eselon_i;}

$pegawaiList = $this->pegawai_serv->getPegawaiListAll($cari);
$jdllap=$_POST['judul_lap'];	
$html='<center><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">PROYEKSI KENAIKAN GAJI BERKALA TAHUN '.$_POST['thnawal'].strtoupper($jdlesl).'<br>MAHKAMAH AGUNG</font></center>';


$html=$html.' <table align="center" border="1" cellspacing="1" cellpadding="2" width="100%">
		<tr>
			<td valign="top" align="center">NO</td>
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
					 $noUrut++;
					 $html=$html.'	
					 <td valign="top" align="right">'.$noUrut.'</td>
					 <td valign="top" align="left">
										
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