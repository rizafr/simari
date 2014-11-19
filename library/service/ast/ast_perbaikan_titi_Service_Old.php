<?php

class ast_perbaikan_titi_Service {
    private static $instance;
   
    private function __construct() {
       //echo 'I am constructed';
    }

    // The singleton method
    public static function getInstance() {
       if (!isset(self::$instance)) {
           $c = __CLASS__;
           self::$instance = new $c;
       }
       return self::$instance;
    }
	
	
	// ----------- get No inventaris untuk perbaikan TI ===========================
	public function getAsetTIList($pageNumber,$itemPerPage,$kdunit) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   $where[] = $kdunit;
	   $where[] = $kdunit;
	   
	   try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasil = $db->fetchAll("Select  A.d_anggaran, A.c_barang, A.i_aset, A.d_perolehan,  B.ur_sskel, 
										A.i_komputer_macaddress
										FROM  E_AST_KOMPUTER_0_TR A , E_AST_SSKEL_0_TR B
										Where A.C_Unit_Kerja = ?
										And B.kd_gol = substr(A.c_barang,1,1)
										And B.kd_bid = substr(A.c_barang,2,2)
										And B.kd_kel = substr(A.c_barang,4,2)
										And B.kd_skel = substr(A.c_barang,6,2)
										And B.kd_sskel = substr(A.c_barang,8,3)
										And B.kd_gol = '2'
										And B.kd_bid = '12'
										UNION
										Select  A.d_anggaran, A.c_barang, A.i_aset, A.d_perolehan,  B.ur_sskel, A.n_hw 
										from E_AST_HARDWARE_0_TM A, E_AST_SSKEL_0_TR B
										Where A.i_orgb = ?
										And B.kd_gol = substr(A.c_barang,1,1)
										And B.kd_bid = substr(A.c_barang,2,2)
										And B.kd_kel = substr(A.c_barang,4,2)
										And B.kd_skel = substr(A.c_barang,6,2)
										And B.kd_gol = '2'
										And B.kd_bid = '12'
										", $where);
				$hasilAkhir = count($hasil);
			}else {
			    
				$xLimit=$itemPerPage;
			    $xOffset=($pageNumber-1)*$itemPerPage;
			 
				$result = $db->fetchAll("Select  A.d_anggaran, A.c_barang, A.i_aset, A.d_perolehan,  B.ur_sskel, 
										A.i_komputer_macaddress
										FROM  E_AST_KOMPUTER_0_TR A , E_AST_SSKEL_0_TR B
										Where A.C_Unit_Kerja = ?
										And B.kd_gol = substr(A.c_barang,1,1)
										And B.kd_bid = substr(A.c_barang,2,2)
										And B.kd_kel = substr(A.c_barang,4,2)
										And B.kd_skel = substr(A.c_barang,6,2)
										And B.kd_sskel = substr(A.c_barang,8,3)
										UNION
										Select  A.d_anggaran, A.c_barang, A.i_aset, A.d_perolehan,  B.ur_sskel, A.n_hw 
										from E_AST_HARDWARE_0_TM A, E_AST_SSKEL_0_TR B
										Where A.i_orgb = ?
										And B.kd_gol = substr(A.c_barang,1,1)
										And B.kd_bid = substr(A.c_barang,2,2)
										And B.kd_kel = substr(A.c_barang,4,2)
										And B.kd_skel = substr(A.c_barang,6,2)
										limit $xLimit offset $xOffset", $where);
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
			 
					$hasilAkhir[$j] = array("d_anggaran"       			=>(string)$result[$j]->d_anggaran,
											"c_barang"           		=>(string)$result[$j]->c_barang, 
											"i_aset"         			=>(string)$result[$j]->i_aset, 
											"d_perolehan"          		=>(string)$result[$j]->d_perolehan,
											"ur_sskel"         			=>(string)$result[$j]->ur_sskel,
											"i_komputer_macaddress"    	=>(string)$result[$j]->i_komputer_macaddress); 
				}
			}					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	//Insert Pengajuan Perbaikan TI
	public function insertPengajuanPerbaikanAsetTI(array $data)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		//$where = $data['noPengajuan'];
		try {
			$db->beginTransaction();
			
				$prmInsert = array("i_barang_ajuanbaik"		=>$data['noPengajuan'],
									"d_barang_ajuanbaik"	=>date("Y-m-d"),
									"i_orgb"				=>$data['unitktr'],
									"i_peg_nip"				=>$data['nipPemberi'],
									"e_barang_perbaikan"	=>$data['mslh'],
									"d_anggaran"			=>$data['thnAnggaran'],
									"c_barang"				=>$data['kodeBarang'],
									"i_aset"				=>$data['noAsset'],
									"d_perolehan"			=>$data['tglprlh'],
									"i_entry"				=>$data['i_entry'],
									"d_entry"				=>date("Y-m-d"));
				
				$prmInsert2 = array("i_barang_ajuanbaik"		=>$data['noPengajuan'],
									"i_entry"				=>$data['i_entry'],
									"d_entry"				=>date("Y-m-d"));
			$db->insert('e_ast_ajuanbaikti_0_tm',$prmInsert);
			$db->insert('e_ast_ajuanbaikti_item_tm',$prmInsert2);
			$db->commit();
			return 'sukses <br>';
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	
	//Ngambil data yang ssudah melakukan pengajuan perbaikan TI
	public function getPengajuanPerbaikanTIList($pageNumber,$itemPerPage,$kdunit)
	//public function getPengajuanPerbaikanTIList($kdunit)
	{
	  try {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
	    $where[]=$kdunit;
		
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilResult = $db->fetchOne("Select count(*) from E_AST_AJUANBAIKTI_0_TM where  c_setuju_statustu is null
										And i_orgb = ? ",$where);
			}
		
			else 
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				$result = $db->fetchAll("Select * from E_AST_AJUANBAIKTI_0_TM where  c_setuju_statussi is null
										And i_orgb = ? limit $xLimit offset $xOffset",$where );
				
				$jmlResult = count($result);
				for($j=0; $j<$jmlResult; $j++)
				{
					$hasilResult[$j] = array("i_barang_ajuanbaik"	=>(string)$result[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" =>(string)$result[$j]->d_barang_ajuanbaik,
											 "d_anggaran"	=>(string)$result[$j]->d_anggaran,
											 "c_barang"	=>(string)$result[$j]->c_barang,
											 "i_aset"	=>(string)$result[$j]->i_aset,
											 "i_peg_nip"	=>(string)$result[$j]->i_peg_nip,
											 "d_perolehan"	=>(string)$result[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$result[$j]->e_barang_perbaikan
											 );
				}
			}
			
			return $hasilResult;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	
	//============== LISST persetujuan BAGIAN  TU =========================
	//public function getPersetujuanTUList($pageNumber,$itemPerPage,$kdunit) // UNTUK PAGING
	public function getPersetujuanTUList($kdunit)
	{
	  try {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
	    $where[]=$kdunit;
		
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			/* if(($pageNumber==0) && ($itemPerPage==0))
			{
				$setujuList = $db->fetchOne("Select count(*) from E_AST_AJUANBAIKTI_0_TM where  c_setuju_statustu is null
										And i_orgb = ? ",$where);
			}
		
			else 
			{ */
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				$setuju = $db->fetchAll("Select * from E_AST_AJUANBAIKTI_0_TM where  c_setuju_statustu is null
										And i_orgb = ? ",$where );
				
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{
					$setujuList[$j] = array("i_barang_ajuanbaik"	=>(string)$setuju[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" =>(string)$setuju[$j]->d_barang_ajuanbaik,
											 "d_anggaran"	=>(string)$setuju[$j]->d_anggaran,
											 "c_barang"	=>(string)$setuju[$j]->c_barang,
											 "i_aset"	=>(string)$setuju[$j]->i_aset,
											 "i_peg_nip"	=>(string)$setuju[$j]->i_peg_nip,
											 "e_barang_perbaikan"	=>(string)$setuju[$j]->e_barang_perbaikan
											 );
				}
			//}
			
			return $setujuList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	//============== LISST persetujuan BAGIAN  SI =========================
	//public function getPersetujuanSIList($kdunit)
	public function getPersetujuanSIList()
	{
	  try {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		//$where[] = "Y";
	    $where[] = $kdunit;
		
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			/*if(($pageNumber==0) && ($itemPerPage==0))
			{
				$setujuSIList = $db->fetchOne("Select count(*) from E_AST_AJUANBAIKTI_0_TM where  c_setuju_statustu is null
										And i_orgb = ? ",$where);
			}
		
			else 
			{*/
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				$setuju = $db->fetchAll("Select * from E_AST_AJUANBAIKTI_0_TM where  
										c_setuju_statussi is null");
				
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{
				//echo "TEST serv ".$setuju[$j]->i_barang_ajuanbaik;
					$setujuSIList[$j] = array("i_barang_ajuanbaik"	=>(string)$setuju[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" =>(string)$setuju[$j]->d_barang_ajuanbaik,
											 "d_anggaran"	=>(string)$setuju[$j]->d_anggaran,
											 "c_barang"	=>(string)$setuju[$j]->c_barang,
											 "i_aset"	=>(string)$setuju[$j]->i_aset,
											 "i_peg_nip"	=>(string)$setuju[$j]->i_peg_nip,
											 "d_perolehan"	=>(string)$setuju[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$setuju[$j]->e_barang_perbaikan
											 );
				}
			//}
			
			return $setujuSIList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	//===========   Ngambil Nama pegawai berdasar kan NIP  =============================
	function getNamaPegawai($nip)
	{
		try {
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
		    $where[]=$nip;
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			$result = $db->fetchAll("Select a.n_peg,a.i_orgb, a.c_unit_kerja, b.n_jabatan 
									from e_sdm_pegawai_0_tm A, e_sdm_jabatan_0_tm B 
									where a.i_peg_nip = b.i_peg_nip
									and a.c_unit_kerja = b.c_jabatan
									and a.i_peg_nip = ? ",$where);
			$jmlResult = count($result);
			for($j=0; $j<$jmlResult; $j++)
			{
				$hasilResult[$j] = array("n_peg"	=>(string)$result[$j]->n_peg,
										 "n_jabatan" =>(string)$result[$j]->n_jabatan,
										 "c_unit_kerja"	=>(string)$result[$j]->c_unit_kerja
										 );
			}
			return $hasilResult;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}

	// ========  Update pengajuan perbaikan TI  =================================
	public function updatePengajuanPerbaikanAsetTI(array $data)
	{
		try {
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			
			$db->beginTransaction();
			$prmUpdate = array("i_orgb"  		    =>$data['unitktr'],
						       "d_anggaran"  		=>$data['thnAnggaran'],
							   "c_barang"  		 	=>$data['kodeBarang'],
							   "i_aset"  	 	 	=>$data['noAsset'],
							   "i_peg_nip"  	 	=>$data['nipPemberi'],
							   "e_barang_perbaikan"	=>$data['mslh'],
						       "i_entry"       		=>$data['i_entry'],
						       "d_entry"       		=>date("Y-m-d"));
			$where[] = "i_barang_ajuanbaik  =  '".$data['noPengajuan']."'";
		
			$db->update('e_ast_ajuanbaikti_0_tm',$prmUpdate, $where);
			$db->commit();
			return 'sukses <br>';
		
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	// ===================== Delete Pengajuan Perbaikan TI =============================
	public function deletePengajuanPerbaikanAsetTI($noPengajuan)
	{
		try
		{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$db->beginTransaction();
			$where[] = "i_barang_ajuanbaik = '".$noPengajuan."'";
			
			$db->delete('e_ast_ajuanbaikti_0_tm', $where);
			$db->commit();
			return 'sukses <br>';
		}catch (Exception $e){
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}
	
	// ======================== PROSES PERSETUJUAN PERBAIKAN TI  BAGIAN TU ==============================
	public function prosesPersetujuanTU($noPengajuan)
	{
		try
		{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$db->beginTransaction();
			$where[] = "i_barang_ajuanbaik = '".$noPengajuan."'";
			$paramProses = array("c_setuju_statustu"	=>"Y",
								  "d_setuju_tu"=>date("Y-m-d"));
			
			$db->update('e_ast_ajuanbaikti_0_tm',$paramProses, $where);
			$db->commit();
			return 'sukses <br>';
		}catch (Exception $e){
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}
	
	// ======================== PROSES PERSETUJUAN PERBAIKAN TI BAGIAN SI ==============================
	public function prosesPersetujuanSI($data)
	{
		try
		{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$db->beginTransaction();
			$alasan = $data['alasan'];
			/* if($alasan !='' && $alasan!=null){ */
				//status ditolak
				$paramProses = array("c_setuju_statussi"	=>$data['status'],
									"e_alasan"	=>$alasan,
									"d_setuju_si"=>date("Y-m-d"));
			/* }else{		
				// status disetujui
				$paramProses = array("c_setuju_statussi"	=>"Y",
									"d_setuju_si"=>date("Y-m-d"));
			} */
			
			$where[] = "i_barang_ajuanbaik = '".$data['noPengajuan']."'";
			$db->update('e_ast_ajuanbaikti_0_tm',$paramProses, $where);
			$db->commit();
			return 'sukses <br>';
		}catch (Exception $e){
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}
	
	//============== LISST persetujuan BAGIAN  SI =========================
	public function getPenerimaanBarangList($pageNumber,$itemPerPage)
	{
	  try {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$where[] = "Y";
		//$where[] = $kdunit;
		//echo "kdunit srvc = ".$kdunit;
		
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$setujuSIList = $db->fetchOne("Select count(*) from E_AST_AJUANBAIKTI_0_TM where  c_setuju_statussi = ?
										   and i_barang_penerimaan is null ",$where);
			}
		
			else 
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				$setuju = $db->fetchAll("Select * from E_AST_AJUANBAIKTI_0_TM where  c_setuju_statussi = ?
										   and i_barang_penerimaan is null 
										   limit $xLimit offset $xOffset",$where)	;
				
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{
				//echo "TEST serv ".$setuju[$j]->i_barang_ajuanbaik;
					$setujuSIList[$j] = array("i_barang_ajuanbaik"	=>(string)$setuju[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" =>(string)$setuju[$j]->d_barang_ajuanbaik,
											 "d_anggaran"	=>(string)$setuju[$j]->d_anggaran,
											 "c_barang"	=>(string)$setuju[$j]->c_barang,
											 "i_aset"	=>(string)$setuju[$j]->i_aset,
											 "i_peg_nip"	=>(string)$setuju[$j]->i_peg_nip,
											 "d_perolehan"	=>(string)$setuju[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$setuju[$j]->e_barang_perbaikan
											 );
				}
			}
			
			return $setujuSIList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	
	//Insert Penerimaan Barang Perbaikan TI
	public function prosesPenerimaan(array $data)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		//echo "no pengajuan service = ".$data['noPengajuan'];
		$noPengajuan = $data['noPengajuan'];
	    $where[] = "i_barang_ajuanbaik = '".$noPengajuan."'";
		try {
			$db->beginTransaction();
				$prminsert = array("i_barang_penerimaan"	=>$data['noPenerimaan'],
								   "i_peg_nipterima"		=>$data['nipPenerima'],
								   "i_peg_nipserah"			=>$data['nipPemberi'],
								   "i_barang_ajuanbaik"		=>$noPengajuan,
								   "d_barang_penerimaan"	=>date("Y-m-d"),
								   "i_entry"				=>$data['i_entry'],
								   "d_entry"				=>date("Y-m-d")
								   );
			$db->insert('e_ast_terimabaikti_0_tm',$prminsert);
			
				$prmupdate = array("i_peg_nip"				=>$data['nipPemberi'],
								   "i_peg_nipterima"		=>$data['nipPenerima'],
								   "i_barang_penerimaan"	=>$data['noPenerimaan']);
			$db->update('e_ast_ajuanbaikti_0_tm',$prmupdate,$where);
				
			$db->commit();
			return 'sukses <br>';
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	
	//============== LISST Pengembalian Perbaikan TI =========================
	public function getPengembalianBarangList($pageNumber,$itemPerPage)
	{
	  try {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$where = "Y";
		$where = "N";
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$setujuSIList = $db->fetchAll("Select a.i_barang_ajuanbaik,a.d_barang_ajuanbaik,a.i_barang_penerimaan,
										a.d_anggaran,a.c_barang,a.i_aset,a.i_peg_nip,a.d_perolehan,
										a.e_barang_perbaikan,a.i_orgb from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B  
										where  
										a.i_barang_penerimaan is not null
										and a.i_barang_pengembalian is null
										and a.i_barang_ajuanbaik = b.i_barang_ajuanbaik
										and (b.c_status_perbaikan ='Y' or b.c_setuju_sparepart='N')
										");
				$hasilList = count($setujuSIList);
			}
		
			else 
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				//$setuju = $db->fetchAll("Select * from E_AST_AJUANBAIKTI_0_TM where  i_barang_penerimaan is not null
										   //and i_barang_pengembalian is null order by i_barang_ajuanbaik")	;
				$setuju = $db->fetchAll("Select a.i_barang_ajuanbaik,a.d_barang_ajuanbaik,a.i_barang_penerimaan,
										a.d_anggaran,a.c_barang,a.i_aset,a.i_peg_nip,a.d_perolehan,
										a.e_barang_perbaikan,a.i_orgb from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B  
										where  
										a.i_barang_penerimaan is not null
										and a.i_barang_pengembalian is null
										and a.i_barang_ajuanbaik = b.i_barang_ajuanbaik
										and (b.c_status_perbaikan ='Y' or b.c_setuju_sparepart='N')
										limit $xLimit offset $xOffset");
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{
				//echo "TEST serv ".$setuju[$j]->i_barang_ajuanbaik;
					$hasilList[$j] = array("i_barang_ajuanbaik"		=>(string)$setuju[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" 	=>(string)$setuju[$j]->d_barang_ajuanbaik,
											 "i_orgb"				=>(string)$setuju[$j]->i_orgb,
											 "i_barang_penerimaan"	=>(string)$setuju[$j]->i_barang_penerimaan,
											 "d_anggaran"			=>(string)$setuju[$j]->d_anggaran,
											 "c_barang"				=>(string)$setuju[$j]->c_barang,
											 "i_aset"				=>(string)$setuju[$j]->i_aset,
											 "i_peg_nip"			=>(string)$setuju[$j]->i_peg_nip,
											 "d_perolehan"			=>(string)$setuju[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$setuju[$j]->e_barang_perbaikan
											 );
				}
			}
			
			return $hasilList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	//============== LISST Laporan Perbaikan TI =========================
	public function getDataPerbaikanByNoPengajuan($noPengajuan)
	{
	  try {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$where[] = $noPengajuan;
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			/*if(($pageNumber==0) && ($itemPerPage==0))
			{
				$setujuSIList = $db->fetchOne("Select count(*) from E_AST_AJUANBAIKTI_0_TM where  c_setuju_statustu is null
										And i_orgb = ? ",$where);
			}
		
			else 
			{*/
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				$setuju = $db->fetchAll("Select * from E_AST_AJUANBAIKTI_0_TM where  i_barang_ajuanbaik = ? ",$where)	;
				
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{
				//echo "TEST serv ".$setuju[$j]->i_barang_ajuanbaik;
					$hasilList[$j] = array("i_barang_ajuanbaik"		=>(string)$setuju[$j]->i_barang_ajuanbaik,
											"d_barang_ajuanbaik" 	=>(string)$setuju[$j]->d_barang_ajuanbaik,
											"i_orgb"				=>(string)$setuju[$j]->i_orgb,
											"i_barang_penerimaan"	=>(string)$setuju[$j]->i_barang_penerimaan,
											"i_barang_pengembalian"	=>(string)$setuju[$j]->i_barang_pengembalian,
											 "d_anggaran"			=>(string)$setuju[$j]->d_anggaran,
											 "c_barang"				=>(string)$setuju[$j]->c_barang,
											 "i_aset"				=>(string)$setuju[$j]->i_aset,
											 "i_peg_nip"			=>(string)$setuju[$j]->i_peg_nip,
											 "d_setuju_tu"			=>(string)$setuju[$j]->d_setuju_tu,
											 "d_setuju_si"			=>(string)$setuju[$j]->d_setuju_si,
											 "i_peg_nipterima"		=>(string)$setuju[$j]->i_peg_nipterima,
											 "d_perolehan"			=>(string)$setuju[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$setuju[$j]->e_barang_perbaikan
											 );
				}
			//}
			
			return $hasilList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	//Insert Laporan (Pengembalian)  Barang Perbaikan TI
	public function prosesPengembalian(array $data)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		//echo "no pengajuan service = ".$data['noPengajuan'];
		$noPengajuan = $data['noPengajuan'];
	    $where[] = "i_barang_ajuanbaik = '".$noPengajuan."'";
		try {
			$db->beginTransaction();
			
			$prmUpdate = array("i_barang_pengembalian"	=>$data['noPengembalian']);
			$db->update('e_ast_ajuanbaikti_0_tm',$prmUpdate,$where);
			
			$prmInsert = array("i_barang_pengembalian"=>$data['noPengembalian'],
							   "d_barang_pengembalian"=>date('Y-m-d'),
							   "i_barang_ajuanbaik"=>$noPengajuan,
							   "i_peg_nipterima"=>$data['nipPenerima'],
							   "i_peg_nipserah"=>$data['nipPemberi'],
							   "i_entry"=>$data['i_entry'],
							   "d_entry"=>date('Y-m-d')
							   );
			$db->insert('e_ast_kembalibaikti_0_tm',$prmInsert);
			
			$db->commit();
			return 'sukses <br>';
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	//============== LISST Pengerjaan Perbaikan TI =========================
	public function getPengerjaanBarangList($pageNumber,$itemPerPage)
	{
	  try {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$where [] = "Y";
		$where [] = "N";
		$where [] = "Y";
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasil = $db->fetchAll("Select distinct a.i_barang_ajuanbaik,a.d_barang_ajuanbaik,a.i_barang_penerimaan,
										a.d_anggaran,a.c_barang,a.i_aset,a.i_peg_nip,a.d_perolehan,
										a.e_barang_perbaikan,a.i_orgb from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 
										and a.c_setuju_statussi = ? 
										and ((b.c_status_perbaikan = ? and b.c_terima_sparepart = ?) or b.c_status_perbaikan is null)
										", $where)	;
				$hasilList = count($hasil);
			}
			else 
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				/*$setuju = $db->fetchAll("Select * from E_AST_AJUANBAIKTI_0_TM where  
										 i_barang_penerimaan is not null
										 and i_barang_pengembalian is null
										 order by i_barang_ajuanbaik")	;*/
				$setuju = $db->fetchAll("Select distinct a.i_barang_ajuanbaik,a.d_barang_ajuanbaik,a.i_barang_penerimaan,
										a.d_anggaran,a.c_barang,a.i_aset,a.i_peg_nip,a.d_perolehan,
										a.e_barang_perbaikan,a.i_orgb from E_AST_AJUANBAIKTI_0_TM A, E_AST_AJUANBAIKTI_ITEM_TM B
										where  
										a.i_barang_ajuanbaik = b.i_barang_ajuanbaik 
										and a.c_setuju_statussi = ? 
										and ((b.c_status_perbaikan = ? and b.c_terima_sparepart = ?) or b.c_status_perbaikan is null)
										limit $xLimit offset $xOffset", $where)	;
				
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{
				//echo "TEST serv ".$setuju[$j]->i_barang_ajuanbaik;
					$hasilList[$j] = array("i_barang_ajuanbaik"		=>(string)$setuju[$j]->i_barang_ajuanbaik,
											 "d_barang_ajuanbaik" 	=>(string)$setuju[$j]->d_barang_ajuanbaik,
											 "i_orgb"				=>(string)$setuju[$j]->i_orgb,
											 "i_barang_penerimaan"	=>(string)$setuju[$j]->i_barang_penerimaan,
											 "d_anggaran"			=>(string)$setuju[$j]->d_anggaran,
											 "c_barang"				=>(string)$setuju[$j]->c_barang,
											 "i_aset"				=>(string)$setuju[$j]->i_aset,
											 "i_peg_nip"			=>(string)$setuju[$j]->i_peg_nip,
											 "d_perolehan"			=>(string)$setuju[$j]->d_perolehan,
											 "e_barang_perbaikan"	=>(string)$setuju[$j]->e_barang_perbaikan
											 );
				}
			}
			
			return $hasilList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	//Insert Pengerjaan Perbaikan Barang TI
	public function prosesPengerjaan(array $data)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		$where[] = "i_barang_ajuanbaik = '".$data['noPengajuan']."'";
		try {
			$db->beginTransaction();
			
				$prmInsert = array("d_awal_perbaikan"		=>$data['tglPengerjaan'],
								   "d_akhir_perbaikan"		=>$data['tglSelesai'],
								   "q_jam_perbaikan"		=>$data['jamPengerjaan'],
								   "e_saran_sparepart"		=>$data['spareParts'],
								   "e_saran_pihakketiga"	=>$data['pihakKetiga'],
								   "e_tindakan_perbaikan"	=>$data['tindakan'],
								   "c_status_perbaikan"		=>$data['status'],
								   "i_peg_helpdesk"			=>$data['nip_helpdesk'],
								   "i_entry"				=>$data['i_entry'],
								   "d_entry"				=>date("Y-m-d"));
			//$db->insert('e_ast_ajuanbaikti_item_tm',$prmInsert);
			$db->update('e_ast_ajuanbaikti_item_tm',$prmInsert,$where);
			$db->commit();
			return 'sukses <br>';
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	public function getListPegawaibyOrgAll($pageNumber,$itemPerPage,$unitkr) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $where[] = $unitkr;
		 $where[] = $unitkr;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_sdm_pegawai_0_tm where i_orgb=? or c_unit_kerja=?",$where);
			/* $hasil = $db->fetchAll("SELECT i_peg_nip,n_peg,n_jabatan,a.i_orgb,n_orgb 
									FROM e_sdm_pegawai_0_tm a, e_org_0_0_tm b
									where a.i_orgb=? and a.i_orgb=b.i_orgb or a.c_unit_kerja=?",$where);
			$hasilAkhir = count($hasil); */
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 /* $result = $db->fetchAll("SELECT i_peg_nip,n_peg,n_jabatan,a.i_orgb,n_orgb 
									FROM e_sdm_pegawai_0_tm a, e_org_0_0_tm b
									where a.i_orgb=? and a.i_orgb=b.i_orgb 
									ORDER BY i_peg_nip
									limit $xLimit offset $xOffset",$where); */ 
			$result = $db->fetchAll("SELECT i_peg_nip,n_peg,n_jabatan,i_orgb
									FROM e_sdm_pegawai_0_tm 
									where i_orgb=? or c_unit_kerja=?
									ORDER BY i_peg_nip
									limit $xLimit offset $xOffset",$where);
						 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_peg_nip"           =>(string)$result[$j]->i_peg_nip,
									"n_peg"           	 =>(string)$result[$j]->n_peg,
									"n_jabatan"          =>(string)$result[$j]->n_jabatan,
									"i_orgb"            =>(string)$result[$j]->i_orgb,
									"n_orgb"            =>(string)$result[$j]->n_orgb);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	//===========   Ngambil Nama Banarang  =============================
	function getNamaBarang($kdbr)
	{
		try {
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$kd_gol = substr($kdbr,0,1);
			$kd_bid = substr($kdbr,1,2);
			$kd_kel = substr($kdbr,3,2);
			$kd_skel = substr($kdbr,5,2);
			$kd_sskel = substr($kdbr,7,3);
			
		    $where[]=$kd_gol;
			$where[]=$kd_bid;
			$where[]=$kd_kel;
			$where[]=$kd_skel;
			$where[]=$kd_sskel;
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			$result = $db->fetchAll("Select * from e_ast_sskel_0_tr 
									where kd_gol = ? 
									and kd_bid = ?
									and kd_kel = ?
									and kd_skel = ?
									and kd_sskel = ?
									",$where);
			$jmlResult = count($result);
			for($j=0; $j<$jmlResult; $j++)
			{
				$hasilResult[$j] = array("ur_sskel"	=>(string)$result[$j]->ur_sskel,
										 "satuan"	=>(string)$result[$j]->satuan
										 );
			}
			return $hasilResult;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	//===========   Ngambil Nama Banarang  =============================
	function getMcAddress($data)
	{
		try {
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$kd_gol = substr($kdbr,0,1);
			$kd_bid = substr($kdbr,1,2);
			$kd_kel = substr($kdbr,3,2);
			$kd_skel = substr($kdbr,5,2);
			$kd_sskel = substr($kdbr,7,3);
			
		    $where[]=$data['thnang'];
			$where[]=$data['kdbr'];
			$where[]=$data['noaset'];
			$where[]=$data['tgl'];
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			$result = $db->fetchAll("Select * from e_ast_komputer_0_tr 
									where d_anggaran = ? 
									and c_barang = ?
									and i_aset = ?
									and d_perolehan = ?
									",$where);
			$jmlResult = count($result);
			for($j=0; $j<$jmlResult; $j++)
			{
				$hasilResult[$j] = array("i_komputer_macaddress"	=>(string)$result[$j]->i_komputer_macaddress,
										 "i_peg_nip"	=>(string)$result[$j]->i_peg_nip,
										 "c_unit_kerja"	=>(string)$result[$j]->c_unit_kerja,
										 "i_ruang"	=>(string)$result[$j]->i_ruang,
										 "i_komputer_serialpc"	=>(string)$result[$j]->i_komputer_serialpc,
										 "i_komputer_serialwindow"	=>(string)$result[$j]->i_komputer_serialwindow,
										 "e_pc"	=>(string)$result[$j]->e_pc
										 );
			}
			return $hasilResult;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	public function getListPegawaiByUnit($pageNumber,$itemPerPage,$kdunit) {
		echo 'kode unitnya ahhhhh'.$kdunit; 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   $where[] = $kdunit;
	   $where[] = $kdunit;
	   $where[] = $kdunit;
	   $where[] = $kdunit;
	   //echo " kdunti = ".$kdunit;
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasil = $db->fetchAll("SELECT a.i_peg_nip,a.n_peg,b.n_jabatan,a.c_unit_kerja
									FROM e_sdm_pegawai_0_tm A, e_sdm_jabatan_0_tm B 
									where 
									a.i_peg_nip = b.i_peg_nip
									and a.c_unit_kerja = b.c_jabatan
									and (a.i_orgb = ? or a.c_unit_kerja=?)
									union
									select a.i_peg_nip, n_peg, NULL, a.c_unit_kerja 
									from  e_sdm_pegawai_0_tm A 
									where  
									not EXISTS(select * from  e_sdm_jabatan_0_tm B
									          where a.i_peg_nip = b.i_peg_nip)
									and (a.c_unit_kerja = ? or a.i_orgb = ?)"
									,$where );
			$hasilAkhir = count($hasil);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 /* $result = $db->fetchAll("SELECT i_peg_nip,n_peg,n_jabatan,a.i_orgb,n_orgb 
									FROM e_sdm_pegawai_0_tm a, e_org_0_0_tm b
									where a.i_orgb = ? and a.i_orgb=b.i_orgb
									ORDER BY i_peg_nip
									limit $xLimit offset $xOffset",$where); */ 
			$result = $db->fetchAll("SELECT a.i_peg_nip,a.n_peg,b.n_jabatan,a.c_unit_kerja
									FROM e_sdm_pegawai_0_tm A, e_sdm_jabatan_0_tm B 
									where 
									a.i_peg_nip = b.i_peg_nip
									and a.c_unit_kerja = b.c_jabatan
									and (a.i_orgb = ? or a.c_unit_kerja=?)
									union
									select a.i_peg_nip, n_peg, NULL, a.c_unit_kerja 
									from  e_sdm_pegawai_0_tm A 
									where  
									not EXISTS(select * from  e_sdm_jabatan_0_tm B
									          where a.i_peg_nip = b.i_peg_nip)
									and (a.c_unit_kerja = ? or a.i_orgb = ?)
									order by n_peg 
									limit $xLimit offset $xOffset",$where);
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_peg_nip"         =>(string)$result[$j]->i_peg_nip,
									"n_peg"           	 	=>(string)$result[$j]->n_peg,
									"n_jabatan"          	=>(string)$result[$j]->n_jabatan,
									"i_orgb"            	=>(string)$result[$j]->c_unit_kerja
									);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	
	
	public function getDataPengajuan($noPengajuan){
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$where[] = $noPengajuan;
		//echo "nopengajuan = ".$noPengajuan;
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$result = $db->fetchAll("SELECT * FROM e_ast_ajuanbaikti_0_tm 
									where i_barang_ajuanbaik = ? ",$where);

			$jmlResult = count($result);

			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_barang_ajuanbaik"	=>(string)$result[$j]->i_barang_ajuanbaik,
										"d_barang_ajuanbaik"    =>(string)$result[$j]->d_barang_ajuanbaik,
										"i_peg_nip"          	=>(string)$result[$j]->i_peg_nip,
										"e_barang_perbaikan"          	=>(string)$result[$j]->e_barang_perbaikan,
										"d_anggaran"          	=>(string)$result[$j]->d_anggaran,
										"c_barang"          	=>(string)$result[$j]->c_barang,
										"i_aset"          	=>(string)$result[$j]->i_aset,
										"d_perolehan"          	=>(string)$result[$j]->d_perolehan,
										"i_barang_penerimaan"          	=>(string)$result[$j]->i_barang_penerimaan,
										"i_peg_nipterima"          	=>(string)$result[$j]->i_peg_nipterima,
										"i_orgb"            	=>(string)$result[$j]->i_orgb
										);
			}	
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	
	public function getDataPengajuanItem($noPengajuan){
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$where[] = $noPengajuan;
		//echo "nopengajuan = ".$noPengajuan;
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$result = $db->fetchAll("SELECT * FROM e_ast_ajuanbaikti_item_tm 
									where i_barang_ajuanbaik = ? ",$where);

			$jmlResult = count($result);

			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_barang_ajuanbaik"	=>(string)$result[$j]->i_barang_ajuanbaik,
										"d_awal_perbaikan"    	=>(string)$result[$j]->d_awal_perbaikan,
										"d_akhir_perbaikan"     =>(string)$result[$j]->d_akhir_perbaikan,
										"q_jam_perbaikan"    	=>(string)$result[$j]->q_jam_perbaikan,
										"i_peg_helpdesk"        =>(string)$result[$j]->i_peg_helpdesk,
										"c_status_perbaikan"    =>(string)$result[$j]->c_status_perbaikan,
										"e_saran_sparepart"     =>(string)$result[$j]->e_saran_sparepart,
										"e_saran_pihakketiga"   =>(string)$result[$j]->e_saran_pihakketiga,
										"e_tindakan_perbaikan"  =>(string)$result[$j]->e_tindakan_perbaikan,
										"c_setuju_sparepart"    =>(string)$result[$j]->c_setuju_sparepart,
										"i_minta_sparepart"     =>(string)$result[$j]->i_minta_sparepart
										);
			}	
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	
	public function getNamaPegawaiSerah($noPenerimaan){
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		//echo "nopenerimaan srvc = ".$noPenerimaan."<br>";
		$where[] = $noPenerimaan;
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$result = $db->fetchAll("SELECT * FROM e_ast_terimabaikti_0_tm 
									where i_barang_penerimaan = ? ",$where);

			$jmlResult = count($result);

			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_barang_penerimaan"	=>(string)$result[$j]->i_barang_penerimaan,
										"d_barang_penerimaan"    =>(string)$result[$j]->d_barang_penerimaan,
										"i_barang_ajuanbaik"          	=>(string)$result[$j]->i_barang_ajuanbaik,
										"i_peg_nipterima"    =>(string)$result[$j]->i_peg_nipterima,
										"i_peg_nipserah"          	=>(string)$result[$j]->i_peg_nipserah
										);
			}	
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	
	public function getSabmMaster($data){
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$where[] = $data['thnang'];
		$where[] = $data['kdbrg'];
		$where[] = $data['noaset'];
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$result = $db->fetchAll("SELECT merk_type,keterangan FROM e_sabm_t_master_tm 
									where thn_ang = ? and kd_brg = ? and no_aset = ?",$where);

			$jmlResult = count($result);

			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("merk_type"	=>(string)$result[$j]->merk_type,
										"keterangan"    =>(string)$result[$j]->keterangan
										);
			}	
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	
	//public function getPermintaanSparePartList($pageNumber,$itemPerPage)
	public function getPermintaanSparePartList()
	{
	  try {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$where[] = "N";
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			/* if(($pageNumber==0) && ($itemPerPage==0))
			{
				$setujuSIList = $db->fetchOne("Select *  from  e_ast_ajuanbaikti_item_tm 
								Where c_status_perbaikan = 'N' and c_setuju_sparepart is null");
			}
		
			else 
			{ */
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				/* $setuju = $db->fetchAll("Select *  from  e_ast_ajuanbaikti_item_tm 
										Where c_status_perbaikan = 'N' and c_setuju_sparepart is null 
										limit $xLimit offset $xOffset")	; */
				$setuju = $db->fetchAll("Select * from e_ast_ajuanbaikti_item_tm A, e_ast_ajuanbaikti_0_tm B
								where a.i_barang_ajuanbaik = b.i_barang_ajuanbaik
								and a.c_status_perbaikan = ? and a.c_setuju_sparepart is null	
								",$where)	;
				
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{
				//echo "TEST serv ".$setuju[$j]->i_barang_ajuanbaik;
					$setujuSIList[$j] = array("i_barang_ajuanbaik"	=>(string)$setuju[$j]->i_barang_ajuanbaik,
											 "d_awal_perbaikan" =>(string)$setuju[$j]->d_awal_perbaikan,
											 "d_akhir_perbaikan"	=>(string)$setuju[$j]->d_akhir_perbaikan,
											 "q_jam_perbaikan"	=>(string)$setuju[$j]->q_jam_perbaikan,
											 "c_status_perbaikan"	=>(string)$setuju[$j]->c_status_perbaikan,
											 "e_saran_sparepart"	=>(string)$setuju[$j]->e_saran_sparepart,
											 "e_saran_pihakketiga"	=>(string)$setuju[$j]->e_saran_pihakketiga,
											 "e_tindakan_perbaikan"	=>(string)$setuju[$j]->e_tindakan_perbaikan,
											 "c_setuju_sparepart"=>(string)$setuju[$j]->c_setuju_sparepart,
											 "i_minta_sparepart"=>(string)$setuju[$j]->i_minta_sparepart,
											 "d_minta_sparepart"=>(string)$setuju[$j]->d_minta_sparepart,
											 "i_peg_nipsetuju"=>(string)$setuju[$j]->i_peg_nipsetuju,
											 "c_terima_sparepart"=>(string)$setuju[$j]->c_terima_sparepart,
											 "d_terima_sparepart"=>(string)$setuju[$j]->d_terima_sparepart,
											 "i_peg_nipterima"=>(string)$setuju[$j]->i_peg_nipterima,
											 "i_peg_helpdesk"=>(string)$setuju[$j]->i_peg_helpdesk,
											 "i_peg_nip"=>(string)$setuju[$j]->i_peg_nip,
											 "d_barang_ajuanbaik"	=>(string)$setuju[$j]->d_barang_ajuanbaik
											 
											 );
				}
			//}
			
			return $setujuSIList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	// ======================== PROSES PERSETUJUAN SPARE PARTS ==============================
	public function prosesPersetujuanSpareParts($data)
	{
		try
		{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$db->beginTransaction();
			$alasan = $data['alasan'];
			
			$paramProses = array("c_setuju_sparepart"	=>$data['status'],
									"e_alasan_sparepart"	=>$alasan,
									"i_minta_sparepart" =>$data['noPermintaan'],
									"i_peg_nipsetuju" =>$data['nip'],
									"d_minta_sparepart"=>date("Y-m-d")
								);
			
			$where[] = "i_barang_ajuanbaik = '".$data['noPengajuan']."'";
			$db->update('e_ast_ajuanbaikti_item_tm',$paramProses, $where);
			$db->commit();
			return 'sukses <br>';
		}catch (Exception $e){
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}
	
	public function getPenerimaanSparePartList()
	{
	  try {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$where[] = "Y";
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			/* if(($pageNumber==0) && ($itemPerPage==0))
			{
				$setujuSIList = $db->fetchOne("Select *  from  e_ast_ajuanbaikti_item_tm 
								Where c_status_perbaikan = 'N' and c_setuju_sparepart is null");
			}
		
			else 
			{ */
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
				
				/* $setuju = $db->fetchAll("Select * from e_ast_ajuanbaikti_item_tm 
								where c_setuju_sparepart = 'Y'	
								")	; */
				 $setuju = $db->fetchAll("Select * from e_ast_ajuanbaikti_item_tm A, e_ast_ajuanbaikti_0_tm B
								where a.i_barang_ajuanbaik = b.i_barang_ajuanbaik
								and a.c_setuju_sparepart = 'Y'	
								and a.c_terima_sparepart is null
								")	; 
				
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{
				//echo "TEST serv ".$setuju[$j]->i_barang_ajuanbaik;
					$setujuSIList[$j] = array("i_barang_ajuanbaik"	=>(string)$setuju[$j]->i_barang_ajuanbaik,
											 "d_awal_perbaikan" =>(string)$setuju[$j]->d_awal_perbaikan,
											 "d_akhir_perbaikan"	=>(string)$setuju[$j]->d_akhir_perbaikan,
											 "q_jam_perbaikan"	=>(string)$setuju[$j]->q_jam_perbaikan,
											 "c_status_perbaikan"	=>(string)$setuju[$j]->c_status_perbaikan,
											 "e_saran_sparepart"	=>(string)$setuju[$j]->e_saran_sparepart,
											 "e_saran_pihakketiga"	=>(string)$setuju[$j]->e_saran_pihakketiga,
											 "e_tindakan_perbaikan"	=>(string)$setuju[$j]->e_tindakan_perbaikan,
											 "c_setuju_sparepart"=>(string)$setuju[$j]->c_setuju_sparepart,
											 "i_minta_sparepart"=>(string)$setuju[$j]->i_minta_sparepart,
											 "d_minta_sparepart"=>(string)$setuju[$j]->d_minta_sparepart,
											 "i_peg_nipsetuju"=>(string)$setuju[$j]->i_peg_nipsetuju,
											 "c_terima_sparepart"=>(string)$setuju[$j]->c_terima_sparepart,
											 "d_terima_sparepart"=>(string)$setuju[$j]->d_terima_sparepart,
											 "i_peg_nipterima"=>(string)$setuju[$j]->i_peg_nipterima,
											 "i_peg_nip"=>(string)$setuju[$j]->i_peg_nip,
											 "d_barang_ajuanbaik"	=>(string)$setuju[$j]->d_barang_ajuanbaik
											 
											 );
				}
			//}
			
			return $setujuSIList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
	
	// ======================== PROSES PENERIMAAN SPARE PARTS ==============================
	public function prosesPenerimaanSpareParts($data)
	{
		try
		{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			$db->beginTransaction();
			$alasan = $data['alasan'];
			
			$paramProses = array("c_terima_sparepart"	=>$data['status'],
									"i_peg_nipterima" =>$data['nip'],
									"d_terima_sparepart"=>date("Y-m-d")
								);
			
			$where[] = "i_minta_sparepart = '".$data['noPermintaan']."'";
			$db->update('e_ast_ajuanbaikti_item_tm',$paramProses, $where);
			$db->commit();
			return 'sukses <br>';
		}catch (Exception $e){
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}
	
	//NGAMBIL DATA PERBAIKAN YG SUDAH DI KEMBALIKAN DARI TABEL E_AST_KEMBALIBAIKTI_O_TM
	public function getDataPengembalian($noPengajuan)
	{
	  try {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$where[] = $noPengajuan;
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
				
				$setuju = $db->fetchAll("Select * from E_AST_KEMBALIBAIKTI_0_TM where  i_barang_ajuanbaik = ? ",$where)	;
				
				$jmlSetuju = count($setuju);
				for($j=0; $j<$jmlSetuju; $j++)
				{
				//echo "TEST serv ".$setuju[$j]->i_barang_ajuanbaik;
					 $hasilList[$j] = array("i_barang_ajuanbaik"		=>(string)$setuju[$j]->i_barang_ajuanbaik,
											"i_barang_pengembalian" 	=>(string)$setuju[$j]->i_barang_pengembalian,
											"d_barang_pengembalian"		=>(string)$setuju[$j]->d_barang_pengembalian,
											"i_peg_nipterima"	=>(string)$setuju[$j]->i_peg_nipterima,
											"i_peg_nipserah"	=>(string)$setuju[$j]->i_peg_nipserah
											 );
				}
			
			return $hasilList;
			
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   }
	}
}
?>