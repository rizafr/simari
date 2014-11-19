<?php
class ast_distribusipc_Service {
   
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
	public function insertAstDirTm(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
		 //echo $data['nipPenerima'];   
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
						       "i_entry"       		            =>$data['nuser'],
						       "d_entry"       		        =>date("Y-m-d"));
	    
	     $db->insert('e_ast_dir_0_tm',$atk_mast_prm);
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
		 $db->delete('e_ast_dir_0_tm', $where);
		 $db->delete('e_ast_dir_item_tm', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
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
							   	 FROM e_ast_dir_0_tm where i_barang_serah like ? and c_barang_statserah=?",$where); ;
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		 
		 $result = $db->fetchAll("SELECT A.i_barang_serah,A.d_barang_serah,
									A.i_peg_nipterima,A.i_orgb_penerima,B.n_peg as namaPenerima,B.n_peg as namaPemberi,
									A.i_peg_nippemberi,A.i_orgb_pemberi,
									A.e_keterangan ,i_peg_nip
									FROM e_ast_dir_0_tm A, e_sdm_pegawai_0_tm B
									where A.i_peg_nipterima = B.i_peg_nip
									and i_barang_serah like ? 
									and c_barang_statserah = ?
								 limit $xLimit offset $xOffset",$where); 
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
			$i_peg_nippemberi	= (string)$result[$j]->i_peg_nippemberi;	
			$i_peg_nipterima	= (string)$result[$j]->i_peg_nip;	
			$namaPenerima 	= $db->fetchCol("select n_peg
											from e_sdm_pegawai_0_tm B
											where i_peg_nip = ?
											",$result[$j]->i_peg_nip);
										
			$namaPemberi 	= $db->fetchCol("select n_peg
										from e_sdm_pegawai_0_tm B
										where i_peg_nip = ?
										",$result[$j]->i_peg_nippemberi);
			
			/*if($n_peg[0] == '' ){
				echo '2...';
				$hasilAkhir[$j] = array("i_barang_serah"       =>(string)$result[$j]->i_barang_serah,
								   "d_barang_serah"            =>(string)$result[$j]->d_barang_serah,
								   "i_peg_nipterima"           =>(string)$result[$j]->i_peg_nipterima,
								   "i_orgb_penerima"           =>(string)$result[$j]->i_orgb_penerima,
								   "n_peg"  		           =>(string)$result[$j]->n_peg,
								   "n_peg"  		           =>(string)$result[$j]->namaPemberi,
								   "i_peg_nippemberi"          =>(string)$result[$j]->i_peg_nippemberi,
								   "i_orgb_pemberi"            =>(string)$result[$j]->i_orgb_pemberi,
								   "e_keterangan"              =>(string)$result[$j]->e_keterangan);
			}else{*/
				$hasilAkhir[$j] = array("i_barang_serah"       =>(string)$result[$j]->i_barang_serah,
								   "d_barang_serah"            =>(string)$result[$j]->d_barang_serah,
								   "i_peg_nipterima"           =>(string)$result[$j]->i_peg_nipterima,
								   "i_orgb_penerima"           =>(string)$result[$j]->i_orgb_penerima,
								   "n_peg"  		           =>$namaPenerima[0],
								   "n_pemberi"  		       =>$namaPemberi[0],
								   "i_peg_nippemberi"          =>(string)$result[$j]->i_peg_nippemberi,
								   "i_orgb_pemberi"            =>(string)$result[$j]->i_orgb_pemberi,
								   "e_keterangan"              =>(string)$result[$j]->e_keterangan);
			
			//}					  
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
		 $result = $db->fetchAll("SELECT a.c_barang,a.d_aset_thnanggar,to_char(c.no_aset,'09999') as no_aset,a.i_ruang, 
		 c.merk_type,d.ur_sskel,b.i_hw_investasi
		 FROM e_ast_dir_item_tm a , e_ast_komputer_0_tr b,
		      e_sabm_t_master_tm c,e_ast_sskel_0_tr d
		 where a.c_barang=b.c_barang
		 and c.no_aset = b.i_aset and a.d_barang_peroleh =  c.tgl_perlh
		 and a.i_aset =  c.no_aset  and a.i_barang_serah = ?
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
								   "ur_sskel"           =>(string)$result[$j]->ur_sskel,
								   "i_hw_investasi"           =>(string)$result[$j]->i_hw_investasi);
								  
								  
			  			       
		
		 }
        }	
        		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
 
 public function getKomputerList($nopeng) {
       $status='T';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
	     $where[] = $status;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT a.c_barang,d_anggaran,to_char(a.i_aset,'09999') as no_aset,i_komputer_macaddress,
									 i_komputer_macaddress1,i_komputer_macaddress2,i_komputer_macaddress3,i_komputer_macaddress4,
									 i_komputer_serialpc,i_komputer_serialwindow,e_pc,a.i_ruang, ur_sskel, i_hw_investasi
									 FROM e_ast_dir_item_tm a ,
							         e_sabm_t_master_tm c,e_ast_sskel_0_tr d, e_ast_komputer_0_tr b
									 where a.d_barang_peroleh =  c.tgl_perlh
									 and a.i_aset =  c.no_aset  
									 and b.d_perolehan =  c.tgl_perlh
									 and b.i_aset =  c.no_aset  
									 and a.i_barang_serah = ?
									 and  a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
									 and substr(c.kd_brg,2,2) = d.kd_bid 
									 and substr(c.kd_brg,4,2) = d.kd_kel
									 and substr(c.kd_brg,6,2) = d.kd_skel
									 and substr(c.kd_brg,8,3) = d.kd_sskel",$nopeng);
								      
		
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_barang"           			 =>(string)$result[$j]->c_barang,
								   "d_anggaran"          			 =>(string)$result[$j]->d_anggaran,
								   "no_aset"           				 =>(string)$result[$j]->no_aset,
								   "n_barang"           				 =>(string)$result[$j]->ur_sskel,
								   "i_komputer_macaddress"           =>(string)$result[$j]->i_komputer_macaddress
										                               .' '.$result[$j]->i_komputer_macaddress1
																	   .' '.$result[$j]->i_komputer_macaddress2
																	   .' '.$result[$j]->i_komputer_macaddress3
																	   .' '.$result[$j]->i_komputer_macaddress4,
								   "e_pc"           				 =>(string)$result[$j]->e_pc,
								   "i_komputer_serialpc"             =>(string)$result[$j]->i_komputer_serialpc,
								   "i_komputer_serialwindow"         =>(string)$result[$j]->i_komputer_serialwindow,
								   "i_ruang"         			     =>(string)$result[$j]->i_ruang,
								   "i_hw_investasi"         			     =>(string)$result[$j]->i_hw_investasi);
								  
								  
			  			       
		
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
		 $result = $db->fetchAll("SELECT a.c_barang,d_aset_thnanggar,to_char(no_aset,'09999') as no_aset,a.i_ruang,e_keterangan,
		 merk_type,ur_sskel,d_barang_peroleh, i_komputer_macaddress, i_komputer_macaddress1,
		 i_komputer_macaddress2,i_komputer_macaddress3, i_komputer_macaddress4, i_hw_investasi
		 FROM e_ast_dir_item_tm a,e_ast_dir_0_tm b,
		      e_sabm_t_master_tm c,e_ast_sskel_0_tr d,  e_ast_komputer_0_tr e
		 where a.i_barang_serah = b.i_barang_serah	and a.c_barang=? and no_aset=? and  a.i_aset =  c.no_aset  
		    and e.c_barang = a.c_barang and  e.i_aset = a.i_aset 
		   and a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
								     and substr(c.kd_brg,2,2) = d.kd_bid 
									 and substr(c.kd_brg,4,2) = d.kd_kel
									 and substr(c.kd_brg,6,2) = d.kd_skel
									 and substr(c.kd_brg,8,3) = d.kd_sskel",$params);
		
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_barang"           =>(string)$result[$j]->c_barang,
								   "d_aset_thnanggar"   =>(string)$result[$j]->d_aset_thnanggar,
								   "no_aset"            =>(string)$result[$j]->no_aset,
								   "i_ruang"            =>(string)$result[$j]->i_ruang,
								   "e_keterangan"       =>(string)$result[$j]->e_keterangan,
								   "merk_type"          =>(string)$result[$j]->merk_type,
								   "ur_sskel"           =>(string)$result[$j]->ur_sskel,
								   "d_barang_peroleh"   =>(string)$result[$j]->d_barang_peroleh,
								   "mac_adress"   	    =>(string)$result[$j]->i_komputer_macaddress
										                               .' '.$result[$j]->i_komputer_macaddress1
																	   .' '.$result[$j]->i_komputer_macaddress2
																	   .' '.$result[$j]->i_komputer_macaddress3
																	   .' '.$result[$j]->i_komputer_macaddress4,
								   "i_hw_investasi"   =>(string)$result[$j]->i_hw_investasi);

								  
								  
							       
		
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
						       "i_orgb_penerima"  		 =>trim($data['orgPenerima']),
							   "i_peg_nippemberi"  		 =>trim($data['nipPemberi']),
							   "i_orgb_pemberi"  	 	 =>trim($data['orgPemberi']),
							   "e_keterangan"  	 	     =>trim($data['ktr']),
							   "i_entry"       		     =>$data['nuser'],
						       "d_entry"       		     =>date("Y-m-d"));
						  	    
							   
							   
	     $where[] = "i_barang_serah  =  '".$data['nopeng']."'";
		
	     $db->update('e_ast_dir_0_tm',$personal_parm, $where);
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
						         "i_entry"       		     =>$data['nuser'],
						         "d_entry"       		     =>date("Y-m-d"));
						  	    
							   
							   
