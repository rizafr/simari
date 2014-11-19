<?php
class ast_penghapusan_atkmaster_service {
   
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
	 
	    
	public function queryEditAjuanHapusAtkM($pageNumber,$itemPerPage,$unitkr) {
		$status='A';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $unitkr;
			$where[] = $status;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_hapus_atk_tm where i_orgb=? and c_atk_setju1hapus=? ",$where);
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				
				$result = $db->fetchAll("SELECT i_atk_ajuanhapus,d_atk_ajuanhapus FROM e_ast_hapus_atk_tm where i_orgb=? and c_atk_setju1hapus=?
										order by i_atk_ajuanhapus
										limit $xLimit offset $xOffset",$where); 
			 
				$jmlResult = count($result);
			 	for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuanhapus"           =>(string)$result[$j]->i_atk_ajuanhapus,
											"d_atk_ajuanhapus"           =>(string)$result[$j]->d_atk_ajuanhapus);
											
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada';
	   }
	}
	public function queryEditAjuanHapusAtkM2($unitkr) {
       $status='A';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_atk_ajuanhapus,d_atk_ajuanhapus FROM e_ast_hapus_atk_tm where i_orgb=? and c_atk_setju1hapus=?',$where); 
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_atk_ajuanhapus"           =>(string)$result[$j]->i_atk_ajuanhapus,
								   "d_atk_ajuanhapus"           =>(string)$result[$j]->d_atk_ajuanhapus);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	   
	}	
	
	public function queryAjuanHapusAtkM($unitkr) {
       $status='A';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_atk_ajuanhapus,d_atk_ajuanhapus FROM e_ast_hapus_atk_tm where i_orgb=? and c_atk_setju1hapus=?',$where); 
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_atk_ajuanhapus"           =>(string)$result[$j]->i_atk_ajuanhapus,
								   "d_atk_ajuanhapus"           =>(string)$result[$j]->d_atk_ajuanhapus);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	   
	}	
	public function queryHapusSubbagAtkM($pageNumber,$itemPerPage) {
		$status='B';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $status;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_hapus_atk_tm where (c_atk_setju1hapus=? 
											 or c_atk_setju1hapus ='' or c_atk_setju1hapus isnull)",$where);
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				
				$result = $db->fetchAll("SELECT i_atk_ajuanhapus,d_atk_ajuanhapus,a.i_orgb,n_orgb FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										where a.i_orgb=b.i_orgb and (c_atk_setju1hapus=? or c_atk_setju1hapus ='' or c_atk_setju1hapus isnull)
										order by i_atk_ajuanhapus
										limit $xLimit offset $xOffset",$where); 
				$jmlResult = count($result);
			 	for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuanhapus"          =>(string)$result[$j]->i_atk_ajuanhapus,
											"d_atk_ajuanhapus"          =>(string)$result[$j]->d_atk_ajuanhapus,
											"i_orgb"           			=>(string)$result[$j]->i_orgb,
											"n_orgb"           			=>(string)$result[$j]->n_orgb);
											
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada';
	   }
	}
	public function queryHapusSubbagAtkM2($unitkr) {
       $status='B';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_atk_ajuanhapus,d_atk_ajuanhapus FROM e_ast_hapus_atk_tm where i_orgb=? and c_atk_setju1hapus=?',$where); 
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_atk_ajuanhapus"           =>(string)$result[$j]->i_atk_ajuanhapus,
								   "d_atk_ajuanhapus"           =>(string)$result[$j]->d_atk_ajuanhapus);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
	public function queryHapusKabagAtkM($pageNumber,$itemPerPage) {
		$status='Y';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $status;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_hapus_atk_tm 
											 where c_atk_setju1hapus=? and c_atk_setuju2hapus is null ",$where);
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				
				$result = $db->fetchAll("SELECT i_atk_ajuanhapus,d_atk_ajuanhapus,a.i_orgb,n_orgb 
										FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b
										where a.i_orgb=b.i_orgb and c_atk_setju1hapus=? and (c_atk_setuju2hapus is null or c_atk_setuju2hapus='')
										order by i_atk_ajuanhapus
										limit $xLimit offset $xOffset",$where); 
			 
				$jmlResult = count($result);
				for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuanhapus"           =>(string)$result[$j]->i_atk_ajuanhapus,
											"d_atk_ajuanhapus"           =>(string)$result[$j]->d_atk_ajuanhapus,
											"i_orgb"           			=>(string)$result[$j]->i_orgb,
											"n_orgb"           			=>(string)$result[$j]->n_orgb);
											
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada';
	   }
	}
	public function queryHapusKabagAtkM2($unitkr) {
       $status='Y';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_atk_ajuanhapus,d_atk_ajuanhapus 
									FROM e_ast_hapus_atk_tm 
									where i_orgb=? and c_atk_setju1hapus=? and c_atk_setuju2hapus is null',$where); 
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_atk_ajuanhapus"           =>(string)$result[$j]->i_atk_ajuanhapus,
								   "d_atk_ajuanhapus"           =>(string)$result[$j]->d_atk_ajuanhapus);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
	public function queryHapusProsesAtkM($pageNumber,$itemPerPage) {
		$status='Y';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $status;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_hapus_atk_tm 
											where c_atk_setuju2hapus=? and d_atk_hapus is null  ",$where);
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				
				$result = $db->fetchAll("SELECT i_atk_ajuanhapus,d_atk_ajuanhapus, i_atk_setuju2hapus , d_atk_setuju2hapus ,a.i_orgb,n_orgb 
										FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b
										where a.i_orgb=b.i_orgb and c_atk_setuju2hapus=? and d_atk_hapus is null 
										order by i_atk_ajuanhapus
										limit $xLimit offset $xOffset",$where); 
			 
				$jmlResult = count($result);
				for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuanhapus"           =>(string)$result[$j]->i_atk_ajuanhapus,
										    "d_atk_ajuanhapus"           =>(string)$result[$j]->d_atk_ajuanhapus,
										    "i_atk_setuju2hapus"         =>(string)$result[$j]->i_atk_setuju2hapus,
										    "d_atk_setuju2hapus"         =>(string)$result[$j]->d_atk_setuju2hapus,
											"i_orgb"           			 =>(string)$result[$j]->i_orgb,
											"n_orgb"           			 =>(string)$result[$j]->n_orgb);
											
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada';
	   }
	}
	public function queryHapusProsesAtkM2($unitkr) {
       $status='Y';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_atk_ajuanhapus,d_atk_ajuanhapus, i_atk_setuju2hapus , d_atk_setuju2hapus 
									FROM e_ast_hapus_atk_tm 
									where i_orgb=? and c_atk_setuju2hapus=? and d_atk_hapus is null ORDER BY i_atk_ajuanhapus ',$where); 
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_atk_ajuanhapus"           =>(string)$result[$j]->i_atk_ajuanhapus,
								   "d_atk_ajuanhapus"           =>(string)$result[$j]->d_atk_ajuanhapus,
								   "i_atk_setuju2hapus"           =>(string)$result[$j]->i_atk_setuju2hapus,
								   "d_atk_setuju2hapus"           =>(string)$result[$j]->d_atk_setuju2hapus);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	
	public function queryUnitKerja($unitkr) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = "i_orgb = '".$data['unitkr']."'";
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_orgb,n_orgb FROM e_org_0_0_tm where i_orgb=?',$unitkr);
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb"           =>(string)$result[$j]->i_orgb,
								   "n_orgb"          =>(string)$result[$j]->n_orgb);
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
 public function queryNourutmax(array $data) {
	    
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	    try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ);
		 $where[] = $data['unitkr'];
		 $where[] = $data['modl'];
		 $result = $db->fetchOne('SELECT gen_nomor(?,?)',$where);
		
	     return $result;
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return  0;
	   }
 
	} 
