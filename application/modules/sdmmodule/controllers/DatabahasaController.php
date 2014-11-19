<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_Bahasa_Service.php";
require_once "service/sdm/sdm_refferensi_Service.php";
class Sdmmodule_DatabahasaController extends Zend_Controller_Action {
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu'); 
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->bahasa_serv = Sdm_Bahasa_Service::getInstance();
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
		$this->view->c_izin=$ssologin->c_izin[0]['c_izin'];			
    }
	
    public function indexAction() {
	   
    }
public function bahasajsAction() 
{
	header('content-type : text/javascript');
	$this->render('bahasajs');
}	
	
public function listbahasaAction() {
	$nip=$this->view->nip;	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->bahasaList = $this->bahasa_serv->getBahasaList($cari);	
}
public function bahasaAction() {

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
		else{$this->view->jdl="Melihat ";$this->render('bahasaview');}			
	} 
	
	
}
public function maintaindataAction() {
 		if ($_POST['d_test_kemampuan'])
		{
			$d_test_kemampuan1=substr($_POST['d_test_kemampuan'],0,2);
			$d_test_kemampuan2=substr($_POST['d_test_kemampuan'],3,2);
			$d_test_kemampuan3=substr($_POST['d_test_kemampuan'],6,4);
		}
		$d_test_kemampuan=$d_test_kemampuan3."-".$d_test_kemampuan2."-".$d_test_kemampuan1;
		if (!$_POST['d_test_kemampuan']){$d_test_kemampuan=null;$cektgltest=true;}
		else{$cektgltest=checkdate($d_test_kemampuan2,$d_test_kemampuan1,$d_test_kemampuan3);}

 		if ($_POST['d_tmt_berlaku'])
		{
			$d_tmt_berlaku1=substr($_POST['d_tmt_berlaku'],0,2);
			$d_tmt_berlaku2=substr($_POST['d_tmt_berlaku'],3,2);
			$d_tmt_berlaku3=substr($_POST['d_tmt_berlaku'],6,4);
		}
		$d_tmt_berlaku=$d_tmt_berlaku3."-".$d_tmt_berlaku2."-".$d_tmt_berlaku1;
		if (!$_POST['d_tmt_berlaku']){$d_tmt_berlaku=null;$cektglberlaku=true;}
		else{$cektglberlaku=checkdate($d_tmt_berlaku2,$d_tmt_berlaku1,$d_tmt_berlaku3);}
		

 		if ($_POST['d_test_kemampuan2'])
		{
			$d_test_kemampuan21=substr($_POST['d_test_kemampuan2'],0,2);
			$d_test_kemampuan22=substr($_POST['d_test_kemampuan2'],3,2);
			$d_test_kemampuan23=substr($_POST['d_test_kemampuan2'],6,4);
		}
		$d_test_kemampuan2=$d_test_kemampuan23."-".$d_test_kemampuan22."-".$d_test_kemampuan21;		
		
if (($cektgltest==true) )
{	


	$MaintainData = array("id"=>$_POST['id'],
				"i_peg_nip"=>$_POST['i_peg_nip'],
				"q_nilai"=>$_POST['q_nilai']*1,
				"n_penyelenggara"=>$_POST['n_penyelenggara'],
				"e_tujuan"=>$_POST['e_tujuan'],
				"e_bahasa"=>$_POST['e_bahasa'],
				"d_test_kemampuan"=>$d_test_kemampuan,
				"d_test_kemampuan2"=>$d_test_kemampuan2,
				"d_tmt_berlaku"=>$d_tmt_berlaku,
				"i_entry"=>$this->view->userid,
				"d_entry"=>date('Ymd'));		

	if ($_POST['proses']=='Simpan')
	{
		$hasil = $this->bahasa_serv->maintainData($MaintainData,'insert');		
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
	}		
	else
	{
		$hasil = $this->bahasa_serv->maintainData($MaintainData,'update');
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";		
	}	
	$id=$_POST['id'];
	$this->view->id=$id;	
	$this->listDataByKey($id);
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
		$id=$_POST['id'];
	$this->view->id=$id;	
	$this->listDataByKey($id);
}	
	
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$nip=$this->view->nip;	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->bahasaList = $this->bahasa_serv->getBahasaList($cari);	
	$this->render('listbahasa');	
}

public function hapusdataAction() {
 	$id = $_GET['id'];
	$MaintainData = array("id"=>$id);		
	$hasil = $this->bahasa_serv->maintainData($MaintainData,'delete');	
	$pesan= "Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->listbahasaAction();
	
	$nip=$this->view->nip;	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->bahasaList = $this->bahasa_serv->getBahasaList($cari);	
	
	$this->render('listbahasa');	
}
	
public function listDataByKey($id) { 
	if($id){
		$carilist = " and id='$id' ";
	$databahasa=$this->bahasa_serv->getBahasaList($carilist);	
	
	$this->view->i_peg_nip=$databahasa[0]['i_peg_nip'];
	$this->view->d_test_kemampuan=$databahasa[0]['d_test_kemampuan'];
	$this->view->q_nilai=$databahasa[0]['q_nilai'];
	$this->view->n_penyelenggara=$databahasa[0]['n_penyelenggara'];
	$this->view->e_tujuan=$databahasa[0]['e_tujuan'];
	$this->view->d_tmt_berlaku=$databahasa[0]['d_tmt_berlaku'];
	$this->view->e_bahasa=$databahasa[0]['e_bahasa'];
	$this->view->i_entry=$databahasa[0]['i_entry'];
	$this->view->d_entry=$databahasa[0]['d_entry'];
	}
}	
	
}
?>