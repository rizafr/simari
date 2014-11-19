<?php
class ast_angkut_Service 
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
	public function getNoInventarisPeralatanPCList() 
	{		
		//echo "masuk getNoInventarisPeralatanPCList angkut";
		$registry = Zend_Registry::getInstance();
	   	$db = $registry->get('db');
	   	
	   	$kdBrg = '20201';
	   	echo "kdbrg =".$kdbrg;
		$kbr = '%'.$kdBrg.'%';
		
	   	try 
	   	{
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			//$result = $db->fetchAll("SELECT A.thn_ang as thn_ang,
			//				A.kd_brg as kd_brg,B.ur_sskel as ur_sskel,
			//				A.tgl_perlh as tgl_perlh,
			//				to_char(no_aset,'09999') as no_aset
			//				FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
			//				where substr(a.kd_brg,1,1) = b.kd_gol
			//				      and substr(a.kd_brg,2,2) = b.kd_bid 
			//				      and substr(a.kd_brg,4,2) = b.kd_kel
			//				      and substr(a.kd_brg,6,2) = b.kd_skel
			//				      and substr(a.kd_brg,8,3) = b.kd_sskel
			//				      and kd_brg like ? ORDER BY thn_ang",$kbr);
	         	$result = $db->fetchAll("SELECT A.thn_ang as thn_ang,A.kd_brg as kd_brg,B.ur_sskel as ur_sskel,
A.tgl_perlh as tgl_perlh,to_char(no_aset,'09999') as no_aset
FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
where substr(a.kd_brg,1,1) = b.kd_gol
and substr(a.kd_brg,2,2) = b.kd_bid 
and substr(a.kd_brg,4,2) = b.kd_kel
and substr(a.kd_brg,6,2) = b.kd_skel
and substr(a.kd_brg,8,3) = b.kd_sskel");
	         	$jmlResult = count($result);		 
			for ($j = 0; $j < $jmlResult; $j++) 
			{
	           		$hasilAkhir[$j] = array("thn_ang" =>(string)$result[$j]->thn_ang,
							"kd_brg"  =>(string)$result[$j]->kd_brg,
							"no_aset" =>(string)$result[$j]->no_aset,
							"ur_sskel" =>(string)$result[$j]->ur_sskel,
							"tgl_perlh" =>(string)$result[$j]->tgl_perlh);
			}					 
		     	return $hasilAkhir;
	   	} 
	   	catch (Exception $e) 
	   	{
         		echo $e->getMessage().'<br>';
	     		return 'gagal <br>';
	   	}
	}
	
	
	//untuk mengeluarkan list angkutan waktu diklik images cari di proseangkut.phtml
	public function getCariInventarisAngkutanList($kdbrg,$dari,$sampai) 
	{		
		//echo "+masuk getCariInventarisAngkutanList oh ya";
		$registry = Zend_Registry::getInstance();
	   	$db = $registry->get('db');
	   	$where[] = $kdbrg;
	   	$where[] = $dari;	   	
	   	$where[] = $sampai;
		if ($dari < 1 && $sampai < 1){
			$queryInterv= " and true ";
		}else{
			if ($dari > 0) $queryInterv= " and no_kib between '$dari' and '$sampai' ";
				else $queryInterv= " and no_kib <= '$sampai' ";
		}
	   	try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$query = "SELECT distinct A.kd_brg,B.ur_sskel,A.no_aset,A.no_kib,
					A.merk,A.type,A.thn_buat,A.pabrik,A.negara,A.rakit,A.muat,A.bobot,A.daya,A.msn_grk,A.jml_msn,
					A.bhn_bkr,A.no_msn,A.no_rangka,A.no_bpkb,A.no_polisi,
					A.lengkap1,A.lengkap2,A.lengkap3,
					A.dari,A.tgl_prl,A.kondisi,A.rph_aset,A.dasar_hrg,A.sumber,
					A.no_dana,A.tgl_dana,
					A.unit_pmk,A.catatan							
					FROM e_sabm_t_kangk_tm A, e_ast_sskel_0_tr B
					where substr(a.kd_brg,1,1) = b.kd_gol
					and substr(a.kd_brg,2,2) = b.kd_bid 
				        and substr(a.kd_brg,4,2) = b.kd_kel
				        and substr(a.kd_brg,6,2) = b.kd_skel
				        and substr(a.kd_brg,8,3) = b.kd_sskel
				        and kd_brg ='$kdbrg' and (A.sumber is not null and  A.sumber !='')";
			$query=$query.$queryInterv;
			$result = $db->fetchAll($query);
			$jmlResult = count($result); 
			for ($j = 0; $j < $jmlResult; $j++) 
			{
				$hasilAkhir[$j] = array("kd_brg" =>(string)$result[$j]->kd_brg,
							"ur_sskel"  =>(string)$result[$j]->ur_sskel,
							"no_aset" =>(string)$result[$j]->no_aset,
							"no_kib" =>(string)$result[$j]->no_kib,
							"merk" =>(string)$result[$j]->merk,
							"type" =>(string)$result[$j]->type,
							"thn_buat" =>(string)$result[$j]->thn_buat,
							"pabrik" =>(string)$result[$j]->pabrik,
							"negara" =>(string)$result[$j]->negara,
							"rakit" =>(string)$result[$j]->rakit,
							"muat" =>(string)$result[$j]->muat,
							"bobot" =>(string)$result[$j]->bobot,
							"daya" =>(string)$result[$j]->daya,
							"msn_grk" =>(string)$result[$j]->msn_grk,
							"jml_msn" =>(string)$result[$j]->jml_msn,
							"bhn_bkr" =>(string)$result[$j]->bhn_bkr,
							"no_msn" =>(string)$result[$j]->no_msn,
							"no_rangka" =>(string)$result[$j]->no_rangka,
							"no_bpkb" =>(string)$result[$j]->no_bpkb,
							"no_polisi" =>(string)$result[$j]->no_polisi,
							"lengkap1" =>(string)$result[$j]->lengkap1,
							"lengkap2" =>(string)$result[$j]->lengkap2,
							"lengkap3" =>(string)$result[$j]->lengkap3,
							"dari" =>(string)$result[$j]->dari,
							"tgl_prl" =>(string)$result[$j]->tgl_prl,
							"kondisi" =>(string)$result[$j]->kondisi,
							"rph_aset" =>(string)$result[$j]->rph_aset,
							"dasar_hrg" =>(string)$result[$j]->dasar_hrg,
							"sumber" =>(string)$result[$j]->sumber,
							"no_dana" =>(string)$result[$j]->no_dana,
							"tgl_dana" =>(string)$result[$j]->tgl_dana,
							"unit_pmk" =>(string)$result[$j]->unit_pmk,
							"alm_pmk" =>(string)$result[$j]->alm_pmk,
							"jns_trn" =>(string)$result[$j]->jns_trn,
							"catatan" =>(string)$result[$j]->catatan);
			}
		    return $hasilAkhir;
		}catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
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

	public function getNoInventarisAngkutanListNew($pageNumber,$itemPerPage,$namaBarang) {
		$namaBarang = strtoupper($namaBarang);
		$kdBrg 	= '202';
		$kbr 	= $kdBrg.'%';
		$nbrg 	= $namaBarang.'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] =$kbr;
			$where[] =$nbrg;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0)){
				$hasilAkhir = $db->fetchOne("select count(distinct A.kd_brg) FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
							where substr(a.kd_brg,1,1) = b.kd_gol
							and substr(a.kd_brg,2,2) = b.kd_bid 
							and substr(a.kd_brg,4,2) = b.kd_kel
							and substr(a.kd_brg,6,2) = b.kd_skel
							and substr(a.kd_brg,8,3) = b.kd_sskel
							and kd_brg like ? and ur_sskel like ? ",$where);
			}else{
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
				//echo "jumlah di Dodol =".$jmlResult;
				for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("kd_brg"	=>(string)$result[$j]->kd_brg,
											"ur_sskel" 	=>(string)$result[$j]->ur_sskel);
				}	
			}
			return $hasilAkhir;
		}catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	
		
}	
?>