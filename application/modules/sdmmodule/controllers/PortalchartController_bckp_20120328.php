<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/Sdm_Statistik_Service.php";

class Sdmmodule_PortalchartController extends Zend_Controller_Action {

		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->auth = Zend_Auth::getInstance();
		$this->view->basePath = $registry->get('basepath');
		$this->view->baseData = $registry->get('baseData');
		$this->statistik_serv = Sdm_Statistik_Service::getInstance();
    }
	
	public function indexAction() {
	}
	public function chartgolonganAction() {
	
		$this->golongan('a');	
	}
	
	public function chartgolonganbAction() {
	
		$this->golongan('b');	
	}
		
	

private function golongan($par)
{
	// $jmlgol1 = $this->statistik_serv->getCountData(" $cari and c_golongan in ('20','21','22','23') and c_status_kepegawaian in('1','2') ");
	// $jmlgol2 = $this->statistik_serv->getCountData("$cari and c_golongan in ('14','15','16','17') and c_status_kepegawaian in('1','2') ");
	// $jmlgol3 = $this->statistik_serv->getCountData("$cari and c_golongan in ('08','09','10','11') and c_status_kepegawaian in('1','2') ");
	// $jmlgol4 = $this->statistik_serv->getCountData("$cari and c_golongan in ('03','04','05','06','07') and c_status_kepegawaian in('1','2') ");
	
	$jmlgol1 = $this->statistik_serv->getCountData(" $cari and c_golongan in ('20','21','22','23')");
	$jmlgol2 = $this->statistik_serv->getCountData("$cari and c_golongan in ('14','15','16','17')");
	$jmlgol3 = $this->statistik_serv->getCountData("$cari and c_golongan in ('08','09','10','11')");
	$jmlgol4 = $this->statistik_serv->getCountData("$cari and c_golongan in ('03','04','05','06','07')");	
		
	$this->view->jmlgol1=$jmlgol1;
	$this->view->jmlgol2=$jmlgol2;
	$this->view->jmlgol3=$jmlgol3;
	$this->view->jmlgol4=$jmlgol4;
	if($par=='b'){
	$this->view->chartgol=$this->toChartGol('',$jmlgol1,$jmlgol2,$jmlgol3,$jmlgol4);
	}
	else{
	$this->view->chartgol=$this->toChartGol('Komposisi Golongan',$jmlgol1,$jmlgol2,$jmlgol3,$jmlgol4);
	}

}
function toChartGol($caption,$jmlgol1,$jmlgol2,$jmlgol3,$jmlgol4){
				require_once "../library/fusionchart/FusionCharts.php";
				$fusionCharts = new fusionChart();
				$w='80%'; 
				$h='100%';
		$strXML = "<chart caption='$caption' xAxisName='Month' yAxisName='Units' showValues='0' formatNumberScale='0' showBorder='3'
		bgColor='999999,FFFFFF' bgAlpha='10' palette='2' animation='1' numberPrefix='$' pieSliceDepth='10' startingAngle='100' 
		legendPosition='left' showLegend='1' baseFont='Arial' baseFontSize ='10' baseFontColor ='000000' >";
			
			if ($jmlgol1){$strXML .= "<set label=' I' value='$jmlgol1'  Color='FF0000'/> ";}			
			if ($jmlgol2){$strXML .= "<set label='II' value='$jmlgol2'   Color='FFFF00'/>";}  
			if ($jmlgol3){$strXML .= "<set label='III' value='$jmlgol3'  Color='009900' />";}
			if ($jmlgol4){$strXML .= "<set label='IV' value='$jmlgol4'   Color='333366'/>";}
			
		$strXML .="<styles>
				      <definition>
					<style name='myCaptionFont' type='font' font='Arial' size='14' color='666666' bold='2' underline='1'/>
					<style name='myShadow' type='Shadow' color='999999' angle='45'/>
					<style name='myGlow' type='Glow' color='FF5904'/>
					<style name='myAnim' type='animation' param='_alpha' start='0' duration='1'/>	  
				      </definition>	
					<application>
						<apply toObject='CAPTION' styles='myCaptionFont,myShadow'/>
						 <apply toObject='Legend' styles='myAnim' />
						 <apply toObject='XAxisName' styles='myGlow' />
						<apply toObject='YAxisName' styles='myGlow' />
					</application>
				  </styles>";
				  
		$strXML .= "</chart>";
		$alm=$this->basePath."/charts/Pie3D.swf";
		$grafik = 'Chart';			
		$findchartdir = $fusionCharts->renderChartHTML($alm,'', $strXML, $grafik,500,200, false);
		$getlistdir=$findchartdir;
		$getlistdir=$getlistdir;
		return $getlistdir; 
}	
	public function chartpendidikanAction() {
	
		$this->pendidikan('a');	
	}
	public function chartpendidikanbAction() {
	
		$this->pendidikan('b');	
	}	
		
	private function pendidikan($par)
	{
		$cari="";
		$jmlsd = $this->statistik_serv->getCountData(" $cari and c_pend ='01' ");
		$jmlsmp = $this->statistik_serv->getCountData("$cari and c_pend ='02' ");
		$jmlsma = $this->statistik_serv->getCountData("$cari and c_pend ='03'");
		$jmld1 = $this->statistik_serv->getCountData("$cari and c_pend ='04'");
		$jmld2 = $this->statistik_serv->getCountData("$cari and c_pend ='05'");
		$jmld3 = $this->statistik_serv->getCountData(" $cari and c_pend ='06'");
		$jmld4 = $this->statistik_serv->getCountData(" $cari and c_pend ='07'");
		$jmls1 = $this->statistik_serv->getCountData("$cari and c_pend ='08'");
		$jmls2 = $this->statistik_serv->getCountData("$cari and c_pend ='09'");
		$jmls3 = $this->statistik_serv->getCountData("$cari and c_pend ='10'");	
		
		$this->view->jmlsd=$jmlsd;
		$this->view->jmlsmp=$jmlsmp;
		$this->view->jmlsma=$jmlsma;
		$this->view->jmld1=$jmld1;
		$this->view->jmld2=$jmld2;
		$this->view->jmld3=$jmld3;
		$this->view->jmld4=$jmld4;
		$this->view->jmls1=$jmls1;
		$this->view->jmls2=$jmls2;
		$this->view->jmls3=$jmls3;
		if ($par=='b'){
		$this->view->chartpend=$this->toChartEdu('',$jmlsd,$jmlsmp,$jmlsma,$jmld1,$jmld2,$jmld3,$jmld4,$jmls1,$jmls2,$jmls3);
		}
		else{
		$this->view->chartpend=$this->toChartEdu('Komposisi Pendidikan',$jmlsd,$jmlsmp,$jmlsma,$jmld1,$jmld2,$jmld3,$jmld4,$jmls1,$jmls2,$jmls3);
		}

	}
