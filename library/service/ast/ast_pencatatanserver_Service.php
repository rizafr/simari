<?php
class ast_pencatatanserver_service 
{   
	private static $instance;
        // A private constructor; prevents direct creation of object
        
    	private function __construct() 
    	{
      	//  echo 'I am constructed';
    	} 
    	// The singleton method
    	
     	public static function getInstance() 
     	{
	        if (!isset(self::$instance)) 
	        {
	           $c = __CLASS__;
	           self::$instance = new $c;
	       	}
	       	return self::$instance;
    	}
	
	//untuk mengeluarkan list hardware pertama waktu di klik menu server, device
	public function getDataHardWareList($server,$pageNumber,$itemPerPage)  
	{
	   //echo "masuk services getDataHardWareList";	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   $svr = $server; 
	   //echo "svr = ".$svr;
	   //echo "pageNumber = ".$pageNumber;
	   //echo "itemPerPage = ".$itemPerPage;
	   
	   try 
	   {  	         	 
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
										FROM e_ast_hardware_0_tm A, e_ast_vendor_0_tr B, e_ast_sskel_0_tr C 
										where A.i_rekanan = B.i_rekanan 
										 and substr(a.c_barang,1,1) = c.kd_gol
										 and substr(a.c_barang,2,2) = c.kd_bid 
										 and substr(a.c_barang,4,2) = c.kd_kel
										 and substr(a.c_barang,6,2) = c.kd_skel
										 and substr(a.c_barang,8,3) = c.kd_sskel
										 and A.c_hw=?
										 and (c_status_lengkap is null or c_status_lengkap ='T')
										 ",$svr); 
		 }
		 else
		 {
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;	
		 	$result = $db->fetchAll("SELECT A.d_anggaran,A.c_barang,
											to_char(A.i_aset,'09999') as no_aset,
								 				A.i_hw,
								 				A.n_hw,
								 				A.c_hw_type, 
								 				A.i_hw_register,
								 				B.n_rekanan,
								 				A.d_hw_garansi,	
								 				A.d_perolehan,
								 				A.i_rekanan,
								 				B.a_rekanan,
								 				A.q_hw_masapakai,
								 				A.e_hw,	
								         		C.ur_sskel,
												c_hw_status,
												e_hw_fungsi,
												i_rekanan_ref, a_prsh_jalan,
												i_hw_investasi
									 FROM e_ast_hardware_0_tm A, e_ast_vendor_0_tr B, e_ast_sskel_0_tr C 
									 where A.i_rekanan = B.i_rekanan 
									 and substr(a.c_barang,1,1) = c.kd_gol
									 and substr(a.c_barang,2,2) = c.kd_bid 
									 and substr(a.c_barang,4,2) = c.kd_kel
									 and substr(a.c_barang,6,2) = c.kd_skel
									 and substr(a.c_barang,8,3) = c.kd_sskel
									 and A.c_hw=? 
									 and (c_status_lengkap is null or c_status_lengkap ='T')
									 limit $xLimit offset $xOffset",$svr); 
	         	 $jmlResult = count($result);
	         	 //echo "jumlah =".$jmlResult;		
			 if($jmlResult > 0)
			 {
				 for ($j = 0; $j < $jmlResult; $j++) 
				 {			 
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
						
						$a_prsh_jalan 	= $db->fetchCol("select a_prsh_jalan
													from e_rekanan_0_0_tm a,e_rekanan_ijin_prsh_tm b,e_rekanan_ijin_usaha_tm c,e_rekanan_almt_prsh_tm d
													where a.i_rekanan = b.i_rekanan and a.i_rekanan = d.i_rekanan
													and b.i_prsh_ijinusaha = c.i_prsh_ijinusaha 
													and (c.n_prsh_ijinusaha  like 'TI%' or c.n_prsh_ijinusaha  like 'Komputer%')
													and a.i_rekanan=? ",$result[$j]->i_rekanan_ref);
						//...................................................................................................................
						if($i_rekanan_ref =='--' || $i_rekanan_ref =='-' ){
							//field field yg akan ditampilkan aja di gui 
							$hasilAkhir[$j] = array("d_anggaran"  		=>(string)$result[$j]->d_anggaran,
					           					"c_barang"  		=>(string)$result[$j]->c_barang,
					           					"i_aset" 	 		=>(string)$result[$j]->no_aset,
					           					"i_hw"  			=>(string)$result[$j]->i_hw,
					           					"n_hw"  			=>(string)$result[$j]->n_hw,
												"c_hw_type"  		=>(string)$result[$j]->c_hw_type,
												"i_hw_register"  	=>(string)$result[$j]->i_hw_register,
												"d_hw_garansi"  	=>(string)$result[$j]->d_hw_garansi,
												"d_perolehan"  		=>(string)$result[$j]->d_perolehan,
												"i_rekanan"  		=>(string)$result[$j]->i_rekanan,
												"n_rekanan"  		=>(string)$result[$j]->n_rekanan,
												"a_rekanan"  		=>(string)$result[$j]->a_rekanan,
												"q_hw_masapakai"  	=>(string)$result[$j]->q_hw_masapakai,
												"e_hw"  			=>(string)$result[$j]->e_hw,
												"ur_sskel"  		=>(string)$result[$j]->ur_sskel,
												"c_hw_status"		=>(string)$result[$j]->c_hw_status,
												"e_hw_fungsi"		=>(string)$result[$j]->e_hw_fungsi,
												"i_hw_investasi"		=>(string)$result[$j]->i_hw_investasi);
						}else{
							$hasilAkhir[$j] = array("d_anggaran"  		=>(string)$result[$j]->d_anggaran,
					           					"c_barang"  		=>(string)$result[$j]->c_barang,
					           					"i_aset" 	 		=>(string)$result[$j]->no_aset,
					           					"i_hw"  			=>(string)$result[$j]->i_hw,
					           					"n_hw"  			=>(string)$result[$j]->n_hw,
												"c_hw_type"  		=>(string)$result[$j]->c_hw_type,
												"i_hw_register"  	=>(string)$result[$j]->i_hw_register,
												"d_hw_garansi"  	=>(string)$result[$j]->d_hw_garansi,
												"d_perolehan"  		=>(string)$result[$j]->d_perolehan,
												"i_rekanan"  		=>(string)$result[$j]->i_rekanan,
												"n_rekanan"  		=>$n_rekanan[0],
												"a_rekanan"  		=>$a_prsh_jalan[0],
												"q_hw_masapakai"  	=>(string)$result[$j]->q_hw_masapakai,
												"e_hw"  			=>(string)$result[$j]->e_hw,
												"ur_sskel"  		=>(string)$result[$j]->ur_sskel,
												"c_hw_status"		=>(string)$result[$j]->c_hw_status,
												"e_hw_fungsi"		=>(string)$result[$j]->e_hw_fungsi,
												"i_hw_investasi"		=>(string)$result[$j]->i_hw_investasi);
						}
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
	
	//untuk mengeluarkan list hardware pada waktu di klik button cari 
	public function getCariDataHardWareList($id,$kodeHW,$pageNumber,$itemPerPage) 
	{
	   //echo "masuk...services getCariDataHardWareList";
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   $namaHW = '%'.strtoupper($id).'%';
	   $kodeHW = $kodeHW;
	   
	   //echo "namaHW = ".$namaHW;
	   //echo "kodeHW = ".$kodeHW;
	   
	   $where[]=$kodeHW;
	   $where[]=$namaHW;
	   
	   try 
	   {  	         	 
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
			FROM e_ast_hardware_0_tm A, e_ast_vendor_0_tr B 
			where A.i_rekanan = B.i_rekanan and A.c_hw=? and upper(A.n_hw) like upper(?)",$where);
		 }
		 else
		 {
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;
			$result = $db->fetchAll("SELECT A.d_anggaran,A.c_barang,A.i_aset,  
			 				A.i_hw,
			 				A.n_hw,
			 				A.c_hw_type, 
			 				A.i_hw_register,
							A.c_hw_status,
							A.e_hw_fungsi,
			 				B.n_rekanan,
			 				A.d_hw_garansi,	
			 				A.d_perolehan,
			 				A.i_rekanan,
			 				B.a_rekanan,
			 				A.q_hw_masapakai,
			 				A.e_hw
			FROM e_ast_hardware_0_tm A, e_ast_vendor_0_tr B 
			where A.i_rekanan = B.i_rekanan 
			and A.c_hw=? and upper(A.n_hw) like upper(?) limit $xLimit offset $xOffset",$where);
	         	$jmlResult = count($result);         	 
	         	//echo "jml cari Service = ".$jmlResult;
		
			if($jmlResult > 0)
			{
				 for ($j = 0; $j < $jmlResult; $j++) 
				 {			 
				 	//field field yg akan ditampilkan aja di gui 
		           		$hasilAkhir[$j] = array("d_anggaran" 		=>(string)$result[$j]->d_anggaran,
								"c_barang"  		=>(string)$result[$j]->c_barang,
								"i_aset" 		=>(string)$result[$j]->i_aset,
								"i_hw"  		=>(string)$result[$j]->i_hw,
		           				"n_hw"  		=>(string)$result[$j]->n_hw,
								"c_hw_type"  		=>(string)$result[$j]->c_hw_type,
								"i_hw_register"  	=>(string)$result[$j]->i_hw_register,
								"c_hw_status"  	=>(string)$result[$j]->c_hw_status,
								"e_hw_fungsi"  	=>(string)$result[$j]->e_hw_fungsi,
								"n_rekanan"  		=>(string)$result[$j]->n_rekanan,
								"d_hw_garansi"  	=>(string)$result[$j]->d_hw_garansi,
								"d_perolehan"  		=>(string)$result[$j]->d_perolehan,
								"i_rekanan"  		=>(string)$result[$j]->i_rekanan,
								"a_rekanan"  		=>(string)$result[$j]->a_rekanan,
								"q_hw_masapakai"  	=>(string)$result[$j]->q_hw_masapakai,
								"e_hw"  		=>(string)$result[$j]->e_hw,
								"ur_sskel"  		=>(string)$result[$j]->ur_sskel);
								  
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
	
	 
	//untuk mengeluarkan list vendor waktu diklik button no vendor
	public function getVendorList($pageNumber,$itemPerPage) 
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
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_vendor_0_tr");
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				
				$result = $db->fetchAll("SELECT i_rekanan,n_rekanan,a_rekanan,n_rekanan_agendistr,
								i_rekanan_telp,n_rekanan_kontak 
								FROM e_ast_vendor_0_tr");
								 
				
				$jmlResult = count($result);
				//echo "jumlah di server =".$jmlResult;
				
				for ($j = 0; $j < $jmlResult; $j++) 
				{
					$hasilAkhir[$j] = array("i_rekanan" 	=>(string)$result[$j]->i_rekanan,
					"n_rekanan"  				=>(string)$result[$j]->n_rekanan,
					"a_rekanan" 				=>(string)$result[$j]->a_rekanan,
					"n_rekanan_agendistr" 			=>(string)$result[$j]->n_rekanan_agendistr,
					"i_rekanan_telp" 			=>(string)$result[$j]->i_rekanan_telp,
					"n_rekanan_kontak" 			=>(string)$result[$j]->n_rekanan_kontak);
					 
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
	
	// untuk menggenarate call gen_nomorbarang di servercatat.phtml, servercatatmdv.phtml
	public function queryNourutmax($kode) 
	{   
	   //echo "kode = ".$kode;	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try 
	   {
	     	$db->setFetchMode(Zend_Db::FETCH_OBJ);
	     	
		$where[] = $kode;
		
		$result = $db->fetchOne('SELECT gen_nomorbarang(?)',$where);		
	     	return $result;
	   } 
	   catch (Exception $e) 
	   {
         	$db->rollBack();
         	echo $e->getMessage().'<br>';
	     	return  0;
	   }
	}  
	
	//untuk memasukan data ke tabel e_ast_hardware_0_tr dari servercatat.phtml, servercatatmdv.phtml
	public function getInsertDataHW($data) 
	{		
		  
		//echo "no vendor/i_rekanan = ".$data['no_vendor'];
		//echo "+q_hw_masapakai = ".$data['q_hw_masapakai'];
		//echo "+keterangan SRVC = ".$data['keterangan'];
		
		$registry = Zend_Registry::getInstance();
	   	$db = $registry->get('db');
	   	try 
	   	{
		    $db->beginTransaction();
			$insertdataHW = array("d_anggaran"  	=>$data['thnang'],
					      "c_barang"  	=>$data['kdbrg'],	
			              "i_aset"  	=>$data['noaset'],
					      "d_perolehan" =>$data['tglPerl'],
					      "c_hw"		=>$data['jenisserver'],
					      "i_hw"   		=>$data['noServer'],
					      "n_hw"   		=>$data['n_server'],
					      "c_hw_type"   	=>$data['type'],
					      "i_hw_register" 	=>$data['no_register'],
						  "c_hw_status" 	=>$data['status'],
						  "e_hw_fungsi" 	=>$data['fungsi'],
					      "i_rekanan"       =>$data['no_vendor'],
					      "d_hw_garansi"   	=>$data['garansi'],
					      "q_hw_masapakai"   =>$data['q_hw_masapakai'],
					      "e_hw" 		=>$data['keterangan'],
					      "i_entry"       	=>$data['i_entry'],
					      "d_entry"       	=>date("Y-m-d"),
					      "i_hw_investasi"       	=>$data['noinvent']);
						 // "e_hw_fungsi" 	=>$data['fungsi'],
						 // "c_hw_status" 	=>$data['status']);
			$db->insert("e_ast_hardware_0_tm", $insertdataHW);
			$db->commit();
			$_hasil = array("rcNumber"=>"1","rcDesc"  =>"Proses Sukses");
		     	return 'sukses';
	   	} 
	   	catch (Exception $e) 
	   	{
	         	$db->rollBack();
	         	echo $e->getMessage().'<br>';
		     	return 'gagal';
	   	}
	}	
	
	//untuk memasukan data ke tabel e_ast_hardware_toolkit_tm dari toolcatat.phtml, toolcatatmdv.phtml
	public function getInsertDataToolkitHW($data) 
	{	
		//echo "+masuk service getInsertDataToolkitHW";
		//echo "+jenisToolkit =".$data['jenisToolkit'];
		//echo "+noserver =".$data['noserver'];
		//echo "+notoolkit =".$data['notoolkit'];
		//echo "+jumlah =".$data['jumlah'];
		
		$registry = Zend_Registry::getInstance();
	   	$db = $registry->get('db');
	   	try 
	   	{
		     	$db->beginTransaction();
			$insertdatatoolkit = array("d_anggaran" =>$data['thnang3'],
						"c_barang"  		=>$data['kdbrg3'],	
						"i_aset"  		=>$data['noaset3'],
						"d_perolehan"   	=>$data['tglPerl3'],					      
						"c_hw"			=>$data['jenisserver'],
						"i_hw"   		=>$data['noserver'],
						"i_toolkit" 		=>$data['notoolkit'],
						"c_toolkit_jenis"	=>$data['jenisToolkit'],					      
						"n_toolkit" 		=>$data['namaToolkit'],
						"e_toolkit"       	=>$data['deskripsi'],
						"q_toolkit"   		=>$data['jumlah'],
						"i_entry"       	=>"svr",
						"d_entry"       	=>date("Y-m-d"),	
						"i_hw_investasi"  		=>$data['noinvent']);
					      
			$db->insert("e_ast_hardware_toolkit_tm", $insertdatatoolkit);
			$db->commit();
			$_hasil = array("rcNumber"=>"1","rcDesc"  =>"Proses Sukses");
		     	return 'sukses';
	   	} 
	   	catch (Exception $e) 
	   	{
	         	$db->rollBack();
	         	echo $e->getMessage().'<br>';
		     	return 'gagal';
	   	}
	}
	
	
	//untuk mengeluarkan list data toolkit 
	public function getDataToolkitList(array $data) 
	{	
		//echo "+masuk getDataToolkitList";
		//echo "+thnang =".$data['thnang'];
		//echo "+kdbrg =".$data['kdbrg'];
		//echo "+noaset =".$data['noaset'];
		//echo "+tglPerl =".$data['tglPerl'];
		//echo "+jenisserver =".$data['jenisserver'];
		//echo "+i_hw =".$data['i_hw'];
		
		$registry = Zend_Registry::getInstance();
	   	$db = $registry->get('db');
		
		//parameter getDataToolkitList 		   
	   	$where[] = trim($data['thnang']);
	        $where[] = trim($data['kdbrg']);
		$where[] = trim($data['noaset']);
	   	$where[] = trim($data['tglPerl']);
	   	$where[] = trim($data['jenisserver']);
	   	$where[] = trim($data['i_hw']);
	   
	   	try 
	   	{
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$result = $db->fetchAll('SELECT i_toolkit,
							c_toolkit_jenis,
							n_toolkit,
							e_toolkit,
							q_toolkit,
							i_hw_investasi
						FROM e_ast_hardware_toolkit_tm
						WHERE d_anggaran = ? and c_barang = ?
						and i_aset = ? and d_perolehan = ?
						and c_hw = ? and i_hw = ?',$where); 
						
	         	$jmlResult = count($result);	
	         	//echo "jml Service getDataToolkitList = ".$jmlResult;	         	
	         	if($jmlResult > 0)
			{
				 for ($j = 0; $j < $jmlResult; $j++) 
				 {			 		
	           			$hasilAkhir[$j] = array("i_toolkit" 	=>(string)$result[$j]->i_toolkit,
							"c_toolkit_jenis"  	=>(string)$result[$j]->c_toolkit_jenis,
							"n_toolkit" 		=>(string)$result[$j]->n_toolkit,
							"e_toolkit" 		=>(string)$result[$j]->e_toolkit,
							"q_toolkit" 		=>(string)$result[$j]->q_toolkit,
							"i_hw_investasi" 		=>(string)$result[$j]->i_hw_investasi);
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
	
	//untuk update list data toolkit
	public function getUpdateDataToolkitHW(array $data) 
	{	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try 
	   {
	     $db->beginTransaction();
	     /* $updateDataToolkitHW = array("d_anggaran" 		=>$data['thnang'],
					      "c_barang"  	=>$data['kdbrg'],	
			              "i_aset"  	=>$data['noaset'],
					      "d_perolehan"   	=>$data['tglPerl'],					      
					      "c_hw"		=>$data['jenisserver'],
					      "i_hw"   		=>$data['noserver'],
					      "i_toolkit" 	=>$data['notoolkit'],
					      "c_toolkit_jenis"	=>$data['jenisToolkit'],					      
					      "n_toolkit" 	=>$data['namaToolkit'],
					      "e_toolkit"       =>$data['deskripsi'],
					      "q_toolkit"   	=>$data['jumlah'],
					      "i_entry"       	=>"svr",
					      "d_entry"       	=>date("Y-m-d")); */
						  
		$updateDataToolkitHW = array(
					      "c_toolkit_jenis"	=>$data['jenisToolkit'],					      
					      "n_toolkit" 	=>$data['namaToolkit'],
					      "e_toolkit"       =>$data['deskripsi'],
					      "q_toolkit"   	=>$data['jumlah'],
					      "i_entry"       	=>$data['i_entry'],
					      "d_entry"       	=>date("Y-m-d"),
					      "i_hw_investasi"       	=>$data['noinvent']);
	     
	     
	     //parameter getUpdateDataToolkitHW 		   
	     $where[] = "d_anggaran  	=  '".trim($data['thnang'])."'";
	     $where[] = "c_barang       =  '".trim($data['kdbrg'])."'";
	     $where[] = "i_aset   	=  '".trim($data['noaset'])."'";
	     $where[] = "d_perolehan   	=  '".trim($data['tglPerl'])."'";
	     $where[] = "c_hw   	=  '".trim($data['jenisserver'])."'";
	     $where[] = "i_hw   	=  '".trim($data['noserver'])."'";
	     $where[] = "i_toolkit   	=  '".trim($data['notoolkit'])."'";
	     
	     $db->update('e_ast_hardware_toolkit_tm',$updateDataToolkitHW,$where);
	     $db->commit();
	     return 'sukses';
	   } 
	   catch (Exception $e) 
	   {
         	$db->rollBack();
         	echo $e->getMessage().'<br>';
	     	return 'gagal';
	   }
	}	
	
	//untuk delete list data toolkit
	public function getDeleteDataToolkitHW(array $data) 
	{ 	   	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try 
	   {
	     	$db->beginTransaction();
	 	//parameter getDeleteDataToolkitHW 		   
	     	$where[] = "d_anggaran  	=  '".trim($data['thnang'])."'";
	     	$where[] = "c_barang       	=  '".trim($data['kdbrg'])."'";
	     	$where[] = "i_aset   		=  '".trim($data['noaset'])."'";
	     	$where[] = "d_perolehan   	=  '".trim($data['tglPerl'])."'";
	     	$where[] = "c_hw   		=  '".trim($data['c_hw'])."'";
	     	$where[] = "i_hw   		=  '".trim($data['i_hw'])."'";
	     	$where[] = "i_toolkit   	=  '".trim($data['i_toolkit'])."'";
		
		$db->delete('e_ast_hardware_toolkit_tm', $where);
		$db->commit();
	     	return 'sukses';
	   } 
	   catch (Exception $e) 
	   {
         	$db->rollBack();
         	echo $e->getMessage().'<br>';
	     	return 'gagal';
	   }
	}
	
	//untuk update list data hardware
	public function getUpdateDataHW(array $data) 
	{	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try 
	   {
	     $db->beginTransaction();
	     $updateDataHW = array("n_hw"   			=>$data['n_hw'],
								"c_hw_type"   		=>$data['type'],
								"i_hw_register"   	=>$data['no_register'],
								"c_hw_status"   	=>$data['status'],
								"e_hw_fungsi"   	=>$data['fungsi'],
								"q_hw_masapakai"  	=>$data['q_hw_masapakai'],
								"e_hw"   			=>$data['keterangan'],
								"c_status_lengkap" 	=>$data['nStatus'],
								"d_hw_garansi"   	=>$data['garansi'],
								"i_entry"       	=>$data['i_entry'],
								"d_entry"       	=>date("Y-m-d"),
								"i_hw_investasi"       	=>$data['noinvent']);
	          
	     //parameter getUpdateDataHW 		   
	     // $where[] = "d_anggaran  	=  '".trim($data['thnang'])."'";
	     // $where[] = "c_barang       =  '".trim($data['kdbrg'])."'";
	     // $where[] = "i_aset   	=  '".trim($data['noaset'])."'";
	     // $where[] = "d_perolehan   	=  '".trim($data['tglPerl'])."'";
	     // $where[] = "c_hw   	=  '".trim($data['c_hw'])."'";
	     $where[] = "i_hw   	=  '".trim($data['i_hw'])."'";
	     
	     $db->update('e_ast_hardware_0_tm',$updateDataHW,$where);
	     $db->commit();
	     return 'sukses';
	   } 
	   catch (Exception $e) 
	   {
         	$db->rollBack();
         	echo $e->getMessage().'<br>';
	     	return 'gagal';
	   }
	}	
	
	//untuk delete list data HW sekaligus delete data toolkit nya 
	public function getDeleteDataHW(array $data) 
	{    
	   //echo "+masuk services getDeleteDataHW";
	   //echo "+thnang 	=".$data['thnang'];
	   //echo "+kdbrg 	=".$data['kdbrg'];
	   //echo "+noaset 	=".$data['noaset'];
	   //echo "+tglPerl 	=".$data['tglPerl'];
	   //echo "+c_hw 		=".$data['c_hw'];
	   //echo "+i_hw 		=".$data['i_hw'];
	   //echo "+i_toolkit	=".$data['i_toolkit'];
	   	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try 
	   {
	     	$db->beginTransaction();
	 	//par getDeleteDataHW & getDeleteDataToolkitHW		   
	     	$where[] = "d_anggaran  	=  '".trim($data['thnang'])."'";
	     	$where[] = "c_barang       	=  '".trim($data['kdbrg'])."'";
	     	$where[] = "i_aset   		=  '".trim($data['noaset'])."'";
	     	$where[] = "d_perolehan   	=  '".trim($data['tglPerl'])."'";
	     	$where[] = "c_hw   		=  '".trim($data['c_hw'])."'";
	     	$where[] = "i_hw   		=  '".trim($data['i_hw'])."'";
	     	//$where[] = "i_tookit  		=  '".trim($data['i_toolkit'])."'";
		
		$db->delete('e_ast_hardware_0_tm',$where);
		$db->delete('e_ast_hardware_toolkit_tm',$where);
		$db->commit();
	     	return 'sukses';
	   } 
	   catch (Exception $e) 
	   {
         	$db->rollBack();
         	echo $e->getMessage().'<br>';
	     	return 'gagal';
	   }
	}
	
	//=============== PINDAHAN DARI AST_REFERENSI_SERVICE 29 JANUARI 2008 ======================================
	public function getNoInventarisJaringan_Old($pageNumber,$itemPerPage) {
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{
			$where[] =$kbr;
			//$where[] =$nbrg;
		 
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasil = $db->fetchAll("SELECT A.thn_ang as thn_ang,
										A.kd_brg as kd_brg,B.ur_sskel as ur_sskel,
										A.tgl_perlh as tgl_perlh,
										to_char(no_aset,'09999') as no_aset
										FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
										where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and kd_gol = '2'
											and kd_bid = '12'
											and kd_kel = '02'
											and kd_skel = '04'
											and kd_sskel != '001' 
											and not exists(select * from e_ast_hardware_0_tm c
                                              where A.thn_ang = c.d_anggaran
                                              and   A.kd_brg  = c.c_barang
                                              and   a.no_aset = c.i_aset)
										");
				$hasilAkhir = count($hasil);
			}
			else
			{
			
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
			 
				$result = $db->fetchAll("SELECT A.thn_ang as thn_ang,
										A.kd_brg as kd_brg,B.ur_sskel as ur_sskel,
										A.tgl_perlh as tgl_perlh,
										to_char(no_aset,'09999') as no_aset
										FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
										where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and kd_gol = '2'
											and kd_bid = '12'
											and kd_kel = '02'
											and kd_skel = '04'
											and kd_sskel != '001' 
											 and not exists(select * from e_ast_hardware_0_tm c
                                              where A.thn_ang = c.d_anggaran
                                              and   A.kd_brg  = c.c_barang
                                              and   a.no_aset = c.i_aset)
										ORDER BY thn_ang 
										limit $xLimit offset $xOffset");
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("thn_ang" 	=>(string)$result[$j]->thn_ang,
										"kd_brg"  	=>(string)$result[$j]->kd_brg,
										"no_aset" 	=>(string)$result[$j]->no_aset,
										"ur_sskel" 	=>(string)$result[$j]->ur_sskel,
										"tgl_perlh" =>(string)$result[$j]->tgl_perlh);
				}	
			}	 
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	
	public function getNoInvJaringanByNama($pageNumber,$itemPerPage,$nmBarang) {
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$nmbr = $nmBarang.'%';
		try 
		{
			$where[] =strtoupper($nmbr);
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasil = $db->fetchAll("SELECT A.thn_ang as thn_ang,
										A.kd_brg as kd_brg,B.ur_sskel as ur_sskel,
										A.tgl_perlh as tgl_perlh,
										to_char(no_aset,'09999') as no_aset
										FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
										where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and kd_gol = '2'
											and kd_bid = '12'
											and kd_kel = '02'
											and kd_skel = '04'
											and kd_sskel != '001' 
											and B.ur_sskel like ?
											and not exists(select * from e_ast_hardware_0_tm c
                                              where A.thn_ang = c.d_anggaran
                                              and   A.kd_brg  = c.c_barang
                                              and   a.no_aset = c.i_aset)
										",$where);
				$hasilAkhir = count($hasil);
			}
			else
			{
			
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
			 
				$result = $db->fetchAll("SELECT A.thn_ang as thn_ang,
										A.kd_brg as kd_brg,B.ur_sskel as ur_sskel,
										A.tgl_perlh as tgl_perlh,
										to_char(no_aset,'09999') as no_aset
										FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
										where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and kd_gol = '2'
											and kd_bid = '12'
											and kd_kel = '02'
											and kd_skel = '04'
											and kd_sskel != '001'
											and B.ur_sskel like ?
                                            and not exists(select * from e_ast_hardware_0_tm c
                                              where A.thn_ang = c.d_anggaran
                                              and   A.kd_brg  = c.c_barang
                                              and   a.no_aset = c.i_aset)											
										ORDER BY thn_ang 
										limit $xLimit offset $xOffset",$where);
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("thn_ang" 	=>(string)$result[$j]->thn_ang,
										"kd_brg"  	=>(string)$result[$j]->kd_brg,
										"no_aset" 	=>(string)$result[$j]->no_aset,
										"ur_sskel" 	=>(string)$result[$j]->ur_sskel,
										"tgl_perlh" =>(string)$result[$j]->tgl_perlh);
				}	
			}	 
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	
	public function getNoInventarisServer($pageNumber,$itemPerPage,$nmBarang) {
		$kdBrg 	= '2120204001';
		$kbr 	= $kdBrg.'%';
		$kdBrg1 	= '2060101048';
		$kbr1 	= $kdBrg.'%';
		$nmbrg   = '%'.strtoupper($nmBarang).'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{
			$where[] =$kbr;
			$where[] =$kb1r;
			$where[] =$nmbrg;
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasil = $db->fetchAll("SELECT to_char(tgl_perlh,'yyyy') as thn_ang,
										A.kd_brg as kd_brg,B.ur_sskel as ur_sskel,
										A.tgl_perlh as tgl_perlh,
										to_char(no_aset,'09999') as no_aset
										FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
										where substr(a.kd_brg,1,1) = b.kd_gol
										      and substr(a.kd_brg,2,2) = b.kd_bid 
										      and substr(a.kd_brg,4,2) = b.kd_kel
										      and substr(a.kd_brg,6,2) = b.kd_skel
										      and substr(a.kd_brg,8,3) = b.kd_sskel
										      and (kd_brg like ? or kd_brg like ?)
											  and upper(b.ur_sskel) like ?	
											  and not exists(select * from e_ast_hardware_0_tm c
                                              where c.d_anggaran = to_char(tgl_perlh,'yyyy')
                                              and   A.kd_brg  = c.c_barang
                                              and   a.no_aset = c.i_aset)
											  ",$where); 
					
				$hasilAkhir = count($hasil);
			}
			else
			{
			
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
			 
					$result = $db->fetchAll("SELECT to_char(tgl_perlh,'yyyy') as thn_ang,
										A.kd_brg as kd_brg,B.ur_sskel as ur_sskel,
										A.tgl_perlh as tgl_perlh,
										to_char(no_aset,'09999') as no_aset
										FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
										where substr(a.kd_brg,1,1) = b.kd_gol
										      and substr(a.kd_brg,2,2) = b.kd_bid 
										      and substr(a.kd_brg,4,2) = b.kd_kel
										      and substr(a.kd_brg,6,2) = b.kd_skel
										      and substr(a.kd_brg,8,3) = b.kd_sskel
										      and(kd_brg like ? or kd_brg like ?)
											  and upper(b.ur_sskel) like ?	
											  and not exists(select * from e_ast_hardware_0_tm c
                                              where c.d_anggaran = to_char(tgl_perlh,'yyyy')
                                              and   A.kd_brg  = c.c_barang
                                              and   a.no_aset = c.i_aset)											  
											  ORDER BY thn_ang
										limit $xLimit offset $xOffset",$where); 
					
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("thn_ang" 	=>(string)$result[$j]->thn_ang,
										"kd_brg"  	=>(string)$result[$j]->kd_brg,
										"no_aset" 	=>(string)$result[$j]->no_aset,
										"ur_sskel" 	=>(string)$result[$j]->ur_sskel,
										"tgl_perlh" =>(string)$result[$j]->tgl_perlh);
				}	
			}	 
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	
	//add 07 mei 2008 =================================tambahan pencarian berdasarkan tgl =======================
	
	public function getNoInventarisServerByPeriode($pageNumber,$itemPerPage,$nmBarang,$tglAwal,$tglAkhir) {
		$kdBrg 	= '2120204001';
		$kbr 	= $kdBrg.'%';
		$kdBrg1 	= '2060101048';
		$kbr1 	= $kdBrg.'%';
		$nmbrg   = '%'.strtoupper($nmBarang).'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{
			$where[] =$kbr;
			$where[] =$kb1r;
			$where[] =$nmbrg;
			$where[] =$tglAwal;
			$where[] =$tglAkhir;
		 
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasil = $db->fetchAll("SELECT to_char(tgl_perlh,'yyyy') as thn_ang,
										A.kd_brg as kd_brg,B.ur_sskel as ur_sskel,
										A.tgl_perlh as tgl_perlh,
										to_char(no_aset,'09999') as no_aset
										FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
										where substr(a.kd_brg,1,1) = b.kd_gol
										      and substr(a.kd_brg,2,2) = b.kd_bid 
										      and substr(a.kd_brg,4,2) = b.kd_kel
										      and substr(a.kd_brg,6,2) = b.kd_skel
										      and substr(a.kd_brg,8,3) = b.kd_sskel
										      and (kd_brg like ? or kd_brg like ?)
											  and upper(b.ur_sskel) like ?	
											  and A.tgl_perlh between ? and ?
											  and not exists(select * from e_ast_hardware_0_tm c
                                              where c.d_anggaran = to_char(tgl_perlh,'yyyy')
                                              and   A.kd_brg  = c.c_barang
                                              and   a.no_aset = c.i_aset)
											  ",$where); 
					
				$hasilAkhir = count($hasil);
			}
			else
			{
			
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
			 
					$result = $db->fetchAll("SELECT to_char(tgl_perlh,'yyyy') as thn_ang,
										A.kd_brg as kd_brg,B.ur_sskel as ur_sskel,
										A.tgl_perlh as tgl_perlh,
										to_char(no_aset,'09999') as no_aset
										FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
										where substr(a.kd_brg,1,1) = b.kd_gol
										      and substr(a.kd_brg,2,2) = b.kd_bid 
										      and substr(a.kd_brg,4,2) = b.kd_kel
										      and substr(a.kd_brg,6,2) = b.kd_skel
										      and substr(a.kd_brg,8,3) = b.kd_sskel
										      and(kd_brg like ? or kd_brg like ?)
											  and upper(b.ur_sskel) like ?	
											  and A.tgl_perlh between ? and ?
											  and not exists(select * from e_ast_hardware_0_tm c
                                              where c.d_anggaran = to_char(tgl_perlh,'yyyy')
                                              and   A.kd_brg  = c.c_barang
                                              and   a.no_aset = c.i_aset)											  
											  ORDER BY thn_ang
										limit $xLimit offset $xOffset",$where); 
					
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("thn_ang" 	=>(string)$result[$j]->thn_ang,
										"kd_brg"  	=>(string)$result[$j]->kd_brg,
										"no_aset" 	=>(string)$result[$j]->no_aset,
										"ur_sskel" 	=>(string)$result[$j]->ur_sskel,
										"tgl_perlh" =>(string)$result[$j]->tgl_perlh);
				}	
			}	 
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	
	//===============================list jaringan =======================================================08 mei 08==============
	public function getNoInventarisJaringan($pageNumber,$itemPerPage,$nmBarang) {
	
		$nmbrg   = '%'.strtoupper($nmBarang).'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{
			$where[] =$nmbrg;
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasil = $db->fetchAll("SELECT to_char(tgl_perlh,'yyyy') as thn_ang,
										A.kd_brg as kd_brg,B.ur_sskel as ur_sskel,
										A.tgl_perlh as tgl_perlh,
										to_char(no_aset,'09999') as no_aset
										FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
										where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and kd_gol = '2'
											and kd_bid = '12'
											and kd_kel = '02'
											and kd_skel = '04'
											and kd_sskel != '001'
											and upper(b.ur_sskel) like ?	
											and not exists(select * from e_ast_hardware_0_tm c
                                              where c.d_anggaran = to_char(tgl_perlh,'yyyy')
                                              and   A.kd_brg  = c.c_barang
                                              and   a.no_aset = c.i_aset)
											  ",$where); 
					
				$hasilAkhir = count($hasil);
			}
			else
			{
			
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
			 
					$result = $db->fetchAll("SELECT to_char(tgl_perlh,'yyyy') as thn_ang,
										A.kd_brg as kd_brg,B.ur_sskel as ur_sskel,
										A.tgl_perlh as tgl_perlh,
										to_char(no_aset,'09999') as no_aset
										FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
										where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and kd_gol = '2'
											and kd_bid = '12'
											and kd_kel = '02'
											and kd_skel = '04'
											and kd_sskel != '001'
											and upper(b.ur_sskel) like ?	
											and not exists(select * from e_ast_hardware_0_tm c
                                              where c.d_anggaran = to_char(tgl_perlh,'yyyy')
                                              and   A.kd_brg  = c.c_barang
                                              and   a.no_aset = c.i_aset)											  
											  ORDER BY thn_ang
										limit $xLimit offset $xOffset",$where); 
					
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("thn_ang" 	=>(string)$result[$j]->thn_ang,
										"kd_brg"  	=>(string)$result[$j]->kd_brg,
										"no_aset" 	=>(string)$result[$j]->no_aset,
										"ur_sskel" 	=>(string)$result[$j]->ur_sskel,
										"tgl_perlh" =>(string)$result[$j]->tgl_perlh);
				}	
			}	 
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	
	//add 07 mei 2008 =================================tambahan pencarian berdasarkan tgl =======================
	
	public function getNoInventarisJaringanByPeriode($pageNumber,$itemPerPage,$nmBarang,$tglAwal,$tglAkhir) {
		
		$nmbrg   = '%'.strtoupper($nmBarang).'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{
			$where[] =$nmbrg;
			$where[] =$tglAwal;
			$where[] =$tglAkhir;
		 
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasil = $db->fetchAll("SELECT to_char(tgl_perlh,'yyyy') as thn_ang,
										A.kd_brg as kd_brg,B.ur_sskel as ur_sskel,
										A.tgl_perlh as tgl_perlh,
										to_char(no_aset,'09999') as no_aset
										FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
										where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and kd_gol = '2'
											and kd_bid = '12'
											and kd_kel = '02'
											and kd_skel = '04'
											and kd_sskel != '001'
											and upper(b.ur_sskel) like ?	
											and A.tgl_perlh between ? and ?
											and not exists(select * from e_ast_hardware_0_tm c
                                              where c.d_anggaran = to_char(tgl_perlh,'yyyy')
                                              and   A.kd_brg  = c.c_barang
                                              and   a.no_aset = c.i_aset)
											  ",$where); 
					
				$hasilAkhir = count($hasil);
			}
			else
			{
			
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
			 
					$result = $db->fetchAll("SELECT to_char(tgl_perlh,'yyyy') as thn_ang,
										A.kd_brg as kd_brg,B.ur_sskel as ur_sskel,
										A.tgl_perlh as tgl_perlh,
										to_char(no_aset,'09999') as no_aset
										FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
										where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and kd_gol = '2'
											and kd_bid = '12'
											and kd_kel = '02'
											and kd_skel = '04'
											and kd_sskel != '001'
											and upper(b.ur_sskel) like ?	
											and A.tgl_perlh between ? and ?
											and not exists(select * from e_ast_hardware_0_tm c
                                              where c.d_anggaran = to_char(tgl_perlh,'yyyy')
                                              and   A.kd_brg  = c.c_barang
                                              and   a.no_aset = c.i_aset)											  
											  ORDER BY thn_ang
										limit $xLimit offset $xOffset",$where); 
					
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("thn_ang" 	=>(string)$result[$j]->thn_ang,
										"kd_brg"  	=>(string)$result[$j]->kd_brg,
										"no_aset" 	=>(string)$result[$j]->no_aset,
										"ur_sskel" 	=>(string)$result[$j]->ur_sskel,
										"tgl_perlh" =>(string)$result[$j]->tgl_perlh);
				}	
			}	 
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	
	//=======================melihat distribusi ========================== 08apr 08 ==========
	
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
								   
		 // $result = $db->fetchAll("SELECT A.i_barang_serah,A.d_barang_serah,
									// A.i_peg_nipterima,A.i_orgb_penerima,B.n_peg,
									// A.i_peg_nippemberi,A.i_orgb_pemberi,
									// A.e_keterangan 
									// FROM e_ast_dir_0_tm A, e_sdm_pegawai_0_tm B
									// where A.i_peg_nipterima = B.i_peg_nip
									// and i_barang_serah like ? 
									// and c_barang_statserah = ?
								 // limit $xLimit offset $xOffset",$where); 
         // $jmlResult = count($result);
		
		 // if($jmlResult > 0){
		 // for ($j = 0; $j < $jmlResult; $j++) {
		 
           // $hasilAkhir[$j] = array("i_barang_serah"            =>(string)$result[$j]->i_barang_serah,
								   // "d_barang_serah"            =>(string)$result[$j]->d_barang_serah,
								   // "i_peg_nipterima"           =>(string)$result[$j]->i_peg_nipterima,
								   // "i_orgb_penerima"           =>(string)$result[$j]->i_orgb_penerima,
								   // "n_peg"  		           =>(string)$result[$j]->n_peg,
								   // "i_peg_nippemberi"          =>(string)$result[$j]->i_peg_nippemberi,
								   // "i_orgb_pemberi"            =>(string)$result[$j]->i_orgb_pemberi,
								   // "e_keterangan"              =>(string)$result[$j]->e_keterangan);
								  
								  
							       
		
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
		 $result = $db->fetchAll("SELECT c_barang,d_aset_thnanggar,to_char(no_aset,'09999') as no_aset,i_ruang, 
									 merk_type,ur_sskel,n_lokasi
									 FROM e_ast_dir_item_tm a ,
									      e_sabm_t_master_tm c,e_ast_sskel_0_tr d, e_ast_lokasi_0_tr e
									   where  a.i_aset =  c.no_aset  and a.i_barang_serah = ?
									   and  a.c_barang = c.kd_brg and substr(c.kd_brg,1,1) = d.kd_gol
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
								   "ur_sskel"           =>(string)$result[$j]->ur_sskel,
								   "n_lokasi"			=>(string)$result[$j]->n_lokasi);
								  
								  
			  			       
		
		 }
        }	
        		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
	
	
	public function getSofwareList($pageNumber,$itemPerPage,$thnang,$kdbrg,$noaset) {
		 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $thnang;
		 $where[] = $kdbrg;
		 $where[] = $noaset;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
				                         FROM e_ast_distribusi_software_tm  a, e_ast_software_0_tr b
										Where a.i_sw=b.i_sw 
										and thn_ang=? and kd_brg=? and no_aset=?",$where);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("Select b.i_sw,n_sw ,n_sw_kelompok ,e_sw_platform ,
										i_sw_versi ,i_sw_nomorlicensi ,d_sw_releasepublish 
										from e_ast_distribusi_software_tm  a, e_ast_software_0_tr b
										Where a.i_sw=b.i_sw 
										and thn_ang=? and kd_brg=? and no_aset=?
											ORDER BY thn_ang
											limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_sw"           	=>(string)$result[$j]->i_sw,
								   "n_sw"           		=>(string)$result[$j]->n_sw,
								   "n_sw_kelompok"      	=>(string)$result[$j]->n_sw_kelompok,
								   "e_sw_platform"      	=>(string)$result[$j]->e_sw_platform,
								   "i_sw_versi"  			=>(string)$result[$j]->i_sw_versi,
								   "i_sw_nomorlicensi"      =>(string)$result[$j]->i_sw_nomorlicensi,
								   "d_sw_releasepublish"    =>(string)$result[$j]->d_sw_releasepublish);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
}	
?>