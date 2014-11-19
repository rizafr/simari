<?php
class Ast_peminjamaninv_Service {
   
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
	public function getjabatan($unitkr) {
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try { 
           $where[] = $unitkr;
		   $where[] = $unitkr;
		   $db->setFetchMode(Zend_Db::FETCH_OBJ);
           $hasilAkhir = $db->fetchOne("select count(*)  
										from e_sdm_jabatan_0_tm A,  e_sdm_pegawai_0_tm B
										where a.i_peg_nip = b.i_peg_nip
										and (b.c_unit_kerja = ? or b.i_orgb = ?)
										and b.c_unit_kerja = a.c_jabatan",$where);  
       		
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
	
	
	public function insertAjuanPinjamMaster(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	
	   try {
	     $db->beginTransaction();
		 $atk_mast_prm = array("i_barang_ajuanpinjam"         		    =>$data['nopin'],
	                           "d_barang_ajuanpinjam"    	        	=>$data['d_barang_ajuanpinjam'],
						       "i_orgb"                                 =>$data['i_orgb'],
							   "c_barang_statajuanpinjam"               =>$data['c_barang_statajuanpinjam'],
							   "d_setuju_tu"  	                        =>$data['d_setuju_tu'],
							   "d_setuju_kabag"                         =>$data['d_setuju_kabag'],
							   "c_setuju_statustu"                      =>$data['c_setuju_statustu'],
						       "c_setuju_statuskabag"  	               =>$data['c_setuju_kabag'],
							   "i_entry"       		        			=>$data['i_entry'],
						       "d_entry"       		        			=>date("Y-m-d"));
	    
	     $db->insert('e_ast_ajuanpinjaminv_0_tm',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function insertAjuanPinjamDtl(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
		 $atk_mast_prm = array("i_barang_ajuanpinjam"           =>$data['nopin'],
	                           "d_anggaran"    	                =>$data['d_anggaran'],
						       "c_barang"                       =>$data['c_barang'],
							   "i_aset"              		    =>$data['i_aset'],
							   "d_perolehan"  	                =>$data['d_perolehan'], 
							   "d_barang_pinjam"     			=>$data['d_barang_pinjam'],
							   "d_barang_waktupinjam"           =>$data['d_barang_waktupinjam'],
							   "d_barang_lamapinjam"     	    =>$data['d_barang_lamapinjam'],
							   "e_keterangan"       		    =>$data['e_keterangan'],
							   "c_barang_serah"		            =>$data['c_barang_serah'],
							   "i_entry"       		            =>$data['i_entry'],
						       "d_entry"       		           =>date("Y-m-d"));
	    
	     $db->insert('e_ast_ajuanpinjaminv_item_tm',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	} 
     
		 
	public function DeleteAstPinjTm($nopeng) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $where[] = "i_barang_ajuanpinjam = '". $nopeng ."'";
		 $db->delete('e_ast_ajuanpinjaminv_0_tm', $where);
		 $db->delete('e_ast_ajuanpinjaminv_item_tm', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
     
public function getPinjamanInvList($pageNumber,$itemPerPage,$unitkr) {
       //$status='A';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     //$where[] = $status;
		 $where[] = $unitkr;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	  FROM e_ast_ajuanpinjaminv_0_tm where c_barang_statajuanpinjam in ('A,B')
								  and i_orgb = ?",$where); ;
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		 
			 $result = $db->fetchAll("SELECT i_barang_ajuanpinjam,d_barang_ajuanpinjam
		                       	 FROM e_ast_ajuanpinjaminv_0_tm where c_barang_statajuanpinjam in ('A','B') and i_orgb = ?
								 limit $xLimit offset $xOffset",$where); 
			 $jmlResult = count($result);
		      
		
			if($jmlResult > 0){
			for ($j = 0; $j < $jmlResult; $j++) {
		 
				$hasilAkhir[$j] = array("i_barang_ajuanpinjam"           =>(string)$result[$j]->i_barang_ajuanpinjam,
								   "d_barang_ajuanpinjam"           =>(string)$result[$j]->d_barang_ajuanpinjam);
								  
								  
							       
		
				}
			}
 		}
		  
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	
public function getPinjamanByKey(array $data) {
       $status='T';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	    
	   try {
	    $params =  array();
		$params[] = $data['nopin'];
		$params[] = $data['c_barang'];
		$params[] = $data['i_aset'];
		 
	    $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT d_perolehan,d_barang_pinjam,
		 d_barang_lamapinjam,d_barang_waktupinjam,e_keterangan, merk_type,ur_sskel 
		 FROM e_ast_ajuanpinjaminv_item_tm a ,
		      e_sabm_t_master_tm c,e_ast_sskel_0_tr d
		   where  a.i_aset =  c.no_aset  and  i_barang_ajuanpinjam = ? and c_barang = ? and i_aset = ?  
		          and  a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
								      and substr(c.kd_brg,2,2) = d.kd_bid 
								   	  and substr(c.kd_brg,4,2) = d.kd_kel
									  and substr(c.kd_brg,6,2) = d.kd_skel
									  and substr(c.kd_brg,8,3) = d.kd_sskel",$params);
		
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("d_perolehan"                =>(string)$result[$j]->d_perolehan,
								   "d_barang_pinjam"            =>(string)$result[$j]->d_barang_pinjam,
								   "d_barang_lamapinjam"        =>(string)$result[$j]->d_barang_lamapinjam,
								   "d_barang_waktupinjam"        =>(string)$result[$j]->d_barang_waktupinjam,
								   "e_keterangan"               =>(string)$result[$j]->e_keterangan,
								   "merk_type"                  =>(string)$result[$j]->merk_type,
								   "ur_sskel"                   =>(string)$result[$j]->ur_sskel);
								  
								  
			  			       
		
		 }
        }	
        		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
 public function getPinjamanInvListD($nopeng) {
       $status='T';
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT c_barang,to_char(d_perolehan,'YYYY') as d_anggaran,to_char(no_aset,'09999') as no_aset,d_perolehan,d_barang_pinjam,
		 d_barang_lamapinjam,d_barang_waktupinjam,e_keterangan, merk_type,ur_sskel 
		 FROM e_ast_ajuanpinjaminv_item_tm a ,
		      e_sabm_t_master_tm c,e_ast_sskel_0_tr d
		 where  a.i_aset =  c.no_aset  and a.i_barang_ajuanpinjam = ?
		   and  a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
								      and substr(c.kd_brg,2,2) = d.kd_bid 
								   	  and substr(c.kd_brg,4,2) = d.kd_kel
									  and substr(c.kd_brg,6,2) = d.kd_skel
									  and substr(c.kd_brg,8,3) = d.kd_sskel",$nopeng);
		
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_barang"                   =>(string)$result[$j]->c_barang,
								   "d_anggaran"                 =>(string)$result[$j]->d_anggaran,
								   "no_aset"                    =>(string)$result[$j]->no_aset,
								   "d_perolehan"                =>(string)$result[$j]->d_perolehan,
								   "d_barang_pinjam"            =>(string)$result[$j]->d_barang_pinjam,
								   "d_barang_lamapinjam"        =>(string)$result[$j]->d_barang_lamapinjam,
								   "d_barang_waktupinjam"        =>(string)$result[$j]->d_barang_waktupinjam,
								   "e_keterangan"               =>(string)$result[$j]->e_keterangan,
								   "merk_type"                  =>(string)$result[$j]->merk_type,
								   "ur_sskel"                   =>(string)$result[$j]->ur_sskel);
								  
								  
			  			       
		
		 }
        }	
        		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
public function getPinjamanInvListV($nopeng) {
       $status='T';
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT a.c_barang,to_char(a.d_perolehan,'YYYY') as d_anggaran,to_char(a.i_aset,'09999') as no_aset,a.d_perolehan,a.d_barang_pinjam,
		 a.d_barang_lamapinjam,a.d_barang_waktupinjam,e_keterangan, merk_type,ur_sskel 
		 FROM e_ast_ajuanpinjaminv_item_tm a ,e_ast_serahpinjaminv_0_tm b,
		      e_sabm_t_master_tm c,e_ast_sskel_0_tr d
		 where  a.i_aset =  c.no_aset  and a.i_barang_ajuanpinjam = b.i_barang_ajuanpinjam 
		   and  b.i_barang_serah = ?
		   and  a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
								      and substr(c.kd_brg,2,2) = d.kd_bid 
								   	  and substr(c.kd_brg,4,2) = d.kd_kel
									  and substr(c.kd_brg,6,2) = d.kd_skel
									  and substr(c.kd_brg,8,3) = d.kd_sskel",$nopeng);
		
         $jmlResult = count($result);
	  	 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_barang"                   =>(string)$result[$j]->c_barang,
								   "d_anggaran"                 =>(string)$result[$j]->d_anggaran,
								   "no_aset"                    =>(string)$result[$j]->no_aset,
								   "d_perolehan"                =>(string)$result[$j]->d_perolehan,
								   "d_barang_pinjam"            =>(string)$result[$j]->d_barang_pinjam,
								   "d_barang_lamapinjam"        =>(string)$result[$j]->d_barang_lamapinjam,
								   "d_barang_waktupinjam"        =>(string)$result[$j]->d_barang_waktupinjam,
								   "e_keterangan"               =>(string)$result[$j]->e_keterangan,
								   "merk_type"                  =>(string)$result[$j]->merk_type,
								   "ur_sskel"                   =>(string)$result[$j]->ur_sskel);
								  
								  
			  			       
		
		 }
        }	
        		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
public function getPinjamanInvListK($nopeng) {
       $status='T';
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT a.c_barang,to_char(a.d_perolehan,'YYYY') as d_anggaran,to_char(a.i_aset,'09999') as no_aset,a.d_perolehan,a.d_barang_pinjam,
		 a.d_barang_lamapinjam,a.d_barang_waktupinjam,e_keterangan, merk_type,ur_sskel 
		 FROM e_ast_ajuanpinjaminv_item_tm a ,e_ast_serahpinjaminv_0_tm b,
		      e_sabm_t_master_tm c,e_ast_sskel_0_tr d,e_ast_pengembalianinv_0_tm e
		 where  a.i_aset =  c.no_aset  and a.i_barang_ajuanpinjam = b.i_barang_ajuanpinjam 
		   and  b.i_barang_serah = e.i_barang_serah and i_barang_pengembalian = ?
		   and  a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
								      and substr(c.kd_brg,2,2) = d.kd_bid 
								   	  and substr(c.kd_brg,4,2) = d.kd_kel
									  and substr(c.kd_brg,6,2) = d.kd_skel
									  and substr(c.kd_brg,8,3) = d.kd_sskel",$nopeng);
		
         $jmlResult = count($result);
	  	 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		  
           $hasilAkhir[$j] = array("c_barang"                   =>(string)$result[$j]->c_barang,
								   "d_anggaran"                 =>(string)$result[$j]->d_anggaran,
								   "no_aset"                    =>(string)$result[$j]->no_aset,
								   "d_perolehan"                =>(string)$result[$j]->d_perolehan,
								   "d_barang_pinjam"            =>(string)$result[$j]->d_barang_pinjam,
								   "d_barang_lamapinjam"        =>(string)$result[$j]->d_barang_lamapinjam,
								   "d_barang_waktupinjam"        =>(string)$result[$j]->d_barang_waktupinjam,
								   "e_keterangan"               =>(string)$result[$j]->e_keterangan,
								   "merk_type"                  =>(string)$result[$j]->merk_type,
								   "ur_sskel"                   =>(string)$result[$j]->ur_sskel);
								  
								  
			  			       
		
		 }
        }	
        		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	} 
	public function UpdStsAstPinjTm(array $data) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $personal_parm = array("c_barang_statajuanpinjam"   =>$data['status'],
								 "d_setuju_tu"  	         =>date("Y-m-d"),
								 "c_setuju_statustu"         =>'Y',
						         "i_entry"       		     =>$data['i_entry'],
						         "d_entry"       		     =>date("Y-m-d"));
						  	    
							   
							   
	     $where[] = "i_barang_ajuanpinjam  =  '".$data['nopin']."'";
		
	     $db->update('e_ast_ajuanpinjaminv_0_tm',$personal_parm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
    public function UpdatePinjamItem(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $item_parm = array("d_barang_pinjam"  		            =>$data['d_barang_pinjam'],
						 	"d_barang_lamapinjam"				=>$data['d_barang_lamapinjam'],
						 	"d_barang_waktupinjam"				=>$data['d_barang_waktupinjam'],
							"e_keterangan" 						=>$data['e_keterangan'],
							"i_entry"       		    		=>$data['i_entry'],
						    "d_entry"       		    		=>date("Y-m-d"));
						  	   							   
	      $where[] = "i_barang_ajuanpinjam  =  '".$data['nopin']."'";
		  $where[] = "c_barang        =  '".$data['c_barang']."'";
		  $where[] = "i_aset          =  '".$data['i_aset']."'";
		   
	     $db->update('e_ast_ajuanpinjaminv_item_tm',$item_parm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
   public function DeletePinjamItemAll($nopin) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	      $where[] = "i_barang_ajuanpinjam  =  '".$data['nopin']."'";
		  $db->delete('e_ast_ajuanpinjaminv_item_tm', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function DeletePinjamItem(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	      $where[] = "i_barang_ajuanpinjam  =  '".$data['nopin']."'";
		  $where[] = "c_barang        =  '".$data['c_barang']."'";
		  $where[] = "i_aset          =  '".$data['i_aset']."'";
		  $where[] = "d_anggaran   =  '".$data['d_anggaran']."'";
		 $db->delete('e_ast_ajuanpinjaminv_item_tm', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getSetujuPinjamanInvList($pageNumber,$itemPerPage,$unitkr) {
       $status='B';
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
							   	  FROM e_ast_ajuanpinjaminv_0_tm a,e_org_0_0_tm b 
								  where a.i_orgb = b.i_orgb and c_setuju_statustu is null and
								  c_barang_statajuanpinjam =?  and  a.i_orgb like ?",$where);
			 				   
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		 
			 $result = $db->fetchAll("SELECT i_barang_ajuanpinjam,d_barang_ajuanpinjam,a.i_orgb,n_orgb
		                       	 FROM e_ast_ajuanpinjaminv_0_tm a,e_org_0_0_tm b 
								 where a.i_orgb = b.i_orgb and c_setuju_statustu is null and
								 c_barang_statajuanpinjam=? and  a.i_orgb like ? 
								 limit $xLimit offset $xOffset",$where); 
			 $jmlResult = count($result);
		 
		 
		 
		  
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_barang_ajuanpinjam"           =>(string)$result[$j]->i_barang_ajuanpinjam,
								   "d_barang_ajuanpinjam"           =>(string)$result[$j]->d_barang_ajuanpinjam,
								   "i_orgb"           =>(string)$result[$j]->i_orgb,
								   "n_orgb"           =>(string)$result[$j]->n_orgb);
								  
								  
							       
		
		   }
          }
         }		
	     return $hasilAkhir;
		}else{
				return 0;
			} 
	   }catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	  }
	}
	
	public function getAjuPinjamanInvList($pageNumber,$itemPerPage,$unitkr,$unitkr1) {
        
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
							   	  FROM e_ast_ajuanpinjaminv_0_tm a,e_org_0_0_tm b 
								  where a.i_orgb = b.i_orgb and c_setuju_statustu is null 
								  and c_barang_statajuanpinjam = ?
								  and (a.i_orgb like ? or a.i_orgb like ?)",$where);
			 				   
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		 
			 $result = $db->fetchAll("SELECT i_barang_ajuanpinjam,d_barang_ajuanpinjam,a.i_orgb,n_orgb
		                       	 FROM e_ast_ajuanpinjaminv_0_tm a,e_org_0_0_tm b 
								 where a.i_orgb = b.i_orgb and c_setuju_statustu is null 
								 and c_barang_statajuanpinjam = ?
								 and (a.i_orgb like ? or a.i_orgb like ?)
								 order by i_barang_ajuanpinjam
								 limit $xLimit offset $xOffset",$where); 
			 $jmlResult = count($result);
		    
		
		    if($jmlResult > 0){
		       for ($j = 0; $j < $jmlResult; $j++) {
		      
              $hasilAkhir[$j] = array("i_barang_ajuanpinjam"           =>(string)$result[$j]->i_barang_ajuanpinjam,
								   "d_barang_ajuanpinjam"           =>(string)$result[$j]->d_barang_ajuanpinjam,
								   "i_orgb"           =>(string)$result[$j]->i_orgb,
								   "n_orgb"           =>(string)$result[$j]->n_orgb);
								  
								  
							       
		
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
	public function getAjuPinjamanInvListA($pageNumber,$itemPerPage,$unitkr,$unitkr1) {
       $statustu="A";
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 
		 $where[] = $statustu;
		 $where[] = $unitkr;
		 $where[] = $unitkr1;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	  FROM e_ast_ajuanpinjaminv_0_tm a,e_org_0_0_tm b 
								  where a.i_orgb = b.i_orgb and c_barang_statajuanpinjam = ? 
								  and (a.i_orgb like ? or a.i_orgb like ?)",$where);
			 				   
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		 
			 $result = $db->fetchAll("SELECT i_barang_ajuanpinjam,d_barang_ajuanpinjam,a.i_orgb,n_orgb
		                       	 FROM e_ast_ajuanpinjaminv_0_tm a,e_org_0_0_tm b 
								 where a.i_orgb = b.i_orgb and c_barang_statajuanpinjam = ? 
								 and (a.i_orgb like ? or a.i_orgb like ?) 
								 order by i_barang_ajuanpinjam
								 limit $xLimit offset $xOffset",$where); 
			 $jmlResult = count($result);
		     
		
		    if($jmlResult > 0){
		       for ($j = 0; $j < $jmlResult; $j++) {
		      
              $hasilAkhir[$j] = array("i_barang_ajuanpinjam"           =>(string)$result[$j]->i_barang_ajuanpinjam,
								   "d_barang_ajuanpinjam"           =>(string)$result[$j]->d_barang_ajuanpinjam,
								   "i_orgb"           =>(string)$result[$j]->i_orgb,
								   "n_orgb"           =>(string)$result[$j]->n_orgb);
								  
								  
							       
		
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
	public function getPerlPinjamanInvList($pageNumber,$itemPerPage,$unitkr,$unitkr1) {
       $statustu="Y"; 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $unitkr1;
		 $where[] = $statustu;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	  FROM e_ast_ajuanpinjaminv_0_tm a,e_org_0_0_tm b 
								  where a.i_orgb = b.i_orgb and c_setuju_statuskabag is null 
								  and (a.i_orgb like ? or a.i_orgb like ?)
								  and c_setuju_statustu = ?",$where);
							   
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		 
			 $result = $db->fetchAll("SELECT i_barang_ajuanpinjam,d_barang_ajuanpinjam,a.i_orgb,n_orgb
		                       	 FROM e_ast_ajuanpinjaminv_0_tm a,e_org_0_0_tm b 
								 where a.i_orgb = b.i_orgb and c_setuju_statuskabag is null 
								 and (a.i_orgb like ? or a.i_orgb like ?)
                                 and c_setuju_statustu = ?
								 order by i_barang_ajuanpinjam		
								 limit $xLimit offset $xOffset",$where); 
			 $jmlResult = count($result);
		 
		 
		 
		  
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		    for ($j = 0; $j < $jmlResult; $j++) {
		 
               $hasilAkhir[$j] = array("i_barang_ajuanpinjam"           =>(string)$result[$j]->i_barang_ajuanpinjam,
								   "d_barang_ajuanpinjam"           =>(string)$result[$j]->d_barang_ajuanpinjam,
								   "i_orgb"           =>(string)$result[$j]->i_orgb,
								   "n_orgb"           =>(string)$result[$j]->n_orgb);
								  
								  
							       
		
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
	public function getPerlPinjamanInvListA($pageNumber,$itemPerPage,$unitkr,$unitkr1) {
       $statustu="N"; 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $unitkr1;
		 $where[] = $statustu;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	  FROM e_ast_ajuanpinjaminv_0_tm a,e_org_0_0_tm b 
								  where a.i_orgb = b.i_orgb  
								  and (a.i_orgb like ? or a.i_orgb like ?)
								  and c_setuju_statustu = ?",$where);
			 				   
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		 
			 $result = $db->fetchAll("SELECT i_barang_ajuanpinjam,d_barang_ajuanpinjam,a.i_orgb,n_orgb
		                       	 FROM e_ast_ajuanpinjaminv_0_tm a,e_org_0_0_tm b 
								 where a.i_orgb = b.i_orgb  
								 and (a.i_orgb like ? or a.i_orgb like ?)
                                 and c_setuju_statustu = ?	
								order by i_barang_ajuanpinjam		
								 limit $xLimit offset $xOffset",$where); 
			 $jmlResult = count($result);
		 
		 
		 
		  
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		    for ($j = 0; $j < $jmlResult; $j++) {
		 
               $hasilAkhir[$j] = array("i_barang_ajuanpinjam"           =>(string)$result[$j]->i_barang_ajuanpinjam,
								   "d_barang_ajuanpinjam"           =>(string)$result[$j]->d_barang_ajuanpinjam,
								   "i_orgb"           =>(string)$result[$j]->i_orgb,
								   "n_orgb"           =>(string)$result[$j]->n_orgb);
								  
								  
							       
		
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
	public function getBagPinjamanInvList($pageNumber,$itemPerPage ,$unitkr,$unitkr1) {
       $statustu="Y"; 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $unitkr1;
		 $where[] = $statustu;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	  FROM e_ast_ajuanpinjaminv_0_tm a,e_org_0_0_tm b 
								  where a.i_orgb = b.i_orgb  
								  and (a.i_orgb like ? or a.i_orgb like ?)
								  and c_setuju_statuskabag = ?
								  and not exists(select * from e_ast_serahpinjaminv_0_tm c
								  where a.i_barang_ajuanpinjam = c.i_barang_ajuanpinjam)",$where);
			 				   
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		 
			 $result = $db->fetchAll("SELECT i_barang_ajuanpinjam,d_barang_ajuanpinjam,a.i_orgb,n_orgb
		                       	 FROM e_ast_ajuanpinjaminv_0_tm a,e_org_0_0_tm b 
								 where a.i_orgb = b.i_orgb  
								 and (a.i_orgb like ? or a.i_orgb like ?)
                                 and c_setuju_statuskabag = ? 
								 and not exists(select * from e_ast_serahpinjaminv_0_tm c
								 where a.i_barang_ajuanpinjam = c.i_barang_ajuanpinjam)
                                 order by i_barang_ajuanpinjam								 
								 limit $xLimit offset $xOffset",$where); 
			 $jmlResult = count($result);
		 
		 
		 
		  
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_barang_ajuanpinjam"           =>(string)$result[$j]->i_barang_ajuanpinjam,
								   "d_barang_ajuanpinjam"           =>(string)$result[$j]->d_barang_ajuanpinjam,
								   "i_orgb"           =>(string)$result[$j]->i_orgb,
								   "n_orgb"           =>(string)$result[$j]->n_orgb);
								  
								  
							       
		
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
	public function getBagPinjamanInvListA($pageNumber,$itemPerPage ,$unitkr,$unitkr1) {
       $statustu="N"; 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $unitkr1;
		 $where[] = $statustu;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	  FROM e_ast_ajuanpinjaminv_0_tm a,e_org_0_0_tm b 
								  where a.i_orgb = b.i_orgb  
								  and (a.i_orgb like ? or a.i_orgb like ?)
								  and c_setuju_statuskabag = ?",$where);
			 				   
		 }
		 else
		 {
			 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		 
			 $result = $db->fetchAll("SELECT i_barang_ajuanpinjam,d_barang_ajuanpinjam,a.i_orgb,n_orgb
		                       	 FROM e_ast_ajuanpinjaminv_0_tm a,e_org_0_0_tm b 
								 where a.i_orgb = b.i_orgb  
								 and (a.i_orgb like ? or a.i_orgb like ?)
                                 and c_setuju_statuskabag = ?
								 order by i_barang_ajuanpinjam		
								 limit $xLimit offset $xOffset",$where); 
			 $jmlResult = count($result);
		 
		 
		 
		  
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_barang_ajuanpinjam"           =>(string)$result[$j]->i_barang_ajuanpinjam,
								   "d_barang_ajuanpinjam"           =>(string)$result[$j]->d_barang_ajuanpinjam,
								   "i_orgb"           =>(string)$result[$j]->i_orgb,
								   "n_orgb"           =>(string)$result[$j]->n_orgb);
								  
								  
							       
		
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
	public function updateSetujuPjmTU($nopin,$setuju) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   if($setuju=='1'){
	      $setuju='Y';		  
	   }else{
           $setuju='N';		   
       }		   
	   try {
		 $db->beginTransaction();
	     $atk_mast_prm = array("c_setuju_statustu"  		=>$setuju,
						       "d_setuju_tu"   				=>date("Y-m-d"),
						       "d_entry"       		    	=>date("Y-m-d"));
	     
		 
		 $where[] = "i_barang_ajuanpinjam = '".trim($nopin)."'";
	     $db->update('e_ast_ajuanpinjaminv_0_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
		 		 
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	
	}
   
  public function getSetujuPinjamanKbgList($pageNumber,$itemPerPage) {
       $status='Y';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	  FROM e_ast_ajuanpinjaminv_0_tm a,e_org_0_0_tm b 
								  where a.i_orgb = b.i_orgb and c_setuju_statuskabag is null and
								  c_setuju_statustu=?",$where);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		 
			 $result = $db->fetchAll("SELECT i_barang_ajuanpinjam,d_barang_ajuanpinjam,a.i_orgb,n_orgb
		                       	 FROM e_ast_ajuanpinjaminv_0_tm a,e_org_0_0_tm b 
								 where a.i_orgb = b.i_orgb and c_setuju_statuskabag is null and
								 c_setuju_statustu=? 
								 limit $xLimit offset $xOffset",$where); 
			 $jmlResult = count($result);
		      
		 
		 
		  
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_barang_ajuanpinjam"           =>(string)$result[$j]->i_barang_ajuanpinjam,
								   "d_barang_ajuanpinjam"           =>(string)$result[$j]->d_barang_ajuanpinjam,
								   "i_orgb"           =>(string)$result[$j]->i_orgb,
								   "n_orgb"           =>(string)$result[$j]->n_orgb);
								  
								  
							       
		
		 }
        }
       }		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	 }
	  
	public function updateSetujuPjmKbg($nopin,$setuju) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   echo 'service 991 :'.$setuju;
	   if($setuju=='1'){
	      $setuju='Y';
	   }else{
          $setuju='N';
       }
	   try {
		 $db->beginTransaction();
	     $atk_mast_prm = array("c_setuju_statuskabag"  		=>$setuju,
						       "d_setuju_kabag"   				=>date("Y-m-d"),
						       "d_entry"       		    	=>date("Y-m-d"));
	     
		 
		 $where[] = "i_barang_ajuanpinjam = '".trim($nopin)."'";
	     $db->update('e_ast_ajuanpinjaminv_0_tm',$atk_mast_prm, $where);
		 $db->commit();
	     return 'sukses';
		 		 
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	
	}
	public function getSerahPinjamanInvList($pageNumber,$itemPerPage) {
	  $status='Y';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	  FROM e_ast_ajuanpinjaminv_0_tm a,e_org_0_0_tm b 
								  where a.i_orgb = b.i_orgb and 
								  c_setuju_statuskabag=?",$where);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;
			 $result = $db->fetchAll("SELECT a.i_barang_ajuanpinjam,d_barang_ajuanpinjam,a.i_orgb,n_orgb
		                       	 FROM e_ast_ajuanpinjaminv_0_tm a,e_org_0_0_tm b 
								 where a.i_orgb = b.i_orgb and 
								 c_setuju_statuskabag = ?
								 and not exists(select * from e_ast_serahpinjaminv_0_tm c
								 where a.i_barang_ajuanpinjam = c.i_barang_ajuanpinjam)
								 order by i_barang_ajuanpinjam
								 limit $xLimit offset $xOffset",$where); 	
			 $jmlResult = count($result);
		
			if($jmlResult > 0){
				for ($j = 0; $j < $jmlResult; $j++) {
		 
				$hasilAkhir[$j] = array("i_barang_ajuanpinjam"           =>(string)$result[$j]->i_barang_ajuanpinjam,
								   "d_barang_ajuanpinjam"           =>(string)$result[$j]->d_barang_ajuanpinjam,
								   "i_orgb"           				=>(string)$result[$j]->i_orgb,
								   "n_orgb"           				=>(string)$result[$j]->n_orgb);
								  
								  
							       
		
				}
			}
		}		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function insertSerahTrmPinjam(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	
	   try {
	     $db->beginTransaction();
		 $atk_mast_prm = array("i_barang_serah"         		    =>$data['noSerahb'],
	                           "d_barang_serah"    	        	    =>$data['tglSerah'],
						       "i_barang_ajuanpinjam"               =>$data['nopin'],
							   "i_peg_nippemberi"                   =>$data['nipPemberi'],
							   "i_peg_nipterima"                   =>$data['nipPenerima'],
							   "i_orgb"  	                        =>$data['i_orgb'],
							   "i_entry"       		            	=>$data['i_entry'],
						       "d_entry"       		        			=>date("Y-m-d"));
	    
	     $db->insert('e_ast_serahpinjaminv_0_tm',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function insertSerahTrmPinjamItem(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	
	   try {
	     $db->beginTransaction();
		 $atk_mast_prm = array("i_barang_serah"         		    =>$data['i_barang_serah'],
	                           "d_anggaran"    	        	    =>$data['d_anggaran'],
						       "c_barang"                    =>$data['c_barang'],
							   "i_aset"                   =>$data['i_aset'],
							   "d_perolehan"  	                =>$data['d_perolehan'],
							   "c_barang_kembali"  	                =>null,
							   "i_entry"       		        			=>$data['i_entry'],
						       "d_entry"       		        			=>date("Y-m-d"));
	    
	     $db->insert('e_ast_serahpinjaminv_item_tm',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getBalikPinjamanInvList($pageNumber,$itemPerPage,$unitkr,$unitkr1) {
	   $status='Y';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $unitkr1;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
		    
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	  FROM e_ast_serahpinjaminv_0_tm  a, e_ast_serahpinjaminv_item_tm b, e_org_0_0_tm c 
								  where a.i_orgb = c.i_orgb and a.i_barang_serah = b.i_barang_serah and
								  c_barang_kembali is null 
								  and (a.i_orgb like ? or a.i_orgb like ?)",$where);

			 
		 }
		 else
		 {
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;
			 $result = $db->fetchAll("SELECT distinct a.i_barang_serah,a.d_barang_serah,a.i_orgb,n_orgb
		                       	 FROM e_ast_serahpinjaminv_0_tm  a, e_ast_serahpinjaminv_item_tm b, e_org_0_0_tm c 
								 where a.i_orgb = c.i_orgb 
								 and a.i_barang_serah = b.i_barang_serah 
								 and c_barang_kembali is null 
								 and (a.i_orgb like ? or a.i_orgb like ?)
								 order by a.i_barang_serah
								 limit $xLimit offset $xOffset",$where); 	
			 
			  	
			 $jmlResult = count($result);
		
			 if($jmlResult > 0){
				for ($j = 0; $j < $jmlResult; $j++) {
		 
				$hasilAkhir[$j] = array("i_barang_serah"           =>(string)$result[$j]->i_barang_serah,
								   "d_barang_serah"           =>(string)$result[$j]->d_barang_serah,
								   "i_orgb"           				=>(string)$result[$j]->i_orgb,
								   "n_orgb"           				=>(string)$result[$j]->n_orgb);
								  
								  
							       
		
				}
			}	
		}		
			
		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getBalikPinjamanInvListA($pageNumber,$itemPerPage) {
	   $status='Y';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
		  
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	  FROM e_ast_serahpinjaminv_0_tm  a, e_ast_serahpinjaminv_item_tm b, e_org_0_0_tm c 
								  where a.i_orgb = c.i_orgb and a.i_barang_serah = b.i_barang_serah and
								  c_barang_kembali is null ");
						
		 }
		 else
		 {
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;
			 $result = $db->fetchAll("SELECT distinct a.i_barang_serah,a.d_barang_serah,a.i_orgb,n_orgb
		                       	 FROM e_ast_serahpinjaminv_0_tm  a, e_ast_serahpinjaminv_item_tm b, e_org_0_0_tm c 
								 where a.i_orgb = c.i_orgb 
								 and a.i_barang_serah = b.i_barang_serah 
								 and c_barang_kembali is null  
								 order by a.i_barang_serah
								 limit $xLimit offset $xOffset"); 	
			 
			  	
			 $jmlResult = count($result);
		
			 if($jmlResult > 0){
				for ($j = 0; $j < $jmlResult; $j++) {
		 
				$hasilAkhir[$j] = array("i_barang_serah"           =>(string)$result[$j]->i_barang_serah,
								   "d_barang_serah"           =>(string)$result[$j]->d_barang_serah,
								   "i_orgb"           				=>(string)$result[$j]->i_orgb,
								   "n_orgb"           				=>(string)$result[$j]->n_orgb);
								  
								  
							       
		
				}
			}	
		}		
				
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getBalikPinjamanInvList2($pageNumber,$itemPerPage,$unitkr,$unitkr1) {
	 $status='Y';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unitkr;
		 $where[] = $unitkr1;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
		    
			$hasilAkhir = $db->fetchOne("SELECT count(*)
							   	  FROM e_ast_pengembalianinv_0_tm  a, e_ast_pengembalianinv_item_tm b, e_org_0_0_tm c
                                  ,e_ast_serahpinjaminv_0_tm d								  
								  where d.i_orgb = c.i_orgb and a.i_barang_pengembalian = b.i_barang_pengembalian
								  and a.i_barang_serah = d.i_barang_serah 
								  and (d.i_orgb like ? or d.i_orgb like ?)",$where);
						
			
		 }
		 else
		 {
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;
			 $result = $db->fetchAll("SELECT distinct a.i_barang_pengembalian,a.d_barang_pengembalian,d.i_orgb,n_orgb
		                       	 FROM e_ast_pengembalianinv_0_tm  a, e_ast_pengembalianinv_item_tm b, e_org_0_0_tm c, 
								 e_ast_serahpinjaminv_0_tm d
								 where d.i_orgb = c.i_orgb  and a.i_barang_serah = d.i_barang_serah 
								 and   a.i_barang_pengembalian = b.i_barang_pengembalian  
								 and (d.i_orgb like ? or d.i_orgb like ?) 
								 order by a.i_barang_pengembalian
								 limit $xLimit offset $xOffset",$where); 	
			 
			  	
			 $jmlResult = count($result);
		
			 if($jmlResult > 0){
				for ($j = 0; $j < $jmlResult; $j++) {
		 
				$hasilAkhir[$j] = array("i_barang_serah"           =>(string)$result[$j]->i_barang_pengembalian,
								   "d_barang_serah"           =>(string)$result[$j]->d_barang_pengembalian,
								   "i_orgb"           				=>(string)$result[$j]->i_orgb,
								   "n_orgb"           				=>(string)$result[$j]->n_orgb);
								  
								  
							       
		
				}
			}	
		}		
		
		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getSerahInvList($nopeng) {
       $status='T';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT c_barang,to_char(d_perolehan,'yyyy') as d_anggaran,to_char(i_aset,'09999') as no_aset,d_perolehan, 
		 merk_type,ur_sskel 
		 FROM e_ast_serahpinjaminv_item_tm a ,
		      e_sabm_t_master_tm c,e_ast_sskel_0_tr d
		 where  a.i_aset =  c.no_aset  and c_barang_kembali is null and a.i_barang_serah = ?
		   and  a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
								      and substr(c.kd_brg,2,2) = d.kd_bid 
								   	  and substr(c.kd_brg,4,2) = d.kd_kel
									  and substr(c.kd_brg,6,2) = d.kd_skel
									  and substr(c.kd_brg,8,3) = d.kd_sskel",$nopeng);
		
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_barang"                   =>(string)$result[$j]->c_barang,
								   "d_anggaran"                 =>(string)$result[$j]->d_anggaran,
								   "no_aset"                    =>(string)$result[$j]->no_aset,
								   "d_perolehan"                =>(string)$result[$j]->d_perolehan,
								   "merk_type"                  =>(string)$result[$j]->merk_type,
								   "ur_sskel"                   =>(string)$result[$j]->ur_sskel);
								  
								  
			  			       
		
		 }
        }	
        		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getSerahInvListReport($nopeng) {
       $status='T';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT c_barang,to_char(d_perolehan,'yyyy') as d_anggaran,to_char(i_aset,'09999') as no_aset,d_perolehan, 
		 merk_type,ur_sskel 
		 FROM e_ast_serahpinjaminv_item_tm a ,
		      e_sabm_t_master_tm c,e_ast_sskel_0_tr d
		 where  a.i_aset =  c.no_aset  and c_barang_kembali is not null and a.i_barang_serah = ?
		   and  a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
								      and substr(c.kd_brg,2,2) = d.kd_bid 
								   	  and substr(c.kd_brg,4,2) = d.kd_kel
									  and substr(c.kd_brg,6,2) = d.kd_skel
									  and substr(c.kd_brg,8,3) = d.kd_sskel",$nopeng);
		
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_barang"                   =>(string)$result[$j]->c_barang,
								   "d_anggaran"                 =>(string)$result[$j]->d_anggaran,
								   "no_aset"                    =>(string)$result[$j]->no_aset,
								   "d_perolehan"                =>(string)$result[$j]->d_perolehan,
								   "merk_type"                  =>(string)$result[$j]->merk_type,
								   "ur_sskel"                   =>(string)$result[$j]->ur_sskel);
								  
								  
			  			       
		
		 }
        }	
        		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
	public function getSerahInvListV($nopeng) {
       $status='T';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT b.i_barang_serah,b.d_barang_serah,c_barang,to_char(d_perolehan,'yyyy') as d_anggaran,to_char(i_aset,'09999') as no_aset,d_perolehan, 
		 merk_type,ur_sskel 
		 FROM e_ast_serahpinjaminv_item_tm a ,e_ast_serahpinjaminv_0_tm b,
		      e_sabm_t_master_tm c,e_ast_sskel_0_tr d
		   where  a.i_aset =  c.no_aset  and 
		   a.i_barang_serah = b.i_barang_serah  and b.i_barang_ajuanpinjam = ?
		   and  a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
								      and substr(c.kd_brg,2,2) = d.kd_bid 
								   	  and substr(c.kd_brg,4,2) = d.kd_kel
									  and substr(c.kd_brg,6,2) = d.kd_skel
									  and substr(c.kd_brg,8,3) = d.kd_sskel",$nopeng);
		
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_barang_serah"              =>(string)$result[$j]->i_barang_serah,
									"d_barang_serah"             =>(string)$result[$j]->d_barang_serah,
								    "c_barang"                   =>(string)$result[$j]->c_barang,
								   "d_anggaran"                  =>(string)$result[$j]->d_anggaran,
								   "no_aset"                     =>(string)$result[$j]->no_aset,
								   "d_perolehan"                 =>(string)$result[$j]->d_perolehan,
								   "merk_type"                   =>(string)$result[$j]->merk_type,
								   "ur_sskel"                    =>(string)$result[$j]->ur_sskel);
								  
								  
			  			       
		
		 }
        }	
        		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
    public function insertSerahTrmKembali(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	
	   try {
	     $db->beginTransaction();
		 $atk_mast_prm = array("i_barang_pengembalian"         		    =>$data['noSerahb'],
	                           "d_barang_pengembalian"    	        	    =>$data['tglSerah'],
						       "i_barang_serah"                     =>$data['nopin'],
							   "i_peg_nippemberi"                   =>$data['nipPenerima'],
							   "i_peg_nipterima"                     =>$data['nipPemberi'],
							   "i_entry"       		            	=>$data['i_entry'],
						       "d_entry"       		        			=>date("Y-m-d"));
	    
	     $db->insert('e_ast_pengembalianinv_0_tm',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
    public function insertKembaliPinjamItem(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	
	   try {
	     $db->beginTransaction();
		 $atk_mast_prm = array("i_barang_pengembalian"         		    =>$data['i_barang_pengembalian'],
	                           "d_anggaran"    	        	    =>$data['d_anggaran'],
						       "c_barang"                    =>$data['c_barang'],
							   "i_aset"                   =>$data['i_aset'],
							   "d_perolehan"  	                =>$data['d_perolehan'],
							   "i_entry"       		        			=>$data['i_entry'], 
						       "d_entry"       		        			=>date("Y-m-d"));
	    
	     $db->insert('e_ast_pengembalianinv_item_tm',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
    public function UpdSerahTrmPinjamItem(array $data) {
	   $status = 'Y';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $item_parm = array("c_barang_kembali"  		        =>$status,
						 	"i_entry"       		    		=>$data['i_entry'], 
						    "d_entry"       		    		=>date("Y-m-d"));
						  	   							   
	      $where[] = "i_barang_serah  =  '".$data['i_barang_serah']."'";
		  $where[] = "c_barang        =  '".$data['c_barang']."'";
		  $where[] = "i_aset          =  '".$data['i_aset']."'";
		  $where[] = "d_anggaran   =  '".$data['d_anggaran']."'";
	     $db->update('e_ast_serahpinjaminv_item_tm',$item_parm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
   public function getCariNamaBrg($pageNumber,$itemPerPage,$namaBarang) {
	   $namaBarang = strtoupper($namaBarang);
	   $nbrg = '%'.$namaBarang.'%';
	  
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*) 
 						 from  e_ast_sskel_0_tr a, e_sabm_t_master_tm b
						 where substr(b.kd_brg,1,1) = a.kd_gol
						 and substr(b.kd_brg,2,2) = a.kd_bid 
						 and substr(b.kd_brg,4,2) = a.kd_kel
                         and substr(b.kd_brg,6,2) = a.kd_skel
                         and substr(b.kd_brg,8,3) = a.kd_sskel 
					     and ((kd_gol != '2' and kd_bid != '12') 
							 or (kd_gol != '2' and kd_bid = '12') 
							 or (kd_gol = '2' and kd_bid != '12'))
						 and ur_sskel like ?
						 and not exists(select c.i_aset from e_ast_dir_item_tm  c
								  where c.c_barang =b.kd_brg
								  and c.i_aset = b.no_aset)
						 and not exists(select d.i_aset from e_ast_dil_item_tm  d
								  where d.c_barang =b.kd_brg
								  and d.i_aset = b.no_aset)
                         and not exists(select e.i_aset from e_ast_kib_item_tm  e
								  where e.c_barang =b.kd_brg
								  and e.i_aset = b.no_aset)	
                        and not exists(select f.i_aset from e_ast_ajuanpinjaminv_item_tm f 
        						  where   f.c_barang =b.kd_brg
								  and   f.i_aset = b.no_aset)",$nbrg);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT distinct to_char(tgl_perlh,'yyyy') as thn_ang, b.kd_brg, to_char(b.no_aset,'09999') as  i_aset , ur_sskel, 
						 tgl_perlh, merk_type,rph_aset
						 from  e_ast_sskel_0_tr a, e_sabm_t_master_tm b
						 where substr(b.kd_brg,1,1) = a.kd_gol
						 and substr(b.kd_brg,2,2) = a.kd_bid 
						 and substr(b.kd_brg,4,2) = a.kd_kel
                         and substr(b.kd_brg,6,2) = a.kd_skel
                         and substr(b.kd_brg,8,3) = a.kd_sskel 
					     and ((kd_gol != '2' and kd_bid != '12') 
							 or (kd_gol != '2' and kd_bid = '12') 
							 or (kd_gol = '2' and kd_bid != '12'))
						 and ur_sskel like ?
						 and not exists(select c.i_aset from e_ast_dir_item_tm  c
								  
								  where c.c_barang =b.kd_brg
								  and c.i_aset = b.no_aset)
						 and not exists(select d.i_aset from e_ast_dil_item_tm  d
								  
								  where d.c_barang =b.kd_brg
								  and d.i_aset = b.no_aset)
                         and not exists(select e.i_aset from e_ast_kib_item_tm  e
								  
								  where e.c_barang =b.kd_brg
								  and e.i_aset = b.no_aset)	
                        and not exists(select f.i_aset from e_ast_ajuanpinjaminv_item_tm f 
        						  
								  where   f.c_barang =b.kd_brg
								  and   f.i_aset = b.no_aset)
												
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
  public function getListPegawaiAll($pageNumber,$itemPerPage,$unitkr,$nmPegawai) {
		$nmPegawai = strtoupper($nmPegawai);
		$npeg = '%'.$nmPegawai.'%';
		 
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $unitkr;
			$where[] = $npeg;
			$where[] = $unitkr;
			$where[] = $npeg;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$hasil = $db->fetchAll("SELECT a.i_peg_nip,a.n_peg,b.n_jabatan,a.c_unit_kerja
									FROM e_sdm_pegawai_0_tm A, e_sdm_jabatan_0_tm B 
									where 
									a.i_peg_nip = b.i_peg_nip
									and a.c_unit_kerja = b.c_jabatan
									and a.c_unit_kerja = ?
									and upper(n_peg) like ?
									union
									select a.i_peg_nip, n_peg, NULL, a.c_unit_kerja 
									from  e_sdm_pegawai_0_tm A 
									where  a.c_unit_kerja = ? 
									and not EXISTS(select * from  e_sdm_jabatan_0_tm B
									          where a.i_peg_nip = b.i_peg_nip)
									and upper(n_peg) like ?",$where);
			 $hasilAkhir=count($hasil);
			  
			 }
			 
			 else
			 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 $result = $db->fetchAll("SELECT a.i_peg_nip,a.n_peg,b.n_jabatan,a.c_unit_kerja
									FROM e_sdm_pegawai_0_tm A, e_sdm_jabatan_0_tm B 
									where 
									a.i_peg_nip = b.i_peg_nip
									and a.c_unit_kerja = b.c_jabatan
									and a.c_unit_kerja = ?
									and upper(n_peg) like ?
									union
									select a.i_peg_nip, n_peg, NULL, a.c_unit_kerja 
									from  e_sdm_pegawai_0_tm A 
									where  a.c_unit_kerja = ?
									 and not EXISTS(select * from  e_sdm_jabatan_0_tm B
									          where a.i_peg_nip = b.i_peg_nip)
									and upper(n_peg) like ?
									order by n_peg 
									limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_peg_nip"         =>(string)$result[$j]->i_peg_nip,
									"n_peg"           	 	=>(string)$result[$j]->n_peg,
									"n_jabatan"          	=>(string)$result[$j]->n_jabatan,
									"c_unit_kerja"			=>(string)$result[$j]->c_unit_kerja);
			 }	
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
			$result = $db->fetchOne($query,$nip);
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
			$result = $db->fetchOne($query,$nip);
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
			$result = $db->fetchOne($query,$where);
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
	public function getNamaJbtOrgByNip($nip) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			//$where[] = $nip;
			$query="select B.i_peg_nip, n_peg, A.n_jabatan, b.c_unit_kerja, b.i_orgb  
						from e_sdm_jabatan_0_tm A,  e_sdm_pegawai_0_tm B
						where a.i_peg_nip = b.i_peg_nip
						and b.c_unit_kerja = a.c_jabatan
						and b.i_peg_nip = '$nip'
					union
						select a.i_peg_nip, n_peg, NULL, a.c_unit_kerja, a.i_orgb  
						from  e_sdm_pegawai_0_tm A 
						where a.i_peg_nip = '$nip'
						and not EXISTS(select * from  e_sdm_jabatan_0_tm B
							where a.i_peg_nip = b.i_peg_nip
							and a.i_peg_nip = '$nip')  
					";
			$result = $db->fetchAll($query);
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
    public function getNoPengembalianBarang($noPeng){
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$result="No Number";		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$query="SELECT i_barang_pengembalian 
			        FROM e_ast_pengembalianinv_0_tm 
					where i_barang_serah= '$noPeng'";
			$result = $db->fetchOne($query);
			return $result;
		} 
		catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return $result;
		}		  
	}
/*
//Servicenya di ganti ini aja !!!!
			select B.i_peg_nip, n_peg, A.n_jabatan, b.c_unit_kerja, b.i_orgb  
			from e_sdm_jabatan_0_tm A,  e_sdm_pegawai_0_tm B
			where a.i_peg_nip = b.i_peg_nip
			and b.c_unit_kerja = a.c_jabatan
			and b.i_peg_nip = 'IT006'
			union
			select a.i_peg_nip, n_peg, NULL, a.c_unit_kerja, a.i_orgb  
			from  e_sdm_pegawai_0_tm A 
			where a.i_peg_nip = 'IT006'
			and not EXISTS(select * from  e_sdm_jabatan_0_tm B
			               where a.i_peg_nip = b.i_peg_nip
			               and a.i_peg_nip = 'IT006')  
order by n_peg
*/	
	/// Akhir Tambahan Cah Bagus ////
	
  }	
?>