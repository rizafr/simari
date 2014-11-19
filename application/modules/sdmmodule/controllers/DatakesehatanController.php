<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_Kesehatan_Service.php";

class Sdmmodule_DataKesehatanController extends Zend_Controller_Action {
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu'); 
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->kesehatan_serv = Sdm_Kesehatan_Service::getInstance();
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
public function kesehatanjsAction() 
{
	header('content-type : text/javascript');
	$this->render('kesehatanjs');
}	
	
public function listkesehatanAction() {
	$nip=$this->view->nip;	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->kesehatanList = $this->kesehatan_serv->getKesehatanList($cari);	
}
public function kesehatanAction() {

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
		else{$this->view->jdl="Melihat ";$this->render('kesehatanview');}			
	}
}
public function maintaindataAction() {
 		if ($_POST['d_rawatmulai'])
		{
			$d_rawatmulai1=substr($_POST['d_rawatmulai'],0,2);
			$d_rawatmulai2=substr($_POST['d_rawatmulai'],3,2);
			$d_rawatmulai3=substr($_POST['d_rawatmulai'],6,4);
		}
		$d_rawatmulai=$d_rawatmulai3."-".$d_rawatmulai2."-".$d_rawatmulai1;
		if (!$_POST['d_rawatmulai']){$d_rawatmulai=null;$cektglmulai=true;}
		else{$cektglmulai=checkdate($d_rawatmulai2,$d_rawatmulai1,$d_rawatmulai3);}

 		if ($_POST['d_rawatakhir'])
		{
			$d_rawatakhir1=substr($_POST['d_rawatakhir'],0,2);
			$d_rawatakhir2=substr($_POST['d_rawatakhir'],3,2);
			$d_rawatakhir3=substr($_POST['d_rawatakhir'],6,4);
		}
		$d_rawatakhir=$d_rawatakhir3."-".$d_rawatakhir2."-".$d_rawatakhir1;
		if (!$_POST['d_rawatakhir']){$d_rawatakhir=null;$cektglakhir=true;}
		else{$cektglakhir=checkdate($d_rawatakhir2,$d_rawatakhir1,$d_rawatakhir3);}

 		
if (($cektglmulai==true &&  $cektglakhir==true) )
{	
	$MaintainData = array("id"=>$_POST['id'],
						"i_peg_nip"=>$_POST['i_peg_nip'],
						"n_penyakit"=>$_POST['n_penyakit'],
						"d_rawatmulai"=>$d_rawatmulai,
						"d_rawatakhir"=>$d_rawatakhir,
						"n_rmhsakit"=>$_POST['n_rmhsakit'],
						"a_rmhsakit"=>$_POST['a_rmhsakit'],
						"e_keterangan"=>$_POST['e_keterangan'],
						"i_entry"=>$this->view->userid,
						"d_entry"=>date('Ymd'));		

	if ($_POST['proses']=='Simpan')
	{
		$hasil = $this->kesehatan_serv->maintainData($MaintainData,'insert');		
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
	}		
	else
	{
		$hasil = $this->kesehatan_serv->maintainData($MaintainData,'update');
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";		
	}	

	//$this->listDataByKey($this->view->nip,$_POST['d_rawatmulai']);
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
		//$this->listDataByKey($this->view->nip,$_POST['n_kesehatan']);
}	
	$nip=$_POST['i_peg_nip'];	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->kesehatanList = $this->kesehatan_serv->getKesehatanList($cari);	
	
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->render('listkesehatan');	
}

public function hapusdataAction() {
	$id = $_GET['id'];
	$MaintainData = array("id"=>$id);		
	$hasil = $this->kesehatan_serv->maintainData($MaintainData,'delete');	
	$pesan= "Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->listkesehatanAction();
	$this->render('listkesehatan');	
}
	
public function listDataByKey($id) { 
	$carilist = " and id='$id'";
	$datakesehatan=$this->kesehatan_serv->getKesehatanList($carilist);	
	$this->view->i_peg_nip=$datakesehatan[0]['i_peg_nip'];
	$this->view->d_rawatmulai=$datakesehatan[0]['d_rawatmulai'];
	$this->view->d_rawatakhir=$datakesehatan[0]['d_rawatakhir'];
	$this->view->n_rmhsakit=$datakesehatan[0]['n_rmhsakit'];
	$this->view->a_rmhsakit=trim($datakesehatan[0]['a_rmhsakit']);
	$this->view->n_penyakit=$datakesehatan[0]['n_penyakit'];
	$this->view->e_keterangan=$datakesehatan[0]['e_keterangan'];
	$this->view->i_entry=$datakesehatan[0]['i_entry'];
	$this->view->d_entry=$datakesehatan[0]['d_entry'];
}	
	
}
?>