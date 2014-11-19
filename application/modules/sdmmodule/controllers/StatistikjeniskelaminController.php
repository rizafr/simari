<?php
require_once 'Zend/Controller/Action.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Statistik_Service.php";

class Sdmmodule_StatistikjeniskelaminController extends Zend_Controller_Action
{
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
	 	$registry = Zend_Registry::getInstance();
	   	$this->view->basePath = $registry->get('basepath');	
		$this->view->leftMenu = $registry->get('leftMenu'); 
		$this->reff_serv = sdm_refferensi_Service::getInstance();
		$this->statistik_serv = Sdm_Statistik_Service::getInstance();
		$ssologin = new Zend_Session_Namespace('ssologin');
		$this->view->c_lokasi_unitkerja=$ssologin->c_lokasi_unitkerja;
		$this->view->c_eselon_i=$ssologin->c_eselon_i;			

    }
	public function indexAction()
	{
	}
	public function statistikjeniskelaminjsAction() 
	{
		header('content-type : text/javascript');
		$this->render('statistikjeniskelaminjs');
	}
	public function statistikjeniskelaminAction() 
	{
	   	$this->view->eselonList = $this->reff_serv->getEselon('');
		//$this->view->lokasiList = $this->reff_serv->getLokasi('');
		$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai('');
		$this->view->statusKePegRef = $this->reff_serv->getStatusKepegawaian('');	

	   	$c_lokasi_unitkerja=trim($this->view->c_lokasi_unitkerja);
		$c_eselon_i=trim($this->view->c_eselon_i);
		if ($c_eselon_i!='01'){
			$this->view->lokasiList = $this->reff_serv->getLokasi(" and c_lokasi='$c_lokasi_unitkerja'");
			if ($c_lokasi_unitkerja=='1'){
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}
			else{
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}
		}
		else{
			$this->view->lokasiList = $this->reff_serv->getLokasi("");
			if ($c_lokasi_unitkerja=='1'){
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}
			else{
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}		
		
		}
		

		$this->view->c_eselon_i=$c_eselon_i;
		$this->view->c_lokasi_unitkerja=$c_lokasi_unitkerja;			
	}	
	public function viewAction() 
	{
		$c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);
		if ($c_lokasi_unitkerja=='1'){
			$n_lokasi_unitkerja='Kantor Pusat MA';
			if ($_GET['c_eselon_i']){
				list($c_eselon_i,$n_eselon_i) = explode(' ',trim($_GET['c_eselon_i']),2);
			}
			if ($_GET['c_eselon_ii']){
				list($c_eselon_ii,$n_eselon_ii) = explode(' ',trim($_GET['c_eselon_ii']),2);
			}
			if ($_GET['c_eselon_iii']){
				list($c_eselon_iii,$n_eselon_iii) = explode(' ',trim($_GET['c_eselon_iii']),2);
			}
			if ($_GET['c_eselon_iii']){
				list($c_eselon_iii,$n_eselon_iii) = explode(' ',trim($_GET['c_eselon_iii']),2);
			}
			if ($_GET['c_eselon_iv']){
				list($c_eselon_iv,$n_eselon_iv) = explode(' ',trim($_GET['c_eselon_iv']),2);
			}
			$nmunit = ($n_eselon_iv ? "$n_eselon_iv, " : '').($n_eselon_iii ? "$n_eselon_iii, " : '').($n_eselon_ii ? "$n_eselon_ii, " : '').($n_eselon_i? "$n_eselon_i " : ''); 
		}
		else if ($c_lokasi_unitkerja=='3'){
			$n_lokasi_unitkerja='Lingkup Pengadilan';
			if ($_GET['c_eselon_i']){
				list($c_eselon_i,$n_eselon_i) = explode(';',trim($_GET['c_eselon_i']),2);
				$nmunit = $n_eselon_i;
			}
			if ($_GET['c_eselon_ii']){
				list($c_eselon_iiy,$c_parent,$n_eselon_ii) = explode(';',trim($_GET['c_eselon_ii']),3);
				$nmunit = $n_eselon_ii;
			}
			if ($_GET['c_eselon_iii']){
				list($c_eselon_iix,$c_eselon_iii,$c_satker,$n_eselon_iii) = explode(';',trim($_GET['c_eselon_iii']),4);
				$nmunit = $n_eselon_iii;
			}
			if ($_GET['c_eselon_iv']){
				list($c_eselon_iv,$n_eselon_iv) = explode(';',trim($_GET['c_eselon_iv']),2);
			}
		}				
			$cari= " and (c_eselon !='17') "; 	//
			if ($c_lokasi_unitkerja){$cari .= " and c_lokasi_unitkerja='$c_lokasi_unitkerja'";}
			if ($c_eselon_i){$cari .= " and c_eselon_i='$c_eselon_i'";}
			if ($c_eselon_iiy && $c_eselon_iix == ''){$cari .= " and c_eselon_ii='$c_eselon_iiy'";}
			else if ($c_eselon_iix ){$cari .= " and c_eselon_ii='$c_eselon_iix'";}
			else if ($c_eselon_ii ){$cari .= " and c_eselon_ii='$c_eselon_ii'";}
			
			if ($c_satker){$cari .= " and c_satker='$c_satker' ";}
			if ($c_eselon_iii){$cari .= " and c_eselon_iii='$c_eselon_iii' ";}
			if ($c_eselon_iv){$cari .= " and c_eselon_iv='$c_eselon_iv'";}
		
		$c_eselon=$_GET['c_eselon'];
		$c_status_kepegawaian=$_GET['c_status_kepegawaian'];
		$tahun=date('Y');

		$jmllaki = $this->statistik_serv->getCountData(" $cari and c_peg_jeniskelamin ='L' ");
		$jmlwanita = $this->statistik_serv->getCountData("$cari and c_peg_jeniskelamin ='P' ");
		$jmlkosong =  $this->statistik_serv->getCountData("$cari and c_peg_jeniskelamin isnull ");
		$jmlkosong1  =  $this->statistik_serv->getCountData("$cari and trim(c_peg_jeniskelamin) =''  ");
		$jmlkosong2 =  $this->statistik_serv->getCountData("$cari and c_peg_jeniskelamin is not null and trim(c_peg_jeniskelamin) !='' and c_peg_jeniskelamin not in ('L','P')");

		
		$this->view->jmllaki=$jmllaki;
		$this->view->jmlwanita=$jmlwanita;
		$jmlkosongx=$jmlkosong*1+$jmlkosong1*1+$jmlkosong2*1;
		$this->view->jmlkosong=$jmlkosongx;
		$this->view->chart=$this->toChart($_GET['par'],$jmllaki,$jmlwanita,$jmlkosongx);
	$this->view->judul= "Statistik Pegawai Menurut Jenis Kelamin Tahun $tahun <br>Lingkup $nmunit"; 
			
	}

function toChart($par,$jmllaki,$jmlwanita,$jmlkosongx){

				require_once "../library/fusionchart/FusionCharts.php";
				$fusionCharts = new fusionChart();
				$w='80%'; 
				$h='100%';
		$strXML = "<chart caption='$caption' xAxisName='' yAxisName='' showValues='100' formatNumberScale='0' showBorder='3'
		bgColor='999999,FFFFFF' bgAlpha='10' palette='2' animation='1' numberPrefix='' pieSliceDepth='10' startingAngle='100' 
		legendPosition='left' showLegend='1' baseFont='Arial' baseFontSize ='10' baseFontColor ='000000' >";
			
			if ($jmllaki){$strXML .= "<set label='Laki-laki' value='$jmllaki'  Color='Cc0000'/> ";}			
			if ($jmlwanita){$strXML .= "<set label='Perempuan' value='$jmlwanita'   Color='000066'/>";} 
			if ($jmlkosongx){$strXML .= "<set label='Data Kosong' value='$jmlkosongx'   Color='08D108'/>";} 
			
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