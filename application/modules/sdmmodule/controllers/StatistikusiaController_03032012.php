<?php
require_once 'Zend/Controller/Action.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Statistik_Service.php";

class Sdmmodule_StatistikusiaController extends Zend_Controller_Action
{
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
	 	$registry = Zend_Registry::getInstance();
	   	$this->view->basePath = $registry->get('basepath');	
		$this->view->leftMenu = $registry->get('leftMenu'); 
		$this->reff_serv = sdm_refferensi_Service::getInstance();
		$this->statistik_serv = Sdm_Statistik_Service::getInstance();

    }
	public function indexAction()
	{
	}
	public function statistikusiajsAction() 
	{
		header('content-type : text/javascript');
		$this->render('statistikusiajs');
	}
	public function statistikusiaAction() 
	{
	   	$this->view->eselonList = $this->reff_serv->getEselon('');
		$this->view->lokasiList = $this->reff_serv->getLokasi('');
		$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai('');
		$this->view->statusKePegRef = $this->reff_serv->getStatusKepegawaian('');	
	}	
	public function viewAction() 
	{
		//Unit Kerja=====================
		if (trim($_GET['c_lokasi_unitkerja']))
			{ $c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);
				$unitkerja = $this->statistik_serv->getLokasi(" and c_lokasi = '$c_lokasi_unitkerja'"); 
				
				$cari= $cari." and c_lokasi_unitkerja ='$c_lokasi_unitkerja'";}
		
