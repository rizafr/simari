<?php
require_once 'Zend/Controller/Action.php';
require_once "eis/Sdm_Eis.php";
require_once "eis/Rencana_Eis.php";
require_once "eis/Keuangan_Eis.php";
require_once "eis/Logistik_Eis.php";
require_once "eis/Aset_Eis.php";
require_once "service/sdm/Sdm_Statistik_Service.php";
require_once "service/cms/Cms_Berita_Service.php";
require_once "service/cms/Cms_pengumuman_Service.php";
require_once "service/ast/Ast_Dashboard_Service.php";
require_once "service/rencana/Rencana_Pagu_Service.php";
require_once "service/eis/Keu_Statistik_Service.php";
require_once "service/eis/Aset_Statistik_Service.php";
require_once "service/logistik/Logistik_Reminder_Service .php";
require_once "service/siap/Siap_Perkarakasasi_Service.php";
require_once "service/adm/Adm_Admaplikasi_Service.php";
require_once "../library/fusionchart/FusionCharts.php";
class EisController extends Zend_Controller_Action
{

    public function init()
    {
		$ssologin = new Zend_Session_Namespace('ssologin');
		$this->view->c_jabatan=$ssologin->c_jabatan;
		$this->view->c_eselon_i=$ssologin->c_eselon_i;
		$this->view->c_eselon_ii=$ssologin->c_eselon_ii;
		$this->view->userid= $ssologin->userid;		
	
	$this->_helper->layout->setLayout('eis-layout');
	$registry = Zend_Registry::getInstance();
	$this->auth = Zend_Auth::getInstance();
	$this->view->basePath = $registry->get('basepath');
	$this->view->baseData = $registry->get('baseData');
	$this->sdm_serv = Sdm_eis::getInstance();
	$this->log_serv = Logistik_eis::getInstance();
	$this->aset_serv = Aset_eis::getInstance();
	$this->rencana_serv = Rencana_eis::getInstance();
	$this->keuangan_serv = Keuangan_eis::getInstance();
	$this->statistik_serv = Sdm_Statistik_Service::getInstance();
	$this->asetdashboard_serv = Ast_Dashboard_Service::getInstance();
	$this->pagu_serv = Rencana_Pagu_Service::getInstance();	
	$this->keu_serv = Keu_Statistik_Service::getInstance();
	$this->ast_serv = Aset_Statistik_Service::getInstance();
	$this->logrmnder_serv = Logistik_Reminder_Service::getInstance();
	$this->view->perkarakasasi_serv = Siap_Perkarakasasi_Service::getInstance();
	$this->admaplikasi_serv = Adm_Admaplikasi_Service::getInstance(); 

	$this->berita_serv = Cms_Berita_Service::getInstance();
	$this->view->idberita= $this->idberita;
	$this->view->jdlberita= $this->jdlberita;
	$this->view->detilberita= $this->detilberita;
	$this->pengumuman_serv = Cms_pengumuman_Service::getInstance();
	$this->view->idpengumuman= $this->idpengumuman;
	$this->view->jdlpengumuman= $this->jdlpengumuman;
	$this->view->detilpengumuman= $this->detilpengumuman;	
		
    }

    public function indexAction()
    {   
    
if ($this->view->userid){
	if (trim($this->view->c_jabatan)=='008' && trim($this->view->c_eselon_ii)!='002'){$this->view->uspejabat='1';}
	if (trim($this->view->c_jabatan)=='008' && trim($this->view->c_eselon_ii)!='006'){$this->view->uspejabat='2';}	
	if (trim($this->view->c_jabatan)=='004' && trim($this->view->c_eselon_i)!='01'){$this->view->uspejabat='3';}	
	if (trim($this->view->c_jabatan)=='001'){$this->view->uspejabat='4';}	
    
	if (trim($this->view->c_jabatan)=='008' || trim($this->view->c_jabatan)=='004' || trim($this->view->c_jabatan)=='001'){
		$this->userOnline();
		$this->view->beritaPubList = $this->berita_serv->getBeritaPubList($cari);
		$this->view->pengumumanPubList = $this->pengumuman_serv->getpengumumanPubList($cari2);	
		
		$this->toSdmChartUsia();
		$this->toSdmChartPendidikan();
		$this->toSdmChartGolongan();
		$this->toSdmChartPensiun();
		$this->toSdmChartNaikGol();
		$this->toSdmChartKgb(); 

		if ((trim($this->view->c_jabatan)!='008' && trim($this->view->c_eselon_ii)!='002') || (trim($this->view->c_jabatan)!='008' && trim($this->view->c_eselon_ii)!='006')) {  
			$this->toChartAset();
			$this->toChartAsetb();
			$this->toChartAsetc();
			$this->rencana();
			$this->rencanab();
			
			$this->chartpaguanggaran();    

			$datakdstoklst = $this->dataKdStokBarang();
			$datastoklst = $this->dataStokBarang();
			$datamasuklst = $this->databarangmasuk();
			$datakeluarlst = $this->databarangkeluar();
			$this->toChartPersediaan();

		   $this->view->listpanmuddata = $this->view->perkarakasasi_serv->listpanmud();
		   $caption = "Monitoring Perkara Kasasi Sisa";
		   $datalbl = $this->dataPanmud();
		   $dataval = $this->datakasasisisa();
		   $this->view->kasasichart = $this->toChartKasasi($caption,$datalbl,$dataval);
		   $caption = "Monitoring Perkara Kasasi Masuk";
		   $datalbl = $this->dataPanmud();
		   $dataval = $this->datakasasimasuk();
		   $this->view->kasasimasuk = $this->toChartKasasi($caption,$datalbl,$dataval);			
			
		}
	}	
		//$this->toChartPersediaan('Stok Barang',$datakdstoklst,$datastoklst);
		//$this->toChartPersediaan('Barang Masuk',$datakdstoklst,$datamasuklst);
		//$this->toChartPersediaan('Barang Keluar',$datakdstoklst,$datakeluarlst);
		
	
	//$this->charSiap() ;	
}
else{
	//$this->_helper->layout->setLayout('login-layout');
	$this->_helper->layout->setLayout('eis-layout');
}		
    }
 
function userOnline(){
	$userOnline = $this->admaplikasi_serv->getUserOnline();	
	$this->view->userOnline=$this->sdm_serv->userOnline($userOnline);
	
} 
//=======================================================================================S I A P==========================================================================
	
	public function datakasasisisa()
	{
	    $panmud = $this->view->perkarakasasi_serv->listpanmud();
        $jmlpanmud = count($panmud);
        for ($i=0; $i<$jmlpanmud; $i++)
        {      
		    $where = " panmud='".$panmud[$i]['sPanmud']."' and tgl_putusan_kasasi is NOT NULL and (year(curdate())-year(tgl_perkara_masuk))>1";
		    $sisaawal[(string)$panmud[$i]['sPanmud']] = $this->view->perkarakasasi_serv->getDataPerkara($where);
        }
		return $sisaawal;
	}
	public function datakasasimasuk()
	{
	    $panmud = $this->view->perkarakasasi_serv->listpanmud();
        $jmlpanmud = count($panmud);
        for ($i=0; $i<$jmlpanmud; $i++)
        {      
		    $where = " no_registrasi_tahun='".date('Y')."' and panmud='".$panmud[$i]['sPanmud']."'";
		    $masuk[(string)$panmud[$i]['sPanmud']] = $this->view->perkarakasasi_serv->getDataPerkara($where);
        }
		return $masuk;
	}

