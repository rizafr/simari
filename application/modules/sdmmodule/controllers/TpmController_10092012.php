<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Tpm_Service.php";
require_once "service/sdm/Sdm_Pegawai_Service.php";
class Sdmmodule_TpmController extends Zend_Controller_Action {

		
public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu');
		$this->view->photoPath = $registry->get('photoPath');
		 
		$this->reff_serv = sdm_refferensi_Service::getInstance();
		$this->tpm_serv = Sdm_Tpm_Service::getInstance();
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		
		$ssologin = new Zend_Session_Namespace('ssologin');
		$this->view->c_eselon_i=$ssologin->c_eselon_i;
		$this->view->c_eselon_ii=$ssologin->c_eselon_ii;
		$this->view->c_eselon_iii=$ssologin->c_eselon_iii;
		$this->view->c_eselon_iv=$ssologin->c_eselon_iv;
		$this->view->c_eselon_v=$ssologin->c_eselon_v;
		
		$this->view->c_eselon=$ssologin->c_eselon;
		$this->view->sektoral=$ssologin->sektoral;
		$this->view->wewenang=$ssologin->wewenang;	
		$this->view->c_satker=$ssologin->c_satker;
		$this->view->c_parent=$ssologin->c_parent;	
		
		}
	
public function indexAction() {}
public function tpmjsAction() 
{
	header('content-type : text/javascript');
	$this->render('tpmjs');		
}	

public function listusultpmAction() {

	$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
	$this->view->katakunciCari 	= $_REQUEST['carii'];
	$this->view->status 	= $_REQUEST['status'];
	//var_dump($this->view->status);
	$whereOptCar = "";
	$kategoriCari = $this->view->kategoriCari;
	$katakunciCari = $this->view->katakunciCari;
	if($this->view->kategoriCari == "") { $kategoriCari ="usulan_nomor";}
		
	if($this->view->katakunciCari != ""){$whereOptCar = " and lower($kategoriCari) like '%$katakunciCari%' ";}
		
	if($this->view->status == 'usul'){	
	    $cari=$whereOptCar." and usulan_id not in (select usulan_id from sdm.tm_pegawai_tpm)";
	}
	else if($this->view->status == 'proses') {
		$cari=$whereOptCar." and usulan_id in (select usulan_id from sdm.tm_pegawai_tpm)";
	}
	else{
		$cari=$whereOptCar."";
	}
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
	if ($par=='view'){
		$this->view->par="view";
		$usulan_id=$_GET['usulan_id'];
		$this->listDataByKeyUsul($usulan_id);
	}	
	else{

		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
		if($this->view->sektoral=='S'){	
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1'");
		}
		if($this->view->sektoral=='D'){
			$c_eselon_i=$this->view->c_eselon_i;
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_eselon_i='$c_eselon_i' ");
		}		
	}

}

