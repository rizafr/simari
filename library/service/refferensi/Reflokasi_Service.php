<?php
class reflokasi_Service {
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

	public function getLokasiList($cari, $currentPage, $numToDisplay) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
				$sql = "select c_lokasi, n_lokasi, c_status from sdm.tr_lokasi where 1=1 $cari";
				
				if(($currentPage == 0) && ($numToDisplay == 0)){
					$data = $db->fetchOne("select count(*) from ($sql) a");
				} else {
					$xLimit=$numToDisplay;
			  		$xOffset=($currentPage-1)*$numToDisplay;	
					
					$result = $db->fetchAll("$sql order by c_lokasi limit $xLimit offset $xOffset");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("c_lokasi"=>(string)$result[$j]->c_lokasi,
								  "n_lokasi"=>(string)$result[$j]->n_lokasi,								 
								  "c_status"=>(string)$result[$j]->c_status);}
				}
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambahlokasi(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_lokasi = $dataMasukan['n_lokasi'];
			$c_lokasi = $dataMasukan['c_lokasi'];
			$c_status = $dataMasukan['c_status'];
			
			$paramInput = array("n_lokasi"	=> $n_lokasi,
					"c_lokasi"	=> $c_lokasi,
					"c_status"	=> $c_status);
					//var_dump($paramInput);
			$db->insert('sdm.tr_lokasi',$paramInput);
			$db->commit();
			
			return "sukses";
			
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			
			$errMsg = $errmsgArr[0];

			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				//return "gagal";
				return $e->getMessage();
			}
	   }
	}
	
	public function detailLokasi($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchRow("select c_lokasi, n_lokasi, c_status from sdm.tr_lokasi 
							where c_lokasi  = '".$masukan['c_lokasi']."'");
							
							/* echo "select c_agama, n_agama from sdm.tr_agama 
							where c_agama  = '".$masukan['c_agama']."'"; */
				$jmlResult = count($result);
				$data = array("c_lokasi"=>(string)$result->c_lokasi,
					      "n_lokasi"=>(string)$result->n_lokasi,
					      "c_status"=>(string)$result->c_status);
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahlokasi(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_lokasi = $dataMasukan['n_lokasi'];
			$c_lokasi = $dataMasukan['c_lokasi'];
			$c_status = $dataMasukan['c_status'];
						
			$where[] = "c_lokasi = '".$c_lokasi."'";
			$paramInput = array("c_lokasi"	=> $c_lokasi,
					"n_lokasi"	=> $n_lokasi,
					"c_status"	=> $c_status);
					//var_dump($paramInput);
			$db->update('sdm.tr_lokasi',$paramInput, $where);
			$db->commit();
			
			return "sukses";
			
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			
			$errMsg = $errmsgArr[0];

			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				//return "gagal";
				return $e->getMessage();
			}
	   }
	}
	
	public function hapuslokasi(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$c_lokasi = $dataMasukan['c_lokasi'];			
			$where[] = "c_lokasi = '".$c_lokasi."'";
			
			//var_dump($where);
			$db->delete('sdm.tr_lokasi', $where);
			$db->commit();
			
			return "sukses";
			
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			
			$errMsg = $errmsgArr[0];

			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				//return "gagal";
				return $e->getMessage();
			}
	   }
	}
}
?>
