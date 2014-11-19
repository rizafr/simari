<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/Sdm_Absensi_Service.php";

class Sdmmodule_DataabsensiController extends Zend_Controller_Action {
		
    public function init() {

		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->absensi_serv = Sdm_Absensi_Service::getInstance();
		$this->absensi_serv = Sdm_Absensi_Service::getInstance();
		$this->dataPerPage = $registry->get('dataPerPage');
		
    }
    public function indexAction() {
	   
    }
	public function dataabsensijsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('dataabsensijs');
    }
	
	public function listdataabsensiAction()
	{
	
	}
	
	public function listdatapegawaiAction()
	{
      //$this->_helper->viewRenderer->setNoRender(true);
	    $unitkerja = $_REQUEST['unitkerja'];
		
	    $numToDisplay = $this->dataPerPage;
	    $currentPage = $_REQUEST['currentPage']; 
        $wheredata = '';
		if ((!$unitkerja) || ($unitkerja == 'undefined')) { $unitkerja = $_REQUEST['param1']; }
		$wheredata .= " c_lokasi_unitkerja||c_eselon_i||c_eselon_ii='".$unitkerja."' and c_eselon_iii='00' and c_eselon_iv='00' and c_eselon_v='00' ";

		$pilfilter = $_REQUEST['pilfilter'];
		if ((!$pilfilter) || ($pilfilter == 'undefined')) { $pilfilter = $_REQUEST['param2']; }
		
		$filterval = $_REQUEST['filterval'];   
		if ((!$filterval) || ($filterval == 'undefined')) { $filterval = $_REQUEST['param3']; }

		if ($filterval != '')
		{  
		   $wheredata .= " and ".$pilfilter." like '%".$filterval."%'";
        }
		if((!$currentPage) || ($currentPage == 'undefined')) { $currentPage = 1; }
		$this->view->unitkerja = $unitkerja;
		$this->view->pilfilter = $pilfilter;
		$this->view->filterval = $filterval;
		$this->view->numToDisplay = $numToDisplay;
        $pegawaidatalstJml = $this->absensi_serv->getPegawaiDataList($wheredata,0,0,'');
	    $this->view->currentPage = $currentPage;   
		$this->view->pegawaidatalstJml = $pegawaidatalstJml;
        $this->view->pegawaidatalst = $this->absensi_serv->getPegawaiDataList($wheredata, $this->view->currentPage, $this->view->numToDisplay,'');
	}
	public function dataabsensiAction()
	{ 
       $tahunabsen = $_REQUEST['tahunabsen'];
       $bulanabsen = $_REQUEST['bulanabsen'];
	   $kd_lokasi = $_REQUEST['kd_lokasi'];
	   //$unitkerja = $_REQUEST['unitkerja'];
	   $c_eselon_i = $_REQUEST['c_eselon_i'];
	   $c_eselon_ii = $_REQUEST['c_eselon_ii'];
	   $nippegawai = $_REQUEST['i_peg_nip'];
	   //$i_peg_nip = explode('.',$nippegawai);
	   
	   $this->view->i_peg_nip = $nippegawai;
	   if ((!$tahunabsen) || ($tahunabsen == 'undefined')) { $this->view->tahunabsen = date('Y'); }
	   else { $this->view->tahunabsen = $tahunabsen; }
	   if ((!$bulanabsen) || ($bulanabsen == 'undefined')) { $this->view->bulanabsen = date('m'); }
	   else { $this->view->bulanabsen = $bulanabsen; }
	   
       $this->view->lokasilist = $this->absensi_serv->getLokasilst();
	   if ((!$kd_lokasi) || ($kd_lokasi == 'undefined')) { $this->view->kd_lokasi = $this->view->lokasilist[0]['c_lokasi']; }
	   else { $this->view->kd_lokasi = $kd_lokasi; }
	   
	   $whereeselon1 .= " c_lokasi_unitkerja='".$this->view->kd_lokasi."' and c_eselon_ii='00' and c_eselon_iii='00' and c_eselon_iv='00' and c_eselon_v='00'";
       $this->view->eselon1lst = $this->absensi_serv->getTrUnitkerjalst($whereeselon1,'');
	   if ((!$c_eselon_i) || ($c_eselon_i == 'undefined')) 
	   { 
	      $this->view->c_eselon_i = $this->view->eselon1lst[0]['c_eselon_i']; 
	   }
	   else { $this->view->c_eselon_i = $c_eselon_i; }
	   
	   $whereeselon2 .= " c_lokasi_unitkerja='".$this->view->kd_lokasi."' and c_eselon_i='".$this->view->c_eselon_i."' and c_eselon_iii='00' and c_eselon_iv='00' and c_eselon_v='00'";
       $this->view->eselon2lst = $this->absensi_serv->getTrUnitkerjalst($whereeselon2,'');
	   if ((!$c_eselon_ii) || ($c_eselon_ii == 'undefined')) 
	   { 
	      $this->view->c_eselon_ii = $this->view->eselon1lst[0]['c_eselon_ii']; 
	   }
	   else { $this->view->c_eselon_ii = $c_eselon_ii; }
/*
	   $whereunit = "c_lokasi_unitkerja='".$this->view->kd_lokasi."'";
       $this->view->unitkerjalst = $this->absensi_serv->getTrUnitkerjalst($whereunit,'');
	   if ((!$unitkerja) || ($unitkerja == 'undefined')) { $this->view->unitkerja = $this->view->unitkerjalst[0]['c_satker']; }
	   else { $this->view->unitkerja = $unitkerja; }
	   */
	}



}
?>