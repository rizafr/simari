<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/cms/Cms_pelayanan_Service.php";
require_once 'share/Portalconf.php'; 

class Cmsmodule_PelayananController extends Zend_Controller_Action {

		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		//$this->view->photoPath = $registry->get('photoPath');
		 
		$this->pelayanan_serv = Cms_pelayanan_Service::getInstance();
		$this->view->idpelayanan= $this->idpelayanan;
		$this->view->jdlpelayanan= $this->jdlpelayanan;
		$this->view->tglpelayanan= $this->tglpelayanan;
		$this->view->detilpelayanan= $this->detilpelayanan;
		$this->view->sumber= $this->sumber;
		$this->view->status= $this->status;
		 $registry = Zend_Registry::getInstance();
		 $this->auth = Zend_Auth::getInstance();
		 $this->view->basePath = $registry->get('basepath');
		 $this->view->baseData = $registry->get('baseData');
		 //$this->admmenu_serv = Adm_Admmenu_Service::getInstance();
		 
		 //sesion dari login
		$ssologin = new Zend_Session_Namespace('ssologin');
		$this->view->userid=$ssologin->userid;
		$this->view->c_jabatan=$ssologin->c_jabatan;	

		}
	
    public function indexAction() {
	
    }
	
public function pelayananjsAction() 
{
	header('content-type : text/javascript');
	$this->render('pelayananjs');
}	
	

public function listpelayananAction() {    
    
	$status=$_GET['status'];
	$key=strtoupper($_GET['key']);
	if ($status!=''){
		if ($key!='') {
		  $cari = " where c_status='$status' and (upper(n_judul) like '%$key%' or upper(n_detil) like '%$key%')";
		} else {
		  $cari = " where c_status='$status'";
		}
	}	
	else if ($key!='') $cari = " where  (upper(n_judul) like '%$key%' or upper(n_detil) like '%$key%')";
	else $cari ="";
	//echo "c=".$cari;
	$orderBy=$_GET['orderBy'];
	$order=$_GET['order'];
	if (!$_GET['order']){$this->view->orderbya="asc";$this->view->orderbyb="desc";}
	else{
		if ($_GET['order']=='desc'){	$this->view->orderbya="desc";$this->view->orderbyb="asc";}
		else{$this->view->orderbya="asc";$this->view->orderbyb="desc";}
	}
	if ($_GET['orderBy']) $orderBy=" order by $orderBy $order";
	else $orderBy=" order by d_pelayanan desc";
	$this->view->orderBy=$_GET['orderBy'];
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totalPelayananList = $this->pelayanan_serv->getPelayananList($cari, 0, 0 ,$orderBy);		
		$this->view->PelayananList = $this->pelayanan_serv->getPelayananList($cari, $currentPage, $numToDisplay,$orderBy );	 
    }
	
public function cmspelayananAction() {    
    
	$status=$_GET['status'];
	$key=strtoupper($_GET['key']);
	if ($status!=''){
		if ($key!='') {
		  $cari = " where c_status='$status' and (upper(n_judul) like '%$key%' or upper(n_detil) like '%$key%')";
		} else {
		  $cari = " where c_status='$status'";
		}
	}	
	else if ($key!='') $cari = " where  (upper(n_judul) like '%$key%' or upper(n_detil) like '%$key%')";
	else $cari ="";
	//echo "c=".$cari;
	$orderBy=$_GET['orderBy'];
	$order=$_GET['order'];
	if (!$_GET['order']){$this->view->orderbya="asc";$this->view->orderbyb="desc";}
	else{
		if ($_GET['order']=='desc'){	$this->view->orderbya="desc";$this->view->orderbyb="asc";}
		else{$this->view->orderbya="asc";$this->view->orderbyb="desc";}
	}
	if ($_GET['orderBy']) $orderBy=" order by $orderBy $order";
	else $orderBy=" order by d_pelayanan desc";
	$this->view->orderBy=$_GET['orderBy'];
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totalPelayananList = $this->pelayanan_serv->getPelayananList($cari, 0, 0 ,$orderBy);		
		$this->view->PelayananList = $this->pelayanan_serv->getPelayananList($cari, $currentPage, $numToDisplay,$orderBy );	 
    }
	

	
