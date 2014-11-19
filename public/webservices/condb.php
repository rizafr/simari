<?
set_include_path('.' . PATH_SEPARATOR . '../../etc/libs'
                     . PATH_SEPARATOR . '../../etc/data'
	                 . PATH_SEPARATOR . get_include_path());
	 
require_once 'Zend/Loader.php';
require_once 'Zend/Session/Namespace.php';
require_once 'Zend/Auth.php';

Zend_Loader::loadClass('Zend_Registry');
Zend_Loader::loadClass('Zend_Controller_Front');
Zend_Loader::loadClass('Zend_Controller_Router_Rewrite');
Zend_Loader::loadClass('Zend_View');
Zend_Loader::loadClass('Zend_Controller_Router_Route_Module');
Zend_Loader::loadClass('Zend_Config_Ini');
Zend_Loader::loadClass('Zend_Db');
Zend_Loader::loadClass('Zend_Debug');

require_once '../../etc/oa_service_loader.php';

// load configuration
$config = new Zend_Config_Ini('../../etc/config.ini', 'general');
$absensiconf = new Zend_Config_Ini('../../etc/absen.conf', 'general');
$registry = Zend_Registry::getInstance();
$registry->set('config', $config);
$registry->set('absensiconf', $absensiconf);

// setup database
$db = Zend_Db::factory($config->db->adapter, $config->db->config->toArray());
$dbadmin = Zend_Db::factory($config->dbadmin->adapter, $config->dbadmin->config->toArray());
$dbother = Zend_Db::factory($config->dbother->adapter, $config->dbother->config->toArray());
$absendb = Zend_Db::factory($absensiconf->absendb->adapter, $absensiconf->absendb->absensiconf->toArray());
$registry->set('absendb', $absendb);
$registry->set('db', $db);
$registry->set('dbadmin', $dbadmin);
$registry->set('dbother', $dbother);

?>
