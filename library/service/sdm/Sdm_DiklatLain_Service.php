<?php
class Sdm_DiklatLain_Service {
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
					$result = $db->fetchAll("SELECT  id,i_peg_nip,to_char(d_diklat,'dd-mm-yyyy') as d_diklat,n_diklat,c_negara,q_lama,n_penyelenggara,
										i_sertifikat,to_char(d_sertifikat,'dd-mm-yyyy') as d_sertifikat,n_pejabat,i_entry,d_entry
										FROM sdm.tm_pelatihan_lain where 1=1 $cari  order by d_diklat asc");	
										
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
						$c_negara=trim((string)$result[$j]->c_negara);
						if($c_negara) $n_negara=$db->fetchOne("select n_negara  from sdm.tr_negara where c_negara='$c_negara'");
						
						$data[$j] = array("id"=>(string)$result[$j]->id,"i_peg_nip"=>(string)$result[$j]->i_peg_nip,
										"d_diklat"=>(string)$result[$j]->d_diklat,
										"c_negara"=>(string)$result[$j]->c_negara,
										"n_negara"=>$n_negara,
										"n_diklat"=>(string)$result[$j]->n_diklat,
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
								"d_diklat"=>$data['d_diklat'],
								"c_negara"=>$data['c_negara'],
								"n_diklat"=>$data['n_diklat'],
								"q_lama"=>$data['q_lama'],
								"n_penyelenggara"=>$data['n_penyelenggara'],
								"i_sertifikat"=>$data['i_sertifikat'],
								"d_sertifikat"=>$data['d_sertifikat'],
								"n_pejabat"=>$data['n_pejabat'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>$data['d_entry']);											
							
		if ($par=='insert'){$db->insert('sdm.tm_pelatihan_lain',$maintain_data);}
		//if ($par=='update'){$db->update('sdm.tm_pelatihan_lain',$maintain_data, "i_peg_nip = '".trim($data['i_peg_nip'])."'  and d_diklat = '".trim($data['d_diklat'])."' ");}	 
		//if ($par=='delete'){$db->delete('sdm.tm_pelatihan_lain', "i_peg_nip = '".trim($data['i_peg_nip'])."'  and d_diklat = '".trim($data['d_diklat'])."' ");}
		if ($par=='update'){$db->update('sdm.tm_pelatihan_lain',$maintain_data, "id = '".trim($data['id'])."' ");}	 
		if ($par=='delete'){$db->delete('sdm.tm_pelatihan_lain', "id = '".trim($data['id'])."' ");}
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