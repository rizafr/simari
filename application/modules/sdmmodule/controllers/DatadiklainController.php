<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_DiklatLain_Service.php";
require_once "service/sdm/sdm_refferensi_Service.php";
class Sdmmodule_DatadiklainController extends Zend_Controller_Action {
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu'); 
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->pelatihan_serv = Sdm_DiklatLain_Service::getInstance();
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
public function diklainjsAction() 
{
	header('content-type : text/javascript');
	$this->render('diklainjs');
}	
	
public function listdiklatAction() {
	$nip=$this->view->nip;	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->pelatihanList = $this->pelatihan_serv->getPelatihanList($cari);	
	$this->listDataByKeyPeg($nip);	
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
public function lainAction() {
	$par=$_GET['par'];
	$this->view->listnegara=$this->reff_serv->getNegara('');	
	if ($par=='insert'){
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
	}
	else if ($par=='delete'){
		$this->view->par="Hapus";
		$this->view->jdl="Manghapus ";	
		if ($_GET['d_diklat'])
		{
			$d_diklat1=substr($_GET['d_diklat'],0,2);
			$d_diklat2=substr($_GET['d_diklat'],3,2);
			$d_diklat3=substr($_GET['d_diklat'],6,4);
		}
		$d_diklat=$d_diklat3."-".$d_diklat2."-".$d_diklat1;				
		$this->listDataByKey($this->view->nip,$_GET['id']); 
	}		
	else{	
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$this->view->id = $_GET['id'];	
		
		if ($_GET['d_diklat'])
		{
			$d_diklat1=substr($_GET['d_diklat'],0,2);
			$d_diklat2=substr($_GET['d_diklat'],3,2);
			$d_diklat3=substr($_GET['d_diklat'],6,4);
		}
		$d_diklat=$d_diklat3."-".$d_diklat2."-".$d_diklat1;				
		$this->listDataByKey($this->view->nip,$_GET['id']);

		if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Merubah ";}		
		else{$this->view->jdl="Melihat ";$this->render('lainview');}			
	}			
			

	
}

public function maintaindatalainAction() {

 	if ($_POST['d_sertifikat'])
		{
			$d_sertifikat1=substr($_POST['d_sertifikat'],0,2);
			$d_sertifikat2=substr($_POST['d_sertifikat'],3,2);
			$d_sertifikat3=substr($_POST['d_sertifikat'],6,4);
		}
		$d_sertifikat=$d_sertifikat3."-".$d_sertifikat2."-".$d_sertifikat1;
	if (!$_POST['d_sertifikat']){$d_sertifikat=null;}
	else{$cekd_sertifikat=checkdate($d_sertifikat2,$d_sertifikat1,$d_sertifikat3);}

 	if ($_POST['d_diklat'])
		{
			$d_diklat1=substr($_POST['d_diklat'],0,2);
			$d_diklat2=substr($_POST['d_diklat'],3,2);
			$d_diklat3=substr($_POST['d_diklat'],6,4);
		}
		$d_diklat=$d_diklat3."-".$d_diklat2."-".$d_diklat1;
	if (!$_POST['d_sertifikat']){$d_sertifikat=null;}
	else{$cekd_diklat=checkdate($d_diklat2,$d_diklat1,$d_diklat3);}
	
	if ($cekd_sertifikat==true && $cekd_diklat==true)
	{
		$q_lama=$_POST['q_lama'];
		if(!$_POST['q_lama']){$q_lama=0;}
		$MaintainData = array("id"=>$_POST['id'],"i_peg_nip"=>$_POST['i_peg_nip'],
								"d_diklat"=>$d_diklat,
								"n_diklat"=>strtoupper($_POST['n_diklat']),
								"c_negara"=>$_POST['c_negara'],
								"q_lama"=>$q_lama,
								"n_penyelenggara"=>$_POST['n_penyelenggara'],
								"i_sertifikat"=>$_POST['i_sertifikat'],
								"d_sertifikat"=>$d_sertifikat,
								"n_pejabat"=>$_POST['n_pejabat'],		
								"i_entry"=>$this->view->userid,
								"d_entry"=>date('Ymd'));	
													

		if ($_POST['proses']=='Simpan')
		{
			$hasil = $this->pelatihan_serv->maintainData($MaintainData,'insert');		
			$this->view->par="Simpan";
			$this->view->jdl="Menambah ";
			$par="Menambah";
		}
		else if ($_POST['proses']=='Hapus')
		{
			$hasil = $this->pelatihan_serv->maintainData($MaintainData,'delete');		
			$this->view->par="Hapus";
			$this->view->jdl="Menghapus ";
			$par="Menghapus";
		}		
		else
		{
			$hasil = $this->pelatihan_serv->maintainData($MaintainData,'update');
			$this->view->par="Ubah";
			$this->view->jdl="Merubah ";
			$this->view->id = $_GET['id'];	
			$par="Merubah";		
		}
		//$this->listDataByKey($this->view->nip,$d_diklat,$_POST['n_diklat']);		
	}	
		$this->view->listnegara=$this->reff_serv->getNegara('');	

	$nip=$_POST['i_peg_nip'];	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->pelatihanList = $this->pelatihan_serv->getPelatihanList($cari);	
	$this->listDataByKeyPeg($nip);	
	
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;		
	$this->render('listdiklat');	
	
}

      public function listDataByKey($nip,$id) { 
		$n_diklat=strtoupper($n_diklat);
		//$cari = " and i_peg_nip ='$nip' and to_char(d_diklat,'yyyy-mm-dd') ='$d_diklat' and upper(n_diklat)='$n_diklat'";
		$cari = " and i_peg_nip ='$nip' and id ='$id' ";
		$datalatih = $this->pelatihan_serv->getPelatihanList($cari);		

		$this->view->d_diklat=$datalatih [0]['d_diklat'];
		$this->view->n_diklat=$datalatih [0]['n_diklat'];
		$this->view->c_negara=$datalatih [0]['c_negara'];
		$this->view->q_lama=$datalatih [0]['q_lama'];
		$this->view->n_penyelenggara=$datalatih [0]['n_penyelenggara'];
		$this->view->i_sertifikat=$datalatih [0]['i_sertifikat'];
		$this->view->d_sertifikat=$datalatih [0]['d_sertifikat'];
		$this->view->n_pejabat=$datalatih [0]['n_pejabat'];	

    }
	public function hapusdataAction() {
	$MaintainData = array("id"=>$_GET['id']);		
	$hasil = $this->pelatihan_serv->maintainData($MaintainData,'delete');	
	
	$pesan= "Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->listdiklatAction();
	$this->render('listdiklat');	
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