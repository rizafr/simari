<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_Pendidikan_Service.php";
require_once "service/sdm/Sdm_Pelatihan_Service.php";

class Sdmmodule_DatapegawaiController extends Zend_Controller_Action {

		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu');
		$this->view->photoPath = $registry->get('photoPath');
		 
		$this->reff_serv = sdm_refferensi_Service::getInstance();
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->pendidikan_serv = Sdm_Pendidikan_Service::getInstance();
		$this->pelatihan_serv = Sdm_Pelatihan_Service::getInstance();	
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

		$sesmenu = new Zend_Session_Namespace('sesmenu');
		$this->view->menu= $sesmenu->menu;
		
		
		//sesion dari login
		$ssologin = new Zend_Session_Namespace('ssologin');
		$this->view->userid=$ssologin->userid;
		$this->view->i_peg_nip=$ssologin->i_peg_nip;
		$this->view->i_peg_nip_new=$ssologin->i_peg_nip_new;
		$this->view->n_peg=$ssologin->n_peg;
		$this->view->c_jabatan=$ssologin->c_jabatan;
		$this->view->c_eselon_i=$ssologin->c_eselon_i;
		$this->view->c_lokasi_unitkerja=$ssologin->c_lokasi_unitkerja;
		$this->view->c_izin=$ssologin->c_izin[0]['c_izin'];
		if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Kelola ";}
		else{$this->view->jdl="Melihat ";}
    }
	
    public function indexAction() {
    }
	public function pegawaijsAction() 
	{
		header('content-type : text/javascript');
		$this->render('pegawaijs');
	}	
	
    public function listcombosatkerAction() {
	$i_org_parent=$_GET['i_org_parent'];
	$cari=" and i_orgb_parent ='$i_org_parent' ";
	$this->view->unitKList = $this->pegawai_serv->getUnitKerjaList($cari);	   
    }
    public function listcombokabupatenAction() {
	$c_propinsi=$_GET['c_propinsi'];
	$this->view->par=$_GET['target'];
	$carikabupaten=" and c_propinsi ='$c_propinsi' ";
	$this->view->kabupatenList = $this->reff_serv->getKabupatenListAll($carikabupaten); 
    }	
	
