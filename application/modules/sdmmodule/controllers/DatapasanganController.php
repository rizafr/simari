<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_Pasangan_Service.php";
require_once "service/sdm/sdm_refferensi_Service.php";
class Sdmmodule_DataPasanganController extends Zend_Controller_Action {
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu'); 
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->pasangan_serv = Sdm_Pasangan_Service::getInstance();
		$this->reff_serv = sdm_refferensi_Service::getInstance();
		$this->view->nama= $this->nama;
		$this->view->nip= $this->nip;
		$this->view->golongan= $this->golongan;
		$this->view->pangkat=$this->pangkat;
		$this->view->filephoto=$this->filephoto;
		$this->view->statuspeg=$this->statuspeg;
		$this->view->cpegstatusnikah=$this->cpegstatusnikah;
		$this->view->nipnew= $this->nipnew;
		
		$sespeg = new Zend_Session_Namespace('sespeg');

		$this->view->nama= $sespeg->nama;
		$this->view->nip= $sespeg->nip;
		$this->view->golongan= $sespeg->golongan;
		$this->view->pangkat= $sespeg->pangkat;	
		$this->view->filephoto= $sespeg->filephoto;	
		$this->view->statuspeg= $sespeg->statuspeg;
		$this->view->cpegstatusnikah= $sespeg->cpegstatusnikah;
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
public function pasanganjsAction() 
{
	header('content-type : text/javascript');
	$this->render('pasanganjs');
}	
	
public function listpasanganAction() {
	$nip=$this->view->nip;	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->pasanganList = $this->pasangan_serv->getPasanganList($cari);	
}
public function pasanganAction() {

	$par=$_GET['par'];	
	if ($par=='insert'){
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
	}
	else{
		$this->view->par="Ubah";
		//$this->view->jdl="Merubah ";			
		$par=$_GET['par'];
		$this->view->id= $_GET['id'];
		$id=$_GET['id'];
		$this->listDataByKey($id);	
		
		if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Merubah ";}
		else{$this->view->jdl="Melihat ";$this->render('pasanganview');}			
	}
	$this->view->pekerjaanList = $this->reff_serv->getPekerjaan(''); 
	
}
public function maintaindataAction() {
 		if ($_POST['d_tanggal_lahir'])
		{
			$d_tanggal_lahir1=substr($_POST['d_tanggal_lahir'],0,2);
			$d_tanggal_lahir2=substr($_POST['d_tanggal_lahir'],3,2);
			$d_tanggal_lahir3=substr($_POST['d_tanggal_lahir'],6,4);
		}
		$d_tanggal_lahir=$d_tanggal_lahir3."-".$d_tanggal_lahir2."-".$d_tanggal_lahir1;
		if (!$_POST['d_tanggal_lahir']){$d_tanggal_lahir=null;$cektglmulai=true;}
		else{$cektglmulai=checkdate($d_tanggal_lahir2,$d_tanggal_lahir1,$d_tanggal_lahir3);}

 		if ($_POST['d_tanggal_nikah'])
		{
			$d_tanggal_nikah1=substr($_POST['d_tanggal_nikah'],0,2);
			$d_tanggal_nikah2=substr($_POST['d_tanggal_nikah'],3,2);
			$d_tanggal_nikah3=substr($_POST['d_tanggal_nikah'],6,4);
		}
		$d_tanggal_nikah=$d_tanggal_nikah3."-".$d_tanggal_nikah2."-".$d_tanggal_nikah1;
		if (!$_POST['d_tanggal_nikah']){$d_tanggal_nikah=null;$cektglakhir=true;}
		else{$cektglakhir=checkdate($d_tanggal_nikah2,$d_tanggal_nikah1,$d_tanggal_nikah3);}

 		
if (($cektglmulai==true &&  $cektglakhir==true) )
{	
	$MaintainData = array("id"=>$_POST['id'],
						"i_peg_nip"=>$_POST['i_peg_nip'],
						"c_pasangan"=>$_POST['c_pasangan'],
						"q_pasangan"=>$_POST['q_pasangan']*1,
						"c_pasangan2"=>$_POST['c_pasangan2'],
						"q_pasangan2"=>$_POST['q_pasangan2']*1,
						"c_pekerjaan"=>$_POST['c_pekerjaan'],
						"c_jns_pekerjaan"=>$_POST['c_jns_pekerjaan'],
						"i_nip_pasangan"=>$_POST['i_nip_pasangan'],
						"n_nama"=>$_POST['n_nama'],
						"a_tempat_lahir"=>$_POST['a_tempat_lahir'],
						"d_tanggal_lahir"=>$d_tanggal_lahir,
						"d_tanggal_nikah"=>$d_tanggal_nikah,
						"i_karis"=>$_POST['i_karis'],
						"e_keterangan"=>$_POST['e_keterangan'],
						"c_tunjangan"=>$_POST['c_tunjangan'],
						"i_entry"=>$this->view->userid,
						"d_entry"=>date('Ymd'));		

	if ($_POST['proses']=='Simpan')
	{
		$hasil = $this->pasangan_serv->maintainData($MaintainData,'insert');		
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
	}		
	else
	{
		$hasil = $this->pasangan_serv->maintainData($MaintainData,'update');
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";		
	}

if ($hasil=='sukses'){
	$hasil = $this->pasangan_serv->updateNipPasangan($MaintainData);
}

	

	//$this->listDataByKey($this->view->nip,$_POST['c_pasangan'],$_POST['q_pasangan']);
	//$this->view->pekerjaanList = $this->reff_serv->getPekerjaan(''); 
}
else{
	$hasil="gagal format tanggal salah....";
	if ($_POST['proses']=='Simpan')
	{
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
	}		
	else
	{
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";		
	}
		//$this->listDataByKey($this->view->nip,$_POST['c_pasangan'],$_POST['q_pasangan']);
}	
	$nip=$_POST['i_peg_nip'];
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->pasanganList = $this->pasangan_serv->getPasanganList($cari);
	
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->render('listpasangan');	
}

public function hapusdataAction() {
	$MaintainData = array("id"=>$_GET['id']);		
	$hasil = $this->pasangan_serv->maintainData($MaintainData,'delete');	
	$pesan= "Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->listpasanganAction();
	$this->render('listpasangan');	
}
	
public function listDataByKey($id) { 
	$carilist = " and id='$id'";
	$datapasangan=$this->pasangan_serv->getPasanganList($carilist);	
	$this->view->i_peg_nip=$datapasangan[0]['i_peg_nip'];
	$this->view->c_pasangan=trim($datapasangan[0]['c_pasangan']);
	$this->view->q_pasangan=$datapasangan[0]['q_pasangan'];
	$this->view->c_pekerjaan=$datapasangan[0]['c_pekerjaan'];
	$this->view->c_jns_pekerjaan=$datapasangan[0]['c_jns_pekerjaan'];	
	$this->view->i_nip_pasangan=$datapasangan[0]['i_nip_pasangan'];
	$this->view->n_nama=$datapasangan[0]['n_nama'];
	$this->view->a_tempat_lahir=$datapasangan[0]['a_tempat_lahir'];
	$this->view->d_tanggal_lahir=$datapasangan[0]['d_tanggal_lahir'];
	$this->view->d_tanggal_nikah=$datapasangan[0]['d_tanggal_nikah'];
	$this->view->i_karis=$datapasangan[0]['i_karis'];
	$this->view->e_keterangan=$datapasangan[0]['e_keterangan'];
	$this->view->c_tunjangan=$datapasangan[0]['c_tunjangan'];	
	$this->view->i_entry=$datapasangan[0]['i_entry'];
	$this->view->d_entry=$datapasangan[0]['d_entry'];
}	

