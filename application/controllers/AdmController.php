<?php
require_once 'Zend/Controller/Action.php';
require_once "service/sdm/Sdm_Dashboard_Service.php";

require_once "service/adm/Adm_Adminuser_Service.php";
require_once "service/adm/Adm_Admaplikasi_Service.php";



class AdmController extends Zend_Controller_Action
{

    public function init()
    {
		$this->_helper->layout->setLayout('adm-layout');
   	    $registry = Zend_Registry::getInstance();
		$this->auth = Zend_Auth::getInstance();
		$this->view->basePath = $registry->get('basepath');
		$this->view->baseData = $registry->get('baseData');
		 //$this->admmenu_serv = Adm_Admmenu_Service::getInstance();
		$this->admaplikasi_serv = Adm_Admaplikasi_Service::getInstance(); 
		$ssologin = new Zend_Session_Namespace('ssologin');
		
		if ($ssologin->userid && $ssologin->n_peg){
			$this->userid  			= $ssologin->userid;
			$this->password			= $ssologin->password;
			$this->i_peg_nip  		= $ssologin->i_peg_nip;
			$this->n_peg  			= $ssologin->n_peg;
			$this->n_peg_gelardepan = $ssologin->n_peg_gelardepan;
			$this->n_peg_gelarblkg 	= $ssologin->n_peg_gelarblkg;
			$this->c_jabatan 		= $ssologin->c_jabatan;
			$this->c_eselon_i 		= $ssologin->c_eselon_i;
			$this->c_eselon_ii 		= $ssologin->c_eselon_ii;
			$this->c_eselon_iii 	= $ssologin->c_eselon_iii;
			$this->c_eselon_iv 		= $ssologin->c_eselon_iv;
			$this->c_eselon_v 		= $ssologin->c_eselon_v; 
			$this->c_lokasi_unitkerja = $ssologin->c_lokasi_unitkerja; 
			$this->c_satker			= $ssologin->c_satker; 
			$this->n_satker			= $ssologin->n_satker; 
		}
		$this->view->ssologin = $ssologin;
		//echo "satker = ".$ssologin->c_satker.' '.$ssologin->userid;
		
		$urlAplikasiArr = explode("?",str_replace("/", "", $_SERVER['REQUEST_URI']));
		$this->view->aplikasi = $urlAplikasiArr[0]; //str_replace("/", "", $_SERVER['REQUEST_URI']);
    }

