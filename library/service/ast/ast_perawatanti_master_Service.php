<?php
class ast_perawatanti_master_service {
   
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
	 * fungsi untuk   e_ast_ajuanrawatti_master_tm
	 ***************************/
	 //================================================ 07 des 07 ==============================================================
	 public function getPengajuanPerawatanTIList($pageNumber,$itemPerPage,$unitkr) {
		$status='A';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		 $where[]=$status;
		 $where[]=$unitkr;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_ajuanrawatti_master_tm
									where c_status_entry=? and i_orgb =? ",$where);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT i_barang_ajuanrawat,d_barang_ajuanrawat ,i_peg_nippemohon ,
										d_anggaran ,c_barang ,i_aset ,d_perolehan ,c_barang_perbaikan 
										FROM e_ast_ajuanrawatti_master_tm a 
										where c_status_entry=? and i_orgb =?
										ORDER BY i_barang_ajuanrawat
										limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_barang_ajuanrawat"       =>(string)$result[$j]->i_barang_ajuanrawat,
										   "d_barang_ajuanrawat"    =>(string)$result[$j]->d_barang_ajuanrawat,
										   "i_peg_nippemohon"      	=>(string)$result[$j]->i_peg_nippemohon,
										   "d_anggaran"      		=>(string)$result[$j]->d_anggaran,
										   "c_barang"      			=>(string)$result[$j]->c_barang,
										   "i_aset"      			=>(string)$result[$j]->i_aset,
										   "d_perolehan"      		=>(string)$result[$j]->d_perolehan,
										   "c_barang_perbaikan"     =>(string)$result[$j]->c_barang_perbaikan);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
	
	
	public function insertPengajuanPerawatanAsetTIM(array $data) {
		
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	  
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("i_barang_ajuanrawat"      	=>$data['noAjuan'],
	                           "d_barang_ajuanrawat"    	=>date("Y-m-d"),
						       "i_peg_nippemohon"  			=>$data['nipPemberi'],
							   "i_orgb"  			        =>$data['org'],
						       "d_anggaran" 				=>$data['thnang'],
							   "c_barang" 					=>$data['kdbrg'],
							   "i_aset" 					=>$data['noaset'],
							   "d_perolehan" 				=>$data['tglPerl'],
						       "c_barang_perbaikan"   		=>$data['kdperb'],
							   "c_status_entry"   			=>'A',
							   "i_entry"       				=>$data['userid'], 
						       "d_entry"       				=>date("Y-m-d"));
	    
		
 		 $db->insert('e_ast_ajuanrawatti_master_tm',$atk_mast_prm);
		 $db->commit();
	     return 'sukses <br>';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	//add === 05 06 08
	public function deletAjuanRawatM($noPindah) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 
	  	 $where[] = "i_barang_ajuanrawat  =  '".trim($noPindah)."'";
	     
		 $db->delete('e_ast_ajuanrawatti_master_tm', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'delete gagal';
	   }
	}
	
