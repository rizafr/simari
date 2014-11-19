<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/refferensi/Pengajuancuti_Service.php";
//require_once "service/sdm/sdm_refferensi_Service.php";
//require_once "service/sdm/Sdm_Pegawai_Service.php";

class Sdmmodule_PengajuancutiController extends Zend_Controller_Action {
		
    public function init() {
  		$this->_helper->layout->setLayout('target-column');
  		$registry = Zend_Registry::getInstance();
  		$this->view->basePath = $registry->get('basepath'); 
  		$this->view->leftMenu = $registry->get('leftMenu'); 		
  		$this->pengajuancuti_serv = Pengajuancuti_Service::getInstance();
		
  		$ssologin = new Zend_Session_Namespace('ssologin');
  		
  		if ($ssologin->userid && $ssologin->n_peg){
  			$this->userid  			= $ssologin->userid;
  			$this->password			= $ssologin->password;
  			$this->i_peg_nip  		= $ssologin->i_peg_nip;
  			$this->i_peg_nip_new	= $ssologin->i_peg_nip_new;
  			$this->n_peg  			= $ssologin->n_peg;
  			$this->n_peg_gelardepan = $ssologin->n_peg_gelardepan;
  			$this->n_peg_gelarblkg 	= $ssologin->n_peg_gelarblkg;
  			$this->c_jabatan 		= $ssologin->c_jabatan;
  			$this->c_eselon_i 		= $ssologin->c_eselon_i;
  			$this->c_eselon_ii 		= $ssologin->c_eselon_ii;
  			$this->c_eselon_iii 	= $ssologin->c_eselon_iii;
  			$this->c_eselon_iv 		= $ssologin->c_eselon_iv;
  			$this->c_eselon_v 		= $ssologin->c_eselon_v; 
  			$this->c_lokasi_unitkerja = $ssologin->c_lokasi_unitkerja; 
  			$this->c_satker			= $ssologin->c_satker; 
  			$this->n_satker			= $ssologin->n_satker; 
  			$this->c_izin			= $ssologin->view->userIzin; 
  			$this->checkotoritas	= $ssologin->view->checkotoritas; 
  		}	
    }
	
