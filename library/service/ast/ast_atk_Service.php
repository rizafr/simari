<?php
class Ast_Atk_Service{
   	private static $instance;
     // A private constructor; prevents direct creation of object
    private function __construct() {
      //  echo 'I am constructed';
    }
 
    // The singleton method
     public static function getInstance() {
       if (!isset(self::$instance)) {
           $c = __CLASS__;
           self::$instance = new $c;
       }

       return self::$instance;
    }

 
    /* ************
	 * fungsi untuk memasukan data Pagu ke tabel 'e_pagu_ren_0_tm'
	 ***************************/
	public function insertAtkReff(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
	     $db->beginTransaction();
	     $atk_dtl_parm = array("c_atk"         		=>$data['KdBarang'],
	                           "c_atk_ctgr"    	    =>trim($data['KatBarang']),
						       "n_atk"              =>strtoupper($data['NmBarang']),
						       "n_atk_satuan"  		=>$data['KdSatuan'],
							   "n_atk_merek"  		=>$data['MrkBarang'],
							   "n_atk_tipe"         =>$data['TypeBarang'],
							   "i_rekanan"          =>'',
							   "q_atk_masagrs"      =>0,
							   "q_atk_stockmin"     =>$data['MinStock'],
							   "q_atk_stock"        =>0,
							   "v_atk_hrgsatuan"    =>0,
						       "i_entry"       		=>$data['nuser'],
						       "d_entry"       		=>date("Y-m-d"));
	   
	     $db->insert('e_ast_barang_atk_tr',$atk_dtl_parm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
	
	public function insertAtkReff3(array $data) {
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
						       "q_atk_masagrs"      =>0,
							   "q_atk_stockmin"     =>$data['MinStock'],
							   "q_atk_stock"        =>$data['Stock'],
							   "v_atk_hrgsatuan"    =>0,
						       "i_entry"       		=>$data['nuser'],
						       "d_entry"       		=>date("Y-m-d"));
	    //  echo "TEST CONN ".$db->getConnection()."<br>";
	     $db->insert('e_ast_barang_atk_tr',$pagu_mod_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	 
	 public function insertAtkReff2(array $data) {
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
							   "q_atk_stockmin"     =>$data['MinStock'],
							   "q_atk_stock"        =>$data['Stock'],
							   "v_atk_hrgsatuan"    =>0,
						       "i_entry"       		=>"ast",
						       "d_entry"       		=>date("Y-m-d"));
	    //  echo "TEST CONN ".$db->getConnection()."<br>";
	     $db->insert('e_ast_barang_atk_tr',$pagu_mod_prm);
		 $db->commit();
	     return 'sukses';
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
	     return 'sukses';
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
	     return 'sukses';
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
			 
			 $result = $db->fetchAll("SELECT a.c_atk,a.c_atk_ctgr,n_atk_ctgr,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,
										i_rekanan,q_atk_stockmin,q_atk_stock 
										FROM e_ast_barang_atk_tr a, e_ast_kategori_atk_tr b
										where n_atk like ? and a.c_atk_ctgr=b.c_atk_ctgr ORDER BY c_atk
										limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("c_atk"           	 =>(string)$result[$j]->c_atk,
										   "c_atk_ctgr"      =>(string)$result[$j]->c_atk_ctgr,
										   "n_atk_ctgr"      =>(string)$result[$j]->n_atk_ctgr,
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
		 //$where[] = "SK1000";
		 $where[] = "SK1400";
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