public function listDataByKeyUsul($usulan_id) {  
	$cari = " and usulan_id =$usulan_id ";
	$datausulan=$this->tpm_serv->getTpmUsulan($cari );	

	$this->view->usulan_id=$datausulan[0]['usulan_id'];
	$this->view->usulan_nomor=$datausulan[0]['usulan_nomor'];
	$this->view->usulan_keterangan=$datausulan[0]['usulan_keterangan'];
	$this->view->n_jabatan=$datausulan[0]['n_jabatan'];
	$this->view->tvinstansi_kd=$datausulan[0]['tvinstansi_kd'];
	$this->view->mod_date=$datausulan[0]['mod_date'];
	$this->view->periode_text=$datausulan[0]['periode_text'];
	$this->view->periode_keterangan=$datausulan[0]['periode_keterangan'];
	
	
$c_eselon_i=trim($datausulan[0]['c_eselon_i']);	
$this->view->c_eselon_ix=$c_eselon_i;
if ($c_eselon_i=='03' || $c_eselon_i=='04' || $c_eselon_i=='05'){

	$neselon1=trim($datausulan[0]['neselon1']);
	$neselon2=trim($datausulan[0]['neselon2']);
	$neselon3=trim($datausulan[0]['neselon3']);
	$neselon4=trim($datausulan[0]['neselon4']);
	$neselon5=trim($datausulan[0]['neselon5']);
	
	$c_eselon_i=trim($datausulan[0]['c_eselon_i']);
	$c_eselon_ii=trim($datausulan[0]['c_eselon_ii']);
	$c_eselon_iii=trim($datausulan[0]['c_eselon_iii']);
	$c_eselon_iv=trim($datausulan[0]['c_eselon_iv']);
	$c_eselon_v=trim($datausulan[0]['c_eselon_v']);
	$c_satker=trim($datausulan[0]['c_satker']);
	$c_parent=trim($datausulan[0]['c_parent']);
	
	
	if($this->view->sektoral=='S'){	
		$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1'");
	}
	if($this->view->sektoral=='D'){
		$c_eselon_i=$this->view->c_eselon_i;
		$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_eselon_i='$c_eselon_i' ");
	}	

	$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja("and c_level ='2' and c_lokasi_unitkerja='3' and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii'");		
	$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and  c_level ='3' and c_lokasi_unitkerja='3' and c_eselon_i='$c_eselon_i'  and c_parent ='$c_parent'");	
	$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_level ='5' and c_lokasi_unitkerja='3' and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_parent ='$c_parent'");	
	
	$this->view->c_eselon_i=trim($datausulan[0]['c_eselon_i']).";".trim($datausulan[0]['neselon1']);


	$this->view->c_eselon_ii=$c_eselon_ii.";".trim($datausulan[0]['c_parent']).";".trim($datausulan[0]['neselon2']);
	$this->view->c_eselon_iii=trim($datausulan[0]['ceseloncpns2']).";".trim($datausulan[0]['c_eselon_iii']).";".trim($datausulan[0]['c_satker']).";".trim($datausulan[0]['neselon3']);

	
	$this->view->c_eselon_iv=trim($datausulan[0]['c_eselon_iv']).";".trim($datausulan[0]['neselon4']);
	
	//echo trim($datausulan[0]['c_eselon_iv']).";".trim($datausulan[0]['neselon4']);
	
	$this->view->n_eselon_i=trim($datausulan[0]['neselon1']);
	$this->view->n_eselon_ii=trim($datausulan[0]['neselon2']);
	$this->view->n_eselon_iii=trim($datausulan[0]['neselon3']);
	$this->view->n_eselon_iv=trim($datausulan[0]['neselon4']);	

}
else{
	$neselon1=trim($datausulan[0]['neselon1']);
	$neselon2=trim($datausulan[0]['neselon2']);
	$neselon3=trim($datausulan[0]['neselon3']);
	$neselon4=trim($datausulan[0]['neselon4']);

	$this->view->c_eselon_i=trim($datausulan[0]['c_eselon_i']).";".trim($datausulan[0]['neselon1']);
	$this->view->c_eselon_ii=trim($datausulan[0]['c_eselon_ii']).";".trim($datausulan[0]['neselon2']);
	$this->view->c_eselon_iii=trim($datausulan[0]['c_eselon_iii']).";".trim($datausulan[0]['neselon3']);
	$this->view->c_eselon_iv=trim($datausulan[0]['c_eselon_iv']).";".trim($datausulan[0]['neselon4']);	
	
	$this->view->n_eselon_i=trim($datausulan[0]['neselon1']);
	$this->view->n_eselon_ii=trim($datausulan[0]['neselon2']);
	$this->view->n_eselon_iii=trim($datausulan[0]['neselon3']);
	$this->view->n_eselon_iv=trim($datausulan[0]['neselon4']);	

	$c_eselon_i=trim($datausulan[0]['c_eselon_i']);
	$c_eselon_ii=trim($datausulan[0]['c_eselon_ii']);
	$c_eselon_iii=trim($datausulan[0]['c_eselon_iii']);
	$c_eselon_iv=trim($datausulan[0]['c_eselon_iv']);
	
	if($this->view->sektoral=='S'){	
		$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1'");
	}
	if($this->view->sektoral=='D'){
		$c_eselon_i=$this->view->c_eselon_i;
		$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_eselon_i='$c_eselon_i' ");
	}	
	

	$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja("  and c_eselon_i='$c_eselon_i' and c_tkt_esl='2' and c_lokasi_unitkerja='1'");	
	$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='3' and c_lokasi_unitkerja='1'");	
	$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'   and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_tkt_esl='4' and c_lokasi_unitkerja='1'");
	$this->view->eselonvList = $this->reff_serv->getTrUnitKerja("  and c_eselon_i='$c_eselon_i'   and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='4' and c_lokasi_unitkerja='1'");	
	

}
	
	
}


