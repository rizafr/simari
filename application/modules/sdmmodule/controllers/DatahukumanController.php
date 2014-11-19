<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_Hukuman_Service.php";
require_once "service/sdm/sdm_refferensi_Service.php";
class Sdmmodule_DataHukumanController extends Zend_Controller_Action {
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu'); 		
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->hukuman_serv = Sdm_Hukuman_Service::getInstance();
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
		//$this->view->c_izin=$ssologin->c_izin[0]['c_izin'];	
		$this->view->sektoral			= $ssologin->arrayc_sektoral[1]; 
		$this->view->wewenang			= $ssologin->arrayc_wewenang[1]; 
		if($this->view->wewenang == 'O'){$this->view->c_izin='000002';}
		
		
    }
	
    public function indexAction() {
	   
    }
public function hukumanjsAction() 
{
	header('content-type : text/javascript');
	$this->render('hukumanjs');
}	

public function listpegawaiAction() {    
	$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai($cari);
	$this->view->statusPegRef = $this->reff_serv->getStatusPegawai($cari);

	if ($this->view->nip)
	{
		$nipx=$this->view->nip; 
		$this->view->nCol = $nipx;
		$this->view->cCol = 'i_peg_nip';
		$cari=" and (i_peg_nip='$nipx' or  i_peg_nip_new='$nipx')" ;
	}
	else
	{
		$nCol=strtoupper($_GET['nCol']);
		$cCol=$_GET['cCol'];
		$this->view->nCol = $_GET['nCol'];
		$this->view->cCol = $_GET['cCol'];	
	}		 
	
	if ($_POST['cCol'])
	{
		$nCol=strtoupper($_POST['nCol']);
		$cCol=$_POST['cCol'];	
		 $this->view->nCol = $_POST['nCol'];
		 $this->view->cCol = $_POST['cCol'];
	}



	
	if ($nCol && $cCol ){
		if ($cCol=='i_peg_nip'){$cari .= " and (i_peg_nip like '%$nCol%' or  i_peg_nip_new like '%$nCol%')";} 
		else{	$cari .= " and upper($cCol) like '%".strtoupper($nCol)."%' ";}
		
	}
	
if($this->view->sektoral=='S'){
   	$c_lokasi_unitkerja=trim($this->view->c_lokasi_unitkerja);
	$c_eselon_i=trim($this->view->c_eselon_i);
	if ($c_eselon_i!='01'){
		$cari= " and (c_lokasi_unitkerja='$c_lokasi_unitkerja' or c_lokasi_unitkerja_cpns ='$c_lokasi_unitkerja') and (c_eselon_i='$c_eselon_i' or c_eselon_i_cpns='$c_eselon_i') ";
	}
	

	
	//if ($cCol=='unitkerja'){
	if ($_POST['cCol']=='unitkerja' || $_GET['cCol']=='unitkerja')
	{

		
			if ($_POST['c_lokasi_unitkerja']){
			$c_lokasi_unitkerja=trim($_POST['c_lokasi_unitkerja']);
			}
			else{
			$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);
			}
			
			$this->view->c_lokasi_unitkerja=$c_lokasi_unitkerja;
			$cari .= " and c_lokasi_unitkerja='$c_lokasi_unitkerja'";
			
			if ($_POST['cCol']){$this->view->cCol = $_POST['cCol'];}
			else{$this->view->cCol = $_GET['cCol'];}			
			
		if ($c_lokasi_unitkerja=='1'){
			
			if ($_POST['c_eselon_i']){$c_eselon_i=$_POST['c_eselon_i'];}
			else{$c_eselon_i=$_GET['c_eselon_i'];}
			
			$c_eselon_i=substr($c_eselon_i,0,2);
			if ($_POST['c_eselon_i'] || $_GET['c_eselon_i']){$cari .= " and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'";}

			if ($_POST['c_eselon_ii']){$c_eselon_ii=$_POST['c_eselon_ii'];}
			else{$c_eselon_ii=$_GET['c_eselon_ii'];}
			$c_eselon_ii=substr($c_eselon_ii,0,2);
			if ($_POST['c_eselon_ii'] || $_GET['c_eselon_ii']){$cari .= " and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and c_lokasi_unitkerja='$c_lokasi_unitkerja'";}
			
			if ($_POST['c_eselon_iii']){$c_eselon_i=$_POST['c_eselon_iii'];}
			else{$c_eselon_iii=$_GET['c_eselon_iii'];}
			
			
			$c_eselon_iii=substr($c_eselon_iii,0,2);
			if ($_POST['c_eselon_iii'] || $_GET['c_eselon_iii']){$cari .= " and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_lokasi_unitkerja='$c_lokasi_unitkerja'";}

			if ($_POST['c_eselon_iv']){$c_eselon_iv=$_POST['c_eselon_iv'];}
			else{$c_eselon_iv=$_GET['c_eselon_iv'];}
			
			
			$c_eselon_iv=substr($c_eselon_iv,0,2);
			if ($_POST['c_eselon_iv'] || $_GET['c_eselon_iv']){$cari .= " and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_lokasi_unitkerja='$c_lokasi_unitkerja'";}
		

			$this->view->c_eselon_i = $c_eselon_i;
			

			
			$this->view->lokasiList = $this->reff_serv->getLokasi('');
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			
			
			
			if ($_POST['c_eselon_i']){$this->view->c_eselon_i=$_POST['c_eselon_i'];}
			else{$this->view->c_eselon_i=$_GET['c_eselon_i'];}	
			
			$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	

			if ($_POST['c_eselon_ii']){$this->view->c_eselon_ii=$_POST['c_eselon_ii'];}
			else{$this->view->c_eselon_ii=$_GET['c_eselon_ii'];}
				
			
			$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");


			if ($_POST['c_eselon_iii']){$this->view->c_eselon_iii=$_POST['c_eselon_iii'];}
			else{$this->view->c_eselon_iii=$_GET['c_eselon_iii'];}
				
			
			$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

			if ($_POST['c_eselon_iv']){$this->view->c_eselon_iv=$_POST['c_eselon_iv'];}
			else{$this->view->c_eselon_iv=$_GET['c_eselon_iv'];}
				
			
			$this->view->eselonvList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

		}
		else
		{


			if ($_POST['c_lokasi_unitkerja']){$c_lokasi_unitkerja=trim($_POST['c_lokasi_unitkerja']);}
			else{$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);}
			$this->view->lokasiList = $this->reff_serv->getLokasi('');
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

			
			if ($_POST['c_eselon_i']){$c_eselon_i=$_POST['c_eselon_i'];}
			else{$c_eselon_i=trim($_GET['c_eselon_i']);}
			$this->view->c_eselon_i =$c_eselon_i;
			if ($c_eselon_i){
				$expesl1 = explode(";",$c_eselon_i);
				$c_eselon_i=$expesl1[0];
			}
			
			if ($_POST['c_eselon_ii']){$c_eselon_ii=$_POST['c_eselon_ii'];}
			else{$c_eselon_ii=trim($_GET['c_eselon_ii']);}
			$this->view->c_eselon_ii =$c_eselon_ii;
			if ($c_eselon_ii){
			$expesl2 = explode(";",$c_eselon_ii);
			$c_eselon_ii=$expesl2[0];
			$c_parent=$expesl2[1];
			}
			
			
			if ($_POST['c_eselon_iii']){$c_eselon_iii=$_POST['c_eselon_iii'];}
			else{$c_eselon_iii=trim($_GET['c_eselon_iii']);}
			$this->view->c_eselon_iii =trim($c_eselon_iii);		
			if ($c_eselon_iii){
			$expesl3 = explode(";",$c_eselon_iii);	
			$c_eselon_iix=$expesl3[0];
			$c_eselon_iii=$expesl3[1];
			$c_satker=$expesl3[2];			
			}

			if ($_POST['c_eselon_iv']){$c_eselon_iv=$_POST['c_eselon_iv'];}
			else{$c_eselon_iv=trim($_GET['c_eselon_iv']);}
			$this->view->c_eselon_iv =trim($c_eselon_iv);	
			if ($c_eselon_iv){
				$expesl4 = explode(";",$c_eselon_iv);	
				$c_eselon_iv=$expesl4[0];	
				$c_eselon_v=$expesl4[1];
			}			
			

			
			if ($c_eselon_i){$cari .= " and c_eselon_i='$c_eselon_i'";}
			if ($c_eselon_ii ){$cari .= " and c_eselon_ii='$c_eselon_ii'";}
			if ($c_eselon_iii){$cari .= " and c_eselon_iii='$c_eselon_iii' and c_parent='$c_parent' and c_satker='$c_satker'";}
			if ($c_eselon_iv){$cari .= " and c_eselon_iv='$c_eselon_iv'";}
			if ($c_eselon_v){$cari .= " and c_eselon_v='$c_eselon_v'";}
			
			//echo $cari;
			//echo " and c_eselon_i='$c_eselon_i'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child <> '00' and c_parent ='$c_parent'  and c_lokasi_unitkerja='$c_lokasi_unitkerja'";
			
			$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_iii = '00'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child = '00' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child <> '00' and c_parent ='$c_parent'  and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
					
			//$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_level ='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_parent ='$c_parent' ");
			//$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_iii <> '00' and c_eselon_ii='$c_eselon_iix'  and c_lokasi_unitkerja='$c_lokasi_unitkerja' ");
	
		}		

	}

}
else{	
	$c_eselon_i=$this->view->c_eselon_i;
	$c_eselon_ii=$this->view->c_eselon_ii;
	$c_eselon_iii=$this->view->c_eselon_iii;
	$c_eselon_iv=$this->view->c_eselon_iv;
	$c_eselon_v=$this->view->c_eselon_v;	
	
	if ($this->view->sektoral=='D'){
		//jika dalam sektoral
		if ($this->view->c_eselon=='01' || $this->view->c_eselon=='02')
			{$cari .= " and (c_eselon_i='$c_eselon_i' or c_eselon_i_cpns='$c_eselon_i') ";}
		if ($this->view->c_eselon=='03' || $this->view->c_eselon=='04')
			{$cari .= " and (c_eselon_i='$c_eselon_i' or c_eselon_i_cpns='$c_eselon_i') and (c_eselon_ii='$c_eselon_ii' or c_eselon_ii_cpns='$c_eselon_ii')";}			
		if ($this->view->c_eselon=='05' || $this->view->c_eselon=='06')
			{$cari .= " and (c_eselon_i='$c_eselon_i' or c_eselon_i_cpns='$c_eselon_i') and (c_eselon_ii='$c_eselon_ii' or c_eselon_ii_cpns='$c_eselon_ii') and (c_eselon_iii='$c_eselon_iii' or c_eselon_iii_cpns='$c_eselon_iii')";}
		if ($this->view->c_eselon=='07' || $this->view->c_eselon=='08')
			{$cari .= " and (c_eselon_i='$c_eselon_i' or c_eselon_i_cpns='$c_eselon_i') and (c_eselon_ii='$c_eselon_ii' or c_eselon_ii_cpns='$c_eselon_ii') and (c_eselon_iii='$c_eselon_iii' or c_eselon_iii_cpns='$c_eselon_iii') and (c_eselon_iv='$c_eselon_iv' or c_eselon_iv_cpns='$c_eselon_iv')";}		
	}
	if ($this->view->sektoral=='L'){
		//jika lintas sektoral
		$cari .= " and (c_eselon_i='$c_eselon_i' or c_eselon_i_cpns='$c_eselon_i') and (c_eselon_ii='$c_eselon_ii' or c_eselon_ii_cpns='$c_eselon_ii')";	
	}	
}	
	$orderBy=$_GET['orderBy'];
	$order=$_GET['order'];
	if (!$_GET['order']){$this->view->orderbya="asc";$this->view->orderbyb="desc";}
	else{
		if ($_GET['order']=='desc'){	$this->view->orderbya="desc";$this->view->orderbyb="asc";}
		else{$this->view->orderbya="asc";$this->view->orderbyb="desc";}
	}
	
	if ($_GET['orderBy']){$orderBy=" order by $orderBy $order";}
	else{$orderBy=" order by c_golongan asc";}
	$this->view->orderBy=$_GET['orderBy'];
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;} $numToDisplay = 10;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;

	//echo $cari;	
	$this->view->totalpegawaiList = $this->pegawai_serv->getPegawaiList($cari, 0, 0 ,$orderBy);		
	$this->view->pegawaiList = $this->pegawai_serv->getPegawaiList($cari, $currentPage, $numToDisplay,$orderBy );	
	
	$sesmenu = new Zend_Session_Namespace('sesmenu');
	$sesmenu->menu= $_GET['menu'];
	$this->view->menu= $_GET['menu'];
}

	
public function listhukumanAction() {
	$ssologin = new Zend_Session_Namespace('ssologin');
	$this->view->sektoral			= $ssologin->arrayc_sektoral[1]; 
		
	$nip=$_GET['nip'];
	if(!$nip){$nip=$this->view->nip;}
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->hukumanList = $this->hukuman_serv->getHukumanList($cari);


	$datapegawai=$this->pegawai_serv->getPegawaiListByNip($cari );
	$sespeg = new Zend_Session_Namespace('sespeg');
	$sespeg->nama= $datapegawai[0]['n_peg'];
	$sespeg->nip= $datapegawai[0]['i_peg_nip'];
	$sespeg->nipnew= $datapegawai[0]['i_peg_nip_new'];
	$this->view->nama= $datapegawai[0]['n_peg'];
	$this->view->nip= $datapegawai[0]['i_peg_nip'];
	
}
public function hukumanAction() {
	$ssologin = new Zend_Session_Namespace('ssologin');
	$this->sektoral			= $ssologin->arrayc_sektoral[1]; 
	
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
		$this->view->id= $id;
		//$this->listDataByKey($this->view->nip,$_GET['tingkat'],$_GET['jenis'],$_GET['dmulai']);
		$this->listDataByKey($id);
		//$q_tingkat_hukuman=$_GET['tingkat'];
		$q_tingkat_hukuman= $this->view->c_tingkat_sanksi;
		$cari =" and q_tingkat_hukuman=$q_tingkat_hukuman";
		$this->view->jnsHukuman = $this->reff_serv->getTrHukuman($cari);
		if ($this->sektoral =='W' && ($this->view->c_izin=='000002' || $this->view->c_izin=='000003')){$this->view->jdl="Merubah ";}		
		else{$this->view->jdl="Melihat ";$this->render('hukumanview');}			
	}

	
}
public function listcombojnsAction() {
	$q_tingkat_hukuman=$_GET['c_tingkat_sanksi'];
	$cari ="and q_tingkat_hukuman=$q_tingkat_hukuman";
	$this->view->jnsHukuman = $this->reff_serv->getTrHukuman($cari);
}
public function listcombotktAction() {

}
public function listcombotktbAction() {

}
public function listcombotktcAction() {

}
public function maintaindataAction() {
 		if ($_POST['d_mulai_sanksi'])
		{
			$d_mulai_sanksi1=substr($_POST['d_mulai_sanksi'],0,2);
			$d_mulai_sanksi2=substr($_POST['d_mulai_sanksi'],3,2);
			$d_mulai_sanksi3=substr($_POST['d_mulai_sanksi'],6,4);
		}
		$d_mulai_sanksi=$d_mulai_sanksi3."-".$d_mulai_sanksi2."-".$d_mulai_sanksi1;
		if (!$_POST['d_mulai_sanksi']){$d_mulai_sanksi=null; $cektglmulai=true;}
		else{$cektglmulai=checkdate($d_mulai_sanksi2,$d_mulai_sanksi1,$d_mulai_sanksi3);}

 		if ($_POST['d_akhir_sanksi'])
		{
			$d_akhir_sanksi1=substr($_POST['d_akhir_sanksi'],0,2);
			$d_akhir_sanksi2=substr($_POST['d_akhir_sanksi'],3,2);
			$d_akhir_sanksi3=substr($_POST['d_akhir_sanksi'],6,4);
		}
		$d_akhir_sanksi=$d_akhir_sanksi3."-".$d_akhir_sanksi2."-".$d_akhir_sanksi1;
		if (!$_POST['d_akhir_sanksi']){$d_akhir_sanksi=null;$cektglakhir=true;}
		else{$cektglakhir=checkdate($d_akhir_sanksi2,$d_akhir_sanksi1,$d_akhir_sanksi3);}

		if ($_POST['d_sk_sanksi'])
		{
			$d_sk_sanksi1=substr($_POST['d_sk_sanksi'],0,2);
			$d_sk_sanksi2=substr($_POST['d_sk_sanksi'],3,2);
			$d_sk_sanksi3=substr($_POST['d_sk_sanksi'],6,4);
		}
		$d_sk_sanksi=$d_sk_sanksi3."-".$d_sk_sanksi2."-".$d_sk_sanksi1;
		if (!$_POST['d_sk_sanksi']){$d_sk_sanksi=null;$cektglsk=true;}
		else{$cektglsk=checkdate($d_sk_sanksi2,$d_sk_sanksi1,$d_sk_sanksi3);}
 		
if (($cektglmulai==true &&  $cektglakhir==true &&  $cektglsk==true) )
{
 		if ($_POST['d_mulai_sanksib'])
		{
			$d_mulai_sanksib1=substr($_POST['d_mulai_sanksib'],0,2);
			$d_mulai_sanksib2=substr($_POST['d_mulai_sanksib'],3,2);
			$d_mulai_sanksib3=substr($_POST['d_mulai_sanksib'],6,4);
		}
		$d_mulai_sanksib=$d_mulai_sanksib3."-".$d_mulai_sanksib2."-".$d_mulai_sanksib1;

	$theFileSize = $_FILES['e_file_sk']['size'];
	if (!$theFileSize){
		$destDir = $_POST['a_file'];
	} else{
		if ($_POST['a_file'])
		{
				$dsk=$d_sk_sanksi3.$d_sk_sanksi2.$d_sk_sanksi1;	
				$namefile=trim($_POST['i_peg_nip'])."_".trim($_POST['c_jenis_sanksi'])."_".$dsk;
				$FileName_dat = $namefile;
				$fileName = $_FILES['e_file_sk']['name'];	
				$extention = strtolower(substr($fileName, strrpos($fileName, '.') + 1));				
				$FileName_pdf = $FileName_dat.'.'.$extention;
				$destDir = "$FileName_pdf";	
		}
	}	
		
	$MaintainData = array("id"=>$_POST['id'],
				"i_peg_nip"=>$_POST['i_peg_nip'],
				"c_tingkat_sanksi"=>$_POST['c_tingkat_sanksi'],
				"c_tingkat_sanksib"=>$_POST['c_tingkat_sanksib'],
				"c_jenis_sanksi"=>$_POST['c_jenis_sanksi'],
				"c_jenis_sanksib"=>$_POST['c_jenis_sanksib'],
				"d_mulai_sanksi"=>$d_mulai_sanksi,
				"d_mulai_sanksib"=>$d_mulai_sanksib,
				"d_akhir_sanksi"=>$d_akhir_sanksi,
				"e_alasan_sanksi"=>$_POST['e_alasan_sanksi'],
				"i_sk_sanksi"=>$_POST['i_sk_sanksi'],
				"d_sk_sanksi"=>$d_sk_sanksi,
				"n_pejabat"=>strtoupper($_POST['n_pejabat']),
				"e_keterangan"=>$_POST['e_keterangan'],
				"c_jns_pelanggaran"=>$_POST['c_jns_pelanggaran'],
				"c_jns_pelanggaranb"=>$_POST['c_jns_pelanggaranb'],
				"e_file_sk"=>$destDir,
				"i_entry"=>$this->view->userid,
				"d_entry"=>date('Ymd'));		

	$stop="no";		
	$theFileSize = $_FILES['e_file_sk']['size'];
        if ($theFileSize>999999){
            $theDiv = $theFileSize / 1000000;
            //$theFileSize = round($theDiv, 1)." MB"; 
	    $theFileSize = round($theDiv, 1); 
	    if ($theFileSize>=4){$stop="ok";}
        } 
	
	if ($_POST['proses']=='Simpan')
	{
		if ($stop=="ok"){$hasil="Gagal besar file tidak diijinkan";}
		else{$hasil = $this->hukuman_serv->maintainData($MaintainData,'insert');}		
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
	}		
	else
	{
		if ($stop=="ok"){$hasil="Gagal besar file tidak diijinkan";}
		else{$hasil = $this->hukuman_serv->maintainData($MaintainData,'update');}
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";		
	}	
/// simpan file
 		if ($hasil=="sukses"){
			$dsk=$d_sk_sanksi3.$d_sk_sanksi2.$d_sk_sanksi1;	
			$namefile=trim($_POST['i_peg_nip'])."_".trim($_POST['c_jenis_sanksi'])."_".$dsk;
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
						$destDir = "../library/data/sdm/hukuman/$FileName_pdf";
						if (move_uploaded_file($_FILES['e_file_sk']['tmp_name'], $destDir)) { 
							$lampiran ="file";
						}
				}
			} 
//=================================================================================================
	//$this->listDataByKey($this->view->nip,$_POST['c_tingkat_sanksi'],$_POST['c_jenis_sanksi'],$_POST['d_mulai_sanksi']);
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
	//$this->listDataByKey($this->view->nip,$_POST['c_tingkat_sanksi'],$_POST['c_jenis_sanksi'],$_POST['d_mulai_sanksi']);
	$this->listDataByKey($this->view->id);

}	

	$nip=$_POST['i_peg_nip'];	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->hukumanList = $this->hukuman_serv->getHukumanList($cari);
	
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	if ($hasil=='sukses'){$this->render('listhukuman');}else{$this->render('hukuman');}
}

