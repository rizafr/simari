<?php
class refuniversitas_Service {
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

	public function getUniversitasList($cari, $currentPage, $numToDisplay) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
				$sql = "select c_universitas, n_universitas2, n_rayon_pro, q_strata, n_universitas from sdm.tr_universitas where 1=1 $cari";
				
				if(($currentPage == 0) && ($numToDisplay == 0)){
					$data = $db->fetchOne("select count(*) from ($sql) a");
				} else {
					$xLimit=$numToDisplay;
			  		$xOffset=($currentPage-1)*$numToDisplay;	
					
					$result = $db->fetchAll("$sql order by c_universitas limit $xLimit offset $xOffset");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("c_universitas"=>(string)$result[$j]->c_universitas,
								  "n_universitas2"=>(string)$result[$j]->n_universitas2,								 
								  "n_rayon_pro"=>(string)$result[$j]->n_rayon_pro,
								  "q_strata"=>(string)$result[$j]->q_strata,
								  "n_universitas"=>(string)$result[$j]->n_universitas);}
				}
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambahuniversitas(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$c_universitas = $dataMasukan['c_universitas'];
			$n_universitas2 = $dataMasukan['n_universitas2'];
			$n_rayon_pro = $dataMasukan['n_rayon_pro'];
			$q_strata = $dataMasukan['q_strata'];
			$n_universitas = $dataMasukan['n_universitas'];
			
			$paramInput = array("c_universitas"	=> $c_universitas,
					"n_universitas2"	=> $n_universitas2,
					"n_rayon_pro"	=> $n_rayon_pro,
					"q_strata"	=> $q_strata,
					"n_universitas"	=> $n_universitas);
					//var_dump($paramInput);
			$db->insert('sdm.tr_universitas',$paramInput);
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
	
	public function detailUniversitas($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchRow("select c_universitas, n_universitas2, n_rayon_pro, q_strata, n_universitas from sdm.tr_universitas 
							where c_universitas  = '".$masukan['c_universitas']."'");
							
							/* echo "select c_agama, n_agama from sdm.tr_agama 
							where c_agama  = '".$masukan['c_agama']."'"; */
				$jmlResult = count($result);
				$data = array("c_universitas"=>(string)$result->c_universitas,
					      "n_universitas2"=>(string)$result->n_universitas2,
					      "n_rayon_pro"=>(string)$result->n_rayon_pro,
					      "q_strata"=>(string)$result->q_strata,
						"n_universitas"=>(string)$result->n_universitas);
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahuniversitas(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$c_universitas = $dataMasukan['c_universitas'];
			$n_universitas2 = $dataMasukan['n_universitas2'];
			$n_rayon_pro = $dataMasukan['n_rayon_pro'];
			$q_strata = $dataMasukan['q_strata'];
			$n_universitas = $dataMasukan['n_universitas'];
						
			$where[] = "c_universitas = '".$c_universitas."'";
			$paramInput = array("c_universitas"	=> $c_universitas,
					"n_universitas2"	=> $n_universitas2,
					"n_rayon_pro"	=> $n_rayon_pro,
					"q_strata"	=> $q_strata,
					"n_universitas"	=> $n_universitas);
					//var_dump($paramInput);
			$db->update('sdm.tr_universitas',$paramInput, $where);
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
	
	public function hapusuniversitas(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$c_universitas = $dataMasukan['c_universitas'];			
			$where[] = "c_universitas = '".$c_universitas."'";
			
			//var_dump($where);
			$db->delete('sdm.tr_universitas', $where);
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
