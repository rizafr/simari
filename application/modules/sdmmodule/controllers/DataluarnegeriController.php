<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_Luarnegeri_Service.php";
require_once "service/sdm/sdm_refferensi_Service.php";
class Sdmmodule_DataluarnegeriController extends Zend_Controller_Action {
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu'); 		
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->luarnegeri_serv = Sdm_Luarnegeri_Service::getInstance();
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
public function luarnegerijsAction() 
{
	header('content-type : text/javascript');
	$this->render('luarnegerijs');
}	
	
public function listluarnegeriAction() {
	$nip=$this->view->nip;	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->luarnegeriList = $this->luarnegeri_serv->getLuarnegeriList($cari);	
}
public function luarnegeriAction() {
	$this->view->negaraList = $this->reff_serv->getNegara(''); 
	$par=$_GET['par'];	
	if ($par=='insert'){
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
	}
	else{
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";	
		$this->view->id= $_GET['id'];
		$par=$_GET['par'];
		$this->listDataByKey($_GET['id']);
		
		if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Merubah ";}		
		else{$this->view->jdl="Melihat ";$this->render('luarnegeriview');}			
	}

}
public function maintaindataAction() {

 		if ($_POST['d_barangkat'])
		{
			$d_barangkat1=substr($_POST['d_barangkat'],0,2);
			$d_barangkat2=substr($_POST['d_barangkat'],3,2);
			$d_barangkat3=substr($_POST['d_barangkat'],6,4);
		}
		$d_barangkat=$d_barangkat3."-".$d_barangkat2."-".$d_barangkat1;
		if (!$_POST['d_barangkat']){$d_barangkat=null;$cektglmulai=true;}
		else{$cektglmulai=checkdate($d_barangkat2,$d_barangkat1,$d_barangkat3);}

		if (!$_POST['q_hari']){$q_hari=0;}else{$q_hari=$_POST['q_hari'];}
		if (!$_POST['q_bulan']){$q_bulan=0;}else{$q_bulan=$_POST['q_bulan'];}
		if (!$_POST['q_tahun']){$q_tahun=0;}else{$q_tahun=$_POST['q_tahun'];}
		
	$MaintainData = array("id"=>$_POST['id'],
					"i_peg_nip"=>$_POST['i_peg_nip'],
						"c_negara"=>$_POST['c_negara'],
						"c_negara2"=>$_POST['c_negara2'],
						"a_tujuan"=>$_POST['a_tujuan'],
						"a_tujuan"=>$_POST['a_tujuan'],
						"c_biaya"=>$_POST['c_biaya'],
						"e_sponsor"=>$_POST['e_sponsor'],
						"d_barangkat"=>$d_barangkat,
						"q_hari"=>$q_hari,
						"q_bulan"=>$q_bulan,
						"q_tahun"=>$q_tahun,
						"e_keterangan"=>$_POST['e_keterangan'],
						"i_entry"=>$this->view->userid,
						"d_entry"=>date('Ymd'));		

	if ($_POST['proses']=='Simpan')
	{
		$hasil = $this->luarnegeri_serv->maintainData($MaintainData,'insert');		
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
	}		
	else
	{
		$hasil = $this->luarnegeri_serv->maintainData($MaintainData,'update');
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";		
	}	

	//$this->listDataByKey($this->view->nip,$_POST['c_negara'],$_POST['d_barangkat']);
	$this->view->negaraList = $this->reff_serv->getNegara(''); 
	
	$nip=$_POST['i_peg_nip'];	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->luarnegeriList = $this->luarnegeri_serv->getLuarnegeriList($cari);	
	
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->render('listluarnegeri');	
}

public function hapusdataAction() {
	$MaintainData = array("id"=>$_GET['id']);		
	$hasil = $this->luarnegeri_serv->maintainData($MaintainData,'delete');	
	$pesan= "Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->listluarnegeriAction();
	$this->render('listluarnegeri');	
}
	
public function listDataByKey($id) {  
	$carilist = " and id='$id'";
	$dataluarnegeri=$this->luarnegeri_serv->getluarnegeriList($carilist);
	$this->view->i_peg_nip=$dataluarnegeri[0]['i_peg_nip'];
	$this->view->c_negara=trim($dataluarnegeri[0]['c_negara']);
	$this->view->a_tujuan=trim($dataluarnegeri[0]['a_tujuan']);
	$this->view->c_biaya=$dataluarnegeri[0]['c_biaya'];
	$this->view->e_sponsor=trim($dataluarnegeri[0]['e_sponsor']);
	$this->view->d_barangkat=$dataluarnegeri[0]['d_barangkat'];
	$this->view->q_hari=$dataluarnegeri[0]['q_hari'];
	$this->view->q_bulan=$dataluarnegeri[0]['q_bulan'];
	$this->view->q_tahun=$dataluarnegeri[0]['q_tahun'];
	$this->view->e_keterangan=$dataluarnegeri[0]['e_keterangan'];
	$this->view->i_entry=$dataluarnegeri[0]['i_entry'];
	$this->view->d_entry=$dataluarnegeri[0]['d_entry'];
}	
	
}
?>