<?php
class Sdm_Pendidikan_Service {
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

 	public function getPendidikanList($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT  i_peg_nip,c_pend_jenis,c_pend,n_pend_lembaga,n_pend_jurusan,
										a_pend_alamat,n_pend_kepsek,d_pend_mulai,d_pend_akhir,
										i_pend_ipk,i_pend_ijazah,to_char(d_pend_ijazah,'dd-mm-yyyy') as d_pend_ijazah,c_pend_sumberdana,
										e_pend_skripsi,e_keterangan,c_pend_status,i_entry,d_entry
										FROM sdm.tm_pendidikan where 1=1 $cari  order by c_pend asc");	
										
										
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 

						$namaPendidikan = $db->fetchCol('SELECT n_pend FROM sdm.tr_pendidikan WHERE c_pend = ?',$result[$j]->c_pend);
						$c_pend_sumberdana=$result[$j]->c_pend_sumberdana;
						if ($c_pend_sumberdana=='B'){$n_pend_sumberdana='Beasiswa';}
						if ($c_pend_sumberdana=='NB'){$n_pend_sumberdana='Non Beasiswa';}
						$data[$j] = array("i_peg_nip"  =>(string)$result[$j]->i_peg_nip,
										"a_pend_alamat"=>(string)$result[$j]->a_pend_alamat,
										"c_pend"=>(string)$result[$j]->c_pend,
										"c_pend_sumberdana"=>(string)$result[$j]->c_pend_sumberdana,
										"d_pend_akhir"=>(string)$result[$j]->d_pend_akhir,
										"d_pend_ijazah"=>(string)$result[$j]->d_pend_ijazah,
										"d_pend_mulai"=>(string)$result[$j]->d_pend_mulai,
										"e_keterangan"=>(string)$result[$j]->e_keterangan,
										"e_pend_skripsi"=>(string)$result[$j]->e_pend_skripsi,
										"i_pend_ipk"=>(string)$result[$j]->i_pend_ipk,
										"i_pend_ijazah"=>(string)$result[$j]->i_pend_ijazah,
										"n_pend_jurusan"=>(string)$result[$j]->n_pend_jurusan,
										"n_pend_kepsek"=>(string)$result[$j]->n_pend_kepsek,
										"n_pend_lembaga"=>(string)$result[$j]->n_pend_lembaga,
										"n_pend"=>$namaPendidikan[0],
										"n_pend_sumberdana"=>$n_pend_sumberdana);									
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
							"c_pend_jenis"=>$data['c_pend_jenis'],
							"a_pend_alamat"=>$data['a_pend_alamat'],
							"c_pend"=>$data['c_pend'],
							"c_pend_sumberdana"=>$data['c_pend_sumberdana'],
							"d_pend_akhir"=>$data['d_pend_akhir'],
							"d_pend_ijazah"=>$data['d_pend_ijazah'],
							"d_pend_mulai"=>$data['d_pend_mulai'],
							"e_keterangan"=>$data['e_keterangan'],
							"e_pend_skripsi"=>$data['e_pend_skripsi'],
							"i_pend_ipk"=>$data['i_pend_ipk'],
							"i_pend_ijazah"=>$data['i_pend_ijazah'],
							"n_pend_jurusan"=>$data['n_pend_jurusan'],
							"n_pend_kepsek"=>$data['n_pend_kepsek'],
							"n_pend_lembaga"=>$data['n_pend_lembaga'],
							"i_entry"=>$data['i_entry'],
							"d_entry"=>date("Y-m-d"));
		if ($par=='insert'){$db->insert('sdm.tm_pendidikan',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tm_pendidikan',$maintain_data, "i_peg_nip = '".trim($data['i_peg_nip'])."'  and c_pend = '".trim($data['c_pend'])."' ");}	 
		if ($par=='delete'){$db->delete('sdm.tm_pendidikan', "i_peg_nip = '".trim($data['i_peg_nip'])."'  and c_pend = '".trim($data['c_pend'])."' ");}
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function updateTmPegawai(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	   
	     $db->beginTransaction();
	     $maintain_data = array("c_pend_jenis"=>$data['c_pend_jenis'],
								"c_pend"=>$data['c_pend'],
								"d_pend_mulai"=>$data['d_pend_mulai'],
								"d_pend_akhir"=>$data['d_pend_akhir'],
								"n_pend_jurusan"=>$data['n_pend_jurusan']);
			
		$db->update('sdm.tm_pegawai',$maintain_data, "i_peg_nip = '".trim($data['i_peg_nip'])."'");
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
}
?>