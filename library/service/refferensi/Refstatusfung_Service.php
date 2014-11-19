<?php
class refstatusfung_Service {
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

	public function getStatusfungList($cari, $currentPage, $numToDisplay) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
				$sql = "select c_statusfung, n_statusfung, c_kelompok from sdm.tr_statfungsional where 1=1 $cari";
				
				if(($currentPage == 0) && ($numToDisplay == 0)){
					$data = $db->fetchOne("select count(*) from ($sql) a");
				} else {
					$xLimit=$numToDisplay;
			  		$xOffset=($currentPage-1)*$numToDisplay;	
					
					$result = $db->fetchAll("$sql order by c_statusfung limit $xLimit offset $xOffset");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("c_statusfung"=>(string)$result[$j]->c_statusfung,
								  "n_statusfung"=>(string)$result[$j]->n_statusfung,								 
								  "c_kelompok"=>(string)$result[$j]->c_kelompok);}
				}
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambahstatusfung(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_statusfung = $dataMasukan['n_statusfung'];
			$c_statusfung = $dataMasukan['c_statusfung'];
			$c_kelompok = $dataMasukan['c_kelompok'];
			
			$paramInput = array("n_statusfung"	=> $n_statusfung,
					"c_statusfung"	=> $c_statusfung,
					"c_kelompok"	=> $c_kelompok);
					//var_dump($paramInput);
			$db->insert('sdm.tr_statfungsional',$paramInput);
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
	
	public function detailStatusfung($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchRow("select c_statusfung, n_statusfung, c_kelompok from sdm.tr_statfungsional 
							where c_statusfung  = '".$masukan['c_statusfung']."'");
							
							/* echo "select c_agama, n_agama from sdm.tr_agama 
							where c_agama  = '".$masukan['c_agama']."'"; */
				$jmlResult = count($result);
				$data = array("c_statusfung"=>(string)$result->c_statusfung,
					      "n_statusfung"=>(string)$result->n_statusfung,
					      "c_kelompok"=>(string)$result->c_kelompok);
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahstatusfung(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_statusfung = $dataMasukan['n_statusfung'];
			$c_statusfung = $dataMasukan['c_statusfung'];
			$c_kelompok = $dataMasukan['c_kelompok'];
						
			$where[] = "c_statusfung = '".$c_statusfung."'";
			$paramInput = array("c_statusfung"	=> $c_statusfung,
					"n_statusfung"	=> $n_statusfung,
					"c_kelompok"	=> $c_kelompok);
					//var_dump($paramInput);
			$db->update('sdm.tr_statfungsional',$paramInput, $where);
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
	
	public function hapusstatusfung(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$c_statusfung = $dataMasukan['c_statusfung'];			
			$where[] = "c_statusfung = '".$c_statusfung."'";
			
			//var_dump($where);
			$db->delete('sdm.tr_statfungsional', $where);
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
