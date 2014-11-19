<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_Pangkat_Service.php";

class Sdmmodule_DataPangkatController extends Zend_Controller_Action {
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu'); 		
		$this->reff_serv = sdm_refferensi_Service::getInstance();
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->pangkat_serv = Sdm_Pangkat_Service::getInstance();
		$this->view->nama= $this->nama;
		$this->view->nip= $this->nip;
		$this->view->golongan= $this->golongan;
		$this->view->pangkat=$this->pangkat;
		$this->view->filephoto=$this->filephoto;
		$this->view->statuspeg=$this->statuspeg;
		$this->view->nipnew= $this->nipnew;
		
		$sespeg = new Zend_Session_Namespace('sespeg');

		$this->view->nama= $sespeg->nama;
		$this->view->nip= $sespeg->nip;
		$this->view->golongan= $sespeg->golongan;
		$this->view->pangkat= $sespeg->pangkat;	
		$this->view->filephoto= $sespeg->filephoto;	
		$this->view->statuspeg= $sespeg->statuspeg;
		$this->view->nipnew= $sespeg->nipnew;	

		$ssologin = new Zend_Session_Namespace('ssologin');
		$this->view->userid=$ssologin->userid;
		$this->view->sektoral			= $ssologin->arrayc_sektoral[1]; 
		$this->view->wewenang			= $ssologin->arrayc_wewenang[1]; 
		if($this->view->wewenang == 'O'){$this->view->c_izin='000002';}
		//$this->view->c_izin=$ssologin->c_izin[0]['c_izin'];				
    }
	
    public function indexAction() {
	   
    }
public function pangkatjsAction() 
{
	header('content-type : text/javascript');
	$this->render('pangkatjs');
}	
	
