<?php
class refgoldarah_Service {
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

	public function getGoldarahList($cari, $currentPage, $numToDisplay) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
				$sql = "select c_golongan_darah, n_golongan_darah from sdm.tr_gol_darah where 1=1 $cari";
				
				if(($currentPage == 0) && ($numToDisplay == 0)){
					$data = $db->fetchOne("select count(*) from ($sql) a");
				} else {
					$xLimit=$numToDisplay;
			  		$xOffset=($currentPage-1)*$numToDisplay;	
					
					$result = $db->fetchAll("$sql order by c_golongan_darah limit $xLimit offset $xOffset");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("c_golongan_darah"=>(string)$result[$j]->c_golongan_darah,
								  "n_golongan_darah"=>(string)$result[$j]->n_golongan_darah);}
				}
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambahgoldarah(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$c_golongan_darah = $dataMasukan['c_golongan_darah'];
			$n_golongan_darah = $dataMasukan['n_golongan_darah'];
						
			$paramInput = array("c_golongan_darah"	=> $c_golongan_darah,
					"n_golongan_darah"	=> $n_golongan_darah);
					//var_dump($paramInput);
			$db->insert('sdm.tr_gol_darah',$paramInput);
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
	
	public function detailGoldarah($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchRow("select c_golongan_darah, n_golongan_darah from sdm.tr_gol_darah 
							where c_golongan_darah  = '".$masukan['c_golongan_darah']."'");
							
							/* echo "select c_agama, n_agama from sdm.tr_agama 
							where c_agama  = '".$masukan['c_agama']."'"; */
				$jmlResult = count($result);
				$data = array("c_golongan_darah"=>(string)$result->c_golongan_darah,
					      "n_golongan_darah"=>(string)$result->n_golongan_darah);
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahgoldarah(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$c_golongan_darah = $dataMasukan['c_golongan_darah'];
			$n_golongan_darah = $dataMasukan['n_golongan_darah'];
						
			$where[] = "c_golongan_darah = '".$c_golongan_darah."'";
			$paramInput = array("c_golongan_darah"	=> $c_golongan_darah,
					"n_golongan_darah"	=> $n_golongan_darah);
					//var_dump($paramInput);
			$db->update('sdm.tr_gol_darah',$paramInput, $where);
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
	
	public function hapusgoldarah(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$c_golongan_darah = $dataMasukan['c_golongan_darah'];			
			$where[] = "c_golongan_darah = '".$c_golongan_darah."'";
			
			//var_dump($where);
			$db->delete('sdm.tr_gol_darah', $where);
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