	     $where[] = "i_barang_serah  =  '".$data['nopeng']."'";
		
	     $db->update('e_ast_dir_0_tm',$personal_parm, $where);
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
	      
	     $tglYMD = substr($data['dbarangperoleh'],6,4).'-'.substr($data['dbarangperoleh'],3,2).'-'.substr($data['dbarangperoleh'],0,2);
	     $db->beginTransaction();
		 $atk_mast_prm = array("i_barang_serah"         		=>$data['nopeng'],
	                           "d_aset_thnanggar"    	        =>$data['dasetthnanggar'],
						       "c_barang"                       =>$data['cbarang'],
							   "i_aset"              		    =>$data['iaset'],
							   "d_barang_peroleh"  	            =>$tglYMD, 
							   "i_ruang"                        =>$data['iruang'],
							   "i_entry"       		            =>$data['nuser'],
						       "d_entry"       		        =>date("Y-m-d"));
	    
	     $db->insert('e_ast_dir_item_tm',$atk_mast_prm);
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
						     "d_entry"       		     =>date("Y-m-d"));
						  	    
							   
							   
	      $where[] = "i_barang_serah  =  '".$data['nopeng']."'";
		  $where[] = "c_barang        =  '".$data['cbarang']."'";
		  $where[] = "i_aset          =  '".$data['iaset']."'";
		  $where[] = "d_aset_thnanggar   =  '".$data['dasetthnanggar']."'";
	     $db->update('e_ast_dir_item_tm',$item_parm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function UpdateAstKomputer(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $item_parm = array("c_unit_kerja"  		     =>$data['unitkr'],
	                         "i_peg_nip"  		         =>$data['nipPenerima'],
							 "i_ruang"  		         =>$data['iruang'],
							 "i_entry"       		     =>$data['nuser'],
						     "d_entry"       		     =>date("Y-m-d"));
						  	    
							   
							   
	      $where[] = "c_barang        =  '".$data['cbarang']."'";
		  $where[] = "i_aset          =  '".$data['iaset']."'";
		  $where[] = "d_anggaran      =  '".$data['dasetthnanggar']."'";
	     $db->update('e_ast_komputer_0_tr',$item_parm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function DelAstKomputer(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $item_parm = array("c_unit_kerja"  		             =>null,
							 "i_entry"       		            =>$data['nuser'],
						     "d_entry"       		     =>date("Y-m-d"));
						  	    
							   
							   
	      $where[] = "c_barang        =  '".$data['cbarang']."'";
		  $where[] = "i_aset          =  '".$data['iaset']."'";
		  $where[] = "d_anggaran      =  '".$data['thn']."'";
	     $db->update('e_ast_komputer_0_tr',$item_parm, $where);
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
		  $where[] = "d_aset_thnanggar   =  '".$data['thn']."'";
		  $where[] = "c_barang        =  '".$data['cbarang']."'";
		  $where[] = "i_aset          =  '".$data['iaset']."'";
		
	     $db->delete('e_ast_dir_item_tm', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	/*******************/
	public function getCariNamaBrgDIR($pageNumber,$itemPerPage,$namaBarang,$tahunPerolehan) 
	{
	   //echo "/pageNumber serv = ".$pageNumber;
	   //echo "/itemPerPage serv = ".$itemPerPage;
	   //echo "/namaBarang serv = ".$namaBarang;
	   //echo "/tahun serv = ".$tahunPerolehan;
	   	
	   $namaBarang = strtoupper($namaBarang);
	   $nbrg = '%'.$namaBarang.'%';
	  
	   $tahunPerolehan = strtoupper($tahunPerolehan);
	   $thnPeroleh = '%'.$tahunPerolehan.'%';
	   //echo "/thnPeroleh =".$thnPeroleh;
	   
	   $where[] = $nbrg;
	   $where[] = $thnPeroleh;
		 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*) 
 						 from  e_ast_sskel_0_tr a, e_ast_komputer_0_tr b
						 where substr(b.c_barang,1,1) = a.kd_gol
						 and substr(b.c_barang,2,2) = a.kd_bid 
						 and substr(b.c_barang,4,2) = a.kd_kel
                         and substr(b.c_barang,6,2) = a.kd_skel
                         and substr(b.c_barang,8,3) = a.kd_sskel 
						 and ur_sskel like ?
						 and to_char(b.d_perolehan,'YYYY') like ?
					     and  (c_unit_kerja is null or c_unit_kerja = '')",$where);
						 
            
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT distinct b.d_anggaran, b.c_barang, to_char(b.i_aset,'09999') as  i_aset , ur_sskel, 
										d_perolehan,i_komputer_macaddress, i_komputer_macaddress1, i_komputer_macaddress2, 
										i_komputer_macaddress3, i_komputer_macaddress4, c.merk_type as type_sabmn, b.n_type as type_oa,
										i_hw_investasi
										FROM  e_ast_sskel_0_tr a, e_ast_komputer_0_tr b, e_sabm_t_master_tm c
										where  substr(b.c_barang,1,1) = a.kd_gol
											and substr(b.c_barang,2,2) = a.kd_bid 
											and substr(b.c_barang,4,2) = a.kd_kel
											and substr(b.c_barang,6,2) = a.kd_skel
											and substr(b.c_barang,8,3) = a.kd_sskel 
											and c.kd_brg = b.c_barang
											and c.no_aset = b.i_aset
											and (c_unit_kerja is null or c_unit_kerja = '')
											and ur_sskel like ? 
											and to_char(b.d_perolehan,'YYYY') like ?
											order by ur_sskel
											limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 //echo "jumlah serv =".$jmlResult;
			 for ($j = 0; $j < $jmlResult; $j++) {
			    if (!$result[$j]->type_sabmn && $result[$j]->type_oa)
				{
				   $merk_type = $result[$j]->type_oa;
				} else if ($result[$j]->type_sabmn && !$result[$j]->type_oa)
				{
				   $merk_type = $result[$j]->type_sabmn;
 			    }else
				{
				  $merk_type = $result[$j]->type_sabmn;
				}
				
				
				$hasilAkhir[$j] = array("d_aset_thnanggar"          =>(string)$result[$j]->d_anggaran,
										"c_barang"           		=>(string)$result[$j]->c_barang,
										"i_aset"           			=>(string)$result[$j]->i_aset,
										"i_hw_investasi"          	=>(string)$result[$j]->i_hw_investasi,
										"ur_sskel"          		=>(string)$result[$j]->ur_sskel,
										"d_barang_peroleh"          =>(string)$result[$j]->d_perolehan,
										"type"          			=>$merk_type,
										"macadress"   				=>(string)$result[$j]->i_komputer_macaddress
										                               .' '.$result[$j]->i_komputer_macaddress1
																	   .' '.$result[$j]->i_komputer_macaddress2
																	   .' '.$result[$j]->i_komputer_macaddress3
																	   .' '.$result[$j]->i_komputer_macaddress4);
									 
									
			 }	
			 
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
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
public function getListPegawaibyNip($nip) {
        //echo 'test nip'.$nip;
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = "i_peg_nip = '".trim($data['nip'])."'";
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT n_peg,n_jabatan,i_orgb FROM e_sdm_pegawai_0_tm where i_peg_nip=?',$nip);
	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array(	"n_peg"           	 =>(string)$result[$j]->n_peg,
									"n_jabatan"          =>(string)$result[$j]->n_jabatan,
									"i_orgb"            =>(string)$result[$j]->i_orgb);
						       
		
		 }
        }
        	
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

//tambahan ========================== 29 apr 08 =======================

public function getPejabat($unitjabatan) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
			$where[]=$unitjabatan;
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			 
				$result = $db->fetchAll("select B.i_peg_nip, n_peg, A.n_jabatan, b.c_unit_kerja, b.i_orgb  
										from e_sdm_jabatan_0_tm A,  e_sdm_pegawai_0_tm B
										where a.i_peg_nip = b.i_peg_nip
										and b.c_unit_kerja = ?
										and b.c_unit_kerja = a.c_jabatan",$where);
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
			 
					$hasilAkhir[$j] = array("i_peg_nip"		=>(string)$result[$j]->i_peg_nip,
											"n_peg"   		=>(string)$result[$j]->n_peg, 
											"n_jabatan"     =>(string)$result[$j]->n_jabatan, 
											"n_peg"         =>(string)$result[$j]->n_peg,
											"c_unit_kerja"  =>(string)$result[$j]->c_unit_kerja,
											"i_orgb"    	=>(string)$result[$j]->i_orgb);
											
				}
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
}	
?>