<?php
class ast_referensiatk_services 
{   
	private static $instance;
        // A private constructor; prevents direct creation of object
        
    	private function __construct() 
    	{
      	//  echo 'I am constructed';
    	} 
    	// The singleton method
    	
     	public static function getInstance() 
     	{
	        if (!isset(self::$instance)) 
	        {
	           $c = __CLASS__;
	           self::$instance = new $c;
	       	}
	       	return self::$instance;
    	}
	
	//untuk mengeluarkan list kategori atk pertama waktu di klik menu kategori atk 
	public function getKategoriATKList() 
	{
	   //echo "+masuk services getKategoriATKList";	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try 
	   {  	         	 
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT c_atk_ctgr,n_atk_ctgr,q_nomor_max 			
		 FROM e_ast_kategori_atk_tr order by c_atk_ctgr Asc'); 
		 
         	 $jmlResult = count($result);
		 //echo "+jumlah data =".$jmlResult; 
		  
		 if($jmlResult > 0)
		 {
			 for ($j = 0; $j < $jmlResult; $j++) 
			 {			 
			 	//field field yg akan ditampilkan aja di gui 
	           		$dataKategoriATKList[$j] = array("c_atk_ctgr"  	=>(string)$result[$j]->c_atk_ctgr,
	           					  "n_atk_ctgr"  	=>(string)$result[$j]->n_atk_ctgr,
	           					  "q_nomor_max" 	=>(string)$result[$j]->q_nomor_max);
			 }
        	}		 
	     	return $dataKategoriATKList;
	   } 
	   catch (Exception $e) 
	   {
         	echo $e->getMessage().'<br>';
	     	return 'gagal <br>';
	   }
	}
	
	public function getCariDataKategoriATKList($pageNumber,$itemPerPage,$namaBarang) 
	{
		$namaBarang = strtoupper($namaBarang);
		$nbrg = '%'.$namaBarang.'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $nbrg;
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$hasilAkhir = $db->fetchOne("select count(*) from e_ast_kategori_atk_tr where n_atk_ctgr like ? ",$where);
			 }
			else
			 {
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT c_atk_ctgr,n_atk_ctgr,q_nomor_max  
										FROM e_ast_kategori_atk_tr 
										where n_atk_ctgr like ? ORDER BY c_atk_ctgr
										limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("c_atk_ctgr"  	=>(string)$result[$j]->c_atk_ctgr,
		           					  "n_atk_ctgr"  	=>(string)$result[$j]->n_atk_ctgr,
		           					  "q_nomor_max" 	=>(string)$result[$j]->q_nomor_max);
				}					 
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	//untuk mengeluarkan list kategori pada waktu di klik button cari di referensiatk.phtml
	public function getCariDataKategoriATKList2($id)
	{
	   //echo "masuk services getCariDataKategoriATKList";
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   $namaKategori = strtoupper($id).'%';
	   //echo "namaKategori".$namaKategori;	   
	   $where[]=$namaKategori;	
	   
	   try 
	   {  	         	 
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT c_atk_ctgr,n_atk_ctgr,q_nomor_max 			
		 			FROM e_ast_kategori_atk_tr 
		 			where upper(n_atk_ctgr) like upper(?) order by c_atk_ctgr Asc',$where);
				
         	 $jmlResult = count($result);         	 
         	 //echo "jml data cari Service = ".$jmlResult;
		
		 if($jmlResult > 0)
		 {
			 for ($j = 0; $j < $jmlResult; $j++) 
			 {			 
			 	//field field yg akan ditampilkan aja di gui 
	           		$dataKategoriATKList[$j] = array("c_atk_ctgr"  	=>(string)$result[$j]->c_atk_ctgr,
	           					  "n_atk_ctgr"  	=>(string)$result[$j]->n_atk_ctgr,
	           					  "q_nomor_max" 	=>(string)$result[$j]->q_nomor_max);
			 }
        	 }		 
	     	 return $dataKategoriATKList;
	   } 
	   catch (Exception $e) 
	   {
         	echo $e->getMessage().'<br>';
	     	return 'gagal <br>';
	   }
	}
	
