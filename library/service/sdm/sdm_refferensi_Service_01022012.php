<?php
class sdm_refferensi_Service {
    private static $instance;
  
    private function __construct() {
    }

    public static function getInstance() {
       if (!isset(self::$instance)) {
           $c = __CLASS__;
           self::$instance = new $c;
       }
       return self::$instance;
    }

	public function getGolonganPegawai($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("select c_peg_golongan,n_peg_golongan,n_peg_pangkat,c_pph,c_peg_lvlgolongan,c_peg_tipegolongan
											from sdm.tr_golongan_pangkat where 1=1 $cari order by c_peg_tipegolongan asc");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{$data[$j] = array("c_peg_golongan"=>(string)$result[$j]->c_peg_golongan,
										"n_peg_pangkat"=>(string)$result[$j]->n_peg_pangkat,
										"n_peg_golongan"=>(string)$result[$j]->n_peg_golongan,
										"c_pph"=>(string)$result[$j]->c_pph,
										"c_peg_lvlgolongan"=>(string)$result[$j]->c_peg_lvlgolongan,
										"c_peg_tipegolongan"=>(string)$result[$j]->c_peg_tipegolongan);}
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	  

	public function getStatusPegawai($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("select c_peg_status ,n_peg_status  from sdm.tr_status_pegawai where 1=1 $cari ");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{$data[$j] = array("c_peg_status"=>(string)$result[$j]->c_peg_status,
										"n_peg_status"=>(string)$result[$j]->n_peg_status);}
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}

		public function getStatusKepegawaian($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("select c_status_kepegawaian ,n_status_kepegawaian  from sdm.tr_status_kepegawaian where 1=1 $cari order by c_status_kepegawaian asc  ");					
					return $result;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	public function getPendidikan($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT c_pend, n_pend
					FROM sdm.tr_pendidikan  where 1=1  $cari order by d_entry asc");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{$data[$j] = array("c_pend"=>(string)$result[$j]->c_pend,
										"n_pend"=>(string)$result[$j]->n_pend);}
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function getAgamaList() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll('SELECT c_agama, n_agama FROM sdm.tr_agama order by c_agama ');
         $jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{$data[$j] = array("c_agama"=>(string)$result[$j]->c_agama,
										"n_agama"=>(string)$result[$j]->n_agama);}
					return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function getEselonList($cari) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_eselon ,n_eselon  from sdm.tr_eselon where 1=1 $cari order by c_eselon asc");				 
		$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{$data[$j] = array("c_eselon"=>(string)$result[$j]->c_eselon,
										"n_eselon"=>(string)$result[$j]->n_eselon);}
					return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function getPropinsiListAll() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("SELECT c_propinsi, n_propinsi FROM sdm.tr_propinsi order by n_propinsi ");				 
		$jmlResult = count($result);
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function getKabupatenListAll($cari) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("SELECT c_kabupaten, n_kabupaten FROM sdm.tr_kabupaten where 1=1 $cari order by n_kabupaten ");						 
		$jmlResult = count($result);
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
	public function getStatusPegListAll() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll('SELECT c_peg_status, n_peg_status
				  FROM e_sdm_sdm.tr_status_pegawai order by c_peg_status ');
						 
         $jmlResult = count($result);
	     return $result;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	public function getAgamaListAll() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll('SELECT c_agama, n_agama FROM sdm.tr_agama order by c_agama ');
         $jmlResult = count($result);
	     return $result;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
	

	public function getNegara($cari) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_negara ,n_negara  from sdm.tr_negara where 1=1 $cari order by n_negara asc  ");				 
		$jmlResult = count($result);
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	

	public function getPekerjaan($cari) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_pekerjaan ,n_pekerjaan  from sdm.tr_pekerjaan where 1=1 $cari order by c_pekerjaan asc  ");				 
		$jmlResult = count($result);
	     return $result;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}		


	public function getEselon($cari) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_eselon ,n_eselon  from sdm.tr_eselon where 1=1 $cari order by c_eselon asc");				 
		$jmlResult = count($result);
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	


