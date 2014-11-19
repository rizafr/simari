<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_Pendidikan_Service.php";
require_once "service/sdm/Sdm_Pelatihan_Service.php";

class Sdmmodule_ViewdatapokokController extends Zend_Controller_Action {

		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu');
		$this->view->photoPath = $registry->get('photoPath');
		 
		$this->reff_serv = sdm_refferensi_Service::getInstance();
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->pendidikan_serv = Sdm_Pendidikan_Service::getInstance();
		$this->pelatihan_serv = Sdm_Pelatihan_Service::getInstance();	
		$this->view->nama= $this->nama;
		$this->view->nip= $this->nip;
		$this->view->golongan= $this->golongan;
		$this->view->pangkat=$this->pangkat;
		
		$sespeg = new Zend_Session_Namespace('sespeg');

		$this->view->nama= $sespeg->nama;
		$this->view->nip= $sespeg->nip;
		$this->view->golongan= $sespeg->golongan;
		$this->view->pangkat= $sespeg->pangkat;	

		$sesmenu = new Zend_Session_Namespace('sesmenu');
		$this->view->menu= $sesmenu->menu;
    }
	
    public function indexAction() {
    }
	public function pegawaijsAction() 
	{
		header('content-type : text/javascript');
		$this->render('pegawaijs');
	}	
	
    public function listcombosatkerAction() {
	$i_org_parent=$_GET['i_org_parent'];
	$cari=" and i_orgb_parent ='$i_org_parent' ";
	$this->view->unitKList = $this->pegawai_serv->getUnitKerjaList($cari);	   
    }
    public function listcombokabupatenAction() {
	$c_propinsi=$_GET['c_propinsi'];
	$this->view->par=$_GET['target'];
	$carikabupaten=" and c_propinsi ='$c_propinsi' ";
	$this->view->kabupatenList = $this->reff_serv->getKabupatenListAll($carikabupaten); 
    }	
	
public function listpegawaiAction() {    
	$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai($cari);
	$this->view->statusPegRef = $this->reff_serv->getStatusPegawai($cari);
	$statuspegcari=$_GET['statuspegcari'];
	$golcari=$_GET['golcari'];
	$namacari=strtoupper($_GET['namacari']);
	$nipcari=$_GET['nipcari'];
	$this->view->statuspegcari=$_GET['statuspegcari'];
	$this->view->golcari=$_GET['golcari'];
	$this->view->namacari=$_GET['namacari'];
	$this->view->nipcari=$_GET['nipcari'];
	if ($nipcari){$cari= " and i_peg_nip like '%$nipcari%' ";}
	if ($namacari){$cari .= " and upper(n_peg) like '%$namacari%' ";}
	if ($golcari){$cari .= " and c_peg_golongan = '$golcari' ";}
	if ($statuspegcari){$cari .= " and c_peg_status = '$statuspegcari' ";}
	$orderBy=$_GET['orderBy'];
	$order=$_GET['order'];
	if (!$_GET['order']){$this->view->orderbya="asc";$this->view->orderbyb="desc";}
	else{
		if ($_GET['order']=='desc'){	$this->view->orderbya="desc";$this->view->orderbyb="asc";}
		else{$this->view->orderbya="asc";$this->view->orderbyb="desc";}
	}
	if ($_GET['orderBy']){$orderBy=" order by $orderBy $order";}
	$this->view->orderBy=$_GET['orderBy'];
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totalpegawaiList = $this->pegawai_serv->getPegawaiList($cari, 0, 0 ,$orderBy);		
		$this->view->pegawaiList = $this->pegawai_serv->getPegawaiList($cari, $currentPage, $numToDisplay,$orderBy );	
		
		$sesmenu = new Zend_Session_Namespace('sesmenu');
		$sesmenu->menu= $_GET['menu'];
		$this->view->menu= $_GET['menu'];
    }
public function toplinkAction() {

}
public function pegawaiAction() {
	$par=$_GET['par'];
	if ($par=='insert'){
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
	}
	else{
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$nip=$_GET['nip'];
		if (!$nip){$nip=$this->view->nip;}
		$this->listDataByKey($nip);
	}	
	//$this->view->unitOList = $this->pegawai_serv->getUnitOrgList();
	//$this->view->unitKList = $this->pegawai_serv->getUnitKerjaList($carisatker);
	$this->view->propinsiList = $this->reff_serv->getPropinsiListAll();
	$this->view->kabupatenList = $this->reff_serv->getKabupatenListAll($carikabupaten);
	//$this->view->statusPegList = $this->reff_serv->getStatusPegListAll();
	//$this->view->agamaList = $this->reff_serv->getAgamaListAll();
	$this->view->kabupatenListLahir = $this->reff_serv->getKabupatenListAll($carikabupatenlahir);
	$this->view->menulink="datadiri";
}
	
