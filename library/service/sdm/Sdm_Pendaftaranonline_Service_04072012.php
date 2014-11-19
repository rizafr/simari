<?php
class Sdm_Pendaftaranonline_Service {
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

public function getJabatanUsulan() 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("select b.c_jabatan_usul,b.n_surat,b.n_jabatan_usul ,b.c_pend_usul,b.c_kualifikasi_pend
									FROM sdm.tm_cpns_usul a, sdm.tm_cpns_usuljabatan b
									where a.n_surat=b.n_surat and c_aktivasi='1'  order by n_jabatan_usul asc");		
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 

						$data[$j] = array("n_surat"=>$result[$j]->n_surat,
								"n_jabatan_usul"=>(string)$result[$j]->n_jabatan_usul,
								"c_jabatan_usul"=>$result[$j]->c_jabatan_usul,
								"c_pend_usul"=>$result[$j]->c_pend_usul,
								"c_kualifikasi_pend"=>$result[$j]->c_kualifikasi_pend);									
					}
										
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}    
public function cekPassword($cari) 
{
      $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {  
        $xLimit=$numToDisplay;
        $xOffset=($currentPage-1)*$numToDisplay;        
        $db->setFetchMode(Zend_Db::FETCH_OBJ);      
	$result = $db->fetchAll("select i_ktp,n_password,n_userid from sdm.tm_daftaronline where 1=1 $cari");
	$jmlResult = count($result);
	for ($j = 0; $j < $jmlResult; $j++) 
	{            
		$data[$j] = array("i_ktp"=>(string)$result[$j]->i_ktp,"n_password"=>(string)$result[$j]->n_password,
				"n_userid"=>(string)$result[$j]->n_userid,"n_pendaftar"=>(string)$result[$j]->n_pendaftar);
	}
	return $data;
      } catch (Exception $e) 
      {
		echo $e->getMessage().'<br>';
		return 'Data tidak ada <br>';
      }
}

    
public function cekKtp($cari) 
{
      $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {  
        $xLimit=$numToDisplay;
        $xOffset=($currentPage-1)*$numToDisplay;        
        $db->setFetchMode(Zend_Db::FETCH_OBJ);      
	$result = $db->fetchAll("select i_ktp from sdm.tm_daftaronline where 1=1 $cari");
	$data = count($result);
	return $data;
      } catch (Exception $e) 
      {
               echo $e->getMessage().'<br>';
             return 'Data tidak ada <br>';
      }
}
  
