<?php
class Sdm_Kesehatan_Service {
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

 	public function getKesehatanList($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT id,i_peg_nip,to_char(d_rawatmulai,'dd-mm-yyyy') as d_rawatmulai,
											  to_char(d_rawatakhir,'dd-mm-yyyy') as d_rawatakhir,
											  n_rmhsakit,a_rmhsakit,n_penyakit,e_keterangan,i_entry,d_entry
											FROM sdm.tm_kesehatan where 1=1 $cari  order by d_rawatmulai asc");		
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 		
						$data[$j] = array("id"=>(string)$result[$j]->id,
										"i_peg_nip"=>(string)$result[$j]->i_peg_nip,
										"d_rawatmulai"=>(string)$result[$j]->d_rawatmulai,
										"d_rawatakhir"=>(string)$result[$j]->d_rawatakhir,
										"n_rmhsakit"=>(string)$result[$j]->n_rmhsakit,
										"a_rmhsakit"=>(string)$result[$j]->a_rmhsakit,
										"n_penyakit"=>(string)$result[$j]->n_penyakit,
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
									"d_rawatmulai"=>$data['d_rawatmulai'],
									"d_rawatakhir"=>$data['d_rawatakhir'],
									"n_rmhsakit"=>$data['n_rmhsakit'],
									"a_rmhsakit"=>$data['a_rmhsakit'],
									"n_penyakit"=>$data['n_penyakit'],
									"e_keterangan"=>$data['e_keterangan'],
									"i_entry"=>$data['i_entry'],
									"d_entry"=>date("Y-m-d"));

		if ($par=='insert'){$db->insert('sdm.tm_kesehatan',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tm_kesehatan',$maintain_data, "id = '".trim($data['id'])."'");}	 
		if ($par=='delete'){$db->delete('sdm.tm_kesehatan', "id = '".trim($data['id'])."'");}
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