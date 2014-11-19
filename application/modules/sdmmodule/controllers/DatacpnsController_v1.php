<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_Pendidikan_Service.php";
require_once "service/sdm/Sdm_Pelatihan_Service.php";

class Sdmmodule_DataCpnsController extends Zend_Controller_Action {

		
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
	$statuspegcari=$_GET['statuspegcari'];
	$golcari=$_GET['golcari'];
	$namacari=strtoupper($_GET['namacari']);
	$nipcari=$_GET['nipcari'];
	$this->view->statuspegcari=$_GET['statuspegcari'];
	$this->view->golcari=$_GET['golcari'];
	$this->view->namacari=$_GET['namacari'];
	$this->view->nipcari=$_GET['nipcari'];
	if ($nipcari){$cari= " and i_peg_nip like '%$nipcari%' ";}
	if ($namacari){$cari .= " and upper(n_peg) like '%$namacari%' ";}
	if ($golcari){$cari .= " and c_peg_golongan = '$golcari' ";}
	if ($statuspegcari){$cari .= " and c_peg_status = '$statuspegcari' ";}
	$orderBy=$_GET['orderBy'];
	$order=$_GET['order'];
	if (!$_GET['order']){$this->view->orderbya="asc";$this->view->orderbyb="desc";}
	else{
		if ($_GET['order']=='desc'){	$this->view->orderbya="desc";$this->view->orderbyb="asc";}
		else{$this->view->orderbya="asc";$this->view->orderbyb="desc";}
	}
	if ($_GET['orderBy']){$orderBy=" order by $orderBy $order";}
	$this->view->orderBy=$_GET['orderBy'];
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totalpegawaiList = $this->pegawai_serv->getPegawaiList($cari, 0, 0 ,$orderBy);		
		$this->view->pegawaiList = $this->pegawai_serv->getPegawaiList($cari, $currentPage, $numToDisplay,$orderBy );	
		
		$sesmenu = new Zend_Session_Namespace('sesmenu');
		$sesmenu->menu= $_GET['menu'];
		$this->view->menu= $_GET['menu'];
    }
public function cpnsAction() {
	$par=$_GET['par'];
	if ($par=='insert'){
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
	}
	else{
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$nip=$_GET['nip'];
		if (!$nip){$nip=$this->view->nip;}
		$this->listDataByKey($nip);
	}
	$this->view->statusKePegRef = $this->reff_serv->getStatusKepegawaian('');
	$this->view->nmJenjangList = $this->reff_serv->getPendidikan('');	
	$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai('');
	$this->view->eselonList = $this->reff_serv->getEselon('');
	$this->view->lokasiList = $this->reff_serv->getLokasi('');	

}

