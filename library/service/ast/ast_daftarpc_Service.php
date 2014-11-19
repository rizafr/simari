<?php
require_once 'Zend/Json.php';

class ast_daftarpc_Service {
   
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
	 * fungsi daftar PC
	 ***************************/
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
	
	public function getUnitKerjaList2() {
	    //echo 'service tool';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_orgb,i_orgb_parent,c_orgb_level,n_orgb FROM e_org_0_0_tm
								  order by n_orgb');
         $jmlResult = count($result);
		
		for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb"             =>(string)$result[$j]->i_orgb,
								   "i_orgb_parent"             =>(string)$result[$j]->i_orgb_parent,
								   "c_orgb_level"    =>(string)$result[$j]->c_orgb_level,
								   "n_orgb"     =>(string)$result[$j]->n_orgb);
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getRefNamaUnitByKey($kode) {
	//echo '$kode'.$kode;
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchOne('SELECT n_orgb FROM e_org_0_0_tm WHERE i_orgb = ?',$kode);
		 $returnValue = Zend_Json::encode(array($result));
	     return $returnValue;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getDataPCList($data) {
           
		
	        $where[] = $data['d_anggaran'];
		 $where[] = $data['c_barang'];
               $where[] = $data['tgl'];
		 $where[] = $data['i_aset'];

	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT d_anggaran,c_barang,d_perolehan,i_komputer_macaddress,c_unit_kerja,
									i_ruang,to_char(i_aset,'09999') as no_aset,i_komputer_serialpc,i_komputer_serialwindow,e_pc
									FROM e_ast_komputer_0_tr   where d_anggaran = ? and c_barang = ? and d_perolehan = ? and i_aset = ?
									ORDER BY d_anggaran",$where);
									
	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("d_anggaran"           =>(string)$result[$j]->thn_ang,
									"c_barang"           =>(string)$result[$j]->kd_brg,
									"no_aset"           =>(string)$result[$j]->no_aset,
									"d_perolehan"           =>(string)$result[$j]->tgl_perlh,
									"i_komputer_macaddress"   =>(string)$result[$j]->i_komputer_macaddress,
									"c_unit_kerja"           =>(string)$result[$j]->c_unit_kerja,
									"i_komputer_serialpc"           =>(string)$result[$j]->i_komputer_serialpc,
									"e_pc"        =>(string)$result[$j]->e_pc,
									"i_komputer_serialwindow"           =>(string)$result[$j]->i_komputer_serialwindow);
								  
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getCariDataPCList($pageNumber,$itemPerPage) {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
								   	     FROM e_ast_komputer_0_tr
										 where (c_status_lengkap ='T' or c_status_lengkap is null)"); 
									      
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		     $result = $db->fetchAll("SELECT d_anggaran,c_barang,d_perolehan,i_komputer_macaddress,
										i_hw_investasi,
										i_komputer_macaddress1,i_komputer_macaddress2,i_komputer_macaddress3,
										i_komputer_macaddress4,c_unit_kerja,
										e_PC,to_char(i_aset,'09999') as no_aset,i_komputer_serialpc,
										i_komputer_serialwindow,n_type,d.ur_sskel
										FROM e_ast_komputer_0_tr c, e_ast_sskel_0_tr d
										where (c_status_lengkap ='T' or c_status_lengkap is null)
										and substr(c.c_barang ,1,1) = d.kd_gol
										and substr(c.c_barang ,2,2) = d.kd_bid 
										and substr(c.c_barang ,4,2) = d.kd_kel
										and substr(c.c_barang ,6,2) = d.kd_skel
										and substr(c.c_barang ,8,3) = d.kd_sskel
										ORDER BY d_anggaran ,c_barang,i_aset 
										limit $xLimit offset $xOffset");
		
		
             $jmlResult = count($result);
		 
		 
		      for ($j = 0; $j < $jmlResult; $j++) {
		 
              $hasilAkhir[$j] = array("d_anggaran"           		=>(string)$result[$j]->d_anggaran,
										"c_barang"           		=>(string)$result[$j]->c_barang,
										"no_aset"           		=>(string)$result[$j]->no_aset,
										"d_perolehan"           	=>(string)$result[$j]->d_perolehan,
										"i_komputer_macaddress"   	=>(string)$result[$j]->i_komputer_macaddress,
										"i_komputer_macaddress1"   	=>(string)$result[$j]->i_komputer_macaddress1,
										"i_komputer_macaddress2"   	=>(string)$result[$j]->i_komputer_macaddress2,
										"i_komputer_macaddress3"   	=>(string)$result[$j]->i_komputer_macaddress3,
										"i_komputer_macaddress4"   	=>(string)$result[$j]->i_komputer_macaddress4,
										"c_unit_kerja"           	=>(string)$result[$j]->c_unit_kerja,
										"e_pc"           			=>(string)$result[$j]->e_pc,
										"ur_sskel"           		=>(string)$result[$j]->ur_sskel,
										"i_komputer_serialpc"       =>(string)$result[$j]->i_komputer_serialpc,
										"i_komputer_serialwindow"   =>(string)$result[$j]->i_komputer_serialwindow,
										"n_type"   					=>(string)$result[$j]->n_type,
										"i_hw_investasi"   			=>(string)$result[$j]->i_hw_investasi
										);
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	//add 28Juli 08 =========================================================================================================
	public function getCariDataPCListByNm($pageNumber,$itemPerPage,$nmBarang,$thn) {
	
		$namaBarang = strtoupper($nmBarang);
	    $nbrg = $namaBarang.'%';
		$thn = strtoupper($thn);
	    $tahun = $thn.'%';
	   
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $nbrg;
			$where[] = $tahun;
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			 {
				// $hasilAkhir = $db->fetchOne("SELECT count(*)
									   	     // FROM e_ast_komputer_0_tr
											 // where (c_status_lengkap ='T' or c_status_lengkap is null)
											 // and upper(ur_sskel) like ?	and d_anggaran like ?",$where); 
											 
				$hasil = $db->fetchAll("SELECT d_anggaran,c_barang,d_perolehan,i_komputer_macaddress,
										i_komputer_macaddress1,i_komputer_macaddress2,i_komputer_macaddress3,
										i_komputer_macaddress4,c_unit_kerja,
										e_PC,to_char(i_aset,'09999') as no_aset,i_komputer_serialpc,
										i_komputer_serialwindow,n_type,d.ur_sskel
										FROM e_ast_komputer_0_tr c, e_ast_sskel_0_tr d
										where substr(c.c_barang ,1,1) = d.kd_gol
										and substr(c.c_barang ,2,2) = d.kd_bid 
										and substr(c.c_barang ,4,2) = d.kd_kel
										and substr(c.c_barang ,6,2) = d.kd_skel
										and substr(c.c_barang ,8,3) = d.kd_sskel
										and (c_status_lengkap ='T' or c_status_lengkap is null)
										and upper(ur_sskel) like ?
										and d_anggaran like ?
										ORDER BY d_anggaran ,c_barang,i_aset ",$where);
				
				$hasilAkhir	= count($hasil);					
										      
			 }
			else
			 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		     $result = $db->fetchAll("SELECT d_anggaran,c_barang,d_perolehan,i_komputer_macaddress,
										i_komputer_macaddress1,i_komputer_macaddress2,i_komputer_macaddress3,
										i_komputer_macaddress4,c_unit_kerja,
										e_PC,to_char(i_aset,'09999') as no_aset,i_komputer_serialpc,
										i_komputer_serialwindow,n_type,d.ur_sskel
										FROM e_ast_komputer_0_tr c, e_ast_sskel_0_tr d
										where substr(c.c_barang ,1,1) = d.kd_gol
										and substr(c.c_barang ,2,2) = d.kd_bid 
										and substr(c.c_barang ,4,2) = d.kd_kel
										and substr(c.c_barang ,6,2) = d.kd_skel
										and substr(c.c_barang ,8,3) = d.kd_sskel
										and (c_status_lengkap ='T' or c_status_lengkap is null)
										and upper(ur_sskel) like ?
										and d_anggaran like ?
										ORDER BY d_anggaran ,c_barang,i_aset 
										limit $xLimit offset $xOffset",$where);
		
		
             $jmlResult = count($result);
		 
		 
		      for ($j = 0; $j < $jmlResult; $j++) {
		 
              $hasilAkhir[$j] = array("d_anggaran"           		=>(string)$result[$j]->d_anggaran,
										"c_barang"           		=>(string)$result[$j]->c_barang,
										"no_aset"           		=>(string)$result[$j]->no_aset,
										"d_perolehan"           	=>(string)$result[$j]->d_perolehan,
										"i_komputer_macaddress"   	=>(string)$result[$j]->i_komputer_macaddress,
										"i_komputer_macaddress1"   	=>(string)$result[$j]->i_komputer_macaddress1,
										"i_komputer_macaddress2"   	=>(string)$result[$j]->i_komputer_macaddress2,
										"i_komputer_macaddress3"   	=>(string)$result[$j]->i_komputer_macaddress3,
										"i_komputer_macaddress4"   	=>(string)$result[$j]->i_komputer_macaddress4,
										"c_unit_kerja"           	=>(string)$result[$j]->c_unit_kerja,
										"e_pc"           			=>(string)$result[$j]->e_pc,
										"ur_sskel"           		=>(string)$result[$j]->ur_sskel,
										"i_komputer_serialpc"       =>(string)$result[$j]->i_komputer_serialpc,
										"i_komputer_serialwindow"   =>(string)$result[$j]->i_komputer_serialwindow,
										"n_type"   					=>(string)$result[$j]->n_type);
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	
	
	public function getNoInventarisPCList($pageNumber,$itemPerPage,$nmBarang) {
	 
	$kdBrg  = '2120102';
    $kdBrg1 = '2120203'; 
	$kbr    = '%'.$kdBrg.'%';
	$kbr1   = '%'.$kdBrg1.'%';
    $nmbrg   = '%'.strtoupper($nmBarang).'%';
	$where[] = $kbr;
	$where[] = $kbr1;
	$where[] = $nmbrg;
		 
	$registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 { 
			$hasilAkhir = $db->fetchOne("SELECT count(*) FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
					where substr(a.kd_brg,1,1) = b.kd_gol
					and substr(a.kd_brg,2,2) = b.kd_bid 
					and substr(a.kd_brg,4,2) = b.kd_kel
					and substr(a.kd_brg,6,2) = b.kd_skel
					and substr(a.kd_brg,8,3) = b.kd_sskel
					and (kd_brg like ?  or  kd_brg like ?) 
                   			and b.ur_sskel like ?					
					and not exists(SELECT c.d_perolehan,c.d_anggaran,c.c_barang,c.i_aset 
							 FROM e_ast_komputer_0_tr c
							 where c.d_perolehan = a.tgl_perlh 							 
							 and   c.c_barang = a.kd_brg
							 and   c.i_aset = a.no_aset)",$where);	
								   	    
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		  
		      $result = $db->fetchAll("SELECT  to_char(tgl_perlh,'yyyy') as thn_ang,a.kd_brg,to_char(a.no_aset,'09999') as no_aset,
											a.tgl_perlh,b.ur_sskel,merk_type
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and (kd_brg like ?  or  kd_brg like ?)
											and b.ur_sskel like ?		
											and not exists(SELECT c.d_perolehan,c.d_anggaran,c.c_barang,c.i_aset 
											FROM e_ast_komputer_0_tr c
											where c.d_perolehan = a.tgl_perlh 											
											and c.c_barang = a.kd_brg
											and   c.i_aset = a.no_aset) order by a.thn_ang,a.kd_brg,no_aset limit $xLimit offset $xOffset ",$where);
							
            $jmlResult = count($result);
		 
		 
		   for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("thn_ang"           =>(string)$result[$j]->thn_ang,
									"kd_brg"           =>(string)$result[$j]->kd_brg,
									"no_aset"          =>(string)$result[$j]->no_aset,
									"tgl_perlh"        =>(string)$result[$j]->tgl_perlh,
									"ur_sskel"         =>(string)$result[$j]->ur_sskel,
									"merk_type"		   =>(string)$result[$j]->merk_type);
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getNoInventarisPCListByPeriode($pageNumber,$itemPerPage,$nmBarang,$tglAwal,$tglAkhir) {
	 
	$kdBrg  = '2120102';
    $kdBrg1 = '2120203'; 
	$kbr    = '%'.$kdBrg.'%';
	$kbr1   = '%'.$kdBrg1.'%';
    $nmbrg   = '%'.strtoupper($nmBarang).'%';
	$where[] = $kbr;
	$where[] = $kbr1;
	$where[] = $nmbrg;
	$where[] = $tglAwal;
	$where[] = $tglAkhir;
		 
	$registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 { 
			$hasilAkhir = $db->fetchOne("SELECT count(*) FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
										where substr(a.kd_brg,1,1) = b.kd_gol
										and substr(a.kd_brg,2,2) = b.kd_bid 
										and substr(a.kd_brg,4,2) = b.kd_kel
										and substr(a.kd_brg,6,2) = b.kd_skel
										and substr(a.kd_brg,8,3) = b.kd_sskel
										and (kd_brg like ?  or  kd_brg like ?) 
					                   	and b.ur_sskel like ?
										and A.tgl_perlh between ? and ?
										and not exists(SELECT c.d_perolehan,c.d_anggaran,c.c_barang,c.i_aset 
												 FROM e_ast_komputer_0_tr c
												 where c.d_perolehan = a.tgl_perlh 
												 and   c.d_anggaran = to_char(tgl_perlh,'yyyy') 
												 and   c.c_barang = a.kd_brg
												 and   c.i_aset = a.no_aset)",$where);	
								   	    
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		  
		      $result = $db->fetchAll("SELECT  to_char(tgl_perlh,'yyyy') as thn_ang,a.kd_brg,to_char(a.no_aset,'09999') as no_aset,
											a.tgl_perlh,b.ur_sskel,merk_type
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and (kd_brg like ?  or  kd_brg like ?)
											and b.ur_sskel like ?		
											and A.tgl_perlh between ? and ?
											and not exists(SELECT c.d_perolehan,c.d_anggaran,c.c_barang,c.i_aset 
											FROM e_ast_komputer_0_tr c
											where c.d_perolehan = a.tgl_perlh 
											and   c.d_anggaran = to_char(tgl_perlh,'yyyy')
											and c.c_barang = a.kd_brg
											and   c.i_aset = a.no_aset) order by a.thn_ang,a.kd_brg,no_aset limit $xLimit offset $xOffset ",$where);
							
            $jmlResult = count($result);
		 
		 
		   for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("thn_ang"           	=>(string)$result[$j]->thn_ang,
									"kd_brg"           	=>(string)$result[$j]->kd_brg,
									"no_aset"         	=>(string)$result[$j]->no_aset,
									"tgl_perlh"         =>(string)$result[$j]->tgl_perlh,
									"ur_sskel"          =>(string)$result[$j]->ur_sskel,
									"merk_type"			=>(string)$result[$j]->merk_type);
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	//=======================new not exist ====================== 29 apr 08 ===================================================
	public function getNoInventarisPCList_BUp($pageNumber,$itemPerPage,$nmBarang) {
	 
	$kdBrg  = '2120102';
    $kdBrg1 = '2120203'; 
	$kbr    = '%'.$kdBrg.'%';
	$kbr1   = '%'.$kdBrg1.'%';
    $nmbrg   = '%'.strtoupper($nmBarang).'%';
	$where[] = $kbr;
	$where[] = $kbr1;
	$where[] = $nmbrg;
	//
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasil = $db->fetchAll("SELECT A.thn_ang as thn_ang,
										A.kd_brg as kd_brg,B.ur_sskel as ur_sskel,
										A.tgl_perlh as tgl_perlh,
										to_char(no_aset,'09999') as no_aset
										FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
										where substr(a.kd_brg,1,1) = b.kd_gol
										      and substr(a.kd_brg,2,2) = b.kd_bid 
										      and substr(a.kd_brg,4,2) = b.kd_kel
										      and substr(a.kd_brg,6,2) = b.kd_skel
										      and substr(a.kd_brg,8,3) = b.kd_sskel
										      and (kd_brg like ? or kd_brg like ?)
											  and b.ur_sskel like ?
											  and not exists(select * from e_ast_komputer_0_tr c
                                              where A.thn_ang = c.d_anggaran
                                              and   A.kd_brg  = c.c_barang
                                              and   a.no_aset = c.i_aset)
											  ",$where); 
					
				$hasilAkhir = count($hasil);
			}
			else
			{
			
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
			 
					$result = $db->fetchAll("SELECT A.thn_ang as thn_ang,
										A.kd_brg as kd_brg,B.ur_sskel as ur_sskel,
										A.tgl_perlh as tgl_perlh,
										to_char(no_aset,'09999') as no_aset
										FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
										where substr(a.kd_brg,1,1) = b.kd_gol
										      and substr(a.kd_brg,2,2) = b.kd_bid 
										      and substr(a.kd_brg,4,2) = b.kd_kel
										      and substr(a.kd_brg,6,2) = b.kd_skel
										      and substr(a.kd_brg,8,3) = b.kd_sskel
										      and(kd_brg like ? or kd_brg like ?)
											  and b.ur_sskel like ?
											  and not exists(select * from e_ast_komputer_0_tr c
                                              where A.thn_ang = c.d_anggaran
                                              and   A.kd_brg  = c.c_barang
                                              and   a.no_aset = c.i_aset)											  
											  ORDER BY thn_ang
										limit $xLimit offset $xOffset",$where); 
					
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("thn_ang" 	=>(string)$result[$j]->thn_ang,
											"kd_brg"  	=>(string)$result[$j]->kd_brg,
											"no_aset" 	=>(string)$result[$j]->no_aset,
											"ur_sskel" 	=>(string)$result[$j]->ur_sskel,
											"tgl_perlh" =>(string)$result[$j]->tgl_perlh);
				}	
			}	 
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	//======================================================================================================================
	public function getRuanganList($unitkr) {
	
	$registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $unit = '%.$unitkr.%';
		  
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT i_ruang,i_ruang_lokasi,n_gedung
									FROM e_ast_ruangan_0_tr 
									where  i_orgb_tgjwbgedung like ? ORDER BY i_ruang",$unit);
		
		 $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_ruang"           =>(string)$result[$j]->i_ruang,
									"i_ruang_lokasi"           =>(string)$result[$j]->i_ruang_lokasi,
									"n_gedung"           =>(string)$result[$j]->n_gedung);
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

                                       
    public function ChecktDataMC($mac) {
        $registry = Zend_Registry::getInstance();
	    $db = $registry->get('db');
	 
	    try {
               //$db->beginTransaction();
               $result = $db->fetchOne("SELECT count(*)
						FROM e_ast_komputer_0_tr a 
						WHERE i_komputer_macaddress = ? ",$mac);
                
         if ($result > 0){ 
	           return $result;
          }   
		 
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
    }
	
	public function ChecktDataMCUpd($mac,$thnang,$kdbrg,$noaset) {
		$where[] = $mac;
		$where[] = $thnang;
		$where[] = $kdbrg;
		$where[] = $noaset;
		
        $registry = Zend_Registry::getInstance();
	    $db = $registry->get('db');
	 
	    try {
               //$db->beginTransaction();
               $result = $db->fetchOne("SELECT count(*)
						FROM e_ast_komputer_0_tr a 
						WHERE i_komputer_macaddress = ? 
						and d_anggaran = ?	and c_barang = ? and i_aset = ?
						",$where );
                
		 if ($result > 0){ 
	           return $result;
          }   
		 
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
    }
	
	public function insertDataPC(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	    
	   try {
	      $db->beginTransaction();
	       $atk_mast_prm = array("d_anggaran"         		=>$data['thnang'],
	                           "c_barang"   				=>$data['kdbrg'],
						       "i_aset"  		    		=>$data['noaset'],
							   "d_perolehan"  	    		=>$data['tglPerl'],
							   "i_komputer_macaddress"  	=>$data['macKomp'],
							   "i_komputer_macaddress1"  	=>$data['macKomp2'],
							   "i_komputer_macaddress2"  	=>$data['macKomp3'],
							   "i_komputer_macaddress3"  	=>$data['macKomp4'],
							   "i_komputer_macaddress4"  	=>$data['macKomp5'],
							   "i_komputer_serialpc"        =>$data['noserKomp'],
							   "i_komputer_serialwindow"    =>$data['noserWin'],
							   "c_unit_kerja"  				=>$data['unitkr'],
							   "n_type"  					=>$data['nType'],
							   "e_pc"  						=>$data['ePC'],							   
							   "c_status_lengkap"  			=>$data['nStatus'],
							   "i_entry"       				=>$data['nuser'],
						       "d_entry"       				=>date("Y-m-d"),
							   "i_hw_investasi"  		    =>$data['noinvent'],
							   );
	    
		/*
		 $where[] = $data['d_perolehan'];
	     $where[] = $data['d_anggaran'];
		 $where[] = $data['c_barang'];
		 $where[] = $data['i_aset'];
		 
		 $result = $db->fetchAll('SELECT d_perolehan,d_anggaran,c_barang,i_aset 
								 FROM e_ast_komputer_0_tr where d_perolehan = ? 
									and d_anggaran = ? and c_barang = ? and i_aset = ?',$where);
		 $jmlResult = count($result);
		 echo 'jumla ada..'.$jmlResult;		 
		 if($jmlResult > 0){
			echo 'Data Sudah Ada..';
                }else{
		    echo 'Data belum Ada..'; */
			 
			$db->insert('e_ast_komputer_0_tr',$atk_mast_prm);
			$db->commit();
		 
	        
			
	     return 'sukses';
		 
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	 
	public function updateDataPC(array $data) {
          
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $atk_dtl_parm = array("i_komputer_macaddress"  	 	=>$data['macKomp'],
								"i_komputer_serialpc"        	=>$data['NoserKomp'],
								"i_komputer_serialwindow"    	=>$data['noserWin'],
								"e_pc"                       	=>$data['ePC'],
								"i_komputer_macaddress"  		=>$data['macKomp'],
								"i_komputer_macaddress1"  		=>$data['macKomp2'],
								"i_komputer_macaddress2"  		=>$data['macKomp3'],
								"i_komputer_macaddress3"  		=>$data['macKomp4'],
								"i_komputer_macaddress4"  		=>$data['macKomp5'],
								"i_komputer_serialpc"        	=>$data['NoserKomp'],
								"i_komputer_serialwindow"    	=>$data['NoserWin'],
								"n_type"  						=>$data['nType'],
								"c_status_lengkap"  			=>$data['nStatus'],
								"i_entry"       		     	=>$data['nuser'],
								"d_entry"       		     	=>date("Y-m-d"),
								"i_hw_investasi"    			=>$data['noinvent']);
							   
	        $where[] = "d_anggaran        =  '".trim($data['thnang'])."'";
	        $where[] = "c_barang          =  '".trim($data['kdbrg'])."'";
			$where[] = "i_aset            =  '".trim($data['noaset'])."'";
			$where[] = "d_perolehan       =  '".trim($data['tglPerl'])."'";
			$db->update('e_ast_komputer_0_tr',$atk_dtl_parm, $where);
		 
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	
	//==============================================================================================================================
	//BackUp
	
	public function getDataPCList22($kode) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     //echo "TEST CONN ".$db->getConnection()."<br>";
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 //echo $tahun." ".$jenis." ".$kode;
		 $where[] = $kode;
		 $result = $db->fetchAll("SELECT thn_ang,kd_brg,tgl_perlh,i_komputer_macaddress,c_unit_kerja,
									i_ruang,to_char(no_aset,'09999') as no_aset, b.n_org
									FROM e_ast_komputer_0_tr a, e_org_0_0_tm  b
									WHERE b.i_orgb =a.c_unit_kerja and c_unit_kerja = ? ",$where);
			 
	     return $result;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getRefInvKompList() {
       
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT thn_ang,kd_brg,tgl_perlh,i_komputer_macaddress,c_unit_kerja,
									i_ruang,to_char(no_aset,'09999') as no_aset
									FROM e_ast_komputer_0_tr 
									ORDER BY thn_ang");
									
	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("thn_ang"           =>(string)$result[$j]->thn_ang,
									"kd_brg"           =>(string)$result[$j]->kd_brg,
									"no_aset"           =>(string)$result[$j]->no_aset,
									"tgl_perlh"           =>(string)$result[$j]->tgl_perlh,
									"i_komputer_macaddress"   =>(string)$result[$j]->i_komputer_macaddress,
									"c_unit_kerja"           =>(string)$result[$j]->c_unit_kerja,
									"i_ruang"           =>(string)$result[$j]->i_ruang);
								  
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getPenyerahanInvList($pageNumber,$itemPerPage) {
       $status='T';
	   $kdserah = '%'.'KYN'.'%';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $where[] = $kdserah;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	 FROM e_ast_dir_0_tm A, e_sdm_pegawai_0_tm B
									where A.i_peg_nipterima = B.i_peg_nip
									and i_barang_serah like ? 
									and c_barang_statserah = ?",$where); ;
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		 
		 $result = $db->fetchAll("SELECT A.i_barang_serah,A.d_barang_serah,
									A.i_peg_nipterima,A.i_orgb_penerima,B.n_peg,
									A.i_peg_nippemberi,A.i_orgb_pemberi,
									A.e_keterangan 
									FROM e_ast_dir_0_tm A, e_sdm_pegawai_0_tm B
									where A.i_peg_nipterima = B.i_peg_nip
									and i_barang_serah like ? 
									and c_barang_statserah = ?
									ORDER BY i_barang_serah
								 limit $xLimit offset $xOffset",$where); 
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_barang_serah"            =>(string)$result[$j]->i_barang_serah,
								   "d_barang_serah"            =>(string)$result[$j]->d_barang_serah,
								   "i_peg_nipterima"           =>(string)$result[$j]->i_peg_nipterima,
								   "i_orgb_penerima"           =>(string)$result[$j]->i_orgb_penerima,
								   "n_peg"  		           =>(string)$result[$j]->n_peg,
								   "i_peg_nippemberi"          =>(string)$result[$j]->i_peg_nippemberi,
								   "i_orgb_pemberi"            =>(string)$result[$j]->i_orgb_pemberi,
								   "e_keterangan"              =>(string)$result[$j]->e_keterangan);
								  
								  
							       
		
		  }
         }
        }		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getPenyInvList($nopeng) {
       $status='T';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
	     $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT c_barang,d_aset_thnanggar,to_char(no_aset,'09999') as no_aset,i_ruang, 
		 merk_type,ur_sskel
		 FROM e_ast_dir_item_tm a ,
		      e_sabm_t_master_tm c,e_ast_sskel_0_tr d
		 where  a.i_aset =  c.no_aset  and a.i_barang_serah = ?
		   and  a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
								     and substr(c.kd_brg,2,2) = d.kd_bid 
									 and substr(c.kd_brg,4,2) = d.kd_kel
									 and substr(c.kd_brg,6,2) = d.kd_skel
									 and substr(c.kd_brg,8,3) = d.kd_sskel",$nopeng);
		
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_barang"           =>(string)$result[$j]->c_barang,
								   "d_aset_thnanggar"           =>(string)$result[$j]->d_aset_thnanggar,
								   "no_aset"           =>(string)$result[$j]->no_aset,
								   "i_ruang"           =>(string)$result[$j]->i_ruang,
								   "merk_type"           =>(string)$result[$j]->merk_type,
								   "ur_sskel"           =>(string)$result[$j]->ur_sskel);
								  
								  
			  			       
		
		 }
        }	
        		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	//=============================melihat pc ===================================07apr 08 ==============================================
	
	//==========================utk  perlengkapan ====================================================
	public function queryPencatatanpcByOrgTU($pageNumber, $itemPerPage, $stat, $unitkr) {
		$status=$stat;
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $unitkr;
			$where[] = $status;
			//echo "TEST CONN ".$db->getConnection()."<br>";
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$result = $db->fetchAll("SELECT a.i_atk_ajuanminta,d_atk_ajuanminta 
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										  where a.i_orgb like ? and  a.c_atk_setujuplkp = ? and a.i_orgb=b.i_orgb 
										  ",$where);
			 
		         $jmlResult = count($result);
				 $hasilAkhir = $jmlResult;
			 }
			 else
			 {
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										  where a.i_orgb like ? and  a.c_atk_setujuplkp = ? and a.i_orgb=b.i_orgb 
										  order by i_atk_ajuanminta
										  limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuanminta"      =>(string)$result[$j]->i_atk_ajuanminta,
											"d_atk_ajuanminta"      =>(string)$result[$j]->d_atk_ajuanminta,
											"i_orgb" 				=>(string)$result[$j]->i_orgb,
											"n_orgb" 				=>(string)$result[$j]->n_orgb);
								  
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function queryPencatatanpcByOrgPrnt($pageNumber, $itemPerPage, $stat, $unitkr) {
		$status=$stat;
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $unitkr;
				 $where[] = $status;
				 $where[] = $unitkr;
				 $where[] = $status;
				 $where[] = $status;
				 $where[] = $unitkr;
				 $where[] = $status;
				 $where[] = $unitkr;
				 $where[] = $status;
				 $where[] = $unitkr;
			//echo "TEST CONN ".$db->getConnection()."<br>";
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$result = $db->fetchAll("SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										  where a.i_orgb = ? and  a.c_atk_setujuplkp = ? and a.i_orgb=b.i_orgb 
										  
										  UNION
										  SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										  where i_orgb_parent  = ? and  a.c_atk_setujuplkp = ? and a.i_orgb=b.i_orgb 
										  
										  UNION
										  SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm C 
										  where a.c_atk_setujuplkp = ? and a.i_orgb=C.i_orgb
											and exists ( select * 
											from e_org_0_0_tm  B
											where B.i_orgb_parent  = ?
											         and B.i_orgb = C.i_orgb_parent
											             )  
										  
										  UNION
										  SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										  where a.c_atk_setujuplkp = ? and a.i_orgb=b.i_orgb
											and i_orgb_parent   
													in ( select A.i_orgb from e_org_0_0_tm A
														 where exists 
													       ( select * from e_org_0_0_tm  B
													         where B.i_orgb_parent  = ?
													          and B.i_orgb = A.i_orgb_parent
													        )                 
													    )
										  
										  UNION
										  SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										  where a.c_atk_setujuplkp = ? and a.i_orgb=b.i_orgb
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
										  ",$where);
			 
		         $jmlResult = count($result);
				 $hasilAkhir = $jmlResult;
			 }
			 else
			 {
				 $xLimit=$itemPerPage;
		  		 $xOffset=($pageNumber-1)*$itemPerPage;		
			     
				 $result = $db->fetchAll("SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										  where a.i_orgb = ? and  a.c_atk_setujuplkp = ? and a.i_orgb=b.i_orgb 
										  
										  UNION
										  SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										  where i_orgb_parent  = ? and  a.c_atk_setujuplkp = ? and a.i_orgb=b.i_orgb 
										  
										  UNION
										  SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm C 
										  where a.c_atk_setujuplkp = ? and a.i_orgb=C.i_orgb
											and exists ( select * 
											from e_org_0_0_tm  B
											where B.i_orgb_parent  = ?
											         and B.i_orgb = C.i_orgb_parent
											             )  
										  
										  UNION
										  SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										  where a.c_atk_setujuplkp = ? and a.i_orgb=b.i_orgb
											and i_orgb_parent   
													in ( select A.i_orgb from e_org_0_0_tm A
														 where exists 
													       ( select * from e_org_0_0_tm  B
													         where B.i_orgb_parent  = ?
													          and B.i_orgb = A.i_orgb_parent
													        )                 
													    )
										  
										  UNION
										  SELECT a.i_atk_ajuanminta,d_atk_ajuanminta ,a.i_orgb,n_orgb
										  FROM e_ast_minta_atk_tm a, e_org_0_0_tm b 
										  where a.c_atk_setujuplkp = ? and a.i_orgb=b.i_orgb
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
										  
										  order by i_atk_ajuanminta
										  limit $xLimit offset $xOffset",$where);
			 
		         $jmlResult = count($result);
				
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_atk_ajuanminta"      =>(string)$result[$j]->i_atk_ajuanminta,
											"d_atk_ajuanminta"      =>(string)$result[$j]->d_atk_ajuanminta,
											"i_orgb" 				=>(string)$result[$j]->i_orgb,
											"n_orgb" 				=>(string)$result[$j]->n_orgb);
								  
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	//=======================cek unit tu ==================================================================
	
	public function cekUnitTU($unitkr) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			
			 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 	
				$TU 	= $db->fetchCol("SELECT i_orgb_tu FROM e_org_0_0_tm where i_orgb = ? and i_orgb_tu is not null",$unitkr);
				$unitTU = $TU[0];
				
				return $unitTU;
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	//===============================cek jabatan=============================================================================
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
	
	//============================================================================================================
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
    

	public function getDaftarPCDanPeralatannya($pageNumber,$itemPerPage) {
	   //echo "getDaftarPCDanPeralatannya";
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
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
											and (kd_brg like '2120102%'  or  kd_brg like '2120203%') "); 
											
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
	
	 
    public function getDaftarPeralatanJaringan($pageNumber,$itemPerPage) {
	   //echo "getDaftarPeralatanJaringan";
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
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

    // Ina : Awal : 06-11-2008
	public function getDaftarPC_BelumTercatat($pageNumber,$itemPerPage,$namaBarang,$tahunPerolehan) {
	 
	    $kdBrg  = '2120102';
	    $kdBrg1 = '2120203'; 
		$kbr    = '%'.$kdBrg.'%';
		$kbr1   = '%'.$kdBrg1.'%';	    
		
	   $namaBarang = strtoupper($namaBarang);
	   $nbrg = '%'.$namaBarang.'%';
	  
	   $tahunPerolehan = strtoupper($tahunPerolehan);
	   $thnPeroleh = '%'.$tahunPerolehan.'%';
	   //echo "/thnPeroleh =".$thnPeroleh;
	   
	   $where[] = $kbr;
	   $where[] = $kbr1;
	   $where[] = $nbrg;
	   $where[] = $thnPeroleh;
		 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 { 
			$hasilAkhir = $db->fetchOne("SELECT count(*) FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
										where substr(a.kd_brg,1,1) = b.kd_gol
										and substr(a.kd_brg,2,2) = b.kd_bid 
										and substr(a.kd_brg,4,2) = b.kd_kel
										and substr(a.kd_brg,6,2) = b.kd_skel
										and substr(a.kd_brg,8,3) = b.kd_sskel										
										and (kd_brg like ?  or  kd_brg like ?) 
					                   	and b.ur_sskel like ?			
						                and to_char(a.tgl_perlh,'YYYY') like ?										
										and not exists(SELECT c.d_perolehan,c.d_anggaran,c.c_barang,c.i_aset 
												 FROM e_ast_komputer_0_tr c
												 where c.d_perolehan = a.tgl_perlh 
												 and   c.d_anggaran = to_char(tgl_perlh,'yyyy') 
												 and   c.c_barang = a.kd_brg
												 and   c.i_aset = a.no_aset)",$where);	
								   	    
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		  
		      $result = $db->fetchAll("SELECT  to_char(tgl_perlh,'yyyy') as thn_ang,a.kd_brg,to_char(a.no_aset,'09999') as no_aset,
											a.tgl_perlh,b.ur_sskel,merk_type
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and (kd_brg like ?  or  kd_brg like ?) 
											and b.ur_sskel like ?			
						                    and to_char(a.tgl_perlh,'YYYY') like ?										
											and not exists(SELECT c.d_perolehan,c.d_anggaran,c.c_barang,c.i_aset 
											FROM e_ast_komputer_0_tr c
											where c.d_perolehan = a.tgl_perlh 
											and   c.d_anggaran = to_char(tgl_perlh,'yyyy')
											and c.c_barang = a.kd_brg
											and   c.i_aset = a.no_aset) order by a.thn_ang,a.kd_brg,no_aset limit $xLimit offset $xOffset ",$where);
							
            $jmlResult = count($result);
		 
		 
		   for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("d_aset_thnanggar"           	=>(string)$result[$j]->thn_ang,
									"c_barang"           	=>(string)$result[$j]->kd_brg,
									"i_aset"         	=>(string)$result[$j]->no_aset,
									"d_barang_peroleh"         =>(string)$result[$j]->tgl_perlh,
									"ur_sskel"          =>(string)$result[$j]->ur_sskel,
									"type"			=>(string)$result[$j]->merk_type);
									
									
									
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	// Ina : Akhir : 06-11-2008
	
}
?>