function toChartEdu($caption,$jmlsd,$jmlsmp,$jmlsma,$jmld1,$jmld2,$jmld3,$jmld4,$jmls1,$jmls2,$jmls3){
				require_once "../library/fusionchart/FusionCharts.php";
				$fusionCharts = new fusionChart();
				$w='80%'; 
				$h='100%';
		$strXML = "<chart caption='$caption' xAxisName='Month' yAxisName='Units' showValues='0' formatNumberScale='0' showBorder='3'
		bgColor='999999,FFFFFF' bgAlpha='10' palette='2' animation='1' numberPrefix='$' pieSliceDepth='10' startingAngle='100' 
		legendPosition='left' showLegend='1' baseFont='Arial' baseFontSize ='10' baseFontColor ='000000' >";
			
			if ($jmlsd){$strXML .= "<set label=' SD' value='$jmlsd'  Color='0000FF' /> ";}			
			if ($jmlsmp){$strXML .= "<set label='SMP' value='$jmlsmp'   Color='FF3300' />";}  
			if ($jmlsma){$strXML .= "<set label='SMA' value='$jmlsma'  Color='993300' />";}
			if ($jmld1){$strXML .= "<set label='D1' value='$jmld1'   Color='663333' />";}
			if ($jmld2){$strXML .= "<set label='D2' value='$jmld2'   Color='009900' />";}
			if ($jmld3){$strXML .= "<set label='D3' value='$jmld3'   Color='003399' />";}
			if ($jmld3){$strXML .= "<set label='D4' value='$jmld4'   Color='000033' />";}
			if ($jmls1){$strXML .= "<set label='S1' value='$jmls1'   Color='FF0000' />";}
			if ($jmls2){$strXML .= "<set label='S2' value='$jmls2'   Color='550000' />";}
			if ($jmls3){$strXML .= "<set label='S3' value='$jmls3'   Color='FF66CC' />";}
			
		$strXML .="<styles>
				      <definition>
					<style name='myCaptionFont' type='font' font='Arial' size='14' color='666666' bold='2' underline='1'/>
					<style name='myShadow' type='Shadow' color='999999' angle='45'/>
					<style name='myGlow' type='Glow' color='FF5904'/>
					<style name='myAnim' type='animation' param='_alpha' start='0' duration='1'/>	  
				      </definition>	
					<application>
						<apply toObject='CAPTION' styles='myCaptionFont,myShadow'/>
						 <apply toObject='Legend' styles='myAnim' />
						 <apply toObject='XAxisName' styles='myGlow' />
						<apply toObject='YAxisName' styles='myGlow' />
					</application>
				  </styles>";
				  
		$strXML .= "</chart>";
		$alm=$this->basePath."/charts/Pie3D.swf";
		$grafik = 'Chart';			
		$findchartdir = $fusionCharts->renderChartHTML($alm, '', $strXML, $grafik, 430, 250, false);
		$getlistdir=$findchartdir;
		$getlistdir=$getlistdir;
		return $getlistdir; 
}	
	public function chartusiaAction() {
	
		$this->usia('a');	
	}	

	public function chartusiabAction() {
	
		$this->usia('b');	
	}
	private function usia($par) 
	{
		$cari="";		
		$jml25 = $this->statistik_serv->getCountData(" $cari and (EXTRACT(years from AGE(now(), d_peg_lahir))<= 25)  ");
		$jml2635 = $this->statistik_serv->getCountData("$cari and ((EXTRACT(years from AGE(now(), d_peg_lahir))>= 26) and (EXTRACT(years from AGE(now(), d_peg_lahir))<= 35))  ");
		$jml3645 = $this->statistik_serv->getCountData("$cari and ((EXTRACT(years from AGE(now(), d_peg_lahir))>= 36) and (EXTRACT(years from AGE(now(), d_peg_lahir))<= 45))  ");
		$jml4655 = $this->statistik_serv->getCountData("$cari and ((EXTRACT(years from AGE(now(), d_peg_lahir))>= 46) and (EXTRACT(years from AGE(now(), d_peg_lahir))<= 55))  ");
		$jml56 = $this->statistik_serv->getCountData("$cari and (EXTRACT(years from AGE(now(), d_peg_lahir))>= 56)  ");

		$this->view->jml25=$jml25;
		$this->view->jml2635=$jml2635;
		$this->view->jml3645=$jml3645;
		$this->view->jml4655=$jml4655;
		$this->view->jml56=$jml56;
		if ($par=='b'){
		$this->view->chartjmlpeg=$this->toChartAge('',$jml25,$jml2635,$jml3645,$jml4655,$jml56);
		}
		else{
		$this->view->chartjmlpeg=$this->toChartAge('Komposisi Usia',$jml25,$jml2635,$jml3645,$jml4655,$jml56);
		}

	}

	function toChartAge($caption,$jml25,$jml2635,$jml3645,$jml4655,$jml56){
				require_once "../library/fusionchart/FusionCharts.php";
				$fusionCharts = new fusionChart();
				$w='80%'; 
				$h='100%';
		$strXML = "<chart caption='$caption' xAxisName='Month' yAxisName='Units' showValues='0' formatNumberScale='0' showBorder='3'
		bgColor='999999,FFFFFF' bgAlpha='10' palette='2' animation='1' numberPrefix='$' pieSliceDepth='10' startingAngle='100' 
		legendPosition='left' showLegend='1' baseFont='Arial' baseFontSize ='10' baseFontColor ='000000' >";
			
			if ($jml25){$strXML .= "<set label=' 25 thn' value='$jml25'  Color='FF0000'/> ";}			
			if ($jml2635){$strXML .= "<set label='26-35 thn' value='$jml2635'   Color='000066'/>";}  
			if ($jml3645){$strXML .= "<set label='36-45 thn' value='$jml3645'  Color='006600' />";}
			if ($jml4655){$strXML .= "<set label='46-55 thn' value='$jml4655'   Color='FFFF00'/>";}
			if ($jml56){$strXML .= "<set label='56 thn' value='$jml56'   Color='990000'/>";}
			
		$strXML .="<styles>
				      <definition>
					<style name='myCaptionFont' type='font' font='Arial' size='14' color='666666' bold='2' underline='1'/>
					<style name='myShadow' type='Shadow' color='999999' angle='45'/>
					<style name='myGlow' type='Glow' color='FF5904'/>
					<style name='myAnim' type='animation' param='_alpha' start='0' duration='1'/>	  
				      </definition>	
					<application>
						<apply toObject='CAPTION' styles='myCaptionFont,myShadow'/>
						 <apply toObject='Legend' styles='myAnim' />
						 <apply toObject='XAxisName' styles='myGlow' />
						<apply toObject='YAxisName' styles='myGlow' />
					</application>
				  </styles>";
				  
		$strXML .= "</chart>";
		$alm=$this->basePath."/charts/Pie3D.swf";
		$grafik = 'Chart';			
		$findchartdir = $fusionCharts->renderChartHTML($alm, '', $strXML, $grafik, 400, 200, false);
		$getlistdir=$findchartdir;
		$getlistdir=$getlistdir;
		return $getlistdir; 
}	

