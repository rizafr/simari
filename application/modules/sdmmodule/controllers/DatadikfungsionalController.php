<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_DiklatFungsional_Service.php";
require_once "service/sdm/sdm_refferensi_Service.php";
class Sdmmodule_DatadikfungsionalController extends Zend_Controller_Action {
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu'); 
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->pelatihan_serv = Sdm_DiklatFungsional_Service::getInstance();
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
public function dikfungsionaljsAction() 
{
	header('content-type : text/javascript');
	$this->render('dikfungsionaljs');
}	
public function listpegawaiAction() 
	{
		$currentPage=$_GET['currentPage'];
		if((!$currentPage) || ($currentPage == 'undefined'))
			{$currentPage = 1;}
			$numToDisplay = 10;
			$this->view->numToDisplay = $numToDisplay;
			$this->view->currentPage = $currentPage;
			$this->view->totalpegawaiList = $this->pegawai_serv->getPegawaiList($cari, 0, 0 ,$orderBy);		
			$this->view->pegawaiList = $this->pegawai_serv->getPegawaiList($cari, $currentPage, $numToDisplay,$orderBy );	    	
	}	
	
public function listdiklatAction() {
	$nip=$this->view->nip;	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->pelatihanList = $this->pelatihan_serv->getPelatihanList($cari);	
		$this->listDataByKeyPeg($nip);	
}

public function fungsionalAction() {
	$par=$_GET['par'];
	$jenisLatih=$_GET['jenisLatih'];
	if ($jenisLatih=="P"){$this->render('penjenjangan');}
	elseif ($jenisLatih=="T"){$this->render('teknis');}
	elseif ($jenisLatih=="L"){$this->render('lainnya');}
	else{
		$this->view->lisjnslatih=$this->reff_serv->getJnsPelatihanFungsional('');		
		$this->view->liskellatih=$this->reff_serv->getKelPelatihanFungsional('');	
		$this->view->lisjenjanglatih=$this->reff_serv->getPenjenjanganFungsional('');
		$this->view->lisnamajenjanglatih=$this->reff_serv->getNamaPenjenjanganFungsional('');		

	}	
	if ($par=='insert'){
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
	}
	else if ($par=='delete'){
		$this->view->par="Hapus";
		$this->view->jdl="Manghapus ";	
		$nip=$_GET['nip'];
		$c_jns_fungsional=$_GET['c_jns_fungsional'];
		$c_kel_pelatihan=$_GET['c_kel_pelatihan'];
		$c_jns_kelompok=$_GET['c_jns_kelompok'];
		$c_nama_kelompok=$_GET['c_nama_kelompok'];
		$this->listDataByKey($this->view->nip,$_GET['id']);	
		if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Merubah ";}		
		else{$this->view->jdl="Melihat ";$this->render('fungsionalview');}
	}	
	else{	
		$nip=$_GET['nip'];
		$c_jns_fungsional=$_GET['c_jns_fungsional'];
		$c_kel_pelatihan=$_GET['c_kel_pelatihan'];
		$c_jns_kelompok=$_GET['c_jns_kelompok'];
		$c_nama_kelompok=$_GET['c_nama_kelompok'];
		$this->view->id = $_GET['id'];	
		
		$this->listDataByKey($this->view->nip,$_GET['id']);	
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";

		if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Merubah ";}		
		else{$this->view->jdl="Melihat ";$this->render('fungsionalview');}			
	}	

}
public function listcombokelompokAction() {
	$c_filter=$_GET['c_filter'];
	$cari=" and c_filter='$c_filter' ";
	$this->view->liskellatih=$this->reff_serv->getKelPelatihanFungsional($cari);	

}
public function listcombojenisAction() {
	$c_fungsional=$_GET['c_fungsional'];
	$cari=" and c_fungsional='$c_fungsional' ";
	$this->view->lisjnslatih=$this->reff_serv->getPenjenjanganFungsional($cari);

}	
public function listcombonamaAction() {
	$c_jenis=$_GET['c_jenis'];
	$cari=" and c_jenis='$c_jenis' ";
	$c_jns_fungsional=trim($_GET['c_jns_fungsional']);
	if ($c_jns_fungsional!='1'){
	$this->view->lisnamajenjanglatih=$this->reff_serv->getNamaPenjenjanganFungsional($cari);
	}

}
public function maintaindatafungsionalAction() {

 	if ($_POST['d_sertifikat'])
		{
			$d_sertifikat1=substr($_POST['d_sertifikat'],0,2);
			$d_sertifikat2=substr($_POST['d_sertifikat'],3,2);
			$d_sertifikat3=substr($_POST['d_sertifikat'],6,4);
		}
		$d_sertifikat=$d_sertifikat3."-".$d_sertifikat2."-".$d_sertifikat1;
	if (!$_POST['d_sertifikat']){$d_sertifikat=null;$cektglmulai=true;}
	else{$cekd_sertifikat=checkdate($d_sertifikat2,$d_sertifikat1,$d_sertifikat3);}
		
	if ($cekd_sertifikat==true)
	{
		$MaintainData = array(
			"id"=>$_POST['id'],
			"i_peg_nip"=>$_POST['i_peg_nip'],
			"c_jns_fungsional"=>$_POST['c_jns_fungsional'],
			"c_jns_kelompok"=>$_POST['c_jns_kelompok'],
			"c_kel_pelatihan"=>$_POST['c_kel_pelatihan'],
			"c_nama_kelompok"=>$_POST['c_nama_kelompok'],
			"q_pelatihan"=>$_POST['q_pelatihan']*1,
			"d_sertifikat"=>$d_sertifikat,
			"i_sertifikat"=>$_POST['i_sertifikat'],	
			"n_pejabat"=>$_POST['n_pejabat'],	
			"n_penyelenggara"=>$_POST['n_penyelenggara'],			
			"i_entry"=>$this->view->userid,
			"d_entry"=>date('Ymd'));			

		if ($_POST['proses']=='Simpan')
		{
			$hasil = $this->pelatihan_serv->maintainDataFungsional($MaintainData,'insert');		
			$this->view->par="Simpan";
			$this->view->jdl="Menambah ";
			$par="Menambah";
		}
		else if ($_POST['proses']=='Hapus')
		{
			$hasil = $this->pelatihan_serv->maintainDataFungsional($MaintainData,'delete');		
			$this->view->par="Hapus";
			$this->view->jdl="Menghapus ";
			$par="Menghapus";
		}		
		else
		{
			$hasil = $this->pelatihan_serv->maintainDataFungsional($MaintainData,'update');
			$this->view->par="Ubah";
			$this->view->jdl="Merubah ";
			$par="Merubah";		
		}
		//$this->listDataByKey($this->view->nip,$_POST['c_jns_fungsional'],$_POST['c_kel_pelatihan'],$_POST['c_jns_kelompok'],$_POST['c_nama_kelompok']);		
	}
	
		$this->view->lisjnslatih=$this->reff_serv->getJnsPelatihanFungsional('');		
		$this->view->liskellatih=$this->reff_serv->getKelPelatihanFungsional('');	
		$this->view->lisjenjanglatih=$this->reff_serv->getPenjenjanganFungsional('');
		$this->view->lisnamajenjanglatih=$this->reff_serv->getNamaPenjenjanganFungsional('');	

	$nip=$_POST['i_peg_nip'];	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->pelatihanList = $this->pelatihan_serv->getPelatihanList($cari);	
	$this->listDataByKeyPeg($nip);	
		
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;		
	$this->render('listdiklat');	
	
}
public function hapusdataAction() {
	$MaintainData = array("id"=>$_GET['id']);		
	$hasil = $this->pelatihan_serv->maintainDataFungsional($MaintainData,'delete');	
	
	$pesan= "Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->listdiklatAction();
	$this->render('listdiklat');	
}
     // public function listDataByKey($nip,$c_jns_fungsional,$c_kel_pelatihan,$c_jns_kelompok,$c_nama_kelompok) {  
	//	$cari = " and i_peg_nip ='$nip' and c_jns_fungsional='$c_jns_fungsional' and c_kel_pelatihan='$c_kel_pelatihan' and c_jns_kelompok='$c_jns_kelompok' and c_nama_kelompok='$c_nama_kelompok'";
	 public function listDataByKey($nip,$id) {  
		$cari = " and i_peg_nip ='$nip' and id='$id' ";
		$datalatih = $this->pelatihan_serv->getPelatihanList($cari);		
		
		$this->view->c_jns_fungsional=$datalatih [0]['c_jns_fungsional'];
		$this->view->c_kel_pelatihan=$datalatih [0]['c_kel_pelatihan'];
		$this->view->c_jns_kelompok=$datalatih [0]['c_jns_kelompok'];
		$this->view->c_nama_kelompok=$datalatih [0]['c_nama_kelompok'];
		$this->view->q_pelatihan=$datalatih [0]['q_pelatihan'];
		$this->view->n_penyelenggara=$datalatih [0]['n_penyelenggara'];
		$this->view->i_sertifikat=$datalatih [0]['i_sertifikat'];
		$this->view->d_sertifikat=$datalatih [0]['d_sertifikat'];
		$this->view->n_pejabat=$datalatih [0]['n_pejabat'];	
    }

public function listDataByKeyPeg($nip) {  
	$cari = " and i_peg_nip ='$nip' ";
	$datapegawai=$this->pegawai_serv->getPegawaiListByNip($cari );
	$sespeg = new Zend_Session_Namespace('sespeg');
	$sespeg->nama= $datapegawai[0]['n_peg'];
	$sespeg->nip= $datapegawai[0]['i_peg_nip'];
	$sespeg->golongan= $datapegawai[0]['c_peg_golongan'];
	$sespeg->pangkat= $datapegawai[0]['n_peg_pangkat'];
	$this->view->nama= $sespeg->nama;
	$this->view->nip= $sespeg->nip;
	$this->view->golongan= $sespeg->golongan;
	$this->view->pangkat= $sespeg->pangkat;	
}
}
?>