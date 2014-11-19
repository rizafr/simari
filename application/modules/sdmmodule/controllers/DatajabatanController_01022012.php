<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_Jabatan_Service.php";

class Sdmmodule_DatajabatanController extends Zend_Controller_Action {
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu'); 		
		$this->reff_serv = sdm_refferensi_Service::getInstance();
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->jabatan_serv = Sdm_Jabatan_Service::getInstance();
		$this->view->nama= $this->nama;
		$this->view->nip= $this->nip;
		$this->view->golongan= $this->golongan;
		$this->view->pangkat=$this->pangkat;
		$this->view->filephoto=$this->filephoto;
		$this->view->statuspeg=$this->statuspeg;
		
		$sespeg = new Zend_Session_Namespace('sespeg');

		$this->view->nama= $sespeg->nama;
		$this->view->nip= $sespeg->nip;
		$this->view->golongan= $sespeg->golongan;
		$this->view->pangkat= $sespeg->pangkat;	
		$this->view->filephoto= $sespeg->filephoto;	
		$this->view->statuspeg= $sespeg->statuspeg;	

		$ssologin = new Zend_Session_Namespace('ssologin');
		$this->view->userid=$ssologin->userid;
		$this->view->c_izin=$ssologin->c_izin[0]['c_izin'];		
    }
	
    public function indexAction() {
	   
    }
public function jabatanjsAction() 
{
	header('content-type : text/javascript');
	$this->render('jabatanjs');
}	
	
public function listjabatanAction() {
	$nip=$this->view->nip;	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->jabatanList = $this->jabatan_serv->getJabatanList($cari);	
		
}
public function jabatanAction() {

	$par=$_GET['par'];

	$this->view->eselonList = $this->reff_serv->getEselon('');		
	$this->view->lokasiList = $this->reff_serv->getLokasi('');	
	$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='1'");
	$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='2'");
	$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='3'");
	$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='4'");
	$this->view->eselonvList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='5'");
	
	if ($par=='insert'){
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
		//$this->view->c_lokasi_unitkerja='1';
	}
	else if ($par=='delete'){
		$this->view->par="Hapus";
		$this->view->jdl="Manghapus ";	
		$par=$_GET['par'];
		$jenisLatih=$_GET['jenisLatih'];
		$nLatih=$_GET['nLatih'];
		
		$this->listDataByKey($this->view->nip,$_GET['c_eselon'],$_GET['c_jabatan'],$_GET['d_mulai_jabat']);
	}
	else{
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";	
		$par=$_GET['par'];
		$jenisLatih=$_GET['jenisLatih'];
		$nLatih=$_GET['nLatih'];
		
		$this->listDataByKey($this->view->nip,$_GET['c_eselon'],$_GET['c_jabatan'],$_GET['d_mulai_jabat']);
		
		if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Merubah ";}		
		else{$this->view->jdl="Melihat ";$this->render('jabatanview');}			
	}


	
}

