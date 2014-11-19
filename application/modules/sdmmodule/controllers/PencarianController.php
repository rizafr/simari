<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Monitoring_Service.php";

class Sdmmodule_PencarianController extends Zend_Controller_Action {

		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu');
		$this->view->photoPath = $registry->get('photoPath');
		 
		$this->reff_serv = sdm_refferensi_Service::getInstance();
		$this->pegawai_serv = Sdm_Monitoring_Service::getInstance();
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
	public function pencarianjsAction() 
	{
		header('content-type : text/javascript');
		$this->render('pencarianjs');
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
	
public function pencarianAction() {    

	$c_eselon_cari	=$_POST['c_eselon_cari'];
	$c_golongan_cari =$_POST['c_golongan_cari'];	
	$c_jab_query =$_POST['c_jab_query'];
	$c_jabatan_cari =$_POST['c_jabatan'];
	$c_query =$_POST['c_query'];
	$nama_cari =$_POST['nama_cari'];
	$nip_cari =$_POST['nip_cari'];
	

	
	$this->view->lokasiList = $this->reff_serv->getLokasi('');

	if($c_jab_query){
		$c_jab_query=strtoupper($c_jab_query);
		if ($c_query=='S') {$carijab= " and upper(n_jabatan) ='$c_jab_query' ";}
		if ($c_query=='AW') {$carijab= " and upper(n_jabatan) like'%$c_jab_query' ";}
		if ($c_query=='AK') {$carijab= " and upper(n_jabatan) like'$c_jab_query%' ";}
		if ($c_query=='M') {$carijab= " and upper(n_jabatan) like'%$c_jab_query%' ";}
		
		$getJabatan = $this->reff_serv->getJabatan($carijab);
		$jmlResult=count($getJabatan);
		for ($j = 0; $j < $jmlResult; $j++) 
		{
			$c_jabatan=$getJabatan[$j]['c_jabatan'];
			$c_jabatanx=$c_jabatanx."'".$c_jabatan."',";	
		}
		 $c_jabatan=$c_jabatanx."''";
		 
		 $cari= " and c_jabatan in ($c_jabatan) ";
	 }
	 

		
			if ($_POST['c_lokasi_unitkerja']){
			$c_lokasi_unitkerja=trim($_POST['c_lokasi_unitkerja']);
			}
			else{
			$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);
			}
			
			$this->view->c_lokasi_unitkerja=$c_lokasi_unitkerja;
			if ($c_lokasi_unitkerja){$cari .= " and c_lokasi_unitkerja='$c_lokasi_unitkerja'";}
	
			
		if ($c_lokasi_unitkerja=='1'){
			
			if ($_POST['c_eselon_i']){$c_eselon_i=$_POST['c_eselon_i'];}
			else{$c_eselon_i=$_GET['c_eselon_i'];}
			
			$c_eselon_i=substr($c_eselon_i,0,2);
			if ($_POST['c_eselon_i'] || $_GET['c_eselon_i']){$cari .= " and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'";}

			if ($_POST['c_eselon_ii']){$c_eselon_ii=$_POST['c_eselon_ii'];}
			else{$c_eselon_ii=$_GET['c_eselon_ii'];}
			$c_eselon_ii=substr($c_eselon_ii,0,2);
			if ($_POST['c_eselon_ii'] || $_GET['c_eselon_ii']){$cari .= " and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and c_lokasi_unitkerja='$c_lokasi_unitkerja'";}
			
			if ($_POST['c_eselon_iii']){$c_eselon_i=$_POST['c_eselon_iii'];}
			else{$c_eselon_iii=$_GET['c_eselon_iii'];}
			
			
			$c_eselon_iii=substr($c_eselon_iii,0,2);
			if ($_POST['c_eselon_iii'] || $_GET['c_eselon_iii']){$cari .= " and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_lokasi_unitkerja='$c_lokasi_unitkerja'";}

			if ($_POST['c_eselon_iv']){$c_eselon_iv=$_POST['c_eselon_iv'];}
			else{$c_eselon_iv=$_GET['c_eselon_iv'];}
			
			
			$c_eselon_iv=substr($c_eselon_iv,0,2);
			if ($_POST['c_eselon_iv'] || $_GET['c_eselon_iv']){$cari .= " and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_lokasi_unitkerja='$c_lokasi_unitkerja'";}
		

			$this->view->c_eselon_i = $c_eselon_i;
			

			
			$this->view->lokasiList = $this->reff_serv->getLokasi('');
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			
			
			
			if ($_POST['c_eselon_i']){$this->view->c_eselon_i=$_POST['c_eselon_i'];}
			else{$this->view->c_eselon_i=$_GET['c_eselon_i'];}	
			
			$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	

			if ($_POST['c_eselon_ii']){$this->view->c_eselon_ii=$_POST['c_eselon_ii'];}
			else{$this->view->c_eselon_ii=$_GET['c_eselon_ii'];}
				
			
			$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");


			if ($_POST['c_eselon_iii']){$this->view->c_eselon_iii=$_POST['c_eselon_iii'];}
			else{$this->view->c_eselon_iii=$_GET['c_eselon_iii'];}
				
			
			$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

			if ($_POST['c_eselon_iv']){$this->view->c_eselon_iv=$_POST['c_eselon_iv'];}
			else{$this->view->c_eselon_iv=$_GET['c_eselon_iv'];}
				
			
			$this->view->eselonvList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

		}
		else
		{


			if ($_POST['c_lokasi_unitkerja']){$c_lokasi_unitkerja=trim($_POST['c_lokasi_unitkerja']);}
			else{$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);}
			$this->view->lokasiList = $this->reff_serv->getLokasi('');
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

			
			if ($_POST['c_eselon_i']){$c_eselon_i=$_POST['c_eselon_i'];}
			else{$c_eselon_i=trim($_GET['c_eselon_i']);}
			$this->view->c_eselon_i =$c_eselon_i;
			if ($c_eselon_i){
				$expesl1 = explode(";",$c_eselon_i);
				$c_eselon_i=$expesl1[0];
			}
			
			if ($_POST['c_eselon_ii']){$c_eselon_ii=$_POST['c_eselon_ii'];}
			else{$c_eselon_ii=trim($_GET['c_eselon_ii']);}
			$this->view->c_eselon_ii =$c_eselon_ii;
			if ($c_eselon_ii){
			$expesl2 = explode(";",$c_eselon_ii);
			$c_eselon_ii=$expesl2[0];
			$c_parent=$expesl2[1];
			}
			
			
			if ($_POST['c_eselon_iii']){$c_eselon_iii=$_POST['c_eselon_iii'];}
			else{$c_eselon_iii=trim($_GET['c_eselon_iii']);}
			$this->view->c_eselon_iii =trim($c_eselon_iii);		
			if ($c_eselon_iii){
			$expesl3 = explode(";",$c_eselon_iii);	
			$c_eselon_iix=$expesl3[0];
			$c_eselon_iii=$expesl3[1];
			$c_satker=$expesl3[2];			
			}

			if ($_POST['c_eselon_iv']){$c_eselon_iv=$_POST['c_eselon_iv'];}
			else{$c_eselon_iv=trim($_GET['c_eselon_iv']);}
			$this->view->c_eselon_iv =trim($c_eselon_iv);	
			if ($c_eselon_iv){
			$expesl4 = explode(";",$c_eselon_iv);	
			$c_eselon_iv=$expesl4[0];
			}			
			

			
			if ($c_eselon_i){$cari .= " and c_eselon_i='$c_eselon_i'";}
			if ($c_eselon_ii ){$cari .= " and c_eselon_ii='$c_eselon_ii'";}
			if ($c_eselon_iii){$cari .= " and c_eselon_iii='$c_eselon_iii' and c_parent='$c_parent' and c_satker='$c_satker'";}
			if ($c_eselon_iv){$cari .= " and c_eselon_iv='$c_eselon_iv'";}
			
			//echo $cari;
			//echo " and c_eselon_i='$c_eselon_i'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child <> '00' and c_parent ='$c_parent'  and c_lokasi_unitkerja='$c_lokasi_unitkerja'";
			
			$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_iii = '00'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child = '00' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child <> '00' and c_parent ='$c_parent'  and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_iii <> '00' and c_eselon_ii='$c_eselon_iix'  and c_lokasi_unitkerja='$c_lokasi_unitkerja' ");
			
						



		}		

	
	
