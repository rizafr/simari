<?php
require_once 'Zend/Json.php';

class ast_dirdilkib_Service {
    private static $instance;
   
    // A private constructor; prevents direct creation of object
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
	
    /**
	 * fungsi untuk menampilkan data Pagu ke tabel 'e_pgm_0_0_tr'
	 */
	/*public function getRefPgmListAll() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchAll('SELECT * FROM e_pgm_0_0_tr ORDER BY c_pgm');
	     return $result;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function getRefNamaPgmByKey($kode) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchOne('SELECT n_pgm FROM e_pgm_0_0_tr WHERE c_pgm = ?',$kode);
		 $returnValue = Zend_Json::encode(array($result));
	     return $returnValue;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}*/
	
	public function getDaftarInventarisDirList($pageNumber,$itemPerPage, $kdPemilik) {
		   $registry = Zend_Registry::getInstance();
		   $db = $registry->get('db');
		   try {
			 $where =$kdPemilik;
			 $db->setFetchMode(Zend_Db::FETCH_OBJ);
			if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	  from e_sabm_t_master_tm A, e_ast_sskel_0_tr B
					where substr(A.kd_brg,1,1) = B.kd_gol
					and substr(A.kd_brg,2,2) = B.kd_bid 
					and substr(A.kd_brg,4,2) = B.kd_kel
					and substr(A.kd_brg,6,2) = B.kd_skel
					and substr(A.kd_brg,8,3) = B.kd_sskel 
					and A.tercatat = '1'
					and not exists (select * from e_ast_dir_item_tm C
					                   where C.d_aset_thnanggar = A.thn_ang
					                   and C.c_barang = A.kd_brg
					                   and C.i_aset = A.no_aset)");
			//echo 'jumlahnya'.$hasilAkhir;
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;
			 $query="select A.kd_brg, B.ur_sskel, A.no_aset,A.tgl_buku, 
					A.jns_trn, A.merk_type
					from e_sabm_t_master_tm A, e_ast_sskel_0_tr B
					where substr(A.kd_brg,1,1) = B.kd_gol
					and substr(A.kd_brg,2,2) = B.kd_bid 
					and substr(A.kd_brg,4,2) = B.kd_kel
					and substr(A.kd_brg,6,2) = B.kd_skel
					and substr(A.kd_brg,8,3) = B.kd_sskel 
					and A.tercatat = '1'
					and not exists (select * from e_ast_dir_item_tm C
					                   where C.d_aset_thnanggar = A.thn_ang
					                   and C.c_barang = A.kd_brg
					                   and C.i_aset = A.no_aset)
                     order by A.kd_brg, A.no_aset								   
									   limit $xLimit offset $xOffset";
			 $result = $db->fetchAll($query);
			 $jmlResult = count($result);		 
			 for ($j = 0; $j < $jmlResult; $j++) {		 
				$hasilAkhir[$j] = array("kd_brg"	=>(string)$result[$j]->kd_brg
		                               ,"ur_sskel"           	=>(string)$result[$j]->ur_sskel
		                               ,"no_aset"         	=>(string)$result[$j]->no_aset
		                               ,"tgl_buku"       		=>(string)$result[$j]->tgl_buku
		                               ,"jns_trn"    		=>(string)$result[$j]->jns_trn
		                               ,"merk_type"         	=>(string)$result[$j]->merk_type
		                               );			
			 }					 
		     
		   } return $hasilAkhir;
		   }
		   
