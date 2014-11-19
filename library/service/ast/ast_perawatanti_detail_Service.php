<?php
class ast_perawatanti_detail_Service {
   
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

	//=========================================08 des 07 ====================================================================
	public function getSWTelahInstallList($thnang,$kdbrg,$noaset) {
	 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $thnang;
		 $where[] = $kdbrg;
		 $where[] = $noaset;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('Select b.i_sw,n_sw ,n_sw_kelompok ,e_sw_platform ,
									i_sw_versi ,i_sw_nomorlicensi ,d_sw_releasepublish 
									from e_ast_distribusi_software_tm  a, e_ast_software_0_tr b
										Where a.i_sw=b.i_sw 
										and thn_ang=? and kd_brg=? and no_aset=?',$where);
								
         $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_sw"           		=>(string)$result[$j]->i_sw,
								   "n_sw"           		=>(string)$result[$j]->n_sw,
								   "n_sw_kelompok"      	=>(string)$result[$j]->n_sw_kelompok,
								   "e_sw_platform"      	=>(string)$result[$j]->e_sw_platform,
								   "i_sw_versi"  			=>(string)$result[$j]->i_sw_versi,
								   "i_sw_nomorlicensi"      =>(string)$result[$j]->i_sw_nomorlicensi,
								   "d_sw_releasepublish"    =>(string)$result[$j]->d_sw_releasepublish);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	public function getSWTelahInstallListByAset($thnang,$kdbrg,$noaset,$noAjuan) {
	 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $thnang;
		 $where[] = $kdbrg;
		 $where[] = $noaset;
		 $where[] = $noAjuan;
		 
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("Select b.i_sw,n_sw ,n_sw_kelompok ,e_sw_platform ,
									i_sw_versi ,i_sw_nomorlicensi ,d_sw_releasepublish 
									from e_ast_distribusi_software_tm  a, e_ast_software_0_tr b
										Where a.i_sw=b.i_sw 
										and thn_ang=? and kd_brg=? and no_aset=?
										and
										not exists( Select i_sw
										FROM  e_ast_ajuanrawatti_item_tm d
										where  a.i_sw=d.i_sw and d.i_barang_ajuanrawat = ?)",$where);
								
         $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_sw"           		=>(string)$result[$j]->i_sw,
								   "n_sw"           		=>(string)$result[$j]->n_sw,
								   "n_sw_kelompok"      	=>(string)$result[$j]->n_sw_kelompok,
								   "e_sw_platform"      	=>(string)$result[$j]->e_sw_platform,
								   "i_sw_versi"  			=>(string)$result[$j]->i_sw_versi,
								   "i_sw_nomorlicensi"      =>(string)$result[$j]->i_sw_nomorlicensi,
								   "d_sw_releasepublish"    =>(string)$result[$j]->d_sw_releasepublish);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	public function getSWTelahInstallList2($nipPemohon) {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $nipPemohon;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('Select b.i_sw,n_sw ,n_sw_kelompok ,e_sw_platform ,
									i_sw_versi ,i_sw_nomorlicensi ,d_sw_releasepublish 
									from E_ast_komputer_0_tr a, e_ast_distribusi_software_tm  b,
										e_ast_software_0_tr c
										Where b.i_sw=c.i_sw
											and i_peg_nip=?',$where);
								
         $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_sw"           		=>(string)$result[$j]->i_sw,
								   "n_sw"           		=>(string)$result[$j]->n_sw,
								   "n_sw_kelompok"      	=>(string)$result[$j]->n_sw_kelompok,
								   "e_sw_platform"      	=>(string)$result[$j]->e_sw_platform,
								   "i_sw_versi"  			=>(string)$result[$j]->i_sw_versi,
								   "i_sw_nomorlicensi"      =>(string)$result[$j]->i_sw_nomorlicensi,
								   "d_sw_releasepublish"    =>(string)$result[$j]->d_sw_releasepublish);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	public function getSWBelumInstallListByAset($thnang,$kdbrg,$noaset,$noAjuan) {
	
		$where[] = $thnang;
		$where[] = $kdbrg;
		$where[] = $noaset;
		$where[] = $noAjuan;
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
	     
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$result = $db->fetchAll("Select a.i_sw,n_sw ,n_sw_kelompok ,e_sw_platform ,
									i_sw_versi ,i_sw_nomorlicensi ,d_sw_releasepublish  
									from e_ast_software_0_tr a
									Where 
									not exists( Select i_sw
									FROM e_ast_distribusi_software_tm b where a.i_sw=b.i_sw and 
									thn_ang=? and kd_brg=? and to_char(no_aset,'09999')=?)
									and
									not exists( Select i_sw
									FROM  e_ast_ajuanrawatti_item_tm d
									where  a.i_sw=d.i_sw and d.i_barang_ajuanrawat = ?)",$where);
								
			$jmlResult = count($result);
		 
			if($jmlResult > 0){
			for ($j = 0; $j < $jmlResult; $j++) {
		 
				$hasilAkhir[$j] = array("i_sw"           		=>(string)$result[$j]->i_sw,
								   "n_sw"           		=>(string)$result[$j]->n_sw,
								   "n_sw_kelompok"      	=>(string)$result[$j]->n_sw_kelompok,
								   "e_sw_platform"      	=>(string)$result[$j]->e_sw_platform,
								   "i_sw_versi"  			=>(string)$result[$j]->i_sw_versi,
								   "i_sw_nomorlicensi"      =>(string)$result[$j]->i_sw_nomorlicensi,
								   "d_sw_releasepublish"    =>(string)$result[$j]->d_sw_releasepublish);
								  
								  
			}
        }		 
	     return $hasilAkhir;
		} catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
		}
	}	 
	
	public function getSWBelumInstallList($thnang,$kdbrg,$noaset) {
	
		$where[] = $thnang;
		$where[] = $kdbrg;
		$where[] = $noaset;
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
	     
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$result = $db->fetchAll("Select a.i_sw,n_sw ,n_sw_kelompok ,e_sw_platform ,
									i_sw_versi ,i_sw_nomorlicensi ,d_sw_releasepublish  
									from e_ast_software_0_tr a
									Where 
									not exists( Select i_sw
									FROM e_ast_distribusi_software_tm b where a.i_sw=b.i_sw and 
									thn_ang=? and kd_brg=? and to_char(no_aset,'09999')=?)",$where);
								
			$jmlResult = count($result);
		 
			if($jmlResult > 0){
			for ($j = 0; $j < $jmlResult; $j++) {
		 
				$hasilAkhir[$j] = array("i_sw"           		=>(string)$result[$j]->i_sw,
								   "n_sw"           		=>(string)$result[$j]->n_sw,
								   "n_sw_kelompok"      	=>(string)$result[$j]->n_sw_kelompok,
								   "e_sw_platform"      	=>(string)$result[$j]->e_sw_platform,
								   "i_sw_versi"  			=>(string)$result[$j]->i_sw_versi,
								   "i_sw_nomorlicensi"      =>(string)$result[$j]->i_sw_nomorlicensi,
								   "d_sw_releasepublish"    =>(string)$result[$j]->d_sw_releasepublish);
								  
								  
			}
        }		 
	     return $hasilAkhir;
		} catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
		}
	}	 
	
	public function getSWBelumInstallListByNoAjuan($noAjuan) {
	
		$where[] = $noAjuan;
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
	     
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$result = $db->fetchAll("Select a.i_sw,n_sw ,n_sw_kelompok ,e_sw_platform ,
									i_sw_versi ,i_sw_nomorlicensi ,d_sw_releasepublish  
									from e_ast_software_0_tr a
									Where 
									not exists( Select i_sw
									FROM  e_ast_ajuanrawatti_item_tm d
									where  a.i_sw=d.i_sw and d.i_barang_ajuanrawat = ?)",$where);
								
			$jmlResult = count($result);
		 
			if($jmlResult > 0){
			for ($j = 0; $j < $jmlResult; $j++) {
		 
				$hasilAkhir[$j] = array("i_sw"           		=>(string)$result[$j]->i_sw,
								   "n_sw"           		=>(string)$result[$j]->n_sw,
								   "n_sw_kelompok"      	=>(string)$result[$j]->n_sw_kelompok,
								   "e_sw_platform"      	=>(string)$result[$j]->e_sw_platform,
								   "i_sw_versi"  			=>(string)$result[$j]->i_sw_versi,
								   "i_sw_nomorlicensi"      =>(string)$result[$j]->i_sw_nomorlicensi,
								   "d_sw_releasepublish"    =>(string)$result[$j]->d_sw_releasepublish);
								  
								  
			}
        }		 
	     return $hasilAkhir;
		} catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
		}
	}
	
