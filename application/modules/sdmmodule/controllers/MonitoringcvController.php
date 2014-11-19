<?php
require_once 'Zend/Controller/Action.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Monitoring_Service.php";

require_once "service/sdm/Sdm_Pendidikan_Service.php";
require_once "service/sdm/Sdm_DiklatPenjenjangan_Service.php";
require_once "service/sdm/Sdm_DiklatFungsional_Service.php";
require_once "service/sdm/Sdm_DiklatTeknis_Service.php";
require_once "service/sdm/Sdm_DiklatLain_Service.php";
require_once "service/sdm/Sdm_Seminar_Service.php";
require_once "service/sdm/Sdm_Pangkat_Service.php";
require_once "service/sdm/Sdm_Jabatan_Service.php";
require_once "service/sdm/Sdm_Penghargaan_Service.php";
require_once "service/sdm/Sdm_Hukuman_Service.php";
require_once "service/sdm/Sdm_Organisasi_Service.php";
require_once "service/sdm/Sdm_Luarnegeri_Service.php";
require_once "service/sdm/Sdm_Kesehatan_Service.php";

class Sdmmodule_MonitoringcvController extends Zend_Controller_Action
{
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
	 	$registry = Zend_Registry::getInstance();
	   	$this->view->basePath = $registry->get('basepath');
		$this->view->leftMenu = $registry->get('leftMenu'); 
		$this->reff_serv = sdm_refferensi_Service::getInstance();
		$this->pegawai_serv = Sdm_Monitoring_Service::getInstance();

		$this->pendidikan_serv = Sdm_Pendidikan_Service::getInstance();
		$this->dikjenjang_serv = Sdm_DiklatPenjenjangan_Service::getInstance();
		$this->dikFung_serv = Sdm_DiklatFungsional_Service::getInstance();
		$this->dikTeknis_serv = Sdm_DiklatTeknis_Service::getInstance();
		$this->dikLain_serv = Sdm_DiklatLain_Service::getInstance();
		$this->seminar_serv = Sdm_Seminar_Service::getInstance();
		$this->pangkat_serv = Sdm_Pangkat_Service::getInstance();
		$this->jabatan_serv = Sdm_Jabatan_Service::getInstance();
		$this->penghargaan_serv = Sdm_Penghargaan_Service::getInstance();
		$this->hukuman_serv = Sdm_Hukuman_Service::getInstance();
		$this->organisasi_serv = Sdm_organisasi_service::getInstance();
		$this->luarnegeri_serv = Sdm_Luarnegeri_Service::getInstance();
		$this->kesehatan_serv = Sdm_Kesehatan_Service::getInstance();	

