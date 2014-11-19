<?php
require_once 'Zend/Controller/Action.php';

class Sdmmodule_PhotoController extends Zend_Controller_Action {

    public function init() {
 	   $registry = Zend_Registry::getInstance();
	   $this->view->basePath = $registry->get('basepath'); 
	   $this->view->dPath = $registry->get('baseData');
	   $this->view->photoPath = $registry->get('photoPath');
   }
	
    public function indexAction() {
	
    }
    public function tampilfotoAction()
	{
	   $valNip = $_REQUEST['f'];
       $this->_helper->viewRenderer->setNoRender(true);
	   $valFile = "data/sdm/photo/".$valNip;
       $foto = file_get_contents($valFile);
       header('Content-Type: image/jpg');
	   echo $foto;
	}

}
?>