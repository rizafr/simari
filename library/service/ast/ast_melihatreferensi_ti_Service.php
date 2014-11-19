<?php
class ast_melihatreferensi_ti_Service {
   
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
	
	public function getVendorList_Bc($no_rekanan) {
	    
       $status='A';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_rekanan, n_rekanan, a_rekanan,n_rekanan_agendistr, i_rekanan_telp, n_rekanan_kontak,
		 n_vendor_ctgr FROM e_ast_vendor_0_tr where i_rekanan=?', $no_rekanan); 
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_rekanan"           =>(string)$result[$j]->i_rekanan,
								   "n_rekanan"           =>(string)$result[$j]->n_rekanan,
								   "a_rekanan"           =>(string)$result[$j]->a_rekanan,
								   "n_rekanan_agendistr"           =>(string)$result[$j]->n_rekanan_agendistr,
								   "i_rekanan_telp"           =>(string)$result[$j]->i_rekanan_telp,
								   "n_rekanan_kontak"           =>(string)$result[$j]->n_rekanan_kontak,
								   "n_vendor_ctgr"           =>(string)$result[$j]->n_vendor_ctgr);
								  
								  
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
										and (c.n_prsh_ijinusaha  like 'TI%' or c.n_prsh_ijinusaha  like 'KOMPUTER%')");
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				
				$result = $db->fetchAll("select a.i_rekanan,n_rekanan,c.n_prsh_ijinusaha,a_prsh_jalan,a_prsh_kota,i_prsh_telpon,i_prsh_fax 
										from e_rekanan_0_0_tm a,e_rekanan_ijin_prsh_tm b,e_rekanan_ijin_usaha_tm c,e_rekanan_almt_prsh_tm d
										where a.i_rekanan = b.i_rekanan and a.i_rekanan = d.i_rekanan
										and b.i_prsh_ijinusaha = c.i_prsh_ijinusaha 
										and (c.n_prsh_ijinusaha  like 'TI%' or c.n_prsh_ijinusaha  like 'Komputer%')
										limit $xLimit offset $xOffset");
								 
				
				$jmlResult = count($result);
				//echo "jumlah di server =".$jmlResult;
				
				for ($j = 0; $j < $jmlResult; $j++) 
				{
					$hasilAkhir[$j] = array("i_rekanan" 	=>(string)$result[$j]->i_rekanan,
					"n_rekanan"  				=>(string)$result[$j]->n_rekanan,
					"n_prsh_ijinusaha" 				=>(string)$result[$j]->n_prsh_ijinusaha,
					"a_prsh_jalan" 			=>(string)$result[$j]->a_prsh_jalan,
					"a_prsh_kota" 			=>(string)$result[$j]->a_prsh_kota,
					"i_prsh_telpon" 			=>(string)$result[$j]->i_prsh_telpon,
					"i_prsh_fax" 		=>(string)$result[$j]->i_prsh_fax);
					
					
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
				$result = $db->fetchAll("SELECT i_rekanan, n_rekanan , a_rekanan,  n_rekanan_agendistr,
									i_rekanan_telp, n_rekanan_kontak,n_vendor_ctgr
									from e_ast_vendor_0_tr
									where i_rekanan_ref ='--'																		
									UNION ALL
									select c.i_rekanan, a.n_prsh as n_rekanan, b.a_prsh_jalan as a_rekanan, 
									n_rekanan_agendistr,i_rekanan_telp, n_rekanan_kontak,n_vendor_ctgr
									from e_rekanan_prsh_0_tm A, e_rekanan_almt_prsh_tm B,
									e_ast_vendor_0_tr C
									where b.i_rekanan = a.i_rekanan
									and c.i_rekanan_ref = a.i_rekanan 
									and b.c_prsh_levelktr = a.c_prsh_levelktr									
									order by i_rekanan
									limit $xLimit offset $xOffset"); 
		         $jmlResult = count($result);
		
				 if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {
		 
						$hasilAkhir[$j] = array("i_rekanan"           =>(string)$result[$j]->i_rekanan,
								   "n_rekanan"           =>(string)$result[$j]->n_rekanan,
								   "a_rekanan"           =>(string)$result[$j]->a_rekanan,
								   "n_rekanan_agendistr"           =>(string)$result[$j]->n_rekanan_agendistr,
								   "i_rekanan_telp"           =>(string)$result[$j]->i_rekanan_telp,
								   "n_rekanan_kontak"           =>(string)$result[$j]->n_rekanan_kontak,
								   "n_vendor_ctgr"           =>(string)$result[$j]->n_vendor_ctgr);
								  
						}
				}	
            }				
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function getVendorList_Old($unitkr) {
       $status='A';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_rekanan, n_rekanan, a_rekanan,n_rekanan_agendistr, i_rekanan_telp, n_rekanan_kontak,
		 n_vendor_ctgr FROM e_ast_vendor_0_tr order by i_rekanan',$where); 
         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_rekanan"           =>(string)$result[$j]->i_rekanan,
								   "n_rekanan"           =>(string)$result[$j]->n_rekanan,
								   "a_rekanan"           =>(string)$result[$j]->a_rekanan,
								   "n_rekanan_agendistr"           =>(string)$result[$j]->n_rekanan_agendistr,
								   "i_rekanan_telp"           =>(string)$result[$j]->i_rekanan_telp,
								   "n_rekanan_kontak"           =>(string)$result[$j]->n_rekanan_kontak,
								   "n_vendor_ctgr"           =>(string)$result[$j]->n_vendor_ctgr);
								  
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

	public function insertDataVendor(array $data, $userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	
	   //echo $data['NoRekanan'].'<br>';
	  // echo $data->getNamaRekanan().'<br>';
	   try {
	     $db->beginTransaction();
	     $vendor_prm = array("i_rekanan"        		=>$data['NoRekanan'],
	                           "n_rekanan"    	    	=>$data['NamaRekanan'],
						       "a_rekanan"              =>$data['AlamatRekanan'],
						       "n_rekanan_agendistr"  	=>$data['NamaDistributor'],
							   "i_rekanan_telp"  		=>$data['NoTlp'],
							   "n_rekanan_kontak"  		=>$data['NamaKontak'],
							   "n_vendor_ctgr"          =>$data['kelompok'],
						       "i_entry"       		        =>$userid,
						       "d_entry"       		        =>date("Y-m-d"));
	   
	     $db->insert('e_ast_vendor_0_tr',$vendor_prm);
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
		
	public function updateDataVendor(array $data, $userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $vendor_prm = array("n_rekanan"    	    	=>$data['NamaRekanan'],
						       "a_rekanan"              =>$data['AlamatRekanan'],
						       "n_rekanan_agendistr"  	=>$data['NamaDistributor'],
							   "i_rekanan_telp"  		=>$data['NoTlp'],
							   "n_rekanan_kontak"  		=>$data['NamaKontak'],
							   "n_vendor_ctgr"          =>$data['kelompok'],
						       "i_entry"       		        =>$userid,
						       "d_entry"       		        =>date("Y-m-d"));
		 $where[] = "i_rekanan = '".$data['NoRekanan']."'";
	     $db->update('e_ast_vendor_0_tr',$vendor_prm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function deleteVendorMaster (array $data) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$where[] = "i_rekanan = '".$data['NoRekanan']."'";
			$db->delete('e_ast_vendor_0_tr', $where);
			$db->commit();
			return 'sukses <br>';
		  } catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		  }
		}

	//melihat kategori ===========
	
	public function getKategoriProblemSi($pageNumber,$itemPerPage) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_kategori_problemti_tm");
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				$result = $db->fetchAll("SELECT  *  FROM e_ast_kategori_problemti_tm order by c_problem_ctgr limit $xLimit offset $xOffset");
				$jmlResult = count($result);
		 
				if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {
		 
						$hasilAkhir[$j] = array("c_problem_ctgr"           =>(string)$result[$j]->c_problem_ctgr,
									    "n_problem_ctgr"    =>(string)$result[$j]->n_problem_ctgr,
									    "q_nomor_max"           =>(string)$result[$j]->q_nomor_max );
					}
				}
			}		 
			return $hasilAkhir;
		} catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
		}
	}
	
	