    public function listpegawaiAction() { 	
	$cPasangan=trim($_GET['cPasangan']);
	if ($cPasangan=='I'){$cari=" and c_peg_jeniskelamin='P'";}
	else {$cari=" and c_peg_jeniskelamin='L'";}
	$this->view->cPasangan = $_GET['cPasangan'];
	$nCol=strtoupper($_GET['nCol']);
	$cCol=$_GET['cCol'];
	$this->view->nCol = $_GET['nCol'];
	$this->view->cCol = $_GET['cCol'];	

	if ($nCol && $cCol ){$cari .= " and upper($cCol) like '%$nCol%' ";}
	if (!$cCol ){$cari .= "";}	
		
		$currentPage=$_GET['currentPage'];
		if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
			$numToDisplay = 10;
			$this->view->numToDisplay = $numToDisplay;
			$this->view->currentPage = $currentPage;
			
			$this->view->totalpegawaiList = $this->pegawai_serv->getPegawaiList($cari, 0, 0 ,$orderBy);		
			$this->view->PegawaiList = $this->pegawai_serv->getPegawaiList($cari, $currentPage, $numToDisplay,$orderBy );		
}
    public function pnsmaAction() { 	
}
    public function pnsnonmaAction() { 	
    $this->view->par=$_GET['par'];
}
	
}
?>