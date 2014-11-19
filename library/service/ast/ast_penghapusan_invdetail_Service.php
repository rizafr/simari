<?php
class ast_penghapusan_invdetail_service {
   
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

 
    //=========================================15 nop 07= penghapusan==========================================================
	public function getCariNamaBrg($pageNumber, $itemPerPage,$namaBarang) {
       $status='A';
	  // echo '$namaBarang'.$namaBarang;
	   $nbrg = '%'.$namaBarang.'%';
	   //echo '$nbrg'.$nbrg;
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		{
			$hasilAkhir = $db->fetchOne("SELECT count(*)
									FROM aset.tm_sskel b, aset.tm_masterhm c
									where  substr(c.kd_brg,1,1) = b.kd_gol
									and substr(c.kd_brg,2,2) = b.kd_bid 
									and substr(c.kd_brg,4,2) = b.kd_kel
									and substr(c.kd_brg,6,2) = b.kd_skel
									and substr(c.kd_brg,8,3) = b.kd_sskel 
									and ur_sskel like ?
									and not exists(select * from aset.tm_ajuanhapusinv_item a
									where  a.d_anggaran=c.thn_ang
									       and a.c_barang =c.kd_brg
									       and a.i_aset= c.no_aset)",$nbrg);
		}
		else
		{
		 $xLimit=$itemPerPage;
		 $xOffset=($pageNumber-1)*$itemPerPage;
		 $result = $db->fetchAll("SELECT c.thn_ang,c.kd_brg,to_char(c.no_aset,'09999') as no_aset, 
									c.tgl_perlh, c.rph_aset,c.keterangan,c.merk_type,'' as i_ruang,
									b.ur_sskel
									FROM aset.tm_sskel b, aset.tm_masterhm c
									where  substr(c.kd_brg,1,1) = b.kd_gol
									and substr(c.kd_brg,2,2) = b.kd_bid 
									and substr(c.kd_brg,4,2) = b.kd_kel
									and substr(c.kd_brg,6,2) = b.kd_skel
									and substr(c.kd_brg,8,3) = b.kd_sskel 
									and ur_sskel like ?
									and not exists(select * from aset.tm_ajuanhapusinv_item a
									where  a.d_anggaran=c.thn_ang
									       and a.c_barang =c.kd_brg
									       and a.i_aset= c.no_aset)
									order by thn_ang,kd_brg,no_aset	   
									limit $xLimit offset $xOffset",$nbrg);
								
	     $jmlResult = count($result);
		 //echo '$jmlResult '.$jmlResult ;
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("thn_ang"      		=>(string)$result[$j]->thn_ang,
								   "kd_brg"      		=>(string)$result[$j]->kd_brg,
								   "no_aset"      		=>(string)$result[$j]->no_aset,
								   "tgl_perlh"      	=>(string)$result[$j]->tgl_perlh,
								   "rph_aset"      		=>(string)$result[$j]->rph_aset,
								   "keterangan"      	=>(string)$result[$j]->keterangan,
								   "merk_type"      	=>(string)$result[$j]->merk_type,
								   "i_ruang"      		=>(string)$result[$j]->i_ruang,
								   "ur_sskel"      		=>(string)$result[$j]->ur_sskel);
							       
		
				}
			}
		}		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	public function getCariNamaBrgQ($namaBarang) {
       $status='A';
	  // echo '$namaBarang'.$namaBarang;
	   $nbrg = '%'.$namaBarang.'%';
	   //echo '$nbrg'.$nbrg;
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
		 $result = $db->fetchAll("SELECT thn_ang,c.kd_brg,to_char(no_aset,'09999') as no_aset, 
									tgl_perlh, rph_aset,keterangan,merk_type,'' as i_ruang,
									ur_sskel
									FROM aset.tm_sskel b, aset.tm_masterhm c
									where  substr(c.kd_brg,1,1) = b.kd_gol
									and substr(c.kd_brg,2,2) = b.kd_bid 
									and substr(c.kd_brg,4,2) = b.kd_kel
									and substr(c.kd_brg,6,2) = b.kd_skel
									and substr(c.kd_brg,8,3) = b.kd_sskel 
									and ur_sskel like ? 
									and not exists(select * from aset.tm_ajuanhapusinv_item a
									where  a.d_anggaran=c.thn_ang
									       and a.c_barang =c.kd_brg
									       and a.i_aset= c.no_aset)
									order by thn_ang,kd_brg,no_aset",$nbrg);
								
	     $jmlResult = count($result);
		 //echo '$jmlResult '.$jmlResult ;
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("thn_ang"      		=>(string)$result[$j]->thn_ang,
								   "kd_brg"      		=>(string)$result[$j]->kd_brg,
								   "no_aset"      		=>(string)$result[$j]->no_aset,
								   "tgl_perlh"      	=>(string)$result[$j]->tgl_perlh,
								   "rph_aset"      		=>(string)$result[$j]->rph_aset,
								   "keterangan"      	=>(string)$result[$j]->keterangan,
								   "merk_type"      	=>(string)$result[$j]->merk_type,
								   "i_ruang"      		=>(string)$result[$j]->i_ruang,
								   "ur_sskel"      		=>(string)$result[$j]->ur_sskel);
							       
		
				}
			}
		 		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getCariByKdAset($pageNumber, $itemPerPage,array $data) {
       
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	   $a=trim($data['kdgol']);
	   //echo '$a'.$a;
	   
		 $where[] = trim($data['kdgol']);
		 $where[] = trim($data['kdbid']);
		 $where[] = trim($data['kdkel']);
		 $where[] = trim($data['kdskel']);
		 $where[] = trim($data['kdsskel']);

		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
         if(($pageNumber==0) && ($itemPerPage==0))
		{
			$hasilAkhir = $db->fetchOne("SELECT count(*)
									FROM aset.tm_sskel b, aset.tm_masterhm c
									where  substr(c.kd_brg,1,1) = b.kd_gol
									and substr(c.kd_brg,2,2) = b.kd_bid 
									and substr(c.kd_brg,4,2) = b.kd_kel
									and substr(c.kd_brg,6,2) = b.kd_skel
									and substr(c.kd_brg,8,3) = b.kd_sskel 
									and b.kd_gol = ? and b.kd_bid=? and b.kd_kel =? and b.kd_skel =? and b.kd_sskel=? 
									and not exists (select thn_ang,kd_brg,no_aset from aset.tm_ajuanhapusinv_item a,aset.tm_masterhm b
													where  a.d_anggaran = b.thn_ang
													    and a.c_barang = b.kd_brg 
													    and to_char(a.i_aset,'09999')= b.no_aset)",$where);
		}
		else
		{
		 $xLimit=$itemPerPage;
		 $xOffset=($pageNumber-1)*$itemPerPage; 		 
		 $result = $db->fetchAll("SELECT thn_ang,kd_brg,to_char(no_aset,'09999') as no_aset, 
									tgl_perlh, rph_aset,keterangan,merk_type,'' as i_ruang,
									ur_sskel
									FROM aset.tm_sskel b, aset.tm_masterhm c
									where  substr(c.kd_brg,1,1) = b.kd_gol
									and substr(c.kd_brg,2,2) = b.kd_bid 
									and substr(c.kd_brg,4,2) = b.kd_kel
									and substr(c.kd_brg,6,2) = b.kd_skel
									and substr(c.kd_brg,8,3) = b.kd_sskel 
									and b.kd_gol = ? and b.kd_bid=? and b.kd_kel =? and b.kd_skel =? and b.kd_sskel=? 
									and not exists (select thn_ang,kd_brg,no_aset from aset.tm_ajuanhapusinv_item a,aset.tm_masterhm b
													where  a.d_anggaran = b.thn_ang
													    and a.c_barang = b.kd_brg 
													    and to_char(a.i_aset,'09999')= b.no_aset)
														limit $xLimit offset $xOffset",$where);
									
									
								
	     $jmlResult = count($result);
		 //echo '$jmlResult '.$jmlResult ;
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("thn_ang"      		=>(string)$result[$j]->thn_ang,
								   "kd_brg"      		=>(string)$result[$j]->kd_brg,
								   "no_aset"      		=>(string)$result[$j]->no_aset,
								   "tgl_perlh"      	=>(string)$result[$j]->tgl_perlh,
								   "rph_aset"      		=>(string)$result[$j]->rph_aset,
								   "keterangan"      	=>(string)$result[$j]->keterangan,
								   "merk_type"      	=>(string)$result[$j]->merk_type,
								   "i_ruang"      		=>(string)$result[$j]->i_ruang,
								   "ur_sskel"      		=>(string)$result[$j]->ur_sskel);
							       
		
			}
			}
		}		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	public function getCariUnitkrDir($unitkr) {
       $status='A';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 //$where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT d_aset_thnanggar,c_barang,
									to_char(i_aset,'09999') as i_aset, tgl_perlh, 
									i_ruang, keterangan, rph_aset,
									ur_sskel, merk_type,i_orgb_pemberi,e_keterangan
									FROM   aset.tm_dir_item  a, aset.tm_sskel b, aset.tm_masterhm c, aset.tm_dir_0 d
									where  substr(a.c_barang,1,1) = b.kd_gol
									and substr(a.c_barang,2,2) = b.kd_bid 
									and substr(a.c_barang,4,2) = b.kd_kel
									and substr(a.c_barang,6,2) = b.kd_skel
									and substr(a.c_barang,8,3) = b.kd_sskel 
									and a.d_aset_thnanggar=c.thn_ang
									and a.c_barang =c.kd_brg
									and a.i_aset= c.no_aset
									and a.i_barang_serah=d.i_barang_serah
									and i_orgb_pemberi=?",$where);
								
	     $jmlResult = count($result);
		 //echo '$jmlResult '.$jmlResult ;
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("thn_ang"      		=>(string)$result[$j]->d_aset_thnanggar,
								   "kd_brg"      		=>(string)$result[$j]->c_barang,
								   "no_aset"      		=>(string)$result[$j]->i_aset,
								   "tgl_perlh"      	=>(string)$result[$j]->tgl_perlh,
								   "rph_aset"      		=>(string)$result[$j]->rph_aset,
								   "keterangan"      	=>(string)$result[$j]->e_keterangan,
								   "merk_type"      	=>(string)$result[$j]->merk_type,
								   "i_ruang"      		=>(string)$result[$j]->i_ruang,
								   "rph_aset"      		=>(string)$result[$j]->rph_aset,
								   "ur_sskel"      		=>(string)$result[$j]->ur_sskel);
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	public function getCariUnitkrDirNama($unitkr,$nmBarang) {
       $status='A';
	   $nbrg = '%'.$nmBarang.'%';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $nbrg;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT d_aset_thnanggar,c_barang,
									to_char(i_aset,'09999') as i_aset, tgl_perlh, 
									i_ruang, keterangan, rph_aset,
									ur_sskel, merk_type,i_orgb_pemberi,e_keterangan
									FROM   aset.tm_dir_item  a, aset.tm_sskel b, aset.tm_mastehm c, aset.tm_dir_0 d
									where  substr(a.c_barang,1,1) = b.kd_gol
									and substr(a.c_barang,2,2) = b.kd_bid 
									and substr(a.c_barang,4,2) = b.kd_kel
									and substr(a.c_barang,6,2) = b.kd_skel
									and substr(a.c_barang,8,3) = b.kd_sskel 
									and a.d_aset_thnanggar=c.thn_ang
									and a.c_barang =c.kd_brg
									and a.i_aset= c.no_aset
									and a.i_barang_serah=d.i_barang_serah
									and i_orgb_pemberi=? and ur_sskel like ?",$where);
								
	     $jmlResult = count($result);
		 //echo '$jmlResult '.$jmlResult ;
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("thn_ang"      		=>(string)$result[$j]->d_aset_thnanggar,
								   "kd_brg"      		=>(string)$result[$j]->c_barang,
								   "no_aset"      		=>(string)$result[$j]->i_aset,
								   "tgl_perlh"      	=>(string)$result[$j]->tgl_perlh,
								   "rph_aset"      		=>(string)$result[$j]->rph_aset,
								   "keterangan"      	=>(string)$result[$j]->e_keterangan,
								   "merk_type"      	=>(string)$result[$j]->merk_type,
								   "i_ruang"      		=>(string)$result[$j]->i_ruang,
								   "rph_aset"      		=>(string)$result[$j]->rph_aset,
								   "ur_sskel"      		=>(string)$result[$j]->ur_sskel);
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	public function insertAjuanHapusD(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("i_inv_ajuanhapus"      	=>$data['noHapus'],
	                           "d_anggaran"    			=>$data['thnang'],
						       "c_barang"  				=>$data['kdbrg'],
						       "i_aset" 				=>$data['noaset'],
						       "d_perolehan"   			=>$data['tglPerl'],
							   "e_keterangan"   		=>$data['ktr'],
							   "i_entry"       			=>$data['nuser'],
						       "d_entry"       			=>date("Y-m-d"));
		
		
 		 $db->insert('aset.tm_ajuanhapusinv_item',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function queryAjuanHapusD($noHapus) {
       $status='A';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $noHapus;
		 //$where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT i_inv_ajuanhapus,d_anggaran,c_barang,
									to_char(i_aset,'09999') as i_aset, d_perolehan, 
									e_keterangan, rph_aset, 
									ur_sskel, merk_type
									FROM aset.tm_ajuanhapusinv_item a, aset.tm_sskel b, aset.tm_masterhm c
									where  substr(a.c_barang,1,1) = b.kd_gol
									       and substr(a.c_barang,2,2) = b.kd_bid 
									       and substr(a.c_barang,4,2) = b.kd_kel
									       and substr(a.c_barang,6,2) = b.kd_skel
									       and substr(a.c_barang,8,3) = b.kd_sskel 
									       and a.d_anggaran=c.thn_ang
									       and a.c_barang =c.kd_brg
									       and a.i_aset= c.no_aset
										   and i_inv_ajuanhapus = ? 
										   order by d_anggaran,c_barang,i_aset",$where);
									
									
         $jmlResult = count($result);
		 //echo '$jmlResult '.$jmlResult ;
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_inv_ajuanhapus"   =>(string)$result[$j]->i_inv_ajuanhapus,
								   "thn_ang"      		=>(string)$result[$j]->d_anggaran,
								   "kd_brg"      		=>(string)$result[$j]->c_barang,
								   "no_aset"      		=>(string)$result[$j]->i_aset,
								   "tgl_perlh"      	=>(string)$result[$j]->d_perolehan,
								   "keterangan"      	=>(string)$result[$j]->e_keterangan,
								   "ur_sskel"      		=>(string)$result[$j]->ur_sskel,
								   "rph_aset"      		=>(string)$result[$j]->rph_aset,
								   "merk_type"      	=>(string)$result[$j]->merk_type);
							       
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	public function deletAjuanHapusD(array $data) {
	   // echo 'detele service trim($data)'.trim($data['noHapus']);
		 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	 
		 $where[] = "i_inv_ajuanhapus  	=  '".trim($data['noHapus'])."'";
	     $where[] = "d_anggaran        	=  '".trim($data['thnang'])."'";
		 $where[] = "c_barang        		=  '".trim($data['kdbrg'])."'";
		 $where[] = "i_aset        	=  '".trim($data['noaset'])."'";
		 
		 $db->delete('aset.tm_ajuanhapusinv_item', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function deletAjuanHapusMD($noHapus) {
	    
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	 
		 $where[] = "i_inv_ajuanhapus  	=  '".$noHapus."'";
	     
		 $db->delete('aset.tm_ajuanhapusinv_item', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	//==============PERSETUJUAN BIRO==============================================================================
	public function querySetujuHapusD($noHapus) {
       $status='A';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $noHapus;
		 //$where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT a.i_inv_ajuanhapus,d_anggaran,c_barang,
									to_char(i_aset,'09999') as i_aset, d_perolehan, 
									e_keterangan, rph_aset, 
									ur_sskel, merk_type,i_orgb
									FROM aset.tm_ajuanhapusinv_item a, aset.tm_sskel b, aset.tm_masterhm c,aset.tm_ajuanhapusinv d
									where  substr(a.c_barang,1,1) = b.kd_gol
									       and substr(a.c_barang,2,2) = b.kd_bid 
									       and substr(a.c_barang,4,2) = b.kd_kel
									       and substr(a.c_barang,6,2) = b.kd_skel
									       and substr(a.c_barang,8,3) = b.kd_sskel 
									       and a.d_anggaran=c.thn_ang
									       and a.c_barang =c.kd_brg
									       and a.i_aset= c.no_aset
										   and a.i_inv_ajuanhapus=d.i_inv_ajuanhapus
										   and a.i_inv_ajuanhapus = ? ",$where);
									
									
         $jmlResult = count($result);
		 //echo '$jmlResult '.$jmlResult ;
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_inv_ajuanhapus"   =>(string)$result[$j]->i_inv_ajuanhapus,
								   "thn_ang"      		=>(string)$result[$j]->d_anggaran,
								   "kd_brg"      		=>(string)$result[$j]->c_barang,
								   "no_aset"      		=>(string)$result[$j]->i_aset,
								   "tgl_perlh"      	=>(string)$result[$j]->d_perolehan,
								   "keterangan"      	=>(string)$result[$j]->e_keterangan,
								   "ur_sskel"      		=>(string)$result[$j]->ur_sskel,
								   "rph_aset"      		=>(string)$result[$j]->rph_aset,
								   "i_orgb"      		=>(string)$result[$j]->i_orgb,
								   "merk_type"      	=>(string)$result[$j]->merk_type);
							       
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	//==========================================================================================================================
	
	public function updateAjuanPindahD(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $atk_dtl_parm = array("q_barang"   				=>$data['jml'],
							   "i_ruang_baru"   			=>$data['ruang'],
							   "i_ruang"   					=>$data['Nourut'],
							   "e_keterangan"   			=>$data['ktr'],
							   "i_entry"       				=>"ast",
						       "d_entry"       				=>date("Y-m-d"));
	     
		 $where[] = "i_barang_ajuanpindah  =  '".trim($data['noPindah'])."'";
	     $where[] = "d_anggaran        =  '".trim($data['thnang'])."'";
		 $where[] = "c_barang   =  '".trim($data['kdbrg'])."'";
		 $where[] = "i_aset_awal   =  '".trim($data['noaset'])."'";
		 $where[] = "d_perolehan   =  '".trim($data['tglPerl'])."'";
		 $db->update('aset.tm_ajuanpindahinv_item',$atk_dtl_parm, $where);
		 
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	//Serah Terima ===========================================================================================
	
	public function updateSerahTrmPindahD(array $data) {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $atk_dtl_parm = array("c_barang_serah"   			=>"Y",
							   "i_entry"       				=>"ast",
						       "d_entry"       				=>date("Y-m-d"));
	     
		 $where[] = "i_barang_ajuanpindah  =  '".trim($data['noPindah'])."'";
	     $where[] = "d_anggaran        =  '".trim($data['thnang'])."'";
		 $where[] = "c_barang   =  '".trim($data['kdbrg'])."'";
		 $where[] = "i_aset_awal   =  '".trim($data['noaset'])."'";
		 $where[] = "d_perolehan   =  '".trim($data['tglPerl'])."'";
		 $db->update('aset.tm_ajuanpindahinv_item',$atk_dtl_parm, $where);
		 
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	} 
	
	
	
	public function insertSerahTrmPindah(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("i_barang_ajuanpindah"      	=>$data['noPindah'],
	                           "i_barang_serah"    			=>$data['noSerahb'],
						       "d_barang_serah"  			=>date("Y-m-d"),
						       "i_peg_nipterima" 			=>$data['nipPenerima'],
						       "i_peg_nippemberi"   		=>$data['nipPemberi'],
							   "i_entry"       				=>"ast",
						       "d_entry"       				=>date("Y-m-d"));
		
		 $db->insert('aset.tm_serahpindahinv_0',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function updateDirItem(array $data) {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $atk_dtl_parm = array("i_ruang"   			=>$data['ruangbaru'],
							   "i_entry"       		=>"ast",
						       "d_entry"       		=>date("Y-m-d"));
	     
		 $where[] = "d_aset_thnanggar        =  '".trim($data['thnang'])."'";
		 $where[] = "c_barang   =  '".trim($data['kdbrg'])."'";
		 $where[] = "i_aset   =  '".trim($data['noaset'])."'";
		 $db->update('aset.tm_dir_item',$atk_dtl_parm, $where);
		 
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	} 
	
	//=========================================================================================================================
	
}
?>