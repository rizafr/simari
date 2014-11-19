<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Tpm_Service.php";

class Sdmmodule_TpmController extends Zend_Controller_Action {

		
public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu');
		$this->view->photoPath = $registry->get('photoPath');
		 
		$this->reff_serv = sdm_refferensi_Service::getInstance();
		$this->tpm_serv = Sdm_Tpm_Service::getInstance();}
	
public function indexAction() {}
public function tpmjsAction() 
{
	header('content-type : text/javascript');
	$this->render('tpmjs');		
}	
public function listusultpmAction() {
	$this->view->dataTpmUsul = $this->tpm_serv->getTpmUsulan($cari);
}
public function tpmusulAction() {
	$par=$_GET['par'];	
	if ($par=='update'){
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$usulan_id=$_GET['usulan_id'];
		$this->listDataByKeyUsul($usulan_id);
	}
	else{

		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
	}
}

public function listDataByKeyUsul($usulan_id) {  
	$cari = " and usulan_id =$usulan_id ";
	$datausulan=$this->tpm_serv->getTpmUsulan($cari );	

	$this->view->usulan_id=$datausulan[0]['usulan_id'];
	$this->view->usulan_nomor=$datausulan[0]['usulan_nomor'];
	$this->view->usulan_keterangan=$datausulan[0]['usulan_keterangan'];
	$this->view->tvinstansi_kd=$datausulan[0]['tvinstansi_kd'];
	$this->view->mod_date=$datausulan[0]['mod_date'];
	$this->view->periode_text=$datausulan[0]['periode_text'];
	$this->view->periode_keterangan=$datausulan[0]['periode_keterangan'];
}


public function maintaindatatpmusulAction() {


 		if ($_POST['mod_date'])
		{
			$mod_date1=substr($_POST['mod_date'],0,2);
			$mod_date2=substr($_POST['mod_date'],3,2);
			$mod_date3=substr($_POST['mod_date'],6,4);
		}
		$mod_date=$mod_date3."-".$mod_date2."-".$mod_date1;
		if (!$_POST['mod_date']){$mod_date=null;$cektglmulai=true;}
		
$MaintainData = array("usulan_id"=>$_POST['usulan_id'],
			"usulan_nomor"=>$_POST['usulan_nomor'],
			"usulan_keterangan"=>$_POST['usulan_keterangan'],
			"tvinstansi_kd"=>$_POST['tvinstansi_kd'],
			"mod_date"=>$mod_date,
			"periode_text"=>$_POST['periode_text'],
			"periode_keterangan"=>$_POST['periode_keterangan']);
							
	if ($_POST['proses']=='Simpan')
		{
			$hasil = $this->tpm_serv->maintainDataTpmUsulan($MaintainData,'insert');		
			$this->view->par="Simpan";
			$this->view->jdl="Menambah ";
			$par="Menambah";
		}
	else if ($_POST['proses']=='Ubah')
		{
			$hasil = $this->tpm_serv->maintainDataTpmUsulan($MaintainData,'update');
			$this->view->par="Ubah";
			$this->view->jdl="Merubah ";
			$par="Merubah";	
		}	
	else
		{
			$MaintainData = array("usulan_id"=>$_GET['usulan_id']);
			$hasil = $this->tpm_serv->maintainDataTpmUsulan($MaintainData,'delete');		
			$this->view->par="Hapus";
			$this->view->jdl="Menghapus ";
			$par="Menghapus";
			
		}
	$this->view->dataTpmUsul = $this->tpm_serv->getTpmUsulan($cari);
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->render('listusultpm');		
}

//=================================================================================================================
	
public function listpratpmAction() {
	$cari=" and a.usulan_id=b.usulan_id and tpm_status_2 isnull and tpm_status_3 isnull ";	
	$this->view->dataPraTpm = $this->tpm_serv->getListTpm2($cari);
}	

