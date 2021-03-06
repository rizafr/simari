<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_Pendidikan_Service.php";
require_once "service/sdm/Sdm_Pelatihan_Service.php";

class Sdmmodule_DataPnsController extends Zend_Controller_Action {

		
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
		$this->view->c_izin=$ssologin->c_izin[0]['c_izin'];	
		
    }
	
    public function indexAction() {
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
	

public function pnsAction() {
		$par=$_GET['par'];
		$this->view->pns=$par;
		$this->view->par="Ubah";
		//$this->view->jdl="Merubah ";

		
		
		$nip=$_GET['nip'];
		if (!$nip){$nip=$this->view->nip;}
		$this->listDataByKey($nip);
		

	$this->view->statusKePegRef = $this->reff_serv->getStatusKepegawaian('');
	$this->view->nmJenjangList = $this->reff_serv->getPendidikan('');	
	$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai('');
	$this->view->eselonList = $this->reff_serv->getEselon('');
	$this->view->lokasiList = $this->reff_serv->getLokasi('');	

		if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Merubah ";}
		else{$this->view->jdl="Melihat ";$this->render('pnsview');}	

}

public function pnsjsAction() 
	{
		header('content-type : text/javascript');
		$this->render('pnsjs');
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
	$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	if ($_GET['eselonii']){$nesl2=$this->left($_GET['eselonii']); $nesl2=",".$nesl2;}
	
	$c_eselon_iii=$_GET['eseloniii'];
	$c_eselon_iii=substr($c_eselon_iii,0,2);
	$this->view->c_eselon_iii =trim($_GET['eseloniii']);
	$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii'  and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	if ($_GET['eseloniii']){$nesl3=$this->left($_GET['eseloniii']); $nesl3=",".$nesl3;}
	
	$c_eselon_iv=$_GET['eseloniv'];
	$c_eselon_iv=substr($c_eselon_iv,0,2);
	$this->view->c_eselon_iv =trim($_GET['eseloniv']);
	$this->view->eselonvList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'   and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	
	if ($_GET['eseloniv']){$nesl4=$this->left($_GET['eseloniv']); $nesl4=",".$nesl4;}	
	$jabatanlengkap=$nesl4.$nesl3.$nesl2.$nesl1;
	$this->view->jabat_lengkap=$jabatanlengkap;
	$this->view->c_eselon=$_GET['c_eselon'];
	$this->view->eselonList = $this->reff_serv->getEselon('');
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
	$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	if ($_GET['eselonii']){$nesl2=$this->left($_GET['eselonii']); $nesl2=",".$nesl2;}
	
	$c_eselon_iii=$_GET['eseloniii'];
	$c_eselon_iii=substr($c_eselon_iii,0,2);
	$this->view->c_eselon_iii =trim($_GET['eseloniii']);
	$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	if ($_GET['eseloniii']){$nesl3=$this->left($_GET['eseloniii']); $nesl3=",".$nesl3;}
	
	
	if ($_GET['eseloniv']){$nesl4=$this->left($_GET['eseloniv']); $nesl4=",".$nesl4;}	
	$jabatanlengkap=$nesl4.$nesl3.$nesl2.$nesl1;
	$this->view->jabat_lengkap=$jabatanlengkap;
	$this->view->c_eselon=$_GET['c_eselon'];
	$this->view->eselonList = $this->reff_serv->getEselon('');
	

	$this->render('listcombo2');
}	
}

