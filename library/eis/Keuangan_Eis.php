<?php
require_once "../library/fusionchart/FusionCharts.php";
class Keuangan_Eis {
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


function toChartKeuanganTgr(
						$jmlbuatgrpeg,$jmlbuatgrtp,$jmlbuatgrp3,
						$jmlbadilumtgrpeg,$jmlbadilumtgrtp,$jmlbadilumtgrp3,
						$jmlbadilagtgrpeg,$jmlbadilagtgrtp,$jmlbadilagtgrp3,
						$jmlbadimiltgrpeg,$jmlbadimiltgrtp,$jmlbadimiltgrp3,
						$jmlbpppphptgrpeg,$jmlbpppphptgrtp,$jmlbpppphptgrp3,
						$jmlbawastgrpeg,$jmlbawastgrtp,$jmlbawastgrp3) {
       $fusionCharts = new fusionChart();
        $w = '80%';
        $h = '100%';
$strXML = "    
	<chart 
			yaxisname='Jumlah' xaxisname='' 
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
	<category label='TGR Pegawai' />
	<category label='TGR TP' />
	<category label='TGR Pihak III' />
	</categories>

	    <dataset seriesName='BUA' color='FF0000' showValues='0'>
		<set value='$jmlbuatgrpeg' />
		<set value='$jmlbuatgrtp' />
		<set value='$jmlbuatgrp3' />
	    </dataset>

	    <dataset seriesName='Badilum' color='00FF00' showValues='0'>
		<set value='$jmlbadilumtgrpeg' />
		<set value='$jmlbadilumtgrtp' />
		<set value='$jmlbadilumtgrp3' />
	    </dataset>

	    <dataset seriesName='Badilag' color='0000FF' showValues='0'>
		<set value='$jmlbadilagtgrpeg' />
		<set value='$jmlbadilagtgrtp' />
		<set value='$jmlbadilagtgrp3' />
	    </dataset>
	    <dataset seriesName='Badimiltun' color='FFFF00' showValues='0'>
		<set value='$jmlbadimiltgrpeg' />
		<set value='$jmlbadimiltgrtp' />
		<set value='$jmlbadimiltgrp3' />
	    </dataset>
	    <dataset seriesName='Balitbangkumdil' color='000000' showValues='0'>
		<set value='$jmlbpppphptgrpeg' />
		<set value='$jmlbpppphptgrtp' />
		<set value='$jmlbpppphptgrp3' />
	     </dataset>  
	    <dataset seriesName='Bawas' color='CC00CC' showValues='0'>
		<set value='$jmlbawastgrpeg' />
		<set value='$jmlbawastgrtp' />
		<set value='$jmlbawastgrp3' />
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
function toChartKeuangan($jmlbadilag1,$jmlbadilag2,$jmlbadilum1,$jmlbadilum2,$jmlbua1,$jmlbua2,$jmlbadimil1,$jmlbadimil2,$jmlbpppphp1,$jmlbpppphp2,$jmlbawas1,$jmlbawas2) {
       $fusionCharts = new fusionChart();
        $w = '80%';
        $h = '100%';
$strXML = "    
	<chart 
			yaxisname='Jumlah' xaxisname='' 
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
	<category label='Target Penyerapan Anggaran' />
	<category label='Realisasi' />
	</categories>

	    <dataset seriesName='Badilag' color='FF0000' showValues='0'>
		<set value='$jmlbadilag1' />
		<set value='$jmlbadilag2' />
	    </dataset>

	    <dataset seriesName='Badilum' color='00FF00' showValues='0'>
		<set value='$jmlbadilum1' />
		<set value='$jmlbadilum2' />
	    </dataset>

	    <dataset seriesName='BUA' color='0000FF' showValues='0'>
		<set value='$jmlbua1' />
		<set value='$jmlbua2' />
	    </dataset>
	    <dataset seriesName='Badimiltun' color='FFFF00' showValues='0'>
		<set value='$jmlbadimil1' />
		<set value='$jmlbadimil2' />
	    </dataset>
	    <dataset seriesName='Balitbangkumdil' color='000000' showValues='0'>
		<set value='$jmlbpppphp1' />
		<set value='$jmlbpppphp2' />
	    </dataset>  
	    <dataset seriesName='Bawas' color='CC00CC' showValues='0'>
		<set value='$jmlbawas1' />
		<set value='$jmlbawas2' />
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