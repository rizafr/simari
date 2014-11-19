<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Pendaftaranonline_Service.php";

class Sdmmodule_PendaftaranonlineController extends Zend_Controller_Action {

		
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
	public function daftaronlineAction() {
		$i_ktp=$_GET['i_ktp'];
		$this->listDataByKey($i_ktp);

		$this->view->propinsiList = $this->reff_serv->getPropinsiListAll();
		$this->view->kabupatenList = $this->reff_serv->getKabupatenListAll($carikabupaten);	
		$this->view->nmJenjangList = $this->reff_serv->getPendidikan('');
		$this->view->agamaList = $this->reff_serv->getAgamaList('');
	}
     
	public function daftaronlinejsAction() 
	{
		header('content-type : text/javascript');
		$this->render('daftaronlinejs');
	}
	
    public function listcombokabupatenAction() {
	$c_propinsi=$_GET['c_propinsi'];
	$this->view->par=$_GET['target'];
	$carikabupaten=" and c_propinsi ='$c_propinsi' ";
	$this->view->kabupatenList = $this->reff_serv->getKabupatenListAll($carikabupaten); 
    }	
	


	
public function listDataByKey($i_ktp) {  
	$cari = " and i_ktp ='$i_ktp' ";
	$this->view->i_ktp=$i_ktp;
	//$this->view->i_ktp=$datapegawai[0]['i_ktp'];

	$datapegawai=$this->daftarol_serv->getPegawaiListByKtp($cari );
	if (count($datapegawai)!=0){	
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
	$this->view->n_pend_lembaga=$datapegawai[0]['n_pend_lembaga'];
	$this->view->c_pend_akreditasi=$datapegawai[0]['c_pend_akreditasi'];
	$this->view->c_posisi_jabatan=$datapegawai[0]['c_posisi_jabatan'];
	
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
	
	$this->view->par="Ubah";
	$this->view->jdl="Merubah ";		
	}
	else{
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";		
	}

}

public function maintaindataAction() {

$i_telponrumah=null;
if (!$_POST['i_telponrumah1'])
{$i_telponrumah1="000";}
if (!$_POST['i_telponrumah2']){
$i_telponrumah=$i_telponrumah1."-".$_POST['i_telponrumah2'];
}
else{$i_telponrumah=$_POST['i_telponrumah1']."-".$_POST['i_telponrumah2'];}


$d_lahir=null;
if ($_POST['d_lahir']){
	$d_lahir= substr($_POST['d_lahir'],6,4)."-".substr($_POST['d_lahir'],3,2)."-".substr($_POST['d_lahir'],0,2);
}

if ($_POST['d_pend_ijazah'])
	{
		$d_pend_ijazah1=substr($_POST['d_pend_ijazah'],0,2);
		$d_pend_ijazah2=substr($_POST['d_pend_ijazah'],3,2);
		$d_pend_ijazah3=substr($_POST['d_pend_ijazah'],6,4);
	}
	$d_pend_ijazah=$d_pend_ijazah3."-".$d_pend_ijazah2."-".$d_pend_ijazah1;
		
	$i_pend_ipk=$_POST['i_pend_ipk'];
	if (!$d_pend_ijazah1){$d_pend_ijazah=null;}
	if (!$i_pend_ipk){$i_pend_ipk=null;$cektgl=true;}
	else{$cektgl=checkdate($d_pend_ijazah2,$d_pend_ijazah1,$d_pend_ijazah3);}
		
	$MaintainData = array(	"i_ktp"=>$_POST['i_ktp'],
				"n_pendaftar"=>$_POST['n_pendaftar'],
				"c_jeniskelamin"=>$_POST['c_jeniskelamin'],
				"c_agama"=>$_POST['c_agama'],
				"c_golongan_darah"=>$_POST['c_golongan_darah'],
				"c_statusnikah"=>$_POST['c_statusnikah'],
				"n_hobi"=>$_POST['n_hobi'],
				"d_lahir"=>$d_lahir,
				"c_propinsi_lahir"=>$_POST['c_propinsi_lahir'],
				"a_kota_lahir"=>$_POST['a_kota_lahir'],
				"q_tinggibdn"=>$_POST['q_tinggibdn']*1,
				"q_beratbdn"=>$_POST['q_beratbdn']*1,
				"n_rambut"=>$_POST['n_rambut'],
				"n_btkmuka"=>$_POST['n_btkmuka'],
				"n_warnakulit"=>$_POST['n_warnakulit'],
				"n_cirikhas"=>$_POST['n_cirikhas'],
				"a_rumah"=>$_POST['a_rumah'],
				"a_rt"=>$_POST['a_rt'],
				"a_rw"=>$_POST['a_rw'],
				"a_kelurahan"=>$_POST['a_kelurahan'],
				"a_kecamatan"=>$_POST['a_kecamatan'],
				"a_kota"=>$_POST['a_kota'],
				"a_propinsi"=>$_POST['a_propinsi'],
				"a_kodepos"=>$_POST['a_kodepos'],
				"i_telponrumah"=>$i_telponrumah,
				"i_telponhp"=>$_POST['i_telponhp'],
				"c_warganegara"=>$_POST['c_warganegara'],
				"a_email"=>$_POST['a_email'],				
				"c_pend"=>$_POST['c_pend'],
				"d_pend_akhir"=>$_POST['d_pend_akhir'],
				"d_pend_ijazah"=>$d_pend_ijazah,
				"d_pend_mulai"=>$_POST['d_pend_mulai'],
				"i_pend_ipk"=>$i_pend_ipk,
				"i_pend_ijazah"=>$_POST['i_pend_ijazah'],
				"n_pend_jurusan"=>strtoupper($_POST['n_pend_jurusan']),
				"n_pend_lembaga"=>$_POST['n_pend_lembaga'],
				"c_pend_akreditasi"=>$_POST['c_pend_akreditasi'],
				"c_posisi_jabatan"=>$_POST['c_posisi_jabatan'],
				"d_entry"=>date("Y-m-d"));

if ($_POST['i_ktp']){						
	if ($_POST['proses']=='Simpan')
	{
		$hasil = $this->daftarol_serv->maintainData($MaintainData,'insert');		
		$this->view->par="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
		if ($hasil=='sukses'){
			$MaintainData = array("i_ktp"=>$_POST['i_ktp'],"d_tahun"=>date("Y"));		
			$hasil = $this->daftarol_serv->insertNoDaftar($MaintainData,'insert');			
		}
							
		if ($hasil=='sukses'){
			$i_ktp=$_POST['i_ktp'];
			$d_tahun=date("Y");
			$cari=" and i_ktp='$i_ktp' and d_tahun='$d_tahun' ";
			$datanomor=$this->daftarol_serv->getNomorDaftar($cari );
			if (count($datanomor)!=0){
				$datanomor=trim($datanomor[0]['noid']);
				$q_nomor_daftar=$i_ktp."_".$d_tahun."_".$datanomor;
				$MaintainData = array("i_ktp"=>$_POST['i_ktp'],"d_tahun"=>date("Y"),"q_nomor_daftar"=>$q_nomor_daftar );		
				$hasil = $this->daftarol_serv->insertNoDaftar($MaintainData,'update');
			}
			
		?>
			<script>
			cetakDaftar();
			function cetakDaftar()
			{
				url = "<?php echo $this->basePath; ?>/sdmmodule/pendaftaranonline/cetakdaftar?i_ktp=<?=$i_ktp?>&d_tahun=<?=$d_tahun?>&nomor=<?=$q_nomor_daftar?>"	
					BukaWindows('700','500');
			}

			function BukaWindows(wid,heg){
				var w = 0; 
				var h = 0;
				w = screen.availWidth;
				h = screen.availHeight;
				var popW = wid, popH = heg;
				var leftc = (w-popW)/2;
				var topc = (h-popH)/2;
				var selectWindow = window.open(url,'Selection', 'left=' + leftc + ',top=' + topc + ', width='+popW+',height='+popH+',resizable=0,scrollbars=yes,menubar=yes');
			}
			</script>
		<?	
		}		
	}		
	else
	{
		$hasil = $this->daftarol_serv->maintainData($MaintainData,'update');
		$this->view->par="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";		
	}
$this->listDataByKey($_POST['i_ktp']);	
}
else{ $hasil="gagal";}

	$this->view->propinsiList = $this->reff_serv->getPropinsiListAll();
	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;
	$this->render('daftaronline');							
}

public function cetakdaftarAction() {
	$i_ktp=$_GET['i_ktp'];
	$d_tahun=$_GET['d_tahun'];
	$nomor=$_GET['nomor'];
	$this->view->nomordaftar=$nomor;
	
	if(!$d_tahun)
	{
		$d_tahun=date('Y');
		$cari=" and i_ktp='$i_ktp' and d_tahun='$d_tahun' ";
		$datanomor=$this->daftarol_serv->getNomorDaftar($cari );
		$nomor=$datanomor[0]['q_nomor_daftar'];
		$this->view->nomordaftar=$nomor;
	} 

	$cari = " and i_ktp ='$i_ktp' ";
	$this->view->i_ktp=$i_ktp;
	$datapegawai=$this->daftarol_serv->getPegawaiListByKtp($cari );
	if (count($datapegawai)!=0){	
		$this->view->n_pendaftar= $datapegawai[0]['n_pendaftar'];	
		$this->view->c_jeniskelamin=$datapegawai[0]['c_jeniskelamin'];
		$this->view->c_agama=$datapegawai[0]['c_agama'];
		$this->view->c_statusnikah=trim($datapegawai[0]['c_statusnikah']);
		$this->view->d_lahir=$datapegawai[0]['d_lahir'];
		$this->view->a_kota_lahir=trim($datapegawai[0]['a_kota_lahir']);
		$this->view->a_rumah=$datapegawai[0]['a_rumah'];
		$this->view->a_rt=$datapegawai[0]['a_rt'];
		$this->view->a_rw=$datapegawai[0]['a_rw'];
		$this->view->a_kelurahan=$datapegawai[0]['a_kelurahan'];
		$this->view->a_kecamatan=$datapegawai[0]['a_kecamatan'];
		$this->view->a_kota=$datapegawai[0]['a_kota'];
		$this->view->a_propinsi=$datapegawai[0]['a_propinsi'];
		$this->view->a_kodepos=$datapegawai[0]['a_kodepos'];
		$this->view->i_telponrumah=$datapegawai[0]['i_telponrumah'];
		$this->view->i_telponhp=$datapegawai[0]['i_telponhp'];
		$this->view->a_email=$datapegawai[0]['a_email'];
		$this->view->c_pend=$datapegawai[0]['c_pend'];
		$this->view->n_pendidikan=$datapegawai[0]['n_pendidikan'];
		$this->view->n_kota=$datapegawai[0]['n_kota'];
		$this->view->a_propinsi=$datapegawai[0]['a_propinsi'];
		$this->view->n_propinsi=$datapegawai[0]['n_propinsi'];
		
	}	
	
 
}	  

}
?>