    public function indexAction() {
	   
    }
    
public function pengajuancutijsAction() 
{
	header('content-type : text/javascript');
	$this->render('pengajuancutijs');
}	
	
public function pengajuancutiolahdataAction() {
	
	
	$this->view->JenisCuti = $this->pengajuancuti_serv->getJenisCuti();
	
		$par=$_GET['par'];
	if ($par=='insert'){
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
		//$this->view->c_lokasi_unitkerja_cpns='1';
	}
	else{
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$nip=$_GET['nip'];
		if (!$nip){$nip=$this->view->nip;}
		$this->listDataByKey($nip);
	}	
	
	
	
/* 	$d_cuti_mulai = strtotime($_REQUEST['d_cuti_mulai']);
	$d_cuti_akhir = strtotime($_REQUEST['d_cuti_akhir']);
	
	if($d_cuti_mulai != "" && $d_cuti_akhir != ""){
	if($d_cuti_mulai == $d_cuti_akhir){
	$satu = 1;
	$this->view->jumlah_cuti = $satu;
	}elseif($d_cuti_mulai < $d_cuti_akhir){
	$jumlah_cuti = floor(abs($d_cuti_mulai - $d_cuti_akhir) / 86400)+1;
	$this->view->jumlah_cuti = $jumlah_cuti;
	}
	}
	
	$this->view->detailPengajuancuti = array();
	if($this->view->par == 'update'){
		$masukan = array("i_peg_nip" => $this->view->i_peg_nip);
		$this->view->detailPengajuancuti = $this->pengajuancuti_serv->detailPengajuancuti($masukan);
	} */
	
}

public function pengajuancutieditAction() {
	
	
	$this->view->JenisCuti = $this->pengajuancuti_serv->getJenisCuti();
	
	$d_cuti_mulai = strtotime($_REQUEST['d_cuti_mulai']);
	$d_cuti_akhir = strtotime($_REQUEST['d_cuti_akhir']);
	
	if($d_cuti_mulai != "" && $d_cuti_akhir != ""){
	if($d_cuti_mulai == $d_cuti_akhir){
	$satu = 1;
	$this->view->jumlah_cuti = $satu;
	}elseif($d_cuti_mulai < $d_cuti_akhir){
	$jumlah_cuti = floor(abs($d_cuti_mulai - $d_cuti_akhir) / 86400)+1;
	$this->view->jumlah_cuti = $jumlah_cuti;
	}
	}
	
	$this->view->detailPengajuancuti = array();
	if($this->view->par == 'Update'){
		$masukan = array("i_peg_nip" => $this->view->i_peg_nip);
		$this->view->detailPengajuancuti = $this->pengajuancuti_serv->detailPengajuancuti($masukan);
	}
	
}

public function listnamapejabatAction() {
	$this->view->nom=$_GET['nom'];
	$this->view->par=$_GET['par'];
	$this->view->nip=$_GET['nip'];
	
	$this->view->c_lokasi_unitkerja=$_GET['c_lokasi_unitkerja'];
	$this->view->eseloni=$_GET['eseloni'];
	$this->view->eselonii=$_GET['eselonii'];
	$this->view->eseloniii=$_GET['eseloniii'];
	$this->view->eseloniv=$_GET['eseloniv'];
	
	$nip=$_GET['nip'];
	
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 13;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
if ($_GET['cCol']=='unitkerja'){

		
		$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);
		$this->view->c_lokasi_unitkerja=$c_lokasi_unitkerja;
			$c_eselon_i=$_GET['eseloni'];			
			$c_eselon_ii=$_GET['eselonii'];		
			$c_eselon_iii=$_GET['eseloniii'];			
			$c_eselon_iv=$_GET['eseloniv'];		
			$c_eselon_v=$_GET['eselonv'];				
		if ($c_lokasi_unitkerja=='1'){			
			
			
		
			$this->view->eseloniList = $this->pengajuancuti_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");		
			$expesl1 = explode(";",$c_eselon_i);
			$c_eselon_i=$expesl1[0];	
			$this->view->eseloniiList = $this->pengajuancuti_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and trim(c_tkt_esl)='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
			
			$expesl2 = explode(";",$c_eselon_ii);
			$c_eselon_ii=$expesl2[0];
			$c_parent=$expesl2[1];
			$this->view->eseloniiiList = $this->pengajuancuti_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and trim(c_tkt_esl)='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");		


			$expesl3 = explode(";",$c_eselon_iii);	
			$c_eselon_iii=$expesl3[0];
			$this->view->eseloniiiList = $this->pengajuancuti_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and trim(c_tkt_esl)='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			$this->view->eselonivList = $this->pengajuancuti_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and trim(c_tkt_esl)='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			
			$expesl4 = explode(";",$c_eselon_iv);	
			$c_eselon_iv=$expesl4[0];
			
			if ($c_eselon_i){$cari .= " and c_eselon_i='$c_eselon_i'";}
			if ($c_eselon_ii ){$cari .= " and c_eselon_ii='$c_eselon_ii'";}
			if ($c_eselon_iii){$cari .= " and c_eselon_iii='$c_eselon_iii'";}
			if ($c_eselon_iv){$cari .= " and c_eselon_iv='$c_eselon_iv'";}

			$this->view->c_eselon_i=$_GET['c_eselon_i'];
			$this->view->c_eselon_ii=$_GET['c_eselon_ii'];
			$this->view->c_eselon_iii=$_GET['c_eselon_iii'];
			$this->view->c_eselon_iv=$_GET['c_eselon_iv'];
			$this->view->c_eselon_v=$_GET['c_eselon_v'];
			
			if ($_POST['c_lokasi_unitkerja']){$c_lokasi_unitkerja=trim($_POST['c_lokasi_unitkerja']);}
			else{$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);}
			$this->view->lokasiList = $this->pengajuancuti_serv->getLokasi('');

			
		}
		else
		{

			$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);
			$this->view->lokasiList = $this->pengajuancuti_serv->getLokasi('');
			$this->view->eseloniList = $this->pengajuancuti_serv->getTrUnitKerjaPngl(" and c_lokasi_unitkerja='3'");

			
			$c_eselon_i=trim($_GET['eseloni']);
			if ($c_eselon_i){
				$expesl1 = explode(";",$c_eselon_i);
				$c_eselon_i=$expesl1[0];
			}
			
			$c_eselon_ii=trim($_GET['eselonii']);
			if ($c_eselon_ii){
			$expesl2 = explode(";",$c_eselon_ii);
			$c_eselon_ii=$expesl2[0];
			$c_parent=$expesl2[1];
			}
			
			
			$c_eselon_iii=trim($_GET['eseloniii']);	
			if ($c_eselon_iii){
			$expesl3 = explode(";",$c_eselon_iii);	
			$c_eselon_iix=$expesl3[0];
			$c_eselon_iii=$expesl3[1];
			$c_satker=$expesl3[2];			
			}

			$c_eselon_iv=trim($_GET['eseloniv']);
			if ($c_eselon_iv){
			$expesl4 = explode(";",$c_eselon_iv);	
			$c_eselon_iv=$expesl4[0];
			}			
			
			if ($c_eselon_i){$cari .= " and c_eselon_i='$c_eselon_i'";}
			if ($c_eselon_iii){$cari .= "  and c_satker='$c_satker'";}
			else if ($c_eselon_iii == '' &&  $c_eselon_ii != '') {
				$cari .= " and c_eselon_ii='$c_eselon_ii'";
			}
			if ($c_eselon_iv){$cari .= " and c_eselon_iv='$c_eselon_iv'";}
		
			$this->view->eseloniiList = $this->pengajuancuti_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_iii = '00'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child = '00' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			$this->view->eseloniiiList = $this->pengajuancuti_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child <> '00' and c_parent ='$c_parent'  and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			$this->view->eselonivList = $this->pengajuancuti_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_iii <> '00' and c_eselon_ii='$c_eselon_iix'  and c_lokasi_unitkerja='$c_lokasi_unitkerja' ");
						
