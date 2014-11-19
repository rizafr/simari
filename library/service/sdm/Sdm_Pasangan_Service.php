<?php
class Sdm_Pasangan_Service {
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

 	public function getPasanganList($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{

				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT id,i_peg_nip,c_pasangan,q_pasangan,c_pekerjaan,i_nip_pasangan,n_nama,
								a_tempat_lahir,to_char(d_tanggal_lahir,'dd-mm-yyyy') as d_tanggal_lahir,
								c_jns_pekerjaan,
								to_char(d_tanggal_nikah,'dd-mm-yyyy') as d_tanggal_nikah,i_karis,e_keterangan,c_tunjangan,i_entry,d_entry
								FROM sdm.tm_pasangan where 1=1 $cari  order by c_pasangan asc");		
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 		
						
						$n_pekerjaan = $db->FetchOne('SELECT n_pekerjaan FROM sdm.tr_pekerjaan WHERE c_pekerjaan = ?',trim($result[$j]->c_pekerjaan));
						$n_pasangan="-";
						if (trim($result[$j]->c_pasangan)=="I") {$n_pasangan="Istri";}
						if (trim($result[$j]->c_pasangan)=="S") {$n_pasangan="Suami";}
						$data[$j] = array("id"=>(string)$result[$j]->id,
						"i_peg_nip"=>(string)$result[$j]->i_peg_nip,
										"c_pasangan"=>(string)$result[$j]->c_pasangan,
										"n_pasangan"=>$n_pasangan,
										"q_pasangan"=>(string)$result[$j]->q_pasangan,
										"c_pekerjaan"=>(string)$result[$j]->c_pekerjaan,
										"c_jns_pekerjaan"=>(string)$result[$j]->c_jns_pekerjaan,										
										"n_pekerjaan"=>$n_pekerjaan,
										"i_nip_pasangan"=>(string)$result[$j]->i_nip_pasangan,
										"n_nama"=>(string)$result[$j]->n_nama,
										"a_tempat_lahir"=>(string)$result[$j]->a_tempat_lahir,
										"d_tanggal_lahir"=>(string)$result[$j]->d_tanggal_lahir,
										"d_tanggal_nikah"=>(string)$result[$j]->d_tanggal_nikah,
										"i_karis"=>(string)$result[$j]->i_karis,
										"e_keterangan"=>(string)$result[$j]->e_keterangan,
										"c_tunjangan"=>(string)$result[$j]->c_tunjangan,										
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
									"c_pasangan"=>$data['c_pasangan'],
									"q_pasangan"=>$data['q_pasangan'],
									"c_pekerjaan"=>$data['c_pekerjaan'],
									"c_jns_pekerjaan"=>$data['c_jns_pekerjaan'],									
									"i_nip_pasangan"=>$data['i_nip_pasangan'],
									"n_nama"=>$data['n_nama'],
									"a_tempat_lahir"=>$data['a_tempat_lahir'],
									"d_tanggal_lahir"=>$data['d_tanggal_lahir'],
									"d_tanggal_nikah"=>$data['d_tanggal_nikah'],
									"i_karis"=>$data['i_karis'],
									"e_keterangan"=>$data['e_keterangan'],
									"c_tunjangan"=>$data['c_tunjangan'],									
									"i_entry"=>$data['i_entry'],
									"d_entry"=>date("Y-m-d"));

		if ($par=='insert'){$db->insert('sdm.tm_pasangan',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tm_pasangan',$maintain_data, "id = '".trim($data['id'])."'");}	 
		if ($par=='delete'){$db->delete('sdm.tm_pasangan', "id = '".trim($data['id'])."'");}
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
	     return "Gagal ".$e->getMessage().'<br>';
	   }
	}

	public function updateNipPasangan(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();

	     $maintain_data = array("i_nip_pasangan"=>$data['i_nip_pasangan']);
		$db->update('sdm.tm_pegawai',$maintain_data, "i_peg_nip = '".trim($data['i_peg_nip'])."' ");
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
	     return "Gagal ".$e->getMessage().'<br>';
	   }
	}
	
}
?>