<?php
class Keu_Statistik_Service {
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
	public function getCountDataTgrForEis($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{		
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll(" select c_satker,count(*) as jumlah from  keu.tm_tgr where c_satker in ('097450','663712','663122','610378','663136','663157')  $cari group by c_satker order by c_satker asc");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
						$data[$j] = array("c_satker"=>(string)$result[$j]->c_satker,"jumlah"=>(string)$result[$j]->jumlah);
					}
					
					
					return $data;
					
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
public function getCountDataAnggaranForEis($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{		
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll(" select c_satker,sum(q_jumlah) as jumlah from  rencana.tm_d_item where  c_satker in('097450','663712','663122','610378','663136','663157') and c_dept='005' $cari group by c_satker order by c_satker asc");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
						$data[$j] = array("c_satker"=>(string)$result[$j]->c_satker,"jumlah"=>(string)$result[$j]->jumlah);
					}
					
					
					return $data;
					
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}

/* public function getCountDataRealisasiForEis($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{		
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("  SELECT sum(a.q_jumlah) as q_jumlah,   (SELECT sum(v_keu_spp)
					      FROM  keu.trspp d inner join keu.tr_spp_dtl e  on e.i_spp = d.i_spp where 
					      d.c_satker in('097450','663712','663122','610378','663136','663157')   and e.i_mak = a.c_akun  and i_sp2d is not null and i_sp2d <> ''  
					      and d.c_prog = a.c_program and d.c_unit = a.c_unit  ) as jumlah
					      FROM rencana.tm_d_item  a 
					      inner join rencana.tm_giat  b 
					      on b.c_program = a.c_program and b.c_unit = a.c_unit 
					      and b.c_dept = a.c_dept and b.c_giat = a.c_giat
					      where a.c_dept = '005' and a.d_thang = '$thang'
					       and a.c_satker in('097450','663712','663122','610378','663136','663157')  
					       group by a.c_satker,a.c_akun, a.c_program,a.c_unit  ");
					       
	
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
						$data[$j] = array("c_satker"=>(string)$result[$j]->c_satker,"jumlah"=>(string)$result[$j]->jumlah);
					}
					
					
					return $data;
					
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	} */

public function getCountDataRealisasiForEis($thang) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{		
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll(" SELECT sum( v_keu_spp) as jumlah,b.c_satker FROM keu.tr_spp_dtl a 
								inner join keu.trspp b on b.i_spp = a.i_spp
								where b.c_satker in('097450','663712','663122','610378','663136','663157')  
								and date_part('year',b.d_spp) = $thang  
								group by b.c_satker ");
	
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
						$data[$j] = array("c_satker"=>(string)$result[$j]->c_satker,"jumlah"=>(string)$result[$j]->jumlah);
					}
					
					
					return $data;
					
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}	

    

public function getCountDataRencanaForEis($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{		
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll(" select SUBSTRING( a.kdakun,0,3) as kdakun,b.nmgbkpk,sum(a.jumlah) as jumlah,kdsatker 
								from rencana_rkakl.d_item a
								left join rencana_rkakl.t_gbkpk b on SUBSTRING( a.kdakun,0,3)=b.kdgbkpk
								where $cari and kdsatker in ('097450','663712','663122','610378','663136','663157')
								and b.nmgbkpk is not null
								group by kdsatker,SUBSTRING( a.kdakun,0,3),b.nmgbkpk");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
						$data[$j] = array("c_satker"=>(string)$result[$j]->kdsatker,
						"jumlah"=>(string)$result[$j]->jumlah,
						"kdakun"=>(string)$result[$j]->kdakun);
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