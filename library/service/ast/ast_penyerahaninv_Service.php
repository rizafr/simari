<?php

class ast_penyerahaninv_Service {
   
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
	 public function queryUnitKerja($unitkerja) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     //$where[] = "c_lokasi_unitkerja = '".$unitkerja."'";
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 //$result = $db->fetchAll('SELECT i_peg_nip,n_organisasi FROM sdm.tm_organisasi where i_peg_nip=?',$nip);	
        $result = $db->fetchAll("SELECT c_satker,n_unitkerja FROM sdm.tr_unitkerja where c_satker='".$unitkerja."'");
         $jmlResult = count($result);
         //$jmlResult = 1;
		 //echo "sql : ".$sql."<br/>";
		 //print_r($result);
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_peg_nip"           =>(string)$result[$j]->c_satker,
								   "n_organisasi"          =>(string)$result[$j]->n_unitkerja);
		   
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function insertAstDirTm(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
		 $atk_mast_prm = array("i_barang_serah"         		=>$data['nopeng'],
	                           "d_barang_serah"    	        	=>$data['dbarangserah'],
						       "i_peg_nipterima"                =>$data['nipPenerima'],
							   "i_peg_nippemberi"               =>$data['nipPemberi'],
							   "i_barang_baserah"  	            =>' ',
							   "i_orgb_pemberi"                 =>$data['orgPemberi'],
							   "i_orgb_penerima"                =>$data['orgPenerima'],
						       "c_barang_statserah"  	        =>$data['status'],
							   "i_surat"					    =>$data['nosurat'],
							   "e_keterangan"					=>$data['ket'],
						       "i_entry"       		            =>$data['nuser'],
						       "d_entry"       		        =>date("Y-m-d"));
	    
	     $db->insert('aset.tm_dir_0',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function insertAstDirItemTm(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
		 $atk_mast_prm = array("i_barang_serah"         		=>$data['nopeng'],
	                           "d_aset_thnanggar"    	        =>$data['dasetthnanggar'],
						       "c_barang"                       =>$data['cbarang'],
							   "i_aset"              		    =>$data['iaset'],
							   "d_barang_peroleh"  	            =>$data['dbarangperoleh'], 
							   "i_jumlah_barang"                        =>$data['i_jumlah_barang'],
							   "i_ruang"                        =>$data['iruang'],
							   "i_entry"       		            =>$data['nuser'],							   
						       "d_entry"       		        =>date("Y-m-d"));
	    
	     $db->insert('aset.tm_dir_item',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	} 
     
		 
	public function DeleteAstDirTm($nopeng) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     
	     $db->beginTransaction();
	     $where[] = "i_barang_serah = '". $nopeng ."'";
		 $db->delete('aset.tm_dir_0', $where);
		 $db->delete('aset.tm_dir_item', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
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
										from sdm.tr_jabatan A,  sdm.tm_pegawai B,sdm.tr_unitkerja c
										where a.c_jabatan = b.c_jabatan
										and (c.c_lokasi_unitkerja = ? or c.c_satker = ?)
										",$where);  
       		
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
        // $TU 	= $db->fetchCol("SELECT i_orgb_tu FROM e_org_0_0_tm where i_orgb = ? and i_orgb_tu is not null",$unitkr); 
         $TU 	= $db->fetchCol("SELECT c_dept FROM sdm.tr_unitkerja where c_satker = ?",$unitkr); 
        return $TU;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	} 
     public function getParent($unitkr) {
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
         // $parent 	= $db->fetchCol("select A.i_orgb, A.i_orgb_parent , A.c_orgb_level
					  // from e_org_0_0_tm A
					  // where A.i_orgb = ?
               
				// union all
					// select A.i_orgb, A.i_orgb_parent , A.c_orgb_level
					// from e_org_0_0_tm A
					// where A.i_orgb_parent = ?
               
				// union all
					// select A.i_orgb, A.i_orgb_parent , A.c_orgb_level
					// from e_org_0_0_tm A
					// where exists ( select * 
							// from e_org_0_0_tm  B
							// where B.i_orgb_parent  =?
							// and B.i_orgb = A.i_orgb_parent
							// )                 
				// union all               
					// select X.i_orgb, X.i_orgb_parent , X.c_orgb_level
					// from e_org_0_0_tm X
					// where X.i_orgb_parent 
					// in ( select A.i_orgb from e_org_0_0_tm A
					// where exists 
						// ( select * from e_org_0_0_tm  B
							// where B.i_orgb_parent  = ?
							// and B.i_orgb = A.i_orgb_parent
						// )                 
					// )

				// union all               
					// select X.i_orgb, X.i_orgb_parent , X.c_orgb_level
					// from e_org_0_0_tm X
					// where X.i_orgb_parent 
					// in ( select C.i_orgb from e_org_0_0_tm C
						// where C.i_orgb_parent 
						// in ( select A.i_orgb from e_org_0_0_tm A
						// where exists 
								// ( select * from e_org_0_0_tm  B
								// where B.i_orgb_parent  = ?
								// and B.i_orgb = A.i_orgb_parent
								// )                 
							// )
						// )",$where); 
			$parent 	= $db->fetchCol("select A.c_satker, A.c_dept , A.c_orgb_level
					  from e_org_0_0_tm A
					  where A.i_orgb = ?
               
				union all
					select A.i_orgb, A.i_orgb_parent , A.c_orgb_level
					from e_org_0_0_tm A
					where A.i_orgb_parent = ?
               
				union all
					select A.i_orgb, A.i_orgb_parent , A.c_orgb_level
					from e_org_0_0_tm A
					where exists ( select * 
							from e_org_0_0_tm  B
							where B.i_orgb_parent  =?
							and B.i_orgb = A.i_orgb_parent
							)                 
				union all               
					select X.i_orgb, X.i_orgb_parent , X.c_orgb_level
					from e_org_0_0_tm X
					where X.i_orgb_parent 
					in ( select A.i_orgb from e_org_0_0_tm A
					where exists 
						( select * from e_org_0_0_tm  B
							where B.i_orgb_parent  = ?
							and B.i_orgb = A.i_orgb_parent
						)                 
					)

				union all               
					select X.i_orgb, X.i_orgb_parent , X.c_orgb_level
					from e_org_0_0_tm X
					where X.i_orgb_parent 
					in ( select C.i_orgb from e_org_0_0_tm C
						where C.i_orgb_parent 
						in ( select A.i_orgb from e_org_0_0_tm A
						where exists 
								( select * from e_org_0_0_tm  B
								where B.i_orgb_parent  = ?
								and B.i_orgb = A.i_orgb_parent
								)                 
							)
						)",$where); 
		return $parent;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
public function getPenyerahanInvList($pageNumber,$itemPerPage,$unitkr) {
       $status='T';
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    //and i_barang_serah like '%AYN%' 
		 $where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	 FROM aset.tm_dir_0 A , sdm.tm_pegawai B, sdm.tm_pegawai C,sdm.tr_unitkerja d 
								where d.c_satker = ? 
								and c_barang_statserah = ? 								
								and A.i_peg_nipterima = B.i_peg_nip
								and b.c_eselon_i=d.c_eselon_i
								and b.c_eselon_ii=d.c_eselon_ii
								and b.c_eselon_iii=d.c_eselon_iii
								and b.c_eselon_iv=d.c_eselon_iv
								and b.c_eselon_v=d.c_eselon_v								 
								and A.i_peg_nippemberi = C.i_peg_nip
								and a.i_orgb_penerima = d.c_lokasi_unitkerja
								and c.c_eselon_i=d.c_eselon_i
								and c.c_eselon_ii=d.c_eselon_ii
								and c.c_eselon_iii=d.c_eselon_iii
								and c.c_eselon_iv=d.c_eselon_iv
								and c.c_eselon_v=d.c_eselon_v								 
								and a.i_orgb_pemberi = d.c_lokasi_unitkerja
								",$where);  
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		 
		 $result = $db->fetchAll("SELECT i_barang_serah,d_barang_serah,i_peg_nipterima,i_orgb_penerima,
								i_peg_nippemberi,i_orgb_pemberi,A.e_keterangan,d.n_unitkerja as namaunitkerjapenerima,d.n_unitkerja as namaunitkerjapemberi, 
								B.n_peg as penerima, C.n_peg as pemberi,a.i_surat
								FROM aset.tm_dir_0 A , sdm.tm_pegawai B, sdm.tm_pegawai C,sdm.tr_unitkerja d 
								where d.c_satker = ? 
								and c_barang_statserah = ? 								
								and A.i_peg_nipterima = B.i_peg_nip
								and b.c_eselon_i=d.c_eselon_i
								and b.c_eselon_ii=d.c_eselon_ii
								and b.c_eselon_iii=d.c_eselon_iii
								and b.c_eselon_iv=d.c_eselon_iv
								and b.c_eselon_v=d.c_eselon_v								 
								and A.i_peg_nippemberi = C.i_peg_nip
								and a.i_orgb_penerima = d.c_lokasi_unitkerja
								and c.c_eselon_i=d.c_eselon_i
								and c.c_eselon_ii=d.c_eselon_ii
								and c.c_eselon_iii=d.c_eselon_iii
								and c.c_eselon_iv=d.c_eselon_iv
								and c.c_eselon_v=d.c_eselon_v								 
								and a.i_orgb_pemberi = d.c_lokasi_unitkerja								 					 
								order by i_barang_serah
								 limit $xLimit offset $xOffset",$where); 								 
								 

         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_barang_serah"           =>(string)$result[$j]->i_barang_serah,
								   "d_barang_serah"           =>(string)$result[$j]->d_barang_serah,
								   "i_peg_nipterima"           =>(string)$result[$j]->i_peg_nipterima,
								   "i_orgb_penerima"           =>(string)$result[$j]->i_orgb_penerima,
								   "i_peg_nippemberi"           =>(string)$result[$j]->i_peg_nippemberi,
								   "n_peg_penerima"           =>(string)$result[$j]->penerima,
								   "n_peg_pemberi"           =>(string)$result[$j]->pemberi,
								   "i_orgb_pemberi"           =>(string)$result[$j]->i_orgb_pemberi,
								   "namaunitkerjapenerima"           =>(string)$result[$j]->namaunitkerjapenerima,
								   "namaunitkerjapemberi"           =>(string)$result[$j]->namaunitkerjapemberi,
								   "i_surat"           =>(string)$result[$j]->i_surat,
								   "e_keterangan"           =>(string)$result[$j]->e_keterangan);
								  
								  
							       
		
		  }
         }
        }	
         		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getPenyerahanInvListA($pageNumber,$itemPerPage,$unitkr) {
       $status='Y';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
	    
		 $where[] = $unitkr;
		 $where[] = $status;
		 
		 
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	 FROM aset.tm_dir_0 A, sdm.tm_pegawai B, sdm.tm_pegawai C,sdm.tr_unitkerja d 
								where a.c_barang_statserah = '$status' 
								and i_barang_serah like '%AYN%' 
								and A.i_peg_nipterima = B.i_peg_nip								 
								and A.i_peg_nippemberi = C.i_peg_nip
								and b.c_lokasi_unitkerja = d.c_lokasi_unitkerja
								and b.c_eselon_i = d.c_eselon_i
								and b.c_eselon_ii = d.c_eselon_ii
								and b.c_eselon_iii = d.c_eselon_iii
								and b.c_eselon_iv = d.c_eselon_iv
								and b.c_eselon_v = d.c_eselon_v
								and c.c_lokasi_unitkerja = d.c_lokasi_unitkerja
								and c.c_eselon_i = d.c_eselon_i
								and c.c_eselon_ii = d.c_eselon_ii
								and c.c_eselon_iii = d.c_eselon_iii
								and c.c_eselon_iv = d.c_eselon_iv
								and c.c_eselon_v = d.c_eselon_v
								and d.c_satker = '$unitkr'");  
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		 $result = $db->fetchAll("SELECT i_barang_serah,d_barang_serah,i_peg_nipterima,i_orgb_penerima, 
								i_peg_nippemberi,i_orgb_pemberi,A.e_keterangan, B.n_peg as penerima, C.n_peg as pemberi,d.n_unitkerja,a.i_surat
								FROM aset.tm_dir_0 A, sdm.tm_pegawai B, sdm.tm_pegawai C,sdm.tr_unitkerja d 
								where a.c_barang_statserah = '$status' 
								and i_barang_serah like '%AYN%' 
								and A.i_peg_nipterima = B.i_peg_nip								 
								and A.i_peg_nippemberi = C.i_peg_nip
								and b.c_lokasi_unitkerja = d.c_lokasi_unitkerja
								and b.c_eselon_i = d.c_eselon_i
								and b.c_eselon_ii = d.c_eselon_ii
								and b.c_eselon_iii = d.c_eselon_iii
								and b.c_eselon_iv = d.c_eselon_iv
								and b.c_eselon_v = d.c_eselon_v
								and c.c_lokasi_unitkerja = d.c_lokasi_unitkerja
								and c.c_eselon_i = d.c_eselon_i
								and c.c_eselon_ii = d.c_eselon_ii
								and c.c_eselon_iii = d.c_eselon_iii
								and c.c_eselon_iv = d.c_eselon_iv
								and c.c_eselon_v = d.c_eselon_v
								and d.c_satker = '$unitkr'							 
								order by i_barang_serah 
								limit $xLimit offset $xOffset"); 
								
		 // $result = $db->fetchAll("SELECT i_barang_serah,d_barang_serah,i_peg_nipterima,i_orgb_penerima, 
		                         // i_peg_nippemberi,i_orgb_pemberi,A.e_keterangan, B.n_peg as penerima, C.n_peg as pemberi
								 // FROM aset.tm_dir_0 A, sdm.tm_pegawai B, sdm.tm_pegawai C 
							   	 // where i_orgb_penerima like ? and c_barang_statserah = ? 
							   	 // and i_barang_serah like '%AYN%' 
								 // and A.i_peg_nipterima = B.i_peg_nip								 
								 // and A.i_peg_nippemberi = C.i_peg_nip								 
								 // order by i_barang_serah
								 // limit $xLimit offset $xOffset",$where); 
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_barang_serah"           =>(string)$result[$j]->i_barang_serah,
								   "d_barang_serah"           =>(string)$result[$j]->d_barang_serah,
								   "i_peg_nipterima"           =>(string)$result[$j]->i_peg_nipterima,
								   "i_orgb_penerima"           =>(string)$result[$j]->i_orgb_penerima,
								   "n_peg_penerima"           =>(string)$result[$j]->penerima,
								   "n_peg_pemberi"           =>(string)$result[$j]->pemberi,
								   "i_peg_nippemberi"           =>(string)$result[$j]->i_peg_nippemberi,
								   "i_orgb_pemberi"           =>(string)$result[$j]->i_orgb_pemberi,
								   "n_unitkerja"           =>(string)$result[$j]->n_unitkerja,
								   "i_surat"           =>(string)$result[$j]->i_surat,
								   "e_keterangan"           =>(string)$result[$j]->e_keterangan);
								  
								  
							       
		
		  }
		 
         }
        }		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	
	public function getPenyerahanInvLainList($pageNumber,$itemPerPage,$unitkr) {
       $status='T';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
         //echo "unit kerja :".$unitkr."<br/>";		 
         //echo "status :".$status."<br/>";		 
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		  if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	 FROM aset.tm_dil_0 a,sdm.tm_pegawai b,sdm.tr_unitkerja c,sdm.tm_pegawai d 
								 where  substr(a.i_barang_serah,1,6) =  ?  
								 and a.c_barang_statserah like ? 
								 and a.i_barang_serah like '%AYN%'
								 and a.i_peg_nipterima = b.i_peg_nip                                  								 
								 and a.i_orgb_penerima = c.c_lokasi_unitkerja
								 and b.c_lokasi_unitkerja = c.c_lokasi_unitkerja
								 and b.c_eselon_i = c.c_eselon_i
								 and b.c_eselon_ii = c.c_eselon_ii
								 and b.c_eselon_iii = c.c_eselon_iii
								 and b.c_eselon_iv = c.c_eselon_iv
								 and b.c_eselon_v = c.c_eselon_v
								 and a.i_peg_nippemberi=d.i_peg_nip
								 and a.i_orgb_pemberi = c.c_lokasi_unitkerja
								 and d.c_lokasi_unitkerja = c.c_lokasi_unitkerja
								 and d.c_eselon_i = c.c_eselon_i
								 and d.c_eselon_ii = c.c_eselon_ii
								 and d.c_eselon_iii = c.c_eselon_iii
								 and d.c_eselon_iv = c.c_eselon_iv
								 and d.c_eselon_v = c.c_eselon_v
								 and c.c_satker=substr(a.i_barang_serah,1,6)",$where); ;
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		 $result = $db->fetchAll("SELECT a.i_barang_serah,a.d_barang_serah,a.i_peg_nipterima,a.i_orgb_penerima,
		                         a.i_peg_nippemberi,a.i_orgb_pemberi,a.e_keterangan,b.n_peg as namaPenerima,c.n_unitkerja as namaunitpemberi
								 ,c.n_unitkerja as namaunitpenerima,d.n_peg as namaPemberi,a.i_surat
								 FROM aset.tm_dil_0 a,sdm.tm_pegawai b,sdm.tr_unitkerja c,sdm.tm_pegawai d 
								 where  substr(a.i_barang_serah,1,6) =  ?  
								 and a.c_barang_statserah like ? 
								 and a.i_barang_serah like '%AYN%'
								 and a.i_peg_nipterima = b.i_peg_nip                                  								 
								 and a.i_orgb_penerima = c.c_lokasi_unitkerja
								 and b.c_lokasi_unitkerja = c.c_lokasi_unitkerja
								 and b.c_eselon_i = c.c_eselon_i
								 and b.c_eselon_ii = c.c_eselon_ii
								 and b.c_eselon_iii = c.c_eselon_iii
								 and b.c_eselon_iv = c.c_eselon_iv
								 and b.c_eselon_v = c.c_eselon_v
								 and a.i_peg_nippemberi=d.i_peg_nip
								 and a.i_orgb_pemberi = c.c_lokasi_unitkerja
								 and d.c_lokasi_unitkerja = c.c_lokasi_unitkerja
								 and d.c_eselon_i = c.c_eselon_i
								 and d.c_eselon_ii = c.c_eselon_ii
								 and d.c_eselon_iii = c.c_eselon_iii
								 and d.c_eselon_iv = c.c_eselon_iv
								 and d.c_eselon_v = c.c_eselon_v
								 and c.c_satker=substr(a.i_barang_serah,1,6)
								  order by a.i_barang_serah
								 limit $xLimit offset $xOffset",$where); 
         $jmlResult = count($result);
		 //echo "Jumlah : ".$jmlResult."<br/>";
		 //print_r($result);
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_barang_serah"           =>(string)$result[$j]->i_barang_serah,
								   "d_barang_serah"           =>(string)$result[$j]->d_barang_serah,
								   "i_peg_nipterima"           =>(string)$result[$j]->i_peg_nipterima,
								   "namaPenerima"           =>(string)$result[$j]->namapenerima,
								   "i_orgb_penerima"           =>(string)$result[$j]->i_orgb_penerima,
								   "i_peg_nippemberi"           =>(string)$result[$j]->i_peg_nippemberi,
								   "namaPemberi"           =>(string)$result[$j]->namapemberi,
								   "i_orgb_pemberi"           =>(string)$result[$j]->i_orgb_pemberi,
								   "i_surat"           =>(string)$result[$j]->i_surat,
								   "namaunitpemberi"           =>(string)$result[$j]->namaunitpemberi,
								   "namaunitpenerima"           =>(string)$result[$j]->namaunitpenerima,
								   "e_keterangan"           =>(string)$result[$j]->e_keterangan);
								  
								  
							       
		
		 }
        }
		}
         	
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getPenyerahanInvLainListA($pageNumber,$itemPerPage,$unitkr) {
       $status='Y';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;		  
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		  if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	  FROM aset.tm_dil_0 a,sdm.tm_pegawai b,sdm.tm_pegawai c,sdm.tr_unitkerja d,sdm.tr_unitkerja e
								 where 
								 a.c_barang_statserah like '$status' 
								 and a.i_barang_serah like '%AYN%'
								 and a.i_peg_nipterima = b.i_peg_nip
								 and a.i_peg_nippemberi = c.i_peg_nip
								 and b.c_lokasi_unitkerja = d.c_lokasi_unitkerja
								 and b.c_eselon_i = d.c_eselon_i
								 and b.c_eselon_ii = d.c_eselon_ii
								 and b.c_eselon_iii= d.c_eselon_iii
								 and b.c_eselon_iv = d.c_eselon_iv
								 and b.c_eselon_v = d.c_eselon_v
								 and d.c_satker = '$unitkr'
								 and c.c_lokasi_unitkerja = e.c_lokasi_unitkerja
								 and c.c_eselon_i = e.c_eselon_i
								 and c.c_eselon_ii = e.c_eselon_ii
								 and c.c_eselon_iii= e.c_eselon_iii
								 and c.c_eselon_iv = e.c_eselon_iv
								 and c.c_eselon_v = e.c_eselon_v
								 and e.c_satker = '$unitkr'");
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
			 $sql = "SELECT a.i_barang_serah,a.d_barang_serah,a.i_peg_nipterima,a.i_orgb_penerima,
		                         a.i_peg_nippemberi,a.i_orgb_pemberi,a.e_keterangan,b.n_peg as namapenerima,c.n_peg as namapemberi,
								 d.n_unitkerja as namaunitpemberi,e.n_unitkerja as namaunitpenerima,a.i_surat
								 FROM aset.tm_dil_0 a,sdm.tm_pegawai b,sdm.tm_pegawai c,sdm.tr_unitkerja d,sdm.tr_unitkerja e
								 where 
								 a.c_barang_statserah like '$status' 
								 and a.i_barang_serah like '%AYN%'
								 and a.i_peg_nipterima = b.i_peg_nip
								 and a.i_peg_nippemberi = c.i_peg_nip
								 and a.i_orgb_penerima = b.c_lokasi_unitkerja
								 and a.i_orgb_pemberi = c.c_lokasi_unitkerja
								 and b.c_lokasi_unitkerja = d.c_lokasi_unitkerja
								 and b.c_eselon_i = d.c_eselon_i
								 and b.c_eselon_ii = d.c_eselon_ii
								 and b.c_eselon_iii= d.c_eselon_iii
								 and b.c_eselon_iv = d.c_eselon_iv
								 and b.c_eselon_v = d.c_eselon_v
								 and d.c_satker = '$unitkr'
								 and c.c_lokasi_unitkerja = e.c_lokasi_unitkerja
								 and c.c_eselon_i = e.c_eselon_i
								 and c.c_eselon_ii = e.c_eselon_ii
								 and c.c_eselon_iii= e.c_eselon_iii
								 and c.c_eselon_iv = e.c_eselon_iv
								 and c.c_eselon_v = e.c_eselon_v
								 and e.c_satker = '$unitkr'								 
								 order by a.i_barang_serah
								 limit $xLimit offset $xOffset";
			//echo $sql;
		 $result = $db->fetchAll($sql); 
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_barang_serah"           =>(string)$result[$j]->i_barang_serah,
								   "d_barang_serah"           =>(string)$result[$j]->d_barang_serah,
								   "i_peg_nipterima"           =>(string)$result[$j]->i_peg_nipterima,
								   "i_orgb_penerima"           =>(string)$result[$j]->i_orgb_penerima,
								   "namapenerima"           =>(string)$result[$j]->namapenerima,
								   "namaunitpenerima"           =>(string)$result[$j]->namaunitpenerima,
								   "i_peg_nippemberi"           =>(string)$result[$j]->i_peg_nippemberi,
								   "i_orgb_pemberi"           =>(string)$result[$j]->i_orgb_pemberi,
								   "namapemberi"           =>(string)$result[$j]->namapemberi,
								   "i_surat"           =>(string)$result[$j]->i_surat,
								   "namaunitpemberi"           =>(string)$result[$j]->namaunitpemberi,
								   "e_keterangan"           =>(string)$result[$j]->e_keterangan);
								  
								  
							       
		
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
		 merk_type,ur_sskel,i_jumlah_barang,c.rph_aset,d.satuan
		 FROM aset.tm_dir_item a ,
		      aset.tm_masterhm c,aset.tm_sskel d
		   where  a.i_aset =  c.no_aset  and a.i_barang_serah = ?
		   and c.thn_ang = a.d_aset_thnanggar
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
								   "i_jumlah_barang"           =>(string)$result[$j]->i_jumlah_barang,
								   "rph_aset"           =>(string)$result[$j]->rph_aset,
								   "satuan"           =>(string)$result[$j]->satuan,
								   "ur_sskel"           =>(string)$result[$j]->ur_sskel);
								  
								  
			  			       
		
		 }
        }	
        		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
 public function getPenyInvListDil($nopeng) {
       $status='T';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	    
	   try {
	     $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT c_barang,d_anggaran,to_char(i_aset,'09999') as no_aset,n_aset_lokasifisik, 
		 merk_type,ur_sskel,i_jumlah_barang,c.rph_aset,d.satuan
		 FROM aset.tm_dil_item a ,
		      aset.tm_masterhm c,aset.tm_sskel d
		   where  a.i_aset =  c.no_aset  and a.i_barang_serah = ?
		   and c.thn_ang = a.d_anggaran
		   and  a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
								     and substr(c.kd_brg,2,2) = d.kd_bid 
									 and substr(c.kd_brg,4,2) = d.kd_kel
									 and substr(c.kd_brg,6,2) = d.kd_skel
									 and substr(c.kd_brg,8,3) = d.kd_sskel",$nopeng);
		
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		    
           $hasilAkhir[$j] = array("c_barang"           =>(string)$result[$j]->c_barang,
								   "d_aset_thnanggar"           =>(string)$result[$j]->d_anggaran,
								   "no_aset"           =>(string)$result[$j]->no_aset,
								   "i_ruang"           =>(string)$result[$j]->n_aset_lokasifisik,
								   "merk_type"           =>(string)$result[$j]->merk_type,
								   "i_jumlah_barang"           =>(string)$result[$j]->i_jumlah_barang,
								   "rph_aset"           =>(string)$result[$j]->rph_aset,
								   "satuan"           =>(string)$result[$j]->satuan,
								   "ur_sskel"           =>(string)$result[$j]->ur_sskel);
								  
								  
			  			       
		
		 }
        }	
        		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
