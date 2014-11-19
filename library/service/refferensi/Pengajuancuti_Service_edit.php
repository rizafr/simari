<?php
class pengajuancuti_Service {
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
	
	
	public function getJenisCuti()
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
				$result = $db->fetchAll("select c_cuti, trim(n_cuti) as n_cuti from sdm.tr_cuti_pegawai order by n_cuti");
				$jmlResult = count($result);
				for ($j = 0;$j < $jmlResult; $j++)
				{
					$data[$j] = array("n_cuti"=>(string)$result[$j]->n_cuti,
								"c_cuti"=>(string)$result[$j]->c_cuti);
				}
			return $data;
		} catch (Exception $e)
		{
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	
	public function getTrUnitKerja($cari) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$sql = "select trim(c_eselon_i) as c_eselon_i,trim(c_eselon_ii) as c_eselon_ii,trim(c_eselon_iii) as c_eselon_iii,trim(c_eselon_iv) as c_eselon_iv,trim(c_eselon_v) as c_eselon_v,trim(n_unitkerja) as n_unitkerja ,trim(c_bidang) as c_bidang ,trim(c_parent) as c_parent ,trim(c_child) as c_child ,trim(c_satker) as c_satker  from sdm.tr_unitkerja where 1=1 $cari";
		//echo "$sql<br>";
		//$result = $db->fetchAll("select c_eselon_i,c_eselon_ii,c_eselon_iii,c_eselon_iv,c_eselon_v,trim(n_unitkerja) as n_unitkerja,c_bidang,c_parent,c_child,c_satker from sdm.tr_unitkerja where 1=1 $cari");
				$result = $db->fetchAll("select trim(c_eselon_i) as c_eselon_i,trim(c_eselon_ii) as c_eselon_ii,trim(c_eselon_iii) as c_eselon_iii,trim(c_eselon_iv) as c_eselon_iv,trim(c_eselon_v) as c_eselon_v,trim(n_unitkerja) as n_unitkerja ,trim(c_bidang) as c_bidang ,trim(c_parent) as c_parent ,trim(c_child) as c_child ,trim(c_satker) as c_satker  from sdm.tr_unitkerja where 1=1 $cari");
		$jmlResult = count($result);
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function getPegawaiList($cari,$currentPage,$numToDisplay) 
	{
	
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				if(($currentPage==0) && ($numToDisplay==0))
			{
				$data = $db->fetchOne("select count(*) from  sdm.tm_pegawai where 1=1 $cari");
	
			}
			else		
			{	
				$xLimit=$numToDisplay;
				$xOffset=($currentPage-1)*$numToDisplay;				
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT i_peg_nip,i_peg_nrp,i_peg_nip_new,n_peg,
								c_lokasi_unitkerja,c_eselon_i,c_eselon_ii,c_eselon_iii,c_eselon_iv,c_eselon_v
								FROM sdm.tm_pegawai where 1=1 $cari  
								ORDER BY c_golongan ASC,d_tmt_golongan ASC,c_eselon ASC,q_tktfgs,d_tmt_eselon ASC,d_tmt_cpns asc, q_tahun ASC,c_pend ASC,d_pend_akhir ASC,d_peg_lahir ASC
								limit $xLimit offset $xOffset");
									
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 

						$c_eselon_i=trim($result[$j]->c_eselon_i);
						$c_eselon_ii=trim($result[$j]->c_eselon_ii);
						$c_eselon_iii=trim($result[$j]->c_eselon_iii);
						$c_eselon_iv=trim($result[$j]->c_eselon_iv);
						$c_parent=trim($result[$j]->c_parent);
						$c_satker=trim($result[$j]->c_satker);
						$c_lokasi_unitkerja=trim($result[$j]->c_lokasi_unitkerja);
						
						if ($c_eselon_i=='03' || $c_eselon_i=='04' || $c_eselon_i=='05'){							
							$c_lokasi_unitkerja="3";
							$ceselon2 = $db->fetchOne(" SELECT distinct(c_eselon_ii) FROM sdm.tr_unitkerja WHERE c_satker='$c_satker' ");
							if (!$ceselon2){$ceselon2=$c_eselon_ii;}
							$neselon1 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_tkt_esl='1' ");
							$neselon3 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2' and c_eselon_iii='$c_eselon_iii' and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child <> '00' and c_parent ='$c_parent'  and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

							
							if ($neselon3){
								$ceseloniix = $db->fetchOne(" SELECT c_eselon_ii FROM sdm.tr_unitkerja WHERE c_level ='2' and c_eselon_i='$c_eselon_i' and c_parent='$c_parent' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
								$neselon2 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceseloniix' and c_eselon_iii = '00'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child = '00' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
								
							}
							else{
								$neselon2 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii = '00'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child = '00' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");

							}
							if ($c_satker=='00'){
								$neselon4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2' and c_eselon_iv='$c_eselon_iv' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
							}else{
								$neselon4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2' and c_eselon_iv='$c_eselon_iv' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_satker='$c_satker'");
							}							
						}
						else{
							$c_lokasi_unitkerja="1";
							$neselon1 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_tkt_esl='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
							$neselon2 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");							
							$neselon3 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
							$neselon4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
							$neselon5 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_eselon_v='$c_eselon_v' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
						}
				$eselon="";						
				if ($neselon5){$eselon=$neselon5.", ";}
				else{$eselon=$eselon.$neselon5;}
				if ($neselon4){$eselon=$eselon.$neselon4.", ";}
				else{$eselon=$eselon.$neselon4;}
				if ($neselon3){$eselon=$eselon.$neselon3.", ";}
				else{$eselon=$eselon.$neselon3;}
				if ($neselon2){$eselon=$eselon.$neselon2.", ";}
				else{$eselon=$eselon.$neselon2;}
				if ($neselon1){$eselon=$eselon.$neselon1;}
				else{$eselon=$eselon.$neselon1;}

				$n_eselon=$eselon;	
				$data[$j] = array("i_peg_nip"=>(string)$result[$j]->i_peg_nip,
						"i_peg_nip_new"=>(string)$result[$j]->i_peg_nip_new,
						"n_peg"=>(string)$result[$j]->n_peg,
						"n_eselon"=>$n_eselon);	
	}
			}							
			return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function getLokasi($cari) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("select c_lokasi ,n_lokasi  from sdm.tr_lokasi where 1=1 $cari order by c_lokasi asc  ");				 
		$jmlResult = count($result);
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function getTrUnitKerjaPngl($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
			
					$result = $db->fetchAll("select distinct(c_eselon_i) as c_eselon_i from sdm.tr_unitkerja where 1=1 $cari");
		//echo "select distinct(c_eselon_i) as c_eselon_i from sdm.tr_unitkerja where 1=1 $cari";								
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$c_eselon_i=trim($result[$j]->c_eselon_i);
						$n_unitkerja = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_tkt_esl='1'");
						$data[$j] = array("c_eselon_i"=>(string)$result[$j]->c_eselon_i,
							"n_unitkerja"=>$n_unitkerja);
					}
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function tambahpengajuancuti(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$i_peg_nip = $dataMasukan['i_peg_nip'];
			$c_jenis_cuti = $dataMasukan['c_jenis_cuti'];
			$d_cuti_mulai = date('Y-m-d', strtotime($dataMasukan['d_cuti_mulai']));
			$d_cuti_akhir = date('Y-m-d', strtotime($dataMasukan['d_cuti_akhir']));
			$i_surat_cuti = $dataMasukan['i_surat_cuti'];
			$i_sisa_cuti = $dataMasukan['i_sisa_cuti'];
			$q_lama_cuti = $dataMasukan['q_lama_cuti'];
			$a_alamat_cuti = $dataMasukan['a_alamat_cuti'];
			$a_cuti_rt = $dataMasukan['a_cuti_rt'];
			$a_cuti_rw = $dataMasukan['a_cuti_rw'];
			$a_cuti_propinsi = $dataMasukan['a_cuti_propinsi'];
			$a_cuti_kota = $dataMasukan['a_cuti_kota'];
			$a_cuti_kodepos = $dataMasukan['a_cuti_kodepos'];
			$q_cuti_telponrumah = $dataMasukan['q_cuti_telponrumah'];
			$q_cuti_telponhp = $dataMasukan['q_cuti_telponhp'];
			$e_cuti_alasan = $dataMasukan['e_cuti_alasan'];
			$i_entry = $dataMasukan['i_entry'];
			$i_peg_nip_atasan = $dataMasukan['i_peg_nip_atasan'];
			
			
			if($dataMasukan['q_lama_cuti'] == ""){
			$q_lama_cuti = 0;
			}else{
			$q_lama_cuti = $dataMasukan['q_lama_cuti'];
			}
			
			$paramInput = array("i_peg_nip"	=> $i_peg_nip,
					"c_jenis_cuti"	=> $c_jenis_cuti,
					"d_cuti_mulai"	=> $d_cuti_mulai,
					"d_cuti_akhir"	=> $d_cuti_akhir,
					"i_surat_cuti"	=> $i_surat_cuti,
					"i_sisa_cuti"	=> $i_sisa_cuti,
					"q_lama_cuti"	=> $q_lama_cuti,
					"a_alamat_cuti"	=> $a_alamat_cuti,					
					"a_cuti_rt"	=> $a_cuti_rt,
					"a_cuti_rw"	=> $a_cuti_rw,
					"a_cuti_propinsi"	=> $a_cuti_propinsi,
					"a_cuti_kota"	=> $a_cuti_kota,
					"a_cuti_kodepos"	=> $a_cuti_kodepos,					
					"q_cuti_telponrumah"	=> $q_cuti_telponrumah,
					"q_cuti_telponhp"	=> $q_cuti_telponhp,
					"e_cuti_alasan"	=> $e_cuti_alasan,
					"i_entry"	=> $i_entry,
					"d_entry"	=> date('Y-m-d'),
					"i_peg_nip_atasan"	=> $i_peg_nip_atasan);
					//var_dump($paramInput);
			$db->insert('sdm.tm_ajuan_cuti',$paramInput);
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
	
	public function detailPengajuancuti($masukan) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchRow("select i_peg_nip, c_jenis_cuti, d_cuti_mulai, d_cuti_akhir, i_surat_cuti, i_sisa_cuti, q_lama_cuti, a_alamat_cuti,
								a_cuti_rt, a_cuti_rw, a_cuti_propinsi, a_cuti_kota, a_cuti_kodepos, q_cuti_telponrumah, q_cuti_telponhp, e_cuti_alasan,
								i_peg_nip_atasan
							from sdm.tm_ajuan_cuti
							where i_peg_nip  = '".$masukan['i_peg_nip']."'");
							
							
				$jmlResult = count($result);
				$data = array("i_peg_nip"=>(string)$result->i_peg_nip,	
					      "c_jenis_cuti"=>(string)$result->c_jenis_cuti,
					      "d_cuti_mulai"=>(string)$result->d_cuti_mulai,
					      "d_cuti_akhir"=>(string)$result->d_cuti_akhir,
					      "i_surat_cuti"=>(string)$result->i_surat_cuti,
					      "i_sisa_cuti"=>(string)$result->i_sisa_cuti,
					      "q_lama_cuti"=>(string)$result->q_lama_cuti,
					      "a_alamat_cuti"=>(string)$result->a_alamat_cuti,
						"a_cuti_rt"=>(string)$result->a_cuti_rt,
						"a_cuti_rw"=>(string)$result->a_cuti_rw,
						"a_cuti_propinsi"=>(string)$result->a_cuti_propinsi,
						"a_cuti_kota"=>(string)$result->a_cuti_kota,
						"a_cuti_kodepos"=>(string)$result->a_cuti_kodepos,
						"q_cuti_telponrumah"=>(string)$result->q_cuti_telponrumah,
						"q_cuti_telponhp"=>(string)$result->q_cuti_telponhp,
						"e_cuti_alasan"=>(string)$result->e_cuti_alasan,
						"i_peg_nip_atasan"=>(string)$result->i_peg_nip_atasan);
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
		
	public function ubahpengajuancuti(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$i_peg_nip = $dataMasukan['i_peg_nip'];
			$c_jenis_cuti = $dataMasukan['c_jenis_cuti'];
			$d_cuti_mulai = date('Y-m-d', strtotime($dataMasukan['d_cuti_mulai']));
			$d_cuti_akhir = date('Y-m-d', strtotime($dataMasukan['d_cuti_akhir']));
			$i_surat_cuti = $dataMasukan['i_surat_cuti'];
			$i_sisa_cuti = $dataMasukan['i_sisa_cuti'];
			$q_lama_cuti = $dataMasukan['q_lama_cuti'];
			$a_alamat_cuti = $dataMasukan['a_alamat_cuti'];
			$a_cuti_rt = $dataMasukan['a_cuti_rt'];
			$a_cuti_rw = $dataMasukan['a_cuti_rw'];
			$a_cuti_propinsi = $dataMasukan['a_cuti_propinsi'];
			$a_cuti_kota = $dataMasukan['a_cuti_kota'];
			$a_cuti_kodepos = $dataMasukan['a_cuti_kodepos'];
			$q_cuti_telponrumah = $dataMasukan['q_cuti_telponrumah'];
			$q_cuti_telponhp = $dataMasukan['q_cuti_telponhp'];
			$e_cuti_alasan = $dataMasukan['e_cuti_alasan'];
			$i_entry = $dataMasukan['i_entry'];
			$i_peg_nip_atasan = $dataMasukan['i_peg_nip_atasan'];
			
			
			if($dataMasukan['q_lama_cuti'] == ""){
			$q_lama_cuti = 0;
			}else{
			$q_lama_cuti = $dataMasukan['q_lama_cuti'];
			}
			
			$where[] = "i_peg_nip = '".$i_peg_nip."'";
			$paramInput = array("c_jenis_cuti"	=> $c_jenis_cuti,
					"d_cuti_mulai"	=> $d_cuti_mulai,
					"d_cuti_akhir"	=> $d_cuti_akhir,
					"i_surat_cuti"	=> $i_surat_cuti,
					"i_sisa_cuti"	=> $i_sisa_cuti,
					"q_lama_cuti"	=> $q_lama_cuti,
					"a_alamat_cuti"	=> $a_alamat_cuti,					
					"a_cuti_rt"	=> $a_cuti_rt,
					"a_cuti_rw"	=> $a_cuti_rw,
					"a_cuti_propinsi"	=> $a_cuti_propinsi,
					"a_cuti_kota"	=> $a_cuti_kota,
					"a_cuti_kodepos"	=> $a_cuti_kodepos,					
					"q_cuti_telponrumah"	=> $q_cuti_telponrumah,
					"q_cuti_telponhp"	=> $q_cuti_telponhp,
					"e_cuti_alasan"	=> $e_cuti_alasan,
					"i_entry"	=> $i_entry,
					"d_entry"	=> date('Y-m-d'),
					"i_peg_nip_atasan"	=> $i_peg_nip_atasan);
					//var_dump($paramInput);
			$db->update('sdm.tm_ajuan_cuti',$paramInput, $where);
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
	
	public function hapuspengajuancuti(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$i_peg_nip = $dataMasukan['i_peg_nip'];			
			$where[] = "i_peg_nip = '".$i_peg_nip."'";
			
			//var_dump($where);
			$db->delete('sdm.tm_ajuan_cuti', $where);
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