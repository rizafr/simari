<?php 

class tanggal {

  public function formatTanggal($fieldId,$value) {
					$YearsArr=date(Y);
					$val = explode("-",$value);
					$DateArr = Array("01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31");
					$BulanArr = Array("01","02","03","04","05","06","07","08","09","10","11","12");
					$BulanNameArr = Array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
					$event	= "if(($('".$fieldId."1').value)||($('".$fieldId."2').value)||($('".$fieldId."3').value))$('".$fieldId."').value=''+$('".$fieldId."3').value+'-'+$('".$fieldId."2').options[$('".$fieldId."2').selectedIndex].value+'-'+$('".$fieldId."1').options[$('".$fieldId."1').selectedIndex].value+'';else $('".$fieldId."').value=null;";
					$dt = "<select id='".$fieldId."1' name='".$fieldId."1' ".$rd." ".$dd." onChange=\"$event\">
								<option value=''>--</option>";
					for ($i=0;$i<count($DateArr);$i++) {
						if ($val[2] == $DateArr[$i]) $sd = "selected"; else unset($sd);
						$dt .="<option value='".$DateArr[$i]."' ".$sd.">".$DateArr[$i]."</option>";
					}
					$dt .= "</select>";

					$mt = "<select id='".$fieldId."2' name='".$fieldId."2' ".$rd." ".$dm." onChange=\"$event\">
								<option value=''>--</option>";		
					for ($i=0;$i<count($BulanArr);$i++) {
						if ($val[1] == $BulanArr[$i]) $sm = "selected"; else unset($sm);
						$mt .="<option value='".$BulanArr[$i]."' ".$sm.">".$BulanNameArr[$i]."</option>";
					}
					$mt .="</select>";

					$yt = "<select id='".$fieldId."3' name='".$fieldId."3' ".$dy." ".$ry." onChange=\"$event\">
						<option value=''>--</option>";
					for ($i=0;$i<60;$i++) {
						$YearsArr=$YearsArr-1;
						if ($val[0] == $YearsArr) $sy = "selected"; else unset($sy);
						$yt .="<option value='".$YearsArr."' ".$sy.">".$YearsArr."</option>";
					}
					$yt .= "</select>";
					
					//$yt = "<input id='".$fieldId."3' name='".$fieldId."3' type='text' size='4' maxlength='4' value='".$val[0]."' ".$dy." ".$ry."  onKeyUp=\"javascript:checkNumber(this)\">"; 
		
					$h .= $dt."&nbsp;".$mt."&nbsp;".$yt;
					$h .= "<input id='".$fieldId."' name='".$fieldId."' type='hidden' size='10' maxlength='10' class='".$c." validate-date-au' title='".$title."' value='".$value."'>";
	
	return $h;
  }
  public function formatTanggalLahir($fieldId,$value) {
					$YearsArr=date(Y)-17;
					$val = explode("-",$value);
					$DateArr = Array("01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31");
					$BulanArr = Array("01","02","03","04","05","06","07","08","09","10","11","12");
					$BulanNameArr = Array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
					$event	= "if(($('".$fieldId."1').value)||($('".$fieldId."2').value)||($('".$fieldId."3').value))$('".$fieldId."').value=''+$('".$fieldId."3').value+'-'+$('".$fieldId."2').options[$('".$fieldId."2').selectedIndex].value+'-'+$('".$fieldId."1').options[$('".$fieldId."1').selectedIndex].value+'';else $('".$fieldId."').value=null;";
					$dt = "<select id='".$fieldId."1' name='".$fieldId."1' ".$rd." ".$dd." onChange=\"$event\">
								<option value=''>--</option>";
					for ($i=0;$i<count($DateArr);$i++) {
						if ($val[2] == $DateArr[$i]) $sd = "selected"; else unset($sd);
						$dt .="<option value='".$DateArr[$i]."' ".$sd.">".$DateArr[$i]."</option>";
					}
					$dt .= "</select>";

					$mt = "<select id='".$fieldId."2' name='".$fieldId."2' ".$rd." ".$dm." onChange=\"$event\">
								<option value=''>--</option>";		
					for ($i=0;$i<count($BulanArr);$i++) {
						if ($val[1] == $BulanArr[$i]) $sm = "selected"; else unset($sm);
						$mt .="<option value='".$BulanArr[$i]."' ".$sm.">".$BulanNameArr[$i]."</option>";
					}
					$mt .="</select>";

					$yt = "<select id='".$fieldId."3' name='".$fieldId."3' ".$dy." ".$ry." onChange=\"$event\">
						<option value=''>--</option>";
					for ($i=0;$i<60;$i++) {
						$YearsArr=$YearsArr-1;
						if ($val[0] == $YearsArr) $sy = "selected"; else unset($sy);
						$yt .="<option value='".$YearsArr."' ".$sy.">".$YearsArr."</option>";
					}
					$yt .= "</select>";
					
					//$yt = "<input id='".$fieldId."3' name='".$fieldId."3' type='text' size='4' maxlength='4' value='".$val[0]."' ".$dy." ".$ry."  onKeyUp=\"javascript:checkNumber(this)\">"; 
		
					$h .= $dt."&nbsp;".$mt."&nbsp;".$yt;
					$h .= "<input id='".$fieldId."' name='".$fieldId."' type='hidden' size='10' maxlength='10' class='".$c." validate-date-au' title='".$title."' value='".$value."'>";
	
	return $h;
  }
   
  
  
}
?>