<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_Pendidikan_Service.php";
require_once "service/sdm/Sdm_Pelatihan_Service.php";

class Sdmmodule_DatariwayatpegawaiController extends Zend_Controller_Action {

		
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


	if ($nCol && $cCol ){$cari .= " and $cCol like '%$nCol%' ";}
	$orderBy=$_GET['orderBy'];
	$order=$_GET['order'];
	if (!$_GET['order']){$this->view->orderbya="asc";$this->view->orderbyb="desc";}
	else{
		if ($_GET['order']=='desc'){	$this->view->orderbya="desc";$this->view->orderbyb="asc";}
		else{$this->view->orderbya="asc";$this->view->orderbyb="desc";}
	}
	if ($_GET['orderBy']){$orderBy=" order by $orderBy $order";}
	else{$orderBy=" order by d_entry asc";}
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
public function datapokokAction() {
}	


}
?>