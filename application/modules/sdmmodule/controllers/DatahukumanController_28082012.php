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
		$this->view->c_izin=$ssologin->c_izin[0]['c_izin'];	
		
		
    }
	
    public function indexAction() {
	   
    }
public function hukumanjsAction() 
{
	header('content-type : text/javascript');
	$this->render('hukumanjs');
}	
	
public function listhukumanAction() {
	$nip=$this->view->nip;	
	$cari = " and i_peg_nip ='$nip' ";
	$this->view->hukumanList = $this->hukuman_serv->getHukumanList($cari);	
}
public function hukumanAction() {

	$par=$_GET['par'];	
	if ($par=='insert'){
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
	}
	else{
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";	
		$par=$_GET['par'];
		$this->listDataByKey($this->view->nip,$_GET['tingkat'],$_GET['jenis'],$_GET['dmulai']);
		$q_tingkat_hukuman=$_GET['tingkat'];
		$cari ="and q_tingkat_hukuman=$q_tingkat_hukuman";
		$this->view->jnsHukuman = $this->reff_serv->getTrHukuman($cari);
		
		if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Merubah ";}		
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
 		if ($_POST['d_mulai_sanksi'] != '')
		{
			$d_mulai_sanksi1=substr($_POST['d_mulai_sanksi'],0,2);
			$d_mulai_sanksi2=substr($_POST['d_mulai_sanksi'],3,2);
			$d_mulai_sanksi3=substr($_POST['d_mulai_sanksi'],6,4);
			$d_mulai_sanksi=$d_mulai_sanksi3."-".$d_mulai_sanksi2."-".$d_mulai_sanksi1;
		
		} else {
			$d_mulai_sanksi= date('Y-m-d');
		}
		if (!$_POST['d_mulai_sanksi']){$d_mulai_sanksi=null; $cektglmulai=true;}
		else{$cektglmulai=checkdate($d_mulai_sanksi2,$d_mulai_sanksi1,$d_mulai_sanksi3);}

 		if ($_POST['d_akhir_sanksi'])
		{
			$d_akhir_sanksi1=substr($_POST['d_akhir_sanksi'],0,2);
			$d_akhir_sanksi2=substr($_POST['d_akhir_sanksi'],3,2);
			$d_akhir_sanksi3=substr($_POST['d_akhir_sanksi'],6,4);
			$d_akhir_sanksi=$d_akhir_sanksi3."-".$d_akhir_sanksi2."-".$d_akhir_sanksi1;
		
		} else {
			$d_akhir_sanksi=null;
		}
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
	if (!$theFileSize)
	{$destDir = $_POST['a_file'];}
	else{
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
		
	$MaintainData = array("i_peg_nip"=>$_POST['i_peg_nip'],
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
	$this->listDataByKey($this->view->nip,$_POST['c_tingkat_sanksi'],$_POST['c_jenis_sanksi'],$_POST['d_mulai_sanksi']);
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
	$MaintainData = array("i_peg_nip"=>($this->view->nip),"c_tingkat_sanksi"=>$_GET['tingkat'],"c_jns_pelanggaran"=>$_GET['jenis'],"d_mulai_sanksi"=>$_GET['dmulai']);		
	$hasil = $this->hukuman_serv->maintainData($MaintainData,'delete');	
	$pesan= "Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->listhukumanAction();
	$this->render('listhukuman');	
}
	
public function listDataByKey($nip,$tingkat,$jenis,$tmtmulai) {  
	$carilist = " and i_peg_nip='$nip' and c_tingkat_sanksi='$tingkat' and c_jenis_sanksi='$jenis' and to_char(d_mulai_sanksi,'dd-mm-yyyy')='$tmtmulai' ";
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
	
}
?>