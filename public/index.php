<?php
// // Define path to application directory
// defined('ZEND_LIBRARY_PATH')
// || define('ZEND_LIBRARY_PATH', 'D:/Project/htdocs/kecamatan/library');

// Define path to application directory
defined('ZEND_LIBRARY_PATH')
|| define('ZEND_LIBRARY_PATH', 'C:/xampp/htdocs/kecamatan/library');

/// tambahan jika pdf nggak bisa baca fontpath
defined('FPDF_FONTPATH')
|| define('FPDF_FONTPATH', 'D:/Project/htdocs/kecamatan/library/fpdf/font/');


// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
//echo APPLICATION_PATH; exit();
// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    ZEND_LIBRARY_PATH,
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Loader/Autoloader.php';
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

			
$bootstrap = $application->getBootstrap();
$resource = $bootstrap->getPluginResource('db');
$db = $resource->getDbAdapter();
Zend_Registry::set('db', $db);

$application->bootstrap()
            ->run();			