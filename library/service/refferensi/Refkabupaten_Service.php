<?php
class refkabupaten_Service {
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

	public function getKabupatenList($cari, $currentPage, $numToDisplay) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
				$sql = "select c_kabupaten, n_kabupaten, c_propinsi from sdm.tr_kabupaten where 1=1 $cari";
				
				if(($currentPage == 0) && ($numToDisplay == 0)){
					$data = $db->fetchOne("select count(*) from ($sql) a");
				} else {
					$xLimit=$numToDisplay;
			  		$xOffset=($currentPage-1)*$numToDisplay;	
					
					$result = $db->fetchAll("$sql order by c_kabupaten limit $xLimit offset $xOffset");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("c_kabupaten"=>(string)$result[$j]->c_kabupaten,
								  "n_kabupaten"=>(string)$result[$j]->n_kabupaten,
								  "c_propinsi"=>(string)$result[$j]->c_propinsi);}
				}
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambahkabupaten(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_kabupaten = $dataMasukan['n_kabupaten'];
			$c_kabupaten = $dataMasukan['c_kabupaten'];
			$c_propinsi = $dataMasukan['c_propinsi'];
			$i_entry = $dataMasukan['i_entry'];
			
			$paramInput = array("n_kabupaten"	=> $n_kabupaten,
					"c_kabupaten"	=> $c_kabupaten,
					"c_propinsi"	=> $c_propinsi,
					"i_entry"	=> $i_entry,
					"d_entry"	=> date('Y-m-d'));
					//var_dump($paramInput);
			$db->insert('sdm.tr_kabupaten',$paramInput);
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
	
	public function detailKabupaten($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchRow("select c_kabupaten, n_kabupaten, c_propinsi from sdm.tr_kabupaten 
							where c_kabupaten  = '".$masukan['c_kabupaten']."'");
							
							/* echo "select c_agama, n_agama from sdm.tr_agama 
							where c_agama  = '".$masukan['c_agama']."'"; */
				$jmlResult = count($result);
				$data = array("c_kabupaten"=>(string)$result->c_kabupaten,
					      "n_kabupaten"=>(string)$result->n_kabupaten,
					      "c_propinsi"=>(string)$result->c_propinsi);
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahkabupaten(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_kabupaten = $dataMasukan['n_kabupaten'];
			$c_kabupaten = $dataMasukan['c_kabupaten'];
			$c_propinsi = $dataMasukan['c_propinsi'];
			$i_entry = $dataMasukan['i_entry'];
			
			$where[] = "c_kabupaten = '".$c_kabupaten."'";
			$paramInput = array("c_kabupaten"	=> $c_kabupaten,
					"n_kabupaten"	=> $n_kabupaten,
					"c_propinsi"	=> $c_propinsi,
					"i_entry"	=> $i_entry,
					"d_entry"	=> date('Y-m-d'));
					//var_dump($paramInput);
			$db->update('sdm.tr_kabupaten',$paramInput, $where);
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
	
	public function hapuskabupaten(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$c_kabupaten = $dataMasukan['c_kabupaten'];			
			$where[] = "c_kabupaten = '".$c_kabupaten."'";
			
			//var_dump($where);
			$db->delete('sdm.tr_kabupaten', $where);
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
