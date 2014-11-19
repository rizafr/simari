<?php
class ast_penghapusan_atkdetail_Service {
   
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
	
	public function queryHapusAjuanAtkD($nohapus) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT b.c_atk_ctgr,n_atk_ctgr,b.c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stock,
								  q_atk_expire,e_keterangan
		                         FROM e_ast_barang_atk_tr a, e_ast_hapus_itematk_tm b,e_ast_kategori_atk_tr c
								 where i_atk_ajuanhapus = ? and a.c_atk = b.c_atk and a.c_atk_ctgr=c.c_atk_ctgr',$nohapus);
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_atk"                  =>(string)$result[$j]->c_atk,
								   "n_atk"                  =>(string)$result[$j]->n_atk,
								   "c_atk_ctgr"             =>(string)$result[$j]->c_atk_ctgr,
								   "n_atk_ctgr"             =>(string)$result[$j]->n_atk_ctgr,	
								   "q_atk_stock"            =>(string)$result[$j]->q_atk_stock,
								   "n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
								   "q_atk_expire"             =>(string)$result[$j]->q_atk_expire,
	                               "e_keterangan"             =>(string)$result[$j]->e_keterangan);
		
		}					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
 
	public function getBarangHapusList($noajuan) {
       $status='A';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $where[] = $noajuan;
		 $where[] = $status;
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT a.c_atk,a.c_atk_ctgr,q_atk_beli,n_atk,n_atk_satuan,n_atk_merek,
		 n_atk_tipe,q_atk_stock FROM e_ast_ajuanbeli_itematk_tm a,e_ast_barang_atk_tr b, e_ast_ajuanbeli_atk_tm c 
		 where i_atk_ajuan =? and a.c_atk_ctgr= b.c_atk_ctgr and a.c_atk = b.c_atk 
		 and and c_atk_setuju = ?
		 oRDER BY c_atk',$where);
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
	
	public function insertHapusAjuanAtkD(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	
	   try {
	     $db->beginTransaction();
	     $atk_dtl_parm = array("i_atk_ajuanhapus"        	=>$data['nohapus'],
	                           "c_atk"    	    	        =>$data['KdBarang'],
						       "c_atk_ctgr"                 =>$data['KatBarang'],
						       "q_atk_expire"  		        =>$data['JmlExpire'],
							   "e_keterangan"  		        =>$data['Keterangan'],
						       "i_entry"       		        =>$data['nuser'],
						       "d_entry"       		        =>date("Y-m-d"));
	   
	   
	     $db->insert('e_ast_hapus_itematk_tm',$atk_dtl_parm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	 
    
	 
	public function updateHapuseditAjuanAtkD(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $atk_dtl_parm = array("q_atk_expire"  		    =>$data['JmlExpire'],
						       "e_keterangan"       	=>$data['Keterangan'],
						       "i_entry"       		    =>$data['nuser'],
						       "d_entry"       		    =>date("Y-m-d"));
	     
		 $where[] = "i_atk_ajuanhapus  =  '".trim($data['nohapus'])."'";
	     $where[] = "c_atk        =  '".trim($data['KdBarang'])."'";
		 $where[] = "c_atk_ctgr   =  '".trim($data['KatBarang'])."'";
		 $db->update('e_ast_hapus_itematk_tm',$atk_dtl_parm, $where);
		 
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
     
	 
	public function deletHapusEditAjuanAtkD(array $data) {
	    
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	  	 //$where[] = "i_atk_ajuanhapus  =  '". $nohapus ."'";
	     $where[] = "i_atk_ajuanhapus  =  '".trim($data['nohapus'])."'";
	     $where[] = "c_atk        =  '".trim($data['KdBarang'])."'";
		 
		 $db->delete('e_ast_hapus_itematk_tm', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function deletHapusEditAjuanAtkMD($nohapus) {
	     
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	  	 $where[] = "i_atk_ajuanhapus  =  '". $nohapus ."'";
	     
		 $db->delete('e_ast_hapus_itematk_tm', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function updateStockBarangAtkD($nohapus,$nuser) {
	
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
	
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$result = $db->fetchAll('SELECT b.c_atk_ctgr,b.c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stock,
									 q_atk_expire,e_keterangan
			                         FROM e_ast_barang_atk_tr a, e_ast_hapus_itematk_tm b
									 where i_atk_ajuanhapus = ? and a.c_atk = b.c_atk',$nohapus);
			$jmlResult = count($result);
		 
			for ($j = 0; $j < $jmlResult; $j++) {
			 
	            $db->beginTransaction();
				
				$atk_dtl_parm = array( "q_atk_stock"  		        =>((string)$result[$j]->q_atk_stock) - ((string)$result[$j]->q_atk_expire),
									   "i_entry"       		        =>$nuser,
								       "d_entry"       		        =>date("Y-m-d"));
									   
			     $where[] = "c_atk        =  '".trim((string)$result[$j]->c_atk)."'";
				 $where[] = "c_atk_ctgr   =  '".trim((string)$result[$j]->c_atk_ctgr)."'";
				 $db->update('e_ast_barang_atk_tr',$atk_dtl_parm, $where);
				 $db->commit();
			}					 
	    
		return 'sukses';
		 
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getPejabat($unitjabatan) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
			$where[]=$unitjabatan;
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			 
				$result = $db->fetchAll("select B.i_peg_nip, n_peg, A.n_jabatan, b.c_unit_kerja, b.i_orgb  
										from e_sdm_jabatan_0_tm A,  e_sdm_pegawai_0_tm B
										where a.i_peg_nip = b.i_peg_nip
										and b.c_unit_kerja = ?
										and b.c_unit_kerja = a.c_jabatan",$where);
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
			 
					$hasilAkhir[$j] = array("i_peg_nip"		=>(string)$result[$j]->i_peg_nip,
											"n_peg"   		=>(string)$result[$j]->n_peg, 
											"n_jabatan"     =>(string)$result[$j]->n_jabatan, 
											"n_peg"         =>(string)$result[$j]->n_peg,
											"c_unit_kerja"  =>(string)$result[$j]->c_unit_kerja,
											"i_orgb"    	=>(string)$result[$j]->i_orgb);
											
				}
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
}	 	
?>