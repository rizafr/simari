<?php
require_once 'Zend/Controller/Action.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Statistik_Service.php";

class Sdmmodule_StatistikjabatanController extends Zend_Controller_Action
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
	public function statistikjabatanjsAction() 
	{
		header('content-type : text/javascript');
		$this->render('statistikjabatanjs');
	}
	public function statistikjabatanAction() 
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
	public function viewmatriksAction() 
	{
		//Unit Kerja=====================
		$j_lap	= trim($_POST['j_lap']);
		if($j_lap == 'g'){
			$array_data = array("and trim(c_golongan)='03'"=>"Golongan IV/e","and trim(c_golongan)='04'"=>"Golongan IV/d","and trim(c_golongan)='05'"=>"Golongan IV/c","and trim(c_golongan)='06'"=>"Golongan IV/b","and trim(c_golongan)='07'"=>"Golongan IV/a",
					"and trim(c_golongan)='08'"=>"Golongan III/d","and trim(c_golongan)='09'"=>"Golongan III/c","and trim(c_golongan)='10'"=>"Golongan III/b","and trim(c_golongan)='11'"=>"Golongan III/a",
					"and trim(c_golongan)='14'"=>"Golongan II/d","and trim(c_golongan)='15'"=>"Golongan II/c","and trim(c_golongan)='16'"=>"Golongan II/b","and trim(c_golongan)='17'"=>"Golongan II/a",
					"and trim(c_golongan)='20'"=>"Golongan I/d","and trim(c_golongan)='21'"=>"Golongan I/c","and trim(c_golongan)='22'"=>"Golongan I/b","and trim(c_golongan)='23'"=>"Golongan I/a"
					);
					$this->view->judul = 'Golongan';
		} else if ($j_lap == 'p'){
			$array_data = array("and trim(c_pend)='10'"=>"S-3","and trim(c_pend)='09'"=>"S-2","and (trim(c_pend)='36' OR trim(c_pend)='07')"=>"S-1 / D-4",
				"and trim(c_pend)='32'"=>"D-3",
				"and (trim(c_pend)='40' OR trim(c_pend)='05' OR trim(c_pend)='04')"=>"SLTA / D-1 / D-2",
				"and trim(c_pend)='41'"=>"SLTP","and trim(c_pend)='29'"=>"SD");
			$this->view->judul = 'Pendidikan';
		} else if ($j_lap == 'u'){
			$array_data = array("and EXTRACT(years from AGE(now(), d_peg_lahir) > 59"=>">= 60",
			"and EXTRACT(years from AGE(now(), d_peg_lahir)) between 55 and 59 "=>">= 56 < 60",
			"and EXTRACT(years from AGE(now(), d_peg_lahir)) between 50 and 55 "=>">= 51 < 56",
			"and EXTRACT(years from AGE(now(), d_peg_lahir)) between 45 and 50 "=>">= 46 < 51",
			"and EXTRACT(years from AGE(now(), d_peg_lahir)) between 40 and 45 "=>">= 41 < 46",
			"and EXTRACT(years from AGE(now(), d_peg_lahir)) between 35 and 40 "=>">= 36 < 41",
			"and EXTRACT(years from AGE(now(), d_peg_lahir)) between 30 and 35 "=>">= 31 < 36",
			"and EXTRACT(years from AGE(now(), d_peg_lahir)) between 25 and 30 "=>">= 26 < 31",
			"and EXTRACT(years from AGE(now(), d_peg_lahir)) between 20 and 25 "=>">= 21 < 26",
			"and EXTRACT(years from AGE(now(), d_peg_lahir)) < 21 "=>"< 21");
			$this->view->judul = 'Umur';
		}
		$this->view->jenjangpdk = $array_data;		
		$tipe	= trim($_POST['tipe']);
		$c_lokasi_unitkerja=trim($_POST['c_lokasi_unitkerja']);
		if ($c_lokasi_unitkerja=='1'){
			$n_lokasi_unitkerja='Kantor Pusat MA';
			if ($_POST['c_eselon_i']){
				list($c_eselon_i,$n_eselon_i) = explode(' ',trim($_POST['c_eselon_i']),2);
			}
			if ($_POST['c_eselon_ii']){
				list($c_eselon_ii,$n_eselon_ii) = explode(' ',trim($_POST['c_eselon_ii']),2);
			}
			if ($_POST['c_eselon_iii']){
				list($c_eselon_iii,$n_eselon_iii) = explode(' ',trim($_POST['c_eselon_iii']),2);
			}
			if ($_POST['c_eselon_iii']){
				list($c_eselon_iii,$n_eselon_iii) = explode(' ',trim($_POST['c_eselon_iii']),2);
			}
			if ($_POST['c_eselon_iv']){
				list($c_eselon_iv,$n_eselon_iv) = explode(' ',trim($_POST['c_eselon_iv']),2);
			}
			$nmunit = ($n_eselon_iv ? "$n_eselon_iv, " : '').($n_eselon_iii ? "$n_eselon_iii, " : '').($n_eselon_ii ? "$n_eselon_ii, " : '').($n_eselon_i? "$n_eselon_i " : ''); 
		}
		else if ($c_lokasi_unitkerja=='3'){
			if ($_POST['c_eselon_i']){
				list($c_eselon_i,$n_eselon_i) = explode(';',trim($_POST['c_eselon_i']),2);
				$nmunit = $n_eselon_i;
			}
			if ($_POST['c_eselon_ii']){
				list($c_eselon_iiy,$c_parent,$n_eselon_ii) = explode(';',trim($_POST['c_eselon_ii']),3);
				$nmunit = $n_eselon_ii;
			}
			if ($_POST['c_eselon_iii']){
				list($c_eselon_iix,$c_eselon_iii,$c_satker,$n_eselon_iii) = explode(';',trim($_POST['c_eselon_iii']),4);
				$nmunit = $n_eselon_iii;
			}
			if ($_POST['c_eselon_iv']){
				list($c_eselon_iv,$n_eselon_iv) = explode(';',trim($_POST['c_eselon_iv']),2);
			}
			//print "".$_POST['c_eselon_ii']."<br>".$_POST['c_eselon_iii']."<br>c_eselon_i: $c_eselon_i <> c_eselon_iiy: $c_eselon_iiy <> c_parent:$c_parent <> $n_eselon_ii<br>
			//		c_eselon_iix: $c_eselon_iix <> c_eselon_iii: $c_eselon_iii<> c_satker: $c_satker <> $n_eselon_iii<br>";
			
		}				
			$cari= " and (c_eselon !='17' or c_eselon isnull)"; 	
			if ($c_lokasi_unitkerja){$cari .= " and c_lokasi_unitkerja='$c_lokasi_unitkerja'";}
			if ($c_eselon_i){$cari .= " and c_eselon_i='$c_eselon_i'";}
			if ($c_eselon_iiy && $c_eselon_iix == ''){$cari .= " and c_eselon_ii='$c_eselon_iiy'";}
			else if ($c_eselon_iix ){$cari .= " and c_eselon_ii='$c_eselon_iix'";}
			else if ($c_eselon_ii ){$cari .= " and c_eselon_ii='$c_eselon_ii'";}
			if ($c_satker){$cari .= " and c_satker='$c_satker' ";}
			
			if ($c_eselon_iii){$cari .= " and c_eselon_iii='$c_eselon_iii' ";}
			if ($c_eselon_iv){$cari .= " and c_eselon_iv='$c_eselon_iv'";}
			
		$this->view->sqlunit=$cari;
		$this->view->tipe = $tipe;
		$this->view->j_lap = $j_lap;
		$this->view->nmunit = $nmunit;
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
			$cari= " "; 	//and (c_eselon !='17' or c_eselon isnull)
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
		
		$esl1 = $this->statistik_serv->getCountData(" $cari and c_eselon in ('01','02')");
		$esl2 = $this->statistik_serv->getCountData("$cari and c_eselon  in ('03','04') ");
		$esl3 = $this->statistik_serv->getCountData("$cari and c_eselon  in ('05','06') ");
		$esl4 = $this->statistik_serv->getCountData("$cari and c_eselon  in ('07','08') ");
		$esl5 = $this->statistik_serv->getCountData("$cari and c_eselon  ='09'  ");
		$fungsional = $this->statistik_serv->getCountData("$cari and c_eselon ='13' ");
		$staf = $this->statistik_serv->getCountData("$cari and c_eselon ='15' ");
		$pn = $this->statistik_serv->getCountData("$cari and c_eselon ='12' ");
		$ttp = $this->statistik_serv->getCountData("$cari and c_eselon ='14' ");
		$adhoc = $this->statistik_serv->getCountData("$cari and c_eselon ='16' ");
		$nonjob = $this->statistik_serv->getCountData("$cari and c_eselon ='18' ");

		//$jmlkosong =  $this->statistik_serv->getCountData("$cari and c_eselon not in('01','02','03','04','05','06','07','08','09','13','17','15','12','14','16','18') ");
		$jmlkosong =  $this->statistik_serv->getCountData("$cari and c_eselon ='17' ");
		$jmlkosong2 =  $this->statistik_serv->getCountData("$cari and c_eselon isnull ");
		$jmlkosong3  =  $this->statistik_serv->getCountData("$cari and c_eselon =' ' ");		
		
		$this->view->esl1=$esl1;
		$this->view->esl2=$esl2;
		$this->view->esl3=$esl3;
		$this->view->esl4=$esl4; 
		$this->view->esl5=$esl5;
		$this->view->staf=$staf;
		$this->view->fungsional=$fungsional;
		$jmlkosongx=$jmlkosong*1+$jmlkosong2*1+$jmlkosong3*1;
		$this->view->jmlkosong=$jmlkosongx ;
		
		$this->view->pn=$pn;
		$this->view->ttp=$ttp;
		$this->view->adhoc=$adhoc;
		$this->view->nonjob=$nonjob;
		$this->view->chart=$this->toChartJbt($_GET['par'],$esl1,$esl2,$esl3,$esl4,$esl5,$fungsional,$jmlkosongx,$staf,$pn,$ttp,$adhoc,$nonjob);
			
		$this->view->judul= "Statistik Pegawai Menurut Tingkat Jabatan Tahun $tahun <br>Lingkup $nmunit"; 
		
	}
