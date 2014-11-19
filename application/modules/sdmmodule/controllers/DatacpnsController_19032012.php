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

		$ssologin = new Zend_Session_Namespace('ssologin');
		$this->view->userid=$ssologin->userid;
		//$this->view->i_peg_nip=$ssologin->i_peg_nip;
		//$this->view->i_peg_nip_new=$ssologin->i_peg_nip_new;
		//$this->view->n_peg=$ssologin->n_peg;
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
	public function nipAction() {

}
	public function nrpAction() {

}	
    public function listgolonganAction() {
	$c_status_kepegawaian=$_GET['c_status_kepegawaian'];
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
		
$cari= $cari." and (c_eselon !='17' or c_eselon isnull)"; 
if ($this->view->c_izin=='000000' || $this->view->c_izin=='000001' || $this->view->c_izin=='000002' || $this->view->c_izin=='000003'){
	$c_lokasi_unitkerja=trim($this->view->c_lokasi_unitkerja);
	$c_eselon_i=trim($this->view->c_eselon_i);
	//$cari= $cari." and (c_lokasi_unitkerja='$c_lokasi_unitkerja' or c_lokasi_unitkerja_cpns='$c_lokasi_unitkerja')  and (c_eselon_i='$c_eselon_i' or c_eselon_i_cpns='$c_eselon_i') ";
}
		
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
		//$this->view->c_lokasi_unitkerja_cpns='1';
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
	//$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai('');
	//$cariesl= " and c_eselon='15'";
	$this->view->eselonList = $this->reff_serv->getEselon($cariesl);
	$this->view->lokasiList = $this->reff_serv->getLokasi('');	

}