public function maintaindataAction() {


 		if ($_POST['d_sk_pns'])
		{
			$d_sk_pns1=substr($_POST['d_sk_pns'],0,2);
			$d_sk_pns2=substr($_POST['d_sk_pns'],3,2);
			$d_sk_pns3=substr($_POST['d_sk_pns'],6,4);
		}
		$d_sk_pns=$d_sk_pns3."-".$d_sk_pns2."-".$d_sk_pns1;
		if (!$_POST['d_sk_pns']){$d_sk_pns=null;$cektglmulai=true;}
		else{$cekskpns=checkdate($d_sk_pns2,$d_sk_pns1,$d_sk_pns3);}	

 		if ($_POST['d_kesehatan_pns'])
		{
			$d_kesehatan_pns1=substr($_POST['d_kesehatan_pns'],0,2);
			$d_kesehatan_pns2=substr($_POST['d_kesehatan_pns'],3,2);
			$d_kesehatan_pns3=substr($_POST['d_kesehatan_pns'],6,4);
		}
		$d_kesehatan_pns=$d_kesehatan_pns3."-".$d_kesehatan_pns2."-".$d_kesehatan_pns1;
		if (!$_POST['d_kesehatan_pns']){$d_kesehatan_pns=null;$cektglmulai=true;}
		else{$cekkes=checkdate($d_kesehatan_pns2,$d_kesehatan_pns1,$d_kesehatan_pns3);}		

 		if ($_POST['d_sk_prajab'])
		{
			$d_sk_prajab1=substr($_POST['d_sk_prajab'],0,2);
			$d_sk_prajab2=substr($_POST['d_sk_prajab'],3,2);
			$d_sk_prajab3=substr($_POST['d_sk_prajab'],6,4);
		}
		$d_sk_prajab=$d_sk_prajab3."-".$d_sk_prajab2."-".$d_sk_prajab1;
		if (!$_POST['d_sk_prajab']){$d_sk_prajab=null;$cektglmulai=true;}
		else{$cekprajab=checkdate($d_sk_prajab2,$d_sk_prajab1,$d_sk_prajab3);}	


		$c_eselon_i=$_POST['c_eselon_i'];
		if ($_POST['c_eselon_i']!=''){	$c_eselon_il=strlen($c_eselon_i); $c_eselon_i=$this->right($c_eselon_i,$c_eselon_il);}
		else {$c_eselon_i='00';}

		$c_eselon_ii=$_POST['c_eselon_ii'];
		if ($_POST['c_eselon_ii']!=''){$c_eselon_iil=strlen($c_eselon_ii); $c_eselon_ii=$this->right($c_eselon_ii, $c_eselon_iil);}
		else {$c_eselon_ii='00';}

		$c_eselon_iii=$_POST['c_eselon_iii'];
		if ($_POST['c_eselon_iii']!=''){$c_eselon_iiil=strlen($c_eselon_iii); $c_eselon_iii=$this->right($c_eselon_iii, $c_eselon_iiil);}
		else {$c_eselon_iii='00';}

		$c_eselon_iv=$_POST['c_eselon_iv'];
		if ($_POST['c_eselon_iv']!=''){$c_eselon_ivl=strlen($c_eselon_iv); $c_eselon_iv=$this->right($c_eselon_iv, $c_eselon_ivl);}
		else {$c_eselon_iv='00';}

		$c_eselon_v=$_POST['c_eselon_v'];
		if ($_POST['c_eselon_v']!=''){$c_eselon_vl=strlen($c_eselon_v); $c_eselon_v=$this->right($c_eselon_v, $c_eselon_vl);}
		else {$c_eselon_v='00';}


//if (($cekskpns==true &&  $cekkes==true && $cekprajab==true ) )
//if (($cekskpns==true &&  $cekprajab==true ) )
//{


	$MaintainData = array("i_peg_nip"=>$_POST['i_peg_nip'],
						//"c_eselon"=>$_POST['c_eselon'],
						"c_lokasi_unitkerja"=>$_POST['c_lokasi_unitkerja'],
						"c_eselon_i"=>$c_eselon_i,
						"c_eselon_ii"=>$c_eselon_ii,
						"c_eselon_iii"=>$c_eselon_iii,
						"c_eselon_iv"=>$c_eselon_iv,
						"c_eselon_v"=>$c_eselon_v,	
						"n_unitkerja_nokode"=>$_POST['n_unitkerja_nokode'],
						"i_sk_pns"=>$_POST['i_sk_pns'],
						"d_sk_pns"=>$d_sk_pns,
						"n_sk_pejabatpns"=>$_POST['n_sk_pejabatpns'],
						"i_kesehatan_pns"=>$_POST['i_kesehatan_pns'],
						"d_kesehatan_pns"=>$d_kesehatan_pns,
						"n_rumahsakit_pns"=>$_POST['n_rumahsakit_pns'],
						"n_kesehatan_pejabatpns"=>$_POST['n_kesehatan_pejabatpns'],
						"i_sk_prajab"=>$_POST['i_sk_prajab'],
						"d_sk_prajab"=>$d_sk_prajab,
						"n_sk_pejabatprajab"=>$_POST['n_sk_pejabatprajab'],						
						"c_peg_status"=>'3PN',
						"i_entry"=>$this->view->userid,
						"d_entry"=>date('Ymd'));		

	if ($_POST['proses']=='Simpan')
	{
		$hasil = $this->pegawai_serv->maintainDataPns($MaintainData,'update');		
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
	}
	else if ($_POST['proses']=='Hapus')
	{
		$hasil = $this->pegawai_serv->maintainDataPns($MaintainData,'delete');		
		$this->view->par="Hapus";
		$this->view->jdl="Menghapus ";
		$par="Menghapus";
	}	
	else
	{
		$hasil = $this->pegawai_serv->maintainDataPns($MaintainData,'update');
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";		
	}

	$this->view->statusKePegRef = $this->reff_serv->getStatusKepegawaian('');
	$this->view->nmJenjangList = $this->reff_serv->getPendidikan('');	
	$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai('');
	$this->view->eselonList = $this->reff_serv->getEselon('');
	$this->view->lokasiList = $this->reff_serv->getLokasi('');	
	$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='1'");
	$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='2'");
	$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='3'");
	$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='4'");
	$this->view->eselonvList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='5'");

	$this->listDataByKey($_POST['i_peg_nip']) ;
//}
/*
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
*/	
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;	
	if ($_POST['proses']=='Hapus'){$this->render('listpegawai');}
	else{$this->render('pns');}
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
	$this->view->c_eselon=$datapegawai[0]['c_eselon'];

	$this->view->c_eselon=trim($datapegawai[0]['c_eselon']);
	

	

	$this->view->n_unitkerja_nokode=trim($datapegawai[0]['n_unitkerja_nokode']);
	$this->view->i_sk_pns=trim($datapegawai[0]['i_sk_pns']);
	$this->view->d_sk_pns=trim($datapegawai[0]['d_sk_pns']);
	$this->view->n_sk_pejabatpns=trim($datapegawai[0]['n_sk_pejabatpns']);
	$this->view->i_kesehatan_pns=trim($datapegawai[0]['i_kesehatan_pns']);
	$this->view->d_kesehatan_pns=trim($datapegawai[0]['d_kesehatan_pns']);
	$this->view->n_rumahsakit_pns=trim($datapegawai[0]['n_rumahsakit_pns']);
	$this->view->n_kesehatan_pejabatpns=trim($datapegawai[0]['n_kesehatan_pejabatpns']);
	$this->view->i_sk_prajab=trim($datapegawai[0]['i_sk_prajab']);
	$this->view->d_sk_prajab=trim($datapegawai[0]['d_sk_prajab']);
	$this->view->n_sk_pejabatprajab=trim($datapegawai[0]['n_sk_pejabatprajab']);

	
	$c_lokasi_unitkerja=trim($datapegawai[0]['c_lokasi_unitkerja']);
if ($c_lokasi_unitkerja){

	$neselon1=trim($datapegawai[0]['neselon1']);
	$neselon2=trim($datapegawai[0]['neselon2']);
	$neselon3=trim($datapegawai[0]['neselon3']);
	$neselon4=trim($datapegawai[0]['neselon4']);
	
	$this->view->c_eselon_i=trim($datapegawai[0]['c_eselon_i'])." ".trim($datapegawai[0]['neselon1']);
	$this->view->c_eselon_ii=trim($datapegawai[0]['c_eselon_ii'])." ".trim($datapegawai[0]['neselon2']);
	$this->view->c_eselon_iii=trim($datapegawai[0]['c_eselon_iii'])." ".trim($datapegawai[0]['neselon3']);
	$this->view->c_eselon_iv=trim($datapegawai[0]['c_eselon_iv'])." ".trim($datapegawai[0]['neselon4']);
	
	$this->view->n_eselon_i=trim($datapegawai[0]['neselon1']);
	$this->view->n_eselon_ii=trim($datapegawai[0]['neselon2']);
	$this->view->n_eselon_iii=trim($datapegawai[0]['neselon3']);
	$this->view->n_eselon_iv=trim($datapegawai[0]['neselon4']);	
	
	$this->view->c_lokasi_unitkerja=trim($datapegawai[0]['c_lokasi_unitkerja']);
	$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
	$c_eselon_i=trim($datapegawai[0]['c_eselon_i']);
	$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja("  and c_eselon_i='$c_eselon_i' and c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	

	$c_eselon_ii=trim($datapegawai[0]['c_eselon_ii']);
	$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
	
	$c_eselon_iii=trim($datapegawai[0]['c_eselon_iii']);
	$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'   and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

	$c_eselon_iv=trim($datapegawai[0]['c_eselon_iv']);
	$this->view->eselonvList = $this->reff_serv->getTrUnitKerja("  and c_eselon_i='$c_eselon_i'   and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
	
	$this->view->ceselonpns=substr($datapegawai[0]['c_eselon'],1,1);
	
	if ($neselon1){$nesl1=",$neselon1";}
	if ($neselon2){$nesl2=",$neselon2";}
	if ($neselon3){$nesl3=",$neselon3";}
	if ($neselon4){$nesl4=",$neselon4";}
	if ($neselon5){$nesl5=",$neselon5";}
	
	$jabatanlengkap=$nesl5.$nesl4.$nesl3.$nesl2.$nesl1;
	$this->view->jabat_lengkap=$jabatanlengkap;	
	}
else{
	$neseloncpns1=trim($datapegawai[0]['neseloncpns1']);
	$neseloncpns2=trim($datapegawai[0]['neseloncpns2']);
	$neseloncpns3=trim($datapegawai[0]['neseloncpns3']);
	$neseloncpns4=trim($datapegawai[0]['neseloncpns4']);
	
	$c_lokasi_unitkerja=trim($datapegawai[0]['c_lokasi_unitkerja_cpns']);
	$this->view->c_lokasi_unitkerja=trim($datapegawai[0]['c_lokasi_unitkerja_cpns']);
	
	$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
	$c_eselon_i=trim($datapegawai[0]['c_eselon_i_cpns']);
	$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	

	$c_eselon_ii=trim($datapegawai[0]['c_eselon_ii_cpns']);
	$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
	
	$c_eselon_iii=trim($datapegawai[0]['c_eselon_iii_cpns']);
	$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

	$c_eselon_iv=trim($datapegawai[0]['c_eselon_iv_cpns']);
	$this->view->eselonvList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii'  and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
	
	$c_eselon_v=trim($datapegawai[0]['c_eselon_v_cpns']);
	
	$this->view->ceseloncpns=substr($datapegawai[0]['c_eselon_cpns'],1,1);

	$this->view->c_eselon_i=trim($datapegawai[0]['c_eselon_i_cpns'])." ".trim($datapegawai[0]['neseloncpns1']);
	$this->view->c_eselon_ii=trim($datapegawai[0]['c_eselon_ii_cpns'])." ".trim($datapegawai[0]['neseloncpns2']);
	$this->view->c_eselon_iii=trim($datapegawai[0]['c_eselon_iii_cpns'])." ".trim($datapegawai[0]['neseloncpns3']);
	$this->view->c_eselon_iv=trim($datapegawai[0]['c_eselon_iv_cpns'])." ".trim($datapegawai[0]['neseloncpns4']);
	$this->view->c_eselon_v=trim($datapegawai[0]['c_eselon_v_cpns']);	
	
	$this->view->n_eselon_i=trim($datapegawai[0]['neselon1']);
	$this->view->n_eselon_ii=trim($datapegawai[0]['neselon2']);
	$this->view->n_eselon_iii=trim($datapegawai[0]['neselon3']);
	$this->view->n_eselon_iv=trim($datapegawai[0]['neselon4']);	

	if ($neseloncpns1){$nesl1=",$neseloncpns1";}
	if ($neseloncpns2){$nesl2=",$neseloncpns2";}
	if ($neseloncpns3){$nesl3=",$neseloncpns3";}
	if ($neseloncpns4){$nesl4=",$neseloncpns4";}
	if ($neseloncpns5){$nesl5=",$neseloncpns5";}
	
	$jabatanlengkap=$datapegawai[0]['n_jabatan_cpns'].", pada ".$nesl5.$nesl4.$nesl3.$nesl2.$nesl1;
	$this->view->jabat_lengkap=$jabatanlengkap;

}	
}
function right($string){
    return substr($string,0,2);
}	
function left($string){
    return substr($string,3,200);
}
}
?>