<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_PenilaianMil_Service.php";
class Sdmmodule_PenilaianmilController extends Zend_Controller_Action {

		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu');
		$this->view->photoPath = $registry->get('photoPath');
		 
		$this->reff_serv = sdm_refferensi_Service::getInstance();
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->penilaian_serv = Sdm_PenilaianMil_Service::getInstance();
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
		$sesmenu = new Zend_Session_Namespace('sesmenu');
		$this->view->menu= $sesmenu->menu;
		
		$ssologin = new Zend_Session_Namespace('ssologin');
		$this->view->userid=$ssologin->userid;
		$this->view->sektoral			= $ssologin->arrayc_sektoral[1]; 
		$this->view->wewenang			= $ssologin->arrayc_wewenang[1]; 
		if($this->view->wewenang == 'O'){$this->view->c_izin='000002';}
		//$this->view->c_izin=$ssologin->c_izin[0]['c_izin'];				
    }
	
public function indexAction() {
    }

public function refpenilaianmilAction() {
	$this->view->refpenilaianList = $this->penilaian_serv->getPenilaianList('');
	if ($_GET['par']=='update'){$this->view->par="Ubah";$this->listDataByKey($_GET['c_nilai_kinerja']);}
	else{$this->view->par="Simpan";}
}
    
public function penilaianmiljsAction() {
	header('content-type : text/javascript');
	$this->render('penilaianmiljs');		
}	



public function maintaindatarefAction() {
		
	$maxck = $this->penilaian_serv->getMaxRefNilai();
 	if (!$maxck){$maxck=1;}
	else{$maxck=$maxck*1+1;}
	$MaintainData = array("c_nilai_kinerja"=>$maxck,
				"c_nilai_kinerja2"=>$_POST['c_nilai_kinerja2'],
				"n_faktor_kinerja"=>$_POST['n_faktor_kinerja'],
				"n_standar_kinerja"=>$_POST['n_standar_kinerja'],
				"q_nilai_dibawah"=>$_POST['q_nilai_dibawah'],
				"q_nilai_perbaikan"=>$_POST['q_nilai_perbaikan'],
				"q_nilai_perbaikan"=>$_POST['q_nilai_perbaikan'],
				"q_nilai_sesuai"=>$_POST['q_nilai_sesuai'],
				"q_nilai_diatas"=>$_POST['q_nilai_diatas'],
				"d_nilai_kinerja"=>$_POST['d_nilai_kinerja'],
				"i_entry"=>$this->view->userid,
				"d_entry"=>date('Ymd'));

	if ($_POST['proses']=='Simpan')
	{
		$hasil = $this->penilaian_serv->maintainDataRef($MaintainData,'insert');		
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
	}		
	else
	{
		$hasil = $this->penilaian_serv->maintainDataRef($MaintainData,'update');
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";		
	}
	
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->view->refpenilaianList = $this->penilaian_serv->getPenilaianList('');
	
	$this->render('refpenilaianmil');	
}

public function listDataByKey($c_nilai_kinerja) { 
	$carilist = " and c_nilai_kinerja='$c_nilai_kinerja' ";	
	$datarefnilai = $this->penilaian_serv->getPenilaianList($carilist);
	$this->view->c_nilai_kinerja=$datarefnilai[0]['c_nilai_kinerja'];
	$this->view->n_faktor_kinerja=$datarefnilai[0]['n_faktor_kinerja'];
	$this->view->n_standar_kinerja=$datarefnilai[0]['n_standar_kinerja'];
	$this->view->d_nilai_kinerja=$datarefnilai[0]['d_nilai_kinerja'];
	$this->view->q_nilai_dibawah=$datarefnilai[0]['q_nilai_dibawah'];
	$this->view->q_nilai_perbaikan=$datarefnilai[0]['q_nilai_perbaikan'];
	$this->view->q_nilai_sesuai=$datarefnilai[0]['q_nilai_sesuai'];
	$this->view->q_nilai_diatas=$datarefnilai[0]['q_nilai_diatas'];
}


