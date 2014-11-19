<?php
class ast_software_Service {
   
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
	 * fungsi untuk Software =====================
	 ***************************/
	public function getSoftwareList($pageNumber,$itemPerPage) {
	    //echo 'service tool';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			// $hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_software_0_tr a, e_ast_vendor_0_tr b
			                            // where a.i_rekanan = b.i_rekanan");
			
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_software_0_tr a WHERE c_status_lengkap is null");
		 }
		 else
		 {
			
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;	
			// $result = $db->fetchAll("SELECT a.i_sw,a.n_sw,a.i_sw_kelompok,c.n_sw_kelompok,a.i_sw_licensi,a.i_sw_versi,
									// a.e_sw_platform,e_sw_platform1,e_sw_platform2,e_sw_platform3,
									// b.n_rekanan	,i_rekanan_ref, a_prsh_jalan
									// FROM e_ast_software_0_tr a, e_ast_vendor_0_tr b,
									// e_ast_kelompok_software_tr c
									// where a.i_rekanan = b.i_rekanan
									// and a.i_sw_kelompok    = c.i_sw_kelompok
									// order by a.i_sw 
									// limit $xLimit offset $xOffset"); 
									
			$result = $db->fetchAll("SELECT a.i_sw,a.n_sw,a.i_sw_kelompok,n_sw_kelompok,a.i_sw_licensi,a.i_sw_versi,
									a.e_sw_platform,e_sw_platform1,e_sw_platform2,e_sw_platform3,e_sw_platform4,i_rekanan,
									i_hw_investasi
									FROM e_ast_software_0_tr a
									WHERE c_status_lengkap is null
									order by a.i_sw 
									limit $xLimit offset $xOffset"); 
									
			$jmlResult = count($result);
		
			for ($j = 0; $j < $jmlResult; $j++) {
				//===== ambil nama rekanan =================================
				$i_rekanan	= (string)$result[$j]->i_rekanan;
						$n_rekananVendor 	= $db->fetchCol("select n_rekanan
													from e_ast_vendor_0_tr
													where i_rekanan=? ",$result[$j]->i_rekanan);
						
						$i_rekanan_ref 	= $db->fetchCol("select i_rekanan_ref
													from e_ast_vendor_0_tr
													where i_rekanan=? ",$result[$j]->i_rekanan);
						
						//ambil data dari pengadaan.....................................................................................
						$n_rekanan 	= $db->fetchCol("select n_rekanan
													from e_rekanan_0_0_tm a,e_rekanan_ijin_prsh_tm b,e_rekanan_ijin_usaha_tm c,e_rekanan_almt_prsh_tm d
													where a.i_rekanan = b.i_rekanan and a.i_rekanan = d.i_rekanan
													and b.i_prsh_ijinusaha = c.i_prsh_ijinusaha 
													and (c.n_prsh_ijinusaha  like 'TI%' or c.n_prsh_ijinusaha  like 'Komputer%')
													and a.i_rekanan=? ",$result[$j]->i_rekanan_ref);
						
						$a_prsh_jalan 	= $db->fetchCol("select a_prsh_jalan
													from e_rekanan_0_0_tm a,e_rekanan_ijin_prsh_tm b,e_rekanan_ijin_usaha_tm c,e_rekanan_almt_prsh_tm d
													where a.i_rekanan = b.i_rekanan and a.i_rekanan = d.i_rekanan
													and b.i_prsh_ijinusaha = c.i_prsh_ijinusaha 
													and (c.n_prsh_ijinusaha  like 'TI%' or c.n_prsh_ijinusaha  like 'Komputer%')
													and a.i_rekanan=? ",$result[$j]->i_rekanan_ref);
						//...................................................................................................................
						if($i_rekanan_ref =='--' || $i_rekanan_ref =='-' ){
								$hasilAkhir[$j] = array("i_sw"             			=>(string)$result[$j]->i_sw,
															"n_sw"             		=>(string)$result[$j]->n_sw,
															"n_sw_kelompok"    		=>(string)$result[$j]->n_sw_kelompok,
															"i_sw_kelompok"    		=>(string)$result[$j]->i_sw_kelompok,
															"i_sw_licensi"     		=>(string)$result[$j]->i_sw_licensi,
															"i_sw_versi"       		=>(string)$result[$j]->i_sw_versi,
															"e_sw_platform"    		=>(string)$result[$j]->e_sw_platform,
															"e_sw_platform1"    	=>(string)$result[$j]->e_sw_platform1,
															"e_sw_platform2"    	=>(string)$result[$j]->e_sw_platform2,
															"e_sw_platform3"    	=>(string)$result[$j]->e_sw_platform3,
															"e_sw_platform4"    	=>(string)$result[$j]->e_sw_platform4,
															"d_sw_releasepublish"   =>(string)$result[$j]->d_sw_releasepublish,
															"c_matauang"    		=>(string)$result[$j]->c_matauang,
															"v_sw_harga" 			=>(string)$result[$j]->v_sw_harga,
															"d_sw_lastupdate"    	=>(string)$result[$j]->d_sw_lastupdate,
															"e_sw_reqsystem"    	=>(string)$result[$j]->e_sw_reqsystem,
															"d_sw_expiregaransi"    =>(string)$result[$j]->d_sw_expiregaransi,
															"i_rekanan"    			=>(string)$result[$j]->i_rekanan,
															"d_sw_peroleh"    		=>(string)$result[$j]->d_sw_peroleh,
															"d_sw_expirelicensi"    =>(string)$result[$j]->d_sw_expirelicensi,
															"q_sw_licensi"    		=>(string)$result[$j]->q_sw_licensi,
															"c_sw_tipelicensi"    	=>(string)$result[$j]->c_sw_tipelicensi,
															"i_sw_nomorlicensi"    	=>(string)$result[$j]->i_sw_nomorlicensi,
															"c_sw_sertifikat"    	=>(string)$result[$j]->c_sw_sertifikat,
															"i_sw_sertifikat"    	=>(string)$result[$j]->i_sw_sertifikat,
															"i_hw_investasi"    	=>(string)$result[$j]->i_hw_investasi,
															"n_rekanan"        		=>$n_rekananVendor[0]);
						}else{
								$hasilAkhir[$j] = array("i_sw"             			=>(string)$result[$j]->i_sw,
															"n_sw"             		=>(string)$result[$j]->n_sw,
															"n_sw_kelompok"    		=>(string)$result[$j]->n_sw_kelompok,
															"i_sw_kelompok"    		=>(string)$result[$j]->i_sw_kelompok,
															"i_sw_licensi"     		=>(string)$result[$j]->i_sw_licensi,
															"i_sw_versi"       		=>(string)$result[$j]->i_sw_versi,
															"e_sw_platform"    		=>(string)$result[$j]->e_sw_platform,
															"e_sw_platform1"    	=>(string)$result[$j]->e_sw_platform1,
															"e_sw_platform2"    	=>(string)$result[$j]->e_sw_platform2,
															"e_sw_platform3"    	=>(string)$result[$j]->e_sw_platform3,
															"e_sw_platform4"    	=>(string)$result[$j]->e_sw_platform4,
															"d_sw_releasepublish"   =>(string)$result[$j]->d_sw_releasepublish,
															"c_matauang"    		=>(string)$result[$j]->c_matauang,
															"v_sw_harga" 			=>(string)$result[$j]->v_sw_harga,
															"d_sw_lastupdate"    	=>(string)$result[$j]->d_sw_lastupdate,
															"e_sw_reqsystem"    	=>(string)$result[$j]->e_sw_reqsystem,
															"d_sw_expiregaransi"    =>(string)$result[$j]->d_sw_expiregaransi,
															"i_rekanan"    			=>(string)$result[$j]->i_rekanan,
															"d_sw_peroleh"    		=>(string)$result[$j]->d_sw_peroleh,
															"d_sw_expirelicensi"    =>(string)$result[$j]->d_sw_expirelicensi,
															"q_sw_licensi"    		=>(string)$result[$j]->q_sw_licensi,
															"c_sw_tipelicensi"    	=>(string)$result[$j]->c_sw_tipelicensi,
															"i_sw_nomorlicensi"    	=>(string)$result[$j]->i_sw_nomorlicensi,
															"c_sw_sertifikat"    	=>(string)$result[$j]->c_sw_sertifikat,
															"i_sw_sertifikat"    	=>(string)$result[$j]->i_sw_sertifikat,
															"i_hw_investasi"    	=>(string)$result[$j]->i_hw_investasi,
															"n_rekanan"        		=>$i_rekanan_ref[0]);
						}
			}
        }			
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

//===========================================================================
	
public function getCariSoftwareList($pageNumber,$itemPerPage,$nmSoftware) 
{
	//echo '$nmSoftware..serv'.$nmSoftware;
	$nsw[] = '%'.$nmSoftware.'%';
	//echo '$nsw'.$nsw;
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	try 
	{
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		if(($pageNumber==0) && ($itemPerPage==0))
		{
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_software_0_tr a, e_ast_vendor_0_tr b
			where a.i_rekanan = b.i_rekanan and a.n_sw like ?",$nsw);
		}
		else
		{
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;	  		 
			$result = $db->fetchAll("SELECT a.i_sw,a.n_sw,a.n_sw_kelompok,a.i_sw_licensi,a.i_sw_versi,a.e_sw_platform,
			b.n_rekanan	FROM e_ast_software_0_tr a, e_ast_vendor_0_tr b
			where a.i_rekanan = b.i_rekanan and a.n_sw like ? Order by a.n_sw
			limit $xLimit offset $xOffset",$nsw);
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) 
			{
				$hasilAkhir[$j] = array("i_sw"             =>(string)$result[$j]->i_sw,
					"n_sw"             	=>(string)$result[$j]->n_sw,
					"n_sw_kelompok"    	=>(string)$result[$j]->n_sw_kelompok,
					"i_sw_licensi"     	=>(string)$result[$j]->i_sw_licensi,
					"i_sw_versi"       	=>(string)$result[$j]->i_sw_versi,
					"e_sw_platform"    	=>(string)$result[$j]->e_sw_platform,
					"d_sw_releasepublish"   =>(string)$result[$j]->d_sw_releasepublish,
					"c_matauang"    	=>(string)$result[$j]->c_matauang,
					"v_sw_harga" 		=>(string)$result[$j]->v_sw_harga,
					"d_sw_lastupdate"    	=>(string)$result[$j]->d_sw_lastupdate,
					"e_sw_reqsystem"    	=>(string)$result[$j]->e_sw_reqsystem,
					"d_sw_expiregaransi"    =>(string)$result[$j]->d_sw_expiregaransi,
					"i_rekanan"    		=>(string)$result[$j]->i_rekanan,
					"d_sw_peroleh"    	=>(string)$result[$j]->d_sw_peroleh,
					"d_sw_expirelicensi"    =>(string)$result[$j]->d_sw_expirelicensi,
					"q_sw_licensi"    	=>(string)$result[$j]->q_sw_licensi,
					"c_sw_tipelicensi"    	=>(string)$result[$j]->c_sw_tipelicensi,
					"i_sw_nomorlicensi"    	=>(string)$result[$j]->i_sw_nomorlicensi,
					"c_sw_sertifikat"    	=>(string)$result[$j]->c_sw_sertifikat,
					"i_sw_sertifikat"    	=>(string)$result[$j]->i_sw_sertifikat,
					"n_rekanan"        	=>(string)$result[$j]->n_rekanan);
			}
		}		 
		return $hasilAkhir;
	} 
		catch (Exception $e) 
	{
		echo $e->getMessage().'<br>';
		return 'gagal <br>';
	}
}
//=========================================================================
	public function getMataUangList() {
       
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT  c_matauang, c_matauang_simbol, n_matauang  FROM e_keu_matauang_0_tr ORDER BY c_matauang_simbol');
	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_matauang"           =>(string)$result[$j]->c_matauang,
								   "c_matauang_simbol"    =>(string)$result[$j]->c_matauang_simbol,
								   "n_matauang"           =>(string)$result[$j]->n_matauang);
								  
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getVendorList_Old() {
       
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT  i_rekanan, n_rekanan   FROM e_ast_vendor_0_tr ORDER BY i_rekanan');
	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_rekanan"           =>(string)$result[$j]->i_rekanan,
								   "n_rekanan"           =>(string)$result[$j]->n_rekanan);
								  
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getVendorList2($pageNumber,$itemPerPage) 
	{
		$namaBarang = strtoupper($namaBarang);
		$nbrg 	= $namaBarang.'%';
		//echo "nbrg =".$nbrg;
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{
			//$where[] =$kbr;
			$where[] =$nbrg;
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) from FROM e_ast_vendor_0_tr ORDER BY i_rekanan");
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				
				$result = $db->fetchAll("SELECT  i_rekanan, n_rekanan   FROM e_ast_vendor_0_tr ORDER BY i_rekanan
										limit $xLimit offset $xOffset");
								 
				
				$jmlResult = count($result);
				//echo "jumlah di server =".$jmlResult;
				
				for ($j = 0; $j < $jmlResult; $j++) 
				{
					$hasilAkhir[$j] = array("i_rekanan"           =>(string)$result[$j]->i_rekanan,
											"n_rekanan"           =>(string)$result[$j]->n_rekanan);
					
					
				}	
			}	 
			return $hasilAkhir;
		} 
		catch (Exception $e) 
		{
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	public function getVendorList($pageNumber,$itemPerPage) {
       
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
          
	   try {
         $db->setFetchMode(Zend_Db::FETCH_OBJ);
         if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_vendor_0_tr");
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;			 
				$result = $db->fetchAll("SELECT i_rekanan, n_rekanan, a_rekanan,n_rekanan_agendistr, i_rekanan_telp, n_rekanan_kontak,
										n_vendor_ctgr,i_rekanan_ref,a_prsh_jalan FROM e_ast_vendor_0_tr order by i_rekanan
										limit $xLimit offset $xOffset"); 
		         $jmlResult = count($result);
				
				 if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {
						$i_rekanan_ref	= (string)$result[$j]->i_rekanan_ref;	
						
						//ambil data dari pengadaan.....................................................................................
						$n_rekanan 	= $db->fetchCol("select n_rekanan
													from e_rekanan_0_0_tm a,e_rekanan_ijin_prsh_tm b,e_rekanan_ijin_usaha_tm c,e_rekanan_almt_prsh_tm d
													where a.i_rekanan = b.i_rekanan and a.i_rekanan = d.i_rekanan
													and b.i_prsh_ijinusaha = c.i_prsh_ijinusaha 
													and (c.n_prsh_ijinusaha  like 'TI%' or c.n_prsh_ijinusaha  like 'Komputer%')
													and a.i_rekanan=? ",$result[$j]->i_rekanan_ref);
						
						$a_prsh_jalan 	= $db->fetchCol("select a_prsh_jalan
													from e_rekanan_0_0_tm a,e_rekanan_ijin_prsh_tm b,e_rekanan_ijin_usaha_tm c,e_rekanan_almt_prsh_tm d
													where a.i_rekanan = b.i_rekanan and a.i_rekanan = d.i_rekanan
													and b.i_prsh_ijinusaha = c.i_prsh_ijinusaha 
													and (c.n_prsh_ijinusaha  like 'TI%' or c.n_prsh_ijinusaha  like 'Komputer%')
													and a.i_rekanan=? ",$result[$j]->i_rekanan_ref);
						//...................................................................................................................
						if($i_rekanan_ref =='--' || $i_rekanan_ref =='-' ){
						$hasilAkhir[$j] = array("i_rekanan"           		=>(string)$result[$j]->i_rekanan,
												   "n_rekanan"           	=>(string)$result[$j]->n_rekanan,
												   "a_rekanan"           	=>(string)$result[$j]->a_rekanan,
												   "n_rekanan_agendistr"    =>(string)$result[$j]->n_rekanan_agendistr,
												   "i_rekanan_telp"         =>(string)$result[$j]->i_rekanan_telp,
												   "n_rekanan_kontak"       =>(string)$result[$j]->n_rekanan_kontak,
												   "n_vendor_ctgr"          =>(string)$result[$j]->n_vendor_ctgr,
												   "i_rekanan_ref"          =>(string)$result[$j]->i_rekanan_ref,
												   "a_prsh_jalan"           =>(string)$result[$j]->a_prsh_jalan);
						}else{
						$hasilAkhir[$j] = array("i_rekanan"           		=>(string)$result[$j]->i_rekanan,
												   "n_rekanan"           	=>$n_rekanan[0],
												   "a_rekanan"           	=>$a_prsh_jalan[0],
												   "n_rekanan_agendistr"    =>(string)$result[$j]->n_rekanan_agendistr,
												   "i_rekanan_telp"         =>(string)$result[$j]->i_rekanan_telp,
												   "n_rekanan_kontak"       =>(string)$result[$j]->n_rekanan_kontak,
												   "n_vendor_ctgr"          =>(string)$result[$j]->n_vendor_ctgr,
												   "i_rekanan_ref"          =>(string)$result[$j]->i_rekanan_ref,
												   "a_prsh_jalan"           =>(string)$result[$j]->a_prsh_jalan);
						
						}
					}
				}	
            }				
	     return $hasilAkhir;
	   } catch (Exception $e) {
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
	
	public function insertDataSoftware(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	      $subTglRelease 	= substr($data['tglRelease'],0,1);
		  $subTglReleaseLu 	= substr($data['tglReleaseLu'],0,1);
		  $subTglGaransi 	= substr($data['tglGaransi'],0,1);
		  $subTglPerl 		= substr($data['tglPerl'],0,1);
		  $SubTglExLis 		= substr($data['tglExLis'],0,1);
			
		  
		  if ($subTglRelease == '-') {
		     $tglRelease =null;			 
		  }else {
		     $tglRelease = $data['tglRelease'];			 
		  }
		  
		  if ($subTglReleaseLu == '-') {
		     $tglReleaseLu =null;			 
		  }else {
		     $tglReleaseLu = $data['tglReleaseLu'];			 
		  }
		  
		  
		  if ($subTglGaransi == '-') {
		     $tglGaransi =null;			 
		  }else {
		     $tglGaransi = $data['tglGaransi'];			 
		  }
		  
		  
		  if ($subTglPerl == '-') {
		     $tglPerl =null;			 
		  }else {
		     $tglPerl = $data['tglPerl'];			 
		  }
		  
		   if ($SubTglExLis == '-') {
		     $tglExLis =null;			 
		  }else {
		     $tglExLis = $data['tglExLis'];			 
		  }
		  
		  	  
		  
		   
			$jmlUser 			= $data['jmlUser'];
			$harga 			= $data['harga'];
			
			if ($jmlUser == ''){
				$jmlUser =0;
			}
			if ($harga == ''){
				$harga =0;
			}
			
		
	     $db->beginTransaction();
		 /**
			if ($SubTglExLis == '-' && $subTglPerl == '-' && $subTglGaransi =='-' && $subTglReleaseLu =='-' && $subTglRelease =='-'){			    
				
				$atk_mast_prm = array("i_sw"         			=>$data['noSoftware'],
			                            "n_sw"    				=>$data['nmSoftware'],
										"i_sw_kelompok"  		=>$data['kdsoftware'],
										"n_sw_kelompok"  		=>$data['kelompok'],
										"i_sw_versi" 			=>$data['versi'],
										"c_matauang" 			=>$data['kdmatauang'],
										"v_sw_harga" 			=>$harga,
										"e_sw_reqsystem" 		=>$data['Required'],
										"e_sw_platform" 		=>$data['platform'],
										"e_sw_platform1" 		=>$data['platform1'],
										"e_sw_platform2" 		=>$data['platform2'],
										"e_sw_platform3" 		=>$data['platform3'],
										"e_sw_platform4" 		=>$data['platform4'],
										"i_rekanan" 			=>$data['noVendor'],
										"i_sw_licensi" 			=>$data['licensi'],
										"q_sw_licensi" 			=>$jmlUser,
										"c_sw_tipelicensi" 		=>$data['TypLicensi'],
										"i_sw_nomorlicensi" 	=>$data['NoLicensi'],
										"c_sw_sertifikat" 		=>$data['kdsertifikat'],
										"i_sw_sertifikat" 		=>$data['NoSertifikat'],
										"i_entry"       		=>$data['userid'],
										"d_entry"       		=>date("Y-m-d"));
			}else{
			if ($subTglRelease == '-'){
			    
				$atk_mast_prm = array("i_sw"         			=>$data['noSoftware'],
			                            "n_sw"    				=>$data['nmSoftware'],
										"i_sw_kelompok"  		=>$data['kdsoftware'],
										"n_sw_kelompok"  		=>$data['kelompok'],
										"i_sw_versi" 			=>$data['versi'],
										"c_matauang" 			=>$data['kdmatauang'],
										"v_sw_harga" 			=>$harga,
										"d_sw_lastupdate" 		=>$data['tglReleaseLu'],
										"e_sw_reqsystem" 		=>$data['Required'],
										"e_sw_platform" 		=>$data['platform'],
										"e_sw_platform1" 		=>$data['platform1'],
										"e_sw_platform2" 		=>$data['platform2'],
										"e_sw_platform3" 		=>$data['platform3'],
										"e_sw_platform4" 		=>$data['platform4'],
										"d_sw_expiregaransi" 	=>$data['tglGaransi'],
										"i_rekanan" 			=>$data['noVendor'],
										"d_sw_peroleh" 			=>$data['tglPerl'],
										"d_sw_expirelicensi" 	=>$data['tglExLis'],
										"i_sw_licensi" 			=>$data['licensi'],
										"q_sw_licensi" 			=>$jmlUser,
										"c_sw_tipelicensi" 		=>$data['TypLicensi'],
										"i_sw_nomorlicensi" 	=>$data['NoLicensi'],
										"c_sw_sertifikat" 		=>$data['kdsertifikat'],
										"i_sw_sertifikat" 		=>$data['NoSertifikat'],
										"i_entry"       		=>$data['userid'],
										"d_entry"       		=>date("Y-m-d"));
			}
			if ($subTglReleaseLu == '-'){			    
			    
				$atk_mast_prm = array("i_sw"         			=>$data['noSoftware'],
			                            "n_sw"    				=>$data['nmSoftware'],
										"i_sw_kelompok"  		=>$data['kdsoftware'],
										"n_sw_kelompok"  		=>$data['kelompok'],
										"i_sw_versi" 			=>$data['versi'],
										"d_sw_releasepublish"   =>$data['tglRelease'],
										"c_matauang" 			=>$data['kdmatauang'],
										"v_sw_harga" 			=>$harga,
										"e_sw_reqsystem" 		=>$data['Required'],
										"e_sw_platform" 		=>$data['platform'],
										"e_sw_platform1" 		=>$data['platform1'],
										"e_sw_platform2" 		=>$data['platform2'],
										"e_sw_platform3" 		=>$data['platform3'],
										"e_sw_platform4" 		=>$data['platform4'],
										"d_sw_expiregaransi" 	=>$data['tglGaransi'],
										"i_rekanan" 			=>$data['noVendor'],
										"d_sw_peroleh" 			=>$data['tglPerl'],
										"d_sw_expirelicensi" 	=>$data['tglExLis'],
										"i_sw_licensi" 			=>$data['licensi'],
										"q_sw_licensi" 			=>$jmlUser,
										"c_sw_tipelicensi" 		=>$data['TypLicensi'],
										"i_sw_nomorlicensi" 	=>$data['NoLicensi'],
										"c_sw_sertifikat" 		=>$data['kdsertifikat'],
										"i_sw_sertifikat" 		=>$data['NoSertifikat'],
										"i_entry"       		=>$data['userid'],
										"d_entry"       		=>date("Y-m-d"));
			}
			if ($subTglGaransi == '-'){
			    
				$atk_mast_prm = array("i_sw"         			=>$data['noSoftware'],
			                            "n_sw"    				=>$data['nmSoftware'],
										"i_sw_kelompok"  		=>$data['kdsoftware'],
										"n_sw_kelompok"  		=>$data['kelompok'],
										"i_sw_versi" 			=>$data['versi'],
										"d_sw_releasepublish"   =>$data['tglRelease'],
										"c_matauang" 			=>$data['kdmatauang'],
										"v_sw_harga" 			=>$harga,
										"e_sw_reqsystem" 		=>$data['Required'],
										"e_sw_platform" 		=>$data['platform'],
										"e_sw_platform1" 		=>$data['platform1'],
										"e_sw_platform2" 		=>$data['platform2'],
										"e_sw_platform3" 		=>$data['platform3'],
										"e_sw_platform4" 		=>$data['platform4'],
										"i_rekanan" 			=>$data['noVendor'],
										"d_sw_peroleh" 			=>$data['tglPerl'],
										"d_sw_expirelicensi" 	=>$data['tglExLis'],
										"i_sw_licensi" 			=>$data['licensi'],
										"q_sw_licensi" 			=>$jmlUser,
										"c_sw_tipelicensi" 		=>$data['TypLicensi'],
										"i_sw_nomorlicensi" 	=>$data['NoLicensi'],
										"c_sw_sertifikat" 		=>$data['kdsertifikat'],
										"i_sw_sertifikat" 		=>$data['NoSertifikat'],
										"i_entry"       		=>$data['userid'],
										"d_entry"       		=>date("Y-m-d"));
			}
			if ($subTglPerl == '-'){
			    
				$atk_mast_prm = array("i_sw"         			=>$data['noSoftware'],
			                            "n_sw"    				=>$data['nmSoftware'],
										"i_sw_kelompok"  		=>$data['kdsoftware'],
										"n_sw_kelompok"  		=>$data['kelompok'],
										"i_sw_versi" 			=>$data['versi'],
										"d_sw_releasepublish"   =>$data['tglRelease'],
										"c_matauang" 			=>$data['kdmatauang'],
										"v_sw_harga" 			=>$harga,
										"e_sw_reqsystem" 		=>$data['Required'],
										"e_sw_platform" 		=>$data['platform'],
										"e_sw_platform1" 		=>$data['platform1'],
										"e_sw_platform2" 		=>$data['platform2'],
										"e_sw_platform3" 		=>$data['platform3'],
										"e_sw_platform4" 		=>$data['platform4'],
										"d_sw_expiregaransi" 	=>$data['tglGaransi'],
										"i_rekanan" 			=>$data['noVendor'],
										"d_sw_expirelicensi" 	=>$data['tglExLis'],
										"i_sw_licensi" 			=>$data['licensi'],
										"q_sw_licensi" 			=>$jmlUser,
										"c_sw_tipelicensi" 		=>$data['TypLicensi'],
										"i_sw_nomorlicensi" 	=>$data['NoLicensi'],
										"c_sw_sertifikat" 		=>$data['kdsertifikat'],
										"i_sw_sertifikat" 		=>$data['NoSertifikat'],
										"i_entry"       		=>$data['userid'],
										"d_entry"       		=>date("Y-m-d"));
			}
			if ($SubTglExLis == '-'){
			    
				$atk_mast_prm = array("i_sw"         			=>$data['noSoftware'],
			                            "n_sw"    				=>$data['nmSoftware'],
										"i_sw_kelompok"  		=>$data['kdsoftware'],
										"n_sw_kelompok"  		=>$data['kelompok'],
										"i_sw_versi" 			=>$data['versi'],
										"d_sw_releasepublish"   =>$data['tglRelease'],
										"c_matauang" 			=>$data['kdmatauang'],
										"v_sw_harga" 			=>$harga,
										"e_sw_reqsystem" 		=>$data['Required'],
										"e_sw_platform" 		=>$data['platform'],
										"e_sw_platform1" 		=>$data['platform1'],
										"e_sw_platform2" 		=>$data['platform2'],
										"e_sw_platform3" 		=>$data['platform3'],
										"e_sw_platform4" 		=>$data['platform4'],
										"d_sw_expiregaransi" 	=>$data['tglGaransi'],
										"i_rekanan" 			=>$data['noVendor'],
										"d_sw_peroleh" 			=>$data['tglPerl'],
										"i_sw_licensi" 			=>$data['licensi'],
										"q_sw_licensi" 			=>$jmlUser,
										"c_sw_tipelicensi" 		=>$data['TypLicensi'],
										"i_sw_nomorlicensi" 	=>$data['NoLicensi'],
										"c_sw_sertifikat" 		=>$data['kdsertifikat'],
										"i_sw_sertifikat" 		=>$data['NoSertifikat'],
										"i_entry"       		=>$data['userid'],
										"d_entry"       		=>date("Y-m-d"));
			}
			
			if ($SubTglExLis != '-' && $subTglPerl != '-' && $subTglGaransi !='-' && $subTglReleaseLu !='-' && $subTglRelease !='-')
			{			    
			    
  			    $atk_mast_prm = array("i_sw"         			=>$data['noSoftware'],
			                            "n_sw"    				=>$data['nmSoftware'],
										"i_sw_kelompok"  		=>$data['kdsoftware'],
										"n_sw_kelompok"  		=>$data['kelompok'],
										"i_sw_versi" 			=>$data['versi'],
										"d_sw_releasepublish"   =>$data['tglRelease'],
										"c_matauang" 			=>$data['kdmatauang'],
										"v_sw_harga" 			=>$harga,
										"d_sw_lastupdate" 		=>$data['tglReleaseLu'],
										"e_sw_reqsystem" 		=>$data['Required'],
										"e_sw_platform" 		=>$data['platform'],
										"e_sw_platform1" 		=>$data['platform1'],
										"e_sw_platform2" 		=>$data['platform2'],
										"e_sw_platform3" 		=>$data['platform3'],
										"e_sw_platform4" 		=>$data['platform4'],										
										"d_sw_expiregaransi" 	=>$data['tglGaransi'],
										"i_rekanan" 			=>$data['noVendor'],
										"d_sw_peroleh" 			=>$data['tglPerl'],
										"d_sw_expirelicensi" 	=>$data['tglExLis'],										
										"i_sw_licensi" 			=>$data['licensi'],
										"q_sw_licensi" 			=>$jmlUser,
										"c_sw_tipelicensi" 		=>$data['TypLicensi'],
										"i_sw_nomorlicensi" 	=>$data['NoLicensi'],
										"c_sw_sertifikat" 		=>$data['kdsertifikat'],
										"i_sw_sertifikat" 		=>$data['NoSertifikat'],
										"i_entry"       		=>$data['userid'],
										"d_entry"       		=>date("Y-m-d"));
			}
			
			}
		 **/
		     //// Ina : 23-10-2008 :Awal
			 $atk_mast_prm = array("i_sw"         			=>$data['noSoftware'],
			                            "n_sw"    				=>$data['nmSoftware'],
										"i_sw_kelompok"  		=>$data['kdsoftware'],
										"n_sw_kelompok"  		=>$data['kelompok'],
										"i_sw_versi" 			=>$data['versi'],
										"d_sw_releasepublish"   =>$tglRelease,
										"c_matauang" 			=>$data['kdmatauang'],
										"v_sw_harga" 			=>$harga,
										"d_sw_lastupdate" 		=>$tglReleaseLu,
										"e_sw_reqsystem" 		=>$data['Required'],
										"e_sw_platform" 		=>$data['platform'],
										"e_sw_platform1" 		=>$data['platform1'],
										"e_sw_platform2" 		=>$data['platform2'],
										"e_sw_platform3" 		=>$data['platform3'],
										"e_sw_platform4" 		=>$data['platform4'],										
										"d_sw_expiregaransi" 	=>$tglGaransi,
										"i_rekanan" 			=>$data['noVendor'],
										"d_sw_peroleh" 			=>$tglPerl,
										"d_sw_expirelicensi" 	=>$tglExLis,										
										"i_sw_licensi" 			=>$data['licensi'],
										"q_sw_licensi" 			=>$jmlUser,
										"c_sw_tipelicensi" 		=>$data['TypLicensi'],
										"i_sw_nomorlicensi" 	=>$data['NoLicensi'],
										"c_sw_sertifikat" 		=>$data['kdsertifikat'],
										"i_sw_sertifikat" 		=>$data['NoSertifikat'],
										"i_entry"       		=>$data['userid'],
										"d_entry"       		=>date("Y-m-d"),
										"i_hw_investasi"       	=>$data['noinvent']);
			 //// Ina : 23-10-2008 : Akhir
								
	     
	    
		
 		 $db->insert('e_ast_software_0_tr',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function updateDataSoftware(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	   
			$subTglRelease 		= substr($data['tglRelease'],0,1);
			$subTglReleaseLu 	= substr($data['tglReleaseLu'],0,1);
			$subTglGaransi 		= substr($data['tglGaransi'],0,1);
			$subTglPerl 		= substr($data['tglPerl'],0,1);
			$SubTglExLis 		= substr($data['tglExLis'],0,1);
			
			
					  
		  if ($subTglRelease == '-') {
		     $tglRelease =null;			 
		  }else {
		     $tglRelease = $data['tglRelease'];			 
		  }
		  
		  if ($subTglReleaseLu == '-') {
		     $tglReleaseLu =null;			 
		  }else {
		     $tglReleaseLu = $data['tglReleaseLu'];			 
		  }
		  
		  
		  if ($subTglGaransi == '-') {
		     $tglGaransi =null;			 
		  }else {
		     $tglGaransi = $data['tglGaransi'];			 
		  }
		  
		  
		  if ($subTglPerl == '-') {
		     $tglPerl =null;			 
		  }else {
		     $tglPerl = $data['tglPerl'];			 
		  }
		  
		   if ($SubTglExLis == '-') {
		     $tglExLis =null;			 
		  }else {
		     $tglExLis = $data['tglExLis'];			 
		  }
		  
			$jmlUser 			= $data['jmlUser'];
			$harga 			= $data['harga'];
			
			if ($jmlUser == ''){
				$jmlUser =0;
			}
			if ($harga == ''){
				$harga =0;
			}
			
		
	     $db->beginTransaction();
		 /**
			if ($SubTglExLis == '-' && $subTglPerl == '-' && $subTglGaransi =='-' && $subTglReleaseLu =='-' && $subTglRelease =='-'){
				$atk_dtl_parm = array(  "n_sw"    				=>$data['nmSoftware'],
										"i_sw_kelompok"  		=>$data['kdsoftware'],
										"n_sw_kelompok"  		=>$data['kelompok'],
										"i_sw_versi" 			=>$data['versi'],
										"c_matauang" 			=>$data['kdmatauang'],
										"v_sw_harga" 			=>$harga,
										"e_sw_reqsystem" 		=>$data['Required'],
										"e_sw_platform" 		=>$data['platform'],
										"e_sw_platform1" 		=>$data['platform1'],
										"e_sw_platform2" 		=>$data['platform2'],
										"e_sw_platform3" 		=>$data['platform3'],
										"e_sw_platform4" 		=>$data['platform4'],
										"i_rekanan" 			=>$data['noVendor'],
										"i_sw_licensi" 			=>$data['licensi'],
										"q_sw_licensi" 			=>$jmlUser,
										"c_sw_tipelicensi" 		=>$data['TypLicensi'],
										"i_sw_nomorlicensi" 	=>$data['NoLicensi'],
										"c_sw_sertifikat" 		=>$data['kdsertifikat'],
										"i_sw_sertifikat" 		=>$data['NoSertifikat'],
										"c_status_lengkap" 		=>$data['nStatus'],
										"i_entry"       		=>$data['userid'],
										"d_entry"       		=>date("Y-m-d"));
			}else{
			if ($subTglRelease !='-' && ($SubTglExLis == '-' && $subTglPerl == '-' && $subTglGaransi =='-' && $subTglReleaseLu =='-' )){
				$atk_dtl_parm = array(  "n_sw"    				=>$data['nmSoftware'],
										"i_sw_kelompok"  		=>$data['kdsoftware'],
										"n_sw_kelompok"  		=>$data['kelompok'],
										"i_sw_versi" 			=>$data['versi'],
										"d_sw_releasepublish"   =>$data['tglRelease'],
										"c_matauang" 			=>$data['kdmatauang'],
										"v_sw_harga" 			=>$harga,
										"e_sw_reqsystem" 		=>$data['Required'],
										"e_sw_platform" 		=>$data['platform'],
										"e_sw_platform1" 		=>$data['platform1'],
										"e_sw_platform2" 		=>$data['platform2'],
										"e_sw_platform3" 		=>$data['platform3'],
										"e_sw_platform4" 		=>$data['platform4'],
										"i_rekanan" 			=>$data['noVendor'],
										"i_sw_licensi" 			=>$data['licensi'],
										"q_sw_licensi" 			=>$jmlUser,
										"c_sw_tipelicensi" 		=>$data['TypLicensi'],
										"i_sw_nomorlicensi" 	=>$data['NoLicensi'],
										"c_sw_sertifikat" 		=>$data['kdsertifikat'],
										"i_sw_sertifikat" 		=>$data['NoSertifikat'],
										"c_status_lengkap" 		=>$data['nStatus'],
										"i_entry"       		=>$data['userid'],
										"d_entry"       		=>date("Y-m-d"));
			}
			if ($subTglRelease !='-' && $subTglReleaseLu !='-' ){
				$atk_dtl_parm = array(  "n_sw"    				=>$data['nmSoftware'],
										"i_sw_kelompok"  		=>$data['kdsoftware'],
										"n_sw_kelompok"  		=>$data['kelompok'],
										"i_sw_versi" 			=>$data['versi'],
										"d_sw_releasepublish"   =>$data['tglRelease'],
										"c_matauang" 			=>$data['kdmatauang'],
										"v_sw_harga" 			=>$harga,
										"d_sw_lastupdate" 		=>$data['tglReleaseLu'],
										"e_sw_reqsystem" 		=>$data['Required'],
										"e_sw_platform" 		=>$data['platform'],
										"e_sw_platform1" 		=>$data['platform1'],
										"e_sw_platform2" 		=>$data['platform2'],
										"e_sw_platform3" 		=>$data['platform3'],
										"e_sw_platform4" 		=>$data['platform4'],
										"i_rekanan" 			=>$data['noVendor'],
										"i_sw_licensi" 			=>$data['licensi'],
										"q_sw_licensi" 			=>$jmlUser,
										"c_sw_tipelicensi" 		=>$data['TypLicensi'],
										"i_sw_nomorlicensi" 	=>$data['NoLicensi'],
										"c_sw_sertifikat" 		=>$data['kdsertifikat'],
										"i_sw_sertifikat" 		=>$data['NoSertifikat'],
										"i_entry"       		=>$data['userid'],
										"d_entry"       		=>date("Y-m-d"));
			}
			if ($subTglRelease !='-' && $subTglGaransi !='-' ){
				$atk_dtl_parm = array(  "n_sw"    				=>$data['nmSoftware'],
										"i_sw_kelompok"  		=>$data['kdsoftware'],
										"n_sw_kelompok"  		=>$data['kelompok'],
										"i_sw_versi" 			=>$data['versi'],
										"d_sw_releasepublish"   =>$data['tglRelease'],
										"c_matauang" 			=>$data['kdmatauang'],
										"v_sw_harga" 			=>$harga,
										"d_sw_lastupdate" 		=>$data['tglReleaseLu'],
										"e_sw_reqsystem" 		=>$data['Required'],
										"e_sw_platform" 		=>$data['platform'],
										"e_sw_platform1" 		=>$data['platform1'],
										"e_sw_platform2" 		=>$data['platform2'],
										"e_sw_platform3" 		=>$data['platform3'],
										"e_sw_platform4" 		=>$data['platform4'],
										"d_sw_expiregaransi" 	=>$data['tglGaransi'],
										"i_rekanan" 			=>$data['noVendor'],
										"i_sw_licensi" 			=>$data['licensi'],
										"q_sw_licensi" 			=>$jmlUser,
										"c_sw_tipelicensi" 		=>$data['TypLicensi'],
										"i_sw_nomorlicensi" 	=>$data['NoLicensi'],
										"c_sw_sertifikat" 		=>$data['kdsertifikat'],
										"i_sw_sertifikat" 		=>$data['NoSertifikat'],
										"c_status_lengkap" 		=>$data['nStatus'],
										"i_entry"       		=>$data['userid'],
										"d_entry"       		=>date("Y-m-d"));
			}
			if ($subTglRelease !='-' && $subTglPerl !='-' ){
				$atk_dtl_parm = array(  "n_sw"    				=>$data['nmSoftware'],
										"i_sw_kelompok"  		=>$data['kdsoftware'],
										"n_sw_kelompok"  		=>$data['kelompok'],
										"i_sw_versi" 			=>$data['versi'],
										"d_sw_releasepublish"   =>$data['tglRelease'],
										"c_matauang" 			=>$data['kdmatauang'],
										"v_sw_harga" 			=>$harga,
										"e_sw_reqsystem" 		=>$data['Required'],
										"e_sw_platform" 		=>$data['platform'],
										"e_sw_platform1" 		=>$data['platform1'],
										"e_sw_platform2" 		=>$data['platform2'],
										"e_sw_platform3" 		=>$data['platform3'],
										"e_sw_platform4" 		=>$data['platform4'],
										"i_rekanan" 			=>$data['noVendor'],
										"d_sw_peroleh" 			=>$data['tglPerl'],
										"i_sw_licensi" 			=>$data['licensi'],
										"q_sw_licensi" 			=>$jmlUser,
										"c_sw_tipelicensi" 		=>$data['TypLicensi'],
										"i_sw_nomorlicensi" 	=>$data['NoLicensi'],
										"c_sw_sertifikat" 		=>$data['kdsertifikat'],
										"i_sw_sertifikat" 		=>$data['NoSertifikat'],
										"c_status_lengkap" 		=>$data['nStatus'],
										"i_entry"       		=>$data['userid'],
										"d_entry"       		=>date("Y-m-d"));
			}
			if ($subTglRelease !='-' && $SubTglExLis !='-' ){
				$atk_dtl_parm = array(  "n_sw"    				=>$data['nmSoftware'],
										"i_sw_kelompok"  		=>$data['kdsoftware'],
										"n_sw_kelompok"  		=>$data['kelompok'],
										"i_sw_versi" 			=>$data['versi'],
										"d_sw_releasepublish"   =>$data['tglRelease'],
										"c_matauang" 			=>$data['kdmatauang'],
										"v_sw_harga" 			=>$harga,
										"e_sw_reqsystem" 		=>$data['Required'],
										"e_sw_platform" 		=>$data['platform'],
										"e_sw_platform1" 		=>$data['platform1'],
										"e_sw_platform2" 		=>$data['platform2'],
										"e_sw_platform3" 		=>$data['platform3'],
										"e_sw_platform4" 		=>$data['platform4'],
										"i_rekanan" 			=>$data['noVendor'],
										"d_sw_expirelicensi" 	=>$data['tglExLis'],
										"i_sw_licensi" 			=>$data['licensi'],
										"q_sw_licensi" 			=>$jmlUser,
										"c_sw_tipelicensi" 		=>$data['TypLicensi'],
										"i_sw_nomorlicensi" 	=>$data['NoLicensi'],
										"c_sw_sertifikat" 		=>$data['kdsertifikat'],
										"i_sw_sertifikat" 		=>$data['NoSertifikat'],
										"c_status_lengkap" 		=>$data['nStatus'],
										"i_entry"       		=>$data['userid'],
										"d_entry"       		=>date("Y-m-d"));
			}
			if ($subTglRelease =='-' && ($SubTglExLis != '-' && $subTglPerl != '-' && $subTglGaransi !='-' && $subTglReleaseLu !='-' )){
				$atk_dtl_parm = array(  "n_sw"    				=>$data['nmSoftware'],
										"i_sw_kelompok"  		=>$data['kdsoftware'],
										"n_sw_kelompok"  		=>$data['kelompok'],
										"i_sw_versi" 			=>$data['versi'],
										"c_matauang" 			=>$data['kdmatauang'],
										"v_sw_harga" 			=>$harga,
										"d_sw_lastupdate" 		=>$data['tglReleaseLu'],
										"e_sw_reqsystem" 		=>$data['Required'],
										"e_sw_platform" 		=>$data['platform'],
										"e_sw_platform1" 		=>$data['platform1'],
										"e_sw_platform2" 		=>$data['platform2'],
										"e_sw_platform3" 		=>$data['platform3'],
										"e_sw_platform4" 		=>$data['platform4'],
										"d_sw_expiregaransi" 	=>$data['tglGaransi'],
										"i_rekanan" 			=>$data['noVendor'],
										"d_sw_peroleh" 			=>$data['tglPerl'],
										"d_sw_expirelicensi" 	=>$data['tglExLis'],
										"i_sw_licensi" 			=>$data['licensi'],
										"q_sw_licensi" 			=>$jmlUser,
										"c_sw_tipelicensi" 		=>$data['TypLicensi'],
										"i_sw_nomorlicensi" 	=>$data['NoLicensi'],
										"c_sw_sertifikat" 		=>$data['kdsertifikat'],
										"i_sw_sertifikat" 		=>$data['NoSertifikat'],
										"c_status_lengkap" 		=>$data['nStatus'],
										"i_entry"       		=>$data['userid'],
										"d_entry"       		=>date("Y-m-d"));
			}
			if ($subTglReleaseLu =='-' && ($SubTglExLis != '-' && $subTglPerl != '-' && $subTglGaransi !='-' && $subTglRelease !='-' )){
				$atk_dtl_parm = array(  "n_sw"    				=>$data['nmSoftware'],
										"i_sw_kelompok"  		=>$data['kdsoftware'],
										"n_sw_kelompok"  		=>$data['kelompok'],
										"i_sw_versi" 			=>$data['versi'],
										"d_sw_releasepublish"   =>$data['tglRelease'],
										"c_matauang" 			=>$data['kdmatauang'],
										"v_sw_harga" 			=>$harga,
										"e_sw_reqsystem" 		=>$data['Required'],
										"e_sw_platform" 		=>$data['platform'],
										"e_sw_platform1" 		=>$data['platform1'],
										"e_sw_platform2" 		=>$data['platform2'],
										"e_sw_platform3" 		=>$data['platform3'],
										"e_sw_platform4" 		=>$data['platform4'],
										"d_sw_expiregaransi" 	=>$data['tglGaransi'],
										"i_rekanan" 			=>$data['noVendor'],
										"d_sw_peroleh" 			=>$data['tglPerl'],
										"d_sw_expirelicensi" 	=>$data['tglExLis'],
										"i_sw_licensi" 			=>$data['licensi'],
										"q_sw_licensi" 			=>$jmlUser,
										"c_sw_tipelicensi" 		=>$data['TypLicensi'],
										"i_sw_nomorlicensi" 	=>$data['NoLicensi'],
										"c_sw_sertifikat" 		=>$data['kdsertifikat'],
										"i_sw_sertifikat" 		=>$data['NoSertifikat'],
										"c_status_lengkap" 		=>$data['nStatus'],
										"i_entry"       		=>$data['userid'],
										"d_entry"       		=>date("Y-m-d"));
			}
			if ($subTglGaransi =='-' && ($SubTglExLis != '-' && $subTglPerl != '-' && $subTglReleaseLu !='-' && $subTglRelease !='-' )){
				$atk_dtl_parm = array(  "n_sw"    				=>$data['nmSoftware'],
										"i_sw_kelompok"  		=>$data['kdsoftware'],
										"n_sw_kelompok"  		=>$data['kelompok'],
										"i_sw_versi" 			=>$data['versi'],
										"d_sw_releasepublish"   =>$data['tglRelease'],
										"c_matauang" 			=>$data['kdmatauang'],
										"v_sw_harga" 			=>$harga,
										"e_sw_reqsystem" 		=>$data['Required'],
										"e_sw_platform" 		=>$data['platform'],
										"e_sw_platform1" 		=>$data['platform1'],
										"e_sw_platform2" 		=>$data['platform2'],
										"e_sw_platform3" 		=>$data['platform3'],
										"e_sw_platform4" 		=>$data['platform4'],
										"i_rekanan" 			=>$data['noVendor'],
										"d_sw_peroleh" 			=>$data['tglPerl'],
										"d_sw_expirelicensi" 	=>$data['tglExLis'],
										"i_sw_licensi" 			=>$data['licensi'],
										"q_sw_licensi" 			=>$jmlUser,
										"c_sw_tipelicensi" 		=>$data['TypLicensi'],
										"i_sw_nomorlicensi" 	=>$data['NoLicensi'],
										"c_sw_sertifikat" 		=>$data['kdsertifikat'],
										"i_sw_sertifikat" 		=>$data['NoSertifikat'],
										"c_status_lengkap" 		=>$data['nStatus'],
										"i_entry"       		=>$data['userid'],
										"d_entry"       		=>date("Y-m-d"));
			}
			if ($subTglPerl =='-' && ($SubTglExLis != '-' && $subTglGaransi != '-' && $subTglReleaseLu !='-' && $subTglRelease !='-' )){
				$atk_dtl_parm = array(  "n_sw"    				=>$data['nmSoftware'],
										"i_sw_kelompok"  		=>$data['kdsoftware'],
										"n_sw_kelompok"  		=>$data['kelompok'],
										"i_sw_versi" 			=>$data['versi'],
										"d_sw_releasepublish"   =>$data['tglRelease'],
										"c_matauang" 			=>$data['kdmatauang'],
										"v_sw_harga" 			=>$harga,
										"e_sw_reqsystem" 		=>$data['Required'],
										"e_sw_platform" 		=>$data['platform'],
										"e_sw_platform1" 		=>$data['platform1'],
										"e_sw_platform2" 		=>$data['platform2'],
										"e_sw_platform3" 		=>$data['platform3'],
										"e_sw_platform4" 		=>$data['platform4'],
										"d_sw_expiregaransi" 	=>$data['tglGaransi'],
										"i_rekanan" 			=>$data['noVendor'],
										"d_sw_expirelicensi" 	=>$data['tglExLis'],
										"i_sw_licensi" 			=>$data['licensi'],
										"q_sw_licensi" 			=>$jmlUser,
										"c_sw_tipelicensi" 		=>$data['TypLicensi'],
										"i_sw_nomorlicensi" 	=>$data['NoLicensi'],
										"c_sw_sertifikat" 		=>$data['kdsertifikat'],
										"i_sw_sertifikat" 		=>$data['NoSertifikat'],
										"c_status_lengkap" 		=>$data['nStatus'],
										"i_entry"       		=>$data['userid'],
										"d_entry"       		=>date("Y-m-d"));
			}
			if ($SubTglExLis =='-' && ($subTglPerl != '-' && $subTglGaransi != '-' && $subTglReleaseLu !='-' && $subTglRelease !='-' )){
				$atk_dtl_parm = array(  "n_sw"    				=>$data['nmSoftware'],
										"i_sw_kelompok"  		=>$data['kdsoftware'],
										"n_sw_kelompok"  		=>$data['kelompok'],
										"i_sw_versi" 			=>$data['versi'],
										"d_sw_releasepublish"   =>$data['tglRelease'],
										"c_matauang" 			=>$data['kdmatauang'],
										"v_sw_harga" 			=>$harga,
										"e_sw_reqsystem" 		=>$data['Required'],
										"e_sw_platform" 		=>$data['platform'],
										"e_sw_platform1" 		=>$data['platform1'],
										"e_sw_platform2" 		=>$data['platform2'],
										"e_sw_platform3" 		=>$data['platform3'],
										"e_sw_platform4" 		=>$data['platform4'],
										"d_sw_expiregaransi" 	=>$data['tglGaransi'],
										"i_rekanan" 			=>$data['noVendor'],
										"d_sw_peroleh" 			=>$data['tglPerl'],
										"i_sw_licensi" 			=>$data['licensi'],
										"q_sw_licensi" 			=>$jmlUser,
										"c_sw_tipelicensi" 		=>$data['TypLicensi'],
										"i_sw_nomorlicensi" 	=>$data['NoLicensi'],
										"c_sw_sertifikat" 		=>$data['kdsertifikat'],
										"i_sw_sertifikat" 		=>$data['NoSertifikat'],
										"c_status_lengkap" 		=>$data['nStatus'],
										"i_entry"       		=>$data['userid'],
										"d_entry"       		=>date("Y-m-d"));
			}
			}
			
	     **/
		 // Ina : 23-10-2008 : Awal 
							   
							   $atk_dtl_parm = array(  "n_sw"    				=>$data['nmSoftware'],
										"i_sw_kelompok"  		=>$data['kdsoftware'],
										"n_sw_kelompok"  		=>$data['kelompok'],
										"i_sw_versi" 			=>$data['versi'],
										"d_sw_releasepublish"   =>$tglRelease,
										"c_matauang" 			=>$data['kdmatauang'],
										"v_sw_harga" 			=>$harga,
										"d_sw_lastupdate" 		=>$tglReleaseLu,
										"e_sw_reqsystem" 		=>$data['Required'],
										"e_sw_platform" 		=>$data['platform'],
										"e_sw_platform1" 		=>$data['platform1'],
										"e_sw_platform2" 		=>$data['platform2'],
										"e_sw_platform3" 		=>$data['platform3'],
										"e_sw_platform4" 		=>$data['platform4'],
										"d_sw_expiregaransi" 	=>$tglGaransi,
										"i_rekanan" 			=>$data['noVendor'],
										"d_sw_peroleh" 			=>$tglPerl,
										"d_sw_expirelicensi" 	=>$tglExLis,																				
										"i_sw_licensi" 			=>$data['licensi'],
										"q_sw_licensi" 			=>$jmlUser,
										"c_sw_tipelicensi" 		=>$data['TypLicensi'],
										"i_sw_nomorlicensi" 	=>$data['NoLicensi'],
										"c_sw_sertifikat" 		=>$data['kdsertifikat'],
										"i_sw_sertifikat" 		=>$data['NoSertifikat'],
										"c_status_lengkap" 		=>$data['nStatus'],
										"i_entry"       		=>$data['userid'],
										"d_entry"       		=>date("Y-m-d"),
										"i_hw_investasi"       	=>$data['noinvent']);
							

										
	     $where[] = "i_sw  =  '".trim($data['noSoftware'])."'";
	     $db->update('e_ast_software_0_tr',$atk_dtl_parm, $where);
		 
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function deleteDataSoftware(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 
	  	 $where[] = "i_sw  =  '".trim($data['noSoftware'])."'";
	     
		 $db->delete('e_ast_software_0_tr', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getTechSoftwareList($noSoftware) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT a.i_sw,a.c_sw_teknikal,a.n_sw_teknikal,a.i_sw_teknikaltelp
								    FROM e_ast_teknikal_software_tr a
									where a.i_sw like ? Order by a.n_sw_teknikal ',$noSoftware);
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_sw"             =>(string)$result[$j]->i_sw,
								   "c_sw_teknikal"             =>(string)$result[$j]->c_sw_teknikal,
								   "n_sw_teknikal"    =>(string)$result[$j]->n_sw_teknikal,
								   "i_sw_teknikaltelp"     =>(string)$result[$j]->i_sw_teknikaltelp);
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function insertDataTechSoftware(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
	     $atk_mast_prm = array("i_sw"         		=>$data['noSoftware'],
	                           "c_sw_teknikal"    	=>$data['noTechSw'],
						       "n_sw_teknikal"  	=>$data['nmTechSw'],
						       "i_sw_teknikaltelp" 		=>$data['noTelp'],
						       "i_entry"       			=>$data['nuser'],
						       "d_entry"       			=>date("Y-m-d"));
	    
		
 		 $db->insert('e_ast_teknikal_software_tr',$atk_mast_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function updateDataTechSoftware(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $atk_dtl_parm = array("n_sw_teknikal"  	=>$data['nmTechSw'],
						       "i_sw_teknikaltelp" 		=>$data['noTelp'],
						       "i_entry"       			=>$data['nuser'], 
						       "d_entry"       			=>date("Y-m-d"));
							   
							   
	     $where[] = "i_sw  =  '".trim($data['noSoftware'])."'";
		 $where[] = "c_sw_teknikal  =  '".trim($data['noTechSw'])."'";
	     $db->update('e_ast_teknikal_software_tr',$atk_dtl_parm, $where);
		 
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
          echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function deleteDataTechSoftware(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 
	  	 $where[] = "i_sw  =  '".trim($data['noSoftware'])."'";
		 $where[] = "c_sw_teknikal  =  '".trim($data['noTechSw'])."'";
	     
		 $db->delete('e_ast_teknikal_software_tr', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}


//===============================================================================	
public function getDataSoftware($prmSoftwareEdit) 
{
	$status='A';
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	try 
	{
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 	 
		 // $result = $db->fetchAll('SELECT a.i_sw,a.n_sw,a.i_sw_kelompok,a.n_sw_kelompok,a.i_sw_licensi,a.i_sw_versi,
									// a.e_sw_platform,e_sw_platform1,e_sw_platform2,e_sw_platform3,
									// d_sw_releasepublish,c_matauang,v_sw_harga,d_sw_lastupdate,e_sw_reqsystem,
									// d_sw_expiregaransi,a.i_rekanan,d_sw_peroleh,d_sw_expirelicensi,q_sw_licensi,
									// c_sw_tipelicensi,i_sw_nomorlicensi,c_sw_sertifikat,i_sw_sertifikat,
									// b.n_rekanan	,i_rekanan_ref, a_prsh_jalan
									// FROM e_ast_software_0_tr a, e_ast_vendor_0_tr b
									// where a.i_rekanan = b.i_rekanan and a.i_sw = ? Order by a.n_sw ',$prmSoftwareEdit);
									
		$result = $db->fetchAll('SELECT a.i_sw,a.n_sw,a.i_sw_kelompok,a.n_sw_kelompok,a.i_sw_licensi,a.i_sw_versi,
									a.e_sw_platform,e_sw_platform1,e_sw_platform2,e_sw_platform3,e_sw_platform4,
									d_sw_releasepublish,c_matauang,v_sw_harga,d_sw_lastupdate,e_sw_reqsystem,
									d_sw_expiregaransi,a.i_rekanan,d_sw_peroleh,d_sw_expirelicensi,q_sw_licensi,
									c_sw_tipelicensi,i_sw_nomorlicensi,c_sw_sertifikat,i_sw_sertifikat,
									i_hw_investasi
									FROM e_ast_software_0_tr a
									where a.i_sw = ? Order by a.n_sw ',$prmSoftwareEdit);
									
		$jmlResult = count($result);	
		//echo 'jmldata :'.$jmlResult;
		//echo 'no sw :'.$prmSoftwareEdit;
		if($jmlResult > 0)
		{
		 	for ($j = 0; $j < $jmlResult; $j++) 
		 	{
			//===== ambil nama rekanan =================================
				$i_rekanan_ref	= (string)$result[$j]->i_rekanan_ref;	
						
						//ambil data dari pengadaan.....................................................................................
						$n_rekanan 	= $db->fetchCol("select n_rekanan
													from e_rekanan_0_0_tm a,e_rekanan_ijin_prsh_tm b,e_rekanan_ijin_usaha_tm c,e_rekanan_almt_prsh_tm d
													where a.i_rekanan = b.i_rekanan and a.i_rekanan = d.i_rekanan
													and b.i_prsh_ijinusaha = c.i_prsh_ijinusaha 
													and (c.n_prsh_ijinusaha  like 'TI%' or c.n_prsh_ijinusaha  like 'Komputer%')
													and a.i_rekanan=? ",$result[$j]->i_rekanan_ref);
						
						$a_prsh_jalan 	= $db->fetchCol("select a_prsh_jalan
													from e_rekanan_0_0_tm a,e_rekanan_ijin_prsh_tm b,e_rekanan_ijin_usaha_tm c,e_rekanan_almt_prsh_tm d
													where a.i_rekanan = b.i_rekanan and a.i_rekanan = d.i_rekanan
													and b.i_prsh_ijinusaha = c.i_prsh_ijinusaha 
													and (c.n_prsh_ijinusaha  like 'TI%' or c.n_prsh_ijinusaha  like 'Komputer%')
													and a.i_rekanan=? ",$result[$j]->i_rekanan_ref);
						//...................................................................................................................
						if($i_rekanan_ref =='--' || $i_rekanan_ref =='-' )
						{
								$hasilAkhir[$j] = array("i_sw"             		=>(string)$result[$j]->i_sw,
														"n_sw"             		=>(string)$result[$j]->n_sw,
														"i_sw_kelompok"    		=>(string)$result[$j]->i_sw_kelompok,
														"n_sw_kelompok"    		=>(string)$result[$j]->n_sw_kelompok,
														"i_sw_licensi"     		=>(string)$result[$j]->i_sw_licensi,
														"i_sw_versi"       		=>(string)$result[$j]->i_sw_versi,
														"e_sw_platform"    		=>(string)$result[$j]->e_sw_platform,
														"e_sw_platform1"    	=>(string)$result[$j]->e_sw_platform1,
														"e_sw_platform2"    	=>(string)$result[$j]->e_sw_platform2,
														"e_sw_platform3"    	=>(string)$result[$j]->e_sw_platform3,
														"e_sw_platform4"    	=>(string)$result[$j]->e_sw_platform4,
														"d_sw_releasepublish"   =>(string)$result[$j]->d_sw_releasepublish,
														"c_matauang"    		=>(string)$result[$j]->c_matauang,
														"v_sw_harga" 			=>(string)$result[$j]->v_sw_harga,
														"d_sw_lastupdate"    	=>(string)$result[$j]->d_sw_lastupdate,
														"e_sw_reqsystem"    	=>(string)$result[$j]->e_sw_reqsystem,
														"d_sw_expiregaransi"    =>(string)$result[$j]->d_sw_expiregaransi,
														"i_rekanan"    			=>(string)$result[$j]->i_rekanan,
														"d_sw_peroleh"    		=>(string)$result[$j]->d_sw_peroleh,
														"d_sw_expirelicensi"    =>(string)$result[$j]->d_sw_expirelicensi,
														"q_sw_licensi"    		=>(string)$result[$j]->q_sw_licensi,
														"c_sw_tipelicensi"    	=>(string)$result[$j]->c_sw_tipelicensi,
														"i_sw_nomorlicensi"    	=>(string)$result[$j]->i_sw_nomorlicensi,
														"c_sw_sertifikat"    	=>(string)$result[$j]->c_sw_sertifikat,
														"i_sw_sertifikat"    	=>(string)$result[$j]->i_sw_sertifikat,
														"i_hw_investasi"    	=>(string)$result[$j]->i_hw_investasi,
														"n_rekanan"        		=>(string)$result[$j]->n_rekanan);
						}else{
								$hasilAkhir[$j] = array("i_sw"             		=>(string)$result[$j]->i_sw,
														"n_sw"             		=>(string)$result[$j]->n_sw,
														"i_sw_kelompok"    		=>(string)$result[$j]->i_sw_kelompok,
														"n_sw_kelompok"    		=>(string)$result[$j]->n_sw_kelompok,
														"i_sw_licensi"     		=>(string)$result[$j]->i_sw_licensi,
														"i_sw_versi"       		=>(string)$result[$j]->i_sw_versi,
														"e_sw_platform"    		=>(string)$result[$j]->e_sw_platform,
														"e_sw_platform1"    	=>(string)$result[$j]->e_sw_platform1,
														"e_sw_platform2"    	=>(string)$result[$j]->e_sw_platform2,
														"e_sw_platform3"    	=>(string)$result[$j]->e_sw_platform3,
														"e_sw_platform4"    	=>(string)$result[$j]->e_sw_platform4,
														"d_sw_releasepublish"   =>(string)$result[$j]->d_sw_releasepublish,
														"c_matauang"    		=>(string)$result[$j]->c_matauang,
														"v_sw_harga" 			=>(string)$result[$j]->v_sw_harga,
														"d_sw_lastupdate"    	=>(string)$result[$j]->d_sw_lastupdate,
														"e_sw_reqsystem"    	=>(string)$result[$j]->e_sw_reqsystem,
														"d_sw_expiregaransi"    =>(string)$result[$j]->d_sw_expiregaransi,
														"i_rekanan"    			=>(string)$result[$j]->i_rekanan,
														"d_sw_peroleh"    		=>(string)$result[$j]->d_sw_peroleh,
														"d_sw_expirelicensi"    =>(string)$result[$j]->d_sw_expirelicensi,
														"q_sw_licensi"    		=>(string)$result[$j]->q_sw_licensi,
														"c_sw_tipelicensi"    	=>(string)$result[$j]->c_sw_tipelicensi,
														"i_sw_nomorlicensi"    	=>(string)$result[$j]->i_sw_nomorlicensi,
														"c_sw_sertifikat"    	=>(string)$result[$j]->c_sw_sertifikat,
														"i_sw_sertifikat"    	=>(string)$result[$j]->i_sw_sertifikat,
														"i_hw_investasi"    	=>(string)$result[$j]->i_hw_investasi,
														"n_rekanan"        		=>$n_rekanan[0]);
						}
		 	}
		}		 
		return $hasilAkhir;
	} 
	catch (Exception $e) 
	{
	echo $e->getMessage().'<br>';
	return 'gagal <br>';
	}
}	
//=====================***************************************************************========================================
	 
	
	//Query ... 29 okt 2007
	
	
	
	
	
	public function getToolkitList($nmSoftware) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_sw,i_sw_toolkit,c_sw_jenistoolkit,n_sw_toolkit,e_sw_toolkit,q_sw_toolkit,
									i_hw_investasi
									FROM e_ast_toolkit__0_tr
									where i_sw like ?',$nmSoftware);
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_sw"             =>(string)$result[$j]->i_sw,
								   "i_sw_toolkit"             =>(string)$result[$j]->i_sw_toolkit,
								   "c_sw_jenistoolkit"    =>(string)$result[$j]->c_sw_jenistoolkit,
								   "n_sw_toolkit"     =>(string)$result[$j]->n_sw_toolkit,
	                               "e_sw_toolkit"       =>(string)$result[$j]->e_sw_toolkit,
								   "i_hw_investasi"       =>(string)$result[$j]->i_hw_investasi,
								   "q_sw_toolkit"    =>(string)$result[$j]->q_sw_toolkit);
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
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
	     $atk_mast_prm = array("thn_ang"        =>$data['thnang'],
	                           "kd_brg"   		=>$data['kdbrg'],
						       "no_aset"  		=>$data['noaset'],
							   "tgl_perlh"  	=>$data['tglPerl'],
							   "i_sw"  			=>$data['noSoftware'],
							   "n_sw_installer"  	=>$data['techInst'],
						       "i_entry"       		=>$data['nuser'],
						       "d_entry"       		=>date("Y-m-d"));
	    
		 $where[] = $data['noSoftware'];
	     $where[] = $data['thnang'];
		 $where[] = $data['kdbrg'];
		 $where[] = $data['noaset'];
		 
		 $result = $db->fetchAll('SELECT thn_ang,kd_brg,no_aset 
								 FROM e_ast_distribusi_software_tm where i_sw = ? 
									and thn_ang = ? and kd_brg = ? and no_aset = ?',$where);
		 $jmlResult = count($result);
		 		 
		 if($jmlResult > 0){
			
        }else{
			$db->insert('e_ast_distribusi_software_tm',$atk_mast_prm);
			$db->commit();
		 }
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function getDistribusiList($nmSoftware) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT a.thn_ang,a.kd_brg,a.tgl_perlh,a.i_sw,a.n_sw_installer,
									to_char(a.no_aset,'09999') as no_aset, b.i_komputer_macaddress,b.c_unit_kerja,
									b.i_ruang, i_hw_investasi
									FROM e_ast_distribusi_software_tm a, e_ast_komputer_0_tr b
									where a.thn_ang=b.d_anggaran and a.kd_brg=b.c_barang
									and a.no_aset=b.i_aset and	a.i_sw = ?",$nmSoftware);
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("thn_ang"            =>(string)$result[$j]->thn_ang,
								   "kd_brg"             =>(string)$result[$j]->kd_brg,
								   "no_aset"            =>(string)$result[$j]->no_aset,
								   "tgl_perlh"          =>(string)$result[$j]->tgl_perlh,
								   "i_sw"     			=>(string)$result[$j]->i_sw,
								   "n_sw_installer"     =>(string)$result[$j]->n_sw_installer,
								   "i_komputer_macaddress"     	=>(string)$result[$j]->i_komputer_macaddress,
								   "c_unit_kerja"             	=>(string)$result[$j]->c_unit_kerja,
								   "i_ruang"             	=>(string)$result[$j]->i_ruang,
								   "i_hw_investasi"             	=>(string)$result[$j]->i_hw_investasi);
								  
							       
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	//===========get ref=========================
	public function getRefInvKompList2($noSoftware) {
       
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT d_anggaran,a.c_barang,d_perolehan,i_komputer_macaddress,c_unit_kerja,
									b.i_ruang,to_char(a.i_aset,'09999') as no_aset
									FROM e_ast_komputer_0_tr, e_ast_dir_item_tm
									where a.d_anggaran = b.d_aset_thnanggar
									and   a.c_barang   = b.c_barang
									and   a.i_aset     = b.i_aset 
									and  not exists (select thn_ang,kd_brg,no_aset from e_ast_distribusi_software_tm
											where i_sw <>?)
									ORDER BY d_anggaran",$noSoftware);
									
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
	     return 'gagal';
	   }
	}
	
	public function getRefInvKompList3() {
       
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT d_anggaran,c_barang,d_perolehan,i_komputer_macaddress,c_unit_kerja,
									i_ruang,to_char(i_aset,'09999') as no_aset
									FROM e_ast_komputer_0_tr 
									where  not exists (select thn_ang,kd_brg,no_aset from e_ast_distribusi_software_tm)
									ORDER BY d_anggaran");
									
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
	     return 'gagal';
	   }
	}
	
	public function getRefInvKompList($pageNumber,$itemPerPage,$kd_brg) {
       $kd = '%'.strtoupper($kd_brg).'%';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
            if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_komputer_0_tr where c_barang like ?",$kd); 
			                           
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;
		        $result = $db->fetchAll("SELECT d_anggaran,c_barang,d_perolehan,i_komputer_macaddress,c_unit_kerja,
									i_ruang,to_char(i_aset,'09999') as no_aset, i_hw_investasi
									FROM e_ast_komputer_0_tr where c_barang like ? 
									ORDER BY d_anggaran
                                    limit $xLimit offset $xOffset",$kd);  
									
	     		$jmlResult = count($result);
		 
		 	if($jmlResult > 0){
		 		for ($j = 0; $j < $jmlResult; $j++) {
		 
           					$hasilAkhir[$j] = array("thn_ang"           =>(string)$result[$j]->d_anggaran,
									"kd_brg"           =>(string)$result[$j]->c_barang,
									"no_aset"           =>(string)$result[$j]->no_aset,
									"tgl_perlh"           =>(string)$result[$j]->d_perolehan,
									"i_komputer_macaddress"   =>(string)$result[$j]->i_komputer_macaddress,
									"c_unit_kerja"           =>(string)$result[$j]->c_unit_kerja,
									"i_ruang"           =>(string)$result[$j]->i_ruang,
									"i_hw_investasi"           =>(string)$result[$j]->i_hw_investasi);
								  
				 }
       		 }
             }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function updateDataDistribusiSW(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $atk_dtl_parm = array("tgl_perlh" 		=>$data['tglPerl'],
							   "n_sw_installer" 	=>$data['techInst'],
						          "i_entry"       		=>$data['nuser'],
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
	     return 'gagal';
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
	     return 'gagal';
	   }
	}
	
	//====================================add 15 april ==============asih
	public function getCariSwByNmSoftware($pageNumber,$itemPerPage,$nmSoftware) {   
	   $nsw = strtoupper($nmSoftware);
	   $sw = '%'.$nsw.'%';
	  
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $sw;
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*) 
										FROM  e_ast_software_0_tr a, e_ast_vendor_0_tr b
										where a.i_rekanan = b.i_rekanan and upper(a.n_sw) like ?" ,$where);  
        
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT a.i_sw,a.n_sw,a.n_sw_kelompok,a.i_sw_licensi,a.i_sw_versi,a.e_sw_platform,
										b.n_rekanan	,a.i_rekanan,i_rekanan_ref, a.i_hw_investasi	
										FROM e_ast_software_0_tr a, e_ast_vendor_0_tr b
										where a.i_rekanan = b.i_rekanan and upper(a.n_sw) like ?  
										ORDER BY i_sw
											limit $xLimit offset $xOffset",$where); 
			
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
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
	
	//====list kategori software pop up==============	
	public function getKategoriSoftwareList($pageNumber,$itemPerPage)
	{    
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select * FROM e_ast_kelompok_software_tr");
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;			 
				$result = $db->fetchAll("SELECT i_sw_kelompok, n_sw_kelompok 
				FROM e_ast_kelompok_software_tr order by i_sw_kelompok 
				limit $xLimit offset $xOffset"); 
				$jmlResult = count($result);				
				if($jmlResult > 0)
				{
					for ($j = 0; $j < $jmlResult; $j++) 
					{			
						$hasilAkhir[$j] = array("i_sw_kelompok"           =>(string)$result[$j]->i_sw_kelompok,
									"n_sw_kelompok"    =>(string)$result[$j]->n_sw_kelompok);
					}
				}	
			}				
			return $hasilAkhir;
		} 
		catch (Exception $e) 
		{
		echo $e->getMessage().'<br>';
		return 'gagal <br>';
		}
	}
	//=========================================
	
	public function getcarinamaSoftware($pageNumber,$itemPerPage,$kdkelsoftware)
	{    
		
		//echo " getcarinamaSoftware = ".$kdkelsoftware;
				
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select * FROM e_ast_kelompok_software_tr");
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;			 
				$result = $db->fetchAll("SELECT n_sw_kelompok 
				FROM e_ast_kelompok_software_tr 
				where i_sw_kelompok like ?
				limit $xLimit offset $xOffset",$kdkelsoftware);
				
				$jmlResult = count($result);	
				//echo " jmlResult =".$jmlResult;		 	
				if($jmlResult > 0)
				{
					for ($j = 0; $j < $jmlResult; $j++) 
					{			
						$hasilAkhir[$j] = array("n_sw_kelompok"    =>(string)$result[$j]->n_sw_kelompok);
					}
				}	
			}				
			return $hasilAkhir;
		} 
		catch (Exception $e) 
		{
		echo $e->getMessage().'<br>';
		return 'gagal <br>';
		}
	}
	
	public function getNamaBarang($pageNumber,$itemPerPage) {
	   //$namaBarang = strtoupper($namaBarang);
	   //$nbrg = '%'.$namaBarang.'%';
	   //$nbrg = $namaBarang.'%';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_software_0_tr ");
										   
		 }
		 else
		 {
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT a.i_sw,a.n_sw,a.i_sw_kelompok,n_sw_kelompok,a.i_sw_licensi,a.i_sw_versi,
										a.e_sw_platform,e_sw_platform1,e_sw_platform2,e_sw_platform3,e_sw_platform4,i_rekanan
										FROM e_ast_software_0_tr a
										order by a.n_sw 
										   limit $xLimit offset $xOffset"); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_sw"        	=>(string)$result[$j]->i_sw,
										   "n_sw"   	=>(string)$result[$j]->n_sw);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
	
	// cari nama software ================4/7/08====
	public function getSoftwareByNamaList($pageNumber,$itemPerPage,$nmBarang) {
	    //echo 'service tool';
		$nbrg = strtoupper($nmBarang);
	    $brg = '%'.$nbrg.'%';
		$registry = Zend_Registry::getInstance();
	    $db = $registry->get('db');
	    try {
			$where[] = $brg;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_software_0_tr a where upper(n_sw) like ?",$where);
			 }
			 else
			 {
			
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;	
										
				$result = $db->fetchAll("SELECT a.i_sw,a.n_sw,a.i_sw_kelompok,n_sw_kelompok,a.i_sw_licensi,a.i_sw_versi,
										a.i_hw_investasi,
										a.e_sw_platform,e_sw_platform1,e_sw_platform2,e_sw_platform3,e_sw_platform4,i_rekanan
										FROM e_ast_software_0_tr a
										where upper(n_sw) like ?
										order by a.i_sw 
										limit $xLimit offset $xOffset",$where); 
										
				$jmlResult = count($result);
		
				for ($j = 0; $j < $jmlResult; $j++) {
					//===== ambil nama rekanan =================================
						$i_rekanan	= (string)$result[$j]->i_rekanan;
						$n_rekananVendor 	= $db->fetchCol("select n_rekanan
													from e_ast_vendor_0_tr
													where i_rekanan=? ",$result[$j]->i_rekanan);
						
						$i_rekanan_ref 	= $db->fetchCol("select i_rekanan_ref
													from e_ast_vendor_0_tr
													where i_rekanan=? ",$result[$j]->i_rekanan);
						
						//ambil data dari pengadaan.....................................................................................
						$n_rekanan 	= $db->fetchCol("select n_rekanan
													from e_rekanan_0_0_tm a,e_rekanan_ijin_prsh_tm b,e_rekanan_ijin_usaha_tm c,e_rekanan_almt_prsh_tm d
													where a.i_rekanan = b.i_rekanan and a.i_rekanan = d.i_rekanan
													and b.i_prsh_ijinusaha = c.i_prsh_ijinusaha 
													and (c.n_prsh_ijinusaha  like 'TI%' or c.n_prsh_ijinusaha  like 'Komputer%')
													and a.i_rekanan=? ",$result[$j]->i_rekanan_ref);
						
						$a_prsh_jalan 	= $db->fetchCol("select a_prsh_jalan
													from e_rekanan_0_0_tm a,e_rekanan_ijin_prsh_tm b,e_rekanan_ijin_usaha_tm c,e_rekanan_almt_prsh_tm d
													where a.i_rekanan = b.i_rekanan and a.i_rekanan = d.i_rekanan
													and b.i_prsh_ijinusaha = c.i_prsh_ijinusaha 
													and (c.n_prsh_ijinusaha  like 'TI%' or c.n_prsh_ijinusaha  like 'Komputer%')
													and a.i_rekanan=? ",$result[$j]->i_rekanan_ref);
						//...................................................................................................................
						if($i_rekanan_ref =='--' || $i_rekanan_ref =='-' ){
								$hasilAkhir[$j] = array("i_sw"             			=>(string)$result[$j]->i_sw,
															"n_sw"             		=>(string)$result[$j]->n_sw,
															"n_sw_kelompok"    		=>(string)$result[$j]->n_sw_kelompok,
															"i_sw_kelompok"    		=>(string)$result[$j]->i_sw_kelompok,
															"i_sw_licensi"     		=>(string)$result[$j]->i_sw_licensi,
															"i_sw_versi"       		=>(string)$result[$j]->i_sw_versi,
															"e_sw_platform"    		=>(string)$result[$j]->e_sw_platform,
															"e_sw_platform1"    	=>(string)$result[$j]->e_sw_platform1,
															"e_sw_platform2"    	=>(string)$result[$j]->e_sw_platform2,
															"e_sw_platform3"    	=>(string)$result[$j]->e_sw_platform3,
															"e_sw_platform4"    	=>(string)$result[$j]->e_sw_platform4,
															"d_sw_releasepublish"   =>(string)$result[$j]->d_sw_releasepublish,
															"c_matauang"    		=>(string)$result[$j]->c_matauang,
															"v_sw_harga" 			=>(string)$result[$j]->v_sw_harga,
															"d_sw_lastupdate"    	=>(string)$result[$j]->d_sw_lastupdate,
															"e_sw_reqsystem"    	=>(string)$result[$j]->e_sw_reqsystem,
															"d_sw_expiregaransi"    =>(string)$result[$j]->d_sw_expiregaransi,
															"i_rekanan"    			=>(string)$result[$j]->i_rekanan,
															"d_sw_peroleh"    		=>(string)$result[$j]->d_sw_peroleh,
															"d_sw_expirelicensi"    =>(string)$result[$j]->d_sw_expirelicensi,
															"q_sw_licensi"    		=>(string)$result[$j]->q_sw_licensi,
															"c_sw_tipelicensi"    	=>(string)$result[$j]->c_sw_tipelicensi,
															"i_sw_nomorlicensi"    	=>(string)$result[$j]->i_sw_nomorlicensi,
															"c_sw_sertifikat"    	=>(string)$result[$j]->c_sw_sertifikat,
															"i_sw_sertifikat"    	=>(string)$result[$j]->i_sw_sertifikat,
															"i_hw_investasi"    	=>(string)$result[$j]->i_hw_investasi,
															"n_rekanan"        		=>$n_rekananVendor[0]);
						}else{
								$hasilAkhir[$j] = array("i_sw"             			=>(string)$result[$j]->i_sw,
															"n_sw"             		=>(string)$result[$j]->n_sw,
															"n_sw_kelompok"    		=>(string)$result[$j]->n_sw_kelompok,
															"i_sw_kelompok"    		=>(string)$result[$j]->i_sw_kelompok,
															"i_sw_licensi"     		=>(string)$result[$j]->i_sw_licensi,
															"i_sw_versi"       		=>(string)$result[$j]->i_sw_versi,
															"e_sw_platform"    		=>(string)$result[$j]->e_sw_platform,
															"e_sw_platform1"    	=>(string)$result[$j]->e_sw_platform1,
															"e_sw_platform2"    	=>(string)$result[$j]->e_sw_platform2,
															"e_sw_platform3"    	=>(string)$result[$j]->e_sw_platform3,
															"e_sw_platform4"    	=>(string)$result[$j]->e_sw_platform4,
															"d_sw_releasepublish"   =>(string)$result[$j]->d_sw_releasepublish,
															"c_matauang"    		=>(string)$result[$j]->c_matauang,
															"v_sw_harga" 			=>(string)$result[$j]->v_sw_harga,
															"d_sw_lastupdate"    	=>(string)$result[$j]->d_sw_lastupdate,
															"e_sw_reqsystem"    	=>(string)$result[$j]->e_sw_reqsystem,
															"d_sw_expiregaransi"    =>(string)$result[$j]->d_sw_expiregaransi,
															"i_rekanan"    			=>(string)$result[$j]->i_rekanan,
															"d_sw_peroleh"    		=>(string)$result[$j]->d_sw_peroleh,
															"d_sw_expirelicensi"    =>(string)$result[$j]->d_sw_expirelicensi,
															"q_sw_licensi"    		=>(string)$result[$j]->q_sw_licensi,
															"c_sw_tipelicensi"    	=>(string)$result[$j]->c_sw_tipelicensi,
															"i_sw_nomorlicensi"    	=>(string)$result[$j]->i_sw_nomorlicensi,
															"c_sw_sertifikat"    	=>(string)$result[$j]->c_sw_sertifikat,
															"i_sw_sertifikat"    	=>(string)$result[$j]->i_sw_sertifikat,
															"i_hw_investasi"    	=>(string)$result[$j]->i_hw_investasi,
															"n_rekanan"        		=>$i_rekanan_ref[0]);
						}
			}
        }			
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	//cari by kelompok sw ===
	
	public function getSoftwareByKelSw($pageNumber,$itemPerPage,$kelsw) {
	    //echo 'service tool';
		$kels = strtoupper($kelsw);
	    $ksw = '%'.$kels.'%';
		
		$registry = Zend_Registry::getInstance();
	    $db = $registry->get('db');
	    try {
			$where[] = $ksw;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
		 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_software_0_tr a 
											where upper(n_sw_kelompok) like ?
											and c_status_lengkap is null",$where);
			 }
			 else
			 {
			
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;	
										
				$result = $db->fetchAll("SELECT a.i_sw,a.n_sw,a.i_sw_kelompok,n_sw_kelompok,a.i_sw_licensi,a.i_sw_versi,
										a.e_sw_platform,e_sw_platform1,e_sw_platform2,e_sw_platform3,e_sw_platform4,i_rekanan,
										i_hw_investasi
										FROM e_ast_software_0_tr a
										where upper(n_sw_kelompok) like ?
										and c_status_lengkap is null
										order by a.i_sw 
										limit $xLimit offset $xOffset",$where); 
										
				$jmlResult = count($result);
		
				for ($j = 0; $j < $jmlResult; $j++) {
					//===== ambil nama rekanan =================================
						$i_rekanan	= (string)$result[$j]->i_rekanan;
						$n_rekananVendor 	= $db->fetchCol("select n_rekanan
													from e_ast_vendor_0_tr
													where i_rekanan=? ",$result[$j]->i_rekanan);
						
						$i_rekanan_ref 	= $db->fetchCol("select i_rekanan_ref
													from e_ast_vendor_0_tr
													where i_rekanan=? ",$result[$j]->i_rekanan);
						
						//ambil data dari pengadaan.....................................................................................
						$n_rekanan 	= $db->fetchCol("select n_rekanan
													from e_rekanan_0_0_tm a,e_rekanan_ijin_prsh_tm b,e_rekanan_ijin_usaha_tm c,e_rekanan_almt_prsh_tm d
													where a.i_rekanan = b.i_rekanan and a.i_rekanan = d.i_rekanan
													and b.i_prsh_ijinusaha = c.i_prsh_ijinusaha 
													and (c.n_prsh_ijinusaha  like 'TI%' or c.n_prsh_ijinusaha  like 'Komputer%')
													and a.i_rekanan=? ",$result[$j]->i_rekanan_ref);
						
						$a_prsh_jalan 	= $db->fetchCol("select a_prsh_jalan
													from e_rekanan_0_0_tm a,e_rekanan_ijin_prsh_tm b,e_rekanan_ijin_usaha_tm c,e_rekanan_almt_prsh_tm d
													where a.i_rekanan = b.i_rekanan and a.i_rekanan = d.i_rekanan
													and b.i_prsh_ijinusaha = c.i_prsh_ijinusaha 
													and (c.n_prsh_ijinusaha  like 'TI%' or c.n_prsh_ijinusaha  like 'Komputer%')
													and a.i_rekanan=? ",$result[$j]->i_rekanan_ref);
						//...................................................................................................................
						if($i_rekanan_ref =='--' || $i_rekanan_ref =='-' ){
								$hasilAkhir[$j] = array("i_sw"             			=>(string)$result[$j]->i_sw,
															"n_sw"             		=>(string)$result[$j]->n_sw,
															"n_sw_kelompok"    		=>(string)$result[$j]->n_sw_kelompok,
															"i_sw_kelompok"    		=>(string)$result[$j]->i_sw_kelompok,
															"i_sw_licensi"     		=>(string)$result[$j]->i_sw_licensi,
															"i_sw_versi"       		=>(string)$result[$j]->i_sw_versi,
															"e_sw_platform"    		=>(string)$result[$j]->e_sw_platform,
															"e_sw_platform1"    	=>(string)$result[$j]->e_sw_platform1,
															"e_sw_platform2"    	=>(string)$result[$j]->e_sw_platform2,
															"e_sw_platform3"    	=>(string)$result[$j]->e_sw_platform3,
															"e_sw_platform4"    	=>(string)$result[$j]->e_sw_platform4,
															"d_sw_releasepublish"   =>(string)$result[$j]->d_sw_releasepublish,
															"c_matauang"    		=>(string)$result[$j]->c_matauang,
															"v_sw_harga" 			=>(string)$result[$j]->v_sw_harga,
															"d_sw_lastupdate"    	=>(string)$result[$j]->d_sw_lastupdate,
															"e_sw_reqsystem"    	=>(string)$result[$j]->e_sw_reqsystem,
															"d_sw_expiregaransi"    =>(string)$result[$j]->d_sw_expiregaransi,
															"i_rekanan"    			=>(string)$result[$j]->i_rekanan,
															"d_sw_peroleh"    		=>(string)$result[$j]->d_sw_peroleh,
															"d_sw_expirelicensi"    =>(string)$result[$j]->d_sw_expirelicensi,
															"q_sw_licensi"    		=>(string)$result[$j]->q_sw_licensi,
															"c_sw_tipelicensi"    	=>(string)$result[$j]->c_sw_tipelicensi,
															"i_sw_nomorlicensi"    	=>(string)$result[$j]->i_sw_nomorlicensi,
															"c_sw_sertifikat"    	=>(string)$result[$j]->c_sw_sertifikat,
															"i_sw_sertifikat"    	=>(string)$result[$j]->i_sw_sertifikat,
															"i_hw_investasi"    	=>(string)$result[$j]->i_hw_investasi,
															"n_rekanan"        		=>$n_rekananVendor[0]);
						}else{
								$hasilAkhir[$j] = array("i_sw"             			=>(string)$result[$j]->i_sw,
															"n_sw"             		=>(string)$result[$j]->n_sw,
															"n_sw_kelompok"    		=>(string)$result[$j]->n_sw_kelompok,
															"i_sw_kelompok"    		=>(string)$result[$j]->i_sw_kelompok,
															"i_sw_licensi"     		=>(string)$result[$j]->i_sw_licensi,
															"i_sw_versi"       		=>(string)$result[$j]->i_sw_versi,
															"e_sw_platform"    		=>(string)$result[$j]->e_sw_platform,
															"e_sw_platform1"    	=>(string)$result[$j]->e_sw_platform1,
															"e_sw_platform2"    	=>(string)$result[$j]->e_sw_platform2,
															"e_sw_platform3"    	=>(string)$result[$j]->e_sw_platform3,
															"e_sw_platform4"    	=>(string)$result[$j]->e_sw_platform4,
															"d_sw_releasepublish"   =>(string)$result[$j]->d_sw_releasepublish,
															"c_matauang"    		=>(string)$result[$j]->c_matauang,
															"v_sw_harga" 			=>(string)$result[$j]->v_sw_harga,
															"d_sw_lastupdate"    	=>(string)$result[$j]->d_sw_lastupdate,
															"e_sw_reqsystem"    	=>(string)$result[$j]->e_sw_reqsystem,
															"d_sw_expiregaransi"    =>(string)$result[$j]->d_sw_expiregaransi,
															"i_rekanan"    			=>(string)$result[$j]->i_rekanan,
															"d_sw_peroleh"    		=>(string)$result[$j]->d_sw_peroleh,
															"d_sw_expirelicensi"    =>(string)$result[$j]->d_sw_expirelicensi,
															"q_sw_licensi"    		=>(string)$result[$j]->q_sw_licensi,
															"c_sw_tipelicensi"    	=>(string)$result[$j]->c_sw_tipelicensi,
															"i_sw_nomorlicensi"    	=>(string)$result[$j]->i_sw_nomorlicensi,
															"c_sw_sertifikat"    	=>(string)$result[$j]->c_sw_sertifikat,
															"i_sw_sertifikat"    	=>(string)$result[$j]->i_sw_sertifikat,
															"i_hw_investasi"    	=>(string)$result[$j]->i_hw_investasi,
															"n_rekanan"        		=>$i_rekanan_ref[0]);
						}
			}
        }			
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	
}	
?>