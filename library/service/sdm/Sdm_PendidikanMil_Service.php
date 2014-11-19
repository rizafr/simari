<?php
class Sdm_PendidikanMil_Service {
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

 	public function getPendidikanList($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{

				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT  id,i_peg_nip,c_jenjang,c_sekolahmil,d_tahun_masuk,d_tahun_lulus,
								c_status,n_tempat,i_entry,d_entry
								FROM sdm.tm_pendidikan_militer where 1=1 $cari  order by c_jenjang asc");	
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 		
						$n_jenjang = $db->fetchOne("SELECT n_jenjang from sdm.tr_jenjang_dikmil where c_jenjang=".(string)$result[$j]->c_jenjang);
						$n_sekolahmil = $db->fetchOne("SELECT n_sekolahmil from sdm.tr_sekolah_militer where c_sekolahmil=".(string)$result[$j]->c_sekolahmil);
						if ((string)$result[$j]->c_status=='1'){$n_status='Lulus';}
						if ((string)$result[$j]->c_status=='2'){$n_status='Tidak Lulus';}
						$data[$j] = array("i_peg_nip"=>(string)$result[$j]->i_peg_nip,
								"id"=>(string)$result[$j]->id,
								"c_jenjang"=>(string)$result[$j]->c_jenjang,
								"n_jenjang"=>$n_jenjang,
								"c_sekolahmil"=>(string)$result[$j]->c_sekolahmil,
								"n_sekolahmil"=>$n_sekolahmil,
								"d_tahun_masuk"=>(string)$result[$j]->d_tahun_masuk,
								"d_tahun_lulus"=>(string)$result[$j]->d_tahun_lulus,
								"c_status"=>(string)$result[$j]->c_status,
								"n_status"=>$n_status,
								"n_tempat"=>(string)$result[$j]->n_tempat,
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
					"c_jenjang"=>$data['c_jenjang'],
					"c_sekolahmil"=>$data['c_sekolahmil'],
					"d_tahun_masuk"=>$data['d_tahun_masuk'],
					"d_tahun_lulus"=>$data['d_tahun_lulus'],
					"c_status"=>$data['c_status'],
					"n_tempat"=>$data['n_tempat'],
					"i_entry"=>$data['i_entry'],
					"d_entry"=>date("Y-m-d"));
		
		if ($par=='insert'){$db->insert('sdm.tm_pendidikan_militer',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tm_pendidikan_militer',$maintain_data, "id = '".trim($data['id'])."'");}	 
		if ($par=='delete'){$db->delete('sdm.tm_pendidikan_militer', "id = '".trim($data['id'])."'");}
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