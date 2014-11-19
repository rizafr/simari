<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/Sdm_Cv_Service.php";
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
require_once "service/sdm/Sdm_Anak_Service.php";
class Sdmmodule_DatacvController extends Zend_Controller_Action {

		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu');
		$this->view->photoPath = $registry->get('photoPath');
		 
		$this->cv_serv = Sdm_Cv_Service::getInstance();
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
		$this->anak_serv = Sdm_Anak_Service::getInstance();
		
		$this->view->nama= $this->nama;
		$this->view->nip= $this->nip;
		$this->view->golongan= $this->golongan;
		$this->view->pangkat=$this->pangkat;
		$this->view->filephoto=$this->filephoto;
		$this->view->statuspeg=$this->statuspeg;
		$this->view->cpegstatusnikah=$this->cpegstatusnikah;
		$sespeg = new Zend_Session_Namespace('sespeg');
		$this->view->nama= $sespeg->nama;
		$this->view->nip= $sespeg->nip;
		$this->view->golongan= $sespeg->golongan;
		$this->view->pangkat= $sespeg->pangkat;	
		$this->view->filephoto= $sespeg->filephoto;	
		$this->view->statuspeg= $sespeg->statuspeg;
		$this->view->cpegstatusnikah= $sespeg->cpegstatusnikah;	

		$sesmenu = new Zend_Session_Namespace('sesmenu');
		$this->view->menu= $sesmenu->menu;
    }
		
 	
    public function indexAction() {
	
    }