public function hapusdataAction() {
	//$MaintainData = array("i_peg_nip"=>($this->view->nip),"c_tingkat_sanksi"=>$_GET['tingkat'],"c_jns_pelanggaran"=>$_GET['jenis'],"d_mulai_sanksi"=>$_GET['dmulai']);		
	//$hasil = $this->hukuman_serv->maintainData($MaintainData,'delete');	
	$id = $_GET['id'];
	$MaintainData = array("id"=>$id);		
	$hasil = $this->hukuman_serv->maintainData($MaintainData,'delete');	
	$pesan= "Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->listhukumanAction();
	$this->render('listhukuman');	
}
	
//public function listDataByKey($nip,$tingkat,$jenis,$tmtmulai) {  
public function listDataByKey($id) {  
	$carilist = " and id='$id'";
	// and c_tingkat_sanksi='$tingkat' and c_jenis_sanksi='$jenis' and to_char(d_mulai_sanksi,'dd-mm-yyyy')='$tmtmulai'
	$datahukuman=$this->hukuman_serv->getHukumanList($carilist);	
	$this->view->i_peg_nip=$datahukuman[0]['i_peg_nip'];
	$this->view->c_tingkat_sanksi=trim($datahukuman[0]['c_tingkat_sanksi']);
	$this->view->c_jenis_sanksi=trim($datahukuman[0]['c_jenis_sanksi']);
	$this->view->e_alasan_sanksi=$datahukuman[0]['e_alasan_sanksi'];
	$this->view->d_mulai_sanksi=$datahukuman[0]['d_mulai_sanksi'];
	$this->view->d_akhir_sanksi=$datahukuman[0]['d_akhir_sanksi'];
	$this->view->i_sk_sanksi=$datahukuman[0]['i_sk_sanksi'];
	$this->view->d_sk_sanksi=$datahukuman[0]['d_sk_sanksi'];
	$this->view->n_pejabat=$datahukuman[0]['n_pejabat'];
	$this->view->e_keterangan=$datahukuman[0]['e_keterangan'];
	$this->view->c_jns_pelanggaran=$datahukuman[0]['c_jns_pelanggaran'];	
	$this->view->i_entry=$datahukuman[0]['i_entry'];
	$this->view->d_entry=$datahukuman[0]['d_entry'];
	$this->view->e_file_sk=$datahukuman[0]['e_file_sk'];
	
}


