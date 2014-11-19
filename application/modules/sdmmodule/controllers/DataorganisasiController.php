<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_Organisasi_Service.php";

class Sdmmodule_DataOrganisasiController extends Zend_Controller_Action {
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu'); 
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->organisasi_serv = Sdm_organisasi_service::getInstance();
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
public function organisasijsAction() 
{
	header('content-type : text/javascript');
	$this->render('organisasijs');
}	
	
public function listorganisasiAction() {
	$nip=$this->view->nip;	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->organisasiList = $this->organisasi_serv->getOrganisasiList($cari);	
}
public function organisasiAction() {
	$par=$_GET['par'];	
	if ($par=='insert'){
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
	}
	else{
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";	
		$par=$_GET['par'];
		$this->view->id  = $_GET['id'];	
		$id=$_GET['id'];
		$this->listDataByKey($id);
		if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Merubah ";}		
		else{$this->view->jdl="Melihat ";$this->render('organisasiview');}			
	}
}
public function maintaindataAction() {

	$MaintainData = array("id"=>$_POST['id'],
						"i_peg_nip"=>$_POST['i_peg_nip'],
						"n_jenis_organisasi"=>$_POST['n_jenis_organisasi'],
						"n_organisasi"=>strtoupper($_POST['n_organisasi']),
						"n_jenis_organisasi2"=>$_POST['n_jenis_organisasi2'],
						"n_organisasi2"=>strtoupper($_POST['n_organisasi2']),
						"d_daftar_organisasi"=>$_POST['d_daftar_organisasi'],
						"d_daftar_organisasi2"=>$_POST['d_daftar_organisasi2'],
						"n_peran_organisasi"=>strtoupper($_POST['n_peran_organisasi']),
						"n_tempat_organisasi"=>strtoupper($_POST['n_tempat_organisasi']),
						"e_keterangan"=>$_POST['e_keterangan'],
						"i_entry"=>$this->view->userid,
						"d_entry"=>date('Ymd'));
	if ($_POST['proses']=='Simpan')
	{
		$hasil = $this->organisasi_serv->maintainData($MaintainData,'insert');		
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
	}		
	else
	{
		$hasil = $this->organisasi_serv->maintainData($MaintainData,'update');
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";		
	}	

	//$this->listDataByKey($this->view->nip,$_POST['n_jenis_organisasi'],strtoupper($_POST['n_organisasi']),$_POST['d_daftar_organisasi']);

	$nip=$_POST['i_peg_nip'];	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->organisasiList = $this->organisasi_serv->getOrganisasiList($cari);	
	
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	
	$this->render('listorganisasi');	
}

public function hapusdataAction() {
	$id = $_GET['id'];
	$MaintainData = array("id"=>$id);		
	$hasil = $this->organisasi_serv->maintainData($MaintainData,'delete');	
	$pesan= "Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->listorganisasiAction();
	$this->render('listorganisasi');	
}
	
public function listDataByKey($id) {  
	$carilist = " and id='$id'";
	$dataorganisasi=$this->organisasi_serv->getOrganisasiList($carilist);	

	$this->view->i_peg_nip=$dataorganisasi[0]['i_peg_nip'];
	$this->view->n_jenis_organisasi=$dataorganisasi[0]['n_jenis_organisasi'];
	$this->view->n_organisasi=$dataorganisasi[0]['n_organisasi'];
	$this->view->d_daftar_organisasi=$dataorganisasi[0]['d_daftar_organisasi'];
	$this->view->n_peran_organisasi=trim($dataorganisasi[0]['n_peran_organisasi']);
	$this->view->n_tempat_organisasi=$dataorganisasi[0]['n_tempat_organisasi'];
	$this->view->e_keterangan=$dataorganisasi[0]['e_keterangan'];
	$this->view->i_entry=$dataorganisasi[0]['i_entry'];
	$this->view->d_entry=$dataorganisasi[0]['d_entry'];
}	
	
}
?>