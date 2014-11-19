<?php
class ast_lokasi_Service {
   
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
	
	public function getLokasiList_Bc($no_lokasi) {
	    
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_lokasi, n_lokasi, a_lokasi,q_lokasi_lantai, i_peg_nip
										FROM e_ast_lokasi_0_tr 
										where i_lokasi=?
										order by i_lokasi ', $no_lokasi); 
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
		   //ambil data dari sdm.....................................................................................
						$n_peg 		= $db->fetchCol("select n_peg
														from  e_sdm_pegawai_0_tm A 
														where  
														not EXISTS(select * from  e_sdm_jabatan_0_tm B
														          where a.i_peg_nip = b.i_peg_nip)
														and a.i_peg_nip  = ? ",	$result[$j]->i_peg_nip)	;
						
						$n_peg2 	= $db->fetchCol("select n_peg 
													from e_sdm_jabatan_0_tm A,  e_sdm_pegawai_0_tm B
													where a.i_peg_nip = b.i_peg_nip
													and b.c_unit_kerja = a.c_jabatan
													and b.i_peg_nip = ?",$result[$j]->i_peg_nip);												
													
						$n_jabatan 	= $db->fetchCol("select A.n_jabatan
													from e_sdm_jabatan_0_tm A,  e_sdm_pegawai_0_tm B
													where a.i_peg_nip = b.i_peg_nip
													and b.c_unit_kerja = a.c_jabatan
													and b.i_peg_nip = ?",$result[$j]->i_peg_nip);
						
						$i_orgb 	= $db->fetchCol("select b.i_orgb  
													from e_sdm_jabatan_0_tm A,  e_sdm_pegawai_0_tm B
													where a.i_peg_nip = b.i_peg_nip
													and b.c_unit_kerja = a.c_jabatan
													and b.i_peg_nip = ?",$result[$j]->i_peg_nip);
						
						$hasilAkhir[$j] = array("i_lokasi"           		=>(string)$result[$j]->i_lokasi,
												   "n_lokasi"           	=>(string)$result[$j]->n_lokasi,
												   "a_lokasi"           	=>(string)$result[$j]->a_lokasi,
												   "q_lokasi_lantai"    	=>(string)$result[$j]->q_lokasi_lantai,
												   "i_peg_nip"         		=>(string)$result[$j]->i_peg_nip,
												   "n_peg"       			=>$n_peg[0],
												   "n_peg2"       			=>$n_peg2[0],
												   "n_jabatan" 		        =>$n_jabatan[0],
												   "i_orgb"          		=>$i_orgb[0]);
			
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getRekananRef($noref) {
	   $status='A';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select a.i_rekanan,n_rekanan,c.n_prsh_ijinusaha,a_prsh_jalan,a_prsh_kota,i_prsh_telpon,i_prsh_fax 
										from e_rekanan_0_0_tm a,e_rekanan_ijin_prsh_tm b,e_rekanan_ijin_usaha_tm c,e_rekanan_almt_prsh_tm d
										where a.i_rekanan = b.i_rekanan and a.i_rekanan = d.i_rekanan
										and b.i_prsh_ijinusaha = c.i_prsh_ijinusaha 
										and (c.n_prsh_ijinusaha  like 'TI%' or c.n_prsh_ijinusaha  like 'Komputer%')
										and a.i_rekanan=?", $noref); 
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
			for ($j = 0; $j < $jmlResult; $j++) {
		 
				$hasilAkhir[$j] = array("i_rekanan" 			=>(string)$result[$j]->i_rekanan,
											"n_rekanan"  			=>(string)$result[$j]->n_rekanan,
											"n_prsh_ijinusaha" 		=>(string)$result[$j]->n_prsh_ijinusaha,
											"a_prsh_jalan" 			=>(string)$result[$j]->a_prsh_jalan,
											"a_prsh_kota" 			=>(string)$result[$j]->a_prsh_kota,
											"i_prsh_telpon" 		=>(string)$result[$j]->i_prsh_telpon,
											"i_prsh_fax" 			=>(string)$result[$j]->i_prsh_fax);
					
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getSuppList($pageNumber,$itemPerPage) 
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
				$hasilAkhir = $db->fetchOne("select count(*) from e_rekanan_0_0_tm a,e_rekanan_ijin_prsh_tm b,e_rekanan_ijin_usaha_tm c,e_rekanan_almt_prsh_tm d
										where a.i_rekanan = b.i_rekanan and a.i_rekanan = d.i_rekanan
										and b.i_prsh_ijinusaha = c.i_prsh_ijinusaha 
										and (c.n_prsh_ijinusaha  like 'TI%' or c.n_prsh_ijinusaha  like 'Komputer%')");
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				
				$result = $db->fetchAll("select '--' as i_rekanan,null as n_rekanan,null as n_prsh_ijinusaha,
										null as a_prsh_jalan,null as a_prsh_kota,null as i_prsh_telpon,null as i_prsh_fax 
										UNION
										select a.i_rekanan,n_rekanan,c.n_prsh_ijinusaha,a_prsh_jalan,a_prsh_kota,i_prsh_telpon,i_prsh_fax 
										from e_rekanan_0_0_tm a,e_rekanan_ijin_prsh_tm b,e_rekanan_ijin_usaha_tm c,e_rekanan_almt_prsh_tm d
										where a.i_rekanan = b.i_rekanan and a.i_rekanan = d.i_rekanan
										and b.i_prsh_ijinusaha = c.i_prsh_ijinusaha 
										and (c.n_prsh_ijinusaha  like 'TI%' or c.n_prsh_ijinusaha  like 'Komputer%')
										ORDER BY i_rekanan
										limit $xLimit offset $xOffset");
								 
				
				$jmlResult = count($result);
				//echo "jumlah di server =".$jmlResult;
				
				for ($j = 0; $j < $jmlResult; $j++) 
				{
					$hasilAkhir[$j] = array("i_rekanan" 			=>(string)$result[$j]->i_rekanan,
											"n_rekanan"  			=>(string)$result[$j]->n_rekanan,
											"n_prsh_ijinusaha" 		=>(string)$result[$j]->n_prsh_ijinusaha,
											"a_prsh_jalan" 			=>(string)$result[$j]->a_prsh_jalan,
											"a_prsh_kota" 			=>(string)$result[$j]->a_prsh_kota,
											"i_prsh_telpon" 		=>(string)$result[$j]->i_prsh_telpon,
											"i_prsh_fax" 			=>(string)$result[$j]->i_prsh_fax);
					
					
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
	public function getLokasiList($pageNumber,$itemPerPage) {
       
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
          
	   try {
         $db->setFetchMode(Zend_Db::FETCH_OBJ);
         if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM   e_ast_lokasi_0_tr ");
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;			 
				$result = $db->fetchAll("SELECT i_lokasi, n_lokasi, a_lokasi,q_lokasi_lantai, i_peg_nip
										FROM e_ast_lokasi_0_tr order by i_lokasi
										limit $xLimit offset $xOffset"); 
		         $jmlResult = count($result);
				
				
				
				 if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {
						$i_rekanan_ref	= (string)$result[$j]->i_rekanan_ref;	
						
						//ambil data dari sdm.....................................................................................
						$n_peg 		= $db->fetchCol("select n_peg
														from  e_sdm_pegawai_0_tm A 
														where  
														not EXISTS(select * from  e_sdm_jabatan_0_tm B
														          where a.i_peg_nip = b.i_peg_nip)
														and a.i_peg_nip  = ? ",	$result[$j]->i_peg_nip)	;
						
						$n_peg2 	= $db->fetchCol("select n_peg 
													from e_sdm_jabatan_0_tm A,  e_sdm_pegawai_0_tm B
													where a.i_peg_nip = b.i_peg_nip
													and b.c_unit_kerja = a.c_jabatan
													and b.i_peg_nip = ?",$result[$j]->i_peg_nip);
						
						$n_jabatan 	= $db->fetchCol("select A.n_jabatan
													from e_sdm_jabatan_0_tm A,  e_sdm_pegawai_0_tm B
													where a.i_peg_nip = b.i_peg_nip
													and b.c_unit_kerja = a.c_jabatan
													and b.i_peg_nip = ?",$result[$j]->i_peg_nip);
						
						$i_orgb 	= $db->fetchCol("select b.i_orgb  
													from e_sdm_jabatan_0_tm A,  e_sdm_pegawai_0_tm B
													where a.i_peg_nip = b.i_peg_nip
													and b.c_unit_kerja = a.c_jabatan
													and b.i_peg_nip = ?",$result[$j]->i_peg_nip);
						
						$hasilAkhir[$j] = array("i_lokasi"           		=>(string)$result[$j]->i_lokasi,
												   "n_lokasi"           	=>(string)$result[$j]->n_lokasi,
												   "a_lokasi"           	=>(string)$result[$j]->a_lokasi,
												   "q_lokasi_lantai"    	=>(string)$result[$j]->q_lokasi_lantai,
												   "i_peg_nip"         		=>(string)$result[$j]->i_peg_nip,
												   "n_peg"       			=>$n_peg[0],
												   "n_peg2"       			=>$n_peg2[0],
												   "n_jabatan" 		        =>$n_jabatan[0],
												   "i_orgb"          		=>$i_orgb[0]);
						
					}
				}	
            }				
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function getDataVendor($prmVendorEdit) {
       $status='A';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_rekanan, n_rekanan, a_rekanan,n_rekanan_agendistr,
		 i_rekanan_telp, n_rekanan_kontak,
		 n_vendor_ctgr
		 FROM e_ast_vendor_0_tr
		 where i_rekanan=?',$prmVendorEdit); 
         $jmlResult = count($result);
		 //echo 'jmldata :'.$jmlResult;
		 //echo 'no rekanan :'.$prmVendorEdit;
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		    
			//echo 'masuk loop :'  ;
           $hasilAkhir[$j] = array("i_rekanan"           	=>(string)$result[$j]->i_rekanan,
								   "n_rekanan"           	=>(string)$result[$j]->n_rekanan,
								   "a_rekanan"           	=>(string)$result[$j]->a_rekanan,
								   "n_rekanan_agendistr"      =>(string)$result[$j]->n_rekanan_agendistr,
								   "i_rekanan_telp"           =>(string)$result[$j]->i_rekanan_telp,
								   "n_rekanan_kontak"         =>(string)$result[$j]->n_rekanan_kontak,
								   "n_vendor_ctgr"           =>(string)$result[$j]->n_vendor_ctgr);
			//echo 'hasil loop '. $hasilAkhir[0]['d_rekanan_aspilukiexpire'];
			
			
            
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function insertDataLokasi(array $data, $userid) {
	    $registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
	
		try {
	     $db->beginTransaction();
	     $lokasi_prm = array("i_lokasi"        			=>$data['noLokasi'],
								"n_lokasi"        		=>$data['NamaLokasi'],
								"a_lokasi"        		=>$data['AlamatLokasi'],
								"q_lokasi_lantai"  		=>$data['lantai'],
								"i_peg_nip"  			=>$data['nipPenerima'],
							    "i_entry"       		=>$userid,
						        "d_entry"       		=>date("Y-m-d"));
	   
	     $db->insert('e_ast_lokasi_0_tr',$lokasi_prm);
		 $db->commit();
		 $_hasil = array("rcnumber"=>"1",
						 "rcDesc"=>"Proses Sukses");
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
		
	public function updateDataLokasi(array $data, $userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $lokasi_prm = array("n_lokasi"              	=>$data['NamaLokasi'],
						       "a_lokasi"  				=>$data['AlamatLokasi'],
							   "q_lokasi_lantai"  		=>$data['lantai'],
							   "i_peg_nip"  			=>$data['nipPenerima'],
							   "i_entry"       		    =>$userid,
						       "d_entry"       		    =>date("Y-m-d"));
		
		 $where[] = "i_lokasi = '".$data['noLokasi']."'";
	     $db->update('e_ast_lokasi_0_tr',$lokasi_prm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function deleteLokasi (array $data) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$where[] = "i_lokasi = '".$data['noLokasi']."'";
			$db->delete('e_ast_lokasi_0_tr', $where);
			$db->commit();
			return 'sukses <br>';
		  } catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		  }
		}


	//====================================add 05 - 05 -08 ==============================================
	
	public function queryNourutmax($modl) {
	   // echo 'gen brg'.$modl;
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
	
}	
?>