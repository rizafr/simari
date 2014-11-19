<?php
class ast_laporan_ast_Service {
   
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
	 * fungsi untuk   e_ast_ajuanpindahinv_0_tm
	 ***************************/
	//=================================== 12 Nop 07=============================================================================
	
	public function queryUnitKerja($unitkr) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = "i_orgb = '".$data['unitkr']."'";
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT c_satker,n_unitkerja FROM sdm.tr_unitkerja where c_satker=?',$unitkr);
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb"           =>(string)$result[$j]->c_satker,
								   "n_orgb"          =>(string)$result[$j]->n_unitkerja);
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	//=========================================================================================================================
	//=========================================================================================================================
	public function getLaporanBMN($tglAwal,$tglAkhir) {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {			
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 			 				
				$where[] 	= $tglAkhir;				
								
				// $result = $db->fetchAll("select E.kd_perk, C.ur_sminor, A.kd_brg, B.ur_sskel, A.kd_sat
											// from aset.tm_masterhm A,aset.tm_sskel B,
											// aset.tr_perk C, aset.tm_croleh D, aset.tm_sskel E
											// where substr(A.kd_brg,1,1) = B.kd_gol
											// and substr(A.kd_brg,2,2) = B.kd_bid 
											// and substr(A.kd_brg,4,2) = B.kd_kel
											// and substr(A.kd_brg,6,2) = B.kd_skel
											// and substr(A.kd_brg,8,3) = B.kd_sskel 
											// and A.jns_trn = D.jns_trn
											// and substr(A.kd_brg,1,1) = E.kd_gol
											// and substr(A.kd_brg,2,2) = E.kd_bid
											// and E.kd_perk = C.kd_perk 
											// and A.tgl_perlh <= ?
											// group by  E.kd_perk, C.ur_sminor,A.kd_brg, B.ur_sskel, A.kd_sat
											// order by kd_perk,kd_brg" ,$where);
				$result = $db->fetchAll("select B.kd_perk, C.ur_sminor, A.kd_brg, B.ur_sskel
										from aset.tm_masterhm A,aset.tm_sskel B,
										aset.tr_perk C, aset.tm_croleh D
										where substr(A.kd_brg,1,1) = B.kd_gol
										and substr(A.kd_brg,2,2) = B.kd_bid 
										and substr(A.kd_brg,4,2) = B.kd_kel
										and substr(A.kd_brg,6,2) = B.kd_skel
										and substr(A.kd_brg,8,3) = B.kd_sskel 
										and A.jns_trn = D.jns_trn
										and B.kd_perk = C.kd_perk 
										and A.tgl_perlh <= ?
										group by  B.kd_perk, C.ur_sminor,A.kd_brg, B.ur_sskel
										order by kd_perk,kd_brg" ,$where);
											
				
				$jmlResult = count($result);
				
				for ($j = 0; $j < $jmlResult; $j++) {
					$kd_brg[0] = $result[$j]->kd_brg;
					
					$kuantitasAwal 			= $db->fetchCol("select sum(A.kuantitas) as kuantitasAwal
															from aset.tm_masterhm A
															where kd_brg = '$kd_brg[0]'  
															and A.tgl_perlh <= '$tglAwal'");
															
					$nilaiAwal 				= $db->fetchCol("select sum(A.rph_aset) as nilaiAwal
															from aset.tm_masterhm A
															where kd_brg = '$kd_brg[0]'  
															and A.tgl_perlh <= '$tglAwal'");	
					
					$kuantitasPlus 			= $db->fetchCol("select sum(A.kuantitas) as kuantitasPlus
															from aset.tm_masterhm A
															where kd_brg = '$kd_brg[0]'  
															and (A.tgl_perlh > '$tglAwal' and A.tgl_perlh <= '$tglAkhir')
															and A.jns_trn in ('100','101','102','103','104','105','107','202')");
															
					$nilaiPlus 				= $db->fetchCol("select sum(A.rph_aset) as nilaiPlus
															from aset.tm_masterhm A
															where kd_brg = '$kd_brg[0]'  
															and (A.tgl_perlh > '$tglAwal' and A.tgl_perlh <= '$tglAkhir')
															 and A.jns_trn in ('100','101','102','103','104','105','107','202')");										
					
					$kuantitasMin 			= $db->fetchCol("select sum(A.kuantitas) as kuantitasPlus
															from aset.tm_masterhm A
															where kd_brg = '$kd_brg[0]'  
															and (A.tgl_perlh > '$tglAwal' and A.tgl_perlh <= '$tglAkhir')
															and A.jns_trn not in ('100','101','102','103','104','105','107','202')");
															
					$nilaiMin 				= $db->fetchCol("select sum(A.rph_aset) as nilaiPlus
															from aset.tm_masterhm A
															where kd_brg = '$kd_brg[0]'  
															and (A.tgl_perlh > '$tglAwal' and A.tgl_perlh <= '$tglAkhir')
															 and A.jns_trn not in ('100','101','102','103','104','105','107','202')");
					
					//"kd_sat"         		=>(string)$result[$j]->kd_sat,
					$hasilAkhir[$j] = array("jns_trn"            	=>(string)$result[$j]->jns_trn,
										   "kd_perk"       			=>(string)$result[$j]->kd_perk,
										   "ur_sminor"            	=>(string)$result[$j]->ur_sminor,
										   "kd_brg"     			=>(string)$result[$j]->kd_brg,
										   "ur_sskel"          		=>(string)$result[$j]->ur_sskel,
										   
										   "kuantitasAwal"         	=>$kuantitasAwal[0],
										   "nilaiAwal"         		=>$nilaiAwal[0],
										   "kuantitasPlus"         	=>$kuantitasPlus[0],
										   "nilaiPlus"         		=>$nilaiPlus[0],
										   "kuantitasMin"         	=>$kuantitasMin[0],
										   "nilaiMin"         		=>$nilaiMin[0],
										   "qKuantitasAkhir"        =>($kuantitasAwal[0] + $kuantitasPlus[0]) - $kuantitasMin[0],
										   "qNilaiAkhir"         	=>($nilaiAwal[0]  + $nilaiPlus[0]) - $nilaiMin[0]  );
										   
					
		 }	
		//}
	     return $hasilAkhir;
		
		}catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	//=========================================================================================================================
	public function getLaporanDaftarBMN($jnstrn,$tglAwal,$tglAkhir) {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 
				$where[] 	= $jnstrn;
				$where[] 	= $tglAwal;
				$where[] 	= $tglAkhir;
								
				$result = $db->fetchAll("select A.jns_trn, D.ur_trn, E.kd_perk, C.ur_sminor, A.kd_brg, B.ur_sskel, 
										A.kd_sat, sum(A.kuantitas) as kuantitas, sum(A.rph_aset) as rph_aset
										from aset.tm_masterhm A,aset.tm_sskel B,
										aset.tr_perk C, aset.tm_croleh D,
										e_ast_bid_aset_tr E
										where substr(A.kd_brg,1,1) = B.kd_gol
										and substr(A.kd_brg,2,2) = B.kd_bid 
										and substr(A.kd_brg,4,2) = B.kd_kel
										and substr(A.kd_brg,6,2) = B.kd_skel
										and substr(A.kd_brg,8,3) = B.kd_sskel 
										and A.jns_trn = D.jns_trn
										and substr(A.kd_brg,1,1) = E.kd_gol
										and substr(A.kd_brg,2,2) = E.kd_bid
										and E.kd_perk = C.kd_perk 
										and A.jns_trn = ?
										and A.tgl_perlh between ? and ?
										group by A.jns_trn, D.ur_trn, E.kd_perk, C.ur_sminor,A.kd_brg, 
										B.ur_sskel, A.kd_sat
										order by A.jns_trn,E.kd_perk
										" ,$where);
				
				$jmlResult = count($result);
				
				for ($j = 0; $j < $jmlResult; $j++) {
					$kd_brg[0] = $result[$j]->kd_brg;
					
					$hasilAkhir[$j] = array("jns_trn"            	=>(string)$result[$j]->jns_trn,
										   "kd_perk"       			=>(string)$result[$j]->kd_perk,
										   "ur_sminor"            	=>(string)$result[$j]->ur_sminor,
										   "kd_brg"     			=>(string)$result[$j]->kd_brg,
										   "ur_sskel"          		=>(string)$result[$j]->ur_sskel,
										   "kd_sat"         		=>(string)$result[$j]->kd_sat,
										   "kuantitas"         		=>(string)$result[$j]->kuantitas,
										   "rph_aset"         		=>(string)$result[$j]->rph_aset);
										   
		 }	
		 return $hasilAkhir;
		
		}catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getRefJnsTrnList($pageNumber,$itemPerPage,$namaBarang) {
		$namaBarang = strtoupper($namaBarang);
		$nbrg = '%'.$namaBarang.'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $nbrg;
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			 {
											 
				$result = $db->fetchAll("SELECT *
										FROM   aset.tm_croleh 
										where ur_trn like ?
										ORDER BY jns_trn",$where); 
			 
			 
				$jmlResult  = count($result);
				$hasilAkhir = $jmlResult;
			 }
			else
			 {
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT jns_trn,ur_trn 
										FROM aset.tm_croleh
										where ur_trn like ?
										ORDER BY jns_trn
									limit $xLimit offset $xOffset",$where); 
			 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("jns_trn"         =>(string)$result[$j]->jns_trn,
										   "ur_trn"           	=>(string)$result[$j]->ur_trn);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
	
	//=========================================================================================================================
	public function getLaporanPersediaanPaging($pageNumber,$itemPerPage,$tglAwal,$tglAkhir) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_barang_atk_tr");			
		 }
		 else
		 {
			
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;		
			 echo '$xLimit'.$xLimit;
			 echo '$xOffset'.$xOffset;
			 
			$whereA[] 	= 'Y';
			$whereA[] 	= $tglAwal;
			$whereB[] 	= $tglAwal;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 
			//$kategori = $db->fetchAll('select * from   e_ast_kategori_atk_tr ');
	        //$jmlRkategori = count($kategori);
			 
			// for ($i = 0; $i < $kategori; $i++) {
				//$result = $db->fetchAll('select * from e_ast_barang_atk_tr order by c_atk');
				$resultAwal = $db->fetchAll('select distinct c.c_atk,c.c_atk_ctgr,n_atk,n_atk_satuan,c.v_atk_hrgsatuan
										    from e_ast_terima_atk_tm A, e_ast_terima_itematk_tm B, e_ast_barang_atk_tr c
											where A.i_atk_terima = B.i_atk_terima and b.c_atk=c.c_atk
											and A.c_atk_setuju = ?
											and A.d_atk_beli < ? ',$whereA);
				
				$resultAwal2 = $db->fetchAll('select distinct c.c_atk,c.c_atk_ctgr,n_atk,n_atk_satuan,c.v_atk_hrgsatuan
										    from e_ast_distribusi_atk_tm A, e_ast_distribusi_itematk_tm B, e_ast_barang_atk_tr c
											where A.i_atk_kirim = B.i_atk_kirim and b.c_atk=c.c_atk
											and A.d_atk_kirim < ? ',$whereB);				
				
				$where[] 	= 'Y';
				$where[] 	= $tglAwal;
				$where[] 	= $tglAkhir;
				$where[] 	= $tglAwal;
				$where[] 	= $tglAkhir;
				$where[] 	= 'Y';
				$where[] 	= $tglAwal;
				$where[] 	= $tglAkhir;
				$where[] 	= $tglAwal;
				$where[] 	= $tglAkhir;
				/*$result = $db->fetchAll('select distinct c.c_atk,c.c_atk_ctgr,n_atk,n_atk_satuan,c.v_atk_hrgsatuan,n_atk_tipe
											from e_ast_terima_atk_tm A, e_ast_terima_itematk_tm B, e_ast_barang_atk_tr c,
											e_ast_distribusi_atk_tm d, e_ast_distribusi_itematk_tm e
											where A.i_atk_terima = B.i_atk_terima and b.c_atk=c.c_atk 
											and e.c_atk= c.c_atk
											and A.c_atk_setuju = ?
											and A.d_atk_beli > ? and A.d_atk_beli < ?
											and d.d_atk_kirim > ? and d.d_atk_kirim < ? ',$where);
		        */
				
				$result = $db->fetchAll("select distinct c.c_atk,c.c_atk_ctgr,n_atk,n_atk_satuan,c.v_atk_hrgsatuan,n_atk_tipe
											from e_ast_terima_atk_tm A, e_ast_terima_itematk_tm B, e_ast_barang_atk_tr c,
											e_ast_distribusi_atk_tm d, e_ast_distribusi_itematk_tm e
											where A.i_atk_terima = B.i_atk_terima and b.c_atk=c.c_atk 
											and e.c_atk= c.c_atk
											and A.c_atk_setuju = ?
											and A.d_atk_beli > ? and A.d_atk_beli < ?
											and d.d_atk_kirim > ? and d.d_atk_kirim < ?
											UNION
											SELECT
											c.c_atk,
											c.c_atk_ctgr,
											c.n_atk,
											c.n_atk_satuan,
											c.v_atk_hrgsatuan,
											c.n_atk_tipe
											 from e_ast_barang_atk_tr c
											 where not exists(select b.c_atk from e_ast_terima_atk_tm A, e_ast_terima_itematk_tm B, e_ast_distribusi_atk_tm d, e_ast_distribusi_itematk_tm e
											where A.i_atk_terima = B.i_atk_terima and b.c_atk=c.c_atk 
											and e.c_atk= c.c_atk
											and A.c_atk_setuju = ?
											and A.d_atk_beli > ? and A.d_atk_beli < ?
											and d.d_atk_kirim > ? and d.d_atk_kirim < ?)
											ORDER BY c_atk
											limit $xLimit offset $xOffset",$where);
				
				$jmlResult = count($result);
				echo '$jmlResult'.$jmlResult;
				for ($j = 0; $j < $jmlResult; $j++) {
					/*$kode= (string)$result[$j]->c_atk;
					
					$PengadaanSebelum 	= $db->fetchCol('SELECT sum(q_atk_realterima) FROM e_ast_terima_atk_tm A, e_ast_terima_itematk_tm B
											where A.i_atk_terima = B.i_atk_terima 
											and c_atk = ? ',$resultAwal[$j]->c_atk);
											
					$PemakaianSebelum 	= $db->fetchCol('SELECT sum(q_atk_kirim) FROM e_ast_distribusi_atk_tm A, e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim 
											and c_atk = ? ',$resultAwal2[$j]->c_atk);
					
					$Pengadaan		 	= $db->fetchCol('SELECT sum(q_atk_realterima) FROM e_ast_terima_atk_tm A, e_ast_terima_itematk_tm B
											where A.i_atk_terima = B.i_atk_terima 
											and c_atk = ? ',$result[$j]->c_atk);
					
					$Pemakaian 			= $db->fetchCol('SELECT sum(q_atk_kirim) FROM e_ast_distribusi_atk_tm A, e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim 
											and c_atk = ? ',$result[$j]->c_atk);	
					*/						
					//PersediaanAwal = PengadaanSebelum – PemakaianSebelum
					//$PersediaanAwal = ($PengadaanSebelum) - ($PemakaianSebelum);
					//PersediaanAkhir= PersediaanAwal + Pengadaan - Pemakaian
					//$PersediaanAkhir = $PersediaanAkhir + $Pengadaan - $Pemakaian;
					
					$hasilAkhir[$j] = array("c_atk"            	=>(string)$result[$j]->c_atk,
										   "c_atk_ctgr"       	=>(string)$result[$j]->c_atk_ctgr,
										   "n_atk"            	=>(string)$result[$j]->n_atk,
										   "n_atk_satuan"     	=>(string)$result[$j]->n_atk_satuan,
										   "jumlah"   			=>$c_atk[0],
										   "jumlah2"          	=>(string)$result[$j]->jumlah2,
										   "mutasi1"         	=>(string)$result[$j]->mutasi1,
										   "mutasi2"         	=>(string)$result[$j]->mutasi2,
										   "v_atk_hrgsatuan"    =>(string)$result[$j]->v_atk_hrgsatuan,
										   "d_atk_kirim"        =>(string)$result[$j]->d_atk_kirim,
										   "q_atk_stock"        =>(string)$result[$j]->q_atk_stock,
										   //"Pengadaan"   		=>$Pengadaan[0],
										   "Pengadaan"   		=>0,
										   //"nilai"            	=>($PengadaanSebelum[0] - $PemakaianSebelum[0]) * (string)$result[$j]->v_atk_hrgsatuan,
										   "nilai"            	=>0,
										   //"Pemakaian"   		=>$Pemakaian[0],
										   "Pemakaian"   		=>0,
										   //"PersediaanAwal"   	=>$PengadaanSebelum[0] - $PemakaianSebelum[0],
										   "PersediaanAwal"   	=>0,
										   //"PersediaanAkhir"   	=>($PengadaanSebelum[0] - $PemakaianSebelum[0])+$Pengadaan[0] - $Pemakaian[0],
										   "PersediaanAkhir"   	=>0,
										   //"nilai2"            	=>(($PengadaanSebelum[0] - $PemakaianSebelum[0])+$Pengadaan[0] - $Pemakaian[0]) * (string)$result[$j]->v_atk_hrgsatuan,
										   "nilai2"            	=>0,
										   "n_atk_tipe"			=>(string)$result[$j]->n_atk_tipe);
			}	
		}
	     return $hasilAkhir;
		
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	
	//=========================================================================================================================
	
	public function getLaporanPengadaan($tglAwal,$tglAkhir) {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
			$whereA[] 	= 'Y';
			$whereA[] 	= $tglAwal;
			$whereB[] 	= $tglAwal;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 
			//$kategori = $db->fetchAll('select * from   e_ast_kategori_atk_tr ');
	        //$jmlRkategori = count($kategori);
			 
			// for ($i = 0; $i < $kategori; $i++) {
				//$result = $db->fetchAll('select * from e_ast_barang_atk_tr order by c_atk');
				$resultAwal = $db->fetchAll('select distinct c.c_atk,c.c_atk_ctgr,n_atk,n_atk_satuan,c.v_atk_hrgsatuan
										    from e_ast_terima_atk_tm A, e_ast_terima_itematk_tm B, e_ast_barang_atk_tr c
											where A.i_atk_terima = B.i_atk_terima and b.c_atk=c.c_atk
											and A.c_atk_setuju = ?
											and A.d_atk_beli < ? ',$whereA);
				
				$resultAwal2 = $db->fetchAll('select distinct c.c_atk,c.c_atk_ctgr,n_atk,n_atk_satuan,c.v_atk_hrgsatuan
										    from e_ast_distribusi_atk_tm A, e_ast_distribusi_itematk_tm B, e_ast_barang_atk_tr c
											where A.i_atk_kirim = B.i_atk_kirim and b.c_atk=c.c_atk
											and A.d_atk_kirim < ? ',$whereB);				
				
				$where[] 	= 'Y';
				$where[] 	= $tglAwal;
				$where[] 	= $tglAkhir;
				$where[] 	= $tglAwal;
				$where[] 	= $tglAkhir;
				$where[] 	= 'Y';
				$where[] 	= $tglAwal;
				$where[] 	= $tglAkhir;
				$where[] 	= $tglAwal;
				$where[] 	= $tglAkhir;
								
				$result = $db->fetchAll('select distinct c.c_atk,c.c_atk_ctgr,n_atk,n_atk_satuan,c.v_atk_hrgsatuan,n_atk_tipe
											from e_ast_terima_atk_tm A, e_ast_terima_itematk_tm B, e_ast_barang_atk_tr c,
											e_ast_distribusi_atk_tm d, e_ast_distribusi_itematk_tm e
											where A.i_atk_terima = B.i_atk_terima and b.c_atk=c.c_atk 
											and e.c_atk= c.c_atk
											and A.c_atk_setuju = ?
											and A.d_atk_beli > ? and A.d_atk_beli < ?
											and d.d_atk_kirim > ? and d.d_atk_kirim < ?
											UNION
											SELECT
											c.c_atk,
											c.c_atk_ctgr,
											c.n_atk,
											c.n_atk_satuan,
											c.v_atk_hrgsatuan,
											c.n_atk_tipe
											 from e_ast_barang_atk_tr c
											 where not exists(select b.c_atk from e_ast_terima_atk_tm A, e_ast_terima_itematk_tm B, e_ast_distribusi_atk_tm d, e_ast_distribusi_itematk_tm e
											where A.i_atk_terima = B.i_atk_terima and b.c_atk=c.c_atk 
											and e.c_atk= c.c_atk
											and A.c_atk_setuju = ?
											and A.d_atk_beli > ? and A.d_atk_beli < ?
											and d.d_atk_kirim > ? and d.d_atk_kirim < ?) ',$where);
				
				$jmlResult = count($result);
				
				for ($j = 0; $j < $jmlResult; $j++) {
					$kode= (string)$result[$j]->c_atk;
					
					$PengadaanSebelum 	= $db->fetchCol('SELECT sum(q_atk_realterima) FROM e_ast_terima_atk_tm A, e_ast_terima_itematk_tm B
											where A.i_atk_terima = B.i_atk_terima 
											and c_atk = ? ',$resultAwal[$j]->c_atk);
											
					$PemakaianSebelum 	= $db->fetchCol('SELECT sum(q_atk_kirim) FROM e_ast_distribusi_atk_tm A, e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim 
											and c_atk = ? ',$resultAwal2[$j]->c_atk);
					
					$Pengadaan		 	= $db->fetchCol('SELECT sum(q_atk_realterima) FROM e_ast_terima_atk_tm A, e_ast_terima_itematk_tm B
											where A.i_atk_terima = B.i_atk_terima 
											and c_atk = ? ',$result[$j]->c_atk);
					
					$Menteri 			= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and A.i_orgb like 'MN0000%' and c_atk=?",$result[$j]->c_atk);	
					
					$jmlSekMen 			= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and A.i_orgb like 'SK%' and c_atk=?",$result[$j]->c_atk);	
					
					$jmlD1	 			= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and A.i_orgb like 'DP1%' and c_atk=?",$result[$j]->c_atk);
					
					$jmlD2	 			= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and A.i_orgb like 'DP2%' and c_atk=?",$result[$j]->c_atk);
					
					$jmlD3	 			= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and A.i_orgb like 'DP3%' and c_atk=?",$result[$j]->c_atk);
											
					$jmlD4	 			= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and A.i_orgb like 'DP4%' and c_atk=?",$result[$j]->c_atk);
					
					$jmlD5	 			= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and A.i_orgb like 'DP5%' and c_atk=?",$result[$j]->c_atk);
											
					$jmlD6	 			= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and A.i_orgb like 'DP6%' and c_atk=?",$result[$j]->c_atk);

					$jmlS1	 			= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and A.i_orgb like 'SA1000%' and c_atk=?",$result[$j]->c_atk);

					$jmlS2	 			= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and A.i_orgb like 'SA2000%' and c_atk=?",$result[$j]->c_atk);

					$jmlS3	 			= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and A.i_orgb like 'SA3000%' and c_atk=?",$result[$j]->c_atk);

					$jmlS4	 			= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and A.i_orgb like 'SA4000%' and c_atk=?",$result[$j]->c_atk);
					
					$jmlS5	 			= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and A.i_orgb like 'SA5000%' and c_atk=?",$result[$j]->c_atk);
					
					$jmlIP	 			= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and A.i_orgb like 'IP1000%' and c_atk=?",$result[$j]->c_atk);
											
					$JmlPengeluaran		= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and c_atk=?",$result[$j]->c_atk);
					
					
					//PersediaanAwal = PengadaanSebelum – PemakaianSebelum
					//$PersediaanAwal = ($PengadaanSebelum) - ($PemakaianSebelum);
					//PersediaanAkhir= PersediaanAwal + Pengadaan - Pemakaian
					//$PersediaanAkhir = $PersediaanAkhir + $Pengadaan - $Pemakaian;
					//jumlahKol6 = PersediaanAwal + Pengadaan
					
					$hasilAkhir[$j] = array("c_atk"            	=>(string)$result[$j]->c_atk,
										   "c_atk_ctgr"       	=>(string)$result[$j]->c_atk_ctgr,
										   "n_atk"            	=>(string)$result[$j]->n_atk,
										   "n_atk_satuan"     	=>(string)$result[$j]->n_atk_satuan,
										   "jumlah"   			=>$c_atk[0],
										   "jumlah2"          	=>(string)$result[$j]->jumlah2,
										   "mutasi2"         	=>(string)$result[$j]->mutasi2,
										   "mutasi1"         	=>(string)$result[$j]->mutasi1,
										   "v_atk_hrgsatuan"    =>(string)$result[$j]->v_atk_hrgsatuan,
										   "d_atk_kirim"        =>(string)$result[$j]->d_atk_kirim,
										   "q_atk_stock"        =>(string)$result[$j]->q_atk_stock,
										   "Pengadaan"   		=>$Pengadaan[0],
										   "nilai"            	=>($PengadaanSebelum[0] - $PemakaianSebelum[0]) * (string)$result[$j]->v_atk_hrgsatuan,
										   "Pemakaian"   		=>$Pemakaian[0],
										   "PersediaanAwal"   	=>$PengadaanSebelum[0] - $PemakaianSebelum[0],
										   "PersediaanAkhir"   	=>($PengadaanSebelum[0] - $PemakaianSebelum[0])+$Pengadaan[0] - $Pemakaian[0],
										   "nilai2"            	=>(($PengadaanSebelum[0] - $PemakaianSebelum[0])+$Pengadaan[0] - $Pemakaian[0]) * (string)$result[$j]->v_atk_hrgsatuan,
										   "jumlahKol6"         =>($PengadaanSebelum[0] - $PemakaianSebelum[0])+$Pengadaan[0],
										   "n_atk_tipe"			=>(string)$result[$j]->n_atk_tipe,
										   "Menteri"   			=>$Menteri[0],
										   "jmlSekMen"			=>$jmlSekMen[0],
										   "jmlD1"				=>$jmlD1[0],
										   "jmlD2"				=>$jmlD2[0],
										   "jmlD3"				=>$jmlD3[0],
										   "jmlD4"				=>$jmlD4[0],
										   "jmlD5"				=>$jmlD5[0],
										   "jmlD6"				=>$jmlD6[0],
										   "jmlS1"				=>$jmlS1[0],
										   "jmlS2"				=>$jmlS2[0],
										   "jmlS3"				=>$jmlS3[0],
										   "jmlS4"				=>$jmlS4[0],
										   "jmlS5"				=>$jmlS5[0],
										   "jmlIP"				=>$jmlIP[0],
										   "JmlPengeluaran"		=>$JmlPengeluaran[0]);
		 }	
		//}
	     return $hasilAkhir;
		
		}catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getLaporanPengadaanTU($tglAwal,$tglAkhir,$unitkrj) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
			$whereA[] 	= 'Y';
			$whereA[] 	= $tglAwal;
			$whereA[] 	= $unitkrj;
			$whereB[] 	= $tglAwal;
			$whereB[] 	= $unitkrj;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 
				$resultAwal = $db->fetchAll('select distinct c.c_atk,c.c_atk_ctgr,n_atk,n_atk_satuan,c.v_atk_hrgsatuan
										    from e_ast_terima_atk_tm A, e_ast_terima_itematk_tm B, e_ast_barang_atk_tr c
											where A.i_atk_terima = B.i_atk_terima and b.c_atk=c.c_atk
											and A.c_atk_setuju = ? 
											and A.d_atk_beli < ? and a.i_orgb =? ',$whereA);
				
				$resultAwal2 = $db->fetchAll('select distinct c.c_atk,c.c_atk_ctgr,n_atk,n_atk_satuan,c.v_atk_hrgsatuan
										    from e_ast_distribusi_atk_tm A, e_ast_distribusi_itematk_tm B, e_ast_barang_atk_tr c
											where A.i_atk_kirim = B.i_atk_kirim and b.c_atk=c.c_atk
											and A.d_atk_kirim < ? and a.i_orgb =? ',$whereB);				
				
				$where[] 	= 'Y';
				$where[] 	= $tglAwal;
				$where[] 	= $tglAkhir;
				$where[] 	= $tglAwal;
				$where[] 	= $tglAkhir;
				$where[] 	= $unitkrj;
				$where[] 	= 'Y';
				$where[] 	= $tglAwal;
				$where[] 	= $tglAkhir;
				$where[] 	= $tglAwal;
				$where[] 	= $tglAkhir;
				$where[] 	= $unitkrj;
								
				$result = $db->fetchAll('select distinct c.c_atk,c.c_atk_ctgr,n_atk,n_atk_satuan,c.v_atk_hrgsatuan,n_atk_tipe
											from e_ast_terima_atk_tm A, e_ast_terima_itematk_tm B, e_ast_barang_atk_tr c,
											e_ast_distribusi_atk_tm d, e_ast_distribusi_itematk_tm e
											where A.i_atk_terima = B.i_atk_terima and b.c_atk=c.c_atk 
											and e.c_atk= c.c_atk
											and A.c_atk_setuju = ?
											and A.d_atk_beli > ? and A.d_atk_beli < ?
											and d.d_atk_kirim > ? and d.d_atk_kirim < ? and a.i_orgb =?
											UNION
											SELECT
											c.c_atk,
											c.c_atk_ctgr,
											c.n_atk,
											c.n_atk_satuan,
											c.v_atk_hrgsatuan,
											c.n_atk_tipe
											 from e_ast_barang_atk_tr c
											 where not exists(select b.c_atk from e_ast_terima_atk_tm A, e_ast_terima_itematk_tm B, e_ast_distribusi_atk_tm d, e_ast_distribusi_itematk_tm e
											where A.i_atk_terima = B.i_atk_terima and b.c_atk=c.c_atk 
											and e.c_atk= c.c_atk
											and A.c_atk_setuju = ?
											and A.d_atk_beli > ? and A.d_atk_beli < ?
											and d.d_atk_kirim > ? and d.d_atk_kirim < ? and a.i_orgb =?) ',$where);
				
				$jmlResult = count($result);
				
				for ($j = 0; $j < $jmlResult; $j++) {
					$kode= (string)$result[$j]->c_atk;
					
					$PengadaanSebelum 	= $db->fetchCol('SELECT sum(q_atk_realterima) FROM e_ast_terima_atk_tm A, e_ast_terima_itematk_tm B
											where A.i_atk_terima = B.i_atk_terima 
											and c_atk = ? ',$resultAwal[$j]->c_atk);
											
					$PemakaianSebelum 	= $db->fetchCol('SELECT sum(q_atk_kirim) FROM e_ast_distribusi_atk_tm A, e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim 
											and c_atk = ? ',$resultAwal2[$j]->c_atk);
					
					$Pengadaan		 	= $db->fetchCol('SELECT sum(q_atk_realterima) FROM e_ast_terima_atk_tm A, e_ast_terima_itematk_tm B
											where A.i_atk_terima = B.i_atk_terima 
											and c_atk = ? ',$result[$j]->c_atk);
					
					$Menteri 			= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and A.i_orgb like 'MN0000%' and c_atk=?",$result[$j]->c_atk);	
					
					$jmlSekMen 			= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and A.i_orgb like 'SK%' and c_atk=?",$result[$j]->c_atk);	
					
					$jmlD1	 			= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and A.i_orgb like 'DP1%' and c_atk=?",$result[$j]->c_atk);
					
					$jmlD2	 			= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and A.i_orgb like 'DP2%' and c_atk=?",$result[$j]->c_atk);
					
					$jmlD3	 			= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and A.i_orgb like 'DP3%' and c_atk=?",$result[$j]->c_atk);
											
					$jmlD4	 			= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and A.i_orgb like 'DP4%' and c_atk=?",$result[$j]->c_atk);
					
					$jmlD5	 			= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and A.i_orgb like 'DP5%' and c_atk=?",$result[$j]->c_atk);
											
					$jmlD6	 			= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and A.i_orgb like 'DP6%' and c_atk=?",$result[$j]->c_atk);

					$jmlS1	 			= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and A.i_orgb like 'SA1000%' and c_atk=?",$result[$j]->c_atk);

					$jmlS2	 			= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and A.i_orgb like 'SA2000%' and c_atk=?",$result[$j]->c_atk);

					$jmlS3	 			= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and A.i_orgb like 'SA3000%' and c_atk=?",$result[$j]->c_atk);

					$jmlS4	 			= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and A.i_orgb like 'SA4000%' and c_atk=?",$result[$j]->c_atk);
					
					$jmlS5	 			= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and A.i_orgb like 'SA5000%' and c_atk=?",$result[$j]->c_atk);
					
					$jmlIP	 			= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and A.i_orgb like 'IP1000%' and c_atk=?",$result[$j]->c_atk);
											
					$JmlPengeluaran		= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and c_atk=?",$result[$j]->c_atk);
					
					
					//PersediaanAwal = PengadaanSebelum – PemakaianSebelum
					//$PersediaanAwal = ($PengadaanSebelum) - ($PemakaianSebelum);
					//PersediaanAkhir= PersediaanAwal + Pengadaan - Pemakaian
					//$PersediaanAkhir = $PersediaanAkhir + $Pengadaan - $Pemakaian;
					//jumlahKol6 = PersediaanAwal + Pengadaan
					
					$hasilAkhir[$j] = array("c_atk"            	=>(string)$result[$j]->c_atk,
										   "c_atk_ctgr"       	=>(string)$result[$j]->c_atk_ctgr,
										   "n_atk"            	=>(string)$result[$j]->n_atk,
										   "n_atk_satuan"     	=>(string)$result[$j]->n_atk_satuan,
										   "jumlah"   			=>$c_atk[0],
										   "jumlah2"          	=>(string)$result[$j]->jumlah2,
										   "mutasi2"         	=>(string)$result[$j]->mutasi2,
										   "mutasi1"         	=>(string)$result[$j]->mutasi1,
										   "v_atk_hrgsatuan"    =>(string)$result[$j]->v_atk_hrgsatuan,
										   "d_atk_kirim"        =>(string)$result[$j]->d_atk_kirim,
										   "q_atk_stock"        =>(string)$result[$j]->q_atk_stock,
										   "Pengadaan"   		=>$Pengadaan[0],
										   "nilai"            	=>($PengadaanSebelum[0] - $PemakaianSebelum[0]) * (string)$result[$j]->v_atk_hrgsatuan,
										   "Pemakaian"   		=>$Pemakaian[0],
										   "PersediaanAwal"   	=>$PengadaanSebelum[0] - $PemakaianSebelum[0],
										   "PersediaanAkhir"   	=>($PengadaanSebelum[0] - $PemakaianSebelum[0])+$Pengadaan[0] - $Pemakaian[0],
										   "nilai2"            	=>(($PengadaanSebelum[0] - $PemakaianSebelum[0])+$Pengadaan[0] - $Pemakaian[0]) * (string)$result[$j]->v_atk_hrgsatuan,
										   "jumlahKol6"         =>($PengadaanSebelum[0] - $PemakaianSebelum[0])+$Pengadaan[0],
										   "n_atk_tipe"			=>(string)$result[$j]->n_atk_tipe,
										   "Menteri"   			=>$Menteri[0],
										   "jmlSekMen"			=>$jmlSekMen[0],
										   "jmlD1"				=>$jmlD1[0],
										   "jmlD2"				=>$jmlD2[0],
										   "jmlD3"				=>$jmlD3[0],
										   "jmlD4"				=>$jmlD4[0],
										   "jmlD5"				=>$jmlD5[0],
										   "jmlD6"				=>$jmlD6[0],
										   "jmlS1"				=>$jmlS1[0],
										   "jmlS2"				=>$jmlS2[0],
										   "jmlS3"				=>$jmlS3[0],
										   "jmlS4"				=>$jmlS4[0],
										   "jmlS5"				=>$jmlS5[0],
										   "jmlIP"				=>$jmlIP[0],
										   "JmlPengeluaran"		=>$JmlPengeluaran[0]);
		 }	
		//}
	     return $hasilAkhir;
		
		}catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getLaporanPengadaanNB($tglAwal,$tglAkhir,$kdBarang) {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
			$whereA[] 	= 'Y';
			$whereA[] 	= $tglAwal;
			$whereA[] 	= $kdBarang;
			$whereB[] 	= $tglAwal;
			$whereB[] 	= $kdBarang;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 
				$resultAwal = $db->fetchAll('select distinct c.c_atk,c.c_atk_ctgr,n_atk,n_atk_satuan,c.v_atk_hrgsatuan
										    from e_ast_terima_atk_tm A, e_ast_terima_itematk_tm B, e_ast_barang_atk_tr c
											where A.i_atk_terima = B.i_atk_terima and b.c_atk=c.c_atk
											and A.c_atk_setuju = ? 
											and A.d_atk_beli < ? and c.c_atk =? ',$whereA);
				
				$resultAwal2 = $db->fetchAll('select distinct c.c_atk,c.c_atk_ctgr,n_atk,n_atk_satuan,c.v_atk_hrgsatuan
										    from e_ast_distribusi_atk_tm A, e_ast_distribusi_itematk_tm B, e_ast_barang_atk_tr c
											where A.i_atk_kirim = B.i_atk_kirim and b.c_atk=c.c_atk
											and A.d_atk_kirim < ? and c.c_atk =? ',$whereB);				
				
				$where[] 	= 'Y';
				$where[] 	= $tglAwal;
				$where[] 	= $tglAkhir;
				$where[] 	= $tglAwal;
				$where[] 	= $tglAkhir;
				$where[] 	= $kdBarang;
				
								
				$result = $db->fetchAll('select distinct a.i_orgb,c.c_atk,c.c_atk_ctgr,n_atk,n_atk_satuan,c.v_atk_hrgsatuan,n_atk_tipe
											from e_ast_terima_atk_tm A, e_ast_terima_itematk_tm B, e_ast_barang_atk_tr c,
											e_ast_distribusi_atk_tm d, e_ast_distribusi_itematk_tm e
											where A.i_atk_terima = B.i_atk_terima and b.c_atk=c.c_atk 
											and e.c_atk= c.c_atk
											and A.c_atk_setuju = ?
											and A.d_atk_beli > ? and A.d_atk_beli < ?
											and d.d_atk_kirim > ? and d.d_atk_kirim < ? and c.c_atk =?',$where);
				
				$jmlResult = count($result);
				
				for ($j = 0; $j < $jmlResult; $j++) {
					$kode= (string)$result[$j]->c_atk;
					
					$PengadaanSebelum 	= $db->fetchCol('SELECT sum(q_atk_realterima) FROM e_ast_terima_atk_tm A, e_ast_terima_itematk_tm B
											where A.i_atk_terima = B.i_atk_terima 
											and i_orgb = ? ',$resultAwal[$j]->i_orgb);
											
					$PemakaianSebelum 	= $db->fetchCol('SELECT sum(q_atk_kirim) FROM e_ast_distribusi_atk_tm A, e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim 
											and i_orgb = ? ',$resultAwal2[$j]->i_orgb);
					
					$Pengadaan		 	= $db->fetchCol('SELECT sum(q_atk_realterima) FROM e_ast_terima_atk_tm A, e_ast_terima_itematk_tm B
											where A.i_atk_terima = B.i_atk_terima 
											and i_orgb = ? ',$result[$j]->i_orgb);
					
					
					$JmlPengeluaran		= $db->fetchCol("select  sum(B.q_atk_kirim) 
											from  e_ast_distribusi_atk_tm A,
											e_ast_distribusi_itematk_tm B
											where A.i_atk_kirim = B.i_atk_kirim
											and i_orgb=?",$result[$j]->i_orgb);
					
					
					//PersediaanAwal = PengadaanSebelum – PemakaianSebelum
					//$PersediaanAwal = ($PengadaanSebelum) - ($PemakaianSebelum);
					//PersediaanAkhir= PersediaanAwal + Pengadaan - Pemakaian
					//$PersediaanAkhir = $PersediaanAkhir + $Pengadaan - $Pemakaian;
					//jumlahKol6 = PersediaanAwal + Pengadaan
					
					$hasilAkhir[$j] = array("i_orgb"            =>(string)$result[$j]->i_orgb,
											"c_atk"            	=>(string)$result[$j]->c_atk,
											"c_atk_ctgr"       	=>(string)$result[$j]->c_atk_ctgr,
											"n_atk"            	=>(string)$result[$j]->n_atk,
											"n_atk_satuan"     	=>(string)$result[$j]->n_atk_satuan,
											"jumlah"   			=>$c_atk[0],
											"jumlah2"          	=>(string)$result[$j]->jumlah2,
											"mutasi2"         	=>(string)$result[$j]->mutasi2,
											"mutasi1"         	=>(string)$result[$j]->mutasi1,
											"v_atk_hrgsatuan"   =>(string)$result[$j]->v_atk_hrgsatuan,
											"d_atk_kirim"       =>(string)$result[$j]->d_atk_kirim,
											"q_atk_stock"       =>(string)$result[$j]->q_atk_stock,
											"Pengadaan"   		=>$Pengadaan[0],
											"nilai"            	=>($PengadaanSebelum[0] - $PemakaianSebelum[0]) * (string)$result[$j]->v_atk_hrgsatuan,
											"Pemakaian"   		=>$Pemakaian[0],
											"PersediaanAwal"   	=>$PengadaanSebelum[0] - $PemakaianSebelum[0],
											"PersediaanAkhir"   =>($PengadaanSebelum[0] - $PemakaianSebelum[0])+$Pengadaan[0] - $Pemakaian[0],
											"nilai2"            =>(($PengadaanSebelum[0] - $PemakaianSebelum[0])+$Pengadaan[0] - $Pemakaian[0]) * (string)$result[$j]->v_atk_hrgsatuan,
											"jumlahKol6"        =>($PengadaanSebelum[0] - $PemakaianSebelum[0])+$Pengadaan[0],
											"n_atk_tipe"		=>(string)$result[$j]->n_atk_tipe,
											"JmlPengeluaran"	=>$JmlPengeluaran[0]);
		 }	
		//}
	     return $hasilAkhir;
		
		}catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	//===========================================================================================================================
	public function getLaporanPengadaan2($tglAwal,$tglAkhir) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
	     $where[] = $tglAwal;
		 $where[] = $tglAwal;
		 $where[] = $tglAkhir;
		 $where[] = $tglAkhir;
		 $where[] = $tglAwal;
		 $where[] = $tglAwal;
		 $where[] = $tglAkhir;
		 $where[] = $tglAkhir;
		 $where[] = $tglAwal;
		 $where[] = $tglAwal;
		 $where[] = $tglAkhir;
		 $where[] = $tglAkhir;
		 
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
		 $result = $db->fetchAll('select a.c_atk,b.n_atk,a.c_atk_ctgr,n_atk_satuan, q_atk_stock,v_atk_hrgsatuan,
									sum(CASE WHEN d_atk_ajuan < ? and d_atk_kirim < ?  THEN  (cast(q_atk_setujubeli as float)- cast(q_atk_kirim as float)) + cast(q_atk_stock as float)  END) as jumlah,
									sum(CASE WHEN d_atk_ajuan < ? and d_atk_kirim < ?  THEN (cast(q_atk_setujubeli as float) - cast(q_atk_kirim as float)) + cast(q_atk_stock as float) END) as jumlah2,
									sum(CASE WHEN d_atk_ajuan < ? and d_atk_kirim < ?  THEN ((cast(q_atk_setujubeli as float) - cast(q_atk_kirim as float)) + cast(q_atk_stock as float)) * v_atk_hrgsatuan END) as nilai,
									sum(CASE WHEN d_atk_ajuan < ? and d_atk_kirim < ?  THEN ((cast(q_atk_setujubeli as float) - cast(q_atk_kirim as float)) + cast(q_atk_stock as float)) * v_atk_hrgsatuan END) as nilai2,
									sum(CASE WHEN d_atk_ajuan < ? and d_atk_kirim < ?  THEN cast(q_atk_setujubeli as float) END) as mutasi1,
									sum(CASE WHEN d_atk_ajuan < ? and d_atk_kirim < ?  THEN cast(q_atk_kirim as float) END) as mutasi2
					                from(select c.c_atk,c.c_atk_ctgr,q_atk_kirim ,d_atk_kirim,q_atk_setujubeli,d_atk_ajuan   
					                       from e_ast_distribusi_atk_tm b,e_ast_distribusi_itematk_tm c, 
					                       e_ast_ajuanbeli_atk_tm  d,e_ast_ajuanbeli_itematk_tm e
					                       where b.i_atk_kirim =c.i_atk_kirim and d.i_atk_ajuan =e.i_atk_ajuan  
					                             and c.c_atk=e.c_atk) a, 
                			        e_ast_barang_atk_tr  b	where   a.c_atk=b.c_atk  and a.c_atk_ctgr=b.c_atk_ctgr	
									group by a.c_atk,b.n_atk,a.c_atk_ctgr,n_atk_satuan,q_atk_stock,v_atk_hrgsatuan order by b.n_atk',$where);
         
		 $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		   $hasilAkhir[$j] = array("c_atk"            	=>(string)$result[$j]->c_atk,
								   "c_atk_ctgr"       	=>(string)$result[$j]->c_atk_ctgr,
								   "n_atk"            	=>(string)$result[$j]->n_atk,
								   "n_atk_satuan"     	=>(string)$result[$j]->n_atk_satuan,
								   "jumlah"           	=>(string)$result[$j]->jumlah,
								   "jumlah2"          	=>(string)$result[$j]->jumlah2,
								   "nilai"            	=>(string)$result[$j]->nilai,
								   "nilai2"          	=>(string)$result[$j]->nilai2,
	                               "mutasi1"         	=>(string)$result[$j]->mutasi1,
								   "mutasi2"         	=>(string)$result[$j]->mutasi2,
								   "v_atk_hrgsatuan"    =>(string)$result[$j]->v_atk_hrgsatuan,
								   "d_atk_kirim"        =>(string)$result[$j]->d_atk_kirim,
								   "q_atk_stock"        =>(string)$result[$j]->q_atk_stock);
								  
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getRefAtkList($pageNumber,$itemPerPage,$namaBarang) {
		$namaBarang = strtoupper($namaBarang);
		$nbrg = '%'.$namaBarang.'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $nbrg;
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			 {
											 
				$result = $db->fetchAll("SELECT a.c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stock 
										FROM e_ast_barang_atk_tr a where n_atk like ?
										ORDER BY c_atk",$where); 
			 
			 
				$jmlResult  = count($result);
				$hasilAkhir = $jmlResult;
			 }
			else
			 {
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT a.c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stock 
										FROM e_ast_barang_atk_tr a where n_atk like ?
										ORDER BY c_atk
										limit $xLimit offset $xOffset",$where); 
			 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("c_atk"         		=>(string)$result[$j]->c_atk,
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
	//=================================== 15 Nop 07============================================================================= 
	//ini utk list combo unit all===============
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
	//ini tuk unit TU dr SK1404-1405 ----------------------------------------------------------------------------------------------------------------------------------------------------
	public function getUnitKerjaTUList() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchAll("SELECT i_orgb,n_orgb FROM e_org_0_0_tm 
									WHERE i_orgb in('SK1404','SK1405','SK1406','SK1407','SK1408',
									'SK1409','SK1410','SK1411','SK1412')
									ORDER BY i_orgb");
	     return $result;
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
	public function getUnitKerjaList2() {
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
	//==========================================================unit kerja TU ============================================================
	public function getUnitKerja($pageNumber,$itemPerPage,$unitkr,$namaUnit) {
		$namaUnit = strtoupper($namaUnit);
		$nunit = '%'.$namaUnit.'%';
	   
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			
			 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 //
				/*$orgTU = $db->fetchAll("select i_orgb as TU, i_orgb_tu as unitTU 	from e_org_0_0_tm 
										where i_orgb = 'SK1405' and i_orgb_tu is not null
											ORDER BY i_orgb"); 
				 
				$jmlOrgTU = count($orgTU);*/
				
				$TU 	= $db->fetchCol("SELECT i_orgb_tu FROM e_org_0_0_tm where i_orgb = ? and i_orgb_tu is not null",$unitkr);
				$unitTU = $TU[0];
				
				if($unitTU !=''){
					if (substr($unitTU,0,2) == 'DP' ){
			               $unitTULike = substr($unitTU,0,3).'%';
			        }else{
			               $unitTULike = substr($unitTU,0,2).'%';
			        }

					 $where[] = $unitTULike;
					 $where[] = $nunit;
					 
					 if(($pageNumber==0) && ($itemPerPage==0))
					 {
						$hasilAkhir = $db->fetchOne("select count(*) from e_org_0_0_tm 
														where i_orgb like ? and upper(n_orgb) like ?", $where);
					 }
					 else
					 {
				
						 $xLimit=$itemPerPage;
						 $xOffset=($pageNumber-1)*$itemPerPage;		
						 
						 $result = $db->fetchAll("SELECT i_orgb,n_orgb FROM e_org_0_0_tm 
													where i_orgb like ? and upper(n_orgb) like ? 
													ORDER BY i_orgb
													limit $xLimit offset $xOffset", $where); 
						 
						 $jmlResult = count($result);
						 
						 for ($j = 0; $j < $jmlResult; $j++) {
								$hasilAkhir[$j] = array("i_orgb"           =>(string)$result[$j]->i_orgb,
														"n_orgb"           =>(string)$result[$j]->n_orgb);
					 }	
					}	 
					return $hasilAkhir;
			}else{
				return 0;
			}
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	
	public function getUnitKerja2($pageNumber,$itemPerPage,$namaUnit) {
		$namaUnit = strtoupper($namaUnit);
		$nunit = '%'.$namaUnit.'%';
	   
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $nunit;
			 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$hasilAkhir = $db->fetchOne("select count(*) from e_org_0_0_tm 
												where i_orgb like 'DP1%' and upper(n_orgb) like ?", $where);
			 }
			 else
			 {
			
				 $xLimit=$itemPerPage;
				 $xOffset=($pageNumber-1)*$itemPerPage;		
				 
				 $result = $db->fetchAll("SELECT i_orgb,n_orgb FROM e_org_0_0_tm 
											where i_orgb like 'DP1%' and upper(n_orgb) like ? 
											ORDER BY i_orgb
											limit $xLimit offset $xOffset", $where); 
				 
				 $jmlResult = count($result);
				 
				 for ($j = 0; $j < $jmlResult; $j++) {
						$hasilAkhir[$j] = array("i_orgb"           =>(string)$result[$j]->i_orgb,
												"n_orgb"           =>(string)$result[$j]->n_orgb);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	
	
	public function getPejabat($unitjabatan) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
			$where[]=$unitjabatan;
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			 
				// $result = $db->fetchAll("select B.i_peg_nip, n_peg, A.n_jabatan, b.c_unit_kerja, b.i_orgb  
										// from sdm.tm_jabatan A,  sdm.tm_pegawai B
										// where a.i_peg_nip = b.i_peg_nip
										// and b.c_unit_kerja = ?
										// and b.c_unit_kerja = a.c_jabatan",$where);
				 $result = $db->fetchAll("select B.i_peg_nip, n_peg, A.n_jabatan, b.c_lokasi_unitkerja
										from sdm.tr_jabatan A,  sdm.tm_pegawai B
										where a.c_jabatan = b.c_jabatan
										and b.i_peg_nip = ?",$where);
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
			 
					$hasilAkhir[$j] = array("i_peg_nip"		=>(string)$result[$j]->i_peg_nip,
											"n_peg"   		=>(string)$result[$j]->n_peg, 
											"n_jabatan"     =>(string)$result[$j]->n_jabatan, 
											"n_peg"         =>(string)$result[$j]->n_peg,
											"c_unit_kerja"  =>(string)$result[$j]->c_lokasi_unitkerja);
											//"i_orgb"    	=>(string)$result[$j]->i_orgb);
											
				}
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	////=================================================19 nop 07===============================================================
	
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
	public function getLaporanBarangRuangan($tahunanggaran){
	$registry = Zend_Registry::getInstance();
	  $db=$registry->get('db');
	  try{
	    $db->setFetchMode(Zend_Db::FETCH_OBJ);
         // $result = $db->fetchAll("SELECT a.kd_brg, c.ur_sskel, a.no_aset, b.kd_ruang, b.ur_ruang
								// FROM aset.tm_dir a, aset.tm_ruang b, aset.tm_sskel c,aset.tm_masterhm d
								// WHERE b.kd_lokasi = a.kd_lokasi 
								// and a.kd_brg=c.kd_brg
								// and a.kd_brg = d.kd_brg
								// and c.kd_brg=d.kd_brg
								// and d.thn_ang='$tahunanggaran'
								// ORDER BY a.kd_brg");		
        $result = $db->fetchAll("SELECT kd_brg, ur_sskel, no_aset, kd_ruang, ur_ruang,rph_aset
								FROM aset.v_barangruang
                                where thn_ang='$tahunanggaran'								
								ORDER BY kd_brg");
        $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("kd_brg"           =>(string)$result[$j]->kd_brg,
		                           "ur_sskel"           =>(string)$result[$j]->ur_sskel,		                           
		                           "no_aset"           =>(string)$result[$j]->no_aset,
		                           "kd_ruang"           =>(string)$result[$j]->kd_ruang,
		                           "rph_aset"           =>(string)$result[$j]->rph_aset,
		                           "ur_ruang"           =>(string)$result[$j]->ur_ruang);
								  
							       
		
		 }					 
	     return $hasilAkhir;         								
	  }catch(Exception $e){
	     echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	  }
	}
    public function getLaporanBMNTahun($tahundari,$tahunsampai){
	  $registry = Zend_Registry::getInstance();
	  $db=$registry->get('db');
	  try{
	    $db->setFetchMode(Zend_Db::FETCH_OBJ); 			 				
		$where[] 	= $tglAkhir;
        $result = $db->fetchAll("SELECT kd_brg, merk_type, keterangan, no_aset, tgl_perlh, 
		                        asal_perlh, rph_aset, no_bukti, ur_sskel
								FROM aset.v_masteru
								where thn_ang between '".$tahundari."' and '".$tahunsampai."'
								ORDER BY kd_brg");
        $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("kd_brg"           =>(string)$result[$j]->kd_brg,
		                           "merk_type"           =>(string)$result[$j]->merk_type,
		                           "keterangan"           =>(string)$result[$j]->keterangan,
		                           "no_aset"           =>(string)$result[$j]->no_aset,
		                           "tgl_perlh"           =>(string)$result[$j]->tgl_perlh,
		                           "asal_perlh"           =>(string)$result[$j]->asal_perlh,
		                           "rph_aset"           =>(string)$result[$j]->rph_aset,
								   "no_bukti"          =>(string)$result[$j]->no_bukti,
								   "ur_sskel"          =>(string)$result[$j]->ur_sskel);
								  
							       
		
		 }					 
	     return $hasilAkhir;         								
	  }catch(Exception $e){
	     echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	  }
	}
	public function getKodebarangList($tahundari,$tahunsampai){
	  $registry = Zend_Registry::getInstance();
	  $db=$registry->get('db');
	  try{
	    $db->setFetchMode(Zend_Db::FETCH_OBJ); 			 				
		$where[] 	= $tglAkhir;
        $result = $db->fetchAll("SELECT distinct(kd_brg),ur_sskel
								FROM aset.v_masteru
								where thn_ang between '".$tahundari."' and '".$tahunsampai."'
								ORDER BY kd_brg");
        $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("kd_brg"           =>(string)$result[$j]->kd_brg,
		                           "ur_sskel"           =>(string)$result[$j]->ur_sskel);
								  
							       
		
		 }					 
	     return $hasilAkhir;         								
	  }catch(Exception $e){
	     echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	  }
	}
	
	
//=================================== 15 Nop 07=============================================================================
	
}		
?>