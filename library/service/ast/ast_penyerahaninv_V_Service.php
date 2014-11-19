<?php
class ast_penyerahaninv_Service {
   
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
	public function insertAstDirTm(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
		 $atk_mast_prm = array("i_barang_serah"         		=>$data['nopeng'],
	                           "d_barang_serah"    	        	=>$data['dbarangserah'],
						       "i_peg_nipterima"                =>$data['nipPenerima'],
							   "i_peg_nippemberi"               =>$data['nipPemberi'],
							   "i_barang_baserah"  	            =>' ',
							   "i_orgb_pemberi"                 =>$data['orgPemberi'],
							   "i_orgb_penerima"                =>$data['orgPenerima'],
						       "c_barang_statserah"  	        =>$data['status'],
							   "e_keterangan"					=>$data['ket'],
						       "i_entry"       		            =>$data['nuser'],
						       "d_entry"       		        =>date("Y-m-d"));
	    
	     $db->insert('e_ast_dir_0_tm',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function insertAstDirItemTm(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
		 $atk_mast_prm = array("i_barang_serah"         		=>$data['nopeng'],
	                           "d_aset_thnanggar"    	        =>$data['dasetthnanggar'],
						       "c_barang"                       =>$data['cbarang'],
							   "i_aset"              		    =>$data['iaset'],
							   "d_barang_peroleh"  	            =>$data['dbarangperoleh'], 
							   "i_ruang"                        =>$data['iruang'],
							   "i_entry"       		            =>$data['nuser'],
						       "d_entry"       		        =>date("Y-m-d"));
	    
	     $db->insert('e_ast_dir_item_tm',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	} 
     
		 
	public function DeleteAstDirTm($nopeng) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     
	     $db->beginTransaction();
	     $where[] = "i_barang_serah = '". $nopeng ."'";
		 $db->delete('e_ast_dir_0_tm', $where);
		 $db->delete('e_ast_dir_item_tm', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
     
public function getPenyerahanInvList($pageNumber,$itemPerPage,$unitkr) {
       $status='T';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	 FROM e_ast_dir_0_tm where i_orgb_pemberi = ? and c_barang_statserah=?",$where); ;
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		 
		 $result = $db->fetchAll("SELECT i_barang_serah,d_barang_serah,i_peg_nipterima,i_orgb_penerima,
		                         i_peg_nippemberi,i_orgb_pemberi,e_keterangan 
								 FROM e_ast_dir_0_tm 
							   	 where i_orgb_pemberi = ? and c_barang_statserah = ? 
								 limit $xLimit offset $xOffset",$where); 
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_barang_serah"           =>(string)$result[$j]->i_barang_serah,
								   "d_barang_serah"           =>(string)$result[$j]->d_barang_serah,
								   "i_peg_nipterima"           =>(string)$result[$j]->i_peg_nipterima,
								   "i_orgb_penerima"           =>(string)$result[$j]->i_orgb_penerima,
								   "i_peg_nippemberi"           =>(string)$result[$j]->i_peg_nippemberi,
								   "i_orgb_pemberi"           =>(string)$result[$j]->i_orgb_pemberi,
								   "e_keterangan"           =>(string)$result[$j]->e_keterangan);
								  
								  
							       
		
		  }
         }
        }		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getPenyerahanInvLainList($pageNumber,$itemPerPage,$unitkr) {
       $status='T';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;		  
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		  if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	 FROM e_ast_dil_0_tm where i_orgb_pemberi=? and c_barang_statserah=?",$where); ;
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		 $result = $db->fetchAll("SELECT i_barang_serah,d_barang_serah,i_peg_nipterima,i_orgb_penerima,
		                         i_peg_nippemberi,i_orgb_pemberi,e_keterangan 
								 FROM e_ast_dil_0_tm 
								 where i_orgb_pemberi=? and c_barang_statserah = ? 
								 limit $xLimit offset $xOffset",$where); 
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_barang_serah"           =>(string)$result[$j]->i_barang_serah,
								   "d_barang_serah"           =>(string)$result[$j]->d_barang_serah,
								   "i_peg_nipterima"           =>(string)$result[$j]->i_peg_nipterima,
								   "i_orgb_penerima"           =>(string)$result[$j]->i_orgb_penerima,
								   "i_peg_nippemberi"           =>(string)$result[$j]->i_peg_nippemberi,
								   "i_orgb_pemberi"           =>(string)$result[$j]->i_orgb_pemberi,
								   "e_keterangan"           =>(string)$result[$j]->e_keterangan);
								  
								  
							       
		
		 }
        }
		}		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
public function getPenyInvList($nopeng) {
       $status='T';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
	     $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT c_barang,d_aset_thnanggar,to_char(no_aset,'09999') as no_aset,i_ruang, 
		 merk_type,ur_sskel
		 FROM e_ast_dir_item_tm a ,
		      e_sabm_t_master_tm c,e_ast_sskel_0_tr d
		 where  a.i_aset =  c.no_aset  and a.i_barang_serah = ?
		   and  a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
								     and substr(c.kd_brg,2,2) = d.kd_bid 
									 and substr(c.kd_brg,4,2) = d.kd_kel
									 and substr(c.kd_brg,6,2) = d.kd_skel
									 and substr(c.kd_brg,8,3) = d.kd_sskel",$nopeng);
		
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_barang"           =>(string)$result[$j]->c_barang,
								   "d_aset_thnanggar"           =>(string)$result[$j]->d_aset_thnanggar,
								   "no_aset"           =>(string)$result[$j]->no_aset,
								   "i_ruang"           =>(string)$result[$j]->i_ruang,
								   "merk_type"           =>(string)$result[$j]->merk_type,
								   "ur_sskel"           =>(string)$result[$j]->ur_sskel);
								  
								  
			  			       
		
		 }
        }	
        		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
 
