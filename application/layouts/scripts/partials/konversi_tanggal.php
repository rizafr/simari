<?php
function konversi_tanggal($format, $tanggal="now", $bahasa="id"){
 $en=array("Sun","Mon","Tue","Wed","Thu","Fri","Sat","Jan","Feb",
 "Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
 
 $id=array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu",
 "Januari","Pebruari","Maret","April","Mei","Juni","Juli","Agustus","September",
 "Oktober","November","Desember");
 
 // tambahan untuk bahasa prancis
 // sumber http://w.blankon.in/6V
 $fr = array("dimanche","lundi","mardi","mercredi","jeudi","vendredi","samedi",
 "janvier","février","mars","avril","Mei","mai","juillet","aoùt","septembre",
 "octobre","novembre","décembre");
 
 // mengganti kata yang berada pada array en dengan array id, fr (default id)
 return str_replace($en,$$bahasa,date($format,strtotime($tanggal)));
}
 
// //menampilkan tanggal saat ini
// //keluaran Tue, 26 Mar 2013
// echo date("D, j M Y")."<br/>";
 
// //menampilkan tanggal saat ini setelah di konversi
// //keluaran Minggu, 13 Maret 2011
// echo konversi_tanggal("D, j M Y")."<br/>";
 
// //menampilkan bulan saat ini
// //keluaran Maret
// echo konversi_tanggal("M")."<br/>";
 
// //menampilkan hari saat ini
// //keluaran Minggu
// echo konversi_tanggal("D")."<br/>";
 
// //konversi tanggal dari format tanggal di mysql
// //keluaran Jumat, 17 Agustus 1945
// echo konversi_tanggal("D, j M Y","1945-08-17")."<br/>";
 
// //konversi tanggal dari format tanggal di mysql
// //keluaran Jumat
// echo konversi_tanggal("D","1945-08-17")."<br/>";
 
// //konversi tanggal dari format tanggal di mysql
// //keluaran Agustus
// echo konversi_tanggal("M","1945-08-17")."<br/>";
 
// //konversi tanggal dari format dengan bahasa lain
// //keluaran dimanche, 17 avril 1988
// echo konversi_tanggal("D, j M Y","1988-04-17", "fr")."<br/>";
?>