public function listpegawaiAction() {    
	$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai($cari);
	$this->view->statusPegRef = $this->reff_serv->getStatusPegawai($cari);
	// $statuspegcari=$_GET['statuspegcari'];
	// $golcari=$_GET['golcari'];
	// $namacari=strtoupper($_GET['namacari']);
	// $nipcari=$_GET['nipcari'];
	// $this->view->statuspegcari=$_GET['statuspegcari'];
	// $this->view->golcari=$_GET['golcari'];
	// $this->view->namacari=$_GET['namacari'];
	// $this->view->nipcari=$_GET['nipcari'];
	// if ($nipcari){$cari= " and i_peg_nip like '%$nipcari%' ";}
	// if ($namacari){$cari .= " and upper(n_peg) like '%$namacari%' ";}
	// if ($golcari){$cari .= " and c_peg_golongan = '$golcari' ";}
	// if ($statuspegcari){$cari .= " and c_peg_status = '$statuspegcari' ";}
	
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


	
	if ($nCol && $cCol ){
	if ($cCol=='i_peg_nip'){$cari .= " and (i_peg_nip like '%$nCol%' or  i_peg_nip_new like '%$nCol%')";}else{	$cari .= " and upper($cCol) like '%$nCol%' ";}
	}
	
	//if ($cCol=='unitkerja'){
	if ($_POST['cCol']=='unitkerja' || $_GET['cCol']=='unitkerja'){
		
		if ($_POST['c_lokasi_unitkerja']){
		$c_lokasi_unitkerja=trim($_POST['c_lokasi_unitkerja']);
		}
		else{
		$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);
		}
		$cari .= " and c_lokasi_unitkerja='$c_lokasi_unitkerja'";
		
		if ($_POST['c_eselon_i']){$c_eselon_i=$_POST['c_eselon_i'];}
		else{$c_eselon_i=$_GET['c_eselon_i'];}
		
		$c_eselon_i=substr($c_eselon_i,0,2);
		if ($_POST['c_eselon_i'] || $_GET['c_eselon_i']){$cari .= " and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'";}

		if ($_POST['c_eselon_ii']){$c_eselon_ii=$_POST['c_eselon_ii'];}
		else{$c_eselon_ii=$_GET['c_eselon_ii'];}
		
		//$c_eselon_ii=$_POST['c_eselon_ii'];
		$c_eselon_ii=substr($c_eselon_ii,0,2);
		if ($_POST['c_eselon_ii'] || $_GET['c_eselon_ii']){$cari .= " and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and c_lokasi_unitkerja='$c_lokasi_unitkerja'";}
		
		if ($_POST['c_eselon_iii']){$c_eselon_i=$_POST['c_eselon_iii'];}
		else{$c_eselon_iii=$_GET['c_eselon_iii'];}
		
		//$c_eselon_iii=$_POST['c_eselon_iii'];
		$c_eselon_iii=substr($c_eselon_iii,0,2);
		if ($_POST['c_eselon_iii'] || $_GET['c_eselon_iii']){$cari .= " and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_lokasi_unitkerja='$c_lokasi_unitkerja'";}

		if ($_POST['c_eselon_iv']){$c_eselon_iv=$_POST['c_eselon_iv'];}
		else{$c_eselon_iv=$_GET['c_eselon_iv'];}
		
		//$c_eselon_iv=$_POST['c_eselon_iv'];
		$c_eselon_iv=substr($c_eselon_iv,0,2);
		if ($_POST['c_eselon_iv'] || $_GET['c_eselon_iv']){$cari .= " and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_lokasi_unitkerja='$c_lokasi_unitkerja'";}


		if ($_POST['cCol']){$this->view->cCol = $_POST['cCol'];}
		else{$this->view->cCol = $_GET['cCol'];}
		
		
		$this->view->c_eselon_i = $c_eselon_i;
		

	$this->view->c_lokasi_unitkerja=$c_lokasi_unitkerja;
	$this->view->lokasiList = $this->reff_serv->getLokasi('');
	$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	
	
	
	if ($_POST['c_eselon_i']){$this->view->c_eselon_i=$_POST['c_eselon_i'];}
	else{$this->view->c_eselon_i=$_GET['c_eselon_i'];}	
	//$this->view->c_eselon_i =$_POST['c_eselon_i'];
	$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	

	if ($_POST['c_eselon_ii']){$this->view->c_eselon_ii=$_POST['c_eselon_ii'];}
	else{$this->view->c_eselon_ii=$_GET['c_eselon_ii'];}
		
	//$this->view->c_eselon_ii =$_POST['c_eselon_ii'];
	$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");


	if ($_POST['c_eselon_iii']){$this->view->c_eselon_iii=$_POST['c_eselon_iii'];}
	else{$this->view->c_eselon_iii=$_GET['c_eselon_iii'];}
		
	//$this->view->c_eselon_iii =$_POST['c_eselon_iii'];
	$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

	if ($_POST['c_eselon_iv']){$this->view->c_eselon_iv=$_POST['c_eselon_iv'];}
	else{$this->view->c_eselon_iv=$_GET['c_eselon_iv'];}
		
	//$this->view->c_eselon_iv =$_POST['c_eselon_iv'];
	$this->view->eselonvList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

		     

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
		{$currentPage = 1;}
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		
//
//$cari= $cari." and (c_eselon !='17' or c_eselon isnull)"; 
if ($this->view->c_izin=='000000' || $this->view->c_izin=='000001' || $this->view->c_izin=='000002' || $this->view->c_izin=='000003'){
	$c_lokasi_unitkerja=trim($this->view->c_lokasi_unitkerja);
	$c_eselon_i=trim($this->view->c_eselon_i);
	//$cari= $cari." and (c_lokasi_unitkerja='$c_lokasi_unitkerja' or c_lokasi_unitkerja_cpns='$c_lokasi_unitkerja')  and (c_eselon_i='$c_eselon_i' or c_eselon_i_cpns='$c_eselon_i') ";
	//$cari= $cari." and (c_lokasi_unitkerja_cpns='$c_lokasi_unitkerja')  and (c_eselon_i_cpns='$c_eselon_i') ";
}

		$this->view->totalpegawaiList = $this->pegawai_serv->getPegawaiList($cari, 0, 0 ,$orderBy);		
		$this->view->pegawaiList = $this->pegawai_serv->getPegawaiList($cari, $currentPage, $numToDisplay,$orderBy );	
		
		$sesmenu = new Zend_Session_Namespace('sesmenu');
		$sesmenu->menu= $_GET['menu'];
		$this->view->menu= $_GET['menu'];
    }
