<?php
class Sdm_PenilaianMil_Service {
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

 	public function getPenilaianList($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{

				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT  c_nilai_kinerja,n_faktor_kinerja,n_standar_kinerja,
								q_nilai_dibawah,q_nilai_perbaikan,to_char(d_nilai_kinerja,'dd-mm-yyyy') as d_nilai_kinerja,
								q_nilai_sesuai,q_nilai_diatas,i_entry,d_entry
								FROM sdm.tr_formpenilaian_mil where 1=1 $cari  order by c_nilai_kinerja asc");	
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 		

						$data[$j] = array("c_nilai_kinerja"=>(string)$result[$j]->c_nilai_kinerja,
								"n_faktor_kinerja"=>(string)$result[$j]->n_faktor_kinerja,
								"n_standar_kinerja"=>(string)$result[$j]->n_standar_kinerja,
								"q_nilai_dibawah"=>(string)$result[$j]->q_nilai_dibawah,
								"q_nilai_perbaikan"=>(string)$result[$j]->q_nilai_perbaikan,
								"q_nilai_sesuai"=>(string)$result[$j]->q_nilai_sesuai,
								"q_nilai_diatas"=>(string)$result[$j]->q_nilai_diatas,
								"d_nilai_kinerja"=>(string)$result[$j]->d_nilai_kinerja,
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

	public function maintainDataRef(array $data,$par) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $maintain_data = array("c_nilai_kinerja"=>$data['c_nilai_kinerja'],
					"n_faktor_kinerja"=>$data['n_faktor_kinerja'],
					"n_standar_kinerja"=>$data['n_standar_kinerja'],
					"q_nilai_dibawah"=>$data['q_nilai_dibawah'],
					"q_nilai_perbaikan"=>$data['q_nilai_perbaikan'],
					"q_nilai_perbaikan"=>$data['q_nilai_perbaikan'],
					"q_nilai_sesuai"=>$data['q_nilai_sesuai'],
					"q_nilai_diatas"=>$data['q_nilai_diatas'],
					"d_nilai_kinerja"=>$data['d_nilai_kinerja'],
					"i_entry"=>$data['i_entry'],
					"d_entry"=>date("Y-m-d"));

		if ($par=='insert'){$db->insert('sdm.tr_formpenilaian_mil',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tr_formpenilaian_mil',$maintain_data, "c_nilai_kinerja = '".trim($data['c_nilai_kinerja2'])."' ");}	 
		if ($par=='delete'){$db->delete('sdm.tr_formpenilaian_mil', "c_nilai_kinerja = '".trim($data['c_nilai_kinerja'])."' ");}
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function getMaxRefNilai() 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{

			$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$data = $db->fetchOne("SELECT max(c_nilai_kinerja) FROM sdm.tr_formpenilaian_mil");		
				return $data;
		} catch (Exception $e) 
		{
		         echo $e->getMessage().'<br>';
			     return 'Data tidak ada <br>';
		}
	}	


	

 	public function getHasilPenilaianList($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{

				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				$sql ="SELECT id,i_peg_nip,a.c_nilai_kinerja,a.q_nilai_kinerja,a.c_pers_penilai,a.i_nip_penilai,
								a.n_penilai,to_char(a.d_nilai_kinerja,'yyyy') as d_nilai_kinerja ,b.n_faktor_kinerja,b.n_standar_kinerja,
								q_nilai_dibawah,q_nilai_perbaikan,q_nilai_sesuai,q_nilai_diatas
								FROM sdm.tm_penilaian_kinerjamil a, sdm.tr_formpenilaian_mil b 
								where a.c_nilai_kinerja=b.c_nilai_kinerja $cari  order by a.c_pers_penilai asc ,a.c_nilai_kinerja desc";
					//echo $sql;
					$result = $db->fetchAll($sql);	
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 	

						$data[$j] = array("id"=>(string)$result[$j]->id,
								"i_peg_nip"=>(string)$result[$j]->i_peg_nip,
								"c_nilai_kinerja"=>(string)$result[$j]->c_nilai_kinerja,
								"q_nilai_kinerja"=>(string)$result[$j]->q_nilai_kinerja,
								"c_pers_penilai"=>(string)$result[$j]->c_pers_penilai,
								"i_nip_penilai"=>(string)$result[$j]->i_nip_penilai,
								"n_penilai"=>(string)$result[$j]->n_penilai,
								"d_nilai_kinerja"=>(string)$result[$j]->d_nilai_kinerja,
								"n_faktor_kinerja"=>(string)$result[$j]->n_faktor_kinerja,
								"n_standar_kinerja"=>(string)$result[$j]->n_standar_kinerja,
								"q_nilai_dibawah"=>(string)$result[$j]->q_nilai_dibawah,
								"q_nilai_perbaikan"=>(string)$result[$j]->q_nilai_perbaikan,
								"q_nilai_sesuai"=>(string)$result[$j]->q_nilai_sesuai,
								"q_nilai_diatas"=>(string)$result[$j]->q_nilai_diatas);									
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
	     $q_nilai_kinerja = $data['q_nilai_kinerja'] != '' ?  $data['q_nilai_kinerja'] : 0;
		 $c_nilai_kinerja = $data['c_nilai_kinerja'] != '' ? $data['c_nilai_kinerja'] : 0;
		 $maintain_data = array("i_peg_nip"=>$data['i_peg_nip'],
					"c_nilai_kinerja"=>$c_nilai_kinerja,
					"q_nilai_kinerja"=>$q_nilai_kinerja,
					"c_pers_penilai"=>$data['c_pers_penilai'],
					"i_nip_penilai"=>$data['i_nip_penilai'],
					"n_penilai"=>$data['n_penilai'],
					"d_nilai_kinerja"=>$data['d_nilai_kinerja'],
					"i_entry"=>$data['i_entry'],
					"d_entry"=>date("Y-m-d"));
		//var_dump($maintain_data);
		if ($par=='insert'){$db->insert('sdm.tm_penilaian_kinerjamil',$maintain_data);}
		//if ($par=='update'){$db->update('sdm.tm_penilaian_kinerjamil',$maintain_data, " id = '".trim($data['id'])."'");}	 
		//if ($par=='delete'){$db->delete('sdm.tm_penilaian_kinerjamil', "id = '".trim($data['id'])."'");}
		//if ($par=='update'){$db->update('sdm.tm_penilaian_kinerjamil',$maintain_data, " c_pers_penilai = '".trim($data['c_pers_penilai'])."' and c_nilai_kinerja = '".trim($data['c_nilai_kinerja'])."' and i_peg_nip = '".trim($data['i_peg_nip'])."'  and d_nilai_kinerja = '".trim($data['d_nilai_kinerja'])."' ");}	 
		//if ($par=='delete'){$db->delete('sdm.tm_penilaian_kinerjamil', "c_pers_penilai = '".trim($data['c_pers_penilai'])."' and c_nilai_kinerja = '".trim($data['c_nilai_kinerja2'])."' and i_peg_nip = '".trim($data['i_peg_nip'])."'  and d_nilai_kinerja = '".trim($data['d_nilai_kinerja'])."'  ");}
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
	public function hapusData(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		if($data['c_pers_penilai'])	$db->delete('sdm.tm_penilaian_kinerjamil', "d_nilai_kinerja = '".trim($data['d_nilai_kinerja'])."' and c_pers_penilai = '".trim($data['c_pers_penilai'])."'"); 
		else $db->delete('sdm.tm_penilaian_kinerjamil', "d_nilai_kinerja = '".trim($data['d_nilai_kinerja'])."'"); 
		
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