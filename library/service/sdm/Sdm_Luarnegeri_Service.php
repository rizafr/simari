<?php
class Sdm_Luarnegeri_Service {
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

 	public function getLuarnegeriList($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{

				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT  id,i_peg_nip,c_negara,to_char(d_barangkat,'dd-mm-yyyy') as d_barangkat,
										a_tujuan,c_biaya,q_hari,q_bulan,q_tahun,e_sponsor,e_keterangan,i_entry,d_entry
										FROM sdm.tm_luarnegeri where 1=1 $cari  order by d_barangkat asc");		
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 		
						$n_negara = $db->FetchOne('SELECT n_negara FROM sdm.tr_negara WHERE c_negara = ?',$result[$j]->c_negara);
						
						switch ($result[$j]->c_biaya)
						{
							case "01":
							$n_biaya= "Dinas";
							break;
							case "02":
							$n_biaya= "Mandiri";
							break;
							case "03":
							$n_biaya= "Penyelenggara";
							break;
							case "04":
							$n_biaya= "Sponsor";
							break;
							default:
							$n_biaya= "-";
						} 
						$data[$j] = array("i_peg_nip"=>(string)$result[$j]->i_peg_nip,
										"id"=>(string)$result[$j]->id,
										"c_negara"=>(string)$result[$j]->c_negara,
										"n_negara"=>$n_negara,
										"n_biaya"=>$n_biaya,
										"a_tujuan"=>(string)$result[$j]->a_tujuan,
										"c_biaya"=>(string)$result[$j]->c_biaya,
										"e_sponsor"=>(string)$result[$j]->e_sponsor,
										"d_barangkat"=>(string)$result[$j]->d_barangkat,
										"q_hari"=>(string)$result[$j]->q_hari,
										"q_bulan"=>(string)$result[$j]->q_bulan,
										"q_tahun"=>(string)$result[$j]->q_tahun,
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

	public function maintainData(array $data,$par) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $maintain_data = array("i_peg_nip"=>$data['i_peg_nip'],
							"c_negara"=>$data['c_negara'],
							"a_tujuan"=>$data['a_tujuan'],
							"c_biaya"=>$data['c_biaya'],
							"e_sponsor"=>$data['e_sponsor'],
							"d_barangkat"=>$data['d_barangkat'],
							"q_hari"=>$data['q_hari'],
							"q_bulan"=>$data['q_bulan'],
							"q_tahun"=>$data['q_tahun'],
							"e_keterangan"=>$data['e_keterangan'],
							"i_entry"=>$data['i_entry'],
							"d_entry"=>date("Y-m-d"));

		if ($par=='insert'){$db->insert('sdm.tm_luarnegeri',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tm_luarnegeri',$maintain_data, "id = '".trim($data['id'])."'");}	 
		if ($par=='delete'){$db->delete('sdm.tm_luarnegeri', "id = '".trim($data['id'])."'");}
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