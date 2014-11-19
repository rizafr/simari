<?php
class formulir1_Service {
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
	public function getformulir1($tahun)  
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
					$result = $db->fetchAll("select *
								from sdm.tm_formasiformulir1
								where thn_skrg = '$tahun' order by id");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("id"=>(string)$result[$j]->id,
									"gol_ruang_gaji"=>(string)$result[$j]->gol_ruang_gaji,
									"bezetting"=>(string)$result[$j]->bezetting,
									"thn_skrg"=>(string)$result[$j]->thn_skrg,
									"thn_sblm"=>(string)$result[$j]->thn_sblm,
									"knaikn_pngkt"=>(string)$result[$j]->knaikn_pngkt,
									"stlh_naik_pgkt"=>(string)$result[$j]->stlh_naik_pgkt,
									"pengangkt_pgwai_br"=>(string)$result[$j]->pengangkt_pgwai_br,
									"pndah_dr_instasi_lain"=>(string)$result[$j]->pndah_dr_instasi_lain,
									"pndah_ke_instasi_lain"=>(string)$result[$j]->pndah_ke_instasi_lain,
									"pns_yg_brhnti"=>(string)$result[$j]->pns_yg_brhnti,
									"bezetting_lajur"=>(string)$result[$j]->bezetting_lajur,
									"ket"=>(string)$result[$j]->ket);}
					
				
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function getjumgol()
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);
				
					$result = $db->fetchAll("select trim(n_peg_golongan) as n_peg_golongan, count(trim(n_peg_golongan)) as jumlah from 
								sdm.tr_golongan_pangkat a right join sdm.tm_pegawai b on(b.c_golongan = a.c_peg_golongan) 
								where c_peg_tipegolongan='3' group by n_peg_golongan order by n_peg_golongan DESC");
					$jmlResult = count($result);
					for($j = 0; $j < $jmlResult; $j++)
					{
						$data[$j] = array("n_peg_golongan"=>(string)$result[$j]->n_peg_golongan,
									"jumlah"=>(string)$result[$j]->jumlah);
					}
				return $data;
			}catch (Exception $e)
			{
				echo $e->getMessage().'<br>';
				return 'Data tidak ada <br>';
			}
	}
	
	public function tambahformulir1(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$gol_ruang_gaji = $dataMasukan['gol_ruang_gaji'];
			$bezetting = $dataMasukan['bezetting'];
			$thn_skrg = $dataMasukan['thn_skrg'];
			$thn_sblm = $dataMasukan['thn_sblm'];
			$knaikn_pngkt = $dataMasukan['knaikn_pngkt'];
			$stlh_naik_pgkt = $dataMasukan['stlh_naik_pgkt'];
			$pengangkt_pgwai_br = $dataMasukan['pengangkt_pgwai_br'];
			$pndah_dr_instasi_lain = $dataMasukan['pndah_dr_instasi_lain'];
			$pndah_ke_instasi_lain = $dataMasukan['pndah_ke_instasi_lain'];
			$pns_yg_brhnti = $dataMasukan['pns_yg_brhnti'];
			$bezetting_lajur = $dataMasukan['bezetting_lajur'];
			$ket = $dataMasukan['ket'];
			$i_entry = $dataMasukan['i_entry'];
						
			$paramInput = array("gol_ruang_gaji"	=> $gol_ruang_gaji,
					"bezetting"	=> $bezetting,
					"thn_skrg"	=> $thn_skrg,
					"thn_sblm"	=> $thn_sblm,
					"knaikn_pngkt"	=> $knaikn_pngkt,
					"stlh_naik_pgkt"	=> $stlh_naik_pgkt,
					"pengangkt_pgwai_br"	=> $pengangkt_pgwai_br,
					"pndah_dr_instasi_lain"	=> $pndah_dr_instasi_lain,
					"pndah_ke_instasi_lain"	=> $pndah_ke_instasi_lain,
					"pns_yg_brhnti"	=> $pns_yg_brhnti,
					"bezetting_lajur"	=> $bezetting_lajur,
					"ket"	=> $ket,
					"i_entry"	=> $i_entry,
					"d_entry"	=> date('Y-m-d H:i:s'));
					//var_dump($paramInput);
			$db->insert('sdm.tm_formasiformulir1',$paramInput);
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
	
	public function detailFormulir1($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchRow("select gol_ruang_gaji, bezetting, thn_skrg, thn_sblm, knaikn_pngkt, stlh_naik_pgkt, pengangkt_pgwai_br,
							pndah_dr_instasi_lain, pndah_ke_instasi_lain, pns_yg_brhnti, bezetting_lajur, ket
							from sdm.tm_formasiformulir1 
							where id  = '".$masukan['id']."'");
							
							/* echo "select c_agama, n_agama from sdm.tr_agama 
							where c_agama  = '".$masukan['c_agama']."'"; */
				$jmlResult = count($result);
				$data = array("gol_ruang_gaji"=>(string)$result->gol_ruang_gaji,
					      "bezetting"=>(string)$result->bezetting,
					      "thn_skrg"=>(string)$result->thn_skrg,
					      "thn_sblm"=>(string)$result->thn_sblm,
					      "knaikn_pngkt"=>(string)$result->knaikn_pngkt,
					      "stlh_naik_pgkt"=>(string)$result->stlh_naik_pgkt,
					      "pengangkt_pgwai_br"=>(string)$result->pengangkt_pgwai_br,
					      "pndah_dr_instasi_lain"=>(string)$result->pndah_dr_instasi_lain,
					      "pndah_ke_instasi_lain"=>(string)$result->pndah_ke_instasi_lain,
					      "pns_yg_brhnti"=>(string)$result->pns_yg_brhnti,
					      "bezetting_lajur"=>(string)$result->bezetting_lajur,
					      "ket"=>(string)$result->ket);
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahformulir1(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$id = $dataMasukan['id'];
			$gol_ruang_gaji = $dataMasukan['gol_ruang_gaji'];
			$bezetting = $dataMasukan['bezetting'];
			$thn_skrg = $dataMasukan['thn_skrg'];
			$thn_sblm = $dataMasukan['thn_sblm'];
			$knaikn_pngkt = $dataMasukan['knaikn_pngkt'];
			$stlh_naik_pgkt = $dataMasukan['stlh_naik_pgkt'];
			$pengangkt_pgwai_br = $dataMasukan['pengangkt_pgwai_br'];
			$pndah_dr_instasi_lain = $dataMasukan['pndah_dr_instasi_lain'];
			$pndah_ke_instasi_lain = $dataMasukan['pndah_ke_instasi_lain'];
			$pns_yg_brhnti = $dataMasukan['pns_yg_brhnti'];
			$bezetting_lajur = $dataMasukan['bezetting_lajur'];
			$ket = $dataMasukan['ket'];
			$i_entry = $dataMasukan['i_entry'];
			
			$where[] = "id = '".$id."'";
			$paramInput = array("gol_ruang_gaji"	=> $gol_ruang_gaji,
					"bezetting"	=> $bezetting,
					"thn_skrg"	=> $thn_skrg,
					"thn_sblm"	=> $thn_sblm,
					"knaikn_pngkt"	=> $knaikn_pngkt,
					"stlh_naik_pgkt"	=> $stlh_naik_pgkt,
					"pengangkt_pgwai_br"	=> $pengangkt_pgwai_br,
					"pndah_dr_instasi_lain"	=> $pndah_dr_instasi_lain,
					"pndah_ke_instasi_lain"	=> $pndah_ke_instasi_lain,
					"pns_yg_brhnti"	=> $pns_yg_brhnti,
					"bezetting_lajur"	=> $bezetting_lajur,
					"ket"	=> $ket,
					"i_entry"	=> $i_entry,
					"d_entry"	=> date('Y-m-d H:i:s'));
					//var_dump($paramInput);
			$db->update('sdm.tm_formasiformulir1',$paramInput, $where);
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