<?php
class refjenis_Service {
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

	public function getJenisList($cari, $currentPage, $numToDisplay) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
				$sql = "select i_kode, n_nama, v_sort, c_status, d_update from sdm.tr_jnsnaik_gol where 1=1 $cari";
				
				if(($currentPage == 0) && ($numToDisplay == 0)){
					$data = $db->fetchOne("select count(*) from ($sql) a");
				} else {
					$xLimit=$numToDisplay;
			  		$xOffset=($currentPage-1)*$numToDisplay;	
					
					$result = $db->fetchAll("$sql order by i_kode limit $xLimit offset $xOffset");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("i_kode"=>(string)$result[$j]->i_kode,
								  "n_nama"=>(string)$result[$j]->n_nama,
								  "v_sort"=>(string)$result[$j]->v_sort,
								  "c_status"=>(string)$result[$j]->c_status,
								  "d_update"=>(string)$result[$j]->d_update);}
				}
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambahjenis(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$i_kode = $dataMasukan['i_kode'];
			$n_nama = $dataMasukan['n_nama'];
			$v_sort = $dataMasukan['v_sort'];
			$c_status = $dataMasukan['c_status'];
			$d_update = date('Y-m-d',strtotime($dataMasukan['d_update']))." ".gmdate('H:i:s', time()+60*60*7);
			
			$paramInput = array("i_kode"	=> $i_kode,
					"n_nama"	=> $n_nama,
					"v_sort"	=> $v_sort,
					"c_status"	=> $c_status,
					"d_update"	=> $d_update,
					"d_entry"	=> date('Y-m-d H:i:s'));
					//var_dump($paramInput);
			$db->insert('sdm.tr_jnsnaik_gol',$paramInput);
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
	
	public function detailJenis($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchRow("select i_kode, n_nama, v_sort, c_status, d_update from sdm.tr_jnsnaik_gol where
							i_kode  = '".$masukan['i_kode']."'");
							
							/* echo "select c_agama, n_agama from sdm.tr_agama 
							where c_agama  = '".$masukan['c_agama']."'"; */
				$jmlResult = count($result);
				$data = array("i_kode"=>(string)$result->i_kode,
						"n_nama"=>(string)$result->n_nama,
						"v_sort"=>(string)$result->v_sort,
						"c_status"=>(string)$result->c_status,
						"d_update"=>(string)$result->d_update);
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahjenis(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$i_kode = $dataMasukan['i_kode'];
			$n_nama = $dataMasukan['n_nama'];
			$v_sort = $dataMasukan['v_sort'];
			$c_status = $dataMasukan['c_status'];
			$d_update = date('Y-m-d',strtotime($dataMasukan['d_update']))." ".gmdate('H:i:s', time()+60*60*7);
			
			$where[] = "i_kode = '".$i_kode."'";
			$paramInput = array("i_kode"	=> $i_kode,
					"n_nama"	=> $n_nama,
					"v_sort"	=> $v_sort,
					"c_status"	=> $c_status,
					"d_update"	=> $d_update,
					"d_entry"	=> date('Y-m-d H:i:s'));
					//var_dump($paramInput);
			$db->update('sdm.tr_jnsnaik_gol',$paramInput, $where);
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
	
	public function hapusjenis(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$i_kode = $dataMasukan['i_kode'];			
			$where[] = "i_kode = '".$i_kode."'";
			
			//var_dump($where);
			$db->delete('sdm.tr_jnsnaik_gol', $where);
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
