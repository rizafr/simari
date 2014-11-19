<?php
class refjenjangfungsi_Service {
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

	public function getJenjangfungsiList($cari, $currentPage, $numToDisplay) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
				$sql = "select c_jenjang_fungsional, n_jenjang_fungsional, c_fungsional from sdm.tr_penjenganan_fungsional where 1=1 $cari";
				
				if(($currentPage == 0) && ($numToDisplay == 0)){
					$data = $db->fetchOne("select count(*) from ($sql) a");
				} else {
					$xLimit=$numToDisplay;
			  		$xOffset=($currentPage-1)*$numToDisplay;	
					
					$result = $db->fetchAll("$sql order by c_jenjang_fungsional limit $xLimit offset $xOffset");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("c_jenjang_fungsional"=>(string)$result[$j]->c_jenjang_fungsional,
								  "n_jenjang_fungsional"=>(string)$result[$j]->n_jenjang_fungsional,								 
								  "c_fungsional"=>(string)$result[$j]->c_fungsional);}
				}
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambahjenjangfungsi(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_jenjang_fungsional = $dataMasukan['n_jenjang_fungsional'];
			$c_jenjang_fungsional = $dataMasukan['c_jenjang_fungsional'];
			$c_fungsional = $dataMasukan['c_fungsional'];
			
			$paramInput = array("n_jenjang_fungsional"	=> $n_jenjang_fungsional,
					"c_jenjang_fungsional"	=> $c_jenjang_fungsional,
					"c_fungsional"	=> $c_fungsional);
					//var_dump($paramInput);
			$db->insert('sdm.tr_penjenganan_fungsional',$paramInput);
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
	
	public function detailJenjangfungsi($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchRow("select c_jenjang_fungsional, n_jenjang_fungsional, c_fungsional from sdm.tr_penjenganan_fungsional 
							where c_jenjang_fungsional  = '".$masukan['c_jenjang_fungsional']."'");
							
							/* echo "select c_agama, n_agama from sdm.tr_agama 
							where c_agama  = '".$masukan['c_agama']."'"; */
				$jmlResult = count($result);
				$data = array("c_jenjang_fungsional"=>(string)$result->c_jenjang_fungsional,
					      "n_jenjang_fungsional"=>(string)$result->n_jenjang_fungsional,
					      "c_fungsional"=>(string)$result->c_fungsional);
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahjenjangfungsi(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_jenjang_fungsional = $dataMasukan['n_jenjang_fungsional'];
			$c_jenjang_fungsional = $dataMasukan['c_jenjang_fungsional'];
			$c_fungsional = $dataMasukan['c_fungsional'];
						
			$where[] = "c_jenjang_fungsional = '".$c_jenjang_fungsional."'";
			$paramInput = array("c_jenjang_fungsional"	=> $c_jenjang_fungsional,
					"n_jenjang_fungsional"	=> $n_jenjang_fungsional,
					"c_fungsional"	=> $c_fungsional);
					//var_dump($paramInput);
			$db->update('sdm.tr_penjenganan_fungsional',$paramInput, $where);
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
	
	public function hapusjenjangfungsi(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$c_jenjang_fungsional = $dataMasukan['c_jenjang_fungsional'];			
			$where[] = "c_jenjang_fungsional = '".$c_jenjang_fungsional."'";
			
			//var_dump($where);
			$db->delete('sdm.tr_penjenganan_fungsional', $where);
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
