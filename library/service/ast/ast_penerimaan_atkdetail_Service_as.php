<?php
class ast_penerimaan_atkdetail_Service {
   
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

 
	//===============================penerimaan atk detail ========================================================
   
    public function nopengFormated($nomax) {
		
	    $pjg = strlen($nomax);
		if ($pjg = 1 ){
		   $hasil = '00000'.$nomax;
		}
		else if($pjg = 2 ){
		 $hasil = '0000'.$nomax;
		}
		else if($pjg = 3 ){
		 $hasil = '000'.$nomax;
		}
		else if($pjg = 4 ){
		 $hasil = '00'.$nomax;
		}
		else if($pjg = 5 ){
		 $hasil = '0'.$nomax;
		}
		else 
		  $hasil = $nomax; 
		 
		return $hasil;
	} 
		
	public function queryPenerimaanAtkD($nopeng) {
	    
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT b.c_atk_ctgr,d.n_atk_ctgr,b.c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,
									q_atk_terima,q_atk_realterima,b.v_atk_hrgsatuan,i_atk_kwtbeli,d_atk_beli,q_atk_stock ,c_matauang,q_atk_setujubeli
									FROM e_ast_barang_atk_tr a, e_ast_terima_itematk_tm b,e_ast_terima_atk_tm c,
									e_ast_kategori_atk_tr d,e_ast_ajuanbeli_itematk_tm e
									where b.i_atk_terima = ? and a.c_atk = b.c_atk and b.i_atk_terima = c.i_atk_terima 
									and b.c_atk_ctgr=d.c_atk_ctgr and a.c_atk = e.c_atk and b.c_atk_ctgr=e.c_atk_ctgr and
									c.i_atk_ajuan = e.i_atk_ajuan' ,$nopeng);
         $jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_atk_ctgr"             =>(string)$result[$j]->c_atk_ctgr,
								   "n_atk_ctgr"             =>(string)$result[$j]->n_atk_ctgr,
								   "c_atk"                  =>(string)$result[$j]->c_atk,
								   "q_atk_terima"           =>(string)$result[$j]->q_atk_terima,
	                               "n_atk"                  =>(string)$result[$j]->n_atk,
								   "n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
								   "q_atk_realterima"       =>(string)$result[$j]->q_atk_realterima,
								   "v_atk_hrgsatuan"       	=>(string)$result[$j]->v_atk_hrgsatuan,
								   "i_atk_kwtbeli"          =>(string)$result[$j]->i_atk_kwtbeli,
								   "d_atk_beli"             =>(string)$result[$j]->d_atk_beli,
								   "q_atk_stock"           	=>(string)$result[$j]->q_atk_stock,
								   "c_matauang"				=>(string)$result[$j]->c_matauang,
								   "q_atk_setujubeli"		=>(string)$result[$j]->q_atk_setujubeli);
								  
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function queryPenerimaanAtkD1($nopeng) {
	    
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT b.c_atk_ctgr,d.n_atk_ctgr,b.c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,
									q_atk_terima,q_atk_realterima,b.v_atk_hrgsatuan,i_atk_kwtbeli,d_atk_beli,q_atk_stock,c_matauang,q_atk_setujubeli
									FROM e_ast_barang_atk_tr a, e_ast_terima_itematk_tm b,e_ast_terima_atk_tm c,
									e_ast_kategori_atk_tr d,,e_ast_ajuanbeli_itematk_tm e
									where b.i_atk_terima = ? and a.c_atk = b.c_atk and b.i_atk_terima = c.i_atk_terima 
									and b.c_atk_ctgr=d.c_atk_ctgr and a.c_atk = e.c_atk and b.c_atk_ctgr=e.c_atk_ctgr and
									c.i_atk_ajuan = e.i_atk_ajuan' ,$nopeng);
         $jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++) {
		
           $hasilAkhir[$j] = array("c_atk_ctgr"             =>(string)$result[$j]->c_atk_ctgr,
								   "n_atk_ctgr"             =>(string)$result[$j]->n_atk_ctgr,
								   "c_atk"                  =>(string)$result[$j]->c_atk,
								   "q_atk_terima"           =>(string)$result[$j]->q_atk_terima,
	                               "n_atk"                  =>(string)$result[$j]->n_atk,
								   "n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
								   "q_atk_realterima"       =>(string)$result[$j]->q_atk_realterima,
								   "v_atk_hrgsatuan"       	=>(string)$result[$j]->v_atk_hrgsatuan,
								   "i_atk_kwtbeli"          =>(string)$result[$j]->i_atk_kwtbeli,
								   "d_atk_beli"             =>(string)$result[$j]->d_atk_beli,
								   "q_atk_stock"           	=>(string)$result[$j]->q_atk_stock,
								   "c_matauang"				=>(string)$result[$j]->c_matauang,
								   "q_atk_setujubeli"		=>(string)$result[$j]->q_atk_setujubeli);
								  
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	//p.ndang
	public function queryPenerimaanAtkD1_pd($nopeng) {
	    
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT b.c_atk_ctgr,d.n_atk_ctgr,b.c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_terima,q_atk_realterima,b.v_atk_hrgsatuan,i_atk_kwtbeli,d_atk_beli,q_atk_stock 
		                         FROM e_ast_barang_atk_tr a, e_ast_terima_itematk_tm b,e_ast_terima_atk_tm c,e_ast_kategori_atk_tr d
								 where b.i_atk_ajuan = ? and a.c_atk = b.c_atk and b.i_atk_terima = c.i_atk_terima and b.c_atk_ctgr=d.c_atk_ctgr' ,$nopeng);
         $jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++) {
		// 
           $hasilAkhir[$j] = array("c_atk_ctgr"             =>(string)$result[$j]->c_atk_ctgr,
								   "n_atk_ctgr"             =>(string)$result[$j]->n_atk_ctgr,
								   "c_atk"                  =>(string)$result[$j]->c_atk,
								   "q_atk_terima"           =>(string)$result[$j]->q_atk_terima,
	                               "n_atk"                  =>(string)$result[$j]->n_atk,
								   "n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
								   "q_atk_realterima"       =>(string)$result[$j]->q_atk_realterima,
								   "v_atk_hrgsatuan"       =>(string)$result[$j]->v_atk_hrgsatuan,
								   "i_atk_kwtbeli"          =>(string)$result[$j]->i_atk_kwtbeli,
								   "d_atk_beli"             =>(string)$result[$j]->d_atk_beli,
								   "q_atk_stock"           =>(string)$result[$j]->q_atk_stock);
								  
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function insertTerimaAtkD(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
	     $db->beginTransaction();
	     $atk_dtl_parm = array("i_atk_terima"         		=>$data['nopeng'],
	                           "c_atk"    	    	        =>trim($data['KdBarang']),
						       "c_atk_ctgr"                 =>$data['KatBarang'],
						       "q_atk_terima"  		        =>$data['Jml'],
							   "q_atk_realterima"  		    =>0,
							   "v_atk_hrgsatuan"            =>$data['HrgSatuan'],
							   "c_matauang"                 =>$data['kdmatauang'],
						       "i_entry"       		        =>$data['nuser'],
						       "d_entry"       		        =>date("Y-m-d"));
	   
	     $db->insert('e_ast_terima_itematk_tm',$atk_dtl_parm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
	 
	public function updateTerimaAtkD(array $data) {
	    
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $atk_dtl_parm = array("q_atk_terima"  		    =>$data['JmlPenerimaan'],
						       "v_atk_hrgsatuan"     	=>$data['HrgSatuan'],
							   "c_matauang"     		=>$data['kdmatauang'],
						       "i_entry"       		    =>$data['nuser'],
						       "d_entry"       		    =>date("Y-m-d"));
	    
		 $where[] = "i_atk_terima  =  '".trim($data['nopeng'])."'";
	     $where[] = "c_atk        =  '".trim($data['KdBarang'])."'";
		 $where[] = "c_atk_ctgr   =  '".trim($data['KatBarang'])."'";
		 $db->update('e_ast_terima_itematk_tm',$atk_dtl_parm, $where);
		 
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function updateTerimaAtkM(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $atk_dtl_parm = array("i_atk_kwtbeli"  	    =>$data['noKwitansi'],
						       "i_entry"       		    =>$data['nuser'],
						       "d_entry"       		    =>date("Y-m-d"));
	     
		 $where[] = "i_atk_terima  =  '".trim($data['nopeng'])."'";
	    
		 $db->update('e_ast_terima_atk_tm',$atk_dtl_parm, $where);
		 
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function deletPenerimaanAtkD2($nopeng) {
	      
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	 
		 $where[] = "i_atk_terima  =  '".trim($data['nopeng'])."'";
	      $db->delete('e_ast_terima_itematk_tm', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	public function deletPenerimaanAtkD(array $data) {
	     
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	 
		 $where[] = "i_atk_terima  =  '".trim($data['nopeng'])."'";
	     $where[] = "c_atk        =  '".trim($data['KdBarang'])."'";

		 $db->delete('e_ast_terima_itematk_tm', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function queryAjuanBeliAtkD($nopeng) {
	    
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT b.c_atk_ctgr,b.c_atk,q_atk_beli,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stock 
		                         FROM e_ast_barang_atk_tr a, e_ast_ajuanbeli_itematk_tm b
								 where i_atk_ajuan = ? and a.c_atk = b.c_atk',$nopeng);
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_atk_ctgr"             =>(string)$result[$j]->c_atk_ctgr,
								   "c_atk"                  =>(string)$result[$j]->c_atk,
								   "q_atk_beli"             =>(string)$result[$j]->q_atk_beli,
	                               "n_atk"                  =>(string)$result[$j]->n_atk,
								   "n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stock"            =>(string)$result[$j]->q_atk_stock);
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getBarangListAll($nopeng) {
        
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT a.c_atk,a.c_atk_ctgr,q_atk_beli,n_atk,n_atk_satuan,n_atk_merek,
		 n_atk_tipe,q_atk_stock FROM e_ast_ajuanbeli_itematk_tm a,e_ast_barang_atk_tr b 
		 where i_atk_ajuan ? and a.c_atk_ctgr= b.c_atk_ctgr and a.c_atk = b.c_atk oRDER BY c_atk',$nopeng);
	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_atk"           =>(string)$result[$j]->c_atk,
								   "c_atk_ctgr"      =>(string)$result[$j]->c_atk_ctgr,
								   "q_atk_beli"      =>(string)$result[$j]->q_atk_beli,
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
	
	/* SERVICE UNTUK PENGAUAN PERMINTAAN ATK (STOCK TERSEDIA */
	
	public function insertAjuanMintaAtkD(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	
	   try {
	     $db->beginTransaction();
	     $atk_dtl_parm = array("i_atk_ajuanminta"        	=>$data['nopeng'],
	                           "c_atk"    	    	        =>$data['KdBarang'],
						       "c_atk_ctgr"                 =>$data['KatBarang'],
						       "q_atk_minta"  		        =>$data['Jml'],
							   "q_atk_setuju"  		        =>0,
						       "i_entry"       		        =>$data['nuser'],
						       "d_entry"       		        =>date("Y-m-d"));
	   
	     $db->insert('e_ast_minta_itematk_tm',$atk_dtl_parm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	 
    
	 
	public function updateAjuanMintaAtkD(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $atk_dtl_parm = array("q_atk_minta"  		    =>$data['Jml'],
						       "q_atk_setuju"       	=>0,
						       "i_entry"       		    =>$data['nuser'],
						       "d_entry"       		    =>date("Y-m-d"));
	     
		 $where[] = "i_atk_ajuanterima  =  '".trim($data['nopeng'])."'";
	     $where[] = "c_atk        =  '".trim($data['KdBarang'])."'";
		 $where[] = "c_atk_ctgr   =  '".trim($data['KatBarang'])."'";
		 $db->update('e_ast_minta_itematk_tm',$atk_dtl_parm, $where);
		 
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
     
	 
	public function deletAjuanMintaAtkD(array $data) {
	     
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	 
		 $where[] = "i_atk_ajuanterima  =  '".trim($data['nopeng'])."'";
	     $where[] = "c_atk        =  '".trim($data['KdBarang'])."'";

		 $db->delete('e_ast_minta_itematk_tm', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function queryAjuanMintaAtkD($nopeng) {
	    
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT b.c_atk_ctgr,b.c_atk,q_atk_minta,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stock 
		                         FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b
								 where i_atk_ajuan = ? and a.c_atk = b.c_atk',$nopeng);
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_atk_ctgr"             =>(string)$result[$j]->c_atk_ctgr,
								   "c_atk"                  =>(string)$result[$j]->c_atk,
								   "q_atk_minta"            =>(string)$result[$j]->q_atk_beli,
	                               "n_atk"                  =>(string)$result[$j]->n_atk,
								   "n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stock"            =>(string)$result[$j]->q_atk_stock);
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function queryVerifikasiAtkD($noajuan) {
        
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT b.c_atk_ctgr,b.c_atk,n_atk,q_atk_terima,b.v_atk_hrgsatuan,q_atk_realterima
		                         FROM e_ast_barang_atk_tr a, e_ast_terima_itematk_tm b,e_ast_terima_atk_tm c
								 where b.i_atk_terima = ? and a.c_atk = b.c_atk and b.i_atk_terima = c.i_atk_terima' ,$noajuan);
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_atk_ctgr"             =>(string)$result[$j]->c_atk_ctgr,
								   "c_atk"                  =>(string)$result[$j]->c_atk,
								   "n_atk"                  =>(string)$result[$j]->n_atk,
								   "q_atk_terima"           =>(string)$result[$j]->q_atk_terima,
								   "q_atk_realterima"		=>(string)$result[$j]->q_atk_realterima,
								   "v_atk_hrgsatuan"       	=>(string)$result[$j]->v_atk_hrgsatuan);
								 
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function updateVerifikasi(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	    try {
	     $db->beginTransaction();
	     $atk_dtl_parm = array("q_atk_realterima"  		    =>$data['Jml'],
						       "i_entry"       		        =>$data['nuser'],
						       "d_entry"       		        =>date("Y-m-d"));
	    
		 $where[] = "i_atk_terima  =  '".trim($data['nopeng'])."'";
	     $where[] = "c_atk         =  '".trim($data['KdBarang'])."'";
		 $where[] = "c_atk_ctgr    =  '".trim($data['KatBarang'])."'";
		 $db->update('e_ast_terima_itematk_tm',$atk_dtl_parm, $where);
		 
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function updateStock(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	    try {
	     $db->beginTransaction();
		 $KdBarang = $data['KdBarang'];
		 $jml = $db->fetchOne("select q_atk_stock 
		 						FROM e_ast_barang_atk_tr where c_atk = ?",$KdBarang); 
		 								 
		 $where[] = "c_atk         =  '".trim($data['KdBarang'])."'";
		 $where[] = "c_atk_ctgr    =  '".trim($data['KatBarang'])."'";		 
	        $atk_dtl_parm = array("q_atk_stock"  		        =>$jml + $data['Jml'],
						       "i_entry"       		        =>$data['nuser'],
						       "d_entry"       		        =>date("Y-m-d"));
	    
	     
		 $db->update('e_ast_barang_atk_tr',$atk_dtl_parm, $where);
		 
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	
	public function queryMonitorAtkD($pageNumber, $itemPerPage, $nokwitansi) {
	
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		
			$where[] = $nokwitansi;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$result = $db->fetchAll("SELECT distinct b.c_atk_ctgr,b.c_atk,n_atk,q_atk_terima,b.v_atk_hrgsatuan
					                         FROM e_ast_barang_atk_tr a, e_ast_terima_itematk_tm b,e_ast_terima_atk_tm c
											 where c.i_atk_kwtbeli = ? and a.c_atk = b.c_atk and b.i_atk_terima = c.i_atk_terima",$where);
				$jmlResult = count($result);
				$hasilAkhir =$jmlResult;
				
			 }
			 else
			 {
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT distinct b.c_atk_ctgr,b.c_atk,n_atk,q_atk_terima,b.v_atk_hrgsatuan
					                         FROM e_ast_barang_atk_tr a, e_ast_terima_itematk_tm b,e_ast_terima_atk_tm c
											 where c.i_atk_kwtbeli = ? and a.c_atk = b.c_atk and b.i_atk_terima = c.i_atk_terima ORDER BY c_atk_ctgr
											limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					 $hasilAkhir[$j] = array("c_atk_ctgr"             	=>(string)$result[$j]->c_atk_ctgr,
											   "c_atk"                  =>(string)$result[$j]->c_atk,
											   "n_atk"                  =>(string)$result[$j]->n_atk,
											   "q_atk_terima"           =>(string)$result[$j]->q_atk_terima,
											   "v_atk_hrgsatuan"       	=>(string)$result[$j]->v_atk_hrgsatuan);
			}	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	public function queryMonitorAtkD2($nokwitansi) {
        
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT distinct b.c_atk_ctgr,b.c_atk,n_atk,q_atk_terima,b.v_atk_hrgsatuan
		                         FROM e_ast_barang_atk_tr a, e_ast_terima_itematk_tm b,e_ast_terima_atk_tm c
								 where c.i_atk_kwtbeli = ? and a.c_atk = b.c_atk and b.i_atk_terima = c.i_atk_terima' ,$nokwitansi);
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_atk_ctgr"             =>(string)$result[$j]->c_atk_ctgr,
								   "c_atk"                  =>(string)$result[$j]->c_atk,
								   "n_atk"                  =>(string)$result[$j]->n_atk,
								   "q_atk_terima"             =>(string)$result[$j]->q_atk_terima,
								   "v_atk_hrgsatuan"       =>(string)$result[$j]->v_atk_hrgsatuan);
								 
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
}	 	
?>