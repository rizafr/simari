<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Pangkat_Service.php";
require_once "service/sdm/Sdm_Jabatan_Service.php";
require_once "service/sdm/Sdm_JabatanFungsional_Service.php";
require_once "service/sdm/Sdm_Hukuman_Service.php";
class Sdmmodule_DataSKController extends Zend_Controller_Action {
		
    public function init() {
	$this->_helper->layout->setLayout('target-column');
	$registry = Zend_Registry::getInstance();
	$this->view->basePath = $registry->get('basepath'); 
	$this->view->photoPath = $registry->get('photoPath'); 	
	$this->view->leftMenu = $registry->get('leftMenu'); 
	$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
	$this->reff_serv = sdm_refferensi_Service::getInstance();
	$this->pangkat_serv = Sdm_Pangkat_Service::getInstance();
	$this->jabatan_serv = Sdm_Jabatan_Service::getInstance();
	$this->jabatanfung_serv = Sdm_JabatanFungsional_Service::getInstance();
	$this->hukuman_serv = Sdm_Hukuman_Service::getInstance();

	$sespeg = new Zend_Session_Namespace('sespeg');
	$this->view->n_peg= $sespeg->n_peg;
	$this->view->i_peg_nip= $sespeg->i_peg_nip;	
	
		//sesion dari login
		$ssologin = new Zend_Session_Namespace('ssologin');
		$this->view->userid=$ssologin->userid;
		$this->view->i_peg_nip=$ssologin->i_peg_nip;
		$this->view->i_peg_nip_new=$ssologin->i_peg_nip_new;
		$this->view->n_peg=$ssologin->n_peg;
		$this->view->c_jabatan=$ssologin->c_jabatan;
		$this->view->c_eselon_i=$ssologin->c_eselon_i;
		$this->view->c_lokasi_unitkerja=$ssologin->c_lokasi_unitkerja;
		$this->view->sektoral			= $ssologin->arrayc_sektoral[1]; 
		$this->view->wewenang			= $ssologin->arrayc_wewenang[1]; 
		if($this->view->wewenang == 'O'){$this->view->c_izin='000002';}
		//$this->view->c_izin=$ssologin->c_izin[0]['c_izin'];
		if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Kelola ";}
		else{$this->view->jdl="Melihat ";}	
		
    }
	
