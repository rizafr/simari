<?php

class ast_problem_ti_Service {
    private static $instance;
   
    private function __construct() {
       //echo 'I am constructed';
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
										and d_barang_ajuanbaik between ? and ?
										",$where);
				$hasilAkhir = count($hasil);
			}else {
			    
				$xLimit=$itemPerPage;
			    $xOffset=($pageNumber-1)*$itemPerPage;
					$result = $db->fetchAll("select A.d_barang_ajuanbaik, A.e_barang_perbaikan, A.i_peg_nip, 
									    A.i_orgb, B.d_akhir_perbaikan,i_peg_helpdesk
									    from e_ast_ajuanbaikti_0_tm A, e_ast_ajuanbaikti_item_tm B									    
										where B.i_barang_ajuanbaik =  A.i_barang_ajuanbaik
										and d_barang_ajuanbaik between ? and ?
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
										and d_barang_ajuanbaik between ? and ?
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
										and d_barang_ajuanbaik between ? and ?
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
											and d_barang_ajuanbaik between ? and ?",$where);
				
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
											and d_barang_ajuanbaik between ? and ?
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
			 
				$result = $db->fetchAll("select B.i_peg_nip, n_peg, A.n_jabatan, b.c_unit_kerja, b.i_orgb , c.n_orgb 
										from e_sdm_jabatan_0_tm A,  e_sdm_pegawai_0_tm B, e_org_0_0_tm C
										where a.i_peg_nip = b.i_peg_nip
										and b.c_unit_kerja = a.c_jabatan
										and b.c_unit_kerja = c.i_orgb
										and b.c_unit_kerja = ? 
										",$where);
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
			 
					$hasilAkhir[$j] = array("i_peg_nip"	=>(string)$result[$j]->i_peg_nip,
											"n_peg"   =>(string)$result[$j]->n_peg, 
											"n_jabatan"      =>(string)$result[$j]->n_jabatan, 
											"n_peg"          		=>(string)$result[$j]->n_peg,
											"c_unit_kerja"         		=>(string)$result[$j]->c_unit_kerja,
											"n_orgb"         		=>(string)$result[$j]->n_orgb,
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
	public function getKategoriProblemSi($pageNumber,$itemPerPage,$jenis) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $jenis;
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_kategori_problemti_tm
												where c_status = 'Y' and c_jenis_problem = ? ",$where);
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				$result = $db->fetchAll("SELECT  *  FROM e_ast_kategori_problemti_tm
										 where c_status = 'Y' and c_jenis_problem = ?
										order by c_problem_ctgr limit $xLimit offset $xOffset",$where);
				$jmlResult = count($result);
		 
				if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {
		 
						$hasilAkhir[$j] = array("c_problem_ctgr"    =>(string)$result[$j]->c_problem_ctgr,
											    "n_problem_ctgr"    =>(string)$result[$j]->n_problem_ctgr,
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
	
	public function getKategoriProblemSi_Old($pageNumber,$itemPerPage) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_kategori_problemti_tm
												where c_status = 'Y' ");
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				$result = $db->fetchAll("SELECT  *  FROM e_ast_kategori_problemti_tm
										 where c_status = 'Y'
										order by c_problem_ctgr limit $xLimit offset $xOffset");
				$jmlResult = count($result);
		 
				if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {
		 
						$hasilAkhir[$j] = array("c_problem_ctgr"    =>(string)$result[$j]->c_problem_ctgr,
											    "n_problem_ctgr"    =>(string)$result[$j]->n_problem_ctgr,
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
	
	//Insert Kategori Problem
	public function insertKategoriProblemSi(array $data)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		//$where = $data['noPengajuan'];
		try {
			$db->beginTransaction();
			
				$prmInsert = array("c_problem_ctgr"		=>$data['c_problem_ctgr'],
									"n_problem_ctgr"				=>$data['n_problem_ctgr'],
									"q_nomor_max"				=>$data['q_nomor_max'],
									"i_entry"				=>$data['i_entry'],
									"d_entry"				=>date("Y-m-d"));
				
				
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
			
				$prmUpdate = array("n_problem_ctgr"	=>$data['n_problem_ctgr']);
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
	
	public function getDataProblemSiByNoPengajuan($pageNumber,$itemPerPage) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_problemti_0_tm 
											where (c_status_perbaikan is null or c_status_perbaikan ='A')
											and c_status_lengkap is null"
										   );
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				$result = $db->fetchAll("SELECT  i_barang_ajuanbaik,d_barang_ajuanbaik,a.c_problem_ctgr, 
										c_problem, d_problem_awal, d_awal_perbaikan,  d_akhir_perbaikan,
										e_problem_kasus,e_problem_penyebab,e_problem_solusi,e_sumberdata,
										e_keterangan,i_peg_nippemohon,n_peg_bumn,i_telpon,c_pemohon, i_orgb
										FROM e_ast_problemti_0_tm a
										where (c_status_perbaikan is null or c_status_perbaikan ='A') 
										and c_status_lengkap is null
										order by i_barang_ajuanbaik 
										limit $xLimit offset $xOffset");
										
				$jmlResult = count($result);
		 
				if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {
					/**
						$n_peg 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nippemohon);	
														
						$n_bumn		= $db->fetchCol('select n_bumn  
														from    a_bumn_0_0_tr  
														where i_bumn = ? ',$result[$j]->i_peg_nippemohon);	
														
														
						
						if($n_peg){
							$n_peg =$n_peg;
						}else if($n_bumn){
							$n_peg = $n_bumn;
						}
					**/	
						// Awal : Pencari Nama Pemohon dan org Pemohon
					$n_peg 	= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nippemohon);	
														
					if ($n_peg==null || $n_peg == '')
					{
					     $n_peg 	=  $db->fetchCol('select n_peg_bumn  
														from  e_ast_problemti_0_tm 
														where i_barang_ajuanbaik = ? ',$result[$j]->i_barang_ajuanbaik);	
														
					     $n_orgb 	= $db->fetchCol('select n_bumn  
														from  a_bumn_0_0_tr 
														where i_bumn = ? ',$result[$j]->i_peg_nippemohon);	
					}	else
					{
					     $n_orgb 			= $db->fetchCol('select n_orgb  
														from  e_org_0_0_tm 
														where i_orgb = ? ',$result[$j]->i_orgb);
					}								
					// Akhir  : Pencari Nama Pemohon dan org Pemohon 
						$hasilAkhir[$j] = array("i_barang_ajuanbaik"	=>(string)$result[$j]->i_barang_ajuanbaik,
												"d_barang_ajuanbaik"    =>(string)$result[$j]->d_barang_ajuanbaik,
												"c_problem_ctgr"		=>(string)$result[$j]->c_problem_ctgr,
												"c_problem"    			=>(string)$result[$j]->c_problem,
												"d_problem_awal"    	=>(string)$result[$j]->d_problem_awal,
												"d_awal_perbaikan"    	=>(string)$result[$j]->d_awal_perbaikan,
												"d_akhir_perbaikan"    	=>(string)$result[$j]->d_akhir_perbaikan,
												"e_problem_kasus"    	=>(string)$result[$j]->e_problem_kasus,
												"e_problem_penyebab"    =>(string)$result[$j]->e_problem_penyebab,
												"e_problem_solusi"    	=>(string)$result[$j]->e_problem_solusi,
												"e_sumberdata"    		=>(string)$result[$j]->e_sumberdata,
												"e_keterangan"    		=>(string)$result[$j]->e_keterangan,
												"q_nomor_max"       	=>(string)$result[$j]->q_nomor_max,
												"i_peg_nippemohon"		=>(string)$result[$j]->i_peg_nippemohon,
												"n_peg_bumn"			=>(string)$result[$j]->n_peg_bumn,
												"i_telpon"				=>(string)$result[$j]->i_telpon,
												"c_pemohon"				=>(string)$result[$j]->c_pemohon,
												"n_orgb"				=>$n_orgb[0],
												"n_peg"					=>$n_peg[0]);
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
	
	public function getDataProblemSi($pageNumber,$itemPerPage,$nmKategori) {
		
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
				$result = $db->fetchAll("SELECT  a.c_problem_ctgr, c_problem, d_problem_awal, d_awal_perbaikan,  d_problem_akhir,
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
												"d_problem_awal"		=>(string)$result[$j]->d_problem_awal,
												"d_awal_perbaikan"    	=>(string)$result[$j]->d_awal_perbaikan,
												"d_akhir_perbaikan"    	=>(string)$result[$j]->d_akhir_perbaikan,
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
			
			$d_problem_akhir = $data['d_problem_akhir'];
			$sub = substr($d_problem_akhir,0,4);
			if ($sub =='1970'){
				$prmInsert = array("i_barang_ajuanbaik"		=>$data['i_barang_ajuanbaik'],
									"d_barang_ajuanbaik"	=>date("Y-m-d"),
									"d_problem_awal"		=>$data['d_problem_awal'],
									"e_problem_kasus"		=>$data['e_problem_kasus'],
									"e_problem_penyebab"	=>$data['e_problem_penyebab'],
									"e_problem_solusi"		=>$data['e_problem_solusi'],
									"e_keterangan"			=>$data['e_keterangan'],
									"e_sumberdata"			=>$data['e_sumberdata'],
									"c_status_perbaikan"	=>$data['c_status_perbaikan'],
									"c_status_lengkap"		=>$data['c_status_lengkap'],
									"i_peg_nip"				=>'-',
									"i_peg_nippemohon"		=>$data['i_peg_nippemohon'],
									"n_peg_bumn"			=>$data['n_peg_bumn'],
									"i_telpon"				=>$data['i_telpon'],
									"c_pemohon"				=>$data['c_pemohon'],
									"i_orgb"				=>$data['i_orgb'],
									"i_entry"				=>$data['i_entry'],
									"d_entry"				=>date("Y-m-d"));
			}else{
				$prmInsert = array("i_barang_ajuanbaik"		=>$data['i_barang_ajuanbaik'],
									"d_barang_ajuanbaik"	=>date("Y-m-d"),
									"d_problem_awal"		=>$data['d_problem_awal'],
									"d_problem_akhir"		=>$data['d_problem_akhir'],
									"e_problem_kasus"		=>$data['e_problem_kasus'],
									"e_problem_penyebab"	=>$data['e_problem_penyebab'],
									"e_problem_solusi"		=>$data['e_problem_solusi'],
									"e_keterangan"			=>$data['e_keterangan'],
									"e_sumberdata"			=>$data['e_sumberdata'],
									"c_status_perbaikan"	=>$data['c_status_perbaikan'],
									"i_peg_nip"				=>'-',
									"i_peg_nippemohon"		=>$data['i_peg_nippemohon'],
									"n_peg_bumn"			=>$data['n_peg_bumn'],
									"i_telpon"				=>$data['i_telpon'],
									"c_pemohon"				=>$data['c_pemohon'],
									"i_orgb"				=>$data['i_orgb'],
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
		
		$where[] = "i_barang_ajuanbaik = '".$data['i_barang_ajuanbaik']."'";
		//$where[] = "c_problem_ctgr = '".$data['kdctgr']."'";
		//$where[] = "c_problem = '".$data['kdproblem']."'";
		
		try {
			$db->beginTransaction();
			$tglAkhir = $data['tglAkhir'];
			$sub = substr($tglAkhir,0,4);
			if ($sub =='-#-#'){
			
				$prmInsert = array("d_problem_awal"			=>$data['tglAwal'],
									"e_problem_kasus"		=>$data['kasus'],
									"e_problem_penyebab"	=>$data['penyebab'],
									"e_problem_solusi"		=>$data['solusi'],
									"e_keterangan"			=>$data['keterangan'],
									"e_sumberdata"			=>$data['sumberdata'],
									"c_status_perbaikan"	=>$data['status'],
									"c_status_lengkap"		=>$data['c_status_lengkap'],
									"i_peg_nip"				=>$data['nip'],
									"i_peg_nippemohon"		=>$data['i_peg_nippemohon'],
									"i_orgb"				=>$data['i_orgb'],
									"n_peg_bumn"			=>$data['n_peg_bumn'],
									"i_telpon"				=>$data['i_telpon'],
									"c_pemohon"				=>$data['c_pemohon'],
									);
			}else{
				$prmInsert = array("d_problem_awal"			=>$data['tglAwal'],
									"e_problem_kasus"		=>$data['kasus'],
									"e_problem_penyebab"	=>$data['penyebab'],
									"e_problem_solusi"		=>$data['solusi'],
									"e_keterangan"			=>$data['keterangan'],
									"e_sumberdata"			=>$data['sumberdata'],
									"c_status_perbaikan"	=>$data['status'],
									"c_status_lengkap"		=>$data['c_status_lengkap'],
									"i_peg_nippemohon"		=>$data['i_peg_nippemohon'],
									"i_orgb"				=>$data['i_orgb'],
									"n_peg_bumn"			=>$data['n_peg_bumn'],
									"i_telpon"				=>$data['i_telpon'],
									"c_pemohon"				=>$data['c_pemohon'],
									);
			}	
			$db->update('e_ast_problemti_0_tm',$prmInsert,$where);
			$db->commit();
			return 'sukses';
			//"d_problem_akhir"		=>$data['tglAkhir'],
									
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
	public function deleteDataProblemSi($noPengajuan)
	{
		try
		{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$db->beginTransaction();
			$where[] = "i_barang_ajuanbaik = '".$noPengajuan."'";
			
			$db->delete('e_ast_problemti_0_tm', $where);
			$db->commit();
			return 'sukses';
		}catch (Exception $e){
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal';
		}
	}
	
	public function deleteDataProblemSi_Old($kdctgr,$kdproblem)
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

	public function getDataByNoPengajuan($noajuan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
				$where[]= $noajuan;
				$result = $db->fetchAll("SELECT  *  FROM e_ast_problemti_0_tm 
										where i_barang_ajuanbaik = ?
										",$where);
				$jmlResult = count($result);
		 
				if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {
						$n_bumn		= $db->fetchCol('select n_bumn  
														from    a_bumn_0_0_tr  
														where i_bumn = ? ',$result[$j]->i_peg_nippemohon);	
						
						$hasilAkhir[$j] = array("c_problem_ctgr"		=>(string)$result[$j]->c_problem_ctgr,
												"c_problem"    			=>(string)$result[$j]->c_problem,
												"d_problem_awal"    	=>(string)$result[$j]->d_problem_awal,
												"d_problem_akhir"    	=>(string)$result[$j]->d_problem_akhir,
												"e_problem_kasus"    	=>(string)$result[$j]->e_problem_kasus,
												"e_problem_penyebab"    =>(string)$result[$j]->e_problem_penyebab,
												"e_problem_solusi"    	=>(string)$result[$j]->e_problem_solusi,
												"e_sumberdata"    		=>(string)$result[$j]->e_sumberdata,
												"e_keterangan"    		=>(string)$result[$j]->e_keterangan,
												"i_peg_nippemohon"      =>(string)$result[$j]->i_peg_nippemohon,
												"n_peg_bumn"			=>(string)$result[$j]->n_peg_bumn,
												"i_telpon"				=>(string)$result[$j]->i_telpon,
												"c_pemohon"				=>(string)$result[$j]->c_pemohon,
												"n_bumn"				=>$n_bumn[0]);
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
	
	//======================================================
	
	public function getSoftwareList($pageNumber,$itemPerPage) {
	    //echo 'service tool';
		$tglSkrg = date("Y-m-d");
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
	    
			$where = $tglSkrg;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_software_0_tr a, e_ast_vendor_0_tr b
			                            where a.i_rekanan = b.i_rekanan");
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;	
				$result = $db->fetchAll("SELECT a.i_sw,a.n_sw,a.i_sw_kelompok,c.n_sw_kelompok,a.i_sw_licensi,
									a.i_sw_versi,a.e_sw_platform,e_sw_platform1,e_sw_platform2,e_sw_platform3,
									b.n_rekanan	,i_rekanan_ref, a_prsh_jalan,d_sw_expiregaransi
									FROM e_ast_software_0_tr a, e_ast_vendor_0_tr b,
									e_ast_kelompok_software_tr c
									where a.i_rekanan = b.i_rekanan
									and a.i_sw_kelompok    = c.i_sw_kelompok
									and d_sw_expiregaransi < ?
									order by a.i_sw 
									limit $xLimit offset $xOffset", $where); 
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
															"n_rekanan"        		=>(string)$result[$j]->n_rekanan);
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
															"n_rekanan"        		=>$n_rekanan[0]);
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
	   $tglSkrg = date("Y-m-d");
	   $ThnEx = $tglSkrg - 1 ;
	   $blntgl = date("-m-d");
	   
	   $TglExGaransi = $ThnEx.date("-m-d");
	   
	   try 
	   {  	         	 
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
										 and substr(a.c_barang,8,3) = c.kd_sskel"); 
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
												i_rekanan_ref, a_prsh_jalan, c_hw,
												(cast(d_anggaran as int) + cast(d_hw_garansi as int)) as jmlgaransi, substr(d_perolehan,5,10) as exgaransi
									 FROM e_ast_hardware_0_tm A, e_ast_vendor_0_tr B, e_ast_sskel_0_tr C 
									 where A.i_rekanan = B.i_rekanan 
									 and substr(a.c_barang,1,1) = c.kd_gol
									 and substr(a.c_barang,2,2) = c.kd_bid 
									 and substr(a.c_barang,4,2) = c.kd_kel
									 and substr(a.c_barang,6,2) = c.kd_skel
									 and substr(a.c_barang,8,3) = c.kd_sskel
									 limit $xLimit offset $xOffset"); 
									 
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
												"c_hw"				=>(string)$result[$j]->c_hw);
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
												"d_hw_garansi"		=>$d_hw_garansi[0]);
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
			$hasil = $db->fetchAll("select i_peg_helpdesk
									    from e_ast_ajuanbaikti_0_tm A, e_ast_ajuanbaikti_item_tm B									    
										where B.i_barang_ajuanbaik =  A.i_barang_ajuanbaik
										and i_peg_helpdesk is not null");
			$hasilAkhir = count($hasil);
		 }
		 else
		 {
			
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;		
			
			$result = $db->fetchAll("select i_peg_helpdesk
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
	
	
	//============================Perbaikan TI ========================== 18 Jun 08 ===============================
	
	// ----------- get No inventaris untuk perbaikan TI ===========================
	public function getAsetTIList($pageNumber,$itemPerPage,$kdunit) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   $where[] = $kdunit;
	   $where[] = $kdunit;
	   
	   try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasil = $db->fetchAll("Select  A.d_anggaran, A.c_barang, 
										to_char(A.i_aset,'09999') as i_aset, A.d_perolehan,  B.ur_sskel, 
										A.i_komputer_macaddress
										FROM  E_AST_KOMPUTER_0_TR A , E_AST_SSKEL_0_TR B
										Where A.C_Unit_Kerja = ?
										And B.kd_gol = substr(A.c_barang,1,1)
										And B.kd_bid = substr(A.c_barang,2,2)
										And B.kd_kel = substr(A.c_barang,4,2)
										And B.kd_skel = substr(A.c_barang,6,2)
										And B.kd_sskel = substr(A.c_barang,8,3)
										And B.kd_gol = '2'
										And B.kd_bid = '12'
										UNION
										Select  A.d_anggaran, A.c_barang, 
										to_char(A.i_aset,'09999') as i_aset,
										A.d_perolehan,  B.ur_sskel, A.n_hw 
										from E_AST_HARDWARE_0_TM A, E_AST_SSKEL_0_TR B
										Where A.i_orgb = ?
										And B.kd_gol = substr(A.c_barang,1,1)
										And B.kd_bid = substr(A.c_barang,2,2)
										And B.kd_kel = substr(A.c_barang,4,2)
										And B.kd_skel = substr(A.c_barang,6,2)
										And B.kd_gol = '2'
										And B.kd_bid = '12'
										", $where);
				$hasilAkhir = count($hasil);
			}else {
			    
				$xLimit=$itemPerPage;
			    $xOffset=($pageNumber-1)*$itemPerPage;
			 
				$result = $db->fetchAll("Select  A.d_anggaran, A.c_barang, to_char(A.i_aset,'09999') as i_aset , A.d_perolehan,  B.ur_sskel, 
										A.i_komputer_macaddress
										FROM  E_AST_KOMPUTER_0_TR A , E_AST_SSKEL_0_TR B
										Where A.C_Unit_Kerja = ?
										And B.kd_gol = substr(A.c_barang,1,1)
										And B.kd_bid = substr(A.c_barang,2,2)
										And B.kd_kel = substr(A.c_barang,4,2)
										And B.kd_skel = substr(A.c_barang,6,2)
										And B.kd_sskel = substr(A.c_barang,8,3)
										UNION
										Select  A.d_anggaran, A.c_barang, to_char(A.i_aset,'09999') as i_aset, A.d_perolehan,  B.ur_sskel, A.n_hw 
										from E_AST_HARDWARE_0_TM A, E_AST_SSKEL_0_TR B
										Where A.i_orgb = ?
										And B.kd_gol = substr(A.c_barang,1,1)
										And B.kd_bid = substr(A.c_barang,2,2)
										And B.kd_kel = substr(A.c_barang,4,2)
										And B.kd_skel = substr(A.c_barang,6,2)
										limit $xLimit offset $xOffset", $where);
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
			 
					$hasilAkhir[$j] = array("d_anggaran"       			=>(string)$result[$j]->d_anggaran,
											"c_barang"           		=>(string)$result[$j]->c_barang, 
											"i_aset"         			=>(string)$result[$j]->i_aset, 
											"d_perolehan"          		=>(string)$result[$j]->d_perolehan,
											"ur_sskel"         			=>(string)$result[$j]->ur_sskel,
											"i_komputer_macaddress"    	=>(string)$result[$j]->i_komputer_macaddress); 
				}
			}					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	//Insert Pengajuan Perbaikan TI
	public function insertPengajuanPerbaikanAsetTI(array $data)
	{
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		//$where = $data['noPengajuan'];
		try {
			$db->beginTransaction();
			
				$prmInsert = array("i_barang_ajuanbaik"		=>$data['noPengajuan'],
									"d_barang_ajuanbaik"	=>date("Y-m-d"),
									"i_orgb"				=>$data['unitktr'],
									"i_peg_nip"				=>$data['nipPemberi'],
									"e_barang_perbaikan"	=>$data['mslh'],
									"c_status_lengkap"		=>$data['nStatus'],
									"d_anggaran"			=>'0000', 
									"c_barang"				=>$data['kodeBarang'],
									"i_aset"				=>'00000',
									"d_perolehan"			=>$data['tglprlh'],
									"i_telpon"				=>$data['notelp'],
									"e_barang"				=>$data['keterangan'],
									"i_entry"				=>$data['i_entry'],									
									"d_entry"				=>date("Y-m-d"));
				
				$prmInsert2 = array("i_barang_ajuanbaik"	=>$data['noPengajuan'],
									"d_problem_awal"		=>$data['d_problem_awal'],
									"c_status_perbaikan"	=>$data['nStatusPerbaikan'],
									"i_peg_helpdesk"	=>'-',
									"i_entry"				=>$data['i_entry'],
									"d_entry"				=>date("Y-m-d"));
										
			$db->insert('e_ast_ajuanbaikti_0_tm',$prmInsert);
			$db->insert('e_ast_ajuanbaikti_item_tm',$prmInsert2);
			$db->commit();
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	
	//Ngambil data yang ssudah melakukan pengajuan perbaikan TI
	public function getPengajuanPerbaikanTIList($pageNumber,$itemPerPage,$kdunit)
	{
		try {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
	    $where[]=$kdunit;
		
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				/*$hasilResult = $db->fetchOne("Select count(*) from E_AST_AJUANBAIKTI_0_TM where  c_setuju_statustu is null
												And i_orgb = ? ",$where);*/
												
				$hasilResult = $db->fetchOne("Select count(*) from E_AST_AJUANBAIKTI_0_TM where  c_setuju_statussi is null
												And i_orgb = ? and c_status_lengkap is null ",$where);
												
			}
		
			else 
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				$result = $db->fetchAll("Select * from E_AST_AJUANBAIKTI_0_TM a, e_ast_ajuanbaikti_item_tm  b
										where  a.i_barang_ajuanbaik=b.i_barang_ajuanbaik
										and c_setuju_statussi is null
										And i_orgb = ? and c_status_lengkap is null 
										order by a.i_barang_ajuanbaik
										limit $xLimit offset $xOffset",$where );
				
				$jmlResult = count($result);
				for($j=0; $j<$jmlResult; $j++)
				{
					$hasilResult[$j] = array("i_barang_ajuanbaik"	=>(string)$result[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" 	=>(string)$result[$j]->d_barang_ajuanbaik,
											 "d_anggaran"			=>(string)$result[$j]->d_anggaran,
											 "c_barang"				=>(string)$result[$j]->c_barang,
											 "i_aset"				=>(string)$result[$j]->i_aset,
											 "i_peg_nip"			=>(string)$result[$j]->i_peg_nip,
											 "d_perolehan"			=>(string)$result[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$result[$j]->e_barang_perbaikan,
											 "d_problem_awal"		=>(string)$result[$j]->d_problem_awal
											 );
				}
			}
			
			return $hasilResult;
			
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
	
	//===========   Ngambil Nama pegawai berdasar kan NIP  =============================update by asih... 16 mei 08 ================
	function getNamaPegawai_Old($nip)
	{
		try {
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
		    $where[]=$nip;
			$where[]=$nip;
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			$result = $db->fetchAll("SELECT a.i_peg_nip,a.n_peg,b.n_jabatan,a.c_unit_kerja
									FROM e_sdm_pegawai_0_tm A, e_sdm_jabatan_0_tm B 
									where 
									a.i_peg_nip = b.i_peg_nip
									and a.c_unit_kerja = b.c_jabatan
									and a.i_peg_nip = ?
									union
									select a.i_peg_nip, n_peg, NULL, a.c_unit_kerja 
									from  e_sdm_pegawai_0_tm A 
									where  
									not EXISTS(select * from  e_sdm_jabatan_0_tm B
									          where a.i_peg_nip = b.i_peg_nip)
									and a.i_peg_nip = ? ",$where);
			$jmlResult = count($result);
			for($j=0; $j<$jmlResult; $j++)
			{
				$hasilResult[$j] = array("n_peg"	=>(string)$result[$j]->n_peg,
										 "n_jabatan" =>(string)$result[$j]->n_jabatan,
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
	
	//new get pegawai =============
	function getNamaPegawai($nip)
	{
		try {
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
		    $where[]=$nip;
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			$result = $db->fetchAll("SELECT a.i_peg_nip,a.n_peg,a.c_unit_kerja, b.n_orgb
									FROM e_sdm_pegawai_0_tm A, e_org_0_0_tm B
									where a.i_peg_nip = ?	
									and a.c_unit_kerja = b.i_orgb
									",$where);
									
			$jmlResult = count($result);
			for($j=0; $j<$jmlResult; $j++)
			{
				$n_jabatan 				= $db->fetchCol('select n_jabatan  
														from  e_sdm_jabatan_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nip);	
														
				$hasilResult[$j] = array("n_peg"		=>(string)$result[$j]->n_peg,
										 "i_peg_nip"		=>(string)$result[$j]->i_peg_nip,
										 "n_jabatan" 	=>$n_jabatan[0],
										 "n_orgb"	=>(string)$result[$j]->n_orgb,
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
	
	
	//===========   Ngambil Nama Banarang  =============================
	function getNamaBarang($kdbr)
	{
		try {
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$kd_gol = substr($kdbr,0,1);
			$kd_bid = substr($kdbr,1,2);
			$kd_kel = substr($kdbr,3,2);
			$kd_skel = substr($kdbr,5,2);
			$kd_sskel = substr($kdbr,7,3);
			
		    $where[]=$kd_gol;
			$where[]=$kd_bid;
			$where[]=$kd_kel;
			$where[]=$kd_skel;
			$where[]=$kd_sskel;
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			$result = $db->fetchAll("Select * from e_ast_sskel_0_tr 
									where kd_gol = ? 
									and kd_bid = ?
									and kd_kel = ?
									and kd_skel = ?
									and kd_sskel = ?
									",$where);
			$jmlResult = count($result);
			for($j=0; $j<$jmlResult; $j++)
			{
				$hasilResult[$j] = array("ur_sskel"	=>(string)$result[$j]->ur_sskel,
										 "satuan"	=>(string)$result[$j]->satuan
										 );
			}
			return $hasilResult;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	//===========   Ngambil Nama Banarang  =============================
	function getMcAddress($data)
	{
		try {
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$kd_gol = substr($kdbr,0,1);
			$kd_bid = substr($kdbr,1,2);
			$kd_kel = substr($kdbr,3,2);
			$kd_skel = substr($kdbr,5,2);
			$kd_sskel = substr($kdbr,7,3);
			
		    $where[]=$data['thnang'];
			$where[]=$data['kdbr'];
			$where[]=$data['noaset'];
			$where[]=$data['tgl'];
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			$result = $db->fetchAll("Select * from e_ast_komputer_0_tr 
									where d_anggaran = ? 
									and c_barang = ?
									and i_aset = ?
									and d_perolehan = ?
									",$where);
			$jmlResult = count($result);
			for($j=0; $j<$jmlResult; $j++)
			{
				$hasilResult[$j] = array("i_komputer_macaddress"	=>(string)$result[$j]->i_komputer_macaddress,
										 "i_peg_nip"	=>(string)$result[$j]->i_peg_nip,
										 "c_unit_kerja"	=>(string)$result[$j]->c_unit_kerja,
										 "i_ruang"	=>(string)$result[$j]->i_ruang,
										 "i_komputer_serialpc"	=>(string)$result[$j]->i_komputer_serialpc,
										 "i_komputer_serialwindow"	=>(string)$result[$j]->i_komputer_serialwindow,
										 "e_pc"	=>(string)$result[$j]->e_pc
										 );
			}
			return $hasilResult;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	public function getListPegawaiByUnit($pageNumber,$itemPerPage,$kdunit) {
		//echo 'kode unitnya ahhhhh'.$kdunit; 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   $where[] = $kdunit;
	   $where[] = $kdunit;
	   $where[] = $kdunit;
	   $where[] = $kdunit;
	   //echo " kdunti = ".$kdunit;
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasil = $db->fetchAll("SELECT a.i_peg_nip,a.n_peg,b.n_jabatan,a.c_unit_kerja
									FROM e_sdm_pegawai_0_tm A, e_sdm_jabatan_0_tm B 
									where 
									a.i_peg_nip = b.i_peg_nip
									and a.c_unit_kerja = b.c_jabatan
									and (a.i_orgb = ? or a.c_unit_kerja=?)
									union
									select a.i_peg_nip, n_peg, NULL, a.c_unit_kerja 
									from  e_sdm_pegawai_0_tm A 
									where  
									not EXISTS(select * from  e_sdm_jabatan_0_tm B
									          where a.i_peg_nip = b.i_peg_nip)
									and (a.c_unit_kerja = ? or a.i_orgb = ?)"
									,$where );
			$hasilAkhir = count($hasil);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 /* $result = $db->fetchAll("SELECT i_peg_nip,n_peg,n_jabatan,a.i_orgb,n_orgb 
									FROM e_sdm_pegawai_0_tm a, e_org_0_0_tm b
									where a.i_orgb = ? and a.i_orgb=b.i_orgb
									ORDER BY i_peg_nip
									limit $xLimit offset $xOffset",$where); */ 
			$result = $db->fetchAll("SELECT a.i_peg_nip,a.n_peg,b.n_jabatan,a.c_unit_kerja
									FROM e_sdm_pegawai_0_tm A, e_sdm_jabatan_0_tm B 
									where 
									a.i_peg_nip = b.i_peg_nip
									and a.c_unit_kerja = b.c_jabatan
									and (a.i_orgb = ? or a.c_unit_kerja=?)
									union
									select a.i_peg_nip, n_peg, NULL, a.c_unit_kerja 
									from  e_sdm_pegawai_0_tm A 
									where  
									not EXISTS(select * from  e_sdm_jabatan_0_tm B
									          where a.i_peg_nip = b.i_peg_nip)
									and (a.c_unit_kerja = ? or a.i_orgb = ?)
									order by n_peg 
									limit $xLimit offset $xOffset",$where);
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_peg_nip"         =>(string)$result[$j]->i_peg_nip,
									"n_peg"           	 	=>(string)$result[$j]->n_peg,
									"n_jabatan"          	=>(string)$result[$j]->n_jabatan,
									"i_orgb"            	=>(string)$result[$j]->c_unit_kerja
									);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	
	
	public function getDataPengajuan($noPengajuan){
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$where[] = $noPengajuan;
		//echo "nopengajuan = ".$noPengajuan;
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$result = $db->fetchAll("SELECT * FROM e_ast_ajuanbaikti_0_tm 
									where i_barang_ajuanbaik = ? ",$where);

			$jmlResult = count($result);

			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_barang_ajuanbaik"	=>(string)$result[$j]->i_barang_ajuanbaik,
										"d_barang_ajuanbaik"    =>(string)$result[$j]->d_barang_ajuanbaik,
										"i_peg_nip"          	=>(string)$result[$j]->i_peg_nip,
										"e_barang_perbaikan"          	=>(string)$result[$j]->e_barang_perbaikan,
										"d_anggaran"          	=>(string)$result[$j]->d_anggaran,
										"c_barang"          	=>(string)$result[$j]->c_barang,
										"i_aset"          	=>(string)$result[$j]->i_aset,
										"d_perolehan"          	=>(string)$result[$j]->d_perolehan,
										"i_barang_penerimaan"          	=>(string)$result[$j]->i_barang_penerimaan,
										"i_peg_nipterima"          	=>(string)$result[$j]->i_peg_nipterima,
										"e_barang"          	=>(string)$result[$j]->e_barang,
										"i_orgb"            	=>(string)$result[$j]->i_orgb
										);
			}	
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	
	public function getDataPengajuanItem($noPengajuan){
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$where[] = $noPengajuan;
		//echo "nopengajuan = ".$noPengajuan;
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$result = $db->fetchAll("SELECT * FROM e_ast_ajuanbaikti_item_tm 
									where i_barang_ajuanbaik = ? ",$where);

			$jmlResult = count($result);

			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_barang_ajuanbaik"	=>(string)$result[$j]->i_barang_ajuanbaik,
										"d_awal_perbaikan"    	=>(string)$result[$j]->d_awal_perbaikan,
										"d_akhir_perbaikan"     =>(string)$result[$j]->d_akhir_perbaikan,
										"q_jam_perbaikan"    	=>(string)$result[$j]->q_jam_perbaikan,
										"i_peg_helpdesk"        =>(string)$result[$j]->i_peg_helpdesk,
										"c_status_perbaikan"    =>(string)$result[$j]->c_status_perbaikan,
										"e_saran_sparepart"     =>(string)$result[$j]->e_saran_sparepart,
										"e_saran_pihakketiga"   =>(string)$result[$j]->e_saran_pihakketiga,
										"e_tindakan_perbaikan"  =>(string)$result[$j]->e_tindakan_perbaikan,
										"c_setuju_sparepart"    =>(string)$result[$j]->c_setuju_sparepart,
										"i_minta_sparepart"     =>(string)$result[$j]->i_minta_sparepart
										);
			}	
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	
	public function updatePengajuanPerbaikanAsetTI(array $data)
	{
		
	
		try {
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			
			$db->beginTransaction();
			$prmUpdate = array("i_orgb"  		    =>$data['unitktr'],
						       "d_anggaran"  		=>'0000',
							   "c_barang"  		 	=>$data['kodeBarang'],
							   "i_aset"  	 	 	=>'00000',
							   "i_peg_nip"  	 	=>$data['nipPemberi'],
							   "e_barang_perbaikan"	=>$data['mslh'],
							   "c_status_lengkap"	=>$data['nStatus'],
							   "i_telpon"			=>$data['notelp'],
							   "e_barang"			=>$data['keterangan'],
							   "i_entry"       		=>$data['i_entry'],
						       "d_entry"       		=>date("Y-m-d"));
							   
			$where[] = "i_barang_ajuanbaik  =  '".$data['noPengajuan']."'";
			$db->update('e_ast_ajuanbaikti_0_tm',$prmUpdate, $where);
			
			$prmUpdateItem = array("d_problem_awal"		=>$data['d_problem_awal'],
								   "c_status_perbaikan"	=>$data['nStatusPerbaikan'],
							       "i_entry"       		=>$data['i_entry'],
							       "d_entry"       		=>date("Y-m-d"));
								   
			$where[] = "i_barang_ajuanbaik  =  '".$data['noPengajuan']."'";
			$db->update('e_ast_ajuanbaikti_item_tm',$prmUpdateItem, $where);
			
			$db->commit();
			return 'sukses';
		
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	// ===================== Delete Pengajuan Perbaikan TI =============================
	public function deletePengajuanPerbaikanAsetTI($noPengajuan)
	{
		try
		{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$db->beginTransaction();
			$where[] = "i_barang_ajuanbaik = '".$noPengajuan."'";
			
			$db->delete('e_ast_ajuanbaikti_0_tm', $where);
			$db->commit();
			return 'sukses <br>';
		}catch (Exception $e){
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}
	
	
	//============== LISST Pengerjaan Perbaikan TI =========================
	public function getPengerjaanProblemView($pageNumber,$itemPerPage,$nmHelpdesk,$status)
	{
		$nm	=	$nmHelpdesk;
		$st	=	$status;
		$nmHelp= strtoupper($nmHelpdesk);
		$nmHelpdesk = '%'.$nmHelp.'%';
		$stats = strtoupper($status);
		$status = '%'.$stats.'%';
		
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
	
	//
	public function getPengerjaanProblemAll($pageNumber,$itemPerPage)
	{
		try {
			
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
										order by d_barang_ajuanbaik")	;
				$hasilList = count($hasil);
			
			}
			else 
			{
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
										order by d_barang_ajuanbaik
										limit $xLimit offset $xOffset")	;
				
								
				$jmlResult = count($result);
			
				for($j=0; $j<$jmlResult; $j++)
				{
					$n_problem_ctgr		= $db->fetchCol('select n_problem_ctgr  
														from  e_ast_kategori_problemti_tm 
														where c_problem_ctgr = ? ',$result[$j]->c_problem_ctgr);	
					
					$n_peg 				= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_helpdesk);	
														
					$hasilList[$j] = array("i_barang_ajuanbaik"		=>(string)$result[$j]->i_barang_ajuanbaik,
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
	
	public function getPengerjaanProblemList($pageNumber,$itemPerPage)
	{
	  try {
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
										to_char(A.i_aset,'09999') as i_aset, ur_sskel,
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										c_problem_ctgr,
										d_barang_ajuanbaik as d_problem_awal,
										c_barang_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B, E_ast_sskel_0_tr C
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and  substr(c_barang,1,1) = c.kd_gol
										and substr(c_barang,2,2) = c.kd_bid 
										and substr(a.c_barang,4,2) = c.kd_kel
										and substr(a.c_barang,6,2) = c.kd_skel
										and substr(a.c_barang,8,3) = c.kd_sskel
										and c_status_lengkap ='Y'
										and (c_status_perbaikan = 'B' or c_status_perbaikan = 'C')
										UNION
										SELECT  
										i_barang_ajuanbaik,
										d_barang_ajuanbaik,
										null as d_anggaran,
										null as c_barang,
										null as i_aset, null as ur_sskel,
										i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk,
										e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, 
										d_problem_awal,
										'' as c_barang_perbaikan
										FROM e_ast_problemti_0_tm a
										WHERE c_status_lengkap ='Y'
										and (c_status_perbaikan = 'B' or c_status_perbaikan = 'C')										
										order by i_barang_ajuanbaik 	
										")	;
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
										to_char(A.i_aset,'09999') as i_aset, ur_sskel,
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										c_problem_ctgr,
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan,
										c_barang_perbaikan,
										e_saran_sparepart,
										e_saran_pihakketiga,
										i_disposisi, i_orgb
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B, e_ast_sskel_0_tr c
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and  substr(c_barang,1,1) = c.kd_gol
										and substr(c_barang,2,2) = c.kd_bid 
										and substr(a.c_barang,4,2) = c.kd_kel
										and substr(a.c_barang,6,2) = c.kd_skel
										and substr(a.c_barang,8,3) = c.kd_sskel
										and c_status_lengkap ='Y'
										and (c_status_perbaikan = 'B' or c_status_perbaikan = 'C')										
										UNION
										SELECT  
										i_barang_ajuanbaik,
										d_barang_ajuanbaik,
										null as d_anggaran,
										null as c_barang,
										null as i_aset,
										null as ur_sskel,
										i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk,
										e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, 
										d_problem_awal,
										c_status_perbaikan,
										'' as c_barang_perbaikan,
										'' as e_saran_sparepart,
										'' as e_saran_pihakketiga,
										'' as i_disposisi, i_orgb
										FROM e_ast_problemti_0_tm a
										WHERE c_status_lengkap ='Y'
										and (c_status_perbaikan = 'B' or c_status_perbaikan = 'C')										
										order by d_barang_ajuanbaik
										limit $xLimit offset $xOffset")	;
				
								
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{
				    
					$n_problem_ctgr		= $db->fetchCol('select n_problem_ctgr  
														from  e_ast_kategori_problemti_tm 
														where c_problem_ctgr = ? ',$setuju[$j]->c_problem_ctgr);	
					
					$n_peg 				= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_helpdesk);	
					// Awal : Pencari Nama Pemohon dan org Pemohon									
					$n_peg_pemohon 	= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nippemohon);	
					
					if ($n_peg_pemohon==null || $n_peg_pemohon == '')
					{
					     $n_peg_pemohon 	=  $db->fetchCol('select n_peg_bumn  
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
					
							
					$hasilList[$j] = array("i_barang_ajuanbaik"		=>(string)$setuju[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" 	=>(string)$setuju[$j]->d_barang_ajuanbaik,
											 "i_orgb"				=>(string)$setuju[$j]->i_orgb,
											 "i_barang_penerimaan"	=>(string)$setuju[$j]->i_barang_penerimaan,
											 "d_anggaran"			=>(string)$setuju[$j]->d_anggaran,
											 "c_barang"				=>(string)$setuju[$j]->c_barang,
											 "i_aset"				=>(string)$setuju[$j]->i_aset,
											 "ur_sskel"				=>(string)$setuju[$j]->ur_sskel,
											 "i_peg_nip"			=>(string)$setuju[$j]->i_peg_nippemohon,
											 "i_peg_helpdesk"		=>(string)$setuju[$j]->i_peg_helpdesk,
											 "d_perolehan"			=>(string)$setuju[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$setuju[$j]->e_barang_perbaikan,
											 "c_problem_ctgr"		=>(string)$setuju[$j]->c_problem_ctgr,
											 "n_problem_ctgr"		=>$n_problem_ctgr[0],											 
											 "n_peg"				=>$n_peg[0],
											 "n_peg_pemohon"		=>$n_peg_pemohon[0],
											 "n_orgb"				=>$n_orgb[0],											 
											 "c_status_perbaikan"	=>(string)$setuju[$j]->c_status_perbaikan,
											 "c_barang_perbaikan"	=>(string)$setuju[$j]->c_barang_perbaikan,
											 "e_saran_sparepart"	=>(string)$setuju[$j]->e_saran_sparepart,
											 "e_saran_pihakketiga"	=>(string)$setuju[$j]->e_saran_pihakketiga,
											 "i_disposisi"			=>(string)$setuju[$j]->i_disposisi,
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
	
	public function getPengerjaanProblemListByNip($pageNumber,$itemPerPage,$nip)
	{
		$nip2 = strtoupper($nip);		
		try {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$where[] = $nip2;
				$where[] = $nip2;
				$hasil = $db->fetchAll("Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										c_problem_ctgr,
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  	a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 
										and upper(i_peg_helpdesk) =?
										and c_status_lengkap ='Y'
										and (c_status_perbaikan = 'B' or c_status_perbaikan = 'C')
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
										c_status_perbaikan
										FROM e_ast_problemti_0_tm a
										where  upper(i_peg_nip) =?
										and c_status_lengkap ='Y'
										and (c_status_perbaikan = 'B' or c_status_perbaikan = 'C')
										order by i_barang_ajuanbaik 	
										",$where)	;
				$hasilList = count($hasil);
			}
			else 
			{
				$where[] = $nip2;
				$where[] = $nip2;
		
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				$setuju = $db->fetchAll("Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset, ur_sskel, 
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										c_problem_ctgr,
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan, i_orgb
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B,
										E_AST_SSKEL_0_TR C
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 											
										and  substr(a.c_barang,1,1) = c.kd_gol
										and substr(a.c_barang,2,2) = c.kd_bid 
										and substr(a.c_barang,4,2) = c.kd_kel
										and substr(a.c_barang,6,2) = c.kd_skel
										and substr(a.c_barang,8,3) = c.kd_sskel
										and upper(i_peg_helpdesk) =?
										and c_status_lengkap ='Y'										
										and (c_status_perbaikan = 'B' or c_status_perbaikan = 'C')
										UNION
										SELECT  
										i_barang_ajuanbaik,
										d_barang_ajuanbaik,
										null as d_anggaran,
										null as c_barang,
										null as i_aset, null as ur_sskel,
										i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk,
										e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, 
										d_problem_awal,
										c_status_perbaikan, i_orgb
										FROM e_ast_problemti_0_tm a
										where upper(i_peg_nip) =?
										and c_status_lengkap ='Y'
										and (c_status_perbaikan = 'B' or c_status_perbaikan = 'C')
										order by d_barang_ajuanbaik
										limit $xLimit offset $xOffset",$where)	;
				
								
				$jmlSetuju = count($setuju);
				
				for($j=0; $j<$jmlSetuju; $j++)
				{
					$n_problem_ctgr		= $db->fetchCol('select n_problem_ctgr  
														from  e_ast_kategori_problemti_tm 
														where c_problem_ctgr = ? ',$setuju[$j]->c_problem_ctgr);	
					// Awal : Pencari Nama Pemohon dan org Pemohon
					$n_peg_pemohon 	= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nippemohon);	
														
					if ($n_peg_pemohon==null || $n_peg_pemohon == '')
					{
					     $n_peg_pemohon 	=  $db->fetchCol('select n_peg_bumn  
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
											 "ur_sskel"				=>(string)$setuju[$j]->ur_sskel,
											 "i_peg_nip"			=>(string)$setuju[$j]->i_peg_nippemohon,
											 "i_peg_helpdesk"		=>(string)$setuju[$j]->i_peg_helpdesk,
											 "d_perolehan"			=>(string)$setuju[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$setuju[$j]->e_barang_perbaikan,
											 "c_problem_ctgr"		=>(string)$setuju[$j]->c_problem_ctgr,
											 "n_problem_ctgr"		=>$n_problem_ctgr[0],
											 "n_peg"				=>$n_peg[0],
											 "n_peg_pemohon"		=>$n_peg_pemohon[0],
											 "n_orgb"				=>$n_orgb[0],											 
											 "c_status_perbaikan"	=>(string)$setuju[$j]->c_status_perbaikan
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
	
	//list by nip & periode
	public function getPengerjaanProblemListByNipPeriode($pageNumber,$itemPerPage,$data) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
			$where[] = strtoupper($data['nipPenerima']);
			$where[] = $data['prdAwal'];
			$where[] = $data['prdAkhir'];
			$where[] = strtoupper($data['nipPenerima']);
			$where[] = $data['prdAwal'];
			$where[] = $data['prdAkhir'];
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasil = $db->fetchAll("Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset,
										ur_sskel,
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										c_problem_ctgr,
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B, e_ast_sskel_0_tr C
										where  	a.i_barang_ajuanbaik = b.i_barang_ajuanbaik                           
										and  substr(c_barang,1,1) = c.kd_gol
										and substr(c_barang,2,2) = c.kd_bid 
										and substr(a.c_barang,4,2) = c.kd_kel
										and substr(a.c_barang,6,2) = c.kd_skel
										and substr(a.c_barang,8,3) = c.kd_sskel
										and upper(i_peg_helpdesk) =?
										and d_barang_ajuanbaik between ? and ?
										and c_status_lengkap ='Y'
										and (c_status_perbaikan = 'B' or c_status_perbaikan = 'C')
										UNION
										SELECT  
										i_barang_ajuanbaik,
										d_barang_ajuanbaik,
										null as d_anggaran,
										null as c_barang,
										null as i_aset,
										null as ur_sskel,
										i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk,
										e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, 
										d_problem_awal,
										c_status_perbaikan
										FROM e_ast_problemti_0_tm a
										where  upper(i_peg_nip) =?
										and d_barang_ajuanbaik between ? and ?
										and c_status_lengkap ='Y'
										and (c_status_perbaikan = 'B' or c_status_perbaikan = 'C')
										order by i_barang_ajuanbaik 	
										",$where)	;
				$hasilAkhir = count($hasil);
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				$result = $db->fetchAll("Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset,
										ur_sskel,
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										c_problem_ctgr,
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan, i_orgb
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B, e_ast_sskel_0_tr C
										where  	a.i_barang_ajuanbaik = b.i_barang_ajuanbaik                           
										and  substr(a.c_barang,1,1) = c.kd_gol
										and substr(a.c_barang,2,2) = c.kd_bid 
										and substr(a.c_barang,4,2) = c.kd_kel
										and substr(a.c_barang,6,2) = c.kd_skel
										and substr(a.c_barang,8,3) = c.kd_sskel
										and upper(i_peg_helpdesk) =?
										and d_barang_ajuanbaik between ? and ?
										and c_status_lengkap ='Y'
										and (c_status_perbaikan = 'B' or c_status_perbaikan = 'C')
										UNION
										SELECT  
										i_barang_ajuanbaik,
										d_barang_ajuanbaik,
										null as d_anggaran,
										null as c_barang,
										null as i_aset,
										null as ur_sskel,
										i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk,
										e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, 
										d_problem_awal,
										c_status_perbaikan, i_orgb
										FROM e_ast_problemti_0_tm a
										where upper(i_peg_nip) =?
										and d_barang_ajuanbaik between ? and ?
										and c_status_lengkap ='Y'
										and (c_status_perbaikan = 'B' or c_status_perbaikan = 'C')
										order by d_barang_ajuanbaik
										limit $xLimit offset $xOffset",$where);
				
				$jmlResult = count($result);
		 
				if($jmlResult > 0){
					for($j=0; $j<$jmlResult; $j++)
					{
						$n_problem_ctgr		= $db->fetchCol('select n_problem_ctgr  
															from  e_ast_kategori_problemti_tm 
															where c_problem_ctgr = ? ',$result[$j]->c_problem_ctgr);	
						
						$n_peg 				= $db->fetchCol('select n_peg  
															from  e_sdm_pegawai_0_tm 
															where i_peg_nip = ? ',$result[$j]->i_peg_helpdesk);	
															
															
						
						// Awal : Pencari Nama Pemohon dan org Pemohon									
					$n_peg_pemohon 	= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nippemohon);	
					
					if ($n_peg_pemohon==null || $n_peg_pemohon == '')
					{
					     $n_peg_pemohon 	=  $db->fetchCol('select n_peg_bumn  
														from  e_ast_problemti_0_tm 
														where i_barang_ajuanbaik = ? ',$result[$j]->i_barang_ajuanbaik);	
														
					     $n_orgb 	= $db->fetchCol('select n_bumn  
														from  a_bumn_0_0_tr 
														where i_bumn = ? ',$result[$j]->i_peg_nippemohon);	
					}	else
					{
					     $n_orgb 			= $db->fetchCol('select n_orgb  
														from  e_org_0_0_tm 
														where i_orgb = ? ',$result[$j]->i_orgb);
					}									
					// Akhir  : Pencari Nama Pemohon dan org Pemohon 						
					
						$hasilAkhir[$j] = array("i_barang_ajuanbaik"	=>(string)$result[$j]->i_barang_ajuanbaik,
												 "d_barang_ajuanbaik" 	=>(string)$result[$j]->d_barang_ajuanbaik,
												 "i_orgb"				=>(string)$result[$j]->i_orgb,
												 "i_barang_penerimaan"	=>(string)$result[$j]->i_barang_penerimaan,
												 "d_anggaran"			=>(string)$result[$j]->d_anggaran,
												 "c_barang"				=>(string)$result[$j]->c_barang,
												 "i_aset"				=>(string)$result[$j]->i_aset,
												 "ur_sskel"				=>(string)$result[$j]->ur_sskel,
												 "i_peg_nip"			=>(string)$result[$j]->i_peg_nippemohon,
												 "i_peg_helpdesk"		=>(string)$result[$j]->i_peg_helpdesk,
												 "d_perolehan"			=>(string)$result[$j]->d_perolehan,
												 "e_barang_perbaikan"	=>(string)$result[$j]->e_barang_perbaikan,
												 "c_problem_ctgr"		=>(string)$setuju[$j]->c_problem_ctgr,
												 "n_problem_ctgr"		=>$n_problem_ctgr[0],
												 "n_peg"				=>$n_peg[0],
												 "n_peg_pemohon"		=>$n_peg_pemohon[0],
												 "n_orgb"				=>$n_orgb[0],	
												 "c_status_perbaikan"	=>(string)$result[$j]->c_status_perbaikan
												 );
					}
				}
			}		 
			return $hasilAkhir;
		} catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
		}
	}
	
	//By Nama PIC =====================================================================================================
	
	public function getPengerjaanProblemListByNama($pageNumber,$itemPerPage,$nama)
	{
		$nama2   = '%'.strtoupper($nama).'%';
		
		try {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$where[] = $nama2;
				$where[] = $nama2;
				$hasil = $db->fetchAll("Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset, 
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										c_problem_ctgr,
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan,n_peg
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B, 
										e_sdm_pegawai_0_tm C
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik										
										and upper(n_peg) like ?	and c_status_lengkap ='Y'
										and (c_status_perbaikan = 'B' or c_status_perbaikan = 'C')
										and b.i_peg_helpdesk=C.i_peg_nip
										UNION
										SELECT  
										i_barang_ajuanbaik,
										d_barang_ajuanbaik,
										null as d_anggaran,
										null as c_barang,
										null as i_aset,
										i_peg_nippemohon,
										a.i_peg_nip as i_peg_helpdesk,
										e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, 
										d_problem_awal,
										c_status_perbaikan,n_peg  
										FROM e_ast_problemti_0_tm a, e_sdm_pegawai_0_tm b
										where upper(n_peg) like ?
										and c_status_lengkap ='Y'
										and (c_status_perbaikan = 'B' or c_status_perbaikan = 'C')
										and a.i_peg_nip =b.i_peg_nip 
										order by d_barang_ajuanbaik
										",$where)	;
				$hasilList = count($hasil);
			}
			else 
			{
				$where[] = $nama2;
				$where[] = $nama2;
		
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				$setuju = $db->fetchAll("Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset, ur_sskel, 
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										c_problem_ctgr,
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan, i_orgb    
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B, e_sdm_pegawai_0_tm C,
										E_AST_SSKEL_0_TR D
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and  substr(a.c_barang,1,1) = d.kd_gol
										and substr(a.c_barang,2,2) = d.kd_bid 
										and substr(a.c_barang,4,2) = d.kd_kel
										and substr(a.c_barang,6,2) = d.kd_skel
										and substr(a.c_barang,8,3) = d.kd_sskel
										and upper(n_peg) like ?	and c_status_lengkap ='Y'
										and (c_status_perbaikan = 'B' or c_status_perbaikan = 'C')
										and b.i_peg_helpdesk=C.i_peg_nip
										UNION
										SELECT  
										i_barang_ajuanbaik,
										d_barang_ajuanbaik,
										null as d_anggaran,
										null as c_barang,
										null as i_aset, nulll as ur_sskel,
										i_peg_nippemohon,
										a.i_peg_nip as i_peg_helpdesk,
										e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, 
										d_problem_awal,
										c_status_perbaikan, i_orgb  
										FROM e_ast_problemti_0_tm a, e_sdm_pegawai_0_tm b
										where upper(n_peg) like ?
										and c_status_lengkap ='Y'										
										and (c_status_perbaikan = 'B' or c_status_perbaikan = 'C')
										and a.i_peg_nip =b.i_peg_nip 
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
					
					// Awal : Pencari Nama Pemohon dan org Pemohon									
					$n_peg_pemohon 	= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nippemohon);	
					
					if ($n_peg_pemohon==null || $n_peg_pemohon == '')
					{
					     $n_peg_pemohon 	=  $db->fetchCol('select n_peg_bumn  
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
					
					$hasilList[$j] = array("i_barang_ajuanbaik"		=>(string)$setuju[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" 	=>(string)$setuju[$j]->d_barang_ajuanbaik,
											 "i_orgb"				=>(string)$setuju[$j]->i_orgb,
											 "i_barang_penerimaan"	=>(string)$setuju[$j]->i_barang_penerimaan,
											 "d_anggaran"			=>(string)$setuju[$j]->d_anggaran,
											 "c_barang"				=>(string)$setuju[$j]->c_barang,
											 "i_aset"				=>(string)$setuju[$j]->i_aset,
											 "ur_sskel"				=>(string)$setuju[$j]->ur_sskel,
											 "i_peg_nip"			=>(string)$setuju[$j]->i_peg_nippemohon,
											 "i_peg_helpdesk"		=>(string)$setuju[$j]->i_peg_helpdesk,
											 "d_perolehan"			=>(string)$setuju[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$setuju[$j]->e_barang_perbaikan,
											 "c_problem_ctgr"		=>(string)$setuju[$j]->c_problem_ctgr,
											 "n_problem_ctgr"		=>$n_problem_ctgr[0],
											 "n_peg"				=>$n_peg[0],											 
											 "n_peg_pemohon"		=>$n_peg_pemohon[0],
											 "n_orgb"				=>$n_orgb[0],											 											 
											 "c_status_perbaikan"	=>(string)$setuju[$j]->c_status_perbaikan
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
	
	//list by nama & periode
	public function getPengerjaanProblemListByNamaPeriode($pageNumber,$itemPerPage,$data) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
			$where[] =  '%'.strtoupper($data['namaPenerima']).'%';
			$where[] = $data['prdAwal'];
			$where[] = $data['prdAkhir'];
			$where[] =  '%'.strtoupper($data['namaPenerima']).'%';
			$where[] = $data['prdAwal'];
			$where[] = $data['prdAkhir'];
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
										c_problem_ctgr,
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan,n_peg
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B, e_sdm_pegawai_0_tm C
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and upper(n_peg) like ?	and c_status_lengkap ='Y'										
										and (c_status_perbaikan = 'B' or c_status_perbaikan = 'C')
										and b.i_peg_helpdesk=C.i_peg_nip
										and d_barang_ajuanbaik between ? and ?
										UNION
										SELECT  
										i_barang_ajuanbaik,
										d_barang_ajuanbaik,
										null as d_anggaran,
										null as c_barang,
										null as i_aset,
										i_peg_nippemohon,
										a.i_peg_nip as i_peg_helpdesk,
										e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, 
										d_problem_awal,
										c_status_perbaikan,n_peg  
										FROM e_ast_problemti_0_tm a, e_sdm_pegawai_0_tm b
										where upper(n_peg) like ?
										and c_status_lengkap ='Y'
										and (c_status_perbaikan = 'B' or c_status_perbaikan = 'C')
										and a.i_peg_nip =b.i_peg_nip 
										and d_barang_ajuanbaik between ? and ?
										order by d_barang_ajuanbaik
										",$where)	;
				$hasilAkhir = count($hasil);
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				$result = $db->fetchAll("Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset,ur_sskel,
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										c_problem_ctgr,
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan,i_orgb    
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B, e_sdm_pegawai_0_tm C,
										E_AST_SSKEL_0_TR D
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and upper(n_peg) like ?	and c_status_lengkap ='Y'
										and (c_status_perbaikan = 'B' or c_status_perbaikan = 'C')
										and b.i_peg_helpdesk=C.i_peg_nip
										and  substr(a.c_barang,1,1) = d.kd_gol
										and substr(a.c_barang,2,2) = d.kd_bid 
										and substr(a.c_barang,4,2) = d.kd_kel
										and substr(a.c_barang,6,2) = d.kd_skel
										and substr(a.c_barang,8,3) = d.kd_sskel										
										and d_barang_ajuanbaik between ? and ?
										UNION
										SELECT  
										i_barang_ajuanbaik,
										d_barang_ajuanbaik,
										null as d_anggaran,
										null as c_barang,
										null as i_aset, null as ur_sskel,
										i_peg_nippemohon,
										a.i_peg_nip as i_peg_helpdesk,
										e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, 
										d_problem_awal,
										c_status_perbaikan,i_orgb    
										FROM e_ast_problemti_0_tm a, e_sdm_pegawai_0_tm b
										where upper(n_peg) like ?
										and c_status_lengkap ='Y'
										and (c_status_perbaikan = 'B' or c_status_perbaikan = 'C')
										and a.i_peg_nip =b.i_peg_nip 
										and d_barang_ajuanbaik between ? and ?
										order by d_barang_ajuanbaik
										limit $xLimit offset $xOffset",$where);
				
				$jmlResult = count($result);
		 
				if($jmlResult > 0){
					for($j=0; $j<$jmlResult; $j++)
					{
						$n_problem_ctgr		= $db->fetchCol('select n_problem_ctgr  
															from  e_ast_kategori_problemti_tm 
															where c_problem_ctgr = ? ',$result[$j]->c_problem_ctgr);	
						
						$n_peg 				= $db->fetchCol('select n_peg  
															from  e_sdm_pegawai_0_tm 
															where i_peg_nip = ? ',$result[$j]->i_peg_helpdesk);	
															
						
// Awal : Pencari Nama Pemohon dan org Pemohon									
					$n_peg_pemohon 	= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nippemohon);	
					
					if ($n_peg_pemohon==null || $n_peg_pemohon == '')
					{
					     $n_peg_pemohon 	=  $db->fetchCol('select n_peg_bumn  
														from  e_ast_problemti_0_tm 
														where i_barang_ajuanbaik = ? ',$result[$j]->i_barang_ajuanbaik);	
														
					     $n_orgb 	= $db->fetchCol('select n_bumn  
														from  a_bumn_0_0_tr 
														where i_bumn = ? ',$result[$j]->i_peg_nippemohon);	
					}	else
					{
					     $n_orgb 			= $db->fetchCol('select n_orgb  
														from  e_org_0_0_tm 
														where i_orgb = ? ',$result[$j]->i_orgb);
					}									
					// Akhir  : Pencari Nama Pemohon dan org Pemohon 																	
					
									
															
						$hasilAkhir[$j] = array("i_barang_ajuanbaik"	=>(string)$result[$j]->i_barang_ajuanbaik,
												 "d_barang_ajuanbaik" 	=>(string)$result[$j]->d_barang_ajuanbaik,
												 "i_orgb"				=>(string)$result[$j]->i_orgb,
												 "i_barang_penerimaan"	=>(string)$result[$j]->i_barang_penerimaan,
												 "d_anggaran"			=>(string)$result[$j]->d_anggaran,
												 "c_barang"				=>(string)$result[$j]->c_barang,
												 "i_aset"				=>(string)$result[$j]->i_aset,
												 "ur_sskel"				=>(string)$result[$j]->ur_sskel,
												 "i_peg_nip"			=>(string)$result[$j]->i_peg_nippemohon,
												 "i_peg_helpdesk"		=>(string)$result[$j]->i_peg_helpdesk,
												 "d_perolehan"			=>(string)$result[$j]->d_perolehan,
												 "e_barang_perbaikan"	=>(string)$result[$j]->e_barang_perbaikan,
												 "c_problem_ctgr"		=>(string)$setuju[$j]->c_problem_ctgr,
												 "n_problem_ctgr"		=>$n_problem_ctgr[0],
												 "n_peg"				=>$n_peg[0],
												 "n_peg_pemohon"		=>$n_peg_pemohon[0],
												 "n_orgb"				=>$n_orgb[0],	
												 "c_status_perbaikan"	=>(string)$result[$j]->c_status_perbaikan
												 );
					}
				}
			}		 
			return $hasilAkhir;
		} catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
		}
	}
	
	//By Periode
	public function getPengerjaanProblemListByPeriode($pageNumber,$itemPerPage,$data) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where[] = $data['prdAwal'];
			$where[] = $data['prdAkhir'];
			$where[] = $data['prdAwal'];
			$where[] = $data['prdAkhir'];
			
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
										c_problem_ctgr,
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan, i_orgb
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  	a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 
										and d_barang_ajuanbaik between ? and ?
										and c_status_lengkap ='Y'
										and (c_status_perbaikan = 'B' or c_status_perbaikan = 'C')
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
										c_status_perbaikan, i_orgb
										FROM e_ast_problemti_0_tm a
										where  d_barang_ajuanbaik between ? and ?
										and c_status_lengkap ='Y'
										and (c_status_perbaikan = 'B' or c_status_perbaikan = 'C')
										order by i_barang_ajuanbaik 	
										",$where)	;
				$hasilAkhir = count($hasil);
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				$result = $db->fetchAll("Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset, ur_sskel, 										
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										c_problem_ctgr,
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan,i_orgb
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B,
										E_AST_SSKEL_0_TR C
										where  	a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 								
										and  substr(a.c_barang,1,1) = c.kd_gol
										and substr(a.c_barang,2,2) = c.kd_bid 
										and substr(a.c_barang,4,2) = c.kd_kel
										and substr(a.c_barang,6,2) = c.kd_skel
										and substr(a.c_barang,8,3) = c.kd_sskel
										and d_barang_ajuanbaik between ? and ?
										and c_status_lengkap ='Y'
										and (c_status_perbaikan = 'B' or c_status_perbaikan = 'C')
										UNION
										SELECT  
										i_barang_ajuanbaik,
										d_barang_ajuanbaik,
										null as d_anggaran,
										null as c_barang,
										null as i_aset, null as ur_sskel,
										i_peg_nippemohon,
										i_peg_nip as i_peg_helpdesk,
										e_problem_kasus as e_barang_perbaikan,
										a.c_problem_ctgr, 
										d_problem_awal,
										c_status_perbaikan, i_orgb
										FROM e_ast_problemti_0_tm a
										where  d_barang_ajuanbaik between ? and ?
										and c_status_lengkap ='Y'
										and (c_status_perbaikan = 'B' or c_status_perbaikan = 'C')
										order by i_barang_ajuanbaik 
										limit $xLimit offset $xOffset",$where);
				
				$jmlResult = count($result);
				
				if($jmlResult > 0){
					for($j=0; $j<$jmlResult; $j++)
					{
						$n_problem_ctgr		= $db->fetchCol('select n_problem_ctgr  
															from  e_ast_kategori_problemti_tm 
															where c_problem_ctgr = ? ',$result[$j]->c_problem_ctgr);	
						
						$n_peg 				= $db->fetchCol('select n_peg  
															from  e_sdm_pegawai_0_tm 
															where i_peg_nip = ? ',$result[$j]->i_peg_helpdesk);	
															
					
					
					// Awal : Pencari Nama Pemohon dan org Pemohon									
					$n_peg_pemohon 	= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nippemohon);	
					
					if ($n_peg_pemohon==null || $n_peg_pemohon == '')
					{
					     $n_peg_pemohon 	=  $db->fetchCol('select n_peg_bumn  
														from  e_ast_problemti_0_tm 
														where i_barang_ajuanbaik = ? ',$result[$j]->i_barang_ajuanbaik);	
														
					     $n_orgb 	= $db->fetchCol('select n_bumn  
														from  a_bumn_0_0_tr 
														where i_bumn = ? ',$result[$j]->i_peg_nippemohon);	
					}	else
					{
					     $n_orgb 			= $db->fetchCol('select n_orgb  
														from  e_org_0_0_tm 
														where i_orgb = ? ',$result[$j]->i_orgb);
					}									
					// Akhir  : Pencari Nama Pemohon dan org Pemohon 																	
					
					
						$hasilAkhir[$j] = array("i_barang_ajuanbaik"		=>(string)$result[$j]->i_barang_ajuanbaik,
												 "d_barang_ajuanbaik" 	=>(string)$result[$j]->d_barang_ajuanbaik,
												 "i_orgb"				=>(string)$result[$j]->i_orgb,
												 "i_barang_penerimaan"	=>(string)$result[$j]->i_barang_penerimaan,
												 "d_anggaran"			=>(string)$result[$j]->d_anggaran,
												 "c_barang"				=>(string)$result[$j]->c_barang,
												 "i_aset"				=>(string)$result[$j]->i_aset,
												 "ur_sskel"				=>(string)$result[$j]->ur_sskel,
												 "i_peg_nip"			=>(string)$result[$j]->i_peg_nippemohon,
												 "i_peg_helpdesk"		=>(string)$result[$j]->i_peg_helpdesk,
												 "d_perolehan"			=>(string)$result[$j]->d_perolehan,
												 "e_barang_perbaikan"	=>(string)$result[$j]->e_barang_perbaikan,
												 "c_problem_ctgr"		=>(string)$setuju[$j]->c_problem_ctgr,
												 "n_problem_ctgr"		=>$n_problem_ctgr[0],
												 "n_peg"				=>$n_peg[0],												 
												 "n_peg_pemohon"		=>$n_peg_pemohon[0],
												 "n_orgb"				=>$n_orgb[0],	
												 "c_status_perbaikan"	=>(string)$result[$j]->c_status_perbaikan
												 );
					}
				}
			}		 
			return $hasilAkhir;
		} catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
		}
	}
	
	//Insert Pengerjaan Perbaikan Barang TI
	public function prosesPengerjaan(array $data)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		$where[] = "i_barang_ajuanbaik = '".$data['noPengajuan']."'";
		try {
			$db->beginTransaction();
			
				$prmInsert = array("i_peg_helpdesk"			=>$data['nip_helpdesk'],
									"c_status_perbaikan"	=>$data['nStatusPerbaikan'],
								    "i_entry"				=>$data['i_entry'],
								    "d_entry"				=>date("Y-m-d"));
			
				$db->update('e_ast_ajuanbaikti_item_tm',$prmInsert,$where);
			
				$prmUpd = array("c_barang_perbaikan"		=>$data['c_barang_perbaikan'],
								   "c_problem_ctgr"			=>$data['c_problem_ctgr'],
								   "i_entry"				=>$data['i_entry'],
								   "d_entry"				=>date("Y-m-d"));
				
				$db->update('e_ast_ajuanbaikti_0_tm',$prmUpd,$where);
			
			$db->commit();
			return 'sukses <br>';
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	public function prosesPengerjaanProblem(array $data)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		$where[] = "i_barang_ajuanbaik = '".$data['noPengajuan']."'";
		try {
			$db->beginTransaction();
			
				$prmInsert = array("i_peg_nip"			=>$data['i_peg_nip'],
								   "c_problem_ctgr"		=>$data['c_problem_ctgr'],
								   "c_status_perbaikan"	=>$data['nStatusPerbaikan'],
								   "i_entry"			=>$data['i_entry'],
								   "d_entry"			=>date("Y-m-d"));
			//$db->insert('e_ast_ajuanbaikti_item_tm',$prmInsert);
			$db->update('e_ast_problemti_0_tm',$prmInsert,$where);
			$db->commit();
			return 'sukses <br>';
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	public function getDataPerbaikanByNoPengajuan($noPengajuan)
	{
	  try {
	    
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$where[] = $noPengajuan;
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				$setuju = $db->fetchAll("Select a.*, b.*,ur_sskel 
                         				 from  E_AST_AJUANBAIKTI_0_TM a,  e_ast_ajuanbaikti_item_tm b, e_ast_sskel_0_tr C
										 where a.i_barang_ajuanbaik =b.i_barang_ajuanbaik
										 and  substr(c_barang,1,1) = c.kd_gol
										 and substr(c_barang,2,2) = c.kd_bid 
										 and substr(a.c_barang,4,2) = c.kd_kel
										 and substr(a.c_barang,6,2) = c.kd_skel
										 and substr(a.c_barang,8,3) = c.kd_sskel
										 and a.i_barang_ajuanbaik = ? ",$where)	;
				
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{
							    
					$n_problem_ctgr		= $db->fetchCol('select n_problem_ctgr  
														from  e_ast_kategori_problemti_tm 
														where c_problem_ctgr = ? ',$setuju[$j]->c_problem_ctgr);
														
                    
					$n_peg_pemohon 	= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nip);	
														
					
					$n_orgb 			= $db->fetchCol('select n_orgb  
														from  e_org_0_0_tm 
														where i_orgb = ? ',$setuju[$j]->i_orgb);
					
					
					
														
					$hasilList[$j] = array("i_barang_ajuanbaik"			=>(string)$setuju[$j]->i_barang_ajuanbaik,
 											 "d_barang_ajuanbaik" 		=>(string)$setuju[$j]->d_barang_ajuanbaik,
											 "i_orgb"					=>(string)$setuju[$j]->i_orgb,
											 "i_barang_penerimaan"		=>(string)$setuju[$j]->i_barang_penerimaan,
											 "i_barang_pengembalian"	=>(string)$setuju[$j]->i_barang_pengembalian,
											 "d_anggaran"				=>(string)$setuju[$j]->d_anggaran,
											 "c_barang"					=>(string)$setuju[$j]->c_barang,
											 "i_aset"					=>(string)$setuju[$j]->i_aset,
											 "ur_sskel"					=>(string)$setuju[$j]->ur_sskel,
											 "i_peg_nip"				=>(string)$setuju[$j]->i_peg_nip,
											 "d_setuju_tu"				=>(string)$setuju[$j]->d_setuju_tu,
											 "d_setuju_si"				=>(string)$setuju[$j]->d_setuju_si,
											 "i_peg_nipterima"			=>(string)$setuju[$j]->i_peg_nipterima,
											 "d_perolehan"				=>(string)$setuju[$j]->d_perolehan,
											 "e_barang_perbaikan"		=>(string)$setuju[$j]->e_barang_perbaikan,
											 "i_peg_helpdesk"			=>(string)$setuju[$j]->i_peg_helpdesk,
											 "d_problem_awal"			=>(string)$setuju[$j]->d_problem_awal,
											 "d_awal_perbaikan"			=>(string)$setuju[$j]->d_awal_perbaikan,
											 "d_akhir_perbaikan"		=>(string)$setuju[$j]->d_akhir_perbaikan,
											 "q_jam_perbaikan"			=>(string)$setuju[$j]->q_jam_perbaikan,
											 "e_tindakan_perbaikan"		=>(string)$setuju[$j]->e_tindakan_perbaikan,
											 "c_barang_perbaikan"		=>(string)$setuju[$j]->c_barang_perbaikan,
											 "c_status_perbaikan"		=>(string)$setuju[$j]->c_status_perbaikan,
											 "e_saran_sparepart"		=>(string)$setuju[$j]->e_saran_sparepart,
											 "e_saran_pihakketiga"		=>(string)$setuju[$j]->e_saran_pihakketiga,
											 "i_disposisi"				=>(string)$setuju[$j]->i_disposisi,
											 "d_disposisi"				=>(string)$setuju[$j]->d_disposisi,
											 "e_alasan"					=>(string)$setuju[$j]->e_alasan,
											 "c_barang_perbaikan"		=>(string)$setuju[$j]->c_barang_perbaikan,
											 "i_telpon"					=>(string)$setuju[$j]->i_telpon,
											 "c_problem_ctgr"			=>(string)$setuju[$j]->c_problem_ctgr,
											 "e_barang"					=>(string)$setuju[$j]->e_barang,
											 "n_problem_ctgr"			=>$n_problem_ctgr[0],
											 "n_peg_pemohon"			=>$n_peg_pemohon[0],
											 "n_orgb"				=>$n_orgb[0]
											 );
											 
				}
						
			return $hasilList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	//data PC 
	public function getDataPC($thnang,$kdbrg,$noaset)
	{
	  try {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		$where[] = $thnang;
		$where[] = $kdbrg;
		$where[] = $noaset;
		
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				$setuju = $db->fetchAll("Select * from   e_ast_komputer_0_tr 
										 where d_anggaran = ? and c_barang = ? and i_aset = ? ",$where)	;
				
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{
				
					$hasilList[$j] = array("d_anggaran"					=>(string)$setuju[$j]->d_anggaran,
 											 "d_anggaran" 				=>(string)$setuju[$j]->d_anggaran,
											 "i_aset"					=>(string)$setuju[$j]->i_aset,
											 "d_perolehan"				=>(string)$setuju[$j]->d_perolehan,
											 "i_komputer_macaddress"	=>(string)$setuju[$j]->i_komputer_macaddress,
											 "i_peg_nip"				=>(string)$setuju[$j]->i_peg_nip,
											 "c_unit_kerja"				=>(string)$setuju[$j]->c_unit_kerja,
											 "i_aset"					=>(string)$setuju[$j]->i_aset,
											 "i_peg_nip"				=>(string)$setuju[$j]->i_peg_nip,
											 "i_ruang"					=>(string)$setuju[$j]->i_ruang,
											 "i_komputer_serialpc"		=>(string)$setuju[$j]->i_komputer_serialpc,
											 "i_komputer_serialwindow"	=>(string)$setuju[$j]->i_komputer_serialwindow,
											 "e_pc"						=>(string)$setuju[$j]->e_pc,
											 "n_type"					=>(string)$setuju[$j]->n_type
											 );
											 
				}
						
			return $hasilList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	
	public function getDataProblemByNoPengajuan($noPengajuan)
	{
		try {
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$where[] = $noPengajuan;
				$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				$result = $db->fetchAll("Select * from   e_ast_problemti_0_tm 
										 where i_barang_ajuanbaik = ? ",$where)	;
				
				$jmlSetuju = count($result);
				for($j=0; $j<$jmlSetuju; $j++)
				{
					$n_problem_ctgr		= $db->fetchCol('select n_problem_ctgr  
														from  e_ast_kategori_problemti_tm 
														where c_problem_ctgr = ? ',$result[$j]->c_problem_ctgr);
														
					// Awal : Pencari Nama Pemohon dan org Pemohon
					$n_peg_pemohon 	= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nippemohon);	
														
					if ($n_peg_pemohon==null || $n_peg_pemohon == '')
					{
					     $n_peg_pemohon 	=  $db->fetchCol('select n_peg_bumn  
														from  e_ast_problemti_0_tm 
														where i_barang_ajuanbaik = ? ',$result[$j]->i_barang_ajuanbaik);	
														
					     $n_orgb 	= $db->fetchCol('select n_bumn  
														from  a_bumn_0_0_tr 
														where i_bumn = ? ',$result[$j]->i_peg_nippemohon);	
					}	else
					{
					     $n_orgb 			= $db->fetchCol('select n_orgb  
														from  e_org_0_0_tm 
														where i_orgb = ? ',$result[$j]->i_orgb);
					}								
					// Akhir  : Pencari Nama Pemohon dan org Pemohon 	
														
					$hasilList[$j] = array("i_barang_ajuanbaik"		=>(string)$result[$j]->i_barang_ajuanbaik,
												"d_barang_ajuanbaik"    =>(string)$result[$j]->d_barang_ajuanbaik,
												"c_problem_ctgr"		=>(string)$result[$j]->c_problem_ctgr,
												"c_problem"    			=>(string)$result[$j]->c_problem,
												"d_problem_awal"		=>(string)$result[$j]->d_problem_awal,
												"d_awal_perbaikan"    	=>(string)$result[$j]->d_awal_perbaikan,
												"d_akhir_perbaikan"    	=>(string)$result[$j]->d_akhir_perbaikan,
												"e_problem_kasus"    	=>(string)$result[$j]->e_problem_kasus,
												"e_problem_penyebab"    =>(string)$result[$j]->e_problem_penyebab,
												"e_problem_solusi"    	=>(string)$result[$j]->e_problem_solusi,
												"e_sumberdata"    		=>(string)$result[$j]->e_sumberdata,
												"e_keterangan"    		=>(string)$result[$j]->e_keterangan,
												"q_nomor_max"       	=>(string)$result[$j]->q_nomor_max,
												"i_peg_nippemohon"		=>(string)$result[$j]->i_peg_nippemohon,
												"i_peg_nip"				=>(string)$result[$j]->i_peg_nip,
												"n_problem_ctgr"		=>$n_problem_ctgr[0],
												"n_peg_pemohon"			=>$n_peg_pemohon[0],
												"n_orgb"				=>$n_orgb[0],
												"c_status_perbaikan"	=>(string)$result[$j]->c_status_perbaikan
											 );
											 
				}
						
			return $hasilList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	//Insert Diagnosa & Pengerjaan Perbaikan Barang TI
	public function prosesPengerjaanDiagnosa(array $data)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		$where[] = "i_barang_ajuanbaik = '".$data['noPengajuan']."'";
		try {
			$db->beginTransaction();
			
				$prmInsert = array("d_awal_perbaikan"		=>$data['tglPengerjaan'],
								   "d_akhir_perbaikan"		=>$data['tglSelesai'],
								   "q_jam_perbaikan"		=>$data['jamPengerjaan'],
								   "e_saran_sparepart"		=>$data['spareParts'],
								   "e_saran_pihakketiga"	=>$data['pihakKetiga'],
								   "e_tindakan_perbaikan"	=>$data['tindakan'],
								   "c_status_perbaikan"		=>$data['status'],
								   "i_entry"				=>$data['i_entry'],
								   "d_entry"				=>date("Y-m-d"));
			
				$db->update('e_ast_ajuanbaikti_item_tm',$prmInsert,$where);
			
				$prmInsert1 = array("c_problem_ctgr"		=>$data['c_problem_ctgr']);
				$db->update('e_ast_ajuanbaikti_0_tm',$prmInsert1,$where);
			
			$db->commit();
			return 'sukses <br>';
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	public function prosesPengerjaanDiagnosaProblem(array $data)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		$where[] = "i_barang_ajuanbaik = '".$data['noPengajuan']."'";
		
		try {
			$db->beginTransaction();
			
				$prmInsert = array("c_problem_ctgr"		=>$data['c_problem_ctgr'],
									"d_awal_perbaikan"		=>$data['d_awal_perbaikan'],
								    "d_akhir_perbaikan"		=>$data['d_akhir_perbaikan'],
									"e_problem_penyebab"	=>$data['e_problem_penyebab'],
									"e_problem_solusi"		=>$data['e_problem_solusi'],
									"e_keterangan"			=>$data['e_keterangan'],
									"e_sumberdata"			=>$data['e_sumberdata'],
									"c_status_perbaikan"	=>$data['c_status_perbaikan'],
									"i_entry"				=>$data['i_entry'],
									"d_entry"				=>date("Y-m-d"));
								   
			//$db->insert('e_ast_ajuanbaikti_item_tm',$prmInsert);
			$db->update('e_ast_problemti_0_tm',$prmInsert,$where);
			$db->commit();
			return 'sukses <br>';
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	//=====================persetujuan ================
	
	public function getPersetujuanSIList()
	{
	  try {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		//$where[] = "Y";
	    $where[] = $kdunit;
		
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				$setuju = $db->fetchAll("Select * from E_AST_AJUANBAIKTI_0_TM where  
										c_setuju_statussi is null");
				
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{
				
					$setujuSIList[$j] = array("i_barang_ajuanbaik"	=>(string)$setuju[$j]->i_barang_ajuanbaik,
											  "d_barang_ajuanbaik" 	=>(string)$setuju[$j]->d_barang_ajuanbaik,
											  "d_anggaran"			=>(string)$setuju[$j]->d_anggaran,
											  "c_barang"			=>(string)$setuju[$j]->c_barang,
											  "i_aset"				=>(string)$setuju[$j]->i_aset,
											  "i_peg_nip"			=>(string)$setuju[$j]->i_peg_nip,
											  "d_perolehan"			=>(string)$setuju[$j]->d_perolehan,
											  "e_barang_perbaikan"	=>(string)$setuju[$j]->e_barang_perbaikan
											 );
				}
			
			return $setujuSIList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	public function prosesPersetujuanSI($data)
	{
		try
		{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$db->beginTransaction();
			
				$paramProses = array("c_setuju_statussi"	=>$data['status'],
										"i_disposisi"		=>$data['i_disposisi'],
										"d_disposisi"		=>date("Y-m-d"),
										"e_alasan"			=>$data['e_alasan'],
										"d_setuju_si"		=>date("Y-m-d"),
										"i_entry"			=>$data['i_entry'],
										"d_entry"			=>date("Y-m-d"));
			
			$where[] = "i_barang_ajuanbaik = '".$data['noPengajuan']."'";
			$db->update('e_ast_ajuanbaikti_0_tm',$paramProses, $where);
			$db->commit();
			return 'sukses <br>';
		}catch (Exception $e){
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}
	
	public function getDisposisiSIList($pageNumber,$itemPerPage)
	{
	  try {
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
										d_barang_ajuanbaik as d_problem_awal
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and (c_status_perbaikan ='N' or c_status_perbaikan ='D')
										and (e_saran_sparepart <> '' or e_saran_pihakketiga <> '')
										and (c_setuju_statussi is null or c_setuju_statussi ='')
										and (i_disposisi is null or i_disposisi ='')
										")	;
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
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan,
										e_saran_sparepart,
										e_saran_pihakketiga,
										i_disposisi,
										d_disposisi, ur_sskel, e_barang
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B,
                                        e_ast_sskel_0_tr C
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										And C.kd_gol = substr(A.c_barang,1,1)
										And C.kd_bid = substr(A.c_barang,2,2)
										And C.kd_kel = substr(A.c_barang,4,2)
										And C.kd_skel = substr(A.c_barang,6,2)
										And C.kd_sskel = substr(A.c_barang,8,3)
										and (c_status_perbaikan ='N' or c_status_perbaikan ='D')
										and (e_saran_sparepart <> '' or e_saran_pihakketiga <> '')
										and (c_setuju_statussi is null or c_setuju_statussi ='')
										and (i_disposisi is null or i_disposisi ='')
										order by d_barang_ajuanbaik
										limit $xLimit offset $xOffset")	;
				
								
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{
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
											 "n_problem_ctgr"		=>'',
											 "n_peg"				=>$n_peg[0],
											 "c_status_perbaikan"	=>(string)$setuju[$j]->c_status_perbaikan,
											 "e_saran_sparepart"	=>(string)$setuju[$j]->e_saran_sparepart,
											 "e_saran_pihakketiga"	=>(string)$setuju[$j]->e_saran_pihakketiga,
											 "i_disposisi"			=>(string)$setuju[$j]->i_disposisi,
											 "ur_sskel"				=>(string)$setuju[$j]->ur_sskel,
											 "e_barang"				=>(string)$setuju[$j]->e_barang,
											 "d_disposisi"			=>(string)$setuju[$j]->d_disposisi
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
	
	//==============================================penerimaan barang user =========================================
	
	public function getPenerimaanBarangList($pageNumber,$itemPerPage)
	{
	  try {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$where[] = "K";
		
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$setujuSIList = $db->fetchOne("Select count(*) from E_AST_AJUANBAIKTI_0_TM 
												where  c_barang_perbaikan = ?
												and (i_barang_penerimaan is null or i_barang_penerimaan ='') ",$where);
			}
		
			else 
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				$setuju = $db->fetchAll("Select A.*, ur_sskel  from E_AST_AJUANBAIKTI_0_TM A,
										e_ast_sskel_0_tr B
										where  c_barang_perbaikan = ?
										And B.kd_gol = substr(A.c_barang,1,1)
										And B.kd_bid = substr(A.c_barang,2,2)
										And B.kd_kel = substr(A.c_barang,4,2)
										And B.kd_skel = substr(A.c_barang,6,2)
										And B.kd_sskel = substr(A.c_barang,8,3)
										And (i_barang_penerimaan is null or i_barang_penerimaan ='')
										   limit $xLimit offset $xOffset",$where)	;
				
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{
				//echo "TEST serv ".$setuju[$j]->i_barang_ajuanbaik;
					$setujuSIList[$j] = array("i_barang_ajuanbaik"		=>(string)$setuju[$j]->i_barang_ajuanbaik,
												 "d_barang_ajuanbaik" 	=>(string)$setuju[$j]->d_barang_ajuanbaik,
												 "d_anggaran"			=>(string)$setuju[$j]->d_anggaran,
												 "c_barang"				=>(string)$setuju[$j]->c_barang,
												 "i_aset"				=>(string)$setuju[$j]->i_aset,
												 "i_peg_nip"			=>(string)$setuju[$j]->i_peg_nip,
												 "i_peg_nipterima"		=>(string)$setuju[$j]->i_peg_nipterima,
												 "d_perolehan"			=>(string)$setuju[$j]->d_perolehan,
												 "ur_sskel"				=>(string)$setuju[$j]->ur_sskel,
												 "e_barang"				=>(string)$setuju[$j]->e_barang,
												 "e_barang_perbaikan"	=>(string)$setuju[$j]->e_barang_perbaikan
											 );
				}
			}
			
			return $setujuSIList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	
	//Insert Penerimaan Barang Perbaikan TI
	public function prosesPenerimaan(array $data)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		//echo "no pengajuan service = ".$data['noPengajuan'];
		$noPengajuan = $data['noPengajuan'];
	    $where[] = "i_barang_ajuanbaik = '".$noPengajuan."'";
		try {
			$db->beginTransaction();
				$prminsert = array("i_barang_penerimaan"	=>$data['noPenerimaan'],
								   "i_peg_nipterima"		=>$data['nipPenerima'],
								   "i_peg_nipserah"			=>$data['nipPemberi'],
								   "i_barang_ajuanbaik"		=>$noPengajuan,
								   "d_barang_penerimaan"	=>date("Y-m-d"),
								   "i_entry"				=>$data['i_entry'],
								   "d_entry"				=>date("Y-m-d")
								   );
			$db->insert('e_ast_terimabaikti_0_tm',$prminsert);
			
				$prmupdate = array("i_peg_nip"				=>$data['nipPemberi'],
								   "i_peg_nipterima"		=>$data['nipPenerima'],
								   "i_barang_penerimaan"	=>$data['noPenerimaan']);
			$db->update('e_ast_ajuanbaikti_0_tm',$prmupdate,$where);
				
			$db->commit();
			return 'sukses <br>';
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	
	//============== LISST Pengembalian Perbaikan TI =========================
	public function getPengembalianBarangList($pageNumber,$itemPerPage)
	{
	  try {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$where = "Y";
		$where = "N";
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$setujuSIList = $db->fetchAll("Select a.i_barang_ajuanbaik,a.d_barang_ajuanbaik,a.i_barang_penerimaan,
										a.d_anggaran,a.c_barang,
										to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip,a.d_perolehan,
										a.e_barang_perbaikan,a.i_orgb from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B  
										where  
										a.i_barang_pengembalian is null
										and a.i_barang_penerimaan is not null
										and a.i_barang_ajuanbaik = b.i_barang_ajuanbaik
										and b.c_status_perbaikan ='Y'
										");
				$hasilList = count($setujuSIList);
			}
		
			else 
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;				
				
				$setuju = $db->fetchAll("Select a.i_barang_ajuanbaik,a.d_barang_ajuanbaik,a.i_barang_penerimaan,
										a.d_anggaran,a.c_barang,
										to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip,a.d_perolehan,e_barang, ur_sskel, n_peg,
										a.e_barang_perbaikan,a.i_orgb from E_AST_AJUANBAIKTI_0_TM A, 
										E_AST_AJUANBAIKTI_ITEM_TM B, e_ast_sskel_0_tr   C, e_sdm_pegawai_0_tm D
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik
										And C.kd_gol = substr(A.c_barang,1,1)
										And C.kd_bid = substr(A.c_barang,2,2)
										And C.kd_kel = substr(A.c_barang,4,2)
										And C.kd_skel = substr(A.c_barang,6,2)
										And C.kd_sskel = substr(A.c_barang,8,3)
										AND A.I_Peg_Nip = D.i_Peg_Nip
										and a.i_barang_pengembalian is null
										and a.i_barang_penerimaan is not null
										and b.c_status_perbaikan ='Y'
										limit $xLimit offset $xOffset");
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{
				//echo "TEST serv ".$setuju[$j]->i_barang_ajuanbaik;
					$hasilList[$j] = array("i_barang_ajuanbaik"		=>(string)$setuju[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" 	=>(string)$setuju[$j]->d_barang_ajuanbaik,
											 "i_orgb"				=>(string)$setuju[$j]->i_orgb,
											 "i_barang_penerimaan"	=>(string)$setuju[$j]->i_barang_penerimaan,
											 "d_anggaran"			=>(string)$setuju[$j]->d_anggaran,
											 "c_barang"				=>(string)$setuju[$j]->c_barang,
											 "i_aset"				=>(string)$setuju[$j]->i_aset,
											 "i_peg_nip"			=>(string)$setuju[$j]->i_peg_nip,
											 "d_perolehan"			=>(string)$setuju[$j]->d_perolehan,
											 "e_barang"				=>(string)$setuju[$j]->e_barang,
											 "ur_sskel"				=>(string)$setuju[$j]->ur_sskel,
											 "n_peg"				=>(string)$setuju[$j]->n_peg,
											 "e_barang_perbaikan"	=>(string)$setuju[$j]->e_barang_perbaikan
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
	
	// ======================== PROSES PERSETUJUAN SPARE PARTS ==============================
	
	public function getPermintaanSparePartList()
	{
	  try {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$where[] = "N";
		$where[] = "D";
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			/* if(($pageNumber==0) && ($itemPerPage==0))
			{
				$setujuSIList = $db->fetchOne("Select *  from  e_ast_ajuanbaikti_item_tm 
								Where c_status_perbaikan = 'N' and c_setuju_sparepart is null");
			}
		
			else 
			{ */
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				/* $setuju = $db->fetchAll("Select *  from  e_ast_ajuanbaikti_item_tm 
										Where c_status_perbaikan = 'N' and c_setuju_sparepart is null 
										limit $xLimit offset $xOffset")	; */
				$setuju = $db->fetchAll("Select * from e_ast_ajuanbaikti_item_tm A, e_ast_ajuanbaikti_0_tm B
										where a.i_barang_ajuanbaik = b.i_barang_ajuanbaik
										and (a.c_status_perbaikan = 'N' or a.c_status_perbaikan = 'D') 
										and (i_disposisi is not null	or i_disposisi <> '')
										and (c_setuju_sparepart is null or c_setuju_sparepart = '') ")	;
				
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{
					$n_peg_helpdesk 				= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_helpdesk);
					
					$n_peg_nip 						= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nip);
					
					$setujuSIList[$j] = array("i_barang_ajuanbaik"		=>(string)$setuju[$j]->i_barang_ajuanbaik,
												 "d_awal_perbaikan" 	=>(string)$setuju[$j]->d_awal_perbaikan,
												 "d_akhir_perbaikan"	=>(string)$setuju[$j]->d_akhir_perbaikan,
												 "q_jam_perbaikan"		=>(string)$setuju[$j]->q_jam_perbaikan,
												 "c_status_perbaikan"	=>(string)$setuju[$j]->c_status_perbaikan,
												 "e_saran_sparepart"	=>(string)$setuju[$j]->e_saran_sparepart,
												 "e_saran_pihakketiga"	=>(string)$setuju[$j]->e_saran_pihakketiga,
												 "e_tindakan_perbaikan"	=>(string)$setuju[$j]->e_tindakan_perbaikan,
												 "c_setuju_sparepart"	=>(string)$setuju[$j]->c_setuju_sparepart,
												 "i_minta_sparepart"	=>(string)$setuju[$j]->i_minta_sparepart,
												 "d_minta_sparepart"	=>(string)$setuju[$j]->d_minta_sparepart,
												 "i_peg_nipsetuju"		=>(string)$setuju[$j]->i_peg_nipsetuju,
												 "c_terima_sparepart"	=>(string)$setuju[$j]->c_terima_sparepart,
												 "d_terima_sparepart"	=>(string)$setuju[$j]->d_terima_sparepart,
												 "i_peg_nipterima"		=>(string)$setuju[$j]->i_peg_nipterima,
												 "i_peg_helpdesk"		=>(string)$setuju[$j]->i_peg_helpdesk,
												 "n_peg_helpdesk"		=>$n_peg_helpdesk[0],
												 "i_peg_nip"			=>(string)$setuju[$j]->i_peg_nip,
												 "n_peg_nip"			=>$n_peg_nip[0],
												 "i_orgb"				=>(string)$setuju[$j]->i_orgb,
												 "e_alasan"				=>(string)$setuju[$j]->e_alasan,
												 "d_barang_ajuanbaik"	=>(string)$setuju[$j]->d_barang_ajuanbaik
											 
											 );
				}
			//}
			
			return $setujuSIList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	public function prosesPersetujuanSpareParts($data)
	{
		try
		{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$db->beginTransaction();
			$alasan = $data['alasan'];
			
			$paramProses = array("c_setuju_sparepart"		=>$data['status'],
									"e_alasan_sparepart"	=>$alasan,
									"i_minta_sparepart" 	=>$data['noPermintaan'],
									"i_peg_nipsetuju" 		=>$data['nip'],
									"d_minta_sparepart"		=>date("Y-m-d"));
			
			$where[] = "i_barang_ajuanbaik = '".$data['noPengajuan']."'";
			$db->update('e_ast_ajuanbaikti_item_tm',$paramProses, $where);
			$db->commit();
			return 'sukses <br>';
		}catch (Exception $e){
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}
	
	public function UpdatePersetujuanSpareParts($data)
	{
		try
		{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$db->beginTransaction();
			$alasan = $data['alasan'];
			
			$paramProses = array("i_rab"   	    		=>$data['rab'],
									"i_mak"   	    		=>$data['mak'],
									"i_rab_norutdlmrangka"  =>$data['rabkg'],
									"i_rab_norut"   	    =>$data['nrabkg']);
			
			$where[] = "i_barang_ajuanbaik = '".$data['noPengajuan']."'";
			$db->update('e_ast_ajuanbaikti_0_tm',$paramProses, $where);
			$db->commit();
			return 'sukses <br>';
		}catch (Exception $e){
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}
	
	public function getPenerimaanSparePartList()
	{
	  try {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$where[] = "Y";
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			/* if(($pageNumber==0) && ($itemPerPage==0))
			{
				$setujuSIList = $db->fetchOne("Select *  from  e_ast_ajuanbaikti_item_tm 
								Where c_status_perbaikan = 'N' and c_setuju_sparepart is null");
			}
		
			else 
			{ */
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				/* $setuju = $db->fetchAll("Select * from e_ast_ajuanbaikti_item_tm 
								where c_setuju_sparepart = 'Y'	
								")	; */
				 $setuju = $db->fetchAll("Select A.*, B.* ,n_peg
								from e_ast_ajuanbaikti_item_tm A, e_ast_ajuanbaikti_0_tm B,
								e_sdm_pegawai_0_tm C
								where a.i_barang_ajuanbaik = b.i_barang_ajuanbaik
								and A.i_peg_helpdesk = C.i_peg_nip
								and a.c_setuju_sparepart = 'Y'	
								and a.c_terima_sparepart is null
								")	; 
				
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{
				//echo "TEST serv ".$setuju[$j]->i_barang_ajuanbaik;
					$setujuSIList[$j] = array("i_barang_ajuanbaik"	=>(string)$setuju[$j]->i_barang_ajuanbaik,
											 "d_awal_perbaikan" =>(string)$setuju[$j]->d_awal_perbaikan,
											 "d_akhir_perbaikan"	=>(string)$setuju[$j]->d_akhir_perbaikan,
											 "q_jam_perbaikan"	=>(string)$setuju[$j]->q_jam_perbaikan,
											 "c_status_perbaikan"	=>(string)$setuju[$j]->c_status_perbaikan,
											 "e_saran_sparepart"	=>(string)$setuju[$j]->e_saran_sparepart,
											 "e_saran_pihakketiga"	=>(string)$setuju[$j]->e_saran_pihakketiga,
											 "e_tindakan_perbaikan"	=>(string)$setuju[$j]->e_tindakan_perbaikan,
											 "c_setuju_sparepart"=>(string)$setuju[$j]->c_setuju_sparepart,
											 "i_minta_sparepart"=>(string)$setuju[$j]->i_minta_sparepart,
											 "d_minta_sparepart"=>(string)$setuju[$j]->d_minta_sparepart,
											 "i_peg_nipsetuju"=>(string)$setuju[$j]->i_peg_nipsetuju,
											 "c_terima_sparepart"=>(string)$setuju[$j]->c_terima_sparepart,
											 "d_terima_sparepart"=>(string)$setuju[$j]->d_terima_sparepart,
											 "i_peg_nipterima"=>(string)$setuju[$j]->i_peg_nipterima,
											 "i_peg_nip"=>(string)$setuju[$j]->i_peg_nip,
											 "helpdesk"=>(string)$setuju[$j]->n_peg,
											 "d_barang_ajuanbaik"	=>(string)$setuju[$j]->d_barang_ajuanbaik
											 
											 );
				}
			//}
			
			return $setujuSIList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	// ======================== PROSES PENERIMAAN SPARE PARTS ==============================
	public function prosesPenerimaanSpareParts($data)
	{
		try
		{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$db->beginTransaction();
			$alasan = $data['alasan'];
			
			$paramProses = array("c_terima_sparepart"	=>$data['status'],
									"i_peg_nipterima" =>$data['nip'],
									"d_terima_sparepart"=>date("Y-m-d")
								);
			
			$where[] = "i_minta_sparepart = '".$data['noPermintaan']."'";
			$db->update('e_ast_ajuanbaikti_item_tm',$paramProses, $where);
			$db->commit();
			return 'sukses <br>';
		}catch (Exception $e){
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}
	
	//NGAMBIL DATA PERBAIKAN YG SUDAH DI KEMBALIKAN DARI TABEL E_AST_KEMBALIBAIKTI_O_TM
	public function getDataPengembalian($noPengajuan)
	{
	  try {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$where[] = $noPengajuan;
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
				
				$setuju = $db->fetchAll("Select * from E_AST_KEMBALIBAIKTI_0_TM where  i_barang_ajuanbaik = ? ",$where)	;
				
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{
				//echo "TEST serv ".$setuju[$j]->i_barang_ajuanbaik;
					 $hasilList[$j] = array("i_barang_ajuanbaik"		=>(string)$setuju[$j]->i_barang_ajuanbaik,
											"i_barang_pengembalian" 	=>(string)$setuju[$j]->i_barang_pengembalian,
											"d_barang_pengembalian"		=>(string)$setuju[$j]->d_barang_pengembalian,
											"i_peg_nipterima"	=>(string)$setuju[$j]->i_peg_nipterima,
											"i_peg_nipserah"	=>(string)$setuju[$j]->i_peg_nipserah
											 );
				}
			
			return $hasilList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	public function getRabListAll($pageNumber,$itemPerPage,$nRab) {
		$namaBarang = strtoupper($nRab);
		$nbrg = '%'.$nRab.'%';
	   
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $nbrg;
			 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$result = $db->fetchAll("select distinct A.i_rab,B.e_rab_giat, A.i_mak, C.n_mak
											from e_rab_non_standard_tm A, e_rab_0_0_tm B,
											e_keu_mak_0_tr C
											where A.i_rab = B.i_rab
											and A.c_rab_versi = B.c_rab_versi
											and A.i_mak = C.i_mak
											and upper(A.e_rab_ket) like '%PERBAIKAN%'
											and upper(e_rab_giat) like ? ", $where);
											
				$hasilAkhir = count($result);							
			 }
			 else
			 {
			
				 $xLimit=$itemPerPage;
				 $xOffset=($pageNumber-1)*$itemPerPage;		
				 
				 $result = $db->fetchAll("select distinct A.i_rab,B.e_rab_giat, A.i_mak, C.n_mak
											from e_rab_non_standard_tm A, e_rab_0_0_tm B,
											e_keu_mak_0_tr C
											where A.i_rab = B.i_rab
											and A.c_rab_versi = B.c_rab_versi
											and A.i_mak = C.i_mak
											and upper(A.e_rab_ket) like '%PERBAIKAN%'
											and upper(e_rab_giat) like ? 
											ORDER BY i_rab
											limit $xLimit offset $xOffset", $where); 
				 
				 $jmlResult = count($result);
				 
				 for ($j = 0; $j < $jmlResult; $j++) {
						$hasilAkhir[$j] = array("i_rab"           =>(string)$result[$j]->i_rab,
												"e_rab_giat"      =>(string)$result[$j]->e_rab_giat,
												"i_mak"           =>(string)$result[$j]->i_mak,
												"n_mak"           =>(string)$result[$j]->n_mak);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	//==============list rab kegiatan =========================
	public function getRabkegList($pageNumber,$itemPerPage,$namaBarang,$rab,$mak) {
		$namaBarang = strtoupper($namaBarang);
		$nbrg = '%'.$namaBarang.'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $rab;
			$where[] = $mak;
			$where[] = $nbrg;
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$result = $db->fetchAll("select distinct A.i_rab_norutdlmrangka, A.i_rab_norut,
										A.e_rab_dalamrangka
										from e_rab_non_standard_tm A, e_rab_0_0_tm B,
										e_keu_mak_0_tr C
										where A.i_rab = B.i_rab
										and A.c_rab_versi = B.c_rab_versi
										and A.i_mak = C.i_mak
										and A.i_rab = ?   -- Inputan dari popup 1
										and A.i_mak = ?    -- Inputan dari popup 1
										and upper(A.e_rab_ket) like '%PERBAIKAN%'
										and upper(e_rab_dalamrangka) like ? ",$where); 
			 
			 
				$hasilAkhir  = count($result);
			 }
			else
			 {
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("select distinct A.i_rab_norutdlmrangka, A.i_rab_norut,
										A.e_rab_dalamrangka
										from e_rab_non_standard_tm A, e_rab_0_0_tm B,
										e_keu_mak_0_tr C
										where A.i_rab = B.i_rab
										and A.c_rab_versi = B.c_rab_versi
										and A.i_mak = C.i_mak
										and A.i_rab = ?   -- Inputan dari popup 1
										and A.i_mak = ?    -- Inputan dari popup 1
										and upper(A.e_rab_ket) like '%PERBAIKAN%'
										and upper(e_rab_dalamrangka) like ? 
										ORDER BY i_rab_norut
										limit $xLimit offset $xOffset",$where); 
			 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_rab_norutdlmrangka"      =>(string)$result[$j]->i_rab_norutdlmrangka,
										   "i_rab_norut"           	=>(string)$result[$j]->i_rab_norut,
										   "e_rab_dalamrangka"    	=>(string)$result[$j]->e_rab_dalamrangka);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	//Insert Laporan (Pengembalian)  Barang Perbaikan TI
	public function prosesPengembalian(array $data)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		//echo "no pengajuan service = ".$data['noPengajuan'];
		$noPengajuan = $data['noPengajuan'];
	    $where[] = "i_barang_ajuanbaik = '".$noPengajuan."'";
		try {
			$db->beginTransaction();
			
			$prmUpdate = array("i_barang_pengembalian"	=>$data['noPengembalian']);
			$db->update('e_ast_ajuanbaikti_0_tm',$prmUpdate,$where);
			
			$prmInsert = array("i_barang_pengembalian"=>$data['noPengembalian'],
							   "d_barang_pengembalian"=>date('Y-m-d'),
							   "i_barang_ajuanbaik"=>$noPengajuan,
							   "i_peg_nipterima"=>$data['nipPenerima'],
							   "i_peg_nipserah"=>$data['nipPemberi'],
							   "i_entry"=>$data['i_entry'],
							   "d_entry"=>date('Y-m-d')
							   );
			$db->insert('e_ast_kembalibaikti_0_tm',$prmInsert);
			
			$db->commit();
			return 'sukses <br>';
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	public function getNamaPegawaiSerah($noPenerimaan){
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		//echo "nopenerimaan srvc = ".$noPenerimaan."<br>";
		$where[] = $noPenerimaan;
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$result = $db->fetchAll("SELECT * FROM e_ast_terimabaikti_0_tm 
									where i_barang_penerimaan = ? ",$where);

			$jmlResult = count($result);

			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_barang_penerimaan"	=>(string)$result[$j]->i_barang_penerimaan,
										"d_barang_penerimaan"    =>(string)$result[$j]->d_barang_penerimaan,
										"i_barang_ajuanbaik"          	=>(string)$result[$j]->i_barang_ajuanbaik,
										"i_peg_nipterima"    =>(string)$result[$j]->i_peg_nipterima,
										"i_peg_nipserah"          	=>(string)$result[$j]->i_peg_nipserah
										);
			}	
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	
	public function getSabmMaster($data){
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$where[] = $data['thnang'];
		$where[] = $data['kdbrg'];
		$where[] = $data['noaset'];
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$result = $db->fetchAll("SELECT merk_type,keterangan FROM e_sabm_t_master_tm 
									where thn_ang = ? and kd_brg = ? and no_aset = ?",$where);

			$jmlResult = count($result);

			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("merk_type"	=>(string)$result[$j]->merk_type,
										"keterangan"    =>(string)$result[$j]->keterangan
										);
			}	
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	
	//
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
	
	//melihat problem User -> dulunya perbaikan Aset TI -> dari ajuan baik TI sj... 16 Juli 08===========
	
	public function getProblemUser($pageNumber,$itemPerPage,$nmHelpdesk,$status)
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
											 "i_disposisi"			=>(string)$setuju[$j]->i_disposisi);
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
	
	//==================================by Nama ============================================5Agustus 08 ----------------
	
	public function getProblemUserByNama($pageNumber,$itemPerPage,$nmHelpdesk,$status)
	{
		$nmHelpdesk = strtoupper($nmHelpdesk);
		$nmHelpdesk = '%'.$nmHelpdesk.'%';
		
		try {
			$where[] = $nmHelpdesk;
			
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
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan,
										c_barang_perbaikan,
										e_saran_sparepart,
										e_saran_pihakketiga,
										i_disposisi,
										n_peg
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B, e_sdm_pegawai_0_tm C
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and b.i_peg_helpdesk = C.i_peg_nip
										and upper(n_peg) like ?
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
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan,
										c_barang_perbaikan,
										e_saran_sparepart,
										e_saran_pihakketiga,
										i_disposisi,
										n_peg
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B, e_sdm_pegawai_0_tm C
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and b.i_peg_helpdesk = C.i_peg_nip
										and upper(n_peg) like ?
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
											 "i_disposisi"			=>(string)$setuju[$j]->i_disposisi);
				}
			}
						
			return $hasilList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	// all yg login pejabat =========
	
	public function getProblemUserAll($pageNumber,$itemPerPage,$nmHelpdesk,$status)
	{
		$nmHelpdesk = strtoupper($nmHelpdesk);
		$nmHelpdesk = '%'.$nmHelpdesk.'%';
		
		try {
			$where[] = $nmHelpdesk;
			
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
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan,
										c_barang_perbaikan,
										e_saran_sparepart,
										e_saran_pihakketiga,
										i_disposisi
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and c_status_perbaikan <> 'A'
										order by d_barang_ajuanbaik	
										")	;
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
										and c_status_perbaikan <> 'A'
										order by d_barang_ajuanbaik
										limit $xLimit offset $xOffset")	;
								
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
											 "i_disposisi"			=>(string)$setuju[$j]->i_disposisi);
				}
			}
						
			return $hasilList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	
	//By Status ==================================================================
	public function getProblemUserByStatus($pageNumber,$itemPerPage,$nmHelpdesk,$status)
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
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan,
										c_barang_perbaikan,
										e_saran_sparepart,
										e_saran_pihakketiga,
										i_disposisi,
										n_peg
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B, e_sdm_pegawai_0_tm C
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and b.i_peg_helpdesk = C.i_peg_nip
										and upper(n_peg) like ?
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
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan,
										c_barang_perbaikan,
										e_saran_sparepart,
										e_saran_pihakketiga,
										i_disposisi,
										n_peg
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B, e_sdm_pegawai_0_tm C
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and b.i_peg_helpdesk = C.i_peg_nip
										and upper(n_peg) like ?
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
											 "i_disposisi"			=>(string)$setuju[$j]->i_disposisi);
				}
			}
						
			return $hasilList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	//utk status A & B
	
	public function getProblemUserByStatusAB($pageNumber,$itemPerPage,$nmHelpdesk,$status)
	{
		$nm	=	$nmHelpdesk;
		$st	=	$status;
		$nmHelpdesk = strtoupper($nmHelpdesk);
		$nmHelpdesk = '%'.$nmHelpdesk.'%';
		$status = strtoupper($status);
		$status = '%'.$status.'%';
		
		try {
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
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan,
										c_barang_perbaikan,
										e_saran_sparepart,
										e_saran_pihakketiga,
										i_disposisi
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
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
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan,
										c_barang_perbaikan,
										e_saran_sparepart,
										e_saran_pihakketiga,
										i_disposisi
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
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
											 "i_disposisi"			=>(string)$setuju[$j]->i_disposisi);
				}
			}
						
			return $hasilList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	//utk status A & B by Unit
	public function getProblemUserByUnitStatusAB($pageNumber,$itemPerPage,$nmHelpdesk,$status,$unitkr)
	{
		$nm	=	$nmHelpdesk;
		$st	=	$status;
		$nmHelpdesk = strtoupper($nmHelpdesk);
		$nmHelpdesk = '%'.$nmHelpdesk.'%';
		$status = strtoupper($status);
		$status = '%'.$status.'%';
		
		try {
			$where[] = $status;
			$where[] = $unitkr;
			
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
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan,
										c_barang_perbaikan,
										e_saran_sparepart,
										e_saran_pihakketiga,
										i_disposisi
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and c_status_perbaikan like ? 
										and i_orgb = ?
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
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan,
										c_barang_perbaikan,
										e_saran_sparepart,
										e_saran_pihakketiga,
										i_disposisi
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and c_status_perbaikan like ? 
										and i_orgb = ?
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
											 "i_disposisi"			=>(string)$setuju[$j]->i_disposisi);
				}
			}
						
			return $hasilList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	
	//===================get no inventaris pc by penanggungjawab barang ===========================================================hasil review 25Juli08 ==
	public function getNoInventarisPCList($pageNumber,$itemPerPage,$nip,$nmBarang) {
	
		$nmbrg   = '%'.strtoupper($nmBarang).'%';
		$where[] = $nip;
		$where[] = $nmbrg;
		 
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasil = $db->fetchAll("SELECT A.i_barang_serah,A.d_barang_serah,
										A.i_peg_nipterima,A.i_orgb_penerima,
										A.e_keterangan,
										b.d_aset_thnanggar,
										b.c_barang,
										to_char(b.i_aset,'09999') as i_aset,
										b.d_barang_peroleh,
										C.i_komputer_macaddress,
										D.ur_sskel
										FROM e_ast_dir_0_tm A,   e_ast_dir_item_tm B, E_AST_KOMPUTER_0_TR C, E_AST_SSKEL_0_TR D
										where A.i_barang_serah=B.i_barang_serah
										and (a.i_barang_serah like '%KYN%' or a.i_barang_serah like '%HYN%')
										And D.kd_gol = substr(C.c_barang,1,1)
										And D.kd_bid = substr(C.c_barang,2,2)
										And D.kd_kel = substr(C.c_barang,4,2)
										And D.kd_skel = substr(C.c_barang,6,2)
										And D.kd_sskel = substr(C.c_barang,8,3)
										And D.kd_gol = '2'
										And D.kd_bid = '12'
										and b.d_aset_thnanggar=c.d_anggaran
										and b.c_barang =c.c_barang
										and b.i_aset = c.i_aset
										and i_peg_nipterima = ?
										and d.ur_sskel like ?
										order by d_aset_thnanggar
										", $where);
				$hasilAkhir = count($hasil);
			}else {
			    
				$xLimit=$itemPerPage;
			    $xOffset=($pageNumber-1)*$itemPerPage;
			 
				$result = $db->fetchAll("SELECT A.i_barang_serah,A.d_barang_serah,
										A.i_peg_nipterima,A.i_orgb_penerima,
										A.e_keterangan,
										b.d_aset_thnanggar,
										b.c_barang,
										to_char(b.i_aset,'09999') as i_aset,
										b.d_barang_peroleh,
										C.i_komputer_macaddress,
										D.ur_sskel
										FROM e_ast_dir_0_tm A,   e_ast_dir_item_tm B, E_AST_KOMPUTER_0_TR C, E_AST_SSKEL_0_TR D
										where A.i_barang_serah=B.i_barang_serah
										and (a.i_barang_serah like '%KYN%' or a.i_barang_serah like '%HYN%')
										And D.kd_gol = substr(C.c_barang,1,1)
										And D.kd_bid = substr(C.c_barang,2,2)
										And D.kd_kel = substr(C.c_barang,4,2)
										And D.kd_skel = substr(C.c_barang,6,2)
										And D.kd_sskel = substr(C.c_barang,8,3)
										And D.kd_gol = '2'
										And D.kd_bid = '12'
										and b.d_aset_thnanggar=c.d_anggaran
										and b.c_barang =c.c_barang
										and b.i_aset = c.i_aset
										and i_peg_nipterima = ?
										and d.ur_sskel like ?
										order by d_aset_thnanggar
										limit $xLimit offset $xOffset", $where);
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
			 
					$hasilAkhir[$j] = array("d_anggaran"       			=>(string)$result[$j]->d_aset_thnanggar,
											"c_barang"           		=>(string)$result[$j]->c_barang, 
											"i_aset"         			=>(string)$result[$j]->i_aset, 
											"d_perolehan"          		=>(string)$result[$j]->d_barang_peroleh,
											"ur_sskel"         			=>(string)$result[$j]->ur_sskel,
											"i_komputer_macaddress"    	=>(string)$result[$j]->i_komputer_macaddress); 
				}
			}					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	//
	public function getNoInventarisPCListByPeriode($pageNumber,$itemPerPage,$nip,$nmBarang,$tglAwal,$tglAkhir) {
	 
	$nmbrg   = '%'.strtoupper($nmBarang).'%';
	
	$where[] = $nip;
	$where[] = $nmbrg;
	$where[] = $tglAwal;
	$where[] = $tglAkhir;
		 
	$registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 { 
			$result = $db->fetchOne("SELECT A.i_barang_serah,A.d_barang_serah,
										A.i_peg_nipterima,A.i_orgb_penerima,
										A.e_keterangan,
										b.d_aset_thnanggar,
										b.c_barang,
										to_char(b.i_aset,'09999') as i_aset,
										b.d_barang_peroleh,
										C.i_komputer_macaddress,
										D.ur_sskel
										FROM e_ast_dir_0_tm A,   e_ast_dir_item_tm B, E_AST_KOMPUTER_0_TR C, E_AST_SSKEL_0_TR D
										where A.i_barang_serah=B.i_barang_serah
										and (a.i_barang_serah like '%KYN%' or a.i_barang_serah like '%HYN%')
										And D.kd_gol = substr(C.c_barang,1,1)
										And D.kd_bid = substr(C.c_barang,2,2)
										And D.kd_kel = substr(C.c_barang,4,2)
										And D.kd_skel = substr(C.c_barang,6,2)
										And D.kd_sskel = substr(C.c_barang,8,3)
										And D.kd_gol = '2'
										And D.kd_bid = '12'
										and b.d_aset_thnanggar=c.d_anggaran
										and b.c_barang =c.c_barang
										and b.i_aset = c.i_aset
										and i_peg_nipterima = ?
										and upper(d.ur_sskel) like ?
										and d_perolehan  between ? and ?
										order by d_aset_thnanggar
										",$where);	
										
			$hasilAkhir = count($result);
								   	    
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		  
		      $result = $db->fetchAll("SELECT A.i_barang_serah,A.d_barang_serah,
										A.i_peg_nipterima,A.i_orgb_penerima,
										A.e_keterangan,
										b.d_aset_thnanggar,
										b.c_barang,
										to_char(b.i_aset,'09999') as i_aset,
										b.d_barang_peroleh,
										C.i_komputer_macaddress,
										D.ur_sskel
										FROM e_ast_dir_0_tm A,   e_ast_dir_item_tm B, E_AST_KOMPUTER_0_TR C, E_AST_SSKEL_0_TR D
										where A.i_barang_serah=B.i_barang_serah
										and (a.i_barang_serah like '%KYN%' or a.i_barang_serah like '%HYN%')
										And D.kd_gol = substr(C.c_barang,1,1)
										And D.kd_bid = substr(C.c_barang,2,2)
										And D.kd_kel = substr(C.c_barang,4,2)
										And D.kd_skel = substr(C.c_barang,6,2)
										And D.kd_sskel = substr(C.c_barang,8,3)
										And D.kd_gol = '2'
										And D.kd_bid = '12'
										and b.d_aset_thnanggar=c.d_anggaran
										and b.c_barang =c.c_barang
										and b.i_aset = c.i_aset
										and i_peg_nipterima = ?
										and upper(d.ur_sskel) like ?
										and d_perolehan  between ? and ?
										order by d_aset_thnanggar
										limit $xLimit offset $xOffset ",$where);
							
            $jmlResult = count($result);
		 
		 
		   for ($j = 0; $j < $jmlResult; $j++) {
		 
				$hasilAkhir[$j] = array("d_anggaran"       				=>(string)$result[$j]->d_aset_thnanggar,
											"c_barang"           		=>(string)$result[$j]->c_barang, 
											"i_aset"         			=>(string)$result[$j]->i_aset, 
											"d_perolehan"          		=>(string)$result[$j]->d_barang_peroleh,
											"ur_sskel"         			=>(string)$result[$j]->ur_sskel,
											"i_komputer_macaddress"    	=>(string)$result[$j]->i_komputer_macaddress); 
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	//List Pegawai Kementrian Negara bumn
	
	public function getListPegawaiKnbumn($pageNumber,$itemPerPage,$npeg) {
		
		$nama   = '%'.strtoupper($npeg).'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[]=$nama;
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$hasilAkhir = $db->fetchOne("SELECT count(*)
										FROM e_sdm_pegawai_0_tm A
										WHERE upper(n_peg) like ?"	,$where );
			 }
			 else
			 {
			
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;		
			 
			$result = $db->fetchAll("SELECT a.i_peg_nip,a.n_peg,a.c_unit_kerja
									FROM e_sdm_pegawai_0_tm A
									WHERE upper(n_peg) like ?
									order by n_peg 
									limit $xLimit offset $xOffset",$where);
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$n_jabatan 			= $db->fetchCol('select n_jabatan  
														from  e_sdm_jabatan_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nip);	
														
				$hasilAkhir[$j] = array("i_peg_nip"         	=>(string)$result[$j]->i_peg_nip,
										"n_peg"           	 	=>(string)$result[$j]->n_peg,
										"n_jabatan"          	=>$n_jabatan[0],
										"i_orgb"            	=>(string)$result[$j]->c_unit_kerja
										);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	
	
	//Daftar Kantor bumn ===============
	
	public function getListDataBumn($pageNumber,$itemPerPage,$nbumn) {
		
		$nama   = '%'.strtoupper($nbumn).'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[]=$nama;
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$hasilAkhir = $db->fetchOne("SELECT count(*)
										FROM   a_bumn_0_0_tr  A
										WHERE upper(n_bumn) like ?"	,$where );
			 }
			 else
			 {
			
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;		
			 
			$result = $db->fetchAll("SELECT a.i_bumn,a.n_bumn
									FROM   a_bumn_0_0_tr  A
									WHERE upper(n_bumn) like ?
									order by n_bumn 
									limit $xLimit offset $xOffset",$where);
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
														
				$hasilAkhir[$j] = array("i_bumn"         		=>(string)$result[$j]->i_bumn,
										"n_bumn"           	 	=>(string)$result[$j]->n_bumn
										);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	
	
	//list helpdesk
	public function getListPegawaiPic($pageNumber,$itemPerPage,$npeg) {
		
		$nama   = '%'.strtoupper($npeg).'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[]=$nama;
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$hasilAkhir = $db->fetchOne("SELECT count(*)
										FROM e_sdm_pegawai_0_tm A
										WHERE upper(n_peg) like ?
										and (c_unit_kerja ='DP6511' or c_unit_kerja ='DP6512')"	,$where );
			 }
			 else
			 {
			
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;		
			 
			$result = $db->fetchAll("SELECT a.i_peg_nip,a.n_peg,a.c_unit_kerja
									FROM e_sdm_pegawai_0_tm A
									WHERE upper(n_peg) like ?
									and (c_unit_kerja ='DP6511' or c_unit_kerja ='DP6512')
									order by n_peg 
									limit $xLimit offset $xOffset",$where);
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$n_jabatan 			= $db->fetchCol('select n_jabatan  
														from  e_sdm_jabatan_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nip);	
														
				$hasilAkhir[$j] = array("i_peg_nip"         	=>(string)$result[$j]->i_peg_nip,
										"n_peg"           	 	=>(string)$result[$j]->n_peg,
										"n_jabatan"          	=>$n_jabatan[0],
										"i_orgb"            	=>(string)$result[$j]->c_unit_kerja
										);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	
	// Ina : Awa : 19-11-2008 : Fungsi Untuk List Diagnosa Pengerjaan
	public function getDiagnosaPengerjaanListByNip($pageNumber,$itemPerPage,$nip)
	{
		$nip2 = strtoupper($nip);	    
		try {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$where[] = $nip2;
				$where[] = $nip2;
				$where[] = $nip2;
				
				$hasil = $db->fetchAll("Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										c_problem_ctgr,
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 
										and ((c_barang_perbaikan  = 'K' and i_barang_penerimaan is not null and c_setuju_statussi is null)
										      or  
										      (c_barang_perbaikan  = 'D' and c_setuju_statussi is null)
											  or  
										      (c_barang_perbaikan  = 'K' and c_terima_sparepart = 'Y')
											  or  
										      (c_barang_perbaikan  = 'D' and c_terima_sparepart = 'Y')
										    )  										
										and upper(i_peg_helpdesk) =?
										and c_status_lengkap ='Y'
										and (c_status_perbaikan <> 'Y' or c_status_perbaikan is null)
										UNION
										Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										c_problem_ctgr,
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  	a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 
										and c_barang_perbaikan != 'K'
										and c_barang_perbaikan != 'D'
										and upper(i_peg_helpdesk) =?
										and c_status_lengkap ='Y'
										and (c_status_perbaikan <> 'Y' or c_status_perbaikan is null)
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
										c_status_perbaikan
										FROM e_ast_problemti_0_tm a
										where  upper(i_peg_nip) =?
										and c_status_lengkap ='Y'
										and (c_status_perbaikan <> 'Y' or c_status_perbaikan is null)
										order by i_barang_ajuanbaik 	
										",$where)	;
				$hasilList = count($hasil);
			}
			else 
			{
				$where[] = $nip2;
				$where[] = $nip2;
				$where[] = $nip2;
				
		
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
										c_problem_ctgr,
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan, '' as n_peg_bumn, i_orgb, c.ur_sskel, e_barang
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B, e_ast_sskel_0_tr C
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and ((c_barang_perbaikan  = 'K' and i_barang_penerimaan is not null and c_setuju_statussi is null)
										      or  
											(c_barang_perbaikan  = 'D' and c_setuju_statussi is null)
											or  
											(c_barang_perbaikan  = 'K' and c_terima_sparepart = 'Y')
											or  
											(c_barang_perbaikan  = 'D' and c_terima_sparepart = 'Y')
										)  										
										and upper(i_peg_helpdesk) = ?
										and c_status_lengkap ='Y'
										and (c_status_perbaikan <> 'Y' or c_status_perbaikan is null)
                                        and  substr(a.c_barang,1,1) = c.kd_gol
										and substr(a.c_barang,2,2) = c.kd_bid 
										and substr(a.c_barang,4,2) = c.kd_kel
										and substr(a.c_barang,6,2) = c.kd_skel
										and substr(a.c_barang,8,3) = c.kd_sskel
										UNION
										Select 
										a.i_barang_ajuanbaik,
										a.d_barang_ajuanbaik,
										a.d_anggaran,
										a.c_barang,
										to_char(A.i_aset,'09999') as i_aset,
										a.i_peg_nip as i_peg_nippemohon,
										i_peg_helpdesk,
										a.e_barang_perbaikan,
										c_problem_ctgr,
										d_barang_ajuanbaik as d_problem_awal,
										c_status_perbaikan, '' as n_peg_bumn, i_orgb, ur_sskel, e_barang
										from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B, e_ast_sskel_0_tr C
										where  a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 	
										and c_barang_perbaikan != 'K'
										and c_barang_perbaikan != 'D'
										and upper(i_peg_helpdesk) = ?
										and c_status_lengkap ='Y'
										and (c_status_perbaikan <> 'Y' or c_status_perbaikan is null)
                                        and  substr(a.c_barang,1,1) = c.kd_gol
										and substr(a.c_barang,2,2) = c.kd_bid 
										and substr(a.c_barang,4,2) = c.kd_kel
										and substr(a.c_barang,6,2) = c.kd_skel
										and substr(a.c_barang,8,3) = c.kd_sskel
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
										c_status_perbaikan, n_peg_bumn , i_orgb, null as ur_sskel, null as e_barang
										FROM e_ast_problemti_0_tm a
										where upper(i_peg_nip) = ?
										and c_status_lengkap ='Y'
										and (c_status_perbaikan <> 'Y' or c_status_perbaikan is null)
										order by d_barang_ajuanbaik										
										limit $xLimit offset $xOffset",$where)	;
				
								
				$jmlSetuju = count($setuju);
				
				for($j=0; $j<$jmlSetuju; $j++)
				{
					$n_problem_ctgr		= $db->fetchCol('select n_problem_ctgr  
														from  e_ast_kategori_problemti_tm 
														where c_problem_ctgr = ? ',$setuju[$j]->c_problem_ctgr);	
					// Awal : Pencari Nama Pemohon dan org Pemohon
					$n_peg_pemohon 	= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$setuju[$j]->i_peg_nippemohon);	
														
					if ($n_peg_pemohon==null || $n_peg_pemohon == '')
					{
					     $n_peg_pemohon 	=  $db->fetchCol('select n_peg_bumn  
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
											 "ur_sskel"		=>(string)$setuju[$j]->ur_sskel,
											 "e_barang"		=>(string)$setuju[$j]->e_barang,
											 "n_problem_ctgr"		=>$n_problem_ctgr[0],
											 "n_peg"				=>$n_peg[0],
											 "n_peg_pemohon"		=>$n_peg_pemohon[0],
											 "n_orgb"				=>$n_orgb[0],											 
											 "c_status_perbaikan"	=>(string)$setuju[$j]->c_status_perbaikan
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
	
	// Ina : Awal : 27-12-2008 
	public function getPengajuanProblemUserByNipList($pageNumber,$itemPerPage,$nip)
	{
		//echo "services getPengajuanProblemUserByNipList";
		try {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
	    $where[]=$nip;
		
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				/*$hasilResult = $db->fetchOne("Select count(*) from E_AST_AJUANBAIKTI_0_TM where  c_setuju_statustu is null
												And i_orgb = ? ",$where);*/
												
				$hasilResult = $db->fetchOne("Select count(*) from E_AST_AJUANBAIKTI_0_TM where  c_setuju_statussi is null
												And i_peg_nip = ? and c_status_lengkap is null ",$where);
												
			}
		
			else 
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				$result = $db->fetchAll("Select a.i_barang_ajuanbaik,d_barang_ajuanbaik,
										d_anggaran, c_barang, i_aset, i_peg_nip, d_perolehan,
										e_barang_perbaikan,e_barang, d_problem_awal, ur_sskel 
										from 
										E_AST_AJUANBAIKTI_0_TM a, e_ast_ajuanbaikti_item_tm  b, e_ast_sskel_0_tr c
										where  a.i_barang_ajuanbaik=b.i_barang_ajuanbaik
										and  substr(c_barang,1,1) = c.kd_gol
										and substr(c_barang,2,2) = c.kd_bid 
										and substr(a.c_barang,4,2) = c.kd_kel
										and substr(a.c_barang,6,2) = c.kd_skel
										and substr(a.c_barang,8,3) = c.kd_sskel
										and c_setuju_statussi is null
										and  i_peg_nip = ? 
										and c_status_lengkap is null 
										order by a.i_barang_ajuanbaik
										limit $xLimit offset $xOffset",$where );
				
				$jmlResult = count($result);
				for($j=0; $j<$jmlResult; $j++)
				{
					$hasilResult[$j] = array("i_barang_ajuanbaik"	=>(string)$result[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" 		=>(string)$result[$j]->d_barang_ajuanbaik,
											 "d_anggaran"			=>(string)$result[$j]->d_anggaran,
											 "c_barang"			=>(string)$result[$j]->c_barang,
											 "i_aset"			=>(string)$result[$j]->i_aset,
											 "i_peg_nip"			=>(string)$result[$j]->i_peg_nip,
											 "d_perolehan"			=>(string)$result[$j]->d_perolehan,
											 "e_barang_perbaikan"		=>(string)$result[$j]->e_barang_perbaikan,
											 "e_barang"			=>(string)$result[$j]->e_barang,
											 "nama_barang"			=>(string)$result[$j]->ur_sskel,
											 "d_problem_awal"		=>(string)$result[$j]->d_problem_awal
											 );
				}
			}
			
			return $hasilResult;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	// Ina : Akhir : 19-11-2008
	
	// Ina: Awal : 02-12-2008
	public function getNamaBarangTI($pageNumber,$itemPerPage) {
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
	// Ina : Akhir : 02-12-2008
}
?>