public function listDataByKey($nip) {  
	$cari = " and i_peg_nip ='$nip' ";
	$datapegawai=$this->pegawai_serv->getPegawaiListByNip($cari );
	$sespeg = new Zend_Session_Namespace('sespeg');
	$sespeg->nama= $datapegawai[0]['n_peg'];
	$sespeg->nip= $datapegawai[0]['i_peg_nip'];
	$sespeg->golongan= $datapegawai[0]['c_peg_golongan'];
	$sespeg->pangkat= $datapegawai[0]['n_peg_pangkat'];
	
	$this->view->nama= $datapegawai[0]['n_peg'];
	$this->view->nip= $datapegawai[0]['i_peg_nip'];
	$this->view->golongan= $datapegawai[0]['c_peg_golongan'];
	$this->view->pangkat= $datapegawai[0]['n_peg_pangkat'];	

	$this->view->i_peg_nip=$datapegawai[0]['i_peg_nip'];
	$this->view->i_peg_nip_new=$datapegawai[0]['i_peg_nip_new'];	
	$this->view->n_peg=$datapegawai[0]['n_peg'];
	$this->view->n_peg_gelardepan=$datapegawai[0]['n_peg_gelardepan'];
	$this->view->n_peg_gelarblkg=$datapegawai[0]['n_peg_gelarblkg'];
	$this->view->c_peg_jeniskelamin=$datapegawai[0]['c_peg_jeniskelamin'];
	$this->view->c_agama=$datapegawai[0]['c_agama'];
	$this->view->c_golongan_darah=trim($datapegawai[0]['c_golongan_darah']);
	$this->view->c_peg_statusnikah=trim($datapegawai[0]['c_peg_statusnikah']);
	$this->view->n_peg_hobi=$datapegawai[0]['n_peg_hobi'];
	$this->view->d_peg_lahir=$datapegawai[0]['d_peg_lahir'];
	$this->view->c_peg_propinsi_lahir=trim($datapegawai[0]['c_peg_propinsi_lahir']);
	$this->view->a_peg_kota_lahir=trim($datapegawai[0]['a_peg_kota_lahir']);
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
	$this->view->i_peg_karpeg=$datapegawai[0]['i_peg_karpeg'];
	$this->view->i_peg_karis=$datapegawai[0]['i_peg_karis'];
	$this->view->i_peg_taspen=$datapegawai[0]['i_peg_taspen'];
	$this->view->i_peg_korpri=$datapegawai[0]['i_peg_korpri'];
	$this->view->i_peg_ktp=$datapegawai[0]['i_peg_ktp'];
	$this->view->i_peg_askes=$datapegawai[0]['i_peg_askes'];
	$this->view->e_file_photo=$datapegawai[0]['e_file_photo'];
	$this->view->c_stat_aktivasi=$datapegawai[0]['c_stat_aktivasi'];
	$this->view->i_entry=$datapegawai[0]['i_entry'];
	$this->view->d_entry=$datapegawai[0]['d_entry'];

}

public function hapusdataAction() {
 	$i_peg_nip=$_GET['nip'];
	$i_entry='HDR';
	$hasil = $this->pegawai_serv->maintainHapusData($i_peg_nip,$i_entry);

	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totalpegawaiList = $this->pegawai_serv->getPegawaiList($cari, 0, 0 ,$orderBy);		
		$this->view->pegawaiList = $this->pegawai_serv->getPegawaiList($cari, $currentPage, $numToDisplay,$orderBy );
	
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;		
	$this->render('listpegawai');
}