			$this->view->c_eselon_i=$_GET['c_eselon_i'];
			$this->view->c_eselon_ii=$_GET['c_eselon_ii'];
			$this->view->c_eselon_iii=$_GET['c_eselon_iii'];
			$this->view->c_eselon_iv=$_GET['c_eselon_iv'];
			$this->view->c_eselon_v=$_GET['c_eselon_v'];


		}		

	

}
else
{
	if ($_GET['nCol'])
	{
		$nCol=strtoupper($_GET['nCol']);
		$cCol=$_GET['cCol'];	
		 $this->view->nCol = $_GET['nCol'];
		 $this->view->cCol = $_GET['cCol'];	
	}
	else{
		$nCol=strtoupper($_GET['nCol']);
		$cCol=$_GET['cCol'];
		 $this->view->nCol = $_GET['nCol'];
		 $this->view->cCol = $_GET['cCol'];	
	}

}	

	
	if($nip){		
		$nip= str_replace("\'", "'", $nip);
		$nip= str_replace(",''s", " ", $nip);
		$nip= str_replace(",''", "", $nip);
		$nip= str_replace("''", "'", $nip);
		
		$cari .= " and (i_peg_nip not in ($nip) or i_peg_nip_new not in ($nip) ) ";
	}
	
	$this->view->cCol=$_GET['cCol'];
	$this->view->c_eselon_i =$_GET['eseloni'];
	$this->view->c_eselon_ii =$_GET['eselonii'];
	$this->view->c_eselon_iii =$_GET['eseloniii'];
	$this->view->c_eselon_iv =$_GET['eseloniv'];
	$this->view->c_eselon_v =$_GET['eselonv'];
		

	if ($nCol && $cCol ){
		if ($cCol=='i_peg_nip'){
			$cari .=" and (i_peg_nip like '%$nCol%' or i_peg_nip_new like '%$nCol%') ";
			
		}
		else{$cari .= " and $cCol like '%$nCol%' ";}
	}	
	$cari .=" and (c_eselon !='17' or c_eselon isnull)"; 	
	$this->view->totalpegawaiList = $this->pengajuancuti_serv->getPegawaiList($cari,0,0);		
	$this->view->PegawaiList = $this->pengajuancuti_serv->getPegawaiList($cari,$currentPage,$numToDisplay);
}



