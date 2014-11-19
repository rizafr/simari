<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_Pelatihan_Service.php";
require_once "service/sdm/sdm_refferensi_Service.php";
class Sdmmodule_DataPelatihanController extends Zend_Controller_Action {
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu'); 
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->pelatihan_serv = Sdm_Pelatihan_Service::getInstance();
		$this->reff_serv = sdm_refferensi_Service::getInstance();
		$this->view->nama= $this->nama;
		$this->view->nip= $this->nip;
		$this->view->golongan= $this->golongan;
		$this->view->pangkat=$this->pangkat;
		$this->view->filephoto=$this->filephoto;
		$this->view->statuspeg=$this->statuspeg;
		
		$sespeg = new Zend_Session_Namespace('sespeg');

		$this->view->nama= $sespeg->nama;
		$this->view->nip= $sespeg->nip;
		$this->view->golongan= $sespeg->golongan;
		$this->view->pangkat= $sespeg->pangkat;	
		$this->view->filephoto= $sespeg->filephoto;	
		$this->view->statuspeg= $sespeg->statuspeg;		
    }
	
    public function indexAction() {
	   
    }
public function pelatihanjsAction() 
{
	header('content-type : text/javascript');
	$this->render('pelatihanjs');
}	
	
public function listpelatihanAction() {
	$nip=$this->view->nip;	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->pelatihanList = $this->pelatihan_serv->getPelatihanList($cari);	
}
public function pelatihanAction() {

	$par=$_GET['par'];	
	$this->view->par=$_GET['par'];
	
}
//pelatihan Fungsional
public function fungsionalAction() {
	$par=$_GET['par'];	
	if ($par=='insert'){
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
	}
	else{
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";	
	}	
	$jenisLatih=$_GET['jenisLatih'];
	if ($jenisLatih=="P"){$this->render('penjenjangan');}
	elseif ($jenisLatih=="T"){$this->render('teknis');}
	elseif ($jenisLatih=="L"){$this->render('lainnya');}
	else{
		$this->view->lisjnslatih=$this->reff_serv->getJnsPelatihanFungsional('');		
		$this->view->liskellatih=$this->reff_serv->getKelPelatihanFungsional('');	
		$this->view->lisjenjanglatih=$this->reff_serv->getPenjenjanganFungsional('');
		$this->view->lisnamajenjanglatih=$this->reff_serv->getNamaPenjenjanganFungsional('');		
				
				
		$this->render('fungsional');
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
	$this->view->lisnamajenjanglatih=$this->reff_serv->getNamaPenjenjanganFungsional($cari);

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
			"i_peg_nip"=>$_POST['i_peg_nip'],
			"c_jns_fungsional"=>$_POST['c_jns_fungsional'],
			"c_jns_kelompok"=>$_POST['c_jns_kelompok'],
			"c_kel_pelatihan"=>$_POST['c_kel_pelatihan'],
			"c_nama_kelompok"=>$_POST['c_nama_kelompok'],
			"q_pelatihan"=>$_POST['q_pelatihan'],
			"d_sertifikat"=>$d_sertifikat,
			"i_sertifikat"=>$_POST['i_sertifikat'],	
			"n_pejabat"=>$_POST['n_pejabat'],	
			"n_penyelenggara"=>$_POST['n_penyelenggara'],			
			"i_entry"=>"test",
			"d_entry"=>date('Ymd'));			

		if ($_POST['proses']=='Simpan')
		{
			$hasil = $this->pelatihan_serv->maintainDataFungsional($MaintainData,'insert');		
			$this->view->par="Simpan";
			$this->view->jdl="Menambah ";
			$par="Menambah";
		}		
		else
		{
			$hasil = $this->pelatihan_serv->maintainDataFungsional($MaintainData,'update');
			$this->view->par="Ubah";
			$this->view->jdl="Merubah ";
			$par="Merubah";		
		}
	}
	$this->view->jnspelatihan="fungsional";
	$this->render('pelatihan');	
	
}
	
}
?>