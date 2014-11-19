<?php
class reftandajasa_Service {
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

	public function getTandajasaList($cari, $currentPage, $numToDisplay) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
				$sql = "select c_tandajasa, n_tandajasa, e_penerbit, e_jnama from sdm.tr_tandajasa where 1=1 $cari";
				
				if(($currentPage == 0) && ($numToDisplay == 0)){
					$data = $db->fetchOne("select count(*) from ($sql) a");
				} else {
					$xLimit=$numToDisplay;
			  		$xOffset=($currentPage-1)*$numToDisplay;	
					
					$result = $db->fetchAll("$sql order by c_tandajasa limit $xLimit offset $xOffset");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("c_tandajasa"=>(string)$result[$j]->c_tandajasa,
								  "n_tandajasa"=>(string)$result[$j]->n_tandajasa,								 
								  "e_penerbit"=>(string)$result[$j]->e_penerbit,
								  "e_jnama"=>(string)$result[$j]->e_jnama);}
				}
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambahtandajasa(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$c_tandajasa = $dataMasukan['c_tandajasa'];
			$n_tandajasa = $dataMasukan['n_tandajasa'];
			$e_penerbit = $dataMasukan['e_penerbit'];
			$e_jnama = $dataMasukan['e_jnama'];
			
			$paramInput = array("c_tandajasa"	=> $c_tandajasa,
					"n_tandajasa"	=> $n_tandajasa,
					"e_penerbit"	=> $e_penerbit,
					"e_jnama"	=> $e_jnama);
					//var_dump($paramInput);
			$db->insert('sdm.tr_tandajasa',$paramInput);
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
	
	public function detailTandajasa($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchRow("select c_tandajasa, n_tandajasa, e_penerbit, e_jnama from sdm.tr_tandajasa 
							where c_tandajasa  = '".$masukan['c_tandajasa']."'");
							
							/* echo "select c_agama, n_agama from sdm.tr_agama 
							where c_agama  = '".$masukan['c_agama']."'"; */
				$jmlResult = count($result);
				$data = array("c_tandajasa"=>(string)$result->c_tandajasa,
					      "n_tandajasa"=>(string)$result->n_tandajasa,
					      "e_penerbit"=>(string)$result->e_penerbit,
					      "e_jnama"=>(string)$result->e_jnama);
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahtandajasa(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$c_tandajasa = $dataMasukan['c_tandajasa'];
			$n_tandajasa = $dataMasukan['n_tandajasa'];
			$e_penerbit = $dataMasukan['e_penerbit'];
			$e_jnama = $dataMasukan['e_jnama'];
						
			$where[] = "c_tandajasa = '".$c_tandajasa."'";
			$paramInput = array("c_tandajasa"	=> $c_tandajasa,
					"n_tandajasa"	=> $n_tandajasa,
					"e_penerbit"	=> $e_penerbit,
					"e_jnama"	=> $e_jnama);
					//var_dump($paramInput);
			$db->update('sdm.tr_tandajasa',$paramInput, $where);
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
	
	public function hapustandajasa(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$c_tandajasa = $dataMasukan['c_tandajasa'];			
			$where[] = "c_tandajasa = '".$c_tandajasa."'";
			
			//var_dump($where);
			$db->delete('sdm.tr_tandajasa', $where);
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
