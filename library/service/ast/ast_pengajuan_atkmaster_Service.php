<?php
class ast_pengajuan_atkmaster_service {
   
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
	 
	public function insertAjuanBeliAtkM(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("i_atk_ajuan"         		=>$data['nopeng'],
	                           "d_atk_ajuan"    	    	=>date("Y-m-d"),
						       "i_orgb"                     =>$data['Unitkr'],
						       "c_atk_setuju"  		        =>$data['status'],
						       "e_keterangan"   	    	=>$data['ket'],
							   "i_rab"   	    			=>$data['rab'],
							   "i_mak"   	    			=>$data['mak'],
							   "i_rab_norutdlmrangka"   	=>$data['rabkg'],
							   "i_rab_norut"   	    		=>$data['nrabkg'],
						       "i_entry"       		        =>$data['nuser'],
						       "d_entry"       		        =>date("Y-m-d"));
	    //"d_atk_ajuan"    	    	=>$data['tglPengajuan'],
							   
	     $db->insert('e_ast_ajuanbeli_atk_tm',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	 
     
	 
	public function updateAjuanBeliAtkM($nopeng,$nuser) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("c_atk_setuju"  		=>'B',
						       "e_keterangan"   		=>' ',
						       "i_entry"       		    =>$nuser,
						       "d_entry"       		    =>date("Y-m-d"));
	     
		 $where[] = "i_atk_ajuan = '". $nopeng ."'";
	     $db->update('e_ast_ajuanbeli_atk_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
   
	 
	public function deletAjuanBeliAtkM($nopeng) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $where[] = "i_atk_ajuan = '". $nopeng ."'";
		 $db->delete('e_ast_ajuanbeli_atk_tm', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	public function updateAjuanBeliAtkM2($unitkr) {
	  
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("c_atk_setuju"  		=>'B',
						       "e_keterangan"   		=>' ',
						       "i_entry"       		    =>'ast',
						       "d_entry"       		    =>date("Y-m-d"));
	     
		 $where[] = "i_orgb = '". $unitkr ."'";
	     $db->update('e_ast_ajuanbeli_atk_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
     
public function queryAjuanBeliAtkM($unitkr) {
       $status='A';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_atk_ajuan,d_atk_ajuan FROM e_ast_ajuanbeli_atk_tm where i_orgb=? and c_atk_setuju=?',$where); 
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_atk_ajuan"           =>(string)$result[$j]->i_atk_ajuan,
								   "d_atk_ajuan"           =>(string)$result[$j]->d_atk_ajuan);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	 
	
	//
	//=====================================utk page======================================================================14 des 07========
	public function getAjuanBeliAtkMListAll($pageNumber,$itemPerPage,$unitkr) {
		$status='A';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $unitkr;
			$where[] = $status;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_ajuanbeli_atk_tm where i_orgb=? and c_atk_setuju=? ",$where);
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				
				$result = $db->fetchAll("SELECT *
										FROM e_ast_ajuanbeli_atk_tm where i_orgb=? and c_atk_setuju=?
										order by i_atk_ajuan
										limit $xLimit offset $xOffset",$where); 
			 
				$jmlResult = count($result);
			 	for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuan"           =>(string)$result[$j]->i_atk_ajuan,
											"d_atk_ajuan"           =>(string)$result[$j]->d_atk_ajuan,
											"i_rab"           		=>(string)$result[$j]->i_rab,
											"i_mak"           		=>(string)$result[$j]->i_mak,
											"i_rab_norutdlmrangka"  =>(string)$result[$j]->i_rab_norutdlmrangka,
											"i_rab_norut"           =>(string)$result[$j]->i_rab_norut);
											
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada';
	   }
	}
	
	public function getAjuanBeliAtkListAll($pageNumber, $itemPerPage) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     //echo "TEST CONN ".$db->getConnection()."<br>";
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_ajuanbeli_atk_tm");
		 }
		 else
		 {
			 $xLimit=$itemPerPage;
	  		 $xOffset=($pageNumber-1)*$itemPerPage;		
		     
			 $result = $db->fetchAll("select * 
									  FROM e_ast_ajuanbeli_atk_tm 
									  order by i_atk_ajuan
									  limit $xLimit offset $xOffset");
			 
	         $jmlResult = count($result);
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_atk_ajuan"           =>(string)$result[$j]->i_atk_ajuan,
										"d_atk_ajuan"           =>(string)$result[$j]->d_atk_ajuan);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	//=================================Persetujuan ATK ==============================================================================
	public function querySetujuBeliAtkMList($pageNumber,$itemPerPage) {
		$status='B';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $status;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_ajuanbeli_atk_tm where c_atk_setuju=? ",$where);
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				
				$result = $db->fetchAll("SELECT *
										FROM e_ast_ajuanbeli_atk_tm where c_atk_setuju=?
										order by i_atk_ajuan
										limit $xLimit offset $xOffset",$where); 
			 
				$jmlResult = count($result);
			 	for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuan"           =>(string)$result[$j]->i_atk_ajuan,
											"d_atk_ajuan"           =>(string)$result[$j]->d_atk_ajuan,
											"i_orgb" 				=>(string)$result[$j]->i_orgb);
											
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada';
	   }
	}
	public function querySetujuBeliAtkMList2() {
		$status='B';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $status;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$result = $db->fetchAll('SELECT i_atk_ajuan,d_atk_ajuan,i_orgb FROM e_ast_ajuanbeli_atk_tm where c_atk_setuju=?',$where); 
			$jmlResult = count($result);		 
			if($jmlResult > 0){
				for ($j = 0; $j < $jmlResult; $j++) {			
				$hasilAkhir[$j] = array("i_atk_ajuan"	=>(string)$result[$j]->i_atk_ajuan,
						   "d_atk_ajuan" 	=>(string)$result[$j]->d_atk_ajuan,
						   "i_orgb" 	=>(string)$result[$j]->i_orgb
						   );
				}
			}		 
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}
	//============================rekap setuju atk ====================================================================================
	public function rekapSetujuBeliAtkMList($pageNumber,$itemPerPage,$Stat) {
		$status=$Stat;
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $status;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_ajuanbeli_atk_tm where c_atk_setuju=? ",$where);
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				
				$result = $db->fetchAll("SELECT i_atk_ajuan,d_atk_ajuan,a.i_orgb,n_orgb ,
										i_rab,i_mak,i_rab_norutdlmrangka,i_rab_norut
										FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b where c_atk_setuju=? and a.i_orgb=b.i_orgb
										order by i_atk_ajuan
										limit $xLimit offset $xOffset",$where); 
			 
				$jmlResult = count($result);
			 	for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuan"           =>(string)$result[$j]->i_atk_ajuan,
											"d_atk_ajuan"           =>(string)$result[$j]->d_atk_ajuan,
											"i_orgb" 				=>(string)$result[$j]->i_orgb,
											"n_orgb" 				=>(string)$result[$j]->n_orgb,
											"i_rab" 				=>(string)$result[$j]->i_rab,
											"i_mak" 				=>(string)$result[$j]->i_mak,
											"i_rab_norutdlmrangka" 	=>(string)$result[$j]->i_rab_norutdlmrangka,
											"i_rab_norut" 			=>(string)$result[$j]->i_rab_norut);
											
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada';
	   }
	}
	public function rekapSetujuBeliAtkMList2($Stat) {
	
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		if ($Stat =='Y' || $Stat =='T'){
			$where[] = $Stat;
			$query= "SELECT i_atk_ajuan,d_atk_ajuan,a.i_orgb,n_orgb ".
				" FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b where c_atk_setuju=? and a.i_orgb=b.i_orgb order by i_atk_ajuan";
			
		}else{
			$where[] = 'A';
			$where[] = 'B';		
			$query= "SELECT i_atk_ajuan,d_atk_ajuan,a.i_orgb,n_orgb ".
				" FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b where ( c_atk_setuju=? or c_atk_setuju=? ) and a.i_orgb=b.i_orgb order by i_atk_ajuan ";	
				
		}
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$result = $db->fetchAll($query,$where); 
			$jmlResult = count($result);		 
			if($jmlResult > 0){
				for ($j = 0; $j < $jmlResult; $j++) {			
				$hasilAkhir[$j] = array("i_atk_ajuan"	=>(string)$result[$j]->i_atk_ajuan,
									   "d_atk_ajuan" 	=>(string)$result[$j]->d_atk_ajuan,
									   "i_orgb" 		=>(string)$result[$j]->i_orgb,
									   "n_orgb" 		=>(string)$result[$j]->n_orgb
									   );
				}
			}		 
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}
	
	public function rekapSetujuBeliAtkMBYPeriodList($pageNumber,$itemPerPage,$Stat,$tglAwal,$tglAkhir) {
		$status=$Stat;
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $status;
			$where[] = $tglAwal;
			$where[] = $tglAkhir;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_ajuanbeli_atk_tm where c_atk_setuju=? and (d_atk_ajuan between ? and ?) ",$where);
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				
				$result = $db->fetchAll("SELECT i_atk_ajuan,d_atk_ajuan,a.i_orgb, n_orgb,
											i_rab,i_mak,i_rab_norutdlmrangka,i_rab_norut
											FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b where c_atk_setuju=? and (d_atk_ajuan between ? and ?) and a.i_orgb=b.i_orgb 
											order by i_atk_ajuan
											limit $xLimit offset $xOffset",$where); 
			 
			 
				$jmlResult = count($result);
			 	for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuan"           =>(string)$result[$j]->i_atk_ajuan,
											"d_atk_ajuan"           =>(string)$result[$j]->d_atk_ajuan,
											"i_orgb" 				=>(string)$result[$j]->i_orgb,
											"n_orgb" 				=>(string)$result[$j]->n_orgb,
											"i_rab" 				=>(string)$result[$j]->i_rab,
											"i_mak" 				=>(string)$result[$j]->i_mak,
											"i_rab_norutdlmrangka" 	=>(string)$result[$j]->i_rab_norutdlmrangka,
											"i_rab_norut" 			=>(string)$result[$j]->i_rab_norut);
											
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada';
	   }
	}
	
	public function rekapSetujuBeliAtkMBYPeriodList2($Stat,$tglAwal,$tglAkhir) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		if ($Stat =='Y' || $Stat =='T'){
			$where[] = $Stat;
			$where[] = $tglAwal;
			$where[] = $tglAkhir;
			$query= "SELECT i_atk_ajuan,d_atk_ajuan,a.i_orgb,n_orgb  FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b".
				" where c_atk_setuju=? and (d_atk_ajuan between ? and ?) and a.i_orgb=b.i_orgb order by i_atk_ajuan	";
			
		}else{
			$where[] = 'A';
			$where[] = 'B';			
			$where[] = $tglAwal;
			$where[] = $tglAkhir;
			$query= "SELECT i_atk_ajuan,d_atk_ajuan,a.i_orgb, n_orgb FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b".
				" where (c_atk_setuju=? or c_atk_setuju=?) and (d_atk_ajuan between ? and ?) and a.i_orgb=b.i_orgb order by i_atk_ajuan ";	
		}
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$result = $db->fetchAll($query,$where); 
			$jmlResult = count($result);		 
			if($jmlResult > 0){
				for ($j = 0; $j < $jmlResult; $j++) {			
				$hasilAkhir[$j] = array("i_atk_ajuan"	=>(string)$result[$j]->i_atk_ajuan,
									    "d_atk_ajuan" 	=>(string)$result[$j]->d_atk_ajuan,
									    "i_orgb" 	=>(string)$result[$j]->i_orgb,
										"n_orgb" 	=>(string)$result[$j]->n_orgb
									    );
				}
			}		 
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}
	
	public function getAtkDetailList($nopeng) {	   
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$query="SELECT b.c_atk_ctgr,b.c_atk,q_atk_beli,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stock ,q_atk_stockmin,q_atk_setujubeli".
			       " FROM e_ast_barang_atk_tr a, e_ast_ajuanbeli_itematk_tm b where i_atk_ajuan = ? and a.c_atk = b.c_atk";
			//echo "Querynya  : ". $query;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$result = $db->fetchAll($query,$nopeng);
			$jmlResult = count($result);		
			for ($j = 0; $j < $jmlResult; $j++) {		
				$hasilAkhir[$j] = array("c_atk_ctgr"	=>(string)$result[$j]->c_atk_ctgr,
					"c_atk"                  =>(string)$result[$j]->c_atk,
					"q_atk_beli"             =>(string)$result[$j]->q_atk_beli,
					"n_atk"                  =>(string)$result[$j]->n_atk,
					"n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
					"n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
					"n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
					"q_atk_stock"            =>(string)$result[$j]->q_atk_stock, 
					"q_atk_stockmin"         =>(string)$result[$j]->q_atk_stockmin, 
					"q_atk_setujubeli"       =>(string)$result[$j]->q_atk_setujubeli
					); 
				}					 
			return $hasilAkhir;
		} 
		catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}
	
