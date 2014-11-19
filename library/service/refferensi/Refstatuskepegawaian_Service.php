<?php
class refstatuskepegawaian_Service {
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

	public function getStatuskepegawaianList($cari, $currentPage, $numToDisplay) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
				$sql = "select c_status_kepegawaian, n_status_kepegawaian, c_filter from sdm.tr_status_kepegawaian where 1=1 $cari";
				
				if(($currentPage == 0) && ($numToDisplay == 0)){
					$data = $db->fetchOne("select count(*) from ($sql) a");
				} else {
					$xLimit=$numToDisplay;
			  		$xOffset=($currentPage-1)*$numToDisplay;	
					
					$result = $db->fetchAll("$sql order by c_status_kepegawaian limit $xLimit offset $xOffset");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("c_status_kepegawaian"=>(string)$result[$j]->c_status_kepegawaian,
								  "n_status_kepegawaian"=>(string)$result[$j]->n_status_kepegawaian,								 
								  "c_filter"=>(string)$result[$j]->c_filter);}
				}
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambahstatuskepegawaian(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_status_kepegawaian = $dataMasukan['n_status_kepegawaian'];
			$c_status_kepegawaian = $dataMasukan['c_status_kepegawaian'];
			$c_filter = $dataMasukan['c_filter'];
			
			$paramInput = array("n_status_kepegawaian"	=> $n_status_kepegawaian,
					"c_status_kepegawaian"	=> $c_status_kepegawaian,
					"c_filter"	=> $c_filter);
					//var_dump($paramInput);
			$db->insert('sdm.tr_status_kepegawaian',$paramInput);
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
	
	public function detailStatuskepegawaian($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchRow("select c_status_kepegawaian, n_status_kepegawaian, c_filter from sdm.tr_status_kepegawaian 
							where c_status_kepegawaian  = '".$masukan['c_status_kepegawaian']."'");
							
							/* echo "select c_agama, n_agama from sdm.tr_agama 
							where c_agama  = '".$masukan['c_agama']."'"; */
				$jmlResult = count($result);
				$data = array("c_status_kepegawaian"=>(string)$result->c_status_kepegawaian,
					      "n_status_kepegawaian"=>(string)$result->n_status_kepegawaian,
					      "c_filter"=>(string)$result->c_filter);
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahstatuskepegawaian(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_status_kepegawaian = $dataMasukan['n_status_kepegawaian'];
			$c_status_kepegawaian = $dataMasukan['c_status_kepegawaian'];
			$c_filter = $dataMasukan['c_filter'];
						
			$where[] = "c_status_kepegawaian = '".$c_status_kepegawaian."'";
			$paramInput = array("c_status_kepegawaian"	=> $c_status_kepegawaian,
					"n_status_kepegawaian"	=> $n_status_kepegawaian,
					"c_filter"	=> $c_filter);
					//var_dump($paramInput);
			$db->update('sdm.tr_status_kepegawaian',$paramInput, $where);
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
	
	public function hapusstatuskepegawaian(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$c_status_kepegawaian = $dataMasukan['c_status_kepegawaian'];			
			$where[] = "c_status_kepegawaian = '".$c_status_kepegawaian."'";
			
			//var_dump($where);
			$db->delete('sdm.tr_status_kepegawaian', $where);
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
