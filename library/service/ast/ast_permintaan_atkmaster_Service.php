<?php
class ast_permintaan_atkmaster_service {
   
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
	 
	public function getAtkDetailList($nopeng) {	   
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$query="SELECT b.c_atk_ctgr,b.c_atk,q_atk_beli,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stock ,q_atk_stockmin,q_atk_setujubeli".
			       " FROM e_ast_barang_atk_tr a, e_ast_ajuanbeli_itematk_tm b where i_atk_ajuan = ? and a.c_atk = b.c_atk";
			//echo "Querynya  : ". $query;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$result = $db->fetchAll($query,$nopeng);
			$jmlResult = count($result);		
			for ($j = 0; $j < $jmlResult; $j++) {		
				$hasilAkhir[$j] = array("c_atk_ctgr"	=>(string)$result[$j]->c_atk_ctgr,
					"c_atk"                  =>(string)$result[$j]->c_atk,
					"q_atk_beli"             =>(string)$result[$j]->q_atk_beli,
					"n_atk"                  =>(string)$result[$j]->n_atk,
					"n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
					"n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
					"n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
					"q_atk_stock"            =>(string)$result[$j]->q_atk_stock, 
					"q_atk_stockmin"         =>(string)$result[$j]->q_atk_stockmin, 
					"q_atk_setujubeli"       =>(string)$result[$j]->q_atk_setujubeli
					); 
				}					 
			return $hasilAkhir;
		} 
		catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}
	
	//==============================================================================================================================
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
	public function getRefAtkList($pageNumber,$itemPerPage,$katBarang,$namaBarang,$nopeng) {
		$namaBarang = strtoupper($namaBarang);
		$nbrg = '%'.$namaBarang.'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $katBarang;
			$where[] = $nbrg;
			$where[] = $nopeng;
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$result = $db->fetchAll("SELECT a.c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stock,q_atk_stockmin 
										FROM e_ast_barang_atk_tr a where a.c_atk_ctgr=? and n_atk like ? 
										and not exists(select d.i_atk_ajuanminta,c.c_atk,c.c_atk_ctgr 
										from   e_ast_minta_itematk_tm  c,  e_ast_minta_atk_tm  d
										where c.i_atk_ajuanminta = d.i_atk_ajuanminta and a.c_atk = c.c_atk 
										and a.c_atk_ctgr = c.c_atk_ctgr and d.i_atk_ajuanminta=?)
										ORDER BY c_atk",$where); 
			 
			 
				$jmlResult  = count($result);
				$hasilAkhir = $jmlResult;
			 }
			else
			 {
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT a.c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stock,q_atk_stockmin 
										FROM e_ast_barang_atk_tr a where a.c_atk_ctgr=? and n_atk like ? 
										and not exists(select d.i_atk_ajuanminta,c.c_atk,c.c_atk_ctgr 
										from   e_ast_minta_itematk_tm  c,  e_ast_minta_atk_tm  d
										where c.i_atk_ajuanminta = d.i_atk_ajuanminta and a.c_atk = c.c_atk 
										and a.c_atk_ctgr = c.c_atk_ctgr and d.i_atk_ajuanminta=?)
										ORDER BY c_atk
									 limit $xLimit offset $xOffset",$where); 
			 
			$jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("c_atk"         =>(string)$result[$j]->c_atk,
								   "n_atk"           	=>(string)$result[$j]->n_atk,
								   "n_atk_satuan"    	=>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"     	=>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"      	=>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stock"      	=>(string)$result[$j]->q_atk_stock,
								   "q_atk_stockmin"		=>(string)$result[$j]->q_atk_stockmin);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
	
	//========getRefAtkList_Old => diganti dg gabungan stock kosong & tersedia ---- 21 mei 08 ==============
	public function getRefAtkList_Old($pageNumber,$itemPerPage,$katBarang,$namaBarang,$nopeng) {
		$namaBarang = strtoupper($namaBarang);
		$nbrg = '%'.$namaBarang.'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $katBarang;
			$where[] = $nbrg;
			$where[] = $nopeng;
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			 {
				//$hasilAkhir = $db->fetchOne("select count(*) from e_ast_barang_atk_tr 
				//							 where c_atk_ctgr=? and n_atk like ? ",$where);
											 
				$result = $db->fetchAll("SELECT a.c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stock,q_atk_stockmin 
										FROM e_ast_barang_atk_tr a where a.c_atk_ctgr=? and n_atk like ? and q_atk_stock > 0
										and not exists(select d.i_atk_ajuanminta,c.c_atk,c.c_atk_ctgr 
										from   e_ast_minta_itematk_tm  c,  e_ast_minta_atk_tm  d
										where c.i_atk_ajuanminta = d.i_atk_ajuanminta and a.c_atk = c.c_atk 
										and a.c_atk_ctgr = c.c_atk_ctgr and d.i_atk_ajuanminta=?)
										ORDER BY c_atk",$where); 
			 
			 
				$jmlResult  = count($result);
				$hasilAkhir = $jmlResult;
			 }
			else
			 {
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT a.c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stock,q_atk_stockmin 
										FROM e_ast_barang_atk_tr a where a.c_atk_ctgr=? and n_atk like ? and q_atk_stock > 0
										and not exists(select d.i_atk_ajuanminta,c.c_atk,c.c_atk_ctgr 
										from   e_ast_minta_itematk_tm  c,  e_ast_minta_atk_tm  d
										where c.i_atk_ajuanminta = d.i_atk_ajuanminta and a.c_atk = c.c_atk 
										and a.c_atk_ctgr = c.c_atk_ctgr and d.i_atk_ajuanminta=?)
										ORDER BY c_atk
									limit $xLimit offset $xOffset",$where); 
			 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("c_atk"         =>(string)$result[$j]->c_atk,
								   "n_atk"           	=>(string)$result[$j]->n_atk,
								   "n_atk_satuan"    	=>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"     	=>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"      	=>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stock"      	=>(string)$result[$j]->q_atk_stock,
								   "q_atk_stockmin"		=>(string)$result[$j]->q_atk_stockmin);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
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

	public function queryDaftarAtkM($unitkr) {
       $status='Y';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_atk_ajuan,d_atk_ajuan FROM e_ast_ajuanbeli_atk_tm where i_orgb=? and c_atk_setuju=?',$where); 
         $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_atk_ajuan"           =>(string)$result[$j]->i_atk_ajuan,
								   "d_atk_ajuan"           =>(string)$result[$j]->d_atk_ajuan);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	/* ************
	 * fungsi untuk memasukan data PENERIMAAN LANGSUNG ATK  ke tabel 'e_ast_terima_atk_tm'
	 ***************************/
	 public function queryAjuanMintaAtkM($pageNumber, $itemPerPage, $unitkr) {
		$status='A';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $unitkr;
			$where[] = $status;
			//echo "TEST CONN ".$db->getConnection()."<br>";
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$result = $db->fetchAll("SELECT distinct a.i_atk_ajuanminta,d_atk_ajuanminta FROM e_ast_minta_itematk_tm a,
										  e_ast_minta_atk_tm b, e_ast_barang_atk_tr c
										  where i_orgb=? and c_atk_setujutu=? and a.i_atk_ajuanminta = b.i_atk_ajuanminta
										  and a.c_atk=c.c_atk ",$where);
			 
		         $jmlResult = count($result);
				 $hasilAkhir = $jmlResult;
			 }
			 else
			 {
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT distinct a.i_atk_ajuanminta,d_atk_ajuanminta FROM e_ast_minta_itematk_tm a,
										  e_ast_minta_atk_tm b, e_ast_barang_atk_tr c
										  where i_orgb=? and c_atk_setujutu=? and a.i_atk_ajuanminta = b.i_atk_ajuanminta
										  and a.c_atk=c.c_atk 
										  order by i_atk_ajuanminta
										  limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuanminta"           =>(string)$result[$j]->i_atk_ajuanminta,
											"d_atk_ajuanminta"           =>(string)$result[$j]->d_atk_ajuanminta);
								  
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	//queryAjuanMintaAtkM_Old -> diganti dengan gabungan stok kosong & tersedia =============================================
	 public function queryAjuanMintaAtkM_Old($pageNumber, $itemPerPage, $unitkr) {
		$status='A';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $unitkr;
			$where[] = $status;
			//echo "TEST CONN ".$db->getConnection()."<br>";
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				/*$hasilAkhir = $db->fetchOne("select distinct count(*) 
											FROM e_ast_minta_itematk_tm a,
											  e_ast_minta_atk_tm b, e_ast_barang_atk_tr c
											  where i_orgb=? and c_atk_setujutu=? and a.i_atk_ajuanminta = b.i_atk_ajuanminta
											  and a.c_atk=c.c_atk and c.q_atk_stock > 0",$where);
											  */
				$result = $db->fetchAll("SELECT distinct a.i_atk_ajuanminta,d_atk_ajuanminta FROM e_ast_minta_itematk_tm a,
										  e_ast_minta_atk_tm b, e_ast_barang_atk_tr c
										  where i_orgb=? and c_atk_setujutu=? and a.i_atk_ajuanminta = b.i_atk_ajuanminta
										  and a.c_atk=c.c_atk and c.q_atk_stock > 0",$where);
			 
		         $jmlResult = count($result);
				 $hasilAkhir = $jmlResult;
			 }
			 else
			 {
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT distinct a.i_atk_ajuanminta,d_atk_ajuanminta FROM e_ast_minta_itematk_tm a,
										  e_ast_minta_atk_tm b, e_ast_barang_atk_tr c
										  where i_orgb=? and c_atk_setujutu=? and a.i_atk_ajuanminta = b.i_atk_ajuanminta
										  and a.c_atk=c.c_atk and c.q_atk_stock > 0
										  order by i_atk_ajuanminta
										  limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuanminta"           =>(string)$result[$j]->i_atk_ajuanminta,
											"d_atk_ajuanminta"           =>(string)$result[$j]->d_atk_ajuanminta);
								  
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	public function queryAjuanMintaAtkM3($pageNumber, $itemPerPage, $unitkr) {
		$status='A';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $unitkr;
			$where[] = $status;
			//echo "TEST CONN ".$db->getConnection()."<br>";
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$hasilAkhir = $db->fetchOne("select count(*) 
											FROM e_ast_minta_atk_tm where i_orgb=? and c_atk_setujutu=?",$where);
			 }
			 else
			 {
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT i_atk_ajuanminta,d_atk_ajuanminta FROM e_ast_minta_atk_tm where i_orgb=? and c_atk_setujutu=?
										  order by i_atk_ajuanminta
										  limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuanminta"           =>(string)$result[$j]->i_atk_ajuanminta,
											"d_atk_ajuanminta"           =>(string)$result[$j]->d_atk_ajuanminta);
								  
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	 public function queryAjuanMintaAtkM2($unitkr) {
       $status='A';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_atk_ajuanminta,d_atk_ajuanminta FROM e_ast_minta_atk_tm where i_orgb=? and c_atk_setujutu=?',$where); 
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_atk_ajuanminta"           =>(string)$result[$j]->i_atk_ajuanminta,
								   "d_atk_ajuanminta"           =>(string)$result[$j]->d_atk_ajuanminta);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	public function insertAjuanMIntaAtkM(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();		
	     $atk_mast_prm = array("i_atk_ajuanminta"         		=>$data['nopeng'],
	                           "d_atk_ajuanminta"    	    	=>date("Y-m-d"),
						       "i_orgb"                         =>$data['unitkr'],
						       "c_atk_setujutu" 		        =>$data['status'],
						       "e_atk_setujutu"   	         	=>$data['kettu'],
							   "c_atk_setujuplkp" 		        =>$data['statusplkp'],
						       "e_atk_setujuplkp"   	    	=>$data['ketplkp'],
						       "i_entry"       		            =>$data['nuser'],
						       "d_entry"       		           =>date("Y-m-d"));
	    
	     $db->insert('e_ast_minta_atk_tm',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function deletAjuanMintaAtkM($nopeng) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $where[] = "i_atk_ajuanminta = '". $nopeng ."'";
		 $db->delete('e_ast_minta_atk_tm', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	//  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++	
	public function queryVerifikasiAtkM($unitkr) {
       $status='B';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_atk_terima,d_atk_terima FROM e_ast_terima_atk_tm 
						where i_orgb=? and c_atk_setuju=? ORDER BY i_atk_terima',$where);
		 
         $jmlResult = count($result);
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_atk_terima"           =>(string)$result[$j]->i_atk_terima,
								   "d_atk_terima"           =>(string)$result[$j]->d_atk_terima);
								  
								  
							       
		
		 }
        }
        
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	//  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++	
	public function queryMonitoringAtkM($unitkr) {
       $status='Y';// utk sementara bisa tampil pake A, sebenarna : status Y
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_atk_kwtbeli,d_atk_beli FROM e_ast_terima_atk_tm 
						where i_orgb=? and c_atk_setuju=? ORDER BY i_atk_terima',$where);
		 
         $jmlResult = count($result);
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_atk_kwtbeli"           =>(string)$result[$j]->i_atk_kwtbeli,
								   "d_atk_beli"           =>(string)$result[$j]->d_atk_beli);
								  
		
		}
        }
        
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function querySetujuTuMintaAtkM($pageNumber, $itemPerPage,$unitkr) {
		$status='B'; 
			
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			
			 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 	
				$TU 	= $db->fetchCol("SELECT i_orgb_tu FROM e_org_0_0_tm where i_orgb = ? and i_orgb_tu is not null",$unitkr);
				$unitTU = $TU[0];
				
				if($unitTU !=''){
					if (substr($unitTU,0,2) == 'DP' ){
			               $unitTULike = substr($unitTU,0,3).'%';
			        }else{
			               $unitTULike = substr($unitTU,0,2).'%';
			        }

					$where[] = $status;
					$where[] = $unitTULike;
					$where[] = $unitkr ;
					
					 if(($pageNumber==0) && ($itemPerPage==0))
					 {
						$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
													 where a.i_orgb=b.i_orgb and (c_atk_setujutu=? or c_atk_setujutu='' or c_atk_setujutu isnull)
													 and ( a.i_orgb like ? or a.i_orgb like ?)
													 and a.i_orgb not in(
														'SK1404','SK1405','SK1406','SK1407','SK1408','SK1409',
														'SK1410','SK1411','SK1412')
													 ", $where);
														
					 }
					 else
					 {
				
						 $xLimit=$itemPerPage;
						 $xOffset=($pageNumber-1)*$itemPerPage;		
						 
						 $result = $db->fetchAll("SELECT a.i_orgb, b.n_orgb, i_atk_ajuanminta,d_atk_ajuanminta 
													FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
													where a.i_orgb=b.i_orgb and (c_atk_setujutu=? or c_atk_setujutu='' or c_atk_setujutu isnull)
													and ( a.i_orgb like ? or a.i_orgb like ?)
													and a.i_orgb not in(
													'SK1404','SK1405','SK1406','SK1407','SK1408','SK1409',
													'SK1410','SK1411','SK1412')
													ORDER BY i_orgb
													limit $xLimit offset $xOffset", $where); 
						
						 $jmlResult = count($result);
						 
						 for ($j = 0; $j < $jmlResult; $j++) {
								$hasilAkhir[$j] = array("i_orgb"           			=>(string)$result[$j]->i_orgb,
													   "n_orgb"           			=>(string)$result[$j]->n_orgb,
													   "i_atk_ajuanminta"           =>(string)$result[$j]->i_atk_ajuanminta,
													   "d_atk_ajuanminta"           =>(string)$result[$j]->d_atk_ajuanminta);
					 }	
					}	 
					return $hasilAkhir;
			}else{
				return 0;
			}
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	
	
	public function querySetujuTuMintaAtkM3($pageNumber, $itemPerPage) {
		$status='B'; 
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $status;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$hasilAkhir = $db->fetchOne("select count(*) 
											FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
											where a.i_orgb=b.i_orgb and (c_atk_setujutu=? or c_atk_setujutu='' or c_atk_setujutu isnull) ",$where);
			 }
			 else
			 {
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT a.i_orgb, b.n_orgb, i_atk_ajuanminta,d_atk_ajuanminta 
											FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
											where a.i_orgb=b.i_orgb and (c_atk_setujutu=? or c_atk_setujutu='' or c_atk_setujutu isnull)ORDER BY a.i_orgb
											  limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_orgb"           			=>(string)$result[$j]->i_orgb,
										   "n_orgb"           			=>(string)$result[$j]->n_orgb,
										   "i_atk_ajuanminta"           =>(string)$result[$j]->i_atk_ajuanminta,
										   "d_atk_ajuanminta"           =>(string)$result[$j]->d_atk_ajuanminta);
								  
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
public function querySetujuTuMintaAtkM2() {
       $status='B'; 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     //$where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT a.i_orgb, b.n_orgb, i_atk_ajuanminta,d_atk_ajuanminta 
									FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
									where a.i_orgb=b.i_orgb and c_atk_setujutu=? ORDER BY a.i_orgb' ,$where); 
									
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) { 
		 
           $hasilAkhir[$j] = array("i_orgb"           			=>(string)$result[$j]->i_orgb,
								   "n_orgb"           			=>(string)$result[$j]->n_orgb,
								   "i_atk_ajuanminta"           =>(string)$result[$j]->i_atk_ajuanminta,
								   "d_atk_ajuanminta"           =>(string)$result[$j]->d_atk_ajuanminta);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	public function updateSetujuTuMintaAtkM($noajuan,$setuju,$keterangan,$nuser) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->beginTransaction();
	     $atk_mast_prm = array("c_atk_setujutu"  		=>$setuju,
						       "e_atk_setujutu"   		=>$keterangan,
						       "i_entry"       		    =>$nuser,
						       "d_entry"       		    =>date("Y-m-d"));
	     
		 $where[] = "i_atk_ajuanminta = '". $noajuan ."'";
	     $db->update('e_ast_minta_atk_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
		 		 
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	
	}
	
	//
	//19 okt 2007 ******************Persetujuan Bag Perlengkapan Atk*********/
	public function querySetujuMintaAtkM($pageNumber, $itemPerPage) {
		$status='Y';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $status;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$hasilAkhir = $db->fetchOne("select count(*) 
											FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
											where a.i_orgb=b.i_orgb and (c_atk_setujutu=? 
											or a.i_orgb in(
													'SK1404','SK1405','SK1406','SK1407','SK1408','SK1409',
													'SK1410','SK1411','SK1412')
											)
											and (c_atk_setujuplkp is null  or c_atk_setujuplkp='')",$where);
			 }
			 else
			 {
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT a.i_orgb, b.n_orgb, i_atk_ajuanminta,d_atk_ajuanminta 
											FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
											where a.i_orgb=b.i_orgb and (c_atk_setujutu=? 
											or a.i_orgb in(
													'SK1404','SK1405','SK1406','SK1407','SK1408','SK1409',
													'SK1410','SK1411','SK1412')
											)
											and (c_atk_setujuplkp is null or c_atk_setujuplkp='')
											ORDER BY a.i_orgb
											  limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_orgb"           			=>(string)$result[$j]->i_orgb,
										   "n_orgb"           			=>(string)$result[$j]->n_orgb,
										   "i_atk_ajuanminta"           =>(string)$result[$j]->i_atk_ajuanminta,
										   "d_atk_ajuanminta"           =>(string)$result[$j]->d_atk_ajuanminta);
								  
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	 public function querySetujuMintaAtkM2() {
       $status='Y';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     //$where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT a.i_orgb, b.n_orgb, i_atk_ajuanminta,d_atk_ajuanminta 
									FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
									where a.i_orgb=b.i_orgb and c_atk_setujutu=? and c_atk_setujuplkp is null
									ORDER BY a.i_orgb' ,$where); 
									
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb"           			=>(string)$result[$j]->i_orgb,
								   "n_orgb"           			=>(string)$result[$j]->n_orgb,
								   "i_atk_ajuanminta"           =>(string)$result[$j]->i_atk_ajuanminta,
								   "d_atk_ajuanminta"           =>(string)$result[$j]->d_atk_ajuanminta);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	public function updateSetujuMintaAtkM($noajuan,$setuju,$keterangan,$nuser) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->beginTransaction();
	     $atk_mast_prm = array("c_atk_setujuplkp"  		=>$setuju,
						       "e_atk_setujuplkp"   	=>$keterangan,
						       "i_entry"       		    =>$nuser,
						       "d_entry"       		    =>date("Y-m-d"));
	     
		 $where[] = "i_atk_ajuanminta = '". $noajuan ."'";
	     $db->update('e_ast_minta_atk_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
		 		 
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	
	}
	
	//Stock Kosong =========== 22 okt 2007
	public function updateAjuanMintaAtkM($nopeng,$nuser) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->beginTransaction();
	     $atk_mast_prm = array("c_atk_setujutu"  		=>'B',
						       "e_atk_setujutu"   		=>' ',
						       "i_entry"       		    =>$nuser,
						       "d_entry"       		    =>date("Y-m-d"));
	     
		 $where[] = "i_atk_ajuanminta = '". $nopeng ."'";
	     $db->update('e_ast_minta_atk_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
		 		 
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	
	}
	
	
	public function updateAjuanMintaAtkM_Baru($nopeng,$nuser,$org) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
	      // Ina : 28-11-2008 : Awal : Untuk Staff khusus permintaan ATK harus segera, maka tidak perlu lewat persetujuan TU dan kaSubBag Perlengkapan
		 if ((substr($org,0,2) == 'SA') || ($org=='SK1412') ) {
		    $setujuTU = 'Y';
		    $setujuPerlengkapan = 'Y';
		 } else {
		    $setujuTU = 'B';
		    $setujuPerlengkapan = null;
		 }
		 
		 // Ina : 28-11-2008 : Akhir
		 
		 $db->beginTransaction();
	     $atk_mast_prm = array("c_atk_setujutu"  		=>$setujuTU,
						       "e_atk_setujutu"   		=>' ',
							   "c_atk_setujuplkp"  		=>$setujuPerlengkapan,
						       "i_entry"       		    =>$nuser,
						       "d_entry"       		    =>date("Y-m-d"));
	     
		 $where[] = "i_atk_ajuanminta = '". $nopeng ."'";
	     $db->update('e_ast_minta_atk_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
		 		 
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	
	}
	
	//Ref Atk Kosong... 23okt 2007
	public function getRefAtkListKs($pageNumber,$itemPerPage,$katBarang,$namaBarang,$nopeng) {
		$namaBarang = strtoupper($namaBarang);
		$nbrg = '%'.$namaBarang.'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $katBarang;
			$where[] = $nbrg;
			$where[] = $nopeng;
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			 {
				//$hasilAkhir = $db->fetchOne("select count(*) from e_ast_barang_atk_tr 
				//							 where c_atk_ctgr=? and n_atk like ? ",$where);
											 
				$result = $db->fetchAll("SELECT a.c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stock 
										FROM e_ast_barang_atk_tr a where a.c_atk_ctgr=? and q_atk_stock <= 0 and n_atk like ?
										and not exists(select d.i_atk_ajuanminta,c.c_atk,c.c_atk_ctgr 
										from   e_ast_minta_itematk_tm  c,  e_ast_minta_atk_tm  d
										where c.i_atk_ajuanminta = d.i_atk_ajuanminta and a.c_atk = c.c_atk 
										and a.c_atk_ctgr = c.c_atk_ctgr and d.i_atk_ajuanminta=?)
										ORDER BY c_atk",$where); 
			 
			 
				$jmlResult  = count($result);
				$hasilAkhir = $jmlResult;
			 }
			else
			 {
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT a.c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stock 
										FROM e_ast_barang_atk_tr a where a.c_atk_ctgr=? and q_atk_stock <= 0 and n_atk like ?
										and not exists(select d.i_atk_ajuanminta,c.c_atk,c.c_atk_ctgr 
										from   e_ast_minta_itematk_tm  c,  e_ast_minta_atk_tm  d
										where c.i_atk_ajuanminta = d.i_atk_ajuanminta and a.c_atk = c.c_atk 
										and a.c_atk_ctgr = c.c_atk_ctgr and d.i_atk_ajuanminta=?)
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
	public function getRefAtkListKs2($katBarang) {
        
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
	//Ref Atk Sedia... 23okt 2007
	public function getRefAtkListSedia($katBarang) {
        
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
	//query  stock kosong ..... 23 okt 2007
	public function queryAjuanMintaAtkKsM($pageNumber, $itemPerPage, $unitkr) {
		$status='A';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $unitkr;
			$where[] = $status;
			//echo "TEST CONN ".$db->getConnection()."<br>";
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				/*$hasilAkhir = $db->fetchOne("select count(*) 
											FROM e_ast_minta_itematk_tm a,
											  e_ast_minta_atk_tm b, e_ast_barang_atk_tr c
											  where i_orgb=? and c_atk_setujutu=? and a.i_atk_ajuanminta = b.i_atk_ajuanminta
											  and a.c_atk=c.c_atk and c.q_atk_stock <= 0",$where);*/
				
				$result = $db->fetchAll("SELECT distinct a.i_atk_ajuanminta,d_atk_ajuanminta FROM e_ast_minta_itematk_tm a,
								  e_ast_minta_atk_tm b, e_ast_barang_atk_tr c
								  where i_orgb=? and c_atk_setujutu=? and a.i_atk_ajuanminta = b.i_atk_ajuanminta
								  and a.c_atk=c.c_atk and c.q_atk_stock <= 0 ",$where);
			 
		        $jmlResult = count($result);		
				$hasilAkhir = $jmlResult;
			 }
			 else
			 {
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT distinct a.i_atk_ajuanminta,d_atk_ajuanminta FROM e_ast_minta_itematk_tm a,
								  e_ast_minta_atk_tm b, e_ast_barang_atk_tr c
								  where i_orgb=? and c_atk_setujutu=? and a.i_atk_ajuanminta = b.i_atk_ajuanminta
								  and a.c_atk=c.c_atk and c.q_atk_stock <= 0
										  order by i_atk_ajuanminta
										  limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuanminta"           =>(string)$result[$j]->i_atk_ajuanminta,
											"d_atk_ajuanminta"           =>(string)$result[$j]->d_atk_ajuanminta);
								  
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function queryAjuanMintaAtkKsM2($unitkr) {
       $status='A';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT a.i_atk_ajuanminta,d_atk_ajuanminta FROM e_ast_minta_itematk_tm a,
								  e_ast_minta_atk_tm b, e_ast_barang_atk_tr c
								  where i_orgb=? and c_atk_setujutu=? and a.i_atk_ajuanminta = b.i_atk_ajuanminta
								  and a.c_atk=c.c_atk and c.q_atk_stock <= 0
								  ',$where); 
         $jmlResult = count($result);
		
		if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_atk_ajuanminta"           =>(string)$result[$j]->i_atk_ajuanminta,
								   "d_atk_ajuanminta"           =>(string)$result[$j]->d_atk_ajuanminta);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	// Distribusi ATK.........................23 okt 2007
	public function queryDistribusiAtkM($pageNumber,$itemPerPage) {
		$status='Y'; 
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $status;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
											where a.i_orgb=b.i_orgb and c_atk_setujuplkp=? ",$where);
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				
				$result = $db->fetchAll("SELECT a.i_orgb, b.n_orgb, i_atk_ajuanminta,d_atk_ajuanminta 
										FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										where a.i_orgb=b.i_orgb and c_atk_setujuplkp=? ORDER BY a.i_orgb
										limit $xLimit offset $xOffset",$where); 
			 
				$jmlResult = count($result);
			 	for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_orgb"           			=>(string)$result[$j]->i_orgb,
										   "n_orgb"           			=>(string)$result[$j]->n_orgb,
										   "i_atk_ajuanminta"           =>(string)$result[$j]->i_atk_ajuanminta,
										   "d_atk_ajuanminta"           =>(string)$result[$j]->d_atk_ajuanminta);
											
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada';
	   }
	}
	public function queryDistribusiAtkM2() {
       $status='Y'; 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     //$where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT a.i_orgb, b.n_orgb, i_atk_ajuanminta,d_atk_ajuanminta 
									FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
									where a.i_orgb=b.i_orgb and c_atk_setujuplkp=? ORDER BY a.i_orgb' ,$where); 
									
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb"           			=>(string)$result[$j]->i_orgb,
								   "n_orgb"           			=>(string)$result[$j]->n_orgb,
								   "i_atk_ajuanminta"           =>(string)$result[$j]->i_atk_ajuanminta,
								   "d_atk_ajuanminta"           =>(string)$result[$j]->d_atk_ajuanminta);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	//Distribusi ATK............... 23 okt 2007..............
	public function insertDistribusiAtkM(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
		 
	     $atk_mast_prm = array("i_atk_kirim" 		        	=>$data['nomorPengiriman'],
						       "d_atk_kirim"   	         		=>date("Y-m-d"),
							   "i_atk_ajuanminta"         		=>$data['noajuan'],
	                           "d_atk_ajuanminta"    	    	=>$data['tglAjuan'],
						       "i_orgb"                         =>$data['unitkr'],
						       "i_atk_nippemberi" 		        =>$data['nipPemberi'],
						       "i_atk_nippenerima"   	    	=>$data['nipPenerima'],
						       "i_entry"       		            =>$data['nuser'],
						       "d_entry"       		           =>date("Y-m-d"));
	    
		
		 $db->insert('e_ast_distribusi_atk_tm',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	//Distribusi ATK... 24 okt 2007..
	public function updateDistribusiAtkM($noajuan,$nuser) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->beginTransaction();
	     $atk_mast_prm = array("c_atk_setujuplkp"  		=>'C',
						       "e_alasan"   			=>'Distribusi ATK',
						       "i_entry"       		    =>$nuser,
						       "d_entry"       		    =>date("Y-m-d"));
	     
		 $where[] = "i_atk_ajuanminta = '". $noajuan ."'";
	     $db->update('e_ast_minta_atk_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
		 		 
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	
	}
	
	//Query ... 24  okt 2007
	public function queryMonitoringAtkKs($pageNumber,$itemPerPage) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $KatBarang;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				/* $result = $db->fetchAll("SELECT distinct b.c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock 
				                         FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
										 where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr and q_atk_stock <= 0 "); */

				$result = $db->fetchAll("SELECT distinct b.c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock,v_atk_hrgsatuan 
				                         FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
										 where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr");
				
				$jmlResult = count($result);				
				$hasilAkhir =$jmlResult;
				//$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
				//						    where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr and q_atk_stock <= 0 ",$where);
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				
				/* $result = $db->fetchAll("SELECT distinct b.c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock 
				                         FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
										 where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr and q_atk_stock <= 0  order by b.c_atk
										 limit $xLimit offset $xOffset");  */
										 
				$result = $db->fetchAll("SELECT distinct b.c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock,v_atk_hrgsatuan 
				                         FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
										 where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr order by b.c_atk
										 limit $xLimit offset $xOffset");
			 
				$jmlResult = count($result);
			 	for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("c_atk"                  =>(string)$result[$j]->c_atk,
										   "n_atk"                  =>(string)$result[$j]->n_atk,
										   "n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
										   "n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
										   "n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
										   "q_atk_stockmin"         =>(string)$result[$j]->q_atk_stockmin,
										   "q_atk_stock"            =>(string)$result[$j]->q_atk_stock,
										   "v_atk_hrgsatuan"        =>(string)$result[$j]->v_atk_hrgsatuan);
											
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada';
	   }
	}
	public function queryMonitoringAtkKs2($KatBarang) {
	    
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT b.c_atk_ctgr,n_atk_ctgr,b.c_atk,q_atk_minta,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock 
		                         FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
								 where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr and q_atk_stock <= 0 and b.c_atk_ctgr = ? order by b.c_atk',$KatBarang);
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_atk_ctgr"             =>(string)$result[$j]->c_atk_ctgr,
								   "n_atk_ctgr"             =>(string)$result[$j]->n_atk_ctgr,
								   "c_atk"                  =>(string)$result[$j]->c_atk,
								   "q_atk_minta"            =>(string)$result[$j]->q_atk_minta,
	                               "n_atk"                  =>(string)$result[$j]->n_atk,
								   "n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stockmin"         =>(string)$result[$j]->q_atk_stockmin,
								   "q_atk_stock"            =>(string)$result[$j]->q_atk_stock);
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function queryMonitoringAtkSedia($pageNumber,$itemPerPage) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $KatBarang;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				/* $result = $db->fetchAll("SELECT distinct b.c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock,v_atk_hrgsatuan 
				                         FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
										 where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr and q_atk_stock > 0 and q_atk_stock < q_atk_stockmin"); */

				$result = $db->fetchAll("SELECT c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock,v_atk_hrgsatuan 
				                         FROM e_ast_barang_atk_tr
										 where q_atk_stock <= q_atk_stockmin");
				
				$jmlResult = count($result);				
				$hasilAkhir =$jmlResult;
				//$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
				//						    where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr and q_atk_stock <= 0 ",$where);
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				
				$result = $db->fetchAll("SELECT c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock,v_atk_hrgsatuan 
				                         FROM e_ast_barang_atk_tr
										 where q_atk_stock <= q_atk_stockmin
										 order by c_atk
										 limit $xLimit offset $xOffset"); 
			 
				$jmlResult = count($result);
			 	for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("c_atk"                  =>(string)$result[$j]->c_atk,
										   "n_atk"                  =>(string)$result[$j]->n_atk,
										   "n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
										   "n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
										   "n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
										   "q_atk_stockmin"         =>(string)$result[$j]->q_atk_stockmin,
										   "q_atk_stock"            =>(string)$result[$j]->q_atk_stock,
										   "v_atk_hrgsatuan"        =>(string)$result[$j]->v_atk_hrgsatuan);
											
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada';
	   }
	}
	public function queryMonitoringAtkSedia2($KatBarang) {
	    
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT b.c_atk_ctgr,n_atk_ctgr,b.c_atk,q_atk_minta,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock 
		                         FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
								 where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr and q_atk_stock > 0 and b.c_atk_ctgr = ? order by b.c_atk',$KatBarang);
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_atk_ctgr"             =>(string)$result[$j]->c_atk_ctgr,
								   "n_atk_ctgr"             =>(string)$result[$j]->n_atk_ctgr,
								   "c_atk"                  =>(string)$result[$j]->c_atk,
								   "q_atk_minta"            =>(string)$result[$j]->q_atk_minta,
	                               "n_atk"                  =>(string)$result[$j]->n_atk,
								   "n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stockmin"         =>(string)$result[$j]->q_atk_stockmin,
								   "q_atk_stock"            =>(string)$result[$j]->q_atk_stock);
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function queryMonitoringAtkKsKat($pageNumber,$itemPerPage,$KatBarang) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $KatBarang;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				
										 
				/* $result = $db->fetchAll("SELECT distinct b.c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock 
		                         FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
								 where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr and q_atk_stock <= 0 and b.c_atk_ctgr = ?  ",$where); */

				$result = $db->fetchAll("SELECT c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock,v_atk_hrgsatuan 
				                        FROM e_ast_barang_atk_tr
										where c_atk_ctgr = ?",$where);
								 
				$jmlResult = count($result);
				$hasilAkhir =$jmlResult;
				//$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
				//						    where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr and q_atk_stock <= 0 and b.c_atk_ctgr = ? ",$where);
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				
				/* $result = $db->fetchAll("SELECT distinct b.c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock 
		                         FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
								 where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr and q_atk_stock <= 0 and b.c_atk_ctgr = ?  order by b.c_atk
										 limit $xLimit offset $xOffset",$where); */ 
										 
				$result = $db->fetchAll("SELECT c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock,v_atk_hrgsatuan 
				                        FROM e_ast_barang_atk_tr
										where c_atk_ctgr = ?  order by c_atk
										limit $xLimit offset $xOffset",$where);
			 
				$jmlResult = count($result);
			 	for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("c_atk"                  =>(string)$result[$j]->c_atk,
										   "n_atk"                  =>(string)$result[$j]->n_atk,
										   "n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
										   "n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
										   "n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
										   "q_atk_stockmin"         =>(string)$result[$j]->q_atk_stockmin,
										   "q_atk_stock"            =>(string)$result[$j]->q_atk_stock,
										   "v_atk_hrgsatuan"        =>(string)$result[$j]->v_atk_hrgsatuan);
											
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada';
	   }
	}
	public function queryMonitoringAtkKsKat2($KatBarang) {
	    
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT b.c_atk_ctgr,n_atk_ctgr,b.c_atk,q_atk_minta,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock 
		                         FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
								 where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr and q_atk_stock <= 0 and b.c_atk_ctgr = ? order by b.c_atk',$KatBarang);
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_atk_ctgr"             =>(string)$result[$j]->c_atk_ctgr,
								   "n_atk_ctgr"             =>(string)$result[$j]->n_atk_ctgr,
								   "c_atk"                  =>(string)$result[$j]->c_atk,
								   "q_atk_minta"            =>(string)$result[$j]->q_atk_minta,
	                               "n_atk"                  =>(string)$result[$j]->n_atk,
								   "n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stockmin"         =>(string)$result[$j]->q_atk_stockmin,
								   "q_atk_stock"            =>(string)$result[$j]->q_atk_stock);
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function queryMonitoringAtkKsNm($pageNumber,$itemPerPage,$KatBarang) {
		//$namaBarang = strtoupper($KatBarang);
	    //$nbrg = '%'.$namaBarang.'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = "%".strtoupper($KatBarang)."%";
			//$where[] = $nbrg;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			if(($pageNumber==0) && ($itemPerPage==0))
			{	
				/* $result = $db->fetchAll("SELECT distinct  n_atk, b.c_atk_ctgr,n_atk_ctgr,b.c_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock 
		                         FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
								 where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr and upper (a.n_atk) like ?  ",$where);  */
								 
				$result = $db->fetchAll("SELECT c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock,v_atk_hrgsatuan 
				                        FROM e_ast_barang_atk_tr
										where upper (n_atk) like ?  ",$where); 
				
				$jmlResult = count($result);
				$hasilAkhir =$jmlResult;
				//$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
				//						    where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr and q_atk_stock <= 0 and b.c_atk_ctgr = ? ",$where);
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				
				/* $result = $db->fetchAll("SELECT distinct  n_atk, b.c_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock 
		                         FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b
								 where a.c_atk = b.c_atk and upper (a.n_atk) like ? order by a.n_atk
										 limit $xLimit offset $xOffset",$where); */

				$result = $db->fetchAll("SELECT c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock,v_atk_hrgsatuan 
				                        FROM e_ast_barang_atk_tr
										where upper (n_atk) like ? order by n_atk
										limit $xLimit offset $xOffset",$where); 
			 
				$jmlResult = count($result);
			 	for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("c_atk"                  =>(string)$result[$j]->c_atk,
										   "n_atk"                  =>(string)$result[$j]->n_atk,
										   "n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
										   "n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
										   "n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
										   "q_atk_stockmin"         =>(string)$result[$j]->q_atk_stockmin,
										   "q_atk_stock"            =>(string)$result[$j]->q_atk_stock,
										   "v_atk_hrgsatuan"        =>(string)$result[$j]->v_atk_hrgsatuan);
											
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada';
	   }
	}
	public function queryMonitoringAtkKsNm2($KatBarang) {
		$namaBarang = strtoupper($KatBarang);
	    $nbrg = '%'.$namaBarang.'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
	     $where[] = $nbrg;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT b.c_atk_ctgr,n_atk_ctgr,b.c_atk,q_atk_minta,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock 
		                         FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
								 where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr and q_atk_stock <= 0 and a.n_atk like ? order by a.n_atk',$where);
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_atk_ctgr"             =>(string)$result[$j]->c_atk_ctgr,
								   "n_atk_ctgr"             =>(string)$result[$j]->n_atk_ctgr,
								   "c_atk"                  =>(string)$result[$j]->c_atk,
								   "q_atk_minta"            =>(string)$result[$j]->q_atk_minta,
	                               "n_atk"                  =>(string)$result[$j]->n_atk,
								   "n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stockmin"         =>(string)$result[$j]->q_atk_stockmin,
								   "q_atk_stock"            =>(string)$result[$j]->q_atk_stock);
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function queryMonitoringAtkSediaKat($pageNumber,$itemPerPage,$KatBarang) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $KatBarang;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
										 
				/* $result = $db->fetchAll("SELECT distinct b.c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock 
		                         FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
								 where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr and q_atk_stock > 0 and q_atk_stock < q_atk_stockmin and b.c_atk_ctgr = ?  ",$where); */

				$result = $db->fetchAll("SELECT c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock,v_atk_hrgsatuan 
				                        FROM e_ast_barang_atk_tr
										where q_atk_stock <= q_atk_stockmin and c_atk_ctgr = ?",$where);
								 
				$jmlResult = count($result);
				$hasilAkhir =$jmlResult;
				//$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
				//						    where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr and q_atk_stock <= 0 and b.c_atk_ctgr = ? ",$where);
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				
				/* $result = $db->fetchAll("SELECT distinct b.c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock 
		                         FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
								 where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr and q_atk_stock > 0 and q_atk_stock < q_atk_stockmin and b.c_atk_ctgr = ?  order by b.c_atk
										 limit $xLimit offset $xOffset",$where); */

				$result = $db->fetchAll("SELECT c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock,v_atk_hrgsatuan 
				                        FROM e_ast_barang_atk_tr
										where q_atk_stock <= q_atk_stockmin and c_atk_ctgr = ?  order by c_atk
										limit $xLimit offset $xOffset",$where);
			 
				$jmlResult = count($result);
			 	for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("c_atk"                 =>(string)$result[$j]->c_atk,
										   "n_atk"                  =>(string)$result[$j]->n_atk,
										   "n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
										   "n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
										   "n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
										   "q_atk_stockmin"         =>(string)$result[$j]->q_atk_stockmin,
										   "q_atk_stock"            =>(string)$result[$j]->q_atk_stock,
										   "v_atk_hrgsatuan"        =>(string)$result[$j]->v_atk_hrgsatuan);
											
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada';
	   }
	}
	public function queryMonitoringAtkSediaKat2($KatBarang) {
	    
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT b.c_atk_ctgr,n_atk_ctgr,b.c_atk,q_atk_minta,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock 
		                         FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
								 where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr and q_atk_stock > 0 and b.c_atk_ctgr = ? order by b.c_atk',$KatBarang);
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_atk_ctgr"             =>(string)$result[$j]->c_atk_ctgr,
								   "n_atk_ctgr"             =>(string)$result[$j]->n_atk_ctgr,
								   "c_atk"                  =>(string)$result[$j]->c_atk,
								   "q_atk_minta"            =>(string)$result[$j]->q_atk_minta,
	                               "n_atk"                  =>(string)$result[$j]->n_atk,
								   "n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stockmin"         =>(string)$result[$j]->q_atk_stockmin,
								   "q_atk_stock"            =>(string)$result[$j]->q_atk_stock);
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function queryMonitoringAtkSediaNm($pageNumber,$itemPerPage,$KatBarang) {
		//$namaBarang = strtoupper($KatBarang);
	    //$nbrg = '%'.$namaBarang.'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			//$where[] = $nbrg;
			$where[] = "%".strtoupper($KatBarang)."%";
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			if(($pageNumber==0) && ($itemPerPage==0))
			{	
										 
				$result = $db->fetchAll("SELECT c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock,v_atk_hrgsatuan 
				                        FROM e_ast_barang_atk_tr
										where q_atk_stock <= q_atk_stockmin and upper(n_atk) like ?  ",$where); 
				$jmlResult = count($result);
				$hasilAkhir =$jmlResult;
				//$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
				//						    where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr and q_atk_stock <= 0 and b.c_atk_ctgr = ? ",$where);
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				
				$result = $db->fetchAll("SELECT c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock,v_atk_hrgsatuan 
				                        FROM e_ast_barang_atk_tr
										where q_atk_stock <= q_atk_stockmin and upper(n_atk) like ? order by n_atk
										limit $xLimit offset $xOffset",$where); 
			 
				$jmlResult = count($result);
			 	for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("c_atk"                  =>(string)$result[$j]->c_atk,
										    "n_atk"                  =>(string)$result[$j]->n_atk,
											"n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
											"n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
											"n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
											"q_atk_stockmin"         =>(string)$result[$j]->q_atk_stockmin,
											"q_atk_stock"            =>(string)$result[$j]->q_atk_stock,
											"v_atk_hrgsatuan"        =>(string)$result[$j]->v_atk_hrgsatuan);
											
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada';
	   }
	}
	public function queryMonitoringAtkSediaNm2($KatBarang) {
		$namaBarang = strtoupper($KatBarang);
	    $nbrg = '%'.$namaBarang.'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
	     $where[] = $nbrg;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT b.c_atk_ctgr,n_atk_ctgr,b.c_atk,q_atk_minta,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock 
		                         FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
								 where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr and q_atk_stock > 0 and a.n_atk like ? order by a.n_atk',$where);
         
		 $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		   $hasilAkhir[$j] = array("c_atk_ctgr"             =>(string)$result[$j]->c_atk_ctgr,
								   "n_atk_ctgr"             =>(string)$result[$j]->n_atk_ctgr,
								   "c_atk"                  =>(string)$result[$j]->c_atk,
								   "q_atk_minta"            =>(string)$result[$j]->q_atk_minta,
	                               "n_atk"                  =>(string)$result[$j]->n_atk,
								   "n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stockmin"         =>(string)$result[$j]->q_atk_stockmin,
								   "q_atk_stock"            =>(string)$result[$j]->q_atk_stock);
								  
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function queryMonitoringAtkAll($pageNumber,$itemPerPage) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_barang_atk_tr ");
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				
				$result = $db->fetchAll("SELECT c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock,v_atk_hrgsatuan 
				                         FROM e_ast_barang_atk_tr 
										 ORDER BY c_atk
										 limit $xLimit offset $xOffset"); 
			 
				$jmlResult = count($result);
			 	for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("c_atk"                  =>(string)$result[$j]->c_atk,
											"n_atk"                  =>(string)$result[$j]->n_atk,
											"n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
											"n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
											"n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
											"q_atk_stockmin"         =>(string)$result[$j]->q_atk_stockmin,
											"q_atk_stock"            =>(string)$result[$j]->q_atk_stock,
											"v_atk_hrgsatuan"        =>(string)$result[$j]->v_atk_hrgsatuan);
											
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada';
	   }
	}
	
	public function queryMonitoringAtkAll2($pageNumber,$itemPerPage) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $KatBarang;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$result = $db->fetchAll("SELECT distinct b.c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock 
		                         FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
								 where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr "); 
				
				$jmlResult 	= count($result);
				$hasilAkhir = $jmlResult;
				//$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
				//						    where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr ",$where);
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				
				$result = $db->fetchAll("SELECT distinct b.c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock 
				                         FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
										 where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr order by b.c_atk
										 limit $xLimit offset $xOffset"); 
			 
				$jmlResult = count($result);
			 	for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("c_atk"                  =>(string)$result[$j]->c_atk,
										   "n_atk"                  =>(string)$result[$j]->n_atk,
										   "n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
										   "n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
										   "n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
										   "q_atk_stockmin"         =>(string)$result[$j]->q_atk_stockmin,
										   "q_atk_stock"            =>(string)$result[$j]->q_atk_stock);
											
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada';
	   }
	}
	
	//====================================melihat atk =====================================================================================
	
	public function queryPermintaanByOrgTU($pageNumber, $itemPerPage, $stat, $unitkr, $unitkr2) {
		$status=$stat;
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $unitkr;
			$where[] = $unitkr2;
			$where[] = $status;
			//echo "TEST CONN ".$db->getConnection()."<br>";
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$result = $db->fetchAll("SELECT a.i_atk_ajuanminta,d_atk_ajuanminta 
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										  where (a.i_orgb like ? or a.i_orgb like ? ) and  a.c_atk_setujutu = ? and a.i_orgb=b.i_orgb 
										  and c_atk_setujuplkp is null",$where);
			 
		         $jmlResult = count($result);
				 $hasilAkhir = $jmlResult;
			 }
			 else
			 {
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										  where (a.i_orgb like ? or a.i_orgb like ? ) and  a.c_atk_setujutu = ? and a.i_orgb=b.i_orgb 
										  and c_atk_setujuplkp is null
										  order by i_atk_ajuanminta
										  limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuanminta"      =>(string)$result[$j]->i_atk_ajuanminta,
											"d_atk_ajuanminta"      =>(string)$result[$j]->d_atk_ajuanminta,
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
	
	public function queryPermintaanByOrgPrnt($pageNumber, $itemPerPage, $stat, $unitkr) {
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
				$result = $db->fetchAll("SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										  where a.i_orgb = ? and  a.c_atk_setujutu = ? and a.i_orgb=b.i_orgb 
										  and c_atk_setujuplkp is null
										  
										  UNION
										  SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										  where i_orgb_parent  = ? and  a.c_atk_setujutu = ? and a.i_orgb=b.i_orgb 
										  and c_atk_setujuplkp is null
										  
										  UNION
										  SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm C 
										  where a.c_atk_setujutu = ? and a.i_orgb=C.i_orgb
											and exists ( select * 
											from e_org_0_0_tm  B
											where B.i_orgb_parent  = ?
											         and B.i_orgb = C.i_orgb_parent
											             )  
										  and c_atk_setujuplkp is null
										  
										  UNION
										  SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										  where a.c_atk_setujutu = ? and a.i_orgb=b.i_orgb
											and i_orgb_parent   
													in ( select A.i_orgb from e_org_0_0_tm A
														 where exists 
													       ( select * from e_org_0_0_tm  B
													         where B.i_orgb_parent  = ?
													          and B.i_orgb = A.i_orgb_parent
													        )                 
													    )
										  and c_atk_setujuplkp is null
										  
										  UNION
										  SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										  where a.c_atk_setujutu = ? and a.i_orgb=b.i_orgb
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
										  and c_atk_setujuplkp is null",$where);
			 
		         $jmlResult = count($result);
				 $hasilAkhir = $jmlResult;
			 }
			 else
			 {
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										  where a.i_orgb = ? and  a.c_atk_setujutu = ? and a.i_orgb=b.i_orgb 
										  and c_atk_setujuplkp is null
										  
										  UNION
										  SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										  where i_orgb_parent  = ? and  a.c_atk_setujutu = ? and a.i_orgb=b.i_orgb 
										  and c_atk_setujuplkp is null
										  
										  UNION
										  SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm C 
										  where a.c_atk_setujutu = ? and a.i_orgb=C.i_orgb
											and exists ( select * 
											from e_org_0_0_tm  B
											where B.i_orgb_parent  = ?
											         and B.i_orgb = C.i_orgb_parent
											             )  
										  and c_atk_setujuplkp is null
										  
										  UNION
										  SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										  where a.c_atk_setujutu = ? and a.i_orgb=b.i_orgb
											and i_orgb_parent   
													in ( select A.i_orgb from e_org_0_0_tm A
														 where exists 
													       ( select * from e_org_0_0_tm  B
													         where B.i_orgb_parent  = ?
													          and B.i_orgb = A.i_orgb_parent
													        )                 
													    )
										  and c_atk_setujuplkp is null
										  
										  UNION
										  SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										  where a.c_atk_setujutu = ? and a.i_orgb=b.i_orgb
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
										  and c_atk_setujuplkp is null
										  
										  
										  order by i_atk_ajuanminta
										  limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuanminta"      =>(string)$result[$j]->i_atk_ajuanminta,
											"d_atk_ajuanminta"      =>(string)$result[$j]->d_atk_ajuanminta,
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
	
	//==========================utk  perlengkapan ====================================================
	public function queryPermintaanLkpByOrgTU($pageNumber, $itemPerPage, $stat, $unitkr) {
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
				$result = $db->fetchAll("SELECT a.i_atk_ajuanminta,d_atk_ajuanminta 
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										  where a.i_orgb like ? and  a.c_atk_setujuplkp = ? and a.i_orgb=b.i_orgb 
										  ",$where);
			 
		         $jmlResult = count($result);
				 $hasilAkhir = $jmlResult;
			 }
			 else
			 {
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										  where a.i_orgb like ? and  a.c_atk_setujuplkp = ? and a.i_orgb=b.i_orgb 
										  order by i_atk_ajuanminta
										  limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuanminta"      =>(string)$result[$j]->i_atk_ajuanminta,
											"d_atk_ajuanminta"      =>(string)$result[$j]->d_atk_ajuanminta,
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
	
	public function queryPermintaanLkpByOrgPrnt($pageNumber, $itemPerPage, $stat, $unitkr) {
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
				$result = $db->fetchAll("SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										  where a.i_orgb = ? and  a.c_atk_setujuplkp = ? and a.i_orgb=b.i_orgb 
										  
										  UNION
										  SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										  where i_orgb_parent  = ? and  a.c_atk_setujuplkp = ? and a.i_orgb=b.i_orgb 
										  
										  UNION
										  SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm C 
										  where a.c_atk_setujuplkp = ? and a.i_orgb=C.i_orgb
											and exists ( select * 
											from e_org_0_0_tm  B
											where B.i_orgb_parent  = ?
											         and B.i_orgb = C.i_orgb_parent
											             )  
										  
										  UNION
										  SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										  where a.c_atk_setujuplkp = ? and a.i_orgb=b.i_orgb
											and i_orgb_parent   
													in ( select A.i_orgb from e_org_0_0_tm A
														 where exists 
													       ( select * from e_org_0_0_tm  B
													         where B.i_orgb_parent  = ?
													          and B.i_orgb = A.i_orgb_parent
													        )                 
													    )
										  
										  UNION
										  SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										  where a.c_atk_setujuplkp = ? and a.i_orgb=b.i_orgb
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
			     
				 $result = $db->fetchAll("SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										  where a.i_orgb = ? and  a.c_atk_setujuplkp = ? and a.i_orgb=b.i_orgb 
										  
										  UNION
										  SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										  where i_orgb_parent  = ? and  a.c_atk_setujuplkp = ? and a.i_orgb=b.i_orgb 
										  
										  UNION
										  SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm C 
										  where a.c_atk_setujuplkp = ? and a.i_orgb=C.i_orgb
											and exists ( select * 
											from e_org_0_0_tm  B
											where B.i_orgb_parent  = ?
											         and B.i_orgb = C.i_orgb_parent
											             )  
										  
										  UNION
										  SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										  where a.c_atk_setujuplkp = ? and a.i_orgb=b.i_orgb
											and i_orgb_parent   
													in ( select A.i_orgb from e_org_0_0_tm A
														 where exists 
													       ( select * from e_org_0_0_tm  B
													         where B.i_orgb_parent  = ?
													          and B.i_orgb = A.i_orgb_parent
													        )                 
													    )
										  
										  UNION
										  SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										  where a.c_atk_setujuplkp = ? and a.i_orgb=b.i_orgb
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
					$hasilAkhir[$j] = array("i_atk_ajuanminta"      =>(string)$result[$j]->i_atk_ajuanminta,
											"d_atk_ajuanminta"      =>(string)$result[$j]->d_atk_ajuanminta,
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
	
	public function queryMonitoringAtkAllCetak() {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
		 $result = $db->fetchAll("SELECT c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock,v_atk_hrgsatuan 
								 FROM e_ast_barang_atk_tr 
								 ORDER BY c_atk"); 

         $jmlResult = count($result);
		 //echo "servicessss".$jmlResult;
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
           $hasilAkhir[$j] = array("c_atk"               =>(string)$result[$j]->c_atk,
								"n_atk"                  =>(string)$result[$j]->n_atk,
								"n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
								"n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
								"n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
								"q_atk_stockmin"         =>(string)$result[$j]->q_atk_stockmin,
								"q_atk_stock"            =>(string)$result[$j]->q_atk_stock,
								"v_atk_hrgsatuan"        =>(string)$result[$j]->v_atk_hrgsatuan);
			}
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function queryMonitoringAtkKsKatCetak($KatBarang) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
		 $where[] = $KatBarang;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
		 $result = $db->fetchAll("SELECT c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock,v_atk_hrgsatuan 
								FROM e_ast_barang_atk_tr
								where c_atk_ctgr = ?  order by c_atk",$where); 

         $jmlResult = count($result);
		 //echo "servicessss".$jmlResult;
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
           $hasilAkhir[$j] = array("c_atk"                  =>(string)$result[$j]->c_atk,
								   "n_atk"                  =>(string)$result[$j]->n_atk,
								   "n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stockmin"         =>(string)$result[$j]->q_atk_stockmin,
								   "q_atk_stock"            =>(string)$result[$j]->q_atk_stock,
								   "v_atk_hrgsatuan"        =>(string)$result[$j]->v_atk_hrgsatuan);
			}
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function queryMonitoringAtkKsNmCetak($KatBarang) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
		 $where[] = "%".strtoupper($KatBarang)."%";
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
		 $result = $db->fetchAll("SELECT c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock,v_atk_hrgsatuan 
								FROM e_ast_barang_atk_tr
								where upper (n_atk) like ? order by n_atk",$where); 

         $jmlResult = count($result);
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
           $hasilAkhir[$j] = array("c_atk"                  =>(string)$result[$j]->c_atk,
								   "n_atk"                  =>(string)$result[$j]->n_atk,
								   "n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stockmin"         =>(string)$result[$j]->q_atk_stockmin,
								   "q_atk_stock"            =>(string)$result[$j]->q_atk_stock,
								   "v_atk_hrgsatuan"        =>(string)$result[$j]->v_atk_hrgsatuan);
			}
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function queryMonitoringAtkSediaCetak() {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
		 $result = $db->fetchAll("SELECT c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock,v_atk_hrgsatuan 
								 FROM e_ast_barang_atk_tr
								 where q_atk_stock <= q_atk_stockmin
								 order by c_atk"); 

         $jmlResult = count($result);
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
           $hasilAkhir[$j] = array("c_atk"                  =>(string)$result[$j]->c_atk,
								   "n_atk"                  =>(string)$result[$j]->n_atk,
								   "n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stockmin"         =>(string)$result[$j]->q_atk_stockmin,
								   "q_atk_stock"            =>(string)$result[$j]->q_atk_stock,
								   "v_atk_hrgsatuan"        =>(string)$result[$j]->v_atk_hrgsatuan);
			}
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function queryMonitoringAtkSediaKatCetak($KatBarang) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
		 $where[] = $KatBarang;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
		 $result = $db->fetchAll("SELECT c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock,v_atk_hrgsatuan 
				                FROM e_ast_barang_atk_tr
								where q_atk_stock <= q_atk_stockmin and c_atk_ctgr = ?  order by c_atk",$where); 

         $jmlResult = count($result);
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
           $hasilAkhir[$j] = array("c_atk"                  =>(string)$result[$j]->c_atk,
								   "n_atk"                  =>(string)$result[$j]->n_atk,
								   "n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stockmin"         =>(string)$result[$j]->q_atk_stockmin,
								   "q_atk_stock"            =>(string)$result[$j]->q_atk_stock,
								   "v_atk_hrgsatuan"        =>(string)$result[$j]->v_atk_hrgsatuan);
			}
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function queryMonitoringAtkSediaNmCetak($KatBarang) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
		 $where[] = "%".strtoupper($KatBarang)."%";
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
		 $result = $db->fetchAll("SELECT c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock,v_atk_hrgsatuan 
				                FROM e_ast_barang_atk_tr
								where q_atk_stock <= q_atk_stockmin and upper(n_atk) like ? order by n_atk",$where); 

         $jmlResult = count($result);
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
           $hasilAkhir[$j] = array("c_atk"                  =>(string)$result[$j]->c_atk,
								   "n_atk"                  =>(string)$result[$j]->n_atk,
								   "n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stockmin"         =>(string)$result[$j]->q_atk_stockmin,
								   "q_atk_stock"            =>(string)$result[$j]->q_atk_stock,
								   "v_atk_hrgsatuan"        =>(string)$result[$j]->v_atk_hrgsatuan);
			}
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	
}		
?>