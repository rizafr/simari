<?php
class Sdm_Monitoring_Service {
    private static $instance;
   
    // A private constructor; prevents direct creation of object
    private function __construct() {
       //echo 'I am constructed';
    }

    // The singleton method
    public static function getInstance() {
       if (!isset(self::$instance)) {
           $c = __CLASS__;
           self::$instance = new $c;
       }

       return self::$instance;
    }
	
	public function getPegawaiListAll($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
		
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT i_peg_nip, n_peg,c_peg_status,c_eselon,to_char(d_tmt_eselon,'dd-mm-yyyy') as d_tmt_eselon,
								c_jabatan,to_char(d_mulai_jabat,'dd-mm-yyyy') as d_mulai_jabat,c_peg_jeniskelamin,
								to_char(d_akhir_jabat,'dd-mm-yyyy') as d_akhir_jabat,c_golongan,to_char(d_tmt_golongan,'dd-mm-yyyy') as d_tmt_golongan,v_gaji_pokok,
								c_lokasi_unitkerja,c_eselon_i,c_eselon_ii,c_eselon_iii,c_eselon_iv,c_eselon_v,c_gol_cpns,
								c_lokasi_unitkerja_cpns,c_eselon_cpns,e_file_photo,c_eselon_cpns,c_lokasi_unitkerja_cpns,c_eselon_i_cpns,
								c_eselon_ii_cpns,c_eselon_iii_cpns,c_eselon_iv_cpns,c_eselon_v_cpns,c_jabatan_cpns,
								i_peg_nip_new,c_status_kepegawaian,n_peg_gelardepan,n_peg_gelarblkg,c_agama,c_golongan_darah,
								c_peg_statusnikah,n_peg_hobi,to_char(d_peg_lahir,'dd-mm-yyyy') as d_peg_lahir2,d_peg_lahir,c_peg_propinsi_lahir,a_peg_kota_lahir,q_peg_tinggibdn,
								q_peg_beratbdn,n_peg_rambut,n_peg_btkmuka,n_peg_warnakulit,n_peg_cirikhas,a_peg_rumah,a_peg_rt,
								a_peg_rw,a_peg_kelurahan,a_peg_kecamatan,a_peg_kota,a_peg_propinsi,a_peg_kodepos,i_peg_telponrumah,
								i_peg_telponhp,i_peg_karpeg,i_peg_karis,i_peg_taspen,i_peg_korpri,i_peg_ktp,i_peg_askes,d_tmt_kerja,
								c_stat_aktivasi,q_masakerja_bulan,q_masakerja_tahun,c_jenis_naik,n_jabatan_nokode,c_pend_jenis,
								c_pend,n_pend_jurusan,d_pend_mulai,d_pend_akhir,q_jumlah_anak,i_nip_pasangan,c_pekerjaan,n_seminar,
								d_mulai_seminar,d_akhir_seminar,n_seminar_peran,n_seminar_lembaga,n_jenis_organisasi,n_organisasi,
								d_daftar_organisasi,n_peran_organisasi,n_tempat_organisasi,c_negara,a_tujuan,c_biaya,n_jns_penghargaan,
								n_penghargaan,d_tahun_alteratif,c_tingkat_sanksi,c_jenis_sanksi,d_mulai_sanksi,c_penjenjangan,q_angkatan,
								q_tahun,c_kualifikasi,c_jns_fungsional,c_kel_pelatihan,c_jns_kelompok,c_nama_kelompok,q_pelatihan,n_diklat_lain,
								d_diklat_lain,c_negara_lain,q_lama_lain,c_kelompok,n_diklat_teknis,c_negara_teknis,q_lama_teknis,d_peg_pnilaidp3,
								q_peg_totalnilaidp3,d_peg_pnilaiak,q_totalnilaiak,d_sk_cpns,n_sk_pejabatcpns,i_sk_cpns,d_tmt_cpns,c_gol_cpns,c_eselon_cpns,
								q_fiktif_cpns_thn,q_fiktif_cpns_bln,q_honorer_cpns_thn,q_honorer_cpns_bln,q_swasta_cpns_thn,q_swasta_cpns_bln,q_mktotal_cpns_thn,
								q_mktotal_cpns_bln,c_pend_cpns,c_jabatan_cpns,n_unitkerja_nokode,i_sk_pns,d_sk_pns,n_sk_pejabatpns,i_kesehatan_pns,d_kesehatan_pns,
								n_rumahsakit_pns,n_kesehatan_pejabatpns,i_sk_prajab,d_sk_prajab,n_sk_pejabatprajab,d_tmt_pensiun,to_char(d_tmt_kgb,'dd-mm-yyyy') as d_tmt_kgb
								FROM sdm.tm_pegawai where 1=1 $cari ");
									
					$jmlResult = count($result);
		for ($j = 0; $j < $jmlResult; $j++) 
		{ 
						$c_eselon_i="";$c_eselon_ii="";$c_eselon_iii="";$c_eselon_iv="";$c_eselon_v="";$c_eselon="";
						

						$c_eselon_i=trim($result[$j]->c_eselon_i);
						$c_eselon_ii=trim($result[$j]->c_eselon_ii);
						$c_eselon_iii=trim($result[$j]->c_eselon_iii);
						$c_eselon_iv=trim($result[$j]->c_eselon_iv);
						$c_eselon_v=trim($result[$j]->c_eselon_v);
						$c_eselon=trim($result[$j]->c_eselon);
						$c_jabatan=trim($result[$j]->c_jabatan);
						$c_lokasi_unitkerja=trim($result[$j]->c_lokasi_unitkerja);
						$c_golongan=trim($result[$j]->c_golongan);
						$c_peg_status=trim($result[$j]->c_peg_status);

						$c_eselon_i_cpns=trim($result[$j]->c_eselon_i_cpns);
						$c_eselon_ii_cpns=trim($result[$j]->c_eselon_ii_cpns);
						$c_eselon_iii_cpns=trim($result[$j]->c_eselon_iii_cpns);
						$c_eselon_iv_cpns=trim($result[$j]->c_eselon_iv_cpns);
						$c_eselon_v_cpns=trim($result[$j]->c_eselon_v_cpns);
						$c_eselon_cpns=trim($result[$j]->c_eselon_cpns);
						$c_jabatan_cpns=trim($result[$j]->c_jabatan_cpns);
						$c_lokasi_unitkerja_cpns=trim($result[$j]->c_lokasi_unitkerja_cpns);
						$c_golongan_cpns=trim($result[$j]->c_gol_cpns);
						$c_peg_status_cpns=trim($result[$j]->c_peg_status);



				$n_eselon = $db->fetchOne("select n_eselon  from sdm.tr_eselon where c_eselon='$c_eselon'");
				$n_jabatan = $db->fetchOne("select n_jabatan from sdm.tr_jabatan where c_jabatan='$c_jabatan'");
				$n_lokasi_unitkerja = $db->fetchOne("select n_lokasi  from sdm.tr_lokasi where c_lokasi='$c_lokasi_unitkerja'");
				$n_eselon_i = $db->fetchOne("select n_unitkerja from sdm.tr_unitkerja where c_eselon_i='$c_eselon_i'");
				$n_eselon_ii = $db->fetchOne("select n_unitkerja from sdm.tr_unitkerja where  c_eselon_ii='$c_eselon_ii'");
				$n_eselon_iii = $db->fetchOne("select n_unitkerja from sdm.tr_unitkerja where  c_eselon_iii='$c_eselon_iii'");
				$n_eselon_iv = $db->fetchOne("select n_unitkerja from sdm.tr_unitkerja where  c_eselon_iv='$c_eselon_iv'");
				$c_eselon_v = $db->fetchOne("select n_unitkerja from sdm.tr_unitkerja where  c_eselon_v='$c_eselon_v'");
				$n_pangkat= $db->fetchOne("SELECT n_peg_pangkat FROM sdm.tr_golongan_pangkat WHERE c_peg_golongan ='$c_golongan'");
				$n_peg_status= $db->fetchOne("SELECT n_peg_status FROM sdm.tr_status_pegawai WHERE c_peg_status ='$c_peg_status'");
				$n_golongan= $db->fetchOne("SELECT n_peg_golongan FROM sdm.tr_golongan_pangkat WHERE c_peg_golongan ='$c_golongan'");
				
				$n_pangkat_cpns= $db->fetchOne("SELECT n_peg_pangkat FROM sdm.tr_golongan_pangkat WHERE c_peg_golongan ='$c_golongan_cpns'");
				
				$c_pend=(string)$result[$j]->c_pend;
				$n_pendidikan= $db->fetchOne("SELECT n_pend FROM sdm.tr_pendidikan WHERE c_pend ='$c_pend'");
				
				$c_peg_jeniskelamin=(string)$result[$j]->c_peg_jeniskelamin;
				if ($c_peg_jeniskelamin=="L"){$n_peg_jeniskelamin="Laki-laki";}
				if ($c_peg_jeniskelamin=="P"){$n_peg_jeniskelamin="Perempuan";}
				
				$c_peg_statusnikah=(string)$result[$j]->c_peg_statusnikah;
				$n_peg_statusnikah= $db->fetchOne("SELECT n_status_nikah FROM sdm.tr_status_nikah WHERE c_status_nikah ='$c_peg_statusnikah'");
				
				$c_agama=(string)$result[$j]->c_agama;
				$n_agama= $db->fetchOne("SELECT n_agama FROM sdm.tr_agama WHERE c_agama ='$c_agama'");
				
				$c_agama=(string)$result[$j]->c_agama;
				$n_agama= $db->fetchOne("SELECT n_agama FROM sdm.tr_agama WHERE c_agama ='$c_agama'");

				$c_peg_propinsi_lahir=(string)$result[$j]->c_peg_propinsi_lahir;
				$n_peg_propinsi_lahir= $db->fetchOne("SELECT n_propinsi FROM sdm.tr_propinsi WHERE c_propinsi ='$c_peg_propinsi_lahir'");

				$a_peg_kota_lahir=(string)$result[$j]->a_peg_kota_lahir;
				$n_peg_kota_lahir= $db->fetchOne("SELECT n_kabupaten FROM sdm.tr_kabupaten WHERE c_kabupaten ='$a_peg_kota_lahir'");
				
						if ($n_eselon_i){$nesl1=" ,$n_eselon_i";}
						if ($n_eselon_ii){$nesl2=" ,$n_eselon_ii";}
						if ($n_eselon_iii){$nesl3=" ,$n_eselon_iii";}
						if ($n_eselon_iv){$nesl4=",$n_eselon_iv";}
						if ($n_eselon_v){$nesl5=" ,$n_eselon_v";}
						if ($n_eselon_i){$jabatanlengkap=$n_jabatan." pada ".$nesl5.$nesl4.$nesl3.$nesl2.$nesl1;}
			
				$d_peg_lahir=(string)$result[$j]->d_peg_lahir;
				if ($d_peg_lahir){
					$usiatahun=$db->fetchOne("SELECT EXTRACT(years from AGE(NOW(), '$d_peg_lahir')) as age");
					$usiabulan=$db->fetchOne("SELECT EXTRACT(month from AGE(NOW(), '$d_peg_lahir')) as age");
				}
				else{
				$usiatahun="";$usiabulan="";
				}
				$d_tmt_kerja=(string)$result[$j]->d_tmt_kerja;
				if ($d_tmt_kerja){
					$masakerjatahun=$db->fetchOne("SELECT EXTRACT(years from AGE(NOW(), '$d_tmt_kerja')) as age");
					$masakerjabulan=$db->fetchOne("SELECT EXTRACT(month from AGE(NOW(), '$d_tmt_kerja')) as age");
				}
				else{$masakerjatahun=""; $masakerjabulan="";}
				
				$c_peg_propinsi=(string)$result[$j]->c_peg_propinsi;
				$n_peg_propinsi= $db->fetchOne("SELECT n_propinsi FROM sdm.tr_propinsi WHERE c_propinsi ='$c_peg_propinsi'");

				$a_peg_kota=(string)$result[$j]->a_peg_kota;
				$n_peg_kota= $db->fetchOne("SELECT n_kabupaten FROM sdm.tr_kabupaten WHERE c_kabupaten ='$a_peg_kota'");
				
				// next golongan
				$c_golongan_next = $this->getcgolongannext($c_golongan);
			
				$n_golongan_next= $db->fetchOne("SELECT n_peg_golongan FROM sdm.tr_golongan_pangkat WHERE c_peg_golongan ='$c_golongan_next'");

				
				
		$data[$j] = array("i_peg_nip"=>(string)$result[$j]->i_peg_nip,
						"n_peg"=>(string)$result[$j]->n_peg,
						"c_peg_status"=>(string)$result[$j]->c_peg_status,
						"usiabulan"=>$usiabulan,
						"usiatahun"=>(string)$usiatahun,
						"masakerjabulan"=>$masakerjabulan,
						"masakerjatahun"=>(string)$masakerjatahun,
						"n_peg_status"=>$n_peg_status,											
						"n_jabatan"=>$n_jabatan,
						"jabatanlengkap"=>$jabatanlengkap,
						"n_eselon"=>$n_eselon,
						"n_eselon_cpns"=>$n_eselon_cpns,
						"n_lokasi_unitkerja"=>$n_lokasi_unitkerja,
						"c_golongan"=>$c_golongan,
						"n_golongan"=>$n_golongan,
						"n_pangkat"=>$n_pangkat,
						"c_peg_jeniskelamin"=>(string)$result[$j]->c_peg_jeniskelamin,
						"e_file_photo"=>(string)$result[$j]->e_file_photo,
						"unitkerjalengkap"=>$unitkerjalengkap,
						"n_pangkat_cpns"=>$n_pangkat_cpns,			
						"i_peg_nip_new"=>(string)$result[$j]->i_peg_nip_new,
						"c_status_kepegawaian"=>(string)$result[$j]->c_status_kepegawaian,
						"n_peg_gelardepan"=>(string)$result[$j]->n_peg_gelardepan,
						"n_peg_gelarblkg"=>(string)$result[$j]->n_peg_gelarblkg,
						"c_peg_jeniskelamin"=>(string)$result[$j]->c_peg_jeniskelamin,
						"n_peg_jeniskelamin"=>$n_peg_jeniskelamin,						
						"c_agama"=>(string)$result[$j]->c_agama,
						"n_agama"=>$n_agama,
						"c_golongan_darah"=>(string)$result[$j]->c_golongan_darah,
						"c_peg_statusnikah"=>(string)$result[$j]->c_peg_statusnikah,
						"n_peg_statusnikah"=>$n_peg_statusnikah,
						"n_peg_hobi"=>(string)$result[$j]->n_peg_hobi,
						"d_peg_lahir"=>(string)$result[$j]->d_peg_lahir,
						"d_peg_lahir2"=>(string)$result[$j]->d_peg_lahir2,
						"c_peg_propinsi_lahir"=>(string)$result[$j]->c_peg_propinsi_lahir,
						"n_peg_propinsi_lahir"=>$n_peg_propinsi_lahir,
						"n_peg_kota_lahir"=>$n_peg_kota_lahir,
						"a_peg_kota_lahir"=>(string)$result[$j]->a_peg_kota_lahir,
						"q_peg_tinggibdn"=>(string)$result[$j]->q_peg_tinggibdn,
						"q_peg_beratbdn"=>(string)$result[$j]->q_peg_beratbdn,
						"n_peg_rambut"=>(string)$result[$j]->n_peg_rambut,
						"n_peg_btkmuka"=>(string)$result[$j]->n_peg_btkmuka,
						"n_peg_warnakulit"=>(string)$result[$j]->n_peg_warnakulit,
						"n_peg_cirikhas"=>(string)$result[$j]->n_peg_cirikhas,
						"a_peg_rumah"=>(string)$result[$j]->a_peg_rumah,
						"a_peg_rt"=>(string)$result[$j]->a_peg_rt,
						"a_peg_rw"=>(string)$result[$j]->a_peg_rw,
						"a_peg_kelurahan"=>(string)$result[$j]->a_peg_kelurahan,
						"a_peg_kecamatan"=>(string)$result[$j]->a_peg_kecamatan,
						"a_peg_kota"=>(string)$result[$j]->a_peg_kota,
						"a_peg_propinsi"=>(string)$result[$j]->a_peg_propinsi,
						"a_peg_kodepos"=>(string)$result[$j]->a_peg_kodepos,
						"i_peg_telponrumah"=>(string)$result[$j]->i_peg_telponrumah,
						"i_peg_telponhp"=>(string)$result[$j]->i_peg_telponhp,
						"i_peg_karpeg"=>(string)$result[$j]->i_peg_karpeg,
						"i_peg_karis"=>(string)$result[$j]->i_peg_karis,
						"i_peg_taspen"=>(string)$result[$j]->i_peg_taspen,
						"i_peg_korpri"=>(string)$result[$j]->i_peg_korpri,
						"i_peg_ktp"=>(string)$result[$j]->i_peg_ktp,
						"i_peg_askes"=>(string)$result[$j]->i_peg_askes,
						"d_tmt_kerja"=>(string)$result[$j]->d_tmt_kerja,
						"e_file_photo"=>(string)$result[$j]->e_file_photo,
						"c_stat_aktivasi"=>(string)$result[$j]->c_stat_aktivasi,
						"c_golongan"=>(string)$result[$j]->c_golongan,
						"d_tmt_golongan"=>(string)$result[$j]->d_tmt_golongan,
						"v_gaji_pokok"=>(string)$result[$j]->v_gaji_pokok,
						"q_masakerja_bulan"=>(string)$result[$j]->q_masakerja_bulan,
						"q_masakerja_tahun"=>(string)$result[$j]->q_masakerja_tahun,
						"c_jenis_naik"=>(string)$result[$j]->c_jenis_naik,
						"c_eselon"=>(string)$result[$j]->c_eselon,
						"d_tmt_eselon"=>(string)$result[$j]->d_tmt_eselon,
						"c_jabatan"=>(string)$result[$j]->c_jabatan,
						"d_mulai_jabat"=>(string)$result[$j]->d_mulai_jabat,
						"d_akhir_jabat"=>(string)$result[$j]->d_akhir_jabat,
						"c_lokasi_unitkerja"=>(string)$result[$j]->c_lokasi_unitkerja,
						"c_eselon_i"=>(string)$result[$j]->c_eselon_i,
						"c_eselon_ii"=>(string)$result[$j]->c_eselon_ii,
						"c_eselon_iii"=>(string)$result[$j]->c_eselon_iii,
						"c_eselon_iv"=>(string)$result[$j]->c_eselon_iv,
						"c_eselon_v"=>(string)$result[$j]->c_eselon_v,
						"n_jabatan_nokode"=>(string)$result[$j]->n_jabatan_nokode,
						"c_pend_jenis"=>(string)$result[$j]->c_pend_jenis,
						"c_pend"=>(string)$result[$j]->c_pend,
						"n_pendidikan"=>$n_pendidikan,						
						"n_pend_jurusan"=>(string)$result[$j]->n_pend_jurusan,
						"d_pend_mulai"=>(string)$result[$j]->d_pend_mulai,
						"d_pend_akhir"=>(string)$result[$j]->d_pend_akhir,
						"q_jumlah_anak"=>(string)$result[$j]->q_jumlah_anak,
						"i_nip_pasangan"=>(string)$result[$j]->i_nip_pasangan,
						"c_pekerjaan"=>(string)$result[$j]->c_pekerjaan,
						"n_seminar"=>(string)$result[$j]->n_seminar,
						"d_mulai_seminar"=>(string)$result[$j]->d_mulai_seminar,
						"d_akhir_seminar"=>(string)$result[$j]->d_akhir_seminar,
						"n_seminar_peran"=>(string)$result[$j]->n_seminar_peran,
						"n_seminar_lembaga"=>(string)$result[$j]->n_seminar_lembaga,
						"n_jenis_organisasi"=>(string)$result[$j]->n_jenis_organisasi,
						"n_organisasi"=>(string)$result[$j]->n_organisasi,
						"d_daftar_organisasi"=>(string)$result[$j]->d_daftar_organisasi,
						"n_peran_organisasi"=>(string)$result[$j]->n_peran_organisasi,
						"n_tempat_organisasi"=>(string)$result[$j]->n_tempat_organisasi,
						"c_negara"=>(string)$result[$j]->c_negara,
						"a_tujuan"=>(string)$result[$j]->a_tujuan,
						"c_biaya"=>(string)$result[$j]->c_biaya,
						"n_jns_penghargaan"=>(string)$result[$j]->n_jns_penghargaan,
						"n_penghargaan"=>(string)$result[$j]->n_penghargaan,
						"d_tahun_alteratif"=>(string)$result[$j]->d_tahun_alteratif,
						"c_tingkat_sanksi"=>(string)$result[$j]->c_tingkat_sanksi,
						"c_jenis_sanksi"=>(string)$result[$j]->c_jenis_sanksi,
						"d_mulai_sanksi"=>(string)$result[$j]->d_mulai_sanksi,
						"c_penjenjangan"=>(string)$result[$j]->c_penjenjangan,
						"q_angkatan"=>(string)$result[$j]->q_angkatan,
						"q_tahun"=>(string)$result[$j]->q_tahun,
						"c_kualifikasi"=>(string)$result[$j]->c_kualifikasi,
						"c_jns_fungsional"=>(string)$result[$j]->c_jns_fungsional,
						"c_kel_pelatihan"=>(string)$result[$j]->c_kel_pelatihan,
						"c_jns_kelompok"=>(string)$result[$j]->c_jns_kelompok,
						"c_nama_kelompok"=>(string)$result[$j]->c_nama_kelompok,
						"q_pelatihan"=>(string)$result[$j]->q_pelatihan,
						"n_diklat_lain"=>(string)$result[$j]->n_diklat_lain,
						"d_diklat_lain"=>(string)$result[$j]->d_diklat_lain,
						"c_negara_lain"=>(string)$result[$j]->c_negara_lain,
						"q_lama_lain"=>(string)$result[$j]->q_lama_lain,
						"c_kelompok"=>(string)$result[$j]->c_kelompok,
						"n_diklat_teknis"=>(string)$result[$j]->n_diklat_teknis,
						"c_negara_teknis"=>(string)$result[$j]->c_negara_teknis,
						"q_lama_teknis"=>(string)$result[$j]->q_lama_teknis,
						"d_peg_pnilaidp3"=>(string)$result[$j]->d_peg_pnilaidp3,
						"q_peg_totalnilaidp3"=>(string)$result[$j]->q_peg_totalnilaidp3,
						"d_peg_pnilaiak"=>(string)$result[$j]->d_peg_pnilaiak,
						"q_totalnilaiak"=>(string)$result[$j]->q_totalnilaiak,
						"d_sk_cpns"=>(string)$result[$j]->d_sk_cpns,
						"n_sk_pejabatcpns"=>(string)$result[$j]->n_sk_pejabatcpns,
						"i_sk_cpns"=>(string)$result[$j]->i_sk_cpns,
						"d_tmt_cpns"=>(string)$result[$j]->d_tmt_cpns,
						"c_gol_cpns"=>(string)$result[$j]->c_gol_cpns,
						"c_eselon_cpns"=>(string)$result[$j]->c_eselon_cpns,
						"c_lokasi_unitkerja_cpns"=>(string)$result[$j]->c_lokasi_unitkerja_cpns,
						"c_eselon_i_cpns"=>(string)$result[$j]->c_eselon_i_cpns,
						"c_eselon_ii_cpns"=>(string)$result[$j]->c_eselon_ii_cpns,
						"c_eselon_iii_cpns"=>(string)$result[$j]->c_eselon_iii_cpns,
						"c_eselon_iv_cpns"=>(string)$result[$j]->c_eselon_iv_cpns,
						"c_eselon_v_cpns"=>(string)$result[$j]->c_eselon_v_cpns,
						"q_fiktif_cpns_thn"=>(string)$result[$j]->q_fiktif_cpns_thn,
						"q_fiktif_cpns_bln"=>(string)$result[$j]->q_fiktif_cpns_bln,
						"q_honorer_cpns_thn"=>(string)$result[$j]->q_honorer_cpns_thn,
						"q_honorer_cpns_bln"=>(string)$result[$j]->q_honorer_cpns_bln,
						"q_swasta_cpns_thn"=>(string)$result[$j]->q_swasta_cpns_thn,
						"q_swasta_cpns_bln"=>(string)$result[$j]->q_swasta_cpns_bln,
						"q_mktotal_cpns_thn"=>(string)$result[$j]->q_mktotal_cpns_thn,
						"q_mktotal_cpns_bln"=>(string)$result[$j]->q_mktotal_cpns_bln,
						"c_pend_cpns"=>(string)$result[$j]->c_pend_cpns,
						"c_jabatan_cpns"=>(string)$result[$j]->c_jabatan_cpns,
						"n_unitkerja_nokode"=>(string)$result[$j]->n_unitkerja_nokode,
						"i_sk_pns"=>(string)$result[$j]->i_sk_pns,
						"d_sk_pns"=>(string)$result[$j]->d_sk_pns,
						"n_sk_pejabatpns"=>(string)$result[$j]->n_sk_pejabatpns,
						"i_kesehatan_pns"=>(string)$result[$j]->i_kesehatan_pns,
						"d_kesehatan_pns"=>(string)$result[$j]->d_kesehatan_pns,
						"n_rumahsakit_pns"=>(string)$result[$j]->n_rumahsakit_pns,
						"n_kesehatan_pejabatpns"=>(string)$result[$j]->n_kesehatan_pejabatpns,
						"i_sk_prajab"=>(string)$result[$j]->i_sk_prajab,
						"d_sk_prajab"=>(string)$result[$j]->d_sk_prajab,
						"n_sk_pejabatprajab"=>(string)$result[$j]->n_sk_pejabatprajab,
						"d_tmt_pensiun"=>(string)$result[$j]->d_tmt_pensiun,
						"n_peg_propinsi"=>(string)$result[$j]->n_peg_propinsi,
						"n_peg_kota"=>(string)$result[$j]->n_peg_kota,
						"d_tmt_kgb"=>(string)$result[$j]->d_tmt_kgb);	
		}
										
			return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	 
 	public function getPegawaiList($cari,$currentPage, $numToDisplay,$orderby) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				if(($currentPage==0) && ($numToDisplay==0))
			{
				$data = $db->fetchOne("select count(*) from  sdm.tm_pegawai where  1=1 $cari");
	
			}
			else		
			{	
				$xLimit=$numToDisplay;
				$xOffset=($currentPage-1)*$numToDisplay;				
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT i_peg_nip, n_peg,c_peg_status,c_eselon,to_char(d_tmt_eselon,'dd-mm-yyyy') as d_tmt_eselon,
								c_jabatan,to_char(d_mulai_jabat,'dd-mm-yyyy') as d_mulai_jabat,c_peg_jeniskelamin,
								to_char(d_akhir_jabat,'dd-mm-yyyy') as d_akhir_jabat,c_golongan,to_char(d_tmt_golongan,'dd-mm-yyyy') as d_tmt_golongan ,v_gaji_pokok,
								c_lokasi_unitkerja,c_eselon_i,c_eselon_ii,c_eselon_iii,c_eselon_iv,c_eselon_v,c_gol_cpns,
								c_lokasi_unitkerja_cpns,c_eselon_cpns,e_file_photo,c_eselon_cpns,c_lokasi_unitkerja_cpns,c_eselon_i_cpns,
								c_eselon_ii_cpns,c_eselon_iii_cpns,c_eselon_iv_cpns,c_eselon_v_cpns,c_jabatan_cpns,
								i_peg_nip_new,c_status_kepegawaian,n_peg_gelardepan,n_peg_gelarblkg,c_agama,c_golongan_darah,
								c_peg_statusnikah,n_peg_hobi,to_char(d_peg_lahir,'dd-mm-yyyy') as d_peg_lahir2,d_peg_lahir,c_peg_propinsi_lahir,a_peg_kota_lahir,q_peg_tinggibdn,
								q_peg_beratbdn,n_peg_rambut,n_peg_btkmuka,n_peg_warnakulit,n_peg_cirikhas,a_peg_rumah,a_peg_rt,
								a_peg_rw,a_peg_kelurahan,a_peg_kecamatan,a_peg_kota,a_peg_propinsi,a_peg_kodepos,i_peg_telponrumah,
								i_peg_telponhp,i_peg_karpeg,i_peg_karis,i_peg_taspen,i_peg_korpri,i_peg_ktp,i_peg_askes,d_tmt_kerja,
								c_stat_aktivasi,q_masakerja_bulan,q_masakerja_tahun,c_jenis_naik,n_jabatan_nokode,c_pend_jenis,
								c_pend,n_pend_jurusan,d_pend_mulai,d_pend_akhir,q_jumlah_anak,i_nip_pasangan,c_pekerjaan,n_seminar,
								d_mulai_seminar,d_akhir_seminar,n_seminar_peran,n_seminar_lembaga,n_jenis_organisasi,n_organisasi,
								d_daftar_organisasi,n_peran_organisasi,n_tempat_organisasi,c_negara,a_tujuan,c_biaya,n_jns_penghargaan,
								n_penghargaan,d_tahun_alteratif,c_tingkat_sanksi,c_jenis_sanksi,d_mulai_sanksi,c_penjenjangan,q_angkatan,
								q_tahun,c_kualifikasi,c_jns_fungsional,c_kel_pelatihan,c_jns_kelompok,c_nama_kelompok,q_pelatihan,n_diklat_lain,
								d_diklat_lain,c_negara_lain,q_lama_lain,c_kelompok,n_diklat_teknis,c_negara_teknis,q_lama_teknis,d_peg_pnilaidp3,
								q_peg_totalnilaidp3,d_peg_pnilaiak,q_totalnilaiak,d_sk_cpns,n_sk_pejabatcpns,i_sk_cpns,to_char(d_tmt_cpns,'dd-mm-yyyy') as d_tmt_cpns,c_gol_cpns,c_eselon_cpns,
								q_fiktif_cpns_thn,q_fiktif_cpns_bln,q_honorer_cpns_thn,q_honorer_cpns_bln,q_swasta_cpns_thn,q_swasta_cpns_bln,q_mktotal_cpns_thn,
								q_mktotal_cpns_bln,c_pend_cpns,c_jabatan_cpns,n_unitkerja_nokode,i_sk_pns,d_sk_pns,n_sk_pejabatpns,i_kesehatan_pns,d_kesehatan_pns,
								n_rumahsakit_pns,n_kesehatan_pejabatpns,i_sk_prajab,d_sk_prajab,n_sk_pejabatprajab,d_tmt_pensiun,to_char(d_tmt_kgb,'dd-mm-yyyy') as d_tmt_kgb
								FROM sdm.tm_pegawai where  1=1 $cari  $orderby   
								limit $xLimit offset $xOffset");
									
					$jmlResult = count($result);
		for ($j = 0; $j < $jmlResult; $j++) 
		{ 
						$c_eselon_i="";$c_eselon_ii="";$c_eselon_iii="";$c_eselon_iv="";$c_eselon_v="";$c_eselon="";
						

						$c_eselon_i=trim($result[$j]->c_eselon_i);
						$c_eselon_ii=trim($result[$j]->c_eselon_ii);
						$c_eselon_iii=trim($result[$j]->c_eselon_iii);
						$c_eselon_iv=trim($result[$j]->c_eselon_iv);
						$c_eselon_v=trim($result[$j]->c_eselon_v);
						$c_eselon=trim($result[$j]->c_eselon);
						$c_jabatan=trim($result[$j]->c_jabatan);
						$c_lokasi_unitkerja=trim($result[$j]->c_lokasi_unitkerja);
						$c_golongan=trim($result[$j]->c_golongan);
						$c_peg_status=trim($result[$j]->c_peg_status);

						$c_eselon_i_cpns=trim($result[$j]->c_eselon_i_cpns);
						$c_eselon_ii_cpns=trim($result[$j]->c_eselon_ii_cpns);
						$c_eselon_iii_cpns=trim($result[$j]->c_eselon_iii_cpns);
						$c_eselon_iv_cpns=trim($result[$j]->c_eselon_iv_cpns);
						$c_eselon_v_cpns=trim($result[$j]->c_eselon_v_cpns);
						$c_eselon_cpns=trim($result[$j]->c_eselon_cpns);
						$c_jabatan_cpns=trim($result[$j]->c_jabatan_cpns);
						$c_lokasi_unitkerja_cpns=trim($result[$j]->c_lokasi_unitkerja_cpns);
						$c_golongan_cpns=trim($result[$j]->c_gol_cpns);
						$c_peg_status_cpns=trim($result[$j]->c_peg_status);



				$n_eselon = $db->fetchOne("select n_eselon  from sdm.tr_eselon where c_eselon='$c_eselon'");
				$n_jabatan = $db->fetchOne("select n_jabatan from sdm.tr_jabatan where c_jabatan='$c_jabatan'");
				$n_lokasi_unitkerja = $db->fetchOne("select n_lokasi  from sdm.tr_lokasi where c_lokasi='$c_lokasi_unitkerja'");
				$n_eselon_i = $db->fetchOne("select n_unitkerja from sdm.tr_unitkerja where c_eselon_i='$c_eselon_i'");
				$n_eselon_ii = $db->fetchOne("select n_unitkerja from sdm.tr_unitkerja where  c_eselon_ii='$c_eselon_ii'");
				$n_eselon_iii = $db->fetchOne("select n_unitkerja from sdm.tr_unitkerja where  c_eselon_iii='$c_eselon_iii'");
				$n_eselon_iv = $db->fetchOne("select n_unitkerja from sdm.tr_unitkerja where  c_eselon_iv='$c_eselon_iv'");
				$c_eselon_v = $db->fetchOne("select n_unitkerja from sdm.tr_unitkerja where  c_eselon_v='$c_eselon_v'");
				$n_pangkat= $db->fetchOne("SELECT n_peg_pangkat FROM sdm.tr_golongan_pangkat WHERE c_peg_golongan ='$c_golongan'");
				$n_peg_status= $db->fetchOne("SELECT n_peg_status FROM sdm.tr_status_pegawai WHERE c_peg_status ='$c_peg_status'");
				$n_golongan= $db->fetchOne("SELECT n_peg_golongan FROM sdm.tr_golongan_pangkat WHERE c_peg_golongan ='$c_golongan'");
				
				$n_pangkat_cpns= $db->fetchOne("SELECT n_peg_pangkat FROM sdm.tr_golongan_pangkat WHERE c_peg_golongan ='$c_golongan_cpns'");
				
				$c_pend=(string)$result[$j]->c_pend;
				$n_pendidikan= $db->fetchOne("SELECT n_pend FROM sdm.tr_pendidikan WHERE c_pend ='$c_pend'");
				
				$c_peg_jeniskelamin=(string)$result[$j]->c_peg_jeniskelamin;
				if ($c_peg_jeniskelamin=="L"){$n_peg_jeniskelamin="Laki-laki";}
				if ($c_peg_jeniskelamin=="P"){$n_peg_jeniskelamin="Perempuan";}
				
				$c_peg_statusnikah=(string)$result[$j]->c_peg_statusnikah;
				$n_peg_statusnikah= $db->fetchOne("SELECT n_status_nikah FROM sdm.tr_status_nikah WHERE c_status_nikah ='$c_peg_statusnikah'");
				
				$c_agama=(string)$result[$j]->c_agama;
				$n_agama= $db->fetchOne("SELECT n_agama FROM sdm.tr_agama WHERE c_agama ='$c_agama'");
				
				$c_agama=(string)$result[$j]->c_agama;
				$n_agama= $db->fetchOne("SELECT n_agama FROM sdm.tr_agama WHERE c_agama ='$c_agama'");

				$c_peg_propinsi_lahir=(string)$result[$j]->c_peg_propinsi_lahir;
				$n_peg_propinsi_lahir= $db->fetchOne("SELECT n_propinsi FROM sdm.tr_propinsi WHERE c_propinsi ='$c_peg_propinsi_lahir'");

				$a_peg_kota_lahir=(string)$result[$j]->a_peg_kota_lahir;
				$n_peg_kota_lahir= $db->fetchOne("SELECT n_kabupaten FROM sdm.tr_kabupaten WHERE c_kabupaten ='$a_peg_kota_lahir'");
				
						if ($n_eselon_i){$nesl1=" ,$n_eselon_i";}
						if ($n_eselon_ii){$nesl2=" ,$n_eselon_ii";}
						if ($n_eselon_iii){$nesl3=" ,$n_eselon_iii";}
						if ($n_eselon_iv){$nesl4=",$n_eselon_iv";}
						if ($n_eselon_v){$nesl5=" ,$n_eselon_v";}
						if ($n_eselon_i){$jabatanlengkap=$n_jabatan." pada ".$nesl5.$nesl4.$nesl3.$nesl2.$nesl1;}
			
				$d_peg_lahir=(string)$result[$j]->d_peg_lahir;
				if ($d_peg_lahir){
					$usiatahun=$db->fetchOne("SELECT EXTRACT(years from AGE(NOW(), '$d_peg_lahir')) as age");
					$usiabulan=$db->fetchOne("SELECT EXTRACT(month from AGE(NOW(), '$d_peg_lahir')) as age");
				}
				else{
				$usiatahun="";$usiabulan="";
				}
				$d_tmt_kerja=(string)$result[$j]->d_tmt_kerja;
				if ($d_tmt_kerja){
					$masakerjatahun=$db->fetchOne("SELECT EXTRACT(years from AGE(NOW(), '$d_tmt_kerja')) as age");
					$masakerjabulan=$db->fetchOne("SELECT EXTRACT(month from AGE(NOW(), '$d_tmt_kerja')) as age");
				}
				
				else{$masakerjatahun=""; $masakerjabulan="";}
				$c_peg_propinsi=(string)$result[$j]->c_peg_propinsi;
				$n_peg_propinsi= $db->fetchOne("SELECT n_propinsi FROM sdm.tr_propinsi WHERE c_propinsi ='$c_peg_propinsi'");

				$a_peg_kota=(string)$result[$j]->a_peg_kota;
				$n_peg_kota= $db->fetchOne("SELECT n_kabupaten FROM sdm.tr_kabupaten WHERE c_kabupaten ='$a_peg_kota'");
				
				// next golongan
				$c_golongan_next = $this->getcgolongannext($c_golongan);				
				$n_golongan_next= $db->fetchOne("SELECT n_peg_golongan FROM sdm.tr_golongan_pangkat WHERE c_peg_golongan ='$c_golongan_next' and c_peg_tipegolongan='3'");
				
		$data[$j] = array("i_peg_nip"=>(string)$result[$j]->i_peg_nip,
						"n_peg"=>(string)$result[$j]->n_peg,
						"c_peg_status"=>(string)$result[$j]->c_peg_status,
						"usiabulan"=>$usiabulan,
						"usiatahun"=>(string)$usiatahun,
						"masakerjabulan"=>$masakerjabulan,
						"masakerjatahun"=>(string)$masakerjatahun,
						"n_peg_status"=>$n_peg_status,											
						"n_jabatan"=>$n_jabatan,
						"jabatanlengkap"=>$jabatanlengkap,
						"n_eselon"=>$n_eselon,
						"n_eselon_cpns"=>$n_eselon_cpns,
						"n_lokasi_unitkerja"=>$n_lokasi_unitkerja,
						"c_golongan"=>$c_golongan,
						"n_golongan"=>$n_golongan,
						"n_golongan_next"=>$n_golongan_next,						
						"n_pangkat"=>$n_pangkat,
						"c_peg_jeniskelamin"=>(string)$result[$j]->c_peg_jeniskelamin,
						"e_file_photo"=>(string)$result[$j]->e_file_photo,
						"unitkerjalengkap"=>$unitkerjalengkap,
						"n_pangkat_cpns"=>$n_pangkat_cpns,			
						"i_peg_nip_new"=>(string)$result[$j]->i_peg_nip_new,
						"c_status_kepegawaian"=>(string)$result[$j]->c_status_kepegawaian,
						"n_peg_gelardepan"=>(string)$result[$j]->n_peg_gelardepan,
						"n_peg_gelarblkg"=>(string)$result[$j]->n_peg_gelarblkg,
						"c_peg_jeniskelamin"=>(string)$result[$j]->c_peg_jeniskelamin,
						"n_peg_jeniskelamin"=>$n_peg_jeniskelamin,						
						"c_agama"=>(string)$result[$j]->c_agama,
						"n_agama"=>$n_agama,
						"c_golongan_darah"=>(string)$result[$j]->c_golongan_darah,
						"c_peg_statusnikah"=>(string)$result[$j]->c_peg_statusnikah,
						"n_peg_statusnikah"=>$n_peg_statusnikah,
						"n_peg_hobi"=>(string)$result[$j]->n_peg_hobi,
						"d_peg_lahir"=>(string)$result[$j]->d_peg_lahir,
						"d_peg_lahir2"=>(string)$result[$j]->d_peg_lahir2,
						"c_peg_propinsi_lahir"=>(string)$result[$j]->c_peg_propinsi_lahir,
						"n_peg_propinsi_lahir"=>$n_peg_propinsi_lahir,
						"n_peg_kota_lahir"=>$n_peg_kota_lahir,
						"a_peg_kota_lahir"=>(string)$result[$j]->a_peg_kota_lahir,
						"q_peg_tinggibdn"=>(string)$result[$j]->q_peg_tinggibdn,
						"q_peg_beratbdn"=>(string)$result[$j]->q_peg_beratbdn,
						"n_peg_rambut"=>(string)$result[$j]->n_peg_rambut,
						"n_peg_btkmuka"=>(string)$result[$j]->n_peg_btkmuka,
						"n_peg_warnakulit"=>(string)$result[$j]->n_peg_warnakulit,
						"n_peg_cirikhas"=>(string)$result[$j]->n_peg_cirikhas,
						"a_peg_rumah"=>(string)$result[$j]->a_peg_rumah,
						"a_peg_rt"=>(string)$result[$j]->a_peg_rt,
						"a_peg_rw"=>(string)$result[$j]->a_peg_rw,
						"a_peg_kelurahan"=>(string)$result[$j]->a_peg_kelurahan,
						"a_peg_kecamatan"=>(string)$result[$j]->a_peg_kecamatan,
						"a_peg_kota"=>(string)$result[$j]->a_peg_kota,
						"a_peg_propinsi"=>(string)$result[$j]->a_peg_propinsi,
						"a_peg_kodepos"=>(string)$result[$j]->a_peg_kodepos,
						"i_peg_telponrumah"=>(string)$result[$j]->i_peg_telponrumah,
						"i_peg_telponhp"=>(string)$result[$j]->i_peg_telponhp,
						"i_peg_karpeg"=>(string)$result[$j]->i_peg_karpeg,
						"i_peg_karis"=>(string)$result[$j]->i_peg_karis,
						"i_peg_taspen"=>(string)$result[$j]->i_peg_taspen,
						"i_peg_korpri"=>(string)$result[$j]->i_peg_korpri,
						"i_peg_ktp"=>(string)$result[$j]->i_peg_ktp,
						"i_peg_askes"=>(string)$result[$j]->i_peg_askes,
						"d_tmt_kerja"=>(string)$result[$j]->d_tmt_kerja,
						"e_file_photo"=>(string)$result[$j]->e_file_photo,
						"c_stat_aktivasi"=>(string)$result[$j]->c_stat_aktivasi,
						"c_golongan"=>(string)$result[$j]->c_golongan,
						"d_tmt_golongan"=>(string)$result[$j]->d_tmt_golongan,
						"v_gaji_pokok"=>(string)$result[$j]->v_gaji_pokok,
						"q_masakerja_bulan"=>(string)$result[$j]->q_masakerja_bulan,
						"q_masakerja_tahun"=>(string)$result[$j]->q_masakerja_tahun,
						"c_jenis_naik"=>(string)$result[$j]->c_jenis_naik,
						"c_eselon"=>(string)$result[$j]->c_eselon,
						"d_tmt_eselon"=>(string)$result[$j]->d_tmt_eselon,
						"c_jabatan"=>(string)$result[$j]->c_jabatan,
						"d_mulai_jabat"=>(string)$result[$j]->d_mulai_jabat,
						"d_akhir_jabat"=>(string)$result[$j]->d_akhir_jabat,
						"c_lokasi_unitkerja"=>(string)$result[$j]->c_lokasi_unitkerja,
						"c_eselon_i"=>(string)$result[$j]->c_eselon_i,
						"c_eselon_ii"=>(string)$result[$j]->c_eselon_ii,
						"c_eselon_iii"=>(string)$result[$j]->c_eselon_iii,
						"c_eselon_iv"=>(string)$result[$j]->c_eselon_iv,
						"c_eselon_v"=>(string)$result[$j]->c_eselon_v,
						"n_jabatan_nokode"=>(string)$result[$j]->n_jabatan_nokode,
						"c_pend_jenis"=>(string)$result[$j]->c_pend_jenis,
						"c_pend"=>(string)$result[$j]->c_pend,
						"n_pendidikan"=>$n_pendidikan,						
						"n_pend_jurusan"=>(string)$result[$j]->n_pend_jurusan,
						"d_pend_mulai"=>(string)$result[$j]->d_pend_mulai,
						"d_pend_akhir"=>(string)$result[$j]->d_pend_akhir,
						"q_jumlah_anak"=>(string)$result[$j]->q_jumlah_anak,
						"i_nip_pasangan"=>(string)$result[$j]->i_nip_pasangan,
						"c_pekerjaan"=>(string)$result[$j]->c_pekerjaan,
						"n_seminar"=>(string)$result[$j]->n_seminar,
						"d_mulai_seminar"=>(string)$result[$j]->d_mulai_seminar,
						"d_akhir_seminar"=>(string)$result[$j]->d_akhir_seminar,
						"n_seminar_peran"=>(string)$result[$j]->n_seminar_peran,
						"n_seminar_lembaga"=>(string)$result[$j]->n_seminar_lembaga,
						"n_jenis_organisasi"=>(string)$result[$j]->n_jenis_organisasi,
						"n_organisasi"=>(string)$result[$j]->n_organisasi,
						"d_daftar_organisasi"=>(string)$result[$j]->d_daftar_organisasi,
						"n_peran_organisasi"=>(string)$result[$j]->n_peran_organisasi,
						"n_tempat_organisasi"=>(string)$result[$j]->n_tempat_organisasi,
						"c_negara"=>(string)$result[$j]->c_negara,
						"a_tujuan"=>(string)$result[$j]->a_tujuan,
						"c_biaya"=>(string)$result[$j]->c_biaya,
						"n_jns_penghargaan"=>(string)$result[$j]->n_jns_penghargaan,
						"n_penghargaan"=>(string)$result[$j]->n_penghargaan,
						"d_tahun_alteratif"=>(string)$result[$j]->d_tahun_alteratif,
						"c_tingkat_sanksi"=>(string)$result[$j]->c_tingkat_sanksi,
						"c_jenis_sanksi"=>(string)$result[$j]->c_jenis_sanksi,
						"d_mulai_sanksi"=>(string)$result[$j]->d_mulai_sanksi,
						"c_penjenjangan"=>(string)$result[$j]->c_penjenjangan,
						"q_angkatan"=>(string)$result[$j]->q_angkatan,
						"q_tahun"=>(string)$result[$j]->q_tahun,
						"c_kualifikasi"=>(string)$result[$j]->c_kualifikasi,
						"c_jns_fungsional"=>(string)$result[$j]->c_jns_fungsional,
						"c_kel_pelatihan"=>(string)$result[$j]->c_kel_pelatihan,
						"c_jns_kelompok"=>(string)$result[$j]->c_jns_kelompok,
						"c_nama_kelompok"=>(string)$result[$j]->c_nama_kelompok,
						"q_pelatihan"=>(string)$result[$j]->q_pelatihan,
						"n_diklat_lain"=>(string)$result[$j]->n_diklat_lain,
						"d_diklat_lain"=>(string)$result[$j]->d_diklat_lain,
						"c_negara_lain"=>(string)$result[$j]->c_negara_lain,
						"q_lama_lain"=>(string)$result[$j]->q_lama_lain,
						"c_kelompok"=>(string)$result[$j]->c_kelompok,
						"n_diklat_teknis"=>(string)$result[$j]->n_diklat_teknis,
						"c_negara_teknis"=>(string)$result[$j]->c_negara_teknis,
						"q_lama_teknis"=>(string)$result[$j]->q_lama_teknis,
						"d_peg_pnilaidp3"=>(string)$result[$j]->d_peg_pnilaidp3,
						"q_peg_totalnilaidp3"=>(string)$result[$j]->q_peg_totalnilaidp3,
						"d_peg_pnilaiak"=>(string)$result[$j]->d_peg_pnilaiak,
						"q_totalnilaiak"=>(string)$result[$j]->q_totalnilaiak,
						"d_sk_cpns"=>(string)$result[$j]->d_sk_cpns,
						"n_sk_pejabatcpns"=>(string)$result[$j]->n_sk_pejabatcpns,
						"i_sk_cpns"=>(string)$result[$j]->i_sk_cpns,
						"d_tmt_cpns"=>(string)$result[$j]->d_tmt_cpns,
						"c_gol_cpns"=>(string)$result[$j]->c_gol_cpns,
						"c_eselon_cpns"=>(string)$result[$j]->c_eselon_cpns,
						"c_lokasi_unitkerja_cpns"=>(string)$result[$j]->c_lokasi_unitkerja_cpns,
						"c_eselon_i_cpns"=>(string)$result[$j]->c_eselon_i_cpns,
						"c_eselon_ii_cpns"=>(string)$result[$j]->c_eselon_ii_cpns,
						"c_eselon_iii_cpns"=>(string)$result[$j]->c_eselon_iii_cpns,
						"c_eselon_iv_cpns"=>(string)$result[$j]->c_eselon_iv_cpns,
						"c_eselon_v_cpns"=>(string)$result[$j]->c_eselon_v_cpns,
						"q_fiktif_cpns_thn"=>(string)$result[$j]->q_fiktif_cpns_thn,
						"q_fiktif_cpns_bln"=>(string)$result[$j]->q_fiktif_cpns_bln,
						"q_honorer_cpns_thn"=>(string)$result[$j]->q_honorer_cpns_thn,
						"q_honorer_cpns_bln"=>(string)$result[$j]->q_honorer_cpns_bln,
						"q_swasta_cpns_thn"=>(string)$result[$j]->q_swasta_cpns_thn,
						"q_swasta_cpns_bln"=>(string)$result[$j]->q_swasta_cpns_bln,
						"q_mktotal_cpns_thn"=>(string)$result[$j]->q_mktotal_cpns_thn,
						"q_mktotal_cpns_bln"=>(string)$result[$j]->q_mktotal_cpns_bln,
						"c_pend_cpns"=>(string)$result[$j]->c_pend_cpns,
						"c_jabatan_cpns"=>(string)$result[$j]->c_jabatan_cpns,
						"n_unitkerja_nokode"=>(string)$result[$j]->n_unitkerja_nokode,
						"i_sk_pns"=>(string)$result[$j]->i_sk_pns,
						"d_sk_pns"=>(string)$result[$j]->d_sk_pns,
						"n_sk_pejabatpns"=>(string)$result[$j]->n_sk_pejabatpns,
						"i_kesehatan_pns"=>(string)$result[$j]->i_kesehatan_pns,
						"d_kesehatan_pns"=>(string)$result[$j]->d_kesehatan_pns,
						"n_rumahsakit_pns"=>(string)$result[$j]->n_rumahsakit_pns,
						"n_kesehatan_pejabatpns"=>(string)$result[$j]->n_kesehatan_pejabatpns,
						"i_sk_prajab"=>(string)$result[$j]->i_sk_prajab,
						"d_sk_prajab"=>(string)$result[$j]->d_sk_prajab,
						"n_sk_pejabatprajab"=>(string)$result[$j]->n_sk_pejabatprajab,
						"d_tmt_pensiun"=>(string)$result[$j]->d_tmt_pensiun,
						"n_peg_propinsi"=>(string)$result[$j]->n_peg_propinsi,
						"n_peg_kota"=>(string)$result[$j]->n_peg_kota,
						"d_tmt_kgb"=>(string)$result[$j]->d_tmt_kgb);	
		}
			}							
			return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
 	public function getPegawaiListByNip($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{		
				
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT i_peg_nip,i_peg_nip_new,n_peg,n_peg_gelardepan,n_peg_gelarblkg,c_peg_jeniskelamin,
										c_agama,c_golongan_darah,c_peg_statusnikah,n_peg_hobi,to_char(d_peg_lahir,'dd-mm-yyyy') as d_peg_lahir,c_peg_propinsi_lahir,
										a_peg_kota_lahir,q_peg_tinggibdn,q_peg_beratbdn,n_peg_rambut,n_peg_btkmuka,n_peg_warnakulit,
										n_peg_cirikhas,a_peg_rumah,a_peg_rt,a_peg_rw,a_peg_kelurahan,a_peg_kecamatan,a_peg_kota,
										a_peg_propinsi,a_peg_kodepos,i_peg_telponrumah,i_peg_telponhp,i_peg_karpeg,i_peg_karis,
										i_peg_taspen,i_peg_korpri,i_peg_ktp,i_peg_askes,e_file_photo,c_stat_aktivasi,
										to_char(d_sk_cpns,'dd-mm-yyyy') as d_sk_cpns,n_sk_pejabatcpns,i_sk_cpns,
										to_char(d_tmt_cpns,'dd-mm-yyyy') as d_tmt_cpns,c_gol_cpns,c_eselon_cpns,
										c_lokasi_unitkerja_cpns,c_eselon_i_cpns,c_eselon_ii_cpns,c_eselon_iii_cpns,
										c_eselon_iv_cpns,c_eselon_v_cpns,q_fiktif_cpns_thn,q_fiktif_cpns_bln,q_honorer_cpns_thn,
										q_honorer_cpns_bln,q_swasta_cpns_thn,q_swasta_cpns_bln,q_mktotal_cpns_thn,q_mktotal_cpns_bln,
										c_pend_cpns,c_jabatan_cpns,c_status_kepegawaian,to_char(d_tmt_kerja,'dd-mm-yyyy') as d_tmt_kerja,
										n_unitkerja_nokode,i_sk_pns,to_char(d_sk_pns,'dd-mm-yyyy') as d_sk_pns ,n_sk_pejabatpns,i_kesehatan_pns,
										to_char(d_kesehatan_pns,'dd-mm-yyyy') as d_kesehatan_pns ,n_rumahsakit_pns,n_kesehatan_pejabatpns,i_sk_prajab,
										to_char(d_sk_prajab,'dd-mm-yyyy') as d_sk_prajab ,n_sk_pejabatprajab,c_eselon,
										c_lokasi_unitkerja,c_eselon_i,c_eselon_ii,c_eselon_iii,c_eselon_iv,c_eselon_v,c_peg_status,i_entry,d_entry
										FROM sdm.tm_pegawai where 1=1 $cari");
									
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 

						$c_golongan=trim($result[$j]->c_golongan);
						$n_pangkat= $db->fetchOne("SELECT n_peg_pangkat FROM sdm.tr_golongan_pangkat WHERE c_peg_golongan ='$c_golongan'");	
						$c_gol_cpns=trim($result[$j]->c_gol_cpns);
						$n_pangkat_cpns= $db->fetchOne("SELECT n_peg_pangkat FROM sdm.tr_golongan_pangkat WHERE c_peg_golongan ='$c_gol_cpns'");
						
						$c_eselon=trim($result[$j]->c_eselon);
						$n_eselon= $db->fetchOne("SELECT n_eselon FROM sdm.tr_eselon WHERE c_eselon ='$c_eselon'");						

						$c_eselon_cpns=trim($result[$j]->c_eselon_cpns);
						$n_eselon_cpns= $db->fetchOne("SELECT n_eselon FROM sdm.tr_eselon WHERE c_eselon ='$c_eselon_cpns'");
						
						$c_jabatan_cpns=trim($result[$j]->c_jabatan_cpns);
						$n_jabatan_cpns= $db->fetchOne("SELECT n_jabatan FROM sdm.tr_jabatan WHERE c_jabatan ='$c_jabatan_cpns'");
						
						$c_lokasi_unitkerja_cpns=trim($result[$j]->c_lokasi_unitkerja_cpns);
						$c_eselon_i_cpns=trim($result[$j]->c_eselon_i_cpns);
						$c_eselon_ii_cpns=trim($result[$j]->c_eselon_ii_cpns);
						$c_eselon_iii_cpns=trim($result[$j]->c_eselon_iii_cpns);
						$c_eselon_iv_cpns=trim($result[$j]->c_eselon_iv_cpns);
						
						$neseloncpns1 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i_cpns' and c_tkt_esl='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja_cpns'");
						$neseloncpns2 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i_cpns' and c_eselon_ii='$c_eselon_ii_cpns' and  c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja_cpns'");							
						$neseloncpns3 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i_cpns' and c_eselon_ii='$c_eselon_ii_cpns' and c_eselon_iii='$c_eselon_iii_cpns' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja_cpns'");
						$neseloncpns4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i_cpns' and c_eselon_ii='$c_eselon_ii_cpns' and c_eselon_iii='$c_eselon_iii_cpns' and c_eselon_iv='$c_eselon_iv_cpns' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja_cpns'");
						$neseloncpns5 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i_cpns' and c_eselon_ii='$c_eselon_ii_cpns' and c_eselon_iii='$c_eselon_iii_cpns' and c_eselon_iv='$c_eselon_iv_cpns' and c_eselon_v='$c_eselon_v_cpns' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja_cpns'");


						$c_lokasi_unitkerja=trim($result[$j]->c_lokasi_unitkerja);
						$c_eselon_i=trim($result[$j]->c_eselon_i);
						$c_eselon_ii=trim($result[$j]->c_eselon_ii);
						$c_eselon_iii=trim($result[$j]->c_eselon_iii);
						$c_eselon_iv=trim($result[$j]->c_eselon_iv);
						$c_eselon_v=trim($result[$j]->c_eselon_v);

						$neselon1 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_tkt_esl='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
						$neselon2 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");							
						$neselon3 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
						$neselon4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
						$neselon5 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_eselon_v='$c_eselon_v' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

						
						$data[$j] = array("i_peg_nip"=>(string)$result[$j]->i_peg_nip,
									"i_peg_nip_new"=>(string)$result[$j]->i_peg_nip_new,					
									"n_peg"=>(string)$result[$j]->n_peg,
									"n_peg_gelardepan"=>(string)$result[$j]->n_peg_gelardepan,
									"n_peg_gelarblkg"=>(string)$result[$j]->n_peg_gelarblkg,
									"c_peg_jeniskelamin"=>(string)$result[$j]->c_peg_jeniskelamin,
									"c_agama"=>(string)$result[$j]->c_agama,
									"c_golongan_darah"=>(string)$result[$j]->c_golongan_darah,
									"c_peg_statusnikah"=>(string)$result[$j]->c_peg_statusnikah,
									"n_peg_hobi"=>(string)$result[$j]->n_peg_hobi,
									"n_peg_hobi"=>(string)$result[$j]->n_peg_hobi,
									"n_pangkat"=>$n_pangkat,
									"d_peg_lahir"=>(string)$result[$j]->d_peg_lahir,
									"c_peg_propinsi_lahir"=>(string)$result[$j]->c_peg_propinsi_lahir,
									"a_peg_kota_lahir"=>(string)$result[$j]->a_peg_kota_lahir,
									"q_peg_tinggibdn"=>(string)$result[$j]->q_peg_tinggibdn,
									"q_peg_beratbdn"=>(string)$result[$j]->q_peg_beratbdn,
									"n_peg_rambut"=>(string)$result[$j]->n_peg_rambut,
									"n_peg_btkmuka"=>(string)$result[$j]->n_peg_btkmuka,
									"n_peg_warnakulit"=>(string)$result[$j]->n_peg_warnakulit,
									"n_peg_cirikhas"=>(string)$result[$j]->n_peg_cirikhas,
									"a_peg_rumah"=>(string)$result[$j]->a_peg_rumah,
									"a_peg_rt"=>(string)$result[$j]->a_peg_rt,
									"a_peg_rw"=>(string)$result[$j]->a_peg_rw,
									"a_peg_kelurahan"=>(string)$result[$j]->a_peg_kelurahan,
									"a_peg_kecamatan"=>(string)$result[$j]->a_peg_kecamatan,
									"a_peg_kota"=>(string)$result[$j]->a_peg_kota,
									"a_peg_propinsi"=>(string)$result[$j]->a_peg_propinsi,
									"a_peg_kodepos"=>(string)$result[$j]->a_peg_kodepos,
									"i_peg_telponrumah"=>(string)$result[$j]->i_peg_telponrumah,
									"i_peg_telponhp"=>(string)$result[$j]->i_peg_telponhp,
									"i_peg_karpeg"=>(string)$result[$j]->i_peg_karpeg,
									"i_peg_karis"=>(string)$result[$j]->i_peg_karis,
									"i_peg_taspen"=>(string)$result[$j]->i_peg_taspen,
									"i_peg_korpri"=>(string)$result[$j]->i_peg_korpri,
									"i_peg_ktp"=>(string)$result[$j]->i_peg_ktp,
									"i_peg_askes"=>(string)$result[$j]->i_peg_askes,
									"e_file_photo"=>(string)$result[$j]->e_file_photo,
									"c_stat_aktivasi"=>(string)$result[$j]->c_stat_aktivasi,
									"c_eselon"=>(string)$result[$j]->c_eselon,
									"n_eselon"=>$n_eselon,
									"d_sk_cpns"=>(string)$result[$j]->d_sk_cpns,
									"n_sk_pejabatcpns"=>(string)$result[$j]->n_sk_pejabatcpns,
									"i_sk_cpns"=>(string)$result[$j]->i_sk_cpns,
									"d_tmt_cpns"=>(string)$result[$j]->d_tmt_cpns,
									"c_gol_cpns"=>(string)$result[$j]->c_gol_cpns,
									"n_pangkat_cpns"=>$n_pangkat_cpns,							
									"c_eselon_cpns"=>(string)$result[$j]->c_eselon_cpns,
									"n_eselon_cpns"=>$n_eselon_cpns,
									"c_lokasi_unitkerja_cpns"=>(string)$result[$j]->c_lokasi_unitkerja_cpns,
									"c_eselon_i_cpns"=>(string)$result[$j]->c_eselon_i_cpns,
									"c_eselon_ii_cpns"=>(string)$result[$j]->c_eselon_ii_cpns,
									"c_eselon_iii_cpns"=>(string)$result[$j]->c_eselon_iii_cpns,
									"c_eselon_iv_cpns"=>(string)$result[$j]->c_eselon_iv_cpns,
									"c_eselon_v_cpns"=>(string)$result[$j]->c_eselon_v_cpns,
									"q_fiktif_cpns_thn"=>(string)$result[$j]->q_fiktif_cpns_thn,
									"q_fiktif_cpns_bln"=>(string)$result[$j]->q_fiktif_cpns_bln,
									"q_honorer_cpns_thn"=>(string)$result[$j]->q_honorer_cpns_thn,
									"q_honorer_cpns_bln"=>(string)$result[$j]->q_honorer_cpns_bln,
									"q_swasta_cpns_thn"=>(string)$result[$j]->q_swasta_cpns_thn,
									"q_swasta_cpns_bln"=>(string)$result[$j]->q_swasta_cpns_bln,
									"q_mktotal_cpns_thn"=>(string)$result[$j]->q_mktotal_cpns_thn,
									"q_mktotal_cpns_bln"=>(string)$result[$j]->q_mktotal_cpns_bln,
									"c_pend_cpns"=>(string)$result[$j]->c_pend_cpns,
									"c_jabatan_cpns"=>(string)$result[$j]->c_jabatan_cpns,
									"d_tmt_kerja"=>(string)$result[$j]->d_tmt_kerja,
									"c_status_kepegawaian"=>(string)$result[$j]->c_status_kepegawaian,
									"n_jabatan_cpns"=>$n_jabatan_cpns,
									"n_unitkerja_nokode"=>(string)$result[$j]->n_unitkerja_nokode,
									"i_sk_pns"=>(string)$result[$j]->i_sk_pns,
									"d_sk_pns"=>(string)$result[$j]->d_sk_pns,
									"n_sk_pejabatpns"=>(string)$result[$j]->n_sk_pejabatpns,
									"i_kesehatan_pns"=>(string)$result[$j]->i_kesehatan_pns,
									"d_kesehatan_pns"=>(string)$result[$j]->d_kesehatan_pns,
									"n_rumahsakit_pns"=>(string)$result[$j]->n_rumahsakit_pns,
									"n_kesehatan_pejabatpns"=>(string)$result[$j]->n_kesehatan_pejabatpns,
									"i_sk_prajab"=>(string)$result[$j]->i_sk_prajab,
									"d_sk_prajab"=>(string)$result[$j]->d_sk_prajab,
									"n_sk_pejabatprajab"=>(string)$result[$j]->n_sk_pejabatprajab,	
									"c_lokasi_unitkerja"=>(string)$result[$j]->c_lokasi_unitkerja,
									"c_eselon_i"=>trim($result[$j]->c_eselon_i),
									"c_eselon_ii"=>trim($result[$j]->c_eselon_ii),
									"c_eselon_iii"=>trim($result[$j]->c_eselon_iii),
									"c_eselon_iv"=>trim($result[$j]->c_eselon_iv),
									"c_eselon_v"=>trim($result[$j]->c_eselon_v),
									"c_peg_status"=>trim($result[$j]->c_peg_status),									
									"neseloncpns1"=>$neseloncpns1,
									"neseloncpns2"=>$neseloncpns2,
									"neseloncpns3"=>$neseloncpns3,
									"neseloncpns4"=>$neseloncpns4,
									"neseloncpns5"=>$neseloncpns5, 
									"neselon1"=>$neselon1,
									"neselon2"=>$neselon2,
									"neselon3"=>$neselon3,
									"neselon4"=>$neselon4,
									"neselon5"=>$neselon5, 										
									"i_entry"=>(string)$result[$j]->i_entry,
									"d_entry"=>(string)$result[$j]->d_entry);	
					}
									
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}

	function getcgolongannext($c_golongan) 
	{
				if ($c_golongan == '01') {
					$c_golongan_next='02';
				} elseif ($c_golongan == '02') {
					$c_golongan_next='03';
				} elseif ($c_golongan == '03') {
					$c_golongan_next='04';
				}
				 elseif ($c_golongan == '03') {
					$c_golongan_next='04';
				}
				 elseif ($c_golongan == '04') {
					$c_golongan_next='05';
				}
				 elseif ($c_golongan == '05') {
					$c_golongan_next='06';
				}
				 elseif ($c_golongan == '06') {
					$c_golongan_next='07';
				}
				 elseif ($c_golongan == '07') {
					$c_golongan_next='08';
				}
				 elseif ($c_golongan == '08') {
					$c_golongan_next='09';
				}
				 elseif ($c_golongan == '09') {
					$c_golongan_next='10';
				}
				else {$c_golongan_next=$c_golongan*1-1;}	
		return $c_golongan_next;
	}
	
 	public function getJmlDataPeg($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{		
				
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchOne("SELECT count(*) i_peg_nip FROM sdm.tm_pegawai where 1=1 $cari");
					return $result;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return '0';
			}
	}	

}
?>