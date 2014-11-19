<?php
class Sdm_Kepangkatan_Service {
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

 	public function getKepangkatanList($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{

				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("select id,i_peg_nip,c_jns_kepangkatan,to_char(d_test_kepangkatan,'dd-mm-yyyy') as d_test_kepangkatan,
								a_tempat_test,c_hasil_kepangkatan,i_sk_kepangkatan,
								to_char(d_sk_kepangkatan,'dd-mm-yyyy') as d_sk_kepangkatan,n_pejabat_kepangkatan,
								i_entry,d_entry
								FROM sdm.tm_kepangkatan where 1=1 $cari  order by d_test_kepangkatan asc");		
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
	
						$n_jns_kepangkatan = $db->fetchOne('SELECT n_jns_kepangkatan FROM sdm.tr_jns_kepangkatan WHERE c_jns_kepangkatan = ?',$result[$j]->c_jns_kepangkatan);
						if ((string)$result[$j]->c_hasil_kepangkatan=="1"){$n_hasil_kepangkatan="Lulus";}
						if ((string)$result[$j]->c_hasil_kepangkatan=="2"){$n_hasil_kepangkatan="Tidak Lulus";}
						
						$data[$j] = array("id"=>(string)$result[$j]->id,
								"i_peg_nip"=>(string)$result[$j]->i_peg_nip,
								"c_jns_kepangkatan"=>(string)$result[$j]->c_jns_kepangkatan,
								"n_jns_kepangkatan"=>$n_jns_kepangkatan,
								"d_test_kepangkatan"=>(string)$result[$j]->d_test_kepangkatan,
								"a_tempat_test"=>(string)$result[$j]->a_tempat_test,
								"c_hasil_kepangkatan"=>(string)$result[$j]->c_hasil_kepangkatan,
								"n_hasil_kepangkatan"=>$n_hasil_kepangkatan,
								"i_sk_kepangkatan"=>(string)$result[$j]->i_sk_kepangkatan,								
								"d_sk_kepangkatan"=>(string)$result[$j]->d_sk_kepangkatan,
								"n_pejabat_kepangkatan"=>(string)$result[$j]->n_pejabat_kepangkatan,
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
				"c_jns_kepangkatan"=>$data['c_jns_kepangkatan'],
				"d_test_kepangkatan"=>$data['d_test_kepangkatan'],
				"a_tempat_test"=>$data['a_tempat_test'],
				"c_hasil_kepangkatan"=>$data['c_hasil_kepangkatan'],
				"i_sk_kepangkatan"=>$data['i_sk_kepangkatan'],
				"d_sk_kepangkatan"=>$data['d_sk_kepangkatan'],
				"n_pejabat_kepangkatan"=>$data['n_pejabat_kepangkatan'],
				"i_entry"=>$data['i_entry'],
				"d_entry"=>date("Y-m-d"));

		if ($par=='insert'){$db->insert('sdm.tm_kepangkatan',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tm_kepangkatan',$maintain_data, "id = '".trim($data['id'])."'");}	 
		if ($par=='delete'){$db->delete('sdm.tm_kepangkatan', "id = '".trim($data['id'])."'");}	 
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