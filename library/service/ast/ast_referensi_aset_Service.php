<?php
class ast_referensi_aset_Service {
    private static $instance;
   
    // A private constructor; prevents direct creation of object
    private function __construct() {
        // echo 'I am constructed';
    }

    /* The singleton method */
    public static function getInstance() {
       //echo 'I am constructed';
	   if (!isset(self::$instance)) {
           $c = __CLASS__;
           self::$instance = new $c;
       }

       return self::$instance;
    }
	
    /**
	 * LIST RUANG RAPAT
	  */
	  
	//Module referensi ruangan ==================================================================
 public function getRuangRapatListA($pageNumber, $itemPerPage) 
{
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	try 
	{
	
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		
		if(($pageNumber==0) && ($itemPerPage==0))
		{
			$hasilAkhir = $db->fetchOne("SELECT count(*)
			FROM e_ast_ruangan_0_tr");
			 
		}
		else
		{
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;	
			$result = $db->fetchAll("SELECT i_ruang,i_ruang_lokasi,n_gedung,q_gedung_lantai,q_gedung_kapasitas,
			q_gedung_luas,i_orgb_tgjwbgedung,i_peg_nik,c_gedung_fungsi,n_peg,
			(case c_gedung_fungsi 
									     WHEN '1' then 'Ruang Kerja'
									     WHEN '2' then 'Ruang Rapat Umum' 
									     WHEN '3' then 'Ruang Rapat Deputi' 
									     WHEN '4' then 'Ruang Umum' 
									     else 'Lain-Lain' END) as n_gedung_fungsi
			FROM e_ast_ruangan_0_tr A, e_sdm_pegawai_0_tm B
			where a.i_peg_nik = b.i_peg_nip
			ORDER BY i_ruang limit $xLimit offset $xOffset");
			
			$jmlResult = count($result);
			
			if($jmlResult > 0)
			{
				for ($j = 0; $j < $jmlResult; $j++) 
				{		
				$hasilAkhir[$j] =   array("i_ruang"        =>(string)$result[$j]->i_ruang,
										   	"i_ruang_lokasi"           =>(string)$result[$j]->i_ruang_lokasi,
										   	"n_gedung"                 =>(string)$result[$j]->n_gedung,
											"q_gedung_lantai"          =>(string)$result[$j]->q_gedung_lantai,
										   	"q_gedung_kapasitas"       =>(string)$result[$j]->q_gedung_kapasitas,
										   	"q_gedung_luas"            =>(string)$result[$j]->q_gedung_luas,
										   	"c_gedung_fungsi"          =>(string)$result[$j]->c_gedung_fungsi,
											"n_gedung_fungsi"          =>(string)$result[$j]->n_gedung_fungsi,
										   	"i_orgb_tgjwbgedung"       =>(string)$result[$j]->i_orgb_tgjwbgedung,
											"i_peg_nik"				   =>(string)$result[$j]->i_peg_nik,
											"n_peg"				   =>(string)$result[$j]->n_peg);
				
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
  public function getRuangRapatList($pageNumber, $itemPerPage) 
{
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	try 
	{
		$where ='2';
		$where ='3';
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		
		if(($pageNumber==0) && ($itemPerPage==0))
		{
			$hasilAkhir = $db->fetchOne("SELECT count(*)
			FROM e_ast_ruangan_0_tr
			where c_gedung_fungsi = ? or c_gedung_fungsi = ?",$where);
		}
		else
		{
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;	
			$result = $db->fetchAll("SELECT i_ruang,i_ruang_lokasi,n_gedung,q_gedung_lantai,q_gedung_kapasitas,
			q_gedung_luas,i_orgb_tgjwbgedung,
			(case c_gedung_fungsi 
									     WHEN '1' then 'Ruang Kerja'
									     WHEN '2' then 'Ruang Rapat Umum' 
									     WHEN '3' then 'Ruang Rapat Deputi' 
									     WHEN '4' then 'Ruang Umum' 
									     else 'Lain-Lain' END) as c_gedung_fungsi
			FROM e_ast_ruangan_0_tr 
			where c_gedung_fungsi = ? or c_gedung_fungsi = ?
			ORDER BY i_ruang limit $xLimit offset $xOffset",$where);
			
			$jmlResult = count($result);
			
			if($jmlResult > 0)
			{
				for ($j = 0; $j < $jmlResult; $j++) 
				{		
				$hasilAkhir[$j] =   array("i_ruang"        =>(string)$result[$j]->i_ruang,
					   	"i_ruang_lokasi"           =>(string)$result[$j]->i_ruang_lokasi,
					   	"n_gedung"                 =>(string)$result[$j]->n_gedung,
						"q_gedung_lantai"          =>(string)$result[$j]->q_gedung_lantai,
					   	"q_gedung_kapasitas"       =>(string)$result[$j]->q_gedung_kapasitas,
					   	"q_gedung_luas"            =>(string)$result[$j]->q_gedung_luas,
					   	"c_gedung_fungsi"          =>(string)$result[$j]->c_gedung_fungsi,
					   	"i_orgb_tgjwbgedung"       =>(string)$result[$j]->i_orgb_tgjwbgedung);
				
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
   
    public function getFungsiRuangList() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchAll('SELECT c_gedung_fungsi,e_keterangan
		                          FROM e_ast_ruang_tr ORDER BY c_gedung_fungsi');
		 $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
         $hasilAkhir[$j] =   array("c_gedung_fungsi"    =>(string)$result[$j]->c_gedung_fungsi,
								   "e_keterangan"       =>(string)$result[$j]->e_keterangan);
								  
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }						
	   
	 
    }
	
	public function queryNourutmax($Lantai) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	    try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ);
		 $where[] = $Lantai;
		 $result = $db->fetchOne('SELECT  gen_noruang (?)',$where);
		  return $result;
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return  0;
	   }
	} 

	public function queryNourutmax2($Lantai) {
	    echo '$Lantai..service'.$Lantai;
	    $registry = Zend_Registry::getInstance();
	    $db = $registry->get('db');
	    try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ);
		 $where[] = $Lantai * 1;
		 $jml = $db->fetchOne('select count(*)  
									from e_ast_nomorruang_max_tr
									where q_gedung_lantai = ?',$where);
									
		echo '$jml'.$jml;
		if ($jml > 0){
			$max = $db->fetchOne('select q_nomor_max 
									from e_ast_nomorruang_max_tr
									where q_gedung_lantai = ?',$where);
			echo '$max'.$max;
			$NoMax = $max +1;
			$db->beginTransaction();
			$ruangan_parm = array("q_gedung_lantai" 	=>$Lantai,
									 "q_nomor_max"  	=>$NoMax, 
									 "i_entry"      	=>"SYS",
									 "d_entry"      	=>date("Y-m-d"));
			
			$where[] = "q_gedung_lantai  =  '".$Lantai."'";		
			//$db->update('e_ast_nomorruang_max_tr',$ruangan_parm, $where);
			$db->commit();
		}else{
			$NoMax = 1;
			$db->beginTransaction(); 
				$ruangan_parm = array("q_gedung_lantai" 		=>$Lantai,
									 "q_nomor_max"           	=>1, 
									 "i_entry"       	   		=>"SYS",
									 "d_entry"       	   		=>date("Y-m-d"));
		
			//$db->insert('e_ast_nomorruang_max_tr',$ruangan_parm);
			$db->commit();
		}
		
		echo 'NoMax'.NoMax;
		
	     return $NoMax;
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return  0;
	   }
	} 
	
	
	
	//======================================================================================================================================
	//=====================07 nop 07===========================================================
	public function getInfoRuangList() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchAll('SELECT distinct a.i_orgb as i_orga, a.q_rapat_peserta, a.i_rapat_nippimpin, 
							       b.n_peg, b.n_jabatan, b.i_orgb       
									FROM   e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b
									WHERE a.i_rapat_nippimpin=b.i_peg_nip');
									
		 $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
         $hasilAkhir[$j] =   array("i_orga"    =>(string)$result[$j]->i_orga,
								   "q_rapat_peserta"    =>(string)$result[$j]->q_rapat_peserta,
								   "i_rapat_nippimpin"    =>(string)$result[$j]->i_rapat_nippimpin,
								   "n_peg"       =>(string)$result[$j]->n_peg,
								   "n_jabatan"       =>(string)$result[$j]->n_jabatan,
								   "i_orgb"       =>(string)$result[$j]->i_orgb);
								  
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }						
	   
	 
    }
	
	//=====================07 nop 07===========================================================
	public function getInfoRuangListA($prmjam,$prmtgl) {
		//echo 'prmjam..ser'.$prmjam;
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $where[]=$prmjam;
		 $where[]=$prmjam;
		 $where[]=$prmtgl;
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchAll('SELECT distinct a.i_orgb as i_orga, a.q_rapat_peserta, a.i_rapat_nippimpin, 
							       b.n_peg, b.n_jabatan, b.i_orgb       
									FROM   e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b
									WHERE a.i_rapat_nippimpin=b.i_peg_nip and d_rapat_awalpakai <=?
									and d_rapat_akhirpakai > ? and d_rapat_pesan =?',$where);
									
		 $jmlResult = count($result);
		 //echo '$jmlResult'.$jmlResult;
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
         $hasilAkhir[$j] =   array("i_orga"    			=>(string)$result[$j]->i_orga,
								   "q_rapat_peserta"    =>(string)$result[$j]->q_rapat_peserta,
								   "i_rapat_nippimpin"  =>(string)$result[$j]->i_rapat_nippimpin,
								   "n_peg"       		=>(string)$result[$j]->n_peg,
								   "n_jabatan"       	=>(string)$result[$j]->n_jabatan,
								   "i_orgb"       		=>(string)$result[$j]->i_orgb);
								  
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }						
	   
	 
    }
	
	// 07 nop 2007=================================
	public function getRuangList() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchAll("SELECT i_ruang,i_ruang_lokasi,n_gedung,
		 (case c_gedung_fungsi 
									     WHEN '1' then 'Ruang Kerja'
									     WHEN '2' then 'Ruang Rapat Umum' 
									     WHEN '3' then 'Ruang Rapat Deputi' 
									     WHEN '4' then 'Ruang Umum' 
									     else 'Lain-Lain' END) as c_gedung_fungsi
		                          FROM e_ast_ruangan_0_tr ORDER BY i_ruang");
		 $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
         $hasilAkhir[$j] =   array("i_ruang"    =>(string)$result[$j]->i_ruang,
								   "i_ruang_lokasi"    =>(string)$result[$j]->i_ruang_lokasi,
								   "n_gedung"    =>(string)$result[$j]->n_gedung,
								   "c_gedung_fungsi"       =>(string)$result[$j]->c_gedung_fungsi);
								  
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }						
	   
	 
    }
	
	
	public function getRuanganList2() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchAll('SELECT kd_lokasi,kd_ruang,ur_ruang,pj_ruang,nip_pjrug
		                          FROM e_sabm_t_ruang_tm a
								  where not exists(select b.i_ruang from e_ast_ruangan_0_tr b
								  where a.kd_ruang = b.i_ruang
								  ORDER BY kd_lokasi');
		 $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
         $hasilAkhir[$j] =   array("kd_lokasi"     =>(string)$result[$j]->kd_lokasi,
								   "kd_ruang"       =>(string)$result[$j]->kd_ruang,
								   "ur_ruang"       =>(string)$result[$j]->ur_ruang,
								   "pj_ruang"       =>(string)$result[$j]->pj_ruang,
								   "nip_pjrug"      =>(string)$result[$j]->nip_pjrug 
								   );
								  
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }						
	   
	 
   }  
   //..............................................................................................New... 06 nop 07 .................................................
	public function getFasilitasList($noRuang) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT distinct a.c_barang,b.ur_sskel
		                         FROM e_ast_dir_item_tm a, e_ast_sskel_0_tr b
								 where a.i_ruang = ? and substr(a.c_barang,1,1) = b.kd_gol
								       and substr(a.c_barang,2,2) = b.kd_bid 
									   and substr(a.c_barang,4,2) = b.kd_kel
									   and substr(a.c_barang,6,2) = b.kd_skel
									   and substr(a.c_barang,8,3) = b.kd_sskel'
									   ,$noRuang);
									   
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_barang"             =>(string)$result[$j]->c_barang,
								   "ur_sskel"           =>(string)$result[$j]->ur_sskel); 
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	   
	}
	
	//.... Fasilitas List di ubah tabel ke .. e_ast_dir_item_tm.... 06 nop 07
	public function getFasilitasList2($noRuang) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT a.kd_brg,b.ur_sskel
		                         FROM e_sabm_t_dir_tm a, e_ast_sskel_0_tr b
								 where a.kd_ruang = ? and substr(a.kd_brg,1,1) = b.kd_gol
								       and substr(a.kd_brg,2,2) = b.kd_bid 
									   and substr(a.kd_brg,4,2) = b.kd_kel
									   and substr(a.kd_brg,6,2) = b.kd_skel
									   and substr(a.kd_brg,8,3) = b.kd_sskel'
									   ,$noRuang);
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("kd_brg"             =>(string)$result[$j]->kd_brg,
								   "ur_sskel"           =>(string)$result[$j]->ur_sskel); 
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	   
	}
	
	public function getRuanganList() {
	    $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT  kd_ruang,pj_ruang,nip_pjrug
		                         FROM e_sabm_t_ruang_tm  
								 where not exists (select i_ruang from e_ast_ruangan_0_tr
								 where i_ruang = kd_ruang) ORDER by kd_ruang');
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("kd_ruang"             =>(string)$result[$j]->kd_ruang,
								   "pj_ruang"             =>(string)$result[$j]->pj_ruang, 
								   "nip_pjrug"             =>(string)$result[$j]->nip_pjrug); 
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	   
	}
 	
	//Module referensi ruangan ==================================================================
 public function insertRuangan(array $data) 
{
	//echo "masuk insertRuangan";
	//echo "ruang".$data['ruang'];
	//echo "lokasi".$data['lokasi'];
	//echo "gedung".$data['gedung'];
	//echo "lantai".$data['lantai'];
	//echo "kapasitas".$data['kapasitas'];
	//echo "luas".$data['luas'];
	//echo "fungsiRuangan'".$data['fungsiRuangan'];
	//echo "tgjwb".$data['tgjwb'];
	
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	
	try 
	{
	    echo 'fungsi ruang : '.$data['fungsiRuangan'];
		$db->beginTransaction(); 
		$ruangan_parm = array("i_ruang" 		   			=>$data['ruang'],
								"i_ruang_lokasi"           	=>$data['lokasi'],
								"n_gedung"                 	=>$data['gedung'],
								"q_gedung_lantai"          	=>$data['Lantai'],
								"q_gedung_kapasitas"       	=>$data['kapasitas'],
								"q_gedung_luas"            	=>$data['luas'],
								"c_gedung_fungsi"          	=>$data['fungsiRuangan'],
								"i_orgb_tgjwbgedung"       	=>$data['tgjwb'], 
								"i_peg_nik"       			=>$data['nip'], 
								"i_entry"       	   		=>$data['nuser'],
								"d_entry"       	   		=>date("Y-m-d"));
		
		$db->insert('e_ast_ruangan_0_tr',$ruangan_parm);
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
	
//Module referensi ruangan ==================================================================
public function updateRuangan(array $data) 
{	
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	try 
	{
		$db->beginTransaction();
		$ruangan_parm = array("i_ruang_lokasi"  			=>$data['lokasi'],
								"n_gedung"  		   		=>$data['gedung'],
								"q_gedung_lantai"  		 	=>$data['Lantai'],
								"q_gedung_kapasitas"  	 	=>$data['kapasitas'],
								"q_gedung_luas"  	 	    =>$data['luas'],
								"c_gedung_fungsi"  	 	    =>$data['fungsi'],
								"i_orgb_tgjwbgedung"   	    =>$data['tgjwb'],
								"i_peg_nik"       			=>$data['nip'], 
								"i_entry"       	   		=>$data['nuser'],
								"d_entry"       		    =>date("Y-m-d"));
		
		$where[] = "i_ruang  =  '".$data['ruang']."'";		
		$db->update('e_ast_ruangan_0_tr',$ruangan_parm, $where);
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

	public function getRuangan($noRuang) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT    i_ruang_lokasi,
								            n_gedung,
	                                        q_gedung_lantai,
											q_gedung_kapasitas, 
											q_gedung_luas ,											
											i_orgb_tgjwbgedung,
								        (case c_gedung_fungsi 
									     WHEN '1' then 'Ruang Kerja'
									     WHEN '2' then 'Ruang Rapat Umum' 
									     WHEN '3' then 'Ruang Rapat Deputi' 
									     WHEN '4' then 'Ruang Umum' 
									     else 'Lain-Lain' END) as c_gedung_fungsi											
		                         FROM e_ast_ruangan_0_tr
								 where i_ruang = ?",$noRuang);
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_ruang"                  =>(string)$result[$j]->i_ruang,
							       "i_ruang_lokasi"           =>(string)$result[$j]->i_ruang_lokasi,
								   "n_gedung"                 =>(string)$result[$j]->n_gedung,
	                               "q_gedung_lantai"          =>(string)$result[$j]->q_gedung_lantai,
								   "q_gedung_kapasitas"       =>(string)$result[$j]->q_gedung_kapasitas,
								   "q_gedung_luas"            =>(string)$result[$j]->q_gedung_luas,
								   "c_gedung_fungsi"          =>(string)$result[$j]->c_gedung_fungsi,
								   "i_orgb_tgjwbgedung"       =>(string)$result[$j]->i_orgb_tgjwbgedung);
				
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	 
  
	public function deletRuangan($ruang) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $where[] = "trim(i_ruang) = '". trim($ruang) ."'";
		 $db->delete('e_ast_ruangan_0_tr', $where);
		 $db->commit();
	     return 'sukses <br>';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getUnitKerjaList() {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT a.i_orgb,a.n_orgb,c.i_peg_nip,c.n_peg
		                         FROM e_org_0_0_tm a, e_sdm_pegawai_0_tm c,e_sdm_peg_jabatan_tm d
								 where a.i_orgb =  c.i_orgb or a.i_orgb =  c.c_unit_kerja 
								       and c.i_peg_nip = d.i_peg_nip','TU'); 
									  
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb"             =>(string)$result[$j]->i_orgb,
								   "n_orgb"           =>(string)$result[$j]->n_orgb, 
								   "i_peg_nip"           =>(string)$result[$j]->i_peg_nip, 
								   "n_peg"           =>(string)$result[$j]->n_peg); 
								  
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
 
	public function queryUnitKerja($unitkr) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = "i_orgb = '".trim($data['unitkr'])."'";
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
	public function getListPegawai() {
       
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_peg_nip,n_peg,n_jabatan,a.i_orgb,n_orgb 
									FROM e_sdm_pegawai_0_tm a, e_org_0_0_tm b
									where a.i_orgb=b.i_orgb or a.c_unit_kerja = b.i_orgb
									ORDER BY i_peg_nip');
	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
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
	     return 'gagal <br>';
	   }
	}
	//public function getListPegawaibyOrg($unitkr) {
    public function getListPegawaibyOrg($pageNumber,$itemPerPage,$kdunit) {
		 
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
	
	
	public function getRefInvList() {
       
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT thn_ang,kd_brg,tgl_perlh,merk_type,
									to_char(no_aset,'09999') as no_aset,ur_sskel
									FROM e_sabm_t_master_tm a,e_ast_sskel_0_tr b
									where  substr(a.kd_brg,1,1) = b.kd_gol
								           and substr(a.kd_brg,2,2) = b.kd_bid 
									       and substr(a.kd_brg,4,2) = b.kd_kel
									       and substr(a.kd_brg,6,2) = b.kd_skel
									       and substr(a.kd_brg,8,3) = b.kd_sskel 
										   and not exists(select * from e_ast_dir_item_tm c
										   where d_aset_thnanggar = thn_ang and 
										         c_barang = kd_brg and
 												 i_aset   = no_aset and
												 d_barang_peroleh = tgl_perlh)
										   ORDER BY thn_ang");
	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("thn_ang"           =>(string)$result[$j]->thn_ang,
									"kd_brg"           =>(string)$result[$j]->kd_brg,
									"no_aset"           =>(string)$result[$j]->no_aset,
									"tgl_perlh"           =>(string)$result[$j]->tgl_perlh,
									"merk_type"   =>(string)$result[$j]->merk_type,
									"ur_sskel"           =>(string)$result[$j]->ur_sskel);
								  
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	//=======================================as 09 nop 07=======================================
	public function getDataPegawai($prmRapatEdit) {
       
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_peg_nip,n_peg,n_jabatan,i_orgb 
									FROM e_sdm_pegawai_0_tm 
									where i_peg_nip = ? ',$prmRapatEdit);
									
	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_peg_nip"           =>(string)$result[$j]->i_peg_nip,
									"n_peg"           	 =>(string)$result[$j]->n_peg,
									"n_jabatan"          =>(string)$result[$j]->n_jabatan,
									"i_orgb"            =>(string)$result[$j]->i_orgb);
						       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	//=================================12 nop 07======================================================================
	
	public function getInvBarangList() {
       
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT d_aset_thnanggar, c_barang, to_char(i_aset,'09999') as  i_aset , ur_sskel, 
									d_barang_peroleh, i_ruang, merk_type,rph_aset
									FROM e_ast_dir_item_tm a, e_ast_sskel_0_tr b, e_sabm_t_master_tm c
									where  substr(a.c_barang,1,1) = b.kd_gol
									       and substr(a.c_barang,2,2) = b.kd_bid 
									       and substr(a.c_barang,4,2) = b.kd_kel
									       and substr(a.c_barang,6,2) = b.kd_skel
									       and substr(a.c_barang,8,3) = b.kd_sskel 
									       and a.d_aset_thnanggar=c.thn_ang
									       and a.c_barang =c.kd_brg
									       and a.i_aset = c.no_aset
									       ORDER BY d_aset_thnanggar");
	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("d_aset_thnanggar"           =>(string)$result[$j]->d_aset_thnanggar,
									"c_barang"           		=>(string)$result[$j]->c_barang,
									"i_aset"           			=>(string)$result[$j]->i_aset,
									"ur_sskel"          		=>(string)$result[$j]->ur_sskel,
									"d_barang_peroleh"          =>(string)$result[$j]->d_barang_peroleh,
									"merk_type"   				=>(string)$result[$j]->merk_type,
									"rph_aset"   				=>(string)$result[$j]->rph_aset,
									"i_ruang"           		=>(string)$result[$j]->i_ruang);
								  
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getRuangPindahList($unitkr) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $where[] = '2';
		 $where[] = '3';
		 $where[] = $unitkr;
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchAll("SELECT i_ruang,i_ruang_lokasi,n_gedung,q_gedung_lantai,q_gedung_kapasitas,
		                                 q_gedung_luas,i_orgb_tgjwbgedung,
										 (case c_gedung_fungsi 
									     WHEN '1' then 'Ruang Kerja'
									     WHEN '2' then 'Ruang Rapat Umum' 
									     WHEN '3' then 'Ruang Rapat Deputi' 
									     WHEN '4' then 'Ruang Umum' 
									     else 'Lain-Lain' END) as c_gedung_fungsi
		                          FROM e_ast_ruangan_0_tr 
								  where (c_gedung_fungsi = ? or c_gedung_fungsi = ?)
								  and i_orgb_tgjwbgedung =?
								  ORDER BY i_ruang",$where);
								
		 $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
         $hasilAkhir[$j] =   array("i_ruang"                  =>(string)$result[$j]->i_ruang,
								   "i_ruang_lokasi"           =>(string)$result[$j]->i_ruang_lokasi,
								   "n_gedung"                 =>(string)$result[$j]->n_gedung,
	                               "q_gedung_lantai"          =>(string)$result[$j]->q_gedung_lantai,
								   "q_gedung_kapasitas"       =>(string)$result[$j]->q_gedung_kapasitas,
								   "q_gedung_luas"            =>(string)$result[$j]->q_gedung_luas,
								   "c_gedung_fungsi"          =>(string)$result[$j]->c_gedung_fungsi,
								   "i_orgb_tgjwbgedung"       =>(string)$result[$j]->i_orgb_tgjwbgedung);
				
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }						
	   
	 
   }
   
   // get Unit Kerja utk penghapusan inv=================================15 nop 07
   
   public function getUnitKerja() {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_orgb,n_orgb FROM e_org_0_0_tm ORDER BY i_orgb'); 
									  
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb"             =>(string)$result[$j]->i_orgb,
								   "n_orgb"           =>(string)$result[$j]->n_orgb); 
								  
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	//pindahan dr yg lama ke referensi utk ATK
	public function getRefKatListAll($pageNumber,$itemPerPage,$namaBarang) {
		$namaBarang = strtoupper($namaBarang);
		$nbrg = '%'.$namaBarang.'%';
	   
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $nbrg;
			 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$hasilAkhir = $db->fetchOne("select count(*) from e_ast_kategori_atk_tr where upper(n_atk_ctgr) like ?", $where);
			 }
			 else
			 {
			
				 $xLimit=$itemPerPage;
				 $xOffset=($pageNumber-1)*$itemPerPage;		
				 
				 $result = $db->fetchAll("SELECT c_atk_ctgr,n_atk_ctgr FROM e_ast_kategori_atk_tr where upper(n_atk_ctgr) like ? ORDER BY c_atk_ctgr
											limit $xLimit offset $xOffset", $where); 
				 
				 $jmlResult = count($result);
				 
				 for ($j = 0; $j < $jmlResult; $j++) {
						$hasilAkhir[$j] = array("c_atk_ctgr"           =>(string)$result[$j]->c_atk_ctgr,
												"n_atk_ctgr"           =>(string)$result[$j]->n_atk_ctgr);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
	public function getRefSatuan($pageNumber,$itemPerPage,$namaSatuan) {
		$namaSatuan = strtoupper($namaSatuan);
		$nbrg = '%'.$namaSatuan.'%';
	   
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $nbrg;
			 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$hasilAkhir = $db->fetchOne("select count(*) from e_satuan_0_0_tr where upper(e_satuan) like ?", $where);
			 }
			 else
			 {
			
				 $xLimit=$itemPerPage;
				 $xOffset=($pageNumber-1)*$itemPerPage;		
				 
				 $result = $db->fetchAll("SELECT c_satuan,e_satuan FROM e_satuan_0_0_tr where upper(e_satuan) like ? ORDER BY c_satuan
											limit $xLimit offset $xOffset", $where); 
				 
				 $jmlResult = count($result);
				 
				 for ($j = 0; $j < $jmlResult; $j++) {
						$hasilAkhir[$j] = array("c_satuan"           =>(string)$result[$j]->c_satuan,
												"e_satuan"           =>(string)$result[$j]->e_satuan);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
	public function getRefKatListAll2() {
       
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT c_atk_ctgr,n_atk_ctgr FROM e_ast_kategori_atk_tr ORDER BY c_atk_ctgr');
	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_atk_ctgr"           =>(string)$result[$j]->c_atk_ctgr,
								   "n_atk_ctgr"           =>(string)$result[$j]->n_atk_ctgr);
								  
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getRefAtkList($pageNumber,$itemPerPage,$katBarang,$namaBarang) {
		$namaBarang = strtoupper($namaBarang);
		$nbrg = '%'.$namaBarang.'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $katBarang;
			$where[] = $nbrg;
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$hasilAkhir = $db->fetchOne("select count(*) from e_ast_barang_atk_tr 
											 where c_atk_ctgr=? and n_atk like ? ",$where);
			 }
			else
			 {
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stock 
									FROM e_ast_barang_atk_tr where c_atk_ctgr=? and n_atk like ?   ORDER BY c_atk
									limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("c_atk"         =>(string)$result[$j]->c_atk,
								   "n_atk"           	=>(string)$result[$j]->n_atk,
								   "n_atk_satuan"    	=>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"     	=>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"      	=>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stock"      	=>(string)$result[$j]->q_atk_stock);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
	//Atk kosong..
	public function getRefAtkListKs($pageNumber,$itemPerPage,$katBarang,$namaBarang) {
		$namaBarang = strtoupper($namaBarang);
		$nbrg = '%'.$namaBarang.'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $katBarang;
			$where[] = $nbrg;
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$hasilAkhir = $db->fetchOne("select count(*) from e_ast_barang_atk_tr 
											 where c_atk_ctgr=? and n_atk like ? and q_atk_stock <= 0 ",$where);
			 }
			else
			 {
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stock 
									FROM e_ast_barang_atk_tr where c_atk_ctgr=? and n_atk like ? and q_atk_stock <= 0  ORDER BY c_atk
									limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("c_atk"         =>(string)$result[$j]->c_atk,
								   "n_atk"           	=>(string)$result[$j]->n_atk,
								   "n_atk_satuan"    	=>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"     	=>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"      	=>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stock"      	=>(string)$result[$j]->q_atk_stock);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
	
	public function getRefAtkList2($katBarang) {
        
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT c_atk,n_atk,n_atk_satuan,n_atk_merek,n_atk_tipe,q_atk_stock FROM e_ast_barang_atk_tr where c_atk_ctgr=? and q_atk_stock > 0  ORDER BY c_atk',$katBarang);
	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_atk"           =>(string)$result[$j]->c_atk,
								   "n_atk"           =>(string)$result[$j]->n_atk,
								   "n_atk_satuan"    =>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"     =>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"      =>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stock"      =>(string)$result[$j]->q_atk_stock);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	//============================================20 nop 2007 =========untuk ASSET=======asih=======================================================
	public function getRefGolAset() {
        
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT  kd_gol,kd_bid, kd_kel, kd_skel, kd_sskel, satuan, 
										ur_gol from e_ast_gol_aset_tr ORDER BY kd_gol');
	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("kd_gol"           	=>(string)$result[$j]->kd_gol,
								   "kd_bid"           	=>(string)$result[$j]->kd_bid,
								   "kd_kel"    			=>(string)$result[$j]->kd_kel,
								   "kd_skel"     		=>(string)$result[$j]->kd_skel,
								   "kd_sskel"      		=>(string)$result[$j]->kd_sskel,
								   "satuan"      		=>(string)$result[$j]->satuan,
								   "namaBrg"      		=>(string)$result[$j]->ur_gol);
							
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getRefBidAset($kdGol) {
        
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $where[] = $kdGol;
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT  kd_gol, kd_bid, kd_kel, kd_skel, kd_sskel, satuan, ur_bid
									FROM e_ast_bid_aset_tr
									WHERE kd_gol=?', $where);

	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("kd_gol"           	=>(string)$result[$j]->kd_gol,
								   "kd_bid"           	=>(string)$result[$j]->kd_bid,
								   "kd_kel"    			=>(string)$result[$j]->kd_kel,
								   "kd_skel"     		=>(string)$result[$j]->kd_skel,
								   "kd_sskel"      		=>(string)$result[$j]->kd_sskel,
								   "satuan"      		=>(string)$result[$j]->satuan,
								   "namaBrg"      		=>(string)$result[$j]->ur_bid);
							
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getRefKelAset($kdgol,$kdbid) {
       // echo 'diservice $kdgol'.$kdgol.$kdbid;
		
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $where[] = trim($kdgol);
		 $where[] = trim($kdbid);
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT kd_gol, kd_bid, kd_kel, kd_skel, kd_sskel, satuan, ur_kel 
									FROM e_ast_kel_aset_tr
									WHERE kd_gol=? and kd_bid=?',$where);

	     $jmlResult = count($result);
		 //echo "hitung ".$jmlResult;
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("kd_gol"           	=>(string)$result[$j]->kd_gol,
								   "kd_bid"           	=>(string)$result[$j]->kd_bid,
								   "kd_kel"    			=>(string)$result[$j]->kd_kel,
								   "kd_skel"     		=>(string)$result[$j]->kd_skel,
								   "kd_sskel"      		=>(string)$result[$j]->kd_sskel,
								   "satuan"      		=>(string)$result[$j]->satuan,
								   "namaBrg"      		=>(string)$result[$j]->ur_kel);
							
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getRefSKelAset($kdgol,$kdbid,$kdkel) {
       //echo 'di service '.$kdgol.'-'.$kdbid.'-'.$kdkel;
		
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $where[] = $kdgol;
		 $where[] = $kdbid;
		 $where[] = $kdkel;
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT kd_gol, kd_bid, kd_kel, kd_skel, kd_sskel, satuan, ur_skel
									FROM e_ast_skel_0_tr
									WHERE kd_gol=? and kd_bid=? and kd_kel =?' , $where);

	     $jmlResult = count($result);
		 //echo '$jmlResult'.$jmlResult;
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("kd_gol"           	=>(string)$result[$j]->kd_gol,
								   "kd_bid"           	=>(string)$result[$j]->kd_bid,
								   "kd_kel"    			=>(string)$result[$j]->kd_kel,
								   "kd_skel"     		=>(string)$result[$j]->kd_skel,
								   "kd_sskel"      		=>(string)$result[$j]->kd_sskel,
								   "satuan"      		=>(string)$result[$j]->satuan,
								   "namaBrg"      		=>(string)$result[$j]->ur_skel);
							
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getRefSSKelAset($kdgol,$kdbid,$kdkel,$kdskel) {
        
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $where[] = $kdgol;
		 $where[] = $kdbid;
		 $where[] = $kdkel;
		 $where[] = $kdskel;
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT  kd_gol, kd_bid, kd_kel, kd_skel, kd_sskel, satuan, ur_sskel 
									FROM e_ast_sskel_0_tr
									WHERE kd_gol=? and kd_bid=? and kd_kel=? and kd_skel=?' , $where);

	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("kd_gol"           	=>(string)$result[$j]->kd_gol,
								   "kd_bid"           	=>(string)$result[$j]->kd_bid,
								   "kd_kel"    			=>(string)$result[$j]->kd_kel,
								   "kd_skel"     		=>(string)$result[$j]->kd_skel,
								   "kd_sskel"      		=>(string)$result[$j]->kd_sskel,
								   "satuan"      		=>(string)$result[$j]->satuan,
								   "namaBrg"      		=>(string)$result[$j]->ur_sskel);
							
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	//============================================20 nop 2007 =======================================================================
	public function getGolAsetList() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchAll('SELECT  kd_gol,kd_bid, kd_kel, kd_skel, kd_sskel, satuan, 
										ur_gol from e_ast_gol_aset_tr ORDER BY kd_gol');
	     return $result;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	//dr p.ndang//28-1107==
	public function getListPegawaibyNip($nip) {
        echo 'test nip'.$nip;
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = "i_peg_nip = '".trim($data['nip'])."'";
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT n_peg,n_jabatan,i_orgb FROM e_sdm_pegawai_0_tm where i_peg_nip=?',$nip);
	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array(	"n_peg"           	 =>(string)$result[$j]->n_peg,
									"n_jabatan"          =>(string)$result[$j]->n_jabatan,
									"i_orgb"            =>(string)$result[$j]->i_orgb);
						       
		
		 }
        }
        	
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	//=========================================27 nop 2007 ===================================u halaman==========..All==============================
	
	public function getListPegawaiAll($pageNumber,$itemPerPage,$nmPegawai) {
		$nmPegawai = strtoupper($nmPegawai);
		$npeg = '%'.$nmPegawai.'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $npeg;
			$where[] = $npeg;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$hasil = $db->fetchAll("SELECT a.i_peg_nip,a.n_peg,b.n_jabatan,a.c_unit_kerja
									FROM e_sdm_pegawai_0_tm A, e_sdm_jabatan_0_tm B 
									where 
									a.i_peg_nip = b.i_peg_nip
									and a.c_unit_kerja = b.c_jabatan
									and upper(n_peg) like ?
									union
									select a.i_peg_nip, n_peg, NULL, a.c_unit_kerja 
									from  e_sdm_pegawai_0_tm A 
									where  
									not EXISTS(select * from  e_sdm_jabatan_0_tm B
									          where a.i_peg_nip = b.i_peg_nip)
									and upper(n_peg) like ?",$where);
			 $hasilAkhir=count($hasil);
			 echo 'jumlah'.$hasilAkhir;
			 }
			 
			 else
			 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 $result = $db->fetchAll("SELECT a.i_peg_nip,a.n_peg,b.n_jabatan,a.c_unit_kerja
									FROM e_sdm_pegawai_0_tm A, e_sdm_jabatan_0_tm B 
									where 
									a.i_peg_nip = b.i_peg_nip
									and a.c_unit_kerja = b.c_jabatan
									and upper(n_peg) like ?
									union
									select a.i_peg_nip, n_peg, NULL, a.c_unit_kerja 
									from  e_sdm_pegawai_0_tm A 
									where  
									not EXISTS(select * from  e_sdm_jabatan_0_tm B
									          where a.i_peg_nip = b.i_peg_nip)
									and upper(n_peg) like ?
									order by n_peg 
									limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_peg_nip"         =>(string)$result[$j]->i_peg_nip,
									"n_peg"           	 	=>(string)$result[$j]->n_peg,
									"n_jabatan"          	=>(string)$result[$j]->n_jabatan,
									"c_unit_kerja"			=>(string)$result[$j]->c_unit_kerja);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
	
	public function getListPegawaibyOrgAll($pageNumber,$itemPerPage,$unitkr,$nmPegawai) {
		$nmPegawai = strtoupper($nmPegawai);
		$npeg = '%'.$nmPegawai.'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		 $where[] = $unitkr;
		 $where[] = $npeg;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_sdm_pegawai_0_tm a, e_org_0_0_tm b
										where a.c_unit_kerja=? and upper(n_peg) like ? and a.i_orgb=b.i_orgb",$where);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT i_peg_nip,n_peg,n_jabatan,a.i_orgb,n_orgb,c_unit_kerja 
									FROM e_sdm_pegawai_0_tm a, e_org_0_0_tm b
									where a.c_unit_kerja=? and upper(n_peg) like ? and a.i_orgb=b.i_orgb
									ORDER BY i_peg_nip
									limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_peg_nip"          =>(string)$result[$j]->i_peg_nip,
										"n_peg"           	 =>(string)$result[$j]->n_peg,
										"n_jabatan"          =>(string)$result[$j]->n_jabatan,
										"i_orgb"             =>(string)$result[$j]->i_orgb,
										"n_orgb"             =>(string)$result[$j]->n_orgb,
										"c_unit_kerja"       =>(string)$result[$j]->c_unit_kerja);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
	
	public function getRuangRapatListAll($pageNumber,$itemPerPage) {
		 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where ='2';
		 $where = '3';
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_ruangan_0_tr 
			                             where c_gedung_fungsi = ? or c_gedung_fungsi = ?",$where);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT i_ruang,i_ruang_lokasi,n_gedung,q_gedung_lantai,q_gedung_kapasitas,
		                                 q_gedung_luas,i_orgb_tgjwbgedung,
										 (case c_gedung_fungsi 
									     WHEN '1' then 'Ruang Kerja'
									     WHEN '2' then 'Ruang Rapat Umum' 
									     WHEN '3' then 'Ruang Rapat Deputi' 
									     WHEN '4' then 'Ruang Umum' 
									     else 'Lain-Lain' END) as c_gedung_fungsi
										FROM e_ast_ruangan_0_tr 
										where c_gedung_fungsi = ? or c_gedung_fungsi = ?
										ORDER BY i_ruang
										limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] =   array("i_ruang"               =>(string)$result[$j]->i_ruang,
									   "i_ruang_lokasi"           =>(string)$result[$j]->i_ruang_lokasi,
									   "n_gedung"                 =>(string)$result[$j]->n_gedung,
		                               "q_gedung_lantai"          =>(string)$result[$j]->q_gedung_lantai,
									   "q_gedung_kapasitas"       =>(string)$result[$j]->q_gedung_kapasitas,
									   "q_gedung_luas"            =>(string)$result[$j]->q_gedung_luas,
									   "c_gedung_fungsi"          =>(string)$result[$j]->c_gedung_fungsi,
									   "i_orgb_tgjwbgedung"       =>(string)$result[$j]->i_orgb_tgjwbgedung);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
	
	public function getInfoRuangListAll($pageNumber,$itemPerPage,$prmjam,$prmtgl) {
		 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[]=$prmjam;
		 $where[]=$prmjam;
		 $where[]=$prmtgl;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM   e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b
										WHERE a.i_rapat_nippimpin=b.i_peg_nip and d_rapat_awalpakai <=?
										and d_rapat_akhirpakai > ? and d_rapat_pesan =?",$where);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT distinct a.i_orgb as i_orga, a.q_rapat_peserta, a.i_rapat_nippimpin, 
										b.n_peg, b.n_jabatan, b.i_orgb       
										FROM   e_ast_ajuan_pakai_ruang_tm a, e_sdm_pegawai_0_tm  b
										WHERE a.i_rapat_nippimpin=b.i_peg_nip and d_rapat_awalpakai <=?
										and d_rapat_akhirpakai > ? and d_rapat_pesan =?
										ORDER BY i_orga
										limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] =   array("i_orga"    			=>(string)$result[$j]->i_orga,
											"q_rapat_peserta"   =>(string)$result[$j]->q_rapat_peserta,
											"i_rapat_nippimpin" =>(string)$result[$j]->i_rapat_nippimpin,
											"n_peg"       		=>(string)$result[$j]->n_peg,
											"n_jabatan"       	=>(string)$result[$j]->n_jabatan,
											"i_orgb"       		=>(string)$result[$j]->i_orgb);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function getFasilitasListAll($pageNumber,$itemPerPage,$noRuang) {
		 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[]=$noRuang;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
				                         FROM e_ast_dir_item_tm a, e_ast_sskel_0_tr b
										 where a.i_ruang = ? and substr(a.c_barang,1,1) = b.kd_gol
									       and substr(a.c_barang,2,2) = b.kd_bid 
										   and substr(a.c_barang,4,2) = b.kd_kel
										   and substr(a.c_barang,6,2) = b.kd_skel
										   and substr(a.c_barang,8,3) = b.kd_sskel",$where);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT distinct a.c_barang,b.ur_sskel
				                         FROM e_ast_dir_item_tm a, e_ast_sskel_0_tr b
										 where a.i_ruang = ? and substr(a.c_barang,1,1) = b.kd_gol
									       and substr(a.c_barang,2,2) = b.kd_bid 
										   and substr(a.c_barang,4,2) = b.kd_kel
										   and substr(a.c_barang,6,2) = b.kd_skel
										   and substr(a.c_barang,8,3) = b.kd_sskel
											ORDER BY c_barang
											limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("c_barang"             =>(string)$result[$j]->c_barang,
								   "ur_sskel"           =>(string)$result[$j]->ur_sskel); 
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function getRuangPindahListAll($pageNumber,$itemPerPage) {
		 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where ='2';
		 $where = '3';
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) 
			                             FROM e_ast_ruangan_0_tr 
										 where c_gedung_fungsi = ? or c_gedung_fungsi = ?",$where);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT i_ruang,i_ruang_lokasi,n_gedung,q_gedung_lantai,q_gedung_kapasitas,
		                                 q_gedung_luas,i_orgb_tgjwbgedung,
										 (case c_gedung_fungsi 
									     WHEN '1' then 'Ruang Kerja'
									     WHEN '2' then 'Ruang Rapat Umum' 
									     WHEN '3' then 'Ruang Rapat Deputi' 
									     WHEN '4' then 'Ruang Umum' 
									     else 'Lain-Lain' END) as c_gedung_fungsi
										FROM e_ast_ruangan_0_tr 
										where c_gedung_fungsi = ? or c_gedung_fungsi = ?
										ORDER BY i_ruang
										limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] =   array("i_ruang"               =>(string)$result[$j]->i_ruang,
									   "i_ruang_lokasi"           =>(string)$result[$j]->i_ruang_lokasi,
									   "n_gedung"                 =>(string)$result[$j]->n_gedung,
		                               "q_gedung_lantai"          =>(string)$result[$j]->q_gedung_lantai,
									   "q_gedung_kapasitas"       =>(string)$result[$j]->q_gedung_kapasitas,
									   "q_gedung_luas"            =>(string)$result[$j]->q_gedung_luas,
									   "c_gedung_fungsi"          =>(string)$result[$j]->c_gedung_fungsi,
									   "i_orgb_tgjwbgedung"       =>(string)$result[$j]->i_orgb_tgjwbgedung);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
	
	public function getInventarisListAll($pageNumber,$itemPerPage) {
		 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where ='2';
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_dir_item_tm a, e_ast_sskel_0_tr b, e_sabm_t_master_tm c
										where  substr(a.c_barang,1,1) = b.kd_gol
									       and substr(a.c_barang,2,2) = b.kd_bid 
									       and substr(a.c_barang,4,2) = b.kd_kel
									       and substr(a.c_barang,6,2) = b.kd_skel
									       and substr(a.c_barang,8,3) = b.kd_sskel 
									       and a.d_aset_thnanggar=c.thn_ang
									       and a.c_barang =c.kd_brg
									       and a.i_aset = c.no_aset");
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT d_aset_thnanggar, c_barang, to_char(i_aset,'09999') as  i_aset , ur_sskel, 
										d_barang_peroleh, i_ruang, merk_type,rph_aset
										FROM e_ast_dir_item_tm a, e_ast_sskel_0_tr b, e_sabm_t_master_tm c
										where  substr(a.c_barang,1,1) = b.kd_gol
									       and substr(a.c_barang,2,2) = b.kd_bid 
									       and substr(a.c_barang,4,2) = b.kd_kel
									       and substr(a.c_barang,6,2) = b.kd_skel
									       and substr(a.c_barang,8,3) = b.kd_sskel 
									       and a.d_aset_thnanggar=c.thn_ang
									       and a.c_barang =c.kd_brg
									       and a.i_aset = c.no_aset
									       ORDER BY d_aset_thnanggar
										   limit $xLimit offset $xOffset"); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("d_aset_thnanggar"          =>(string)$result[$j]->d_aset_thnanggar,
										"c_barang"           		=>(string)$result[$j]->c_barang,
										"i_aset"           			=>(string)$result[$j]->i_aset,
										"ur_sskel"          		=>(string)$result[$j]->ur_sskel,
										"d_barang_peroleh"          =>(string)$result[$j]->d_barang_peroleh,
										"merk_type"   				=>(string)$result[$j]->merk_type,
										"rph_aset"   				=>(string)$result[$j]->rph_aset,
										"i_ruang"           		=>(string)$result[$j]->i_ruang);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
	public function getCariNamaBrgDIR($pageNumber,$itemPerPage,$tercatat,$namaBarang) {
	   $namaBarang = strtoupper($namaBarang);
	   $nbrg = '%'.$namaBarang.'%';
	  
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*) 
 						 from  e_ast_sskel_0_tr a, e_sabm_t_master_tm b
						 where substr(b.kd_brg,1,1) = a.kd_gol
						 and substr(b.kd_brg,2,2) = a.kd_bid 
						 and substr(b.kd_brg,4,2) = a.kd_kel
                         and substr(b.kd_brg,6,2) = a.kd_skel
                         and substr(b.kd_brg,8,3) = a.kd_sskel 
					     and  tercatat = '1' and ur_sskel like ?
						 and not exists(select c.i_aset from e_ast_dir_item_tm  c
								  where c.d_aset_thnanggar=b.thn_ang
								  and c.c_barang =b.kd_brg
								  and c.i_aset = b.no_aset)",$nbrg);  
        
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT distinct b.thn_ang, b.kd_brg, to_char(b.no_aset,'09999') as  i_aset , ur_sskel, 
										tgl_perlh, merk_type,rph_aset
										FROM  e_ast_sskel_0_tr a, e_sabm_t_master_tm b
										where  substr(b.kd_brg,1,1) = a.kd_gol
											and substr(b.kd_brg,2,2) = a.kd_bid 
											and substr(b.kd_brg,4,2) = a.kd_kel
											and substr(b.kd_brg,6,2) = a.kd_skel
											and substr(b.kd_brg,8,3) = a.kd_sskel 
											and  tercatat = '1'
											and ur_sskel like ?
											and not exists(select c.i_aset from e_ast_dir_item_tm  c
											where c.d_aset_thnanggar=b.thn_ang
												and c.c_barang =b.kd_brg
												and c.i_aset = b.no_aset)
												ORDER BY b.thn_ang
										   limit $xLimit offset $xOffset",$nbrg); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("d_aset_thnanggar"          =>(string)$result[$j]->thn_ang,
										"c_barang"           		=>(string)$result[$j]->kd_brg,
										"i_aset"           			=>(string)$result[$j]->i_aset,
										"ur_sskel"          		=>(string)$result[$j]->ur_sskel,
										"d_barang_peroleh"          =>(string)$result[$j]->tgl_perlh,
										"merk_type"   				=>(string)$result[$j]->merk_type,
										"rph_aset"   				=>(string)$result[$j]->rph_aset);
									
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
	public function getCariNamaBrgDIL($pageNumber,$itemPerPage,$tercatat,$namaBarang) {
	   $namaBarang = strtoupper($namaBarang);
	   $nbrg = '%'.$namaBarang.'%';
	  
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*) 
 						 from  e_ast_sskel_0_tr a, e_sabm_t_master_tm b
						 where substr(b.kd_brg,1,1) = a.kd_gol
						 and substr(b.kd_brg,2,2) = a.kd_bid 
						 and substr(b.kd_brg,4,2) = a.kd_kel
                         and substr(b.kd_brg,6,2) = a.kd_skel
                         and substr(b.kd_brg,8,3) = a.kd_sskel 
					     and  tercatat = '2' and ur_sskel like ?
						 and not exists(select c.i_aset from e_ast_dil_item_tm  c
								  where c.d_anggaran=b.thn_ang
								  and c.c_barang =b.kd_brg
								  and c.i_aset = b.no_aset)",$nbrg);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			  $result = $db->fetchAll("SELECT distinct b.thn_ang, b.kd_brg, to_char(b.no_aset,'09999') as  i_aset , ur_sskel, 
										tgl_perlh, merk_type,rph_aset
										FROM  e_ast_sskel_0_tr a, e_sabm_t_master_tm b
										where  substr(b.kd_brg,1,1) = a.kd_gol
											and substr(b.kd_brg,2,2) = a.kd_bid 
											and substr(b.kd_brg,4,2) = a.kd_kel
											and substr(b.kd_brg,6,2) = a.kd_skel
											and substr(b.kd_brg,8,3) = a.kd_sskel 
											and  tercatat = '2'
											and ur_sskel like ?
											and not exists(select c.i_aset from e_ast_dil_item_tm  c
											where   c.d_anggaran=b.thn_ang
												and c.c_barang =b.kd_brg
												and c.i_aset = b.no_aset)
												ORDER BY b.thn_ang
										   limit $xLimit offset $xOffset",$nbrg); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("d_aset_thnanggar"          =>(string)$result[$j]->thn_ang,
										"c_barang"           		=>(string)$result[$j]->kd_brg,
										"i_aset"           			=>(string)$result[$j]->i_aset,
										"ur_sskel"          		=>(string)$result[$j]->ur_sskel,
										"d_barang_peroleh"          =>(string)$result[$j]->tgl_perlh,
										"merk_type"   				=>(string)$result[$j]->merk_type,
										"rph_aset"   				=>(string)$result[$j]->rph_aset);
										 
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	public function getCariNamaBrgTNH($pageNumber,$itemPerPage,$namaBarang) {
	   $namaBarang = strtoupper($namaBarang);
	   $nbrg = '%'.$namaBarang.'%';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_kib_item_tm a, e_ast_sskel_0_tr b, e_sabm_t_ktnh_tm c
										where  substr(a.c_barang,1,1) = b.kd_gol
									       and substr(a.c_barang,2,2) = b.kd_bid 
									       and substr(a.c_barang,4,2) = b.kd_kel
									       and substr(a.c_barang,6,2) = b.kd_skel
									       and substr(a.c_barang,8,3) = b.kd_sskel 
									       and a.d_anggaran =c.thn_ang
									       and a.c_barang =c.kd_brg
									       and a.i_aset = c.no_aset");
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT thn_ang,kd_brg,tgl_prl,no_kib,
									to_char(no_aset,'09999') as no_aset,ur_sskel
									FROM e_sabm_t_ktnh_tm a,e_ast_sskel_0_tr b
									where  substr(a.kd_brg,1,1) = b.kd_gol
								           and substr(a.kd_brg,2,2) = b.kd_bid 
									       and substr(a.kd_brg,4,2) = b.kd_kel
									       and substr(a.kd_brg,6,2) = b.kd_skel
									       and substr(a.kd_brg,8,3) = b.kd_sskel 
										   and ur_sskel like ?
										   and not exists(select * from e_ast_kib_item_tm c
										   where d_anggaran = thn_ang and 
										         c_barang = kd_brg and
 												 i_aset   = no_aset and
												 d_perolehan = tgl_prl 
												 )
										   ORDER BY thn_ang 
										   limit $xLimit offset $xOffset",$nbrg); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				  $hasilAkhir[$j] = array("d_aset_thnanggar"           =>(string)$result[$j]->thn_ang,
									"c_barang"                   =>(string)$result[$j]->kd_brg,
									"i_aset"                     =>(string)$result[$j]->no_aset,
								    "d_barang_peroleh"           =>(string)$result[$j]->tgl_perlh,
									"no_kib"              =>(string)$result[$j]->no_kib,
									"merk_type"           =>(string)$result[$j]->merk_type,
									"ur_sskel"            =>(string)$result[$j]->ur_sskel);

			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
	
	public function getCariNamaBrgBDG($pageNumber,$itemPerPage,$namaBarang) {
	   $namaBarang = strtoupper($namaBarang);
	   $nbrg = '%'.$namaBarang.'%';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_kib_item_tm a, e_ast_sskel_0_tr b, e_sabm_t_kbdg_tm c
										where  substr(a.c_barang,1,1) = b.kd_gol
									       and substr(a.c_barang,2,2) = b.kd_bid 
									       and substr(a.c_barang,4,2) = b.kd_kel
									       and substr(a.c_barang,6,2) = b.kd_skel
									       and substr(a.c_barang,8,3) = b.kd_sskel 
									       and a.d_anggaran =c.thn_ang
									       and a.c_barang =c.kd_brg
									       and a.i_aset = c.no_aset");
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT thn_ang,kd_brg,tgl_prl,no_kib,
									to_char(no_aset,'09999') as no_aset,ur_sskel
									FROM e_sabm_t_kbdg_tm a,e_ast_sskel_0_tr b
									where  substr(a.kd_brg,1,1) = b.kd_gol
								           and substr(a.kd_brg,2,2) = b.kd_bid 
									       and substr(a.kd_brg,4,2) = b.kd_kel
									       and substr(a.kd_brg,6,2) = b.kd_skel
									       and substr(a.kd_brg,8,3) = b.kd_sskel 
										   and ur_sskel like ?
										   and not exists(select * from e_ast_kib_item_tm c
										   where d_anggaran = thn_ang and 
										         c_barang = kd_brg and
 												 i_aset   = no_aset and
												 d_perolehan = tgl_prl 
												 )
										   ORDER BY thn_ang 
										   limit $xLimit offset $xOffset",$nbrg); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				  $hasilAkhir[$j] = array("d_aset_thnanggar"           =>(string)$result[$j]->thn_ang,
									"c_barang"                   =>(string)$result[$j]->kd_brg,
									"i_aset"                     =>(string)$result[$j]->no_aset,
								    "d_barang_peroleh"           =>(string)$result[$j]->tgl_perlh,
									"no_kib"              =>(string)$result[$j]->no_kib,
									"merk_type"           =>(string)$result[$j]->merk_type,
									"ur_sskel"            =>(string)$result[$j]->ur_sskel);

			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
	public function getCariNamaBrgAKT($pageNumber,$itemPerPage,$namaBarang) {
	   $namaBarang = strtoupper($namaBarang);
	   $nbrg = '%'.$namaBarang.'%';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_kib_item_tm a, e_ast_sskel_0_tr b, e_sabm_t_kangk_tm c
										where  substr(a.c_barang,1,1) = b.kd_gol
									       and substr(a.c_barang,2,2) = b.kd_bid 
									       and substr(a.c_barang,4,2) = b.kd_kel
									       and substr(a.c_barang,6,2) = b.kd_skel
									       and substr(a.c_barang,8,3) = b.kd_sskel 
									       and a.d_anggaran=c.thn_ang
									       and a.c_barang =c.kd_brg
									       and a.i_aset = c.no_aset");
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT thn_ang,kd_brg,tgl_prl,no_kib,
									to_char(no_aset,'09999') as no_aset,merk,type,ur_sskel
									FROM e_sabm_t_kangk_tm a,e_ast_sskel_0_tr b
									where  substr(a.kd_brg,1,1) = b.kd_gol
								           and substr(a.kd_brg,2,2) = b.kd_bid 
									       and substr(a.kd_brg,4,2) = b.kd_kel
									       and substr(a.kd_brg,6,2) = b.kd_skel
									       and substr(a.kd_brg,8,3) = b.kd_sskel 
										   and ur_sskel like ?
										   and not exists(select * from e_ast_kib_item_tm c
										   where d_anggaran = thn_ang and 
										         c_barang = kd_brg and
 												 i_aset   = no_aset and
												 d_perolehan = tgl_prl 
												 )
										   ORDER BY thn_ang 
										   limit $xLimit offset $xOffset",$nbrg); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				  $hasilAkhir[$j] = array("d_aset_thnanggar"           =>(string)$result[$j]->thn_ang,
									"c_barang"                   =>(string)$result[$j]->kd_brg,
									"i_aset"                     =>(string)$result[$j]->no_aset,
								    "d_barang_peroleh"           =>(string)$result[$j]-> tgl_prl,
									"no_kib"              =>(string)$result[$j]->no_kib,
									"merk_type"           =>(string)$result[$j]->merk.(string)$result[$j]->type,
							
									"ur_sskel"            =>(string)$result[$j]->ur_sskel);

			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
	
	
	
	public function getCariNamaBrg($pageNumber,$itemPerPage,$namaBarang) {
	   $namaBarang = strtoupper($namaBarang);
	   $nbrg = '%'.$namaBarang.'%';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_dir_item_tm a, e_ast_sskel_0_tr b, e_sabm_t_master_tm c
										where  substr(a.c_barang,1,1) = b.kd_gol
									       and substr(a.c_barang,2,2) = b.kd_bid 
									       and substr(a.c_barang,4,2) = b.kd_kel
									       and substr(a.c_barang,6,2) = b.kd_skel
									       and substr(a.c_barang,8,3) = b.kd_sskel 
									       and a.d_aset_thnanggar=c.thn_ang
									       and a.c_barang =c.kd_brg
									       and a.i_aset = c.no_aset");
										   
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT distinct d_aset_thnanggar, c_barang, to_char(i_aset,'09999') as  i_aset , ur_sskel, 
										d_barang_peroleh, i_ruang, merk_type,rph_aset
										FROM e_ast_dir_item_tm a, e_ast_sskel_0_tr b, e_sabm_t_master_tm c
										where  substr(a.c_barang,1,1) = b.kd_gol
									       and substr(a.c_barang,2,2) = b.kd_bid 
									       and substr(a.c_barang,4,2) = b.kd_kel
									       and substr(a.c_barang,6,2) = b.kd_skel
									       and substr(a.c_barang,8,3) = b.kd_sskel 
									       and a.d_aset_thnanggar=c.thn_ang
									       and a.c_barang =c.kd_brg
									       and a.i_aset = c.no_aset
									       and ur_sskel like ?
										   ORDER BY d_aset_thnanggar
										   limit $xLimit offset $xOffset",$nbrg); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("d_aset_thnanggar"          =>(string)$result[$j]->d_aset_thnanggar,
										"c_barang"           		=>(string)$result[$j]->c_barang,
										"i_aset"           			=>(string)$result[$j]->i_aset,
										"ur_sskel"          		=>(string)$result[$j]->ur_sskel,
										"d_barang_peroleh"          =>(string)$result[$j]->d_barang_peroleh,
										"merk_type"   				=>(string)$result[$j]->merk_type,
										"rph_aset"   				=>(string)$result[$j]->rph_aset,
										"i_ruang"           		=>(string)$result[$j]->i_ruang);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	public function getKodeBarang($pageNumber,$itemPerPage,$namaBarang) {
	   $namaBarang = strtoupper($namaBarang);
	   //$nbrg = '%'.$namaBarang.'%';
	   $nbrg = $namaBarang.'%';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_sskel_0_tr WHERE  ur_sskel like ?",$nbrg);
		 }
		 else
		 {
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT  kd_gol, kd_bid, kd_kel, kd_skel, kd_sskel, satuan, ur_sskel 
										FROM e_ast_sskel_0_tr
										WHERE  ur_sskel like ?
										   ORDER BY ur_sskel
										   limit $xLimit offset $xOffset",$nbrg); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("kd_gol"        =>(string)$result[$j]->kd_gol,
								   "kd_bid"           	=>(string)$result[$j]->kd_bid,
								   "kd_kel"    			=>(string)$result[$j]->kd_kel,
								   "kd_skel"     		=>(string)$result[$j]->kd_skel,
								   "kd_sskel"      		=>(string)$result[$j]->kd_sskel,
								   "satuan"      		=>(string)$result[$j]->satuan,
								   "namaBrg"      		=>(string)$result[$j]->ur_sskel);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
	public function getBarangListAll($pageNumber,$itemPerPage,$nopeng) {
	   $namaBarang = strtoupper($namaBarang);
	   //$nbrg = '%'.$namaBarang.'%';
	   $nbrg = $namaBarang.'%';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$where[] = $nbrg;
		$where[] = $nopeng;
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			//$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_sskel_0_tr ");
			$result = $db->fetchAll("SELECT a.c_atk,a.c_atk_ctgr,q_atk_beli,q_atk_setujubeli,n_atk,n_atk_satuan,n_atk_merek,
										 n_atk_tipe,q_atk_stock FROM e_ast_ajuanbeli_itematk_tm a,e_ast_barang_atk_tr b 
										 where n_atk like ? and i_atk_ajuan =? and a.c_atk_ctgr= b.c_atk_ctgr and a.c_atk = b.c_atk 
										 and not exists(select d.i_atk_ajuan,c.c_atk,c.c_atk_ctgr from e_ast_terima_itematk_tm c,e_ast_terima_atk_tm d
										 where c.i_atk_terima = d.i_atk_terima and a.i_atk_ajuan = d.i_atk_ajuan and a.c_atk = c.c_atk and a.c_atk_ctgr = c.c_atk_ctgr)
										 oRDER BY c_atk",$where); 
			 
			 $jmlResult = count($result);
			 $hasilAkhir =$jmlResult;
		 }
		 else
		 {
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 
			 $result = $db->fetchAll("SELECT a.c_atk,a.c_atk_ctgr,q_atk_beli,q_atk_setujubeli,n_atk,n_atk_satuan,n_atk_merek,
										 n_atk_tipe,q_atk_stock FROM e_ast_ajuanbeli_itematk_tm a,e_ast_barang_atk_tr b 
										 where n_atk like ? and i_atk_ajuan =? and a.c_atk_ctgr= b.c_atk_ctgr and a.c_atk = b.c_atk 
										 and not exists(select d.i_atk_ajuan,c.c_atk,c.c_atk_ctgr from e_ast_terima_itematk_tm c,e_ast_terima_atk_tm d
										 where c.i_atk_terima = d.i_atk_terima and a.i_atk_ajuan = d.i_atk_ajuan and a.c_atk = c.c_atk and a.c_atk_ctgr = c.c_atk_ctgr)
										 oRDER BY c_atk
										 limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("c_atk"           		=>(string)$result[$j]->c_atk,
										   "c_atk_ctgr"      	=>(string)$result[$j]->c_atk_ctgr,
										   "q_atk_beli"      	=>(string)$result[$j]->q_atk_beli,
										   "n_atk"           	=>(string)$result[$j]->n_atk,
										   "n_atk_satuan"    	=>(string)$result[$j]->n_atk_satuan,
										   "n_atk_merek"     	=>(string)$result[$j]->n_atk_merek,
										   "n_atk_tipe"      	=>(string)$result[$j]->n_atk_tipe,
										   "q_atk_stock"     	=>(string)$result[$j]->q_atk_stock,
										   "q_atk_setujubeli" 	=>(string)$result[$j]->q_atk_setujubeli);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	 
   	public function getBarangListAll2($nopeng) {
        
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT a.c_atk,a.c_atk_ctgr,q_atk_beli,n_atk,n_atk_satuan,n_atk_merek,
		 n_atk_tipe,q_atk_stock FROM e_ast_ajuanbeli_itematk_tm a,e_ast_barang_atk_tr b 
		 where i_atk_ajuan =? and a.c_atk_ctgr= b.c_atk_ctgr and a.c_atk = b.c_atk 
		 and not exists(select d.i_atk_ajuan,c.c_atk,c.c_atk_ctgr from e_ast_terima_itematk_tm c,e_ast_terima_atk_tm d
		 where c.i_atk_terima = d.i_atk_terima and a.i_atk_ajuan = d.i_atk_ajuan and a.c_atk = c.c_atk and a.c_atk_ctgr = c.c_atk_ctgr)
		 oRDER BY c_atk',$nopeng);
	     $jmlResult = count($result);
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("c_atk"           =>(string)$result[$j]->c_atk,
								   "c_atk_ctgr"      =>(string)$result[$j]->c_atk_ctgr,
								   "q_atk_beli"      =>(string)$result[$j]->q_atk_beli,
								   "n_atk"           =>(string)$result[$j]->n_atk,
								   "n_atk_satuan"    =>(string)$result[$j]->n_atk_satuan,
								   "n_atk_merek"     =>(string)$result[$j]->n_atk_merek,
								   "n_atk_tipe"      =>(string)$result[$j]->n_atk_tipe,
								   "q_atk_stock"      =>(string)$result[$j]->q_atk_stock);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getNoInventarisPeralatanPCList($pageNumber,$itemPerPage,$namaBarang) {
		$namaBarang = strtoupper($namaBarang);
		$kdBrg 	= '2120204';
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
										      and substr(a.kd_brg,8,3) = b.kd_sskel");
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
										      and kd_brg like ? and ur_sskel like ? ORDER BY thn_ang
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
	public function getRekananList($pageNumber,$itemPerPage,$n_prsh)
	{
    $namaPrsh = strtoupper($n_prsh);
	$persh	= '%'.$n_prsh.'%';	
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');		
	try 
	
	{
	    $where[] =$persh;
		if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT COUNT(*)
										FROM e_rekanan_prsh_0_tm Rek,e_rekanan_almt_prsh_tm Alm 
										where Rek.i_rekanan=Alm.i_rekanan and n_prsh like ?",$where);
		 }
		 else
		 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
		 	 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 $result = $db->fetchAll("SELECT Rek.i_rekanan,n_prsh,a_prsh_jalan,a_prsh_kota,
									i_prsh_telpon,i_prsh_fax
									FROM e_rekanan_prsh_0_tm Rek,e_rekanan_almt_prsh_tm Alm
									where Rek.i_rekanan=Alm.i_rekanan  
									and n_prsh like ?  
									      limit $xLimit offset $xOffset",$where); 
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++)
			{
				$hasilAkhir[$j] = array("i_rekanan" =>(string)$result[$j]->i_rekanan
						,"n_prsh"  =>(string)$result[$j]->n_prsh
						,"a_prsh_jalan" =>(string)$result[$j]->a_prsh_jalan
						,"a_prsh_kota" =>(string)$result[$j]->a_prsh_kota
						,"i_prsh_telpon" =>(string)$result[$j]->i_prsh_telpon
						,"i_prsh_fax" =>(string)$result[$j]->i_prsh_fax);
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
	//====================================================================================
	public function dataBrgSewaList()
	{
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');		
	try 
	{
		$query="select kd_gol, kd_bid, kd_kel, kd_skel, kd_sskel , satuan ,ur_sskel".
		" FROM e_ast_sskel_0_tr limit 100 "; 
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $db->fetchAll($query);
		$jmlResult = count($result);
		for ($j = 0; $j < $jmlResult; $j++)
		{
		$hasilAkhir[$j] = array("kd_gol" =>(string)$result[$j]->kd_gol
				,"kd_bid"  =>(string)$result[$j]->kd_bid
				,"kd_kel" =>(string)$result[$j]->kd_kel
				,"kd_skel"  =>(string)$result[$j]->kd_skel
				,"kd_sskel" =>(string)$result[$j]->kd_sskel
				,"satuan"  =>(string)$result[$j]->satuan
				,"ur_sskel" =>(string)$result[$j]->ur_sskel);
		}					 
		return $hasilAkhir;
	} 
	catch (Exception $e)
	{
		echo $e->getMessage().'<br>';
		return 'gagal <br>';
	}
}
//tambahan dari elis 18-12-2007=======================================================
	public function getNoInventarisAngkutanList($pageNumber,$itemPerPage,$namaBarang) 
	{
		echo "+masuk services getNoInventarisAngkutanList";
		$namaBarang = strtoupper($namaBarang);
		$kdBrg 	= '202';
		$kbr 	= $kdBrg.'%';
		$nbrg 	= $namaBarang.'%';
		
		echo "+namaBarang =".$namaBarang;
		echo "+kdBrg =".$kdBrg;
		echo "+kbr =".$kbr;
		echo "+nbrg =".$nbrg;
		
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
			 echo "jumlah di server =".$jmlResult;
			 
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
	//=======================================================================================
	public function getBrgBergerak($pageNumber,$itemPerPage,$namaBarang) 
	{
		//echo "+masuk services getBrgBergerak";
		$namaBarang = strtoupper($namaBarang);
		$nbrg 	= '%'.$namaBarang.'%';
		
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{
		     $where[] =$nbrg;
		 
	         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_barangsewa_0_tm 
									    where upper(n_barang) like ? ",$where);
		 }
		 else
		 {
			$inv_jd[$j] = array("c_barang"   	=>(string)$this->InvList[$j]['c_barang'],
				"n_barang"   	=>(string)$this->InvList[$j]['n_barang'],
				"n_barang_merktype"   	=>(string)$this->InvList[$j]['n_barang_merktype'],
				"i_barang_serial"   	=>(string)$this->InvList[$j]['i_barang_serial'],
				"e_keterangan"   	=>(string)$this->InvList[$j]['e_keterangan']);
			
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;					 
			 $result = $db->fetchAll("SELECT c_barang,n_barang,
								 	  n_barang_merktype,i_barang_serial,
									  e_keterangan
									  FROM e_ast_barangsewa_0_tm 
									  where upper(n_barang) like ?
									  limit $xLimit offset $xOffset",$where); 
			 $jmlResult = count($result);
			 echo "jumlah di server =".$jmlResult;
			 
			 for ($j = 0; $j < $jmlResult; $j++) 
			 {
				$hasilAkhir[$j] = array("c_barang"	=>(string)$result[$j]->c_barang,
				"n_barang" 				=>(string)$result[$j]->n_barang,
				"n_barang_merktype" 				=>(string)$result[$j]->n_barang_merktype,
				"i_barang_serial" 				=>(string)$result[$j]->i_barang_serial,
				"e_keterangan" 				=>(string)$result[$j]->e_keterangan);
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
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_vendor_0_tr");
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				
				$result = $db->fetchAll("select a.i_rekanan,n_rekanan,c.n_prsh_ijinusaha,a_prsh_jalan,a_prsh_kota,i_prsh_telpon,i_prsh_fax 
										from e_rekanan_0_0_tm a,e_rekanan_ijin_prsh_tm b,e_rekanan_ijin_usaha_tm c,e_rekanan_almt_prsh_tm d
										where a.i_rekanan = b.i_rekanan and a.i_rekanan = d.i_rekanan
										and b.i_prsh_ijinusaha = c.i_prsh_ijinusaha 
										and c.n_prsh_ijinusaha  like 'ATK%' ");
								 
				
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


/// 2007-30-12 author : Hendar Rusman Desc : Pop Up perbaikan
	public function getLisPbarangDir($unitkr) {
		 
	$registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select  a.i_barang_serah,b.d_barang_peroleh,b.d_aset_thnanggar,
								b.c_barang, to_char(b.i_aset,'09999') as  i_aset,b.i_aset as iaset,c.ur_sskel 
								from e_ast_dir_0_tm a, e_ast_dir_item_tm b, e_ast_sskel_0_tr c
								where a.i_barang_serah = b.i_barang_serah
								and substr(b.c_barang,2,2) = c.kd_bid 
								and substr(b.c_barang,4,2) = c.kd_kel
								and substr(b.c_barang,6,2) = c.kd_skel
								and substr(b.c_barang,8,3) = c.kd_sskel 
								and a.i_orgb_pemberi='$unitkr'
								order by a.i_barang_serah asc");
								
         $jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
			$kode=$result[$j]->c_barang;
			$noaset=$result[$j]->iaset;
			$tgoleh=$result[$j]->d_barang_peroleh;
// merk

			$merktype = $db->fetchCol('SELECT merk_type FROM e_sabm_t_master_tm 
										WHERE kd_brg = ?',$result[$j]->c_barang ,
										'and no_aset = ?',$result[$j]->iaset ,
										'and tgl_perlh = ?',$result[$j]->d_barang_peroleh);
		
			if (isset($merktype[0])) {
				$merktype=$merktype[0];				
				}
			else 
			{	$merktype="";}	 
			//$merktype="Mitshubisi";
			

		 
           $hasilAkhir[$j] = array("i_barang_serah"      =>(string)$result[$j]->i_barang_serah,
									"d_barang_peroleh"   =>(string)$result[$j]->d_barang_peroleh,
									"d_aset_thnanggar"   =>(string)$result[$j]->d_aset_thnanggar,
									"c_barang"    		 =>(string)$result[$j]->c_barang,
									"i_aset"     		 =>(string)$result[$j]->i_aset,
									"ur_sskel"   		 =>(string)$result[$j]->ur_sskel,
									"merktype"   		 =>$merktype);
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function getLisPbarangDil($unitkr) {
		 
	$registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select  a.i_barang_serah,b.d_perolehan,b.d_anggaran,
								b.c_barang,to_char(b.i_aset,'09999') as  i_aset,
								b.i_aset as  iaset,b.n_aset_lokasifisik,c.ur_sskel 
								from e_ast_dil_0_tm a, e_ast_dil_item_tm b, e_ast_sskel_0_tr c
								where a.i_barang_serah = b.i_barang_serah
								and substr(b.c_barang,2,2) = c.kd_bid 
								and substr(b.c_barang,4,2) = c.kd_kel
								and substr(b.c_barang,6,2) = c.kd_skel
								and substr(b.c_barang,8,3) = c.kd_sskel 
								and a.i_orgb_pemberi='$unitkr'
								order by a.i_barang_serah asc");
								
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
// merk

			$merktype = $db->fetchCol('SELECT merk_type FROM e_sabm_t_master_tm 
										WHERE kd_brg = ?',$result[$j]->c_barang ,
										'and no_aset = ?',$result[$j]->iaset ,
										'and tgl_perlh = ?',$result[$j]->d_perolehan);
		
			if (isset($merktype[0])) {
				$merktype=$merktype[0];				
				}
			else 
			{	$merktype="";}		 
		 
           $hasilAkhir[$j] = array("i_barang_serah"      =>(string)$result[$j]->i_barang_serah,
									"d_barang_peroleh"   =>(string)$result[$j]->d_perolehan,
									"d_aset_thnanggar"   =>(string)$result[$j]->d_anggaran,
									"c_barang"    		 =>(string)$result[$j]->c_barang,
									"i_aset"     		 =>(string)$result[$j]->i_aset,
									"n_aset_lokasifisik" =>(string)$result[$j]->n_aset_lokasifisik,
									"ur_sskel"   		 =>(string)$result[$j]->ur_sskel,
									"merktype"   		 =>$merktype);
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	


	public function getLisPbarangKib($unitkr) {
		 
	$registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("select  a.i_barang_serah,b.d_perolehan,b.d_anggaran,
									b.c_barang,to_char(b.i_aset,'09999') as  i_aset,b.i_aset as  iaset,c.ur_sskel 
									from e_ast_kib_0_tm a, e_ast_kib_item_tm b, e_ast_sskel_0_tr c
									where a.i_barang_serah = b.i_barang_serah
									and substr(b.c_barang,2,2) = c.kd_bid 
									and substr(b.c_barang,4,2) = c.kd_kel
									and substr(b.c_barang,6,2) = c.kd_skel
									and substr(b.c_barang,8,3) = c.kd_sskel 
									and a.i_orgb_pemberi='$unitkr'
									order by a.i_barang_serah asc");
								
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {

// merk

			$merktype = $db->fetchCol('SELECT merk_type FROM e_sabm_t_master_tm 
										WHERE kd_brg = ?',$result[$j]->c_barang ,
										'and no_aset = ?',$result[$j]->iaset ,
										'and tgl_perlh = ?',$result[$j]->d_perolehan);
		     
			if (isset($merktype[0])) {
				$merktype=$merktype[0];				
				}
			else 
			{	$merktype="";}	
			
           $hasilAkhir[$j] = array("i_barang_serah"      =>(string)$result[$j]->i_barang_serah,
									"d_barang_peroleh"   =>(string)$result[$j]->d_perolehan,
									"d_aset_thnanggar"   =>(string)$result[$j]->d_anggaran,
									"c_barang"    		 =>(string)$result[$j]->c_barang,
									"i_aset"     		 =>(string)$result[$j]->i_aset,
									"ur_sskel"   		 =>(string)$result[$j]->ur_sskel,
									"merktype"   		 =>$merktype);
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

//======================== tambahan aceng 10 Desember ===============================
	
	public function getListPegawaiByUnit($pageNumber,$itemPerPage,$kdunit) {
		 
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
	
	// ----------- get No inventaris untuk perbaikan TI ===========================
	public function getAsetTIList($kdunit) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   $where[] = $kdunit;
	   $where[] = $kdunit;
	   
	   try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			/*if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetcOne("Select count(*) FROM  E_AST_KOMPUTER_0_TR A , E_AST_SSKEL_0_TR B
									Where A.C_Unit_Kerja = ?
									And B.kd_gol = substr(A.kd_brg,1,1)
									And B.kd_bid = substr(A.kd_brg,2,2)
									And B.kd_kel = substr(A.kd_brg,4,2)
									And B.kd_skel = substr(A.kd_brg,6,2)
									And B.kd_sskel = substr(A.kd_brg,8,3)
									UNION
									Select  A.d_anggaran, A.c_barang, A.i_aset, A.d_perolehan,  B.ur_sskel, A.n_hw 
									from E_AST_HARDWARE_0_TM A, E_AST_SSKEL_0_TR B
									Where A.i_orgb = ?
									And B.kd_gol = substr(A.c_barang,1,1)
									And B.kd_bid = substr(A.c_barang,2,2)
									And B.kd_kel = substr(A.c_barang,4,2)
									And B.kd_skel = substr(A.c_barang,6,2)
									",$where);
			}else {
			  */  
				$xLimit=$itemPerPage;
			    $xOffset=($pageNumber-1)*$itemPerPage;
			 
				$result = $db->fetchAll("Select  A.thn_ang, A.kd_brg, A.no_aset, A.tgl_perlh,  B.ur_sskel, 
										A.i_komputer_macaddress
										FROM  E_AST_KOMPUTER_0_TR A , E_AST_SSKEL_0_TR B
										Where A.C_Unit_Kerja = ?
										And B.kd_gol = substr(A.kd_brg,1,1)
										And B.kd_bid = substr(A.kd_brg,2,2)
										And B.kd_kel = substr(A.kd_brg,4,2)
										And B.kd_skel = substr(A.kd_brg,6,2)
										And B.kd_sskel = substr(A.kd_brg,8,3)
										UNION
										Select  A.d_anggaran, A.c_barang, A.i_aset, A.d_perolehan,  B.ur_sskel, A.n_hw 
										from E_AST_HARDWARE_0_TM A, E_AST_SSKEL_0_TR B
										Where A.i_orgb = ?
										And B.kd_gol = substr(A.c_barang,1,1)
										And B.kd_bid = substr(A.c_barang,2,2)
										And B.kd_kel = substr(A.c_barang,4,2)
										And B.kd_skel = substr(A.c_barang,6,2)
										", $where);
			 //limit $xLimit offset $xOffset",$where);
				$jmlResult = count($result);
			 
				for ($j = 0; $j < $jmlResult; $j++) {
			 
					$hasilAkhir[$j] = array("thn_ang"       			=>(string)$result[$j]->thn_ang,
											"kd_brg"           			=>(string)$result[$j]->kd_brg, 
											"no_aset"         			=>(string)$result[$j]->no_aset, 
											"tgl_perlh"          		=>(string)$result[$j]->tgl_perlh,
											"ur_sskel"         			=>(string)$result[$j]->ur_sskel,
											"i_komputer_macaddress"    	=>(string)$result[$j]->i_komputer_macaddress); 
				}
			//}					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	//======================== akhir tambahan aceng 10 Desember ===============================
	
	public function getNoInventarisPCList($unitkr) {
	$kdBrg = '21201';
	$kbr = '%'.$kdBrg.'%';
	//$where[] = $unitkr;
	$where[] = $kbr;
		 
	$registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT  a.thn_ang,a.kd_brg,to_char(a.no_aset,'09999') as no_aset,
											a.tgl_perlh,b.ur_sskel
											FROM e_sabm_t_master_tm A, e_ast_sskel_0_tr B
											where substr(a.kd_brg,1,1) = b.kd_gol
											and substr(a.kd_brg,2,2) = b.kd_bid 
											and substr(a.kd_brg,4,2) = b.kd_kel
											and substr(a.kd_brg,6,2) = b.kd_skel
											and substr(a.kd_brg,8,3) = b.kd_sskel
											and  kd_brg like ?
									ORDER BY thn_ang,kd_brg, no_aset, tgl_perlh,no_aset",$where);
							
         $jmlResult = count($result);
		 
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("thn_ang"           =>(string)$result[$j]->thn_ang,
									"kd_brg"           =>(string)$result[$j]->kd_brg,
									"no_aset"           =>(string)$result[$j]->no_aset,
									"tgl_perlh"           =>(string)$result[$j]->tgl_perlh,
									"ur_sskel"           =>(string)$result[$j]->ur_sskel);
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	//=========================================27 nop 2007 ===================================u halaman==========..All==============================
	public function createKdBarangSewa() {
	    $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	    try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ);
		 $result = $db->fetchOne('SELECT gen_nosewa()');
		 echo 'hasil kd barang'.$result;
		 return $result;
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return  0;
	   }
 
	} 

	// tambahan aceng 09-02-2008 untuk pencarian vendor by nama pada pop up vendor
	public function getVendorByNama($pageNumber,$itemPerPage,$nmVendor) 
	{
		$nbr = strtoupper($nmVendor);
		$nbrg = $nbr.'%';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{
			//$where[] =$kbr;
			$where[] =$nbrg;
			
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_vendor_0_tr
											 where n_rekanan=? ",$where);
			}
			else
			{	
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				
				$result = $db->fetchAll("SELECT i_rekanan,n_rekanan,a_rekanan,n_rekanan_agendistr,
								i_rekanan_telp,n_rekanan_kontak  
								FROM e_ast_vendor_0_tr 
								where n_rekanan LIKE ? 
								limit $xLimit offset $xOffset",$where);
								 
				
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
	//tambahan asih 13 feb 08=============================================
	public function getMataUangList() {
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT  c_matauang, c_matauang_simbol, n_matauang  FROM e_keu_matauang_0_tr ORDER BY c_matauang_simbol');
	     $jmlResult = count($result);
		 
		if($jmlResult > 0){
			for ($j = 0; $j < $jmlResult; $j++) {
		 
				$hasilAkhir[$j] = array("c_matauang"           =>(string)$result[$j]->c_matauang,
									    "c_matauang_simbol"    =>(string)$result[$j]->c_matauang_simbol,
									    "n_matauang"           =>(string)$result[$j]->n_matauang);
			}
		}		 
	    return $hasilAkhir;
		} catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
		}
	}
	//======================================================
	
	// Ina : 28-07-2008 : Awal
	
	public function getPembuatKIB($nip) 
	{
					
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{	
		
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
			 
	     	return $hasilAkhir;
	   } 
	   catch (Exception $e) 
	   {
         	echo $e->getMessage().'<br>';
	     	return 'Data tidak ada <br>';
	   }
	}
	// Ina : 28:07-2008 :Akhir

}
?>