public function getPenyInvListbyKode($kode,$noas) {
        $status='T';
		$params =  array();
		$params[] = $kode;
		$params[] = $noas;
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT c_barang,d_aset_thnanggar,to_char(no_aset,'09999') as no_aset,i_ruang,e_keterangan,
		 merk_type,ur_sskel,d_barang_peroleh,i_jumlah_barang
		 FROM aset.tm_dir_item a,aset.tm_dir_0 b,
		      aset.tm_masterhm c,aset.tm_sskel d
		 where a.i_barang_serah = b.i_barang_serah	and c_barang=? and no_aset=? and  a.i_aset =  c.no_aset  
		   and a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
								     and substr(c.kd_brg,2,2) = d.kd_bid 
									 and substr(c.kd_brg,4,2) = d.kd_kel
									 and substr(c.kd_brg,6,2) = d.kd_skel
									 and substr(c.kd_brg,8,3) = d.kd_sskel",$params);
		
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_barang"           =>(string)$result[$j]->c_barang,
								   "d_aset_thnanggar"           =>(string)$result[$j]->d_aset_thnanggar,
								   "no_aset"           =>(string)$result[$j]->no_aset,
								   "i_ruang"           =>(string)$result[$j]->i_ruang,
								   "e_keterangan"           =>(string)$result[$j]->e_keterangan,
								   "merk_type"           =>(string)$result[$j]->merk_type,
								   "ur_sskel"           =>(string)$result[$j]->ur_sskel,
								   "i_jumlah_barang"           =>(string)$result[$j]->i_jumlah_barang,
								   "d_barang_peroleh"           =>(string)$result[$j]->d_barang_peroleh);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 	
 	 
	public function UpdateAstDirTm(array $data) {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $personal_parm = array("i_peg_nipterima"  		 =>trim($data['nipPenerima']),
						       "i_orgb_penerima"  		 =>substr($data['orgPenerima'],0,6), 
							   "i_peg_nippemberi"  		 =>trim($data['nipPemberi']),
							   "i_orgb_pemberi"  	 	 =>substr($data['orgPemberi'],0,6), 
							   "e_keterangan"  	 	     =>$data['ktr'],							   
							   "i_entry"       		     =>$data['nuser'],
						       "d_entry"       		     =>date("Y-m-d"));
						  	    
							   
							   
	     $where[] = "i_barang_serah  =  '".$data['nopeng']."'";
		
	     $db->update('aset.tm_dir_0',$personal_parm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function UpdStsAstDirTm(array $data) {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $personal_parm = array("c_barang_statserah"  	     =>$data['status'],
						         "i_entry"       		     =>"ast",
						         "d_entry"       		     =>date("Y-m-d"));
						  	    
							   
							   
	     $where[] = "i_barang_serah  =  '".$data['nopeng']."'";
		
	     $db->update('aset.tm_dir_0',$personal_parm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
    public function UpdateAstDirItem(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $item_parm = array("i_ruang"  		             =>$data['iruang'],
							 "i_entry"       		            =>$data['nuser'],
							 "i_jumlah_barang"       		            =>$data['i_jumlah_barang'],
						     "d_entry"       		     =>date("Y-m-d"));
						  	    
							   
							   
	      $where[] = "i_barang_serah  =  '".$data['nopeng']."'";
		  $where[] = "c_barang        =  '".$data['cbarang']."'";
		  $where[] = "i_aset          =  '".$data['iaset']."'";
		  $where[] = "d_aset_thnanggar   =  '".$data['dasetthnanggar']."'";
	     $db->update('aset.tm_dir_item',$item_parm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
   public function DelAstDirItem(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	  
	   try {
	     $db->beginTransaction();
	     
	      $where[] = "i_barang_serah  =  '".$data['nopeng']."'";
		  $where[] = "c_barang        =  '".$data['cbarang']."'";
		  $where[] = "i_aset          =  '".$data['iaset']."'";
		
	     $db->delete('aset.tm_dir_item', $where);
		 $db->commit();
		 $hasilAkhir = $db->fetchOne("SELECT count(*)
							   	 FROM aset.tm_dir_item",$where);
		
         //if (!$hasilAkhir)
         // if (!$hasilAkhir)
		    // { 
			 // $db->beginTransaction();
             // $where1[] = "i_barang_serah  =  '".$data['nopeng']."'";
		     // $db->delete('aset.tm_dir_0',$where1);
			 // $db->commit();
            // }
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	/*******************/
	public function getPenyInvLainListbyKode($kode,$noas) {
        $status='T';
		$params =  array();
		$params[] = $kode;
		$params[] = $noas;
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT c_barang,d_anggaran,to_char(no_aset,'09999') as no_aset,n_aset_lokasifisik, 
		 merk_type,ur_sskel,d_perolehan,i_jumlah_barang
		 FROM aset.tm_dil_item a, 
		      aset.tm_masterhm c,aset.tm_sskel d
		 where  c_barang=? and no_aset=? and  a.i_aset =  c.no_aset  
		   and c.thn_ang = a.d_anggaran
		   and a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
								     and substr(c.kd_brg,2,2) = d.kd_bid 
									 and substr(c.kd_brg,4,2) = d.kd_kel
									 and substr(c.kd_brg,6,2) = d.kd_skel
									 and substr(c.kd_brg,8,3) = d.kd_sskel",$params);
		
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_barang"           =>(string)$result[$j]->c_barang,
								   "d_anggaran"           =>(string)$result[$j]->d_anggaran,
								   "no_aset"           =>(string)$result[$j]->no_aset,
								   "n_aset_lokasifisik"           =>(string)$result[$j]->n_aset_lokasifisik,
								   "merk_type"           =>(string)$result[$j]->merk_type,
								   "ur_sskel"           =>(string)$result[$j]->ur_sskel,
								   "i_jumlah_barang"           =>(string)$result[$j]->i_jumlah_barang,
								   "d_perolehan"           =>(string)$result[$j]->d_perolehan);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getPenyInvLainList($nopeng) {
       $status='T';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
	     $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT c_barang,d_anggaran,to_char(no_aset,'09999') as no_aset,n_aset_lokasifisik, 
		 merk_type,ur_sskel
		 FROM aset.tm_dil_item a ,
		      aset.tm_masterhm c,aset.tm_sskel d
		 where  a.i_aset =  c.no_aset  and a.i_barang_serah = ?
		   and c.thn_ang = a.d_anggaran
		   and  a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
								     and substr(c.kd_brg,2,2) = d.kd_bid 
									 and substr(c.kd_brg,4,2) = d.kd_kel
									 and substr(c.kd_brg,6,2) = d.kd_skel
									 and substr(c.kd_brg,8,3) = d.kd_sskel",$nopeng);
		
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_barang"           =>(string)$result[$j]->c_barang,
								   "d_anggaran"           =>(string)$result[$j]->d_anggaran,
								   "no_aset"           =>(string)$result[$j]->no_aset,
								   "n_aset_lokasifisik"           =>(string)$result[$j]->n_aset_lokasifisik,
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
	
    public function UpdateAstDilTm(array $data) {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $personal_parm = array("i_peg_nipterima"  		 =>trim($data['nipPenerima']),
						       "i_orgb_penerima"  		 =>substr($data['orgPenerima'],0,6),
							   "i_peg_nippemberi"  		 =>trim($data['nipPemberi']),
							   "i_orgb_pemberi"  	 	 =>substr($data['orgPemberi'],0,6),
							   "e_keterangan"  	 	     =>trim($data['ktr']),
							   "i_entry"       		     =>$data['nuser'],
						       "d_entry"       		     =>date("Y-m-d"));
						  	    
							   
							   
	     $where[] = "i_barang_serah  =  '".$data['nopeng']."'";
		
	     $db->update('aset.tm_dil_0',$personal_parm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
    public function insertAstDilTm(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
		 $atk_mast_prm = array("i_barang_serah"         		=>$data['nopeng'],
	                           "d_barang_serah"    	        	=>$data['dbarangserah'],
						       "i_peg_nipterima"                =>$data['nipPenerima'],
							   "i_peg_nippemberi"               =>$data['nipPemberi'],
							   "i_barang_baserah"  	            =>' ',
							   "i_orgb_pemberi"                 =>$data['orgPemberi'],
							   "i_orgb_penerima"                =>$data['orgPenerima'],
						       "c_barang_statserah"  	        =>$data['status'],
							   "e_keterangan"					=>$data['ket'],
							   "i_surat"					    =>$data['nosurat'],
						       "i_entry"       		            =>$data['nuser'],
						       "d_entry"       		            =>date("Y-m-d"));
	    
	     $db->insert('aset.tm_dil_0',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function insertAstDilItemTm(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
		 $atk_mast_prm = array("i_barang_serah"         		=>$data['nopeng'],
	                           "d_anggaran"    	                =>$data['danggaran'],
						       "c_barang"                       =>$data['cbarang'],
							   "i_aset"              		    =>$data['iaset'],
							   "d_perolehan"  	               =>$data['dperolehan'], 
							   "n_aset_lokasifisik"              =>$data['lokasi'],
							   "i_jumlah_barang"              =>$data['i_jumlah_barang'],
							   "i_entry"       		            =>$data['nuser'],
						       "d_entry"       		        =>date("Y-m-d"));
	    
	     $db->insert('aset.tm_dil_item',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	} 
     
		 
	public function DeleteAstDilTm($nopeng) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $where[] = "i_barang_serah = '". $nopeng ."'";
		 $db->delete('aset.tm_dil_0', $where);
		 $db->delete('aset.tm_dil_item', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function UpdStsAstDilTm(array $data) {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $personal_parm = array("c_barang_statserah"  	     =>$data['status'],
						           "i_entry"       		     =>$data['nuser'],
						         "d_entry"       		     =>date("Y-m-d"));
						  	    
							   
							   
	     $where[] = "i_barang_serah  =  '".$data['nopeng']."'";
		
	     $db->update('aset.tm_dil_0',$personal_parm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
    public function UpdateAstDilItem(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $item_parm = array("n_aset_lokasifisik"  	    =>$data['lokasi'],
							 "i_entry"       	        =>$data['nuser'],
							 "i_jumlah_barang"       	 =>$data['i_jumlah_barang'],
						     "d_entry"       		     =>date("Y-m-d"));
						  	    
							   
							   
	      $where[] = "i_barang_serah  =  '".$data['nopeng']."'";
		  $where[] = "c_barang        =  '".$data['cbarang']."'";
		  $where[] = "i_aset          =  '".$data['iaset']."'";
		  $where[] = "d_anggaran      =  '".$data['danggaran']."'";
	     $db->update('aset.tm_dil_item',$item_parm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
   public function DelAstDilItem(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
	     $db->beginTransaction();
	     
	      $where[] = "i_barang_serah  =  '".$data['nopeng']."'";
		  $where[] = "c_barang        =  '".$data['cbarang']."'";
		  $where[] = "i_aset          =  '".$data['iaset']."'";
		
	     $db->delete('aset.tm_dil_item', $where);
		 $db->commit();
		 $hasilAkhir = $db->fetchOne("SELECT count(*)
							   	 FROM aset.tm_dil_item",$where);
		
         // if (!$hasilAkhir)
		    // { 
			 // $db->beginTransaction();
             // $where1[] = "i_barang_serah  =  '".$data['nopeng']."'";
		     // $db->delete('e_ast_dil_0_tm',$where1);
			 // $db->commit();
            // }
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
 
  /*    service untuk inventais barang                        */
  public function getPenyerahanKIBList($pageNumber,$itemPerPage,$unitkr) {
       $status='T';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	 FROM aset.tm_kib_0 a,sdm.tr_unitkerja b,sdm.tm_pegawai c
							     where substr(a.i_barang_serah,1,6) = ?
								 and b.c_satker = substr(a.i_barang_serah,1,6)
								 and a.i_peg_nippemberi = c.i_peg_nip
								 and a.i_peg_nipterima = c.i_peg_nip
								 and a.i_orgb_penerima = c.c_lokasi_unitkerja
								 and a.i_orgb_pemberi = c.c_lokasi_unitkerja
								 and b.c_lokasi_unitkerja = c.c_lokasi_unitkerja
								 and b.c_eselon_i = c.c_eselon_i
								 and b.c_eselon_ii = c.c_eselon_ii
								 and b.c_eselon_iii = c.c_eselon_iii								 
								 and b.c_eselon_iv = c.c_eselon_iv								 
								 and b.c_eselon_v = c.c_eselon_v								 
							     and a.c_barang_statserah = ?
							     and a.i_barang_serah like '%AYN%'
",$where);           
		 }
		 else
		 {			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		     $result = $db->fetchAll("SELECT a.i_barang_serah,a.d_barang_serah,a.i_peg_nipterima,a.i_orgb_penerima,
		                         a.i_peg_nippemberi,a.i_orgb_pemberi,a.c_aset_kib,a.e_keterangan,b.n_unitkerja as namaunitpemberi,
                                 b.n_unitkerja as namaunitpenerima,c.n_peg as namapegawaipemberi,c.n_peg as namapegawaipenerima,a.i_surat								 
								 FROM aset.tm_kib_0 a,sdm.tr_unitkerja b,sdm.tm_pegawai c
							     where substr(a.i_barang_serah,1,6) = ?
								 and b.c_satker = substr(a.i_barang_serah,1,6)
								 and a.i_peg_nippemberi = c.i_peg_nip
								 and a.i_peg_nipterima = c.i_peg_nip
								 and a.i_orgb_penerima = c.c_lokasi_unitkerja
								 and a.i_orgb_pemberi = c.c_lokasi_unitkerja
								 and b.c_lokasi_unitkerja = c.c_lokasi_unitkerja
								 and b.c_eselon_i = c.c_eselon_i
								 and b.c_eselon_ii = c.c_eselon_ii
								 and b.c_eselon_iii = c.c_eselon_iii								 
								 and b.c_eselon_iv = c.c_eselon_iv								 
								 and b.c_eselon_v = c.c_eselon_v								 
							     and a.c_barang_statserah = ?
							     and a.i_barang_serah like '%AYN%'
								 limit $xLimit offset $xOffset",$where); 



		 
         $jmlResult = count($result);
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		  if($result[$j]->c_aset_kib=='TNH'){$desk="Tanah";}
		  if($result[$j]->c_aset_kib=='BDG'){$desk="Bangunan";}
		  if($result[$j]->c_aset_kib=='AKT'){$desk="Kendaraan";}
           $hasilAkhir[$j] = array("i_barang_serah"             =>(string)$result[$j]->i_barang_serah,
								   "d_barang_serah"             =>(string)$result[$j]->d_barang_serah,
								   "i_peg_nipterima"            =>(string)$result[$j]->i_peg_nipterima,
								   "namapegawaipenerima"            =>(string)$result[$j]->namapegawaipenerima,
								   "namaunitpenerima"            =>(string)$result[$j]->namaunitpenerima,
								   "i_orgb_penerima"            =>(string)$result[$j]->i_orgb_penerima,
								   "i_peg_nippemberi"           =>(string)$result[$j]->i_peg_nippemberi,
								   "namapegawaipemberi"           =>(string)$result[$j]->namapegawaipemberi,
								   "namaunitpemberi"           =>(string)$result[$j]->namaunitpemberi,
								   "i_orgb_pemberi"             =>(string)$result[$j]->i_orgb_pemberi,
								   "i_surat"             =>(string)$result[$j]->i_surat,
								   "desk"                       =>$desk,
								   "c_aset_kib"                 =>(string)$result[$j]->c_aset_kib,
								   "e_keterangan"               =>(string)$result[$j]->e_keterangan);
								  
								  
							       
		
		 }
        }
      }		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getPenyerahanKIBListM($pageNumber,$itemPerPage,$unitkr) {
       $status='Y';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	 FROM aset.tm_kib_0 a,sdm.tm_pegawai b,sdm.tm_pegawai c,sdm.tr_unitkerja d,sdm.tr_unitkerja e 
							     where a.c_barang_statserah = '$status'
							     and a.i_barang_serah like '%AYN%'
								 and a.i_peg_nipterima = b.i_peg_nip
								 and a.i_peg_nippemberi = c.i_peg_nip
								 and a.i_orgb_penerima = d.c_lokasi_unitkerja
								 and a.i_orgb_pemberi = e.c_lokasi_unitkerja
								 and b.c_lokasi_unitkerja=d.c_lokasi_unitkerja
								 and b.c_eselon_i=d.c_eselon_i
								 and b.c_eselon_ii=d.c_eselon_ii
								 and b.c_eselon_iii=d.c_eselon_iii
								 and b.c_eselon_iv=d.c_eselon_iv
								 and b.c_eselon_v=d.c_eselon_v
								 and d.c_satker='$unitkr'
								 and c.c_lokasi_unitkerja=e.c_lokasi_unitkerja
								 and c.c_eselon_i=e.c_eselon_i
								 and c.c_eselon_ii=e.c_eselon_ii
								 and c.c_eselon_iii=e.c_eselon_iii
								 and c.c_eselon_iv=e.c_eselon_iv
								 and c.c_eselon_v=e.c_eselon_v	
                                 and e.c_satker='$unitkr'");           
		 }
		 else
		 {			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		     $result = $db->fetchAll("SELECT a.i_barang_serah,a.d_barang_serah,a.i_peg_nipterima,a.i_orgb_penerima,
		                         a.i_peg_nippemberi,a.i_orgb_pemberi,a.c_aset_kib,a.e_keterangan,b.n_peg as namapegpenerima,c.n_peg as namapegpemberi,
                                 d.n_unitkerja as namaunitpenerima,e.n_unitkerja as namaunitpemberi,a.i_surat								 
								 FROM aset.tm_kib_0 a,sdm.tm_pegawai b,sdm.tm_pegawai c,sdm.tr_unitkerja d,sdm.tr_unitkerja e 
							     where a.c_barang_statserah = '$status'
							     and a.i_barang_serah like '%AYN%'
								 and a.i_peg_nipterima = b.i_peg_nip
								 and a.i_peg_nippemberi = c.i_peg_nip
								 and a.i_orgb_penerima = d.c_lokasi_unitkerja
								 and a.i_orgb_pemberi = e.c_lokasi_unitkerja
								 and b.c_lokasi_unitkerja=d.c_lokasi_unitkerja
								 and b.c_eselon_i=d.c_eselon_i
								 and b.c_eselon_ii=d.c_eselon_ii
								 and b.c_eselon_iii=d.c_eselon_iii
								 and b.c_eselon_iv=d.c_eselon_iv
								 and b.c_eselon_v=d.c_eselon_v
								 and d.c_satker='$unitkr'
								 and c.c_lokasi_unitkerja=e.c_lokasi_unitkerja
								 and c.c_eselon_i=e.c_eselon_i
								 and c.c_eselon_ii=e.c_eselon_ii
								 and c.c_eselon_iii=e.c_eselon_iii
								 and c.c_eselon_iv=e.c_eselon_iv
								 and c.c_eselon_v=e.c_eselon_v	
                                 and e.c_satker='$unitkr'								 
								 order by a.i_barang_serah
								 limit $xLimit offset $xOffset"); 



		 
         $jmlResult = count($result);
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_barang_serah"             =>(string)$result[$j]->i_barang_serah,
								   "d_barang_serah"             =>(string)$result[$j]->d_barang_serah,
								   "i_peg_nipterima"            =>(string)$result[$j]->i_peg_nipterima,
								   "i_orgb_penerima"            =>(string)$result[$j]->i_orgb_penerima,
								   "namapegpenerima"            =>(string)$result[$j]->namapegpenerima,
								   "namaunitpenerima"            =>(string)$result[$j]->namaunitpenerima,
								   "i_peg_nippemberi"           =>(string)$result[$j]->i_peg_nippemberi,
								   "i_orgb_pemberi"             =>(string)$result[$j]->i_orgb_pemberi,
								   "namapegpemberi"             =>(string)$result[$j]->namapegpemberi,
								   "namaunitpemberi"             =>(string)$result[$j]->namaunitpemberi,
								   "i_surat"             =>(string)$result[$j]->i_surat,
								   "c_aset_kib"                 =>(string)$result[$j]->c_aset_kib,
								   "e_keterangan"               =>(string)$result[$j]->e_keterangan);
								  
								  
							       
		
		 }
        }
      }		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function DeleteAstKIBTm($nopeng) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $where[] = "i_barang_serah = '". $nopeng ."'";
		 $db->delete('aset.tm_kib_0', $where);
		 $db->delete('aset.tm_kib_item', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function UpdStsAstKIBTm(array $data) {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $personal_parm = array("c_barang_statserah"  	     =>$data['status'],
						         "i_entry"       		     =>$data['nuser'],
						         "d_entry"       		     =>date("Y-m-d"));
						  	    
							   
							   
	     $where[] = "i_barang_serah  =  '".$data['nopeng']."'";
		
	     $db->update('aset.tm_kib_0',$personal_parm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getPenyInvKIBListbyKdTnh($kode,$noas) {
        $status='T';
		$params =  array();
		$params[] = $kode;
		$params[] = $noas;
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT c_barang,d_anggaran,to_char(i_aset,'09999') as no_aset,i_asetkib, 
		 ur_sskel,d_perolehan
		 FROM aset.tm_kib_item a, 
		      aset.tm_ktnh c,aset.tm_sskel d
		   where  c_barang=? and no_aset=? and  a.i_aset =  c.no_aset  
		   and a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
								     and substr(c.kd_brg,2,2) = d.kd_bid 
									 and substr(c.kd_brg,4,2) = d.kd_kel
									 and substr(c.kd_brg,6,2) = d.kd_skel
									 and substr(c.kd_brg,8,3) = d.kd_sskel",$params);
		
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_barang"           =>(string)$result[$j]->c_barang,
								   "d_anggaran"           =>(string)$result[$j]->d_anggaran,
								   "no_aset"           =>(string)$result[$j]->no_aset,
								   "n_aset_lokasifisik"           =>(string)$result[$j]->n_aset_lokasifisik,
								   "ur_sskel"           =>(string)$result[$j]->ur_sskel,
								   "d_perolehan"           =>(string)$result[$j]->d_perolehan);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getPenyInvKIBListbyKdBDG($kode,$noas) {
        $status='T';
		$params =  array();
		$params[] = $kode;
		$params[] = $noas;
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT c_barang,d_anggaran,to_char(i_aset,'09999') as no_aset,i_asetkib, 
		 merk_type,ur_sskel,d_perolehan
		 FROM aset.tm_kib_item a, 
		      aset.tm_kdbg c,aset.tm_sskel d
		 where  c_barang=? and no_aset=? and  a.i_aset =  c.no_aset  
		   and a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
								     and substr(c.kd_brg,2,2) = d.kd_bid 
									 and substr(c.kd_brg,4,2) = d.kd_kel
									 and substr(c.kd_brg,6,2) = d.kd_skel
									 and substr(c.kd_brg,8,3) = d.kd_sskel",$params);
		
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_barang"           =>(string)$result[$j]->c_barang,
								   "d_anggaran"           =>(string)$result[$j]->d_anggaran,
								   "no_aset"           =>(string)$result[$j]->no_aset,
								   "n_aset_lokasifisik"           =>(string)$result[$j]->n_aset_lokasifisik,
								   "merk_type"           =>(string)$result[$j]->merk_type,
								   "ur_sskel"           =>(string)$result[$j]->ur_sskel,
								   "d_perolehan"           =>(string)$result[$j]->d_perolehan);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	 
	public function getPenyInvKIBListbyKdAKT($kode,$noas) {
        $status='T';
		$params =  array();
		$params[] = $kode;
		$params[] = $noas;
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT c_barang,d_anggaran,to_char(i_aset,'09999') as no_aset,i_asetkib, 
		 merk_type,ur_sskel,d_perolehan
		 FROM aset.tm_kib_item a, 
		      aset.tm_kangk c,aset.tm_sskel d
		 where  c_barang=? and no_aset=? and  a.i_aset =  c.no_aset  
		   and a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
								     and substr(c.kd_brg,2,2) = d.kd_bid 
									 and substr(c.kd_brg,4,2) = d.kd_kel
									 and substr(c.kd_brg,6,2) = d.kd_skel
									 and substr(c.kd_brg,8,3) = d.kd_sskel",$params);
		
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_barang"           =>(string)$result[$j]->c_barang,
								   "d_anggaran"           =>(string)$result[$j]->d_anggaran,
								   "no_aset"           =>(string)$result[$j]->no_aset,
								   "n_aset_lokasifisik"           =>(string)$result[$j]->n_aset_lokasifisik,
								   "merk_type"           =>(string)$result[$j]->merk_type,
								   "ur_sskel"           =>(string)$result[$j]->ur_sskel,
								   "d_perolehan"           =>(string)$result[$j]->d_perolehan);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	 } 
	 public function UpdateAstKIBTm(array $data) {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $personal_parm = array("i_peg_nipterima"  		 =>trim($data['nipPenerima']),
						       "i_orgb_penerima"  		 =>substr($data['orgPenerima'],0,6),
							   "i_peg_nippemberi"  		 =>trim($data['nipPemberi']),
							   "i_orgb_pemberi"  	 	 =>substr($data['orgPemberi'],0,6),
							   "e_keterangan"  	 	     =>trim($data['ktr']),
							   "i_entry"                 =>$data['nuser'],
						       "d_entry"       		     =>date("Y-m-d"));
						  	    
							   
							   
	     $where[] = "i_barang_serah  =  '".$data['nopeng']."'";
		
	     $db->update('aset.tm_kib_0',$personal_parm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getPenyInvKIBList($nopeng,$jenisbrg) {
       $status='T';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	  
	   try {
	     $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 // echo "nopengajuan : ".$nopeng;
		 // echo "jenis barang : ".$jenisbrg;
		 if($jenisbrg=="TNH"){
		   $sql="SELECT distinct c_barang,d_anggaran,to_char(no_aset,'09999') as no_aset,
				ur_sskel,i_jumlah_barang,d.satuan,c.rph_aset
				FROM aset.tm_kib_item a ,
				aset.tm_ktnh c,aset.tm_sskel d
				where  a.i_aset =  c.no_aset  and a.i_barang_serah = '$nopeng'
				and  a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
				and substr(c.kd_brg,2,2) = d.kd_bid 
				and substr(c.kd_brg,4,2) = d.kd_kel
				and substr(c.kd_brg,6,2) = d.kd_skel
				and substr(c.kd_brg,8,3) = d.kd_sskel";
		 }
		  if($jenisbrg=="BDG"){
		   $sql="SELECT distinct c_barang,d_anggaran,to_char(no_aset,'09999') as no_aset,
				ur_sskel,i_jumlah_barang,c.rph_aset,d.satuan
				FROM aset.tm_kib_item a ,
				aset.tm_kbdg c,aset.tm_sskel d
				where  a.i_aset =  c.no_aset  and a.i_barang_serah = '$nopeng'
				and  a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
				and substr(c.kd_brg,2,2) = d.kd_bid 
				and substr(c.kd_brg,4,2) = d.kd_kel
				and substr(c.kd_brg,6,2) = d.kd_skel
				and substr(c.kd_brg,8,3) = d.kd_sskel";
		 }
		  if($jenisbrg=="AKT"){
		   $sql="SELECT distinct c_barang,d_anggaran,to_char(no_aset,'09999') as no_aset,
				ur_sskel,i_jumlah_barang,c.rph_aset,d.satuan
				FROM aset.tm_kib_item a ,
				aset.tm_kangk c,aset.tm_sskel d
				where  a.i_aset =  c.no_aset  and a.i_barang_serah = '$nopeng'
				and  a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
				and substr(c.kd_brg,2,2) = d.kd_bid 
				and substr(c.kd_brg,4,2) = d.kd_kel
				and substr(c.kd_brg,6,2) = d.kd_skel
				and substr(c.kd_brg,8,3) = d.kd_sskel";
		 }
		 //echo $sql;
		 $result = $db->fetchAll($sql);
		 // $result = $db->fetchAll("SELECT distinct c_barang,d_anggaran,to_char(no_aset,'09999') as no_aset,
		 // ur_sskel
		 // FROM aset.tm_kib_item a ,
		      // $namatabel,aset.tm_sskel d
		   // where  a.i_aset =  c.no_aset  and a.i_barang_serah = ?
		   // and  a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
								     // and substr(c.kd_brg,2,2) = d.kd_bid 
									 // and substr(c.kd_brg,4,2) = d.kd_kel
									 // and substr(c.kd_brg,6,2) = d.kd_skel
									 // and substr(c.kd_brg,8,3) = d.kd_sskel",$nopeng);
		
         $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_barang"           =>(string)$result[$j]->c_barang,
								   "d_anggaran"           =>(string)$result[$j]->d_anggaran,
								   "no_aset"           =>(string)$result[$j]->no_aset,
								   "i_ruang"           =>(string)$result[$j]->i_ruang,
								   "i_jumlah_barang"           =>(string)$result[$j]->i_jumlah_barang,
								   "rph_aset"           =>(string)$result[$j]->rph_aset,
								   "satuan"           =>(string)$result[$j]->satuan,
								   //"merk_type"           =>(string)$result[$j]->merk.(string)$result[$j]->type,
								   "ur_sskel"           =>(string)$result[$j]->ur_sskel);
								  
								  
			  			       
		
		 }
        }	
        		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getPenyInvKIBList2($nopeng) {
       $status='T';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	    //echo "tes ".$nopeng;
	   try {
	     $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT c_barang,d_anggaran,to_char(no_aset,'09999') as no_aset,
		 ur_sskel,i_jumlah_barang,c.rph_aset,d.satuan
		 FROM aset.tm_kib_item a ,
		      aset.tm_ktnh c,aset.tm_sskel d
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
								   "d_anggaran"           =>(string)$result[$j]->d_anggaran,
								   "no_aset"           =>(string)$result[$j]->no_aset,
								   "i_ruang"           =>(string)$result[$j]->i_ruang,
								   "merk_type"           =>(string)$result[$j]->merk_type,
								   "i_jumlah_barang"           =>(string)$result[$j]->i_jumlah_barang,
								   "rph_aset"           =>(string)$result[$j]->rph_aset,
								   "satuan"           =>(string)$result[$j]->satuan,
								   "ur_sskel"           =>(string)$result[$j]->ur_sskel);
								  
								  
			  			       
		
		 }
        }	
        		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getPenyInvKIBList3($nopeng) {
       $status='T';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
	     $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT c_barang,d_anggaran,to_char(no_aset,'09999') as no_aset,
								ur_sskel,i_jumlah_barang,c.rph_aset,d.satuan
								FROM aset.tm_kib_item a ,
								aset.tm_kbdg c,aset.tm_sskel d
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
								   "d_anggaran"           =>(string)$result[$j]->d_anggaran,
								   "no_aset"           =>(string)$result[$j]->no_aset,
								   "i_ruang"           =>(string)$result[$j]->i_ruang,								    
								   "i_jumlah_barang"           =>(string)$result[$j]->i_jumlah_barang,								    
								   "rph_aset"           =>(string)$result[$j]->rph_aset,								    
								   "satuan"           =>(string)$result[$j]->satuan,								    
								   "ur_sskel"           =>(string)$result[$j]->ur_sskel);
		 }
        }
        	
        		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
    public function insertAstKIBTm(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	    
	   try {
	     $db->beginTransaction();
		 $atk_mast_prm = array("i_barang_serah"         		=>$data['nopeng'],
	                           "d_barang_serah"    	        	=>$data['dbarangserah'],
						       "i_peg_nipterima"                =>$data['nipPenerima'],
							   "i_peg_nippemberi"               =>$data['nipPemberi'],
							   "i_barang_baserah"  	            =>' ',
							   "i_orgb_pemberi"                 =>$data['orgPemberi'],
							   "i_orgb_penerima"                =>$data['orgPenerima'],
						       "c_barang_statserah"  	        =>$data['status'], 
							   "c_aset_kib"  	                =>$data['casetkib'], 
							   "i_surat"  	                =>$data['nosurat'], 
							   "e_keterangan"					=>$data['ket'],
							   "i_entry"       		            =>$data['nuser'],
						       "d_entry"       		        =>date("Y-m-d"));
	    
	     $db->insert('aset.tm_kib_0',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
  	
	public function insertAstKIBItemTm(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
		 $atk_mast_prm = array("i_barang_serah"         		=>$data['nopeng'],
	                           "d_anggaran"    	                =>$data['danggaran'],
						       "c_barang"                       =>$data['cbarang'],
							   "i_aset"              		    =>$data['iaset'],
							   "d_perolehan"  	                =>$data['dperolehan'], 
							   "i_aset_kib"                     =>$data['asetkib'],
							   "i_jumlah_barang"                =>$data['i_jumlah_barang'],
							   "i_entry"       		            =>$data['nuser'],
						       "d_entry"       		           =>date("Y-m-d"));
	    
	     $db->insert('aset.tm_kib_item',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	} 
    

	 public function UpdateAstKIBItem(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $item_parm = array("i_aset_kib"  	    =>$data['asetkib'],
							 "i_entry"          =>$data['nuser'],
							 "i_jumlah_barang"          =>$data['i_jumlah_barang'],
						     "d_entry"          =>date("Y-m-d"));
						  	    
							   
							   
	      $where[] = "i_barang_serah  =  '".$data['nopeng']."'";
		  $where[] = "c_barang        =  '".$data['cbarang']."'";
		  $where[] = "i_aset          =  '".$data['iaset']."'";
		  $where[] = "d_anggaran      =  '".$data['danggaran']."'";
	     $db->update('aset.tm_kib_item',$item_parm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function DelAstKIBItem(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     
	      $where[] = "i_barang_serah  =  '".$data['nopeng']."'";
		  $where[] = "c_barang        =  '".$data['cbarang']."'";
		  $where[] = "i_aset          =  '".$data['iaset']."'";
		
	     $db->delete('aset.tm_kib_item', $where);
		 $db->commit();
		 $hasilAkhir = $db->fetchOne("SELECT count(*)
							   	 FROM aset.tm_kib_item ",$where);
		  
         // if (!$hasilAkhir)
            // {$db->beginTransaction();
			 // $where1[] = "i_barang_serah  =  '".$data['nopeng']."'";
		     // $db->delete('aset.tm_kib_0',$where1);
			 // $db->commit();
            // }  			
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
 
   public function getPenyKIBListbyKode($kode,$noas) {
       //echo "Kode : ".$kode;
       //echo "No as : ".$noas;
        $status='T';
		$params =  array();
		$params[] = $kode;
		$params[] = $noas;
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT c_barang,d_anggaran,to_char(no_aset,'09999') as no_aset,
		 merk_type,ur_sskel,d_perolehan,i_jumlah_barang
		 FROM aset.tm_kib_item a, 
		      aset.tm_masterhm c,aset.tm_sskel d
		 where  c_barang=? and no_aset=? and  a.i_aset =  c.no_aset  
		   and a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
								     and substr(c.kd_brg,2,2) = d.kd_bid 
									 and substr(c.kd_brg,4,2) = d.kd_kel
									 and substr(c.kd_brg,6,2) = d.kd_skel
									 and substr(c.kd_brg,8,3) = d.kd_sskel",$params);
		
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_barang"           =>(string)$result[$j]->c_barang,
								   "d_anggaran"           =>(string)$result[$j]->d_anggaran,
								   "no_aset"           =>(string)$result[$j]->no_aset,
								   "merk_type"           =>(string)$result[$j]->merk_type,
								   "ur_sskel"           =>(string)$result[$j]->ur_sskel,
								   "i_jumlah_barang"           =>(string)$result[$j]->i_jumlah_barang,
								   "d_perolehan"           =>(string)$result[$j]->d_perolehan);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
    public function getCariNamaBrgDIR($pageNumber,$itemPerPage,$tercatat,$namaBarang,$thnang) {
	   $namaBarang = strtoupper($namaBarang);
	   $nbrg = '%'.$namaBarang.'%';
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*) 
										from aset.tm_masterhm a,aset.tm_sskel b
										where
										substr(a.kd_brg,1,1) = b.kd_gol
										and substr(a.kd_brg,2,2) = b.kd_bid 
										and substr(a.kd_brg,4,2) = b.kd_kel
										and substr(a.kd_brg,6,2) = b.kd_skel
										and substr(a.kd_brg,8,3) = b.kd_sskel 
										and  a.tercatat = '1'
										and ((b.kd_gol != '2' and b.kd_bid != '12') 
										or (b.kd_gol != '2' and b.kd_bid = '12') 
										or (b.kd_gol = '2' and b.kd_bid != '12'))
										and a.thn_ang = '$thnang'
										and upper(b.ur_sskel) like ?
										and not exists(select c.c_barang from aset.tm_dir_item  c
										where c.c_barang =a.kd_brg
										and c.i_aset = a.no_aset)",$nbrg);    
        
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("select distinct thn_ang, a.kd_brg,to_char(a.no_aset,'09999') as i_aset,a.tgl_perlh,
			                        a.rph_aset,a.merk_type,b.ur_sskel
									from aset.tm_masterhm a,aset.tm_sskel b
									where
									substr(a.kd_brg,1,1) = b.kd_gol
									and substr(a.kd_brg,2,2) = b.kd_bid 
									and substr(a.kd_brg,4,2) = b.kd_kel
									and substr(a.kd_brg,6,2) = b.kd_skel
									and substr(a.kd_brg,8,3) = b.kd_sskel 
									and  a.tercatat = '1'
									and upper(b.ur_sskel) like ?
									and ((b.kd_gol != '2' and b.kd_bid != '12') 
									or (b.kd_gol != '2' and b.kd_bid = '12') 
									or (b.kd_gol = '2' and b.kd_bid != '12'))
									and a.thn_ang = '$thnang'
									and not exists(select c.c_barang from aset.tm_dir_item  c
									where c.c_barang =a.kd_brg
									and c.i_aset = a.no_aset)												 
									limit $xLimit offset $xOffset",$nbrg); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("d_aset_thnanggar"          =>(string)$result[$j]->thn_ang,
										"c_barang"           		=>(string)$result[$j]->kd_brg,
										"i_aset"           			=>(string)$result[$j]->i_aset,
										"ur_sskel"          		=>(string)$result[$j]->ur_sskel,
										"d_barang_peroleh"          =>(string)$result[$j]->tgl_perlh,
										"merk_type"   				=>(string)$result[$j]->merk_type,
										"rph_aset"   				=>(string)$result[$j]->rph_aset);
									
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
	public function getCariNamaBrgDIL($pageNumber,$itemPerPage,$tercatat,$namaBarang,$thnang) {
	   $namaBarang = strtoupper($namaBarang);
	   $nbrg = '%'.$namaBarang.'%';
	  
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*) 
 						 from  aset.tm_sskel a, aset.tm_masterhm b
						 where substr(b.kd_brg,1,1) = a.kd_gol
						 and substr(b.kd_brg,2,2) = a.kd_bid 
						 and substr(b.kd_brg,4,2) = a.kd_kel
                         and substr(b.kd_brg,6,2) = a.kd_skel
                         and substr(b.kd_brg,8,3) = a.kd_sskel 
					     and  tercatat = '2' and upper(ur_sskel) like ?
						 and b.thn_ang='$thnang'
						 and ((kd_gol != '2' and kd_bid != '12') 
											or (kd_gol != '2' and kd_bid = '12') 
											or (kd_gol = '2' and kd_bid != '12'))
						 and not exists(select c.i_aset from aset.tm_dil_item  c
								  WHERE c.c_barang =b.kd_brg
								  and c.i_aset = b.no_aset)",$nbrg);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			  $result = $db->fetchAll("SELECT thn_ang, b.kd_brg, to_char(b.no_aset,'09999') as  i_aset , ur_sskel, 
										tgl_perlh, merk_type,rph_aset
										FROM  aset.tm_sskel a, aset.tm_masterhm b
										where  substr(b.kd_brg,1,1) = a.kd_gol
											and substr(b.kd_brg,2,2) = a.kd_bid 
											and substr(b.kd_brg,4,2) = a.kd_kel
											and substr(b.kd_brg,6,2) = a.kd_skel
											and substr(b.kd_brg,8,3) = a.kd_sskel 
											and  tercatat = '2'
											and ((kd_gol != '2' and kd_bid != '12') 
											or (kd_gol != '2' and kd_bid = '12') 
											or (kd_gol = '2' and kd_bid != '12'))
											and upper(ur_sskel) like ?
											and b.thn_ang='$thnang'
											and not exists(select c.i_aset from aset.tm_dil_item  c
												where c.c_barang =b.kd_brg
												and c.i_aset = b.no_aset)
												 
										   limit $xLimit offset $xOffset",$nbrg); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("d_aset_thnanggar"          =>(string)$result[$j]->thn_ang,
										"c_barang"           		=>(string)$result[$j]->kd_brg,
										"i_aset"           			=>(string)$result[$j]->i_aset,
										"ur_sskel"          		=>(string)$result[$j]->ur_sskel,
										"d_barang_peroleh"          =>(string)$result[$j]->tgl_perlh,
										"merk_type"   				=>(string)$result[$j]->merk_type,
										"rph_aset"   				=>(string)$result[$j]->rph_aset);
										 
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	public function getCariNamaBrgTNH($pageNumber,$itemPerPage,$namaBarang) {
	   $namaBarang = strtoupper($namaBarang);
	   $nbrg = '%'.$namaBarang.'%';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
		    
			$hasilAkhir = $db->fetchOne("SELECT count(*) FROM aset.tm_ktnh a,aset.tm_sskel b
									    where substr(a.kd_brg,1,1) = b.kd_gol
								           and substr(a.kd_brg,2,2) = b.kd_bid 
									       and substr(a.kd_brg,4,2) = b.kd_kel
									       and substr(a.kd_brg,6,2) = b.kd_skel
									       and substr(a.kd_brg,8,3) = b.kd_sskel 
										   and ur_sskel like ?
										   and not exists(select * from aset.tm_kib_item c
										   where c.c_barang = a.kd_brg 
												 and c.i_aset   = a.no_aset 
												 and c.d_perolehan = a.tgl_prl 
												 )",$nbrg);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT to_char(tgl_prl,'yyyy') as thn_ang,a.kd_brg,tgl_prl,no_kib,
									to_char(no_aset,'09999') as no_aset,ur_sskel
									FROM aset.tm_ktnh a,aset.tm_sskel b
									where  substr(a.kd_brg,1,1) = b.kd_gol
								           and substr(a.kd_brg,2,2) = b.kd_bid 
									       and substr(a.kd_brg,4,2) = b.kd_kel
									       and substr(a.kd_brg,6,2) = b.kd_skel
									       and substr(a.kd_brg,8,3) = b.kd_sskel 
										   and ur_sskel like ?
										   and not exists(select * from aset.tm_kib_item c
										       where c_barang = a.kd_brg
  										            and i_aset   = no_aset 
													and d_perolehan = tgl_prl 
												 )										    
										   limit $xLimit offset $xOffset",$nbrg); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				  $hasilAkhir[$j] = array("d_aset_thnanggar"           =>(string)$result[$j]->thn_ang,
									"c_barang"                   =>(string)$result[$j]->kd_brg,
									"i_aset"                     =>(string)$result[$j]->no_aset,
								    "d_barang_peroleh"           =>(string)$result[$j]->tgl_prl,
									"no_kib"              =>(string)$result[$j]->no_kib,
									"merk_type"           =>(string)$result[$j]->merk_type,
									"ur_sskel"            =>(string)$result[$j]->ur_sskel);

			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
	
	public function getCariNamaBrgBDG($pageNumber,$itemPerPage,$namaBarang) {
	   $namaBarang = strtoupper($namaBarang);
	   $nbrg = '%'.$namaBarang.'%';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) 
			                            FROM aset.tm_kbdg a,aset.tm_sskel b,aset.tm_masterhm c
									    where  substr(a.kd_brg,1,1) = b.kd_gol
								           and substr(a.kd_brg,2,2) = b.kd_bid 
									       and substr(a.kd_brg,4,2) = b.kd_kel
									       and substr(a.kd_brg,6,2) = b.kd_skel
									       and substr(a.kd_brg,8,3) = b.kd_sskel 
										   and a.kd_brg=c.kd_brg
										   and b.kd_brg=c.kd_brg
										   and upper(ur_sskel) like ?
										   and not exists(select * from aset.tm_kib_item c
										   where  
										         c_barang = a.kd_brg and
 												 i_aset   = a.no_aset and
												 d_perolehan = tgl_prl 
												 )
										    
										   ",$nbrg);
			                           // FROM aset.tm_kib_item a, aset.tm_sskel b, aset.tm_kbdg c
										// where  substr(a.c_barang,1,1) = b.kd_gol
									       // and substr(a.c_barang,2,2) = b.kd_bid 
									       // and substr(a.c_barang,4,2) = b.kd_kel
									       // and substr(a.c_barang,6,2) = b.kd_skel
									       // and substr(a.c_barang,8,3) = b.kd_sskel 
									       // and a.c_barang =c.kd_brg
									       // and a.i_aset = c.no_aset");
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT to_char(tgl_prl,'yyyy') as thn_ang,a.kd_brg,tgl_prl,no_kib,
									to_char(a.no_aset,'09999') as no_aset,ur_sskel
									FROM aset.tm_kbdg a,aset.tm_sskel b,aset.tm_masterhm c
									where  substr(a.kd_brg,1,1) = b.kd_gol
								           and substr(a.kd_brg,2,2) = b.kd_bid 
									       and substr(a.kd_brg,4,2) = b.kd_kel
									       and substr(a.kd_brg,6,2) = b.kd_skel
									       and substr(a.kd_brg,8,3) = b.kd_sskel 
										   and upper(ur_sskel) like ?
										   and a.kd_brg=c.kd_brg
										   and b.kd_brg=c.kd_brg
										   and not exists(select * from aset.tm_kib_item c
										   where  
										         c_barang = a.kd_brg and
 												 i_aset   = a.no_aset and
												 d_perolehan = tgl_prl 
												 )
										    
										   limit $xLimit offset $xOffset",$nbrg); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				  $hasilAkhir[$j] = array("d_aset_thnanggar"           =>(string)$result[$j]->thn_ang,
									"c_barang"                   =>(string)$result[$j]->kd_brg,
									"i_aset"                     =>(string)$result[$j]->no_aset,
								    "d_barang_peroleh"           =>(string)$result[$j]->tgl_prl,
									"no_kib"              =>(string)$result[$j]->no_kib,
									"merk_type"           =>(string)$result[$j]->merk_type,
									"ur_sskel"            =>(string)$result[$j]->ur_sskel);

			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
	public function getCariNamaBrgAKT($pageNumber,$itemPerPage,$namaBarang) {
	   $namaBarang = strtoupper($namaBarang);
	   $nbrg = '%'.$namaBarang.'%';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM aset.tm_kangk a, aset.tm_sskel b 
										where  substr(a.kd_brg,1,1) = b.kd_gol
								           and substr(a.kd_brg,2,2) = b.kd_bid 
									       and substr(a.kd_brg,4,2) = b.kd_kel
									       and substr(a.kd_brg,6,2) = b.kd_skel
									       and substr(a.kd_brg,8,3) = b.kd_sskel 
										   and ur_sskel like ?
										   and not exists(select * from aset.tm_kib_item c
										   where 
										         c.c_barang = a.kd_brg and
 												 c.i_aset   = a.no_aset and
												 c.d_perolehan = a.tgl_prl)",$nbrg);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT to_char(tgl_prl,'yyyy') as thn_ang,a.kd_brg,tgl_prl,no_kib,
									to_char(no_aset,'09999') as no_aset,merk,tipe,ur_sskel
									FROM aset.tm_kangk a,aset.tm_sskel b
									where  substr(a.kd_brg,1,1) = b.kd_gol
								           and substr(a.kd_brg,2,2) = b.kd_bid 
									       and substr(a.kd_brg,4,2) = b.kd_kel
									       and substr(a.kd_brg,6,2) = b.kd_skel
									       and substr(a.kd_brg,8,3) = b.kd_sskel 
										   and ur_sskel like ?
										   and not exists(select * from aset.tm_kib_item c
										   where  
										         c.c_barang = a.kd_brg and
 												 c.i_aset   = a.no_aset and
												 c.d_perolehan = a.tgl_prl 
												 )
										   
										   limit $xLimit offset $xOffset",$nbrg); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				  $hasilAkhir[$j] = array("d_aset_thnanggar"           =>(string)$result[$j]->thn_ang,
									"c_barang"                   =>(string)$result[$j]->kd_brg,
									"i_aset"                     =>(string)$result[$j]->no_aset,
								    "d_barang_peroleh"           =>(string)$result[$j]-> tgl_prl,
									"no_kib"              =>(string)$result[$j]->no_kib,
									"merk_type"           =>(string)$result[$j]->merk.(string)$result[$j]->tipe,
							
									"ur_sskel"            =>(string)$result[$j]->ur_sskel);

			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
    public function getNamaorg($org) {
	    $registry = Zend_Registry::getInstance();
	    $db = $registry->get('db');
	    try { 
	     $db->setFetchMode(Zend_Db::FETCH_OBJ);
         // $result = $db->fetchAll("SELECT n_orgb from sdm.tm_organisasi_0
		                          // where i_orgb = ?",$org);
			$result = $db->fetchAll("SELECT n_organisasi from sdm.tm_organisasi
		                           where i_peg_nip = ?",$org);
		 $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array(	"n_orgb"           	 =>(string)$result[$j]->n_orgb);
												       
		
		 }
        }
        	
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }						  
	}
    public function getListPegawaibyNip($nip) {   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     /* $where[] = "i_peg_nip = '".trim($data['nip'])."'";
		 $where[] = "i_peg_nip = '".trim($data['nip'])."'"; */
		 $where[] = $nip;
		 $where[] = $nip;
		 
	     $db->setFetchMode(Zend_Db::FETCH_OBJ);
		 $sql="select a.n_peg,a.c_lokasi_unitkerja,b.n_jabatan,c.n_unitkerja
		       from sdm.tm_pegawai a,sdm.tr_jabatan b,sdm.tr_unitkerja c
			   where 
			   a.c_jabatan=b.c_jabatan
			   and a.c_lokasi_unitkerja=c.c_lokasi_unitkerja
			   and a.c_eselon_i=c.c_eselon_i
			   and a.c_eselon_ii=c.c_eselon_ii
			   and a.c_eselon_iii=c.c_eselon_iii
			   and a.c_eselon_iv=c.c_eselon_iv
			   and a.c_eselon_v=c.c_eselon_v
			   and a.i_peg_nip='".$nip."'";
		  $result=$db->fetchAll($sql);
		 // $result = $db->fetchAll("select a.n_peg,a.c_lokasi_unitkerja,b.n_jabatan
		                          // from sdm.tm_pegawai a, sdm.tr_jabatan b
								  // where
								  // a.c_jabatan=b.c_jabatan
								  // and a.i_peg_nip=?
								  // union
								  // select n_peg, c_lokasi_unitkerja,NULL
								  // from sdm.tm_pegawai a
								  // where
								  // not EXISTS(select * from  sdm.tr_jabatan B
									          // where a.c_jabatan = b.c_jabatan)
									// and a.i_peg_nip =  ?",$where);
         /*$result = $db->fetchAll("SELECT a.n_peg,b.n_jabatan_nokode,a.c_unit_kerja
									FROM sdm.tm_pegawai A, sdm.tm_jabatan B 
									where 
									a.i_peg_nip = b.i_peg_nip
									and a.c_unit_kerja = b.c_jabatan
									and a.i_peg_nip =  ?
									union
									select  n_peg, NULL, a.c_unit_kerja 
									from  sdm.tm_pegawai A 
									where  
									not EXISTS(select * from  sdm.tm_jabatan B
									          where a.i_peg_nip = b.i_peg_nip)
									and a.i_peg_nip =  ?",$where);*/
		 
		 /* $result = $db->fetchAll('SELECT n_peg,n_jabatan,a.c_unit_kerja 
								 FROM e_sdm_pegawai_0_tm a, e_org_0_0_tm b where i_peg_nip=?
								 and (a.i_orgb=b.i_orgb or a.c_unit_kerja = b.i_orgb)',$nip); */
	     $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array(	"n_peg"           	 =>(string)$result[$j]->n_peg,
									"n_jabatan"          =>(string)$result[$j]->n_jabatan,
									"n_unitkerja"          =>(string)$result[$j]->n_unitkerja,
									"i_orgb"            =>(string)$result[$j]->c_lokasi_unitkerja);
						       
		
		 }
        }
        	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
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
	 //$db->beginTransaction();
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
	     //$db->beginTransaction();
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
    public function bulanRomawi($bulan){
	  $bulan = $bulan*1;
	  $bulanRomawi = array('1' =>'I', 'II', 'III', 'IV',  'V', 'VI',  'VII', 'VIII', 'IX', 'X', 'XI', 'XII');
	  //print_r($bulanRomawi);
	  $blnromawi = $bulanRomawi[$bulan];
	  return $blnromawi;
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
		 // echo "cek ktr : ".$cekktr;
		 // echo "cek modul : ".$cekmodul;
		 if($cekktr!=0){
		   if($cekmodul!=0){
				//$result = $db->fetchOne('SELECT aset.gen_nomor(?,?)',$where);
				// print_r($data);
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
		     $nomaxsurat=$db->fetchOne("select to_char($nomormax,'099')"); 
		    }
		 $nomorsuratsimari = $data['unitkr'].$data['modl'].date("Y").trim($nomax); 		 
		 $blnromawi = $this->bulanRomawi(date("m"));
		 $nomorsuratt = trim($nomaxsurat)."/Bua.7/BAST/BMN/".$blnromawi."/".date("Y"); 
		 $nomorsurat = array("nosuratsimari"=>$nomorsuratsimari,"nosurat"=>$nomorsuratt);
	     return $nomorsurat;
	     //return "TESTING";
	     
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return  0;
	   }
 
 
	}

  
    public function getOrgTU($unitkr) {
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);         
		 /////////////////////////////////////////////////////////////
		 // $TU 	= $db->fetchCol("SELECT i_orgb FROM e_org_0_0_tm where i_orgb = ? 
		                        // and i_orgb_tu !=''
		                        // and i_orgb_tu is not null",$unitkr);   
         $TU 	= $db->fetchCol("SELECT c_satker FROM sdm.tr_unitkerja where c_satker = ? 
		                        ",$unitkr); 								
		
	    if (isset($TU[0])) {
			$TU=$TU[0];				
		}
		else 
		{		
			if (substr($unitkr,0,2) == 'DP')
			{
			      $unit = substr($unitkr,0,3);
				  // $TU = $db->fetchCol("SELECT i_orgb FROM e_org_0_0_tm 
	                         		// where i_orgb_tu is not null and i_orgb_tu !=''
	                                // and SUBSTRING(i_orgb_tu,1,3) = ?",$unit);
					 $TU = $db->fetchCol("SELECT c_satker FROM sdm.tr_unitkerja 
	                         		where c_dept is not null and c_dept !=''
	                                and c_dept= ?",$unit);
			} else
			{
			      $unit = substr($unitkr,0,2);
				  // $TU = $db->fetchCol("SELECT i_orgb FROM e_org_0_0_tm 
	                         		// where i_orgb_tu is not null and i_orgb_tu !=''
	                                // and SUBSTRING(i_orgb_tu,1,2) = ?",$unit);
					$TU = $db->fetchCol("SELECT c_satker FROM sdm.tr_unitkerja 
	                         		where c_dept is not null and c_dept !=''
	                                and c_dept= ?",$unit);
			}
			
			if (isset($TU[0])) {
				$TU=$TU[0];				
			}
			else 
			{	$TU="";}	 
		}	
		
		// Ina : 07-05-2008 : Akhir Menentukan Unit TU
		 ////////////////////////////////////////////////////////////
		 //echo ' org TU : '.$TU;
        return $TU;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	} 

    // Ina : 13-05-2008 : Awal
	public function getCariNamaBrgDIRByPeriode($pageNumber,$itemPerPage,$tercatat,$namaBarang, $tglAwal, $tglAkhir) 
	{
	   $namaBarang = strtoupper($namaBarang);
	   $nbrg = '%'.$namaBarang.'%';
	  
	   $where[] = $nbrg;
	   $where[] = $tglAwal;
	   $where[] = $tglAkhir;
	   // echo "Nama barang : ".$namaBarang."<br/>";
	   // echo "Tgl awal : ".$tglAwal."<br/>";
	   // echo "Tgl akhir : ".$tglAkhir."<br/>";
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*) 			                        
									from aset.tm_masterhm a,aset.tm_sskel b
									where
									substr(a.kd_brg,1,1) = b.kd_gol
									and substr(a.kd_brg,2,2) = b.kd_bid 
									and substr(a.kd_brg,4,2) = b.kd_kel
									and substr(a.kd_brg,6,2) = b.kd_skel
									and substr(a.kd_brg,8,3) = b.kd_sskel 
									and  a.tercatat = '1'
									and upper(b.ur_sskel) like ?
									and a.tgl_perlh between ? and ?
									and ((b.kd_gol != '2' and b.kd_bid != '12') 
									or (b.kd_gol != '2' and b.kd_bid = '12') 
									or (b.kd_gol = '2' and b.kd_bid != '12'))									
									and not exists(select c.i_aset from aset.tm_dir_item  c
									where c.c_barang =a.kd_brg
									and c.i_aset = a.no_aset)",$where);  
        
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("select distinct thn_ang, a.kd_brg,to_char(a.no_aset,'09999') as i_aset,a.tgl_perlh,
			                        a.rph_aset,a.merk_type,b.ur_sskel
									from aset.tm_masterhm a,aset.tm_sskel b
									where
									substr(a.kd_brg,1,1) = b.kd_gol
									and substr(a.kd_brg,2,2) = b.kd_bid 
									and substr(a.kd_brg,4,2) = b.kd_kel
									and substr(a.kd_brg,6,2) = b.kd_skel
									and substr(a.kd_brg,8,3) = b.kd_sskel 
									and  a.tercatat = '1'
									and upper(b.ur_sskel) like ?
									and a.tgl_perlh between ? and ?
									and ((b.kd_gol != '2' and b.kd_bid != '12') 
									or (b.kd_gol != '2' and b.kd_bid = '12') 
									or (b.kd_gol = '2' and b.kd_bid != '12'))									
									and not exists(select c.i_aset from aset.tm_dir_item  c
									where c.c_barang =a.kd_brg
									and c.i_aset = a.no_aset)
								    limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("d_aset_thnanggar"          =>(string)$result[$j]->thn_ang,
										"c_barang"           		=>(string)$result[$j]->kd_brg,
										"i_aset"           			=>(string)$result[$j]->i_aset,
										"ur_sskel"          		=>(string)$result[$j]->ur_sskel,
										"d_barang_peroleh"          =>(string)$result[$j]->tgl_perlh,
										"merk_type"   				=>(string)$result[$j]->merk_type,
										"rph_aset"   				=>(string)$result[$j]->rph_aset);
									
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