public function maintaindataAction() {

$i_peg_telponrumah=null;
if (!$_POST['i_peg_telponrumah1'])
{$i_peg_telponrumah1="000";}
if (!$_POST['i_peg_telponrumah2']){
$i_peg_telponrumah=$i_peg_telponrumah1."-".$_POST['i_peg_telponrumah2'];
}
else{$i_peg_telponrumah=$_POST['i_peg_telponrumah1']."-".$_POST['i_peg_telponrumah2'];}


$d_peg_lahir=null;
if ($_POST['d_peg_lahir']){
	$d_peg_lahir= substr($_POST['d_peg_lahir'],6,4)."-".substr($_POST['d_peg_lahir'],3,2)."-".substr($_POST['d_peg_lahir'],0,2);
}



//simpan file			
		if ($_POST['a_file'])
		{
			if ($_POST['a_file']!=$_POST['a_file2'])
			{				
				$namefile=trim($_POST['i_peg_nip']);
				$FileName_dat = $namefile;
				$fileName = $_FILES['a_filex']['name'];
				$extention = substr($fileName, -3, 3);				
				$FileName_pdf = $FileName_dat.'.'.$extention;
				$destDir = "$FileName_pdf";	
			}
			else
			{
				$destDir =$_POST['a_file'];
			}
		}

///
if ($_POST['del_photo']=="on") {$e_file_photo=null;}
else {$e_file_photo=$destDir;}

	$MaintainData = array("i_peg_nip"=>$_POST['i_peg_nip'],
							"i_peg_nip_new"=>$_POST['i_peg_nip_new'],
							"n_peg"=>$_POST['n_peg'],
							"n_peg_gelardepan"=>$_POST['n_peg_gelardepan'],
							"n_peg_gelarblkg"=>$_POST['n_peg_gelarblkg'],
							"c_peg_jeniskelamin"=>$_POST['c_peg_jeniskelamin'],
							"c_agama"=>$_POST['c_agama'],
							"c_golongan_darah"=>$_POST['c_golongan_darah'],
							"c_peg_statusnikah"=>$_POST['c_peg_statusnikah'],
							"n_peg_hobi"=>$_POST['n_peg_hobi'],
							"d_peg_lahir"=>$d_peg_lahir,
							"c_peg_propinsi_lahir"=>$_POST['c_peg_propinsi_lahir'],
							"a_peg_kota_lahir"=>$_POST['a_peg_kota_lahir'],
							"q_peg_tinggibdn"=>$_POST['q_peg_tinggibdn'],
							"q_peg_beratbdn"=>$_POST['q_peg_beratbdn'],
							"n_peg_rambut"=>$_POST['n_peg_rambut'],
							"n_peg_btkmuka"=>$_POST['n_peg_btkmuka'],
							"n_peg_warnakulit"=>$_POST['n_peg_warnakulit'],
							"n_peg_cirikhas"=>$_POST['n_peg_cirikhas'],
							"a_peg_rumah"=>$_POST['a_peg_rumah'],
							"a_peg_rt"=>$_POST['a_peg_rt'],
							"a_peg_rw"=>$_POST['a_peg_rw'],
							"a_peg_kelurahan"=>$_POST['a_peg_kelurahan'],
							"a_peg_kecamatan"=>$_POST['a_peg_kecamatan'],
							"a_peg_kota"=>$_POST['a_peg_kota'],
							"a_peg_propinsi"=>$_POST['a_peg_propinsi'],
							"a_peg_kodepos"=>$_POST['a_peg_kodepos'],
							"i_peg_telponrumah"=>$i_peg_telponrumah,
							"i_peg_telponhp"=>$_POST['i_peg_telponhp'],
							"i_peg_karpeg"=>$_POST['i_peg_karpeg'],
							"i_peg_karis"=>$_POST['i_peg_karis'],
							"i_peg_taspen"=>$_POST['i_peg_taspen'],
							"i_peg_korpri"=>$_POST['i_peg_korpri'],
							"i_peg_ktp"=>$_POST['i_peg_ktp'],
							"i_peg_askes"=>$_POST['i_peg_askes'],
							"e_file_photo"=>$e_file_photo,
							"c_stat_aktivasi"=>"A",
							"i_entry"=>"HDR",
							"d_entry"=>date("Y-m-d"));

if ($_POST['i_peg_nip']){						
	if ($_POST['proses']=='Simpan')
	{
		$hasil = $this->pegawai_serv->maintainData($MaintainData,'insert');		
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
	}		
	else
	{
		$hasil = $this->pegawai_serv->maintainData($MaintainData,'update');
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";
		$this->listDataByKey($_POST['i_peg_nip']) ;
		//$this->view->unitOList = $this->pegawai_serv->getUnitOrgList();
		//$this->view->unitKList = $this->pegawai_serv->getUnitKerjaList($carisatker);
		$this->view->propinsiList = $this->reff_serv->getPropinsiListAll();
		$c_propinsi=trim($this->view->a_peg_propinsi);
		$carikabupaten=" and c_propinsi ='$c_propinsi' ";
		$this->view->kabupatenList = $this->reff_serv->getKabupatenListAll($carikabupaten);
		$c_propinsilahir=trim($this->view->c_peg_propinsi_lahir);
		$carikabupatenlahir=" and c_propinsi ='$c_propinsilahir' ";
		$this->view->kabupatenListLahir = $this->reff_serv->getKabupatenListAll($carikabupatenlahir);
		//$this->view->statusPegList = $this->pegawai_serv->getStatusPegListAll();
		$this->view->agamaList = $this->reff_serv->getAgamaListAll();	 
	}
}
else{ $hasil="gagal";}	

/// simpan file
		if ($hasil=="sukses"){
			$namefile=trim($_POST['i_peg_nip']);
			$FileName_pdf;
			$fileNamex = $_FILES['a_filex']['name'];
			$extentionx = substr($fileNamex, -3, 3);	
				
		    if (!empty($_FILES['a_filex'])) 
		   {$FileName_pdf = $FileName_dat.'.'.$extentionx;}
			$FileName_dat = $namefile;
			$FileName_pdf = $FileName_dat.'.'.$extentionx;				
					
	       if (!empty($_FILES['a_filex'])) 	   {

	           $fileName = $_FILES['a_filex']['name'];
			   $extention = substr($fileName, -3, 3);
					$destDir = "data/sdm/photo/$FileName_pdf";	
					//$destDirx =cropImage(225, 165, "gambar1", 'jpg', $destDir);					

					if (move_uploaded_file($_FILES['a_filex']['tmp_name'], $destDir)) { 
						$lampiran ="file";
					}
					
					//  $img_base = base directory structure for thumbnail images
					//  $w_dst = maximum width of thumbnail
					//  $h_dst = maximum height of thumbnail
					//  $n_img = new thumbnail name
					//  $o_img = old thumbnail name

					 // $img_base = "data/sdm/photo/$FileName_pdf";	
					 // $w_dst = 225;
					 // $h_dst =165;
					 // $n_img = "x1";
					 // $o_img = "x2";				
					// $this->convertPicx($destDir, $w_dst, $h_dst, $n_img, $o_img);

			}
			}

	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;
	$this->render('pegawai');							
}
	  
 
     public function pelatihanAction() {    
	$this->view->latihList = $this->pelatihan_serv->getPelatihan($cari);	
    }	
     public function pelatihantambahAction() {    
	$this->view->par="Simpan";
	$this->view->jdl="Menambah ";
    }		
      public function pelatihanupdateAction() { 

	$datalatih = $this->pelatihan_serv->getPelatihan($cari);	  
	$this->view->par="Ubah";
	$this->view->jdl="Merubah ";
	$this->render('pelatihantambah');	
    }	

     public function seminarAction() {    

    }	
     public function seminartambahAction() {    
	$this->view->par="Simpan";
	$this->view->jdl="Menambah ";
    }		
      public function seminarupdateAction() {    
	$this->view->par="Ubah";
	$this->view->jdl="Merubah ";
	$this->render('seminartambah');	
    }	
     public function kepangkatanAction() {    

    }	
     public function kepangkatantambahAction() {    
	$this->view->par="Simpan";
	$this->view->jdl="Menambah ";
    }		
      public function kepangkatanupdateAction() {    
	$this->view->par="Ubah";
	$this->view->jdl="Merubah ";
	$this->render('kepangkatantambah');	
    }		
     public function jabatanAction() {    

    }	
     public function jabatantambahAction() {    
	$this->view->par="Simpan";
	$this->view->jdl="Menambah ";
    }		
      public function jabatanupdateAction() {    
	$this->view->par="Ubah";
	$this->view->jdl="Merubah ";
	$this->render('jabatantambah');	
    }		
     public function sertifikasiAction() {    

    }	
     public function sertifikasitambahAction() {    
	$this->view->par="Simpan";
	$this->view->jdl="Menambah ";
    }		
      public function sertifikasiupdateAction() {    
	$this->view->par="Ubah";
	$this->view->jdl="Merubah ";
	$this->render('sertifikasitambah');	
    }	

	
     public function organisasiAction() {    

    }	
     public function organisasitambahAction() {    
	$this->view->par="Simpan";
	$this->view->jdl="Menambah ";
    }		
      public function organisasiupdateAction() {    
	$this->view->par="Ubah";
	$this->view->jdl="Merubah ";
	$this->render('organisasitambah');	
    }	
     public function penghargaanAction() {    

    }	
     public function penghargaantambahAction() {    
	$this->view->par="Simpan";
	$this->view->jdl="Menambah ";
    }		
      public function penghargaanupdateAction() {    
	$this->view->par="Ubah";
	$this->view->jdl="Merubah ";
	$this->render('penghargaantambah');
    }		
     public function hukumanAction() {    

    }	
     public function hukumantambahAction() {    
	$this->view->par="Simpan";
	$this->view->jdl="Menambah ";
    }		
      public function hukumanupdateAction() {    
	$this->view->par="Ubah";
	$this->view->jdl="Merubah ";
	$this->render('hukumantambah');
    }	
	
     public function luarnegeriAction() {    

    }	
     public function luarnegeritambahAction() {    
	$this->view->par="Simpan";
	$this->view->jdl="Menambah ";
    }		
      public function luarnegeriupdateAction() {    
	$this->view->par="Ubah";
	$this->view->jdl="Merubah ";
	$this->render('luarnegeritambah');
    }
     public function kesehatanAction() {    

    }	
     public function kesehatantambahAction() {    
	$this->view->par="Simpan";
	$this->view->jdl="Menambah ";
    }		
      public function kesehatanupdateAction() {    
	$this->view->par="Ubah";
	$this->view->jdl="Merubah ";
	$this->render('kesehatantambah');
    }	
     public function keluargaAction() {    

    }	
     public function keluargatambahAction() {    
	$this->view->par="Simpan";
	$this->view->jdl="Menambah ";
    }		
      public function keluargaupdateAction() {    
	$this->view->par="Ubah";
	$this->view->jdl="Merubah ";
	$this->render('keluargatambah');
    }	
     public function kerabatAction() {    

    }	
     public function kerabattambahAction() {    
	$this->view->par="Simpan";
	$this->view->jdl="Menambah ";
    }		
      public function kerabatupdateAction() {    
	$this->view->par="Ubah";
	$this->view->jdl="Merubah ";
	$this->render('kerabattambah');
    }	
     public function alamatAction() {    

    }	
     public function alamattambahAction() {    
	$this->view->par="Simpan";
	$this->view->jdl="Menambah ";
    }		
      public function alamatupdateAction() {    
	$this->view->par="Ubah";
	$this->view->jdl="Merubah ";
	$this->render('alamattambah');
    }	
      public function ambilfotoAction() {    
	$this->view->par="Simpan";
	$this->view->jdl="Merubah ";
    }		


