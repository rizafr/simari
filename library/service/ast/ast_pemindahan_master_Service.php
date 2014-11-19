<?php
class ast_pemindahan_master_service {
   
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

	
	public function getNamaOrganisasi($iOrgb) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$result="No Name";
		$where[] = $iOrgb;
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$query="SELECT n_unitkerja ".
				" FROM sdm.tr_unitkerja where c_lokasi_unitkerja=? ";
			$result = $db->fetchOne($query,$iOrgb);
			return $result;
		} 
		catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return $result;
		}						
	}
	public function getNamaPegawai($nip) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$result="No Name";
		$where[] = $iOrgb;
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$query="SELECT n_peg ".
				" FROM sdm.tm_pegawai where i_peg_nip=? ";
			$result = $db->fetchOne($query,$nip);
			return $result;
		} 
		catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return $result;
		}						
	}
	public function getNamaPjbOrg($iOrgb) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');

		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$where[] = $iOrgb;
			$query="select b.i_peg_nip , B.n_peg, A.n_jabatan, b.c_lokasi_unitkerja
					from sdm.tr_jabatan A,  sdm.tm_pegawai b,sdm.tr_unitkerja c
					where a.c_jabatan = b.c_jabatan
					and b.i_peg_nip=?
					and b.c_lokasi_unitkerja = c.c_lokasi_unitkerja
					and b.c_eselon_i=c.c_eselon_i
					and b.c_eselon_ii=c.c_eselon_ii
					and b.c_eselon_iii=c.c_eselon_iii
					and b.c_eselon_iv=c.c_eselon_iv
					and b.c_eselon_v=c.c_eselon_v					
								";
			$result = $db->fetchAll($query,$where);
			//echo $query;
			$jmlResult = count($result);
			//"i_orgb"  				=>(string)$result[$j]->i_orgb
			for ($j = 0; $j < $jmlResult; $j++) {
	  			$hasilAkhir[$j] = array("nip"  				=>(string)$result[$j]->i_peg_nip,
									"nama"					=>(string)$result[$j]->n_peg,
									"jabatan"  				=>(string)$result[$j]->n_jabatan,
									"c_unit_kerja"  		=>(string)$result[$j]->c_lokasi_unitkerja,
									
									);
			}
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}
	

	 public function queryAjuanPindahM($unitkr) {
       $status='A';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 $result = $db->fetchAll('SELECT i_orgb_penerima,i_barang_ajuanpindah,d_barang_ajuanpindah ,b.n_unitkerja
									FROM aset.tm_ajuanpindahinv_0 a , sdm.tr_unitkerja b
									where substring(a.i_barang_ajuanpindah,1,6)=? and c_barang_statuspindah=?
									and a.i_orgb_penerima = b.c_satker',$where);
								
         $jmlResult = count($result);
		 //echo '$jmlResult '.$jmlResult ;
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb_penerima"           	=>(string)$result[$j]->i_orgb_penerima,
								   "n_orgb"           			=>(string)$result[$j]->n_unitkerja,
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
	
	public function AjuanPindahList($pageNumber,$itemPerPage,$unitkr) {
		$status='A';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		 $where[]=$unitkr;
		 $where[]=$status;
		 $where[]=$unitkr;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM aset.tm_ajuanpindahinv_0 a , sdm.tr_unitkerja b, sdm.tm_pegawai c
									where substring(i_barang_ajuanpindah,1,6)=? 
									and c_barang_statuspindah=?
									and b.c_satker= ? 
									and a.i_orgb_penerima= c.i_peg_nip
									and c.c_lokasi_unitkerja=b.c_lokasi_unitkerja
									and c.c_eselon_i=b.c_eselon_i
									and c.c_eselon_ii=b.c_eselon_ii
									and c.c_eselon_iii=b.c_eselon_iii
									and c.c_eselon_iv=b.c_eselon_iv
									and c.c_eselon_v=b.c_eselon_v
									",$where);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT i_orgb_penerima,i_barang_ajuanpindah,d_barang_ajuanpindah ,b.n_unitkerja 
									FROM aset.tm_ajuanpindahinv_0 a , sdm.tr_unitkerja b,sdm.tm_pegawai c
									where substring(i_barang_ajuanpindah,1,6)=? 
									and c_barang_statuspindah=?
									and b.c_satker=? 
									and a.i_orgb_penerima= c.i_peg_nip
									and c.c_lokasi_unitkerja=b.c_lokasi_unitkerja
									and c.c_eselon_i=b.c_eselon_i
									and c.c_eselon_ii=b.c_eselon_ii
									and c.c_eselon_iii=b.c_eselon_iii
									and c.c_eselon_iv=b.c_eselon_iv
									and c.c_eselon_v=b.c_eselon_v
									ORDER BY i_barang_ajuanpindah
									limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_orgb_penerima"       		=>(string)$result[$j]->i_orgb_penerima,
										   "n_orgb"           			=>(string)$result[$j]->n_unitkerja,
										   "i_barang_ajuanpindah"      	=>(string)$result[$j]->i_barang_ajuanpindah,
										   "d_barang_ajuanpindah"      	=>(string)$result[$j]->d_barang_ajuanpindah);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}		 
	
	
	public function insertAjuanPindahM(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("i_barang_ajuanpindah"      	=>$data['noPindah'],
	                           "d_barang_ajuanpindah"    	=>date("Y-m-d"),
						       "i_orgb_pemberi"  			=>$data['ipegnipemberi'],//c_satker pemberi
						       "i_orgb_penerima" 			=>$data['ipegnippenerima'],//c_satker penerima
						       "c_barang_statuspindah"   	=>'A',
							   "i_entry"       				=>$data['nuser'],
						       "d_entry"       				=>date("Y-m-d"));
	    
		
 		 $db->insert('aset.tm_ajuanpindahinv_0',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function updateAjuanPindahM($noPindah,$nuser) {
	//echo '$noPindah'.$noPindah;
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->beginTransaction();
	     $atk_mast_prm = array("c_barang_statuspindah"  		=>'B',
							   "d_setuju_tu"  	         	    =>date("Y-m-d"),
							   "c_setuju_statustu"              =>'Y',
						       "i_entry"       		    		=>$nuser,
						       "d_entry"       		    		=>date("Y-m-d"));
	     
		 $where[] = "i_barang_ajuanpindah = '". $noPindah ."'";
	     $db->update('aset.tm_ajuanpindahinv_0',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
		 		 
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	
	}
	
	public function deletAjuanPindahM($noPindah) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $where[] = "i_barang_ajuanpindah = '". $noPindah ."'";
		 $db->delete('aset.tm_ajuanpindahinv_0', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function querySetujuTuPindahM($unitkr) {
       $status='B';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_orgb_penerima,i_barang_ajuanpindah,d_barang_ajuanpindah ,b.n_unitkerja as n_orgp
									FROM aset.tm_ajuanpindahinv_0 a , sdm.tr_unitkerja b
									where substring(a.i_barang_ajuanpindah,1,6)=? and c_barang_statuspindah=? 
									and c_setuju_statustu is null 
									and a.i_orgb_penerima = b.c_satker',$where);
								
         $jmlResult = count($result);
		 //echo '$jmlResult '.$jmlResult ;
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb_penerima"           	=>(string)$result[$j]->i_orgb_penerima,
								   "n_orgp"           			=>(string)$result[$j]->n_orgp,
								   "i_barang_ajuanpindah"      	=>(string)$result[$j]->i_barang_ajuanpindah,
								   "d_barang_ajuanpindah"      	=>(string)$result[$j]->d_barang_ajuanpindah);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	 
	
	public function getSetujuTuPindahM($pageNumber,$itemPerPage,$unitkr) {
		$status='B';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
         //$TU 	= $db->fetchCol("SELECT i_orgb_tu FROM e_org_0_0_tm where i_orgb = ? and i_orgb_tu is not null",$unitkr);
         $TU 	= $db->fetchCol("SELECT c_dept FROM sdm.tr_unitkerja where c_satker = ? and c_dept is not null",$unitkr);
				$unitTU = $TU[0];
		 		
		 if($unitTU !=''){
					if (substr($unitTU,0,2) == 'DP' ){
			               $unitTULike = substr($unitTU,0,3).'%';
			        }else{
			               $unitTULike = substr($unitTU,0,2).'%';
			        }
         $where[] = $status;
         $where[] = $unitTULike; 		 
		  
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM aset.tm_ajuanpindahinv_0_tm a , sdm.tr_unitkerja b
									where c_barang_statuspindah=? 
									and c_setuju_statustu is null 
									and a.i_orgb_penerima = b.c_satker and  substring(a.i_barang_ajuanpindah,1,6) like ?",$where);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT i_orgb_penerima,i_barang_ajuanpindah,d_barang_ajuanpindah ,b.n_unitkerja as n_orgp
									FROM aset.tm_ajuanpindahinv_0 a , sdm.tr_unitkerja b
									where c_barang_statuspindah=? 
									and c_setuju_statustu is null 
									and a.i_orgb_penerima = b.c_satker and substring(a.i_barang_ajuanpindah,1,6) like ?
									ORDER BY i_barang_ajuanpindah
									limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_orgb_penerima"       =>(string)$result[$j]->i_orgb_penerima,
								   "n_orgp"           			=>(string)$result[$j]->n_orgp,
								   "i_barang_ajuanpindah"      	=>(string)$result[$j]->i_barang_ajuanpindah,
								   "d_barang_ajuanpindah"      	=>(string)$result[$j]->d_barang_ajuanpindah);
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
	public function getSetujuTuPindahM2($pageNumber,$itemPerPage,$unitkr) {
		$status='B';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		 $where[]=$unitkr;
		 $where[]=$status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM aset.tm_ajuanpindahinv_0 a , sdm.tr_unitkerja b
									where substring(a.i_barang_ajuanpindah,1,6)=? and c_barang_statuspindah=? 
									and c_setuju_statustu is null 
									and a.i_orgb_pemberi = b.c_satker",$where);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT i_orgb_penerima,i_barang_ajuanpindah,d_barang_ajuanpindah ,b.n_unitkerja as n_orgp
									FROM aset.tm_ajuanpindahinv_0 a , sdm.tr_unitkerja b
									where substring(a.i_barang_ajuanpindah,1,6)=? and c_barang_statuspindah=? 
									and c_setuju_statustu is null 
									and a.i_orgb_pemberi = b.c_satker
									ORDER BY i_barang_ajuanpindah
									limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_orgb_penerima"       =>(string)$result[$j]->i_orgb_penerima,
								   "n_orgp"           			=>(string)$result[$j]->n_orgp,
								   "i_barang_ajuanpindah"      	=>(string)$result[$j]->i_barang_ajuanpindah,
								   "d_barang_ajuanpindah"      	=>(string)$result[$j]->d_barang_ajuanpindah);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
	public function updateSetujuTuPindahM($noPindah,$setuju,$keterangan,$nuser) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->beginTransaction();
	     $atk_mast_prm = array("c_setuju_statustu"  		=>$setuju,
						       "d_setuju_tu"   				=>date("Y-m-d"),
							   "e_alasan"   				=>$keterangan,
							   "i_entry"       		    	=>$nuser,
						       "d_entry"       		    	=>date("Y-m-d"));
	     
		 
		 $where[] = "i_barang_ajuanpindah = '".trim($noPindah)."'";
	     $db->update('aset.tm_ajuanpindahinv_0',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
		 		 
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
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
		 $result = $db->fetchAll('SELECT i_orgb_penerima,i_barang_ajuanpindah,d_barang_ajuanpindah ,b.n_unitkerja as n_orgp
									FROM aset.tm_ajuanpindahinv_0 a , sdm.tr_unitkerja b
									where substring(a.i_barang_ajuanpindah,1,6)=? and c_barang_statuspindah=? 
									and c_setuju_statustu = ? and c_setuju_statuskabag is null
									and a.i_orgb_pemberi = b.c_satker',$where);
								
         $jmlResult = count($result);
		 //echo '$jmlResult '.$jmlResult ;
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb_penerima"           	=>(string)$result[$j]->i_orgb_penerima,
								   "n_orgp"           			=>(string)$result[$j]->n_orgp,
								   "i_barang_ajuanpindah"      	=>(string)$result[$j]->i_barang_ajuanpindah,
								   "d_barang_ajuanpindah"      	=>(string)$result[$j]->d_barang_ajuanpindah);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	 
	
	public function getSetujuKabagPindahM($pageNumber,$itemPerPage,$unitkr) {
		$status='B';
	    $status2='Y';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
		 
		 $where[] = $status;
		 $where[] = $status2;
		 $where[] = $unitkr;
		
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM aset.tm_ajuanpindahinv_0 a , sdm.tr_unitkerja b,sdm.tm_pegawai c
									where c_barang_statuspindah=? 
									and c_setuju_statustu = ? 
									and c_setuju_statuskabag is null
									and b.c_satker=? 
									and a.i_orgb_pemberi = c.i_peg_nip
									and c.c_eselon_i=b.c_eselon_i
									and c.c_eselon_ii=b.c_eselon_ii
									and c.c_eselon_iii=b.c_eselon_iii
									and c.c_eselon_iv=b.c_eselon_iv
									and c.c_eselon_v=b.c_eselon_v
									",$where);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 $sql="SELECT i_orgb_penerima,i_barang_ajuanpindah,d_barang_ajuanpindah ,b.n_unitkerja as n_orgp
					FROM aset.tm_ajuanpindahinv_0 a , sdm.tr_unitkerja b,sdm.tm_pegawai c
					where c_barang_statuspindah='$status' 
					and c_setuju_statustu = '$status2' 
					and c_setuju_statuskabag is null
					and b.c_satker='$unitkr' 
					and a.i_orgb_pemberi = c.i_peg_nip
					and c.c_eselon_i=b.c_eselon_i
					and c.c_eselon_ii=b.c_eselon_ii
					and c.c_eselon_iii=b.c_eselon_iii
					and c.c_eselon_iv=b.c_eselon_iv
					and c.c_eselon_v=b.c_eselon_v
					ORDER BY i_barang_ajuanpindah
					limit $xLimit offset $xOffset";
			 //echo $sql;
			 $result = $db->fetchAll("SELECT i_orgb_penerima,i_barang_ajuanpindah,d_barang_ajuanpindah ,b.n_unitkerja as n_orgp
									FROM aset.tm_ajuanpindahinv_0 a , sdm.tr_unitkerja b,sdm.tm_pegawai c
									where c_barang_statuspindah=? 
									and c_setuju_statustu = ? 
									and c_setuju_statuskabag is null
									and b.c_satker=? 
									and a.i_orgb_pemberi = c.i_peg_nip
									and c.c_eselon_i=b.c_eselon_i
									and c.c_eselon_ii=b.c_eselon_ii
									and c.c_eselon_iii=b.c_eselon_iii
									and c.c_eselon_iv=b.c_eselon_iv
									and c.c_eselon_v=b.c_eselon_v
									ORDER BY i_barang_ajuanpindah
									limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_orgb_penerima"       =>(string)$result[$j]->i_orgb_penerima,
								   "n_orgp"           			=>(string)$result[$j]->n_orgp,
								   "i_barang_ajuanpindah"      	=>(string)$result[$j]->i_barang_ajuanpindah,
								   "d_barang_ajuanpindah"      	=>(string)$result[$j]->d_barang_ajuanpindah);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
	public function getSetujuKabagPindahM2($pageNumber,$itemPerPage,$unitkr) {
		$status='B';
	    $status2='Y';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		 $where[] = $unitkr;
		 $where[] = $status;
		 $where[] = $status2;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM aset.tm_ajuanpindahinv_0 a , sdm.tr_unitkerja b
									where substring(a.i_barang_ajuanpindah,1,6)=? and c_barang_statuspindah=? 
									and c_setuju_statustu = ? and c_setuju_statuskabag is null
									and a.i_orgb_pemberi = b.c_satker",$where);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT i_orgb_penerima,i_barang_ajuanpindah,d_barang_ajuanpindah ,b.n_unitkerja as n_orgp
									FROM aset.tm_ajuanpindahinv_0 a , sdm.tr_unitkerja b
									where substring(a.i_barang_ajuanpindah,1,6)=? and c_barang_statuspindah=? 
									and c_setuju_statustu = ? and c_setuju_statuskabag is null
									and a.i_orgb_pemberi = b.c_satkers
									ORDER BY i_barang_ajuanpindah
									limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_orgb_penerima"       =>(string)$result[$j]->i_orgb_penerima,
								   "n_orgp"           			=>(string)$result[$j]->n_orgp,
								   "i_barang_ajuanpindah"      	=>(string)$result[$j]->i_barang_ajuanpindah,
								   "d_barang_ajuanpindah"      	=>(string)$result[$j]->d_barang_ajuanpindah);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
	public function updateSetujuKabagPindahM($noPindah,$setuju,$keterangan,$nuser) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->beginTransaction();
	     $atk_mast_prm = array("c_setuju_statuskabag"  		=>$setuju,
						       "d_setuju_kabag" 			=>date("Y-m-d"),
							   "e_alasan" 					=>$keterangan,
							   "i_entry"       		    	=>$nuser,
						       "d_entry"       		    	=>date("Y-m-d"));
	     
		 
		 $where[] = "i_barang_ajuanpindah = '".trim($noPindah)."'";
	     $db->update('aset.tm_ajuanpindahinv_0',$atk_mast_prm, $where);
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
		 $result = $db->fetchAll("SELECT distinct i_orgb_penerima,a.i_barang_ajuanpindah,d_barang_ajuanpindah ,b.n_unitkerja as n_orgp
									FROM aset.tm_ajuanpindahinv_0 a , sdm.tr_unitkerja b, aset.tmt_ajuanpindahinv_item c
									where substring(a.i_barang_ajuanpindah,1,6)=? and c_barang_statuspindah=?
									and c_setuju_statustu = ? and c_setuju_statuskabag =? 
									and (c.c_barang_serah is null or c.c_barang_serah ='')
									and a.i_orgb_penerima = b.c_satker and a.i_barang_ajuanpindah = c.i_barang_ajuanpindah",$where);
								
         $jmlResult = count($result);
		 //echo '$jmlResult '.$jmlResult ;
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb_penerima"           	=>(string)$result[$j]->i_orgb_penerima,
								   "n_orgp"           			=>(string)$result[$j]->n_orgp,
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
	
	public function getSerahTrmPindahM($pageNumber,$itemPerPage,$unitkr) {
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
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select  count(distinct(a.i_barang_ajuanpindah)) FROM aset.tm_ajuanpindahinv_0 a , sdm.tr_unitkerja b, aset.tm_ajuanpindahinv_item c
										where substring(a.i_barang_ajuanpindah,1,6)=? and c_barang_statuspindah=?
										and c_setuju_statustu = ? and c_setuju_statuskabag =? 
										and (c.c_barang_serah is null or c.c_barang_serah ='')
										and a.i_orgb_penerima = b.c_satker and a.i_barang_ajuanpindah = c.i_barang_ajuanpindah",$where);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT distinct i_orgb_penerima,a.i_barang_ajuanpindah,d_barang_ajuanpindah ,b.n_unitkerja as n_orgp, 
			                            i_orgb_pemberi, d.n_orgb as n_orgb_pemberi
										FROM aset.tm_ajuanpindahinv_0 a , sdm.tr_unitkerja b, 
										aset.tm_ajuanpindahinv_item c, sdm.tr_unitkerja d 
										where substring(a.i_barang_ajuanpindah,1,6)=? and c_barang_statuspindah=?
										and c_setuju_statustu = ? and c_setuju_statuskabag =? 
										and (c.c_barang_serah is null or c.c_barang_serah ='')
										and a.i_orgb_penerima = b.c_satker 
										and a.i_orgb_pemberi = d.c_satker 
										and a.i_barang_ajuanpindah = c.i_barang_ajuanpindah
										ORDER BY i_barang_ajuanpindah
										limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_orgb_penerima"       =>(string)$result[$j]->i_orgb_penerima,
					               "i_orgb_pemberi"           			=>(string)$result[$j]->i_orgb_pemberi,
								   "n_orgp"           			=>(string)$result[$j]->n_orgp,
								   "n_orgb_pemberi"           			=>(string)$result[$j]->n_orgb_pemberi,
								   "i_barang_ajuanpindah"      	=>(string)$result[$j]->i_barang_ajuanpindah,
								   "d_barang_ajuanpindah"      	=>(string)$result[$j]->d_barang_ajuanpindah);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
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
		 $result = $db->fetchAll('SELECT c_satker,n_unitkerja,c_lokasi_unitkerja FROM sdm.tr_unitkerja where c_satker=?',$unitkr);
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb"           =>(string)$result[$j]->c_satker,
		                           "c_lokasi_unitkerja"           =>(string)$result[$j]->c_lokasi_unitkerja,
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
		 // $db->beginTransaction();
	     // $db->setFetchMode(Zend_Db::FETCH_OBJ);
		 // $where[] = $data['unitkr'];
		 // $where[] = $data['modl'];
		 // $result = $db->fetchOne('SELECT aset.gen_nomor(?,?)',$where);
		
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
		$result = $db->fetchOne('select count(*)
							from sdm.tr_unitkerja a,sdm.tm_pegawai b
							where
							a.c_lokasi_unitkerja = b.c_lokasi_unitkerja
							and a.c_eselon_i = b.c_eselon_i
							and a.c_eselon_ii = b.c_eselon_ii
							and a.c_eselon_iii = b.c_eselon_iii
							and a.c_eselon_iv = b.c_eselon_iv
							and a.c_eselon_v = b.c_eselon_v
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
	public function getCariNamaBrg($pageNumber,$itemPerPage,$nip,$namaBarang) {
	   $namaBarang = strtoupper($namaBarang);
	   $nbrg = '%'.$namaBarang.'%';
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $nip;
		 $where[] = $nbrg;
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*) 
 						 from  aset.tm_sskel a, aset.tm_dir_item b,
										aset.tm_ruang C
										where substr(b.c_barang,1,1) = a.kd_gol
										and substr(b.c_barang,2,2) = a.kd_bid 
										and substr(b.c_barang,4,2) = a.kd_kel
										and substr(b.c_barang,6,2) = a.kd_skel
										and substr(b.c_barang,8,3) = a.kd_sskel 
										and c.nip_pjrug = ?
										and not exists(select d.c_barang from aset.tm_ajuanpindahinv_item d
										where d.c_barang=b.c_barang)
										and upper(ur_sskel) like ?  
										and b.i_ruang = c.kd_ruang",$where);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT distinct b.d_aset_thnanggar, b.c_barang, to_char(b.i_aset,'09999') as  i_aset ,
										ur_sskel, d_barang_peroleh, b.i_ruang
										from  aset.tm_sskel a, aset.tm_dir_item b,
										aset.tm_ruang C
										where substr(b.c_barang,1,1) = a.kd_gol
										and substr(b.c_barang,2,2) = a.kd_bid 
										and substr(b.c_barang,4,2) = a.kd_kel
										and substr(b.c_barang,6,2) = a.kd_skel
										and substr(b.c_barang,8,3) = a.kd_sskel 
										and c.nip_pjrug = ?
										and not exists(select d.c_barang from aset.tm_ajuanpindahinv_item d
										where d.c_barang=b.c_barang)
										and upper(ur_sskel) like ?  
										and b.i_ruang = c.kd_ruang
										ORDER BY b.d_aset_thnanggar
						 limit $xLimit offset $xOffset",$where); 
						 
						 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("d_aset_thnanggar"          =>(string)$result[$j]->d_aset_thnanggar,
										"c_barang"           		=>(string)$result[$j]->c_barang,
										"i_aset"           			=>(string)$result[$j]->i_aset,
										"ur_sskel"          		=>(string)$result[$j]->ur_sskel,
										"d_barang_peroleh"          =>(string)$result[$j]->d_barang_peroleh,
										"i_ruang"   				=>(string)$result[$j]->i_ruang);
									
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	 
	public function getAjupindahanInvList($pageNumber,$itemPerPage,$unitkr,$unitkr1) {
       $statustu = 'A'; 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
         
		 $where[] = $statustu;
		 $where[] = $unitkr;
		 $where[] = $unitkr1;
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
		    $sql="SELECT count(*)
				  FROM aset.tm_ajuanpindahinv_0 a,sdm.tm_pegawai c,sdm.tr_unitkerja b 
				  where 
				  a.i_orgb_pemberi = c.i_peg_nip
                  and c.c_lokasi_unitkerja=b.c_lokasi_unitkerja
                  and c.c_eselon_i=b.c_eselon_i				  
                  and c.c_eselon_ii=b.c_eselon_ii				  
                  and c.c_eselon_iii=b.c_eselon_iii				  
                  and c.c_eselon_iv=b.c_eselon_iv				  
                  and c.c_eselon_v=b.c_eselon_v
                  and b.c_satker='$unitkr1'				  
				  and c_setuju_statustu is null
				  and c_barang_statuspindah = '$statustu'
				  and (substring(a.i_barang_ajuanpindah,1,6) ='$unitkr1'
				  or substring(a.i_barang_ajuanpindah,1,6) ='$unitkr1')";
			$hasilAkhir = $db->fetchOne($sql);
			 				   
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		     $sql="SELECT i_barang_ajuanpindah,d_barang_ajuanpindah,a.i_orgb_pemberi,b.n_unitkerja,b.c_lokasi_unitkerja
				  FROM aset.tm_ajuanpindahinv_0 a,sdm.tm_pegawai c,sdm.tr_unitkerja b 
				  where 
				  a.i_orgb_pemberi = c.i_peg_nip
                  and c.c_lokasi_unitkerja=b.c_lokasi_unitkerja
                  and c.c_eselon_i=b.c_eselon_i				  
                  and c.c_eselon_ii=b.c_eselon_ii				  
                  and c.c_eselon_iii=b.c_eselon_iii				  
                  and c.c_eselon_iv=b.c_eselon_iv				  
                  and c.c_eselon_v=b.c_eselon_v
                  and b.c_satker='$unitkr1'				  
				  and c_setuju_statustu is null
				  and c_barang_statuspindah = '$statustu'
				  and (substring(a.i_barang_ajuanpindah,1,6) ='$unitkr1'
				  or substring(a.i_barang_ajuanpindah,1,6) ='$unitkr1')";
			//echo $sql;
			 $result = $db->fetchAll($sql); 
			 $jmlResult = count($result);
		     
		    
		    if($jmlResult > 0){
		       for ($j = 0; $j < $jmlResult; $j++) {
		      
              $hasilAkhir[$j] = array("i_barang_ajuanpindah"           =>(string)$result[$j]->i_barang_ajuanpindah,
								   "d_barang_ajuanpindah"           =>(string)$result[$j]->d_barang_ajuanpindah,
								   "i_orgb"           =>(string)$result[$j]->c_lokasi_unitkerja,
								   "n_orgb"           =>(string)$result[$j]->n_unitkerja);
								  
								  
							       
		
		      }
           
       		
	         
		     }
			 //else{
				// return 0;
			// }
         }
		 
         return $hasilAkhir;		 
	   }catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	  }
	}
	
	 
	public function getAjupindahanInvListA($pageNumber,$itemPerPage,$unitkr,$unitkr1) {
       $statustu = 'B'; 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
         
		 $where[] = $statustu;
		 $where[] = $unitkr;
		 $where[] = $unitkr1;
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
		    $sql="SELECT count(*)
				  FROM aset.tm_ajuanpindahinv_0 a,sdm.tr_unitkerja b,sdm.tm_pegawai c 
				  where a.i_orgb_pemberi = c.i_peg_nip
				  and c.lokasi_unitkerja=b.c_lokasi_unitkerja
				  and c.c_eselon_i=b.c_eselon_i								  
				  and c.c_eselon_ii=b.c_eselon_ii								  
				  and c.c_eselon_iii=b.c_eselon_iii								  
				  and c.c_eselon_iv=b.c_eselon_iv								  
				  and c.c_eselon_v=b.c_eselon_v
				  and b.c_satker='$unitkr1'								  
				  and c_setuju_statustu is null
				  and c_barang_statuspindah = '$statustu'
				  and (substring(a.i_barang_ajuanpindah,1,6) like '$unitkr1'
				  or substring(a.i_barang_ajuanpindah,1,6) like '$unitkr1')";
			$hasilAkhir = $db->fetchOne($sql);
			 				   
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		      $sql="SELECT i_barang_ajuanpindah,d_barang_ajuanpindah,a.i_orgb_pemberi,b.n_unitkerja,b.c_lokasi_unitkerja
				  FROM aset.tm_ajuanpindahinv_0 a,sdm.tr_unitkerja b,sdm.tm_pegawai c 
				  where a.i_orgb_pemberi = c.i_peg_nip
				  and c.lokasi_unitkerja=b.c_lokasi_unitkerja
				  and c.c_eselon_i=b.c_eselon_i								  
				  and c.c_eselon_ii=b.c_eselon_ii								  
				  and c.c_eselon_iii=b.c_eselon_iii								  
				  and c.c_eselon_iv=b.c_eselon_iv								  
				  and c.c_eselon_v=b.c_eselon_v
				  and b.c_satker='$unitkr1'								  
				  and c_setuju_statustu is null
				  and c_barang_statuspindah = '$statustu'
				  and (substring(a.i_barang_ajuanpindah,1,6) like '$unitkr1'
				  or substring(a.i_barang_ajuanpindah,1,6) like '$unitkr1')";
			 $result = $db->fetchAll($sql); 
			 $jmlResult = count($result);
		     
		
		    if($jmlResult > 0){
		       for ($j = 0; $j < $jmlResult; $j++) {
		      
              $hasilAkhir[$j] = array("i_barang_ajuanpindah"        =>(string)$result[$j]->i_barang_ajuanpindah,
								   "d_barang_ajuanpindah"           =>(string)$result[$j]->d_barang_ajuanpindah,
								   "i_orgb"                         =>(string)$result[$j]->c_lokasi_unitkerja,
								   "n_orgb"                         =>(string)$result[$j]->n_unitkerja);
								  
								  
							       
		
		      }
	         
		    }
			// else{
				// return 0;
			// }
         }
         return $hasilAkhir;		 
	   }catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	  }
	}
	
	public function getPerlpindahanInvList($pageNumber,$itemPerPage,$unitkr,$unitkr1) {
       $statustu="Y"; 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $unitkr1;
		 $where[] = $statustu;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
		    $sql="SELECT count(*)
				FROM aset.tm_ajuanpindahinv_0 a,sdm.tr_unitkerja b,sdm.tm_pegawai c 
				where a.i_orgb_pemberi = c.i_peg_nip
                and c.c_eselon_i=b.c_eselon_i				
                and c.c_eselon_ii=b.c_eselon_ii				
                and c.c_eselon_iii=b.c_eselon_iii				
                and c.c_eselon_iv=b.c_eselon_iv				
                and c.c_eselon_v=b.c_eselon_v
                and b.c_satker='$unitkr1'				
				and c_setuju_statuskabag is null 
				and (substring(a.i_barang_ajuanpindah,1,6) like '$unitkr1'
				or substring(a.i_barang_ajuanpindah,1,6) like '$unitkr1') 
				and c_setuju_statustu = '$statustu'";
			$hasilAkhir = $db->fetchOne($sql);
			 				   
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		     $sql="SELECT i_barang_ajuanpindah,d_barang_ajuanpindah,a.i_orgb_pemberi,b.n_unitkerja,b.c_lokasi_unitkerja
				FROM aset.tm_ajuanpindahinv_0 a,sdm.tr_unitkerja b,sdm.tm_pegawai c 
				where a.i_orgb_pemberi = c.i_peg_nip
                and c.c_eselon_i=b.c_eselon_i				
                and c.c_eselon_ii=b.c_eselon_ii				
                and c.c_eselon_iii=b.c_eselon_iii				
                and c.c_eselon_iv=b.c_eselon_iv				
                and c.c_eselon_v=b.c_eselon_v
                and b.c_satker='$unitkr1'				
				and c_setuju_statuskabag is null 
				and (substring(a.i_barang_ajuanpindah,1,6) like '$unitkr1'
				or substring(a.i_barang_ajuanpindah,1,6) like '$unitkr1') 
				and c_setuju_statustu = '$statustu'";
			 $result = $db->fetchAll($sql); 
			 $jmlResult = count($result);
		 
		
		 if($jmlResult > 0){
		    for ($j = 0; $j < $jmlResult; $j++) {
		 
               $hasilAkhir[$j] = array("i_barang_ajuanpindah"           =>(string)$result[$j]->i_barang_ajuanpindah,
								   "d_barang_ajuanpindah"           =>(string)$result[$j]->d_barang_ajuanpindah,
								   "i_orgb"           =>(string)$result[$j]->c_lokasi_unitkerja,
								   "n_orgb"           =>(string)$result[$j]->n_unitkerja);
								  
								  
							       
		
		    }
          	
	     
		}
		// else{
				// return 0;
			// } 
		 }
       return $hasilAkhir;		 
	   }catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	  }
	}
	public function getPerlpindahanInvListA($pageNumber,$itemPerPage,$unitkr,$unitkr1) {
       $statustu="T"; 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	    
	   try {
	     $where[] = $unitkr;
		 $where[] = $unitkr1;
		 $where[] = $statustu;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
		    $sql="SELECT count(*)
				  FROM aset.tm_ajuanpindahinv_0 a,sdm.tr_unitkerja b,sdm.tm_pegawai c
				  where a.i_orgb_pemberi = c.i_peg_nip
                  and c.c_lokasi_unitkerja=b.c_lokasi_unitkerja
                  and c.c_eselon_i=b.c_eselon_i				  
                  and c.c_eselon_ii=b.c_eselon_ii				  
                  and c.c_eselon_iii=b.c_eselon_iii				  
                  and c.c_eselon_iv=b.c_eselon_iv				  
                  and c.c_eselon_v=b.c_eselon_v
                  and b.c_satker='$unitkr1'				  
				  and (substring(a.i_barang_ajuanpindah,1,6) like '$unitkr1'
					   or substring(a.i_barang_ajuanpindah,1,6) like '$unitkr1')
				  and c_setuju_statustu = '$statustu'";
			$hasilAkhir = $db->fetchOne($sql);
			 				   
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		      $sql="SELECT i_barang_ajuanpindah,d_barang_ajuanpindah,a.i_orgb_pemberi,b.n_unitkerja,b.c_lokasi_unitkerja
				  FROM aset.tm_ajuanpindahinv_0 a,sdm.tr_unitkerja b,sdm.tm_pegawai c
				  where a.i_orgb_pemberi = c.i_peg_nip
                  and c.c_lokasi_unitkerja=b.c_lokasi_unitkerja
                  and c.c_eselon_i=b.c_eselon_i				  
                  and c.c_eselon_ii=b.c_eselon_ii				  
                  and c.c_eselon_iii=b.c_eselon_iii				  
                  and c.c_eselon_iv=b.c_eselon_iv				  
                  and c.c_eselon_v=b.c_eselon_v
                  and b.c_satker='$unitkr1'				  
				  and (substring(a.i_barang_ajuanpindah,1,6) like '$unitkr1'
					   or substring(a.i_barang_ajuanpindah,1,6) like '$unitkr1')
				  and c_setuju_statustu = '$statustu'";
			 $result = $db->fetchAll($sql); 
			 $jmlResult = count($result);		 
		
		 if($jmlResult > 0){
		    for ($j = 0; $j < $jmlResult; $j++) {
		 
               $hasilAkhir[$j] = array("i_barang_ajuanpindah"           =>(string)$result[$j]->i_barang_ajuanpindah,
								   "d_barang_ajuanpindah"           =>(string)$result[$j]->d_barang_ajuanpindah,
								   "i_orgb"           =>(string)$result[$j]->c_lokasi_unitkerja,
								   "n_orgb"           =>(string)$result[$j]->n_unitkerja);
								  
								  
							       
		
		    }
          	
	     
		}
		// else{
				// return 0;
			// } 
		 }	
	   return $hasilAkhir;	  
	   }catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	  }
	}
	public function getBagpindahanInvList($pageNumber,$itemPerPage,$unitkr,$unitkr1) {
       $statustu="T"; 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $unitkr1;
		 $where[] = $statustu;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
		    $sql="SELECT count(*)
				FROM aset.tm_ajuanpindahinv_0 a,sdm.tr_unitkerja b,sdm.tm_pegawai c
				where a.i_orgb_pemberi = c.i_peg_nip
                and c.c_eselon_i=b.c_eselon_i				
                and c.c_eselon_ii=b.c_eselon_ii				
                and c.c_eselon_iii=b.c_eselon_iii				
                and c.c_eselon_iv=b.c_eselon_iv				
                and c.c_eselon_v=b.c_eselon_v
                and b.c_satker='$unitkr1'				
				and (substring(a.i_barang_ajuanpindah,1,6) like '$unitkr1' 
				or substring(a.i_barang_ajuanpindah,1,6) like '$unitkr1') 
				and c_setuju_statuskabag = '$statustu' 
				and not exists(select * from aset.tm_serahpindahinv_0 c
				where a.i_barang_ajuanpindah = c.i_barang_ajuanpindah)
				";
			$hasilAkhir = $db->fetchOne($sql);
			 				   
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		     	$sql="SELECT i_barang_ajuanpindah,d_barang_ajuanpindah,a.i_orgb_pemberi,b.n_unitkerja,b.c_lokasi_unitkerja
				FROM aset.tm_ajuanpindahinv_0 a,sdm.tr_unitkerja b,sdm.tm_pegawai c
				where a.i_orgb_pemberi = c.i_peg_nip
                and c.c_eselon_i=b.c_eselon_i				
                and c.c_eselon_ii=b.c_eselon_ii				
                and c.c_eselon_iii=b.c_eselon_iii				
                and c.c_eselon_iv=b.c_eselon_iv				
                and c.c_eselon_v=b.c_eselon_v
                and b.c_satker='$unitkr1'				
				and (substring(a.i_barang_ajuanpindah,1,6) like '$unitkr1' 
				or substring(a.i_barang_ajuanpindah,1,6) like '$unitkr1') 
				and c_setuju_statuskabag = '$statustu' 
				and not exists(select * from aset.tm_serahpindahinv_0 c
				where a.i_barang_ajuanpindah = c.i_barang_ajuanpindah)
				";
			 $result = $db->fetchAll($sql); 
			 $jmlResult = count($result);
		 		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_barang_ajuanpindah"           =>(string)$result[$j]->i_barang_ajuanpindah,
								   "d_barang_ajuanpindah"           =>(string)$result[$j]->d_barang_ajuanpindah,
								   "i_orgb"           =>(string)$result[$j]->c_lokasi_unitkerja,
								   "n_orgb"           =>(string)$result[$j]->n_unitkerja);
								  
								  
							       
		
		      }
          	
	    
		}
		// else{
				// return 0;
			// } 
		}
        return $hasilAkhir;		
	   }catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	  }
	  
	}
	public function getBagpindahanInvListA($pageNumber,$itemPerPage,$unitkr,$unitkr1) {
       $statuskbag = 'Y';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $unitkr1;
		 $where[] = $statuskbag;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
		    $sql="SELECT count(*)
				FROM aset.tm_ajuanpindahinv_0 a,sdm.tr_unitkerja b,sdm.tm_pegawai c
				where a.i_orgb_pemberi = c.i_peg_nip
				and c.c_eselon_i=b.c_eselon_i
				and c.c_eselon_ii=b.c_eselon_ii
				and c.c_eselon_iii=b.c_eselon_iii
				and c.c_eselon_iv=b.c_eselon_iv
				and c.c_eselon_v=b.c_eselon_v
				and b.c_satker='$unitkr1'
				and (substring(a.i_barang_ajuanpindah,1,6) like '$unitkr1' 
				or substring(a.i_barang_ajuanpindah,1,6) like '$unitkr1') 
				and c_setuju_statuskabag = '$statuskbag'
				and not exists (select * from aset.tm_serahpindahinv_0 C
				where C.i_barang_ajuanpindah = a.i_barang_ajuanpindah)";
			$hasilAkhir = $db->fetchOne($sql);
			 				   
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
             $sql="SELECT i_barang_ajuanpindah,d_barang_ajuanpindah,a.i_orgb_pemberi,b.n_unitkerja,b.c_lokasi_unitkerja
				FROM aset.tm_ajuanpindahinv_0 a,sdm.tr_unitkerja b,sdm.tm_pegawai c
				where a.i_orgb_pemberi = c.i_peg_nip
				and c.c_eselon_i=b.c_eselon_i
				and c.c_eselon_ii=b.c_eselon_ii
				and c.c_eselon_iii=b.c_eselon_iii
				and c.c_eselon_iv=b.c_eselon_iv
				and c.c_eselon_v=b.c_eselon_v
				and b.c_satker='$unitkr1'
				and (substring(a.i_barang_ajuanpindah,1,6) like '$unitkr1' 
				or substring(a.i_barang_ajuanpindah,1,6) like '$unitkr1') 
				and c_setuju_statuskabag = '$statuskbag'
				and not exists (select * from aset.tm_serahpindahinv_0 C
				where C.i_barang_ajuanpindah = a.i_barang_ajuanpindah)";			 
			 $result = $db->fetchAll($sql); 
								 
			 $jmlResult = count($result);
		 
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_barang_ajuanpindah"           =>(string)$result[$j]->i_barang_ajuanpindah,
								   "d_barang_ajuanpindah"           =>(string)$result[$j]->d_barang_ajuanpindah,
								   "i_orgb"           =>(string)$result[$j]->c_lokasi_unitkerja,
								   "n_orgb"           =>(string)$result[$j]->n_unitkerja);
								  
								  
							       
		
		      }
          	
	     
		}
		// else{
				// return 0;
			// } 
		}
       return $hasilAkhir;		
	   }catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	  }
	  
	}
	public function getSerahpindahanInvList($pageNumber,$itemPerPage,$unitkr,$unitkr1) {
       $statustu="Y"; 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $unitkr1;
		
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
		    $sql="SELECT count(*)
				  FROM aset.tm_serahpindahinv_0 a,sdm.tr_unitkerja b,sdm.tm_pegawai c
				  where substr(a.i_barang_serah,1,6) = b.c_satker 
				  and (substr(i_barang_serah,1,6) like '$unitkr1' or substr(i_barang_serah,1,6) like '$unitkr1') 
				  and a.i_peg_nipterima = c.i_peg_nip
				  and a.i_peg_nippemberi=c.i_peg_nip
				  and c.c_lokasi_unitkerja=b.c_lokasi_unitkerja
				  and c.c_eselon_i=b.c_eselon_i
				  and c.c_eselon_ii=b.c_eselon_ii
				  and c.c_eselon_iii=b.c_eselon_iii
				  and c.c_eselon_iv=b.c_eselon_iv
				  and c.c_eselon_v=b.c_eselon_v
				  and b.c_satker='$unitkr1'
				  ";
			$hasilAkhir = $db->fetchOne($sql);
			 				   
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		     $sql="SELECT i_barang_serah,d_barang_serah,b.c_lokasi_unitkerja,b.n_unitkerja
				  FROM aset.tm_serahpindahinv_0 a,sdm.tr_unitkerja b,sdm.tm_pegawai c
				  where substr(a.i_barang_serah,1,6) = b.c_satker 
				  and (substr(i_barang_serah,1,6) like '$unitkr1' or substr(i_barang_serah,1,6) like '$unitkr1') 
				  and a.i_peg_nipterima = c.i_peg_nip
				  and a.i_peg_nippemberi=c.i_peg_nip
				  and c.c_lokasi_unitkerja=b.c_lokasi_unitkerja
				  and c.c_eselon_i=b.c_eselon_i
				  and c.c_eselon_ii=b.c_eselon_ii
				  and c.c_eselon_iii=b.c_eselon_iii
				  and c.c_eselon_iv=b.c_eselon_iv
				  and c.c_eselon_v=b.c_eselon_v
				  and b.c_satker='$unitkr1'
				  order by i_barang_serah
				  limit $xLimit offset $xOffset
				  ";
			 $result = $db->fetchAll($sql); 
			 $jmlResult = count($result);
		 
		 
		 
		  
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_barang_ajuanpindah"           =>(string)$result[$j]->i_barang_serah,
								   "d_barang_ajuanpindah"           =>(string)$result[$j]->d_barang_serah,
								   "i_orgb"           =>(string)$result[$j]->c_lokasi_unitkerja,
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
	
	public function getPindahanInvListD($nopeng) {
       $status='T';
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT c_barang,d_anggaran,to_char(no_aset,'09999') as no_aset,d_perolehan,i_ruang
		 ,e_keterangan, merk_type,ur_sskel 
		 FROM e_ast_ajuanpindahinv_item_tm a ,
		      e_sabm_t_master_tm c,e_ast_sskel_0_tr d
		 where  a.i_aset_awal =  c.no_aset  and a.i_barang_ajuanpindah = ?
		   and  a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
								      and substr(c.kd_brg,2,2) = d.kd_bid 
								   	  and substr(c.kd_brg,4,2) = d.kd_kel
									  and substr(c.kd_brg,6,2) = d.kd_skel
									  and substr(c.kd_brg,8,3) = d.kd_sskel",$nopeng);
		
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_barang"                   =>(string)$result[$j]->c_barang,
								   "d_anggaran"                 =>(string)$result[$j]->d_anggaran,
								   "no_aset"                    =>(string)$result[$j]->no_aset,
								   "d_perolehan"                =>(string)$result[$j]->d_perolehan,
								   "i_ruang"                    =>(string)$result[$j]->i_ruang,
								   "e_keterangan"               =>(string)$result[$j]->e_keterangan,
								   "merk_type"                  =>(string)$result[$j]->merk_type,
								   "ur_sskel"                   =>(string)$result[$j]->ur_sskel);
								  
								  
			  			       
		
		 }
        }	
        		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
