<?php
class refjenjang_Service {
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

	public function getJenjangList($cari, $currentPage, $numToDisplay) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
				$sql = "select c_jenjang, n_jenjang, q_level from sdm.tr_diklat_penjenjangan where 1=1 $cari";
				
				if(($currentPage == 0) && ($numToDisplay == 0)){
					$data = $db->fetchOne("select count(*) from ($sql) a");
				} else {
					$xLimit=$numToDisplay;
			  		$xOffset=($currentPage-1)*$numToDisplay;	
					
					$result = $db->fetchAll("$sql order by c_jenjang limit $xLimit offset $xOffset");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("c_jenjang"=>(string)$result[$j]->c_jenjang,
								  "n_jenjang"=>(string)$result[$j]->n_jenjang,								 
								  "q_level"=>(string)$result[$j]->q_level);}
				}
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambahjenjang(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_jenjang = $dataMasukan['n_jenjang'];
			$c_jenjang = $dataMasukan['c_jenjang'];
			$q_level = $dataMasukan['q_level'];
			
			$paramInput = array("n_jenjang"	=> $n_jenjang,
					"c_jenjang"	=> $c_jenjang,
					"q_level"	=> $q_level);
					//var_dump($paramInput);
			$db->insert('sdm.tr_diklat_penjenjangan',$paramInput);
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
	
	public function detailJenjang($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchRow("select c_jenjang, n_jenjang, q_level from sdm.tr_diklat_penjenjangan 
							where c_jenjang  = '".$masukan['c_jenjang']."'");
							
							/* echo "select c_agama, n_agama from sdm.tr_agama 
							where c_agama  = '".$masukan['c_agama']."'"; */
				$jmlResult = count($result);
				$data = array("c_jenjang"=>(string)$result->c_jenjang,
					      "n_jenjang"=>(string)$result->n_jenjang,
					      "q_level"=>(string)$result->q_level);
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahjenjang(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_jenjang = $dataMasukan['n_jenjang'];
			$c_jenjang = $dataMasukan['c_jenjang'];
			$q_level = $dataMasukan['q_level'];
						
			$where[] = "c_jenjang = '".$c_jenjang."'";
			$paramInput = array("c_jenjang"	=> $c_jenjang,
					"n_jenjang"	=> $n_jenjang,
					"q_level"	=> $q_level);
					//var_dump($paramInput);
			$db->update('sdm.tr_diklat_penjenjangan',$paramInput, $where);
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
	
	public function hapusjenjang(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$c_jenjang = $dataMasukan['c_jenjang'];			
			$where[] = "c_jenjang = '".$c_jenjang."'";
			
			//var_dump($where);
			$db->delete('sdm.tr_diklat_penjenjangan', $where);
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