	if ($_POST['harilahir']){$harilahir=$_POST['harilahir']; $this->view->harilahir=($_POST['harilahir']);$cari= $cari." and to_char(d_peg_lahir,'dd') ='$harilahir'";}
	if ($_POST['bulanlahir']){$bulanlahir=$_POST['bulanlahir']; $this->view->bulanlahir=($_POST['bulanlahir']);$cari= $cari." and to_char(d_peg_lahir,'mm') ='$bulanlahir'";}
	if ($_POST['tahunlahir']){$tahunlahir=$_POST['tahunlahir']; $this->view->tahunlahir=($_POST['tahunlahir']);$cari= $cari." and to_char(d_peg_lahir,'yyyy') ='$tahunlahir'";}

	if ($_POST['haricpns']){$haricpns=$_POST['haricpns']; $this->view->haricpns=($_POST['haricpns']);$cari= $cari." and to_char(d_tmt_cpns,'dd') ='$haricpns'";}
	if ($_POST['bulancpns']){$bulancpns=$_POST['bulancpns']; $this->view->bulancpns=($_POST['bulancpns']);$cari= $cari." and to_char(d_tmt_cpns,'mm') ='$bulancpns'";}
	if ($_POST['tahuncpns']){$tahuncpns=$_POST['tahuncpns']; $this->view->tahuncpns=($_POST['tahuncpns']);$cari= $cari." and to_char(d_tmt_cpns,'yyyy') ='$tahuncpns'";}	

	

	
	if ($nip_cari){$cari= $cari." and (i_peg_nip like '%$nip_cari%' or i_peg_nip_new like '%$nip_cari%') ";}
	if ($nama_cari){$nama_cari=strtoupper($nama_cari);$cari= $cari." and upper(n_peg) like '%$nama_cari%'";}
	//if ($tgllahir_cari){$cari= $cari." and to_char(d_peg_lahir,'dd-mm-yyyy') ='$tgllahir_cari'";}
	//if ($tmtcpns_cari){$cari= $cari." and to_char(d_tmt_cpns,'dd-mm-yyyy') ='$tmtcpns_cari'";}
	if ($c_eselon_cari){$cari= $cari." and c_eselon ='$c_eselon_cari'";}
	if ($c_golongan_cari){$cari= $cari." and c_golongan ='$c_golongan_cari'";}
	if ($c_jabatan_cari){$cari= $cari." and c_jabatan ='$c_jabatan_cari'";}

	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		

