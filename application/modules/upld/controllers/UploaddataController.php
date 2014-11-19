<?php
require_once 'Zend/Controller/Action.php';
class Upld_UploaddataController extends Zend_Controller_Action {

public function init() {
	$this->_helper->layout->setLayout('target-column');
 	$registry = Zend_Registry::getInstance();
	$this->view->basePath = $registry->get('basepath'); 
	$this->view->dPath = $registry->get('baseData');
	$this->view->photoPath = $registry->get('photoPath');
}
	
public function indexAction() {
	
}

	
public function getphotoAction()
	{
	   $par = $_REQUEST['f'];
       $this->_helper->viewRenderer->setNoRender(true);
	   $valFile = $this->view->photoPath."/sdm/photo/".$par;
       $getPhoto = file_get_contents($valFile);
       header('Content-Type: image/jpg');
	   echo $getPhoto;
	}
	
public function getphotoolAction()
	{
		$par = $_REQUEST['f'];
		$this->_helper->viewRenderer->setNoRender(true);
		$valFile = $this->view->photoPath."/sdm/online/".$par;
		$getPhoto = file_get_contents($valFile);
		header('Content-Type: image/jpg');
		echo $getPhoto;
	}
  
}
?>