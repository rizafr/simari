<?php
class refkelfungsional_Service {
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

	public function getKelfungsionalList($cari, $currentPage, $numToDisplay) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
				$sql = "select c_kelfungsional, n_kelfungsional, c_filter from sdm.tr_kelfungsional where 1=1 $cari";
				
				if(($currentPage == 0) && ($numToDisplay == 0)){
					$data = $db->fetchOne("select count(*) from ($sql) a");
				} else {
					$xLimit=$numToDisplay;
			  		$xOffset=($currentPage-1)*$numToDisplay;	
					
					$result = $db->fetchAll("$sql order by c_kelfungsional limit $xLimit offset $xOffset");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("c_kelfungsional"=>(string)$result[$j]->c_kelfungsional,
								  "n_kelfungsional"=>(string)$result[$j]->n_kelfungsional,								 
								  "c_filter"=>(string)$result[$j]->c_filter);}
				}
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambahkelfungsional(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_kelfungsional = $dataMasukan['n_kelfungsional'];
			$c_kelfungsional = $dataMasukan['c_kelfungsional'];
			$c_filter = $dataMasukan['c_filter'];
			
			$paramInput = array("n_kelfungsional"	=> $n_kelfungsional,
					"c_kelfungsional"	=> $c_kelfungsional,
					"c_filter"	=> $c_filter);
					//var_dump($paramInput);
			$db->insert('sdm.tr_kelfungsional',$paramInput);
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
	
	public function detailKelfungsional($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchRow("select c_kelfungsional, n_kelfungsional, c_filter from sdm.tr_kelfungsional 
							where c_kelfungsional  = '".$masukan['c_kelfungsional']."'");
							
							/* echo "select c_agama, n_agama from sdm.tr_agama 
							where c_agama  = '".$masukan['c_agama']."'"; */
				$jmlResult = count($result);
				$data = array("c_kelfungsional"=>(string)$result->c_kelfungsional,
					      "n_kelfungsional"=>(string)$result->n_kelfungsional,
					      "c_filter"=>(string)$result->c_filter);
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahkelfungsional(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_kelfungsional = $dataMasukan['n_kelfungsional'];
			$c_kelfungsional = $dataMasukan['c_kelfungsional'];
			$c_filter = $dataMasukan['c_filter'];
						
			$where[] = "c_kelfungsional = '".$c_kelfungsional."'";
			$paramInput = array("c_kelfungsional"	=> $c_kelfungsional,
					"n_kelfungsional"	=> $n_kelfungsional,
					"c_filter"	=> $c_filter);
					//var_dump($paramInput);
			$db->update('sdm.tr_kelfungsional',$paramInput, $where);
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
	
	public function hapuskelfungsional(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$c_kelfungsional = $dataMasukan['c_kelfungsional'];			
			$where[] = "c_kelfungsional = '".$c_kelfungsional."'";
			
			//var_dump($where);
			$db->delete('sdm.tr_kelfungsional', $where);
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