	//untuk memasukkan data ke tabel e_ast_kategori_atk_tr waktu di klik proses di referensiatkentri.phtml 
	public function getInsertKategoriATKList($data) 
	{
	   //echo "+masuk services getInsertKategoriATKList";	
	   //echo "no kategori barang = ".$data['c_atk_ctgr'];
	   
	   $registry = Zend_Registry::getInstance();
   	   $db = $registry->get('db');
   	   try 
   	   {
	     	$db->beginTransaction();
		$insertdataKatBrg = array("c_atk_ctgr"  	=>$data['c_atk_ctgr'],
									"n_atk_ctgr"  	=>$data['n_kategori'],	
									"q_nomor_max"  	=>$data['no_max'],
									"i_entry"       =>$data['nuser'],
									"d_entry"       =>date("Y-m-d"));
									
		$db->insert("e_ast_kategori_atk_tr", $insertdataKatBrg);
		$db->commit();
		$_hasil = array("rcNumber"=>"1","rcDesc"  =>"Proses Sukses");
	     	return 'sukses <br>';
   	   }
	   catch (Exception $e) 
   	   {
         	$db->rollBack();
         	echo $e->getMessage().'<br>';
	     	return 'gagal <br>';
   	   }
	}
	
	//untuk memasukkan data ke tabel e_ast_kategori_atk_tr waktu di klik proses di referensiatkedit.phtml 
	public function getUpdateKategoriATKList(array $data) 
	{
	   //echo "+masuk services getUpdateKategoriATKList";	
	   //echo "no kategori barang = ".$data['c_atk_ctgr'];
	   
	   $registry = Zend_Registry::getInstance();
   	   $db = $registry->get('db');
   	   try 
   	   {
	     	$db->beginTransaction();
		$updatetdataKatBrg = array("c_atk_ctgr"  	=>$data['c_atk_ctgr'],
									"n_atk_ctgr"  	=>$data['n_kategori'],	
									"q_nomor_max"  	=>$data['no_max'],
									"i_entry"       =>$data['nuser'],
									"d_entry"       =>date("Y-m-d"));
		                      
	        //parameter getUpdateKategoriATKList 		   
	        $where[] = "c_atk_ctgr  	=  '".trim($data['c_atk_ctgr'])."'";		                      
		                      
		$db->update('e_ast_kategori_atk_tr',$updatetdataKatBrg,$where);
		$db->commit();
		return 'sukses <br>';
	   } 
	   catch (Exception $e) 
	   {
         	$db->rollBack();
         	echo $e->getMessage().'<br>';
	     	return 'gagal <br>';
	   }
	}
	
	//=========================BARANG ATK ============================================================
	
