<?php
class Sdm_Jabatan_Service {
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

 	public function getJabatanList($cari) 
	{
	
	
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
		
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT  i_peg_nip,c_eselon,to_char(d_tmt_eselon,'dd-mm-yyyy') as d_tmt_eselon,
										c_jabatan,n_jabatan_nokode,to_char(d_mulai_jabat,'dd-mm-yyyy') as d_mulai_jabat,
										to_char(d_akhir_jabat,'dd-mm-yyyy') as d_akhir_jabat,
										q_angka_kredit,c_lokasi_unitkerja,c_eselon_i,c_eselon_ii,c_eselon_iii,
										c_eselon_iv,c_eselon_v,a_alamat_kantor,i_sk_jabat,
										to_char(d_sk_jabat,'dd-mm-yyyy') as d_sk_jabat,
										n_sk_pejabat,to_char(d_tmt_lantik,'dd-mm-yyyy') as d_tmt_lantik,
										n_lok_kppn,n_lok_taspen,e_keterangan,i_entry,d_entry,e_file_sk	
										FROM sdm.tm_jabatan where 1=1 $cari  order by d_entry asc");

							
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 		
						$c_eselon=trim($result[$j]->c_eselon);
						$n_eselon = $db->fetchOne("select n_eselon  from sdm.tr_eselon where c_eselon='$c_eselon'");	
						$c_jabatan=trim($result[$j]->c_jabatan);
						$n_jabatan = $db->fetchOne("select n_jabatan from sdm.tr_jabatan where c_jabatan='$c_jabatan'");						
						$c_lokasi_unitkerja=trim($result[$j]->c_lokasi_unitkerja);
						$n_lokasi_unitkerja = $db->fetchOne("select n_lokasi  from sdm.tr_lokasi where c_lokasi='$c_lokasi_unitkerja'");
						$c_eselon_i=trim($result[$j]->c_eselon_i);
						$n_eselon_i = $db->fetchOne("select n_unitkerja from sdm.tr_unitkerja where c_eselon_i='$c_eselon_i'");
						$c_eselon_ii=trim($result[$j]->c_eselon_ii);
						$n_eselon_ii = $db->fetchOne("select n_unitkerja from sdm.tr_unitkerja where c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii'");
						$c_eselon_iii=trim($result[$j]->c_eselon_iii);
						$n_eselon_iii = $db->fetchOne("select n_unitkerja from sdm.tr_unitkerja where c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii'");
						$c_eselon_iv=trim($result[$j]->c_eselon_iv);
						$n_eselon_iv = $db->fetchOne("select n_unitkerja from sdm.tr_unitkerja where c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv'");
						$c_eselon_v=trim($result[$j]->c_eselon_v);
						$c_eselon_v = $db->fetchOne("select n_unitkerja from sdm.tr_unitkerja where c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_eselon_v='$c_eselon_v'");

						if ($n_eselon_i){$nesl1=" ,$n_eselon_i";}
						if ($n_eselon_ii){$nesl2=" ,$n_eselon_ii";}
						if ($n_eselon_iii){$nesl3=" ,$n_eselon_iii";}
						if ($n_eselon_iv){$nesl4=",$n_eselon_iv";}
						if ($n_eselon_v){$nesl5=" ,$n_eselon_v";}
						if ($n_eselon_i){$unitkerjalengkap="pada ".$nesl5.$nesl4.$nesl3.$nesl2.$nesl1;}
	
						$data[$j] = array("i_peg_nip"=>(string)$result[$j]->i_peg_nip,
										"c_eselon"=>(string)$result[$j]->c_eselon,
										"n_eselon"=>$n_eselon,
										"d_tmt_eselon"=>(string)$result[$j]->d_tmt_eselon,
										"c_jabatan"=>(string)$result[$j]->c_jabatan,
										"n_jabatan"=>$n_jabatan,
										"n_jabatan_nokode"=>(string)$result[$j]->n_jabatan_nokode,
										"d_mulai_jabat"=>(string)$result[$j]->d_mulai_jabat,
										"d_akhir_jabat"=>(string)$result[$j]->d_akhir_jabat,
										"q_angka_kredit"=>(string)$result[$j]->q_angka_kredit,
										"c_lokasi_unitkerja"=>(string)$result[$j]->c_lokasi_unitkerja,
										"n_lokasi_unitkerja"=>(string)$result[$j]->n_lokasi_unitkerja,
										"unitkerjalengkap"=>$unitkerjalengkap,
										"c_eselon_i"=>trim($result[$j]->c_eselon_i),
										"n_eselon_i"=>$n_eselon_i,
										"c_eselon_ii"=>trim($result[$j]->c_eselon_ii),
										"n_eselon_ii"=>$n_eselon_ii,
										"c_eselon_iii"=>trim($result[$j]->c_eselon_iii),
										"n_eselon_iii"=>$n_eselon_iii,
										"c_eselon_iv"=>trim($result[$j]->c_eselon_iv),
										"n_eselon_iv"=>$n_eselon_iv,
										"c_eselon_v"=>trim($result[$j]->c_eselon_v),
										"n_eselon_v"=>$n_eselon_v,
										"a_alamat_kantor"=>(string)$result[$j]->a_alamat_kantor,
										"i_sk_jabat"=>(string)$result[$j]->i_sk_jabat,
										"d_sk_jabat"=>(string)$result[$j]->d_sk_jabat,
										"n_sk_pejabat"=>(string)$result[$j]->n_sk_pejabat,
										"d_tmt_lantik"=>(string)$result[$j]->d_tmt_lantik,
										"n_lok_kppn"=>(string)$result[$j]->n_lok_kppn,
										"n_lok_taspen"=>(string)$result[$j]->n_lok_taspen,
										"e_keterangan"=>(string)$result[$j]->e_keterangan,
										"e_file_sk"=>(string)$result[$j]->e_file_sk,
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
								"c_eselon"=>$data['c_eselon'],
								"d_tmt_eselon"=>$data['d_tmt_eselon'],
								"c_jabatan"=>$data['c_jabatan'],
								"n_jabatan_nokode"=>$data['n_jabatan_nokode'],
								"d_mulai_jabat"=>$data['d_mulai_jabat'],
								"d_akhir_jabat"=>$data['d_akhir_jabat'],
								"q_angka_kredit"=>$data['q_angka_kredit'],
								"c_lokasi_unitkerja"=>$data['c_lokasi_unitkerja'],
								"c_eselon_i"=>$data['c_eselon_i'],
								"c_eselon_ii"=>$data['c_eselon_ii'],
								"c_eselon_iii"=>$data['c_eselon_iii'],
								"c_eselon_iv"=>$data['c_eselon_iv'],
								"c_eselon_v"=>$data['c_eselon_v'],
								"a_alamat_kantor"=>$data['a_alamat_kantor'],
								"i_sk_jabat"=>$data['i_sk_jabat'],
								"d_sk_jabat"=>$data['d_sk_jabat'],
								"n_sk_pejabat"=>$data['n_sk_pejabat'],
								"d_tmt_lantik"=>$data['d_tmt_lantik'],
								"n_lok_kppn"=>$data['n_lok_kppn'],
								"n_lok_taspen"=>$data['n_lok_taspen'],
								"e_keterangan"=>$data['e_keterangan'],
								"e_file_sk"=>$data['e_file_sk'],								
								"i_entry"=>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));

