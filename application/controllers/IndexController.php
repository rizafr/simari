<?php
require_once 'Zend/Controller/Action.php';
require_once "service/cms/Cms_Berita_Service.php";
require_once "service/cms/Cms_pengumuman_Service.php";
require_once "service/cms/Cms_agenda_Service.php";
require_once "service/cms/Cms_kategoriprodhukum.php";
require_once "service/cms/Cms_produkhukum.php";
require_once "service/cms/Cms_tentangkami_Service.php";
require_once "service/cms/Cms_kontakkami_Service.php";

require_once "service/sdm/Sdm_Dashboard_Service.php";
require_once "service/sdm/Sdm_Statistik_Service.php";
require_once "service/sdm/Sdm_Monitoring_Service.php";

require_once "service/portal/Portal_Shoutbox_Service.php";
require_once "service/portal/Portal_Useronline_Service.php";

require_once "service/adm/Adm_Adminuser_Service.php";
require_once "service/adm/Adm_Admaplikasi_Service.php";

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        //$this->_helper->layout->setLayout('two-column');
		$registry = Zend_Registry::getInstance();
		$this->auth = Zend_Auth::getInstance();
		$this->view->basePath = $registry->get('basepath');
		$this->view->baseData = $registry->get('baseData');

		 $this->dasboard_serv = Sdm_Dashboard_Service::getInstance();
		 $this->statistik_serv = Sdm_Statistik_Service::getInstance();
		 $this->monitoring_serv = Sdm_Monitoring_Service::getInstance();
		// $this->perkarakasasi_serv = Siap_Perkarakasasi_Service::getInstance();
		
		$this->berita_serv = Cms_Berita_Service::getInstance();
		$this->view->idberita= $this->idberita;
		$this->view->jdlberita= $this->jdlberita;
		$this->view->detilberita= $this->detilberita;

		$this->pengumuman_serv = Cms_pengumuman_Service::getInstance();
		$this->view->idpengumuman= $this->idpengumuman;
		$this->view->jdlpengumuman= $this->jdlpengumuman;
		$this->view->detilpengumuman= $this->detilpengumuman;

		$this->agenda_serv = Cms_agenda_Service::getInstance();
		$this->tentang_serv = Cms_tentangkami_Service::getInstance();
		$this->kontak_serv = Cms_kontakkami_Service::getInstance();
		
		$this->view->idagenda= $this->idagenda;
		$this->view->jdlagenda= $this->jdlagenda;
		$this->view->detilagenda= $this->detilagenda;
		$this->view->tempat= $this->tempat;

		$this->shoutbox_serv = Portal_shoutbox_Service::getInstance();
		$this->view->id= $this->id;
		$this->view->n_userid= $this->n_userid;
		$this->view->n_name= $this->n_name;
		$this->view->n_message= $this->n_message;

		$this->kategoriprodukhukum_serv = Cms_kategoriprodhukum_Service::getInstance();
		// $this->view->idkategoriprodukhukum= $this->idkategoriprodukhukum;
		// $this->view->jdlkategoriprodukhukum= $this->jdlkategoriprodukhukum;

		$this->produkhukum_serv = Cms_produkhukum_Service::getInstance();
		$this->view->userid= $this->userid;
		$this->view->i_ym= $this->i_ym;

		$this->adminuser_serv = Adm_Adminuser_Service::getInstance();
		$this->admaplikasi_serv = Adm_Admaplikasi_Service::getInstance();
		$this->useronline_serv = Portal_Useronline_Service::getInstance();
	
    }

    public function indexAction()
    {
        // action body
		Zend_Auth::getInstance()->clearIdentity();
		//$this->_helper->layout->setLayout('index-layout');
		$this->url = $_SERVER['SERVER_NAME'];
		if($this->url == 'cimahiutara.cimahikota.go.id'){
			$this->_helper->layout->setLayout('cimut-layout');
		} else {
			if($this->url == 'CIMAHISELATAN.CIMAHIKOTA.GO.ID'){
				$this->_helper->layout->setLayout('cimsel-layout');
			} else {
				$this->_helper->layout->setLayout('cimteng-layout');
			}
		}
		$id=session_id();
			$DataUseronline = array(
								"id"=>$id,
								"userid"=>$userid,
								"ip"=>$ip,
								"tm"=>date("Y-m-d H:i:s"),
								"status"=>'OFF');
						
			$hasiladdol = $this->useronline_serv->maintainDatainsertOl($DataUseronline);
    
			// action body
			/*insert useronline*/
			$ip=$_SERVER[REMOTE_ADDR];
			//echo $ip;
			$id=session_id();
			$DataUseronline = array(
								"id"=>$id,
								"userid"=>$userid,
								"ip"=>$ip,
								"tm"=>date("Y-m-d H:i:s"),
								"status"=>'OFF');
						
			$hasiladdol = $this->useronline_serv->maintainDatainsertOl($DataUseronline);

			////// To update session status for tmuseronline table to get who is online ////////
			//if(isset(session_id())){
				$tm=date("Y-m-d H:i:s");
				$DataUseronlineUpdate = array(
					"id"=>session_id(),
					"tm"=>$tm,
					"status"=>'ON');
				$hasilupdateol = $this->useronline_serv->maintainDataupdateOl($DataUseronlineUpdate);
				//$sqlquery="update plus_login set status='ON',tm='$tm' where id='$session[id]'";
				//echo $sqlquery;
				//$q=mysql_query($sqlquery);
			//}
			// Find out who is online /////////
			$gap=1; // change this to change the time in minutes, This is the time for which active users are collected. 
			$tm=date ("Y-m-d H:i:s", mktime (date("H"),date("i")-$gap,date("s"),date("m"),date("d"),date("Y")));
			$this->view->jmluserol = $this->useronline_serv->getUseronlineSum($tm);
			
			$this->view->useronlinelist = $this->useronline_serv->getUseronlineList($tm);

			/*Notifikasi*/
			//Notifikasi SDM
			$carix="
and ((c_eselon in('01','02','03') and c_golongan in('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17') 
or (c_eselon = '04' and c_golongan in('04','05','06','07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_eselon = '03' and c_golongan in('02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_eselon = '05' and c_golongan in('04','05','06','07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_eselon = '06' and c_golongan in('05','06','07','08','09','10','11','12','13','14','15','16','17') ) 
or ((c_eselon = '07' or c_eselon = '08') and c_golongan in('05','06','07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_eselon = '15' and ( (c_pend = '29' and c_golongan in('12','13','14','15','16','17') ) 
or (c_pend = '41' and c_golongan in('11','12','13','14','15','16','17') ) 
or (c_pend = '40' and c_golongan in('08','09','10','11','12','13','14','15','16','17') ) 
or ((c_pend = '04' or c_pend = '05') and c_golongan in('08','09','10','11','12','13','14','15','16','17') ) 
or (c_pend = '32' and c_golongan in('07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_pend = '07' and c_golongan in('06','07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_pend = '36' and c_golongan in('06','07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_pend = '09' and c_golongan in('05','06','07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_pend = '10' and c_golongan in('04','05','06','07','08','09','10','11','12','13','14','15','16','17') ) ) ) ))";
	//to_char(d_tmt_golongan,'yyyy-mm-dd') like '2008-10%' 
	$datestart =  date('Y-m').'-01';
	if(date('m') + 6 > 12){
		$mdate = (date('m') + 6) % 12;
		$mday 	= '0'.$mdate;
		$yday	= date('Y')+1;
	} else{
		$mdate = date('m') + 6;
		$mday 	= strlen($mdate == 1) ? '0'.$mdate : $mdate;
		$yday	= date('Y');
	}
	$dateend = $yday.'-'.$mday.'-01';
	$dateendold 	= ($yday-4).'-'.$mday.'-01';
	$datestartold 	= (date('Y')-4).'-'.date('m').'-01';
	$sqldate = " and d_tmt_golongan between '$datestartold' and '$dateendold'";
	$carigol= $carix.$sqldate;
			$thnpen=date('Y')+1;
			$tgla="$thnpen-01-01";
			$caripens= " and c_eselon !='17' and (EXTRACT(years from AGE('$tgla', d_peg_lahir))= q_usia_pensiun)";
			$this->view->remindPensiun=$this->monitoring_serv->getJmlDataPeg($caripens);
			//$carigol= " and (EXTRACT(months from AGE(now(), d_tmt_golongan))= 6)";
			$this->view->remindGolPangkat=$this->monitoring_serv->getJmlDataPeg($carigol);	
			$cariKgb= " and (EXTRACT(years from AGE(now(), d_tmt_kgb))= 6)";	
			$this->view->remindKgb=$this->monitoring_serv->getJmlDataPeg($cariKgb);				
			
			/*CHART DEFAULT*/
		
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
		$this->view->chartjmlpeg=$this->toChartAge('Komposisi Usia',$jml25,$jml2635,$jml3645,$jml4655,$jml56);
		
	


			
			$cari = "";
			$this->view->beritaPubList = $this->berita_serv->getBeritaPubList($cari);	
			
			$cari2 = "";
			$this->view->pengumumanPubList = $this->pengumuman_serv->getpengumumanPubList($cari2);	
			$userii=$this->view->userid;
			$tglsek=date('Ymd');
			// $cari3 = " and (i_nip='$this->i_peg_nip' or b.i_entri='$userii')  and to_char(d_agenda,'yyyymmdd') >= '$tglsek' ";
			$cari3 = "";
			$this->view->agendaPubList = $this->agenda_serv->getagendaPubList($cari3);	

			$cari4 = "";
			$this->view->kategoriprodukhukumPubList = $this->kategoriprodukhukum_serv->getkategoriprodukhukumPubList($cari4);	

			$cari5 = "";
			$this->view->produkhukumPubList = $this->produkhukum_serv->getprodukhukumTenPubList($cari5);	
			$cari6 = "";
			$this->view->kontakkamiList = $this->kontak_serv->getkontakkamiPubList($cari6);	
			
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
			if ($jml56){$strXML .= "<set label=' 56 thn' value='$jml56'   Color='990000'/>";}
			
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


}