public function listcomboeseloniAction() {
	$c_lokasi_unitkerja=$_GET['c_lokasi_unitkerja_cpns'];
	$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
}	
public function listcomboeseloniiAction() {
	$c_lokasi_unitkerja=$_GET['c_lokasi_unitkerja_cpns'];
	$c_eselon_i=$_GET['c_eselon_x'];
	$c_eselon_i=substr($c_eselon_i,0,2);
	$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	

}	
public function listcomboeseloniiiAction() {
	$c_lokasi_unitkerja=$_GET['c_lokasi_unitkerja_cpns'];
	$c_eselon_ii=$_GET['c_eselon_x'];
	$c_eselon_ii=substr($c_eselon_ii,0,2);
	$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
}	
public function listcomboeselonivAction() {
	$c_lokasi_unitkerja=$_GET['c_lokasi_unitkerja_cpns'];
	$c_eselon_iii=$_GET['c_eselon_x'];
	$c_eselon_iii=substr($c_eselon_iii,0,2);
	$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_iii='$c_eselon_iii' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
}
public function listcomboeselonvAction() {
	$c_lokasi_unitkerja=$_GET['c_lokasi_unitkerja_cpns'];
	$c_eselon_iv=$_GET['c_eselon_x'];
	$c_eselon_iv=substr($c_eselon_iv,0,2);
	$this->view->eselonvList = $this->reff_serv->getTrUnitKerja(" and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
}

public function listcomboAction() {
	$this->view->lokasiList = $this->reff_serv->getLokasi('');	
	$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1'");
	$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='2'");
	$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='3'");
	$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='4'");
	$this->view->eselonvList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='5'");
}
public function maintaindataAction() {

 		if ($_POST['d_peg_lahir'])
		{
			$d_peg_lahir1=substr($_POST['d_peg_lahir'],0,2);
			$d_peg_lahir2=substr($_POST['d_peg_lahir'],3,2);
			$d_peg_lahir3=substr($_POST['d_peg_lahir'],6,4);
		}
		$d_peg_lahir=$d_peg_lahir3."-".$d_peg_lahir2."-".$d_peg_lahir1;
		if (!$_POST['d_peg_lahir']){$d_peg_lahir=null;$cektglmulai=true;}
		else{$cektgllahir=checkdate($d_peg_lahir2,$d_peg_lahir1,$d_peg_lahir3);}

 		if ($_POST['d_sk_cpns'])
		{
			$d_sk_cpns1=substr($_POST['d_sk_cpns'],0,2);
			$d_sk_cpns2=substr($_POST['d_sk_cpns'],3,2);
			$d_sk_cpns3=substr($_POST['d_sk_cpns'],6,4);
		}
		$d_sk_cpns=$d_sk_cpns3."-".$d_sk_cpns2."-".$d_sk_cpns1;
		if (!$_POST['d_sk_cpns']){$d_sk_cpns=null;$cektglmulai=true;}
		else{$ceksk=checkdate($d_sk_cpns2,$d_sk_cpns1,$d_sk_cpns3);}	

 		if ($_POST['d_tmt_cpns'])
		{
			$d_tmt_cpns1=substr($_POST['d_tmt_cpns'],0,2);
			$d_tmt_cpns2=substr($_POST['d_tmt_cpns'],3,2);
			$d_tmt_cpns3=substr($_POST['d_tmt_cpns'],6,4);
		}
		$d_tmt_cpns=$d_tmt_cpns3."-".$d_tmt_cpns2."-".$d_tmt_cpns1;
		if (!$_POST['d_tmt_cpns']){$d_tmt_cpns=null;$cektglmulai=true;}
		else{$cektmt=checkdate($d_tmt_cpns2,$d_tmt_cpns1,$d_tmt_cpns3);}		

 		if ($_POST['d_tmt_kerjacpns'])
		{
			$d_tmt_kerjacpns1=substr($_POST['d_tmt_kerjacpns'],0,2);
			$d_tmt_kerjacpns2=substr($_POST['d_tmt_kerjacpns'],3,2);
			$d_tmt_kerjacpns3=substr($_POST['d_tmt_kerjacpns'],6,4);
		}
		$d_tmt_kerjacpns=$d_tmt_kerjacpns3."-".$d_tmt_kerjacpns2."-".$d_tmt_kerjacpns1;
		if (!$_POST['d_tmt_kerjacpns']){$d_tmt_kerjacpns=null;$cektglmulai=true;}
		else{$cektmtkerja=checkdate($d_tmt_kerjacpns2,$d_tmt_kerjacpns1,$d_tmt_kerjacpns3);}	

		$c_eselon_i=$_POST['c_eselon_i_cpns'];
		if ($_POST['c_eselon_i_cpns']!=''){	$c_eselon_il=strlen($c_eselon_i); $c_eselon_i=$this->right($c_eselon_i,$c_eselon_il);}
		else {$c_eselon_i=null;}

		$c_eselon_ii=$_POST['c_eselon_ii_cpns'];
		if ($_POST['c_eselon_ii_cpns']!=''){$c_eselon_iil=strlen($c_eselon_ii); $c_eselon_ii=$this->right($c_eselon_ii, $c_eselon_iil);}
		else {$c_eselon_ii=null;}

		$c_eselon_iii=$_POST['c_eselon_iii_cpns'];
		if ($_POST['c_eselon_iii_cpns']!=''){$c_eselon_iiil=strlen($c_eselon_iii); $c_eselon_iii=$this->right($c_eselon_iii, $c_eselon_iiil);}
		else {$c_eselon_iii=null;}

		$c_eselon_iv=$_POST['c_eselon_iv_cpns'];
		if ($_POST['c_eselon_iv_cpns']!=''){$c_eselon_ivl=strlen($c_eselon_iv); $c_eselon_iv=$this->right($c_eselon_iv, $c_eselon_ivl);}
		else {$c_eselon_iv=null;}

		$c_eselon_v=$_POST['c_eselon_v_cpns'];
		if ($_POST['c_eselon_v_cpns']!=''){$c_eselon_vl=strlen($c_eselon_v); $c_eselon_v=$this->right($c_eselon_v, $c_eselon_vl);}
		else {$c_eselon_v=null;}


if (($cektgllahir==true &&  $ceksk==true && $cektmt==true && $cektmtkerja==true ) )
{


	$MaintainData = array("i_peg_nip"=>$_POST['i_peg_nip'],
						"i_peg_nip_new"=>$_POST['i_peg_nip_new'],
						"n_peg"=>$_POST['n_peg'],
						"c_eselon_cpns"=>$_POST['c_eselon_cpns'],
						"c_eselon_i_cpns"=>$c_eselon_i,
						"c_eselon_ii_cpns"=>$c_eselon_ii,
						"c_eselon_iii_cpns"=>$c_eselon_iii,
						"c_eselon_iv_cpns"=>$c_eselon_iv,
						"c_eselon_v_cpns"=>$c_eselon_v,
						"c_gol_cpns"=>$_POST['c_gol_cpns'],
						"c_jabatan_cpns"=>$_POST['c_jabatan_cpns'],
						"c_lokasi_unitkerja_cpns"=>$_POST['c_lokasi_unitkerja_cpns'],
						"c_pend_cpns"=>$_POST['c_pend_cpns'],
						"c_status_kepegawaian"=>$_POST['c_status_kepegawaian'],
						"d_peg_lahir"=>$d_peg_lahir,
						"d_sk_cpns"=>$d_sk_cpns,
						"d_tmt_cpns"=>$d_tmt_cpns,
						"d_tmt_kerjacpns"=>$d_tmt_kerjacpns,
						"i_sk_cpns"=>$_POST['i_sk_cpns'],
						"n_peg_gelarblkg"=>$_POST['n_peg_gelarblkg'],
						"n_peg_gelardepan"=>$_POST['n_peg_gelardepan'],
						"n_sk_pejabatcpns"=>$_POST['n_sk_pejabatcpns'],
						"q_fiktif_cpns_bln"=>$_POST['q_fiktif_cpns_bln']*1,
						"q_fiktif_cpns_thn"=>$_POST['q_fiktif_cpns_thn']*1,
						"q_honorer_cpns_bln"=>$_POST['q_honorer_cpns_bln']*1,
						"q_honorer_cpns_thn"=>$_POST['q_honorer_cpns_thn']*1,
						"q_mktotal_cpns_bln"=>$_POST['q_mktotal_cpns_bln']*1,
						"q_mktotal_cpns_thn"=>$_POST['q_mktotal_cpns_thn']*1,
						"q_swasta_cpns_bln"=>$_POST['q_swasta_cpns_bln']*1,
						"q_swasta_cpns_thn"=>$_POST['q_swasta_cpns_thn']*1,
						"c_peg_status"=>'2CP',
						"c_stat_aktivasi"=>'A',	
						"e_file_photo"=>'nophoto',						
						"i_entry"=>"test",
						"d_entry"=>date('Ymd'));		

	if ($_POST['proses']=='Simpan')
	{
		$hasil = $this->pegawai_serv->maintainDataCpns($MaintainData,'insert');		
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
	}
	else if ($_POST['proses']=='Hapus')
	{
		$hasil = $this->pegawai_serv->maintainDataCpns($MaintainData,'delete');		
		$this->view->par="Hapus";
		$this->view->jdl="Menghapus ";
		$par="Menghapus";
	}	
	else
	{
		$hasil = $this->pegawai_serv->maintainDataCpns($MaintainData,'update');
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";		
	}

	$this->view->statusKePegRef = $this->reff_serv->getStatusKepegawaian('');
	$this->view->nmJenjangList = $this->reff_serv->getPendidikan('');	
	$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai('');
	$this->view->eselonList = $this->reff_serv->getEselon('');
	$this->view->lokasiList = $this->reff_serv->getLokasi('');	
	$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1'");
	$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='2'");
	$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='3'");
	$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='4'");
	$this->view->eselonvList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='5'");

	$this->listDataByKey($_POST['i_peg_nip']) ;
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
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	if ($_POST['proses']=='Hapus'){$this->render('listpegawai');}
	else{$this->render('cpns');}
}

public function listDataByKey($nip) {  
	$cari = " and i_peg_nip ='$nip' ";
	$datapegawai=$this->pegawai_serv->getPegawaiListByNip($cari );
	$sespeg = new Zend_Session_Namespace('sespeg');
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

	$this->view->i_peg_nip=$datapegawai[0]['i_peg_nip'];
	$this->view->i_peg_nip_new=$datapegawai[0]['i_peg_nip_new'];	
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
	$this->view->c_eselon_i_cpns=trim($datapegawai[0]['c_eselon_i_cpns']);
	$this->view->c_eselon_ii_cpns=trim($datapegawai[0]['c_eselon_ii_cpns']);
	$this->view->c_eselon_iii_cpns=trim($datapegawai[0]['c_eselon_iii_cpns']);
	$this->view->c_eselon_iv_cpns=trim($datapegawai[0]['c_eselon_iv_cpns']);
	$this->view->c_eselon_v_cpns=trim($datapegawai[0]['c_eselon_v_cpns']);
	$this->view->q_fiktif_cpns_thn=$datapegawai[0]['q_fiktif_cpns_thn'];
	$this->view->q_fiktif_cpns_bln=$datapegawai[0]['q_fiktif_cpns_bln'];
	$this->view->q_honorer_cpns_thn=$datapegawai[0]['q_honorer_cpns_thn'];
	$this->view->q_honorer_cpns_bln=$datapegawai[0]['q_honorer_cpns_bln'];
	$this->view->q_swasta_cpns_thn=$datapegawai[0]['q_swasta_cpns_thn'];
	$this->view->q_swasta_cpns_bln=$datapegawai[0]['q_swasta_cpns_bln'];
	$this->view->q_mktotal_cpns_thn=$datapegawai[0]['q_mktotal_cpns_thn'];
	$this->view->q_mktotal_cpns_bln=$datapegawai[0]['q_mktotal_cpns_bln'];
	$this->view->c_pend_cpns=$datapegawai[0]['c_pend_cpns'];
	$this->view->c_jabatan_cpns=$datapegawai[0]['c_jabatan_cpns'];
	$this->view->n_jabatan_cpns=$datapegawai[0]['n_jabatan_cpns'];
	$this->view->c_status_kepegawaian=$datapegawai[0]['c_status_kepegawaian'];
	$this->view->d_tmt_kerjacpns=$datapegawai[0]['d_tmt_kerja'];
	$this->view->d_peg_lahir=$datapegawai[0]['d_peg_lahir'];
	$this->view->e_file_photo=$datapegawai[0]['e_file_photo'];
	
	$c_lokasi_unitkerja=trim($datapegawai[0]['c_lokasi_unitkerja_cpns']);
	$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
	$c_eselon_i=trim($datapegawai[0]['c_eselon_i_cpns']);
	$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	

	$c_eselon_ii=trim($datapegawai[0]['c_eselon_ii_cpns']);
	$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
	
	$c_eselon_iii=trim($datapegawai[0]['c_eselon_iii_cpns']);
	$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_iii='$c_eselon_iii' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

	$c_eselon_iv=trim($datapegawai[0]['c_eselon_iv_cpns']);
	$this->view->eselonvList = $this->reff_serv->getTrUnitKerja(" and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
	
	$this->view->ceseloncpns=substr($datapegawai[0]['c_eselon_cpns'],1,1);
}
function right($string){
    return substr($string,0,2);
}

	
}
?>