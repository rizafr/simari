<?php
class refteknis_Service {
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

	public function getTeknisList($cari, $currentPage, $numToDisplay) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
				$sql = "select c_diklat_teknis, n_diklat_teknis, c_kelompok, n_kelompok from sdm.tr_diklat_teknis where 1=1 $cari";
				
				if(($currentPage == 0) && ($numToDisplay == 0)){
					$data = $db->fetchOne("select count(*) from ($sql) a");
				} else {
					$xLimit=$numToDisplay;
			  		$xOffset=($currentPage-1)*$numToDisplay;	
					
					$result = $db->fetchAll("$sql order by c_diklat_teknis limit $xLimit offset $xOffset");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("c_diklat_teknis"=>(string)$result[$j]->c_diklat_teknis,
								  "n_diklat_teknis"=>(string)$result[$j]->n_diklat_teknis,								 
								  "c_kelompok"=>(string)$result[$j]->c_kelompok,
								  "n_kelompok"=>(string)$result[$j]->n_kelompok);}
				}
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambahteknis(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$c_diklat_teknis = $dataMasukan['c_diklat_teknis'];
			$n_diklat_teknis = $dataMasukan['n_diklat_teknis'];
			$c_kelompok = $dataMasukan['c_kelompok'];
			$n_kelompok = $dataMasukan['n_kelompok'];
			
			$paramInput = array("c_diklat_teknis"	=> $c_diklat_teknis,
					"n_diklat_teknis"	=> $n_diklat_teknis,
					"c_kelompok"	=> $c_kelompok,
					"n_kelompok"	=> $n_kelompok);
					//var_dump($paramInput);
			$db->insert('sdm.tr_diklat_teknis',$paramInput);
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
	
	public function detailTeknis($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchRow("select c_diklat_teknis, n_diklat_teknis, c_kelompok, n_kelompok from sdm.tr_diklat_teknis 
							where c_diklat_teknis  = '".$masukan['c_diklat_teknis']."'");
							
							/* echo "select c_agama, n_agama from sdm.tr_agama 
							where c_agama  = '".$masukan['c_agama']."'"; */
				$jmlResult = count($result);
				$data = array("c_diklat_teknis"=>(string)$result->c_diklat_teknis,
					      "n_diklat_teknis"=>(string)$result->n_diklat_teknis,
					      "c_kelompok"=>(string)$result->c_kelompok,
					      "n_kelompok"=>(string)$result->n_kelompok);
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahteknis(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$c_diklat_teknis = $dataMasukan['c_diklat_teknis'];
			$n_diklat_teknis = $dataMasukan['n_diklat_teknis'];
			$c_kelompok = $dataMasukan['c_kelompok'];
			$n_kelompok = $dataMasukan['n_kelompok'];
						
			$where[] = "c_diklat_teknis = '".$c_diklat_teknis."'";
			$paramInput = array("c_diklat_teknis"	=> $c_diklat_teknis,
					"n_diklat_teknis"	=> $n_diklat_teknis,
					"c_kelompok"	=> $c_kelompok,
					"n_kelompok"	=> $n_kelompok);
					//var_dump($paramInput);
			$db->update('sdm.tr_diklat_teknis',$paramInput, $where);
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
	
	public function hapusteknis(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$c_diklat_teknis = $dataMasukan['c_diklat_teknis'];			
			$where[] = "c_diklat_teknis = '".$c_diklat_teknis."'";
			
			//var_dump($where);
			$db->delete('sdm.tr_diklat_teknis', $where);
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