public function penilaianmilAction() {
	$par=$_GET['par'];
	$tahun=$_GET['tahun'];
	$d_nilai_kinerja = $tahun."-01-01";
	if ($par=='insert'){
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
		$this->view->penilai=$_GET['penilai'];
		$this->view->refpenilaianList = $this->penilaian_serv->getPenilaianList('');
	}
	else{
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$id=$_GET['id'];
		$this->view->id=$id;
		$nip=$_GET['nip'];
		if (!$nip){$nip=$this->view->nip;}
		$i_peg_nip=trim($this->view->nip);
		$this->view->penilai=$_GET['penilai'];
		
		$cari =" and to_char(a.d_nilai_kinerja,'yyyy-mm-dd')= '$d_nilai_kinerja' and i_peg_nip='$i_peg_nip' ";
		
		
		$refpenilaianList = $this->penilaian_serv->getHasilPenilaianList($cari);
		$this->view->refpenilaianList=$refpenilaianList;
		$this->view->d_nilai_kinerja=$refpenilaianList[0]['d_nilai_kinerja'];
		$this->view->n_penilai=$refpenilaianList[0]['n_penilai'];
		$this->view->i_nip_penilai=$refpenilaianList[0]['i_nip_penilai'];
		
		  
	}
	$this->view->tahun=$tahun;
	// if ($_GET['penilai']=='1'){$this->view->penilai="Penjabat Penilai";$this->view->c_pers_penilai="1";}
	// else if ($_GET['penilai']=='2'){$this->view->penilai="Rekan Kerja I";$this->view->c_pers_penilai="2";}
	// else if ($_GET['penilai']=='3'){$this->view->penilai="Rekan Kerja II";$this->view->c_pers_penilai="3";}

	
}
public function penilaianmilubahAction() {
	$par			=$_GET['par'];
	$keydata		=$_GET['keydata'];
	$d_nilai_kinerja=$_GET['tahun'];
	$i_peg_nip		=$_GET['i_peg_nip'];
	
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$nip=$_GET['i_peg_nip'];
		if (!$nip){$nip=$this->view->nip;}
		
		$i_peg_nip=trim($this->view->nip);
		
		$cari =" and to_char(a.d_nilai_kinerja,'yyyy')= '$d_nilai_kinerja' and i_peg_nip='$i_peg_nip' and c_pers_penilai='$keydata'";
		
		
		$refpenilaianList = $this->penilaian_serv->getHasilPenilaianList($cari);
		$this->view->refpenilaianList=$refpenilaianList;
		$this->view->d_nilai_kinerja=$refpenilaianList[0]['d_nilai_kinerja'];
		$this->view->n_penilai=$refpenilaianList[0]['n_penilai'];
		$this->view->i_nip_penilai=$refpenilaianList[0]['i_nip_penilai'];
		$this->view->keys=$keydata;
		$this->view->tahun=$d_nilai_kinerja;
}
public function listpenilaianmilAction() {

	$i_peg_nip=trim($this->view->nip);
	$tahun = $_GET['d_nilai_kinerja'] ? $_GET['d_nilai_kinerja'] : date(Y);
	$d_nilai_kinerja = $tahun."-01-01";
		$cari = "  and i_peg_nip='$i_peg_nip' and to_char(a.d_nilai_kinerja,'yyyy-mm-dd')='$d_nilai_kinerja'";
	
	$this->view->d_nilai_kinerja=$_GET['d_nilai_kinerja'];
	$this->view->tahun= $tahun;
	$this->view->penilaianList = $this->penilaian_serv->getHasilPenilaianList($cari);	
}
public function hapusdataAction() {
	$i_peg_nip		=trim($_GET['i_peg_nip']);
	$d_nilai_kinerja= $_GET['d_nilai_kinerja']."-01-01";
	$tahuncurrent	= date(Y)."-01-01";
	$MaintainDataHapus = array("i_peg_nip"=>$_GET['i_peg_nip'],"d_nilai_kinerja"=>$d_nilai_kinerja);
	$hasil = $this->penilaian_serv->hapusData($MaintainDataHapus);
	
	$cari=" and to_char(a.d_nilai_kinerja,'yyyy-mm-dd')= '$tahuncurrent' and a.i_peg_nip='$i_peg_nip' "; //and a.c_pers_penilai ='$c_pers_penilai'"; 
	$this->view->penilaianList = $this->penilaian_serv->getHasilPenilaianList($cari);	
	$this->render('listpenilaianmil');		
}