public function listcomboAction() {

	$jabatanlengkap="";
if ($_GET['c_lokasi_unitkerja']=='1'){		
	$this->view->c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);
	$this->view->lokasiList = $this->reff_serv->getLokasi('');	
	$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);
	$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	
	$c_eselon_i=$_GET['eseloni'];
	$c_eselon_i=substr($c_eselon_i,0,2);
	$this->view->c_eselon_i =trim($_GET['eseloni']);
	$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
	if ($_GET['eseloni']){$nesl1=$this->left($_GET['eseloni']); $nesl1=",".$nesl1;}
	
	$c_eselon_ii=$_GET['eselonii'];
	$c_eselon_ii=substr($c_eselon_ii,0,2);
	$this->view->c_eselon_ii =trim($_GET['eselonii']);
	$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	if ($_GET['eselonii']){$nesl2=$this->left($_GET['eselonii']); $nesl2=",".$nesl2;}
	
	$c_eselon_iii=$_GET['eseloniii'];
	$c_eselon_iii=substr($c_eselon_iii,0,2);
	$this->view->c_eselon_iii =trim($_GET['eseloniii']);
	$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	if ($_GET['eseloniii']){$nesl3=$this->left($_GET['eseloniii']); $nesl3=",".$nesl3;}
	
	$c_eselon_iv=$_GET['eseloniv'];
	$c_eselon_iv=substr($c_eselon_iv,0,2);
	$this->view->c_eselon_iv =trim($_GET['eseloniv']);
	$this->view->eselonvList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	if ($_GET['eseloniv']){$nesl4=$this->left($_GET['eseloniv']); $nesl4=",".$nesl4;}
	
	$jabatanlengkap=$_GET['n_jabatan'].", pada ".$nesl4.$nesl3.$nesl2.$nesl1;
	$this->view->jabat_lengkap=$jabatanlengkap;
	$this->view->c_eselon=$_GET['c_eselon'];
	$this->view->eselonList = $this->reff_serv->getEselon('');
	$this->view->a_alamat_kantor=$_GET['a_alamat_kantor'];
}
else{
	$this->view->c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);
	$this->view->lokasiList = $this->reff_serv->getLokasi('');	
	$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);
	$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	
	$c_eselon_i=$_GET['eseloni'];
	$c_eselon_i=substr($c_eselon_i,0,2);
	$this->view->c_eselon_i =trim($_GET['eseloni']);
	$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
	if ($_GET['eseloni']){$nesl1=$this->left($_GET['eseloni']); $nesl1=",".$nesl1;}
	
	$c_eselon_ii=$_GET['eselonii'];
	$c_eselon_ii=substr($c_eselon_ii,0,2);
	$this->view->c_eselon_ii =trim($_GET['eselonii']);
	$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	if ($_GET['eselonii']){$nesl2=$this->left($_GET['eselonii']); $nesl2=",".$nesl2;}
	
	$c_eselon_iii=$_GET['eseloniii'];
	$c_eselon_iii=substr($c_eselon_iii,0,2);
	$this->view->c_eselon_iii =trim($_GET['eseloniii']);
	$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	if ($_GET['eseloniii']){$nesl3=$this->left($_GET['eseloniii']); $nesl3=",".$nesl3;}
	

	$jabatanlengkap=$_GET['n_jabatan'].", pada ".$nesl4.$nesl3.$nesl2.$nesl1;
	$this->view->jabat_lengkap=$jabatanlengkap;
	$this->view->c_eselon=$_GET['c_eselon'];
	$this->view->eselonList = $this->reff_serv->getEselon('');
	$this->view->a_alamat_kantor=$_GET['a_alamat_kantor'];
	$this->render('listcombo2');

}	
	
}

