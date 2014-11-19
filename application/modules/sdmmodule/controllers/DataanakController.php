<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_Anak_Service.php";
require_once "service/sdm/sdm_refferensi_Service.php";
class Sdmmodule_DataanakController extends Zend_Controller_Action {
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu'); 
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->anak_serv = Sdm_Anak_Service::getInstance();
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
public function anakjsAction() 
{
	header('content-type : text/javascript');
	$this->render('anakjs');
}	
	
public function listanakAction() {
	$nip=$this->view->nip;	
	$cari = " and (i_peg_nip ='$nip' or i_peg_nip in (select i_nip_pasangan from sdm.tm_pegawai where i_peg_nip='$nip')) ";
	$this->view->anakList = $this->anak_serv->getanakList($cari);	
}
public function anakAction() {

	$par=$_GET['par'];	
	if ($par=='insert'){
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
	}
	else{
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";	
		$par=$_GET['par'];
		$this->view->id=$_GET['id'];
		$this->listDataByKey($this->view->nip,$_GET['id']);
		
		if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Merubah ";}
		else{$this->view->jdl="Melihat ";$this->render('anakview');}		
	}

	
	//$this->view->pekerjaanList = $this->reff_serv->getPekerjaanAnak(''); 
	
}
public function maintaindataAction() {

 		if ($_POST['d_tanggal_lahir'])
		{
			$d_tanggal_lahir1=substr($_POST['d_tanggal_lahir'],0,2);
			$d_tanggal_lahir2=substr($_POST['d_tanggal_lahir'],3,2);
			$d_tanggal_lahir3=substr($_POST['d_tanggal_lahir'],6,4);
			$d_tanggal_lahir=$d_tanggal_lahir3."-".$d_tanggal_lahir2."-".$d_tanggal_lahir1;
			if($d_tanggal_lahir2 != '' && $d_tanggal_lahir1 != '' && $d_tanggal_lahir3 != '') {
				$cektglmulai=checkdate($d_tanggal_lahir2,$d_tanggal_lahir1,$d_tanggal_lahir3);
			} else {
				$cektglmulai=true;
				$d_tanggal_lahir=null;
			}
		} else {
			$d_tanggal_lahir=null;
			$cektglmulai=true;
			//$d_tanggal_lahir3."-".$d_tanggal_lahir2."-".$d_tanggal_lahir1;
		
		}
		
		$i_peg_nip=$_POST['i_peg_nip'];
		$q_anak_ke=$_POST['q_anak_ke'];
		//$checkdata= $this->anak_serv->getAnakList(" and i_peg_nip='$i_peg_nip' and q_anak_ke='$q_anak_ke'");
if (($cektglmulai==true) )
{

	$MaintainData = array(
						"id"=>$_POST['id'],
						"i_peg_nip"=>$_POST['i_peg_nip'],
						"c_anak"=>$_POST['c_anak'],
						"n_nama"=>$_POST['n_nama'],
						"c_anak2"=>$_POST['c_anak2'],
						"q_anak_ke"=>$_POST['q_anak_ke'],
						"q_anak_ke2"=>$_POST['q_anak_ke2'],
						"n_nama2"=>stripslashes($_POST['n_nama2']),
						"c_jns_kel"=>$_POST['c_jns_kel'],
						"a_tempat_lahir"=>$_POST['a_tempat_lahir'],
						"d_tanggal_lahir"=>$d_tanggal_lahir,
						"d_tanggal_nikah"=>$d_tanggal_nikah,
						"c_tunjangan"=>$_POST['c_tunjangan'],
						"c_pekerjaan"=>$_POST['c_pekerjaan'],
						"e_keterangan"=>$_POST['e_keterangan'],
						"i_entry"=>$this->view->userid,
						"d_entry"=>date('Ymd'));		
		//print_r($MaintainData);
	if ($_POST['proses']=='Simpan')
	{
		//$checkdata='';//$checkdata[0]['i_peg_nip'];
		//if (!$checkdata)	
		//{
			$hasil = $this->anak_serv->maintainData($MaintainData,'insert');		
			$this->view->par="Simpan";
			$this->view->jdl="Menambah ";
			$par="Menambah";
			/*
		}
		else
		{
			$this->view->i_peg_nip=$_POST['i_peg_nip'];
			$this->view->c_anak=$_POST['c_anak'];
			$this->view->q_anak_ke=$_POST['q_anak_ke'];
			$this->view->n_nama=$_POST['n_nama'];
			$this->view->c_jns_kel=$_POST['c_jns_kel'];
			$this->view->a_tempat_lahir=$_POST['a_tempat_lahir'];
			$this->view->d_tanggal_lahir=$_POST['d_tanggal_lahir'];
			$this->view->c_tunjangan=$_POST['c_tunjangan'];
			$this->view->c_pekerjaan=$_POST['c_pekerjaan'];
			$this->view->e_keterangan=$_POST['e_keterangan'];
	
			$hasil="gagal";
			$this->view->par="Simpan";
			$this->view->jdl="Menambah ";
			$par="Menambah";		
		
		}*/
	}		
	else
	{
		$hasil = $this->anak_serv->maintainData($MaintainData,'update');
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";		
	}	

	//$this->listDataByKey($this->view->nip,$_POST['c_anak']);
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
		$this->listDataByKey($this->view->nip,$_POST['id']);
}	

	$nip=$_POST['i_peg_nip'];	
	//$cari = " and i_peg_nip ='$nip' ";
	$cari = " and (i_peg_nip ='$nip' or i_peg_nip in (select i_nip_pasangan from sdm.tm_pegawai where i_peg_nip='$nip')) ";
	$this->view->anakList = $this->anak_serv->getanakList($cari);	
	
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	if ($hasil=='gagal'){
		$q_anak_ke=$_POST['q_anak_ke'];
		$pesan=$par." data ".$hasil." anak ke $q_anak_ke sudah ada";
		$this->view->pesan = $pesan;
		$this->view->pesancek = $hasil;	
		$this->render('anak');
	}else{
	$this->render('listanak');}
}

public function hapusdataAction() {
	$MaintainData = array("i_peg_nip"=>($this->view->nip),"id"=>$_GET['id']);		
	$hasil = $this->anak_serv->maintainData($MaintainData,'delete');	
	$pesan= "Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->listanakAction();
	$this->render('listanak');	
}
	
public function listDataByKey($nip,$id) { 
	if($id){
	$carilist = " and id='$id'";
	$dataanak=$this->anak_serv->getanakList($carilist);	
	$this->view->i_peg_nip=$dataanak[0]['i_peg_nip'];
	$this->view->c_anak=$dataanak[0]['c_anak'];
	$this->view->q_anak_ke=$dataanak[0]['q_anak_ke'];
	$this->view->n_nama=$dataanak[0]['n_nama'];
	$this->view->c_jns_kel=$dataanak[0]['c_jns_kel'];
	$this->view->a_tempat_lahir=$dataanak[0]['a_tempat_lahir'];
	$this->view->d_tanggal_lahir=$dataanak[0]['d_tanggal_lahir'];
	$this->view->c_tunjangan=$dataanak[0]['c_tunjangan'];
	$this->view->c_pekerjaan=$dataanak[0]['c_pekerjaan'];
	$this->view->e_keterangan=$dataanak[0]['e_keterangan'];
	$this->view->i_entry=$dataanak[0]['i_entry'];
	$this->view->d_entry=$dataanak[0]['d_entry'];
	}
}	
	
}
?>