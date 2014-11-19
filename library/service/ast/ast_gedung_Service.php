<?php
class ast_gedung_Service 
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
//// Perubahan Baru ///	
	public function getCariInventarisGedungList($kdbrg,$dari,$sampai){		
		$registry = Zend_Registry::getInstance();
	   	$db = $registry->get('db');	   	
		$queryInterv= " and no_kib between '$dari' and '$sampai' ";
		if ($dari < 1 && $sampai < 1){
			$queryInterv= " and true ";
		}else{
			if ($dari > 0) $queryInterv= " and no_kib between '$dari' and '$sampai' ";
				else $queryInterv= " and no_kib <= '$sampai' ";
		}
	   	try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$query="SELECT A.kd_brg,B.ur_sskel,A.no_aset,A.no_kib,
				A.kuantitas,A.rph_aset,A.luas_bdg,A.jml_lt,A.type,A.no_imb,
				A.kd_prov,A.kd_kab,A.kd_kec,A.kd_kel,A.kd_rtrw,A.alamat,
				A.thn_sls,A.thn_pakai,A.thnang_kib,A.no_kibtnh,
				A.jns_trn,A.sumber,A.dari,A.dasar_hrg,A.tgl_prl,A.no_dana,A.tgl_dana,
				A.rph_aset,A.rph,A.unit_pmk,A.catatan							
				FROM e_sabm_t_kbdg_tm A, e_ast_sskel_0_tr B
				where substr(a.kd_brg,1,1) = b.kd_gol
				and substr(a.kd_brg,2,2) = b.kd_bid 
			        and substr(a.kd_brg,4,2) = b.kd_kel
			        and substr(a.kd_brg,6,2) = b.kd_skel
			        and substr(a.kd_brg,8,3) = b.kd_sskel
			        and kd_brg ='$kdbrg'";
			$query=$query.$queryInterv;
			$result = $db->fetchAll($query);		        
			$jmlResult = count($result); 
			for ($j = 0; $j < $jmlResult; $j++) {
	           	$hasilAkhir[$j] = array("kd_brg" =>(string)$result[$j]->kd_brg,
					"ur_sskel"  =>(string)$result[$j]->ur_sskel, 
					"kd_lokasi"  =>(string)$result[$j]->kd_lokasi,					
					"no_aset" 	=>(string)$result[$j]->no_aset,
					"no_kib" 	=>(string)$result[$j]->no_kib, 		
					"thn_ang" 	=>(string)$result[$j]->thn_ang,
					"thn_aset" 	=>(string)$result[$j]->thn_aset, 	
					"kd_data" 	=>(string)$result[$j]->kd_data,
					"kuantitas" =>(string)$result[$j]->kuantitas, 	
					"rph_aset" 	=>(string)$result[$j]->rph_aset,
					"thnang_kib" =>(string)$result[$j]->thnang_kib, 
					
					"luas_bdg" 	=>(string)$result[$j]->luas_bdg,
					"jml_lt" 	=>(string)$result[$j]->jml_lt,
					"type" 		=>(string)$result[$j]->type,
					"thn_sls" 	=>(string)$result[$j]->thn_sls,
					"thn_pakai" 	=>(string)$result[$j]->thn_pakai,
					"no_imb" 	=>(string)$result[$j]->no_imb,
					"tgl_imb" 		=>(string)$result[$j]->tgl_imb,
					
					"kd_prov" 	=>(string)$result[$j]->kd_prov,
					"kd_kab" 	=>(string)$result[$j]->kd_kab,
					"kd_kec" 	=>(string)$result[$j]->kd_kec,
					"kd_kel" 	=>(string)$result[$j]->kd_kel,
					"kd_rtrw" 	=>(string)$result[$j]->kd_rtrw,
					"alamat" 	=>(string)$result[$j]->alamat,
					
					"no_kibtnh" 	=>(string)$result[$j]->no_kibtnh,
					"jns_trn" 	=>(string)$result[$j]->jns_trn,

					"dari" 		=>(string)$result[$j]->dari,
					"tgl_prl" 	=>(string)$result[$j]->tgl_prl,
					"kondisi" 	=>(string)$result[$j]->kondisi,
					"rph" 		=>(string)$result[$j]->rph,
					"dasar_hrg" =>(string)$result[$j]->dasar_hrg,
					"sumber" 	=>(string)$result[$j]->sumber,
					"no_dana" 	=>(string)$result[$j]->no_dana,
					"tgl_dana" 	=>(string)$result[$j]->tgl_dana,
					"unit_pmk" 	=>(string)$result[$j]->unit_pmk,
					"alm_pmk"	=>(string)$result[$j]->alm_pmk,
					
					"catatan" 	=>(string)$result[$j]->catatan,
					"tgl_rekam" 	=>(string)$result[$j]->tgl_rekam,
					"tgl_update" 	=>(string)$result[$j]->tgl_update,					
					"tgl_buku" 	=>(string)$result[$j]->tgl_buku,
					"flag_kib" 	=>(string)$result[$j]->flag_kib);
			}					 
			return $hasilAkhir;
	   	}catch (Exception $e){
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
	   	}
	}
	
	public function getNoInventarisGedung($pageNumber,$itemPerPage) 
	{
		//echo "+masuk services getNoInventarisAngkutanList";
		$namaBarang = strtoupper($namaBarang);
		$kdBrg 	= '106';
		$kbr 	= $kdBrg.'%';
		$nbrg 	= $namaBarang.'%';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] =$kbr;
			$where[] =$nbrg;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
								where substr(a.kd_brg,1,1) = b.kd_gol
								and substr(a.kd_brg,2,2) = b.kd_bid 
								and substr(a.kd_brg,4,2) = b.kd_kel
								and substr(a.kd_brg,6,2) = b.kd_skel
								and substr(a.kd_brg,8,3) = b.kd_sskel
								and kd_brg like ? and ur_sskel like ? ",$where);
			}else{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;					 
				$result = $db->fetchAll("SELECT distinct A.kd_brg as kd_brg,B.ur_sskel as ur_sskel,
									A.tgl_perlh as tgl_perlh,
									to_char(no_aset,'09999') as no_aset
									FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
									where substr(a.kd_brg,1,1) = b.kd_gol
										  and substr(a.kd_brg,2,2) = b.kd_bid 
										  and substr(a.kd_brg,4,2) = b.kd_kel
										  and substr(a.kd_brg,6,2) = b.kd_skel
										  and substr(a.kd_brg,8,3) = b.kd_sskel
										  and kd_brg like ? and ur_sskel like ? 
									limit $xLimit offset $xOffset",$where); 
				$jmlResult = count($result);
				for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("kd_brg"	=>(string)$result[$j]->kd_brg,
					"ur_sskel" 				=>(string)$result[$j]->ur_sskel,
					"tgl_perlh" 				=>(string)$result[$j]->tgl_perlh,
					"no_aset" 				=>(string)$result[$j]->no_aset);
				}	
			}	 
			return $hasilAkhir;
		}catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}

	