public function getPenyInvListbyKode($kode,$noas) {
        $status='T';
		$params =  array();
		$params[] = $kode;
		$params[] = $noas;
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT c_barang,d_aset_thnanggar,to_char(no_aset,'09999') as no_aset,i_ruang,e_keterangan,
		 merk_type,ur_sskel,d_barang_peroleh
		 FROM e_ast_dir_item_tm a,e_ast_dir_0_tm b,
		      e_sabm_t_master_tm c,e_ast_sskel_0_tr d
		 where a.i_barang_serah = b.i_barang_serah	and c_barang=? and no_aset=? and  a.i_aset =  c.no_aset  
		   and a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
								     and substr(c.kd_brg,2,2) = d.kd_bid 
									 and substr(c.kd_brg,4,2) = d.kd_kel
									 and substr(c.kd_brg,6,2) = d.kd_skel
									 and substr(c.kd_brg,8,3) = d.kd_sskel",$params);
		
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_barang"           =>(string)$result[$j]->c_barang,
								   "d_aset_thnanggar"           =>(string)$result[$j]->d_aset_thnanggar,
								   "no_aset"           =>(string)$result[$j]->no_aset,
								   "i_ruang"           =>(string)$result[$j]->i_ruang,
								   "e_keterangan"           =>(string)$result[$j]->e_keterangan,
								   "merk_type"           =>(string)$result[$j]->merk_type,
								   "ur_sskel"           =>(string)$result[$j]->ur_sskel,
								   "d_barang_peroleh"           =>(string)$result[$j]->d_barang_peroleh);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 	
 	 
	public function UpdateAstDirTm(array $data) {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $personal_parm = array("i_peg_nipterima"  		 =>trim($data['nipPenerima']),
						       "i_orgb_penerima"  		 =>substr($data['orgPenerima'],1,6),
							   "i_peg_nippemberi"  		 =>trim($data['nipPemberi']),
							   "i_orgb_pemberi"  	 	 =>substr($data['orgPemberi'],1,6),
							   "e_keterangan"  	 	     =>trim($data['ktr']),
							   "i_entry"       		     =>$data['nuser'],
						       "d_entry"       		     =>date("Y-m-d"));
						  	    
							   
							   
	     $where[] = "i_barang_serah  =  '".$data['nopeng']."'";
		
	     $db->update('e_ast_dir_0_tm',$personal_parm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function UpdStsAstDirTm(array $data) {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $personal_parm = array("c_barang_statserah"  	     =>$data['status'],
						         "i_entry"       		     =>"ast",
						         "d_entry"       		     =>date("Y-m-d"));
						  	    
							   
							   
	     $where[] = "i_barang_serah  =  '".$data['nopeng']."'";
		
	     $db->update('e_ast_dir_0_tm',$personal_parm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
    public function UpdateAstDirItem(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $item_parm = array("i_ruang"  		             =>$data['iruang'],
							 "i_entry"       		            =>$data['nuser'],
						     "d_entry"       		     =>date("Y-m-d"));
						  	    
							   
							   
	      $where[] = "i_barang_serah  =  '".$data['nopeng']."'";
		  $where[] = "c_barang        =  '".$data['cbarang']."'";
		  $where[] = "i_aset          =  '".$data['iaset']."'";
		  $where[] = "d_aset_thnanggar   =  '".$data['dasetthnanggar']."'";
	     $db->update('e_ast_dir_item_tm',$item_parm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
   public function DelAstDirItem(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     
	      $where[] = "i_barang_serah  =  '".$data['nopeng']."'";
		  $where[] = "c_barang        =  '".$data['cbarang']."'";
		  $where[] = "i_aset          =  '".$data['iaset']."'";
		
	     $db->delete('e_ast_dir_item_tm', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	/*******************/
	public function getPenyInvLainListbyKode($kode,$noas) {
        $status='T';
		$params =  array();
		$params[] = $kode;
		$params[] = $noas;
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT c_barang,d_anggaran,to_char(no_aset,'09999') as no_aset,n_aset_lokasifisik, 
		 merk_type,ur_sskel,d_perolehan
		 FROM e_ast_dil_item_tm a, 
		      e_sabm_t_master_tm c,e_ast_sskel_0_tr d
		 where  c_barang=? and no_aset=? and  a.i_aset =  c.no_aset  
		   and a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
								     and substr(c.kd_brg,2,2) = d.kd_bid 
									 and substr(c.kd_brg,4,2) = d.kd_kel
									 and substr(c.kd_brg,6,2) = d.kd_skel
									 and substr(c.kd_brg,8,3) = d.kd_sskel",$params);
		
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_barang"           =>(string)$result[$j]->c_barang,
								   "d_anggaran"           =>(string)$result[$j]->d_anggaran,
								   "no_aset"           =>(string)$result[$j]->no_aset,
								   "n_aset_lokasifisik"           =>(string)$result[$j]->n_aset_lokasifisik,
								   "merk_type"           =>(string)$result[$j]->merk_type,
								   "ur_sskel"           =>(string)$result[$j]->ur_sskel,
								   "d_perolehan"           =>(string)$result[$j]->d_perolehan);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getPenyInvLainList($nopeng) {
       $status='T';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
	     $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT c_barang,d_anggaran,to_char(no_aset,'09999') as no_aset,n_aset_lokasifisik, 
		 merk_type,ur_sskel
		 FROM e_ast_dil_item_tm a ,
		      e_sabm_t_master_tm c,e_ast_sskel_0_tr d
		 where  a.i_aset =  c.no_aset  and a.i_barang_serah = ?
		   and  a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
								     and substr(c.kd_brg,2,2) = d.kd_bid 
									 and substr(c.kd_brg,4,2) = d.kd_kel
									 and substr(c.kd_brg,6,2) = d.kd_skel
									 and substr(c.kd_brg,8,3) = d.kd_sskel",$nopeng);
		
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_barang"           =>(string)$result[$j]->c_barang,
								   "d_anggaran"           =>(string)$result[$j]->d_anggaran,
								   "no_aset"           =>(string)$result[$j]->no_aset,
								   "n_aset_lokasifisik"           =>(string)$result[$j]->n_aset_lokasifisik,
								   "merk_type"           =>(string)$result[$j]->merk_type,
								   "ur_sskel"           =>(string)$result[$j]->ur_sskel);
								  
								  
			  			       
		
		 }
        }	
        		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
	
    public function UpdateAstDilTm(array $data) {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $personal_parm = array("i_peg_nipterima"  		 =>trim($data['nipPenerima']),
						       "i_orgb_penerima"  		 =>substr($data['orgPenerima'],1,6),
							   "i_peg_nippemberi"  		 =>trim($data['nipPemberi']),
							   "i_orgb_pemberi"  	 	 =>substr($data['orgPemberi'],1,6),
							   "e_keterangan"  	 	     =>trim($data['ktr']),
							   "i_entry"       		     =>$data['nuser'],
						       "d_entry"       		     =>date("Y-m-d"));
						  	    
							   
							   
	     $where[] = "i_barang_serah  =  '".$data['nopeng']."'";
		
	     $db->update('e_ast_dil_0_tm',$personal_parm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
    public function insertAstDilTm(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
		 $atk_mast_prm = array("i_barang_serah"         		=>$data['nopeng'],
	                           "d_barang_serah"    	        	=>$data['dbarangserah'],
						       "i_peg_nipterima"                =>$data['nipPenerima'],
							   "i_peg_nippemberi"               =>$data['nipPemberi'],
							   "i_barang_baserah"  	            =>' ',
							   "i_orgb_pemberi"                 =>$data['orgPemberi'],
							   "i_orgb_penerima"                =>$data['orgPenerima'],
						       "c_barang_statserah"  	        =>$data['status'],
							   "e_keterangan"					=>$data['ket'],
						       "i_entry"       		            =>$data['nuser'],
						       "d_entry"       		        =>date("Y-m-d"));
	    
	     $db->insert('e_ast_dil_0_tm',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function insertAstDilItemTm(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
		 $atk_mast_prm = array("i_barang_serah"         		=>$data['nopeng'],
	                           "d_anggaran"    	                =>$data['danggaran'],
						       "c_barang"                       =>$data['cbarang'],
							   "i_aset"              		    =>$data['iaset'],
							   "d_perolehan"  	               =>$data['dperolehan'], 
							   "n_aset_lokasifisik"              =>$data['lokasi'],
							   "i_entry"       		            =>$data['nuser'],
						       "d_entry"       		        =>date("Y-m-d"));
	    
	     $db->insert('e_ast_dil_item_tm',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	} 
     
		 
	public function DeleteAstDilTm($nopeng) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $where[] = "i_barang_serah = '". $nopeng ."'";
		 $db->delete('e_ast_dil_0_tm', $where);
		 $db->delete('e_ast_dil_item_tm', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function UpdStsAstDilTm(array $data) {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $personal_parm = array("c_barang_statserah"  	     =>$data['status'],
						           "i_entry"       		     =>$data['nuser'],
						         "d_entry"       		     =>date("Y-m-d"));
						  	    
							   
							   
	     $where[] = "i_barang_serah  =  '".$data['nopeng']."'";
		
	     $db->update('e_ast_dil_0_tm',$personal_parm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
    public function UpdateAstDilItem(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $item_parm = array("n_aset_lokasifisik"  	    =>$data['lokasi'],
							 "i_entry"       	        =>$data['nuser'],
						     "d_entry"       		     =>date("Y-m-d"));
						  	    
							   
							   
	      $where[] = "i_barang_serah  =  '".$data['nopeng']."'";
		  $where[] = "c_barang        =  '".$data['cbarang']."'";
		  $where[] = "i_aset          =  '".$data['iaset']."'";
		  $where[] = "d_anggaran      =  '".$data['danggaran']."'";
	     $db->update('e_ast_dil_item_tm',$item_parm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
   public function DelAstDilItem(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
	     $db->beginTransaction();
	     
	      $where[] = "i_barang_serah  =  '".$data['nopeng']."'";
		  $where[] = "c_barang        =  '".$data['cbarang']."'";
		  $where[] = "i_aset          =  '".$data['iaset']."'";
		
	     $db->delete('e_ast_dil_item_tm', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
 
  /*    service untuk inventais barang                        */
  public function getPenyerahanKIBList($pageNumber,$itemPerPage,$unitkr) {
       $status='T';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	 FROM e_ast_kib_0_tm where i_orgb_pemberi = ? and c_barang_statserah=?",$where);
           
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		 $result = $db->fetchAll("SELECT i_barang_serah,d_barang_serah,i_peg_nipterima,i_orgb_penerima,
		                         i_peg_nippemberi,i_orgb_pemberi,e_keterangan 
								 FROM e_ast_kib_0_tm 
							     where i_orgb_pemberi = ? and c_barang_statserah = ? 
								 limit $xLimit offset $xOffset",$where); 



		 
         $jmlResult = count($result);
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_barang_serah"           =>(string)$result[$j]->i_barang_serah,
								   "d_barang_serah"           =>(string)$result[$j]->d_barang_serah,
								   "i_peg_nipterima"           =>(string)$result[$j]->i_peg_nipterima,
								   "i_orgb_penerima"           =>(string)$result[$j]->i_orgb_penerima,
								   "i_peg_nippemberi"           =>(string)$result[$j]->i_peg_nippemberi,
								   "i_orgb_pemberi"           =>(string)$result[$j]->i_orgb_pemberi,
								   "e_keterangan"           =>(string)$result[$j]->e_keterangan);
								  
								  
							       
		
		 }
        }
      }		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function DeleteAstKIBTm($nopeng) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $where[] = "i_barang_serah = '". $nopeng ."'";
		 $db->delete('e_ast_kib_0_tm', $where);
		 $db->delete('e_ast_kib_item_tm', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function UpdStsAstKIBTm(array $data) {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $personal_parm = array("c_barang_statserah"  	     =>$data['status'],
						         "i_entry"       		     =>$data['nuser'],
						         "d_entry"       		     =>date("Y-m-d"));
						  	    
							   
							   
	     $where[] = "i_barang_serah  =  '".$data['nopeng']."'";
		
	     $db->update('e_ast_kib_0_tm',$personal_parm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getPenyInvKIBListbyKdTnh($kode,$noas) {
        $status='T';
		$params =  array();
		$params[] = $kode;
		$params[] = $noas;
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT c_barang,d_anggaran,to_char(i_aset,'09999') as no_aset,i_asetkib, 
		 merk_type,ur_sskel,d_perolehan
		 FROM e_ast_kib_item_tm a, 
		      e_sabm_t_ktnh_tm c,e_ast_sskel_0_tr d
		 where  c_barang=? and no_aset=? and  a.i_aset =  c.no_aset  
		   and a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
								     and substr(c.kd_brg,2,2) = d.kd_bid 
									 and substr(c.kd_brg,4,2) = d.kd_kel
									 and substr(c.kd_brg,6,2) = d.kd_skel
									 and substr(c.kd_brg,8,3) = d.kd_sskel",$params);
		
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_barang"           =>(string)$result[$j]->c_barang,
								   "d_anggaran"           =>(string)$result[$j]->d_anggaran,
								   "no_aset"           =>(string)$result[$j]->no_aset,
								   "n_aset_lokasifisik"           =>(string)$result[$j]->n_aset_lokasifisik,
								   "merk_type"           =>(string)$result[$j]->merk_type,
								   "ur_sskel"           =>(string)$result[$j]->ur_sskel,
								   "d_perolehan"           =>(string)$result[$j]->d_perolehan);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getPenyInvKIBListbyKdBDG($kode,$noas) {
        $status='T';
		$params =  array();
		$params[] = $kode;
		$params[] = $noas;
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT c_barang,d_anggaran,to_char(i_aset,'09999') as no_aset,i_asetkib, 
		 merk_type,ur_sskel,d_perolehan
		 FROM e_ast_kib_item_tm a, 
		      e_sabm_t_kdbg_tm c,e_ast_sskel_0_tr d
		 where  c_barang=? and no_aset=? and  a.i_aset =  c.no_aset  
		   and a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
								     and substr(c.kd_brg,2,2) = d.kd_bid 
									 and substr(c.kd_brg,4,2) = d.kd_kel
									 and substr(c.kd_brg,6,2) = d.kd_skel
									 and substr(c.kd_brg,8,3) = d.kd_sskel",$params);
		
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_barang"           =>(string)$result[$j]->c_barang,
								   "d_anggaran"           =>(string)$result[$j]->d_anggaran,
								   "no_aset"           =>(string)$result[$j]->no_aset,
								   "n_aset_lokasifisik"           =>(string)$result[$j]->n_aset_lokasifisik,
								   "merk_type"           =>(string)$result[$j]->merk_type,
								   "ur_sskel"           =>(string)$result[$j]->ur_sskel,
								   "d_perolehan"           =>(string)$result[$j]->d_perolehan);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	 
	public function getPenyInvKIBListbyKdAKT($kode,$noas) {
        $status='T';
		$params =  array();
		$params[] = $kode;
		$params[] = $noas;
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT c_barang,d_anggaran,to_char(i_aset,'09999') as no_aset,i_asetkib, 
		 merk_type,ur_sskel,d_perolehan
		 FROM e_ast_kib_item_tm a, 
		      e_sabm_t_kangk_tm c,e_ast_sskel_0_tr d
		 where  c_barang=? and no_aset=? and  a.i_aset =  c.no_aset  
		   and a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
								     and substr(c.kd_brg,2,2) = d.kd_bid 
									 and substr(c.kd_brg,4,2) = d.kd_kel
									 and substr(c.kd_brg,6,2) = d.kd_skel
									 and substr(c.kd_brg,8,3) = d.kd_sskel",$params);
		
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_barang"           =>(string)$result[$j]->c_barang,
								   "d_anggaran"           =>(string)$result[$j]->d_anggaran,
								   "no_aset"           =>(string)$result[$j]->no_aset,
								   "n_aset_lokasifisik"           =>(string)$result[$j]->n_aset_lokasifisik,
								   "merk_type"           =>(string)$result[$j]->merk_type,
								   "ur_sskel"           =>(string)$result[$j]->ur_sskel,
								   "d_perolehan"           =>(string)$result[$j]->d_perolehan);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	 } 
	 public function UpdateAstKIBTm(array $data) {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $personal_parm = array("i_peg_nipterima"  		 =>trim($data['nipPenerima']),
						       "i_orgb_penerima"  		 =>substr($data['orgPenerima'],1,6),
							   "i_peg_nippemberi"  		 =>trim($data['nipPemberi']),
							   "i_orgb_pemberi"  	 	 =>substr($data['orgPemberi'],1,6),
							   "e_keterangan"  	 	     =>trim($data['ktr']),
							   "i_entry"                 =>$data['nuser'],
						       "d_entry"       		     =>date("Y-m-d"));
						  	    
							   
							   
	     $where[] = "i_barang_serah  =  '".$data['nopeng']."'";
		
	     $db->update('e_ast_kib_0_tm',$personal_parm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getPenyInvKIBList($nopeng) {
       $status='T';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
	     $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT c_barang,d_anggaran,to_char(no_aset,'09999') as no_aset,
		 merk_type,ur_sskel
		 FROM e_ast_kib_item_tm a ,
		      e_sabm_t_master_tm c,e_ast_sskel_0_tr d
		 where  a.i_aset =  c.no_aset  and a.i_barang_serah = ?
		   and  a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
								     and substr(c.kd_brg,2,2) = d.kd_bid 
									 and substr(c.kd_brg,4,2) = d.kd_kel
									 and substr(c.kd_brg,6,2) = d.kd_skel
									 and substr(c.kd_brg,8,3) = d.kd_sskel",$nopeng);
		
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_barang"           =>(string)$result[$j]->c_barang,
								   "d_anggaran"           =>(string)$result[$j]->d_anggaran,
								   "no_aset"           =>(string)$result[$j]->no_aset,
								   "i_ruang"           =>(string)$result[$j]->i_ruang,
								   "merk_type"           =>(string)$result[$j]->merk_type,
								   "ur_sskel"           =>(string)$result[$j]->ur_sskel);
								  
								  
			  			       
		
		 }
        }	
        		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
    public function insertAstKIBTm(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
		 $atk_mast_prm = array("i_barang_serah"         		=>$data['nopeng'],
	                           "d_barang_serah"    	        	=>$data['dbarangserah'],
						       "i_peg_nipterima"                =>$data['nipPenerima'],
							   "i_peg_nippemberi"               =>$data['nipPemberi'],
							   "i_barang_baserah"  	            =>' ',
							   "i_orgb_pemberi"                 =>$data['orgPemberi'],
							   "i_orgb_penerima"                =>$data['orgPenerima'],
						       "c_barang_statserah"  	        =>$data['status'],
							   "e_keterangan"					=>$data['ket'],
							   "c_aset_kib"                     =>' ',
						       "i_entry"       		            =>$data['nuser'],
						       "d_entry"       		        =>date("Y-m-d"));
	    
	     $db->insert('e_ast_kib_0_tm',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
  	
	public function insertAstKIBItemTm(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
		 $atk_mast_prm = array("i_barang_serah"         		=>$data['nopeng'],
	                           "d_anggaran"    	                =>$data['danggaran'],
						       "c_barang"                       =>$data['cbarang'],
							   "i_aset"              		    =>$data['iaset'],
							   "d_perolehan"  	                =>$data['dperolehan'], 
							   "i_aset_kib"                     =>$data['asetkib'],
							   "i_entry"       		            =>$data['nuser'],
						       "d_entry"       		           =>date("Y-m-d"));
	    
	     $db->insert('e_ast_kib_item_tm',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	} 
    

	 public function UpdateAstKIBItem(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $item_parm = array("i_aset_kib"  	    =>$data['asetkib'],
							 "i_entry"          =>$data['nuser'],
						     "d_entry"          =>date("Y-m-d"));
						  	    
							   
							   
	      $where[] = "i_barang_serah  =  '".$data['nopeng']."'";
		  $where[] = "c_barang        =  '".$data['cbarang']."'";
		  $where[] = "i_aset          =  '".$data['iaset']."'";
		  $where[] = "d_anggaran      =  '".$data['danggaran']."'";
	     $db->update('e_ast_kib_item_tm',$item_parm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function DelAstKIBItem(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     
	      $where[] = "i_barang_serah  =  '".$data['nopeng']."'";
		  $where[] = "c_barang        =  '".$data['cbarang']."'";
		  $where[] = "i_aset          =  '".$data['iaset']."'";
		
	     $db->delete('e_ast_kib_item_tm', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
 
   public function getPenyKIBListbyKode($kode,$noas) {
       
        $status='T';
		$params =  array();
		$params[] = $kode;
		$params[] = $noas;
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT c_barang,d_anggaran,to_char(no_aset,'09999') as no_aset,
		 merk_type,ur_sskel,d_perolehan
		 FROM e_ast_kib_item_tm a, 
		      e_sabm_t_master_tm c,e_ast_sskel_0_tr d
		 where  c_barang=? and no_aset=? and  a.i_aset =  c.no_aset  
		   and a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
								     and substr(c.kd_brg,2,2) = d.kd_bid 
									 and substr(c.kd_brg,4,2) = d.kd_kel
									 and substr(c.kd_brg,6,2) = d.kd_skel
									 and substr(c.kd_brg,8,3) = d.kd_sskel",$params);
		
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_barang"           =>(string)$result[$j]->c_barang,
								   "d_anggaran"           =>(string)$result[$j]->d_anggaran,
								   "no_aset"           =>(string)$result[$j]->no_aset,
								   "merk_type"           =>(string)$result[$j]->merk_type,
								   "ur_sskel"           =>(string)$result[$j]->ur_sskel,
								   "d_perolehan"           =>(string)$result[$j]->d_perolehan);
								  
								  
							       
		
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