	if ($this->view->c_izin=='000000' || $this->view->c_izin=='000001' || $this->view->c_izin=='000002' || $this->view->c_izin=='000003'){
		$c_lokasi_unitkerja=trim($this->view->c_lokasi_unitkerja);
		$c_eselon_i=trim($this->view->c_eselon_i);
	}

	$carigol=" and c_peg_tipegolongan ='3' ";
	$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai($carigol);
	$this->view->eselonList = $this->reff_serv->getEselon('');
//echo $cari;
	$this->view->totalpegawaiList = $this->pegawai_serv->getPegawaiList($cari, 0, 0 ,$orderBy);		
	$this->view->pegawaiList = $this->pegawai_serv->getPegawaiList($cari, $currentPage, $numToDisplay,$orderBy );	
	
	$this->view->c_eselon_cari	=$_POST['c_eselon_cari'];
	$this->view->c_golongan_cari =$_POST['c_golongan_cari'];	
	$this->view->c_jab_query =$_POST['c_jab_query'];
	$this->view->c_jabatan =$_POST['c_jabatan'];
	$this->view->n_jabatan =$_POST['n_jabatan'];
	$this->view->c_query =$_POST['c_query'];
	$this->view->nama_cari =$_POST['nama_cari'];
	$this->view->nip_cari =$_POST['nip_cari'];
	$this->view->tgllahir_cari	=$_POST['tgllahir_cari'];
	$this->view->tmtcpns_cari =$_POST['tmtcpns_cari'];	
}

public function listtextAction() {

}

