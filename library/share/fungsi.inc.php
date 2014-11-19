<?
class fungsiformat {
public function bulan_name($bln){
 if ($bln < 10) $bln = "0$bln";
   switch($bln){
    case "01":
       $bulan="Januari";
      break;
    case "02":
       $bulan="Februari";
      break;
    case "03":
       $bulan="Maret";
      break;
    case "04":
       $bulan="April";
      break;
    case "05":
       $bulan="Mei";
      break;
    case "06":
       $bulan="Juni";
      break;
    case "07":
       $bulan="Juli";
      break;
    case "08":
       $bulan="Agustus";
      break;
    case "09":
       $bulan="September";
      break;
    case "10":
       $bulan="Oktober";
      break;
    case "11":
       $bulan="November";
      break;
    case "12":
       $bulan="Desember";
      break;
    }
  $result=$bulan;
  return $result;
}//end public function format_bulan

public function hari_name($hr){
  switch($hr){
    case "Sunday":
       $bulan="Minggu";
      break;
    case "Monday":
       $bulan="Senin";
      break;
    case "Thrusday":
       $bulan="Selasa";
      break;
    case "wednesday":
       $bulan="Rabu";
      break;
    case "Tuesday":
       $bulan="Kamis";
      break;
    case "Friday":
       $bulan="Jum'at";
      break;
    case "Saturday":
       $bulan="Sabtu";
      
    }
  $result=$bulan;
  return $result;
}//end public function
function angka_ke_romawi ($angka ) {
  switch ($angka) {
    case 1: return ("I");
    case 2: return ("II");
    case 3: return ("III");
    case 4: return ("IV");
    case 5: return ("V"); // needs to be different than "May"
    case 6: return ("VI");
    case 7: return ("VII");
    case 8: return ("VIII");
    case 9: return ("IX");
    case 10: return ("X");
    case 11: return ("XI");
    case 12: return ("XII");
  }
  return "unknown-angka($angka)";
}
function getharidalamsebulan ($bln) {
  $bln = (strlen($bln) == 1 ? "0$bln" : $bln );
  switch ($bln) {
    case '01': return ('31');
    case '02': return ("28");
    case '03': return ("31");
    case '04': return ("30");
    case '05': return ("31"); // needs to be different than "May"
    case '06': return ("30");
    case '07': return ("31");
    case '08': return ("31");
    case '09': return ("30");
    case 10: return ("31");
    case 11: return ("30");
    case 12: return ("31");
  }
 
}
public function convert_date($tgl) {
	if ($tgl) {
		list($d,$m,$y) = split("-",$tgl);
		return "$y-$m-$d";
	} else {return "";}
}

public function print_date($tgl) {
	if ($tgl) {
		list($y,$m,$d) = split("-",$tgl);
		return "$d-$m-$y";
	} else {return "";}
}
public function print_date2($tgl) {
 list($y,$m,$d) = split("-",$tgl);
 $tgl1 = "$d " .month_name($m). " $y";
 return "$tgl1";

}
public function getcurentdate(){
	list($y,$m,$d) = split(" ",date('Y m d'));
	$tgl1 = "$d " .month_name($m). " $y";
	return "$tgl1";
}
public function getTglAkhir($bulan,$tahun){

$lastday= date('d-m-Y',strtotime('-1 second',strtotime('+1 month',strtotime(date($bulan).'/01/'.date($tahun).' 00:00:00'))));
	 
	return "$lastday";
}


public function date_to_html($str_date) {
	// change from yyyy-mm-dd to dd-mm-yyyy
	$tok = strtok($str_date,"-");
	if($tok) {
		$yy=$tok;
	}
	if($tok = strtok("-")) {
		$mm=$tok;
		if(strlen($mm)==1) $mm='0'.$mm;
	}
	if($tok = strtok("-")) {
		$dd=$tok;
		if(strlen($dd)==1) $dd='0'.$dd;
	}

	if($dd!='' && $mm!='' && $yy!='') $retval=$dd.'-'.month_name($mm).'-'.$yy;
	return($retval);
}
 
public function date_to_sql($str_date) {
	// change from dd-mm-yyyy to yyyy-mm-dd
	//return(substr($str_date,6,4).'-'.substr($str_date,3,2).'-'.substr($str_date,0,2));
	list($tgl,$bln,$thn)=split("-",$str_date);
	return $thn."-".$bln."-".$tgl;
	
}

public function format_angka($angka,$digit_number=2){
        return number_format($angka,$digit_number,",",".");
}
public function angka_to_html($angka,$digit_number=2){
        return number_format($angka,$digit_number,",",".");
}
public function format_angka2($angka,$digit_number=2)
{
        return number_format($angka,$digit_number,",",",");
}
public function angka_to_html1($angka,$digit_number=0){
        return number_format($angka,$digit_number,",",".");
}
public function format_angka_to_sql($angka_str) {
	$retval=str_replace(".","",$angka_str);
	$retval=str_replace(",",".",$retval);
	return $retval;
}
public function angka_to_sql($angka_str) {
	$retval=str_replace(".","",$angka_str);
	$retval=str_replace(",",".",$retval);
	return $retval;
}


public function terbilang ($bilangan,$idr=null) {
## mengembalingan terbilang dari bilangan
	//global $sangka,$sorde,$sribu;
	$sangka = array("","satu","dua","tiga","empat","lima","enam","tujuh","delapan","sembilan");
	$sorde = array("","puluh","ratus");
	$sribu = array("","ribu","juta","miliar","trilyun");
	$terbilang = "";
    $cleanstring = "";
    
    $cleanstring = $bilangan+0;
    
    #print $cleanstring."<--";
    $len = strlen($cleanstring);
    $x = $len;
    $y = 0;
    while ($x>0) {
        $y = substr($cleanstring,0,1);
        $faktor = $x%3;
        $bagi = floor($x/3);
        $orde = (($faktor+5)%3);
        #print "$x $faktor $bagi $orde<br/>";
        $terbilang = trim($terbilang)." ".$sangka[$y];
        if($y!=0) {
            $terbilang = trim($terbilang)." ".$sorde[$orde];
        }
        if ($faktor==1) $terbilang = trim($terbilang)." ".$sribu[$bagi];

        $cleanstring = substr($cleanstring,1);
        $x--;
    }
    #print $terbilang;
    $terganti = array("satu ratus","satu puluh","sepuluh satu");
    $gantinya = array("seratus","sepuluh","sebelas");
    $terbilang = str_replace($terganti,$gantinya,$terbilang);
    for ($z=2;$z<count($sangka);$z++) {
        $terbilang = str_replace("sepuluh ".$sangka[$z],$sangka[$z]." belas",$terbilang);
    }
	if ($idr == '') $terbilang .= ' Rupiah';
	//echo "bil = $bilangan terbilang = $terbilang";
    return $terbilang;
}

public function tofloat2($mydec) {   //tanpa angka di belakang koma
   $len  = strlen($mydec);
   $myfloat = '';
   for($k=0; $k<$len; $k++) {
     $kar[$k] = substr ($mydec,$k,1);
     if($kar[$k]==',')
       $myfloat = $myfloat . ".";
     if($kar[$k]!='.')
       $myfloat = $myfloat . $kar[$k];
     
   }
   return $myfloat;
 }
public function tofloat($mydec) {  // dengan angka di belakang koma
   $len  = strlen($mydec);
   $myfloat = '';
   for($k=0; $k<$len; $k++) {     
     $kar[$k] = substr ($mydec,$k,1);     
     //echo "ch=".$kar[$k]."<br>";     
     if($kar[$k]==','){
       $myfloat = $myfloat . ".";
      }else{
      	if($kar[$k]!='.')
        $myfloat = $myfloat . $kar[$k];
      }
     
     //echo "currval=".$myfloat."<br>";
   }
   return $myfloat;
 }
 