public function listnamapejabat2Action() {
	$this->view->nom=$_GET['nom'];
	$this->view->par=$_GET['par'];
	$this->view->nip=$_GET['nip'];
	
	$this->view->c_lokasi_unitkerja=$_GET['c_lokasi_unitkerja'];
	$this->view->eseloni=$_GET['eseloni'];
	$this->view->eselonii=$_GET['eselonii'];
	$this->view->eseloniii=$_GET['eseloniii'];
	$this->view->eseloniv=$_GET['eseloniv'];
	
	$nip=$_GET['nip'];
	
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 13;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
if ($_GET['cCol']=='unitkerja'){

		
		$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);
		$this->view->c_lokasi_unitkerja=$c_lokasi_unitkerja;
			$c_eselon_i=$_GET['eseloni'];			
			$c_eselon_ii=$_GET['eselonii'];		
			$c_eselon_iii=$_GET['eseloniii'];			
			$c_eselon_iv=$_GET['eseloniv'];		
			$c_eselon_v=$_GET['eselonv'];				
		if ($c_lokasi_unitkerja=='1'){			
			
			
		
			$this->view->eseloniList = $this->pengajuancuti_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");		
			$expesl1 = explode(";",$c_eselon_i);
			$c_eselon_i=$expesl1[0];	
			$this->view->eseloniiList = $this->pengajuancuti_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and trim(c_tkt_esl)='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
			
			$expesl2 = explode(";",$c_eselon_ii);
			$c_eselon_ii=$expesl2[0];
			$c_parent=$expesl2[1];
			$this->view->eseloniiiList = $this->pengajuancuti_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and trim(c_tkt_esl)='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");		


			$expesl3 = explode(";",$c_eselon_iii);	
			$c_eselon_iii=$expesl3[0];
			$this->view->eseloniiiList = $this->pengajuancuti_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and trim(c_tkt_esl)='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			$this->view->eselonivList = $this->pengajuancuti_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and trim(c_tkt_esl)='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			
			$expesl4 = explode(";",$c_eselon_iv);	
			$c_eselon_iv=$expesl4[0];
			
			if ($c_eselon_i){$cari .= " and c_eselon_i='$c_eselon_i'";}
			if ($c_eselon_ii ){$cari .= " and c_eselon_ii='$c_eselon_ii'";}
			if ($c_eselon_iii){$cari .= " and c_eselon_iii='$c_eselon_iii'";}
			if ($c_eselon_iv){$cari .= " and c_eselon_iv='$c_eselon_iv'";}

			$this->view->c_eselon_i=$_GET['c_eselon_i'];
			$this->view->c_eselon_ii=$_GET['c_eselon_ii'];
			$this->view->c_eselon_iii=$_GET['c_eselon_iii'];
			$this->view->c_eselon_iv=$_GET['c_eselon_iv'];
			$this->view->c_eselon_v=$_GET['c_eselon_v'];
			
			if ($_POST['c_lokasi_unitkerja']){$c_lokasi_unitkerja=trim($_POST['c_lokasi_unitkerja']);}
			else{$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);}
			$this->view->lokasiList = $this->pengajuancuti_serv->getLokasi('');

			
		}
		else
		{

			$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);
			$this->view->lokasiList = $this->pengajuancuti_serv->getLokasi('');
			$this->view->eseloniList = $this->pengajuancuti_serv->getTrUnitKerjaPngl(" and c_lokasi_unitkerja='3'");

			
			$c_eselon_i=trim($_GET['eseloni']);
			if ($c_eselon_i){
				$expesl1 = explode(";",$c_eselon_i);
				$c_eselon_i=$expesl1[0];
			}
			
			$c_eselon_ii=trim($_GET['eselonii']);
			if ($c_eselon_ii){
			$expesl2 = explode(";",$c_eselon_ii);
			$c_eselon_ii=$expesl2[0];
			$c_parent=$expesl2[1];
			}
			
			
			$c_eselon_iii=trim($_GET['eseloniii']);	
			if ($c_eselon_iii){
			$expesl3 = explode(";",$c_eselon_iii);	
			$c_eselon_iix=$expesl3[0];
			$c_eselon_iii=$expesl3[1];
			$c_satker=$expesl3[2];			
			}

			$c_eselon_iv=trim($_GET['eseloniv']);
			if ($c_eselon_iv){
			$expesl4 = explode(";",$c_eselon_iv);	
			$c_eselon_iv=$expesl4[0];
			}			
			
			if ($c_eselon_i){$cari .= " and c_eselon_i='$c_eselon_i'";}
			if ($c_eselon_iii){$cari .= "  and c_satker='$c_satker'";}
			else if ($c_eselon_iii == '' &&  $c_eselon_ii != '') {
				$cari .= " and c_eselon_ii='$c_eselon_ii'";
			}
			if ($c_eselon_iv){$cari .= " and c_eselon_iv='$c_eselon_iv'";}
		
			$this->view->eseloniiList = $this->pengajuancuti_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_iii = '00'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child = '00' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			$this->view->eseloniiiList = $this->pengajuancuti_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child <> '00' and c_parent ='$c_parent'  and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			$this->view->eselonivList = $this->pengajuancuti_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_iii <> '00' and c_eselon_ii='$c_eselon_iix'  and c_lokasi_unitkerja='$c_lokasi_unitkerja' ");
						
			$this->view->c_eselon_i=$_GET['c_eselon_i'];
			$this->view->c_eselon_ii=$_GET['c_eselon_ii'];
			$this->view->c_eselon_iii=$_GET['c_eselon_iii'];
			$this->view->c_eselon_iv=$_GET['c_eselon_iv'];
			$this->view->c_eselon_v=$_GET['c_eselon_v'];


		}		

	

}
else
{
	if ($_GET['nCol'])
	{
		$nCol=strtoupper($_GET['nCol']);
		$cCol=$_GET['cCol'];	
		 $this->view->nCol = $_GET['nCol'];
		 $this->view->cCol = $_GET['cCol'];	
	}
	else{
		$nCol=strtoupper($_GET['nCol']);
		$cCol=$_GET['cCol'];
		 $this->view->nCol = $_GET['nCol'];
		 $this->view->cCol = $_GET['cCol'];	
	}

}	

	
	if($nip){		
		$nip= str_replace("\'", "'", $nip);
		$nip= str_replace(",''s", " ", $nip);
		$nip= str_replace(",''", "", $nip);
		$nip= str_replace("''", "'", $nip);
		
		$cari .= " and (i_peg_nip not in ($nip) or i_peg_nip_new not in ($nip) ) ";
	}
	
	$this->view->cCol=$_GET['cCol'];
	$this->view->c_eselon_i =$_GET['eseloni'];
	$this->view->c_eselon_ii =$_GET['eselonii'];
	$this->view->c_eselon_iii =$_GET['eseloniii'];
	$this->view->c_eselon_iv =$_GET['eseloniv'];
	$this->view->c_eselon_v =$_GET['eselonv'];
		

	if ($nCol && $cCol ){
		if ($cCol=='i_peg_nip'){
			$cari .=" and (i_peg_nip like '%$nCol%' or i_peg_nip_new like '%$nCol%') ";
			
		}
		else{$cari .= " and $cCol like '%$nCol%' ";}
	}	
	$cari .=" and (c_eselon !='17' or c_eselon isnull)"; 	
	$this->view->totalpegawaiList = $this->pengajuancuti_serv->getPegawaiList($cari,0,0);		
	$this->view->PegawaiList = $this->pengajuancuti_serv->getPegawaiList($cari,$currentPage,$numToDisplay);
}