public function cekUserid($cari) 
{
      $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {  
        $xLimit=$numToDisplay;
        $xOffset=($currentPage-1)*$numToDisplay;        
        $db->setFetchMode(Zend_Db::FETCH_OBJ);      
	$result = $db->fetchAll("select n_userid from sdm.tm_daftaronline where 1=1 $cari");
	$data = count($result);
	return $data;
      } catch (Exception $e) 
      {
               echo $e->getMessage().'<br>';
             return 'Data tidak ada <br>';
      }
}


  
public function setnUserId($cmodul) 
{
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
		try 	{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);
				$where[] = $cmodul;
				$result = $db->fetchOne('SELECT sdm.gen_tpmlist(?)',$where);
				return $result;
			} catch (Exception $e) {
				$db->rollBack();
				echo $e->getMessage().'<br>';
				return  0;
		   }
}  
  public function maintainDataReg(array $data,$par) {
     $registry = Zend_Registry::getInstance();
     $db = $registry->get('db');
     try {
       $db->beginTransaction();

       $maintain_data = array("i_ktp"=>$data['i_ktp'],     
				"n_pendaftar"=>$data['n_pendaftar'],
				"c_jeniskelamin"=>$data['c_jeniskelamin'],
				"c_agama"=>$data['c_agama'],
				"c_golongan_darah"=>$data['c_golongan_darah'],
				"c_statusnikah"=>$data['c_statusnikah'],
				"n_hobi"=>$data['n_hobi'],
				"d_lahir"=>$data['d_lahir'],
				"c_propinsi_lahir"=>$data['c_propinsi_lahir'],
				"a_kota_lahir"=>$data['a_kota_lahir'],
				"q_tinggibdn"=>$data['q_tinggibdn'],
				"q_beratbdn"=>$data['q_beratbdn'],
				"n_rambut"=>$data['n_rambut'],
				"n_btkmuka"=>$data['n_btkmuka'],
				"n_warnakulit"=>$data['n_warnakulit'],
				"n_cirikhas"=>$data['n_cirikhas'],
				"a_rumah"=>$data['a_rumah'],
				"a_rt"=>$data['a_rt'],
				"a_rw"=>$data['a_rw'],
				"a_kelurahan"=>$data['a_kelurahan'],
				"a_kecamatan"=>$data['a_kecamatan'],
				"a_kota"=>$data['a_kota'],
				"a_propinsi"=>$data['a_propinsi'],
				"a_kodepos"=>$data['a_kodepos'],
				"i_telponrumah"=>$data['i_telponrumah'],
				"i_telponhp"=>$data['i_telponhp'],
				"a_email"=>$data['a_email'],
				"c_pend"=>$data['c_pend'],
				"d_pend_akhir"=>$data['d_pend_akhir'],
				"d_pend_ijazah"=>$data['d_pend_ijazah'],
				"d_pend_mulai"=>$data['d_pend_mulai'],
				"i_pend_ipk"=>$data['i_pend_ipk'],
				"i_pend_ijazah"=>$data['i_pend_ijazah'],
				"n_pend_jurusan"=>$data['n_pend_jurusan'],
				"c_warganegara"=>$data['c_warganegara'],
				"n_pend_lembaga"=>$data['n_pend_lembaga'],
				"c_pend_akreditasi"=>$data['c_pend_akreditasi'],
				"c_posisi_jabatan"=>$data['c_posisi_jabatan'],
				"c_wil_pengadilan"=>$data['c_wil_pengadilan'],
				"e_file_photo"=>$data['e_file_photo'],
				"n_password"=>$data['n_password'],
				"n_userid"=>$data['n_userid'],
				"n_register"=>$data['n_register'],
				"d_register"=>$data['d_register'],
				"n_surat_usuljabat"=>$data['n_surat_usuljabat'],
				"d_entry"=>$data['d_entry']);
	
    if ($par=='insert'){$db->insert('sdm.tm_daftaronline',$maintain_data);}
    //if ($par=='update'){$db->update('sdm.tm_daftaronline',$maintain_data, "i_ktp = '".trim($data['i_ktp'])."'");}   
    
    $db->commit();
       return 'sukses';
     } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
       return 'gagal';
     }
  }
  
  
 public function maintainData(array $data,$par) {
     $registry = Zend_Registry::getInstance();
     $db = $registry->get('db');
     try {
       $db->beginTransaction();

       $maintain_data = array("i_ktp"=>$data['i_ktp'],     
				"n_pendaftar"=>$data['n_pendaftar'],
				"c_jeniskelamin"=>$data['c_jeniskelamin'],
				"c_agama"=>$data['c_agama'],
				"c_golongan_darah"=>$data['c_golongan_darah'],
				"c_statusnikah"=>$data['c_statusnikah'],
				"n_hobi"=>$data['n_hobi'],
				"d_lahir"=>$data['d_lahir'],
				"c_propinsi_lahir"=>$data['c_propinsi_lahir'],
				"a_kota_lahir"=>$data['a_kota_lahir'],
				"q_tinggibdn"=>$data['q_tinggibdn'],
				"q_beratbdn"=>$data['q_beratbdn'],
				"n_rambut"=>$data['n_rambut'],
				"n_btkmuka"=>$data['n_btkmuka'],
				"n_warnakulit"=>$data['n_warnakulit'],
				"n_cirikhas"=>$data['n_cirikhas'],
				"a_rumah"=>$data['a_rumah'],
				"a_rt"=>$data['a_rt'],
				"a_rw"=>$data['a_rw'],
				"a_kelurahan"=>$data['a_kelurahan'],
				"a_kecamatan"=>$data['a_kecamatan'],
				"a_kota"=>$data['a_kota'],
				"a_propinsi"=>$data['a_propinsi'],
				"a_kodepos"=>$data['a_kodepos'],
				"i_telponrumah"=>$data['i_telponrumah'],
				"i_telponhp"=>$data['i_telponhp'],
				"a_email"=>$data['a_email'],
				"c_pend"=>$data['c_pend'],
				"d_pend_akhir"=>$data['d_pend_akhir'],
				"d_pend_ijazah"=>$data['d_pend_ijazah'],
				"d_pend_mulai"=>$data['d_pend_mulai'],
				"i_pend_ipk"=>$data['i_pend_ipk'],
				"i_pend_ijazah"=>$data['i_pend_ijazah'],
				"n_pend_jurusan"=>$data['n_pend_jurusan'],
				"c_warganegara"=>$data['c_warganegara'],
				"n_pend_lembaga"=>$data['n_pend_lembaga'],
				"c_pend_akreditasi"=>$data['c_pend_akreditasi'],
				"c_posisi_jabatan"=>$data['c_posisi_jabatan'],
				"c_wil_pengadilan"=>$data['c_wil_pengadilan'],
				"c_satker"=>$data['c_satker'],
				"e_file_photo"=>$data['e_file_photo'],
				"n_register"=>$data['n_register'],
				"n_surat_usuljabat"=>$data['n_surat_usuljabat'],
				"d_entry"=>$data['d_entry']);
	
    if ($par=='update'){$db->update('sdm.tm_daftaronline',$maintain_data, "i_ktp = '".trim($data['i_ktp'])."' and n_password = '".trim($data['n_password'])."'");}   
    
    $db->commit();
       return 'sukses';
     } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
       return 'gagal';
     }
  }  
  
   public function getPendaftarList($cari) 
  {
      $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {
  
        $xLimit=$numToDisplay;
        $xOffset=($currentPage-1)*$numToDisplay;        
        $db->setFetchMode(Zend_Db::FETCH_OBJ);      
          $result = $db->fetchAll("SELECT i_nip,i_nrp, n_peg,c_status,c_status_kepegawaian,c_eselon,to_char(d_tmt_eselon,'dd-mm-yyyy') as d_tmt_eselon,
                c_jabatan,to_char(d_mulai_jabat,'dd-mm-yyyy') as d_mulai_jabat,c_jeniskelamin,
                to_char(d_akhir_jabat,'dd-mm-yyyy') as d_akhir_jabat,c_golongan,d_tmt_golongan,v_gaji_pokok,
                c_lokasi_unitkerja,c_eselon_i,c_eselon_ii,c_eselon_iii,c_eselon_iv,c_eselon_v,c_gol_cpns,
                c_lokasi_unitkerja_cpns,c_eselon_cpns,e_file_photo,c_eselon_cpns,c_lokasi_unitkerja_cpns,c_eselon_i_cpns,
                c_eselon_ii_cpns,c_eselon_iii_cpns,c_eselon_iv_cpns,c_eselon_v_cpns,c_jabatan_cpns,to_char(d_tmt_kgb,'dd-mm-yyyy') as d_tmt_kgb,
                n_pend_lembaga,c_pend_akreditasi,c_posisi_jabatan,c_wil_pengadilan
                FROM sdm.tm_daftaronline where 1=1 $cari");
                  
          $jmlResult = count($result);
          for ($j = 0; $j < $jmlResult; $j++) 
          {



            
    $data[$j] = array("i_nip"=>(string)$result[$j]->i_nip,
      "i_nrp"=>(string)$result[$j]->i_nrp,
      "n_peg"=>(string)$result[$j]->n_peg,
      "c_status"=>(string)$result[$j]->c_status,
      "n_status_kepegawaian"=>$n_status_kepegawaian,      
      "n_status"=>$n_status,                      
      "n_jabatan"=>$n_jabatan,
      "n_eselon"=>$n_eselon,
      "n_eselon_cpns"=>$n_eselon_cpns,
      "n_lokasi_unitkerja"=>$n_lokasi_unitkerja,
      "c_golongan"=>$c_golongan,
      "n_golongan"=>$n_golongan,
      "n_pangkat"=>$n_pangkat,
      "c_jeniskelamin"=>(string)$result[$j]->c_jeniskelamin,
      "e_file_photo"=>(string)$result[$j]->e_file_photo,
      "unitkerjalengkap"=>$unitkerjalengkap,
      "n_pangkat_cpns"=>$n_pangkat_cpns,
      "d_tmt_kgb"=>(string)$result[$j]->d_tmt_kgb);  
  }
                    
      return $data;
      } catch (Exception $e) 
      {
               echo $e->getMessage().'<br>';
             return 'Data tidak ada <br>';
      }
  }




  
   public function getPegawaiListByKtp($cari) 
  {
      $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {    
        
        $db->setFetchMode(Zend_Db::FETCH_OBJ);      
          $result = $db->fetchAll("SELECT i_ktp,n_pendaftar,c_jeniskelamin,
                c_agama,c_golongan_darah,c_statusnikah,n_hobi,to_char(d_lahir,'dd-mm-yyyy') as d_lahir,d_lahir as d_lahirx,c_propinsi_lahir,
                a_kota_lahir,q_tinggibdn,q_beratbdn,n_rambut,n_btkmuka,n_warnakulit,
                n_cirikhas,a_rumah,a_rt,a_rw,a_kelurahan,a_kecamatan,a_kota,
                a_propinsi,a_kodepos,i_telponrumah,i_telponhp,a_email,c_pend,n_pend_jurusan,
                d_pend_mulai,d_pend_akhir,i_pend_ipk,c_warganegara,
                i_pend_ijazah,to_char(d_pend_ijazah,'dd-mm-yyyy') as d_pend_ijazah,
                n_pend_lembaga,c_pend_akreditasi,c_posisi_jabatan,c_wil_pengadilan,e_file_photo,n_register,n_surat_usuljabat,n_password,c_satker
                FROM sdm.tm_daftaronline where 1=1 $cari");
          $jmlResult = count($result);
          for ($j = 0; $j < $jmlResult; $j++) 
          { 
            $c_pendidikan=(string)$result[$j]->c_pend;
            $n_pendidikan= $db->fetchOne("SELECT n_pend from sdm.tr_pendidikan where c_pend='$c_pendidikan'");
            $a_kota=(string)$result[$j]->a_kota;
            $n_kota= $db->fetchOne("SELECT n_kabupaten from sdm.tr_kabupaten where c_kabupaten='$a_kota'");
            $a_propinsi=(string)$result[$j]->a_propinsi;
            $n_propinsi= $db->fetchOne("SELECT n_propinsi from sdm.tr_propinsi where c_propinsi='$a_propinsi'");
            
            $c_posisi_jabatan=trim((string)$result[$j]->c_posisi_jabatan);
            $n_posisi_jabatan= $db->fetchOne("SELECT n_jabatan_usul from sdm.tm_cpns_usuljabatan where c_jabatan_usul='$c_posisi_jabatan'");
            //$c_wilayah=(string)$result[$j]->c_wil_pengadilan;
            //$n_wil_pengadilan= $db->fetchOne("SELECT n_wilayah from sdm.tr_wil_pengadilan where c_wilayah='$c_wilayah'");

            $c_pengadilan=(string)$result[$j]->c_wil_pengadilan;
            $pengadilan= $db->fetchAll("SELECT n_pengadilan, n_wilayah from sdm.tr_wil_pengadilan where c_pengadilan='$c_pengadilan'");
            $n_pengadilan=$pengadilan[0]->n_pengadilan;
            $n_wil_pengadilan=$pengadilan[0]->n_wilayah;  

            $c_agama=trim((string)$result[$j]->c_agama);
            $n_agama= $db->fetchOne("SELECT n_agama from sdm.tr_agama where c_agama='$c_agama'");	    

		$d_lahirx=$result[$j]->d_lahirx;
		$usiatahun=$db->fetchOne("SELECT EXTRACT(years from AGE(NOW(), '$d_lahirx')) as age");
		$usiabulan=$db->fetchOne("SELECT EXTRACT(month from AGE(NOW(), '$d_lahirx')) as age");
		$usiathn=$usiatahun." Tahun";
		if ($usiabulan){$usia=$usiathn." ".$usiabulan." Bulan";}
		else{$usia=$usiathn;}
		
		$n_pend_jurusan	=trim($result[$j]->n_pend_jurusan);
		$n_kualifikasi_pend= $db->fetchOne("SELECT c_kualifikasi_pend from sdm.tr_kulifikasi_pendidikan where n_kualifikasi_pend='$n_pend_jurusan'");
            $data[$j] = array(  
			  "usia"=>$usia,          
			  "i_ktp"=>(string)$result[$j]->i_ktp,      
			 "n_password"=>(string)$result[$j]->n_password,
			  "n_pendaftar"=>(string)$result[$j]->n_pendaftar,
			  "c_jeniskelamin"=>(string)$result[$j]->c_jeniskelamin,
			  "c_agama"=>(string)$result[$j]->c_agama,
			  "n_agama"=>$n_agama,
			  "c_golongan_darah"=>(string)$result[$j]->c_golongan_darah,
			  "c_statusnikah"=>(string)$result[$j]->c_statusnikah,
			  "n_hobi"=>(string)$result[$j]->n_hobi,
			  "n_hobi"=>(string)$result[$j]->n_hobi,
			  "d_lahir"=>(string)$result[$j]->d_lahir,
			  "c_propinsi_lahir"=>(string)$result[$j]->c_propinsi_lahir,
			  "a_kota_lahir"=>(string)$result[$j]->a_kota_lahir,
			  "q_tinggibdn"=>(string)$result[$j]->q_tinggibdn,
			  "q_beratbdn"=>(string)$result[$j]->q_beratbdn,
			  "n_rambut"=>(string)$result[$j]->n_rambut,
			  "n_btkmuka"=>(string)$result[$j]->n_btkmuka,
			  "n_warnakulit"=>(string)$result[$j]->n_warnakulit,
			  "n_cirikhas"=>(string)$result[$j]->n_cirikhas,
			  "a_rumah"=>(string)$result[$j]->a_rumah,
			  "a_rt"=>(string)$result[$j]->a_rt,
			  "a_rw"=>(string)$result[$j]->a_rw,
			  "a_kelurahan"=>(string)$result[$j]->a_kelurahan,
			  "a_kecamatan"=>(string)$result[$j]->a_kecamatan,
			  "a_kota"=>(string)$result[$j]->a_kota,
			  "n_kota"=>$n_kota,                  
			  "a_propinsi"=>(string)$result[$j]->a_propinsi,
			  "n_propinsi"=>$n_propinsi,
			  "a_kodepos"=>(string)$result[$j]->a_kodepos,
			  "i_telponrumah"=>(string)$result[$j]->i_telponrumah,
			  "i_telponhp"=>(string)$result[$j]->i_telponhp,
			  "a_email"=>(string)$result[$j]->a_email,
			  "c_pend"=>(string)$result[$j]->c_pend,
			  "n_pendidikan"=>$n_pendidikan,
			  "n_pend_jurusan"=>(string)$result[$j]->n_pend_jurusan,
			  "d_pend_mulai"=>(string)$result[$j]->d_pend_mulai,
			  "d_pend_akhir"=>(string)$result[$j]->d_pend_akhir,
			  "i_pend_ipk"=>(string)$result[$j]->i_pend_ipk,
			  "i_pend_ijazah"=>(string)$result[$j]->i_pend_ijazah,
			  "d_pend_ijazah"=>(string)$result[$j]->d_pend_ijazah,
			  "c_warganegara"=>(string)$result[$j]->c_warganegara,
			  "n_pend_lembaga"=>(string)$result[$j]->n_pend_lembaga,
			  "c_pend_akreditasi"=>(string)$result[$j]->c_pend_akreditasi,
			  "c_posisi_jabatan"=>(string)$result[$j]->c_posisi_jabatan,
			  "c_wil_pengadilan"=>(string)$result[$j]->c_wil_pengadilan,
			  "n_posisi_jabatan"=>$n_posisi_jabatan,
			  //"n_posisi_jabatan"=>(string)$result[$j]->c_posisi_jabatan,
			  "n_wil_pengadilan"=>$n_wil_pengadilan,
			  "e_file_photo"=>(string)$result[$j]->e_file_photo,  
			"n_register"=>(string)$result[$j]->n_register,
			"n_surat_usuljabat"=>(string)$result[$j]->n_surat_usuljabat,
			"c_satker"=>(string)$result[$j]->c_satker,
			"n_kualifikasi_pend"=>$n_kualifikasi_pend,	
			"d_entry"=>(string)$result[$j]->d_entry);
          }
  
          return $data;
      } catch (Exception $e) 
      {
               echo $e->getMessage().'<br>';
             return 'Data tidak ada <br>';
      }
  }

  public function insertNoDaftar(array $data,$par) {
     $registry = Zend_Registry::getInstance();
     $db = $registry->get('db');
     try {
       $db->beginTransaction();

       $maintain_data = array("i_ktp"=>$data['i_ktp'],     
        "d_tahun"=>$data['d_tahun'],
        "q_nomor_daftar"=>$data['q_nomor_daftar'],
        "c_hasil"=>$data['c_hasil']*1);
    if ($par=='insert'){$db->insert('sdm.tm_nomor_pendaftaran',$maintain_data);}
    if ($par=='update'){$db->update('sdm.tm_nomor_pendaftaran',$maintain_data, "i_ktp = '".trim($data['i_ktp'])."' and d_tahun = '".trim($data['d_tahun'])."'");}       
    
    $db->commit();
       return 'sukses';
     } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
       return 'gagal';
     }
  }
  
  public function updateHasil(array $data,$par) {
     $registry = Zend_Registry::getInstance();
     $db = $registry->get('db');
     try {
       $db->beginTransaction();
       $maintain_data = array("c_hasil"=>$data['c_hasil']*1);
       if ($par=='hasil'){$db->update('sdm.tm_nomor_pendaftaran',$maintain_data, "q_nomor_daftar = '".trim($data['q_nomor_daftar'])."'");}
    
    $db->commit();
       return 'sukses';
     } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
       return 'gagal';
     }
  }
  
   
  public function getNomorDaftar($cari) 
  {
      $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {  
        $db->setFetchMode(Zend_Db::FETCH_OBJ);      
          $result = $db->fetchOne("SELECT q_nomor_daftar FROM sdm.tm_nomor_pendaftaran where 1=1 $cari");
          return $result;
      } catch (Exception $e) 
      {
               echo $e->getMessage().'<br>';
             return 'Data tidak ada <br>';
      }
  } 
  


  public function setNomorDaftar(array $data) 
  {
       $registry = Zend_Registry::getInstance();
       $db = $registry->get('db');
        try {
         $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $where[] = $data['i_ktp'];
        $where[] = $data['kdwil'];
        $where[] = $data['kdjab'];
        $result = $db->fetchOne('SELECT sdm.gen_nopendaftaran(?,?,?)',$where);
         return $result;
       } catch (Exception $e) {
     $db->rollBack();
     echo $e->getMessage().'<br>';
         return  0;
       }
  }
  
   public function getPegawaiListByNodaf($cari) 
  {
      $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {    
        
        $db->setFetchMode(Zend_Db::FETCH_OBJ);      
          $result = $db->fetchAll("SELECT a.i_ktp,a.n_pendaftar,a.c_jeniskelamin,
                a.c_agama,a.c_golongan_darah,a.c_statusnikah,a.n_hobi,to_char(a.d_lahir,'dd-mm-yyyy') as d_lahir,a.c_propinsi_lahir,
                a.a_kota_lahir,a.q_tinggibdn,a.q_beratbdn,a.n_rambut,a.n_btkmuka,a.n_warnakulit,
                a.n_cirikhas,a.a_rumah,a.a_rt,a.a_rw,a.a_kelurahan,a.a_kecamatan,a.a_kota,
                a.a_propinsi,a.a_kodepos,a.i_telponrumah,a.i_telponhp,a.a_email,a.c_pend,a.n_pend_jurusan,
                a.d_pend_mulai,a.d_pend_akhir,a.i_pend_ipk,a.c_warganegara,n_pend_lembaga,c_pend_akreditasi,c_posisi_jabatan,
                a.i_pend_ijazah,to_char(a.d_pend_ijazah,'dd-mm-yyyy') as d_pend_ijazah,
                q_nomor_daftar,c_hasil,c_posisi_jabatan,c_wil_pengadilan,n_surat_usuljabat,e_file_photo
                FROM sdm.tm_daftaronline a, sdm.tm_nomor_pendaftaran b where a.i_ktp = b.i_ktp $cari");


                
          $jmlResult = count($result);
          for ($j = 0; $j < $jmlResult; $j++) 
          { 
            $c_pendidikan=(string)$result[$j]->c_pend;
            $n_pendidikan= $db->fetchOne("SELECT n_pend from sdm.tr_pendidikan where c_pend='$c_pendidikan'");
            $a_kota=(string)$result[$j]->a_kota;
            $n_kota= $db->fetchOne("SELECT n_kabupaten from sdm.tr_kabupaten where c_kabupaten='$a_kota'");
            $a_propinsi=(string)$result[$j]->a_propinsi;
            $n_propinsi= $db->fetchOne("SELECT n_propinsi from sdm.tr_propinsi where c_propinsi='$a_propinsi'");
            
            $c_pengadilan=(string)$result[$j]->c_wil_pengadilan;
            $pengadilan= $db->fetchAll("SELECT n_pengadilan, n_wilayah from sdm.tr_wil_pengadilan where c_pengadilan='$c_pengadilan'");
            $n_pengadilan=$pengadilan[0]->n_pengadilan;
            $n_wilayah=$pengadilan[0]->n_wilayah;
	    $c_agama=(string)$result[$j]->c_agama;
	    $n_agama= $db->fetchOne("SELECT n_agama from sdm.tr_agama where c_agama='$c_agama'");
	    
            $a_kota_lahir=trim((string)$result[$j]->a_kota_lahir);
            $n_kota_lahir= $db->fetchOne("SELECT n_kabupaten from sdm.tr_kabupaten where c_kabupaten='$a_kota_lahir'");
            $c_propinsi_lahir=trim((string)$result[$j]->c_propinsi_lahir);
            $n_propinsi_lahir= $db->fetchOne("SELECT n_propinsi from sdm.tr_propinsi where c_propinsi='$c_propinsi_lahir'");	    
 
            //$c_posisi_jabatan=(string)$result[$j]->c_posisi_jabatan;
            //$n_posisi_jabatan= $db->fetchOne("SELECT n_jabatan from sdm.tr_jabatan_calon where c_jabatan='$c_posisi_jabatan'");

            $data[$j] = array(  "q_nomor_daftar"=>(string)$result[$j]->q_nomor_daftar,
                  "c_hasil"=>(string)$result[$j]->c_hasil,
                  "i_ktp"=>(string)$result[$j]->i_ktp,          
                  "n_pendaftar"=>(string)$result[$j]->n_pendaftar,
                  "c_jeniskelamin"=>(string)$result[$j]->c_jeniskelamin,
                  "c_agama"=>(string)$result[$j]->c_agama,
		  "n_agama"=>$n_agama,
                  "c_golongan_darah"=>(string)$result[$j]->c_golongan_darah,
                  "c_statusnikah"=>(string)$result[$j]->c_statusnikah,
                  "n_hobi"=>(string)$result[$j]->n_hobi,
                  "n_hobi"=>(string)$result[$j]->n_hobi,
                  "d_lahir"=>(string)$result[$j]->d_lahir,
                  "c_propinsi_lahir"=>(string)$result[$j]->c_propinsi_lahir,
                  "a_kota_lahir"=>(string)$result[$j]->a_kota_lahir,
		  "n_propinsi_lahir"=>$n_propinsi_lahir,
                  "n_kota_lahir"=>$n_kota_lahir,
                  "q_tinggibdn"=>(string)$result[$j]->q_tinggibdn,
                  "q_beratbdn"=>(string)$result[$j]->q_beratbdn,
                  "n_rambut"=>(string)$result[$j]->n_rambut,
                  "n_btkmuka"=>(string)$result[$j]->n_btkmuka,
                  "n_warnakulit"=>(string)$result[$j]->n_warnakulit,
                  "n_cirikhas"=>(string)$result[$j]->n_cirikhas,
                  "a_rumah"=>(string)$result[$j]->a_rumah,
                  "a_rt"=>(string)$result[$j]->a_rt,
                  "a_rw"=>(string)$result[$j]->a_rw,
                  "a_kelurahan"=>(string)$result[$j]->a_kelurahan,
                  "a_kecamatan"=>(string)$result[$j]->a_kecamatan,
                  "a_kota"=>(string)$result[$j]->a_kota,
                  "n_kota"=>$n_kota,                  
                  "a_propinsi"=>(string)$result[$j]->a_propinsi,
                  "n_propinsi"=>$n_propinsi,
                  "a_kodepos"=>(string)$result[$j]->a_kodepos,
                  "i_telponrumah"=>(string)$result[$j]->i_telponrumah,
                  "i_telponhp"=>(string)$result[$j]->i_telponhp,
                  "a_email"=>(string)$result[$j]->a_email,
                  "c_pend"=>(string)$result[$j]->c_pend,
                  "n_pendidikan"=>$n_pendidikan,
                  "n_pend_jurusan"=>(string)$result[$j]->n_pend_jurusan,
                  "d_pend_mulai"=>(string)$result[$j]->d_pend_mulai,
                  "d_pend_akhir"=>(string)$result[$j]->d_pend_akhir,
                  "i_pend_ipk"=>(string)$result[$j]->i_pend_ipk,
                  "i_pend_ijazah"=>(string)$result[$j]->i_pend_ijazah,
                  "d_pend_ijazah"=>(string)$result[$j]->d_pend_ijazah,
                  "c_warganegara"=>(string)$result[$j]->c_warganegara,  
                  "n_pend_lembaga"=>(string)$result[$j]->n_pend_lembaga,
                  "c_pend_akreditasi"=>(string)$result[$j]->c_pend_akreditasi,
                  "c_posisi_jabatan"=>(string)$result[$j]->c_posisi_jabatan,
		   "n_surat_usuljabat"=>(string)$result[$j]->n_surat_usuljabat,		  
                  //"n_posisi_jabatan"=>$n_posisi_jabatan,
		  "n_posisi_jabatan"=>(string)$result[$j]->c_posisi_jabatan,
                  "n_pengadilan"=>$n_pengadilan,
                  "n_wilayah"=>$n_wilayah,  
		  "e_file_photo"=>(string)$result[$j]->e_file_photo, 
                  "d_entry"=>(string)$result[$j]->d_entry);
          }
  
          return $data;
      } catch (Exception $e) 
      {
               echo $e->getMessage().'<br>';
             return 'Data tidak ada <br>';
      }
  }


   public function getDaftar($cari,$currentPage, $numToDisplay) 
  {
      $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {
        if(($currentPage==0) && ($numToDisplay==0))
        {
          $data = $db->fetchOne("SELECT count(*)
                FROM sdm.tm_daftaronline a, sdm.tm_nomor_pendaftaran b where a.i_ktp = b.i_ktp $cari");
                

    
        }
        else    
        {      
        $xLimit=$numToDisplay;
        $xOffset=($currentPage-1)*$numToDisplay;
        $db->setFetchMode(Zend_Db::FETCH_OBJ);      
          $result = $db->fetchAll("SELECT a.i_ktp,a.n_pendaftar,a.c_jeniskelamin,
                a.c_agama,a.c_golongan_darah,a.c_statusnikah,a.n_hobi,to_char(a.d_lahir,'dd-mm-yyyy') as d_lahir,a.c_propinsi_lahir,
                a.a_kota_lahir,a.q_tinggibdn,a.q_beratbdn,a.n_rambut,a.n_btkmuka,a.n_warnakulit,
                a.n_cirikhas,a.a_rumah,a.a_rt,a.a_rw,a.a_kelurahan,a.a_kecamatan,a.a_kota,
                a.a_propinsi,a.a_kodepos,a.i_telponrumah,a.i_telponhp,a.a_email,a.c_pend,a.n_pend_jurusan,
                a.d_pend_mulai,a.d_pend_akhir,a.i_pend_ipk,a.c_warganegara,n_pend_lembaga,c_pend_akreditasi,c_posisi_jabatan,
                a.i_pend_ijazah,to_char(a.d_pend_ijazah,'dd-mm-yyyy') as d_pend_ijazah,
                q_nomor_daftar,c_hasil,c_posisi_jabatan,c_wil_pengadilan,n_surat_usuljabat
                FROM sdm.tm_daftaronline a, sdm.tm_nomor_pendaftaran b where a.i_ktp = b.i_ktp $cari order by c_wil_pengadilan asc,c_posisi_jabatan asc limit $xLimit offset $xOffset ");


                
          $jmlResult = count($result);
          for ($j = 0; $j < $jmlResult; $j++) 
          { 
            $c_pendidikan=(string)$result[$j]->c_pend;
            $n_pendidikan= $db->fetchOne("SELECT n_pend from sdm.tr_pendidikan where c_pend='$c_pendidikan'");
            $a_kota=(string)$result[$j]->a_kota;
            $n_kota= $db->fetchOne("SELECT n_kabupaten from sdm.tr_kabupaten where c_kabupaten='$a_kota'");
            $a_propinsi=(string)$result[$j]->a_propinsi;
            $n_propinsi= $db->fetchOne("SELECT n_propinsi from sdm.tr_propinsi where c_propinsi='$a_propinsi'");
            
            $c_pengadilan=(string)$result[$j]->c_wil_pengadilan;
            $pengadilan= $db->fetchAll("SELECT n_pengadilan, n_wilayah from sdm.tr_wil_pengadilan where c_pengadilan='$c_pengadilan'");
            $n_pengadilan=$pengadilan[0]->n_pengadilan;
            $n_wilayah=$pengadilan[0]->n_wilayah;
            
            //$c_posisi_jabatan=(string)$result[$j]->c_posisi_jabatan;
            //$n_posisi_jabatan= $db->fetchOne("SELECT n_jabatan from sdm.tr_jabatan_calon where c_jabatan='$c_posisi_jabatan'");

            $data[$j] = array(  "q_nomor_daftar"=>(string)$result[$j]->q_nomor_daftar,
                  "c_hasil"=>(string)$result[$j]->c_hasil,
                  "i_ktp"=>(string)$result[$j]->i_ktp,          
                  "n_pendaftar"=>(string)$result[$j]->n_pendaftar,
                  "c_jeniskelamin"=>(string)$result[$j]->c_jeniskelamin,
                  "c_agama"=>(string)$result[$j]->c_agama,
                  "c_golongan_darah"=>(string)$result[$j]->c_golongan_darah,
                  "c_statusnikah"=>(string)$result[$j]->c_statusnikah,
                  "n_hobi"=>(string)$result[$j]->n_hobi,
                  "n_hobi"=>(string)$result[$j]->n_hobi,
                  "d_lahir"=>(string)$result[$j]->d_lahir,
                  "c_propinsi_lahir"=>(string)$result[$j]->c_propinsi_lahir,
                  "a_kota_lahir"=>(string)$result[$j]->a_kota_lahir,
                  "q_tinggibdn"=>(string)$result[$j]->q_tinggibdn,
                  "q_beratbdn"=>(string)$result[$j]->q_beratbdn,
                  "n_rambut"=>(string)$result[$j]->n_rambut,
                  "n_btkmuka"=>(string)$result[$j]->n_btkmuka,
                  "n_warnakulit"=>(string)$result[$j]->n_warnakulit,
                  "n_cirikhas"=>(string)$result[$j]->n_cirikhas,
                  "a_rumah"=>(string)$result[$j]->a_rumah,
                  "a_rt"=>(string)$result[$j]->a_rt,
                  "a_rw"=>(string)$result[$j]->a_rw,
                  "a_kelurahan"=>(string)$result[$j]->a_kelurahan,
                  "a_kecamatan"=>(string)$result[$j]->a_kecamatan,
                  "a_kota"=>(string)$result[$j]->a_kota,
                  "n_kota"=>$n_kota,                  
                  "a_propinsi"=>(string)$result[$j]->a_propinsi,
                  "n_propinsi"=>$n_propinsi,
                  "a_kodepos"=>(string)$result[$j]->a_kodepos,
                  "i_telponrumah"=>(string)$result[$j]->i_telponrumah,
                  "i_telponhp"=>(string)$result[$j]->i_telponhp,
                  "a_email"=>(string)$result[$j]->a_email,
                  "c_pend"=>(string)$result[$j]->c_pend,
                  "n_pendidikan"=>$n_pendidikan,
                  "n_pend_jurusan"=>(string)$result[$j]->n_pend_jurusan,
                  "d_pend_mulai"=>(string)$result[$j]->d_pend_mulai,
                  "d_pend_akhir"=>(string)$result[$j]->d_pend_akhir,
                  "i_pend_ipk"=>(string)$result[$j]->i_pend_ipk,
                  "i_pend_ijazah"=>(string)$result[$j]->i_pend_ijazah,
                  "d_pend_ijazah"=>(string)$result[$j]->d_pend_ijazah,
                  "c_warganegara"=>(string)$result[$j]->c_warganegara,  
                  "n_pend_lembaga"=>(string)$result[$j]->n_pend_lembaga,
                  "c_pend_akreditasi"=>(string)$result[$j]->c_pend_akreditasi,
                  "c_posisi_jabatan"=>(string)$result[$j]->c_posisi_jabatan,
		  "n_surat_usuljabat"=>(string)$result[$j]->n_surat_usuljabat,		  
                  //"n_posisi_jabatan"=>$n_posisi_jabatan,
		  "n_posisi_jabatan"=>(string)$result[$j]->c_posisi_jabatan,
                  "n_pengadilan"=>$n_pengadilan,
                  "n_wilayah"=>$n_wilayah,  
                  "d_entry"=>(string)$result[$j]->d_entry);
          }
        }
          return $data;
      } catch (Exception $e) 
      {
               echo $e->getMessage().'<br>';
             return 'Data tidak ada <br>';
      }
  }

   public function getRekap($cari) 
  {
      $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {
      
        
        $db->setFetchMode(Zend_Db::FETCH_OBJ);      
          // $result = $db->fetchAll("SELECT count(q_nomor_daftar) as jumlah,c_posisi_jabatan,c_wil_pengadilan
                // FROM sdm.tm_daftaronline a, sdm.tm_nomor_pendaftaran b where a.i_ktp=b.i_ktp and c_hasil=1 $cari
                // group by c_posisi_jabatan,c_wil_pengadilan order by c_posisi_jabatan desc");

          $result = $db->fetchAll("SELECT count(q_nomor_daftar) as jumlah,c_posisi_jabatan,c_wil_pengadilan
                FROM sdm.tm_daftaronline a, sdm.tm_nomor_pendaftaran b where a.i_ktp=b.i_ktp $cari
                group by c_posisi_jabatan,c_wil_pengadilan order by c_posisi_jabatan desc");                

            
                
          $jmlResult = count($result);
          for ($j = 0; $j < $jmlResult; $j++) 
          { 
            
            $c_pengadilan=(string)$result[$j]->c_wil_pengadilan;
            $pengadilan= $db->fetchAll("SELECT n_pengadilan, n_wilayah from sdm.tr_wil_pengadilan where c_pengadilan='$c_pengadilan'");
            $n_pengadilan=$pengadilan[0]->n_pengadilan;
            $n_wilayah=$pengadilan[0]->n_wilayah;
            
            //$c_posisi_jabatan=(string)$result[$j]->c_posisi_jabatan;
            //$n_posisi_jabatan= $db->fetchOne("SELECT n_jabatan from sdm.tr_jabatan_calon where c_jabatan='$c_posisi_jabatan'");

            $data[$j] = array("jumlah"=>(string)$result[$j]->jumlah,
                  "c_posisi_jabatan"=>(string)$result[$j]->c_posisi_jabatan,
                  //"n_posisi_jabatan"=>$n_posisi_jabatan,
		  "n_posisi_jabatan"=>(string)$result[$j]->c_posisi_jabatan,
                  "n_pengadilan"=>$n_pengadilan,
                  "n_wilayah"=>$n_wilayah);
          }
        
          return $data;
      } catch (Exception $e) 
      {
               echo $e->getMessage().'<br>';
             return 'Data tidak ada <br>';
      }
  }
  

public function getStatus() 
  {
    $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {
        $data = $db->fetchOne("SELECT c_status_aktivasi FROM sdm.tm_aktivasi_pendaftaran");
        return $data;
      } catch (Exception $e) 
      {
               echo $e->getMessage().'<br>';
             return 'Data tidak ada <br>';
      }
  }

  public function maintaindataaktivasi(array $data,$par) {
     $registry = Zend_Registry::getInstance();
     $db = $registry->get('db');
     try {
       $db->beginTransaction();
    $tanggal=date('Ymd');
    $maintain_data = array("c_status_aktivasi"=>$data['c_status_aktivasi'],"d_status_aktivasi"=>$tanggal);
    if ($par=='insert'){$db->insert('sdm.tm_aktivasi_pendaftaran',$maintain_data);}
    if ($par=='update'){$db->update('sdm.tm_aktivasi_pendaftaran',$maintain_data);}   
    
    $db->commit();
       return 'sukses';
     } catch (Exception $e) {
    $db->rollBack();
    echo $e->getMessage().'<br>';
       return 'gagal';
     }
  }


public function getTrWilPengadilan($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("select c_satker,c_pengadilan ,n_pengadilan,c_wilayah,n_wilayah 
								from sdm.tr_wil_pengadilan where 1=1 $cari order by c_wilayah asc  ");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{$data[$j] = array("c_pengadilan"=>(string)$result[$j]->c_pengadilan,
							"n_pengadilan"=>(string)$result[$j]->n_pengadilan,
							"c_wilayah"=>(string)$result[$j]->c_wilayah,
							"n_wilayah"=>(string)$result[$j]->n_wilayah,
							"c_satker"=>(string)$result[$j]->c_satker
							);}
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
  
  
}
?>