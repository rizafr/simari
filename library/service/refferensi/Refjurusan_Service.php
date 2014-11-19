<?php
class refjurusan_Service {
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

	public function getJurusanList($cari, $currentPage, $numToDisplay) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
				$sql = "select c_jurusan, n_jurusan, q_strata from sdm.tr_jurusan_pendidikan where 1=1 $cari";
				
				if(($currentPage == 0) && ($numToDisplay == 0)){
					$data = $db->fetchOne("select count(*) from ($sql) a");
				} else {
					$xLimit=$numToDisplay;
			  		$xOffset=($currentPage-1)*$numToDisplay;	
					
					$result = $db->fetchAll("$sql order by c_jurusan limit $xLimit offset $xOffset");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("c_jurusan"=>(string)$result[$j]->c_jurusan,
								  "n_jurusan"=>(string)$result[$j]->n_jurusan,								 
								  "q_strata"=>(string)$result[$j]->q_strata);}
				}
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambahjurusan(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_jurusan = $dataMasukan['n_jurusan'];
			$c_jurusan = $dataMasukan['c_jurusan'];
			$q_strata = $dataMasukan['q_strata'];
			
			$paramInput = array("n_jurusan"	=> $n_jurusan,
					"c_jurusan"	=> $c_jurusan,
					"q_strata"	=> $q_strata);
					//var_dump($paramInput);
			$db->insert('sdm.tr_jurusan_pendidikan',$paramInput);
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
	
	public function detailJurusan($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchRow("select c_jurusan, n_jurusan, q_strata from sdm.tr_jurusan_pendidikan 
							where c_jurusan  = '".$masukan['c_jurusan']."'");
							
							/* echo "select c_agama, n_agama from sdm.tr_agama 
							where c_agama  = '".$masukan['c_agama']."'"; */
				$jmlResult = count($result);
				$data = array("c_jurusan"=>(string)$result->c_jurusan,
					      "n_jurusan"=>(string)$result->n_jurusan,
					      "q_strata"=>(string)$result->q_strata);
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahjurusan(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_jurusan = $dataMasukan['n_jurusan'];
			$c_jurusan = $dataMasukan['c_jurusan'];
			$q_strata = $dataMasukan['q_strata'];
						
			$where[] = "c_jurusan = '".$c_jurusan."'";
			$paramInput = array("c_jurusan"	=> $c_jurusan,
					"n_jurusan"	=> $n_jurusan,
					"q_strata"	=> $q_strata);
					//var_dump($paramInput);
			$db->update('sdm.tr_jurusan_pendidikan',$paramInput, $where);
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
	
	public function hapusjurusan(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$c_jurusan = $dataMasukan['c_jurusan'];			
			$where[] = "c_jurusan = '".$c_jurusan."'";
			
			//var_dump($where);
			$db->delete('sdm.tr_jurusan_pendidikan', $where);
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
