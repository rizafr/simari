<?php
class ast_invlainnya_Service {   
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
	
	public function getDaftarInventarisLainList($kdPemilik) {
		   $registry = Zend_Registry::getInstance();
		   $db = $registry->get('db');
		   try {
			 $where =$kdPemilik;
			 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 $query=" SELECT thn_ang,kd_lokasi,kd_pemilik,kd_brg,no_aset,lok_fisik " .
					" ,C.ur_sskel " .
					" FROM e_sabm_t_dil_tm A, e_ast_sskel_0_tr C " .
					" where kd_pemilik = ?	and substr(a.kd_brg,1,1) = c.kd_gol and substr(a.kd_brg,2,2) = c.kd_bid and substr(a.kd_brg,4,2) = c.kd_kel and substr(a.kd_brg,6,2) = c.kd_skel and substr(a.kd_brg,8,3) = c.kd_sskel ";
			 $result = $db->fetchAll($query,$where);
			 $jmlResult = count($result);		 
			 for ($j = 0; $j < $jmlResult; $j++) {		 
				$hasilAkhir[$j] = array("thn_ang"	=>(string)$result[$j]->thn_ang
		                               ,"kd_lokasi"           	=>(string)$result[$j]->kd_lokasi
		                               ,"kd_pemilik"         	=>(string)$result[$j]->kd_pemilik
		                               ,"kd_brg"       		=>(string)$result[$j]->kd_brg
		                               ,"no_aset"    		=>(string)$result[$j]->no_aset
		                               ,"lok_fisik"         	=>(string)$result[$j]->lok_fisik
		                               ,"ur_sskel"         	=>(string)$result[$j]->ur_sskel
		                               );			
			 }					 
		     return $hasilAkhir;
		   } 
		   catch (Exception $e) {
		     echo $e->getMessage().'<br>';
		     return 'gagal <br>';
		   }						
	}

	public function getAllDaftarInventarisLainList() {
		   $registry = Zend_Registry::getInstance();
		   $db = $registry->get('db');
		   try {			 
			 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 $query=" SELECT thn_ang,kd_lokasi,kd_pemilik,kd_brg,no_aset,lok_fisik " .
					" ,C.ur_sskel " .
					" FROM e_sabm_t_dil_tm A, e_ast_sskel_0_tr C " .
					" where  substr(a.kd_brg,1,1) = c.kd_gol and substr(a.kd_brg,2,2) = c.kd_bid and substr(a.kd_brg,4,2) = c.kd_kel and substr(a.kd_brg,6,2) = c.kd_skel and substr(a.kd_brg,8,3) = c.kd_sskel ";
			 $result = $db->fetchAll($query);
			 $jmlResult = count($result);		 
			 for ($j = 0; $j < $jmlResult; $j++) {		 
				$hasilAkhir[$j] = array("thn_ang"	=>(string)$result[$j]->thn_ang
		                               ,"kd_lokasi"           	=>(string)$result[$j]->kd_lokasi
		                               ,"kd_pemilik"         	=>(string)$result[$j]->kd_pemilik
		                               ,"kd_brg"       		=>(string)$result[$j]->kd_brg
		                               ,"no_aset"    		=>(string)$result[$j]->no_aset
		                               ,"lok_fisik"         	=>(string)$result[$j]->lok_fisik
		                               ,"ur_sskel"         	=>(string)$result[$j]->ur_sskel
		                               );			
			 }					 
		     return $hasilAkhir;
		   } 
		   catch (Exception $e) {
		     echo $e->getMessage().'<br>';
		     return 'gagal <br>';
		   }						
	}
	
	public function getJabatan($kdOrg,$nipPeg) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	   $db->setFetchMode(Zend_Db::FETCH_OBJ); 
       $teuing = $db->fetchOne("SELECT n_jabatan FROM e_sdm_jabatan_0_tm WHERE c_jabatan = '$kdOrg' AND
                                i_peg_nip = '$nipPeg'");
		return $teuing;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Tidak Ada Data <br>';
	   }
	} 	
	
}	
?>