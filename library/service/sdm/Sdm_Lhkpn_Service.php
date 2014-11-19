<?php
class Sdm_Lhkpn_Service {
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

 	public function getLhkpnList($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{

				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("select id,i_peg_nip,d_tahun_lapor,i_nomor_lhkpn,c_formulira,c_formulirb,
											to_char(d_lhkpn,'dd-mm-yyyy') as d_lhkpn,i_entry,d_entry,i_nomor_lembaran
											FROM sdm.tm_lhkpn where 1=1 $cari order by d_tahun_lapor asc");		
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 

						$data[$j] = array("id"=>(string)$result[$j]->id,
										"i_peg_nip"=>(string)$result[$j]->i_peg_nip,
										"d_tahun_lapor"=>(string)$result[$j]->d_tahun_lapor,
										"i_nomor_lhkpn"=>(string)$result[$j]->i_nomor_lhkpn,
										"c_formulira"=>(string)$result[$j]->c_formulira,
										"c_formulirb"=>(string)$result[$j]->c_formulirb,
										"d_lhkpn"=>(string)$result[$j]->d_lhkpn,
										"i_nomor_lembaran"=>(string)$result[$j]->i_nomor_lembaran,
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
									"d_tahun_lapor"=>$data['d_tahun_lapor'],
									"i_nomor_lhkpn"=>$data['i_nomor_lhkpn'],
									"i_nomor_lembaran"=>$data['i_nomor_lembaran'],									
									"c_formulira"=>$data['c_formulira'],
									"c_formulirb"=>$data['c_formulirb'],
									"d_lhkpn"=>$data['d_lhkpn'],
									"i_entry"=>$data['i_entry'],
									"d_entry"=>date("Y-m-d"));

		if ($par=='insert'){$db->insert('sdm.tm_lhkpn',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tm_lhkpn',$maintain_data, "id = '".trim($data['id'])."'");}	 
		if ($par=='delete'){$db->delete('sdm.tm_lhkpn', "id = '".trim($data['id'])."'");}
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