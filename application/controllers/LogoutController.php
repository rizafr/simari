<?php
require_once "service/adm/Adm_Adminuser_Service.php";
require_once "service/adm/Adm_Admaplikasi_Service.php";

class LogoutController extends Zend_Controller_Action
{

    public function init()
    {
        $this->admaplikasi_serv = Adm_Admaplikasi_Service::getInstance();
    }

    public function indexAction()
    {
		//$this->sso_serv->emptyIpLogin($this->user);
		Zend_Session::destroy(true);
		//Zend_Auth::getInstance()->clearIdentity();
        //hapus log akses aplikasi
		//------------------------------
		
		$this->view->deleteLogAksesaplikasi = $this->admaplikasi_serv->deleteLogAksesaplikasi($dataMasukan);
		
		$this->_helper->layout->setLayout('login-layout');	
    }

	
	public function mainAction()
    {
        // action body
		
    }




}







