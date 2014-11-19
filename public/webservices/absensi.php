<?php
    include "absensi.service.lib.php";
	$user = $_REQUEST['user'];
    $nip = trim($_REQUEST['nip']);
    $tanggal = trim($_REQUEST['tanggal']);
    $jam = trim($_REQUEST['jam']);
    $kodeabsen = trim($_REQUEST['kodeabsen']);
    $kodemnjmn = trim($_REQUEST['kodemnjmn']);
    $terminal = trim($_REQUEST['terminal']);
	$absenMesinIns = array("i_peg_nip"	  => $nip,
                              "d_peg_absensi" => $tanggal,
                              "d_peg_jam"     => $jam,
                              "c_absensi_peg" => $kodeabsen,
                              "c_menejmen"    => $kodemnjmn,
                              "i_term"        => $terminal,
							  "i_entry"		  => $user);
    $iSrvc = new absensiservice();							  
	$AbsensiFound = $iSrvc->foundAbsensiMesin($nip,$tanggal,$jam);
	   //echo "nilai AbsensiFound : ".$AbsensiFound;
	if ($AbsensiFound!="")
	{ echo "Data sudah ada"; }
	else
	{ 
	   $hasilProses = $iSrvc->insAbsensiMesin($absenMesinIns); 
	   echo $hasilProses;
	}
?>