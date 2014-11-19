<?php
class refhukuman_Service {
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

	public function getHukumanList($cari, $currentPage, $numToDisplay) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
				$sql = "select c_hukuman, n_hukuman, q_tingkat_hukuman from sdm.tr_jenis_hukuman where 1=1 $cari";
				
				if(($currentPage == 0) && ($numToDisplay == 0)){
					$data = $db->fetchOne("select count(*) from ($sql) a");
				} else {
					$xLimit=$numToDisplay;
			  		$xOffset=($currentPage-1)*$numToDisplay;	
					
					$result = $db->fetchAll("$sql order by c_hukuman limit $xLimit offset $xOffset");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("c_hukuman"=>(string)$result[$j]->c_hukuman,
								  "n_hukuman"=>(string)$result[$j]->n_hukuman,								 
								  "q_tingkat_hukuman"=>(string)$result[$j]->q_tingkat_hukuman);}
				}
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambahhukuman(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_hukuman = $dataMasukan['n_hukuman'];
			$c_hukuman = $dataMasukan['c_hukuman'];
			$q_tingkat_hukuman = $dataMasukan['q_tingkat_hukuman'];
			
			$paramInput = array("n_hukuman"	=> $n_hukuman,
					"c_hukuman"	=> $c_hukuman,
					"q_tingkat_hukuman"	=> $q_tingkat_hukuman);
					//var_dump($paramInput);
			$db->insert('sdm.tr_jenis_hukuman',$paramInput);
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
	
	public function detailHukuman($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchRow("select c_hukuman, n_hukuman, q_tingkat_hukuman from sdm.tr_jenis_hukuman 
							where c_hukuman  = '".$masukan['c_hukuman']."'");
							
							/* echo "select c_agama, n_agama from sdm.tr_agama 
							where c_agama  = '".$masukan['c_agama']."'"; */
				$jmlResult = count($result);
				$data = array("c_hukuman"=>(string)$result->c_hukuman,
					      "n_hukuman"=>(string)$result->n_hukuman,
					      "q_tingkat_hukuman"=>(string)$result->q_tingkat_hukuman);
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahhukuman(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_hukuman = $dataMasukan['n_hukuman'];
			$c_hukuman = $dataMasukan['c_hukuman'];
			$q_tingkat_hukuman = $dataMasukan['q_tingkat_hukuman'];
						
			$where[] = "c_hukuman = '".$c_hukuman."'";
			$paramInput = array("c_hukuman"	=> $c_hukuman,
					"n_hukuman"	=> $n_hukuman,
					"q_tingkat_hukuman"	=> $q_tingkat_hukuman);
					//var_dump($paramInput);
			$db->update('sdm.tr_jenis_hukuman',$paramInput, $where);
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
	
	public function hapushukuman(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$c_hukuman = $dataMasukan['c_hukuman'];			
			$where[] = "c_hukuman = '".$c_hukuman."'";
			
			//var_dump($where);
			$db->delete('sdm.tr_jenis_hukuman', $where);
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
