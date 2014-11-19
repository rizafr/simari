<?php
class Aset_Statistik_Service {
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
	
public function getCountDataForEis($tahunanggaran) 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{		
			$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchAll(" select count(a.kd_brg) as jumlah,substr(kd_lokasi,10,6) as c_satker,b.kd_gol
							from aset.tm_masterhm a,aset.tm_gol b,aset.tm_sskel c
							where a.kd_brg = c.kd_brg and b.kd_gol = c.kd_gol
							and a.thn_ang <= '$tahunanggaran' and substr(kd_lokasi,10,6) 
							in ('097450','663712','663122','610378','663136','663157')
							group by kd_lokasi,b.kd_gol order by b.kd_gol");
				$jmlResult = count($result);
				for ($j = 0; $j < $jmlResult; $j++) 
				{ 
					$data[$j] = array("c_satker"=>(string)$result[$j]->c_satker,
					"jumlah"=>(string)$result[$j]->jumlah,
					"kd_gol"=>(string)$result[$j]->kd_gol);
				}
					
					
			return $data;
					
		} catch (Exception $e) 
		{
		       	echo $e->getMessage().'<br>';
		     	return 'Data tidak ada <br>';
		}
	}
	
public function getCountDataRpForEis($tahunanggaran) 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{		
			$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchAll("select sum(a.rph_aset) as jumlah,substr(kd_lokasi,10,6) as c_satker,b.kd_gol
							from aset.tm_masterhm a,aset.tm_gol b,aset.tm_sskel c
							where a.kd_brg = c.kd_brg and b.kd_gol = c.kd_gol
							and a.thn_ang <= '$tahunanggaran' and substr(kd_lokasi,10,6) 
							in ('097450','663712','663122','610378','663136','663157')
							group by kd_lokasi,b.kd_gol order by b.kd_gol");
				$jmlResult = count($result);
				for ($j = 0; $j < $jmlResult; $j++) 
				{ 
					$data[$j] = array("c_satker"=>(string)$result[$j]->c_satker,
							"jumlah"=>(string)$result[$j]->jumlah,
							"kd_gol"=>(string)$result[$j]->kd_gol);
				}
					
					
			return $data;
					
		} catch (Exception $e) 
		{
		       	echo $e->getMessage().'<br>';
		     	return 'Data tidak ada <br>';
		}
	}


public function getCountDataStokBarangForEis($tahunanggaran) 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{		
			$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchAll("select substr(kd_lokasi,10,6) as kd_lokasi,sum(stock) as jumlah,substr(kd_brg,0,6) as kd_brg
							from sedia.v_stok where  thn_ang in (select max(thn_ang) from sedia.v_stok)
							group by kd_lokasi,substr(kd_brg,0,6) order by substr(kd_brg,0,6)");
				$jmlResult = count($result);
				for ($j = 0; $j < $jmlResult; $j++) 
				{ 
					$data[$j] = array("c_satker"=>(string)$result[$j]->kd_lokasi,
							"jumlah"=>(string)$result[$j]->jumlah,
							"kd_brg"=>(string)$result[$j]->kd_brg);
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