<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
        protected function _initApp() {
		            $autoloader = Zend_Loader_Autoloader::getInstance();
					Zend_Registry::set('basepath','');
					Zend_Registry::set('baseData','../public/data');
					Zend_Registry::set('bDataLogistik','../data');
					
                                  Zend_Registry::set('baseUrl','');
					Zend_Registry::set('photoPath','../library/data');
					Zend_Registry::set('baseDataUpload','../public/data');
					Zend_Registry::set('leftMenu', '../library/leftmenu');
			              /* $dbkuarr = array('host'     => '127.0.0.1',    
	                                             'username' => 'root',    
					                  'password' => '',    
					                  'dbname'   => 'SIAP'); */
									  
					/*$dbkuarr = array('host'     => '192.168.10.45',    
	                                             'username' => 'integrasi',    
					                  'password' => '1nt3gras1',    
					                  'dbname'   => 'webperkaraexcel');
									  
					Zend_Registry::set('dbku', $dbkuarr);*/
					Zend_Registry::set('dataPerPage','10');
	
		}
		
		protected function _initSession() {
			Zend_Session::start();
			return ;
		}
}

