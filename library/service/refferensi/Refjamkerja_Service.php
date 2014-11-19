<?php
class refjamkerja_Service {
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

	public function getJamkerjaList($cari, $currentPage, $numToDisplay) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
				$sql = "select c_hari, d_jamkerja_mulai, d_jamkerja_selesai, d_jamistrht_mulai, d_jamistrht_selesai from sdm.tr_jamharikerja where 1=1 $cari";
				
				if(($currentPage == 0) && ($numToDisplay == 0)){
					$data = $db->fetchOne("select count(*) from ($sql) a");
				} else {
					$xLimit=$numToDisplay;
			  		$xOffset=($currentPage-1)*$numToDisplay;	
					
					$result = $db->fetchAll("$sql order by c_hari limit $xLimit offset $xOffset");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("c_hari"=>(string)$result[$j]->c_hari,
								  "d_jamkerja_mulai"=>(string)$result[$j]->d_jamkerja_mulai,
								  "d_jamkerja_selesai"=>(string)$result[$j]->d_jamkerja_selesai,
								  "d_jamistrht_mulai"=>(string)$result[$j]->d_jamistrht_mulai,
								  "d_jamistrht_selesai"=>(string)$result[$j]->d_jamistrht_selesai);}
				}
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambahjamkerja(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$c_hari = $dataMasukan['c_hari'];
			$d_jamkerja_mulai = $dataMasukan['d_jamkerja_mulai'];
			$d_jamkerja_selesai = $dataMasukan['d_jamkerja_selesai'];
			$d_jamistrht_mulai = $dataMasukan['d_jamistrht_mulai'];
			$d_jamistrht_selesai = $dataMasukan['d_jamistrht_selesai'];
			$i_entry = $dataMasukan['i_entry'];
			
			$paramInput = array("c_hari"	=> $c_hari,
					"d_jamkerja_mulai"	=> $d_jamkerja_mulai,
					"d_jamkerja_selesai"	=> $d_jamkerja_selesai,
					"d_jamistrht_mulai"	=> $d_jamistrht_mulai,
					"d_jamistrht_selesai"	=> $d_jamistrht_selesai,
					"i_entry"	=> $i_entry,
					"d_entry"	=> date('Y-m-d'));
					//var_dump($paramInput);
			$db->insert('sdm.tr_jamharikerja',$paramInput);
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
	
	public function detailJamkerja($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchRow("select c_hari, d_jamkerja_mulai, d_jamkerja_selesai, d_jamistrht_mulai, d_jamistrht_selesai 
							from sdm.tr_jamharikerja where c_hari  = '".$masukan['c_hari']."'");
							
							/* echo "select c_agama, n_agama from sdm.tr_agama 
							where c_agama  = '".$masukan['c_agama']."'"; */
				$jmlResult = count($result);
				$data = array("c_hari"=>(string)$result->c_hari,
						"d_jamkerja_mulai"=>(string)$result->d_jamkerja_mulai,
						"d_jamkerja_selesai"=>(string)$result->d_jamkerja_selesai,
						"d_jamistrht_mulai"=>(string)$result->d_jamistrht_mulai,
						"d_jamistrht_selesai"=>(string)$result->d_jamistrht_selesai);
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahjamkerja(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$c_hari = $dataMasukan['c_hari'];
			$d_jamkerja_mulai = $dataMasukan['d_jamkerja_mulai'];
			$d_jamkerja_selesai = $dataMasukan['d_jamkerja_selesai'];
			$d_jamistrht_mulai = $dataMasukan['d_jamistrht_mulai'];
			$d_jamistrht_selesai = $dataMasukan['d_jamistrht_selesai'];
			$i_entry = $dataMasukan['i_entry'];
			
			$where[] = "c_hari = '".$c_hari."'";
			$paramInput = array("c_hari"	=> $c_hari,
					"d_jamkerja_mulai"	=> $d_jamkerja_mulai,
					"d_jamkerja_selesai"	=> $d_jamkerja_selesai,
					"d_jamistrht_mulai"	=> $d_jamistrht_mulai,
					"d_jamistrht_selesai"	=> $d_jamistrht_selesai,
					"i_entry"	=> $i_entry,
					"d_entry"	=> date('Y-m-d'));
					//var_dump($paramInput);
			$db->update('sdm.tr_jamharikerja',$paramInput, $where);
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
	
	public function hapusjamkerja(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$c_hari = $dataMasukan['c_hari'];			
			$where[] = "c_hari = '".$c_hari."'";
			
			//var_dump($where);
			$db->delete('sdm.tr_jamharikerja', $where);
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
