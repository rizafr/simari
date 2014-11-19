<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Refferensi_Service.php";
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_Seminar_Service.php";

class Sdmmodule_DataSeminarController extends Zend_Controller_Action {
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu'); 		
		$this->reff_serv = refferensi_Service::getInstance();
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->seminar_serv = Sdm_Seminar_Service::getInstance();
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
public function seminarjsAction() 
{
	header('content-type : text/javascript');
	$this->render('seminarjs');
}	
	
public function listseminarAction() {
	$nip=$this->view->nip;	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->seminarList = $this->seminar_serv->getSeminarList($cari);	
}
public function seminarAction() {

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
		if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Merubah ";}		
		else{$this->view->jdl="Melihat ";$this->render('seminarview');}		
	}
}
public function maintaindataAction() {
 		if ($_POST['d_mulai_seminar'])
		{
			$d_mulai_seminar1=substr($_POST['d_mulai_seminar'],0,2);
			$d_mulai_seminar2=substr($_POST['d_mulai_seminar'],3,2);
			$d_mulai_seminar3=substr($_POST['d_mulai_seminar'],6,4);
		}
		$d_mulai_seminar=$d_mulai_seminar3."-".$d_mulai_seminar2."-".$d_mulai_seminar1;
		if (!$_POST['d_mulai_seminar']){$d_mulai_seminar=null;$cektglmulai=true;}
		else{$cektglmulai=checkdate($d_mulai_seminar2,$d_mulai_seminar1,$d_mulai_seminar3);}

 		if ($_POST['d_akhir_seminar'])
		{
			$d_akhir_seminar1=substr($_POST['d_akhir_seminar'],0,2);
			$d_akhir_seminar2=substr($_POST['d_akhir_seminar'],3,2);
			$d_akhir_seminar3=substr($_POST['d_akhir_seminar'],6,4);
		}
		$d_akhir_seminar=$d_akhir_seminar3."-".$d_akhir_seminar2."-".$d_akhir_seminar1;
		if (!$_POST['d_akhir_seminar']){$d_akhir_seminar=null;$cektglakhir=true;}
		else{$cektglakhir=checkdate($d_akhir_seminar2,$d_akhir_seminar1,$d_akhir_seminar3);}

 		
if (($cektglmulai==true &&  $cektglakhir==true) )
{	

 		if ($_POST['d_mulai_seminar2'])
		{
			$d_mulai_seminar21=substr($_POST['d_mulai_seminar2'],0,2);
			$d_mulai_seminar22=substr($_POST['d_mulai_seminar2'],3,2);
			$d_mulai_seminar23=substr($_POST['d_mulai_seminar2'],6,4);
		}
		$d_mulai_seminar2=$d_mulai_seminar23."-".$d_mulai_seminar22."-".$d_mulai_seminar21;
		if (!$_POST['d_mulai_seminar2']){$d_mulai_seminar2=null;}
		
	$MaintainData = array("id"=>$_POST['id'],
						"i_peg_nip"=>$_POST['i_peg_nip'],
						"n_seminar"=>strtoupper($_POST['n_seminar']),
						"d_mulai_seminar"=>$d_mulai_seminar,
						"d_mulai_seminar2"=>$d_mulai_seminar2,
						"d_akhir_seminar"=>$d_akhir_seminar,
						"n_seminar_peran"=>strtoupper($_POST['n_seminar_peran']),
						"n_seminar_lembaga"=>strtoupper($_POST['n_seminar_lembaga']),
						"a_seminar"=>strtoupper($_POST['a_seminar']),
						"e_keterangan"=>$_POST['e_keterangan'],
						"i_entry"=>$this->view->userid,
						"d_entry"=>date('Ymd'));		

	if ($_POST['proses']=='Simpan')
	{
		$hasil = $this->seminar_serv->maintainData($MaintainData,'insert');		
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
	}		
	else
	{
		$hasil = $this->seminar_serv->maintainData($MaintainData,'update');
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";		
	}	

	//$this->listDataByKey($this->view->nip,$_POST['d_mulai_seminar']);
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
		//$this->listDataByKey($this->view->nip,$_POST['n_seminar']);
}	

	$nip=$_POST['i_peg_nip'];	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->seminarList = $this->seminar_serv->getSeminarList($cari);	
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->render('listseminar');	
}

public function hapusdataAction() {
	$MaintainData = array("id"=>$_GET['id']);		
	$hasil = $this->seminar_serv->maintainData($MaintainData,'delete');	
	$pesan= "Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->listseminarAction();
	$this->render('listseminar');	
}
	
public function listDataByKey($id) {  
	$carilist = " and id='$id' ";
	$dataseminar=$this->seminar_serv->getSeminarList($carilist);	
	$this->view->i_peg_nip=$dataseminar[0]['i_peg_nip'];
	$this->view->n_seminar=$dataseminar[0]['n_seminar'];
	$this->view->d_mulai_seminar=$dataseminar[0]['d_mulai_seminar'];
	$this->view->d_akhir_seminar=$dataseminar[0]['d_akhir_seminar'];
	$this->view->n_seminar_peran=trim($dataseminar[0]['n_seminar_peran']);
	$this->view->n_seminar_lembaga=$dataseminar[0]['n_seminar_lembaga'];
	$this->view->a_seminar=$dataseminar[0]['a_seminar'];
	$this->view->e_keterangan=$dataseminar[0]['e_keterangan'];
	$this->view->i_entry=$dataseminar[0]['i_entry'];
	$this->view->d_entry=$dataseminar[0]['d_entry'];
}	
	
}
?>