public function listpangkatAction() {
	$nip=$this->view->nip;	
	$cari = " and i_peg_nip ='$nip' ";
	
	//echo "cari = $cari";
	$cstatusKepegawaian = $this->pangkat_serv->getStatusKepeg($cari);
	$cari2 = " and c_peg_tipegolongan = '$cstatusKepegawaian' ";
	$maintainAllowed =  $this->pangkat_serv->maintainAllowed($cari2);
	$this->view->maintainAllowed = $maintainAllowed;
	$cari3 = " and a.i_peg_nip ='$nip' 
			   and b.c_peg_tipegolongan = '$cstatusKepegawaian' ";
	
	$this->view->pangkatList = $this->pangkat_serv->getPangkatList($cari3);	
}
public function pangkatAction() {
	$par=$_GET['par'];
	$nip=$_GET['nip'];
	$jenispnk=$_GET['nGol'];
	$id=$_GET['id'];
	
	if($par == 'insert'){
		$cari = " and i_peg_nip ='$nip' ";
		$cstatusKepegawaian = $this->pangkat_serv->getStatusKepeg($cari);
	} else if($par == 'update'){
		$cari = " and b.id ='$id' ";
		$cstatusKepegawaian = $this->pangkat_serv->getStatusKepegById($cari);
	}
	
	$carigol=" and c_peg_tipegolongan ='$cstatusKepegawaian' ";
	$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai($carigol);
	
	$this->view->jnsNaikGolRef = $this->reff_serv->getTrJnsnaikGol('');

	
	if ($par=='insert'){
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
	}
	else if ($par=='delete'){
		$this->view->par="Hapus";
		$this->view->jdl="Manghapus ";	
		$par=$_GET['par'];
		$nip=$_GET['nip'];
		$nGol=$_GET['nGol'];
		$tmt=$_GET['tmt'];
		$id=$_GET['id'];
		$jns=$_GET['jns'];
		$this->listDataByKey($id);
	}	
	else{
	
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$nip=$_GET['nip'];
		$this->view->id  = $_GET['id'];	
		$id=$_GET['id'];
		$nGol=$_GET['nGol'];
		$tmt=$_GET['tmt'];
		$jns=$_GET['jns'];
		$this->listDataByKey($id);
	
		if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Merubah ";}		
		else{$this->view->jdl="Melihat ";$this->render('pangkatview');}			
	}

}
public function maintaindataAction() {

	if ($_POST['d_tmt_golongan'])
		{
			$d_tmt_golongan1=substr($_POST['d_tmt_golongan'],0,2);
			$d_tmt_golongan2=substr($_POST['d_tmt_golongan'],3,2);
			$d_tmt_golongan3=substr($_POST['d_tmt_golongan'],6,4);
		}
		$d_tmt_golongan=$d_tmt_golongan3."-".$d_tmt_golongan2."-".$d_tmt_golongan1;
	if ($_POST['d_sk_golongan'])
		{
			$d_sk_golongan1=substr($_POST['d_sk_golongan'],0,2);
			$d_sk_golongan2=substr($_POST['d_sk_golongan'],3,2);
			$d_sk_golongan3=substr($_POST['d_sk_golongan'],6,4);
		}
		$d_sk_golongan=$d_sk_golongan3."-".$d_sk_golongan2."-".$d_sk_golongan1;		
		if (!$_POST['d_sk_golongan']){$d_sk_golongan=null;}

$theFileSize = $_FILES['e_file_sk']['size'];
	if (!$theFileSize)
	{$destDir = $_POST['a_file'];}
	else{		
		if ($_POST['a_file'])
		{
				$dskgolongan=$d_sk_golongan3.$d_sk_golongan2.$d_sk_golongan1;	
				$namefile=trim($_POST['i_peg_nip'])."_".trim($_POST['c_jenis_naik'])."_".$dskgolongan;
				$FileName_dat = $namefile;
				$fileName = $_FILES['e_file_sk']['name'];	
				$extention = strtolower(substr($fileName, strrpos($fileName, '.') + 1));				
				$FileName_pdf = $FileName_dat.'.'.$extention;
				$destDir = "$FileName_pdf";	
		}
	}

	if ($_POST['d_tmt_golongan2'])
		{
			$d_tmt_golongan21=substr($_POST['d_tmt_golongan2'],0,2);
			$d_tmt_golongan22=substr($_POST['d_tmt_golongan2'],3,2);
			$d_tmt_golongan23=substr($_POST['d_tmt_golongan2'],6,4);
		}
		$d_tmt_golongan2=$d_tmt_golongan23."-".$d_tmt_golongan22."-".$d_tmt_golongan21;

	if ($_POST['d_sk_bkn'])
		{
			$d_sk_bkn1=substr($_POST['d_sk_bkn'],0,2);
			$d_sk_bkn2=substr($_POST['d_sk_bkn'],3,2);
			$d_sk_bkn3=substr($_POST['d_sk_bkn'],6,4);
		}
		$d_sk_bkn=$d_sk_bkn3."-".$d_sk_bkn2."-".$d_sk_bkn1;		
		if (!$_POST['d_sk_bkn']){$d_sk_bkn=null;}
		$v_gaji_pokok = $_POST['v_gaji_pokok'] != '' ? $_POST['v_gaji_pokok'] : 0;
		$q_masakerja_bulan = $_POST['q_masakerja_bulan'] != '' ? $_POST['q_masakerja_bulan'] : 0;
		$q_masakerja_tahun = $_POST['q_masakerja_tahun'] != '' ? $_POST['q_masakerja_tahun'] : 0;
	$MaintainData = array(
		"id"=>$_POST['id'],
		"i_peg_nip"=>$_POST['i_peg_nip'],
		"c_golongan"=>$_POST['c_golongan'],
		"c_golongan2"=>$_POST['c_golongan2'],
		"c_jenis_naik"=>$_POST['c_jenis_naik'],
		"c_jenis_naik2"=>$_POST['c_jenis_naik2'],
		"q_masakerja_bulan"=>$q_masakerja_bulan,
		"q_masakerja_tahun"=>$q_masakerja_tahun,
		"v_gaji_pokok"=>$v_gaji_pokok,
		"d_tmt_golongan"=>$d_tmt_golongan,
		"d_tmt_golongan2"=>$d_tmt_golongan2,
		"i_sk_pejabat"=>$_POST['i_sk_pejabat'],
		"e_keterangan"=>$_POST['e_keterangan'],
		"i_sk_golongan"=>$_POST['i_sk_golongan'],
		"d_sk_golongan"=>$d_sk_golongan,
		"i_sk_bkn"=>$_POST['i_sk_bkn'],
		"d_sk_bkn"=>$d_sk_bkn,
		"e_file_sk"=>$destDir,
		"i_entry"=>$this->view->userid,
		"d_entry"=>date('Ymd'));

	$stop="no";		
	$theFileSize = $_FILES['e_file_sk']['size'];
        if ($theFileSize>999999){
            $theDiv = $theFileSize / 1000000;
            //$theFileSize = round($theDiv, 1)." MB"; 
	    $theFileSize = round($theDiv, 1); 
	    if ($theFileSize>=4){$stop="ok";}
        } 


	if ($_POST['proses']=='Simpan')
	{
		if ($stop=="ok"){$hasil="Gagal besar file tidak diijinkan";}
		else{$hasil = $this->pangkat_serv->maintainData($MaintainData,'insert');}
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
	}
	else if ($_POST['proses']=='Hapus')
	{
		$hasil = $this->pangkat_serv->maintainData($MaintainData,'delete');
		$this->view->par="Hapus";
		$this->view->jdl="Menghapus ";
		$par="Menghapus";
	}	
	else
	{
		if ($stop=="ok"){$hasil="Gagal besar file tidak diijinkan";}
		else{$hasil = $this->pangkat_serv->maintainData($MaintainData,'update');}
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";		
	}
	
/// simpan file

 		if ($hasil=="sukses"){
			$dskgolongan=$d_sk_golongan3.$d_sk_golongan2.$d_sk_golongan1;	
			$namefile=trim($_POST['i_peg_nip'])."_".trim($_POST['c_jenis_naik'])."_".$dskgolongan;
			$FileName_pdf;
			$fileNamex = $_FILES['e_file_sk']['name'];
			$extentionx = strtolower(substr($fileNamex, strrpos($fileNamex, '.') + 1));
				
		    if (!empty($_FILES['e_file_sk'])) 
				{$FileName_pdf = $FileName_dat.'.'.$extentionx;}
				$FileName_dat = $namefile;
				$FileName_pdf = $FileName_dat.'.'.$extentionx;
					
				if (!empty($_FILES['e_file_sk'])) 	   {

				   $fileName = $_FILES['e_file_sk']['name'];
				   $extention = strtolower(substr($fileName, strrpos($fileName, '.') + 1));
						$destDir = "../library/data/sdm/pangkat/$FileName_pdf";
						if (move_uploaded_file($_FILES['e_file_sk']['tmp_name'], $destDir)) { 
							$lampiran ="file";
						}
				}
			}	
//=================================================================================================

	if ($hasil=='sukses')
	{
		$this->updateTmPegawai($_POST['i_peg_nip'],$_POST['c_jenis_naik2']);
		if ($_POST['proses']=='Hapus')
		{
			$this->view->par="Hapus";
			$this->view->jdl="Menghapus ";
			$par="Menghapus";
		}
		else
		{
			$this->view->par="Ubah";
			$this->view->jdl="Merubah ";
			$par="Merubah";				
		}
	}	
	
	//$this->listDataByKey($_POST['i_peg_nip'],$_POST['c_golongan'],$d_tmt_golongan,$_POST['c_jenis_naik']);
	//$this->listDataByKey($_POST['id']);
	$cari = " and i_peg_nip ='".$_POST['i_peg_nip']."' ";
	
	//echo "cari = $cari";
	$cstatusKepegawaian = $this->pangkat_serv->getStatusKepeg($cari);
	
	$carigol=" and c_peg_tipegolongan ='$cstatusKepegawaian' ";
	$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai($carigol);
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	
	///////////
	
	$cari2 = " and c_peg_tipegolongan = '$cstatusKepegawaian' ";
	$maintainAllowed =  $this->pangkat_serv->maintainAllowed($cari2);
	$this->view->maintainAllowed = $maintainAllowed;
	$cari3 = " and i_peg_nip ='".$_POST['i_peg_nip']."' 
			   and c_peg_tipegolongan = '$cstatusKepegawaian' ";
	$this->view->pangkatList = $this->pangkat_serv->getPangkatList($cari3);
	////////
	
	if ($hasil=='sukses'){$this->render('listpangkat');}
	else{$this->render('pangkat');}
	
}
public function hapusdataAction() {
	$MaintainData = array("id"=>$_GET['id']);		
	$hasil = $this->pangkat_serv->maintainData($MaintainData,'delete');	
	$this->updateTmPegawai($_GET['nip'],'');
	$pesan= "Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->listpangkatAction();
	$this->render('listpangkat');	
}
public function pangkataAction() {
	$this->view->c_pnk=$_GET['c_pnk'];
	$this->view->nmJenjangList = $this->reff_serv->getpangkat($cari);
}
public function pangkatbAction() {
	$this->view->c_pnk=$_GET['c_pnk'];
	$this->view->nmJenjangList = $this->reff_serv->getpangkat($cari);
}	 

	
//public function listDataByKey($nip,$nGol,$tmt,$jns) {		
	//$cari = " and i_peg_nip ='$nip' and c_golongan ='$nGol' and to_char(d_tmt_golongan,'yyyy-mm-dd')='$tmt'  "; // and c_jenis_naik='$jns'
