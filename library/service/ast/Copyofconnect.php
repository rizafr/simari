<?php
$ip = $_SERVER['REMOTE_ADDR'];
$hostmysql = $ip.":3310";//"localhost";
//$hostmysql = "localhost:3310";//"localhost";
//$username = "root";
//$username = "sadmin";
$username = "simari";
$password = "simari";
//$database = "simak";
$database = "dbbmn10";
$conn = mysql_connect($hostmysql,$username,$password);
if (!$conn) die ("Gagal Melakukan Koneksi");
mysql_select_db($database,$conn) or die ("Database Tidak Diketemukan di Server");
?>