	public function getSWBelumInstallList4() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     //$where[] = $nipPemohon;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('Select a.i_sw,n_sw ,n_sw_kelompok ,e_sw_platform ,
									i_sw_versi ,i_sw_nomorlicensi ,d_sw_releasepublish  
									from E_AST_SOFTWARE_0_TR a
									Where not exists( Select i_sw
									     FROM e_ast_distribusi_software_tm b where a.i_sw=b.i_sw)');
								
         $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_sw"           		=>(string)$result[$j]->i_sw,
								   "n_sw"           		=>(string)$result[$j]->n_sw,
								   "n_sw_kelompok"      	=>(string)$result[$j]->n_sw_kelompok,
								   "e_sw_platform"      	=>(string)$result[$j]->e_sw_platform,
								   "i_sw_versi"  			=>(string)$result[$j]->i_sw_versi,
								   "i_sw_nomorlicensi"      =>(string)$result[$j]->i_sw_nomorlicensi,
								   "d_sw_releasepublish"    =>(string)$result[$j]->d_sw_releasepublish);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	
	public function getSWBelumInstallList3() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     //$where[] = $nipPemohon;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('Select a.i_sw,n_sw ,n_sw_kelompok ,e_sw_platform ,
									i_sw_versi ,i_sw_nomorlicensi ,d_sw_releasepublish  
									from E_AST_SOFTWARE_0_TR a');
								
         $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_sw"           		=>(string)$result[$j]->i_sw,
								   "n_sw"           		=>(string)$result[$j]->n_sw,
								   "n_sw_kelompok"      	=>(string)$result[$j]->n_sw_kelompok,
								   "e_sw_platform"      	=>(string)$result[$j]->e_sw_platform,
								   "i_sw_versi"  			=>(string)$result[$j]->i_sw_versi,
								   "i_sw_nomorlicensi"      =>(string)$result[$j]->i_sw_nomorlicensi,
								   "d_sw_releasepublish"    =>(string)$result[$j]->d_sw_releasepublish);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	public function getSWBelumInstallList2() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     //$where[] = $nipPemohon;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('Select a.i_sw,n_sw ,n_sw_kelompok ,e_sw_platform ,
									i_sw_versi ,i_sw_nomorlicensi ,d_sw_releasepublish  
									from E_AST_SOFTWARE_0_TR a
									Where not exists( Select b.i_SW  
									     FROM e_ast_ajuanrawatti_item_tm b, E_AST_SOFTWARE_0_TR c
										 where b.i_sw = c.i_sw)');
								
         $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_sw"           		=>(string)$result[$j]->i_sw,
								   "n_sw"           		=>(string)$result[$j]->n_sw,
								   "n_sw_kelompok"      	=>(string)$result[$j]->n_sw_kelompok,
								   "e_sw_platform"      	=>(string)$result[$j]->e_sw_platform,
								   "i_sw_versi"  			=>(string)$result[$j]->i_sw_versi,
								   "i_sw_nomorlicensi"      =>(string)$result[$j]->i_sw_nomorlicensi,
								   "d_sw_releasepublish"    =>(string)$result[$j]->d_sw_releasepublish);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	public function insertAjuanRwtpcD(array $data) {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("i_barang_ajuanrawat"    =>$data['noAjuan'],
	                           "i_sw"    				=>$data['noSw'],
						       "c_status_proses"  		=>$data['statusI'],
						       "c_status_rawat" 		=>$data['statusRwt'],
						       "i_entry"       			=>"ast",
						       "d_entry"       			=>date("Y-m-d"));
		
		 $db->insert('e_ast_ajuanrawatti_item_tm',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
   public function queryAjuanRwtpcD($noAjuan) {
       
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $noAjuan;
		 //$where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT a.i_barang_ajuanrawat,d_barang_ajuanrawat,a.i_sw,c_status_proses,c_status_rawat,
									n_sw ,n_sw_kelompok ,e_sw_platform , i_sw_versi ,i_sw_nomorlicensi ,d_sw_releasepublish 
									FROM e_ast_ajuanrawatti_item_tm a,  e_ast_software_0_tr b, e_ast_ajuanrawatti_master_tm c
										where  a.i_sw=b.i_sw and a.i_barang_ajuanrawat=c.i_barang_ajuanrawat and a.i_barang_ajuanrawat = ? ",$where);
																		
         $jmlResult = count($result);
		 //echo '$jmlResult '.$jmlResult ;
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_barang_ajuanrawat"   		=>(string)$result[$j]->i_barang_ajuanrawat,
								   "d_barang_ajuanrawat"      	=>(string)$result[$j]->d_barang_ajuanrawat,
								   "i_sw"      					=>(string)$result[$j]->i_sw,
								   "c_status_proses"      		=>(string)$result[$j]->c_status_proses,
								   "c_status_rawat"      		=>(string)$result[$j]->c_status_rawat,
								   "n_sw"      					=>(string)$result[$j]->n_sw,
								   "n_sw_kelompok"      		=>(string)$result[$j]->n_sw_kelompok,
								   "e_sw_platform"      		=>(string)$result[$j]->e_sw_platform,
								   "i_sw_versi"      			=>(string)$result[$j]->i_sw_versi,
								   "i_sw_nomorlicensi"      	=>(string)$result[$j]->i_sw_nomorlicensi,
								   "d_sw_releasepublish"      	=>(string)$result[$j]->d_sw_releasepublish);
							       
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
  public function updateAjuanRwtpcD($data) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');		 
		try {
		$db->beginTransaction();
		$ast_ajuan_prm = array("c_status_rawat"          =>'Y', 
							   "i_entry"                 =>$data['userid'],
				               "d_entry"                 =>date("Y-m-d"));
		 	 			
		$where[] = "i_barang_ajuanrawat = '".$data['noAjuan']."'";
		$where[] = "i_sw = '".$data['noSw']."'";
		$db->update("e_ast_ajuanrawatti_item_tm",$ast_ajuan_prm, $where);
		$db->commit();
		//echo 'sukses nih Yee<br>';
		return 'sukses';
		}
		catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}	
   }
   
   //add === 05 06 08
	public function deletAjuanRawatD($noPindah) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 
	  	 $where[] = "i_barang_ajuanrawat  =  '".trim($noPindah)."'";
	     
		 $db->delete('e_ast_ajuanrawatti_item_tm', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'delete gagal';
	   }
	}
	
}
?>