public function maintaindataAction() {

 		if ($_POST['d_mulai_jabat'])
		{
			$d_mulai_jabatan1=substr($_POST['d_mulai_jabat'],0,2);
			$d_mulai_jabatan2=substr($_POST['d_mulai_jabat'],3,2);
			$d_mulai_jabatan3=substr($_POST['d_mulai_jabat'],6,4);
		}
		$d_mulai_jabatan=$d_mulai_jabatan3."-".$d_mulai_jabatan2."-".$d_mulai_jabatan1;
		if (!$_POST['d_mulai_jabat']){$d_mulai_jabatan=null;$cektglmulai=true;}
		else{$cektglmulai=checkdate($d_mulai_jabatan2,$d_mulai_jabatan1,$d_mulai_jabatan3);}

 		if ($_POST['d_akhir_jabat'])
		{
			$d_akhir_jabatan1=substr($_POST['d_akhir_jabat'],0,2);
			$d_akhir_jabatan2=substr($_POST['d_akhir_jabat'],3,2);
			$d_akhir_jabatan3=substr($_POST['d_akhir_jabat'],6,4);
		}
		$d_akhir_jabatan=$d_akhir_jabatan3."-".$d_akhir_jabatan2."-".$d_akhir_jabatan1;
		if (!$_POST['d_akhir_jabat']){$d_akhir_jabatan=null;$cektglakhir=true;}
		else{$cektglakhir=checkdate($d_akhir_jabatan2,$d_akhir_jabatan1,$d_akhir_jabatan3);}

 		if ($_POST['d_tmt_eselon'])
		{
			$d_tmt_eselon1=substr($_POST['d_tmt_eselon'],0,2);
			$d_tmt_eselon2=substr($_POST['d_tmt_eselon'],3,2);
			$d_tmt_eselon3=substr($_POST['d_tmt_eselon'],6,4);
		}
		$d_tmt_eselon=$d_tmt_eselon3."-".$d_tmt_eselon2."-".$d_tmt_eselon1;
		if (!$_POST['d_tmt_eselon']){$d_tmt_eselon=null;$cektglesl=true;}
		else{$cektglesl=checkdate($d_tmt_eselon2,$d_tmt_eselon1,$d_tmt_eselon3);}		

 		if ($_POST['d_sk_jabat'])
		{
			$d_sk_jabat1=substr($_POST['d_sk_jabat'],0,2);
			$d_sk_jabat2=substr($_POST['d_sk_jabat'],3,2);
			$d_sk_jabat3=substr($_POST['d_sk_jabat'],6,4);
		}
		$d_sk_jabat=$d_sk_jabat3."-".$d_sk_jabat2."-".$d_sk_jabat1;
		if (!$_POST['d_sk_jabat']){$d_sk_jabat=null;$cektglsk=true;}
		else{$cektglsk=checkdate($d_sk_jabat2,$d_sk_jabat1,$d_sk_jabat3);}	

 		if ($_POST['d_tmt_lantik'])
		{
			$d_tmt_lantik1=substr($_POST['d_tmt_lantik'],0,2);
			$d_tmt_lantik2=substr($_POST['d_tmt_lantik'],3,2);
			$d_tmt_lantik3=substr($_POST['d_tmt_lantik'],6,4);
		}
		$d_tmt_lantik=$d_tmt_lantik3."-".$d_tmt_lantik2."-".$d_tmt_lantik1;
		if (!$_POST['d_tmt_lantik']){$d_tmt_lantik=null;$cektgllantik=true;}
		else{$cektgllantik=checkdate($d_tmt_lantik2,$d_tmt_lantik1,$d_tmt_lantik3);}	
		
if (!$_POST['q_angka_kredit']){$q_angka_kredit=0;}else{$q_angka_kredit=$_POST['q_angka_kredit'];}

$c_eselon_i=$_POST['c_eselon_i'];
if ($_POST['c_eselon_i']!='#'){	$c_eselon_il=strlen($c_eselon_i); $c_eselon_i=$this->right($c_eselon_i,$c_eselon_il);}
else {$c_eselon_i='00';}

$c_eselon_ii=$_POST['c_eselon_ii'];
if ($_POST['c_eselon_ii']!='#'){$c_eselon_iil=strlen($c_eselon_ii); $c_eselon_ii=$this->right($c_eselon_ii, $c_eselon_iil);}
else {$c_eselon_ii='00';}

$c_eselon_iii=$_POST['c_eselon_iii'];
if ($_POST['c_eselon_iii']!='#'){$c_eselon_iiil=strlen($c_eselon_iii); $c_eselon_iii=$this->right($c_eselon_iii, $c_eselon_iiil);}
else {$c_eselon_iii='00';}

$c_eselon_iv=$_POST['c_eselon_iv'];
if ($_POST['c_eselon_iv']!='#'){$c_eselon_ivl=strlen($c_eselon_iv); $c_eselon_iv=$this->right($c_eselon_iv, $c_eselon_ivl);}
else {$c_eselon_iv='00';}

$c_eselon_v=$_POST['c_eselon_v'];
if ($_POST['c_eselon_v']!='#'){$c_eselon_vl=strlen($c_eselon_v); $c_eselon_v=$this->right($c_eselon_v, $c_eselon_vl);}
else {$c_eselon_v='00';}


if (($cektglmulai==true &&  $cektglakhir==true && $cektglesl==true && $cektglsk==true && $cektgllantik==true) )
{
	$c_lokasi_unitkerja=$_POST['c_lokasi_unitkerja'];
	$c_eselon_i=trim($c_eselon_i);
	$c_eselon_ii=trim($c_eselon_ii);
	$c_eselon_iii=trim($c_eselon_iii);
	$c_eselon_iv=trim($c_eselon_iv);
	$c_eselon_v=trim($c_eselon_v);
	
	$carisatker= " and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_eselon_v='$c_eselon_v' ";
	//$c_satker=$this->reff_serv->getCsatker($carisatker);
	$getTrunit=$this->reff_serv->getUnitKerja($carisatker);
	$c_bidang=$getTrunit[0]['c_bidang'];
	$c_type=$getTrunit[0]['c_type'];
	$c_parent=$getTrunit[0]['c_parent'];
	$c_child=$getTrunit[0]['c_child'];
	$c_satker=$getTrunit[0]['c_satker'];
	
	$theFileSize = $_FILES['e_file_sk']['size'];
	if (!$theFileSize)
	{$destDir = $_POST['a_file'];}
	else{
		if ($_POST['a_file'])
		{
			$dsk=$d_sk_jabat3.$d_sk_jabat2.$d_sk_jabat1;	
			$namefile=trim($_POST['i_peg_nip'])."_".trim($_POST['c_jabatan'])."_".$dsk;
			$FileName_dat = $namefile;
			$fileName = $_FILES['e_file_sk']['name'];	
			$extention = strtolower(substr($fileName, strrpos($fileName, '.') + 1));				
			$FileName_pdf = $FileName_dat.'.'.$extention;
			$destDir = "$FileName_pdf";	
		}	
	}	

	$MaintainData = array("i_peg_nip"=>$_POST['i_peg_nip'],
						"c_eselon"=>$_POST['c_eselon'],
						"d_tmt_eselon"=>$d_tmt_eselon,
						"c_jabatan"=>$_POST['c_jabatan'],
						"n_jabatan_nokode"=>$_POST['n_jabatan_nokode'],
						"d_mulai_jabat"=>$d_mulai_jabatan,
						"d_akhir_jabat"=>$d_akhir_jabatan,
						"q_angka_kredit"=>$q_angka_kredit,
						"c_lokasi_unitkerja"=>trim($_POST['c_lokasi_unitkerja']),
						"c_eselon_i"=>trim($c_eselon_i),
						"c_eselon_ii"=>trim($c_eselon_ii),
						"c_eselon_iii"=>trim($c_eselon_iii),
						"c_eselon_iv"=>trim($c_eselon_iv),
						"c_eselon_v"=>trim($c_eselon_v),
						"a_alamat_kantor"=>$_POST['a_alamat_kantor'],
						"i_sk_jabat"=>$_POST['i_sk_jabat'],
						"d_sk_jabat"=>$d_sk_jabat,
						"n_sk_pejabat"=>$_POST['n_sk_pejabat'],
						"d_tmt_lantik"=>$d_tmt_lantik,
						"n_lok_kppn"=>$_POST['n_lok_kppn'],
						"n_lok_taspen"=>$_POST['n_lok_taspen'],
						"e_keterangan"=>$_POST['e_keterangan'],	
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
		else{$hasil = $this->jabatan_serv->maintainData($MaintainData,'insert');}
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
	}
	else if ($_POST['proses']=='Hapus')
	{
		$hasil = $this->jabatan_serv->maintainData($MaintainData,'delete');		
		$this->view->par="Hapus";
		$this->view->jdl="Menghapus ";
		$par="Menghapus";
	}	
	else
	{
		if ($stop=="ok"){$hasil="Gagal besar file tidak diijinkan";}
		else{$hasil = $this->jabatan_serv->maintainData($MaintainData,'update');}
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";		
	}
	
/// simpan file
 		if ($hasil=="sukses"){
			$dsk=$d_sk_jabat3.$d_sk_jabat2.$d_sk_jabat1;	
			$namefile=trim($_POST['i_peg_nip'])."_".trim($_POST['c_jabatan'])."_".$dsk;
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
						$destDir = "../library/data/sdm/struktural/$FileName_pdf";
						if (move_uploaded_file($_FILES['e_file_sk']['tmp_name'], $destDir)) { 
							$lampiran ="file";
						}
				}
			} 
//=================================================================================================	
	
	if ($hasil=='sukses')
	{
		$c_jabatan=trim($_POST['c_jabatan']);
		$usiapens= $this->reff_serv->getJabatan(" and c_jabatan='$c_jabatan' ");
		$usiapensiun= $usiapens[0]['q_usia_pens'];

		$this->updateTmPegawai($_POST['i_peg_nip'],$usiapensiun,$c_satker,$c_bidang,$c_type,$c_parent,$c_child);
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";				
	}
	$this->view->eselonList = $this->reff_serv->getEselon('');		
	$this->view->lokasiList = $this->reff_serv->getLokasi('');	
	$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='1'");
	$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='2'");
	$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='3'");
	$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='4'");
	$this->view->eselonvList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='5'");	

	//$this->listDataByKey($_POST['i_peg_nip'],trim($_POST['c_eselon']),trim($_POST['c_jabatan']),$_POST['d_mulai_jabat']);
}
else
{
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
	$nip=$_POST['i_peg_nip'];	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->jabatanList = $this->jabatan_serv->getJabatanList($cari);	
	
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	if ($hasil=='sukses'){$this->render('listjabatan');}else{$this->render('jabatan');}
	// if ($_POST['proses']=='Hapus'){$this->render('listjabatan');}
	// else{$this->render('jabatan');}
}

