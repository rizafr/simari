<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
   

class Sdm_PenilaianController extends Zend_Controller_Action {
	private $cv_serv;
	private $pegawai_serv;
	
public function init() {
 	   $registry = Zend_Registry::getInstance();
	   $this->view->dPath = $registry->get('baseData');
	   $this->view->basePath = $registry->get('basepath'); 
 	   $ssogroup = new Zend_Session_Namespace('ssogroup');
	   $auth        = Zend_Auth::getInstance();
       $this->id    = $auth->getIdentity();

    }
  
public function indexAction() {
	
    }
	
public function penilaianjsAction() {
	header('content-type : text/javascript');
	echo $this->render('penilaianjs');	
}

public function penilaianAction() {
}
public function listpenilaianAction() {
	 $this->view->numToDisplay=1;
	 $this->view->currentPage=0;
	 $this->view->totalpegawaiList=0;
}

	
}
?>