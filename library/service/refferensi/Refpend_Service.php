<?php
class refpend_Service {
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

	public function getPendList($cari, $currentPage, $numToDisplay) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
				$sql = "select c_pend, n_pend, c_pend_jenis from sdm.tr_pendidikan where 1=1 $cari";
				
				if(($currentPage == 0) && ($numToDisplay == 0)){
					$data = $db->fetchOne("select count(*) from ($sql) a");
				} else {
					$xLimit=$numToDisplay;
			  		$xOffset=($currentPage-1)*$numToDisplay;	
					
					$result = $db->fetchAll("$sql order by c_pend limit $xLimit offset $xOffset");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("c_pend"=>(string)$result[$j]->c_pend,
								  "n_pend"=>(string)$result[$j]->n_pend,								 
								  "c_pend_jenis"=>(string)$result[$j]->c_pend_jenis);}
				}
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambahpend(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_pend = $dataMasukan['n_pend'];
			$c_pend = $dataMasukan['c_pend'];
			$c_pend_jenis = $dataMasukan['c_pend_jenis'];
			$i_entry = $dataMasukan['i_entry'];
			
			$paramInput = array("n_pend"	=> $n_pend,
					"c_pend"	=> $c_pend,
					"c_pend_jenis"	=> $c_pend_jenis,
					"i_entry"	=> $i_entry,
					"d_entry"	=> date('Y-m-d'));
					//var_dump($paramInput);
			$db->insert('sdm.tr_pendidikan',$paramInput);
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
	
	public function detailPend($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchRow("select c_pend, n_pend, c_pend_jenis from sdm.tr_pendidikan 
							where c_pend  = '".$masukan['c_pend']."'");
							
							/* echo "select c_agama, n_agama from sdm.tr_agama 
							where c_agama  = '".$masukan['c_agama']."'"; */
				$jmlResult = count($result);
				$data = array("c_pend"=>(string)$result->c_pend,
					      "n_pend"=>(string)$result->n_pend,
					      "c_pend_jenis"=>(string)$result->c_pend_jenis);
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahpend(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_pend = $dataMasukan['n_pend'];
			$c_pend = $dataMasukan['c_pend'];
			$c_pend_jenis = $dataMasukan['c_pend_jenis'];
			$i_entry = $dataMasukan['i_entry'];
						
			$where[] = "c_pend = '".$c_pend."'";
			$paramInput = array("c_pend"	=> $c_pend,
					"n_pend"	=> $n_pend,
					"c_pend_jenis"	=> $c_pend_jenis,
					"i_entry"	=> $i_entry,
					"d_entry"	=> date('Y-m-d'));
					//var_dump($paramInput);
			$db->update('sdm.tr_pendidikan',$paramInput, $where);
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
	
	public function hapuspend(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$c_pend = $dataMasukan['c_pend'];			
			$where[] = "c_pend = '".$c_pend."'";
			
			//var_dump($where);
			$db->delete('sdm.tr_pendidikan', $where);
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
