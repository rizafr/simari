<?php
class Sdm_Seminar_Service {
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

 	public function getSeminarList($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT  id,i_peg_nip,n_seminar,to_char(d_mulai_seminar,'dd-mm-yyyy') as d_mulai_seminar,
										to_char(d_akhir_seminar,'dd-mm-yyyy') as d_akhir_seminar,n_seminar_peran,n_seminar_lembaga,
										a_seminar,e_keterangan,i_entry,d_entry
										FROM sdm.tm_seminar where 1=1 $cari  order by d_mulai_seminar asc");		
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 		
						$data[$j] = array("id"=>(string)$result[$j]->id,
										"i_peg_nip"=>(string)$result[$j]->i_peg_nip,
										"n_seminar"=>(string)$result[$j]->n_seminar,
										"d_mulai_seminar"=>(string)$result[$j]->d_mulai_seminar,
										"d_akhir_seminar"=>(string)$result[$j]->d_akhir_seminar,
										"n_seminar_peran"=>(string)$result[$j]->n_seminar_peran,
										"n_seminar_lembaga"=>(string)$result[$j]->n_seminar_lembaga,
										"a_seminar"=>(string)$result[$j]->a_seminar,
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
							"n_seminar"=>$data['n_seminar'],
							"d_mulai_seminar"=>$data['d_mulai_seminar'],
							"d_akhir_seminar"=>$data['d_akhir_seminar'],
							"n_seminar_peran"=>$data['n_seminar_peran'],
							"n_seminar_lembaga"=>$data['n_seminar_lembaga'],
							"a_seminar"=>$data['a_seminar'],
							"e_keterangan"=>$data['e_keterangan'],
							"i_entry"=>$data['i_entry'],
							"d_entry"=>date("Y-m-d"));

		if ($par=='insert'){$db->insert('sdm.tm_seminar',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tm_seminar',$maintain_data, "id = '".trim($data['id'])."'");}	 
		if ($par=='delete'){$db->delete('sdm.tm_seminar', "id = '".trim($data['id'])."'");}
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