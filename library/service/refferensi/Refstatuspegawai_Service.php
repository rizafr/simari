<?php
class refstatuspegawai_Service {
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

	public function getStatuspegawaiList($cari, $currentPage, $numToDisplay) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
				$sql = "select c_peg_status, n_peg_status from sdm.tr_status_pegawai where 1=1 $cari";
				
				if(($currentPage == 0) && ($numToDisplay == 0)){
					$data = $db->fetchOne("select count(*) from ($sql) a");
				} else {
					$xLimit=$numToDisplay;
			  		$xOffset=($currentPage-1)*$numToDisplay;	
					
					$result = $db->fetchAll("$sql order by c_peg_status limit $xLimit offset $xOffset");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("c_peg_status"=>(string)$result[$j]->c_peg_status,
								  "n_peg_status"=>(string)$result[$j]->n_peg_status);}
				}
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambahstatuspegawai(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_peg_status = $dataMasukan['n_peg_status'];
			$c_peg_status = $dataMasukan['c_peg_status'];
			$i_entry = $dataMasukan['i_entry'];
			
			$paramInput = array("n_peg_status"	=> $n_peg_status,
					"c_peg_status"	=> $c_peg_status,
					"i_entry"	=> $i_entry,
					"d_entry"	=> date('Y-m-d'));
					//var_dump($paramInput);
			$db->insert('sdm.tr_status_pegawai',$paramInput);
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
	
	public function detailStatuspegawai($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchRow("select c_peg_status, n_peg_status from sdm.tr_status_pegawai 
							where c_peg_status  = '".$masukan['c_peg_status']."'");
							
							/* echo "select c_peg_status, n_peg_status from sdm.tr_statuspegawai 
							where c_peg_status  = '".$masukan['c_peg_status']."'"; */
				$jmlResult = count($result);
				$data = array("c_peg_status"=>(string)$result->c_peg_status,
					      "n_peg_status"=>(string)$result->n_peg_status);
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahstatuspegawai(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$n_peg_status = $dataMasukan['n_peg_status'];
			$c_peg_status = $dataMasukan['c_peg_status'];
			$i_entry = $dataMasukan['i_entry'];
			
			$where[] = "c_peg_status = '".$c_peg_status."'";
			$paramInput = array("c_peg_status"	=> $c_peg_status,
					"n_peg_status"	=> $n_peg_status,
					"i_entry"	=> $i_entry,
					"d_entry"	=> date('Y-m-d'));
					//var_dump($paramInput);
			$db->update('sdm.tr_status_pegawai',$paramInput, $where);
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
	
	public function hapusstatuspegawai(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$c_peg_status = $dataMasukan['c_peg_status'];			
			$where[] = "c_peg_status = '".$c_peg_status."'";
			
			//var_dump($where);
			$db->delete('sdm.tr_status_pegawai', $where);
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
