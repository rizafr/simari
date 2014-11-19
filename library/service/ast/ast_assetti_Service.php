<?php
class ast_assetti_Service 
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
    	
	
	    	
    	//untuk mengeluarkan list no inventaris waktu diklik button no inventaris
	public function getCariDIRbyBrg($pageNumber,$itemPerPage,$unitKerja) 
	{   
	   $unitkr = strtoupper($unitKerja);
	   $nbrg = '%'.$unitkr.'%';
	    $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			 if ($unitKerja=='')
			 {
				  $hasilAkhir = $db->fetchOne("SELECT count(*) 
 						   FROM  e_ast_dir_0_tm a,e_ast_dir_item_tm b,e_ast_sskel_0_tr c, e_ast_komputer_0_tr d ,e_sdm_pegawai_0_tm e,e_ast_ruangan_0_tr f
										where a.i_barang_serah = b.i_barang_serah
                                            and substr(b.c_barang,1,1) = c.kd_gol
											and substr(b.c_barang,2,2) = c.kd_bid 
											and substr(b.c_barang,4,2) = c.kd_kel
											and substr(b.c_barang,6,2) = c.kd_skel
											and substr(b.c_barang,8,3) = c.kd_sskel 
											and b.d_aset_thnanggar = d.d_anggaran
                                            and b.c_barang = d.c_barang 
											and b.i_aset = d.i_aset
                                            and a.i_peg_nipterima = e.i_peg_nip 
                                            and b.i_ruang = f.i_ruang"); 
											
		     } else
		     {
				  $hasilAkhir = $db->fetchOne("SELECT count(*) 
 						   FROM  e_ast_dir_0_tm a,e_ast_dir_item_tm b,e_ast_sskel_0_tr c, e_ast_komputer_0_tr d ,e_sdm_pegawai_0_tm e,e_ast_ruangan_0_tr f
										where a.i_barang_serah = b.i_barang_serah
                                            and substr(b.c_barang,1,1) = c.kd_gol
											and substr(b.c_barang,2,2) = c.kd_bid 
											and substr(b.c_barang,4,2) = c.kd_kel
											and substr(b.c_barang,6,2) = c.kd_skel
											and substr(b.c_barang,8,3) = c.kd_sskel 
											and b.d_aset_thnanggar = d.d_anggaran
                                            and b.c_barang = d.c_barang 
											and b.i_aset = d.i_aset
                                            and a.i_peg_nipterima = e.i_peg_nip 
                                            and b.i_ruang = f.i_ruang
											and ur_sskel like ?",$nbrg);  
             }
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 if ($unitKerja=='')
			 {
					 $result = $db->fetchAll("SELECT distinct a.i_barang_serah,a.i_peg_nipterima,a.i_orgb_penerima,n_peg,b.d_aset_thnanggar, b.c_barang, to_char(b.i_aset,'09999') as  i_aset , ur_sskel,b.i_ruang,q_gedung_lantai, 
												i_komputer_macaddress ,i_komputer_serialpc
												FROM  e_ast_dir_0_tm a,e_ast_dir_item_tm b,e_ast_sskel_0_tr c, e_ast_komputer_0_tr d ,e_sdm_pegawai_0_tm e,e_ast_ruangan_0_tr f
												where a.i_barang_serah = b.i_barang_serah
		                                            and substr(b.c_barang,1,1) = c.kd_gol
													and substr(b.c_barang,2,2) = c.kd_bid 
													and substr(b.c_barang,4,2) = c.kd_kel
													and substr(b.c_barang,6,2) = c.kd_skel
													and substr(b.c_barang,8,3) = c.kd_sskel 
													and b.d_aset_thnanggar = d.d_anggaran
		                                            and b.c_barang = d.c_barang 
													and b.i_aset = d.i_aset
		                                            and a.i_peg_nipterima = e.i_peg_nip 
		                                            and b.i_ruang = f.i_ruang													
													limit $xLimit offset $xOffset"); 
		      } else
		      {
			       $result = $db->fetchAll("SELECT distinct a.i_barang_serah,a.i_peg_nipterima,a.i_orgb_penerima,n_peg,b.d_aset_thnanggar, b.c_barang, to_char(b.i_aset,'09999') as  i_aset , ur_sskel,b.i_ruang,q_gedung_lantai, 
												i_komputer_macaddress ,i_komputer_serialpc
												FROM  e_ast_dir_0_tm a,e_ast_dir_item_tm b,e_ast_sskel_0_tr c, e_ast_komputer_0_tr d ,e_sdm_pegawai_0_tm e,e_ast_ruangan_0_tr f
												where a.i_barang_serah = b.i_barang_serah
		                                            and substr(b.c_barang,1,1) = c.kd_gol
													and substr(b.c_barang,2,2) = c.kd_bid 
													and substr(b.c_barang,4,2) = c.kd_kel
													and substr(b.c_barang,6,2) = c.kd_skel
													and substr(b.c_barang,8,3) = c.kd_sskel 
													and b.d_aset_thnanggar = d.d_anggaran
		                                            and b.c_barang = d.c_barang 
													and b.i_aset = d.i_aset
		                                            and a.i_peg_nipterima = e.i_peg_nip 
		                                            and b.i_ruang = f.i_ruang
													and ur_sskel like ? 
													limit $xLimit offset $xOffset",$nbrg); 
		      }    
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_barang_serah"          =>(string)$result[$j]->i_barang_serah,
										 "d_aset_thnanggar"          =>(string)$result[$j]->d_aset_thnanggar,
										 "c_barang"           		=>(string)$result[$j]->c_barang,
										 "i_aset"           			=>(string)$result[$j]->i_aset,
										 "ur_sskel"          		=>(string)$result[$j]->ur_sskel,
										 "i_peg_nipterima"          =>(string)$result[$j]->i_peg_nipterima,
										 "i_org_penerima"          =>(string)$result[$j]->i_orgb_penerima,
										 "n_peg"                    =>(string)$result[$j]->n_peg,
										 "i_komputer_macaddress"          =>(string)$result[$j]->i_komputer_macaddress,
										 "i_komputer_serialpc"          =>(string)$result[$j]->i_komputer_serialpc,
										 "i_ruang"   				=>(string)$result[$j]->i_ruang,
										 "q_gedung_lantai"   				=>(string)$result[$j]->q_gedung_lantai);
									
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	
	public function getCariDIRbyOrg_Old($pageNumber,$itemPerPage,$unitKerja,$nmBarang) 
	{   
	   $unitkr = strtoupper($unitKerja);
	   $unit = '%'.$unitkr.'%';
	   $nbrg = strtoupper($nmBarang);
	   $brg = '%'.$nbrg.'%';
	    $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unit;
	     $where[] = $brg;
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*) 
 						   FROM  e_ast_dir_0_tm a,e_ast_dir_item_tm b,e_ast_sskel_0_tr c, e_ast_komputer_0_tr d ,e_sdm_pegawai_0_tm e,e_ast_ruangan_0_tr f
										where a.i_barang_serah = b.i_barang_serah
                                            and substr(b.c_barang,1,1) = c.kd_gol
											and substr(b.c_barang,2,2) = c.kd_bid 
											and substr(b.c_barang,4,2) = c.kd_kel
											and substr(b.c_barang,6,2) = c.kd_skel
											and substr(b.c_barang,8,3) = c.kd_sskel 
											and b.d_aset_thnanggar = d.d_anggaran
                                            and b.c_barang = d.c_barang 
											and b.i_aset = d.i_aset
                                            and a.i_peg_nipterima = e.i_peg_nip 
                                            and b.i_ruang = f.i_ruang
											and i_orgb_penerima like ? and ur_sskel like ?",$where);  
        
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT distinct a.i_barang_serah,a.i_peg_nipterima,n_peg,a.i_orgb_penerima, b.d_aset_thnanggar, b.c_barang, to_char(b.i_aset,'09999') as  i_aset , ur_sskel,b.i_ruang,q_gedung_lantai, 
										i_komputer_macaddress ,i_komputer_serialpc
										FROM  e_ast_dir_0_tm a,e_ast_dir_item_tm b,e_ast_sskel_0_tr c, e_ast_komputer_0_tr d ,e_sdm_pegawai_0_tm e,e_ast_ruangan_0_tr f
										where a.i_barang_serah = b.i_barang_serah
                                            and substr(b.c_barang,1,1) = c.kd_gol
											and substr(b.c_barang,2,2) = c.kd_bid 
											and substr(b.c_barang,4,2) = c.kd_kel
											and substr(b.c_barang,6,2) = c.kd_skel
											and substr(b.c_barang,8,3) = c.kd_sskel 
											and b.d_aset_thnanggar = d.d_anggaran
                                            and b.c_barang = d.c_barang 
											and b.i_aset = d.i_aset
                                            and a.i_peg_nipterima = e.i_peg_nip 
                                            and b.i_ruang = f.i_ruang
											and i_orgb_penerima like ? and ur_sskel like ?
											limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_barang_serah"            =>(string)$result[$j]->i_barang_serah,
										 "d_aset_thnanggar"          =>(string)$result[$j]->d_aset_thnanggar,
										 "c_barang"           		=>(string)$result[$j]->c_barang,
										 "i_aset"         			=>(string)$result[$j]->i_aset,
										 "ur_sskel"          		=>(string)$result[$j]->ur_sskel,
										 "i_peg_nipterima"          =>(string)$result[$j]->i_peg_nipterima,
										 "n_peg"                    =>(string)$result[$j]->n_peg,
										 "i_orgb_penerima"          =>(string)$result[$j]->i_orgb_penerima,										 
										 "i_komputer_macaddress"    =>(string)$result[$j]->i_komputer_macaddress,
										 "i_komputer_serialpc"      =>(string)$result[$j]->i_komputer_serialpc,
										 "i_ruang"   				=>(string)$result[$j]->i_ruang,
										 "q_gedung_lantai"   	    =>(string)$result[$j]->q_gedung_lantai);
									
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	public function getCariBrgCtk($nbr) 
	{   
	   $unitkr = strtoupper($nbr);
	   $nbrg = '%'.$unitkr.'%';
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 			 
			 $result = $db->fetchAll("SELECT distinct a.i_barang_serah,a.i_peg_nipterima,a.i_orgb_penerima,n_peg,b.d_aset_thnanggar, b.c_barang, to_char(b.i_aset,'09999') as  i_aset , ur_sskel,b.i_ruang,q_gedung_lantai, 
										i_komputer_macaddress ,i_komputer_serialpc
										FROM  e_ast_dir_0_tm a,e_ast_dir_item_tm b,e_ast_sskel_0_tr c, e_ast_komputer_0_tr d ,e_sdm_pegawai_0_tm e,e_ast_ruangan_0_tr f
										where a.i_barang_serah = b.i_barang_serah
                                            and substr(b.c_barang,1,1) = c.kd_gol
											and substr(b.c_barang,2,2) = c.kd_bid 
											and substr(b.c_barang,4,2) = c.kd_kel
											and substr(b.c_barang,6,2) = c.kd_skel
											and substr(b.c_barang,8,3) = c.kd_sskel 
											and b.d_aset_thnanggar = d.d_anggaran
                                            and b.c_barang = d.c_barang 
											and b.i_aset = d.i_aset
                                            and a.i_peg_nipterima = e.i_peg_nip 
                                            and b.i_ruang = f.i_ruang
											and ur_sskel like ?",$nbrg); 
			 
			 $jmlResult = count($result);
			  
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_barang_serah"          =>(string)$result[$j]->i_barang_serah,
										 "d_aset_thnanggar"          =>(string)$result[$j]->d_aset_thnanggar,
										 "c_barang"           		=>(string)$result[$j]->c_barang,
										 "i_aset"           			=>(string)$result[$j]->i_aset,
										 "ur_sskel"          		=>(string)$result[$j]->ur_sskel,
										 "i_peg_nipterima"          =>(string)$result[$j]->i_peg_nipterima,
										 "i_org_penerima"          =>(string)$result[$j]->i_orgb_penerima,
										 "n_peg"                    =>(string)$result[$j]->n_peg,
										 "i_komputer_macaddress"          =>(string)$result[$j]->i_komputer_macaddress,
										 "i_komputer_serialpc"          =>(string)$result[$j]->i_komputer_serialpc,
										 "i_ruang"   				=>(string)$result[$j]->i_ruang,
										 "q_gedung_lantai"   				=>(string)$result[$j]->q_gedung_lantai);
									
			 }	
		 	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	public function getCariBrgCtkByOrg($unitKerja,$nmBarang) 
	{   
	   $unitkr = strtoupper($unitKerja);
	   $unit = '%'.$unitkr.'%';
	   $nbrg = strtoupper($nmBarang);
	   $brg = '%'.$nbrg.'%';
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unit;
	     $where[] = $brg;
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 			 
			 $result = $db->fetchAll("SELECT distinct a.i_barang_serah,a.i_orgb_penerima,a.i_peg_nipterima,n_peg,b.d_aset_thnanggar, b.c_barang, to_char(b.i_aset,'09999') as  i_aset , ur_sskel,b.i_ruang,q_gedung_lantai, 
										i_komputer_macaddress ,i_komputer_serialpc
										FROM  e_ast_dir_0_tm a,e_ast_dir_item_tm b,e_ast_sskel_0_tr c, e_ast_komputer_0_tr d ,e_sdm_pegawai_0_tm e,e_ast_ruangan_0_tr f
										where a.i_barang_serah = b.i_barang_serah
                                            and substr(b.c_barang,1,1) = c.kd_gol
											and substr(b.c_barang,2,2) = c.kd_bid 
											and substr(b.c_barang,4,2) = c.kd_kel
											and substr(b.c_barang,6,2) = c.kd_skel
											and substr(b.c_barang,8,3) = c.kd_sskel 
											and b.d_aset_thnanggar = d.d_anggaran
                                            and b.c_barang = d.c_barang 
											and b.i_aset = d.i_aset
                                            and a.i_peg_nipterima = e.i_peg_nip 
                                            and b.i_ruang = f.i_ruang
											and a.i_orgb_penerima like ? and ur_sskel like ?",$where); 
			 
			 $jmlResult = count($result);
			  
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_barang_serah"          =>(string)$result[$j]->i_barang_serah,
										 "d_aset_thnanggar"          =>(string)$result[$j]->d_aset_thnanggar,
										 "c_barang"           		=>(string)$result[$j]->c_barang,
										 "i_aset"           			=>(string)$result[$j]->i_aset,
										 "ur_sskel"          		=>(string)$result[$j]->ur_sskel,
										 "i_peg_nipterima"          =>(string)$result[$j]->i_peg_nipterima,
										 "i_orgb_penerima"          =>(string)$result[$j]->i_orgb_penerima,
										 "n_peg"                    =>(string)$result[$j]->n_peg,
										 "i_komputer_macaddress"          =>(string)$result[$j]->i_komputer_macaddress,
										 "i_komputer_serialpc"          =>(string)$result[$j]->i_komputer_serialpc,
										 "i_ruang"   				=>(string)$result[$j]->i_ruang,
										 "q_gedung_lantai"   				=>(string)$result[$j]->q_gedung_lantai);
									
			 }	
		 	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}

	/* and b.d_aset_thnanggar = d.d_anggaran //and b.d_aset_thnanggar = d.d_anggaran
	and b.c_barang = d.c_barang 
	and b.i_aset = d.i_aset */
	
 	public function getCariBrgByUnit($pageNumber,$itemPerPage,$unitKerja,$nmBarang) 
	{   
	   $unitkr = strtoupper($unitKerja);
	   $unit = '%'.$unitkr.'%';
	   $nbrg = strtoupper($nmBarang);
	   $brg = '%'.$nbrg.'%';
	  
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unit;
	     $where[] = $brg;
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*) 
 						   FROM  e_ast_dir_0_tm a,e_ast_dir_item_tm b,e_ast_sskel_0_tr c, e_ast_komputer_0_tr d ,e_sdm_pegawai_0_tm e,e_ast_ruangan_0_tr f
										where a.i_barang_serah = b.i_barang_serah
                                            and substr(b.c_barang,1,1) = c.kd_gol
											and substr(b.c_barang,2,2) = c.kd_bid 
											and substr(b.c_barang,4,2) = c.kd_kel
											and substr(b.c_barang,6,2) = c.kd_skel
											and substr(b.c_barang,8,3) = c.kd_sskel 											
                                            and b.c_barang = d.c_barang 
											and b.i_aset = d.i_aset
                                            and a.i_peg_nipterima = e.i_peg_nip 
                                            and b.i_ruang = f.i_ruang and a.i_barang_serah like '%KYN%'
											and a.i_orgb_penerima like ? and ur_sskel like ?" ,$where);  
        
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT distinct a.i_barang_serah,a.i_peg_nipterima,a.i_orgb_penerima,
			                            n_peg,n_jabatan,b.d_aset_thnanggar, b.c_barang, to_char(b.i_aset,'09999') as  i_aset , ur_sskel,b.i_ruang,q_gedung_lantai,
										d.i_hw_investasi
										from  e_ast_dir_0_tm a,e_ast_dir_item_tm b,e_ast_sskel_0_tr c, e_sdm_pegawai_0_tm e,e_ast_ruangan_0_tr f,e_ast_komputer_0_tr d
										where a.i_barang_serah = b.i_barang_serah
                                            and substr(b.c_barang,1,1) = c.kd_gol
											and substr(b.c_barang,2,2) = c.kd_bid 
											and substr(b.c_barang,4,2) = c.kd_kel
											and substr(b.c_barang,6,2) = c.kd_skel
											and substr(b.c_barang,8,3) = c.kd_sskel											
                                            and b.c_barang = d.c_barang 
											and b.i_aset = d.i_aset
											and a.i_peg_nipterima = e.i_peg_nip 
                                            and b.i_ruang = f.i_ruang and a.i_barang_serah like '%KYN%'
											and a.i_orgb_penerima like ? and ur_sskel like ?  
											limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_barang_serah"          	=>(string)$result[$j]->i_barang_serah,
										 "d_aset_thnanggar"         =>(string)$result[$j]->d_aset_thnanggar,
										 "c_barang"           		=>(string)$result[$j]->c_barang,
										 "i_aset"           		=>(string)$result[$j]->i_aset,
										 "ur_sskel"          		=>(string)$result[$j]->ur_sskel,
										 "i_peg_nipterima"          =>(string)$result[$j]->i_peg_nipterima,
										 "n_peg"                    =>(string)$result[$j]->n_peg,
										 "n_jabatan"          		=>(string)$result[$j]->n_jabatan,
										 "i_orgb_penerima"          =>(string)$result[$j]->i_orgb_penerima,
										 "i_ruang"   				=>(string)$result[$j]->i_ruang,
										 "q_gedung_lantai"   		=>(string)$result[$j]->q_gedung_lantai,
										 "i_hw_investasi"   		=>(string)$result[$j]->i_hw_investasi);
									
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	public function getListSoftware(array $data) 
	{ 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ);
		 $tahun = $data['danggaran'];
		 $kodebrg = trim($data['cbarang']);
		 $noaset = trim($data['iaset']);
		  
		  
		 
		 $where[] = $tahun;
		 $where[] = $kodebrg;
		 $where[] = $noaset;
		
		 $result = $db->fetchAll("SELECT distinct a.i_sw,n_sw,n_sw_installer,b.i_hw_investasi
			                            from  e_ast_distribusi_software_tm a,e_ast_software_0_tr b
										where a.i_sw = b.i_sw and thn_ang =? and kd_brg=? and no_aset=?",$where);
                                             
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_sw"             =>(string)$result[$j]->i_sw,
										 "n_sw"            =>(string)$result[$j]->n_sw,
										 "n_sw_installer"  =>(string)$result[$j]->n_sw_installer,
										 "i_hw_investasi"  =>(string)$result[$j]->i_hw_investasi);
									
			 }	
		 	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	public function getUnitKerjaList() 
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchAll('SELECT i_orgb,n_orgb FROM e_org_0_0_tm ORDER BY i_orgb');
	     return $result;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getCariNamaOrg($unitkr) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchOne('SELECT n_orgb FROM e_org_0_0_tm where i_orgb = ?',$unitkr);
	     return $result;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	//2 juli 2008 get nama barang 
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
			//$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_sskel_0_tr ",$nbrg);
			
			$result = $db->fetchAll("SELECT distinct a.kd_brg, b.ur_sskel 
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and (kd_brg like '2120102%'  or  kd_brg like '2120203%') 

											UNION
											SELECT distinct a.kd_brg, b.ur_sskel 
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and (kd_brg like '2120204001%'  or  kd_brg like '2060101048%') 

											UNION
											SELECT distinct a.kd_brg, b.ur_sskel 
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
											and kd_sskel != '001'"); 
											
			$hasilAkhir =count($result);
										   
		 }
		 else
		 {
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT distinct a.kd_brg, b.ur_sskel 
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and (kd_brg like '2120102%'  or  kd_brg like '2120203%') 

											UNION
											SELECT distinct a.kd_brg, b.ur_sskel 
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and (kd_brg like '2120204001%'  or  kd_brg like '2060101048%') 

											UNION
											SELECT distinct a.kd_brg, b.ur_sskel 
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

										   ORDER BY ur_sskel
										   limit $xLimit offset $xOffset"); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("kd_brg"        =>(string)$result[$j]->kd_brg,
										   "ur_sskel"   =>(string)$result[$j]->ur_sskel);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
	
	
	// list Aset TI =================================================================02 Juli 2008==============================================
	
	public function getDaftarAsetTIList($pageNumber,$itemPerPage,$nmBarang,$unitKerja) {
	 
	$kdBrg  = '2120102';
    $kdBrg1 = '2120203'; 
	$kbr    = '%'.$kdBrg.'%';
	$kbr1   = '%'.$kdBrg1.'%';
    $nmbrg   = '%'.strtoupper($nmBarang).'%';
	$unitkr = strtoupper($unitKerja);
	$unit = '%'.$unitkr.'%';
	$where[] = $kbr;
	$where[] = $kbr1;
	$where[] = $nmbrg;
	$where[] = $unit;
		 
	$registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 { 
			$hasilAkhir = $db->fetchOne("SELECT count(*) FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
										where substr(a.kd_brg,1,1) = b.kd_gol
										and substr(a.kd_brg,2,2) = b.kd_bid 
										and substr(a.kd_brg,4,2) = b.kd_kel
										and substr(a.kd_brg,6,2) = b.kd_skel
										and substr(a.kd_brg,8,3) = b.kd_sskel
										and (kd_brg like ?  or  kd_brg like ?) 
					                   	and b.ur_sskel like ?					
										and not exists(SELECT c.d_perolehan,c.d_anggaran,c.c_barang,c.i_aset 
												 FROM e_ast_komputer_0_tr c
												 where c.d_perolehan = a.tgl_perlh 
												 and   c.d_anggaran = to_char(tgl_perlh,'yyyy') 
												 and   c.c_barang = a.kd_brg
												 and   c.i_aset = a.no_aset)",$where);	
								   	    
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		  
		      $result = $db->fetchAll("SELECT  to_char(tgl_perlh,'yyyy') as thn_ang,a.kd_brg,to_char(a.no_aset,'09999') as no_aset,
											a.tgl_perlh,b.ur_sskel
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and (kd_brg like ?  or  kd_brg like ?)
											and b.ur_sskel like ?		
											and not exists(SELECT c.d_perolehan,c.d_anggaran,c.c_barang,c.i_aset 
											FROM e_ast_komputer_0_tr c
											where c.d_perolehan = a.tgl_perlh 
											and   c.d_anggaran = to_char(tgl_perlh,'yyyy')
											and c.c_barang = a.kd_brg
											and   c.i_aset = a.no_aset) order by a.thn_ang,a.kd_brg,no_aset limit $xLimit offset $xOffset ",$where);
							
            $jmlResult = count($result);
		 
		 
		   for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("thn_ang"           	=>(string)$result[$j]->thn_ang,
									"kd_brg"           	=>(string)$result[$j]->kd_brg,
									"no_aset"           =>(string)$result[$j]->no_aset,
									"tgl_perlh"         =>(string)$result[$j]->tgl_perlh,
									"ur_sskel"          =>(string)$result[$j]->ur_sskel);
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	//get nama barang & unit kerja ==========> ambil dir==================update on 3 juli 2008 =========================
	
	public function getCariDIRbyOrg($pageNumber,$itemPerPage,$unitKerja,$nmBarang) 
	{   
	   $unitkr = strtoupper($unitKerja);
	   $unit = '%'.$unitkr.'%';
	   $nbrg = strtoupper($nmBarang);
	   $brg = '%'.$nbrg.'%';
	    $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unit;
	     $where[] = $brg;
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$result = $db->fetchAll("SELECT	distinct d.i_barang_serah,d.i_peg_nipterima,d.i_orgb_penerima, to_char(c.d_barang_peroleh,'yyyy') as thn_ang, c.c_barang, to_char(c.i_aset,'09999') as  i_aset , ur_sskel,c.i_ruang
										    FROM e_ast_dir_item_tm  c, e_ast_dir_0_tm d,e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where 
												substr(a.kd_brg,1,1) = b.kd_gol
														and substr(a.kd_brg,2,2) = b.kd_bid 
														and substr(a.kd_brg,4,2) = b.kd_kel
														and substr(a.kd_brg,6,2) = b.kd_skel
														and substr(a.kd_brg,8,3) = b.kd_sskel
											and
											to_char(c.d_barang_peroleh,'yyyy')= to_char(tgl_perlh,'yyyy')
													and   c.c_barang = a.kd_brg
													and   c.i_aset = a.no_aset
											and c.i_barang_serah=d.i_barang_serah
											and (d.i_barang_serah
											 like '%KYN%' or d.i_barang_serah
											 like '%HYN%')
											and i_orgb_penerima like ? and ur_sskel like ?",$where); 
			 
			 $hasilAkhir = count($result);
			
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT	distinct d.i_barang_serah,d.i_peg_nipterima,d.i_orgb_penerima, to_char(c.d_barang_peroleh,'yyyy') as thn_ang, c.c_barang, to_char(c.i_aset,'09999') as  i_aset , ur_sskel,c.i_ruang
										    FROM e_ast_dir_item_tm  c, e_ast_dir_0_tm d,e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where 
												substr(a.kd_brg,1,1) = b.kd_gol
														and substr(a.kd_brg,2,2) = b.kd_bid 
														and substr(a.kd_brg,4,2) = b.kd_kel
														and substr(a.kd_brg,6,2) = b.kd_skel
														and substr(a.kd_brg,8,3) = b.kd_sskel
											and
											to_char(c.d_barang_peroleh,'yyyy')= to_char(tgl_perlh,'yyyy')
													and   c.c_barang = a.kd_brg
													and   c.i_aset = a.no_aset
											and c.i_barang_serah=d.i_barang_serah
											and (d.i_barang_serah
											 like '%KYN%' or d.i_barang_serah
											 like '%HYN%')
											and i_orgb_penerima like ? and ur_sskel like ?
											limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$n_peg 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nipterima);
				
				$q_gedung_lantai 			= $db->fetchCol('select q_gedung_lantai  
														from  e_ast_ruangan_0_tr 
														where i_ruang = ? ',$result[$j]->i_ruang);
														
				$i_komputer_macaddress 			= $db->fetchCol('select i_komputer_macaddress  
														from  e_ast_komputer_0_tr 
														where i_aset = ? ',$result[$j]->i_aset);
				
				$i_komputer_serialpc 			= $db->fetchCol('select i_komputer_serialpc  
														from  e_ast_komputer_0_tr 
														where i_aset = ? ',$result[$j]->i_aset);
														
				$hasilAkhir[$j] = array("i_barang_serah"            =>(string)$result[$j]->i_barang_serah,
										 "d_aset_thnanggar"         =>(string)$result[$j]->d_aset_thnanggar,
										 "thn_ang"					=>(string)$result[$j]->thn_ang,
										 "c_barang"           		=>(string)$result[$j]->c_barang,
										 "i_aset"         			=>(string)$result[$j]->i_aset,
										 "ur_sskel"          		=>(string)$result[$j]->ur_sskel,
										 "i_peg_nipterima"          =>(string)$result[$j]->i_peg_nipterima,
										 "n_peg"                    =>$n_peg[0],
										 "i_orgb_penerima"          =>(string)$result[$j]->i_orgb_penerima,										 
										 "i_komputer_macaddress"    =>$i_komputer_macaddress[0],
										 "i_komputer_serialpc"      =>$i_komputer_serialpc[0],
										 "i_ruang"   				=>(string)$result[$j]->i_ruang,
										 "q_gedung_lantai"   	    =>$q_gedung_lantai[0]);
									
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function getCariAsetbyNama($pageNumber,$itemPerPage,$nmBarang) 
	{   
	   $unitkr = strtoupper($unitKerja);
	   $unit = '%'.$unitkr.'%';
	   $nbrg = strtoupper($nmBarang);
	   $brg = '%'.$nbrg.'%';
	    $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     //$where[] = $unit;
	     $where[] = $brg;
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$result = $db->fetchAll("SELECT distinct
										null as i_barang_serah,null as i_peg_nipterima, null as i_orgb_penerima, to_char(tgl_perlh,'yyyy') as thn_ang, g.kd_brg as c_barang, to_char(g.no_aset,'09999') as  i_aset , ur_sskel,null as i_ruang
										FROM e_sabm_t_master_tm G, e_ast_sskel_0_tr H
												where substr(g.kd_brg,1,1) = h.kd_gol
												and substr(g.kd_brg,2,2) = h.kd_bid 
												and substr(g.kd_brg,4,2) = h.kd_kel
												and substr(g.kd_brg,6,2) = h.kd_skel
												and substr(g.kd_brg,8,3) = h.kd_sskel
												and (kd_brg like '2120102%'  or  kd_brg like '2120203%' or kd_brg like '2120204001%'  or  kd_brg like '2060101048%' or
										(
										    h.kd_gol = '2'
										and h.kd_bid = '12'
										and h.kd_kel = '02'
										and h.kd_skel = '04'
										and h.kd_sskel != '001'
										) ) 		
										and h.ur_sskel like ?",$where); 
			 
			 $hasilAkhir = count($result);
			
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT distinct
										null as i_barang_serah,null as i_peg_nipterima, null as i_orgb_penerima, to_char(tgl_perlh,'yyyy') as thn_ang, g.kd_brg as c_barang, to_char(g.no_aset,'09999') as  i_aset , ur_sskel,null as i_ruang
										FROM e_sabm_t_master_tm G, e_ast_sskel_0_tr H
												where substr(g.kd_brg,1,1) = h.kd_gol
												and substr(g.kd_brg,2,2) = h.kd_bid 
												and substr(g.kd_brg,4,2) = h.kd_kel
												and substr(g.kd_brg,6,2) = h.kd_skel
												and substr(g.kd_brg,8,3) = h.kd_sskel
												and (kd_brg like '2120102%'  or  kd_brg like '2120203%' or kd_brg like '2120204001%'  or  kd_brg like '2060101048%' or
										(
										    h.kd_gol = '2'
										and h.kd_bid = '12'
										and h.kd_kel = '02'
										and h.kd_skel = '04'
										and h.kd_sskel != '001'
										) ) 		
										and h.ur_sskel like ?	
											limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
														
				$n_peg 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nipterima);
				
				$q_gedung_lantai 			= $db->fetchCol('select q_gedung_lantai  
														from  e_ast_ruangan_0_tr 
														where i_ruang = ? ',$result[$j]->i_ruang);
														
				$i_komputer_macaddress 			= $db->fetchCol('select i_komputer_macaddress  
														from  e_ast_komputer_0_tr 
														where i_aset = ? ',$result[$j]->i_aset);
				
				$i_komputer_serialpc 			= $db->fetchCol('select i_komputer_serialpc  
														from  e_ast_komputer_0_tr 
														where i_aset = ? ',$result[$j]->i_aset);
														
				$hasilAkhir[$j] = array("i_barang_serah"            =>(string)$result[$j]->i_barang_serah,
										 "d_aset_thnanggar"         =>(string)$result[$j]->d_aset_thnanggar,
										 "thn_ang"					=>(string)$result[$j]->thn_ang,
										 "c_barang"           		=>(string)$result[$j]->c_barang,
										 "i_aset"         			=>(string)$result[$j]->i_aset,
										 "ur_sskel"          		=>(string)$result[$j]->ur_sskel,
										 "i_peg_nipterima"          =>(string)$result[$j]->i_peg_nipterima,
										 "n_peg"                    =>$n_peg[0],
										 "i_orgb_penerima"          =>(string)$result[$j]->i_orgb_penerima,										 
										 "i_komputer_macaddress"    =>$i_komputer_macaddress[0],
										 "i_komputer_serialpc"      =>$i_komputer_serialpc[0],
										 "i_ruang"   				=>(string)$result[$j]->i_ruang,
										 "q_gedung_lantai"   	    =>$q_gedung_lantai[0]);
									
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	// Ina : Awal : 29-10-2008  
	public function getCariAsetbyAll($pageNumber,$itemPerPage,$unitKerja,$nmBarang,$tahunPerolehan) 
	{   
	   //echo "masuk services semua ";
	   	
	   $unitkrj = strtoupper($unitKerja);
	   $unit = '%'.$unitkrj.'%';   
		   		   
	   $nbrg = strtoupper($nmBarang);
	   $brg = '%'.$nbrg.'%';
	   
	   $tahun = strtoupper($tahunPerolehan);
	   $thn = '%'.$tahun.'%';
	   
	   //echo "/tahun serv =".$thn;
	   //echo "/unit serv =".$unit;
	   //echo "/brg serv =".$brg;
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	   	     
	     $where[] = $brg;
		 $where[] = $thn;
		 $where[] = $brg;
		 $where[] = $thn;
		 
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$result = $db->fetchAll("SELECT	distinct d.i_barang_serah,d.i_peg_nipterima,d.i_orgb_penerima, to_char(c.d_barang_peroleh,'yyyy') as thn_ang, c.c_barang, 
									to_char(c.i_aset,'09999') as  i_aset , ur_sskel,c.i_ruang
									FROM e_ast_dir_item_tm  c, e_ast_dir_0_tm d,e_sabm_t_master_tm A, e_ast_sskel_0_tr B
									where substr(a.kd_brg,1,1) = b.kd_gol
									and substr(a.kd_brg,2,2) = b.kd_bid 
									and substr(a.kd_brg,4,2) = b.kd_kel
									and substr(a.kd_brg,6,2) = b.kd_skel
									and substr(a.kd_brg,8,3) = b.kd_sskel
									and to_char(c.d_barang_peroleh,'yyyy')= to_char(tgl_perlh,'yyyy')
									and   c.c_barang = a.kd_brg and   c.i_aset = a.no_aset
									and c.i_barang_serah=d.i_barang_serah
									and (d.i_barang_serah  like '%KYN%' or d.i_barang_serah like '%HYN%')
									and ur_sskel like ?
									and to_char(c.d_barang_peroleh,'yyyy') like ?
									union
									SELECT distinct
									null as i_barang_serah,null as i_peg_nipterima, null as i_orgb_penerima, to_char(tgl_perlh,'yyyy') as thn_ang, g.kd_brg as c_barang, to_char(g.no_aset,'09999') as  i_aset , ur_sskel,null as i_ruang
									FROM e_sabm_t_master_tm G, e_ast_sskel_0_tr H
									where substr(g.kd_brg,1,1) = h.kd_gol
									and substr(g.kd_brg,2,2) = h.kd_bid 
									and substr(g.kd_brg,4,2) = h.kd_kel
									and substr(g.kd_brg,6,2) = h.kd_skel
									and substr(g.kd_brg,8,3) = h.kd_sskel
									and (kd_brg like '2120102%'  or  kd_brg like '2120203%' or kd_brg like '2120204001%'  or  kd_brg like '2060101048%' or
									(
									h.kd_gol = '2'
									and h.kd_bid = '12'
									and h.kd_kel = '02'
									and h.kd_skel = '04'
									and h.kd_sskel != '001'
									) ) 		
									and h.ur_sskel like ? and to_char(tgl_perlh,'yyyy') like ?
									and not exists (select c.c_barang FROM e_ast_dir_item_tm c
									where to_char(c.d_barang_peroleh,'yyyy')= to_char(g.tgl_perlh,'yyyy')
									and   c.c_barang = g.kd_brg
									and   c.i_aset = g.no_aset)",$where); 
			 
			 $hasilAkhir = count($result);
			 //echo " jumlah semua ".$jmlResult;
			
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT distinct d.i_barang_serah,d.i_peg_nipterima,d.i_orgb_penerima, to_char(c.d_barang_peroleh,'yyyy') as thn_ang, c.c_barang, 
									to_char(c.i_aset,'09999') as  i_aset , ur_sskel,c.i_ruang
									FROM e_ast_dir_item_tm  c, e_ast_dir_0_tm d,e_sabm_t_master_tm A, e_ast_sskel_0_tr B
									where substr(a.kd_brg,1,1) = b.kd_gol
									and substr(a.kd_brg,2,2) = b.kd_bid 
									and substr(a.kd_brg,4,2) = b.kd_kel
									and substr(a.kd_brg,6,2) = b.kd_skel
									and substr(a.kd_brg,8,3) = b.kd_sskel
									and to_char(c.d_barang_peroleh,'yyyy')= to_char(tgl_perlh,'yyyy')
									and   c.c_barang = a.kd_brg and   c.i_aset = a.no_aset
									and c.i_barang_serah=d.i_barang_serah
									and (d.i_barang_serah  like '%KYN%' or d.i_barang_serah like '%HYN%')
									and ur_sskel like ?
									and to_char(c.d_barang_peroleh,'yyyy') like ?
									union
									SELECT distinct
									null as i_barang_serah,null as i_peg_nipterima, null as i_orgb_penerima, to_char(tgl_perlh,'yyyy') as thn_ang, g.kd_brg as c_barang, to_char(g.no_aset,'09999') as  i_aset , ur_sskel,null as i_ruang
									FROM e_sabm_t_master_tm G, e_ast_sskel_0_tr H
									where substr(g.kd_brg,1,1) = h.kd_gol
									and substr(g.kd_brg,2,2) = h.kd_bid 
									and substr(g.kd_brg,4,2) = h.kd_kel
									and substr(g.kd_brg,6,2) = h.kd_skel
									and substr(g.kd_brg,8,3) = h.kd_sskel
									and (kd_brg like '2120102%'  or  kd_brg like '2120203%' or kd_brg like '2120204001%'  or  kd_brg like '2060101048%' or
									(
									h.kd_gol = '2'
									and h.kd_bid = '12'
									and h.kd_kel = '02'
									and h.kd_skel = '04'
									and h.kd_sskel != '001'
									) ) 		
									and h.ur_sskel like ? and to_char(tgl_perlh,'yyyy') like ?
									and not exists (select c.c_barang FROM e_ast_dir_item_tm c
									where to_char(c.d_barang_peroleh,'yyyy')= to_char(g.tgl_perlh,'yyyy')
									and   c.c_barang = g.kd_brg
									and   c.i_aset = g.no_aset)
											limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 //echo " jumlah yg ada param ".$jmlResult;
			 for ($j = 0; $j < $jmlResult; $j++) {
														
				$n_peg 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nipterima);
				
				$q_gedung_lantai 			= $db->fetchCol('select q_gedung_lantai  
														from  e_ast_ruangan_0_tr 
														where i_ruang = ? ',$result[$j]->i_ruang);
														
				$i_komputer_macaddress 			= $db->fetchCol("select i_komputer_macaddress  
														from  e_ast_komputer_0_tr 
														where d_anggaran = '".(string)$result[$j]->thn_ang."'
														and c_barang = '".(string)$result[$j]->c_barang."'
														and i_aset = ".$result[$j]->i_aset);
				
				$i_komputer_serialpc 			= $db->fetchCol("select i_komputer_serialpc  
														from  e_ast_komputer_0_tr 
														where d_anggaran = '".(string)$result[$j]->thn_ang."'
														and c_barang = '".(string)$result[$j]->c_barang."'
														and i_aset = ".$result[$j]->i_aset);
														
				$i_hw_investasi 			= $db->fetchCol("select i_hw_investasi  
														from  e_ast_komputer_0_tr 
														where d_anggaran = '".(string)$result[$j]->thn_ang."'
														and c_barang = '".(string)$result[$j]->c_barang."'
														and i_aset = ".$result[$j]->i_aset);
														
				if($i_hw_investasi[0]=='')
				{
					$i_hw_investasi 			= $db->fetchCol("select i_hw_investasi  
														from  e_ast_hardware_0_tm 
														where d_anggaran = '".(string)$result[$j]->thn_ang."'
														and c_barang = '".(string)$result[$j]->c_barang."'
														and i_aset = ".$result[$j]->i_aset);
				} 
														
				$hasilAkhir[$j] = array("i_barang_serah"            =>(string)$result[$j]->i_barang_serah,
										 "d_aset_thnanggar"         =>(string)$result[$j]->d_aset_thnanggar,
										 "thn_ang"					=>(string)$result[$j]->thn_ang,
										 "c_barang"           		=>(string)$result[$j]->c_barang,
										 "i_aset"         			=>(string)$result[$j]->i_aset,
										 "ur_sskel"          		=>(string)$result[$j]->ur_sskel,
										 "i_peg_nipterima"          =>(string)$result[$j]->i_peg_nipterima,
										 "n_peg"                    =>$n_peg[0],
										 "i_orgb_penerima"          =>(string)$result[$j]->i_orgb_penerima,										 
										 "i_komputer_macaddress"    =>$i_komputer_macaddress[0],
										 "i_komputer_serialpc"      =>$i_komputer_serialpc[0],
										 "i_ruang"   				=>(string)$result[$j]->i_ruang,
										 "q_gedung_lantai"   	    =>$q_gedung_lantai[0],
										 "i_hw_investasi"   	    =>$i_hw_investasi[0]);
									
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	
	
	public function getCariAsetbyBelumTerdistribusi($pageNumber,$itemPerPage,$nmBarang,$tahunPerolehan) 
	{   
	   	   
	   $nbrg = strtoupper($nmBarang);
	   $brg = '%'.$nbrg.'%';
	   
	   $tahun = strtoupper($tahunPerolehan);
	   $thn = '%'.$tahun.'%';
	   
	    $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	   
	     $where[] = $brg;
		 $where[] = $thn;
		 
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$result = $db->fetchAll("SELECT distinct
									null as i_barang_serah,null as i_peg_nipterima, null as i_orgb_penerima, to_char(tgl_perlh,'yyyy') as thn_ang, g.kd_brg as c_barang, to_char(g.no_aset,'09999') as  i_aset , ur_sskel,null as i_ruang
									FROM e_sabm_t_master_tm G, e_ast_sskel_0_tr H
									where substr(g.kd_brg,1,1) = h.kd_gol
									and substr(g.kd_brg,2,2) = h.kd_bid 
									and substr(g.kd_brg,4,2) = h.kd_kel
									and substr(g.kd_brg,6,2) = h.kd_skel
									and substr(g.kd_brg,8,3) = h.kd_sskel
									and (kd_brg like '2120102%'  or  kd_brg like '2120203%' or kd_brg like '2120204001%'  or  kd_brg like '2060101048%' or
									(
									h.kd_gol = '2'
									and h.kd_bid = '12'
									and h.kd_kel = '02'
									and h.kd_skel = '04'
									and h.kd_sskel != '001'
									) ) 		
									and h.ur_sskel like ? and to_char(tgl_perlh,'yyyy') like ?
									and not exists (select c.c_barang FROM e_ast_dir_item_tm c
									where to_char(c.d_barang_peroleh,'yyyy')= to_char(g.tgl_perlh,'yyyy')
									and   c.c_barang = g.kd_brg
									and   c.i_aset = g.no_aset)	",$where); 
			 
			 $hasilAkhir = count($result);
			 //echo "hasilAkhir ".$hasilAkhir;
			
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT distinct
									null as i_barang_serah,null as i_peg_nipterima, null as i_orgb_penerima, to_char(tgl_perlh,'yyyy') as thn_ang, g.kd_brg as c_barang, to_char(g.no_aset,'09999') as  i_aset , ur_sskel,null as i_ruang
									FROM e_sabm_t_master_tm G, e_ast_sskel_0_tr H
									where substr(g.kd_brg,1,1) = h.kd_gol
									and substr(g.kd_brg,2,2) = h.kd_bid 
									and substr(g.kd_brg,4,2) = h.kd_kel
									and substr(g.kd_brg,6,2) = h.kd_skel
									and substr(g.kd_brg,8,3) = h.kd_sskel
									and (kd_brg like '2120102%'  or  kd_brg like '2120203%' or kd_brg like '2120204001%'  or  kd_brg like '2060101048%' or
									(
									h.kd_gol = '2'
									and h.kd_bid = '12'
									and h.kd_kel = '02'
									and h.kd_skel = '04'
									and h.kd_sskel != '001'
									) ) 		
									and h.ur_sskel like ? and to_char(tgl_perlh,'yyyy') like ?
									and not exists (select c.c_barang FROM e_ast_dir_item_tm c
									where to_char(c.d_barang_peroleh,'yyyy')= to_char(g.tgl_perlh,'yyyy')
									and   c.c_barang = g.kd_brg
									and   c.i_aset = g.no_aset)
											limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 //echo "jmlResult ".$jmlResult;
			 for ($j = 0; $j < $jmlResult; $j++) {
														
				$n_peg 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nipterima);
				
				$q_gedung_lantai 			= $db->fetchCol('select q_gedung_lantai  
														from  e_ast_ruangan_0_tr 
														where i_ruang = ? ',$result[$j]->i_ruang);
														
				$i_komputer_macaddress 			= $db->fetchCol("select i_komputer_macaddress  
														from  e_ast_komputer_0_tr 
														where d_anggaran = '".(string)$result[$j]->thn_ang."'
														and c_barang = '".(string)$result[$j]->c_barang."'
														and i_aset = ".$result[$j]->i_aset);
				
				$i_komputer_serialpc 			= $db->fetchCol("select i_komputer_serialpc  
														from  e_ast_komputer_0_tr 
														where d_anggaran = '".(string)$result[$j]->thn_ang."'
														and c_barang = '".(string)$result[$j]->c_barang."'
														and i_aset = ".$result[$j]->i_aset);
														
				$i_hw_investasi 			= $db->fetchCol("select i_hw_investasi  
														from  e_ast_komputer_0_tr 
														where d_anggaran = '".(string)$result[$j]->thn_ang."'
														and c_barang = '".(string)$result[$j]->c_barang."'
														and i_aset = ".$result[$j]->i_aset);
														
				if($i_hw_investasi[0]=='')
				{
					$i_hw_investasi 			= $db->fetchCol("select i_hw_investasi  
														from  e_ast_hardware_0_tm 
														where d_anggaran = '".(string)$result[$j]->thn_ang."'
														and c_barang = '".(string)$result[$j]->c_barang."'
														and i_aset = ".$result[$j]->i_aset);
				} 
														
				$hasilAkhir[$j] = array("i_barang_serah"            =>(string)$result[$j]->i_barang_serah,
										 "d_aset_thnanggar"         =>(string)$result[$j]->d_aset_thnanggar,
										 "thn_ang"					=>(string)$result[$j]->thn_ang,
										 "c_barang"           		=>(string)$result[$j]->c_barang,
										 "i_aset"         			=>(string)$result[$j]->i_aset,
										 "ur_sskel"          		=>(string)$result[$j]->ur_sskel,
										 "i_peg_nipterima"          =>(string)$result[$j]->i_peg_nipterima,
										 "n_peg"                    =>$n_peg[0],
										 "i_orgb_penerima"          =>(string)$result[$j]->i_orgb_penerima,										 
										 "i_komputer_macaddress"    =>$i_komputer_macaddress[0],
										 "i_komputer_serialpc"      =>$i_komputer_serialpc[0],
										 "i_ruang"   				=>(string)$result[$j]->i_ruang,
										 "q_gedung_lantai"   	    =>$q_gedung_lantai[0],
										 "i_hw_investasi"   	    =>$i_hw_investasi[0]);
									
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function getCariAsetTIBySudahTerdistribusi($pageNumber,$itemPerPage,$unitKerja,$nmBarang,$thnPerolehan) 
	{   
	   $unitkr = strtoupper($unitKerja);
	   $unit = '%'.$unitkr.'%';
	   
	   $nbrg = strtoupper($nmBarang);
	   $nbrg = trim($nmBarang);
	   $brg = '%'.$nbrg.'%';
	   
	   $tahun = strtoupper($thnPerolehan);
	   $thn = '%'.$tahun.'%';
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unit;
	     $where[] = $brg;		 
		 $where[] = $thn;
		 
		 		 
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$result = $db->fetchAll("SELECT	distinct d.i_barang_serah,d.i_peg_nipterima,d.i_orgb_penerima, to_char(c.d_barang_peroleh,'yyyy') as thn_ang, c.c_barang, to_char(c.i_aset,'09999') as  i_aset , ur_sskel,c.i_ruang
										    FROM e_ast_dir_item_tm  c, e_ast_dir_0_tm d,e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where 
												substr(a.kd_brg,1,1) = b.kd_gol
														and substr(a.kd_brg,2,2) = b.kd_bid 
														and substr(a.kd_brg,4,2) = b.kd_kel
														and substr(a.kd_brg,6,2) = b.kd_skel
														and substr(a.kd_brg,8,3) = b.kd_sskel
											and
											to_char(c.d_barang_peroleh,'yyyy')= to_char(tgl_perlh,'yyyy')
													and   c.c_barang = a.kd_brg
													and   c.i_aset = a.no_aset
											and c.i_barang_serah=d.i_barang_serah
											and (d.i_barang_serah
											 like '%KYN%' or d.i_barang_serah
											 like '%HYN%')
											and i_orgb_penerima like ? and ur_sskel like ?
											and to_char(c.d_barang_peroleh,'yyyy') like ?",$where); 
			 
			 $hasilAkhir = count($result);
			
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT distinct d.i_barang_serah,d.i_peg_nipterima,d.i_orgb_penerima, to_char(c.d_barang_peroleh,'yyyy') as thn_ang, c.c_barang, to_char(c.i_aset,'09999') as  i_aset , ur_sskel,c.i_ruang
										    FROM e_ast_dir_item_tm  c, e_ast_dir_0_tm d,e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where 
												substr(a.kd_brg,1,1) = b.kd_gol
														and substr(a.kd_brg,2,2) = b.kd_bid 
														and substr(a.kd_brg,4,2) = b.kd_kel
														and substr(a.kd_brg,6,2) = b.kd_skel
														and substr(a.kd_brg,8,3) = b.kd_sskel
											and
											to_char(c.d_barang_peroleh,'yyyy')= to_char(tgl_perlh,'yyyy')
													and   c.c_barang = a.kd_brg
													and   c.i_aset = a.no_aset
											and c.i_barang_serah=d.i_barang_serah
											and (d.i_barang_serah
											 like '%KYN%' or d.i_barang_serah
											 like '%HYN%')
											and i_orgb_penerima like ? and ur_sskel like ?
											and to_char(c.d_barang_peroleh,'yyyy') like ?
											limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$n_peg 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nipterima);
				
				$q_gedung_lantai 			= $db->fetchCol('select q_gedung_lantai  
														from  e_ast_ruangan_0_tr 
														where i_ruang = ? ',$result[$j]->i_ruang);
														
				$i_komputer_macaddress 			= $db->fetchCol("select i_komputer_macaddress  
														from  e_ast_komputer_0_tr 
														where d_anggaran = '".(string)$result[$j]->thn_ang."'
														and c_barang = '".(string)$result[$j]->c_barang."'
														and i_aset = ".$result[$j]->i_aset);
				
				$i_komputer_serialpc 			= $db->fetchCol("select i_komputer_serialpc  
														from  e_ast_komputer_0_tr 
														where d_anggaran = '".(string)$result[$j]->thn_ang."'
														and c_barang = '".(string)$result[$j]->c_barang."'
														and i_aset = ".$result[$j]->i_aset);
														
				$i_hw_investasi 			= $db->fetchCol("select i_hw_investasi  
														from  e_ast_komputer_0_tr 
														where d_anggaran = '".(string)$result[$j]->thn_ang."'
														and c_barang = '".(string)$result[$j]->c_barang."'
														and i_aset = ".$result[$j]->i_aset);
														
				if($i_hw_investasi[0]=='')
				{
					$i_hw_investasi 			= $db->fetchCol("select i_hw_investasi  
														from  e_ast_hardware_0_tm 
														where d_anggaran = '".(string)$result[$j]->thn_ang."'
														and c_barang = '".(string)$result[$j]->c_barang."'
														and i_aset = ".$result[$j]->i_aset);
				} 
														
				$hasilAkhir[$j] = array("i_barang_serah"            =>(string)$result[$j]->i_barang_serah,
										 "d_aset_thnanggar"         =>(string)$result[$j]->d_aset_thnanggar,
										 "thn_ang"					=>(string)$result[$j]->thn_ang,
										 "c_barang"           		=>(string)$result[$j]->c_barang,
										 "i_aset"         			=>(string)$result[$j]->i_aset,
										 "ur_sskel"          		=>(string)$result[$j]->ur_sskel,
										 "i_peg_nipterima"          =>(string)$result[$j]->i_peg_nipterima,
										 "n_peg"                    =>$n_peg[0],
										 "i_orgb_penerima"          =>(string)$result[$j]->i_orgb_penerima,										 
										 "i_komputer_macaddress"    =>$i_komputer_macaddress[0],
										 "i_komputer_serialpc"      =>$i_komputer_serialpc[0],
										 "i_ruang"   				=>(string)$result[$j]->i_ruang,
										 "q_gedung_lantai"   	    =>$q_gedung_lantai[0],
										 "i_hw_investasi"   	    =>$i_hw_investasi[0]);
									
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	// Ina : Akhir : 29-10-2008
	
	
	// Ina : Awal : 13-10-2208
	public function getCetakAsetbyAll($unitKerja,$nmBarang,$tahunPerolehan) 
	{   
	   //echo "masuk services semua ";
	   	
	   $unitkrj = strtoupper($unitKerja);
	   $unit = '%'.$unitkrj.'%';   
		   		   
	   $nbrg = strtoupper($nmBarang);
	   $brg = '%'.$nbrg.'%';
	   
	   $tahun = strtoupper($tahunPerolehan);
	   $thn = '%'.$tahun.'%';
	   
	   	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	   	     
	     $where[] = $brg;
		 $where[] = $thn;
		 $where[] = $brg;
		 $where[] = $thn;
		 
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 $result = $db->fetchAll("SELECT distinct d.i_barang_serah,d.i_peg_nipterima,d.i_orgb_penerima, to_char(c.d_barang_peroleh,'yyyy') as thn_ang, c.c_barang, 
									to_char(c.i_aset,'09999') as  i_aset , ur_sskel,c.i_ruang
									FROM e_ast_dir_item_tm  c, e_ast_dir_0_tm d,e_sabm_t_master_tm A, e_ast_sskel_0_tr B
									where substr(a.kd_brg,1,1) = b.kd_gol
									and substr(a.kd_brg,2,2) = b.kd_bid 
									and substr(a.kd_brg,4,2) = b.kd_kel
									and substr(a.kd_brg,6,2) = b.kd_skel
									and substr(a.kd_brg,8,3) = b.kd_sskel
									and to_char(c.d_barang_peroleh,'yyyy')= to_char(tgl_perlh,'yyyy')
									and   c.c_barang = a.kd_brg and   c.i_aset = a.no_aset
									and c.i_barang_serah=d.i_barang_serah
									and (d.i_barang_serah  like '%KYN%' or d.i_barang_serah like '%HYN%')
									and ur_sskel like ?
									and to_char(c.d_barang_peroleh,'yyyy') like ?
									union
									SELECT distinct
									null as i_barang_serah,null as i_peg_nipterima, null as i_orgb_penerima, to_char(tgl_perlh,'yyyy') as thn_ang, g.kd_brg as c_barang, to_char(g.no_aset,'09999') as  i_aset , ur_sskel,null as i_ruang
									FROM e_sabm_t_master_tm G, e_ast_sskel_0_tr H
									where substr(g.kd_brg,1,1) = h.kd_gol
									and substr(g.kd_brg,2,2) = h.kd_bid 
									and substr(g.kd_brg,4,2) = h.kd_kel
									and substr(g.kd_brg,6,2) = h.kd_skel
									and substr(g.kd_brg,8,3) = h.kd_sskel
									and (kd_brg like '2120102%'  or  kd_brg like '2120203%' or kd_brg like '2120204001%'  or  kd_brg like '2060101048%' or
									(
									h.kd_gol = '2'
									and h.kd_bid = '12'
									and h.kd_kel = '02'
									and h.kd_skel = '04'
									and h.kd_sskel != '001'
									) ) 		
									and h.ur_sskel like ? and to_char(tgl_perlh,'yyyy') like ?
									and not exists (select c.c_barang FROM e_ast_dir_item_tm c
									where to_char(c.d_barang_peroleh,'yyyy')= to_char(g.tgl_perlh,'yyyy')
									and   c.c_barang = g.kd_brg
									and   c.i_aset = g.no_aset) ",$where); 
			 
			 $jmlResult = count($result);
			 //echo " jumlah yg ada param ".$jmlResult;
			 for ($j = 0; $j < $jmlResult; $j++) {
														
				$n_peg 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nipterima);
				
				$q_gedung_lantai 			= $db->fetchCol('select q_gedung_lantai  
														from  e_ast_ruangan_0_tr 
														where i_ruang = ? ',$result[$j]->i_ruang);
														
				$i_komputer_macaddress 			= $db->fetchCol("select i_komputer_macaddress  
														from  e_ast_komputer_0_tr 
														where d_anggaran = '".(string)$result[$j]->thn_ang."'
														and c_barang = '".(string)$result[$j]->c_barang."'
														and i_aset = ".$result[$j]->i_aset);
				
				$i_komputer_serialpc 			= $db->fetchCol("select i_komputer_serialpc  
														from  e_ast_komputer_0_tr 
														where d_anggaran = '".(string)$result[$j]->thn_ang."'
														and c_barang = '".(string)$result[$j]->c_barang."'
														and i_aset = ".$result[$j]->i_aset);
														
				$i_hw_investasi 			= $db->fetchCol("select i_hw_investasi  
														from  e_ast_komputer_0_tr 
														where d_anggaran = '".(string)$result[$j]->thn_ang."'
														and c_barang = '".(string)$result[$j]->c_barang."'
														and i_aset = ".$result[$j]->i_aset);
														
				if($i_hw_investasi[0]=='')
				{
					$i_hw_investasi 			= $db->fetchCol("select i_hw_investasi  
														from  e_ast_hardware_0_tm 
														where d_anggaran = '".(string)$result[$j]->thn_ang."'
														and c_barang = '".(string)$result[$j]->c_barang."'
														and i_aset = ".$result[$j]->i_aset);
				} 
														
				$hasilAkhir[$j] = array("i_barang_serah"            =>(string)$result[$j]->i_barang_serah,
										 "d_aset_thnanggar"         =>(string)$result[$j]->d_aset_thnanggar,
										 "thn_ang"					=>(string)$result[$j]->thn_ang,
										 "c_barang"           		=>(string)$result[$j]->c_barang,
										 "i_aset"         			=>(string)$result[$j]->i_aset,
										 "ur_sskel"          		=>(string)$result[$j]->ur_sskel,
										 "i_peg_nipterima"          =>(string)$result[$j]->i_peg_nipterima,
										 "n_peg"                    =>$n_peg[0],
										 "i_orgb_penerima"          =>(string)$result[$j]->i_orgb_penerima,										 
										 "i_komputer_macaddress"    =>$i_komputer_macaddress[0],
										 "i_komputer_serialpc"      =>$i_komputer_serialpc[0],
										 "i_ruang"   				=>(string)$result[$j]->i_ruang,
										 "q_gedung_lantai"   	    =>$q_gedung_lantai[0],
										 "i_hw_investasi"   	    =>$i_hw_investasi[0]);
									
			 }	
			 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	
	
	public function getCetakAsetbyBelumTerdistribusi($nmBarang,$tahunPerolehan) 
	{   
	   	   
	   $nbrg = strtoupper($nmBarang);
	   $brg = '%'.$nbrg.'%';
	   
	   $tahun = strtoupper($tahunPerolehan);
	   $thn = '%'.$tahun.'%';
	   
	    $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	   
	     $where[] = $brg;
		 $where[] = $thn;
		 
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			 $result = $db->fetchAll("SELECT distinct
									null as i_barang_serah,null as i_peg_nipterima, null as i_orgb_penerima, to_char(tgl_perlh,'yyyy') as thn_ang, g.kd_brg as c_barang, to_char(g.no_aset,'09999') as  i_aset , ur_sskel,null as i_ruang
									FROM e_sabm_t_master_tm G, e_ast_sskel_0_tr H
									where substr(g.kd_brg,1,1) = h.kd_gol
									and substr(g.kd_brg,2,2) = h.kd_bid 
									and substr(g.kd_brg,4,2) = h.kd_kel
									and substr(g.kd_brg,6,2) = h.kd_skel
									and substr(g.kd_brg,8,3) = h.kd_sskel
									and (kd_brg like '2120102%'  or  kd_brg like '2120203%' or kd_brg like '2120204001%'  or  kd_brg like '2060101048%' or
									(
									h.kd_gol = '2'
									and h.kd_bid = '12'
									and h.kd_kel = '02'
									and h.kd_skel = '04'
									and h.kd_sskel != '001'
									) ) 		
									and h.ur_sskel like ? and to_char(tgl_perlh,'yyyy') like ?
									and not exists (select c.c_barang FROM e_ast_dir_item_tm c
									where to_char(c.d_barang_peroleh,'yyyy')= to_char(g.tgl_perlh,'yyyy')
									and   c.c_barang = g.kd_brg
									and   c.i_aset = g.no_aset) ",$where); 
			 
			 $jmlResult = count($result);
			 echo "jmlResult ".$jmlResult;
			 for ($j = 0; $j < $jmlResult; $j++) {
														
				$n_peg 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nipterima);
				
				$q_gedung_lantai 			= $db->fetchCol('select q_gedung_lantai  
														from  e_ast_ruangan_0_tr 
														where i_ruang = ? ',$result[$j]->i_ruang);
														
				$i_komputer_macaddress 			= $db->fetchCol("select i_komputer_macaddress  
														from  e_ast_komputer_0_tr 
														where d_anggaran = '".(string)$result[$j]->thn_ang."'
														and c_barang = '".(string)$result[$j]->c_barang."'
														and i_aset = ".$result[$j]->i_aset);
				
				$i_komputer_serialpc 			= $db->fetchCol("select i_komputer_serialpc  
														from  e_ast_komputer_0_tr 
														where d_anggaran = '".(string)$result[$j]->thn_ang."'
														and c_barang = '".(string)$result[$j]->c_barang."'
														and i_aset = ".$result[$j]->i_aset);
														
				$i_hw_investasi 			= $db->fetchCol("select i_hw_investasi  
														from  e_ast_komputer_0_tr 
														where d_anggaran = '".(string)$result[$j]->thn_ang."'
														and c_barang = '".(string)$result[$j]->c_barang."'
														and i_aset = ".$result[$j]->i_aset);
														
				if($i_hw_investasi[0]=='')
				{
					$i_hw_investasi 			= $db->fetchCol("select i_hw_investasi  
														from  e_ast_hardware_0_tm 
														where d_anggaran = '".(string)$result[$j]->thn_ang."'
														and c_barang = '".(string)$result[$j]->c_barang."'
														and i_aset = ".$result[$j]->i_aset);
				} 
														
				$hasilAkhir[$j] = array("i_barang_serah"            =>(string)$result[$j]->i_barang_serah,
										 "d_aset_thnanggar"         =>(string)$result[$j]->d_aset_thnanggar,
										 "thn_ang"					=>(string)$result[$j]->thn_ang,
										 "c_barang"           		=>(string)$result[$j]->c_barang,
										 "i_aset"         			=>(string)$result[$j]->i_aset,
										 "ur_sskel"          		=>(string)$result[$j]->ur_sskel,
										 "i_peg_nipterima"          =>(string)$result[$j]->i_peg_nipterima,
										 "n_peg"                    =>$n_peg[0],
										 "i_orgb_penerima"          =>(string)$result[$j]->i_orgb_penerima,										 
										 "i_komputer_macaddress"    =>$i_komputer_macaddress[0],
										 "i_komputer_serialpc"      =>$i_komputer_serialpc[0],
										 "i_ruang"   				=>(string)$result[$j]->i_ruang,
										 "q_gedung_lantai"   	    =>$q_gedung_lantai[0],
										 "i_hw_investasi"   	    =>$i_hw_investasi[0]);
									
			 }	
			 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function getCetakAsetTIBySudahTerdistribusi($unitKerja,$nmBarang,$thnPerolehan) 
	{   
	   $unitkr = strtoupper($unitKerja);
	   $unit = '%'.$unitkr.'%';
	   
	   $nbrg = strtoupper($nmBarang);
	   $brg = '%'.$nbrg.'%';
	   
		   
	   $tahun = strtoupper($thnPerolehan);
	   $thn = '%'.$tahun.'%';
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $unit;
	     $where[] = $brg;		 
		 $where[] = $thn;
		 
		 		 
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT distinct d.i_barang_serah,d.i_peg_nipterima,d.i_orgb_penerima, to_char(c.d_barang_peroleh,'yyyy') as thn_ang, c.c_barang, to_char(c.i_aset,'09999') as  i_aset , ur_sskel,c.i_ruang
										    FROM e_ast_dir_item_tm  c, e_ast_dir_0_tm d,e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where 
												substr(a.kd_brg,1,1) = b.kd_gol
														and substr(a.kd_brg,2,2) = b.kd_bid 
														and substr(a.kd_brg,4,2) = b.kd_kel
														and substr(a.kd_brg,6,2) = b.kd_skel
														and substr(a.kd_brg,8,3) = b.kd_sskel
											and
											to_char(c.d_barang_peroleh,'yyyy')= to_char(tgl_perlh,'yyyy')
													and   c.c_barang = a.kd_brg
													and   c.i_aset = a.no_aset
											and c.i_barang_serah=d.i_barang_serah
											and (d.i_barang_serah
											 like '%KYN%' or d.i_barang_serah
											 like '%HYN%')
											and i_orgb_penerima like ? and ur_sskel like ?
											and to_char(c.d_barang_peroleh,'yyyy') like ? ",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$n_peg 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nipterima);
				
				$q_gedung_lantai 			= $db->fetchCol('select q_gedung_lantai  
														from  e_ast_ruangan_0_tr 
														where i_ruang = ? ',$result[$j]->i_ruang);
														
				$i_komputer_macaddress 			= $db->fetchCol("select i_komputer_macaddress  
														from  e_ast_komputer_0_tr 
														where d_anggaran = '".(string)$result[$j]->thn_ang."'
														and c_barang = '".(string)$result[$j]->c_barang."'
														and i_aset = ".$result[$j]->i_aset);
				
				$i_komputer_serialpc 			= $db->fetchCol("select i_komputer_serialpc  
														from  e_ast_komputer_0_tr 
														where d_anggaran = '".(string)$result[$j]->thn_ang."'
														and c_barang = '".(string)$result[$j]->c_barang."'
														and i_aset = ".$result[$j]->i_aset);
														
				$i_hw_investasi 			= $db->fetchCol("select i_hw_investasi  
														from  e_ast_komputer_0_tr 
														where d_anggaran = '".(string)$result[$j]->thn_ang."'
														and c_barang = '".(string)$result[$j]->c_barang."'
														and i_aset = ".$result[$j]->i_aset);
														
				if($i_hw_investasi[0]=='')
				{
					$i_hw_investasi 			= $db->fetchCol("select i_hw_investasi  
														from  e_ast_hardware_0_tm 
														where d_anggaran = '".(string)$result[$j]->thn_ang."'
														and c_barang = '".(string)$result[$j]->c_barang."'
														and i_aset = ".$result[$j]->i_aset);
				} 
														
				$hasilAkhir[$j] = array("i_barang_serah"            =>(string)$result[$j]->i_barang_serah,
										 "d_aset_thnanggar"         =>(string)$result[$j]->d_aset_thnanggar,
										 "thn_ang"					=>(string)$result[$j]->thn_ang,
										 "c_barang"           		=>(string)$result[$j]->c_barang,
										 "i_aset"         			=>(string)$result[$j]->i_aset,
										 "ur_sskel"          		=>(string)$result[$j]->ur_sskel,
										 "i_peg_nipterima"          =>(string)$result[$j]->i_peg_nipterima,
										 "n_peg"                    =>$n_peg[0],
										 "i_orgb_penerima"          =>(string)$result[$j]->i_orgb_penerima,										 
										 "i_komputer_macaddress"    =>$i_komputer_macaddress[0],
										 "i_komputer_serialpc"      =>$i_komputer_serialpc[0],
										 "i_ruang"   				=>(string)$result[$j]->i_ruang,
										 "q_gedung_lantai"   	    =>$q_gedung_lantai[0],
										 "i_hw_investasi"   	    =>$i_hw_investasi[0]);
									
			 }	
			 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	// Ina : Akhir : 13-10-2008
}	
?>