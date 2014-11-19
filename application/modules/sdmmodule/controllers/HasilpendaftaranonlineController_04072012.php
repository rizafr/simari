<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Pendaftaranonline_Service.php";

class Sdmmodule_HasilpendaftaranonlineController extends Zend_Controller_Action {

		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu');
		$this->view->photoPath = $registry->get('photoPath');
		 
		$this->reff_serv = sdm_refferensi_Service::getInstance();
		$this->daftarol_serv = Sdm_Pendaftaranonline_Service::getInstance();
    }
	
	public function indexAction() {
	}
	public function catatAction() {
		$q_nomor_daftar=$_GET['q_nomor_daftar'];
		$this->listDataByKey($q_nomor_daftar,'');

		//$this->view->propinsiList = $this->reff_serv->getPropinsiListAll();
		//$this->view->kabupatenList = $this->reff_serv->getKabupatenListAll($carikabupaten);	
		//$this->view->nmJenjangList = $this->reff_serv->getPendidikan('');
		//$this->view->agamaList = $this->reff_serv->getAgamaList('');
	}
     
	public function catatjsAction() 
	{
		header('content-type : text/javascript');
		$this->render('catatjs');
	}

	// public function viewAction() {
		// $q_nomor_daftar=$_GET['q_nomor_daftar'];
		// $this->listDataByKey($q_nomor_daftar,'hasil');

		// $this->view->propinsiList = $this->reff_serv->getPropinsiListAll();
		// $this->view->kabupatenList = $this->reff_serv->getKabupatenListAll($carikabupaten);	
		// $this->view->nmJenjangList = $this->reff_serv->getPendidikan('');
		// $this->view->agamaList = $this->reff_serv->getAgamaList('');
	// }	
	
	
public function listDataByKey($q_nomor_daftar,$par) {  
	// if($par=='hasil'){$cari = " and q_nomor_daftar ='$q_nomor_daftar'";}
	// else{$cari = " and q_nomor_daftar ='$q_nomor_daftar' and (c_hasil is null or c_hasil=0) ";}
	$cari = " and q_nomor_daftar ='$q_nomor_daftar' and (c_hasil is null or c_hasil=0) ";
	
	$this->view->q_nomor_daftar=$q_nomor_daftar;
	$datapegawai=$this->daftarol_serv->getPegawaiListByNodaf($cari);
	if (count($datapegawai)==0){$cari = " and q_nomor_daftar ='$q_nomor_daftar'";}
	
	$datapegawai=$this->daftarol_serv->getPegawaiListByNodaf($cari);
	
	if (count($datapegawai)!=0){
	$this->view->i_ktp= $datapegawai[0]['i_ktp'];	
	$this->view->c_hasil= $datapegawai[0]['c_hasil'];	
	$this->view->n_pendaftar= $datapegawai[0]['n_pendaftar'];	
	$this->view->c_jeniskelamin=$datapegawai[0]['c_jeniskelamin'];
	$this->view->c_agama=$datapegawai[0]['c_agama'];
	$this->view->c_golongan_darah=trim($datapegawai[0]['c_golongan_darah']);
	$this->view->c_statusnikah=trim($datapegawai[0]['c_statusnikah']);
	$this->view->n_hobi=$datapegawai[0]['n_hobi'];
	$this->view->d_lahir=$datapegawai[0]['d_lahir'];
	$this->view->c_propinsi_lahir=trim($datapegawai[0]['c_propinsi_lahir']);
	$this->view->a_kota_lahir=trim($datapegawai[0]['a_kota_lahir']);
	$this->view->q_tinggibdn=$datapegawai[0]['q_tinggibdn'];
	$this->view->q_beratbdn=$datapegawai[0]['q_beratbdn'];
	$this->view->n_rambut=$datapegawai[0]['n_rambut'];
	$this->view->n_btkmuka=$datapegawai[0]['n_btkmuka'];
	$this->view->n_warnakulit=$datapegawai[0]['n_warnakulit'];
	$this->view->n_cirikhas=$datapegawai[0]['n_cirikhas'];
	$this->view->a_rumah=$datapegawai[0]['a_rumah'];
	$this->view->a_rt=$datapegawai[0]['a_rt'];
	$this->view->a_rw=$datapegawai[0]['a_rw'];
	$this->view->a_kelurahan=$datapegawai[0]['a_kelurahan'];
	$this->view->a_kecamatan=$datapegawai[0]['a_kecamatan'];
	$this->view->a_kota=$datapegawai[0]['a_kota'];
	$this->view->n_kota=$datapegawai[0]['n_kota'];
	$this->view->a_propinsi=$datapegawai[0]['a_propinsi'];
	$this->view->n_propinsi=$datapegawai[0]['n_propinsi'];
	$this->view->a_kodepos=$datapegawai[0]['a_kodepos'];
	$this->view->i_telponrumah=$datapegawai[0]['i_telponrumah'];
	$this->view->i_telponhp=$datapegawai[0]['i_telponhp'];
	$this->view->a_email=$datapegawai[0]['a_email'];
	$this->view->c_pend=$datapegawai[0]['c_pend'];
	$this->view->n_pend_jurusan=$datapegawai[0]['n_pend_jurusan'];
	$this->view->d_pend_mulai=$datapegawai[0]['d_pend_mulai'];
	$this->view->d_pend_akhir=$datapegawai[0]['d_pend_akhir'];
	$this->view->i_pend_ipk=$datapegawai[0]['i_pend_ipk'];
	$this->view->i_pend_ijazah=$datapegawai[0]['i_pend_ijazah'];
	$this->view->d_pend_ijazah=$datapegawai[0]['d_pend_ijazah'];
	$this->view->c_warganegara=$datapegawai[0]['c_warganegara'];
	$this->view->n_posisi_jabatan=$datapegawai[0]['n_posisi_jabatan'];
	$this->view->n_pengadilan=$datapegawai[0]['n_pengadilan'];
	$this->view->n_wilayah=$datapegawai[0]['n_wilayah'];
	$this->view->c_hasil=$datapegawai[0]['c_hasil'];

	
	$this->view->d_entry=$datapegawai[0]['d_entry'];


	$c_propinsi=trim($datapegawai[0]['a_propinsi']);
	$carikabupaten=" and c_propinsi ='$c_propinsi' ";
	$this->view->kabupatenList = $this->reff_serv->getKabupatenListAll($carikabupaten);
	$c_propinsilahir=trim($datapegawai[0]['c_propinsi_lahir']);
	$carikabupatenlahir=" and c_propinsi ='$c_propinsilahir' ";
	$this->view->kabupatenListLahir = $this->reff_serv->getKabupatenListAll($carikabupatenlahir);	
	$this->view->kabupatenList = $this->reff_serv->getKabupatenListAll($carikabupaten);	
	$this->view->nmJenjangList = $this->reff_serv->getPendidikan('');
	$this->view->agamaList = $this->reff_serv->getAgamaList('');
	$this->view->propinsiList = $this->reff_serv->getPropinsiListAll();
	}

}

