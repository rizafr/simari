<?php
require_once 'Zend/Controller/Action.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Monitoring_Service.php";
require_once "service/sdm/Sdm_DiklatPenjenjangan_Service.php";
require_once "service/sdm/Sdm_Pendidikan_Service.php";

class Sdmmodule_MonitoringdukController extends Zend_Controller_Action
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
		$ssologin = new Zend_Session_Namespace('ssologin');
		$this->view->c_lokasi_unitkerja=$ssologin->c_lokasi_unitkerja;
		$this->view->c_eselon_i=$ssologin->c_eselon_i;			
    }
	public function indexAction()
	{
	}
	public function monitoringdukjsAction() 
	{
		header('content-type : text/javascript');
		$this->render('monitoringdukjs');
	}
	public function monitoringdukAction() 
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
		$nourut=0;
/* 		$c_eselon = $_POST['c_eselon'];
		$c_lokasi_unitkerja = $_POST['c_lokasi_unitkerja'];
		$c_eselon_i = $_POST['c_eselon_i'];
		$c_eselon_ii = $_POST['c_eselon_ii'];
		$c_eselon_iii = $_POST['c_eselon_iii'];
		$c_eselon_iv = $_POST['c_eselon_iv'];
		$c_eselon_v = $_POST['c_eselon_v'];
		
		$start=$_POST['start'];
		$limit=$_POST['limit'];	 */
		
		$c_eselon = $_POST['c_eselon'];
		if(!$_POST['c_eselon']){$c_eselon = $_GET['c_eselon'];}
		$c_lokasi_unitkerja = $_POST['c_lokasi_unitkerja'];
		$c_eselon_i = $_POST['c_eselon_i'];
		if(!$_POST['c_eselon_i']){$c_eselon_i = $_GET['c_eselon_i'];}
		$c_eselon_ii = $_POST['c_eselon_ii'];
		if(!$_POST['c_eselon_ii']){$c_eselon_ii = $_GET['c_eselon_ii'];}
		$c_eselon_iii = $_POST['c_eselon_iii'];
		if(!$_POST['c_eselon_iii']){$c_eselon_iii = $_GET['c_eselon_iii'];}
		$c_eselon_iv = $_POST['c_eselon_iv'];
		if(!$_POST['c_eselon_iv']){$c_eselon_iv = $_GET['c_eselon_iv'];}
		$c_eselon_v = $_POST['c_eselon_v'];
		if(!$_POST['c_eselon_v']){$c_eselon_v = $_GET['c_eselon_v'];}
		
		$start=$_POST['start'];
		if(!$_POST['start']){$start = $_GET['start'];}
		$limit=$_POST['limit'];	
		if(!$_POST['limit']){$limit = $_GET['limit'];}		
			

