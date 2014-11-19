<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_Lhkpn_Service.php";


class Sdmmodule_LhkpnController extends Zend_Controller_Action {
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu'); 		
		$this->reff_serv = sdm_refferensi_Service::getInstance();
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->lhkpn_serv = Sdm_Lhkpn_Service::getInstance();
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
public function lhkpnlistAction() 
    {
		$nip=$this->view->nip;	
		if (!$nip){$nip=$_GET['nip'];}
		$cari = " and i_peg_nip ='$nip' ";
		$this->view->lhkpnList = $this->lhkpn_serv->getLhkpnList($cari);		

    }	
public function lhkpnAction() 
    {
	
	$par=$_GET['par'];
	$nip=$_GET['nip'];
	$i_nomor_lhkpn=$_GET['i_nomor_lhkpn'];
	$d_tahun_lapor=$_GET['d_tahun_lapor'];
	
	if ($par=='insert'){
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
	}
	else if ($par=='delete'){
		$this->view->par="Hapus";
		$this->view->jdl="Manghapus ";	
		//$this->listDataByKey($this->view->nip,$i_nomor_lhkpn,$d_tahun_lapor);
	}	
	else{
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";	
		$id=$_GET['id'];
		$this->view->id=$id;
		$this->listDataByKey($id);
		if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Merubah ";}		
		else{$this->view->jdl="Melihat ";$this->render('lhkpnview');}			
	}
	
    }	
public function lhkpnjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('lhkpnjs');
    }

public function maintaindataAction() {

		if ($_POST['d_lhkpn'])
		{
			$d_lhkpn1=substr($_POST['d_lhkpn'],0,2);
			$d_lhkpn2=substr($_POST['d_lhkpn'],3,2);
			$d_lhkpn3=substr($_POST['d_lhkpn'],6,4);
		}
		$d_lhkpn=$d_lhkpn3."-".$d_lhkpn2."-".$d_lhkpn1;
		if (!$d_lhkpn1){$d_lhkpn=null;}
		else{$cektgl=checkdate($d_lhkpn2,$d_lhkpn1,$d_lhkpn3);}
		
		if ($_POST['c_formulira']){$c_formulira=1;}
		else{$c_formulira=0;}
		if ($_POST['c_formulirb']){$c_formulirb=1;}
		else{$c_formulirb=0;}
if ($cektgl==true)
{
	$MaintainData = array("id"=>$_POST['id'],
		"i_peg_nip"=>$_POST['i_peg_nip'],
		"d_tahun_lapor"=>$_POST['d_tahun_lapor'],
		"i_nomor_lhkpn"=>$_POST['i_nomor_lhkpn'],
		"i_nomor_lembaran"=>$_POST['i_nomor_lembaran'],		
		"c_formulira"=>$c_formulira,
		"c_formulirb"=>$c_formulirb,
		"d_lhkpn"=>$d_lhkpn,
		"i_entry"=>$this->view->userid,
		"d_entry"=>date('Ymd'));

	if ($_POST['proses']=='Simpan')
	{
		$i_peg_nip=$_POST['i_peg_nip'];
		$d_tahun_lapor=$_POST['d_tahun_lapor'];
		$i_nomor_lhkpn=$_POST['i_nomor_lhkpn'];
		$carilist = " and i_peg_nip='$i_peg_nip' and i_nomor_lhkpn='$i_nomor_lhkpn' and d_tahun_lapor='$d_tahun_lapor'  ";
		$checkdata=$this->lhkpn_serv->getLhkpnList($carilist); 
		$checkdata=$checkdata[0]['i_peg_nip'];
		
		if (!$checkdata)	
		{		
			$hasil = $this->lhkpn_serv->maintainData($MaintainData,'insert');		
			$this->view->par="Simpan";
			$this->view->jdl="Menambah ";
			$par="Menambah";
		}
		else{
			$this->view->i_peg_nip=$_POST['i_peg_nip'];
			$this->view->d_tahun_lapor=$_POST['d_tahun_lapor'];
			$this->view->i_nomor_lhkpn=$_POST['i_nomor_lhkpn'];
			$this->view->c_formulira=$_POST['c_formulira'];
			$this->view->c_formulirb=$_POST['c_formulirb'];
			$this->view->d_lhkpn=$_POST['d_lhkpn'];	
			$this->view->i_nomor_lembaran=$_POST['i_nomor_lembaran'];	
	
			$hasil="gagal";
			$this->view->par="Simpan";
			$this->view->jdl="Menambah ";
			$par="Menambah";
		}		
	}
	else if ($_POST['proses']=='Hapus')
	{
		$hasil = $this->lhkpn_serv->maintainData($MaintainData,'delete');		
		$this->view->par="Hapus";
		$this->view->jdl="Menghapus ";
		$par="Menghapus";
	}	
	else
	{
		$hasil = $this->lhkpn_serv->maintainData($MaintainData,'update');
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";		
	}

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
		
}	
	
	$nip=$_POST['i_peg_nip'];	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->lhkpnList = $this->lhkpn_serv->getLhkpnList($cari);
	
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
//	$this->render('lhkpnlist');


	if ($hasil=='gagal'){
		$pesan=$par." data ".$hasil." Data sudah ada";
		$this->view->pesan = $pesan;
		$this->view->pesancek = $hasil;	
		$this->render('lhkpn');
	}else{
	$this->render('lhkpnlist');}
	
}

public function hapusdataAction() 
{
	$i_user=$this->i_user;
	$id=$_GET['id'];
	$maintainData= array("id"=>$id);	
	$hasil = $this->lhkpn_serv->maintainData($maintainData,'delete');				
	$pesan="Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;
	$this->lhkpnlistAction();
	$this->render('lhkpnlist');
	//$this->_helper->viewRenderer('lhkpnlist');
}


public function listDataByKey($id) {  
	if($id){
		$carilist = " and id='$id'  ";
		$datalhkpn=$this->lhkpn_serv->getLhkpnList($carilist); 
		$this->view->d_tahun_lapor=$datalhkpn [0]['d_tahun_lapor'];
		$this->view->i_nomor_lhkpn=$datalhkpn [0]['i_nomor_lhkpn'];
		$this->view->c_formulira=$datalhkpn [0]['c_formulira'];
		$this->view->c_formulirb=$datalhkpn [0]['c_formulirb'];
		$this->view->d_lhkpn=$datalhkpn [0]['d_lhkpn'];	
		$this->view->i_nomor_lembaran=$datalhkpn [0]['i_nomor_lembaran'];	
	
    }	
}	
	
}
?>