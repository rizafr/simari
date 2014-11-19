<?php
require_once 'Zend/Controller/Action.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Monitoring_Service.php";
class Sdmmodule_MonitoringpejabatController extends Zend_Controller_Action
{
		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
	 	$registry = Zend_Registry::getInstance();
	   	$this->view->basePath = $registry->get('basepath');	
		$this->view->leftMenu = $registry->get('leftMenu'); 
		$this->reff_serv = sdm_refferensi_Service::getInstance();
		$this->pegawai_serv = Sdm_Monitoring_Service::getInstance();
		
		//sesion dari login
		$ssologin = new Zend_Session_Namespace('ssologin');
		$this->view->c_lokasi_unitkerja=$ssologin->c_lokasi_unitkerja;
		$this->view->c_eselon_i=$ssologin->c_eselon_i;	

    }
	public function indexAction()
	{
	}
	public function monitoringpejabatjsAction() 
	{
		header('content-type : text/javascript');
		$this->render('monitoringpejabatjs');
	}
	public function monitoringpejabatAction() 
	{
	   	$c_lokasi_unitkerja=trim($this->view->c_lokasi_unitkerja);
		$c_eselon_i=trim($this->view->c_eselon_i);
		$this->view->ceseloni=$c_eselon_i;
		if ($c_eselon_i=='01'){
			$this->view->lokasiList = $this->reff_serv->getLokasi("");
			if ($c_lokasi_unitkerja=='1'){
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1'and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}
			else{
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}		

		}
		else{

			$this->view->lokasiList = $this->reff_serv->getLokasi(" and c_lokasi='$c_lokasi_unitkerja'");
			if ($c_lokasi_unitkerja=='1'){
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}
			else{
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}			
		}
		$this->view->c_eselon_i=$c_eselon_i;
		$this->view->c_lokasi_unitkerja=$c_lokasi_unitkerja;
	}	
	

	public function viewAction() 
	{
		// $c_eselon_i = $_POST['c_eselon_i'];
		// $c_eselon_ii = $_POST['c_eselon_ii'];
		// $c_eselon_iii = $_POST['c_eselon_iii'];
		// $c_eselon_iv = $_POST['c_eselon_iv'];
		// $c_eselon_v = $_POST['c_eselon_v'];
		// $c_lokasi_unitkerja = $_POST['c_lokasi_unitkerja'];	


		$c_lokasi_unitkerja = $_POST['c_lokasi_unitkerja'];
		if(!$_POST['c_lokasi_unitkerja']){$c_lokasi_unitkerja = $_GET['c_lokasi_unitkerja'];}
		$c_eselon_i = $_POST['c_eselon_i'];
		if(!$_POST['c_eselon_i']){$c_eselon_i = $_GET['c_eselon_i'];}
		$c_eselon_ii = $_POST['c_eselon_ii'];
		if(!$_POST['c_eselon_ii']){$c_eselon_ii = $_GET['c_eselon_ii'];}
		$c_eselon_iii = $_POST['c_eselon_iii'];
		if(!$_POST['c_eselon_iii']){$c_eselon_iii = $_GET['c_eselon_iii'];}
		$c_eselon_iv = $_POST['c_eselon_iv'];
		if(!$_POST['c_eselon_iv']){$c_eselon_iv = $_GET['c_eselon_iv'];}
		$c_eselon_v = $_POST['c_eselon_v'];
		if(!$_POST['c_eselon_v']){$c_eselon_v = $_GET['c_eselon_v'];}		

		
		if ($c_lokasi_unitkerja=='1'){			
			if ($_POST['c_eselon_i']){$c_eselon_ix=$_POST['c_eselon_i'];}
			$n_eselon_i=substr($c_eselon_ix,2,200);
			if ($_POST['c_eselon_ii']){$c_eselon_iix=$_POST['c_eselon_ii'];}
			$n_eselon_ii=substr($c_eselon_iix,3,200);
			if ($_POST['c_eselon_iii']){$c_eselon_iiix=$_POST['c_eselon_iii'];}
			$n_eselon_iii=substr($c_eselon_iiix,2,200);
			if ($_POST['c_eselon_iv']){$c_eselon_ivx=$_POST['c_eselon_iv'];}
			$n_eselon_iv=substr($c_eselon_ivx,2,200);
		}
		else
		{
			if ($_POST['c_eselon_i']){$c_eselon_i=$_POST['c_eselon_i'];}
			if ($c_eselon_i){
				$expesl1 = explode(";",$c_eselon_i);
				$n_eselon_i=$expesl1[1];
			}
			if ($_POST['c_eselon_ii']){$c_eselon_ii=$_POST['c_eselon_ii'];}
			if ($c_eselon_ii){
			$expesl2 = explode(";",$c_eselon_ii);
			$n_eselon_ii=$expesl2[2];
			}
			if ($_POST['c_eselon_iii']){$c_eselon_iii=$_POST['c_eselon_iii'];}
			if ($c_eselon_iii){
			$expesl3 = explode(";",$c_eselon_iii);	
			$n_eselon_iii=$expesl3[3];			
			}
			if ($_POST['c_eselon_iv']){$c_eselon_iv=$_POST['c_eselon_iv'];}
			if ($c_eselon_iv){
			$expesl4 = explode(";",$c_eselon_iv);	
			$n_eselon_iv=$expesl4[1];
			}			
			
		}	
		
if ($c_lokasi_unitkerja=='1'){$n_lokasi_unitkerja='Kantor Pusat MA';}else{$n_lokasi_unitkerja='Pengadilan';}
if ($n_lokasi_unitkerja){$jdlesl=$n_lokasi_unitkerja;}
if ($n_eselon_i){$jdlesl=$n_lokasi_unitkerja."<br>".$n_eselon_i;}
if ($n_eselon_ii){$jdlesl=$n_lokasi_unitkerja."<br>".$n_eselon_ii."<br>".$n_eselon_i;}
if ($n_eselon_iii){$jdlesl=$n_lokasi_unitkerja."<br>".$n_eselon_iii."<br>".$n_eselon_ii."<br>".$n_eselon_i;}
if ($n_eselon_iv){$jdlesl=$n_lokasi_unitkerja."<br>".$n_eselon_iv." ".$n_eselon_iii."<br>".$n_eselon_ii."<br>".$n_eselon_i;}
		
//$html=$html.'	<div style="width: 100%; height: 500px; overflow: auto; padding: 5px">';
$htmlx='<center><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">MONITORING PEJABAT<br>'.strtoupper($jdlesl).'</font></center>';
$this->view->judul=$htmlx;
$html=$html.'		<center>';
$html=$html.' 		<table align="center" border="0" cellspacing="1" cellpadding="2" class="sortable">
				<tr>
					<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">Unitkerja</font></th>
					<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">NIP</font></th>
					<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">Nama Pejabat</font></th>
				</tr>';

if ($c_lokasi_unitkerja=='1') {$lokasi='Mahkamah Agung';}else{$lokasi='Pengadilan';}
if ($c_lokasi_unitkerja){
$html=$html.' 			<tr class="event">
					<td  class="clleft" width="60%" colspan="3"><b><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">'.$lokasi.'</font></td>
				</tr>';

$cari=" and c_lokasi_unitkerja='$c_lokasi_unitkerja' ";
$c_eselon_i=substr($c_eselon_i,0,2);
if ($c_eselon_i){ $cari=$cari." and c_eselon_i='$c_eselon_i' ";}
$c_eselon_ii=substr($c_eselon_ii,0,3);
if ($c_eselon_ii){ $cari=$cari." and c_eselon_ii='$c_eselon_ii' ";}
$c_eselon_iii=substr($c_eselon_iii,0,2);
if ($c_eselon_iii){ $cari=$cari." and c_eselon_iii='$c_eselon_iii' ";}
$c_eselon_iv=substr($c_eselon_iv,0,2);
if ($c_eselon_iv){ $cari=$cari." and c_eselon_i='$c_eselon_iv' ";}

//echo $cari; //-'.$c_eselon_i.'-'.$c_eselon_ii.'-'.$c_eselon_iii.'-'.$c_eselon_iv.' 
echo $cari;
	$unitKerjaList = $this->pegawai_serv->getUnitKerja($cari);
			for ($j = 0; $j < count($unitKerjaList); $j++) 
				{
					$n_unitkerja=$unitKerjaList[$j]['n_unitkerja'];
					$c_eselon_i=$unitKerjaList[$j]['c_eselon_i'];
					$c_eselon_ii=trim($unitKerjaList[$j]['c_eselon_ii']);
					$c_eselon_iii=$unitKerjaList[$j]['c_eselon_iii'];
					$c_eselon_iv=$unitKerjaList[$j]['c_eselon_iv'];
					$n_jabatan=$unitKerjaList[$j]['n_jabatan'];
					$status=$unitKerjaList[$j]['status'];
					
					$n_peg=$unitKerjaList[$j]['n_peg'];
					if ($status){$status="Ada"; $i_peg_nip_new=$unitKerjaList[$j]['i_peg_nip_new'];$n_peg=$unitKerjaList[$j]['n_peg'];}else{$status="Kosong";$i_peg_nip_new="-";$n_peg="-";}
					$no++;	
					if ($j%2==0) {
$html=$html.'			<tr class="event2">';
						} else if ($j%2==1) { 
$html=$html.'			<tr class="event">';
						}
					if ($c_eselon_i!='00' && $c_eselon_ii=='000' && $c_eselon_iii=='00' && $c_eselon_iv=='00'){$b="<b>";}else{$b="";}
					if ($c_eselon_i!='00' && $c_eselon_ii!='000' && $c_eselon_iii=='00' && $c_eselon_iv=='00'){$sp="&nbsp;&nbsp";$b2="<b>";}else{$sp="";$b2="";}
					if ($c_eselon_i!='00' && $c_eselon_ii!='000' && $c_eselon_iii!='00' && $c_eselon_iv=='00'){$sp2="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";}else{$sp2="";}
					if ($c_eselon_i!='00' && $c_eselon_ii!='000' && $c_eselon_iii!='00' && $c_eselon_iv!='00'){$sp3="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";}else{$sp3="";}
$html=$html.'				<td class="clleft">'.$b.''.$b2.''.$sp.''.$sp2.''.$sp3.'
						<font face="Bookman Old Style, Book Antiqua, Garamond" size="2">
						'.$n_unitkerja.'
						</font>
					</td>
					<td class="clleft">
						<font face="Bookman Old Style, Book Antiqua, Garamond" size="2">
						'.$i_peg_nip_new.'
						</font>
					</td>
					<td class="clcenter" >
						<font face="Bookman Old Style, Book Antiqua, Garamond" size="2">
						'.$n_peg.'
						</font>
					</td>
				</tr>';							
				
				}
					
}				




$html=$html.' 				
			</table>
			</center>	
		</div>';

$this->view->view=$html;		
$this->view->view=$html;
	if ($_GET['par']=='exl'){
?>
<script>

	var url = "<?php echo $this->basePath; ?>/sdmmodule/monitoringpejabat/exl?find=<?echo $cari;?>&c_lokasi_unitkerja=<?echo $c_lokasi_unitkerja;?>";
	var wid='1000';
	var heg='600';
	var w = 0; 
	var h = 0;
	w = screen.availWidth;
	h = screen.availHeight;
	var popW = wid, popH = heg;
	var leftc = (w-popW)/2;
	var topc = (h-popH)/2;
	var selectWindow = window.open(url,'Selection', 'left=' + leftc + ',top=' + topc + ', width='+popW+',height='+popH+',resizable=0,scrollbars=yes');	
</script>
<?	
	$this->_helper->viewRenderer('blank');
	}
	else{
	$this->_helper->viewRenderer('view');			
	}
	
}
	


public function exlAction() {

$cari=$_GET['find'];
$cari=str_replace('\\', "", $cari);
	
$c_lokasi_unitkerja=$_GET['c_lokasi_unitkerja'];
	
$html=$html.'	<div style="width: 100%; height: 500px; overflow: auto; padding: 5px">';
$html=$html.'		<center>';
$html=$html.' 		<table align="center" border="1" cellspacing="1" cellpadding="2" class="sortable">
				<tr>
					<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">Unitkerja</font></th>
					<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">NIP</font></th>
					<th><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">Nama Pejabat</font></th>
				</tr>';

if ($c_lokasi_unitkerja=='1') {$lokasi='Mahkamah Agung';}else{$lokasi='Pengadilan';}
if ($c_lokasi_unitkerja){
$html=$html.' 			<tr class="event">
					<td  class="clleft" width="60%" colspan="3"><b><font face="Bookman Old Style, Book Antiqua, Garamond" size="2">'.$lokasi.'</font></td>
				</tr>';


	$unitKerjaList = $this->pegawai_serv->getUnitKerja($cari);
			for ($j = 0; $j < count($unitKerjaList); $j++) 
				{
					$n_unitkerja=$unitKerjaList[$j]['n_unitkerja'];
					$c_eselon_i=$unitKerjaList[$j]['c_eselon_i'];
					$c_eselon_ii=trim($unitKerjaList[$j]['c_eselon_ii']);
					$c_eselon_iii=$unitKerjaList[$j]['c_eselon_iii'];
					$c_eselon_iv=$unitKerjaList[$j]['c_eselon_iv'];
					$n_jabatan=$unitKerjaList[$j]['n_jabatan'];
					$status=$unitKerjaList[$j]['status'];
					
					$n_peg=$unitKerjaList[$j]['n_peg'];
					if ($status){$status="Ada"; $i_peg_nip_new=$unitKerjaList[$j]['i_peg_nip_new'];$n_peg=$unitKerjaList[$j]['n_peg'];}else{$status="Kosong";$i_peg_nip_new="-";$n_peg="-";}
					$no++;	
					if ($j%2==0) {
$html=$html.'			<tr class="event2">';
						} else if ($j%2==1) { 
$html=$html.'			<tr class="event">';
						}
					if ($c_eselon_i!='00' && $c_eselon_ii=='00' && $c_eselon_iii=='00' && $c_eselon_iv=='00'){$b="<b>";}else{$b="";}
					if ($c_eselon_i!='00' && $c_eselon_ii!='00' && $c_eselon_iii=='00' && $c_eselon_iv=='00'){$sp="&nbsp;&nbsp";$b2="<b>";}else{$sp="";$b2="";}
					if ($c_eselon_i!='00' && $c_eselon_ii!='00' && $c_eselon_iii!='00' && $c_eselon_iv=='00'){$sp2="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";}else{$sp2="";}
					if ($c_eselon_i!='00' && $c_eselon_ii!='00' && $c_eselon_iii!='00' && $c_eselon_iv!='00'){$sp3="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";}else{$sp3="";}
$html=$html.'				<td class="clleft">'.$b.''.$b2.''.$sp.''.$sp2.''.$sp3.'
						<font face="Bookman Old Style, Book Antiqua, Garamond" size="2">
						'.$n_unitkerja.'
						</font>
					</td>
					<td class="clleft">
						<font face="Bookman Old Style, Book Antiqua, Garamond" size="2">
						'.$i_peg_nip_new.'
						</font>
					</td>
					<td class="clcenter" >
						<font face="Bookman Old Style, Book Antiqua, Garamond" size="2">
						'.$n_peg.'
						</font>
					</td>
				</tr>';							
				
				}
					
}				




$html=$html.' 				
			</table>
			</center>	
		</div>';
	$this->view->par=$_GET['par'];
	$this->view->view=$html;


}	
	
}

?>