//==================melihat kategori software=============================================
public function getKategorisoftwareList($pageNumber,$itemPerPage) 
{
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	
	try 
	{
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		if(($pageNumber==0) && ($itemPerPage==0))
		{
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_kelompok_software_tr");
		}
		else
		{	
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;			 
			$result = $db->fetchAll("SELECT * FROM e_ast_kelompok_software_tr order by i_sw_kelompok limit $xLimit offset $xOffset"); 
			$jmlResult = count($result);
			//echo "jumlah getKategorisoftwareList di services =".$jmlResult;
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
	
//==================melihat lokasi=============================================
public function getLokasiList($pageNumber,$itemPerPage) 
{
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	
	try 
	{
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
		//echo "jumlah getLokasiList diservices =".$jmlResult;
		if($jmlResult > 0)
		{
			for ($j = 0; $j < $jmlResult; $j++) 
			{
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
	} 
	catch (Exception $e) 
	{
	echo $e->getMessage().'<br>';
	return 'gagal <br>';
	}
}

//cari nama rekanan ==================================================================
	
public function getCariVendorList($pageNumber,$itemPerPage,$nmRekanan) 
{	
	//echo ' nmRekanan di serv = '.$nmRekanan;
	$barang = strtoupper($nmRekanan);
	$kondisi[] = '%'.$barang.'%';
	$nsw[] = '%'.$barang.'%';
	$nsw[] = '%'.$barang.'%';
	
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	try 
	{
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		if(($pageNumber==0) && ($itemPerPage==0))
		{
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_vendor_0_tr where n_rekanan like ? ",$kondisi);
		}
		else
		{
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;	  		 
			$result = $db->fetchAll("SELECT i_rekanan, n_rekanan , a_rekanan,  n_rekanan_agendistr,
									i_rekanan_telp, n_rekanan_kontak,n_vendor_ctgr
									from e_ast_vendor_0_tr
									where i_rekanan_ref ='--'
									and n_rekanan like ? 
									UNION ALL
									select c.i_rekanan, a.n_prsh as n_rekanan, b.a_prsh_jalan as a_rekanan, 
									n_rekanan_agendistr,i_rekanan_telp, n_rekanan_kontak,n_vendor_ctgr
									from e_rekanan_prsh_0_tm A, e_rekanan_almt_prsh_tm B,
									e_ast_vendor_0_tr C
									where b.i_rekanan = a.i_rekanan
									and c.i_rekanan_ref = a.i_rekanan 
									and b.c_prsh_levelktr = a.c_prsh_levelktr
									and a.n_prsh like ? 
									order by n_rekanan
									limit $xLimit offset $xOffset",$nsw);
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) 
			
			if($jmlResult > 0)
			{
				for ($j = 0; $j < $jmlResult; $j++) 
				{
					$hasilAkhir[$j] = array("i_rekanan"   =>(string)$result[$j]->i_rekanan,
					"n_rekanan"           	=>(string)$result[$j]->n_rekanan,
					"a_rekanan"           	=>(string)$result[$j]->a_rekanan,
					"n_rekanan_agendistr"   =>(string)$result[$j]->n_rekanan_agendistr,
					"i_rekanan_telp"        =>(string)$result[$j]->i_rekanan_telp,
					"n_rekanan_kontak"      =>(string)$result[$j]->n_rekanan_kontak,
					"n_vendor_ctgr"         =>(string)$result[$j]->n_vendor_ctgr);
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

//cari nama kategori ==================================================================
	
public function getCariKategoriList($pageNumber,$itemPerPage,$nmKategori) 
{	
	//echo ' nmKategori di serv = '.$nmKategori;
	$barang = strtoupper($nmKategori);
	$nsw[] = '%'.$barang.'%';
	
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	try 
	{
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		if(($pageNumber==0) && ($itemPerPage==0))
		{
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_kelompok_software_tr");
		}
		else
		{
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;	  		 
			$result = $db->fetchAll("SELECT i_sw_kelompok,n_sw_kelompok
			FROM e_ast_kelompok_software_tr 
			where n_sw_kelompok like ? Order by n_sw_kelompok			
			limit $xLimit offset $xOffset",$nsw);
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) 
			
			if($jmlResult > 0)
			{
				for ($j = 0; $j < $jmlResult; $j++) 
				{
					$hasilAkhir[$j] = array("i_sw_kelompok"   =>(string)$result[$j]->i_sw_kelompok,
					"n_sw_kelompok"           	=>(string)$result[$j]->n_sw_kelompok);
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

//cari nama kategori problem==================================================================
	
public function getCariKategoriProblemList($pageNumber,$itemPerPage,$nmKategori) 
{	
	//echo ' nmKategori di serv = '.$nmKategori;
	$barang = strtoupper($nmKategori);
	$nsw[] = '%'.$barang.'%';
	
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	try 
	{
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		if(($pageNumber==0) && ($itemPerPage==0))
		{
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_kategori_problemti_tm");
		}
		else
		{
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;	  					 
			$result = $db->fetchAll("SELECT c_problem_ctgr, n_problem_ctgr, q_nomor_max
			FROM e_ast_kategori_problemti_tm 
			where n_problem_ctgr like ? Order by c_problem_ctgr			
			limit $xLimit offset $xOffset",$nsw);
			
			$jmlResult = count($result);
			//echo "jumlah di services = ".$jmlResult;
			for ($j = 0; $j < $jmlResult; $j++) 
			
			if($jmlResult > 0)
			{
				for ($j = 0; $j < $jmlResult; $j++) 
				{
					$hasilAkhir[$j] = array("c_problem_ctgr"        =>(string)$result[$j]->c_problem_ctgr,
								"n_problem_ctgr"    	=>(string)$result[$j]->n_problem_ctgr,
								"q_nomor_max"           =>(string)$result[$j]->q_nomor_max );
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

//cari nama lokasi==================================================================
	
public function getCariLokasiList($pageNumber,$itemPerPage,$nmLokasi) 
{	
	//echo ' nmLokasi di serv = '.$nmLokasi;
	$barang = strtoupper($nmLokasi);
	$nsw[] = '%'.$barang.'%';
	
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	try 
	{
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		if(($pageNumber==0) && ($itemPerPage==0))
		{
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_lokasi_0_tr");
		}
		else
		{
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;	  					 
			$result = $db->fetchAll("SELECT i_lokasi, n_lokasi, a_lokasi,q_lokasi_lantai, i_peg_nip
			FROM e_ast_lokasi_0_tr 
			where n_lokasi like ? Order by i_lokasi			
			limit $xLimit offset $xOffset",$nsw);
			
			$jmlResult = count($result);
			//echo "jumlah di services = ".$jmlResult;
			for ($j = 0; $j < $jmlResult; $j++) 
			
			if($jmlResult > 0)
			{
				for ($j = 0; $j < $jmlResult; $j++) 
				{
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
					
					$hasilAkhir[$j] = array("i_lokasi"   =>(string)$result[$j]->i_lokasi,
					   "n_lokasi"           	=>(string)$result[$j]->n_lokasi,
					   "a_lokasi"           	=>(string)$result[$j]->a_lokasi,
					   "q_lokasi_lantai"    	=>(string)$result[$j]->q_lokasi_lantai,
					   "i_peg_nip"         		=>(string)$result[$j]->i_peg_nip,
					   "n_peg"       		=>$n_peg[0],
					   "n_peg2"       		=>$n_peg2[0],
					   "n_jabatan" 		        =>$n_jabatan[0],
					   "i_orgb"          		=>$i_orgb[0]);
				
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
//=========================================================================
}	
?>