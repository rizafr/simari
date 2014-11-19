<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/Sdm_Pegawai_Service.php";
require_once "service/sdm/Sdm_Photo_Service.php";

class Sdmmodule_DataphotoController extends Zend_Controller_Action {

		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu');
		$this->view->photoPath = $registry->get('photoPath');
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->photo_serv = Sdm_Photo_Service::getInstance();
		$this->view->nama= $this->nama;
		$this->view->nip= $this->nip;
		$this->view->golongan= $this->golongan;
		$this->view->pangkat=$this->pangkat;
		$this->view->filephoto=$this->filephoto;
		$this->view->statuspeg=$this->statuspeg;
		$this->view->cpegstatusnikah=$this->cpegstatusnikah;
		$this->view->nipnew= $this->nipnew;
		
		$sespeg = new Zend_Session_Namespace('sespeg');

		$this->view->nama= $sespeg->nama;
		$this->view->nip= $sespeg->nip;
		$this->view->golongan= $sespeg->golongan;
		$this->view->pangkat= $sespeg->pangkat;	
		$this->view->filephoto= $sespeg->filephoto;	
		$this->view->statuspeg= $sespeg->statuspeg;
		$this->view->cpegstatusnikah= $sespeg->cpegstatusnikah;	
		$this->view->nipnew= $sespeg->nipnew;

		$sesmenu = new Zend_Session_Namespace('sesmenu');
		$this->view->menu= $sesmenu->menu;
		
		$ssologin = new Zend_Session_Namespace('ssologin');
		$this->view->userid=$ssologin->userid;
		$this->view->c_izin=$ssologin->c_izin[0]['c_izin'];		
    }
	
    public function indexAction() {
    }
	public function dataphotojsAction() 
	{
		header('content-type : text/javascript');
		$this->render('dataphotojs');
	}	
	

	public function dataphotoAction() {
		$this->view->par='Ubah';
	}
	


	public function hapusdataAction() {
		
	}

	public function maintaindataAction() {
	$max_width = "500";

//simpan file			
		if ($_POST['a_file'])
		{
				$namefile=trim($_POST['i_peg_nip']);
				$FileName_dat = $namefile;
				$fileName = $_FILES['photo']['name'];
				//$extention = substr($fileName, -3, 3);				
				$extention = substr($fileName, strrpos($fileName, '.') + 1);
				$FileName_pdf = $FileName_dat.'.'.$extention;
				$destDir = "$FileName_pdf";	
		}
		else{$destDir="nophoto.jpg";}
///

if ($_POST['del_photo']=="on") {$e_file_photo=null;}
else {$e_file_photo=$destDir;}
	$MaintainData = array("i_peg_nip"=>$_POST['i_peg_nip'],"e_file_photo"=>$e_file_photo);

if ($_POST['i_peg_nip']){						
		$hasil = $this->photo_serv->maintainData($MaintainData,'update');
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";
}
else{ $hasil="gagal";}	

/// simpan file
 		if ($hasil=="sukses"){
			$namefile=trim($_POST['i_peg_nip']);
			$FileName_pdf;
			$fileNamex = $_FILES['photo']['name'];
			//$extentionx = substr($fileNamex, -3, 3);
			$extentionx = substr($fileNamex, strrpos($fileNamex, '.') + 1);
				
		    if (!empty($_FILES['photo'])) 
				{$FileName_pdf = $FileName_dat.'.'.$extentionx;}
				$FileName_dat = $namefile;
				$FileName_pdf = $FileName_dat.'.'.$extentionx;
					
				if (!empty($_FILES['photo'])) 	   {

				   $fileName = $_FILES['photo']['name'];
				   //$extention = substr($fileName, -3, 3);
				   $extention = substr($fileName, strrpos($fileName, '.') + 1);
						$destDir = "../library/data/sdm/photo/$FileName_pdf";
						if (move_uploaded_file($_FILES['photo']['tmp_name'], $destDir)) { 
							$lampiran ="file";
							$this->cropImage(150, 200, $destDir, strtolower($extention), $destDir);
			
						}
				}
			} 

	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;
	
	
	$sespeg = new Zend_Session_Namespace('sespeg');
	$sespeg->filephoto= $e_file_photo;
	$this->view->filephoto=$e_file_photo;
	
	$this->render('dataphoto');							
}

function cropImage($nw, $nh, $source, $stype, $dest) {
 
    $size = getimagesize($source);
    $w = $size[0];
    $h = $size[1];
 
    switch($stype) {
        case 'gif':
        $simg = imagecreatefromgif($source);
        break;
        case 'jpg':
        $simg = imagecreatefromjpeg($source);
        break;
        case 'png':
        $simg = imagecreatefrompng($source);
        break;
    }
 
    $dimg = imagecreatetruecolor($nw, $nh);
 
    $wm = $w/$nw;
    $hm = $h/$nh;
 
    $h_height = $nh/2;
    $w_height = $nw/2;
 
    if($w> $h) {
 
        $adjusted_width = $w / $hm;
        $half_width = $adjusted_width / 2;
        $int_width = $half_width - $w_height;
 
        imagecopyresampled($dimg,$simg,-$int_width,0,0,0,$adjusted_width,$nh,$w,$h);
 
    } elseif(($w <$h) || ($w == $h)) {
 
        $adjusted_height = $h / $wm;
        $half_height = $adjusted_height / 2;
        $int_height = $half_height - $h_height;        
	imagecopyresampled($dimg,$simg,0,-$int_height,0,0,$nw,$adjusted_height,$w,$h);
 
    } else {

        imagecopyresampled($dimg,$simg,0,0,0,0,$nw,$nh,$w,$h);
    }
 
    imagejpeg($dimg,$dest,100);
	chmod($dest, 0777);
}

}
?>