public function InsertNourutmax(array $data) {
	 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
	     $nomor_max_prm = array("i_orgb"         		  =>$data['unitkr'],
	                           "c_modul"    	    	  =>$data['modl'],
						       "d_modul_tahun"            =>$data['tahun'],
						       "q_modul_nomormax"  		  =>$data['nomor']);
						      
	   
	     $db->insert('e_modul_nomor_max_tr',$nomor_max_prm);
		 $db->commit();
	     return 'sukses insert nomax <br>';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>'; 
	     return 'gagal insert master nomax<br>';
	   }
	}
public function UpdateNourutmax(array $data) {
       
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $nomor_max_prm = array("i_orgb"         		  =>$data['unitkr'],
	                           "c_modul"    	    	  =>$data['modl'],
						       "d_modul_tahun"            =>$data['tahun'],
						       "q_modul_nomormax"  		  =>$data[nomor]);
		 $where[] = "i_orgb  = '".$data['unitkr']."'";
         $where[] = "c_modul = '".$data['modl']."'";
         $where[] = "d_modul_tahun = '".$data['tahun']."'"; 		 
		
	     $db->update('e_modul_nomor_max_tr',$nomor_max_prm, $where);
		 $db->commit();
	     return 'sukses update mo max<br>';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>'; 
	     return 'gagal update nomax <br>';
	   }
	}

