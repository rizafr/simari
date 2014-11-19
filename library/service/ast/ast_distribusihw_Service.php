<?php
class ast_distribusihw_Service {
   
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
	   //echo 'nopeng delete'.$nopeng;
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
	   $kdserah = '%'.'HYN'.'%';
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
		 
			// $result = $db->fetchAll("SELECT A.i_barang_serah,A.d_barang_serah,
										// A.i_peg_nipterima,A.i_orgb_penerima,B.n_peg,
										// A.i_peg_nippemberi,A.i_orgb_pemberi,
										// A.e_keterangan 
										// FROM e_ast_dir_0_tm A, e_sdm_pegawai_0_tm B
										// where A.i_peg_nipterima = B.i_peg_nip
										// and i_barang_serah like ? 
										// and c_barang_statserah = ?
									 // limit $xLimit offset $xOffset",$where); 
									 
			$result = $db->fetchAll("SELECT A.i_barang_serah,A.d_barang_serah,
										A.i_peg_nipterima,A.i_orgb_penerima,
										A.i_peg_nippemberi,A.i_orgb_pemberi,
										A.e_keterangan 
										FROM e_ast_dir_0_tm A
										where i_barang_serah like ? 
										and c_barang_statserah = ?
									 limit $xLimit offset $xOffset",$where); 
									 
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
			$n_peg 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nipterima);

