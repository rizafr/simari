<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_JabatanFungsional_Service.php";
require_once "service/sdm/Sdm_Jabatan_Service.php";


class Sdmmodule_DatajabatanfungsionalController extends Zend_Controller_Action {
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu'); 		
		$this->reff_serv = sdm_refferensi_Service::getInstance();
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->jabatan_serv = Sdm_JabatanFungsional_Service::getInstance();
		$this->jabatanstr_serv = Sdm_Jabatan_Service::getInstance();
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
	if ($par=='insert'){
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
	}
	else if ($par=='delete'){
		$this->view->par="Hapus";
		$this->view->jdl="Manghapus ";	
		$par=$_GET['par'];
		$this->view->id  = $_GET['id'];	
		$id=$_GET['id'];
	
		
		$this->listDataByKey($id);
	}
	else{
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";	
		$par=$_GET['par'];
		$this->view->id  = $_GET['id'];	
		$id=$_GET['id'];
	
		$this->listDataByKey($id);
		if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Merubah ";}		
		else{$this->view->jdl="Melihat ";$this->render('jabatanview');}				
	}
		

}

public function listnamajabatanAction() {
	$nip=$this->view->nip;
	$cari=" and i_peg_nip='$nip' and c_eselon in('13','14') ";
	$this->view->nmJabatanList = $this->jabatanstr_serv->getJabatanList($cari);
}

