<?php
class Sdm_CpnsUsulan_Service {
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

   	public function getListCheckJabatCpns($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("select y_tahun,c_jabatan,n_jabatan,c_kode 
								FROM sdm.tm_cpns_jabatan where 1=1 $cari  order by c_jabatan asc");		
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 

						$data[$j] = array("y_tahun"=>(string)$result[$j]->y_tahun,
								"c_jabatan"=>$result[$j]->c_jabatan,
								"n_jabatan"=>(string)$result[$j]->n_jabatan);									
					}
										
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}    
  	public function getJabatCpns($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("select y_tahun,c_jabatan,n_jabatan,c_kode 
								FROM sdm.tm_cpns_jabatan where 1=1 $cari  order by c_jabatan asc");		
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 

						$data[$j] = array("y_tahun"=>(string)$result[$j]->y_tahun,
								"c_jabatan"=>$result[$j]->c_jabatan,
								"n_jabatan"=>(string)$result[$j]->n_jabatan);									
					}
										
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	} 

  	public function getJurusanPend($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("select c_kualifikasi_pend,n_kualifikasi_pend,c_pend 
								FROM sdm.tr_kulifikasi_pendidikan where 1=1 $cari  order by c_kualifikasi_pend asc");							
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
						$c_pend=trim($result[$j]->c_pend);
						$n_pendidikan = $db->fetchOne("select n_pend from sdm.tr_pendidikan where c_pend='$c_pend'");

						$data[$j] = array("c_kualifikasi_pend"=>(string)$result[$j]->c_kualifikasi_pend,
								"n_kualifikasi_pend"=>$result[$j]->n_kualifikasi_pend,
								"c_pend"=>(string)$result[$j]->c_pend,
								"n_pendidikan"=>$n_pendidikan);									
					}
										
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}   

  	public function getKualifikasiPend($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("select DISTINCT(c_pend) as c_pend
								FROM sdm.tr_kulifikasi_pendidikan where 1=1 $cari  order by c_pend asc");								
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
						$c_pend=trim($result[$j]->c_pend);
						$n_pendidikan = $db->fetchOne("select n_pend from sdm.tr_pendidikan where c_pend='$c_pend'");

						$data[$j] = array("c_kualifikasi_pend"=>(string)$result[$j]->c_kualifikasi_pend,
								"n_kualifikasi_pend"=>$result[$j]->n_kualifikasi_pend,
								"c_pend"=>(string)$result[$j]->c_pend,
								"n_pendidikan"=>$n_pendidikan);									
					}
										
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
 	public function getUsulan($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("select id,n_surat,to_char(d_surat,'dd-mm-yyyy') as d_surat,n_perihal,c_jabatan,n_pejabat,
								n_file,i_entry,d_entry,c_aktivasi FROM sdm.tm_cpns_usul where 1=1 $cari  order by id desc");		
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
						$c_jabatan=trim($result[$j]->c_jabatan);
						$n_jabatan = $db->fetchOne("select n_jabatan from sdm.tr_jabatan where c_jabatan='$c_jabatan'");
						$data[$j] = array("id"=>(string)$result[$j]->id,
								"n_surat"=>$result[$j]->n_surat,
								"d_surat"=>(string)$result[$j]->d_surat,
								"n_perihal"=>(string)$result[$j]->n_perihal,
								"c_jabatan"=>(string)$result[$j]->c_jabatan,
								"n_jabatan"=>$n_jabatan,
								"n_pejabat"=>(string)$result[$j]->n_pejabat,
								"n_file"=>(string)$result[$j]->n_file,								
								"c_aktivasi"=>(string)$result[$j]->c_aktivasi,		
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
	
	public function getUsulanJabatan($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{

				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("select id,n_surat,to_char(d_surat,'dd-mm-yyyy') as d_surat,n_jabatan_usul,n_pend_usul,i_entry,d_entry
								FROM sdm.tm_cpns_usuljabatan where 1=1 $cari  order by id asc");		
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
					
						$data[$j] = array("id"=>(string)$result[$j]->id,
								"n_surat"=>$result[$j]->n_surat,
								"d_surat"=>(string)$result[$j]->d_surat,
								"n_jabatan_usul"=>(string)$result[$j]->n_jabatan_usul,
								"n_pend_usul"=>(string)$result[$j]->n_pend_usul,							
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

	public function maintainDataUsul(array $data,$par) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();

	     $maintain_data = array("n_surat"=>$data['n_surat'],
				"d_surat"=>$data['d_surat'],
				"n_perihal"=>$data['n_perihal'],
				"c_jabatan"=>$data['c_jabatan'],
				"n_pejabat"=>$data['n_pejabat'],
				"n_file"=>$data['n_file'],
				"c_aktivasi"=>$data['c_aktivasi'],				
				"i_entry"=>$data['i_entry'],
				"d_entry"=>date("Y-m-d"));

		if ($par=='insert'){$db->insert('sdm.tm_cpns_usul',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tm_cpns_usul',$maintain_data, "id = '".trim($data['id'])."'");}	 
		if ($par=='delete'){$db->delete('sdm.tm_cpns_usul', "id = '".trim($data['id'])."'");}
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}


	public function maintainDataUsulJabatan(array $data,$par) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();

	     $maintain_data = array("n_surat"=>$data['n_surat'],
				"d_surat"=>$data['d_surat'],
				"n_jabatan_usul"=>$data['n_jabatan_usul'],
				"n_pend_usul"=>$data['n_pend_usul'],				
				"i_entry"=>$data['i_entry'],
				"d_entry"=>date("Y-m-d"));

		if ($par=='insert'){$db->insert('sdm.tm_cpns_usuljabatan',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tm_cpns_usuljabatan',$maintain_data, "n_surat = '".trim($data['n_surat'])."'");}	 
		if ($par=='delete'){$db->delete('sdm.tm_cpns_usuljabatan', "n_surat = '".trim($data['n_surat'])."' and to_char(d_surat,'yyyy-mm-dd') = '".$data['d_surat']."'");}
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function updateAktivasi(array $data,$par) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->beginTransaction();
		
		if ($par=='all'){$maintain_data = array("c_aktivasi"=>"2");
		$db->update('sdm.tm_cpns_usul',$maintain_data);}
		else{$maintain_data = array("c_aktivasi"=>$data['c_aktivasi']);
		$db->update('sdm.tm_cpns_usul',$maintain_data, "id = '".trim($data['id'])."'");}
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