<?php
class Sdm_Importdata_Service {

private static $instance;
   
    // A private constructor; prevents direct creation of object
private function __construct() {
       //echo 'I am constructed';
    }

    // The singleton method
public static function getInstance() {
       if (!isset(self::$instance)) {
           $c = __CLASS__;
           self::$instance = new $c;
       }

       return self::$instance;
    }
	

public function maintainData(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->beginTransaction();
		$maintain_data = array("i_peg_nip"=>$data['i_peg_nip'],
					"i_peg_nip_new"=>$data['i_peg_nip_new'],
					"n_peg"=>$data['n_peg'],
					"c_peg_jeniskelamin"=>$data['c_peg_jeniskelamin'],
					"c_agama"=>$data['c_agama'],
					"c_peg_statusnikah"=>$data['c_peg_statusnikah'],
					"d_peg_lahir"=>$data['d_peg_lahir'],
					"a_peg_kota_lahir"=>$data['a_peg_kota_lahir'],
					"c_stat_aktivasi"=>$data['c_stat_aktivasi'],
					"d_entry"=>$data['d_entry']);
		$db->insert('sdm.tm_pegawai',$maintain_data);		
		$db->commit();
		return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}


public function getPegawaiListByNip($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{		
				
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT i_peg_nip FROM sdm.tm_pegawai where 1=1 $cari");									
										
									
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
						$data[$j] = array("i_peg_nip"=>(string)$result[$j]->i_peg_nip);
					}
									
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}

	
}
?>