public function toplinkAction() {

}
public function pegawaiAction() {
	$par=$_GET['par'];

	//$this->view->unitOList = $this->pegawai_serv->getUnitOrgList();
	//$this->view->unitKList = $this->pegawai_serv->getUnitKerjaList($carisatker);
	$this->view->propinsiList = $this->reff_serv->getPropinsiListAll();
	$this->view->kabupatenList = $this->reff_serv->getKabupatenListAll($carikabupaten);
	//$this->view->statusPegList = $this->reff_serv->getStatusPegListAll();
	$this->view->agamaList = $this->reff_serv->getAgamaListAll();
	$this->view->kabupatenListLahir = $this->reff_serv->getKabupatenListAll($carikabupatenlahir);
	$this->view->bankList = $this->reff_serv->getTrBank('');
	$this->view->menulink="datadiri";
	
	if ($par=='insert'){
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
	}
	else{
	
		$this->view->par="Ubah";
		//$this->view->jdl="Merubah ";
		if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Merubah ";}
		else{$this->view->jdl="Melihat ";}			
		$nip=$_GET['nip'];
		if (!$nip){$nip=$this->view->nip;}
		$this->listDataByKey($nip);

		if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Merubah ";}
		else{$this->view->jdl="Melihat ";$this->render('pegawaiview');}			
	}	

}
	
