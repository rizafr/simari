<?php
class refpangkat_Service {
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

	public function getPangkatList($cari, $currentPage, $numToDisplay) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
				$sql = "select c_jns_kepangkatan, n_jns_kepangkatan from sdm.tr_jns_kepangkatan where 1=1 $cari";
				
				if(($currentPage == 0) && ($numToDisplay == 0)){
					$data = $db->fetchOne("select count(*) from ($sql) a");
				} else {
					$xLimit=$numToDisplay;
			  		$xOffset=($currentPage-1)*$numToDisplay;	
					
					$result = $db->fetchAll("$sql order by c_jns_kepangkatan limit $xLimit offset $xOffset");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("c_jns_kepangkatan"=>(string)$result[$j]->c_jns_kepangkatan,
								  "n_jns_kepangkatan"=>(string)$result[$j]->n_jns_kepangkatan);}
				}
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambahpangkat(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$c_jns_kepangkatan = $dataMasukan['c_jns_kepangkatan'];
			$n_jns_kepangkatan = $dataMasukan['n_jns_kepangkatan'];
						
			$paramInput = array("c_jns_kepangkatan"	=> $c_jns_kepangkatan,
					"n_jns_kepangkatan"	=> $n_jns_kepangkatan);
					//var_dump($paramInput);
			$db->insert('sdm.tr_jns_kepangkatan',$paramInput);
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
	
	public function detailPangkat($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchRow("select c_jns_kepangkatan, n_jns_kepangkatan from sdm.tr_jns_kepangkatan 
							where c_jns_kepangkatan  = '".$masukan['c_jns_kepangkatan']."'");
							
							/* echo "select c_agama, n_agama from sdm.tr_agama 
							where c_agama  = '".$masukan['c_agama']."'"; */
				$jmlResult = count($result);
				$data = array("c_jns_kepangkatan"=>(string)$result->c_jns_kepangkatan,
					      "n_jns_kepangkatan"=>(string)$result->n_jns_kepangkatan);
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahpangkat(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_jns_kepangkatan = $dataMasukan['n_jns_kepangkatan'];
			$c_jns_kepangkatan = $dataMasukan['c_jns_kepangkatan'];
						
			$where[] = "c_jns_kepangkatan = '".$c_jns_kepangkatan."'";
			$paramInput = array("c_jns_kepangkatan"	=> $c_jns_kepangkatan,
					"n_jns_kepangkatan"	=> $n_jns_kepangkatan);
					//var_dump($paramInput);
			$db->update('sdm.tr_jns_kepangkatan',$paramInput, $where);
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
	
	public function hapuspangkat(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$c_jns_kepangkatan = $dataMasukan['c_jns_kepangkatan'];			
			$where[] = "c_jns_kepangkatan = '".$c_jns_kepangkatan."'";
			
			//var_dump($where);
			$db->delete('sdm.tr_jns_kepangkatan', $where);
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