public function maintaindataAction() {
	if ($_GET['par']=='lulus'){$c_hasil=1;}
	if ($_GET['par']=='tdklulus'){$c_hasil=2;}
	$MaintainData = array("q_nomor_daftar"=>trim($_GET['q_nomor_daftar']),"c_hasil"=>$c_hasil);		
	$hasil = $this->daftarol_serv->updateHasil($MaintainData,'hasil');
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;
	$this->render('catat');							
}

public function daftarAction() {
	if ($_POST['nCol'])
	{
		$nCol=strtoupper($_POST['nCol']);
		$cCol=$_POST['cCol'];	
		 $this->view->nCol = $_POST['nCol'];
		 $this->view->cCol = $_POST['cCol'];	
	}
	else{
		$nCol=strtoupper($_GET['nCol']);
		$cCol=$_GET['cCol'];
		 $this->view->nCol = $_GET['nCol'];
		 $this->view->cCol = $_GET['cCol'];	
	}
	$tahun=date('Y');
	$cari=" and d_tahun='$tahun' ";
	if ($nCol && $cCol ){
		if ($cCol=="c_hasil"){
			$cari .= " and $cCol =$nCol ";
		}
		else{
		$cari .= " and upper($cCol) like '%$nCol%' ";
		}
	}
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;		

		$this->view->totaldataList = $this->daftarol_serv->getDaftar($cari, 0, 0 );		
		$this->view->dataList = $this->daftarol_serv->getDaftar($cari, $currentPage, $numToDisplay);	
		

}
public function listcombojabatanAction() {
	$this->view->jabatanList = $this->reff_serv->getTrJabatanCalon('');
}	  
public function listcombowilayahAction() {
	$this->view->wilayahList = $this->reff_serv->getTrWilPengadilan('');
}
public function listcombohasilAction() {

}
public function viewAction() {
	$q_nomor_daftar=$_GET['q_nomor_daftar'];
	$this->listDataByKey($q_nomor_daftar,$par);
}

public function rekapAction() {
	$tahun=date('Y');
	$cari=" and d_tahun='$tahun' ";
	$this->view->dataList = $this->daftarol_serv->getRekap($cari);
}
public function cetakrekapAction() {
	$tahun=date('Y');
	$cari=" and d_tahun='$tahun' ";
	$this->view->dataList = $this->daftarol_serv->getRekap($cari);
}
}
?>