public function listDataByKey($nip) {  
	$cari = " and i_peg_nip ='$nip' ";
	$datapegawai=$this->pegawai_serv->getPegawaiListByNip($cari );
	$sespeg = new Zend_Session_Namespace('sespeg');
	$sespeg->nama= $datapegawai[0]['n_peg'];
	$sespeg->nip= $datapegawai[0]['i_peg_nip'];
	$sespeg->golongan= $datapegawai[0]['c_peg_golongan'];
	$sespeg->pangkat= $datapegawai[0]['n_peg_pangkat'];
	
	$this->view->nama= $datapegawai[0]['n_peg'];
	$this->view->nip= $datapegawai[0]['i_peg_nip'];
	$this->view->golongan= $datapegawai[0]['c_peg_golongan'];
	$this->view->pangkat= $datapegawai[0]['n_peg_pangkat'];	

	
	$this->view->i_peg_nip_new=$datapegawai[0]['i_peg_nip_new'];
	if ($datapegawai[0]['i_peg_nip_new']==$datapegawai[0]['i_peg_nip']){$this->view->i_peg_nip="";}
	else{$this->view->i_peg_nip=$datapegawai[0]['i_peg_nip'];}
	$this->view->n_peg=$datapegawai[0]['n_peg'];
	$this->view->n_peg_gelardepan=$datapegawai[0]['n_peg_gelardepan'];
	$this->view->n_peg_gelarblkg=$datapegawai[0]['n_peg_gelarblkg'];
	$this->view->c_peg_jeniskelamin=$datapegawai[0]['c_peg_jeniskelamin'];
	$this->view->c_agama=$datapegawai[0]['c_agama'];
	$this->view->c_golongan_darah=trim($datapegawai[0]['c_golongan_darah']);
	$this->view->c_peg_statusnikah=trim($datapegawai[0]['c_peg_statusnikah']);
	$this->view->n_peg_hobi=$datapegawai[0]['n_peg_hobi'];
	$this->view->d_peg_lahir=$datapegawai[0]['d_peg_lahir'];
	$this->view->c_peg_propinsi_lahir=trim($datapegawai[0]['c_peg_propinsi_lahir']);
	$this->view->a_peg_kota_lahir=trim($datapegawai[0]['a_peg_kota_lahir']);
	$this->view->a_peg_kelurahan_lahir=trim($datapegawai[0]['a_peg_kelurahan_lahir']);
	$this->view->a_peg_kecamatan_lahir=trim($datapegawai[0]['a_peg_kecamatan_lahir']);
	$this->view->q_peg_tinggibdn=$datapegawai[0]['q_peg_tinggibdn'];
	$this->view->q_peg_beratbdn=$datapegawai[0]['q_peg_beratbdn'];
	$this->view->n_peg_rambut=$datapegawai[0]['n_peg_rambut'];
	$this->view->n_peg_btkmuka=$datapegawai[0]['n_peg_btkmuka'];
	$this->view->n_peg_warnakulit=$datapegawai[0]['n_peg_warnakulit'];
	$this->view->n_peg_cirikhas=$datapegawai[0]['n_peg_cirikhas'];
	$this->view->a_peg_rumah=$datapegawai[0]['a_peg_rumah'];
	$this->view->a_peg_rt=$datapegawai[0]['a_peg_rt'];
	$this->view->a_peg_rw=$datapegawai[0]['a_peg_rw'];
	$this->view->a_peg_kelurahan=$datapegawai[0]['a_peg_kelurahan'];
	$this->view->a_peg_kecamatan=$datapegawai[0]['a_peg_kecamatan'];
	$this->view->a_peg_kota=$datapegawai[0]['a_peg_kota'];
	$this->view->a_peg_propinsi=$datapegawai[0]['a_peg_propinsi'];
	$this->view->a_peg_kodepos=$datapegawai[0]['a_peg_kodepos'];
	$this->view->i_peg_telponrumah=$datapegawai[0]['i_peg_telponrumah'];
	$this->view->i_peg_telponhp=$datapegawai[0]['i_peg_telponhp'];
	$this->view->i_peg_karpeg=$datapegawai[0]['i_peg_karpeg'];
	$this->view->i_peg_karis=$datapegawai[0]['i_peg_karis'];
	$this->view->i_peg_taspen=$datapegawai[0]['i_peg_taspen'];
	$this->view->i_peg_korpri=$datapegawai[0]['i_peg_korpri'];
	$this->view->i_peg_ktp=$datapegawai[0]['i_peg_ktp'];
	$this->view->i_peg_askes=$datapegawai[0]['i_peg_askes'];
	$this->view->e_file_photo=$datapegawai[0]['e_file_photo'];
	$this->view->c_stat_aktivasi=$datapegawai[0]['c_stat_aktivasi'];
	$this->view->i_peg_npwp=$datapegawai[0]['i_peg_npwp'];
	$this->view->i_peg_rekening=$datapegawai[0]['i_peg_rekening'];
	$this->view->c_peg_bank=$datapegawai[0]['c_peg_bank'];
	$this->view->c_peg_jeniskelamin=$datapegawai[0]['c_peg_jeniskelamin'];
					
	$this->view->i_entry=$datapegawai[0]['i_entry'];
	$this->view->d_entry=$datapegawai[0]['d_entry'];

}

