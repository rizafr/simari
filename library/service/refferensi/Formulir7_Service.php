<?php
class formulir7_Service {
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
	public function getformulir7($tahun)  
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
					$result = $db->fetchAll("select *
								from sdm.tm_formasiformulir7
								where thn_skrg = '$tahun' order by id");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("id"=>(string)$result[$j]->id,
									"perwakilan"=>(string)$result[$j]->perwakilan,
									"thn_skrg"=>(string)$result[$j]->thn_skrg,
									"thn_sblm"=>(string)$result[$j]->thn_sblm,
									"jum_pegawai"=>(string)$result[$j]->jum_pegawai,
									"formasi"=>(string)$result[$j]->formasi,
									"ket"=>(string)$result[$j]->ket);}
					
				
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function getJumformulir7($tahun)  
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
					$result = $db->fetchAll("select sum(jum_pegawai) as jum_pegawai, sum(formasi) as formasi							
								from sdm.tm_formasiformulir7
								where thn_skrg = '$tahun'");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("jum_pegawai"=>(string)$result[$j]->jum_pegawai,
									"formasi"=>(string)$result[$j]->formasi);}
					
				
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambahformulir7(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$perwakilan = $dataMasukan['perwakilan'];
			$thn_skrg = $dataMasukan['thn_skrg'];
			$thn_sblm = $dataMasukan['thn_sblm'];
			
			if($dataMasukan['jum_pegawai'] == ""){
			$jum_pegawai = 0;
			}else{
			$jum_pegawai = $dataMasukan['jum_pegawai'];
			}
			
			if($dataMasukan['formasi'] == ""){
			$formasi = 0;
			}else{
			$formasi = $dataMasukan['formasi'];
			}
			
			$ket = $dataMasukan['ket'];
			$i_entry = $dataMasukan['i_entry'];
						
			$paramInput = array("perwakilan"	=> $perwakilan,
					"thn_skrg"	=> $thn_skrg,
					"thn_sblm"	=> $thn_sblm,
					"jum_pegawai"	=> $jum_pegawai,
					"formasi"	=> $formasi,
					"ket"	=> $ket,
					"i_entry"	=> $i_entry,
					"d_entry"	=> date('Y-m-d H:i:s'));
					//var_dump($paramInput);
			$db->insert('sdm.tm_formasiformulir7',$paramInput);
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
	
	public function detailFormulir7($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchRow("select perwakilan, thn_skrg, thn_sblm, jum_pegawai, formasi, ket
							from sdm.tm_formasiformulir7
							where id  = '".$masukan['id']."'");
							
							/* echo "select c_agama, n_agama from sdm.tr_agama 
							where c_agama  = '".$masukan['c_agama']."'"; */
				$jmlResult = count($result);
				$data = array("perwakilan"=>(string)$result->perwakilan,
					      "thn_skrg"=>(string)$result->thn_skrg,
					      "thn_sblm"=>(string)$result->thn_sblm,
					      "jum_pegawai"=>(string)$result->jum_pegawai,
					      "formasi"=>(string)$result->formasi,
					      "ket"=>(string)$result->ket);
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahformulir7(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$id = $dataMasukan['id'];
			$perwakilan = $dataMasukan['perwakilan'];
			$thn_skrg = $dataMasukan['thn_skrg'];
			$thn_sblm = $dataMasukan['thn_sblm'];
			
			if($dataMasukan['jum_pegawai'] == ""){
			$jum_pegawai = 0;
			}else{
			$jum_pegawai = $dataMasukan['jum_pegawai'];
			}
			
			if($dataMasukan['formasi'] == ""){
			$formasi = 0;
			}else{
			$formasi = $dataMasukan['formasi'];
			}
			
			$ket = $dataMasukan['ket'];
			$i_entry = $dataMasukan['i_entry'];
			
			$where[] = "id = '".$id."'";
			$paramInput = array("perwakilan"	=> $perwakilan,
					"thn_skrg"	=> $thn_skrg,
					"thn_sblm"	=> $thn_sblm,
					"jum_pegawai"	=> $jum_pegawai,
					"formasi"	=> $formasi,
					"ket"	=> $ket,
					"i_entry"	=> $i_entry,
					"d_entry"	=> date('Y-m-d H:i:s'));
					//var_dump($paramInput);
			$db->update('sdm.tm_formasiformulir7',$paramInput, $where);
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
	
	public function hapusformulir7(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$id = $dataMasukan['id'];			
			$where[] = "id = '".$id."'";
			
			//var_dump($where);
			$db->delete('sdm.tm_formasiformulir7', $where);
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
}?>