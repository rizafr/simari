<?php
require_once 'Zend/Controller/Action.php';
// require_once "service/sdm/Sdm_Pegawai_Service.php";
// require_once "service/sdm/Sdm_Cv_Service.php";
require_once "service/sdm/Sdm_Absensi_Service.php";
// require_once "service/sdm/Sdm_Cuti_Service.php";
// require_once "service/sdm/Sdm_Aktifitas_Service.php";
require_once 'Zend/Auth.php';
   

class Sdm_AbsensiController extends Zend_Controller_Action {
	private $cv_serv;
	private $pegawai_serv;
	
    public function init() {
 	   $registry = Zend_Registry::getInstance();
	   $this->view->dPath = $registry->get('baseData');
	   $this->view->basePath = $registry->get('basepath'); 
 	   $ssogroup = new Zend_Session_Namespace('ssogroup');
	   // $this->sdm_cv_serv = Sdm_Cv_Service::getInstance();
	   // $this->sdm_peg_serv = Sdm_Pegawai_Service::getInstance();
	   $this->sdm_absen_serv = Sdm_Absensi_Service::getInstance();
	   // $this->sdm_cuti_serv = Sdm_Cuti_Service::getInstance();
 	   // $this->sdm_aktif_serv = Sdm_Aktifitas_Service::getInstance();
	   $auth        = Zend_Auth::getInstance();
       $this->id    = $auth->getIdentity();

    }
  
    public function indexAction() {
	
    }
	
	public function aktifitasjsAction() {
		header('content-type : text/javascript');
		echo $this->render('aktifitasjs');	
	}
//========================DATA SEARCH========================================	

    public function absensisearchAction() {

     
	}
    public function dataijinAction() {
	 $this->view->numToDisplay=1;
	 $this->view->currentPage=0;
	 $this->view->totalpegawaiList=0;				
	}
    public function tambahijinAction() {
	 $this->view->numToDisplay=1;
	 $this->view->currentPage=0;
	 $this->view->totalpegawaiList=0;				
	}	
		
    public function dataabsensiAction() {
	 $this->view->numToDisplay=1;
	 $this->view->currentPage=0;
	 $this->view->totalpegawaiList=0;		
	}	
	

   public function absensipegawaiAction()
   {
	 $this->view->numToDisplay=1;
	 $this->view->currentPage=0;
	 $this->view->totalpegawaiList=0;
   }
   public function harikerjajsAction()
   {
	  header('content-type : text/javascript');
	  $this->render('harikerjajs');
   }

    public function harikerjaAction()
    {
	   $thnSekarang = $_REQUEST['thnHariKerja'];
	   $blnSekarang = $_REQUEST['blnHariKerja'];
	   if ($thnSekarang=="") $thnSekarang = date("Y");
	   if ($blnSekarang=="") $blnSekarang = date("m");
		$this->view->thnSekarang = $thnSekarang;
		$this->view->blnSekarang = $blnSekarang;
	   $this->view->srviceE = $this->sdm_absen_serv;
    }	
     public function listpegabsensiAction()
    {
	 $this->view->numToDisplay=1;
	 $this->view->currentPage=0;
	 $this->view->totalpegawaiList=0;
    }
	
}
?>