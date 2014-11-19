<?php
class refstatusnikah_Service {
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

	public function getStatusnikahList($cari, $currentPage, $numToDisplay) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
				$sql = "select c_status_nikah, n_status_nikah, filt from sdm.tr_status_nikah where 1=1 $cari";
				
				if(($currentPage == 0) && ($numToDisplay == 0)){
					$data = $db->fetchOne("select count(*) from ($sql) a");
				} else {
					$xLimit=$numToDisplay;
			  		$xOffset=($currentPage-1)*$numToDisplay;	
					
					$result = $db->fetchAll("$sql order by c_status_nikah limit $xLimit offset $xOffset");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("c_status_nikah"=>(string)$result[$j]->c_status_nikah,
								  "n_status_nikah"=>(string)$result[$j]->n_status_nikah,								 
								  "filt"=>(string)$result[$j]->filt);}
				}
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambahstatusnikah(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_status_nikah = $dataMasukan['n_status_nikah'];
			$c_status_nikah = $dataMasukan['c_status_nikah'];
			$filt = $dataMasukan['filt'];
			
			$paramInput = array("n_status_nikah"	=> $n_status_nikah,
					"c_status_nikah"	=> $c_status_nikah,
					"filt"	=> $filt);
					//var_dump($paramInput);
			$db->insert('sdm.tr_status_nikah',$paramInput);
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
	
	public function detailStatusnikah($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchRow("select c_status_nikah, n_status_nikah, filt from sdm.tr_status_nikah 
							where c_status_nikah  = '".$masukan['c_status_nikah']."'");
							
							/* echo "select c_agama, n_agama from sdm.tr_agama 
							where c_agama  = '".$masukan['c_agama']."'"; */
				$jmlResult = count($result);
				$data = array("c_status_nikah"=>(string)$result->c_status_nikah,
					      "n_status_nikah"=>(string)$result->n_status_nikah,
					      "filt"=>(string)$result->filt);
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahstatusnikah(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_status_nikah = $dataMasukan['n_status_nikah'];
			$c_status_nikah = $dataMasukan['c_status_nikah'];
			$filt = $dataMasukan['filt'];
						
			$where[] = "c_status_nikah = '".$c_status_nikah."'";
			$paramInput = array("c_status_nikah"	=> $c_status_nikah,
					"n_status_nikah"	=> $n_status_nikah,
					"filt"	=> $filt);
					//var_dump($paramInput);
			$db->update('sdm.tr_status_nikah',$paramInput, $where);
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
	
	public function hapusstatusnikah(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$c_status_nikah = $dataMasukan['c_status_nikah'];			
			$where[] = "c_status_nikah = '".$c_status_nikah."'";
			
			//var_dump($where);
			$db->delete('sdm.tr_status_nikah', $where);
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