		if ($par=='insert'){$db->insert('sdm.tm_jabatan',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tm_jabatan',$maintain_data, "i_peg_nip = '".trim($data['i_peg_nip'])."'  and c_eselon = '".trim($data['c_eselon'])."' and d_mulai_jabat = '".trim($data['d_mulai_jabat'])."'");}	 
		if ($par=='delete'){$db->delete('sdm.tm_jabatan', "i_peg_nip = '".trim($data['i_peg_nip'])."'  and c_eselon = '".trim($data['c_eselon'])."' and d_mulai_jabat = '".trim($data['d_mulai_jabat'])."' ");}
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function updateTmPegawai(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	   
	     $db->beginTransaction();
	     $maintain_data = array("c_eselon"=>$data['c_eselon'],
					"d_tmt_eselon"=>$data['d_tmt_eselon'],
					"c_jabatan"=>$data['c_jabatan'],
					"d_mulai_jabat"=>$data['d_mulai_jabat'],
					"d_akhir_jabat"=>$data['d_akhir_jabat'],
					"c_lokasi_unitkerja"=>$data['c_lokasi_unitkerja'],
					"c_eselon_i"=>$data['c_eselon_i'],
					"c_eselon_ii"=>$data['c_eselon_ii'],
					"c_eselon_iii"=>$data['c_eselon_iii'],
					"c_eselon_iv"=>$data['c_eselon_iv'],
					"c_eselon_v"=>$data['c_eselon_v'],
					"c_satker"=>$data['c_satker'],								
					"q_usia_pensiun"=>$data['q_usia_pensiun']);
			
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