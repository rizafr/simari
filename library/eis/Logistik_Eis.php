<?php
require_once "../library/fusionchart/FusionCharts.php";
class Logistik_eis {
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

    
function toChartStock($jmlbuaBahan,$jmlbuaATK,$jmlbuaObat,$jmlbadilumBahan,$jmlbadilumATK,$jmlbadilumObat,
			$jmlbadilagBahan,$jmlbadilagATK,$jmlbadilagObat,$jmlbadimilBahan,$jmlbadimilATK,$jmlbadimilObat,
			$jmlATKppphpBahan,$jmlATKppphpATK,$jmlATKppphpObat,$jmlbawasBahan,$jmlbawasATK,$jmlbawasObat)
{

$fusionCharts = new fusionChart();    
$strXML = "    
<chart
		yaxisname='Jumlah' xaxisname='Barang' 
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
<category label='Bahan' />
<category label='>Alat Kegiatan Kantor' />
<category label='Obat-obatan' />
</categories>

    <dataset seriesName='Badilag' color='FF0000' showValues='0'>
        <set value='$jmlbadilagBahan' />
        <set value='$jmlbadilagATK' />
        <set value='$jmlbadilagObat' />
    </dataset>

    <dataset seriesName='Badilum' color='00FF00' showValues='0'>
        <set value='$jmlbadilumBahan' />
        <set value='$jmlbadilumATK' />
        <set value='$jmlbadilumObat' />
    </dataset>

    <dataset seriesName='BUA' color='0000FF' showValues='0'>
        <set value='$jmlbuaBahan' />
        <set value='$jmlbuaATK' />
        <set value='$jmlbuaObat' />
    </dataset>
    <dataset seriesName='Badimiltun' color='FFFF00' showValues='0'>
        <set value='$jmlbadimilBahan' />
        <set value='$jmlbadimilATK' />
        <set value='$jmlbadimilObat' />
    </dataset>
    <dataset seriesName='Balitbangkumdil' color='000000' showValues='0'>
        <set value='$jmlATKppphpBahan' />
        <set value='$jmlATKppphpATK' />
        <set value='$jmlATKppphpObat' />
    </dataset>  
    <dataset seriesName='Bawas' color='CC00CC' showValues='0'>
        <set value='$jmlbawasBahan' />
        <set value='$jmlbawasATK' />
        <set value='$jmlbawasObat' />
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

}
?>