	public function getJabatan($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("select c_jabatan,n_jabatan,e_keterangan,c_tkfgs,c_kelfgs,c_golr,
											c_golt,n_jenjang,c_tanda,c_eselon,c_strata,q_tunjangan,
											q_usia_pens,q_tktfgs,q_ak_minimal from sdm.tr_jabatan where 1=1 $cari order by n_jabatan asc");
										
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{$data[$j] = array("c_jabatan"=>(string)$result[$j]->c_jabatan,
										"n_jabatan"=>(string)$result[$j]->n_jabatan,
										"e_keterangan"=>(string)$result[$j]->e_keterangan,
										"c_tkfgs"=>(string)$result[$j]->c_tkfgs,
										"c_kelfgs"=>(string)$result[$j]->c_kelfgs,
										"c_golr"=>(string)$result[$j]->c_golr,
										"c_golt"=>(string)$result[$j]->c_golt,
										"n_jenjang"=>(string)$result[$j]->n_jenjang,
										"c_tanda"=>(string)$result[$j]->c_tanda,
										"c_eselon"=>(string)$result[$j]->c_eselon,
										"c_strata"=>(string)$result[$j]->c_strata,
										"q_tunjangan"=>(string)$result[$j]->q_tunjangan,
										"q_usia_pens"=>(string)$result[$j]->q_usia_pens,
										"q_tktfgs"=>(string)$result[$j]->q_tktfgs,
										"q_ak_minimal"=>(string)$result[$j]->q_ak_minimal);}
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}

	public function getLokasi($cari) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_lokasi ,n_lokasi  from sdm.tr_lokasi where 1=1 $cari order by c_lokasi asc  ");				 
		$jmlResult = count($result);
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function getPenghargaan($cari) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_penghargaan ,n_penghargaan  from sdm.tr_penghargaan where 1=1 $cari order by c_penghargaan asc  ");				 
		$jmlResult = count($result);
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}		
	public function getTandaJasa($cari) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_tandajasa ,n_tandajasa  from sdm.tr_tandajasa where 1=1 $cari order by c_tandajasa asc  ");				 
		$jmlResult = count($result);
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	public function getPekerjaanAnak($cari) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_kerja ,n_kerja  from sdm.tr_pekerjaan_anak where 1=1 $cari order by c_kerja asc  ");				 
		$jmlResult = count($result);
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	

 	public function getUnitKerja($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("select c_satker,c_lokasi_unitkerja,c_eselon_i,c_eselon_ii,c_eselon_iii,c_eselon_iv,c_eselon_v,
											c_propinsi_unit,c_tkt_esl,c_esl,c_jabatan,c_bidang,c_type,n_unitkerja,c_parent,c_child 
											from sdm.tr_unitkerja where 1=1 $cari order by c_eselon_i asc");		
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 		
						$data[$j] = array("c_lokasi_unitkerja"=>(string)$result[$j]->c_lokasi_unitkerja,
										"c_eselon_i"=>(string)$result[$j]->c_eselon_i,
										"c_eselon_ii"=>(string)$result[$j]->c_eselon_ii,
										"c_eselon_iii"=>(string)$result[$j]->c_eselon_iii,
										"c_eselon_iv"=>(string)$result[$j]->c_eselon_iv,
										"c_eselon_v"=>(string)$result[$j]->c_eselon_v,
										"c_propinsi_unit"=>(string)$result[$j]->c_propinsi_unit,
										"c_tkt_esl"=>(string)$result[$j]->c_tkt_esl,
										"c_esl"=>(string)$result[$j]->c_esl,
										"c_jabatan"=>(string)$result[$j]->c_jabatan,
										"c_bidang"=>(string)$result[$j]->c_bidang,
										"c_type"=>(string)$result[$j]->c_type,
										"c_parent"=>(string)$result[$j]->c_parent,
										"c_child"=>(string)$result[$j]->c_child,
										"c_satker"=>(string)$result[$j]->c_satker,
										"n_unitkerja"=>(string)$result[$j]->n_unitkerja);
					}
										
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function getTrUnitKerja($cari) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_eselon_i,c_eselon_ii,c_eselon_iii,c_eselon_iv,c_eselon_v,n_unitkerja,c_bidang,c_parent,c_child from sdm.tr_unitkerja where 1=1 $cari");
		$jmlResult = count($result);
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
	public function getJnsPelatihanFungsional($cari) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_fungsional ,n_fungsional  from sdm.tr_jns_dikfungsional where 1=1 $cari order by c_fungsional asc  ");				 
		$jmlResult = count($result);
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
	public function getKelPelatihanFungsional($cari) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_kelfungsional ,n_kelfungsional  from sdm.tr_kelfungsional where 1=1 $cari order by n_kelfungsional asc  ");				 
		$jmlResult = count($result);
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	public function getPenjenjanganFungsional($cari) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_jenjang_fungsional ,n_jenjang_fungsional  from sdm.tr_penjenganan_fungsional where 1=1 $cari order by c_jenjang_fungsional asc  ");				 
		$jmlResult = count($result);
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
	public function getNamaPenjenjanganFungsional($cari) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_jenjang ,n_jenjang  from sdm.tr_nama_penjenjangan where 1=1 $cari order by c_jenjang asc  ");				 
		$jmlResult = count($result);
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
	public function getTrPenjenjangan($cari) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_jenjang ,n_jenjang  from sdm.tr_diklat_penjenjangan where 1=1 $cari order by c_jenjang asc  ");				 
		$jmlResult = count($result);
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function getTrPenjenjanganList($cari) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_jenjang ,n_jenjang  from sdm.tr_diklat_penjenjangan where n_jenjang is not null $cari order by c_jenjang asc  ");				 
		$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 		
						$data[$j] = array("c_jenjang"=>(string)$result[$j]->c_jenjang,
										"n_jenjang"=>(string)$result[$j]->n_jenjang);
					}
										