public function pelayananAction() {
	$par=$_GET['par'];
	if ($par=='insert'){
		$this->view->par="insert";
		$this->view->pars="Simpan";
		$this->view->jdl="Menambah ";
		//$this->view->maxid=$this->pelayanan_serv->getMaxId();
	}
	else{
		$this->view->par="update";
		$this->view->pars="Ubah";
		$this->view->jdl="Merubah ";
		$idpelayanan=$_GET['idpelayanan'];
		if (!$idpelayanan){$idpelayanan=$this->view->idpelayanan;}
		$this->listDataByKey($idpelayanan);
	}	
}
public function listDataByKey($idpelayanan) { 
	$pelayanan=$this->pelayanan_serv->findPelayananByKey($idpelayanan );
	$this->view->idpelayanan= $pelayanan[0]['c_pelayanan'];
	$this->view->jdlpelayanan= $pelayanan[0]['n_judul'];
	$this->view->detilpelayanan= $pelayanan[0]['n_detil'];
	$this->view->tglpelayanan= $pelayanan[0]['d_pelayanan'];	
	$this->view->status= $pelayanan[0]['c_status'];	
	$this->view->sumber= $pelayanan[0]['n_sumber'];	
	$this->view->ientri=$pelayanan[0]['i_entri'];
	$this->view->dentri=$pelayanan[0]['d_entri'];

}	
public function pelayanandetilAction() {  
		$idpelayanan=$_GET['idpelayanan'];
		if (!$idpelayanan){$idpelayanan=$this->view->idpelayanan;}
		$this->listDataByKey($idpelayanan);
}

public function hapusdataAction() {
 	$idpelayanan=$_GET['idpelayanan'];
	$userlogin=$this->view->userid;
	$hasil = $this->pelayanan_serv->maintainHapusData($idpelayanan,$userlogin);

	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totalPelayananList = $this->pelayanan_serv->getPelayananList($cari, 0, 0 ,$orderBy);		
		$this->view->PelayananList = $this->pelayanan_serv->getPelayananList($cari, $currentPage, $numToDisplay,$orderBy );	
	
	$pesan="Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;		
	$this->render('listpelayanan');
}

public function maintaindataAction() {

$h=$_POST['jam'];
$i=$_POST['mnt'];
$date=$_POST['date'];
$datex=reformatDate($date);
$dates=$datex." ".$h.":".$i ;
$userlogin=$this->view->userid;

	$MaintainData = array("c_pelayanan"=>$_POST['idpelayanan'],
							"n_judul"=>$_POST['title'],
							"n_sumber"=>$_POST['source'],
							"n_detil"=>stripslashes($_POST['content']),
							"d_pelayanan"=>$dates,
							"c_status"=>$_POST['status'],
							"i_entri"=>$userlogin,
							"d_entri"=>date("Y-m-d H:i:s"));
//echo "id=".$_POST['idpelayanan'];							

if ($_POST['title']){	
//echo "action=".$_POST['action'];					
	if ($_POST['action']=='insert')
	
	{
		$hasil = $this->pelayanan_serv->maintainData($MaintainData,'insert');		
		$this->view->pars="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
	}		
	else
	{
		$hasil = $this->pelayanan_serv->maintainData($MaintainData,'update');
		$this->view->pars="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";
		$this->listDataByKey($_POST['idpelayanan']) ;
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
					if (move_uploaded_file($_FILES['a_filex']['tmp_name'], $destDir)) { 
						$lampiran ="file";	
						$this->cropImage($nw, $nh, $destDir, $extention, $destDir);
					}
			}
			}




	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;
	$this->render('pelayanan');							
   }
	  
 

	
}
?>