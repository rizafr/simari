<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_Tpa_Service.php";
require_once "service/sdm/sdm_refferensi_Service.php";
class Sdmmodule_DatatpaController extends Zend_Controller_Action {
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu'); 
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->tpa_serv = Sdm_Tpa_Service::getInstance();
		$this->reff_serv = sdm_refferensi_Service::getInstance();
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
public function tpajsAction() 
{
	header('content-type : text/javascript');
	$this->render('tpajs');
}	
	
public function listtpaAction() {
	$nip=$this->view->nip;	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->tpaList = $this->tpa_serv->getTpaList($cari);	
}
public function tpaAction() {

	$par=$_GET['par'];	
	if ($par=='insert'){
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
	}
	else{
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";	
		$par=$_GET['par'];
		$id=$_GET['id'];
		$this->view->id=$id;
		$this->listDataByKey($id);

		if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Merubah ";}		
		else{$this->view->jdl="Melihat ";$this->render('tpaview');}			
	} 
	
	
}
public function maintaindataAction() {
 		if ($_POST['d_test_akademik'])
		{
			$d_test_akademik1=substr($_POST['d_test_akademik'],0,2);
			$d_test_akademik2=substr($_POST['d_test_akademik'],3,2);
			$d_test_akademik3=substr($_POST['d_test_akademik'],6,4);
		}
		$d_test_akademik=$d_test_akademik3."-".$d_test_akademik2."-".$d_test_akademik1;
		if (!$_POST['d_test_akademik']){$d_test_akademik=null;$cektgltest=true;}
		else{$cektgltest=checkdate($d_test_akademik2,$d_test_akademik1,$d_test_akademik3);}

 		if ($_POST['d_tmt_berlaku'])
		{
			$d_tmt_berlaku1=substr($_POST['d_tmt_berlaku'],0,2);
			$d_tmt_berlaku2=substr($_POST['d_tmt_berlaku'],3,2);
			$d_tmt_berlaku3=substr($_POST['d_tmt_berlaku'],6,4);
		}
		$d_tmt_berlaku=$d_tmt_berlaku3."-".$d_tmt_berlaku2."-".$d_tmt_berlaku1;
		if (!$_POST['d_tmt_berlaku']){$d_tmt_berlaku=null;$cektglberlaku=true;}
		else{$cektglberlaku=checkdate($d_tmt_berlaku2,$d_tmt_berlaku1,$d_tmt_berlaku3);}
		

 		if ($_POST['d_test_akademik2'])
		{
			$d_test_akademik21=substr($_POST['d_test_akademik2'],0,2);
			$d_test_akademik22=substr($_POST['d_test_akademik2'],3,2);
			$d_test_akademik23=substr($_POST['d_test_akademik2'],6,4);
		}
		$d_test_akademik2=$d_test_akademik23."-".$d_test_akademik22."-".$d_test_akademik21;		
		
if (($cektgltest==true) )
{	


	$MaintainData = array("id"=>$_POST['id'],
				"i_peg_nip"=>$_POST['i_peg_nip'],
				"q_nilai_akademik"=>$_POST['q_nilai_akademik']*1,
				"n_penyelenggara_akademik"=>$_POST['n_penyelenggara_akademik'],
				"e_tujuan_akademik"=>$_POST['e_tujuan_akademik'],
				"d_test_akademik"=>$d_test_akademik,
				"d_test_akademik2"=>$d_test_akademik2,
				"d_tmt_berlaku"=>$d_tmt_berlaku,
				"i_entry"=>$this->view->userid,
				"d_entry"=>date('Ymd'));		

	if ($_POST['proses']=='Simpan')
	{
		$hasil = $this->tpa_serv->maintainData($MaintainData,'insert');		
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
	}		
	else
	{
		$hasil = $this->tpa_serv->maintainData($MaintainData,'update');
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";		
	}	
	$this->view->id = $_POST['id'];
	$this->listDataByKey($_POST['id']);
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
		$this->view->id = $_POST['id'];
	$this->listDataByKey($_POST['id']);
}	
	
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	
	$nip=$this->view->nip;	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->tpaList = $this->tpa_serv->getTpaList($cari);		
	$this->render('listtpa');	
}

public function hapusdataAction() {
 		
	$MaintainData = array("id"=>$_GET['id']);		
	$hasil = $this->tpa_serv->maintainData($MaintainData,'delete');	
	$pesan= "Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->listtpaAction();
	$nip=$this->view->nip;	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->tpaList = $this->tpa_serv->getTpaList($cari);		
	$this->render('listtpa');	
}
	
public function listDataByKey($id) { 
	if($id){
		$carilist = " and id='$id' ";
		$datatpa=$this->tpa_serv->getTpaList($carilist);	
		
		$this->view->i_peg_nip=$datatpa[0]['i_peg_nip'];
		$this->view->d_test_akademik=$datatpa[0]['d_test_akademik'];
		$this->view->q_nilai_akademik=$datatpa[0]['q_nilai_akademik'];
		$this->view->n_penyelenggara_akademik=$datatpa[0]['n_penyelenggara_akademik'];
		$this->view->e_tujuan_akademik=$datatpa[0]['e_tujuan_akademik'];
		$this->view->d_tmt_berlaku=$datatpa[0]['d_tmt_berlaku'];
		$this->view->i_entry=$datatpa[0]['i_entry'];
		$this->view->d_entry=$datatpa[0]['d_entry'];
	}
}	
	
}
?>