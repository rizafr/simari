<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/Sdm_Statistik_Service.php";
require_once "service/sdm/Sdm_Monitoring_Service.php";
class Sdmmodule_ReminderController extends Zend_Controller_Action {

		
    public function init() {
	$this->_helper->layout->setLayout('target-column');
	$registry = Zend_Registry::getInstance();
	$this->auth = Zend_Auth::getInstance();
	$this->view->basePath = $registry->get('basepath');
	$this->view->baseData = $registry->get('baseData');
	$this->statistik_serv = Sdm_Statistik_Service::getInstance();
	$this->monitoring_serv = Sdm_Monitoring_Service::getInstance();
    }
	
	public function indexAction() {
	}
	
	public function pensiunAction() {
		$par=$_GET['par'];
		$this->view->par=$par;
		$currentPage=$_GET['currentPage'];
		if ($par=='pensiun')
		{
			$this->view->judul="Daftar Pegawai yang akan pensiun 1 tahun ke depan";
			$thnpen=date('Y')+1;
			$tgla="$thnpen-01-01";	
			$caripens= " and c_eselon !='17' and (EXTRACT(years from AGE('$tgla', d_peg_lahir))= q_usia_pensiun)";
			if((!$currentPage) || ($currentPage == 'undefined'))
				{$currentPage = 1;}
				$numToDisplay = 100;
				$this->view->numToDisplay = $numToDisplay;
				$this->view->currentPage = $currentPage;
			$this->view->totaldata=$this->monitoring_serv->getPegawaiListByNipB($caripens,0,0);					
			$this->view->remindPensiun=$this->monitoring_serv->getPegawaiListByNipB($caripens,$currentPage, $numToDisplay);	
		}
		else if ($par=='golongan')
		{
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
			$this->view->judul="Daftar Pegawai yang akan naik Golongan Pangkat 6 Bulan ke depan";
			$carigol= $carix.$sqldate;
			//$thnpen=date('Y')+1;
			//$tgla="$thnpen-01-01";	
			//$carigol= " and (EXTRACT(months from AGE(now(), d_tmt_golongan))= 6)";
			
			$currentPage=$_GET['currentPage'];
			if((!$currentPage) || ($currentPage == 'undefined'))
				{$currentPage = 1;}
				$numToDisplay = 100;
				$this->view->numToDisplay = $numToDisplay;
				$this->view->currentPage = $currentPage;
			$this->view->totaldata=$this->monitoring_serv->getPegawaiListByNipB($carigol,0,0);					
			$this->view->remindPensiun=$this->monitoring_serv->getPegawaiListByNipB($carigol,$currentPage, $numToDisplay);			
		}
		else if ($par=='kgb')
		{
			$this->view->judul="Daftar Pegawai yang akan naik KGB 6 Bulan ke depan";
			$thnpen=date('Y')+1;
			$tgla="$thnpen-01-01";	
			$cariKgb= " and (EXTRACT(years from AGE(now(), d_tmt_kgb))= 6)";	
			$currentPage=$_GET['currentPage'];
			if((!$currentPage) || ($currentPage == 'undefined'))
				{$currentPage = 1;}
				$numToDisplay = 100;
				$this->view->numToDisplay = $numToDisplay;
				$this->view->currentPage = $currentPage;
				$this->view->totaldata=$this->monitoring_serv->getPegawaiListByNipB($cariKgb,0,0);					
			$this->view->remindPensiun=$this->monitoring_serv->getPegawaiListByNipB($cariKgb,$currentPage, $numToDisplay);			
				
		}		
	}
	
	
	public function reminderAction() {
		$par	= $_GET['par'];
		$currentPage=$_GET['currentPage'];
			$this->view->par=$par;
		if ($par=='pensiun')
		{
			$this->view->judul="Daftar Pegawai yang akan pensiun 1 tahun ke depan";
			$thnpen=date('Y')+1;
			$tgla="$thnpen-01-01";	
			$caripens= " and c_eselon !='17' and (EXTRACT(years from AGE('$tgla', d_peg_lahir))= q_usia_pensiun)";
			if((!$currentPage) || ($currentPage == 'undefined'))
				{$currentPage = 1;}
				$numToDisplay = 100;
				$this->view->numToDisplay = $numToDisplay;
				$this->view->currentPage = $currentPage;
			$this->view->totaldata=$this->monitoring_serv->getPegawaiListByNipB($caripens,0,0);					
			$this->view->remindPensiun=$this->monitoring_serv->getPegawaiListByNipB($caripens,$currentPage, $numToDisplay);	
		}
		else if ($par=='golongan')
		{
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
	
		$this->view->judul="Daftar Pegawai yang akan naik Golongan/Pangkat 6 Bulan ke depan";
			//$thnpen=date('Y')+1;
			//$tgla="$thnpen-01-01";	
			//$carigol= " and (EXTRACT(months from AGE(now(), d_tmt_golongan))= 6)";
			$currentPage=$_GET['currentPage'];
			if((!$currentPage) || ($currentPage == 'undefined'))
				{$currentPage = 1;}
				$numToDisplay = 100;
				$this->view->numToDisplay = $numToDisplay;
				$this->view->currentPage = $currentPage;
			$this->view->totaldata=$this->monitoring_serv->getPegawaiListByNipB($carigol,0,0);					
			$this->view->remindPensiun=$this->monitoring_serv->getPegawaiListByNipB($carigol,$currentPage, $numToDisplay);			
		}
		else if ($par=='kgb')
		{
			$this->view->judul="Daftar Pegawai yang akan KGB 6 Bulan ke depan";
			$thnpen=date('Y')+1;
			$tgla="$thnpen-01-01";	
			$cariKgb= " and (EXTRACT(years from AGE(now(), d_tmt_kgb))= 6)";	
			$currentPage=$_GET['currentPage'];
			if((!$currentPage) || ($currentPage == 'undefined'))
				{$currentPage = 1;}
				$numToDisplay = 100;
				$this->view->numToDisplay = $numToDisplay;
				$this->view->currentPage = $currentPage;
				$this->view->totaldata=$this->monitoring_serv->getPegawaiListByNipB($cariKgb,0,0);					
			$this->view->remindPensiun=$this->monitoring_serv->getPegawaiListByNipB($cariKgb,$currentPage, $numToDisplay);			
		
		}		
	}	
}
?>