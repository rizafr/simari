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
		
		
		$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai('');	
		//$this->view->statusKePegRef = $this->reff_serv->getStatusKepegawaian('');    	
		$this->view->statusKePegRef = $this->reff_serv->getStatusKepegawaian(" and c_status_kepegawaian in ('3','4','5','6')");
		$this->view->c_eselon_i=$c_eselon_i;
		$this->view->c_lokasi_unitkerja=$c_lokasi_unitkerja;		
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
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			
			
			
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
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

			
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
if ($c_eselon && $c_status_kepegawaian){
	$c_golongan=substr($_POST['c_golongan'],0,2); 
	$cari= $cari." and c_golongan ='$c_golongan' and c_status_kepegawaian='$c_status_kepegawaian' ";
//echo " and c_golongan ='$c_golongan' and c_status_kepegawaian='$c_status_kepegawaian' ";	
}
if ($c_eselon){$cari= $cari." and c_eselon ='$c_eselon'";}

if ($blnawal){ $tgla ="01-$blnawal-$thnawal"; $tglb ="01-$blnawal-$thnakhir";
$cari= $cari." and (EXTRACT(years from AGE('$tgla', d_tmt_golongan))= 4)";}
$cari= $cari." and (c_eselon !='17' or c_eselon isnull)"; 

		$totalpegawaiList = $this->pegawai_serv->getPegawaiList($cari, 0, 0 ,$orderBy);	
		$this->view->totalpegawaiList=$totalpegawaiList;	
		$pegawaiList = $this->pegawai_serv->getPegawaiList($cari,$currentPage,$numToDisplay,$orderBy );
		
		$jdllap=$_POST['judul_lap'];	
if ($jdllap){$html='<font face="Bookman Old Style, Book Antiqua, Garamond" size="2"><b>PROYEKSI KENAIKAN PANGKAT REGULER TAHUN '.$_POST['tahun'].'<br>MAHKAMAH AGUNG</b></font>';}
else{$html='<font face="Bookman Old Style, Book Antiqua, Garamond" size="2"><b>PROYEKSI KENAIKAN PANGKAT REGULER TAHUN '.$_POST['tahun'].'<br>MAHKAMAH AGUNG</b></font>';}

$html=$html.'<div style="height: 470px; overflow: auto; padding: 5px">';
$html=$html.' <table align="center" border="0" cellspacing="1" cellpadding="2" class="sortable">
		<tr>
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
					$n_golongan_next=$pegawaiList[$j]['n_golongan_next'];
					
					if ($masakerjatahun){$masakerja=$masakerjatahun.' tahun';}
					else if ($masakerjabulan){$masakerja=$masakerja." ".$masakerjabulan.' bulan';}					
					else {$masakerja="";}

					$d_tmt_kgb=$pegawaiList[$j]['d_tmt_kgb'];

			$d_tmt_golongan1=substr($d_tmt_golongan,0,2);
			$d_tmt_golongan2=substr($d_tmt_golongan,3,2);
			$d_tmt_golongan3=substr($d_tmt_golongan,6,4);
			
			if ($d_tmt_golongan1){
			$d_tmt_kgb_baru	= "$d_tmt_golongan1-$d_tmt_golongan2-".(2 + $d_tmt_golongan3);
			}
					$no++;	
					if ($j%2==0) {
						  $html=$html.'<tr class="event">';
					 } else if ($j%2==1) { 
						  $html=$html.'<tr class="event2">';
					 }
					 $html=$html.'	<td class="clleft" width="30%">
										<font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">
											'.$n_peg.'<br>NIP: '.$i_peg_nip.'<br>Tgl Lahir: '.$d_peg_lahir2.' <br>Usia: '.$usiatahun.' tahun '.$usiabulan.' bulan
										</font>
									</td>';
							if ($jabatanlengkap){
 $html=$html.'								
							<td class="clleft" width="40%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$jabatanlengkap.'<br>'.$d_mulai_jabat.'</font></td>';
							}else{
 $html=$html.'							
							<td class="clleft" width="40%">&nbsp;</td>';
							}