public function carilistpegawaiAction() {    
	$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai($cari);
	$this->view->statusPegRef = $this->reff_serv->getStatusPegawai($cari);


	if ($_POST['cCol'])
	{
		$nCol=strtoupper($_POST['nCol']);
		$cCol=$_POST['cCol'];		
		 $nCol= str_replace("\'", "''", $nCol);
		 
		 $nCola=$_POST['nCol'];
		 $nCola= str_replace("\'", "'", $nCola);
		 $this->view->nCol = $nCola;
		 
		 //$this->view->nCol = $_POST['nCol'];
		 $this->view->cCol = $_POST['cCol'];
		
	}
	else
	{
		$nCol=strtoupper($_GET['nCol']);
		$cCol=$_GET['cCol'];
		$nCol=strtoupper($_GET['nCol']);
		$cCol=$_GET['cCol'];		
		 $nCol= str_replace("\'", "''", $nCol);
		 
		 $nCola=$_GET['nCol'];
		 $nCola= str_replace("\'", "'", $nCola);
		 $this->view->nCol = $nCola;
		 
		$this->view->cCol = $_GET['cCol'];	
	}

	if ($nCol && $cCol ){
	if ($cCol=='i_peg_nip'){$cari .= " and (i_peg_nip like '%$nCol%' or  i_peg_nip_new like '%$nCol%')";}else{	$cari .= " and upper($cCol) like '%$nCol%' ";}
	}
	

if($this->view->sektoral=='S'){
	if ($_POST['cCol']=='unitkerja' || $_GET['cCol']=='unitkerja')
	{

		
			if ($_POST['c_lokasi_unitkerja']){
			$c_lokasi_unitkerja=trim($_POST['c_lokasi_unitkerja']);
			}
			else{
			$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);
			}
			
			$this->view->c_lokasi_unitkerja=$c_lokasi_unitkerja;
			$cari .= " and c_lokasi_unitkerja='$c_lokasi_unitkerja'";
			
			if ($_POST['cCol']){$this->view->cCol = $_POST['cCol'];}
			else{$this->view->cCol = $_GET['cCol'];}			
			$this->view->lokasiList = $this->reff_serv->getLokasi('');
			
			
		if ($c_lokasi_unitkerja=='1'){	
			
			if ($_POST['c_lokasi_unitkerja']){$c_lokasi_unitkerja=trim($_POST['c_lokasi_unitkerja']);}
			else{$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);}
			
			$c_eselon_i=$_GET['c_eselon_i'];
			if (!$c_eselon_i){$c_eselon_i=trim($_POST['c_eselon_i']);}			
			$this->view->c_eselon_i =$c_eselon_i;	
			
			$c_eselon_ii=$_GET['c_eselon_ii'];
			if (!$c_eselon_ii){$c_eselon_ii=trim($_POST['c_eselon_ii']);}
			$this->view->c_eselon_ii =$c_eselon_ii;

		
			$c_eselon_iii=$_GET['c_eselon_iii'];
			if (!$c_eselon_iii){$c_eselon_iii=trim($_POST['c_eselon_iii']);}
			$this->view->c_eselon_iii =$c_eselon_iii;
			
			$c_eselon_iv=$_GET['c_eselon_iv'];
			if (!$c_eselon_iv){$c_eselon_iv=trim($_POST['c_eselon_iv']);}
			$this->view->c_eselon_iv =$c_eselon_iv;
		
			$c_eselon_v=$_GET['c_eselon_v'];		
			if (!$c_eselon_v){$c_eselon_v=trim($_POST['c_eselon_v']);}
			$this->view->c_eselon_v =$c_eselon_v;
			
			
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");		
			$expesl1 = explode(";",$c_eselon_i);
			$c_eselon_i=$expesl1[0];	
			$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and trim(c_tkt_esl)='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
			
			$expesl2 = explode(";",$c_eselon_ii);
			$c_eselon_ii=$expesl2[0];
			$c_parent=$expesl2[1];
			$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and trim(c_tkt_esl)='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");		


			$expesl3 = explode(";",$c_eselon_iii);	
			$c_eselon_iii=$expesl3[0];
			$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and trim(c_tkt_esl)='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and trim(c_tkt_esl)='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			
			$expesl4 = explode(";",$c_eselon_iv);	
			$c_eselon_iv=$expesl4[0];
			
			if ($c_eselon_i){$cari .= " and c_eselon_i='$c_eselon_i'";}
			if ($c_eselon_ii ){$cari .= " and c_eselon_ii='$c_eselon_ii'";}
			if ($c_eselon_iii){$cari .= " and c_eselon_iii='$c_eselon_iii'";}
			if ($c_eselon_iv){$cari .= " and c_eselon_iv='$c_eselon_iv'";}
			
		}
		else
		{


			if ($_POST['c_lokasi_unitkerja']){$c_lokasi_unitkerja=trim($_POST['c_lokasi_unitkerja']);}
			else{$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);}

			$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

			
			if ($_POST['c_eselon_i']){$c_eselon_i=$_POST['c_eselon_i'];}
			else{$c_eselon_i=trim($_GET['c_eselon_i']);}
			$this->view->c_eselon_i =$c_eselon_i;
			if ($c_eselon_i){
				$expesl1 = explode(";",$c_eselon_i);
				$c_eselon_i=$expesl1[0];
			}
			
			if ($_POST['c_eselon_ii']){$c_eselon_ii=$_POST['c_eselon_ii'];}
			else{$c_eselon_ii=trim($_GET['c_eselon_ii']);}
			$this->view->c_eselon_ii =$c_eselon_ii;
			if ($c_eselon_ii){
			$expesl2 = explode(";",$c_eselon_ii);
			$c_eselon_ii=$expesl2[0];
			$c_parent=$expesl2[1];
			}
			
			
			if ($_POST['c_eselon_iii']){$c_eselon_iii=$_POST['c_eselon_iii'];}
			else{$c_eselon_iii=trim($_GET['c_eselon_iii']);}
			$this->view->c_eselon_iii =trim($c_eselon_iii);		
			if ($c_eselon_iii){
			$expesl3 = explode(";",$c_eselon_iii);	
			$c_eselon_iix=$expesl3[0];
			$c_eselon_iii=$expesl3[1];
			$c_satker=$expesl3[2];			
			}

			if ($_POST['c_eselon_iv']){$c_eselon_iv=$_POST['c_eselon_iv'];}
			else{$c_eselon_iv=trim($_GET['c_eselon_iv']);}
			$this->view->c_eselon_iv =trim($c_eselon_iv);	
			if ($c_eselon_iv){
			$expesl4 = explode(";",$c_eselon_iv);	
				$c_eselon_iv=$expesl4[0];	
				$c_eselon_v=$expesl4[0];
			}			
			

			
			if ($c_eselon_i){$cari .= " and c_eselon_i='$c_eselon_i'";}
			if ($c_eselon_ii ){$cari .= " and c_eselon_ii='$c_eselon_ii'";}
			if ($c_eselon_iii){$cari .= " and c_eselon_iii='$c_eselon_iii' and c_parent='$c_parent' and c_satker='$c_satker'";}
			if ($c_eselon_iv){$cari .= " and c_eselon_iv='$c_eselon_iv'";}
			if ($c_eselon_v){$cari .= " and c_eselon_v='$c_eselon_v'";}
			
			//echo $cari;
			//echo " and c_eselon_i='$c_eselon_i'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child <> '00' and c_parent ='$c_parent'  and c_lokasi_unitkerja='$c_lokasi_unitkerja'";
			
			$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_iii = '00'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child = '00' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child <> '00' and c_parent ='$c_parent'  and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			
			//$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_level ='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_parent ='$c_parent'");
			
		}		

	}


}
else{
 	$c_eselon_i=$this->view->c_eselon_i;
	$c_eselon_ii=$this->view->c_eselon_ii;
	$c_eselon_iii=$this->view->c_eselon_iii;
	$c_eselon_iv=$this->view->c_eselon_iv;
	$c_eselon_v=$this->view->c_eselon_v;	
	
	if ($this->view->sektoral=='D'){
		//jika dalam sektoral
		if ($this->view->c_eselon=='01' || $this->view->c_eselon=='02')
			{$cari .= " and (c_eselon_i='$c_eselon_i' or c_eselon_i_cpns='$c_eselon_i') ";}
		if ($this->view->c_eselon=='03' || $this->view->c_eselon=='04')
			{$cari .= " and (c_eselon_i='$c_eselon_i' or c_eselon_i_cpns='$c_eselon_i') and (c_eselon_ii='$c_eselon_ii' or c_eselon_ii_cpns='$c_eselon_ii')";}			
		if ($this->view->c_eselon=='05' || $this->view->c_eselon=='06')
			{$cari .= " and (c_eselon_i='$c_eselon_i' or c_eselon_i_cpns='$c_eselon_i') and (c_eselon_ii='$c_eselon_ii' or c_eselon_ii_cpns='$c_eselon_ii') and (c_eselon_iii='$c_eselon_iii' or c_eselon_iii_cpns='$c_eselon_iii')";}
		if ($this->view->c_eselon=='07' || $this->view->c_eselon=='08')
			{$cari .= " and (c_eselon_i='$c_eselon_i' or c_eselon_i_cpns='$c_eselon_i') and (c_eselon_ii='$c_eselon_ii' or c_eselon_ii_cpns='$c_eselon_ii') and (c_eselon_iii='$c_eselon_iii' or c_eselon_iii_cpns='$c_eselon_iii') and (c_eselon_iv='$c_eselon_iv' or c_eselon_iv_cpns='$c_eselon_iv')";}		
	}
	if ($this->view->sektoral=='L'){
		//jika lintas sektoral
		$cari .= " and (c_eselon_i='$c_eselon_i' or c_eselon_i_cpns='$c_eselon_i') and (c_eselon_ii='$c_eselon_ii' or c_eselon_ii_cpns='$c_eselon_ii')";	
	}
	
}	
	$orderBy=$_GET['orderBy'];
	$order=$_GET['order'];
	if (!$_GET['order']){$this->view->orderbya="asc";$this->view->orderbyb="desc";}
	else{
		if ($_GET['order']=='desc'){	$this->view->orderbya="desc";$this->view->orderbyb="asc";}
		else{$this->view->orderbya="asc";$this->view->orderbyb="desc";}
	}
	
	if ($_GET['orderBy']){$orderBy=" order by $orderBy $order";}
	else{$orderBy=" order by c_golongan asc";}
	$this->view->orderBy=$_GET['orderBy'];
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;} $numToDisplay = 10;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;

	//echo $cari;	
		$this->view->totalpegawaiList = $this->pegawai_serv->getPegawaiList($cari, 0, 0 ,$orderBy);		
		$this->view->pegawaiList = $this->pegawai_serv->getPegawaiList($cari, $currentPage, $numToDisplay,$orderBy );	
		
		$sesmenu = new Zend_Session_Namespace('sesmenu');
		$sesmenu->menu= $_GET['menu'];
		$this->view->menu= $_GET['menu'];
		$sespeg = new Zend_Session_Namespace('sespeg');
		$sespeg->nama= $datapegawai[0]['n_peg'];
		$sespeg->nip= '';
	
		$this->render('listpegawai');
    }		
	
}
?>