		$ssologin = new Zend_Session_Namespace('ssologin');
		$this->view->c_lokasi_unitkerja=$ssologin->c_lokasi_unitkerja;
		$this->view->c_eselon_i=$ssologin->c_eselon_i;			
    }
	public function indexAction()
	{
	}
	public function monitoringcvjsAction() 
	{
		header('content-type : text/javascript');
		$this->render('monitoringcvjs');
	}
	public function monitoringcvAction() 
	{
	$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai($cari);
	$this->view->statusPegRef = $this->reff_serv->getStatusPegawai($cari);
	$ncol=$_GET['ncol'];
	$statpeg=$_GET['statpeg'];
	$ncari=strtoupper($_GET['ncari']);
	
	
	$this->view->ncari=$_GET['ncari'];
	$this->view->ncol=$_GET['ncol'];
	$this->view->statpeg=$_GET['statpeg'];
	
	$c_lokasi_unitkerja=$this->view->c_lokasi_unitkerja;
	$c_eselon_i=trim($this->view->c_eselon_i);
	
	if ($c_eselon_i!='01'){
		$cari= " and (c_lokasi_unitkerja='$c_lokasi_unitkerja' or c_lokasi_unitkerja_cpns ='$c_lokasi_unitkerja') and (c_eselon_i='$c_eselon_i' or c_eselon_i_cpns='$c_eselon_i') ";
	}
	
	
	if ($ncari){$cari= $cari." and upper($ncol) like '%$ncari%' ";}
	if ($statpeg){$cari= $cari." and c_status_pegawai like '%$statpeg%' ";}
	$orderBy=$_GET['orderBy'];
	$order=$_GET['order'];
	if (!$_GET['order']){$this->view->orderbya="asc";$this->view->orderbyb="desc";}
	else{
		if ($_GET['order']=='desc'){	$this->view->orderbya="desc";$this->view->orderbyb="asc";}
		else{$this->view->orderbya="asc";$this->view->orderbyb="desc";}
	}
	if ($_GET['orderBy']){$orderBy=" order by $orderBy $order";}
	else{$orderBy=" order by d_entry asc";}
	$this->view->orderBy=$_GET['orderBy'];
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$cari= $cari." and (c_eselon !='17' or c_eselon isnull)"; 
		$this->view->totalpegawaiList = $this->pegawai_serv->getPegawaiList($cari, 0, 0 ,$orderBy);		
		$this->view->pegawaiList = $this->pegawai_serv->getPegawaiList($cari, $currentPage, $numToDisplay,$orderBy );	
	}	

    public function cetakcvAction() {		
		$i_peg_nip=$_GET['i_peg_nip'];
		$cari = " and i_peg_nip ='$i_peg_nip' ";
		$datapegawai=$this->pegawai_serv->getPegawaiListAll($cari);
		$this->view->i_peg_nip=$datapegawai[0]['i_peg_nip'];
		$this->view->i_peg_nip_new=$datapegawai[0]['i_peg_nip_new'];	
		$this->view->n_peg=$datapegawai[0]['n_peg'];
		$this->view->n_peg_gelardepan=$datapegawai[0]['n_peg_gelardepan'];
		$this->view->n_eselon=$datapegawai[0]['n_eselon'];
		$this->view->d_tmt_eselon=$datapegawai[0]['d_tmt_eselon'];
		$this->view->n_unit_kerja=$datapegawai[0]['n_unit_kerja'];
		$this->view->n_satker=$datapegawai[0]['n_satker'];
		$this->view->c_eselon=$datapegawai[0]['c_eselon'];
		$this->view->d_tmt_eselon=$datapegawai[0]['d_tmt_eselon'];
		$this->view->c_lokasi_unitkerja=$datapegawai[0]['c_lokasi_unitkerja'];
		$this->view->c_eselon_i=$datapegawai[0]['c_eselon_i'];
		$this->view->c_eselon_ii=$datapegawai[0]['c_eselon_ii'];
		$this->view->c_eselon_iii=$datapegawai[0]['c_eselon_iii'];
		$this->view->c_eselon_iv=$datapegawai[0]['c_eselon_iv'];
		$this->view->c_eselon_v=$datapegawai[0]['c_eselon_v'];
		$this->view->d_tmt_cpns=$datapegawai[0]['d_tmt_cpns'];
		$this->view->c_peg_status=$datapegawai[0]['c_peg_status'];
		$this->view->c_golongan=$datapegawai[0]['c_golongan'];
		$this->view->d_peg_lahir=$datapegawai[0]['d_peg_lahir'];
		$this->view->c_peg_propinsi_lahir=$datapegawai[0]['c_peg_propinsi_lahir'];
		$this->view->a_peg_kota_lahir=$datapegawai[0]['a_peg_kota_lahir'];
		$this->view->c_peg_jeniskelamin=$datapegawai[0]['c_peg_jeniskelamin'];
		$this->view->c_peg_statusnikah=$datapegawai[0]['c_peg_statusnikah'];
		$this->view->q_peg_tinggibdn=$datapegawai[0]['q_peg_tinggibdn'];
		$this->view->q_peg_beratbdn=$datapegawai[0]['q_peg_beratbdn'];
		$this->view->n_peg_rambut=$datapegawai[0]['n_peg_rambut'];
		$this->view->n_peg_btkmuka=$datapegawai[0]['n_peg_btkmuka'];
		$this->view->n_peg_warnakulit=$datapegawai[0]['n_peg_warnakulit'];
		$this->view->n_peg_cirikhas=$datapegawai[0]['n_peg_cirikhas'];
		$this->view->a_peg_rumah=$datapegawai[0]['a_peg_rumah'];
		$this->view->a_peg_rt=$datapegawai[0]['a_peg_rt'];
		$this->view->a_peg_rw=$datapegawai[0]['a_peg_rw'];
		$this->view->a_peg_kelurahan=$datapegawai[0]['a_peg_kelurahan'];
		$this->view->a_peg_kecamatan=$datapegawai[0]['a_peg_kecamatan'];
		$this->view->a_peg_kota=$datapegawai[0]['a_peg_kota'];
		$this->view->a_peg_propinsi=$datapegawai[0]['a_peg_propinsi'];
		$this->view->a_peg_kodepos=$datapegawai[0]['a_peg_kodepos'];
		$this->view->i_peg_telponrumah=$datapegawai[0]['i_peg_telponrumah'];
		$this->view->i_peg_telponhp=$datapegawai[0]['i_peg_telponhp'];
		$this->view->c_golongan_darah=$datapegawai[0]['c_golongan_darah'];
		$this->view->n_peg_hobi=$datapegawai[0]['n_peg_hobi'];
		$this->view->pangkat=$datapegawai[0]['c_golongan']." - ".$datapegawai[0]['n_pangkat'];
		$this->view->n_peg_kota_lahir=$datapegawai[0]['n_peg_kota_lahir'];		
		
		
		$cari = " and i_peg_nip ='$i_peg_nip' ";
		$this->view->pendList = $this->pendidikan_serv->getPendidikanList($cari);	
		$this->view->dikJenjangList = $this->dikjenjang_serv->getPelatihanList($cari);
		$this->view->dikFungList = $this->dikFung_serv->getPelatihanList($cari);		
		$this->view->dikTeknisList = $this->dikTeknis_serv->getPelatihanList($cari);
		$this->view->dikLainList = $this->dikLain_serv->getPelatihanList($cari);	
		$this->view->seminarList = $this->seminar_serv->getSeminarList($cari);	
		$this->view->pangkatList = $this->pangkat_serv->getPangkatList($cari);	
		$this->view->jabatanList = $this->jabatan_serv->getJabatanList($cari);	
		$this->view->penghargaanList = $this->penghargaan_serv->getPenghargaanList($cari);	
		$this->view->hukumanList = $this->hukuman_serv->getHukumanList($cari);
		$this->view->organisasiList = $this->organisasi_serv->getOrganisasiList($cari);	
		$this->view->luarnegeriList = $this->luarnegeri_serv->getLuarnegeriList($cari);	
		$this->view->kesehatanList = $this->kesehatan_serv->getKesehatanList($cari);

    }
	
	
  function age($age){
    list($year,$month,$day) = explode("-",$age);
    $year_diff  = date("Y") - $year;
    $month_diff = date("m") - $month;
    $day_diff   = date("d") - $day;
    if ($day_diff < 0 || $month_diff < 0) {
      $year_diff--;
    }
    return $year_diff;
  }	
}

?>