//// Akhir Perubahan Baru ///	
	public function getCariInventarisGedungListOLD($kdbrg,$dari,$sampai) 
	{		
		//echo "+masuk getCariInventarisAngkutanList oh ya";
		$registry = Zend_Registry::getInstance();
	   	$db = $registry->get('db');
	   		   	
	   	
	   	//$between = "between $dari and $sampai";	 
	   	//echo "+between servis =".$between;  
	   	//$where[]=$between;	
	   	
	   	$where[] = $kdbrg;
	   	$where[] = $dari;	   	
	   	$where[] = $sampai;
	   	//echo "+kdnrg servis =".$kdbrg;
	   	//echo "+dari servis =".$dari;
	   	//echo "+sampai servis =".$sampai;
		
	   	try 
	   	{

			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$result = $db->fetchAll("SELECT A.kd_brg,B.ur_sskel,A.no_aset,A.no_kib,
			A.kuantitas,A.rph_aset,A.luas_bdg,A.jml_lt,A.type,A.no_imb,
			A.kd_prov,A.kd_kab,A.kd_kec,A.kd_kel,A.kd_rtrw,A.alamat,
			A.thn_sls,A.thn_pakai,A.thnang_kib,A.no_kibtnh,
			A.jns_trn,A.sumber,A.dari,A.dasar_hrg,A.tgl_prl,A.no_dana,A.tgl_dana,
			A.rph_aset,A.rph,A.unit_pmk,A.catatan							
			FROM e_sabm_t_kbdg_tm A, e_ast_sskel_0_tr B
			where substr(a.kd_brg,1,1) = b.kd_gol
			and substr(a.kd_brg,2,2) = b.kd_bid 
		        and substr(a.kd_brg,4,2) = b.kd_kel
		        and substr(a.kd_brg,6,2) = b.kd_skel
		        and substr(a.kd_brg,8,3) = b.kd_sskel
		        and kd_brg =?
		        and no_kib between ? and ?",$where);
		        
	         	$jmlResult = count($result); 
	         	//echo "+jumlah data services =".$jmlResult;
	         	
			for ($j = 0; $j < $jmlResult; $j++) 
			{
	           	$hasilAkhir[$j] = array("kd_brg" =>(string)$result[$j]->kd_brg,
					"ur_sskel"  =>(string)$result[$j]->ur_sskel, 
					"kd_lokasi"  =>(string)$result[$j]->kd_lokasi,					
					"no_aset" 	=>(string)$result[$j]->no_aset,
					"no_kib" 	=>(string)$result[$j]->no_kib, 		
					"thn_ang" 	=>(string)$result[$j]->thn_ang,
					"thn_aset" 	=>(string)$result[$j]->thn_aset, 	
					"kd_data" 	=>(string)$result[$j]->kd_data,
					"kuantitas" =>(string)$result[$j]->kuantitas, 	
					"rph_aset" 	=>(string)$result[$j]->rph_aset,
					"thnang_kib" =>(string)$result[$j]->thnang_kib, 
					
					"luas_bdg" 	=>(string)$result[$j]->luas_bdg,
					"jml_lt" 	=>(string)$result[$j]->jml_lt,
					"type" 		=>(string)$result[$j]->type,
					"thn_sls" 	=>(string)$result[$j]->thn_sls,
					"thn_pakai" 	=>(string)$result[$j]->thn_pakai,
					"no_imb" 	=>(string)$result[$j]->no_imb,
					"tgl_imb" 		=>(string)$result[$j]->tgl_imb,
					
					"kd_prov" 	=>(string)$result[$j]->kd_prov,
					"kd_kab" 	=>(string)$result[$j]->kd_kab,
					"kd_kec" 	=>(string)$result[$j]->kd_kec,
					"kd_kel" 	=>(string)$result[$j]->kd_kel,
					"kd_rtrw" 	=>(string)$result[$j]->kd_rtrw,
					"alamat" 	=>(string)$result[$j]->alamat,
					
					"no_kibtnh" 	=>(string)$result[$j]->no_kibtnh,
					"jns_trn" 	=>(string)$result[$j]->jns_trn,
					/* "batas_b" 	=>(string)$result[$j]->batas_b,
					"batas_t" 	=>(string)$result[$j]->batas_t, */
					"dari" 		=>(string)$result[$j]->dari,
					"tgl_prl" 	=>(string)$result[$j]->tgl_prl,
					"kondisi" 	=>(string)$result[$j]->kondisi,
					"rph" 		=>(string)$result[$j]->rph,
					"dasar_hrg" =>(string)$result[$j]->dasar_hrg,
					"sumber" 	=>(string)$result[$j]->sumber,
					"no_dana" 	=>(string)$result[$j]->no_dana,
					"tgl_dana" 	=>(string)$result[$j]->tgl_dana,
					"unit_pmk" 	=>(string)$result[$j]->unit_pmk,
					"alm_pmk"	=>(string)$result[$j]->alm_pmk,
					
					"catatan" 	=>(string)$result[$j]->catatan,
					"tgl_rekam" 	=>(string)$result[$j]->tgl_rekam,
					"tgl_update" 	=>(string)$result[$j]->tgl_update,					
					"tgl_buku" 	=>(string)$result[$j]->tgl_buku,
					"flag_kib" 	=>(string)$result[$j]->flag_kib);
			}					 
		     	return $hasilAkhir;
	   	} 
	   	catch (Exception $e) 
	   	{
         		echo $e->getMessage().'<br>';
	     		return 'gagal <br>';
	   	}
	}

	public function getNoInventarisGedungOLD($pageNumber,$itemPerPage) 
	{
		//echo "+masuk services getNoInventarisAngkutanList";
		$namaBarang = strtoupper($namaBarang);
		$kdBrg 	= '106';
		$kbr 	= $kdBrg.'%';
		$nbrg 	= $namaBarang.'%';
		
		//echo "+namaBarang =".$namaBarang;
		//echo "+kdBrg =".$kdBrg;
		//echo "+kbr =".$kbr;
		//echo "+nbrg =".$nbrg;
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{
		 $where[] =$kbr;
		 $where[] =$nbrg;
		 
	         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
									where substr(a.kd_brg,1,1) = b.kd_gol
									and substr(a.kd_brg,2,2) = b.kd_bid 
									and substr(a.kd_brg,4,2) = b.kd_kel
									and substr(a.kd_brg,6,2) = b.kd_skel
									and substr(a.kd_brg,8,3) = b.kd_sskel
									and kd_brg like ? and ur_sskel like ? ",$where);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;					 
			 $result = $db->fetchAll("SELECT A.kd_brg as kd_brg,B.ur_sskel as ur_sskel,
									A.tgl_perlh as tgl_perlh,
									to_char(no_aset,'09999') as no_aset
									FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
									where substr(a.kd_brg,1,1) = b.kd_gol
									      and substr(a.kd_brg,2,2) = b.kd_bid 
									      and substr(a.kd_brg,4,2) = b.kd_kel
									      and substr(a.kd_brg,6,2) = b.kd_skel
									      and substr(a.kd_brg,8,3) = b.kd_sskel
									      and kd_brg like ? and ur_sskel like ? 
									limit $xLimit offset $xOffset",$where); 
			 $jmlResult = count($result);
			 //echo "jumlah di server =".$jmlResult;
			 
			 for ($j = 0; $j < $jmlResult; $j++) 
			 {
				$hasilAkhir[$j] = array("kd_brg"	=>(string)$result[$j]->kd_brg,
				"ur_sskel" 				=>(string)$result[$j]->ur_sskel,
				"tgl_perlh" 				=>(string)$result[$j]->tgl_perlh,
				"no_aset" 				=>(string)$result[$j]->no_aset);
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