<?php
class ast_toolkit_Service {
   
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

 public function insertDataToolkit(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("i_sw"         		=>$data['noSoftware'],
	                           "i_sw_toolkit"    	=>$data['noToolkit'],
						       "c_sw_jenistoolkit"  =>$data['jnsToolkit'],
						       "n_sw_toolkit" 		=>$data['nmToolkit'],
						       "e_sw_toolkit"   	=>$data['deskripsi'],
							   "q_sw_toolkit" 		=>$data['jumlah'],
						       "i_entry"       		=>"ast",
						       "d_entry"       		=>date("Y-m-d"),
							   "i_hw_investasi" 	=>$data['noinventtk']);
	    
 		 $db->insert('e_ast_toolkit__0_tr',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
   
 public function queryNourutmax($modl) {
	    //echo 'gen brg'.$modl;
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	    try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ);
		 //$where[] = $data['unitkr'];
		 $where[] = $modl;
		 $result = $db->fetchOne('SELECT gen_nomorbarang(?)',$where);
		
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

	
	//Query ... 29 okt 2007
	
	public function getSoftwareList() {
	   // echo 'service tool';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT a.i_sw,a.n_sw,a.n_sw_kelompok,a.i_sw_licensi,a.i_sw_versi,a.e_sw_platform,
									b.n_rekanan	FROM e_ast_software_0_tr a, e_ast_vendor_0_tr b
									where a.i_rekanan = b.i_rekanan order by a.i_sw');
         $jmlResult = count($result);
		
		for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_sw"             =>(string)$result[$j]->i_sw,
								   "n_sw"             =>(string)$result[$j]->n_sw,
								   "n_sw_kelompok"    =>(string)$result[$j]->n_sw_kelompok,
								   "i_sw_licensi"     =>(string)$result[$j]->i_sw_licensi,
	                               "i_sw_versi"       =>(string)$result[$j]->i_sw_versi,
								   "e_sw_platform"    =>(string)$result[$j]->e_sw_platform,
								   "n_rekanan"        =>(string)$result[$j]->n_rekanan);
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getCariSoftwareList($nmSoftware) {
		//echo '$nmSoftware..serv'.$nmSoftware;
	   $nsw = '%'.$nmSoftware.'%';
	   //echo '$nsw'.$nsw;
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT a.i_sw,a.n_sw,a.n_sw_kelompok,a.i_sw_licensi,a.i_sw_versi,a.e_sw_platform,
									b.n_rekanan	FROM e_ast_software_0_tr a, e_ast_vendor_0_tr b
									where a.i_rekanan = b.i_rekanan and a.n_sw like ?',$nsw);
         $jmlResult = count($result);
		 
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_sw"             =>(string)$result[$j]->i_sw,
								   "n_sw"             =>(string)$result[$j]->n_sw,
								   "n_sw_kelompok"    =>(string)$result[$j]->n_sw_kelompok,
								   "i_sw_licensi"     =>(string)$result[$j]->i_sw_licensi,
	                               "i_sw_versi"       =>(string)$result[$j]->i_sw_versi,
								   "e_sw_platform"    =>(string)$result[$j]->e_sw_platform,
								   "n_rekanan"        =>(string)$result[$j]->n_rekanan);
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getCariToolkitByNmSoftware($pageNumber,$itemPerPage,$nmSoftware) {   
	   $nsw = strtoupper($nmSoftware);
	   $sw = '%'.$nsw.'%';
	  
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $kondisi[] = $sw;
		 $where[] = $sw;
		 $where[] = $sw;
		 
		 
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*) FROM  e_ast_software_0_tr 
			                             where upper(n_sw) like ?" ,$kondisi);  
        
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT a.i_sw,a.n_sw,a.n_sw_kelompok,a.i_sw_licensi,a.i_sw_versi,a.e_sw_platform,
										b.n_rekanan as n_prsh,a.i_rekanan, i_rekanan_ref, i_hw_investasi	
										FROM e_ast_software_0_tr a,  e_ast_vendor_0_tr b
										where a.i_rekanan = b.i_rekanan 
										and b.i_rekanan_ref = '--'
										and upper(a.n_sw) like ?  
										UNION ALL
										SELECT a.i_sw,a.n_sw,a.n_sw_kelompok,a.i_sw_licensi,a.i_sw_versi,a.e_sw_platform,
										b.n_prsh,a.i_rekanan, i_rekanan_ref, i_hw_investasi	
										FROM e_ast_software_0_tr a, e_rekanan_prsh_0_tm b, e_ast_vendor_0_tr c
										where a.i_rekanan = c.i_rekanan 
										and c.i_rekanan_ref = b.i_rekanan 
										and upper(a.n_sw) like ?  
										ORDER BY i_sw
											limit $xLimit offset $xOffset",$where); 
			
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				//
				//===== ambil nama rekanan =================================
				$i_rekanan_ref	= (string)$result[$j]->i_rekanan_ref;	
						
						//ambil data dari pengadaan.....................................................................................
						$n_rekanan 	= $db->fetchCol("select n_rekanan
													from e_rekanan_0_0_tm a,e_rekanan_ijin_prsh_tm b,e_rekanan_ijin_usaha_tm c,e_rekanan_almt_prsh_tm d
													where a.i_rekanan = b.i_rekanan and a.i_rekanan = d.i_rekanan
													and b.i_prsh_ijinusaha = c.i_prsh_ijinusaha 
													and (c.n_prsh_ijinusaha  like 'TI%' or c.n_prsh_ijinusaha  like 'Komputer%')
													and a.i_rekanan=? ",$result[$j]->i_rekanan_ref);
						
						if($i_rekanan_ref =='--' || $i_rekanan_ref =='-' ){
							$hasilAkhir[$j] = array("i_sw"             	  =>(string)$result[$j]->i_sw,
											   "n_sw"             =>(string)$result[$j]->n_sw,
											   "n_sw_kelompok"    =>(string)$result[$j]->n_sw_kelompok,
											   "i_sw_licensi"     =>(string)$result[$j]->i_sw_licensi,
				                               "i_sw_versi"       =>(string)$result[$j]->i_sw_versi,
											   "e_sw_platform"    =>(string)$result[$j]->e_sw_platform,
											   "i_hw_investasi"    =>(string)$result[$j]->i_hw_investasi,
											   "n_rekanan"        =>(string)$result[$j]->n_rekanan);
						}else{
							$hasilAkhir[$j] = array("i_sw"             	  =>(string)$result[$j]->i_sw,
											   "n_sw"             =>(string)$result[$j]->n_sw,
											   "n_sw_kelompok"    =>(string)$result[$j]->n_sw_kelompok,
											   "i_sw_licensi"     =>(string)$result[$j]->i_sw_licensi,
				                               "i_sw_versi"       =>(string)$result[$j]->i_sw_versi,
											   "e_sw_platform"    =>(string)$result[$j]->e_sw_platform,
											   "i_hw_investasi"    =>(string)$result[$j]->i_hw_investasi,
											   "n_rekanan"        =>$n_rekanan[0]);
					
						}				
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function getToolkitList($nmSoftware) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_sw,i_sw_toolkit,c_sw_jenistoolkit,n_sw_toolkit,e_sw_toolkit,q_sw_toolkit,i_hw_investasi
									FROM e_ast_toolkit__0_tr
									where i_sw like ?',$nmSoftware);
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_sw"             =>(string)$result[$j]->i_sw,
								   "i_sw_toolkit"             =>(string)$result[$j]->i_sw_toolkit,
								   "c_sw_jenistoolkit"    =>(string)$result[$j]->c_sw_jenistoolkit,
								   "n_sw_toolkit"     =>(string)$result[$j]->n_sw_toolkit,
	                               "e_sw_toolkit"       =>(string)$result[$j]->e_sw_toolkit,
								   "q_sw_toolkit"    =>(string)$result[$j]->q_sw_toolkit,
								   "i_hw_investasi"    =>(string)$result[$j]->i_hw_investasi);
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function updateDataToolkit(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $atk_dtl_parm = array("c_sw_jenistoolkit"  =>$data['jnsToolkit'],
						       "n_sw_toolkit" 		=>$data['nmToolkit'],
						       "e_sw_toolkit"   	=>$data['deskripsi'],
							   "q_sw_toolkit" 		=>$data['jumlah'],
						       "i_entry"       		=>$data['nuser'],
						       "d_entry"       		=>date("Y-m-d"),
							   "i_hw_investasi" 	=>$data['noinventtk']);
							   
	     $where[] = "i_sw  =  '".trim($data['noSoftware'])."'";
	     $where[] = "i_sw_toolkit        =  '".trim($data['noToolkit'])."'";
		 $db->update('e_ast_toolkit__0_tr',$atk_dtl_parm, $where);
		 
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function deleteDataToolkit(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 
	  	 $where[] = "i_sw  =  '".trim($data['noSoftware'])."'";
	       $where[] = "i_sw_toolkit        =  '".trim($data['noToolkit'])."'";
		 
		 $db->delete('e_ast_toolkit__0_tr', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	//========================DISTRIBUSI SOFTWARE===================30/10/07===========
	public function insertDataDistribusi(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("i_sw"         		=>$data['noSoftware'],
	                           "thn_ang"   			=>$data['thnang'],
							   "kd_brg"   			=>$data['kdbrg'],
							   "no_aset"   			=>$data['noaset'],
							   "tgl_perlh"   		=>$data['tglPerl'],
							   "n_sw_installer"  	=>$data['techInst'],
						          "i_entry"       		=>$data['nuser'],

						           "d_entry"       		=>date("Y-m-d"));
	    
		
 		 $db->insert('e_ast_distribusi_software_tm',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function updateDataDistribusiSW(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $atk_dtl_parm = array("n_sw_installer"  	=>$data['techInst'],
						       "i_entry"       		=>"ast",
						       "d_entry"       		=>date("Y-m-d"));
							   
	     $where[] = "i_sw  =  '".trim($data['noSoftware'])."'";
	     $where[] = "thn_ang        =  '".trim($data['thnang'])."'";
		 $where[] = "kd_brg        =  '".trim($data['kdbrg'])."'";
		 $where[] = "no_aset        =  '".trim($data['noaset'])."'";
		 
		 $db->update('e_ast_distribusi_software_tm',$atk_dtl_parm, $where);
		 
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getDistribusiList($nmSoftware) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT a.thn_ang,a.kd_brg,a.tgl_perlh,i_komputer_macaddress,c_unit_kerja,
									a.i_ruang,to_char(a.no_aset,'09999') as no_aset,i_sw,n_sw_installer
									FROM e_ast_komputer_0_tr a, e_ast_distribusi_software_tm b
									where a.thn_ang=b.thn_ang
									and a.kd_brg=b.kd_brg
									and a.no_aset=b.no_aset
									and i_sw = ?",$nmSoftware);
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("thn_ang"            	=>(string)$result[$j]->thn_ang,
								   "kd_brg"             	=>(string)$result[$j]->kd_brg,
								   "no_aset"            	=>(string)$result[$j]->no_aset,
								   "tgl_perlh"          	=>(string)$result[$j]->tgl_perlh,
								   "i_komputer_macaddress"  =>(string)$result[$j]->i_komputer_macaddress,
								   "i_ruang"          		=>(string)$result[$j]->i_ruang,
								   "i_sw"             		=>(string)$result[$j]->i_sw,
								   "n_sw_installer"     	=>(string)$result[$j]->n_sw_installer);
								   
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function deleteDataDistribusiSW(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 
	  	 $where[] = "i_sw  =  '".trim($data['noSoftware'])."'";
	     $where[] = "thn_ang        =  '".trim($data['thnang'])."'";
		 $where[] = "kd_brg        =  '".trim($data['kdbrg'])."'";
		 $where[] = "no_aset        =  '".trim($data['noaset'])."'";
		 
		 $db->delete('e_ast_distribusi_software_tm', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
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
									FROM e_ast_komputer_0_tr ORDER BY thn_ang");
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
	
	//===========get ref=============================================================================================
}	
?>