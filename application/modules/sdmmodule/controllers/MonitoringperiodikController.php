<?php
require_once 'Zend/Controller/Action.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Monitoring_Service.php";
require_once "service/sdm/Sdm_DiklatPenjenjangan_Service.php";
require_once "service/sdm/Sdm_Pendidikan_Service.php";
require_once "service/sdm/Sdm_Jabatan_Service.php";
class Sdmmodule_MonitoringperiodikController extends Zend_Controller_Action
{
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
	 	$registry = Zend_Registry::getInstance();
	   	$this->view->basePath = $registry->get('basepath');
		$this->view->leftMenu = $registry->get('leftMenu'); 
		$this->reff_serv = sdm_refferensi_Service::getInstance();
		$this->pegawai_serv = Sdm_Monitoring_Service::getInstance();
		$this->penjenjangan_serv = Sdm_DiklatPenjenjangan_Service::getInstance();
		$this->pendidikan_serv = Sdm_Pendidikan_Service::getInstance();
		$this->jabatan_serv = Sdm_Jabatan_Service::getInstance();
		
		$ssologin = new Zend_Session_Namespace('ssologin');
		$this->view->c_lokasi_unitkerja=$ssologin->c_lokasi_unitkerja;
		$this->view->c_eselon_i=$ssologin->c_eselon_i;			
    }
	public function indexAction()
	{
	}
	public function monitoringperiodikjsAction() 
	{
		header('content-type : text/javascript');
		$this->render('monitoringperiodikjs');
	}
	public function monitoringperiodikAction() 
	{
	   	$this->view->eselonList = $this->reff_serv->getEselon('');
		//$this->view->lokasiList = $this->reff_serv->getLokasi('');
		$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai('');	    	
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
		

		$this->view->c_eselon_i=$c_eselon_i;
		$this->view->c_lokasi_unitkerja=$c_lokasi_unitkerja;			
	}
	
	public function viewAction() 
	{

		$start=$_POST['start'];
		if (!$_POST['start']){$start = $_GET['start'];}
		$limit=$_POST['limit'];		
		if (!$_POST['limit']){$limit = $_GET['limit'];}	
		
		$currentPage=$_GET['currentPage'];
		if((!$currentPage) || ($currentPage == 'undefined'))
			{$currentPage = $start;}
			//echo $currentPage."  ".$numToDisplay;			
			if (!$limit){$limit=$_GET['limit'];}
			$numToDisplay = $limit;
			$this->view->numToDisplay = $numToDisplay;
			$this->view->currentPage = $currentPage;
			$this->view->limit=$numToDisplay;
	
if ($_POST['c_lokasi_unitkerja']){$c_lokasi_unitkerja=$_POST['c_lokasi_unitkerja'];}
else if ($_GET['c_lokasi_unitkerja']) {$c_lokasi_unitkerja=$_GET['c_lokasi_unitkerja'];}	
$this->view->c_lokasi_unitkerja=$c_lokasi_unitkerja;

		//Unit Kerja=====================

		if ($c_lokasi_unitkerja=='1'){
			$cari .= " and c_lokasi_unitkerja='$c_lokasi_unitkerja'";
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
			if ($_POST['c_eselon_i']){$this->view->c_eselon_i=$_POST['c_eselon_i'];}
			else{$this->view->c_eselon_i=$_GET['c_eselon_i'];}	
			if ($_POST['c_eselon_ii']){$this->view->c_eselon_ii=$_POST['c_eselon_ii'];}
			else{$this->view->c_eselon_ii=$_GET['c_eselon_ii'];}
			if ($_POST['c_eselon_iii']){$this->view->c_eselon_iii=$_POST['c_eselon_iii'];}
			else{$this->view->c_eselon_iii=$_GET['c_eselon_iii'];}
			if ($_POST['c_eselon_iv']){$this->view->c_eselon_iv=$_POST['c_eselon_iv'];}
			else{$this->view->c_eselon_iv=$_GET['c_eselon_iv'];}
		}
		else
		{


			if ($_POST['c_lokasi_unitkerja']){$c_lokasi_unitkerja=trim($_POST['c_lokasi_unitkerja']);}
			else if (trim($_GET['c_lokasi_unitkerja'])) {$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);}
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
			
			if ($c_eselon_i){$cari .= " and c_eselon_i='$c_eselon_i'";}
			if ($c_eselon_ii ){$cari .= " and c_eselon_ii='$c_eselon_ii'";}
			if ($c_eselon_iii){$cari .= " and c_eselon_iii='$c_eselon_iii' and c_parent='$c_parent' and c_satker='$c_satker'";}
			if ($c_eselon_iv){$cari .= " and c_eselon_iv='$c_eselon_iv'";}

		}				
				
		