/* 		if  ($_GET['eseloni'])
			{$c_eselon_i=substr($_GET['eseloni'],0,2);
				$jdlesi=substr($_GET['eseloni'],2,200);
				$cari= $cari." and c_eselon_i ='$c_eselon_i'";}
		
		if  ($_GET['eselonii'])
			{$c_eselon_ii=substr($_GET['eselonii'],0,2);
				$jdlesii=substr($_GET['eselonii'],2,200);
				$cari= $cari." and c_eselon_ii ='$c_eselon_ii'";}	
	
		if  ($_GET['eseloniii'])
			{$c_eselon_iii=substr($_GET['eseloniii'],0,2);
				$jdlesiii=substr($_GET['eseloniii'],2,200);
				$cari= $cari." and c_eselon_iii ='$c_eselon_iii'";}		

		if  ($_GET['eseloniv'])
			{$c_eselon_iv=substr($_GET['eseloniv'],0,2);
				$jdlesiv=substr($_GET['eseloniv'],2,200);
				$cari= $cari." and c_eselon_iv ='$c_eselon_iv'";}

		if  ($_GET['eselonv'])
			{$c_eselon_v=substr($_GET['eselonv'],0,2);
				$jdlesvsubstr($_GET['eselonv'],2,200);
				$cari= $cari." and c_eselon_v ='$c_eselon_v'";} */

		if ($c_lokasi_unitkerja=='1'){
			
			if ($c_lokasi_unitkerja){$cari .= " and c_lokasi_unitkerja='$c_lokasi_unitkerja'";}
			if ($_GET['c_eselon_i']){$c_eselon_i=$_GET['c_eselon_i'];}
			else{$c_eselon_i=$_GET['c_eselon_i'];}
			
			$c_eselon_i=substr($c_eselon_i,0,2);
			if ($_GET['c_eselon_i'] || $_GET['c_eselon_i']){$cari .= " and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'";}

			if ($_GET['c_eselon_ii']){$c_eselon_ii=$_GET['c_eselon_ii'];}
			else{$c_eselon_ii=$_GET['c_eselon_ii'];}
			$c_eselon_ii=substr($c_eselon_ii,0,2);
			if ($_GET['c_eselon_ii'] || $_GET['c_eselon_ii']){$cari .= " and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and c_lokasi_unitkerja='$c_lokasi_unitkerja'";}
			
			if ($_GET['c_eselon_iii']){$c_eselon_i=$_GET['c_eselon_iii'];}
			else{$c_eselon_iii=$_GET['c_eselon_iii'];}
			
			
			$c_eselon_iii=substr($c_eselon_iii,0,2);
			if ($_GET['c_eselon_iii'] || $_GET['c_eselon_iii']){$cari .= " and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_lokasi_unitkerja='$c_lokasi_unitkerja'";}

			if ($_GET['c_eselon_iv']){$c_eselon_iv=$_GET['c_eselon_iv'];}
			else{$c_eselon_iv=$_GET['c_eselon_iv'];}
			
			
			$c_eselon_iv=substr($c_eselon_iv,0,2);
			if ($_GET['c_eselon_iv'] || $_GET['c_eselon_iv']){$cari .= " and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_lokasi_unitkerja='$c_lokasi_unitkerja'";}
		

			$this->view->c_eselon_i = $c_eselon_i;
			

			
			$this->view->lokasiList = $this->reff_serv->getLokasi('');
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and c_tkt_esl='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			
			
			
			if ($_GET['c_eselon_i']){$this->view->c_eselon_i=$_GET['c_eselon_i'];}
			else{$this->view->c_eselon_i=$_GET['c_eselon_i'];}	
			
			$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	

			if ($_GET['c_eselon_ii']){$this->view->c_eselon_ii=$_GET['c_eselon_ii'];}
			else{$this->view->c_eselon_ii=$_GET['c_eselon_ii'];}
				
			
			$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");


			if ($_GET['c_eselon_iii']){$this->view->c_eselon_iii=$_GET['c_eselon_iii'];}
			else{$this->view->c_eselon_iii=$_GET['c_eselon_iii'];}
				
			
			$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

			if ($_GET['c_eselon_iv']){$this->view->c_eselon_iv=$_GET['c_eselon_iv'];}
			else{$this->view->c_eselon_iv=$_GET['c_eselon_iv'];}
				
			
			$this->view->eselonvList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

		}
		else
		{


			if ($_GET['c_lokasi_unitkerja']){$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);}
			else{$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);}
			$this->view->lokasiList = $this->reff_serv->getLokasi('');
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and c_tkt_esl='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

			
			if ($_GET['c_eselon_i']){$c_eselon_i=$_GET['c_eselon_i'];}
			else{$c_eselon_i=trim($_GET['c_eselon_i']);}
			$this->view->c_eselon_i =$c_eselon_i;
			if ($c_eselon_i){
				$expesl1 = explode(";",$c_eselon_i);
				$c_eselon_i=$expesl1[0];
				$n_eselon_i=$expesl1[1];
			}
			
			if ($_GET['c_eselon_ii']){$c_eselon_ii=$_GET['c_eselon_ii'];}
			else{$c_eselon_ii=trim($_GET['c_eselon_ii']);}
			$this->view->c_eselon_ii =$c_eselon_ii;
			if ($c_eselon_ii){
			$expesl2 = explode(";",$c_eselon_ii);
			$c_eselon_ii=$expesl2[0];
			$c_parent=$expesl2[1];
			$n_eselon_ii=$expesl2[2];
			}
			
			
			if ($_GET['c_eselon_iii']){$c_eselon_iii=$_GET['c_eselon_iii'];}
			else{$c_eselon_iii=trim($_GET['c_eselon_iii']);}
			$this->view->c_eselon_iii =trim($c_eselon_iii);		
			if ($c_eselon_iii){
			$expesl3 = explode(";",$c_eselon_iii);	
			$c_eselon_iix=$expesl3[0];
			$c_eselon_iii=$expesl3[1];
			$c_satker=$expesl3[2];
			$n_eselon_iii=$expesl3[3];			
			}

			if ($_GET['c_eselon_iv']){$c_eselon_iv=$_GET['c_eselon_iv'];}
			else{$c_eselon_iv=trim($_GET['c_eselon_iv']);}
			$this->view->c_eselon_iv =trim($c_eselon_iv);	
			if ($c_eselon_iv){
			$expesl4 = explode(";",$c_eselon_iv);	
			$c_eselon_iv=$expesl4[0];
			$n_eselon_iv=$expesl4[1];
			}			
			

			
			if ($c_lokasi_unitkerja){$cari .= " and c_lokasi_unitkerja='$c_lokasi_unitkerja'";}
			if ($c_eselon_i){$cari .= " and c_eselon_i='$c_eselon_i'";}
			if ($c_eselon_ii ){$cari .= " and c_eselon_ii='$c_eselon_ii'";}
			if ($c_eselon_iii){$cari .= " and c_eselon_iii='$c_eselon_iii' and c_parent='$c_parent' and c_satker='$c_satker'";}
			if ($c_eselon_iv){$cari .= " and c_eselon_iv='$c_eselon_iv'";}
			
			//echo $cari;
			//echo " and c_eselon_i='$c_eselon_i'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child <> '00' and c_parent ='$c_parent'  and c_lokasi_unitkerja='$c_lokasi_unitkerja'";
			
			$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_iii = '00'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child = '00' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child <> '00' and c_parent ='$c_parent'  and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and c_eselon_iii <> '00' and c_eselon_ii='$c_eselon_iix'  and c_lokasi_unitkerja='$c_lokasi_unitkerja' ");

		}				
		$cari= $cari." and (c_eselon !='17' or c_eselon isnull)"; 	
	
		$c_eselon=$_GET['c_eselon'];
		$c_status_kepegawaian=$_GET['c_status_kepegawaian'];
		$tahun=date('Y');
		
		 
		
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
		
		//$this->view->chart=$this->toChartUsia($_GET['par'],$jml25,$jml2635,$jml3645,$jml4655,$jml56);
		$this->view->chart=$this->toChartUsia($_GET['par'],$jml25,$jml2635,$jml3645,$jml4655,$jml56);
