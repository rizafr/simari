<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_Sertifikasi_Service.php";

class Sdmmodule_DataSertifikasiController extends Zend_Controller_Action {
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu'); 
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->sertifikasi_serv = Sdm_Sertifikasi_Service::getInstance();
		$this->view->nama= $this->nama;
		$this->view->nip= $this->nip;
		$this->view->golongan= $this->golongan;
		$this->view->pangkat=$this->pangkat;
		
		$sespeg = new Zend_Session_Namespace('sespeg');

		$this->view->nama= $sespeg->nama;
		$this->view->nip= $sespeg->nip;
		$this->view->golongan= $sespeg->golongan;
		$this->view->pangkat= $sespeg->pangkat;		
    }
	
    public function indexAction() {
	   
    }
public function sertifikasijsAction() 
{
	header('content-type : text/javascript');
	$this->render('sertifikasijs');
}	
	
public function listsertifikasiAction() {
	$nip=$this->view->nip;	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->sertifikasiList = $this->sertifikasi_serv->getSertifikasiList($cari);	
}
public function sertifikasiAction() {
	$par=$_GET['par'];	
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
	}
}
public function maintaindataAction() {
 		if ($_POST['d_sertifikasi'])
		{
			$d_sertifikasi1=substr($_POST['d_sertifikasi'],0,2);
			$d_sertifikasi2=substr($_POST['d_sertifikasi'],3,2);
			$d_sertifikasi3=substr($_POST['d_sertifikasi'],6,4);
		}
		$d_sertifikasi=$d_sertifikasi3."-".$d_sertifikasi2."-".$d_sertifikasi1;
		if (!$_POST['d_sertifikasi']){$d_sertifikasi=null;$cektglserti=true;}
		else{$cektglserti=checkdate($d_sertifikasi2,$d_sertifikasi1,$d_sertifikasi3);}

 		if ($_POST['d_mulai_sertifikasi'])
		{
			$d_mulai_sertifikasi1=substr($_POST['d_mulai_sertifikasi'],0,2);
			$d_mulai_sertifikasi2=substr($_POST['d_mulai_sertifikasi'],3,2);
			$d_mulai_sertifikasi3=substr($_POST['d_mulai_sertifikasi'],6,4);
		}
		$d_mulai_sertifikasi=$d_mulai_sertifikasi3."-".$d_mulai_sertifikasi2."-".$d_mulai_sertifikasi1;
		if (!$_POST['d_mulai_sertifikasi']){$d_mulai_sertifikasi=null;$cektglmulai=true;}
		else{$cektglmulai=checkdate($d_mulai_sertifikasi2,$d_mulai_sertifikasi1,$d_mulai_sertifikasi3);}

 		if ($_POST['d_akhir_sertifikasi'])
		{
			$d_akhir_sertifikasi1=substr($_POST['d_akhir_sertifikasi'],0,2);
			$d_akhir_sertifikasi2=substr($_POST['d_akhir_sertifikasi'],3,2);
			$d_akhir_sertifikasi3=substr($_POST['d_akhir_sertifikasi'],6,4);
		}
		$d_akhir_sertifikasi=$d_akhir_sertifikasi3."-".$d_akhir_sertifikasi2."-".$d_akhir_sertifikasi1;
		if (!$_POST['d_akhir_sertifikasi']){$d_akhir_sertifikasi=null;$cektglakhir=true;}
		else{$cektglakhir=checkdate($d_akhir_sertifikasi2,$d_akhir_sertifikasi1,$d_akhir_sertifikasi3);	}
 		
if (($cektglmulai==true &&  $cektglakhir==true &&  $cektglserti==true) ){	

 		if ($_POST['d_mulai_sertifikasi2'])
		{
			$d_mulai_sertifikasi21=substr($_POST['d_mulai_sertifikasi2'],0,2);
			$d_mulai_sertifikasi22=substr($_POST['d_mulai_sertifikasi2'],3,2);
			$d_mulai_sertifikasi23=substr($_POST['d_mulai_sertifikasi2'],6,4);
		}
		$d_mulai_sertifikasi2=$d_mulai_sertifikasi23."-".$d_mulai_sertifikasi22."-".$d_mulai_sertifikasi21;
		if (!$_POST['d_mulai_sertifikasi2']){$d_mulai_sertifikasi2=null;}
		
	$MaintainData = array("id"=>$_POST['id'],
						"i_peg_nip"=>$_POST['i_peg_nip'],
						"i_sertifikasi"=>strtoupper($_POST['i_sertifikasi']),
						"n_sertifikasi"=>strtoupper($_POST['n_sertifikasi']),
						"n_sertifikasi2"=>strtoupper($_POST['n_sertifikasi2']),
						"d_sertifikasi"=>$d_sertifikasi,
						"d_mulai_sertifikasi"=>$d_mulai_sertifikasi,
						"d_mulai_sertifikasi2"=>$d_mulai_sertifikasi2,
						"d_akhir_sertifikasi"=>$d_akhir_sertifikasi,
						"n_sertifikasi_lembaga"=>strtoupper($_POST['n_sertifikasi_lembaga']),
						"e_keterangan"=>$_POST['e_keterangan'],
						"i_entry"=>"test",
						"d_entry"=>date('Ymd'));		

	if ($_POST['proses']=='Simpan')
	{
		$hasil = $this->sertifikasi_serv->maintainData($MaintainData,'insert');		
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
	}		
	else
	{
		$hasil = $this->sertifikasi_serv->maintainData($MaintainData,'update');
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
		
}	
	$this->view->id=$_POST['id'];
	$this->listDataByKey($_POST['id']);
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	
	$this->render('sertifikasi');	
}

public function hapusdataAction() {
	$MaintainData = array("id"=>$_GET['id']);		
	$hasil = $this->sertifikasi_serv->maintainData($MaintainData,'delete');	
	$pesan= "Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->listsertifikasiAction();
	$this->render('listsertifikasi');	
}

public function listDataByKey($id) {  
	$carilist = " and id='$id'";
	$datasertifikasi=$this->sertifikasi_serv->getSertifikasiList($carilist);	
	$this->view->i_peg_nip=$datasertifikasi[0]['i_peg_nip'];
	$this->view->i_sertifikasi=$datasertifikasi[0]['i_sertifikasi'];
	$this->view->n_sertifikasi=$datasertifikasi[0]['n_sertifikasi'];
	$this->view->n_sertifikasi_lembaga=$datasertifikasi[0]['n_sertifikasi_lembaga'];
	$this->view->d_sertifikasi=trim($datasertifikasi[0]['d_sertifikasi']);
	$this->view->d_mulai_sertifikasi=$datasertifikasi[0]['d_mulai_sertifikasi'];
	$this->view->d_akhir_sertifikasi=$datasertifikasi[0]['d_akhir_sertifikasi'];
	$this->view->e_keterangan=$datasertifikasi[0]['e_keterangan'];
	$this->view->i_entry=$datasertifikasi[0]['i_entry'];
	$this->view->d_entry=$datasertifikasi[0]['d_entry'];
}	
	
}
?>