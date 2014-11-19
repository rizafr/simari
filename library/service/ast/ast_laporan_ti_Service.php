<?php

class ast_laporan_ti_Service {
    private static $instance;
   
    private function __construct() {
       
    }

    // The singleton method
    public static function getInstance() {
       if (!isset(self::$instance)) {
           $c = __CLASS__;
           self::$instance = new $c;
       }
       return self::$instance;
    }
	
	
	// ----------- get Laporan perbaikan Aset TI ===========================
	public function getLaporanPerbaikanTiList($pageNumber,$itemPerPage,$nip,$periode) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   $where[] = $nip;
	   $where[] = $periode;

	   try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasil = $db->fetchAll("select A.d_barang_ajuanbaik, A.e_barang_perbaikan, A.i_peg_nip, 
									    D.n_peg,  A.i_orgb, C.n_orgb, B.d_akhir_perbaikan
									    from e_ast_ajuanbaikti_0_tm A, e_ast_ajuanbaikti_item_tm B,
									    e_sdm_pegawai_0_tm D, e_org_0_0_tm C
									    where B.i_barang_ajuanbaik =  A.i_barang_ajuanbaik
									    and A.i_orgb = C.i_orgb
									    and A.i_peg_nip = D.i_peg_nip
									    and B.i_peg_helpdesk = ? 
									    and to_char(d_akhir_perbaikan,'YYYYMM') like ?
										", $where);
				$hasilAkhir = count($hasil);
			}else {
			    
				$xLimit=$itemPerPage;
			    $xOffset=($pageNumber-1)*$itemPerPage;
					$result = $db->fetchAll("select A.d_barang_ajuanbaik, A.e_barang_perbaikan, A.i_peg_nip, 
									    D.n_peg,  A.i_orgb, C.n_orgb, B.d_akhir_perbaikan
									    from e_ast_ajuanbaikti_0_tm A, e_ast_ajuanbaikti_item_tm B,
									    e_sdm_pegawai_0_tm D, e_org_0_0_tm C
									    where B.i_barang_ajuanbaik =  A.i_barang_ajuanbaik
									    and A.i_orgb = C.i_orgb
									    and A.i_peg_nip = D.i_peg_nip
									    and B.i_peg_helpdesk = ? 
									    and to_char(d_akhir_perbaikan,'YYYYMM') like ?
										limit $xLimit offset $xOffset", $where);
				
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
			 
					$hasilAkhir[$j] = array("d_barang_ajuanbaik"	=>(string)$result[$j]->d_barang_ajuanbaik,
											"e_barang_perbaikan"    =>(string)$result[$j]->e_barang_perbaikan, 
											"i_peg_nip"         	=>(string)$result[$j]->i_peg_nip, 
											"n_peg"          		=>(string)$result[$j]->n_peg,
											"i_orgb"         		=>(string)$result[$j]->i_orgb,
											"n_orgb"    			=>(string)$result[$j]->n_orgb,
											"d_akhir_perbaikan"    	=>(string)$result[$j]->d_akhir_perbaikan)
											; 
				}
			}					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	// ----------- get Laporan perbaikan Aset TI ==================Semua Data ditampilkan=========11 Juni 08 ===============================
	public function getLaporanPerbaikanTiListAll($pageNumber,$itemPerPage) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	  
	   try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasil = $db->fetchAll("select A.d_barang_ajuanbaik, A.e_barang_perbaikan, A.i_peg_nip, 
									    A.i_orgb, B.d_akhir_perbaikan
									    from e_ast_ajuanbaikti_0_tm A, e_ast_ajuanbaikti_item_tm B									    
										where B.i_barang_ajuanbaik =  A.i_barang_ajuanbaik");
				$hasilAkhir = count($hasil);
			}else {
			    
				$xLimit=$itemPerPage;
			    $xOffset=($pageNumber-1)*$itemPerPage;
					$result = $db->fetchAll("select A.d_barang_ajuanbaik, A.e_barang_perbaikan, A.i_peg_nip, 
									    A.i_orgb, B.d_akhir_perbaikan,i_peg_helpdesk
									    from e_ast_ajuanbaikti_0_tm A, e_ast_ajuanbaikti_item_tm B									    
										where B.i_barang_ajuanbaik =  A.i_barang_ajuanbaik
										limit $xLimit offset $xOffset");
				
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
					$n_peg 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nip);	
					
					$n_peg_helpdesk 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_helpdesk);	
														
