<?php
class ast_lap_Service {
   
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


	public function getOrg() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchAll("SELECT i_orgb FROM e_org_0_0_tm order by i_orgb ");
	     return $result;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getDaftarKPB($btnCari,$waktu,$unitKerja) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
		 if ($btnCari == 'Cari' && $waktu != '' && $unitKerja == '') {
		 //echo "1"; to_char(no_aset,'09999')
	     $listkpb = $db->fetchAll("Select A.i_inv_ajuanperbaikan, A.d_inv_ajuanperbaikan,A.i_orgb,B.d_anggaran,
                                   B.c_barang,to_char(B.i_aset,'09999') , C.ur_sskel, B.e_keterangan from
								   e_ast_ajuanpbaikinv_0_tm A,  e_ast_ajuanpbaikinv_item_tm B, e_ast_sskel_0_tr C
                                   where A.i_inv_ajuanperbaikan = B.i_inv_ajuanperbaikan
                                   and  substr(B.c_barang,1,1) = C.kd_gol
                                   and substr(B.c_barang,2,2) = C.kd_bid 
                                   and substr(B.c_barang,4,2) = C.kd_kel
                                   and substr(B.c_barang,6,2) = C.kd_skel
                                   and substr(B.c_barang,8,3) = C.kd_sskel
                                   and to_char(d_inv_ajuanperbaikan,'YYYYMMDD')  = '$waktu'
                                   and not exists (select * from e_ast_rincibiayaperbaik_0_tm D
                                   where D.i_inv_ajuanperbaikan = A.i_inv_ajuanperbaikan)");
         } else if ($btnCari == 'Cari' && $waktu == '' && $unitKerja != ''){
		  //echo "2";
		  $listkpb = $db->fetchAll("Select A.i_inv_ajuanperbaikan, A.d_inv_ajuanperbaikan,A.i_orgb,B.d_anggaran,
                                   B.c_barang, to_char(B.i_aset,'09999'), C.ur_sskel, B.e_keterangan from
								   e_ast_ajuanpbaikinv_0_tm A,  e_ast_ajuanpbaikinv_item_tm B, e_ast_sskel_0_tr C
                                   where A.i_inv_ajuanperbaikan = B.i_inv_ajuanperbaikan
                                   and  substr(B.c_barang,1,1) = C.kd_gol
                                   and substr(B.c_barang,2,2) = C.kd_bid 
                                   and substr(B.c_barang,4,2) = C.kd_kel
                                   and substr(B.c_barang,6,2) = C.kd_skel
                                   and substr(B.c_barang,8,3) = C.kd_sskel 
								   and i_orgb = '$unitKerja'
                                   and not exists (select * from e_ast_rincibiayaperbaik_0_tm D
                                   where D.i_inv_ajuanperbaikan = A.i_inv_ajuanperbaikan)");
         } else if ($btnCari == 'Cari' && $waktu != '' && $unitKerja != '')	{
		   //echo "3";
		  $listkpb = $db->fetchAll("Select A.i_inv_ajuanperbaikan, A.d_inv_ajuanperbaikan,A.i_orgb,B.d_anggaran,
                                   B.c_barang, to_char(B.i_aset,'09999'), C.ur_sskel, B.e_keterangan from
								   e_ast_ajuanpbaikinv_0_tm A,  e_ast_ajuanpbaikinv_item_tm B, e_ast_sskel_0_tr C
                                   where A.i_inv_ajuanperbaikan = B.i_inv_ajuanperbaikan
                                   and  substr(B.c_barang,1,1) = C.kd_gol
                                   and substr(B.c_barang,2,2) = C.kd_bid 
                                   and substr(B.c_barang,4,2) = C.kd_kel
                                   and substr(B.c_barang,6,2) = C.kd_skel
                                   and substr(B.c_barang,8,3) = C.kd_sskel
								   and i_orgb = '$unitKerja'
                                   and to_char(d_inv_ajuanperbaikan,'YYYYMMDD')  = '$waktu'
                                   and not exists (select * from e_ast_rincibiayaperbaik_0_tm D
                                   where D.i_inv_ajuanperbaikan = A.i_inv_ajuanperbaikan)");
         } else if ($btnCari == 'Cari' && $waktu == '' && $unitKerja == '') {
		   //echo "4";
		  $listkpb = $db->fetchAll("Select A.i_inv_ajuanperbaikan, A.d_inv_ajuanperbaikan,A.i_orgb,B.d_anggaran,
                                   B.c_barang,to_char(B.i_aset,'09999'), C.ur_sskel, B.e_keterangan from
								   e_ast_ajuanpbaikinv_0_tm A,  e_ast_ajuanpbaikinv_item_tm B, e_ast_sskel_0_tr C
                                   where A.i_inv_ajuanperbaikan = B.i_inv_ajuanperbaikan
                                   and  substr(B.c_barang,1,1) = C.kd_gol
                                   and substr(B.c_barang,2,2) = C.kd_bid 
                                   and substr(B.c_barang,4,2) = C.kd_kel
                                   and substr(B.c_barang,6,2) = C.kd_skel
                                   and substr(B.c_barang,8,3) = C.kd_sskel
								   and not exists (select * from e_ast_rincibiayaperbaik_0_tm D
                                   where D.i_inv_ajuanperbaikan = A.i_inv_ajuanperbaikan)");
         } else if ($btnCari == '' && $waktu == '' && $unitKerja == '' ) {
		  //echo "5";
		  $listkpb = $db->fetchAll("Select A.i_inv_ajuanperbaikan, A.d_inv_ajuanperbaikan,A.i_orgb,B.d_anggaran,
                                   B.c_barang,to_char(B.i_aset,'09999'), C.ur_sskel, B.e_keterangan from
								   e_ast_ajuanpbaikinv_0_tm A,  e_ast_ajuanpbaikinv_item_tm B, e_ast_sskel_0_tr C
                                   where A.i_inv_ajuanperbaikan = B.i_inv_ajuanperbaikan
                                   and  substr(B.c_barang,1,1) = C.kd_gol
                                   and substr(B.c_barang,2,2) = C.kd_bid 
                                   and substr(B.c_barang,4,2) = C.kd_kel
                                   and substr(B.c_barang,6,2) = C.kd_skel
                                   and substr(B.c_barang,8,3) = C.kd_sskel
								   and not exists (select * from e_ast_rincibiayaperbaik_0_tm D
                                   where D.i_inv_ajuanperbaikan = A.i_inv_ajuanperbaikan)");
         }		 
         $jmlResult = count($listkpb);
		 //echo "jumlah : ".$jmlResult;
		 for ($j = 0; $j < $jmlResult; $j++) {
	     $nomor = $listkpb[$j]->d_anggaran." ".$listkpb[$j]->c_barang.$listkpb[$j]->to_char;
           
		   $tglAju = substr($listkpb[$j]->d_inv_ajuanperbaikan,8,2)."-".substr($listkpb[$j]->d_inv_ajuanperbaikan,5,2)."-".substr($listkpb[$j]->d_inv_ajuanperbaikan,0,4);
           $hasilAkhir[$j] = array("noAju"     =>(string)$listkpb[$j]->i_inv_ajuanperbaikan,
	                              // "tglAju"    =>(string)$listkpb[$j]->d_inv_ajuanperbaikan,
								   "tglAju"    =>(string)$tglAju,
							       "pemohon"   =>(string)$listkpb[$j]->i_orgb,
								   "nmBarang"  =>(string)$listkpb[$j]->ur_sskel,
								   "ket"       =>(string)$listkpb[$j]->e_keterangan,
								   "noAset"    =>(string)$nomor);
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	

	public function getInfoSetujuRuangRapatList($pageNumber,$itemPerPage,$tglGuna,$ruang) {
	    // Data ditampilkan berdasarkan otoritasnya (Dibatasi unit kerja yg mengajukan, kecuali untuk org2 tertentu yg bisa melihat seluruhnya)
		// yg sudah disetujui : c_rapat_statsetuju = 'Y' or c_rapat_statjwbusul = Y
		// (+) kolom untuk menampilkan status persetujuan Konsumsi
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[]=$tglGuna;
			$where[]=$ruang;
						
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
						AND i_ruang like ?
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
						AND i_ruang like ?
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
					     $ketKonsumsi = 'Tidak Minta';
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
		
	public function getInfoSetujuRuangRapatAllList($pageNumber,$itemPerPage,$ruang) {		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {		 
		 $where[]=$ruang;  
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
						AND i_ruang like ?
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
						AND i_ruang like ?
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
					     $ketKonsumsi = 'Tidak Minta';
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
	
	public function getListNip() {
       
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT i_peg_nip, n_peg, n_jabatan FROM e_sdm_pegawai_0_tm order by i_peg_nip");
	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array(	"i_peg_nip"   =>(string)$result[$j]->i_peg_nip,
									"n_peg"       =>(string)$result[$j]->n_peg,
									"n_jabatan"   =>(string)$result[$j]->n_jabatan);
						       
		
		 }
        }
        	
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getListGolongan() {
       
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select kd_gol, ur_gol from e_ast_gol_aset_tr 
		                          order by kd_gol");
	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array(	"kd_gol"           	 =>(string)$result[$j]->kd_gol,
									"ur_gol"          =>(string)$result[$j]->ur_gol);
						       
		
		 }
        }
        	
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getListBidang($kdGol) {
       
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select kd_bid, ur_bid, kd_perk from e_ast_bid_aset_tr 
		                          where kd_gol = '$kdGol' order by kd_bid");
	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array(	"kd_bid"           	 =>(string)$result[$j]->kd_bid,
									"ur_bid"          =>(string)$result[$j]->ur_bid);
						       
		
		 }
        }
        	
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getListKel($kdGol,$kdBid) {
       
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select kd_kel, ur_kel from e_ast_kel_aset_tr where kd_gol = '$kdGol' and 
		                          kd_bid = '$kdBid' order by kd_kel");
	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array(	"kd_kel"          =>(string)$result[$j]->kd_kel,
									"ur_kel"          =>(string)$result[$j]->ur_kel);
						       
		
		 }
        }
        	
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getListSKel($kdGol,$kdBid,$kdKel) {
       
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select kd_skel, ur_skel from e_ast_skel_0_tr where kd_gol = '$kdGol' and 
		                          kd_bid = '$kdBid' and kd_kel = '$kdKel' order by kd_skel");
	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array(	"kd_skel"          =>(string)$result[$j]->kd_skel,
									"ur_skel"          =>(string)$result[$j]->ur_skel);
						       
		
		 }
        }
        	
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getListSSKel($kdGol,$kdBid,$kdKel,$kdSKel) {
       
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select kd_sskel, ur_sskel from e_ast_sskel_0_tr where kd_gol = '$kdGol' and
                                  kd_bid = '$kdBid' and kd_kel = '$kdKel' and kd_skel = '$kdSKel' order by
								  kd_sskel");
	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array(	"kd_sskel"          =>(string)$result[$j]->kd_sskel,
									"ur_sskel"          =>(string)$result[$j]->ur_sskel);
						       
		
		 }
        }
        	
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	
	
	public function getListMutasiBarang($pilih) {
       
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if ($pilih =='tanah') {
		     $result = $db->fetchAll("SELECT distinct A.kd_brg as kd_brg,B.ur_sskel as ur_sskel
										FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
										where substr(a.kd_brg,1,1) = b.kd_gol
										and substr(a.kd_brg,2,2) = b.kd_bid 
										and substr(a.kd_brg,4,2) = b.kd_kel
										and substr(a.kd_brg,6,2) = b.kd_skel
										and substr(a.kd_brg,8,3) = b.kd_sskel
										and kd_brg like '101%' and ur_sskel like '%' order by B.ur_sskel");
	     
		 
		 } else if ($pilih =='bangged') {
		      $result = $db->fetchAll("SELECT distinct A.kd_brg as kd_brg,B.ur_sskel as ur_sskel
										FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
										where substr(a.kd_brg,1,1) = b.kd_gol
										and substr(a.kd_brg,2,2) = b.kd_bid 
										and substr(a.kd_brg,4,2) = b.kd_kel
										and substr(a.kd_brg,6,2) = b.kd_skel
										and substr(a.kd_brg,8,3) = b.kd_sskel
										and kd_brg like '106%' and ur_sskel like '%' order by B.ur_sskel");
		 } else if ($pilih =='angkut') {
		     $result = $db->fetchAll("SELECT distinct A.kd_brg as kd_brg,B.ur_sskel as ur_sskel
										FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
										where substr(a.kd_brg,1,1) = b.kd_gol
										and substr(a.kd_brg,2,2) = b.kd_bid 
										and substr(a.kd_brg,4,2) = b.kd_kel
										and substr(a.kd_brg,6,2) = b.kd_skel
										and substr(a.kd_brg,8,3) = b.kd_sskel
										and kd_brg like '202%' and ur_sskel like '%' order by B.ur_sskel");
		 }
		 
		 $jmlResult = count($result);
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array(	"kd_sskel"          =>(string)$result[$j]->kd_brg,
									"ur_sskel"          =>(string)$result[$j]->ur_sskel);
						       
		
		 }
        }
        	
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getDaftarKondisiBarang1($pilihopt,$waktu1,$waktu2) {
       // echo"Service1";	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select A.kd_brg, B.ur_sskel, A.no_aset, A.kd_sat, A.rph_aset,
		                          case when A.kondisi = '1' then A.kuantitas END as Baik,
								  case when A.kondisi = '2' then A.kuantitas END as RusakRingan,
								  case when A.kondisi = '3' then A.kuantitas END as RusakBerat
								  from e_sabm_t_master_tm A,e_ast_sskel_0_tr B
								  where substr(A.kd_brg,1,1) = B.kd_gol
								  and substr(A.kd_brg,2,2) = B.kd_bid and substr(A.kd_brg,4,2) = B.kd_kel
								  and substr(A.kd_brg,6,2) = B.kd_skel
								  and substr(A.kd_brg,8,3) = B.kd_sskel
								  and A.sejarah = '$pilihopt'
								  and to_char(A.tgl_perlh,'yyyy-mm-dd') between '$waktu1' and '$waktu2'");
		 //print_r($result);						  
	     $jmlResult = count($result);
		 //echo"Jumlah Service 1: ".$jmlResult;
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array(	"kode"      =>(string)$result[$j]->kd_brg,
									"nama"      =>(string)$result[$j]->ur_sskel,
									"nup"       =>(string)$result[$j]->no_aset,
									"satuan"    =>(string)$result[$j]->kd_sat,
									"harga"     =>(string)$result[$j]->rph_aset,
									"Baik"      =>(string)$result[$j]->baik,
									"RusakRingan"    =>(string)$result[$j]->rusakringan,
									"RusakBerat"     =>(string)$result[$j]->rusakberat
								  );
						       
		
		 }
        }
        	
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	
	public function getDaftarKondisiBarang2($pilihopt,$kodeBrg,$waktu1,$waktu2) {
        //echo"Service2";	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select A.kd_brg, B.ur_sskel, A.no_aset, A.kd_sat, A.rph_aset,
		                          case when A.kondisi = '1' then A.kuantitas END as Baik,
								  case when A.kondisi = '2' then A.kuantitas END as RusakRingan,
								  case when A.kondisi = '3' then A.kuantitas END as RusakBerat
								  from e_sabm_t_master_tm A,e_ast_sskel_0_tr B
								  where substr(A.kd_brg,1,1) = B.kd_gol
								  and substr(A.kd_brg,2,2) = B.kd_bid and substr(A.kd_brg,4,2) = B.kd_kel
								  and substr(A.kd_brg,6,2) = B.kd_skel
								  and substr(A.kd_brg,8,3) = B.kd_sskel
								  and A.sejarah = '$pilihopt'
								  and to_char(A.tgl_perlh,'yyyy-mm-dd') between '$waktu1' and '$waktu2'
								  and A.kd_brg = '$kodeBrg'");
		 //print_r($result);						  
	     $jmlResult = count($result);
		 //echo"Jumlah Service 2: ".$jmlResult;
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array(	"kode"      =>(string)$result[$j]->kd_brg,
									"nama"      =>(string)$result[$j]->ur_sskel,
									"nup"       =>(string)$result[$j]->no_aset,
									"satuan"    =>(string)$result[$j]->kd_sat,
									"harga"     =>(string)$result[$j]->rph_aset,
									"Baik"      =>(string)$result[$j]->baik,
									"RusakRingan"    =>(string)$result[$j]->rusakringan,
									"RusakBerat"     =>(string)$result[$j]->rusakberat
								  );
						       
		
		 }
        }
        	
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getDaftarKondisiBarang3($pilihopt,$pilihopt1,$waktu1,$waktu2) {
        //echo"Service3";	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select A.kd_brg, B.ur_sskel, A.no_aset, A.kd_sat, A.rph_aset,A.kuantitas from
                                  e_sabm_t_master_tm A,e_ast_sskel_0_tr B where substr(A.kd_brg,1,1) = B.kd_gol
								  and substr(A.kd_brg,2,2) = B.kd_bid 
								  and substr(A.kd_brg,4,2) = B.kd_kel
								  and substr(A.kd_brg,6,2) = B.kd_skel
								  and substr(A.kd_brg,8,3) = B.kd_sskel 
								  and A.sejarah = '$pilihopt'
								  and to_char(A.tgl_perlh,'yyyy-mm-dd') between '$waktu1' and '$waktu2'
								  and A.kondisi= '$pilihopt1'");
		 //print_r($result);						  
	     $jmlResult = count($result);
		 //echo"Jumlah Service 3: ".$jmlResult;
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array(	"kode"      =>(string)$result[$j]->kd_brg,
									"nama"      =>(string)$result[$j]->ur_sskel,
									"nup"       =>(string)$result[$j]->no_aset,
									"satuan"    =>(string)$result[$j]->kd_sat,
									"harga"     =>(string)$result[$j]->rph_aset,
									"jumlah"    =>(string)$result[$j]->kuantitas
								  );
						       
		
		 }
        }
        	
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getDaftarKondisiBarang4($pilihopt,$pilihopt1,$kodeBrg,$waktu1,$waktu2) {
        //echo"Service4";	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select A.kd_brg, B.ur_sskel, A.no_aset, A.kd_sat, A.rph_aset,A.kuantitas from
                                  e_sabm_t_master_tm A,e_ast_sskel_0_tr B where substr(A.kd_brg,1,1) = B.kd_gol
								  and substr(A.kd_brg,2,2) = B.kd_bid 
								  and substr(A.kd_brg,4,2) = B.kd_kel
								  and substr(A.kd_brg,6,2) = B.kd_skel
								  and substr(A.kd_brg,8,3) = B.kd_sskel 
								  and A.sejarah = '$pilihopt'
								  and to_char(A.tgl_perlh,'yyyy-mm-dd') between '$waktu1' and '$waktu2'
								  and A.kondisi= '$pilihopt1'
								  and A.kd_brg = '$kodeBrg' 
								  order by A.no_aset");
								  
		 //print_r($result);						  
		 
		 //print_r($result);						  
	     $jmlResult = count($result);
		 //echo"Jumlah Service 4: ".$jmlResult;
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array(	"kode"      =>(string)$result[$j]->kd_brg,
									"nama"      =>(string)$result[$j]->ur_sskel,
									"nup"       =>(string)$result[$j]->no_aset,
									"satuan"    =>(string)$result[$j]->kd_sat,
									"harga"     =>(string)$result[$j]->rph_aset,
									"jumlah"    =>(string)$result[$j]->kuantitas
								  );
						       
		
		 }
        }
        	
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	
	public function getCatMutPerTanahBarangAwalAkhir($kodeBrg,$awal,$akhir) {
	   //echo "A";
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select  distinct A.kd_brg, B.ur_sskel, A.no_aset, to_char(A.tgl_perlh,'YYYY') as thn_ang,
		                          (Select ur_wilayah from e_ast_wilayah_0_tr D where C.kd_prov = D.kd_wilayah)
								  as provinsi,
								  (Select ur_wilayah from e_ast_wilayah_0_tr D where C.kd_kab = D.kd_wilayah) 
								  as kabupaten,C.kd_kec, C.kd_kel, C.kd_rtrw, C.alamat, A.kd_pemilik
								  from e_sabm_t_master_tm A,e_ast_sskel_0_tr B, e_sabm_t_ktnh_tm C 
								  where substr(A.kd_brg,1,1) = B.kd_gol
								  and substr(A.kd_brg,2,2) = B.kd_bid 
								  and substr(A.kd_brg,4,2) = B.kd_kel
								  and substr(A.kd_brg,6,2) = B.kd_skel
								  and substr(A.kd_brg,8,3) = B.kd_sskel 
								  and A.kd_brg = C.kd_brg								  
								  and A.no_aset = C.no_aset
								  and A.kd_brg = '$kodeBrg'
								  and A.no_aset between '$awal' and '$akhir'");
		 //print_r($result);						  
	     $jmlResult = count($result);
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 $letak = $result[$j]->alamat.", ".$result[$j]->kd_rtrw.", ".$result[$j]->kd_kel.", ".
		          $result[$j]->kd_kec.", ".$result[$j]->kabupaten.", ".$result[$j]->provinsi;
           $hasilAkhir[$j] = array(	"kode"     =>(string)$result[$j]->kd_brg,
									"thn"      =>(string)$result[$j]->thn_ang,
									"uraian"   =>(string)$result[$j]->ur_sskel,
									"no"       =>(string)$result[$j]->no_aset,
									"lokasi"   =>(string)$letak,
									"milik"    =>(string)$result[$j]->kd_pemilik
								  );
						       
		
		 }
        }
        	
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getCatMutPerTanahBarang($kodeBrg) {
	   //echo "B";
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select  distinct A.kd_brg, B.ur_sskel, A.no_aset, to_char(A.tgl_perlh,'YYYY') as thn_ang, 
		                          (Select ur_wilayah from e_ast_wilayah_0_tr D where C.kd_prov = D.kd_wilayah)
								  as provinsi,
								  (Select ur_wilayah from e_ast_wilayah_0_tr D where C.kd_kab = D.kd_wilayah) 
								  as kabupaten,C.kd_kec, C.kd_kel, C.kd_rtrw, C.alamat, A.kd_pemilik
								  from e_sabm_t_master_tm A,e_ast_sskel_0_tr B, e_sabm_t_ktnh_tm C 
								  where substr(A.kd_brg,1,1) = B.kd_gol
								  and substr(A.kd_brg,2,2) = B.kd_bid 
								  and substr(A.kd_brg,4,2) = B.kd_kel
								  and substr(A.kd_brg,6,2) = B.kd_skel
								  and substr(A.kd_brg,8,3) = B.kd_sskel 
								  and A.kd_brg = C.kd_brg								  
								  and A.no_aset = C.no_aset
								  and A.kd_brg = '$kodeBrg'
								");
		 //print_r($result);						  
	     $jmlResult = count($result);
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 $letak = $result[$j]->alamat.", ".$result[$j]->kd_rtrw.", ".$result[$j]->kd_kel.", ".
		          $result[$j]->kd_kec.", ".$result[$j]->kabupaten.", ".$result[$j]->provinsi;
           $hasilAkhir[$j] = array(	"kode"     =>(string)$result[$j]->kd_brg,
									"thn"      =>(string)$result[$j]->thn_ang,
									"uraian"   =>(string)$result[$j]->ur_sskel,
									"no"       =>(string)$result[$j]->no_aset,
									"lokasi"   =>(string)$letak,
									"milik"    =>(string)$result[$j]->kd_pemilik
								  );
						       
		
		 }
        }
        	
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	
	public function getCatMutPerTanah() {
	   //echo "C";
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select  distinct A.kd_brg, B.ur_sskel, A.no_aset, to_char(A.tgl_perlh,'YYYY') as thn_ang,
		                          (Select ur_wilayah from e_ast_wilayah_0_tr D where C.kd_prov = D.kd_wilayah)
								  as provinsi,
								  (Select ur_wilayah from e_ast_wilayah_0_tr D where C.kd_kab = D.kd_wilayah) 
								  as kabupaten,C.kd_kec, C.kd_kel, C.kd_rtrw, C.alamat,  A.kd_pemilik
								  from e_sabm_t_master_tm A,e_ast_sskel_0_tr B, e_sabm_t_ktnh_tm C 
								  where substr(A.kd_brg,1,1) = B.kd_gol
								  and substr(A.kd_brg,2,2) = B.kd_bid 
								  and substr(A.kd_brg,4,2) = B.kd_kel
								  and substr(A.kd_brg,6,2) = B.kd_skel
								  and substr(A.kd_brg,8,3) = B.kd_sskel 
								  and A.kd_brg = C.kd_brg								  
								  and A.no_aset = C.no_aset
								");
		 //print_r($result);						  
	     $jmlResult = count($result);
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 $letak = $result[$j]->alamat.", ".$result[$j]->kd_rtrw.", ".$result[$j]->kd_kel.", ".
		          $result[$j]->kd_kec.", ".$result[$j]->kabupaten.", ".$result[$j]->provinsi;
           $hasilAkhir[$j] = array(	"kode"     =>(string)$result[$j]->kd_brg,
									"thn"      =>(string)$result[$j]->thn_ang,
									"uraian"   =>(string)$result[$j]->ur_sskel,
									"no"       =>(string)$result[$j]->no_aset,
									"lokasi"   =>(string)$letak,
									"milik"    =>(string)$result[$j]->kd_pemilik
								  );
						       
		
		 }
        }
        	
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	
	
	public function getCatMutPerBanggedBarangAwalAkhir($kodeBrg,$awal,$akhir) {
	   //echo "D";
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select  distinct A.kd_brg, B.ur_sskel, A.no_aset, to_char(A.tgl_perlh,'YYYY') as thn_ang,
		                         (Select ur_wilayah from e_ast_wilayah_0_tr D where C.kd_prov = D.kd_wilayah) 
								 as provinsi, (Select ur_wilayah from e_ast_wilayah_0_tr D where C.kd_kab =
								 D.kd_wilayah) as kabupaten, C.kd_kec, C.kd_kel, C.kd_rtrw, C.alamat, 
								 A.kd_pemilik
								 from e_sabm_t_master_tm A,e_ast_sskel_0_tr B, e_sabm_t_kbdg_tm C 
								 where substr(A.kd_brg,1,1) = B.kd_gol
								 and substr(A.kd_brg,2,2) = B.kd_bid 
								 and substr(A.kd_brg,4,2) = B.kd_kel
								 and substr(A.kd_brg,6,2) = B.kd_skel
								 and substr(A.kd_brg,8,3) = B.kd_sskel 
								 and A.kd_brg = C.kd_brg								 
								 and A.no_aset = C.no_aset
								 and A.kd_brg = '$kodeBrg'
								 and A.no_aset between '$awal' and '$akhir'");
		 //print_r($result);						  
	     $jmlResult = count($result);
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 $letak = $result[$j]->alamat.", ".$result[$j]->kd_rtrw.", ".$result[$j]->kd_kel.", ".
		          $result[$j]->kd_kec.", ".$result[$j]->kabupaten.", ".$result[$j]->provinsi;
           $hasilAkhir[$j] = array(	"kode"     =>(string)$result[$j]->kd_brg,
									"thn"      =>(string)$result[$j]->thn_ang,
									"uraian"   =>(string)$result[$j]->ur_sskel,
									"no"       =>(string)$result[$j]->no_aset,
									"lokasi"   =>(string)$letak,
									"milik"    =>(string)$result[$j]->kd_pemilik
								  );
						       
		
		 }
        }
        	
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getCatMutPerBanggedBarang($kodeBrg) {
	   //echo "E";
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select  distinct A.kd_brg, B.ur_sskel, A.no_aset, to_char(A.tgl_perlh,'YYYY') as thn_ang,
		                         (Select ur_wilayah from e_ast_wilayah_0_tr D where C.kd_prov = D.kd_wilayah) 
								 as provinsi, (Select ur_wilayah from e_ast_wilayah_0_tr D where C.kd_kab =
								 D.kd_wilayah) as kabupaten, C.kd_kec, C.kd_kel, C.kd_rtrw, C.alamat, 
								 A.kd_pemilik
								 from e_sabm_t_master_tm A,e_ast_sskel_0_tr B, e_sabm_t_kbdg_tm C 
								 where substr(A.kd_brg,1,1) = B.kd_gol
								 and substr(A.kd_brg,2,2) = B.kd_bid 
								 and substr(A.kd_brg,4,2) = B.kd_kel
								 and substr(A.kd_brg,6,2) = B.kd_skel
								 and substr(A.kd_brg,8,3) = B.kd_sskel 
								 and A.kd_brg = C.kd_brg								 
								 and A.no_aset = C.no_aset
								 and A.kd_brg = '$kodeBrg'
								");
		 //print_r($result);						  
	     $jmlResult = count($result);
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 $letak = $result[$j]->alamat.", ".$result[$j]->kd_rtrw.", ".$result[$j]->kd_kel.", ".
		          $result[$j]->kd_kec.", ".$result[$j]->kabupaten.", ".$result[$j]->provinsi;
           $hasilAkhir[$j] = array(	"kode"     =>(string)$result[$j]->kd_brg,
									"thn"      =>(string)$result[$j]->thn_ang,
									"uraian"   =>(string)$result[$j]->ur_sskel,
									"no"       =>(string)$result[$j]->no_aset,
									"lokasi"   =>(string)$letak,
									"milik"    =>(string)$result[$j]->kd_pemilik
								  );
						       
		
		 }
        }
        	
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	
	public function getCatMutPerBangged() {
	   //echo "F";
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select  distinct A.kd_brg, B.ur_sskel, A.no_aset, to_char(A.tgl_perlh,'YYYY') as thn_ang,
		                         (Select ur_wilayah from e_ast_wilayah_0_tr D where C.kd_prov = D.kd_wilayah) 
								 as provinsi, (Select ur_wilayah from e_ast_wilayah_0_tr D where C.kd_kab =
								 D.kd_wilayah) as kabupaten, C.kd_kec, C.kd_kel, C.kd_rtrw, C.alamat, 
								 A.kd_pemilik
								 from e_sabm_t_master_tm A,e_ast_sskel_0_tr B, e_sabm_t_kbdg_tm C 
								 where substr(A.kd_brg,1,1) = B.kd_gol
								 and substr(A.kd_brg,2,2) = B.kd_bid 
								 and substr(A.kd_brg,4,2) = B.kd_kel
								 and substr(A.kd_brg,6,2) = B.kd_skel
								 and substr(A.kd_brg,8,3) = B.kd_sskel 
								 and A.kd_brg = C.kd_brg								 
								 and A.no_aset = C.no_aset
								");
		 //print_r($result);						  
	     $jmlResult = count($result);
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 $letak = $result[$j]->alamat.", ".$result[$j]->kd_rtrw.", ".$result[$j]->kd_kel.", ".
		          $result[$j]->kd_kec.", ".$result[$j]->kabupaten.", ".$result[$j]->provinsi;
           $hasilAkhir[$j] = array(	"kode"     =>(string)$result[$j]->kd_brg,
									"thn"      =>(string)$result[$j]->thn_ang,
									"uraian"   =>(string)$result[$j]->ur_sskel,
									"no"       =>(string)$result[$j]->no_aset,
									"lokasi"   =>(string)$letak,
									"milik"    =>(string)$result[$j]->kd_pemilik
								  );
						       
		
		 }
        }
        	
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	
	
	public function getCatMutPerAngkutBarangAwalAkhir($kodeBrg,$awal,$akhir) {
	   //echo "G";
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select  distinct A.kd_brg, B.ur_sskel, A.no_aset, A.kd_pemilik, to_char(A.tgl_perlh,'YYYY') as thn_ang from
                                  e_sabm_t_master_tm A,e_ast_sskel_0_tr B, e_sabm_t_kangk_tm C where
								  substr(A.kd_brg,1,1) = B.kd_gol and substr(A.kd_brg,2,2) = B.kd_bid 
								  and substr(A.kd_brg,4,2) = B.kd_kel
								  and substr(A.kd_brg,6,2) = B.kd_skel
								  and substr(A.kd_brg,8,3) = B.kd_sskel 
								  and A.kd_brg = C.kd_brg								  
								  and A.no_aset = C.no_aset
								  and A.kd_brg = '$kodeBrg'
								  and A.no_aset between '$awal' and '$akhir'");
		 //print_r($result);						  
	     $jmlResult = count($result);
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 $letak = $result[$j]->alamat.", ".$result[$j]->kd_rtrw.", ".$result[$j]->kd_kel.", ".
		          $result[$j]->kd_kec.", ".$result[$j]->kabupaten.", ".$result[$j]->provinsi;
           $hasilAkhir[$j] = array(	"kode"     =>(string)$result[$j]->kd_brg,
									"thn"      =>(string)$result[$j]->thn_ang,
									"uraian"   =>(string)$result[$j]->ur_sskel,
									"no"       =>(string)$result[$j]->no_aset,
									"lokasi"   =>(string)$letak,
									"milik"    =>(string)$result[$j]->kd_pemilik
								  );
						       
		
		 }
        }
        	
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getCatMutPerAngkutBarang($kodeBrg) {
	   //echo "H"; 
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select  distinct A.kd_brg, B.ur_sskel, A.no_aset, to_char(A.tgl_perlh,'YYYY') as thn_ang, A.kd_pemilik from
                                  e_sabm_t_master_tm A,e_ast_sskel_0_tr B, e_sabm_t_kangk_tm C where
								  substr(A.kd_brg,1,1) = B.kd_gol and substr(A.kd_brg,2,2) = B.kd_bid 
								  and substr(A.kd_brg,4,2) = B.kd_kel
								  and substr(A.kd_brg,6,2) = B.kd_skel
								  and substr(A.kd_brg,8,3) = B.kd_sskel 
								  and A.kd_brg = C.kd_brg								  
								  and A.no_aset = C.no_aset
								  and A.kd_brg = '$kodeBrg'
								");
		 //print_r($result);						  
	     $jmlResult = count($result);
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 $letak = $result[$j]->alamat.", ".$result[$j]->kd_rtrw.", ".$result[$j]->kd_kel.", ".
		          $result[$j]->kd_kec.", ".$result[$j]->kabupaten.", ".$result[$j]->provinsi;
           $hasilAkhir[$j] = array(	"kode"     =>(string)$result[$j]->kd_brg,
									"thn"      =>(string)$result[$j]->thn_ang,
									"uraian"   =>(string)$result[$j]->ur_sskel,
									"no"       =>(string)$result[$j]->no_aset,
									"lokasi"   =>(string)$letak,
									"milik"    =>(string)$result[$j]->kd_pemilik
								  );
						       
		
		 }
        }
        	
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	
	public function getCatMutPerAngkut() {
	   //echo "I";
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select  distinct A.kd_brg, B.ur_sskel, A.no_aset, to_char(A.tgl_perlh,'YYYY') as thn_ang, A.kd_pemilik from
                                  e_sabm_t_master_tm A,e_ast_sskel_0_tr B, e_sabm_t_kangk_tm C where
								  substr(A.kd_brg,1,1) = B.kd_gol and substr(A.kd_brg,2,2) = B.kd_bid 
								  and substr(A.kd_brg,4,2) = B.kd_kel
								  and substr(A.kd_brg,6,2) = B.kd_skel
								  and substr(A.kd_brg,8,3) = B.kd_sskel 
								  and A.kd_brg = C.kd_brg								  
								  and A.no_aset = C.no_aset
								");
		 //print_r($result);						  
	     $jmlResult = count($result);
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 $letak = $result[$j]->alamat.", ".$result[$j]->kd_rtrw.", ".$result[$j]->kd_kel.", ".
		          $result[$j]->kd_kec.", ".$result[$j]->kabupaten.", ".$result[$j]->provinsi;
           $hasilAkhir[$j] = array(	"kode"     =>(string)$result[$j]->kd_brg,
									"thn"      =>(string)$result[$j]->thn_ang,
									"uraian"   =>(string)$result[$j]->ur_sskel,
									"no"       =>(string)$result[$j]->no_aset,
									"lokasi"   =>(string)$letak,
									"milik"    =>(string)$result[$j]->kd_pemilik
								  );
						       
		
		 }
        }
        	
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	

	 public function getCatMutPerDetTanah($no,$kode,$thn) {
	   //echo "Tanah";
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select distinct C.tgl_prl,C.jns_trn, D.ur_trn, A.kuantitas, A.rph_aset 
		                          from e_sabm_t_master_tm A,  e_sabm_t_ktnh_tm C, e_ast_jns_trn_tr D 
								  where c.jns_trn = D.jns_trn
								  and A.kd_brg = C.kd_brg								  
								  and A.no_aset = C.no_aset
								  and C.kd_brg = '$kode' 
								  and to_char(A.tgl_perlh,'YYYY') = '$thn'
								  and C.no_aset = '$no'
								");
		 //print_r($result);						  
	     $jmlResult = count($result);
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 $jenis = $result[$j]->jns_trn." ".$result[$j]->ur_trn;
           $hasilAkhir[$j] = array(	"tgl"     =>(string)$result[$j]->tgl_prl,
									"jenis"   =>(string)$jenis,
									"banyak"  =>(string)$result[$j]->kuantitas,
									"harga"   =>(string)$result[$j]->rph_aset
								  );
						       
		
		 }
        }
        	
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	
	 public function getCatMutPerDetBangged($no,$kode,$thn) {
	  // echo "Bangged";
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select distinct C.tgl_prl,C.jns_trn, D.ur_trn, A.kuantitas, A.rph_aset 
		                         from e_sabm_t_master_tm A,e_sabm_t_kbdg_tm C, e_ast_jns_trn_tr D 
								 where c.jns_trn = D.jns_trn
								 and A.kd_brg = C.kd_brg								  
								 and A.no_aset = C.no_aset
								 and C.kd_brg = '$kode' 
								 and to_char(A.tgl_perlh,'YYYY') = '$thn'
								 and C.no_aset = '$no'
								");
		 //print_r($result);						  
	     $jmlResult = count($result);
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 $jenis = $result[$j]->jns_trn." ".$result[$j]->ur_trn;
           $hasilAkhir[$j] = array(	"tgl"     =>(string)$result[$j]->tgl_prl,
									"jenis"   =>(string)$jenis,
									"banyak"  =>(string)$result[$j]->kuantitas,
									"harga"   =>(string)$result[$j]->rph_aset
								  );
						       
		
		 }
        }
        	
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getCatMutPerDetAngkut($no,$kode,$thn) {
	   //echo "Angkut";
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select distinct C.tgl_prl,C.jns_trn, D.ur_trn, A.kuantitas, A.rph_aset 
							      from e_sabm_t_master_tm A, e_sabm_t_kangk_tm C, e_ast_jns_trn_tr D 
								  where c.jns_trn = D.jns_trn
								  and A.kd_brg = C.kd_brg								  
								  and A.no_aset = C.no_aset
								  and C.kd_brg = '$kode'
								  and to_char(A.tgl_perlh,'YYYY') = '$thn'
								  and C.no_aset = '$no'
								  ");
		 //print_r($result);						  
	     $jmlResult = count($result);
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 $jenis = $result[$j]->jns_trn." ".$result[$j]->ur_trn;
           $hasilAkhir[$j] = array(	"tgl"     =>(string)$result[$j]->tgl_prl,
									"jenis"   =>(string)$jenis,
									"banyak"  =>(string)$result[$j]->kuantitas,
									"harga"   =>(string)$result[$j]->rph_aset
								  );
						       
		
		 }
        }
        	
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	} 
	
	////*********** Tambahan/Perubahan Cah Bagus *********** ////	
	public function getNBarangAllList($pageNumber,$itemPerPage) 
	{
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {			
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0)){
				$query= "select count(*) from (select distinct kd_brg, kd_sat, ur_sskel
						from e_sabm_t_master_tm A, e_ast_sskel_0_tr B
						where substr(a.kd_brg,1,1) = b.kd_gol
						and substr(a.kd_brg,2,2) = b.kd_bid 
						and substr(a.kd_brg,4,2) = b.kd_kel
						and substr(a.kd_brg,6,2) = b.kd_skel
						and substr(a.kd_brg,8,3) = b.kd_sskel) as a";
				$hasilAkhir = $db->fetchOne($query);
			}else{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				$query= "select distinct kd_brg, kd_sat, ur_sskel
						from e_sabm_t_master_tm A, e_ast_sskel_0_tr B
						where substr(a.kd_brg,1,1) = b.kd_gol
						and substr(a.kd_brg,2,2) = b.kd_bid 
						and substr(a.kd_brg,4,2) = b.kd_kel
						and substr(a.kd_brg,6,2) = b.kd_skel
						and substr(a.kd_brg,8,3) = b.kd_sskel ".
						" ORDER BY ur_sskel Asc limit $xLimit offset $xOffset";
				
				$result = $db->fetchAll($query); 
				$jmlResult = count($result);
				for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("kd_brg" =>(string)$result[$j]->kd_brg
						,"kd_sat" =>(string)$result[$j]->kd_sat
						,"ur_sskel" =>(string)$result[$j]->ur_sskel
							   );
				}	
			}	 
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}	 
	
	////*********** Akhir Tambahan/Perubahan Cah Bagus *********** ////	
}		
?>