$html=$html.'

							<td class="clleft" width="10%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$n_pendidikan.'</font></td>							
							<td class="clleft" width="10%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$n_golongan.'<br>'.$d_tmt_golongan.'</font></td>
							<td class="clleft" width="10%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$n_golongan_next.'<br>'.$d_tmt_kgb_baru.'</font></td>
							</tr>';				 
				}
			}	
	$html=$html.'</table></div>';
	$this->view->par=$_GET['par'];
	$this->view->view=$html;

	if ($_GET['par']=='exl'){
?>
<script>

	var url = "<?php echo $this->basePath; ?>/sdmmodule/monitoringkp/exl?find=<?echo $cari;?>";
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

$pegawaiList = $this->pegawai_serv->getPegawaiListAll($cari.$carix);


		$jdllap=$_POST['judul_lap'];	
if ($jdllap){$html='<b>PROYEKSI KENAIKAN PANGKAT REGULER TAHUN '.$_POST['tahun'].'<br>MAHKAMAH AGUNG</b>';}
else{$html='<b>PROYEKSI KENAIKAN PANGKAT REGULER TAHUN '.$_POST['tahun'].'<br>MAHKAMAH AGUNG</b>';}
$html=$html.' <table align="center" border="1" width="100%">
		<tr>
			<th rowspan="2">Nama / NIP<br>TGL. LAHIR / UMUR </th>
			<th rowspan="2">JABATAN<br>TMT JABATAN</th>
			<th rowspan="2">PENDIDIKAN</th>
			<th colspan="2">GOL. RUANG & TMT</th>
		</tr>
		<tr>
			<th>LAMA</th>
			<th>BARU</th>
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
					$n_golongan_next=$pegawaiList[$j]['n_golongan_next'];
					
					if ($masakerjatahun){$masakerja=$masakerjatahun.' tahun';}
					else if ($masakerjabulan){$masakerja=$masakerja." ".$masakerjabulan.' bulan';}					
					else {$masakerja="";}

					$d_tmt_kgb=$pegawaiList[$j]['d_tmt_kgb'];

			$d_tmt_golongan1=substr($d_tmt_golongan,0,2);
			$d_tmt_golongan2=substr($d_tmt_golongan,3,2);
			$d_tmt_golongan3=substr($d_tmt_golongan,6,4);
			
			$d_tmt_kgb_baru	= "$d_tmt_golongan1-$d_tmt_golongan2-".(2 + $d_tmt_golongan3);
					$no++;	
					$html=$html.'<tr>';
					 $html=$html.'	<td  valign="top" align="left" width="30%">
										
											'.$n_peg.'<br>NIP: '.$i_peg_nip.'<br>Tgl Lahir: '.$d_peg_lahir2.' <br>Usia: '.$usiatahun.' tahun '.$usiabulan.' bulan
										
									</td>';
							if ($jabatanlengkap){
 $html=$html.'								
							<td  valign="top" align="left" width="40%">'.$jabatanlengkap.'<br>'.$d_mulai_jabat.'</td>';
							}else{
 $html=$html.'							
							<td  valign="top" align="left" width="40%">&nbsp;</td>';
							}
$html=$html.'

							<td  valign="top" align="center" width="10%">'.$n_pendidikan.'</td>							
							<td  valign="top" align="center" width="10%">'.$n_golongan.'<br>'.$d_tmt_golongan.'</td>
							<td valign="top" align="center" width="10%">'.$n_golongan_next.'<br>'.$d_tmt_kgb_baru.'</td>
							</tr>';				 
				}
			}	
	$html=$html.'</table>';
	$this->view->par=$_GET['par'];
	$this->view->view=$html;


}	
	
}

?>