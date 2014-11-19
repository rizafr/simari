<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_Kerabat_Service.php";
require_once "service/sdm/sdm_refferensi_Service.php";
class Sdmmodule_DatakerabatController extends Zend_Controller_Action {
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu'); 
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->kerabat_serv = Sdm_Kerabat_Service::getInstance();
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
		$this->view->c_izin=$ssologin->c_izin[0]['c_izin'];		
    }
	
    public function indexAction() {
	   
    }
public function kerabatjsAction() 
{
	header('content-type : text/javascript');
	$this->render('kerabatjs');
}	
	
public function listkerabatAction() {
	$nip=$this->view->nip;	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->kerabatList = $this->kerabat_serv->getKerabatList($cari);	
}
public function kerabatAction() {
	$par=$_GET['par'];	
	if ($par=='insert'){
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
	}
	else{
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";	
		$par=$_GET['par'];
		$this->listDataByKey($this->view->nip,$_GET['c_kerabat'],$_GET['n_nama']);
		
		if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Merubah ";}
		else{$this->view->jdl="Melihat ";$this->render('kerabatview');}			
	} 
	
}
public function maintaindataAction() {

 		if ($_POST['d_tanggal_lahir'])
		{
			$d_tanggal_lahir1=substr($_POST['d_tanggal_lahir'],0,2);
			$d_tanggal_lahir2=substr($_POST['d_tanggal_lahir'],3,2);
			$d_tanggal_lahir3=substr($_POST['d_tanggal_lahir'],6,4);
		}
		$d_tanggal_lahir=$d_tanggal_lahir3."-".$d_tanggal_lahir2."-".$d_tanggal_lahir1;
		if (!$_POST['d_tanggal_lahir']){$d_tanggal_lahir=null;$cektglmulai=true; }
		else{$cektglmulai=checkdate($d_tanggal_lahir2,$d_tanggal_lahir1,$d_tanggal_lahir3);}
		$i_peg_nip=$_POST['i_peg_nip'];
		$c_kerabat=trim($_POST['c_kerabat']);
		$checkdata=$this->kerabat_serv->getkerabatList(" and i_peg_nip='$i_peg_nip' and c_kerabat='$c_kerabat'");
		
if (($cektglmulai==true) )
{	
	$MaintainData = array(
						"i_peg_nip"=>$_POST['i_peg_nip'],
						"c_kerabat"=>$_POST['c_kerabat'],
						"n_nama"=>$_POST['n_nama'],
						"c_kerabat2"=>$_POST['c_kerabat2'],
						"n_nama2"=>$_POST['n_nama2'],
						"c_jns_kel"=>$_POST['c_jns_kel'],
						"a_tempat_lahir"=>$_POST['a_tempat_lahir'],
						"d_tanggal_lahir"=>$d_tanggal_lahir,
						"n_pekerjaan"=>$_POST['n_pekerjaan'],
						"e_keterangan"=>$_POST['e_keterangan'],
						"i_entry"=>$this->view->userid,
						"d_entry"=>date('Ymd'));		
									
	if ($_POST['proses']=='Simpan')
	{
		$checkdata=$checkdata[0]['i_peg_nip'];
		
		if (!$checkdata)	
		{
			$hasil = $this->kerabat_serv->maintainData($MaintainData,'insert');		
			$this->view->par="Simpan";
			$this->view->jdl="Menambah ";
			$par="Menambah";
}
		else
		{
			$this->view->i_peg_nip=$_POST['i_peg_nip'];
			$this->view->c_kerabat=$_POST['c_kerabat'];
			$this->view->n_nama=$_POST['n_nama'];
			$this->view->c_jns_kel=$_POST['c_jns_kel'];
			$this->view->a_tempat_lahir=$_POST['a_tempat_lahir'];
			$this->view->d_tanggal_lahir=$_POST['d_tanggal_lahir'];
			$this->view->n_pekerjaan=$_POST['n_pekerjaan'];
			$this->view->e_keterangan=$_POST['e_keterangan'];
	
			$hasil="gagal";
			$this->view->par="Simpan";
			$this->view->jdl="Menambah ";
			$par="Menambah";		
		
		}			
	}		
	else
	{
		$hasil = $this->kerabat_serv->maintainData($MaintainData,'update');
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";		
	}	

	//$this->listDataByKey($this->view->nip,$_POST['c_kerabat'],$_POST['n_nama']);
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
		$this->listDataByKey($this->view->nip,$_POST['c_kerabat'],$_POST['n_nama']);
}	
	$nip=$_POST['i_peg_nip'];	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->kerabatList = $this->kerabat_serv->getKerabatList($cari);	
	
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	

	
	if ($hasil=='gagal'){
		$pesan=$par." data ".$hasil." Status sudah ada";
		$this->view->pesan = $pesan;
		$this->view->pesancek = $hasil;	
		$this->render('kerabat');
	}else{
	$this->render('listkerabat');}
	
}

public function hapusdataAction() {
	$MaintainData = array("i_peg_nip"=>($this->view->nip),"c_kerabat"=>$_GET['c_kerabat'],"n_nama"=>$_GET['n_nama']);		
	$hasil = $this->kerabat_serv->maintainData($MaintainData,'delete');	
	$pesan= "Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->listkerabatAction();
	$this->render('listkerabat');	
}
	
public function listDataByKey($nip,$c_kerabat,$n_nama) { 
	$carilist = " and i_peg_nip='$nip' and c_kerabat='$c_kerabat' and n_nama='$n_nama' ";
	$datakerabat=$this->kerabat_serv->getkerabatList($carilist);	
	$this->view->i_peg_nip=$datakerabat[0]['i_peg_nip'];
	$this->view->c_kerabat=$datakerabat[0]['c_kerabat'];
	$this->view->n_nama=$datakerabat[0]['n_nama'];
	$this->view->c_jns_kel=$datakerabat[0]['c_jns_kel'];
	$this->view->a_tempat_lahir=$datakerabat[0]['a_tempat_lahir'];
	$this->view->d_tanggal_lahir=$datakerabat[0]['d_tanggal_lahir'];
	$this->view->n_pekerjaan=$datakerabat[0]['n_pekerjaan'];
	$this->view->e_keterangan=$datakerabat[0]['e_keterangan'];
	$this->view->i_entry=$datakerabat[0]['i_entry'];
	$this->view->d_entry=$datakerabat[0]['d_entry'];

}	
	
}
?>