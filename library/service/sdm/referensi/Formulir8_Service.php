<?php
class formulir8_Service {
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
	public function getformulir8($tahun)  
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
					$result = $db->fetchAll("select id, nm_jabatan, thn_skrg, berhenti, pensiun, meninggal_dunia, 
								jml, ket, c_jabatan, c_lokasi_unit, n_lokasi_unit, n_eselon_i, c_eselon_i, 
								n_eselon_ii, c_eselon_ii, c_eselon_ii_1,
								n_eselon_iii, c_eselon_ii_iii, c_eselon_iii
								from sdm.tm_formasiformulir8
								where thn_skrg = '$tahun' order by id");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("id"=>(string)$result[$j]->id,
									"nm_jabatan"=>(string)$result[$j]->nm_jabatan,
									"thn_skrg"=>(string)$result[$j]->thn_skrg,
									"berhenti"=>(string)$result[$j]->berhenti,
									"pensiun"=>(string)$result[$j]->pensiun,
									"meninggal_dunia"=>(string)$result[$j]->meninggal_dunia,
									"jml"=>(string)$result[$j]->jml,
									"ket"=>(string)$result[$j]->ket,
									"c_jabatan"=>(string)$result[$j]->c_jabatan,
									"c_lokasi_unit"=>(string)$result[$j]->c_lokasi_unit,
									"n_lokasi_unit"=>(string)$result[$j]->n_lokasi_unit,
									"n_eselon_i"=>(string)$result[$j]->n_eselon_i,
									"c_eselon_i"=>(string)$result[$j]->c_eselon_i,
									"n_eselon_ii"=>(string)$result[$j]->n_eselon_ii,
									"c_eselon_ii"=>(string)$result[$j]->c_eselon_ii,
									"c_eselon_ii_1"=>(string)$result[$j]->c_eselon_ii_1,
									"n_eselon_iii"=>(string)$result[$j]->n_eselon_iii,
									"c_eselon_ii_iii"=>(string)$result[$j]->c_eselon_ii_iii,
									"c_eselon_iii"=>(string)$result[$j]->c_eselon_iii);}
					
				
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function getJumformulir8($tahun)  
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
					$result = $db->fetchAll("select sum(berhenti) as berhenti, sum(pensiun) as pensiun,
								sum(meninggal_dunia) as meninggal_dunia, sum(jml) as jml
								from sdm.tm_formasiformulir8
								where thn_skrg = '$tahun'");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("berhenti"=>(string)$result[$j]->berhenti,
									"pensiun"=>(string)$result[$j]->pensiun,
									"meninggal_dunia"=>(string)$result[$j]->meninggal_dunia,
									"jml"=>(string)$result[$j]->jml);}
					
				
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	/*
	public function getUnitList() 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
					$result = $db->fetchAll("(select c_satker as satker, c_satker || '-' || replace(replace(n_unitkerja, 'Direktorat Jenderal', 'Ditjen'), 
								'Badan Penelitian dan Pengembangan dan Pendidikan dan Pelatihan Hukum dan Peradilan', 'Balitbangdiklatkumdil') as nama_satker
									from sdm.tr_unitkerja
									where c_satker is not null
									and c_eselon_ii = '00'
									and c_eselon_iii = '00'
									and c_eselon_iv = '00'
									and c_eselon_v = '00' 
									order by c_eselon_i)
									union all
									(select c_satker, c_satker || '-' || replace(n_unitkerja, 'Pengadilan Tinggi', 'PT')
									from sdm.tr_unitkerja
									where c_satker is not null
									and c_lokasi_unitkerja = '3'
									and c_eselon_iii = '00'
									and c_eselon_iv = '00'
									and c_eselon_v = '00'
									and c_child = '00' 
									order by c_bidang, c_eselon_i, c_eselon_ii)
									union all
									(select c_satker, c_satker || '-' || replace(n_unitkerja, 'Pengadilan Negeri', 'PN')
									from sdm.tr_unitkerja
									where c_satker is not null
									and c_lokasi_unitkerja = '3'
									and c_eselon_iii = '00'
									and c_eselon_iv = '00'
									and c_eselon_v = '00'
									and c_child != '00' 
									order by c_bidang, c_eselon_i, c_eselon_ii)");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("c_satker"=>(string)$result[$j]->c_satker,
									"satker"=>(string)$result[$j]->satker,
									"nama_satker"=>(string)$result[$j]->nama_satker);}
				
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}*/
	
	public function getLokasi()
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
				$result = $db->fetchAll("select c_lokasi, n_lokasi from sdm.tr_lokasi order by c_lokasi");
				$jmlResult = count($result);
				for ($j = 0;$j < $jmlResult; $j++)
				{
					$data[$j] = array("c_lokasi"=>(string)$result[$j]->c_lokasi,
								"n_lokasi"=>(string)$result[$j]->n_lokasi);
				}
			return $data;
		} catch (Exception $e)
		{
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	
	public function getEselon1($lokasi)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
				
				if($lokasi == "1"){
				$result = $db->fetchAll("select c_eselon_i, n_unitkerja from sdm.tr_unitkerja where c_lokasi_unitkerja = '1' 
							and c_tkt_esl = '1' and c_eselon_ii = '000' and c_eselon_iii = '00' and c_eselon_iv = '00' and c_eselon_v = '00' 
							order by c_eselon_i");
				}elseif($lokasi == "3"){
				$result = $db->fetchAll("select c_eselon_i, n_unitkerja from sdm.tr_unitkerja where c_lokasi_unitkerja = '1' 
							and c_eselon_i between  '03' and '05' and c_eselon_ii = '000' and c_eselon_iii = '00' and c_eselon_iv = '00' 
							and c_eselon_v = '00' order by c_eselon_i");
				}
				$jmlResult = count($result);
				for ($j = 0;$j < $jmlResult; $j++)
				{
					$data[$j] = array("c_eselon_i"=>(string)$result[$j]->c_eselon_i,
								"n_unitkerja"=>(string)$result[$j]->n_unitkerja);
				}
			return $data;
		} catch (Exception $e)
		{
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	
	public function getEselon2($lokasi,$c_eselon1)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
				
				if($lokasi == "1" and $c_eselon1 != ""){
					if($c_eselon1 == "01"){
					$result = $db->fetchAll("select c_eselon_ii, n_unitkerja from sdm.tr_unitkerja where c_lokasi_unitkerja = '1' 
							and c_eselon_i = '01' and c_eselon_ii >='000' and c_eselon_iii >= '00' and c_eselon_iv = '00' 
							and c_eselon_v = '00' and n_unitkerja like 'Biro%' order by c_eselon_ii");
					}else{
					$result = $db->fetchAll("select c_eselon_ii, n_unitkerja from sdm.tr_unitkerja where c_lokasi_unitkerja = '1' and c_tkt_esl = '2'
							and c_eselon_i = '$c_eselon1' and not c_eselon_ii ='000' and c_eselon_iii = '00' and c_eselon_iv = '00' 
							and c_eselon_v = '00' order by c_eselon_ii");}
				}elseif($lokasi == "3" and $c_eselon1 != ""){
					if($c_eselon1 == "05"){
						$result = $db->fetchAll("select c_eselon_ii, n_unitkerja from sdm.tr_unitkerja where c_lokasi_unitkerja = '3' 
							and c_eselon_i = '05' and n_unitkerja like '%Pengadilan Tinggi%' or n_unitkerja like '%PENGADILAN MILITER TINGGI%' 
							or n_unitkerja like '%PENGADILAN MILITER UTAMA%' order by c_eselon_ii");
					}elseif($c_eselon1 == "04"){
						$result = $db->fetchAll("select c_eselon_ii, n_unitkerja from sdm.tr_unitkerja where c_lokasi_unitkerja = '3' 
							and c_eselon_i = '04' and not c_eselon_ii ='000' and c_eselon_iii = '00' and c_eselon_iv = '00' 
							and c_eselon_v = '00' and n_unitkerja like '%Pengadilan Tinggi%' or n_unitkerja like '%MAHKAMAH SYARIAH%' order by c_eselon_ii");}
					else{
						$result = $db->fetchAll("select c_eselon_ii, n_unitkerja from sdm.tr_unitkerja where c_lokasi_unitkerja = '3' 
							and c_eselon_i = '$c_eselon1' and not c_eselon_ii ='000' and c_eselon_iii = '00' and c_eselon_iv = '00' 
							and c_eselon_v = '00' and n_unitkerja like '%Pengadilan Tinggi%' order by c_eselon_ii");}
				}
				$jmlResult = count($result);
				for ($j = 0;$j < $jmlResult; $j++)
				{
					$data[$j] = array("c_eselon_ii"=>(string)$result[$j]->c_eselon_ii,
								"n_unitkerja"=>(string)$result[$j]->n_unitkerja);
				}
			return $data;
		} catch (Exception $e)
		{
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	
	public function getEselon3($lokasi,$c_eselon1,$c_eselon2,$c_eselon2Next)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
				
				if($lokasi == "1" && $c_eselon1 != "" && $c_eselon2 != ""){
				$result = $db->fetchAll("select c_eselon_ii, c_eselon_iii, n_unitkerja from sdm.tr_unitkerja where c_lokasi_unitkerja = '1' and c_tkt_esl = '3'
							and c_eselon_i = '$c_eselon1' and c_eselon_ii ='$c_eselon2' and c_eselon_iii > '00' and c_eselon_iv = '00' 
							and c_eselon_v = '00' order by c_eselon_iii");
				}elseif($lokasi == "3" && $c_eselon1 != "" && $c_eselon2 != ""){
				$result = $db->fetchAll("select c_eselon_ii, c_eselon_iii, n_unitkerja from sdm.tr_unitkerja where c_lokasi_unitkerja = '3' 
							and c_eselon_i = '$c_eselon1' and c_eselon_ii >  '$c_eselon2'  and c_eselon_ii < '$c_eselon2Next' and c_eselon_iii = '00' and c_eselon_iv = '00' 
							and c_eselon_v = '00' order by c_eselon_ii");
				}
				$jmlResult = count($result);
				for ($j = 0;$j < $jmlResult; $j++)
				{
					$data[$j] = array("c_eselon_ii"=>(string)$result[$j]->c_eselon_ii,
								"c_eselon_iii"=>(string)$result[$j]->c_eselon_iii,
								"n_unitkerja"=>(string)$result[$j]->n_unitkerja);
				}
			return $data;
		} catch (Exception $e)
		{
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	
	public function getNamaJabatan()
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
				$result = $db->fetchAll("select c_jabatan, n_jabatan from sdm.tr_jabatan order by c_jabatan");
				$jmlResult = count($result);
				for ($j = 0;$j < $jmlResult; $j++)
				{
					$data[$j] = array("c_jabatan"=>(string)$result[$j]->c_jabatan,
								"n_jabatan"=>(string)$result[$j]->n_jabatan);
				}
			return $data;
		} catch (Exception $e)
		{
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	
	public function tambahformulir8(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$nm_jabatan = $dataMasukan['nm_jabatan'];
			$thn_skrg = $dataMasukan['thn_skrg'];
			
			if($dataMasukan['berhenti'] == ""){
			$berhenti = 0;
			}else{
			$berhenti = $dataMasukan['berhenti'];
			}
						
			if($dataMasukan['pensiun'] == ""){
			$pensiun = 0;
			}else{
			$pensiun = $dataMasukan['pensiun'];
			}
						
			if($dataMasukan['meninggal_dunia'] == ""){
			$meninggal_dunia = 0;
			}else{
			$meninggal_dunia = $dataMasukan['meninggal_dunia'];
			}
						
			if($dataMasukan['jml'] == ""){
			$jml = 0;
			}else{
			$jml = $dataMasukan['jml'];
			}
			
			$ket = $dataMasukan['ket'];
			$i_entry = $dataMasukan['i_entry'];
			$c_jabatan = $dataMasukan['c_jabatan'];
			$c_lokasi_unit = $dataMasukan['c_lokasi_unit'];
			$n_lokasi_unit = $dataMasukan['n_lokasi_unit'];
			$n_eselon_i = $dataMasukan['n_eselon_i'];
			$c_eselon_i = $dataMasukan['c_eselon_i'];
			$n_eselon_ii = $dataMasukan['n_eselon_ii'];
			$c_eselon_ii = $dataMasukan['c_eselon_ii'];
			$c_eselon_ii_1 = $dataMasukan['c_eselon_ii_1'];
			$n_eselon_iii = $dataMasukan['n_eselon_iii'];
			$c_eselon_ii_iii = $dataMasukan['c_eselon_ii_iii'];
			$c_eselon_iii = $dataMasukan['c_eselon_iii'];
						
			$paramInput = array("nm_jabatan"	=> $nm_jabatan,
					"thn_skrg"	=> $thn_skrg,
					"berhenti"	=> $berhenti,
					"pensiun"	=> $pensiun,
					"meninggal_dunia"	=> $meninggal_dunia,
					"jml"	=> $jml,
					"ket"	=> $ket,
					"i_entry"	=> $i_entry,
					"d_entry"	=> date('Y-m-d H:i:s'),
					"c_jabatan"	=> $c_jabatan,					
					"c_lokasi_unit"	=> $c_lokasi_unit,															
					"n_lokasi_unit"	=> $n_lokasi_unit,
					"n_eselon_i"	=> $n_eselon_i,
					"c_eselon_i"	=> $c_eselon_i,
					"n_eselon_ii"	=> $n_eselon_ii,
					"c_eselon_ii"	=> $c_eselon_ii,					
					"c_eselon_ii_1"	=> $c_eselon_ii_1,
					"n_eselon_iii"	=> $n_eselon_iii,
					"c_eselon_ii_iii"	=> $c_eselon_ii_iii,
					"c_eselon_iii"	=> $c_eselon_iii);
					//var_dump($paramInput);
			$db->insert('sdm.tm_formasiformulir8',$paramInput);
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
	
	public function detailFormulir8($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchRow("select nm_jabatan, thn_skrg, berhenti, pensiun, meninggal_dunia, 
								jml, ket, c_jabatan, c_lokasi_unit, n_lokasi_unit, n_eselon_i, c_eselon_i, 
								n_eselon_ii, c_eselon_ii, c_eselon_ii_1,
								n_eselon_iii, c_eselon_ii_iii, c_eselon_iii
							from sdm.tm_formasiformulir8 
							where id  = '".$masukan['id']."'");
							
							/* echo "select c_agama, n_agama from sdm.tr_agama 
							where c_agama  = '".$masukan['c_agama']."'"; */
				$jmlResult = count($result);
				$data = array("nm_jabatan"=>(string)$result->nm_jabatan,	
					      "thn_skrg"=>(string)$result->thn_skrg,
					      "berhenti"=>(string)$result->berhenti,
					      "pensiun"=>(string)$result->pensiun,
					      "meninggal_dunia"=>(string)$result->meninggal_dunia,
					      "jml"=>(string)$result->jml,
					      "ket"=>(string)$result->ket,
					       "c_jabatan"=>(string)$result->c_jabatan,
						"c_lokasi_unit"=>(string)$result->c_lokasi_unit,
						"n_lokasi_unit"=>(string)$result->n_lokasi_unit,
						"n_eselon_i"=>(string)$result->n_eselon_i,
						"c_eselon_i"=>(string)$result->c_eselon_i,
						"n_eselon_ii"=>(string)$result->n_eselon_ii,
						"c_eselon_ii"=>(string)$result->c_eselon_ii,
						"c_eselon_ii_1"=>(string)$result->c_eselon_ii_1,
						"n_eselon_iii"=>(string)$result->n_eselon_iii,
						"c_eselon_ii_iii"=>(string)$result->c_eselon_ii_iii,
						"c_eselon_iii"=>(string)$result->c_eselon_iii);
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahformulir8(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$id = $dataMasukan['id'];
			$unit_organisasi = $dataMasukan['unit_organisasi'];
			$nm_jabatan = $dataMasukan['nm_jabatan'];
			$thn_skrg = $dataMasukan['thn_skrg'];
			
			if($dataMasukan['berhenti'] == ""){
			$berhenti = 0;
			}else{
			$berhenti = $dataMasukan['berhenti'];
			}
						
			if($dataMasukan['pensiun'] == ""){
			$pensiun = 0;
			}else{
			$pensiun = $dataMasukan['pensiun'];
			}
						
			if($dataMasukan['meninggal_dunia'] == ""){
			$meninggal_dunia = 0;
			}else{
			$meninggal_dunia = $dataMasukan['meninggal_dunia'];
			}
						
			if($dataMasukan['jml'] == ""){
			$jml = 0;
			}else{
			$jml = $dataMasukan['jml'];
			}
			
			$ket = $dataMasukan['ket'];
			$i_entry = $dataMasukan['i_entry'];
			$c_jabatan = $dataMasukan['c_jabatan'];
			$c_lokasi_unit = $dataMasukan['c_lokasi_unit'];
			$n_lokasi_unit = $dataMasukan['n_lokasi_unit'];
			$n_eselon_i = $dataMasukan['n_eselon_i'];
			$c_eselon_i = $dataMasukan['c_eselon_i'];
			$n_eselon_ii = $dataMasukan['n_eselon_ii'];
			$c_eselon_ii = $dataMasukan['c_eselon_ii'];
			$c_eselon_ii_1 = $dataMasukan['c_eselon_ii_1'];
			$n_eselon_iii = $dataMasukan['n_eselon_iii'];
			$c_eselon_ii_iii = $dataMasukan['c_eselon_ii_iii'];
			$c_eselon_iii = $dataMasukan['c_eselon_iii'];
			
			$where[] = "id = '".$id."'";
			$paramInput = array("nm_jabatan"	=> $nm_jabatan,
					"thn_skrg"	=> $thn_skrg,
					"berhenti"	=> $berhenti,
					"pensiun"	=> $pensiun,
					"meninggal_dunia"	=> $meninggal_dunia,
					"jml"	=> $jml,
					"ket"	=> $ket,
					"i_entry"	=> $i_entry,
					"d_entry"	=> date('Y-m-d H:i:s'),
					"c_jabatan"	=> $c_jabatan,					
					"c_lokasi_unit"	=> $c_lokasi_unit,															
					"n_lokasi_unit"	=> $n_lokasi_unit,
					"n_eselon_i"	=> $n_eselon_i,
					"c_eselon_i"	=> $c_eselon_i,
					"n_eselon_ii"	=> $n_eselon_ii,
					"c_eselon_ii"	=> $c_eselon_ii,					
					"c_eselon_ii_1"	=> $c_eselon_ii_1,
					"n_eselon_iii"	=> $n_eselon_iii,
					"c_eselon_ii_iii"	=> $c_eselon_ii_iii,
					"c_eselon_iii"	=> $c_eselon_iii);
					//var_dump($paramInput);
			$db->update('sdm.tm_formasiformulir8',$paramInput, $where);
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
	
	public function hapusformulir8(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$id = $dataMasukan['id'];			
			$where[] = "id = '".$id."'";
			
			//var_dump($where);
			$db->delete('sdm.tm_formasiformulir8', $where);
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