public function pratpmAction() {
	$par=$_GET['par'];	
	if ($par=='update'){
		$usulan_id=$_GET['usulan_id'];
		$this->listDataTpmList($usulan_id);
		$cari=" and usulan_id='$usulan_id'";
		$this->view->dataPraTpm = $this->tpm_serv->getTpmPegawai($cari);
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
	}
	else{

		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";		
	}
}
public function listtpmusulpopupAction() {
	$cari=" and usulan_id not in (select usulan_id from sdm.tm_pegawai_tpm) ";	
	$this->view->dataTpmUsul = $this->tpm_serv->getTpmUsulan($cari);
}
public function listtpmiAction() {
	$usulan_id=$_GET['usulan_id'];
	if ($usulan_id){
	$cari=" and a.usulan_id=b.usulan_id and b.usulan_id ='$usulan_id' and tpm_status_1=3 and tpm_status_2 is not null and tpm_status_3 isnull ";	
	}
	else{
	$cari=" and a.usulan_id=b.usulan_id and tpm_status_1=3 and tpm_status_2 is not null and tpm_status_3 isnull ";	
	}
	$this->view->dataPraTpm = $this->tpm_serv->getListTpm2($cari);
	$this->view->usulan_id=$_GET['usulan_id'];
}
public function listpratpmtpmiAction() {
//$cari=" and a.usulan_id=b.usulan_id and tpm_status_2 isnull and tpm_status_3 isnull ";	
$cari=" and a.usulan_id=b.usulan_id";	
$this->view->dataPraTpm = $this->tpm_serv->getListTpm2($cari);

}
public function tpmiAction() {
	$par=$_GET['tanggal'];
	$judul=$_GET['judul'];
	$par=$_GET['par'];	
	if ($par=='insert'){
		$usulan_id=$_GET['usulan_id'];
		$cari=" and tpm_status_1=3 and tpm_status_2 isnull and tpm_status_3 isnull and usulan_id='$usulan_id' ";		
		$this->view->dataPraTpm= $this->tpm_serv->getTpmPegawai($cari);
		$this->listDataTpmList($usulan_id);		
	
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
	}
	else{
		$usulan_id=$_GET['usulan_id'];
		$cari=" and tpm_status_2 is not null and tpm_status_3 isnull and usulan_id='$usulan_id' ";		
		$this->view->dataPraTpm= $this->tpm_serv->getTpmPegawai($cari);
		$this->listDataTpmList($usulan_id);	
		
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";	
		$par=$_GET['par'];
	}
}

public function listpmtipmiiAction() {
$cari=" and a.usulan_id=b.usulan_id and tpm_status_1=3 and tpm_status_2 is not null and tpm_status_3 isnull ";	
$this->view->dataPraTpm = $this->tpm_serv->getListTpm2($cari);
}

public function listtpmiiAction() {

	$usulan_id=$_GET['usulan_id'];
	if ($usulan_id){
		$cari=" and a.usulan_id=b.usulan_id and b.usulan_id ='$usulan_id' and tpm_status_1=3 and tpm_status_2 = 3 and tpm_status_3 is not null ";
	}
	else{
		$cari=" and a.usulan_id=b.usulan_id and tpm_status_1=3 and tpm_status_2 = 3 and tpm_status_3 is not null ";
	}
	
		
	$this->view->dataPraTpm = $this->tpm_serv->getListTpm($cari);
	$this->view->usulan_id=$_GET['usulan_id'];
}	

public function tpmiiAction() {
	$par=$_GET['tanggal'];
	$judul=$_GET['judul'];
	$par=$_GET['par'];	
	if ($par=='insert'){
		$usulan_id=$_GET['usulan_id'];
		$cari=" and tpm_status_1=3 and tpm_status_2=3 and tpm_status_3 isnull and usulan_id='$usulan_id' ";		
		$this->view->dataPraTpm= $this->tpm_serv->getTpmPegawai($cari);
		$this->listDataTpmList($usulan_id);		
	
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
	}
	else{
		$usulan_id=$_GET['usulan_id'];
		$cari=" and tpm_status_2 is not null and tpm_status_3 is not null and usulan_id='$usulan_id' ";		
		$this->view->dataPraTpm= $this->tpm_serv->getTpmPegawai($cari);
		$this->listDataTpmList($usulan_id);	
		
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";	
		$par=$_GET['par'];
	}
}


