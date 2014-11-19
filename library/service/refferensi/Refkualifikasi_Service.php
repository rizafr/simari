<?php
class refkualifikasi_Service {
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

	public function getKualifikasiList($cari, $currentPage, $numToDisplay) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
				$sql = "select c_kualifikasi, n_kualifikasi from sdm.tr_diklat_kualifikasi where 1=1 $cari";
				
				if(($currentPage == 0) && ($numToDisplay == 0)){
					$data = $db->fetchOne("select count(*) from ($sql) a");
				} else {
					$xLimit=$numToDisplay;
			  		$xOffset=($currentPage-1)*$numToDisplay;	
					
					$result = $db->fetchAll("$sql order by c_kualifikasi limit $xLimit offset $xOffset");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("c_kualifikasi"=>(string)$result[$j]->c_kualifikasi,
								  "n_kualifikasi"=>(string)$result[$j]->n_kualifikasi);}
				}
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambahkualifikasi(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_kualifikasi = $dataMasukan['n_kualifikasi'];
			$c_kualifikasi = $dataMasukan['c_kualifikasi'];
			$i_entry = $dataMasukan['i_entry'];
			
			$paramInput = array("n_kualifikasi"	=> $n_kualifikasi,
					"c_kualifikasi"	=> $c_kualifikasi);
					//var_dump($paramInput);
			$db->insert('sdm.tr_diklat_kualifikasi',$paramInput);
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
	
	public function detailKualifikasi($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchRow("select c_kualifikasi, n_kualifikasi from sdm.tr_diklat_kualifikasi 
							where c_kualifikasi  = '".$masukan['c_kualifikasi']."'");
							
							/* echo "select c_agama, n_agama from sdm.tr_agama 
							where c_agama  = '".$masukan['c_agama']."'"; */
				$jmlResult = count($result);
				$data = array("c_kualifikasi"=>(string)$result->c_kualifikasi,
					      "n_kualifikasi"=>(string)$result->n_kualifikasi);
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahkualifikasi(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_kualifikasi = $dataMasukan['n_kualifikasi'];
			$c_kualifikasi = $dataMasukan['c_kualifikasi'];
						
			$where[] = "c_kualifikasi = '".$c_kualifikasi."'";
			$paramInput = array("c_kualifikasi"	=> $c_kualifikasi,
					"n_kualifikasi"	=> $n_kualifikasi);
					//var_dump($paramInput);
			$db->update('sdm.tr_diklat_kualifikasi',$paramInput, $where);
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
	
	public function hapuskualifikasi(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$c_kualifikasi = $dataMasukan['c_kualifikasi'];			
			$where[] = "c_kualifikasi = '".$c_kualifikasi."'";
			
			//var_dump($where);
			$db->delete('sdm.tr_diklat_kualifikasi', $where);
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