/* 		if ($c_lokasi_unitkerja){
			$this->view->judul= "Statistik Pegawai Menurut Tingkat Usia di Tahun $tahun di $unitkerja pada, $jdlesi, $jdlesii, $jdlesiii, $jdlesiv"; 
		}
		else{
			$this->view->judul= "Statistik Pegawai Menurut Tingkat Usia di Tahun $tahun di Mahkamah Agung"; 
		} */
		
		if ($unitkerja){
		if ($n_eselon_i){$jd="$n_eselon_i,";}
		if ($n_eselon_ii){$jd=$jd."$n_eselon_ii,";}
		if ($n_eselon_iii){$jd=$jd."$n_eselon_iii ,";}
		if ($n_eselon_iv){$jd=$jd."$n_eselon_iv ,";}
		$this->view->judul= "Statistik Pegawai Menurut Tingkat Usia di Tahun $tahun <br>di $unitkerja pada, $jd"; 
		}
		else{
		$this->view->judul= "Statistik Pegawai Menurut Tingkat Usia di Tahun $tahun di Mahkamah Agung"; 
		}		
	}

function toChartUsia($par,$esl1,$esl2,$esl3,$esl4,$esl5){
				require_once "../library/fusionchart/FusionCharts.php";
				$fusionCharts = new fusionChart();
				$w='80%'; 
				$h='100%';
		$strXML = "<chart caption='$caption' xAxisName='Usia' yAxisName='' showValues='100' formatNumberScale='0' showBorder='3'
		bgColor='999999,FFFFFF' bgAlpha='10' palette='2' animation='1' numberPrefix='' pieSliceDepth='10' startingAngle='100' 
		legendPosition='left' showLegend='1' baseFont='Arial' baseFontSize ='10' baseFontColor ='000000' >";
			
			if ($esl1){$strXML .= "<set label='kurang 25' value='$esl1'  Color='Cc0000' /> ";}			
			if ($esl2){$strXML .= "<set label='26 s/d 35' value='$esl2'   Color='000066' />";}  
			if ($esl3){$strXML .= "<set label='36 s/d 45' value='$esl3'  Color='005500' />";}
			if ($esl4){$strXML .= "<set label='46 s/d 55' value='$esl4'   Color='880000' />";}
			if ($esl5){$strXML .= "<set label='lebih 56' value='$esl5'   Color='222222' />";}
			
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
		if ($par=='pie'){
		$alm=$this->basePath."/charts/Pie2D.swf";
		}
		else{
		$alm=$this->basePath."/charts/Column2D.swf";
		}
		$grafik = 'Chart';			
		$findchartdir = $fusionCharts->renderChartHTML($alm, '', $strXML, $grafik, 500, 400, false);
		$getlistdir=$findchartdir;
		$getlistdir=$getlistdir;
		return $getlistdir; 
}		
	
}

?>