$cari= $cari." and (c_eselon !='17' )"; 	
$html="";

		$totalpegawaiList = $this->pegawai_serv->getPegawaiList($cari, 0, 0 ,$orderBy);	
		$this->view->totalpegawaiList=$totalpegawaiList;	
		$pegawaiList = $this->pegawai_serv->getPegawaiList($cari,$currentPage,$numToDisplay,$orderBy );
		
if ($n_eselon_i){$jdlesl="<br>".$n_eselon_i;}
if ($n_eselon_ii){$jdlesl="<br>".$n_eselon_ii."<br>".$n_eselon_i;}
if ($n_eselon_iii){$jdlesl="<br>".$n_eselon_iii."<br>".$n_eselon_ii."<br>".$n_eselon_i;}
if ($n_eselon_iv){$jdlesl="<br>".$n_eselon_iv." ".$n_eselon_iii."<br>".$n_eselon_ii."<br>".$n_eselon_i;}

$semester=$_POST['semester'] ;
$tahunsemester=$_POST['tahunsemester'] ;

if ($_POST['semester']){$semester=$_POST['semester'];}
else{$semester=$_GET['semester'];}
if ($_POST['tahunsemester']){$tahunsemester=$_POST['tahunsemester'];}
else{$tahunsemester=$_GET['tahunsemester'];} 

if ($_POST['c_eselon_ii']){$c_eselon_ii=$_POST['c_eselon_ii'];}
else{$c_eselon_ii=$_GET['c_eselon_ii'];}
if ($_POST['c_eselon_i']){$c_eselon_i=$_POST['c_eselon_i'];}
else{$c_eselon_i=$_GET['c_eselon_i'];}
if ($_POST['c_eselon_iii']){$c_eselon_iii=$_POST['c_eselon_iii'];}
else{$c_eselon_iii=$_GET['c_eselon_iii'];}
if ($_POST['c_eselon_iv']){$c_eselon_iv=$_POST['c_eselon_iv'];}
else{$c_eselon_iv=$_GET['c_eselon_iv'];}