					return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function getTrDiklatKualifikasi($cari) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_kualifikasi ,n_kualifikasi  from sdm.tr_diklat_kualifikasi where 1=1 $cari order by c_kualifikasi asc  ");				 
		$jmlResult = count($result);
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	public function getTrKelDiklatTeknis($cari) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_kelompok ,n_kelompok  from sdm.tr_kel_diklat_teknis where 1=1 $cari order by c_kelompok asc  ");				 
		$jmlResult = count($result);
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function getTrJnsKepangkatan($cari) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_jns_kepangkatan ,n_jns_kepangkatan  from sdm.tr_jns_kepangkatan where 1=1 $cari order by c_jns_kepangkatan asc  ");				 
		$jmlResult = count($result);
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function getTrStatusNikahList($cari) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_status_nikah ,n_status_nikah  from sdm.tr_status_nikah where 1=1 $cari order by c_status_nikah asc  ");				 
		$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 		
						$data[$j] = array("c_status_nikah"=>(string)$result[$j]->c_status_nikah,
										"n_status_nikah"=>(string)$result[$j]->n_status_nikah);
					}
										
					return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
	
	public function getTrGolDarah($cari) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_golongan_darah ,n_golongan_darah  from sdm.tr_gol_darah where 1=1 $cari order by c_golongan_darah asc  ");				 
		$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 		
						$data[$j] = array("c_golongan_darah"=>(string)$result[$j]->c_golongan_darah,
										"n_golongan_darah"=>(string)$result[$j]->n_golongan_darah);
					}
										
					return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function getTrJurusanPendidikan($cari) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_jurusan ,n_jurusan  from sdm.tr_jurusan_pendidikan where 1=1 $cari order by c_jurusan asc  ");				 
		$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 		
						$data[$j] = array("c_jurusan"=>(string)$result[$j]->c_jurusan,
										"n_jurusan"=>(string)$result[$j]->n_jurusan);
					}
										
					return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
	public function getTrUniversitas($cari) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_universitas ,n_universitas2,n_rayon_pro,q_strata,n_universitas  from sdm.tr_universitas where 1=1 $cari order by c_universitas asc  ");				 
		$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 		
						$data[$j] = array("c_universitas"=>(string)$result[$j]->c_universitas,
										"n_universitas2"=>(string)$result[$j]->n_universitas2,
										"n_rayon_pro"=>(string)$result[$j]->n_rayon_pro,
										"q_strata"=>(string)$result[$j]->q_strata,
										"n_universitas"=>(string)$result[$j]->n_universitas);
					}
										
					return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	public function getTrBank($cari) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_bank ,n_bank from sdm.tr_bank where 1=1 $cari order by c_bank asc  ");						 
		$jmlResult = count($result);
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function getTrJenisDok($cari) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_dokumen ,n_dokumen  from sdm.tr_jenis_dokumen where 1=1 $cari order by c_dokumen asc  ");				 
		$jmlResult = count($result);
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function getTrJenjangDikmil($cari) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_jenjang ,n_jenjang  from sdm.tr_jenjang_dikmil where 1=1 $cari order by c_jenjang asc  ");				 
		$jmlResult = count($result);
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	

	public function getTrSekolahMiliter($cari) { 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_sekolahmil ,n_sekolahmil  from sdm.tr_sekolah_militer where 1=1 $cari order by c_sekolahmil asc  ");				 
		$jmlResult = count($result);
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function getTrKejuruanMiliter($cari) { 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_kejuruanmil ,n_kejuruanmil  from sdm.tr_kejuruanmiliter where 1=1 $cari order by c_kejuruanmil asc  ");				 
		$jmlResult = count($result);
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function getTrJurMiliter($cari) { 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_jenis_kel ,n_jenis_kel  from sdm.tr_jurmiliter where 1=1 $cari order by c_jenis_kel asc  ");				 
		$jmlResult = count($result);
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function getTrStatusjabatan($cari) { 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_statusjabatan ,n_statusjabatan  from sdm.tr_statusjabatan where 1=1 $cari order by c_statusjabatan asc  ");				 
		$jmlResult = count($result);
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function getTrStatfungsional($cari) { 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_statusfung ,n_statusfung  from sdm.tr_statfungsional where 1=1 $cari order by c_statusfung asc  ");				 
		$jmlResult = count($result);
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function getCsatker($cari) { 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchOne("select c_satker from sdm.tr_unitkerja where 1=1 $cari ");	
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function getTrHukuman($cari) { 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_hukuman ,n_hukuman  from sdm.tr_jenis_hukuman where 1=1 $cari order by c_hukuman asc  ");				 
		$jmlResult = count($result);
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	

	public function getTrJnsnaikGol($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("select i_kode ,n_nama  from sdm.tr_jnsnaik_gol where 1=1 $cari order by i_kode asc  ");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{$data[$j] = array("i_kode"=>(string)$result[$j]->i_kode,"n_nama"=>(string)$result[$j]->n_nama);}
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}	

public function getTrJabatanCalon($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("select c_jabatan ,n_jabatan  from sdm.tr_jabatan_calon where 1=1 $cari order by c_jabatan asc  ");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{$data[$j] = array("c_jabatan"=>(string)$result[$j]->c_jabatan,"n_jabatan"=>(string)$result[$j]->n_jabatan);}
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
public function getTrWilPengadilan($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("select c_pengadilan ,n_pengadilan,c_wilayah,n_wilayah from sdm.tr_wil_pengadilan where 1=1 $cari order by c_wilayah asc  ");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{$data[$j] = array("c_pengadilan"=>(string)$result[$j]->c_pengadilan,
							"n_pengadilan"=>(string)$result[$j]->n_pengadilan,
							"c_wilayah"=>(string)$result[$j]->c_wilayah,
							"n_wilayah"=>(string)$result[$j]->n_wilayah
							);}
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}

public function getKelDikTeknis($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("select c_kelompok ,n_kelompok from sdm.tr_diklat_teknis where 1=1 $cari order by c_kelompok asc  ");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{$data[$j] = array("c_kelompok"=>(string)$result[$j]->c_kelompok,
							"n_kelompok"=>(string)$result[$j]->n_kelompok
							);}
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}	

public function getTrDiklatTeknis($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("select c_diklat_teknis ,n_diklat_teknis from sdm.tr_diklat_teknis where 1=1 $cari order by c_diklat_teknis asc  ");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{$data[$j] = array("c_diklat_teknis"=>(string)$result[$j]->c_diklat_teknis,
							"n_diklat_teknis"=>(string)$result[$j]->n_diklat_teknis
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
