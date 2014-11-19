<?php
require_once 'Zend/Controller/Action.php';
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_Dp3_Service.php";
require_once "service/sdm/sdm_refferensi_Service.php";
class Sdmmodule_DptigaController extends Zend_Controller_Action
{
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
	 	$registry = Zend_Registry::getInstance();
	   	$this->view->basePath = $registry->get('basepath');
		$this->view->leftMenu = $registry->get('leftMenu');
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->dptiga_serv = Sdm_Dp3_Service::getInstance();
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
		$this->view->ceselon= $sespeg->ceselon;
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
	public function dptigajsAction() 
	{
		header('content-type : text/javascript');
		$this->render('dptigajs');
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
	public function listdptigaAction() 
	{
		if ($_GET['nip']){$i_peg_nip=$_GET['nip'];}
		else{$i_peg_nip=$this->view->nip;}		
		$cari=" and i_peg_nip='$i_peg_nip'";
		$this->view->dptigaList = $this->dptiga_serv->getDp3List($cari);
	    	
	}
	public function dptigaAction() 
	{
		$par=$_GET['par'];
		$this->view->i_peg_nip=$_GET['i_peg_nip'];
		
		$carigol=" and c_peg_tipegolongan in ('1','2','3') ";
		$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai($carigol);			
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
			//$this->listDataByKey($id);
		
			$this->listDataByKey($_GET['id']);
			
		if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Merubah ";}		
		else{$this->view->jdl="Melihat ";$this->render('dptigaview');}				
		}

	
	}

public function maintaindataAction() {


	
  
		$MaintainData = array("id"=>$_POST['id'],
								"i_peg_nip"=>$_POST['i_peg_nip'],
								"c_peg_golongan"=>$_POST['c_peg_golongan'],
								"d_peg_pnilai"=>$_POST['d_peg_pnilai'],
								"d_peg_pnilaiawal"=>$_POST['d_peg_pnilaiawal'],
								"d_peg_pnilaiakhir"=>$_POST['d_peg_pnilaiakhir'],
								"i_peg_nippnilai"=>$_POST['i_peg_nippnilai'],
								"i_peg_nipatasanpnilai"=>$_POST['i_peg_nipatasanpnilai'],
								"n_jabatanpenilai"=>$_POST['n_jabatan_pnilai'],
								"n_jabatanatasan"=>$_POST['n_jabatan_atasanpnilai'],
								"q_peg_kesetiaan"=>$_POST['q_peg_kesetiaan']*1,
								"q_peg_preskerja"=>$_POST['q_peg_preskerja']*1,
								"q_peg_tggjawab"=>$_POST['q_peg_tggjawab']*1,
								"q_peg_ketaatan"=>$_POST['q_peg_ketaatan']*1,
								"q_peg_kejujuran"=>$_POST['q_peg_kejujuran']*1,
								"q_peg_kerjasama"=>$_POST['q_peg_kerjasama']*1,
								"q_peg_prakarsa"=>$_POST['q_peg_prakarsa']*1,
								"q_peg_kpimpinan"=>$_POST['q_peg_kpimpinan']*1,
								"q_peg_totalnilai"=>$_POST['q_peg_totalnilai']*1,
								"q_peg_nilairata"=>$_POST['q_peg_nilairata']*1,
								"e_peg_keberatan"=>$_POST['e_peg_keberatan'],
								"e_peg_tgpanpnilai"=>$_POST['e_peg_tgpanpnilai'],
								"e_peg_kputusanatasan"=>$_POST['e_peg_kputusanatasan'],		
								"i_entry"=>$this->view->userid,
								"d_entry"=>date('Ymd'));

		if ($_POST['proses']=='Simpan')
		{
			$d_peg_pnilai=$_POST['d_peg_pnilai'];
			$i_peg_nip=$_POST['i_peg_nip'];
			$caricek=" and i_peg_nip='$i_peg_nip' and d_peg_pnilai='$d_peg_pnilai'";
			$cekData = $this->dptiga_serv->getDp3List($caricek);
			
			if (count($cekData)==0){
				$hasil = $this->dptiga_serv->maintainData($MaintainData,'insert');		
				$this->view->par="Simpan";
				$this->view->jdl="Menambah ";
				$par="Menambah";
				$i_peg_nip=$_POST['i_peg_nip'];	
				$cari=" and i_peg_nip='$i_peg_nip'";
				$this->view->dptigaList = $this->dptiga_serv->getDp3List($cari);
				$pesan=$par." data ".$hasil;
				$this->view->pesan = $pesan;
				$this->view->pesancek = $hasil;		
				$this->render('listdptiga');					
			}
			else{
				$hasil=" Gagal data sudah ada";
				$this->view->par="Simpan";
				$this->view->jdl="Menambah ";
				$par="Menambah";
				$this->listDataBack();
				$pesan=$par." data ".$hasil;
				$this->view->pesan = $pesan;
				$this->view->pesancek = $hasil;	
				$carigol=" and c_peg_tipegolongan in ('1','2','3') ";
				$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai($carigol);				
				$this->render('dptiga');	
				
			
			}
		}		
		else
		{
			$hasil = $this->dptiga_serv->maintainData($MaintainData,'update');
			$this->view->par="Ubah";
			$this->view->jdl="Merubah ";
			$par="Merubah";	
			$i_peg_nip=$_POST['i_peg_nip'];	
			$cari=" and i_peg_nip='$i_peg_nip'";
			$this->view->dptigaList = $this->dptiga_serv->getDp3List($cari);
			$pesan=$par." data ".$hasil;
			$this->view->pesan = $pesan;
			$this->view->pesancek = $hasil;		
			$this->render('listdptiga');				
		}

	
		

	
}	

public function hapusdataAction() {
		$MaintainData = array("id"=>$_GET['id']);													

			$hasil = $this->dptiga_serv->maintainData($MaintainData,'delete');				
			$this->view->par="Hapus";
	
	$i_peg_nip=$_GET['i_peg_nip'];	
	$cari=" and i_peg_nip='$i_peg_nip'";
	$this->view->dptigaList = $this->dptiga_serv->getDp3List($cari);
		
	$pesan="Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;		
	$this->render('listdptiga');	
	
}
public function listDataByKey($id) {
	
	$carilist = "  and id='$id' ";
	$datadptiga=$this->dptiga_serv->getDp3List($carilist);
	$this->view->i_peg_nip=$datadptiga[0]['i_peg_nip'];
	$this->view->n_peg=$datadptiga[0]['n_peg'];
	$this->view->c_peg_golongan=$datadptiga[0]['c_peg_golongan'];
	$this->view->n_pangkat=$datadptiga[0]['n_pangkat'];
	$this->view->c_jabatan=$datadptiga[0]['c_jabatan'];
	$this->view->n_jabatan=$datadptiga[0]['n_jabatan'];
	$this->view->d_peg_pnilai=$datadptiga[0]['d_peg_pnilai'];
	$this->view->d_peg_pnilaiawal=$datadptiga[0]['d_peg_pnilaiawal'];
	$this->view->d_peg_pnilaiakhir=$datadptiga[0]['d_peg_pnilaiakhir'];
	$this->view->i_peg_nippnilai=$datadptiga[0]['i_peg_nippnilai'];
	$this->view->n_peg_nippnilai=$datadptiga[0]['n_peg_nippnilai'];
	$this->view->n_jabatan_pnilai=$datadptiga[0]['n_jabatanpenilai'];
	$this->view->i_peg_nipatasanpnilai=$datadptiga[0]['i_peg_nipatasanpnilai'];
	$this->view->n_peg_nipatasanpnilai=$datadptiga[0]['n_peg_nipatasanpnilai'];
	$this->view->n_jabatan_atasanpnilai=$datadptiga[0]['n_jabatanatasan'];
	
	$this->view->q_peg_kesetiaan=$datadptiga[0]['q_peg_kesetiaan'];
	$this->SetNilai($datadptiga[0]['q_peg_kesetiaan'],'e_peg_kesetiaan');
	$this->view->q_peg_preskerja=$datadptiga[0]['q_peg_preskerja'];
	$this->SetNilai($datadptiga[0]['q_peg_preskerja'],'e_peg_preskerja');
	$this->view->q_peg_tggjawab=$datadptiga[0]['q_peg_tggjawab'];
	$this->SetNilai($datadptiga[0]['q_peg_tggjawab'],'e_peg_tggjawab');
	$this->view->q_peg_ketaatan=$datadptiga[0]['q_peg_ketaatan'];
	$this->SetNilai($datadptiga[0]['q_peg_ketaatan'],'e_peg_ketaatan');
	$this->view->q_peg_kejujuran=$datadptiga[0]['q_peg_kejujuran'];
	$this->SetNilai($datadptiga[0]['q_peg_kejujuran'],'e_peg_kejujuran');
	$this->view->q_peg_kerjasama=$datadptiga[0]['q_peg_kerjasama'];
	$this->SetNilai($datadptiga[0]['q_peg_kerjasama'],'e_peg_kerjasama');
	$this->view->q_peg_prakarsa=$datadptiga[0]['q_peg_prakarsa'];
	$this->SetNilai($datadptiga[0]['q_peg_kesetiaan'],'e_peg_prakarsa');
	$this->view->q_peg_kpimpinan=$datadptiga[0]['q_peg_kpimpinan'];
	$this->SetNilai($datadptiga[0]['q_peg_kpimpinan'],'e_peg_kpimpinan');
	$this->view->q_peg_totalnilai=$datadptiga[0]['q_peg_totalnilai'];
	$this->SetNilai($datadptiga[0]['q_peg_totalnilai'],'e_peg_totalnilai');
	$this->view->q_peg_nilairata=$datadptiga[0]['q_peg_nilairata'];
	$this->SetNilai($datadptiga[0]['q_peg_nilairata'],'e_peg_nilairata');
	$this->view->e_peg_keberatan=$datadptiga[0]['e_peg_keberatan'];
	$this->view->e_peg_tgpanpnilai=$datadptiga[0]['e_peg_tgpanpnilai'];
	$this->view->e_peg_kputusanatasan=$datadptiga[0]['e_peg_kputusanatasan'];	
}

public function listDataBack() {	

	$this->view->c_peg_golongan=$_POST['c_peg_golongan'];
	$this->view->d_peg_pnilai=$_POST['d_peg_pnilai'];
	$this->view->e_peg_keberatan=$_POST['e_peg_keberatan'];
	$this->view->e_peg_kejujuran=$_POST['e_peg_kejujuran'];
	$this->view->e_peg_kerjasama=$_POST['e_peg_kerjasama'];
	$this->view->e_peg_kesetiaan=$_POST['e_peg_kesetiaan'];
	$this->view->e_peg_ketaatan=$_POST['e_peg_ketaatan'];
	$this->view->e_peg_kpimpinan=$_POST['e_peg_kpimpinan'];
	$this->view->e_peg_kputusanatasan=$_POST['e_peg_kputusanatasan'];
	$this->view->e_peg_nilairata=$_POST['e_peg_nilairata'];
	$this->view->e_peg_prakarsa=$_POST['e_peg_prakarsa'];
	$this->view->e_peg_preskerja=$_POST['e_peg_preskerja'];
	$this->view->e_peg_tggjawab=$_POST['e_peg_tggjawab'];
	$this->view->e_peg_tgpanpnilai=$_POST['e_peg_tgpanpnilai'];
	$this->view->i_peg_nip=$_POST['i_peg_nip'];
	$this->view->i_peg_nipatasanpnilai=$_POST['i_peg_nipatasanpnilai'];
	$this->view->i_peg_nippnilai=$_POST['i_peg_nippnilai'];
	$this->view->n_jabatan_atasanpnilai=$_POST['n_jabatan_atasanpnilai'];
	$this->view->n_jabatan_pnilai=$_POST['n_jabatan_pnilai'];
	$this->view->n_peg=$_POST['n_peg'];
	$this->view->n_peg_nipatasanpnilai=$_POST['n_peg_nipatasanpnilai'];
	$this->view->n_peg_nippnilai=$_POST['n_peg_nippnilai'];
	$this->view->proses=$_POST['proses'];
	$this->view->q_peg_kejujuran=$_POST['q_peg_kejujuran'];
	$this->view->q_peg_kerjasama=$_POST['q_peg_kerjasama'];
	$this->view->q_peg_kesetiaan=$_POST['q_peg_kesetiaan'];
	$this->view->q_peg_ketaatan=$_POST['q_peg_ketaatan'];
	$this->view->q_peg_kpimpinan=$_POST['q_peg_kpimpinan'];
	$this->view->q_peg_nilairata=$_POST['q_peg_nilairata'];
	$this->view->q_peg_prakarsa=$_POST['q_peg_prakarsa'];
	$this->view->q_peg_preskerja=$_POST['q_peg_preskerja'];
	$this->view->q_peg_tggjawab=$_POST['q_peg_tggjawab'];
	$this->view->q_peg_totalnilai=$_POST['q_peg_totalnilai'];
		
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