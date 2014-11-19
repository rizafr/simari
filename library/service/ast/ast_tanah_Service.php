<?php
class ast_tanah_Service 
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
	
	public function getCariInventarisTanahList($kdbrg,$dari,$sampai) 
	{		
		//echo "+masuk getCariInventarisAngkutanList oh ya";
		$registry = Zend_Registry::getInstance();
	   	$db = $registry->get('db');
		
	   		
	   	try 
	   	{
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if ($kdbrg =='' && $dari =='' && $sampai =='') {			     
				 $result = $db->fetchAll("SELECT distinct A.kd_brg,B.ur_sskel,A.no_aset,A.no_kib,
									A.kuantitas,A.rph_aset,A.luas_tnhs,A.luas_tnhb,A.luas_tnhl,A.luas_tnhk,
									A.kd_prov,A.kd_kab,A.kd_kec,A.kd_kel,A.kd_rtrw,A.alamat,
									A.batas_u,A.batas_s,A.batas_b,A.batas_t,
									A.jns_trn,A.sumber,A.dari,A.dasar_hrg,A.tgl_prl,A.no_dana,A.tgl_dana,
									A.surat1,A.surat2,A.surat3,A.rph_m2,A.unit_pmk,A.catatan							
									FROM e_sabm_t_ktnh_tm A, e_ast_sskel_0_tr B
									where substr(a.kd_brg,1,1) = b.kd_gol
									and substr(a.kd_brg,2,2) = b.kd_bid 
							        and substr(a.kd_brg,4,2) = b.kd_kel
							        and substr(a.kd_brg,6,2) = b.kd_skel
							        and substr(a.kd_brg,8,3) = b.kd_sskel");
				
			} elseif ($kdbrg !='' && $dari == '' && $sampai =='') {						    
				$where[] = $kdbrg;			   	
				$result = $db->fetchAll("SELECT distinct A.kd_brg,B.ur_sskel,A.no_aset,A.no_kib,
									A.kuantitas,A.rph_aset,A.luas_tnhs,A.luas_tnhb,A.luas_tnhl,A.luas_tnhk,
									A.kd_prov,A.kd_kab,A.kd_kec,A.kd_kel,A.kd_rtrw,A.alamat,
									A.batas_u,A.batas_s,A.batas_b,A.batas_t,
									A.jns_trn,A.sumber,A.dari,A.dasar_hrg,A.tgl_prl,A.no_dana,A.tgl_dana,
									A.surat1,A.surat2,A.surat3,A.rph_m2,A.unit_pmk,A.catatan							
									FROM e_sabm_t_ktnh_tm A, e_ast_sskel_0_tr B
									where substr(a.kd_brg,1,1) = b.kd_gol
									and substr(a.kd_brg,2,2) = b.kd_bid 
							        and substr(a.kd_brg,4,2) = b.kd_kel
							        and substr(a.kd_brg,6,2) = b.kd_skel
							        and substr(a.kd_brg,8,3) = b.kd_sskel
							        and kd_brg =?",$where);
			} elseif ($kdbrg !='' && $dari !='' && $sampai !='') {						    
				$where[] = $kdbrg;
			   	$where[] = $dari;	   	
			   	$where[] = $sampai;
				$result = $db->fetchAll("SELECT distinct A.kd_brg,B.ur_sskel,A.no_aset,A.no_kib,
									A.kuantitas,A.rph_aset,A.luas_tnhs,A.luas_tnhb,A.luas_tnhl,A.luas_tnhk,
									A.kd_prov,A.kd_kab,A.kd_kec,A.kd_kel,A.kd_rtrw,A.alamat,
									A.batas_u,A.batas_s,A.batas_b,A.batas_t,
									A.jns_trn,A.sumber,A.dari,A.dasar_hrg,A.tgl_prl,A.no_dana,A.tgl_dana,
									A.surat1,A.surat2,A.surat3,A.rph_m2,A.unit_pmk,A.catatan							
									FROM e_sabm_t_ktnh_tm A, e_ast_sskel_0_tr B
									where substr(a.kd_brg,1,1) = b.kd_gol
									and substr(a.kd_brg,2,2) = b.kd_bid 
							        and substr(a.kd_brg,4,2) = b.kd_kel
							        and substr(a.kd_brg,6,2) = b.kd_skel
							        and substr(a.kd_brg,8,3) = b.kd_sskel
							        and kd_brg =?
							        and no_kib between ? and ?",$where);
			}	 
			
	         	$jmlResult = count($result); 
	         	//echo "+jumlah data services =".$jmlResult;
	         	
			for ($j = 0; $j < $jmlResult; $j++) 
			{
	           	$hasilAkhir[$j] = array("kd_brg" =>(string)$result[$j]->kd_brg,
					"ur_sskel"  =>(string)$result[$j]->ur_sskel, 	
					"no_aset" 	=>(string)$result[$j]->no_aset,
					"no_kib" 	=>(string)$result[$j]->no_kib, 		
					"kuantitas" =>(string)$result[$j]->kuantitas,
					"rph_aset" 	=>(string)$result[$j]->rph_aset, 	
					"luas_tnhs" =>(string)$result[$j]->luas_tnhs,
					"luas_tnhb" =>(string)$result[$j]->luas_tnhb, 	
					"luas_tnhl" =>(string)$result[$j]->luas_tnhl,
					"luas_tnhk" =>(string)$result[$j]->luas_tnhk, 	
					"kd_prov" 	=>(string)$result[$j]->kd_prov,
					"kd_kab" 	=>(string)$result[$j]->kd_kab,
					"kd_kec" 	=>(string)$result[$j]->kd_kec,
					"kd_kel" 	=>(string)$result[$j]->kd_kel,
					"kd_rtrw" 	=>(string)$result[$j]->kd_rtrw,
					"alamat" 	=>(string)$result[$j]->alamat,
					"batas_u" 	=>(string)$result[$j]->batas_u,
					"batas_s" 	=>(string)$result[$j]->batas_s,
					"batas_b" 	=>(string)$result[$j]->batas_b,
					"batas_t" 	=>(string)$result[$j]->batas_t,
					"jns_trn" 	=>(string)$result[$j]->jns_trn,
					"sumber" 	=>(string)$result[$j]->sumber,
					"dari" 		=>(string)$result[$j]->dari,
					"dasar_hrg" =>(string)$result[$j]->dasar_hrg,
					"tgl_prl" 	=>(string)$result[$j]->tgl_prl,
					"no_dana" 	=>(string)$result[$j]->no_dana,
					"tgl_dana" 	=>(string)$result[$j]->tgl_dana,
					"surat1" 	=>(string)$result[$j]->surat1,
					"surat2" 	=>(string)$result[$j]->surat2,
					"surat3" 	=>(string)$result[$j]->surat3,					
					"rph_m2" 	=>(string)$result[$j]->rph_m2,
					"unit_pmk" 	=>(string)$result[$j]->unit_pmk,
					"catatan" 	=>(string)$result[$j]->catatan);
			}					 
		     	return $hasilAkhir;
	   	} 
	   	catch (Exception $e) 
	   	{
         		echo $e->getMessage().'<br>';
	     		return 'gagal <br>';
	   	}
	}
	
	public function getNoInventarisTanah($pageNumber,$itemPerPage) 
	{
		//echo "+masuk services getNoInventarisAngkutanList";
		$namaBarang = strtoupper($namaBarang);
		$kdBrg 	= '101';
		$kbr 	= $kdBrg.'%';
		$nbrg 	= $namaBarang.'%';
				
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{
		 $where[] =$kbr;
		 $where[] =$nbrg;
		 
	         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM 
											(SELECT distinct A.kd_brg,B.ur_sskel
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
										    and substr(a.kd_brg,2,2) = b.kd_bid 
										    and substr(a.kd_brg,4,2) = b.kd_kel
										    and substr(a.kd_brg,6,2) = b.kd_skel
										    and substr(a.kd_brg,8,3) = b.kd_sskel
										    and kd_brg like ? and ur_sskel like ?) temp",$where); 
			
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;					 
			 $result = $db->fetchAll("SELECT distinct A.kd_brg as kd_brg,B.ur_sskel as ur_sskel
									FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
									where substr(a.kd_brg,1,1) = b.kd_gol
									      and substr(a.kd_brg,2,2) = b.kd_bid 
									      and substr(a.kd_brg,4,2) = b.kd_kel
									      and substr(a.kd_brg,6,2) = b.kd_skel
									      and substr(a.kd_brg,8,3) = b.kd_sskel
									      and kd_brg like ? and ur_sskel like ? 
									limit $xLimit offset $xOffset",$where); 
			 $jmlResult = count($result);
			 
			 
			 for ($j = 0; $j < $jmlResult; $j++) 
			 {
				$hasilAkhir[$j] = array("kd_brg"	=>(string)$result[$j]->kd_brg,
				"ur_sskel" 				=>(string)$result[$j]->ur_sskel);
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
	
	//untuk ambil data bidang waktu diklik images cmdCetakAngkut di proseangkut1.phtml
	public function getCariBidang($kdbrg) 
	{		
		//echo "+masuk getCariBidang";
		$registry = Zend_Registry::getInstance();
	   	$db = $registry->get('db');
	   		   	
	   	$kdbrg=$kdbrg;
	   	//echo "+kdbrg di services = ".$kdbrg;
		
	   	try 
	   	{
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
			$result = $db->fetchAll("SELECT a.ur_bid
							FROM e_ast_bid_aset_tr a
							where substr('$kdbrg',1,1) = a.kd_gol
							and substr('$kdbrg',2,2) = a.kd_bid");	
	         	$jmlResult = count($result);	
	         	
	         	//echo "+nama bidang = ".$result[0]->ur_bid;
			for ($j = 0; $j < $jmlResult; $j++) 
			{
	           		$hasilAkhir[$j] = array("ur_bid"  =>(string)$result[$j]->ur_bid);
			}					 
		     	return $hasilAkhir;
	   	} 
	   	catch (Exception $e) 
	   	{
         		echo $e->getMessage().'<br>';
	     		return 'gagal <br>';
	   	}
	}
	
	//untuk ambil data kelompok  waktu diklik images cmdCetakAngkut di proseangkut1.phtml
	public function getCariKelompok($kdbrg) 
	{		
		//echo "+masuk getCariKelompok";
		$registry = Zend_Registry::getInstance();
	   	$db = $registry->get('db');
	   		   	
	   	$kdbrg=$kdbrg;
	   	//echo "+kdbrg di services = ".$kdbrg;
		
	   	try 
	   	{
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$result = $db->fetchAll("SELECT a.ur_kel
							FROM e_ast_kel_aset_tr a
							where substr('$kdbrg',1,1) = a.kd_gol
							and substr('$kdbrg',2,2) = a.kd_bid
							and substr('$kdbrg',4,2) = a.kd_kel");	
	         	$jmlResult = count($result);
	         	
	         	//echo "+nama kelompok = ".$result[0]->ur_kel;		 
			for ($j = 0; $j < $jmlResult; $j++) 
			{
	           		$hasilAkhir[$j] = array("ur_kel"  =>(string)$result[$j]->ur_kel);
			}					 
		     	return $hasilAkhir;
	   	} 
	   	catch (Exception $e) 
	   	{
         		echo $e->getMessage().'<br>';
	     		return 'gagal <br>';
	   	}
	}
	
	//untuk ambil data sub kelompok waktu diklik images cmdCetakAngkut di proseangkut1.phtml
	public function getCariSubKelompok($kdbrg) 
	{		
		//echo "+masuk getCariSubKelompok";
		$registry = Zend_Registry::getInstance();
	   	$db = $registry->get('db');
	   		   	
	   	$kdbrg=$kdbrg;
	   	//echo "+kdbrg di services = ".$kdbrg;
		
	   	try 
	   	{
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$result = $db->fetchAll("SELECT a.ur_skel
							FROM e_ast_skel_0_tr a
							where substr($kdbrg,1,1) = a.kd_gol
							      and substr($kdbrg,2,2) = a.kd_bid 
							      and substr($kdbrg,4,2) = a.kd_kel
							      and substr($kdbrg,6,2) = a.kd_skel");
	         	$jmlResult = count($result);	
	         	
	         	//echo "+nama sub kelompok = ".$result[0]->ur_skel;		
	         	for ($j = 0; $j < $jmlResult; $j++) 
			{
	           		$hasilAkhir[$j] = array("ur_skel"  =>(string)$result[$j]->ur_skel);
			}					 
		     	return $hasilAkhir;
	   	} 
	   	catch (Exception $e) 
	   	{
         		echo $e->getMessage().'<br>';
	     		return 'gagal <br>';
	   	}
	}
	
	
	//untuk ambil data sub sub kelompok waktu diklik images cmdCetakAngkut di proseangkut1.phtml
	public function getCariSubSubKelompok($kdbrg) 
	{		
		//echo "+masuk getCariSubSubKelompok";
		$registry = Zend_Registry::getInstance();
	   	$db = $registry->get('db');
	   		   	
	   	$kdbrg=$kdbrg;
	   	//echo "+kdbrg di services = ".$kdbrg;
		
	   	try 
	   	{
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$result = $db->fetchAll("SELECT a.ur_sskel
							FROM e_ast_sskel_0_tr a
							where substr($kdbrg,1,1) = a.kd_gol
							      and substr($kdbrg,2,2) = a.kd_bid 
							      and substr($kdbrg,4,2) = a.kd_kel
							      and substr($kdbrg,6,2) = a.kd_skel
							      and substr($kdbrg,8,3) = a.kd_sskel");
	         	$jmlResult = count($result);	
	         	
	         	//echo "+nama sub sub kelompok = ".$result[0]->ur_sskel;	 
			for ($j = 0; $j < $jmlResult; $j++) 
			{
	           		$hasilAkhir[$j] = array("ur_sskel"  =>(string)$result[$j]->ur_sskel);
			}					 
		     	return $hasilAkhir;
	   	} 
	   	catch (Exception $e) 
	   	{
         		echo $e->getMessage().'<br>';
	     		return 'gagal <br>';
	   	}
	}
	
	public function getListPegawaiAll($pageNumber,$itemPerPage) {
		 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_sdm_pegawai_0_tm");
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT a.i_peg_nip,a.n_peg,a.n_jabatan,a.i_orgb,b.n_orgb 
									FROM e_sdm_pegawai_0_tm a, e_org_0_0_tm b
									where a.i_orgb=b.i_orgb
									ORDER BY i_peg_nip
									limit $xLimit offset $xOffset"); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_peg_nip"         =>(string)$result[$j]->i_peg_nip,
									"n_peg"           	 	=>(string)$result[$j]->n_peg,
									"n_jabatan"          	=>(string)$result[$j]->n_jabatan,
									"i_orgb"            	=>(string)$result[$j]->i_orgb,
									"n_orgb"            	=>(string)$result[$j]->n_orgb);
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