	//
	 public function getSWTelahInstallList2($nipPemohon) {
	 echo '$nipPemohon'.$nipPemohon;
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $nipPemohon;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('Select a.thn_ang, a.kd_brg, a.no_aset, a.tgl_perlh, i_komputer_macaddress, 
									i_peg_nip, c_unit_kerja, i_ruang, i_sw, n_sw_installer 
									from E_ast_komputer_0_tr a, e_ast_distribusi_software_tm  b
									Where a.thn_ang = b.thn_ang 
											and a.kd_brg = a.kd_brg
											and a.no_aset = a.no_Aset
											and a.tgl_perlh= a.tgl_perlh
											and i_peg_nip=?',$where);
								
         $jmlResult = count($result);
		 //echo '$jmlResult '.$jmlResult ;
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("thn_ang"           		=>(string)$result[$j]->thn_ang,
								   "kd_brg"           		=>(string)$result[$j]->kd_brg,
								   "no_aset"      			=>(string)$result[$j]->no_aset,
								   "tgl_perlh"      		=>(string)$result[$j]->tgl_perlh,
								   "i_komputer_macaddress"  =>(string)$result[$j]->i_komputer_macaddress,
								   "i_peg_nip"      		=>(string)$result[$j]->i_peg_nip,
								   "c_unit_kerja"      		=>(string)$result[$j]->c_unit_kerja,
								   "i_ruang"      			=>(string)$result[$j]->i_ruang,
								   "i_sw"      				=>(string)$result[$j]->i_sw,
								   "n_sw_installer"      	=>(string)$result[$j]->n_sw_installer);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	public function getPengajuanPerawatanTI($noAjuan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		 $where[]=$noAjuan;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 
			 $result = $db->fetchAll("SELECT i_barang_ajuanrawat,d_barang_ajuanrawat ,i_peg_nippemohon ,
										d_anggaran ,c_barang ,to_char(i_aset,'09999') as no_aset ,d_perolehan ,c_barang_perbaikan ,
										n_peg,n_jabatan,b.i_orgb
										FROM e_ast_ajuanrawatti_master_tm a ,e_sdm_pegawai_0_tm b
										where a.i_peg_nippemohon = b.i_peg_nip
										and i_barang_ajuanrawat=? 
										ORDER BY i_barang_ajuanrawat",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_barang_ajuanrawat"       =>(string)$result[$j]->i_barang_ajuanrawat,
										   "d_barang_ajuanrawat"    =>(string)$result[$j]->d_barang_ajuanrawat,
										   "i_peg_nippemohon"      	=>(string)$result[$j]->i_peg_nippemohon,
										   "d_anggaran"      		=>(string)$result[$j]->d_anggaran,
										   "c_barang"      			=>(string)$result[$j]->c_barang,
										   "i_aset"      			=>(string)$result[$j]->no_aset,
										   "d_perolehan"      		=>(string)$result[$j]->d_perolehan,
										   "n_peg"      			=>(string)$result[$j]->n_peg,
										   "i_orgb"      			=>(string)$result[$j]->i_orgb,
										   "n_jabatan"      		=>(string)$result[$j]->n_jabatan,
										   "c_barang_perbaikan"     =>(string)$result[$j]->c_barang_perbaikan);
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function updateAjuanRwtpcM($noAjuan) {
	//echo '$noPindah'.$noPindah;
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->beginTransaction();
	     $atk_mast_prm = array("c_status_entry"  				=>'B',
						       "i_entry"       		    		=>"ast",
						       "d_entry"       		    		=>date("Y-m-d"));
	     
		 $where[] = "i_barang_ajuanrawat = '". $noAjuan ."'";
	     $db->update('e_ast_ajuanrawatti_master_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses <br>';
		 		 
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	
	}
	
	 public function getPersetujuanPerawatanTIList($pageNumber,$itemPerPage) {
		$status='B';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		 $where[]=$status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_ajuanrawatti_master_tm
									where c_status_entry=? ",$where);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT i_barang_ajuanrawat,d_barang_ajuanrawat ,i_peg_nippemohon ,
										d_anggaran ,c_barang ,i_aset ,d_perolehan ,c_barang_perbaikan,n_peg,b.i_orgb
										FROM e_ast_ajuanrawatti_master_tm a , e_sdm_pegawai_0_tm b
										where c_status_entry=? and a.i_peg_nippemohon=b.i_peg_nip
										ORDER BY i_barang_ajuanrawat
										limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_barang_ajuanrawat"       =>(string)$result[$j]->i_barang_ajuanrawat,
										   "d_barang_ajuanrawat"    =>(string)$result[$j]->d_barang_ajuanrawat,
										   "i_peg_nippemohon"      	=>(string)$result[$j]->i_peg_nippemohon,
										   "n_peg"      			=>(string)$result[$j]->n_peg,
										   "i_orgb"      			=>(string)$result[$j]->i_orgb,
										   "d_anggaran"      		=>(string)$result[$j]->d_anggaran,
										   "c_barang"      			=>(string)$result[$j]->c_barang,
										   "i_aset"      			=>(string)$result[$j]->i_aset,
										   "d_perolehan"      		=>(string)$result[$j]->d_perolehan,
										   "c_barang_perbaikan"     =>(string)$result[$j]->c_barang_perbaikan);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function getRefInvKompList($pageNumber,$itemPerPage,$unitkr) {
       
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) from e_ast_komputer_0_tr a,e_ast_sskel_0_tr c
								  	        Where substr(a.c_barang,1,1) = c.kd_gol				 
											and substr(a.c_barang,2,2) = c.kd_bid 
											and substr(a.c_barang,4,2) = c.kd_kel
											and substr(a.c_barang,6,2) = c.kd_skel
											and substr(a.c_barang,8,3) = c.kd_sskel
											and c_unit_kerja = ?",$unitkr);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		 $result = $db->fetchAll("Select d_anggaran, c_barang, d_perolehan, to_char(i_aset,'09999') as no_aset,ur_sskel,
									i_komputer_macaddress,c_unit_kerja,i_ruang
									from e_ast_komputer_0_tr a,e_ast_sskel_0_tr c
									Where substr(a.c_barang,1,1) = c.kd_gol				 
											and substr(a.c_barang,2,2) = c.kd_bid 
											and substr(a.c_barang,4,2) = c.kd_kel
											and substr(a.c_barang,6,2) = c.kd_skel
											and substr(a.c_barang,8,3) = c.kd_sskel
											and c_unit_kerja = ?
 									        ORDER BY d_anggaran
											limit $xLimit offset $xOffset",$unitkr);
									
	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("thn_ang"           			=>(string)$result[$j]->d_anggaran,
									"kd_brg"           			=>(string)$result[$j]->c_barang,
									"no_aset"           		=>(string)$result[$j]->no_aset,
									"tgl_perlh"           		=>(string)$result[$j]->d_perolehan,
									"ur_sskel"   				=>(string)$result[$j]->ur_sskel,
									"c_unit_kerja"           	=>(string)$result[$j]->c_unit_kerja,
									"ur_sskel"           		=>(string)$result[$j]->ur_sskel,
									"i_ruang"           		=>(string)$result[$j]->i_ruang);
								  
		 }
        }
       }		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
    	public function getSetujuRawatSIList($pageNumber,$itemPerPage) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$query= "select count(*) FROM e_ast_ajuanrawatti_master_tm where c_setuju_statussi ='' or c_setuju_statussi is null ";
			if(($pageNumber==0) && ($itemPerPage==0)){
				$hasilAkhir = $db->fetchOne($query);
			}else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				$query= "SELECT i_barang_ajuanrawat,d_barang_ajuanrawat,i_peg_nippemohon,d_anggaran,c_barang,i_aset,d_perolehan,c_barang_perbaikan ".
					" FROM e_ast_ajuanrawatti_master_tm a where  c_setuju_statussi ='' or c_setuju_statussi is null  ".
					" ORDER BY i_barang_ajuanrawat,d_barang_ajuanrawat limit $xLimit offset $xOffset";
				$result = $db->fetchAll($query); 
				$jmlResult = count($result);
				for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_barang_ajuanrawat"	=>(string)$result[$j]->i_barang_ajuanrawat,
							   "d_barang_ajuanrawat"    =>(string)$result[$j]->d_barang_ajuanrawat,
							   "i_peg_nippemohon"      	=>(string)$result[$j]->i_peg_nippemohon,
							   "d_anggaran"      		=>(string)$result[$j]->d_anggaran,
							   "c_barang"      			=>(string)$result[$j]->c_barang,
							   "i_aset"      			=>(string)$result[$j]->i_aset,
							   "d_perolehan"      		=>(string)$result[$j]->d_perolehan,
							   "c_barang_perbaikan"     =>(string)$result[$j]->c_barang_perbaikan
							   );
				}	
			}	 
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}	 

	public function UpdRawatSetujuSI($nopeng,$stspersetujuan,$alasan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');		
		try {
		$db->beginTransaction();
		$ast_ajuan_prm = array("c_setuju_statussi"=>$stspersetujuan,
                                     "e_alasan"=>$alasan,
				          "d_setuju_si"=>date("Y-m-d"));
		 	 			
		$where[] = "i_barang_ajuanrawat = '".trim($nopeng)."'";
		$db->update("e_ast_ajuanrawatti_master_tm",$ast_ajuan_prm, $where);
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

	public function getInfoTidakSetujuList($pageNumber,$itemPerPage) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$query= "select count(*) FROM e_ast_ajuanrawatti_master_tm where (c_setuju_statustu ='T' or c_setuju_statussi ='T' )";
			if(($pageNumber==0) && ($itemPerPage==0)){
				$hasilAkhir = $db->fetchOne($query);
			}else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				$query= "SELECT i_barang_ajuanrawat,d_barang_ajuanrawat,i_peg_nippemohon,d_anggaran,c_barang,i_aset,d_perolehan,c_barang_perbaikan ".
					" FROM e_ast_ajuanrawatti_master_tm a where (c_setuju_statustu ='T' or c_setuju_statussi ='T' ) ".
					" ORDER BY i_barang_ajuanrawat limit $xLimit offset $xOffset";
				$result = $db->fetchAll($query); 
				$jmlResult = count($result);
				for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_barang_ajuanrawat"	=>(string)$result[$j]->i_barang_ajuanrawat,
							   "d_barang_ajuanrawat"    =>(string)$result[$j]->d_barang_ajuanrawat,
							   "i_peg_nippemohon"      	=>(string)$result[$j]->i_peg_nippemohon,
							   "d_anggaran"      		=>(string)$result[$j]->d_anggaran,
							   "c_barang"      			=>(string)$result[$j]->c_barang,
							   "i_aset"      			=>(string)$result[$j]->i_aset,
							   "d_perolehan"      		=>(string)$result[$j]->d_perolehan,
							   "c_barang_perbaikan"     =>(string)$result[$j]->c_barang_perbaikan
							   );
				}	
			}	 
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
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
	public function getRawatTIByNOAjuList($nopeng) {  
            
              $nopeng = trim($nopeng);
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			$result = $db->fetchAll("SELECT i_barang_ajuanrawat,i_sw,c_status_proses,c_status_rawat  
					 from e_ast_ajuanrawatti_item_tm where trim(i_barang_ajuanrawat) =? order by 2",trim($nopeng));
			$jmlResult = count($result);
                     
			if($jmlResult > 0){
	 			for ($j = 0; $j < $jmlResult; $j++) {
                                  
					$hasilAkhir[$j] = array("i_barang_ajuanrawat"=>(string)$result[$j]->i_barang_ajuanrawat
					,"d_barang_ajuanrawat"=>(string)$result[$j]->d_barang_ajuanrawat
					,"i_sw"=>(string)$result[$j]->i_sw
					,"c_status_proses"=>(string)$result[$j]->c_status_proses
					,"c_status_rawat"=>(string)$result[$j]->c_status_rawat
						);
	 			}
			}
			return $hasilAkhir;
		}
		catch (Exception $e){
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
	public function getInfoPengerjaanList($pageNumber,$itemPerPage) {
	 $registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$query= "select count(*) FROM e_ast_ajuanrawatti_master_tm a,e_sdm_pegawai_0_tm b,e_org_0_0_tm c   
			         where i_peg_nippemohon = i_peg_nip and b.i_orgb = c.i_orgb and c_setuju_statussi ='Y'
					 and i_peg_niprawat is null";
			if(($pageNumber==0) && ($itemPerPage==0)){
				$hasilAkhir = $db->fetchOne($query);
			}else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				$query= "select i_barang_ajuanrawat,d_barang_ajuanrawat,i_peg_nippemohon,i_orgb
							FROM e_ast_ajuanrawatti_master_tm a   
							where  c_setuju_statussi = 'Y'  and i_peg_niprawat is null 
							ORDER BY i_barang_ajuanrawat,d_barang_ajuanrawat 
							limit $xLimit offset $xOffset";
				$result = $db->fetchAll($query); 
				$jmlResult = count($result);
				for ($j = 0; $j < $jmlResult; $j++) {
					$i_peg_nippemohon	= (string)$result[$j]->i_peg_nippemohon;	
					
						$n_peg 	= $db->fetchCol("select n_peg
													FROM e_sdm_pegawai_0_tm A, e_sdm_jabatan_0_tm B 
													where 
													a.i_peg_nip = b.i_peg_nip
													and a.i_peg_nip =?",$result[$j]->i_peg_nippemohon);
						
						$n_peg2 	= $db->fetchCol("select n_peg
													from  e_sdm_pegawai_0_tm A 
													where  
													not EXISTS(select * from  e_sdm_jabatan_0_tm B
													         where a.i_peg_nip = b.i_peg_nip)
														and a.i_peg_nip = ?",$result[$j]->i_peg_nippemohon);
													
						$n_orgb 	= $db->fetchCol("select n_orgb
													FROM e_org_0_0_tm 
													where 	i_orgb=?		",$result[$j]->i_orgb);
													
						
						if ($n_peg[0] != ''){
							$hasilAkhir[$j] = array("i_barang_ajuanrawat"	=>(string)$result[$j]->i_barang_ajuanrawat,
												   "d_barang_ajuanrawat"    =>(string)$result[$j]->d_barang_ajuanrawat,
												   "i_peg_nippemohon"      	=>(string)$result[$j]->i_peg_nippemohon,
												   "n_peg"      			=>$n_peg[0],
												   "i_orgb"      			=>(string)$result[$j]->i_orgb,
												   "n_orgb"     			=>$n_orgb[0]);
						}else{
							$hasilAkhir[$j] = array("i_barang_ajuanrawat"	=>(string)$result[$j]->i_barang_ajuanrawat,
												   "d_barang_ajuanrawat"    =>(string)$result[$j]->d_barang_ajuanrawat,
												   "i_peg_nippemohon"      	=>(string)$result[$j]->i_peg_nippemohon,
												   "n_peg"      			=>$n_peg2[0],
												   "i_orgb"      			=>(string)$result[$j]->i_orgb,
												   "n_orgb"     			=>$n_orgb[0]);
						}
						
/*						
						$hasilAkhir[$j] = array("i_barang_ajuanrawat"		=>(string)$result[$j]->i_barang_ajuanrawat,
												   "d_barang_ajuanrawat"    =>(string)$result[$j]->d_barang_ajuanrawat,
												   "i_peg_nippemohon"      	=>(string)$result[$j]->i_peg_nippemohon,
												   "n_peg"      			=>$n_peg[0],
												   "i_orgb"      			=>(string)$result[$j]->i_orgb,
												   "n_orgb"     			=>$n_orgb[0]);
												   */
				}	
			}
             			
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	public function getInfoPengerjaanList_Old($pageNumber,$itemPerPage) {
	 $registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$query= "select count(*) FROM e_ast_ajuanrawatti_master_tm a,e_sdm_pegawai_0_tm b,e_org_0_0_tm c   
			         where i_peg_nippemohon = i_peg_nip and b.i_orgb = c.i_orgb and c_setuju_statussi ='Y'
					 and i_peg_niprawat is null";
			if(($pageNumber==0) && ($itemPerPage==0)){
				$hasilAkhir = $db->fetchOne($query);
			}else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				$query= "select i_barang_ajuanrawat,d_barang_ajuanrawat,i_peg_nippemohon,n_peg,b.i_orgb,n_orgb FROM e_ast_ajuanrawatti_master_tm a,e_sdm_pegawai_0_tm b,e_org_0_0_tm c   
						 where i_peg_nippemohon = i_peg_nip and b.i_orgb = c.i_orgb and c_setuju_statussi = 'Y' 
					     and i_peg_niprawat is null ORDER BY i_barang_ajuanrawat,d_barang_ajuanrawat limit $xLimit offset $xOffset";
				$result = $db->fetchAll($query); 
				$jmlResult = count($result);
				for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_barang_ajuanrawat"	=>(string)$result[$j]->i_barang_ajuanrawat,
							   "d_barang_ajuanrawat"    =>(string)$result[$j]->d_barang_ajuanrawat,
							   "i_peg_nippemohon"      	=>(string)$result[$j]->i_peg_nippemohon,
							   "n_peg"      		=>(string)$result[$j]->n_peg,
							   "i_orgb"      			=>(string)$result[$j]->i_orgb,
							   "n_orgb"     =>(string)$result[$j]->n_orgb
							   );
				}	
			}
             			
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	public function queryAjuanRwtpcM($nopeng) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
				 
			$result = $db->fetchAll("SELECT i_barang_ajuanrawat,d_barang_ajuanrawat,i_peg_nippemohon,d_anggaran,c_barang,to_char(i_aset,'0000') as i_aset,d_perolehan,c_barang_perbaikan  
					                FROM e_ast_ajuanrawatti_master_tm a where  i_barang_ajuanrawat=?",$nopeng);
			$jmlResult = count($result);
				for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_barang_ajuanrawat"	=>(string)$result[$j]->i_barang_ajuanrawat,
							   "d_barang_ajuanrawat"    =>(string)$result[$j]->d_barang_ajuanrawat,
							   "i_peg_nippemohon"      	=>(string)$result[$j]->i_peg_nippemohon,
							   "d_anggaran"      		=>(string)$result[$j]->d_anggaran,
							   "c_barang"      			=>(string)$result[$j]->c_barang,
							   "i_aset"      			=>(string)$result[$j]->i_aset,
							   "d_perolehan"      		=>(string)$result[$j]->d_perolehan,
							   "c_barang_perbaikan"     =>(string)$result[$j]->c_barang_perbaikan
							   );
				}	
			 	 
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	
	
	public function insertDataDistribusi(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("thn_ang"        =>$data['thn_ang'],
	                           "kd_brg"   		=>$data['kd_brg'],
						       "no_aset"  		=>$data['no_aset'],
							   "tgl_perlh"  	=>$data['tgl_perlh'],
							   "i_sw"  			=>$data['i_sw'],
							   "n_sw_installer"  	=>' ',
						       "i_entry"       		=>$data['userid'],
						       "d_entry"       		=>date("Y-m-d"));
	    
		 $where[] = $data['i_sw'];
	     $where[] = $data['thn_ang'];
		 $where[] = $data['kd_brg'];
		 $where[] = $data['no_aset'];
		 
		$result = $db->fetchAll('SELECT thn_ang,kd_brg,no_aset 
								 FROM e_ast_distribusi_software_tm where i_sw = ? 
									and thn_ang = ? and kd_brg = ? and no_aset = ?',$where);
		 $jmlResult = count($result);
		 		 
		 if($jmlResult > 0){
			echo 'Data Sudah Ada..';
        }else{
			$db->insert('e_ast_distribusi_software_tm',$atk_mast_prm);
			$db->commit();
		 }
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'insert gagal';
	   }
	}
	public function deleteDataDistribusiSW(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 
	  	 $where[] = "i_sw  =  '".trim($data['i_sw'])."'";
	     $where[] = "thn_ang        =  '".trim($data['thn_ang'])."'";
		 $where[] = "kd_brg        =  '".trim($data['kd_brg'])."'";
		 $where[] = "no_aset        =  '".trim($data['no_aset'])."'";
		 
		 $db->delete('e_ast_distribusi_software_tm', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'delete gagal';
	   }
	}
	public function updateKerjaRwtpcM($data) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');		
		try {
		$db->beginTransaction();
		$ast_ajuan_prm = array("i_peg_niprawat"          =>$data['NipRawat'], 
							   "d_rawat_pengerjaan"      =>$data['tglRawat'],
                               "i_entry"=>$data['userid'],
				               "d_entry"=>date("Y-m-d"));
		 	 			
		$where[] = "i_barang_ajuanrawat = '".$data['noAjuan']."'";
		$db->update("e_ast_ajuanrawatti_master_tm",$ast_ajuan_prm, $where);
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
	//=================================== 12 Nop 07============================================================================= 
	//=================================== 12 Nop 07============================================================================= 
	//=================================== 12 Nop 07============================================================================= 
	
		
	
	
	
}		
?>