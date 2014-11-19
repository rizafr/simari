<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_CpnsUsulan_Service.php";

class Sdmmodule_UsulanterimacpnsController extends Zend_Controller_Action {
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu'); 		
		$this->reff_serv = sdm_refferensi_Service::getInstance();
		$this->cpnsusul_serv = Sdm_CpnsUsulan_Service::getInstance();
	
    }
	
public function indexAction() {
	   
    }
public function usulanjsAction() 
{
	header('content-type : text/javascript');
	$this->render('usulanjs');
}	
public function listusulanAction() {
	$cCol=$_GET['cCol'];
	$nCol=strtoupper($_GET['nCol']);
	if($cCol && $nCol){ 
		if ($cCol=='n_surat' || $cCol=='n_perihal'){ $cari=" and upper($cCol) like '%$nCol%' ";}
		else{$cari=" and to_char(d_surat,'yyyy')='$nCol' " ;}
		$this->view->cCol=$_GET['cCol'];
		$this->view->nCol=$_GET['nCol'];
	}
	$this->view->usulanList = $this->cpnsusul_serv->getUsulan($cari);
	
}	
public function usulanAction() {
	$this->view->par=$_GET['par'];
	 if ($_GET['par']=='Ubah'){		
		$id=$_GET['id'];
		$this->listData($id);	
	 }
	//$this->view->listJabatCpns = $this->cpnsusul_serv->getJabatCpns($cari); 
	//$this->view->listKualifikasiPend = $this->cpnsusul_serv->getKualifikasiPend($cari); 
	$this->view->listKualifikasiPend = $this->cpnsusul_serv->getJurusanPend($cari); 	
	$this->view->checkListJabatan = $this->cpnsusul_serv->getListCheckJabatCpns($cari); 
	
	
}
public function listData($id) {  
	$cari=" and id=$id";
	$datausul=$this->cpnsusul_serv->getUsulan($cari);
	$this->view->id= $datausul[0]['id'];
	$this->view->n_surat= $datausul[0]['n_surat'];
	$this->view->d_surat= $datausul[0]['d_surat'];
	$this->view->n_perihal= $datausul[0]['n_perihal'];
	$this->view->c_jabatan= $datausul[0]['c_jabatan'];
	$this->view->n_jabatan= $datausul[0]['n_jabatan'];
	$this->view->a_file= $datausul[0]['n_file'];
	$this->view->c_aktivasi= $datausul[0]['c_aktivasi'];	
	$n_surat= $datausul[0]['n_surat'];
	$d_surat= $datausul[0]['d_surat'];
	$carix=" and n_surat='$n_surat' and to_char(d_surat,'dd-mm-yyyy')='$d_surat' ";
	$this->view->dataUsulJabat=$this->cpnsusul_serv->getUsulanJabatan($carix);
	
	
	

}