	//==============================================================================================================================
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
public function InsertNourutmax(array $data) {
	 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
	     $nomor_max_prm = array("i_orgb"         		  =>$data['unitkr'],
	                           "c_modul"    	    	  =>$data['modl'],
						       "d_modul_tahun"            =>$data['tahun'],
						       "q_modul_nomormax"  		  =>$data['nomor']);
						      
	   
	     $db->insert('e_modul_nomor_max_tr',$nomor_max_prm);
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
		
	     $db->update('e_modul_nomor_max_tr',$nomor_max_prm, $where);
		 $db->commit();
	     return 'sukses update mo max<br>';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>'; 
	     return 'gagal update nomax <br>';
	   }
	}
public function getRefAtkList($pageNumber,$itemPerPage,$katBarang,$namaBarang,$nopeng) {
		$namaBarang = strtoupper($namaBarang);
		$nbrg = '%'.$namaBarang.'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $katBarang;
			$where[] = $nbrg;
			$where[] = $nopeng;
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			 {
				//$hasilAkhir = $db->fetchOne("select count(*) from e_ast_barang_atk_tr 
				//							 where c_atk_ctgr=? and n_atk like ? ",$where);
											 
				$result = $db->fetchAll("SELECT a.c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stock 
										FROM e_ast_barang_atk_tr a where a.c_atk_ctgr=? and n_atk like ?
										and not exists(select d.i_atk_ajuan,c.c_atk,c.c_atk_ctgr 
										from     e_ast_ajuanbeli_itematk_tm   c,    e_ast_ajuanbeli_atk_tm   d
										where c.i_atk_ajuan = d.i_atk_ajuan and a.c_atk = c.c_atk 
										and a.c_atk_ctgr = c.c_atk_ctgr and d.i_atk_ajuan=?)
										ORDER BY c_atk",$where); 
			 
			 
				$jmlResult  = count($result);
				$hasilAkhir = $jmlResult;
			 }
			else
			 {
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT a.c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stock 
										FROM e_ast_barang_atk_tr a where a.c_atk_ctgr=? and n_atk like ?
										and not exists(select d.i_atk_ajuan,c.c_atk,c.c_atk_ctgr 
										from   e_ast_ajuanbeli_itematk_tm  c,  e_ast_ajuanbeli_atk_tm  d
										where c.i_atk_ajuan = d.i_atk_ajuan and a.c_atk = c.c_atk 
										and a.c_atk_ctgr = c.c_atk_ctgr and d.i_atk_ajuan=?)
										ORDER BY c_atk
									limit $xLimit offset $xOffset",$where); 
			 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("c_atk"         =>(string)$result[$j]->c_atk,
								   "n_atk"           	=>(string)$result[$j]->n_atk,
								   "n_atk_satuan"    	=>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"     	=>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"      	=>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stock"      	=>(string)$result[$j]->q_atk_stock);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
public function getRefAtkListAll($katBarang) {
        
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stock FROM e_ast_barang_atk_tr where c_atk_ctgr=? ORDER BY c_atk',$katBarang);
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
public function getRefKatListAll() {
       
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT c_atk_ctgr,n_atk_ctgr FROM e_ast_kategori_atk_tr ORDER BY c_atk_ctgr');
	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_atk_ctgr"           =>(string)$result[$j]->c_atk_ctgr,
								   "n_atk_ctgr"           =>(string)$result[$j]->n_atk_ctgr);
								  
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
public function querySetujuBeliAtkM($unitkr) {
       $status='B';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_atk_ajuan,d_atk_ajuan FROM e_ast_ajuanbeli_atk_tm where i_orgb=? and c_atk_setuju=?',$where); 
         $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_atk_ajuan"           =>(string)$result[$j]->i_atk_ajuan,
								   "d_atk_ajuan"           =>(string)$result[$j]->d_atk_ajuan);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
public function updateSetujuAtkM($nopeng,$stat,$nuser) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("c_atk_setuju"  		=>$stat,
						       "e_keterangan"   	=>' ',
						       "i_entry"       		=>$nuser,
						       "d_entry"       		=>date("Y-m-d"));
	     
		 $where[] = "i_atk_ajuan = '". $nopeng ."'";
	     $db->update('e_ast_ajuanbeli_atk_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function updateSetujuAtkM3($nopeng,$nuser) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("c_atk_setuju"  		=>'Y',
						       "e_keterangan"   	=>' ',
						       "i_entry"       		=>$nuser,
						       "d_entry"       		=>date("Y-m-d"));
	     
		 $where[] = "i_atk_ajuan = '". $nopeng ."'";
	     $db->update('e_ast_ajuanbeli_atk_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
public function updateSetujuAtkM2($nopeng) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("c_atk_setuju"  		=>'Y',
						       "e_keterangan"   	=>' ',
						       "i_entry"       		=>$nuser,
						       "d_entry"       		=>date("Y-m-d"));
	     
		 $where[] = "i_atk_ajuan = '". $nopeng ."'";
	     $db->update('e_ast_ajuanbeli_atk_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function queryDaftarAtkM($pageNumber,$itemPerPage,$unitkr) {
		$status='Y';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			//$where[] = $unitkr;
			$where[] = $status;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_ajuanbeli_atk_tm where  c_atk_setuju=? ",$where);
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				
				$result = $db->fetchAll("SELECT a.i_orgb, b.n_orgb,i_atk_ajuan,d_atk_ajuan
										FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b where  c_atk_setuju=?
										and a.i_orgb=b.i_orgb
										order by i_atk_ajuan
										limit $xLimit offset $xOffset",$where); 
			 
			 
				$jmlResult = count($result);
			 	for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuan"           =>(string)$result[$j]->i_atk_ajuan,
											"d_atk_ajuan"           =>(string)$result[$j]->d_atk_ajuan,
											"i_orgb"           		=>(string)$result[$j]->i_orgb,
											"n_orgb"           		=>(string)$result[$j]->n_orgb);
											
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada';
	   }
	}
	public function queryDaftarAtkM3($pageNumber,$itemPerPage,$unitkr) {
		$status='Y';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			//$where[] = $unitkr;
			$where[] = $status;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_ajuanbeli_atk_tm where  c_atk_setuju=? ",$where);
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				
				$result = $db->fetchAll("SELECT *
										FROM e_ast_ajuanbeli_atk_tm where  c_atk_setuju=?
										order by i_atk_ajuan
										limit $xLimit offset $xOffset",$where); 
			 
				$jmlResult = count($result);
			 	for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuan"           =>(string)$result[$j]->i_atk_ajuan,
											"d_atk_ajuan"           =>(string)$result[$j]->d_atk_ajuan);
											
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada';
	   }
	}
public function queryDaftarAtkM2($unitkr) {
       $status='Y';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_atk_ajuan,d_atk_ajuan FROM e_ast_ajuanbeli_atk_tm where  c_atk_setuju=?',$where); 
         $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_atk_ajuan"           =>(string)$result[$j]->i_atk_ajuan,
								   "d_atk_ajuan"           =>(string)$result[$j]->d_atk_ajuan);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function queryPenerimaanAtkM($unitkr) {
       $status='A';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_atk_terima,d_atk_terima,i_atk_kwtbeli FROM e_ast_terima_atk_tm where i_orgb=? and c_atk_setuju=?',$where); 
         $jmlResult = count($result);
		  if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_atk_terima"           =>(string)$result[$j]->i_atk_terima,
								   "i_atk_kwtbeli"          =>(string)$result[$j]->i_atk_kwtbeli,
								   "d_atk_terima"           =>(string)$result[$j]->d_atk_terima);
								  
								  
							       
		
		 }
        }
        
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
    /* ************
	 * fungsi untuk memasukan data PENERIMAAN LANGSUNG ATK  ke tabel 'e_ast_terima_atk_tm'
	 ***************************/
	 
	public function insertTerimaAtkM(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("i_atk_terima"      =>$data['nopeng'],
								"d_atk_terima"     =>$data['tglPenerimaan'],
								"i_orgb"           =>$data['unitkr'],
								"i_atk_ajuan"      =>$data['noAjuan'],
								"i_atk_kwtbeli"    =>$data['noKwitansi'],
								"d_atk_beli"       =>$data['tglPembelian'],
								"c_atk_setuju"     =>'A',
						        "i_entry"       		        =>$data['nuser'],
						        "d_entry"       		        =>date("Y-m-d"));
	    
	     $db->insert('e_ast_terima_atk_tm',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function deletPenerimaanAtkM($nopeng) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $where[] = "i_atk_terima = '". $nopeng ."'";
		 $db->delete('e_ast_terima_atk_tm', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function updatePenerimaanAtkM($nopeng) {
	  
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("c_atk_setuju"  		=>'B',
						       "i_entry"       		    =>'ast',
						       "d_entry"       		    =>date("Y-m-d"));
	     
		 $where[] = "i_atk_terima = '". $nopeng ."'";
	     $db->update('e_ast_terima_atk_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function updateStatusVerAtkM($nopeng) {
	  
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("c_atk_setuju"  		=>'Y',
						       "i_entry"       		    =>'ast',
						       "d_entry"       		    =>date("Y-m-d"));
	     
		 $where[] = "i_atk_terima = '". $nopeng ."'";
	     $db->update('e_ast_terima_atk_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
 //  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++	
 //   service untuk modul penerimaan atk tidak langsung
 //  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++	
	public function queryPenerimaanTlAtkM($unitkr) {
       $status='Y';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT distinct a.i_atk_ajuan,a.d_atk_ajuAn from e_ast_ajuanbeli_atk_tm a,e_ast_ajuanbeli_itematk_tm b
								where a.i_orgb = ? and  a.c_atk_setuju = ? and  a.i_atk_ajuan = b.i_atk_ajuan 
								and not exists (select c.i_atk_ajuan from e_ast_terima_atk_tm c,e_ast_terima_itematk_tm d
								where   c.i_atk_terima = d.i_atk_terima and a.i_atk_ajuan = c.i_atk_ajuan 
								and b.c_atk= d.c_atk)
								ORDER BY a.i_atk_ajuan',$where);
		 
         $jmlResult = count($result);
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_atk_ajuan"           =>(string)$result[$j]->i_atk_ajuan,
								   "d_atk_ajuan"           =>(string)$result[$j]->d_atk_ajuan);
								  
								  
							       
		
		 }
        }
        
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	 /* ************
	 * servis untuk Permintaan atk (stock Tersedia)
	 ***************************/
	 public function queryAjuanMintaAtkM($unitkr) {
       $status='A';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_atk_ajuanminta,d_atk_ajuanminta FROM e_ast_minta_atk_tm where i_orgb=? and c_atk_setujutu=?',$where); 
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_atk_ajuanminta"           =>(string)$result[$j]->i_atk_ajuanminta,
								   "d_atk_ajuanminta"           =>(string)$result[$j]->d_atk_ajuanminta);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	public function insertAjuanMIntaAtkM(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("i_atk_ajuanminta"         		=>$data['nopeng'],
	                           "d_atk_ajuanminta"    	    	=>date("Y-m-d"),
						       "i_orgb"                         =>$data['unitkr'],
						       "c_atk_setujutu" 		        =>$data['status'],
						       "e_atk_setujutu"   	         	=>$data['kettu'],
							   "c_atk_setujuplkp" 		        =>$data['statusplkp'],
						       "e_atk_setujuplkp"   	    	=>$data['ketplkp'],
						       "i_entry"       		            =>$data['nuser'],
						       "d_entry"       		           =>date("Y-m-d"));
	    
	     $db->insert('e_ast_minta_atk_tm',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function deletAjuanMintaAtkM($nopeng) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $where[] = "i_atk_ajuanminta = '". $nopeng ."'";
		 $db->delete('e_ast_minta_atk_tm', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	//  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++	
	public function queryVerifikasiAtkM($unitkr) {
       $status='B';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_atk_terima,d_atk_terima FROM e_ast_terima_atk_tm 
						where i_orgb=? and c_atk_setuju=? ORDER BY i_atk_terima',$where);
		 
         $jmlResult = count($result);
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_atk_terima"           =>(string)$result[$j]->i_atk_terima,
								   "d_atk_terima"           =>(string)$result[$j]->d_atk_terima);
								  
								  
							       
		
		 }
        }
        
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	//  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++	
	public function queryMonitoringAtkM($unitkr) {
       $status='Y';// utk sementara bisa tampil pake A, sebenarna : status Y
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_atk_kwtbeli,d_atk_beli FROM e_ast_terima_atk_tm 
						where i_orgb=? and c_atk_setuju=? ORDER BY i_atk_terima',$where);
		 
         $jmlResult = count($result);
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_atk_kwtbeli"           =>(string)$result[$j]->i_atk_kwtbeli,
								   "d_atk_beli"           =>(string)$result[$j]->d_atk_beli);
								  
		
		}
        }
        
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
 
public function querySetujuTuMintaAtkM() {
       $status='B'; 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     //$where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT a.i_orgb, b.n_orgb, i_atk_ajuanminta,d_atk_ajuanminta 
									FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
									where a.i_orgb=b.i_orgb and c_atk_setujutu=? ORDER BY a.i_orgb' ,$where); 
									
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb"           			=>(string)$result[$j]->i_orgb,
								   "n_orgb"           			=>(string)$result[$j]->n_orgb,
								   "i_atk_ajuanminta"           =>(string)$result[$j]->i_atk_ajuanminta,
								   "d_atk_ajuanminta"           =>(string)$result[$j]->d_atk_ajuanminta);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	public function updateSetujuTuMintaAtkM($noajuan,$setuju) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->beginTransaction();
	     $atk_mast_prm = array("c_atk_setujutu"  		=>$setuju,
						       "e_atk_setujutu"   		=>' ',
						       "i_entry"       		    =>$data['nuser'],
						       "d_entry"       		    =>date("Y-m-d"));
	     
		 $where[] = "i_atk_ajuanminta = '". $noajuan ."'";
	     $db->update('e_ast_minta_atk_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
		 		 
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	
	}
	
	//
	//19 okt 2007 ******************Persetujuan Bag Perlengkapan Atk*********/
	 public function querySetujuMintaAtkM() {
       $status='Y';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     //$where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT a.i_orgb, b.n_orgb, i_atk_ajuanminta,d_atk_ajuanminta 
									FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
									where a.i_orgb=b.i_orgb and c_atk_setujutu=? and c_atk_setujuplkp is null
									ORDER BY a.i_orgb' ,$where); 
									
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb"           			=>(string)$result[$j]->i_orgb,
								   "n_orgb"           			=>(string)$result[$j]->n_orgb,
								   "i_atk_ajuanminta"           =>(string)$result[$j]->i_atk_ajuanminta,
								   "d_atk_ajuanminta"           =>(string)$result[$j]->d_atk_ajuanminta);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	public function updateSetujuMintaAtkM($noajuan,$setuju) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->beginTransaction();
	     $atk_mast_prm = array("c_atk_setujuplkp"  		=>$setuju,
						       "e_atk_setujuplkp"   	=>' ',
						       "i_entry"       		    =>$data['nuser'],
						       "d_entry"       		    =>date("Y-m-d"));
	     
		 $where[] = "i_atk_ajuanminta = '". $noajuan ."'";
	     $db->update('e_ast_minta_atk_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
		 		 
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	
	}
	
	//Stock Kosong =========== 22 okt 2007
	public function updateAjuanMintaAtkM($nopeng) {
	 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->beginTransaction();
	     $atk_mast_prm = array("c_atk_setujutu"  		=>'B',
						       "e_atk_setujutu"   		=>' ',
						       "i_entry"       		    =>$data['nuser'],
						       "d_entry"       		    =>date("Y-m-d"));
	     
		 $where[] = "i_atk_ajuanminta = '". $nopeng ."'";
	     $db->update('e_ast_minta_atk_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
		 		 
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	
	}
	
	//Ref Atk Kosong... 23okt 2007
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
	//query  stock kosong ..... 23 okt 2007
	public function queryAjuanMintaAtkKsM($unitkr) {
       $status='A';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT a.i_atk_ajuanminta,d_atk_ajuanminta FROM e_ast_minta_itematk_tm a,
								  e_ast_minta_atk_tm b, e_ast_barang_atk_tr c
								  where i_orgb=? and c_atk_setujutu=? and a.i_atk_ajuanminta = b.i_atk_ajuanminta
								  and a.c_atk=c.c_atk and c.q_atk_stock <= 0
								  ',$where); 
         $jmlResult = count($result);
		
		if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_atk_ajuanminta"           =>(string)$result[$j]->i_atk_ajuanminta,
								   "d_atk_ajuanminta"           =>(string)$result[$j]->d_atk_ajuanminta);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	// Distribusi ATK.........................23 okt 2007
	public function queryDistribusiAtkM() {
       $status='Y'; 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     //$where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT a.i_orgb, b.n_orgb, i_atk_ajuanminta,d_atk_ajuanminta 
									FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
									where a.i_orgb=b.i_orgb and c_atk_setujuplkp=? ORDER BY a.i_orgb' ,$where); 
									
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb"           			=>(string)$result[$j]->i_orgb,
								   "n_orgb"           			=>(string)$result[$j]->n_orgb,
								   "i_atk_ajuanminta"           =>(string)$result[$j]->i_atk_ajuanminta,
								   "d_atk_ajuanminta"           =>(string)$result[$j]->d_atk_ajuanminta);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	//Distribusi ATK............... 23 okt 2007..............
	public function insertDistribusiAtkM(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("i_atk_kirim" 		        	=>$data['nomorPengiriman'],
						       "d_atk_kirim"   	         		=>date("Y-m-d"),
							   "i_atk_ajuanminta"         		=>$data['noajuan'],
	                           "d_atk_ajuanminta"    	    	=>$data['tglAjuan'],
						       "i_orgb"                         =>$data['Unitkr'],
						       "i_atk_nippemberi" 		        =>$data['nipPemberi'],
						       "i_atk_nippenerima"   	    	=>$data['nipPenerima'],
						       "i_entry"       		            =>$data['nuser'],
						       "d_entry"       		           =>date("Y-m-d"));
	    
		
		 $db->insert('e_ast_distribusi_atk_tm',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	//Distribusi ATK... 24 okt 2007..
	public function updateDistribusiAtkM($noajuan) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->beginTransaction();
	     $atk_mast_prm = array("c_atk_setujuplkp"  		=>'C',
						       "e_atk_setujuplkp"   	=>' ',
						       "i_entry"       		    =>$data['nuser'],
						       "d_entry"       		    =>date("Y-m-d"));
	     
		 $where[] = "i_atk_ajuanminta = '". $noajuan ."'";
	     $db->update('e_ast_minta_atk_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
		 		 
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	
	}
	
	//Query ... 24  okt 2007
	
	public function queryMonitoringAtkKs($KatBarang) {
	    
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT b.c_atk_ctgr,n_atk_ctgr,b.c_atk,q_atk_minta,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock 
		                         FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
								 where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr and q_atk_stock <= 0 and b.c_atk_ctgr = ? order by b.c_atk',$KatBarang);
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_atk_ctgr"             =>(string)$result[$j]->c_atk_ctgr,
								   "n_atk_ctgr"             =>(string)$result[$j]->n_atk_ctgr,
								   "c_atk"                  =>(string)$result[$j]->c_atk,
								   "q_atk_minta"            =>(string)$result[$j]->q_atk_minta,
	                               "n_atk"                  =>(string)$result[$j]->n_atk,
								   "n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stockmin"         =>(string)$result[$j]->q_atk_stockmin,
								   "q_atk_stock"            =>(string)$result[$j]->q_atk_stock);
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function queryMonitoringAtkSedia($KatBarang) {
	    
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT b.c_atk_ctgr,n_atk_ctgr,b.c_atk,q_atk_minta,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock 
		                         FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
								 where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr and q_atk_stock > 0 and b.c_atk_ctgr = ? order by b.c_atk',$KatBarang);
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_atk_ctgr"             =>(string)$result[$j]->c_atk_ctgr,
								   "n_atk_ctgr"             =>(string)$result[$j]->n_atk_ctgr,
								   "c_atk"                  =>(string)$result[$j]->c_atk,
								   "q_atk_minta"            =>(string)$result[$j]->q_atk_minta,
	                               "n_atk"                  =>(string)$result[$j]->n_atk,
								   "n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stockmin"         =>(string)$result[$j]->q_atk_stockmin,
								   "q_atk_stock"            =>(string)$result[$j]->q_atk_stock);
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function queryMonitoringAtkKsKat($KatBarang) {
	    
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT b.c_atk_ctgr,n_atk_ctgr,b.c_atk,q_atk_minta,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock 
		                         FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
								 where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr and q_atk_stock <= 0 and b.c_atk_ctgr = ? order by b.c_atk',$KatBarang);
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_atk_ctgr"             =>(string)$result[$j]->c_atk_ctgr,
								   "n_atk_ctgr"             =>(string)$result[$j]->n_atk_ctgr,
								   "c_atk"                  =>(string)$result[$j]->c_atk,
								   "q_atk_minta"            =>(string)$result[$j]->q_atk_minta,
	                               "n_atk"                  =>(string)$result[$j]->n_atk,
								   "n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stockmin"         =>(string)$result[$j]->q_atk_stockmin,
								   "q_atk_stock"            =>(string)$result[$j]->q_atk_stock);
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function queryMonitoringAtkKsNm($KatBarang) {
		$namaBarang = strtoupper($KatBarang);
	    $nbrg = '%'.$namaBarang.'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
	     $where[] = $nbrg;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT b.c_atk_ctgr,n_atk_ctgr,b.c_atk,q_atk_minta,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock 
		                         FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
								 where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr and q_atk_stock <= 0 and a.n_atk like ? order by a.n_atk',$where);
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_atk_ctgr"             =>(string)$result[$j]->c_atk_ctgr,
								   "n_atk_ctgr"             =>(string)$result[$j]->n_atk_ctgr,
								   "c_atk"                  =>(string)$result[$j]->c_atk,
								   "q_atk_minta"            =>(string)$result[$j]->q_atk_minta,
	                               "n_atk"                  =>(string)$result[$j]->n_atk,
								   "n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stockmin"         =>(string)$result[$j]->q_atk_stockmin,
								   "q_atk_stock"            =>(string)$result[$j]->q_atk_stock);
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function queryMonitoringAtkSediaKat($KatBarang) {
	    
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT b.c_atk_ctgr,n_atk_ctgr,b.c_atk,q_atk_minta,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock 
		                         FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
								 where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr and q_atk_stock > 0 and b.c_atk_ctgr = ? order by b.c_atk',$KatBarang);
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_atk_ctgr"             =>(string)$result[$j]->c_atk_ctgr,
								   "n_atk_ctgr"             =>(string)$result[$j]->n_atk_ctgr,
								   "c_atk"                  =>(string)$result[$j]->c_atk,
								   "q_atk_minta"            =>(string)$result[$j]->q_atk_minta,
	                               "n_atk"                  =>(string)$result[$j]->n_atk,
								   "n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stockmin"         =>(string)$result[$j]->q_atk_stockmin,
								   "q_atk_stock"            =>(string)$result[$j]->q_atk_stock);
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function queryMonitoringAtkSediaNm($KatBarang) {
		$namaBarang = strtoupper($KatBarang);
	    $nbrg = '%'.$namaBarang.'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
	     $where[] = $nbrg;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT b.c_atk_ctgr,n_atk_ctgr,b.c_atk,q_atk_minta,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stockmin,q_atk_stock 
		                         FROM e_ast_barang_atk_tr a, e_ast_minta_itematk_tm b, e_ast_kategori_atk_tr c
								 where a.c_atk = b.c_atk and b.c_atk_ctgr=c.c_atk_ctgr and q_atk_stock > 0 and a.n_atk like ? order by a.n_atk',$where);
         
		 $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		   $hasilAkhir[$j] = array("c_atk_ctgr"             =>(string)$result[$j]->c_atk_ctgr,
								   "n_atk_ctgr"             =>(string)$result[$j]->n_atk_ctgr,
								   "c_atk"                  =>(string)$result[$j]->c_atk,
								   "q_atk_minta"            =>(string)$result[$j]->q_atk_minta,
	                               "n_atk"                  =>(string)$result[$j]->n_atk,
								   "n_atk_satuan"           =>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"            =>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"             =>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stockmin"         =>(string)$result[$j]->q_atk_stockmin,
								   "q_atk_stock"            =>(string)$result[$j]->q_atk_stock);
								  
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	//tambahan baru - utk dpindahin ke reffer
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
	
	//=================================service untuk melihat ==========================================================
	
	public function cekPejabat($unitjabatan) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
			$where[]=$unitjabatan;
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			 
				$i_orgb = $db->fetchCol("select b.i_orgb  
										from e_sdm_jabatan_0_tm A,  e_sdm_pegawai_0_tm B
										where a.i_peg_nip = b.i_peg_nip
										and b.c_unit_kerja = ?
										and b.c_unit_kerja = a.c_jabatan",$where);
				
				$jabatan = $i_orgb[0];
				
	     return $jabatan;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	//====================================melihat by org ==jab: sk1000..1400.. 1401======================================================
	
	public function rekapSetujuBeliAtkMByOrgList($pageNumber,$itemPerPage,$Stat,$unitkr) {
		$status=$Stat;
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $status;
			$where[] = $unitkr;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
											where c_atk_setuju=? and a.i_orgb=b.i_orgb
											and a.i_orgb=? ",$where);
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				
				$result = $db->fetchAll("SELECT i_atk_ajuan,d_atk_ajuan,a.i_orgb,n_orgb 
										FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
										where c_atk_setuju=? and a.i_orgb=b.i_orgb
										and a.i_orgb=?
										order by i_atk_ajuan
										limit $xLimit offset $xOffset",$where); 
			 
				$jmlResult = count($result);
			 	for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuan"           =>(string)$result[$j]->i_atk_ajuan,
											"d_atk_ajuan"           =>(string)$result[$j]->d_atk_ajuan,
											"i_orgb" 				=>(string)$result[$j]->i_orgb,
											"n_orgb" 				=>(string)$result[$j]->n_orgb);
											
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada';
	   }
	}
	
	public function rekapSetujuBeliAtkMByOrgPeriodList($pageNumber,$itemPerPage,$Stat,$tglAwal,$tglAkhir,$unitkr) {
		$status=$Stat;
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $status;
			$where[] = $tglAwal;
			$where[] = $tglAkhir;
			$where[] = $unitkr;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_ajuanbeli_atk_tm 
											 where c_atk_setuju=? and (d_atk_ajuan between ? and ?) and a.i_orgb=? ",$where);
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				
				$result = $db->fetchAll("SELECT i_atk_ajuan,d_atk_ajuan,a.i_orgb, n_orgb
										FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
										where c_atk_setuju=? and (d_atk_ajuan between ? and ?) and a.i_orgb=b.i_orgb and a.i_orgb=?
										order by i_atk_ajuan
										limit $xLimit offset $xOffset",$where); 
			 
				$jmlResult = count($result);
			 	for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuan"           =>(string)$result[$j]->i_atk_ajuan,
											"d_atk_ajuan"           =>(string)$result[$j]->d_atk_ajuan,
											"i_orgb" 				=>(string)$result[$j]->i_orgb,
											"n_orgb" 				=>(string)$result[$j]->n_orgb);
											
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada';
	   }
	}
	
	//=======================================================melihat by org =========TU== 
	public function rekapSetujuBeliAtkMByOrgTuList($pageNumber,$itemPerPage,$Stat,$unitkr) {
		$status=$Stat; 
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			
			 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 	
				$TU 	= $db->fetchCol("SELECT i_orgb_tu FROM e_org_0_0_tm where i_orgb = ? and i_orgb_tu is not null",$unitkr);
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
						$hasilAkhir = $db->fetchOne("SELECT count(*)
													FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
													where c_atk_setuju=? and a.i_orgb=b.i_orgb
													and  a.i_orgb like ? ", $where);
					 }
					 else
					 {
				
						 $xLimit=$itemPerPage;
						 $xOffset=($pageNumber-1)*$itemPerPage;		
						 
						 $result = $db->fetchAll("SELECT i_atk_ajuan,d_atk_ajuan,a.i_orgb,n_orgb,
													i_rab,i_mak,i_rab_norutdlmrangka,i_rab_norut
													FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
													where c_atk_setuju=? and a.i_orgb=b.i_orgb
													and a.i_orgb like ? 
													ORDER BY i_atk_ajuan
													limit $xLimit offset $xOffset", $where); 
						
						 $jmlResult = count($result);
						 
						 for ($j = 0; $j < $jmlResult; $j++) {
								$hasilAkhir[$j] = array("i_atk_ajuan"           =>(string)$result[$j]->i_atk_ajuan,
														"d_atk_ajuan"           =>(string)$result[$j]->d_atk_ajuan,
														"i_orgb" 				=>(string)$result[$j]->i_orgb,
														"n_orgb" 				=>(string)$result[$j]->n_orgb,
														"i_rab" 				=>(string)$result[$j]->i_rab,
														"i_mak" 				=>(string)$result[$j]->i_mak,
														"i_rab_norutdlmrangka" 	=>(string)$result[$j]->i_rab_norutdlmrangka,
														"i_rab_norut" 			=>(string)$result[$j]->i_rab_norut);
					 }	
					}	 
					return $hasilAkhir;
			}else{
				//melihat == unit parent==============================================
				//echo 'service melihat.. parent';
				//$unitkr='SK1400';
				
					$where[] = $status;
					$where[] = $unitkr;
					$where[] = $status;
					$where[] = $unitkr;
					$where[] = $status;
					$where[] = $unitkr;
					$where[] = $status;
					$where[] = $unitkr;
					$where[] = $status;
					$where[] = $unitkr;
					
					if(($pageNumber==0) && ($itemPerPage==0))
					 {
						$result = $db->fetchAll("SELECT i_atk_ajuan,d_atk_ajuan,a.i_orgb,n_orgb 
													FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
													where c_atk_setuju=? and a.i_orgb=b.i_orgb
													and a.i_orgb = ? 
													
													UNION
													SELECT i_atk_ajuan,d_atk_ajuan,a.i_orgb,n_orgb 
													FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
													where c_atk_setuju=? and a.i_orgb=b.i_orgb
													and i_orgb_parent  = ? 
													
													UNION
													SELECT i_atk_ajuan,d_atk_ajuan,a.i_orgb,n_orgb 
													FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm C 
													where c_atk_setuju=? and a.i_orgb=C.i_orgb
													and exists ( select * 
													from e_org_0_0_tm  B
													where B.i_orgb_parent  = ?
									                and B.i_orgb = C.i_orgb_parent
									                )     

													UNION
													SELECT i_atk_ajuan,d_atk_ajuan,a.i_orgb,n_orgb 
													FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
													where c_atk_setuju=? and a.i_orgb=b.i_orgb
													and i_orgb_parent   
													in ( select A.i_orgb from e_org_0_0_tm A
														 where exists 
													       ( select * from e_org_0_0_tm  B
													         where B.i_orgb_parent  = ?
													          and B.i_orgb = A.i_orgb_parent
													        )                 
													    )

													UNION
													SELECT i_atk_ajuan,d_atk_ajuan,a.i_orgb,n_orgb 
													FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
													where c_atk_setuju=? and a.i_orgb=b.i_orgb
													and i_orgb_parent 
													in ( select C.i_orgb from e_org_0_0_tm C
													      where C.i_orgb_parent 
													      in ( select A.i_orgb from e_org_0_0_tm A
													            where exists 
														        ( select * from e_org_0_0_tm  B
													    	          where B.i_orgb_parent  = ?
													        	          and B.i_orgb = A.i_orgb_parent
														        )                 
													    	)
													    )
													", $where); 
						
						 $jmlResult = count($result);
						 $hasilAkhir		= $jmlResult;				
					 }
					 else
					 {
				
						 $xLimit=$itemPerPage;
						 $xOffset=($pageNumber-1)*$itemPerPage;		
						 
						 $result = $db->fetchAll("SELECT i_atk_ajuan,d_atk_ajuan,a.i_orgb,n_orgb,
													i_rab,i_mak,i_rab_norutdlmrangka,i_rab_norut
													FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
													where c_atk_setuju=? and a.i_orgb=b.i_orgb
													and a.i_orgb = ? 
													
													UNION
													SELECT i_atk_ajuan,d_atk_ajuan,a.i_orgb,n_orgb,
													i_rab,i_mak,i_rab_norutdlmrangka,i_rab_norut													
													FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
													where c_atk_setuju=? and a.i_orgb=b.i_orgb
													and i_orgb_parent  = ? 
													
													UNION
													SELECT i_atk_ajuan,d_atk_ajuan,a.i_orgb,n_orgb, 
													i_rab,i_mak,i_rab_norutdlmrangka,i_rab_norut
													FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm C 
													where c_atk_setuju=? and a.i_orgb=C.i_orgb
													and exists ( select * 
													from e_org_0_0_tm  B
													where B.i_orgb_parent  = ?
									                and B.i_orgb = C.i_orgb_parent
									                )     

													UNION
													SELECT i_atk_ajuan,d_atk_ajuan,a.i_orgb,n_orgb, 
													i_rab,i_mak,i_rab_norutdlmrangka,i_rab_norut
													FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
													where c_atk_setuju=? and a.i_orgb=b.i_orgb
													and i_orgb_parent   
													in ( select A.i_orgb from e_org_0_0_tm A
														 where exists 
													       ( select * from e_org_0_0_tm  B
													         where B.i_orgb_parent  = ?
													          and B.i_orgb = A.i_orgb_parent
													        )                 
													    )

													UNION
													SELECT i_atk_ajuan,d_atk_ajuan,a.i_orgb,n_orgb ,
													i_rab,i_mak,i_rab_norutdlmrangka,i_rab_norut
													FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
													where c_atk_setuju=? and a.i_orgb=b.i_orgb
													and i_orgb_parent 
													in ( select C.i_orgb from e_org_0_0_tm C
													      where C.i_orgb_parent 
													      in ( select A.i_orgb from e_org_0_0_tm A
													            where exists 
														        ( select * from e_org_0_0_tm  B
													    	          where B.i_orgb_parent  = ?
													        	          and B.i_orgb = A.i_orgb_parent
														        )                 
													    	)
													    )

													
													ORDER BY i_atk_ajuan
													limit $xLimit offset $xOffset", $where); 
						
						 $jmlResult = count($result);
						 
						 for ($j = 0; $j < $jmlResult; $j++) {
								$hasilAkhir[$j] = array("i_atk_ajuan"           =>(string)$result[$j]->i_atk_ajuan,
														"d_atk_ajuan"           =>(string)$result[$j]->d_atk_ajuan,
														"i_orgb" 				=>(string)$result[$j]->i_orgb,
														"n_orgb" 				=>(string)$result[$j]->n_orgb,
														"i_rab" 				=>(string)$result[$j]->i_rab,
														"i_mak" 				=>(string)$result[$j]->i_mak,
														"i_rab_norutdlmrangka" 	=>(string)$result[$j]->i_rab_norutdlmrangka,
														"i_rab_norut" 			=>(string)$result[$j]->i_rab_norut);
					 }	
					}	 
					return $hasilAkhir;
					
			}
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function rekapSetujuBeliAtkMByOrgTuPeriodeList($pageNumber,$itemPerPage,$Stat,$tglAwal,$tglAkhir,$unitkr) {
		$status=$Stat; 
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			
			 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 	
				$TU 	= $db->fetchCol("SELECT i_orgb_tu FROM e_org_0_0_tm where i_orgb = ? and i_orgb_tu is not null",$unitkr);
				$unitTU = $TU[0];
		
				if($unitTU !=''){
					if (substr($unitTU,0,2) == 'DP' ){
			               $unitTULike = substr($unitTU,0,3).'%';
			        }else{
			               $unitTULike = substr($unitTU,0,2).'%';
			        }
					
					$where[] = $status;
					$where[] = $unitTULike;
					$where[] = $tglAwal;
					$where[] = $tglAkhir;
					
					 if(($pageNumber==0) && ($itemPerPage==0))
					 {
						$hasilAkhir = $db->fetchOne("SELECT count(*)
													FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
													where c_atk_setuju=? and a.i_orgb=b.i_orgb
													and  a.i_orgb like ? 
													and (d_atk_ajuan between ? and ?)", $where);
					 }
					 else
					 {
				
						 $xLimit=$itemPerPage;
						 $xOffset=($pageNumber-1)*$itemPerPage;		
						 
						 $result = $db->fetchAll("SELECT i_atk_ajuan,d_atk_ajuan,a.i_orgb,n_orgb ,
													i_rab,i_mak,i_rab_norutdlmrangka,i_rab_norut
													FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
													where c_atk_setuju=? and a.i_orgb=b.i_orgb
													and a.i_orgb like ? and (d_atk_ajuan between ? and ?)
													ORDER BY i_atk_ajuan
													limit $xLimit offset $xOffset", $where); 
						
						 $jmlResult = count($result);
						 
						 for ($j = 0; $j < $jmlResult; $j++) {
								$hasilAkhir[$j] = array("i_atk_ajuan"           =>(string)$result[$j]->i_atk_ajuan,
														"d_atk_ajuan"           =>(string)$result[$j]->d_atk_ajuan,
														"i_orgb" 				=>(string)$result[$j]->i_orgb,
														"n_orgb" 				=>(string)$result[$j]->n_orgb,
														"i_rab" 				=>(string)$result[$j]->i_rab,
														"i_mak" 				=>(string)$result[$j]->i_mak,
														"i_rab_norutdlmrangka" 	=>(string)$result[$j]->i_rab_norutdlmrangka,
														"i_rab_norut" 			=>(string)$result[$j]->i_rab_norut);
					 }	
					}	 
					return $hasilAkhir;
			}else{
				//melihat == unit parent==============================================
				//echo 'service melihat.. parent';
				//$unitkr='SK1400';
				
					$where[] = $status;
					$where[] = $tglAwal;
					$where[] = $tglAkhir;
					$where[] = $unitkr;
					$where[] = $status;
					$where[] = $tglAwal;
					$where[] = $tglAkhir;
					$where[] = $unitkr;
					$where[] = $status;
					$where[] = $tglAwal;
					$where[] = $tglAkhir;
					$where[] = $unitkr;
					$where[] = $status;
					$where[] = $tglAwal;
					$where[] = $tglAkhir;
					$where[] = $unitkr;
					$where[] = $status;
					$where[] = $tglAwal;
					$where[] = $tglAkhir;
					$where[] = $unitkr;
					
					if(($pageNumber==0) && ($itemPerPage==0))
					 {
						$result = $db->fetchAll("SELECT i_atk_ajuan,d_atk_ajuan,a.i_orgb,n_orgb 
													FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
													where c_atk_setuju=? and a.i_orgb=b.i_orgb
													and (d_atk_ajuan between ? and ?)
													and a.i_orgb = ? 
													
													UNION
													SELECT i_atk_ajuan,d_atk_ajuan,a.i_orgb,n_orgb 
													FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
													where c_atk_setuju=? and a.i_orgb=b.i_orgb
													and (d_atk_ajuan between ? and ?)
													and i_orgb_parent  = ? 
													
													UNION
													SELECT i_atk_ajuan,d_atk_ajuan,a.i_orgb,n_orgb 
													FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm C 
													where c_atk_setuju=? and a.i_orgb=C.i_orgb
													and (d_atk_ajuan between ? and ?)
													and exists ( select * 
													from e_org_0_0_tm  B
													where B.i_orgb_parent  = ?
									                and B.i_orgb = C.i_orgb_parent
									                )     

													UNION
													SELECT i_atk_ajuan,d_atk_ajuan,a.i_orgb,n_orgb 
													FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
													where c_atk_setuju=? and a.i_orgb=b.i_orgb
													and (d_atk_ajuan between ? and ?)
													and i_orgb_parent   
													in ( select A.i_orgb from e_org_0_0_tm A
														 where exists 
													       ( select * from e_org_0_0_tm  B
													         where B.i_orgb_parent  = ?
													          and B.i_orgb = A.i_orgb_parent
													        )                 
													    )

													UNION
													SELECT i_atk_ajuan,d_atk_ajuan,a.i_orgb,n_orgb 
													FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
													where c_atk_setuju=? and a.i_orgb=b.i_orgb
													and (d_atk_ajuan between ? and ?)
													and i_orgb_parent 
													in ( select C.i_orgb from e_org_0_0_tm C
													      where C.i_orgb_parent 
													      in ( select A.i_orgb from e_org_0_0_tm A
													            where exists 
														        ( select * from e_org_0_0_tm  B
													    	          where B.i_orgb_parent  = ?
													        	          and B.i_orgb = A.i_orgb_parent
														        )                 
													    	)
													    )
													", $where); 
						
						 $jmlResult = count($result);
						 $hasilAkhir		= $jmlResult;				
					 }
					 else
					 {
				
						 $xLimit=$itemPerPage;
						 $xOffset=($pageNumber-1)*$itemPerPage;		
						 
						 $result = $db->fetchAll("SELECT i_atk_ajuan,d_atk_ajuan,a.i_orgb,n_orgb,
													i_rab,i_mak,i_rab_norutdlmrangka,i_rab_norut
													FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
													where c_atk_setuju=? and a.i_orgb=b.i_orgb
													and a.i_orgb = ? 
													
													UNION
													SELECT i_atk_ajuan,d_atk_ajuan,a.i_orgb,n_orgb,
													i_rab,i_mak,i_rab_norutdlmrangka,i_rab_norut
													FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
													where c_atk_setuju=? and a.i_orgb=b.i_orgb
													and i_orgb_parent  = ? 
													
													UNION
													SELECT i_atk_ajuan,d_atk_ajuan,a.i_orgb,n_orgb,
													i_rab,i_mak,i_rab_norutdlmrangka,i_rab_norut
													FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm C 
													where c_atk_setuju=? and a.i_orgb=C.i_orgb
													and exists ( select * 
													from e_org_0_0_tm  B
													where B.i_orgb_parent  = ?
									                and B.i_orgb = C.i_orgb_parent
									                )     

													UNION
													SELECT i_atk_ajuan,d_atk_ajuan,a.i_orgb,n_orgb,
													i_rab,i_mak,i_rab_norutdlmrangka,i_rab_norut
													FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
													where c_atk_setuju=? and a.i_orgb=b.i_orgb
													and i_orgb_parent   
													in ( select A.i_orgb from e_org_0_0_tm A
														 where exists 
													       ( select * from e_org_0_0_tm  B
													         where B.i_orgb_parent  = ?
													          and B.i_orgb = A.i_orgb_parent
													        )                 
													    )

													UNION
													SELECT i_atk_ajuan,d_atk_ajuan,a.i_orgb,n_orgb,
													i_rab,i_mak,i_rab_norutdlmrangka,i_rab_norut
													FROM e_ast_ajuanbeli_atk_tm a, e_org_0_0_tm b 
													where c_atk_setuju=? and a.i_orgb=b.i_orgb
													and i_orgb_parent 
													in ( select C.i_orgb from e_org_0_0_tm C
													      where C.i_orgb_parent 
													      in ( select A.i_orgb from e_org_0_0_tm A
													            where exists 
														        ( select * from e_org_0_0_tm  B
													    	          where B.i_orgb_parent  = ?
													        	          and B.i_orgb = A.i_orgb_parent
														        )                 
													    	)
													    )

													
													ORDER BY i_atk_ajuan
													limit $xLimit offset $xOffset", $where); 
						
						 $jmlResult = count($result);
						 
						 for ($j = 0; $j < $jmlResult; $j++) {
								$hasilAkhir[$j] = array("i_atk_ajuan"           =>(string)$result[$j]->i_atk_ajuan,
														"d_atk_ajuan"           =>(string)$result[$j]->d_atk_ajuan,
														"i_orgb" 				=>(string)$result[$j]->i_orgb,
														"n_orgb" 				=>(string)$result[$j]->n_orgb,
														"i_rab" 				=>(string)$result[$j]->i_rab,
														"i_mak" 				=>(string)$result[$j]->i_mak,
														"i_rab_norutdlmrangka" 	=>(string)$result[$j]->i_rab_norutdlmrangka,
														"i_rab_norut" 			=>(string)$result[$j]->i_rab_norut);
					 }	
					}	 
					return $hasilAkhir;
					
			}
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	//tambahan utk pop up list RAB =============================17 apr 08 ==========================
	
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
											and upper(A.e_rab_ket) like '%ATK%'
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
											and upper(A.e_rab_ket) like '%ATK%'
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
										and upper(A.e_rab_ket) like '%ATK%'
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
										and upper(A.e_rab_ket) like '%ATK%'
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
	
	// utk ambil data kegiatan rab  ===========================================================
	
	public function getRab($rab,$mak,$rabkg,$nrabkg) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
			$where[]=$rab;
			$where[]=$mak;
			$where[]=$rabkg;
			$where[]=$nrabkg;
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			 
				$result = $db->fetchAll("select distinct A.i_rab,A.i_mak,A.i_rab_norutdlmrangka, A.i_rab_norut,
										A.e_rab_dalamrangka,B.e_rab_giat,C.n_mak
										from e_rab_non_standard_tm A, e_rab_0_0_tm B,
										e_keu_mak_0_tr C
										where A.i_rab = B.i_rab
										and A.c_rab_versi = B.c_rab_versi
										and A.i_mak = C.i_mak
										and A.i_rab = ?   
										and A.i_mak = ?   
										and A.i_rab_norutdlmrangka =?
										and A.i_rab_norut =?
										",$where);
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
			 
					$hasilAkhir[$j] = array("i_rab"						=>(string)$result[$j]->i_rab,
											"i_mak"   					=>(string)$result[$j]->i_mak, 
											"i_rab_norutdlmrangka"     	=>(string)$result[$j]->i_rab_norutdlmrangka, 
											"e_rab_dalamrangka"         =>(string)$result[$j]->e_rab_dalamrangka,
											"e_rab_giat"  				=>(string)$result[$j]->e_rab_giat,
											"n_mak"  					=>(string)$result[$j]->n_mak);
											
				}
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	/// Tambahan Cah Bagus ////
	public function getNamaPeg($nip) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$result="No Name";
		$where[] = $nip;
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$query="SELECT n_peg ".
				" FROM e_sdm_pegawai_0_tm where i_peg_nip=? ";
			$result = $db->fetchOne($query,$iOrgb);
			return $result;
		} 
		catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return $result;
		}						
	}

	public function getKodeOrgPeg($nip) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$result="No Name";
		$where[] = $nip;
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$query="SELECT c_unit_kerja ".
				" FROM e_sdm_pegawai_0_tm where i_peg_nip=? ";
			$result = $db->fetchOne($query,$iOrgb);
			return $result;
		} 
		catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return $result;
		}						
	}

	public function getNamaOrganisasi($iOrgb) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$result="No Name";
		$where[] = $iOrgb;
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$query="SELECT n_orgb ".
				" FROM e_org_0_0_tm where i_orgb=? ";
			$result = $db->fetchOne($query,$iOrgb);
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
			$query="select B.i_peg_nip , n_peg, A.n_jabatan, b.c_unit_kerja, b.i_orgb  
								from e_sdm_jabatan_0_tm A,  e_sdm_pegawai_0_tm B
								where a.i_peg_nip = b.i_peg_nip
								and b.c_unit_kerja = ?
								and b.c_unit_kerja = a.c_jabatan
								";
			$result = $db->fetchAll($query,$where);
			echo $query;
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
	  			$hasilAkhir[$j] = array("nip"  				=>(string)$result[$j]->i_peg_nip,
									"nama"					=>(string)$result[$j]->n_peg,
									"jabatan"  				=>(string)$result[$j]->n_jabatan,
									"c_unit_kerja"  		=>(string)$result[$j]->c_unit_kerja,
									"i_orgb"  				=>(string)$result[$j]->i_orgb
									);
			}
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}
	
	/// Akhir Tambahan Cah Bagus ////
	
}		
?>