public function maintaindataAction() {
	
	$d_nilai_kinerja= $_POST['d_nilai_kinerja']."-01-01";
	if ($_POST['proses']=='Simpan')
		{
			$array_data = array("1" => "Pejabat Penilai","2" => "Rekan Kerja I","3" => "Rekan Kerja II");
			$this->view->par="Simpan";
			$this->view->jdl="Menambah ";
			$par="Menambah";
		}		
		else
		{
			$c_pers_penilai= $_POST['c_pers_penilai'];
			if($c_pers_penilai==1)$array_data = array("1" => "Pejabat Penilai");
			else if($c_pers_penilai==2)$array_data = array("2" => "Rekan Kerja I");
			else if($c_pers_penilai==3)$array_data = array("3" => "Rekan Kerja II");
			$MaintainDataHapus = array("i_peg_nip"=>$_POST['i_peg_nip'],"d_nilai_kinerja"=>$d_nilai_kinerja,"c_pers_penilai"=>$c_pers_penilai);
			$hasil = $this->penilaian_serv->hapusData($MaintainDataHapus);
			$this->view->par="Ubah";
			$this->view->jdl="Merubah ";
			$par="Merubah";		
		}
		
	$i=1;
foreach($array_data as $keys => $values){
	$totcount		= $_POST['totcount'.$keys];
	$startcount		= $_POST['startcount'.$keys];
	
	for ($j = $startcount; $j <= $totcount*1; $j++) 
	{
		$idt	= $_POST['idt'.$j];
		
		$MaintainData = array("id"=>$idt,
					"i_peg_nip"=>$_POST['i_peg_nip'],
					"c_nilai_kinerja"=>$_POST['c_nilai_kinerja'.$j],
					"q_nilai_kinerja"=>$_POST['nilaitotal'.$j],
					"c_pers_penilai"=>$_POST['c_pers_penilai'.$keys],
					"i_nip_penilai"=>$_POST['i_nip_penilai'.$keys],
					"n_penilai"=>$_POST['n_penilai'.$keys],
					"d_nilai_kinerja"=>$d_nilai_kinerja,
					"i_entry"=>"test",
					"d_entry"=>date('Ymd'));
		$hasil = $this->penilaian_serv->maintainData($MaintainData,'insert');		
			
		$i++;
	}	
}	
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$i_peg_nip=trim($_POST['i_peg_nip']);
	
	$cari=" and to_char(a.d_nilai_kinerja,'yyyy-mm-dd')= '$d_nilai_kinerja' and a.i_peg_nip='$i_peg_nip' "; //and a.c_pers_penilai ='$c_pers_penilai'"; 
	// if ($_POST['c_pers_penilai']=='1'){$this->view->judul="Penjabat Penilai";}
	// else if ($_POST['c_pers_penilai']=='2'){$this->view->judul="Rekan Kerja I";}
	// else if ($_POST['c_pers_penilai']=='3'){$this->view->judul="Rekan Kerja II";}	
	$this->view->tahun = $_POST['d_nilai_kinerja'];
	$this->view->penilaianList = $this->penilaian_serv->getHasilPenilaianList($cari);	
	$this->render('listpenilaianmil');	
}
	
}
?>