public function listcomboAction() {
	$c_kelompok=$_GET['c_kelompok'];
	$cari= " and c_kelompok='$c_kelompok' ";
	$this->view->statusJabatList = $this->reff_serv->getTrStatfungsional($cari);
	
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
		if (!$_POST['d_tmt_lantik']){$d_tmt_lantik=null;}
		
		
if (!$_POST['q_angka_kredit']){$q_angka_kredit=0;}else{$q_angka_kredit=$_POST['q_angka_kredit'];}



if (($cektglmulai==true &&  $cektglakhir==true && $cektglsk==true) )
{
	$c_jabatan=trim($_POST['c_jabatan']);
	$carijab= " and c_jabatan='$c_jabatan'";
	$datajab = $this->jabatan_serv->getJabatanTr($carijab);
	$c_eselon=$datajab[0]['c_eselon'];
	$c_tkfgs=$datajab[0]['c_tkfgs'];
	$c_kelfgs=$datajab[0]['c_kelfgs'];
	$q_tktfgs=$datajab[0]['q_tktfgs']*1;

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
	$MaintainData = array("id"=>$_POST['id'],
				"i_peg_nip"=>$_POST['i_peg_nip'],
				"c_statusjabatan"=>$_POST['c_statusjabatan'],
				"c_statusjabatan2"=>$_POST['c_statusjabatan2'],
				"c_alasan"=>$_POST['c_alasan'],
				"c_alasan2"=>$_POST['c_alasan2'],
				"c_eselon"=>$c_eselon,
				"c_jabatan"=>$_POST['c_jabatan'],
				"d_mulai_jabat"=>$d_mulai_jabatan,
				"d_mulai_jabat2"=>$_POST['d_mulai_jabat2'],
				"d_akhir_jabat"=>$d_akhir_jabatan,
				"q_angka_kredit"=>$q_angka_kredit,
				"i_sk_jabat"=>$_POST['i_sk_jabat'],
				"d_sk_jabat"=>$d_sk_jabat,
				"n_sk_pejabat"=>$_POST['n_sk_pejabat'],
				"n_lembaga"=>$_POST['n_lembaga'],
				"c_tkfgs"=>$c_tkfgs, 
				"c_kelfgs"=>$c_kelfgs,
				"q_tktfgs"=>$q_tktfgs*1,
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
						$destDir = "../library/data/sdm/fungsional/$FileName_pdf";
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
		
		$this->updateTmPegawai($_POST['i_peg_nip'],$c_jabatan,$c_kelfgs,$q_tktfgs,$d_tmt_lantik);
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";				
	}
	

	//$this->listDataByKey($_POST['i_peg_nip'],trim($_POST['c_eselon']),trim($_POST['c_jabatan']),$_POST['d_mulai_jabat']);
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

	$MaintainData = array("id"=>$_GET['id']);		
	$hasil = $this->jabatan_serv->maintainData($MaintainData,'delete');

	if ($hasil=='sukses')
	{
		
		// sementara dtutup dulu noer
		//$this->updateTmPegawai($this->view->nip,$c_jabatan,$c_kelfgs,$q_tktfgs,$d_tmt_lantik);
	}	
	$pesan= "Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->listjabatanAction();
	$this->render('listjabatan');	
}


public function listDataByKey($id) {  
	$carilist = " and id='$id'";
	$datajabatan=$this->jabatan_serv->getjabatanList($carilist);	
	$this->view->i_peg_nip=$datajabatan[0]['i_peg_nip'];
	$this->view->c_jabatan=$datajabatan[0]['c_jabatan'];
	$this->view->n_jabatan=$datajabatan[0]['n_jabatan'];
	$this->view->c_statusjabatan=$datajabatan[0]['c_statusjabatan'];
	$this->view->c_alasan=$datajabatan[0]['c_alasan'];
	$this->view->d_mulai_jabat=$datajabatan[0]['d_mulai_jabat'];
	$this->view->d_akhir_jabat=$datajabatan[0]['d_akhir_jabat'];
	$this->view->q_angka_kredit=$datajabatan[0]['q_angka_kredit'];
	$this->view->i_sk_jabat=$datajabatan[0]['i_sk_jabat'];
	$this->view->d_sk_jabat=$datajabatan[0]['d_sk_jabat'];
	$this->view->n_sk_pejabat=$datajabatan[0]['n_sk_pejabat'];	
	$this->view->n_lembaga=$datajabatan[0]['n_lembaga'];
	$this->view->e_file_sk=$datajabatan[0]['e_file_sk'];
	
	$cari= " and c_kelompok='$c_statusjabatan' ";
	$this->view->statusJabatList = $this->reff_serv->getTrStatfungsional($cari);	
	
}	

public function updateTmPegawai($nip,$c_jabatan,$c_kelfgs,$q_tktfgs,$d_tmt_lantik) {  
	$carilist = " and i_peg_nip='$nip' and d_mulai_jabat in (select max(d_mulai_jabat) from sdm.tm_jabatan_fungsional where i_peg_nip='$nip') ";
	$datajabatan=$this->jabatan_serv->getjabatanList($carilist);

	$countdata=count($datajabatan);	
 
  
if ($countdata==0){	
		$MaintainData = array("i_peg_nip"=>$nip,
					"c_kelfgs"=>null,
					"q_tktfgs"=>null,
					"c_statusfjabatan"=>null,
					"c_fjabatan_alasan"=>null);
	}
else{
		$MaintainData = array("i_peg_nip"=>$datajabatan[0]['i_peg_nip'],
						"c_kelfgs"=>$datajabatan[0]['c_kelfgs'],
						"q_tktfgs"=>$datajabatan[0]['q_tktfgs'],
						"c_statusfjabatan"=>$datajabatan[0]['c_statusjabatan'],
						"c_fjabatan_alasan"=>$datajabatan[0]['c_alasan']);
}	
		$hasil = $this->jabatan_serv->updateTmPegawai($MaintainData);
		
	if ($hasil=='sukses')
	{
		
		$this->updateTmJabatan($nip,$c_jabatan,$c_kelfgs,$q_tktfgs,$d_tmt_lantik);
	}
		

}


public function updateTmJabatan($nip,$c_jabatan,$c_kelfgs,$q_tktfgs,$d_tmt_lantik) { 
  
		$MaintainData = array("i_peg_nip"=>$nip,
						"c_kelfgs"=>$c_kelfgs,
						"q_tktfgs"=>$q_tktfgs*1,
						"c_jabatan"=>$c_jabatan,
						"d_tmt_lantik"=>$d_tmt_lantik);
		//noer aneh
		//$hasil = $this->jabatan_serv->updateTmJabatan($MaintainData);	

}	
	
}
?>