$htmlx='<center><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">LAPORAN KEADAAN PEGAWAI '.$semester.' TAHUN '.$tahunsemester.strtoupper($jdlesl).'<br>MAHKAMAH AGUNG</font></center>';
$this->view->judul=$htmlx;
//$html=$html.'<div style="height: 470px; overflow: auto; padding: 5px">';
$html=$html.' <table align="center" border="0" cellspacing="1" cellpadding="2" class="sortable">
		<tr>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">No</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">Nama / NIP<br>Tgl. Lahir / Umur </font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">GOL./ RUANG<br>T M T</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">Jabatan<br>a.TMT Jabatan<br>b.TMT Eselon</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">Masa Kerja Total(Tahun)</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">Riwayat Diklat Penjenjangan</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">Riwayat Pendidikan</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">Riwayat Jabatan</font></th>
		</tr>';
		if (count($pegawaiList)==0)
		{
		$html=$html.' <tr class="event2">
					<td>&nbsp</td>
					<td>&nbsp</td>
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
				
					$i_peg_nip=$pegawaiList[$j]['i_peg_nip'];
					$n_peg=$pegawaiList[$j]['n_peg'];
					$d_peg_lahir=$pegawaiList[$j]['d_peg_lahir2'];
					
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

					$penjenjanganList = $this->penjenjangan_serv->getPelatihanList(" and i_peg_nip ='$i_peg_nip' and q_tahun in (select (max(q_tahun))from sdm.tm_pelatihan_penjenjangan where i_peg_nip='$i_peg_nip')");								
					if (count($penjenjanganList)!=0){
						for($xpenjenjangan = 0; $xpenjenjangan < count($penjenjanganList); $xpenjenjangan++)
						{
							$penjenjangan=$penjenjanganList[$xpenjenjangan]['n_penjenjangan']." Tahun ".$penjenjanganList[$xpenjenjangan]['q_tahun']."<br>";
							$penjenjangandetil=$penjenjangandetil.$penjenjangan;
						}
					}
					
	
					
					$nourut = (($currentPage -1)* $numToDisplay) + $j +1;
					if ($j%2==0) {
						  $html=$html.'<tr class="event">';
					 } else if ($j%2==1) { 
						  $html=$html.'<tr class="event2">';
					 }
					 $html=$html.'	
					 <td class="clleft">
										<font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">'.$nourut.'</font>
									</td>
					 <td class="clleft">
										<font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">
											'.$n_peg.'<br>NIP: '.$i_peg_nip.'<br>Tgl Lahir: '.$d_peg_lahir.' <br>Usia: '.$usiatahun.' tahun '.$usiabulan.' bulan
										</font>
									</td>
							<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">'.$n_golongan.'<br>'.$d_tmt_golongan.'</font></td>';
							if ($jabatanlengkap){
 $html=$html.'								
							<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">'.$jabatanlengkap.'<br>a. '.$d_mulai_jabat.'<br>b. '.$d_tmt_eselon.'</font></td>';
							}else{
 $html=$html.'							
							<td class="clleft">&nbsp;</td>';
							}
$html=$html.'							
							<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">'.$masakerja.'</font></td>
							<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">'.$penjenjangandetil.'</font></td>
							<td class="clleft"><table>';
							$pendList="";
							$pendList = $this->pendidikan_serv->getPendidikanList(" and i_peg_nip ='$i_peg_nip'");								
							if (count($pendList)!=0){
								for($xpend = 0; $xpend < count($pendList); $xpend++)
								{
									$xpend2++;
									$pendidikan=$pendList[$xpend]['n_pend']."  ".$pendList[$xpend]['d_pend_mulai']."  ".$pendList[$xpend]['n_pend_lembaga'];
									
								$html=$html.'<tr>';
									$html=$html.'<td><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">'.$xpend2.'</font></td>';
									$html=$html.'<td><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">'.$pendidikan.'</font></td>';
								$html=$html.'<tr>';									
								}
							}
$html=$html.'							
							</table></td>
							<td class="clleft"><table>';
					$jabatanList="";
					$jabatan="";
					$jabatandetil="";
					$jabatanList = $this->jabatan_serv->getJabatanList(" and i_peg_nip ='$i_peg_nip'");								
						if (count($jabatanList)!=0){
							for($xjabatan = 0; $xjabatan < count($jabatanList); $xjabatan++)
							{
								$xjabatan2++;
								$jabatan=$jabatanList[$xjabatan]['n_jabatan']." pada ".$jabatanList[$xjabatan]['unitkerjalengkap']."<br>".$jabatanList[$xjabatan]['d_mulai_jabat'];
								$html=$html.'<tr>';
									$html=$html.'<td><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">'.$xjabatan2.'</font></td>';
									$html=$html.'<td><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">'.$jabatan.'</font></td>';
								$html=$html.'<tr>';
							}
						}
						
						$html=$html.'</table>
							</td>
							</tr>';				 
				}
			}	
	$html=$html.'</table>';

	$this->view->par=$_GET['par'];
	$this->view->view=$html;
	if ($_GET['par']=='exl'){
?>
<script>

	var url = "<?php echo $this->basePath; ?>/sdmmodule/monitoringperiodik/exl?limit=<?=$limit?>&c_eselon_i=<?=$c_eselon_i?>&c_eselon_ii=<?=$c_eselon_ii?>&c_eselon_iii=<?=$c_eselon_iii?>&c_eselon_iv=<?=$c_eselon_iv?>&c_eselon_v=<?=$c_eselon_v?>&c_lokasi_unitkerja=<?=$c_lokasi_unitkerja?>&semester=<?=$semester?>&tahunsemester=<?=$tahunsemester?>";
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

		
		//Unit Kerja=====================
		$c_lokasi_unitkerja=$_GET['c_lokasi_unitkerja'];
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
				
		
$cari= $cari." and (c_eselon !='17' or c_eselon isnull)"; 

if ($c_lokasi_unitkerja=='1'){$n_lokasi_unitkerja='Kantor Pusat MA';}else{$n_lokasi_unitkerja='Pengadilan';}
if ($n_lokasi_unitkerja){$jdlesl=$n_lokasi_unitkerja;}
if ($n_eselon_i){$jdlesl=$n_lokasi_unitkerja."<br>".$n_eselon_i;}
if ($n_eselon_ii){$jdlesl=$n_lokasi_unitkerja."<br>".$n_eselon_ii."<br>".$n_eselon_i;}
if ($n_eselon_iii){$jdlesl=$n_lokasi_unitkerja."<br>".$n_eselon_iii."<br>".$n_eselon_ii."<br>".$n_eselon_i;}
if ($n_eselon_iv){$jdlesl=$n_lokasi_unitkerja."<br>".$n_eselon_iv." ".$n_eselon_iii."<br>".$n_eselon_ii."<br>".$n_eselon_i;}




$pegawaiList = $this->pegawai_serv->getPegawaiListAll($cari);
		
$html='<center><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">LAPORAN KEADAAN PEGAWAI '.$semester.' TAHUN '.$tahunsemester.strtoupper($jdlesl).'<br>MAHKAMAH AGUNG</font></center>';
$html=$html.' <table align="center" border="1" cellspacing="1" cellpadding="2" width="100%">
		<tr>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">NO</th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">Nama / NIP<br>Tgl. Lahir / Umur </th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">GOL./ RUANG<br>T M T</th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">Jabatan<br>a.TMT Jabatan<br>b.TMT Eselon</th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">Masa Kerja Total(Tahun)</th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">Riwayat Diklat</th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">Riwayat Pendidikan</th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">Riwayat Jabatan</th>
		</tr>';
		if (count($pegawaiList)==0)
		{
		$html=$html.' <tr class="event2">
					<td>&nbsp</td>
					<td>&nbsp</td>
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
				
					$i_peg_nip=$pegawaiList[$j]['i_peg_nip'];
					$n_peg=$pegawaiList[$j]['n_peg'];
					$d_peg_lahir=$pegawaiList[$j]['d_peg_lahir'];
					
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

					$penjenjanganList = $this->penjenjangan_serv->getPelatihanList(" and i_peg_nip ='$i_peg_nip' and q_tahun in (select (max(q_tahun))from sdm.tm_pelatihan_penjenjangan where i_peg_nip='$i_peg_nip')");								
					if (count($penjenjanganList)!=0){
						for($xpenjenjangan = 0; $xpenjenjangan < count($penjenjanganList); $xpenjenjangan++)
						{
							$penjenjangan=$penjenjanganList[$xpenjenjangan]['n_penjenjangan']." Tahun ".$penjenjanganList[$xpenjenjangan]['q_tahun']."<br>";
							$penjenjangandetil=$penjenjangandetil.$penjenjangan;
						}
					}
					
	
					
					$no++;	
				     $html=$html.'<tr>';
					 $html=$html.'	<td valign="top" align="left"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">'.$no.'</td>
					 
					 <td valign="top" align="left"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">
										
											'.$n_peg.'<br>NIP: '.$i_peg_nip.'<br>Tgl Lahir: '.$d_peg_lahir.' <br>Usia: '.$usiatahun.' tahun '.$usiabulan.' bulan
										
									</td>
							<td valign="top" align="left"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">'.$n_golongan.'<br>'.chr(149).$d_tmt_golongan.'</td>';
							if ($jabatanlengkap){
 $html=$html.'								
							<td valign="top" align="left"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">'.$jabatanlengkap.'<br>a. '.chr(149).$d_mulai_jabat.'<br>b. '.chr(149).$d_tmt_eselon.'</td>';
							}else{
 $html=$html.'							
							<td valign="top" align="left">&nbsp;</td>';
							}
$html=$html.'							
							<td valign="top" align="left"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">'.$masakerja.'</td>
							<td valign="top" align="left"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">'.$penjenjangandetil.'</td>
							<td valign="top" align="left"><table>';
							$pendList="";
							$pendList = $this->pendidikan_serv->getPendidikanList(" and i_peg_nip ='$i_peg_nip'");								
							if (count($pendList)!=0){
								for($xpend = 0; $xpend < count($pendList); $xpend++)
								{
									$xpend2++;
									$pendidikan=$pendList[$xpend]['n_pend']."  ".chr(149).$pendList[$xpend]['d_pend_mulai']."  ".$pendList[$xpend]['n_pend_lembaga'];
									
								$html=$html.'<tr>';
									$html=$html.'<td valign="top" align="left"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">'.$xpend2.'</td>';
									$html=$html.'<td valign="top" align="left"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">'.$pendidikan.'</td>';
								$html=$html.'<tr>';									
								}
							}
$html=$html.'							
							</table></td>
							<td valign="top" align="left"><table>';
					$jabatanList="";
					$jabatan="";
					$jabatandetil="";
					$jabatanList = $this->jabatan_serv->getJabatanList(" and i_peg_nip ='$i_peg_nip'");								
						if (count($jabatanList)!=0){
							for($xjabatan = 0; $xjabatan < count($jabatanList); $xjabatan++)
							{
								$xjabatan2++;
								$jabatan=$jabatanList[$xjabatan]['n_jabatan']." pada ".$jabatanList[$xjabatan]['unitkerjalengkap']."<br>".chr(149).$jabatanList[$xjabatan]['d_mulai_jabat'];
								$html=$html.'<tr>';
									$html=$html.'<td valign="top" align="left"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">'.$xjabatan2.'</td>';
									$html=$html.'<td valign="top" align="left"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">'.$jabatan.'</td>';
								$html=$html.'<tr>';
							}
						}
						
						$html=$html.'</table>
							</td>
							</tr>';				 
				}
			}	
	$html=$html.'</table>';
	$this->view->par=$_GET['par'];
	$this->view->view=$html;


}
	
  function age($age){
    list($year,$month,$day) = explode("-",$age);
    $year_diff  = date("Y") - $year;
    $month_diff = date("m") - $month;
    $day_diff   = date("d") - $day;
    if ($day_diff < 0 || $month_diff < 0) {
      $year_diff--;
    }
    return $year_diff;
  }	
}

?>