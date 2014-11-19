<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_Pendidikan_Service.php";
require_once "service/sdm/Sdm_Pelatihan_Service.php";

class Sdmmodule_DetilController extends Zend_Controller_Action {
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->reff_serv = sdm_refferensi_Service::getInstance();
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->pendidikan_serv = Sdm_Pendidikan_Service::getInstance();
		$this->pelatihan_serv = Sdm_Pelatihan_Service::getInstance();	
		
    }
	
    public function indexAction() {
	   
    }


     public function detilpegawaiAction() {    
	$nip=$_GET['nip'];	
	$cari = " and i_peg_nip ='$nip' ";	
	$datapegawai=$this->pegawai_serv->getPegawaiListByNip($cari );
	
	$this->view->i_peg_nip= $datapegawai[0]['i_peg_nip'];
	$this->view->n_peg= $datapegawai[0]['n_peg'];	
	$this->view->n_peg_gelardepan=$datapegawai[0]['n_peg_gelardepan'];
	$this->view->n_peg_gelarblkg=$datapegawai[0]['n_peg_gelarblkg'];
	$this->view->i_peg_karpeg=$datapegawai[0]['i_peg_karpeg'];
	$this->view->i_npwp=$datapegawai[0]['i_npwp'];
	$this->view->c_peg_golongan=$datapegawai[0]['c_peg_golongan'];
	$this->view->n_jabatan=$datapegawai[0]['n_jabatan'];
	$this->view->d_peg_tmtmasuk=$datapegawai[0]['d_peg_tmtmasuk'];
	$this->view->c_peg_status=$datapegawai[0]['c_peg_status'];
	$this->view->c_peg_jeniskelamin=$datapegawai[0]['c_peg_jeniskelamin'];
	$this->view->c_peg_statusnikah=$datapegawai[0]['c_peg_statusnikah'];
	$this->view->d_peg_lahir=$datapegawai[0]['d_peg_lahir'];
	$this->view->c_peg_identitas=$datapegawai[0]['c_peg_identitas'];
	$this->view->i_peg_identitas=$datapegawai[0]['i_peg_identitas'];
	$this->view->n_peg_wn=$datapegawai[0]['n_peg_wn'];
	$this->view->n_peg_suku=$datapegawai[0]['n_peg_suku'];
	$this->view->a_peg_lahir=$datapegawai[0]['a_peg_lahir'];
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
	$this->view->i_orgb=$datapegawai[0]['i_orgb'];
	$this->view->c_agama=$datapegawai[0]['c_agama'];
	$this->view->c_pend=$datapegawai[0]['c_pend'];
	$this->view->n_peg_hobi=$datapegawai[0]['n_peg_hobi'];
	$this->view->a_ortu_jalan=$datapegawai[0]['a_ortu_jalan'];
	$this->view->a_ortu_rt=$datapegawai[0]['a_ortu_rt'];
	$this->view->a_ortu_rw=$datapegawai[0]['a_ortu_rw'];
	$this->view->a_ortu_kelurahan=$datapegawai[0]['a_ortu_kelurahan'];
	$this->view->a_ortu_kecamatan=$datapegawai[0]['a_ortu_kecamatan'];
	$this->view->a_ortu_kota=$datapegawai[0]['a_ortu_kota'];
	$this->view->a_ortu_propinsi=$datapegawai[0]['a_ortu_propinsi'];
	$this->view->e_keterangan=$datapegawai[0]['e_keterangan'];
	$this->view->c_unit_kerja=$datapegawai[0]['c_unit_kerja'];
	$this->view->n_ortu=$datapegawai[0]['n_ortu'];
	$this->view->i_ortu_telponhp=$datapegawai[0]['i_ortu_telponhp'];
	$this->view->i_ortu_telponrumah=$datapegawai[0]['i_ortu_telponrumah'];
	$this->view->i_entry=$datapegawai[0]['i_entry'];
	$this->view->d_entry=$datapegawai[0]['d_entry'];
	$this->view->c_eselon=$datapegawai[0]['c_eselon'];
	$this->view->d_eselon_tmt=$datapegawai[0]['d_eselon_tmt'];
	$this->view->e_peg_foto=$datapegawai[0]['e_peg_foto'];
	$this->view->i_peg_email=$datapegawai[0]['i_peg_email'];
	$this->view->i_peg_email1=$datapegawai[0]['i_peg_email1'];
	$this->view->i_orgb_penempatan=$datapegawai[0]['i_orgb_penempatan'];
	$this->view->c_golongan_darah=$datapegawai[0]['c_golongan_darah'];
	$this->view->v_jumlah_anak=$datapegawai[0]['v_jumlah_anak'];


    }
      public function detilpendidikanAction() {
	$nip=$_GET['nip'];
	$cPend=$_GET['cPend'];
	$carilist = " and i_peg_nip='$nip' and c_pend='$cPend' ";
	
	$datapend=$this->pendidikan_serv->getPendidikanList($carilist); 
	$this->view->c_pend=$datapend [0]['c_pend'];
	$this->view->n_pend_jurusan=$datapend [0]['n_pend_jurusan'];
	$this->view->n_pend=$datapend [0]['n_pend'];
	$this->view->n_pend_lembaga=$datapend [0]['n_pend_lembaga'];
	$this->view->a_pend_alamat=$datapend [0]['a_pend_alamat'];	
	$this->view->n_pend_kepsek=$datapend [0]['n_pend_kepsek'];
	$this->view->d_pend_mulai=$datapend [0]['d_pend_mulai'];
	$this->view->d_pend_akhir=$datapend [0]['d_pend_akhir'];
	$this->view->i_pend_ipk=$datapend [0]['i_pend_ipk'];
	$this->view->e_pend_skripsi=$datapend [0]['e_pend_skripsi'];
	$this->view->d_pend_ijazah=$datapend [0]['d_pend_ijazah'];
	$this->view->c_pend_sumberdana=$datapend [0]['c_pend_sumberdana'];
	$this->view->i_pend_ijazah=$datapend [0]['i_pend_ijazah'];
	$this->view->e_keterangan=$datapend [0]['e_keterangan'];
	$this->view->nmJenjangList = $this->reff_serv->getPendidikan($cari);
}
    public function listnamajabatanAction() {
	$c_eselon=$_GET['c_eselon'];
	if ($c_eselon){$cari=" and c_eselon='$c_eselon' ";}
	$this->view->nmJabatanList = $this->reff_serv->getJabatan($cari);
}
     public function listpegawaidptigaAction() { 
		$this->view->par=$_GET['par'];
		$currentPage=$_GET['currentPage'];
		if((!$currentPage) || ($currentPage == 'undefined'))
			{$currentPage = 1;}
			$numToDisplay = 10;
			$this->view->numToDisplay = $numToDisplay;
			$this->view->currentPage = $currentPage;
			
			$this->view->totalpegawaiList = $this->pegawai_serv->getPegawaiList($cari, 0, 0 ,$orderBy);		
			$this->view->pegawaiList = $this->pegawai_serv->getPegawaiList($cari, $currentPage, $numToDisplay,$orderBy );
}
     public function listpegawaicutiAction() { 
		// $this->view->par=$_GET['par'];
		// $this->view->PegawaiList=$this->pegawai_serv->getPegawaiListByNip($cari );	 
		

	$nCol=strtoupper($_GET['nCol']);
	$cCol=$_GET['cCol'];
	$this->view->nCol = $_GET['nCol'];
	$this->view->cCol = $_GET['cCol'];	

	if ($nCol && $cCol ){$cari .= " and upper($cCol) like '%$nCol%' ";}
	if (!$cCol ){$cari .= "";}	
		
		$currentPage=$_GET['currentPage'];
		if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
			$numToDisplay = 10;
			$this->view->numToDisplay = $numToDisplay;
			$this->view->currentPage = $currentPage;
			
			$this->view->totalpegawaiList = $this->pegawai_serv->getPegawaiList($cari, 0, 0 ,$orderBy);		
			$this->view->PegawaiList = $this->pegawai_serv->getPegawaiList($cari, $currentPage, $numToDisplay,$orderBy );		
}
 	
	
     public function closedetilAction() {    

}

     public function listpegawaipenilaiAction() { 
		$this->view->par=$_GET['par'];
		$currentPage=$_GET['currentPage'];
		if((!$currentPage) || ($currentPage == 'undefined'))
			{$currentPage = 1;}
			$numToDisplay = 10;
			$this->view->numToDisplay = $numToDisplay;
			$this->view->currentPage = $currentPage;
			
			$this->view->totalpegawaiList = $this->pegawai_serv->getPegawaiList($cari, 0, 0 ,$orderBy);		
			$this->view->pegawaiList = $this->pegawai_serv->getPegawaiList($cari, $currentPage, $numToDisplay,$orderBy );
}

public function listnamapejabatanAction() {
	$this->view->nom=$_GET['nom'];
	$this->view->par=$_GET['par'];
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 10;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	if ($_GET['nCol'])
	{
		$nCol=strtoupper($_GET['nCol']);
		$cCol=$_GET['cCol'];	
		 $this->view->nCol = $_GET['nCol'];
		 $this->view->cCol = $_GET['cCol'];	
	}
	else{
		$nCol=strtoupper($_GET['nCol']);
		$cCol=$_GET['cCol'];
		 $this->view->nCol = $_GET['nCol'];
		 $this->view->cCol = $_GET['cCol'];	
	}


	if ($nCol && $cCol ){$cari .= " and $cCol like '%$nCol%' ";}			
	$this->view->totalpegawaiList = $this->pegawai_serv->getPegawaiList($cari, 0, 0 ,$orderBy);		
	$this->view->PegawaiList = $this->pegawai_serv->getPegawaiList($cari, $currentPage, $numToDisplay,$orderBy );
}	 
}
?>