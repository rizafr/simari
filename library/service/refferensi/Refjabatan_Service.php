<?php
class refjabatan_Service {
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
	public function getCodeJabatan()  
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);
				
				$sql = "select max(c_jabatan) from sdm.tr_jabatan";
				$data = $db->fetchOne($sql);
				$result = $data + 1;
				if(strlen($result) == 1) $result = '0'.$result;
				return $result;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	public function getJabatanList($cari, $currentPage, $numToDisplay)  
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);
				
				$sql = "select c_jabatan, n_jabatan, e_keterangan, c_tkfgs,
					c_kelfgs, c_golr, c_golt, n_jenjang, c_tanda, c_eselon, c_strata, q_tunjangan,
					q_usia_pens, q_tktfgs, q_ak_minimal from sdm.tr_jabatan where 1=1 $cari";
				
				if(($currentPage == 0) && ($numToDisplay == 0)){
					$data = $db->fetchOne("select count(*) from ($sql) a");
				} else {
					$xLimit=$numToDisplay;
			  		$xOffset=($currentPage-1)*$numToDisplay;	
					
					$result = $db->fetchAll("$sql order by c_jabatan limit $xLimit offset $xOffset");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$n_tkfgs ='';$n_kelfgs = '';$n_golr = '';$n_golt = '';$n_eselon = '';$n_strata = '';
						$c_tkfgs 	= (string)$result[$j]->c_tkfgs;
						if($c_tkfgs != ''  && $c_tkfgs != '00') $n_tkfgs = $db->fetchOne("select n_title from sdm.tr_tingkat_fungsional where c_tkfgs='$c_tkfgs'");
						$c_kelfgs 	= (string)$result[$j]->c_kelfgs;
						if($c_kelfgs != ''  && $c_kelfgs != '00') $n_kelfgs = $db->fetchOne("select n_kelfungsional from sdm.tr_kelfungsional where c_kelfungsional='$c_kelfgs'");
						$c_golr 	= (string)$result[$j]->c_golr;
						if($c_golr != ''  && $c_golr != '00') $n_golr = $db->fetchOne("select n_peg_golongan from sdm.tr_golongan_pangkat where c_peg_golongan='$c_golr' and c_peg_tipegolongan='3'");
						$c_golt 	= (string)$result[$j]->c_golt;
						if($c_golt != ''  && $c_golt != '00') $n_golt = $db->fetchOne("select n_peg_golongan from sdm.tr_golongan_pangkat where c_peg_golongan='$c_golt' and c_peg_tipegolongan='3'");
						$c_eselon 	= (string)$result[$j]->c_eselon;
						if($c_eselon != ''  && $c_eselon != '00') $n_eselon = $db->fetchOne("select n_eselon from sdm.tr_eselon where c_eselon='$c_eselon'");
						$c_strata 	= (string)$result[$j]->c_strata;
						if($c_strata != ''  && $c_strata != '00') $n_strata = $db->fetchOne("select n_pend from sdm.tr_pendidikan where c_pend='$c_pend'");
						
						$data[$j] = array("c_jabatan"=>(string)$result[$j]->c_jabatan,
								"n_jabatan"=>(string)$result[$j]->n_jabatan,
								"e_keterangan"=>(string)$result[$j]->e_keterangan,
								"c_tkfgs"=>(string)$result[$j]->c_tkfgs,
								"c_kelfgs"=>(string)$result[$j]->c_kelfgs,
								"n_tkfgs"=>$n_tkfgs,
								"n_kelfgs"=>$n_kelfgs,
								"c_golr"=>(string)$result[$j]->c_golr,
								"c_golt"=>(string)$result[$j]->c_golt,
								"n_golr"=>$n_golr,
								"n_golt"=>$n_golt,
								"n_jenjang"=>(string)$result[$j]->n_jenjang,
								"c_tanda"=>(string)$result[$j]->c_tanda,
								"c_eselon"=>(string)$result[$j]->c_eselon,
								"n_eselon"=>$n_eselon,
								"c_strata"=>(string)$result[$j]->c_strata,
								"n_strata"=>$n_strata,
								"q_tunjangan"=>(string)$result[$j]->q_tunjangan,
								"q_usia_pens"=>(string)$result[$j]->q_usia_pens,
								"q_tktfgs"=>(string)$result[$j]->q_tktfgs,
								"q_ak_minimal"=>(string)$result[$j]->q_ak_minimal
								);}
				}
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambahjabatan(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$c_jabatan = $dataMasukan['c_jabatan'];
			$n_jabatan = $dataMasukan['n_jabatan'];
			$e_keterangan = $dataMasukan['e_keterangan'];
			$c_tkfgs = $dataMasukan['c_tkfgs'];
			$c_kelfgs = $dataMasukan['c_kelfgs'];
			$c_golr = $dataMasukan['c_golr'];
			$c_golt = $dataMasukan['c_golt'];
			$n_jenjang = $dataMasukan['n_jenjang'];
			$c_tanda = $dataMasukan['c_tanda'];
			$c_eselon = $dataMasukan['c_eselon'];
			$c_strata = $dataMasukan['c_strata'];
			$q_tunjangan = $dataMasukan['q_tunjangan'];
			$q_usia_pens = $dataMasukan['q_usia_pens'];
			$q_tktfgs = $dataMasukan['q_tktfgs'];
			$q_ak_minimal = $dataMasukan['q_ak_minimal'];
						
			$paramInput = array("c_jabatan"	=> $c_jabatan,
					"n_jabatan"	=> $n_jabatan,
					"e_keterangan"	=> $e_keterangan,
					"c_tkfgs"	=> $c_tkfgs,
					"c_kelfgs"	=> $c_kelfgs,
					"c_golr"	=> $c_golr,
					"c_golt"	=> $c_golt,
					"n_jenjang"	=> $n_jenjang,
					"c_tanda"	=> $c_tanda,
					"c_eselon"	=> $c_eselon,
					"c_strata"	=> $c_strata,
					"q_tunjangan"	=> $q_tunjangan,
					"q_usia_pens"	=> $q_usia_pens,
					"q_tktfgs"	=> $q_tktfgs,
					"q_ak_minimal"	=> $q_ak_minimal);
					//var_dump($paramInput);
			$db->insert('sdm.tr_jabatan',$paramInput);
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
	
	public function detailJabatan($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchRow("select c_jabatan, n_jabatan, e_keterangan, c_tkfgs,
					c_kelfgs, c_golr, c_golt, n_jenjang, c_tanda, c_eselon, c_strata, q_tunjangan, 
					q_usia_pens, q_tktfgs, q_ak_minimal from sdm.tr_jabatan 
					where c_jabatan  = '".$masukan['c_jabatan']."'");
					$jmlResult = count($result);
					$data = array("c_jabatan"=>(string)$result->c_jabatan,
							"n_jabatan"=>(string)$result->n_jabatan,
							"e_keterangan"=>(string)$result->e_keterangan,
							"c_tkfgs"=>(string)$result->c_tkfgs,
							"c_kelfgs"=>(string)$result->c_kelfgs,
							"c_golr"=>(string)$result->c_golr,
							"c_golt"=>(string)$result->c_golt,
							"n_jenjang"=>(string)$result->n_jenjang,
							"c_tanda"=>(string)$result->c_tanda,
							"c_eselon"=>(string)$result->c_eselon,
							"c_strata"=>(string)$result->c_strata,
							"q_tunjangan"=>(string)$result->q_tunjangan,
							"q_usia_pens"=>(string)$result->q_usia_pens,
							"q_tktfgs"=>(string)$result->q_tktfgs,
							"q_ak_minimal"=>(string)$result->q_ak_minimal);
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahjabatan(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$c_jabatan = $dataMasukan['c_jabatan'];
			$n_jabatan = $dataMasukan['n_jabatan'];
			$e_keterangan = $dataMasukan['e_keterangan'];
			$c_tkfgs = $dataMasukan['c_tkfgs'];
			$c_kelfgs = $dataMasukan['c_kelfgs'];
			$c_golr = $dataMasukan['c_golr'];
			$c_golt = $dataMasukan['c_golt'];
			$n_jenjang = $dataMasukan['n_jenjang'];
			$c_tanda = $dataMasukan['c_tanda'];
			$c_eselon = $dataMasukan['c_eselon'];
			$c_strata = $dataMasukan['c_strata'];
			$q_tunjangan = $dataMasukan['q_tunjangan'];
			$q_usia_pens = $dataMasukan['q_usia_pens'];
			$q_tktfgs = $dataMasukan['q_tktfgs'];
			$q_ak_minimal = $dataMasukan['q_ak_minimal'];
						
			$where[] = "c_jabatan = '".$c_jabatan."'";
			$paramInput = array("c_jabatan"	=> $c_jabatan,
					"n_jabatan"	=> $n_jabatan,
					"e_keterangan"	=> $e_keterangan,
					"c_tkfgs"	=> $c_tkfgs,
					"c_kelfgs"	=> $c_kelfgs,
					"c_golr"	=> $c_golr,
					"c_golt"	=> $c_golt,
					"n_jenjang"	=> $n_jenjang,
					"c_tanda"	=> $c_tanda,
					"c_eselon"	=> $c_eselon,
					"c_strata"	=> $c_strata,
					"q_tunjangan"	=> $q_tunjangan,
					"q_usia_pens"	=> $q_usia_pens,
					"q_tktfgs"	=> $q_tktfgs,
					"q_ak_minimal"	=> $q_ak_minimal);
					//var_dump($paramInput);
			$paramInputs = array("q_usia_pensiun" => $q_usia_pens);
			$db->update('sdm.tm_pegawai',$paramInputs, $where);
			$db->update('sdm.tr_jabatan',$paramInput, $where);
			
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
	
	public function hapusjabatan(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$c_jabatan = $dataMasukan['c_jabatan'];			
			$where[] = "c_jabatan = '".$c_jabatan."'";
			
			//var_dump($where);
			$db->delete('sdm.tr_jabatan', $where);
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