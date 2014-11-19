<?php
require_once "../library/fusionchart/FusionCharts.php";
class Rencana_eis {
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
function toChartPagu($caption,$dataStatistik){
				require_once "../library/fusionchart/FusionCharts.php";
				$fusionCharts = new fusionChart();
				$w='80%'; 
				$h='100%';
		$strXML = "<chart animation='1' 
                YAxisName='' 
                showValues='1' 
                showNames='1' 
                numberPrefix='$' 
                formatNumberScale='0' 
                showPercentageValues='1' 
                decimalPrecision='0'
		showLegend='1'
		legendPosition='right' baseFont='Arial' baseFontSize ='10'>";
			$noUrut = 1;
			$warna[1]='FF0000';
			$warna[2]='00FF00';
			$warna[3]='0000FF';
			foreach ($dataStatistik as $key => $val):
				$nmgbkpk  = strtolower(rtrim($val['nmgbkpk']));
				$nmgbkpk  = ucwords($nmgbkpk);
				$jumlah  = $val['jumlah'];
				if ($nmgbkpk){
				$strXML .= "<set label='$nmgbkpk' value='$jumlah'  Color='".$warna[$noUrut]."'/> ";
				}
				$noUrut++;
			endforeach;
		$strXML .= "</chart>";
		$alm=$this->basePath."/charts/Pie2D.swf";
		$grafik = 'Chart';			
		$findchartdir = $fusionCharts->renderChartHTML($alm, '', $strXML, $grafik, 540, 280, false);
		$getlistdir=$findchartdir;
		$getlistdir=$getlistdir;
		return $getlistdir; 
}

 

 function toChartPagub($jmlbuaBP,$jmlbuaBB,$jmlbuaBM,$jmlbadilumBP,$jmlbadilumBB,$jmlbadilumBM,$jmlbadilagBP,$jmlbadilagBB,$jmlbadilagBM,
			$jmlbadimilBP,$jmlbadimilBB,$jmlbadimilBM,$jmlbpppphpBP,$jmlbpppphpBB,$jmlbpppphpBM,$jmlbawasBP,$jmlbawasBB,$jmlbawasBM)
{

$fusionCharts = new fusionChart();    
$strXML = "    
<chart
		yaxisname='Rupiah' xaxisname='' 
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
<category label='Belanja Barang' />
<category label='Belanja Pegawai' />
<category label='Belanja Modal' />
</categories>

    <dataset seriesName='Badilag' color='FF0000' showValues='0'>
        <set value='$jmlbadilagBB' />
        <set value='$jmlbadilagBP' />
        <set value='$jmlbadilagBM' />
    </dataset>

    <dataset seriesName='Badilum' color='00FF00' showValues='0'>
        <set value='$jmlbadilumBB' />
        <set value='$jmlbadilumBP' />
        <set value='$jmlbadilumBM' />
    </dataset>

    <dataset seriesName='BUA' color='0000FF' showValues='0'>
        <set value='$jmlbuaBB' />
        <set value='$jmlbuaBP' />
        <set value='$jmlbuaBM' />
    </dataset>
    <dataset seriesName='Badimiltun' color='FFFF00' showValues='0'>
        <set value='$jmlbadimilBB' />
        <set value='$jmlbadimilBP' />
        <set value='$jmlbadimilBM' />
    </dataset>
    <dataset seriesName='Balitbangkumdil' color='000000' showValues='0'>
        <set value='$jmlbpppphpBB' />
        <set value='$jmlbpppphpBP' />
        <set value='$jmlbpppphpBM' />
    </dataset>  
    <dataset seriesName='Bawas' color='CC00CC' showValues='0'>
        <set value='$jmlbawasBB' />
        <set value='$jmlbawasBP' />
        <set value='$jmlbawasBM' />
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