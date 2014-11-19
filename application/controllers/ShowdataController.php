<?php
require_once 'Zend/Controller/Action.php';
require_once "service/portal/Portal_Shoutbox_Service.php";

class ShowdataController extends Zend_Controller_Action
{

    public function init()
    {
	$this->_helper->layout->setLayout('main-layout');	
	$registry = Zend_Registry::getInstance();
	$this->auth = Zend_Auth::getInstance();
	$this->view->basePath = $registry->get('basepath');
	$this->view->baseData = $registry->get('baseData');

	$this->shoutbox_serv = Portal_shoutbox_Service::getInstance();
	$this->view->id= $this->id;
	$this->view->n_userid= $this->n_userid;
	$this->view->n_nama= $this->n_nama;
	
    }

    public function indexAction()
    {
        // action body
	$this->view->shoutboxList = $this->shoutbox_serv->getshoutboxList();	
	$this->render();
	//$shoutboxList = $this->shoutbox_serv->getshoutboxList();
	//echo "xxxxx".$shoutboxList[0]['n_nama'];
	/*$cari = " where c_status=1 ";
	$currentPage=1;
	$numToDisplay=10;
	$orderby=" orderby d_berita desc"; 
	$this->view->beritaPubList = $this->berita_serv->getBeritaList($cari,$currentPage, $numToDisplay,$orderby);	
	*/
    }
/*Action for save mesg from shoutbox*/



}