public function listcomboAction() {

if ($_GET['c_lokasi_unitkerja_cpns']=='1'){
	$jabatanlengkap="";
	$this->view->c_lokasi_unitkerja_cpns=trim($_GET['c_lokasi_unitkerja_cpns']);
	$this->view->lokasiList = $this->reff_serv->getLokasi('');

	
	$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja_cpns']);
	$this->view->c_eselon_i_cpns =trim($_GET['eseloni']);
	$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

	
	$c_eselon_i=$_GET['eseloni'];
	$c_eselon_i=substr($c_eselon_i,0,2);
	$this->view->c_eselon_ii_cpns =trim($_GET['eselonii']);
	$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
	if ($_GET['eseloni']){$nesl1=$this->left($_GET['eseloni']); $nesl1=",".$nesl1;}
	
	$c_eselon_ii=$_GET['eselonii'];
	$c_eselon_ii=substr($c_eselon_ii,0,3);
	$this->view->c_eselon_iii_cpns =trim($_GET['eseloniii']);
	$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	if ($_GET['eselonii']){$nesl2=$this->left($_GET['eselonii']); $nesl2=",".$nesl2;}
	
	$c_eselon_iii=$_GET['eseloniii'];
	$c_eselon_iii=substr($c_eselon_iii,0,2);
	$this->view->c_eselon_iv_cpns =trim($_GET['eseloniv']);
	$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	if ($_GET['eseloniii']){$nesl3=$this->left($_GET['eseloniii']); $nesl3=",".$nesl3;}
	
	$c_eselon_iv=$_GET['eseloniv'];
	$c_eselon_iv=substr($c_eselon_iv,0,2);	
	$this->view->eselonvList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii'  and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	if ($_GET['eseloniv']){$nesl4=$this->left($_GET['eseloniv']); $nesl4=",".$nesl4;}
	
	$jabatanlengkap=$_GET['n_jabatan'].", pada ".$nesl4.$nesl3.$nesl2.$nesl1;
	$this->view->jabat_lengkap=$jabatanlengkap;
}
else{

	

	$this->view->c_lokasi_unitkerja_cpns=trim($_GET['c_lokasi_unitkerja_cpns']);
	$this->view->lokasiList = $this->reff_serv->getLokasi('');	
	$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja_cpns']);
	$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

	
	$c_eselon_i=$_GET['eseloni'];
	$expesl1 = explode(";",$c_eselon_i);
	$c_eselon_i=$expesl1[0];
	$this->view->c_eselon_i_cpns =trim($_GET['eseloni']);
	

	$c_eselon_ii=$_GET['eselonii'];
	$expesl2 = explode(";",$c_eselon_ii);
	$c_eselon_ii=$expesl2[0];
	$c_parent=$expesl2[1];
	$this->view->c_eselon_ii_cpns =trim($_GET['eselonii']);
	
	
	$c_eselon_iii=$_GET['eseloniii'];
	if ($c_eselon_iii){
	$expesl3 = explode(";",$c_eselon_iii);	
	$c_eselon_ii=$expesl3[0];
	$this->view->c_eselon_iii_cpns =trim($_GET['eseloniii']);	
	}


	
	$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_iii = '00'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child = '00' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	if ($_GET['eseloni']){$nesl1=$expesl1[1]; $nesl1=$nesl1;}	

	$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child <> '00' and c_parent ='$c_parent'  and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	if ($_GET['eselonii']){$nesl2=$expesl2[2]; $nesl2=$nesl2.",";}	

	$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_iii <> '00' and c_eselon_ii='$c_eselon_ii'  and c_lokasi_unitkerja='$c_lokasi_unitkerja' ");
	if ($_GET['eseloniii']){$nesl3=$expesl3[3]; $nesl3=$nesl3.",";}


	$this->view->c_eselon_iv_cpns =trim($_GET['eseloniv']);
	if ($_GET['eseloniv']){$nesl4=$this->left($_GET['eseloniv']); $nesl4=$nesl4.",";}	
	$jabatanlengkap=$nesl4.$nesl3.$nesl2.$nesl1;
	$this->view->jabat_lengkap=$jabatanlengkap;
	$this->view->c_eselon=$_GET['c_eselon'];
	$this->view->eselonList = $this->reff_serv->getEselon('');		
	

	$this->render('listcombo2');
}
	

}
public function maintaindataAction() {

	if (!$_POST['i_peg_nip']){$i_peg_nip=$_POST['i_peg_nip_new'];}
	else{$i_peg_nip=$_POST['i_peg_nip'];}
	$cari = " and i_peg_nip ='$i_peg_nip' ";
	$datapegawai=$this->pegawai_serv->getPegawaiListByNip($cari );
	$cekdata=count($datapegawai);
	if (($cekdata==1) && ($_POST['proses']=='Simpan')) {
	
		$hasil="data unit nip : $i_peg_nip sudah ada......";
		$cek="gagal";
	
	}
	else
	{
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

 		if ($_POST['d_spmt_cpns'])
		{
			$d_spmt_cpns1=substr($_POST['d_spmt_cpns'],0,2);
			$d_spmt_cpns2=substr($_POST['d_spmt_cpns'],3,2);
			$d_spmt_cpns3=substr($_POST['d_spmt_cpns'],6,4);
		}
		$d_spmt_cpns=$d_spmt_cpns3."-".$d_spmt_cpns2."-".$d_spmt_cpns1;
		if (!$_POST['d_spmt_cpns']){$d_spmt_cpns=null;$cektglmulai=true;}
		else{$cektmtkerja=checkdate($d_spmt_cpns2,$d_spmt_cpns1,$d_spmt_cpns3);}	

		if ($_POST['c_lokasi_unitkerja_cpns']=='3')
		{
			$c_eselon_i=$_POST['c_eselon_i_cpns'];
			if ($_POST['c_eselon_i_cpns']!=''){
				$c_eselon_il=explode(";",$c_eselon_i);
				$c_eselon_i=$c_eselon_il[0];
			}
			else {$c_eselon_i='00';}

			$c_eselon_ii=$_POST['c_eselon_ii_cpns'];
			if ($_POST['c_eselon_ii_cpns']!=''){			
				$valesl = explode(";",$c_eselon_ii);
				$c_eselon_ii=$valesl[0];
				$c_parent=$valesl[1];
			}
			else {$c_eselon_ii='00';$c_parent='00';}

			$c_eselon_iii=$_POST['c_eselon_iii_cpns'];
			if ($_POST['c_eselon_iii_cpns']!=''){			
				$valesliii = explode(";",$c_eselon_iii);
				$c_eselon_iii=$valesliii[1];
				$c_satker=$valesliii[2];	
			}
			else {$c_eselon_iii='00';$c_satker='00';}

			$c_eselon_iv=$_POST['c_eselon_iv_cpns'];
			if ($_POST['c_eselon_iv_cpns']!=''){
				$valesliv = explode(";",$c_eselon_iv);
				$c_eselon_iv=$valesliv[0];
			}
			else {$c_eselon_iv='00';}

			$c_eselon_v=$_POST['c_eselon_v_cpns'];
			if ($_POST['c_eselon_v_cpns']!=''){$c_eselon_vl=strlen($c_eselon_v); $c_eselon_v=$this->right($c_eselon_v, $c_eselon_vl);}
			else {$c_eselon_v='00';}
		}
		else
		{		
			$c_eselon_i=$_POST['c_eselon_i_cpns'];
			if ($_POST['c_eselon_i_cpns']!=''){	$c_eselon_il=strlen($c_eselon_i); $c_eselon_i=$this->right($c_eselon_i,$c_eselon_il);}
			else {$c_eselon_i='00';}

			$c_eselon_ii=$_POST['c_eselon_ii_cpns'];
			if ($_POST['c_eselon_ii_cpns']!=''){$c_eselon_iil=strlen($c_eselon_ii); $c_eselon_ii=$this->right($c_eselon_ii, $c_eselon_iil);}
			else {$c_eselon_ii='00';}

			$c_eselon_iii=$_POST['c_eselon_iii_cpns'];
			if ($_POST['c_eselon_iii_cpns']!=''){$c_eselon_iiil=strlen($c_eselon_iii); $c_eselon_iii=$this->right($c_eselon_iii, $c_eselon_iiil);}
			else {$c_eselon_iii='00';}

			$c_eselon_iv=$_POST['c_eselon_iv_cpns'];
			if ($_POST['c_eselon_iv_cpns']!=''){$c_eselon_ivl=strlen($c_eselon_iv); $c_eselon_iv=$this->right($c_eselon_iv, $c_eselon_ivl);}
			else {$c_eselon_iv='00';}

			$c_eselon_v=$_POST['c_eselon_v_cpns'];
			if ($_POST['c_eselon_v_cpns']!=''){$c_eselon_vl=strlen($c_eselon_v); $c_eselon_v=$this->right($c_eselon_v, $c_eselon_vl);}
			else {$c_eselon_v='00';}
		}


//if (($cektgllahir==true &&  $ceksk==true && $cektmt==true && $cektmtkerja==true ) )
//{
	$c_gol_cpns=substr($_POST['c_gol_cpns'],0,2);
	if ($_POST['i_peg_nrp']){$i_peg_nip=$_POST['i_peg_nrp'];}
	else{$i_peg_nip=$_POST['i_peg_nip'];}
		  
	if (!$_POST['i_peg_nip']){$i_peg_nip=$_POST['i_peg_nip_new'];}
	
	if ($_POST['c_status_kepegawaian']=='4' || $_POST['c_status_kepegawaian']=='5' || $_POST['c_status_kepegawaian']=='6' || $_POST['c_status_kepegawaian']=='7')
	{$c_peg_status='MIL';}
	else{ $c_peg_status='2CP';}
	
	if (!$_POST['i_peg_nip_new']){$i_peg_nip_new=$i_peg_nip;}
	else{$i_peg_nip_new=$_POST['i_peg_nip_new'];}
	
	$MaintainData = array("i_peg_nip"=>$i_peg_nip,
						"i_peg_nrp"=>$_POST['i_peg_nrp'],
						"i_peg_nip_new"=>$i_peg_nip_new,
						"n_peg"=>$_POST['n_peg'],
						"c_peg_jeniskelamin"=>$_POST['c_peg_jeniskelamin'],
						"c_eselon_cpns"=>$_POST['c_eselon_cpns'],
						"c_eselon_i_cpns"=>$c_eselon_i,
						"c_eselon_ii_cpns"=>$c_eselon_ii,
						"c_eselon_iii_cpns"=>$c_eselon_iii,
						"c_eselon_iv_cpns"=>$c_eselon_iv,
						"c_eselon_v_cpns"=>$c_eselon_v,
						"c_gol_cpns"=>$c_gol_cpns,
						"c_jabatan_cpns"=>$_POST['c_jabatan_cpns'],
						"c_lokasi_unitkerja_cpns"=>$_POST['c_lokasi_unitkerja_cpns'],
						"c_pend_cpns"=>$_POST['c_pend_cpns'],
						"c_status_kepegawaian"=>$_POST['c_status_kepegawaian'],
						"d_peg_lahir"=>$d_peg_lahir,
						"d_sk_cpns"=>$d_sk_cpns,
						"d_tmt_cpns"=>$d_tmt_cpns,
						"d_tmt_kerja"=>$d_tmt_cpns,						
						"d_tmt_kgb"=>$d_tmt_cpns,
						"d_tmt_kerjacpns"=>$d_spmt_cpns,
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
						"e_file_photo"=>'nophoto.jpg',
						"d_spmt_cpns"=>$d_spmt_cpns,
						"i_spmt_cpns"=>$_POST['i_spmt_cpns'],
						"n_spmt_pejabatcpns"=>$_POST['n_spmt_pejabatcpns'],
						"c_parent"=>$c_parent,
						"c_satker"=>$c_satker,						
						"i_entry"=>$this->view->userid,
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
}

	$this->view->statusKePegRef = $this->reff_serv->getStatusKepegawaian('');
	$this->view->nmJenjangList = $this->reff_serv->getPendidikan('');	
	//$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai('');
		
	//$cariesl= " and c_eselon='15'";
	$this->view->eselonList = $this->reff_serv->getEselon($cariesl);
	$this->view->lokasiList = $this->reff_serv->getLokasi('');	
	$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1'");
	$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='2'");
	$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='3'");
	$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='4'");
	$this->view->eselonvList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='5'");

	
//}
//else{
	// $hasil="gagal format tanggal salah....";
	// if ($_POST['proses']=='Simpan')
	// {
		// $this->view->par="Simpan";
		// $this->view->jdl="Menambah ";
		// $par="Menambah";
	// }		
	// else
	// {
		// $this->view->par="Ubah";
		// $this->view->jdl="Merubah ";
		// $par="Merubah";		
	// }

// }	

	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	if ($cek=='gagal'){
		$this->listDataByKeyPost();
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
		$this->render('cpns');
	}
	else{
//		$this->listDataByKey($_POST['i_peg_nip']) ;
		$this->listpegawaiAction();	
/* 		if ($_POST['proses']=='Hapus'){$this->render('listpegawai');}
		else{$this->render('listpegawai');} */
		$this->listDataByKey($i_peg_nip);
		if ($_POST['proses']=='Hapus'){$this->render('cpns');}
		else{$this->render('cpns');}		

				
		
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

	$neseloncpns1=trim($datapegawai[0]['neseloncpns1']);
	$neseloncpns2=trim($datapegawai[0]['neseloncpns2']);
	$neseloncpns3=trim($datapegawai[0]['neseloncpns3']);
	$neseloncpns4=trim($datapegawai[0]['neseloncpns4']);
	

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
	$this->view->c_peg_jeniskelamin=$datapegawai[0]['c_peg_jeniskelamin'];
	
	$this->view->d_spmt_cpns=$datapegawai[0]['d_spmt_cpns'];
	$this->view->i_spmt_cpns=$datapegawai[0]['i_spmt_cpns'];
	$this->view->n_spmt_pejabatcpns=$datapegawai[0]['n_spmt_pejabatcpns'];
						
	$c_lokasi_unitkerja=trim($datapegawai[0]['c_lokasi_unitkerja_cpns']);

if ($c_lokasi_unitkerja=='1')	{	
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
	$c_satker=trim($datapegawai[0]['c_satker']);
	$c_parent=trim($datapegawai[0]['c_parent']);


	$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='3'");
	$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_iii = '00'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child = '00' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");		
	$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child <> '00' and c_parent ='$c_parent'  and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
	$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2'  and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	
	$neseloncpns1=trim($datapegawai[0]['neseloncpns1']);
	$neseloncpns2=trim($datapegawai[0]['neseloncpns2']);
	$neseloncpns3=trim($datapegawai[0]['neseloncpns3']);
	$neseloncpns4=trim($datapegawai[0]['neseloncpns4']);
	
	$this->view->c_eselon_i_cpns=trim($datapegawai[0]['c_eselon_i_cpns']).";".trim($datapegawai[0]['neseloncpns1']);
	$this->view->c_eselon_ii_cpns=trim($datapegawai[0]['c_eselon_ii_cpns']).";".trim($datapegawai[0]['c_parent']).";".trim($datapegawai[0]['neseloncpns2']);
	$this->view->c_eselon_iii_cpns=trim($datapegawai[0]['ceseloncpns2']).";".trim($datapegawai[0]['c_eselon_iii_cpns']).";".trim($datapegawai[0]['c_satker']).";".trim($datapegawai[0]['neseloncpns3']);
	$this->view->c_eselon_iv_cpns=trim($datapegawai[0]['c_eselon_iv_cpns']).";".trim($datapegawai[0]['neseloncpns4']);
	
	//echo trim($datapegawai[0]['ceseloncpns2']).";".trim($datapegawai[0]['c_eselon_iii_cpns']).";".trim($datapegawai[0]['c_satker']).";".trim($datapegawai[0]['neseloncpns3']);
	
}
	
	$this->view->ceseloncpns=substr($datapegawai[0]['c_eselon_cpns'],1,1);
	


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

	$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai($carigol); 
	
	if ($neseloncpns1){$nesl1=",$neseloncpns1";}
	if ($neseloncpns2){$nesl2=",$neseloncpns2";}
	if ($neseloncpns3){$nesl3=",$neseloncpns3";}
	if ($neseloncpns4){$nesl4=",$neseloncpns4";}
	if ($neseloncpns5){$nesl5=",$neseloncpns5";}
	
	$jabatanlengkap=$datapegawai[0]['n_jabatan_cpns'].", pada ".$nesl5.$nesl4.$nesl3.$nesl2.$nesl1;
	$this->view->jabat_lengkap=$jabatanlengkap;
}

public function listDataByKeyPost() {  

	$this->view->nama= $_POST['n_peg'];
	$this->view->nip= $_POST['i_peg_nip'];
	$this->view->golongan= $_POST['c_peg_golongan'];
	$this->view->pangkat= $_POST['n_peg_pangkat'];	
	$this->view->filephoto= $_POST['e_file_photo'];
	$this->view->statuspeg= $_POST['c_peg_status'];	

	$this->view->i_peg_nip=$_POST['i_peg_nip'];
	$this->view->i_peg_nrp=$_POST['i_peg_nrp'];
	$this->view->i_peg_nip_new=$_POST['i_peg_nip_new'];	
	$this->view->n_peg=$_POST['n_peg'];
	$this->view->n_peg_gelardepan=$_POST['n_peg_gelardepan'];
	$this->view->n_peg_gelarblkg=$_POST['n_peg_gelarblkg'];
	$this->view->d_sk_cpns=$_POST['d_sk_cpns'];
	$this->view->n_sk_pejabatcpns=$_POST['n_sk_pejabatcpns'];
	$this->view->i_sk_cpns=$_POST['i_sk_cpns'];
	$this->view->d_tmt_cpns=$_POST['d_tmt_cpns'];
	$this->view->c_gol_cpns=$_POST['c_gol_cpns'];
	$this->view->n_pangkat_cpns=$_POST['n_pangkat_cpns'];
	$this->view->c_eselon_cpns=$_POST['c_eselon_cpns'];
	$this->view->c_lokasi_unitkerja_cpns=trim($_POST['c_lokasi_unitkerja_cpns']);


	$this->view->q_fiktif_cpns_thn=$_POST['q_fiktif_cpns_thn'];
	$this->view->q_fiktif_cpns_bln=$_POST['q_fiktif_cpns_bln'];
	$this->view->q_honorer_cpns_thn=$_POST['q_honorer_cpns_thn'];
	$this->view->q_honorer_cpns_bln=$_POST['q_honorer_cpns_bln'];
	$this->view->q_swasta_cpns_thn=$_POST['q_swasta_cpns_thn'];
	$this->view->q_swasta_cpns_bln=$_POST['q_swasta_cpns_bln'];
	$this->view->q_mktotal_cpns_thn=$_POST['q_mktotal_cpns_thn'];
	$this->view->q_mktotal_cpns_bln=$_POST['q_mktotal_cpns_bln'];
	$this->view->c_pend_cpns=$_POST['c_pend_cpns'];
	$this->view->c_jabatan_cpns=$_POST['c_jabatan_cpns'];
	$this->view->n_jabatan_cpns=$_POST['n_jabatan_cpns'];
	$this->view->c_status_kepegawaian=$_POST['c_status_kepegawaian'];
	$this->view->d_tmt_kerjacpns=$_POST['d_tmt_kerja'];
	$this->view->d_peg_lahir=$_POST['d_peg_lahir'];
	$this->view->e_file_photo=$_POST['e_file_photo'];
	$this->view->c_peg_jeniskelamin=$_POST['c_peg_jeniskelamin'];
	
	$this->view->d_spmt_cpns=$_POST['d_spmt_cpns'];
	$this->view->i_spmt_cpns=$_POST['i_spmt_cpns'];
	$this->view->n_spmt_pejabatcpns=$_POST['n_spmt_pejabatcpns'];

	
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
		
	
	
	$c_lokasi_unitkerja=trim($_POST['c_lokasi_unitkerja_cpns']);
if ($c_lokasi_unitkerja=='1')	{
	$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	

	$eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
	$this->view->eseloniiList=$eseloniiList;

	$eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
	$this->view->eseloniiiList = $eseloniiiList;

	$eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	$this->view->eselonivList = $eselonivList;

	$eselonvList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii'  and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
	$this->view->eselonvList = $eselonvList;
}
else
{
	$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	

	$eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_child='0' ");	
	$this->view->eseloniiList=$eseloniiList;

	$eseloniiiList = $this->reff_serv->getTrUnitKerja("  and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_child!='0'");	
	$this->view->eseloniiiList = $eseloniiiList;

	$eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and c_tkt_esl in ('3','4') and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	$this->view->eselonivList = $eselonivList;
	
	

}	
	
	
	$c_eselon_v=trim($_POST['c_eselon_v_cpns']);
	
	$this->view->ceseloncpns=substr($_POST['c_eselon_cpns'],1,1);
	

	
	$this->view->c_eselon_i_cpns=trim($_POST['c_eselon_i_cpns']);
	$this->view->c_eselon_ii_cpns=trim($_POST['c_eselon_ii_cpns']);
	$this->view->c_eselon_iii_cpns=trim($_POST['c_eselon_iii_cpns']);
	$this->view->c_eselon_iv_cpns=trim($_POST['c_eselon_iv_cpns']);
	$this->view->c_eselon_v_cpns=trim($_POST['c_eselon_v_cpns']);
	

	$c_status_kepegawaian=$_POST['c_status_kepegawaian'];
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

	$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai($carigol); 
	

	$this->view->jabat_lengkap=$_POST['jabat_lengkap'];
}

function right($string){
    return substr($string,0,2);
}
function left($string){
    return substr($string,3,200);
}
	
}
?>