public function listDataByKey($id) {		
	if($id) {$cari = " and id ='$id'";} // and c_jenis_naik='$jns'

	$pangkatList = $this->pangkat_serv->getPangkatList($cari);
	$this->view->i_peg_nip=$pangkatList[0]['i_peg_nip'];
	$this->view->c_golongan=$pangkatList[0]['c_golongan'];

	$tgltmt = $pangkatList[0]['d_tmt_golongan'];
	$thntmt = substr($tgltmt,0,4);
	$blntmt = substr($tgltmt,5,2);
	$hrtmt = substr($tgltmt,8,2);
	if ($tgltmt){
	$tgltmt = $hrtmt."-".$blntmt."-".$thntmt;
	}					
	$this->view->d_tmt_golongan=$tgltmt;
	$this->view->i_sk_golongan=$pangkatList[0]['i_sk_golongan'];
	$this->view->i_sk_pejabat=$pangkatList[0]['i_sk_pejabat'];
	$this->view->i_sk_bkn=$pangkatList[0]['i_sk_bkn'];

	$tglbkn = $pangkatList[0]['d_sk_bkn'];
	$thnbkn = substr($tglbkn,0,4);
	$blnbkn = substr($tglbkn,5,2);
	$hrbkn = substr($tglbkn,8,2);
	if ($tglbkn){
	$tglbkn = $hrbkn."-".$blnbkn."-".$thnbkn;
	}	
	$this->view->d_sk_bkn=$tglbkn;
	$tglSK = $pangkatList[0]['d_sk_golongan'];
	$thnSK = substr($tglSK,0,4);
	$blnSK = substr($tglSK,5,2);
	$hrSK = substr($tglSK,8,2);
	if ($tglSK){
	$tglSK = $hrSK."-".$blnSK."-".$thnSK;
	}
	
	$this->view->d_sk_golongan=$tglSK;
	$this->view->q_masakerja_bulan=$pangkatList[0]['q_masakerja_bulan'];
	$this->view->q_masakerja_tahun=$pangkatList[0]['q_masakerja_tahun'];
	$this->view->v_gaji_pokok=$pangkatList[0]['v_gaji_pokok'];
	$this->view->c_jenis_naik=$pangkatList[0]['c_jenis_naik'];
	$this->view->e_file_sk=$pangkatList[0]['e_file_sk'];
	$this->view->e_keterangan=$pangkatList[0]['e_keterangan'];	
}	
 	
