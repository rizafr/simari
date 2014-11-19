<?php
class refpengadilan_Service {
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

	public function getPengadilanList($cari, $currentPage, $numToDisplay) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
				$sql = "select c_pengadilan, n_pengadilan, c_wilayah, n_wilayah from sdm.tr_wil_pengadilan where 1=1 $cari";
				
				if(($currentPage == 0) && ($numToDisplay == 0)){
					$data = $db->fetchOne("select count(*) from ($sql) a");
				} else {
					$xLimit=$numToDisplay;
			  		$xOffset=($currentPage-1)*$numToDisplay;	
					
					$result = $db->fetchAll("$sql order by c_pengadilan limit $xLimit offset $xOffset");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("c_pengadilan"=>(string)$result[$j]->c_pengadilan,
								  "n_pengadilan"=>(string)$result[$j]->n_pengadilan,								 
								  "c_wilayah"=>(string)$result[$j]->c_wilayah,
								  "n_wilayah"=>(string)$result[$j]->n_wilayah);}
				}
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambahpengadilan(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$c_pengadilan = $dataMasukan['c_pengadilan'];
			$n_pengadilan = $dataMasukan['n_pengadilan'];
			$c_wilayah = $dataMasukan['c_wilayah'];
			$n_wilayah = $dataMasukan['n_wilayah'];
			
			$paramInput = array("c_pengadilan"	=> $c_pengadilan,
					"n_pengadilan"	=> $n_pengadilan,
					"c_wilayah"	=> $c_wilayah,
					"n_wilayah"	=> $n_wilayah);
					//var_dump($paramInput);
			$db->insert('sdm.tr_wil_pengadilan',$paramInput);
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
	
	public function detailPengadilan($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchRow("select c_pengadilan, n_pengadilan, c_wilayah, n_wilayah from sdm.tr_wil_pengadilan 
							where c_pengadilan  = '".$masukan['c_pengadilan']."'");
							
							/* echo "select c_agama, n_agama from sdm.tr_agama 
							where c_agama  = '".$masukan['c_agama']."'"; */
				$jmlResult = count($result);
				$data = array("c_pengadilan"=>(string)$result->c_pengadilan,
					      "n_pengadilan"=>(string)$result->n_pengadilan,
					      "c_wilayah"=>(string)$result->c_wilayah,
					      "n_wilayah"=>(string)$result->n_wilayah);
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahpengadilan(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$c_pengadilan = $dataMasukan['c_pengadilan'];
			$n_pengadilan = $dataMasukan['n_pengadilan'];
			$c_wilayah = $dataMasukan['c_wilayah'];
			$n_wilayah = $dataMasukan['n_wilayah'];
						
			$where[] = "c_pengadilan = '".$c_pengadilan."'";
			$paramInput = array("c_pengadilan"	=> $c_pengadilan,
					"n_pengadilan"	=> $n_pengadilan,
					"c_wilayah"	=> $c_wilayah,
					"n_wilayah"	=> $n_wilayah);
					//var_dump($paramInput);
			$db->update('sdm.tr_wil_pengadilan',$paramInput, $where);
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
	
	public function hapuspengadilan(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$c_pengadilan = $dataMasukan['c_pengadilan'];			
			$where[] = "c_pengadilan = '".$c_pengadilan."'";
			
			//var_dump($where);
			$db->delete('sdm.tr_wil_pengadilan', $where);
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