		   catch (Exception $e) {
		     echo $e->getMessage().'<br>';
		     return 'gagal <br>';
		   }						
	}
	
	public function getDaftarInventarisDirListCari($pageNumber,$itemPerPage, $kdPemilik,$namaBrg) {
		   $registry = Zend_Registry::getInstance();
		   $db = $registry->get('db');
		   try {
			 $where =$kdPemilik;
			 $db->setFetchMode(Zend_Db::FETCH_OBJ);
			if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	  from e_sabm_t_master_tm A, e_ast_sskel_0_tr B
					where substr(A.kd_brg,1,1) = B.kd_gol
					and substr(A.kd_brg,2,2) = B.kd_bid 
					and substr(A.kd_brg,4,2) = B.kd_kel
					and substr(A.kd_brg,6,2) = B.kd_skel
					and substr(A.kd_brg,8,3) = B.kd_sskel 
					and A.tercatat = '1'
					and B.ur_sskel LIKE '%$namaBrg%'
					and not exists (select * from e_ast_dir_item_tm C
					                   where C.d_aset_thnanggar = A.thn_ang
					                   and C.c_barang = A.kd_brg
					                   and C.i_aset = A.no_aset)");
			//echo 'jumlahnya'.$hasilAkhir;
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;
			 $query="select A.kd_brg, B.ur_sskel, A.no_aset,A.tgl_buku, 
					A.jns_trn, A.merk_type
					from e_sabm_t_master_tm A, e_ast_sskel_0_tr B
					where substr(A.kd_brg,1,1) = B.kd_gol
					and substr(A.kd_brg,2,2) = B.kd_bid 
					and substr(A.kd_brg,4,2) = B.kd_kel
					and substr(A.kd_brg,6,2) = B.kd_skel
					and substr(A.kd_brg,8,3) = B.kd_sskel 
					and A.tercatat = '1'
					and B.ur_sskel LIKE '%$namaBrg%'
					and not exists (select * from e_ast_dir_item_tm C
					                   where C.d_aset_thnanggar = A.thn_ang
					                   and C.c_barang = A.kd_brg
					                   and C.i_aset = A.no_aset)
                     order by A.kd_brg, A.no_aset								   
									   limit $xLimit offset $xOffset";
			 $result = $db->fetchAll($query);
			 $jmlResult = count($result);	
             			 
			 for ($j = 0; $j < $jmlResult; $j++) {		 
				$hasilAkhir[$j] = array("kd_brg"	=>(string)$result[$j]->kd_brg
		                               ,"ur_sskel"           	=>(string)$result[$j]->ur_sskel
		                               ,"no_aset"         	=>(string)$result[$j]->no_aset
		                               ,"tgl_buku"       		=>(string)$result[$j]->tgl_buku
		                               ,"jns_trn"    		=>(string)$result[$j]->jns_trn
		                               ,"merk_type"         	=>(string)$result[$j]->merk_type
		                               );			
			 }					 
		     
		   } return $hasilAkhir;
		   }
		   
		   catch (Exception $e) {
		     echo $e->getMessage().'<br>';
		     return 'gagal <br>';
		   }						
	}
	
	
	public function getDaftarInventarisDilList($pageNumber,$itemPerPage, $kdPemilik) {
		   $registry = Zend_Registry::getInstance();
		   $db = $registry->get('db');
		   try {
			 $where =$kdPemilik;
			 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	  from e_sabm_t_master_tm A, e_ast_sskel_0_tr B
					where substr(A.kd_brg,1,1) = B.kd_gol
					and substr(A.kd_brg,2,2) = B.kd_bid 
					and substr(A.kd_brg,4,2) = B.kd_kel
					and substr(A.kd_brg,6,2) = B.kd_skel
					and substr(A.kd_brg,8,3) = B.kd_sskel 
					and A.tercatat = '2'                                              
					and not exists (select * from e_ast_dil_item_tm C
					                   where C.d_anggaran = A.thn_ang
					                   and C.c_barang = A.kd_brg
					                   and C.i_aset = A.no_aset)");
			//echo 'jumlahnya'.$hasilAkhir;
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;
			 $query=" select A.kd_brg, B.ur_sskel, A.no_aset,A.tgl_buku, 
					A.jns_trn, A.merk_type
					from e_sabm_t_master_tm A, e_ast_sskel_0_tr B
					where substr(A.kd_brg,1,1) = B.kd_gol
					and substr(A.kd_brg,2,2) = B.kd_bid 
					and substr(A.kd_brg,4,2) = B.kd_kel
					and substr(A.kd_brg,6,2) = B.kd_skel
					and substr(A.kd_brg,8,3) = B.kd_sskel 
					and A.tercatat = '2'                                              
					and not exists (select * from e_ast_dil_item_tm C
					                   where C.d_anggaran = A.thn_ang
					                   and C.c_barang = A.kd_brg
					                   and C.i_aset = A.no_aset)
                    order by A.kd_brg, A.no_aset 												
									   limit $xLimit offset $xOffset";
			 $result = $db->fetchAll($query);
			 $jmlResult = count($result);		 
			 for ($j = 0; $j < $jmlResult; $j++) {		 
				$hasilAkhir[$j] = array("kd_brg"	=>(string)$result[$j]->kd_brg
		                               ,"ur_sskel"           	=>(string)$result[$j]->ur_sskel
		                               ,"no_aset"         	=>(string)$result[$j]->no_aset
		                               ,"tgl_buku"       		=>(string)$result[$j]->tgl_buku
		                               ,"jns_trn"    		=>(string)$result[$j]->jns_trn
		                               ,"merk_type"         	=>(string)$result[$j]->merk_type
		                               );			
			 }	
			}
		     return $hasilAkhir;
		   } 
		   catch (Exception $e) {
		     echo $e->getMessage().'<br>';
		     return 'gagal <br>';
		   }						
	}
	
	
	public function getDaftarInventarisDilListCari($pageNumber,$itemPerPage, $kdPemilik, $namaBrg) {
		   $registry = Zend_Registry::getInstance();
		   $db = $registry->get('db');
		   try {
			 $where =$kdPemilik;
			 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	  from e_sabm_t_master_tm A, e_ast_sskel_0_tr B
					where substr(A.kd_brg,1,1) = B.kd_gol
					and substr(A.kd_brg,2,2) = B.kd_bid 
					and substr(A.kd_brg,4,2) = B.kd_kel
					and substr(A.kd_brg,6,2) = B.kd_skel
					and substr(A.kd_brg,8,3) = B.kd_sskel 
					and A.tercatat = '2'                                              
					and B.ur_sskel LIKE '%$namaBrg%'
					and not exists (select * from e_ast_dil_item_tm C
					                   where C.d_anggaran = A.thn_ang
					                   and C.c_barang = A.kd_brg
					                   and C.i_aset = A.no_aset)");
			//echo 'jumlahnya'.$hasilAkhir;
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;
			 $query=" select A.kd_brg, B.ur_sskel, A.no_aset,A.tgl_buku, 
					A.jns_trn, A.merk_type
					from e_sabm_t_master_tm A, e_ast_sskel_0_tr B
					where substr(A.kd_brg,1,1) = B.kd_gol
					and substr(A.kd_brg,2,2) = B.kd_bid 
					and substr(A.kd_brg,4,2) = B.kd_kel
					and substr(A.kd_brg,6,2) = B.kd_skel
					and substr(A.kd_brg,8,3) = B.kd_sskel 
					and A.tercatat = '2'
                    and B.ur_sskel LIKE '%$namaBrg%'					
					and not exists (select * from e_ast_dil_item_tm C
					                   where C.d_anggaran = A.thn_ang
					                   and C.c_barang = A.kd_brg
					                   and C.i_aset = A.no_aset)
                    order by A.kd_brg, A.no_aset 												
									   limit $xLimit offset $xOffset";
			 $result = $db->fetchAll($query);
			 $jmlResult = count($result);		 
			 for ($j = 0; $j < $jmlResult; $j++) {		 
				$hasilAkhir[$j] = array("kd_brg"	=>(string)$result[$j]->kd_brg
		                               ,"ur_sskel"           	=>(string)$result[$j]->ur_sskel
		                               ,"no_aset"         	=>(string)$result[$j]->no_aset
		                               ,"tgl_buku"       		=>(string)$result[$j]->tgl_buku
		                               ,"jns_trn"    		=>(string)$result[$j]->jns_trn
		                               ,"merk_type"         	=>(string)$result[$j]->merk_type
		                               );			
			 }	
			}
		     return $hasilAkhir;
		   } 
		   catch (Exception $e) {
		     echo $e->getMessage().'<br>';
		     return 'gagal <br>';
		   }						
	}
	
	
	public function getDaftarInventarisKibList($pageNumber,$itemPerPage, $kdPemilik) {
		   $registry = Zend_Registry::getInstance();
		   $db = $registry->get('db');
		   try {
			 $where =$kdPemilik;
			 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	  from e_sabm_t_master_tm A, e_ast_sskel_0_tr B
					where substr(A.kd_brg,1,1) = B.kd_gol
					and substr(A.kd_brg,2,2) = B.kd_bid 
					and substr(A.kd_brg,4,2) = B.kd_kel
					and substr(A.kd_brg,6,2) = B.kd_skel
					and substr(A.kd_brg,8,3) = B.kd_sskel 
					and A.tercatat = '3'                                                                 
					and not exists (select * from e_ast_kib_item_tm C
					                   where C.d_anggaran = A.thn_ang
					                   and C.c_barang = A.kd_brg
					                   and C.i_aset = A.no_aset)");
			//echo 'jumlahnya'.$hasilAkhir;
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;
			 $query=" select A.kd_brg, B.ur_sskel, A.no_aset,A.tgl_buku, 
					A.jns_trn, A.merk_type
					from e_sabm_t_master_tm A, e_ast_sskel_0_tr B
					where substr(A.kd_brg,1,1) = B.kd_gol
					and substr(A.kd_brg,2,2) = B.kd_bid 
					and substr(A.kd_brg,4,2) = B.kd_kel
					and substr(A.kd_brg,6,2) = B.kd_skel
					and substr(A.kd_brg,8,3) = B.kd_sskel 
					and A.tercatat = '3'                                                                 
					and not exists (select * from e_ast_kib_item_tm C
					                   where C.d_anggaran = A.thn_ang
					                   and C.c_barang = A.kd_brg
					                   and C.i_aset = A.no_aset)
                    order by A.kd_brg, A.no_aset										   
									   limit $xLimit offset $xOffset";
			 $result = $db->fetchAll($query);
			 $jmlResult = count($result);		 
			 for ($j = 0; $j < $jmlResult; $j++) {		 
				$hasilAkhir[$j] = array("kd_brg"	=>(string)$result[$j]->kd_brg
		                               ,"ur_sskel"           	=>(string)$result[$j]->ur_sskel
		                               ,"no_aset"         	=>(string)$result[$j]->no_aset
		                               ,"tgl_buku"       		=>(string)$result[$j]->tgl_buku
		                               ,"jns_trn"    		=>(string)$result[$j]->jns_trn
		                               ,"merk_type"         	=>(string)$result[$j]->merk_type
		                               );			
			 }					 
		     
		   } return $hasilAkhir;
		   }
		   catch (Exception $e) {
		     echo $e->getMessage().'<br>';
		     return 'gagal <br>';
		   }						
	}
	
	
	public function getDaftarInventarisKibListCari($pageNumber,$itemPerPage, $kdPemilik, $namaBrg) {
		   $registry = Zend_Registry::getInstance();
		   $db = $registry->get('db');
		   try {
			 $where =$kdPemilik;
			 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	  from e_sabm_t_master_tm A, e_ast_sskel_0_tr B
					where substr(A.kd_brg,1,1) = B.kd_gol
					and substr(A.kd_brg,2,2) = B.kd_bid 
					and substr(A.kd_brg,4,2) = B.kd_kel
					and substr(A.kd_brg,6,2) = B.kd_skel
					and substr(A.kd_brg,8,3) = B.kd_sskel 
					and A.tercatat = '3'
                    and B.ur_sskel LIKE '%$namaBrg%'					
					and not exists (select * from e_ast_kib_item_tm C
					                   where C.d_anggaran = A.thn_ang
					                   and C.c_barang = A.kd_brg
					                   and C.i_aset = A.no_aset)");
			//echo 'jumlahnya'.$hasilAkhir;
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;
			 $query=" select A.kd_brg, B.ur_sskel, A.no_aset,A.tgl_buku, 
					A.jns_trn, A.merk_type
					from e_sabm_t_master_tm A, e_ast_sskel_0_tr B
					where substr(A.kd_brg,1,1) = B.kd_gol
					and substr(A.kd_brg,2,2) = B.kd_bid 
					and substr(A.kd_brg,4,2) = B.kd_kel
					and substr(A.kd_brg,6,2) = B.kd_skel
					and substr(A.kd_brg,8,3) = B.kd_sskel 
					and A.tercatat = '3' 
                    and B.ur_sskel LIKE '%$namaBrg%'					
					and not exists (select * from e_ast_kib_item_tm C
					                   where C.d_anggaran = A.thn_ang
					                   and C.c_barang = A.kd_brg
					                   and C.i_aset = A.no_aset)
                    order by A.kd_brg, A.no_aset										   
									   limit $xLimit offset $xOffset";
			 $result = $db->fetchAll($query);
			 $jmlResult = count($result);		 
			 for ($j = 0; $j < $jmlResult; $j++) {		 
				$hasilAkhir[$j] = array("kd_brg"	=>(string)$result[$j]->kd_brg
		                               ,"ur_sskel"           	=>(string)$result[$j]->ur_sskel
		                               ,"no_aset"         	=>(string)$result[$j]->no_aset
		                               ,"tgl_buku"       		=>(string)$result[$j]->tgl_buku
		                               ,"jns_trn"    		=>(string)$result[$j]->jns_trn
		                               ,"merk_type"         	=>(string)$result[$j]->merk_type
		                               );			
			 }					 
		     
		   } return $hasilAkhir;
		   }
		   catch (Exception $e) {
		     echo $e->getMessage().'<br>';
		     return 'gagal <br>';
		   }						
	}
	
	// Ina : 11-12-2008 : Awal
	public function getReferensiDIR($pageNumber,$itemPerPage) 
	{
					
		   $registry = Zend_Registry::getInstance();
		   $db = $registry->get('db');
		   try {
			 
			 $db->setFetchMode(Zend_Db::FETCH_OBJ);
			if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$result = $db->fetchAll("select distinct A.kd_brg, B.ur_sskel						   	  
					from e_sabm_t_master_tm A, e_ast_sskel_0_tr B
					where substr(A.kd_brg,1,1) = B.kd_gol
					and substr(A.kd_brg,2,2) = B.kd_bid 
					and substr(A.kd_brg,4,2) = B.kd_kel
					and substr(A.kd_brg,6,2) = B.kd_skel
					and substr(A.kd_brg,8,3) = B.kd_sskel 
					and A.tercatat = '1'
					");
					
			$hasilAkhir = count($result);		
			//echo 'jumlahnya'.$hasilAkhir;
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;
			 $query="select distinct A.kd_brg, B.ur_sskel
					from e_sabm_t_master_tm A, e_ast_sskel_0_tr B
					where substr(A.kd_brg,1,1) = B.kd_gol
					and substr(A.kd_brg,2,2) = B.kd_bid 
					and substr(A.kd_brg,4,2) = B.kd_kel
					and substr(A.kd_brg,6,2) = B.kd_skel
					and substr(A.kd_brg,8,3) = B.kd_sskel 
					and A.tercatat = '1'
					order by B.ur_sskel							   
					limit $xLimit offset $xOffset";
					
			 $result = $db->fetchAll($query);
			 $jmlResult = count($result);		 
			 for ($j = 0; $j < $jmlResult; $j++) {		 
				$hasilAkhir[$j] = array("kd_brg"	=>(string)$result[$j]->kd_brg
		                               ,"ur_sskel"           	=>(string)$result[$j]->ur_sskel		                               
		                               );			
			 }					 
		     
		   } return $hasilAkhir;
		   }
		   
		   catch (Exception $e) {
		     echo $e->getMessage().'<br>';
		     return 'gagal <br>';
		   }						
	}
	
	
	public function getReferensiDIL($pageNumber,$itemPerPage) {
		   $registry = Zend_Registry::getInstance();
		   $db = $registry->get('db');
		   try {
			 
			 $db->setFetchMode(Zend_Db::FETCH_OBJ);
			if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$result = $db->fetchAll("select distinct A.kd_brg, B.ur_sskel						   	  
					from e_sabm_t_master_tm A, e_ast_sskel_0_tr B
					where substr(A.kd_brg,1,1) = B.kd_gol
					and substr(A.kd_brg,2,2) = B.kd_bid 
					and substr(A.kd_brg,4,2) = B.kd_kel
					and substr(A.kd_brg,6,2) = B.kd_skel
					and substr(A.kd_brg,8,3) = B.kd_sskel 
					and A.tercatat = '2'
					");
					
			$hasilAkhir = count($result);		
			//echo 'jumlahnya'.$hasilAkhir;
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;
			 $query="select distinct A.kd_brg, B.ur_sskel
					from e_sabm_t_master_tm A, e_ast_sskel_0_tr B
					where substr(A.kd_brg,1,1) = B.kd_gol
					and substr(A.kd_brg,2,2) = B.kd_bid 
					and substr(A.kd_brg,4,2) = B.kd_kel
					and substr(A.kd_brg,6,2) = B.kd_skel
					and substr(A.kd_brg,8,3) = B.kd_sskel 
					and A.tercatat = '2'
					order by B.ur_sskel							   
					limit $xLimit offset $xOffset";
					
			 $result = $db->fetchAll($query);
			 $jmlResult = count($result);		 
			 for ($j = 0; $j < $jmlResult; $j++) {		 
				$hasilAkhir[$j] = array("kd_brg"	=>(string)$result[$j]->kd_brg
		                               ,"ur_sskel"           	=>(string)$result[$j]->ur_sskel		                               
		                               );			
			 }					 
		     
		   } return $hasilAkhir;
		   }
		   
		   catch (Exception $e) {
		     echo $e->getMessage().'<br>';
		     return 'gagal <br>';
		   }						
	}
	
	public function getReferensiKIB($pageNumber,$itemPerPage) {
		   $registry = Zend_Registry::getInstance();
		   $db = $registry->get('db');
		   try {
			 
			 $db->setFetchMode(Zend_Db::FETCH_OBJ);
			if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$result = $db->fetchAll("select distinct A.kd_brg, B.ur_sskel						   	  
					from e_sabm_t_master_tm A, e_ast_sskel_0_tr B
					where substr(A.kd_brg,1,1) = B.kd_gol
					and substr(A.kd_brg,2,2) = B.kd_bid 
					and substr(A.kd_brg,4,2) = B.kd_kel
					and substr(A.kd_brg,6,2) = B.kd_skel
					and substr(A.kd_brg,8,3) = B.kd_sskel 
					and A.tercatat = '3'
					");
					
			$hasilAkhir = count($result);		
			//echo 'jumlahnya'.$hasilAkhir;
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;
			 $query="select distinct A.kd_brg, B.ur_sskel
					from e_sabm_t_master_tm A, e_ast_sskel_0_tr B
					where substr(A.kd_brg,1,1) = B.kd_gol
					and substr(A.kd_brg,2,2) = B.kd_bid 
					and substr(A.kd_brg,4,2) = B.kd_kel
					and substr(A.kd_brg,6,2) = B.kd_skel
					and substr(A.kd_brg,8,3) = B.kd_sskel 
					and A.tercatat = '3'
					order by B.ur_sskel							   
					limit $xLimit offset $xOffset";
					
			 $result = $db->fetchAll($query);
			 $jmlResult = count($result);		 
			 for ($j = 0; $j < $jmlResult; $j++) {		 
				$hasilAkhir[$j] = array("kd_brg"	=>(string)$result[$j]->kd_brg
		                               ,"ur_sskel"           	=>(string)$result[$j]->ur_sskel		                               
		                               );			
			 }					 
		     
		   } return $hasilAkhir;
		   }
		   
		   catch (Exception $e) {
		     echo $e->getMessage().'<br>';
		     return 'gagal <br>';
		   }						
	}
	// Ina : 11-12-2008 : AKhir
	
}
?>