    public function indexAction() {
	   
    }
public function dataskjsAction() 
{
	header('content-type : text/javascript');
	$this->render('dataskjs');
}
	
public function listpegawaiAction() {
	$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai($cari);
	$this->view->statusPegRef = $this->reff_serv->getStatusPegawai($cari);
	if ($_POST['nCol'])
	{
		$nCol=strtoupper($_POST['nCol']);
		$cCol=$_POST['cCol'];	
		 $this->view->nCol = $_POST['nCol'];
		 $this->view->cCol = $_POST['cCol'];	
	}
	else{
		$nCol=strtoupper($_GET['nCol']);
		$cCol=$_GET['cCol'];
		 $this->view->nCol = $_GET['nCol'];
		 $this->view->cCol = $_GET['cCol'];	
	}


	if ($nCol && $cCol ){$cari .= " and $cCol like '%$nCol%' ";}
	$orderBy=$_GET['orderBy'];
	$order=$_GET['order'];
	if (!$_GET['order']){$this->view->orderbya="asc";$this->view->orderbyb="desc";}
	else{
		if ($_GET['order']=='desc'){	$this->view->orderbya="desc";$this->view->orderbyb="asc";}
		else{$this->view->orderbya="asc";$this->view->orderbyb="desc";}
	}
	if ($_GET['orderBy']){$orderBy=" order by $orderBy $order";}
	else{$orderBy=" order by d_entry asc";}
	$this->view->orderBy=$_GET['orderBy'];
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		
	if ($this->view->c_izin=='000000' || $this->view->c_izin=='000001' || $this->view->c_izin=='000002' || $this->view->c_izin=='000003'){
		$c_lokasi_unitkerja=trim($this->view->c_lokasi_unitkerja);
		$c_eselon_i=trim($this->view->c_eselon_i);
	}
	
	if ($this->view->sektoral=='D'){ //dalam sektoral
		if (trim($this->view->c_lokasi_unitkerja)=='1'){
			$c_eselon_i=trim($this->view->c_eselon_i);
			$c_eselon_ii=trim($this->view->c_eselon_ii);
			$c_eselon_iii=trim($this->view->c_eselon_iii);
			$c_eselon_iv=trim($this->view->c_eselon_iv);
			$c_eselon_v=trim($this->view->c_eselon_v);		
			//jika dalam sektoral
			if ($this->view->c_eselon=='01' || $this->view->c_eselon=='02')
				{$cari .= " and ((c_eselon_i is null and c_eselon_i_cpns='$c_eselon_i') or (c_eselon_i='$c_eselon_i'))   ";}
			if ($this->view->c_eselon=='03' || $this->view->c_eselon=='04' )
				{$cari .= " and ((c_eselon_i is null and c_eselon_i_cpns='$c_eselon_i') or (c_eselon_i='$c_eselon_i'))   and ((c_eselon_ii is null and c_eselon_ii_cpns='$c_eselon_ii') or (c_eselon_ii='$c_eselon_ii'))  ";}			
			if ($this->view->c_eselon=='05' || $this->view->c_eselon=='06')
				{$cari .= " and ((c_eselon_i is null and c_eselon_i_cpns='$c_eselon_i') or (c_eselon_i='$c_eselon_i'))   and ((c_eselon_ii is null and c_eselon_ii_cpns='$c_eselon_ii') or (c_eselon_ii='$c_eselon_ii'))   and ((c_eselon_iii is null and c_eselon_iii_cpns='$c_eselon_iii') or (c_eselon_iii='$c_eselon_iii')) ";}
			if ($this->view->c_eselon=='07' || $this->view->c_eselon=='08')
				{
				     $cari .= " and ((c_eselon_i is null and c_eselon_i_cpns='$c_eselon_i') or (c_eselon_i='$c_eselon_i'))  
						and ((c_eselon_ii is null and c_eselon_ii_cpns='$c_eselon_ii') or (c_eselon_ii='$c_eselon_ii'))  
						and ((c_eselon_iii is null and c_eselon_iii_cpns='$c_eselon_iii') or (c_eselon_iii='$c_eselon_iii')) 
						and ((c_eselon_iv is null and c_eselon_iv_cpns='$c_eselon_iv') or (c_eselon_iv='$c_eselon_iv') )";
					
				}		
			
			if ($this->view->c_eselon=='15')
			{
				if($c_eselon_i){$cari .= " and ((c_eselon_i is null and c_eselon_i_cpns='$c_eselon_i') or (c_eselon_i='$c_eselon_i'))   ";}
				if($c_eselon_ii){$cari .= " and ((c_eselon_ii is null and c_eselon_ii_cpns='$c_eselon_ii') or (c_eselon_ii='$c_eselon_ii'))   ";}
				if($c_eselon_iii){$cari .= " and ((c_eselon_iii is null and c_eselon_iii_cpns='$c_eselon_iii') or (c_eselon_iii='$c_eselon_iii'))  ";}
				if($c_eselon_iv){$cari .= " and ((c_eselon_iv is null and c_eselon_iv_cpns='$c_eselon_iv') or (c_eselon_iv='$c_eselon_iv') ) ";}
			
			}
			
		} else{ // pengadilan
			$c_eselon_i=trim($this->view->c_eselon_i);
			$c_eselon_ii=trim($this->view->c_eselon_ii);
			$c_eselon_iii=trim($this->view->c_eselon_iii);
			$c_eselon_iv=trim($this->view->c_eselon_iv);
			$c_eselon_v=$this->view->c_eselon_v;
			$c_satker=$this->view->c_satker;
			$cari .= " and c_lokasi_unitkerja='3'"; 
			$cari .= " and c_eselon_i='$c_eselon_i'";			

			if ($c_eselon_iii!='00'){$cari .= "  and c_satker='$c_satker'";}
			else if ($c_eselon_iii == '00' &&  $c_eselon_ii != '') {
				$cari .= " and c_eselon_ii='$c_eselon_ii'";
			}
			if ($c_eselon_iv!='00'){$cari .= " and c_eselon_iv='$c_eselon_iv'";}

		}		
		
	
	} else if ($this->view->sektoral=='L'){ 	//jika lintas sektoral , tp tidak semua sektoral
		$c_eselon_i=trim($this->view->c_eselon_i);
		$cari .= " and (c_eselon_i='$c_eselon_i')  ";
	}
	//echo $cari;
	
		$this->view->totalpegawaiList = $this->pegawai_serv->getPegawaiList($cari, 0, 0 ,$orderByx);		
		$this->view->pegawaiList = $this->pegawai_serv->getPegawaiList($cari, $currentPage, $numToDisplay,$orderByx );	
}	
public function listdataskAction() {

	$i_peg_nip=$_GET['i_peg_nip'];
	$n_peg=$_GET['n_peg'];
	$this->view->n_peg= $n_peg;
	$this->view->i_peg_nip= $i_peg_nip;
	
	$cari = " and i_peg_nip ='$i_peg_nip' ";
	$this->view->pangkatList = $this->pangkat_serv->getPangkatList($cari);
	$this->view->jabatanList = $this->jabatan_serv->getJabatanList($cari);	
	$this->view->jabatanFungList = $this->jabatanfung_serv->getJabatanList($cari);
	$this->view->hukumanList = $this->hukuman_serv->getHukumanList($cari);	
}
public function dataskAction() {

	$par=$_GET['par'];
	$this->view->i_peg_nip=	$_GET['i_peg_nip'];
	$this->view->n_peg=$_GET['n_peg'];
	if ($par=='insert'){
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
	}
	else{
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";	
		$par=$_GET['par'];
		$c_jns_dokumen_sk=$_GET['c_jns_dokumen_sk'];
		$i_dokumen_sk=$_GET['i_dokumen_sk'];
		
		$this->listDataByKey($_GET['i_peg_nip'],$_GET['c_jns_dokumen_sk'],$_GET['i_dokumen_sk']);
	} 
	$this->view->jnsDokList = $this->reff_serv->getTrJenisDok('');	
	
}
public function maintaindataAction() {
 		if ($_POST['d_dokumen_sk'])
		{
			$d_dokumen_sk1=substr($_POST['d_dokumen_sk'],0,2);
			$d_dokumen_sk2=substr($_POST['d_dokumen_sk'],3,2);
			$d_dokumen_sk3=substr($_POST['d_dokumen_sk'],6,4);
		}
		$d_dokumen_sk=$d_dokumen_sk3."-".$d_dokumen_sk2."-".$d_dokumen_sk1;
		if (!$_POST['d_dokumen_sk']){$d_dokumen_sk=null;$cektgltest=true;}
		else{$cektgltest=checkdate($d_dokumen_sk2,$d_dokumen_sk1,$d_dokumen_sk3);}
		
if (($cektgltest==true) )
{	


								
//simpan file			
		if ($_POST['a_file'])
		{
				$namefile=trim($_POST['i_peg_nip']).$_POST['c_jns_dokumen_sk'].$_POST['i_dokumen_sk'];
				$FileName_dat = $namefile;
				$fileName = $_FILES['e_file_sk']['name'];	
				$extention = strtolower(substr($fileName, strrpos($fileName, '.') + 1));				
				$FileName_pdf = $FileName_dat.'.'.$extention;
				$destDir = "$FileName_pdf";	
		}
///
if ($_POST['del_photo']=="on") {$e_file_sk=null;}
else {$e_file_sk=$destDir;}

	$MaintainData = array(
				"i_peg_nip"=>$_POST['i_peg_nip'],
				"c_jns_dokumen_sk"=>$_POST['c_jns_dokumen_sk'],
				"i_dokumen_sk"=>$_POST['i_dokumen_sk'],
				"c_jns_dokumen_sk2"=>$_POST['c_jns_dokumen_sk2'],
				"i_dokumen_sk2"=>$_POST['i_dokumen_sk2'],				
				"n_dokumen_sk"=>$_POST['n_dokumen_sk'],
				"d_dokumen_sk"=>$d_dokumen_sk,
				"e_file_sk"=>$e_file_sk,
				"i_entry"=>"test",
				"d_entry"=>date('Ymd'));		

	if ($_POST['proses']=='Simpan')
	{
		$hasil = $this->dosier_serv->maintainData($MaintainData,'insert');		
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
	}		
	else
	{
		$hasil = $this->dosier_serv->maintainData($MaintainData,'update');
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";		
	}

/// simpan file
 		if ($hasil=="sukses"){
			$namefile=trim($_POST['i_peg_nip']).$_POST['c_jns_dokumen_sk'].$_POST['i_dokumen_sk'];
			$FileName_pdf;
			$fileNamex = $_FILES['e_file_sk']['name'];
			$extentionx = strtolower(substr($fileNamex, strrpos($fileNamex, '.') + 1));
				
		    if (!empty($_FILES['e_file_sk'])) 
				{$FileName_pdf = $FileName_dat.'.'.$extentionx;}
				$FileName_dat = $namefile;
				$FileName_pdf = $FileName_dat.'.'.$extentionx;
					
				if (!empty($_FILES['e_file_sk'])) 	   {

				   $fileName = $_FILES['e_file_sk']['name'];
				   $extention = strtolower(substr($fileName, strrpos($fileName, '.') + 1));
						$destDir = "../library/data/sdm/dosier/$FileName_pdf";
						if (move_uploaded_file($_FILES['e_file_sk']['tmp_name'], $destDir)) { 
							$lampiran ="file";
						}
				}
			} 
//=================================================================================================			
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
	$cari = " and i_peg_nip ='$i_peg_nip' ";
	$this->view->dataskList = $this->dosier_serv->getDosierList($cari);
	//$this->listDataByKey($_POST['i_peg_nip'],$_POST['c_jns_dokumen_sk'],$_POST['i_dokumen_sk']);	
	
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->render('listdatask');	
}

public function hapusdataAction() {
	
	$c_jns_dokumen_sk = $_GET['c_jns_dokumen_sk'];
	$i_dokumen_sk = $_GET['i_dokumen_sk'];
	$i_peg_nip = $_GET['i_peg_nip'];
	
	$this->view->i_peg_nip=$i_peg_nip;
	$this->view->n_peg=$n_peg;

	$MaintainData = array("i_peg_nip"=>trim($i_peg_nip),
				"c_jns_dokumen_sk"=>trim($c_jns_dokumen_sk),
				"i_dokumen_sk"=>trim($i_dokumen_sk));		
	$hasil = $this->dosier_serv->maintainData($MaintainData,'delete');	
	$pesan= "Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->listdataskAction();
	$this->render('listdatask');	
}
	
public function listDataByKey($i_peg_nip,$c_jns_dokumen_sk,$i_dokumen_sk) { 
	$carilist = " and i_peg_nip='$i_peg_nip' and c_jns_dokumen_sk = '$c_jns_dokumen_sk' and i_dokumen_sk = '$i_dokumen_sk' ";
	$datadosier=$this->dosier_serv->getDosierList($carilist);	
	$this->view->i_peg_nip=$datadosier[0]['i_peg_nip'];
	$this->view->c_jns_dokumen_sk=$datadosier[0]['c_jns_dokumen_sk'];
	$this->view->i_dokumen_sk=$datadosier[0]['i_dokumen_sk'];
	$this->view->n_dokumen_sk=$datadosier[0]['n_dokumen_sk'];
	$this->view->d_dokumen_sk=$datadosier[0]['d_dokumen_sk'];
	$this->view->e_file_sk=$datadosier[0]['e_file_sk'];
}

	public function viewdokumenAction()
	{
		$valFile = $_REQUEST['f'];	  
		$this->_helper->viewRenderer->setNoRender(true);
		$folderE = $this->view->photoPath.$valFile;
		$dokumenNe = file_get_contents($folderE);
	   $ektensi = explode(".",$valFile);
	   $valEks = $ektensi[1];
	   header("Content-Type: application/$valEks");
	   header("Content-Disposition: attachment; filename=$folderE"); 
	   echo $dokumenNe;
	}	
	
}
?>