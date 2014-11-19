<?php
class ast_ruangrapat_Service {
   
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
	 //==============================================================================================06 nop 2007========
	 public function getPengajuanRuangRapatList($unitkr) {
       $status='A';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 //$where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,n_rapat_judul,
								 q_rapat_peserta,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai,	
								 i_ruang,i_rapat_nippimpin,i_rapat_nippesan
							     FROM e_ast_ajuan_pakai_ruang_tm where i_orgb=?',$where); 
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_ruang_ajuanpakai"           =>(string)$result[$j]->i_ruang_ajuanpakai,
									"d_ruang_ajuanpakai"           =>(string)$result[$j]->d_ruang_ajuanpakai,
									"n_rapat_judul"          	  =>(string)$result[$j]->n_rapat_judul,
								   "q_rapat_peserta"          	  =>(string)$result[$j]->q_rapat_peserta,
								   "d_rapat_pesan"           	  =>(string)$result[$j]->d_rapat_pesan,
								   "d_rapat_awalpakai"            =>(string)$result[$j]->d_rapat_awalpakai,
								   "d_rapat_akhirpakai"           =>(string)$result[$j]->d_rapat_akhirpakai,
								   "i_ruang"                      =>(string)$result[$j]->i_ruang,
								   "i_rapat_nippimpin"            =>(string)$result[$j]->i_rapat_nippimpin,
								   "i_rapat_nippesan"             =>(string)$result[$j]->i_rapat_nippesan);
				
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	public function getCariPengajuanRuangRapatList($unitkr,$tglGuna) {
       $status='A';
	   $nsw = '%'.$tglGuna.'%';
	   echo 'prmAtkView..s..'.$tglGuna;
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $where[] = $unitkr;
		 $where[] = $nsw;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,n_rapat_judul,
								 q_rapat_peserta,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai,	
								 i_ruang,i_rapat_nippimpin,i_rapat_nippesan
							     FROM e_ast_ajuan_pakai_ruang_tm where i_orgb=? and d_rapat_pesan like ?',$where); 
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_ruang_ajuanpakai"           =>(string)$result[$j]->i_ruang_ajuanpakai,
									"d_ruang_ajuanpakai"           =>(string)$result[$j]->d_ruang_ajuanpakai,
									"n_rapat_judul"          	  =>(string)$result[$j]->n_rapat_judul,
								   "q_rapat_peserta"          	  =>(string)$result[$j]->q_rapat_peserta,
								   "d_rapat_pesan"           	  =>(string)$result[$j]->d_rapat_pesan,
								   "d_rapat_awalpakai"            =>(string)$result[$j]->d_rapat_awalpakai,
								   "d_rapat_akhirpakai"           =>(string)$result[$j]->d_rapat_akhirpakai,
								   "i_ruang"                      =>(string)$result[$j]->i_ruang,
								   "i_rapat_nippimpin"            =>(string)$result[$j]->i_rapat_nippimpin,
								   "i_rapat_nippesan"             =>(string)$result[$j]->i_rapat_nippesan);
				
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	public function getInformasiRuangList($unitkr,$tglGuna) {
       $status='A';
	   $nsw = '%'.$tglGuna.'%';
	   echo 'prmAtkView..s..'.$tglGuna;
	   echo 'unitkr..s..'.$unitkr;
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 //$where[] = $unitkr;
		 $where[] = $tglGuna;
		 $where[] = $tglGuna;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select i_orgb,a.i_ruang, 
									max(CASE WHEN d_rapat_awalpakai =9   THEN 'Y' END) as awal_9, 
									max(CASE WHEN d_rapat_awalpakai =10  THEN 'Y' END) as awal_10, 
									max(CASE WHEN d_rapat_awalpakai =11  THEN 'Y' END) as awal_11, 
									max(CASE WHEN d_rapat_awalpakai =12  THEN 'Y' END) as awal_12, 
									max(CASE WHEN d_rapat_awalpakai =13  THEN 'Y' END) as awal_13, 
									max(CASE WHEN d_rapat_awalpakai =14  THEN 'Y' END) as awal_14, 
									max(CASE WHEN d_rapat_awalpakai =15  THEN 'Y' END) as awal_15, 
									max(CASE WHEN d_rapat_awalpakai =16  THEN 'Y' END) as awal_16, 
									max(CASE WHEN d_rapat_awalpakai =17  THEN 'Y' END) as awal_17, 
									max(CASE WHEN d_rapat_awalpakai =18  THEN 'Y' END) as awal_18, 
									max(CASE WHEN d_rapat_awalpakai =19  THEN 'Y' END) as awal_19
									from(select i_orgb,i_ruang,d_rapat_awalpakai from e_ast_ajuan_pakai_ruang_tm where 
										 d_rapat_pesan =? ) a, e_ast_ruangan_0_tr b
										where a.i_ruang=b.i_ruang
										group by i_orgb,a.i_ruang

									UNION  SELECT
									   null as i_orgb,
									   a.i_ruang, 
									   null  as awal_9, 
									   null  as awal_10, 
									   null  as awal_11, 
									   null  as awal_12, 
									   null  as awal_13, 
									   null  as awal_14, 
									   null  as awal_15, 
									   null  as awal_16, 
									   null  as awal_17, 
									   null  as awal_18, 
									   null  as awal_19
									   from e_ast_ruangan_0_tr a
									   where not exists(select i_ruang from e_ast_ajuan_pakai_ruang_tm b
											  where a.i_ruang= b.i_ruang and b.d_rapat_pesan =?) and c_gedung_fungsi='2'
									   order by i_ruang",$where); 
								 
         $jmlResult = count($result);
		echo '$jmlResult'.$jmlResult;
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb"           	=>(string)$result[$j]->i_orgb,
									"i_ruang"           =>(string)$result[$j]->i_ruang,
									"d_rapat_awalpakai" =>(string)$result[$j]->d_rapat_awalpakai,
									"awal_9"          	=>(string)$result[$j]->awal_9,
									"awal_10"           =>(string)$result[$j]->awal_10,
									"awal_11"           =>(string)$result[$j]->awal_11,
									"awal_12"           =>(string)$result[$j]->awal_12,
									"awal_13"           =>(string)$result[$j]->awal_13,
									"awal_14"           =>(string)$result[$j]->awal_14,
									"awal_15"           =>(string)$result[$j]->awal_15,
									"awal_16"           =>(string)$result[$j]->awal_16,
									"awal_17"           =>(string)$result[$j]->awal_17,
									"awal_18"           =>(string)$result[$j]->awal_18,
									"awal_19"           =>(string)$result[$j]->awal_19);
				
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	public function getInformasiRuangList2($unitkr,$tglGuna) {
       $status='A';
	   $nsw = '%'.$tglGuna.'%';
	   echo 'prmAtkView..s..'.$tglGuna;
	   echo 'unitkr..s..'.$unitkr;
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $where[] = $unitkr;
		 $where[] = $tglGuna;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select i_orgb,i_ruang, d_rapat_awalpakai,
									max(CASE WHEN d_rapat_awalpakai =9 THEN 'Y' END) as awal_9, 
									max(CASE WHEN d_rapat_awalpakai =10 THEN 'Y' END) as awal_10, 
									max(CASE WHEN d_rapat_awalpakai =11 THEN 'Y' END) as awal_11, 
									max(CASE WHEN d_rapat_awalpakai =12 THEN 'Y' END) as awal_12, 
									max(CASE WHEN d_rapat_awalpakai =13 THEN 'Y' END) as awal_13, 
									max(CASE WHEN d_rapat_awalpakai =14 THEN 'Y' END) as awal_14, 
									max(CASE WHEN d_rapat_awalpakai =15 THEN 'Y' END) as awal_15, 
									max(CASE WHEN d_rapat_awalpakai =16 THEN 'Y' END) as awal_16, 
									max(CASE WHEN d_rapat_awalpakai =17 THEN 'Y' END) as awal_17, 
									max(CASE WHEN d_rapat_awalpakai =18 THEN 'Y' END) as awal_18, 
									max(CASE WHEN d_rapat_awalpakai =19 THEN 'Y' END) as awal_19
									from(select i_orgb,i_ruang,d_rapat_awalpakai from e_ast_ajuan_pakai_ruang_tm 
									where i_orgb=? and d_rapat_pesan =?) a
									group by i_orgb,i_ruang, d_rapat_awalpakai
									order by i_orgb",$where); 
								 
         $jmlResult = count($result);
		echo '$jmlResult'.$jmlResult;
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb"           	=>(string)$result[$j]->i_orgb,
									"i_ruang"           =>(string)$result[$j]->i_ruang,
									"d_rapat_awalpakai" =>(string)$result[$j]->d_rapat_awalpakai,
									"awal_9"          	=>(string)$result[$j]->awal_9,
									"awal_10"           =>(string)$result[$j]->awal_10,
									"awal_11"           =>(string)$result[$j]->awal_11,
									"awal_12"           =>(string)$result[$j]->awal_12,
									"awal_13"           =>(string)$result[$j]->awal_13,
									"awal_14"           =>(string)$result[$j]->awal_14,
									"awal_15"           =>(string)$result[$j]->awal_15,
									"awal_16"           =>(string)$result[$j]->awal_16,
									"awal_17"           =>(string)$result[$j]->awal_17,
									"awal_18"           =>(string)$result[$j]->awal_18,
									"awal_19"           =>(string)$result[$j]->awal_19);
				
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
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
	
