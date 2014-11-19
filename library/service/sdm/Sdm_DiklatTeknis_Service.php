<?php
class Sdm_DiklatTeknis_Service {
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
					$result = $db->fetchAll("SELECT  id,i_peg_nip,c_kelompok,n_diklat,c_negara,q_lama,n_penyelenggara,
										i_sertifikat,to_char(d_sertifikat,'dd-mm-yyyy') as d_sertifikat,n_pejabat,i_entry,d_entry
										FROM sdm.tm_pelatihan_teknis where 1=1 $cari  order by c_kelompok asc");	
										
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
						$c_kelompok=(string)$result[$j]->c_kelompok;
						$n_kelompok=$db->fetchOne("select distinct(n_kelompok) as n_kelompok  from sdm.tr_diklat_teknis where c_kelompok='$c_kelompok'");
						$c_diklat_teknis=(string)$result[$j]->n_diklat;
						$n_diklat2=$db->fetchOne("select n_diklat_teknis from sdm.tr_diklat_teknis where c_diklat_teknis='$c_diklat_teknis'");
						//$c_negara=trim((string)$result[$j]->c_negara);
						//$n_negara=$db->fetchOne("select n_negara  from sdm.tr_negara where c_negara='$c_negara'");
						
						$data[$j] = array("id"=>(string)$result[$j]->id,
										"i_peg_nip"=>(string)$result[$j]->i_peg_nip,
										"c_kelompok"=>(string)$result[$j]->c_kelompok,
										"n_kelompok"=>$n_kelompok,
										"c_negara"=>(string)$result[$j]->c_negara,
										"n_negara"=>$n_negara,
										"n_diklat"=>(string)$result[$j]->n_diklat,
										"n_diklat2"=>$n_diklat2,
										"q_lama"=>(string)$result[$j]->q_lama,
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
								"c_kelompok"=>$data['c_kelompok'],
								"c_negara"=>$data['c_negara'],
								"n_diklat"=>$data['n_diklat'],
								"q_lama"=>$data['q_lama'],
								"n_penyelenggara"=>$data['n_penyelenggara'],
								"i_sertifikat"=>$data['i_sertifikat'],
								"d_sertifikat"=>$data['d_sertifikat'],
								"n_pejabat"=>$data['n_pejabat'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>$data['d_entry']);											
		//print_r($maintain_data);					
		if ($par=='insert'){$db->insert('sdm.tm_pelatihan_teknis',$maintain_data);}
		//if ($par=='update'){$db->update('sdm.tm_pelatihan_teknis',$maintain_data, "i_peg_nip = '".trim($data['i_peg_nip'])."'  and c_kelompok = '".trim($data['c_kelompok'])."' ");}	 
		//if ($par=='delete'){$db->delete('sdm.tm_pelatihan_teknis', "i_peg_nip = '".trim($data['i_peg_nip'])."'  and c_kelompok = '".trim($data['c_kelompok'])."' ");}
		if ($par=='update'){$db->update('sdm.tm_pelatihan_teknis',$maintain_data, " id = '".trim($data['id'])."' ");}	 
		if ($par=='delete'){$db->delete('sdm.tm_pelatihan_teknis', " id = '".trim($data['id'])."'   ");}
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