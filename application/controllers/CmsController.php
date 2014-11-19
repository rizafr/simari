<?php
require_once 'Zend/Controller/Action.php';
require_once "service/sdm/Sdm_Dashboard_Service.php";
require_once "service/sdm/Sdm_Statistik_Service.php";
require_once "service/sdm/Sdm_Monitoring_Service.php";

require_once "service/adm/Adm_Adminuser_Service.php";
require_once "service/adm/Adm_Admaplikasi_Service.php";
class CmsController extends Zend_Controller_Action
{

 public function init() {
        $this->_helper->layout->setLayout('target-column');
        $registry = Zend_Registry::getInstance();
        $this->view->basePath = $registry->get('basepath');
        $this->view->baseData = $registry->get('baseData');       
        $this->view->leftMenu = $registry->get('leftMenu');
       
        $this->dataPerPage = $registry->get('dataPerPage');
       
        //$this->view->photoPath = $registry->get('photoPath');
         
		 $this->statistik_serv = Sdm_Statistik_Service::getInstance();
		 $this->monitoring_serv = Sdm_Monitoring_Service::getInstance();
		 
		 $this->adminuser_serv = Adm_Adminuser_Service::getInstance();
		 $this->admaplikasi_serv = Adm_Admaplikasi_Service::getInstance();
       
        $ssologin = new Zend_Session_Namespace('ssologin');
       
 	 if ($ssologin->userid && $ssologin->n_peg){
		 	$this->userid  			= $ssologin->userid;
			$this->password			= $ssologin->password;
			$this->i_peg_nip  		= $ssologin->i_peg_nip;
			$this->i_peg_nip_new	= $ssologin->i_peg_nip_new;
			$this->n_peg  			= $ssologin->n_peg;
			$this->n_peg_gelardepan = $ssologin->n_peg_gelardepan;
			$this->n_peg_gelarblkg 	= $ssologin->n_peg_gelarblkg;
			$this->c_jabatan 		= $ssologin->c_jabatan;
			$this->c_eselon_i 		= $ssologin->c_eselon_i;
			$this->c_eselon_ii 		= $ssologin->c_eselon_ii;
			$this->c_eselon_iii 	= $ssologin->c_eselon_iii;
			$this->c_eselon_iv 		= $ssologin->c_eselon_iv;
			$this->c_eselon_v 		= $ssologin->c_eselon_v; 
			$this->c_lokasi_unitkerja = $ssologin->c_lokasi_unitkerja; 
			$this->c_satker			= $ssologin->c_satker; 
			$this->n_satker			= $ssologin->n_satker; 
			$this->c_izin			= $ssologin->userIzin; 
			$this->checkotoritas	= $ssologin->checkotoritas; 
		 }
		 
		 $this->view->ssologin = $ssologin;
		//echo "satker = ".$ssologin->c_satker.' '.$ssologin->userid;
		//$this->view->aplikasi = str_replace("/", "", $_SERVER['REQUEST_URI']);
		$urlAplikasiArr = explode("?",str_replace("/", "", $_SERVER['REQUEST_URI']));
		$this->view->aplikasi = $urlAplikasiArr[0]; //str_replace("/", "", $_SERVER['REQUEST_URI']);
		//echo "xxxx= ".$this->view->aplikasi;
    }
    

    public function indexAction()
    {
        // action body
	
		$this->view->userid = $this->userid;
		$this->view->password = $this->password;
		$this->view->checkotoritas 	= $this->checkotoritas;
		
		// ambil list aplikasi
		//----------------------------------
		$dataMasukanAplikasiList = array("pageNumber" => 99,
										"itemPerPage" => 99,
										"kategoriCari" => "semua",
										"katakunciCari" => "",
										"sortBy" => "i_urut_aplikasi",
										"sortOrder" => "asc");
		$this->view->aplikasiList = $this->admaplikasi_serv->aplikasiList($dataMasukanAplikasiList);
		$dataMasukanLogAplikasi = array("userid" 	=> $this->view->userid,
										"cAplikasi"	=> $this->view->aplikasi);
		$this->view->writeToLogAksesaplikasi = $this->admaplikasi_serv->writeToLogAksesaplikasi($dataMasukanLogAplikasi);
		
		
	
	$admaplikasi_serv = Adm_Admaplikasi_Service::getInstance();
	
					  for($a=0;$a<count($this->checkotoritas); $a++){
							$iAplikasi = $this->checkotoritas[$a];
							$dataDetailAplikasi = array("i_aplikasi" => $iAplikasi);
							$detailAplikasi = $admaplikasi_serv->aplikasiDetail($dataDetailAplikasi);
							//echo "detailAplikasi = ".$detailAplikasi['c_aplikasi']."<br>";
							
							$checkotoritasKodeAplikasi[$a] = $detailAplikasi['c_aplikasi'];
					  }
					  //var_dump($checkotoritasKodeAplikasi);
					  $cAplikasiCms = 'cms'; //di db manajemen aplikasi i_aplikasi=8
					  if(in_array($cAplikasiCms, $checkotoritasKodeAplikasi)) $this->_helper->layout->setLayout('cms-layout');
					  else header ("Location: ../main");
	
	
	
    }
		
    




}







