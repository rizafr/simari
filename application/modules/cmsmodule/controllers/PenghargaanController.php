<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/cms/Cms_penghargaan_Service.php";
require_once 'share/Portalconf.php'; 

class Cmsmodule_penghargaanController extends Zend_Controller_Action {

		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		//$this->view->photoPath = $registry->get('photoPath');
		 
		$this->penghargaan_serv = Cms_penghargaan_Service::getInstance();
		$this->view->idpenghargaan= $this->idpenghargaan;
		$this->view->jdlpenghargaan= $this->jdlpenghargaan;
		$this->view->tglpenghargaan= $this->tglpenghargaan;
		$this->view->detilpenghargaan= $this->detilpenghargaan;
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
public function penghargaanjsAction() 
{
	header('content-type : text/javascript');
	$this->render('penghargaanjs');
}	
	

public function listpenghargaanAction() {    
    
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
	else $orderBy=" order by d_penghargaan desc";
	$this->view->orderBy=$_GET['orderBy'];
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totalPenghargaanList = $this->penghargaan_serv->getPenghargaanList($cari, 0, 0 ,$orderBy);		
		$this->view->PenghargaanList = $this->penghargaan_serv->getPenghargaanList($cari, $currentPage, $numToDisplay,$orderBy );	 
    }
	
public function cmspenghargaanAction() {    
    
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
	else $orderBy=" order by d_penghargaan desc";
	$this->view->orderBy=$_GET['orderBy'];
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totalPenghargaanList = $this->penghargaan_serv->getPenghargaanList($cari, 0, 0 ,$orderBy);		
		$this->view->PenghargaanList = $this->penghargaan_serv->getPenghargaanList($cari, $currentPage, $numToDisplay,$orderBy );	 
    }
	

	
public function penghargaanAction() {
	$par=$_GET['par'];
	if ($par=='insert'){
		$this->view->par="insert";
		$this->view->pars="Simpan";
		$this->view->jdl="Menambah ";
		//$this->view->maxid=$this->penghargaan_serv->getMaxId();
	}
	else{
		$this->view->par="update";
		$this->view->pars="Ubah";
		$this->view->jdl="Merubah ";
		$idpenghargaan=$_GET['idpenghargaan'];
		if (!$idpenghargaan){$idpenghargaan=$this->view->idpenghargaan;}
		$this->listDataByKey($idpenghargaan);
	}	
}
public function listDataByKey($idpenghargaan) { 
	$penghargaan=$this->penghargaan_serv->findPenghargaanByKey($idpenghargaan );
	$this->view->idpenghargaan= $penghargaan[0]['c_penghargaan'];
	$this->view->jdlpenghargaan= $penghargaan[0]['n_judul'];
	$this->view->detilpenghargaan= $penghargaan[0]['n_detil'];
	$this->view->tglpenghargaan= $penghargaan[0]['d_penghargaan'];	
	$this->view->status= $penghargaan[0]['c_status'];	
	$this->view->sumber= $penghargaan[0]['n_sumber'];	
	$this->view->ientri=$penghargaan[0]['i_entri'];
	$this->view->dentri=$penghargaan[0]['d_entri'];

}	
public function penghargaandetilAction() {  
		$idpenghargaan=$_GET['idpenghargaan'];
		if (!$idpenghargaan){$idpenghargaan=$this->view->idpenghargaan;}
		$this->listDataByKey($idpenghargaan);
		var_dump($this->listDataByKey($idpenghargaan));
}

public function hapusdataAction() {
 	$idpenghargaan=$_GET['idpenghargaan'];
	$userlogin=$this->view->userid;
	$hasil = $this->penghargaan_serv->maintainHapusData($idpenghargaan,$userlogin);

	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totalPenghargaanList = $this->penghargaan_serv->getPenghargaanList($cari, 0, 0 ,$orderBy);		
		$this->view->PenghargaanList = $this->penghargaan_serv->getPenghargaanList($cari, $currentPage, $numToDisplay,$orderBy );	
	
	$pesan="Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;		
	$this->render('listpenghargaan');
}

public function maintaindataAction() {

$h=$_POST['jam'];
$i=$_POST['mnt'];
$date=$_POST['date'];
$datex=reformatDate($date);
$dates=$datex." ".$h.":".$i ;
$userlogin=$this->view->userid;

	$MaintainData = array("c_penghargaan"=>$_POST['idpenghargaan'],
							"n_judul"=>$_POST['title'],
							"n_sumber"=>$_POST['source'],
							"n_detil"=>stripslashes($_POST['content']),
							"d_penghargaan"=>$dates,
							"c_status"=>$_POST['status'],
							"i_entri"=>$userlogin,
							"d_entri"=>date("Y-m-d H:i:s"));
//echo "id=".$_POST['idpenghargaan'];							

if ($_POST['title']){	
//echo "action=".$_POST['action'];					
	if ($_POST['action']=='insert')
	
	{
		$hasil = $this->penghargaan_serv->maintainData($MaintainData,'insert');		
		$this->view->pars="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
	}		
	else
	{
		$hasil = $this->penghargaan_serv->maintainData($MaintainData,'update');
		$this->view->pars="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";
		$this->listDataByKey($_POST['idpenghargaan']) ;
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
	$this->render('penghargaan');							
   }
	  
 

	
}
?>