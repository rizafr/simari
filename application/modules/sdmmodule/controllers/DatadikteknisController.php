<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_DiklatTeknis_Service.php";
require_once "service/sdm/sdm_refferensi_Service.php";
class Sdmmodule_DatadikteknisController extends Zend_Controller_Action {
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu'); 
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->pelatihan_serv = Sdm_DiklatTeknis_Service::getInstance();
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
public function dikteknisjsAction() 
{
	header('content-type : text/javascript');
	$this->render('dikteknisjs');
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

public function teknisAction() {
	$par=$_GET['par'];
		$this->view->listrkelteknis=$this->reff_serv->getKelDikTeknis('');		
		//$this->view->lisdikteknis=$this->reff_serv->getTrDiklatTeknis($cari);		
		//$this->view->listnegara=$this->reff_serv->getNegara('');	
	if ($par=='insert'){
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
	}
	else if ($par=='delete'){
		$this->view->par="Hapus";
		$this->view->jdl="Menghapus ";
		$this->listDataByKey($this->view->nip,$_GET['id']);
	}
	else{	
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$this->view->id=$_GET['id'];
		$this->listDataByKey($this->view->nip,$_GET['id']);

		if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Merubah ";}		
		else{$this->view->jdl="Melihat ";$this->render('teknisview');}			
	}			
			

	
}

public function listcomboteknisAction() {
	$c_kelompok=$_GET['c_kelompok'];
	if($c_kelompok){
		$cari = " and c_kelompok ='$c_kelompok' ";
		$this->view->lisdikteknis=$this->reff_serv->getTrDiklatTeknis($cari);		
	}
}

public function maintaindatateknisAction() {

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
		$MaintainData = array("i_peg_nip"=>$_POST['i_peg_nip'],
					"id"=>$_POST['id'],
					"c_kelompok"=>$_POST['c_kelompok']*1,
					"n_diklat"=>$_POST['n_diklat'],
					"c_negara"=>$_POST['c_negara']*1,
					"q_lama"=>$_POST['q_lama']*1,
					"n_penyelenggara"=>$_POST['n_penyelenggara'],
					"i_sertifikat"=>$_POST['i_sertifikat'],
					"d_sertifikat"=>$d_sertifikat,
					"n_pejabat"=>$_POST['n_pejabat'],		
					"i_entry"=>"test",
					"d_entry"=>date('Ymd'));	
													

		if ($_POST['proses']=='Simpan')
		{
			$i_peg_nip=$_POST['i_peg_nip'];
			$c_kelompok=$_POST['c_kelompok']*1;
			$n_diklat=$_POST['n_diklat'];
			//$cariList = " and i_peg_nip ='$i_peg_nip' and c_kelompok='$c_kelompok' and n_diklat='$n_diklat'";
			//$checkdata = $this->pelatihan_serv->getPelatihanList($cariList);	
			//$checkdata=$checkdata[0]['i_peg_nip'];
			
			//if (!$checkdata)	
			//{				
				$hasil = $this->pelatihan_serv->maintainData($MaintainData,'insert');		
				$this->view->par="Simpan";
				$this->view->jdl="Menambah ";
				$par="Menambah";
			//}
			/*
			else{
				$this->view->i_peg_nip=$_POST['i_peg_nip'];
				$this->view->c_kelompok=$_POST['c_kelompok'];
				$this->view->n_diklat=$_POST['n_diklat'];
				$this->view->c_negara=$_POST['c_negara'];
				$this->view->q_lama=$_POST['q_lama'];
				$this->view->n_penyelenggara=$_POST['n_penyelenggara'];
				$this->view->i_sertifikat=$_POST['i_sertifikat'];
				$this->view->d_sertifikat=$_POST['d_sertifikat'];
				$this->view->n_pejabat=$_POST['n_pejabat'];
		
				$hasil="gagal";
				$this->view->par="Simpan";
				$this->view->jdl="Menambah ";
				$par="Menambah";
			}	*/		
		}
		else if ($_POST['proses']=='Hapus')
		{							
			$hasil = $this->pelatihan_serv->maintainData($MaintainData,'delete');
			$this->view->par="Hapus";
			$this->view->jdl="Manghapus ";
			$par="Manghapus";	
		}		
		else
		{
			$hasil = $this->pelatihan_serv->maintainData($MaintainData,'update');
			$this->view->par="Ubah";
			$this->view->jdl="Merubah ";
			$par="Merubah";		
		}
		//$this->listDataByKey($this->view->nip,$_POST['c_kelompok']);		
	}	
		$this->view->listrkelteknis=$this->reff_serv->getTrKelDiklatTeknis('');	
		$this->view->listnegara=$this->reff_serv->getNegara('');	

	$nip=$_POST['i_peg_nip'];	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->pelatihanList = $this->pelatihan_serv->getPelatihanList($cari);	
	$this->listDataByKeyPeg($nip);
	
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;		
	//$this->render('listdiklat');

	if ($hasil=='gagal'){
		$cari = " and c_kelompok ='$c_kelompok' ";
		$this->view->lisdikteknis=$this->reff_serv->getTrDiklatTeknis($cari);
		$this->view->listrkelteknis=$this->reff_serv->getKelDikTeknis('');	
		$pesan=$par." data ".$hasil." Status sudah ada";
		$this->view->pesan = $pesan;
		$this->view->pesancek = $hasil;	
		$this->render('teknis');
	}else{
	$this->render('listdiklat');}		
	
}
public function hapusdataAction() {
	$id  = $_GET['id'];
	$MaintainData = array("id"=>$id);		
	$hasil = $this->pelatihan_serv->maintainData($MaintainData,'delete');	
	$pesan= "Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this-> listdiklatAction();
	$this->render('listdiklat');	
}
      public function listDataByKey($nip,$id) {  
		$cari = " and i_peg_nip ='$nip' and id=$id ";
		$datalatih = $this->pelatihan_serv->getPelatihanList($cari);		
		
		$this->view->c_kelompok=$datalatih [0]['c_kelompok'];
		$this->view->n_diklat=$datalatih [0]['n_diklat'];
		$this->view->c_negara=$datalatih [0]['c_negara'];
		$this->view->q_lama=$datalatih [0]['q_lama'];
		$this->view->n_penyelenggara=$datalatih [0]['n_penyelenggara'];
		$this->view->i_sertifikat=$datalatih [0]['i_sertifikat'];
		$this->view->d_sertifikat=$datalatih [0]['d_sertifikat'];
		$this->view->n_pejabat=$datalatih [0]['n_pejabat'];
		$this->view->id=$datalatih [0]['id'];
		$cari = " and c_kelompok ='".$datalatih [0]['c_kelompok']."' ";
		$this->view->lisdikteknis=$this->reff_serv->getTrDiklatTeknis($cari);			

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