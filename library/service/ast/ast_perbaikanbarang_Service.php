<?php
class ast_perbaikanbarang_Service {
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
	
	 public function findUnitKerja($unitkr) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = "c_satker = '".$data['unitkr']."'";
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT c_lokasi_unitkerja,n_unitkerja FROM sdm.tr_unitkerja where c_satker=?',$unitkr);
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb"  =>(string)$result[$j]->c_lokasi_unitkerja,
								   "n_orgb"  =>(string)$result[$j]->n_unitkerja);
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getSatker($nip) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT a.c_satker,a.n_unitkerja 
		                          FROM sdm.tr_unitkerja a,sdm.tm_pegawai b
								  where
								  b.i_peg_nip='$nip'
								  and b.c_lokasi_unitkerja=a.c_lokasi_unitkerja
								  and b.c_eselon_i=a.c_eselon_i
								  and b.c_eselon_ii=a.c_eselon_ii
								  and b.c_eselon_iii=a.c_eselon_iii
								  and b.c_eselon_iv=a.c_eselon_iv
								  and b.c_eselon_v=a.c_eselon_v"
								  );
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_satker"  =>(string)$result[$j]->c_satker,
		                           "n_unitkerja"=>(string)$result[$j]->n_unitkerja);
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
// cari master
public function getAstPerbaikanBrgByOrg($Orgb,$pageNumber, $itemPerPage) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
		 if(($pageNumber==0) && ($itemPerPage==0))
			{
				$data =  $db->fetchOne("select count(*) 
				                        FROM aset.tm_ajuanperbaikan_0 
										where  i_orgb = '$Orgb' 
										and c_inv_statajuanperbaikan = 'A'" );
			}
			else
			{	
			 $xLimit=$itemPerPage;
	  		 $xOffset=($pageNumber-1)*$itemPerPage;
		 
	     $result = $db->fetchAll("select i_inv_ajuanperbaikan,to_char(d_inv_ajuanperbaikan,'dd-mm-yyyy') as d_inv_ajuanperbaikan,
								c_inv_statajuanperbaikan,c_setuju_statustu,to_char(d_setuju_tu,'dd-mm-yyyy') as d_setuju_tu ,
								i_orgb,c_setuju_statuskabag,d_setuju_kabag,i_entry,d_entry 
								from aset.tm_ajuanperbaikan_0 where  i_orgb = '$Orgb' and c_inv_statajuanperbaikan = 'A'
								order by d_inv_ajuanperbaikan desc limit $xLimit offset $xOffset" );
 
		//print_r 	($result);

		$jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++) {

           $data[$j] = array("i_inv_ajuanperbaikan" =>(string)$result[$j]->i_inv_ajuanperbaikan,
								"d_inv_ajuanperbaikan" =>(string)$result[$j]->d_inv_ajuanperbaikan,
								"c_inv_statajuanperbaikan" =>(string)$result[$j]->c_inv_statajuanperbaikan,
								"c_setuju_statustu" =>(string)$result[$j]->c_setuju_statustu,
								"d_setuju_tu" =>(string)$result[$j]->d_setuju_tu,
								"c_setuju_statuskabag" =>(string)$result[$j]->c_setuju_statuskabag,
								"d_setuju_kabag" =>(string)$result[$j]->d_setuju_kabag,
								"i_orgb" =>(string)$result[$j]->i_orgb,
								"i_entry" =>(string)$result[$j]->i_entry,
								"d_entry"	 =>(string)$result[$j]->d_entry);	
						 
		 }	
		}	
	     return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	} 


	
// cari item	
public function getAstPerbaikanBrgByKodeItem($iinvajuanperbaikan) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchAll("select i_inv_ajuanperbaikan,d_anggaran,
								a.c_barang,to_char(i_aset,'09999') as i_aset,
								to_char(d_perolehan,'dd-mm-yyyy') as d_perolehan,e_keterangan,
								to_char(d_perolehan,'yyyy-mm-dd') as d_oleh,
								c_barang_serah,c_inv_perbaikan,v_inv_biayaperbaikan,a.i_entry,a.d_entry,ur_sskel,
								i_aset as kode_aset			
								from  aset.tm_ajuanperbaikan_item a, aset.tm_sskel b
									where  substr(a.c_barang,1,1) = b.kd_gol
								           and substr(a.c_barang,2,2) = b.kd_bid 
									       and substr(a.c_barang,4,2) = b.kd_kel
									       and substr(a.c_barang,6,2) = b.kd_skel
									       and substr(a.c_barang,8,3) = b.kd_sskel 
										   and i_inv_ajuanperbaikan = '$iinvajuanperbaikan'");										   
								
 
		//print_r 	($result);

	     $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {		   
           $data[$j] = array("i_inv_ajuanperbaikan" =>(string)$result[$j]->i_inv_ajuanperbaikan,
								"d_anggaran" =>(string)$result[$j]->d_anggaran,
								"c_barang" =>(string)$result[$j]->c_barang,
								"d_perolehan" =>(string)$result[$j]->d_perolehan,
								"d_oleh" =>(string)$result[$j]->d_oleh,
								"e_keterangan" =>(string)$result[$j]->e_keterangan,
								"c_barang_serah" =>(string)$result[$j]->c_barang_serah,
								"c_inv_perbaikan" =>(string)$result[$j]->c_inv_perbaikan,
								"v_inv_biayaperbaikan" =>(string)$result[$j]->v_inv_biayaperbaikan,
								"ur_sskel" =>(string)$result[$j]->ur_sskel,
								"i_aset" =>(string)$result[$j]->i_aset,	
								"kode_aset" =>(string)$result[$j]->kode_aset,			
								"i_entry" =>(string)$result[$j]->i_entry,
								"d_entry"	 =>(string)$result[$j]->d_entry);	
						 
		 }		
      		 
	     return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	} 	
	

	
	
// cari item	
public function getAstPerbaikanBrgByKodeItemBiaya($iinvajuanperbaikan) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchAll("select a.i_inv_ajuanperbaikan,a.d_anggaran,
								a.c_barang,to_char(i_aset,'09999') as i_aset,to_char(d_perolehan,'yyyy-mm-dd') as d_perolehan,e_keterangan,
								c_barang_serah,c_inv_perbaikan,v_inv_biayaperbaikan,a.i_entry,a.d_entry,ur_sskel,c.d_inv_ajuanperbaikan,
								i_aset as kode_aset			
								from  aset.tm_ajuanperbaikan_item a, aset.tm_sskel b,aset.tm_ajuanperbaikan_0 c
								where  substr(a.c_barang,1,1) = b.kd_gol
								and substr(a.c_barang,2,2) = b.kd_bid 
								and substr(a.c_barang,4,2) = b.kd_kel
								and substr(a.c_barang,6,2) = b.kd_skel
								and substr(a.c_barang,8,3) = b.kd_sskel 
								and a.i_inv_ajuanperbaikan = '$iinvajuanperbaikan'
								and c.i_inv_ajuanperbaikan = a.i_inv_ajuanperbaikan
								and not exists (select * from  aset.tm_rincibiayaperbaikan c
								where c.i_inv_ajuanperbaikan = a.i_inv_ajuanperbaikan
								and c.c_barang = a.c_barang
								and c.d_anggaran = a.d_anggaran
								and c.i_aset = a.i_aset)");										   
								
 
		//print_r 	($result);

		$jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
		 
// biaya			
/*
			$biaya = $db->fetchCol('SELECT v_inv_biayaperbakian FROM e_ast_rincibiayaperbaik_0_tm WHERE c_barang = ?',$result[$j]->c_barang);
			
			if (isset($biaya[0])) {
				$biaya=$biaya[0];				
				}
			else {	$biaya="";}	

			
//keterangan			
			$keterangan = $db->fetchCol('SELECT e_inv_kegiatanperbakian FROM e_ast_rincibiayaperbaik_0_tm WHERE c_barang = ?',$result[$j]->c_barang);
			
			if (isset($keterangan[0])) {
				$keterangan=$keterangan[0];				
				}
			else {	$keterangan="";}	
**/						
			$keterangan="";
				$biaya="";
           $data[$j] = array("i_inv_ajuanperbaikan" =>(string)$result[$j]->i_inv_ajuanperbaikan,
								"d_anggaran" =>(string)$result[$j]->d_anggaran,
								"c_barang" =>(string)$result[$j]->c_barang,
								"d_perolehan" =>(string)$result[$j]->d_perolehan,
								"e_keterangan" =>$keterangan,
								"c_barang_serah" =>(string)$result[$j]->c_barang_serah,
								"c_inv_perbaikan" =>(string)$result[$j]->c_inv_perbaikan,
								"v_inv_biayaperbaikan" =>$biaya,								
								"ur_sskel" =>(string)$result[$j]->ur_sskel,
								"i_aset" =>(string)$result[$j]->i_aset,	
								"kode_aset" =>(string)$result[$j]->kode_aset,			
								"d_inv_ajuanperbaikan" =>(string)$result[$j]->d_inv_ajuanperbaikan,			
								"i_entry" =>(string)$result[$j]->i_entry,
								"d_entry"	 =>(string)$result[$j]->d_entry);	
						 
		 }					 
	     return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	} 		
	
	
public function getAstPerbaikanBrgByKodeItemEdit($iinvajuanperbaikan,$kodeasset) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchAll("select i_inv_ajuanperbaikan,d_anggaran,
								a.c_barang,to_char(i_aset,'09999') as i_aset,to_char(d_perolehan,'dd-mm-yyyy') as d_perolehan,e_keterangan,
								to_char(d_perolehan,'yyyy-mm-dd') as d_oleh,
								c_barang_serah,c_inv_perbaikan,v_inv_biayaperbaikan,a.i_entry,a.d_entry,ur_sskel,
								i_aset as kode_aset			
								from  aset.tm_ajuanperbaikan_item a, aset.tm_sskel b
									where  substr(a.c_barang,1,1) = b.kd_gol
								           and substr(a.c_barang,2,2) = b.kd_bid 
									       and substr(a.c_barang,4,2) = b.kd_kel
									       and substr(a.c_barang,6,2) = b.kd_skel
									       and substr(a.c_barang,8,3) = b.kd_sskel 
										   and i_inv_ajuanperbaikan = '$iinvajuanperbaikan'
										   and i_aset = '$kodeasset'");										   
								
 
		//print_r 	($result);

		$jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++) {

           $data[$j] = array("i_inv_ajuanperbaikan" =>(string)$result[$j]->i_inv_ajuanperbaikan,
								"d_anggaran" =>(string)$result[$j]->d_anggaran,
								"c_barang" =>(string)$result[$j]->c_barang,
								"d_perolehan" =>(string)$result[$j]->d_perolehan,
								"e_keterangan" =>(string)$result[$j]->e_keterangan,
								"c_barang_serah" =>(string)$result[$j]->c_barang_serah,
								"d_oleh" =>(string)$result[$j]->d_oleh,
								"c_inv_perbaikan" =>(string)$result[$j]->c_inv_perbaikan,
								"v_inv_biayaperbaikan" =>(string)$result[$j]->v_inv_biayaperbaikan,
								"ur_sskel" =>(string)$result[$j]->ur_sskel,
								"i_aset" =>(string)$result[$j]->i_aset,	
								"kode_aset" =>(string)$result[$j]->kode_aset,			
								"i_entry" =>(string)$result[$j]->i_entry,
								"d_entry"	 =>(string)$result[$j]->d_entry);	
						 
		 }					 
	     return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	} 	
	
