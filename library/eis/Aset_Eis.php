<?php
require_once "../library/fusionchart/FusionCharts.php";
class Aset_eis {
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

//Chart Pagu1
function toChartAset1($caption,$jml25,$jml2635,$jmlpersediaan,$jmlperalatandanmesin,$jmljalan,$jmlatl,$jmlkdp,$jmlatb,$tahun){
	$fusionCharts = new fusionChart();
 	$strXML = "	
	<chart 
		animation='1' 
                YAxisName='' 
                showValues='1' 
                showNames='1' 
                numberPrefix='$' 
                formatNumberScale='0' 
                showPercentageValues='1' 
                decimalPrecision='0'
		showLegend='1'
		legendPosition='right' baseFont='Arial' baseFontSize ='10'>

		<set label='Persediaan' value='$jmlpersediaan' color='AFD8F8'/> 
		<set label='Tanah' value='$jml25' color='F6BD0F'/> 
		<set label='Gedung dan Bangunan' value='$jmlperalatandanmesin' color='8BBA00'/> 
		<set label='Jalan, Irigasi dan Jaringan' value='$jmljalan' color='FF8E46'/> 
		<set label='Aset Tetap Lainnya' value='$jmlatl' color='008E8E'/> 
		<set label='Kontruksi Dalam Pengerjaan' value='$jmlkdp' color='B9D4F9'/> 
		<set label='Aset Tak Berwujud' value='$jmlatb' color='333366'/> 

	</chart>
  <styles>
      <definition>
          <style name='myCaptionFont' type='font' font='Arial' size='14' color='666666' bold='1' underline='1'/>
          <style name='myShadow' type='Shadow' color='999999' angle='45'/>
          <style name='myGlow' type='Glow' color='FF5904'/>
      </definition>
      <application>
          <apply toObject='caption' styles='myCaptionFont,myShadow' />
          <apply toObject='SubCaption' styles='myShadow' />
          <apply toObject='XAxisName' styles='myGlow' />
          <apply toObject='YAxisName' styles='myGlow' />
      </application>
   </styles> 
	"; 

			
	$alm=$this->basePath."/charts/Pie2D.swf";
	$grafik = 'Chart';			
	$findchartdir = $fusionCharts->renderChartHTML($alm, '', $strXML, $grafik, 540, 250, false);
	$getlistdir=$findchartdir;
	$getlistdir=$getlistdir;
	return $getlistdir; 
}

function toChartAsetb($jmlbuaTanah,$jmlbuaPersediaan,$jmlbuaperalatandanmesin,$jmlbuaJalan,$jmlbuaAsetTetapLainnya,$jmlbuaKDP,$jmlbuaATB,				
						$jmlbadilumTanah,$jmlbadilumPersediaan,$jmlbadilumperalatandanmesin,$jmlbadilumJalan,$jmlbadilumAsetTetapLainnya,$jmlbadilumKDP,$jmlbadilumATB,
						$jmlbadilagTanah,$jmlbadilagPersediaan,$jmlbadilagperalatandanmesin,$jmlbadilagJalan,$jmlbadilagAsetTetapLainnya,$jmlbadilagKDP,$jmlbadilagATB,
						$jmlbadimilTanah,$jmlbadimilPersediaan,$jmlbadimilperalatandanmesin,$jmlbadimilJalan,$jmlbadimilAsetTetapLainnya,$jmlbadimilKDP,$jmlbadimilATB,
						$jmlbpppphpTanah,$jmlbpppphpPersediaan,$jmlbpppphpperalatandanmesin,$jmlbpppphpJalan,$jmlbpppphpAsetTetapLainnya,$jmlbpppphpKDP,$jmlbpppphpATB,
						$jmlbawasTanah,$jmlbawasPersediaan,$jmlbawasperalatandanmesin,$jmlbawasJalan,$jmlbawasAsetTetapLainnya,$jmlbawasKDP,$jmlbawasATB)
{

$fusionCharts = new fusionChart();    
$strXML = "    
<chart
		yaxisname='Rupiah' xaxisname='Aset' 
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
<category label='Persediaan' />
<category label='Tanah' />
<category label='Peralatan Mesin' />
<category label='Gedung Bangunan' />
<category label='Jalan Irigasi Jaringan' />
<category label='Aset Tetap Lainnya' />
<category label='Konstruksi Dalam Pengerjaan' />
<category label='Aset Tak Berwujud' />
</categories>

    <dataset seriesName='Badilag' color='FF0000' showValues='0'>
        <set value='$jmlbadilagPersediaan' />
        <set value='$jmlbadilagTanah' />
        <set value='$jmlbadilagperalatandanmesin' />
	<set value='$jmlbadilagGB' />
        <set value='$jmlbadilagJalan' />
        <set value='$jmlbadilagAsetTetapLainnya' />
	<set value='$jmlbadilagKDP' />
	<set value='$jmlbadilagATB' />
    </dataset>

    <dataset seriesName='Badilum' color='00FF00' showValues='0'>
        <set value='$jmlbadilumPersediaan' />
        <set value='$jmlbadilumTanah' />
        <set value='$jmlbadilumperalatandanmesin' />
        <set value='$jmlbadilumJalan' />
        <set value='$jmlbadilumAsetTetapLainnya' />
	<set value='$jmlbadilumKDP' />
	<set value='$jmlbadilumATB' />
    </dataset>

    <dataset seriesName='BUA' color='0000FF' showValues='0'>
        <set value='$jmlbuaPersediaan' />
        <set value='$jmlbuaTanah' />
        <set value='$jmlbuaperalatandanmesin' />
        <set value='$jmlbuaJalan' />
        <set value='$jmlbuaAsetTetapLainnya' />
	<set value='$jmlbuaKDP' />
	<set value='$jmlbuaATB' />
    </dataset>
    <dataset seriesName='Badimiltun' color='FFFF00' showValues='0'>
        <set value='$jmlbadimilPersediaan' />
        <set value='$jmlbadimilTanah' />
        <set value='$jmlbadimilperalatandanmesin' />
        <set value='$jmlbadimilJalan' />
        <set value='$jmlbadimilAsetTetapLainnya' />
	<set value='$jmlbadimilKDP' />
	<set value='$jmlbadimilATB' />
    </dataset>
    <dataset seriesName='Balitbangkumdil' color='000000' showValues='0'>
        <set value='$jmlbpppphpPersediaan' />
        <set value='$jmlbpppphpTanah' />
        <set value='$jmlbpppphpperalatandanmesin' />
        <set value='$jmlbpppphpJalan' />
        <set value='$jmlbpppphpAsetTetapLainnya' />
	<set value='$jmlbpppphpKDP' />
	<set value='$jmlbpppphpATB' />
    </dataset>  
    <dataset seriesName='Bawas' color='CC00CC' showValues='0'>
        <set value='$jmlbawasPersediaan' />
        <set value='$jmlbawasTanah' />
        <set value='$jmlbawasperalatandanmesin' />
        <set value='$jmlbawasJalan' />
        <set value='$jmlbawasAsetTetapLainnya' />
	<set value='$jmlbawasKDP' />
	<set value='$jmlbawasATB' />
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
		$findchartdir = $fusionCharts->renderChartHTML($alm, '', $strXML, $grafik, 640, 280, false);
		$getlistdir=$findchartdir;
		$getlistdir=$getlistdir;
		return $getlistdir; 
}						
 

}
?>