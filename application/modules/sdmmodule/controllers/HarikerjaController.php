<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/Sdm_Absensi_Service.php";
class Sdmmodule_HarikerjaController extends Zend_Controller_Action {
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->leftMenu = $registry->get('leftMenu'); 
		 $this->sdm_absen_serv = Sdm_Absensi_Service::getInstance();
    }
	
public function indexAction() {
	   
}
public function harikerjajsAction() 
{
	header('content-type : text/javascript');
	$this->render('harikerjajs');
}	
public function harikerjaAction() 
{
 	   $thnSekarang = $_REQUEST['thnHariKerja'];
	   $blnSekarang = $_REQUEST['blnHariKerja'];
	   if ($thnSekarang=="") $thnSekarang = date("Y");
	   if ($blnSekarang=="") $blnSekarang = date("m");
		$this->view->thnSekarang = $thnSekarang;
		$this->view->blnSekarang = $blnSekarang;
		$this->view->srviceE = $this->sdm_absen_serv;
	   
		$bulanDesc = array("01"=>"Januari",
                      "02"=>"Februari",
                      "03"=>"Maret",
                      "04"=>"April",
                      "05"=>"Mei",
                      "06"=>"Juni",
                      "07"=>"Juli",
                      "08"=>"Agustus",
                      "09"=>"September",
                      "10"=>"Oktober",
                      "11"=>"November",
                      "12"=>"Desember");
					  
$html="";

$html="	<table class='tbl' cellspacing='1' cellpadding='2' border='0' align='center'>
				<tr>
					<th rowspan='2'>No</th>			
					<th rowspan='2'>Tanggal</th>
					<th rowspan='2'>Hari</th>
					<th colspan='2'>Jam Kerja</th>
					<th colspan='2'>Jam Istirahat</th>		
					<th rowspan='2'>Status</th>
				</tr>
				<tr>
					<th>Mulai</th>			
					<th>Selesai</th>
					<th>mulai</th>
					<th>Selesai</th>
				</tr>";
	   $hari = array("Minggu","Senin","Selasa","Rabu","Kamis","Jum'at","Sabtu");
	   $bulan = array("","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September",
	   "Oktober","November","Desember");
	   $bln=$blnSekarang;
	   $noUrut=0;							
       for ($i=1; $i<32; $i++)
       {
          $tahunE = date("Y", mktime(0, 0, 0, $bln, $i, $thnSekarang));
          $bulanE = date("m", mktime(0, 0, 0, $bln, $i, $thnSekarang));
          $tglE = date("d", mktime(0, 0, 0, $bln, $i, $thnSekarang));
          $hariE = date("w", mktime(0, 0, 0, $bln, $i, $thnSekarang));
		  $mulaiKerja = $this->sdm_absen_serv->cekJamKerja($hariE);
		  if ($bulanE==$bln)
          { 
		     $noUrut++;
		     if ($i%2==0)
			 { 
			    if (($hariE=="0") || ($hariE=="6"))
				{ 
				   $chCkd = "Checked"; 
				   $dSbld = "disabled";
				   $valStatus = "Libur";
			       $html=$html."<tr class='event' id='triHariKe$i' style='color:red'>"; 
				}   
				else
				{
				   $chCkd = ""; 
				   $dSbld = "";
				   $valStatus = "Masuk";
			       $html=$html."<tr class='event' id='triHariKe$i'>"; 
				}
			 }
			 else
			 { 
			    if (($hariE=="0") || ($hariE=="6"))
				{ 
				   $chCkd = "Checked"; 
				   $dSbld = "disabled";
				   $valStatus = "Libur";
			       $html=$html."<tr class='event2' id='triHariKe$i' style='color:red'>"; 
				}   
				else
				{
				   $chCkd = ""; 
				   $dSbld = "";
				   $valStatus = "Masuk";
			       $html=$html."<tr class='event2' id='triHariKe$i'>"; 
				}
			 }
$html=$html."				
					<td class='clcenter'>".$noUrut."</td>			
					<td class='clcenter'>".$tglE." ".$bulanDesc[$bulanE]." ".$tahunE."</td>			
					<td class='clcenter'>".$hari[$hariE]."</td>			
					<td class='clcenter'>";
					$mskMulai = $mulaiKerja['d_jamkerja_mulai'];
					
$html=$html."				<input type='text' id='itMskMulai$i' name='itMskMulai$i' value='".
                    $mskMulai."' style='width:70px' maxlength='8' ".$dSbld.">&nbsp;
					</td>			
					<td class='clcenter'>";
					$mskSelesai = $mulaiKerja['d_jamkerja_selesai'];
$html=$html."				<input type='text' id='itMskSelesai$i' name='itMskSelesai$i' value='".
                    $mskSelesai."' style='width:70px' maxlength='8' ".$dSbld.">&nbsp;
					</td>			
					<td class='clcenter'>";
					$breakMulai = $mulaiKerja['d_jamistrht_mulai'];
$html=$html."				<input type='text' id='itBreakMulai$i' name='itBreakMulai$i' value='".
                    $breakMulai."' style='width:70px' maxlength='8' ".$dSbld.">&nbsp;
					</td>			
					<td class='clcenter'>";
					$breakSelesai = $mulaiKerja['d_jamistrht_selesai'];
$html=$html."				<input type='text' id='itBreakSelesai$i' name='itBreakSelesai$i' value='".
                    $breakSelesai."' style='width:70px' maxlength='8' ".$dSbld.">&nbsp;
					</td>			
					<td align='left'><input type='hidden' id='ihiStatus$i' name='ihiStatus$i' value='".
					$valStatus."'>
					<input type='checkbox' id='iiStatus$i' name='iiStatus$i' noHari='".
					$i."' class='icSetStatus' $chCkd>&nbsp;<span id='siStatusHari$i'>".$valStatus."</span>
					</td>			
				</tr>";
		  }
       }	
	$this->view->html=$html;
}	


}
?>