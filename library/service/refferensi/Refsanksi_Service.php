<?php
class refsanksi_Service {
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

	public function getSanksiList($cari, $currentPage, $numToDisplay) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
				$sql = "select c_jns_sanksi, n_jns_sanksi, \"MDISIPLINJNS_SORT\", c_status from sdm.tr_jns_sanksi_new where 1=1 $cari";
				
				if(($currentPage == 0) && ($numToDisplay == 0)){
					$data = $db->fetchOne("select count(*) from ($sql) a");
				} else {
					$xLimit=$numToDisplay;
			  		$xOffset=($currentPage-1)*$numToDisplay;	
					
					$result = $db->fetchAll("$sql order by c_jns_sanksi limit $xLimit offset $xOffset");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("c_jns_sanksi"=>(string)$result[$j]->c_jns_sanksi,
								  "n_jns_sanksi"=>(string)$result[$j]->n_jns_sanksi,
								  "MDISIPLINJNS_SORT"=>(string)$result[$j]->MDISIPLINJNS_SORT,
								  "c_status"=>(string)$result[$j]->c_status);}
				}
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambahsanksi(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$c_jns_sanksi = $dataMasukan['c_jns_sanksi'];
			$n_jns_sanksi = $dataMasukan['n_jns_sanksi'];
			$MDISIPLINJNS_SORT = $dataMasukan['MDISIPLINJNS_SORT'];
			$c_status = $dataMasukan['c_status'];
			
			$paramInput = array("c_jns_sanksi"	=> $c_jns_sanksi,
					"n_jns_sanksi"	=> $n_jns_sanksi,
					"MDISIPLINJNS_SORT"	=> $MDISIPLINJNS_SORT,
					"c_status"	=> $c_status,
					"d_entry"	=> date('Y-m-d H:i:s'));
					//var_dump($paramInput);
			$db->insert('sdm.tr_jns_sanksi_new',$paramInput);
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
	
	public function detailSanksi($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchRow("select c_jns_sanksi, n_jns_sanksi, \"MDISIPLINJNS_SORT\", c_status from sdm.tr_jns_sanksi_new 
							where c_jns_sanksi  = '".$masukan['c_jns_sanksi']."'");
							
							/* echo "select c_agama, n_agama from sdm.tr_agama 
							where c_agama  = '".$masukan['c_agama']."'"; */
				$jmlResult = count($result);
				$data = array("c_jns_sanksi"=>(string)$result->c_jns_sanksi,
						"n_jns_sanksi"=>(string)$result->n_jns_sanksi,
						"MDISIPLINJNS_SORT"=>(string)$result->MDISIPLINJNS_SORT,
						"c_status"=>(string)$result->c_status);
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahsanksi(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$c_jns_sanksi = $dataMasukan['c_jns_sanksi'];
			$n_jns_sanksi = $dataMasukan['n_jns_sanksi'];
			$MDISIPLINJNS_SORT = $dataMasukan['MDISIPLINJNS_SORT'];
			$c_status = $dataMasukan['c_status'];
			
			$where[] = "c_jns_sanksi = '".$c_jns_sanksi."'";
			$paramInput = array("c_jns_sanksi"	=> $c_jns_sanksi,
					"n_jns_sanksi"	=> $n_jns_sanksi,
					"MDISIPLINJNS_SORT"	=> $MDISIPLINJNS_SORT,
					"c_status"	=> $c_status,
					"d_entry"	=> date('Y-m-d H:i:s'));
					//var_dump($paramInput);
			$db->update('sdm.tr_jns_sanksi_new',$paramInput, $where);
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
	
	public function hapussanksi(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$c_jns_sanksi = $dataMasukan['c_jns_sanksi'];			
			$where[] = "c_jns_sanksi = '".$c_jns_sanksi."'";
			
			//var_dump($where);
			$db->delete('sdm.tr_jns_sanksi_new', $where);
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