$html="";
		$currentPage=$_GET['currentPage'];
		if((!$currentPage) || ($currentPage == 'undefined'))
			{$currentPage = $start;}
			//echo $currentPage."  ".$numToDisplay;			
			if (!$limit){$limit=$_GET['limit'];}
			$numToDisplay = $limit;
			$this->view->numToDisplay = $numToDisplay;
			$this->view->currentPage = $currentPage;
		
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
		if ($_POST['c_lokasi_unitkerja']){$c_lokasi_unitkerja=$_POST['c_lokasi_unitkerja'];}
		else if ($_GET['c_lokasi_unitkerja']) {$c_lokasi_unitkerja=$_GET['c_lokasi_unitkerja'];}	
		
		if ($c_lokasi_unitkerja=='1'){
			$cari .= "  and c_lokasi_unitkerja='$c_lokasi_unitkerja'";
 			//$c_eselon_i=trim($this->view->c_eselon_i);	
			// if ($c_eselon_i!='01'){
				// $c_eselon_i=trim($this->view->c_eselon_i);
				// $cari .= " and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'";
			// }
			// else{
				if ($_POST['c_eselon_i']){$c_eselon_ix=$_POST['c_eselon_i'];}
				else{$c_eselon_ix=$_GET['c_eselon_i'];}				
				$c_eselon_i=substr($c_eselon_i,0,2);
				$n_eselon_i=substr($c_eselon_ix,2,200);
				if ($_POST['c_eselon_i'] || $_GET['c_eselon_i']){$cari .= " and c_eselon_i='$c_eselon_i' ";}

			//} 
	

			if ($_POST['c_eselon_ii']){$c_eselon_iix=$_POST['c_eselon_ii'];}
			else{$c_eselon_iix=$_GET['c_eselon_ii'];}
			$c_eselon_ii=substr($c_eselon_ii,0,3);
			$n_eselon_ii=substr($c_eselon_iix,3,200);
			if ($_POST['c_eselon_ii'] || $_GET['c_eselon_ii']){$cari .= " and c_eselon_ii='$c_eselon_ii' ";}
			
			
			if ($_POST['c_eselon_iii']){$c_eselon_iiix=$_POST['c_eselon_iii'];}
			else{$c_eselon_iiix=$_GET['c_eselon_iii'];}
			$c_eselon_iii=substr($c_eselon_iii,0,2);
			$n_eselon_iii=substr($c_eselon_iiix,2,200);
			if ($_POST['c_eselon_iii'] || $_GET['c_eselon_iii']){$cari .= "  and c_eselon_iii='$c_eselon_iii' ";}

			if ($_POST['c_eselon_iv']){$c_eselon_ivx=$_POST['c_eselon_iv'];}
			else{$c_eselon_ivx=$_GET['c_eselon_iv'];}
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
			else{$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);}
			$cari .= "  and c_lokasi_unitkerja='$c_lokasi_unitkerja'";
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

if ($_POST['tahunsemester']){$tahunsemester=$_POST['tahunsemester'];}
else{$tahunsemester=trim($_GET['tahunsemester']);}
$this->view->tahunsemester=$tahunsemester;

if ($_POST['c_lokasi_unitkerja']){$c_lokasi_unitkerja=$_POST['c_lokasi_unitkerja'];}
else{$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);}
$this->view->c_lokasi_unitkerja=$c_lokasi_unitkerja;

				
$cari= $cari." and (c_eselon !='17' or c_eselon isnull)";
		$this->view->limit=$numToDisplay;
	 $orderBy = "  ORDER BY c_golongan ASC,d_tmt_golongan ASC,c_eselon ASC,q_tktfgs,d_tmt_eselon ASC,d_tmt_cpns asc, q_tahun ASC,c_pend ASC,d_pend_akhir ASC,d_peg_lahir ASC";
  	
		$totalpegawaiList = $this->pegawai_serv->getPegawaiList($cari, 0, 0 ,$orderBy);	
		$this->view->totalpegawaiList=$totalpegawaiList;
		$pegawaiList = $this->pegawai_serv->getPegawaiList($cari,$currentPage,$numToDisplay,$orderBy );
		
		$jdllap=$_POST['judul_lap'];
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
		
$htmlx='<center><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">DAFTAR URUT KEPANGKATAN TAHUN '.$tahunsemester.'<br>LINGKUP '.strtoupper($jdlesl).'</font></center>';


$this->view->judul=$htmlx;
$html=$html.'<div style=" height: 470px; overflow: auto; padding: 5px">';
$html=$html.' <table align="center" border="0" cellspacing="1" cellpadding="2" class="tbl">
		<tr>
			<th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">NO URUT</font></th>';
			//<th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">NO DUK</font></th>