public function getjabatan($unitkr) {
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try { 
           $where[] = $unitkr;
		   $where[] = $unitkr;
		   $db->setFetchMode(Zend_Db::FETCH_OBJ);
           $hasilAkhir = $db->fetchOne("select count(*)  
										from sdm.tr_jabatan A,  sdm.tm_pegawai B
										where a.c_jabatan = b.c_jabatan
										and b.c_lokasi_unitkerja = ?",$where);  
       		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	} 		
 public function queryPegLama($nopin) {
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
		 $sql="select a.i_barang_ajuanpindah,a.i_orgb_pemberi,b.n_unitkerja,c.n_peg,b.c_lokasi_unitkerja
		       from aset.tm_ajuanpindahinv_0 a,
			   sdm.tr_unitkerja b,
			   sdm.tm_pegawai c
			   where a.i_barang_ajuanpindah='$nopin'
			   and a.i_orgb_pemberi=c.i_peg_nip
			   and c.c_lokasi_unitkerja=b.c_lokasi_unitkerja
			   and c.c_eselon_i=b.c_eselon_i
			   and c.c_eselon_ii=b.c_eselon_ii
			   and c.c_eselon_iii=b.c_eselon_iii
			   and c.c_eselon_iv=b.c_eselon_iv
			   and c.c_eselon_v=b.c_eselon_v
			   ";
			 // echo $sql;
			$result=$db->fetchAll($sql);
         // $result = $db->fetchAll("select distinct a.i_barang_ajuanpindah, a.i_orgb_pemberi, B.n_unitkerja, C.nip_pjrug, d.n_peg
								// from aset.tm_ajuanpindahinv_0 A,
								// sdm.tr_unitkerja B,
								// aset.tm_ruang C,
								// sdm.tm_pegawai D
								// where A.i_orgb_pemberi = B.c_satker
								// and A.i_orgb_pemberi = C.nip_pjrug
								// and C.nip_pjrug = D.i_peg_nip
								// and a.i_barang_ajuanpindah = ?",$nopin); 
		  $jmlResult = count($result);
		  
		  if($jmlResult > 0){
		  for ($j = 0; $j < $jmlResult; $j++) {
		   
           $hasilAkhir[$j] = array("i_barang_ajuanpindah"           =>(string)$result[$j]->i_barang_serah,
								   "i_orgb_pemberi"           =>(string)$result[$j]->i_orgb_pemberi,
								   "n_unitkerja"           =>(string)$result[$j]->n_unitkerja,
								   "c_lokasi_unitkerja"           =>(string)$result[$j]->c_lokasi_unitkerja,
								   "n_peg"           =>(string)$result[$j]->n_peg);
								  
								  
							       
		
		      }
          	
	     
		}else{
				return 0;
		} 						
         return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	} 
	public function queryPegbaru($nopin) {
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
		 $sql="select a.i_barang_ajuanpindah,a.i_orgb_penerima,b.c_lokasi_unitkerja,b.n_unitkerja,c.n_peg
		       from aset.tm_ajuanpindahinv_0 a,
			   sdm.tr_unitkerja b,
			   sdm.tm_pegawai c
			   where a.i_barang_ajuanpindah='$nopin'
			   and a.i_orgb_penerima=c.i_peg_nip
			   and c.c_lokasi_unitkerja=b.c_lokasi_unitkerja
			   and c.c_eselon_i=b.c_eselon_i
			   and c.c_eselon_ii=b.c_eselon_ii
			   and c.c_eselon_iii=b.c_eselon_iii
			   and c.c_eselon_iv=b.c_eselon_iv
			   and c.c_eselon_v=b.c_eselon_v			 
		      ";
			 $result 	= $db->fetchAll($sql);
         // $result 	= $db->fetchAll("select distinct a.i_barang_ajuanpindah, a.i_orgb_penerima, B.n_unitkerja, C.nip_pjrug, d.n_peg
								// from aset.tm_ajuanpindahinv_0 A,
								// sdm.tr_unitkerja B,
								// aset.tm_ruang C,
								// sdm.tm_pegawai D
								// where A.i_orgb_penerima = B.c_satker
								// and A.i_orgb_penerima = C.nip_pjrug
								// and C.nip_pjrug = D.i_peg_nip
								// and a.i_barang_ajuanpindah = ?",$nopin); 
		  $jmlResult = count($result);
		 
		  if($jmlResult > 0){
		   for ($j = 0; $j < $jmlResult; $j++) {
		     
              $hasilAkhir[$j] = array("i_barang_ajuanpindah"           =>(string)$result[$j]->i_barang_ajuanpindah,
								   "i_orgb_penerima"                =>(string)$result[$j]->i_orgb_penerima,
								   "n_unitkerja"                         =>(string)$result[$j]->n_unitkerja,
								   "c_lokasi_unitkerja"                      =>(string)$result[$j]->c_lokasi_unitkerja,
								   "n_peg"                          =>(string)$result[$j]->n_peg);
								  
								  
							       
		
		      }
          	
	     
		}else{
				return 0;
		} 					
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
         //$TU 	= $db->fetchCol("SELECT i_orgb_tu FROM e_org_0_0_tm where i_orgb = ? and i_orgb_tu is not null",$unitkr); 
         $TU 	= $db->fetchCol("SELECT c_dept FROM sdm.tr_unitkerja where c_satker = ? and c_dept is not null",$unitkr); 
        return $TU;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

   
	public function getPengembalianPindahanD($nopeng,$thnang) {
       $status='T';
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT c_barang,d_anggaran,to_char(no_aset,'09999') as no_aset,d_perolehan,i_ruang,
								i_ruang_baru, e_keterangan, merk_type,ur_sskel 
								FROM aset.tm_ajuanpindahinv_item a ,aset.tm_serahpindahinv_0 b,
								aset.tm_masterhm c,aset.tm_sskel d
								where  a.i_aset_awal =  c.no_aset  and b.i_barang_serah = ?
								and a.i_barang_ajuanpindah = b.i_barang_ajuanpindah
								and  a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
								and substr(c.kd_brg,2,2) = d.kd_bid 
								and substr(c.kd_brg,4,2) = d.kd_kel
								and substr(c.kd_brg,6,2) = d.kd_skel
								and substr(c.kd_brg,8,3) = d.kd_sskel
								and c.thn_ang='$thnang'",$nopeng);
		
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_barang"                   =>(string)$result[$j]->c_barang,
								   "d_anggaran"                 =>(string)$result[$j]->d_anggaran,
								   "no_aset"                    =>(string)$result[$j]->no_aset,
								   "d_perolehan"                =>(string)$result[$j]->d_perolehan,
								   "i_ruang"                    =>(string)$result[$j]->i_ruang,
								   "i_ruang_baru"                    =>(string)$result[$j]->i_ruang_baru,
								   "e_keterangan"               =>(string)$result[$j]->e_keterangan,
								   "merk_type"                  =>(string)$result[$j]->merk_type,
								   "ur_sskel"                   =>(string)$result[$j]->ur_sskel);
								  
								  
			  			       
		
		 }
        }	
        		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
  
	public function getSerahTrmPindah_SubBagPerlengkapan($pageNumber,$itemPerPage,$unitkr) {
		$status='B';
	    $status2='Y';
	    $status3='Y';
		$unitkr1=$unitkr;
		$unitkr2=$unitkr;
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {		 
		 $where[] = $status;
		 $where[] = $status2;
		 $where[] = $status3;
		 $where[] = $unitkr1;
		 $where[] = $unitkr2;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
		    $sql="select  count(distinct(a.i_barang_ajuanpindah)) 
					FROM aset.tm_ajuanpindahinv_0 a , sdm.tr_unitkerja b, aset.tm_ajuanpindahinv_item c,sdm.tm_pegawai d
					where c_barang_statuspindah='$status'
					and c_setuju_statustu = '$status2' and c_setuju_statuskabag ='$status3' 
					and (c.c_barang_serah is null or c.c_barang_serah ='')
					and a.i_orgb_penerima = d.i_peg_nip 
					and d.c_eselon_i = b.c_eselon_i 
					and d.c_eselon_ii = b.c_eselon_ii
					and d.c_eselon_iii = b.c_eselon_iii
					and d.c_eselon_iv = b.c_eselon_iv 
					and d.c_eselon_v = b.c_eselon_v
					and b.c_satker='$unitkr' 
					and a.i_barang_ajuanpindah = c.i_barang_ajuanpindah";
			$hasilAkhir = $db->fetchOne($sql);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 $sql="SELECT distinct i_orgb_penerima,a.i_barang_ajuanpindah,d_barang_ajuanpindah ,b.n_unitkerja as n_orgp, 
					i_orgb_pemberi, d.n_unitkerja as n_orgb_pemberi
					FROM aset.tm_ajuanpindahinv_0 a , sdm.tr_unitkerja b, 
					aset.tm_ajuanpindahinv_item c, sdm.tr_unitkerja d,sdm.tm_pegawai e,sdm.tm_pegawai f
					where c_barang_statuspindah='$status'
					and c_setuju_statustu = '$status2' and c_setuju_statuskabag ='$status3' 
					and (c.c_barang_serah is null or c.c_barang_serah ='')
					and a.i_orgb_penerima = e.i_peg_nip
					and e.c_eselon_i=b.c_eselon_i
					and e.c_eselon_ii=b.c_eselon_ii
					and e.c_eselon_iii=b.c_eselon_iii
					and e.c_eselon_iv=b.c_eselon_iv
					and e.c_eselon_v=b.c_eselon_v
					and b.c_satker='$unitkr'
					and a.i_orgb_pemberi = f.i_peg_nip
					and f.c_eselon_i=d.c_eselon_i
					and f.c_eselon_ii=d.c_eselon_ii
					and f.c_eselon_iii=d.c_eselon_iii
					and f.c_eselon_iv=d.c_eselon_iv
					and f.c_eselon_v=d.c_eselon_v									
					and d.c_satker='$unitkr'
					and a.i_barang_ajuanpindah = c.i_barang_ajuanpindah
					ORDER BY i_barang_ajuanpindah
					limit $xLimit offset $xOffset";
			 $result = $db->fetchAll($sql); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_orgb_penerima"       =>(string)$result[$j]->i_orgb_penerima,
					               "i_orgb_pemberi"           			=>(string)$result[$j]->i_orgb_pemberi,
								   "n_orgp"           			=>(string)$result[$j]->n_orgp,
								   "n_orgb_pemberi"           			=>(string)$result[$j]->n_orgb_pemberi,
								   "i_barang_ajuanpindah"      	=>(string)$result[$j]->i_barang_ajuanpindah,
								   "d_barang_ajuanpindah"      	=>(string)$result[$j]->d_barang_ajuanpindah);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
	
}
		
?>