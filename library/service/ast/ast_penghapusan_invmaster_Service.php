<?php
class ast_penghapusan_invmaster_Service {
   
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
	 * fungsi untuk   e_ast_ajuanpindahinv_0_tm
	 ***************************/
	//=================================== 15 Nop 07============================================================================= 
	 public function queryAjuanHapusInvM($unitkr) {
       $status='A';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_orgb,i_inv_ajuanhapus,d_inv_ajuanhapus  
									FROM   aset.tm_ajuanhapusinv 
									where i_orgb=? and c_inv_statajuanhapus=?',$where);
								
         $jmlResult = count($result);
		 //echo '$jmlResult '.$jmlResult ;
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb"           	=>(string)$result[$j]->i_orgb,
								   "i_inv_ajuanhapus"   =>(string)$result[$j]->i_inv_ajuanhapus,
								   "d_inv_ajuanhapus"   =>(string)$result[$j]->d_inv_ajuanhapus);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	public function getAjuanHapusList($pageNumber,$itemPerPage,$unitkr) {
		$status='A';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		 $where[]=$unitkr;
		 $where[]=$status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM aset.tm_ajuanhapusinv 
										 where i_orgb=? and c_inv_statajuanhapus=?",$where);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT i_orgb,i_inv_ajuanhapus,d_inv_ajuanhapus  
										FROM   aset.tm_ajuanhapusinv 
										where i_orgb=? and c_inv_statajuanhapus=?
										ORDER BY i_inv_ajuanhapus
										limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_orgb"        		=>(string)$result[$j]->i_orgb,
										   "i_inv_ajuanhapus"   =>(string)$result[$j]->i_inv_ajuanhapus,
										   "d_inv_ajuanhapus"   =>(string)$result[$j]->d_inv_ajuanhapus);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function querySetujuHapusKabag($pageNumber,$itemPerPage,$unitkr) {
		$status='B';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		 $where[]=$unitkr;
		 $where[]=$status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM aset.tm_ajuanhapusinv 
										 where i_orgb=? and c_inv_statajuanhapus=? and (c_setuju_statuskabag is null or c_setuju_statuskabag='')",$where);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT i_orgb,i_inv_ajuanhapus,d_inv_ajuanhapus  
										FROM   aset.tm_ajuanhapusinv 
										where i_orgb=? and c_inv_statajuanhapus=? and (c_setuju_statuskabag is null or c_setuju_statuskabag='')
										ORDER BY i_inv_ajuanhapus
										limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_orgb"        		=>(string)$result[$j]->i_orgb,
										   "i_inv_ajuanhapus"   =>(string)$result[$j]->i_inv_ajuanhapus,
										   "d_inv_ajuanhapus"   =>(string)$result[$j]->d_inv_ajuanhapus);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function querySetujuHapusKabag2($unitkr) {
       $status='B';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT i_orgb,i_inv_ajuanhapus,d_inv_ajuanhapus  
									FROM   aset.tm_ajuanhapusinv 
									where i_orgb=? and c_inv_statajuanhapus=? 
									and (c_setuju_statuskabag is null or c_setuju_statuskabag='') ",$where);
								
         $jmlResult = count($result);
		 //echo '$jmlResult '.$jmlResult ;
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb"           	=>(string)$result[$j]->i_orgb,
								   "i_inv_ajuanhapus"   =>(string)$result[$j]->i_inv_ajuanhapus,
								   "d_inv_ajuanhapus"   =>(string)$result[$j]->d_inv_ajuanhapus);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	public function querySetujuHapusBiro($pageNumber,$itemPerPage,$unitkr) {
		$status='B';
	    $kabag='Y';
	    $registry = Zend_Registry::getInstance();
	    $db = $registry->get('db');
	    try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $where[] = $kabag;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM aset.tm_ajuanhapusinv 
									where i_orgb=? and c_inv_statajuanhapus=? 
									and c_setuju_statuskabag =?
									and (c_setuju_statusbiro is null or c_setuju_statusbiro='') ",$where);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT i_orgb,i_inv_ajuanhapus,d_inv_ajuanhapus  
									FROM   e_ast_ajuanhapusinv_0_tm 
									where i_orgb=? and c_inv_statajuanhapus=? 
									and c_setuju_statuskabag =?
									and (c_setuju_statusbiro is null or c_setuju_statusbiro='') 
									ORDER BY i_inv_ajuanhapus
									limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_orgb"        		=>(string)$result[$j]->i_orgb,
										   "i_inv_ajuanhapus"   =>(string)$result[$j]->i_inv_ajuanhapus,
										   "d_inv_ajuanhapus"   =>(string)$result[$j]->d_inv_ajuanhapus);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	public function querySetujuHapusBiro2($unitkr) {
       $status='B';
	   $kabag='Y';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $where[] = $kabag;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT i_orgb,i_inv_ajuanhapus,d_inv_ajuanhapus  
									FROM   aset.tm_ajuanhapusinv 
									where i_orgb=? and c_inv_statajuanhapus=? 
									and c_setuju_statuskabag =?
									and (c_setuju_statusbiro is null or c_setuju_statusbiro='') ",$where);
								
         $jmlResult = count($result);
		 //echo '$jmlResult '.$jmlResult ;
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb"           	=>(string)$result[$j]->i_orgb,
								   "i_inv_ajuanhapus"   =>(string)$result[$j]->i_inv_ajuanhapus,
								   "d_inv_ajuanhapus"   =>(string)$result[$j]->d_inv_ajuanhapus);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	public function querySetujuHapusSesmen($pageNumber,$itemPerPage,$unitkr) {
		$status='B';
	    $kabag='Y';
	    $biro='Y';
		//echo 'unitnya'.$unitkr;
	    $registry = Zend_Registry::getInstance();
	    $db = $registry->get('db');
	    try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $where[] = $kabag;
		 $where[] = $biro;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM aset.tm_ajuanhapusinv 
									where i_orgb=? and c_inv_statajuanhapus=? 
									and c_setuju_statuskabag=?
									and c_setuju_statusbiro=?
									and (c_setuju_statussesmen is null or c_setuju_statussesmen='') ",$where);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT i_orgb,i_inv_ajuanhapus,d_inv_ajuanhapus  
									FROM   aset.tm_ajuanhapusinv 
									where i_orgb=? and c_inv_statajuanhapus=? 
									and c_setuju_statuskabag=?
									and c_setuju_statusbiro=?
									and (c_setuju_statussesmen is null or c_setuju_statussesmen='')
									ORDER BY i_inv_ajuanhapus
									limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_orgb"        		=>(string)$result[$j]->i_orgb,
										   "i_inv_ajuanhapus"   =>(string)$result[$j]->i_inv_ajuanhapus,
										   "d_inv_ajuanhapus"   =>(string)$result[$j]->d_inv_ajuanhapus);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	public function querySetujuHapusSesmen2($unitkr) {
       $status='B';
	   $kabag='Y';
	   $biro='Y';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $where[] = $kabag;
		 $where[] = $biro;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT i_orgb,i_inv_ajuanhapus,d_inv_ajuanhapus  
									FROM   aset.tm_ajuanhapusinv 
									where i_orgb=? and c_inv_statajuanhapus=? 
									and c_setuju_statuskabag=?
									and c_setuju_statusbiro=?
									and (c_setuju_statussesmen is null or c_setuju_statussesmen='')",$where);
								
         $jmlResult = count($result);
		 //echo '$jmlResult '.$jmlResult ;
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb"           	=>(string)$result[$j]->i_orgb,
								   "i_inv_ajuanhapus"   =>(string)$result[$j]->i_inv_ajuanhapus,
								   "d_inv_ajuanhapus"   =>(string)$result[$j]->d_inv_ajuanhapus);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	public function queryAjuanHapusInvByNo($noHapus) {
       //$status='A';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $noHapus;
		 //$where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_orgb,i_inv_ajuanhapus,d_inv_ajuanhapus  
									FROM   aset.tm_ajuanhapusinv 
									where i_inv_ajuanhapus=? ',$where);
								
         $jmlResult = count($result);
		 //echo '$jmlResult '.$jmlResult ;
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb"           	=>(string)$result[$j]->i_orgb,
								   "i_inv_ajuanhapus"   =>(string)$result[$j]->i_inv_ajuanhapus,
								   "d_inv_ajuanhapus"   =>(string)$result[$j]->d_inv_ajuanhapus);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	public function insertAjuanHapusInvM(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("i_inv_ajuanhapus"      		=>$data['noHapus'],
	                           "d_inv_ajuanhapus"    		=>date("Y-m-d"),
						       "i_orgb"  					=>$data['unitkr'],
						       "c_inv_statajuanhapus"   	=>'A',
							   "i_entry"       				=>$data['nuser'],
						       "d_entry"       				=>date("Y-m-d"));
	    
		 $db->insert('aset.tm_ajuanhapusinv',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function updateAjuanHapusM($noHapus,$nuser) {
	//echo '$noPindah'.$noPindah;
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->beginTransaction();
	     $atk_mast_prm = array("c_inv_statajuanhapus"  			=>'B',
						       "i_entry"       		    		=>$nuser,
						       "d_entry"       		    		=>date("Y-m-d"));
	     
		 $where[] = "i_inv_ajuanhapus = '". $noHapus ."'";
	     $db->update('aset.tm_ajuanhapusinv',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
		 		 
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	
	}
	
	public function deletAjuanHapusM($noHapus) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $where[] = "i_inv_ajuanhapus = '". $noHapus ."'";
		 $db->delete('aset.tm_ajuanhapusinv', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getUnitKerjaList() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     //$result = $db->fetchAll('SELECT i_orgb,n_orgb FROM e_org_0_0_tm ORDER BY i_orgb');
	     $result = $db->fetchAll('SELECT c_satker,n_unitkerja FROM sdm.tr_unitkerja ORDER BY c_satker');
	     return $result;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function updateSetujuKabagHapusM(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   if ($data['setuju'] = '1')
	   { $status = 'Y';
	   }
	   else
	   {$status = 'N';
	   }
	   
	   try {
		 $db->beginTransaction();
	     $atk_mast_prm = array("c_setuju_statuskabag"  		=>$status,
							   "i_setuju_kabag"				=>$data['noSetuju'],
						       "d_setuju_kabag"   			=>date("Y-m-d"),
							   "e_alasan"       		    =>$data['keterangan'],
							   "i_entry"       		    	=>$data['nuser'],
						       "d_entry"       		    	=>date("Y-m-d"));
	     
		 
		 $where[] = "i_inv_ajuanhapus = '".$data['noHapus']."'";
	     $db->update('aset.tm_ajuanhapusinv',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
		 		 
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	
	}
	
	public function updateSetujuBiroHapusM(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   if ($data['setuju'] = '1')
	   { $status = 'Y';
	   }
	   else
	   {$status = 'N';
	   }
	   try {
		 $db->beginTransaction();
	     $atk_mast_prm = array("c_setuju_statusbiro"  		=>$status,
							   "i_setuju_biro"				=>$data['noSetuju'],
						       "d_setuju_biro"   			=>date("Y-m-d"),
							   "e_alasan"       		    =>$data['keterangan'],
						       "i_entry"       		    	=>$data['nuser'],
						       "d_entry"       		    	=>date("Y-m-d"));
	     
		 
		 $where[] = "i_inv_ajuanhapus = '".$data['noHapus']."'";
	     $db->update('aset.tm_ajuanhapusinv',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
		 		 
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	
	}
	
	public function updateSetujuSesmenHapusM(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   if ($data['setuju'] = '1')
	   { $status = 'Y';
	   }
	   else
	   {$status = 'N';
	   }
	   try {
		 $db->beginTransaction();
	     $atk_mast_prm = array("c_setuju_statussesmen"  	=>$status,
							   "i_setuju_sesmen"			=>$data['noSetuju'],
						       "d_setuju_sesmen"   			=>date("Y-m-d"),
							   "e_alasan"       		    =>$data['keterangan'],
						       "i_entry"       		    	=>$data['nuser'],
						       "d_entry"       		    	=>date("Y-m-d"));
	     
		 
		 $where[] = "i_inv_ajuanhapus = '".$data['noHapus']."'";
	     $db->update('aset.tm_ajuanhapusinv',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
		 		 
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	
	}
	//=================================================19 nop 07===============================================================
	////=================================================19 nop 07===============================================================
	
	
	
	public function querySetujuTuPindahM($unitkr) {
       $status='B';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_orgb_penerima,i_barang_ajuanpindah,d_barang_ajuanpindah ,b.n_untikerja as n_unitkerja
									FROM aset.tm_ajuanhapusinv a , sdm.tr_unitkerja b
									where i_orgb_pemberi=? and c_barang_statuspindah=? 
									and c_setuju_statustu is null 
									and a.i_orgb_penerima = b.c_satker',$where);
								
         $jmlResult = count($result);
		 //echo '$jmlResult '.$jmlResult ;
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb_penerima"           	=>(string)$result[$j]->i_orgb_penerima,
								   "n_orgp"           			=>(string)$result[$j]->n_unitkerja,
								   "i_barang_ajuanpindah"      	=>(string)$result[$j]->i_barang_ajuanpindah,
								   "d_barang_ajuanpindah"      	=>(string)$result[$j]->d_barang_ajuanpindah);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	
	
	public function querySetujuKabagPindahM($unitkr) {
       $status='B';
	   $status2='Y';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $where[] = $status2;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_orgb_penerima,i_barang_ajuanpindah,d_barang_ajuanpindah ,b.n_unitkerja as n_unitkerja
									FROM aset.tm_ajuanhapusinv a , sdm.tr_unitkerja b
									where i_orgb_pemberi=? and c_barang_statuspindah=? 
									and c_setuju_statustu = ? and c_setuju_statuskabag is null
									and a.i_orgb_penerima = b.c_satker',$where);
								
         $jmlResult = count($result);
		 //echo '$jmlResult '.$jmlResult ;
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb_penerima"           	=>(string)$result[$j]->i_orgb_penerima,
								   "n_orgp"           			=>(string)$result[$j]->n_unitkerja,
								   "i_barang_ajuanpindah"      	=>(string)$result[$j]->i_barang_ajuanpindah,
								   "d_barang_ajuanpindah"      	=>(string)$result[$j]->d_barang_ajuanpindah);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	public function updateSetujuKabagPindahM($noPindah,$setuju) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->beginTransaction();
	     $atk_mast_prm = array("c_setuju_statuskabag"  		=>$setuju,
						       "d_setuju_kabag" 			=>date("Y-m-d"),
						       "i_entry"       		    	=>"ast",
						       "d_entry"       		    	=>date("Y-m-d"));
	     
		 
		 $where[] = "i_barang_ajuanpindah = '".trim($noPindah)."'";
	     $db->update('aset.tm_ajuanhapusinv',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
		 		 
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	
	}
	
	public function querySerahTrmPindahM($unitkr) {
       $status='B';
	   $status2='Y';
	   $status3='Y';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $where[] = $status2;
		 $where[] = $status3;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT distinct i_orgb_penerima,a.i_barang_ajuanpindah,d_barang_ajuanpindah ,b.n_unitkerja as n_unitkerja
									FROM aset.tm_ajuanhapusinv a , sdm.tr_unitkerja b, aset.tm_ajuanhapusinv_item c
									where i_orgb_pemberi=? and c_barang_statuspindah=?
									and c_setuju_statustu = ? and c_setuju_statuskabag =? 
									and (c.c_barang_serah is null or c.c_barang_serah ='')
									and a.i_orgb_penerima = b.c_satker and a.i_barang_ajuanpindah = c.i_barang_ajuanpindah",$where);
								
         $jmlResult = count($result);
		 //echo '$jmlResult '.$jmlResult ;
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb_penerima"           	=>(string)$result[$j]->i_orgb_penerima,
								   "n_orgp"           			=>(string)$result[$j]->n_unitkerja,
								   "i_barang_ajuanpindah"      	=>(string)$result[$j]->i_barang_ajuanpindah,
								   "d_barang_ajuanpindah"      	=>(string)$result[$j]->d_barang_ajuanpindah);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	
	//=================================== 12 Nop 07=============================================================================
	
	public function queryUnitKerja($unitkr) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = "i_orgb = '".$data['unitkr']."'";
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 //$result = $db->fetchAll('SELECT i_orgb,n_orgb FROM e_org_0_0_tm where i_orgb=?',$unitkr);
		 $result = $db->fetchAll('SELECT c_satker,n_unitkerja FROM sdm.tr_unitkerja where c_satker=?',$unitkr);
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb"           =>(string)$result[$j]->c_satker,
								   "n_orgb"          =>(string)$result[$j]->n_unitkerja);
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	// public function queryNourutmax(array $data) {
	    
	   // $registry = Zend_Registry::getInstance();
	   // $db = $registry->get('db');
	    // try {
	     // $db->setFetchMode(Zend_Db::FETCH_OBJ);
		 // $where[] = $data['unitkr'];
		 // $where[] = $data['modl'];
		 // $result = $db->fetchOne('SELECT gen_nomor(?,?)',$where);
		
	     // return $result;
	   // } catch (Exception $e) {
         // $db->rollBack();
         // echo $e->getMessage().'<br>';
	     // return  0;
	   // }
 
	// } 
		public function cekkantor($unitkerja){
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	try{
	 $db->setFetchMode(Zend_Db::FETCH_OBJ);
	 $where = $unitkerja;
	 // $result = $db->fetchOne('select count(*) 
					  // from sdm.tm_organisasi 
					  // where i_orgb=?',$where);
		$result = $db->fetchOne(' select count(*)
					from sdm.tr_unitkerja a,sdm.tm_pegawai b,sdm.tm_organisasi c
					where
					a.c_lokasi_unitkerja = b.c_lokasi_unitkerja
					and b.i_peg_nip = c.i_peg_nip
					and a.c_satker=?',$where);	
	  return $result;
	}catch (Exception $e){
	 $db->rollBack();
	 echo $e->getMessage().'<br>';
	 return 0;
	}
	}
  public function cekmodul($modul){
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	try{
	 $db->setFetchMode(Zend_Db::FETCH_OBJ);
	 $where = $modul;
	 $result = $db->fetchOne('select count(*) 
							  from aset.tr_modul 
							  where c_modul=?',$where);
	  return $result;
	}catch (Exception $e){
	 $db->rollBack();
	 echo $e->getMessage().'<br>';
	 return 0;
	}
	}
	public function getNoMax($cktr,$modul){
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	try{
	 $db->beginTransaction();
	 $db->setFetchMode(Zend_Db::FETCH_OBJ);
	 $where[] = $cktr;
	 $where[] = $modul;
	 $where[] = date("Y");
	 $result = $db->fetchOne('select q_modul_nomormax
						   from aset.tr_modul_max
						   where i_orgb=?
						   and c_modul=?
						   and d_modul_tahun=?',$where);
	  return $result;
	}catch (Exception $e){
	 $db->rollBack();
	 echo $e->getMessage().'<br>';
	 return 0;
	}
	}
	public function insertNomorMax($cktr,$modul) {
   $registry = Zend_Registry::getInstance();
   $db = $registry->get('db');
 
   try {
	// $db->beginTransaction();
	 $tahun = date("Y");
	 $tm_nomormax = array("i_orgb"         		=>$cktr,
						   "c_modul"    	    =>$modul,
						   "d_modul_tahun"      =>$tahun,
						   "q_modul_nomormax"      =>1
						  );
	
	 $db->insert('aset.tr_modul_max',$tm_nomormax);
	 $db->commit();
	 return 'sukses';
   } catch (Exception $e) {
	 $db->rollBack();
	 echo $e->getMessage().'<br>';
	 return 'gagal <br>';
   }
} 
   	public function updateNomorMax($cktr,$modul,$nomormax) {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    // $db->beginTransaction();
		 $tahun = date("Y");
	     $dataupd = array("q_modul_nomormax"  	     =>$nomormax);
						  	    
							   
							   
	     $where[] = "i_orgb  =  '".$cktr."' and c_modul  =  '".$modul."' and d_modul_tahun  =  '".$tahun."' ";
		
	     $db->update('aset.tr_modul_max',$dataupd, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
    public function queryNourutmax(array $data) {
	    
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	    try {
	      
		 $where[] = $data['unitkr'];
		 $where[] = $data['modl'];
		 $where[] = date("Y");
		 $cekktr = $this->cekkantor($data['unitkr']);
		 $cekmodul = $this->cekmodul($data['modl']);
		 //echo "cek ktr : ".$cekktr;
		 //echo "cek modul : ".$cekmodul;
		 if($cekktr!=0){
		   if($cekmodul!=0){
				//$result = $db->fetchOne('SELECT aset.gen_nomor(?,?)',$where);
				$nomormax=$this->getNoMax($data['unitkr'],$data['modl']);
				
		  }
		 }
		 //echo "nomax : ".$nomormax."<br/>";
		 //echo "length nomax : ".strlen($nomormax);
		 if (strlen($nomormax)==0) {
		    $this->insertNomorMax($data['unitkr'],$data['modl']);
		     $nomax = '000001';
		  } 
		  if (strlen($nomormax)>0)   {                                 
		     $nomormax = $nomormax + 1;
		     $this->updateNomorMax($data['unitkr'],$data['modl'],$nomormax);
			 //$db->beginTransaction();
			 $db->setFetchMode(Zend_Db::FETCH_OBJ);
		     $nomax=$db->fetchOne("select to_char($nomormax,'099999')"); 
		    }
		 $nomorsurat = $data['unitkr'].$data['modl'].date("Y").trim($nomax); 
	     return $nomorsurat;
	     //return "TESTING";
	     
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
						      
	   
	     $db->insert('aset.tr_modul_max',$nomor_max_prm);
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
		
	     $db->update('aset.tr_modul_max',$nomor_max_prm, $where);
		 $db->commit();
	     return 'sukses update mo max<br>';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>'; 
	     return 'gagal update nomax <br>';
	   }
	}
public function getAjuHapusInvList($pageNumber,$itemPerPage,$unitkr) {
        
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = 'A';
		 $where[] = $unitkr;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	  FROM aset.tm_ajuanhapusinv a,sdm.tr_unitkerja b 
								  where a.i_orgb = b.c_satker  
								  and c_inv_statajuanhapus = ?
								  and a.i_orgb like ?",$where);
			 				   
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		 
			 $result = $db->fetchAll("SELECT i_inv_ajuanhapus,d_inv_ajuanhapus,a.i_orgb,n_unitkerja
		                       	 FROM aset.tm_ajuanhapusinv a,sdm.tr_unitkerja b 
								 where a.i_orgb = b.c_satker 
								 and c_inv_statajuanhapus = ?
								 and a.i_orgb like ? 
								 order by i_inv_ajuanhapus
								 limit $xLimit offset $xOffset",$where); 
			 $jmlResult = count($result);
		    
		
		    if($jmlResult > 0){
		       for ($j = 0; $j < $jmlResult; $j++) {
		      
              $hasilAkhir[$j] = array("i_inv_ajuanhapus"           =>(string)$result[$j]->i_inv_ajuanhapus,
								   "d_inv_ajuanhapus"           =>(string)$result[$j]->d_inv_ajuanhapus,
								   "i_orgb"           =>(string)$result[$j]->i_orgb,
								   "n_orgb"           =>(string)$result[$j]->n_unitkerja);
								  
								  
							       
		
		      }
           
       		
	         
		    }else{
				return 0;
			}
         }
		  
         return $hasilAkhir;	
       		 
	   }catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	  }
	} 
public function getAjuHapusInvList2($pageNumber,$itemPerPage,$unitkr) {
        
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = 'B';
		 $where[] = $unitkr;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	  FROM aset.tm_ajuanhapusinv a,sdm.tr_unitkerja b 
								  where a.i_orgb = b.c_satker  
								  and c_inv_statajuanhapus = ? and c_setuju_statuskabag is null
								  and a.i_orgb like ?",$where);
			 				   
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		 
			 $result = $db->fetchAll("SELECT i_inv_ajuanhapus,d_inv_ajuanhapus,a.i_orgb,n_unitkerja
		                       	 FROM aset.tm_ajuanhapusinv a,sdm.tr_unitkerja b 
								 where a.i_orgb = b.c_satker 
								 and c_inv_statajuanhapus = ? and c_setuju_statuskabag is null
								 and a.i_orgb like ? 
								 order by i_inv_ajuanhapus
								 limit $xLimit offset $xOffset",$where); 
			 $jmlResult = count($result);
		    
		
		    if($jmlResult > 0){
		       for ($j = 0; $j < $jmlResult; $j++) {
		      
              $hasilAkhir[$j] = array("i_inv_ajuanhapus"           =>(string)$result[$j]->i_inv_ajuanhapus,
								   "d_inv_ajuanhapus"           =>(string)$result[$j]->d_inv_ajuanhapus,
								   "i_orgb"           =>(string)$result[$j]->i_orgb,
								   "n_orgb"           =>(string)$result[$j]->n_unitkerja);
								  
								  
							       
		
		      }
           
       		
	         
		    }else{
				return 0;
			}
         }
		  
         return $hasilAkhir;	
       		 
	   }catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	  }
	}
public function getAjuHapusInvList3($pageNumber,$itemPerPage,$unitkr) {
        
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = 'Y';
		 $where[] = $unitkr;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	  FROM aset.tm_ajuanhapusinv a,sdm.tr_unitkerja b 
								  where a.i_orgb = b.c_satker 
								  and c_setuju_statuskabag = ?  
								  and a.i_orgb = ?",$where);
			 				   
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		 
			 $result = $db->fetchAll("SELECT i_inv_ajuanhapus,d_inv_ajuanhapus,a.i_orgb,n_unitkerja
		                       	 FROM aset.tm_ajuanhapusinv a,sdm.tr_unitkerja b 
								 where a.i_orgb = b.c_satker  
								 and c_setuju_statuskabag = ?  
								 and a.i_orgb = ? 
								 order by i_inv_ajuanhapus
								 limit $xLimit offset $xOffset",$where); 
			 $jmlResult = count($result);
		    
		
		    if($jmlResult > 0){
		       for ($j = 0; $j < $jmlResult; $j++) {
		      
              $hasilAkhir[$j] = array("i_inv_ajuanhapus"           =>(string)$result[$j]->i_inv_ajuanhapus,
								   "d_inv_ajuanhapus"           =>(string)$result[$j]->d_inv_ajuanhapus,
								   "i_orgb"           =>(string)$result[$j]->i_orgb,
								   "n_orgb"           =>(string)$result[$j]->n_unitkerja);
								  
								  
							       
		
		      }
           
       		
	         
		    }else{
				return 0;
			}
         }
		  //print_r($hasilAkhir);
         return $hasilAkhir;	
       		 
	   }catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	  }
	}
public function getAjuHapusInvList4($pageNumber,$itemPerPage,$unitkr) {
        
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = 'T';
		 $where[] = $unitkr;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	  FROM aset.tm_ajuanhapusinv a,sdm.tr_unitkerja b 
								  where a.i_orgb = b.c_satker 
								  and c_setuju_statuskabag = ?  
								  and a.i_orgb like ?",$where);
			 				   
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		 
			 $result = $db->fetchAll("SELECT i_inv_ajuanhapus,d_inv_ajuanhapus,a.i_orgb,n_unitkerja
		                       	 FROM aset.tm_ajuanhapusinv a,sdm.tr_unitkerja b 
								 where a.i_orgb = b.c_satker  
								 and c_setuju_statuskabag = ?  
								 and a.i_orgb like ? 
								 order by i_inv_ajuanhapus
								 limit $xLimit offset $xOffset",$where); 
			 $jmlResult = count($result);
		    
		
		    if($jmlResult > 0){
		       for ($j = 0; $j < $jmlResult; $j++) {
		      
              $hasilAkhir[$j] = array("i_inv_ajuanhapus"           =>(string)$result[$j]->i_inv_ajuanhapus,
								   "d_inv_ajuanhapus"           =>(string)$result[$j]->d_inv_ajuanhapus,
								   "i_orgb"           =>(string)$result[$j]->i_orgb,
								   "n_orgb"           =>(string)$result[$j]->n_unitkerja);
								  
								  
							       
		
		      }
           
       		
	         
		    }else{
				return 0;
			}
         }
		  
         return $hasilAkhir;	
       		 
	   }catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	  }
	}	
	
	public function getAjuHapusInvList5($pageNumber,$itemPerPage,$unitkr) {
        
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = 'Y';
		 $where[] = $unitkr;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	  FROM aset.tm_ajuanhapusinv a,sdm.tr_unitkerja b 
								  where a.i_orgb = b.c_satker 
								  and c_setuju_statusbiro = ?  
								  and a.i_orgb like ?",$where);
			 				   
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		 
			 $result = $db->fetchAll("SELECT i_inv_ajuanhapus,d_inv_ajuanhapus,a.i_orgb,n_unitkerja
		                       	 FROM aset.tm_ajuanhapusinv a,sdm.tr_unitkerja b 
								 where a.i_orgb = b.c_satker  
								 and c_setuju_statusbiro = ?  
								 and a.i_orgb like ? 
								 order by i_inv_ajuanhapus
								 limit $xLimit offset $xOffset",$where); 
			 $jmlResult = count($result);
		    
		
		    if($jmlResult > 0){
		       for ($j = 0; $j < $jmlResult; $j++) {
		      
              $hasilAkhir[$j] = array("i_inv_ajuanhapus"           =>(string)$result[$j]->i_inv_ajuanhapus,
								   "d_inv_ajuanhapus"           =>(string)$result[$j]->d_inv_ajuanhapus,
								   "i_orgb"           =>(string)$result[$j]->i_orgb,
								   "n_orgb"           =>(string)$result[$j]->n_unitkerja);
								  
								  
							       
		
		      }
           
       		
	         
		    }else{
				return 0;
			}
         }
		  
         return $hasilAkhir;	
       		 
	   }catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	  }
	}
	public function getAjuHapusInvList6($pageNumber,$itemPerPage,$unitkr) {
        
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = 'T';
		 $where[] = $unitkr;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	  FROM aset.tm_ajuanhapusinv a,sdm.tr_unitkerja b 
								  where a.i_orgb = b.c_satker 
								  and c_setuju_statusbiro = ?  
								  and a.i_orgb like ?",$where);
			 				   
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		 
			 $result = $db->fetchAll("SELECT i_inv_ajuanhapus,d_inv_ajuanhapus,a.i_orgb,n_unitkerja
		                       	 FROM aset.tm_ajuanhapusinv a,sdm.tr_unitkerja b 
								 where a.i_orgb = b.c_satker  
								 and c_setuju_statusbiro = ?  
								 and a.i_orgb like ? 
								 order by i_inv_ajuanhapus
								 limit $xLimit offset $xOffset",$where); 
			 $jmlResult = count($result);
		    
		
		    if($jmlResult > 0){
		       for ($j = 0; $j < $jmlResult; $j++) {
		      
              $hasilAkhir[$j] = array("i_inv_ajuanhapus"           =>(string)$result[$j]->i_inv_ajuanhapus,
								   "d_inv_ajuanhapus"           =>(string)$result[$j]->d_inv_ajuanhapus,
								   "i_orgb"           =>(string)$result[$j]->i_orgb,
								   "n_orgb"           =>(string)$result[$j]->n_unitkerja);
								  
								  
							       
		
		      }
           
       		
	         
		    }else{
				return 0;
			}
         }
		  
         return $hasilAkhir;	
       		 
	   }catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	  }
	}
	
	public function getAjuHapusInvList7($pageNumber,$itemPerPage,$unitkr) {
        
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = 'Y';
		 $where[] = $unitkr;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	  FROM aset.tm_ajuanhapusinv a,sdm.tr_unitkerja b 
								  where a.i_orgb = b.c_satker  
								  and c_setuju_statussesmen = ?  
								  and a.i_orgb like ?",$where);
			 				   
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		 
			 $result = $db->fetchAll("SELECT i_inv_ajuanhapus,d_inv_ajuanhapus,a.i_orgb,n_unitkerja
		                       	 FROM aset.tm_ajuanhapusinv
								 a,sdm.tr_unitkerja b 
								 where a.i_orgb = b.c_satker  
								 and c_setuju_statussesmen = ?  
								 and a.i_orgb like ? 
								 order by i_inv_ajuanhapus
								 limit $xLimit offset $xOffset",$where); 
			 $jmlResult = count($result);
		    
		
		    if($jmlResult > 0){
		       for ($j = 0; $j < $jmlResult; $j++) {
		      
              $hasilAkhir[$j] = array("i_inv_ajuanhapus"           =>(string)$result[$j]->i_inv_ajuanhapus,
								   "d_inv_ajuanhapus"           =>(string)$result[$j]->d_inv_ajuanhapus,
								   "i_orgb"           =>(string)$result[$j]->i_orgb,
								   "n_orgb"           =>(string)$result[$j]->n_unitkerja);
								  
								  
							       
		
		      }
           
       		
	         
		    }else{
				return 0;
			}
         }
		  
         return $hasilAkhir;	
       		 
	   }catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	  }
	}
	public function getAjuHapusInvList8($pageNumber,$itemPerPage,$unitkr) {
        
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = 'T';
		 $where[] = $unitkr;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	  FROM aset.tm_ajuanhapusinv a,sdm.tr_unitkerja b 
								  where a.i_orgb = b.c_satker  
								  and c_setuju_statussesmen = ?  
								  and a.i_orgb like ?",$where);
			 				   
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		 
			 $result = $db->fetchAll("SELECT i_inv_ajuanhapus,d_inv_ajuanhapus,a.i_orgb,n_unitkerja
		                       	 FROM aset.tm_ajuanhapusinv a,sdm.tr_unitkerja b 
								 where a.i_orgb = b.c_satker  
								 and c_setuju_statussesmen = ?  
								 and a.i_orgb like ? 
								 order by i_inv_ajuanhapus
								 limit $xLimit offset $xOffset",$where); 
			 $jmlResult = count($result);
		    
		
		    if($jmlResult > 0){
		       for ($j = 0; $j < $jmlResult; $j++) {
		      
              $hasilAkhir[$j] = array("i_inv_ajuanhapus"           =>(string)$result[$j]->i_inv_ajuanhapus,
								   "d_inv_ajuanhapus"           =>(string)$result[$j]->d_inv_ajuanhapus,
								   "i_orgb"           =>(string)$result[$j]->i_orgb,
								   "n_orgb"           =>(string)$result[$j]->n_unitkerja);
								  
								  
							       
		
		      }
           
       		
	         
		    }else{
				return 0;
			}
         }
		  
         return $hasilAkhir;	
       		 
	   }catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	  }
	}
