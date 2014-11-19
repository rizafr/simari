<?php
class Sdm_DiklatPenjenjangan_Service {
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

 	public function getPelatihanList($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
			
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT  id,i_peg_nip,c_penjenjangan,q_angkatan,q_tahun,q_lama,n_penyelenggara,
										q_peringkat,c_kualifikasi,i_sertifikat,to_char(d_sertifikat,'dd-mm-yyyy') as d_sertifikat,
										n_pejabat,i_entry,d_entry
										FROM sdm.tm_pelatihan_penjenjangan where 1=1 $cari  order by c_penjenjangan asc");	
										
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
						$c_penjenjangan=(string)$result[$j]->c_penjenjangan;
						if($c_penjenjangan) $n_penjenjangan=$db->fetchOne("select n_jenjang  from sdm.tr_diklat_penjenjangan where c_jenjang='$c_penjenjangan'");
						$c_kualifikasi=trim((string)$result[$j]->c_kualifikasi);
						if($c_kualifikasi) $n_kualifikasi=$db->fetchOne("select n_kualifikasi  from sdm.tr_diklat_kualifikasi where c_kualifikasi='$c_kualifikasi'");
						
						$data[$j] = array("id"=>(string)$result[$j]->id,
										"i_peg_nip"=>(string)$result[$j]->i_peg_nip,
										"c_penjenjangan"=>(string)$result[$j]->c_penjenjangan,
										"n_penjenjangan"=>$n_penjenjangan,
										"c_kualifikasi"=>(string)$result[$j]->c_kualifikasi,
										"n_kualifikasi"=>$n_kualifikasi,
										"q_angkatan"=>(string)$result[$j]->q_angkatan,
										"q_tahun"=>(string)$result[$j]->q_tahun,
										"q_lama"=>(string)$result[$j]->q_lama,
										"q_peringkat"=>(string)$result[$j]->q_peringkat,
										"n_penyelenggara"=>(string)$result[$j]->n_penyelenggara,
										"i_sertifikat"=>(string)$result[$j]->i_sertifikat,
										"d_sertifikat"=>(string)$result[$j]->d_sertifikat,
										"n_pejabat"=>(string)$result[$j]->n_pejabat,
										"i_entry"=>(string)$result[$j]->i_entry,
										"d_entry"=>(string)$result[$j]->d_entry);									
					}										
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	public function getPelatihanList1($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
			
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT  id,i_peg_nip,c_penjenjangan,q_angkatan,q_tahun,q_lama,n_penyelenggara,
										q_peringkat,c_kualifikasi,i_sertifikat,to_char(d_sertifikat,'dd-mm-yyyy') as d_sertifikat,
										n_pejabat,i_entry,d_entry
										FROM sdm.tm_pelatihan_penjenjangan where 1=1 $cari  order by c_penjenjangan asc limit 1 offset 0");	
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
						$c_penjenjangan=(string)$result[$j]->c_penjenjangan;
						//$n_penjenjangan=$db->fetchOne("select n_jenjang  from sdm.tr_diklat_penjenjangan where c_jenjang='$c_penjenjangan'");
						$c_kualifikasi=trim((string)$result[$j]->c_kualifikasi);
						//$n_kualifikasi=$db->fetchOne("select n_kualifikasi  from sdm.tr_diklat_kualifikasi where c_kualifikasi='$c_kualifikasi'");
						
						$data[$j] = array("id"=>(string)$result[$j]->id,
										"i_peg_nip"=>(string)$result[$j]->i_peg_nip,
										"c_penjenjangan"=>(string)$result[$j]->c_penjenjangan,
										"n_penjenjangan"=>$n_penjenjangan,
										"c_kualifikasi"=>(string)$result[$j]->c_kualifikasi,
										"n_kualifikasi"=>$n_kualifikasi,
										"q_angkatan"=>(string)$result[$j]->q_angkatan,
										"q_tahun"=>(string)$result[$j]->q_tahun,
										"q_lama"=>(string)$result[$j]->q_lama,
										"q_peringkat"=>(string)$result[$j]->q_peringkat,
										"n_penyelenggara"=>(string)$result[$j]->n_penyelenggara,
										"i_sertifikat"=>(string)$result[$j]->i_sertifikat,
										"d_sertifikat"=>(string)$result[$j]->d_sertifikat,
										"n_pejabat"=>(string)$result[$j]->n_pejabat,
										"i_entry"=>(string)$result[$j]->i_entry,
										"d_entry"=>(string)$result[$j]->d_entry);									
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
										"c_penjenjangan"=>$data['c_penjenjangan'],
										"c_kualifikasi"=>$data['c_kualifikasi'],
										"q_angkatan"=>$data['q_angkatan'],
										"q_tahun"=>$data['q_tahun'],
										"q_lama"=>$data['q_lama'],
										"q_peringkat"=>$data['q_peringkat'],
										"n_penyelenggara"=>$data['n_penyelenggara'],
										"i_sertifikat"=>$data['i_sertifikat'],
										"d_sertifikat"=>$data['d_sertifikat'],
										"n_pejabat"=>$data['n_pejabat'],
										"i_entry"=>$data['i_entry'],
										"d_entry"=>$data['d_entry']);											
							
		if ($par=='insert'){$db->insert('sdm.tm_pelatihan_penjenjangan',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tm_pelatihan_penjenjangan',$maintain_data, "i_peg_nip = '".trim($data['i_peg_nip'])."'  and id = '".trim($data['id'])."' ");}	 
		if ($par=='delete'){$db->delete('sdm.tm_pelatihan_penjenjangan', " id = '".trim($data['id'])."' ");}
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
		public function updateTmPegawaiPenjenjangan(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	   
	     $db->beginTransaction();
	     $maintain_data = array("c_penjenjangan"=>$data['c_penjenjangan'],
								"q_angkatan"=>$data['q_angkatan'],
								"q_tahun"=>$data['q_tahun'],"c_kualifikasi"=>$data['c_kualifikasi']);
			
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