public function datacvjsAction() 
{
	header('content-type : text/javascript');
	$this->render('datacvjs');
}		
    public function datacvAction() {		
		$nip=$this->view->nip;
		if (!$nip){$nip=$_GET['nip'];}
		//$cari = " and i_peg_nip ='$nip' ";
		$cari = " and (i_peg_nip ='$nip' or i_peg_nip_new ='$nip') ";
		
		$datapegawai=$this->cv_serv->getPegawaiListByPnsNip($cari );
		
		$this->view->i_peg_nip_new=$datapegawai[0]['i_peg_nip_new'];
		if ($datapegawai[0]['i_peg_nip_new']==$datapegawai[0]['i_peg_nip']){$this->view->i_peg_nip="-";}
		else{$this->view->i_peg_nip=$datapegawai[0]['i_peg_nip'];}
		$this->view->n_peg=$datapegawai[0]['n_peg'];
		$this->view->i_peg_nrp=$datapegawai[0]['i_peg_nrp'];
		
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
		$this->view->c_status_kepegawaian=$datapegawai[0]['c_status_kepegawaian'];
		$this->view->n_peg_status=$datapegawai[0]['n_peg_status'];
		$this->view->c_golongan=$datapegawai[0]['c_golongan'];
		$this->view->n_golongan=$datapegawai[0]['n_golongan'];
		$this->view->d_peg_lahir=$datapegawai[0]['d_peg_lahir'];
		$this->view->c_peg_propinsi_lahir=$datapegawai[0]['c_peg_propinsi_lahir'];
		$this->view->n_propinsi_lahir=$datapegawai[0]['n_propinsi_lahir'];		
		$this->view->a_peg_kota_lahir=$datapegawai[0]['a_peg_kota_lahir'];
		$this->view->n_peg_kota_lahir=$datapegawai[0]['n_peg_kota_lahir'];		
		$this->view->c_peg_jeniskelamin=$datapegawai[0]['c_peg_jeniskelamin'];
		$this->view->c_peg_statusnikah=$datapegawai[0]['c_peg_statusnikah'];
		$this->view->q_peg_tinggibdn= $datapegawai[0]['q_peg_tinggibdn'] > 0 ? $datapegawai[0]['q_peg_tinggibdn']: '';
		$this->view->q_peg_beratbdn=$datapegawai[0]['q_peg_beratbdn'] > 0 ? $datapegawai[0]['q_peg_beratbdn'] : '';
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
		$this->view->pangkat=$datapegawai[0]['n_golongan']." - ".$datapegawai[0]['n_pangkat'];
		$this->view->i_peg_karpeg=$datapegawai[0]['i_peg_karpeg'];
		
		
		$cari = " and i_peg_nip ='$nip' ";
		//echo "xxxxxxxxx ".$cari;
		$this->view->CountAnak = $this->anak_serv->getAnakCount($cari) > 0 ? $this->anak_serv->getAnakCount($cari): '';	
		$this->view->pendList = $this->pendidikan_serv->getPendidikanList($cari);	
		$this->view->dikJenjangList = $this->dikjenjang_serv->getPelatihanList($cari);
		$this->view->dikFungList = $this->dikFung_serv->getPelatihanList($cari);		
		$this->view->dikTeknisList = $this->dikTeknis_serv->getPelatihanList($cari);
		$this->view->dikLainList = $this->dikLain_serv->getPelatihanList($cari);	
		$this->view->seminarList = $this->seminar_serv->getSeminarList($cari);	
		//$this->view->pangkatList = $this->pangkat_serv->getPangkatList($cari);	


		$carip = " and i_peg_nip ='$nip' ";
	
		$cstatusKepegawaian = $this->pangkat_serv->getStatusKepeg($carip);
		$cari2 = " and c_peg_tipegolongan = '$cstatusKepegawaian' ";
		$maintainAllowed =  $this->pangkat_serv->maintainAllowed($cari2);
		$this->view->maintainAllowed = $maintainAllowed;
		$cari3 = " and i_peg_nip ='$nip' and c_peg_tipegolongan = '$cstatusKepegawaian' ";
	
		$this->view->pangkatList = $this->pangkat_serv->getPangkatList($cari3);	


		$this->view->jabatanList = $this->jabatan_serv->getJabatanList($cari);	
		$this->view->penghargaanList = $this->penghargaan_serv->getPenghargaanList($cari);	
		$this->view->hukumanList = $this->hukuman_serv->getHukumanList($cari);
		$this->view->organisasiList = $this->organisasi_serv->getOrganisasiList($cari);	
		$this->view->luarnegeriList = $this->luarnegeri_serv->getLuarnegeriList($cari);	
		$this->view->kesehatanList = $this->kesehatan_serv->getKesehatanList($cari);
		
		if ($_GET['par']=='cetak'){$this->render('cetakcv');}
    }
	public function datacvtpmAction() {		
		if ($_GET['nip'])
		{
			$nip=$_GET['nip'];
		}
		else{
			$nip=$this->view->nip;
		}
		$cari = " and (i_peg_nip ='$nip' or i_peg_nip_new ='$nip') ";
		$datapegawai=$this->cv_serv->getPegawaiListByPnsNip($cari );
		
		$this->view->i_peg_nip_new=$datapegawai[0]['i_peg_nip_new'];
		if ($datapegawai[0]['i_peg_nip_new']==$datapegawai[0]['i_peg_nip']){$this->view->i_peg_nip="-";}
		else{$this->view->i_peg_nip=$datapegawai[0]['i_peg_nip'];}
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
		$this->view->n_peg_status=$datapegawai[0]['n_peg_status'];
		$this->view->c_golongan=$datapegawai[0]['c_golongan'];
		$this->view->n_golongan=$datapegawai[0]['n_golongan'];
		$this->view->d_peg_lahir=$datapegawai[0]['d_peg_lahir'];
		$this->view->c_peg_propinsi_lahir=$datapegawai[0]['c_peg_propinsi_lahir'];
		$this->view->n_propinsi_lahir=$datapegawai[0]['n_propinsi_lahir'];		
		$this->view->a_peg_kota_lahir=$datapegawai[0]['a_peg_kota_lahir'];
		$this->view->n_peg_kota_lahir=$datapegawai[0]['n_peg_kota_lahir'];		
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
		$this->view->pangkat=$datapegawai[0]['n_golongan']." - ".$datapegawai[0]['n_pangkat'];
		$this->view->i_peg_karpeg=$datapegawai[0]['i_peg_karpeg'];
		$this->view->filephoto= $datapegawai[0]['e_file_photo'];
		
		$inip = $this->cv_serv->getNip($nip);
		$cari = " and i_peg_nip ='$inip' ";
		//echo "xxxxxxxxx ".$cari;
		$this->view->pendList = $this->pendidikan_serv->getPendidikanList($cari);	
		$this->view->dikJenjangList = $this->dikjenjang_serv->getPelatihanList($cari);
		$this->view->dikFungList = $this->dikFung_serv->getPelatihanList($cari);		
		$this->view->dikTeknisList = $this->dikTeknis_serv->getPelatihanList($cari);
		$this->view->dikLainList = $this->dikLain_serv->getPelatihanList($cari);	
		$this->view->seminarList = $this->seminar_serv->getSeminarList($cari);	
		
		
		$carip = " and i_peg_nip ='$inip' ";
	
		$cstatusKepegawaian = $this->pangkat_serv->getStatusKepeg($carip);
		$cari2 = " and c_peg_tipegolongan = '$cstatusKepegawaian' ";
		$maintainAllowed =  $this->pangkat_serv->maintainAllowed($cari2);
		$this->view->maintainAllowed = $maintainAllowed;
		$cari3 = " and i_peg_nip ='$inip' and c_peg_tipegolongan = '$cstatusKepegawaian' ";
	
		$this->view->pangkatList = $this->pangkat_serv->getPangkatList($cari3);	
	

		
		//$this->view->pangkatList = $this->pangkat_serv->getPangkatList($cari);	
		$this->view->jabatanList = $this->jabatan_serv->getJabatanList($cari);	
		$this->view->penghargaanList = $this->penghargaan_serv->getPenghargaanList($cari);	
		$this->view->hukumanList = $this->hukuman_serv->getHukumanList($cari);
		$this->view->organisasiList = $this->organisasi_serv->getOrganisasiList($cari);	
		$this->view->luarnegeriList = $this->luarnegeri_serv->getLuarnegeriList($cari);	
		$this->view->kesehatanList = $this->kesehatan_serv->getKesehatanList($cari);
		
		if ($_GET['par']=='cetak'){$this->render('cetakcv');}
    }
}
?>