function toChartJbt($par,$esl1,$esl2,$esl3,$esl4,$esl5,$fungsional,$jmlkosongx,$staf,$pn,$ttp,$adhoc,$nonjob){
				require_once "../library/fusionchart/FusionCharts.php";
				$fusionCharts = new fusionChart();
				$w='80%'; 
				$h='100%';
		$strXML = "<chart caption='$caption' xAxisName='' yAxisName='' showValues='100' formatNumberScale='0' showBorder='3'
		bgColor='999999,FFFFFF' bgAlpha='10' palette='2' animation='1' numberPrefix='' pieSliceDepth='10' startingAngle='100' 
		legendPosition='left' showLegend='1' baseFont='Arial' baseFontSize ='10' baseFontColor ='000000' >";
			
			if ($esl1){$strXML .= "<set label='Eselon I' value='$esl1'  Color='550000' /> ";}			
			if ($esl2){$strXML .= "<set label='Eselon II' value='$esl2'   Color='006600' />";}  
			if ($esl3){$strXML .= "<set label='Eselon III' value='$esl3'  Color='FF0000' />";}
			if ($esl4){$strXML .= "<set label='Eselon IV' value='$esl4'   Color='990000' />";}
			if ($esl5){$strXML .= "<set label='Eselon V' value='$esl5'   Color='398AF9' />";}
			if ($fungsional){$strXML .= "<set label='Fungsional' value='$fungsional'   Color='FFFF00' />";}
			if ($jmlkosongx){$strXML .= "<set label='Data Kosong' value='$jmlkosongx'   Color='77000' />";}
			if ($staf){$strXML .= "<set label='Data Kosong' value='$staf'   Color='Cc0000' />";}
			if ($pn){$strXML .= "<set label='Pejabat Negara' value='$pn'   Color='004400' />";}
			if ($ttp){$strXML .= "<set label='TTP' value='$ttp'   Color='66FF33' />";}
			if ($adhoc){$strXML .= "<set label='AdHoc' value='$adhoc'   Color='074AA5' />";}
			if ($nonjob){$strXML .= "<set label='NonJob' value='$nonjob'   Color='330033' />";}
			
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