public function viewAction() {
	$mod=$_GET['mod'];
	$par=$_GET['par'];
	$this->view->par=$par;
	$this->view->mod=$mod;
	if ($mod=='usia'){
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		
		if ($par=='25'){$cari =" and (EXTRACT(years from AGE(now(), d_peg_lahir))<= 25) ";}
		if ($par=='26-35'){$cari =" and ((EXTRACT(years from AGE(now(), d_peg_lahir))>= 26) and (EXTRACT(years from AGE(now(), d_peg_lahir))<= 35)) ";}
		if ($par=='36-45'){$cari =" and ((EXTRACT(years from AGE(now(), d_peg_lahir))>= 36) and (EXTRACT(years from AGE(now(), d_peg_lahir))<= 45)) ";}
		if ($par=='46-55'){$cari =" and ((EXTRACT(years from AGE(now(), d_peg_lahir))>= 46) and (EXTRACT(years from AGE(now(), d_peg_lahir))<= 55))";}
		if ($par=='56'){$cari ="  and (EXTRACT(years from AGE(now(), d_peg_lahir))>= 56) ";}
		
		$this->view->totalpegawaiList = $this->statistik_serv->getPegawaiList($cari,0,0,$orderby);		
		$this->view->dataList = $this->statistik_serv->getPegawaiList($cari,$currentPage,$numToDisplay,$orderby);	
		$this->render('viewusia');
	}
	if ($mod=='pendidikan'){
		$currentPage=$_GET['currentPage'];
		if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		
		if ($par=='sd'){$cari =" and c_pend ='01'";}
		if ($par=='smp'){$cari =" and c_pend ='02'";}
		if ($par=='sma'){$cari =" and c_pend ='03'";}
		if ($par=='d1'){$cari =" and c_pend ='04'";}
		if ($par=='d2'){$cari =" and c_pend ='05'";}
		if ($par=='d3'){$cari =" and c_pend ='06'";}
		if ($par=='d4'){$cari =" and c_pend ='07'";}
		if ($par=='s1'){$cari =" and c_pend ='08'";}
		if ($par=='s2'){$cari =" and c_pend ='09'";}
		if ($par=='s3'){$cari =" and c_pend ='10'";}
		
		$this->view->totalpegawaiList = $this->statistik_serv->getPegawaiList($cari,0,0,$orderby);		
		$this->view->dataList = $this->statistik_serv->getPegawaiList($cari,$currentPage,$numToDisplay,$orderby);	
		$this->render('viewpendidikan');
	}	
	if ($mod=='golongan'){
		$currentPage=$_GET['currentPage'];
		if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		
		// if ($par=='i'){$cari =" and c_golongan in ('20','21','22','23') and c_status_kepegawaian in('1','2')";}
		// if ($par=='ii'){$cari =" and  c_golongan in ('14','15','16','17') and c_status_kepegawaian in('1','2')";}
		// if ($par=='iii'){$cari =" and  c_golongan in ('08','09','10','11') and c_status_kepegawaian in('1','2')";}
		// if ($par=='iv'){$cari =" and  c_golongan in ('03','04','05','06','07') and c_status_kepegawaian in('1','2')";}
		
		if ($par=='i'){$cari =" and c_golongan in ('20','21','22','23') ";}
		if ($par=='ii'){$cari =" and  c_golongan in ('14','15','16','17') ";}
		if ($par=='iii'){$cari =" and  c_golongan in ('08','09','10','11') ";}
		if ($par=='iv'){$cari =" and  c_golongan in ('03','04','05','06','07') ";}		
		
		$this->view->totalpegawaiList = $this->statistik_serv->getPegawaiList($cari,0,0,$orderby);		
		$this->view->dataList = $this->statistik_serv->getPegawaiList($cari,$currentPage,$numToDisplay,$orderby);	
		$this->render('viewgolongan');
	}
}

}
?>