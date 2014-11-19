<?php
class Sdm_Tpa_Service {
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

 	public function getTpaList($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{

				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("select id,i_peg_nip,to_char(d_test_akademik,'dd-mm-yyyy') as d_test_akademik,
								q_nilai_akademik,n_penyelenggara_akademik,e_tujuan_akademik,
								to_char(d_tmt_berlaku,'dd-mm-yyyy') as d_tmt_berlaku,i_entry,d_entry
								FROM sdm.tm_potensi_akademik where 1=1 $cari  order by d_test_akademik asc");		
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 

						$data[$j] = array("id"=>(string)$result[$j]->id,
								"i_peg_nip"=>(string)$result[$j]->i_peg_nip,
								"d_test_akademik"=>(string)$result[$j]->d_test_akademik,
								"q_nilai_akademik"=>(string)$result[$j]->q_nilai_akademik,
								"n_penyelenggara_akademik"=>(string)$result[$j]->n_penyelenggara_akademik,
								"e_tujuan_akademik"=>(string)$result[$j]->e_tujuan_akademik,
								"d_tmt_berlaku"=>(string)$result[$j]->d_tmt_berlaku,
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
				"d_test_akademik"=>$data['d_test_akademik'],
				"q_nilai_akademik"=>$data['q_nilai_akademik'],
				"n_penyelenggara_akademik"=>$data['n_penyelenggara_akademik'],
				"e_tujuan_akademik"=>$data['e_tujuan_akademik'],
				"d_tmt_berlaku"=>$data['d_tmt_berlaku'],
				"i_entry"=>$data['i_entry'],
				"d_entry"=>date("Y-m-d"));

		if ($par=='insert'){$db->insert('sdm.tm_potensi_akademik',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tm_potensi_akademik',$maintain_data, "id = '".trim($data['id'])."'");}	 
		if ($par=='delete'){$db->delete('sdm.tm_potensi_akademik', "id = '".trim($data['id'])."'");}
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