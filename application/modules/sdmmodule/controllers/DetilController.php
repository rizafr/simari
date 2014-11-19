<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_Pendidikan_Service.php";
require_once "service/sdm/Sdm_Pelatihan_Service.php";
require_once "service/rencana/Rencana_Unitkerja_Service.php";

class Sdmmodule_DetilController extends Zend_Controller_Action {
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->reff_serv = sdm_refferensi_Service::getInstance();
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->pendidikan_serv = Sdm_Pendidikan_Service::getInstance();
		$this->pelatihan_serv = Sdm_Pelatihan_Service::getInstance();	
		$this->unitkerja_serv = Rencana_Unitkerja_Service::getInstance();
		
		$sespeg = new Zend_Session_Namespace('sespeg');

		$this->view->nama= $sespeg->nama;
		$this->view->nip= $sespeg->nip;
		$this->view->golongan= $sespeg->golongan;
		$this->view->pangkat= $sespeg->pangkat;	
		$this->view->filephoto= $sespeg->filephoto;	
		$this->view->statuspeg= $sespeg->statuspeg;		
		
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
	$nCol=strtoupper($_GET['nCol']);
	$cCol=$_GET['cCol'];
	$this->view->nCol = $_GET['nCol'];
	$this->view->cCol = $_GET['cCol'];	
	$nip=$this->view->nip;
	$cari .= " and i_peg_nip !='$nip'  ";
	if ($nCol && $cCol ){$cari .= " and upper($cCol) like '%$nCol%' ";}
	if (!$cCol ){$cari .= "";}	     
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
		$this->view->keydata=$_GET['keydata'];
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


	if ( $_REQUEST['param1']){$this->view->lokasi= $_REQUEST['param1'];}
		else { $this->view->lokasi= $_REQUEST['lokasi'];
		}
		if ( $_REQUEST['param2']){$this->view->eselon_i= $_REQUEST['param2'];}
		else { $this->view->eselon_i= $_REQUEST['eselon_i'];
		}
		if ( $_REQUEST['param3']){$this->view->eselon_ii= $_REQUEST['param3'];}
		else { $this->view->eselon_ii= $_REQUEST['eselon_ii'];
		}
		if ( $_REQUEST['param4']){$this->view->eselon_iii= $_REQUEST['param4'];}
		else { $this->view->eselon_iii= $_REQUEST['eselon_iii'];
		}
		if ( $_REQUEST['param5']){$this->view->eselon_iv= $_REQUEST['param5'];}
		else { $this->view->eselon_iv= $_REQUEST['eselon_iv'];
		}

		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= $_POST['kategoriCari'];

		$this->view->c_eselon_i 	 = trim($this->c_eselon_i);

		if($this->view->lokasi == '1'){
		$v_eselon_ii 				= $this->view->eselon_ii;
		$eselon_ii_arr				= explode("~", $v_eselon_ii);
		$eselon_ii					= $eselon_ii_arr[0];
		
		$v_eselon_iii 				= $this->view->eselon_iii;
		$eselon_iii_arr				= explode("~", $v_eselon_iii);
		$eselon_iii					= $eselon_iii_arr[0];
		$this->view->eselon_iv	= trim($this->view->eselon_iv);
		
		} else {
		$v_eselon_ii 				= $this->view->eselon_ii;
		$eselon_ii_arr				= explode("~", $v_eselon_ii);
		$eselon_ii					= $eselon_ii_arr[0];
		$c_parent					= $eselon_ii_arr[1];
		
		$v_eselon_iii 				= $this->view->eselon_iii;
		$eselon_iii_arr				= explode("~", $v_eselon_iii);
		$eselon_iii					= $eselon_iii_arr[0];
		$c_child					= $eselon_iii_arr[1];
		$this->view->eselon_iv	= trim($this->view->eselon_iv);
		}

		$this->view->lokasiList = $this->unitkerja_serv->getLokasi('');
		$this->view->eseloniList = $this->unitkerja_serv->getEselon_i($this->view->lokasi);
		$this->view->eseloniiList = $this->unitkerja_serv->getEselon_ii($this->view->lokasi,$this->view->eselon_i);
		$this->view->eseloniiiList = $this->unitkerja_serv->getEselon_iii($this->view->lokasi,$this->view->eselon_i,$eselon_ii,$c_parent);
		$this->view->eselonivList = $this->unitkerja_serv->getEselon_iv($this->view->lokasi,$this->view->eselon_i,$eselon_ii,$eselon_iii,$c_parent);



	
/*	if ($_GET['nCol'])
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
	} */

		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->katakunciCari 	= $_REQUEST['carii'];
		
		$dataMasukan = array("kategoriCari" => $this->view->kategoriCari,
							 "katakunciCari"=> $this->view->katakunciCari,
							 "lokasi"		=> $this->view->lokasi,
							 "eselon_i"		=> $this->view->eselon_i,
							 "eselon_ii"	=> $eselon_ii,
							 "eselon_iii"	=> $eselon_iii,
							 "eselon_iv"	=> $this->view->eselon_iv,
							 "c_parent"		=> $c_parent,
							 "c_child"		=> $c_child
							 );
	//if ($nCol && $cCol ){$cari .= " and $cCol like '%$nCol%' ";}			
	$this->view->totalpegawaiList = $this->pegawai_serv->getPegawaiList2($dataMasukan, 0, 0 );		
	$this->view->PegawaiList = $this->pegawai_serv->getPegawaiList2($dataMasukan, $currentPage, $numToDisplay );
}	

 public function carilistnamapejabatanAction() { 
		$c_eselon_i=$_POST['c_eselon_i'];
		$c_eselon_ii=$_POST['c_eselon_ii'];
		$c_eselon_iii=$_POST['c_eselon_iii'];
		$c_eselon_iv=$_POST['c_eselon_iv'];
		$c_eselon_v=$_POST['c_eselon_v'];
		$c_parent=$_POST['c_parent'];
		$cCol=$_POST['cCol'];
		$nCol=$_POST['nCol'];
		/*echo "cCol-------------------->".$cCol;
		echo "nCol-------------------->".$nCol;
		echo "eselon_i-------------------->".$c_eselon_i;
		echo "eselon_ii-------------------->".$c_eselon_ii;
		echo "eselon_iiii-------------------->".$c_eselon_iii;
		echo "eselon_iv-------------------->".$c_eselon_iv;
		echo "eselon_v-------------------->".$c_eselon_v; */
		//$this->render('listnamapejabatan');
		if ($nCol && $cCol ){$cari .= " and $cCol like '%$nCol%' ";}			
	$this->view->totalpegawaiList = $this->pegawai_serv->getPegawaiList($cari, 0, 0 ,$orderBy);		
	$this->view->PegawaiList = $this->pegawai_serv->getPegawaiList($cari, $currentPage, $numToDisplay,$orderBy );
}

}
?>