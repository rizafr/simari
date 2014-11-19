<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_DiklatPenjenjangan_Service.php";
require_once "service/sdm/sdm_refferensi_Service.php";
class Sdmmodule_DatadikpenjenjanganController extends Zend_Controller_Action {
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu'); 
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->pelatihan_serv = Sdm_DiklatPenjenjangan_Service::getInstance();
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
public function dikpenjenjanganjsAction() 
{
	header('content-type : text/javascript');
	$this->render('dikpenjenjanganjs');
}	
	
public function listdiklatAction() {
	$nip=$this->view->nip;
	if(!$nip){$nip=$_GET['nip'];}
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->pelatihanList = $this->pelatihan_serv->getPelatihanList($cari);
	$this->listDataByKeyPeg($nip);	
}

public function penjenjanganAction() {
	$par=$_GET['par'];
		$this->view->listrpenjenjangan=$this->reff_serv->getTrPenjenjangan('');	
		$this->view->listrkualifikasi=$this->reff_serv->getTrDiklatKualifikasi('');	
		
	if ($par=='insert'){
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
	}
	else if ($par=='delete'){
		$this->view->par="Hapus";
		$this->view->jdl="Manghapus ";	
		$this->listDataByKey($this->view->nip,$_GET['id']);	
	}	
	else{	
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";	
		$this->view->id = $_GET['id'];	
		$this->listDataByKey($this->view->nip,$_GET['id']);	
		if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Merubah ";}		
		else{$this->view->jdl="Melihat ";$this->render('penjenjanganview');}			
	}			
		

	
}

public function maintaindatajenjangAction() {
	$nip=$_POST['i_peg_nip'];
 	if ($_POST['d_sertifikat'])
		{
			$d_sertifikat1=substr($_POST['d_sertifikat'],0,2);
			$d_sertifikat2=substr($_POST['d_sertifikat'],3,2);
			$d_sertifikat3=substr($_POST['d_sertifikat'],6,4);
		}
		$d_sertifikat=$d_sertifikat3."-".$d_sertifikat2."-".$d_sertifikat1;
	if (!$_POST['d_sertifikat']){$d_sertifikat=null;$cektglmulai=true;}
	else{$cekd_sertifikat=checkdate($d_sertifikat2,$d_sertifikat1,$d_sertifikat3);}
		
	//if ($cekd_sertifikat==true)
	//{
		$MaintainData = array("id"=>$_POST['id'],
								"i_peg_nip"=>$_POST['i_peg_nip'],
								"c_penjenjangan2"=>$_POST['c_penjenjangan2']*1,
								"c_penjenjangan"=>$_POST['c_penjenjangan']*1,
								"c_kualifikasi"=>$_POST['c_kualifikasi']*1,
								"q_angkatan"=>$_POST['q_angkatan'],
								"q_tahun"=>$_POST['q_tahun']*1,
								"q_lama"=>$_POST['q_lama']*1,
								"q_peringkat"=>$_POST['q_peringkat']*1,
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
			$par="Merubah";	
			
		}
		//$this->listDataByKey($this->view->nip,$_POST['c_penjenjangan']);		
	//}	
		$this->view->listrpenjenjangan=$this->reff_serv->getTrPenjenjangan('');	
		$this->view->listrkualifikasi=$this->reff_serv->getTrDiklatKualifikasi('');		

	$this->updateTmPegawai($nip,'');	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->pelatihanList = $this->pelatihan_serv->getPelatihanList($cari);
	$this->listDataByKeyPeg($nip);	
	
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;		
	$this->render('listdiklat');	
	
}

      public function listDataByKey($nip,$id) {  
		$cari = " and i_peg_nip ='$nip' and id='$id'";
		$datalatih = $this->pelatihan_serv->getPelatihanList($cari);		
		
		$this->view->id=$datalatih [0]['id'];	
		$this->view->c_penjenjangan=$datalatih [0]['c_penjenjangan'];
		$this->view->c_kualifikasi=$datalatih [0]['c_kualifikasi'];
		$this->view->q_angkatan=$datalatih [0]['q_angkatan'];
		$this->view->q_tahun=$datalatih [0]['q_tahun'];
		$this->view->q_lama=$datalatih [0]['q_lama'];
		$this->view->q_peringkat=$datalatih [0]['q_peringkat'];
		$this->view->n_penyelenggara=$datalatih [0]['n_penyelenggara'];
		$this->view->i_sertifikat=$datalatih [0]['i_sertifikat'];
		$this->view->d_sertifikat=$datalatih [0]['d_sertifikat'];
		$this->view->n_pejabat=$datalatih [0]['n_pejabat'];	
		
    }
public function hapusdataAction() {
	$MaintainData = array("id"=>$_GET['id']);		
	$hasil = $this->pelatihan_serv->maintainData($MaintainData,'delete');	
	$this->updateTmPegawai($_GET['nip'],'');
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

public function updateTmPegawai($nip,$jnsnaik) { 


	$carilist = " and i_peg_nip='$nip'  and q_tahun in (select max(q_tahun) from sdm.tm_pelatihan_penjenjangan where i_peg_nip='$nip' ) ";
	
	$dklList = $this->pelatihan_serv->getPelatihanList1($carilist);
	
	$countdata=count($dklList);	
if ($countdata > 0){

			$MaintainData = array("i_peg_nip"=>$nip,
								"c_penjenjangan"=>$dklList[0]['c_penjenjangan'],
							"q_angkatan"=>$dklList[0]['q_angkatan'],
							"q_tahun"=>$dklList[0]['q_tahun'],
							"c_kualifikasi"=>$dklList[0]['c_kualifikasi']*1);
							
		$hasil = $this->pelatihan_serv->updateTmPegawaiPenjenjangan($MaintainData);
	}

	
		
		

}	

}
?>