public function listcomboxAction() {
	
	$this->view->lokasiList = $this->pengajuancuti_serv->getLokasi("");
	$this->view->c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);
	$this->view->c_eselon_i =$_GET['eseloni'];
	$this->view->c_eselon_ii =$_GET['eselonii'];
	$this->view->c_eselon_iii =$_GET['eseloniii'];
	$this->view->c_eselon_iv =$_GET['eseloniv'];
	$this->view->c_eselon_v =$_GET['eselonv'];
	$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);
	$c_eselon_i=$_GET['eseloni'];
	$c_eselon_ii=$_GET['eselonii'];
	$c_eselon_iii=$_GET['eseloniii'];
	$c_eselon_iv=$_GET['eseloniv'];
	$c_eselon_v=$_GET['eselonv'];
	
	
	if ($_GET['c_lokasi_unitkerja']=='3'){
	
		$this->view->eseloniList = $this->pengajuancuti_serv->getTrUnitKerjaPngl(" and c_lokasi_unitkerja='3'");
		$c_eselon_i=$_GET['eseloni'];
		$expesl1 = explode(";",$c_eselon_i);
		$c_eselon_i=$expesl1[0];			

		$c_eselon_ii=$_GET['eselonii'];
		$expesl2 = explode(";",$c_eselon_ii);
		$c_eselon_ii=$expesl2[0];
		$c_parent=$expesl2[1];


		$c_eselon_iii=$_GET['eseloniii'];
		if ($c_eselon_iii){
		$expesl3 = explode(";",$c_eselon_iii);	
		$c_eselon_iii=$expesl3[0];
		}

		$this->view->eseloniiList = $this->pengajuancuti_serv->getTrUnitKerja(" and c_level ='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_eselon_i='$c_eselon_i' ");
		$this->view->eseloniiiList = $this->pengajuancuti_serv->getTrUnitKerja(" and  c_level ='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_eselon_i='$c_eselon_i'  and c_parent ='$c_parent'");
		$this->view->eselonivList = $this->pengajuancuti_serv->getTrUnitKerja(" and c_level ='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_parent ='$c_parent'");
	
		$this->render('listcombo2x');
	}
	else{
		$this->view->eseloniList = $this->pengajuancuti_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");		
		$c_eselon_i=$_GET['eseloni'];
		$expesl1 = explode(";",$c_eselon_i);
		$c_eselon_i=$expesl1[0];	
		$this->view->eseloniiList = $this->pengajuancuti_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and trim(c_tkt_esl)='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
		
		$c_eselon_ii=$_GET['eselonii'];
		$expesl2 = explode(";",$c_eselon_ii);
		$c_eselon_ii=$expesl2[0];
		$c_parent=$expesl2[1];
		$this->view->eseloniiiList = $this->pengajuancuti_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and trim(c_tkt_esl)='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");		


		$c_eselon_iii=$_GET['eseloniii'];
		$expesl3 = explode(";",$c_eselon_iii);	
		$c_eselon_iii=$expesl3[0];
		$this->view->eseloniiiList = $this->pengajuancuti_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and trim(c_tkt_esl)='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
		$this->view->eselonivList = $this->pengajuancuti_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and trim(c_tkt_esl)='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

	
		$this->render('listcombox');
	}	
}

public function insertpengajuancutiAction(){
	$i_peg_nip = $_POST['i_peg_nip'];
	$c_jenis_cuti = $_POST['c_jenis_cuti'];
	$d_cuti_mulai = $_POST['d_cuti_mulai'];
	$d_cuti_akhir = $_POST['d_cuti_akhir'];
	$i_surat_cuti = $_POST['i_surat_cuti'];
	$i_sisa_cuti = $_POST['i_sisa_cuti'];
	$q_lama_cuti = $_POST['q_lama_cuti'];
	$a_alamat_cuti = $_POST['a_alamat_cuti'];
	$a_cuti_rt = $_POST['a_cuti_rt'];
	$a_cuti_rw = $_POST['a_cuti_rw'];
	$a_cuti_propinsi = $_POST['a_cuti_propinsi'];
	$a_cuti_kota = $_POST['a_cuti_kota'];
	$a_cuti_kodepos = $_POST['a_cuti_kodepos'];
	$q_cuti_telponrumah = $_POST['q_cuti_telponrumah'];
	$q_cuti_telponhp = $_POST['q_cuti_telponhp'];
	$e_cuti_alasan = $_POST['e_cuti_alasan'];
	$i_peg_nip_atasan = $_POST['i_peg_nip_atasan'];
		
	if($_POST['c_jenis_cuti'] == "--Pilih--"){
	$c_jenis_cuti = "";
	}else{
	$c_jenis_cuti = $_POST['c_jenis_cuti'];
	
	}	
	
	$masukanInsert = array("i_peg_nip" => $i_peg_nip,
			"c_jenis_cuti" => $c_jenis_cuti,
			"d_cuti_mulai" => $d_cuti_mulai,
			"d_cuti_akhir" => $d_cuti_akhir,
			"i_surat_cuti" => $i_surat_cuti,
			"i_sisa_cuti" => $i_sisa_cuti,
			"q_lama_cuti" => $q_lama_cuti,
			"a_alamat_cuti" => $a_alamat_cuti,
			"a_cuti_rt" => $a_cuti_rt,
			"a_cuti_rw" => $a_cuti_rw,
			"a_cuti_propinsi" => $a_cuti_propinsi,
			"a_cuti_kota" => $a_cuti_kota,
			"a_cuti_kodepos" => $a_cuti_kodepos,
			"q_cuti_telponrumah" => $q_cuti_telponrumah,
			"q_cuti_telponhp" => $q_cuti_telponhp,
			"e_cuti_alasan" => $e_cuti_alasan,			
			"i_entry" => $this->userid,
			"i_peg_nip_atasan" => $i_peg_nip_atasan);
	$hasil = $this->pengajuancuti_serv->tambahpengajuancuti($masukanInsert);
	$this->view->proses 	= '1';
	$this->view->keterangan = 'Pengajuan Cuti';
	$this->view->status	= $hasil;
	$this->pengajuancutiolahdataAction();
	$this->render(pengajuancutiolahdata);
}

