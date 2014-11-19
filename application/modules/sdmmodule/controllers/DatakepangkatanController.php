<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_Kepangkatan_Service.php";
require_once "service/sdm/sdm_refferensi_Service.php";
class Sdmmodule_DataKepangkatanController extends Zend_Controller_Action {
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu'); 
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->kepangkatan_serv = Sdm_kepangkatan_service::getInstance();
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
public function kepangkatanjsAction() 
{
	header('content-type : text/javascript');
	$this->render('kepangkatanjs');
}	
	
public function listkepangkatanAction() {
	$nip=$this->view->nip;	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->kepangkatanList = $this->kepangkatan_serv->getKepangkatanList($cari);	
}
public function kepangkatanAction() {

	$par=$_GET['par'];
	$this->view->jnsKepangkatanList = $this->reff_serv->getTrJnsKepangkatan($cari);		
	if ($par=='insert'){
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
	}
	else{
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";	
		$par=$_GET['par'];
		$id=$_GET['id'];
		$this->view->id=$id;
		$this->listDataByKey($id);
		
		if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Merubah ";}		
		else{$this->view->jdl="Melihat ";$this->render('kepangkatanview');}			
	}
		

	
}
public function maintaindataAction() {
 		if ($_POST['d_test_kepangkatan'])
		{
			$d_test_kepangkatan1=substr($_POST['d_test_kepangkatan'],0,2);
			$d_test_kepangkatan2=substr($_POST['d_test_kepangkatan'],3,2);
			$d_test_kepangkatan3=substr($_POST['d_test_kepangkatan'],6,4);
		}
		$d_test_kepangkatan=$d_test_kepangkatan3."-".$d_test_kepangkatan2."-".$d_test_kepangkatan1;
		if (!$_POST['d_test_kepangkatan']){$d_test_kepangkatan=null;$cektgltest=true;}
		else{$cektgltest=checkdate($d_test_kepangkatan2,$d_test_kepangkatan1,$d_test_kepangkatan3);}

 		if ($_POST['d_sk_kepangkatan'])
		{
			$d_sk_kepangkatan1=substr($_POST['d_sk_kepangkatan'],0,2);
			$d_sk_kepangkatan2=substr($_POST['d_sk_kepangkatan'],3,2);
			$d_sk_kepangkatan3=substr($_POST['d_sk_kepangkatan'],6,4);
		}
		$d_sk_kepangkatan=$d_sk_kepangkatan3."-".$d_sk_kepangkatan2."-".$d_sk_kepangkatan1;
		if (!$_POST['d_sk_kepangkatan']){$d_sk_kepangkatan=null;$cektglberlaku=true;}
		else{$cektglberlaku=checkdate($d_sk_kepangkatan2,$d_sk_kepangkatan1,$d_sk_kepangkatan3);}
		

 		if ($_POST['d_test_kepangkatan2'])
		{
			$d_test_kepangkatan21=substr($_POST['d_test_kepangkatan2'],0,2);
			$d_test_kepangkatan22=substr($_POST['d_test_kepangkatan2'],3,2);
			$d_test_kepangkatan23=substr($_POST['d_test_kepangkatan2'],6,4);
		}
		$d_test_kepangkatan2=$d_test_kepangkatan23."-".$d_test_kepangkatan22."-".$d_test_kepangkatan21;		
		
if (($cektgltest==true) )
{


	$MaintainData = array("id"=>$_POST['id'],
				"i_peg_nip"=>$_POST['i_peg_nip'],
				"c_jns_kepangkatan"=>$_POST['c_jns_kepangkatan'],
				"c_jns_kepangkatan2"=>$_POST['c_jns_kepangkatan2'],
				"a_tempat_test"=>$_POST['a_tempat_test'],
				"c_hasil_kepangkatan"=>$_POST['c_hasil_kepangkatan'],
				"n_pejabat_kepangkatan"=>$_POST['n_pejabat_kepangkatan'],
				"i_sk_kepangkatan"=>$_POST['i_sk_kepangkatan'],
				"d_test_kepangkatan"=>$d_test_kepangkatan,
				"d_test_kepangkatan2"=>$d_test_kepangkatan2,
				"d_sk_kepangkatan"=>$d_sk_kepangkatan,
				"i_entry"=>$this->view->userid,
				"d_entry"=>date('Ymd'));		

	if ($_POST['proses']=='Simpan')
	{
		$hasil = $this->kepangkatan_serv->maintainData($MaintainData,'insert');		
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
	}		
	else
	{
		$hasil = $this->kepangkatan_serv->maintainData($MaintainData,'update');
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
	$id = $_POST['$id'];
	$this->view->id  = $id;	
		$this->listDataByKey($id);
}	
	$this->listkepangkatanAction();
	//$this->view->jnsKepangkatanList = $this->reff_serv->getTrJnsKepangkatan('');
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->render('listkepangkatan');	
}

public function hapusdataAction() {
 	$id = $_GET['id'];
	$MaintainData = array("id"=>$id);	
	$hasil = $this->kepangkatan_serv->maintainData($MaintainData,'delete');	
	$pesan= "Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->listkepangkatanAction();
	$this->render('listkepangkatan');	
}
	
public function listDataByKey($id) { 
	$carilist = " and id='$id'";
	$datakepangkatan=$this->kepangkatan_serv->getKepangkatanList($carilist);	
	$this->view->i_peg_nip=$datakepangkatan[0]['i_peg_nip'];
	$this->view->d_test_kepangkatan=$datakepangkatan[0]['d_test_kepangkatan'];
	$this->view->c_jns_kepangkatan=$datakepangkatan[0]['c_jns_kepangkatan'];
	$this->view->a_tempat_test=$datakepangkatan[0]['a_tempat_test'];
	$this->view->c_hasil_kepangkatan=$datakepangkatan[0]['c_hasil_kepangkatan'];
	$this->view->d_sk_kepangkatan=$datakepangkatan[0]['d_sk_kepangkatan'];
	$this->view->i_sk_kepangkatan=$datakepangkatan[0]['i_sk_kepangkatan'];
	$this->view->n_pejabat_kepangkatan=$datakepangkatan[0]['n_pejabat_kepangkatan'];
	$this->view->i_entry=$datakepangkatan[0]['i_entry'];
	$this->view->d_entry=$datakepangkatan[0]['d_entry'];

}	
	
}
?>