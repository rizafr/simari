<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");                          // HTTP/1.0
require ( "ldap.lib.php");
/*
$ref		=$_POST[ref];
$pid		=$_POST[pid];
$ppwd		=$_POST[ppwd];
$cmd		=$_POST[cmd];
$userid		=$_POST[userid];
*/

$ref	= 'kelola'; //$_REQUEST[ref];
$pid	=$_REQUEST[pid];
$ppwd	=$_REQUEST[ppwd];
$cmd	=$_REQUEST[cmd];
$userid	=$_REQUEST[userid];
//print_r($_REQUEST);
//if($ref) 

	include "services.$ref.php";
//else
	//echo "unknown service ref $ref";

?>

