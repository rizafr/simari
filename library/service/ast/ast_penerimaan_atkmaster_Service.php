<?php
class ast_penerimaan_atkmaster_service {
   
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
	 * fungsi untuk penerimaan ATK
	 ***************************/
	
	public function getPenerimaanAtkM($pageNumber, $itemPerPage) {
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
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_terima_atk_tm");
			 }
			 else
			 {
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT i_atk_terima,d_atk_terima,i_atk_kwtbeli 
										  FROM e_ast_terima_atk_tm 
										  order by i_atk_terima
										  limit $xLimit offset $xOffset");
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_terima"      =>(string)$result[$j]->i_atk_terima,
									   "i_atk_kwtbeli"          =>(string)$result[$j]->i_atk_kwtbeli,
									   "d_atk_terima"           =>(string)$result[$j]->d_atk_terima);
								  
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function getPenerimaanAtkByUnitM($pageNumber, $itemPerPage, $unitkr) {
		$status='A';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $status;
			$where[] = $unitkr;
			//echo "TEST CONN ".$db->getConnection()."<br>";
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$hasilAkhir = $db->fetchOne("select count(*) 
											FROM e_ast_terima_atk_tm where  c_atk_setuju=? and i_orgb=?",$where);
			 }
			 else
			 {
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT i_atk_terima,d_atk_terima,i_atk_kwtbeli,d_atk_beli,i_pgdaan_beritaacara 
										  FROM e_ast_terima_atk_tm where  c_atk_setuju=? and i_orgb=?
										  order by i_atk_terima
										  limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_terima"      =>(string)$result[$j]->i_atk_terima,
									   "i_atk_kwtbeli"          =>(string)$result[$j]->i_atk_kwtbeli,
									   "d_atk_beli"          	=>(string)$result[$j]->d_atk_beli,
									   "d_atk_terima"           =>(string)$result[$j]->d_atk_terima,
									   "i_pgdaan_beritaacara"   =>(string)$result[$j]->i_pgdaan_beritaacara);
								  
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function queryPenerimaanAtkM($unitkr) {
       $status='A';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     //$where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_atk_terima,d_atk_terima,i_atk_kwtbeli,i_pgdaan_beritaacara 
									FROM e_ast_terima_atk_tm where  c_atk_setuju=?
									order by i_atk_terima',$where); 
         $jmlResult = count($result);
          
		  if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_atk_terima"           =>(string)$result[$j]->i_atk_terima,
								   "i_atk_kwtbeli"          =>(string)$result[$j]->i_atk_kwtbeli,
								   "d_atk_terima"           =>(string)$result[$j]->d_atk_terima,
								   "i_pgdaan_beritaacara"   =>(string)$result[$j]->i_pgdaan_beritaacara);
								  
								  
							       
		
		 }
        }
        
	     return $hasilAkhir;
            
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function insertTerimaAtkM(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("i_atk_terima"      =>$data['nopeng'],
								"d_atk_terima"     =>date("Y-m-d"),
								"i_orgb"           =>$data['unitkr'],
								"i_atk_ajuan"      =>$data['noAjuan'],
								"i_atk_kwtbeli"    =>$data['noKwitansi'],
								"d_atk_beli"       =>$data['tglPembelian'],
								"c_atk_setuju"     =>'A',
								"i_pgdaan_beritaacara" =>$data['beritaAcara'],
						        "i_entry"          =>$data['nuser'],
						        "d_entry"          =>date("Y-m-d"));
	    
	     $db->insert('e_ast_terima_atk_tm',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function deletPenerimaanAtkM($nopeng) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $where[] = "i_atk_terima = '". $nopeng ."'";
		 $db->delete('e_ast_terima_atk_tm', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	public function updatePenerimaanAtkM($nopeng,$nuser) {
	   //echo 'statusmya b';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("c_atk_setuju"  		=>'B',
						       "i_entry"       		=>$nuser,
						       "d_entry"       		=>date("Y-m-d"));
	     
		 $where[] = "i_atk_terima = '".$nopeng."'";
	     $db->update('e_ast_terima_atk_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	//=====================================utk page======================================================================14 des 07========
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
				//$hasilAkhir = $db->fetchOne("select count(*) from e_ast_barang_atk_tr 
				//							 where c_atk_ctgr=? and n_atk like ? ",$where);
											 
				$result = $db->fetchAll("SELECT a.c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stock 
										FROM e_ast_barang_atk_tr a where a.c_atk_ctgr=? and n_atk like ?
										and not exists(select d.i_atk_terima,c.c_atk,c.c_atk_ctgr 
										from       e_ast_terima_itematk_tm    c,      e_ast_terima_atk_tm    d
										where c.i_atk_terima = d.i_atk_terima and a.c_atk = c.c_atk 
										and a.c_atk_ctgr = c.c_atk_ctgr and d.i_atk_terima=?)
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
										and not exists(select d.i_atk_terima,c.c_atk,c.c_atk_ctgr 
										from     e_ast_terima_itematk_tm   c,    e_ast_terima_atk_tm   d
										where c.i_atk_terima = d.i_atk_terima and a.c_atk = c.c_atk 
										and a.c_atk_ctgr = c.c_atk_ctgr and d.i_atk_terima=?)
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

	
    /* ************
	 * fungsi untuk memasukan data PENERIMAAN LANGSUNG ATK  ke tabel 'e_ast_terima_atk_tm'
	 ***************************/
	 
	
	public function updateStatusVerAtkM($nopeng,$nuser) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("c_atk_setuju"  		=>'Y',
						       "i_entry"       		=>$nuser,
						       "d_entry"       		=>date("Y-m-d"));
	     
		$where[] = "i_atk_terima = '". $nopeng ."'";
	     $db->update('e_ast_terima_atk_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
 //  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++	
 //   service untuk modul penerimaan atk tidak langsung
 //  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++	
	public function queryPenerimaanTlAtkM($pageNumber, $itemPerPage, $unitkr) {
		$status='Y';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			//$where[] = $unitkr;
			$where[] = $status;
			//$where[] = $unitkr;
			$where[] = $status;
			//echo "TEST CONN ".$db->getConnection()."<br>";
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$result = $db->fetchAll("SELECT a.i_atk_ajuan,a.d_atk_ajuan,
											null as itr ,
											a.d_atk_ajuAn as dtr,
											null as ikwt,
											a.d_atk_ajuAn as dkwt
											from e_ast_ajuanbeli_atk_tm a
											where a.c_atk_setuju = ? 
											and not exists (select c.i_atk_ajuan,c.i_atk_terima  from e_ast_terima_atk_tm c
											where   a.i_atk_ajuan = c.i_atk_ajuan 
											)								
  											UNION 
											SELECT 
											a.i_atk_ajuan,a.d_atk_ajuan,
											c.i_atk_terima as itr,
											c.d_atk_terima as dtr,
											c.i_atk_kwtbeli as ikwt,
											c.d_atk_beli as dkwt
											from e_ast_ajuanbeli_atk_tm a,
											e_ast_terima_atk_tm c
											where a.c_atk_setuju = ?   
											and  a.i_atk_ajuan = c.i_atk_ajuan and  c.c_atk_setuju = 'A'  
											ORDER BY i_atk_ajuan",$where);
				$jmlResult = count($result);
				$hasilAkhir =$jmlResult;
				
			}
			else
			{
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT a.i_atk_ajuan,a.d_atk_ajuan,
											null as itr ,
											a.d_atk_ajuAn as dtr,
											null as ikwt,
											a.d_atk_ajuAn as dkwt
											from e_ast_ajuanbeli_atk_tm a
											where  a.c_atk_setuju = ? 
											and not exists (select c.i_atk_ajuan,c.i_atk_terima  from e_ast_terima_atk_tm c
											where   a.i_atk_ajuan = c.i_atk_ajuan 
											)								
  											UNION 
											SELECT 
											a.i_atk_ajuan,a.d_atk_ajuan,
											c.i_atk_terima as itr,
											c.d_atk_terima as dtr,
											c.i_atk_kwtbeli as ikwt,
											c.d_atk_beli as dkwt
											from e_ast_ajuanbeli_atk_tm a,
											e_ast_terima_atk_tm c
											where a.c_atk_setuju = ?   
											and  a.i_atk_ajuan = c.i_atk_ajuan and  c.c_atk_setuju = 'A'  
											ORDER BY i_atk_ajuan
											limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuan"           =>(string)$result[$j]->i_atk_ajuan,
											"d_atk_ajuan"           =>(string)$result[$j]->d_atk_ajuan,
											"i_atk_terima"          =>(string)$result[$j]->itr,
											"d_atk_terima"          =>(string)$result[$j]->dtr,
											"i_atk_kwtbeli"         =>(string)$result[$j]->ikwt,
											"d_atk_beli"           	=>(string)$result[$j]->dkwt);
								  
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	//================update ================28 april 08=========== tdk by org login ======================================
	public function queryPenerimaanTlAtkM_Last($pageNumber, $itemPerPage, $unitkr) {
		$status='Y';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $unitkr;
			$where[] = $status;
			$where[] = $unitkr;
			$where[] = $status;
			//echo "TEST CONN ".$db->getConnection()."<br>";
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$result = $db->fetchAll("SELECT a.i_atk_ajuan,a.d_atk_ajuan,
											null as itr ,
											a.d_atk_ajuAn as dtr,
											null as ikwt,
											a.d_atk_ajuAn as dkwt
											from e_ast_ajuanbeli_atk_tm a
											where a.i_orgb = ? and  a.c_atk_setuju = ? 
											and not exists (select c.i_atk_ajuan,c.i_atk_terima  from e_ast_terima_atk_tm c
											where   a.i_atk_ajuan = c.i_atk_ajuan 
											)								
  											UNION 
											SELECT 
											a.i_atk_ajuan,a.d_atk_ajuan,
											c.i_atk_terima as itr,
											c.d_atk_terima as dtr,
											c.i_atk_kwtbeli as ikwt,
											c.d_atk_beli as dkwt
											from e_ast_ajuanbeli_atk_tm a,
											e_ast_terima_atk_tm c
											where a.i_orgb = ? and  a.c_atk_setuju = ?   
											and  a.i_atk_ajuan = c.i_atk_ajuan and  c.c_atk_setuju = 'A'  
											ORDER BY i_atk_ajuan",$where);
				$jmlResult = count($result);
				$hasilAkhir =$jmlResult;
				
			}
			else
			{
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT a.i_atk_ajuan,a.d_atk_ajuan,
											null as itr ,
											a.d_atk_ajuAn as dtr,
											null as ikwt,
											a.d_atk_ajuAn as dkwt
											from e_ast_ajuanbeli_atk_tm a
											where a.i_orgb = ? and  a.c_atk_setuju = ? 
											and not exists (select c.i_atk_ajuan,c.i_atk_terima  from e_ast_terima_atk_tm c
											where   a.i_atk_ajuan = c.i_atk_ajuan 
											)								
  											UNION 
											SELECT 
											a.i_atk_ajuan,a.d_atk_ajuan,
											c.i_atk_terima as itr,
											c.d_atk_terima as dtr,
											c.i_atk_kwtbeli as ikwt,
											c.d_atk_beli as dkwt
											from e_ast_ajuanbeli_atk_tm a,
											e_ast_terima_atk_tm c
											where a.i_orgb = ? and  a.c_atk_setuju = ?   
											and  a.i_atk_ajuan = c.i_atk_ajuan and  c.c_atk_setuju = 'A'  
											ORDER BY i_atk_ajuan
											limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuan"           =>(string)$result[$j]->i_atk_ajuan,
											"d_atk_ajuan"           =>(string)$result[$j]->d_atk_ajuan,
											"i_atk_terima"          =>(string)$result[$j]->itr,
											"d_atk_terima"          =>(string)$result[$j]->dtr,
											"i_atk_kwtbeli"         =>(string)$result[$j]->ikwt,
											"d_atk_beli"           	=>(string)$result[$j]->dkwt);
								  
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	//===============================================================================================================
	public function queryPenerimaanTlAtkM4($pageNumber, $itemPerPage, $unitkr) {
		$status='Y';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $unitkr;
			$where[] = $status;
			//echo "TEST CONN ".$db->getConnection()."<br>";
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$result = $db->fetchAll("SELECT distinct a.i_atk_ajuan,a.d_atk_ajuAn ,c.i_atk_terima ,c.d_atk_terima 
											from e_ast_ajuanbeli_atk_tm a,e_ast_ajuanbeli_itematk_tm b,
											e_ast_terima_atk_tm c,e_ast_terima_itematk_tm d
											where a.i_orgb = ? and  a.c_atk_setuju = ?  and  a.i_atk_ajuan = b.i_atk_ajuan 
											and  a.i_atk_ajuan = c.i_atk_ajuan and  c.c_atk_setuju = 'A' and c.i_atk_terima = d.i_atk_terima 
											ORDER BY a.i_atk_ajuan",$where);
				$jmlResult = count($result);
				$hasilAkhir =$jmlResult;
				
			}
			else
			{
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT distinct a.i_atk_ajuan,a.d_atk_ajuAn ,c.i_atk_terima ,c.d_atk_terima ,c.i_atk_kwtbeli,c.d_atk_beli
											from e_ast_ajuanbeli_atk_tm a,e_ast_ajuanbeli_itematk_tm b,
											e_ast_terima_atk_tm c,e_ast_terima_itematk_tm d
											where a.i_orgb = ? and  a.c_atk_setuju = ?  and  a.i_atk_ajuan = b.i_atk_ajuan 
											and  a.i_atk_ajuan = c.i_atk_ajuan and  c.c_atk_setuju = 'A' and c.i_atk_terima = d.i_atk_terima 
											ORDER BY a.i_atk_ajuan
											limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuan"           =>(string)$result[$j]->i_atk_ajuan,
											"d_atk_ajuan"           =>(string)$result[$j]->d_atk_ajuan,
											"i_atk_terima"          =>(string)$result[$j]->i_atk_terima,
											"d_atk_terima"          =>(string)$result[$j]->d_atk_terima,
											"i_atk_kwtbeli"         =>(string)$result[$j]->i_atk_kwtbeli,
											"d_atk_beli"           	=>(string)$result[$j]->d_atk_beli);
								  
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	public function queryPenerimaanTlAtkM3($pageNumber, $itemPerPage, $unitkr) {
		$status='Y';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $unitkr;
			$where[] = $status;
			//echo "TEST CONN ".$db->getConnection()."<br>";
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$result = $db->fetchAll("SELECT distinct a.i_atk_ajuan,a.d_atk_ajuAn from e_ast_ajuanbeli_atk_tm a,e_ast_ajuanbeli_itematk_tm b
											where a.i_orgb = ? and  a.c_atk_setuju = ? and  a.i_atk_ajuan = b.i_atk_ajuan 
											and not exists (select c.i_atk_ajuan from e_ast_terima_atk_tm c,e_ast_terima_itematk_tm d
											where   c.i_atk_terima = d.i_atk_terima and a.i_atk_ajuan = c.i_atk_ajuan 
											and b.c_atk= d.c_atk)
											ORDER BY a.i_atk_ajuan",$where);
				$jmlResult = count($result);
				$hasilAkhir =$jmlResult;
				
				/*$hasilAkhir = $db->fetchOne("select count(distinct a.i_atk_ajuan,a.d_atk_ajuAn) 
											FROM e_ast_ajuanbeli_atk_tm a,e_ast_ajuanbeli_itematk_tm b
											where a.i_orgb = ? and  a.c_atk_setuju = ? and  a.i_atk_ajuan = b.i_atk_ajuan 
											and not exists (select c.i_atk_ajuan from e_ast_terima_atk_tm c,e_ast_terima_itematk_tm d
											where   c.i_atk_terima = d.i_atk_terima and a.i_atk_ajuan = c.i_atk_ajuan 
											and b.c_atk= d.c_atk)",$where);
											*/
			 }
			 else
			 {
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT distinct a.i_atk_ajuan,a.d_atk_ajuAn from e_ast_ajuanbeli_atk_tm a,e_ast_ajuanbeli_itematk_tm b
											where a.i_orgb = ? and  a.c_atk_setuju = ? and  a.i_atk_ajuan = b.i_atk_ajuan 
											and not exists (select c.i_atk_ajuan from e_ast_terima_atk_tm c,e_ast_terima_itematk_tm d
											where   c.i_atk_terima = d.i_atk_terima and a.i_atk_ajuan = c.i_atk_ajuan 
											and b.c_atk= d.c_atk and c_atk_setuju = 'Y')
											ORDER BY a.i_atk_ajuan
											limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuan"           =>(string)$result[$j]->i_atk_ajuan,
											"d_atk_ajuan"           =>(string)$result[$j]->d_atk_ajuan);
								  
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	public function queryPenerimaanTlAtkM2($unitkr) {
       $status='Y';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT distinct a.i_atk_ajuan,a.d_atk_ajuAn from e_ast_ajuanbeli_atk_tm a,e_ast_ajuanbeli_itematk_tm b
								where a.i_orgb = ? and  a.c_atk_setuju = ? and  a.i_atk_ajuan = b.i_atk_ajuan 
								and not exists (select c.i_atk_ajuan from e_ast_terima_atk_tm c,e_ast_terima_itematk_tm d
								where   c.i_atk_terima = d.i_atk_terima and a.i_atk_ajuan = c.i_atk_ajuan 
								and b.c_atk= d.c_atk)
								ORDER BY a.i_atk_ajuan',$where);
		 
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
	 
	
	//  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++	
	public function queryVerifikasiAtkM($pageNumber, $itemPerPage, $unitkr) {
		$status='B';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			//$where[] = $unitkr;
			$where[] = $status;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$result = $db->fetchAll("SELECT i_atk_terima,d_atk_terima FROM e_ast_terima_atk_tm 
										where  c_atk_setuju=? ORDER BY i_atk_terima",$where);
				$jmlResult = count($result);
				$hasilAkhir =$jmlResult;
				
			 }
			 else
			 {
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT i_atk_terima,d_atk_terima FROM e_ast_terima_atk_tm 
										 where  c_atk_setuju=? ORDER BY i_atk_terima
											limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_terima"           =>(string)$result[$j]->i_atk_terima,
											"d_atk_terima"           =>(string)$result[$j]->d_atk_terima);
								  
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	//
	public function querySdhVerifikasiAtkM($pageNumber, $itemPerPage, $unitkr) {
		$status='Y';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			//$where[] = $unitkr;
			$where[] = $status;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$result = $db->fetchAll("SELECT i_atk_terima,d_atk_terima FROM e_ast_terima_atk_tm 
										where  c_atk_setuju=? ORDER BY i_atk_terima",$where);
				$jmlResult = count($result);
				$hasilAkhir =$jmlResult;
				
			 }
			 else
			 {
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT i_atk_terima,d_atk_terima FROM e_ast_terima_atk_tm 
										 where  c_atk_setuju=? ORDER BY i_atk_terima
											limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_terima"           =>(string)$result[$j]->i_atk_terima,
											"d_atk_terima"           =>(string)$result[$j]->d_atk_terima);
								  
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function queryVerifikasiAtkM2($unitkr) {
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
	
	public function queryMonitoringAtkM($pageNumber, $itemPerPage, $unitkr) {
		$status='Y';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			//$where[] = $unitkr;
			$where[] = $status;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$result = $db->fetchAll("SELECT i_atk_terima, d_atk_terima,i_atk_kwtbeli,d_atk_beli 
				                        FROM e_ast_terima_atk_tm 
										where  c_atk_setuju=? ORDER BY i_atk_terima",$where);
				$jmlResult = count($result);
				$hasilAkhir =$jmlResult;
				
			 }
			 else
			 {
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT i_atk_terima, d_atk_terima,i_atk_kwtbeli,d_atk_beli 
				                          FROM e_ast_terima_atk_tm 
										  where  c_atk_setuju=? ORDER BY i_atk_terima
										  limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					 $hasilAkhir[$j] = array("i_atk_terima"        =>(string)$result[$j]->i_atk_terima,
					                         "d_atk_terima"        =>(string)$result[$j]->d_atk_terima,
					                         "i_atk_kwtbeli"        =>(string)$result[$j]->i_atk_kwtbeli,
											 "d_atk_beli"           =>(string)$result[$j]->d_atk_beli);
								  
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function queryMonitoringAtkM2($unitkr) {
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
 

	// Distribusi ATK.........................23 okt 2007
	public function queryDistribusiAtkM() {
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
						       "i_orgb"                         =>$data['Unitkr'],
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
	public function updateDistribusiAtkM($noajuan) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->beginTransaction();
	     $atk_mast_prm = array("c_atk_setujuplkp"  		=>'C',
						       "e_atk_setujuplkp"   	=>' ',
						       "i_entry"       		    =>"ast",
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
	
	public function queryMonitoringAtkKs($KatBarang) {
	    
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
	
	public function queryMonitoringAtkSedia($KatBarang) {
	    
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
	
	public function queryMonitoringAtkKsKat($KatBarang) {
	    
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
	
	public function queryMonitoringAtkKsNm($KatBarang) {
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
	
	public function queryMonitoringAtkSediaKat($KatBarang) {
	    
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
	
	public function queryMonitoringAtkSediaNm($KatBarang) {
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

	//================================================ melihat penerimaan atk ==============================================
	
	public function queryBlmTerimaAtkM($pageNumber, $itemPerPage, $unitkr) {
		$status='Y';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $unitkr;
			$where[] = $status;
			//echo "TEST CONN ".$db->getConnection()."<br>";
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$result = $db->fetchAll("SELECT a.i_atk_ajuan,a.d_atk_ajuan,
											null as itr ,
											a.d_atk_ajuAn as dtr,
											null as ikwt,
											a.d_atk_ajuAn as dkwt
											from e_ast_ajuanbeli_atk_tm a
											where a.i_orgb like ? and  a.c_atk_setuju = ? 
											and not exists (select c.i_atk_ajuan,c.i_atk_terima  from e_ast_terima_atk_tm c
											where   a.i_atk_ajuan = c.i_atk_ajuan 
											) 
											ORDER BY i_atk_ajuan",$where);
				$jmlResult = count($result);
				$hasilAkhir =$jmlResult;
				
			}
			else
			{
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT a.i_atk_ajuan,a.d_atk_ajuan,
											null as itr ,
											a.d_atk_ajuAn as dtr,
											null as ikwt,
											a.d_atk_ajuAn as dkwt,a.i_orgb,n_orgb 
											from e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b
											where a.i_orgb like ? and  a.c_atk_setuju = ? and a.i_orgb=b.i_orgb
											and not exists (select c.i_atk_ajuan,c.i_atk_terima  from e_ast_terima_atk_tm c
											where   a.i_atk_ajuan = c.i_atk_ajuan 
											)
											ORDER BY i_atk_ajuan
											limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuan"           =>(string)$result[$j]->i_atk_ajuan,
											"d_atk_ajuan"           =>(string)$result[$j]->d_atk_ajuan,
											"i_atk_terima"          =>(string)$result[$j]->itr,
											"d_atk_terima"          =>(string)$result[$j]->dtr,
											"i_atk_kwtbeli"         =>(string)$result[$j]->ikwt,
											"d_atk_beli"           	=>(string)$result[$j]->dkwt,
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
	
	public function queryBlmTerimaByOrgPrnt($pageNumber, $itemPerPage, $unitkr) {
		$status='Y';
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
				$result = $db->fetchAll("SELECT a.i_atk_ajuan,a.d_atk_ajuan,
											null as itr ,
											a.d_atk_ajuAn as dtr,
											null as ikwt,
											a.d_atk_ajuAn as dkwt,a.i_orgb,n_orgb 
											from e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
											where a.i_orgb = ? and  a.c_atk_setuju = ? and a.i_orgb=b.i_orgb
											and not exists (select c.i_atk_ajuan,c.i_atk_terima  from e_ast_terima_atk_tm c
											where   a.i_atk_ajuan = c.i_atk_ajuan 
											)
											
											UNION
											SELECT a.i_atk_ajuan,a.d_atk_ajuan,
											null as itr ,
											a.d_atk_ajuAn as dtr,
											null as ikwt,
											a.d_atk_ajuAn as dkwt,a.i_orgb,n_orgb 
											from e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
											where i_orgb_parent  = ?  and  a.c_atk_setuju = ? and a.i_orgb=b.i_orgb
											and not exists (select c.i_atk_ajuan,c.i_atk_terima  from e_ast_terima_atk_tm c
											where   a.i_atk_ajuan = c.i_atk_ajuan 
											)
											
											UNION
											SELECT a.i_atk_ajuan,a.d_atk_ajuan,
											null as itr ,
											a.d_atk_ajuAn as dtr,
											null as ikwt,
											a.d_atk_ajuAn as dkwt,a.i_orgb,n_orgb 
											from e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm C 
											where a.c_atk_setuju = ? and a.i_orgb=C.i_orgb
											and exists ( select * 
											from e_org_0_0_tm  B
											where B.i_orgb_parent  = ?
											         and B.i_orgb = C.i_orgb_parent
											             )     
											and not exists (select d.i_atk_ajuan,d.i_atk_terima  from e_ast_terima_atk_tm d
											where   a.i_atk_ajuan = d.i_atk_ajuan 
											)
											
											UNION
											SELECT a.i_atk_ajuan,a.d_atk_ajuan,
											null as itr ,
											a.d_atk_ajuAn as dtr,
											null as ikwt,
											a.d_atk_ajuAn as dkwt,a.i_orgb,n_orgb 
											from e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
											where a.c_atk_setuju = ? and a.i_orgb=b.i_orgb
											and i_orgb_parent   
													in ( select A.i_orgb from e_org_0_0_tm A
														 where exists 
													       ( select * from e_org_0_0_tm  B
													         where B.i_orgb_parent  = ?
													          and B.i_orgb = A.i_orgb_parent
													        )                 
													    )
											and not exists (select c.i_atk_ajuan,c.i_atk_terima  from e_ast_terima_atk_tm c
											where   a.i_atk_ajuan = c.i_atk_ajuan 
											)
											
											UNION
											SELECT a.i_atk_ajuan,a.d_atk_ajuan,
											null as itr ,
											a.d_atk_ajuAn as dtr,
											null as ikwt,
											a.d_atk_ajuAn as dkwt,a.i_orgb,n_orgb 
											from e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
											where a.c_atk_setuju = ? and a.i_orgb=b.i_orgb
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
											and not exists (select d.i_atk_ajuan,d.i_atk_terima  from e_ast_terima_atk_tm d
											where   a.i_atk_ajuan = d.i_atk_ajuan 
											)
											
											
											ORDER BY i_atk_ajuan
											",$where);
				$jmlResult = count($result);
				$hasilAkhir =$jmlResult;
				
			}
			else
			{
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT a.i_atk_ajuan,a.d_atk_ajuan,
											null as itr ,
											a.d_atk_ajuAn as dtr,
											null as ikwt,
											a.d_atk_ajuAn as dkwt,a.i_orgb,n_orgb 
											from e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
											where a.i_orgb = ? and  a.c_atk_setuju = ? and a.i_orgb=b.i_orgb
											and not exists (select c.i_atk_ajuan,c.i_atk_terima  from e_ast_terima_atk_tm c
											where   a.i_atk_ajuan = c.i_atk_ajuan 
											)
											
											UNION
											SELECT a.i_atk_ajuan,a.d_atk_ajuan,
											null as itr ,
											a.d_atk_ajuAn as dtr,
											null as ikwt,
											a.d_atk_ajuAn as dkwt,a.i_orgb,n_orgb 
											from e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
											where i_orgb_parent  = ?  and  a.c_atk_setuju = ? and a.i_orgb=b.i_orgb
											and not exists (select c.i_atk_ajuan,c.i_atk_terima  from e_ast_terima_atk_tm c
											where   a.i_atk_ajuan = c.i_atk_ajuan 
											)
											
											UNION
											SELECT a.i_atk_ajuan,a.d_atk_ajuan,
											null as itr ,
											a.d_atk_ajuAn as dtr,
											null as ikwt,
											a.d_atk_ajuAn as dkwt,a.i_orgb,n_orgb 
											from e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm C 
											where a.c_atk_setuju = ? and a.i_orgb=C.i_orgb
											and exists ( select * 
											from e_org_0_0_tm  B
											where B.i_orgb_parent  = ?
											         and B.i_orgb = C.i_orgb_parent
											             )     
											and not exists (select d.i_atk_ajuan,d.i_atk_terima  from e_ast_terima_atk_tm d
											where   a.i_atk_ajuan = d.i_atk_ajuan 
											)
											
											UNION
											SELECT a.i_atk_ajuan,a.d_atk_ajuan,
											null as itr ,
											a.d_atk_ajuAn as dtr,
											null as ikwt,
											a.d_atk_ajuAn as dkwt,a.i_orgb,n_orgb 
											from e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
											where a.c_atk_setuju = ? and a.i_orgb=b.i_orgb
											and i_orgb_parent   
													in ( select A.i_orgb from e_org_0_0_tm A
														 where exists 
													       ( select * from e_org_0_0_tm  B
													         where B.i_orgb_parent  = ?
													          and B.i_orgb = A.i_orgb_parent
													        )                 
													    )
											and not exists (select c.i_atk_ajuan,c.i_atk_terima  from e_ast_terima_atk_tm c
											where   a.i_atk_ajuan = c.i_atk_ajuan 
											)
											
											UNION
											SELECT a.i_atk_ajuan,a.d_atk_ajuan,
											null as itr ,
											a.d_atk_ajuAn as dtr,
											null as ikwt,
											a.d_atk_ajuAn as dkwt,a.i_orgb,n_orgb 
											from e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
											where a.c_atk_setuju = ? and a.i_orgb=b.i_orgb
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
											and not exists (select d.i_atk_ajuan,d.i_atk_terima  from e_ast_terima_atk_tm d
											where   a.i_atk_ajuan = d.i_atk_ajuan 
											)
											
											
											ORDER BY i_atk_ajuan
											limit $xLimit offset $xOffset",$where);
			 
			 
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuan"           =>(string)$result[$j]->i_atk_ajuan,
											"d_atk_ajuan"           =>(string)$result[$j]->d_atk_ajuan,
											"i_atk_terima"          =>(string)$result[$j]->itr,
											"d_atk_terima"          =>(string)$result[$j]->dtr,
											"i_atk_kwtbeli"         =>(string)$result[$j]->ikwt,
											"d_atk_beli"           	=>(string)$result[$j]->dkwt,
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
	//=========================terima blm lengkap ============================untuk TU=======================
	
	public function queryPenerimaanByUnitTU($pageNumber, $itemPerPage, $stat, $unitkr) {
		$status=$stat;
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $unitkr;
			$where[] = $status;
			//$where[] = $unitkr;
			//echo "TEST CONN ".$db->getConnection()."<br>";
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$result = $db->fetchAll("SELECT 
											i_atk_ajuan,null as d_atk_ajuan,
											a.i_atk_terima as itr,
											a.d_atk_terima as dtr,
											a.i_atk_kwtbeli as ikwt,
											a.d_atk_beli as dkwt,a.i_orgb,n_orgb
											FROM e_ast_terima_atk_tm a, e_org_0_0_tm b 
											where a.i_orgb like ? and  a.c_atk_setuju = ? and a.i_orgb=b.i_orgb  
											ORDER BY i_atk_ajuan",$where);
				$jmlResult = count($result);
				$hasilAkhir =$jmlResult;
				
			}
			else
			{
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT 
											i_atk_ajuan,null as d_atk_ajuan,
											a.i_atk_terima as itr,
											a.d_atk_terima as dtr,
											a.i_atk_kwtbeli as ikwt,
											a.d_atk_beli as dkwt,a.i_orgb,n_orgb
											FROM e_ast_terima_atk_tm a, e_org_0_0_tm b 
											where a.i_orgb like ? and  a.c_atk_setuju = ? and a.i_orgb=b.i_orgb
											ORDER BY i_atk_ajuan
											limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuan"           =>(string)$result[$j]->i_atk_ajuan,
											"d_atk_ajuan"           =>(string)$result[$j]->d_atk_ajuan,
											"i_atk_terima"          =>(string)$result[$j]->itr,
											"d_atk_terima"          =>(string)$result[$j]->dtr,
											"i_atk_kwtbeli"         =>(string)$result[$j]->ikwt,
											"d_atk_beli"           	=>(string)$result[$j]->dkwt,
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
	//=========================terima blm lengkap ============================untuk Org Parent=======================
	
	public function queryPenerimaanByOrgPrnt($pageNumber, $itemPerPage,$stat, $unitkr) {
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
			 	$result = $db->fetchAll("SELECT 
											i_atk_ajuan,null as d_atk_ajuan,
											a.i_atk_terima as itr,
											a.d_atk_terima as dtr,
											a.i_atk_kwtbeli as ikwt,
											a.d_atk_beli as dkwt,a.i_orgb,n_orgb
											FROM e_ast_terima_atk_tm a, e_org_0_0_tm b 
											where a.i_orgb = ? and  a.c_atk_setuju = ? and a.i_orgb=b.i_orgb
											
											UNION
											SELECT 
											i_atk_ajuan,null as d_atk_ajuan,
											a.i_atk_terima as itr,
											a.d_atk_terima as dtr,
											a.i_atk_kwtbeli as ikwt,
											a.d_atk_beli as dkwt,a.i_orgb,n_orgb 
											FROM e_ast_terima_atk_tm a, e_org_0_0_tm b 
											where i_orgb_parent  = ? and  a.c_atk_setuju = ? and a.i_orgb=b.i_orgb
											
											UNION
											SELECT 
											i_atk_ajuan,null as d_atk_ajuan,
											a.i_atk_terima as itr,
											a.d_atk_terima as dtr,
											a.i_atk_kwtbeli as ikwt,
											a.d_atk_beli as dkwt,a.i_orgb,n_orgb 
											FROM e_ast_terima_atk_tm a, e_org_0_0_tm C 
											where a.c_atk_setuju = ? and a.i_orgb=C.i_orgb
											and exists ( select * 
											from e_org_0_0_tm  B
											where B.i_orgb_parent  = ?
											         and B.i_orgb = C.i_orgb_parent
											             )      
											
											UNION
											SELECT 
											i_atk_ajuan,null as d_atk_ajuan,
											a.i_atk_terima as itr,
											a.d_atk_terima as dtr,
											a.i_atk_kwtbeli as ikwt,
											a.d_atk_beli as dkwt,a.i_orgb,n_orgb 
											FROM e_ast_terima_atk_tm a, e_org_0_0_tm b 
											where a.c_atk_setuju = ? and a.i_orgb=b.i_orgb
											and i_orgb_parent   
													in ( select A.i_orgb from e_org_0_0_tm A
														 where exists 
													       ( select * from e_org_0_0_tm  B
													         where B.i_orgb_parent  = ?
													          and B.i_orgb = A.i_orgb_parent
													        )                 
													    )
											
											UNION
											SELECT 
											i_atk_ajuan,null as d_atk_ajuan,
											a.i_atk_terima as itr,
											a.d_atk_terima as dtr,
											a.i_atk_kwtbeli as ikwt,
											a.d_atk_beli as dkwt,a.i_orgb,n_orgb 
											FROM e_ast_terima_atk_tm a, e_org_0_0_tm b 
											where a.c_atk_setuju = ? and a.i_orgb=b.i_orgb
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
													    )",$where);
				$jmlResult = count($result);
				$hasilAkhir =$jmlResult;
				
			}
			else
			{
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT 
											i_atk_ajuan,null as d_atk_ajuan,
											a.i_atk_terima as itr,
											a.d_atk_terima as dtr,
											a.i_atk_kwtbeli as ikwt,
											a.d_atk_beli as dkwt,a.i_orgb,n_orgb
											FROM e_ast_terima_atk_tm a, e_org_0_0_tm b 
											where a.i_orgb = ? and  a.c_atk_setuju = ? and a.i_orgb=b.i_orgb
											
											UNION
											SELECT 
											i_atk_ajuan,null as d_atk_ajuan,
											a.i_atk_terima as itr,
											a.d_atk_terima as dtr,
											a.i_atk_kwtbeli as ikwt,
											a.d_atk_beli as dkwt,a.i_orgb,n_orgb 
											FROM e_ast_terima_atk_tm a, e_org_0_0_tm b 
											where i_orgb_parent  = ? and  a.c_atk_setuju = ? and a.i_orgb=b.i_orgb
											
											UNION
											SELECT 
											i_atk_ajuan,null as d_atk_ajuan,
											a.i_atk_terima as itr,
											a.d_atk_terima as dtr,
											a.i_atk_kwtbeli as ikwt,
											a.d_atk_beli as dkwt,a.i_orgb,n_orgb 
											FROM e_ast_terima_atk_tm a, e_org_0_0_tm C 
											where a.c_atk_setuju = ? and a.i_orgb=C.i_orgb
											and exists ( select * 
											from e_org_0_0_tm  B
											where B.i_orgb_parent  = ?
											         and B.i_orgb = C.i_orgb_parent
											             )      
											
											UNION
											SELECT 
											i_atk_ajuan,null as d_atk_ajuan,
											a.i_atk_terima as itr,
											a.d_atk_terima as dtr,
											a.i_atk_kwtbeli as ikwt,
											a.d_atk_beli as dkwt,a.i_orgb,n_orgb 
											FROM e_ast_terima_atk_tm a, e_org_0_0_tm b 
											where a.c_atk_setuju = ? and a.i_orgb=b.i_orgb
											and i_orgb_parent   
													in ( select A.i_orgb from e_org_0_0_tm A
														 where exists 
													       ( select * from e_org_0_0_tm  B
													         where B.i_orgb_parent  = ?
													          and B.i_orgb = A.i_orgb_parent
													        )                 
													    )
											
											UNION
											SELECT 
											i_atk_ajuan,null as d_atk_ajuan,
											a.i_atk_terima as itr,
											a.d_atk_terima as dtr,
											a.i_atk_kwtbeli as ikwt,
											a.d_atk_beli as dkwt,a.i_orgb,n_orgb 
											FROM e_ast_terima_atk_tm a, e_org_0_0_tm b 
											where a.c_atk_setuju = ? and a.i_orgb=b.i_orgb
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
														
										
											ORDER BY itr
											limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuan"           =>(string)$result[$j]->i_atk_ajuan,
											"d_atk_ajuan"           =>(string)$result[$j]->d_atk_ajuan,
											"i_atk_terima"          =>(string)$result[$j]->itr,
											"d_atk_terima"          =>(string)$result[$j]->dtr,
											"i_atk_kwtbeli"         =>(string)$result[$j]->ikwt,
											"d_atk_beli"           	=>(string)$result[$j]->dkwt,
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
	//=================================terima lengkap ============================================================
	
	public function queryTerimaLkpM($pageNumber, $itemPerPage, $unitkr) {
		$status='Y';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $unitkr;
			//$where[] = $status;
			//$where[] = $unitkr;
			//echo "TEST CONN ".$db->getConnection()."<br>";
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$result = $db->fetchAll("SELECT 
											null as i_atk_ajuan,null as d_atk_ajuan,
											d.i_atk_terima as itr,
											d.d_atk_terima as dtr,
											d.i_atk_kwtbeli as ikwt,
											d.d_atk_beli as dkwt
											FROM e_ast_terima_atk_tm d where i_orgb=? and c_atk_setuju='B'  
											ORDER BY i_atk_ajuan",$where);
				$jmlResult = count($result);
				$hasilAkhir =$jmlResult;
				
			}
			else
			{
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT 
											null as i_atk_ajuan,null as d_atk_ajuan,
											d.i_atk_terima as itr,
											d.d_atk_terima as dtr,
											d.i_atk_kwtbeli as ikwt,
											d.d_atk_beli as dkwt
											FROM e_ast_terima_atk_tm d where i_orgb=? and c_atk_setuju='B'  
											ORDER BY i_atk_ajuan
											limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuan"           =>(string)$result[$j]->i_atk_ajuan,
											"d_atk_ajuan"           =>(string)$result[$j]->d_atk_ajuan,
											"i_atk_terima"          =>(string)$result[$j]->itr,
											"d_atk_terima"          =>(string)$result[$j]->dtr,
											"i_atk_kwtbeli"         =>(string)$result[$j]->ikwt,
											"d_atk_beli"           	=>(string)$result[$j]->dkwt);
								  
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	//=======================verifikasi =====================================================
	
	public function queryLihatVerifikasi($pageNumber, $itemPerPage, $unitkr) {
		$status='Y';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			//$where[] = $unitkr;
			$where[] = $status;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$result = $db->fetchAll("SELECT i_atk_terima,d_atk_terima FROM e_ast_terima_atk_tm 
										where  c_atk_setuju=? ORDER BY i_atk_terima",$where);
				$jmlResult = count($result);
				$hasilAkhir =$jmlResult;
				
			 }
			 else
			 {
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT i_atk_terima,d_atk_terima FROM e_ast_terima_atk_tm 
										 where  c_atk_setuju=? ORDER BY i_atk_terima
											limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_terima"           =>(string)$result[$j]->i_atk_terima,
											"d_atk_terima"           =>(string)$result[$j]->d_atk_terima);
								  
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	//===================================tolak verifikasi =======================================
	
	public function queryLihatTolakVerifikasi($pageNumber, $itemPerPage, $unitkr) {
		$status='T';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			//$where[] = $unitkr;
			$where[] = $status;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$result = $db->fetchAll("SELECT i_atk_terima,d_atk_terima FROM e_ast_terima_atk_tm 
										where  c_atk_setuju=? ORDER BY i_atk_terima",$where);
				$jmlResult = count($result);
				$hasilAkhir =$jmlResult;
				
			 }
			 else
			 {
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT i_atk_terima,d_atk_terima FROM e_ast_terima_atk_tm 
										 where  c_atk_setuju=? ORDER BY i_atk_terima
											limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_terima"           =>(string)$result[$j]->i_atk_terima,
											"d_atk_terima"           =>(string)$result[$j]->d_atk_terima);
								  
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