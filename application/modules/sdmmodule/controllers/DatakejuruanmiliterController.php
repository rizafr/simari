<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_KejuruanMil_Service.php";
require_once "service/sdm/sdm_refferensi_Service.php";
class Sdmmodule_DatakejuruanmiliterController extends Zend_Controller_Action {
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu'); 
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->dikmil_serv = Sdm_KejuruanMil_Service::getInstance();
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
public function kejuruanmiliterjsAction() 
{
	header('content-type : text/javascript');
	$this->render('kejuruanmiliterjs');
}	
	
public function listkejuruanmiliterAction() {
	$nip=$this->view->nip;	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->dikmilList = $this->dikmil_serv->getKejuruanList($cari);
}

public function kejuruanmiliterAction() {
	$par=$_GET['par'];
	$this->view->cmbkelompok=$this->reff_serv->getTrJurMiliter('');
	$this->view->cmbkejuruan=$this->reff_serv->getTrKejuruanMiliter(''); 	
	if ($par=='insert'){
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
	}
	else{	
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$id=$_GET['id'];
		$this->view->id=$id;
		$this->listDataByKey($id);

		if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Merubah ";}		
		else{$this->view->jdl="Melihat ";$this->render('kejuruanmiliterview');}			
	}			

	
}

public function maintaindataAction() {
					
	$MaintainData = array("id"=>$_POST['id'],
				"i_peg_nip"=>$_POST['i_peg_nip'],
				"c_jenis_kel"=>$_POST['c_jenis_kel']*1,
				"c_jenis_kel2"=>$_POST['c_jenis_kel2'],
				"c_kejuruanmil"=>$_POST['c_kejuruanmil']*1,
				"c_kejuruanmil2"=>$_POST['c_kejuruanmil2'],
				"d_tahun_masuk"=>$_POST['d_tahun_masuk']*1,
				"d_tahun_masuk2"=>$_POST['d_tahun_masuk2'],
				"d_tahun_lulus"=>$_POST['d_tahun_lulus']*1,
				"c_status"=>$_POST['c_status'],
				"n_tempat"=>$_POST['n_tempat'],		
				"i_entry"=>$this->view->userid,
				"d_entry"=>date('Ymd'));	
												

	if ($_POST['proses']=='Simpan')
	{
		$hasil = $this->dikmil_serv->maintainData($MaintainData,'insert');		
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
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
	$this->view->dikmilList = $this->dikmil_serv->getKejuruanList($cari);
	
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;		
	$this->render('listkejuruanmiliter');	
	
}
public function hapusdataAction() {
	$id  = $_GET['id'];
	$MaintainData = array("id"=>$id);		
	$hasil = $this->dikmil_serv->maintainData($MaintainData,'delete');	
	$pesan= "Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->listkejuruanmiliterAction();
	$this->render('listkejuruanmiliter');	
}
public function listDataByKey($id) {
	//$cari = " and i_peg_nip ='$nip' and c_jenis_kel=$c_jenis_kel and c_kejuruanmil=$c_kejuruanmil and d_tahun_masuk=$d_tahun_masuk";
	$cari = " and id ='$id'";
	$datalatih = $this->dikmil_serv->getKejuruanList($cari);		
	$this->view->c_jenis_kel=$datalatih [0]['c_jenis_kel'];
	$this->view->c_kejuruanmil=$datalatih [0]['c_kejuruanmil'];
	$this->view->d_tahun_masuk=$datalatih [0]['d_tahun_masuk'];
	$this->view->d_tahun_lulus=$datalatih [0]['d_tahun_lulus'];
	$this->view->c_status=$datalatih [0]['c_status'];
	$this->view->n_tempat=$datalatih [0]['n_tempat'];

    }

}
?>