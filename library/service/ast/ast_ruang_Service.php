<?php
class ast_ruang_Service 
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
	
	//untuk mengeluarkan list ruangan pertama waktu di klik menu daftar inventaris ruangan
	//// Addition By Cah Bagus ////
	public function getDataInventarisRuanganListP($strSearch,$pageNumber,$itemPerPage) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {  	         	 
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if ($strSearch == '') $xquery = " ";
				else $xquery = " and A.i_ruang='$strSearch'";
			if(($pageNumber==0) && ($itemPerPage==0)){				
				$query="SELECT distinct A.c_barang,A.i_aset,A.i_ruang,		         			
		         			B.i_ruang_lokasi,B.n_gedung,C.ur_sskel,A.d_barang_peroleh,            				 
		         			D.merk_type,D.kd_pemilik			         				 				
						FROM e_ast_dir_item_tm A, e_ast_ruangan_0_tr B, e_ast_sskel_0_tr C, e_sabm_t_master_tm D
							 where A.i_ruang = B.i_ruang
							 and d.tgl_perlh = a.d_barang_peroleh 
							 and d.kd_brg = a.c_barang
							 and d.no_aset = a.i_aset
							 and substr(a.c_barang,1,1) = c.kd_gol
							 and substr(a.c_barang,2,2) = c.kd_bid 
							 and substr(a.c_barang,4,2) = c.kd_kel
							 and substr(a.c_barang,6,2) = c.kd_skel
							 and substr(a.c_barang,8,3) = c.kd_sskel";
				echo $query = $query.$xquery;
				$TempArr = $db->fetchAll($query);
				$hasilAkhir = count($TempArr);
				return $hasilAkhir;
			}else{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				$query="SELECT distinct A.c_barang,A.i_aset,A.i_ruang,		         			
		         			B.i_ruang_lokasi,B.n_gedung,C.ur_sskel,A.d_barang_peroleh,            				 
		         			D.merk_type,D.kd_pemilik			         				 				
						FROM e_ast_dir_item_tm A, e_ast_ruangan_0_tr B, e_ast_sskel_0_tr C, e_sabm_t_master_tm D
							 where A.i_ruang = B.i_ruang
							 and d.tgl_perlh = a.d_barang_peroleh 
							 and d.kd_brg = a.c_barang
							 and d.no_aset = a.i_aset
							 and substr(a.c_barang,1,1) = c.kd_gol
							 and substr(a.c_barang,2,2) = c.kd_bid 
							 and substr(a.c_barang,4,2) = c.kd_kel
							 and substr(a.c_barang,6,2) = c.kd_skel
							 and substr(a.c_barang,8,3) = c.kd_sskel
							 ";
				$query = $query.$xquery." limit $xLimit offset $xOffset";
				$result = $db->fetchAll($query);		
				$jmlResult = count($result);
				if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {			 
						$hasilAkhir[$j] = array("c_barang"  	=>(string)$result[$j]->c_barang,
								  "ur_sskel"  		=>(string)$result[$j]->ur_sskel,
								  "i_aset" 	 	=>(string)$result[$j]->i_aset,
								  "i_ruang"  		=>(string)$result[$j]->i_ruang,
								  "i_ruang_lokasi"	=>(string)$result[$j]->i_ruang_lokasi,
								  "d_barang_peroleh"  	=>(string)$result[$j]->d_barang_peroleh,
								  "merk_type"  		=>(string)$result[$j]->merk_type,
								  "kd_pemilik"  	=>(string)$result[$j]->kd_pemilik);
					}
					return $hasilAkhir;
	        	}		 
			}
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}
	public function getSearchBrgRuanganList($strSearch1,$strSearch2,$pageNumber,$itemPerPage) {	
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {  	         	 
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if ($strSearch1 == '') $xquery1 = " and true ";
				else $xquery1 = " and A.i_ruang like '%$strSearch1%'";
			if ($strSearch2 == '') $xquery2 = " and true ";
				else $xquery2 = " and A.c_barang like '%$strSearch2%'";
		    //echo "Page Number : ".$pageNumber."<br/>";
		    //echo "itemPerPage : ".$itemPerPage."<br/>";
			if(($pageNumber==0) && ($itemPerPage==0)){				
				$query="SELECT distinct A.c_barang,A.i_aset,A.i_ruang,		         			
		         			B.i_ruang_lokasi,C.ur_sskel,A.d_barang_peroleh,            				 
		         			D.merk_type,D.kd_pemilik			         				 				
						FROM e_ast_dir_item_tm A, e_ast_ruangan_0_tr B, e_ast_sskel_0_tr C, e_sabm_t_master_tm D
							 where A.i_ruang = B.i_ruang
							 and d.tgl_perlh = a.d_barang_peroleh 
							 and d.kd_brg = a.c_barang
							 and d.no_aset = a.i_aset
							 and substr(a.c_barang,1,1) = c.kd_gol
							 and substr(a.c_barang,2,2) = c.kd_bid 
							 and substr(a.c_barang,4,2) = c.kd_kel
							 and substr(a.c_barang,6,2) = c.kd_skel
							 and substr(a.c_barang,8,3) = c.kd_sskel";
				$query = $query.$xquery1.$xquery2;
				$TempArr = $db->fetchAll($query);
				$hasilAkhir = count($TempArr);
				return $hasilAkhir;
			}else{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				$query="SELECT distinct A.c_barang,A.i_aset,A.i_ruang,		         			
		         			B.i_ruang_lokasi,C.ur_sskel,A.d_barang_peroleh,            				 
		         			D.merk_type,D.kd_pemilik			         				 				
						FROM e_ast_dir_item_tm A, e_ast_ruangan_0_tr B, e_ast_sskel_0_tr C, e_sabm_t_master_tm D
							 where A.i_ruang = B.i_ruang
							 and d.tgl_perlh = a.d_barang_peroleh 
							 and d.kd_brg = a.c_barang
							 and d.no_aset = a.i_aset
							 and substr(a.c_barang,1,1) = c.kd_gol
							 and substr(a.c_barang,2,2) = c.kd_bid 
							 and substr(a.c_barang,4,2) = c.kd_kel
							 and substr(a.c_barang,6,2) = c.kd_skel
							 and substr(a.c_barang,8,3) = c.kd_sskel
							 ";
				$query = $query.$xquery1.$xquery2." limit $xLimit offset $xOffset";
				//echo "Query : ".$query;
				$result = $db->fetchAll($query);		
				$jmlResult = count($result);
				if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {			 
						$hasilAkhir[$j] = array("c_barang"  	=>(string)$result[$j]->c_barang,
								  "ur_sskel"  		=>(string)$result[$j]->ur_sskel,
								  "i_aset" 	 	=>(string)$result[$j]->i_aset,
								  "i_ruang"  		=>(string)$result[$j]->i_ruang,
								  "i_ruang_lokasi"	=>(string)$result[$j]->i_ruang_lokasi,
								  "d_barang_peroleh"  	=>(string)$result[$j]->d_barang_peroleh,
								  "merk_type"  		=>(string)$result[$j]->merk_type,
								  "kd_pemilik"  	=>(string)$result[$j]->kd_pemilik);
					}
					return $hasilAkhir;
	        	}		 
			}
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}
	public function getBrgRuanganListP($strSearch,$pageNumber,$itemPerPage) {	
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$strSearch = "%".strtoupper($strSearch)."%";
		try {  	         	 
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
			if ($strSearch == '') $xquery = " and true ";
			else $xquery = " and UPPER(b.ur_sskel) like '$strSearch'";
			
			if(($pageNumber==0) && ($itemPerPage==0)){				
			   
				$query="SELECT count (distinct a.kd_brg) 
						FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
						where substr(a.kd_brg,1,1) = b.kd_gol 
						and substr(a.kd_brg,2,2) = b.kd_bid 
						and substr(a.kd_brg,4,2) = b.kd_kel
						and substr(a.kd_brg,6,2) = b.kd_skel
						and substr(a.kd_brg,8,3) = b.kd_sskel
						";
				//$query = $query.$xquery;
				$hasilAkhir = $db->fetchOne($query);				
				return $hasilAkhir;
			}else{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				$query="SELECT distinct a.kd_brg, b.ur_sskel 
						FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
						where substr(a.kd_brg,1,1) = b.kd_gol
						and substr(a.kd_brg,2,2) = b.kd_bid 
						and substr(a.kd_brg,4,2) = b.kd_kel
						and substr(a.kd_brg,6,2) = b.kd_skel
						and substr(a.kd_brg,8,3) = b.kd_sskel ";
				$query = $query.$xquery." order by b.ur_sskel limit $xLimit offset $xOffset";
				$result = $db->fetchAll($query);		
				$jmlResult = count($result);
				if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {			 
						$hasilAkhir[$j] = array("kd_brg"  	=>(string)$result[$j]->kd_brg,
												"ur_sskel"  =>(string)$result[$j]->ur_sskel);
					}
					return $hasilAkhir;
	        	}
			}
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}	
	}
	/// Pengambialn Data  yg Lama  ////
	public function getDataInventarisRuanganList() 
	{
	   //echo "masuk...services getDataInventarisRuanganList"; 	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try 
	   {  	         	 
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT distinct A.c_barang,
		 				A.i_aset,
		 				A.i_ruang,		         			
		         			B.i_ruang_lokasi,
		         			C.ur_sskel,
		         			A.d_barang_peroleh,            				 
		         			D.merk_type,
		         			D.kd_pemilik			         				 				
		 FROM e_ast_dir_item_tm A, e_ast_ruangan_0_tr B, e_ast_sskel_0_tr C, e_sabm_t_master_tm D
		 where A.i_ruang = B.i_ruang
		 and d.tgl_perlh = a.d_barang_peroleh 
		 and d.kd_brg = a.c_barang
		 and d.no_aset = a.i_aset
		 and substr(a.c_barang,1,1) = c.kd_gol
		 and substr(a.c_barang,2,2) = c.kd_bid 
		 and substr(a.c_barang,4,2) = c.kd_kel
		 and substr(a.c_barang,6,2) = c.kd_skel
		 and substr(a.c_barang,8,3) = c.kd_sskel'); 
		  
         	 $jmlResult = count($result);
         	 //echo "jml Service getDataInventarisRuanganList = ".$jmlResult;
		
		 if($jmlResult > 0)
		 {
			 for ($j = 0; $j < $jmlResult; $j++) 
			 {			 
			 	
			 	//field field yg akan ditampilkan aja di gui 
	           		$dataInventarisRuangan[$j] = array("c_barang"  	=>(string)$result[$j]->c_barang,
	           					  "ur_sskel"  		=>(string)$result[$j]->ur_sskel,
	           					  "i_aset" 	 	=>(string)$result[$j]->i_aset,
	           					  "i_ruang"  		=>(string)$result[$j]->i_ruang,
	           					  "i_ruang_lokasi"	=>(string)$result[$j]->i_ruang_lokasi,
	           					  "d_barang_peroleh"  	=>(string)$result[$j]->d_barang_peroleh,
	           					  "merk_type"  		=>(string)$result[$j]->merk_type,
	           					  "kd_pemilik"  	=>(string)$result[$j]->kd_pemilik);
			 }
        	}		 
	     	return $dataInventarisRuangan;
	   } 
	   catch (Exception $e) 
	   {
         	echo $e->getMessage().'<br>';
	     	return 'gagal <br>';
	   }
	}
	
	//untuk mengeluarkan ListRuang pada waktu di klik images cari 
	public function getCariDataInventarisRuanganList($kodeRuang,$kodeBrg,$namaBrg)
	{
	   //echo "masuk services getCariDataInventarisRuanganList";
	   $namaBarang = strtoupper($namaBrg);
	   $nbrg = '%'.$namaBarang.'%';
	   $kBrg = '%'.$kodeBrg.'%';
	   $kRuang = '%'.$kodeRuang.'%';
		
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   //$kodeRuang = $kodeRuang;
	   $where[]=$kRuang;
	   $where[]=$kBrg;
	   //$where[]=$nbrg;
	   
	   try 
	   {  	         	 
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT A.c_barang,
					 				A.i_aset,
					 				A.i_ruang,		         			
					         			B.i_ruang_lokasi,
										B.n_gedung,
					         			C.ur_sskel,
					         			A.d_barang_peroleh,            				 
					         			D.merk_type,
					         			D.kd_pemilik				
									 FROM e_ast_dir_item_tm A, e_ast_ruangan_0_tr B, e_ast_sskel_0_tr C, e_sabm_t_master_tm D
									 where A.i_ruang = B.i_ruang
									 and d.tgl_perlh = a.d_barang_peroleh 
									 and d.kd_brg = a.c_barang
									 and d.no_aset = a.i_aset
									 and substr(a.c_barang,1,1) = c.kd_gol
									 and substr(a.c_barang,2,2) = c.kd_bid 
									 and substr(a.c_barang,4,2) = c.kd_kel
									 and substr(a.c_barang,6,2) = c.kd_skel
									 and substr(a.c_barang,8,3) = c.kd_sskel
									 and A.i_ruang like ?
									 and a.c_barang like ? ",$where); 
		 
		 $jmlResult = count($result);
         	// echo "jml Service cari = ".$jmlResult;
		
		 if($jmlResult > 0)
		 {
			 for ($j = 0; $j < $jmlResult; $j++) 
			 {			 
			 	//field field yg akan ditampilkan aja di gui 
	           		$dataInventarisRuangan[$j] = array("c_barang"  	=>(string)$result[$j]->c_barang,
	           					  "ur_sskel"  		=>(string)$result[$j]->ur_sskel,
	           					  "i_aset" 	 	=>(string)$result[$j]->i_aset,
	           					  "i_ruang"  		=>(string)$result[$j]->i_ruang,
	           					  "n_gedung"  		=>(string)$result[$j]->n_gedung,
	           					  "i_ruang_lokasi"	=>(string)$result[$j]->i_ruang_lokasi,
	           					  "d_barang_peroleh"  	=>(string)$result[$j]->d_barang_peroleh,
	           					  "merk_type"  		=>(string)$result[$j]->merk_type,
	           					  "kd_pemilik"  	=>(string)$result[$j]->kd_pemilik);
			 }
        	}		 
	     	return $dataInventarisRuangan;
	   } 
	   catch (Exception $e) 
	   {
         	echo $e->getMessage().'<br>';
	     	return 'gagal <br>';
	   }
	}
	
	// getCariDataInventarisRuanganList  ditambahkan like nama barang ==
	
	public function getCariDataInventarisRuanganList_Old($kodeRuang)
	{
	   //echo "masuk services getCariDataInventarisRuanganList";
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   $kodeRuang = $kodeRuang;
	   //echo "kodeRuang services=".$kodeRuang;
	   
	   try 
	   {  	         	 
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT A.c_barang,
		 				A.i_aset,
		 				A.i_ruang,		         			
		         			B.i_ruang_lokasi,
							B.n_gedung,
		         			C.ur_sskel,
		         			A.d_barang_peroleh,            				 
		         			D.merk_type,
		         			D.kd_pemilik				
		 FROM e_ast_dir_item_tm A, e_ast_ruangan_0_tr B, e_ast_sskel_0_tr C, e_sabm_t_master_tm D
		 where A.i_ruang = B.i_ruang
		 and d.tgl_perlh = a.d_barang_peroleh 
		 and d.kd_brg = a.c_barang
		 and d.no_aset = a.i_aset
		 and substr(a.c_barang,1,1) = c.kd_gol
		 and substr(a.c_barang,2,2) = c.kd_bid 
		 and substr(a.c_barang,4,2) = c.kd_kel
		 and substr(a.c_barang,6,2) = c.kd_skel
		 and substr(a.c_barang,8,3) = c.kd_sskel
		 and A.i_ruang=?',$kodeRuang); 
		 
		 $jmlResult = count($result);
         	 //echo "jml Service cari = ".$jmlResult;
		
		 if($jmlResult > 0)
		 {
			 for ($j = 0; $j < $jmlResult; $j++) 
			 {			 
			 	//field field yg akan ditampilkan aja di gui 
	           		$dataInventarisRuangan[$j] = array("c_barang"  	=>(string)$result[$j]->c_barang,
	           					  "ur_sskel"  		=>(string)$result[$j]->ur_sskel,
	           					  "i_aset" 	 	=>(string)$result[$j]->i_aset,
	           					  "i_ruang"  		=>(string)$result[$j]->i_ruang,
	           					  "n_gedung"  		=>(string)$result[$j]->n_gedung,
	           					  "i_ruang_lokasi"	=>(string)$result[$j]->i_ruang_lokasi,
	           					  "d_barang_peroleh"  	=>(string)$result[$j]->d_barang_peroleh,
	           					  "merk_type"  		=>(string)$result[$j]->merk_type,
	           					  "kd_pemilik"  	=>(string)$result[$j]->kd_pemilik);
			 }
        	}		 
	     	return $dataInventarisRuangan;
	   } 
	   catch (Exception $e) 
	   {
         	echo $e->getMessage().'<br>';
	     	return 'gagal <br>';
	   }
	}
	
	//untuk mengeluarkan list pegawai pada waktu di klik button nip 
	public function getListPegawai() 
	{
           //echo "masuk services getListPegawai";
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try 
	   {
	     	 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT A.i_peg_nip,
		 				A.n_peg,
		 				C.c_jabatan,
		 				C.n_jabatan		 
		 FROM e_sdm_pegawai_0_tm A, e_sdm_jabatan_0_tm B, e_sdm_jabatan_tr C 
		 where A.i_peg_nip = B.i_peg_nip
		 and B.c_jabatan = C.c_jabatan');
	    	 
	     	 $jmlResult = count($result);
	     	 //echo "jumlah getListPegawai =".$jmlResult;
		 
		 if($jmlResult > 0)
		 {
		 	for ($j = 0; $j < $jmlResult; $j++) 
		 	{
		 		$hasilAkhir[$j] = array("i_peg_nip"  =>(string)$result[$j]->i_peg_nip,
							"n_peg"      =>(string)$result[$j]->n_peg,
							"c_jabatan"  =>(string)$result[$j]->c_jabatan,
							"n_jabatan"  =>(string)$result[$j]->n_jabatan);
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
	
	//untuk mengeluarkan prosesrekapcetak.phtml pada waktu di klik images cetak rekap pakai kode ruang
	public function getDataRekapList1($kodeRuang)
	{
	   //echo "masuk services getDataRekapList pakai kode";
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   $kodeRuang = $kodeRuang;
	   //echo "kodeRekap services=".$kodeRuang;
	   
	   try 
	   {  	         	 
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT A.c_barang,
		 			C.ur_sskel, 
		 			D.kd_sat, 
		 			count(*) as "jumlahBarang"
		 FROM e_ast_dir_item_tm A, e_ast_ruangan_0_tr B, e_ast_sskel_0_tr C, e_sabm_t_master_tm D
		 where A.i_ruang = B.i_ruang
		 and d.tgl_perlh = a.d_barang_peroleh 
		 and d.kd_brg = a.c_barang
		 and d.no_aset = a.i_aset
		 and substr(a.c_barang,1,1) = c.kd_gol
		 and substr(a.c_barang,2,2) = c.kd_bid 
		 and substr(a.c_barang,4,2) = c.kd_kel
		 and substr(a.c_barang,6,2) = c.kd_skel
		 and substr(a.c_barang,8,3) = c.kd_sskel
		 and A.i_ruang=? group by A.c_barang,C.ur_sskel,D.kd_sat',$kodeRuang); 
		 
		 $jmlResult = count($result);
         	 //echo "jml rekap Service = ".$jmlResult;
         	 
		 if($jmlResult > 0)
		 {
			 for ($j = 0; $j < $jmlResult; $j++) 
			 {		
			 		 
			 	//field field yg akan ditampilkan aja di gui 
	           		$dataRekap[$j] = array("c_barang"  	=>(string)$result[$j]->c_barang,
	           					  "ur_sskel"  		=>(string)$result[$j]->ur_sskel,
	           					  "kd_sat"  		=>(string)$result[$j]->kd_sat,
	           					  "jumlahBarang"  	=>(string)$result[$j]->jumlahBarang);
	           					  
			 
			 }
			 //echo untuk melihat array
			 //print_r($dataRekap);
        	}		 
	     	return $dataRekap;
	   } 
	   catch (Exception $e) 
	   {
         	echo $e->getMessage().'<br>';
	     	return 'gagal <br>';
	   }
	}
		
}	
?>