$html=$html.'		<th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">Nama</font></th>
			<th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">NIP</font></th>
			<th colspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">PANGKAT</font></th>
			<th colspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">JABATAN</font></th>
			<th colspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">MASA<br>KERJA</font></th>
			<th colspan="3"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">LATIHAN JABATAN</font></th>
			<th colspan="3"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">PENDIDIKAN TERAKHIR</font></th>
			<th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">TANGGAL LAHIR</font></th>
			<th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">CATATAN MUTASI KEPEGAWAIAN</font></th>
		</tr>
		<tr>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">GOL.<br>RUANG</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">TMT</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">NAMA</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">TMT</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">TH</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">BL</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">NAMA</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">TAHUN</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">JUMLAH</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">NAMA</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">LULUS</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">TINGKAT IJAZAH</font></th>
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
					//$nourut++;
					$nourut = (($currentPage -1)* $numToDisplay) + $j +1;
					$n_penjenjangan="";
					$q_tahun="";
					$q_peringkat="";
					$i_peg_nip="";
					$i_peg_nip_new="";
					$n_peg="";
					$d_peg_lahir="";			
					$usiatahun="";
					$usiabulan="";
					$c_golongan="";
					$n_golongan="";
					$d_tmt_golongan="";
					$c_penjenjangan="";
					$n_pendidikan="";
					$jabatanlengkap="";
					$n_jabatan="";
					$d_mulai_jabat="";
					$d_tmt_eselon="";
					$masakerjabulan="";
					$masakerjatahun="";
					$c_pend="";
					$d_pend_akhir="";
					$n_pend_lembaga="";
					
					
					$i_peg_nip=$pegawaiList[$j]['i_peg_nip'];
					$i_peg_nip_new=$pegawaiList[$j]['i_peg_nip_new'];
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
					$n_jabatan=$pegawaiList[$j]['n_jabatan'];
					$d_mulai_jabat=$pegawaiList[$j]['d_mulai_jabat'];
					$d_tmt_eselon=$pegawaiList[$j]['d_tmt_eselon'];
					$masakerjabulan=$pegawaiList[$j]['masakerjabulan'];
					$masakerjatahun=$pegawaiList[$j]['masakerjatahun'];
					$c_pend=$pegawaiList[$j]['c_pend'];
					if ($masakerjatahun){$masakerja=$masakerjatahun.' tahun';}
					else if ($masakerjabulan){$masakerja=$masakerja." ".$masakerjabulan.' bulan';}					
					else {$masakerja="";}

					//$penjenjanganList = $this->penjenjangan_serv->getPelatihanList(" and i_peg_nip ='$i_peg_nip' and q_tahun in (select (max(q_tahun))from sdm.tm_pelatihan_penjenjangan where i_peg_nip='$i_peg_nip')");								
					$penjenjanganList = $this->penjenjangan_serv->getPelatihanList(" and i_peg_nip ='$i_peg_nip' ");								
					if (count($penjenjanganList)!=0){
						for($xpenjenjangan = 0; $xpenjenjangan < count($penjenjanganList); $xpenjenjangan++)
						{
							//$penjenjangan=$penjenjanganList[$xpenjenjangan]['n_penjenjangan']." Tahun ".$penjenjanganList[$xpenjenjangan]['q_tahun']."<br>";
							//$penjenjangandetil=$penjenjangandetil.$penjenjangan;
							$n_penjenjangan=$penjenjanganList[$xpenjenjangan]['n_penjenjangan']."<br>".$n_penjenjangan;
							
							$q_tahun=$penjenjanganList[$xpenjenjangan]['q_tahun']."<br>".$q_tahun;
							$q_peringkat=$penjenjanganList[$xpenjenjangan]['q_peringkat']."<br>".$q_peringkat;
							
						}
					}
					

					$pendList = $this->pendidikan_serv->getPendidikanList(" and i_peg_nip ='$i_peg_nip' and c_pend='$c_pend' ");
					if (count($pendList)!=0){
						for($xpend = 0; $xpend < count($pendList); $xpend++)
						{
							$d_pend_akhir=$pendList[$xpend]['d_pend_akhir'];
							$n_pend_lembaga=$pendList[$xpend]['n_pend_lembaga'];					
						}					
					}
					
					$no++;	
					if ($j%2==0) {
						  $html=$html.'<tr class="event">';
					 } else if ($j%2==1) { 
						  $html=$html.'<tr class="event2">';
					 }
					 $html=$html.'	<td class="clright"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">'.$nourut.'</font></td>';
							//<td class="clright"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px"></font></td>
					 $html=$html.'		<td class="clleft">
								<font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">
									'.$n_peg.'
								</font>
							</td>
							<td class="clleft">
								<font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">
									'.$i_peg_nip_new.'
								</font>
							</td>
							<td class="clcenter">
								<font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">
									'.$n_golongan.'
								</font>
							</td>
							<td class="clcenter">
								<font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">
									'.$d_tmt_golongan.'
								</font>
							</td>
							<td class="clleft">
								<font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">
									'.$n_jabatan.'
								</font>
							</td>
							<td class="clcenter">
								<font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">
									'.$d_mulai_jabat.'
								</font>
							</td>
							<td class="clcenter">
								<font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">
									'.$masakerjatahun.'
								</font>
							</td>
							<td class="clcenter">
								<font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">
									'.$masakerjabulan.'
								</font>
							</td>
							<td class="clleft">
								<font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">
									'.$n_penjenjangan.'
								</font>
							</td>
							<td class="clcenter">
								<font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">
									'.$q_tahun.'
								</font>
							</td>	
							<td class="clcenter">
								<font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">
									'.$q_peringkat.'
								</font>
							</td>
							<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">'.$n_pend_lembaga.'</font></td>
							<td class="clcenter"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">'.$d_pend_akhir.'</font></td>
							<td class="clcenter"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">'.$n_pendidikan.'</font></td>
							<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">'.$d_peg_lahir.'</font></td>
							<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px"></font></td>
							</tr>';				 
				}
			}	
	$html=$html.'</table></div>';

	$this->view->par=$_GET['par'];
	$this->view->view=$html;
	if ($_GET['par']=='exl'){
?>
<script>

	var url = "<?php echo $this->basePath; ?>/sdmmodule/monitoringduk/exl?limit=<?=$limit?>&c_eselon_i=<?=$c_eselon_i?>&c_eselon_ii=<?=$c_eselon_ii?>&c_eselon_iii=<?=$c_eselon_iii?>&c_eselon_iv=<?=$c_eselon_iv?>&c_eselon_v=<?=$c_eselon_v?>&c_lokasi_unitkerja=<?=$c_lokasi_unitkerja?>&blnawal=<?=$blnawal?>&c_eselon=<?=$c_eselon?>&tahunsemester=<?=$tahunsemester?>";
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
$html='<center><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">DAFTAR URUT KEPANGKATAN TAHUN '.$tahunsemester.'<br>LINGKUP '.strtoupper($jdlesl).'</font></center>';
$html=$html.' <table align="center" border="1" cellspacing="1" cellpadding="2" width="100%">
<tr>
			<th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">NO URUT</font></th>
			<th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">NO DUK</font></th>
			<th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">Nama</font></th>
			<th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">NIP</font></th>
			<th colspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">PANGKAT</font></th>
			<th colspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">JABATAN</font></th>
			<th colspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">MASA<br>KERJA</font></th>
			<th colspan="3"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">LATIHAN JABATAN</font></th>
			<th colspan="3"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">PENDIDIKAN TERAKHIR</font></th>
			<th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">TANGGAL LAHIR</font></th>
			<th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">CATATAN MUTASI KEPEGAWAIAN</font></th>
		</tr>
		<tr>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">GOL.<br>RUANG</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">TMT</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">NAMA</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">TMT</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">TH</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">BL</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">NAMA</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">TAHUN</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">JUMLAH</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">NAMA</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">LULUS</font></th>
			<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">TINGKAT IJAZAH</font></th>
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
					$nourut++;
					$n_penjenjangan="";
					$q_tahun="";
					$q_peringkat="";
					$i_peg_nip="";
					$i_peg_nip_new="";
					$n_peg="";
					$d_peg_lahir="";			
					$usiatahun="";
					$usiabulan="";
					$c_golongan="";
					$n_golongan="";
					$d_tmt_golongan="";
					$c_penjenjangan="";
					$n_pendidikan="";
					$jabatanlengkap="";
					$n_jabatan="";
					$d_mulai_jabat="";
					$d_tmt_eselon="";
					$masakerjabulan="";
					$masakerjatahun="";
					$c_pend="";
					$d_pend_akhir="";
					$n_pend_lembaga="";
					$sep = "t";

				
					$i_peg_nip=$pegawaiList[$j]['i_peg_nip'];
					$i_peg_nip_new=$pegawaiList[$j]['i_peg_nip_new'];
					$n_peg=$pegawaiList[$j]['n_peg'];
					$d_peg_lahir=$pegawaiList[$j]['d_peg_lahir2'];
					// if ($d_peg_lahir){
						// $usia = $this->age($d_peg_lahir);
					// }
					

					$usiatahun=$pegawaiList[$j]['usiatahun'];
					$usiabulan=$pegawaiList[$j]['usiabulan'];
					$c_golongan=$pegawaiList[$j]['c_golongan'];
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

/* 					$penjenjanganList = $this->penjenjangan_serv->getPelatihanList(" and i_peg_nip ='$i_peg_nip' and q_tahun in (select (max(q_tahun))from sdm.tm_pelatihan_penjenjangan where i_peg_nip='$i_peg_nip')");								
					if (count($penjenjanganList)!=0){
						for($xpenjenjangan = 0; $xpenjenjangan < count($penjenjanganList); $xpenjenjangan++)
						{
							$penjenjangan=$penjenjanganList[$xpenjenjangan]['n_penjenjangan']." Tahun ".$penjenjanganList[$xpenjenjangan]['q_tahun']."<br>";
							$penjenjangandetil=$penjenjangandetil.$penjenjangan;
						}
					} */

					$penjenjanganList = $this->penjenjangan_serv->getPelatihanList(" and i_peg_nip ='$i_peg_nip' ");								
					if (count($penjenjanganList)!=0){
						for($xpenjenjangan = 0; $xpenjenjangan < count($penjenjanganList); $xpenjenjangan++)
						{
							$n_penjenjangan=$penjenjanganList[$xpenjenjangan]['n_penjenjangan']."<br>".$n_penjenjangan;
							$q_tahun=$penjenjanganList[$xpenjenjangan]['q_tahun']."<br>".$q_tahun;
							$q_peringkat=$penjenjanganList[$xpenjenjangan]['q_peringkat']."<br>".$q_peringkat;
							
						}
					}					
					
					$no++;	
					
						  $html=$html.'<tr>';
					 $html=$html.'	<td class="clright"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">'.$nourut.'</font></td>
							<td class="clright"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px"></font></td>
							<td class="clleft">
								<font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">
									'.$n_peg.'
								</font>
							</td>
							<td class="clleft">
								<font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">
									'.chr(149).$i_peg_nip_new.'
								</font>
							</td>
							<td class="clcenter">
								<font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">
									'.$n_golongan.'
								</font>
							</td>
							<td class="clcenter">
								<font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">
									'.chr(149).$d_tmt_golongan.'
								</font>
							</td>
							<td class="clleft">
								<font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">
									'.$n_jabatan.'
								</font>
							</td>
							<td class="clcenter">
								<font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">
									'.chr(149).$d_mulai_jabat.'
								</font>
							</td>
							<td class="clcenter">
								<font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">
									'.$masakerjatahun.'
								</font>
							</td>
							<td class="clcenter">
								<font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">
									'.$masakerjabulan.'
								</font>
							</td>
							<td class="clleft">
								<font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">
									'.$n_penjenjangan.'
								</font>
							</td>
							<td class="clcenter">
								<font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">
									'.$q_tahun.'
								</font>
							</td>	
							<td class="clcenter">
								<font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">
									'.$q_peringkat.'
								</font>
							</td>
							<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">'.$n_pend_lembaga.'</font></td>
							<td class="clcenter"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">'.chr(149).$d_pend_akhir.'</font></td>
							<td class="clcenter"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">'.$n_pendidikan.'</font></td>
							<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px">'.chr(149).$d_peg_lahir.'</font></td>
							<td class="clleft"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1px"></font></td>
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