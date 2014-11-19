<?php
class Sdm_Referensicpns_Service {
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

 	public function getJabatancpns(array $dataMasukan, $pageNumber, $itemPerPage) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);
				
				$kategoriCari = $dataMasukan['kategoriCari'];
				$katakunciCari = strtoupper($dataMasukan['katakunciCari']);
				
				//echo "$kategoriCari | $katakunciCari";
				$sql1 = "select a.id, a.y_tahun, a.c_jabatan, a.n_jabatan, a.c_kode, b.n_kualifikasi_pend
						 from sdm.tm_cpns_jabatan a, sdm.tr_kualifikasi_pendidikan b
						 where a.c_kode = b.c_kualifikasi_pend
						";
				if($kategoriCari){
					$where = " and UPPER($kategoriCari) like '%$katakunciCari%'";
				}
				
				
				if(($pageNumber == 0) && ($itemPerPage == 0)) {
			
					$data = $db->fetchOne("select count(x.c_jabatan) 
										   FROM ($sql1 $where) x");
								
				} else {
					$xLimit=$itemPerPage;
			  		$xOffset=($pageNumber-1)*$itemPerPage;		
					
					$data = $db->fetchAll(" $sql1 $where 
											  order by a.c_jabatan 
											  limit $xLimit offset $xOffset");				
				}				
				
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}

	public function getKualifikasiPendidikan() 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);
				
				$sql1 = "select a.c_pend, a.c_kualifikasi_pend, a.n_kualifikasi_pend
						 from sdm.tr_kualifikasi_pendidikan a
						";
				
				$data = $db->fetchAll("$sql1");					
				
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
//echo "cccc=$par";
			$maintain_data = array("y_tahun"=>$data['y_tahun'],
							"c_jabatan"=>$data['c_jabatan'],
							"n_jabatan"=>$data['n_jabatan'],
							"i_entry"=>$data['i_entry'],
							"d_entry"=>date("Y-m-d"),
							"c_kode"=>$data['c_kode']);
			if ($par=='insert'){$db->insert('sdm.tm_cpns_jabatan',$maintain_data);}
			if ($par=='update'){$db->update('sdm.tm_cpns_jabatan',$maintain_data, "id = ".trim($data['id']));}	 
			if ($par=='delete'){$db->delete('sdm.tm_cpns_jabatan', "id = ".trim($data['id']));}
			$db->commit();
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal';
		}
	}

	public function detailJabatancpns($data) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);
				
				$id = $data['id'];
				$sql1 = "select a.id, a.y_tahun, a.c_jabatan, a.n_jabatan, a.c_kode, b.n_kualifikasi_pend
						 from sdm.tm_cpns_jabatan a, sdm.tr_kualifikasi_pendidikan b
						 where a.c_kode = b.c_kualifikasi_pend
						   and a.id = $id
						";
				
				$data = $db->fetchRow("$sql1");					
				
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function getJenjangPendidikan() 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);
				
				$sql1 = "select a.c_pend, a.n_pend
						 from sdm.tr_pendidikan a
						 where a.c_pend_jenis = 'F'
						";
				
				$data = $db->fetchAll("$sql1");					
				
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}	
}
?>