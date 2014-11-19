<?php
class Sdm_DiklatFungsional_Service {
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
					$result = $db->fetchAll("SELECT  id,i_peg_nip,c_jns_fungsional,c_kel_pelatihan,c_jns_kelompok,c_nama_kelompok,
										q_pelatihan,n_penyelenggara,i_sertifikat,to_char(d_sertifikat,'dd-mm-yyyy') as d_sertifikat ,n_pejabat,i_entry,d_entry
										FROM sdm.tm_pelatihan_fungsional where 1=1 $cari  order by d_sertifikat asc");	


  
										
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
						$c_jns_fungsional=(string)$result[$j]->c_jns_fungsional;
						$n_jns_fungsional=$db->fetchOne("select n_fungsional  from sdm.tr_jns_dikfungsional where c_fungsional='$c_jns_fungsional'");
						$c_kel_pelatihan=trim((string)$result[$j]->c_kel_pelatihan);
						$n_kel_pelatihan=$db->fetchOne("select n_kelfungsional  from sdm.tr_kelfungsional where c_kelfungsional='$c_kel_pelatihan'");
						$c_jns_kelompok=(string)$result[$j]->c_jns_kelompok;
						$n_jns_kelompok=$db->fetchOne("select n_jenjang_fungsional  from sdm.tr_penjenganan_fungsional where c_jenjang_fungsional='$c_jns_kelompok'");
						$c_nama_kelompok=(string)$result[$j]->c_nama_kelompok;
						$n_nama_kelompok=$db->fetchOne("select n_jenjang  from sdm.tr_nama_penjenjangan where c_jenjang='$c_nama_kelompok'");
						
						$data[$j] = array("id"=>(string)$result[$j]->id,
										"i_peg_nip"=>(string)$result[$j]->i_peg_nip,
										"c_jns_fungsional"=>(string)$result[$j]->c_jns_fungsional,
										"n_jns_fungsional"=>$n_jns_fungsional,
										"c_kel_pelatihan"=>(string)$result[$j]->c_kel_pelatihan,
										"n_kel_pelatihan"=>$n_kel_pelatihan,
										"c_jns_kelompok"=>(string)$result[$j]->c_jns_kelompok,
										"n_jns_kelompok"=>$n_jns_kelompok,
										"c_nama_kelompok"=>(string)$result[$j]->c_nama_kelompok,
										"n_nama_kelompok"=>$n_nama_kelompok,
										"q_pelatihan"=>(string)$result[$j]->q_pelatihan,
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
		if ($par=='update'){$db->update('sdm.tm_pelatihan_fungsional',$maintain_data, "id = '".trim($data['id'])."'");}	 
		if ($par=='delete'){$db->delete('sdm.tm_pelatihan_fungsional', "id = '".trim($data['id'])."'");}
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