public function getRefAtkListAll($katBarang) {
        
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stock FROM e_ast_barang_atk_tr where c_atk_ctgr=? ORDER BY c_atk',$katBarang);
	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_atk"           =>(string)$result[$j]->c_atk,
								   "n_atk"           =>(string)$result[$j]->n_atk,
								   "n_atk_satuan"    =>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"     =>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"      =>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stock"      =>(string)$result[$j]->q_atk_stock);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getRefKatListBeli() {
       
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT distinct b.c_atk_ctgr, n_atk_ctgr 
									FROM e_ast_kategori_atk_tr a, e_ast_ajuanbeli_itematk_tm b
									WHERE a.c_atk_ctgr=b.c_atk_ctgr
									ORDER BY c_atk_ctgr');
									
	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_atk_ctgr"           =>(string)$result[$j]->c_atk_ctgr,
								   "n_atk_ctgr"           =>(string)$result[$j]->n_atk_ctgr);
								  
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getRefKatListAll() {
       
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT c_atk_ctgr,n_atk_ctgr FROM e_ast_kategori_atk_tr ORDER BY c_atk_ctgr');
	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_atk_ctgr"           =>(string)$result[$j]->c_atk_ctgr,
								   "n_atk_ctgr"           =>(string)$result[$j]->n_atk_ctgr);
								  
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	 /* ****************************************************************************************
	 * servis untuk Penghapusan atk .... 25 okt 2007
	 ***************************/
	 
	public function insertHapusAjuanAtkM(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("i_atk_ajuanhapus"         		=>$data['nohapus'],
	                           "d_atk_ajuanhapus"    	    	=>date("Y-m-d"),
						       "i_orgb"                         =>$data['unitkr'],
						       "c_atk_setju1hapus" 		        =>$data['status'],
							   "d_atk_setuju1hapus"   			=>date("Y-m-d"),
						       "i_entry"       		            =>$data['nuser'],
						       "d_entry"       		           	=>date("Y-m-d"));
	    
	     $db->insert('e_ast_hapus_atk_tm',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function deletHapusEditAjuanAtkM($nohapus) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $where[] = "i_atk_ajuanhapus = '". $nohapus ."'";
		 $db->delete('e_ast_hapus_atk_tm', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function updateSetujuHapusSbagAtkM($Unitkr,$nohapus,$setuju,$nuser) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->beginTransaction();
	     $atk_mast_prm = array("c_atk_setju1hapus"  		=>$setuju,
						       "d_atk_setuju1hapus"   		=>date("Y-m-d"),
						       "i_entry"       		    	=>$nuser,
						       "d_entry"       		    	=>date("Y-m-d"));
	    
 		 $where[] = "i_orgb = '". $Unitkr ."'";
		 $where[] = "i_atk_ajuanhapus = '". $nohapus ."'";
	     $db->update('e_ast_hapus_atk_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
		 		 
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	
	}
	
	public function updateNoSetujuHapusSbagAtkM(array $data) {
		//echo '$Unitkr,$nohapus,$nosetuju'.$Unitkr.$nohapus.$nosetuju;
		
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->beginTransaction();
	     $atk_mast_prm = array("i_atk_setuju1hapus"  		=>$data['nosetuju'],
						       "d_atk_setuju1hapus"   		=>date("Y-m-d"),
							   "i_atk_nipsetuju1hapus"   	=>$data['nip'],
							   "e_alasan"   				=>$data['keterangan'],
						       "i_entry"       		    	=>$data['nuser'],
						       "d_entry"       		    	=>date("Y-m-d"));
	    
		 $where[] = "i_orgb = '".$data['unitkr']."'";
		 $where[] = "i_atk_ajuanhapus = '".$data['nohapus']."'";
	     $db->update('e_ast_hapus_atk_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
		 		 
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	
	}
	
	public function updateNoSetujuHapusKabagAtkM(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->beginTransaction();
	     $atk_mast_prm = array("i_atk_setuju2hapus"  		=> $data['nosetuju'],
						       "d_atk_setuju2hapus"   		=>date("Y-m-d"),
							   "i_atk_nipsetuju2hapus"   	=>$data['nip'],
							   "c_atk_setuju2hapus"   		=>$data['setuju'],
							   "e_alasan"					=>$data['keterangan'],
						       "i_entry"       		    	=>$data['nuser'],
						       "d_entry"       		    	=>date("Y-m-d"));
	    
		 $where[] = "i_orgb  =  '".trim($data['unitkr'])."'";
		 $where[] = "i_atk_ajuanhapus  =  '".trim($data['nohapus'])."'";
	     $db->update('e_ast_hapus_atk_tm',$atk_mast_prm, $where);
		 
		 $db->commit();
	     return 'sukses';
		 		 
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	
	}
	
	
	public function updateSetujuHapusKabagAtkM($Unitkr,$nohapus,$setuju,$nuser) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->beginTransaction();
	     $atk_mast_prm = array("c_atk_setuju2hapus"  		=>$setuju,
						       "d_atk_setuju2hapus"   		=>date("Y-m-d"),
						       "i_entry"       		    	=>$nuser,
						       "d_entry"       		    	=>date("Y-m-d"));
	    
 		 $where[] = "i_orgb = '". $Unitkr ."'";
		 $where[] = "i_atk_ajuanhapus = '". $nohapus ."'";
	     $db->update('e_ast_hapus_atk_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
		 		 
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	
	}
	
	public function updateProsesHapusAtkM3($Unitkr,$nohapus,$nuser) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->beginTransaction();
	     $atk_mast_prm = array("c_atk_setuju2hapus"  		=>$setuju,
						       "d_atk_setuju2hapus"   		=>date("Y-m-d"),
							   "d_atk_hapus"   				=>date("Y-m-d"),
						       "i_entry"       		    	=>$nuser,
						       "d_entry"       		    	=>date("Y-m-d"));
	    
 		 $where[] = "i_orgb = '". $Unitkr ."'";
		 $where[] = "i_atk_ajuanhapus = '". $nohapus ."'";
	     $db->update('e_ast_hapus_atk_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
		 		 
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	
	}
	public function updateProsesHapusAtkM($Unitkr,$nohapus,$nuser) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->beginTransaction();
	     $atk_mast_prm = array("d_atk_hapus"   				=>date("Y-m-d"),
						       "i_entry"       		    	=>$nuser,
						       "d_entry"       		    	=>date("Y-m-d"));
	    
 		 $where[] = "i_orgb = '". $Unitkr ."'";
		 $where[] = "i_atk_ajuanhapus = '". $nohapus ."'";
	     $db->update('e_ast_hapus_atk_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
		 		 
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	
	}
	public function updateHapusAjuanAtkM($nohapus,$nuser) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->beginTransaction();
	     $atk_mast_prm = array("c_atk_setju1hapus"  		=>'B',
						       "d_atk_setuju1hapus"   		=>date("Y-m-d"),
						       "i_entry"       		    	=>$nuser,
						       "d_entry"       		    	=>date("Y-m-d"));
	     
		 $where[] = "i_atk_ajuanhapus = '". $nohapus ."'";
	     $db->update('e_ast_hapus_atk_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
		 		 
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	
	}
	
	//Ref Atk Kosong... 23okt 2007
	public function getRefAtkListKs($katBarang) {
        
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stock FROM e_ast_barang_atk_tr where c_atk_ctgr=? and q_atk_stock <= 0  ORDER BY c_atk',$katBarang);
	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_atk"           =>(string)$result[$j]->c_atk,
								   "n_atk"           =>(string)$result[$j]->n_atk,
								   "n_atk_satuan"    =>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"     =>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"      =>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stock"      =>(string)$result[$j]->q_atk_stock);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getRefAtkList($pageNumber,$itemPerPage,$katBarang,$namaBarang,$nohapus) {
		$namaBarang = strtoupper($namaBarang);
		$nbrg = '%'.$namaBarang.'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $katBarang;
			$where[] = $nbrg;
			$where[] = $nohapus;
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			 {
				//$hasilAkhir = $db->fetchOne("select count(*) from e_ast_barang_atk_tr 
				//							 where c_atk_ctgr=? and n_atk like ? ",$where);
											 
				$result = $db->fetchAll("SELECT a.c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stock 
										FROM e_ast_barang_atk_tr a where a.c_atk_ctgr=? and n_atk like ?
										and not exists(select d.i_atk_ajuanhapus,c.c_atk,c.c_atk_ctgr 
										from     e_ast_hapus_itematk_tm   c,    e_ast_hapus_atk_tm   d
										where c.i_atk_ajuanhapus = d.i_atk_ajuanhapus and a.c_atk = c.c_atk 
										and a.c_atk_ctgr = c.c_atk_ctgr and d.i_atk_ajuanhapus=?)
										ORDER BY c_atk",$where); 
			 
			 
				$jmlResult  = count($result);
				$hasilAkhir = $jmlResult;
			 }
			else
			 {
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT a.c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stock 
										FROM e_ast_barang_atk_tr a where a.c_atk_ctgr=? and n_atk like ?
										and not exists(select d.i_atk_ajuanhapus,c.c_atk,c.c_atk_ctgr 
										from     e_ast_hapus_itematk_tm   c,    e_ast_hapus_atk_tm   d
										where c.i_atk_ajuanhapus = d.i_atk_ajuanhapus and a.c_atk = c.c_atk 
										and a.c_atk_ctgr = c.c_atk_ctgr and d.i_atk_ajuanhapus=?)
										ORDER BY c_atk
									limit $xLimit offset $xOffset",$where); 
			 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("c_atk"         =>(string)$result[$j]->c_atk,
								   "n_atk"           	=>(string)$result[$j]->n_atk,
								   "n_atk_satuan"    	=>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"     	=>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"      	=>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stock"      	=>(string)$result[$j]->q_atk_stock);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
	public function getRefAtkList2($katBarang) {
        
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stock FROM e_ast_barang_atk_tr where c_atk_ctgr=? and q_atk_stock > 0  ORDER BY c_atk',$katBarang);
	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_atk"           =>(string)$result[$j]->c_atk,
								   "n_atk"           =>(string)$result[$j]->n_atk,
								   "n_atk_satuan"    =>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"     =>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"      =>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stock"      =>(string)$result[$j]->q_atk_stock);
								  
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	//Ref Atk Sedia... 25okt 2007
	public function getRefAtkListHapus($status) {
       $status='A';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT distinct b.c_atk,b.c_atk_ctgr,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stock 
									FROM e_ast_barang_atk_tr a, e_ast_ajuanbeli_itematk_tm b, e_ast_ajuanbeli_atk_tm c
									where c.c_atk_setuju=? and b.c_atk_ctgr=a.c_atk_ctgr  and b.c_atk=a.c_atk  
									and b.i_atk_ajuan=c.i_atk_ajuan
									ORDER BY c_atk',$status);
	     $jmlResult = count($result);
		 
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_atk"           =>(string)$result[$j]->c_atk,
								   "c_atk_ctgr"      =>(string)$result[$j]->c_atk_ctgr,
								   "n_atk"           =>(string)$result[$j]->n_atk,
								   "n_atk_merek"     =>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"      =>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stock"      =>(string)$result[$j]->q_atk_stock,
								   "n_atk_satuan"    =>(string)$result[$j]->n_atk_satuan);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	//======================================melihat penghapusan =====================================================================
	
	public function queryPenghapusanByOrgTU($pageNumber, $itemPerPage, $stat, $unitkr) {
		$status=$stat;
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $unitkr;
			$where[] = $status;
			//echo "TEST CONN ".$db->getConnection()."<br>";
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$result = $db->fetchAll("SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										  where a.i_orgb like ? and  a.c_atk_setju1hapus = ? and a.i_orgb=b.i_orgb 
										  and c_atk_setuju2hapus is null",$where);
			 
		         $jmlResult = count($result);
				 $hasilAkhir = $jmlResult;
			 }
			 else
			 {
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										  where a.i_orgb like ? and  a.c_atk_setju1hapus = ? and a.i_orgb=b.i_orgb 
										  and c_atk_setuju2hapus is null
										  order by i_atk_ajuanhapus
										  limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuanhapus"      =>(string)$result[$j]->i_atk_ajuanhapus,
											"d_atk_ajuanhapus"      =>(string)$result[$j]->d_atk_ajuanhapus,
											"i_orgb" 				=>(string)$result[$j]->i_orgb,
											"n_orgb" 				=>(string)$result[$j]->n_orgb);
								  
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function queryPenghapusanByOrgPrnt($pageNumber, $itemPerPage, $stat, $unitkr) {
		$status=$stat;
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $unitkr;
				 $where[] = $status;
				 $where[] = $unitkr;
				 $where[] = $status;
				 $where[] = $status;
				 $where[] = $unitkr;
				 $where[] = $status;
				 $where[] = $unitkr;
				 $where[] = $status;
				 $where[] = $unitkr;
			//echo "TEST CONN ".$db->getConnection()."<br>";
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$result = $db->fetchAll("SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										  where a.i_orgb = ? and  a.c_atk_setju1hapus = ? and a.i_orgb=b.i_orgb 
										  and c_atk_setuju2hapus is null
										  
										  UNION
										  SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										  where i_orgb_parent  = ? and  a.c_atk_setju1hapus = ? and a.i_orgb=b.i_orgb 
										  and c_atk_setuju2hapus is null
										  
										  UNION
										  SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm C 
										  where a.c_atk_setju1hapus = ? and a.i_orgb=C.i_orgb
											and exists ( select * 
											from e_org_0_0_tm  B
											where B.i_orgb_parent  = ?
											         and B.i_orgb = C.i_orgb_parent
											             )  
										  and c_atk_setuju2hapus is null
										  
										  UNION
										  SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										  where a.c_atk_setju1hapus = ? and a.i_orgb=b.i_orgb
											and i_orgb_parent   
													in ( select A.i_orgb from e_org_0_0_tm A
														 where exists 
													       ( select * from e_org_0_0_tm  B
													         where B.i_orgb_parent  = ?
													          and B.i_orgb = A.i_orgb_parent
													        )                 
													    )
										  and c_atk_setuju2hapus is null
										  
										  UNION
										  SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										  where a.c_atk_setju1hapus = ? and a.i_orgb=b.i_orgb
													and i_orgb_parent 
													in ( select C.i_orgb from e_org_0_0_tm C
													      where C.i_orgb_parent 
													      in ( select A.i_orgb from e_org_0_0_tm A
													            where exists 
														        ( select * from e_org_0_0_tm  B
													    	          where B.i_orgb_parent  = ?
													        	          and B.i_orgb = A.i_orgb_parent
														        )                 
													    	)
													    )
										  and c_atk_setuju2hapus is null",$where);
			 
		         $jmlResult = count($result);
				 $hasilAkhir = $jmlResult;
			 }
			 else
			 {
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										  where a.i_orgb = ? and  a.c_atk_setju1hapus = ? and a.i_orgb=b.i_orgb 
										  and c_atk_setuju2hapus is null
										  
										  UNION
										  SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										  where i_orgb_parent  = ? and  a.c_atk_setju1hapus = ? and a.i_orgb=b.i_orgb 
										  and c_atk_setuju2hapus is null
										  
										  UNION
										  SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm C 
										  where a.c_atk_setju1hapus = ? and a.i_orgb=C.i_orgb
											and exists ( select * 
											from e_org_0_0_tm  B
											where B.i_orgb_parent  = ?
											         and B.i_orgb = C.i_orgb_parent
											             )  
										  and c_atk_setuju2hapus is null
										  
										  UNION
										  SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										  where a.c_atk_setju1hapus = ? and a.i_orgb=b.i_orgb
											and i_orgb_parent   
													in ( select A.i_orgb from e_org_0_0_tm A
														 where exists 
													       ( select * from e_org_0_0_tm  B
													         where B.i_orgb_parent  = ?
													          and B.i_orgb = A.i_orgb_parent
													        )                 
													    )
										  and c_atk_setuju2hapus is null
										  
										  UNION
										  SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										  where a.c_atk_setju1hapus = ? and a.i_orgb=b.i_orgb
													and i_orgb_parent 
													in ( select C.i_orgb from e_org_0_0_tm C
													      where C.i_orgb_parent 
													      in ( select A.i_orgb from e_org_0_0_tm A
													            where exists 
														        ( select * from e_org_0_0_tm  B
													    	          where B.i_orgb_parent  = ?
													        	          and B.i_orgb = A.i_orgb_parent
														        )                 
													    	)
													    )
										  and c_atk_setuju2hapus is null
										  
										  order by i_atk_ajuanminta
										  limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuanhapus"      =>(string)$result[$j]->i_atk_ajuanhapus,
											"d_atk_ajuanhapus"      =>(string)$result[$j]->d_atk_ajuanhapus,
											"i_orgb" 				=>(string)$result[$j]->i_orgb,
											"n_orgb" 				=>(string)$result[$j]->n_orgb);
								  
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	//===================kabag perlengkapan =========================================================================
	
	public function queryPenghapusanKabagByOrgTU($pageNumber, $itemPerPage, $stat, $unitkr) {
		$status=$stat;
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $unitkr;
			$where[] = $status;
			//echo "TEST CONN ".$db->getConnection()."<br>";
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$result = $db->fetchAll("SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										  where a.i_orgb like ? and  a.c_atk_setuju2hapus = ? and a.i_orgb=b.i_orgb 
										  ",$where);
			 
		         $jmlResult = count($result);
				 $hasilAkhir = $jmlResult;
			 }
			 else
			 {
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										  where a.i_orgb like ? and  a.c_atk_setuju2hapus = ? and a.i_orgb=b.i_orgb 
										  order by i_atk_ajuanhapus
										  limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuanhapus"      =>(string)$result[$j]->i_atk_ajuanhapus,
											"d_atk_ajuanhapus"      =>(string)$result[$j]->d_atk_ajuanhapus,
											"i_orgb" 				=>(string)$result[$j]->i_orgb,
											"n_orgb" 				=>(string)$result[$j]->n_orgb);
								  
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function queryPenghapusanKabagByOrgPrnt($pageNumber, $itemPerPage, $stat, $unitkr) {
		$status=$stat;
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $unitkr;
				 $where[] = $status;
				 $where[] = $unitkr;
				 $where[] = $status;
				 $where[] = $status;
				 $where[] = $unitkr;
				 $where[] = $status;
				 $where[] = $unitkr;
				 $where[] = $status;
				 $where[] = $unitkr;
			//echo "TEST CONN ".$db->getConnection()."<br>";
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$result = $db->fetchAll("SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										  where a.i_orgb = ? and  a.c_atk_setuju2hapus = ? and a.i_orgb=b.i_orgb 
										  
										  UNION
										  SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										  where i_orgb_parent  = ? and  a.c_atk_setuju2hapus = ? and a.i_orgb=b.i_orgb 
										  
										  UNION
										  SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm C 
										  where a.c_atk_setuju2hapus = ? and a.i_orgb=C.i_orgb
											and exists ( select * 
											from e_org_0_0_tm  B
											where B.i_orgb_parent  = ?
											         and B.i_orgb = C.i_orgb_parent
											             )  
										  
										  UNION
										  SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										  where a.c_atk_setuju2hapus = ? and a.i_orgb=b.i_orgb
											and i_orgb_parent   
													in ( select A.i_orgb from e_org_0_0_tm A
														 where exists 
													       ( select * from e_org_0_0_tm  B
													         where B.i_orgb_parent  = ?
													          and B.i_orgb = A.i_orgb_parent
													        )                 
													    )
										  
										  UNION
										  SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										  where a.c_atk_setuju2hapus = ? and a.i_orgb=b.i_orgb
													and i_orgb_parent 
													in ( select C.i_orgb from e_org_0_0_tm C
													      where C.i_orgb_parent 
													      in ( select A.i_orgb from e_org_0_0_tm A
													            where exists 
														        ( select * from e_org_0_0_tm  B
													    	          where B.i_orgb_parent  = ?
													        	          and B.i_orgb = A.i_orgb_parent
														        )                 
													    	)
													    )
										  ",$where);
			 
		         $jmlResult = count($result);
				 $hasilAkhir = $jmlResult;
			 }
			 else
			 {
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										  where a.i_orgb = ? and  a.c_atk_setuju2hapus = ? and a.i_orgb=b.i_orgb 
										  
										  UNION
										  SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										  where i_orgb_parent  = ? and  a.c_atk_setuju2hapus = ? and a.i_orgb=b.i_orgb 
										  
										  UNION
										  SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm C 
										  where a.c_atk_setuju2hapus = ? and a.i_orgb=C.i_orgb
											and exists ( select * 
											from e_org_0_0_tm  B
											where B.i_orgb_parent  = ?
											         and B.i_orgb = C.i_orgb_parent
											             )  
										  
										  UNION
										  SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										  where a.c_atk_setuju2hapus = ? and a.i_orgb=b.i_orgb
											and i_orgb_parent   
													in ( select A.i_orgb from e_org_0_0_tm A
														 where exists 
													       ( select * from e_org_0_0_tm  B
													         where B.i_orgb_parent  = ?
													          and B.i_orgb = A.i_orgb_parent
													        )                 
													    )
										  
										  UNION
										  SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										  where a.c_atk_setuju2hapus = ? and a.i_orgb=b.i_orgb
													and i_orgb_parent 
													in ( select C.i_orgb from e_org_0_0_tm C
													      where C.i_orgb_parent 
													      in ( select A.i_orgb from e_org_0_0_tm A
													            where exists 
														        ( select * from e_org_0_0_tm  B
													    	          where B.i_orgb_parent  = ?
													        	          and B.i_orgb = A.i_orgb_parent
														        )                 
													    	)
													    )
										  
										  order by i_atk_ajuanminta
										  limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuanhapus"      =>(string)$result[$j]->i_atk_ajuanhapus,
											"d_atk_ajuanhapus"      =>(string)$result[$j]->d_atk_ajuanhapus,
											"i_orgb" 				=>(string)$result[$j]->i_orgb,
											"n_orgb" 				=>(string)$result[$j]->n_orgb);
								  
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	//===================Proses hapus =========================================================================
	
	public function queryPenghapusanProsesByOrgTU($pageNumber, $itemPerPage,  $unitkr) {
		$status=$stat;
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $unitkr;
			//$where[] = $status;
			//echo "TEST CONN ".$db->getConnection()."<br>";
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$result = $db->fetchAll("SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										  where a.i_orgb like ? and  d_atk_hapus is not null and a.i_orgb=b.i_orgb 
										  ",$where);
			 
		         $jmlResult = count($result);
				 $hasilAkhir = $jmlResult;
			 }
			 else
			 {
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb,i_atk_setuju2hapus,d_atk_hapus
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										  where a.i_orgb like ? and  d_atk_hapus is not null and a.i_orgb=b.i_orgb 
										  order by i_atk_ajuanhapus
										  limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuanhapus"      =>(string)$result[$j]->i_atk_ajuanhapus,
											"d_atk_ajuanhapus"      =>(string)$result[$j]->d_atk_ajuanhapus,
											"i_atk_setuju2hapus"	=>(string)$result[$j]->i_atk_setuju2hapus,
											"i_orgb" 				=>(string)$result[$j]->i_orgb,
											"n_orgb" 				=>(string)$result[$j]->n_orgb,
											"d_atk_hapus"			=>(string)$result[$j]->d_atk_hapus);
								  
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function queryPenghapusanProsesByOrgPrnt($pageNumber, $itemPerPage, $unitkr) {
		$status=$stat;
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $unitkr;
				 $where[] = $unitkr;
				 $where[] = $unitkr;
				 $where[] = $unitkr;
				 $where[] = $unitkr;
			//echo "TEST CONN ".$db->getConnection()."<br>";
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$result = $db->fetchAll("SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										  where a.i_orgb = ? and  d_atk_hapus is not null and a.i_orgb=b.i_orgb 
										  
										  UNION
										  SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										  where i_orgb_parent  = ? and  d_atk_hapus is not null and a.i_orgb=b.i_orgb 
										  
										  UNION
										  SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm C 
										  where d_atk_hapus is not null and a.i_orgb=C.i_orgb
											and exists ( select * 
											from e_org_0_0_tm  B
											where B.i_orgb_parent  = ?
											         and B.i_orgb = C.i_orgb_parent
											             )  
										  
										  UNION
										  SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										  where d_atk_hapus is not null and a.i_orgb=b.i_orgb
											and i_orgb_parent   
													in ( select A.i_orgb from e_org_0_0_tm A
														 where exists 
													       ( select * from e_org_0_0_tm  B
													         where B.i_orgb_parent  = ?
													          and B.i_orgb = A.i_orgb_parent
													        )                 
													    )
										  
										  UNION
										  SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										  where d_atk_hapus is not null and a.i_orgb=b.i_orgb
													and i_orgb_parent 
													in ( select C.i_orgb from e_org_0_0_tm C
													      where C.i_orgb_parent 
													      in ( select A.i_orgb from e_org_0_0_tm A
													            where exists 
														        ( select * from e_org_0_0_tm  B
													    	          where B.i_orgb_parent  = ?
													        	          and B.i_orgb = A.i_orgb_parent
														        )                 
													    	)
													    )
										  ",$where);
			 
		         $jmlResult = count($result);
				 $hasilAkhir = $jmlResult;
			 }
			 else
			 {
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb,i_atk_setuju2hapus,d_atk_hapus
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										  where a.i_orgb = ? and  d_atk_hapus is not null and a.i_orgb=b.i_orgb 
										  
										  UNION
										  SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb,i_atk_setuju2hapus,d_atk_hapus
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										  where i_orgb_parent  = ? and  d_atk_hapus is not null and a.i_orgb=b.i_orgb 
										  
										  UNION
										  SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb,i_atk_setuju2hapus,d_atk_hapus
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm C 
										  where d_atk_hapus is not null and a.i_orgb=C.i_orgb
											and exists ( select * 
											from e_org_0_0_tm  B
											where B.i_orgb_parent  = ?
											         and B.i_orgb = C.i_orgb_parent
											             )  
										  
										  UNION
										  SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb,i_atk_setuju2hapus,d_atk_hapus
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										  where d_atk_hapus is not null and a.i_orgb=b.i_orgb
											and i_orgb_parent   
													in ( select A.i_orgb from e_org_0_0_tm A
														 where exists 
													       ( select * from e_org_0_0_tm  B
													         where B.i_orgb_parent  = ?
													          and B.i_orgb = A.i_orgb_parent
													        )                 
													    )
										  
										  UNION
										  SELECT a.i_atk_ajuanhapus,d_atk_ajuanhapus ,a.i_orgb,n_orgb,i_atk_setuju2hapus,d_atk_hapus
										  FROM e_ast_hapus_atk_tm a, e_org_0_0_tm b 
										  where d_atk_hapus is not null and a.i_orgb=b.i_orgb
													and i_orgb_parent 
													in ( select C.i_orgb from e_org_0_0_tm C
													      where C.i_orgb_parent 
													      in ( select A.i_orgb from e_org_0_0_tm A
													            where exists 
														        ( select * from e_org_0_0_tm  B
													    	          where B.i_orgb_parent  = ?
													        	          and B.i_orgb = A.i_orgb_parent
														        )                 
													    	)
													    )
										  
										  order by i_atk_ajuanminta
										  limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuanhapus"      =>(string)$result[$j]->i_atk_ajuanhapus,
											"d_atk_ajuanhapus"      =>(string)$result[$j]->d_atk_ajuanhapus,
											"i_atk_setuju2hapus"	=>(string)$result[$j]->i_atk_setuju2hapus,
											"i_orgb" 				=>(string)$result[$j]->i_orgb,
											"n_orgb" 				=>(string)$result[$j]->n_orgb,
											"d_atk_hapus"			=>(string)$result[$j]->d_atk_hapus);
								  
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	//=======================cek unit tu ==================================================================
	
	public function cekUnitTU($unitkr) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			
			 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 	
				$TU 	= $db->fetchCol("SELECT i_orgb_tu FROM e_org_0_0_tm where i_orgb = ? and i_orgb_tu is not null",$unitkr);
				$unitTU = $TU[0];
				
				return $unitTU;
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	//===============================cek jabatan=============================================================================
	public function cekPejabat($unitjabatan) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
			$where[]=$unitjabatan;
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			 
				$i_orgb = $db->fetchCol("select b.i_orgb  
										from e_sdm_jabatan_0_tm A,  e_sdm_pegawai_0_tm B
										where a.i_peg_nip = b.i_peg_nip
										and b.c_unit_kerja = ?
										and b.c_unit_kerja = a.c_jabatan",$where);
				
				$jabatan = $i_orgb[0];
				
	     return $jabatan;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
}	
?>