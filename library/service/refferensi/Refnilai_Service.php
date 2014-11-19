<?php
class refnilai_Service {
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
		
	public function getNilaiList($cari, $currentPage, $numToDisplay) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
					
					$sql = "select c_nilai_kinerja, n_faktor_kinerja, n_standar_kinerja, d_nilai_kinerja,
					q_nilai_dibawah, q_nilai_perbaikan, q_nilai_sesuai, q_nilai_diatas from sdm.tr_formpenilaian_mil where 1=1 $cari";
					
					if(($currentPage == 0) && ($numToDisplay == 0)){
						$data = $db->fetchOne("select count(*) from ($sql) a");
					} else {
						$xLimit=$numToDisplay;
						$xOffset=($currentPage-1)*$numToDisplay;	
					
						$result = $db->fetchAll("$sql order by c_nilai_kinerja limit $xLimit offset $xOffset");
						$jmlResult = count($result);
						for ($j = 0; $j < $jmlResult; $j++) 
						{
						$data[$j] = array("c_nilai_kinerja"=>(string)$result[$j]->c_nilai_kinerja,
								"n_faktor_kinerja"=>(string)$result[$j]->n_faktor_kinerja,
								"n_standar_kinerja"=>(string)$result[$j]->n_standar_kinerja,
								"d_nilai_kinerja"=>(string)$result[$j]->d_nilai_kinerja,
								"q_nilai_dibawah"=>(string)$result[$j]->q_nilai_dibawah,
								"q_nilai_perbaikan"=>(string)$result[$j]->q_nilai_perbaikan,
								"q_nilai_sesuai"=>(string)$result[$j]->q_nilai_sesuai,
								"q_nilai_diatas"=>(string)$result[$j]->q_nilai_diatas
								);}
					}
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambahnilai(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$c_nilai_kinerja = $dataMasukan['c_nilai_kinerja'];
			$n_faktor_kinerja = $dataMasukan['n_faktor_kinerja'];
			$n_standar_kinerja = $dataMasukan['n_standar_kinerja'];
			$d_nilai_kinerja = date('Y-m-d',strtotime($dataMasukan['d_nilai_kinerja']));
			$q_nilai_dibawah = $dataMasukan['q_nilai_dibawah'];
			$q_nilai_perbaikan = $dataMasukan['q_nilai_perbaikan'];
			$q_nilai_sesuai = $dataMasukan['q_nilai_sesuai'];
			$q_nilai_diatas = $dataMasukan['q_nilai_diatas'];
			$i_entry = $dataMasukan['i_entry'];
			
			$paramInput = array("c_nilai_kinerja"	=> $c_nilai_kinerja,
					"n_faktor_kinerja"	=> $n_faktor_kinerja,
					"n_standar_kinerja"	=> $n_standar_kinerja,
					"d_nilai_kinerja"	=> $d_nilai_kinerja,
					"q_nilai_dibawah"	=> $q_nilai_dibawah,
					"q_nilai_perbaikan"	=> $q_nilai_perbaikan,
					"q_nilai_sesuai"	=> $q_nilai_sesuai,
					"q_nilai_diatas"	=> $q_nilai_diatas,
					"i_entry"	=> $i_entry,
					"d_entry"	=> date('Y-m-d'));
					//var_dump($paramInput);
			$db->insert('sdm.tr_formpenilaian_mil',$paramInput);
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
	
	public function detailNilai($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchRow("select c_nilai_kinerja, n_faktor_kinerja, n_standar_kinerja, d_nilai_kinerja,
					q_nilai_dibawah, q_nilai_perbaikan, q_nilai_sesuai, q_nilai_diatas from sdm.tr_formpenilaian_mil
								where c_nilai_kinerja  = ".$masukan['c_nilai_kinerja']);
					$jmlResult = count($result);
					$data = array("c_nilai_kinerja"=>(string)$result->c_nilai_kinerja,
							"n_faktor_kinerja"=>(string)$result->n_faktor_kinerja,
							"n_standar_kinerja"=>(string)$result->n_standar_kinerja,
							"d_nilai_kinerja"=>(string)$result->d_nilai_kinerja,
							"q_nilai_dibawah"=>(string)$result->q_nilai_dibawah,
							"q_nilai_perbaikan"=>(string)$result->q_nilai_perbaikan,
							"q_nilai_sesuai"=>(string)$result->q_nilai_sesuai,
							"q_nilai_diatas"=>(string)$result->q_nilai_diatas);
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function ubahnilai(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$c_nilai_kinerja = $dataMasukan['c_nilai_kinerja'];
			$n_faktor_kinerja = $dataMasukan['n_faktor_kinerja'];
			$n_standar_kinerja = $dataMasukan['n_standar_kinerja'];
			$d_nilai_kinerja = date('Y-m-d',strtotime($dataMasukan['d_nilai_kinerja']));
			$q_nilai_dibawah = $dataMasukan['q_nilai_dibawah'];
			$q_nilai_perbaikan = $dataMasukan['q_nilai_perbaikan'];
			$q_nilai_sesuai = $dataMasukan['q_nilai_sesuai'];
			$q_nilai_diatas = $dataMasukan['q_nilai_diatas'];
			$i_entry = $dataMasukan['i_entry'];
						
			$where[] = "c_nilai_kinerja = '".$c_nilai_kinerja."'";
			$paramInput = array("c_nilai_kinerja"	=> $c_nilai_kinerja,
					"n_faktor_kinerja"	=> $n_faktor_kinerja,
					"n_standar_kinerja"	=> $n_standar_kinerja,
					"d_nilai_kinerja"	=> $d_nilai_kinerja,
					"q_nilai_dibawah"	=> $q_nilai_dibawah,
					"q_nilai_perbaikan"	=> $q_nilai_perbaikan,
					"q_nilai_sesuai"	=> $q_nilai_sesuai,
					"q_nilai_diatas"	=> $q_nilai_diatas,
					"i_entry"	=> $i_entry,
					"d_entry"	=> date('Y-m-d'));
					//var_dump($paramInput);
			$db->update('sdm.tr_formpenilaian_mil',$paramInput, $where);
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
	
	public function hapusnilai(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$c_nilai_kinerja = $dataMasukan['c_nilai_kinerja'];			
			$where[] = "c_nilai_kinerja = '".$c_nilai_kinerja."'";
			
			//var_dump($where);
			$db->delete('sdm.tr_formpenilaian_mil', $where);
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
