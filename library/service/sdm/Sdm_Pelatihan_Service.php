<?php
class Sdm_Pelatihan_Service {
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
					$result = $db->fetchAll("SELECT  id,i_peg_nip,c_pelatihan,n_pelatihan,to_char(d_mulai_pelatihan,'dd-mm-yyyy') as d_mulai_pelatihan,
										to_char(d_akhir_pelatihan,'dd-mm-yyyy') as d_akhir_pelatihan,a_tempat_pelatihan,n_dok_pelatihan,
										n_penyelenggara,i_sertifikat,to_char(d_sertifikat,'dd-mm-yyyy') as d_sertifikat,e_keterangan,i_entry,d_entry
										FROM sdm.tm_pelatihan where 1=1 $cari  order by d_mulai_pelatihan asc");	
								
										
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
						$data[$j] = array("id"=>(string)$result[$j]->id,
									"i_peg_nip"=>(string)$result[$j]->i_peg_nip,
										"c_pelatihan"=>(string)$result[$j]->c_pelatihan,
										"n_pelatihan"=>(string)$result[$j]->n_pelatihan,
										"d_mulai_pelatihan"=>(string)$result[$j]->d_mulai_pelatihan,
										"d_akhir_pelatihan"=>(string)$result[$j]->d_akhir_pelatihan,
										"a_tempat_pelatihan"=>(string)$result[$j]->a_tempat_pelatihan,
										"n_dok_pelatihan"=>(string)$result[$j]->n_dok_pelatihan,
										"n_penyelenggara"=>(string)$result[$j]->n_penyelenggara,
										"i_sertifikat"=>(string)$result[$j]->i_sertifikat,
										"d_sertifikat"=>(string)$result[$j]->d_sertifikat,
										"e_keterangan"=>(string)$result[$j]->e_keterangan,
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
	
	public function maintainDataFungsional(array $data,$par) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();

	     $maintain_data = array("i_peg_nip"=>$data['i_peg_nip'],
							"c_jns_fungsional"=>$data['c_jns_fungsional'],
							"c_jns_kelompok"=>$data['c_jns_kelompok'],
							"c_kel_pelatihan"=>$data['c_kel_pelatihan'],
							"c_nama_kelompok"=>$data['c_nama_kelompok'],
							"q_pelatihan"=>$data['q_pelatihan'],
							"d_sertifikat"=>$data['d_sertifikat'],
							"i_sertifikat"=>$data['i_sertifikat'],	
							"n_pejabat"=>$data['n_pejabat'],	
							"n_penyelenggara"=>$data['n_penyelenggara'],
							"i_entry"=>$data['i_entry'],
							"d_entry"=>$data['d_entry']);

											
							
		if ($par=='insert'){$db->insert('sdm.tm_pelatihan_fungsional',$maintain_data);}
		//if ($par=='update'){$db->update('sdm.tm_pelatihan_fungsional',$maintain_data, "i_peg_nip = '".trim($data['i_peg_nip'])."'  and c_jns_fungsional = '".trim($data['c_jns_fungsional'])."' ");}	 
		//if ($par=='delete'){$db->delete('sdm.tm_pelatihan_fungsional', "i_peg_nip = '".trim($data['i_peg_nip'])."'  and c_jns_fungsional = '".trim($data['c_jns_fungsional'])."' ");}
		if ($par=='update'){$db->update('sdm.tm_pelatihan_fungsional',$maintain_data, "id = '".trim($data['id'])."' ");}	 
		if ($par=='delete'){$db->delete('sdm.tm_pelatihan_fungsional', "id = '".trim($data['id'])."' ");}
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