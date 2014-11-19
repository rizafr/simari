<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/sdm_refferensi_Service.php";

class Sdmmodule_ListcombounitkerjaController extends Zend_Controller_Action {

		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu');
		$this->view->photoPath = $registry->get('photoPath');
		$this->reff_serv = sdm_refferensi_Service::getInstance();
		
		$ssologin = new Zend_Session_Namespace('ssologin');
		$this->view->c_lokasi_unitkerja=$ssologin->c_lokasi_unitkerja;
		$this->view->ceseloni=$ssologin->c_eselon_i;	
    }
	
public function indexAction() {}


public function listcombounitkerjaAction() {
	$this->view->lokasiList = $this->reff_serv->getLokasi("");
	
	if($_GET['c_lokasi_unitkerja']){
		$c_lokasi_unitkerja		 = trim($_GET['c_lokasi_unitkerja']);
		$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
		$this->view->c_lokasi_unitkerja = $c_lokasi_unitkerja;
	}
	if($_GET['eseloni']) {
		list($c_eselon_i,$n_eselon_i)	= split(';',trim($_GET['eseloni']));
		$this->view->eseloniiList 		= $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and trim(c_tkt_esl)='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
		$this->view->c_eselon_i 		= trim($_GET['eseloni']);
	}
	if($_GET['eselonii']) {
		list($c_eselon_ii,$n_eselon_ii)	= split(';',trim($_GET['eselonii']));
		$this->view->eseloniiiList 		= $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and trim(c_tkt_esl)='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
		$this->view->c_eselon_ii 		= trim($_GET['eselonii']);
	}
	if($_GET['eseloniii']) {
		list($c_eselon_iii,$n_eselon_iii)= split(';',trim($_GET['eseloniii']));
		$this->view->eselonivList 		= $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and trim(c_tkt_esl)='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
		$this->view->c_eselon_iii 		= trim($_GET['eseloniii']);
	}
	if($_GET['eseloniv']) {
		list($c_eselon_iv,$n_eselon_iv)	= split(';',trim($_GET['eseloniv']));
		$this->view->eselonvList 		= $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii'  and c_eselon_iv='$c_eselon_iv' and trim(c_tkt_esl)='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
		$this->view->c_eselon_iv 		= trim($_GET['eseloniv']);
	}
	
	
}
public function listcombounitkerja2Action() {
	$this->view->lokasiList = $this->reff_serv->getLokasi("");
	
	if($_GET['c_lokasi_unitkerja']){
		$c_lokasi_unitkerja		 = trim($_GET['c_lokasi_unitkerja']);
		$this->view->eseloniList 		= $this->reff_serv->getTrUnitKerjaPngl(" and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
		$this->view->c_lokasi_unitkerja = $c_lokasi_unitkerja;
	}
	if($_GET['eseloni']) {
		list($c_eselon_i,$n_eselon_i)	= split(';',trim($_GET['eseloni']));
		$this->view->eseloniiList 		= $this->reff_serv->getTrUnitKerja(" and c_level ='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_eselon_i='$c_eselon_i' ");
		$this->view->c_eselon_i 		= trim($_GET['eseloni']);
	}
	if($_GET['eselonii']) {
		list($c_eselon_ii,$c_parent,$n_eselon_ii)	= split(';',trim($_GET['eselonii']));
		$this->view->eseloniiiList 		= $this->reff_serv->getTrUnitKerja(" and  c_level ='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_eselon_i='$c_eselon_i'  and c_parent ='$c_parent'");
		$this->view->eselonivList		= $this->reff_serv->getTrUnitKerja(" and c_level ='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_parent ='$c_parent'");
		$this->view->c_eselon_ii 		= trim($_GET['eselonii']);
	}
	if($_GET['eseloniii']) {
		list($c_eselon_ii,$c_eselon_iii,$c_satker,$n_eselon_iii)= split(';',trim($_GET['eseloniii']));
		$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_level ='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_parent ='$c_parent'");
		$this->view->c_eselon_iii 		= trim($_GET['eseloniii']);
	}
	if($_GET['eseloniv']) {
		list($c_eselon_iv,$n_eselon_iv)	= split(';',trim($_GET['eseloniv']));
		$this->view->c_eselon_iv 		= trim($_GET['eseloniv']);
	}
	
}

}
?>