<?php
require_once 'Zend/Controller/Action.php';
require_once "service/cms/Cms_Berita_Service.php";
require_once "service/cms/Cms_pengumuman_Service.php";
require_once "service/cms/Cms_agenda_Service.php";
require_once "service/cms/Cms_kategoriprodhukum.php";
require_once "service/cms/Cms_produkhukum.php";

require_once "service/sdm/Sdm_Dashboard_Service.php";
require_once "service/sdm/Sdm_Statistik_Service.php";
require_once "service/sdm/Sdm_Monitoring_Service.php";

require_once "service/portal/Portal_Shoutbox_Service.php";
require_once "service/portal/Portal_Useronline_Service.php";

require_once "service/adm/Adm_Adminuser_Service.php";
require_once "service/adm/Adm_Admaplikasi_Service.php";

require_once "share/ldap.lib.php";


require_once "service/siap/Siap_Perkarakasasi_Service.php";

class MainController extends Zend_Controller_Action
{

    public function init()
    {
		$this->_helper->layout->setLayout('main-layout');	
		$registry = Zend_Registry::getInstance();
		$this->auth = Zend_Auth::getInstance();
		$this->view->basePath = $registry->get('basepath');
		$this->view->baseData = $registry->get('baseData');

		 $this->dasboard_serv = Sdm_Dashboard_Service::getInstance();
		 $this->statistik_serv = Sdm_Statistik_Service::getInstance();
		 $this->monitoring_serv = Sdm_Monitoring_Service::getInstance();
		 $this->perkarakasasi_serv = Siap_Perkarakasasi_Service::getInstance();
		
		$this->berita_serv = Cms_Berita_Service::getInstance();
		$this->view->idberita= $this->idberita;
		$this->view->jdlberita= $this->jdlberita;
		$this->view->detilberita= $this->detilberita;

		$this->pengumuman_serv = Cms_pengumuman_Service::getInstance();
		$this->view->idpengumuman= $this->idpengumuman;
		$this->view->jdlpengumuman= $this->jdlpengumuman;
		$this->view->detilpengumuman= $this->detilpengumuman;

		$this->agenda_serv = Cms_agenda_Service::getInstance();
		$this->view->idagenda= $this->idagenda;
		$this->view->jdlagenda= $this->jdlagenda;
		$this->view->detilagenda= $this->detilagenda;
		$this->view->tempat= $this->tempat;

		$this->shoutbox_serv = Portal_shoutbox_Service::getInstance();
		$this->view->id= $this->id;
		$this->view->n_userid= $this->n_userid;
		$this->view->n_name= $this->n_name;
		$this->view->n_message= $this->n_message;

		$this->kategoriprodukhukum_serv = Cms_kategoriprodhukum_Service::getInstance();
		// $this->view->idkategoriprodukhukum= $this->idkategoriprodukhukum;
		// $this->view->jdlkategoriprodukhukum= $this->jdlkategoriprodukhukum;

		$this->produkhukum_serv = Cms_produkhukum_Service::getInstance();
		$this->view->userid= $this->userid;
		$this->view->i_ym= $this->i_ym;

		$this->adminuser_serv = Adm_Adminuser_Service::getInstance();
		$this->admaplikasi_serv = Adm_Admaplikasi_Service::getInstance();
		$this->useronline_serv = Portal_Useronline_Service::getInstance();
	
		$ssologin = new Zend_Session_Namespace('ssologin');
		$this->ldap_service = new ldap_services();
		
		if ($ssologin->userid && $ssologin->n_peg){
			$this->userid  			= $ssologin->userid;
			$this->password			= $ssologin->password;
			$this->i_peg_nip  		= $ssologin->i_peg_nip;
			$this->i_peg_nip_new	= $ssologin->i_peg_nip_new;
			$this->n_peg  			= $ssologin->n_peg;
			$this->n_peg_gelardepan = $ssologin->n_peg_gelardepan;
			$this->n_peg_gelarblkg 	= $ssologin->n_peg_gelarblkg;
			$this->c_jabatan 		= $ssologin->c_jabatan;
			$this->c_eselon 		= $ssologin->c_eselon;
			$this->c_eselon_i 		= $ssologin->c_eselon_i;
			$this->c_eselon_ii 		= $ssologin->c_eselon_ii;
			$this->c_eselon_iii 	= $ssologin->c_eselon_iii;
			$this->c_eselon_iv 		= $ssologin->c_eselon_iv;
			$this->c_eselon_v 		= $ssologin->c_eselon_v; 
			$this->c_lokasi_unitkerja = $ssologin->c_lokasi_unitkerja; 
			$this->c_satker			= $ssologin->c_satker; 
			$this->n_satker			= $ssologin->n_satker; 
			$this->c_izin			= $ssologin->view->userIzin; 
			$this->checkotoritas	= $ssologin->view->checkotoritas;
			$this->id_bpp			= $ssologin->id_bpp; 
			$this->c_parent			= $ssologin->c_parent; 
			$this->c_child			= $ssologin->c_child; 
			$this->c_izin			= $ssologin->userIzin; 
			$this->checkotoritas	= $ssologin->checkotoritas; 
			$this->wewenang			= $ssologin->wewenang;
			$this->sektoral			= $ssologin->sektoral; 
			$this->n_eselon_i 		= $ssologin->n_eselon_i;
			$this->n_eselon_ii 		= $ssologin->n_eselon_ii;
			$this->n_eselon_iii 	= $ssologin->n_eselon_iii;
			$this->n_eselon_iv 		= $ssologin->n_eselon_iv;
			$this->n_eselon_v 		= $ssologin->n_eselon_v; 
		
		}
    }