public function maintaindatatpmusulAction() {

	$c_eselon_i=$_POST['c_eselon_i'];
	$expesl1 = explode(";",$c_eselon_i);
	$c_eselon_i=trim($expesl1[0]);
	if ($c_eselon_i=='03' || $c_eselon_i=='04' || $c_eselon_i=='05')
	{
			$c_eselon_i=$_POST['c_eselon_i'];
			if ($_POST['c_eselon_i']!=''){
				$c_eselon_il=explode(";",$c_eselon_i);
				$c_eselon_i=$c_eselon_il[0];
			}
			else {$c_eselon_i='00';}

			$c_eselon_ii=$_POST['c_eselon_ii'];
			if ($_POST['c_eselon_ii']!=''){			
				$valesl = explode(";",$c_eselon_ii);
				$c_eselon_ii=$valesl[0];
				$c_parent=$valesl[1];
			}
			else {$c_eselon_ii='00';$c_parent='00';}

			$c_eselon_iii=$_POST['c_eselon_iii'];
			if ($_POST['c_eselon_iii']!=''){			
				$valesliii = explode(";",$c_eselon_iii);
				$c_eselon_iii=$valesliii[0];
				$c_eselon_iii=$valesliii[1];
				$c_satker=$valesliii[2];	
			}
			else {$c_eselon_iii='00';$c_satker='00';}

			$c_eselon_iv=$_POST['c_eselon_iv'];
			if ($_POST['c_eselon_iv']!=''){
				$valesliv = explode(";",$c_eselon_iv);
				$c_eselon_iv=$valesliv[0];
			}
			else {$c_eselon_iv='00';}
		}
		else
		{

			$c_eselon_i=$_POST['c_eselon_i'];
			if ($_POST['c_eselon_i']!=''){	$c_eselon_il=strlen($c_eselon_i); $c_eselon_i=$this->right($c_eselon_i,$c_eselon_il);}
			else {$c_eselon_i='00';}

			$c_eselon_ii=$_POST['c_eselon_ii'];
			if ($_POST['c_eselon_ii']!=''){$c_eselon_iil=strlen($c_eselon_ii); $c_eselon_ii=$this->right2($c_eselon_ii, $c_eselon_iil);}
			else {$c_eselon_ii='000';}

			$c_eselon_iii=$_POST['c_eselon_iii'];
			if ($_POST['c_eselon_iii']!=''){$c_eselon_iiil=strlen($c_eselon_iii); $c_eselon_iii=$this->right($c_eselon_iii, $c_eselon_iiil);}
			else {$c_eselon_iii='00';}

			$c_eselon_iv=$_POST['c_eselon_iv'];
			if ($_POST['c_eselon_iv']!=''){$c_eselon_ivl=strlen($c_eselon_iv); $c_eselon_iv=$this->right($c_eselon_iv, $c_eselon_ivl);}
			else {$c_eselon_iv='00';}
		}


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
			"periode_keterangan"=>$_POST['periode_keterangan'],
			"c_eselon_i"=>$c_eselon_i,
			"c_eselon_ii"=>$c_eselon_ii,
			"c_eselon_iii"=>$c_eselon_iii,
			"c_eselon_iv"=>$c_eselon_iv,
			"c_satker"=>$c_satker,
			"c_parent"=>$c_parent );
							
	if ($_POST['proses']=='Simpan')
		{
			$hasil = $this->tpm_serv->maintainDataTpmUsulan($MaintainData,'insert');		
			$this->view->par="Simpan";
			$this->view->jdl="Menambah ";
			$par="Menambah";
			$cari=" and usulan_id not in (select usulan_id from sdm.tm_pegawai_tpm)";
	
		}
	else if ($_POST['proses']=='Ubah')
		{
			$hasil = $this->tpm_serv->maintainDataTpmUsulan($MaintainData,'update');
			$this->view->par="Ubah";
			$this->view->jdl="Merubah ";
			$par="Merubah";	
			$cari=" and usulan_id not in (select usulan_id from sdm.tm_pegawai_tpm)";
	}	
	else
		{
			$MaintainData = array("usulan_id"=>$_GET['usulan_id']);
			$hasil = $this->tpm_serv->maintainDataTpmUsulan($MaintainData,'delete');		
			$this->view->par="Hapus";
			$this->view->jdl="Menghapus ";
			$par="Menghapus";
			$cari=" and usulan_id not in (select usulan_id from sdm.tm_pegawai_tpm)";
	
		}
	$this->view->dataTpmUsul = $this->tpm_serv->getTpmUsulan($cari);
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	$this->render('listusultpm');		
}

//=================================================================================================================
	
public function listpratpmAction() {
	$cari=" usulan_id in (select usulan_id from sdm.tm_pegawai_tpm 
		where (tpm_status_1 is not null and tpm_status_2 is not null and tpm_status_3 is not null) 
		or (tpm_status_2 isnull and tpm_status_3 isnull)) and tpm_status=0 ";	
	$this->view->dataPraTpm = $this->tpm_serv->getListTpm5($cari);
}	

