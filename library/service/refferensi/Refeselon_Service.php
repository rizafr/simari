<?php
class refeselon_Service {
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

	public function getEselonList($cari, $currentPage, $numToDisplay) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
				$sql = "select c_eselon, n_eselon, n_eselonb from sdm.tr_eselon where 1=1 $cari";
				
				if(($currentPage == 0) && ($numToDisplay == 0)){
					$data = $db->fetchOne("select count(*) from ($sql) a");
				} else {
					$xLimit=$numToDisplay;
			  		$xOffset=($currentPage-1)*$numToDisplay;	
					
					$result = $db->fetchAll("$sql order by c_eselon limit $xLimit offset $xOffset");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("c_eselon"=>(string)$result[$j]->c_eselon,
								  "n_eselon"=>(string)$result[$j]->n_eselon,								 
								  "n_eselonb"=>(string)$result[$j]->n_eselonb);}
				}
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambaheselon(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_eselon = $dataMasukan['n_eselon'];
			$c_eselon = $dataMasukan['c_eselon'];
			$n_eselonb = $dataMasukan['n_eselonb'];
			
			$paramInput = array("n_eselon"	=> $n_eselon,
					"c_eselon"	=> $c_eselon,
					"n_eselonb"	=> $n_eselonb);
					//var_dump($paramInput);
			$db->insert('sdm.tr_eselon',$paramInput);
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
	
	public function detailEselon($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchRow("select c_eselon, n_eselon, n_eselonb from sdm.tr_eselon 
							where c_eselon  = '".$masukan['c_eselon']."'");
							
							/* echo "select c_agama, n_agama from sdm.tr_agama 
							where c_agama  = '".$masukan['c_agama']."'"; */
				$jmlResult = count($result);
				$data = array("c_eselon"=>(string)$result->c_eselon,
					      "n_eselon"=>(string)$result->n_eselon,
					      "n_eselonb"=>(string)$result->n_eselonb);
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubaheselon(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_eselon = $dataMasukan['n_eselon'];
			$c_eselon = $dataMasukan['c_eselon'];
			$n_eselonb = $dataMasukan['n_eselonb'];
						
			$where[] = "c_eselon = '".$c_eselon."'";
			$paramInput = array("c_eselon"	=> $c_eselon,
					"n_eselon"	=> $n_eselon,
					"n_eselonb"	=> $n_eselonb);
					//var_dump($paramInput);
			$db->update('sdm.tr_eselon',$paramInput, $where);
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
	
	public function hapuseselon(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$c_eselon = $dataMasukan['c_eselon'];			
			$where[] = "c_eselon = '".$c_eselon."'";
			
			//var_dump($where);
			$db->delete('sdm.tr_eselon', $where);
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
