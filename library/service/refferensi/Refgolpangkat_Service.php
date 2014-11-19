<?php
class refgolpangkat_Service {
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
		
	public function getGolpangkatList($cari, $currentPage, $numToDisplay)  
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);
				
				$sql = "select c_peg_golongan, c_peg_tipegolongan, c_peg_lvlgolongan, n_peg_golongan,
					n_peg_pangkat, c_pph from sdm.tr_golongan_pangkat where 1=1 $cari";
				
				if(($currentPage == 0) && ($numToDisplay == 0)){
					$data = $db->fetchOne("select count(*) from ($sql) a");
				} else {
					$xLimit=$numToDisplay;
			  		$xOffset=($currentPage-1)*$numToDisplay;	
					
					$result = $db->fetchAll("$sql order by c_peg_golongan limit $xLimit offset $xOffset");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("c_peg_golongan"=>(string)$result[$j]->c_peg_golongan,
								"c_peg_tipegolongan"=>(string)$result[$j]->c_peg_tipegolongan,
								"c_peg_lvlgolongan"=>(string)$result[$j]->c_peg_lvlgolongan,
								"n_peg_golongan"=>(string)$result[$j]->n_peg_golongan,
								"n_peg_pangkat"=>(string)$result[$j]->n_peg_pangkat,
								"c_pph"=>(string)$result[$j]->c_pph
								);}
				}
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambahgolpangkat(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$c_peg_golongan = $dataMasukan['c_peg_golongan'];
			$c_peg_tipegolongan = $dataMasukan['c_peg_tipegolongan'];
			$c_peg_lvlgolongan = $dataMasukan['c_peg_lvlgolongan'];
			$n_peg_golongan = $dataMasukan['n_peg_golongan'];
			$n_peg_pangkat = $dataMasukan['n_peg_pangkat'];
			$c_pph = $dataMasukan['c_pph'];
			$i_entry = $dataMasukan['i_entry'];
			
			$paramInput = array("c_peg_golongan"	=> $c_peg_golongan,
					"c_peg_tipegolongan"	=> $c_peg_tipegolongan,
					"c_peg_lvlgolongan"	=> $c_peg_lvlgolongan,
					"n_peg_golongan"	=> $n_peg_golongan,
					"n_peg_pangkat"	=> $n_peg_pangkat,
					"c_pph"	=> $c_pph,
					"i_entry"	=> $i_entry,
					"d_entry"	=> date('Y-m-d'));
					//var_dump($paramInput);
			$db->insert('sdm.tr_golongan_pangkat',$paramInput);
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
	
	public function detailGolpangkat($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchRow("select c_peg_golongan, c_peg_tipegolongan, c_peg_lvlgolongan, n_peg_golongan,
								n_peg_pangkat, c_pph from sdm.tr_golongan_pangkat 
								where c_peg_golongan  = '".$masukan['c_peg_golongan']."' and c_peg_tipegolongan = '".$masukan['c_peg_tipegolongan']."'");
					$jmlResult = count($result);
					$data = array("c_peg_golongan"=>(string)$result->c_peg_golongan,
							"c_peg_tipegolongan"=>(string)$result->c_peg_tipegolongan,
							"c_peg_lvlgolongan"=>(string)$result->c_peg_lvlgolongan,
							"n_peg_golongan"=>(string)$result->n_peg_golongan,
							"n_peg_pangkat"=>(string)$result->n_peg_pangkat,
							"c_pph"=>(string)$result->c_pph);
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahgolpangkat(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$c_peg_golongan = $dataMasukan['c_peg_golongan'];
			$c_peg_tipegolongan = $dataMasukan['c_peg_tipegolongan'];
			$c_peg_lvlgolongan = $dataMasukan['c_peg_lvlgolongan'];
			$n_peg_golongan = $dataMasukan['n_peg_golongan'];
			$n_peg_pangkat = $dataMasukan['n_peg_pangkat'];
			$c_pph = $dataMasukan['c_pph'];
			$i_entry = $dataMasukan['i_entry'];
						
			$where[] = "c_peg_golongan = '".$c_peg_golongan."' and c_peg_tipegolongan = '".$c_peg_tipegolongan."'";
			$paramInput = array("c_peg_golongan"	=> $c_peg_golongan,
					"c_peg_tipegolongan"	=> $c_peg_tipegolongan,
					"c_peg_lvlgolongan"	=> $c_peg_lvlgolongan,
					"n_peg_golongan"	=> $n_peg_golongan,
					"n_peg_pangkat"	=> $n_peg_pangkat,
					"c_pph"	=> $c_pph,
					"i_entry"	=> $i_entry,
					"d_entry"	=> date('Y-m-d'));
					//var_dump($paramInput);
			$db->update('sdm.tr_golongan_pangkat',$paramInput, $where);
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
	
	public function hapusgolpangkat(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$c_peg_golongan = $dataMasukan['c_peg_golongan'];
			$c_peg_tipegolongan = $dataMasukan['c_peg_tipegolongan'];
			
			$where[] = "c_peg_golongan = '".$c_peg_golongan."' and c_peg_tipegolongan = '".$c_peg_tipegolongan."'";
			
			//var_dump($where);
			$db->delete('sdm.tr_golongan_pangkat', $where);
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
