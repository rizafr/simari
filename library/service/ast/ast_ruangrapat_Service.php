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
	   $nsw = '%'.$tglGuna.'%';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 //$where[] = $unitkr;
		 $where[] = $tglGuna;
		 $where[] = $tglGuna;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select a.i_ruang, 
									max(CASE WHEN d_rapat_awalpakai =8   THEN 'Y' END) as awal_8, 
									max(CASE WHEN d_rapat_awalpakai <=9 and d_rapat_akhirpakai > 9  THEN 'Y' END) as awal_9, 
									max(CASE WHEN d_rapat_awalpakai <=10 and d_rapat_akhirpakai > 10 THEN 'Y' END) as awal_10, 
									max(CASE WHEN d_rapat_awalpakai <=11 and d_rapat_akhirpakai > 11 THEN 'Y' END) as awal_11, 
									max(CASE WHEN d_rapat_awalpakai <=12 and d_rapat_akhirpakai > 12 THEN 'Y' END) as awal_12, 
									max(CASE WHEN d_rapat_awalpakai <=13 and d_rapat_akhirpakai > 13 THEN 'Y' END) as awal_13, 
									max(CASE WHEN d_rapat_awalpakai <=14 and d_rapat_akhirpakai > 14 THEN 'Y' END) as awal_14, 
									max(CASE WHEN d_rapat_awalpakai <=15 and d_rapat_akhirpakai > 15 THEN 'Y' END) as awal_15, 
									max(CASE WHEN d_rapat_awalpakai <=16 and d_rapat_akhirpakai > 16 THEN 'Y' END) as awal_16, 
									max(CASE WHEN d_rapat_awalpakai <=17 and d_rapat_akhirpakai > 17 THEN 'Y' END) as awal_17, 
									max(CASE WHEN d_rapat_awalpakai <=18 and d_rapat_akhirpakai > 18 THEN 'Y' END) as awal_18, 
									max(CASE WHEN d_rapat_awalpakai <=19 and d_rapat_akhirpakai > 19 THEN 'Y' END) as awal_19
									from(select i_orgb,i_ruang,d_rapat_awalpakai,d_rapat_akhirpakai 
									     from e_ast_ajuan_pakai_ruang_tm where 
										      d_rapat_pesan =? and (c_rapat_statsetuju = 'Y' or c_rapat_statjwbusul = 'Y')) a,
											  e_ast_ruangan_0_tr b
										where a.i_ruang=b.i_ruang 										
										group by a.i_ruang
									UNION  
									SELECT
									   a.i_ruang, 
									   null  as awal_8, 
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
											            where a.i_ruang= b.i_ruang and b.d_rapat_pesan =?
														and (b.c_rapat_statsetuju = 'Y'  or b.c_rapat_statjwbusul = 'Y')
											           ) 
									   and (c_gedung_fungsi='2' or c_gedung_fungsi='3')
									   order by i_ruang",$where); 
								 
         $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_ruang"           =>(string)$result[$j]->i_ruang,
									"d_rapat_awalpakai" =>(string)$result[$j]->d_rapat_awalpakai,
									"awal_8"          	=>(string)$result[$j]->awal_8,
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
	
	public function getInformasiRuangList_Old($unitkr,$tglGuna) {
       $status='A';
	   $nsw = '%'.$tglGuna.'%';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 //$where[] = $unitkr;
		 $where[] = $tglGuna;
		 $where[] = $tglGuna;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select i_orgb,a.i_ruang, 
									max(CASE WHEN d_rapat_awalpakai =9   THEN 'Y' END) as awal_9, 
									max(CASE WHEN d_rapat_awalpakai <=10 and d_rapat_akhirpakai > 10 THEN 'Y' END) as awal_10, 
									max(CASE WHEN d_rapat_awalpakai <=11 and d_rapat_akhirpakai > 11 THEN 'Y' END) as awal_11, 
									max(CASE WHEN d_rapat_awalpakai <=12 and d_rapat_akhirpakai > 12 THEN 'Y' END) as awal_12, 
									max(CASE WHEN d_rapat_awalpakai <=13 and d_rapat_akhirpakai > 13 THEN 'Y' END) as awal_13, 
									max(CASE WHEN d_rapat_awalpakai <=14 and d_rapat_akhirpakai > 14 THEN 'Y' END) as awal_14, 
									max(CASE WHEN d_rapat_awalpakai <=15 and d_rapat_akhirpakai > 15 THEN 'Y' END) as awal_15, 
									max(CASE WHEN d_rapat_awalpakai <=16 and d_rapat_akhirpakai > 16 THEN 'Y' END) as awal_16, 
									max(CASE WHEN d_rapat_awalpakai <=17 and d_rapat_akhirpakai > 17 THEN 'Y' END) as awal_17, 
									max(CASE WHEN d_rapat_awalpakai <=18 and d_rapat_akhirpakai > 18 THEN 'Y' END) as awal_18, 
									max(CASE WHEN d_rapat_awalpakai <=19 and d_rapat_akhirpakai > 19 THEN 'Y' END) as awal_19
									from(select i_orgb,i_ruang,d_rapat_awalpakai,d_rapat_akhirpakai from e_ast_ajuan_pakai_ruang_tm where 
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
	//untuk informasi ===================================================
	public function getInfoRuangList($unitkr) {
       $status='A';
	   $nsw = '%'.$tglGuna.'%';
	   $dpesan = date("Y-m-d");
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $where[] = $unitkr;
		 $where[] = $dpesan;
		 $where[] = $unitkr;
		 $where[] = $dpesan;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select i_orgb,a.i_ruang, d_rapat_pesan,
									max(CASE WHEN d_rapat_awalpakai =8   THEN 'Y' END) as awal_8, 
									max(CASE WHEN d_rapat_awalpakai <=9 and d_rapat_akhirpakai > 9 THEN 'Y' END) as awal_9, 
									max(CASE WHEN d_rapat_awalpakai <=10 and d_rapat_akhirpakai > 10 THEN 'Y' END) as awal_10, 
									max(CASE WHEN d_rapat_awalpakai <=11 and d_rapat_akhirpakai > 11 THEN 'Y' END) as awal_11, 
									max(CASE WHEN d_rapat_awalpakai <=12 and d_rapat_akhirpakai > 12 THEN 'Y' END) as awal_12, 
									max(CASE WHEN d_rapat_awalpakai <=13 and d_rapat_akhirpakai > 13 THEN 'Y' END) as awal_13, 
									max(CASE WHEN d_rapat_awalpakai <=14 and d_rapat_akhirpakai > 14 THEN 'Y' END) as awal_14, 
									max(CASE WHEN d_rapat_awalpakai <=15 and d_rapat_akhirpakai > 15 THEN 'Y' END) as awal_15, 
									max(CASE WHEN d_rapat_awalpakai <=16 and d_rapat_akhirpakai > 16 THEN 'Y' END) as awal_16, 
									max(CASE WHEN d_rapat_awalpakai <=17 and d_rapat_akhirpakai > 17 THEN 'Y' END) as awal_17, 
									max(CASE WHEN d_rapat_awalpakai <=18 and d_rapat_akhirpakai > 18 THEN 'Y' END) as awal_18, 
									max(CASE WHEN d_rapat_awalpakai <=19 and d_rapat_akhirpakai > 19 THEN 'Y' END) as awal_19
									from(select i_orgb,i_ruang,d_rapat_awalpakai,d_rapat_akhirpakai,d_rapat_pesan from e_ast_ajuan_pakai_ruang_tm where 
										i_orgb=? and d_rapat_pesan >=? ) a, e_ast_ruangan_0_tr b
										where a.i_ruang=b.i_ruang 
										group by i_orgb,a.i_ruang,a.d_rapat_pesan

									UNION  SELECT
									   null as i_orgb,
									   a.i_ruang, 
									   null  as d_rapat_pesan,
									   null  as awal_8, 
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
											  where a.i_ruang= b.i_ruang and i_orgb=? and b.d_rapat_pesan >= ?) and c_gedung_fungsi='2'
									   order by i_ruang",$where); 
								 
         $jmlResult = count($result);
		//echo '$jmlResult'.$jmlResult;
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb"           	=>(string)$result[$j]->i_orgb,
									"i_ruang"           =>(string)$result[$j]->i_ruang,
									"d_rapat_pesan"           =>(string)$result[$j]->d_rapat_pesan,
									"d_rapat_awalpakai" =>(string)$result[$j]->d_rapat_awalpakai,
									"awal_8"          	=>(string)$result[$j]->awal_8,
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
	
	public function getCariInfoRuangList($unitkr,$tglGuna) {
       $status='A';
	   $nsw = '%'.$tglGuna.'%';
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $where[] = $unitkr;
		 $where[] = $tglGuna;
		 $where[] = $unitkr;
		 $where[] = $tglGuna;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select i_orgb,a.i_ruang, d_rapat_pesan,
									max(CASE WHEN d_rapat_awalpakai =8  THEN 'Y' END) as awal_8 
									max(CASE WHEN d_rapat_awalpakai <=9and d_rapat_akhirpakai > 9 THEN 'Y' END) as awal_9, 
									max(CASE WHEN d_rapat_awalpakai <=10 and d_rapat_akhirpakai > 10 THEN 'Y' END) as awal_10, 
									max(CASE WHEN d_rapat_awalpakai <=11 and d_rapat_akhirpakai > 11 THEN 'Y' END) as awal_11, 
									max(CASE WHEN d_rapat_awalpakai <=12 and d_rapat_akhirpakai > 12 THEN 'Y' END) as awal_12, 
									max(CASE WHEN d_rapat_awalpakai <=13 and d_rapat_akhirpakai > 13 THEN 'Y' END) as awal_13, 
									max(CASE WHEN d_rapat_awalpakai <=14 and d_rapat_akhirpakai > 14 THEN 'Y' END) as awal_14, 
									max(CASE WHEN d_rapat_awalpakai <=15 and d_rapat_akhirpakai > 15 THEN 'Y' END) as awal_15, 
									max(CASE WHEN d_rapat_awalpakai <=16 and d_rapat_akhirpakai > 16 THEN 'Y' END) as awal_16, 
									max(CASE WHEN d_rapat_awalpakai <=17 and d_rapat_akhirpakai > 17 THEN 'Y' END) as awal_17, 
									max(CASE WHEN d_rapat_awalpakai <=18 and d_rapat_akhirpakai > 18 THEN 'Y' END) as awal_18, 
									max(CASE WHEN d_rapat_awalpakai <=19 and d_rapat_akhirpakai > 19 THEN 'Y' END) as awal_19
									from(select i_orgb,i_ruang,d_rapat_awalpakai,d_rapat_akhirpakai,d_rapat_pesan from e_ast_ajuan_pakai_ruang_tm where 
										i_orgb=? and d_rapat_pesan =? ) a, e_ast_ruangan_0_tr b
										where a.i_ruang=b.i_ruang 
										group by i_orgb,a.i_ruang,a.d_rapat_pesan

									UNION  SELECT
									   null as i_orgb,
									   a.i_ruang, 
									   null as d_rapat_pesan,
									   null  as awal_8, 
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
											  where a.i_ruang= b.i_ruang and i_orgb=? and b.d_rapat_pesan =?) and c_gedung_fungsi='2'
									   order by i_ruang",$where); 
								 
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb"           	=>(string)$result[$j]->i_orgb,
									"i_ruang"           =>(string)$result[$j]->i_ruang,
									"d_rapat_awalpakai" =>(string)$result[$j]->d_rapat_awalpakai,
									"d_rapat_pesan" 	=>(string)$result[$j]->d_rapat_pesan,
									"awal_8"          	=>(string)$result[$j]->awal_8,
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
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $where[] = $unitkr;
		 $where[] = $tglGuna;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select i_orgb,i_ruang, d_rapat_awalpakai,
									max(CASE WHEN d_rapat_awalpakai =8 THEN 'Y' END) as awal_8, 
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
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb"           	=>(string)$result[$j]->i_orgb,
									"i_ruang"           =>(string)$result[$j]->i_ruang,
									"d_rapat_awalpakai" =>(string)$result[$j]->d_rapat_awalpakai,
									"awal_8"          	=>(string)$result[$j]->awal_8,
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
							   "d_rapat_awalasli" 			=>$data['jam'],
							   "d_rapat_akhirasli" 			=>$data['akhirpakai'],
							   "i_ruang_asli" 				=>$data['ruang'],
							   "i_entry"       				=>$data['nuser'],
						       "d_entry"       				=>date("Y-m-d"));
	    
		
		
 		 $db->insert('e_ast_ajuan_pakai_ruang_tm',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
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
	     $atk_dtl_parm = array("i_orgb"  					=>$data['unitkr'],
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
							   "d_rapat_awalasli" 			=>$data['jam'],
							   "d_rapat_akhirasli" 			=>$data['akhirpakai'],
							   "i_ruang_asli" 				=>$data['ruang'],
							   "i_entry"       				=>$data['nuser'],
						       "d_entry"       				=>date("Y-m-d"));
							   
							   
	     $where[] = "i_ruang_ajuanpakai  =  '".trim($data['noRapat'])."'";
	     $db->update('e_ast_ajuan_pakai_ruang_tm',$atk_dtl_parm, $where);
		 
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal';
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
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	//========================09 nop 2007 ========================================
	public function getInfoSetujuRuangRapatListA($unitkr) {
       
	   $dpesan = date("Y-m-d");
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $where[] = $unitkr;
		 $where[] = $dpesan;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select i_ruang_ajuanpakai, d_ruang_ajuanpakai, d_rapat_pesan,
									i_rapat_nippesan , d_rapat_awalpakai ,d_rapat_akhirpakai ,
									i_ruang_usulan,i_ruang ,c_rapat_statsetuju ,n_peg,n_jabatan,a.i_orgb as i_orgp,
									(CASE WHEN c_rapat_statsetuju ='U' THEN i_ruang_usulan
									      WHEN c_rapat_statsetuju ='Y' THEN i_ruang END) as realruang
									FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b
									where i_peg_nip = i_rapat_nippesan  and a.i_orgb = ?
									and (c_rapat_statsetuju ='U' or c_rapat_statsetuju ='Y')
									and d_rapat_pesan >= ?",$where); 
									
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_ruang_ajuanpakai"     =>(string)$result[$j]->i_ruang_ajuanpakai,
									"d_ruang_ajuanpakai"    =>(string)$result[$j]->d_ruang_ajuanpakai,
									"d_rapat_pesan"    		=>(string)$result[$j]->d_rapat_pesan,
									"i_rapat_nippesan"      =>(string)$result[$j]->i_rapat_nippesan,
									"d_rapat_awalpakai"     =>(string)$result[$j]->d_rapat_awalpakai,
									"d_rapat_akhirpakai"    =>(string)$result[$j]->d_rapat_akhirpakai,
									"i_ruang_usulan"        =>(string)$result[$j]->i_ruang_usulan,
									"i_ruang"           	=>(string)$result[$j]->i_ruang,
									"c_rapat_statsetuju"    =>(string)$result[$j]->c_rapat_statsetuju,
									"n_peg"    				=>(string)$result[$j]->n_peg,
									"i_orgp"    			=>(string)$result[$j]->i_orgp,
									"n_jabatan"    			=>(string)$result[$j]->n_jabatan,
									"realruang"            	=>(string)$result[$j]->realruang);
				
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	public function getCariInfoSetujuRuangRapatList($unitkr,$tglGuna) {
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $where[] = $unitkr;
		 $where[] = $tglGuna;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select i_ruang_ajuanpakai, d_ruang_ajuanpakai, d_rapat_pesan,
									i_rapat_nippesan , d_rapat_awalpakai ,d_rapat_akhirpakai ,
									i_ruang_usulan,i_ruang ,c_rapat_statsetuju ,n_peg,n_jabatan,b.i_orgb as i_orgp,
									(CASE WHEN c_rapat_statsetuju ='U' THEN i_ruang_usulan
									      WHEN c_rapat_statsetuju ='Y' THEN i_ruang END) as realruang
									FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b
									where i_peg_nip = i_rapat_nippesan  and a.i_orgb = ?
									and (c_rapat_statsetuju ='U' or c_rapat_statsetuju ='Y')
									and d_rapat_pesan = ?",$where); 
									
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_ruang_ajuanpakai"     =>(string)$result[$j]->i_ruang_ajuanpakai,
									"d_ruang_ajuanpakai"    =>(string)$result[$j]->d_ruang_ajuanpakai,
									"d_rapat_pesan"    		=>(string)$result[$j]->d_rapat_pesan,
									"i_rapat_nippesan"      =>(string)$result[$j]->i_rapat_nippesan,
									"d_rapat_awalpakai"     =>(string)$result[$j]->d_rapat_awalpakai,
									"d_rapat_akhirpakai"    =>(string)$result[$j]->d_rapat_akhirpakai,
									"i_ruang_usulan"        =>(string)$result[$j]->i_ruang_usulan,
									"i_ruang"           	=>(string)$result[$j]->i_ruang,
									"c_rapat_statsetuju"    =>(string)$result[$j]->c_rapat_statsetuju,
									"n_peg"    				=>(string)$result[$j]->n_peg,
									"i_orgp"    			=>(string)$result[$j]->i_orgp,
									"n_jabatan"    			=>(string)$result[$j]->n_jabatan,
									"realruang"            	=>(string)$result[$j]->realruang);
				
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	public function getInfoBlmSetujuRuangRapatListA($unitkr) {
       $dpesan = date("Y-m-d");
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $where[] = $unitkr;
		 $where[] = $dpesan;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select i_ruang_ajuanpakai, d_ruang_ajuanpakai,d_rapat_pesan,
									i_rapat_nippesan , d_rapat_awalpakai ,d_rapat_akhirpakai ,
									i_ruang_usulan,i_ruang ,c_rapat_statsetuju ,n_peg,n_jabatan,b.i_orgb as i_orgp,
									(CASE WHEN c_rapat_statsetuju ='U' THEN i_ruang_usulan
									      WHEN c_rapat_statsetuju ='Y' THEN i_ruang END) as realruang
									FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b
									where i_peg_nip = i_rapat_nippesan  and a.i_orgb=?
									and (c_rapat_statsetuju = '' or c_rapat_statsetuju is null )
									and d_rapat_pesan >= ?",$where); 
									
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_ruang_ajuanpakai"     =>(string)$result[$j]->i_ruang_ajuanpakai,
									"d_ruang_ajuanpakai"    =>(string)$result[$j]->d_ruang_ajuanpakai,
									"d_rapat_pesan"    		=>(string)$result[$j]->d_rapat_pesan,
									"i_rapat_nippesan"      =>(string)$result[$j]->i_rapat_nippesan,
									"d_rapat_awalpakai"     =>(string)$result[$j]->d_rapat_awalpakai,
									"d_rapat_akhirpakai"    =>(string)$result[$j]->d_rapat_akhirpakai,
									"i_ruang_usulan"        =>(string)$result[$j]->i_ruang_usulan,
									"i_ruang"           	=>(string)$result[$j]->i_ruang,
									"c_rapat_statsetuju"    =>(string)$result[$j]->c_rapat_statsetuju,
									"n_peg"    				=>(string)$result[$j]->n_peg,
									"i_orgp"    			=>(string)$result[$j]->i_orgp,
									"n_jabatan"    			=>(string)$result[$j]->n_jabatan,
									"realruang"            	=>(string)$result[$j]->realruang);
				
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	public function getCariInfoBlmSetujuRuangRapatList($unitkr,$tglGuna) {
       $dpesan = date("Y-m-d");
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $where[] = $unitkr;
		 $where[] = $tglGuna;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select i_ruang_ajuanpakai, d_ruang_ajuanpakai, d_rapat_pesan,
									i_rapat_nippesan , d_rapat_awalpakai ,d_rapat_akhirpakai ,
									i_ruang_usulan,i_ruang ,c_rapat_statsetuju ,n_peg,n_jabatan,b.i_orgb as i_orgp,
									(CASE WHEN c_rapat_statsetuju ='U' THEN i_ruang_usulan
									      WHEN c_rapat_statsetuju ='Y' THEN i_ruang END) as realruang
									FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b
									where i_peg_nip = i_rapat_nippesan  and a.i_orgb=?
									and (c_rapat_statsetuju = '' or c_rapat_statsetuju is null )
									and d_rapat_pesan = ?",$where); 
									
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_ruang_ajuanpakai"     =>(string)$result[$j]->i_ruang_ajuanpakai,
									"d_ruang_ajuanpakai"    =>(string)$result[$j]->d_ruang_ajuanpakai,
									"i_rapat_nippesan"      =>(string)$result[$j]->i_rapat_nippesan,
									"d_rapat_pesan"     	=>(string)$result[$j]->d_rapat_pesan,
									"d_rapat_awalpakai"     =>(string)$result[$j]->d_rapat_awalpakai,
									"d_rapat_akhirpakai"    =>(string)$result[$j]->d_rapat_akhirpakai,
									"i_ruang_usulan"        =>(string)$result[$j]->i_ruang_usulan,
									"i_ruang"           	=>(string)$result[$j]->i_ruang,
									"c_rapat_statsetuju"    =>(string)$result[$j]->c_rapat_statsetuju,
									"n_peg"    				=>(string)$result[$j]->n_peg,
									"i_orgp"    			=>(string)$result[$j]->i_orgp,
									"n_jabatan"    			=>(string)$result[$j]->n_jabatan,
									"realruang"            	=>(string)$result[$j]->realruang);
				
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	//============================ 12-11-2007========================================
	public function getInfoPerubahanRuangRapatListA($unitkr) {
       $dpesan = date("Y-m-d");
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $where[] = $unitkr;
		 $where[] = $dpesan;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select i_ruang_ajuanpakai, d_ruang_ajuanpakai, i_ruang, d_rapat_awalpakai ,d_rapat_akhirpakai,
									i_ruang_usulan,c_rapat_statsetuju ,i_rapat_nippesan ,n_peg,n_jabatan,b.i_orgb as i_orgp,
									d_rapat_pesan, i_ruang_usulan, d_rapat_usul, d_rapat_usuljamawal,d_rapat_usuljamakh, 
									c_rapat_statjwbusul, d_rapat_jwbusul 
									FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b
									where i_peg_nip = i_rapat_nippesan  
									and c_rapat_statsetuju ='U' and a.i_orgb = ?  and d_rapat_pesan >= ?",$where); 
									
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_ruang_ajuanpakai"     =>(string)$result[$j]->i_ruang_ajuanpakai,
									"d_ruang_ajuanpakai"    =>(string)$result[$j]->d_ruang_ajuanpakai,
									"i_ruang"           	=>(string)$result[$j]->i_ruang,
									"d_rapat_awalpakai"     =>(string)$result[$j]->d_rapat_awalpakai,
									"d_rapat_akhirpakai"    =>(string)$result[$j]->d_rapat_akhirpakai,
									"i_ruang_usulan"        =>(string)$result[$j]->i_ruang_usulan,
									"c_rapat_statsetuju"    =>(string)$result[$j]->c_rapat_statsetuju,
									"i_rapat_nippesan"      =>(string)$result[$j]->i_rapat_nippesan,
									"n_peg"    				=>(string)$result[$j]->n_peg,
									"n_jabatan"    			=>(string)$result[$j]->n_jabatan,
									"i_orgp"    			=>(string)$result[$j]->i_orgp,
									"d_rapat_pesan"    		=>(string)$result[$j]->d_rapat_pesan,
									"i_ruang_usulan"    	=>(string)$result[$j]->i_ruang_usulan,
									"d_rapat_usul"    		=>(string)$result[$j]->d_rapat_usul,
									"d_rapat_usuljamawal"   =>(string)$result[$j]->d_rapat_usuljamawal,
									"d_rapat_usuljamakh"   =>(string)$result[$j]->d_rapat_usuljamakh,
									"c_rapat_statjwbusul"   =>(string)$result[$j]->c_rapat_statjwbusul,
									"d_rapat_jwbusul"    	=>(string)$result[$j]->d_rapat_jwbusul);
				
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	public function getCariInfoPerubahanRuangRapatList($unitkr,$tglGuna) {
       $dpesan = date("Y-m-d");
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $where[] = $unitkr;
		 $where[] = $tglGuna;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select i_ruang_ajuanpakai, d_ruang_ajuanpakai, i_ruang, d_rapat_awalpakai ,d_rapat_akhirpakai,
									i_ruang_usulan,c_rapat_statsetuju ,i_rapat_nippesan ,n_peg,n_jabatan,b.i_orgb as i_orgp,
									d_rapat_pesan, i_ruang_usulan, d_rapat_usul, d_rapat_usuljamawal,d_rapat_usuljamakh, 
									c_rapat_statjwbusul, d_rapat_jwbusul 
									FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b
									where i_peg_nip = i_rapat_nippesan  
									and c_rapat_statsetuju ='U' and a.i_orgb = ?  and d_rapat_pesan = ?",$where); 
									
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_ruang_ajuanpakai"     =>(string)$result[$j]->i_ruang_ajuanpakai,
									"d_ruang_ajuanpakai"    =>(string)$result[$j]->d_ruang_ajuanpakai,
									"i_ruang"           	=>(string)$result[$j]->i_ruang,
									"d_rapat_awalpakai"     =>(string)$result[$j]->d_rapat_awalpakai,
									"d_rapat_akhirpakai"    =>(string)$result[$j]->d_rapat_akhirpakai,
									"i_ruang_usulan"        =>(string)$result[$j]->i_ruang_usulan,
									"c_rapat_statsetuju"    =>(string)$result[$j]->c_rapat_statsetuju,
									"i_rapat_nippesan"      =>(string)$result[$j]->i_rapat_nippesan,
									"n_peg"    				=>(string)$result[$j]->n_peg,
									"n_jabatan"    			=>(string)$result[$j]->n_jabatan,
									"i_orgp"    			=>(string)$result[$j]->i_orgp,
									"d_rapat_pesan"    		=>(string)$result[$j]->d_rapat_pesan,
									"i_ruang_usulan"    	=>(string)$result[$j]->i_ruang_usulan,
									"d_rapat_usul"    		=>(string)$result[$j]->d_rapat_usul,
									"d_rapat_usuljamawal"   =>(string)$result[$j]->d_rapat_usuljamawal,
									"d_rapat_usuljamakh"   =>(string)$result[$j]->d_rapat_usuljamakh,
									"c_rapat_statjwbusul"   =>(string)$result[$j]->c_rapat_statjwbusul,
									"d_rapat_jwbusul"    	=>(string)$result[$j]->d_rapat_jwbusul);
				
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
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

//=====================================utk page======================================================================27 nop 07========
public function getPengajuanRuangRapatListAll($pageNumber,$itemPerPage,$nipKry) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$where[] = $nipKry;	     
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
		    // Ina : 17-06-2008 : Awal  : Difilter berdasarkan nip  pemohon
			//$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_ajuan_pakai_ruang_tm where i_orgb=? and c_rapat_statsetuju is null",$where);
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_ajuan_pakai_ruang_tm where i_rapat_nippesan=? and c_rapat_statsetuju is null",$where);
			// Ina : 17-06-2008 : Akhir
		 }
		 else
		 {
			 $xLimit=$itemPerPage;
	  		 $xOffset=($pageNumber-1)*$itemPerPage;		
		    
             // Ina : 17-06-2008 : Awal  : Difilter berdasarkan nip  pemohon 			
			 /**
			 $result = $db->fetchAll("SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,n_rapat_judul,
										q_rapat_peserta,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai,	
										i_ruang,i_rapat_nippimpin,i_rapat_nippesan
										FROM e_ast_ajuan_pakai_ruang_tm where i_orgb=? 
										and c_rapat_statsetuju is null
										ORDER BY i_ruang_ajuanpakai
										limit $xLimit offset $xOffset",$where); 
			**/							
										
              $result = $db->fetchAll("SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,n_rapat_judul,
										q_rapat_peserta,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai,	
										i_ruang,i_rapat_nippimpin,i_rapat_nippesan
										FROM e_ast_ajuan_pakai_ruang_tm where i_rapat_nippesan=? 
										and c_rapat_statsetuju is null
										ORDER BY i_ruang_ajuanpakai
										limit $xLimit offset $xOffset",$where); 										
			 // Ina : 17-06-2008 : Akhir
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

	public function getCariPengajuanRuangRapatListAll($pageNumber,$itemPerPage,$nipKry,$tglGuna ) {
	   $status='A';
	   $nsw = '%'.$tglGuna.'%';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $where[] = $nipKry;
		 $where[] = $nsw;
		 
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
		    // Ina : 17-06-2008 : Awal : List difilter berdasarakan nip pemohon
			//$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_ajuan_pakai_ruang_tm where i_orgb=? and d_rapat_pesan like ? and c_rapat_statsetuju is null",$where);
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_ajuan_pakai_ruang_tm where i_rapat_nippesan=? and d_rapat_pesan like ? and c_rapat_statsetuju is null",$where);
			// Ina : 17-06-2008 : Akhir
		 }
		 else
		 {
			 $xLimit=$itemPerPage;
	  		 $xOffset=($pageNumber-1)*$itemPerPage;		
		     // Ina : 17-06-2008 : Awal : List difilter berdasarakan nip pemohon
			 /**
			 $result = $db->fetchAll("SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,n_rapat_judul,
									q_rapat_peserta,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai,	
									i_ruang,i_rapat_nippimpin, B.n_peg as pemimpin, i_rapat_nippesan, C.n_peg as pemesan
									FROM e_ast_ajuan_pakai_ruang_tm A,
									e_sdm_pegawai_0_tm B,
									e_sdm_pegawai_0_tm C
									where A.i_orgb = ?  and d_rapat_pesan like ?
									and c_rapat_statsetuju is null
									and i_rapat_nippimpin = B.I_Peg_Nip
									and i_rapat_nippesan = C.I_Peg_Nip
									ORDER BY i_ruang_ajuanpakai
								 limit $xLimit offset $xOffset",$where); 								 
								 
			**/
			 $result = $db->fetchAll("SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,n_rapat_judul,
									q_rapat_peserta,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai,	
									i_ruang,i_rapat_nippimpin, B.n_peg as pemimpin, i_rapat_nippesan, C.n_peg as pemesan
									FROM e_ast_ajuan_pakai_ruang_tm A,
									e_sdm_pegawai_0_tm B,
									e_sdm_pegawai_0_tm C
									where A.i_rapat_nippesan = ?  and d_rapat_pesan like ?
									and c_rapat_statsetuju is null
									and i_rapat_nippimpin = B.I_Peg_Nip
									and i_rapat_nippesan = C.I_Peg_Nip
									ORDER BY i_ruang_ajuanpakai
								 limit $xLimit offset $xOffset",$where); 								 
			// Ina : 17-06-2008 : Akhir						
								 
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
										"pemimpin"            			=>(string)$result[$j]->pemimpin,
										"pemesan"             			=>(string)$result[$j]->pemesan,
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
	
	public function getDataPesan($tglRapat,$jamAwal,$jamAkhir,$ruang ) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $where[] = $jamAwal;
		 $where[] = $jamAkhir;
		 $where[] = $tglRapat;
		 $where[] = $ruang;
		 		 
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$hasilAkhir = $db->fetchOne("select count(*) 
											from e_ast_ajuan_pakai_ruang_tm 
											where (? between d_rapat_awalpakai and d_rapat_akhirpakai 
											or ? between d_rapat_awalpakai and d_rapat_akhirpakai )
											and d_rapat_pesan = ? 
											and i_ruang = ?	 ",$where);
											
			 
			return $hasilAkhir;
	   
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 0;
	   }
	}
	
	public function getDataByKey($tglRapat,$jamAwal,$jamAkhir,$ruang ) {
	//echo '$kode'.$kode;
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $where[] = $jamAwal;
		 $where[] = $jamAkhir;
		 $where[] = $tglRapat;
		 $where[] = $ruang;
		 
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchOne("select count(*) 
											from e_ast_ajuan_pakai_ruang_tm 
											where (? between d_rapat_awalpakai and d_rapat_akhirpakai 
											or ? between d_rapat_awalpakai and d_rapat_akhirpakai )
											and d_rapat_pesan = ? 
											and i_ruang = ?	 ",$where);
											
		 $returnValue = Zend_Json::encode(array($result));
	     return $returnValue;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
//=======================================================================================================================================	

public function getPegawaiListAll($pageNumber, $itemPerPage) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     //echo "TEST CONN ".$db->getConnection()."<br>";
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_sdm_pegawai_0_tm");
		 }
		 else
		 {
			 $xLimit=$itemPerPage;
	  		 $xOffset=($pageNumber-1)*$itemPerPage;		
		     
			 $result = $db->fetchAll("select * 
									  FROM e_sdm_pegawai_0_tm 
									  order by n_peg
									  limit $xLimit offset $xOffset");
			 
	         $jmlResult = count($result);
			 for ($j = 0; $j < $jmlResult; $j++) {
				$nmUnitKerja = $db->fetchCol('SELECT n_orgb FROM e_org_0_0_tm WHERE i_orgb = ?',$result[$j]->i_orgb);
				$dPenilaianAkhir = $db->fetchCol('SELECT d_peg_pnilaiakhir FROM e_sdm_dp3_pegawai_tm  WHERE i_peg_nip = ?',$result[$j]->i_peg_nip);
				$hasilAkhir[$j] = array("i_peg_nip"  =>(string)$result[$j]->i_peg_nip,
										"n_peg"  =>(string)$result[$j]->n_peg,
										"n_jabatan"  =>(string)$result[$j]->n_jabatan,
										"i_orgb"  =>(string)$result[$j]->i_orgb,
										"n_orgb"	=>$nmUnitKerja[0],
										"d_peg_pnilaiakhir" =>$dPenilaianAkhir[0]);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function getListPegawaiAll($pageNumber,$itemPerPage) {
		 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_sdm_pegawai_0_tm");
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT i_peg_nip,n_peg,n_jabatan,a.i_orgb,n_orgb 
									FROM e_sdm_pegawai_0_tm a, e_org_0_0_tm b
									where a.i_orgb=b.i_orgb
									ORDER BY i_peg_nip
									limit $xLimit offset $xOffset"); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_peg_nip"         =>(string)$result[$j]->i_peg_nip,
									"n_peg"           	 	=>(string)$result[$j]->n_peg,
									"n_jabatan"          	=>(string)$result[$j]->n_jabatan,
									"i_orgb"            	=>(string)$result[$j]->i_orgb,
									"n_orgb"            	=>(string)$result[$j]->n_orgb);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
//========================09 nop 2007 ========================================
	// Updated By Agung 22-11-2007
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
	public function getInformasiRuangListAll($pageNumber,$itemPerPage) {		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {			
		 
		 $hasilAkhir1 = $db->fetchAll("SELECT a.i_ruang,d_rapat_pesan,
									max(CASE WHEN d_rapat_awalpakai =8   THEN 'Y' END) as awal_8, 
									max(CASE WHEN d_rapat_awalpakai <=9 and d_rapat_akhirpakai > 9  THEN 'Y' END) as awal_9, 
									max(CASE WHEN d_rapat_awalpakai <=10 and d_rapat_akhirpakai > 10 THEN 'Y' END) as awal_10, 
									max(CASE WHEN d_rapat_awalpakai <=11 and d_rapat_akhirpakai > 11 THEN 'Y' END) as awal_11, 
									max(CASE WHEN d_rapat_awalpakai <=12 and d_rapat_akhirpakai > 12 THEN 'Y' END) as awal_12, 
									max(CASE WHEN d_rapat_awalpakai <=13 and d_rapat_akhirpakai > 13 THEN 'Y' END) as awal_13, 
									max(CASE WHEN d_rapat_awalpakai <=14 and d_rapat_akhirpakai > 14 THEN 'Y' END) as awal_14, 
									max(CASE WHEN d_rapat_awalpakai <=15 and d_rapat_akhirpakai > 15 THEN 'Y' END) as awal_15, 
									max(CASE WHEN d_rapat_awalpakai <=16 and d_rapat_akhirpakai > 16 THEN 'Y' END) as awal_16, 
									max(CASE WHEN d_rapat_awalpakai <=17 and d_rapat_akhirpakai > 17 THEN 'Y' END) as awal_17, 
									max(CASE WHEN d_rapat_awalpakai <=18 and d_rapat_akhirpakai > 18 THEN 'Y' END) as awal_18, 
									max(CASE WHEN d_rapat_awalpakai <=19 and d_rapat_akhirpakai > 19 THEN 'Y' END) as awal_19
									from(select i_orgb,i_ruang,d_rapat_awalpakai,d_rapat_akhirpakai,d_rapat_pesan 
									     from e_ast_ajuan_pakai_ruang_tm where (c_rapat_statsetuju = 'Y' or c_rapat_statjwbusul = 'Y')) a,
											  e_ast_ruangan_0_tr b
										where a.i_ruang=b.i_ruang 										
										group by a.i_ruang,d_rapat_pesan
									UNION  
									SELECT a.i_ruang, null as d_rapat_pesan,
									   null  as awal_8, 
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
											            where a.i_ruang= b.i_ruang 
														and (b.c_rapat_statsetuju = 'Y' or b.c_rapat_statjwbusul = 'Y')
											           ) 
									   and (c_gedung_fungsi='2' or c_gedung_fungsi='3')
									   order by i_ruang,d_rapat_pesan");
		 							  
			$jmlRec = count($hasilAkhir1);			
			$hasilAkhir = $jmlRec ; //+ $hasilAkhir2 ;
			
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 $result = $db->fetchAll("SELECT a.i_ruang,d_rapat_pesan,
									max(CASE WHEN d_rapat_awalpakai =8   THEN 'Y' END) as awal_8, 
									max(CASE WHEN d_rapat_awalpakai <=9 and d_rapat_akhirpakai > 9  THEN 'Y' END) as awal_9, 
									max(CASE WHEN d_rapat_awalpakai <=10 and d_rapat_akhirpakai > 10 THEN 'Y' END) as awal_10, 
									max(CASE WHEN d_rapat_awalpakai <=11 and d_rapat_akhirpakai > 11 THEN 'Y' END) as awal_11, 
									max(CASE WHEN d_rapat_awalpakai <=12 and d_rapat_akhirpakai > 12 THEN 'Y' END) as awal_12, 
									max(CASE WHEN d_rapat_awalpakai <=13 and d_rapat_akhirpakai > 13 THEN 'Y' END) as awal_13, 
									max(CASE WHEN d_rapat_awalpakai <=14 and d_rapat_akhirpakai > 14 THEN 'Y' END) as awal_14, 
									max(CASE WHEN d_rapat_awalpakai <=15 and d_rapat_akhirpakai > 15 THEN 'Y' END) as awal_15, 
									max(CASE WHEN d_rapat_awalpakai <=16 and d_rapat_akhirpakai > 16 THEN 'Y' END) as awal_16, 
									max(CASE WHEN d_rapat_awalpakai <=17 and d_rapat_akhirpakai > 17 THEN 'Y' END) as awal_17, 
									max(CASE WHEN d_rapat_awalpakai <=18 and d_rapat_akhirpakai > 18 THEN 'Y' END) as awal_18, 
									max(CASE WHEN d_rapat_awalpakai <=19 and d_rapat_akhirpakai > 19 THEN 'Y' END) as awal_19
									from(select i_orgb,i_ruang,d_rapat_awalpakai,d_rapat_akhirpakai,d_rapat_pesan 
									     from e_ast_ajuan_pakai_ruang_tm where (c_rapat_statsetuju = 'Y' or c_rapat_statjwbusul = 'Y')) a,
											  e_ast_ruangan_0_tr b
										where a.i_ruang=b.i_ruang 										
										group by a.i_ruang,d_rapat_pesan
									UNION  
									SELECT a.i_ruang, null as d_rapat_pesan,
									   null  as awal_8, 
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
											            where a.i_ruang= b.i_ruang 
														and (b.c_rapat_statsetuju = 'Y' or b.c_rapat_statjwbusul = 'Y')
											           ) 
									   and (c_gedung_fungsi='2' or c_gedung_fungsi='3')
									   order by i_ruang,d_rapat_pesan
									   limit $xLimit offset $xOffset"); 
									   
			 
			 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array(
									"i_ruang"           =>(string)$result[$j]->i_ruang,
									"d_rapat_pesan"	=>(string)$result[$j]->d_rapat_pesan,
									"d_rapat_awalpakai" =>(string)$result[$j]->d_rapat_awalpakai,
									"awal_8"          	=>(string)$result[$j]->awal_8,
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
	     return 'Data tidak ada <br>';
	   }
	}	 
	
	// Ina : 25-06-2008 : Awal :Informasi Penggunaan Ruangan Yg Telah Disetujui Berdasarkna tanggal Pesan
	public function getInformasiRuangList_ByTanggal($pageNumber,$itemPerPage,$tglGuna) {		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');		
		$nsw = '%'.$tglGuna.'%';
		
		try {
		 $where[]=$nsw; 
		 $where[]=$nsw; 
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {			
		 
		 $hasilAkhir1 = $db->fetchAll("SELECT a.i_ruang,d_rapat_pesan,
									max(CASE WHEN d_rapat_awalpakai =8   THEN 'Y' END) as awal_8, 
									max(CASE WHEN d_rapat_awalpakai <=9 and d_rapat_akhirpakai > 9  THEN 'Y' END) as awal_9, 
									max(CASE WHEN d_rapat_awalpakai <=10 and d_rapat_akhirpakai > 10 THEN 'Y' END) as awal_10, 
									max(CASE WHEN d_rapat_awalpakai <=11 and d_rapat_akhirpakai > 11 THEN 'Y' END) as awal_11, 
									max(CASE WHEN d_rapat_awalpakai <=12 and d_rapat_akhirpakai > 12 THEN 'Y' END) as awal_12, 
									max(CASE WHEN d_rapat_awalpakai <=13 and d_rapat_akhirpakai > 13 THEN 'Y' END) as awal_13, 
									max(CASE WHEN d_rapat_awalpakai <=14 and d_rapat_akhirpakai > 14 THEN 'Y' END) as awal_14, 
									max(CASE WHEN d_rapat_awalpakai <=15 and d_rapat_akhirpakai > 15 THEN 'Y' END) as awal_15, 
									max(CASE WHEN d_rapat_awalpakai <=16 and d_rapat_akhirpakai > 16 THEN 'Y' END) as awal_16, 
									max(CASE WHEN d_rapat_awalpakai <=17 and d_rapat_akhirpakai > 17 THEN 'Y' END) as awal_17, 
									max(CASE WHEN d_rapat_awalpakai <=18 and d_rapat_akhirpakai > 18 THEN 'Y' END) as awal_18, 
									max(CASE WHEN d_rapat_awalpakai <=19 and d_rapat_akhirpakai > 19 THEN 'Y' END) as awal_19
									from(select i_orgb,i_ruang,d_rapat_awalpakai,d_rapat_akhirpakai,d_rapat_pesan 
									     from e_ast_ajuan_pakai_ruang_tm 										 
										 where d_rapat_pesan like ? 
										 and (c_rapat_statsetuju = 'Y' or c_rapat_statjwbusul = 'Y')) a, e_ast_ruangan_0_tr b
									where a.i_ruang=b.i_ruang 										
									group by a.i_ruang,d_rapat_pesan
									UNION  
									SELECT a.i_ruang, null as d_rapat_pesan,
									   null  as awal_8, 
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
											            where a.i_ruang= b.i_ruang 
														and d_rapat_pesan like ?
														and (b.c_rapat_statsetuju = 'Y' or b.c_rapat_statjwbusul = 'Y')
											           ) 
									   and (c_gedung_fungsi='2' or c_gedung_fungsi='3')
									   order by i_ruang,d_rapat_pesan",$where);
		 							  
			$jmlRec = count($hasilAkhir1);			
			$hasilAkhir = $jmlRec ; //+ $hasilAkhir2 ;
			
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 $result = $db->fetchAll("SELECT a.i_ruang,d_rapat_pesan,
									max(CASE WHEN d_rapat_awalpakai =8   THEN 'Y' END) as awal_8, 
									max(CASE WHEN d_rapat_awalpakai <=9 and d_rapat_akhirpakai > 9  THEN 'Y' END) as awal_9, 
									max(CASE WHEN d_rapat_awalpakai <=10 and d_rapat_akhirpakai > 10 THEN 'Y' END) as awal_10, 
									max(CASE WHEN d_rapat_awalpakai <=11 and d_rapat_akhirpakai > 11 THEN 'Y' END) as awal_11, 
									max(CASE WHEN d_rapat_awalpakai <=12 and d_rapat_akhirpakai > 12 THEN 'Y' END) as awal_12, 
									max(CASE WHEN d_rapat_awalpakai <=13 and d_rapat_akhirpakai > 13 THEN 'Y' END) as awal_13, 
									max(CASE WHEN d_rapat_awalpakai <=14 and d_rapat_akhirpakai > 14 THEN 'Y' END) as awal_14, 
									max(CASE WHEN d_rapat_awalpakai <=15 and d_rapat_akhirpakai > 15 THEN 'Y' END) as awal_15, 
									max(CASE WHEN d_rapat_awalpakai <=16 and d_rapat_akhirpakai > 16 THEN 'Y' END) as awal_16, 
									max(CASE WHEN d_rapat_awalpakai <=17 and d_rapat_akhirpakai > 17 THEN 'Y' END) as awal_17, 
									max(CASE WHEN d_rapat_awalpakai <=18 and d_rapat_akhirpakai > 18 THEN 'Y' END) as awal_18, 
									max(CASE WHEN d_rapat_awalpakai <=19 and d_rapat_akhirpakai > 19 THEN 'Y' END) as awal_19
									from(select i_orgb,i_ruang,d_rapat_awalpakai,d_rapat_akhirpakai,d_rapat_pesan 
									     from e_ast_ajuan_pakai_ruang_tm 
										 where d_rapat_pesan like ? 
										 and (c_rapat_statsetuju = 'Y' or c_rapat_statjwbusul = 'Y')) a, e_ast_ruangan_0_tr b
									where a.i_ruang=b.i_ruang 										
									group by a.i_ruang,d_rapat_pesan
									UNION  
									SELECT a.i_ruang, null as d_rapat_pesan,
									   null  as awal_8, 
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
											            where a.i_ruang= b.i_ruang 
														and d_rapat_pesan like ? 
														and (b.c_rapat_statsetuju = 'Y' or b.c_rapat_statjwbusul = 'Y')
											           ) 
									   and (c_gedung_fungsi='2' or c_gedung_fungsi='3')
									   order by i_ruang,d_rapat_pesan
									   limit $xLimit offset $xOffset",$where);
									   
			 
			 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array(
									"i_ruang"           =>(string)$result[$j]->i_ruang,
									"d_rapat_pesan"	=>(string)$tglGuna,
									"d_rapat_awalpakai" =>(string)$result[$j]->d_rapat_awalpakai,
									"awal_8"          	=>(string)$result[$j]->awal_8,
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
	     return 'Data tidak ada <br>';
	   }
	}	 
	//Ina : 25-06-2008 : Akhir
	public function getInformasiRuangListAll2($unitkr) {
		$status='A';
	   	$registry = Zend_Registry::getInstance();
	   	$db = $registry->get('db');
	   	try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$query="select i_orgb,a.i_ruang,d_rapat_pesan,
					max(CASE WHEN d_rapat_awalpakai =8   THEN 'Y' END) as awal_8, 
					                max(CASE WHEN d_rapat_awalpakai <=9 and d_rapat_akhirpakai > 9 THEN 'Y' END) as awal_9, 
									max(CASE WHEN d_rapat_awalpakai <=10 and d_rapat_akhirpakai > 10 THEN 'Y' END) as awal_10, 
									max(CASE WHEN d_rapat_awalpakai <=11 and d_rapat_akhirpakai > 11 THEN 'Y' END) as awal_11, 
									max(CASE WHEN d_rapat_awalpakai <=12 and d_rapat_akhirpakai > 12 THEN 'Y' END) as awal_12, 
									max(CASE WHEN d_rapat_awalpakai <=13 and d_rapat_akhirpakai > 13 THEN 'Y' END) as awal_13, 
									max(CASE WHEN d_rapat_awalpakai <=14 and d_rapat_akhirpakai > 14 THEN 'Y' END) as awal_14, 
									max(CASE WHEN d_rapat_awalpakai <=15 and d_rapat_akhirpakai > 15 THEN 'Y' END) as awal_15, 
									max(CASE WHEN d_rapat_awalpakai <=16 and d_rapat_akhirpakai > 16 THEN 'Y' END) as awal_16, 
									max(CASE WHEN d_rapat_awalpakai <=17 and d_rapat_akhirpakai > 17 THEN 'Y' END) as awal_17, 
									max(CASE WHEN d_rapat_awalpakai <=18 and d_rapat_akhirpakai > 18 THEN 'Y' END) as awal_18, 
									max(CASE WHEN d_rapat_awalpakai <=19 and d_rapat_akhirpakai > 19 THEN 'Y' END) as awal_19
									from(select i_orgb,i_ruang,d_rapat_awalpakai,d_rapat_akhirpakai,d_rapat_pesan from e_ast_ajuan_pakai_ruang_tm ) a, e_ast_ruangan_0_tr b
							where a.i_ruang=b.i_ruang
							group by i_orgb,a.i_ruang,d_rapat_pesan
					UNION  SELECT  null as i_orgb,
						a.i_ruang, 
                        null as d_rapat_pesan,
						null  as awal_8, 
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
							             where a.i_ruang= b.i_ruang ) 
						 and (c_gedung_fungsi='2' or c_gedung_fungsi='3')
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
									"awal_8"          	=>(string)$result[$j]->awal_8,
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
	public function getInfoSetujuRuangRapatAllList($pageNumber,$itemPerPage, $ktr1, $ktr2) {
		$status='A';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		 $where[]=$ktr1;
		 $where[]=$ktr2;  
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
		    $query="SELECT i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , 
						d_rapat_awalpakai ,d_rapat_akhirpakai, i_ruang,
						n_peg,n_jabatan,a.i_orgb as i_orgp, d_rapat_pesan, 
						c_rapat_konsumsipagi,c_rapat_konsumsisiang,
						c_rapat_makansiang,c_rapat_makanmalam,c_rapat_statsetuju1
						FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b, e_org_0_0_tm c 
						WHERE i_peg_nip = i_rapat_nippesan 		
						AND a.i_orgb = c.i_orgb
						AND (a.i_orgb like ? or (a.i_orgb like ? and (c.i_orgb_tu isnull or c.i_orgb_tu = ''))) 
						AND(c_rapat_statsetuju ='Y' or  c_rapat_statjwbusul = 'Y')";
			
			$result = $db->fetchAll($query,$where);
			$jmlResult = count($result);
			$hasilAkhir = $jmlResult ;			
		 }
		 else
		 {
			
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;		
			$query="SELECT i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , 
						d_rapat_awalpakai ,d_rapat_akhirpakai, i_ruang,
						n_peg,n_jabatan,a.i_orgb as i_orgp, d_rapat_pesan, 
						c_rapat_konsumsipagi,c_rapat_konsumsisiang,
						c_rapat_makansiang,c_rapat_makanmalam,c_rapat_statsetuju1
						FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b, e_org_0_0_tm c 
						WHERE i_peg_nip = i_rapat_nippesan 		
						AND a.i_orgb = c.i_orgb
						AND (a.i_orgb like ? or (a.i_orgb like ? and (c.i_orgb_tu isnull or c.i_orgb_tu = ''))) 
						AND(c_rapat_statsetuju ='Y' or  c_rapat_statjwbusul = 'Y')		
			   		    limit $xLimit offset $xOffset";							
			
			$result = $db->fetchAll($query,$where);			 
			$jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				     $snackPagi = $result[$j]->c_rapat_konsumsipagi;
					 $snackSiang = $result[$j]->c_rapat_konsumsisiang;
					 $makanSiang = $result[$j]->c_rapat_makansiang;
					 $makanMalam = $result[$j]->c_rapat_makanmalam;
					 $setujuKonsumsi = $result[$j]->c_rapat_statsetuju1;
					 					 
				     if ($snackPagi=='Y' ||  $snackSiang=='Y' || $makanSiang =='Y' || $makanMalam == 'Y')
					 { 
					     if ($setujuKonsumsi =='Y') {
						    $ketKonsumsi = 'Disetujui';
						 } else if ($setujuKonsumsi=='T') {
						    $ketKonsumsi = 'Ditolak';
						 } else {
						    $ketKonsumsi = 'Blm Disetujui';
						 }
					 } else{
					     $ketKonsumsi = 'Tidak Ada';
					 }
					$hasilAkhir[$j] = array("i_ruang_ajuanpakai"     =>(string)$result[$j]->i_ruang_ajuanpakai,
									"d_ruang_ajuanpakai"    =>(string)$result[$j]->d_ruang_ajuanpakai,
									"i_rapat_nippesan"      =>(string)$result[$j]->i_rapat_nippesan,
									"d_rapat_awalpakai"     =>(string)$result[$j]->d_rapat_awalpakai,
									"d_rapat_akhirpakai"    =>(string)$result[$j]->d_rapat_akhirpakai,									
									"i_ruang"           	=>(string)$result[$j]->i_ruang,									
									"ketKonsumsi"    		=>(string)$ketKonsumsi,
									"n_peg"    				=>(string)$result[$j]->n_peg,
									"i_orgp"    			=>(string)$result[$j]->i_orgp,
									"n_jabatan"    			=>(string)$result[$j]->n_jabatan,									
									"d_rapat_pesan"         =>(string)$result[$j]->d_rapat_pesan);		
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	public function getInfoSetujuRuangRapatAllList2() {
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
	
	public function getInfoSetujuRuangRapatList($pageNumber,$itemPerPage,$tglGuna, $ktr1, $ktr2) {
	    // Data ditampilkan berdasarkan otoritasnya (Dibatasi unit kerja yg mengajukan, kecuali untuk org2 tertentu yg bisa melihat seluruhnya)
		// yg sudah disetujui : c_rapat_statsetuju = 'Y' or c_rapat_statjwbusul = Y
		// (+) kolom untuk menampilkan status persetujuan Konsumsi
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[]=$tglGuna;
			$where[]=$ktr1;
			$where[]=$ktr2;			
			
			 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$query="SELECT i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , 
						d_rapat_awalpakai ,d_rapat_akhirpakai, i_ruang,
						n_peg,n_jabatan,a.i_orgb as i_orgp, d_rapat_pesan, 
						c_rapat_konsumsipagi,c_rapat_konsumsisiang,
						c_rapat_makansiang,c_rapat_makanmalam,c_rapat_statsetuju1
						FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b, e_org_0_0_tm c 
						WHERE i_peg_nip = i_rapat_nippesan 
						AND a.i_orgb = c.i_orgb						
						AND d_rapat_pesan = ? 	
						AND (a.i_orgb like ? or (a.i_orgb like ? and (c.i_orgb_tu isnull or c.i_orgb_tu = ''))) 						
						AND(c_rapat_statsetuju ='Y' or  c_rapat_statjwbusul = 'Y')";
						
				$result = $db->fetchAll($query,$where);
				$jmlResult = count($result);
				$hasilAkhir = $jmlResult ;
			
			}
			else
			{			
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				 
				$query="SELECT i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , 
						d_rapat_awalpakai ,d_rapat_akhirpakai, i_ruang ,
						n_peg,n_jabatan,a.i_orgb as i_orgp, d_rapat_pesan, 
						c_rapat_konsumsipagi,c_rapat_konsumsisiang,
						c_rapat_makansiang,c_rapat_makanmalam,c_rapat_statsetuju1
						FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b,e_org_0_0_tm c  
						WHERE i_peg_nip = i_rapat_nippesan 
						AND a.i_orgb = c.i_orgb
						AND d_rapat_pesan = ? 						
						AND (a.i_orgb like ? or (a.i_orgb like ? and (c.i_orgb_tu isnull or c.i_orgb_tu = ''))) 
						AND(c_rapat_statsetuju ='Y' or  c_rapat_statjwbusul = 'Y')
						limit $xLimit offset $xOffset";			
			
				$result = $db->fetchAll($query,$where);				 
				$jmlResult = count($result);				 
				for ($j = 0; $j < $jmlResult; $j++) {
				     $snackPagi = $result[$j]->c_rapat_konsumsipagi;
					 $snackSiang = $result[$j]->c_rapat_konsumsisiang;
					 $makanSiang = $result[$j]->c_rapat_makansiang;
					 $makanMalam = $result[$j]->c_rapat_makanmalam;
					 $setujuKonsumsi = $result[$j]->c_rapat_statsetuju1;
					 
				     if ($snackPagi=='Y' ||  $snackSiang=='Y' || $makanSiang =='Y' || $makanMalam == ' Y')
					 { 
					     if ($setujuKonsumsi=='Y') {
						    $ketKonsumsi = 'Disetujui';
						 } else if ($setujuKonsumsi=='T') {
						    $ketKonsumsi = 'Ditolak';
						 } else {
						    $ketKonsumsi = 'Blm Disetujui';
						 }
					 } else{
					     $ketKonsumsi = 'Tidak Ada';
					 }
					$hasilAkhir[$j] = array("i_ruang_ajuanpakai"     =>(string)$result[$j]->i_ruang_ajuanpakai,
									"d_ruang_ajuanpakai"    =>(string)$result[$j]->d_ruang_ajuanpakai,
									"i_rapat_nippesan"      =>(string)$result[$j]->i_rapat_nippesan,
									"d_rapat_awalpakai"     =>(string)$result[$j]->d_rapat_awalpakai,
									"d_rapat_akhirpakai"    =>(string)$result[$j]->d_rapat_akhirpakai,									
									"i_ruang"           	=>(string)$result[$j]->i_ruang,									
									"ketKonsumsi"    		=>(string)$ketKonsumsi,
									"n_peg"    				=>(string)$result[$j]->n_peg,
									"i_orgp"    			=>(string)$result[$j]->i_orgp,
									"n_jabatan"    			=>(string)$result[$j]->n_jabatan,									
									"d_rapat_pesan"         =>(string)$result[$j]->d_rapat_pesan);					
				 }	
			}	 
		     return $hasilAkhir;
		   } catch (Exception $e) {
	         echo $e->getMessage().'<br>';
		     return 'Data tidak ada <br>';
		   }
	}
	public function getInfoSetujuRuangRapatList2($tglGuna) {
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
	public function getInfoBlmSetujuRuangRapatAllList($pageNumber,$itemPerPage,$ktr1,$ktr2) {
		// Data ditampilkan berdasarkan otoritasnya (Dibatasi unit kerja yg mengajukan, kecuali untuk org2 tertentu yg bisa melihat seluruhnya)
		// yg sudah disetujui : c_rapat_statsetuju is null or c_rapat_statsetuju =''
		// (+) kolom untuk menampilkan status persetujuan Konsumsi
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {			
			$where[]=$ktr1;
			$where[]=$ktr2;			
			
			 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$query="SELECT i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , 
						d_rapat_awalpakai ,d_rapat_akhirpakai, i_ruang,
						n_peg,n_jabatan,a.i_orgb as i_orgp, d_rapat_pesan, 
						c_rapat_konsumsipagi,c_rapat_konsumsisiang,
						c_rapat_makansiang,c_rapat_makanmalam,c_rapat_statsetuju1
						FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b, e_org_0_0_tm c 
						WHERE i_peg_nip = i_rapat_nippesan 
						AND a.i_orgb = c.i_orgb												
						AND (a.i_orgb like ? or (a.i_orgb like ? and (c.i_orgb_tu isnull or c.i_orgb_tu = ''))) 						
						AND(c_rapat_statsetuju is null or c_rapat_statsetuju = '')";
						
				$result = $db->fetchAll($query,$where);
				$jmlResult = count($result);
				$hasilAkhir = $jmlResult ;
			
			}
			else
			{			
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				 
				$query="SELECT i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , 
						d_rapat_awalpakai ,d_rapat_akhirpakai, i_ruang ,
						n_peg,n_jabatan,a.i_orgb as i_orgp, d_rapat_pesan, 
						c_rapat_konsumsipagi,c_rapat_konsumsisiang,
						c_rapat_makansiang,c_rapat_makanmalam,c_rapat_statsetuju1
						FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b,e_org_0_0_tm c  
						WHERE i_peg_nip = i_rapat_nippesan 
						AND a.i_orgb = c.i_orgb						
						AND (a.i_orgb like ? or (a.i_orgb like ? and (c.i_orgb_tu isnull or c.i_orgb_tu = ''))) 
						AND(c_rapat_statsetuju is null or c_rapat_statsetuju = '')
						limit $xLimit offset $xOffset";			
			
				$result = $db->fetchAll($query,$where);				 
				$jmlResult = count($result);				 
				for ($j = 0; $j < $jmlResult; $j++) {
				     
					$hasilAkhir[$j] = array("i_ruang_ajuanpakai"     =>(string)$result[$j]->i_ruang_ajuanpakai,
									"d_ruang_ajuanpakai"    =>(string)$result[$j]->d_ruang_ajuanpakai,
									"i_rapat_nippesan"      =>(string)$result[$j]->i_rapat_nippesan,
									"d_rapat_awalpakai"     =>(string)$result[$j]->d_rapat_awalpakai,
									"d_rapat_akhirpakai"    =>(string)$result[$j]->d_rapat_akhirpakai,									
									"i_ruang"           	=>(string)$result[$j]->i_ruang,
									"n_peg"    				=>(string)$result[$j]->n_peg,
									"i_orgp"    			=>(string)$result[$j]->i_orgp,
									"n_jabatan"    			=>(string)$result[$j]->n_jabatan,									
									"d_rapat_pesan"         =>(string)$result[$j]->d_rapat_pesan);					
				 }	
			}	 
		     return $hasilAkhir;
		   } catch (Exception $e) {
	         echo $e->getMessage().'<br>';
		     return 'Data tidak ada <br>';
		   }
	}
	public function getInfoBlmSetujuRuangRapatAllList2() {
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
	public function getInfoBlmSetujuRuangRapatList($pageNumber,$itemPerPage,$tglGuna,$ktr1,$ktr2) {
	
		// Data ditampilkan berdasarkan otoritasnya (Dibatasi unit kerja yg mengajukan, kecuali untuk org2 tertentu yg bisa melihat seluruhnya)
		// yg sudah disetujui : c_rapat_statsetuju isnull or c_rapat_statsetuju = ''
		// (+) kolom untuk menampilkan status persetujuan Konsumsi
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[]=$tglGuna;
			$where[]=$ktr1;
			$where[]=$ktr2;			
			
			 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$query="SELECT i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , 
						d_rapat_awalpakai ,d_rapat_akhirpakai, i_ruang,
						n_peg,n_jabatan,a.i_orgb as i_orgp, d_rapat_pesan, 
						c_rapat_konsumsipagi,c_rapat_konsumsisiang,
						c_rapat_makansiang,c_rapat_makanmalam,c_rapat_statsetuju1
						FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b, e_org_0_0_tm c 
						WHERE i_peg_nip = i_rapat_nippesan 
						AND a.i_orgb = c.i_orgb						
						AND d_rapat_pesan = ? 	
						AND (a.i_orgb like ? or (a.i_orgb like ? and (c.i_orgb_tu isnull or c.i_orgb_tu = ''))) 						
						AND(c_rapat_statsetuju isnull or c_rapat_statsetuju = '')";
						
				$result = $db->fetchAll($query,$where);
				$jmlResult = count($result);
				$hasilAkhir = $jmlResult ;
			
			}
			else
			{			
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				 
				$query="SELECT i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , 
						d_rapat_awalpakai ,d_rapat_akhirpakai, i_ruang ,
						n_peg,n_jabatan,a.i_orgb as i_orgp, d_rapat_pesan, 
						c_rapat_konsumsipagi,c_rapat_konsumsisiang,
						c_rapat_makansiang,c_rapat_makanmalam,c_rapat_statsetuju1
						FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b,e_org_0_0_tm c  
						WHERE i_peg_nip = i_rapat_nippesan 
						AND a.i_orgb = c.i_orgb
						AND d_rapat_pesan = ? 						
						AND (a.i_orgb like ? or (a.i_orgb like ? and (c.i_orgb_tu isnull or c.i_orgb_tu = ''))) 
						AND(c_rapat_statsetuju isnull or c_rapat_statsetuju = '')
						limit $xLimit offset $xOffset";			
			
				$result = $db->fetchAll($query,$where);				 
				$jmlResult = count($result);				 
				for ($j = 0; $j < $jmlResult; $j++) {
				     
					$hasilAkhir[$j] = array("i_ruang_ajuanpakai"     =>(string)$result[$j]->i_ruang_ajuanpakai,
									"d_ruang_ajuanpakai"    =>(string)$result[$j]->d_ruang_ajuanpakai,
									"i_rapat_nippesan"      =>(string)$result[$j]->i_rapat_nippesan,
									"d_rapat_awalpakai"     =>(string)$result[$j]->d_rapat_awalpakai,
									"d_rapat_akhirpakai"    =>(string)$result[$j]->d_rapat_akhirpakai,									
									"i_ruang"           	=>(string)$result[$j]->i_ruang,														
									"n_peg"    				=>(string)$result[$j]->n_peg,
									"i_orgp"    			=>(string)$result[$j]->i_orgp,
									"n_jabatan"    			=>(string)$result[$j]->n_jabatan,									
									"d_rapat_pesan"         =>(string)$result[$j]->d_rapat_pesan);					
				 }	
			}	 
		     return $hasilAkhir;
		   } catch (Exception $e) {
	         echo $e->getMessage().'<br>';
		     return 'Data tidak ada <br>';
		   }
	}
	public function getInfoBlmSetujuRuangRapatList2($tglGuna) {
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
	public function getInfoPerubahanRuangRapatAllList($pageNumber,$itemPerPage,$ktr1,$ktr2) {
		
		// Data ditampilkan berdasarkan otoritasnya (Dibatasi unit kerja yg mengajukan, kecuali untuk org2 tertentu yg bisa melihat seluruhnya)
		// yg sudah disetujui : c_rapat_statsetuju = 'U'
		// (+) kolom untuk menampilkan status persetujuan Konsumsi
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {			
			$where[]=$ktr1;
			$where[]=$ktr2;			
			
			 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$query="SELECT i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , 
						i_ruang_asli, d_rapat_awalasli ,d_rapat_akhirasli,
						i_ruang_usulan, d_rapat_usuljamawal ,d_rapat_usuljamakh,
						n_peg,n_jabatan,a.i_orgb as i_orgp, d_rapat_pesan, 
						c_rapat_konsumsipagi,c_rapat_konsumsisiang,
						c_rapat_makansiang,c_rapat_makanmalam,c_rapat_statsetuju1,
						c_rapat_statjwbusul
						FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b, e_org_0_0_tm c 
						WHERE i_peg_nip = i_rapat_nippesan 
						AND a.i_orgb = c.i_orgb									
						AND (a.i_orgb like ? or (a.i_orgb like ? and (c.i_orgb_tu isnull or c.i_orgb_tu = '')))
						AND c_rapat_statsetuju = 'U'";
						
				$result = $db->fetchAll($query,$where);
				$jmlResult = count($result);
				$hasilAkhir = $jmlResult ;
			
			}
			else
			{			
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				 
				$query="SELECT i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , 
						i_ruang_asli, d_rapat_awalasli ,d_rapat_akhirasli,
						i_ruang_usulan, d_rapat_usuljamawal ,d_rapat_usuljamakh,
						n_peg,n_jabatan,a.i_orgb as i_orgp, d_rapat_pesan, 
						c_rapat_konsumsipagi,c_rapat_konsumsisiang,
						c_rapat_makansiang,c_rapat_makanmalam,c_rapat_statsetuju1,
						c_rapat_statjwbusul
						FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b, e_org_0_0_tm c 
						WHERE i_peg_nip = i_rapat_nippesan 
						AND a.i_orgb = c.i_orgb												
						AND (a.i_orgb like ? or (a.i_orgb like ? and (c.i_orgb_tu isnull or c.i_orgb_tu = '')))
						AND c_rapat_statsetuju = 'U'
						limit $xLimit offset $xOffset";			
			
				$result = $db->fetchAll($query,$where);				 
				$jmlResult = count($result);	
				
				for ($j = 0; $j < $jmlResult; $j++) {				    
				     $setujuRuang = $result[$j]->c_rapat_statjwbusul;
					 $snackPagi = $result[$j]->c_rapat_konsumsipagi;
					 $snackSiang = $result[$j]->c_rapat_konsumsisiang;
					 $makanSiang = $result[$j]->c_rapat_makansiang;
					 $makanMalam = $result[$j]->c_rapat_makanmalam;
					 $setujuKonsumsi = $result[$j]->c_rapat_statsetuju1;
					 
					 // Menentukan persetujuan Ruangan
					 
					 if ($setujuRuang=='Y') {
						    $ketSetujuRuang = 'Disetujui';
					} else if ($setujuRuang=='T') {
						    $ketSetujuRuang = 'Ditolak';
					} else {
						    $ketSetujuRuang = 'Blm Disetujui';
					}
					 
					 
					 // menentukan Persetujuan Konsumsi
				     if ($snackPagi=='Y' ||  $snackSiang=='Y' || $makanSiang =='Y' || $makanMalam == ' Y')
					 { 
					     if ($setujuKonsumsi=='Y') {
						    $ketKonsumsi = 'Disetujui';
						 } else if ($setujuKonsumsi=='T') {
						    $ketKonsumsi = 'Ditolak';
						 } else  {
						    $ketKonsumsi = 'Blm Disetujui';
						 }
					 } else{
					     $ketKonsumsi = 'Tidak Ada';
					 }
					 
					$hasilAkhir[$j] = array("i_ruang_ajuanpakai"     =>(string)$result[$j]->i_ruang_ajuanpakai,
									"d_ruang_ajuanpakai"    =>(string)$result[$j]->d_ruang_ajuanpakai,
									"i_rapat_nippesan"      =>(string)$result[$j]->i_rapat_nippesan,
									"i_ruang_asli"           	=>(string)$result[$j]->i_ruang_asli,
									"d_rapat_awalasli"     =>(string)$result[$j]->d_rapat_awalasli,
									"d_rapat_akhirasli"    =>(string)$result[$j]->d_rapat_akhirasli,
									"i_ruang_usulan"           	=>(string)$result[$j]->i_ruang_usulan,
									"d_rapat_usuljamawal"     =>(string)$result[$j]->d_rapat_usuljamawal,
									"d_rapat_usuljamakh"    =>(string)$result[$j]->d_rapat_usuljamakh,									
									"ketKonsumsi"           =>(string)$ketKonsumsi,
									"ketSetujuRuang"        =>(string)$ketSetujuRuang,
									"n_peg"    				=>(string)$result[$j]->n_peg,
									"i_orgp"    			=>(string)$result[$j]->i_orgp,
									"n_jabatan"    			=>(string)$result[$j]->n_jabatan,									
									"d_rapat_pesan"         =>(string)$result[$j]->d_rapat_pesan);					
				 }	
			}	 
		     return $hasilAkhir;
		   } catch (Exception $e) {
	         echo $e->getMessage().'<br>';
		     return 'Data tidak ada <br>';
		   }
	}
	public function getInfoPerubahanRuangRapatAllList2() {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$query="select i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , d_rapat_awalpakai ,d_rapat_akhirpakai ,d_rapat_usuljamawal,d_rapat_usuljamakh,i_ruang_usulan,i_ruang ,c_rapat_statsetuju ,n_peg,n_jabatan,b.i_orgb as i_orgp".
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
									"d_rapat_usuljamawal"    =>(string)$result[$j]->d_rapat_usuljamawal,
									"d_rapat_usuljamakh"    =>(string)$result[$j]->d_rapat_usuljamakh,
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
	public function getInfoPerubahanRuangRapatList($pageNumber,$itemPerPage,$tglGuna,$ktr1,$ktr2) {
	
		// Data ditampilkan berdasarkan otoritasnya (Dibatasi unit kerja yg mengajukan, kecuali untuk org2 tertentu yg bisa melihat seluruhnya)
		// yg sudah disetujui : c_rapat_statsetuju = 'U'
		// (+) kolom untuk menampilkan status persetujuan Konsumsi
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[]=$tglGuna;
			$where[]=$ktr1;
			$where[]=$ktr2;			
			
			 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$query="SELECT i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , 
						i_ruang_asli, d_rapat_awalasli ,d_rapat_akhirasli,
						i_ruang_usulan, d_rapat_usuljamawal ,d_rapat_usuljamakh,
						n_peg,n_jabatan,a.i_orgb as i_orgp, d_rapat_pesan, 
						c_rapat_konsumsipagi,c_rapat_konsumsisiang,
						c_rapat_makansiang,c_rapat_makanmalam,c_rapat_statsetuju1,
						c_rapat_statjwbusul
						FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b, e_org_0_0_tm c 
						WHERE i_peg_nip = i_rapat_nippesan 
						AND a.i_orgb = c.i_orgb						
						AND d_rapat_pesan = ? 	
						AND (a.i_orgb like ? or (a.i_orgb like ? and (c.i_orgb_tu isnull or c.i_orgb_tu = '')))
						AND c_rapat_statsetuju = 'U'";
						
				$result = $db->fetchAll($query,$where);
				$jmlResult = count($result);
				$hasilAkhir = $jmlResult ;
			
			}
			else
			{			
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				 
				$query="SELECT i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , 
						i_ruang_asli, d_rapat_awalasli ,d_rapat_akhirasli,
						i_ruang_usulan, d_rapat_usuljamawal ,d_rapat_usuljamakh,
						n_peg,n_jabatan,a.i_orgb as i_orgp, d_rapat_pesan, 
						c_rapat_konsumsipagi,c_rapat_konsumsisiang,
						c_rapat_makansiang,c_rapat_makanmalam,c_rapat_statsetuju1,
						c_rapat_statjwbusul
						FROM e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b, e_org_0_0_tm c 
						WHERE i_peg_nip = i_rapat_nippesan 
						AND a.i_orgb = c.i_orgb						
						AND d_rapat_pesan = ? 	
						AND (a.i_orgb like ? or (a.i_orgb like ? and (c.i_orgb_tu isnull or c.i_orgb_tu = '')))
						AND c_rapat_statsetuju = 'U'
						limit $xLimit offset $xOffset";			
			
				$result = $db->fetchAll($query,$where);				 
				$jmlResult = count($result);	
				
				for ($j = 0; $j < $jmlResult; $j++) {				    
				     $setujuRuang = $result[$j]->c_rapat_statjwbusul;
					 $snackPagi = $result[$j]->c_rapat_konsumsipagi;
					 $snackSiang = $result[$j]->c_rapat_konsumsisiang;
					 $makanSiang = $result[$j]->c_rapat_makansiang;
					 $makanMalam = $result[$j]->c_rapat_makanmalam;
					 $setujuKonsumsi = $result[$j]->c_rapat_statsetuju1;
					 
					 // Menentukan persetujuan Ruangan
					 
					 if ($setujuRuang=='Y') {
						    $ketSetujuRuang = 'Disetujui';
					} else if ($setujuRuang=='T') {
						    $ketSetujuRuang = 'Ditolak';
					} else {
						    $ketSetujuRuang = 'Blm Disetujui';
					}
					 
					 
					 // menentukan Persetujuan Konsumsi
				     if ($snackPagi=='Y' ||  $snackSiang=='Y' || $makanSiang =='Y' || $makanMalam == ' Y')
					 { 
					     if ($setujuKonsumsi=='Y') {
						    $ketKonsumsi = 'Disetujui';
						 } else if ($setujuKonsumsi=='T') {
						    $ketKonsumsi = 'Ditolak';
						 } else {
						    $ketKonsumsi = 'Blm Disetujui';
						 }
					 } else{
					     $ketKonsumsi = 'Tidak Ada';
					 }
					 
					$hasilAkhir[$j] = array("i_ruang_ajuanpakai"     =>(string)$result[$j]->i_ruang_ajuanpakai,
									"d_ruang_ajuanpakai"    =>(string)$result[$j]->d_ruang_ajuanpakai,
									"i_rapat_nippesan"      =>(string)$result[$j]->i_rapat_nippesan,
									"i_ruang_asli"           	=>(string)$result[$j]->i_ruang_asli,
									"d_rapat_awalasli"     =>(string)$result[$j]->d_rapat_awalasli,
									"d_rapat_akhirasli"    =>(string)$result[$j]->d_rapat_akhirasli,
									"i_ruang_usulan"           	=>(string)$result[$j]->i_ruang_usulan,
									"d_rapat_usuljamawal"     =>(string)$result[$j]->d_rapat_usuljamawal,
									"d_rapat_usuljamakh"    =>(string)$result[$j]->d_rapat_usuljamakh,									
									"ketKonsumsi"           =>(string)$ketKonsumsi,
									"ketSetujuRuang"        =>(string)$ketSetujuRuang,
									"n_peg"    				=>(string)$result[$j]->n_peg,
									"i_orgp"    			=>(string)$result[$j]->i_orgp,
									"n_jabatan"    			=>(string)$result[$j]->n_jabatan,									
									"d_rapat_pesan"         =>(string)$result[$j]->d_rapat_pesan);					
				 }	
			}	 
		     return $hasilAkhir;
		   } catch (Exception $e) {
	         echo $e->getMessage().'<br>';
		     return 'Data tidak ada <br>';
		   }
	}
	public function getInfoPerubahanRuangRapatList2($tglGuna) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$query="select i_ruang_ajuanpakai, d_ruang_ajuanpakai,i_rapat_nippesan , d_rapat_awalpakai ,d_rapat_akhirpakai ,d_rapat_usuljamawal,d_rapat_usuljamakh,i_ruang_usulan,i_ruang ,c_rapat_statsetuju ,n_peg,n_jabatan,b.i_orgb as i_orgp".
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
									"d_rapat_usuljamawal"    =>(string)$result[$j]->d_rapat_usuljamawal,
									"d_rapat_usuljamakh"    =>(string)$result[$j]->d_rapat_usuljamakh,
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


	// Akhir Updated By Agung 22-11-2007

	public function getRuangRapatListAll($pageNumber,$itemPerPage) {
		 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where ='2';
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_ruangan_0_tr where c_gedung_fungsi = ? ",$where);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT i_ruang,i_ruang_lokasi,n_gedung,q_gedung_lantai,q_gedung_kapasitas,
		                                 q_gedung_luas,c_gedung_fungsi,i_orgb_tgjwbgedung
										FROM e_ast_ruangan_0_tr where c_gedung_fungsi = ? 
										ORDER BY i_ruang
										limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] =   array("i_ruang"               =>(string)$result[$j]->i_ruang,
									   "i_ruang_lokasi"           =>(string)$result[$j]->i_ruang_lokasi,
									   "n_gedung"                 =>(string)$result[$j]->n_gedung,
		                               "q_gedung_lantai"          =>(string)$result[$j]->q_gedung_lantai,
									   "q_gedung_kapasitas"       =>(string)$result[$j]->q_gedung_kapasitas,
									   "q_gedung_luas"            =>(string)$result[$j]->q_gedung_luas,
									   "c_gedung_fungsi"          =>(string)$result[$j]->c_gedung_fungsi,
									   "i_orgb_tgjwbgedung"       =>(string)$result[$j]->i_orgb_tgjwbgedung);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
	
    // Ina : 16-06-2008 : Informasi pengajuan penggunaan ruangan
	public function getInformasiRuangList_DlmPengajuan($unitkr,$tglGuna) {
       $status='A';
	   $nsw = '%'.$tglGuna.'%';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 //$where[] = $unitkr;
		 $where[] = $tglGuna;
		 $where[] = $tglGuna;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select a.i_ruang, 
									max(CASE WHEN d_rapat_awalpakai =8   THEN 'Y' END) as awal_8, 
									max(CASE WHEN d_rapat_awalpakai <=9 and d_rapat_akhirpakai > 9  THEN 'Y' END) as awal_9, 
									max(CASE WHEN d_rapat_awalpakai <=10 and d_rapat_akhirpakai > 10 THEN 'Y' END) as awal_10, 
									max(CASE WHEN d_rapat_awalpakai <=11 and d_rapat_akhirpakai > 11 THEN 'Y' END) as awal_11, 
									max(CASE WHEN d_rapat_awalpakai <=12 and d_rapat_akhirpakai > 12 THEN 'Y' END) as awal_12, 
									max(CASE WHEN d_rapat_awalpakai <=13 and d_rapat_akhirpakai > 13 THEN 'Y' END) as awal_13, 
									max(CASE WHEN d_rapat_awalpakai <=14 and d_rapat_akhirpakai > 14 THEN 'Y' END) as awal_14, 
									max(CASE WHEN d_rapat_awalpakai <=15 and d_rapat_akhirpakai > 15 THEN 'Y' END) as awal_15, 
									max(CASE WHEN d_rapat_awalpakai <=16 and d_rapat_akhirpakai > 16 THEN 'Y' END) as awal_16, 
									max(CASE WHEN d_rapat_awalpakai <=17 and d_rapat_akhirpakai > 17 THEN 'Y' END) as awal_17, 
									max(CASE WHEN d_rapat_awalpakai <=18 and d_rapat_akhirpakai > 18 THEN 'Y' END) as awal_18, 
									max(CASE WHEN d_rapat_awalpakai <=19 and d_rapat_akhirpakai > 19 THEN 'Y' END) as awal_19
									from(select i_orgb,i_ruang,d_rapat_awalpakai,d_rapat_akhirpakai 
									     from e_ast_ajuan_pakai_ruang_tm 
									     where d_rapat_pesan =? and 
										 (c_rapat_statsetuju is null or (c_rapat_statsetuju = 'U' and c_rapat_statjwbusul is null))) a,
									e_ast_ruangan_0_tr b
									where a.i_ruang=b.i_ruang 										
									group by a.i_ruang
									UNION  
									SELECT
									   a.i_ruang, 
									   null  as awal_8, 
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
											            where a.i_ruang= b.i_ruang and b.d_rapat_pesan =?
														and (c_rapat_statsetuju is null or (c_rapat_statsetuju = 'U' and c_rapat_statjwbusul = null))
											           ) 
									   and (c_gedung_fungsi='2' or c_gedung_fungsi='3')
									   order by i_ruang",$where); 
								 
         $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_ruang"           =>(string)$result[$j]->i_ruang,
									"d_rapat_awalpakai" =>(string)$result[$j]->d_rapat_awalpakai,
									"awal_8"          	=>(string)$result[$j]->awal_8,
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
    public function getDataPejabat(){
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
         $result 	= $db->fetchAll("select a.i_peg_nip,b.n_peg from e_sdm_jabatan_0_tm a,e_sdm_pegawai_0_tm b
                                 where a.c_jabatan = 'SK1400' and b.i_peg_nip = a.i_peg_nip"); 
        return $result;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }	  
    }	
}		
?>