public function pratpmAction() {
	$par=$_GET['par'];	
	$this->view->usulan_id=$_GET['usulan_id'];
		
	if ($par=='update'){
		$usulan_id = $this->view->usulan_id;
		$this->listDataTpmList($usulan_id);
		$this->listDataTpmList3($usulan_id);
		$cari=" a.usulan_id='$usulan_id'";
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
/*	$usulan_id=$_GET['usulan_id'];
	if ($usulan_id){
	//$cari=" and a.usulan_id=b.usulan_id and b.usulan_id ='$usulan_id' and tpm_status_1=3 and tpm_status_2 is not null and tpm_status_3 isnull ";	
	//$cari="  a.usulan_id ='$usulan_id' and tpm_status_1=1 and tpm_status_2 is null and tpm_status_3 isnull ";
	$cari="  a.usulan_id ='$usulan_id' ";
	} */
/*	else{
	//$cari=" and a.usulan_id=b.usulan_id and tpm_status_1=3 and tpm_status_2 is not null and tpm_status_3 isnull ";
	$cari=" a.usulan_id=b.usulan_id and tpm_status_1=1 and tpm_status_2 is null and tpm_status_3 isnull ";
	} */
	$cari=" usulan_id in (select usulan_id from sdm.tm_pegawai_tpm where (tpm_status_1 = '3' and  tpm_status_3 isnull) 
	or (tpm_status_1 isnull and (tpm_status_3 is not null or tpm_status_3 isnull))) ";	
	$this->view->dataPraTpm = $this->tpm_serv->getListTpm5($cari);
	$this->view->usulan_id=$_GET['usulan_id'];
}
public function listpratpmtpmiAction() {
//$cari=" and a.usulan_id=b.usulan_id and tpm_status_2 isnull and tpm_status_3 isnull ";	
$cari=" a.usulan_id=b.usulan_id";	
$this->view->dataPraTpm = $this->tpm_serv->getListTpm2($cari);

}
public function tpmiAction() {
	$par=$_GET['tanggal'];
	$judul=$_GET['judul'];
	$par=$_GET['par'];
	$this->view->usulan_id=$_GET['usulan_id'];
	if ($par=='insert'){
		$usulan_id=$_GET['usulan_id']*1;
		//$cari=" and tpm_status_1=3 and tpm_status_2 isnull and tpm_status_3 isnull and usulan_id='$usulan_id' ";
		$cari="  tpm_status_1=3 and tpm_status_2 isnull and tpm_status_3 isnull and a.usulan_id='$usulan_id' ";
		$this->view->dataPraTpm= $this->tpm_serv->getTpmPegawai($cari);
		$this->listDataTpmList($usulan_id);	
		$this->listDataTpmList3($usulan_id);
	
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
	}
	else{
		$usulan_id=$_GET['usulan_id'];
		//$cari=" tpm_status_2 is not null and tpm_status_3 isnull and usulan_id='$usulan_id' ";		
		//$cari=" a.usulan_id=b.usulan_id and tpm_status_2 is  null and tpm_status_3 isnull and a.usulan_id='$usulan_id' ";		
		$cari="  (tpm_status_1=3 or tpm_status_1 isnull) and a.usulan_id='$usulan_id' ";
		$this->view->dataPraTpm= $this->tpm_serv->getTpmPegawai($cari);
		$this->listDataTpmList($usulan_id);
		$this->listDataTpmList3($usulan_id);
		
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";	
		$par=$_GET['par'];
	}
}

public function listpmtipmiiAction() {
//$cari=" and a.usulan_id=b.usulan_id and tpm_status_1=3 and tpm_status_2 is not null and tpm_status_3 isnull ";	
//$cari=" a.usulan_id=b.usulan_id and tpm_status_1 is not null and tpm_status_2 is not null and tpm_status_3 isnull ";
$cari=" usulan_id in (select usulan_id from sdm.tm_pegawai_tpm where  tpm_status_2 = 3 and tpm_status_3 isnull) ";
$this->view->dataPraTpm = $this->tpm_serv->getListTpm2($cari);
}

public function listtpmiiAction() {

/*	$usulan_id=$_GET['usulan_id'];
	if ($usulan_id){
		//$cari=" and a.usulan_id=b.usulan_id and b.usulan_id ='$usulan_id' and tpm_status_1=3 and tpm_status_2 = 3 and tpm_status_3 is not null ";
		//$cari=" a.usulan_id=b.usulan_id and a.usulan_id ='$usulan_id' and tpm_status_1 is not null and tpm_status_2 is not null and tpm_status_3 is null ";
		$cari=" a.usulan_id=b.usulan_id and a.usulan_id ='$usulan_id' and tpm_status_2 = 3 and tpm_status_3 is null ";
	}
	else{
		//$cari=" and a.usulan_id=b.usulan_id and tpm_status_1=3 and tpm_status_2 = 3 and tpm_status_3 is not null ";
		$cari="  tpm_status_1 is not null and tpm_status_2 is not null and tpm_status_3 is not null ";
	}*/
			
	//$this->view->dataPraTpm = $this->tpm_serv->getListTpm($cari);
	$cari=" usulan_id in (select usulan_id from sdm.tm_pegawai_tpm where (tpm_status_1 = '3' or  tpm_status_1 isnull) or (tpm_status_2 = '3' or  tpm_status_2 isnull) and tpm_status_3 isnull) ";	
	$this->view->dataPraTpm = $this->tpm_serv->getListTpm5($cari);
	$this->view->usulan_id=$_GET['usulan_id'];
}	

