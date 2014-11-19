<?php
class refpekerjaan_Service {
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

	public function getPekerjaanList($cari, $currentPage, $numToDisplay) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
				$sql = "select c_pekerjaan, n_pekerjaan from sdm.tr_pekerjaan where 1=1 $cari";
				
				if(($currentPage == 0) && ($numToDisplay == 0)){
					$data = $db->fetchOne("select count(*) from ($sql) a");
				} else {
					$xLimit=$numToDisplay;
			  		$xOffset=($currentPage-1)*$numToDisplay;	
					
					$result = $db->fetchAll("$sql order by c_pekerjaan limit $xLimit offset $xOffset");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("c_pekerjaan"=>(string)$result[$j]->c_pekerjaan,
								  "n_pekerjaan"=>(string)$result[$j]->n_pekerjaan);}
				}
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambahpekerjaan(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$c_pekerjaan = $dataMasukan['c_pekerjaan'];
			$n_pekerjaan = $dataMasukan['n_pekerjaan'];
			$i_entry = $dataMasukan['i_entry'];
			
			$paramInput = array("c_pekerjaan"	=> $c_pekerjaan,
					"n_pekerjaan"	=> $n_pekerjaan);
					//var_dump($paramInput);
			$db->insert('sdm.tr_pekerjaan',$paramInput);
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
	
	public function detailPekerjaan($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchRow("select c_pekerjaan, n_pekerjaan from sdm.tr_pekerjaan 
							where c_pekerjaan  = '".$masukan['c_pekerjaan']."'");
							
							/* echo "select c_agama, n_agama from sdm.tr_agama 
							where c_agama  = '".$masukan['c_agama']."'"; */
				$jmlResult = count($result);
				$data = array("c_pekerjaan"=>(string)$result->c_pekerjaan,
					      "n_pekerjaan"=>(string)$result->n_pekerjaan);
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahpekerjaan(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$c_pekerjaan = $dataMasukan['c_pekerjaan'];
			$n_pekerjaan = $dataMasukan['n_pekerjaan'];
			$i_entry = $dataMasukan['i_entry'];
			
			$where[] = "c_pekerjaan = '".$c_pekerjaan."'";
			$paramInput = array("c_pekerjaan"	=> $c_pekerjaan,
					"n_pekerjaan"	=> $n_pekerjaan);
					//var_dump($paramInput);
			$db->update('sdm.tr_pekerjaan',$paramInput, $where);
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
	
	public function hapuspekerjaan(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$c_pekerjaan = $dataMasukan['c_pekerjaan'];			
			$where[] = "c_pekerjaan = '".$c_pekerjaan."'";
			
			//var_dump($where);
			$db->delete('sdm.tr_pekerjaan', $where);
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
