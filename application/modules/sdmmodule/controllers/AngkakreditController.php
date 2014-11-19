<?php
require_once 'Zend/Controller/Action.php';
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_Angkakredit_Service.php";
class Sdmmodule_AngkakreditController extends Zend_Controller_Action
{
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
	 	$registry = Zend_Registry::getInstance();
	   	$this->view->basePath = $registry->get('basepath');		
		$this->view->leftMenu = $registry->get('leftMenu');
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->ak_serv = Sdm_Angkakredit_Service::getInstance();

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
	public function indexAction()
	{
	}
	public function angkakreditjsAction() 
	{
		header('content-type : text/javascript');
		$this->render('angkakreditjs');
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
	
	public function listangkakreditAction() 
	{
		if ($_GET['nip']){$i_peg_nip=$_GET['nip'];}
		else{$i_peg_nip=$this->view->nip;}		
		$cari=" and i_peg_nip='$i_peg_nip'";
		$this->view->akList = $this->ak_serv->getAngkaKreditList($cari);
	    	
	}
	public function angkakreditAction() 
	{
		$par=$_GET['par'];
		$this->view->i_peg_nip=$_GET['i_peg_nip'];
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
			$this->listDataByKey($_GET['id']);
			
		if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Merubah ";}		
		else{$this->view->jdl="Melihat ";$this->render('angkakreditview');}				
		}	    	
	}

public function maintaindataAction() {
 		if ($_POST['d_sk'])
		{
			$d_sk1=substr($_POST['d_sk'],0,2);
			$d_sk2=substr($_POST['d_sk'],3,2);
			$d_sk3=substr($_POST['d_sk'],6,4);
		}
		$d_sk=$d_sk3."-".$d_sk2."-".$d_sk1;
		if (!$_POST['d_sk']){$d_sk=null;$ceksk=true;}
		else{$ceksk=checkdate($d_sk2,$d_sk1,$d_sk3);}	
if ($ceksk==true)
{
		$MaintainData = array("id"=>$_POST['id'],
							"i_peg_nip"=>$_POST['i_peg_nip'],
								"d_peg_pnilai"=>$_POST['d_peg_pnilai'],
								"q_utama"=>$_POST['q_utama']*1,								
								"q_pendidikan"=>$_POST['q_pendidikan']*1,
								"q_keg_teknis"=>$_POST['q_keg_teknis']*1,
								"q_profesi"=>$_POST['q_profesi']*1,						
								"q_penunjang"=>$_POST['q_penunjang']*1,
								"q_totalnilai"=>$_POST['q_totalnilai']*1,
								"a_lembaga"=>$_POST['a_lembaga'],
								"i_sk"=>$_POST['i_sk'],
								"d_sk"=>$d_sk,
								"n_sk_pejabat"=>$_POST['n_sk_pejabat'],
								"i_entry"=>$this->view->userid,
								"d_entry"=>date('Ymd'));													

		if ($_POST['proses']=='Simpan')
		{
			$hasil = $this->ak_serv->maintainData($MaintainData,'insert');		
			$this->view->par="Simpan";
			$this->view->jdl="Menambah ";
			$par="Menambah";
		}		
		else
		{
			$hasil = $this->ak_serv->maintainData($MaintainData,'update');
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
		
	$i_peg_nip=$_POST['i_peg_nip'];	
	$cari=" and i_peg_nip='$i_peg_nip'";
	$this->view->akList = $this->ak_serv->getAngkaKreditList($cari);
		
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;		
	$this->render('listangkakredit');	
	
}	

public function hapusdataAction() {
		$MaintainData = array("id"=>$_GET['id']);													

			$hasil = $this->ak_serv->maintainData($MaintainData,'delete');		
			$this->view->par="Hapus";
	
	$i_peg_nip=$_GET['i_peg_nip'];	
	$cari=" and i_peg_nip='$i_peg_nip'";
	$this->view->akList = $this->ak_serv->getAngkaKreditList($cari);
		
	$pesan="Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;		
	$this->render('listangkakredit');	
	
}
public function listDataByKey($id) {  
	//echo $carilist;
	$carilist = " and id='$id' ";
	$dataak=$this->ak_serv->getAngkaKreditList($carilist);
	$this->view->i_peg_nip=$dataak[0]['i_peg_nip'];
	$this->view->d_peg_pnilai=$dataak[0]['d_peg_pnilai'];
	$this->view->q_utama=$dataak[0]['q_utama'];	
	$this->view->q_pendidikan=$dataak[0]['q_pendidikan'];
	$this->view->q_keg_teknis=$dataak[0]['q_keg_teknis'];
	$this->view->q_profesi=$dataak[0]['q_profesi'];
	$this->view->q_penunjang=$dataak[0]['q_penunjang'];
	$this->view->q_totalnilai=$dataak[0]['q_totalnilai'];
	$this->view->a_lembaga=$dataak[0]['a_lembaga'];
	$this->view->i_sk=$dataak[0]['i_sk'];
	$this->view->d_sk=$dataak[0]['d_sk'];
	$this->view->n_sk_pejabat=$dataak[0]['n_sk_pejabat'];
}	
	
public function SetNilai($nilaiAngka,$field2){
	if (($nilaiAngka*1 >=90) && ($nilaiAngka*1 <=100)){$this->view->$field2="Amat Baik";}
	if (($nilaiAngka*1 >=76) && ($nilaiAngka*1 <=90)){$this->view->$field2="Baik";}
	if (($nilaiAngka*1 >=60) && ($nilaiAngka*1 <=75)){$this->view->$field2="Cukup";}
	if (($nilaiAngka*1 >=51) && ($nilaiAngka*1 <=60)){$this->view->$field2="Sedang";}
	if (($nilaiAngka*1 <=50)){$this->view->$field2="Kurang";}	
}
	
}

?>