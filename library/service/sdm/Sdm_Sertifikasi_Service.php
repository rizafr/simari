<?php
class Sdm_Sertifikasi_Service {
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

 	public function getSertifikasiList($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
			
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT  id,i_peg_nip,i_sertifikasi,n_sertifikasi,n_sertifikasi_lembaga,
										to_char(d_sertifikasi,'dd-mm-yyyy') as d_sertifikasi,
										to_char(d_mulai_sertifikasi,'dd-mm-yyyy') as d_mulai_sertifikasi,
										to_char(d_akhir_sertifikasi,'dd-mm-yyyy') as d_akhir_sertifikasi,
										e_keterangan,i_entry,d_entry
										FROM sdm.tm_sertifikasi where 1=1 $cari  order by d_mulai_sertifikasi asc");		
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 		
						$data[$j] = array("id"=>(string)$result[$j]->id,"i_peg_nip"=>(string)$result[$j]->i_peg_nip,
										"i_sertifikasi"=>(string)$result[$j]->i_sertifikasi,
										"n_sertifikasi"=>(string)$result[$j]->n_sertifikasi,
										"n_sertifikasi_lembaga"=>(string)$result[$j]->n_sertifikasi_lembaga,
										"d_sertifikasi"=>(string)$result[$j]->d_sertifikasi,
										"d_mulai_sertifikasi"=>(string)$result[$j]->d_mulai_sertifikasi,
										"d_akhir_sertifikasi"=>(string)$result[$j]->d_akhir_sertifikasi,
										"e_keterangan"=>(string)$result[$j]->e_keterangan,
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
							"i_sertifikasi"=>$data['i_sertifikasi'],
							"n_sertifikasi"=>$data['n_sertifikasi'],
							"n_sertifikasi_lembaga"=>$data['n_sertifikasi_lembaga'],
							"d_sertifikasi"=>$data['d_sertifikasi'],
							"d_mulai_sertifikasi"=>$data['d_mulai_sertifikasi'],
							"d_akhir_sertifikasi"=>$data['d_akhir_sertifikasi'],
							"e_keterangan"=>$data['e_keterangan'],
							"i_entry"=>$data['i_entry'],
							"d_entry"=>date("Y-m-d"));
		if ($par=='insert'){$db->insert('sdm.tm_sertifikasi',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tm_sertifikasi',$maintain_data, "id = '".trim($data['id'])."'");}	 
		if ($par=='delete'){$db->delete('sdm.tm_sertifikasi', "id = '".trim($data['id'])."'");}
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