public function tpmiiAction() {
	$par=$_GET['tanggal'];
	$judul=$_GET['judul'];
	$par=$_GET['par'];	
	$this->view->usulan_id=$_GET['usulan_id'];
	if ($par=='insert'){
		$usulan_id=$_GET['usulan_id'];
		//$cari=" and tpm_status_1=3 and tpm_status_2=3 and tpm_status_3 isnull and usulan_id='$usulan_id' ";
		//$cari=" tpm_status_1=3 and tpm_status_2=3 and tpm_status_3 isnull and b.usulan_id='$usulan_id' ";
		$cari=" (tpm_status_1=3 or tpm_status_1 isnull) and (tpm_status_2=3 or tpm_status_2 isnull) and a.usulan_id='$usulan_id' ";
		$this->view->dataPraTpm= $this->tpm_serv->getTpmPegawai($cari);
		$this->listDataTpmList($usulan_id);		
		$this->listDataTpmList3($usulan_id);
		
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
	}
	else{
		$usulan_id=$_GET['usulan_id'];
		//$cari=" tpm_status_2 = 3 and tpm_status_3 is null and b.usulan_id='$usulan_id' ";		
		$cari=" (tpm_status_1=3 or tpm_status_1 isnull) and (tpm_status_2=3 or tpm_status_2 isnull) and a.usulan_id='$usulan_id' ";
		$this->view->dataPraTpm= $this->tpm_serv->getTpmPegawai($cari);
		$this->listDataTpmList($usulan_id);	
		$this->listDataTpmList3($usulan_id);
		
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";	
		$par=$_GET['par'];
	}
}