public function hapusdataAction() {
	$MaintainData = array("i_peg_nip"=>($this->view->nip),"n_jabatan"=>$_GET['n_jabatan']);		
	$hasil = $this->jabatan_serv->maintainData($MaintainData,'delete');	
	$pesan= "Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->listjabatanAction();
	$this->render('listjabatan');	
}


public function listDataByKey($nip,$c_eselon,$c_jabatan,$d_mulai_jabat) {  
	$carilist = " and i_peg_nip='$nip' and c_eselon='$c_eselon' and c_jabatan='$c_jabatan' and to_char(d_mulai_jabat,'dd-mm-yyyy')='$d_mulai_jabat'";
	$datajabatan=$this->jabatan_serv->getjabatanList($carilist);	
	$this->view->i_peg_nip=$datajabatan[0]['i_peg_nip'];
	$this->view->c_eselon=$datajabatan[0]['c_eselon'];	
	$this->view->d_tmt_eselon=$datajabatan[0]['d_tmt_eselon'];
	$this->view->c_jabatan=$datajabatan[0]['c_jabatan'];
	$this->view->n_jabatan=$datajabatan[0]['n_jabatan'];
	$this->view->n_jabatan_nokode=$datajabatan[0]['n_jabatan_nokode'];
	$this->view->d_mulai_jabat=$datajabatan[0]['d_mulai_jabat'];
	$this->view->d_akhir_jabat=$datajabatan[0]['d_akhir_jabat'];
	$this->view->q_angka_kredit=$datajabatan[0]['q_angka_kredit'];
	$this->view->c_lokasi_unitkerja=trim($datajabatan[0]['c_lokasi_unitkerja']);
	$this->view->c_eselon_i=$datajabatan[0]['c_eselon_i']." ".$datajabatan[0]['n_eselon_i'];
	$this->view->c_eselon_ii=$datajabatan[0]['c_eselon_ii']." ".$datajabatan[0]['n_eselon_ii'];
	$this->view->c_eselon_iii=$datajabatan[0]['c_eselon_iii']." ".$datajabatan[0]['n_eselon_iii'];
	$this->view->c_eselon_iv=$datajabatan[0]['c_eselon_iv']." ".$datajabatan[0]['n_eselon_iv'];
	$this->view->c_eselon_v=$datajabatan[0]['c_eselon_v']." ".$datajabatan[0]['n_eselon_v'];

	$neselon1=trim($datajabatan[0]['n_eselon_i']);
	$neselon2=trim($datajabatan[0]['n_eselon_ii']);
	$neselon3=trim($datajabatan[0]['n_eselon_iii']);
	$neselon4=trim($datajabatan[0]['n_eselon_iv']);
	$neselon5=trim($datajabatan[0]['n_eselon_v']);
	
	$this->view->n_eselon_i=trim($datajabatan[0]['n_eselon_i']);
	$this->view->n_eselon_ii=trim($datajabatan[0]['n_eselon_ii']);
	$this->view->n_eselon_iii=trim($datajabatan[0]['n_eselon_iii']);
	$this->view->n_eselon_iv=trim($datajabatan[0]['n_eselon_iv']);
	$this->view->n_eselon_v=trim($datajabatan[0]['n_eselon_v']);
	
	$this->view->a_alamat_kantor=$datajabatan[0]['a_alamat_kantor'];
	$this->view->i_sk_jabat=$datajabatan[0]['i_sk_jabat'];
	$this->view->d_sk_jabat=$datajabatan[0]['d_sk_jabat'];
	$this->view->n_sk_pejabat=$datajabatan[0]['n_sk_pejabat'];
	$this->view->d_tmt_lantik=$datajabatan[0]['d_tmt_lantik'];
	$this->view->n_lok_kppn=$datajabatan[0]['n_lok_kppn'];
	$this->view->n_lok_taspen=$datajabatan[0]['n_lok_taspen'];
	$this->view->e_file_sk=$datajabatan[0]['e_file_sk'];	
	$this->view->e_keterangan=$datajabatan[0]['e_keterangan'];

	$c_lokasi_unitkerja=trim($datajabatan[0]['c_lokasi_unitkerja']);
	$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
	$c_eselon_i=trim($datajabatan[0]['c_eselon_i']);
	$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	

	$c_eselon_ii=trim($datajabatan[0]['c_eselon_ii']);
	$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
	
	$c_eselon_iii=trim($datajabatan[0]['c_eselon_iii']);
	$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

	$c_eselon_iv=trim($datajabatan[0]['c_eselon_iv']);
	$this->view->eselonvList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
	
	$this->view->ceselonpns=substr($datajabatan[0]['c_eselon'],1,1);
	
	if ($neselon1){$nesl1=",$neselon1";}
	if ($neselon2){$nesl2=",$neselon2";}
	if ($neselon3){$nesl3=",$neselon3";}
	if ($neselon4){$nesl4=",$neselon4";}
	if ($neselon5){$nesl5=",$neselon5";}
	
	$jabatanlengkap=$nesl5.$nesl4.$nesl3.$nesl2.$nesl1;
	$jabatanlengkap=$datajabatan[0]['n_jabatan'].", pada ".$nesl5.$nesl4.$nesl3.$nesl2.$nesl1;
	$this->view->jabat_lengkap=$jabatanlengkap;	
	
}	