    public function indexAction()
    {
		Zend_Session::namespaceUnset('ssologin');	
		//Zend_Session::destroy(true);
		
        // action body
		
		$userid = $_POST['username'];
		$password = $_POST['password'];
		
		$this->view->userid = $userid;
		$this->view->password = $password;
		
		if($this->userid){ $userid = $this->userid;}
		if($this->password){ $password = $this->password;}
		
		$dataMasukan = array("userid" 	=> $userid,
							 "password" => $password);

		//check login ke database
		//====================
    		//$this->view->checklogin = $this->adminuser_serv->checkLogin($dataMasukan);
		
		//check login ke ldap
		//=================
		$this->view->checklogin = $this->ldap_service->koneksiLdap($dataMasukan);
		
		//echo "test $testldap";
		
		if($this->view->checklogin){
			$this->_helper->layout->setLayout('main-layout');	
			$this->view->detailUser = $this->adminuser_serv->getDetailUser($dataMasukan);
			/*
			$this->view->UserWewenang	= $this->adminuser_serv->getUserWewenang($dataMasukan);
			for($x=0; $x<count($this->view->UserWewenang); $x++){
				$userIzin[$x]	= array('c_izin' => $this->view->UserWewenang[$x]['c_izin'],
										'n_izin' => $this->view->UserWewenang[$x]['n_izin']);
			}
			
			$this->view->userIzin = $userIzin;
			*/
			// Check otoritas ke aplikasi
			//----------------------------------
			//$this->view->checkotoritas = $this->adminuser_serv->getOtoritasAplikasi($dataMasukan);
			$arraycheckotoritas = $this->adminuser_serv->getOtoritasAplikasi($dataMasukan);
			
			$k=0;
			foreach ($arraycheckotoritas as $key => $val){
				$i_aplikasi=trim($val['i_aplikasi']);
				$c_wewenang=trim($val['c_wewenang']);
				$c_sektoral=trim($val['c_sektoral']);
				$checkotoritas[$k] = $i_aplikasi;
				$k++;
				//if($c_wewenang && $c_sektoral){
					$arrayc_wewenang[$i_aplikasi] = $c_wewenang;
					$arrayc_sektoral[$i_aplikasi] = $c_sektoral;
				//}
			}
			
			$this->view->checkotoritas = $checkotoritas;
			$this->view->xxx = $userid;
			
			//Setting session
			//-------------------
			$ssologin = new Zend_Session_Namespace('ssologin');
			
			$ssologin->userid				= $this->view->detailUser['userid'];	
			$ssologin->password     		= $this->view->detailUser['n_password'];
			$ssologin->i_peg_nip     		= $this->view->detailUser['i_peg_nip'];
			$ssologin->i_peg_nip_new   		= $this->view->detailUser['i_peg_nip_new'];
			$ssologin->n_peg     			= $this->view->detailUser['n_peg'];
			$ssologin->n_peg_gelardepan     = $this->view->detailUser['n_peg_gelardepan'];
			$ssologin->n_peg_gelarblkg 		= $this->view->detailUser['n_peg_gelarblkg'];
			$ssologin->c_jabatan 			= $this->view->detailUser['c_jabatan']; 
			$ssologin->c_eselon 			= $this->view->detailUser['c_eselon'];
			$ssologin->c_eselon_i 			= $this->view->detailUser['c_eselon_i'];
			$ssologin->c_eselon_ii 			= $this->view->detailUser['c_eselon_ii'];
			$ssologin->c_eselon_iii 		= $this->view->detailUser['c_eselon_iii'];
			$ssologin->c_eselon_iv 			= $this->view->detailUser['c_eselon_iv'];
			$ssologin->c_eselon_v 			= $this->view->detailUser['c_eselon_v'];
			$ssologin->c_lokasi_unitkerja 	= $this->view->detailUser['c_lokasi_unitkerja']; 
			$ssologin->c_satker			 	= $this->view->detailUser['c_satker']; 
			$ssologin->n_satker			 	= $this->view->detailUser['n_satker']; 
			$ssologin->id_bpp			 	= $this->view->detailUser['id_bpp'];			
			$ssologin->c_parent			 	= $this->view->detailUser['c_parent'];			
			$ssologin->c_child			 	= $this->view->detailUser['c_child'];		
			$ssologin->c_izin			 	= $this->view->userIzin; 
			$ssologin->checkotoritas		= $this->view->checkotoritas; 
			$ssologin->arrayc_sektoral		= $arrayc_sektoral; 
			$ssologin->arrayc_wewenang		= $arrayc_wewenang; 
			//$ssologin->wewenang				= $this->view->UserWewenang[0]['c_wewenang'];
			//$ssologin->sektoral				= $this->view->UserWewenang[0]['c_sektoral'];
			$ssologin->n_eselon_i 			= $this->view->detailUser['n_eselon_i'];
			$ssologin->n_eselon_ii 			= $this->view->detailUser['n_eselon_ii'];
			$ssologin->n_eselon_iii 		= $this->view->detailUser['n_eselon_iii'];
			$ssologin->n_eselon_iv 			= $this->view->detailUser['n_eselon_iv'];
			$ssologin->n_eselon_v 			= $this->view->detailUser['n_eselon_v'];
			
			/* $this->view->xx = $this->view->UserWewenang[0]['c_wewenang'];//$ssologin->c_wewenang;
			$this->view->yy = $this->view->UserWewenang[0]['c_sektoral'];//$ssologin->c_sektoral;
			 */
			$this->view->xxx = $ssologin->n_eselon_i;
			$this->view->yyy = $ssologin->n_eselon_ii;
			$this->view->aaa = "iii".$ssologin->n_eselon_iii;
			$this->view->bbb = $ssologin->n_eselon_iv;
			$this->view->ccc = $ssologin->n_eselon_v;
			
			$this->view->dd = $ssologin->c_eselon_i;
			$this->view->ee = $ssologin->c_eselon_ii;
			$this->view->ff = $ssologin->c_eselon_iii;
			$this->view->gg = $ssologin->c_eselon_iv;
			$this->view->hh = $ssologin->c_eselon_v;
			
			//$this->view->wewenang = $ssologin->wewenang;
			//$this->view->sektoral = $ssologin->sektoral;
			
			$this->view->ssologin = $ssologin; 
			
			$this->view->c_jabatan 		= $this->view->detailUser['c_jabatan']; 
			$this->view->c_eselon_i 	= $this->view->detailUser['c_eselon_i'];
			$this->view->c_eselon_ii 	= $this->view->detailUser['c_eselon_ii'];
			
			//tambah session otoritas user ke aplikasi
			$ssologin->checkotoritas	 	= $this->view->checkotoritas;
			
			
			// ambil list aplikasi
			//----------------------------------
			$dataMasukanAplikasiList = array("pageNumber" => 99,
											"itemPerPage" => 99,
											"kategoriCari" => "semua",
											"katakunciCari" => "",
											"sortBy" => "i_urut_aplikasi",
											"sortOrder" => "asc");
			$this->view->aplikasiList = $this->admaplikasi_serv->aplikasiList($dataMasukanAplikasiList);

			//hapus log akses aplikasi
			//------------------------------
			
			$this->view->deleteLogAksesaplikasi = $this->admaplikasi_serv->deleteLogAksesaplikasi($dataMasukan);
			
			// action body
			/*insert useronline*/
			$ip=$_SERVER[REMOTE_ADDR];
			//echo $ip;
			$id=session_id();
			$DataUseronline = array(
								"id"=>$id,
								"userid"=>$userid,
								"ip"=>$ip,
								"tm"=>date("Y-m-d H:i:s"),
								"status"=>'OFF');
						
			$hasiladdol = $this->useronline_serv->maintainDatainsertOl($DataUseronline);

			////// To update session status for tmuseronline table to get who is online ////////
			//if(isset(session_id())){
				$tm=date("Y-m-d H:i:s");
				$DataUseronlineUpdate = array(
					"id"=>session_id(),
					"tm"=>$tm,
					"status"=>'ON');
				$hasilupdateol = $this->useronline_serv->maintainDataupdateOl($DataUseronlineUpdate);
				//$sqlquery="update plus_login set status='ON',tm='$tm' where id='$session[id]'";
				//echo $sqlquery;
				//$q=mysql_query($sqlquery);
			//}
			// Find out who is online /////////
			$gap=1; // change this to change the time in minutes, This is the time for which active users are collected. 
			$tm=date ("Y-m-d H:i:s", mktime (date("H"),date("i")-$gap,date("s"),date("m"),date("d"),date("Y")));
			$this->view->jmluserol = $this->useronline_serv->getUseronlineSum($tm);
			
			$this->view->useronlinelist = $this->useronline_serv->getUseronlineList($tm);

			/*Notifikasi*/
			//Notifikasi SDM
			$carix="
and ((c_eselon in('01','02','03') and c_golongan in('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17') 
or (c_eselon = '04' and c_golongan in('04','05','06','07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_eselon = '03' and c_golongan in('02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_eselon = '05' and c_golongan in('04','05','06','07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_eselon = '06' and c_golongan in('05','06','07','08','09','10','11','12','13','14','15','16','17') ) 
or ((c_eselon = '07' or c_eselon = '08') and c_golongan in('05','06','07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_eselon = '15' and ( (c_pend = '29' and c_golongan in('12','13','14','15','16','17') ) 
or (c_pend = '41' and c_golongan in('11','12','13','14','15','16','17') ) 
or (c_pend = '40' and c_golongan in('08','09','10','11','12','13','14','15','16','17') ) 
or ((c_pend = '04' or c_pend = '05') and c_golongan in('08','09','10','11','12','13','14','15','16','17') ) 
or (c_pend = '32' and c_golongan in('07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_pend = '07' and c_golongan in('06','07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_pend = '36' and c_golongan in('06','07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_pend = '09' and c_golongan in('05','06','07','08','09','10','11','12','13','14','15','16','17') ) 
or (c_pend = '10' and c_golongan in('04','05','06','07','08','09','10','11','12','13','14','15','16','17') ) ) ) ))";
	//to_char(d_tmt_golongan,'yyyy-mm-dd') like '2008-10%' 
	$datestart =  date('Y-m').'-01';
	if(date('m') + 6 > 12){
		$mdate = (date('m') + 6) % 12;
		$mday 	= '0'.$mdate;
		$yday	= date('Y')+1;
	} else{
		$mdate = date('m') + 6;
		$mday 	= strlen($mdate == 1) ? '0'.$mdate : $mdate;
		$yday	= date('Y');
	}
	$dateend = $yday.'-'.$mday.'-01';
	$dateendold 	= ($yday-4).'-'.$mday.'-01';
	$datestartold 	= (date('Y')-4).'-'.date('m').'-01';
	$sqldate = " and d_tmt_golongan between '$datestartold' and '$dateendold'";
	$carigol= $carix.$sqldate;
			$thnpen=date('Y')+1;
			$tgla="$thnpen-01-01";
			$caripens= " and c_eselon !='17' and (EXTRACT(years from AGE('$tgla', d_peg_lahir))= q_usia_pensiun)";
			$this->view->remindPensiun=$this->monitoring_serv->getJmlDataPeg($caripens);
			//$carigol= " and (EXTRACT(months from AGE(now(), d_tmt_golongan))= 6)";
			$this->view->remindGolPangkat=$this->monitoring_serv->getJmlDataPeg($carigol);	
			$cariKgb= " and (EXTRACT(years from AGE(now(), d_tmt_kgb))= 6)";	
			$this->view->remindKgb=$this->monitoring_serv->getJmlDataPeg($cariKgb);				
			
			/*CHART DEFAULT*/
		
		$cari="";		
		$jml25 = $this->statistik_serv->getCountData(" $cari and (EXTRACT(years from AGE(now(), d_peg_lahir))<= 25)  ");
		$jml2635 = $this->statistik_serv->getCountData("$cari and ((EXTRACT(years from AGE(now(), d_peg_lahir))>= 26) and (EXTRACT(years from AGE(now(), d_peg_lahir))<= 35))  ");
		$jml3645 = $this->statistik_serv->getCountData("$cari and ((EXTRACT(years from AGE(now(), d_peg_lahir))>= 36) and (EXTRACT(years from AGE(now(), d_peg_lahir))<= 45))  ");
		$jml4655 = $this->statistik_serv->getCountData("$cari and ((EXTRACT(years from AGE(now(), d_peg_lahir))>= 46) and (EXTRACT(years from AGE(now(), d_peg_lahir))<= 55))  ");
		$jml56 = $this->statistik_serv->getCountData("$cari and (EXTRACT(years from AGE(now(), d_peg_lahir))>= 56)  ");

		$this->view->jml25=$jml25;
		$this->view->jml2635=$jml2635;
		$this->view->jml3645=$jml3645;
		$this->view->jml4655=$jml4655;
		$this->view->jml56=$jml56;
		$this->view->chartjmlpeg=$this->toChartAge('Komposisi Usia',$jml25,$jml2635,$jml3645,$jml4655,$jml56);
		
	


			
			$cari = "";
			$this->view->beritaPubList = $this->berita_serv->getBeritaPubList($cari);	
			
			$cari2 = "";
			$this->view->pengumumanPubList = $this->pengumuman_serv->getpengumumanPubList($cari2);	
			$userii=$this->view->userid;
			$tglsek=date('Ymd');
			$cari3 = " and (i_nip='$this->i_peg_nip' or b.i_entri='$userii')  and to_char(d_agenda,'yyyymmdd') >= '$tglsek' ";
			$this->view->agendaPubList = $this->agenda_serv->getagendaPubList($cari3);	

			$cari4 = "";
			$this->view->kategoriprodukhukumPubList = $this->kategoriprodukhukum_serv->getkategoriprodukhukumPubList($cari4);	

			$cari5 = "";
			$this->view->produkhukumPubList = $this->produkhukum_serv->getprodukhukumTenPubList($cari5);	

			$this->view->shoutboxList = $this->shoutbox_serv->getshoutboxList();	
			
			//$shoutboxList = $this->shoutbox_serv->getshoutboxList();
			//echo "xxxxx".$shoutboxList[0]['n_nama'];
			/*$cari = " where c_status=1 ";
			$currentPage=1;
			$numToDisplay=10;
			$orderby=" orderby d_berita desc"; 
			$this->view->beritaPubList = $this->berita_serv->getBeritaList($cari,$currentPage, $numToDisplay,$orderby);	
			*/
		/** SIAP **/
				$tahunberjalan	= date('Y');
				$thn10yglalu	= $tahunberjalan - 10;
				$thn2yglalu	= $tahunberjalan - 2;
				$this->view->tahunberjalan = $tahunberjalan;
				$wheremasuk = " no_registrasi_tahun='".$tahunberjalan."' ";
				#Perkara putus : tgl_ki
				#$whereputus = " no_registrasi_tahun='".$tahunberjalan."' and (tgl_putusan_kasasi is NOT NULL or tgl_putusan_grasi is not null or tgl_putusan_pk is not null or (hum_tentang is NOT NULL AND hum_terhadap is NOT NULL))
				#AND no_surat_pengantar_pengadilan_pengaju IS NOT NULL";

				#HUM tanggal putusannya diinput di tgl_putusan_kasasi
				$whereputus = "year(tgl_putusan_kasasi)= year(now()) or year(tgl_putusan_pk) = year(now()) or year(tgl_putusan_grasi) = year(now())";
				#or year(tgl_putusan_grasi)= year(now()) or year(tgl_putusan_pk) = year(now()) or (hum_tentang is NOT NULL AND hum_terhadap is NOT NULL));
					#AND no_surat_pengantar_pengadilan_pengaju IS NOT NULL";
				#$whereputus = " no_registrasi_tahun='".$tahunberjalan."' and (tgl_putusan_kasasi is NOT NULL or tgl_putusan_grasi is not null or tgl_putusan_pk is not null or (hum_tentang is NOT NULL AND hum_terhadap is NOT NULL))
				#	AND no_surat_pengantar_pengadilan_pengaju IS NOT NULL";
					
					
				#AND no_surat_pengantar_pengadilan_pengaju IS NOT NULL";
				$this->view->perkaramasuk = $this->perkarakasasi_serv->getDataPerkara($wheremasuk);
				$this->view->perkaraputus = $this->perkarakasasi_serv->getDataPerkara($whereputus);
				$whereminutasi1 = " no_registrasi_tahun='".$tahunberjalan."' 
				and (
					(tgl_putusan_kasasi is NOT NULL AND tgl_kirim_ke_pengadilan_pengaju is NULL)
					or (tgl_putusan_grasi is NOT NULL AND tgl_kirim_ke_pengadilan_pengaju is NULL)
					or (tgl_putusan_pk is NOT NULL AND tgl_kirim_ke_pengadilan_pengaju is NULL)
				 #or (tun_pjk_kode is NOT NULL AND tgl_kirim_ke_pengadilan_pengaju is NULL)
				 #or (hum_tentang is NOT NULL AND hum_terhadap is NOT NULL AND tgl_kirim_ke_pengadilan_pengaju is NULL)
				)";
				#( TIMESTAMPDIFF(DAY,SYSDATE(),tgl_perkara_masuk) > 365 and TIMESTAMPDIFF(DAY,SYSDATE(),tgl_perkara_masuk) < 3650)";
					
				$wheretunggak = " no_registrasi_tahun is not null and 
					( TIMESTAMPDIFF(DAY,tgl_perkara_masuk,SYSDATE()) > 365 and TIMESTAMPDIFF(DAY,tgl_perkara_masuk,SYSDATE()) < 3650)
					and
					(
					( tgl_putusan_kasasi is null)
					or ( tgl_putusan_grasi is  NULL)
					or ( tgl_putusan_pk is  NULL)
					or (tun_pjk_kode is  NULL)
					or (hum_tentang is NULL and hum_terhadap is null)
					)";
				$this->view->perkaraminutasi = $this->perkarakasasi_serv->getDataPerkara($whereminutasi1);
				$this->view->perkaratunggak = $this->perkarakasasi_serv->getDataPerkara($wheretunggak);
			
					
		//////////////////////////////
		} else {
			Zend_Session::namespaceUnset('ssologin');	
			$this->_helper->layout->setLayout('login-layout');	
			$this->view->pesan = "Userid Atau Password Salah.";
		}
		
		
		
		
        
    }
/*Action for save mesg from shoutbox*/
	public function prosesAction() {
		$MaintainData = array(
							"n_userid"=>$_GET['userid'],
							"n_name"=>$_GET['name'],
							"n_message"=>$_GET['message'],
							"d_entri"=>date("Y-m-d H:i:s"));
		$hasil = $this->shoutbox_serv->maintainData($MaintainData);
		$this->view->shoutboxList = $this->shoutbox_serv->getshoutboxList();	
		$this->render('showdata');		
		}	
	public function proses2Action() {
		$MaintainData = array(
							"n_userid"=>$_POST['userid'],
							"n_name"=>$_POST['name'],
							"n_message"=>$_POST['message'],
							"d_entri"=>date("Y-m-d H:i:s"));
		$hasil = $this->shoutbox_serv->maintainData($MaintainData);
		//$this->render('showdata');		
		$this->_redirect('showdata');
		//$this->render();
		}	
	public function showdataAction() {
		$this->view->shoutboxList = $this->shoutbox_serv->getshoutboxList();
		//$this->render();		
		//$this->_redirect('showdata');
		}	
	
function toChartEdu($caption,$jmlsd,$jmlsmp,$jmlsma,$jmld1,$jmld2,$jmld3,$jmls1,$jmls2,$jmls3){
				require_once "../library/fusionchart/FusionCharts.php";
				$fusionCharts = new fusionChart();
				$w='100%'; 
				$h='100%';
		$strXML = "<chart caption='$caption' xAxisName='Month' yAxisName='Units' showValues='0' formatNumberScale='0' showBorder='3'
		bgColor='999999,FFFFFF' bgAlpha='10' palette='2' animation='1' numberPrefix='$' pieSliceDepth='10' startingAngle='100' 
		legendPosition='left' showLegend='1' baseFont='Arial' baseFontSize ='10' baseFontColor ='000000' >";
			
			if ($jmlsd){$strXML .= "<set label=' SD' value='$jmlsd'  Color='06a7da' /> ";}			
			if ($jmlsmp){$strXML .= "<set label='SMP' value='$jmlsmp'   Color='8AB9F9' />";}  
			if ($jmlsma){$strXML .= "<set label='SMA' value='$jmlsma'  Color='62A2FA' />";}
			if ($jmld1){$strXML .= "<set label='D1' value='$jmld1'   Color='398AF9' />";}
			if ($jmld2){$strXML .= "<set label='D2' value='$jmld2'   Color='0C70F9' />";}
			if ($jmld3){$strXML .= "<set label='D3' value='$jmld3'   Color='5285CA' />";}
			if ($jmls1){$strXML .= "<set label='S1' value='$jmls1'   Color='2B6ECA' />";}
			if ($jmls2){$strXML .= "<set label='S2' value='$jmls2'   Color='095BCA' />";}
			if ($jmls3){$strXML .= "<set label='S3' value='$jmls3'   Color='074AA5' />";}
			
		$strXML .="<styles>
				      <definition>
					<style name='myCaptionFont' type='font' font='Arial' size='14' color='666666' bold='2' underline='1'/>
					<style name='myShadow' type='Shadow' color='999999' angle='45'/>
					<style name='myGlow' type='Glow' color='FF5904'/>
					<style name='myAnim' type='animation' param='_alpha' start='0' duration='1'/>	  
				      </definition>	
					<application>
						<apply toObject='CAPTION' styles='myCaptionFont,myShadow'/>
						 <apply toObject='Legend' styles='myAnim' />
						 <apply toObject='XAxisName' styles='myGlow' />
						<apply toObject='YAxisName' styles='myGlow' />
					</application>
				  </styles>";
				  
		$strXML .= "</chart>";
		$alm=$this->basePath."/charts/Pie3D.swf";
		$grafik = 'Chart';			
		$findchartdir = $fusionCharts->renderChartHTML($alm, '', $strXML, $grafik, 430, 320, false);
		$getlistdir=$findchartdir;
		$getlistdir=$getlistdir;
		return $getlistdir; 
	}	
	function toChartAge($caption,$jml25,$jml2635,$jml3645,$jml4655,$jml56){
				require_once "../library/fusionchart/FusionCharts.php";
				$fusionCharts = new fusionChart();
				$w='80%'; 
				$h='100%';
		$strXML = "<chart caption='$caption' xAxisName='Month' yAxisName='Units' showValues='0' formatNumberScale='0' showBorder='3'
		bgColor='999999,FFFFFF' bgAlpha='10' palette='2' animation='1' numberPrefix='$' pieSliceDepth='10' startingAngle='100' 
		legendPosition='left' showLegend='1' baseFont='Arial' baseFontSize ='10' baseFontColor ='000000' >";
			
			if ($jml25){$strXML .= "<set label=' 25 thn' value='$jml25'  Color='FF0000'/> ";}			
			if ($jml2635){$strXML .= "<set label='26-35 thn' value='$jml2635'   Color='000066'/>";}  
			if ($jml3645){$strXML .= "<set label='36-45 thn' value='$jml3645'  Color='006600' />";}
			if ($jml4655){$strXML .= "<set label='46-55 thn' value='$jml4655'   Color='FFFF00'/>";}
			if ($jml56){$strXML .= "<set label=' 56 thn' value='$jml56'   Color='990000'/>";}
			
		$strXML .="<styles>
				      <definition>
					<style name='myCaptionFont' type='font' font='Arial' size='14' color='666666' bold='2' underline='1'/>
					<style name='myShadow' type='Shadow' color='999999' angle='45'/>
					<style name='myGlow' type='Glow' color='FF5904'/>
					<style name='myAnim' type='animation' param='_alpha' start='0' duration='1'/>	  
				      </definition>	
					<application>
						<apply toObject='CAPTION' styles='myCaptionFont,myShadow'/>
						 <apply toObject='Legend' styles='myAnim' />
						 <apply toObject='XAxisName' styles='myGlow' />
						<apply toObject='YAxisName' styles='myGlow' />
					</application>
				  </styles>";
				  
		$strXML .= "</chart>";
		$alm=$this->basePath."/charts/Pie3D.swf";
		$grafik = 'Chart';			
		$findchartdir = $fusionCharts->renderChartHTML($alm, '', $strXML, $grafik, 400, 200, false);
		$getlistdir=$findchartdir;
		$getlistdir=$getlistdir;
		return $getlistdir; 
}



}







