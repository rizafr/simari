<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_PendidikanMil_Service.php";
require_once "service/sdm/sdm_refferensi_Service.php";
class Sdmmodule_DatapendidikanmiliterController extends Zend_Controller_Action {
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu'); 
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->dikmil_serv = Sdm_PendidikanMil_Service::getInstance();
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
public function pendidikanmiliterjsAction() 
{
	header('content-type : text/javascript');
	$this->render('pendidikanmiliterjs');
}	
	
public function listpendidikanmiliterAction() {
	$nip=$this->view->nip;	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->dikmilList = $this->dikmil_serv->getPendidikanList($cari);
}

public function pendidikanmiliterAction() {
	$par=$_GET['par'];
	$this->view->cmbjenjang=$this->reff_serv->getTrJenjangDikmil('');
	$this->view->cmbsekolah=$this->reff_serv->getTrSekolahMiliter(''); 	
	if ($par=='insert'){
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
	}
	else if ($par=='delete'){	
		$this->view->par="Hapus";
		$this->view->jdl="Menghapus ";
		$id=$_GET['id'];
		$this->view->id=$id;
		$this->listDataByKey($id);	

		if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Merubah ";}		
		else{$this->view->jdl="Melihat ";$this->render('pendidikanmiliterview');}			
	}
	else{	
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$id=$_GET['id'];
		$this->view->id=$id;
		$this->listDataByKey($id);	

		if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Merubah ";}		
		else{$this->view->jdl="Melihat ";$this->render('pendidikanmiliterview');}			
	}			

}

public function maintaindataAction() {
					
	$MaintainData = array("id"=>$_POST['id'],
				"i_peg_nip"=>$_POST['i_peg_nip'],
				"c_jenjang"=>$_POST['c_jenjang']*1,
				"c_jenjang2"=>$_POST['c_jenjang2'],
				"c_sekolahmil"=>$_POST['c_sekolahmil']*1,
				"c_sekolahmil2"=>$_POST['c_sekolahmil2'],
				"d_tahun_masuk"=>$_POST['d_tahun_masuk']*1,
				"d_tahun_masuk2"=>$_POST['d_tahun_masuk2'],
				"d_tahun_lulus"=>$_POST['d_tahun_lulus']*1,
				"c_status"=>$_POST['c_status'],
				"n_tempat"=>$_POST['n_tempat'],		
				"i_entry"=>$this->view->userid,
				"d_entry"=>date('Ymd'));	
	//print_r($MaintainData);exit;										

	if ($_POST['proses']=='Simpan')
	{
		$hasil = $this->dikmil_serv->maintainData($MaintainData,'insert');		
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
	}
	else if ($_POST['proses']=='Hapus')
	{
		$hasil = $this->dikmil_serv->maintainData($MaintainData,'delete');		
		$this->view->par="Hapus";
		$this->view->jdl="Menghapus ";
		$par="Menghapus";
	}	
	else
	{
		$hasil = $this->dikmil_serv->maintainData($MaintainData,'update');
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";		
	}	
	

	
	$nip=$_POST['i_peg_nip'];	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->dikmilList = $this->dikmil_serv->getPendidikanList($cari);
	
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;		
	$this->render('listpendidikanmiliter');	
	
}
public function hapusdataAction() {
	$id  = $_GET['id'];
	$MaintainData = array("id"=>$id);		
	$hasil = $this->dikmil_serv->maintainData($MaintainData,'delete');	
	$pesan= "Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->listpendidikanmiliterAction();
	$this->render('listpendidikanmiliter');	
}
public function listDataByKey($id) {
	$cari = " and id ='$id'";
	$datalatih = $this->dikmil_serv->getPendidikanList($cari);		
	$this->view->c_jenjang=$datalatih [0]['c_jenjang'];
	$this->view->c_sekolahmil=$datalatih [0]['c_sekolahmil'];
	$this->view->d_tahun_masuk=$datalatih [0]['d_tahun_masuk'];
	$this->view->d_tahun_lulus=$datalatih [0]['d_tahun_lulus'];
	$this->view->c_status=$datalatih [0]['c_status'];
	$this->view->n_tempat=$datalatih [0]['n_tempat'];

    }

}
?>