    public function indexAction()
    {
		$this->view->userid = $this->userid;
		$this->view->password = $this->password;
		$this->view->checkotoritas 	= $this->checkotoritas;
		
		// ambil list aplikasi
		//----------------------------------
		$dataMasukanAplikasiList = array("pageNumber" => 99,
										"itemPerPage" => 99,
										"kategoriCari" => "semua",
										"katakunciCari" => "",
										"sortBy" => "i_urut_aplikasi",
										"sortOrder" => "asc");
		$this->view->aplikasiList = $this->admaplikasi_serv->aplikasiList($dataMasukanAplikasiList);
		$dataMasukanLogAplikasi = array("userid" 	=> $this->view->userid,
										"cAplikasi"	=> $this->view->aplikasi);
		$this->view->writeToLogAksesaplikasi = $this->admaplikasi_serv->writeToLogAksesaplikasi($dataMasukanLogAplikasi);
		
		
	$jmla=10;
	$jmlb=20;
	$jmlc=20;
	$jmld=20;
	$jmld=2;
	
	// $usiaChart = $this->dasboard_serv->getUsia();								
	// $this->view->jml25=$usiaChart[0]['jml25'];
	// $this->view->jml2635=$usiaChart[0]['jml2635'];
	// $this->view->jml3645=$usiaChart[0]['jml3645'];
	// $this->view->jml4655=$usiaChart[0]['jml4655'];
	// $this->view->jml56=$usiaChart[0]['jml56'];
	
	// $this->view->chartjmlpeg=$this->toChartAge('Usia',$this->view->jml25,$this->view->jml2635,$this->view->jml3645,$this->view->jml4655,$this->view->jml56);
	// $pendidikanChart = $this->dasboard_serv->getPendidikan();
	// $this->view->jmlsd=$pendidikanChart[0]['sd'];
	// $this->view->jmlsmp=$pendidikanChart[0]['smp'];
	// $this->view->jmlsma=$pendidikanChart[0]['sma'];
	// $this->view->jmld1=$pendidikanChart[0]['d1'];
	// $this->view->jmld2=$pendidikanChart[0]['d2'];
	// $this->view->jmld3=$pendidikanChart[0]['d3'];
	// $this->view->jmls1=$pendidikanChart[0]['s1'];
	// $this->view->jmls2=$pendidikanChart[0]['s2'];
	// $this->view->jmls3=$pendidikanChart[0]['s3'];
	// $this->view->chartpend=$this->toChartEdu('Pendidikan',$this->view->jmlsd,$this->view->jmlsmp,$this->view->jmlsma,$this->view->jmld1,$this->view->jmld2,$this->view->jmld3,$this->view->jmls1,$this->view->jmls2,$this->view->jmls3);
	
	// $golPangkat = $this->dasboard_serv->getGolPangkat();
	// $this->view->jmlgol1=$golPangkat[0]['gol1'];
	// $this->view->jmlgol2=$golPangkat[0]['gol2'];
	// $this->view->jmlgol3=$golPangkat[0]['gol3'];
	// $this->view->jmlgol4=$golPangkat[0]['gol4'];
	// $this->view->chartgol=$this->toChartGol('Golongan',$this->view->jmlgol1,$this->view->jmlgol2,$this->view->jmlgol3,$this->view->jmlgol4);
	
	// $remindPensiun = $this->dasboard_serv->getReminderPensiun();
	// $this->view->remindPensiun =$remindPensiun[0]['jmlpensiun'];
	// $remindGolPangkat = $this->dasboard_serv->getReminderGolPangkat();
	// $this->view->remindGolPangkat =$remindGolPangkat[0]['jmlgolpangkat'];
    }
	
	
public function reminderlistAction() {
	if ($_GET['par']=='pensiun'){
		$this->view->parjudulheader="Tanggal Lahir";
		$this->view->parheader="Daftar Pegawai yang akan pensiun 1 tahun ke depan";
		$this->view->remindList = $this->dasboard_serv->getReminderPensiunList();
	}
	if ($_GET['par']=='golongan'){
		$this->view->parjudulheader="Tanggal TMT Gol";
		$this->view->parheader="Daftar Pegawai yang akan naik Gol/Pangkat 6 bulan ke depan";
		$this->view->remindList = $this->dasboard_serv->getReminderGolPangkatList();
	}
}	


public function halamandepanAction() {

    }
function toChartEdu($caption,$jmlsd,$jmlsmp,$jmlsma,$jmld1,$jmld2,$jmld3,$jmls1,$jmls2,$jmls3){
				require_once "../library/fusionchart/FusionCharts.php";
				$fusionCharts = new fusionChart();
				$w='80%'; 
				$h='100%';
		$strXML = "<chart caption='$caption' xAxisName='Month' yAxisName='Units' showValues='0' formatNumberScale='0' showBorder='3'
		bgColor='999999,FFFFFF' bgAlpha='10' palette='2' animation='1' numberPrefix='$' pieSliceDepth='10' startingAngle='100' 
		legendPosition='left' showLegend='1' baseFont='Arial' baseFontSize ='10' baseFontColor ='000000' >";
			
			if ($jmlsd){$strXML .= "<set label=' SD' value='$jmlsd'  Color='B9D4F9' /> ";}			
			if ($jmlsmp){$strXML .= "<set label='SMP' value='$jmlsmp'   Color='8AB9F9' />";}  
			if ($jmlsma){$strXML .= "<set label='SMA' value='$jmlsma'  Color='62A2FA' />";}
			if ($jmld1){$strXML .= "<set label='D1' value='$jmld1'   Color='398AF9' />";}
			if ($jmld2){$strXML .= "<set label='D2' value='$jmld2'   Color='0C70F9' />";}
			if ($jmld3){$strXML .= "<set label='D3' value='$jmld3'   Color='5285CA' />";}
			if ($jmls1){$strXML .= "<set label='S1' value='$jmls1'   Color='2B6ECA' />";}
			if ($jmls2){$strXML .= "<set label='S2' value='$jmls2'   Color='095BCA' />";}
			if ($jmls3){$strXML .= "<set label='S3' value='$jmls3'   Color='074AA5' />";}
			
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
		$alm=$this->basePath."/charts/Pie2D.swf";
		$grafik = 'Chart';			
		$findchartdir = $fusionCharts->renderChartHTML($alm, '', $strXML, $grafik, 300, 200, false);
		$getlistdir=$findchartdir;
		$getlistdir=$getlistdir;
		return $getlistdir; 
}	
function toChartAge($caption,$jml25,$jml2635,$jml3645,$jml4655,$jml56){
				require_once "../library/fusionchart/FusionCharts.php";
				$fusionCharts = new fusionChart();
				$w='80%'; 
				$h='100%';
		$strXML = "<chart caption='$caption' xAxisName='Month' yAxisName='Units' showValues='0' formatNumberScale='0' showBorder='3'
		bgColor='999999,FFFFFF' bgAlpha='10' palette='2' animation='1' numberPrefix='$' pieSliceDepth='10' startingAngle='100' 
		legendPosition='left' showLegend='1' baseFont='Arial' baseFontSize ='10' baseFontColor ='000000' >";
			
			if ($jml25){$strXML .= "<set label=' 25 thn' value='$jml25'  Color='6AF86A'/> ";}			
			if ($jml2635){$strXML .= "<set label='26-35 thn' value='$jml2635'   Color='28F928'/>";}  
			if ($jml3645){$strXML .= "<set label='36-45 thn' value='$jml3645'  Color='08D108' />";}
			if ($jml4655){$strXML .= "<set label='46-55 thn' value='$jml4655'   Color='05B805'/>";}
			if ($jml56){$strXML .= "<set label='56 thn' value='$jml56'   Color='069E06'/>";}
			
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
		$alm=$this->basePath."/charts/Pie2D.swf";
		$grafik = 'Chart';			
		$findchartdir = $fusionCharts->renderChartHTML($alm, '', $strXML, $grafik, 300, 200, false);
		$getlistdir=$findchartdir;
		$getlistdir=$getlistdir;
		return $getlistdir; 
}
function toChartGol($caption,$jmlgol1,$jmlgol2,$jmlgol3,$jmlgol4){
				require_once "../library/fusionchart/FusionCharts.php";
				$fusionCharts = new fusionChart();
				$w='80%'; 
				$h='100%';
		$strXML = "<chart caption='$caption' xAxisName='Month' yAxisName='Units' showValues='0' formatNumberScale='0' showBorder='3'
		bgColor='999999,FFFFFF' bgAlpha='10' palette='2' animation='1' numberPrefix='$' pieSliceDepth='10' startingAngle='100' 
		legendPosition='left' showLegend='1' baseFont='Arial' baseFontSize ='10' baseFontColor ='000000' >";
			
			if ($jmlgol1){$strXML .= "<set label=' I' value='$jmlgol1'  Color='380000 '/> ";}			
			if ($jmlgol2){$strXML .= "<set label='II' value='$jmlgol2'   Color='700000 '/>";}  
			if ($jmlgol3){$strXML .= "<set label='III' value='$jmlgol3'  Color='A80000 ' />";}
			if ($jmlgol4){$strXML .= "<set label='IV' value='$jmlgol4'   Color='FF0000 '/>";}
			
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
		$alm=$this->basePath."/charts/Pie2D.swf";
		$grafik = 'Chart';			
		$findchartdir = $fusionCharts->renderChartHTML($alm, '', $strXML, $grafik, 300, 200, false);
		$getlistdir=$findchartdir;
		$getlistdir=$getlistdir;
		return $getlistdir; 
}	
}