// Masukan data Pengajuan	
	public function insertPBarang(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $insert_P_Barang = array(
								"i_inv_ajuanperbaikan"=>$data['i_inv_ajuanperbaikan'],
								"d_inv_ajuanperbaikan"=>$data['d_inv_ajuanperbaikan'],								
								"c_inv_statajuanperbaikan"=>$data['c_inv_statajuanperbaikan'],
								"i_orgb"=>$data['i_orgb'],
								"c_setuju_statustu"=>$data['c_setuju_statustu'],
								"d_setuju_tu"=>$data['d_setuju_tu'],
								"c_setuju_statuskabag"=>$data['c_setuju_statuskabag'],
								"d_setuju_kabag"=>$data['d_setuju_kabag'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));
			
	     $db->insert('aset.tm_ajuanperbaikan_0',$insert_P_Barang);
		 
		 $db->commit();
		 $_hasil = array("rcNumber"=>"1",
		                 "rcDesc"  =>"Proses Sukses");
	     return 'sukses ';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function insertPBarangItem(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $insert_P_Barang_Item = array(
								"i_inv_ajuanperbaikan"=>$data['i_inv_ajuanperbaikan'],
								"d_anggaran"=>$data['d_anggaran'],								
								"c_barang"=>$data['c_barang'],
								"i_aset"=>$data['i_aset'],
								"d_perolehan"=>$data['d_perolehan'],
								"e_keterangan"=>$data['e_keterangan'],
								"c_barang_serah"=>$data['c_barang_serah'],
								"c_inv_perbaikan"=>$data['c_inv_perbaikan'],
								"v_inv_biayaperbaikan"=>$data['v_inv_biayaperbaikan'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));
						
	     $db->insert('aset.tm_ajuanperbaikan_item',$insert_P_Barang_Item);
		 
		 $db->commit();
		 $_hasil = array("rcNumber"=>"1",
		                 "rcDesc"  =>"Proses Sukses");
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
// Masukan data Pengajuan
// Mengubah data Pengajuan
	public function updatePBarang(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $update_P_Barang = array(
								"i_inv_ajuanperbaikan"=>$data['i_inv_ajuanperbaikan'],
								"d_inv_ajuanperbaikan"=>$data['d_inv_ajuanperbaikan'],								
								"c_inv_statajuanperbaikan"=>$data['c_inv_statajuanperbaikan'],
								"i_orgb"=>$data['i_orgb'],
								"c_setuju_statustu"=>$data['c_setuju_statustu'],
								"d_setuju_tu"=>$data['d_setuju_tu'],
								"c_setuju_statuskabag"=>$data['c_setuju_statuskabag'],
								"d_setuju_kabag"=>$data['d_setuju_kabag'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));

		$where[] = "i_inv_ajuanperbaikan = '".$data['i_inv_ajuanperbaikan']."'";
	    $db->update('aset.tm_ajuanperbaikan_0',$update_P_Barang, $where);
		 
		 $db->commit();
		 $_hasil = array("rcNumber"=>"1",
		                 "rcDesc"  =>"Proses Sukses");
	     return 'sukses ';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function updatePBarangItem(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	    
	   try {
	     $db->beginTransaction();
	     $update_P_Barang_Item = array(
								"i_inv_ajuanperbaikan"=>$data['i_inv_ajuanperbaikan'],
								"d_anggaran"=>$data['d_anggaran'],								
								"c_barang"=>$data['c_barang'],
								"i_aset"=>$data['i_aset'],
								"d_perolehan"=>$data['d_perolehan'],
								"e_keterangan"=>$data['e_keterangan'],
								"c_barang_serah"=>$data['c_barang_serah'],
								"c_inv_perbaikan"=>$data['c_inv_perbaikan'],
								"v_inv_biayaperbaikan"=>$data['v_inv_biayaperbaikan'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));
		$where[] = "i_inv_ajuanperbaikan = '".$data['i_inv_ajuanperbaikan']."' and i_aset = '".$data['i_aset']."' and c_barang = '".$data['c_barang']."' and d_anggaran = '".$data['d_anggaran']."'";
	    $db->update('aset.tm_ajuanperbaikan_item',$update_P_Barang_Item, $where);
		
		
		$db->commit();
		$_hasil = array("rcNumber"=>"1",
		                 "rcDesc"  =>"Proses Sukses");
	     return 'sukses ';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	

	public function deletePBarang($kode, $iorg) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 $where[] = "i_inv_ajuanperbaikan = '".$kode."'";
		 $where[] = "i_orgb = '".$iorg."'";
	     $db->delete('aset.tm_ajuanperbaikan_0', $where);
		 
		 $db->commit();
		 $_hasil = array("rcNumber"=>"1",
		                 "rcDesc"  =>"Proses Sukses");
	     return 'sukses ';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
    
	public function deletePBarangPerItem($data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
	     $db->beginTransaction();
		 $where[] = "i_inv_ajuanperbaikan = '".$data['i_inv_ajuanperbaikan']."'";
		 $where[] = "d_anggaran = '".$data['d_anggaran']."'";
		 $where[] = "c_barang = '".$data['c_barang']."'";
		 $where[] = "i_aset = '".$data['i_aset']."'";
		 $db->delete('aset.tm_ajuanperbaikan_item', $where);
		
		
		 $db->commit();
		 $_hasil = array("rcNumber"=>"1",
		                 "rcDesc"  =>"Proses Sukses");
	     return 'sukses ';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function deletePBarangItem($kode) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 $where[] = "i_inv_ajuanperbaikan = '".$kode."'";
	     $db->delete('aset.tm_ajuanperbaikan_item', $where);
		
		
		 $db->commit();
		 $_hasil = array("rcNumber"=>"1",
		                 "rcDesc"  =>"Proses Sukses");
	     return 'sukses ';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
// Mengubah data Pengajuan	
	
	public function commitPBarang(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');

	   try {
	     $db->beginTransaction();
	     $update_Status_P_Barang = array(
								"c_inv_statajuanperbaikan"=>$data['c_inv_statajuanperbaikan'],
								"d_setuju_tu"  	         =>date("Y-m-d"),
								"c_setuju_statustu"         =>'Y');

		$where[] = "i_inv_ajuanperbaikan = '".$data['i_inv_ajuanperbaikan']."'";
	     $db->update('aset.tm_ajuanperbaikan_0',$update_Status_P_Barang, $where);
		 $db->commit();

		 $_hasil = array("rcNumber"=>"1",
		                 "rcDesc"  =>"Proses Sukses");
	     return 'sukses ';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	
	
	 public function findKodeAsset() {
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT to_Char(tgl_perlh,'yyyy'),kd_brg,tgl_perlh,merk_type,
									to_char(no_aset,'09999') as no_aset,ur_sskel
									FROM aset.tm_masterhm a,aset.tm_sskel b
									where  substr(a.kd_brg,1,1) = b.kd_gol
								           and substr(a.kd_brg,2,2) = b.kd_bid 
									       and substr(a.kd_brg,4,2) = b.kd_kel
									       and substr(a.kd_brg,6,2) = b.kd_skel
									       and substr(a.kd_brg,8,3) = b.kd_sskel 
										   and tercatat = '3'
										   and not exists(select * from aset.tm_dir_item c
										   where d_aset_thnanggar = thn_ang and 
										         c_barang = kd_brg and
 												 i_aset   = no_aset and
												 d_barang_peroleh = tgl_perlh)
										   ORDER BY thn_ang");
	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("thn_ang"           =>(string)$result[$j]->thn_ang,
									"kd_brg"           =>(string)$result[$j]->kd_brg,
									"no_aset"           =>(string)$result[$j]->no_aset,
									"tgl_perlh"           =>(string)$result[$j]->tgl_perlh,
									"merk_type"   =>(string)$result[$j]->merk_type,
									"ur_sskel"           =>(string)$result[$j]->ur_sskel);
								  
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}



// public function queryNourutmax(array $data) {
	    
	   // $registry = Zend_Registry::getInstance();
	   // $db = $registry->get('db');
	    // try {
		 // $db->beginTransaction();
	     // $db->setFetchMode(Zend_Db::FETCH_OBJ);
		 // $where[] = $data['unitkr'];
		 // $where[] = $data['modl'];
		 // $result = $db->fetchOne('SELECT aset.gen_nomor(?,?)',$where);
		//print_r ($result);
	     // return $result;
	   // } catch (Exception $e) {
         // $db->rollBack();
         // echo $e->getMessage().'<br>';
	     // return  0;
	   // }
 
 
	// }
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
	// $db->beginTransaction();
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
	    // $db->beginTransaction();
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
	    public function queryNourutmax(array $data) {
	    
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	    try {
	      
		 $where[] = $data['unitkr'];
		 $where[] = $data['modl'];
		 $where[] = date("Y");
		 $cekktr = $this->cekkantor($data['unitkr']);
		 $cekmodul = $this->cekmodul($data['modl']);
		 //echo "cek ktr : ".$cekktr;
		 //echo "cek modul : ".$cekmodul;
		 if($cekktr!=0){
		   if($cekmodul!=0){
				//$result = $db->fetchOne('SELECT aset.gen_nomor(?,?)',$where);
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
		    }
		 $nomorsurat = $data['unitkr'].$data['modl'].date("Y").trim($nomax); 
	     return $nomorsurat;
	     //return "TESTING";
	     
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return  0;
	   }
 
 
	}
	
//------------------------------------ approvetu

public function getAstPerbaikanBrgAppTUByOrg($Orgb,$pageNumber, $itemPerPage) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	      
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
		 $TU 	= $db->fetchCol("SELECT c_dept FROM sdm.tr_unitkerja where c_satker = ? and c_dept is not null",$Orgb);
		 $unitTU = $TU[0];
		 	
		 if($unitTU !=''){
					if (substr($unitTU,0,2) == 'DP' ){
			               $unitTULike = substr($unitTU,0,3).'%';
			        }else{
			               $unitTULike = substr($unitTU,0,2).'%';
			        }
         
         //$where[] = $unitTULike;
		 if(($pageNumber==0) && ($itemPerPage==0))
			{
				$data =  $db->fetchOne("select count(*) FROM aset.tm_ajuanperbaikan_0 a,sdm.tr_unitkerja b 
										where  a.c_satker like '$unitTULike' and c_inv_statajuanperbaikan = 'B'
										and a.i_orgb = b.c_lokasi_unitkerja
										and (c_setuju_statustu is NULL or c_setuju_statustu ='')" );
			}
			else
			{	
			 $xLimit=$itemPerPage;
	  		 $xOffset=($pageNumber-1)*$itemPerPage;
			 
	     $result = $db->fetchAll("select i_inv_ajuanperbaikan,to_char(d_inv_ajuanperbaikan,'dd-mm-yyyy') as d_inv_ajuanperbaikan,
								c_inv_statajuanperbaikan,c_setuju_statustu,to_char(d_setuju_tu,'dd-mm-yyyy') as d_setuju_tu ,
								a.i_orgb,c_setuju_statuskabag,d_setuju_kabag,n_orgb 
								from aset.tm_ajuanperbaikan_0 a ,sdm.tr_unitkerja b
								where  a.i_orgb like '$unitTULike' and c_inv_statajuanperbaikan = 'B'
								and a.i_orgb = b.c_lokasi_unitkerja
								and (c_setuju_statustu is NULL or c_setuju_statustu ='') limit $xLimit offset $xOffset " );
 
		//print_r 	($result);

		$jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++) {

           $data[$j] = array("i_inv_ajuanperbaikan" =>(string)$result[$j]->i_inv_ajuanperbaikan,
								"d_inv_ajuanperbaikan" =>(string)$result[$j]->d_inv_ajuanperbaikan,
								"c_inv_statajuanperbaikan" =>(string)$result[$j]->c_inv_statajuanperbaikan,
								"c_setuju_statustu" =>(string)$result[$j]->c_setuju_statustu,
								"d_setuju_tu" =>(string)$result[$j]->d_setuju_tu,
								"c_setuju_statuskabag" =>(string)$result[$j]->c_setuju_statuskabag,
								"d_setuju_kabag" =>(string)$result[$j]->d_setuju_kabag,
								"i_orgb" =>(string)$result[$j]->i_orgb,
								"n_orgb" =>(string)$result[$j]->n_orgb,
								"i_entry" =>(string)$result[$j]->i_entry,
								"d_entry"	 =>(string)$result[$j]->d_entry);	
						 
		 }	
		}
	     return $data;
		}else{
				return 0;
		} 
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	} 	


public function getAstPerbaikanBrgAppTUByOrgKode($Orgb,$Kode) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $Orgb;
		 $where[] = $Kode;
		 
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchAll("select i_inv_ajuanperbaikan,to_char(d_inv_ajuanperbaikan,'dd-mm-yyyy') as d_inv_ajuanperbaikan,
								c_inv_statajuanperbaikan,c_setuju_statustu,to_char(d_setuju_tu,'dd-mm-yyyy') as d_setuju_tu ,
								i_orgb,c_setuju_statuskabag,d_setuju_kabag,i_entry,d_entry 
								from aset.tm_ajuanperbaikan_0 where  i_orgb = ? and c_inv_statajuanperbaikan = 'B'
								and (c_setuju_statustu is NULL or c_setuju_statustu =' ') and i_inv_ajuanperbaikan = ?
								order by i_inv_ajuanperbaikan",$where );
 
		//print_r 	($result);

		 $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {

           $data[$j] = array("i_inv_ajuanperbaikan" =>(string)$result[$j]->i_inv_ajuanperbaikan,
								"d_inv_ajuanperbaikan" =>(string)$result[$j]->d_inv_ajuanperbaikan,
								"c_inv_statajuanperbaikan" =>(string)$result[$j]->c_inv_statajuanperbaikan,
								"c_setuju_statustu" =>(string)$result[$j]->c_setuju_statustu,
								"d_setuju_tu" =>(string)$result[$j]->d_setuju_tu,
								"c_setuju_statuskabag" =>(string)$result[$j]->c_setuju_statuskabag,
								"d_setuju_kabag" =>(string)$result[$j]->d_setuju_kabag,
								"i_orgb" =>(string)$result[$j]->i_orgb,
								"i_entry" =>(string)$result[$j]->i_entry,
								"d_entry"	 =>(string)$result[$j]->d_entry);	
						 
		 }					 
	     return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	} 	
	public function getAstPerbaikanBrgAppTUByOrgKode2($Orgb,$Kode) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchAll("select i_inv_ajuanperbaikan,to_char(d_inv_ajuanperbaikan,'dd-mm-yyyy') as d_inv_ajuanperbaikan,
								c_inv_statajuanperbaikan,c_setuju_statustu,to_char(d_setuju_tu,'dd-mm-yyyy') as d_setuju_tu ,
								i_orgb,c_setuju_statuskabag,d_setuju_kabag,i_entry,d_entry 
								from aset.tm_ajuanperbaikan_0 
								where  i_inv_ajuanperbaikan = ?
								order by i_inv_ajuanperbaikan",$Kode);
 
		//print_r 	($result);

		 $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {

           $data[$j] = array("i_inv_ajuanperbaikan" =>(string)$result[$j]->i_inv_ajuanperbaikan,
								"d_inv_ajuanperbaikan" =>(string)$result[$j]->d_inv_ajuanperbaikan,
								"c_inv_statajuanperbaikan" =>(string)$result[$j]->c_inv_statajuanperbaikan,
								"c_setuju_statustu" =>(string)$result[$j]->c_setuju_statustu,
								"d_setuju_tu" =>(string)$result[$j]->d_setuju_tu,
								"c_setuju_statuskabag" =>(string)$result[$j]->c_setuju_statuskabag,
								"d_setuju_kabag" =>(string)$result[$j]->d_setuju_kabag,
								"i_orgb" =>(string)$result[$j]->i_orgb,
								"i_entry" =>(string)$result[$j]->i_entry,
								"d_entry"	 =>(string)$result[$j]->d_entry);	
						 
		 }					 
	     return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	} 

////Mengubah AppPBarangTU

	public function updatePBarangTu(array $data) {
	    $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $update_P_Barang = array(
								"c_setuju_statustu"=>$data['c_setuju_statustu'],
								"e_alasan"=>$data['e_keterangan'],
								"d_setuju_tu"=>$data['d_setuju_tu']);
//print_r 	($update_P_Barang);
		$where[] = "i_inv_ajuanperbaikan = '".$data['i_inv_ajuanperbaikan']."'"; 
		$db->update('aset.tm_ajuanperbaikan_0',$update_P_Barang, $where);
		 
		 $db->commit();
		 $_hasil = array("rcNumber"=>"1",
		                 "rcDesc"  =>"Proses Sukses");
	     return 'sukses ';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
////Mengubah AppPBarangTU
	
//------------------------------------ approvePerlengkapan

public function getAstPerbaikanBrgAppKBagByOrg($Orgb,$pageNumber, $itemPerPage) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
			{
				$data =  $db->fetchOne("select count(*) from aset.tm_ajuanperbaikan_0 a,sdm.tm_pegawai b
								where   c_inv_statajuanperbaikan = 'B'
								and a.i_orgb = b.i_peg_nip
								and c_setuju_statuskabag is NULL and c_setuju_statustu ='Y'");
			}
			else
			{		 
		 	$xLimit=$itemPerPage;
	  		 $xOffset=($pageNumber-1)*$itemPerPage;
		 
	     $result = $db->fetchAll("select i_inv_ajuanperbaikan,to_char(d_inv_ajuanperbaikan,'dd-mm-yyyy') as d_inv_ajuanperbaikan,
								c_inv_statajuanperbaikan,c_setuju_statustu,to_char(d_setuju_tu,'dd-mm-yyyy') as d_setuju_tu ,
								a.i_orgb,c_setuju_statuskabag,d_setuju_kabag,a.i_entry,a.d_entry,n_peg 
								from aset.tm_ajuanperbaikan_0 a,sdm.tm_pegawai b
								where   c_inv_statajuanperbaikan = 'B'
								and a.i_orgb = b.i_peg_nip
								and c_setuju_statuskabag is NULL and c_setuju_statustu ='Y' 
								limit $xLimit offset $xOffset" );
 
		//print_r 	($result);

		$jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++) {

           $data[$j] = array("i_inv_ajuanperbaikan" =>(string)$result[$j]->i_inv_ajuanperbaikan,
								"d_inv_ajuanperbaikan" =>(string)$result[$j]->d_inv_ajuanperbaikan,
								"c_inv_statajuanperbaikan" =>(string)$result[$j]->c_inv_statajuanperbaikan,
								"c_setuju_statustu" =>(string)$result[$j]->c_setuju_statustu,
								"d_setuju_tu" =>(string)$result[$j]->d_setuju_tu,
								"c_setuju_statuskabag" =>(string)$result[$j]->c_setuju_statuskabag,
								"d_setuju_kabag" =>(string)$result[$j]->d_setuju_kabag,
								"i_orgb" =>(string)$result[$j]->i_orgb,
								"n_orgb" =>(string)$result[$j]->n_peg,
								"i_entry" =>(string)$result[$j]->i_entry,
								"d_entry"	 =>(string)$result[$j]->d_entry);	
						 
		 }	
	}
	     return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	} 	


public function getAstPerbaikanBrgAppKBagByOrgKode($Orgb,$Kode) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchAll("select i_inv_ajuanperbaikan,to_char(d_inv_ajuanperbaikan,'dd-mm-yyyy') as d_inv_ajuanperbaikan,
								c_inv_statajuanperbaikan,c_setuju_statustu,to_char(d_setuju_tu,'dd-mm-yyyy') as d_setuju_tu ,
								i_orgb,c_setuju_statuskabag,d_setuju_kabag,i_entry,d_entry 
								from aset.tm_ajuanperbaikan_0 where  i_inv_ajuanperbaikan = '$Kode'" );
 
		//print_r 	($result);

		$jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++) {

           $data[$j] = array("i_inv_ajuanperbaikan" =>(string)$result[$j]->i_inv_ajuanperbaikan,
								"d_inv_ajuanperbaikan" =>(string)$result[$j]->d_inv_ajuanperbaikan,
								"c_inv_statajuanperbaikan" =>(string)$result[$j]->c_inv_statajuanperbaikan,
								"c_setuju_statustu" =>(string)$result[$j]->c_setuju_statustu,
								"d_setuju_tu" =>(string)$result[$j]->d_setuju_tu,
								"c_setuju_statuskabag" =>(string)$result[$j]->c_setuju_statuskabag,
								"d_setuju_kabag" =>(string)$result[$j]->d_setuju_kabag,
								"i_orgb" =>(string)$result[$j]->i_orgb,
								"i_entry" =>(string)$result[$j]->i_entry,
								"d_entry"	 =>(string)$result[$j]->d_entry);	
						 
		 }					 
	     return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	} 	

//------------------------------------ approvePerlengkapan
////Mengubah AppPBarangKBagPerlengkapan

	public function updatePBarangKBag(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $update_P_Barang = array(
								"c_setuju_statuskabag"=>$data['c_setuju_statuskabag'],
								"e_alasan"=>$data['e_alasan'],
								"d_setuju_kabag"=>$data['d_setuju_kabag']);
//print_r 	($update_P_Barang);
		$where[] = "i_inv_ajuanperbaikan = '".$data['i_inv_ajuanperbaikan']."'";
	    $db->update('aset.tm_ajuanperbaikan_0',$update_P_Barang, $where);
		 
		 $db->commit();
		 $_hasil = array("rcNumber"=>"1",
		                 "rcDesc"  =>"Proses Sukses");
	     return 'sukses ';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
////Mengubah AppPBarangKBagPerlengkapan	


//------------------------------------ Laporan Perbaikan Barang

public function getAstPerbaikanBrgAppLapByOrg($pageNumber, $itemPerPage) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
		 if(($pageNumber==0) && ($itemPerPage==0))
			{
				$data =  $db->fetchOne("select count(*) from  aset.tm_ajuanperbaikan_0 a,aset.tm_ajuanperbaikan_item b
										where  B. i_inv_ajuanperbaikan = A.i_inv_ajuanperbaikan 
										and c_barang_serah  = 'Y' and c_inv_perbaikan is null " );
			}
			else
			{	
			 $xLimit=$itemPerPage;
	  		 $xOffset=($pageNumber-1)*$itemPerPage;
			 
			     $result = $db->fetchAll("Select distinct a.i_orgb, a.i_inv_ajuanperbaikan , 
										to_char(a.d_inv_ajuanperbaikan,'dd-mm-yyyy') as  d_inv_ajuanperbaikan,
										n_peg
										from  aset.tm_ajuanperbaikan_0 a,aset.tm_ajuanperbaikan_item b,sdm.tm_pegawai c
										where  B. i_inv_ajuanperbaikan = A.i_inv_ajuanperbaikan 
										and c_barang_serah  = 'Y' and c_inv_perbaikan is null 
										and a.i_orgb = c.i_peg_nip
										limit $xLimit offset $xOffset" );

		//print_r 	($result);

		$jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++) {

           $data[$j] = array("i_inv_ajuanperbaikan" =>(string)$result[$j]->i_inv_ajuanperbaikan,
								"d_inv_ajuanperbaikan" =>(string)$result[$j]->d_inv_ajuanperbaikan,
								"i_orgb" =>(string)$result[$j]->i_orgb,
								"n_orgb" =>(string)$result[$j]->n_peg,
								"i_entry" =>(string)$result[$j]->i_entry,
								"d_entry"	 =>(string)$result[$j]->d_entry);	
						 
		 }	
		}	
	     return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	} 	


public function getAstPerbaikanBrgAppLapByOrgKode($Orgb,$Kode) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchAll("select i_inv_ajuanperbaikan,to_char(d_inv_ajuanperbaikan,'dd-mm-yyyy') as d_inv_ajuanperbaikan,
								c_inv_statajuanperbaikan,c_setuju_statustu,to_char(d_setuju_tu,'dd-mm-yyyy') as d_setuju_tu ,
								i_orgb,c_setuju_statuskabag,d_setuju_kabag,i_entry,d_entry 
								from aset.tm_ajuanperbaikan_0 where  i_orgb = '$Orgb' and c_inv_statajuanperbaikan = 'B'
								and c_setuju_statuskabag is NULL and c_setuju_statustu is not NULL and i_inv_ajuanperbaikan = '$Kode'" );
 
		//print_r 	($result);

		$jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++) {

           $data[$j] = array("i_inv_ajuanperbaikan" =>(string)$result[$j]->i_inv_ajuanperbaikan,
								"d_inv_ajuanperbaikan" =>(string)$result[$j]->d_inv_ajuanperbaikan,
								"c_inv_statajuanperbaikan" =>(string)$result[$j]->c_inv_statajuanperbaikan,
								"c_setuju_statustu" =>(string)$result[$j]->c_setuju_statustu,
								"d_setuju_tu" =>(string)$result[$j]->d_setuju_tu,
								"c_setuju_statuskabag" =>(string)$result[$j]->c_setuju_statuskabag,
								"d_setuju_kabag" =>(string)$result[$j]->d_setuju_kabag,
								"i_orgb" =>(string)$result[$j]->i_orgb,
								"i_entry" =>(string)$result[$j]->i_entry,
								"d_entry"	 =>(string)$result[$j]->d_entry);	
						 
		 }					 
	     return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}

	public function updateLaporan(array $data) {

	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $insert_P_Barang_Biaya = array(
								"i_inv_ajuanperbaikan"=>$data['i_inv_ajuanperbaikan'],
								"d_anggaran"=>$data['d_anggaran'],								
								"c_barang"=>$data['c_barang'],
								"i_aset"=>$data['i_aset'],
								"d_perolehan"=>$data['d_perolehan'],
								"i_inv_biayaperbaikan"=>$data['i_inv_biayaperbaikan'],
								"d_inv_perbaikan"=>$data['d_inv_perbaikan'],
								"i_ref_kuitansi"=>$data['i_ref_kuitansi'],
								"v_inv_biayaperbakian"=>$data['v_inv_biayaperbaikan'],
								"e_inv_kegiatanperbakian"=>$data['e_inv_kegiatanperbakian'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));
								
								
	     $db->insert('aset.tm_rincibiayaperbaikan',$insert_P_Barang_Biaya);
		 
		 $db->commit();
		 $_hasil = array("rcNumber"=>"1",
		                 "rcDesc"  =>"Proses Sukses");
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function updateLaporan2(array $data) {
	    $registry = Zend_Registry::getInstance();
	    $db = $registry->get('db');
	    try {
	     $db->beginTransaction();
	     $update_P_Barang = array("v_inv_biayaperbaikan"=>$data['v_inv_biayaperbaikan'], 
		   		                  "c_inv_perbaikan"     =>'Y');
 
		 $where[] = "i_inv_ajuanperbaikan = '".$data['i_inv_ajuanperbaikan']."' and 
											d_anggaran = '".$data['d_anggaran']."' and 
											c_barang = '".$data['c_barang']."' and 
											i_aset = '".$data['i_aset']."'";
	     $db->update('aset.tm_ajuanperbaikan_item',$update_P_Barang, $where);
		 
		 
		 
		 $db->commit();
		 $_hasil = array("rcNumber"=>"1",
		                 "rcDesc"  =>"Proses Sukses");
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
 
	
	
//------------------------------------ Laporan Perbaikan Barang
//------------------------------------ penyerahan barang

public function getAstPerbaikanBrgSerahByOrg($pageNumber, $itemPerPage) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
		 if(($pageNumber==0) && ($itemPerPage==0))
			{
				$data =  $db->fetchOne("select count(*) 
				                from aset.tm_ajuanperbaikan_0 
								where i_inv_ajuanperbaikan in (select distinct(i_inv_ajuanperbaikan) from 
								aset.tm_ajuanperbaikan_item where c_barang_serah is null
								and c_setuju_statustu = 'Y'
								and c_setuju_statuskabag = 'Y')");
			}
			else
			{	
			 $xLimit=$itemPerPage;
	  		 $xOffset=($pageNumber-1)*$itemPerPage;		 
		 
	    /* $result = $db->fetchAll("select i_inv_ajuanperbaikan,to_char(d_inv_ajuanperbaikan,'dd-mm-yyyy') as d_inv_ajuanperbaikan,
								 a.i_orgb,n_orgb 
								 from e_ast_ajuanpbaikinv_0_tm a,e_org_0_0_tm b
								 where a.i_orgb = b.i_orgb and
								 i_inv_ajuanperbaikan in (select distinct(i_inv_ajuanperbaikan) from 
								 e_ast_ajuanpbaikinv_item_tm where c_barang_serah is null
								 and c_setuju_statustu = 'Y'
								 and c_setuju_statuskabag = 'Y')
								 limit $xLimit offset $xOffset " );*/
		$result = $db->fetchAll("select i_inv_ajuanperbaikan,to_char(d_inv_ajuanperbaikan,'dd-mm-yyyy') as d_inv_ajuanperbaikan,
								 a.i_orgb,n_peg 
								 from aset.tm_ajuanperbaikan_0 a,sdm.tm_pegawai b
								 where a.i_orgb = b.i_peg_nip 
								 and i_inv_ajuanperbaikan in (select distinct(i_inv_ajuanperbaikan) from 
								aset.tm_ajuanperbaikan_item where c_barang_serah is null
								and c_setuju_statustu = 'Y'
								and c_setuju_statuskabag = 'Y')
								 limit $xLimit offset $xOffset " );
  
		//print_r 	($result);

		$jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++) {

           $data[$j] = array("i_inv_ajuanperbaikan" =>(string)$result[$j]->i_inv_ajuanperbaikan,
								"d_inv_ajuanperbaikan" =>(string)$result[$j]->d_inv_ajuanperbaikan,								
								"n_orgb" =>(string)$result[$j]->n_peg,
								"i_orgb" =>(string)$result[$j]->i_orgb);
								 
						 
		 }		
	}		 
	     return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	} 	

	public function insertPBarangSerah(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $insert_P_Barang_Serah = array(
								"i_inv_serahperbaikan"=>$data['i_inv_serahperbaikan'],
								"d_inv_serahperbaikan"=>$data['d_inv_serahperbaikan'],								
								"i_orgb"=>$data['i_orgb'],
								"i_inv_ajuanperbaikan"=>$data['i_inv_ajuanperbaikan'],
								"i_peg_nippemberi"=>$data['i_peg_nippemberi'],
								"i_peg_nipterima"=>$data['i_peg_nipterima'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));
		//print_r ($insert_P_Barang_Serah);
						
	     $db->insert('aset.tm_serahperbaikan_0',$insert_P_Barang_Serah);
		 
		 $db->commit();
		 $_hasil = array("rcNumber"=>"1",
		                 "rcDesc"  =>"Proses Sukses");
	     return 'sukses ';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	

	public function insertPBarangSerahItem(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	    
	   try {
	     $db->beginTransaction();
	     $pengajuanlist = $this->getAstPerbaikanBrgByKodeItem($data['i_inv_ajuanperbaikan']);
	     $jmllistpbrg= count($pengajuanlist);
		 for ($j = 0; $j < $jmllistpbrg; $j++) {
	     //echo 'nopernya'.$pengajuanlist[$j]['c_barang'];
		 $insert_P_Barang_SerahItem = array(
								"i_inv_serahperbaikan"=>$data['i_inv_serahperbaikan'],
								"d_anggaran"          =>$pengajuanlist[$j]['d_anggaran'],								
								"c_barang"            =>$pengajuanlist[$j]['c_barang'],
								"i_aset"              =>$pengajuanlist[$j]['i_aset'],
								"d_perolehan"=>$data['d_perolehan'],
								"c_inv_perbaikan"=>$data['c_inv_perbaikan'],
								"c_barang_kembali"=>$data['c_barang_kembali'],
								"v_inv_biayaperbaikan"=>$data['v_inv_biayaperbaikan'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));
		 						
	     $db->insert('aset.tm_serahperbaikan_item',$insert_P_Barang_SerahItem);
		 };
		 $db->commit();
		 $_hasil = array("rcNumber"=>"1",
		                 "rcDesc"  =>"Proses Sukses");
	     return 'sukses ';  
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}



	public function updatePBarangSerahItem(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $update_P_Barang_Item = array(
								"c_barang_serah"=>$data['c_barang_serah']);
		$where[] = "i_inv_ajuanperbaikan = '".$data['i_inv_ajuanperbaikan']."'";
	    $db->update('aset.tm_ajuanperbaikan_item',$update_P_Barang_Item, $where);
		
		 	
		$db->commit();
		$_hasil = array("rcNumber"=>"1",
		                 "rcDesc"  =>"Proses Sukses");
	     return 'sukses ';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}		
	

//------------------------------------ penyerahan barang
//------------------------------------ pengembaian barang
public function getAstPerbaikanBrgKembaliByOrg($pageNumber, $itemPerPage) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
			{
				$data =  $db->fetchOne("select count(*) 
				                from aset.tm_ajuanperbaikan_0 a, aset.tm_ajuanperbaikan_item e,
								aset.tm_ajuanperbaikan_0 b,sdm.tr_unitkerja d,sdm.tm_pegawai f							
								where b.i_inv_ajuanperbaikan = a.i_inv_ajuanperbaikan 
								and e.i_inv_ajuanperbaikan = a.i_inv_ajuanperbaikan 
								and e.c_inv_perbaikan = 'Y'																
								and a.i_orgb = f.i_peg_nip
								and f.c_lokasi_unitkerja=d.c_lokasi_unitkerja
								and f.c_eselon_i=d.c_eselon_i
								and f.c_eselon_ii=d.c_eselon_ii
								and f.c_eselon_iii=d.c_eselon_iii
								and f.c_eselon_iv=d.c_eselon_iv
								and f.c_eselon_v=d.c_eselon_v
								and not exists(select * from aset.tm_pengembalian_0 z
								               where b.i_inv_ajuanperbaikan = z.i_barang_serah)");
			}
			else
			{	
			 $xLimit=$itemPerPage;
	  		 $xOffset=($pageNumber-1)*$itemPerPage;		 
		 
		 
         $result = $db->fetchAll("select distinct a.i_inv_ajuanperbaikan,
								to_char(d_inv_ajuanperbaikan,'dd-mm-yyyy') as d_inv_ajuanperbaikan,
								b.i_inv_serahperbaikan,a.i_orgb, n_unitkerja,d.c_satker
								from aset.tm_ajuanperbaikan_0 a, aset.tm_ajuanperbaikan_item e,
								aset.tm_serahperbaikan_0 b,sdm.tr_unitkerja d,sdm.tm_pegawai f							
								where b.i_inv_ajuanperbaikan = a.i_inv_ajuanperbaikan 
								and e.i_inv_ajuanperbaikan = a.i_inv_ajuanperbaikan 
								and e.c_inv_perbaikan = 'Y'																
								and a.i_orgb = f.i_peg_nip
								and f.c_lokasi_unitkerja=d.c_lokasi_unitkerja
								and f.c_eselon_i=d.c_eselon_i
								and f.c_eselon_ii=d.c_eselon_ii
								and f.c_eselon_iii=d.c_eselon_iii
								and f.c_eselon_iv=d.c_eselon_iv
								and f.c_eselon_v=d.c_eselon_v
								and not exists(select * from aset.tm_pengembalian_0 z
								               where b.i_inv_serahperbaikan = z.i_barang_serah)
								order by a.i_inv_ajuanperbaikan asc limit $xLimit offset $xOffset " );								
								

		$jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++) {

           $data[$j] = array("i_inv_ajuanperbaikan" =>(string)$result[$j]->i_inv_ajuanperbaikan,
								"d_inv_ajuanperbaikan" =>(string)$result[$j]->d_inv_ajuanperbaikan,								
								"i_orgb" =>(string)$result[$j]->i_orgb,
								"n_orgb" =>(string)$result[$j]->n_unitkerja,
								"c_satker" =>(string)$result[$j]->c_satker,
								"i_inv_serahperbaikan" =>(string)$result[$j]->i_inv_serahperbaikan);
								
		 }	
	}
	     return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	} 	

public function getAstPerbaikanBrgKembaliByKode($kode) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchAll("select a.i_inv_ajuanperbaikan,a.i_inv_serahperbaikan,to_char(d_inv_serahperbaikan,'dd-mm-yyyy') as d_inv_serahperbaikan,
                                  b.d_anggaran,b.c_barang,b.i_aset,ur_sskel,e_inv_kegiatanperbakian, b.d_perolehan		 
								  from aset.tm_serahperbaikan_0 a, aset.tm_serahperbaikan_item b ,aset.tm_sskel c,aset.tm_rincibiayaperbaikan  d
								  where   a.i_inv_ajuanperbaikan = '$kode' and 
								          a.i_inv_serahperbaikan = b.i_inv_serahperbaikan and
										  a.i_inv_ajuanperbaikan = d.i_inv_ajuanperbaikan
										  and b.C_BARANG = d.C_BARANG
										  and b.d_anggaran = d.d_anggaran
										  and b.i_aset = d.i_aset
										  and substr(b.C_BARANG,1,1) = c.kd_gol
								          and substr(b.C_BARANG,2,2) = c.kd_bid 
									      and substr(b.C_BARANG,4,2) = c.kd_kel
									      and substr(b.C_BARANG,6,2) = c.kd_skel
									      and substr(b.C_BARANG,8,3) = c.kd_sskel
										  and b.c_barang_kembali is null"); 
								           
								
								

		$jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++) {

           $data[$j] = array("i_inv_ajuanperbaikan" =>(string)$result[$j]->i_inv_ajuanperbaikan,
								"i_inv_serahperbaikan" =>(string)$result[$j]->i_inv_serahperbaikan,
								"d_inv_serahperbaikan" =>(string)$result[$j]->d_inv_serahperbaikan,
								"d_anggaran" =>(string)$result[$j]->d_anggaran,
								"c_barang" =>(string)$result[$j]->c_barang,
								"i_aset" =>(string)$result[$j]->i_aset,
								"d_oleh" =>(string)$result[$j]->d_perolehan,
								"e_inv_kegiatanperbakian" =>(string)$result[$j]->e_inv_kegiatanperbakian,
								"ur_sskel" =>(string)$result[$j]->ur_sskel);
								
									
		 }					 
	     return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	} 
	
	public function insertPBarangKembali(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $insert_P_Barang_Kembali = array(
								"i_barang_pengembalian"=>$data['i_barang_pengembalian'],
								"d_barang_pengembalian"=>$data['d_barang_pengembalian'],								
								"i_barang_serah"=>$data['i_barang_serah'],
								"i_peg_nippemberi"=>$data['i_peg_nippemberi'],
								"i_peg_nipterima"=>$data['i_peg_nipterima'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));
						
	     $db->insert('aset.tm_pengembalian_0',$insert_P_Barang_Kembali);
		 
		 $db->commit();
		 $_hasil = array("rcNumber"=>"1",
		                 "rcDesc"  =>"Proses Sukses");
	     return 'sukses ';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
	
	
	public function insertPBarangKembaliItem(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 $pengajuanlist = $this->getAstPerbaikanBrgKembaliByKode($data['nopeng']);
	     $jmllistpbrg= count($pengajuanlist);
		 for ($j = 0; $j < $jmllistpbrg; $j++) {	       
			 $insert_P_Barang_KembaliItem = array(
									"i_barang_pengembalian"=>$data['i_barang_pengembalian'],
									"d_anggaran"          =>$pengajuanlist[$j]['d_anggaran'],								
									"c_barang"            =>$pengajuanlist[$j]['c_barang'],
									"i_aset"              =>$pengajuanlist[$j]['i_aset'],
									"d_perolehan"		  =>$pengajuanlist[$j]['d_oleh'],
									"i_entry"=>$data['i_entry'],
									"d_entry"=>date("Y-m-d"));
			 						
		      $db->insert('aset.tm_pengembalian_item',$insert_P_Barang_KembaliItem);	
		 };
		 $db->commit();  
		 
		 $_hasil = array("rcNumber"=>"1",
		                 "rcDesc"  =>"Proses Sukses");
	     return 'sukses ';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
	


//------------------------------------ pengembaian barang

	public function getUnitKerjaList($Orgb) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchAll("select c_jabatan, i_peg_nip from e_sdm_jabatan_0_tm 
									where c_jabatan like '%$iorgb%' and i_peg_nip is not null
									order by c_jabatan asc");        

		$jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
		 
		$njabatan = $db->fetchCol('select n_jabatan from   e_sdm_pegawai_0_tm  WHERE i_peg_nip = ?',$result[$j]->i_peg_nip);
		if (isset($njabatan[0])) {$njabatan=$njabatan[0];}
		else {$njabatan="";}
		
		$iorgb = $db->fetchCol('select i_orgb from e_org_0_0_tm WHERE i_orgb = ?',$result[$j]->c_jabatan); 
		if (isset($iorgb[0])) {$iorgb=$iorgb[0];} 
		else {$iorgb="";}

		$n_orgb = $db->fetchCol('select n_orgb from e_org_0_0_tm WHERE i_orgb = ?',$result[$j]->c_jabatan); 
		if (isset($n_orgb[0])) {$n_orgb=$n_orgb[0];} 
		else {$n_orgb="";}	

		$n_peg = $db->fetchCol('select n_peg from   e_sdm_pegawai_0_tm  WHERE i_peg_nip = ?',$result[$j]->i_peg_nip);
		if (isset($n_peg[0])) {$n_peg=$n_peg[0];}
		else {$n_peg="";}		
		
           $hasilAkhir[$j] = array("i_orgb" =>$iorgb,
								   "n_orgb" =>$n_orgb, 
								   "i_peg_nip" =>(string)$result[$j]->i_peg_nip, 
								   "n_peg" =>$n_peg,
								   "n_jabatan" =>$njabatan); 
		 }
		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'ga ono data rek <br>';
		}
	}
 
 public function getLisPbarangDir($pageNumber, $itemPerPage,$unitkr,$thnang) {
	 	 
	$registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    $orgTU 	= $db->fetchCol("SELECT i_peg_nip 
		                        FROM sdm.tm_pegawai a,sdm.tr_unitkerja b 
								where b.c_satker = ? 
		                        and a.c_lokasi_unitkerja = b.c_lokasi_unitkerja
								and a.c_eselon_i=b.c_eselon_i
								and a.c_eselon_ii=b.c_eselon_ii
								and a.c_eselon_iii=b.c_eselon_iii
								and a.c_eselon_iv=b.c_eselon_iv
								and a.c_eselon_v=b.c_eselon_v
								",$unitkr); 
		// $orgTU 	= $db->fetchCol("SELECT c_satker FROM sdm.tr_unitkerja where c_satker = ? 
		                        // and c_dept !=''
		                        // and c_dept is not null",$unitkr);          
		
	    if (isset($orgTU[0])) {
			$orgTU=$orgTU[0];				
		}
		// else 
		// {		
			// if (substr($unitkr,0,2) == 'DP')
			// {
			      // $unit = substr($unitkr,0,3);
				  // $orgTU = $db->fetchCol("SELECT c_satker FROM sdm.tr_unitkerja 
	                         		// where c_dept is not null and c_dept !=''
	                                // and SUBSTRING(c_dept,1,3) = ?",$unit);
			// } else
			// {
			      // $unit = substr($unitkr,0,2);
				  // $orgTU = $db->fetchCol("SELECT c_satker FROM sdm.tr_unitkerja 
	                         		// where c_dept is not null and c_dept !=''
	                                // and SUBSTRING(c_dept,1,2) = ?",$unit);
			// }
			
			// if (isset($orgTU[0])) {
				// $orgTU=$orgTU[0];				
			// }
			// else 
			// {	$orgTU="";}	 
		// }	
		
		
        //echo "nippjrug : ".$orgTU;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir =  $db->fetchOne("select count(*) 
				                    from aset.tm_dir_0 a, aset.tm_dir_item b, aset.tm_sskel c,
									aset.tm_ruang D
									where a.i_barang_serah = b.i_barang_serah
									and substr(b.c_barang,1,1) = c.kd_gol
									and substr(b.c_barang,2,2) = c.kd_bid 
									and substr(b.c_barang,4,2) = c.kd_kel
									and substr(b.c_barang,6,2) = c.kd_skel
									and substr(b.c_barang,8,3) = c.kd_sskel 
									and d.nip_pjrug like '$orgTU'
									and b.i_ruang = d.kd_ruang
									AND not exists(select * from aset.tm_ajuanperbaikan_item d, 
							            aset.tm_ajuanperbaikan_0 e            
							            where b.c_barang = d.c_barang 
							            and d.i_inv_ajuanperbaikan =e.i_inv_ajuanperbaikan
							            and   b.i_aset   = d.i_aset 
										and c_inv_perbaikan is null 
							            and (c_setuju_statustu !='T' or c_setuju_statustu is null)
							       		and (c_setuju_statuskabag != 'T' or c_setuju_statuskabag is null))");
				
			}
			else
			{	
			 $xLimit=$itemPerPage;
	  		 $xOffset=($pageNumber-1)*$itemPerPage;		 
		 
		 
			$result = $db->fetchAll("select  a.i_barang_serah,b.d_barang_peroleh,b.d_aset_thnanggar,
									b.c_barang, to_char(b.i_aset,'09999') as  i_aset,b.i_aset as iaset,c.ur_sskel, b.i_ruang 
									from aset.tm_dir_0 a, aset.tm_dir_item b, aset.tm_sskel c,
									aset.tm_ruang D
									where a.i_barang_serah = b.i_barang_serah
									and substr(b.c_barang,1,1) = c.kd_gol
									and substr(b.c_barang,2,2) = c.kd_bid 
									and substr(b.c_barang,4,2) = c.kd_kel
									and substr(b.c_barang,6,2) = c.kd_skel
									and substr(b.c_barang,8,3) = c.kd_sskel 
									and d.nip_pjrug like '$orgTU'
									and b.i_ruang = d.kd_ruang
									AND not exists(select * from aset.tm_ajuanperbaikan_item d, 
							            aset.tm_ajuanperbaikan_0 e            
							            where b.c_barang = d.c_barang 
							            and d.i_inv_ajuanperbaikan =e.i_inv_ajuanperbaikan
							            and   b.i_aset   = d.i_aset 
										and c_inv_perbaikan is null 
							            and (c_setuju_statustu !='T' or c_setuju_statustu is null)
							       		and (c_setuju_statuskabag != 'T' or c_setuju_statuskabag is null)) 
										
									order by a.i_barang_serah asc limit $xLimit offset $xOffset " );
									

		 $jmlResult = count($result);		
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
			$kode=$result[$j]->c_barang;
			$noaset=$result[$j]->iaset;
			$tgoleh=$result[$j]->d_barang_peroleh;
// merk

			$merktype = $db->fetchCol('SELECT merk_type FROM aset.tm_masterhm 
										WHERE kd_brg = ?',$result[$j]->c_barang ,
										'and no_aset = ?',$result[$j]->iaset ,
										'and tgl_perlh = ?',$result[$j]->d_barang_peroleh);
		
			if (isset($merktype[0])) {
				$merktype=$merktype[0];				
				}
			else 
			{	$merktype="";}	 
			
			

		 
           $hasilAkhir[$j] = array("i_barang_serah"      =>(string)$result[$j]->i_barang_serah,
									"d_barang_peroleh"   =>(string)$result[$j]->d_barang_peroleh,
									"d_aset_thnanggar"   =>(string)$result[$j]->d_aset_thnanggar,
									"c_barang"    		 =>(string)$result[$j]->c_barang,
									"i_aset"     		 =>(string)$result[$j]->i_aset,
									"ur_sskel"   		 =>(string)$result[$j]->ur_sskel,
									"i_ruang"   		 =>(string)$result[$j]->i_ruang,
									"merktype"   		 =>$merktype);
		 }
        } 		 		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
		
    public function getLisPbarangDir_Old($pageNumber, $itemPerPage,$unitkr) {
		 
	$registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
	    
		
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir =  $db->fetchOne("select count(*) from e_ast_dir_0_tm a, e_ast_dir_item_tm b, e_ast_sskel_0_tr c
								where a.i_barang_serah = b.i_barang_serah
								and substr(b.c_barang,1,1) = c.kd_gol
								and substr(b.c_barang,2,2) = c.kd_bid 
								and substr(b.c_barang,4,2) = c.kd_kel
								and substr(b.c_barang,6,2) = c.kd_skel
								and substr(b.c_barang,8,3) = c.kd_sskel 
								and a.i_orgb_pemberi = '$unitkr'
                                AND not exists(select * from e_ast_ajuanpbaikinv_item_tm d
                                where b.c_barang = d.c_barang 
                                and   b.i_aset   = d.i_aset 
                                and   b.d_aset_thnanggar = d_anggaran)");
			}
			else
			{	
			 $xLimit=$itemPerPage;
	  		 $xOffset=($pageNumber-1)*$itemPerPage;		 
		 
		 
	     $result = $db->fetchAll("select  a.i_barang_serah,b.d_barang_peroleh,b.d_aset_thnanggar,
								b.c_barang, to_char(b.i_aset,'09999') as  i_aset,b.i_aset as iaset,c.ur_sskel 
								from e_ast_dir_0_tm a, e_ast_dir_item_tm b, e_ast_sskel_0_tr c
								where a.i_barang_serah = b.i_barang_serah
								and substr(b.c_barang,1,1) = c.kd_gol
								and substr(b.c_barang,2,2) = c.kd_bid 
								and substr(b.c_barang,4,2) = c.kd_kel
								and substr(b.c_barang,6,2) = c.kd_skel
								and substr(b.c_barang,8,3) = c.kd_sskel 
								and a.i_orgb_pemberi='$unitkr'
								AND not exists(select * from e_ast_ajuanpbaikinv_item_tm d
                                where b.c_barang = d.c_barang 
                                and   b.i_aset   = d.i_aset 
                                and   b.d_aset_thnanggar = d_anggaran)								
								order by a.i_barang_serah asc limit $xLimit offset $xOffset " );
								
								

		 $jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
			$kode=$result[$j]->c_barang;
			$noaset=$result[$j]->iaset;
			$tgoleh=$result[$j]->d_barang_peroleh;
// merk

			$merktype = $db->fetchCol('SELECT merk_type FROM aset.tm_masterhm 
										WHERE kd_brg = ?',$result[$j]->c_barang ,
										'and no_aset = ?',$result[$j]->iaset ,
										'and tgl_perlh = ?',$result[$j]->d_barang_peroleh);
		
			if (isset($merktype[0])) {
				$merktype=$merktype[0];				
				}
			else 
			{	$merktype="";}	 
			//$merktype="Mitshubisi";
			

		 
           $hasilAkhir[$j] = array("i_barang_serah"      =>(string)$result[$j]->i_barang_serah,
									"d_barang_peroleh"   =>(string)$result[$j]->d_barang_peroleh,
									"d_aset_thnanggar"   =>(string)$result[$j]->d_aset_thnanggar,
									"c_barang"    		 =>(string)$result[$j]->c_barang,
									"i_aset"     		 =>(string)$result[$j]->i_aset,
									"ur_sskel"   		 =>(string)$result[$j]->ur_sskel,
									"merktype"   		 =>$merktype);
		 }
        } 		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}

	public function getLisPbarangDil($pageNumber, $itemPerPage,$unitkr) {
	 
	$registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
         if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir =  $db->fetchOne("select count(*) from aset.tm_dil_0 a, aset.tm_dil_item b, aset.tm_sskel c
								where a.i_barang_serah = b.i_barang_serah
								and substr(b.c_barang,1,1) = c.kd_gol
								and substr(b.c_barang,2,2) = c.kd_bid 
								and substr(b.c_barang,4,2) = c.kd_kel
								and substr(b.c_barang,6,2) = c.kd_skel
								and substr(b.c_barang,8,3) = c.kd_sskel
                                AND not exists(select * from aset.tm_ajuanperbaikan_item d, 
							            aset.tm_ajuanperbaikan_0 e            
							            where b.c_barang = d.c_barang 
							            and d.i_inv_ajuanperbaikan =e.i_inv_ajuanperbaikan
							            and   b.i_aset   = d.i_aset 										 
										and c_inv_perbaikan is null 
							            and (c_setuju_statustu !='T' or c_setuju_statustu is null)
							       		and (c_setuju_statuskabag != 'T' or c_setuju_statuskabag is null))
								and a.i_orgb_penerima='$unitkr' ");
			}
			else
			{	
		     $xLimit=$itemPerPage;
	  		 $xOffset=($pageNumber-1)*$itemPerPage;		 
		     $result = $db->fetchAll("select  a.i_barang_serah,b.d_perolehan,b.d_anggaran,
								b.c_barang,to_char(b.i_aset,'09999') as  i_aset,
								b.i_aset as  iaset,b.n_aset_lokasifisik,c.ur_sskel 
								from aset.tm_dil_0 a, aset.tm_dil_item b, aset.tm_sskel c
								where a.i_barang_serah = b.i_barang_serah
								and substr(b.c_barang,1,1) = c.kd_gol
								and substr(b.c_barang,2,2) = c.kd_bid 
								and substr(b.c_barang,4,2) = c.kd_kel
								and substr(b.c_barang,6,2) = c.kd_skel
								and substr(b.c_barang,8,3) = c.kd_sskel 
								and a.i_orgb_penerima='$unitkr'
								AND not exists(select * from aset.tm_ajuanperbaikan_item d, 
							            aset.tm_ajuanperbaikan_0 e            
							            where b.c_barang = d.c_barang 
							            and d.i_inv_ajuanperbaikan =e.i_inv_ajuanperbaikan
							            and   b.i_aset   = d.i_aset 
										and c_inv_perbaikan is null 
							            and (c_setuju_statustu !='T' or c_setuju_statustu is null)
							       		and (c_setuju_statuskabag != 'T' or c_setuju_statuskabag is null))
								order by a.i_barang_serah asc limit $xLimit offset $xOffset");
								
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
// merk

			$merktype = $db->fetchCol('SELECT merk_type FROM aset.tm_masterhm 
										WHERE kd_brg = ?',$result[$j]->c_barang ,
										'and no_aset = ?',$result[$j]->iaset ,
										'and tgl_perlh = ?',$result[$j]->d_perolehan);
		
			if (isset($merktype[0])) {
				$merktype=$merktype[0];				
				}
			else 
			{	$merktype="";}		 
		 
           $hasilAkhir[$j] = array("i_barang_serah"      =>(string)$result[$j]->i_barang_serah,
									"d_barang_peroleh"   =>(string)$result[$j]->d_perolehan,
									"d_aset_thnanggar"   =>(string)$result[$j]->d_anggaran,
									"c_barang"    		 =>(string)$result[$j]->c_barang,
									"i_aset"     		 =>(string)$result[$j]->i_aset,
									"n_aset_lokasifisik" =>(string)$result[$j]->n_aset_lokasifisik,
									"ur_sskel"   		 =>(string)$result[$j]->ur_sskel,
									"merktype"   		 =>$merktype);
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	


	public function getLisPbarangKib($pageNumber, $itemPerPage,$unitkr) {
		 
	$registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
         if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir =  $db->fetchOne("select count(*) from aset.tm_kib_0 a, aset.tm_kib_item b, aset.tm_sskel c
									where a.i_barang_serah = b.i_barang_serah
									and substr(b.c_barang,1,1) = c.kd_gol
									and substr(b.c_barang,2,2) = c.kd_bid 
									and substr(b.c_barang,4,2) = c.kd_kel
									and substr(b.c_barang,6,2) = c.kd_skel
									and substr(b.c_barang,8,3) = c.kd_sskel 
									and a.i_orgb_penerima ='$unitkr'
									AND not exists(select * from aset.tm_ajuanperbaikan_item d, 
							            aset.tm_ajuanperbaikan_0 e            
							            where b.c_barang = d.c_barang 
							            and d.i_inv_ajuanperbaikan =e.i_inv_ajuanperbaikan
							            and   b.i_aset   = d.i_aset 
										and   b.d_anggaran = d.d_anggaran
										and c_inv_perbaikan is null 
							            and (c_setuju_statustu !='T' or c_setuju_statustu is null)
							       		and (c_setuju_statuskabag != 'T' or c_setuju_statuskabag is null)
										)");
			}
			else
			{	
		     $xLimit=$itemPerPage;
	  		 $xOffset=($pageNumber-1)*$itemPerPage; 		 
		 $result = $db->fetchAll("select  distinct a.i_barang_serah,b.d_perolehan,b.d_anggaran,
									b.c_barang,to_char(b.i_aset,'09999') as  i_aset,b.i_aset as  iaset,c.ur_sskel 
									from aset.tm_kib_0 a, aset.tm_kib_item b, aset.tm_sskel c
									where a.i_barang_serah = b.i_barang_serah
									and substr(b.c_barang,1,1) = c.kd_gol 
									and substr(b.c_barang,2,2) = c.kd_bid 
									and substr(b.c_barang,4,2) = c.kd_kel
									and substr(b.c_barang,6,2) = c.kd_skel
									and substr(b.c_barang,8,3) = c.kd_sskel 
									and a.i_orgb_penerima='$unitkr'
									AND not exists(select * from aset.tm_ajuanperbaikan_item d, 
							            aset.tm_ajuanperbaikan_0 e            
							            where b.c_barang = d.c_barang 
							            and d.i_inv_ajuanperbaikan =e.i_inv_ajuanperbaikan
							            and   b.i_aset   = d.i_aset 
										and   b.d_anggaran = d.d_anggaran
										and c_inv_perbaikan is null 
							            and (c_setuju_statustu !='T' or c_setuju_statustu is null)
							       		and (c_setuju_statuskabag != 'T' or c_setuju_statuskabag is null))
									order by a.i_barang_serah asc limit $xLimit offset $xOffset");
								
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {

// merk

			$merktype = $db->fetchCol('SELECT merk_type FROM aset.tm_masterhm 
										WHERE kd_brg = ?',$result[$j]->c_barang ,
										'and no_aset = ?',$result[$j]->iaset ,
										'and tgl_perlh = ?',$result[$j]->d_perolehan);
		     
			if (isset($merktype[0])) {
				$merktype=$merktype[0];				
				}
			else 
			{	$merktype="";}	
			
           $hasilAkhir[$j] = array("i_barang_serah"      =>(string)$result[$j]->i_barang_serah,
									"d_barang_peroleh"   =>(string)$result[$j]->d_perolehan,
									"d_aset_thnanggar"   =>(string)$result[$j]->d_anggaran,
									"c_barang"    		 =>(string)$result[$j]->c_barang,
									"i_aset"     		 =>(string)$result[$j]->i_aset,
									"ur_sskel"   		 =>(string)$result[$j]->ur_sskel,
									"merktype"   		 =>$merktype);
		 }
		}		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
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
										from sdm.tm_jabatan A,  sdm.tm_pegawai B
										where a.c_jabatan = b.c_jabatan
										and b.c_lokasi_unitkerja = ? 
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
        // $TU 	= $db->fetchCol("SELECT i_orgb_tu FROM e_org_0_0_tm where i_orgb = ? and i_orgb_tu is not null",$unitkr); 
         $TU 	= $db->fetchCol("SELECT c_dept FROM sdm.tr_unitkerja where c_satker = ? and c_dept is not null",$unitkr); 
        return $TU;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	} 
 
public function getPerbaikanListView1($pageNumber,$itemPerPage,$unitkr, $unitkr1) {
        
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     // $where[] = 'A';
		 // $where[] = $unitkr;
		 // $where[] = $unitkr1;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
		    $sql="SELECT count(*)
				FROM aset.tm_ajuanperbaikan_0 a,sdm.tr_unitkerja b,sdm.tm_pegawai c
				where a.i_orgb = c.i_peg_nip
				and c.c_lokasi_unitkerja=b.c_lokasi_unitkerja
				and c.c_eselon_i=b.c_eselon_i
				and c.c_eselon_ii=b.c_eselon_ii
				and c.c_eselon_iii=b.c_eselon_iii
				and c.c_eselon_iv=b.c_eselon_iv
				and c.c_eselon_v=b.c_eselon_v
				and b.c_satker='$unitkr'
				and c_setuju_statustu is null 
				and c_inv_statajuanperbaikan = 'A'";
			$hasilAkhir = $db->fetchOne($sql);
			 				   
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		     $sql="SELECT i_inv_ajuanperbaikan,d_inv_ajuanperbaikan,a.i_orgb,n_unitkerja
				FROM aset.tm_ajuanperbaikan_0 a,sdm.tr_unitkerja b,sdm.tm_pegawai c
				where a.i_orgb = c.i_peg_nip
				and c.c_lokasi_unitkerja=b.c_lokasi_unitkerja
				and c.c_eselon_i=b.c_eselon_i
				and c.c_eselon_ii=b.c_eselon_ii
				and c.c_eselon_iii=b.c_eselon_iii
				and c.c_eselon_iv=b.c_eselon_iv
				and c.c_eselon_v=b.c_eselon_v
				and b.c_satker='$unitkr'
				and c_setuju_statustu is null 
				and c_inv_statajuanperbaikan = 'A'
				order by i_inv_ajuanperbaikan
				limit $xLimit offset $xOffset";
			 $result = $db->fetchAll($sql); 
			 $jmlResult = count($result);
		     
		    
		    if($jmlResult > 0){
		       for ($j = 0; $j < $jmlResult; $j++) {
		      
              $hasilAkhir[$j] = array("i_inv_ajuanperbaikan"           =>(string)$result[$j]->i_inv_ajuanperbaikan,
								   "d_inv_ajuanperbaikan"           =>(string)$result[$j]->d_inv_ajuanperbaikan,
								   "i_orgb"           =>(string)$result[$j]->i_orgb,
								   "n_orgb"           =>(string)$result[$j]->n_unitkerja);
								  
								  
							       
		
		      }
           
       		
	         
		    }
			//else{
				// return 0;
			// }
         }
		 //echo $sql;
         return $hasilAkhir;		 
	   }catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	  }
	}
public function getPerbaikanListView2($pageNumber,$itemPerPage,$unitkr,$unitkr1) {
        
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = 'B';
		 $where[] = $unitkr;
		 $where[] = $unitkr1;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	  FROM aset.tm_ajuanperbaikan_0 a,sdm.tr_unitkerja b 
								  where a.i_orgb = b.c_lokasi_unitkerja and c_setuju_statustu is null 
								  and c_inv_statajuanperbaikan = ?
								  and (a.i_orgb like ? or a.i_orgb like ?)",$where);
			 				   
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		 
			 $result = $db->fetchAll("SELECT i_inv_ajuanperbaikan,d_inv_ajuanperbaikan,a.i_orgb,n_unitkerja
		                       	 FROM aset.tm_ajuanperbaikan_0 a,sdm.tr_unitkerja b 
								 where a.i_orgb = b.c_lokasi_unitkerja and c_setuju_statustu is null 
								 and c_inv_statajuanperbaikan = ?
								 and (a.i_orgb like ? or a.i_orgb like ?)
								 order by i_inv_ajuanperbaikan
								 limit $xLimit offset $xOffset",$where); 
			 $jmlResult = count($result);
		     
		
		    if($jmlResult > 0){
		       for ($j = 0; $j < $jmlResult; $j++) {
		      
              $hasilAkhir[$j] = array("i_inv_ajuanperbaikan"           =>(string)$result[$j]->i_inv_ajuanperbaikan,
								   "d_inv_ajuanperbaikan"           =>(string)$result[$j]->d_inv_ajuanperbaikan,
								   "i_orgb"           =>(string)$result[$j]->i_orgb,
								   "n_orgb"           =>(string)$result[$j]->n_unitkerja);
								  
								  
							       
		
		      }
           
       		
	         
		    }else{
				return 0;
			}
         }
         return $hasilAkhir;		 
	   }catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	  }
	}
public function getPerbaikanListView3($pageNumber,$itemPerPage,$unitkr, $unitkr1) {
        
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = 'Y';
		 $where[] = $unitkr;
		 $where[] = $unitkr1;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
		    $sql="SELECT count(*)
				FROM aset.tm_ajuanperbaikan_0 a,sdm.tr_unitkerja b,sdm.tm_pegawai c
				where a.i_orgb = c.i_peg_nip
                and c.c_lokasi_unitkerja=b.c_lokasi_unitkerja
                and c.c_eselon_i=b.c_eselon_i
                and c.c_eselon_ii=b.c_eselon_ii
                and c.c_eselon_iii=b.c_eselon_iii
                and c.c_eselon_iv=b.c_eselon_iv
                and c.c_eselon_v=b.c_eselon_v
				and b.c_satker='$unitkr'
				and c_setuju_statustu is null";
			$hasilAkhir = $db->fetchOne($sql);
			 				   
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		     $sql="SELECT i_inv_ajuanperbaikan,d_inv_ajuanperbaikan,a.i_orgb,n_unitkerja
				FROM aset.tm_ajuanperbaikan_0 a,sdm.tr_unitkerja b,sdm.tm_pegawai c
				where a.i_orgb = c.i_peg_nip
                and c.c_lokasi_unitkerja=b.c_lokasi_unitkerja
                and c.c_eselon_i=b.c_eselon_i
                and c.c_eselon_ii=b.c_eselon_ii
                and c.c_eselon_iii=b.c_eselon_iii
                and c.c_eselon_iv=b.c_eselon_iv
                and c.c_eselon_v=b.c_eselon_v
				and b.c_satker='$unitkr'
				and c_setuju_statustu is null
				order by i_inv_ajuanperbaikan
			    limit $xLimit offset $xOffset";
			 $result = $db->fetchAll($sql); 
			 $jmlResult = count($result);
		     
		
		    if($jmlResult > 0){
		       for ($j = 0; $j < $jmlResult; $j++) {
		      
              $hasilAkhir[$j] = array("i_inv_ajuanperbaikan"           =>(string)$result[$j]->i_inv_ajuanperbaikan,
								   "d_inv_ajuanperbaikan"           =>(string)$result[$j]->d_inv_ajuanperbaikan,
								   "i_orgb"           =>(string)$result[$j]->i_orgb,
								   "n_orgb"           =>(string)$result[$j]->n_unitkerja);
								  
								  
							       
		
		      }
           
       		
	         
		    }else{
				return 0;
			}
         }
         return $hasilAkhir;		 
	   }catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	  }
	}	
	public function getPerbaikanListView4($pageNumber,$itemPerPage,$unitkr, $unitkr1) {
        
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = 'N';
		 $where[] = $unitkr;
		 $where[] = $unitkr1;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	  FROM e_ast_ajuanpbaikinv_0_tm a,sdm.tr_unitkerja b 
								  where a.i_orgb = b.c_satker and c_setuju_statuskabag is null 
								  and c_setuju_statustu = ?
								  and (a.i_orgb like ? or a.i_orgb like ?)",$where);
			 				   
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		 
			 $result = $db->fetchAll("SELECT i_inv_ajuanperbaikan,d_inv_ajuanperbaikan,a.i_orgb,n_unitkerja
		                       	 FROM aset.tm_ajuanperbaikan_0 a,sdm.tr_unitkerja b 
								 where a.i_orgb = b.c_satker and c_setuju_statuskabag is null 
								 and c_setuju_statustu = ?
								 and (a.i_orgb like ? or a.i_orgb like ?) 
								 order by i_inv_ajuanperbaikan
								 limit $xLimit offset $xOffset",$where); 
			 $jmlResult = count($result);
		     
		
		    if($jmlResult > 0){
		       for ($j = 0; $j < $jmlResult; $j++) {
		      
              $hasilAkhir[$j] = array("i_inv_ajuanperbaikan"           =>(string)$result[$j]->i_inv_ajuanperbaikan,
								   "d_inv_ajuanperbaikan"           =>(string)$result[$j]->d_inv_ajuanperbaikan,
								   "i_orgb"           =>(string)$result[$j]->i_orgb,
								   "n_orgb"           =>(string)$result[$j]->n_unitkerja);
								  
								  
							       
		
		      }
           
       		
	         
		    }else{
				return 0;
			}
         }
         return $hasilAkhir;		 
	   }catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	  }
	}
	public function getPerbaikanListView5($pageNumber,$itemPerPage,$unitkr, $unitkr1) {
        
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = 'Y';
		 $where[] = $unitkr;
		 $where[] = $unitkr1;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
		    $sql="SELECT count(*)
				FROM aset.tm_ajuanperbaikan_0 a,sdm.tr_unitkerja b,sdm.tm_pegawai c
				where a.i_orgb = c.i_peg_nip
                and c.c_lokasi_unitkerja=b.c_lokasi_unitkerja
                and c.c_eselon_i=b.c_eselon_i				
                and c.c_eselon_ii=b.c_eselon_ii				
                and c.c_eselon_iii=b.c_eselon_iii				
                and c.c_eselon_iv=b.c_eselon_iv				
                and c.c_eselon_v=b.c_eselon_v
                and b.c_satker='$unitkr'				
				and c_setuju_statuskabag = 'Y'
				and not exists(select * from aset.tm_serahperbaikan_0 c
				where a.i_inv_ajuanperbaikan = c.i_inv_ajuanperbaikan)
				";
			$hasilAkhir = $db->fetchOne($sql);
			 				   
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		     $sql="SELECT i_inv_ajuanperbaikan,d_inv_ajuanperbaikan,a.i_orgb,n_unitkerja
				FROM aset.tm_ajuanperbaikan_0 a,sdm.tr_unitkerja b,sdm.tm_pegawai c
				where a.i_orgb = c.i_peg_nip
                and c.c_lokasi_unitkerja=b.c_lokasi_unitkerja
                and c.c_eselon_i=b.c_eselon_i				
                and c.c_eselon_ii=b.c_eselon_ii				
                and c.c_eselon_iii=b.c_eselon_iii				
                and c.c_eselon_iv=b.c_eselon_iv				
                and c.c_eselon_v=b.c_eselon_v
                and b.c_satker='$unitkr'				
				and c_setuju_statuskabag = 'Y'
				and not exists(select * from aset.tm_serahperbaikan_0 c
				where a.i_inv_ajuanperbaikan = c.i_inv_ajuanperbaikan)
				order by i_inv_ajuanperbaikan
				limit $xLimit offset $xOffset";
			 $result = $db->fetchAll($sql); 
			 $jmlResult = count($result);
		     
		
		    if($jmlResult > 0){
		       for ($j = 0; $j < $jmlResult; $j++) {
		      
              $hasilAkhir[$j] = array("i_inv_ajuanperbaikan"           =>(string)$result[$j]->i_inv_ajuanperbaikan,
								   "d_inv_ajuanperbaikan"           =>(string)$result[$j]->d_inv_ajuanperbaikan,
								   "i_orgb"           =>(string)$result[$j]->i_orgb,
								   "n_orgb"           =>(string)$result[$j]->n_unitkerja);
								  
								  
							       
		
		      }
           
       		
	         
		    }else{
				return 0;
			}
         }
         return $hasilAkhir;		 
	   }catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	  }
	}
	public function getPerbaikanListView6($pageNumber,$itemPerPage,$unitkr, $unitkr1) {
        
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = 'N';
		 $where[] = $unitkr;
		 $where[] = $unitkr1;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
		    $sql="SELECT count(*)
				FROM aset.tm_ajuanperbaikan_0 a,sdm.tr_unitkerja b,sdm.tm_pegawai c 
				where a.i_orgb = c.i_peg_nip
                and c.c_lokasi_unitkerja=b.c_lokasi_unitkerja
                and c.c_eselon_i=b.c_eselon_i				
                and c.c_eselon_ii=b.c_eselon_ii				
                and c.c_eselon_iii=b.c_eselon_iii				
                and c.c_eselon_iv=b.c_eselon_iv				
                and c.c_eselon_v=b.c_eselon_v
                and b.c_satker='$unitkr'				
				and c_setuju_statuskabag = 'N'";
			$hasilAkhir = $db->fetchOne($sql);
			 				   
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		     $sql="SELECT i_inv_ajuanperbaikan,d_inv_ajuanperbaikan,a.i_orgb,n_unitkerja
				FROM aset.tm_ajuanperbaikan_0 a,sdm.tr_unitkerja b,sdm.tm_pegawai c 
				where a.i_orgb = c.i_peg_nip
                and c.c_lokasi_unitkerja=b.c_lokasi_unitkerja
                and c.c_eselon_i=b.c_eselon_i				
                and c.c_eselon_ii=b.c_eselon_ii				
                and c.c_eselon_iii=b.c_eselon_iii				
                and c.c_eselon_iv=b.c_eselon_iv				
                and c.c_eselon_v=b.c_eselon_v
                and b.c_satker='$unitkr'				
				and c_setuju_statuskabag = 'N'
				order by i_inv_ajuanperbaikan
				limit $xLimit offset $xOffset";
			 $result = $db->fetchAll($sql); 
			 $jmlResult = count($result);
		     
		
		    if($jmlResult > 0){
		       for ($j = 0; $j < $jmlResult; $j++) {
		      
              $hasilAkhir[$j] = array("i_inv_ajuanperbaikan"           =>(string)$result[$j]->i_inv_ajuanperbaikan,
								   "d_inv_ajuanperbaikan"           =>(string)$result[$j]->d_inv_ajuanperbaikan,
								   "i_orgb"           =>(string)$result[$j]->i_orgb,
								   "n_orgb"           =>(string)$result[$j]->n_unitkerja);
								  
								  
							       
		
		      }
           
       		
	         
		    }else{
				return 0;
			}
         }
         return $hasilAkhir;		 
	   }catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	  }
	}
	public function getPerbaikanListView7($pageNumber,$itemPerPage,$unitkr,$unitkr1) {
        
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $where[] = $unitkr;
		 $where[] = $unitkr1;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
		    $sql="SELECT count(*)
				FROM aset.tm_ajuanperbaikan_0 a,sdm.tr_unitkerja b,sdm.tm_pegawai c
				where a.i_orgb = c.i_peg_nip
                and c.c_lokasi_unitkerja=b.c_lokasi_unitkerja
                and c.c_eselon_i=b.c_eselon_i				
                and c.c_eselon_ii=b.c_eselon_ii				
                and c.c_eselon_iii=b.c_eselon_iii				
                and c.c_eselon_iv=b.c_eselon_iv				
                and c.c_eselon_v=b.c_eselon_v				
				and not exists(select * from aset.tm_rincibiayaperbaikan c
				where a.i_inv_ajuanperbaikan = c.i_inv_ajuanperbaikan)
				";
			$hasilAkhir = $db->fetchOne($sql);
			 				   
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		      $sql="SELECT i_inv_ajuanperbaikan,d_inv_ajuanperbaikan,a.i_orgb,n_unitkerja
				FROM aset.tm_ajuanperbaikan_0 a,sdm.tr_unitkerja b,sdm.tm_pegawai c
				where a.i_orgb = c.i_peg_nip
                and c.c_lokasi_unitkerja=b.c_lokasi_unitkerja
                and c.c_eselon_i=b.c_eselon_i				
                and c.c_eselon_ii=b.c_eselon_ii				
                and c.c_eselon_iii=b.c_eselon_iii				
                and c.c_eselon_iv=b.c_eselon_iv				
                and c.c_eselon_v=b.c_eselon_v				
				and not exists(select * from aset.tm_rincibiayaperbaikan c
				where a.i_inv_ajuanperbaikan = c.i_inv_ajuanperbaikan)
				order by i_inv_ajuanperbaikan
				limit $xLimit offset $xOffset
				";
			 $result = $db->fetchAll($sql); 
			 $jmlResult = count($result);
		     
		
		    if($jmlResult > 0){
		       for ($j = 0; $j < $jmlResult; $j++) {
		      
              $hasilAkhir[$j] = array("i_inv_ajuanperbaikan"           =>(string)$result[$j]->i_inv_ajuanperbaikan,
								   "d_inv_ajuanperbaikan"           =>(string)$result[$j]->d_inv_ajuanperbaikan,
								   "i_orgb"           =>(string)$result[$j]->i_orgb,
								   "n_orgb"           =>(string)$result[$j]->n_unitkerja);
								  
								  
							       
		
		      }
           
       		
	         
		    }else{
				return 0;
			}
         }
         return $hasilAkhir;		 
	   }catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	  }
	}
	public function getPerbaikanListView8($pageNumber,$itemPerPage,$unitkr, $unitkr1) {
        
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $where[] = $unitkr;
		 $where[] = $unitkr1;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {   
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	  FROM aset.tm_rincibiayaperbaikan a,sdm.tr_unitkerja b 
								  where substr(i_inv_ajuanperbaikan,1,6) = b.c_satker  
								  and  exists(select * from aset.tm_serahperbaikan_0 c,sdm.tm_pegawai e,sdm.tm_pegawai f
								  where a.i_inv_ajuanperbaikan = c.i_inv_ajuanperbaikan
                                  and c.i_peg_nipterima=e.i_peg_nip
                                  and e.c_lokasi_unitkerja=b.c_lokasi_unitkerja
                                  and e.c_eselon_i=b.c_eselon_i								  
                                  and e.c_eselon_ii=b.c_eselon_ii								  
                                  and e.c_eselon_iii=b.c_eselon_iii								  
                                  and e.c_eselon_iv=b.c_eselon_iv								  
                                  and e.c_eselon_v=b.c_eselon_v
								  and c.i_peg_nippemberi=f.i_peg_nip
                                  and f.c_lokasi_unitkerja=b.c_lokasi_unitkerja
                                  and f.c_eselon_i=b.c_eselon_i								  
                                  and f.c_eselon_ii=b.c_eselon_ii								  
                                  and f.c_eselon_iii=b.c_eselon_iii								  
                                  and f.c_eselon_iv=b.c_eselon_iv								  
                                  and f.c_eselon_v=b.c_eselon_v
                                  and b.c_satker='$unitkr'								  
								  and not exists(select * from aset.tm_pengembalian_0 d
								  where c.i_inv_serahperbaikan = d.i_barang_serah))
								  and (substr(i_inv_ajuanperbaikan,1,6) like ? or 
								  substr(i_inv_ajuanperbaikan,1,6) like ?)",$where);
			 				     
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		 
			 $result = $db->fetchAll("SELECT distinct a.i_inv_ajuanperbaikan,d.d_inv_ajuanperbaikan,d.i_orgb,n_unitkerja
							   	  FROM aset.tm_rincibiayaperbaikan a,sdm.tr_unitkerja b, aset.tm_ajuanperbaikan_0 d 
								  where substr(a.i_inv_ajuanperbaikan,1,6) = b.c_satker
								  and a.i_inv_ajuanperbaikan = d.i_inv_ajuanperbaikan
                                  and (substr(a.i_inv_ajuanperbaikan,1,6) like ? or substr(a.i_inv_ajuanperbaikan,1,6) like ?)
								  and  exists(select * from aset.tm_serahperbaikan_0 c,sdm.tm_pegawai e,sdm.tm_pegawai f
								  where a.i_inv_ajuanperbaikan = c.i_inv_ajuanperbaikan
                                  and c.i_peg_nipterima=e.i_peg_nip
                                  and e.c_lokasi_unitkerja=b.c_lokasi_unitkerja
                                  and e.c_eselon_i=b.c_eselon_i								  
                                  and e.c_eselon_ii=b.c_eselon_ii								  
                                  and e.c_eselon_iii=b.c_eselon_iii								  
                                  and e.c_eselon_iv=b.c_eselon_iv								  
                                  and e.c_eselon_v=b.c_eselon_v
								  and c.i_peg_nippemberi=f.i_peg_nip
                                  and f.c_lokasi_unitkerja=b.c_lokasi_unitkerja
                                  and f.c_eselon_i=b.c_eselon_i								  
                                  and f.c_eselon_ii=b.c_eselon_ii								  
                                  and f.c_eselon_iii=b.c_eselon_iii								  
                                  and f.c_eselon_iv=b.c_eselon_iv								  
                                  and f.c_eselon_v=b.c_eselon_v
                                  and b.c_satker='$unitkr'									  
								  and not exists(select * from aset.tm_pengembalian_0 d
								  where c.i_inv_serahperbaikan = d.i_barang_serah))
								  order by a.i_inv_ajuanperbaikan
								  limit $xLimit offset $xOffset",$where); 
			 $jmlResult = count($result);
		     
		
		    if($jmlResult > 0){
		       for ($j = 0; $j < $jmlResult; $j++) {
		      
              $hasilAkhir[$j] = array("i_inv_ajuanperbaikan"           =>(string)$result[$j]->i_inv_ajuanperbaikan,
								   "d_inv_ajuanperbaikan"           =>(string)$result[$j]->d_inv_ajuanperbaikan,
								   "i_orgb"           =>(string)$result[$j]->i_orgb,
								   "n_orgb"           =>(string)$result[$j]->n_unitkerja);
								  
								  
							       
		
		      }
           
       		
	         
		    }else{
				return 0;
			}
         }
         return $hasilAkhir;		 
	   }catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	  }
	}
	public function getPerbaikanListView9($pageNumber,$itemPerPage,$unitkr,$unitkr1) {
        
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $where[] = $unitkr;
		 //$where[] = $unitkr1;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
										FROM aset.tm_pengembalian_0 a,sdm.tr_unitkerja b, aset.tm_serahperbaikan_0 c,sdm.tm_pegawai d,
                                        sdm.tm_pegawai e										
										where substr(i_inv_ajuanperbaikan,1,6) = b.c_satker  
										and a.i_barang_serah = c.i_inv_serahperbaikan
										and c.i_peg_nippemberi=d.i_peg_nip
										and d.c_lokasi_unitkerja=b.c_lokasi_unitkerja
										and d.c_eselon_i=b.c_eselon_i
										and d.c_eselon_ii=b.c_eselon_ii
										and d.c_eselon_iii=b.c_eselon_iii
										and d.c_eselon_iv=b.c_eselon_iv
										and d.c_eselon_v=b.c_eselon_v
										and c.i_peg_nipterima=e.i_peg_nip
										and e.c_lokasi_unitkerja=b.c_lokasi_unitkerja
										and e.c_eselon_i=b.c_eselon_i
										and e.c_eselon_ii=b.c_eselon_ii
										and e.c_eselon_iii=b.c_eselon_iii
										and e.c_eselon_iv=b.c_eselon_iv
										and e.c_eselon_v=b.c_eselon_v
										and b.c_satker = '$unitkr'");
			 				   
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	 
		 
			 $result = $db->fetchAll("SELECT i_barang_pengembalian,d_barang_pengembalian,b.c_satker,n_unitkerja,
									i_inv_ajuanperbaikan
									FROM aset.tm_pengembalian_0 a,sdm.tr_unitkerja b, aset.tm_serahperbaikan_0 c,sdm.tm_pegawai d,
                                    sdm.tm_pegawai e																		 
									where substr(i_inv_ajuanperbaikan,1,6) = b.c_satker  
									and a.i_barang_serah = c.i_inv_serahperbaikan
									and c.i_peg_nippemberi=d.i_peg_nip
									and d.c_lokasi_unitkerja=b.c_lokasi_unitkerja
									and d.c_eselon_i=b.c_eselon_i
									and d.c_eselon_ii=b.c_eselon_ii
									and d.c_eselon_iii=b.c_eselon_iii
									and d.c_eselon_iv=b.c_eselon_iv
									and d.c_eselon_v=b.c_eselon_v
									and c.i_peg_nipterima=e.i_peg_nip
									and e.c_lokasi_unitkerja=b.c_lokasi_unitkerja
									and e.c_eselon_i=b.c_eselon_i
									and e.c_eselon_ii=b.c_eselon_ii
									and e.c_eselon_iii=b.c_eselon_iii
									and e.c_eselon_iv=b.c_eselon_iv
									and e.c_eselon_v=b.c_eselon_v
									and b.c_satker like '$unitkr'
									order by i_barang_pengembalian
									limit $xLimit offset $xOffset"); 
			 $jmlResult = count($result);
		     
		
		    if($jmlResult > 0){
		       for ($j = 0; $j < $jmlResult; $j++) {
		      
              $hasilAkhir[$j] = array("i_inv_ajuanperbaikan"           =>(string)$result[$j]->i_inv_ajuanperbaikan,
			                       "i_barang_pengembalian"           =>(string)$result[$j]->i_barang_pengembalian,
								   "d_inv_ajuanperbaikan"           =>(string)$result[$j]->d_barang_pengembalian,
								   "i_orgb"           =>(string)$result[$j]->c_satker,
								   "n_orgb"           =>(string)$result[$j]->n_unitkerja);
								  
								  
							       
		
		      }
           
       		
	         
		    }else{
				return 0;
			}
         }
         return $hasilAkhir;		 
	   }catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	  }
	}
	public function getListPegawaiByUnit($pageNumber,$itemPerPage,$kdunit) {
		 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   $where[] = $kdunit;
	   $where[] = $kdunit;
	   $where[] = $kdunit;
	   $where[] = $kdunit;
	   //echo " kdunti = ".$kdunit;
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 // union
									// select a.i_peg_nip, n_peg, NULL, a.c_lokasi_unitkerja 
									// from  sdm.tm_pegawai A,sdm.tr_unitkerja c
									// where  
									// not EXISTS(select * from  sdm.tr_jabatan B
									          // where a.c_jabatan = b.c_jabatan)
								    // and a.c_lokasi_unitkerja = c.c_lokasi_unitkerja
									// and a.c_eselon_i=c.c_eselon_i									
                                    // and a.c_eselon_ii=c.c_eselon_ii									
                                    // and a.c_eselon_iii=c.c_eselon_iii								
                                    // and a.c_eselon_iv=c.c_eselon_iv									
                                    // and a.c_eselon_v=c.c_eselon_v
									// and c.c_satker = ?
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
		    $sql="SELECT a.i_peg_nip,a.n_peg,b.n_jabatan,a.c_lokasi_unitkerja
				FROM sdm.tm_pegawai A, sdm.tr_jabatan B,sdm.tr_unitkerja C
				where 
				a.c_jabatan = b.c_jabatan
				and a.c_lokasi_unitkerja = c.c_lokasi_unitkerja	
				and a.c_eselon_i=c.c_eselon_i									
				and a.c_eselon_ii=c.c_eselon_ii									
				and a.c_eselon_iii=c.c_eselon_iii								
				and a.c_eselon_iv=c.c_eselon_iv									
				and a.c_eselon_v=c.c_eselon_v									
				and c.c_satker='$kdunit'";
				
			$hasil = $db->fetchAll($sql);
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
									// union
									// select a.i_peg_nip, n_peg, NULL, a.c_lokasi_unitkerja 
									// from  sdm.tm_pegawai A,sdm.tr_unitkerja C
									// where  
									// not EXISTS(select * from  sdm.tr_jabatan B
									          // where a.c_jabatan = b.c_jabatan)
									// and a.c_lokasi_unitkerja = c.c_lokasi_unitkerja
									// and a.c_eselon_i=c.c_eselon_i									
                                    // and a.c_eselon_ii=c.c_eselon_ii									
                                    // and a.c_eselon_iii=c.c_eselon_iii								
                                    // and a.c_eselon_iv=c.c_eselon_iv									
                                    // and a.c_eselon_v=c.c_eselon_v
									// and c.c_satker = ?
			$sql="SELECT a.i_peg_nip,a.n_peg,b.n_jabatan,a.c_lokasi_unitkerja
				FROM sdm.tm_pegawai A, sdm.tr_jabatan B,sdm.tr_unitkerja c
				where 
				a.c_jabatan = b.c_jabatan
				and a.c_lokasi_unitkerja = c.c_lokasi_unitkerja
				and a.c_eselon_i=c.c_eselon_i									
				and a.c_eselon_ii=c.c_eselon_ii									
				and a.c_eselon_iii=c.c_eselon_iii								
				and a.c_eselon_iv=c.c_eselon_iv									
				and a.c_eselon_v=c.c_eselon_v									
				and c.c_satker='$kdunit'									
				order by n_peg 
				limit $xLimit offset $xOffset";
			$result = $db->fetchAll($sql);
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_peg_nip"         =>(string)$result[$j]->i_peg_nip,
									"n_peg"           	 	=>(string)$result[$j]->n_peg,
									"n_jabatan"          	=>(string)$result[$j]->n_jabatan,
									"i_orgb"            	=>(string)$result[$j]->c_lokasi_unitkerja
									);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	 
	 public function getBarangPerbaikanByNoPengembalian($kode) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchAll("select a.i_barang_serah,a.i_barang_pengembalian,to_char(d_barang_pengembalian,'dd-mm-yyyy') as d_barang_pengembalian,
								  b.d_anggaran,b.c_barang,b.i_aset,ur_sskel,e_inv_kegiatanperbakian, b.d_perolehan		 
								  from aset.tm_pengembalian_0 a, aset.tm_pengembalian_item b ,aset.tm_sskel c, 
								  aset.tm_rincibiayaperbaikan d, aset.tm_serahperbaikan_0 e
								  where   a.i_barang_pengembalian = '$kode' 
								    and a.i_barang_pengembalian = b.i_barang_pengembalian 
								    and d.i_inv_ajuanperbaikan = e.i_inv_ajuanperbaikan
									and e.i_inv_serahperbaikan = a.i_barang_serah
									and b.C_BARANG = d.C_BARANG
									and b.d_anggaran = d.d_anggaran
									and b.i_aset = d.i_aset
									and substr(b.C_BARANG,1,1) = c.kd_gol
									and substr(b.C_BARANG,2,2) = c.kd_bid 
									and substr(b.C_BARANG,4,2) = c.kd_kel
									and substr(b.C_BARANG,6,2) = c.kd_skel
									and substr(b.C_BARANG,8,3) = c.kd_sskel");
										  
											
										  
								           
								
								

		$jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++) {

           $data[$j] = array("i_inv_serahperbaikan" =>(string)$result[$j]->i_inv_serahperbaikan,
								"i_barang_pengembalian" =>(string)$result[$j]->i_barang_pengembalian,
								"d_barang_pengembalian" =>(string)$result[$j]->d_barang_pengembalian,
								"d_anggaran" =>(string)$result[$j]->d_anggaran,
								"c_barang" =>(string)$result[$j]->c_barang,
								"i_aset" =>(string)$result[$j]->i_aset,
								"d_oleh" =>(string)$result[$j]->d_perolehan,
								"e_inv_kegiatanperbakian" =>(string)$result[$j]->e_inv_kegiatanperbakian,
								"ur_sskel" =>(string)$result[$j]->ur_sskel);
								
									
		 }					 
	     return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	} 
	
	public function updatePBarangKembaliItem(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {	     
		 $pengajuanlist = $this->getAstPerbaikanBrgKembaliByKode($data['nopeng']);
	     $jmllistpbrg= count($pengajuanlist);		 
		 for ($j = 0; $j < $jmllistpbrg; $j++) {	       
		      $db->beginTransaction();		      
			  $kodeKembali ='Y';
			  $update_P_Barang_Item = array("c_barang_kembali"=>$data['c_barang_kembali']);	 
			  $where[] = "i_inv_serahperbaikan = '".$data['i_barang_serah'].
			            "' and 
						 d_anggaran = '".$pengajuanlist[$j]['d_anggaran']."' and 
												c_barang = '".$pengajuanlist[$j]['c_barang']."' and 
												i_aset = '".$pengajuanlist[$j]['i_aset']."'";
		       $db->update('aset.tm_serahperbaikan_item',$update_P_Barang_Item, $where);
			   $db->commit();  
		 };	 		 
		 $_hasil = array("rcNumber"=>"1",
		                 "rcDesc"  =>"Proses Sukses");
	     return 'sukses ';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
	
	
	function getNamaPegawai($nip)
	{
		try {
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
		    $where[]=$nip;
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$sql="SELECT a.i_peg_nip,a.n_peg,a.c_lokasi_unitkerja, b.n_unitkerja,a.c_jabatan,c.n_jabatan
				FROM sdm.tm_pegawai A, sdm.tr_unitkerja B,sdm.tr_jabatan c
				where a.i_peg_nip = '$nip'	
				and a.c_lokasi_unitkerja = b.c_lokasi_unitkerja
				and a.c_eselon_i=b.c_eselon_i
				and a.c_eselon_ii=b.c_eselon_ii
				and a.c_eselon_iii=b.c_eselon_iii
				and a.c_eselon_iv=b.c_eselon_iv
				and a.c_eselon_v=b.c_eselon_v
				and a.c_jabatan=c.c_jabatan";
			//echo $sql;
			$result = $db->fetchAll($sql);									
			$jmlResult = count($result);
			//print_r($result);
			for($j=0; $j<$jmlResult; $j++)
			{
			    // $cjabatan=$result[$j]->c_jabatan;
				
				// $n_jabatan 				= $db->fetchAll("select n_jabatan  
														// from  sdm.tr_jabatan 
														// where c_jabatan = '$cjabatan'");	
				// echo "nama jabatan : ".$n_jabatan[0]->n_jabatan;									
				$hasilResult[$j] = array("n_peg"		=>(string)$result[$j]->n_peg,
										 "i_peg_nip"		=>(string)$result[$j]->i_peg_nip,
										 "n_jabatan" 	=>(string)$result[$j]->n_jabatan,
										 "n_orgb"	=>(string)$result[$j]->n_unitkerja,
										 "c_unit_kerja"	=>(string)$result[$j]->c_lokasi_unitkerja
										 );
			}
			
			return $hasilResult;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	
}
?>