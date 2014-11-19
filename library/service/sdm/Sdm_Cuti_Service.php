<?php
class Sdm_Cuti_Service {
    private static $instance;
   
    // A private constructor; prevents direct creation of object
    private function __construct() {
       //echo 'I am constructed';
    }

    // The singleton method
    public static function getInstance() {
       if (!isset(self::$instance)) {
           $c = __CLASS__;
           self::$instance = new $c;
       }

       return self::$instance;
    }
	

 	public function getDataCuti($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{		
				
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll(" Select a.i_peg_nip,b.n_peg,b.c_golongan,b.c_jabatan,c_jenis_cuti,
											to_char(d_cuti_mulai,'dd-mm-yyyy') as d_cuti_mulai,c_status_kepegawaian,
											to_char(d_cuti_akhir,'dd-mm-yyyy') as d_cuti_akhir,a_alamat_cuti,
											a_cuti_rt,a_cuti_rw,a_cuti_propinsi,a_cuti_kota,a_cuti_kodepos,q_cuti_telponrumah,
											q_cuti_telponhp,e_cuti_alasan,q_lama_cuti
											FROM sdm.tm_ajuan_cuti a, sdm.tm_pegawai b where a.i_peg_nip=b.i_peg_nip $cari");
									
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 

						$c_golongan=trim($result[$j]->c_golongan);
						$c_status_kepegawaian=trim($result[$j]->c_status_kepegawaian);
						$n_pangkat = $db->fetchOne(" SELECT n_peg_pangkat FROM sdm.tr_golongan_pangkat WHERE c_peg_golongan='$c_golongan' and c_peg_tipegolongan='$c_status_kepegawaian'");
		
						$data[$j] = array("i_peg_nip"=>(string)$result[$j]->i_peg_nip,						
									"n_peg"=>(string)$result[$j]->n_peg,
									"c_golongan"=>(string)$result[$j]->c_golongan,
									"n_pangkat"=>$n_pangkat,
									"n_jabatan"=>$n_jabatan,
									"c_jenis_cuti"=>(string)$result[$j]->c_jenis_cuti,
									"d_cuti_mulai"=>(string)$result[$j]->d_cuti_mulai,
									"d_cuti_akhir"=>(string)$result[$j]->d_cuti_akhir,
									"a_alamat_cuti"=>(string)$result[$j]->a_alamat_cuti,
									"a_cuti_rt"=>(string)$result[$j]->a_cuti_rt,
									"a_cuti_rw"=>(string)$result[$j]->a_cuti_rw,
									"a_cuti_propinsi"=>(string)$result[$j]->a_cuti_propinsi,
									"a_cuti_kota"=>(string)$result[$j]->a_cuti_kota,
									"a_cuti_kodepos"=>(string)$result[$j]->a_cuti_kodepos,
									"q_cuti_telponrumah"=>(string)$result[$j]->q_cuti_telponrumah,
									"q_cuti_telponhp"=>(string)$result[$j]->q_cuti_telponhp,
									"q_lama_cuti"=>(string)$result[$j]->q_lama_cuti,
									"e_cuti_alasan"=>(string)$result[$j]->e_cuti_alasan);	
					}
									
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}

	public function maintainData(array $data,$par) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
  
	     $maintain_data = array("i_peg_nip"=>$data['i_peg_nip'],
								"c_jenis_cuti"=>$data['c_jenis_cuti'],
								"d_cuti_mulai"=>$data['d_cuti_mulai'],
								"d_cuti_akhir"=>$data['d_cuti_akhir'],
								"a_alamat_cuti"=>$data['a_alamat_cuti'],
								"a_cuti_rt"=>$data['a_cuti_rt'],
								"a_cuti_rw"=>$data['a_cuti_rw'],
								"a_cuti_propinsi"=>$data['a_cuti_propinsi'],
								"a_cuti_kota"=>$data['a_cuti_kota'],
								"a_cuti_kodepos"=>$data['a_cuti_kodepos'],
								"q_cuti_telponrumah"=>$data['q_cuti_telponrumah'],
								"q_cuti_telponhp"=>$data['q_cuti_telponhp'],
								"e_cuti_alasan"=>$data['e_cuti_alasan'],
								"q_lama_cuti"=>$data['q_lama_cuti'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));
		if ($par=='insert'){$db->insert('sdm.tm_ajuan_cuti',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tm_ajuan_cuti',$maintain_data, "i_peg_nip = '".trim($data['i_peg_nip'])."'  and d_cuti_mulai = '".trim($data['d_cuti_mulai'])."' and c_jenis_cuti = '".trim($data['c_jenis_cuti'])."'");}	 
		if ($par=='delete'){$db->delete('sdm.tm_ajuan_cuti', "i_peg_nip = '".trim($data['i_peg_nip'])."' and d_cuti_mulai = '".trim($data['d_cuti_mulai'])."' and c_jenis_cuti = '".trim($data['c_jenis_cuti'])."' ");}
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}


	public function getCariDataCuti($cari,$currentPage, $numToDisplay) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				if(($currentPage==0) && ($numToDisplay==0))
			{
				$data = $db->fetchOne("select count(*) from  sdm.tm_ajuan_cuti a,sdm.tm_pegawai b where a.i_peg_nip=b.i_peg_nip $cari");
	
			}
			else		
			{	
				$xLimit=$numToDisplay;
				$xOffset=($currentPage-1)*$numToDisplay;				
				
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll(" Select a.i_peg_nip,b.n_peg,b.c_golongan,b.c_jabatan,c_peg_jeniskelamin,c_jenis_cuti,
											to_char(d_cuti_mulai,'dd-mm-yyyy') as d_cuti_mulai,c_status_kepegawaian,
											to_char(d_cuti_akhir,'dd-mm-yyyy') as d_cuti_akhir,a_alamat_cuti,
											a_cuti_rt,a_cuti_rw,a_cuti_propinsi,a_cuti_kota,a_cuti_kodepos,q_cuti_telponrumah,
											q_cuti_telponhp,e_cuti_alasan,q_lama_cuti
											FROM sdm.tm_ajuan_cuti a, sdm.tm_pegawai b where a.i_peg_nip=b.i_peg_nip $cari 
											order by a.i_peg_nip asc,d_cuti_mulai asc limit $xLimit offset $xOffset ");
									
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 

						$c_status_kepegawaian=trim($result[$j]->c_status_kepegawaian);
						$c_jenis_cuti=trim($result[$j]->c_jenis_cuti);
						if ($c_jenis_cuti=='B'){$n_jenis_cuti="Cuti Besar";}
						if ($c_jenis_cuti=='H'){$n_jenis_cuti="Cuti Bersalin";}
						if ($c_jenis_cuti=='P'){$n_jenis_cuti="Alasan Penting";}
						if ($c_jenis_cuti=='S'){$n_jenis_cuti="Cuti Sakit";}
						if ($c_jenis_cuti=='T'){$n_jenis_cuti="Cuti Tahunan";}
						
						$c_propinsi=trim($result[$j]->a_cuti_propinsi);
						$n_propinsi = $db->fetchOne(" SELECT n_propinsi FROM sdm.tr_propinsi WHERE c_propinsi='$c_propinsi'");
						$c_kabupaten=trim($result[$j]->a_cuti_kota);
						$n_kabupaten = $db->fetchOne(" SELECT n_kabupaten FROM sdm.tr_kabupaten WHERE c_kabupaten='$c_kabupaten' and c_propinsi='$c_propinsi'");
						
						$c_golongan=trim($result[$j]->c_golongan);
						$n_pangkat = $db->fetchOne(" SELECT n_peg_pangkat FROM sdm.tr_golongan_pangkat WHERE c_peg_golongan='$c_golongan' and c_peg_tipegolongan='$c_status_kepegawaian'");
						
						$c_peg_jeniskelamin=trim($result[$j]->c_peg_jeniskelamin);
						if ($c_peg_jeniskelamin=='L'){$n_peg_jeniskelamin='Laki-laki';}
						if ($c_peg_jeniskelamin=='P'){$n_peg_jeniskelamin='Perempuan';}
						$data[$j] = array("i_peg_nip"=>(string)$result[$j]->i_peg_nip,
									"n_peg"=>(string)$result[$j]->n_peg,
									"n_peg_jeniskelamin"=>$n_peg_jeniskelamin,									
									"c_golongan"=>(string)$result[$j]->c_golongan,
									"n_pangkat"=>$n_pangkat,
									"n_jabatan"=>$n_jabatan,
									"c_jenis_cuti"=>(string)$result[$j]->c_jenis_cuti,
									"n_jenis_cuti"=>$n_jenis_cuti,
									"d_cuti_mulai"=>(string)$result[$j]->d_cuti_mulai,
									"d_cuti_akhir"=>(string)$result[$j]->d_cuti_akhir,
									"a_alamat_cuti"=>(string)$result[$j]->a_alamat_cuti,
									"a_cuti_rt"=>(string)$result[$j]->a_cuti_rt,
									"a_cuti_rw"=>(string)$result[$j]->a_cuti_rw,
									"a_cuti_propinsi"=>trim((string)$result[$j]->a_cuti_propinsi),
									"n_propinsi"=>$n_propinsi,
									"a_cuti_kota"=>(string)$result[$j]->a_cuti_kota,
									"n_kabupaten"=>$n_kabupaten,
									"a_cuti_kodepos"=>(string)$result[$j]->a_cuti_kodepos,
									"q_cuti_telponrumah"=>(string)$result[$j]->q_cuti_telponrumah,
									"q_cuti_telponhp"=>(string)$result[$j]->q_cuti_telponhp,
									"q_lama_cuti"=>(string)$result[$j]->q_lama_cuti,
									"e_cuti_alasan"=>(string)$result[$j]->e_cuti_alasan);	
					}
			}		
									
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}	
}
?>