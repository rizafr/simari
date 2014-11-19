<?php
class Sdm_Pangkat_Service {
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

 	public function getPangkatList($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT i_peg_nip,c_golongan,to_char(d_tmt_golongan,'yyyy-mm-dd') as d_tmt_golongan,i_sk_golongan,d_sk_golongan,i_sk_pejabat,
										q_masakerja_bulan,q_masakerja_tahun,v_gaji_pokok,c_jenis_naik,e_keterangan,i_entry,d_entry,e_file_sk,
										i_sk_bkn,to_char(d_sk_bkn,'yyyy-mm-dd') as d_sk_bkn
										FROM sdm.tm_golongan_pangkat where 1=1 $cari  order by d_tmt_golongan asc");		
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 		
						$c_golongan=$result[$j]->c_golongan;
						$n_pangkat= $db->fetchOne("SELECT n_peg_pangkat FROM sdm.tr_golongan_pangkat WHERE c_peg_golongan ='$c_golongan'");
						$n_golongan= $db->fetchOne("SELECT n_peg_golongan FROM sdm.tr_golongan_pangkat WHERE c_peg_golongan ='$c_golongan'");
						
						$c_jenis_naik=trim($result[$j]->c_jenis_naik);
						$n_jenis_naik= $db->fetchOne("SELECT n_nama FROM sdm.tr_jnsnaik_gol WHERE i_kode ='$c_jenis_naik'");
								
						$data[$j] = array("i_peg_nip"=>(string)$result[$j]->i_peg_nip,
										"c_golongan"=>(string)$result[$j]->c_golongan,
										"n_pangkat"=>$n_pangkat,
										"n_golongan"=>$n_golongan,
										"d_tmt_golongan"=>(string)$result[$j]->d_tmt_golongan,
										"i_sk_golongan"=>(string)$result[$j]->i_sk_golongan,
										"d_sk_golongan"=>(string)$result[$j]->d_sk_golongan,
										"i_sk_pejabat"=>(string)$result[$j]->i_sk_pejabat,
										"q_masakerja_bulan"=>(string)$result[$j]->q_masakerja_bulan,
										"q_masakerja_tahun"=>(string)$result[$j]->q_masakerja_tahun,
										"v_gaji_pokok"=>(string)$result[$j]->v_gaji_pokok,
										"c_jenis_naik"=>(string)$result[$j]->c_jenis_naik,
										"n_jenis_naik"=>$n_jenis_naik,		
										"e_keterangan"=>(string)$result[$j]->e_keterangan,
										"e_file_sk"=>(string)$result[$j]->e_file_sk,
										"i_sk_bkn"=>(string)$result[$j]->i_sk_bkn,
										"d_sk_bkn"=>(string)$result[$j]->d_sk_bkn,
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

	
	public function maintainData(array $data,$par) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();

	     $maintain_data = array("i_peg_nip"=>$data['i_peg_nip'],
							"c_golongan"=>$data['c_golongan'],
							"d_tmt_golongan"=>$data['d_tmt_golongan'],
							"i_sk_golongan"=>$data['i_sk_golongan'],
							"d_sk_golongan"=>$data['d_sk_golongan'],
							"i_sk_bkn"=>$data['i_sk_bkn'],
							"d_sk_bkn"=>$data['d_sk_bkn'],
							"i_sk_pejabat"=>$data['i_sk_pejabat'],
							"q_masakerja_bulan"=>$data['q_masakerja_bulan'],
							"q_masakerja_tahun"=>$data['q_masakerja_tahun'],
							"v_gaji_pokok"=>$data['v_gaji_pokok'],
							"c_jenis_naik"=>$data['c_jenis_naik'],							
							"e_keterangan"=>$data['e_keterangan'],
							"e_file_sk"=>$data['e_file_sk'],							
							"i_entry"=>$data['i_entry'],
							"d_entry"=>date("Y-m-d"));
		if ($par=='insert'){$db->insert('sdm.tm_golongan_pangkat',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tm_golongan_pangkat',$maintain_data, "i_peg_nip = '".trim($data['i_peg_nip'])."'  and c_golongan = '".trim($data['c_golongan2'])."'  and to_char(d_tmt_golongan,'yyyy-mm-dd') = '".trim($data['d_tmt_golongan2'])."' and c_jenis_naik = '".trim($data['c_jenis_naik2'])."'");}	 
		if ($par=='delete'){$db->delete('sdm.tm_golongan_pangkat', "i_peg_nip = '".trim($data['i_peg_nip'])."'  and c_golongan = '".trim($data['c_golongan2'])."' and to_char(d_tmt_golongan,'yyyy-mm-dd') = '".trim($data['d_tmt_golongan2'])."' and c_jenis_naik = '".trim($data['c_jenis_naik2'])."'");}
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function updateTmPegawaiKp(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	   
	     $db->beginTransaction();
	     $maintain_data = array("c_golongan"=>$data['c_golongan'],
								"d_tmt_golongan"=>$data['d_tmt_golongan'],
								"v_gaji_pokok"=>$data['v_gaji_pokok']);
			
		$db->update('sdm.tm_pegawai',$maintain_data, "i_peg_nip = '".trim($data['i_peg_nip'])."'");
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	public function updateTmPegawaiKgb(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	   
	     $db->beginTransaction();
	     $maintain_data = array("c_golongan"=>$data['c_golongan'],
				"d_tmt_kgb"=>$data['d_tmt_golongan'],
				"v_gaji_pokok"=>$data['v_gaji_pokok']);
			//echo 'sdm.tm_pegawai',$maintain_data, "i_peg_nip = '".trim($data['i_peg_nip'])."'";
		$db->update('sdm.tm_pegawai',$maintain_data, "i_peg_nip = '".trim($data['i_peg_nip'])."'");
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
}
?>