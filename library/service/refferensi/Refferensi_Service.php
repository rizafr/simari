<?php
class refferensi_Service {
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

	public function getGolonganPegawai($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("select c_peg_golongan ,n_peg_pangkat  from sdm_golongan_pangkat_tr where 1=1 $cari ");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{$data[$j] = array("c_peg_golongan"=>(string)$result[$j]->c_peg_golongan,
										"n_peg_pangkat"=>(string)$result[$j]->n_peg_pangkat);}
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	

	public function getStatusPegawai($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("select c_peg_status ,n_peg_status  from e_sdm_status_pegawai_tr where 1=1 $cari ");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{$data[$j] = array("c_peg_status"=>(string)$result[$j]->c_peg_status,
										"n_peg_status"=>(string)$result[$j]->n_peg_status);}
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}

	public function getPendidikan($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT c_pend, n_pend
					FROM e_sdm_pendidikan_tr  where 1=1  $cari order by c_pend asc");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{$data[$j] = array("c_pend"=>(string)$result[$j]->c_pend,
										"n_pend"=>(string)$result[$j]->n_pend);}
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}


}
?>
