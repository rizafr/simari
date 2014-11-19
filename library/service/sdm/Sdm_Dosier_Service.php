<?php
class Sdm_Dosier_Service {
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

 	public function getDosierList($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
			
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("select i_peg_nip,c_jns_dokumen_sk,i_dokumen_sk,n_dokumen_sk,
								to_char(d_dokumen_sk,'dd-mm-yyyy') as d_dokumen_sk,e_file_sk
								FROM sdm.tm_dosier where 1=1 $cari  order by c_jns_dokumen_sk asc");		
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 				
						$c_jns_dokumen_sk=$result[$j]->c_jns_dokumen_sk;
						$n_dokumen=$db->fetchOne("select n_dokumen from sdm.tr_jenis_dokumen where c_dokumen ='$c_jns_dokumen_sk'");
						$data[$j] = array("i_peg_nip"=>(string)$result[$j]->i_peg_nip,
								"c_jns_dokumen_sk"=>(string)$result[$j]->c_jns_dokumen_sk,
								"i_dokumen_sk"=>(string)$result[$j]->i_dokumen_sk,
								"n_dokumen_sk"=>(string)$result[$j]->n_dokumen_sk,
								"d_dokumen_sk"=>(string)$result[$j]->d_dokumen_sk,
								"e_file_sk"=>(string)$result[$j]->e_file_sk,
								"n_dokumen"=>$n_dokumen,
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
				"c_jns_dokumen_sk"=>$data['c_jns_dokumen_sk'],
				"i_dokumen_sk"=>$data['i_dokumen_sk'],
				"n_dokumen_sk"=>$data['n_dokumen_sk'],
				"d_dokumen_sk"=>$data['d_dokumen_sk'],
				"d_dokumen_sk"=>$data['d_dokumen_sk'],
				"e_file_sk"=>$data['e_file_sk'],
				"i_entry"=>$data['i_entry'],
				"d_entry"=>date("Y-m-d"));

		if ($par=='insert'){$db->insert('sdm.tm_dosier',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tm_dosier',$maintain_data, "i_peg_nip = '".trim($data['i_peg_nip'])."'  and c_jns_dokumen_sk = '".trim($data['c_jns_dokumen_sk2'])."'  and i_dokumen_sk = '".trim($data['i_dokumen_sk2'])."' ");}	 
		if ($par=='delete'){$db->delete('sdm.tm_dosier', "i_peg_nip = '".trim($data['i_peg_nip'])."' and c_jns_dokumen_sk = '".trim($data['c_jns_dokumen_sk'])."'  and i_dokumen_sk = '".trim($data['i_dokumen_sk'])."'  ");}
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