 public function tomoney2($myfloat) {
   if($myfloat) {
    $F   = '';
    $B   = '';
    $M   = '';
    $j   = 0;
    $k   = 0;
    $len = strlen($myfloat);

    for($i=0; $i<=$len; $i++) {
      $char = substr($myfloat,$i,1);
      if($char=='.') {
        $char = ",";
        $dot  = 1;
      }
      if(!$dot) {
        if($char!="-") {
          $F = $F . $char;
          $j++;
        }
        else
          $M = '-';
      } elseif($dot && $k<3) {
        $B = $B . $char;
        $k++;
      }
    }
    if($k==0)
      $B = ",00";
    elseif($k==1)
      $B = $B."0";
    $len = strlen($F);
    if ($len > 3) {
      $mod = $len % 3;
      $temp = "";
      for ($j=0; $j<$len; $j++) {
        $kar[$j] = substr($F,$j,1);
        if ($j==($mod)) {
          if ($mod != 0)
             {$temp = $temp . '.';}
          $mod = $mod + 3;
        }
        $temp = $temp . $kar[$j];
      }
      $F = $temp;
    }
    return $M.$F.$B;
  } elseif($myfloat=="0") {
    return "0,00";
  }
 }
 
 public function tomoney($myfloat) {
   if($myfloat) {
    $F   = '';
    $B   = '';
    $M   = '';
    $j   = 0;
    $k   = 0;
    $len = strlen($myfloat);

    for($i=0; $i<=$len; $i++) {
      $char = substr($myfloat,$i,1);
      if($char=='.') {
        $char = ",";
        $dot  = 1;
      }
      if(!$dot) {
        if($char!="-") {
          $F = $F . $char;
          $j++;
        }
        else
          $M = '-';
      } elseif($dot && $k<3) {
        $B = $B . $char;
        $k++;
      }
    }
    if($k==0)
      $B = "";
    elseif($k==1)
      $B = $B."0";
    $len = strlen($F);
    if ($len > 3) {
      $mod = $len % 3;
      $temp = "";
      for ($j=0; $j<$len; $j++) {
        $kar[$j] = substr($F,$j,1);
        if ($j==($mod)) {
          if ($mod != 0)
             {$temp = $temp . '.';}
          $mod = $mod + 3;
        }
        $temp = $temp . $kar[$j];
      }
      $F = $temp;
    }
    return $M.$F.$B;
  } elseif($myfloat=="0") {
    return "0";
  }
 }
}
