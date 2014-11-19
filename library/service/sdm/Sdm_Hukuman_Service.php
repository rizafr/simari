<?php
class Sdm_Hukuman_Service {
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

 	public function getHukumanList($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
			
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT id, i_peg_nip,c_tingkat_sanksi,c_jenis_sanksi,e_alasan_sanksi,
											to_char(d_mulai_sanksi,'dd-mm-yyyy') as d_mulai_sanksi,
											to_char(d_akhir_sanksi,'dd-mm-yyyy') as d_akhir_sanksi,
											i_sk_sanksi,to_char(d_sk_sanksi,'dd-mm-yyyy') as d_sk_sanksi,n_pejabat,
											e_keterangan,i_entry,d_entry,c_jns_pelanggaran,e_file_sk
											FROM sdm.tm_sanksi where 1=1 $cari  order by d_mulai_sanksi asc");		
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 		
						$jnssanksi='';
						$c_jenis_sanksi=trim($result[$j]->c_jenis_sanksi);
						if ($result[$j]->c_jns_pelanggaran=='PL'){
							$n_jns_pelanggaran='Pelangggaran';
							switch (trim($result[$j]->c_tingkat_sanksi))
							{
							case "3":
							  $nsanksi1="Ringan";
							  break;
							case "2":
							  $nsanksi1="Menengah";
							  break;
							case "1":
							  $nsanksi1="Berat";
							  break;						  
							default:
							  $nsanksi1="";
							}
							if ($c_jenis_sanksi){					
								$jnssanksi=$db->fetchOne("select n_hukuman from sdm.tr_jenis_hukuman where c_hukuman='$c_jenis_sanksi'");
							}
							 $nsanksi= $jnssanksi.($jnssanksi ? " (":"").$nsanksi1.($jnssanksi ? ")":"");
						} else if ($result[$j]->c_jns_pelanggaran=='PT'){
							$n_jns_pelanggaran='Peringatan Tertulis';
							switch (trim($result[$j]->c_tingkat_sanksi))
							{
							case "1":
							  $nsanksi="Peringatan Tertulis I";
							  break;
							case "2":
							  $nsanksi="Peringatan Tertulis II";
							  break;
							case "3":
							  $nsanksi="Peringatan Tertulis III";
							  break;						  
							default:
							  $nsanksi="";
							}
						}  else if ($result[$j]->c_jns_pelanggaran=='MT'){
							$nsanksi="";
							$n_jns_pelanggaran='Pemalsuan Tandatangan Absensi';
						}	

						$data[$j] = array("id"=>$result[$j]->id,
									"i_peg_nip"=>(string)$result[$j]->i_peg_nip,
								"c_tingkat_sanksi"=>(string)$result[$j]->c_tingkat_sanksi,
								"nsanksi"=>$nsanksi,
								"n_jns_pelanggaran"=>$n_jns_pelanggaran,
								"c_jenis_sanksi"=>(string)$result[$j]->c_jenis_sanksi,
								"jnssanksi"=>$jnssanksi,
								"e_alasan_sanksi"=>(string)$result[$j]->e_alasan_sanksi,
								"d_mulai_sanksi"=>(string)$result[$j]->d_mulai_sanksi,
								"d_akhir_sanksi"=>(string)$result[$j]->d_akhir_sanksi,
								"i_sk_sanksi"=>(string)$result[$j]->i_sk_sanksi,
								"d_sk_sanksi"=>(string)$result[$j]->d_sk_sanksi,
								"n_pejabat"=>(string)$result[$j]->n_pejabat,
								"e_keterangan"=>(string)$result[$j]->e_keterangan,
								"c_jns_pelanggaran"=>(string)$result[$j]->c_jns_pelanggaran,
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
							"c_tingkat_sanksi"=>$data['c_tingkat_sanksi'],
							"c_jenis_sanksi"=>$data['c_jenis_sanksi'],
							"e_alasan_sanksi"=>$data['e_alasan_sanksi'],
							"d_mulai_sanksi"=>$data['d_mulai_sanksi'],
							"d_akhir_sanksi"=>$data['d_akhir_sanksi'],
							"i_sk_sanksi"=>$data['i_sk_sanksi'],
							"d_sk_sanksi"=>$data['d_sk_sanksi'],
							"n_pejabat"=>$data['n_pejabat'],
							"e_keterangan"=>$data['e_keterangan'],
							"c_jns_pelanggaran"=>$data['c_jns_pelanggaran'],
							"e_file_sk"=>$data['e_file_sk'],	
							"i_entry"=>$data['i_entry'],
							"d_entry"=>date("Y-m-d"));
						
		if ($par=='insert'){$db->insert('sdm.tm_sanksi',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tm_sanksi',$maintain_data, "id = '".trim($data['id'])."'");}	 
		if ($par=='delete'){$db->delete('sdm.tm_sanksi', "id = '".trim($data['id'])."'");}
		//if ($par=='update'){$db->update('sdm.tm_sanksi',$maintain_data, "i_peg_nip = '".trim($data['i_peg_nip'])."' and c_jns_pelanggaran = '".trim($data['c_jns_pelanggaranb'])."' and d_mulai_sanksi = '".trim($data['d_mulai_sanksib'])."'");}	 
		//if ($par=='delete'){$db->delete('sdm.tm_sanksi', "i_peg_nip = '".trim($data['i_peg_nip'])."' and c_jns_pelanggaran = '".trim($data['c_jns_pelanggaran'])."'  and to_char(d_mulai_sanksi,'dd-mm-yyyy') = '".trim($data['d_mulai_sanksi'])."' ");}
		
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