	public function dataPanmud()
	{
	    $lstpanmud = $this->view->perkarakasasi_serv->listpanmud();
        $jmllstpanmud = count($lstpanmud);
        for ($i=0; $i<$jmllstpanmud; $i++)
        {      
	       $datapanmudlst[$i] = (string)$lstpanmud[$i]['sPanmud'];
        }
		return $datapanmudlst;
	}
function toChartKasasi($caption,$datakd,$data){
	$dtjml="";
	$nilai="";
	$nama="";				
		require_once "../library/fusionchart/FusionCharts.php";
				$fusionCharts = new fusionChart();
				$w='80%'; 
				$h='100%';
		$strXML = "<chart xAxisName='Month' yAxisName='Units' showValues='0' formatNumberScale='0' showBorder='3'
		bgColor='999999,FFFFFF' bgAlpha='10' palette='2' animation='1' numberPrefix='$' pieSliceDepth='10' startingAngle='100' 
		legendPosition='left' showLegend='1' baseFont='Arial' baseFontSize ='10' baseFontColor ='000000' >";
		$warna = array ('FF0000', '00FF00', '0000FF', 'FFFF00', '000000', 'CC00CC', '2B6ECA', '095BCA', '074A25', '074A65', '074A85', '074AA5' );
		$jmlDatakd = count($datakd);
		for ($i=0; $i<$jmlDatakd; $i++)
		{      
			$strXML .= "<set label='".$datakd[$i]."' value='".$data[$datakd[$i]]."'  Color='".$warna[$i]."'/> ";
			$nilai=$data[$datakd[$i]];
			$nama=$datakd[$i];
			$dtjml=$dtjml."<tr class='event'><td align='left'>$nama</td><td align='right'>$nilai</td></tr>";		
			
		}
		$this->view->jmlks=$dtjml;	
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
		$alm=$this->view->baseUrl."/charts/Pie3D.swf";
		$grafik = 'Chart';			
		$findchartdir = $fusionCharts->renderChartHTML($alm, '', $strXML, $grafik, 540, 250, false);
		$getlistdir=$findchartdir;
		$getlistdir=$getlistdir;
		return $getlistdir; 
}

//=======================================================================================S I A P==========================================================================

//=======================================================================================LOGISTIK==========================================================================
function dataKdStokBarang()
{
	$lstkelbrg = $this->logrmnder_serv->listKelompokBrg();
        $jmllstkelbrg = count($lstkelbrg);
        for ($i=0; $i<$jmllstkelbrg; $i++)
        {      
	       $datakdStok[$i] = (string)$lstkelbrg[$i]['kd_kelbrg'];
        }
		return $datakdStok;
}
function dataStokBarang()
{
	$lstkelbrg = $this->logrmnder_serv->listKelompokBrg();
        $jmllstkelbrg = count($lstkelbrg);
        for ($i=0; $i<$jmllstkelbrg; $i++)
        {      
		    $wherekel = " substr(kd_brg,0,6)='".(string)$lstkelbrg[$i]['kd_kelbrg']."'";
		    $dataStok[(string)$lstkelbrg[$i]['kd_kelbrg']] = $this->logrmnder_serv->getQtyStkperklmpk($wherekel);
        }
		return $dataStok;
}
function databarangmasuk()
{
	    $lstkelbrg = $this->logrmnder_serv->listKelompokBrg();
        $jmllstkelbrg = count($lstkelbrg);
        for ($i=0; $i<$jmllstkelbrg; $i++)
        {      
		    $wherekel = " substr(kd_brg,0,6)='".(string)$lstkelbrg[$i]['kd_kelbrg']."'";
		    $dataStok[(string)$lstkelbrg[$i]['kd_kelbrg']] = $this->logrmnder_serv->getMskStkperklmpk($wherekel);
        }
		return $dataStok;
}
function databarangkeluar()
{
	$lstkelbrg = $this->logrmnder_serv->listKelompokBrg();
        $jmllstkelbrg = count($lstkelbrg);
        for ($i=0; $i<$jmllstkelbrg; $i++)
        {      
		    $wherekel = " substr(kd_brg,0,6)='".(string)$lstkelbrg[$i]['kd_kelbrg']."'";
		    $dataStok[(string)$lstkelbrg[$i]['kd_kelbrg']] = $this->logrmnder_serv->getKlrStkperklmpk($wherekel);
        }
		return $dataStok;
}
	
function toChartPersediaan($caption,$datakdstoklst,$datastoklst) {
	$tahun=date('Y');
	$jmlStk = $this->ast_serv->getCountDataStokBarangForEis("");	
		$jmlStk = $this->ast_serv->getCountDataStokBarangForEis("");
		$jmlResult= count($jmlStk);
		if ($jmlResult !=0){
			for ($j = 0; $j < $jmlResult; $j++) 
			{ 
				$esl=trim($jmlStk[$j]['c_satker']);
				$kd_brg=trim($jmlStk[$j]['kd_brg']);
				if ($esl=='663157'){					
					if ($kd_brg=='10101'){$jmlbuaBahan=trim($jmlStk[$j]['jumlah']); $this->view->jmlbuaBahan=$jmlbuaBahan;}
					if ($kd_brg=='10103'){$jmlbuaATK=trim($jmlStk[$j]['jumlah']); $this->view->jmlbuaATK=$jmlbuaATK;}
					if ($kd_brg=='10104'){$jmlbuaObat=trim($jmlStk[$j]['jumlah']); $this->view->jmlbuaObat=$jmlbuaObat;}				
				}
				if ($esl=='097450'){				
					if ($kd_brg=='10101'){$jmlbadilumBahan=trim($jmlStk[$j]['jumlah']); $this->view->jmlbadilumBahan=$jmlbadilumBahan;}
					if ($kd_brg=='10103'){$jmlbadilumATK=trim($jmlStk[$j]['jumlah']); $this->view->jmlbadilumATK=$jmlbadilumATK;}
					if( $kd_brg=='10104'){$jmlbadilumObat=trim($jmlStk[$j]['jumlah']); $this->view->jmlbadilumObat=$jmlbadilumObat;}	
				}
				if ($esl=='663712'){
					if ($kd_brg=='10101'){$jmlbadilagBahan=trim($jmlStk[$j]['jumlah']); $this->view->jmlbadilagBahan=$jmlbadilagBahan;}
					if ($kd_brg=='10103'){$jmlbadilagATK=trim($jmlStk[$j]['jumlah']); $this->view->jmlbadilagATK=$jmlbadilagATK;}
					if( $kd_brg=='10104'){$jmlbadilagObat=trim($jmlStk[$j]['jumlah']); $this->view->jmlbadilagObat=$jmlbadilagObat;}
				}
				if ($esl=='663122'){
					if ($kd_brg=='10101'){$jmlbadimilBahan=trim($jmlStk[$j]['jumlah']); $this->view->jmlbadimilBahan=$jmlbadimilBahan;}
					if ($kd_brg=='10103'){$jmlbadimilATK=trim($jmlStk[$j]['jumlah']); $this->view->jmlbadimilATK=$jmlbadimilATK;}
					if( $kd_brg=='10104'){$jmlbadimilObat=trim($jmlStk[$j]['jumlah']); $this->view->jmlbadimilObat=$jmlbadimilObat;}	
				}
				if ($esl=='610378'){
					if ($kd_brg=='10101'){$jmlATKppphpBahan=trim($jmlStk[$j]['jumlah']); $this->view->jmlATKppphpBahan=$jmlATKppphpBahan;}
					if ($kd_brg=='10103'){$jmlATKppphpATK=trim($jmlStk[$j]['jumlah']); $this->view->jmlATKppphpATK=$jmlATKppphpATK;}
					if( $kd_brg=='10104'){$jmlATKppphpObat=trim($jmlStk[$j]['jumlah']); $this->view->jmlATKppphpObat=$jmlATKppphpObat;}
				}
				if ($esl=='663136'){
					if ($kd_brg=='10101'){$jmlbawasBahan=trim($jmlStk[$j]['jumlah']); $this->view->jmlbawasBahan=$jmlbawasBahan;}
					if ($kd_brg=='10103'){$jmlbawasATK=trim($jmlStk[$j]['jumlah']); $this->view->jmlbawasATK=$jmlbawasATK;}
					if( $kd_brg=='10104'){$jmlbawasObat=trim($jmlStk[$j]['jumlah']); $this->view->jmlbawasObat=$jmlbawasObat;}
				}
			}
		} 	
	   $this->view->chartStock=$this->log_serv->toChartStock($jmlbuaBahan,$jmlbuaATK,$jmlbuaObat,$jmlbadilumBahan,$jmlbadilumATK,$jmlbadilumObat,
								   $jmlbadilagBahan,$jmlbadilagATK,$jmlbadilagObat,$jmlbadimilBahan,$jmlbadimilATK,$jmlbadimilObat,
								   $jmlATKppphpBahan,$jmlATKppphpATK,$jmlATKppphpObat,$jmlbawasBahan,$jmlbawasATK,$jmlbawasObat);	
}
//=======================================================================================LOGISTIK==========================================================================

//=======================================================================================KEUANGAN==========================================================================
function chartpaguanggaran() {
	$tahun=date('Y');
	$jmlTpa = $this->keu_serv->getCountDataAnggaranForEis(" and d_thang = '$tahun' ");
		$jmlResultTpa= count($jmlTpa);
		if ($jmlResultTpa !=0){
			for ($j = 0; $j < $jmlResultTpa; $j++) 
			{ 
				$esl=trim($jmlTpa[$j]['c_satker']);
				if ($esl=='663157'){$jmlbuaTpa=trim($jmlTpa[$j]['jumlah']); $this->view->jmlbuaTpa=$jmlbuaTpa;}
				if ($esl=='097450'){$jmlbadilumTpa=trim($jmlTpa[$j]['jumlah']); $this->view->jmlbadilumTpa=$jmlbadilumTpa;}
				if ($esl=='663712'){$jmlbadilagTpa=trim($jmlTpa[$j]['jumlah']); $this->view->jmlbadilagTpa=$jmlbadilagTpa;}
				if ($esl=='663122'){$jmlbadimilTpa=trim($jmlTpa[$j]['jumlah']); $this->view->jmlbadimilTpa=$jmlbadimilTpa;}
				if ($esl=='610378'){$jmlbpppphpTpa=trim($jmlTpa[$j]['jumlah']); $this->view->jmlbpppphpTpa=$jmlbpppphpTpa;}
				if ($esl=='663136'){$jmlbawasTpa=trim($jmlTpa[$j]['jumlah']); $this->view->jmlbawasTpa=$jmlbawasTpa;}
			}
		}
	$jmlReal = $this->keu_serv->getCountDataRealisasiForEis(" '$tahun' ");
		$jmlResultReal= count($jmlReal);
		if ($jmlResultReal !=0){
			for ($j = 0; $j < $jmlResultReal; $j++) 
			{ 
				$esl=trim($jmlReal[$j]['c_satker']);
				if ($esl=='663157'){$jmlbuaReal=trim($jmlReal[$j]['jumlah']); $this->view->jmlbuaReal=$jmlbuaReal;}
				if ($esl=='097450'){$jmlbadilumReal=trim($jmlReal[$j]['jumlah']); $this->view->jmlbadilumReal=$jmlbadilumReal;}
				if ($esl=='663712'){$jmlbadilagReal=trim($jmlReal[$j]['jumlah']); $this->view->jmlbadilagReal=$jmlbadilagReal;}
				if ($esl=='663122'){$jmlbadimilReal=trim($jmlReal[$j]['jumlah']); $this->view->jmlbadimilReal=$jmlbadimilReal;}
				if ($esl=='610378'){$jmlbpppphpReal=trim($jmlReal[$j]['jumlah']); $this->view->jmlbpppphpReal=$jmlbpppphpReal;}
				if ($esl=='663136'){$jmlbawasReal=trim($jmlReal[$j]['jumlah']); $this->view->jmlbawasReal=$jmlbawasReal;}
			}
		}
		
	$this->view->chartKeuangan = $this->keuangan_serv->toChartKeuangan($jmlbadilagTpa,$jmlbadilagReal,$jmlbadilumTpa,$jmlbadilumReal,$jmlbuaTpa,$jmlbuaReal,$jmlbadimilTpa,$jmlbadimilReal,$jmlbpppphpTpa,$jmlbpppphpReal,$jmlbawasTpa,$jmlbawasReal);
	//$this->view->chartKeuangan = $this->keuangan_serv->toChartKeuangan(10,20,20,40,40,40,40,40,40,40,40,40);
}

//=======================================================================================KEUANGAN==========================================================================
//=======================================================================================PERENCANAAN==========================================================================
function rencana(){
	$tahun = date("Y");
	$dataStatistik = $this->pagu_serv->getStatistikPagu($tahun);
	$this->view->chartRencana1=$this->rencana_serv->toChartPagu(''.$tahun.'',$dataStatistik) ;
	$this->view->dataRencana1=$dataStatistik;		
}
function rencanab(){
	$tahun = date("Y");
		$dataJmlRencana = $this->keu_serv->getCountDataRencanaForEis(" a.thang='$tahun'");
		$jmlResult= count($dataJmlRencana);
		if ($jmlResult !=0){
			for ($j = 0; $j < $jmlResult; $j++) 
			{ 
				$esl=trim($dataJmlRencana[$j]['c_satker']);
				$kdakun=trim($dataJmlRencana[$j]['kdakun']);
				if ($esl=='663157'){					
					if ($kdakun=='52'){$jmlbuaBB=trim($dataJmlRencana[$j]['jumlah']); $this->view->jmlbuaBB=$jmlbuaBB;}
					if ($kdakun=='51'){$jmlbuaBP=trim($dataJmlRencana[$j]['jumlah']); $this->view->jmlbuaBP=$jmlbuaBP;}
					if ($kdakun=='53'){$jmlbuaBM=trim($dataJmlRencana[$j]['jumlah']); $this->view->jmlbuaBM=$jmlbuaBM;}				
				}
				if ($esl=='097450'){				
					if ($kdakun=='52'){$jmlbadilumBB=trim($dataJmlRencana[$j]['jumlah']); $this->view->jmlbadilumBB=$jmlbadilumBB;}
					if ($kdakun=='51'){$jmlbadilumBP=trim($dataJmlRencana[$j]['jumlah']); $this->view->jmlbadilumBP=$jmlbadilumBP;}
					if( $kdakun=='53'){$jmlbadilumBM=trim($dataJmlRencana[$j]['jumlah']); $this->view->jmlbadilumBM=$jmlbadilumBM;}	
				}
				if ($esl=='663712'){
					if ($kdakun=='52'){$jmlbadilagBB=trim($dataJmlRencana[$j]['jumlah']); $this->view->jmlbadilagBB=$jmlbadilagBB;}
					if ($kdakun=='51'){$jmlbadilagBP=trim($dataJmlRencana[$j]['jumlah']); $this->view->jmlbadilagBP=$jmlbadilagBP;}
					if( $kdakun=='53'){$jmlbadilagBM=trim($dataJmlRencana[$j]['jumlah']); $this->view->jmlbadilagBM=$jmlbadilagBM;}
				}
				if ($esl=='663122'){
					if ($kdakun=='52'){$jmlbadimilBB=trim($dataJmlRencana[$j]['jumlah']); $this->view->jmlbadimilBB=$jmlbadimilBB;}
					if ($kdakun=='51'){$jmlbadimilBP=trim($dataJmlRencana[$j]['jumlah']); $this->view->jmlbadimilBP=$jmlbadimilBP;}
					if( $kdakun=='53'){$jmlbadimilBM=trim($dataJmlRencana[$j]['jumlah']); $this->view->jmlbadimilBM=$jmlbadimilBM;}	
				}
				if ($esl=='610378'){
					if ($kdakun=='52'){$jmlbpppphpBB=trim($dataJmlRencana[$j]['jumlah']); $this->view->jmlbpppphpBB=$jmlbpppphpBB;}
					if ($kdakun=='51'){$jmlbpppphpBP=trim($dataJmlRencana[$j]['jumlah']); $this->view->jmlbpppphpBP=$jmlbpppphpBP;}
					if( $kdakun=='53'){$jmlbpppphpBM=trim($dataJmlRencana[$j]['jumlah']); $this->view->jmlbpppphpBM=$jmlbpppphpBM;}
				}
				if ($esl=='663136'){
					if ($kdakun=='52'){$jmlbawasBB=trim($dataJmlRencana[$j]['jumlah']); $this->view->jmlbawasBB=$jmlbawasBB;}
					if ($kdakun=='51'){$jmlbawasBP=trim($dataJmlRencana[$j]['jumlah']); $this->view->jmlbawasBP=$jmlbawasBP;}
					if( $kdakun=='53'){$jmlbawasBM=trim($dataJmlRencana[$j]['jumlah']); $this->view->jmlbawasBM=$jmlbawasBM;}
				}
			}
		}   
	   
   
	   $this->view->chartpagub=$this->rencana_serv->toChartPagub($jmlbuaBB,$jmlbuaBP,$jmlbuaBM,$jmlbadilumBB,$jmlbadilumBP,$jmlbadilumBM,
								   $jmlbadilagBB,$jmlbadilagBP,$jmlbadilagBM,$jmlbadimilBB,$jmlbadimilBP,$jmlbadimilBM,
								   $jmlbpppphpBB,$jmlbpppphpBP,$jmlbpppphpBM,$jmlbawasBB,$jmlbawasBP,$jmlbawasBM);	
		
}
//=======================================================================================PERENCANAAN==========================================================================

//=======================================================================================A S E T==========================================================================
function toChartAset(){
	    $tahun=date('Y');	   
	   $this->view->thnang=$tahun;
	   $this->view->jmltanah=$this->asetdashboard_serv->getJumlahTanah($tahun);
	   $this->view->jmlbdg=$this->asetdashboard_serv->getJumlahBDG($tahun);
	   $this->view->jmlpersediaan=$this->asetdashboard_serv->getJumlahPersediaan($tahun);	
	   $this->view->jmlperalatandanmesin=$this->asetdashboard_serv->getJumlahPeralatandanmesin($tahun);	
	   $this->view->jmljalan=$this->asetdashboard_serv->getJumlahJalan($tahun);	
	   $this->view->jmlatl=$this->asetdashboard_serv->getJumlahAsetTetapLainnya($tahun);
	   $this->view->jmlkdp=$this->asetdashboard_serv->getJumlahKDP($tahun);
	   $this->view->jmlatb=$this->asetdashboard_serv->getJumlahATB($tahun);

	   $this->view->jmlrptanah=$this->asetdashboard_serv->getJumlahRpTanah($tahun);
	   $this->view->jmlrpbdg=$this->asetdashboard_serv->getJumlahRpBDG($tahun);
	   $this->view->jmlrppersediaan=$this->asetdashboard_serv->getJumlahRpPersediaan($tahun);
	   $this->view->jmlrpperalatandanmesin=$this->asetdashboard_serv->getJumlahRpPeralatandanmesin($tahun);
	   $this->view->jmlrpjalan=$this->asetdashboard_serv->getJumlahRpJalan($tahun);
	   $this->view->jmlrpatl=$this->asetdashboard_serv->getJumlahRpAsetTetapLainnya($tahun);
	   $this->view->jmlrpkdp=$this->asetdashboard_serv->getJumlahRpKDP($tahun);
	   $this->view->jmlrpatb=$this->asetdashboard_serv->getJumlahRpATB($tahun);
	   
	   $this->view->chartaset=$this->aset_serv->toChartAset1('Jenis Data',$this->view->jmltanah,$this->view->jmlbdg,$this->view->jmlpersediaan,$this->view->jmlperalatandanmesin,$this->view->jmljalan,$this->view->jmlatl,$this->view->jmlkdp,$this->view->jmlatb,$tahun);

}
function toChartAsetb(){
	    $tahun=date('Y');	   
	   $this->view->thnang=$tahun;
	   $jmlAset=$this->ast_serv->getCountDataForEis($tahun);
		$jmlResultTanah= count($jmlAset);
		if ($jmlResultTanah !=0){
			for ($j = 0; $j < $jmlResultTanah; $j++) 
			{ 
				$esl=trim($jmlAset[$j]['c_satker']);
				$kd_gol=trim($jmlAset[$j]['kd_gol']);
				if ($esl=='663157'){					
					if ($kd_gol=='1'){$jmlbuaTanah=trim($jmlAset[$j]['jumlah']); $this->view->jmlbuaTanah=$jmlbuaTanah;}
					if ($kd_gol=='2'){$jmlbuaPersediaan=trim($jmlAset[$j]['jumlah']); $this->view->jmlbuaPersediaan=$jmlbuaPersediaan;}
					if ($kd_gol=='3'){$jmlbuaperalatandanmesin=trim($jmlAset[$j]['jumlah']); $this->view->jmlbuaperalatandanmesin=$jmlbuaperalatandanmesin;}
					if ($kd_gol=='5'){$jmlbuaJalan=trim($jmlAset[$j]['jumlah']); $this->view->jmlbuaJalan=$jmlbuaJalan;}
					if ($kd_gol=='6'){$jmlbuaAsetTetapLainnya=trim($jmlAset[$j]['jumlah']); $this->view->jmlbuaAsetTetapLainnya=$jmlbuaAsetTetapLainnya;}
					if ($kd_gol=='7'){$jmlbuaKDP=trim($jmlAset[$j]['jumlah']); $this->view->jmlbuaKDP=$jmlbuaKDP;}
					if ($kd_gol=='8'){$jmlbuaATB=trim($jmlAset[$j]['jumlah']); $this->view->jmlbuaATB=$jmlbuaATB;}					
				}
				if ($esl=='097450'){				
					if ($kd_gol=='1'){$jmlbadilumTanah=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadilumTanah=$jmlbadilumTanah;}
					if ($kd_gol=='2'){$jmlbadilumPersediaan=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadilumPersediaan=$jmlbadilumPersediaan;}
					if ($kd_gol=='3'){$jmlbadilumperalatandanmesin=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadilumperalatandanmesin=$jmlbadilumperalatandanmesin;}
					if ($kd_gol=='5'){$jmlbadilumJalan=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadilumJalan=$jmlbadilumJalan;}
					if ($kd_gol=='6'){$jmlbadilumAsetTetapLainnya=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadilumAsetTetapLainnya=$jmlbadilumAsetTetapLainnya;}
					if ($kd_gol=='7'){$jmlbadilumKDP=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadilumKDP=$jmlbadilumKDP;}
					if ($kd_gol=='8'){$jmlbadilumATB=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadilumATB=$jmlbadilumATB;}	
				}
				if ($esl=='663712'){
					if ($kd_gol=='1'){$jmlbadilagTanah=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadilagTanah=$jmlbadilagTanah;}
					if ($kd_gol=='2'){$jmlbadilagPersediaan=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadilagPersediaan=$jmlbadilagPersediaan;}
					if ($kd_gol=='3'){$jmlbadilagperalatandanmesin=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadilagperalatandanmesin=$jmlbadilagperalatandanmesin;}
					if ($kd_gol=='5'){$jmlbadilagJalan=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadilagJalan=$jmlbadilagJalan;}
					if ($kd_gol=='6'){$jmlbadilagAsetTetapLainnya=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadilagAsetTetapLainnya=$jmlbadilagAsetTetapLainnya;}
					if ($kd_gol=='7'){$jmlbadilagKDP=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadilagKDP=$jmlbadilagKDP;}
					if ($kd_gol=='8'){$jmlbadilagATB=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadilagATB=$jmlbadilagATB;}
				}
				if ($esl=='663122'){
					if ($kd_gol=='1'){$jmlbadimilTanah=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadimilTanah=$jmlbadimilTanah;}
					if ($kd_gol=='2'){$jmlbadimilPersediaan=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadimilPersediaan=$jmlbadimilPersediaan;}
					if ($kd_gol=='3'){$jmlbadimilperalatandanmesin=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadimilperalatandanmesin=$jmlbadimilperalatandanmesin;}
					if ($kd_gol=='5'){$jmlbadimilJalan=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadimilJalan=$jmlbadimilJalan;}
					if ($kd_gol=='6'){$jmlbadimilAsetTetapLainnya=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadimilAsetTetapLainnya=$jmlbadimilAsetTetapLainnya;}
					if ($kd_gol=='7'){$jmlbadimilKDP=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadimilKDP=$jmlbadimilKDP;}
					if ($kd_gol=='8'){$jmlbadimilATB=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadimilATB=$jmlbadimilATB;}	
				}
				if ($esl=='610378'){
					if ($kd_gol=='1'){$jmlbpppphpTanah=trim($jmlAset[$j]['jumlah']); $this->view->jmlbpppphpTanah=$jmlbpppphpTanah;}
					if ($kd_gol=='2'){$jmlbpppphpPersediaan=trim($jmlAset[$j]['jumlah']); $this->view->jmlbpppphpPersediaan=$jmlbpppphpPersediaan;}
					if ($kd_gol=='3'){$jmlbpppphpperalatandanmesin=trim($jmlAset[$j]['jumlah']); $this->view->jmlbpppphpperalatandanmesin=$jmlbpppphpperalatandanmesin;}
					if ($kd_gol=='5'){$jmlbpppphpJalan=trim($jmlAset[$j]['jumlah']); $this->view->jmlbpppphpJalan=$jmlbpppphpJalan;}
					if ($kd_gol=='6'){$jmlbpppphpAsetTetapLainnya=trim($jmlAset[$j]['jumlah']); $this->view->jmlbpppphpAsetTetapLainnya=$jmlbpppphpAsetTetapLainnya;}
					if ($kd_gol=='7'){$jmlbpppphpKDP=trim($jmlAset[$j]['jumlah']); $this->view->jmlbpppphpKDP=$jmlbpppphpKDP;}
					if ($kd_gol=='8'){$jmlbpppphpATB=trim($jmlAset[$j]['jumlah']); $this->view->jmlbpppphpATB=$jmlbpppphpATB;}
				}
				if ($esl=='663136'){
					if ($kd_gol=='1'){$jmlbawasTanah=trim($jmlAset[$j]['jumlah']); $this->view->jmlbawasTanah=$jmlbawasTanah;}
					if ($kd_gol=='2'){$jmlbawasPersediaan=trim($jmlAset[$j]['jumlah']); $this->view->jmlbawasPersediaan=$jmlbawasPersediaan;}
					if ($kd_gol=='3'){$jmlbawasperalatandanmesin=trim($jmlAset[$j]['jumlah']); $this->view->jmlbawasperalatandanmesin=$jmlbawasperalatandanmesin;}
					if ($kd_gol=='5'){$jmlbawasJalan=trim($jmlAset[$j]['jumlah']); $this->view->jmlbawasJalan=$jmlbawasJalan;}
					if ($kd_gol=='6'){$jmlbawasAsetTetapLainnya=trim($jmlAset[$j]['jumlah']); $this->view->jmlbawasAsetTetapLainnya=$jmlbawasAsetTetapLainnya;}
					if ($kd_gol=='7'){$jmlbawasKDP=trim($jmlAset[$j]['jumlah']); $this->view->jmlbawasKDP=$jmlbawasKDP;}
					if ($kd_gol=='8'){$jmlbawasATB=trim($jmlAset[$j]['jumlah']); $this->view->jmlbawasATB=$jmlbawasATB;}	
				}
			}
		}   
	   
   
	   $this->view->chartasetb=$this->aset_serv->toChartAsetb($jmlbuaTanah,$jmlbuaPersediaan,$jmlbuaperalatandanmesin,$jmlbuaJalan,$jmlbuaAsetTetapLainnya,$jmlbuaKDP,$jmlbuaATB,				
						$jmlbadilumTanah,$jmlbadilumPersediaan,$jmlbadilumperalatandanmesin,$jmlbadilumJalan,$jmlbadilumAsetTetapLainnya,$jmlbadilumKDP,$jmlbadilumATB,
						$jmlbadilagTanah,$jmlbadilagPersediaan,$jmlbadilagperalatandanmesin,$jmlbadilagJalan,$jmlbadilagAsetTetapLainnya,$jmlbadilagKDP,$jmlbadilagATB,
						$jmlbadimilTanah,$jmlbadimilPersediaan,$jmlbadimilperalatandanmesin,$jmlbadimilJalan,$jmlbadimilAsetTetapLainnya,$jmlbadimilKDP,$jmlbadimilATB,
						$jmlbpppphpTanah,$jmlbpppphpPersediaan,$jmlbpppphpperalatandanmesin,$jmlbpppphpJalan,$jmlbpppphpAsetTetapLainnya,$jmlbpppphpKDP,$jmlbpppphpATB,
						$jmlbawasTanah,$jmlbawasPersediaan,$jmlbawasperalatandanmesin,$jmlbawasJalan,$jmlbawasAsetTetapLainnya,$jmlbawasKDP,$jmlbawasATB);

}

function toChartAsetc(){
	    $tahun=date('Y');	   
	   $this->view->thnang=$tahun;
	   $jmlAset=$this->ast_serv->getCountDataRpForEis($tahun);
		$jmlResultTanah= count($jmlAset);
		if ($jmlResultTanah !=0){
			for ($j = 0; $j < $jmlResultTanah; $j++) 
			{ 
				$esl=trim($jmlAset[$j]['c_satker']);
				$kd_gol=trim($jmlAset[$j]['kd_gol']);
				if ($esl=='663157'){					
					if ($kd_gol=='1'){$jmlbuaTanah=trim($jmlAset[$j]['jumlah']); $this->view->jmlbuaTanah=$jmlbuaTanah;}
					if ($kd_gol=='2'){$jmlbuaPersediaan=trim($jmlAset[$j]['jumlah']); $this->view->jmlbuaPersediaan=$jmlbuaPersediaan;}
					if ($kd_gol=='3'){$jmlbuaperalatandanmesin=trim($jmlAset[$j]['jumlah']); $this->view->jmlbuaperalatandanmesin=$jmlbuaperalatandanmesin;}
					if ($kd_gol=='5'){$jmlbuaJalan=trim($jmlAset[$j]['jumlah']); $this->view->jmlbuaJalan=$jmlbuaJalan;}
					if ($kd_gol=='6'){$jmlbuaAsetTetapLainnya=trim($jmlAset[$j]['jumlah']); $this->view->jmlbuaAsetTetapLainnya=$jmlbuaAsetTetapLainnya;}
					if ($kd_gol=='7'){$jmlbuaKDP=trim($jmlAset[$j]['jumlah']); $this->view->jmlbuaKDP=$jmlbuaKDP;}
					if ($kd_gol=='8'){$jmlbuaATB=trim($jmlAset[$j]['jumlah']); $this->view->jmlbuaATB=$jmlbuaATB;}					
				}
				if ($esl=='097450'){				
					if ($kd_gol=='1'){$jmlbadilumTanah=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadilumTanah=$jmlbadilumTanah;}
					if ($kd_gol=='2'){$jmlbadilumPersediaan=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadilumPersediaan=$jmlbadilumPersediaan;}
					if ($kd_gol=='3'){$jmlbadilumperalatandanmesin=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadilumperalatandanmesin=$jmlbadilumperalatandanmesin;}
					if ($kd_gol=='5'){$jmlbadilumJalan=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadilumJalan=$jmlbadilumJalan;}
					if ($kd_gol=='6'){$jmlbadilumAsetTetapLainnya=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadilumAsetTetapLainnya=$jmlbadilumAsetTetapLainnya;}
					if ($kd_gol=='7'){$jmlbadilumKDP=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadilumKDP=$jmlbadilumKDP;}
					if ($kd_gol=='8'){$jmlbadilumATB=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadilumATB=$jmlbadilumATB;}	
				}
				if ($esl=='663712'){
					if ($kd_gol=='1'){$jmlbadilagTanah=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadilagTanah=$jmlbadilagTanah;}
					if ($kd_gol=='2'){$jmlbadilagPersediaan=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadilagPersediaan=$jmlbadilagPersediaan;}
					if ($kd_gol=='3'){$jmlbadilagperalatandanmesin=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadilagperalatandanmesin=$jmlbadilagperalatandanmesin;}
					if ($kd_gol=='5'){$jmlbadilagJalan=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadilagJalan=$jmlbadilagJalan;}
					if ($kd_gol=='6'){$jmlbadilagAsetTetapLainnya=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadilagAsetTetapLainnya=$jmlbadilagAsetTetapLainnya;}
					if ($kd_gol=='7'){$jmlbadilagKDP=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadilagKDP=$jmlbadilagKDP;}
					if ($kd_gol=='8'){$jmlbadilagATB=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadilagATB=$jmlbadilagATB;}
				}
				if ($esl=='663122'){
					if ($kd_gol=='1'){$jmlbadimilTanah=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadimilTanah=$jmlbadimilTanah;}
					if ($kd_gol=='2'){$jmlbadimilPersediaan=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadimilPersediaan=$jmlbadimilPersediaan;}
					if ($kd_gol=='3'){$jmlbadimilperalatandanmesin=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadimilperalatandanmesin=$jmlbadimilperalatandanmesin;}
					if ($kd_gol=='5'){$jmlbadimilJalan=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadimilJalan=$jmlbadimilJalan;}
					if ($kd_gol=='6'){$jmlbadimilAsetTetapLainnya=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadimilAsetTetapLainnya=$jmlbadimilAsetTetapLainnya;}
					if ($kd_gol=='7'){$jmlbadimilKDP=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadimilKDP=$jmlbadimilKDP;}
					if ($kd_gol=='8'){$jmlbadimilATB=trim($jmlAset[$j]['jumlah']); $this->view->jmlbadimilATB=$jmlbadimilATB;}	
				}
				if ($esl=='610378'){
					if ($kd_gol=='1'){$jmlbpppphpTanah=trim($jmlAset[$j]['jumlah']); $this->view->jmlbpppphpTanah=$jmlbpppphpTanah;}
					if ($kd_gol=='2'){$jmlbpppphpPersediaan=trim($jmlAset[$j]['jumlah']); $this->view->jmlbpppphpPersediaan=$jmlbpppphpPersediaan;}
					if ($kd_gol=='3'){$jmlbpppphpperalatandanmesin=trim($jmlAset[$j]['jumlah']); $this->view->jmlbpppphpperalatandanmesin=$jmlbpppphpperalatandanmesin;}
					if ($kd_gol=='5'){$jmlbpppphpJalan=trim($jmlAset[$j]['jumlah']); $this->view->jmlbpppphpJalan=$jmlbpppphpJalan;}
					if ($kd_gol=='6'){$jmlbpppphpAsetTetapLainnya=trim($jmlAset[$j]['jumlah']); $this->view->jmlbpppphpAsetTetapLainnya=$jmlbpppphpAsetTetapLainnya;}
					if ($kd_gol=='7'){$jmlbpppphpKDP=trim($jmlAset[$j]['jumlah']); $this->view->jmlbpppphpKDP=$jmlbpppphpKDP;}
					if ($kd_gol=='8'){$jmlbpppphpATB=trim($jmlAset[$j]['jumlah']); $this->view->jmlbpppphpATB=$jmlbpppphpATB;}
				}
				if ($esl=='663136'){
					if ($kd_gol=='1'){$jmlbawasTanah=trim($jmlAset[$j]['jumlah']); $this->view->jmlbawasTanah=$jmlbawasTanah;}
					if ($kd_gol=='2'){$jmlbawasPersediaan=trim($jmlAset[$j]['jumlah']); $this->view->jmlbawasPersediaan=$jmlbawasPersediaan;}
					if ($kd_gol=='3'){$jmlbawasperalatandanmesin=trim($jmlAset[$j]['jumlah']); $this->view->jmlbawasperalatandanmesin=$jmlbawasperalatandanmesin;}
					if ($kd_gol=='5'){$jmlbawasJalan=trim($jmlAset[$j]['jumlah']); $this->view->jmlbawasJalan=$jmlbawasJalan;}
					if ($kd_gol=='6'){$jmlbawasAsetTetapLainnya=trim($jmlAset[$j]['jumlah']); $this->view->jmlbawasAsetTetapLainnya=$jmlbawasAsetTetapLainnya;}
					if ($kd_gol=='7'){$jmlbawasKDP=trim($jmlAset[$j]['jumlah']); $this->view->jmlbawasKDP=$jmlbawasKDP;}
					if ($kd_gol=='8'){$jmlbawasATB=trim($jmlAset[$j]['jumlah']); $this->view->jmlbawasATB=$jmlbawasATB;}	
				}
			}
		}   
	   
   
	   $this->view->chartasetc=$this->aset_serv->toChartAsetb($jmlbuaTanah,$jmlbuaPersediaan,$jmlbuaperalatandanmesin,$jmlbuaJalan,$jmlbuaAsetTetapLainnya,$jmlbuaKDP,$jmlbuaATB,				
						$jmlbadilumTanah,$jmlbadilumPersediaan,$jmlbadilumperalatandanmesin,$jmlbadilumJalan,$jmlbadilumAsetTetapLainnya,$jmlbadilumKDP,$jmlbadilumATB,
						$jmlbadilagTanah,$jmlbadilagPersediaan,$jmlbadilagperalatandanmesin,$jmlbadilagJalan,$jmlbadilagAsetTetapLainnya,$jmlbadilagKDP,$jmlbadilagATB,
						$jmlbadimilTanah,$jmlbadimilPersediaan,$jmlbadimilperalatandanmesin,$jmlbadimilJalan,$jmlbadimilAsetTetapLainnya,$jmlbadimilKDP,$jmlbadimilATB,
						$jmlbpppphpTanah,$jmlbpppphpPersediaan,$jmlbpppphpperalatandanmesin,$jmlbpppphpJalan,$jmlbpppphpAsetTetapLainnya,$jmlbpppphpKDP,$jmlbpppphpATB,
						$jmlbawasTanah,$jmlbawasPersediaan,$jmlbawasperalatandanmesin,$jmlbawasJalan,$jmlbawasAsetTetapLainnya,$jmlbawasKDP,$jmlbawasATB);

}
//=======================================================================================A S E T==========================================================================
//=======================================================================================S D M==========================================================================
    
function toSdmChartUsia(){
	$jml25 = $this->statistik_serv->getCountDataForEis(" and (EXTRACT(years from AGE(now(), d_peg_lahir))<= 25)  ");
		$jmlResult25= count($jml25);
		if ($jmlResult25 !=0){
			for ($j = 0; $j < $jmlResult25; $j++) 
			{ 
				$esl=trim($jml25[$j]['c_eselon_i']);
				if ($esl=='01'){$jmlbua25=trim($jml25[$j]['jumlah']); $this->view->jmlbua25=$jmlbua25;}
				if ($esl=='03'){$jmlbadilum25=trim($jml25[$j]['jumlah']); $this->view->jmlbadilum25=$jmlbadilum25;}
				if ($esl=='04'){$jmlbadilag25=trim($jml25[$j]['jumlah']); $this->view->jmlbadilag25=$jmlbadilag25;}
				if ($esl=='05'){$jmlbadimil25=trim($jml25[$j]['jumlah']); $this->view->jmlbadimil25=$jmlbadimil25;}
				if ($esl=='06'){$jmlbpppphp25=trim($jml25[$j]['jumlah']); $this->view->jmlbpppphp25=$jmlbpppphp25;}
				if ($esl=='07'){$jmlbawas25=trim($jml25[$j]['jumlah']); $this->view->jmlbawas25=$jmlbawas25;}
			}
		}
	$jml2635 = $this->statistik_serv->getCountDataForEis(" and (EXTRACT(years from AGE(now(), d_peg_lahir))between 26 and 35) ");
		$jmlResult2635= count($jml2635);
		if ($jmlResult2635 !=0){
			for ($j = 0; $j < $jmlResult2635; $j++) 
			{ 
				$esl=trim($jml2635[$j]['c_eselon_i']);
				if ($esl=='01'){$jmlbua2635=trim($jml2635[$j]['jumlah']); $this->view->jmlbua2635=$jmlbua2635;}
				if ($esl=='03'){$jmlbadilum2635=trim($jml2635[$j]['jumlah']); $this->view->jmlbadilum2635=$jmlbadilum2635;}
				if ($esl=='04'){$jmlbadilag2635=trim($jml2635[$j]['jumlah']); $this->view->jmlbadilag2635=$jmlbadilag2635;}
				if ($esl=='05'){$jmlbadimil2635=trim($jml2635[$j]['jumlah']); $this->view->jmlbadimil2635=$jmlbadimil2635;}
				if ($esl=='06'){$jmlbpppphp2635=trim($jml2635[$j]['jumlah']); $this->view->jmlbpppphp2635=$jmlbpppphp2635;}
				if ($esl=='07'){$jmlbawas2635=trim($jml2635[$j]['jumlah']); $this->view->jmlbawas2635=$jmlbawas2635;}
			}
		}

	$jml3645 = $this->statistik_serv->getCountDataForEis(" and (EXTRACT(years from AGE(now(), d_peg_lahir))between 36 and 45) ");
		$jmlResult3645= count($jml3645);
		if ($jmlResult3645 !=0){
			for ($j = 0; $j < $jmlResult3645; $j++) 
			{ 
				$esl=trim($jml3645[$j]['c_eselon_i']);
				if ($esl=='01'){$jmlbua3645=trim($jml3645[$j]['jumlah']); $this->view->jmlbua3645=$jmlbua3645;}
				if ($esl=='03'){$jmlbadilum3645=trim($jml3645[$j]['jumlah']); $this->view->jmlbadilum3645=$jmlbadilum3645;}
				if ($esl=='04'){$jmlbadilag3645=trim($jml3645[$j]['jumlah']); $this->view->jmlbadilag3645=$jmlbadilag3645;}
				if ($esl=='05'){$jmlbadimil3645=trim($jml3645[$j]['jumlah']); $this->view->jmlbadimil3645=$jmlbadimil3645;}
				if ($esl=='06'){$jmlbpppphp3645=trim($jml3645[$j]['jumlah']); $this->view->jmlbpppphp3645=$jmlbpppphp3645;}
				if ($esl=='07'){$jmlbawas3645=trim($jml3645[$j]['jumlah']); $this->view->jmlbawas3645=$jmlbawas3645;}
			}
		}
	$jml4655 = $this->statistik_serv->getCountDataForEis(" and (EXTRACT(years from AGE(now(), d_peg_lahir))between 46 and 55) ");
		$jmlResult4655= count($jml4655);
		if ($jmlResult4655 !=0){
			for ($j = 0; $j < $jmlResult4655; $j++) 
			{ 
				$esl=trim($jml4655[$j]['c_eselon_i']);
				if ($esl=='01'){$jmlbua4655=trim($jml4655[$j]['jumlah']); $this->view->jmlbua4655=$jmlbua4655;}
				if ($esl=='03'){$jmlbadilum4655=trim($jml4655[$j]['jumlah']); $this->view->jmlbadilum4655=$jmlbadilum4655;}
				if ($esl=='04'){$jmlbadilag4655=trim($jml4655[$j]['jumlah']); $this->view->jmlbadilag4655=$jmlbadilag4655;}
				if ($esl=='05'){$jmlbadimil4655=trim($jml4655[$j]['jumlah']); $this->view->jmlbadimil4655=$jmlbadimil4655;}
				if ($esl=='06'){$jmlbpppphp4655=trim($jml3645[$j]['jumlah']); $this->view->jmlbpppphp4655=$jmlbpppphp4655;}
				if ($esl=='07'){$jmlbawas4655=trim($jml3645[$j]['jumlah']); $this->view->jmlbawas4655=$jmlbawas4655;}
			}
		}
	$jml56 = $this->statistik_serv->getCountDataForEis(" and (EXTRACT(years from AGE(now(), d_peg_lahir))>= 56)  ");
		$jmlResult56= count($jml56);
		if ($jmlResult56 !=0){
			for ($j = 0; $j < $jmlResult56; $j++) 
			{ 
				$esl=trim($jml56[$j]['c_eselon_i']);
				if ($esl=='01'){$jmlbua56=trim($jml56[$j]['jumlah']); $this->view->jmlbua56=$jmlbua56;}
				if ($esl=='03'){$jmlbadilum56=trim($jml56[$j]['jumlah']); $this->view->jmlbadilum56=$jmlbadilum56;}
				if ($esl=='04'){$jmlbadilag56=trim($jml56[$j]['jumlah']); $this->view->jmlbadilag56=$jmlbadilag56;}
				if ($esl=='05'){$jmlbadimil56=trim($jml56[$j]['jumlah']); $this->view->jmlbadimil56=$jmlbadimil56;}
				if ($esl=='06'){$jmlbpppphp56=trim($jml56[$j]['jumlah']); $this->view->jmlbpppphp56=$jmlbpppphp56;}
				if ($esl=='07'){$jmlbawas56=trim($jml56[$j]['jumlah']); $this->view->jmlbawas56=$jmlbawas56;}
			}
		}
	
		$this->view->chartUsia=$this->sdm_serv->toChartUsia($jmlbadilag25,$jmlbadilag2635,$jmlbadilag3645,$jmlbadilag4655,$jmlbadilag56,$jmlbadilum25,
		$jmlbadilum2635,$jmlbadilum3645,$jmlbadilum4655,$jmlbadilum56,$jmlbua25,$jmlbua2635,$jmlbua3645,$jmlbua4655,$jmlbua56,$jmlbadimil25,$jmlbadimil2635,
		$jmlbadimil3645,$jmlbadimil4655,$jmlbadimil56,$jmlbpppphp25,	$jmlbpppphp2635,$jmlbpppphp3645,$jmlbpppphp4655,$jmlbpppphp56,$jmlbawas25,$jmlbawas2635,
		$jmlbawas3645,$jmlbawas4655,$jmlbawas56) ;

}	

function toSdmChartPendidikan(){
		
	$jmlsd = $this->statistik_serv->getCountDataForEis(" and c_pend ='29'");
		$jmlResultsd= count($jmlsd);
		if ($jmlResultsd !=0){
			for ($j = 0; $j < $jmlResultsd; $j++) 
			{ 
				$esl=trim($jmlsd[$j]['c_eselon_i']);
				if ($esl=='01'){$jmlbuasd=trim($jmlsd[$j]['jumlah']); $this->view->jmlbuasd=$jmlbuasd;}
				if ($esl=='03'){$jmlbadilumsd=trim($jmlsd[$j]['jumlah']); $this->view->jmlbadilumsd=$jmlbadilumsd;}
				if ($esl=='04'){$jmlbadilagsd=trim($jmlsd[$j]['jumlah']); $this->view->jmlbadilagsd=$jmlbadilagsd;}
				if ($esl=='05'){$jmlbadimilsd=trim($jmlsd[$j]['jumlah']); $this->view->jmlbadimilsd=$jmlbadimilsd;}
				if ($esl=='06'){$jmlbpppphpsd=trim($jmlsd[$j]['jumlah']); $this->view->jmlbpppphpsd=$jmlbpppphpsd;}
				if ($esl=='07'){$jmlbawassd=trim($jmlsd[$j]['jumlah']); $this->view->jmlbawassd=$jmlbawassd;}
			}
		}
	$jmlsmp = $this->statistik_serv->getCountDataForEis(" and c_pend ='41'");
		$jmlResultsmp= count($jmlsmp);
		if ($jmlResultsmp !=0){
			for ($j = 0; $j < $jmlResultsmp; $j++) 
			{ 
				$esl=trim($jmlsmp[$j]['c_eselon_i']);
				if ($esl=='01'){$jmlbuasmp=trim($jmlsmp[$j]['jumlah']); $this->view->jmlbuasmp=$jmlbuasmp;}
				if ($esl=='03'){$jmlbadilumsmp=trim($jmlsmp[$j]['jumlah']); $this->view->jmlbadilumsmp=$jmlbadilumsmp;}
				if ($esl=='04'){$jmlbadilagsmp=trim($jmlsmp[$j]['jumlah']); $this->view->jmlbadilagsmp=$jmlbadilagsmp;}
				if ($esl=='05'){$jmlbadimilsmp=trim($jmlsmp[$j]['jumlah']); $this->view->jmlbadimilsmp=$jmlbadimilsmp;}
				if ($esl=='06'){$jmlbpppphpsmp=trim($jmlsmp[$j]['jumlah']); $this->view->jmlbpppphpsmp=$jmlbpppphpsmp;}
				if ($esl=='07'){$jmlbawassmp=trim($jmlsmp[$j]['jumlah']); $this->view->jmlbawassmp=$jmlbawassmp;}
			}
		}

	$jmlsma = $this->statistik_serv->getCountDataForEis(" and c_pend ='40'");
		$jmlResultsma= count($jmlsma);
		if ($jmlResultsma !=0){
			for ($j = 0; $j < $jmlResultsma; $j++) 
			{ 
				$esl=trim($jmlsma[$j]['c_eselon_i']);
				if ($esl=='01'){$jmlbuasma=trim($jmlsma[$j]['jumlah']); $this->view->jmlbuasma=$jmlbuasma;}
				if ($esl=='03'){$jmlbadilumsma=trim($jmlsma[$j]['jumlah']); $this->view->jmlbadilumsma=$jmlbadilumsma;}
				if ($esl=='04'){$jmlbadilagsma=trim($jmlsma[$j]['jumlah']); $this->view->jmlbadilagsma=$jmlbadilagsma;}
				if ($esl=='05'){$jmlbadimilsma=trim($jmlsma[$j]['jumlah']); $this->view->jmlbadimilsma=$jmlbadimilsma;}
				if ($esl=='06'){$jmlbpppphpsma=trim($jmlsma[$j]['jumlah']); $this->view->jmlbpppphpsma=$jmlbpppphpsma;}
				if ($esl=='07'){$jmlbawassma=trim($jmlsma[$j]['jumlah']); $this->view->jmlbawassma=$jmlbawassma;}
			}
		}
	$jmld1 = $this->statistik_serv->getCountDataForEis(" and c_pend ='04'");
		$jmlResultd1= count($jmld1);
		if ($jmlResultd1 !=0){
			for ($j = 0; $j < $jmlResultd1; $j++) 
			{ 
				$esl=trim($jmld1[$j]['c_eselon_i']);
				if ($esl=='01'){$jmlbuad1=trim($jmld1[$j]['jumlah']); $this->view->jmlbuad1=$jmlbuad1;}
				if ($esl=='03'){$jmlbadilumd1=trim($jmld1[$j]['jumlah']); $this->view->jmlbadilumd1=$jmlbadilumd1;}
				if ($esl=='04'){$jmlbadilagd1=trim($jmld1[$j]['jumlah']); $this->view->jmlbadilagd1=$jmlbadilagd1;}
				if ($esl=='05'){$jmlbadimild1=trim($jmld1[$j]['jumlah']); $this->view->jmlbadimild1=$jmlbadimild1;}
				if ($esl=='06'){$jmlbpppphpd1=trim($jmlsma[$j]['jumlah']); $this->view->jmlbpppphpd1=$jmlbpppphpd1;}
				if ($esl=='07'){$jmlbawasd1=trim($jmlsma[$j]['jumlah']); $this->view->jmlbawasd1=$jmlbawasd1;}
			}
		}
	$jmld2 = $this->statistik_serv->getCountDataForEis(" and c_pend ='05'");
		$jmlResultd2= count($jmld2);
		if ($jmlResultd2 !=0){
			for ($j = 0; $j < $jmlResultd2; $j++) 
			{ 
				$esl=trim($jmld2[$j]['c_eselon_i']);
				if ($esl=='01'){$jmlbuad2=trim($jmld2[$j]['jumlah']); $this->view->jmlbuad2=$jmlbuad2;}
				if ($esl=='03'){$jmlbadilumd2=trim($jmld2[$j]['jumlah']); $this->view->jmlbadilumd2=$jmlbadilumd2;}
				if ($esl=='04'){$jmlbadilagd2=trim($jmld2[$j]['jumlah']); $this->view->jmlbadilagd2=$jmlbadilagd2;}
				if ($esl=='05'){$jmlbadimild2=trim($jmld2[$j]['jumlah']); $this->view->jmlbadimild2=$jmlbadimild2;}
				if ($esl=='06'){$jmlbpppphpd2=trim($jmld2[$j]['jumlah']); $this->view->jmlbpppphpd2=$jmlbpppphpd2;}
				if ($esl=='07'){$jmlbawasd2=trim($jmld2[$j]['jumlah']); $this->view->jmlbawasd2=$jmlbawasd2;}
			}
		}

	$jmld3 = $this->statistik_serv->getCountDataForEis(" and c_pend ='32'");
		$jmlResultd3= count($jmld3);
		if ($jmlResultd3 !=0){
			for ($j = 0; $j < $jmlResultd3; $j++) 
			{ 
				$esl=trim($jmld3[$j]['c_eselon_i']);
				if ($esl=='01'){$jmlbuad3=trim($jmld3[$j]['jumlah']); $this->view->jmlbuad3=$jmlbuad3;}
				if ($esl=='03'){$jmlbadilumd3=trim($jmld3[$j]['jumlah']); $this->view->jmlbadilumd3=$jmlbadilumd3;}
				if ($esl=='04'){$jmlbadilagd3=trim($jmld3[$j]['jumlah']); $this->view->jmlbadilagd3=$jmlbadilagd3;}
				if ($esl=='05'){$jmlbadimild3=trim($jmld3[$j]['jumlah']); $this->view->jmlbadimild3=$jmlbadimild3;}
				if ($esl=='06'){$jmlbpppphpd3=trim($jmld3[$j]['jumlah']); $this->view->jmlbpppphpd3=$jmlbpppphpd3;}
				if ($esl=='07'){$jmlbawasd3=trim($jmld3[$j]['jumlah']); $this->view->jmlbawasd3=$jmlbawasd3;}
			}
		}

	$jmld4 = $this->statistik_serv->getCountDataForEis(" and c_pend ='07'");
		$jmlResultd4= count($jmld4);
		if ($jmlResultd4 !=0){
			for ($j = 0; $j < $jmlResultd4; $j++) 
			{ 
				$esl=trim($jmld4[$j]['c_eselon_i']);
				if ($esl=='01'){$jmlbuad4=trim($jmld4[$j]['jumlah']); $this->view->jmlbuad4=$jmlbuad4;}
				if ($esl=='03'){$jmlbadilumd4=trim($jmld4[$j]['jumlah']); $this->view->jmlbadilumd4=$jmlbadilumd4;}
				if ($esl=='04'){$jmlbadilagd4=trim($jmld4[$j]['jumlah']); $this->view->jmlbadilagd4=$jmlbadilagd4;}
				if ($esl=='05'){$jmlbadimild4=trim($jmld4[$j]['jumlah']); $this->view->jmlbadimild4=$jmlbadimild4;}
				if ($esl=='06'){$jmlbpppphpd4=trim($jmld4[$j]['jumlah']); $this->view->jmlbpppphpd4=$jmlbpppphpd4;}
				if ($esl=='07'){$jmlbawasd4=trim($jmld4[$j]['jumlah']); $this->view->jmlbawasd4=$jmlbawasd4;}
			}
		}

	$jmls1 = $this->statistik_serv->getCountDataForEis(" and c_pend ='36'");
		$jmlResults1= count($jmls1);
		if ($jmlResults1 !=0){
			for ($j = 0; $j < $jmlResults1; $j++) 
			{ 
				$esl=trim($jmls1[$j]['c_eselon_i']);
				if ($esl=='01'){$jmlbuas1=trim($jmls1[$j]['jumlah']); $this->view->jmlbuas1=$jmlbuas1;}
				if ($esl=='03'){$jmlbadilums1=trim($jmls1[$j]['jumlah']); $this->view->jmlbadilums1=$jmlbadilums1;}
				if ($esl=='04'){$jmlbadilags1=trim($jmls1[$j]['jumlah']); $this->view->jmlbadilags1=$jmlbadilags1;}
				if ($esl=='05'){$jmlbadimils1=trim($jmls1[$j]['jumlah']); $this->view->jmlbadimils1=$jmlbadimils1;}
				if ($esl=='06'){$jmlbpppphps1=trim($jmls1[$j]['jumlah']); $this->view->jmlbpppphps1=$jmlbpppphps1;}
				if ($esl=='07'){$jmlbawass1=trim($jmls1[$j]['jumlah']); $this->view->jmlbawass1=$jmlbawass1;}
			}
		}	
		
	$jmls2 = $this->statistik_serv->getCountDataForEis(" and c_pend ='09'");
		$jmlResults2= count($jmls2);
		if ($jmlResults2 !=0){
			for ($j = 0; $j < $jmlResults2; $j++) 
			{ 
				$esl=trim($jmls2[$j]['c_eselon_i']);
				if ($esl=='01'){$jmlbuas2=trim($jmls2[$j]['jumlah']); $this->view->jmlbuas2=$jmlbuas2;}
				if ($esl=='03'){$jmlbadilums2=trim($jmls2[$j]['jumlah']); $this->view->jmlbadilums2=$jmlbadilums2;}
				if ($esl=='04'){$jmlbadilags2=trim($jmls2[$j]['jumlah']); $this->view->jmlbadilags2=$jmlbadilags2;}
				if ($esl=='05'){$jmlbadimils2=trim($jmls2[$j]['jumlah']); $this->view->jmlbadimils2=$jmlbadimils2;}
				if ($esl=='06'){$jmlbpppphps2=trim($jmls2[$j]['jumlah']); $this->view->jmlbpppphps2=$jmlbpppphps2;}
				if ($esl=='07'){$jmlbawass2=trim($jmls2[$j]['jumlah']); $this->view->jmlbawass2=$jmlbawass2;}
			}
		}

	$jmls3 = $this->statistik_serv->getCountDataForEis(" and c_pend ='10'");
		$jmlResults3= count($jmls3);
		if ($jmlResults3 !=0){
			for ($j = 0; $j < $jmlResults3; $j++) 
			{ 
				$esl=trim($jmls3[$j]['c_eselon_i']);
				if ($esl=='01'){$jmlbuas3=trim($jmls3[$j]['jumlah']); $this->view->jmlbuas3=$jmlbuas3;}
				if ($esl=='03'){$jmlbadilums3=trim($jmls3[$j]['jumlah']); $this->view->jmlbadilums3=$jmlbadilums3;}
				if ($esl=='04'){$jmlbadilags3=trim($jmls3[$j]['jumlah']); $this->view->jmlbadilags3=$jmlbadilags3;}
				if ($esl=='05'){$jmlbadimils3=trim($jmls3[$j]['jumlah']); $this->view->jmlbadimils3=$jmlbadimils3;}
				if ($esl=='06'){$jmlbpppphps3=trim($jmls3[$j]['jumlah']); $this->view->jmlbpppphps3=$jmlbpppphps3;}
				if ($esl=='07'){$jmlbawass3=trim($jmls3[$j]['jumlah']); $this->view->jmlbawass3=$jmlbawass3;}
			}
		}
	
	$this->view->chartPend=$this->sdm_serv->toChartPendidikan ($jmlbadilumsd,$jmlbadilumsmp,$jmlbadilumsma,$jmlbadilumd1,$jmlbadilumd2,$jmlbadilumd3,$jmlbadilumd4,$jmlbadilums1,$jmlbadilums2,$jmlbadilums3,
		$jmlbuasd,$jmlbuasmp,$jmlbuasma,$jmlbuad1,$jmlbuad2,$jmlbuad3,$jmlbuad4,$jmlbuas1,$jmlbuas2,$jmlbuas3,$jmlbadimilsd,$jmlbadimilsmp,$jmlbadimilsma,
		$jmlbadimild1,$jmlbadimild2,$jmlbadimild3,$jmlbadimild4,$jmlbadimils1,$jmlbadimils2,$jmlbadimils3,$jmlbpppphpsd,$jmlbpppphpsmp,$jmlbpppphpsma,
		$jmlbpppphpd1,$jmlbpppphpd2,$jmlbpppphpd3,$jmlbpppphpd4,$jmlbpppphps1,$jmlbpppphps2,$jmlbpppphps3,$jmlbawassd,$jmlbawassmp,$jmlbawassma,
		$jmlbawasd1,$jmlbawasd2,$jmlbawasd3,$jmlbawasd4,$jmlbawass1,$jmlbawass2,$jmlbawass3);
	
}

function toSdmChartGolongan(){

		$jmlgol1 = $this->statistik_serv->getCountDataForEis(" and c_golongan in ('20','21','22','23')");
		$jmlResultgol1= count($jmlgol1);
		if ($jmlResultgol1 !=0){
			for ($j = 0; $j < $jmlResultgol1; $j++) 
			{ 
				$esl=trim($jmlgol1[$j]['c_eselon_i']);
				if ($esl=='01'){$jmlbuagol1=trim($jmlgol1[$j]['jumlah']); $this->view->jmlbuagol1=$jmlbuagol1;}
				if ($esl=='03'){$jmlbadilumgol1=trim($jmlgol1[$j]['jumlah']); $this->view->jmlbadilumgol1=$jmlbadilumgol1;}
				if ($esl=='04'){$jmlbadilaggol1=trim($jmlgol1[$j]['jumlah']); $this->view->jmlbadilaggol1=$jmlbadilaggol1;}
				if ($esl=='05'){$jmlbadimilgol1=trim($jmlgol1[$j]['jumlah']); $this->view->jmlbadimilgol1=$jmlbadimilgol1;}
				if ($esl=='06'){$jmlbpppphpgol1=trim($jmlgol1[$j]['jumlah']); $this->view->jmlbpppphpgol1=$jmlbpppphpgol1;}
				if ($esl=='07'){$jmlbawasgol1=trim($jmlgol1[$j]['jumlah']); $this->view->jmlbawasgol1=$jmlbawasgol1;}
			}
		}
		
	$jmlgol2 = $this->statistik_serv->getCountDataForEis(" and c_golongan in ('14','15','16','17')");
		$jmlResultgol2= count($jmlgol2);
		if ($jmlResultgol2 !=0){
			for ($j = 0; $j < $jmlResultgol2; $j++) 
			{ 
				$esl=trim($jmlgol2[$j]['c_eselon_i']);
				if ($esl=='01'){$jmlbuagol2=trim($jmlgol2[$j]['jumlah']); $this->view->jmlbuagol2=$jmlbuagol2;}
				if ($esl=='03'){$jmlbadilumgol2=trim($jmlgol2[$j]['jumlah']); $this->view->jmlbadilumgol2=$jmlbadilumgol2;}
				if ($esl=='04'){$jmlbadilaggol2=trim($jmlgol2[$j]['jumlah']); $this->view->jmlbadilaggol2=$jmlbadilaggol2;}
				if ($esl=='05'){$jmlbadimilgol2=trim($jmlgol2[$j]['jumlah']); $this->view->jmlbadimilgol2=$jmlbadimilgol2;}
				if ($esl=='06'){$jmlbpppphpgol2=trim($jmlgol2[$j]['jumlah']); $this->view->jmlbpppphpgol2=$jmlbpppphpgol2;}
				if ($esl=='07'){$jmlbawasgol2=trim($jmlgol2[$j]['jumlah']); $this->view->jmlbawasgol2=$jmlbawasgol2;}
			}
		}

	$jmlgol3 = $this->statistik_serv->getCountDataForEis(" and c_golongan in ('08','09','10','11')");
		$jmlResultgol3= count($jmlgol3);
		if ($jmlResultgol3 !=0){
			for ($j = 0; $j < $jmlResultgol3; $j++) 
			{ 
				$esl=trim($jmlgol3[$j]['c_eselon_i']);
				if ($esl=='01'){$jmlbuagol3=trim($jmlgol3[$j]['jumlah']); $this->view->jmlbuagol3=$jmlbuagol3;}
				if ($esl=='03'){$jmlbadilumgol3=trim($jmlgol3[$j]['jumlah']); $this->view->jmlbadilumgol3=$jmlbadilumgol3;}
				if ($esl=='04'){$jmlbadilaggol3=trim($jmlgol3[$j]['jumlah']); $this->view->jmlbadilaggol3=$jmlbadilaggol3;}
				if ($esl=='05'){$jmlbadimilgol3=trim($jmlgol3[$j]['jumlah']); $this->view->jmlbadimilgol3=$jmlbadimilgol3;}
				if ($esl=='06'){$jmlbpppphpgol3=trim($jmlgol3[$j]['jumlah']); $this->view->jmlbpppphpgol3=$jmlbpppphpgol3;}
				if ($esl=='07'){$jmlbawasgol3=trim($jmlgol3[$j]['jumlah']); $this->view->jmlbawasgol3=$jmlbawasgol3;}
			}
		}
	$jmlgol4 = $this->statistik_serv->getCountDataForEis(" and c_golongan in ('03','04','05','06','07')");
		$jmlResultgol4= count($jmlgol4);
		if ($jmlResultgol4 !=0){
			for ($j = 0; $j < $jmlResultgol4; $j++) 
			{ 
				$esl=trim($jmlgol4[$j]['c_eselon_i']);
				if ($esl=='01'){$jmlbuagol4=trim($jmlgol4[$j]['jumlah']); $this->view->jmlbuagol4=$jmlbuagol4;}
				if ($esl=='03'){$jmlbadilumgol4=trim($jmlgol4[$j]['jumlah']); $this->view->jmlbadilumgol4=$jmlbadilumgol4;}
				if ($esl=='04'){$jmlbadilaggol4=trim($jmlgol4[$j]['jumlah']); $this->view->jmlbadilaggol4=$jmlbadilaggol4;}
				if ($esl=='05'){$jmlbadimilgol4=trim($jmlgol4[$j]['jumlah']); $this->view->jmlbadimilgol4=$jmlbadimilgol4;}
				if ($esl=='06'){$jmlbpppphpgol4=trim($jmlgol3[$j]['jumlah']); $this->view->jmlbpppphpgol4=$jmlbpppphpgol4;}
				if ($esl=='07'){$jmlbawasgol4=trim($jmlgol3[$j]['jumlah']); $this->view->jmlbawasgol4=$jmlbawasgol4;}
			}
		}
	
	$this->view->chartGol=$this->sdm_serv->toChartGolongan($jmlbadilaggol1,$jmlbadilaggol2,$jmlbadilaggol3,$jmlbadilaggol4,$jmlbadilumgol1,$jmlbadilumgol2,$jmlbadilumgol3,
			$jmlbadilumgol4,$jmlbuagol1,$jmlbuagol2,$jmlbuagol3,$jmlbuagol4,$jmlbadimilgol1,$jmlbadimilgol2,$jmlbadimilgol3,
			$jmlbadimilgol4,$jmlbpppphpgol1,$jmlbpppphpgol2,$jmlbpppphpgol3,$jmlbpppphpgol4,$jmlbawasgol1,$jmlbawasgol2,$jmlbawasgol3,$jmlbawasgol4);	

	}
	
function toSdmChartPensiun(){

	$thnpen=date('Y')+1;
	$tgla="$thnpen-01-01";	
	$jmlpens = $this->statistik_serv->getCountDataForEis(" and (EXTRACT(years from AGE('$tgla', d_peg_lahir))= q_usia_pensiun)");
	$jmlResultpens= count($jmlpens);
	if ($jmlResultpens !=0){
		for ($j = 0; $j < $jmlResultpens; $j++) 
		{ 
			$esl=trim($jmlpens[$j]['c_eselon_i']);
			if ($esl=='01'){$jmlbuapens=trim($jmlpens[$j]['jumlah']); $this->view->jmlbuapens=$jmlbuapens;}
			if ($esl=='03'){$jmlbadilumpens=trim($jmlpens[$j]['jumlah']); $this->view->jmlbadilumpens=$jmlbadilumpens;}
			if ($esl=='04'){$jmlbadilagpens=trim($jmlpens[$j]['jumlah']); $this->view->jmlbadilagpens=$jmlbadilagpens;}
			if ($esl=='05'){$jmlbadimilpens=trim($jmlpens[$j]['jumlah']); $this->view->jmlbadimilpens=$jmlbadimilpens;}
			if ($esl=='06'){$jmlbpppphppens=trim($jmlpens[$j]['jumlah']); $this->view->jmlbpppphppens=$jmlbpppphppens;}
			if ($esl=='07'){$jmlbawaspens=trim($jmlpens[$j]['jumlah']); $this->view->jmlbawaspens=$jmlbawaspens;}
		}
	}

	$this->view->chartPens=$this->sdm_serv->toChartPensiun($jmlbadilagpens,$jmlbadilumpens,$jmlbuapens,$jmlbadimilpens,$jmlbpppphppens,$jmlbawaspens);
	
}

function toSdmChartNaikGol(){	
	$thnpen=date('Y')+1;
	$tgla="$thnpen-01-01";	
	$jmlnaikgol = $this->statistik_serv->getCountDataForEis("  and (EXTRACT(months from AGE(now(), d_tmt_golongan))= 6)");
	$jmlResultnaikgol= count($jmlnaikgol);
	if ($jmlResultnaikgol !=0){
		for ($j = 0; $j < $jmlResultnaikgol; $j++) 
		{ 
			$esl=trim($jmlnaikgol[$j]['c_eselon_i']);
			if ($esl=='01'){$jmlbuanaikgol=trim($jmlnaikgol[$j]['jumlah']); $this->view->jmlbuanaikgol=$jmlbuanaikgol;}
			if ($esl=='03'){$jmlbadilumnaikgol=trim($jmlnaikgol[$j]['jumlah']); $this->view->jmlbadilumnaikgol=$jmlbadilumnaikgol;}
			if ($esl=='04'){$jmlbadilagnaikgol=trim($jmlnaikgol[$j]['jumlah']); $this->view->jmlbadilagnaikgol=$jmlbadilagnaikgol;}
			if ($esl=='05'){$jmlbadimilnaikgol=trim($jmlnaikgol[$j]['jumlah']); $this->view->jmlbadimilnaikgol=$jmlbadimilnaikgol;}
			if ($esl=='06'){$jmlbpppphpnaikgol=trim($jmlnaikgol[$j]['jumlah']); $this->view->jmlbpppphpnaikgol=$jmlbpppphpnaikgol;}
			if ($esl=='07'){$jmlbawasnaikgol=trim($jmlnaikgol[$j]['jumlah']); $this->view->jmlbawasnaikgol=$jmlbawasnaikgol;}
		}
	}

	$this->view->chartnaikgol=$this->sdm_serv->toChartNaikGol($jmlbadilagnaikgol,$jmlbadilumnaikgol,$jmlbuanaikgol,$jmlbadimilnaikgol,$jmlbpppphpnaikgol,$jmlbawasnaikgol);
	
}

function toSdmChartKgb(){
	$thnpen=date('Y')+1;
	$tgla="$thnpen-01-01";	
	$jmlkgb = $this->statistik_serv->getCountDataForEis("  and (EXTRACT(years from AGE(now(), d_tmt_kgb))= 6)");
	$jmlResultkgb= count($jmlkgb);
	if ($jmlResultkgb !=0){
		for ($j = 0; $j < $jmlResultkgb; $j++) 
		{ 
			$esl=trim($jmlkgb[$j]['c_eselon_i']);
			if ($esl=='01'){$jmlbuakgb=trim($jmlkgb[$j]['jumlah']); $this->view->jmlbuakgb=$jmlbuakgb;}
			if ($esl=='03'){$jmlbadilumkgb=trim($jmlkgb[$j]['jumlah']); $this->view->jmlbadilumkgb=$jmlbadilumkgb;}
			if ($esl=='04'){$jmlbadilagkgb=trim($jmlkgb[$j]['jumlah']); $this->view->jmlbadilagkgb=$jmlbadilagkgb;}
			if ($esl=='05'){$jmlbadimilkgb=trim($jmlkgb[$j]['jumlah']); $this->view->jmlbadimilkgb=$jmlbadimilkgb;}
			if ($esl=='06'){$jmlbpppphpkgb=trim($jmlkgb[$j]['jumlah']); $this->view->jmlbpppphpkgb=$jmlbpppphpkgb;}
			if ($esl=='07'){$jmlbawaskgb=trim($jmlkgb[$j]['jumlah']); $this->view->jmlbawaskgb=$jmlbawaskgb;}
		}
	}

	$this->view->chartkgb=$this->sdm_serv->toChartKgb($jmlbadilagkgb,$jmlbadilumkgb,$jmlbuakgb,$jmlbadimilkgb,$jmlbpppphpkgb,$jmlbawaskgb);	 
	
}

//=======================================================================================S D M==========================================================================

}