public function updateTmPegawai($nip,$jnsnaik) { 
$jnsnaik=trim($jnsnaik);
// and c_jenis_naik='$jnsnaik'
	if ($jnsnaik){
	$carilist = " and i_peg_nip='$nip'  and d_tmt_golongan in (select max(d_tmt_golongan) from sdm.tm_golongan_pangkat where i_peg_nip='$nip' ) "; // and c_jenis_naik='$jnsnaik' 
	}
	else{
	$carilist = " and i_peg_nip='$nip'  and d_tmt_golongan in (select max(d_tmt_golongan) from sdm.tm_golongan_pangkat where i_peg_nip='$nip' ) ";
	}
	$pangkatList = $this->pangkat_serv->getPangkatList($carilist);
	
	$countdata=count($pangkatList);	
if ($countdata > 0){
		$MaintainData = array("i_peg_nip"=>$pangkatList[0]['i_peg_nip'],
							"c_golongan"=>$pangkatList[0]['c_golongan'],
							"d_tmt_golongan"=>$pangkatList[0]['d_tmt_golongan'],
							"v_gaji_pokok"=>$pangkatList[0]['v_gaji_pokok']*1);
	$hasil = $this->pangkat_serv->updateTmPegawaiKp($MaintainData);						
}
//	if ($jnsnaik=='KP'){
	
		
//}
//	if ($jnsnaik=='KGB'){	
//		$hasil = $this->pangkat_serv->updateTmPegawaiKgb($MaintainData);
//}			

}	

