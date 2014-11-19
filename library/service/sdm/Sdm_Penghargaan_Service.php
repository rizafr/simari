<?php
class Sdm_Penghargaan_Service {
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

 	public function getPenghargaanList($cari) 
	{
	
	
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
					$result = $db->fetchAll("SELECT  id,i_peg_nip,n_jns_penghargaan,n_penghargaan,d_tahun_alteratif,c_negara_alternatif,
										n_lembaga_alternatif,e_keterangan,i_entry,d_entry
										FROM sdm.tm_penghargaan where 1=1 $cari  order by d_entry asc");		
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 		
						$kdnegara = trim($result[$j]->c_negara_alternatif);
						$namaNegara ='';	
						$jnsPenghargaan = $db->fetchOne('SELECT n_penghargaan FROM sdm.tr_penghargaan WHERE c_penghargaan = ?',$result[$j]->n_jns_penghargaan);
						$namaPenghargaan = $db->fetchOne('SELECT n_tandajasa FROM sdm.tr_tandajasa WHERE c_tandajasa = ?',$result[$j]->n_penghargaan);
						if ($kdnegara != '') $namaNegara = $db->fetchOne("SELECT n_negara FROM sdm.tr_negara WHERE c_negara = '$kdnegara'");
						$data[$j] = array("id"=>(integer)$result[$j]->id,
										"i_peg_nip"=>(string)$result[$j]->i_peg_nip,
										"n_jns_penghargaan"=>(string)$result[$j]->n_jns_penghargaan,
										"jenispenghargaan"=>$jnsPenghargaan,
										"n_penghargaan"=>(string)$result[$j]->n_penghargaan,
										"namapenghargaan"=>$namaPenghargaan,
										"d_tahun_alteratif"=>(string)$result[$j]->d_tahun_alteratif,
										"namaNegara"=>$namaNegara,
										"c_negara_alternatif"=>(string)$result[$j]->c_negara_alternatif,
										"n_lembaga_alternatif"=>(string)$result[$j]->n_lembaga_alternatif,
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
	     $maintain_data = array(
							"i_peg_nip"=>$data['i_peg_nip'],
							"n_jns_penghargaan"=>$data['n_jns_penghargaan'],
							"n_penghargaan"=>$data['n_penghargaan'],
							"d_tahun_alteratif"=>$data['d_tahun_alteratif'],
							"c_negara_alternatif"=>$data['c_negara_alternatif'],
							"n_lembaga_alternatif"=>$data['n_lembaga_alternatif'],
							"e_keterangan"=>$data['e_keterangan'],
							"i_entry"=>$data['i_entry'],
							"d_entry"=>date("Y-m-d"));
		if ($par=='insert'){$db->insert('sdm.tm_penghargaan',$maintain_data);}
		//if ($par=='update'){$db->update('sdm.tm_penghargaan',$maintain_data, "i_peg_nip = '".trim($data['i_peg_nip'])."'  and n_jns_penghargaan = '".trim($data['n_jns_penghargaan2'])."' and n_penghargaan = '".trim($data['n_penghargaan2'])."'");}	 
		if ($par=='update'){$db->update('sdm.tm_penghargaan',$maintain_data, "id = '".trim($data['id'])."'");}	 
		if ($par=='delete'){$db->delete('sdm.tm_penghargaan', "id = '".trim($data['id'])."'  ");}
		//if ($par=='delete'){$db->delete('sdm.tm_penghargaan', "i_peg_nip = '".trim($data['i_peg_nip'])."'  and n_jns_penghargaan = '".trim($data['n_jns_penghargaan'])."' and n_penghargaan = '".trim($data['n_penghargaan'])."' ");}
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