public function maintaindatatpmAction() {

$counttable=$_POST['counttable'];
$con=1;	
	  
$id_list=$this->tpm_serv->setNomorListTpm('tpm');
for($xi=0;$xi<$counttable;$xi++)
	{
		if ($_POST['i_peg_nip_'.$con])
			{

		if ($_POST['modul']=='pratpm')	{$tpm_status_1=$_POST['c_status_'.$con];$tpm_stts="tpm1";}
		if ($_POST['modul']=='tpmi')	{$tpm_status_2=$_POST['c_status_'.$con];$tpm_stts="tpm2";}
		if ($_POST['modul']=='tpmii')	{$tpm_status_3=$_POST['c_status_'.$con];$tpm_stts="tpm3";}			
		//$id_list='1';
		
 		if ($_POST['d_tpm'])
		{
			$d_tpm1=substr($_POST['d_tpm'],0,2);
			$d_tpm2=substr($_POST['d_tpm'],3,2);
			$d_tpm3=substr($_POST['d_tpm'],6,4);
		}
		$d_tpm=$d_tpm3."-".$d_tpm2."-".$d_tpm1;
		if (!$_POST['d_tpm']){$d_tpm=null;$cektglmulai=true;}
		
 		if ($_POST['tpm_tgllahir'])
		{
			$tpm_tgllahir1=substr($_POST['tpm_tgllahir'],0,2);
			$tpm_tgllahir2=substr($_POST['tpm_tgllahir'],3,2);
			$tpm_tgllahir3=substr($_POST['tpm_tgllahir'],6,4);
		}
		$tpm_tgllahir=$tpm_tgllahir3."-".$tpm_tgllahir2."-".$tpm_tgllahir1;
		if (!$_POST['tpm_tgllahir']){$tpm_tgllahir=null;$cektglmulai=true;}		
		
				$MaintainData = array( "tpm_id"=>$_POST['tpm_id_'.$con],
							"id_list"=>$id_list*1,
							"usulan_id"=>$_POST['usulan_id'],
							"peg_nip"=>$_POST['i_peg_nip_'.$con],
							"tvjabatan_kd"=>$_POST['tvjabatan_kd'],
							"tvinstansi_kd"=>$_POST['tvinstansi_kd'],
							"tpm_keterangan"=>$_POST['tpm_keterangan'],
							"tpm_status"=>$_POST['tpm_status'],
							"tpm_tgl"=>$d_tpm,
							"tvinstansi_kd_l"=>$_POST['tvinstansi_kd_l'],
							"ljabatangol_nm"=>$_POST['ljabatangol_nm'],
							"tpm_jekel"=>$_POST['c_jnskelamin_'.$con],
							"tpm_tgllahir"=>$tpm_tgllahir,
							"tpm_namalengkap"=>$_POST['n_peg_'.$con],
							"lap_gol_tmt"=>$_POST['lap_gol_tmt'],
							"lap_gol_nm"=>$_POST['c_golongan_'.$con],
							"lap_jab_l_nm"=>$_POST['lap_jab_l_nm'],
							"lap_jab_l_kelas"=>$_POST['lap_jab_l_kelas'],
							"lap_jab_l_instan"=>$_POST['lap_jab_l_instan'],
							"lap_jab_b_nm"=>$_POST['lap_jab_b_nm'],
							"lap_jab_b_kelas"=>$_POST['lap_jab_b_kelas'],
							"lap_jab_b_instan"=>$_POST['lap_jab_b_instan'],
							"tpm_status_1"=>$tpm_status_1,
							"tpm_status_2"=>$tpm_status_2,
							"tpm_status_3"=>$tpm_status_3,
							"nama_list"=>$_POST['n_tpm'],
							"tpm_stts"=>$tpm_stts);	
							
				if ($_POST['proses']=='Simpan')
				{
					if ($_POST['modul']=='pratpm')	{
						$hasil = $this->tpm_serv->maintainDataTpmPegawai($MaintainData,'insert');
						if ($hasil=="sukses" && $con==1){$hasil = $this->tpm_serv->maintainDataTpmList($MaintainData,'insert');}	
					}
					if ($_POST['modul']=='tpmi'){
						$tpm_id=$_POST['tpm_id_'.$con]*1;
						$cekData=$this->tpm_serv->cekDataTpm(" and tpm_id=$tpm_id ");
						if ($cekData==0)
						{
							$hasil = $this->tpm_serv->maintainDataTpmPegawai($MaintainData,'insert');
						}
						else{
							$hasil = $this->tpm_serv->maintainDataTpmPegawaiUpdtTpmi($MaintainData,'update');
						}
					}
					if ($_POST['modul']=='tpmii'){
						$tpm_id=$_POST['tpm_id_'.$con]*1;
						$cekData=$this->tpm_serv->cekDataTpm(" and tpm_id=$tpm_id ");
						if ($cekData==0)
						{
							$hasil = $this->tpm_serv->maintainDataTpmPegawai($MaintainData,'insert');
						}
						else{
							$hasil = $this->tpm_serv->maintainDataTpmPegawaiUpdtTpmii($MaintainData,'update');
						}
					}					
					$this->view->par="Simpan";
					$this->view->jdl="Menambah ";
					$par="Menambah";
				}
				else if ($_POST['proses']=='Hapus')
				{
					$hasil = $this->tpm_serv->maintainDataTpmPegawai($MaintainData,'delete');
					if ($hasil=="sukses" && $con==1){$hasil = $this->tpm_serv->maintainDataTpmList($MaintainData,'delete');}	
					$this->view->par="Hapus";
					$this->view->jdl="Menghapus ";
					$par="Menghapus";
				}	
				else
				{
					
					if ($_POST['modul']=='pratpm')	{
						if ($con==1){
							$hasil = $this->tpm_serv->maintainDataTpmPegawai($MaintainData,'delete');
						}
						if ($hasil=="sukses"){$hasil = $this->tpm_serv->maintainDataTpmPegawai($MaintainData,'insert');}
						if ($hasil=="sukses" && $con==1){$hasil = $this->tpm_serv->maintainDataTpmList($MaintainData,'update');}
					}
					if ($_POST['modul']=='tpmi'){
						$tpm_id=$_POST['tpm_id_'.$con]*1;
						$cekData=$this->tpm_serv->cekDataTpm(" and tpm_id=$tpm_id ");
						if ($cekData==0)
						{
							$hasil = $this->tpm_serv->maintainDataTpmPegawai($MaintainData,'insert');
						}
						else{
							$hasil = $this->tpm_serv->maintainDataTpmPegawaiUpdtTpmi($MaintainData,'update');
						}
					}
					if ($_POST['modul']=='tpmii'){
						$tpm_id=$_POST['tpm_id_'.$con]*1;
						$cekData=$this->tpm_serv->cekDataTpm(" and tpm_id=$tpm_id ");
						if ($cekData==0)
						{
							$hasil = $this->tpm_serv->maintainDataTpmPegawai($MaintainData,'insert');
						}
						else{
							$hasil = $this->tpm_serv->maintainDataTpmPegawaiUpdtTpmii($MaintainData,'update');
						}
					}					
					$this->view->par="Ubah";
					$this->view->jdl="Merubah ";
					$par="Merubah";		
				}
				
				
			}						
		
						$con++;						
	}
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	
if ($_POST['modul']=='pratpm')	
{
	$cari=" and a.usulan_id=b.usulan_id and a.usulan_id=b.usulan_id and tpm_status_2 isnull and tpm_status_3 isnull ";	
	$this->view->dataPraTpm = $this->tpm_serv->getListTpm2($cari);
	$this->render('listpratpm');
}
if ($_POST['modul']=='tpmi')	{
	$cari=" and a.usulan_id=b.usulan_id and tpm_status_1=3 and tpm_status_2 is not null and tpm_status_3 isnull ";	
	$this->view->dataPraTpm = $this->tpm_serv->getListTpm2($cari);
	$this->render('listtpmi');
}
if ($_POST['modul']=='tpmii')	{
	$cari=" and a.usulan_id=b.usulan_id and tpm_status_1=3 and tpm_status_2 = 3 and tpm_status_3 is not null ";	
	$this->view->dataPraTpm = $this->tpm_serv->getListTpm2($cari);
	$this->render('listtpmii');}


}