//=================================== 15 Nop 07=============================================================================
public function getHapusInvListD($noHapus) {
       $status='A';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $noHapus;
		 //$where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT i_inv_ajuanhapus,d_anggaran,c_barang,
									to_char(i_aset,'09999') as i_aset, d_perolehan, 
									e_keterangan, rph_aset, 
									ur_sskel, merk_type
									FROM aset.tm_ajuanhapusinv_item a, aset.tm_sskel b, aset.tm_masterhm c
									where  substr(a.c_barang,1,1) = b.kd_gol
									       and substr(a.c_barang,2,2) = b.kd_bid 
									       and substr(a.c_barang,4,2) = b.kd_kel
									       and substr(a.c_barang,6,2) = b.kd_skel
									       and substr(a.c_barang,8,3) = b.kd_sskel 
									       and a.d_anggaran=c.thn_ang
									       and a.c_barang =c.kd_brg
									       and a.i_aset= c.no_aset
										   and i_inv_ajuanhapus = ? 
										   order by d_anggaran,c_barang,i_aset",$where);
									
									
         $jmlResult = count($result);
		 //echo '$jmlResult '.$jmlResult ;
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_inv_ajuanhapus"   =>(string)$result[$j]->i_inv_ajuanhapus,
								   "thn_ang"      		=>(string)$result[$j]->d_anggaran,
								   "kd_brg"      		=>(string)$result[$j]->c_barang,
								   "no_aset"      		=>(string)$result[$j]->i_aset,
								   "tgl_perlh"      	=>(string)$result[$j]->d_perolehan,
								   "keterangan"      	=>(string)$result[$j]->e_keterangan,
								   "ur_sskel"      		=>(string)$result[$j]->ur_sskel,
								   "rph_aset"      		=>(string)$result[$j]->rph_aset,
								   "merk_type"      	=>(string)$result[$j]->merk_type);
							       
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
public function getjabatan($nip) {
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try { 
           $where[] = $nip;
		   $where[] = $nip;
		   $db->setFetchMode(Zend_Db::FETCH_OBJ);
           // $hasilAkhir = $db->fetchOne("select count(*)  
										// from sdm.tr_jabatan A,  sdm.tr_pegawai B
										// where a.i_peg_nip = b.i_peg_nip
										// and (b.c_unit_kerja = ? or b.i_orgb = ?)
										// and b.c_unit_kerja = a.c_jabatan",$where);  
			$hasilAkhir = $db->fetchOne("select count(*)  
										from sdm.tr_jabatan A,  sdm.tm_pegawai B
										where b.i_peg_nip = ? and
										a.c_jabatan = b.c_jabatan",$where);  
       		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	} 		
 public function getTU($unitkr) {
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
         $TU 	= $db->fetchCol("SELECT i_orgb_tu FROM e_org_0_0_tm where i_orgb = ? and i_orgb_tu is not null",$unitkr); 
        return $TU;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	} 	
}		
?>