function convertPic($img_base, $w_dst, $h_dst, $n_img, $o_img)
  {ini_set('memory_limit', '100M');   //  handle large images
   unlink($img_base.$n_img);         //  remove old images if present
   unlink($img_base.$o_img);
   $new_img = $img_base.$n_img;
    
   $file_src = $img_base;  //  temporary safe image storage
   unlink($file_src);
   echo "xxxxxxxxxxxxxxxxxxxxx".$file_src;
   move_uploaded_file($_FILES['Filedata']['tmp_name'], $file_src);
             
   list($w_src, $h_src, $type) = getimagesize($file_src);     // create new dimensions, keeping aspect ratio
   $ratio = $w_src/$h_src;
   if ($w_dst/$h_dst > $ratio) {$w_dst = floor($h_dst*$ratio);} else {$h_dst = floor($w_dst/$ratio);}

   switch ($type)
     {case 1:   //   gif -> jpg
        $img_src = imagecreatefromgif($file_src);
        break;
      case 2:   //   jpeg -> jpg
        $img_src = imagecreatefromjpeg($file_src);
        break;
      case 3:  //   png -> jpg
        $img_src = imagecreatefrompng($file_src);
        break;
     }
   $img_dst = imagecreatetruecolor($w_dst, $h_dst);  //  resample
  
   imagecopyresampled($img_dst, $img_src, 0, 0, 0, 0, $w_dst, $h_dst, $w_src, $h_src);
   imagejpeg($img_dst, $new_img);    //  save new image

   unlink($file_src);  //  clean up image storage
   imagedestroy($img_src);       
   imagedestroy($img_dst);
  }


}
?>