public function aktivasiAction() {
	$id=trim($_GET['id']);
	$aktivasi=$_GET['aktivasi'];
	$MaintainDataUsulan = array("id"=>$id,"c_aktivasi"=>$aktivasi);
	$hasil = $this->cpnsusul_serv->updateAktivasi($MaintainDataUsulan,'all');
	if ($hasil=="sukses"){$hasil = $this->cpnsusul_serv->updateAktivasi($MaintainDataUsulan,'update');}

	$pesan=$hasil;
	$this->view->pesan = $pesan;
	$this->view->usulanList = $this->cpnsusul_serv->getUsulan($carixx);	
	$this->render('listusulan');
}
public function maintaindataAction() {

	if ($_POST['a_file'])
	{
		$namefile=trim($_POST['n_surat']);
		$namefile=str_replace("/", "", $namefile);
		$FileName_dat = $namefile;
		$fileName = $_FILES['surat']['name'];				
		//$extention = substr($fileName, strrpos($fileName, '.') + 1);
		$extention = "pdf";
		$FileName_pdf = $FileName_dat.'.'.$extention;
		$destDir = "$FileName_pdf";	
	}
		
 	if ($_POST['d_surat'])
		{
			$d_surat1=substr($_POST['d_surat'],0,2);
			$d_surat2=substr($_POST['d_surat'],3,2);
			$d_surat3=substr($_POST['d_surat'],6,4);
		}
		$d_surat=$d_surat3."-".$d_surat2."-".$d_surat1;
		if (!$_POST['d_surat']){$d_surat=null;}
		
	$MaintainDataUsulan = array("id"=>$_POST['id'],
				"n_surat"=>$_POST['n_surat'],
				"d_surat"=>$d_surat,
				"n_perihal"=>$_POST['n_perihal'],
				"c_jabatan"=>$_POST['c_jabatan'],
				"n_pejabat"=>$_POST['n_pejabat'],
				"c_aktivasi"=>"2",
				"n_file"=>$destDir);
				
	if ($_POST['proses']=='Simpan')
		{
			$hasil = $this->cpnsusul_serv->maintainDataUsul($MaintainDataUsulan,'insert');		
			$this->view->par="Simpan";
			$this->view->jdl="Menambah ";
			$par="Menambah";
		}
	else if ($_POST['proses']=='Ubah')
		{
			
			$hasil = $this->cpnsusul_serv->maintainDataUsul($MaintainDataUsulan,'update');
			$this->view->par="Ubah";
			$this->view->jdl="Merubah ";
			$par="Merubah";	
		}	
	else
		{
			$MaintainDataUsulan = array("id"=>$_GET['id']);
			$hasil = $this->cpnsusul_serv->maintainDataUsul($MaintainDataUsulan,'delete');		
			$this->view->par="Hapus";
			$this->view->jdl="Menghapus ";
			$par="Menghapus";			
		}
		
/// simpan file
 	if ($hasil=="sukses"){
			$namefile=trim($_POST['n_surat']);
			$namefile=str_replace("/", "", $namefile);
			$FileName_pdf;
			$fileNamex = $_FILES['surat']['name'];
			//$extentionx = substr($fileNamex, -3, 3);
			$extentionx = substr($fileNamex, strrpos($fileNamex, '.') + 1);
				
		    if (!empty($_FILES['surat'])) 
				{$FileName_pdf = $FileName_dat.'.'.$extentionx;}
				$FileName_dat = $namefile;
				$FileName_pdf = $FileName_dat.'.'.$extentionx;
					
				if (!empty($_FILES['surat'])) 	   {

				   $fileName = $_FILES['surat']['name'];
				   
				   
				   //$extention = substr($fileName, -3, 3);
				   $extention = substr($fileName, strrpos($fileName, '.') + 1);
				   
						$destDir = "../library/data/sdm/dosier/$FileName_pdf";
						if (move_uploaded_file($_FILES['surat']['tmp_name'], $destDir)) { 
							$lampiran ="file";							
							chmod($destDir, 0777);
			
						}
				}
					

		$counttable=$_POST['counttable'];
		$con=1;	
		for($xi=0;$xi<$counttable;$xi++)
		{
			$cek = $_POST['cek'.$con];
				if ($cek=="ok")
				{
				
				if ($_POST['c_jabatan_usul_'.$con]){
					$MaintainDataUsulJabat = array("n_surat"=>$_POST['n_surat'],
								"d_surat"=>$d_surat, 
								"n_jabatan_usul"=>$_POST['n_jabatan_usul_'.$con],
								"c_jabatan_usul"=>$_POST['c_jabatan_usul_'.$con],
								"c_kualifikasi_pend"=>$_POST['c_kualifikasi_pend_'.$con],
								"n_pend_usul"=>$_POST['n_pend_usul_'.$con]);
								
								
/* 					if ($_POST['proses']=='Simpan')
						{
							$hasil = $this->cpnsusul_serv->maintainDataUsulJabatan($MaintainDataUsulJabat,'insert');		
							$this->view->par="Simpan";
							$this->view->jdl="Menambah ";
							$par="Menambah";
						}
					else 
						{ */
							if ($xi==0){
								$hasil = $this->cpnsusul_serv->maintainDataUsulJabatan($MaintainDataUsulJabat,'delete');
							}
							if ($hasil=="sukses"){
								$hasil = $this->cpnsusul_serv->maintainDataUsulJabatan($MaintainDataUsulJabat,'insert');
							}
							
/* 							$this->view->par="Ubah";
							$this->view->jdl="Merubah ";
							$par="Merubah";	 */
						//}
				}		
			}
			$con++;	
		}
	}
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;		
	$this->view->usulanList = $this->cpnsusul_serv->getUsulan($carixx);	
	$this->render('listusulan');
}	

public function listnamajabatanAction() {
	$c_eselon=$_GET['c_eselon'];
	if ($c_eselon){$cari=" and c_eselon='$c_eselon' ";}
	$this->view->nmJabatanList = $this->reff_serv->getJabatan($cari);
}
}
?>