public function listDataTpmList($usulan_id) {  
	$cari = " and a.usulan_id =$usulan_id ";
	$datatpm=$this->tpm_serv->getListTpm($cari );	
								
	$this->view->usulan_id=$datatpm[0]['usulan_id'];
	$this->view->usulan_nomor=$datatpm[0]['usulan_nomor'];
	$this->view->usulan_keterangan=$datatpm[0]['usulan_keterangan'];
	$this->view->tvinstansi_kd=$datatpm[0]['tvinstansi_kd'];
	$this->view->mod_date=$datatpm[0]['mod_date'];
	$this->view->periode_text=$datatpm[0]['periode_text'];
	$this->view->d_tpm=$datatpm[0]['tpm_tgl'];
	$this->view->periode_keterangan=$datatpm[0]['periode_keterangan'];
	$this->view->n_tpm=$datatpm[0]['nama_list'];

}


public function daftartpmAction() {
	$cari=" and a.usulan_id=b.usulan_id and tpm_status_1=3 and tpm_status_2 is not null and tpm_status_3 is not null ";	
	//$cari = " and a.usulan_id = b.usulan_id";
	$this->view->dataPraTpm = $this->tpm_serv->getListTpm($cari);
}
public function cetakAction() {
	$cari = " and a.usulan_id = b.usulan_id";
	$this->view->dataPraTpm = $this->tpm_serv->getListTpm($cari);
}
		
}
?>