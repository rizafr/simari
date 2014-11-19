<?php
require_once 'Zend/Controller/Action.php';
require_once "service/sdm/excel_reader2.php";
require_once "service/sdm/Sdm_Importdata_Service.php";

class Admmodule_UploadexcelController extends Zend_Controller_Action
{
		
public function init() {
		$this->_helper->layout->setLayout('target-column');
	 	$registry = Zend_Registry::getInstance();
	   	$this->view->basePath = $registry->get('basepath');	
		$this->view->leftMenu = $registry->get('leftMenu'); 
		$this->view->dPath = $registry->get('photoPath'); 
		
		$this->pegawai_serv = Sdm_Importdata_Service::getInstance();
		$ssologin = new Zend_Session_Namespace('ssologin');
		$this->view->c_lokasi_unitkerja=$ssologin->c_lokasi_unitkerja;
		$this->view->c_eselon_i=$ssologin->c_eselon_i;	
    }
public function indexAction()
{
}
public function uploadexceljsAction() 
{
	header('content-type : text/javascript');
	$this->render('uploadexceljs');
}
public function uploadexcelAction() 
{

}	
	
public function prosesuploadexcelAction() 
{
	$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
	$baris = $data->rowcount($sheet_index=0);
	$hitBrs=0;
	$datagagal='';
//	echo "baris".$baris;

    $lokasi_unitkerja=$data->val(1,2);
	$c_lokasi_unitkerja = substr($lokasi_unitkerja,0,1);
	$unit_kerja=$data->val(1,3);
	$c_eselon_i = substr($unit_kerja,0,2);
	$c_eselon_ii = substr($unit_kerja,5,3);
	$c_eselon_iii = substr($unit_kerja,11,2);
	$c_eselon_iv = substr($unit_kerja,16,2);
	$c_eselon_v = substr($unit_kerja,21,2);
	
	for ($i=5; $i<=$baris; $i++)
	{
		$i_peg_nip = trim($data->val($i, 2));
		$i_peg_nip_new = trim($data->val($i, 3));
		$n_peg = $data->val($i, 4);
		$c_status_kepegawaian= $data->val($i, 5);
		if ($c_status_kepegawaian){$c_status_kepegawaian =$this->getData($c_status_kepegawaian);}
		$c_pend = $data->val($i, 6);
		if ($c_pend){$c_pend =$this->getData($c_pend);}
		$d_pend_akhir = $data->val($i, 7);
		$a_peg_kota_lahir = $data->val($i, 8);
		if ($a_peg_kota_lahir){$a_peg_kota_lahir =$this->getData($a_peg_kota_lahir);}
		$d_peg_lahir= $data->val($i, 9);
		$c_peg_jeniskelamin= trim($data->val($i, 10));
		if ($c_peg_jeniskelamin=='Laki-Laki'){$c_peg_jeniskelamin ='L';}
		if ($c_peg_jeniskelamin=='Perempuan'){$c_peg_jeniskelamin ='P';}
		$c_agama= $data->val($i, 11);
		if ($c_agama){$c_agama =$this->getData($c_agama);}
		$c_peg_statusnikah= $data->val($i, 12);
		if ($c_peg_statusnikah=="Kawin"){$c_peg_statusnikah="K";}
		if ($c_peg_statusnikah=="Belum Kawin"){$c_peg_statusnikah="B";}
		if ($c_peg_statusnikah=="Janda"){$c_peg_statusnikah="J";}
		if ($c_peg_statusnikah=="Duda"){$c_peg_statusnikah="D";}
		$d_tmt_cpns= $data->val($i, 13);
		$c_jenis_naik= $data->val($i, 14);
		if ($c_jenis_naik){$c_jenis_naik =$this->getData($c_jenis_naik);}
		$c_golongan= $data->val($i, 15);
		if ($c_golongan){$c_golongan =$this->getData($c_golongan);}
		$d_tmt_golongan= $data->val($i, 16);
		//yg kosong
	//	$d_sk_golongan= $data->val($i, 17);
		//yg kosong
	//	$i_sk_golongan= trim($data->val($i, 18));
		$c_eselon= $data->val($i, 17);
		if ($c_eselon){$c_eselon =$this->getData($c_eselon);}
		$c_jabatan= $data->val($i, 18);
		if ($c_jabatan){$c_jabatan =$this->getData($c_jabatan);}
		//kosong
		$d_mulai_jabat= $data->val($i, 19);
		//kosong
		//kosong


 		$cari = " and (i_peg_nip ='$i_peg_nip' or i_peg_nip_new='$i_peg_nip_new')";
		$checkdata=$this->pegawai_serv->getPegawaiListByNip($cari);
		$checkdata=$checkdata[0]['i_peg_nip'];		
		
		if (!$checkdata)
		{ 
/* 	echo "$i_peg_nip
$i_peg_nip_new
$n_peg
$c_status_kepegawaian
$c_pend
$d_pend_akhir
$a_peg_kota_lahir
$d_peg_lahir
$c_peg_jeniskelamin
$c_agama
$c_peg_statusnikah
$d_tmt_cpns
$c_jenis_naik
$c_golongan
$d_tmt_golongan
$c_eselon
$c_jabatan
$d_mulai_jabat";   */
			
			 $MaintainData = array("i_peg_nip"=>$i_peg_nip,
					"i_peg_nip_new"=>$i_peg_nip_new,
					"n_peg"=>$n_peg,
					"c_status_kepegawaian"=>$c_status_kepegawaian,
					"c_pend"=>$c_pend,
					"d_pend_akhir"=>$d_pend_akhir,
					"a_peg_kota_lahir"=>$a_peg_kota_lahir,
					"d_peg_lahir"=>$d_peg_lahir,
					"c_peg_jeniskelamin"=>$c_peg_jeniskelamin,
					"c_agama"=>$c_agama,
					"c_peg_statusnikah"=>$c_peg_statusnikah,
					"d_tmt_cpns"=>$d_tmt_cpns,
					"c_jenis_naik"=>$c_jenis_naik,
					"c_golongan"=>$c_golongan,
					"d_tmt_golongan"=>$d_tmt_golongan,
					"c_eselon"=>$c_eselon,
					"c_jabatan"=>$c_jabatan,
					"d_mulai_jabat"=>$d_mulai_jabat,
					"c_stat_aktivasi"=>"A",
					"i_entry"=>"import",
					"d_entry"=>date("Y-m-d"),
					"c_lokasi_unitkerja"=>$c_lokasi_unitkerja,
					"c_eselon_i"=>$c_eselon_i,
					"c_eselon_ii"=>$c_eselon_ii,
					"c_eselon_iii"=>$c_eselon_iii,
					"c_eselon_iv"=>$c_eselon_iv,
					"c_eselon_v"=>$c_eselon_v); 
			if($i_peg_nip){
		 	$hasil = $this->pegawai_serv->maintainData($MaintainData); 	 	
			$hasil="sukses";
			}
			else{$hasil='gagal';}
		}
		else{
			$datagagal=$datagagal.$i_peg_nip;
		}	
			if (!$i_peg_nip){
				$hitBrs++;
				if ($hitBrs==5){$i=$baris;}
				$hasil='gagal';
				$gagalx++;
			}
		  
		  if ($hasil=='sukses') $sukses++;
		  else $gagal++;
	}
	
	$gagal=$gagal*1-$gagalx*1;
	echo "<table> <tr>";
	echo "<td><h2>Proses import data selesai.</h2></td>";
	echo "<tr><td>Jumlah data yang sukses diimport<br></td> <td>:</td><td>".$sukses."</td><tr>";
	echo "<tr><td>Jumlah data yang gagal diimport</td> <td>:</td><td>".$gagal."</td><tr></table>";
	
	$this->render('uploadexcel');
}

public function downloadexcelAction()
{
	$this->_helper->viewRenderer->setNoRender(true);
	$dfolder = $this->view->dPath."/sdm/templateexcel/template.xls";
	$ndokumen = file_get_contents($dfolder);
	header("Content-Type: application/xls");
	header("Content-Disposition: attachment; filename=template_sdm.xls");
	echo $ndokumen;
}

public function downloadpdfAction()
{
	$this->_helper->viewRenderer->setNoRender(true);
	$dfolder = $this->view->dPath."/sdm/templateexcel/manual.pdf";
	$ndokumen = file_get_contents($dfolder);
	header("Content-Type: application/pdf");
	header("Content-Disposition: attachment; filename=user_manual.pdf");
	echo $ndokumen;
}
	
function getData($string){
	$expesl1 = explode("~",$string);
	$data=$expesl1[0];
	
	return $data;
}
function checkPanjang($string){

}
	
}

?>