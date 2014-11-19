<?php
class ast_setuju_ruangrapat_Service {   

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


	
	// fungsi Tambahan -> harusnya ambil dari Service-Lain 
	public function getNamaPegawaiRR($iPeg) {
		   $registry = Zend_Registry::getInstance();
		   $db = $registry->get('db');
		   $result="Tidak tedaftar";
		   try {
			 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 $query="SELECT n_peg ".
			 	" FROM e_sdm_pegawai_0_tm where i_peg_nip=? ";
			 $result = $db->fetchOne($query,$iPeg);
		     	 return $result;
		   } 
		   catch (Exception $e) {
		     echo $e->getMessage().'<br>';
		     return $result;
		   }						
	}

	public function getOrgRR($iOrg) {
		   $registry = Zend_Registry::getInstance();
		   $db = $registry->get('db');
		   $result=0;
		   try {
			 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 $query="SELECT n_orgb ".
			 	" FROM e_org_0_0_tm where i_orgb=? ";
			 $result = $db->fetchOne($query,$iOrg);
		     	 return $result;
		   } 
		   catch (Exception $e) {
		     echo $e->getMessage().'<br>';
		     return $result;
		   }						
	}

	
	// fungsi Utama Persetujuan Ruang Rapat
	
	public function geAvailtRRList() {
		   $registry = Zend_Registry::getInstance();
		   $db = $registry->get('db');
		   try {
			 $where[] ='2';
			 $where[] ='3';
			 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 $query="SELECT i_ruang,i_ruang_lokasi,n_gedung,q_gedung_lantai,q_gedung_kapasitas,
			 	q_gedung_luas,c_gedung_fungsi,i_orgb_tgjwbgedung
			 	FROM e_ast_ruangan_0_tr 
				where (c_gedung_fungsi = ? or c_gedung_fungsi = ?)				
				ORDER BY i_ruang";
			 $result = $db->fetchAll($query,$where);
			 $jmlResult = count($result);		 
			 for ($j = 0; $j < $jmlResult; $j++) {		 
				$hasilAkhir[$j] = array("i_ruang"=>(string)$result[$j]->i_ruang,
		                               "i_ruang_lokasi"           =>(string)$result[$j]->i_ruang_lokasi,
		                               "n_gedung"                 =>(string)$result[$j]->n_gedung,
		                               "q_gedung_lantai"          =>(string)$result[$j]->q_gedung_lantai,
		                               "q_gedung_kapasitas"       =>(string)$result[$j]->q_gedung_kapasitas,
		                               "q_gedung_luas"            =>(string)$result[$j]->q_gedung_luas,
		                               "c_gedung_fungsi"          =>(string)$result[$j]->c_gedung_fungsi,
		                               "i_orgb_tgjwbgedung"       =>(string)$result[$j]->i_orgb_tgjwbgedung);			
			 }					 
		     return $hasilAkhir;
		   } 
		   catch (Exception $e) {
		     echo $e->getMessage().'<br>';
		     return 'gagal <br>';
		   }						
	}

	public function getSearchPersetujuanRTList($tgl,$bln,$thn) {        
		//echo "<br> Tanggal : ".$tgl."-".$bln."-".$thn."<br>";
		if ($tgl=='#'){
			if ($bln != '#'){
				$where = "%".$thn."-".$bln."%";
				$query1=" and d_rapat_pesan like ? ";
			}else{
				$where = "%".$thn."%";
				$query1=" and d_rapat_pesan like ? ";			
			}
		}else{
			if ($bln != '#' && $thn >='1970'){
				$where = $thn."-".$bln."-".$tgl;
				$query1=" and d_rapat_pesan = ? ";						
			}else if($bln == '#'){//$thn<='1970' || $thn=''
				$where = "%-".$tgl;
				$query1=" and d_rapat_pesan like ? ";									
			}else{
				$where = "%-".$bln."-".$tgl;
				$query1=" and d_rapat_pesan like ? ";
				}						
		}
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$query="SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,A.i_orgb,n_rapat_judul,q_rapat_peserta,i_rapat_mailtgjwb,i_rapat_telptgjwb,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai " .
					" ,i_ruang,c_rapat_konsumsipagi,c_rapat_konsumsisiang,c_rapat_makansiang,c_rapat_tipemakansiang,c_rapat_makanmalam,c_rapat_tipemakanmalam,i_rapat_nippimpin,i_rapat_nippesan,c_rapat_statsetuju " .
					" ,c_rapat_statsetuju1,d_rapat_usul,d_rapat_usuljamawal,d_rapat_usuljamakh,c_rapat_statjwbusul,d_rapat_jwbusul,A.i_entry,A.d_entry,'n_peg','n_orgb'" .
					" FROM e_ast_ajuan_pakai_ruang_tm A" .
					" where (c_rapat_statsetuju isnull or c_rapat_statsetuju='') "; //,e_sdm_pegawai_0_tm B,e_org_0_0_tm C   i_peg_nip=i_rapat_nippesan and B.i_orgb=C.i_orgb and
			$query=$query.$query1." ORDER BY i_ruang_ajuanpakai ";					
			//echo "<br>Querynya :".$query."<br>";
			$result = $db->fetchAll($query,$where);
			$jmlResult = count($result);
			if($jmlResult > 0){
	 			for ($j = 0; $j < $jmlResult; $j++) {
					$sdm = new ast_setuju_ruangrapat_Service();
					$iPeg=$result[$j]->i_rapat_nippesan;
	 				$iOrg=$result[$j]->i_orgb;
	 				$namaPeg = $sdm->getNamaPegawaiRR($iPeg);
	 				$namaOrg = $sdm->getOrgRR($iOrg);
					$hasilAkhir[$j] = array("i_ruang_ajuanpakai"=>(string)$result[$j]->i_ruang_ajuanpakai,
						"d_ruang_ajuanpakai"=>(string)$result[$j]->d_ruang_ajuanpakai,
						"i_orgb"=>(string)$result[$j]->i_orgb,
						"n_rapat_judul"=>(string)$result[$j]->n_rapat_judul,
						"n_atk_tipe"=>(string)$result[$j]->n_atk_tipe,
						"q_rapat_peserta"=>(string)$result[$j]->q_rapat_peserta,
						"i_rapat_mailtgjwb"=>(string)$result[$j]->i_rapat_mailtgjwb,
						"i_rapat_telptgjwb"=>(string)$result[$j]->i_rapat_telptgjwb,
						"d_rapat_pesan"=>(string)$result[$j]->d_rapat_pesan,
						"d_rapat_awalpakai"=>(string)$result[$j]->d_rapat_awalpakai,
						"d_rapat_akhirpakai"=>(string)$result[$j]->d_rapat_akhirpakai,

						"i_ruang"=>(string)$result[$j]->i_ruang,
						"c_rapat_konsumsipagi"=>(string)$result[$j]->c_rapat_konsumsipagi,
						"c_rapat_konsumsisiang"=>(string)$result[$j]->c_rapat_konsumsisiang,
						"c_rapat_makansiang"=>(string)$result[$j]->c_rapat_makansiang,
						"c_rapat_tipemakansiang"=>(string)$result[$j]->c_rapat_tipemakansiang,
						"c_rapat_makanmalam"=>(string)$result[$j]->c_rapat_makanmalam,
						"c_rapat_tipemakanmalam"=>(string)$result[$j]->c_rapat_tipemakanmalam,
						"i_rapat_nippimpin"=>(string)$result[$j]->i_rapat_nippimpin,
						"i_rapat_nippesan"=>(string)$result[$j]->i_rapat_nippesan,
						"c_rapat_statsetuju"=>(string)$result[$j]->c_rapat_statsetuju,
						
						"c_rapat_statsetuju1"=>(string)$result[$j]->c_rapat_statsetuju1,
						"d_rapat_usul"=>(string)$result[$j]->d_rapat_usul,
						"d_rapat_usuljamawal"=>(string)$result[$j]->d_rapat_usuljamawal,
						"d_rapat_usuljamakh"=>(string)$result[$j]->d_rapat_usuljamakh,
						"c_rapat_statjwbusul"=>(string)$result[$j]->c_rapat_statjwbusul,
						"d_rapat_jwbusul"=>(string)$result[$j]->d_rapat_jwbusul,
						"i_entry"=>(string)$result[$j]->i_entry,
						"d_entry"=>(string)$result[$j]->d_entry,
						//"n_peg"=>(string)$result[$j]->n_peg,
						//"n_orgb"=>(string)$result[$j]->n_orgb
						"n_peg"=>$namaPeg,
						"n_orgb"=>$namaOrg
						);
	 			}
			}
			return $hasilAkhir;
		}
		catch (Exception $e){
	    		echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}	

	public function getAllPersetujuanRTRuangRapatList() {        
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$query="SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,A.i_orgb,n_rapat_judul,q_rapat_peserta,i_rapat_mailtgjwb,i_rapat_telptgjwb,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai " .
					" ,i_ruang,c_rapat_konsumsipagi,c_rapat_konsumsisiang,c_rapat_makansiang,c_rapat_tipemakansiang,c_rapat_makanmalam,c_rapat_tipemakanmalam,i_rapat_nippimpin,i_rapat_nippesan,c_rapat_statsetuju " .
					" ,c_rapat_statsetuju1,d_rapat_usul,d_rapat_usuljamawal,d_rapat_usuljamakh,c_rapat_statjwbusul,d_rapat_jwbusul,A.i_entry,A.d_entry,'Peg','UNIT'" .
					" FROM e_ast_ajuan_pakai_ruang_tm A" .
					" where (c_rapat_statsetuju isnull or c_rapat_statsetuju='') ORDER BY i_ruang_ajuanpakai "; 

			//echo "<br>Querynya :".$query."<br>";
			$result = $db->fetchAll($query);
			$jmlResult = count($result);
			if($jmlResult > 0){
	 			for ($j = 0; $j < $jmlResult; $j++) {
	 				$sdm = new ast_setuju_ruangrapat_Service();
	 				$iPeg=$result[$j]->i_rapat_nippesan;
	 				$iOrg=$result[$j]->i_orgb;
	 				$namaPeg = $sdm->getNamaPegawaiRR($iPeg);
	 				$namaOrg = $sdm->getOrgRR($iOrg);
					$hasilAkhir[$j] = array("i_ruang_ajuanpakai"=>(string)$result[$j]->i_ruang_ajuanpakai,
						"d_ruang_ajuanpakai"=>(string)$result[$j]->d_ruang_ajuanpakai,
						"i_orgb"=>(string)$result[$j]->i_orgb,
						"n_rapat_judul"=>(string)$result[$j]->n_rapat_judul,
						"n_atk_tipe"=>(string)$result[$j]->n_atk_tipe,
						"q_rapat_peserta"=>(string)$result[$j]->q_rapat_peserta,
						"i_rapat_mailtgjwb"=>(string)$result[$j]->i_rapat_mailtgjwb,
						"i_rapat_telptgjwb"=>(string)$result[$j]->i_rapat_telptgjwb,
						"d_rapat_pesan"=>(string)$result[$j]->d_rapat_pesan,
						"d_rapat_awalpakai"=>(string)$result[$j]->d_rapat_awalpakai,
						"d_rapat_akhirpakai"=>(string)$result[$j]->d_rapat_akhirpakai,

						"i_ruang"=>(string)$result[$j]->i_ruang,
						"c_rapat_konsumsipagi"=>(string)$result[$j]->c_rapat_konsumsipagi,
						"c_rapat_konsumsisiang"=>(string)$result[$j]->c_rapat_konsumsisiang,
						"c_rapat_makansiang"=>(string)$result[$j]->c_rapat_makansiang,
						"c_rapat_tipemakansiang"=>(string)$result[$j]->c_rapat_tipemakansiang,
						"c_rapat_makanmalam"=>(string)$result[$j]->c_rapat_makanmalam,
						"c_rapat_tipemakanmalam"=>(string)$result[$j]->c_rapat_tipemakanmalam,
						"i_rapat_nippimpin"=>(string)$result[$j]->i_rapat_nippimpin,
						"i_rapat_nippesan"=>(string)$result[$j]->i_rapat_nippesan,
						"c_rapat_statsetuju"=>(string)$result[$j]->c_rapat_statsetuju,
						
						"c_rapat_statsetuju1"=>(string)$result[$j]->c_rapat_statsetuju1,
						"d_rapat_usul"=>(string)$result[$j]->d_rapat_usul,
						"d_rapat_usuljamawal"=>(string)$result[$j]->d_rapat_usuljamawal,
						"d_rapat_usuljamakh"=>(string)$result[$j]->d_rapat_usuljamakh,
						"c_rapat_statjwbusul"=>(string)$result[$j]->c_rapat_statjwbusul,
						"d_rapat_jwbusul"=>(string)$result[$j]->d_rapat_jwbusul,
						"i_entry"=>(string)$result[$j]->i_entry,
						"d_entry"=>(string)$result[$j]->d_entry,
						"n_peg" => $namaPeg,
						"n_orgb"=>$namaOrg
						);
	 			}
			}
			return $hasilAkhir;
		}
		catch (Exception $e){
	    		echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}	