public function mkAction() {
	$c_golongan_now = $_GET['c_golongan'];
	list($dd_golongan_now,$m_golongan_now,$y_golongan_now) = explode('-',$_GET['d_tmtgol']);
	
	$d_golongan_now = $y_golongan_now.'-'.$m_golongan_now.'-'.$dd_golongan_now;
	$nip=$this->view->nip;	
	$dataPeg = $this->pegawai_serv->getPegawaiListByNip(" and i_peg_nip='$nip'");
	//$c_golongan=$dataPeg[0]['c_golongan'];
	//$d_tmt_golongan=$dataPeg[0]['d_tmt_golongan'];
	$c_gol_cpns=$dataPeg[0]['c_gol_cpns'];
	//$d_tmt_cpns=$dataPeg[0]['d_tmt_cpns'];		
	if($dataPeg[0]['d_tmt_cpns']){
		list($dd_tmt_cpns,$m_tmt_cpns,$y_tmt_cpns) = explode('-',$dataPeg[0]['d_tmt_cpns']);
		$d_tmt_cpns= $y_tmt_cpns.'-'.$m_tmt_cpns.'-'.$dd_tmt_cpns;
	}
	if (!$c_gol_cpns || $d_tmt_cpns == ''){
	?>
	<script> alert ("Untuk Mengisi MK secara otomatis, data golongan cpns & TMT CPNS harus diisi pada menu CPNS")</script>	
	<?
	}
	//$carigolPns=" and c_peg_golongan='$c_golongan' ";
	//$levelPns = $this->reff_serv->getGolonganPegawai($carigolPns);
	//$levelPns=$levelPns[0]['c_peg_lvlgolongan'];
	//$levelPns=$_GET['c_golongan'];
	
	$carigolCpns=" and c_peg_golongan='$c_gol_cpns' ";
	$levelCpns = $this->reff_serv->getGolonganPegawai($carigolCpns);
	$levelCpns=$levelCpns[0]['c_peg_lvlgolongan'];
	$levelCpns=$levelCpns*1;
	
	$carigolNow=" and c_peg_golongan='$c_golongan_now' ";
	$levelNow = $this->reff_serv->getGolonganPegawai($carigolNow);
	$levelNow=$levelNow[0]['c_peg_lvlgolongan'];
	$levelNow=$levelNow*1;
	
	$carigol=" and gol_cpns='$levelCpns' and gol_curent='$levelNow' ";
	if ($c_gol_cpns){
		$thn_minus = $this->pangkat_serv->getTrMasaKerja($carigol);		
		$thn_minus=$thn_minus[0]['thn_minus']*1;
	}
	
	//if (!$d_tmt_golongan){$this->view->psn="Pemberitahuan:  data TMT Golongan tidak ada";}
	//if (!$d_tmt_cpns){$this->view->psn="Pemberitahuan: data TMT CPNS tidak ada";}
	if ($d_golongan_now && $d_tmt_cpns){
		$mktahun=$this->pangkat_serv->getMk("thn",$d_golongan_now,$d_tmt_cpns);
		$q_masakerja_tahun=$mktahun*1-$thn_minus*1;
		$q_masakerja_bulan=$this->pangkat_serv->getMk("bln",$d_golongan_now,$d_tmt_cpns);	
	}
	
	
	
	$this->view->q_masakerja_tahun=$q_masakerja_tahun;
	$this->view->q_masakerja_bulan=$q_masakerja_bulan;
	
	
}

}
?>