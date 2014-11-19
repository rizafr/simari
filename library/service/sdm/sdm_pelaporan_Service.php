<?php
class sdm_pelaporan_Service {
    private static $instance;
    private function __construct() {
    }

    // The singleton method
    public static function getInstance() {
       if (!isset(self::$instance)) {
           $c = __CLASS__;
           self::$instance = new $c;
       }

       return self::$instance;
    }
	

	public function listOrg() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {	     
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchAll("select i_orgb, n_orgb from e_org_0_0_tm where c_orgb_level='2'");
		 
         $jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++) {
           $data[$j] = array("i_orgb"  =>(string)$result[$j]->i_orgb,
	                         "n_orgb"  =>(string)$result[$j]->n_orgb);
		 }					 
	     return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'ga ono data rek <br>';
	   }
	} 
	 
	public function getDukList($cari,$currentPage, $numToDisplay) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {

		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     if(($currentPage==0) && ($numToDisplay==0))
			{
		 $data = $db->fetchOne('select count(*) from
							((select * from e_sdm_peg_duk_vm 
							where max_gol is not null  
							order by max_gol desc, d_peg_tmtgolongan, c_eselon, d_peg_tmtmasuk, c_pend desc, i_peg_nip)
							union all 
							select * from e_sdm_peg_duk_vm 					
							where max_gol is null) a');
		}
		else		
			{
		$xLimit=$numToDisplay;
		$xOffset=($currentPage-1)*$numToDisplay;	
							
		$result = $db->fetchAll("(select * from e_sdm_peg_duk_vm 
							where max_gol is not null  
							order by max_gol desc, d_peg_tmtgolongan, c_eselon, d_eselon_tmt, d_peg_tmtmasuk, c_pend desc, d_peg_lahir, i_peg_nip $cari  limit $xLimit offset $xOffset )
							union all 
							select * from e_sdm_peg_duk_vm 					
							where max_gol is null $cari  limit $xLimit offset $xOffset ");

         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
           $data[$j] = array("i_peg_nip"  =>(string)$result[$j]->i_peg_nip,
	                         "n_peg"  =>(string)$result[$j]->n_peg,
							 "n_peg_gelar"  	=>(string)$result[$j]->n_peg_gelar,
							 "c_eselon"  	=>(string)$result[$j]->c_eselon,
							 "d_eselon_tmt"  	=>(string)$result[$j]->d_eselon_tmt,
							 "d_peg_tmtmasuk"  	=>(string)$result[$j]->d_peg_tmtmasuk,
							 "thnmasakerja"  	=>(string)$result[$j]->thn_masakerja,
							 "blnmasakerja"  	=>(string)$result[$j]->bln_masakerja,
							 "a_peg_lahir"  	=>(string)$result[$j]->a_peg_lahir,
							 "d_peg_lahir"  	=>(string)$result[$j]->d_peg_lahir,
							 "e_keterangan"  	=>(string)$result[$j]->e_keterangan,
							 "c_peg_golongan"  	=>(string)$result[$j]->max_gol,
							 "d_peg_tmtgolongan"  	=>(string)$result[$j]->d_peg_tmtgolongan,
							 "n_jabatan"  	=>(string)$result[$j]->n_jabatan,
							 "d_jabatan_mulai"  	=>(string)$result[$j]->d_jabatan_mulai,
							 "n_pend"  	=>(string)$result[$j]->n_pend,
							 "c_pend"  	=>(string)$result[$j]->c_pend,	
							 "n_pend_jurusan"  	=>(string)$result[$j]->n_pend_jurusan,
							 "n_pend_lembaga"  	=>(string)$result[$j]->n_pend_lembaga,
							 "n_pend_kota"  	=>(string)$result[$j]->n_pend_kota,
							 "d_pend_akhir"  	=>(string)$result[$j]->d_pend_akhir,
							 "n_pend_latih"  	=>(string)$result[$j]->n_pend_latih,
							 "d_pend_seleslatih"  	=>(string)$result[$j]->d_pend_seleslatih,
							 "lamalatih"  	=>(string)$result[$j]->lamapelatihan,
							 "totaldata" =>$totalData,
							 "d_peg_pnilaiawal"   =>(string)$result[$j]->d_peg_pnilaiawal,
							 "q_peg_kesetiaan"   =>(string)$result[$j]->q_peg_kesetiaan,
							 "q_peg_preskerja"   =>(string)$result[$j]->q_peg_preskerja,
							 "q_peg_tggjawab"   =>(string)$result[$j]->q_peg_tggjawab,
							 "q_peg_ketaatan"   =>(string)$result[$j]->q_peg_ketaatan,
							 "q_peg_kejujuran"   =>(string)$result[$j]->q_peg_kejujuran,
							 "q_peg_kerjasama"   =>(string)$result[$j]->q_peg_kerjasama,
							 "q_peg_prakarsa"   =>(string)$result[$j]->q_peg_prakarsa,
							 "q_peg_kpimpinan"   =>(string)$result[$j]->q_peg_kpimpinans,
							 "d_peg_pnilaiawal"  =>(string)$result[$j]->d_peg_pnilaiawal,
							 "d_lhkpn_periode"  =>(string)$result[$j]->dlhkpnperiode,
							 "v_lhkpn_kekayaan"  =>(string)$result[$j]->v_lhkpn_kekayaan,
							 "d_pajak_tahun"  =>(string)$result[$j]->dpajaktahun,
							 "v_pajak_terutang1"  =>(string)$result[$j]->v_pajak_terutang1,
							 "v_pajak_dipotong1"  =>(string)$result[$j]->v_pajak_dipotong1,
							 "v_pajak_sendiri1"  =>(string)$result[$j]->v_pajak_sendiri1
							 );
		 }
}
	     return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'ga ono data rek <br>';
	   }
	}  
	
	public function findDukByOrg($iOrgb) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {

		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     
		 if($iOrgb)
		{
			 $result = $db->fetchAll("select * from
										(
										(select * from e_sdm_peg_duk_vm 				
										where max_gol is not null  			
										order by max_gol desc, d_peg_tmtgolongan, d_peg_tmtmasuk, c_pend desc)
										union all 
										select * from e_sdm_peg_duk_vm 					
										where max_gol is null  
										) a 
										where a.i_orgb = '$iOrgb'");
		}
		else
		{
			$result = $db->fetchAll("(select * from e_sdm_peg_duk_vm 				
										where max_gol is not null  			
										order by max_gol desc, d_peg_tmtgolongan, d_peg_tmtmasuk, c_pend desc)
										union all 
										select * from e_sdm_peg_duk_vm 					
										where max_gol is null  
									");
		}

         $jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++) {
		   //echo "PANJANG ".strlen($result[$j]->c_pgm);
		   //$nPropinsi = $db->fetchCol('SELECT n_propinsi FROM e_sdm_propinsi_0_tr WHERE c_propinsi = ?',$result[$j]->c_propinsi);
           $data[$j] = array("i_peg_nip"  =>(string)$result[$j]->i_peg_nip,
	                         "n_peg"  =>(string)$result[$j]->n_peg,
							 "n_peg_gelar"  	=>(string)$result[$j]->n_peg_gelar,
							 "c_eselon"  	=>(string)$result[$j]->c_eselon,
							 "d_eselon_tmt"  	=>(string)$result[$j]->d_eselon_tmt,
							 "d_peg_tmtmasuk"  	=>(string)$result[$j]->d_peg_tmtmasuk,
							 "thnmasakerja"  	=>(string)$result[$j]->thn_masakerja,
							 "blnmasakerja"  	=>(string)$result[$j]->bln_masakerja,
							 "a_peg_lahir"  	=>(string)$result[$j]->a_peg_lahir,
							 "d_peg_lahir"  	=>(string)$result[$j]->d_peg_lahir,
							 "e_keterangan"  	=>(string)$result[$j]->e_keterangan,
							 "c_peg_golongan"  	=>(string)$result[$j]->max_gol,
							 "d_peg_tmtgolongan"  	=>(string)$result[$j]->d_peg_tmtgolongan,
							 "n_jabatan"  	=>(string)$result[$j]->n_jabatan,
							 "d_jabatan_mulai"  	=>(string)$result[$j]->d_jabatan_mulai,
							 "n_pend"  	=>(string)$result[$j]->n_pend,
							 "c_pend"  	=>(string)$result[$j]->c_pend,	
							 "n_pend_jurusan"  	=>(string)$result[$j]->n_pend_jurusan,
							 "n_pend_lembaga"  	=>(string)$result[$j]->n_pend_lembaga,
							 "n_pend_kota"  	=>(string)$result[$j]->n_pend_kota,
							 "d_pend_akhir"  	=>(string)$result[$j]->d_pend_akhir,
							 "n_pend_latih"  	=>(string)$result[$j]->n_pend_latih,
							 "d_pend_seleslatih"  	=>(string)$result[$j]->d_pend_seleslatih,
							 "lamalatih"  	=>(string)$result[$j]->lamapelatihan);
		 }			 

	     return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'ga ono data rek <br>';
	   }
	}
}
?>