public function updatepengajuancutiAction(){
	$i_peg_nip = $_POST['i_peg_nip'];
	$c_jenis_cuti = $_POST['c_jenis_cuti'];
	$d_cuti_mulai = $_POST['d_cuti_mulai'];
	$d_cuti_akhir = $_POST['d_cuti_akhir'];
	$i_surat_cuti = $_POST['i_surat_cuti'];
	$i_sisa_cuti = $_POST['i_sisa_cuti'];
	$q_lama_cuti = $_POST['q_lama_cuti'];
	$a_alamat_cuti = $_POST['a_alamat_cuti'];
	$a_cuti_rt = $_POST['a_cuti_rt'];
	$a_cuti_rw = $_POST['a_cuti_rw'];
	$a_cuti_propinsi = $_POST['a_cuti_propinsi'];
	$a_cuti_kota = $_POST['a_cuti_kota'];
	$a_cuti_kodepos = $_POST['a_cuti_kodepos'];
	$q_cuti_telponrumah = $_POST['q_cuti_telponrumah'];
	$q_cuti_telponhp = $_POST['q_cuti_telponhp'];
	$e_cuti_alasan = $_POST['e_cuti_alasan'];
	$i_peg_nip_atasan = $_POST['i_peg_nip_atasan'];
		
	if($_POST['c_jenis_cuti'] == "--Pilih--"){
	$c_jenis_cuti = "";
	}else{
	$c_jenis_cuti = $_POST['c_jenis_cuti'];
	
	}	
	
	
	$masukanInsert = array("i_peg_nip" => $i_peg_nip,
			"c_jenis_cuti" => $c_jenis_cuti,
			"d_cuti_mulai" => $d_cuti_mulai,
			"d_cuti_akhir" => $d_cuti_akhir,
			"i_surat_cuti" => $i_surat_cuti,
			"i_sisa_cuti" => $i_sisa_cuti,
			"q_lama_cuti" => $q_lama_cuti,
			"a_alamat_cuti" => $a_alamat_cuti,
			"a_cuti_rt" => $a_cuti_rt,
			"a_cuti_rw" => $a_cuti_rw,
			"a_cuti_propinsi" => $a_cuti_propinsi,
			"a_cuti_kota" => $a_cuti_kota,
			"a_cuti_kodepos" => $a_cuti_kodepos,
			"q_cuti_telponrumah" => $q_cuti_telponrumah,
			"q_cuti_telponhp" => $q_cuti_telponhp,
			"e_cuti_alasan" => $e_cuti_alasan,			
			"i_entry" => $this->userid,
			"i_peg_nip_atasan" => $i_peg_nip_atasan);
	$hasil = $this->pengajuancuti_serv->ubahpengajuancuti($masukanInsert);
	$this->view->proses 	= '2';
	$this->view->keterangan = 'Pengajuan Cuti';
	$this->view->status	= $hasil;
	$this->pengajuancutiolahdataAction();
	$this->render(pengajuancutiolahdata);
}

public function deletepengajuancutiAction(){
	$i_peg_nip = $_REQUEST['i_peg_nip'];
	
	$masukanInsert = array("i_peg_nip" => $i_peg_nip);
	$hasil = $this->pengajuancuti_serv->hapuspengajuancuti($masukanInsert);
	$this->view->proses 	= '3';
	$this->view->keterangan = 'Pengajuan Cuti';
	$this->view->status	= $hasil;
	$this->pengajuancutiolahdataAction();
	$this->render(pengajuancutiolahdata);
}	

/* public function cpnsAction() {
	$this->view->JenisCuti = $this->pengajuancuti_serv->getJenisCuti();

	$par=$_GET['par'];
	if ($par=='insert'){
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
		//$this->view->c_lokasi_unitkerja_cpns='1';
	}
	else{
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$nip=$_GET['nip'];
		if (!$nip){$nip=$this->view->nip;}
		$this->listDataByKey($nip);
	}	
} */


public function maintaindataAction() {

	
	$MaintainData = array("i_peg_nip"=>$_POST['i_peg_nip'],
			"c_jenis_cuti"=>$_POST['c_jenis_cuti'],
			"d_cuti_mulai"=>$_POST['d_cuti_mulai'],
			"d_cuti_akhir"=>$_POST['d_cuti_akhir'],
			"i_surat_cuti"=>$_POST['i_surat_cuti'],
			"i_sisa_cuti"=>$_POST['i_sisa_cuti'],
			"q_lama_cuti"=>$_POST['q_lama_cuti'],
			"a_alamat_cuti"=>$_POST['a_alamat_cuti'],
			"a_cuti_rt"=>$_POST['a_cuti_rt'],
			"a_cuti_rw"=>$_POST['a_cuti_rw'],
			"a_cuti_propinsi"=>$_POST['a_cuti_propinsi'],
			"a_cuti_kota"=>$_POST['a_cuti_kota'],
			"a_cuti_kodepos"=>$_POST['a_cuti_kodepos'],
			"q_cuti_telponrumah"=>$_POST['q_cuti_telponrumah'],
			"q_cuti_telponhp"=>$_POST['q_cuti_telponhp'],
			"e_cuti_alasan"=>$_POST['e_cuti_alasan'],			
			"i_entry"=>$this->userid,
			"i_peg_nip_atasan"=>$_POST['i_peg_nip_atasan']);		

	if ($_POST['proses']=='Simpan')
	{
		$hasil = $this->pengajuancuti_serv->maintainDataCpns($MaintainData,'insert');		
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
	}
	else if ($_POST['proses']=='Hapus')
	{
		$hasil = $this->pengajuancuti_serv->maintainDataCpns($MaintainData,'delete');		
		$this->view->par="Hapus";
		$this->view->jdl="Menghapus ";
		$par="Menghapus";
	}	
	else
	{
		$hasil = $this->pengajuancuti_serv->maintainDataCpns($MaintainData,'update');
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";		
	}

	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	if ($cek=='gagal'){
//		$this->listDataByKeyPost();
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
		$this->render('pengajuancutiolahdata');
	}
	else{
//		$this->listDataByKey($_POST['i_peg_nip']) ;
		$this->pengajuancutiolahdataAction();	
/* 		if ($_POST['proses']=='Hapus'){$this->render('listpegawai');}
		else{$this->render('listpegawai');} */
//		$this->listDataByKey($i_peg_nip);
		if ($_POST['proses']=='Hapus'){$this->render('pengajuancutiolahdata');}
		else{$this->render('pengajuancutiolahdata');}		

				
		
	}	
}

