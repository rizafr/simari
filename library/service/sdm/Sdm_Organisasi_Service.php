<?php
class Sdm_Organisasi_Service {
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

 	public function getOrganisasiList($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
		
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT  id,i_peg_nip,n_jenis_organisasi,n_organisasi,d_daftar_organisasi,
										n_peran_organisasi,n_tempat_organisasi,e_keterangan	,i_entry,d_entry
										FROM sdm.tm_organisasi where 1=1 $cari  order by d_daftar_organisasi asc");		
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 		
						$data[$j] = array("id"=>(string)$result[$j]->id,"i_peg_nip"=>(string)$result[$j]->i_peg_nip,
										"n_jenis_organisasi"=>(string)$result[$j]->n_jenis_organisasi,
										"n_organisasi"=>(string)$result[$j]->n_organisasi,
										"d_daftar_organisasi"=>(string)$result[$j]->d_daftar_organisasi,
										"n_peran_organisasi"=>(string)$result[$j]->n_peran_organisasi,
										"n_tempat_organisasi"=>(string)$result[$j]->n_tempat_organisasi,
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
							"n_jenis_organisasi"=>$data['n_jenis_organisasi'],
							"n_organisasi"=>$data['n_organisasi'],
							"d_daftar_organisasi"=>$data['d_daftar_organisasi'],
							"n_peran_organisasi"=>$data['n_peran_organisasi'],
							"n_tempat_organisasi"=>$data['n_tempat_organisasi'],
							"e_keterangan"=>$data['e_keterangan'],
							"i_entry"=>$data['i_entry'],
							"d_entry"=>date("Y-m-d"));
							
		if ($par=='insert'){$db->insert('sdm.tm_organisasi',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tm_organisasi',$maintain_data, "id = '".trim($data['id'])."'");}	 
		if ($par=='delete'){$db->delete('sdm.tm_organisasi', "id = '".trim($data['id'])."'");}
		//if ($par=='update'){$db->update('sdm.tm_organisasi',$maintain_data, "i_peg_nip = '".trim($data['i_peg_nip'])."' and n_organisasi = '".trim($data['n_organisasi2'])."' and n_jenis_organisasi = '".trim($data['n_jenis_organisasi2'])."' and d_daftar_organisasi = '".trim($data['d_daftar_organisasi2'])."'");}	 
		//if ($par=='delete'){$db->delete('sdm.tm_organisasi', "i_peg_nip = '".trim($data['i_peg_nip'])."'  and n_organisasi = '".trim($data['n_organisasi'])."' and n_jenis_organisasi = '".trim($data['n_jenis_organisasi'])."'  and d_daftar_organisasi = '".trim($data['d_daftar_organisasi'])."'");}
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