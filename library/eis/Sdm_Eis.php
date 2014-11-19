<?php
require_once "../library/fusionchart/FusionCharts.php";
class Sdm_eis {
    private static $instance;
   
    // A private constructor; prevents direct creation of object
    private function __construct() {
       //echo 'I am constructed';
    }

    // The singleton method
    public static function getInstance() {
       if (!isset(self::$instance)) {
           $c = __CLASS__;
           self::$instance = new $c;
       }

       return self::$instance;
    }

//Chart Usia
function toChartUsia($jmlbadilag25,$jmlbadilag2635,$jmlbadilag3645,$jmlbadilag4655,$jmlbadilag56,$jmlbadilum25,$jmlbadilum2635,$jmlbadilum3645,$jmlbadilum4655,
			$jmlbadilum56,$jmlbua25,$jmlbua2635,$jmlbua3645,$jmlbua4655,$jmlbua56,$jmlbadimil25,$jmlbadimil2635,$jmlbadimil3645,$jmlbadimil4655,
			$jmlbadimil56,$jmlbpppphp25,$jmlbpppphp2635,$jmlbpppphp3645,$jmlbpppphp4655,$jmlbpppphp56,$jmlbawas25,$jmlbawas2635,$jmlbawas3645,$jmlbawas4655,
			$jmlbawas56) {
$fusionCharts = new fusionChart();    
$strXML = "    
<chart
		yaxisname='Jumlah' xaxisname='Usia' 
		showAlternateVGridColor='1' 
		alternateVGridAlpha='10' 
		alternateVGridColor='AFD8F8' 
		numDivLines='4' 
		decimalPrecision='0' 
		canvasBorderThickness='1' 
		canvasBorderColor='114B78' 
		baseFontColor='114B78' 
		hoverCapBorderColor='114B78' 
		hoverCapBgColor='E7EFF6'
		showValues='100' formatNumberScale='0' showBorder='3'
		bgColor='999999,FFFFFF' bgAlpha='10' palette='2' animation='1' numberPrefix='' pieSliceDepth='10' startingAngle='100' 
		legendPosition='left' showLegend='1' baseFont='Arial' baseFontSize ='10' baseFontColor ='000000'>

<categories>
<category label='25' />
<category label='26-35' />
<category label='36-45' />
<category label='46-55' />
<category label='56' />
</categories>

    <dataset seriesName='Badilag' color='FF0000' showValues='0'>
        <set value='$jmlbadilag25' />
        <set value='$jmlbadilag2635' />
        <set value='$jmlbadilag3645' />
        <set value='$jmlbadilag4655' />
        <set value='$jmlbadilag56' />
    </dataset>

    <dataset seriesName='Badilum' color='00FF00' showValues='0'>
        <set value='$jmlbadilum25' />
        <set value='$jmlbadilum2635' />
        <set value='$jmlbadilum3645' />
        <set value='$jmlbadilum4655' />
        <set value='$jmlbadilum56' />
    </dataset>

    <dataset seriesName='BUA' color='0000FF' showValues='0'>
        <set value='$jmlbua25' />
        <set value='$jmlbua2635' />
        <set value='$jmlbua3645' />
        <set value='$jmlbua4655' />
        <set value='$jmlbua56' />
    </dataset>
    <dataset seriesName='Badimiltun' color='FFFF00' showValues='0'>
        <set value='$jmlbadimil25' />
        <set value='$jmlbadimil2635' />
        <set value='$jmlbadimil3645' />
        <set value='$jmlbadimil4655' />
        <set value='$jmlbadimil56' />
    </dataset>
    <dataset seriesName='Balitbangkumdil' color='000000' showValues='0'>
        <set value='$jmlbpppphp25' />
        <set value='$jmlbpppphp2635' />
        <set value='$jmlbpppphp3645' />
        <set value='$jmlbpppphp4655' />
        <set value='$jmlbpppphp56' />
    </dataset>  
    <dataset seriesName='Bawas' color='CC00CC' showValues='0'>
        <set value='$jmlbawas25' />
        <set value='$jmlbawas2635' />
        <set value='$jmlbawas3645' />
        <set value='$jmlbawas4655' />
        <set value='$jmlbawas56' />
    </dataset>   

  <styles>
      <definition>
          <style name='myCaptionFont' type='font' font='Arial' size='14' color='666666' bold='1' underline='1'/>
          <style name='myShadow' type='Shadow' color='999999' angle='45'/>
          <style name='myGlow' type='Glow' color='FF5904'/>
      </definition>
      <application>
          <apply toObject='Caption' styles='myCaptionFont,myShadow' />
          <apply toObject='SubCaption' styles='myShadow' />
          <apply toObject='XAxisName' styles='myGlow' />
          <apply toObject='YAxisName' styles='myGlow' />
      </application>
   </styles>    
</chart>";

		$alm=$this->basePath."/charts/MSColumn3D.swf";
		$grafik = 'Chart';			
		$findchartdir = $fusionCharts->renderChartHTML($alm, '', $strXML, $grafik, 540, 280, false);
		$getlistdir=$findchartdir;
		$getlistdir=$getlistdir;
		return $getlistdir; 
}

    

//Chart Pendidikan
function toChartPendidikan($jmlbadilumsd,$jmlbadilumsmp,$jmlbadilumsma,$jmlbadilumd1,$jmlbadilumd2,$jmlbadilumd3,$jmlbadilumd4,$jmlbadilums1,$jmlbadilums2,$jmlbadilums3,
			$jmlbuasd,$jmlbuasmp,$jmlbuasma,$jmlbuad1,$jmlbuad2,$jmlbuad3,$jmlbuad4,$jmlbuas1,$jmlbuas2,$jmlbuas3,$jmlbadimilsd,$jmlbadimilsmp,$jmlbadimilsma,
			$jmlbadimild1,$jmlbadimild2,$jmlbadimild3,$jmlbadimild4,$jmlbadimils1,$jmlbadimils2,$jmlbadimils3,$jmlbpppphpsd,$jmlbpppphpsmp,$jmlbpppphpsma,
			$jmlbpppphpd1,$jmlbpppphpd2,$jmlbpppphpd3,$jmlbpppphpd4,$jmlbpppphps1,$jmlbpppphps2,$jmlbpppphps3,$jmlbawassd,$jmlbawassmp,$jmlbawassma,
			$jmlbawasd1,$jmlbawasd2,$jmlbawasd3,$jmlbawasd4,$jmlbawass1,$jmlbawass2,$jmlbawass3){
$fusionCharts = new fusionChart();    
$strXML = "    
<chart 
		yaxisname='Jumlah' xaxisname='Pendidikan' 
		showAlternateVGridColor='1' 
		alternateVGridAlpha='10' 
		alternateVGridColor='AFD8F8' 
		numDivLines='4' 
		decimalPrecision='0' 
		canvasBorderThickness='1' 
		canvasBorderColor='114B78' 
		baseFontColor='114B78' 
		hoverCapBorderColor='114B78' 
		hoverCapBgColor='E7EFF6'
		showValues='100' formatNumberScale='0' showBorder='3'
		bgColor='999999,FFFFFF' bgAlpha='10' palette='2' animation='1' numberPrefix='' pieSliceDepth='10' startingAngle='100' 
		legendPosition='left' showLegend='1' baseFont='Arial' baseFontSize ='10' baseFontColor ='000000'>


   
<categories>
<category label='SD' />
<category label='SMP' />
<category label='SMA' />
<category label='D1' />
<category label='D2' />
<category label='D3' />
<category label='D4' />
<category label='S1' />
<category label='S2' />
<category label='S3' />
</categories>

    <dataset seriesName='Badilag' color='FF0000' showValues='0'>
        <set value='10' />
        <set value='10' />
        <set value='15' />
        <set value='80' />
        <set value='10' />
	<set value='15' />
	<set value='70' />
	<set value='20' />
	<set value='40' />
	<set value='20' />
    </dataset>

    <dataset seriesName='Badilum' color='00FF00' showValues='0'>
        <set value='20' />
        <set value='$jmlbadilumsmp' />
        <set value='$jmlbadilumsma' />
        <set value='$jmlbadilumd1' />
        <set value='$jmlbadilumd2' />
	<set value='$jmlbadilumd3' />
	<set value='$jmlbadilumd4' />
	<set value='$jmlbadilums1' />
	<set value='$jmlbadilums2' />
	<set value='$jmlbadilums3' />
    </dataset>

    <dataset seriesName='BUA' color='0000FF' showValues='0'>
        <set value='30' />
        <set value='$jmlbuasmp' />
        <set value='$jmlbuasma' />
        <set value='$jmlbuad1' />
        <set value='$jmlbuad2' />
	<set value='$jmlbuad3' />
	<set value='$jmlbuad4' />
	<set value='$jmlbuas1' />
	<set value='$jmlbuas2' />
	<set value='$jmlbuas3' />
    </dataset>
    <dataset seriesName='Badimiltun' color='FFFF00' showValues='0'>
        <set value='10' />
        <set value='$jmlbadimilsmp' />
        <set value='$jmlbadimilsma' />
        <set value='$jmlbadimild1' />
        <set value='$jmlbadimild2' />
	<set value='$jmlbadimild3' />
	<set value='$jmlbadimild4' />
	<set value='$jmlbadimils1' />
	<set value='$jmlbadimils2' />
	<set value='$jmlbadimils3' />
    </dataset>
    <dataset seriesName='Balitbangkumdil' color='000000' showValues='0'>
        <set value='30' />
        <set value='$jmlbpppphpsmp' />
        <set value='$jmlbpppphpsma' />
        <set value='$jmlbpppphpd1' />
        <set value='$jmlbpppphpd2' />
	 <set value='$jmlbpppphpd3' />
	<set value='$jmlbpppphpd4' />
	<set value='$jmlbpppphps1' />
	<set value='$jmlbpppphps2' /> 
	 <set value='$jmlbpppphps3' />
    </dataset>  
    <dataset seriesName='Bawas' color='CC00CC' showValues='0'>
        <set value='50' />
        <set value='$jmlbawassmp' />
        <set value='$jmlbawassma' />
        <set value='$jmlbawasd1' />
        <set value='$jmlbawasd2' />
	<set value='$jmlbawasd3' />
	<set value='$jmlbawasd4' />
	<set value='$jmlbawass1' />
	<set value='$jmlbawass2' />
	<set value='$jmlbawass3' />
    </dataset>     
    
  <styles>
      <definition>
          <style name='myCaptionFont' type='font' font='Arial' size='14' color='666666' bold='1' underline='1'/>
          <style name='myShadow' type='Shadow' color='999999' angle='45'/>
          <style name='myGlow' type='Glow' color='FF5904'/>
      </definition>
      <application>
          <apply toObject='Caption' styles='myCaptionFont,myShadow' />
          <apply toObject='SubCaption' styles='myShadow' />
          <apply toObject='XAxisName' styles='myGlow' />
          <apply toObject='YAxisName' styles='myGlow' />
      </application>
   </styles>
    
</chart>";

		$alm=$this->basePath."/charts/MSColumn3D.swf";
		$grafik = 'Chart';			
		$findchartdir = $fusionCharts->renderChartHTML($alm, '', $strXML, $grafik, 540, 280, false);
		$getlistdir=$findchartdir;
		$getlistdir=$getlistdir;
		return $getlistdir; 
}
	

//Chart Golongan
function toChartGolongan($jmlbadilaggol1,$jmlbadilaggol2,$jmlbadilaggol3,$jmlbadilaggol4,$jmlbadilumgol1,$jmlbadilumgol2,$jmlbadilumgol3,
			$jmlbadilumgol4,$jmlbuagol1,$jmlbuagol2,$jmlbuagol3,$jmlbuagol4,$jmlbadimilgol1,$jmlbadimilgol2,$jmlbadimilgol3,
			$jmlbadimilgol4,$jmlbpppphpgol1,$jmlbpppphpgol2,$jmlbpppphpgol3,$jmlbpppphpgol4,$jmlbawasgol1,$jmlbawasgol2,$jmlbawasgol3,$jmlbawasgol4){	
$fusionCharts = new fusionChart();    

$strXML = "    
<chart 
		yaxisname='Jumlah' xaxisname='Golongan' 
		showAlternateVGridColor='1' 
		alternateVGridAlpha='10' 
		alternateVGridColor='AFD8F8' 
		numDivLines='4' 
		decimalPrecision='0' 
		canvasBorderThickness='1' 
		canvasBorderColor='114B78' 
		baseFontColor='114B78' 
		hoverCapBorderColor='114B78' 
		hoverCapBgColor='E7EFF6'
		showValues='100' formatNumberScale='0' showBorder='3'
		bgColor='999999,FFFFFF' bgAlpha='10' palette='2' animation='1' numberPrefix='' pieSliceDepth='10' startingAngle='100' 
		legendPosition='left' showLegend='1' baseFont='Arial' baseFontSize ='10' baseFontColor ='000000'>

<categories>
<category label='I' />
<category label='II' />
<category label='III' />
<category label='IV' />
</categories>

    <dataset seriesName='Badilag' color='FF0000' showValues='0'>
        <set value='$jmlbadilaggol1' />
        <set value='$jmlbadilaggol2' />
        <set value='$jmlbadilaggol3' />
        <set value='$jmlbadilaggol4' />
    </dataset>

    <dataset seriesName='Badilum' color='00FF00' showValues='0'>
        <set value='$jmlbadilumgol1' />
        <set value='$jmlbadilumgol2' />
        <set value='$jmlbadilumgol3' />
        <set value='$jmlbadilumgol4' />
    </dataset>

    <dataset seriesName='BUA' color='0000FF' showValues='0'>
        <set value='$jmlbuagol1' />
        <set value='$jmlbuagol2' />
        <set value='$jmlbuagol3' />
        <set value='$jmlbuagol4' />
    </dataset>
    <dataset seriesName='Badimiltun' color='FFFF00' showValues='0'>
        <set value='$jmlbadimilgol1' />
        <set value='$jmlbadimilgol2' />
        <set value='$jmlbadimilgol3' />
        <set value='$jmlbadimilgol4' />
    </dataset>
    <dataset seriesName='Balitbangkumdil' color='000000' showValues='0'>
        <set value='$jmlbpppphpgol1' />
        <set value='$jmlbpppphpgol2' />
        <set value='$jmlbpppphpgol3' />
        <set value='$jmlbpppphpgol4' />
    </dataset>  
    <dataset seriesName='Bawas' color='CC00CC' showValues='0'>
        <set value='$jmlbawasgol1' />
        <set value='$jmlbawasgol2' />
        <set value='$jmlbawasgol3' />
        <set value='$jmlbawasgol4' />
    </dataset>  

  <styles>
      <definition>
          <style name='myCaptionFont' type='font' font='Arial' size='14' color='666666' bold='1' underline='1'/>
          <style name='myShadow' type='Shadow' color='999999' angle='45'/>
          <style name='myGlow' type='Glow' color='FF5904'/>
      </definition>
      <application>
          <apply toObject='Caption' styles='myCaptionFont,myShadow' />
          <apply toObject='SubCaption' styles='myShadow' />
          <apply toObject='XAxisName' styles='myGlow' />
          <apply toObject='YAxisName' styles='myGlow' />
      </application>
   </styles>    
</chart>";

		$alm=$this->basePath."/charts/MSColumn3D.swf";
		$grafik = 'Chart';			
		$findchartdir = $fusionCharts->renderChartHTML($alm, '', $strXML, $grafik, 540, 280, false);
		$getlistdir=$findchartdir;
		$getlistdir=$getlistdir;
		return $getlistdir; 
}

//Chart Pensiun
function toChartPensiun($jmlbadilagpens,$jmlbadilumpens,$jmlbuapens,$jmlbadimilpens,$jmlbpppphppens,$jmlbawaspens){
$fusionCharts = new fusionChart();    
$strXML = "    
<chart 
		yaxisname='Jumlah' xaxisname='Eselon' 
		showAlternateVGridColor='1' 
		alternateVGridAlpha='10' 
		alternateVGridColor='AFD8F8' 
		numDivLines='4' 
		decimalPrecision='0' 
		canvasBorderThickness='1' 
		canvasBorderColor='114B78' 
		baseFontColor='114B78' 
		hoverCapBorderColor='114B78' 
		hoverCapBgColor='E7EFF6'
		showValues='100' formatNumberScale='0' showBorder='3'
		bgColor='999999,FFFFFF' bgAlpha='10' palette='2' animation='1' numberPrefix='' pieSliceDepth='10' startingAngle='100' 
		legendPosition='left' showLegend='1' baseFont='Arial' baseFontSize ='10' baseFontColor ='000000'>

<categories>
<category label='' />
</categories>

    <dataset seriesName='Badilag' color='FF0000' showValues='1'>
        <set value='$jmlbadilagpens' />
    </dataset>

    <dataset seriesName='Badilum' color='00FF00' showValues='1'>
        <set value='$jmlbadilumpens' />
    </dataset>

    <dataset seriesName='BUA' color='0000FF' showValues='1'>
        <set value='$jmlbuapens' />
    </dataset>
    <dataset seriesName='Badimiltun' color='FFFF00' showValues='1'>
        <set value='$jmlbadimilpens' />
    </dataset>
    <dataset seriesName='Balitbangkumdil' color='000000' showValues='1'>
        <set value='$jmlbpppphppens' />
    </dataset>  
    <dataset seriesName='Bawas' color='CC00CC' showValues='1'>
        <set value='$jmlbawaspens' />
    </dataset>   

  <styles>
      <definition>
          <style name='myCaptionFont' type='font' font='Arial' size='14' color='666666' bold='1' underline='1'/>
          <style name='myShadow' type='Shadow' color='999999' angle='45'/>
          <style name='myGlow' type='Glow' color='FF5904'/>
      </definition>
      <application>
          <apply toObject='Caption' styles='myCaptionFont,myShadow' />
          <apply toObject='SubCaption' styles='myShadow' />
          <apply toObject='XAxisName' styles='myGlow' />
          <apply toObject='YAxisName' styles='myGlow' />
      </application>
   </styles>    
</chart>";

		$alm=$this->basePath."/charts/MSColumn3D.swf";
		$grafik = 'Chart';			
		$findchartdir = $fusionCharts->renderChartHTML($alm, '', $strXML, $grafik, 540, 280, false);
		$getlistdir=$findchartdir;
		$getlistdir=$getlistdir;
		return $getlistdir; 
}	


//Chart Naik Golongan
function toChartNaikGol($jmlbadilagnaikgol,$jmlbadilumnaikgol,$jmlbuanaikgol,$jmlbadimilnaikgol,$jmlbpppphpnaikgol,$jmlbawasnaikgol){

	$fusionCharts = new fusionChart();    

$strXML = "    
<chart 
		yaxisname='Jumlah' xaxisname='Eselon' 
		showAlternateVGridColor='1' 
		alternateVGridAlpha='10' 
		alternateVGridColor='AFD8F8' 
		numDivLines='4' 
		decimalPrecision='0' 
		canvasBorderThickness='1' 
		canvasBorderColor='114B78' 
		baseFontColor='114B78' 
		hoverCapBorderColor='114B78' 
		hoverCapBgColor='E7EFF6'
		showValues='100' formatNumberScale='0' showBorder='3'
		bgColor='999999,FFFFFF' bgAlpha='10' palette='2' animation='1' numberPrefix='' pieSliceDepth='10' startingAngle='100' 
		legendPosition='left' showLegend='1' baseFont='Arial' baseFontSize ='10' baseFontColor ='000000'>

<categories>
<category label='' />
</categories>

    <dataset seriesName='Badilag' color='FF0000' showValues='1'>
        <set value='$jmlbadilagnaikgol' />
    </dataset>

    <dataset seriesName='Badilum' color='00FF00' showValues='1'>
        <set value='$jmlbadilumnaikgol' />
    </dataset>

    <dataset seriesName='BUA' color='0000FF' showValues='1'>
        <set value='$jmlbuanaikgol' />
    </dataset>
    <dataset seriesName='Badimiltun' color='FFFF00' showValues='1'>
        <set value='$jmlbadimilnaikgol' />
    </dataset>
    <dataset seriesName='Balitbangkumdil' color='000000' showValues='1'>
        <set value='$jmlbpppphpnaikgol' />
    </dataset>  
    <dataset seriesName='Bawas' color='CC00CC' showValues='1'>
        <set value='$jmlbawasnaikgol' />
    </dataset>  

  <styles>
      <definition>
          <style name='myCaptionFont' type='font' font='Arial' size='14' color='666666' bold='1' underline='1'/>
          <style name='myShadow' type='Shadow' color='999999' angle='45'/>
          <style name='myGlow' type='Glow' color='FF5904'/>
      </definition>
      <application>
          <apply toObject='Caption' styles='myCaptionFont,myShadow' />
          <apply toObject='SubCaption' styles='myShadow' />
          <apply toObject='XAxisName' styles='myGlow' />
          <apply toObject='YAxisName' styles='myGlow' />
      </application>
   </styles>    
</chart>";

		$alm=$this->basePath."/charts/MSColumn3D.swf";
		$grafik = 'Chart';			
		$findchartdir = $fusionCharts->renderChartHTML($alm, '', $strXML, $grafik, 540, 280, false);
		$getlistdir=$findchartdir;
		$getlistdir=$getlistdir;
		return $getlistdir; 
}

//Chart Naik KGB
function toChartKgb($jmlbadilagkgb,$jmlbadilumkgb,$jmlbuakgb,$jmlbadimilkgb,$jmlbpppphpkgb,$jmlbawaskgb){

$fusionCharts = new fusionChart();    
$strXML = "    
<chart caption='Menerima Kenaikan Gaji Berkala 6 Bulan ke Depan' 
		yaxisname='Jumlah' xaxisname='Eselon' 
		showAlternateVGridColor='1' 
		alternateVGridAlpha='10' 
		alternateVGridColor='AFD8F8' 
		numDivLines='4' 
		decimalPrecision='0' 
		canvasBorderThickness='1' 
		canvasBorderColor='114B78' 
		baseFontColor='114B78' 
		hoverCapBorderColor='114B78' 
		hoverCapBgColor='E7EFF6'
		showValues='100' formatNumberScale='0' showBorder='3'
		bgColor='999999,FFFFFF' bgAlpha='10' palette='2' animation='1' numberPrefix='' pieSliceDepth='10' startingAngle='100' 
		legendPosition='left' showLegend='1' baseFont='Arial' baseFontSize ='10' baseFontColor ='000000'>

<categories>
<category label='' />
</categories>

    <dataset seriesName='Badilag' color='FF0000' showValues='1'>
        <set value='$jmlbadilagkgb' />
    </dataset>

    <dataset seriesName='Badilum' color='00FF00' showValues='1'>
        <set value='$jmlbadilumkgb' />
    </dataset>

    <dataset seriesName='BUA' color='0000FF' showValues='1'>
        <set value='$jmlbuakgb' />
    </dataset>
    <dataset seriesName='Badimiltun' color='FFFF00' showValues='1'>
        <set value='$jmlbadimilkgb' />
    </dataset>
    <dataset seriesName='Balitbangkumdil' color='000000' showValues='1'>
        <set value='$jmlbpppphpkgb' />
    </dataset>  
    <dataset seriesName='Bawas' color='CC00CC' showValues='1'>
        <set value='$jmlbawaskgb' />
    </dataset>   

  <styles>
      <definition>
          <style name='myCaptionFont' type='font' font='Arial' size='14' color='666666' bold='1' underline='1'/>
          <style name='myShadow' type='Shadow' color='999999' angle='45'/>
          <style name='myGlow' type='Glow' color='FF5904'/>
      </definition>
      <application>
          <apply toObject='Caption' styles='myCaptionFont,myShadow' />
          <apply toObject='SubCaption' styles='myShadow' />
          <apply toObject='XAxisName' styles='myGlow' />
          <apply toObject='YAxisName' styles='myGlow' />
      </application>
   </styles>    
</chart>";

		$alm=$this->basePath."/charts/MSColumn3D.swf";
		$grafik = 'Chart';			
		$findchartdir = $fusionCharts->renderChartHTML($alm, '', $strXML, $grafik, 540, 280, false);
		$getlistdir=$findchartdir;
		$getlistdir=$getlistdir;
		return $getlistdir; 
}

function userOnline($userOnline){
$fusionCharts = new fusionChart();    
$strXML = "
<chart showAlternateVGridColor='1' 
        alternateVGridAlpha='10' 
        alternateVGridColor='AFD8F8' 
        numDivLines='0' 
        decimalPrecision='0' 
        canvasBorderThickness='1' 
        canvasBorderColor='114B78' 
        baseFontColor='114B78' 
        hoverCapBorderColor='114B78' 
        hoverCapBgColor='E7EFF6'>";
	 $jml=count($userOnline);
	 $warna=array('AFD8F0','F6BD0F','8BBA00','A66EDD','F984A1','455d00','BA008A','8B00BA');
	for ($i=0; $i<$jml; $i++)
        {
		
		$nilai = (string)$userOnline[$i]['user_online'];
		$n_aplikasi = (string)$userOnline[$i]['n_aplikasi'];
		$n_aplikasi=str_replace("Sistem Informasi", "", $n_aplikasi); 
		$n_aplikasi=str_replace("Perencanaan dan Organisasi", "Perencanaan", $n_aplikasi);
		$n_aplikasi=str_replace("Administrasi ", "Adm. ", $n_aplikasi); 		
		//if($nilai){ 
		$strXML = $strXML." <set name='$n_aplikasi' value='$nilai' color='$warna[$i]'/>";
		//}
	}

$strXML = $strXML."</chart>";

	$alm=$this->basePath."/charts/Bar2D.swf";
	$grafik = 'Chart';			
	$findchartdir = $fusionCharts->renderChartHTML($alm, '', $strXML, $grafik, 340, 200, false);
	$getlistdir=$findchartdir;
	$getlistdir=$getlistdir;
	return $getlistdir; 
}

}
?>