public function listDataByKey($nip) {  
	$cari = " and i_peg_nip ='$nip' ";
	$datapegawai=$this->pengajuancuti_serv->getPegawaiListByNip($cari );

/* 	$sespeg = new Zend_Session_Namespace('sespeg');
	$sespeg->nama= $datapegawai[0]['n_peg'];
	$sespeg->nip= $datapegawai[0]['i_peg_nip'];
	$sespeg->golongan= $datapegawai[0]['c_peg_golongan'];
	$sespeg->pangkat= $datapegawai[0]['n_peg_pangkat'];
	$sespeg->filephoto= $datapegawai[0]['e_file_photo'];
	$sespeg->statuspeg= $datapegawai[0]['c_peg_status'];
	
	$this->view->nama= $datapegawai[0]['n_peg'];
	$this->view->nip= $datapegawai[0]['i_peg_nip'];
	$this->view->golongan= $datapegawai[0]['c_peg_golongan'];
	$this->view->pangkat= $datapegawai[0]['n_peg_pangkat'];	
	$this->view->filephoto= $datapegawai[0]['e_file_photo'];
	$this->view->statuspeg= $datapegawai[0]['c_peg_status'];	

	
	$this->view->i_peg_nrp=$datapegawai[0]['i_peg_nrp'];
	$this->view->i_peg_nip_new=$datapegawai[0]['i_peg_nip_new'];	
	if ($datapegawai[0]['i_peg_nip_new']==$datapegawai[0]['i_peg_nip']){
	$this->view->i_peg_nip="";
	}else{$this->view->i_peg_nip=$datapegawai[0]['i_peg_nip'];}
	$this->view->n_peg=$datapegawai[0]['n_peg'];
	$this->view->n_peg_gelardepan=$datapegawai[0]['n_peg_gelardepan'];
	$this->view->n_peg_gelarblkg=$datapegawai[0]['n_peg_gelarblkg'];
	$this->view->d_sk_cpns=$datapegawai[0]['d_sk_cpns'];
	$this->view->n_sk_pejabatcpns=$datapegawai[0]['n_sk_pejabatcpns'];
	$this->view->i_sk_cpns=$datapegawai[0]['i_sk_cpns'];
	$this->view->d_tmt_cpns=$datapegawai[0]['d_tmt_cpns'];
	$this->view->c_gol_cpns=$datapegawai[0]['c_gol_cpns'];
	$this->view->n_pangkat_cpns=$datapegawai[0]['n_pangkat_cpns'];
	$this->view->c_eselon_cpns=$datapegawai[0]['c_eselon_cpns'];
	$this->view->c_lokasi_unitkerja_cpns=trim($datapegawai[0]['c_lokasi_unitkerja_cpns']);



if ($c_lokasi_unitkerja=='1')	{
//echo " and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'";	
	$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
	$c_eselon_i=trim($datapegawai[0]['c_eselon_i_cpns']);
	$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	

	$c_eselon_ii=trim($datapegawai[0]['c_eselon_ii_cpns']);
	$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
	
	$c_eselon_iii=trim($datapegawai[0]['c_eselon_iii_cpns']);
	$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

	$c_eselon_iv=trim($datapegawai[0]['c_eselon_iv_cpns']);
	$this->view->eselonvList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii'  and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
	
	$c_eselon_v=trim($datapegawai[0]['c_eselon_v_cpns']);
	
	$this->view->c_eselon_i_cpns=trim($datapegawai[0]['c_eselon_i_cpns']).";".trim($datapegawai[0]['neseloncpns1']);
	$this->view->c_eselon_ii_cpns=trim($datapegawai[0]['c_eselon_ii_cpns']).";".trim($datapegawai[0]['neseloncpns2']);
	$this->view->c_eselon_iii_cpns=trim($datapegawai[0]['c_eselon_iii_cpns']).";".trim($datapegawai[0]['neseloncpns3']);
	$this->view->c_eselon_iv_cpns=trim($datapegawai[0]['c_eselon_iv_cpns']).";".trim($datapegawai[0]['neseloncpns4']);
	$this->view->c_eselon_v_cpns=trim($datapegawai[0]['c_eselon_v_cpns']);
	

}	
else
{
	$c_eselon_i=trim($datapegawai[0]['c_eselon_i_cpns']);
	$c_eselon_ii=trim($datapegawai[0]['c_eselon_ii_cpns']);
	$c_eselon_iii=trim($datapegawai[0]['c_eselon_iii_cpns']);
	$c_eselon_iv=trim($datapegawai[0]['c_eselon_iv_cpns']);
	$ceselon2=trim($datapegawai[0]['ceseloncpns2']);
	$c_satker=trim($datapegawai[0]['c_satker_cpns']);
	$c_parent=trim($datapegawai[0]['c_parent_cpns']); */


/* 	$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='3'");
	$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_iii = '00'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child = '00' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");		
	$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child <> '00' and c_parent ='$c_parent'  and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
	$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2'  and c_lokasi_unitkerja='$c_lokasi_unitkerja'"); */


/* 	
	$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and c_lokasi_unitkerja='3'");
	$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja("and c_level ='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_eselon_i='$c_eselon_i'");		
	$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and  c_level ='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_eselon_i='$c_eselon_i'  and c_parent ='$c_parent'");	
	$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_level ='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_parent ='$c_parent'");	

	
	$neseloncpns1=trim($datapegawai[0]['neseloncpns1']);
	$neseloncpns2=trim($datapegawai[0]['neseloncpns2']);
	$neseloncpns3=trim($datapegawai[0]['neseloncpns3']);
	$neseloncpns4=trim($datapegawai[0]['neseloncpns4']);

	$dataceselonii = $this->reff_serv->getTrUnitKerja(" and c_level ='2' and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_parent ='$c_parent'");		
	$c_eselon_iix=$dataceselonii[0]->c_eselon_ii;
	
	$this->view->c_eselon_i_cpns=trim($datapegawai[0]['c_eselon_i_cpns']).";".trim($datapegawai[0]['neseloncpns1']);
	$this->view->c_eselon_ii_cpns=$c_eselon_iix.";".trim($datapegawai[0]['c_parent_cpns']).";".trim($datapegawai[0]['neseloncpns2']);
	$this->view->c_eselon_iii_cpns=trim($datapegawai[0]['ceseloncpns2']).";".trim($datapegawai[0]['c_eselon_iii_cpns']).";".trim($datapegawai[0]['c_satker_cpns']).";".trim($datapegawai[0]['neseloncpns3']);
	$this->view->c_eselon_iv_cpns=trim($datapegawai[0]['c_eselon_iv_cpns']).";".trim($datapegawai[0]['neseloncpns4']); */
	
	//echo trim($datapegawai[0]['ceseloncpns2']).";".trim($datapegawai[0]['c_eselon_iii_cpns']).";".trim($datapegawai[0]['c_satker']).";".trim($datapegawai[0]['neseloncpns3']);
	
//}
	
/* 	$this->view->ceseloncpns=substr($datapegawai[0]['c_eselon_cpns'],1,1);
	


	$c_status_kepegawaian=$datapegawai[0]['c_status_kepegawaian'];
	if ($c_status_kepegawaian=='1' || $c_status_kepegawaian=='2' || $c_status_kepegawaian=='3')
	{$carigol=" and c_peg_tipegolongan ='3' ";}
	if ($c_status_kepegawaian=='4')
	{$carigol=" and c_peg_tipegolongan ='4' ";}
	if ($c_status_kepegawaian=='5')
	{$carigol=" and c_peg_tipegolongan ='5' ";}	
	if ($c_status_kepegawaian=='6')
	{$carigol=" and c_peg_tipegolongan ='6' ";}	
	if ($c_status_kepegawaian=='7')
	{$carigol=" and c_peg_tipegolongan ='7' ";}	
	$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai($carigol); 

	$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai($carigol);  */
	
/* 	if ($neseloncpns1){$nesl1=",$neseloncpns1";}
	if ($neseloncpns2){$nesl2=",$neseloncpns2";}
	if ($neseloncpns3){$nesl3=",$neseloncpns3";}
	if ($neseloncpns4){$nesl4=",$neseloncpns4";}
	if ($neseloncpns5){$nesl5=",$neseloncpns5";} */
	
/* 	if ($neseloncpns5){$nesl5=",$neseloncpns5";if($neseloncpns5){$nesl5=$nesl5.",";}}	
	if ($neseloncpns4){$nesl4="$neseloncpns4"; if($neseloncpns4){$nesl4=$nesl4.",";} }
	if ($neseloncpns3){$nesl3="$neseloncpns3"; if($neseloncpns3){$nesl3=$nesl3.",";} }
	if ($neseloncpns2){$nesl2="$neseloncpns2"; if($neseloncpns2){$nesl2=$nesl2.",";} }
	if ($neseloncpns1){$nesl1="$neseloncpns1";}	 */
	
	//$jabatanlengkap=$datapegawai[0]['n_jabatan_cpns'].", pada ".$nesl5.$nesl4.$nesl3.$nesl2.$nesl1;
/* 	$jabatanlengkap=$nesl5.$nesl4.$nesl3.$nesl2.$nesl1;
	$this->view->jabat_lengkap=$jabatanlengkap; */
}	
	
}?>