	public function insertPengajuanRuangRapat(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("i_ruang_ajuanpakai"      	=>$data['noRapat'],
	                           "d_ruang_ajuanpakai"    		=>date("Y-m-d"),
						       "i_orgb"  					=>$data['unitkr'],
						       "n_rapat_judul" 				=>$data['jdlRapat'],
						       "q_rapat_peserta"   			=>$data['jmlPeserta'],
							   "i_rapat_mailtgjwb" 			=>$data['email'],
							   "i_rapat_telptgjwb" 			=>$data['noTelpon'],
							   "d_rapat_pesan" 				=>$data['tglRapat'],
							   "d_rapat_awalpakai" 			=>$data['jam'],
							   "d_rapat_akhirpakai" 		=>$data['akhirpakai'],
							   "i_ruang" 					=>$data['ruang'],
							   "c_rapat_konsumsipagi" 		=>$data['konsumsiPagi'],
							   "c_rapat_konsumsisiang" 		=>$data['konsumsiSiang'],
							   "c_rapat_makansiang" 		=>$data['makanSiang'],
							   "c_rapat_tipemakansiang" 	=>$data['pilih1'],
							   "c_rapat_makanmalam" 		=>$data['makanMalam'],
							   "c_rapat_tipemakanmalam" 	=>$data['pilih2'],
							   "i_rapat_nippimpin" 			=>$data['nipPenerima'],
							   "i_rapat_nippesan" 			=>$data['nipPemberi'],
							   "i_entry"       				=>"ast",
						       "d_entry"       				=>date("Y-m-d"));
	    
		
		
 		 $db->insert('e_ast_ajuan_pakai_ruang_tm',$atk_mast_prm);
		 $db->commit();
	     return 'sukses <br>';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getDataRuangRapat($prmRapatEdit) {
       $status='A';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
		 $result = $db->fetchAll('SELECT a.i_ruang_ajuanpakai,a.d_ruang_ajuanpakai,a.i_orgb,a.n_rapat_judul,
									a.q_rapat_peserta,a.i_rapat_mailtgjwb,a.i_rapat_telptgjwb,a.d_rapat_pesan,
									a.d_rapat_awalpakai,d_rapat_akhirpakai,i_ruang,c_rapat_konsumsipagi,
									a.c_rapat_konsumsisiang,c_rapat_makansiang,c_rapat_makanmalam,
									c_rapat_tipemakansiang,c_rapat_tipemakanmalam,
									i_rapat_nippesan,i_rapat_nippimpin
									FROM e_ast_ajuan_pakai_ruang_tm a
									where a.i_ruang_ajuanpakai = ? Order by a.i_ruang_ajuanpakai ',$prmRapatEdit);
         $jmlResult = count($result);
		
		 echo 'jmldata :'.$jmlResult;
		 echo 'no rpa:'.$prmRapatEdit;
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		    
			//echo 'masuk loop :'  ;
           $hasilAkhir[$j] = array("i_ruang_ajuanpakai"             =>(string)$result[$j]->i_ruang_ajuanpakai,
								   "d_ruang_ajuanpakai"             =>(string)$result[$j]->d_ruang_ajuanpakai,
								   "i_orgb"    						=>(string)$result[$j]->i_orgb,
								   "n_rapat_judul"     				=>(string)$result[$j]->n_rapat_judul,
	                               "q_rapat_peserta"       			=>(string)$result[$j]->q_rapat_peserta,
								   "i_rapat_mailtgjwb"    			=>(string)$result[$j]->i_rapat_mailtgjwb,
								   "i_rapat_telptgjwb"    			=>(string)$result[$j]->i_rapat_telptgjwb,
								   "d_rapat_pesan"    				=>(string)$result[$j]->d_rapat_pesan,
								   "d_rapat_awalpakai" 				=>(string)$result[$j]->d_rapat_awalpakai,
								   "d_rapat_akhirpakai"    			=>(string)$result[$j]->d_rapat_akhirpakai,
								   "i_ruang"    					=>(string)$result[$j]->i_ruang,
								   "c_rapat_konsumsipagi"    		=>(string)$result[$j]->c_rapat_konsumsipagi,
								   "c_rapat_konsumsisiang"    		=>(string)$result[$j]->c_rapat_konsumsisiang,
								   "c_rapat_makansiang"    			=>(string)$result[$j]->c_rapat_makansiang,
								   "c_rapat_tipemakansiang"    		=>(string)$result[$j]->c_rapat_tipemakansiang,
								   "c_rapat_makanmalam"    			=>(string)$result[$j]->c_rapat_makanmalam,
								   "c_rapat_tipemakanmalam"    		=>(string)$result[$j]->c_rapat_tipemakanmalam,
								   "i_rapat_nippesan"    			=>(string)$result[$j]->i_rapat_nippesan,
								   "i_rapat_nippimpin"    			=>(string)$result[$j]->i_rapat_nippimpin);
			
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function updatePengajuanRuangRapat(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $atk_dtl_parm = array("d_ruang_ajuanpakai"    		=>date("Y-m-d"),
						       "i_orgb"  					=>$data['unitkr'],
						       "n_rapat_judul" 				=>$data['jdlRapat'],
						       "q_rapat_peserta"   			=>$data['jmlPeserta'],
							   "i_rapat_mailtgjwb" 			=>$data['email'],
							   "i_rapat_telptgjwb" 			=>$data['noTelpon'],
							   "d_rapat_pesan" 				=>$data['tglRapat'],
							   "d_rapat_awalpakai" 			=>$data['jam'],
							   "d_rapat_akhirpakai" 		=>$data['akhirpakai'],
							   "i_ruang" 					=>$data['ruang'],
							   "c_rapat_konsumsipagi" 		=>$data['konsumsiPagi'],
							   "c_rapat_konsumsisiang" 		=>$data['konsumsiSiang'],
							   "c_rapat_makansiang" 		=>$data['makanSiang'],
							   "c_rapat_tipemakansiang" 	=>$data['pilih1'],
							   "c_rapat_makanmalam" 		=>$data['makanMalam'],
							   "c_rapat_tipemakanmalam" 	=>$data['pilih2'],
							   "i_rapat_nippimpin" 			=>$data['nipPenerima'],
							   "i_rapat_nippesan" 			=>$data['nipPemberi'],
							   "i_entry"       				=>"ast",
						       "d_entry"       				=>date("Y-m-d"));
							   
							   
	     $where[] = "i_ruang_ajuanpakai  =  '".trim($data['noRapat'])."'";
	     $db->update('e_ast_ajuan_pakai_ruang_tm',$atk_dtl_parm, $where);
		 
		 $db->commit();
	     return 'sukses <br>';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function deletePengajuanRuangRapat(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 
	  	 $where[] = "i_ruang_ajuanpakai  =  '".trim($data['noRapat'])."'";
	     
		 $db->delete('e_ast_ajuan_pakai_ruang_tm', $where);
		 $db->commit();
	     return 'sukses <br>';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	
	//===========================================================================
	//==============================================================================================06 nop 2007======== asih ================
	//==============================================================================================06 nop 2007======== ====================
	
	 public function getPengajuanRuangRapatList2($unitkr) {
       $status='A';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,n_rapat_judul,
								 q_rapat_peserta,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai,	
								 i_ruang,i_rapat_nippimpin,i_rapat_nippesan FROM e_ast_ajuan_pakai_ruang_tm where a.i_orgb=?',$where); 
         $jmlResult = count($result);
		 echo '$jmlResult'.$jmlResult;
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_ruang_ajuanpakai"           =>(string)$result[$j]->i_ruang_ajuanpakai,
								   "d_ruang_ajuanpakai"           =>(string)$result[$j]->d_ruang_ajuanpakai,
								   "n_rapat_judul"          	  =>(string)$result[$j]->n_rapat_judul,
								   "q_rapat_peserta"          	  =>(string)$result[$j]->q_rapat_peserta,
								   "d_rapat_pesan"           	  =>(string)$result[$j]->d_rapat_pesan,
								   "d_rapat_awalpakai"            =>(string)$result[$j]->d_rapat_awalpakai,
								   "d_rapat_akhirpakai"           =>(string)$result[$j]->d_akhir_awalpakai,
								   "i_ruang"                      =>(string)$result[$j]->i_ruang,
								   "i_rapat_nippimpin"            =>(string)$result[$j]->i_rapat_nippimpin,
								   "i_rapat_nippesan"             =>(string)$result[$j]->i_rapat_nippesan
								   );
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
		
		
	 //.......................................................................................................................................
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
	 //
	
	 
     
	 
	public function updateAjuanBeliAtkM($nopeng) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("c_atk_setuju"  		=>'B',
						       "e_keterangan"   		=>' ',
						       "i_entry"       		    =>"ast",
						       "d_entry"       		    =>date("Y-m-d"));
	     
		 $where[] = "i_atk_ajuan = '". $nopeng ."'";
	     $db->update('e_ast_ajuanbeli_atk_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses <br>';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
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
	     return 'sukses <br>';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
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
	     return 'sukses <br>';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
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
public function updateSetujuAtkM($nopeng) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("c_atk_setuju"  		=>'Y',
						       "e_keterangan"   		=>' ',
						       "i_entry"       		    =>'ast',
						       "d_entry"       		    =>date("Y-m-d"));
	     
		 $where[] = "i_atk_ajuan = '". $nopeng ."'";
	     $db->update('e_ast_ajuanbeli_atk_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses Persetujuan <br>';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
public function queryDaftarAtkM($unitkr) {
       $status='Y';
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
						        "i_entry"       		        =>"ast",
						        "d_entry"       		        =>date("Y-m-d"));
	    
	     $db->insert('e_ast_terima_atk_tm',$atk_mast_prm);
		 $db->commit();
	     return 'sukses <br>';
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
	     return 'sukses <br>';
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
	     return 'sukses <br>';
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
	     return 'sukses <br>';
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
	 
	public function insertAjuanMIntaAtkM(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("i_atk_ajuanminta"         		=>$data['nopeng'],
	                           "d_atk_ajuanminta"    	    	=>$data['tglPengajuan'],
						       "i_orgb"                         =>$data['Unitkr'],
						       "c_atk_setujutu" 		        =>$data['status'],
						       "e_atk_setujutu"   	         	=>$data['kettu'],
							   "c_atk_setujuplkp" 		        =>$data['statusplkp'],
						       "e_atk_setujuplkp"   	    	=>$data['ketplkp'],
						       "i_entry"       		            =>"ast",
						       "d_entry"       		           =>date("Y-m-d"));
	    
	     $db->insert('e_ast_minta_atk_tm',$atk_mast_prm);
		 $db->commit();
	     return 'sukses <br>';
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
	     return 'sukses <br>';
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
						       "i_entry"       		    =>"ast",
						       "d_entry"       		    =>date("Y-m-d"));
	     
		 $where[] = "i_atk_ajuanminta = '". $noajuan ."'";
	     $db->update('e_ast_minta_atk_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses <br>';
		 		 
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
						       "i_entry"       		    =>"ast",
						       "d_entry"       		    =>date("Y-m-d"));
	     
		 $where[] = "i_atk_ajuanminta = '". $noajuan ."'";
	     $db->update('e_ast_minta_atk_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses <br>';
		 		 
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	
	}
	
	//Stock Kosong =========== 22 okt 2007
	public function updateAjuanMintaAtkM($nopeng) {
	echo '$nopeng'.$nopeng;
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->beginTransaction();
	     $atk_mast_prm = array("c_atk_setujutu"  		=>'B',
						       "e_atk_setujutu"   		=>' ',
						       "i_entry"       		    =>"ast",
						       "d_entry"       		    =>date("Y-m-d"));
	     
		 $where[] = "i_atk_ajuanminta = '". $nopeng ."'";
	     $db->update('e_ast_minta_atk_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses <br>';
		 		 
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
						       "i_entry"       		            =>"ast",
						       "d_entry"       		           =>date("Y-m-d"));
	    
		
		 $db->insert('e_ast_distribusi_atk_tm',$atk_mast_prm);
		 $db->commit();
	     return 'sukses <br>';
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
						       "i_entry"       		    =>"ast",
						       "d_entry"       		    =>date("Y-m-d"));
	     
		 $where[] = "i_atk_ajuanminta = '". $noajuan ."'";
	     $db->update('e_ast_minta_atk_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses <br>';
		 		 
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
	 
	public function getPimpInfoRuangList($prmjam,$prmtgl) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {		
		$where[]=$prmjam;
		$where[]=$prmjam;
		$where[]=$prmtgl;
		$query="SELECT distinct a.i_orgb as i_orga, a.q_rapat_peserta, a.i_rapat_nippimpin, b.n_peg, b.n_jabatan, b.i_orgb       
			FROM   e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b
			WHERE a.i_rapat_nippimpin=b.i_peg_nip and d_rapat_awalpakai <=?
				and d_rapat_akhirpakai > ? and d_rapat_pesan =? ";
		//echo "test : ".$query;
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $db->fetchAll($query,$where);
		$jmlResult = count($result);
		for ($j = 0; $j < $jmlResult; $j++) {
         		$hasilAkhir[$j] =   array("i_orga"    		=>(string)$result[$j]->i_orga,
						   "q_rapat_peserta"    =>(string)$result[$j]->q_rapat_peserta,
						   "i_rapat_nippimpin"  =>(string)$result[$j]->i_rapat_nippimpin,
						   "n_peg"       	=>(string)$result[$j]->n_peg,
						   "n_jabatan"       	=>(string)$result[$j]->n_jabatan,
						   "i_orgb"       	=>(string)$result[$j]->i_orgb);
		}					 
		return $hasilAkhir;
	  } catch (Exception $e) {
		echo $e->getMessage().'<br>';
		return 'gagal <br>';
	  }						
	}
	
	
	public function getInformasiRuangListAll($unitkr) {
		$status='A';
	   	$registry = Zend_Registry::getInstance();
	   	$db = $registry->get('db');
	   	try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$query="select i_orgb,a.i_ruang,d_rapat_pesan,
					max(CASE WHEN d_rapat_awalpakai =9   THEN 'Y' END) as awal_9, 
					max(CASE WHEN d_rapat_awalpakai =10  THEN 'Y' END) as awal_10, 
					max(CASE WHEN d_rapat_awalpakai =11  THEN 'Y' END) as awal_11, 
					max(CASE WHEN d_rapat_awalpakai =12  THEN 'Y' END) as awal_12, 
					max(CASE WHEN d_rapat_awalpakai =13  THEN 'Y' END) as awal_13, 
					max(CASE WHEN d_rapat_awalpakai =14  THEN 'Y' END) as awal_14, 
					max(CASE WHEN d_rapat_awalpakai =15  THEN 'Y' END) as awal_15, 
					max(CASE WHEN d_rapat_awalpakai =16  THEN 'Y' END) as awal_16, 
					max(CASE WHEN d_rapat_awalpakai =17  THEN 'Y' END) as awal_17, 
					max(CASE WHEN d_rapat_awalpakai =18  THEN 'Y' END) as awal_18, 
					max(CASE WHEN d_rapat_awalpakai =19  THEN 'Y' END) as awal_19
					from(select i_orgb,i_ruang,d_rapat_awalpakai,d_rapat_pesan from e_ast_ajuan_pakai_ruang_tm ) a, e_ast_ruangan_0_tr b
						where a.i_ruang=b.i_ruang
						group by i_orgb,a.i_ruang,d_rapat_pesan
				UNION  SELECT  null as i_orgb,
					   a.i_ruang, 
                                           null as d_rapat_pesan,
					   null  as awal_9, 
					   null  as awal_10, 
					   null  as awal_11, 
					   null  as awal_12, 
					   null  as awal_13, 
					   null  as awal_14, 
					   null  as awal_15, 
					   null  as awal_16, 
					   null  as awal_17, 
					   null  as awal_18, 
					   null  as awal_19
					   from e_ast_ruangan_0_tr a
					   where not exists(select i_ruang from e_ast_ajuan_pakai_ruang_tm b
							  where a.i_ruang= b.i_ruang ) and c_gedung_fungsi='2'
					   order by i_ruang";
			$result = $db->fetchAll($query); 								 
			$jmlResult = count($result);
			//echo '$jmlResult'.$jmlResult;		
			if($jmlResult > 0){
				for ($j = 0; $j < $jmlResult; $j++) {		 
           				$hasilAkhir[$j] = array("i_orgb"           	=>(string)$result[$j]->i_orgb,
									"i_ruang"           =>(string)$result[$j]->i_ruang,
									"d_rapat_pesan"	=>(string)$result[$j]->d_rapat_pesan,
									"d_rapat_awalpakai" =>(string)$result[$j]->d_rapat_awalpakai,
									"awal_9"          	=>(string)$result[$j]->awal_9,
									"awal_10"           =>(string)$result[$j]->awal_10,
									"awal_11"           =>(string)$result[$j]->awal_11,
									"awal_12"           =>(string)$result[$j]->awal_12,
									"awal_13"           =>(string)$result[$j]->awal_13,
									"awal_14"           =>(string)$result[$j]->awal_14,
									"awal_15"           =>(string)$result[$j]->awal_15,
									"awal_16"           =>(string)$result[$j]->awal_16,
									"awal_17"           =>(string)$result[$j]->awal_17,
									"awal_18"           =>(string)$result[$j]->awal_18,
									"awal_19"           =>(string)$result[$j]->awal_19);
				
			}
        	}		 
		return $hasilAkhir;
	   } catch (Exception $e) {
		echo $e->getMessage().'<br>';
		return 'gagal <br>';
	   }
	}	 
	
	public function getInfoSetujuRuangRapatAllList() {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$query="select i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , d_rapat_awalpakai ,d_rapat_akhirpakai ,i_ruang_usulan,i_ruang ,c_rapat_statsetuju ,n_peg,n_jabatan,b.i_orgb as i_orgp".
				",(CASE WHEN c_rapat_statsetuju ='U' THEN i_ruang_usulan WHEN c_rapat_statsetuju ='Y' THEN i_ruang END) as realruang,d_rapat_pesan ".
				" FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b ".
				" where i_peg_nip = i_rapat_nippesan and c_rapat_statsetuju ='Y' and (c_rapat_konsumsipagi='' or c_rapat_konsumsipagi is null) and (c_rapat_konsumsisiang='' or c_rapat_konsumsisiang is null) ".
				" and (c_rapat_makansiang='' or c_rapat_makansiang is null) and (c_rapat_makanmalam='' or c_rapat_makanmalam is null) ";				
			
			$query=$query." union select i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , d_rapat_awalpakai ,d_rapat_akhirpakai ,i_ruang_usulan,i_ruang ,c_rapat_statsetuju ,n_peg,n_jabatan,b.i_orgb as i_orgp".
				",(CASE WHEN c_rapat_statsetuju ='U' THEN i_ruang_usulan WHEN c_rapat_statsetuju ='Y' THEN i_ruang END) as realruang,d_rapat_pesan ".
				" FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b ".
				" where i_peg_nip = i_rapat_nippesan and c_rapat_statsetuju ='U' and c_rapat_statjwbusul='Y' and (c_rapat_konsumsipagi='' or c_rapat_konsumsipagi is null) and (c_rapat_konsumsisiang='' or c_rapat_konsumsisiang is null) ".
				" and (c_rapat_makansiang='' or c_rapat_makansiang is null) and (c_rapat_makanmalam='' or c_rapat_makanmalam is null) ";
			
			$query=$query." union select i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , d_rapat_awalpakai ,d_rapat_akhirpakai ,i_ruang_usulan,i_ruang ,c_rapat_statsetuju ,n_peg,n_jabatan,b.i_orgb as i_orgp".
				",(CASE WHEN c_rapat_statsetuju ='U' THEN i_ruang_usulan WHEN c_rapat_statsetuju ='Y' THEN i_ruang END) as realruang,d_rapat_pesan ".
				" FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b ".
				" where i_peg_nip = i_rapat_nippesan and c_rapat_statsetuju ='Y' and c_rapat_statsetuju1='Y' ".
				" and (c_rapat_konsumsipagi='Y' or c_rapat_konsumsisiang='Y' or c_rapat_makansiang='Y' or c_rapat_makanmalam='Y') ";
			
			$query=$query." union select i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , d_rapat_awalpakai ,d_rapat_akhirpakai ,i_ruang_usulan,i_ruang ,c_rapat_statsetuju ,n_peg,n_jabatan,b.i_orgb as i_orgp".
				",(CASE WHEN c_rapat_statsetuju ='U' THEN i_ruang_usulan WHEN c_rapat_statsetuju ='Y' THEN i_ruang END) as realruang,d_rapat_pesan ".
				" FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b ".
				" where i_peg_nip = i_rapat_nippesan and c_rapat_statsetuju ='U' and c_rapat_statjwbusul='Y'  and c_rapat_statsetuju1='Y' ".
				" and (c_rapat_konsumsipagi='Y' or c_rapat_konsumsisiang='Y' or c_rapat_makansiang='Y' or c_rapat_makanmalam='Y') ";							
			//echo "<br>Querynya :".$query."<br>";
			$result = $db->fetchAll($query);
			$jmlResult = count($result);
			if($jmlResult > 0){
	 			for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_ruang_ajuanpakai"     =>(string)$result[$j]->i_ruang_ajuanpakai,
									"d_ruang_ajuanpakai"    =>(string)$result[$j]->d_ruang_ajuanpakai,
									"i_rapat_nippesan"      =>(string)$result[$j]->i_rapat_nippesan,
									"d_rapat_awalpakai"     =>(string)$result[$j]->d_rapat_awalpakai,
									"d_rapat_akhirpakai"    =>(string)$result[$j]->d_rapat_akhirpakai,
									"i_ruang_usulan"        =>(string)$result[$j]->i_ruang_usulan,
									"i_ruang"           	=>(string)$result[$j]->i_ruang,
									"c_rapat_statsetuju"    =>(string)$result[$j]->c_rapat_statsetuju,
									"n_peg"    		=>(string)$result[$j]->n_peg,
									"i_orgp"    		=>(string)$result[$j]->i_orgp,
									"n_jabatan"    		=>(string)$result[$j]->n_jabatan,
									"realruang"            	=>(string)$result[$j]->realruang,
									"d_rapat_pesan"         =>(string)$result[$j]->d_rapat_pesan									
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

	public function getInfoSetujuRuangRapatList($tglGuna) {
		/*
		echo "<br> Tanggal : ".$tgl."-".$bln."-".$thn."<br>";
		if ($tgl=='#'){
			if ($bln != '#'){
				$where = "%".$thn."-".$bln."%";
				$query1=" and d_rapat_pesan like ? ";
			}else{
				$where = "%".$thn."%";
				$query1=" and d_rapat_pesan like ? ";			
			}
		}else{
			if ($bln != '#' && $thn >='1970'){
				$where = $thn."-".$bln."-".$tgl;
				$query1=" and d_rapat_pesan = ? ";						
			}else if($bln == '#'){//$thn<='1970' || $thn=''
				$where = "%-".$tgl;
				$query1=" and d_rapat_pesan like ? ";									
			}else{
				$where = "%-".$bln."-".$tgl;
				$query1=" and d_rapat_pesan like ? ";
				}						
		}
		*/
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[]=$tglGuna;
			$where[]=$tglGuna;
			$where[]=$tglGuna;
			$where[]=$tglGuna;
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$query="select i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , d_rapat_awalpakai ,d_rapat_akhirpakai ,i_ruang_usulan,i_ruang ,c_rapat_statsetuju ,n_peg,n_jabatan,b.i_orgb as i_orgp".
				",(CASE WHEN c_rapat_statsetuju ='U' THEN i_ruang_usulan WHEN c_rapat_statsetuju ='Y' THEN i_ruang END) as realruang,d_rapat_pesan ".
				" FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b ".
				" where i_peg_nip = i_rapat_nippesan and c_rapat_statsetuju ='Y' and (c_rapat_konsumsipagi='' or c_rapat_konsumsipagi is null) and (c_rapat_konsumsisiang='' or c_rapat_konsumsisiang is null) ".
				" and (c_rapat_makansiang='' or c_rapat_makansiang is null) and (c_rapat_makanmalam='' or c_rapat_makanmalam is null) and d_rapat_pesan = ? ";							
			
			$query=$query." union select i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , d_rapat_awalpakai ,d_rapat_akhirpakai ,i_ruang_usulan,i_ruang ,c_rapat_statsetuju ,n_peg,n_jabatan,b.i_orgb as i_orgp".
				",(CASE WHEN c_rapat_statsetuju ='U' THEN i_ruang_usulan WHEN c_rapat_statsetuju ='Y' THEN i_ruang END) as realruang,d_rapat_pesan ".
				" FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b ".
				" where i_peg_nip = i_rapat_nippesan and c_rapat_statsetuju ='U' and c_rapat_statjwbusul='Y' and (c_rapat_konsumsipagi='' or c_rapat_konsumsipagi is null) and (c_rapat_konsumsisiang='' or c_rapat_konsumsisiang is null) ".
				" and (c_rapat_makansiang='' or c_rapat_makansiang is null) and (c_rapat_makanmalam='' or c_rapat_makanmalam is null) and d_rapat_pesan = ? ";
			
			$query=$query." union select i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , d_rapat_awalpakai ,d_rapat_akhirpakai ,i_ruang_usulan,i_ruang ,c_rapat_statsetuju ,n_peg,n_jabatan,b.i_orgb as i_orgp".
				",(CASE WHEN c_rapat_statsetuju ='U' THEN i_ruang_usulan WHEN c_rapat_statsetuju ='Y' THEN i_ruang END) as realruang,d_rapat_pesan ".
				" FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b ".
				" where i_peg_nip = i_rapat_nippesan and c_rapat_statsetuju ='Y' and c_rapat_statsetuju1='Y' ".
				" and (c_rapat_konsumsipagi='Y' or c_rapat_konsumsisiang='Y' or c_rapat_makansiang='Y' or c_rapat_makanmalam='Y') and d_rapat_pesan = ? ";
			
			$query=$query." union select i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , d_rapat_awalpakai ,d_rapat_akhirpakai ,i_ruang_usulan,i_ruang ,c_rapat_statsetuju ,n_peg,n_jabatan,b.i_orgb as i_orgp".
				",(CASE WHEN c_rapat_statsetuju ='U' THEN i_ruang_usulan WHEN c_rapat_statsetuju ='Y' THEN i_ruang END) as realruang,d_rapat_pesan ".
				" FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b ".
				" where i_peg_nip = i_rapat_nippesan and c_rapat_statsetuju ='U' and c_rapat_statjwbusul='Y'  and c_rapat_statsetuju1='Y' ".
				" and (c_rapat_konsumsipagi='Y' or c_rapat_konsumsisiang='Y' or c_rapat_makansiang='Y' or c_rapat_makanmalam='Y') and d_rapat_pesan = ? ";			
			//echo "<br>Querynya :".$query."<br>";
			$result = $db->fetchAll($query,$where);
			$jmlResult = count($result);
			if($jmlResult > 0){
	 			for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_ruang_ajuanpakai"     =>(string)$result[$j]->i_ruang_ajuanpakai,
									"d_ruang_ajuanpakai"    =>(string)$result[$j]->d_ruang_ajuanpakai,
									"i_rapat_nippesan"      =>(string)$result[$j]->i_rapat_nippesan,
									"d_rapat_awalpakai"     =>(string)$result[$j]->d_rapat_awalpakai,
									"d_rapat_akhirpakai"    =>(string)$result[$j]->d_rapat_akhirpakai,
									"i_ruang_usulan"        =>(string)$result[$j]->i_ruang_usulan,
									"i_ruang"           	=>(string)$result[$j]->i_ruang,
									"c_rapat_statsetuju"    =>(string)$result[$j]->c_rapat_statsetuju,
									"n_peg"    		=>(string)$result[$j]->n_peg,
									"i_orgp"    		=>(string)$result[$j]->i_orgp,
									"n_jabatan"    		=>(string)$result[$j]->n_jabatan,
									"realruang"            	=>(string)$result[$j]->realruang,
									"d_rapat_pesan"         =>(string)$result[$j]->d_rapat_pesan
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

	public function getInfoBlmSetujuRuangRapatAllList() {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$query="select i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , d_rapat_awalpakai ,d_rapat_akhirpakai ,i_ruang_usulan,i_ruang ,c_rapat_statsetuju ,n_peg,n_jabatan,b.i_orgb as i_orgp".
				",(CASE WHEN c_rapat_statsetuju ='U' THEN i_ruang_usulan WHEN c_rapat_statsetuju ='Y' THEN i_ruang END) as realruang,d_rapat_pesan ".
				" FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b ".
				" where i_peg_nip = i_rapat_nippesan and (c_rapat_statsetuju ='' or c_rapat_statsetuju is null)";				
			
			$query=$query." union select i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , d_rapat_awalpakai ,d_rapat_akhirpakai ,i_ruang_usulan,i_ruang ,c_rapat_statsetuju ,n_peg,n_jabatan,b.i_orgb as i_orgp".
				",(CASE WHEN c_rapat_statsetuju ='U' THEN i_ruang_usulan WHEN c_rapat_statsetuju ='Y' THEN i_ruang END) as realruang,d_rapat_pesan ".
				" FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b ".
				" where i_peg_nip = i_rapat_nippesan and c_rapat_statsetuju ='U' and (c_rapat_statjwbusul='' or c_rapat_statjwbusul is null) ";
			
			$query=$query." union select i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , d_rapat_awalpakai ,d_rapat_akhirpakai ,i_ruang_usulan,i_ruang ,c_rapat_statsetuju ,n_peg,n_jabatan,b.i_orgb as i_orgp".
				",(CASE WHEN c_rapat_statsetuju ='U' THEN i_ruang_usulan WHEN c_rapat_statsetuju ='Y' THEN i_ruang END) as realruang,d_rapat_pesan ".
				" FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b ".
				" where i_peg_nip = i_rapat_nippesan and c_rapat_statsetuju ='Y' and (c_rapat_statsetuju1='' or c_rapat_statsetuju1 is null)".
				" and (c_rapat_konsumsipagi='Y' or c_rapat_konsumsisiang='Y' or c_rapat_makansiang='Y' or c_rapat_makanmalam='Y') ";
			
			//echo "<br>Querynya :".$query."<br>";
			$result = $db->fetchAll($query);
			$jmlResult = count($result);
			if($jmlResult > 0){
	 			for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_ruang_ajuanpakai"     =>(string)$result[$j]->i_ruang_ajuanpakai,
									"d_ruang_ajuanpakai"    =>(string)$result[$j]->d_ruang_ajuanpakai,
									"i_rapat_nippesan"      =>(string)$result[$j]->i_rapat_nippesan,
									"d_rapat_awalpakai"     =>(string)$result[$j]->d_rapat_awalpakai,
									"d_rapat_akhirpakai"    =>(string)$result[$j]->d_rapat_akhirpakai,
									"i_ruang_usulan"        =>(string)$result[$j]->i_ruang_usulan,
									"i_ruang"           	=>(string)$result[$j]->i_ruang,
									"c_rapat_statsetuju"    =>(string)$result[$j]->c_rapat_statsetuju,
									"n_peg"    		=>(string)$result[$j]->n_peg,
									"i_orgp"    		=>(string)$result[$j]->i_orgp,
									"n_jabatan"    		=>(string)$result[$j]->n_jabatan,
									"realruang"            	=>(string)$result[$j]->realruang,
									"d_rapat_pesan"         =>(string)$result[$j]->d_rapat_pesan
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
	public function getInfoBlmSetujuRuangRapatList($tglGuna) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$where[]=$tglGuna;
			$where[]=$tglGuna;
			$where[]=$tglGuna;
			$query="select i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , d_rapat_awalpakai ,d_rapat_akhirpakai ,i_ruang_usulan,i_ruang ,c_rapat_statsetuju ,n_peg,n_jabatan,b.i_orgb as i_orgp".
				",(CASE WHEN c_rapat_statsetuju ='U' THEN i_ruang_usulan WHEN c_rapat_statsetuju ='Y' THEN i_ruang END) as realruang,d_rapat_pesan ".
				" FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b ".
				" where i_peg_nip = i_rapat_nippesan and (c_rapat_statsetuju ='' or c_rapat_statsetuju is null) and d_rapat_pesan = ?";				
			$query=$query." union select i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , d_rapat_awalpakai ,d_rapat_akhirpakai ,i_ruang_usulan,i_ruang ,c_rapat_statsetuju ,n_peg,n_jabatan,b.i_orgb as i_orgp".
				",(CASE WHEN c_rapat_statsetuju ='U' THEN i_ruang_usulan WHEN c_rapat_statsetuju ='Y' THEN i_ruang END) as realruang,d_rapat_pesan ".
				" FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b ".
				" where i_peg_nip = i_rapat_nippesan and c_rapat_statsetuju ='U' and (c_rapat_statjwbusul='' or c_rapat_statjwbusul is null) and d_rapat_pesan = ? ";
			$query=$query." union select i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , d_rapat_awalpakai ,d_rapat_akhirpakai ,i_ruang_usulan,i_ruang ,c_rapat_statsetuju ,n_peg,n_jabatan,b.i_orgb as i_orgp".
				",(CASE WHEN c_rapat_statsetuju ='U' THEN i_ruang_usulan WHEN c_rapat_statsetuju ='Y' THEN i_ruang END) as realruang,d_rapat_pesan ".
				" FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b ".
				" where i_peg_nip = i_rapat_nippesan and c_rapat_statsetuju ='Y' and (c_rapat_statsetuju1='' or c_rapat_statsetuju1 is null)".
				" and (c_rapat_konsumsipagi='Y' or c_rapat_konsumsisiang='Y' or c_rapat_makansiang='Y' or c_rapat_makanmalam='Y') and d_rapat_pesan = ? ";			
			//echo "<br>Querynya :".$query."<br>";
			$result = $db->fetchAll($query,$where);
			$jmlResult = count($result);
			if($jmlResult > 0){
	 			for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_ruang_ajuanpakai"     =>(string)$result[$j]->i_ruang_ajuanpakai,
									"d_ruang_ajuanpakai"    =>(string)$result[$j]->d_ruang_ajuanpakai,
									"i_rapat_nippesan"      =>(string)$result[$j]->i_rapat_nippesan,
									"d_rapat_awalpakai"     =>(string)$result[$j]->d_rapat_awalpakai,
									"d_rapat_akhirpakai"    =>(string)$result[$j]->d_rapat_akhirpakai,
									"i_ruang_usulan"        =>(string)$result[$j]->i_ruang_usulan,
									"i_ruang"           	=>(string)$result[$j]->i_ruang,
									"c_rapat_statsetuju"    =>(string)$result[$j]->c_rapat_statsetuju,
									"n_peg"    		=>(string)$result[$j]->n_peg,
									"i_orgp"    		=>(string)$result[$j]->i_orgp,
									"n_jabatan"    		=>(string)$result[$j]->n_jabatan,
									"realruang"            	=>(string)$result[$j]->realruang,
									"d_rapat_pesan"         =>(string)$result[$j]->d_rapat_pesan
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

	public function getInfoTdkSetujuRuangRapatAllList() {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$query="select i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , d_rapat_awalpakai ,d_rapat_akhirpakai ,i_ruang_usulan,i_ruang ,c_rapat_statsetuju ,n_peg,n_jabatan,b.i_orgb as i_orgp".
				",(CASE WHEN c_rapat_statsetuju ='U' THEN i_ruang_usulan WHEN c_rapat_statsetuju ='Y' THEN i_ruang END) as realruang,d_rapat_pesan ".
				" FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b ".
				" where i_peg_nip = i_rapat_nippesan and (c_rapat_statsetuju ='T' or c_rapat_statsetuju1 ='T' or c_rapat_statjwbusul='T')";
			//echo "<br>Querynya :".$query."<br>";
			$result = $db->fetchAll($query);
			$jmlResult = count($result);
			if($jmlResult > 0){
	 			for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_ruang_ajuanpakai"     =>(string)$result[$j]->i_ruang_ajuanpakai,
									"d_ruang_ajuanpakai"    =>(string)$result[$j]->d_ruang_ajuanpakai,
									"i_rapat_nippesan"      =>(string)$result[$j]->i_rapat_nippesan,
									"d_rapat_awalpakai"     =>(string)$result[$j]->d_rapat_awalpakai,
									"d_rapat_akhirpakai"    =>(string)$result[$j]->d_rapat_akhirpakai,
									"i_ruang_usulan"        =>(string)$result[$j]->i_ruang_usulan,
									"i_ruang"           	=>(string)$result[$j]->i_ruang,
									"c_rapat_statsetuju"    =>(string)$result[$j]->c_rapat_statsetuju,
									"n_peg"    		=>(string)$result[$j]->n_peg,
									"i_orgp"    		=>(string)$result[$j]->i_orgp,
									"n_jabatan"    		=>(string)$result[$j]->n_jabatan,
									"realruang"            	=>(string)$result[$j]->realruang,
									"d_rapat_pesan"         =>(string)$result[$j]->d_rapat_pesan
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

	public function getInfoTdkSetujuRuangRapatList($tglGuna) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$where[]=$tglGuna;
			$where[]=$tglGuna;
			$where[]=$tglGuna;
			$query="select i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , d_rapat_awalpakai ,d_rapat_akhirpakai ,i_ruang_usulan,i_ruang ,c_rapat_statsetuju ,n_peg,n_jabatan,b.i_orgb as i_orgp".
				",(CASE WHEN c_rapat_statsetuju ='U' THEN i_ruang_usulan WHEN c_rapat_statsetuju ='Y' THEN i_ruang END) as realruang,d_rapat_pesan ".
				" FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b ".
				" where i_peg_nip = i_rapat_nippesan and (c_rapat_statsetuju ='' or c_rapat_statsetuju is null) and d_rapat_pesan = ?";				
			$query=$query." union select i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , d_rapat_awalpakai ,d_rapat_akhirpakai ,i_ruang_usulan,i_ruang ,c_rapat_statsetuju ,n_peg,n_jabatan,b.i_orgb as i_orgp".
				",(CASE WHEN c_rapat_statsetuju ='U' THEN i_ruang_usulan WHEN c_rapat_statsetuju ='Y' THEN i_ruang END) as realruang,d_rapat_pesan ".
				" FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b ".
				" where i_peg_nip = i_rapat_nippesan and c_rapat_statsetuju ='U' and (c_rapat_statjwbusul='' or c_rapat_statjwbusul is null) and d_rapat_pesan = ? ";
			$query=$query." union select i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , d_rapat_awalpakai ,d_rapat_akhirpakai ,i_ruang_usulan,i_ruang ,c_rapat_statsetuju ,n_peg,n_jabatan,b.i_orgb as i_orgp".
				",(CASE WHEN c_rapat_statsetuju ='U' THEN i_ruang_usulan WHEN c_rapat_statsetuju ='Y' THEN i_ruang END) as realruang,d_rapat_pesan ".
				" FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b ".
				" where i_peg_nip = i_rapat_nippesan and c_rapat_statsetuju ='Y' and (c_rapat_statsetuju1='' or c_rapat_statsetuju1 is null)".
				" and (c_rapat_konsumsipagi='Y' or c_rapat_konsumsisiang='Y' or c_rapat_makansiang='Y' or c_rapat_makanmalam='Y') and d_rapat_pesan = ? ";			
			//echo "<br>Querynya :".$query."<br>";
			$result = $db->fetchAll($query,$where);
			$jmlResult = count($result);
			if($jmlResult > 0){
	 			for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_ruang_ajuanpakai"     =>(string)$result[$j]->i_ruang_ajuanpakai,
									"d_ruang_ajuanpakai"    =>(string)$result[$j]->d_ruang_ajuanpakai,
									"i_rapat_nippesan"      =>(string)$result[$j]->i_rapat_nippesan,
									"d_rapat_awalpakai"     =>(string)$result[$j]->d_rapat_awalpakai,
									"d_rapat_akhirpakai"    =>(string)$result[$j]->d_rapat_akhirpakai,
									"i_ruang_usulan"        =>(string)$result[$j]->i_ruang_usulan,
									"i_ruang"           	=>(string)$result[$j]->i_ruang,
									"c_rapat_statsetuju"    =>(string)$result[$j]->c_rapat_statsetuju,
									"n_peg"    		=>(string)$result[$j]->n_peg,
									"i_orgp"    		=>(string)$result[$j]->i_orgp,
									"n_jabatan"    		=>(string)$result[$j]->n_jabatan,
									"realruang"            	=>(string)$result[$j]->realruang,
									"d_rapat_pesan"         =>(string)$result[$j]->d_rapat_pesan
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

	public function getInfoPerubahanRuangRapatAllList() {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$query="select i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , d_rapat_awalpakai ,d_rapat_akhirpakai ,i_ruang_usulan,i_ruang ,c_rapat_statsetuju ,n_peg,n_jabatan,b.i_orgb as i_orgp".
				",(CASE WHEN c_rapat_statsetuju ='U' THEN i_ruang_usulan WHEN c_rapat_statsetuju ='Y' THEN i_ruang END) as realruang,d_rapat_pesan ".
				" FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b ".
				" where i_peg_nip = i_rapat_nippesan and c_rapat_statsetuju ='U' ";							
			//echo "<br>Querynya :".$query."<br>";
			$result = $db->fetchAll($query);
			$jmlResult = count($result);
			if($jmlResult > 0){
	 			for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_ruang_ajuanpakai"     =>(string)$result[$j]->i_ruang_ajuanpakai,
									"d_ruang_ajuanpakai"    =>(string)$result[$j]->d_ruang_ajuanpakai,
									"i_rapat_nippesan"      =>(string)$result[$j]->i_rapat_nippesan,
									"d_rapat_awalpakai"     =>(string)$result[$j]->d_rapat_awalpakai,
									"d_rapat_akhirpakai"    =>(string)$result[$j]->d_rapat_akhirpakai,
									"i_ruang_usulan"        =>(string)$result[$j]->i_ruang_usulan,
									"i_ruang"           	=>(string)$result[$j]->i_ruang,
									"c_rapat_statsetuju"    =>(string)$result[$j]->c_rapat_statsetuju,
									"n_peg"    		=>(string)$result[$j]->n_peg,
									"i_orgp"    		=>(string)$result[$j]->i_orgp,
									"n_jabatan"    		=>(string)$result[$j]->n_jabatan,
									"realruang"            	=>(string)$result[$j]->realruang,
									"d_rapat_pesan"         =>(string)$result[$j]->d_rapat_pesan
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
	public function getInfoPerubahanRuangRapatList($tglGuna) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$query="select i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , d_rapat_awalpakai ,d_rapat_akhirpakai ,i_ruang_usulan,i_ruang ,c_rapat_statsetuju ,n_peg,n_jabatan,b.i_orgb as i_orgp".
				",(CASE WHEN c_rapat_statsetuju ='U' THEN i_ruang_usulan WHEN c_rapat_statsetuju ='Y' THEN i_ruang END) as realruang,d_rapat_pesan ".
				" FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b ".
				" where i_peg_nip = i_rapat_nippesan and c_rapat_statsetuju ='U'  and d_rapat_pesan = ? ";
			//echo "<br>Querynya :".$query."<br>";
			$result = $db->fetchAll($query,$tglGuna);
			$jmlResult = count($result);
			if($jmlResult > 0){
	 			for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_ruang_ajuanpakai"     =>(string)$result[$j]->i_ruang_ajuanpakai,
									"d_ruang_ajuanpakai"    =>(string)$result[$j]->d_ruang_ajuanpakai,
									"i_rapat_nippesan"      =>(string)$result[$j]->i_rapat_nippesan,
									"d_rapat_awalpakai"     =>(string)$result[$j]->d_rapat_awalpakai,
									"d_rapat_akhirpakai"    =>(string)$result[$j]->d_rapat_akhirpakai,
									"i_ruang_usulan"        =>(string)$result[$j]->i_ruang_usulan,
									"i_ruang"           	=>(string)$result[$j]->i_ruang,
									"c_rapat_statsetuju"    =>(string)$result[$j]->c_rapat_statsetuju,
									"n_peg"    		=>(string)$result[$j]->n_peg,
									"i_orgp"    		=>(string)$result[$j]->i_orgp,
									"n_jabatan"    		=>(string)$result[$j]->n_jabatan,
									"realruang"            	=>(string)$result[$j]->realruang,
									"d_rapat_pesan"         =>(string)$result[$j]->d_rapat_pesan
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
	//=====================================utk page======================================================================27 nop 07========
  public function getPengajuanRuangRapatListAll($pageNumber,$itemPerPage,$unitkr) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$where[] = $unitkr;
	     //echo "TEST CONN ".$db->getConnection()."<br>";
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_ajuan_pakai_ruang_tm");
		 }
		 else
		 {
			 $xLimit=$itemPerPage;
	  		 $xOffset=($pageNumber-1)*$itemPerPage;		
		     
			 $result = $db->fetchAll('SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,n_rapat_judul,
										q_rapat_peserta,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai,	
										i_ruang,i_rapat_nippimpin,i_rapat_nippesan
										FROM e_ast_ajuan_pakai_ruang_tm where and c_rapat_statsetuju is null',$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_ruang_ajuanpakai"           	=>(string)$result[$j]->i_ruang_ajuanpakai,
										"d_ruang_ajuanpakai"           	=>(string)$result[$j]->d_ruang_ajuanpakai,
										"n_rapat_judul"          	  	=>(string)$result[$j]->n_rapat_judul,
										"q_rapat_peserta"          	  	=>(string)$result[$j]->q_rapat_peserta,
										"d_rapat_pesan"           	  	=>(string)$result[$j]->d_rapat_pesan,
										"d_rapat_awalpakai"            	=>(string)$result[$j]->d_rapat_awalpakai,
										"d_rapat_akhirpakai"           	=>(string)$result[$j]->d_rapat_akhirpakai,
										"i_ruang"                      	=>(string)$result[$j]->i_ruang,
										"i_rapat_nippimpin"            	=>(string)$result[$j]->i_rapat_nippimpin,
										"i_rapat_nippesan"             	=>(string)$result[$j]->i_rapat_nippesan);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}

	public function getCariPengajuanRuangRapatListAll($pageNumber,$itemPerPage,$unitkr,$tglGuna) {
	   $status='A';
	   $nsw = '%'.$tglGuna.'%';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $where[] = $unitkr;
		 $where[] = $nsw;
		 
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_ajuan_pakai_ruang_tm");
		 }
		 else
		 {
			 $xLimit=$itemPerPage;
	  		 $xOffset=($pageNumber-1)*$itemPerPage;		
		     
			 $result = $db->fetchAll('SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,n_rapat_judul,
								 q_rapat_peserta,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai,	
								 i_ruang,i_rapat_nippimpin,i_rapat_nippesan
							     FROM e_ast_ajuan_pakai_ruang_tm where i_orgb=? and d_rapat_pesan like ?',$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_ruang_ajuanpakai"           	=>(string)$result[$j]->i_ruang_ajuanpakai,
										"d_ruang_ajuanpakai"           	=>(string)$result[$j]->d_ruang_ajuanpakai,
										"n_rapat_judul"          	  	=>(string)$result[$j]->n_rapat_judul,
										"q_rapat_peserta"          	  	=>(string)$result[$j]->q_rapat_peserta,
										"d_rapat_pesan"           	  	=>(string)$result[$j]->d_rapat_pesan,
										"d_rapat_awalpakai"            	=>(string)$result[$j]->d_rapat_awalpakai,
										"d_rapat_akhirpakai"           	=>(string)$result[$j]->d_rapat_akhirpakai,
										"i_ruang"                      	=>(string)$result[$j]->i_ruang,
										"i_rapat_nippimpin"            	=>(string)$result[$j]->i_rapat_nippimpin,
										"i_rapat_nippesan"             	=>(string)$result[$j]->i_rapat_nippesan);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
//=======================================================================================================================================	
	
}		
?>