			$n_peg_pemberi 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nippemberi);
														
			$hasilAkhir[$j] = array("i_barang_serah"            =>(string)$result[$j]->i_barang_serah,
								   "d_barang_serah"            =>(string)$result[$j]->d_barang_serah,
								   "i_peg_nipterima"           =>(string)$result[$j]->i_peg_nipterima,
								   "i_orgb_penerima"           =>(string)$result[$j]->i_orgb_penerima,
								   "n_peg"  		           =>$n_peg[0],
								   "i_peg_nippemberi"          =>(string)$result[$j]->i_peg_nippemberi,
								   "i_orgb_pemberi"            =>(string)$result[$j]->i_orgb_pemberi,
								   "n_peg_pemberi"  		   =>$n_peg_pemberi[0],
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
		 $result = $db->fetchAll("SELECT a.c_barang,a.d_aset_thnanggar,to_char(no_aset,'09999') as no_aset,a.i_ruang, 
									 merk_type,ur_sskel, n_hw, n_lokasi,
									 f.i_hw_investasi
									 FROM e_ast_dir_item_tm a ,
									 e_sabm_t_master_tm c,e_ast_sskel_0_tr d,e_ast_lokasi_0_tr e,
									 e_ast_hardware_0_tm f
									 where  a.i_aset =  c.no_aset and  a.c_barang = c.kd_brg 
									 and f.i_aset =  c.no_aset  and  f.c_barang = c.kd_brg 
									 and a.i_barang_serah = ?
									 and substr(c.kd_brg,1,1) = d.kd_gol
								     and substr(c.kd_brg,2,2) = d.kd_bid 
									 and substr(c.kd_brg,4,2) = d.kd_kel
									 and substr(c.kd_brg,6,2) = d.kd_skel
									 and substr(c.kd_brg,8,3) = d.kd_sskel
									 and a.i_ruang = e.i_lokasi",$nopeng);
		
		 $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_barang"           =>(string)$result[$j]->c_barang,
								   "d_aset_thnanggar"   =>(string)$result[$j]->d_aset_thnanggar,
								   "no_aset"           	=>(string)$result[$j]->no_aset,
								   "i_ruang"           	=>(string)$result[$j]->i_ruang,
								   "merk_type"          =>(string)$result[$j]->merk_type,
								   "ur_sskel"           =>(string)$result[$j]->ur_sskel .' - '.$result[$j]->n_hw,
								   "i_hw_investasi"          =>(string)$result[$j]->i_hw_investasi,
								   "n_lokasi"			=>(string)$result[$j]->n_lokasi);
								  
								  
			  			       
		
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
		 $result = $db->fetchAll("SELECT a.c_barang,d_anggaran,to_char(a.i_aset,'09999') as no_aset,i_hw, 
									 c_hw_type,i_hw_register,e_hw 
									 FROM e_ast_dir_item_tm a ,
										  e_ast_hardware_0_tm b
										  where  b.i_aset =  a.i_aset  and a.i_barang_serah = ?
										   and  b.c_barang = a.c_barang and d_anggaran = d_aset_thnanggar",$nopeng);
								      
		
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_barang"           			 =>(string)$result[$j]->c_barang,
								   "d_anggaran"          			 =>(string)$result[$j]->d_anggaran,
								   "no_aset"           				 =>(string)$result[$j]->no_aset,
								   "i_hw"           =>(string)$result[$j]->i_hw,
								   "e_hw"           				 =>(string)$result[$j]->e_hw,
								   "c_hw_type"             =>(string)$result[$j]-c_hw_type,
								   "i_hw_register"         =>(string)$result[$j]->i_hw_register);
								    
								  
								  
			  			       
		
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
		 $result = $db->fetchAll("SELECT a.c_barang, a.d_aset_thnanggar,to_char(no_aset,'09999') as no_aset,a.i_ruang,e_keterangan,
									 merk_type,ur_sskel,d_barang_peroleh,n_lokasi, n_hw, i_hw,
									 f.i_hw_investasi
									 FROM e_ast_dir_item_tm a,e_ast_dir_0_tm b,
									      e_sabm_t_master_tm c,e_ast_sskel_0_tr d,e_ast_lokasi_0_tr e, e_ast_hardware_0_tm f
									 where a.i_barang_serah = b.i_barang_serah	
									    and  a.i_aset =  c.no_aset  and a.c_barang = c.kd_brg
										and  f.i_aset =  c.no_aset  and f.c_barang = c.kd_brg
									    and a.c_barang=? and no_aset=? 
										and a.i_ruang=e.i_lokasi
										and substr(c.kd_brg,1,1) = d.kd_gol
									    and substr(c.kd_brg,2,2) = d.kd_bid 
										and substr(c.kd_brg,4,2) = d.kd_kel
										and substr(c.kd_brg,6,2) = d.kd_skel
										and substr(c.kd_brg,8,3) = d.kd_sskel",$params);
		
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_barang"           	=>(string)$result[$j]->c_barang,
								   "d_aset_thnanggar"       =>(string)$result[$j]->d_aset_thnanggar,
								   "no_aset"           		=>(string)$result[$j]->no_aset,
								   "i_ruang"           		=>(string)$result[$j]->i_ruang,
								   "e_keterangan"           =>(string)$result[$j]->e_keterangan,
								   "merk_type"           	=>(string)$result[$j]->merk_type,
								   "i_hw"           	=>(string)$result[$j]->i_hw,
								   "ur_sskel"           	=>(string)$result[$j]->ur_sskel .' - '. $result[$j]->n_hw,
								   "d_barang_peroleh"       =>(string)$result[$j]->d_barang_peroleh,
								   "i_hw_investasi"       =>(string)$result[$j]->i_hw_investasi,
								   "n_lokasi"				=>(string)$result[$j]->n_lokasi); 
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
						       "i_orgb_penerima"  		 =>substr($data['orgPenerima'],1,6),
							   "i_peg_nippemberi"  		 =>trim($data['nipPemberi']),
							   "i_orgb_pemberi"  	 	 =>substr($data['orgPemberi'],1,6),
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
	     $db->beginTransaction();
		 $atk_mast_prm = array("i_barang_serah"         		=>$data['nopeng'],
	                           "d_aset_thnanggar"    	        =>$data['dasetthnanggar'],
						       "c_barang"                       =>$data['cbarang'],
							   "i_aset"              		    =>$data['iaset'],
							   "d_barang_peroleh"  	            =>$data['dbarangperoleh'], 
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
	public function UpdateAstHardware(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $item_parm = array("i_orgb"  		    =>$data['unitkr'],
							"i_lokasi"			=>$data['iruang'],
	                        "i_entry"       	=>$data['nuser'],
						    "d_entry"       	=>date("Y-m-d"));
						  	    
							   
							   
	      $where[] = "c_barang        =  '".$data['cbarang']."'";
		  $where[] = "i_aset          =  '".$data['iaset']."'";
		  $where[] = "d_anggaran      =  '".$data['dasetthnanggar']."'";
	     $db->update('e_ast_hardware_0_tm',$item_parm, $where);
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
	     $item_parm = array("i_orgb"  		             =>null,
							 "i_entry"       		            =>$data['nuser'],
						     "d_entry"       		     =>date("Y-m-d"));
						  	    
							   
							   
	      $where[] = "c_barang        =  '".$data['cbarang']."'";
		  $where[] = "i_aset          =  '".$data['iaset']."'";
		  $where[] = "d_anggaran      =  '".$data['thn']."'";
	     $db->update('e_ast_hardware_0_tm',$item_parm, $where);
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
	public function getCariNamaBrgDIR($pageNumber,$itemPerPage,$namaBarang) {
	   $namaBarang = strtoupper($namaBarang);
	   $nbrg = '%'.$namaBarang.'%';
	  
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*) 
 						 from   e_ast_hardware_0_tm 
						 where  n_hw like ?
					     and  i_orgb is null",$nbrg);
						 
        
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT distinct a.d_anggaran, a.c_barang, to_char(a.i_aset,'09999') as  i_aset ,  
									a.d_perolehan,a.i_hw,a.n_hw, d.ur_sskel,
									a.i_hw_investasi
									FROM   e_ast_hardware_0_tm  a,e_sabm_t_master_tm c,e_ast_sskel_0_tr d
									where a.c_barang = c.kd_brg
									and a.i_aset = c.no_aset
									and substr(c.kd_brg,1,1) = d.kd_gol
									and substr(c.kd_brg,2,2) = d.kd_bid 
									and substr(c.kd_brg,4,2) = d.kd_kel
									and substr(c.kd_brg,6,2) = d.kd_skel
									and substr(c.kd_brg,8,3) = d.kd_sskel
									and I_orgb is null
									and n_hw like ?   
									limit $xLimit offset $xOffset",$nbrg); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("d_aset_thnanggar"          =>(string)$result[$j]->d_anggaran,
										"c_barang"           		=>(string)$result[$j]->c_barang,
										"i_aset"           			=>(string)$result[$j]->i_aset,
										"n_hw"          		=>(string)$result[$j]->ur_sskel. ' - '. $result[$j]->n_hw,
										"d_barang_peroleh"          =>(string)$result[$j]->d_perolehan,
										"i_hw"   				=>(string)$result[$j]->i_hw,
										"i_hw_investasi"          =>(string)$result[$j]->i_hw_investasi);
									 
									
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
	
	// add lokasi ================================================================================ 07 mei 08 ======
	
	public function getLokasiList($pageNumber,$itemPerPage,$namaLokasi) {
	   $namaLokasi = strtoupper($namaLokasi);
	   $nbrg = '%'.$namaLokasi.'%';
	  
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*) 
											from e_ast_lokasi_0_tr 
											where n_lokasi like ?",$nbrg);
						 
        
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT i_lokasi, n_lokasi, a_lokasi,q_lokasi_lantai, i_peg_nip
										FROM e_ast_lokasi_0_tr
											where n_lokasi like ?
											 order by i_lokasi
											limit $xLimit offset $xOffset",$nbrg); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_lokasi"          		=>(string)$result[$j]->i_lokasi,
										"n_lokasi"           		=>(string)$result[$j]->n_lokasi,
										"a_lokasi"           		=>(string)$result[$j]->a_lokasi,
										"q_lokasi_lantai"          	=>(string)$result[$j]->q_lokasi_lantai,
										"d_barang_peroleh"          =>(string)$result[$j]->d_perolehan,
										"i_peg_nip"   				=>(string)$result[$j]->i_peg_nip);
									 
									
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	//tambahan ==========================08 mei 08 =======================

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