	//======================================ubah service ke paging & TU ============================PR===>>
	public function querySetujuTuRuangM($pageNumber, $itemPerPage,$unitkr) {
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			
			 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 	
				// Ina : 19-05-2008 
				// Cek apakah yg login TU atau RT
				// Jika TU, maka ambil pengajuan dimana penanggungjawab ruangan yg dipesan = UnitKerjaTU
				$TU 	= $db->fetchCol("SELECT i_orgb_tu FROM e_org_0_0_tm where i_orgb = ? and i_orgb_tu is not null",$unitkr);
				$unitTU = $TU[0]; 
				
				 if($unitTU !=''){    /// Pengajuan Ruang Rapat Deputi
					$where[] = $unitkr;
					
					 if(($pageNumber==0) && ($itemPerPage==0))
					 {
						$hasilAkhir = $db->fetchOne("Select count(*)
								FROM e_ast_ajuan_pakai_ruang_tm A, e_ast_ruangan_0_tr B
								where  (c_rapat_statsetuju isnull or c_rapat_statsetuju='')
								and a.i_ruang = b.i_ruang
								and b.c_gedung_fungsi = '3'
								and b.i_orgb_tgjwbgedung = ?", $where); 
					 }
					 else
					 {
				
						 $xLimit=$itemPerPage;
						 $xOffset=($pageNumber-1)*$itemPerPage;		
						 
						 $result = $db->fetchAll("SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,A.i_orgb,n_rapat_judul,q_rapat_peserta,i_rapat_mailtgjwb,i_rapat_telptgjwb,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai, 
											    a.i_ruang,c_rapat_konsumsipagi,c_rapat_konsumsisiang,c_rapat_makansiang,c_rapat_tipemakansiang,c_rapat_makanmalam,c_rapat_tipemakanmalam,i_rapat_nippimpin,i_rapat_nippesan,c_rapat_statsetuju, 
											    c_rapat_statsetuju1,d_rapat_usul,d_rapat_usuljamawal,d_rapat_usuljamakh,c_rapat_statjwbusul,d_rapat_jwbusul,A.i_entry,A.d_entry,'Peg','UNIT'
											 	FROM e_ast_ajuan_pakai_ruang_tm A, e_ast_ruangan_0_tr B
												where  (c_rapat_statsetuju isnull or c_rapat_statsetuju='')
												and a.i_ruang = b.i_ruang
												and b.i_orgb_tgjwbgedung = ?
												and b.c_gedung_fungsi = '3'
												ORDER BY d_rapat_pesan,a.i_ruang, d_rapat_awalpakai,d_rapat_akhirpakai limit $xLimit offset $xOffset",$where); 
						
						 $jmlResult = count($result);
						 if($jmlResult > 0){
				 			for ($j = 0; $j < $jmlResult; $j++) {
				 				$sdm = new ast_setuju_ruangrapat_Service();
				 				$iPeg=$result[$j]->i_rapat_nippesan;
				 				$iOrg=$result[$j]->i_orgb;
				 				$namaPeg = $sdm->getNamaPegawaiRR($iPeg);
				 				$namaOrg = $sdm->getOrgRR($iOrg);
								$hasilAkhir[$j] = array("i_ruang_ajuanpakai"	=>(string)$result[$j]->i_ruang_ajuanpakai,
														"d_ruang_ajuanpakai"	=>(string)$result[$j]->d_ruang_ajuanpakai,
														"i_orgb"				=>(string)$result[$j]->i_orgb,
														"n_rapat_judul"			=>(string)$result[$j]->n_rapat_judul,
														"n_atk_tipe"			=>(string)$result[$j]->n_atk_tipe,
														"q_rapat_peserta"		=>(string)$result[$j]->q_rapat_peserta,
														"i_rapat_mailtgjwb"		=>(string)$result[$j]->i_rapat_mailtgjwb,
														"i_rapat_telptgjwb"		=>(string)$result[$j]->i_rapat_telptgjwb,
														"d_rapat_pesan"			=>(string)$result[$j]->d_rapat_pesan,
														"d_rapat_awalpakai"		=>(string)$result[$j]->d_rapat_awalpakai,
														"d_rapat_akhirpakai"	=>(string)$result[$j]->d_rapat_akhirpakai,

														"i_ruang"				=>(string)$result[$j]->i_ruang,
														"c_rapat_konsumsipagi"	=>(string)$result[$j]->c_rapat_konsumsipagi,
														"c_rapat_konsumsisiang"	=>(string)$result[$j]->c_rapat_konsumsisiang,
														"c_rapat_makansiang"	=>(string)$result[$j]->c_rapat_makansiang,
														"c_rapat_tipemakansiang"=>(string)$result[$j]->c_rapat_tipemakansiang,
														"c_rapat_makanmalam"	=>(string)$result[$j]->c_rapat_makanmalam,
														"c_rapat_tipemakanmalam"=>(string)$result[$j]->c_rapat_tipemakanmalam,
														"i_rapat_nippimpin"		=>(string)$result[$j]->i_rapat_nippimpin,
														"i_rapat_nippesan"		=>(string)$result[$j]->i_rapat_nippesan,
														"c_rapat_statsetuju"	=>(string)$result[$j]->c_rapat_statsetuju,
														
														"c_rapat_statsetuju1"	=>(string)$result[$j]->c_rapat_statsetuju1,
														"d_rapat_usul"			=>(string)$result[$j]->d_rapat_usul,
														"d_rapat_usuljamawal"	=>(string)$result[$j]->d_rapat_usuljamawal,
														"d_rapat_usuljamakh"	=>(string)$result[$j]->d_rapat_usuljamakh,
														"c_rapat_statjwbusul"	=>(string)$result[$j]->c_rapat_statjwbusul,
														"d_rapat_jwbusul"		=>(string)$result[$j]->d_rapat_jwbusul,
														"i_entry"				=>(string)$result[$j]->i_entry,
														"d_entry"				=>(string)$result[$j]->d_entry,
														"n_peg" 				=> $namaPeg,
														"n_orgb"				=>$namaOrg
										);
								}
						}					
				
			       }
				} else   /// Pengajuan Ruang Rapat Umum
				{					
					 if(($pageNumber==0) && ($itemPerPage==0))
					 {
						        $hasilAkhir = $db->fetchOne("Select count(*)
									FROM e_ast_ajuan_pakai_ruang_tm A, e_ast_ruangan_0_tr B
									where  (c_rapat_statsetuju isnull or c_rapat_statsetuju='')
									and a.i_ruang = b.i_ruang
									and b.c_gedung_fungsi = '2'"); 
													 
					 }
					 else
					 {				
						 $xLimit=$itemPerPage;
						 $xOffset=($pageNumber-1)*$itemPerPage;		
						 
						 $result = $db->fetchAll("SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,A.i_orgb,n_rapat_judul,q_rapat_peserta,i_rapat_mailtgjwb,i_rapat_telptgjwb,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai ,
												a.i_ruang,c_rapat_konsumsipagi,c_rapat_konsumsisiang,c_rapat_makansiang,c_rapat_tipemakansiang,c_rapat_makanmalam,c_rapat_tipemakanmalam,i_rapat_nippimpin,i_rapat_nippesan,c_rapat_statsetuju,
												c_rapat_statsetuju1,d_rapat_usul,d_rapat_usuljamawal,d_rapat_usuljamakh,c_rapat_statjwbusul,d_rapat_jwbusul,A.i_entry,A.d_entry,'Peg','UNIT'
												FROM e_ast_ajuan_pakai_ruang_tm A, e_ast_ruangan_0_tr B
												where  (c_rapat_statsetuju isnull or c_rapat_statsetuju='')
												and a.i_ruang = b.i_ruang
												and b.c_gedung_fungsi = '2'
												ORDER BY d_rapat_pesan,a.i_ruang, d_rapat_awalpakai,d_rapat_akhirpakai
												limit $xLimit offset $xOffset"); 
						
						 $jmlResult = count($result);
						 if($jmlResult > 0){
				 			for ($j = 0; $j < $jmlResult; $j++) {
				 				$sdm = new ast_setuju_ruangrapat_Service();
				 				$iPeg=$result[$j]->i_rapat_nippesan;
				 				$iOrg=$result[$j]->i_orgb;
				 				$namaPeg = $sdm->getNamaPegawaiRR($iPeg);
				 				$namaOrg = $sdm->getOrgRR($iOrg);
								$hasilAkhir[$j] = array("i_ruang_ajuanpakai"	=>(string)$result[$j]->i_ruang_ajuanpakai,
														"d_ruang_ajuanpakai"	=>(string)$result[$j]->d_ruang_ajuanpakai,
														"i_orgb"				=>(string)$result[$j]->i_orgb,
														"n_rapat_judul"			=>(string)$result[$j]->n_rapat_judul,
														"n_atk_tipe"			=>(string)$result[$j]->n_atk_tipe,
														"q_rapat_peserta"		=>(string)$result[$j]->q_rapat_peserta,
														"i_rapat_mailtgjwb"		=>(string)$result[$j]->i_rapat_mailtgjwb,
														"i_rapat_telptgjwb"		=>(string)$result[$j]->i_rapat_telptgjwb,
														"d_rapat_pesan"			=>(string)$result[$j]->d_rapat_pesan,
														"d_rapat_awalpakai"		=>(string)$result[$j]->d_rapat_awalpakai,
														"d_rapat_akhirpakai"	=>(string)$result[$j]->d_rapat_akhirpakai,
														"i_ruang"				=>(string)$result[$j]->i_ruang,
														"c_rapat_konsumsipagi"	=>(string)$result[$j]->c_rapat_konsumsipagi,
														"c_rapat_konsumsisiang"	=>(string)$result[$j]->c_rapat_konsumsisiang,
														"c_rapat_makansiang"	=>(string)$result[$j]->c_rapat_makansiang,
														"c_rapat_tipemakansiang"=>(string)$result[$j]->c_rapat_tipemakansiang,
														"c_rapat_makanmalam"	=>(string)$result[$j]->c_rapat_makanmalam,
														"c_rapat_tipemakanmalam"=>(string)$result[$j]->c_rapat_tipemakanmalam,
														"i_rapat_nippimpin"		=>(string)$result[$j]->i_rapat_nippimpin,
														"i_rapat_nippesan"		=>(string)$result[$j]->i_rapat_nippesan,
														"c_rapat_statsetuju"	=>(string)$result[$j]->c_rapat_statsetuju,
														
														"c_rapat_statsetuju1"	=>(string)$result[$j]->c_rapat_statsetuju1,
														"d_rapat_usul"			=>(string)$result[$j]->d_rapat_usul,
														"d_rapat_usuljamawal"	=>(string)$result[$j]->d_rapat_usuljamawal,
														"d_rapat_usuljamakh"	=>(string)$result[$j]->d_rapat_usuljamakh,
														"c_rapat_statjwbusul"	=>(string)$result[$j]->c_rapat_statjwbusul,
														"d_rapat_jwbusul"		=>(string)$result[$j]->d_rapat_jwbusul,
														"i_entry"				=>(string)$result[$j]->i_entry,
														"d_entry"				=>(string)$result[$j]->d_entry,
														"n_peg" 				=> $namaPeg,
														"n_orgb"				=>$namaOrg
										);
								}
						}
					}
				}
						return $hasilAkhir;
			/* }else{
				return 0;
			} */
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	
	
	public function querySetujuTuRuangByTgl($pageNumber, $itemPerPage,$unitkr,$tglPesan) {
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			
			 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 	
				/* $TU 	= $db->fetchCol("SELECT i_orgb_tu FROM e_org_0_0_tm where i_orgb = ? and i_orgb_tu is not null",$unitkr);
				$unitTU = $TU[0];
				
				if($unitTU !=''){
					if (substr($unitTU,0,2) == 'DP' ){
			               $unitTULike = substr($unitTU,0,3).'%';
			        }else{
			               $unitTULike = substr($unitTU,0,2).'%';
			        } */
					
					//$where[] = $unitTULike;
					$where[] = $tglPesan;
					
					 if(($pageNumber==0) && ($itemPerPage==0))
					 {
						$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_ajuan_pakai_ruang_tm 
													 where (c_rapat_statsetuju isnull or c_rapat_statsetuju='')
													 and d_rapat_pesan like ? ", $where);
					 }
					 else
					 {
				
						 $xLimit=$itemPerPage;
						 $xOffset=($pageNumber-1)*$itemPerPage;		
						 
						 $result = $db->fetchAll("SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,A.i_orgb,n_rapat_judul,q_rapat_peserta,i_rapat_mailtgjwb,i_rapat_telptgjwb,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai " .
													" ,i_ruang,c_rapat_konsumsipagi,c_rapat_konsumsisiang,c_rapat_makansiang,c_rapat_tipemakansiang,c_rapat_makanmalam,c_rapat_tipemakanmalam,i_rapat_nippimpin,i_rapat_nippesan,c_rapat_statsetuju " .
													" ,c_rapat_statsetuju1,d_rapat_usul,d_rapat_usuljamawal,d_rapat_usuljamakh,c_rapat_statjwbusul,d_rapat_jwbusul,A.i_entry,A.d_entry,'Peg','UNIT'" .
													" FROM e_ast_ajuan_pakai_ruang_tm A" .
													" where (c_rapat_statsetuju isnull or c_rapat_statsetuju='')  and d_rapat_pesan like ?
													ORDER BY i_ruang_ajuanpakai
													limit $xLimit offset $xOffset", $where); 
						
						 $jmlResult = count($result);
						 if($jmlResult > 0){
				 			for ($j = 0; $j < $jmlResult; $j++) {
				 				$sdm = new ast_setuju_ruangrapat_Service();
				 				$iPeg=$result[$j]->i_rapat_nippesan;
				 				$iOrg=$result[$j]->i_orgb;
				 				$namaPeg = $sdm->getNamaPegawaiRR($iPeg);
				 				$namaOrg = $sdm->getOrgRR($iOrg);
								$hasilAkhir[$j] = array("i_ruang_ajuanpakai"	=>(string)$result[$j]->i_ruang_ajuanpakai,
														"d_ruang_ajuanpakai"	=>(string)$result[$j]->d_ruang_ajuanpakai,
														"i_orgb"				=>(string)$result[$j]->i_orgb,
														"n_rapat_judul"			=>(string)$result[$j]->n_rapat_judul,
														"n_atk_tipe"			=>(string)$result[$j]->n_atk_tipe,
														"q_rapat_peserta"		=>(string)$result[$j]->q_rapat_peserta,
														"i_rapat_mailtgjwb"		=>(string)$result[$j]->i_rapat_mailtgjwb,
														"i_rapat_telptgjwb"		=>(string)$result[$j]->i_rapat_telptgjwb,
														"d_rapat_pesan"			=>(string)$result[$j]->d_rapat_pesan,
														"d_rapat_awalpakai"		=>(string)$result[$j]->d_rapat_awalpakai,
														"d_rapat_akhirpakai"	=>(string)$result[$j]->d_rapat_akhirpakai,

														"i_ruang"				=>(string)$result[$j]->i_ruang,
														"c_rapat_konsumsipagi"	=>(string)$result[$j]->c_rapat_konsumsipagi,
														"c_rapat_konsumsisiang"	=>(string)$result[$j]->c_rapat_konsumsisiang,
														"c_rapat_makansiang"	=>(string)$result[$j]->c_rapat_makansiang,
														"c_rapat_tipemakansiang"=>(string)$result[$j]->c_rapat_tipemakansiang,
														"c_rapat_makanmalam"	=>(string)$result[$j]->c_rapat_makanmalam,
														"c_rapat_tipemakanmalam"=>(string)$result[$j]->c_rapat_tipemakanmalam,
														"i_rapat_nippimpin"		=>(string)$result[$j]->i_rapat_nippimpin,
														"i_rapat_nippesan"		=>(string)$result[$j]->i_rapat_nippesan,
														"c_rapat_statsetuju"	=>(string)$result[$j]->c_rapat_statsetuju,
														
														"c_rapat_statsetuju1"	=>(string)$result[$j]->c_rapat_statsetuju1,
														"d_rapat_usul"			=>(string)$result[$j]->d_rapat_usul,
														"d_rapat_usuljamawal"	=>(string)$result[$j]->d_rapat_usuljamawal,
														"d_rapat_usuljamakh"	=>(string)$result[$j]->d_rapat_usuljamakh,
														"c_rapat_statjwbusul"	=>(string)$result[$j]->c_rapat_statjwbusul,
														"d_rapat_jwbusul"		=>(string)$result[$j]->d_rapat_jwbusul,
														"i_entry"				=>(string)$result[$j]->i_entry,
														"d_entry"				=>(string)$result[$j]->d_entry,
														"n_peg" 				=> $namaPeg,
														"n_orgb"				=>$namaOrg
										);
								}
						}
					}
						return $hasilAkhir;
			/* }else{
				return 0;
			} */
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	
	//=================================================================================================

	public function getPersetujuanRT_RuangRapatByNo($nopeng) {        
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$query="SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,A.i_orgb,n_rapat_judul,q_rapat_peserta,i_rapat_mailtgjwb,i_rapat_telptgjwb,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai " .
					" ,i_ruang,c_rapat_konsumsipagi,c_rapat_konsumsisiang,c_rapat_makansiang,c_rapat_tipemakansiang,c_rapat_makanmalam,c_rapat_tipemakanmalam,i_rapat_nippimpin,i_rapat_nippesan,c_rapat_statsetuju " .
					" ,c_rapat_statsetuju1,d_rapat_usul,d_rapat_usuljamawal,d_rapat_usuljamakh,c_rapat_statjwbusul,d_rapat_jwbusul,A.i_entry,A.d_entry,'n_peg','n_orgb'" .
					" FROM e_ast_ajuan_pakai_ruang_tm A" .
					" where i_ruang_ajuanpakai=? and (c_rapat_statsetuju isnull or c_rapat_statsetuju='') ORDER BY i_ruang_ajuanpakai "; 
			$result = $db->fetchAll($query,$nopeng);
			$jmlResult = count($result);
			if($jmlResult > 0){
	 			for ($j = 0; $j < $jmlResult; $j++) {
	 				$sdm = new ast_setuju_ruangrapat_Service();
	 				$iPeg=$result[$j]->i_rapat_nippesan;
	 				$iOrg=$result[$j]->i_orgb;
	 				$namaPeg = $sdm->getNamaPegawaiRR($iPeg);
	 				$namaOrg = $sdm->getOrgRR($iOrg);
					$hasilAkhir[$j] = array("i_ruang_ajuanpakai"=>(string)$result[$j]->i_ruang_ajuanpakai,
						"d_ruang_ajuanpakai"=>(string)$result[$j]->d_ruang_ajuanpakai,
						"i_orgb"=>(string)$result[$j]->i_orgb,
						"n_rapat_judul"=>(string)$result[$j]->n_rapat_judul,
						"n_atk_tipe"=>(string)$result[$j]->n_atk_tipe,
						"q_rapat_peserta"=>(string)$result[$j]->q_rapat_peserta,
						"i_rapat_mailtgjwb"=>(string)$result[$j]->i_rapat_mailtgjwb,
						"i_rapat_telptgjwb"=>(string)$result[$j]->i_rapat_telptgjwb,
						"d_rapat_pesan"=>(string)$result[$j]->d_rapat_pesan,
						"d_rapat_awalpakai"=>(string)$result[$j]->d_rapat_awalpakai,
						"d_rapat_akhirpakai"=>(string)$result[$j]->d_rapat_akhirpakai,

						"i_ruang"=>(string)$result[$j]->i_ruang,
						"c_rapat_konsumsipagi"=>(string)$result[$j]->c_rapat_konsumsipagi,
						"c_rapat_konsumsisiang"=>(string)$result[$j]->c_rapat_konsumsisiang,
						"c_rapat_makansiang"=>(string)$result[$j]->c_rapat_makansiang,
						"c_rapat_tipemakansiang"=>(string)$result[$j]->c_rapat_tipemakansiang,
						"c_rapat_makanmalam"=>(string)$result[$j]->c_rapat_makanmalam,
						"c_rapat_tipemakanmalam"=>(string)$result[$j]->c_rapat_tipemakanmalam,
						"i_rapat_nippimpin"=>(string)$result[$j]->i_rapat_nippimpin,
						"i_rapat_nippesan"=>(string)$result[$j]->i_rapat_nippesan,
						"c_rapat_statsetuju"=>(string)$result[$j]->c_rapat_statsetuju,
						
						"c_rapat_statsetuju1"=>(string)$result[$j]->c_rapat_statsetuju1,
						"d_rapat_usul"=>(string)$result[$j]->d_rapat_usul,
						"d_rapat_usuljamawal"=>(string)$result[$j]->d_rapat_usuljamawal,
						"d_rapat_usuljamakh"=>(string)$result[$j]->d_rapat_usuljamakh,
						"c_rapat_statjwbusul"=>(string)$result[$j]->c_rapat_statjwbusul,
						"d_rapat_jwbusul"=>(string)$result[$j]->d_rapat_jwbusul,
						"i_entry"=>(string)$result[$j]->i_entry,
						"d_entry"=>(string)$result[$j]->d_entry,
						"n_peg" => $namaPeg,
						"n_orgb"=>$namaOrg
						);
	 			}
			}
			return $hasilAkhir;
		}
		catch (Exception $e){
	    		echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}	

	public function getPersetujuanRT_RuangRapat($tglPenggunaan) {        
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$query="SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,A.i_orgb,n_rapat_judul,q_rapat_peserta,i_rapat_mailtgjwb,i_rapat_telptgjwb,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai " .
					" ,i_ruang,c_rapat_konsumsipagi,c_rapat_konsumsisiang,c_rapat_makansiang,c_rapat_tipemakansiang,c_rapat_makanmalam,c_rapat_tipemakanmalam,i_rapat_nippimpin,i_rapat_nippesan,c_rapat_statsetuju " .
					" ,c_rapat_statsetuju1,d_rapat_usul,d_rapat_usuljamawal,d_rapat_usuljamakh,c_rapat_statjwbusul,d_rapat_jwbusul,A.i_entry,A.d_entry,'n_peg','n_orgb'" .
					" FROM e_ast_ajuan_pakai_ruang_tm A" .
					" where  d_rapat_pesan=? and (c_rapat_statsetuju isnull or c_rapat_statsetuju='') ORDER BY i_ruang_ajuanpakai "; 
			$result = $db->fetchAll($query,$tglPenggunaan);
			$jmlResult = count($result);
			if($jmlResult > 0){
	 			for ($j = 0; $j < $jmlResult; $j++) {
	 				$sdm = new ast_setuju_ruangrapat_Service();
	 				$iPeg=$result[$j]->i_rapat_nippesan;
	 				$iOrg=$result[$j]->i_orgb;
	 				$namaPeg = $sdm->getNamaPegawaiRR($iPeg);
	 				$namaOrg = $sdm->getOrgRR($iOrg);
					$hasilAkhir[$j] = array("i_ruang_ajuanpakai"=>(string)$result[$j]->i_ruang_ajuanpakai,
						"d_ruang_ajuanpakai"=>(string)$result[$j]->d_ruang_ajuanpakai,
						"i_orgb"=>(string)$result[$j]->i_orgb,
						"n_rapat_judul"=>(string)$result[$j]->n_rapat_judul,
						"n_atk_tipe"=>(string)$result[$j]->n_atk_tipe,
						"q_rapat_peserta"=>(string)$result[$j]->q_rapat_peserta,
						"i_rapat_mailtgjwb"=>(string)$result[$j]->i_rapat_mailtgjwb,
						"i_rapat_telptgjwb"=>(string)$result[$j]->i_rapat_telptgjwb,
						"d_rapat_pesan"=>(string)$result[$j]->d_rapat_pesan,
						"d_rapat_awalpakai"=>(string)$result[$j]->d_rapat_awalpakai,
						"d_rapat_akhirpakai"=>(string)$result[$j]->d_rapat_akhirpakai,

						"i_ruang"=>(string)$result[$j]->i_ruang,
						"c_rapat_konsumsipagi"=>(string)$result[$j]->c_rapat_konsumsipagi,
						"c_rapat_konsumsisiang"=>(string)$result[$j]->c_rapat_konsumsisiang,
						"c_rapat_makansiang"=>(string)$result[$j]->c_rapat_makansiang,
						"c_rapat_tipemakansiang"=>(string)$result[$j]->c_rapat_tipemakansiang,
						"c_rapat_makanmalam"=>(string)$result[$j]->c_rapat_makanmalam,
						"c_rapat_tipemakanmalam"=>(string)$result[$j]->c_rapat_tipemakanmalam,
						"i_rapat_nippimpin"=>(string)$result[$j]->i_rapat_nippimpin,
						"i_rapat_nippesan"=>(string)$result[$j]->i_rapat_nippesan,
						"c_rapat_statsetuju"=>(string)$result[$j]->c_rapat_statsetuju,
						
						"c_rapat_statsetuju1"=>(string)$result[$j]->c_rapat_statsetuju1,
						"d_rapat_usul"=>(string)$result[$j]->d_rapat_usul,
						"d_rapat_usuljamawal"=>(string)$result[$j]->d_rapat_usuljamawal,
						"d_rapat_usuljamakh"=>(string)$result[$j]->d_rapat_usuljamakh,
						"c_rapat_statjwbusul"=>(string)$result[$j]->c_rapat_statjwbusul,
						"d_rapat_jwbusul"=>(string)$result[$j]->d_rapat_jwbusul,
						"i_entry"=>(string)$result[$j]->i_entry,
						"d_entry"=>(string)$result[$j]->d_entry,
						"n_peg" => $namaPeg,
						"n_orgb"=>$namaOrg
						);
	 			}
			}
			return $hasilAkhir;
		}
		catch (Exception $e){
	    		echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}	
	
	public function getAllPersetujuanRRKabagList($pageNumber, $itemPerPage,$unitkr) {
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			//$where[] = $tglPesan;
					
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$query="SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,A.i_orgb,n_rapat_judul,q_rapat_peserta,i_rapat_mailtgjwb,i_rapat_telptgjwb,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai " .
								" ,i_ruang,c_rapat_konsumsipagi,c_rapat_konsumsisiang,c_rapat_makansiang,c_rapat_tipemakansiang,c_rapat_makanmalam,c_rapat_tipemakanmalam,i_rapat_nippimpin,i_rapat_nippesan,c_rapat_statsetuju " .
								" ,c_rapat_statsetuju1,d_rapat_usul,d_rapat_usuljamawal,d_rapat_usuljamakh,c_rapat_statjwbusul,d_rapat_jwbusul,A.i_entry,A.d_entry,'n_peg','n_orgb',i_ruang_usulan" .
								" FROM e_ast_ajuan_pakai_ruang_tm A " .
								" where c_rapat_statsetuju='Y' and (c_rapat_konsumsipagi='Y' or c_rapat_konsumsisiang='Y' or c_rapat_makansiang='Y' or c_rapat_makanmalam='Y') and (c_rapat_statsetuju1 is null or c_rapat_statsetuju1='') "; 
				$query=$query." union SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,A.i_orgb,n_rapat_judul,q_rapat_peserta,i_rapat_mailtgjwb,i_rapat_telptgjwb,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai " .
								" ,i_ruang,c_rapat_konsumsipagi,c_rapat_konsumsisiang,c_rapat_makansiang,c_rapat_tipemakansiang,c_rapat_makanmalam,c_rapat_tipemakanmalam,i_rapat_nippimpin,i_rapat_nippesan,c_rapat_statsetuju " .
								" ,c_rapat_statsetuju1,d_rapat_usul,d_rapat_usuljamawal,d_rapat_usuljamakh,c_rapat_statjwbusul,d_rapat_jwbusul,A.i_entry,A.d_entry,'n_peg','n_orgb',i_ruang_usulan" .
								" FROM e_ast_ajuan_pakai_ruang_tm A " .
								" where c_rapat_statsetuju='U' and c_rapat_statjwbusul='Y' and (c_rapat_konsumsipagi='Y' or c_rapat_konsumsisiang='Y' or c_rapat_makansiang='Y' or c_rapat_makanmalam='Y') " .
								"  and (c_rapat_statsetuju1 is null or c_rapat_statsetuju1='') " .
								"  ORDER BY d_rapat_pesan"; 
						
				$result = $db->fetchAll($query);
				$jmlResult = count($result);
				$hasilAkhir = $jmlResult;
				
			}
			else
			{
				
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
						 
				$query="SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,A.i_orgb,n_rapat_judul,q_rapat_peserta,i_rapat_mailtgjwb,i_rapat_telptgjwb,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai " .
								" ,i_ruang,c_rapat_konsumsipagi,c_rapat_konsumsisiang,c_rapat_makansiang,c_rapat_tipemakansiang,c_rapat_makanmalam,c_rapat_tipemakanmalam,i_rapat_nippimpin,i_rapat_nippesan,c_rapat_statsetuju " .
								" ,c_rapat_statsetuju1,d_rapat_usul,d_rapat_usuljamawal,d_rapat_usuljamakh,c_rapat_statjwbusul,d_rapat_jwbusul,A.i_entry,A.d_entry,'n_peg','n_orgb',i_ruang_usulan" .
								" FROM e_ast_ajuan_pakai_ruang_tm A " .
								" where c_rapat_statsetuju='Y' and (c_rapat_konsumsipagi='Y' or c_rapat_konsumsisiang='Y' or c_rapat_makansiang='Y' or c_rapat_makanmalam='Y') and (c_rapat_statsetuju1 is null or c_rapat_statsetuju1='') "; 
				$query=$query." union SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,A.i_orgb,n_rapat_judul,q_rapat_peserta,i_rapat_mailtgjwb,i_rapat_telptgjwb,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai " .
								" ,i_ruang,c_rapat_konsumsipagi,c_rapat_konsumsisiang,c_rapat_makansiang,c_rapat_tipemakansiang,c_rapat_makanmalam,c_rapat_tipemakanmalam,i_rapat_nippimpin,i_rapat_nippesan,c_rapat_statsetuju " .
								" ,c_rapat_statsetuju1,d_rapat_usul,d_rapat_usuljamawal,d_rapat_usuljamakh,c_rapat_statjwbusul,d_rapat_jwbusul,A.i_entry,A.d_entry,'n_peg','n_orgb',i_ruang_usulan" .
								" FROM e_ast_ajuan_pakai_ruang_tm A " .
								" where c_rapat_statsetuju='U' and c_rapat_statjwbusul='Y' and (c_rapat_konsumsipagi='Y' or c_rapat_konsumsisiang='Y' or c_rapat_makansiang='Y' or c_rapat_makanmalam='Y') " .
								"  and (c_rapat_statsetuju1 is null or c_rapat_statsetuju1='') " .
								"  ORDER BY d_rapat_pesan" .
								"  limit $xLimit offset $xOffset"; 
						
				$result = $db->fetchAll($query);
				$jmlResult = count($result);
						
				if($jmlResult > 0){
					for ($j = 0; $j < $jmlResult; $j++) {
				 				$sdm = new ast_setuju_ruangrapat_Service();
				 				$iPeg=$result[$j]->i_rapat_nippesan;
				 				$iOrg=$result[$j]->i_orgb;
				 				$namaPeg = $sdm->getNamaPegawaiRR($iPeg);
				 				$namaOrg = $sdm->getOrgRR($iOrg);
								$hasilAkhir[$j] = array("i_ruang_ajuanpakai"=>(string)$result[$j]->i_ruang_ajuanpakai,
														"d_ruang_ajuanpakai"=>(string)$result[$j]->d_ruang_ajuanpakai,
														"i_orgb"=>(string)$result[$j]->i_orgb,
														"n_rapat_judul"=>(string)$result[$j]->n_rapat_judul,
														"n_atk_tipe"=>(string)$result[$j]->n_atk_tipe,
														"q_rapat_peserta"=>(string)$result[$j]->q_rapat_peserta,
														"i_rapat_mailtgjwb"=>(string)$result[$j]->i_rapat_mailtgjwb,
														"i_rapat_telptgjwb"=>(string)$result[$j]->i_rapat_telptgjwb,
														"d_rapat_pesan"=>(string)$result[$j]->d_rapat_pesan,
														"d_rapat_awalpakai"=>(string)$result[$j]->d_rapat_awalpakai,
														"d_rapat_akhirpakai"=>(string)$result[$j]->d_rapat_akhirpakai,

														"i_ruang"=>(string)$result[$j]->i_ruang,
														"c_rapat_konsumsipagi"=>(string)$result[$j]->c_rapat_konsumsipagi,
														"c_rapat_konsumsisiang"=>(string)$result[$j]->c_rapat_konsumsisiang,
														"c_rapat_makansiang"=>(string)$result[$j]->c_rapat_makansiang,
														"c_rapat_tipemakansiang"=>(string)$result[$j]->c_rapat_tipemakansiang,
														"c_rapat_makanmalam"=>(string)$result[$j]->c_rapat_makanmalam,
														"c_rapat_tipemakanmalam"=>(string)$result[$j]->c_rapat_tipemakanmalam,
														"i_rapat_nippimpin"=>(string)$result[$j]->i_rapat_nippimpin,
														"i_rapat_nippesan"=>(string)$result[$j]->i_rapat_nippesan,
														"c_rapat_statsetuju"=>(string)$result[$j]->c_rapat_statsetuju,
														
														"c_rapat_statsetuju1"=>(string)$result[$j]->c_rapat_statsetuju1,
														"d_rapat_usul"=>(string)$result[$j]->d_rapat_usul,
														"d_rapat_usuljamawal"=>(string)$result[$j]->d_rapat_usuljamawal,
														"d_rapat_usuljamakh"=>(string)$result[$j]->d_rapat_usuljamakh,
														"c_rapat_statjwbusul"=>(string)$result[$j]->c_rapat_statjwbusul,
														"d_rapat_jwbusul"=>(string)$result[$j]->d_rapat_jwbusul,
														"i_entry"=>(string)$result[$j]->i_entry,
														"d_entry"=>(string)$result[$j]->d_entry,
														"n_peg" => $namaPeg,
														"n_orgb"=>$namaOrg,
														"i_ruang_usulan"=>(string)$result[$j]->i_ruang_usulan
														);
									 			}
						}
				}
						return $hasilAkhir;
			
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	
	public function getAllPersetujuanRRKabagList2() {        
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$query="SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,A.i_orgb,n_rapat_judul,q_rapat_peserta,i_rapat_mailtgjwb,i_rapat_telptgjwb,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai " .
					" ,i_ruang,c_rapat_konsumsipagi,c_rapat_konsumsisiang,c_rapat_makansiang,c_rapat_tipemakansiang,c_rapat_makanmalam,c_rapat_tipemakanmalam,i_rapat_nippimpin,i_rapat_nippesan,c_rapat_statsetuju " .
					" ,c_rapat_statsetuju1,d_rapat_usul,d_rapat_usuljamawal,d_rapat_usuljamakh,c_rapat_statjwbusul,d_rapat_jwbusul,A.i_entry,A.d_entry,'n_peg','n_orgb',i_ruang_usulan" .
					" FROM e_ast_ajuan_pakai_ruang_tm A " .
					" where c_rapat_statsetuju='Y' and (c_rapat_konsumsipagi='Y' or c_rapat_konsumsisiang='Y' or c_rapat_makansiang='Y' or c_rapat_makanmalam='Y') and (c_rapat_statsetuju1 is null or c_rapat_statsetuju1='') "; 
			$query=$query." union SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,A.i_orgb,n_rapat_judul,q_rapat_peserta,i_rapat_mailtgjwb,i_rapat_telptgjwb,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai " .
					" ,i_ruang,c_rapat_konsumsipagi,c_rapat_konsumsisiang,c_rapat_makansiang,c_rapat_tipemakansiang,c_rapat_makanmalam,c_rapat_tipemakanmalam,i_rapat_nippimpin,i_rapat_nippesan,c_rapat_statsetuju " .
					" ,c_rapat_statsetuju1,d_rapat_usul,d_rapat_usuljamawal,d_rapat_usuljamakh,c_rapat_statjwbusul,d_rapat_jwbusul,A.i_entry,A.d_entry,'n_peg','n_orgb',i_ruang_usulan" .
					" FROM e_ast_ajuan_pakai_ruang_tm A " .
					" where c_rapat_statsetuju='U' and c_rapat_statjwbusul='Y' and (c_rapat_konsumsipagi='Y' or c_rapat_konsumsisiang='Y' or c_rapat_makansiang='Y' or c_rapat_makanmalam='Y') and (c_rapat_statsetuju1 is null or c_rapat_statsetuju1='')"; 
			//echo "<br>Querynya :".$query."<br>";
			$result = $db->fetchAll($query);
			$jmlResult = count($result);
			if($jmlResult > 0){
	 			for ($j = 0; $j < $jmlResult; $j++) {
	 				$sdm = new ast_setuju_ruangrapat_Service();
	 				$iPeg=$result[$j]->i_rapat_nippesan;
	 				$iOrg=$result[$j]->i_orgb;
	 				$namaPeg = $sdm->getNamaPegawaiRR($iPeg);
	 				$namaOrg = $sdm->getOrgRR($iOrg);
					$hasilAkhir[$j] = array("i_ruang_ajuanpakai"=>(string)$result[$j]->i_ruang_ajuanpakai,
						"d_ruang_ajuanpakai"=>(string)$result[$j]->d_ruang_ajuanpakai,
						"i_orgb"=>(string)$result[$j]->i_orgb,
						"n_rapat_judul"=>(string)$result[$j]->n_rapat_judul,
						"n_atk_tipe"=>(string)$result[$j]->n_atk_tipe,
						"q_rapat_peserta"=>(string)$result[$j]->q_rapat_peserta,
						"i_rapat_mailtgjwb"=>(string)$result[$j]->i_rapat_mailtgjwb,
						"i_rapat_telptgjwb"=>(string)$result[$j]->i_rapat_telptgjwb,
						"d_rapat_pesan"=>(string)$result[$j]->d_rapat_pesan,
						"d_rapat_awalpakai"=>(string)$result[$j]->d_rapat_awalpakai,
						"d_rapat_akhirpakai"=>(string)$result[$j]->d_rapat_akhirpakai,

						"i_ruang"=>(string)$result[$j]->i_ruang,
						"c_rapat_konsumsipagi"=>(string)$result[$j]->c_rapat_konsumsipagi,
						"c_rapat_konsumsisiang"=>(string)$result[$j]->c_rapat_konsumsisiang,
						"c_rapat_makansiang"=>(string)$result[$j]->c_rapat_makansiang,
						"c_rapat_tipemakansiang"=>(string)$result[$j]->c_rapat_tipemakansiang,
						"c_rapat_makanmalam"=>(string)$result[$j]->c_rapat_makanmalam,
						"c_rapat_tipemakanmalam"=>(string)$result[$j]->c_rapat_tipemakanmalam,
						"i_rapat_nippimpin"=>(string)$result[$j]->i_rapat_nippimpin,
						"i_rapat_nippesan"=>(string)$result[$j]->i_rapat_nippesan,
						"c_rapat_statsetuju"=>(string)$result[$j]->c_rapat_statsetuju,
						
						"c_rapat_statsetuju1"=>(string)$result[$j]->c_rapat_statsetuju1,
						"d_rapat_usul"=>(string)$result[$j]->d_rapat_usul,
						"d_rapat_usuljamawal"=>(string)$result[$j]->d_rapat_usuljamawal,
						"d_rapat_usuljamakh"=>(string)$result[$j]->d_rapat_usuljamakh,
						"c_rapat_statjwbusul"=>(string)$result[$j]->c_rapat_statjwbusul,
						"d_rapat_jwbusul"=>(string)$result[$j]->d_rapat_jwbusul,
						"i_entry"=>(string)$result[$j]->i_entry,
						"d_entry"=>(string)$result[$j]->d_entry,
						"n_peg" => $namaPeg,
						"n_orgb"=>$namaOrg,
						"i_ruang_usulan"=>(string)$result[$j]->i_ruang_usulan
						);
	 			}
			}
			return $hasilAkhir;
		}
		catch (Exception $e){
	    		echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}	
	public function getPersetujuanRRKabagList($tglPenggunaan) {        
		$where[]=$tglPenggunaan;
		$where[]=$tglPenggunaan;
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$query="SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,A.i_orgb,n_rapat_judul,q_rapat_peserta,i_rapat_mailtgjwb,i_rapat_telptgjwb,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai " .
					" ,i_ruang,c_rapat_konsumsipagi,c_rapat_konsumsisiang,c_rapat_makansiang,c_rapat_tipemakansiang,c_rapat_makanmalam,c_rapat_tipemakanmalam,i_rapat_nippimpin,i_rapat_nippesan,c_rapat_statsetuju " .
					" ,c_rapat_statsetuju1,d_rapat_usul,d_rapat_usuljamawal,d_rapat_usuljamakh,c_rapat_statjwbusul,d_rapat_jwbusul,A.i_entry,A.d_entry,'n_peg','n_orgb',i_ruang_usulan" .
					" FROM e_ast_ajuan_pakai_ruang_tm A " .
					" where d_rapat_pesan=? and c_rapat_statsetuju='Y' and (c_rapat_konsumsipagi='Y' or c_rapat_konsumsisiang='Y' or c_rapat_makansiang='Y' or c_rapat_makanmalam='Y') and (c_rapat_statsetuju1 is null or c_rapat_statsetuju1='') "; 
			$query=$query." union SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,A.i_orgb,n_rapat_judul,q_rapat_peserta,i_rapat_mailtgjwb,i_rapat_telptgjwb,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai " .
					" ,i_ruang,c_rapat_konsumsipagi,c_rapat_konsumsisiang,c_rapat_makansiang,c_rapat_tipemakansiang,c_rapat_makanmalam,c_rapat_tipemakanmalam,i_rapat_nippimpin,i_rapat_nippesan,c_rapat_statsetuju " .
					" ,c_rapat_statsetuju1,d_rapat_usul,d_rapat_usuljamawal,d_rapat_usuljamakh,c_rapat_statjwbusul,d_rapat_jwbusul,A.i_entry,A.d_entry,'n_peg','n_orgb',i_ruang_usulan" .
					" FROM e_ast_ajuan_pakai_ruang_tm A " .
					" where d_rapat_pesan=? and c_rapat_statsetuju='U' and c_rapat_statjwbusul='Y' and (c_rapat_konsumsipagi='Y' or c_rapat_konsumsisiang='Y' or c_rapat_makansiang='Y' or c_rapat_makanmalam='Y') and (c_rapat_statsetuju1 is null or c_rapat_statsetuju1='')"; 

			$result = $db->fetchAll($query,$where);
			$jmlResult = count($result);
			if($jmlResult > 0){
	 			for ($j = 0; $j < $jmlResult; $j++) {
	 				$sdm = new ast_setuju_ruangrapat_Service();
	 				$iPeg=$result[$j]->i_rapat_nippesan;
	 				$iOrg=$result[$j]->i_orgb;
	 				$namaPeg = $sdm->getNamaPegawaiRR($iPeg);
	 				$namaOrg = $sdm->getOrgRR($iOrg);
					$hasilAkhir[$j] = array("i_ruang_ajuanpakai"=>(string)$result[$j]->i_ruang_ajuanpakai,
						"d_ruang_ajuanpakai"=>(string)$result[$j]->d_ruang_ajuanpakai,
						"i_orgb"=>(string)$result[$j]->i_orgb,
						"n_rapat_judul"=>(string)$result[$j]->n_rapat_judul,
						"n_atk_tipe"=>(string)$result[$j]->n_atk_tipe,
						"q_rapat_peserta"=>(string)$result[$j]->q_rapat_peserta,
						"i_rapat_mailtgjwb"=>(string)$result[$j]->i_rapat_mailtgjwb,
						"i_rapat_telptgjwb"=>(string)$result[$j]->i_rapat_telptgjwb,
						"d_rapat_pesan"=>(string)$result[$j]->d_rapat_pesan,
						"d_rapat_awalpakai"=>(string)$result[$j]->d_rapat_awalpakai,
						"d_rapat_akhirpakai"=>(string)$result[$j]->d_rapat_akhirpakai,

						"i_ruang"=>(string)$result[$j]->i_ruang,
						"c_rapat_konsumsipagi"=>(string)$result[$j]->c_rapat_konsumsipagi,
						"c_rapat_konsumsisiang"=>(string)$result[$j]->c_rapat_konsumsisiang,
						"c_rapat_makansiang"=>(string)$result[$j]->c_rapat_makansiang,
						"c_rapat_tipemakansiang"=>(string)$result[$j]->c_rapat_tipemakansiang,
						"c_rapat_makanmalam"=>(string)$result[$j]->c_rapat_makanmalam,
						"c_rapat_tipemakanmalam"=>(string)$result[$j]->c_rapat_tipemakanmalam,
						"i_rapat_nippimpin"=>(string)$result[$j]->i_rapat_nippimpin,
						"i_rapat_nippesan"=>(string)$result[$j]->i_rapat_nippesan,
						"c_rapat_statsetuju"=>(string)$result[$j]->c_rapat_statsetuju,
						
						"c_rapat_statsetuju1"=>(string)$result[$j]->c_rapat_statsetuju1,
						"d_rapat_usul"=>(string)$result[$j]->d_rapat_usul,
						"d_rapat_usuljamawal"=>(string)$result[$j]->d_rapat_usuljamawal,
						"d_rapat_usuljamakh"=>(string)$result[$j]->d_rapat_usuljamakh,
						"c_rapat_statjwbusul"=>(string)$result[$j]->c_rapat_statjwbusul,
						"d_rapat_jwbusul"=>(string)$result[$j]->d_rapat_jwbusul,
						"i_entry"=>(string)$result[$j]->i_entry,
						"d_entry"=>(string)$result[$j]->d_entry,
						"n_peg" => $namaPeg,
						"n_orgb"=>$namaOrg,
						"i_ruang_usulan"=>(string)$result[$j]->i_ruang_usulan
						);
	 			}
			}
			return $hasilAkhir;
		}
		catch (Exception $e){
	    		echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}	
	public function getPersetujuanRRKabagByNoList($nopeng) {        
		$where[]=$nopeng;
		$where[]=$nopeng;
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$query="SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,A.i_orgb,n_rapat_judul,q_rapat_peserta,i_rapat_mailtgjwb,i_rapat_telptgjwb,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai " .
					" ,i_ruang,c_rapat_konsumsipagi,c_rapat_konsumsisiang,c_rapat_makansiang,c_rapat_tipemakansiang,c_rapat_makanmalam,c_rapat_tipemakanmalam,i_rapat_nippimpin,i_rapat_nippesan,c_rapat_statsetuju " .
					" ,c_rapat_statsetuju1,d_rapat_usul,d_rapat_usuljamawal,d_rapat_usuljamakh,c_rapat_statjwbusul,d_rapat_jwbusul,A.i_entry,A.d_entry,'n_peg','n_orgb',i_ruang_usulan" .
					" FROM e_ast_ajuan_pakai_ruang_tm A " .
					" where i_ruang_ajuanpakai =? and c_rapat_statsetuju='Y' and (c_rapat_konsumsipagi='Y' or c_rapat_konsumsisiang='Y' or c_rapat_makansiang='Y' or c_rapat_makanmalam='Y') and (c_rapat_statsetuju1 is null or c_rapat_statsetuju1='') "; 
			$query=$query." union SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,A.i_orgb,n_rapat_judul,q_rapat_peserta,i_rapat_mailtgjwb,i_rapat_telptgjwb,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai " .
					" ,i_ruang,c_rapat_konsumsipagi,c_rapat_konsumsisiang,c_rapat_makansiang,c_rapat_tipemakansiang,c_rapat_makanmalam,c_rapat_tipemakanmalam,i_rapat_nippimpin,i_rapat_nippesan,c_rapat_statsetuju " .
					" ,c_rapat_statsetuju1,d_rapat_usul,d_rapat_usuljamawal,d_rapat_usuljamakh,c_rapat_statjwbusul,d_rapat_jwbusul,A.i_entry,A.d_entry,'n_peg','n_orgb',i_ruang_usulan" .
					" FROM e_ast_ajuan_pakai_ruang_tm A " .
					" where i_ruang_ajuanpakai =? and c_rapat_statsetuju='U' and c_rapat_statjwbusul='Y' and (c_rapat_konsumsipagi='Y' or c_rapat_konsumsisiang='Y' or c_rapat_makansiang='Y' or c_rapat_makanmalam='Y') and (c_rapat_statsetuju1 is null or c_rapat_statsetuju1='')"; 

			$result = $db->fetchAll($query,$where);
			$jmlResult = count($result);
			if($jmlResult > 0){
	 			for ($j = 0; $j < $jmlResult; $j++) {
	 				$sdm = new ast_setuju_ruangrapat_Service();
	 				$iPeg=$result[$j]->i_rapat_nippesan;
	 				$iOrg=$result[$j]->i_orgb;
	 				$namaPeg = $sdm->getNamaPegawaiRR($iPeg);
	 				$namaOrg = $sdm->getOrgRR($iOrg);
					$hasilAkhir[$j] = array("i_ruang_ajuanpakai"=>(string)$result[$j]->i_ruang_ajuanpakai,
						"d_ruang_ajuanpakai"=>(string)$result[$j]->d_ruang_ajuanpakai,
						"i_orgb"=>(string)$result[$j]->i_orgb,
						"n_rapat_judul"=>(string)$result[$j]->n_rapat_judul,
						"n_atk_tipe"=>(string)$result[$j]->n_atk_tipe,
						"q_rapat_peserta"=>(string)$result[$j]->q_rapat_peserta,
						"i_rapat_mailtgjwb"=>(string)$result[$j]->i_rapat_mailtgjwb,
						"i_rapat_telptgjwb"=>(string)$result[$j]->i_rapat_telptgjwb,
						"d_rapat_pesan"=>(string)$result[$j]->d_rapat_pesan,
						"d_rapat_awalpakai"=>(string)$result[$j]->d_rapat_awalpakai,
						"d_rapat_akhirpakai"=>(string)$result[$j]->d_rapat_akhirpakai,

						"i_ruang"=>(string)$result[$j]->i_ruang,
						"c_rapat_konsumsipagi"=>(string)$result[$j]->c_rapat_konsumsipagi,
						"c_rapat_konsumsisiang"=>(string)$result[$j]->c_rapat_konsumsisiang,
						"c_rapat_makansiang"=>(string)$result[$j]->c_rapat_makansiang,
						"c_rapat_tipemakansiang"=>(string)$result[$j]->c_rapat_tipemakansiang,
						"c_rapat_makanmalam"=>(string)$result[$j]->c_rapat_makanmalam,
						"c_rapat_tipemakanmalam"=>(string)$result[$j]->c_rapat_tipemakanmalam,
						"i_rapat_nippimpin"=>(string)$result[$j]->i_rapat_nippimpin,
						"i_rapat_nippesan"=>(string)$result[$j]->i_rapat_nippesan,
						"c_rapat_statsetuju"=>(string)$result[$j]->c_rapat_statsetuju,
						
						"c_rapat_statsetuju1"=>(string)$result[$j]->c_rapat_statsetuju1,
						"d_rapat_usul"=>(string)$result[$j]->d_rapat_usul,
						"d_rapat_usuljamawal"=>(string)$result[$j]->d_rapat_usuljamawal,
						"d_rapat_usuljamakh"=>(string)$result[$j]->d_rapat_usuljamakh,
						"c_rapat_statjwbusul"=>(string)$result[$j]->c_rapat_statjwbusul,
						"d_rapat_jwbusul"=>(string)$result[$j]->d_rapat_jwbusul,
						"i_entry"=>(string)$result[$j]->i_entry,
						"d_entry"=>(string)$result[$j]->d_entry,
						"n_peg" => $namaPeg,
						"n_orgb"=>$namaOrg,
						"i_ruang_usulan"=>(string)$result[$j]->i_ruang_usulan
						);
	 			}
			}
			return $hasilAkhir;
		}
		catch (Exception $e){
	    		echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}	


	public function getPenggunaanRuangRapatList($tglPenggunaan) {        
	/////// cek ajah //////////
		$where[]=$tglPenggunaan;
		$where[]=$tglPenggunaan;

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$query="SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,i_orgb,n_rapat_judul,q_rapat_peserta,i_rapat_mailtgjwb,i_rapat_telptgjwb,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai " .
					" ,i_ruang,c_rapat_konsumsipagi,c_rapat_konsumsisiang,c_rapat_makansiang,c_rapat_tipemakansiang,c_rapat_makanmalam,c_rapat_tipemakanmalam,i_rapat_nippimpin,i_rapat_nippesan,c_rapat_statsetuju " .
					" ,c_rapat_statsetuju1,d_rapat_usul,d_rapat_usuljamawal,d_rapat_usuljamakh,c_rapat_statjwbusul,d_rapat_jwbusul,i_entry,d_entry" .
					" FROM e_ast_ajuan_pakai_ruang_tm " .
					" where d_rapat_pesan=?  and c_rapat_statsetuju ='Y' "; //
			$query=$query." union SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,i_orgb,n_rapat_judul,q_rapat_peserta,i_rapat_mailtgjwb,i_rapat_telptgjwb,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai " .
					" ,i_ruang,c_rapat_konsumsipagi,c_rapat_konsumsisiang,c_rapat_makansiang,c_rapat_tipemakansiang,c_rapat_makanmalam,c_rapat_tipemakanmalam,i_rapat_nippimpin,i_rapat_nippesan,c_rapat_statsetuju " .
					" ,c_rapat_statsetuju1,d_rapat_usul,d_rapat_usuljamawal,d_rapat_usuljamakh,c_rapat_statjwbusul,d_rapat_jwbusul,i_entry,d_entry" .
					" FROM e_ast_ajuan_pakai_ruang_tm " .
					" where d_rapat_pesan=? and c_rapat_statsetuju ='U' and c_rapat_statjwbusul='Y' "; //  ORDER BY i_ruang_ajuanpakai 
			
			$result = $db->fetchAll($query,$where);
			$jmlResult = count($result);
			if($jmlResult > 0){
	 			for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_ruang_ajuanpakai"=>(string)$result[$j]->i_ruang_ajuanpakai,
						"d_ruang_ajuanpakai"=>(string)$result[$j]->d_ruang_ajuanpakai,
						"i_orgb"=>(string)$result[$j]->i_orgb,
						"n_rapat_judul"=>(string)$result[$j]->n_rapat_judul,
						"n_atk_tipe"=>(string)$result[$j]->n_atk_tipe,
						"q_rapat_peserta"=>(string)$result[$j]->q_rapat_peserta,
						"i_rapat_mailtgjwb"=>(string)$result[$j]->i_rapat_mailtgjwb,
						"i_rapat_telptgjwb"=>(string)$result[$j]->i_rapat_telptgjwb,
						"d_rapat_pesan"=>(string)$result[$j]->d_rapat_pesan,
						"d_rapat_awalpakai"=>(string)$result[$j]->d_rapat_awalpakai,
						"d_rapat_akhirpakai"=>(string)$result[$j]->d_rapat_akhirpakai,

						"i_ruang"=>(string)$result[$j]->i_ruang,
						"c_rapat_konsumsipagi"=>(string)$result[$j]->c_rapat_konsumsipagi,
						"c_rapat_konsumsisiang"=>(string)$result[$j]->c_rapat_konsumsisiang,
						"c_rapat_makansiang"=>(string)$result[$j]->c_rapat_makansiang,
						"c_rapat_tipemakansiang"=>(string)$result[$j]->c_rapat_tipemakansiang,
						"c_rapat_makanmalam"=>(string)$result[$j]->c_rapat_makanmalam,
						"c_rapat_tipemakanmalam"=>(string)$result[$j]->c_rapat_tipemakanmalam,
						"i_rapat_nippimpin"=>(string)$result[$j]->i_rapat_nippimpin,
						"i_rapat_nippesan"=>(string)$result[$j]->i_rapat_nippesan,
						"c_rapat_statsetuju"=>(string)$result[$j]->c_rapat_statsetuju,
						
						"c_rapat_statsetuju1"=>(string)$result[$j]->c_rapat_statsetuju1,
						"d_rapat_usul"=>(string)$result[$j]->d_rapat_usul,
						"d_rapat_usuljamawal"=>(string)$result[$j]->d_rapat_usuljamawal,
						"d_rapat_usuljamakh"=>(string)$result[$j]->d_rapat_usuljamakh,
						"c_rapat_statjwbusul"=>(string)$result[$j]->c_rapat_statjwbusul,
						"d_rapat_jwbusul"=>(string)$result[$j]->d_rapat_jwbusul,
						"i_entry"=>(string)$result[$j]->i_entry,
						"d_entry"=>(string)$result[$j]->d_entry
						);
	 			}
			}
			return $hasilAkhir;
		}
		catch (Exception $e){
	    		echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}	
	
	public function getPenggunaRuangan($kdRuangan,$tglPenggunaan,$jamAwalRapat) {        
	/////// cek ajah //////////
		$where[]=$kdRuangan;
		$where[]=$tglPenggunaan;
		$where[]=$jamAwalRapat;

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$query="SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,i_orgb,n_rapat_judul,q_rapat_peserta,i_rapat_mailtgjwb,i_rapat_telptgjwb,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai " .
					" ,i_ruang,c_rapat_konsumsipagi,c_rapat_konsumsisiang,c_rapat_makansiang,c_rapat_tipemakansiang,c_rapat_makanmalam,c_rapat_tipemakanmalam,i_rapat_nippimpin,i_rapat_nippesan,c_rapat_statsetuju " .
					" ,c_rapat_statsetuju1,d_rapat_usul,d_rapat_usuljamawal,d_rapat_usuljamakh,c_rapat_statjwbusul,d_rapat_jwbusul,i_entry,d_entry" .
					" FROM e_ast_ajuan_pakai_ruang_tm " .
					" where i_ruang=? and d_rapat_pesan=? and d_rapat_awal_pakai=? "; 
			$result = $db->fetchAll($query,$where);
			$jmlResult = count($result);
			if($jmlResult > 0){
	 			for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_ruang_ajuanpakai"=>(string)$result[$j]->i_ruang_ajuanpakai,
						"d_ruang_ajuanpakai"=>(string)$result[$j]->d_ruang_ajuanpakai,
						"i_orgb"=>(string)$result[$j]->i_orgb,
						"n_rapat_judul"=>(string)$result[$j]->n_rapat_judul,
						"n_atk_tipe"=>(string)$result[$j]->n_atk_tipe,
						"q_rapat_peserta"=>(string)$result[$j]->q_rapat_peserta,
						"i_rapat_mailtgjwb"=>(string)$result[$j]->i_rapat_mailtgjwb,
						"i_rapat_telptgjwb"=>(string)$result[$j]->i_rapat_telptgjwb,
						"d_rapat_pesan"=>(string)$result[$j]->d_rapat_pesan,
						"d_rapat_awalpakai"=>(string)$result[$j]->d_rapat_awalpakai,
						"d_rapat_akhirpakai"=>(string)$result[$j]->d_rapat_akhirpakai,

						"i_ruang"=>(string)$result[$j]->i_ruang,
						"c_rapat_konsumsipagi"=>(string)$result[$j]->c_rapat_konsumsipagi,
						"c_rapat_konsumsisiang"=>(string)$result[$j]->c_rapat_konsumsisiang,
						"c_rapat_makansiang"=>(string)$result[$j]->c_rapat_makansiang,
						"c_rapat_tipemakansiang"=>(string)$result[$j]->c_rapat_tipemakansiang,
						"c_rapat_makanmalam"=>(string)$result[$j]->c_rapat_makanmalam,
						"c_rapat_tipemakanmalam"=>(string)$result[$j]->c_rapat_tipemakanmalam,
						"i_rapat_nippimpin"=>(string)$result[$j]->i_rapat_nippimpin,
						"i_rapat_nippesan"=>(string)$result[$j]->i_rapat_nippesan,
						"c_rapat_statsetuju"=>(string)$result[$j]->c_rapat_statsetuju,
						
						"c_rapat_statsetuju1"=>(string)$result[$j]->c_rapat_statsetuju1,
						"d_rapat_usul"=>(string)$result[$j]->d_rapat_usul,
						"d_rapat_usuljamawal"=>(string)$result[$j]->d_rapat_usuljamawal,
						"d_rapat_usuljamakh"=>(string)$result[$j]->d_rapat_usuljamakh,
						"c_rapat_statjwbusul"=>(string)$result[$j]->c_rapat_statjwbusul,
						"d_rapat_jwbusul"=>(string)$result[$j]->d_rapat_jwbusul,
						"i_entry"=>(string)$result[$j]->i_entry,
						"d_entry"=>(string)$result[$j]->d_entry
						);
	 			}
			}
			return $hasilAkhir;
		}
		catch (Exception $e){
	    		echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}	
	public function getRuangRapatList($tgl,$jamAwal) {        
	/////// cek ajah //////////
		$where[]=$tgl;
		$where[]=$jamAwal;

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$query="SELECT A.i_ruang from e_ast_ruangan_0_tr A " .
					" where not exists  " .
					" (select B.i_ruang from e_ast_ajuanpakai_ruang_tm B " .
					"  where d_rapat_pesan=? and d_rapat_awal_pakai=? and A.i_ruang=B.i_ruang )" .
					" order by 1  "; 
			$result = $db->fetchAll($query,$where);
			$jmlResult = count($result);
			if($jmlResult > 0){
	 			for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_ruang"=>(string)$result[$j]->i_ruang
						);
	 			}
			}
			return $hasilAkhir;
		}
		catch (Exception $e){
	    		echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}	
	
	public function updatePersetujuanRTU($nopeng,$stspersetujuan,$usulanruangan,$usulanawal,$usulanakhir) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');		
		try {
		 $db->beginTransaction();
		 $ast_ajuan_prm = array("c_rapat_statsetuju"=>$stspersetujuan
			,"i_ruang_usulan"=>$usulanruangan
			,"d_rapat_usuljamawal"=>$usulanawal
			,"d_rapat_usuljamakh"=>$usulanakhir
			);	 			
		 $where[] = "i_ruang_ajuanpakai = '".trim($nopeng)."'";
		 $db->update("e_ast_ajuan_pakai_ruang_tm",$ast_ajuan_prm, $where);
		 $db->commit();		 		 
		 return 'sukses';
		}
		catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal';
		}
	}
	public function updatePersetujuanRTY($nopeng,$stspersetujuan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');		
		try {
		 $db->beginTransaction();
		 $ast_ajuan_prm = array("c_rapat_statsetuju"=>$stspersetujuan
			);	 			
		 $where[] = "i_ruang_ajuanpakai = '".trim($nopeng)."'";
		 $db->update("e_ast_ajuan_pakai_ruang_tm",$ast_ajuan_prm, $where);
		 $db->commit();
		// echo 'sukses nih Yee<br>';
		 return 'sukses <br>';
		}
		catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	} 

	public function updatePersetujuan($nopeng,$stsPersetujuan,$usulanRuangan,$usulanJamAwal,$usulanJamAkhir) {
		//echo " update setuju RT :".$nopeng." : ".$stsPersetujuan." : ".$usulanRuangan." : ".$usulanJamAwal." - ".$usulanJamAkhir;
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');		
		try {
		 $db->beginTransaction();
		 $ast_ajuan_prm = array("c_rapat_statsetuju"=>$stsPersetujuan,
			"i_ruang_usulan"=>$usulanRuangan,
			"d_rapat_usuljamawal"=>$usulanJamAwal,
			"d_rapat_usuljamakh"=>$usulanJamAkhir
			);	 
		 $where[] = "i_ruang_ajuanpakai = '".trim($nopeng)."'";
		 $db->update("e_ast_ajuan_pakai_ruang_tm",$ast_ajuan_prm, $where);
		 $db->commit();
		 return 'sukses <br>';
		}
		catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}

	public function updatePersetujuanKaBag($nopeng,$stsPersetujuan) {
		//echo " update setuju KaBag :".$nopeng." : ".$stsPersetujuan." : ";
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		 $db->beginTransaction();
		 $ast_ajuan_prm = array("c_rapat_statsetuju1"=>$stsPersetujuan
			);	 
		 $where[] = "i_ruang_ajuanpakai = '".trim($nopeng)."'";
		 $db->update('e_ast_ajuan_pakai_ruang_tm',$ast_ajuan_prm, $where);
		 $db->commit();
		 //echo 'sukses KaBag<br>';
		 return 'sukses <br>';
		}
		catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}
	public function getKonfirmasiList_old($kdKantor) {        		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		//echo "masuk Kantor ".$kdKantor." :";
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$query="SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,A.i_orgb,n_rapat_judul,q_rapat_peserta,i_rapat_mailtgjwb,i_rapat_telptgjwb,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai " .
					" ,i_ruang,c_rapat_konsumsipagi,c_rapat_konsumsisiang,c_rapat_makansiang,c_rapat_tipemakansiang,c_rapat_makanmalam,c_rapat_tipemakanmalam,i_rapat_nippimpin,i_rapat_nippesan,c_rapat_statsetuju " .
					" ,c_rapat_statsetuju1,d_rapat_usul,d_rapat_usuljamawal,d_rapat_usuljamakh,c_rapat_statjwbusul,d_rapat_jwbusul,A.i_entry,A.d_entry,'n_peg','n_orgb',i_ruang_usulan" .
					" FROM e_ast_ajuan_pakai_ruang_tm A " .
					" where A.i_orgb=?  and c_rapat_statsetuju='U' and (c_rapat_statjwbusul is null or c_rapat_statjwbusul='') ORDER BY i_ruang_ajuanpakai "; 
			$result = $db->fetchAll($query,$kdKantor);
			$jmlResult = count($result);
			if($jmlResult > 0){
	 			for ($j = 0; $j < $jmlResult; $j++) {
	 				$sdm = new ast_setuju_ruangrapat_Service();
	 				$iPeg=$result[$j]->i_rapat_nippesan;
	 				$iOrg=$result[$j]->i_orgb;
	 				$namaPeg = $sdm->getNamaPegawaiRR($iPeg);
	 				//echo "<br> nama peg: ". $namaPeg
	 				$namaOrg = $sdm->getOrgRR($iOrg);
					$hasilAkhir[$j] = array("i_ruang_ajuanpakai"=>(string)$result[$j]->i_ruang_ajuanpakai,
						"d_ruang_ajuanpakai"=>(string)$result[$j]->d_ruang_ajuanpakai,
						"i_orgb"=>(string)$result[$j]->i_orgb,
						"n_rapat_judul"=>(string)$result[$j]->n_rapat_judul,
						"n_atk_tipe"=>(string)$result[$j]->n_atk_tipe,
						"q_rapat_peserta"=>(string)$result[$j]->q_rapat_peserta,
						"i_rapat_mailtgjwb"=>(string)$result[$j]->i_rapat_mailtgjwb,
						"i_rapat_telptgjwb"=>(string)$result[$j]->i_rapat_telptgjwb,
						"d_rapat_pesan"=>(string)$result[$j]->d_rapat_pesan,
						"d_rapat_awalpakai"=>(string)$result[$j]->d_rapat_awalpakai,
						"d_rapat_akhirpakai"=>(string)$result[$j]->d_rapat_akhirpakai,
						"i_ruang"=>(string)$result[$j]->i_ruang,
						"c_rapat_konsumsipagi"=>(string)$result[$j]->c_rapat_konsumsipagi,
						"c_rapat_konsumsisiang"=>(string)$result[$j]->c_rapat_konsumsisiang,
						"c_rapat_makansiang"=>(string)$result[$j]->c_rapat_makansiang,
						"c_rapat_tipemakansiang"=>(string)$result[$j]->c_rapat_tipemakansiang,
						"c_rapat_makanmalam"=>(string)$result[$j]->c_rapat_makanmalam,
						"c_rapat_tipemakanmalam"=>(string)$result[$j]->c_rapat_tipemakanmalam,
						"i_rapat_nippimpin"=>(string)$result[$j]->i_rapat_nippimpin,
						"i_rapat_nippesan"=>(string)$result[$j]->i_rapat_nippesan,
						"c_rapat_statsetuju"=>(string)$result[$j]->c_rapat_statsetuju,						
						"c_rapat_statsetuju1"=>(string)$result[$j]->c_rapat_statsetuju1,
						"d_rapat_usul"=>(string)$result[$j]->d_rapat_usul,
						"d_rapat_usuljamawal"=>(string)$result[$j]->d_rapat_usuljamawal,
						"d_rapat_usuljamakh"=>(string)$result[$j]->d_rapat_usuljamakh,
						"c_rapat_statjwbusul"=>(string)$result[$j]->c_rapat_statjwbusul,
						"d_rapat_jwbusul"=>(string)$result[$j]->d_rapat_jwbusul,
						"i_entry"=>(string)$result[$j]->i_entry,
						"d_entry"=>(string)$result[$j]->d_entry,
						"n_peg"=>(string)$result[$j]->n_peg,
						"n_orgb"=>(string)$result[$j]->n_orgb,
						"i_ruang_usulan"=>(string)$result[$j]->i_ruang_usulan
						);
	 			}
			}
			return $hasilAkhir;
		}
		catch (Exception $e){
	    		echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}	
	
	// Ina : 24-06-2008 : Awal :Perubahan where claus : Jadi NIP	
	public function getKonfirmasiList($nip) {        		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
	    
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$query="SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,A.i_orgb,n_rapat_judul,q_rapat_peserta,i_rapat_mailtgjwb,i_rapat_telptgjwb,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai " .
					" ,i_ruang,c_rapat_konsumsipagi,c_rapat_konsumsisiang,c_rapat_makansiang,c_rapat_tipemakansiang,c_rapat_makanmalam,c_rapat_tipemakanmalam,i_rapat_nippimpin,i_rapat_nippesan,c_rapat_statsetuju " .
					" ,c_rapat_statsetuju1,d_rapat_usul,d_rapat_usuljamawal,d_rapat_usuljamakh,c_rapat_statjwbusul,d_rapat_jwbusul,A.i_entry,A.d_entry,'n_peg','n_orgb',i_ruang_usulan" .
					" FROM e_ast_ajuan_pakai_ruang_tm A " .
					" where A.i_rapat_nippesan=?  and c_rapat_statsetuju='U' and (c_rapat_statjwbusul is null or c_rapat_statjwbusul='') ORDER BY i_ruang_ajuanpakai "; 
			$result = $db->fetchAll($query,$nip);
			$jmlResult = count($result);
			if($jmlResult > 0){
	 			for ($j = 0; $j < $jmlResult; $j++) {
	 				$sdm = new ast_setuju_ruangrapat_Service();
	 				$iPeg=$result[$j]->i_rapat_nippesan;
	 				$iOrg=$result[$j]->i_orgb;
	 				$namaPeg = $sdm->getNamaPegawaiRR($iPeg);
	 				//echo "<br> nama peg: ". $namaPeg
	 				$namaOrg = $sdm->getOrgRR($iOrg);
					$hasilAkhir[$j] = array("i_ruang_ajuanpakai"=>(string)$result[$j]->i_ruang_ajuanpakai,
						"d_ruang_ajuanpakai"=>(string)$result[$j]->d_ruang_ajuanpakai,
						"i_orgb"=>(string)$result[$j]->i_orgb,
						"n_rapat_judul"=>(string)$result[$j]->n_rapat_judul,
						"n_atk_tipe"=>(string)$result[$j]->n_atk_tipe,
						"q_rapat_peserta"=>(string)$result[$j]->q_rapat_peserta,
						"i_rapat_mailtgjwb"=>(string)$result[$j]->i_rapat_mailtgjwb,
						"i_rapat_telptgjwb"=>(string)$result[$j]->i_rapat_telptgjwb,
						"d_rapat_pesan"=>(string)$result[$j]->d_rapat_pesan,
						"d_rapat_awalpakai"=>(string)$result[$j]->d_rapat_awalpakai,
						"d_rapat_akhirpakai"=>(string)$result[$j]->d_rapat_akhirpakai,

						"i_ruang"=>(string)$result[$j]->i_ruang,
						"c_rapat_konsumsipagi"=>(string)$result[$j]->c_rapat_konsumsipagi,
						"c_rapat_konsumsisiang"=>(string)$result[$j]->c_rapat_konsumsisiang,
						"c_rapat_makansiang"=>(string)$result[$j]->c_rapat_makansiang,
						"c_rapat_tipemakansiang"=>(string)$result[$j]->c_rapat_tipemakansiang,
						"c_rapat_makanmalam"=>(string)$result[$j]->c_rapat_makanmalam,
						"c_rapat_tipemakanmalam"=>(string)$result[$j]->c_rapat_tipemakanmalam,
						"i_rapat_nippimpin"=>(string)$result[$j]->i_rapat_nippimpin,
						"i_rapat_nippesan"=>(string)$result[$j]->i_rapat_nippesan,
						"c_rapat_statsetuju"=>(string)$result[$j]->c_rapat_statsetuju,
						
						"c_rapat_statsetuju1"=>(string)$result[$j]->c_rapat_statsetuju1,
						"d_rapat_usul"=>(string)$result[$j]->d_rapat_usul,
						"d_rapat_usuljamawal"=>(string)$result[$j]->d_rapat_usuljamawal,
						"d_rapat_usuljamakh"=>(string)$result[$j]->d_rapat_usuljamakh,
						"c_rapat_statjwbusul"=>(string)$result[$j]->c_rapat_statjwbusul,
						"d_rapat_jwbusul"=>(string)$result[$j]->d_rapat_jwbusul,
						"i_entry"=>(string)$result[$j]->i_entry,
						"d_entry"=>(string)$result[$j]->d_entry,
						"n_peg"=>(string)$result[$j]->n_peg,
						"n_orgb"=>(string)$result[$j]->n_orgb,
						"i_ruang_usulan"=>(string)$result[$j]->i_ruang_usulan
						);
	 			}
			}
			return $hasilAkhir;
		}
		catch (Exception $e){
	    		echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}	
	// Ina : 24-06-2008 : Akhir
	
	public function updateKonfirmasiList_Tolak($nopeng,$stsPersetujuan) {
		//echo " update KonfirmasiList : ".$nopeng." : ".$stsPersetujuan." : ";
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		 $db->beginTransaction();
		 $ast_ajuan_prm = array("c_rapat_statjwbusul"=>$stsPersetujuan
			);	 
		 $where[] = "i_ruang_ajuanpakai = '".trim($nopeng)."'";
		 $db->update('e_ast_ajuan_pakai_ruang_tm',$ast_ajuan_prm, $where);
		 $db->commit();
		 //echo 'sukses KaBag<br>';
		 return 'sukses <br>';
		}
		catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}
	
	public function updateKonfirmasiList_Setuju($nopeng,$stsPersetujuan,$ruangUsul, $awalUsul, $akhirUsul) {
		//echo " update KonfirmasiList : ".$nopeng." : ".$stsPersetujuan." : ";
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		 $db->beginTransaction();
		 $ast_ajuan_prm = array("c_rapat_statjwbusul"=>$stsPersetujuan	
			                       ,"i_ruang"=>$ruangUsul
								   ,"d_rapat_awalpakai"=>$awalUsul
								   ,"d_rapat_akhirpakai"=>$akhirUsul		 
							   );	 
		 $where[] = "i_ruang_ajuanpakai = '".trim($nopeng)."'";
		 $db->update('e_ast_ajuan_pakai_ruang_tm',$ast_ajuan_prm, $where);
		 $db->commit();
		 //echo 'sukses KaBag<br>';
		 return 'sukses <br>';
		}
		catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}
	
	// Ina : 21-05-2008 	: Awal
	public function cekPemakaianRuangan($tglPesan, $ruangan, $jamAwal, $jamAkhir) {        		    		
		
		    $jamAkhirMin1 = $jamAkhir-1;
		    $where[]=$tglPesan;		
		    $where[]=$ruangan;		 
		    $where[]=$jamAwal;		
			$where[]=$jamAkhirMin1;		
			
			
			
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try {
			   	 $db->setFetchMode(Zend_Db::FETCH_OBJ);
				 $result = $db->fetchAll("SELECT * from e_ast_pakai_ruangitem_tm 
									 where d_ruang_ajuanpakai = ?
									 and i_ruang = ?
									 and d_rapat_awalpakai between ? and ?",$where); 
		         $jmlResult = count($result);
				
				 if($jmlResult > 0){
					 $hasilAkhir = 'ADA';
			     } else {
				   $hasilAkhir = 'TIDAK';
				 }
				 
				return $hasilAkhir;
		    }
			catch (Exception $e){
		    		echo $e->getMessage().'<br>';
				return 'gagal <br>';
			}
		
	}
	
	public function insertPesananRuang($noPengajuan, $tglPenggunaan, $ruangan, $jamAwal, $jamAkhir, $nuser) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	      for ($j = $jamAwal; $j < $jamAkhir; $j++) {  
		  
		     $db->beginTransaction();
		     $atk_mast_prm = array("i_ruang_ajuanpakai"      	=>$noPengajuan,
		                           "d_ruang_ajuanpakai"    		=>$tglPenggunaan,
							       "i_ruang"  					=>$ruangan,
							       "d_rapat_awalpakai" 			=>$j,							       
								   "i_entry"       				=>$nuser,
							       "d_entry"       				=>date("Y-m-d"));
		    
			
			
	 		 $db->insert('e_ast_pakai_ruangitem_tm',$atk_mast_prm);			 
		     $db->commit();
		  }	 
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	// Ina : 21-05-2009 : Akhir
	
	// Ina : 17-06-2008 : AWal : Pencarian data persetujuan penggunaan ruang rapat berdasarkan tgl Pesan
	public function querySetujuRuangM_ByTglPesan($pageNumber, $itemPerPage,$unitkr,$tglPesan) {
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			 
			 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 	
				// Ina : 19-05-2008 
				// Cek apakah yg login TU atau RT
				// Jika TU, maka ambil pengajuan dimana penanggungjawab ruangan yg dipesan = UnitKerjaTU
				$TU 	= $db->fetchCol("SELECT i_orgb_tu FROM e_org_0_0_tm where i_orgb = ? and i_orgb_tu is not null",$unitkr);
				$unitTU = $TU[0]; 
				
				 if($unitTU !=''){    /// Pengajuan Ruang Rapat Deputi
				    
					$where[] = $unitkr;
					$where[]= '%'.$tglPesan.'%';
										
					 if(($pageNumber==0) && ($itemPerPage==0))
					 {
						$hasilAkhir = $db->fetchOne("Select count(*)
								FROM e_ast_ajuan_pakai_ruang_tm A, e_ast_ruangan_0_tr B
								where  (c_rapat_statsetuju isnull or c_rapat_statsetuju='')
								and a.i_ruang = b.i_ruang
								and b.c_gedung_fungsi = '3'
								and b.i_orgb_tgjwbgedung = ?
								and d_rapat_pesan like ?", $where); 
					 }
					 else
					 {
				
						 $xLimit=$itemPerPage;
						 $xOffset=($pageNumber-1)*$itemPerPage;		
						 
						 $result = $db->fetchAll("SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,A.i_orgb,n_rapat_judul,q_rapat_peserta,i_rapat_mailtgjwb,i_rapat_telptgjwb,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai, 
											    a.i_ruang,c_rapat_konsumsipagi,c_rapat_konsumsisiang,c_rapat_makansiang,c_rapat_tipemakansiang,c_rapat_makanmalam,c_rapat_tipemakanmalam,i_rapat_nippimpin,i_rapat_nippesan,c_rapat_statsetuju, 
											    c_rapat_statsetuju1,d_rapat_usul,d_rapat_usuljamawal,d_rapat_usuljamakh,c_rapat_statjwbusul,d_rapat_jwbusul,A.i_entry,A.d_entry,'Peg','UNIT'
											 	FROM e_ast_ajuan_pakai_ruang_tm A, e_ast_ruangan_0_tr B
												where  (c_rapat_statsetuju isnull or c_rapat_statsetuju='')
												and a.i_ruang = b.i_ruang
												and b.i_orgb_tgjwbgedung = ?
												and a.d_rapat_pesan like ?
												and b.c_gedung_fungsi = '3'
												ORDER BY d_rapat_pesan,a.i_ruang, d_rapat_awalpakai,d_rapat_akhirpakai
												limit $xLimit offset $xOffset",$where); 
						
						 $jmlResult = count($result);
						 if($jmlResult > 0){
				 			for ($j = 0; $j < $jmlResult; $j++) {
				 				$sdm = new ast_setuju_ruangrapat_Service();
				 				$iPeg=$result[$j]->i_rapat_nippesan;
				 				$iOrg=$result[$j]->i_orgb;
				 				$namaPeg = $sdm->getNamaPegawaiRR($iPeg);
				 				$namaOrg = $sdm->getOrgRR($iOrg);
								$hasilAkhir[$j] = array("i_ruang_ajuanpakai"	=>(string)$result[$j]->i_ruang_ajuanpakai,
														"d_ruang_ajuanpakai"	=>(string)$result[$j]->d_ruang_ajuanpakai,
														"i_orgb"				=>(string)$result[$j]->i_orgb,
														"n_rapat_judul"			=>(string)$result[$j]->n_rapat_judul,
														"n_atk_tipe"			=>(string)$result[$j]->n_atk_tipe,
														"q_rapat_peserta"		=>(string)$result[$j]->q_rapat_peserta,
														"i_rapat_mailtgjwb"		=>(string)$result[$j]->i_rapat_mailtgjwb,
														"i_rapat_telptgjwb"		=>(string)$result[$j]->i_rapat_telptgjwb,
														"d_rapat_pesan"			=>(string)$result[$j]->d_rapat_pesan,
														"d_rapat_awalpakai"		=>(string)$result[$j]->d_rapat_awalpakai,
														"d_rapat_akhirpakai"	=>(string)$result[$j]->d_rapat_akhirpakai,

														"i_ruang"				=>(string)$result[$j]->i_ruang,
														"c_rapat_konsumsipagi"	=>(string)$result[$j]->c_rapat_konsumsipagi,
														"c_rapat_konsumsisiang"	=>(string)$result[$j]->c_rapat_konsumsisiang,
														"c_rapat_makansiang"	=>(string)$result[$j]->c_rapat_makansiang,
														"c_rapat_tipemakansiang"=>(string)$result[$j]->c_rapat_tipemakansiang,
														"c_rapat_makanmalam"	=>(string)$result[$j]->c_rapat_makanmalam,
														"c_rapat_tipemakanmalam"=>(string)$result[$j]->c_rapat_tipemakanmalam,
														"i_rapat_nippimpin"		=>(string)$result[$j]->i_rapat_nippimpin,
														"i_rapat_nippesan"		=>(string)$result[$j]->i_rapat_nippesan,
														"c_rapat_statsetuju"	=>(string)$result[$j]->c_rapat_statsetuju,
														
														"c_rapat_statsetuju1"	=>(string)$result[$j]->c_rapat_statsetuju1,
														"d_rapat_usul"			=>(string)$result[$j]->d_rapat_usul,
														"d_rapat_usuljamawal"	=>(string)$result[$j]->d_rapat_usuljamawal,
														"d_rapat_usuljamakh"	=>(string)$result[$j]->d_rapat_usuljamakh,
														"c_rapat_statjwbusul"	=>(string)$result[$j]->c_rapat_statjwbusul,
														"d_rapat_jwbusul"		=>(string)$result[$j]->d_rapat_jwbusul,
														"i_entry"				=>(string)$result[$j]->i_entry,
														"d_entry"				=>(string)$result[$j]->d_entry,
														"n_peg" 				=> $namaPeg,
														"n_orgb"				=>$namaOrg
										);
								}
						}
					
				////////////////////////////////////
			       }
				} else   /// Pengajuan Ruang Rapat Umum
				{
				
					$where[]= '%'.$tglPesan.'%';					
					 if(($pageNumber==0) && ($itemPerPage==0))
					 {
						        $hasilAkhir = $db->fetchOne("Select count(*)
									FROM e_ast_ajuan_pakai_ruang_tm A, e_ast_ruangan_0_tr B
									where  (c_rapat_statsetuju isnull or c_rapat_statsetuju='')
									and a.i_ruang = b.i_ruang
									and d_rapat_pesan like ?
									and b.c_gedung_fungsi = '2'", $where); 
													 
					 }
					 else
					 {				
						 $xLimit=$itemPerPage;
						 $xOffset=($pageNumber-1)*$itemPerPage;		
						 
						 $result = $db->fetchAll("SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,A.i_orgb,n_rapat_judul,q_rapat_peserta,i_rapat_mailtgjwb,i_rapat_telptgjwb,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai ,
												a.i_ruang,c_rapat_konsumsipagi,c_rapat_konsumsisiang,c_rapat_makansiang,c_rapat_tipemakansiang,c_rapat_makanmalam,c_rapat_tipemakanmalam,i_rapat_nippimpin,i_rapat_nippesan,c_rapat_statsetuju,
												c_rapat_statsetuju1,d_rapat_usul,d_rapat_usuljamawal,d_rapat_usuljamakh,c_rapat_statjwbusul,d_rapat_jwbusul,A.i_entry,A.d_entry,'Peg','UNIT'
												FROM e_ast_ajuan_pakai_ruang_tm A, e_ast_ruangan_0_tr B
												where  (c_rapat_statsetuju isnull or c_rapat_statsetuju='')
												and a.i_ruang = b.i_ruang
												and b.c_gedung_fungsi = '2'
												and d_rapat_pesan like ?
												ORDER BY d_rapat_pesan,a.i_ruang, d_rapat_awalpakai,d_rapat_akhirpakai
												limit $xLimit offset $xOffset", $where); 
						
						 $jmlResult = count($result);
						 if($jmlResult > 0){
				 			for ($j = 0; $j < $jmlResult; $j++) {
				 				$sdm = new ast_setuju_ruangrapat_Service();
				 				$iPeg=$result[$j]->i_rapat_nippesan;
				 				$iOrg=$result[$j]->i_orgb;
				 				$namaPeg = $sdm->getNamaPegawaiRR($iPeg);
				 				$namaOrg = $sdm->getOrgRR($iOrg);
								$hasilAkhir[$j] = array("i_ruang_ajuanpakai"	=>(string)$result[$j]->i_ruang_ajuanpakai,
														"d_ruang_ajuanpakai"	=>(string)$result[$j]->d_ruang_ajuanpakai,
														"i_orgb"				=>(string)$result[$j]->i_orgb,
														"n_rapat_judul"			=>(string)$result[$j]->n_rapat_judul,
														"n_atk_tipe"			=>(string)$result[$j]->n_atk_tipe,
														"q_rapat_peserta"		=>(string)$result[$j]->q_rapat_peserta,
														"i_rapat_mailtgjwb"		=>(string)$result[$j]->i_rapat_mailtgjwb,
														"i_rapat_telptgjwb"		=>(string)$result[$j]->i_rapat_telptgjwb,
														"d_rapat_pesan"			=>(string)$result[$j]->d_rapat_pesan,
														"d_rapat_awalpakai"		=>(string)$result[$j]->d_rapat_awalpakai,
														"d_rapat_akhirpakai"	=>(string)$result[$j]->d_rapat_akhirpakai,
														"i_ruang"				=>(string)$result[$j]->i_ruang,
														"c_rapat_konsumsipagi"	=>(string)$result[$j]->c_rapat_konsumsipagi,
														"c_rapat_konsumsisiang"	=>(string)$result[$j]->c_rapat_konsumsisiang,
														"c_rapat_makansiang"	=>(string)$result[$j]->c_rapat_makansiang,
														"c_rapat_tipemakansiang"=>(string)$result[$j]->c_rapat_tipemakansiang,
														"c_rapat_makanmalam"	=>(string)$result[$j]->c_rapat_makanmalam,
														"c_rapat_tipemakanmalam"=>(string)$result[$j]->c_rapat_tipemakanmalam,
														"i_rapat_nippimpin"		=>(string)$result[$j]->i_rapat_nippimpin,
														"i_rapat_nippesan"		=>(string)$result[$j]->i_rapat_nippesan,
														"c_rapat_statsetuju"	=>(string)$result[$j]->c_rapat_statsetuju,
														
														"c_rapat_statsetuju1"	=>(string)$result[$j]->c_rapat_statsetuju1,
														"d_rapat_usul"			=>(string)$result[$j]->d_rapat_usul,
														"d_rapat_usuljamawal"	=>(string)$result[$j]->d_rapat_usuljamawal,
														"d_rapat_usuljamakh"	=>(string)$result[$j]->d_rapat_usuljamakh,
														"c_rapat_statjwbusul"	=>(string)$result[$j]->c_rapat_statjwbusul,
														"d_rapat_jwbusul"		=>(string)$result[$j]->d_rapat_jwbusul,
														"i_entry"				=>(string)$result[$j]->i_entry,
														"d_entry"				=>(string)$result[$j]->d_entry,
														"n_peg" 				=> $namaPeg,
														"n_orgb"				=>$namaOrg
										);
								}
						}
					}
				}
						return $hasilAkhir;
			
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	
	// Ina : 17-06-2008 : Akhir
}	
?>