public function updateTmPegawai($nip,$usiapensiun,$c_satker,$c_bidang,$c_type,$c_parent,$c_child) {  
	$carilist = " and i_peg_nip='$nip' and d_mulai_jabat in (select max(d_mulai_jabat) from sdm.tm_jabatan where i_peg_nip='$nip') ";
	$datajabatan=$this->jabatan_serv->getjabatanList($carilist);

	$countdata=count($datajabatan);	
 		if ($datajabatan[0]['d_mulai_jabat'])
		{
			$d_mulai_jabatan1=substr($datajabatan[0]['d_mulai_jabat'],0,2);
			$d_mulai_jabatan2=substr($datajabatan[0]['d_mulai_jabat'],3,2);
			$d_mulai_jabatan3=substr($datajabatan[0]['d_mulai_jabat'],6,4);
		}
		$d_mulai_jabatan=$d_mulai_jabatan3."-".$d_mulai_jabatan2."-".$d_mulai_jabatan1;
		if (!$datajabatan[0]['d_mulai_jabat']){$d_mulai_jabatan=null;$cektglmulai=true;}
		else{$cektglmulai=checkdate($d_mulai_jabatan2,$d_mulai_jabatan1,$d_mulai_jabatan3);}

 		if ($datajabatan[0]['d_akhir_jabat'])
		{
			$d_akhir_jabatan1=substr($datajabatan[0]['d_akhir_jabat'],0,2);
			$d_akhir_jabatan2=substr($datajabatan[0]['d_akhir_jabat'],3,2);
			$d_akhir_jabatan3=substr($datajabatan[0]['d_akhir_jabat'],6,4);
		}
		$d_akhir_jabatan=$d_akhir_jabatan3."-".$d_akhir_jabatan2."-".$d_akhir_jabatan1;
		if (!$datajabatan[0]['d_akhir_jabat']){$d_akhir_jabatan=null;$cektglakhir=true;}
		else{$cektglakhir=checkdate($d_akhir_jabatan2,$d_akhir_jabatan1,$d_akhir_jabatan3);}

 		if ($datajabatan[0]['d_tmt_eselon'])
		{
			$d_tmt_eselon1=substr($datajabatan[0]['d_tmt_eselon'],0,2);
			$d_tmt_eselon2=substr($datajabatan[0]['d_tmt_eselon'],3,2);
			$d_tmt_eselon3=substr($datajabatan[0]['d_tmt_eselon'],6,4);
		}
		$d_tmt_eselon=$d_tmt_eselon3."-".$d_tmt_eselon2."-".$d_tmt_eselon1;
		if (!$datajabatan[0]['d_tmt_eselon']){$d_tmt_eselon=null;$cektglesl=true;}
		else{$cektglesl=checkdate($d_tmt_eselon2,$d_tmt_eselon1,$d_tmt_eselon3);}

 		if ($datajabatan[0]['d_tmt_lantik'])
		{
			$d_tmt_lantik1=substr($datajabatan[0]['d_tmt_lantik'],0,2);
			$d_tmt_lantik2=substr($datajabatan[0]['d_tmt_lantik'],3,2);
			$d_tmt_lantik3=substr($datajabatan[0]['d_tmt_lantik'],6,4);
		}
		$d_tmt_lantik=$d_tmt_lantik3."-".$d_tmt_lantik2."-".$d_tmt_lantik1;
		if (!$datajabatan[0]['d_tmt_lantik']){$d_tmt_lantik=null;$cektglesl=true;}
		else{$ceklantik=checkdate($d_tmt_lantik2,$d_tmt_lantik1,$d_tmt_lantik3);}
		
if ($countdata==0){	
		$MaintainData = array("i_peg_nip"=>$nip,
						"c_eselon"=>null,
						"d_tmt_eselon"=>null,
						"c_jabatan"=>null,
						"d_mulai_jabat"=>null,
						"d_akhir_jabat"=>null,
						"c_lokasi_unitkerja"=>null,
						"c_eselon_i"=>'00',
						"c_eselon_ii"=>'00',
						"c_eselon_iii"=>'00',
						"c_eselon_iv"=>'00',
						"c_eselon_v"=>'00',
						"c_bidang"=>'00',
						"c_parent"=>'00',
						"c_child"=>'00',
						"q_usia_pensiun"=>null);
	}
else{
		$MaintainData = array("i_peg_nip"=>$datajabatan[0]['i_peg_nip'],
						"c_eselon"=>$datajabatan[0]['c_eselon'],
						"d_tmt_eselon"=>$d_tmt_eselon,
						"c_jabatan"=>$datajabatan[0]['c_jabatan'],
						"d_mulai_jabat"=>$d_mulai_jabatan,
						"d_akhir_jabat"=>$d_akhir_jabatan,
						"c_lokasi_unitkerja"=>$datajabatan[0]['c_lokasi_unitkerja'],
						"c_eselon_i"=>$datajabatan[0]['c_eselon_i'],
						"c_eselon_ii"=>$datajabatan[0]['c_eselon_ii'],
						"c_eselon_iii"=>$datajabatan[0]['c_eselon_iii'],
						"c_eselon_iv"=>$datajabatan[0]['c_eselon_iv'],
						"c_eselon_v"=>$datajabatan[0]['c_eselon_v'],
						"c_satker"=>$c_satker,
						"c_bidang"=>$c_bidang,
						"c_type"=>$c_type,
						"c_parent"=>$c_parent,
						"c_child"=>$c_child,
						"d_tmt_lantik"=>$d_tmt_lantik,
						"q_usia_pensiun"=>$usiapensiun);
}	

		$hasil = $this->jabatan_serv->updateTmPegawai($MaintainData);	

}	
function right($string){
    return substr($string,0,2);
}
function left($string){
    return substr($string,3,200);
}	
}
?>