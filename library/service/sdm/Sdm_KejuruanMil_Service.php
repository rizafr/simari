<?php
class Sdm_KejuruanMil_Service {
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

 	public function getKejuruanList($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{

				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT  id,i_peg_nip,c_jenis_kel,c_kejuruanmil,d_tahun_masuk,d_tahun_lulus,
								c_status,n_tempat,i_entry,d_entry
								FROM sdm.tm_kejuruan_militer where 1=1 $cari  order by c_jenis_kel asc");	
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 		
						$n_jenis_kel = $db->fetchOne("SELECT n_jenis_kel from sdm.tr_jurmiliter where c_jenis_kel=".(string)$result[$j]->c_jenis_kel);
						$n_kejuruanmil = $db->fetchOne("SELECT n_kejuruanmil from sdm.tr_kejuruanmiliter where c_kejuruanmil=".(string)$result[$j]->c_kejuruanmil);
						if ((string)$result[$j]->c_status=='1'){$n_status='Lulus';}
						if ((string)$result[$j]->c_status=='2'){$n_status='Tidak Lulus';}
						$data[$j] = array("i_peg_nip"=>(string)$result[$j]->i_peg_nip,
								"c_jenis_kel"=>(string)$result[$j]->c_jenis_kel,
								"id"=>(string)$result[$j]->id,
								"n_jenis_kel"=>$n_jenis_kel,
								"c_kejuruanmil"=>(string)$result[$j]->c_kejuruanmil,
								"n_kejuruanmil"=>$n_kejuruanmil,
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
					"c_jenis_kel"=>$data['c_jenis_kel'],
					"c_kejuruanmil"=>$data['c_kejuruanmil'],
					"d_tahun_masuk"=>$data['d_tahun_masuk'],
					"d_tahun_lulus"=>$data['d_tahun_lulus'],
					"c_status"=>$data['c_status'],
					"n_tempat"=>$data['n_tempat'],
					"i_entry"=>$data['i_entry'],
					"d_entry"=>date("Y-m-d"));

		if ($par=='insert'){$db->insert('sdm.tm_kejuruan_militer',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tm_kejuruan_militer',$maintain_data, "id = '".trim($data['id'])."'");}	 
		if ($par=='delete'){$db->delete('sdm.tm_kejuruan_militer', "id = '".trim($data['id'])."'");}
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