	public function insertAtkReff(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	  // echo 'siapp masuk';
	   try {
	     $db->beginTransaction();
	     $pagu_mod_prm = array("c_atk"         		=>$data['KdBarang'],
	                           "c_atk_ctgr"    		=>$data['KatBarang'],
						       "n_atk"         		=>strtoupper($data['NmBarang']),
						       "n_atk_satuan"  		=>$data['KdSatuan'],
						       "n_atk_merek"   		=>$data['MrkBarang'],
						       "n_atk_tipe"    		=>$data['TypeBarang'],
						       "i_rekanan"     		=>$data['kodeSupplier'],
							   "q_atk_masagrs"      =>0,
							   "q_atk_stockmin"     =>$data[MinStock],
							   "q_atk_stock"        =>$data[Stock],
							   "v_atk_hrgsatuan"    =>0,
						       "i_entry"       		=>"ast",
						       "d_entry"       		=>date("Y-m-d"));
	    //  echo "TEST CONN ".$db->getConnection()."<br>";
	     $db->insert('e_ast_barang_atk_tr',$pagu_mod_prm);
		 $db->commit();
	     return 'sukses <br>';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	 
    /**
	 * fungsi untuk merubah data Pagu ke tabel 'e_pagu_ren_0_tm'
	 */
	 
	public function updateAtkReff(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	    
	   try {
	     $db->beginTransaction();
	     $pagu_mod_prm = array("c_atk_ctgr"    		=>$data['KatBarang'],
						       "n_atk"         		=>$data['NmBarang'],
						       "n_atk_satuan"  		=>$data['KdSatuan'],
						       "n_atk_merek"   		=>$data['MrkBarang'],
						       "n_atk_tipe"    		=>$data['TypeBarang'],
						       "i_rekanan"     		=>$data['kodeSupplier'],
							   "q_atk_masagrs"      =>0,
							   "q_atk_stockmin"     =>$data['MinStock'],
							   "q_atk_stock"        =>$data['Stock'],
							   "v_atk_hrgsatuan"    =>0,
						       "i_entry"       		=>"ast",
						       "d_entry"       		=>date("Y-m-d"));
	     
		 $where[] = "c_atk = '".$data['KdBarang']."'";
	     $db->update('e_ast_barang_atk_tr',$pagu_mod_prm, $where);
		 $db->commit();
	     return 'sukses <br>';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
     /**
	 * fungsi untuk menghapus data Pagu ke tabel 'e_pagu_ren_0_tm'
	 */
	 
	public function deleteAtkReff($KdBarang) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	   //  echo "TEST CONN ".$data['KdBarang'];
		 $where[] = "c_atk = '".$KdBarang."'";
		 $db->delete('e_ast_barang_atk_tr', $where);
		 $db->commit();
	     return 'sukses <br>';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
    /**
	 * fungsi untuk menampilkan data Pagu ke tabel 'e_pagu_ren_0_tm'
	 */
	 
	public function getAtkReffListByReq($NmBarang) {
	   $nambar = '%'.strtoupper($NmBarang).'%';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT c_atk,c_atk_ctgr,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,i_rekanan,q_atk_stockmin,q_atk_stock FROM e_ast_barang_atk_tr where n_atk like ?',$nambar);
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		  // echo 'jumlah '.$jmlResult;
           $hasilAkhir[$j] = array("c_atk"           =>(string)$result[$j]->c_atk,
								   "c_atk_ctgr"      =>(string)$result[$j]->c_atk_ctgr,
	                               "n_atk"           =>(string)$result[$j]->n_atk,
								   "n_atk_satuan"    =>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"     =>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"      =>(string)$result[$j]->n_atk_tipe,
							       "i_rekanan"       =>(string)$result[$j]->i_rekanan,
								   "q_atk_stockmin"  =>(string)$result[$j]->q_atk_stockmin,
							       "q_atk_stock"     =>(string)$result[$j]->q_atk_stock);
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getAtkReffListAll($pageNumber,$itemPerPage,$namaBarang) {
		$namaBarang = strtoupper($namaBarang);
		$nbrg = '%'.$namaBarang.'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $nbrg;
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$hasilAkhir = $db->fetchOne("select count(*) from e_ast_barang_atk_tr where n_atk like ? ",$where);
			 }
			else
			 {
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT c_atk,c_atk_ctgr,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,
										i_rekanan,q_atk_stockmin,q_atk_stock FROM e_ast_barang_atk_tr
										where n_atk like ? ORDER BY c_atk
										limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("c_atk"           	 =>(string)$result[$j]->c_atk,
										   "c_atk_ctgr"      =>(string)$result[$j]->c_atk_ctgr,
			                               "n_atk"           =>(string)$result[$j]->n_atk,
										   "n_atk_satuan"    =>(string)$result[$j]->n_atk_satuan,
										   "n_atk_merek"     =>(string)$result[$j]->n_atk_merek,
										   "n_atk_tipe"      =>(string)$result[$j]->n_atk_tipe,
									       "i_rekanan"       =>(string)$result[$j]->i_rekanan,
										   "q_atk_stockmin"  =>(string)$result[$j]->q_atk_stockmin,
									       "q_atk_stock"     =>(string)$result[$j]->q_atk_stock);
				}					 
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
    public function getAtkReffListAll2() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT c_atk,c_atk_ctgr,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,i_rekanan,q_atk_stockmin,q_atk_stock FROM e_ast_barang_atk_tr');
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		  // echo 'jumlah '.$jmlResult;
           $hasilAkhir[$j] = array("c_atk"           =>(string)$result[$j]->c_atk,
								   "c_atk_ctgr"      =>(string)$result[$j]->c_atk_ctgr,
	                               "n_atk"           =>(string)$result[$j]->n_atk,
								   "n_atk_satuan"    =>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"     =>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"      =>(string)$result[$j]->n_atk_tipe,
							       "i_rekanan"       =>(string)$result[$j]->i_rekanan,
								   "q_atk_stockmin"  =>(string)$result[$j]->q_atk_stockmin,
							       "q_atk_stock"     =>(string)$result[$j]->q_atk_stock);
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
    public function createKdBarang($KatBarang) {
	    $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	    try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ);
		 $where[] = "SK1000";
		 $where[] = "ATK";
		 $result = $db->fetchOne('SELECT gen_noatk(?)',$KatBarang);
		 return $result;
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return  0;
	   }
 
	}
	
}	
?>