public function hapusdataAction() {
 	$i_peg_nip=$_GET['nip'];
	$i_entry=$this->view->userid;
	$hasil = $this->pegawai_serv->maintainHapusData($i_peg_nip,$i_entry);

	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;

$cari= $cari." and (c_eselon !='17' or c_eselon isnull)"; 
if ($this->view->c_izin=='000000' || $this->view->c_izin=='000001' || $this->view->c_izin=='000002'){
	$c_lokasi_unitkerja=trim($this->view->c_lokasi_unitkerja);
	$c_eselon_i=trim($this->view->c_eselon_i);
	$cari= $cari." and (c_lokasi_unitkerja='$c_lokasi_unitkerja' or c_lokasi_unitkerja_cpns='$c_lokasi_unitkerja')  and (c_eselon_i='$c_eselon_i' or c_eselon_i_cpns='$c_eselon_i') ";
}
if ($this->view->c_izin=='000003'){
	$c_lokasi_unitkerja=trim($this->view->c_lokasi_unitkerja);
	$c_eselon_i=trim($this->view->c_eselon_i);
}		
		$this->view->totalpegawaiList = $this->pegawai_serv->getPegawaiList($cari, 0, 0 ,$orderBy);		
		$this->view->pegawaiList = $this->pegawai_serv->getPegawaiList($cari, $currentPage, $numToDisplay,$orderBy );
	
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;		
	$this->render('listpegawai');
}

