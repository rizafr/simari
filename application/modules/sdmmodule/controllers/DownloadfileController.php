<?php
require_once 'Zend/Controller/Action.php';
require_once "service/sdm/excel_reader2.php";
require_once "service/sdm/Sdm_Importdata_Service.php";
require_once "service/sdm/Sdm_Pegawai_Service.php";

class Sdmmodule_DownloadfileController extends Zend_Controller_Action
{
		
public function init() {
		$this->_helper->layout->setLayout('target-column');
	 	$registry = Zend_Registry::getInstance();
	   	$this->view->basePath = $registry->get('basepath');	
		$this->view->leftMenu = $registry->get('leftMenu'); 
		$this->view->dPath = $registry->get('photoPath'); 
		
		$this->pegawai_serv = Sdm_Importdata_Service::getInstance();
		$this->view->pegawaiservis = Sdm_Pegawai_Service::getInstance();
		$ssologin = new Zend_Session_Namespace('ssologin');
		$this->view->c_lokasi_unitkerja=$ssologin->c_lokasi_unitkerja;
		$this->view->c_eselon_i=$ssologin->c_eselon_i;	
    }
public function indexAction()
{
}
public function downloadfilejsAction() 
{
	header('content-type : text/javascript');
	$this->render('downloadfilejs');
}
public function downloadfileAction() 
{
	
}	
	

public function downloadfilepdfAction()
{
	$this->_helper->viewRenderer->setNoRender(true);
	$dfolder = $this->view->dPath."/sdm/templateexcel/userManual_Kepegawaian.pdf";
	$ndokumen = file_get_contents($dfolder);
	header("Content-Type: application/pdf");
	header("Content-Disposition: attachment; filename=userManual_Kepegawaian.pdf");
	echo $ndokumen;
}
	
function getData($string){
	$expesl1 = explode("~",$string);
	$data=$expesl1[0];
	
	return $data;
}
function checkPanjang($string){

}
	
}

?>