public function maintaindatatpmAction() {

$counttable=$_POST['counttable'];
$con=1;	
	  
$id_list=$this->tpm_serv->setNomorListTpm('tpm');
for($xi=0;$xi<($counttable+1);$xi++)
	{
		if ($_POST['i_peg_nip_'.$con])
			{

		//if ($_POST['modul']=='pratpm')	{$tpm_status_1=$_POST['c_status_'.$con];$tpm_stts="tpm1";}
		if ($_POST['modul']=='pratpm')	{$tpm_status_1="3";$tpm_stts="tpm1";}
		if ($_POST['modul']=='tpmi')	{
			$tpm_status_2=$_POST['c_status_'.$con];$tpm_stts="tpm2";
			if (!$tpm_status_2){$tpm_status_2="3";$tpm_status_1="3";}		
		}
		
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
		if (!$_POST['usulan_rekomendasi_pratpm_'.$con]){$usulan_rekomendasi_pratpm=null;}
		else{$usulan_rekomendasi_pratpm=$_POST['usulan_rekomendasi_pratpm_'.$con];}
		if (!$_POST['usulan_rekomendasi_tpmi_'.$con]){$usulan_rekomendasi_tpmi=null;}
		else{$usulan_rekomendasi_tpmi=$_POST['usulan_rekomendasi_tpmi_'.$con];}
		if (!$_POST['usulan_rekomendasi_tpmii_'.$con]){$usulan_rekomendasi_tpmii=null;}
		else{$usulan_rekomendasi_tpmii=$_POST['usulan_rekomendasi_tpmii_'.$con];}
		
		
				$MaintainData = array( "tpm_id"=>$_POST['tpm_id_'.$con],
							//"id_list"=>$id_list*1,
							"usulan_id"=>$_POST['usulan_id'],
							"peg_nip"=>$_POST['i_peg_nip_'.$con],
							"tvjabatan_kd"=>$_POST['tvjabatan_kd'],
							"tvinstansi_kd"=>$_POST['tvinstansi_kd'],
							"tpm_keterangan"=>$_POST['tpm_keterangan'],
							"tpm_status"=>$_POST['tpm_status'],
							"tpm_tgl"=>$d_tpm,
							"nama_list"=>$_POST['n_tpm'],
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
							"tpm_stts"=>$tpm_stts,
							"usulan_rekomendasi_pratpm"=>$usulan_rekomendasi_pratpm,
							"usulan_rekomendasi_tpmi"=>$usulan_rekomendasi_tpmi,
							"usulan_rekomendasi_tpmii"=>$usulan_rekomendasi_tpmii,							
							"modul"=>$_POST['modul'],
							"c_eselon_i"=>$_POST['c_eselon_i'],
							"c_eselon_ii"=>$_POST['c_eselon_ii'],
							"c_eselon_iii"=>$_POST['c_eselon_iii'],
							"c_eselon_iv"=>$_POST['c_eselon_iv'],
							"c_satker"=>$_POST['c_satker'],
							"c_parent"=>$_POST['c_parent'] );	
					//var_dump($MaintainData);		
				if ($_POST['proses']=='Simpan')
				{
					if ($_POST['modul']=='pratpm')	{
						$hasil = $this->tpm_serv->maintainDataTpmPegawai($MaintainData,'insert');
						//if ($hasil=="sukses" && $con==1){$hasil = $this->tpm_serv->maintainDataTpmList($MaintainData,'insert');}	
					}
					if ($_POST['modul']=='tpmi'){
						$tpm_id=$_POST['tpm_id_'.$con]*1;
						$cekData=$this->tpm_serv->cekDataTpm(" and tpm_id=$tpm_id ");
						if ($cekData==0)
						{
							$hasil = $this->tpm_serv->maintainDataTpmPegawai($MaintainData,'insert');
						}
						elseif ($_POST['usulan_rekomendasi_tpmi_'.$con]){
							$hasil = $this->tpm_serv->maintainDataTpmPegawai($MaintainData,'insert');
						}
						else{
							$hasil = $this->tpm_serv->maintainDataTpmPegawaiUpdtTpmi($MaintainData,'update');
						}
					}
					if ($_POST['modul']=='tpmii'){
						$tpm_id=$_POST['tpm_id_'.$con]*1;
						$cekData=$this->tpm_serv->cekDataTpm(" tpm_id=$tpm_id ");
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
						//if ($hasil=="sukses" && $con==1){$hasil = $this->tpm_serv->maintainDataTpmList($MaintainData,'update');}
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
							if($hasil=="sukses" && $con==1){
								$hasil = $this->tpm_serv->UpdateStatusFinish($MaintainData); 
							}
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
		$cari=" usulan_id in (select usulan_id from sdm.tm_pegawai_tpm 
			where (tpm_status_1 is not null and tpm_status_2 is not null and tpm_status_3 is not null) 
			or (tpm_status_2 isnull and tpm_status_3 isnull)) and tpm_status=0 ";	
		$this->view->dataPraTpm = $this->tpm_serv->getListTpm5($cari);
	
		$this->render('listpratpm');
	}
	if ($_POST['modul']=='tpmi')	{
/* 		$cari=" usulan_id in (select usulan_id from sdm.tm_pegawai_tpm where tpm_status_1=3 and tpm_status_2 is not null and tpm_status_3 isnull) ";	
		$this->view->dataPraTpm = $this->tpm_serv->getListTpm2($cari); */
		$cari=" usulan_id in (select usulan_id from sdm.tm_pegawai_tpm where (tpm_status_1 = '3' and  tpm_status_3 isnull) or (tpm_status_1 isnull and (tpm_status_3 is not null or tpm_status_3 isnull))) ";	
		$this->view->dataPraTpm = $this->tpm_serv->getListTpm5($cari);		
		$this->render('listtpmi');
	}
	if ($_POST['modul']=='tpmii')	{
/* 		$cari=" usulan_id in (select usulan_id from sdm.tm_pegawai_tpm where tpm_status_1=3 and tpm_status_2 = 3 and tpm_status_3 is not null) ";	
		$this->view->dataPraTpm = $this->tpm_serv->getListTpm2($cari); */
		$cari=" usulan_id in (select usulan_id from sdm.tm_pegawai_tpm where (tpm_status_1 = '3' or  tpm_status_1 isnull) or (tpm_status_2 = '3' or  tpm_status_2 isnull) and tpm_status_3 isnull) ";	
		$this->view->dataPraTpm = $this->tpm_serv->getListTpm5($cari);		
		$this->render('listtpmii');
	}


}
public function listDataTpmList($usulan_id) {  
	$cari = "  a.usulan_id =$usulan_id ";
	$datatpm=$this->tpm_serv->getListTpm2($cari );	
								
	//$this->view->usulan_id=$datatpm[0]['usulan_id'];
	$this->view->usulan_nomor=$datatpm[0]['usulan_nomor'];
	$this->view->usulan_keterangan=$datatpm[0]['usulan_keterangan'];
	$this->view->n_jabatan=$datatpm[0]['n_jabatan'];
	$this->view->tvinstansi_kd=$datatpm[0]['tvinstansi_kd'];
	$this->view->unitkerja=$datatpm[0]['unitkerja'];
	$this->view->mod_date=$datatpm[0]['mod_date'];
	$this->view->periode_text=$datatpm[0]['periode_text'];
	$this->view->periode_keterangan=$datatpm[0]['periode_keterangan'];
	
	$this->view->c_eselon_i=$datatpm[0]['c_eselon_i'];
	$this->view->c_eselon_ii=$datatpm[0]['c_eselon_ii'];
	$this->view->c_eselon_iii=$datatpm[0]['c_eselon_iii'];
	$this->view->c_eselon_iv=$datatpm[0]['c_eselon_iv'];
	
	$this->view->n_eselon_i=$datatpm[0]['neselon1'];
	$this->view->n_eselon_ii=$datatpm[0]['neselon2'];
	$this->view->n_eselon_iii=$datatpm[0]['neselon3'];
	$this->view->n_eselon_iv=$datatpm[0]['neselon4'];
	
}
public function listDataTpmList3($usulan_id) {  
	$cari = "  a.usulan_id =$usulan_id ";
	$datatpm=$this->tpm_serv->getListTpm5($cari );	
								
	$this->view->usulan_id=$datatpm[0]['usulan_id'];
	$this->view->ket_pratpm=$datatpm[0]['ket_pratpm'];
	$this->view->tgl_pratpm=$datatpm[0]['tgl_pratpm'];
	$this->view->ket_tpm1=$datatpm[0]['ket_tpm1'];
	$this->view->tgl_tpm1=$datatpm[0]['tgl_tpm1'];
	$this->view->ket_tpm2=$datatpm[0]['ket_tpm2'];
	$this->view->tgl_tpm2=$datatpm[0]['tgl_tpm2'];
	$this->view->tpm_status=$datatpm[0]['tpm_status'];
}
public function listDataTpmList2($usulan_id) {  
	$cari = "  a.usulan_id =$usulan_id ";
	$datatpm=$this->tpm_serv->getListTpm4($cari );	
								
	//$this->view->usulan_id=$datatpm[0]['usulan_id'];
	$this->view->usulan_nomor=$datatpm[0]['usulan_nomor'];
	$this->view->usulan_keterangan=$datatpm[0]['usulan_keterangan'];
	$this->view->tvinstansi_kd=$datatpm[0]['tvinstansi_kd'];
	$this->view->mod_date=$datatpm[0]['mod_date'];
	$this->view->periode_text=$datatpm[0]['periode_text'];
	$this->view->d_tpm=$datatpm[0]['tpm_tgl'];
	$this->view->periode_keterangan=$datatpm[0]['periode_keterangan'];
	$this->view->n_tpm=$datatpm[0]['nama_list'];
	$this->view->n_jabatan=$datatpm[0]['n_jabatan'];
	$this->view->unitkerja=$datatpm[0]['unitkerja'];

}

public function daftartpmAction() {
	//$cari=" and a.usulan_id=b.usulan_id and tpm_status_1=3 and tpm_status_2 is not null and tpm_status_3 is not null ";	
	$cari="  a.usulan_id=b.usulan_id and tpm_status_3 = 3 "; //and tpm_status_1=3 and tpm_status_2 = 3 and tpm_status_3 = 3 ";
	//$cari = " and a.usulan_id = b.usulan_id";
	$this->view->dataPraTpm = $this->tpm_serv->getListTpm3($cari);
}
public function cetakAction() {
	//$cari = " and a.usulan_id = b.usulan_id";
	$cari = " a.usulan_id = b.usulan_id";
	$this->view->dataPraTpm = $this->tpm_serv->getListTpm3($cari);
}
public function cetakhasilAction() {
	//$cari = " and a.usulan_id = b.usulan_id";
	$cari="  a.usulan_id=b.usulan_id and tpm_status_3 = 3 "; //and tpm_status_1=3 and tpm_status_2 = 3 and tpm_status_3 = 3 ";
	//$cari = " a.usulan_id = b.usulan_id";
	$this->view->dataPraTpm = $this->tpm_serv->getListTpm3($cari);
}

public function namajabatanAction() {
	$this->view->eselonList = $this->reff_serv->getEselon(" and c_eselon in ('01','02','03','04','05','06','07','08','12','14','16')");
}
public function detilnamajabatanAction() {
	$c_eselon=$_GET['c_eselon'];
	if ($c_eselon){$cari=" and c_eselon='$c_eselon' ";}
	$this->view->nmJabatanList = $this->reff_serv->getJabatan($cari);
}



public function listcomboAction() {

if ($this->view->sektoral=='S'){
	$c_eselon_i=$_GET['eseloni'];
	$expesl1 = explode(";",$c_eselon_i);
	$c_eselon_i=$expesl1[0];
		$this->view->c_eselon_i =$_GET['eseloni'];
		$this->view->c_eselon_ii =$_GET['eselonii'];
		$this->view->c_eselon_iii =$_GET['eseloniii'];
		$this->view->c_eselon_iv =$_GET['eseloniv'];
		$this->view->c_eselon_v =$_GET['eselonv'];	
		
	if ($c_eselon_i=='03' || $c_eselon_i=='04' || $c_eselon_i=='05')
	{

			
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1'");
			$c_eselon_i=$_GET['eseloni'];
			$expesl1 = explode(";",$c_eselon_i);
			$c_eselon_i=$expesl1[0];	
			$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_level ='2' and c_lokasi_unitkerja='3' and c_eselon_i='$c_eselon_i' ");

			$c_eselon_ii=$_GET['eselonii'];
			$expesl2 = explode(";",$c_eselon_ii);
			$c_eselon_ii=$expesl2[0];
			$c_parent=$expesl2[1];


			$c_eselon_iii=$_GET['eseloniii'];
			if ($c_eselon_iii){
			$expesl3 = explode(";",$c_eselon_iii);	
			$c_eselon_iii=$expesl3[0];
			}

			$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_level ='2' and c_lokasi_unitkerja='3' and c_eselon_i='$c_eselon_i' ");
			$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and  c_level ='3' and c_lokasi_unitkerja='3' and c_eselon_i='$c_eselon_i'  and c_parent ='$c_parent'");
			$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_level ='5' and c_lokasi_unitkerja='3' and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_parent ='$c_parent'");
		
			$this->render('listcombo2');
	}
	else
	{

			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='1'");		
			$c_eselon_i=$_GET['eseloni'];
			$expesl1 = explode(";",$c_eselon_i);
			$c_eselon_i=$expesl1[0];	
			$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and trim(c_tkt_esl)='2' and c_lokasi_unitkerja='1'");	
			
			$c_eselon_ii=$_GET['eselonii'];
			$expesl2 = explode(";",$c_eselon_ii);
			$c_eselon_ii=$expesl2[0];
			$c_parent=$expesl2[1];
			$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and trim(c_tkt_esl)='3' and c_lokasi_unitkerja='1'");		


			$c_eselon_iii=$_GET['eseloniii'];
			$expesl3 = explode(";",$c_eselon_iii);	
			$c_eselon_iii=$expesl3[0];
			$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and trim(c_tkt_esl)='3' and c_lokasi_unitkerja='1'");
			$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and trim(c_tkt_esl)='4' and c_lokasi_unitkerja='1'");

			$this->render('listcombo');

	}
}
if ($this->view->sektoral=='D'){
		
		$c_eselon_i=$this->view->c_eselon_i;
		$c_eselon_ii=$this->view->c_eselon_ii;
		$c_eselon_iii=trim($this->view->c_eselon_iii);
		$c_eselon_iv=trim($this->view->c_eselon_iv);
		$c_eselon_v=$this->view->c_eselon_v;	

		if ($c_eselon_i=='03' || $c_eselon_i=='04' || $c_eselon_i=='05')
		{
			
			$expesl2 = explode(";",$_GET['eselonii']);
			$c_parent=$expesl2[1];
			$c_eselon_iix=$expesl2[0];
			
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_eselon_i='$c_eselon_i' ");		
			$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_level ='2' and c_lokasi_unitkerja='3' and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii'");
			if ($c_eselon_iii!='00'){
				$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and  c_level ='3' and c_lokasi_unitkerja='3' and c_eselon_i='$c_eselon_i'  and c_parent ='$c_parent'");
			}
			if ($c_eselon_iv!='00'){			
				$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_level ='5' and c_lokasi_unitkerja='3' and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_parent ='$c_parent'");
			}

			$this->view->c_eselon_i =$_GET['eseloni'];	
			$this->view->c_eselon_ii =$_GET['eselonii'];
			$this->view->c_eselon_iii =$_GET['eseloniii'];
			$this->view->c_eselon_iv =$_GET['eseloniv'];
			$this->view->c_eselon_v =$_GET['eselonv'];
		
			$this->render('listcombo2');
		}
		else{
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='1'");		
			$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and trim(c_tkt_esl)='2' and c_lokasi_unitkerja='1'");	
			$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and trim(c_tkt_esl)='3' and c_lokasi_unitkerja='1'");				
			$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and trim(c_tkt_esl)='4' and c_lokasi_unitkerja='1'");
		

			$this->view->c_eselon_i =$_GET['eseloni'];	
			$this->view->c_eselon_ii =$_GET['eselonii'];
			$this->view->c_eselon_iii =$_GET['eseloniii'];
			$this->view->c_eselon_iv =$_GET['eseloniv'];
			$this->view->c_eselon_v =$_GET['eselonv'];

			$this->render('listcombo');
		
		}
	
}


}


public function listnamapejabatAction() {
	$this->view->nom=$_GET['nom'];
	$this->view->par=$_GET['par'];
	$this->view->nip=$_GET['nip'];
	$nip=$_GET['nip'];
	
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 13;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
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

	if($nip){		
		$nip= str_replace("\'", "'", $nip);
		$nip= str_replace(",''s", " ", $nip);
		$nip= str_replace(",''", "", $nip);
		$nip= str_replace("''", "'", $nip);
		
		$cari .= " and i_peg_nip not in ($nip) ";
	}
	
	if ($nCol && $cCol ){$cari .= " and $cCol like '%$nCol%' ";}
$cari .= " and (c_eselon !='17' or c_eselon isnull)";	
$orderBy=" order by c_golongan desc";		
	$this->view->totalpegawaiList = $this->pegawai_serv->getPegawaiList($cari, 0, 0 ,$orderBy);		
	$this->view->PegawaiList = $this->pegawai_serv->getPegawaiList($cari, $currentPage, $numToDisplay,$orderBy );
}


function right($string){
    return substr($string,0,2);
}
function right2($string){
    return substr($string,0,3);
}	
function left($string){
    return substr($string,3,200);
}
function left2($string){
    return substr($string,7,200);
}
		
}
?>