<?php
class refkejuruanmil_Service {
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

	public function getKejuruanmilList($cari, $currentPage, $numToDisplay) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
				$sql = "select c_kejuruanmil, n_kejuruanmil from sdm.tr_kejuruanmiliter where 1=1 $cari";
				
				if(($currentPage == 0) && ($numToDisplay == 0)){
					$data = $db->fetchOne("select count(*) from ($sql) a");
				} else {
					$xLimit=$numToDisplay;
			  		$xOffset=($currentPage-1)*$numToDisplay;	
					
					$result = $db->fetchAll("$sql order by c_kejuruanmil limit $xLimit offset $xOffset");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("c_kejuruanmil"=>(string)$result[$j]->c_kejuruanmil,
								  "n_kejuruanmil"=>(string)$result[$j]->n_kejuruanmil);}
				}
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambahkejuruanmil(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_kejuruanmil = $dataMasukan['n_kejuruanmil'];
			$c_kejuruanmil = $dataMasukan['c_kejuruanmil'];
			$i_entry = $dataMasukan['i_entry'];
			
			$paramInput = array("n_kejuruanmil"	=> $n_kejuruanmil,
					"c_kejuruanmil"	=> $c_kejuruanmil,
					"i_entry"	=> $i_entry,
					"d_entry"	=> date('Y-m-d'));
					//var_dump($paramInput);
			$db->insert('sdm.tr_kejuruanmiliter',$paramInput);
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
	
	public function detailKejuruanmil($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchRow("select c_kejuruanmil, n_kejuruanmil from sdm.tr_kejuruanmiliter
							where c_kejuruanmil  = '".$masukan['c_kejuruanmil']."'");
							
							/* echo "select c_agama, n_agama from sdm.tr_agama 
							where c_agama  = '".$masukan['c_agama']."'"; */
				$jmlResult = count($result);
				$data = array("c_kejuruanmil"=>(string)$result->c_kejuruanmil,
					      "n_kejuruanmil"=>(string)$result->n_kejuruanmil);
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahkejuruanmil(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_kejuruanmil = $dataMasukan['n_kejuruanmil'];
			$c_kejuruanmil = $dataMasukan['c_kejuruanmil'];
			$i_entry = $dataMasukan['i_entry'];
			
			$where[] = "c_kejuruanmil = '".$c_kejuruanmil."'";
			$paramInput = array("c_kejuruanmil"	=> $c_kejuruanmil,
					"n_kejuruanmil"	=> $n_kejuruanmil,
					"i_entry"	=> $i_entry,
					"d_entry"	=> date('Y-m-d'));
					//var_dump($paramInput);
			$db->update('sdm.tr_kejuruanmiliter',$paramInput, $where);
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
	
	public function hapuskejuruanmil(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$c_kejuruanmil = $dataMasukan['c_kejuruanmil'];			
			$where[] = "c_kejuruanmil = '".$c_kejuruanmil."'";
			
			//var_dump($where);
			$db->delete('sdm.tr_kejuruanmiliter', $where);
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
