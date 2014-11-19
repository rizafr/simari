<?php

class ast_laporan_titi_Service {
	private static $instance;
	
	private function __construct() {
	//echo 'I am constructed';
	}
	
	// The singleton method
	public static function getInstance() 
	{
		if (!isset(self::$instance)) {
		   $c = __CLASS__;
		   self::$instance = new $c;
		}
		return self::$instance;
	}
	
	//------------list pertama kategori software ------------------------------
	public function getKategoriProblemSi($pageNumber,$itemPerPage) 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_kelompok_software_tr");
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				$result = $db->fetchAll("SELECT  *  FROM e_ast_kelompok_software_tr
										order by i_sw_kelompok limit $xLimit offset $xOffset");
				$jmlResult = count($result);
		 		//echo "jumlah di getKategoriProblemSi =".$jmlResult;
				if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {
		 
						$hasilAkhir[$j] = array("i_sw_kelompok"           =>(string)$result[$j]->i_sw_kelompok,
									    "n_sw_kelompok"    =>(string)$result[$j]->n_sw_kelompok);
					}
				}
			}		 
			return $hasilAkhir;
		} catch (Exception $e) {
	 echo $e->getMessage().'<br>';
	     return 'gagal';
		}
	}
	
	//---------------Insert Kategori sofware----------------------------
	public function insertKategoriProblemSi(array $data)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		//$where = $data['noPengajuan'];
		try {
			$db->beginTransaction();
			
				$prmInsert = array("i_sw_kelompok"	=>$data['i_sw_kelompok'],
						"n_sw_kelompok"		=>$data['n_sw_kelompok'],
						"i_entry"		=>$data['i_entry'],
						"d_entry"		=>date("Y-m-d"));				
				
			$db->insert('e_ast_kelompok_software_tr',$prmInsert);
			$db->commit();
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal';
	   }
	}
	
	//---------------Update Kategori software------------------------------------------
	public function updateKategoriProblemSi(array $data)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		//$where = $data['noPengajuan'];
		try {
			$db->beginTransaction();
			
			$prmUpdate = array("n_sw_kelompok"	=>$data['n_sw_kelompok']);
			$where = "i_sw_kelompok  =  '".$data['i_sw_kelompok']."'";
				
			$db->update('e_ast_kelompok_software_tr',$prmUpdate,$where);
			$db->commit();
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal';
	   }
	}
	
	//---------------cari Kategori software------------------------------------------	
	public function getCariSoftwareList($namakategori) 
	{
		$nsw = strtoupper($namakategori);
		$nsw = '%'.$nsw.'%';
		//echo 'namakategori services'.$namakategori;
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {		
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$result = $db->fetchAll('SELECT * FROM e_ast_kelompok_software_tr 
			where n_sw_kelompok like ? ORDER BY i_sw_kelompok',$nsw);
			
			$jmlResult = count($result);
			//echo "jumlah di services = ".$jmlResult;
			
			for ($j = 0; $j < $jmlResult; $j++) 
			{
				$hasilAkhir[$j] = array("i_sw_kelompok"   =>(string)$result[$j]->i_sw_kelompok,
							"n_sw_kelompok"    =>(string)$result[$j]->n_sw_kelompok);
			}					 
			return $hasilAkhir;
		} catch (Exception $e) {
		echo $e->getMessage().'<br>';
		return 'gagal <br>';
		}
	}
	//=======================================================================
}
?>