					$hasilAkhir[$j] 	= array("d_barang_ajuanbaik"	=>(string)$result[$j]->d_barang_ajuanbaik,
											"e_barang_perbaikan"    =>(string)$result[$j]->e_barang_perbaikan, 
											"i_peg_nip"         	=>(string)$result[$j]->i_peg_nip, 
											"n_peg"          		=>$n_peg[0],
											"i_orgb"         		=>(string)$result[$j]->i_orgb,
											"n_orgb"    			=>(string)$result[$j]->n_orgb,
											"d_akhir_perbaikan"    	=>(string)$result[$j]->d_akhir_perbaikan,
											"i_peg_helpdesk"		=>(string)$result[$j]->i_peg_helpdesk,
											"n_peg_helpdesk"        =>$n_peg_helpdesk[0])
											; 
				}
			}					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	// ----------- get Laporan perbaikan Aset TI ==================Ditampilkan By Periode=========11 Juni 08 ===============================
	public function getLaporanPerbaikanTiListByPeriode($pageNumber,$itemPerPage,$tglAwal,$tglAkhir) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	  
	   try {
			$where[] 	= $tglAwal;
			$where[] 	= $tglAkhir;
				
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasil = $db->fetchAll("select A.d_barang_ajuanbaik, A.e_barang_perbaikan, A.i_peg_nip, 
									    A.i_orgb, B.d_akhir_perbaikan
									    from e_ast_ajuanbaikti_0_tm A, e_ast_ajuanbaikti_item_tm B									    
										where B.i_barang_ajuanbaik =  A.i_barang_ajuanbaik
										and d_problem_awal between ? and ?
										",$where);
				$hasilAkhir = count($hasil);
			}else {
			    
				$xLimit=$itemPerPage;
			    $xOffset=($pageNumber-1)*$itemPerPage;
					$result = $db->fetchAll("select A.d_barang_ajuanbaik, A.e_barang_perbaikan, A.i_peg_nip, 
									    A.i_orgb, B.d_akhir_perbaikan,i_peg_helpdesk
									    from e_ast_ajuanbaikti_0_tm A, e_ast_ajuanbaikti_item_tm B									    
										where B.i_barang_ajuanbaik =  A.i_barang_ajuanbaik
										and d_problem_awal between ? and ?
										limit $xLimit offset $xOffset",$where);
				
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
					$n_peg 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nip);	
					
					$n_peg_helpdesk 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_helpdesk);	
														
					$hasilAkhir[$j] 	= array("d_barang_ajuanbaik"	=>(string)$result[$j]->d_barang_ajuanbaik,
												"e_barang_perbaikan"    =>(string)$result[$j]->e_barang_perbaikan, 
												"i_peg_nip"         	=>(string)$result[$j]->i_peg_nip, 
												"n_peg"          		=>$n_peg[0],
												"i_orgb"         		=>(string)$result[$j]->i_orgb,
												"n_orgb"    			=>(string)$result[$j]->n_orgb,
												"d_akhir_perbaikan"    	=>(string)$result[$j]->d_akhir_perbaikan,
												"i_peg_helpdesk"		=>(string)$result[$j]->i_peg_helpdesk,
												"n_peg_helpdesk"        =>$n_peg_helpdesk[0])
												; 
				}
			}					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	// ----------- get Laporan perbaikan Aset TI ==================Ditampilkan By NIP=========12 Juni 08 ===============================
	public function getLaporanPerbaikanTiListByNip($pageNumber,$itemPerPage,$nip) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	  
	   try {
			$where[] 	= $nip;
				
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasil = $db->fetchAll("select A.d_barang_ajuanbaik, A.e_barang_perbaikan, A.i_peg_nip, 
									    A.i_orgb, B.d_akhir_perbaikan
									    from e_ast_ajuanbaikti_0_tm A, e_ast_ajuanbaikti_item_tm B									    
										where B.i_barang_ajuanbaik =  A.i_barang_ajuanbaik
										and i_peg_helpdesk = ?
										",$where);
				$hasilAkhir = count($hasil);
			}else {
			    
				$xLimit=$itemPerPage;
			    $xOffset=($pageNumber-1)*$itemPerPage;
					$result = $db->fetchAll("select A.d_barang_ajuanbaik, A.e_barang_perbaikan, A.i_peg_nip, 
									    A.i_orgb, B.d_akhir_perbaikan,i_peg_helpdesk
									    from e_ast_ajuanbaikti_0_tm A, e_ast_ajuanbaikti_item_tm B									    
										where B.i_barang_ajuanbaik =  A.i_barang_ajuanbaik
										and i_peg_helpdesk = ?
										limit $xLimit offset $xOffset",$where);
				
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
					$n_peg 				= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nip);	
					
					$n_peg_helpdesk 	= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_helpdesk);	
														
					$hasilAkhir[$j] 	= array("d_barang_ajuanbaik"	=>(string)$result[$j]->d_barang_ajuanbaik,
												"e_barang_perbaikan"    =>(string)$result[$j]->e_barang_perbaikan, 
												"i_peg_nip"         	=>(string)$result[$j]->i_peg_nip, 
												"n_peg"          		=>$n_peg[0],
												"i_orgb"         		=>(string)$result[$j]->i_orgb,
												"n_orgb"    			=>(string)$result[$j]->n_orgb,
												"d_akhir_perbaikan"    	=>(string)$result[$j]->d_akhir_perbaikan,
												"i_peg_helpdesk"		=>(string)$result[$j]->i_peg_helpdesk,
												"n_peg_helpdesk"        =>$n_peg_helpdesk[0])
												; 
				}
			}					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	// ----------- get Laporan perbaikan Aset TI ==================Ditampilkan By Periode=========12 Juni 08 ===============================
	public function getLaporanPerbaikanTiListByPeriodeNip($pageNumber,$itemPerPage,$tglAwal,$tglAkhir,$nip) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	  
	   try {
			$where[] 	= $tglAwal;
			$where[] 	= $tglAkhir;
			$where[] 	= $nip;
				
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasil = $db->fetchAll("select A.d_barang_ajuanbaik, A.e_barang_perbaikan, A.i_peg_nip, 
									    A.i_orgb, B.d_akhir_perbaikan
									    from e_ast_ajuanbaikti_0_tm A, e_ast_ajuanbaikti_item_tm B									    
										where B.i_barang_ajuanbaik =  A.i_barang_ajuanbaik
										and d_problem_awal between ? and ?
										and i_peg_helpdesk = ?
										",$where);
				$hasilAkhir = count($hasil);
			}else {
			    
				$xLimit=$itemPerPage;
			    $xOffset=($pageNumber-1)*$itemPerPage;
					$result = $db->fetchAll("select A.d_barang_ajuanbaik, A.e_barang_perbaikan, A.i_peg_nip, 
									    A.i_orgb, B.d_akhir_perbaikan,i_peg_helpdesk
									    from e_ast_ajuanbaikti_0_tm A, e_ast_ajuanbaikti_item_tm B									    
										where B.i_barang_ajuanbaik =  A.i_barang_ajuanbaik
										and d_problem_awal between ? and ?
										and i_peg_helpdesk = ?
										limit $xLimit offset $xOffset",$where);
				
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
					$n_peg 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nip);	
					
					$n_peg_helpdesk 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_helpdesk);	
														
					$hasilAkhir[$j] 	= array("d_barang_ajuanbaik"	=>(string)$result[$j]->d_barang_ajuanbaik,
												"e_barang_perbaikan"    =>(string)$result[$j]->e_barang_perbaikan, 
												"i_peg_nip"         	=>(string)$result[$j]->i_peg_nip, 
												"n_peg"          		=>$n_peg[0],
												"i_orgb"         		=>(string)$result[$j]->i_orgb,
												"n_orgb"    			=>(string)$result[$j]->n_orgb,
												"d_akhir_perbaikan"    	=>(string)$result[$j]->d_akhir_perbaikan,
												"i_peg_helpdesk"		=>(string)$result[$j]->i_peg_helpdesk,
												"n_peg_helpdesk"        =>$n_peg_helpdesk[0])
												; 
				}
			}					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	// ----------- get Laporan perbaikan Aset TI ==================Ditampilkan By Periode=========12 Juni 08 ===============================
	public function getLaporanPerbaikanTiListCetakPeriode($tglAwal,$tglAkhir) {
	   
	   $nip   = '%'.strtoupper($nip).'%';
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	  
	   try {
			$where[] 	= $tglAwal;
			$where[] 	= $tglAkhir;
			//$where[] 	= $nip;
				
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
						    
				$xLimit=$itemPerPage;
			    $xOffset=($pageNumber-1)*$itemPerPage;
					$result = $db->fetchAll("select A.d_barang_ajuanbaik, A.e_barang_perbaikan, A.i_peg_nip, 
										    A.i_orgb, B.d_akhir_perbaikan,i_peg_helpdesk
										    from e_ast_ajuanbaikti_0_tm A, e_ast_ajuanbaikti_item_tm B									    
											where B.i_barang_ajuanbaik =  A.i_barang_ajuanbaik
											and d_problem_awal between ? and ?",$where);
				
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
					$n_peg 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nip);	
					
					$n_peg_helpdesk 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_helpdesk);	
														
					$hasilAkhir[$j] 	= array("d_barang_ajuanbaik"	=>(string)$result[$j]->d_barang_ajuanbaik,
												"e_barang_perbaikan"    =>(string)$result[$j]->e_barang_perbaikan, 
												"i_peg_nip"         	=>(string)$result[$j]->i_peg_nip, 
												"n_peg"          		=>$n_peg[0],
												"i_orgb"         		=>(string)$result[$j]->i_orgb,
												"n_orgb"    			=>(string)$result[$j]->n_orgb,
												"d_akhir_perbaikan"    	=>(string)$result[$j]->d_akhir_perbaikan,
												"i_peg_helpdesk"		=>(string)$result[$j]->i_peg_helpdesk,
												"n_peg_helpdesk"        =>$n_peg_helpdesk[0])
												; 
				}
							 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function getLaporanPerbaikanTiListCetak($tglAwal,$tglAkhir,$nip) {
	   
	   $nip   = '%'.strtoupper($nip).'%';
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	  
	   try {
			$where[] 	= $tglAwal;
			$where[] 	= $tglAkhir;
			$where[] 	= $nip;
				
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
						    
				$xLimit=$itemPerPage;
			    $xOffset=($pageNumber-1)*$itemPerPage;
					$result = $db->fetchAll("select A.d_barang_ajuanbaik, A.e_barang_perbaikan, A.i_peg_nip, 
										    A.i_orgb, B.d_akhir_perbaikan,i_peg_helpdesk
										    from e_ast_ajuanbaikti_0_tm A, e_ast_ajuanbaikti_item_tm B									    
											where B.i_barang_ajuanbaik =  A.i_barang_ajuanbaik
											and d_problem_awal between ? and ?
											and i_peg_helpdesk like ? ",$where);
				
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
					$n_peg 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nip);	
					
					$n_peg_helpdesk 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_helpdesk);	
														
					$hasilAkhir[$j] 	= array("d_barang_ajuanbaik"	=>(string)$result[$j]->d_barang_ajuanbaik,
												"e_barang_perbaikan"    =>(string)$result[$j]->e_barang_perbaikan, 
												"i_peg_nip"         	=>(string)$result[$j]->i_peg_nip, 
												"n_peg"          		=>$n_peg[0],
												"i_orgb"         		=>(string)$result[$j]->i_orgb,
												"n_orgb"    			=>(string)$result[$j]->n_orgb,
												"d_akhir_perbaikan"    	=>(string)$result[$j]->d_akhir_perbaikan,
												"i_peg_helpdesk"		=>(string)$result[$j]->i_peg_helpdesk,
												"n_peg_helpdesk"        =>$n_peg_helpdesk[0])
												; 
				}
							 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function getLaporanPerbaikanTiAllCetak() {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	  
	   try {
			$where[] 	= $tglAwal;
			$where[] 	= $tglAkhir;
			$where[] 	= $nip;
				
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
						    
				$xLimit=$itemPerPage;
			    $xOffset=($pageNumber-1)*$itemPerPage;
					$result = $db->fetchAll("select A.d_barang_ajuanbaik, A.e_barang_perbaikan, A.i_peg_nip, 
										    A.i_orgb, B.d_akhir_perbaikan,i_peg_helpdesk
										    from e_ast_ajuanbaikti_0_tm A, e_ast_ajuanbaikti_item_tm B									    
											where B.i_barang_ajuanbaik =  A.i_barang_ajuanbaik");
				
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
					$n_peg 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nip);	
					
					$n_peg_helpdesk 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_helpdesk);	
														
					$hasilAkhir[$j] 	= array("d_barang_ajuanbaik"	=>(string)$result[$j]->d_barang_ajuanbaik,
												"e_barang_perbaikan"    =>(string)$result[$j]->e_barang_perbaikan, 
												"i_peg_nip"         	=>(string)$result[$j]->i_peg_nip, 
												"n_peg"          		=>$n_peg[0],
												"i_orgb"         		=>(string)$result[$j]->i_orgb,
												"n_orgb"    			=>(string)$result[$j]->n_orgb,
												"d_akhir_perbaikan"    	=>(string)$result[$j]->d_akhir_perbaikan,
												"i_peg_helpdesk"		=>(string)$result[$j]->i_peg_helpdesk,
												"n_peg_helpdesk"        =>$n_peg_helpdesk[0])
												; 
				}
							 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function getLaporanPerbaikanTiCetak($nip,$periode) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   $where[] = $nip;
	   $where[] = $periode;

	   try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$result = $db->fetchAll("select A.d_barang_ajuanbaik, A.e_barang_perbaikan, A.i_peg_nip, 
								D.n_peg,  A.i_orgb, C.n_orgb, B.d_akhir_perbaikan
								from e_ast_ajuanbaikti_0_tm A, e_ast_ajuanbaikti_item_tm B,
								e_sdm_pegawai_0_tm D, e_org_0_0_tm C
								where B.i_barang_ajuanbaik =  A.i_barang_ajuanbaik
								and A.i_orgb = C.i_orgb
								and A.i_peg_nip = D.i_peg_nip
								and B.i_peg_helpdesk = ? 
								and to_char(d_akhir_perbaikan,'YYYYMM') = ?
								", $where);
		
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
			 
					$hasilAkhir[$j] = array("d_barang_ajuanbaik"	=>(string)$result[$j]->d_barang_ajuanbaik,
											"e_barang_perbaikan"    =>(string)$result[$j]->e_barang_perbaikan, 
											"i_peg_nip"         	=>(string)$result[$j]->i_peg_nip, 
											"n_peg"          		=>(string)$result[$j]->n_peg,
											"i_orgb"         		=>(string)$result[$j]->i_orgb,
											"n_orgb"    			=>(string)$result[$j]->n_orgb,
											"d_akhir_perbaikan"    	=>(string)$result[$j]->d_akhir_perbaikan)
											; 
				}
					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	// ----------- get Laporan PerawatanSoftware User  ===========================
	public function getLaporanPerawatanSofwareUser($pageNumber,$itemPerPage,$nip,$periode) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   $where[] = $nip;
	   $where[] = $periode;

	   try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasil = $db->fetchAll("select A.d_barang_ajuanrawat, A.i_barang_ajuanrawat,
									    A.i_peg_nippemohon, D.n_peg,  
									    A.i_orgb, C.n_orgb,A.d_anggaran, A.c_barang,
										to_char(A.i_aset,'09999') as i_aset,
									    E.n_sw, B.c_status_proses, A.d_rawat_pengerjaan
									    from e_ast_ajuanrawatti_master_tm A, e_ast_ajuanrawatti_item_tm B,
									    e_sdm_pegawai_0_tm D, e_org_0_0_tm C, e_ast_software_0_tr E
									    where B.i_barang_ajuanrawat =  A.i_barang_ajuanrawat
									    and A.i_orgb = C.i_orgb
									    and A.i_peg_nippemohon = D.i_peg_nip
									    and b.i_sw = E.i_sw
									    and A.i_peg_niprawat = ? 
									    and to_char(d_rawat_pengerjaan,'YYYYMM') = ?
										", $where);
				$hasilAkhir = count($hasil);
			}else {
			    
				$xLimit=$itemPerPage;
			    $xOffset=($pageNumber-1)*$itemPerPage;
			 
				$result = $db->fetchAll("select A.d_barang_ajuanrawat, A.i_barang_ajuanrawat,
									    A.i_peg_nippemohon, D.n_peg,  
									    A.i_orgb, C.n_orgb,A.d_anggaran, A.c_barang, 
										to_char(A.i_aset,'09999') as i_aset,
									    E.n_sw, B.c_status_proses, A.d_rawat_pengerjaan
									    from e_ast_ajuanrawatti_master_tm A, e_ast_ajuanrawatti_item_tm B,
									    e_sdm_pegawai_0_tm D, e_org_0_0_tm C, e_ast_software_0_tr E
									    where B.i_barang_ajuanrawat =  A.i_barang_ajuanrawat
									    and A.i_orgb = C.i_orgb
									    and A.i_peg_nippemohon = D.i_peg_nip
									    and b.i_sw = E.i_sw
									    and A.i_peg_niprawat = ? 
									    and to_char(d_rawat_pengerjaan,'YYYYMM') = ?
										limit $xLimit offset $xOffset", $where);
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
			 
					$hasilAkhir[$j] = array("d_barang_ajuanrawat"	=>(string)$result[$j]->d_barang_ajuanrawat,
											"i_barang_ajuanrawat"   =>(string)$result[$j]->i_barang_ajuanrawat, 
											"i_peg_nippemohon"      =>(string)$result[$j]->i_peg_nippemohon, 
											"n_peg"          		=>(string)$result[$j]->n_peg,
											"i_orgb"         		=>(string)$result[$j]->i_orgb,
											"n_orgb"    			=>(string)$result[$j]->n_orgb,
											"d_anggaran"    		=>(string)$result[$j]->d_anggaran,
											"c_barang"    			=>(string)$result[$j]->c_barang,
											"i_aset"    			=>(string)$result[$j]->i_aset,
											"n_sw"    				=>(string)$result[$j]->n_sw,
											"c_status_proses"    	=>(string)$result[$j]->c_status_proses,
											"d_rawat_pengerjaan"    =>(string)$result[$j]->d_rawat_pengerjaan
											)
											; 
				}
			}					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function getLaporanPerawatanTiCetak($nip,$periode) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   $where[] = $nip;
	   $where[] = $periode;

	   try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			 
				$result = $db->fetchAll("select A.d_barang_ajuanrawat, A.i_barang_ajuanrawat,
									    A.i_peg_nippemohon, D.n_peg,  
									    A.i_orgb, C.n_orgb,A.d_anggaran, A.c_barang, 
										to_char(A.i_aset,'09999') as i_aset,
									    E.n_sw, B.c_status_proses, A.d_rawat_pengerjaan
									    from e_ast_ajuanrawatti_master_tm A, e_ast_ajuanrawatti_item_tm B,
									    e_sdm_pegawai_0_tm D, e_org_0_0_tm C, e_ast_software_0_tr E
									    where B.i_barang_ajuanrawat =  A.i_barang_ajuanrawat
									    and A.i_orgb = C.i_orgb
									    and A.i_peg_nippemohon = D.i_peg_nip
									    and b.i_sw = E.i_sw
									    and A.i_peg_niprawat = ? 
									    and to_char(d_rawat_pengerjaan,'YYYYMM') = ?
										", $where);
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
			 
					$hasilAkhir[$j] = array("d_barang_ajuanrawat"	=>(string)$result[$j]->d_barang_ajuanrawat,
											"i_barang_ajuanrawat"   =>(string)$result[$j]->i_barang_ajuanrawat, 
											"i_peg_nippemohon"      =>(string)$result[$j]->i_peg_nippemohon, 
											"n_peg"          		=>(string)$result[$j]->n_peg,
											"i_orgb"         		=>(string)$result[$j]->i_orgb,
											"n_orgb"    			=>(string)$result[$j]->n_orgb,
											"d_anggaran"    		=>(string)$result[$j]->d_anggaran,
											"c_barang"    			=>(string)$result[$j]->c_barang,
											"i_aset"    			=>(string)$result[$j]->i_aset,
											"n_sw"    				=>(string)$result[$j]->n_sw,
											"c_status_proses"    	=>(string)$result[$j]->c_status_proses,
											"d_rawat_pengerjaan"    =>(string)$result[$j]->d_rawat_pengerjaan
											)
											; 
				}
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function getNamaBulan($mm)
	{
		$mm = $mm*1;
		$namaBulanArr = array('1' =>'Januari', 'Pebuari', 'Maret', 'April',  'Mei', 'Juni',  'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember');
		$namaBulan = $namaBulanArr[$mm];
	
		return $namaBulan;
	}
	
	public function getPejabat($kdunit) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   $where[] = $kdunit;
	   //$where[] = $periode;

	   try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			 
				$result = $db->fetchAll("select c_jabatan, i_peg_nip, n_jabatan
										from e_sdm_jabatan_0_tm A
										where c_jabatan = ? ",$where);
										
										
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
					
					$n_peg 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nip);	
					
					$c_unit_kerja 	= $db->fetchCol('select c_unit_kerja  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nip);

					$i_orgb 		= $db->fetchCol('select i_orgb  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nip);											
														
					$hasilAkhir[$j] = array("i_peg_nip"			=>(string)$result[$j]->i_peg_nip,
											"n_peg"   			=>$n_peg[0], 
											"n_jabatan"      	=>(string)$result[$j]->n_jabatan, 
											"c_unit_kerja"      =>$c_unit_kerja[0],
											"i_orgb"    		=>$i_orgb[0]
											)
											; 
				}
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	//get jabatan diganti ===== 23Juli 08   ==============
	
	public function getPejabat_Old($kdunit) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   $where[] = $kdunit;
	   //$where[] = $periode;

	   try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			 
				$result = $db->fetchAll("select B.i_peg_nip, n_peg, A.n_jabatan, b.c_unit_kerja, b.i_orgb  
										from e_sdm_jabatan_0_tm A,  e_sdm_pegawai_0_tm B
										where a.i_peg_nip = b.i_peg_nip
										and b.c_unit_kerja = a.c_jabatan
										and b.c_unit_kerja = ? 
										",$where);
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
			 
					$hasilAkhir[$j] = array("i_peg_nip"	=>(string)$result[$j]->i_peg_nip,
											"n_peg"   =>(string)$result[$j]->n_peg, 
											"n_jabatan"      =>(string)$result[$j]->n_jabatan, 
											"n_peg"          		=>(string)$result[$j]->n_peg,
											"c_unit_kerja"         		=>(string)$result[$j]->c_unit_kerja,
											"i_orgb"    			=>(string)$result[$j]->i_orgb
											)
											; 
				}
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	
	//tambahan joy 20 feb 08=============================================
	public function getKategoriProblemSi($pageNumber,$itemPerPage) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_kategori_problemti_tm
											where c_jenis_problem='S'");
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				$result = $db->fetchAll("SELECT  *  FROM e_ast_kategori_problemti_tm
										where c_jenis_problem='S'
										order by c_problem_ctgr limit $xLimit offset $xOffset");
				$jmlResult = count($result);
		 
				if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {
		 
						$hasilAkhir[$j] = array("c_problem_ctgr"    =>(string)$result[$j]->c_problem_ctgr,
											    "n_problem_ctgr"    =>(string)$result[$j]->n_problem_ctgr,
												"c_jenis_problem"    =>(string)$result[$j]->c_jenis_problem,
											    "q_nomor_max"       =>(string)$result[$j]->q_nomor_max,
												"c_status"			=>(string)$result[$j]->c_status);
					}
				}
			}		 
			return $hasilAkhir;
		} catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
		}
	}
	
	
	public function getKategoriProblemAll($pageNumber,$itemPerPage) 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_kategori_problemti_tm");
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				$result = $db->fetchAll("SELECT  *  FROM e_ast_kategori_problemti_tm										
										order by c_problem_ctgr limit $xLimit offset $xOffset");
				$jmlResult = count($result);
		 	        
				if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {
		 
						$hasilAkhir[$j] = array("c_problem_ctgr"    =>(string)$result[$j]->c_problem_ctgr,
											    "n_problem_ctgr"    =>(string)$result[$j]->n_problem_ctgr,
												"c_jenis_problem"    =>(string)$result[$j]->c_jenis_problem,
											    "q_nomor_max"       =>(string)$result[$j]->q_nomor_max,
												"c_status"			=>(string)$result[$j]->c_status);
					}
				}
			}		 
			return $hasilAkhir;
		} catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
		}
	}
	
	//Insert Kategori Problem
	public function insertKategoriProblemSi(array $data)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		//$where = $data['noPengajuan'];
		try {
			$db->beginTransaction();
			
				$prmInsert = array("c_problem_ctgr"		=>$data['c_problem_ctgr'],
									"n_problem_ctgr"	=>$data['n_problem_ctgr'],
									"c_jenis_problem"	=>$data['c_jenis_problem'],
									"q_nomor_max"		=>$data['q_nomor_max'],
									"c_status"			=>$data['c_status'],
									"i_entry"			=>$data['i_entry'],
									"d_entry"			=>date("Y-m-d"));
				
				
			$db->insert('e_ast_kategori_problemti_tm',$prmInsert);
			$db->commit();
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal';
	   }
	}
	
	//Update Kategori Problem
	public function updateKategoriProblemSi(array $data)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		//$where = $data['noPengajuan'];
		try {
			$db->beginTransaction();
			
				$prmUpdate = array("n_problem_ctgr"		=>$data['n_problem_ctgr'],
									"c_jenis_problem"	=>$data['c_jenis_problem'],
									"c_status"			=>$data['c_status']);
									
				$where = "c_problem_ctgr  =  '".$data['c_problem_ctgr']."'";
				
			$db->update('e_ast_kategori_problemti_tm',$prmUpdate,$where);
			$db->commit();
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal';
	   }
	}
	
	public function getDataProblemSi($pageNumber,$itemPerPage) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_problemti_0_tm 
											where c_status_perbaikan is null"
										   );
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				$result = $db->fetchAll("SELECT  a.c_problem_ctgr, c_problem, d_problem_awal,  d_problem_akhir,
										e_problem_kasus,e_problem_penyebab,e_problem_solusi,e_sumberdata,
										e_keterangan,q_nomor_max,n_problem_ctgr  
										FROM e_ast_problemti_0_tm a, e_ast_kategori_problemti_tm b
										where c_status_perbaikan is null
										and a.c_problem_ctgr=b.c_problem_ctgr
										order by c_problem_ctgr 
										limit $xLimit offset $xOffset");
										
				$jmlResult = count($result);
		 
				if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {
		 
						$hasilAkhir[$j] = array("c_problem_ctgr"		=>(string)$result[$j]->c_problem_ctgr,
												"c_problem"    			=>(string)$result[$j]->c_problem,
												"d_problem_awal"    	=>(string)$result[$j]->d_problem_awal,
												"d_problem_akhir"    	=>(string)$result[$j]->d_problem_akhir,
												"e_problem_kasus"    	=>(string)$result[$j]->e_problem_kasus,
												"e_problem_penyebab"    =>(string)$result[$j]->e_problem_penyebab,
												"e_problem_solusi"    	=>(string)$result[$j]->e_problem_solusi,
												"e_sumberdata"    		=>(string)$result[$j]->e_sumberdata,
												"e_keterangan"    		=>(string)$result[$j]->e_keterangan,
												"q_nomor_max"       	=>(string)$result[$j]->q_nomor_max ,
												"n_problem_ctgr"		=>(string)$result[$j]->n_problem_ctgr);
					}
				}
			}		 
			return $hasilAkhir;
		} catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
		}
	}
	
	public function getDataProblemSiByKategori($pageNumber,$itemPerPage,$nmKategori) {
		
		$nmbrg   = '%'.strtoupper($nmKategori).'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $nmbrg;
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) 
											FROM e_ast_problemti_0_tm a, e_ast_kategori_problemti_tm b
											where c_status_perbaikan is null
											and a.c_problem_ctgr=b.c_problem_ctgr
											and upper(n_problem_ctgr) like ? ", $where );
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				$result = $db->fetchAll("SELECT  a.c_problem_ctgr, c_problem, d_problem_awal,  d_problem_akhir,
										e_problem_kasus,e_problem_penyebab,e_problem_solusi,e_sumberdata,
										e_keterangan,q_nomor_max,n_problem_ctgr  
										FROM e_ast_problemti_0_tm a, e_ast_kategori_problemti_tm b
										where c_status_perbaikan is null
										and a.c_problem_ctgr=b.c_problem_ctgr
										and upper(n_problem_ctgr) like ?
										order by c_problem_ctgr 
										limit $xLimit offset $xOffset",$where);
										
				$jmlResult = count($result);
				
				if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {
		 
						$hasilAkhir[$j] = array("c_problem_ctgr"		=>(string)$result[$j]->c_problem_ctgr,
												"c_problem"    			=>(string)$result[$j]->c_problem,
												"d_problem_awal"    	=>(string)$result[$j]->d_problem_awal,
												"d_problem_akhir"    	=>(string)$result[$j]->d_problem_akhir,
												"e_problem_kasus"    	=>(string)$result[$j]->e_problem_kasus,
												"e_problem_penyebab"    =>(string)$result[$j]->e_problem_penyebab,
												"e_problem_solusi"    	=>(string)$result[$j]->e_problem_solusi,
												"e_sumberdata"    		=>(string)$result[$j]->e_sumberdata,
												"e_keterangan"    		=>(string)$result[$j]->e_keterangan,
												"q_nomor_max"       	=>(string)$result[$j]->q_nomor_max ,
												"n_problem_ctgr"		=>(string)$result[$j]->n_problem_ctgr);
					}
				}
			}		 
			return $hasilAkhir;
		} catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
		}
	}
	
	public function getDataProblemSiByPeriode($pageNumber,$itemPerPage,$nmKategori,$tglAwal,$tglAkhir) {
	
		$nmbrg   = '%'.strtoupper($nmKategori).'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $nmbrg;
			$where[] = $tglAwal;
			$where[] = $tglAkhir;
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_problemti_0_tm a, e_ast_kategori_problemti_tm b
											where c_status_perbaikan is null
											and a.c_problem_ctgr=b.c_problem_ctgr
											and upper(n_problem_ctgr) like ?
											and d_problem_awal between ? and ? ", $where );
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				$result = $db->fetchAll("SELECT  a.c_problem_ctgr, c_problem, d_problem_awal,  d_problem_akhir,
										e_problem_kasus,e_problem_penyebab,e_problem_solusi,e_sumberdata,
										e_keterangan,q_nomor_max,n_problem_ctgr  
										FROM e_ast_problemti_0_tm a, e_ast_kategori_problemti_tm b
										where c_status_perbaikan is null
										and a.c_problem_ctgr=b.c_problem_ctgr
										and upper(n_problem_ctgr) like ?
										and d_problem_awal between ? and ?
										order by c_problem_ctgr 
										limit $xLimit offset $xOffset",$where);
										
				$jmlResult = count($result);
		 
				if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {
		 
						$hasilAkhir[$j] = array("c_problem_ctgr"		=>(string)$result[$j]->c_problem_ctgr,
												"c_problem"    			=>(string)$result[$j]->c_problem,
												"d_problem_awal"    	=>(string)$result[$j]->d_problem_awal,
												"d_problem_akhir"    	=>(string)$result[$j]->d_problem_akhir,
												"e_problem_kasus"    	=>(string)$result[$j]->e_problem_kasus,
												"e_problem_penyebab"    =>(string)$result[$j]->e_problem_penyebab,
												"e_problem_solusi"    	=>(string)$result[$j]->e_problem_solusi,
												"e_sumberdata"    		=>(string)$result[$j]->e_sumberdata,
												"e_keterangan"    		=>(string)$result[$j]->e_keterangan,
												"q_nomor_max"       	=>(string)$result[$j]->q_nomor_max ,
												"n_problem_ctgr"		=>(string)$result[$j]->n_problem_ctgr);
					}
				}
			}		 
			return $hasilAkhir;
		} catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
		}
	}
	
	public function createKdProblem($kategori) {
	    $registry = Zend_Registry::getInstance();
		$where[] = $kategori;
		$db = $registry->get('db');
	    try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$result = $db->fetchOne('SELECT gen_noproblemti(?)',$where);
			return $result;
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return  0;
		}
	} 
	//Insert Data Problem
	public function insertProblemSi(array $data)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		//$where = $data['noPengajuan'];
		try {
			$db->beginTransaction();
			
			/*	$prmInsert = array("c_problem_ctgr"			=>$data['c_problem_ctgr'],
									"c_problem"				=>$data['c_problem'],
									"d_problem_awal"		=>$data['d_problem_awal'],
									"d_problem_akhir"		=>$data['d_problem_akhir'],
									"e_problem_kasus"		=>$data['e_problem_kasus'],
									"e_problem_penyebab"	=>$data['e_problem_penyebab'],
									"e_problem_solusi"		=>$data['e_problem_solusi'],
									"e_keterangan"			=>$data['e_keterangan'],
									"e_sumberdata"			=>$data['e_sumberdata'],
									"c_status_perbaikan"	=>$data['c_status_perbaikan'],
									"i_peg_nip"				=>$data['i_peg_nip'],
									"i_entry"				=>$data['i_entry'],
									"d_entry"				=>date("Y-m-d"));
				*/
			$d_problem_akhir = $data['d_problem_akhir'];
			$sub = substr($d_problem_akhir,0,4);
			if ($sub =='1970'){
				$prmInsert = array("c_problem_ctgr"			=>$data['c_problem_ctgr'],
									"c_problem"				=>$data['c_problem'],
									"d_problem_awal"		=>$data['d_problem_awal'],
									"e_problem_kasus"		=>$data['e_problem_kasus'],
									"e_problem_penyebab"	=>$data['e_problem_penyebab'],
									"e_problem_solusi"		=>$data['e_problem_solusi'],
									"e_keterangan"			=>$data['e_keterangan'],
									"e_sumberdata"			=>$data['e_sumberdata'],
									"c_status_perbaikan"	=>$data['c_status_perbaikan'],
									"i_peg_nip"				=>$data['i_peg_nip'],
									"i_entry"				=>$data['i_entry'],
									"d_entry"				=>date("Y-m-d"));
			}else{
				$prmInsert = array("c_problem_ctgr"			=>$data['c_problem_ctgr'],
									"c_problem"				=>$data['c_problem'],
									"d_problem_awal"		=>$data['d_problem_awal'],
									"d_problem_akhir"		=>$data['d_problem_akhir'],
									"e_problem_kasus"		=>$data['e_problem_kasus'],
									"e_problem_penyebab"	=>$data['e_problem_penyebab'],
									"e_problem_solusi"		=>$data['e_problem_solusi'],
									"e_keterangan"			=>$data['e_keterangan'],
									"e_sumberdata"			=>$data['e_sumberdata'],
									"c_status_perbaikan"	=>$data['c_status_perbaikan'],
									"i_peg_nip"				=>$data['i_peg_nip'],
									"i_entry"				=>$data['i_entry'],
									"d_entry"				=>date("Y-m-d"));
			}
			$db->insert('e_ast_problemti_0_tm',$prmInsert);
			$db->commit();
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal';
	   }
	}
	
	//Update Data Problem
	public function updateDataProblemSi(array $data)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$where[] = "c_problem_ctgr = '".$data['kdctgr']."'";
		$where[] = "c_problem = '".$data['kdproblem']."'";
		
		try {
			$db->beginTransaction();
			$tglAkhir = $data['tglAkhir'];
			$sub = substr($tglAkhir,0,4);
			if ($sub =='-#-#'){
			
				$prmInsert = array("d_problem_awal"		=>$data['tglAwal'],
									"e_problem_kasus"		=>$data['kasus'],
									"e_problem_penyebab"	=>$data['penyebab'],
									"e_problem_solusi"		=>$data['solusi'],
									"e_keterangan"			=>$data['keterangan'],
									"e_sumberdata"			=>$data['sumberdata'],
									"c_status_perbaikan"	=>$data['status'],
									"i_peg_nip"				=>$data['nip']);
			}else{
				$prmInsert = array("d_problem_awal"		=>$data['tglAwal'],
									"d_problem_akhir"		=>$data['tglAkhir'],
									"e_problem_kasus"		=>$data['kasus'],
									"e_problem_penyebab"	=>$data['penyebab'],
									"e_problem_solusi"		=>$data['solusi'],
									"e_keterangan"			=>$data['keterangan'],
									"e_sumberdata"			=>$data['sumberdata'],
									"c_status_perbaikan"	=>$data['status'],
									"i_peg_nip"				=>$data['nip']);
			}	
			$db->update('e_ast_problemti_0_tm',$prmInsert,$where);
			$db->commit();
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal';
	   }
	}
	
	function chgFormatDate($date){
		$timestamp = strtotime($date);
		$date = date('Y-m-d H:i:s', $timestamp);
		return $date;
	}
	
	// ===================== Delete Data Pencatatan Problem SI =============================
	public function deleteDataProblemSi($kdctgr,$kdproblem)
	{
		try
		{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$db->beginTransaction();
			$where[] = "c_problem_ctgr = '".$kdctgr."'";
			$where[] = "c_problem = '".$kdproblem."'";
			
			$db->delete('e_ast_problemti_0_tm', $where);
			$db->commit();
			return 'sukses';
		}catch (Exception $e){
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal';
		}
	}
	
	public function getNamaKategori($kdctgr) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$where[] = $kdctgr;
				$result = $db->fetchAll("SELECT  *  FROM e_ast_kategori_problemti_tm 
										where c_problem_ctgr = ?",$where);
				$jmlResult = count($result);
		 
				if($jmlResult > 0){
					//for ($j = 0; $j < $jmlResult; $j++) {
						$namaKategori = (string)$result[0]->n_problem_ctgr;
					//}
				}
			return $namaKategori;
		} catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
		}
	}
	
	public function getDataByKodeProblem($kdctgr,$kdproblem) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
				$where[]= $kdctgr;
				$where[]= $kdproblem;
				$result = $db->fetchAll("SELECT  *  FROM e_ast_problemti_0_tm 
										where c_problem_ctgr = ?
										and c_problem = ?
										",$where);
				$jmlResult = count($result);
		 
				if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {
		 
						$hasilAkhir[$j] = array("c_problem_ctgr"	=>(string)$result[$j]->c_problem_ctgr,
								"c_problem"    =>(string)$result[$j]->c_problem,
								"d_problem_awal"    =>(string)$result[$j]->d_problem_awal,
								"d_problem_akhir"    =>(string)$result[$j]->d_problem_akhir,
								"e_problem_kasus"    =>(string)$result[$j]->e_problem_kasus,
								"e_problem_penyebab"    =>(string)$result[$j]->e_problem_penyebab,
								"e_problem_solusi"    =>(string)$result[$j]->e_problem_solusi,
								"e_sumberdata"    =>(string)$result[$j]->e_sumberdata,
								"e_keterangan"    =>(string)$result[$j]->e_keterangan,
								"q_nomor_max"       =>(string)$result[$j]->q_nomor_max );
					}
				}
			return $hasilAkhir;
		} catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
		}
	}
	
	public function getLaporanProblemSi($pageNumber,$itemPerPage) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_problemti_0_tm");
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				$result = $db->fetchAll("SELECT  *  FROM e_ast_problemti_0_tm 
										order by c_problem_ctgr limit $xLimit offset $xOffset");
				$jmlResult = count($result);
		 
				if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {
		 
						$hasilAkhir[$j] = array("c_problem_ctgr"	=>(string)$result[$j]->c_problem_ctgr,
								"c_problem"    =>(string)$result[$j]->c_problem,
								"d_problem_awal"    =>(string)$result[$j]->d_problem_awal,
								"d_problem_akhir"    =>(string)$result[$j]->d_problem_akhir,
								"e_problem_kasus"    =>(string)$result[$j]->e_problem_kasus,
								"e_problem_penyebab"    =>(string)$result[$j]->e_problem_penyebab,
								"e_problem_solusi"    =>(string)$result[$j]->e_problem_solusi,
								"e_sumberdata"    =>(string)$result[$j]->e_sumberdata,
								"e_keterangan"    =>(string)$result[$j]->e_keterangan,
								"q_nomor_max"       =>(string)$result[$j]->q_nomor_max );
					}
				}
			}		 
			return $hasilAkhir;
		} catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
		}
	}
	
	public function getLaporanProblemSiByKategori_Old($pageNumber,$itemPerPage,$data) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where[] = $data['kdctgr'];
			$where[] = $data['prdAwal'];
			$where[] = $data['prdAkhir'];
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_problemti_0_tm
											where c_problem_ctgr=? and (d_problem_awal
											between ? and ?)",$where);
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				$result = $db->fetchAll("SELECT  *  FROM e_ast_problemti_0_tm
										where c_problem_ctgr=? and 
										(d_problem_awal between ? and ?) 
										order by d_problem_awal 
										limit $xLimit offset $xOffset",$where);
				$jmlResult = count($result);
		 
				if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {
		 
						$hasilAkhir[$j] = array("c_problem_ctgr"		=>(string)$result[$j]->c_problem_ctgr,
												"c_problem"    			=>(string)$result[$j]->c_problem,
												"d_problem_awal"    	=>(string)$result[$j]->d_problem_awal,
												"d_problem_akhir"    	=>(string)$result[$j]->d_problem_akhir,
												"e_problem_kasus"    	=>(string)$result[$j]->e_problem_kasus,
												"e_problem_penyebab"    =>(string)$result[$j]->e_problem_penyebab,
												"e_problem_solusi"    	=>(string)$result[$j]->e_problem_solusi,
												"e_sumberdata"    		=>(string)$result[$j]->e_sumberdata,
												"e_keterangan"    		=>(string)$result[$j]->e_keterangan,
												"q_nomor_max"       	=>(string)$result[$j]->q_nomor_max );
					}
				}
			}		 
			return $hasilAkhir;
		} catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
		}
	}
	
	public function getLaporanProblemSiByKategoriPeriode($pageNumber,$itemPerPage,$data) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where[] = $data['kdctgr'];
			$where[] = $data['prdAwal'];
			$where[] = $data['prdAkhir'];
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_problemti_0_tm
											where c_problem_ctgr=? and (d_problem_awal
											between ? and ?)",$where);
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				$result = $db->fetchAll("SELECT  *  FROM e_ast_problemti_0_tm
										where c_problem_ctgr=? and 
										(d_problem_awal between ? and ?) 
										order by d_problem_awal 
										limit $xLimit offset $xOffset",$where);
				$jmlResult = count($result);
		 
				if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {
		 
						$hasilAkhir[$j] = array("c_problem_ctgr"		=>(string)$result[$j]->c_problem_ctgr,
												"c_problem"    			=>(string)$result[$j]->c_problem,
												"d_problem_awal"    	=>(string)$result[$j]->d_problem_awal,
												"d_problem_akhir"    	=>(string)$result[$j]->d_problem_akhir,
												"e_problem_kasus"    	=>(string)$result[$j]->e_problem_kasus,
												"e_problem_penyebab"    =>(string)$result[$j]->e_problem_penyebab,
												"e_problem_solusi"    	=>(string)$result[$j]->e_problem_solusi,
												"e_sumberdata"    		=>(string)$result[$j]->e_sumberdata,
												"e_keterangan"    		=>(string)$result[$j]->e_keterangan,
												"q_nomor_max"       	=>(string)$result[$j]->q_nomor_max );
					}
				}
			}		 
			return $hasilAkhir;
		} catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
		}
	}
	
	public function getLaporanProblemSiByKategori($pageNumber,$itemPerPage,$data) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where[] = $data['kdctgr'];
			//$where[] = $data['prdAwal'];
			//$where[] = $data['prdAkhir'];
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_problemti_0_tm
											where c_problem_ctgr=? ",$where);
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				$result = $db->fetchAll("SELECT  *  FROM e_ast_problemti_0_tm
										where c_problem_ctgr=? 
										order by d_problem_awal 
										limit $xLimit offset $xOffset",$where);
				$jmlResult = count($result);
		 
				if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {
		 
						$hasilAkhir[$j] = array("c_problem_ctgr"		=>(string)$result[$j]->c_problem_ctgr,
												"c_problem"    			=>(string)$result[$j]->c_problem,
												"d_problem_awal"    	=>(string)$result[$j]->d_problem_awal,
												"d_problem_akhir"    	=>(string)$result[$j]->d_problem_akhir,
												"e_problem_kasus"    	=>(string)$result[$j]->e_problem_kasus,
												"e_problem_penyebab"    =>(string)$result[$j]->e_problem_penyebab,
												"e_problem_solusi"    	=>(string)$result[$j]->e_problem_solusi,
												"e_sumberdata"    		=>(string)$result[$j]->e_sumberdata,
												"e_keterangan"    		=>(string)$result[$j]->e_keterangan,
												"q_nomor_max"       	=>(string)$result[$j]->q_nomor_max );
					}
				}
			}		 
			return $hasilAkhir;
		} catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
		}
	}
	
	public function getLaporanProblemSiByPeriode($pageNumber,$itemPerPage,$data) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			//$where[] = $data['kdctgr'];
			$where[] = $data['prdAwal'];
			$where[] = $data['prdAkhir'];
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_problemti_0_tm
											where  (d_problem_awal
											between ? and ?)",$where);
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				$result = $db->fetchAll("SELECT  *  FROM e_ast_problemti_0_tm
										where
										(d_problem_awal between ? and ?) 
										order by d_problem_awal 
										limit $xLimit offset $xOffset",$where);
				$jmlResult = count($result);
		 
				if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {
		 
						$hasilAkhir[$j] = array("c_problem_ctgr"		=>(string)$result[$j]->c_problem_ctgr,
												"c_problem"    			=>(string)$result[$j]->c_problem,
												"d_problem_awal"    	=>(string)$result[$j]->d_problem_awal,
												"d_problem_akhir"    	=>(string)$result[$j]->d_problem_akhir,
												"e_problem_kasus"    	=>(string)$result[$j]->e_problem_kasus,
												"e_problem_penyebab"    =>(string)$result[$j]->e_problem_penyebab,
												"e_problem_solusi"    	=>(string)$result[$j]->e_problem_solusi,
												"e_sumberdata"    		=>(string)$result[$j]->e_sumberdata,
												"e_keterangan"    		=>(string)$result[$j]->e_keterangan,
												"q_nomor_max"       	=>(string)$result[$j]->q_nomor_max );
					}
				}
			}		 
			return $hasilAkhir;
		} catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
		}
	}
	
	//======================================================
	
	public function getSoftwareList($pageNumber,$itemPerPage,$nmBarang) {
	
	    
		$tglSkrg = date("Y-m-d");
		$nbrg = strtoupper($nmBarang);
	    $brg = '%'.$nbrg.'%';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
	    
			$where[] = $tglSkrg;
			$where[] = $brg;
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("SELECT count(*)
											FROM e_ast_software_0_tr a, e_ast_vendor_0_tr b,
											e_ast_kelompok_software_tr c
											where a.i_rekanan = b.i_rekanan
											and a.i_sw_kelompok    = c.i_sw_kelompok
											and d_sw_expiregaransi < ?
											and upper(n_sw) like ? ",$where);
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;	
				$result = $db->fetchAll("SELECT a.i_sw,a.n_sw,a.i_sw_kelompok,c.n_sw_kelompok,a.i_sw_licensi,
									a.i_sw_versi,a.e_sw_platform,e_sw_platform1,e_sw_platform2,e_sw_platform3,
									b.n_rekanan	,i_rekanan_ref, a_prsh_jalan,d_sw_expiregaransi, i_hw_investasi
									FROM e_ast_software_0_tr a, e_ast_vendor_0_tr b,
									e_ast_kelompok_software_tr c
									where a.i_rekanan = b.i_rekanan
									and a.i_sw_kelompok    = c.i_sw_kelompok
									and d_sw_expiregaransi < ?
									and upper(n_sw) like ?
									order by a.i_sw 
									limit $xLimit offset $xOffset",$where); 
				$jmlResult = count($result);
		
				for ($j = 0; $j < $jmlResult; $j++) {
				//===== ambil nama rekanan =================================
					$i_rekanan_ref	= (string)$result[$j]->i_rekanan_ref;	
						
						//ambil data dari pengadaan.....................................................................................
						$n_rekanan 	= $db->fetchCol("select n_rekanan
													from e_rekanan_0_0_tm a,e_rekanan_ijin_prsh_tm b,e_rekanan_ijin_usaha_tm c,e_rekanan_almt_prsh_tm d
													where a.i_rekanan = b.i_rekanan and a.i_rekanan = d.i_rekanan
													and b.i_prsh_ijinusaha = c.i_prsh_ijinusaha 
													and (c.n_prsh_ijinusaha  like 'TI%' or c.n_prsh_ijinusaha  like 'Komputer%')
													and a.i_rekanan=? ",$result[$j]->i_rekanan_ref);
						
						$a_prsh_jalan 	= $db->fetchCol("select a_prsh_jalan
													from e_rekanan_0_0_tm a,e_rekanan_ijin_prsh_tm b,e_rekanan_ijin_usaha_tm c,e_rekanan_almt_prsh_tm d
													where a.i_rekanan = b.i_rekanan and a.i_rekanan = d.i_rekanan
													and b.i_prsh_ijinusaha = c.i_prsh_ijinusaha 
													and (c.n_prsh_ijinusaha  like 'TI%' or c.n_prsh_ijinusaha  like 'Komputer%')
													and a.i_rekanan=? ",$result[$j]->i_rekanan_ref);
						//...................................................................................................................
						if($i_rekanan_ref =='--' || $i_rekanan_ref =='-' ){
								$hasilAkhir[$j] = array("i_sw"             			=>(string)$result[$j]->i_sw,
															"n_sw"             		=>(string)$result[$j]->n_sw,
															"n_sw_kelompok"    		=>(string)$result[$j]->n_sw_kelompok,
															"i_sw_kelompok"    		=>(string)$result[$j]->i_sw_kelompok,
															"i_sw_licensi"     		=>(string)$result[$j]->i_sw_licensi,
															"i_sw_versi"       		=>(string)$result[$j]->i_sw_versi,
															"e_sw_platform"    		=>(string)$result[$j]->e_sw_platform,
															"e_sw_platform1"    	=>(string)$result[$j]->e_sw_platform1,
															"e_sw_platform2"    	=>(string)$result[$j]->e_sw_platform2,
															"e_sw_platform3"    	=>(string)$result[$j]->e_sw_platform3,
															"d_sw_releasepublish"    =>(string)$result[$j]->d_sw_releasepublish,
															"c_matauang"    			=>(string)$result[$j]->c_matauang,
															"v_sw_harga" 			=>(string)$result[$j]->v_sw_harga,
															"d_sw_lastupdate"    	=>(string)$result[$j]->d_sw_lastupdate,
															"e_sw_reqsystem"    		=>(string)$result[$j]->e_sw_reqsystem,
															"d_sw_expiregaransi"    	=>(string)$result[$j]->d_sw_expiregaransi,
															"i_rekanan"    			=>(string)$result[$j]->i_rekanan,
															"d_sw_peroleh"    		=>(string)$result[$j]->d_sw_peroleh,
															"d_sw_expirelicensi"    	=>(string)$result[$j]->d_sw_expirelicensi,
															"q_sw_licensi"    		=>(string)$result[$j]->q_sw_licensi,
															"c_sw_tipelicensi"    	=>(string)$result[$j]->c_sw_tipelicensi,
															"i_sw_nomorlicensi"    	=>(string)$result[$j]->i_sw_nomorlicensi,
															"c_sw_sertifikat"    	=>(string)$result[$j]->c_sw_sertifikat,
															"i_sw_sertifikat"    	=>(string)$result[$j]->i_sw_sertifikat,
															"n_rekanan"        		=>(string)$result[$j]->n_rekanan,
															"i_hw_investasi"    	=>(string)$result[$j]->i_hw_investasi);
						}else{
								$hasilAkhir[$j] = array("i_sw"             			=>(string)$result[$j]->i_sw,
															"n_sw"             		=>(string)$result[$j]->n_sw,
															"n_sw_kelompok"    		=>(string)$result[$j]->n_sw_kelompok,
															"i_sw_kelompok"    		=>(string)$result[$j]->i_sw_kelompok,
															"i_sw_licensi"     		=>(string)$result[$j]->i_sw_licensi,
															"i_sw_versi"       		=>(string)$result[$j]->i_sw_versi,
															"e_sw_platform"    		=>(string)$result[$j]->e_sw_platform,
															"e_sw_platform1"    	=>(string)$result[$j]->e_sw_platform1,
															"e_sw_platform2"    	=>(string)$result[$j]->e_sw_platform2,
															"e_sw_platform3"    	=>(string)$result[$j]->e_sw_platform3,
															"d_sw_releasepublish"   =>(string)$result[$j]->d_sw_releasepublish,
															"c_matauang"    		=>(string)$result[$j]->c_matauang,
															"v_sw_harga" 			=>(string)$result[$j]->v_sw_harga,
															"d_sw_lastupdate"    	=>(string)$result[$j]->d_sw_lastupdate,
															"e_sw_reqsystem"    	=>(string)$result[$j]->e_sw_reqsystem,
															"d_sw_expiregaransi"    =>(string)$result[$j]->d_sw_expiregaransi,
															"i_rekanan"    			=>(string)$result[$j]->i_rekanan,
															"d_sw_peroleh"    		=>(string)$result[$j]->d_sw_peroleh,
															"d_sw_expirelicensi"    =>(string)$result[$j]->d_sw_expirelicensi,
															"q_sw_licensi"    		=>(string)$result[$j]->q_sw_licensi,
															"c_sw_tipelicensi"    	=>(string)$result[$j]->c_sw_tipelicensi,
															"i_sw_nomorlicensi"    	=>(string)$result[$j]->i_sw_nomorlicensi,
															"c_sw_sertifikat"    	=>(string)$result[$j]->c_sw_sertifikat,
															"i_sw_sertifikat"    	=>(string)$result[$j]->i_sw_sertifikat,
															"n_rekanan"        		=>$n_rekanan[0],
															"i_hw_investasi"    	=>(string)$result[$j]->i_hw_investasi);
						}
			}
        }			
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	//Hardware ===
	
	public function getDataHardWareList($svr, $pageNumber,$itemPerPage)  
	{
	   //echo "masuk services getDataHardWareList";	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   //$svr = $server; 
	   $nbrg = strtoupper($svr);
	    $brg = '%'.$nbrg.'%';
	   $tglSkrg = date("Y-m-d");
	   $ThnEx = $tglSkrg - 1 ;
	   $blntgl = date("-m-d");
	   
	   $TglExGaransi = $ThnEx.date("-m-d");
	   
	   try 
	   {  	         	 
		
 		 $where[]=$brg;
		 $where[]=$tglSkrg;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
										FROM e_ast_hardware_0_tm A, e_ast_vendor_0_tr B, e_ast_sskel_0_tr C 
										 where A.i_rekanan = B.i_rekanan 
										 and substr(a.c_barang,1,1) = c.kd_gol
										 and substr(a.c_barang,2,2) = c.kd_bid 
										 and substr(a.c_barang,4,2) = c.kd_kel
										 and substr(a.c_barang,6,2) = c.kd_skel
										 and substr(a.c_barang,8,3) = c.kd_sskel
										 and C.ur_sskel like ?
										 and d_hw_garansi < ?",$where); 
		 }
		 else
		 {
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;	
		 	$result = $db->fetchAll("SELECT A.d_anggaran,A.c_barang,to_char(A.i_aset,'09999') as i_aset,
								 				A.i_hw,
								 				A.n_hw,
								 				A.c_hw_type, 
								 				A.i_hw_register,
								 				B.n_rekanan,
								 				A.d_hw_garansi,	
								 				A.d_perolehan,
								 				A.i_rekanan,
								 				B.a_rekanan,
								 				A.q_hw_masapakai,
								 				A.e_hw,	
								         		C.ur_sskel,
												c_hw_status,
												e_hw_fungsi,
												i_rekanan_ref, 
												a_prsh_jalan,
												c_hw,
												i_hw_investasi
												 FROM e_ast_hardware_0_tm A, e_ast_vendor_0_tr B, e_ast_sskel_0_tr C 
												 where A.i_rekanan = B.i_rekanan 
												 and substr(a.c_barang,1,1) = c.kd_gol
												 and substr(a.c_barang,2,2) = c.kd_bid 
												 and substr(a.c_barang,4,2) = c.kd_kel
												 and substr(a.c_barang,6,2) = c.kd_skel
												 and substr(a.c_barang,8,3) = c.kd_sskel
												 and C.ur_sskel like ?
												 and d_hw_garansi < ?
									 limit $xLimit offset $xOffset",$where); 
									 
	         	 $jmlResult = count($result);
	         	 //echo "jumlah =".$jmlResult;		
			 if($jmlResult > 0)
			 {
				 for ($j = 0; $j < $jmlResult; $j++) 
				 {			 
					//
					//===== ambil nama rekanan =================================
						$i_rekanan_ref	= (string)$result[$j]->i_rekanan_ref;
						$d_hw_garansi	= (string)$result[$j]->d_hw_garansi;	
						$d_perolehan	= (string)$result[$j]->d_anggaran;	
						$ThnEx = $d_perolehan[0] + $d_hw_garansi[0];
					    $blntgl = date("-m-d");
					    $TglExGaransi = $ThnEx[0].date("-m-d");
					    
						//ambil data dari pengadaan.....................................................................................
						$n_rekanan 	= $db->fetchCol("select n_rekanan
													from e_rekanan_0_0_tm a,e_rekanan_ijin_prsh_tm b,e_rekanan_ijin_usaha_tm c,e_rekanan_almt_prsh_tm d
													where a.i_rekanan = b.i_rekanan and a.i_rekanan = d.i_rekanan
													and b.i_prsh_ijinusaha = c.i_prsh_ijinusaha 
													and (c.n_prsh_ijinusaha  like 'TI%' or c.n_prsh_ijinusaha  like 'Komputer%')
													and a.i_rekanan=? ",$result[$j]->i_rekanan_ref);
						
						$a_prsh_jalan 	= $db->fetchCol("select a_prsh_jalan
													from e_rekanan_0_0_tm a,e_rekanan_ijin_prsh_tm b,e_rekanan_ijin_usaha_tm c,e_rekanan_almt_prsh_tm d
													where a.i_rekanan = b.i_rekanan and a.i_rekanan = d.i_rekanan
													and b.i_prsh_ijinusaha = c.i_prsh_ijinusaha 
													and (c.n_prsh_ijinusaha  like 'TI%' or c.n_prsh_ijinusaha  like 'Komputer%')
													and a.i_rekanan=? ",$result[$j]->i_rekanan_ref);
						//...................................................................................................................
						if($i_rekanan_ref =='--' || $i_rekanan_ref =='-' ){
							//field field yg akan ditampilkan aja di gui 
							$hasilAkhir[$j] = array("d_anggaran"  		=>(string)$result[$j]->d_anggaran,
					           					"c_barang"  		=>(string)$result[$j]->c_barang,
					           					"i_aset" 	 		=>(string)$result[$j]->i_aset,
					           					"i_hw"  			=>(string)$result[$j]->i_hw,
					           					"n_hw"  			=>(string)$result[$j]->n_hw,
												"c_hw_type"  		=>(string)$result[$j]->c_hw_type,
												"i_hw_register"  	=>(string)$result[$j]->i_hw_register,
												"d_hw_garansi"  	=>(string)$result[$j]->d_hw_garansi,
												"d_perolehan"  		=>(string)$result[$j]->d_perolehan,
												"i_rekanan"  		=>(string)$result[$j]->i_rekanan,
												"n_rekanan"  		=>(string)$result[$j]->n_rekanan,
												"a_rekanan"  		=>(string)$result[$j]->a_rekanan,
												"q_hw_masapakai"  	=>(string)$result[$j]->q_hw_masapakai,
												"e_hw"  			=>(string)$result[$j]->e_hw,
												"ur_sskel"  		=>(string)$result[$j]->ur_sskel,
												"c_hw_status"		=>(string)$result[$j]->c_hw_status,
												"e_hw_fungsi"		=>(string)$result[$j]->e_hw_fungsi,
												"c_hw"				=>(string)$result[$j]->c_hw,
												"i_hw_investasi"				=>(string)$result[$j]->i_hw_investasi);
						}else{
							$hasilAkhir[$j] = array("d_anggaran"  		=>(string)$result[$j]->d_anggaran,
					           					"c_barang"  		=>(string)$result[$j]->c_barang,
					           					"i_aset" 	 		=>(string)$result[$j]->i_aset,
					           					"i_hw"  			=>(string)$result[$j]->i_hw,
					           					"n_hw"  			=>(string)$result[$j]->n_hw,
												"c_hw_type"  		=>(string)$result[$j]->c_hw_type,
												"i_hw_register"  	=>(string)$result[$j]->i_hw_register,
												"d_perolehan"  		=>(string)$result[$j]->d_perolehan,
												"i_rekanan"  		=>(string)$result[$j]->i_rekanan,
												"n_rekanan"  		=>$n_rekanan[0],
												"a_rekanan"  		=>$a_prsh_jalan[0],
												"q_hw_masapakai"  	=>(string)$result[$j]->q_hw_masapakai,
												"e_hw"  			=>(string)$result[$j]->e_hw,
												"ur_sskel"  		=>(string)$result[$j]->ur_sskel,
												"c_hw_status"		=>(string)$result[$j]->c_hw_status,
												"e_hw_fungsi"		=>(string)$result[$j]->e_hw_fungsi,
												"c_hw"				=>(string)$result[$j]->c_hw,
												"d_hw_garansi"		=>$d_hw_garansi[0],
												"i_hw_investasi"				=>(string)$result[$j]->i_hw_investasi);
						}
				 }
	        	}	
	        }		 
	     	return $hasilAkhir;
	   } 
	   catch (Exception $e) 
	   {
         	echo $e->getMessage().'<br>';
	     	return 'gagal <br>';
	   }
	}
	
	public function getDataHelpdesk($pageNumber,$itemPerPage) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasil = $db->fetchAll("select distinct(i_peg_helpdesk)
									    from e_ast_ajuanbaikti_0_tm A, e_ast_ajuanbaikti_item_tm B									    
										where B.i_barang_ajuanbaik =  A.i_barang_ajuanbaik
										and i_peg_helpdesk is not null");
			$hasilAkhir = count($hasil);
		 }
		 else
		 {
			
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;		
			
			$result = $db->fetchAll("select distinct(i_peg_helpdesk)
									    from e_ast_ajuanbaikti_0_tm A, e_ast_ajuanbaikti_item_tm B									    
										where B.i_barang_ajuanbaik =  A.i_barang_ajuanbaik
										and i_peg_helpdesk is not null
									order by i_peg_helpdesk 
									limit $xLimit offset $xOffset");
									
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$n_peg 				= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_helpdesk);	
				
				$n_jabatan 				= $db->fetchCol('select n_jabatan  
														from  e_sdm_jabatan_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_helpdesk);					
				
				$i_orgb 				= $db->fetchCol('select i_orgb  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_helpdesk);	
														
				$hasilAkhir[$j] = array("i_peg_nip"         	=>(string)$result[$j]->i_peg_helpdesk,
										"n_peg"           	 	=>$n_peg[0],
										"n_jabatan"          	=>$n_jabatan[0],
										"i_orgb"            	=>$i_orgb[0]
									);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
	
	//====================================add 20 - 06 -08 ==============================================
	
	public function queryNourutmax($modl) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	    try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ);
		 //$where[] = $data['unitkr'];
		 $where[] = $modl;
		 $result = $db->fetchOne('SELECT gen_nomorbarang(?)',$where);
		
	     return $result;
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return  0;
	   }
 
	} 
	
	//add==
	
	public function getPengerjaanProblemView($pageNumber,$itemPerPage,$nmHelpdesk,$status)
	{
		$nm	=	$nmHelpdesk;
		$st	=	$status;
		$nmHelpdesk = strtoupper($nmHelpdesk);
		$nmHelpdesk = '%'.$nmHelpdesk.'%';
		$status = strtoupper($status);
		$status = '%'.$status.'%';
		
		try {
			$where[] = $nmHelpdesk;
			$where[] = $status;
			$where[] = $nmHelpdesk;
			$where[] = $status;
			
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			if($nm =='' && $st ==''){
				if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasil = $db->fetchAll("Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										'' as c_problem_ctgr,
										d_barang_ajuanbaik as d_problem_awal,
										c_barang_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and (i_peg_helpdesk like ? or i_peg_helpdesk is null)
										and (c_status_perbaikan like ? or c_status_perbaikan is null)
										UNION
										SELECT  
										i_barang_ajuanbaik,
										d_barang_ajuanbaik,
										null as d_anggaran,
										null as c_barang,
										null as i_aset,
										i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk,
										e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, 
										d_problem_awal,
										'' as c_barang_perbaikan
										FROM e_ast_problemti_0_tm a
										where (i_peg_nip like ? or i_peg_nip is null)
										and (c_status_perbaikan like ? or c_status_perbaikan is null)
										order by i_barang_ajuanbaik 	
										",$where)	;
				$hasilList = count($hasil);
			}
			else 
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				$setuju = $db->fetchAll("Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										'' as c_problem_ctgr,
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan,
										c_barang_perbaikan,
										e_saran_sparepart,
										e_saran_pihakketiga,
										i_disposisi
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and (i_peg_helpdesk like ? or i_peg_helpdesk is null)
										and (c_status_perbaikan like ? or c_status_perbaikan is null)
										UNION
										SELECT  
										i_barang_ajuanbaik,
										d_barang_ajuanbaik,
										null as d_anggaran,
										null as c_barang,
										null as i_aset,
										i_peg_nip as i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk,
										e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, 
										d_problem_awal,
										c_status_perbaikan,
										'' as c_barang_perbaikan,
										'' as e_saran_sparepart,
										'' as e_saran_pihakketiga,
										'' as i_disposisi
										FROM e_ast_problemti_0_tm a
										where (i_peg_nip like ? or i_peg_nip is null) 
										and (c_status_perbaikan like ? or c_status_perbaikan is null)
										order by d_barang_ajuanbaik
										limit $xLimit offset $xOffset",$where)	;
				
								
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{
					$n_problem_ctgr		= $db->fetchCol('select n_problem_ctgr  
														from  e_ast_kategori_problemti_tm 
														where c_problem_ctgr = ? ',$setuju[$j]->c_problem_ctgr);	
					
					$n_peg 				= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_helpdesk);	
														
					$hasilList[$j] = array("i_barang_ajuanbaik"		=>(string)$setuju[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" 	=>(string)$setuju[$j]->d_barang_ajuanbaik,
											 "i_orgb"				=>(string)$setuju[$j]->i_orgb,
											 "i_barang_penerimaan"	=>(string)$setuju[$j]->i_barang_penerimaan,
											 "d_anggaran"			=>(string)$setuju[$j]->d_anggaran,
											 "c_barang"				=>(string)$setuju[$j]->c_barang,
											 "i_aset"				=>(string)$setuju[$j]->i_aset,
											 "i_peg_nip"			=>(string)$setuju[$j]->i_peg_nippemohon,
											 "i_peg_helpdesk"		=>(string)$setuju[$j]->i_peg_helpdesk,
											 "d_perolehan"			=>(string)$setuju[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$setuju[$j]->e_barang_perbaikan,
											 "c_problem_ctgr"		=>(string)$setuju[$j]->c_problem_ctgr,
											 "n_problem_ctgr"		=>$n_problem_ctgr[0],
											 "n_peg"				=>$n_peg[0],
											 "c_status_perbaikan"	=>(string)$setuju[$j]->c_status_perbaikan,
											 "c_barang_perbaikan"	=>(string)$setuju[$j]->c_barang_perbaikan,
											 "e_saran_sparepart"	=>(string)$setuju[$j]->e_saran_sparepart,
											 "e_saran_pihakketiga"	=>(string)$setuju[$j]->e_saran_pihakketiga,
											 "i_disposisi"			=>(string)$setuju[$j]->i_disposisi,
											 );
				}
			}	
			}
			//jk nip di isi & status tdk
			else if($nm !='' && $st ==''){
					if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasil = $db->fetchAll("Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										'' as c_problem_ctgr,
										d_barang_ajuanbaik as d_problem_awal,
										c_barang_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and i_peg_helpdesk like ? 
										and (c_status_perbaikan like ? or c_status_perbaikan is null)
										UNION
										SELECT  
										i_barang_ajuanbaik,
										d_barang_ajuanbaik,
										null as d_anggaran,
										null as c_barang,
										null as i_aset,
										i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk,
										e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, 
										d_problem_awal,
										'' as c_barang_perbaikan
										FROM e_ast_problemti_0_tm a
										where i_peg_nip like ? 
										and (c_status_perbaikan like ? or c_status_perbaikan is null)
										order by i_barang_ajuanbaik 	
										",$where)	;
				$hasilList = count($hasil);
			}
			else 
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				$setuju = $db->fetchAll("Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										'' as c_problem_ctgr,
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan,
										c_barang_perbaikan,
										e_saran_sparepart,
										e_saran_pihakketiga,
										i_disposisi
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and i_peg_helpdesk like ?
										and (c_status_perbaikan like ? or c_status_perbaikan is null)
										UNION
										SELECT  
										i_barang_ajuanbaik,
										d_barang_ajuanbaik,
										null as d_anggaran,
										null as c_barang,
										null as i_aset,
										i_peg_nip as i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk,
										e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, 
										d_problem_awal,
										c_status_perbaikan,
										'' as c_barang_perbaikan,
										'' as e_saran_sparepart,
										'' as e_saran_pihakketiga,
										'' as i_disposisi
										FROM e_ast_problemti_0_tm a
										where i_peg_nip like ? 
										and (c_status_perbaikan like ? or c_status_perbaikan is null)
										order by d_barang_ajuanbaik
										limit $xLimit offset $xOffset",$where)	;
				
								
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{
					$n_problem_ctgr		= $db->fetchCol('select n_problem_ctgr  
														from  e_ast_kategori_problemti_tm 
														where c_problem_ctgr = ? ',$setuju[$j]->c_problem_ctgr);	
					
					$n_peg 				= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_helpdesk);	
														
					$hasilList[$j] = array("i_barang_ajuanbaik"		=>(string)$setuju[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" 	=>(string)$setuju[$j]->d_barang_ajuanbaik,
											 "i_orgb"				=>(string)$setuju[$j]->i_orgb,
											 "i_barang_penerimaan"	=>(string)$setuju[$j]->i_barang_penerimaan,
											 "d_anggaran"			=>(string)$setuju[$j]->d_anggaran,
											 "c_barang"				=>(string)$setuju[$j]->c_barang,
											 "i_aset"				=>(string)$setuju[$j]->i_aset,
											 "i_peg_nip"			=>(string)$setuju[$j]->i_peg_nippemohon,
											 "i_peg_helpdesk"		=>(string)$setuju[$j]->i_peg_helpdesk,
											 "d_perolehan"			=>(string)$setuju[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$setuju[$j]->e_barang_perbaikan,
											 "c_problem_ctgr"		=>(string)$setuju[$j]->c_problem_ctgr,
											 "n_problem_ctgr"		=>$n_problem_ctgr[0],
											 "n_peg"				=>$n_peg[0],
											 "c_status_perbaikan"	=>(string)$setuju[$j]->c_status_perbaikan,
											 "c_barang_perbaikan"	=>(string)$setuju[$j]->c_barang_perbaikan,
											 "e_saran_sparepart"	=>(string)$setuju[$j]->e_saran_sparepart,
											 "e_saran_pihakketiga"	=>(string)$setuju[$j]->e_saran_pihakketiga,
											 "i_disposisi"			=>(string)$setuju[$j]->i_disposisi,
											 );
				}
			}
			}
			//jk nip & status di isi ==========
			else{
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasil = $db->fetchAll("Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										'' as c_problem_ctgr,
										d_barang_ajuanbaik as d_problem_awal,
										c_barang_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and i_peg_helpdesk like ? 
										and c_status_perbaikan like ?
										UNION
										SELECT  
										i_barang_ajuanbaik,
										d_barang_ajuanbaik,
										null as d_anggaran,
										null as c_barang,
										null as i_aset,
										i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk,
										e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, 
										d_problem_awal,
										'' as c_barang_perbaikan
										FROM e_ast_problemti_0_tm a
										where i_peg_nip like ? 
										and c_status_perbaikan like ? 
										order by i_barang_ajuanbaik 	
										",$where)	;
				$hasilList = count($hasil);
			}
			else 
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				$setuju = $db->fetchAll("Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										'' as c_problem_ctgr,
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan,
										c_barang_perbaikan,
										e_saran_sparepart,
										e_saran_pihakketiga,
										i_disposisi
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and i_peg_helpdesk like ? 
										and c_status_perbaikan like ? 
										UNION
										SELECT  
										i_barang_ajuanbaik,
										d_barang_ajuanbaik,
										null as d_anggaran,
										null as c_barang,
										null as i_aset,
										i_peg_nip as i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk,
										e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, 
										d_problem_awal,
										c_status_perbaikan,
										'' as c_barang_perbaikan,
										'' as e_saran_sparepart,
										'' as e_saran_pihakketiga,
										'' as i_disposisi
										FROM e_ast_problemti_0_tm a
										where i_peg_nip like ? 
										and c_status_perbaikan like ? 
										order by d_barang_ajuanbaik
										limit $xLimit offset $xOffset",$where)	;
				
								
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{
					$n_problem_ctgr		= $db->fetchCol('select n_problem_ctgr  
														from  e_ast_kategori_problemti_tm 
														where c_problem_ctgr = ? ',$setuju[$j]->c_problem_ctgr);	
					
					$n_peg 				= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_helpdesk);	
														
					$hasilList[$j] = array("i_barang_ajuanbaik"		=>(string)$setuju[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" 	=>(string)$setuju[$j]->d_barang_ajuanbaik,
											 "i_orgb"				=>(string)$setuju[$j]->i_orgb,
											 "i_barang_penerimaan"	=>(string)$setuju[$j]->i_barang_penerimaan,
											 "d_anggaran"			=>(string)$setuju[$j]->d_anggaran,
											 "c_barang"				=>(string)$setuju[$j]->c_barang,
											 "i_aset"				=>(string)$setuju[$j]->i_aset,
											 "i_peg_nip"			=>(string)$setuju[$j]->i_peg_nippemohon,
											 "i_peg_helpdesk"		=>(string)$setuju[$j]->i_peg_helpdesk,
											 "d_perolehan"			=>(string)$setuju[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$setuju[$j]->e_barang_perbaikan,
											 "c_problem_ctgr"		=>(string)$setuju[$j]->c_problem_ctgr,
											 "n_problem_ctgr"		=>$n_problem_ctgr[0],
											 "n_peg"				=>$n_peg[0],
											 "c_status_perbaikan"	=>(string)$setuju[$j]->c_status_perbaikan,
											 "c_barang_perbaikan"	=>(string)$setuju[$j]->c_barang_perbaikan,
											 "e_saran_sparepart"	=>(string)$setuju[$j]->e_saran_sparepart,
											 "e_saran_pihakketiga"	=>(string)$setuju[$j]->e_saran_pihakketiga,
											 "i_disposisi"			=>(string)$setuju[$j]->i_disposisi,
											 );
				}
			}
			}
			
			return $hasilList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	//add 08 Jul 08
	
	public function getLaporanProblemTiAllCetak() {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	  
	   try {
				
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
						    
				$xLimit=$itemPerPage;
			    $xOffset=($pageNumber-1)*$itemPerPage;
					$result = $db->fetchAll("Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										'' as c_problem_ctgr,
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan,
										c_barang_perbaikan,
										e_saran_sparepart,
										e_saran_pihakketiga,
										i_disposisi
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										
										UNION
										SELECT  
										i_barang_ajuanbaik,
										d_barang_ajuanbaik,
										null as d_anggaran,
										null as c_barang,
										null as i_aset,
										i_peg_nip as i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk,
										e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, 
										d_problem_awal,
										c_status_perbaikan,
										'' as c_barang_perbaikan,
										'' as e_saran_sparepart,
										'' as e_saran_pihakketiga,
										'' as i_disposisi
										FROM e_ast_problemti_0_tm a
										order by d_barang_ajuanbaik");
				
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
					$n_problem_ctgr		= $db->fetchCol('select n_problem_ctgr  
														from  e_ast_kategori_problemti_tm 
														where c_problem_ctgr = ? ',$result[$j]->c_problem_ctgr);	
					
					$n_peg 				= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_helpdesk);	
														
					$hasilAkhir[$j]  = array("i_barang_ajuanbaik"		=>(string)$result[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" 	=>(string)$result[$j]->d_barang_ajuanbaik,
											 "i_orgb"				=>(string)$result[$j]->i_orgb,
											 "i_barang_penerimaan"	=>(string)$result[$j]->i_barang_penerimaan,
											 "d_anggaran"			=>(string)$result[$j]->d_anggaran,
											 "c_barang"				=>(string)$result[$j]->c_barang,
											 "i_aset"				=>(string)$result[$j]->i_aset,
											 "i_peg_nip"			=>(string)$result[$j]->i_peg_nippemohon,
											 "i_peg_helpdesk"		=>(string)$result[$j]->i_peg_helpdesk,
											 "d_perolehan"			=>(string)$result[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$result[$j]->e_barang_perbaikan,
											 "c_problem_ctgr"		=>(string)$result[$j]->c_problem_ctgr,
											 "n_problem_ctgr"		=>$n_problem_ctgr[0],
											 "n_peg"				=>$n_peg[0],
											 "c_status_perbaikan"	=>(string)$result[$j]->c_status_perbaikan,
											 "c_barang_perbaikan"	=>(string)$result[$j]->c_barang_perbaikan,
											 "e_saran_sparepart"	=>(string)$result[$j]->e_saran_sparepart,
											 "e_saran_pihakketiga"	=>(string)$result[$j]->e_saran_pihakketiga,
											 "i_disposisi"			=>(string)$result[$j]->i_disposisi
											 );
				}
							 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	//add 09 juli 08 =================== by nip
	public function getLaporanProblemTiByNipCetak($nip) {
		
	    try {
			$where[] = $nip;
			$where[] = $nip;
			
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
	  	
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
						    
				$xLimit=$itemPerPage;
			    $xOffset=($pageNumber-1)*$itemPerPage;
					$result = $db->fetchAll("Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										'' as c_problem_ctgr,
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan,
										c_barang_perbaikan,
										e_saran_sparepart,
										e_saran_pihakketiga,
										i_disposisi
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and i_peg_helpdesk like ?
										UNION
										SELECT  
										i_barang_ajuanbaik,
										d_barang_ajuanbaik,
										null as d_anggaran,
										null as c_barang,
										null as i_aset,
										i_peg_nip as i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk,
										e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, 
										d_problem_awal,
										c_status_perbaikan,
										'' as c_barang_perbaikan,
										'' as e_saran_sparepart,
										'' as e_saran_pihakketiga,
										'' as i_disposisi
										FROM e_ast_problemti_0_tm a
										where i_peg_nip like ?
										order by d_barang_ajuanbaik",$where);
				
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
					$n_problem_ctgr		= $db->fetchCol('select n_problem_ctgr  
														from  e_ast_kategori_problemti_tm 
														where c_problem_ctgr = ? ',$result[$j]->c_problem_ctgr);	
					
					$n_peg 				= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_helpdesk);	
														
					$hasilAkhir[$j]  = array("i_barang_ajuanbaik"		=>(string)$result[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" 	=>(string)$result[$j]->d_barang_ajuanbaik,
											 "i_orgb"				=>(string)$result[$j]->i_orgb,
											 "i_barang_penerimaan"	=>(string)$result[$j]->i_barang_penerimaan,
											 "d_anggaran"			=>(string)$result[$j]->d_anggaran,
											 "c_barang"				=>(string)$result[$j]->c_barang,
											 "i_aset"				=>(string)$result[$j]->i_aset,
											 "i_peg_nip"			=>(string)$result[$j]->i_peg_nippemohon,
											 "i_peg_helpdesk"		=>(string)$result[$j]->i_peg_helpdesk,
											 "d_perolehan"			=>(string)$result[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$result[$j]->e_barang_perbaikan,
											 "c_problem_ctgr"		=>(string)$result[$j]->c_problem_ctgr,
											 "n_problem_ctgr"		=>$n_problem_ctgr[0],
											 "n_peg"				=>$n_peg[0],
											 "c_status_perbaikan"	=>(string)$result[$j]->c_status_perbaikan,
											 "c_barang_perbaikan"	=>(string)$result[$j]->c_barang_perbaikan,
											 "e_saran_sparepart"	=>(string)$result[$j]->e_saran_sparepart,
											 "e_saran_pihakketiga"	=>(string)$result[$j]->e_saran_pihakketiga,
											 "i_disposisi"			=>(string)$result[$j]->i_disposisi
											 );
				}
							 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	//add 09 juli 08 =================== by nip & status
	public function getLaporanProblemTiByNipStCetak($nip,$status) {
	    
		try {
			$where[] = $nip;
			$where[] = $status;
			$where[] = $nip;
			$where[] = $status;
			
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
						    
				$xLimit=$itemPerPage;
			    $xOffset=($pageNumber-1)*$itemPerPage;
					$result = $db->fetchAll("Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										'' as c_problem_ctgr,
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan,
										c_barang_perbaikan,
										e_saran_sparepart,
										e_saran_pihakketiga,
										i_disposisi
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and i_peg_helpdesk like ?
										and c_status_perbaikan like ? 
										UNION
										SELECT  
										i_barang_ajuanbaik,
										d_barang_ajuanbaik,
										null as d_anggaran,
										null as c_barang,
										null as i_aset,
										i_peg_nip as i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk,
										e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, 
										d_problem_awal,
										c_status_perbaikan,
										'' as c_barang_perbaikan,
										'' as e_saran_sparepart,
										'' as e_saran_pihakketiga,
										'' as i_disposisi
										FROM e_ast_problemti_0_tm a
										where i_peg_nip like ?
										and c_status_perbaikan like ? 
										order by d_barang_ajuanbaik",$where);
				
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
					$n_problem_ctgr		= $db->fetchCol('select n_problem_ctgr  
														from  e_ast_kategori_problemti_tm 
														where c_problem_ctgr = ? ',$result[$j]->c_problem_ctgr);	
					
					$n_peg 				= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_helpdesk);	
														
					$hasilAkhir[$j]  = array("i_barang_ajuanbaik"		=>(string)$result[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" 	=>(string)$result[$j]->d_barang_ajuanbaik,
											 "i_orgb"				=>(string)$result[$j]->i_orgb,
											 "i_barang_penerimaan"	=>(string)$result[$j]->i_barang_penerimaan,
											 "d_anggaran"			=>(string)$result[$j]->d_anggaran,
											 "c_barang"				=>(string)$result[$j]->c_barang,
											 "i_aset"				=>(string)$result[$j]->i_aset,
											 "i_peg_nip"			=>(string)$result[$j]->i_peg_nippemohon,
											 "i_peg_helpdesk"		=>(string)$result[$j]->i_peg_helpdesk,
											 "d_perolehan"			=>(string)$result[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$result[$j]->e_barang_perbaikan,
											 "c_problem_ctgr"		=>(string)$result[$j]->c_problem_ctgr,
											 "n_problem_ctgr"		=>$n_problem_ctgr[0],
											 "n_peg"				=>$n_peg[0],
											 "c_status_perbaikan"	=>(string)$result[$j]->c_status_perbaikan,
											 "c_barang_perbaikan"	=>(string)$result[$j]->c_barang_perbaikan,
											 "e_saran_sparepart"	=>(string)$result[$j]->e_saran_sparepart,
											 "e_saran_pihakketiga"	=>(string)$result[$j]->e_saran_pihakketiga,
											 "i_disposisi"			=>(string)$result[$j]->i_disposisi
											 );
				}
							 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	//Pelaporan Rekap Aset TI
	
	public function getLaporanRekapAsetTIByJnsBrg($pageNumber,$itemPerPage,$kdBarang) 
	{   
		$kdBarang = strtoupper($kdBarang);
		//$$kdBarang = '%'.$$kdBarang.'%';
		//$kdBarang = '2120102001'; //contoh utk P.C UNIT
	   
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
	     
		 $where[] = $kdBarang;
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$result = $db->fetchAll("SELECT distinct to_char(tgl_perlh,'yyyy') as thn_ang								
										FROM e_sabm_t_master_tm G, e_ast_sskel_0_tr H
												where substr(g.kd_brg,1,1) = h.kd_gol
												and substr(g.kd_brg,2,2) = h.kd_bid 
												and substr(g.kd_brg,4,2) = h.kd_kel
												and substr(g.kd_brg,6,2) = h.kd_skel
												and substr(g.kd_brg,8,3) = h.kd_sskel
												and kd_brg =? ",$where); 
			 
			 $hasilAkhir = count($result);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT distinct to_char(tgl_perlh,'yyyy') as thn_ang								
										FROM e_sabm_t_master_tm G, e_ast_sskel_0_tr H
												where substr(g.kd_brg,1,1) = h.kd_gol
												and substr(g.kd_brg,2,2) = h.kd_bid 
												and substr(g.kd_brg,4,2) = h.kd_kel
												and substr(g.kd_brg,6,2) = h.kd_skel
												and substr(g.kd_brg,8,3) = h.kd_sskel
												and kd_brg =?	
											limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
														
				$total 			= $db->fetchCol("SELECT count(*) as total from e_sabm_t_master_tm A 
													WHERE kd_brg = '$kdBarang'
													and to_char(A.tgl_perlh,'YYYY') = ?", $result[$j]->thn_ang);
				
				$rusak 			= $db->fetchCol("SELECT count(*) as rusak from e_sabm_t_master_tm A,  e_ast_ajuanbaikti_0_tm C,
													e_ast_ajuanbaikti_item_tm D 
													 WHERE  A.kd_brg = '$kdBarang'
												     AND A.kd_brg = C.c_barang 
													 and A.no_aset = C.i_aset
													 AND A.tgl_perlh = C.d_perolehan                
													 AND (c_status_perbaikan != 'Y' or c_status_perbaikan is null)
													 and c.i_barang_ajuanbaik=d.i_barang_ajuanbaik
													 and to_char(A.tgl_perlh,'YYYY') = ?",$result[$j]->thn_ang);
				
				$hasilAkhir[$j] = array("periode"            		=>(string)$result[$j]->thn_ang,
										 "total"         			=>$total[0],
										 "rusak"         			=>$rusak[0],
										 "baik"         			=>$total[0] - $rusak[0]);
									
			 }	
		}	 
	     return $hasilAkhir;
	   }  catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function getLaporanRekapAsetTIByThn($pageNumber,$itemPerPage,$thn) 
	{   
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
	     
		 $where[] = $thn;
		 
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$result = $db->fetchAll("SELECT distinct a.kd_brg, b.ur_sskel 
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and (kd_brg like '2120102%'  or  kd_brg like '2120203%') 
											
											UNION
											SELECT distinct a.kd_brg, b.ur_sskel 
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and (kd_brg like '2120204001%'  or  kd_brg like '2060101048%') 
											
											UNION
											SELECT distinct a.kd_brg, b.ur_sskel 
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and kd_gol = '2'
											and kd_bid = '12'
											and kd_kel = '02'
											and kd_skel = '04'
											and kd_sskel != '001'
										   	and to_char(tgl_perlh,'yyyy') =? ",$where); 
			 
			 $hasilAkhir = count($result);
			
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT distinct a.kd_brg, b.ur_sskel 
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and (kd_brg like '2120102%'  or  kd_brg like '2120203%') 
											
											UNION
											SELECT distinct a.kd_brg, b.ur_sskel 
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and (kd_brg like '2120204001%'  or  kd_brg like '2060101048%') 
											UNION
											SELECT distinct a.kd_brg, b.ur_sskel 
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and kd_gol = '2'
											and kd_bid = '12'
											and kd_kel = '02'
											and kd_skel = '04'
											and kd_sskel != '001'
										   	and to_char(tgl_perlh,'yyyy') =?	
											ORDER BY ur_sskel
											limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
														
				$total 			= $db->fetchCol("SELECT count(*) as total from e_sabm_t_master_tm A 
													WHERE to_char(A.tgl_perlh,'YYYY') = $thn
													and kd_brg = ? ",$result[$j]->kd_brg);
				
				$rusak 			= $db->fetchCol("SELECT count(*) as rusak from e_sabm_t_master_tm A,  e_ast_ajuanbaikti_0_tm C,
													e_ast_ajuanbaikti_item_tm D 
													 WHERE  to_char(A.tgl_perlh,'YYYY') = $thn
												     AND A.kd_brg = C.c_barang 
													 and A.no_aset = C.i_aset
													 AND A.tgl_perlh = C.d_perolehan                
													 AND (c_status_perbaikan != 'Y' or c_status_perbaikan is null)
													 and c.i_barang_ajuanbaik=d.i_barang_ajuanbaik
													 and kd_brg = ?",$result[$j]->kd_brg);
				
				$hasilAkhir[$j] = array("kd_brg"         =>(string)$result[$j]->kd_brg,
										 "ur_sskel"      =>(string)$result[$j]->ur_sskel,
										 "total"         =>$total[0],
										 "rusak"         =>$rusak[0],
										 "baik"          =>$total[0] - $rusak[0]);
									
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	//Pelaporan Rekap Problem TI By Kategori Problem===
	public function getLaporanRekapProblemTIByKat($pageNumber,$itemPerPage,$prdAwal,$prdAkhir) {
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$result = $db->fetchOne("Select 
											c.c_problem_ctgr,
											n_problem_ctgr
											from E_AST_AJUANBAIKTI_0_TM C, e_ast_ajuanbaikti_item_tm D 
											, e_ast_kategori_problemti_tm  E
											WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
											and c.c_problem_ctgr=e.c_problem_ctgr 
											and  d_problem_awal   between ? and ?
											UNION
											SELECT  distinct
											a.c_problem_ctgr,b.n_problem_ctgr
											FROM e_ast_problemti_0_tm a, e_ast_kategori_problemti_tm  b
											where a.c_problem_ctgr=b.c_problem_ctgr 
											and	d_problem_awal   between ? and ?",$where);
											
				$hasilAkhir = count($result);
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				$result = $db->fetchAll("Select 
											c.c_problem_ctgr,
											n_problem_ctgr,
											c_jenis_problem
											from E_AST_AJUANBAIKTI_0_TM C, e_ast_ajuanbaikti_item_tm D 
											, e_ast_kategori_problemti_tm  E
											WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
											and c.c_problem_ctgr=e.c_problem_ctgr 
											and  d_problem_awal between ? and ?
											UNION
											SELECT  distinct
											a.c_problem_ctgr,b.n_problem_ctgr,c_jenis_problem
											FROM e_ast_problemti_0_tm a, e_ast_kategori_problemti_tm  b
											where a.c_problem_ctgr=b.c_problem_ctgr 
											and	d_problem_awal between ? and ?
											order by c_problem_ctgr
											limit $xLimit offset $xOffset",$where);
				$jmlResult = count($result);
		
				if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {
						
						$c_problem_ctgr = $result[$j]->c_problem_ctgr;
						$c_jenis_problem = $result[$j]->c_jenis_problem;
						
						if($c_jenis_problem =='U'){
						    $n_jenis_problem = 'User';
							$total 			= $db->fetchCol("SELECT count(*) as total 
															from E_AST_AJUANBAIKTI_0_TM C, e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
															and (d_problem_awal between '$prdAwal' and '$prdAkhir')
															and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
							
							$belumSelesai 	= $db->fetchCol("SELECT count(*)  as belumSelesai from e_ast_ajuanbaikti_0_tm C, 
													     	 e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
															AND (D.c_status_perbaikan  != 'Y' or D.c_status_perbaikan  isnull)
														    and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
															and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
							
							$Selesai	 	= $db->fetchCol("SELECT count(*) as Selesai from e_ast_ajuanbaikti_0_tm C,
													     	e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik 
													     	AND D.c_status_perbaikan  = 'Y'
															and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
															and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);

							
														  
						 }
						 else{
						    $n_jenis_problem = 'Sistem';
							$total 			= $db->fetchCol("SELECT count(*) as total from e_ast_problemti_0_tm 
														WHERE (d_problem_awal between '$prdAwal' and '$prdAkhir')
														and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
							
							$belumSelesai 	= $db->fetchCol("SELECT count(*)  as belumSelesai from e_ast_problemti_0_tm 
														WHERE (c_status_perbaikan  != 'Y' or c_status_perbaikan  is null)
													    and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
														and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
							
							$Selesai 		= $db->fetchCol("SELECT count(*) as Selesai from e_ast_problemti_0_tm 
														  WHERE c_status_perbaikan ='Y'
														  and (d_problem_awal between '$prdAwal' and '$prdAkhir')
														  and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
						}
						
						
							$hasilAkhir[$j] = array("c_problem_ctgr"    =>(string)$result[$j]->c_problem_ctgr,
													 "n_problem_ctgr"   =>(string)$result[$j]->n_problem_ctgr,
													 "n_jenis_problem"   =>$n_jenis_problem,
													 "total"         	=>$total[0],
													 "belumSelesai"    	=>$belumSelesai[0],
													 "Selesai"          =>$Selesai[0]);
										 
					}
				}
			}		 
			return $hasilAkhir;
		} catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
		}
	}
	
	//cetak
	public function getLaporanRekapProblemTIByKatCetak($prdAwal,$prdAkhir) {
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			
				$result = $db->fetchAll("Select 
											'0' as c_problem_ctgr,
											'Perbaikan' as n_problem_ctgr
											from E_AST_AJUANBAIKTI_0_TM C, e_ast_ajuanbaikti_item_tm D 
											WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
											and  d_problem_awal  between ? and ?
											UNION
											SELECT  distinct
											a.c_problem_ctgr,b.n_problem_ctgr
											FROM e_ast_problemti_0_tm a, e_ast_kategori_problemti_tm  b
											where a.c_problem_ctgr=b.c_problem_ctgr 
											and	d_problem_awal  between ? and ?
											order by c_problem_ctgr",$where);
				$jmlResult = count($result);
				
				if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {
						
						$c_problem_ctgr = $result[$j]->c_problem_ctgr;
						
						if($c_problem_ctgr =='0'){
							$total 			= $db->fetchCol("SELECT count(*) as total 
															from E_AST_AJUANBAIKTI_0_TM C, e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
															and	(d_problem_awal between '$prdAwal' and '$prdAkhir')");
							
							$belumSelesai 	= $db->fetchCol("SELECT count(*)  as belumSelesai from e_ast_ajuanbaikti_0_tm C, 
													     	 e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
															AND (D.c_status_perbaikan  != 'Y' or D.c_status_perbaikan  isnull)
														    and	(d_problem_awal between '$prdAwal' and '$prdAkhir')");
							
							$Selesai	 	= $db->fetchCol("SELECT count(*) as Selesai from e_ast_ajuanbaikti_0_tm C,
													     	e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik 
													     	AND D.c_status_perbaikan  = 'Y'
															and	(d_problem_awal between '$prdAwal' and '$prdAkhir')");

							
														  
						 }
						 else{
							$total 			= $db->fetchCol("SELECT count(*) as total from e_ast_problemti_0_tm 
														WHERE (d_problem_awal between '$prdAwal' and '$prdAkhir')
														and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
							
							$belumSelesai 	= $db->fetchCol("SELECT count(*)  as belumSelesai from e_ast_problemti_0_tm 
														WHERE (c_status_perbaikan  != 'Y' or c_status_perbaikan  is null)
													    and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
														and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
							
							$Selesai 		= $db->fetchCol("SELECT count(*) as Selesai from e_ast_problemti_0_tm 
														WHERE c_status_perbaikan ='Y'
														and (d_problem_awal between '$prdAwal' and '$prdAkhir')
														and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
						}
						
						
							$hasilAkhir[$j] = array("c_problem_ctgr"    =>(string)$result[$j]->c_problem_ctgr,
													 "n_problem_ctgr"   =>(string)$result[$j]->n_problem_ctgr,
													 "total"         	=>$total[0],
													 "belumSelesai"    	=>$belumSelesai[0],
													 "Selesai"          =>$Selesai[0]);
										 
					}
				}
				 
			return $hasilAkhir;
		} catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
		}
	}
	
	
	//Pelaporan By Unit Kerja
	public function getLaporanRekapProblemTIByUnit($pageNumber,$itemPerPage,$prdAwal,$prdAkhir) {
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		    
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			
				$result = $db->fetchAll("Select 
											distinct A.i_orgb, B.n_orgb 
											from E_AST_AJUANBAIKTI_0_TM A, e_org_0_0_tm B,e_ast_ajuanbaikti_item_tm D
											where A.i_orgb = B.i_orgb
											and D.i_barang_ajuanbaik = A.i_barang_ajuanbaik
											and  (d_problem_awal  between ? and ?)
											UNION
											SELECT  distinct A.i_orgb, B.n_orgb 
											FROM e_ast_problemti_0_tm A, e_org_0_0_tm B
											where A.i_orgb = B.i_orgb
											and	(d_problem_awal  between ? and ?)
											UNION
											SELECT  distinct A.i_peg_nippemohon, B.n_bumn
											FROM e_ast_problemti_0_tm A, a_bumn_0_0_tr b
											where A.i_peg_nippemohon = B.i_bumn
											and	(d_problem_awal  between ? and ?)
											order by i_orgb",$where);
			//								limit $xLimit offset $xOffset",$where);
				$jmlResult = count($result);
				
				if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {
						
							$total 			= $db->fetchCol("SELECT count(*) as total from e_ast_problemti_0_tm 
														WHERE (d_problem_awal between  '$prdAwal' and '$prdAkhir')
														and i_orgb = ? ",$result[$j]->i_orgb);
							
							$belumSelesai 	= $db->fetchCol("SELECT count(*)  as belumSelesai from e_ast_problemti_0_tm 
														WHERE (c_status_perbaikan  != 'Y' or c_status_perbaikan  is null)
													    and (d_problem_awal between  '$prdAwal' and '$prdAkhir')
														and i_orgb = ? ",$result[$j]->i_orgb);
							
							$Selesai 		= $db->fetchCol("SELECT count(*) as Selesai from e_ast_problemti_0_tm 
														  WHERE c_status_perbaikan ='Y'
															and (d_problem_awal between  '$prdAwal' and '$prdAkhir')
															and i_orgb = ? ",$result[$j]->i_orgb);
						
							$total2 			= $db->fetchCol("SELECT count(*) as total 
															from E_AST_AJUANBAIKTI_0_TM C, e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
															and (d_problem_awal between  '$prdAwal' and '$prdAkhir')
															and i_orgb = ? ",$result[$j]->i_orgb);
							
							$belumSelesai2 	= $db->fetchCol("SELECT count(*)  as belumSelesai from e_ast_ajuanbaikti_0_tm C, 
													     	 e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
															AND (D.c_status_perbaikan  != 'Y' or D.c_status_perbaikan  isnull)
														    and (d_problem_awal between  '$prdAwal' and '$prdAkhir')
															and i_orgb = ? ",$result[$j]->i_orgb);
							
							$Selesai2	 	= $db->fetchCol("SELECT count(*) as Selesai from e_ast_ajuanbaikti_0_tm C,
													     	e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik 
													     	AND D.c_status_perbaikan  = 'Y'
															and (d_problem_awal between  '$prdAwal' and '$prdAkhir')
															and i_orgb = ? ",$result[$j]->i_orgb);
															
							$hasilAkhir[$j] = array("i_orgb"    		=>(string)$result[$j]->i_orgb,
													 "n_orgb"   		=>(string)$result[$j]->n_orgb,
													 "total"         	=>$total[0]+$total2[0],
													 "belumSelesai"    	=>$belumSelesai[0]+$belumSelesai2[0],
													 "Selesai"          =>$Selesai[0]+$Selesai2[0]);
							
					}
				}
			//}		 
			
			return $hasilAkhir;
		} catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
		}
	}
	
	public function getLaporanRekapProblemTIByUnitKat($pageNumber,$itemPerPage,$prdAwal,$prdAkhir) {
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$result = $db->fetchOne("Select 
											distinct A.i_orgb, B.n_orgb,
											a.c_problem_ctgr,
											n_problem_ctgr,
											c_jenis_problem
											from E_AST_AJUANBAIKTI_0_TM A, e_org_0_0_tm B, 
											e_ast_kategori_problemti_tm  E,e_ast_ajuanbaikti_item_tm D
											where A.i_orgb = B.i_orgb
											and D.i_barang_ajuanbaik = A.i_barang_ajuanbaik
											and a.c_problem_ctgr=e.c_problem_ctgr 
											and  d_problem_awal  between ? and ?
											UNION
											SELECT  distinct A.i_orgb, B.n_orgb ,
											A.c_problem_ctgr,
											n_problem_ctgr,
											c_jenis_problem
											FROM e_ast_problemti_0_tm A, e_org_0_0_tm B, e_ast_kategori_problemti_tm  C
											where A.i_orgb = B.i_orgb
											and A.c_problem_ctgr=C.c_problem_ctgr 
											and	d_problem_awal  between ? and ?",$where);
											
				$hasilAkhir = count($result);
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				$result = $db->fetchAll("Select 
											distinct A.i_orgb, B.n_orgb,
											a.c_problem_ctgr,
											n_problem_ctgr,
											c_jenis_problem
											from E_AST_AJUANBAIKTI_0_TM A, e_org_0_0_tm B, 
											e_ast_kategori_problemti_tm  E,e_ast_ajuanbaikti_item_tm D
											where A.i_orgb = B.i_orgb
											and D.i_barang_ajuanbaik = A.i_barang_ajuanbaik
											and a.c_problem_ctgr=e.c_problem_ctgr 
											and  d_problem_awal  between ? and ?
											UNION
											SELECT  distinct A.i_orgb, B.n_orgb ,
											A.c_problem_ctgr,
											n_problem_ctgr,
											c_jenis_problem
											FROM e_ast_problemti_0_tm A, e_org_0_0_tm B, e_ast_kategori_problemti_tm  C
											where A.i_orgb = B.i_orgb
											and A.c_problem_ctgr=C.c_problem_ctgr 
											and	d_problem_awal  between ? and ?
											order by i_orgb
											limit $xLimit offset $xOffset",$where);
											
				$jmlResult = count($result);
				
				if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {
					
					$c_problem_ctgr = $result[$j]->c_problem_ctgr;
					
						$where = $result[$j]->i_orgb;
						
							$total 			= $db->fetchCol("SELECT count(*) as total from e_ast_problemti_0_tm 
														WHERE (d_problem_awal between '$prdAwal' and '$prdAkhir')
														and i_orgb = ? ",$result[$j]->i_orgb);
							
							$belumSelesai 	= $db->fetchCol("SELECT count(*)  as belumSelesai from e_ast_problemti_0_tm 
														WHERE (c_status_perbaikan  != 'Y' or c_status_perbaikan  is null)
													    and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
														and i_orgb = ? ",$result[$j]->i_orgb);
							
							$Selesai 		= $db->fetchCol("SELECT count(*) as Selesai from e_ast_problemti_0_tm 
														WHERE c_status_perbaikan ='Y'
														and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
														and i_orgb = ? ",$result[$j]->i_orgb);
						
							$total2 			= $db->fetchCol("SELECT count(*) as total 
															from E_AST_AJUANBAIKTI_0_TM C, e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
															and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
															and i_orgb = ? ",$result[$j]->i_orgb);
							
							$belumSelesai2 	= $db->fetchCol("SELECT count(*)  as belumSelesai from e_ast_ajuanbaikti_0_tm C, 
													     	 e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
															AND (D.c_status_perbaikan  != 'Y' or D.c_status_perbaikan  isnull)
														    and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
															and i_orgb = ? ",$result[$j]->i_orgb);
							
							$Selesai2	 	= $db->fetchCol("SELECT count(*) as Selesai from e_ast_ajuanbaikti_0_tm C,
													     	e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik 
													     	AND D.c_status_perbaikan  = 'Y'
															and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
															and i_orgb = ? ",$result[$j]->i_orgb);
															
							$hasilAkhir[$j] = array("i_orgb"    		=>(string)$result[$j]->i_orgb,
													 "n_orgb"   		=>(string)$result[$j]->n_orgb,
													 "c_problem_ctgr"   =>(string)$result[$j]->c_problem_ctgr,
													 "n_problem_ctgr"   =>(string)$result[$j]->n_problem_ctgr,
													 "total"         	=>$total[0]+$total2[0],
													 "belumSelesai"    	=>$belumSelesai[0]+$belumSelesai2[0],
													 "Selesai"          =>$Selesai[0]+$Selesai2[0]);
										 
					}
				}
			}		 
			return $hasilAkhir;
		} catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
		}
	}
	
	
	
	public function getLaporanRekapAsetTIByJnsBrg_Old($pageNumber,$itemPerPage,$kdBarang) 
	{   
	
	$registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     	
				$j = 0;
				$thnNow = date("Y");
				for ($s= 1995; $s <= $thnNow;$j++){
				$thnAwal = $s;
					$thnAkhir = $s+1;	
				        if  ($thnAkhir<= $thnNow)
				       {
							$result1 = $db->fetchAll("SELECT '$thnAwal - $thnAkhir ' as periodePerolehan,  
													(SELECT count(*) from e_sabm_t_master_tm A 
													WHERE A.kd_brg = '2120102001'
													and to_char(A.tgl_perlh,'YYYY') between $thnAwal and $thnAkhir) as total,
													(select 
												 	(SELECT count(*) from e_sabm_t_master_tm A,  e_ast_ajuanbaikti_0_tm C 
													 WHERE  A.kd_brg = '2120102001'
												                And to_char(A.tgl_perlh,'YYYY') between $thnAwal and $thnAkhir 
													 AND A.kd_brg = C.c_barang 
													 and A.no_aset = C.i_aset
													 AND A.tgl_perlh = C.d_perolehan                
													 AND c_barang_perbaikan  != 'Y')
													 )as rusak");

							$jmlresult1 = count($result1);						
							$baik = 	$result1[0]->total - $result1[0]->rusak;

							$hasilAkhir[$j] = array("periode"  		=>(string)$result1[$j]->periodePerolehan,
									 					"total"  	=>(string)$result1[$j]->total,
														"rusak"  	=>(string)$result1[$j]->rusak,
														"baik"  	=>$baik);
														
							// $j++;
					}
				}
		 return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function getUnitKerjaList() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchAll('SELECT i_orgb,n_orgb FROM e_org_0_0_tm ORDER BY i_orgb');
	     return $result;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getNamaBarang($pageNumber,$itemPerPage) {
	   //$namaBarang = strtoupper($namaBarang);
	   //$nbrg = '%'.$namaBarang.'%';
	   //$nbrg = $namaBarang.'%';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			//$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_sskel_0_tr ",$nbrg);
			
			$result = $db->fetchAll("SELECT distinct a.kd_brg, b.ur_sskel 
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and (kd_brg like '2120102%'  or  kd_brg like '2120203%') 

											UNION
											SELECT distinct a.kd_brg, b.ur_sskel 
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and (kd_brg like '2120204001%'  or  kd_brg like '2060101048%') 

											UNION
											SELECT distinct a.kd_brg, b.ur_sskel 
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and kd_gol = '2'
											and kd_bid = '12'
											and kd_kel = '02'
											and kd_skel = '04'
											and kd_sskel != '001'"); 
											
			$hasilAkhir =count($result);
										   
		 }
		 else
		 {
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT distinct a.kd_brg, b.ur_sskel 
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and (kd_brg like '2120102%'  or  kd_brg like '2120203%') 

											UNION
											SELECT distinct a.kd_brg, b.ur_sskel 
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and (kd_brg like '2120204001%'  or  kd_brg like '2060101048%') 

											UNION
											SELECT distinct a.kd_brg, b.ur_sskel 
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and kd_gol = '2'
											and kd_bid = '12'
											and kd_kel = '02'
											and kd_skel = '04'
											and kd_sskel != '001'

										   ORDER BY ur_sskel
										   limit $xLimit offset $xOffset"); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("kd_brg"        =>(string)$result[$j]->kd_brg,
										   "ur_sskel"   =>(string)$result[$j]->ur_sskel);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
	
	//Laporan permasalahan si ==============================================
	
	public function getPermasalahanSiAll($pageNumber,$itemPerPage)
	{
		try {
			
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasil = $db->fetchAll("Select a.i_barang_ajuanbaik,a.d_barang_ajuanbaik,a.d_anggaran,
										a.c_barang,to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,i_peg_helpdesk,
										a.e_barang_perbaikan, a.c_problem_ctgr,n_problem_ctgr, c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,c_status_perbaikan,
										c_barang_perbaikan, e_saran_sparepart, e_saran_pihakketiga,
										i_disposisi, i_orgb,e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan as tglselesai, q_jam_perbaikan, '' as n_peg_bumn
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B, E_AST_KATEGORI_PROBLEMTI_TM C
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										And A.c_problem_ctgr = c.c_problem_ctgr										
										UNION
										SELECT i_barang_ajuanbaik, d_barang_ajuanbaik, null as d_anggaran,
										null as c_barang, null as i_aset,i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk, e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, n_problem_ctgr, c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,
										c_status_perbaikan,'' as c_barang_perbaikan,'' as e_saran_sparepart,
										'' as e_saran_pihakketiga, '' as i_disposisi, i_orgb,e_problem_solusi as tindakan, 
										d_akhir_perbaikan as tglselesai, null as q_jam_perbaikan, n_peg_bumn
										FROM e_ast_problemti_0_tm a,E_AST_KATEGORI_PROBLEMTI_TM C
										WHERE A.c_problem_ctgr = c.c_problem_ctgr	
										UNION
										SELECT i_barang_ajuanbaik, d_barang_ajuanbaik, null as d_anggaran,
										null as c_barang, null as i_aset,i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk, e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, '' as n_problem_ctgr, '' as c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,
										c_status_perbaikan,'' as c_barang_perbaikan,'' as e_saran_sparepart,
										'' as e_saran_pihakketiga, '' as i_disposisi, i_orgb,e_problem_solusi as tindakan, 
										d_akhir_perbaikan as tglselesai, null as q_jam_perbaikan, n_peg_bumn
										FROM e_ast_problemti_0_tm a										
										WHERE  not EXISTS(select c.c_problem_ctgr 
										                 from E_AST_KATEGORI_PROBLEMTI_TM C
										                 where A.c_problem_ctgr = c.c_problem_ctgr)
										UNION
										Select a.i_barang_ajuanbaik,a.d_barang_ajuanbaik,a.d_anggaran,
										a.c_barang,to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,i_peg_helpdesk,
										a.e_barang_perbaikan, a.c_problem_ctgr, '' as n_problem_ctgr, '' as c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,c_status_perbaikan,
										c_barang_perbaikan, e_saran_sparepart, e_saran_pihakketiga,
										i_disposisi, i_orgb,e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan as tglselesai, q_jam_perbaikan, '' as n_peg_bumn
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and not EXISTS(select c.c_problem_ctgr 
										                 from E_AST_KATEGORI_PROBLEMTI_TM C
										                 where A.c_problem_ctgr = c.c_problem_ctgr)
										order by d_barang_ajuanbaik 
										")	;
				$hasilList = count($hasil);
			}
			else 
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				$setuju = $db->fetchAll("Select a.i_barang_ajuanbaik,a.d_barang_ajuanbaik,a.d_anggaran,
										a.c_barang,to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,i_peg_helpdesk,
										a.e_barang_perbaikan, a.c_problem_ctgr,n_problem_ctgr, c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,c_status_perbaikan,
										c_barang_perbaikan, e_saran_sparepart, e_saran_pihakketiga,
										i_disposisi, i_orgb,e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan as tglselesai, q_jam_perbaikan,'' as n_peg_bumn
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B, E_AST_KATEGORI_PROBLEMTI_TM C
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										And A.c_problem_ctgr = c.c_problem_ctgr										
										UNION
										SELECT i_barang_ajuanbaik, d_barang_ajuanbaik, null as d_anggaran,
										null as c_barang, null as i_aset,i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk, e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, n_problem_ctgr, c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,
										c_status_perbaikan,'' as c_barang_perbaikan,'' as e_saran_sparepart,
										'' as e_saran_pihakketiga, '' as i_disposisi, i_orgb,e_problem_solusi as tindakan, 
										d_akhir_perbaikan as tglselesai, null as q_jam_perbaikan, n_peg_bumn
										FROM e_ast_problemti_0_tm a,E_AST_KATEGORI_PROBLEMTI_TM C
										WHERE A.c_problem_ctgr = c.c_problem_ctgr		
										UNION
										SELECT i_barang_ajuanbaik, d_barang_ajuanbaik, null as d_anggaran,
										null as c_barang, null as i_aset,i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk, e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, '' as n_problem_ctgr, '' as c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,
										c_status_perbaikan,'' as c_barang_perbaikan,'' as e_saran_sparepart,
										'' as e_saran_pihakketiga, '' as i_disposisi, i_orgb,e_problem_solusi as tindakan, 
										d_akhir_perbaikan as tglselesai, null as q_jam_perbaikan, n_peg_bumn
										FROM e_ast_problemti_0_tm a										
										WHERE  not EXISTS(select c. c_problem_ctgr 
										                 from E_AST_KATEGORI_PROBLEMTI_TM C
										                 where A.c_problem_ctgr = c.c_problem_ctgr)	
                                        UNION
										Select a.i_barang_ajuanbaik,a.d_barang_ajuanbaik,a.d_anggaran,
										a.c_barang,to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,i_peg_helpdesk,
										a.e_barang_perbaikan, a.c_problem_ctgr, '' as n_problem_ctgr, '' as c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,c_status_perbaikan,
										c_barang_perbaikan, e_saran_sparepart, e_saran_pihakketiga,
										i_disposisi, i_orgb,e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan as tglselesai, q_jam_perbaikan, '' as n_peg_bumn
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and not EXISTS(select c.c_problem_ctgr 
										                 from E_AST_KATEGORI_PROBLEMTI_TM C
										                 where A.c_problem_ctgr = c.c_problem_ctgr)														 
										order by d_barang_ajuanbaik 
										limit $xLimit offset $xOffset")	;
				
								
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{					
					
					$n_peg 				= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_helpdesk);	
					
					
					// Awal : Pencari Nama Pemohon dan org Pemohon									
					$n_peg_nippemohon 	= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nippemohon);	
					
					if ($n_peg_nippemohon==null || $n_peg_nippemohon == '')
					{
					     $n_peg_nippemohon 	=  $db->fetchCol('select n_peg_bumn  
														from  e_ast_problemti_0_tm 
														where i_barang_ajuanbaik = ? ',$setuju[$j]->i_barang_ajuanbaik);	
														
					     $n_orgb 	= $db->fetchCol('select n_bumn  
														from  a_bumn_0_0_tr 
														where i_bumn = ? ',$setuju[$j]->i_peg_nippemohon);	
					}	else
					{
					     $n_orgb 			= $db->fetchCol('select n_orgb  
														from  e_org_0_0_tm 
														where i_orgb = ? ',$setuju[$j]->i_orgb);
					}									
					// Akhir  : Pencari Nama Pemohon dan org Pemohon 					
				   
					$n_jabatan 			= $db->fetchCol('select n_jabatan  
														from  e_sdm_jabatan_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nippemohon);					
				
					
														
					$hasilList[$j] = array("i_barang_ajuanbaik"		=>(string)$setuju[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" 	=>(string)$setuju[$j]->d_barang_ajuanbaik,
											 "i_orgb"				=>(string)$setuju[$j]->i_orgb,
											 "i_barang_penerimaan"	=>(string)$setuju[$j]->i_barang_penerimaan,
											 "d_anggaran"			=>(string)$setuju[$j]->d_anggaran,
											 "c_barang"				=>(string)$setuju[$j]->c_barang,
											 "i_aset"				=>(string)$setuju[$j]->i_aset,
											 "i_peg_nip"			=>(string)$setuju[$j]->i_peg_nippemohon,
											 "i_peg_helpdesk"		=>(string)$setuju[$j]->i_peg_helpdesk,
											 "d_perolehan"			=>(string)$setuju[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$setuju[$j]->e_barang_perbaikan,
											 "c_problem_ctgr"		=>(string)$setuju[$j]->c_problem_ctgr,
											 "n_problem_ctgr"		=>(string)$setuju[$j]->n_problem_ctgr,
											 "n_peg"				=>$n_peg[0],
											 "c_status_perbaikan"	=>(string)$setuju[$j]->c_status_perbaikan,
											 "c_barang_perbaikan"	=>(string)$setuju[$j]->c_barang_perbaikan,
											 "e_saran_sparepart"	=>(string)$setuju[$j]->e_saran_sparepart,
											 "e_saran_pihakketiga"	=>(string)$setuju[$j]->e_saran_pihakketiga,
											 "i_disposisi"			=>(string)$setuju[$j]->i_disposisi,
											 "n_peg_nippemohon"		=>$n_peg_nippemohon[0],											 
											 "n_jabatan"			=>$n_jabatan[0],
											 "n_orgb"				=>$n_orgb[0],
											 "tindakan"				=>(string)$setuju[$j]->tindakan,
											 "tglselesai"			=>(string)$setuju[$j]->tglselesai,
											 "q_jam_perbaikan"		=>(string)$setuju[$j]->q_jam_perbaikan,
											 "d_problem_awal"		=>(string)$setuju[$j]->d_problem_awal,
											 "d_awal_perbaikan"		=>(string)$setuju[$j]->d_awal_perbaikan
											 );
				}
			}	
			
			return $hasilList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	//By Periode ==============
	
	public function getPermasalahanSiByPeriode($pageNumber,$itemPerPage,$prdAwal,$prdAkhir)
	{
		try {
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasil = $db->fetchAll("Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										'' as c_problem_ctgr,
										d_problem_awal,
										d_awal_perbaikan,
										c_status_perbaikan,
										c_barang_perbaikan,
										e_saran_sparepart,
										e_saran_pihakketiga,
										i_disposisi,
										i_orgb,
										e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan  as tglselesai,
										q_jam_perbaikan,
										null  as d_problem_awal
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and d_problem_awal between ? and ?
										UNION
										SELECT  
										i_barang_ajuanbaik,
										d_barang_ajuanbaik,
										null as d_anggaran,
										null as c_barang,
										null as i_aset,
										i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk,
										e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, 
										d_problem_awal,
										d_awal_perbaikan,
										c_status_perbaikan,
										'' as c_barang_perbaikan,
										'' as e_saran_sparepart,
										'' as e_saran_pihakketiga,
										'' as i_disposisi,
										i_orgb,
										e_problem_solusi as tindakan,
										d_akhir_perbaikan  as tglselesai,
										null as q_jam_perbaikan,
										d_problem_awal
										FROM e_ast_problemti_0_tm a
										WHERE d_problem_awal between ? and ?
										order by i_barang_ajuanbaik	
										",$where)	;
				$hasilList = count($hasil);
			}
			else 
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				$setuju = $db->fetchAll("Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										'' as c_problem_ctgr,
										d_problem_awal,
										d_awal_perbaikan,
										c_status_perbaikan,
										c_barang_perbaikan,
										e_saran_sparepart,
										e_saran_pihakketiga,
										i_disposisi,
										i_orgb,
										e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan  as tglselesai,
										q_jam_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and d_problem_awal between ? and ?
										UNION
										SELECT  
										i_barang_ajuanbaik,
										d_barang_ajuanbaik,
										null as d_anggaran,
										null as c_barang,
										null as i_aset,
										i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk,
										e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, 
										d_problem_awal,
										d_awal_perbaikan,
										c_status_perbaikan,
										'' as c_barang_perbaikan,
										'' as e_saran_sparepart,
										'' as e_saran_pihakketiga,
										'' as i_disposisi,
										i_orgb,
										e_problem_solusi as tindakan,
										d_akhir_perbaikan  as tglselesai,
										null as q_jam_perbaikan
										FROM e_ast_problemti_0_tm a
										WHERE d_problem_awal between ? and ?
										order by i_barang_ajuanbaik
										limit $xLimit offset $xOffset",$where)	;
				
								
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{
					$n_problem_ctgr		= $db->fetchCol('select n_problem_ctgr  
														from  e_ast_kategori_problemti_tm 
														where c_problem_ctgr = ? ',$setuju[$j]->c_problem_ctgr);	
					
					$n_peg 				= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_helpdesk);	
					
					$n_peg_nippemohon 	= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nippemohon);	
					if ($n_peg_nippemohon==null || $n_peg_nippemohon == '')
					{
					     $n_peg_nippemohon 	= $db->fetchCol('select n_bumn  
														from  a_bumn_0_0_tr 
														where i_bumn = ? ',$setuju[$j]->i_peg_nippemohon);	
					}	
					
					$n_jabatan 			= $db->fetchCol('select n_jabatan  
														from  e_sdm_jabatan_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nippemohon);					
				
					$n_orgb 			= $db->fetchCol('select n_orgb  
														from  e_org_0_0_tm 
														where i_orgb = ? ',$setuju[$j]->i_orgb);
														
					$hasilList[$j] = array("i_barang_ajuanbaik"		=>(string)$setuju[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" 	=>(string)$setuju[$j]->d_barang_ajuanbaik,
											 "i_orgb"				=>(string)$setuju[$j]->i_orgb,
											 "i_barang_penerimaan"	=>(string)$setuju[$j]->i_barang_penerimaan,
											 "d_anggaran"			=>(string)$setuju[$j]->d_anggaran,
											 "c_barang"				=>(string)$setuju[$j]->c_barang,
											 "i_aset"				=>(string)$setuju[$j]->i_aset,
											 "i_peg_nip"			=>(string)$setuju[$j]->i_peg_nippemohon,
											 "i_peg_helpdesk"		=>(string)$setuju[$j]->i_peg_helpdesk,
											 "d_perolehan"			=>(string)$setuju[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$setuju[$j]->e_barang_perbaikan,
											 "c_problem_ctgr"		=>(string)$setuju[$j]->c_problem_ctgr,
											 "n_problem_ctgr"		=>$n_problem_ctgr[0],
											 "n_peg"				=>$n_peg[0],
											 "c_status_perbaikan"	=>(string)$setuju[$j]->c_status_perbaikan,
											 "c_barang_perbaikan"	=>(string)$setuju[$j]->c_barang_perbaikan,
											 "e_saran_sparepart"	=>(string)$setuju[$j]->e_saran_sparepart,
											 "e_saran_pihakketiga"	=>(string)$setuju[$j]->e_saran_pihakketiga,
											 "i_disposisi"			=>(string)$setuju[$j]->i_disposisi,
											 "n_peg_nippemohon"		=>$n_peg_nippemohon[0],
											 "n_jabatan"			=>$n_jabatan[0],
											 "n_orgb"				=>$n_orgb[0],
											 "tindakan"				=>(string)$setuju[$j]->tindakan,
											 "tglselesai"			=>(string)$setuju[$j]->tglselesai,
											 "q_jam_perbaikan"		=>(string)$setuju[$j]->q_jam_perbaikan,
											 "d_problem_awal"		=>(string)$setuju[$j]->d_problem_awal,
											 "d_awal_perbaikan"		=>(string)$setuju[$j]->d_awal_perbaikan
											 );
				}
			}	
			
			return $hasilList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	//Pelaporan By Status
	
	public function getPermasalahanSiByStatus($pageNumber,$itemPerPage,$prdAwal,$prdAkhir,$status)
	{
		$status = strtoupper($status);
		$status = '%'.$status.'%';
		
		try {
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			$where[] = $status;
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			$where[] = $status;
			
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasil = $db->fetchAll("Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										'' as c_problem_ctgr,
										d_problem_awal,
										d_awal_perbaikan,
										c_barang_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and (d_problem_awal between ? and ?)
										and c_status_perbaikan like ?
										UNION
										SELECT  
										i_barang_ajuanbaik,
										d_barang_ajuanbaik,
										null as d_anggaran,
										null as c_barang,
										null as i_aset,
										i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk,
										e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, 
										d_problem_awal,
										d_awal_perbaikan,
										'' as c_barang_perbaikan
										FROM e_ast_problemti_0_tm a
										WHERE (d_problem_awal between ? and ?)
										and c_status_perbaikan like ?
										order by i_barang_ajuanbaik 	
										",$where)	;
				$hasilList = count($hasil);
			}
			else 
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				$setuju = $db->fetchAll("Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										'' as c_problem_ctgr,
										d_problem_awal,
										d_awal_perbaikan,
										c_status_perbaikan,
										c_barang_perbaikan,
										e_saran_sparepart,
										e_saran_pihakketiga,
										i_disposisi,
										i_orgb,
										e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan as tglselesai,
										q_jam_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and (d_problem_awal between ? and ?)
										and c_status_perbaikan like ?
										UNION
										SELECT  
										i_barang_ajuanbaik,
										d_barang_ajuanbaik,
										null as d_anggaran,
										null as c_barang,
										null as i_aset,
										i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk,
										e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, 
										d_problem_awal,
										d_awal_perbaikan,
										c_status_perbaikan,
										'' as c_barang_perbaikan,
										'' as e_saran_sparepart,
										'' as e_saran_pihakketiga,
										'' as i_disposisi,
										i_orgb,
										e_problem_solusi as tindakan,
										d_akhir_perbaikan as tglselesai,
										null as q_jam_perbaikan
										FROM e_ast_problemti_0_tm a
										WHERE (d_problem_awal between ? and ?)
										and c_status_perbaikan like ?
										order by i_barang_ajuanbaik
										limit $xLimit offset $xOffset",$where)	;
				
								
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{
					$n_problem_ctgr		= $db->fetchCol('select n_problem_ctgr  
														from  e_ast_kategori_problemti_tm 
														where c_problem_ctgr = ? ',$setuju[$j]->c_problem_ctgr);	
					
					$n_peg 				= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_helpdesk);	
					
					$n_peg_nippemohon 	= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nippemohon);	
														
					$n_jabatan 			= $db->fetchCol('select n_jabatan  
														from  e_sdm_jabatan_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nippemohon);					
				
					$n_orgb 			= $db->fetchCol('select n_orgb  
														from  e_org_0_0_tm 
														where i_orgb = ? ',$setuju[$j]->i_orgb);
														
					$hasilList[$j] = array("i_barang_ajuanbaik"		=>(string)$setuju[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" 	=>(string)$setuju[$j]->d_barang_ajuanbaik,
											 "i_orgb"				=>(string)$setuju[$j]->i_orgb,
											 "i_barang_penerimaan"	=>(string)$setuju[$j]->i_barang_penerimaan,
											 "d_anggaran"			=>(string)$setuju[$j]->d_anggaran,
											 "c_barang"				=>(string)$setuju[$j]->c_barang,
											 "i_aset"				=>(string)$setuju[$j]->i_aset,
											 "i_peg_nip"			=>(string)$setuju[$j]->i_peg_nippemohon,
											 "i_peg_helpdesk"		=>(string)$setuju[$j]->i_peg_helpdesk,
											 "d_perolehan"			=>(string)$setuju[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$setuju[$j]->e_barang_perbaikan,
											 "c_problem_ctgr"		=>(string)$setuju[$j]->c_problem_ctgr,
											 "n_problem_ctgr"		=>$n_problem_ctgr[0],
											 "n_peg"				=>$n_peg[0],
											 "c_status_perbaikan"	=>(string)$setuju[$j]->c_status_perbaikan,
											 "c_barang_perbaikan"	=>(string)$setuju[$j]->c_barang_perbaikan,
											 "e_saran_sparepart"	=>(string)$setuju[$j]->e_saran_sparepart,
											 "e_saran_pihakketiga"	=>(string)$setuju[$j]->e_saran_pihakketiga,
											 "i_disposisi"			=>(string)$setuju[$j]->i_disposisi,
											 "n_peg_nippemohon"		=>$n_peg_nippemohon[0],
											 "n_jabatan"			=>$n_jabatan[0],
											 "n_orgb"				=>$n_orgb[0],
											 "tindakan"				=>(string)$setuju[$j]->tindakan,
											 "tglselesai"			=>(string)$setuju[$j]->tglselesai,
											 "q_jam_perbaikan"		=>(string)$setuju[$j]->q_jam_perbaikan,
											 "d_problem_awal"		=>(string)$setuju[$j]->d_problem_awal,
											 "d_awal_perbaikan"		=>(string)$setuju[$j]->d_awal_perbaikan
											 );
				}
			}	
			
			return $hasilList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	
//By NIP Helpdesk==========================
	public function getPermasalahanSiByHelpdesk($pageNumber,$itemPerPage,$prdAwal,$prdAkhir,$nipPenerima)
	{
		$nipPenerima = strtoupper($nipPenerima);
		$nipPenerima = '%'.$nipPenerima.'%';
		
		try {
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			$where[] = $nipPenerima;
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			$where[] = $nipPenerima;
			
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasil = $db->fetchAll("Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										'' as c_problem_ctgr,
										d_problem_awal,
										d_awal_perbaikan,
										c_barang_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and (d_problem_awal between ? and ?)
										and upper(i_peg_helpdesk) like ?
										UNION
										SELECT  
										i_barang_ajuanbaik,
										d_barang_ajuanbaik,
										null as d_anggaran,
										null as c_barang,
										null as i_aset,
										i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk,
										e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, 
										d_problem_awal,
										d_awal_perbaikan,
										'' as c_barang_perbaikan
										FROM e_ast_problemti_0_tm a
										WHERE (d_problem_awal between ? and ?)
										and upper(i_peg_nip) like ?
										order by i_barang_ajuanbaik 	
										",$where)	;
				$hasilList = count($hasil);
			}
			else 
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				$setuju = $db->fetchAll("Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										'' as c_problem_ctgr,
										d_problem_awal,
										d_awal_perbaikan,
										c_status_perbaikan,
										c_barang_perbaikan,
										e_saran_sparepart,
										e_saran_pihakketiga,
										i_disposisi,
										i_orgb,
										e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan as tglselesai,
										q_jam_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and (d_problem_awal between ? and ?)
										and upper(i_peg_helpdesk) like ?
										UNION
										SELECT  
										i_barang_ajuanbaik,
										d_barang_ajuanbaik,
										null as d_anggaran,
										null as c_barang,
										null as i_aset,
										i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk,
										e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, 
										d_problem_awal,
										d_awal_perbaikan,
										c_status_perbaikan,
										'' as c_barang_perbaikan,
										'' as e_saran_sparepart,
										'' as e_saran_pihakketiga,
										'' as i_disposisi,
										i_orgb,
										e_problem_solusi as tindakan,
										d_akhir_perbaikan as tglselesai,
										null as q_jam_perbaikan
										FROM e_ast_problemti_0_tm a
										WHERE (d_problem_awal between ? and ?)
										and upper(i_peg_nip) like ?
										order by i_barang_ajuanbaik
										limit $xLimit offset $xOffset",$where)	;
				
								
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{
					$n_problem_ctgr		= $db->fetchCol('select n_problem_ctgr  
														from  e_ast_kategori_problemti_tm 
														where c_problem_ctgr = ? ',$setuju[$j]->c_problem_ctgr);	
					
					$n_peg 				= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_helpdesk);	
					
					$n_peg_nippemohon 	= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nippemohon);	
														
					$n_jabatan 			= $db->fetchCol('select n_jabatan  
														from  e_sdm_jabatan_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nippemohon);					
				
					$n_orgb 			= $db->fetchCol('select n_orgb  
														from  e_org_0_0_tm 
														where i_orgb = ? ',$setuju[$j]->i_orgb);
														
					$hasilList[$j] = array("i_barang_ajuanbaik"		=>(string)$setuju[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" 	=>(string)$setuju[$j]->d_barang_ajuanbaik,
											 "i_orgb"				=>(string)$setuju[$j]->i_orgb,
											 "i_barang_penerimaan"	=>(string)$setuju[$j]->i_barang_penerimaan,
											 "d_anggaran"			=>(string)$setuju[$j]->d_anggaran,
											 "c_barang"				=>(string)$setuju[$j]->c_barang,
											 "i_aset"				=>(string)$setuju[$j]->i_aset,
											 "i_peg_nip"			=>(string)$setuju[$j]->i_peg_nippemohon,
											 "i_peg_helpdesk"		=>(string)$setuju[$j]->i_peg_helpdesk,
											 "d_perolehan"			=>(string)$setuju[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$setuju[$j]->e_barang_perbaikan,
											 "c_problem_ctgr"		=>(string)$setuju[$j]->c_problem_ctgr,
											 "n_problem_ctgr"		=>$n_problem_ctgr[0],
											 "n_peg"				=>$n_peg[0],
											 "c_status_perbaikan"	=>(string)$setuju[$j]->c_status_perbaikan,
											 "c_barang_perbaikan"	=>(string)$setuju[$j]->c_barang_perbaikan,
											 "e_saran_sparepart"	=>(string)$setuju[$j]->e_saran_sparepart,
											 "e_saran_pihakketiga"	=>(string)$setuju[$j]->e_saran_pihakketiga,
											 "i_disposisi"			=>(string)$setuju[$j]->i_disposisi,
											 "n_peg_nippemohon"		=>$n_peg_nippemohon[0],
											 "n_jabatan"			=>$n_jabatan[0],
											 "n_orgb"				=>$n_orgb[0],
											 "tindakan"				=>(string)$setuju[$j]->tindakan,
											 "tglselesai"			=>(string)$setuju[$j]->tglselesai,
											 "q_jam_perbaikan"		=>(string)$setuju[$j]->q_jam_perbaikan,
											 "d_problem_awal"		=>(string)$setuju[$j]->d_problem_awal,
											 "d_awal_perbaikan"		=>(string)$setuju[$j]->d_awal_perbaikan
											 );
				}
			}	
			
			return $hasilList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	//By Helpdesk & Status, Periode===============
	public function getPermasalahanSiByHelpdeskStatus($pageNumber,$itemPerPage,$prdAwal,$prdAkhir,$nipPenerima,$status)
	{
		$nipPenerima = strtoupper($nipPenerima);
		$nipPenerima = '%'.$nipPenerima.'%';
		$status = strtoupper($status);
		$status = '%'.$status.'%';
		
		try {
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			$where[] = $nipPenerima;
			$where[] = $status;
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			$where[] = $nipPenerima;
			$where[] = $status;
			
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasil = $db->fetchAll("Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										'' as c_problem_ctgr,
										d_problem_awal,
										d_awal_perbaikan,
										c_status_perbaikan,
										c_barang_perbaikan,
										e_saran_sparepart,
										e_saran_pihakketiga,
										i_disposisi,
										i_orgb,
										e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan as tglselesai,
										q_jam_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and (d_problem_awal between ? and ?)
										and upper(i_peg_helpdesk) like ?
										and c_status_perbaikan like ?
										UNION
										SELECT  
										i_barang_ajuanbaik,
										d_barang_ajuanbaik,
										null as d_anggaran,
										null as c_barang,
										null as i_aset,
										i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk,
										e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, 
										d_problem_awal,
										d_awal_perbaikan,
										c_status_perbaikan,
										'' as c_barang_perbaikan,
										'' as e_saran_sparepart,
										'' as e_saran_pihakketiga,
										'' as i_disposisi,
										i_orgb,
										e_problem_solusi as tindakan,
										d_akhir_perbaikan as tglselesai,
										null as q_jam_perbaikan
										FROM e_ast_problemti_0_tm a
										WHERE (d_problem_awal between ? and ?)
										and upper(i_peg_nip) like ?
										and c_status_perbaikan like ?
										order by d_barang_ajuanbaik 
										",$where)	;
				$hasilList = count($hasil);
			}
			else 
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				$setuju = $db->fetchAll("Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										'' as c_problem_ctgr,
										d_problem_awal,
										d_awal_perbaikan,
										c_status_perbaikan,
										c_barang_perbaikan,
										e_saran_sparepart,
										e_saran_pihakketiga,
										i_disposisi,
										i_orgb,
										e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan as tglselesai,
										q_jam_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and (d_problem_awal between ? and ?)
										and upper(i_peg_helpdesk) like ?
										and c_status_perbaikan like ?
										UNION
										SELECT  
										i_barang_ajuanbaik,
										d_barang_ajuanbaik,
										null as d_anggaran,
										null as c_barang,
										null as i_aset,
										i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk,
										e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, 
										d_problem_awal,
										d_awal_perbaikan,
										c_status_perbaikan,
										'' as c_barang_perbaikan,
										'' as e_saran_sparepart,
										'' as e_saran_pihakketiga,
										'' as i_disposisi,
										i_orgb,
										e_problem_solusi as tindakan,
										d_akhir_perbaikan as tglselesai,
										null as q_jam_perbaikan
										FROM e_ast_problemti_0_tm a
										WHERE (d_problem_awal between ? and ?)
										and upper(i_peg_nip) like ?
										and c_status_perbaikan like ?
										order by d_barang_ajuanbaik 
										limit $xLimit offset $xOffset",$where)	;
				
								
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{
					$n_problem_ctgr		= $db->fetchCol('select n_problem_ctgr  
														from  e_ast_kategori_problemti_tm 
														where c_problem_ctgr = ? ',$setuju[$j]->c_problem_ctgr);	
					
					$n_peg 				= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_helpdesk);	
					
					$n_peg_nippemohon 	= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nippemohon);	
					
					if ($n_peg_nippemohon==null || $n_peg_nippemohon == '')
					{
					     $n_peg_nippemohon 	= $db->fetchCol('select n_bumn  
														from  a_bumn_0_0_tr 
														where i_bumn = ? ',$setuju[$j]->i_peg_nippemohon);	
					}					
					
														
					$n_jabatan 			= $db->fetchCol('select n_jabatan  
														from  e_sdm_jabatan_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nippemohon);					
				
					$n_orgb 			= $db->fetchCol('select n_orgb  
														from  e_org_0_0_tm 
														where i_orgb = ? ',$setuju[$j]->i_orgb);
														
					$hasilList[$j] = array("i_barang_ajuanbaik"		=>(string)$setuju[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" 	=>(string)$setuju[$j]->d_barang_ajuanbaik,
											 "i_orgb"				=>(string)$setuju[$j]->i_orgb,
											 "i_barang_penerimaan"	=>(string)$setuju[$j]->i_barang_penerimaan,
											 "d_anggaran"			=>(string)$setuju[$j]->d_anggaran,
											 "c_barang"				=>(string)$setuju[$j]->c_barang,
											 "i_aset"				=>(string)$setuju[$j]->i_aset,
											 "i_peg_nip"			=>(string)$setuju[$j]->i_peg_nippemohon,
											 "i_peg_helpdesk"		=>(string)$setuju[$j]->i_peg_helpdesk,
											 "d_perolehan"			=>(string)$setuju[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$setuju[$j]->e_barang_perbaikan,
											 "c_problem_ctgr"		=>(string)$setuju[$j]->c_problem_ctgr,
											 "n_problem_ctgr"		=>$n_problem_ctgr[0],
											 "n_peg"				=>$n_peg[0],
											 "c_status_perbaikan"	=>(string)$setuju[$j]->c_status_perbaikan,
											 "c_barang_perbaikan"	=>(string)$setuju[$j]->c_barang_perbaikan,
											 "e_saran_sparepart"	=>(string)$setuju[$j]->e_saran_sparepart,
											 "e_saran_pihakketiga"	=>(string)$setuju[$j]->e_saran_pihakketiga,
											 "i_disposisi"			=>(string)$setuju[$j]->i_disposisi,
											 "n_peg_nippemohon"		=>$n_peg_nippemohon[0],
											 "n_jabatan"			=>$n_jabatan[0],
											 "n_orgb"				=>$n_orgb[0],
											 "tindakan"				=>(string)$setuju[$j]->tindakan,
											 "tglselesai"			=>(string)$setuju[$j]->tglselesai,
											 "q_jam_perbaikan"		=>(string)$setuju[$j]->q_jam_perbaikan,
											 "d_problem_awal"		=>(string)$setuju[$j]->d_problem_awal,
											 "d_awal_perbaikan"		=>(string)$setuju[$j]->d_awal_perbaikan
											 );
				}
			}	
			
			return $hasilList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	//cetak laporan permasalahan si
	
	public function getLaporanPermasalahanSiAllCetak() {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	  
	   try {
				
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
						    
				$xLimit=$itemPerPage;
			    $xOffset=($pageNumber-1)*$itemPerPage;
					$result = $db->fetchAll("Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										'' as c_problem_ctgr,
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan,
										c_barang_perbaikan,
										e_saran_sparepart,
										e_saran_pihakketiga,
										i_disposisi,
										i_orgb,
										e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan as tglselesai,
										q_jam_perbaikan,
										null  as d_problem_awal
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										UNION
										SELECT  
										i_barang_ajuanbaik,
										d_barang_ajuanbaik,
										null as d_anggaran,
										null as c_barang,
										null as i_aset,
										i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk,
										e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, 
										d_problem_awal,
										c_status_perbaikan,
										'' as c_barang_perbaikan,
										'' as e_saran_sparepart,
										'' as e_saran_pihakketiga,
										'' as i_disposisi,
										i_orgb,
										e_problem_solusi as tindakan,
										d_problem_akhir as tglselesai,
										null as q_jam_perbaikan,
										d_problem_awal
										FROM e_ast_problemti_0_tm a
										order by d_barang_ajuanbaik");
				
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
					
					$n_peg_nippemohon 	= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nippemohon);	
														
					$n_jabatan 			= $db->fetchCol('select n_jabatan  
														from  e_sdm_jabatan_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nippemohon);					
				
					$n_orgb 			= $db->fetchCol('select n_orgb  
														from  e_org_0_0_tm 
														where i_orgb = ? ',$result[$j]->i_orgb);
														
					$n_problem_ctgr		= $db->fetchCol('select n_problem_ctgr  
														from  e_ast_kategori_problemti_tm 
														where c_problem_ctgr = ? ',$result[$j]->c_problem_ctgr);	
					
					$n_peg 				= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_helpdesk);	
														
					$hasilAkhir[$j]  = array("i_barang_ajuanbaik"		=>(string)$result[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" 	=>(string)$result[$j]->d_barang_ajuanbaik,
											 "i_orgb"				=>(string)$result[$j]->i_orgb,
											 "i_barang_penerimaan"	=>(string)$result[$j]->i_barang_penerimaan,
											 "d_anggaran"			=>(string)$result[$j]->d_anggaran,
											 "c_barang"				=>(string)$result[$j]->c_barang,
											 "i_aset"				=>(string)$result[$j]->i_aset,
											 "i_peg_nip"			=>(string)$result[$j]->i_peg_nippemohon,
											 "i_peg_helpdesk"		=>(string)$result[$j]->i_peg_helpdesk,
											 "d_perolehan"			=>(string)$result[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$result[$j]->e_barang_perbaikan,
											 "c_problem_ctgr"		=>(string)$result[$j]->c_problem_ctgr,
											 "n_problem_ctgr"		=>$n_problem_ctgr[0],
											 "n_peg"				=>$n_peg[0],
											 "c_status_perbaikan"	=>(string)$result[$j]->c_status_perbaikan,
											 "c_barang_perbaikan"	=>(string)$result[$j]->c_barang_perbaikan,
											 "e_saran_sparepart"	=>(string)$result[$j]->e_saran_sparepart,
											 "e_saran_pihakketiga"	=>(string)$result[$j]->e_saran_pihakketiga,
											 "i_disposisi"			=>(string)$result[$j]->i_disposisi,
											 "n_peg_nippemohon"		=>$n_peg_nippemohon[0],
											 "n_jabatan"			=>$n_jabatan[0],
											 "n_orgb"				=>$n_orgb[0],
											 "tindakan"				=>(string)$result[$j]->tindakan,
											 "tglselesai"			=>(string)$result[$j]->tglselesai,
											 "q_jam_perbaikan"		=>(string)$result[$j]->q_jam_perbaikan,
											 "d_problem_awal"		=>(string)$result[$j]->d_problem_awal
											 );
				}
							 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function getLaporanPermasalahanSiByPeriodeCetak($prdAwal,$prdAkhir) {
	   
		$where[] = $prdAwal;
		$where[] = $prdAkhir;
		$where[] = $prdAwal;
		$where[] = $prdAkhir;
			
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	  
	   try {
				
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
						    
				$xLimit=$itemPerPage;
			    $xOffset=($pageNumber-1)*$itemPerPage;
					$result = $db->fetchAll("Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										'' as c_problem_ctgr,
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan,
										c_barang_perbaikan,
										e_saran_sparepart,
										e_saran_pihakketiga,
										i_disposisi,
										i_orgb,
										e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan as tglselesai,
										q_jam_perbaikan,
										null  as d_problem_awal
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and  a.d_problem_awal between ? and ?
										
										UNION
										SELECT  
										i_barang_ajuanbaik,
										d_barang_ajuanbaik,
										null as d_anggaran,
										null as c_barang,
										null as i_aset,
										i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk,
										e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, 
										d_problem_awal,
										c_status_perbaikan,
										'' as c_barang_perbaikan,
										'' as e_saran_sparepart,
										'' as e_saran_pihakketiga,
										'' as i_disposisi,
										i_orgb,
										e_problem_solusi as tindakan,
										d_problem_akhir as tglselesai,
										null as q_jam_perbaikan,
										d_problem_awal
										FROM e_ast_problemti_0_tm a
										WHERE a.d_problem_awal between ? and ?
										order by d_barang_ajuanbaik",$where);
				
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
					
					$n_peg_nippemohon 	= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nippemohon);	
														
					$n_jabatan 			= $db->fetchCol('select n_jabatan  
														from  e_sdm_jabatan_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nippemohon);					
				
					$n_orgb 			= $db->fetchCol('select n_orgb  
														from  e_org_0_0_tm 
														where i_orgb = ? ',$result[$j]->i_orgb);
														
					$n_problem_ctgr		= $db->fetchCol('select n_problem_ctgr  
														from  e_ast_kategori_problemti_tm 
														where c_problem_ctgr = ? ',$result[$j]->c_problem_ctgr);	
					
					$n_peg 				= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_helpdesk);	
														
					$hasilAkhir[$j]  = array("i_barang_ajuanbaik"		=>(string)$result[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" 	=>(string)$result[$j]->d_barang_ajuanbaik,
											 "i_orgb"				=>(string)$result[$j]->i_orgb,
											 "i_barang_penerimaan"	=>(string)$result[$j]->i_barang_penerimaan,
											 "d_anggaran"			=>(string)$result[$j]->d_anggaran,
											 "c_barang"				=>(string)$result[$j]->c_barang,
											 "i_aset"				=>(string)$result[$j]->i_aset,
											 "i_peg_nip"			=>(string)$result[$j]->i_peg_nippemohon,
											 "i_peg_helpdesk"		=>(string)$result[$j]->i_peg_helpdesk,
											 "d_perolehan"			=>(string)$result[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$result[$j]->e_barang_perbaikan,
											 "c_problem_ctgr"		=>(string)$result[$j]->c_problem_ctgr,
											 "n_problem_ctgr"		=>$n_problem_ctgr[0],
											 "n_peg"				=>$n_peg[0],
											 "c_status_perbaikan"	=>(string)$result[$j]->c_status_perbaikan,
											 "c_barang_perbaikan"	=>(string)$result[$j]->c_barang_perbaikan,
											 "e_saran_sparepart"	=>(string)$result[$j]->e_saran_sparepart,
											 "e_saran_pihakketiga"	=>(string)$result[$j]->e_saran_pihakketiga,
											 "i_disposisi"			=>(string)$result[$j]->i_disposisi,
											 "n_peg_nippemohon"		=>$n_peg_nippemohon[0],
											 "n_jabatan"			=>$n_jabatan[0],
											 "n_orgb"				=>$n_orgb[0],
											 "tindakan"				=>(string)$result[$j]->tindakan,
											 "tglselesai"			=>(string)$result[$j]->tglselesai,
											 "q_jam_perbaikan"		=>(string)$result[$j]->q_jam_perbaikan,
											 "d_problem_awal"		=>(string)$result[$j]->d_problem_awal
											 );
				}
							 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	//
	public function getPermasalahanSiByHelpdeskStatusCetak($prdAwal,$prdAkhir,$nip,$kdperb) {
	   
		$nipPenerima = strtoupper($nip);
		$nipPenerima = '%'.$nipPenerima.'%';
		$status = strtoupper($kdperb);
		$status = '%'.$status.'%';
		
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			$where[] = $nipPenerima;
			$where[] = $status;
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			$where[] = $nipPenerima;
			$where[] = $status;
			
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	  
	   try {
				
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
						    
				$xLimit=$itemPerPage;
			    $xOffset=($pageNumber-1)*$itemPerPage;
					$result = $db->fetchAll("Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										'' as c_problem_ctgr,
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan,
										c_barang_perbaikan,
										e_saran_sparepart,
										e_saran_pihakketiga,
										i_disposisi,
										i_orgb,
										e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan as tglselesai,
										q_jam_perbaikan,
										null  as d_problem_awal
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and  d_problem_awal between ? and ?
										and upper(i_peg_helpdesk) like ?
										and c_status_perbaikan like ?
										UNION
										SELECT  
										i_barang_ajuanbaik,
										d_barang_ajuanbaik,
										null as d_anggaran,
										null as c_barang,
										null as i_aset,
										i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk,
										e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, 
										d_problem_awal,
										c_status_perbaikan,
										'' as c_barang_perbaikan,
										'' as e_saran_sparepart,
										'' as e_saran_pihakketiga,
										'' as i_disposisi,
										i_orgb,
										e_problem_solusi as tindakan,
										d_problem_akhir as tglselesai,
										null as q_jam_perbaikan,
										d_problem_awal
										FROM e_ast_problemti_0_tm a
										WHERE d_problem_awal between ? and ?
										and upper(i_peg_nip) like ?
										and c_status_perbaikan like ?
										order by d_barang_ajuanbaik",$where);
				
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
					
					$n_peg_nippemohon 	= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nippemohon);	
														
					$n_jabatan 			= $db->fetchCol('select n_jabatan  
														from  e_sdm_jabatan_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nippemohon);					
				
					$n_orgb 			= $db->fetchCol('select n_orgb  
														from  e_org_0_0_tm 
														where i_orgb = ? ',$result[$j]->i_orgb);
														
					$n_problem_ctgr		= $db->fetchCol('select n_problem_ctgr  
														from  e_ast_kategori_problemti_tm 
														where c_problem_ctgr = ? ',$result[$j]->c_problem_ctgr);	
					
					$n_peg 				= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_helpdesk);	
														
					$hasilAkhir[$j]  = array("i_barang_ajuanbaik"		=>(string)$result[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" 	=>(string)$result[$j]->d_barang_ajuanbaik,
											 "i_orgb"				=>(string)$result[$j]->i_orgb,
											 "i_barang_penerimaan"	=>(string)$result[$j]->i_barang_penerimaan,
											 "d_anggaran"			=>(string)$result[$j]->d_anggaran,
											 "c_barang"				=>(string)$result[$j]->c_barang,
											 "i_aset"				=>(string)$result[$j]->i_aset,
											 "i_peg_nip"			=>(string)$result[$j]->i_peg_nippemohon,
											 "i_peg_helpdesk"		=>(string)$result[$j]->i_peg_helpdesk,
											 "d_perolehan"			=>(string)$result[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$result[$j]->e_barang_perbaikan,
											 "c_problem_ctgr"		=>(string)$result[$j]->c_problem_ctgr,
											 "n_problem_ctgr"		=>$n_problem_ctgr[0],
											 "n_peg"				=>$n_peg[0],
											 "c_status_perbaikan"	=>(string)$result[$j]->c_status_perbaikan,
											 "c_barang_perbaikan"	=>(string)$result[$j]->c_barang_perbaikan,
											 "e_saran_sparepart"	=>(string)$result[$j]->e_saran_sparepart,
											 "e_saran_pihakketiga"	=>(string)$result[$j]->e_saran_pihakketiga,
											 "i_disposisi"			=>(string)$result[$j]->i_disposisi,
											 "n_peg_nippemohon"		=>$n_peg_nippemohon[0],
											 "n_jabatan"			=>$n_jabatan[0],
											 "n_orgb"				=>$n_orgb[0],
											 "tindakan"				=>(string)$result[$j]->tindakan,
											 "tglselesai"			=>(string)$result[$j]->tglselesai,
											 "q_jam_perbaikan"		=>(string)$result[$j]->q_jam_perbaikan,
											 "d_problem_awal"		=>(string)$result[$j]->d_problem_awal
											 );
				}
							 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	//
	public function getLaporanRekapAsetTIByThnCetak($tahun) {
	   		
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	  
	   $where[] = $tahun;				
	   
	   try {
				
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
						    
					$result = $db->fetchAll("SELECT distinct a.kd_brg, b.ur_sskel 
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and (kd_brg like '2120102%'  or  kd_brg like '2120203%') 											
											UNION
											SELECT distinct a.kd_brg, b.ur_sskel 
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and (kd_brg like '2120204001%'  or  kd_brg like '2060101048%') 											
											UNION
											SELECT distinct a.kd_brg, b.ur_sskel 
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and kd_gol = '2'
											and kd_bid = '12'
											and kd_kel = '02'
											and kd_skel = '04'
											and kd_sskel != '001'
										   	and to_char(tgl_perlh,'yyyy') =?",$where);
				
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
					
					$total 			= $db->fetchCol("SELECT count(*) as total from e_sabm_t_master_tm A 
													WHERE to_char(A.tgl_perlh,'YYYY') = '$tahun'
													and kd_brg = ? ",$result[$j]->kd_brg);
					$rusak 			= $db->fetchCol("SELECT count(*) as rusak from e_sabm_t_master_tm A,  e_ast_ajuanbaikti_0_tm C,
													e_ast_ajuanbaikti_item_tm D 
													 WHERE  to_char(A.tgl_perlh,'YYYY') = '$tahun'
												     AND A.kd_brg = C.c_barang 
													 and A.no_aset = C.i_aset
													 AND A.tgl_perlh = C.d_perolehan                
													 AND (c_status_perbaikan != 'Y' or c_status_perbaikan is null)
													 and c.i_barang_ajuanbaik=d.i_barang_ajuanbaik
													 and kd_brg = ?",$result[$j]->kd_brg);
				
					$hasilAkhir[$j] = array("kd_brg"         =>(string)$result[$j]->kd_brg,
											 "ur_sskel"      =>(string)$result[$j]->ur_sskel,
											  "total"         =>$total[0],
											  "rusak"         =>$rusak[0],
											  "baik"          =>$total[0] - $rusak[0]);
				}
							 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function getLaporanRekapAsetTIByKatCetak($tahun) {
	   
		$where[] = $tahun;
		
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	  
	   try {
				
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
						    
					$result = $db->fetchAll("SELECT distinct to_char(tgl_perlh,'yyyy') as thn_ang								
										FROM e_sabm_t_master_tm G, e_ast_sskel_0_tr H
												where substr(g.kd_brg,1,1) = h.kd_gol
												and substr(g.kd_brg,2,2) = h.kd_bid 
												and substr(g.kd_brg,4,2) = h.kd_kel
												and substr(g.kd_brg,6,2) = h.kd_skel
												and substr(g.kd_brg,8,3) = h.kd_sskel
												and kd_brg =?",$where);
				
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
					
					$total 			= $db->fetchCol("SELECT count(*) as total from e_sabm_t_master_tm A 
													WHERE kd_brg = '2120102001'
													and to_char(A.tgl_perlh,'YYYY') = ?",$result[$j]->thn_ang);
				
					$rusak 			= $db->fetchCol("SELECT count(*) as rusak from e_sabm_t_master_tm A,  e_ast_ajuanbaikti_0_tm C,
													e_ast_ajuanbaikti_item_tm D 
													 WHERE  A.kd_brg = '2120102001'
												     AND A.kd_brg = C.c_barang 
													 and A.no_aset = C.i_aset
													 AND A.tgl_perlh = C.d_perolehan                
													 AND (c_status_perbaikan != 'Y' or c_status_perbaikan is null)
													 and c.i_barang_ajuanbaik=d.i_barang_ajuanbaik
													 and to_char(A.tgl_perlh,'YYYY') = ?",$result[$j]->thn_ang);
				
					$hasilAkhir[$j] = array("periode"            		=>(string)$result[$j]->thn_ang,
										 "total"         			=>$total[0],
										 "rusak"         			=>$rusak[0],
										 "baik"         			=>$total[0] - $rusak[0]);
				}
							 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	function getNamaPegawai($nip)
	{
		try {
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
		    $where[]=$nip;
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			$result = $db->fetchAll("SELECT a.i_peg_nip,a.n_peg,a.c_unit_kerja
									FROM e_sdm_pegawai_0_tm A
									where a.i_peg_nip = ?	",$where);
									
			$jmlResult = count($result);
			for($j=0; $j<$jmlResult; $j++)
			{
				$n_jabatan 				= $db->fetchCol('select n_jabatan  
														from  e_sdm_jabatan_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nip);	
														
				$hasilResult[$j] = array("n_peg"		=>(string)$result[$j]->n_peg,
										 "n_jabatan" 	=>$n_jabatan[0],
										 "c_unit_kerja"	=>(string)$result[$j]->c_unit_kerja
										 );
			}
			return $hasilResult;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	//// Cah Bagus Agustus 2008 ////
	public function getCompRekapAsetTIByThn($pageNumber,$itemPerPage,$thn) 
	{   
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {	     
			//$where[] = $thn;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0)){
				$result = $db->fetchAll("SELECT distinct a.kd_brg, b.ur_sskel 
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and (kd_brg like '2120102%'  or  kd_brg like '2120203%') 
											UNION
											SELECT distinct a.kd_brg, b.ur_sskel 
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and (kd_brg like '2120204001%'  or  kd_brg like '2060101048%') 
											UNION
											SELECT distinct a.kd_brg, b.ur_sskel 
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and kd_gol = '2'
											and kd_bid = '12'
											and kd_kel = '02'
											and kd_skel = '04'
											and kd_sskel != '001'
										   	and to_char(tgl_perlh,'yyyy') ='$thn' "); 
				$hasilAkhir = count($result);
			}else{
				$result = $db->fetchAll("SELECT distinct a.kd_brg, b.ur_sskel 
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and (kd_brg like '2120102%'  or  kd_brg like '2120203%') 
											UNION
											SELECT distinct a.kd_brg, b.ur_sskel 
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and (kd_brg like '2120204001%'  or  kd_brg like '2060101048%') 
											UNION
											SELECT distinct a.kd_brg, b.ur_sskel 
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and kd_gol = '2'
											and kd_bid = '12'
											and kd_kel = '02'
											and kd_skel = '04'
											and kd_sskel != '001'
										   	and to_char(tgl_perlh,'yyyy') ='$thn' "); 
				$jmlResult = count($result);
				
				for ($j = 0; $j < $jmlResult; $j++) {			
					$total 			= $db->fetchCol("SELECT count(*) as total from e_sabm_t_master_tm A 
													WHERE to_char(A.tgl_perlh,'YYYY') = '$thn'
													and kd_brg = ? ",$result[$j]->kd_brg);
					$rusak 			= $db->fetchCol("SELECT count(*) as rusak from e_sabm_t_master_tm A,  e_ast_ajuanbaikti_0_tm C,
													e_ast_ajuanbaikti_item_tm D 
													 WHERE  to_char(A.tgl_perlh,'YYYY') = '$thn'
												     AND A.kd_brg = C.c_barang 
													 and A.no_aset = C.i_aset
													 AND A.tgl_perlh = C.d_perolehan                
													 AND (c_status_perbaikan != 'Y' or c_status_perbaikan is null)
													 and c.i_barang_ajuanbaik=d.i_barang_ajuanbaik
													 and kd_brg = ?",$result[$j]->kd_brg);
					$hasilAkhir[$j] = array("kd_brg"         =>(string)$result[$j]->kd_brg,
										 "ur_sskel"      =>(string)$result[$j]->ur_sskel,
										 "total"         =>$total[0],
										 "rusak"         =>$rusak[0],
										 "baik"          =>$total[0] - $rusak[0]);
				}	
			}	 
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}

	public function getCompRekapAsetTIByJnsBrg($pageNumber,$itemPerPage,$kdBarang) 
	{   
		$kdBarang = strtoupper($kdBarang);
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0)){
				$result = $db->fetchAll("SELECT distinct to_char(tgl_perlh,'yyyy') as thn_ang								
									FROM e_sabm_t_master_tm G, e_ast_sskel_0_tr H
											where substr(g.kd_brg,1,1) = h.kd_gol
											and substr(g.kd_brg,2,2) = h.kd_bid 
											and substr(g.kd_brg,4,2) = h.kd_kel
											and substr(g.kd_brg,6,2) = h.kd_skel
											and substr(g.kd_brg,8,3) = h.kd_sskel
											and kd_brg ='$kdBarang' "); 
				$hasilAkhir = count($result);
			}else{
				$result = $db->fetchAll("SELECT distinct to_char(tgl_perlh,'yyyy') as thn_ang								
										FROM e_sabm_t_master_tm G, e_ast_sskel_0_tr H
												where substr(g.kd_brg,1,1) = h.kd_gol
												and substr(g.kd_brg,2,2) = h.kd_bid 
												and substr(g.kd_brg,4,2) = h.kd_kel
												and substr(g.kd_brg,6,2) = h.kd_skel
												and substr(g.kd_brg,8,3) = h.kd_sskel
												and kd_brg ='$kdBarang' "); 
				$jmlResult = count($result);
				for ($j = 0; $j < $jmlResult; $j++) {			
					$total = $db->fetchCol("SELECT count(*) as total from e_sabm_t_master_tm A 
													WHERE kd_brg = '$kdBarang'
													and to_char(A.tgl_perlh,'YYYY') = ?",$result[$j]->thn_ang);
					$rusak = $db->fetchCol("SELECT count(*) as rusak from e_sabm_t_master_tm A,  e_ast_ajuanbaikti_0_tm C,
													e_ast_ajuanbaikti_item_tm D 
													 WHERE  A.kd_brg = '$kdBarang'
													 AND A.kd_brg = C.c_barang
													 and A.no_aset = C.i_aset
													 AND A.tgl_perlh = C.d_perolehan                
													 AND (c_status_perbaikan != 'Y' or c_status_perbaikan is null)
													 and c.i_barang_ajuanbaik=d.i_barang_ajuanbaik
													 and to_char(A.tgl_perlh,'YYYY') = ?",$result[$j]->thn_ang);
					$hasilAkhir[$j] = array("periode"  =>(string)$result[$j]->thn_ang,
										 "total"       =>$total[0],
										 "rusak"       =>$rusak[0],
										 "baik"        =>$total[0] - $rusak[0]);
				}	
			}	 
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	
	//==================================================================04 Sept 08 ==================
	
	public function getCompRekapProblemTIByKat_Old($pageNumber,$itemPerPage,$prdAwal,$prdAkhir) {
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$result = $db->fetchOne("Select 
											c.c_problem_ctgr,
											n_problem_ctgr
											from E_AST_AJUANBAIKTI_0_TM C, e_ast_ajuanbaikti_item_tm D 
											, e_ast_kategori_problemti_tm  E
											WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
											and c.c_problem_ctgr=e.c_problem_ctgr 
											and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
											UNION
											SELECT  distinct
											a.c_problem_ctgr,b.n_problem_ctgr
											FROM e_ast_problemti_0_tm a, e_ast_kategori_problemti_tm  b
											where a.c_problem_ctgr=b.c_problem_ctgr 
											and	(d_problem_awal between '$prdAwal' and '$prdAkhir')");
											
				$hasilAkhir = count($result);
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				$result = $db->fetchAll("Select 
											c.c_problem_ctgr,
											n_problem_ctgr,
											c_jenis_problem
											from E_AST_AJUANBAIKTI_0_TM C, e_ast_ajuanbaikti_item_tm D 
											, e_ast_kategori_problemti_tm  E
											WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
											and c.c_problem_ctgr=e.c_problem_ctgr 
											and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
											UNION
											SELECT  distinct
											a.c_problem_ctgr,b.n_problem_ctgr,c_jenis_problem
											FROM e_ast_problemti_0_tm a, e_ast_kategori_problemti_tm  b
											where a.c_problem_ctgr=b.c_problem_ctgr 
											and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
											order by c_problem_ctgr
											limit $xLimit offset $xOffset");
				$jmlResult = count($result);
		
				if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {
						
						$c_problem_ctgr = $result[$j]->c_problem_ctgr;
						$c_jenis_problem = $result[$j]->c_jenis_problem;
						
						if($c_jenis_problem =='U'){
							$total 			= $db->fetchCol("SELECT count(*) as total 
															from E_AST_AJUANBAIKTI_0_TM C, e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
															and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
															and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
							
							$belumSelesai 	= $db->fetchCol("SELECT count(*)  as belumSelesai from e_ast_ajuanbaikti_0_tm C, 
													     	 e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
															AND (D.c_status_perbaikan  != 'Y' or D.c_status_perbaikan  isnull)
														    and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
															and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
							
							$Selesai	 	= $db->fetchCol("SELECT count(*) as Selesai from e_ast_ajuanbaikti_0_tm C,
													     	e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik 
													     	AND D.c_status_perbaikan  = 'Y'
															and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
															and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);

							
														  
						 }
						 else{
							$total 			= $db->fetchCol("SELECT count(*) as total from e_ast_problemti_0_tm 
														WHERE (d_problem_awal between '$prdAwal' and '$prdAkhir')
														and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
							
							$belumSelesai 	= $db->fetchCol("SELECT count(*)  as belumSelesai from e_ast_problemti_0_tm 
														WHERE (c_status_perbaikan  != 'Y' or c_status_perbaikan  is null)
													    and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
														and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
							
							$Selesai 		= $db->fetchCol("SELECT count(*) as Selesai from e_ast_problemti_0_tm 
														WHERE c_status_perbaikan ='Y'
														and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
														and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
						}
						
						
							$hasilAkhir[$j] = array("c_problem_ctgr"    =>(string)$result[$j]->c_problem_ctgr,
													 "n_problem_ctgr"   =>(string)$result[$j]->n_problem_ctgr,
													 "total"         	=>$total[0],
													 "belumSelesai"    	=>$belumSelesai[0],
													 "Selesai"          =>$Selesai[0]);
										 
					}
				}
			}		 
			return $hasilAkhir;
		} catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
		}
	}
	
	
	//cobain dl
	public function getCompRekapProblemTIByKat($pageNumber,$itemPerPage,$prdAwal,$prdAkhir) 
	{   
		//echo "semua services ";
		//echo "tanggal A =".$prdAwal;
		//echo "tanggal B =".$prdAkhir;
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {	     
			//$where[] = $thn;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0)){
				$result = $db->fetchAll("Select 
											c.c_problem_ctgr,
											n_problem_ctgr,
											c_jenis_problem
											from E_AST_AJUANBAIKTI_0_TM C, e_ast_ajuanbaikti_item_tm D 
											, e_ast_kategori_problemti_tm  E
											WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
											and c.c_problem_ctgr=e.c_problem_ctgr 
											and  (d_problem_awal   between '$prdAwal' and '$prdAkhir')
											UNION
											SELECT  distinct
											a.c_problem_ctgr,b.n_problem_ctgr,c_jenis_problem
											FROM e_ast_problemti_0_tm a, e_ast_kategori_problemti_tm  b
											where a.c_problem_ctgr=b.c_problem_ctgr 
											and	(d_problem_awal   between '$prdAwal' and '$prdAkhir')
											order by c_problem_ctgr"); 
				$hasilAkhir = count($result);
				//echo"jumlah s".$hasilAkhir;
			}else{
				$result = $db->fetchAll("Select 
											c.c_problem_ctgr,
											n_problem_ctgr,
											c_jenis_problem
											from E_AST_AJUANBAIKTI_0_TM C, e_ast_ajuanbaikti_item_tm D 
											, e_ast_kategori_problemti_tm  E
											WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
											and c.c_problem_ctgr=e.c_problem_ctgr 
											and  (d_problem_awal   between '$prdAwal' and '$prdAkhir')
											UNION
											SELECT  distinct
											a.c_problem_ctgr,b.n_problem_ctgr,c_jenis_problem
											FROM e_ast_problemti_0_tm a, e_ast_kategori_problemti_tm  b
											where a.c_problem_ctgr=b.c_problem_ctgr 
											and	(d_problem_awal   between '$prdAwal' and '$prdAkhir')
											order by c_problem_ctgr "); 
				$jmlResult = count($result);
				//echo "jumlah =".$jmlResult;
				
				for ($j = 0; $j < $jmlResult; $j++) {			
					    $c_problem_ctgr = $result[$j]->c_problem_ctgr;
						$c_jenis_problem = $result[$j]->c_jenis_problem;
						
						if($c_jenis_problem =='U'){
						    $n_jenis_problem = 'User';
							$total 			= $db->fetchCol("SELECT count(*) as total 
															from E_AST_AJUANBAIKTI_0_TM C, e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
															and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
															and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
							
							$belumSelesai 	= $db->fetchCol("SELECT count(*)  as belumSelesai from e_ast_ajuanbaikti_0_tm C, 
													     	 e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
															AND (D.c_status_perbaikan  != 'Y' or D.c_status_perbaikan  isnull)
														    and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
															and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
							
							$Selesai	 	= $db->fetchCol("SELECT count(*) as Selesai from e_ast_ajuanbaikti_0_tm C,
													     	e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik 
													     	AND D.c_status_perbaikan  = 'Y'
															and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
															and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);

							
														  
						 }
						 else{
						    $n_jenis_problem = 'Sistem';
							$total 			= $db->fetchCol("SELECT count(*) as total from e_ast_problemti_0_tm 
														WHERE (d_problem_awal between '$prdAwal' and '$prdAkhir')
														and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
							
							$belumSelesai 	= $db->fetchCol("SELECT count(*)  as belumSelesai from e_ast_problemti_0_tm 
														WHERE (c_status_perbaikan  != 'Y' or c_status_perbaikan  is null)
													    and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
														and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
							
							$Selesai 		= $db->fetchCol("SELECT count(*) as Selesai from e_ast_problemti_0_tm 
														WHERE c_status_perbaikan ='Y'
														and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
														and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
						}
						
					
					
					$hasilAkhir[$j] = array("c_problem_ctgr"    =>(string)$result[$j]->c_problem_ctgr,
													 "n_jenis_problem"   =>(string)$n_jenis_problem,
													 "n_problem_ctgr"   =>(string)$result[$j]->n_problem_ctgr,
													 "total"         	=>$total[0],
													 "belumSelesai"    	=>$belumSelesai[0],
													 "Selesai"          =>$Selesai[0]);
				}	
			}	 
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	
	
	//
	public function getCompRekapProblemTIByUnit($pageNumber,$itemPerPage,$prdAwal,$prdAkhir) {
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$result = $db->fetchOne("Select 
											distinct A.i_orgb, B.n_orgb 
											from E_AST_AJUANBAIKTI_0_TM A, e_org_0_0_tm B,e_ast_ajuanbaikti_item_tm D
											where A.i_orgb = B.i_orgb
											and D.i_barang_ajuanbaik = A.i_barang_ajuanbaik
											and  d_problem_awal  between '$prdAwal' and '$prdAkhir'
											UNION
											SELECT  distinct A.i_orgb, B.n_orgb 
											FROM e_ast_problemti_0_tm A, e_org_0_0_tm B
											where A.i_orgb = B.i_orgb
											and	d_problem_awal  between '$prdAwal' and '$prdAkhir' ");
										
				$hasilAkhir = count($result);
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				$result = $db->fetchAll("Select 
											distinct A.i_orgb, B.n_orgb 
											from E_AST_AJUANBAIKTI_0_TM A, e_org_0_0_tm B,e_ast_ajuanbaikti_item_tm D
											where A.i_orgb = B.i_orgb
											and D.i_barang_ajuanbaik = A.i_barang_ajuanbaik
											and  d_problem_awal  between '$prdAwal' and '$prdAkhir'
											UNION
											SELECT  distinct A.i_orgb, B.n_orgb 
											FROM e_ast_problemti_0_tm A, e_org_0_0_tm B
											where A.i_orgb = B.i_orgb
											and	d_problem_awal  between '$prdAwal' and '$prdAkhir' ");
				$jmlResult = count($result);
				
				if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {
						
							$total 			= $db->fetchCol("SELECT count(*) as total from e_ast_problemti_0_tm 
														WHERE (d_problem_awal between '$prdAwal' and '$prdAkhir')
														and i_orgb = ? ",$result[$j]->i_orgb);
							
							$belumSelesai 	= $db->fetchCol("SELECT count(*)  as belumSelesai from e_ast_problemti_0_tm 
														WHERE (c_status_perbaikan  != 'Y' or c_status_perbaikan  is null)
													    and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
														and i_orgb = ? ",$result[$j]->i_orgb);
							
							$Selesai 		= $db->fetchCol("SELECT count(*) as Selesai from e_ast_problemti_0_tm 
														WHERE c_status_perbaikan ='Y'
														and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
														and i_orgb = ? ",$result[$j]->i_orgb);
						
							$total2 			= $db->fetchCol("SELECT count(*) as total 
															from E_AST_AJUANBAIKTI_0_TM C, e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
															and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
															and i_orgb = ? ",$result[$j]->i_orgb);
							
							$belumSelesai2 	= $db->fetchCol("SELECT count(*)  as belumSelesai from e_ast_ajuanbaikti_0_tm C, 
													     	 e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
															AND (D.c_status_perbaikan  != 'Y' or D.c_status_perbaikan  isnull)
														    and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
															and i_orgb = ? ",$result[$j]->i_orgb);
							
							$Selesai2	 	= $db->fetchCol("SELECT count(*) as Selesai from e_ast_ajuanbaikti_0_tm C,
													     	e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik 
													     	AND D.c_status_perbaikan  = 'Y'
															and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
															and i_orgb = ? ",$result[$j]->i_orgb);
															
							$hasilAkhir[$j] = array("i_orgb"    		=>(string)$result[$j]->i_orgb,
													 "n_orgb"   		=>(string)$result[$j]->n_orgb,
													 "total"         	=>$total[0]+$total2[0],
													 "belumSelesai"    	=>$belumSelesai[0]+$belumSelesai2[0],
													 "Selesai"          =>$Selesai[0]+$Selesai2[0]);
										 
					}
				}
			}		 
			return $hasilAkhir;
		} catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
		}
	}
	
	//
	public function getCompRekapProblemTIByUnitKat($pageNumber,$itemPerPage,$prdAwal,$prdAkhir) {
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$result = $db->fetchOne("Select 
											distinct A.i_orgb, B.n_orgb,
											a.c_problem_ctgr,
											n_problem_ctgr,
											c_jenis_problem
											from E_AST_AJUANBAIKTI_0_TM A, e_org_0_0_tm B, 
											e_ast_kategori_problemti_tm  E,e_ast_ajuanbaikti_item_tm D
											where A.i_orgb = B.i_orgb
											and D.i_barang_ajuanbaik = A.i_barang_ajuanbaik
											and a.c_problem_ctgr=e.c_problem_ctgr 
											and  d_problem_awal  between '$prdAwal' and '$prdAkhir'
											UNION
											SELECT  distinct A.i_orgb, B.n_orgb ,
											A.c_problem_ctgr,
											n_problem_ctgr,
											c_jenis_problem
											FROM e_ast_problemti_0_tm A, e_org_0_0_tm B, e_ast_kategori_problemti_tm  C
											where A.i_orgb = B.i_orgb
											and A.c_problem_ctgr=C.c_problem_ctgr 
											and	d_problem_awal  between '$prdAwal' and '$prdAkhir' ");
											
				$hasilAkhir = count($result);
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				$result = $db->fetchAll("Select 
											distinct A.i_orgb, B.n_orgb,
											a.c_problem_ctgr,
											n_problem_ctgr,
											c_jenis_problem
											from E_AST_AJUANBAIKTI_0_TM A, e_org_0_0_tm B, 
											e_ast_kategori_problemti_tm  E,e_ast_ajuanbaikti_item_tm D
											where A.i_orgb = B.i_orgb
											and D.i_barang_ajuanbaik = A.i_barang_ajuanbaik
											and a.c_problem_ctgr=e.c_problem_ctgr 
											and  d_problem_awal  between '$prdAwal' and '$prdAkhir'
											UNION
											SELECT  distinct A.i_orgb, B.n_orgb ,
											A.c_problem_ctgr,
											n_problem_ctgr,
											c_jenis_problem
											FROM e_ast_problemti_0_tm A, e_org_0_0_tm B, e_ast_kategori_problemti_tm  C
											where A.i_orgb = B.i_orgb
											and A.c_problem_ctgr=C.c_problem_ctgr 
											and	d_problem_awal  between '$prdAwal' and '$prdAkhir' ");
											
				$jmlResult = count($result);
				
				if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {
					
					$c_problem_ctgr = $result[$j]->c_problem_ctgr;
					
						$where = $result[$j]->i_orgb;
						
							$total 			= $db->fetchCol("SELECT count(*) as total from e_ast_problemti_0_tm 
														WHERE (d_problem_awal between '$prdAwal' and '$prdAkhir')
														and i_orgb = ? ",$result[$j]->i_orgb);
							
							$belumSelesai 	= $db->fetchCol("SELECT count(*)  as belumSelesai from e_ast_problemti_0_tm 
														WHERE (c_status_perbaikan  != 'Y' or c_status_perbaikan  is null)
													    and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
														and i_orgb = ? ",$result[$j]->i_orgb);
							
							$Selesai 		= $db->fetchCol("SELECT count(*) as Selesai from e_ast_problemti_0_tm 
														WHERE c_status_perbaikan ='Y'
														and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
														and i_orgb = ? ",$result[$j]->i_orgb);
						
							$total2 			= $db->fetchCol("SELECT count(*) as total 
														from E_AST_AJUANBAIKTI_0_TM C, e_ast_ajuanbaikti_item_tm D 
														WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
														and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
														and i_orgb = ? ",$result[$j]->i_orgb);
							
							$belumSelesai2 	= $db->fetchCol("SELECT count(*)  as belumSelesai from e_ast_ajuanbaikti_0_tm C, 
													     	 e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
															AND (D.c_status_perbaikan  != 'Y' or D.c_status_perbaikan  isnull)
														    and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
															and i_orgb = ? ",$result[$j]->i_orgb);
							
							$Selesai2	 	= $db->fetchCol("SELECT count(*) as Selesai from e_ast_ajuanbaikti_0_tm C,
													     	e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik 
													     	AND D.c_status_perbaikan  = 'Y'
															and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
															and i_orgb = ? ",$result[$j]->i_orgb);
															
							$hasilAkhir[$j] = array("i_orgb"    		=>(string)$result[$j]->i_orgb,
													 "n_orgb"   		=>(string)$result[$j]->n_orgb,
													 "c_problem_ctgr"   =>(string)$result[$j]->c_problem_ctgr,
													 "n_problem_ctgr"   =>(string)$result[$j]->n_problem_ctgr,
													 "total"         	=>$total[0]+$total2[0],
													 "belumSelesai"    	=>$belumSelesai[0]+$belumSelesai2[0],
													 "Selesai"          =>$Selesai[0]+$Selesai2[0]);
										 
					}
				}
			}		 
			return $hasilAkhir;
		} catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
		}
	}
	
	
	// Ina : Awal : 28-10-2008 : Service Baru
	public function getPermasalahan_All($pageNumber,$itemPerPage,$prdAwal,$prdAkhir,$nipPenerima,$status,$jenisProblem, $kategoriProblem)
	{
		$nipPenerima = strtoupper($nipPenerima);
		$nipPenerima = '%'.$nipPenerima.'%';
		$status = strtoupper($status);
		$status = '%'.$status.'%';
		
		$jenisProblem = strtoupper($jenisProblem);
		$jenisProblem = '%'.$jenisProblem.'%';
		$kategoriProblem = strtoupper($kategoriProblem);
		$kategoriProblem = '%'.$kategoriProblem.'%';
		
		try {
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			$where[] = $nipPenerima;
			$where[] = $status;
			$where[] = $jenisProblem;
			$where[] = $kategoriProblem;
			
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			$where[] = $nipPenerima;
			$where[] = $status;
			$where[] = $jenisProblem;
			$where[] = $kategoriProblem;
			
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			$where[] = $nipPenerima;
			$where[] = $status;						
						
						
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			$where[] = $nipPenerima;
			$where[] = $status;									
			
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasil = $db->fetchAll("Select a.i_barang_ajuanbaik,a.d_barang_ajuanbaik,a.d_anggaran,
										a.c_barang,to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,i_peg_helpdesk,
										a.e_barang_perbaikan, a.c_problem_ctgr,n_problem_ctgr, c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,c_status_perbaikan,
										c_barang_perbaikan, e_saran_sparepart, e_saran_pihakketiga,
										i_disposisi, i_orgb,e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan as tglselesai, q_jam_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B, E_AST_KATEGORI_PROBLEMTI_TM C
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										And A.c_problem_ctgr = c.c_problem_ctgr
										and (d_problem_awal between ? and ?)
										and upper(i_peg_helpdesk) like ?
										and c_status_perbaikan like ?
										And C.C_jenis_problem like ?
										And A.C_problem_ctgr like ?										
										UNION
										SELECT i_barang_ajuanbaik, d_barang_ajuanbaik, null as d_anggaran,
										null as c_barang, null as i_aset,i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk, e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, n_problem_ctgr, c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,
										c_status_perbaikan,'' as c_barang_perbaikan,'' as e_saran_sparepart,
										'' as e_saran_pihakketiga, '' as i_disposisi, i_orgb,e_problem_solusi as tindakan, 
										d_akhir_perbaikan as tglselesai, null as q_jam_perbaikan
										FROM e_ast_problemti_0_tm a,E_AST_KATEGORI_PROBLEMTI_TM C
										WHERE A.c_problem_ctgr = c.c_problem_ctgr
										and (d_problem_awal between ? and ?)
										and upper(i_peg_nip) like ?
										and c_status_perbaikan like ?										
										And C.C_jenis_problem like ?
										And A.C_problem_ctgr like ?
										UNION
										SELECT i_barang_ajuanbaik, d_barang_ajuanbaik, null as d_anggaran,
										null as c_barang, null as i_aset,i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk, e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, '' as n_problem_ctgr, '' as c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,
										c_status_perbaikan,'' as c_barang_perbaikan,'' as e_saran_sparepart,
										'' as e_saran_pihakketiga, '' as i_disposisi, i_orgb,e_problem_solusi as tindakan, 
										d_akhir_perbaikan as tglselesai, null as q_jam_perbaikan
										FROM e_ast_problemti_0_tm a										
										WHERE (a.d_problem_awal between ? and ?)
										and upper(a.i_peg_nip) like ?
										and a.c_status_perbaikan like ?										
										and not EXISTS(select c.c_problem_ctgr 
										                 from E_AST_KATEGORI_PROBLEMTI_TM C
										                 where A.c_problem_ctgr = c.c_problem_ctgr)	
										UNION
										Select a.i_barang_ajuanbaik,a.d_barang_ajuanbaik,a.d_anggaran,
										a.c_barang,to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,i_peg_helpdesk,
										a.e_barang_perbaikan, a.c_problem_ctgr, '' as n_problem_ctgr, '' as c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,c_status_perbaikan,
										c_barang_perbaikan, e_saran_sparepart, e_saran_pihakketiga,
										i_disposisi, i_orgb,e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan as tglselesai, q_jam_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and (d_problem_awal between ? and ?)
										and upper(i_peg_helpdesk) like ?
										and c_status_perbaikan like ?
										and not EXISTS(select c.c_problem_ctgr 
													   from E_AST_KATEGORI_PROBLEMTI_TM C
													   where A.c_problem_ctgr = c.c_problem_ctgr)
										order by d_barang_ajuanbaik 
										",$where)	;
				$hasilList = count($hasil);
			}
			else 
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				$setuju = $db->fetchAll("Select a.i_barang_ajuanbaik,a.d_barang_ajuanbaik,a.d_anggaran,
										a.c_barang,to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,i_peg_helpdesk,
										a.e_barang_perbaikan, a.c_problem_ctgr,n_problem_ctgr, c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,c_status_perbaikan,
										c_barang_perbaikan, e_saran_sparepart, e_saran_pihakketiga,
										i_disposisi, i_orgb,e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan as tglselesai, q_jam_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B, E_AST_KATEGORI_PROBLEMTI_TM C
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										And A.c_problem_ctgr = c.c_problem_ctgr
										and (d_problem_awal between ? and ?)
										and upper(i_peg_helpdesk) like ?
										and c_status_perbaikan like ?
										And C.C_jenis_problem like ?
										And A.C_problem_ctgr like ?										
										UNION
										SELECT i_barang_ajuanbaik, d_barang_ajuanbaik, null as d_anggaran,
										null as c_barang, null as i_aset,i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk, e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, n_problem_ctgr, c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,
										c_status_perbaikan,'' as c_barang_perbaikan,'' as e_saran_sparepart,
										'' as e_saran_pihakketiga, '' as i_disposisi, i_orgb,e_problem_solusi as tindakan, 
										d_akhir_perbaikan as tglselesai, null as q_jam_perbaikan
										FROM e_ast_problemti_0_tm a,E_AST_KATEGORI_PROBLEMTI_TM C
										WHERE A.c_problem_ctgr = c.c_problem_ctgr
										and (d_problem_awal between ? and ?)
										and upper(i_peg_nip) like ?
										and c_status_perbaikan like ?										
										And C.C_jenis_problem like ?
										And A.C_problem_ctgr like ?
										UNION
										SELECT i_barang_ajuanbaik, d_barang_ajuanbaik, null as d_anggaran,
										null as c_barang, null as i_aset,i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk, e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, '' as n_problem_ctgr, '' as c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,
										c_status_perbaikan,'' as c_barang_perbaikan,'' as e_saran_sparepart,
										'' as e_saran_pihakketiga, '' as i_disposisi, i_orgb,e_problem_solusi as tindakan, 
										d_akhir_perbaikan as tglselesai, null as q_jam_perbaikan
										FROM e_ast_problemti_0_tm a										
										WHERE (a.d_problem_awal between ? and ?)
										and upper(a.i_peg_nip) like ?
										and a.c_status_perbaikan like ?										
										and not EXISTS(select c.c_problem_ctgr 
										                 from E_AST_KATEGORI_PROBLEMTI_TM C
										                 where A.c_problem_ctgr = c.c_problem_ctgr)	
										UNION										
										Select a.i_barang_ajuanbaik,a.d_barang_ajuanbaik,a.d_anggaran,
										a.c_barang,to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,i_peg_helpdesk,
										a.e_barang_perbaikan, a.c_problem_ctgr, '' as n_problem_ctgr, '' as c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,c_status_perbaikan,
										c_barang_perbaikan, e_saran_sparepart, e_saran_pihakketiga,
										i_disposisi, i_orgb,e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan as tglselesai, q_jam_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and (d_problem_awal between ? and ?)
										and upper(i_peg_helpdesk) like ?
										and c_status_perbaikan like ?
										and not EXISTS(select c.c_problem_ctgr 
													   from E_AST_KATEGORI_PROBLEMTI_TM C
													   where A.c_problem_ctgr = c.c_problem_ctgr)										
										order by d_barang_ajuanbaik 
										limit $xLimit offset $xOffset",$where)	;
				
								
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{										
					$n_peg 				= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_helpdesk);	
										
										
					// Awal : Pencari Nama Pemohon dan org Pemohon									
					$n_peg_nippemohon 	= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nippemohon);	
					
					if ($n_peg_nippemohon==null || $n_peg_nippemohon == '')
					{
					     $n_peg_nippemohon 	=  $db->fetchCol('select n_peg_bumn  
														from  e_ast_problemti_0_tm 
														where i_barang_ajuanbaik = ? ',$setuju[$j]->i_barang_ajuanbaik);	
														
					     $n_orgb 	= $db->fetchCol('select n_bumn  
														from  a_bumn_0_0_tr 
														where i_bumn = ? ',$setuju[$j]->i_peg_nippemohon);	
					}	else
					{
					     $n_orgb 			= $db->fetchCol('select n_orgb  
														from  e_org_0_0_tm 
														where i_orgb = ? ',$setuju[$j]->i_orgb);
					}									
					// Akhir  : Pencari Nama Pemohon dan org Pemohon 								
					$n_jabatan 			= $db->fetchCol('select n_jabatan  
														from  e_sdm_jabatan_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nippemohon);					
								
														
					$hasilList[$j] = array("i_barang_ajuanbaik"		=>(string)$setuju[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" 	=>(string)$setuju[$j]->d_barang_ajuanbaik,
											 "i_orgb"				=>(string)$setuju[$j]->i_orgb,
											 "i_barang_penerimaan"	=>(string)$setuju[$j]->i_barang_penerimaan,
											 "d_anggaran"			=>(string)$setuju[$j]->d_anggaran,
											 "c_barang"				=>(string)$setuju[$j]->c_barang,
											 "i_aset"				=>(string)$setuju[$j]->i_aset,
											 "i_peg_nip"			=>(string)$setuju[$j]->i_peg_nippemohon,
											 "i_peg_helpdesk"		=>(string)$setuju[$j]->i_peg_helpdesk,
											 "d_perolehan"			=>(string)$setuju[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$setuju[$j]->e_barang_perbaikan,
											 "c_problem_ctgr"		=>(string)$setuju[$j]->c_problem_ctgr,
											 "n_problem_ctgr"		=>(string)$setuju[$j]->n_problem_ctgr,
											 "n_peg"				=>$n_peg[0],
											 "c_status_perbaikan"	=>(string)$setuju[$j]->c_status_perbaikan,
											 "c_barang_perbaikan"	=>(string)$setuju[$j]->c_barang_perbaikan,
											 "e_saran_sparepart"	=>(string)$setuju[$j]->e_saran_sparepart,
											 "e_saran_pihakketiga"	=>(string)$setuju[$j]->e_saran_pihakketiga,
											 "i_disposisi"			=>(string)$setuju[$j]->i_disposisi,
											 "n_peg_nippemohon"		=>$n_peg_nippemohon[0],
											 "n_jabatan"			=>$n_jabatan[0],
											 "n_orgb"				=>$n_orgb[0],
											 "tindakan"				=>(string)$setuju[$j]->tindakan,
											 "tglselesai"			=>(string)$setuju[$j]->tglselesai,
											 "q_jam_perbaikan"		=>(string)$setuju[$j]->q_jam_perbaikan,
											 "d_problem_awal"		=>(string)$setuju[$j]->d_problem_awal,
											 "d_awal_perbaikan"		=>(string)$setuju[$j]->d_awal_perbaikan
											 );
				}
			}	
			
			return $hasilList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	
	public function getPermasalahan_All_DgnKategori($pageNumber,$itemPerPage,$prdAwal,$prdAkhir,$nipPenerima,$status,$jenisProblem, $kategoriProblem)
	{
		$nipPenerima = strtoupper($nipPenerima);
		$nipPenerima = '%'.$nipPenerima.'%';
		$status = strtoupper($status);
		$status = '%'.$status.'%';
		$jenisProblem = strtoupper($jenisProblem);
		$jenisProblem = '%'.$jenisProblem.'%';
		$kategoriProblem = strtoupper($kategoriProblem);
		$kategoriProblem = '%'.$kategoriProblem.'%';
		
		try {
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			$where[] = $nipPenerima;
			$where[] = $status;
			$where[] = $jenisProblem;
			$where[] = $kategoriProblem;
			
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			$where[] = $nipPenerima;
			$where[] = $status;
			$where[] = $jenisProblem;
			$where[] = $kategoriProblem;
			
						
			
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasil = $db->fetchAll("Select a.i_barang_ajuanbaik,a.d_barang_ajuanbaik,a.d_anggaran,
										a.c_barang,to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,i_peg_helpdesk,
										a.e_barang_perbaikan, a.c_problem_ctgr,n_problem_ctgr, c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,c_status_perbaikan,
										c_barang_perbaikan, e_saran_sparepart, e_saran_pihakketiga,
										i_disposisi, i_orgb,e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan as tglselesai, q_jam_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B, E_AST_KATEGORI_PROBLEMTI_TM C
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										And A.c_problem_ctgr = c.c_problem_ctgr
										and (d_problem_awal between ? and ?)
										and upper(i_peg_helpdesk) like ?
										and c_status_perbaikan like ?
										And C.C_jenis_problem like ?
										And A.C_problem_ctgr like ?										
										UNION
										SELECT i_barang_ajuanbaik, d_barang_ajuanbaik, null as d_anggaran,
										null as c_barang, null as i_aset,i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk, e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, n_problem_ctgr, c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,
										c_status_perbaikan,'' as c_barang_perbaikan,'' as e_saran_sparepart,
										'' as e_saran_pihakketiga, '' as i_disposisi, i_orgb,e_problem_solusi as tindakan, 
										d_akhir_perbaikan as tglselesai, null as q_jam_perbaikan
										FROM e_ast_problemti_0_tm a,E_AST_KATEGORI_PROBLEMTI_TM C
										WHERE A.c_problem_ctgr = c.c_problem_ctgr
										and (d_problem_awal between ? and ?)
										and upper(i_peg_nip) like ?
										and c_status_perbaikan like ?										
										And C.C_jenis_problem like ?
										And A.C_problem_ctgr like ?										
										order by d_barang_ajuanbaik 
										",$where)	;
				$hasilList = count($hasil);
			}
			else 
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				$setuju = $db->fetchAll("Select a.i_barang_ajuanbaik,a.d_barang_ajuanbaik,a.d_anggaran,
										a.c_barang,to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,i_peg_helpdesk,
										a.e_barang_perbaikan, a.c_problem_ctgr,n_problem_ctgr, c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,c_status_perbaikan,
										c_barang_perbaikan, e_saran_sparepart, e_saran_pihakketiga,
										i_disposisi, i_orgb,e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan as tglselesai, q_jam_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B, E_AST_KATEGORI_PROBLEMTI_TM C
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										And A.c_problem_ctgr = c.c_problem_ctgr
										and (d_problem_awal between ? and ?)
										and upper(i_peg_helpdesk) like ?
										and c_status_perbaikan like ?
										And C.C_jenis_problem like ?
										And A.C_problem_ctgr like ?										
										UNION
										SELECT i_barang_ajuanbaik, d_barang_ajuanbaik, null as d_anggaran,
										null as c_barang, null as i_aset,i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk, e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, n_problem_ctgr, c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,
										c_status_perbaikan,'' as c_barang_perbaikan,'' as e_saran_sparepart,
										'' as e_saran_pihakketiga, '' as i_disposisi, i_orgb,e_problem_solusi as tindakan, 
										d_akhir_perbaikan as tglselesai, null as q_jam_perbaikan
										FROM e_ast_problemti_0_tm a,E_AST_KATEGORI_PROBLEMTI_TM C
										WHERE A.c_problem_ctgr = c.c_problem_ctgr
										and (d_problem_awal between ? and ?)
										and upper(i_peg_nip) like ?
										and c_status_perbaikan like ?										
										And C.C_jenis_problem like ?
										And A.C_problem_ctgr like ?										
										order by d_barang_ajuanbaik 										
										limit $xLimit offset $xOffset",$where)	;
				
								
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{										
					$n_peg 				= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_helpdesk);	
										
										
					// Awal : Pencari Nama Pemohon dan org Pemohon									
					$n_peg_nippemohon 	= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nippemohon);	
					
					if ($n_peg_nippemohon==null || $n_peg_nippemohon == '')
					{
					     $n_peg_nippemohon 	=  $db->fetchCol('select n_peg_bumn  
														from  e_ast_problemti_0_tm 
														where i_barang_ajuanbaik = ? ',$setuju[$j]->i_barang_ajuanbaik);	
														
					     $n_orgb 	= $db->fetchCol('select n_bumn  
														from  a_bumn_0_0_tr 
														where i_bumn = ? ',$setuju[$j]->i_peg_nippemohon);	
					}	else
					{
					     $n_orgb 			= $db->fetchCol('select n_orgb  
														from  e_org_0_0_tm 
														where i_orgb = ? ',$setuju[$j]->i_orgb);
					}									
					// Akhir  : Pencari Nama Pemohon dan org Pemohon 								
					$n_jabatan 			= $db->fetchCol('select n_jabatan  
														from  e_sdm_jabatan_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nippemohon);					
								
														
					$hasilList[$j] = array("i_barang_ajuanbaik"		=>(string)$setuju[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" 	=>(string)$setuju[$j]->d_barang_ajuanbaik,
											 "i_orgb"				=>(string)$setuju[$j]->i_orgb,
											 "i_barang_penerimaan"	=>(string)$setuju[$j]->i_barang_penerimaan,
											 "d_anggaran"			=>(string)$setuju[$j]->d_anggaran,
											 "c_barang"				=>(string)$setuju[$j]->c_barang,
											 "i_aset"				=>(string)$setuju[$j]->i_aset,
											 "i_peg_nip"			=>(string)$setuju[$j]->i_peg_nippemohon,
											 "i_peg_helpdesk"		=>(string)$setuju[$j]->i_peg_helpdesk,
											 "d_perolehan"			=>(string)$setuju[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$setuju[$j]->e_barang_perbaikan,
											 "c_problem_ctgr"		=>(string)$setuju[$j]->c_problem_ctgr,
											 "n_problem_ctgr"		=>(string)$setuju[$j]->n_problem_ctgr,
											 "n_peg"				=>$n_peg[0],
											 "c_status_perbaikan"	=>(string)$setuju[$j]->c_status_perbaikan,
											 "c_barang_perbaikan"	=>(string)$setuju[$j]->c_barang_perbaikan,
											 "e_saran_sparepart"	=>(string)$setuju[$j]->e_saran_sparepart,
											 "e_saran_pihakketiga"	=>(string)$setuju[$j]->e_saran_pihakketiga,
											 "i_disposisi"			=>(string)$setuju[$j]->i_disposisi,
											 "n_peg_nippemohon"		=>$n_peg_nippemohon[0],
											 "n_jabatan"			=>$n_jabatan[0],
											 "n_orgb"				=>$n_orgb[0],
											 "tindakan"				=>(string)$setuju[$j]->tindakan,
											 "tglselesai"			=>(string)$setuju[$j]->tglselesai,
											 "q_jam_perbaikan"		=>(string)$setuju[$j]->q_jam_perbaikan,
											 "d_problem_awal"		=>(string)$setuju[$j]->d_problem_awal,
											 "d_awal_perbaikan"		=>(string)$setuju[$j]->d_awal_perbaikan
											 );
				}
			}	
			
			return $hasilList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	
	public function getPermasalahan_PeriodeNull($pageNumber,$itemPerPage,$nipPenerima,$status,$jenisProblem, $kategoriProblem)
	{
		$nipPenerima = strtoupper($nipPenerima);
		$nipPenerima = '%'.$nipPenerima.'%';
		$status = strtoupper($status);
		$status = '%'.$status.'%';
		
		$jenisProblem = strtoupper($jenisProblem);
		$jenisProblem = '%'.$jenisProblem.'%';
		$kategoriProblem = strtoupper($kategoriProblem);
		$kategoriProblem = '%'.$kategoriProblem.'%';
		
		try {
			
			$where[] = $nipPenerima;
			$where[] = $status;
			$where[] = $jenisProblem;
			$where[] = $kategoriProblem;
			
			
			$where[] = $nipPenerima;
			$where[] = $status;
			$where[] = $jenisProblem;
			$where[] = $kategoriProblem;

			$where[] = $nipPenerima;
			$where[] = $status;						
			
			$where[] = $nipPenerima;
			$where[] = $status;						
			
						
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasil = $db->fetchAll("Select a.i_barang_ajuanbaik,a.d_barang_ajuanbaik,a.d_anggaran,
										a.c_barang,to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,i_peg_helpdesk,
										a.e_barang_perbaikan, a.c_problem_ctgr,n_problem_ctgr, c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,c_status_perbaikan,
										c_barang_perbaikan, e_saran_sparepart, e_saran_pihakketiga,
										i_disposisi, i_orgb,e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan as tglselesai, q_jam_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B, E_AST_KATEGORI_PROBLEMTI_TM C
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										And A.c_problem_ctgr = c.c_problem_ctgr										
										and upper(i_peg_helpdesk) like ?
										and c_status_perbaikan like ?
										And C.C_jenis_problem like ?
										And A.C_problem_ctgr like ?										
										UNION
										SELECT i_barang_ajuanbaik, d_barang_ajuanbaik, null as d_anggaran,
										null as c_barang, null as i_aset,i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk, e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, n_problem_ctgr, c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,
										c_status_perbaikan,'' as c_barang_perbaikan,'' as e_saran_sparepart,
										'' as e_saran_pihakketiga, '' as i_disposisi, i_orgb,e_problem_solusi as tindakan, 
										d_akhir_perbaikan as tglselesai, null as q_jam_perbaikan
										FROM e_ast_problemti_0_tm a,E_AST_KATEGORI_PROBLEMTI_TM C
										WHERE A.c_problem_ctgr = c.c_problem_ctgr										
										and upper(i_peg_nip) like ?
										and c_status_perbaikan like ?										
										And C.C_jenis_problem like ?
										And A.C_problem_ctgr like ?	
										UNION
										SELECT i_barang_ajuanbaik, d_barang_ajuanbaik, null as d_anggaran,
										null as c_barang, null as i_aset,i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk, e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, '' as n_problem_ctgr, '' as c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,
										c_status_perbaikan,'' as c_barang_perbaikan,'' as e_saran_sparepart,
										'' as e_saran_pihakketiga, '' as i_disposisi, i_orgb,e_problem_solusi as tindakan, 
										d_akhir_perbaikan as tglselesai, null as q_jam_perbaikan
										FROM e_ast_problemti_0_tm a	                                        
										WHERE upper(a.i_peg_nip) like ?
										and a.c_status_perbaikan like ?										
										and not EXISTS(select c.c_problem_ctgr 
										                 from E_AST_KATEGORI_PROBLEMTI_TM C
										                 where A.c_problem_ctgr = c.c_problem_ctgr)	
										UNION
										Select a.i_barang_ajuanbaik,a.d_barang_ajuanbaik,a.d_anggaran,
										a.c_barang,to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,i_peg_helpdesk,
										a.e_barang_perbaikan, a.c_problem_ctgr, '' as n_problem_ctgr, '' as c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,c_status_perbaikan,
										c_barang_perbaikan, e_saran_sparepart, e_saran_pihakketiga,
										i_disposisi, i_orgb,e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan as tglselesai, q_jam_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and upper(i_peg_helpdesk) like ?
										and c_status_perbaikan like ?
										and not EXISTS(select c.c_problem_ctgr 
													   from E_AST_KATEGORI_PROBLEMTI_TM C
													   where A.c_problem_ctgr = c.c_problem_ctgr)										
										order by d_barang_ajuanbaik 
										",$where)	;
				$hasilList = count($hasil);
			}
			else 
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				$setuju = $db->fetchAll("Select a.i_barang_ajuanbaik,a.d_barang_ajuanbaik,a.d_anggaran,
										a.c_barang,to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,i_peg_helpdesk,
										a.e_barang_perbaikan, a.c_problem_ctgr,n_problem_ctgr, c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,c_status_perbaikan,
										c_barang_perbaikan, e_saran_sparepart, e_saran_pihakketiga,
										i_disposisi, i_orgb,e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan as tglselesai, q_jam_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B, E_AST_KATEGORI_PROBLEMTI_TM C
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										And A.c_problem_ctgr = c.c_problem_ctgr										
										and upper(i_peg_helpdesk) like ?
										and c_status_perbaikan like ?
										And C.C_jenis_problem like ?
										And A.C_problem_ctgr like ?										
										UNION
										SELECT i_barang_ajuanbaik, d_barang_ajuanbaik, null as d_anggaran,
										null as c_barang, null as i_aset,i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk, e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, n_problem_ctgr, c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,
										c_status_perbaikan,'' as c_barang_perbaikan,'' as e_saran_sparepart,
										'' as e_saran_pihakketiga, '' as i_disposisi, i_orgb,e_problem_solusi as tindakan, 
										d_akhir_perbaikan as tglselesai, null as q_jam_perbaikan
										FROM e_ast_problemti_0_tm a,E_AST_KATEGORI_PROBLEMTI_TM C
										WHERE A.c_problem_ctgr = c.c_problem_ctgr										
										and upper(i_peg_nip) like ?
										and c_status_perbaikan like ?										
										And C.C_jenis_problem like ?
										And A.C_problem_ctgr like ?	
										UNION
										SELECT i_barang_ajuanbaik, d_barang_ajuanbaik, null as d_anggaran,
										null as c_barang, null as i_aset,i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk, e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, '' as n_problem_ctgr, '' as c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,
										c_status_perbaikan,'' as c_barang_perbaikan,'' as e_saran_sparepart,
										'' as e_saran_pihakketiga, '' as i_disposisi, i_orgb,e_problem_solusi as tindakan, 
										d_akhir_perbaikan as tglselesai, null as q_jam_perbaikan
										FROM e_ast_problemti_0_tm a										
										WHERE upper(a.i_peg_nip) like ?
										and a.c_status_perbaikan like ?										
										and not EXISTS(select c.c_problem_ctgr 
										                 from E_AST_KATEGORI_PROBLEMTI_TM C
										                 where A.c_problem_ctgr = c.c_problem_ctgr)	
										UNION
										Select a.i_barang_ajuanbaik,a.d_barang_ajuanbaik,a.d_anggaran,
										a.c_barang,to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,i_peg_helpdesk,
										a.e_barang_perbaikan, a.c_problem_ctgr, '' as n_problem_ctgr, '' as c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,c_status_perbaikan,
										c_barang_perbaikan, e_saran_sparepart, e_saran_pihakketiga,
										i_disposisi, i_orgb,e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan as tglselesai, q_jam_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and upper(i_peg_helpdesk) like ?
										and c_status_perbaikan like ?
										and not EXISTS(select c.c_problem_ctgr 
													   from E_AST_KATEGORI_PROBLEMTI_TM C
													   where A.c_problem_ctgr = c.c_problem_ctgr)				 
										order by d_barang_ajuanbaik 
										limit $xLimit offset $xOffset",$where)	;
				
								
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{										
					$n_peg 				= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_helpdesk);	
										
										
					// Awal : Pencari Nama Pemohon dan org Pemohon									
					$n_peg_nippemohon 	= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nippemohon);	
					
					if ($n_peg_nippemohon==null || $n_peg_nippemohon == '')
					{
					     $n_peg_nippemohon 	=  $db->fetchCol('select n_peg_bumn  
														from  e_ast_problemti_0_tm 
														where i_barang_ajuanbaik = ? ',$setuju[$j]->i_barang_ajuanbaik);	
														
					     $n_orgb 	= $db->fetchCol('select n_bumn  
														from  a_bumn_0_0_tr 
														where i_bumn = ? ',$setuju[$j]->i_peg_nippemohon);	
					}	else
					{
					     $n_orgb 			= $db->fetchCol('select n_orgb  
														from  e_org_0_0_tm 
														where i_orgb = ? ',$setuju[$j]->i_orgb);
					}									
					// Akhir  : Pencari Nama Pemohon dan org Pemohon 					
					
					$n_jabatan 			= $db->fetchCol('select n_jabatan  
														from  e_sdm_jabatan_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nippemohon);					
																		
					$hasilList[$j] = array("i_barang_ajuanbaik"		=>(string)$setuju[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" 	=>(string)$setuju[$j]->d_barang_ajuanbaik,
											 "i_orgb"				=>(string)$setuju[$j]->i_orgb,
											 "i_barang_penerimaan"	=>(string)$setuju[$j]->i_barang_penerimaan,
											 "d_anggaran"			=>(string)$setuju[$j]->d_anggaran,
											 "c_barang"				=>(string)$setuju[$j]->c_barang,
											 "i_aset"				=>(string)$setuju[$j]->i_aset,
											 "i_peg_nip"			=>(string)$setuju[$j]->i_peg_nippemohon,
											 "i_peg_helpdesk"		=>(string)$setuju[$j]->i_peg_helpdesk,
											 "d_perolehan"			=>(string)$setuju[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$setuju[$j]->e_barang_perbaikan,
											 "c_problem_ctgr"		=>(string)$setuju[$j]->c_problem_ctgr,
											 "n_problem_ctgr"		=>(string)$setuju[$j]->n_problem_ctgr,
											 "n_peg"				=>$n_peg[0],
											 "c_status_perbaikan"	=>(string)$setuju[$j]->c_status_perbaikan,
											 "c_barang_perbaikan"	=>(string)$setuju[$j]->c_barang_perbaikan,
											 "e_saran_sparepart"	=>(string)$setuju[$j]->e_saran_sparepart,
											 "e_saran_pihakketiga"	=>(string)$setuju[$j]->e_saran_pihakketiga,
											 "i_disposisi"			=>(string)$setuju[$j]->i_disposisi,
											 "n_peg_nippemohon"		=>$n_peg_nippemohon[0],
											 "n_jabatan"			=>$n_jabatan[0],
											 "n_orgb"				=>$n_orgb[0],
											 "tindakan"				=>(string)$setuju[$j]->tindakan,
											 "tglselesai"			=>(string)$setuju[$j]->tglselesai,
											 "q_jam_perbaikan"		=>(string)$setuju[$j]->q_jam_perbaikan,
											 "d_problem_awal"		=>(string)$setuju[$j]->d_problem_awal,
											 "d_awal_perbaikan"		=>(string)$setuju[$j]->d_awal_perbaikan
											 );
				}
			}	
			
			return $hasilList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	
	public function getPermasalahan_PeriodeNull_DgnKategori($pageNumber,$itemPerPage,$nipPenerima,$status,$jenisProblem, $kategoriProblem)
	{
		$nipPenerima = strtoupper($nipPenerima);
		$nipPenerima = '%'.$nipPenerima.'%';
		$status = strtoupper($status);
		$status = '%'.$status.'%';
		$jenisProblem = strtoupper($jenisProblem);
		$jenisProblem = '%'.$jenisProblem.'%';
		$kategoriProblem = strtoupper($kategoriProblem);
		$kategoriProblem = '%'.$kategoriProblem.'%';
		
		try {
			
			$where[] = $nipPenerima;
			$where[] = $status;
			$where[] = $jenisProblem;
			$where[] = $kategoriProblem;
			
			
			$where[] = $nipPenerima;
			$where[] = $status;
			$where[] = $jenisProblem;
			$where[] = $kategoriProblem;
									
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasil = $db->fetchAll("Select a.i_barang_ajuanbaik,a.d_barang_ajuanbaik,a.d_anggaran,
										a.c_barang,to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,i_peg_helpdesk,
										a.e_barang_perbaikan, a.c_problem_ctgr,n_problem_ctgr, c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,c_status_perbaikan,
										c_barang_perbaikan, e_saran_sparepart, e_saran_pihakketiga,
										i_disposisi, i_orgb,e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan as tglselesai, q_jam_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B, E_AST_KATEGORI_PROBLEMTI_TM C
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										And A.c_problem_ctgr = c.c_problem_ctgr										
										and upper(i_peg_helpdesk) like ?
										and c_status_perbaikan like ?
										And C.C_jenis_problem like ?
										And A.C_problem_ctgr like ?										
										UNION
										SELECT i_barang_ajuanbaik, d_barang_ajuanbaik, null as d_anggaran,
										null as c_barang, null as i_aset,i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk, e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, n_problem_ctgr, c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,
										c_status_perbaikan,'' as c_barang_perbaikan,'' as e_saran_sparepart,
										'' as e_saran_pihakketiga, '' as i_disposisi, i_orgb,e_problem_solusi as tindakan, 
										d_akhir_perbaikan as tglselesai, null as q_jam_perbaikan
										FROM e_ast_problemti_0_tm a,E_AST_KATEGORI_PROBLEMTI_TM C
										WHERE A.c_problem_ctgr = c.c_problem_ctgr										
										and upper(i_peg_nip) like ?
										and c_status_perbaikan like ?										
										And C.C_jenis_problem like ?
										And A.C_problem_ctgr like ?											
										order by d_barang_ajuanbaik 
										",$where)	;
				$hasilList = count($hasil);
			}
			else 
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				$setuju = $db->fetchAll("Select a.i_barang_ajuanbaik,a.d_barang_ajuanbaik,a.d_anggaran,
										a.c_barang,to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,i_peg_helpdesk,
										a.e_barang_perbaikan, a.c_problem_ctgr,n_problem_ctgr, c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,c_status_perbaikan,
										c_barang_perbaikan, e_saran_sparepart, e_saran_pihakketiga,
										i_disposisi, i_orgb,e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan as tglselesai, q_jam_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B, E_AST_KATEGORI_PROBLEMTI_TM C
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										And A.c_problem_ctgr = c.c_problem_ctgr										
										and upper(i_peg_helpdesk) like ?
										and c_status_perbaikan like ?
										And C.C_jenis_problem like ?
										And A.C_problem_ctgr like ?										
										UNION
										SELECT i_barang_ajuanbaik, d_barang_ajuanbaik, null as d_anggaran,
										null as c_barang, null as i_aset,i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk, e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, n_problem_ctgr, c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,
										c_status_perbaikan,'' as c_barang_perbaikan,'' as e_saran_sparepart,
										'' as e_saran_pihakketiga, '' as i_disposisi, i_orgb,e_problem_solusi as tindakan, 
										d_akhir_perbaikan as tglselesai, null as q_jam_perbaikan
										FROM e_ast_problemti_0_tm a,E_AST_KATEGORI_PROBLEMTI_TM C
										WHERE A.c_problem_ctgr = c.c_problem_ctgr										
										and upper(i_peg_nip) like ?
										and c_status_perbaikan like ?										
										And C.C_jenis_problem like ?
										And A.C_problem_ctgr like ?										
										order by d_barang_ajuanbaik 
										limit $xLimit offset $xOffset",$where)	;
				
								
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{										
					$n_peg 				= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_helpdesk);	
										
										
					// Awal : Pencari Nama Pemohon dan org Pemohon									
					$n_peg_nippemohon 	= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nippemohon);	
					
					if ($n_peg_nippemohon==null || $n_peg_nippemohon == '')
					{
					     $n_peg_nippemohon 	=  $db->fetchCol('select n_peg_bumn  
														from  e_ast_problemti_0_tm 
														where i_barang_ajuanbaik = ? ',$setuju[$j]->i_barang_ajuanbaik);	
														
					     $n_orgb 	= $db->fetchCol('select n_bumn  
														from  a_bumn_0_0_tr 
														where i_bumn = ? ',$setuju[$j]->i_peg_nippemohon);	
					}	else
					{
					     $n_orgb 			= $db->fetchCol('select n_orgb  
														from  e_org_0_0_tm 
														where i_orgb = ? ',$setuju[$j]->i_orgb);
					}									
					// Akhir  : Pencari Nama Pemohon dan org Pemohon 					
					
					$n_jabatan 			= $db->fetchCol('select n_jabatan  
														from  e_sdm_jabatan_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nippemohon);					
																		
					$hasilList[$j] = array("i_barang_ajuanbaik"		=>(string)$setuju[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" 	=>(string)$setuju[$j]->d_barang_ajuanbaik,
											 "i_orgb"				=>(string)$setuju[$j]->i_orgb,
											 "i_barang_penerimaan"	=>(string)$setuju[$j]->i_barang_penerimaan,
											 "d_anggaran"			=>(string)$setuju[$j]->d_anggaran,
											 "c_barang"				=>(string)$setuju[$j]->c_barang,
											 "i_aset"				=>(string)$setuju[$j]->i_aset,
											 "i_peg_nip"			=>(string)$setuju[$j]->i_peg_nippemohon,
											 "i_peg_helpdesk"		=>(string)$setuju[$j]->i_peg_helpdesk,
											 "d_perolehan"			=>(string)$setuju[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$setuju[$j]->e_barang_perbaikan,
											 "c_problem_ctgr"		=>(string)$setuju[$j]->c_problem_ctgr,
											 "n_problem_ctgr"		=>(string)$setuju[$j]->n_problem_ctgr,
											 "n_peg"				=>$n_peg[0],
											 "c_status_perbaikan"	=>(string)$setuju[$j]->c_status_perbaikan,
											 "c_barang_perbaikan"	=>(string)$setuju[$j]->c_barang_perbaikan,
											 "e_saran_sparepart"	=>(string)$setuju[$j]->e_saran_sparepart,
											 "e_saran_pihakketiga"	=>(string)$setuju[$j]->e_saran_pihakketiga,
											 "i_disposisi"			=>(string)$setuju[$j]->i_disposisi,
											 "n_peg_nippemohon"		=>$n_peg_nippemohon[0],
											 "n_jabatan"			=>$n_jabatan[0],
											 "n_orgb"				=>$n_orgb[0],
											 "tindakan"				=>(string)$setuju[$j]->tindakan,
											 "tglselesai"			=>(string)$setuju[$j]->tglselesai,
											 "q_jam_perbaikan"		=>(string)$setuju[$j]->q_jam_perbaikan,
											 "d_problem_awal"		=>(string)$setuju[$j]->d_problem_awal,
											 "d_awal_perbaikan"		=>(string)$setuju[$j]->d_awal_perbaikan
											 );
				}
			}	
			
			return $hasilList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	// Ina :Akhir  : 28-10-2008 : Service Baru
	
	// Ina : Awal : 30-10-2008 :
	public function getPermasalahan_AllCetak($prdAwal,$prdAkhir,$nipPenerima,$status,$jenisProblem, $kategoriProblem)
	{
		$nipPenerima = strtoupper($nipPenerima);
		$nipPenerima = '%'.$nipPenerima.'%';
		
		$status = strtoupper($status);
		$status = '%'.$status.'%';
		
		$jenisProblem = strtoupper($jenisProblem);
		$jenisProblem = '%'.$jenisProblem.'%';
		
		$kategoriProblem = strtoupper($kategoriProblem);
		$kategoriProblem = '%'.$kategoriProblem.'%';
		
		try {
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			$where[] = $nipPenerima;
			$where[] = $status;
			$where[] = $jenisProblem;
			$where[] = $kategoriProblem;
			
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			$where[] = $nipPenerima;
			$where[] = $status;
			$where[] = $jenisProblem;
			$where[] = $kategoriProblem;
			
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			$where[] = $nipPenerima;
			$where[] = $status;			
			
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			$where[] = $nipPenerima;
			$where[] = $status;			
			
			
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
							
				$setuju = $db->fetchAll("Select a.i_barang_ajuanbaik,a.d_barang_ajuanbaik,a.d_anggaran,
										a.c_barang,to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,i_peg_helpdesk,
										a.e_barang_perbaikan, a.c_problem_ctgr,n_problem_ctgr, c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,c_status_perbaikan,
										c_barang_perbaikan, e_saran_sparepart, e_saran_pihakketiga,
										i_disposisi, i_orgb,e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan as tglselesai, q_jam_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B, E_AST_KATEGORI_PROBLEMTI_TM C
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										And A.c_problem_ctgr = c.c_problem_ctgr
										and (d_problem_awal between ? and ?)
										and upper(i_peg_helpdesk) like ?
										and c_status_perbaikan like ?
										And C.C_jenis_problem like ?
										And A.C_problem_ctgr like ?										
										UNION
										SELECT i_barang_ajuanbaik, d_barang_ajuanbaik, null as d_anggaran,
										null as c_barang, null as i_aset,i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk, e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, n_problem_ctgr, c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,
										c_status_perbaikan,'' as c_barang_perbaikan,'' as e_saran_sparepart,
										'' as e_saran_pihakketiga, '' as i_disposisi, i_orgb,e_problem_solusi as tindakan, 
										d_akhir_perbaikan as tglselesai, null as q_jam_perbaikan
										FROM e_ast_problemti_0_tm a,E_AST_KATEGORI_PROBLEMTI_TM C
										WHERE A.c_problem_ctgr = c.c_problem_ctgr
										and (d_problem_awal between ? and ?)
										and upper(i_peg_nip) like ?
										and c_status_perbaikan like ?										
										And C.C_jenis_problem like ?
										And A.C_problem_ctgr like ?
										UNION
										SELECT i_barang_ajuanbaik, d_barang_ajuanbaik, null as d_anggaran,
										null as c_barang, null as i_aset,i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk, e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, '' as n_problem_ctgr, '' as c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,
										c_status_perbaikan,'' as c_barang_perbaikan,'' as e_saran_sparepart,
										'' as e_saran_pihakketiga, '' as i_disposisi, i_orgb,e_problem_solusi as tindakan, 
										d_akhir_perbaikan as tglselesai, null as q_jam_perbaikan
										FROM e_ast_problemti_0_tm a										
										WHERE  (a.d_problem_awal between ? and ?)
										and upper(a.i_peg_nip) like ?
										and a.c_status_perbaikan like ?																				
										and not EXISTS(select c.c_problem_ctgr 
										                 from E_AST_KATEGORI_PROBLEMTI_TM C
										                 where A.c_problem_ctgr = c.c_problem_ctgr)	
										UNION
										Select a.i_barang_ajuanbaik,a.d_barang_ajuanbaik,a.d_anggaran,
										a.c_barang,to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,i_peg_helpdesk,
										a.e_barang_perbaikan, a.c_problem_ctgr, '' as n_problem_ctgr, '' as c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,c_status_perbaikan,
										c_barang_perbaikan, e_saran_sparepart, e_saran_pihakketiga,
										i_disposisi, i_orgb,e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan as tglselesai, q_jam_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and (d_problem_awal between ? and ?)
										and upper(i_peg_helpdesk) like ?
										and c_status_perbaikan like ?
										and not EXISTS(select c.c_problem_ctgr 
													   from E_AST_KATEGORI_PROBLEMTI_TM C
													   where A.c_problem_ctgr = c.c_problem_ctgr)				 
										order by d_barang_ajuanbaik ",$where);
				
								
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{										
					$n_peg 				= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_helpdesk);	
										
					
					
					// Awal : Pencari Nama Pemohon dan org Pemohon									
					$n_peg_nippemohon 	= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nippemohon);	
					
					if ($n_peg_nippemohon==null || $n_peg_nippemohon == '')
					{
					     $n_peg_nippemohon 	=  $db->fetchCol('select n_peg_bumn  
														from  e_ast_problemti_0_tm 
														where i_barang_ajuanbaik = ? ',$setuju[$j]->i_barang_ajuanbaik);	
														
					     $n_orgb 	= $db->fetchCol('select n_bumn  
														from  a_bumn_0_0_tr 
														where i_bumn = ? ',$setuju[$j]->i_peg_nippemohon);	
					}	else
					{
					     $n_orgb 			= $db->fetchCol('select n_orgb  
														from  e_org_0_0_tm 
														where i_orgb = ? ',$setuju[$j]->i_orgb);
					}									
					// Akhir  : Pencari Nama Pemohon dan org Pemohon 					
					
					$n_jabatan 			= $db->fetchCol('select n_jabatan  
														from  e_sdm_jabatan_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nippemohon);					
				
					
					$hasilList[$j] = array("i_barang_ajuanbaik"		=>(string)$setuju[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" 	=>(string)$setuju[$j]->d_barang_ajuanbaik,
											 "i_orgb"				=>(string)$setuju[$j]->i_orgb,
											 "i_barang_penerimaan"	=>(string)$setuju[$j]->i_barang_penerimaan,
											 "d_anggaran"			=>(string)$setuju[$j]->d_anggaran,
											 "c_barang"				=>(string)$setuju[$j]->c_barang,
											 "i_aset"				=>(string)$setuju[$j]->i_aset,
											 "i_peg_nip"			=>(string)$setuju[$j]->i_peg_nippemohon,
											 "i_peg_helpdesk"		=>(string)$setuju[$j]->i_peg_helpdesk,
											 "d_perolehan"			=>(string)$setuju[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$setuju[$j]->e_barang_perbaikan,
											 "c_problem_ctgr"		=>(string)$setuju[$j]->c_problem_ctgr,
											 "n_problem_ctgr"		=>(string)$setuju[$j]->n_problem_ctgr,
											 "n_peg"				=>$n_peg[0],
											 "c_status_perbaikan"	=>(string)$setuju[$j]->c_status_perbaikan,
											 "c_barang_perbaikan"	=>(string)$setuju[$j]->c_barang_perbaikan,
											 "e_saran_sparepart"	=>(string)$setuju[$j]->e_saran_sparepart,
											 "e_saran_pihakketiga"	=>(string)$setuju[$j]->e_saran_pihakketiga,
											 "i_disposisi"			=>(string)$setuju[$j]->i_disposisi,
											 "n_peg_nippemohon"		=>$n_peg_nippemohon[0],
											 "n_jabatan"			=>$n_jabatan[0],
											 "n_orgb"				=>$n_orgb[0],
											 "tindakan"				=>(string)$setuju[$j]->tindakan,
											 "tglselesai"			=>(string)$setuju[$j]->tglselesai,
											 "q_jam_perbaikan"		=>(string)$setuju[$j]->q_jam_perbaikan,
											 "d_problem_awal"		=>(string)$setuju[$j]->d_problem_awal,
											 "d_awal_perbaikan"		=>(string)$setuju[$j]->d_awal_perbaikan
											 );
				}
				
			
			return $hasilList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	
	public function getPermasalahan_All_DgnKategori_Cetak($prdAwal,$prdAkhir,$nipPenerima,$status,$jenisProblem, $kategoriProblem)
	{
		$nipPenerima = strtoupper($nipPenerima);
		$nipPenerima = '%'.$nipPenerima.'%';
		
		$status = strtoupper($status);
		$status = '%'.$status.'%';
		
		$jenisProblem = strtoupper($jenisProblem);
		$jenisProblem = '%'.$jenisProblem.'%';
		
		$kategoriProblem = strtoupper($kategoriProblem);
		$kategoriProblem = '%'.$kategoriProblem.'%';
		
		try {
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			$where[] = $nipPenerima;
			$where[] = $status;
			$where[] = $jenisProblem;
			$where[] = $kategoriProblem;
			
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			$where[] = $nipPenerima;
			$where[] = $status;
			$where[] = $jenisProblem;
			$where[] = $kategoriProblem;
			
			
			
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
							
				$setuju = $db->fetchAll("Select a.i_barang_ajuanbaik,a.d_barang_ajuanbaik,a.d_anggaran,
										a.c_barang,to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,i_peg_helpdesk,
										a.e_barang_perbaikan, a.c_problem_ctgr,n_problem_ctgr, c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,c_status_perbaikan,
										c_barang_perbaikan, e_saran_sparepart, e_saran_pihakketiga,
										i_disposisi, i_orgb,e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan as tglselesai, q_jam_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B, E_AST_KATEGORI_PROBLEMTI_TM C
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										And A.c_problem_ctgr = c.c_problem_ctgr
										and (d_problem_awal between ? and ?)
										and upper(i_peg_helpdesk) like ?
										and c_status_perbaikan like ?
										And C.C_jenis_problem like ?
										And A.C_problem_ctgr like ?										
										UNION
										SELECT i_barang_ajuanbaik, d_barang_ajuanbaik, null as d_anggaran,
										null as c_barang, null as i_aset,i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk, e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, n_problem_ctgr, c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,
										c_status_perbaikan,'' as c_barang_perbaikan,'' as e_saran_sparepart,
										'' as e_saran_pihakketiga, '' as i_disposisi, i_orgb,e_problem_solusi as tindakan, 
										d_akhir_perbaikan as tglselesai, null as q_jam_perbaikan
										FROM e_ast_problemti_0_tm a,E_AST_KATEGORI_PROBLEMTI_TM C
										WHERE A.c_problem_ctgr = c.c_problem_ctgr
										and (d_problem_awal between ? and ?)
										and upper(i_peg_nip) like ?
										and c_status_perbaikan like ?										
										And C.C_jenis_problem like ?
										And A.C_problem_ctgr like ?										
										order by d_barang_ajuanbaik  ",$where);
				
								
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{										
					$n_peg 				= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_helpdesk);	
										
					
					
					// Awal : Pencari Nama Pemohon dan org Pemohon									
					$n_peg_nippemohon 	= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nippemohon);	
					
					if ($n_peg_nippemohon==null || $n_peg_nippemohon == '')
					{
					     $n_peg_nippemohon 	=  $db->fetchCol('select n_peg_bumn  
														from  e_ast_problemti_0_tm 
														where i_barang_ajuanbaik = ? ',$setuju[$j]->i_barang_ajuanbaik);	
														
					     $n_orgb 	= $db->fetchCol('select n_bumn  
														from  a_bumn_0_0_tr 
														where i_bumn = ? ',$setuju[$j]->i_peg_nippemohon);	
					}	else
					{
					     $n_orgb 			= $db->fetchCol('select n_orgb  
														from  e_org_0_0_tm 
														where i_orgb = ? ',$setuju[$j]->i_orgb);
					}									
					// Akhir  : Pencari Nama Pemohon dan org Pemohon 					
					
					$n_jabatan 			= $db->fetchCol('select n_jabatan  
														from  e_sdm_jabatan_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nippemohon);					
				
					
					$hasilList[$j] = array("i_barang_ajuanbaik"		=>(string)$setuju[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" 	=>(string)$setuju[$j]->d_barang_ajuanbaik,
											 "i_orgb"				=>(string)$setuju[$j]->i_orgb,
											 "i_barang_penerimaan"	=>(string)$setuju[$j]->i_barang_penerimaan,
											 "d_anggaran"			=>(string)$setuju[$j]->d_anggaran,
											 "c_barang"				=>(string)$setuju[$j]->c_barang,
											 "i_aset"				=>(string)$setuju[$j]->i_aset,
											 "i_peg_nip"			=>(string)$setuju[$j]->i_peg_nippemohon,
											 "i_peg_helpdesk"		=>(string)$setuju[$j]->i_peg_helpdesk,
											 "d_perolehan"			=>(string)$setuju[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$setuju[$j]->e_barang_perbaikan,
											 "c_problem_ctgr"		=>(string)$setuju[$j]->c_problem_ctgr,
											 "n_problem_ctgr"		=>(string)$setuju[$j]->n_problem_ctgr,
											 "n_peg"				=>$n_peg[0],
											 "c_status_perbaikan"	=>(string)$setuju[$j]->c_status_perbaikan,
											 "c_barang_perbaikan"	=>(string)$setuju[$j]->c_barang_perbaikan,
											 "e_saran_sparepart"	=>(string)$setuju[$j]->e_saran_sparepart,
											 "e_saran_pihakketiga"	=>(string)$setuju[$j]->e_saran_pihakketiga,
											 "i_disposisi"			=>(string)$setuju[$j]->i_disposisi,
											 "n_peg_nippemohon"		=>$n_peg_nippemohon[0],
											 "n_jabatan"			=>$n_jabatan[0],
											 "n_orgb"				=>$n_orgb[0],
											 "tindakan"				=>(string)$setuju[$j]->tindakan,
											 "tglselesai"			=>(string)$setuju[$j]->tglselesai,
											 "q_jam_perbaikan"		=>(string)$setuju[$j]->q_jam_perbaikan,
											 "d_problem_awal"		=>(string)$setuju[$j]->d_problem_awal,
											 "d_awal_perbaikan"		=>(string)$setuju[$j]->d_awal_perbaikan
											 );
				}
				
			
			return $hasilList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	public function getPermasalahan_PeriodeNullCetak($nipPenerima,$status,$jenisProblem, $kategoriProblem)
	{
		$nipPenerima = strtoupper($nipPenerima);
		$nipPenerima = '%'.$nipPenerima.'%';
		
		$status = strtoupper($status);
		$status = '%'.$status.'%';
		
		$jenisProblem = strtoupper($jenisProblem);
		$jenisProblem = '%'.$jenisProblem.'%';
		
		$kategoriProblem = strtoupper($kategoriProblem);
		$kategoriProblem = '%'.$kategoriProblem.'%';
		
		try {
			
			$where[] = $nipPenerima;
			$where[] = $status;
			$where[] = $jenisProblem;
			$where[] = $kategoriProblem;
			
			
			$where[] = $nipPenerima;
			$where[] = $status;
			$where[] = $jenisProblem;
			$where[] = $kategoriProblem;
			
			
			$where[] = $nipPenerima;
			$where[] = $status;			
			
			$where[] = $nipPenerima;
			$where[] = $status;			
			
			
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
						
				$setuju = $db->fetchAll("Select a.i_barang_ajuanbaik,a.d_barang_ajuanbaik,a.d_anggaran,
										a.c_barang,to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,i_peg_helpdesk,
										a.e_barang_perbaikan, a.c_problem_ctgr,n_problem_ctgr, c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,c_status_perbaikan,
										c_barang_perbaikan, e_saran_sparepart, e_saran_pihakketiga,
										i_disposisi, i_orgb,e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan as tglselesai, q_jam_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B, E_AST_KATEGORI_PROBLEMTI_TM C
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										And A.c_problem_ctgr = c.c_problem_ctgr										
										and upper(i_peg_helpdesk) like ?
										and c_status_perbaikan like ?
										And C.C_jenis_problem like ?
										And A.C_problem_ctgr like ?										
										UNION
										SELECT i_barang_ajuanbaik, d_barang_ajuanbaik, null as d_anggaran,
										null as c_barang, null as i_aset,i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk, e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, n_problem_ctgr, c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,
										c_status_perbaikan,'' as c_barang_perbaikan,'' as e_saran_sparepart,
										'' as e_saran_pihakketiga, '' as i_disposisi, i_orgb,e_problem_solusi as tindakan, 
										d_akhir_perbaikan as tglselesai, null as q_jam_perbaikan
										FROM e_ast_problemti_0_tm a,E_AST_KATEGORI_PROBLEMTI_TM C
										WHERE A.c_problem_ctgr = c.c_problem_ctgr										
										and upper(i_peg_nip) like ?
										and c_status_perbaikan like ?										
										And C.C_jenis_problem like ?
										And A.C_problem_ctgr like ?	
										UNION
										SELECT i_barang_ajuanbaik, d_barang_ajuanbaik, null as d_anggaran,
										null as c_barang, null as i_aset,i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk, e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, '' as n_problem_ctgr, '' as c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,
										c_status_perbaikan,'' as c_barang_perbaikan,'' as e_saran_sparepart,
										'' as e_saran_pihakketiga, '' as i_disposisi, i_orgb,e_problem_solusi as tindakan, 
										d_akhir_perbaikan as tglselesai, null as q_jam_perbaikan
										FROM e_ast_problemti_0_tm a										
										WHERE  upper(a.i_peg_nip) like ?
										and a.c_status_perbaikan like ?											
										and not EXISTS(select c. c_problem_ctgr 
										                 from E_AST_KATEGORI_PROBLEMTI_TM C
										                 where A.c_problem_ctgr = c.c_problem_ctgr)	
										UNION
										Select a.i_barang_ajuanbaik,a.d_barang_ajuanbaik,a.d_anggaran,
										a.c_barang,to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,i_peg_helpdesk,
										a.e_barang_perbaikan, a.c_problem_ctgr, '' as n_problem_ctgr, '' as c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,c_status_perbaikan,
										c_barang_perbaikan, e_saran_sparepart, e_saran_pihakketiga,
										i_disposisi, i_orgb,e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan as tglselesai, q_jam_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and upper(i_peg_helpdesk) like ?
										and c_status_perbaikan like ?
										and not EXISTS(select c.c_problem_ctgr 
													   from E_AST_KATEGORI_PROBLEMTI_TM C
													   where A.c_problem_ctgr = c.c_problem_ctgr)				 
										order by d_barang_ajuanbaik  ",$where);
				
								
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{										
					$n_peg 				= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_helpdesk);	
										
										
					// Awal : Pencari Nama Pemohon dan org Pemohon									
					$n_peg_nippemohon 	= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nippemohon);	
					
					if ($n_peg_nippemohon==null || $n_peg_nippemohon == '')
					{
					     $n_peg_nippemohon 	=  $db->fetchCol('select n_peg_bumn  
														from  e_ast_problemti_0_tm 
														where i_barang_ajuanbaik = ? ',$setuju[$j]->i_barang_ajuanbaik);	
														
					     $n_orgb 	= $db->fetchCol('select n_bumn  
														from  a_bumn_0_0_tr 
														where i_bumn = ? ',$setuju[$j]->i_peg_nippemohon);	
					}	else
					{
					     $n_orgb 			= $db->fetchCol('select n_orgb  
														from  e_org_0_0_tm 
														where i_orgb = ? ',$setuju[$j]->i_orgb);
					}									
					// Akhir  : Pencari Nama Pemohon dan org Pemohon 					
					
					$n_jabatan 			= $db->fetchCol('select n_jabatan  
														from  e_sdm_jabatan_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nippemohon);					
				
					
														
					$hasilList[$j] = array("i_barang_ajuanbaik"		=>(string)$setuju[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" 	=>(string)$setuju[$j]->d_barang_ajuanbaik,
											 "i_orgb"				=>(string)$setuju[$j]->i_orgb,
											 "i_barang_penerimaan"	=>(string)$setuju[$j]->i_barang_penerimaan,
											 "d_anggaran"			=>(string)$setuju[$j]->d_anggaran,
											 "c_barang"				=>(string)$setuju[$j]->c_barang,
											 "i_aset"				=>(string)$setuju[$j]->i_aset,
											 "i_peg_nip"			=>(string)$setuju[$j]->i_peg_nippemohon,
											 "i_peg_helpdesk"		=>(string)$setuju[$j]->i_peg_helpdesk,
											 "d_perolehan"			=>(string)$setuju[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$setuju[$j]->e_barang_perbaikan,
											 "c_problem_ctgr"		=>(string)$setuju[$j]->c_problem_ctgr,
											 "n_problem_ctgr"		=>(string)$setuju[$j]->n_problem_ctgr,
											 "n_peg"				=>$n_peg[0],
											 "c_status_perbaikan"	=>(string)$setuju[$j]->c_status_perbaikan,
											 "c_barang_perbaikan"	=>(string)$setuju[$j]->c_barang_perbaikan,
											 "e_saran_sparepart"	=>(string)$setuju[$j]->e_saran_sparepart,
											 "e_saran_pihakketiga"	=>(string)$setuju[$j]->e_saran_pihakketiga,
											 "i_disposisi"			=>(string)$setuju[$j]->i_disposisi,
											 "n_peg_nippemohon"		=>$n_peg_nippemohon[0],
											 "n_jabatan"			=>$n_jabatan[0],
											 "n_orgb"				=>$n_orgb[0],
											 "tindakan"				=>(string)$setuju[$j]->tindakan,
											 "tglselesai"			=>(string)$setuju[$j]->tglselesai,
											 "q_jam_perbaikan"		=>(string)$setuju[$j]->q_jam_perbaikan,
											 "d_problem_awal"		=>(string)$setuju[$j]->d_problem_awal,
											 "d_awal_perbaikan"		=>(string)$setuju[$j]->d_awal_perbaikan
											 );
				}
				
			
			return $hasilList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	public function getPermasalahan_PeriodeNull_DgnKategori_Cetak($nipPenerima,$status,$jenisProblem, $kategoriProblem)
	{
		$nipPenerima = strtoupper($nipPenerima);
		$nipPenerima = '%'.$nipPenerima.'%';
		
		$status = strtoupper($status);
		$status = '%'.$status.'%';
		
		$jenisProblem = strtoupper($jenisProblem);
		$jenisProblem = '%'.$jenisProblem.'%';
		
		$kategoriProblem = strtoupper($kategoriProblem);
		$kategoriProblem = '%'.$kategoriProblem.'%';
		
		try {
			
			$where[] = $nipPenerima;
			$where[] = $status;
			$where[] = $jenisProblem;
			$where[] = $kategoriProblem;
			
			
			$where[] = $nipPenerima;
			$where[] = $status;
			$where[] = $jenisProblem;
			$where[] = $kategoriProblem;
			
			
			
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
						
				$setuju = $db->fetchAll("Select a.i_barang_ajuanbaik,a.d_barang_ajuanbaik,a.d_anggaran,
										a.c_barang,to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,i_peg_helpdesk,
										a.e_barang_perbaikan, a.c_problem_ctgr,n_problem_ctgr, c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,c_status_perbaikan,
										c_barang_perbaikan, e_saran_sparepart, e_saran_pihakketiga,
										i_disposisi, i_orgb,e_tindakan_perbaikan as tindakan,
										d_akhir_perbaikan as tglselesai, q_jam_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B, E_AST_KATEGORI_PROBLEMTI_TM C
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										And A.c_problem_ctgr = c.c_problem_ctgr										
										and upper(i_peg_helpdesk) like ?
										and c_status_perbaikan like ?
										And C.C_jenis_problem like ?
										And A.C_problem_ctgr like ?										
										UNION
										SELECT i_barang_ajuanbaik, d_barang_ajuanbaik, null as d_anggaran,
										null as c_barang, null as i_aset,i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk, e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, n_problem_ctgr, c_jenis_problem,
										d_problem_awal,d_awal_perbaikan,
										c_status_perbaikan,'' as c_barang_perbaikan,'' as e_saran_sparepart,
										'' as e_saran_pihakketiga, '' as i_disposisi, i_orgb,e_problem_solusi as tindakan, 
										d_akhir_perbaikan as tglselesai, null as q_jam_perbaikan
										FROM e_ast_problemti_0_tm a,E_AST_KATEGORI_PROBLEMTI_TM C
										WHERE A.c_problem_ctgr = c.c_problem_ctgr	
										and upper(i_peg_nip) like ?
										and c_status_perbaikan like ?	
										And C.C_jenis_problem like ?
										And A.C_problem_ctgr like ?																				
										order by d_barang_ajuanbaik ",$where);
				
								
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{										
					$n_peg 				= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_helpdesk);	
										
										
					// Awal : Pencari Nama Pemohon dan org Pemohon									
					$n_peg_nippemohon 	= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nippemohon);	
					
					if ($n_peg_nippemohon==null || $n_peg_nippemohon == '')
					{
					     $n_peg_nippemohon 	=  $db->fetchCol('select n_peg_bumn  
														from  e_ast_problemti_0_tm 
														where i_barang_ajuanbaik = ? ',$setuju[$j]->i_barang_ajuanbaik);	
														
					     $n_orgb 	= $db->fetchCol('select n_bumn  
														from  a_bumn_0_0_tr 
														where i_bumn = ? ',$setuju[$j]->i_peg_nippemohon);	
					}	else
					{
					     $n_orgb 			= $db->fetchCol('select n_orgb  
														from  e_org_0_0_tm 
														where i_orgb = ? ',$setuju[$j]->i_orgb);
					}									
					// Akhir  : Pencari Nama Pemohon dan org Pemohon 					
					
					$n_jabatan 			= $db->fetchCol('select n_jabatan  
														from  e_sdm_jabatan_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nippemohon);					
				
					
														
					$hasilList[$j] = array("i_barang_ajuanbaik"		=>(string)$setuju[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" 	=>(string)$setuju[$j]->d_barang_ajuanbaik,
											 "i_orgb"				=>(string)$setuju[$j]->i_orgb,
											 "i_barang_penerimaan"	=>(string)$setuju[$j]->i_barang_penerimaan,
											 "d_anggaran"			=>(string)$setuju[$j]->d_anggaran,
											 "c_barang"				=>(string)$setuju[$j]->c_barang,
											 "i_aset"				=>(string)$setuju[$j]->i_aset,
											 "i_peg_nip"			=>(string)$setuju[$j]->i_peg_nippemohon,
											 "i_peg_helpdesk"		=>(string)$setuju[$j]->i_peg_helpdesk,
											 "d_perolehan"			=>(string)$setuju[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$setuju[$j]->e_barang_perbaikan,
											 "c_problem_ctgr"		=>(string)$setuju[$j]->c_problem_ctgr,
											 "n_problem_ctgr"		=>(string)$setuju[$j]->n_problem_ctgr,
											 "n_peg"				=>$n_peg[0],
											 "c_status_perbaikan"	=>(string)$setuju[$j]->c_status_perbaikan,
											 "c_barang_perbaikan"	=>(string)$setuju[$j]->c_barang_perbaikan,
											 "e_saran_sparepart"	=>(string)$setuju[$j]->e_saran_sparepart,
											 "e_saran_pihakketiga"	=>(string)$setuju[$j]->e_saran_pihakketiga,
											 "i_disposisi"			=>(string)$setuju[$j]->i_disposisi,
											 "n_peg_nippemohon"		=>$n_peg_nippemohon[0],
											 "n_jabatan"			=>$n_jabatan[0],
											 "n_orgb"				=>$n_orgb[0],
											 "tindakan"				=>(string)$setuju[$j]->tindakan,
											 "tglselesai"			=>(string)$setuju[$j]->tglselesai,
											 "q_jam_perbaikan"		=>(string)$setuju[$j]->q_jam_perbaikan,
											 "d_problem_awal"		=>(string)$setuju[$j]->d_problem_awal,
											 "d_awal_perbaikan"		=>(string)$setuju[$j]->d_awal_perbaikan
											 );
				}
				
			
			return $hasilList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	// Ina : Akhir : 30-10-2008 
	
	//awal Endri :30-10-2008
	
	function getKategoriProblem($pageNumber, $itemPerPage,$jnsperb) 
     { 	
	   	
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   //echo"<br>jnsperbX> ".$jnsperb;
	   try {
	     //echo "TEST CONN ".$db->getConnection()."<br>";
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		  if(($pageNumber==0) && ($itemPerPage==0))
		 {  /*
			$hasilAkhir = $db->fetchOne("select count(distinct a.i_peg_nip) from e_sdm_peg_latih_tm a, e_sdm_pegawai_0_tm b
									where a.i_peg_nip=b.i_peg_nip
									and upper(a.n_pend_latih) like'%PENGADAAN%'
									and upper(b.n_peg) like'%$nama%'  ");
									*/
			if($jnsperb==''){
			$hasilAkhir = $db->fetchOne("select count(*) from e_ast_kategori_problemti_tm 
									    ");
			}
			else{						   
			$hasilAkhir = $db->fetchOne("select count(*) from e_ast_kategori_problemti_tm 
									    where c_jenis_problem = '$jnsperb'	
									   ");
              }									   
			 //echo"<br>hasilAkhir> ".$hasilAkhir;						   
			
					
		 }else
		 {
		 $xLimit=$itemPerPage;
	  		 $xOffset=($pageNumber-1)*$itemPerPage;		
	    /*		
          $result = $db->fetchAll("select distinct a.i_peg_nip,a.n_pend_latih,b.n_peg,b.n_jabatan,
									b.c_peg_golongan,b.i_orgb  
									from e_sdm_peg_latih_tm a, e_sdm_pegawai_0_tm b
									where a.i_peg_nip=b.i_peg_nip
									and upper(a.n_pend_latih) like'%PENGADAAN%'
									and upper(b.n_peg) like'%$nama%'
									limit $xLimit offset $xOffset
					");		
		*/	
		if($jnsperb=='')
		{
		$result = $db->fetchAll("select c_problem_ctgr,n_problem_ctgr,q_nomor_max,
									c_status,c_jenis_problem  
									from e_ast_kategori_problemti_tm
									limit $xLimit offset $xOffset
					");	
		}
		else{
          $result = $db->fetchAll("select c_problem_ctgr,n_problem_ctgr,q_nomor_max,
									c_status,c_jenis_problem  
									from e_ast_kategori_problemti_tm
									where c_jenis_problem = '$jnsperb'
									limit $xLimit offset $xOffset
					");		
        }					
         $jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++) {
		   
           $hasilAkhir[$j] = array("kodeProblem"  =>(string)$result[$j]->c_problem_ctgr,
								   "namaProblem"  =>(string)$result[$j]->n_problem_ctgr,
                                   "nomormax"  =>(string)$result[$j]->q_nomor_max,								   
	                               "status"  =>(string)$result[$j]->c_status,
								   "jenisProblem"         =>(string)$result[$j]->c_jenis_problem
								   
								   );
		 }	
        } 		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }	
			
	}
	
	//Akhir Endri :30-10-2008
	// Service Rekap  Problem TI Berdasarkan Unit Kerja di detailkan berdasarkan Kategori 
	public function getLaporanRekapProblemTIByUnitByKat($pageNumber,$itemPerPage,$prdAwal,$prdAkhir,$unit) 
	{   
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {	     
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0)){
				$result = $db->fetchAll("Select 
											c.c_problem_ctgr,
											n_problem_ctgr,
											c_jenis_problem
											from E_AST_AJUANBAIKTI_0_TM C, e_ast_ajuanbaikti_item_tm D 
											, e_ast_kategori_problemti_tm  E
											WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
											and c.c_problem_ctgr=e.c_problem_ctgr 
											and  (d_problem_awal   between '$prdAwal' and '$prdAkhir')
											and i_orgb = '$unit'
											UNION
											SELECT  distinct
											a.c_problem_ctgr,b.n_problem_ctgr,c_jenis_problem
											FROM e_ast_problemti_0_tm a, e_ast_kategori_problemti_tm  b
											where a.c_problem_ctgr=b.c_problem_ctgr 
											and	(d_problem_awal   between '$prdAwal' and '$prdAkhir')
											and i_orgb = '$unit'											
											order by c_problem_ctgr"); 
				$hasilAkhir = count($result);
			}else{
				$result = $db->fetchAll("Select 
											c.c_problem_ctgr,
											n_problem_ctgr,
											c_jenis_problem
											from E_AST_AJUANBAIKTI_0_TM C, e_ast_ajuanbaikti_item_tm D 
											, e_ast_kategori_problemti_tm  E
											WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
											and c.c_problem_ctgr=e.c_problem_ctgr 
											and  (d_problem_awal   between '$prdAwal' and '$prdAkhir')
											and i_orgb = '$unit'
											UNION
											SELECT  distinct
											a.c_problem_ctgr,b.n_problem_ctgr,c_jenis_problem
											FROM e_ast_problemti_0_tm a, e_ast_kategori_problemti_tm  b
											where a.c_problem_ctgr=b.c_problem_ctgr 
											and	(d_problem_awal   between '$prdAwal' and '$prdAkhir')
											and i_orgb = '$unit'
											order by c_problem_ctgr "); 
				$jmlResult = count($result);
				
				for ($j = 0; $j < $jmlResult; $j++) {			
					    $c_problem_ctgr = $result[$j]->c_problem_ctgr;
						$c_jenis_problem = $result[$j]->c_jenis_problem;						
						if($c_jenis_problem =='U'){
						    $n_jenis_problem = 'User';
							$total 			= $db->fetchCol("SELECT count(*) as total 
															from E_AST_AJUANBAIKTI_0_TM C, e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
															and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
															and i_orgb = '$unit'
															and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
							
							$belumSelesai 	= $db->fetchCol("SELECT count(*)  as belumSelesai from e_ast_ajuanbaikti_0_tm C, 
													     	 e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
															AND (D.c_status_perbaikan  != 'Y' or D.c_status_perbaikan  isnull)
														    and	(d_problem_awal  between '$prdAwal' and '$prdAkhir')
											                and i_orgb = '$unit'
															and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
							
							$Selesai	 	= $db->fetchCol("SELECT count(*) as Selesai from e_ast_ajuanbaikti_0_tm C,
													     	e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik 
													     	AND D.c_status_perbaikan  = 'Y'
															and	(d_problem_awal  between '$prdAwal' and '$prdAkhir')
											                and i_orgb = '$unit'
															and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);

							
														  
						 }
						 else{
						    $n_jenis_problem = 'Sistem';
							$total 			= $db->fetchCol("SELECT count(*) as total from e_ast_problemti_0_tm 
														WHERE i_orgb = '$unit'
														and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
														and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
							
							$belumSelesai 	= $db->fetchCol("SELECT count(*)  as belumSelesai from e_ast_problemti_0_tm 
														WHERE (c_status_perbaikan  != 'Y' or c_status_perbaikan  is null)
													    and  i_orgb = '$unit'
														and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
														and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
							
							$Selesai 		= $db->fetchCol("SELECT count(*) as Selesai from e_ast_problemti_0_tm 
														WHERE c_status_perbaikan ='Y'
														and  i_orgb = '$unit'
														and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
														and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
						}
						
					
					
					$hasilAkhir[$j] = array("c_problem_ctgr"    =>(string)$result[$j]->c_problem_ctgr,
													 "n_jenis_problem"   =>(string)$n_jenis_problem,
													 "n_problem_ctgr"   =>(string)$result[$j]->n_problem_ctgr,
													 "total"         	=>$total[0],
													 "belumSelesai"    	=>$belumSelesai[0],
													 "Selesai"          =>$Selesai[0]);
				}	
			}	 
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	
	// Ina : 20-11-2008 : Awal
	public function getLaporanRekapProblemTIByUnitByKat_Cetak($prdAwal,$prdAkhir,$unit) 
	{   
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {	     		
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 			
				$result = $db->fetchAll("Select 
											c.c_problem_ctgr,
											n_problem_ctgr,
											c_jenis_problem
											from E_AST_AJUANBAIKTI_0_TM C, e_ast_ajuanbaikti_item_tm D 
											, e_ast_kategori_problemti_tm  E
											WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
											and c.c_problem_ctgr=e.c_problem_ctgr 
											and  (d_problem_awal   between '$prdAwal' and '$prdAkhir')
											and i_orgb = '$unit'
											UNION
											SELECT  distinct
											a.c_problem_ctgr,b.n_problem_ctgr,c_jenis_problem
											FROM e_ast_problemti_0_tm a, e_ast_kategori_problemti_tm  b
											where a.c_problem_ctgr=b.c_problem_ctgr 
											and	(d_problem_awal   between '$prdAwal' and '$prdAkhir')
											and i_orgb = '$unit'
											order by c_problem_ctgr "); 
				$jmlResult = count($result);
				
				for ($j = 0; $j < $jmlResult; $j++) {			
					    $c_problem_ctgr = $result[$j]->c_problem_ctgr;
						$c_jenis_problem = $result[$j]->c_jenis_problem;
						
						if($c_jenis_problem =='U'){
						    $n_jenis_problem = 'User';
							$total 			= $db->fetchCol("SELECT count(*) as total 
															from E_AST_AJUANBAIKTI_0_TM C, e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
															and	(d_problem_awal   between '$prdAwal' and '$prdAkhir')
															and i_orgb = '$unit'
															and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
							
							$belumSelesai 	= $db->fetchCol("SELECT count(*)  as belumSelesai from e_ast_ajuanbaikti_0_tm C, 
													     	 e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
															AND (D.c_status_perbaikan  != 'Y' or D.c_status_perbaikan  isnull)
														    and	(d_problem_awal  between '$prdAwal' and '$prdAkhir')
											                and i_orgb = '$unit'
															and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
							
							$Selesai	 	= $db->fetchCol("SELECT count(*) as Selesai from e_ast_ajuanbaikti_0_tm C,
													     	e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik 
													     	AND D.c_status_perbaikan  = 'Y'
															and	(d_problem_awal   between '$prdAwal' and '$prdAkhir')
											                and i_orgb = '$unit'
															and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);

							
														  
						 }
						 else{
						    $n_jenis_problem = 'Sistem';
							$total 			= $db->fetchCol("SELECT count(*) as total from e_ast_problemti_0_tm 
														WHERE i_orgb = '$unit'
														and	(d_problem_awal   between '$prdAwal' and '$prdAkhir')
														and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
							
							$belumSelesai 	= $db->fetchCol("SELECT count(*)  as belumSelesai from e_ast_problemti_0_tm 
														WHERE (c_status_perbaikan  != 'Y' or c_status_perbaikan  is null)
													    and  i_orgb = '$unit'
														and	(d_problem_awal   between '$prdAwal' and '$prdAkhir')
														and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
							
							$Selesai 		= $db->fetchCol("SELECT count(*) as Selesai from e_ast_problemti_0_tm 
														WHERE c_status_perbaikan ='Y'
														and  i_orgb = '$unit'
														and	(d_problem_awal   between '$prdAwal' and '$prdAkhir')
														and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
						}
						
					
					
					$hasilAkhir[$j] = array("c_problem_ctgr"    =>(string)$result[$j]->c_problem_ctgr,
													 "n_jenis_problem"   =>(string)$n_jenis_problem,
													 "n_problem_ctgr"   =>(string)$result[$j]->n_problem_ctgr,
													 "total"         	=>$total[0],
													 "belumSelesai"    	=>$belumSelesai[0],
													 "Selesai"          =>$Selesai[0]);
				}	
				 
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	// Ina : 20-11-2008 : AKhir
	// Ina : 26-11-2008 : Awal
	public function getCompRekapProblemTIByKat_Sistem($pageNumber,$itemPerPage,$prdAwal,$prdAkhir) 
	{   
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {	     
			//$where[] = $thn;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0)){
				$result = $db->fetchAll("SELECT  distinct
											a.c_problem_ctgr,b.n_problem_ctgr,c_jenis_problem
											FROM e_ast_problemti_0_tm a, e_ast_kategori_problemti_tm  b
											where a.c_problem_ctgr=b.c_problem_ctgr 
											and	(d_problem_awal   between '$prdAwal' and '$prdAkhir')
											order by c_problem_ctgr"); 
				$hasilAkhir = count($result);
			}else{
				$result = $db->fetchAll("SELECT  distinct
											a.c_problem_ctgr,b.n_problem_ctgr,c_jenis_problem
											FROM e_ast_problemti_0_tm a, e_ast_kategori_problemti_tm  b
											where a.c_problem_ctgr=b.c_problem_ctgr 
											and	(d_problem_awal   between '$prdAwal' and '$prdAkhir')
											order by c_problem_ctgr "); 
				$jmlResult = count($result);
				
				for ($j = 0; $j < $jmlResult; $j++) {			
					    $c_problem_ctgr = $result[$j]->c_problem_ctgr;
						$c_jenis_problem = $result[$j]->c_jenis_problem;
						
						
						
						    $n_jenis_problem = 'Sistem';
							$total 			= $db->fetchCol("SELECT count(*) as total from e_ast_problemti_0_tm 
														WHERE (d_problem_awal between '$prdAwal' and '$prdAkhir')
														and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
							
							$belumSelesai 	= $db->fetchCol("SELECT count(*)  as belumSelesai from e_ast_problemti_0_tm 
														WHERE (c_status_perbaikan  != 'Y' or c_status_perbaikan  is null)
													    and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
														and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
							
							$Selesai 		= $db->fetchCol("SELECT count(*) as Selesai from e_ast_problemti_0_tm 
														  WHERE c_status_perbaikan ='Y'
														  and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
														  and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
						
						
					
					
					$hasilAkhir[$j] = array("c_problem_ctgr"    =>(string)$result[$j]->c_problem_ctgr,
													 "n_jenis_problem"   =>(string)$n_jenis_problem,
													 "n_problem_ctgr"   =>(string)$result[$j]->n_problem_ctgr,
													 "total"         	=>$total[0],
													 "belumSelesai"    	=>$belumSelesai[0],
													 "Selesai"          =>$Selesai[0]);
				}	
			}	 
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	
	public function getCompRekapProblemTIByKat_User($pageNumber,$itemPerPage,$prdAwal,$prdAkhir) 
	{   
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {	     
			//$where[] = $thn;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0)){
				$result = $db->fetchAll("Select 
											c.c_problem_ctgr,
											n_problem_ctgr,
											c_jenis_problem
											from E_AST_AJUANBAIKTI_0_TM C, e_ast_ajuanbaikti_item_tm D 
											, e_ast_kategori_problemti_tm  E
											WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
											and c.c_problem_ctgr=e.c_problem_ctgr 
											and  (d_problem_awal   between '$prdAwal' and '$prdAkhir')											
											order by c_problem_ctgr"); 
				$hasilAkhir = count($result);
			}else{
				$result = $db->fetchAll("Select 
											c.c_problem_ctgr,
											n_problem_ctgr,
											c_jenis_problem
											from E_AST_AJUANBAIKTI_0_TM C, e_ast_ajuanbaikti_item_tm D 
											, e_ast_kategori_problemti_tm  E
											WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
											and c.c_problem_ctgr=e.c_problem_ctgr 
											and  (d_problem_awal   between '$prdAwal' and '$prdAkhir')											
											order by c_problem_ctgr "); 
				$jmlResult = count($result);
				
				for ($j = 0; $j < $jmlResult; $j++) {			
					    $c_problem_ctgr = $result[$j]->c_problem_ctgr;
						$c_jenis_problem = $result[$j]->c_jenis_problem;
						
						
						    $n_jenis_problem = 'User';
							$total 			= $db->fetchCol("SELECT count(*) as total 
															from E_AST_AJUANBAIKTI_0_TM C, e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
															and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
															and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
							
							$belumSelesai 	= $db->fetchCol("SELECT count(*)  as belumSelesai from e_ast_ajuanbaikti_0_tm C, 
													     	 e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
															AND (D.c_status_perbaikan  != 'Y' or D.c_status_perbaikan  isnull)
														    and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
															and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
							
							$Selesai	 	= $db->fetchCol("SELECT count(*) as Selesai from e_ast_ajuanbaikti_0_tm C,
													     	e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik 
													     	AND D.c_status_perbaikan  = 'Y'
															and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
															and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
	
					
					$hasilAkhir[$j] = array("c_problem_ctgr"    =>(string)$result[$j]->c_problem_ctgr,
													 "n_jenis_problem"   =>(string)$n_jenis_problem,
													 "n_problem_ctgr"   =>(string)$result[$j]->n_problem_ctgr,
													 "total"         	=>$total[0],
													 "belumSelesai"    	=>$belumSelesai[0],
													 "Selesai"          =>$Selesai[0]);
				}	
			}	 
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	
	public function getLaporanRekapProblemTIByKat_Sistem($pageNumber,$itemPerPage,$prdAwal,$prdAkhir) {
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$result = $db->fetchOne("SELECT  distinct
											a.c_problem_ctgr,b.n_problem_ctgr
											FROM e_ast_problemti_0_tm a, e_ast_kategori_problemti_tm  b
											where a.c_problem_ctgr=b.c_problem_ctgr 
											and	d_problem_awal   between ? and ?",$where);
											
				$hasilAkhir = count($result);
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				$result = $db->fetchAll("SELECT  distinct
											a.c_problem_ctgr,b.n_problem_ctgr,c_jenis_problem
											FROM e_ast_problemti_0_tm a, e_ast_kategori_problemti_tm  b
											where a.c_problem_ctgr=b.c_problem_ctgr 
											and	d_problem_awal   between ? and ?
											order by c_problem_ctgr
											limit $xLimit offset $xOffset",$where);
				$jmlResult = count($result);
		
				if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {
						
						$c_problem_ctgr = $result[$j]->c_problem_ctgr;
						$c_jenis_problem = $result[$j]->c_jenis_problem;
						
						    $n_jenis_problem = 'Sistem';
							$total 			= $db->fetchCol("SELECT count(*) as total from e_ast_problemti_0_tm 
														WHERE (d_problem_awal between '$prdAwal' and '$prdAkhir')
														and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
							
							$belumSelesai 	= $db->fetchCol("SELECT count(*)  as belumSelesai from e_ast_problemti_0_tm 
														WHERE (c_status_perbaikan  != 'Y' or c_status_perbaikan  is null)
													    and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
														and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
							
							$Selesai 		= $db->fetchCol("SELECT count(*) as Selesai from e_ast_problemti_0_tm 
														WHERE c_status_perbaikan ='Y'
														and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
														and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
												
						
							$hasilAkhir[$j] = array("c_problem_ctgr"    =>(string)$result[$j]->c_problem_ctgr,
													 "n_problem_ctgr"   =>(string)$result[$j]->n_problem_ctgr,
													 "n_jenis_problem"   =>$n_jenis_problem,
													 "total"         	=>$total[0],
													 "belumSelesai"    	=>$belumSelesai[0],
													 "Selesai"          =>$Selesai[0]);
										 
					}
				}
			}		 
			return $hasilAkhir;
		} catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
		}
	}
	
	public function getLaporanRekapProblemTIByKat_User($pageNumber,$itemPerPage,$prdAwal,$prdAkhir) {
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where[] = $prdAwal;
			$where[] = $prdAkhir;
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$result = $db->fetchOne("Select 
											c.c_problem_ctgr,
											n_problem_ctgr
											from E_AST_AJUANBAIKTI_0_TM C, e_ast_ajuanbaikti_item_tm D 
											, e_ast_kategori_problemti_tm  E
											WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
											and c.c_problem_ctgr=e.c_problem_ctgr 
											and  d_problem_awal   between ? and ?",$where);
											
				$hasilAkhir = count($result);
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				$result = $db->fetchAll("Select 
											c.c_problem_ctgr,
											n_problem_ctgr,
											c_jenis_problem
											from E_AST_AJUANBAIKTI_0_TM C, e_ast_ajuanbaikti_item_tm D 
											, e_ast_kategori_problemti_tm  E
											WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
											and c.c_problem_ctgr=e.c_problem_ctgr 
											and  d_problem_awal   between ? and ?											
											order by c_problem_ctgr
											limit $xLimit offset $xOffset",$where);
				$jmlResult = count($result);
		
				if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {
						
						$c_problem_ctgr = $result[$j]->c_problem_ctgr;
						$c_jenis_problem = $result[$j]->c_jenis_problem;
						
						
						    $n_jenis_problem = 'User';
							$total 			= $db->fetchCol("SELECT count(*) as total 
															from E_AST_AJUANBAIKTI_0_TM C, e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
															and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
															and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
							
							$belumSelesai 	= $db->fetchCol("SELECT count(*)  as belumSelesai from e_ast_ajuanbaikti_0_tm C, 
													     	 e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik
															AND (D.c_status_perbaikan  != 'Y' or D.c_status_perbaikan  isnull)
														    and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
															and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);
							
							$Selesai	 	= $db->fetchCol("SELECT count(*) as Selesai from e_ast_ajuanbaikti_0_tm C,
													     	e_ast_ajuanbaikti_item_tm D 
															WHERE D.i_barang_ajuanbaik = C. i_barang_ajuanbaik 
													     	AND D.c_status_perbaikan  = 'Y'
															and	(d_problem_awal between '$prdAwal' and '$prdAkhir')
															and c_problem_ctgr = ? ",$result[$j]->c_problem_ctgr);

						 						
							$hasilAkhir[$j] = array("c_problem_ctgr"    =>(string)$result[$j]->c_problem_ctgr,
													 "n_problem_ctgr"   =>(string)$result[$j]->n_problem_ctgr,
													 "n_jenis_problem"   =>$n_jenis_problem,
													 "total"         	=>$total[0],
													 "belumSelesai"    	=>$belumSelesai[0],
													 "Selesai"          =>$Selesai[0]);
										 
					}
				}
			}		 
			return $hasilAkhir;
		} catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
		}
	}
	//Ina :26-11-2008 : Akhir
}
?>