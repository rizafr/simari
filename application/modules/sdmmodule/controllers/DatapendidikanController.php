<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_Pendidikan_Service.php";

class Sdmmodule_DataPendidikanController extends Zend_Controller_Action {
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu'); 		
		$this->reff_serv = sdm_refferensi_Service::getInstance();
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->pendidikan_serv = Sdm_Pendidikan_Service::getInstance();
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
public function pendidikanjsAction() 
{
	header('content-type : text/javascript');
	$this->render('pendidikanjs');
}	
	
public function listpendidikanAction() {
	$nip=$this->view->nip;	
	if (!$nip){$nip=$_GET['nip'];}
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->pendList = $this->pendidikan_serv->getPendidikanList($cari);	
	$this->listDataByKeyPeg($nip);
}
public function pendidikanAction() {

	$par=$_GET['par'];
	$jenisPend=$_GET['jenisPend'];
	$this->view->nmJenjangList = $this->reff_serv->getPendidikan($cari);
	if ($par=='insert'){
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
	}
	else if ($par=='delete'){
		$this->view->par="Hapus";
		$this->view->jdl="Manghapus ";	
		//$this->listDataByKey($this->view->nip,$jenisPend);
		$this->listDataByKey2($id);
		$this->view->jenipend=trim($jenisPend);
	}	
	else{
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";	
		$this->view->id  = $_GET['id'];	
		$id=$_GET['id'];
		//$this->listDataByKey($this->view->nip,$jenisPend);
		$this->listDataByKey2($id);
		$this->view->jenipend=trim($jenisPend);
		if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Merubah ";}		
		else{$this->view->jdl="Melihat ";$this->render('pendidikanview');}			
	}
}
public function maintaindataAction() {
		if ($_POST['d_pend_ijazah'])
		{
			$d_pend_ijazah1=substr($_POST['d_pend_ijazah'],0,2);
			$d_pend_ijazah2=substr($_POST['d_pend_ijazah'],3,2);
			$d_pend_ijazah3=substr($_POST['d_pend_ijazah'],6,4);
		}
		$d_pend_ijazah=$d_pend_ijazah3."-".$d_pend_ijazah2."-".$d_pend_ijazah1;
		
		$i_pend_ipk=$_POST['i_pend_ipk'];
		if (!$d_pend_ijazah1){$d_pend_ijazah=null;}
		if (!$i_pend_ipk){$i_pend_ipk=null;$cektgl=true;}
		else{$cektgl=checkdate($d_pend_ijazah2,$d_pend_ijazah1,$d_pend_ijazah3);}
if ($cektgl==true)
{
	$MaintainData = array(
		"id"=>$_POST['id'],
		"i_peg_nip"=>strtoupper($_POST['i_peg_nip']),
		"a_pend_alamat"=>strtoupper($_POST['a_pend_alamat']),
		"c_pend"=>$_POST['c_pend'],
		"c_pend_jenis"=>"F",		
		"c_pend_sumberdana"=>$_POST['c_pend_sumberdana'],
		"d_pend_akhir"=>$_POST['d_pend_akhir'],
		"d_pend_ijazah"=>$d_pend_ijazah,
		"d_pend_mulai"=>$_POST['d_pend_mulai'],
		"e_keterangan"=>$_POST['e_keterangan'],
		"e_pend_skripsi"=>$_POST['e_pend_skripsi'],
		"i_pend_ipk"=>$i_pend_ipk,
		"i_pend_ijazah"=>$_POST['i_pend_ijazah'],
		"n_pend_jurusan"=>strtoupper($_POST['n_pend_jurusan']),
		"n_pend_kepsek"=>strtoupper($_POST['n_pend_kepsek']),
		"n_pend_lembaga"=>strtoupper($_POST['n_pend_lembaga']),
		"c_akreditasi"=>strtoupper($_POST['c_akreditasi']),
		"i_entry"=>$this->view->userid,
		"d_entry"=>date('Ymd'));
		$this->jenipend=$_POST['c_pend'];

	if ($_POST['proses']=='Simpan')
	{
		$hasil = $this->pendidikan_serv->maintainData($MaintainData,'insert');		
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
	}
	else if ($_POST['proses']=='Hapus')
	{
		$hasil = $this->pendidikan_serv->maintainData($MaintainData,'delete');		
		$this->view->par="Hapus";
		$this->view->jdl="Menghapus ";
		$par="Menghapus";
	}	
	else
	{
		
		$hasil = $this->pendidikan_serv->maintainData($MaintainData,'update');
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";		
	}
	
	if ($hasil=='sukses')
	{
		$this->updateTmPegawai($_POST['i_peg_nip']);
		if ($_POST['proses']=='Hapus')
		{
			$this->view->par="Hapus";
			$this->view->jdl="Menghapus ";
			$par="Menghapus";
		}
		else
		{
			$this->view->par="Ubah";
			$this->view->jdl="Merubah ";
			$par="Merubah";				
		}
	}	
		//$this->listDataByKey($this->view->nip,$_POST['c_pend']);
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
		$this->listDataByKey($this->view->nip,$_POST['c_pend']);
		$this->view->d_pend_ijazah=$_POST['d_pend_ijazah'];
		
}	
	$this->view->nmJenjangList = $this->reff_serv->getPendidikan($cari);

	$this->view->jenipend=trim($_POST['c_pend']);
	
	$nip=$_POST['i_peg_nip'];	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->pendList = $this->pendidikan_serv->getPendidikanList($cari);	
	
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	//if ($_POST['proses']=='Hapus'){
		//$this->listpendidikanAction();
		$this->render('listpendidikan');
	//	}
	//else{
	//$this->render('pendidikan');
	//}		
}
public function hapusdataAction() {
	//$MaintainData = array("i_peg_nip"=>($this->view->nip),"c_pend"=>$_GET['c_pend']);		
	$MaintainData = array("id"=>$_GET['id']);		
	$hasil = $this->pendidikan_serv->maintainData($MaintainData,'delete');	
	$this->updateTmPegawai($_GET['nip']);
	$pesan= "Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->listpendidikanAction();
	$this->render('listpendidikan');	
}
public function pendidikanaAction() {
	$this->view->c_pend=$_GET['c_pend'];
	$this->view->nmJenjangList = $this->reff_serv->getPendidikan($cari);
}
public function pendidikanbAction() {
	$this->view->c_pend=$_GET['c_pend'];
	$this->view->nmJenjangList = $this->reff_serv->getPendidikan($cari);
}	 

	
public function listDataByKey($nip,$jenisPend) {  
	$carilist = " and i_peg_nip='$nip'  ".($jenisPend ? "and c_pend='$jenisPend' " :"");
	$datapend=$this->pendidikan_serv->getPendidikanList($carilist); 
	$this->view->c_pend=$datapend [0]['c_pend'];
	$this->view->n_pend_jurusan=$datapend [0]['n_pend_jurusan'];
	$this->view->n_pend=$datapend [0]['n_pend'];
	$this->view->n_pend_lembaga=$datapend [0]['n_pend_lembaga'];
	$this->view->a_pend_alamat=$datapend [0]['a_pend_alamat'];	
	$this->view->n_pend_kepsek=$datapend [0]['n_pend_kepsek'];
	$this->view->d_pend_mulai=$datapend [0]['d_pend_mulai'];
	$this->view->d_pend_akhir=$datapend [0]['d_pend_akhir'];
	$this->view->i_pend_ipk=$datapend [0]['i_pend_ipk'];
	$this->view->e_pend_skripsi=$datapend [0]['e_pend_skripsi'];
	$this->view->d_pend_ijazah=$datapend [0]['d_pend_ijazah'];
	$this->view->c_pend_sumberdana=$datapend [0]['c_pend_sumberdana'];
	$this->view->i_pend_ijazah=$datapend [0]['i_pend_ijazah'];
	$this->view->e_keterangan=$datapend [0]['e_keterangan'];
	$this->view->c_akreditasi=$datapend [0]['c_akreditasi'];
	
    }	
 public function listDataByKey2($id) {  
	$carilist = " and id='$id' ";
	$datapend=$this->pendidikan_serv->getPendidikanList($carilist); 
	$this->view->c_pend=$datapend [0]['c_pend'];
	$this->view->n_pend_jurusan=$datapend [0]['n_pend_jurusan'];
	$this->view->n_pend=$datapend [0]['n_pend'];
	$this->view->n_pend_lembaga=$datapend [0]['n_pend_lembaga'];
	$this->view->a_pend_alamat=$datapend [0]['a_pend_alamat'];	
	$this->view->n_pend_kepsek=$datapend [0]['n_pend_kepsek'];
	$this->view->d_pend_mulai=$datapend [0]['d_pend_mulai'];
	$this->view->d_pend_akhir=$datapend [0]['d_pend_akhir'];
	$this->view->i_pend_ipk=$datapend [0]['i_pend_ipk'];
	$this->view->e_pend_skripsi=$datapend [0]['e_pend_skripsi'];
	$this->view->d_pend_ijazah=$datapend [0]['d_pend_ijazah'];
	$this->view->c_pend_sumberdana=$datapend [0]['c_pend_sumberdana'];
	$this->view->i_pend_ijazah=$datapend [0]['i_pend_ijazah'];
	$this->view->e_keterangan=$datapend [0]['e_keterangan'];
	$this->view->c_akreditasi=$datapend [0]['c_akreditasi'];
	
    }	
	
public function updateTmPegawai($nip) {  
	$carilist = " and i_peg_nip='$nip' and d_pend_mulai in (select max(d_pend_mulai) from sdm.tm_pendidikan where i_peg_nip='$nip') ";
	$datapend=$this->pendidikan_serv->getPendidikanList($carilist); 
	
	$countdata=count($datapend);	

if ($countdata > 0){	
		
		$MaintainData = array("i_peg_nip"=>$nip,
								"c_pend_jenis"=>$datapend [0]['c_pend_jenis'],
								"c_pend"=>$datapend [0]['c_pend'],
								"d_pend_mulai"=>$datapend [0]['d_pend_mulai'],
								"d_pend_akhir"=>$datapend [0]['d_pend_akhir'],
								"n_pend_jurusan"=>$datapend [0]['n_pend_jurusan'],
								"n_pend_lembaga"=>$datapend [0]['n_pend_lembaga']);
		
}	else {
	$MaintainData = array("i_peg_nip"=>$nip,
								"c_pend_jenis"=>'',
								"c_pend"=>'',
								"d_pend_mulai"=>null,
								"d_pend_akhir"=>null,
								"n_pend_jurusan"=>'',
								"n_pend_lembaga"=>'');
}
	$hasil = $this->pendidikan_serv->updateTmPegawai($MaintainData);	

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