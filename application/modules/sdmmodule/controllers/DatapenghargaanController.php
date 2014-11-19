<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_Penghargaan_Service.php";
require_once "service/sdm/sdm_refferensi_Service.php";
class Sdmmodule_DataPenghargaanController extends Zend_Controller_Action {
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu'); 		
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->penghargaan_serv = Sdm_Penghargaan_Service::getInstance();
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
public function penghargaanjsAction() 
{
	header('content-type : text/javascript');
	$this->render('penghargaanjs');
}	
	
public function listpenghargaanAction() {
	$nip=$this->view->nip;	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->penghargaanList = $this->penghargaan_serv->getPenghargaanList($cari);		
}
public function penghargaanAction() {
	$this->view->negaraList = $this->reff_serv->getNegara(''); 
	$this->view->jnsPenghargaanList = $this->reff_serv->getPenghargaan(''); 
	$par=$_GET['par'];	
	if ($par=='insert'){
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
	}
	else{
		$this->view->par = "Ubah";
		$this->view->jdl = "Merubah ";	
		$id=$_GET['id'];
		$par=$_GET['par'];
		$this->view->id = $id;
		$this->listDataByKey($id);
		if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Merubah ";}		
		else{$this->view->jdl="Melihat ";$this->render('penghargaanview');}			
	}
	
	
}
public function maintaindataAction() {

	$MaintainData = array("id"=>$_POST['id'],
						"i_peg_nip"=>$_POST['i_peg_nip'],
						"n_jns_penghargaan"=>$_POST['n_jns_penghargaan'],
						"n_jns_penghargaan2"=>$_POST['n_jns_penghargaan2'],
						"n_penghargaan"=>$_POST['n_penghargaan'],
						"n_penghargaan2"=>$_POST['n_penghargaan2'],
						"d_tahun_alteratif"=>$_POST['d_tahun_alteratif'],
						"c_negara_alternatif"=>$_POST['c_negara_alternatif'],
						"n_lembaga_alternatif"=>$_POST['n_lembaga_alternatif'],
						"e_keterangan"=>$_POST['e_keterangan'],
						"i_entry"=>$this->view->userid,
						"d_entry"=>date('Ymd'));		

	if ($_POST['proses']=='Simpan')
	{
		$hasil = $this->penghargaan_serv->maintainData($MaintainData,'insert');		
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
	}		
	else
	{
		$MaintainData = array("id"=>$_POST['id'],
						"i_peg_nip"=>$_POST['i_peg_nip'],
						"n_jns_penghargaan"=>$_POST['n_jns_penghargaan'],
						"n_jns_penghargaan2"=>$_POST['n_jns_penghargaan2'],
						"n_penghargaan"=>$_POST['n_penghargaan'],
						"n_penghargaan2"=>$_POST['n_penghargaan2'],
						"d_tahun_alteratif"=>$_POST['d_tahun_alteratif'],
						"c_negara_alternatif"=>$_POST['c_negara_alternatif'],
						"n_lembaga_alternatif"=>$_POST['n_lembaga_alternatif'],
						"e_keterangan"=>$_POST['e_keterangan'],
						"i_entry"=>$this->view->userid,
						"d_entry"=>date('Ymd'));
		$hasil = $this->penghargaan_serv->maintainData($MaintainData,'update');
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";		
	}	

	//$this->listDataByKey($this->view->nip,$_POST['n_jns_penghargaan'],$_POST['n_penghargaan']);

	$this->view->negaraList = $this->reff_serv->getNegara(''); 
	$this->view->jnsPenghargaanList = $this->reff_serv->getPenghargaan(''); 
	$this->view->tandaJasaList = $this->reff_serv->getTandaJasa('');

	$nip=$_POST['i_peg_nip'];	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->penghargaanList = $this->penghargaan_serv->getPenghargaanList($cari);	
	
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->render('listpenghargaan');	
}

public function hapusdataAction() {
	//$MaintainData = array("i_peg_nip"=>($this->view->nip),"n_penghargaan"=>$_GET['nPenghargaan'],"n_jns_penghargaan"=>$_GET['nJnsPenghargaan']);		
	$MaintainData = array("id"=>$_GET['id']);		
	$hasil = $this->penghargaan_serv->maintainData($MaintainData,'delete');	
	$pesan= "Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->listpenghargaanAction();
	$this->render('listpenghargaan');	
}
	
public function listDataByKey($id) {  
	//$carilist = " and i_peg_nip='$nip' and n_jns_penghargaan='$nJenisTtd' and n_penghargaan='$nPenghargaan' ";
	//$carilist = " and id=$id ";
	$cari = " and id=$id ";
	$datapenghargaan=$this->penghargaan_serv->getPenghargaanList($cari);
	$this->view->i_peg_nip=$datapenghargaan[0]['i_peg_nip'];
	$this->view->n_penghargaan=trim($datapenghargaan[0]['n_penghargaan']);
	$this->view->n_jns_penghargaan=trim($datapenghargaan[0]['n_jns_penghargaan']);
	$this->view->d_tahun_alteratif=$datapenghargaan[0]['d_tahun_alteratif'];
	$this->view->c_negara_alternatif=trim($datapenghargaan[0]['c_negara_alternatif']);
	$this->view->n_lembaga_alternatif=$datapenghargaan[0]['n_lembaga_alternatif'];
	$this->view->e_keterangan=$datapenghargaan[0]['e_keterangan'];
	$this->view->i_entry=$datapenghargaan[0]['i_entry'];
	$this->view->d_entry=$datapenghargaan[0]['d_entry'];
	$n_jns_penghargaan = "and e_jnama='".trim($datapenghargaan[0]['n_jns_penghargaan'])."'";
	$this->view->tandaJasaList = $this->reff_serv->getTandaJasa($n_jns_penghargaan); 
	
}	
 public function getlistnamajasaAction() {
        $c_tandajasa = $_REQUEST['c_tandajasa'];
		$sqlj = " and 	e_jnama='$c_tandajasa'";
        $this->_helper->viewRenderer->setNoRender(true);
        echo Zend_Json::encode($this->reff_serv->getTandaJasa($sqlj));
    }	
}
?>