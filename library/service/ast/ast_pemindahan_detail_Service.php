<?php
class ast_pemindahan_detail_Service {
   
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

 
    //=========================================13 nop 07= pemindahan==========================================================
	public function insertAjuanPindahD(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("i_barang_ajuanpindah"      	=>$data['noPindah'],
	                           "d_anggaran"    				=>$data['thnang'],
						       "c_barang"  					=>$data['kdbrg'],
						       "i_aset_awal" 				=>$data['noaset'],
						       "d_perolehan"   				=>$data['tglPerl'],
							   "q_barang"   				=>$data['jml'],
							   "i_ruang_baru"   			=>$data['ruang'],
							   "i_ruang"   					=>$data['Nourut'],
							   "e_keterangan"   			=>$data['ktr'],
							   "i_entry"       				=>$data['nuser'],
						       "d_entry"       				=>date("Y-m-d"));
		
		
 		 $db->insert('aset.tm_ajuanpindahinv_item',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function deletAjuanPindahD(array $data) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
	     $db->beginTransaction();
	 
		 $where[] = "i_barang_ajuanpindah  	=  '".trim($data['noPindah'])."'";
	     $where[] = "d_anggaran        		=  '".trim($data['thnang'])."'";
		 $where[] = "c_barang        		=  '".trim($data['kdbrg'])."'";
		 $where[] = "i_aset_awal        	=  '".trim($data['noaset'])."'";
		 $where[] = "d_perolehan        	=  '".trim($data['tglPerl'])."'";

		 $db->delete('aset.tm_ajuanpindahinv_item', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function deletAjuanPindahMD($noPindah) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
	     $db->beginTransaction();
	 
		 $where[] = "i_barang_ajuanpindah  	=  '".$noPindah."'";
	    
		 $db->delete('aset.tm_ajuanpindahinv_item', $where);
		 $db->commit();
	     return 'sukses';
		} catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function queryAjuanPindahD($noPindah,$thnang) {
       $status='A';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $noPindah;
		 //$where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 /*$result = $db->fetchAll("SELECT i_barang_ajuanpindah,d_anggaran,c_barang,
									to_char(i_aset_awal,'09999') as i_aset_awal, d_perolehan, 
									q_barang,i_ruang, i_ruang_baru, e_keterangan, c_barang_serah, 
									ur_sskel, merk_type
									FROM aset.tm_ajuanpindahinv_item a, aset.tm_sskel b, aset.tm_masterhm c
									where  substr(a.c_barang,1,1) = b.kd_gol
									       and substr(a.c_barang,2,2) = b.kd_bid 
									       and substr(a.c_barang,4,2) = b.kd_kel
									       and substr(a.c_barang,6,2) = b.kd_skel
									       and substr(a.c_barang,8,3) = b.kd_sskel 
									       and a.c_barang =c.kd_brg
									       and a.i_aset_awal= c.no_aset
										   and c.thn_ang='$thnang'
										   and i_barang_ajuanpindah = ? ",$where);*/
			$result = $db->fetchAll("SELECT i_barang_ajuanpindah,d_anggaran,c_barang,
									to_char(i_aset_awal,'09999') as i_aset_awal, d_perolehan, 
									q_barang,i_ruang, i_ruang_baru, e_keterangan, c_barang_serah, 
									ur_sskel, merk_type
									FROM aset.tm_ajuanpindahinv_item a, aset.tm_sskel b, aset.tm_masterhm c
									where  substr(a.c_barang,1,1) = b.kd_gol
									       and substr(a.c_barang,2,2) = b.kd_bid 
									       and substr(a.c_barang,4,2) = b.kd_kel
									       and substr(a.c_barang,6,2) = b.kd_skel
									       and substr(a.c_barang,8,3) = b.kd_sskel 
									       and a.c_barang =c.kd_brg
									       and a.i_aset_awal= c.no_aset										  
										   and i_barang_ajuanpindah = ? ",$where);
															
         $jmlResult = count($result);
		 //echo '$jmlResult '.$jmlResult ;
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_barang_ajuanpindah"   =>(string)$result[$j]->i_barang_ajuanpindah,
								   "d_anggaran"      		=>(string)$result[$j]->d_anggaran,
								   "c_barang"      			=>(string)$result[$j]->c_barang,
								   "i_aset_awal"      		=>(string)$result[$j]->i_aset_awal,
								   "d_perolehan"      		=>(string)$result[$j]->d_perolehan,
								   "q_barang"      			=>(string)$result[$j]->q_barang,
								   "e_keterangan"      		=>(string)$result[$j]->e_keterangan,
								   "c_barang_serah"      	=>(string)$result[$j]->c_barang_serah,
								   "ur_sskel"      			=>(string)$result[$j]->ur_sskel,
								   "i_ruang"      			=>(string)$result[$j]->i_ruang,
								   "i_ruang_baru"      		=>(string)$result[$j]->i_ruang_baru,
								   "merk_type"      		=>(string)$result[$j]->merk_type);
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	public function getItemAjuanPindah(array $data) {
	   $status='A';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = trim($data['noPindah']);
	     $where[] = trim($data['thnang']);
		 $where[] = trim($data['kdbrg']);
		 $where[] = trim($data['noaset']);
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT i_barang_ajuanpindah,d_anggaran,c_barang,
									to_char(i_aset_awal,'09999') as i_aset_awal, d_perolehan, 
									q_barang,i_ruang, i_ruang_baru, e_keterangan, c_barang_serah, 
									ur_sskel, merk_type
									FROM aset.tm_ajuanpindahinv_item a, aset.tm_sskel b, aset.tm_masterhm c
									where  substr(a.c_barang,1,1) = b.kd_gol
									       and substr(a.c_barang,2,2) = b.kd_bid 
									       and substr(a.c_barang,4,2) = b.kd_kel
									       and substr(a.c_barang,6,2) = b.kd_skel
									       and substr(a.c_barang,8,3) = b.kd_sskel 
									       and a.d_anggaran=c.thn_ang
									       and a.c_barang =c.kd_brg
									       and a.i_aset_awal= c.no_aset
										   and i_barang_ajuanpindah = ? and d_anggaran=? 
										   and c_barang=? and i_aset_awal=? ",$where);
															
         $jmlResult = count($result);
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_barang_ajuanpindah"   =>(string)$result[$j]->i_barang_ajuanpindah,
								   "d_anggaran"      		=>(string)$result[$j]->d_anggaran,
								   "c_barang"      			=>(string)$result[$j]->c_barang,
								   "i_aset_awal"      		=>(string)$result[$j]->i_aset_awal,
								   "d_perolehan"      		=>(string)$result[$j]->d_perolehan,
								   "q_barang"      			=>(string)$result[$j]->q_barang,
								   "e_keterangan"      		=>(string)$result[$j]->e_keterangan,
								   "c_barang_serah"      	=>(string)$result[$j]->c_barang_serah,
								   "ur_sskel"      			=>(string)$result[$j]->ur_sskel,
								   "i_ruang"      			=>(string)$result[$j]->i_ruang,
								   "i_ruang_baru"      		=>(string)$result[$j]->i_ruang_baru,
								   "merk_type"      		=>(string)$result[$j]->merk_type);
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
	
	public function updateAjuanPindahD2(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $atk_dtl_parm = array("q_barang"   				=>$data['jml'],
							   "i_ruang_baru"   			=>$data['ruang'],
							   "i_ruang"   					=>$data['Nourut'],
							   "e_keterangan"   			=>$data['ktr'],
							   "d_anggaran"   				=>$data['thnang'],
							   "c_barang"   				=>$data['kdbrg'],
							   "i_aset_awal"   				=>$data['noaset'],
							   "d_perolehan"   				=>$data['tglPerl'],
							   "i_entry"       				=>$data['nuser'],
						       "d_entry"       				=>date("Y-m-d"));
	     
		 $where[] = "i_barang_ajuanpindah  =  '".trim($data['noPindah'])."'";
	     $db->update('aset.tm_ajuanpindahinv_item',$atk_dtl_parm, $where);
		 
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function updateAjuanPindahD(array $data) {
	    $registry = Zend_Registry::getInstance();
	    $db = $registry->get('db');
	    try {
	     $db->beginTransaction();
	     $atk_dtl_parm = array("q_barang"   				=>$data['jml'],
							   "i_ruang_baru"   			=>$data['ruang'],
							   "i_ruang"   					=>$data['Nourut'],
							   "e_keterangan"   			=>$data['ktr'],
							   "i_entry"       				=>$data['nuser'],
						       "d_entry"       				=>date("Y-m-d"));
	     
		 $where[] = "i_barang_ajuanpindah  =  '".trim($data['noPindah'])."'";
	     $where[] = "d_anggaran        =  '".trim($data['thnang'])."'";
		 $where[] = "c_barang   =  '".trim($data['kdbrg'])."'";
		 $where[] = "i_aset_awal   =  '".trim($data['noaset'])."'";		 
		 $db->update('aset.tm_ajuanpindahinv_item',$atk_dtl_parm, $where);
		 
		 $db->commit();
	     return 'sukses';
	    } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	//Serah Terima ===========================================================================================
	
	public function updateSerahTrmPindahD(array $data) {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $atk_dtl_parm = array("c_barang_serah"   			=>"Y",
							   "i_entry"       				=>$data['nuser'],
						       "d_entry"       				=>date("Y-m-d"));
	     
		 $where[] = "i_barang_ajuanpindah  =  '".trim($data['noPindah'])."'";
	     $where[] = "d_anggaran        =  '".trim($data['thnang'])."'";
		 $where[] = "c_barang   =  '".trim($data['kdbrg'])."'";
		 $where[] = "i_aset_awal   =  '".trim($data['noaset'])."'";
		 $where[] = "d_perolehan   =  '".trim($data['tglPerl'])."'";
		 $db->update('aset.tm_ajuanpindahinv_item',$atk_dtl_parm, $where);
		 
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	} 
	
	public function queryASerahTrmPindahD($noPindah) {
       $status='A';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $noPindah;
		 
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT i_barang_ajuanpindah,d_anggaran,c_barang,
									to_char(i_aset_awal,'09999') as i_aset_awal, d_perolehan, 
									q_barang,i_ruang, i_ruang_baru, e_keterangan, c_barang_serah, 
									ur_sskel, merk_type
									FROM aset.tm_ajuanpindahinv_item a, aset.tm_sskel b, aset.tm_masterhm c
									where  substr(a.c_barang,1,1) = b.kd_gol
									       and substr(a.c_barang,2,2) = b.kd_bid 
									       and substr(a.c_barang,4,2) = b.kd_kel
									       and substr(a.c_barang,6,2) = b.kd_skel
									       and substr(a.c_barang,8,3) = b.kd_sskel 
									       and a.c_barang =c.kd_brg
									       and a.i_aset_awal= c.no_aset 
                                           and a.d_anggaran = c.thn_ang										   
										   and (a.c_barang_serah is null or a.c_barang_serah ='')
										   and i_barang_ajuanpindah = ? ",$where);
															
         $jmlResult = count($result);
		 //echo '$jmlResult '.$jmlResult ;
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_barang_ajuanpindah"   =>(string)$result[$j]->i_barang_ajuanpindah,
								   "d_anggaran"      		=>(string)$result[$j]->d_anggaran,
								   "c_barang"      			=>(string)$result[$j]->c_barang,
								   "i_aset_awal"      		=>(string)$result[$j]->i_aset_awal,
								   "d_perolehan"      		=>(string)$result[$j]->d_perolehan,
								   "q_barang"      			=>(string)$result[$j]->q_barang,
								   "e_keterangan"      		=>(string)$result[$j]->e_keterangan,
								   "c_barang_serah"      	=>(string)$result[$j]->c_barang_serah,
								   "ur_sskel"      			=>(string)$result[$j]->ur_sskel,
								   "i_ruang"      			=>(string)$result[$j]->i_ruang,
								   "i_ruang_baru"      		=>(string)$result[$j]->i_ruang_baru,
								   "merk_type"      		=>(string)$result[$j]->merk_type);
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	public function insertSerahTrmPindah(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("i_barang_ajuanpindah"      	=>$data['noPindah'],
	                           "i_barang_serah"    			=>$data['noSerahb'],
						       "d_barang_serah"  			=>date("Y-m-d"),
						       "i_peg_nipterima" 			=>$data['nipPenerima'],
						       "i_peg_nippemberi"   		=>$data['nipPemberi'],
							   "i_entry"       				=>$data['nuser'],
						       "d_entry"       				=>date("Y-m-d"));
		 print_r($atk_mast_prm);
		 $db->insert('aset.tm_serahpindahinv_0',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function updateDirItem(array $data) {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $atk_dtl_parm = array("i_ruang"   			=>$data['ruangbaru'],
							   "i_entry"       		=>$data['nuser'],
						       "d_entry"       		=>date("Y-m-d"));
	     
		 $where[] = "d_aset_thnanggar        =  '".trim($data['thnang'])."'";
		 $where[] = "c_barang   =  '".trim($data['kdbrg'])."'";
		 $where[] = "i_aset   =  '".trim($data['noaset'])."'";
		 $db->update('aset.tm_dir_item',$atk_dtl_parm, $where);
		 
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	} 
	
	//=========================================================================================================================
	 
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
	
}
?>