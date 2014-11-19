<?php
class refjabatankeu_Service {
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

	public function getJabatankeuList($cari, $currentPage, $numToDisplay) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
				$sql = "select id, n_jabatan, d_awal, d_akhir, n_sk, c_statusdelete, d_statusdelete from sdm.tr_jabatankeu where 1=1 $cari";
				
				if(($currentPage == 0) && ($numToDisplay == 0)){
					$data = $db->fetchOne("select count(*) from ($sql) a");
				} else {
					$xLimit=$numToDisplay;
			  		$xOffset=($currentPage-1)*$numToDisplay;	
					
					$result = $db->fetchAll("$sql order by id limit $xLimit offset $xOffset");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("id"=>(string)$result[$j]-> id,
								  "n_jabatan"=>(string)$result[$j]->n_jabatan,								 
								  "d_awal"=>(string)$result[$j]->d_awal,
								  "d_akhir"=>(string)$result[$j]->d_akhir,
								  "n_sk"=>(string)$result[$j]->n_sk,
								  "c_statusdelete"=>(string)$result[$j]->c_statusdelete,
								  "d_statusdelete"=>(string)$result[$j]->d_statusdelete);}
				}
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambahjabatankeu(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$id = $dataMasukan['id'];
			$n_jabatan = $dataMasukan['n_jabatan'];
			$d_awal = date('Y-m-d',strtotime($dataMasukan['d_awal']))." ".gmdate('H:i:s', time()+60*60*7);
			$d_akhir = date('Y-m-d',strtotime($dataMasukan['d_akhir']))." ".gmdate('H:i:s', time()+60*60*7);
			$n_sk = $dataMasukan['n_sk'];
			$c_statusdelete = $dataMasukan['c_statusdelete'];
			$d_statusdelete = date('Y-m-d',strtotime($dataMasukan['d_statusdelete']))." ".gmdate('H:i:s', time()+60*60*7);
			$i_entry = $dataMasukan['i_entry'];
			
			$paramInput = array("id"	=> $id,
					"n_jabatan"	=> $n_jabatan,
					"d_awal"	=> $d_awal,
					"d_akhir"	=> $d_akhir,
					"n_sk"	=> $n_sk,
					"c_statusdelete"	=> $c_statusdelete,
					"d_statusdelete"	=> $d_statusdelete,
					"i_entry"	=> $i_entry,
					"d_entry"	=> gmdate('Y-m-d H:i:s',time()+60*60*7));
					//var_dump($paramInput);
			$db->insert('sdm.tr_jabatankeu',$paramInput);
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
	
	public function detailJabatankeu($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchRow("select id, n_jabatan, d_awal, d_akhir, n_sk, c_statusdelete, d_statusdelete from sdm.tr_jabatankeu 
							where id  = '".$masukan['id']."'");
							
							/* echo "select c_agama, n_agama from sdm.tr_agama 
							where c_agama  = '".$masukan['c_agama']."'"; */
				$jmlResult = count($result);
				$data = array("id"=>(string)$result-> id,
						"n_jabatan"=>(string)$result->n_jabatan,								 
						"d_awal"=>(string)$result->d_awal,
						"d_akhir"=>(string)$result->d_akhir,
						"n_sk"=>(string)$result->n_sk,
						"c_statusdelete"=>(string)$result->c_statusdelete,
						"d_statusdelete"=>(string)$result->d_statusdelete);
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahjabatankeu(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$id = $dataMasukan['id'];
			$n_jabatan = $dataMasukan['n_jabatan'];
			$d_awal = date('Y-m-d',strtotime($dataMasukan['d_awal']))." ".gmdate('H:i:s', time()+60*60*7);
			$d_akhir = date('Y-m-d',strtotime($dataMasukan['d_akhir']))." ".gmdate('H:i:s', time()+60*60*7);
			$n_sk = $dataMasukan['n_sk'];
			$c_statusdelete = $dataMasukan['c_statusdelete'];
			$d_statusdelete = date('Y-m-d',strtotime($dataMasukan['d_statusdelete']))." ".gmdate('H:i:s', time()+60*60*7);
			$i_entry = $dataMasukan['i_entry'];
						
			$where[] = "id = '".$id."'";
			$paramInput = array("id"	=> $id,
					"n_jabatan"	=> $n_jabatan,
					"d_awal"	=> $d_awal,
					"d_akhir"	=> $d_akhir,
					"n_sk"	=> $n_sk,
					"c_statusdelete"	=> $c_statusdelete,
					"d_statusdelete"	=> $d_statusdelete,
					"i_update"	=> $i_entry,
					"d_update"	=> gmdate('Y-m-d H:i:s',time()+60*60*7));
					//var_dump($paramInput);
			$db->update('sdm.tr_jabatankeu',$paramInput, $where);
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
	
	public function hapusjabatankeu(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$id = $dataMasukan['id'];			
			$where[] = "id = '".$id."'";
			
			//var_dump($where);
			$db->delete('sdm.tr_jabatankeu', $where);
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