function right($string){
    return substr($string,0,2);
}
function left($string){
    return substr($string,3,200);
}


public function listcomboAction() {

	$jabatanlengkap="";
if ($_GET['c_lokasi_unitkerja']=='1'){	
	$this->view->c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);
	$this->view->lokasiList = $this->reff_serv->getLokasi('');	
	$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);
	$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	
	$c_eselon_i=$_GET['eseloni'];
	$c_eselon_i=substr($c_eselon_i,0,2);
	$this->view->c_eselon_i =trim($_GET['eseloni']);
	$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
	if ($_GET['eseloni']){$nesl1=$this->left($_GET['eseloni']); $nesl1=$nesl1.",";}
	
	$c_eselon_ii=$_GET['eselonii'];
	$c_eselon_ii=substr($c_eselon_ii,0,2);
	$this->view->c_eselon_ii =trim($_GET['eselonii']);
	$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	if ($_GET['eselonii']){$nesl2=$this->left($_GET['eselonii']); $nesl2=$nesl2.",";}
	
	$c_eselon_iii=$_GET['eseloniii'];
	$c_eselon_iii=substr($c_eselon_iii,0,2);
	$this->view->c_eselon_iii =trim($_GET['eseloniii']);
	$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii'  and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	if ($_GET['eseloniii']){$nesl3=$this->left($_GET['eseloniii']); $nesl3=$nesl3.",";}
	
	$c_eselon_iv=$_GET['eseloniv'];
	$c_eselon_iv=substr($c_eselon_iv,0,2);
	$this->view->c_eselon_iv =trim($_GET['eseloniv']);
	$this->view->eselonvList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'   and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	
	if ($_GET['eseloniv']){$nesl4=$this->left($_GET['eseloniv']); $nesl4=$nesl4.",";}	
	$jabatanlengkap=$nesl4.$nesl3.$nesl2.$nesl1;
	$this->view->jabat_lengkap=$jabatanlengkap;
	$this->view->c_eselon=$_GET['c_eselon'];
	$this->view->eselonList = $this->reff_serv->getEselon('');
}
else{

	$this->view->c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);
	$this->view->lokasiList = $this->reff_serv->getLokasi('');	
	$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);
	$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

	
	$c_eselon_i=$_GET['eseloni'];
	$expesl1 = explode(";",$c_eselon_i);
	$c_eselon_i=$expesl1[0];
	$this->view->c_eselon_i =trim($_GET['eseloni']);
	

	$c_eselon_ii=$_GET['eselonii'];
	$expesl2 = explode(";",$c_eselon_ii);
	$c_eselon_ii=$expesl2[0];
	$c_parent=$expesl2[1];
	$this->view->c_eselon_ii =trim($_GET['eselonii']);
	
	
	$c_eselon_iii=$_GET['eseloniii'];
	if ($c_eselon_iii){
	$expesl3 = explode(";",$c_eselon_iii);	
	$c_eselon_ii=$expesl3[0];
	$this->view->c_eselon_iii =trim($_GET['eseloniii']);	
	}


	
	$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_iii = '00'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child = '00' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	if ($_GET['eseloni']){$nesl1=$expesl1[1]; $nesl1=$nesl1;}	

	$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child <> '00' and c_parent ='$c_parent'  and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	if ($_GET['eselonii']){$nesl2=$expesl2[2]; $nesl2=$nesl2.",";}	

	$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_iii <> '00' and c_eselon_ii='$c_eselon_ii'  and c_lokasi_unitkerja='$c_lokasi_unitkerja' ");
	if ($_GET['eseloniii']){$nesl3=$expesl3[3]; $nesl3=$nesl3.",";}


	$this->view->c_eselon_iv =trim($_GET['eseloniv']);
	if ($_GET['eseloniv']){$nesl4=$this->left($_GET['eseloniv']); $nesl4=$nesl4.",";}	
	$jabatanlengkap=$nesl4.$nesl3.$nesl2.$nesl1;
	$this->view->jabat_lengkap=$jabatanlengkap;
	$this->view->c_eselon=$_GET['c_eselon'];
	$this->view->eselonList = $this->reff_serv->getEselon('');	

	$this->render('listcombo2');
}	
}	
}
?>