public function maintaindataAction() {

$i_peg_telponrumah=null;
if (!$_POST['i_peg_telponrumah1'])
{$i_peg_telponrumah1="000";}
if (!$_POST['i_peg_telponrumah2']){
$i_peg_telponrumah=$i_peg_telponrumah1."-".$_POST['i_peg_telponrumah2'];
}
else{$i_peg_telponrumah=$_POST['i_peg_telponrumah1']."-".$_POST['i_peg_telponrumah2'];}


$d_peg_lahir=null;
if ($_POST['d_peg_lahir']){
	$d_peg_lahir= substr($_POST['d_peg_lahir'],6,4)."-".substr($_POST['d_peg_lahir'],3,2)."-".substr($_POST['d_peg_lahir'],0,2);
}

$i_peg_npwp=$_POST['i_peg_npwp1'].".".$_POST['i_peg_npwp2'].".".$_POST['i_peg_npwp3'].".".$_POST['i_peg_npwp4']."-".$_POST['i_peg_npwp5'].".".$_POST['i_peg_npwp6'];

	$MaintainData = array("i_peg_nip"=>$_POST['i_peg_nip'],
							"i_peg_nip_new"=>$_POST['i_peg_nip_new'],
							"n_peg"=>$_POST['n_peg'],
							"n_peg_gelardepan"=>$_POST['n_peg_gelardepan'],
							"n_peg_gelarblkg"=>$_POST['n_peg_gelarblkg'],
							"c_peg_jeniskelamin"=>$_POST['c_peg_jeniskelamin'],
							"c_agama"=>$_POST['c_agama'],
							"c_golongan_darah"=>$_POST['c_golongan_darah'],
							"c_peg_statusnikah"=>$_POST['c_peg_statusnikah'],
							"n_peg_hobi"=>$_POST['n_peg_hobi'],
							"d_peg_lahir"=>$d_peg_lahir,
							"c_peg_propinsi_lahir"=>$_POST['c_peg_propinsi_lahir'],
							"a_peg_kota_lahir"=>$_POST['a_peg_kota_lahir'],
							"a_peg_kelurahan_lahir"=>$_POST['a_peg_kelurahan_lahir'],
							"a_peg_kecamatan_lahir"=>$_POST['a_peg_kecamatan_lahir'],
							"q_peg_tinggibdn"=>$_POST['q_peg_tinggibdn']*1,
							"q_peg_beratbdn"=>$_POST['q_peg_beratbdn']*1,
							"n_peg_rambut"=>$_POST['n_peg_rambut'],
							"n_peg_btkmuka"=>$_POST['n_peg_btkmuka'],
							"n_peg_warnakulit"=>$_POST['n_peg_warnakulit'],
							"n_peg_cirikhas"=>$_POST['n_peg_cirikhas'],
							"a_peg_rumah"=>$_POST['a_peg_rumah'],
							"a_peg_rt"=>$_POST['a_peg_rt'],
							"a_peg_rw"=>$_POST['a_peg_rw'],
							"a_peg_kelurahan"=>$_POST['a_peg_kelurahan'],
							"a_peg_kecamatan"=>$_POST['a_peg_kecamatan'],
							"a_peg_kota"=>$_POST['a_peg_kota'],
							"a_peg_propinsi"=>$_POST['a_peg_propinsi'],
							"a_peg_kodepos"=>$_POST['a_peg_kodepos'],
							"i_peg_telponrumah"=>$i_peg_telponrumah,
							"i_peg_telponhp"=>$_POST['i_peg_telponhp'],
							"i_peg_karpeg"=>$_POST['i_peg_karpeg'],
							"i_peg_karis"=>$_POST['i_peg_karis'],
							"i_peg_taspen"=>$_POST['i_peg_taspen'],
							"i_peg_korpri"=>$_POST['i_peg_korpri'],
							"i_peg_ktp"=>$_POST['i_peg_ktp'],
							"i_peg_askes"=>$_POST['i_peg_askes'],
							"i_peg_npwp"=>$i_peg_npwp,
							"i_peg_rekening"=>$_POST['i_peg_rekening']*1,
							"c_peg_bank"=>$_POST['c_peg_bank'],
							"c_stat_aktivasi"=>"A",
							"i_entry"=>$this->view->userid,
							"d_entry"=>date("Y-m-d"));

if ($_POST['i_peg_nip_new']){						
	if ($_POST['proses']=='Simpan')
	{
		$hasil = $this->pegawai_serv->maintainData($MaintainData,'insert');		
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
	}		
	else
	{
		$hasil = $this->pegawai_serv->maintainData($MaintainData,'update');
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";
		$this->listDataByKey($_POST['i_peg_nip']) ;
		//$this->view->unitOList = $this->pegawai_serv->getUnitOrgList();
		//$this->view->unitKList = $this->pegawai_serv->getUnitKerjaList($carisatker);
		$this->view->propinsiList = $this->reff_serv->getPropinsiListAll();
		$c_propinsi=trim($this->view->a_peg_propinsi);
		$carikabupaten=" and c_propinsi ='$c_propinsi' ";
		$this->view->kabupatenList = $this->reff_serv->getKabupatenListAll($carikabupaten);
		$c_propinsilahir=trim($this->view->c_peg_propinsi_lahir);
		$carikabupatenlahir=" and c_propinsi ='$c_propinsilahir' ";
		$this->view->kabupatenListLahir = $this->reff_serv->getKabupatenListAll($carikabupatenlahir);
		//$this->view->statusPegList = $this->pegawai_serv->getStatusPegListAll();
		
		$this->view->agamaList = $this->reff_serv->getAgamaListAll();
		$this->view->bankList = $this->reff_serv->getTrBank('');
		
	}
}
else{ $hasil="gagal";}	


	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;
	$this->render('pegawai');							
}
	  
 
     public function pelatihanAction() {    
	$this->view->latihList = $this->pelatihan_serv->getPelatihan($cari);	
    }	
     public function pelatihantambahAction() {    
	$this->view->par="Simpan";
	$this->view->jdl="Menambah ";
    }		
      public function pelatihanupdateAction() { 

	$datalatih = $this->pelatihan_serv->getPelatihan($cari);	  
	$this->view->par="Ubah";
	$this->view->jdl="Merubah ";
	$this->render('pelatihantambah');	
    }	

     public function seminarAction() {    

    }	
     public function seminartambahAction() {    
	$this->view->par="Simpan";
	$this->view->jdl="Menambah ";
    }		
      public function seminarupdateAction() {    
	$this->view->par="Ubah";
	$this->view->jdl="Merubah ";
	$this->render('seminartambah');	
    }	
     public function kepangkatanAction() {    

    }	
     public function kepangkatantambahAction() {    
	$this->view->par="Simpan";
	$this->view->jdl="Menambah ";
    }		
      public function kepangkatanupdateAction() {    
	$this->view->par="Ubah";
	$this->view->jdl="Merubah ";
	$this->render('kepangkatantambah');	
    }		
     public function jabatanAction() {    

    }	
     public function jabatantambahAction() {    
	$this->view->par="Simpan";
	$this->view->jdl="Menambah ";
    }		
      public function jabatanupdateAction() {    
	$this->view->par="Ubah";
	$this->view->jdl="Merubah ";
	$this->render('jabatantambah');	
    }		
     public function sertifikasiAction() {    

    }	
     public function sertifikasitambahAction() {    
	$this->view->par="Simpan";
	$this->view->jdl="Menambah ";
    }		
      public function sertifikasiupdateAction() {    
	$this->view->par="Ubah";
	$this->view->jdl="Merubah ";
	$this->render('sertifikasitambah');	
    }	

	
     public function organisasiAction() {    

    }	
     public function organisasitambahAction() {    
	$this->view->par="Simpan";
	$this->view->jdl="Menambah ";
    }		
      public function organisasiupdateAction() {    
	$this->view->par="Ubah";
	$this->view->jdl="Merubah ";
	$this->render('organisasitambah');	
    }	
     public function penghargaanAction() {    

    }	
     public function penghargaantambahAction() {    
	$this->view->par="Simpan";
	$this->view->jdl="Menambah ";
    }		
      public function penghargaanupdateAction() {    
	$this->view->par="Ubah";
	$this->view->jdl="Merubah ";
	$this->render('penghargaantambah');
    }		
     public function hukumanAction() {    

    }	
     public function hukumantambahAction() {    
	$this->view->par="Simpan";
	$this->view->jdl="Menambah ";
    }		
      public function hukumanupdateAction() {    
	$this->view->par="Ubah";
	$this->view->jdl="Merubah ";
	$this->render('hukumantambah');
    }	
	
     public function luarnegeriAction() {    

    }	
     public function luarnegeritambahAction() {    
	$this->view->par="Simpan";
	$this->view->jdl="Menambah ";
    }		
      public function luarnegeriupdateAction() {    
	$this->view->par="Ubah";
	$this->view->jdl="Merubah ";
	$this->render('luarnegeritambah');
    }
     public function kesehatanAction() {    

    }	
     public function kesehatantambahAction() {    
	$this->view->par="Simpan";
	$this->view->jdl="Menambah ";
    }		
      public function kesehatanupdateAction() {    
	$this->view->par="Ubah";
	$this->view->jdl="Merubah ";
	$this->render('kesehatantambah');
    }	
     public function keluargaAction() {    

    }	
     public function keluargatambahAction() {    
	$this->view->par="Simpan";
	$this->view->jdl="Menambah ";
    }		
      public function keluargaupdateAction() {    
	$this->view->par="Ubah";
	$this->view->jdl="Merubah ";
	$this->render('keluargatambah');
    }	
     public function kerabatAction() {    

    }	
     public function kerabattambahAction() {    
	$this->view->par="Simpan";
	$this->view->jdl="Menambah ";
    }		
      public function kerabatupdateAction() {    
	$this->view->par="Ubah";
	$this->view->jdl="Merubah ";
	$this->render('kerabattambah');
    }	
     public function alamatAction() {    

    }	
     public function alamattambahAction() {    
	$this->view->par="Simpan";
	$this->view->jdl="Menambah ";
    }		
      public function alamatupdateAction() {    
	$this->view->par="Ubah";
	$this->view->jdl="Merubah ";
	$this->render('alamattambah');
    }	
      public function ambilfotoAction() {    
	$this->view->par="Simpan";
	$this->view->jdl="Merubah ";
    }		

public function listcomboAction() {

	$jabatanlengkap="";
if ($_GET['c_lokasi_unitkerja']=='1' || !$_GET['c_lokasi_unitkerja']){		

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
	
	
	
	$this->render('listcombo2');
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
	
}	
	
}

public function listtextAction() {

}

function right($string){
    return substr($string,0,2);
}
function left($string){
    return substr($string,3,200);
}	
}
?>