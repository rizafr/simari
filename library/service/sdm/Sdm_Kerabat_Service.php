<?php
class Sdm_Kerabat_Service {
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

 	public function getKerabatList($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{

				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("select id,i_peg_nip,c_kerabat,n_nama,c_jns_kel,a_tempat_lahir,
											to_char(d_tanggal_lahir,'dd-mm-yyyy') as d_tanggal_lahir,
											n_pekerjaan,e_keterangan,i_entry,d_entry
											FROM sdm.tm_kerabat where 1=1 $cari  order by c_kerabat asc");		
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
					if ($result[$j]->c_jns_kel=='L'){$jnskel="Laki-laki";}
					if ($result[$j]->c_jns_kel=='P'){$jnskel="Perempuan";}					

						switch ($result[$j]->c_kerabat)
						{
							case "1":
							$n_kerabat= "Bapak Kandung";
							break;
							case "2":
							$n_kerabat= "Ibu Kandung";
							break;
							case "3":
							$n_kerabat= "Bapak Mertua";
							break;
							case "4":
							$n_kerabat= "Ibu Mertua";
							break;
							case "5":
							$n_kerabat= "Saudara Kandung";
							break;							
							default:
							$n_kerabat= "-";
						} 	
													
						$data[$j] = array("id"=>(string)$result[$j]->id,"i_peg_nip"=>(string)$result[$j]->i_peg_nip,
										"c_kerabat"=>(string)$result[$j]->c_kerabat,
										"n_kerabat"=>$n_kerabat,										
										"n_nama"=>(string)$result[$j]->n_nama,
										"c_jns_kel"=>(string)$result[$j]->c_jns_kel,
										"n_jns_kel"=>$jnskel,
										"a_tempat_lahir"=>(string)$result[$j]->a_tempat_lahir,
										"d_tanggal_lahir"=>(string)$result[$j]->d_tanggal_lahir,
										"n_pekerjaan"=>(string)$result[$j]->n_pekerjaan,
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
									"c_kerabat"=>$data['c_kerabat'],
									"n_nama"=>$data['n_nama'],
									"c_jns_kel"=>$data['c_jns_kel'],
									"a_tempat_lahir"=>$data['a_tempat_lahir'],
									"d_tanggal_lahir"=>$data['d_tanggal_lahir'],
									"n_pekerjaan"=>$data['n_pekerjaan'],
									"e_keterangan"=>$data['e_keterangan'],
									"i_entry"=>$data['i_entry'],
									"d_entry"=>date("Y-m-d"));

		if ($par=='insert'){$db->insert('sdm.tm_kerabat',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tm_kerabat',$maintain_data, "id = '".trim($data['id'])."'");}	 
		if ($par=='delete'){$db->delete('sdm.tm_kerabat', "id = '".trim($data['id'])."'");}
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