<?php
require_once 'Zend/Controller/Action.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Monitoring_Service.php";
class Sdmmodule_ExportexcelController extends Zend_Controller_Action
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
	public function exportexceljsAction() 
	{
		header('content-type : text/javascript');
		$this->render('exportexceljs');
	}
	public function exportexcelAction() 
	{
	   	$this->view->eselonList = $this->reff_serv->getEselon('');
		//$this->view->lokasiList = $this->reff_serv->getLokasi('');
		
	   	$c_lokasi_unitkerja=trim($this->view->c_lokasi_unitkerja);
		$c_eselon_i=trim($this->view->c_eselon_i);
	//	$c_eselon_i = "01";
		
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
		
		
	/*	Ditutup dez	
	 	$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai(" and c_peg_tipegolongan ='3' ");	
		//$this->view->statusKePegRef = $this->reff_serv->getStatusKepegawaian('');      	
		$this->view->statusKePegRef = $this->reff_serv->getStatusKepegawaian(" and c_status_kepegawaian in ('3','4','5','6')");	 */
	}	
	

	public function viewAction() 
	{
// 	Ditutup dez
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
		if (!$_POST['c_eselon_v']){$c_eselon_v = $_GET['c_eselon_v'];}  //
		
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

 /* Ditutup dez
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

*/
		
		//Unit Kerja=====================
		
		
		
		
	// Ditutup dez		
		if ($c_lokasi_unitkerja=='1'){
			
			$c_eselon_i=trim($this->view->c_eselon_i);	
			if ($c_eselon_i!='01'){
				$c_eselon_i=trim($this->view->c_eselon_i);
				$cari .= " and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'";
			}
			else{
				if ($_POST['c_eselon_i']){$c_eselon_i=$_POST['c_eselon_i'];}
				else{$c_eselon_i=$_GET['c_eselon_i'];}
				$c_eselon_ix=$c_eselon_i;
				$c_eselon_i=substr($c_eselon_i,0,2);
				$n_eselon_i=substr($c_eselon_ix,2,200);
				if ($_POST['c_eselon_i'] || $_GET['c_eselon_i']){$cari .= " and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'";}

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
			else{$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);}
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
 // akhir tutup dez	

 
 
 
 
 

if ($_POST['c_eselon']){$c_eselon=$_POST['c_eselon'];}
else{$c_eselon=trim($_GET['c_eselon']);}
$this->view->c_eselon=$c_eselon;

/* Ditutup dez

if ($_POST['blnawal']){$blnawal=$_POST['blnawal'];}
else{$blnawal=trim($_GET['blnawal']);}
$this->view->blnawal=$blnawal;

if ($_POST['c_golongan']){$c_golongan=$_POST['c_golongan'];}
else{$c_golongan=trim($_GET['c_golongan']);}
$this->view->c_golongan	=$c_golongan;	*/

if ($_POST['c_status_kepegawaian']){$c_status_kepegawaian=$_POST['c_status_kepegawaian'];}
else{$c_status_kepegawaian=trim($_GET['c_status_kepegawaian']);}
$this->view->c_status_kepegawaian=$c_status_kepegawaian;
	
/*	dez
if ($_POST['thnakhir']){$thnakhir=$_POST['thnakhir'];}
else{$thnakhir=trim($_GET['thnakhir']);}
$this->view->thnakhir=$thnakhir;	

if ($_POST['thnawal']){$thnawal=$_POST['thnawal'];}
else{$thnawal=trim($_GET['thnawal']);}
$this->view->thnawal=$thnawal;	*/

if ($_POST['c_lokasi_unitkerja']){$c_lokasi_unitkerja=$_POST['c_lokasi_unitkerja'];}
else{$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);}
$this->view->c_lokasi_unitkerja	=$c_lokasi_unitkerja;	

if ($c_eselon){$cari= $cari." and c_eselon ='$c_eselon'";}



//	dez if ($c_golongan){$c_golongan=substr($_POST['c_golongan'],0,2); $cari= $cari." and c_golongan ='$c_golongan' ";}
if ($c_eselon){$cari= $cari." and c_eselon ='$c_eselon'";}

/*
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
} */







// Ditutup dez
if ($_POST['c_eselon_i']){$this->view->c_eselon_i=$_POST['c_eselon_i'];}
else{$this->view->c_eselon_i=$_GET['c_eselon_i'];}	
if ($_POST['c_eselon_ii']){$this->view->c_eselon_ii=$_POST['c_eselon_ii'];}
else{$this->view->c_eselon_ii=$_GET['c_eselon_ii'];}
if ($_POST['c_eselon_iii']){$this->view->c_eselon_iii=$_POST['c_eselon_iii'];}
else{$this->view->c_eselon_iii=$_GET['c_eselon_iii'];}
if ($_POST['c_eselon_iv']){$this->view->c_eselon_iv=$_POST['c_eselon_iv'];}
else{$this->view->c_eselon_iv=$_GET['c_eselon_iv'];}

//


 
 
 
 
 
/* 
// Tambahan Dez
			if ($_POST['c_lokasi_unitkerja']){$c_lokasi_unitkerja=trim($_POST['c_lokasi_unitkerja']);}
			else{$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);}
			//$this->view->lokasiList = $this->reff_serv->getLokasi('');
			//$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");


			if ($_POST['c_eselon_i']){$c_eselon_i=$_POST['c_eselon_i'];}
			else{$c_eselon_i=trim($_GET['c_eselon_i']);}
			$this->view->c_eselon_i =$c_eselon_i;
			if ($c_eselon_i){
				$expesl1 = explode(" ",$c_eselon_i);
				$c_eselon_i=$expesl1[0];
				$n_eselon_i=$expesl1[1];
			}
			
			if ($_POST['c_eselon_ii']){$c_eselon_ii=$_POST['c_eselon_ii'];}
			else{$c_eselon_ii=trim($_GET['c_eselon_ii']);}
			$this->view->c_eselon_ii =$c_eselon_ii;
			if ($c_eselon_ii){
			$expesl2 = explode(" ",$c_eselon_ii);
			$c_eselon_ii=$expesl2[0];
			$c_parent=$expesl2[1];
			$n_eselon_ii=$expesl2[2];
			}
			
			
			if ($_POST['c_eselon_iii']){$c_eselon_iii=$_POST['c_eselon_iii'];}
			else{$c_eselon_iii=trim($_GET['c_eselon_iii']);}
			$this->view->c_eselon_iii =trim($c_eselon_iii);		
			if ($c_eselon_iii){
			$expesl3 = explode(" ",$c_eselon_iii);	
		//	$c_eselon_iix=$expesl3[0];
			$c_eselon_iii=$expesl3[0];
			$c_satker=$expesl3[1];
			$n_eselon_iii=$expesl3[2];			
			}

			if ($_POST['c_eselon_iv']){$c_eselon_iv=$_POST['c_eselon_iv'];}
			else{$c_eselon_iv=trim($_GET['c_eselon_iv']);}
			$this->view->c_eselon_iv =trim($c_eselon_iv);	
			if ($c_eselon_iv){
			$expesl4 = explode(" ",$c_eselon_iv);	
			$c_eselon_iv=$expesl4[0];
			$n_eselon_iv=$expesl4[1];
			}			
			
			if ($c_lokasi_unitkerja){$cari .= " and c_lokasi_unitkerja='$c_lokasi_unitkerja'";}
			if ($c_eselon_i){$cari .= " and c_eselon_i='$c_eselon_i'";}
			if ($c_eselon_ii ){$cari .= " and c_eselon_ii='$c_eselon_ii'";}
			if ($c_eselon_iii){$cari .= " and c_eselon_iii='$c_eselon_iii'";}
			if ($c_eselon_iv){$cari .= " and c_eselon_iv='$c_eselon_iv'";}
// akhir	 */





	// Ditutup dez		
		$cari= $cari." and (c_eselon !='17' or c_eselon isnull)";  //
		//echo $cari;//.$carix;
		$orderBy = "ORDER BY c_golongan ASC,d_tmt_golongan ASC,c_eselon ASC,q_tktfgs,d_tmt_eselon ASC,d_tmt_cpns asc, q_tahun ASC,c_pend ASC,d_pend_akhir ASC,d_peg_lahir ASC";
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

$htmlx='<font face="Bookman Old Style, Book Antiqua, Garamond" size="2px">DAFTAR PEGAWAI LINGKUP '.strtoupper($jdlesl).'<br>MAHKAMAH AGUNG</font>';

$this->view->judul=$htmlx;
//$html=$html.'<div style="height: 470px; overflow: auto; padding: 5px">';
$html=$html.' <table align="center" border="0" cellspacing="1" cellpadding="2" class="sortable">
		<tr>
    <th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">NO</font></th>
    <th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">NIP LAMA </font></th>
    <th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">NIP BARU </font></th>
    <th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">NAMA</font></th>
    <th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">STATUS KEPEGAWAIAN </font></th>
    <th colspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">PENDIDIKAN</font></th>
    <th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">TEMPAT LAHIR </font></th>
    <th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">TANGGAL LAHIR </font></th>
    <th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">JENIS KELAMIN </font></th>
    <th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">AGAMA</font></th>
    <th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">STATUS NIKAH </font></th>
    <th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">TMT CPNS </font></th>
    <th colspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">GOLONGAN</font></th>
    <th colspan="3"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">JABATAN</font></th>
  </tr>
  <tr>
    <th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">TINGKAT</font></th>
    <th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">TAHUN LULUS </font></th>
    <th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">GOL</font></th>
    <th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">TMT</font></th>
    <th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">ESELON</font></th>
    <th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">JABATAN</font></th>
    <th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">TMT</font></th>
  </tr>		
		';
		if (count($pegawaiList)==0)
		{
		$html=$html.' <tr class="event2">
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
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>';

		}		
	if (count($pegawaiList)!=0){
			for ($j = 0; $j < count($pegawaiList); $j++) 
				{
					$i_peg_nip=$pegawaiList[$j]['i_peg_nip'];
					$i_peg_nip_new=$pegawaiList[$j]['i_peg_nip_new'];
					$n_peg=$pegawaiList[$j]['n_peg'];
					$n_status_kepegawaian=$pegawaiList[$j]['n_status_kepegawaian'];
					$c_pend=$pegawaiList[$j]['c_pend'];
					$n_pendidikan=$pegawaiList[$j]['n_pendidikan'];
					$d_pend_akhir=$pegawaiList[$j]['d_pend_akhir'];
					$n_peg_kota_lahir=$pegawaiList[$j]['n_peg_kota_lahir'];
					$d_peg_lahir2=$pegawaiList[$j]['d_peg_lahir2'];
					$n_peg_jeniskelamin=$pegawaiList[$j]['n_peg_jeniskelamin'];
					$n_agama=$pegawaiList[$j]['n_agama'];
					$n_peg_statusnikah=$pegawaiList[$j]['n_peg_statusnikah'];
					$d_tmt_cpns=$pegawaiList[$j]['d_tmt_cpns'];
					$n_jenis_naik=$pegawaiList[$j]['n_jenis_naik'];
					$n_golongan=$pegawaiList[$j]['n_golongan'];
					$d_tmt_golongan=$pegawaiList[$j]['d_tmt_golongan'];
					$n_eselon=$pegawaiList[$j]['n_eselon'];
					$n_jabatan=$pegawaiList[$j]['n_jabatan'];
					$d_mulai_jabat=$pegawaiList[$j]['d_mulai_jabat'];
					
					$a_peg_kota_lahir=$pegawaiList[$j]['a_peg_kota_lahir'];
					$c_golongan=$pegawaiList[$j]['c_golongan'];
					
				/* 	$d_peg_lahir=$pegawaiList[$j]['d_peg_lahir'];				
					$usiatahun=$pegawaiList[$j]['usiatahun'];
					$usiabulan=$pegawaiList[$j]['usiabulan'];
					$c_golongan=$pegawaiList[$j]['c_golongan'];
					$c_penjenjangan=$pegawaiList[$j]['c_penjenjangan'];					
					$jabatanlengkap=$pegawaiList[$j]['jabatanlengkap'];					
					$d_tmt_eselon=$pegawaiList[$j]['d_tmt_eselon'];
					$masakerjabulan=$pegawaiList[$j]['masakerjabulan'];
					$masakerjatahun=$pegawaiList[$j]['masakerjatahun'];
					$n_golongan_next=$pegawaiList[$j]['n_golongan_next']; */
					
					
					
					
				/* 	if ($masakerjatahun){$masakerja=$masakerjatahun.' tahun';}
					else if ($masakerjabulan){$masakerja=$masakerja." ".$masakerjabulan.' bulan';}					
					else {$masakerja="";} */

				//	$d_tmt_kgb=$pegawaiList[$j]['d_tmt_kgb'];

		/* 	$d_tmt_golongan1=substr($d_tmt_golongan,0,2);
			$d_tmt_golongan2=substr($d_tmt_golongan,3,2);
			$d_tmt_golongan3=substr($d_tmt_golongan,6,4); */
			
		/* 	if ($d_tmt_golongan1){
			$d_tmt_kgb_baru	= "$d_tmt_golongan1-$d_tmt_golongan2-".(4 + $d_tmt_golongan3);
			} */
					$noUrut = (($currentPage -1)* $numToDisplay) +$j+1;
					if ($j%2==0) {
						  $html=$html.'<tr class="event">';
					 } 
					else if ($j%2==1) { 
						  $html=$html.'<tr class="event2">';
					 }
					 
					 $html=$html.'
					<td align="left" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$noUrut.'.</font></td>
					<td align="left" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">&nbsp'.$i_peg_nip.'</td>
					<td align="left" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">&nbsp'.$i_peg_nip_new.'</td>
					<td align="left" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">&nbsp'.$n_peg.'</td>
					<td align="left" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">&nbsp'.$n_status_kepegawaian.'</td>
					<td align="center" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">&nbsp'.$n_pendidikan.'</td>
					<td align="center" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">&nbsp'.$d_pend_akhir.'</td>
					<td align="left" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">&nbsp'.$n_peg_kota_lahir.'</td>
					<td align="center" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">&nbsp'.$d_peg_lahir2.'</td>
					<td align="left" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">&nbsp'.$n_peg_jeniskelamin.'</td>
					<td align="left" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">&nbsp'.$n_agama.'</td>
					<td align="left" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">&nbsp'.$n_peg_statusnikah.'</td>
					<td align="center" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">&nbsp'.$d_tmt_cpns.'</td>
					<td align="center" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">&nbsp'.$n_golongan.'</td>
					<td align="center" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">&nbsp'.$d_tmt_golongan.'</td>
					<td align="center" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">&nbsp'.$n_eselon.'</td>
					<td align="left" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">&nbsp'.$n_jabatan.'</td>
					<td align="center" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">&nbsp'.$d_mulai_jabat.'</td>
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
	var url = "<?php echo $this->basePath; ?>/sdmmodule/exportexcel/exl?limit=<?=$limit?>&c_eselon_i=<?=$c_eselon_i?>&c_eselon_ii=<?=$c_eselon_ii?>&c_eselon_iii=<?=$c_eselon_iii?>&c_eselon_iv=<?=$c_eselon_iv?>&c_eselon_v=<?=$c_eselon_v?>&c_lokasi_unitkerja=<?=$c_lokasi_unitkerja?>&blnawal=<?=$blnawal?>&c_eselon=<?=$c_eselon?>&c_golongan=<?=$c_golongan?>&c_status_kepegawaian=<?=$c_status_kepegawaian?>&thnakhir=<?=$thnakhir?>&thnawal=<?=$thnawal?>";
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
// 	Ditutup dez	
		$c_eselon = $_GET['c_eselon'];
		$c_lokasi_unitkerja = $_GET['c_lokasi_unitkerja'];
		$c_eselon_i = $_GET['c_eselon_i'];
		$c_eselon_ii = $_GET['c_eselon_ii'];
		$c_eselon_iii = $_GET['c_eselon_iii'];
		$c_eselon_iv = $_GET['c_eselon_iv'];
		$c_eselon_v = $_GET['c_eselon_v'];	
//
		
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
/* Ditutup dez
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
	 */	
	 
	 
		//Unit Kerja=====================

// 	Ditutup dez
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
			

		}	//	akhir	 
	
	
	
// $c_eselon=$_GET['c_eselon'];
// $c_status_kepegawaian=$_GET['c_status_kepegawaian'];
// $c_golongan=$_GET['c_golongan'];
// $blnawal=$_GET['blnawal'];
// $thnawal=$_GET['thnawal'];
// $thnakhir=$_GET['thnakhir'];
// if ($_GET['blnawal']){$blnawal=$_GET['blnawal'];



/* Ditutup dez
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
// if ($c_eselon && $c_status_kepegawaian){
//	$c_golongan=substr($_GET['c_golongan'],0,2); 
//	$cari= $cari." and c_golongan ='$c_golongan' and c_status_kepegawaian='$c_status_kepegawaian' ";
//} 
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
$cari= $cari." and (c_eselon !='17' or c_eselon isnull)";		*/





/* 
// Tambah dez

if ($_POST['c_lokasi_unitkerja']){$c_lokasi_unitkerja=trim($_POST['c_lokasi_unitkerja']);}
			else{$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);}
			//$this->view->lokasiList = $this->reff_serv->getLokasi('');
			//$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");


			if ($_POST['c_eselon_i']){$c_eselon_i=$_POST['c_eselon_i'];}
			else{$c_eselon_i=trim($_GET['c_eselon_i']);}
			$this->view->c_eselon_i =$c_eselon_i;
			if ($c_eselon_i){
				$expesl1 = explode(" ",$c_eselon_i);
				$c_eselon_i=$expesl1[0];
				$n_eselon_i=$expesl1[1];
			}
			
			if ($_POST['c_eselon_ii']){$c_eselon_ii=$_POST['c_eselon_ii'];}
			else{$c_eselon_ii=trim($_GET['c_eselon_ii']);}
			$this->view->c_eselon_ii =$c_eselon_ii;
			if ($c_eselon_ii){
			$expesl2 = explode(" ",$c_eselon_ii);
			$c_eselon_ii=$expesl2[0];
			$c_parent=$expesl2[1];
			$n_eselon_ii=$expesl2[2];
			}
			
			
			if ($_POST['c_eselon_iii']){$c_eselon_iii=$_POST['c_eselon_iii'];}
			else{$c_eselon_iii=trim($_GET['c_eselon_iii']);}
			$this->view->c_eselon_iii =trim($c_eselon_iii);		
			if ($c_eselon_iii){
			$expesl3 = explode(" ",$c_eselon_iii);	
		//	$c_eselon_iix=$expesl3[0];
			$c_eselon_iii=$expesl3[0];
			$c_satker=$expesl3[1];
			$n_eselon_iii=$expesl3[2];			
			}

			if ($_POST['c_eselon_iv']){$c_eselon_iv=$_POST['c_eselon_iv'];}
			else{$c_eselon_iv=trim($_GET['c_eselon_iv']);}
			$this->view->c_eselon_iv =trim($c_eselon_iv);	
			if ($c_eselon_iv){
			$expesl4 = explode(" ",$c_eselon_iv);	
			$c_eselon_iv=$expesl4[0];
			$n_eselon_iv=$expesl4[1];
			}			
			
			if ($c_lokasi_unitkerja){$cari .= " and c_lokasi_unitkerja='$c_lokasi_unitkerja'";}
			if ($c_eselon_i){$cari .= " and c_eselon_i='$c_eselon_i'";}
			if ($c_eselon_ii ){$cari .= " and c_eselon_ii='$c_eselon_ii'";}
			if ($c_eselon_iii){$cari .= " and c_eselon_iii='$c_eselon_iii'";}
			if ($c_eselon_iv){$cari .= " and c_eselon_iv='$c_eselon_iv'";}
// Akhir */


//$cari.$carix = "ORDER BY n_peg ASC";
$pegawaiList = $this->pegawai_serv->getPegawaiListAll($cari.$carix);

// Modif dez, kondisi pertama dibawah
if ($c_lokasi_unitkerja=='1'){$n_lokasi_unitkerja='Kantor Pusat MA';}elseif($c_lokasi_unitkerja=='3'){$n_lokasi_unitkerja='Pengadilan';}else{$n_lokasi_unitkerja='Instansi Luar';}
if ($n_lokasi_unitkerja){$jdlesl=$n_lokasi_unitkerja;}
if ($n_eselon_i){$jdlesl=$n_lokasi_unitkerja."<br>".$n_eselon_i;}
if ($n_eselon_ii){$jdlesl=$n_lokasi_unitkerja."<br>".$n_eselon_ii."<br>".$n_eselon_i;}
if ($n_eselon_iii){$jdlesl=$n_lokasi_unitkerja."<br>".$n_eselon_iii."<br>".$n_eselon_ii."<br>".$n_eselon_i;}
if ($n_eselon_iv){$jdlesl=$n_lokasi_unitkerja."<br>".$n_eselon_iv." ".$n_eselon_iii."<br>".$n_eselon_ii."<br>".$n_eselon_i;}

$html='<center><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">DAFTAR PEGAWAI<br>LINGKUP '.strtoupper($jdlesl).'<br>MAHKAMAH AGUNG</font></center>';

$html=$html.' <table align="center" border="1" width="100%">
		<tr>
    <th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">NO</font></th>
    <th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">NIP LAMA </font></th>
    <th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">NIP BARU </font></th>
    <th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">NAMA</font></th>
    <th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">STATUS KEPEGAWAIAN </font></th>
    <th colspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">PENDIDIKAN</font></th>
    <th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">TEMPAT LAHIR </font></th>
    <th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">TANGGAL LAHIR </font></th>
    <th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">JENIS KELAMIN </font></th>
    <th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">AGAMA</font></th>
    <th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">STATUS NIKAH </font></th>
    <th rowspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">TMT CPNS </font></th>
    <th colspan="2"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">GOLONGAN</font></th>
    <th colspan="3"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">JABATAN</font></th>
  </tr>
  <tr>
    <th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">TINGKAT</font></th>
    <th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">TAHUN LULUS </font></th>
    <th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">GOL</font></th>
    <th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">TMT</font></th>
    <th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">ESELON</font></th>
    <th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">JABATAN</font></th>
    <th><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">TMT</font></th>
  </tr>		
		';
		if (count($pegawaiList)==0)
		{
		$html=$html.' <tr>
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
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>';

		}		
	if (count($pegawaiList)!=0){
			for ($j = 0; $j < count($pegawaiList); $j++) 
				{
				//	$nipegNew=$pegawaiList[$j]['nipegNew'];
					$i_peg_nip=$pegawaiList[$j]['i_peg_nip'];
					$i_peg_nip_new=$pegawaiList[$j]['i_peg_nip_new'];					// Tambahan dez
					$n_peg=$pegawaiList[$j]['n_peg'];
					$c_status_kepegawaian=$pegawaiList[$j]['c_status_kepegawaian'];
					$n_status_kepegawaian=$pegawaiList[$j]['n_status_kepegawaian'];
					$n_pendidikan=$pegawaiList[$j]['n_pendidikan'];
					$d_pend_akhir=$pegawaiList[$j]['d_pend_akhir'];
					$n_peg_kota_lahir=$pegawaiList[$j]['n_peg_kota_lahir'];
					$d_peg_lahir2=$pegawaiList[$j]['d_peg_lahir2'];
					$n_peg_jeniskelamin=$pegawaiList[$j]['n_peg_jeniskelamin'];
					$n_agama=$pegawaiList[$j]['n_agama'];
					$n_peg_statusnikah=$pegawaiList[$j]['n_peg_statusnikah'];
					$d_tmt_cpns=$pegawaiList[$j]['d_tmt_cpns'];
					$n_jenis_naik=$pegawaiList[$j]['n_jenis_naik'];
					$n_golongan=$pegawaiList[$j]['n_golongan'];
					$d_tmt_golongan=$pegawaiList[$j]['d_tmt_golongan'];
					$n_eselon=$pegawaiList[$j]['n_eselon'];
					$n_jabatan=$pegawaiList[$j]['n_jabatan'];
					$d_mulai_jabat=$pegawaiList[$j]['d_mulai_jabat'];
					
					$d_peg_lahir=$pegawaiList[$j]['d_peg_lahir'];
					$tgl = strftime("%d-%m-%Y",strtotime("".$d_peg_lahir.""));
					
					
					$out = "'";
					$tes = str_replace($out, "", ".'$nipegNew'.");
					
				/* 	$format = "%d-%m-%Y";
					$tgl = date( string $format [, int $d_peg_lahir2 ]); */
					
				 /* 	$numBer = $nipegNew;
					$format = '%s';  */
					
			/* 	Ditutup dez
					if ($masakerjatahun){$masakerja=$masakerjatahun.' tahun';}
					else if ($masakerjabulan){$masakerja=$masakerja." ".$masakerjabulan.' bulan';}					
					else {$masakerja="";}

					$d_tmt_kgb=$pegawaiList[$j]['d_tmt_kgb'];

			$d_tmt_golongan1=substr($d_tmt_golongan,0,2);
			$d_tmt_golongan2=substr($d_tmt_golongan,3,2);
			$d_tmt_golongan3=substr($d_tmt_golongan,6,4);
			
			$d_tmt_kgb_baru	= "$d_tmt_golongan1-$d_tmt_golongan2-".(4 + $d_tmt_golongan3); */
					$noUrut++;
					$html=$html.'<tr>';
					 $html=$html.'	
					 <td align="left" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$noUrut.'.</font></td>
					<td align="left" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$i_peg_nip.'&nbsp;</td>
					<td align="left" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$i_peg_nip_new.'&nbsp;</td>
					<td align="left" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$n_peg.'</td>
					<td align="left" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$n_status_kepegawaian.'</td>
					<td align="left" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$n_pendidikan.'</td>
					<td align="left" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$d_pend_akhir.'</td>
					<td align="left" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$n_peg_kota_lahir.'</td>
					<td align="left" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$tgl.'&nbsp;</td>
					<td align="left" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$n_peg_jeniskelamin.'</td>
					<td align="left" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$n_agama.'</td>
					<td align="left" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$n_peg_statusnikah.'</td>
					<td align="left" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$d_tmt_cpns.'&nbsp;</td>
					<td align="left" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$n_golongan.'</td>
					<td align="left" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$d_tmt_golongan.'&nbsp;</td>
					<td align="left" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$n_eselon.'</td>
					<td align="left" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$n_jabatan.'</td>
					<td align="left" width="3%"><font face="Bookman Old Style, Book Antiqua, Garamond" size="1.5">'.$d_mulai_jabat.'&nbsp;</td>
							</tr>';				 
				}
			}	
	$html=$html.'</table>';
	$this->view->par=$_GET['par'];
	$this->view->view=$html;


}	
	
}

?>