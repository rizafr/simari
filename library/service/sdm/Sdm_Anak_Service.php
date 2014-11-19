<?php
class Sdm_Anak_Service {
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
	public function getAnakCount($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{

				$db->setFetchMode(Zend_Db::FETCH_OBJ);		
					$sql = "select count(*) FROM sdm.tm_anak where 1=1 $cari ";	
					$jmlResult = $db->fetchOne($sql);
				return $jmlResult;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}	
 	public function getAnakList($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{

				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("select id,i_peg_nip,c_anak,n_nama,c_jns_kel,a_tempat_lahir,
											to_char(d_tanggal_lahir,'yyyy-mm-dd') as d_tanggal_lahir,
											c_tunjangan,c_pekerjaan,e_keterangan,i_entry,d_entry,q_anak_ke
											FROM sdm.tm_anak where 1=1 $cari  order by d_tanggal_lahir asc");		
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
					if ($result[$j]->c_jns_kel=='L'){$jnskel="Laki-laki";}
					if ($result[$j]->c_jns_kel=='P'){$jnskel="Perempuan";}		
					if ($result[$j]->c_tunjangan=='Y'){$ntunj="Ya";}
					if ($result[$j]->c_tunjangan=='T'){$ntunj="Tidak";}	
					//$c_pekerjaan=(string)$result[$j]->c_pekerjaan;					
					//$n_pekerjaan=$db->fetchOne("select n_kerja from sdm.tr_pekerjaan_anak where c_kerja ='$c_pekerjaan'");
					
					if ($result[$j]->c_anak=='1'){$n_anak="Kandung";}
					if ($result[$j]->c_anak=='2'){$n_anak="Tiri";}		
					if ($result[$j]->c_anak=='3'){$n_anak="Angkat";}
					list($y_lahir,$m_lahir,$d_lahir) = split('-',$result[$j]->d_tanggal_lahir);
					$d_tanggal_lahir = $d_lahir.'-'.$m_lahir.'-'.$y_lahir;
						$data[$j] = array("id"=>(string)$result[$j]->id,
											"i_peg_nip"=>(string)$result[$j]->i_peg_nip,
										"c_anak"=>$result[$j]->c_anak,
										"n_anak"=>$n_anak,
										"n_nama"=>(string)$result[$j]->n_nama,
										"c_jns_kel"=>(string)$result[$j]->c_jns_kel,
										"n_jns_kel"=>$jnskel,
										"a_tempat_lahir"=>(string)$result[$j]->a_tempat_lahir,
										"d_tanggal_lahir"=>$d_tanggal_lahir,
										"c_tunjangan"=>(string)$result[$j]->c_tunjangan,
										"n_tunjangan"=>$ntunj,
										"c_pekerjaan"=>(string)$result[$j]->c_pekerjaan,
										"n_pekerjaan"=>$n_pekerjaan,
										"e_keterangan"=>(string)$result[$j]->e_keterangan,
										"q_anak_ke"=>(string)$result[$j]->q_anak_ke,										
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
									"c_anak"=>$data['c_anak'],
									"n_nama"=>$data['n_nama'],
									"c_jns_kel"=>$data['c_jns_kel'],
									"a_tempat_lahir"=>$data['a_tempat_lahir'],
									"d_tanggal_lahir"=>$data['d_tanggal_lahir'],
									"c_tunjangan"=>$data['c_tunjangan'],
									"c_pekerjaan"=>$data['c_pekerjaan'],
									"e_keterangan"=>$data['e_keterangan'],
									"q_anak_ke"=>$data['q_anak_ke'],									
									"i_entry"=>$data['i_entry'],
									"d_entry"=>date("Y-m-d"));
		
		
		if ($par=='insert'){$db->insert('sdm.tm_anak',$maintain_data);}
		//if ($par=='update'){$db->update('sdm.tm_anak',$maintain_data, "i_peg_nip = '".trim($data['i_peg_nip'])."'  and c_anak = '".trim($data['c_anak2'])."' and q_anak_ke = '".trim($data['q_anak_ke2'])."' ");}	 
		//if ($par=='delete'){$db->delete('sdm.tm_anak', "i_peg_nip = '".trim($data['i_peg_nip'])."' and c_anak = '".trim($data['c_anak'])."' and q_anak_ke = '".trim($data['q_anak_ke'])."' ");}
		if ($par=='update'){$db->update('sdm.tm_anak',$maintain_data, "id = '".trim($data['id'])."'");}	 
		if ($par=='delete'){$db->delete('sdm.tm_anak', "id = '".trim($data['id'])."'");}
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