<?
class ast_data_software_services{
  
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

//***********FUNGSI UNTUK  MENHAPUS DATA SOFTWARE DARI TABEL e-ast_software_0_tr ****************	
	public function deletedataSW($noSW) {
	     
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	  	 $where[] = "i_sw =  '".$noSW."'";
	     $db->delete('e_ast_software_0_tr', $where);
		 $db->commit();
	     return 'sukses <br>';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	/* *************************************************************************************************************************************
	 * FUNGSI UNTUK MELAKUKAN LISTING DATA SOFWARE
	 ****************************************************************************************************************************************/
	 public function getSoftwareList() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchAll('SELECT a.i_sw,a.n_sw,a.n_sw_kelompok,b.i_rekanan,b.n_rekanan,a.i_sw_licensi,a.i_sw_versi,a.e_sw_platform FROM e_ast_software_0_tr a,e_ast_vendor_0_tr b WHERE a.i_rekanan =b.i_rekanan');
	     $jmlResult = count($result);
		
		 if($jmlResult>0){
		    for ($j = 0; $j < $jmlResult; $j++) {
            $hasilAkhir[$j] = array("i_sw"          =>(string)$result[$j]->i_sw,
		                            "n_sw"          =>(string)$result[$j]->n_sw,
								    "n_sw_kelompok" =>(string)$result[$j]->n_sw_kelompok,
									"i_rekanan"     =>(string)$result[$j]->i_rekanan,
								    "n_rekanan"     =>(string)$result[$j]->n_rekanan,
								    "i_sw_licensi"  =>(string)$result[$j]->i_sw_licensi,
								    "i_sw_versi"    =>(string)$result[$j]->i_sw_versi,
								    "e_sw_platform" =>(string)$result[$j]->e_sw_platform);
		    }
        }		 
	     return $hasilAkhir;
	    }catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	
	
	
	/*************************************************************************************
	 * FUNGSI UNTUK MENCARI DATA SOFWARE BERDASARKAN NAMA SOFTWARE
	 *************************************************************************************/
    public function getCariSoftwareList($namaSW){ 
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try{
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll("SELECT a.i_sw,a.n_sw,a.n_sw_kelompok,b.n_rekanan,a.i_sw_licensi,a.i_sw_versi,a.e_sw_platform FROM e_ast_software_0_tr a,e_ast_vendor_0_tr b WHERE a.i_rekanan =b.i_rekanan AND upper(n_sw)  LIKE upper(?||'%') order by n_sw",$namaSW);
         $jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++){
		  $hasilAkhir[$j] = array("i_sw"          =>(string)$result[$j]->i_sw,
		                          "n_sw"          =>(string)$result[$j]->n_sw,
								  "n_sw_kelompok" =>(string)$result[$j]->n_sw_kelompok,
								  "n_rekanan"     =>(string)$result[$j]->n_rekanan,
								  "i_sw_licensi"  =>(string)$result[$j]->i_sw_licensi,
								  "i_sw_versi"    =>(string)$result[$j]->i_sw_versi,
								  "e_sw_platform" =>(string)$result[$j]->e_sw_platform);
								   
		  }					 
	      return $hasilAkhir;
	    }catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	/*************************************************************************************
	 * FUNGSI UNTUK MEMASUKAN DATA SOFWARE KE TABEL 'e_ast_sofware_0_tr'
	 *************************************************************************************/
	public function insertDataSoftware(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $tambah_data_SW = array("i_sw"                =>$data['txti_sw'],
		                         "n_sw"                =>$data['txtn_sw'],
				                 "n_sw_kelompok"       =>$data['txtn_sw_kelompok'],
				                 "i_sw_versi"          =>$data['txti_sw_versi'],
							     "d_sw_releasepublish" =>$data['txtd_sw_releasepublish'],
				                 "c_matauang"          =>$data['txtc_matauang'],
							     "v_sw_harga"          =>$data['txtv_sw_harga'],
				                 "d_sw_lastupdate"     =>$data['txtd_sw_lastupdate'],
							     "e_sw_reqsystem"      =>$data['txte_sw_reqsystem'],
				                 "e_sw_platform"       =>$data['txte_sw_platform'],
				                 "d_sw_expiregaransi"  =>$data['txtd_sw_expiregaransi'],
							     "i_rekanan"           =>$data['txti_rekanan'],
				                 "d_sw_peroleh"        =>$data['txtd_sw_peroleh'],
							     "d_sw_expirelicensi"  =>$data['txtd_sw_expirelicensi'],
							     "i_sw_licensi"        =>$data['txti_sw_licensi'],
							     "q_sw_licensi"        =>$data['txtq_sw_licensi'],
							     "c_sw_tipelicensi"    =>$data['txtc_sw_tipelicensi'],
							     "i_sw_nomorlicensi"   =>$data['txti_sw_nomorlicensi'],
							     "c_sw_sertifikat"     =>$data['txtc_sw_sertifikat'],
							     "i_sw_sertifikat"     =>$data['txti_sw_sertifikat'],
							     "i_entry"             =>$data['i_entry'],
							     "d_entry"             =>$data['d_entry']);
	     $db->insert("e_ast_software_0_tr", $tambah_data_SW);
		 $db->commit();
		
		 $_hasil = array("rcNumber"=>"1",
		                 "rcDesc"  =>"Proses Sukses");
	     return 'sukses <br>';
	   } catch (Exception $e) {
			 echo "gagal";
		$db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
   
  
	/*************************************************************************************
	 * FUNGSI UNTUK MEMASUKAN DATA TECHNICAL USER KE TABEL 'e_ast_sofware_0_tr'
	 *************************************************************************************/
	public function insertTeknicalUser(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
    try {
	     $db->beginTransaction();
	     $tambah_data_TecknicalSW = array("i_sw"          =>$data['txti_sw'],
		                                  "c_sw_teknikal" =>$data['txtno_ts'],
				                          "n_sw_teknikal" =>$data['txtn_nama'],
				                          "i_sw_teknikaltelp"    =>$data['txtno_tlp']);
	     $db->insert("e_ast_teknikal_software_tr", $tambah_data_TecknicalSW);
		 $db->commit();
		
		 $_hasil = array("rcNumber"=>"1",
		                 "rcDesc"  =>"Proses Sukses");
	     return 'sukses <br>';
	   } catch (Exception $e) {
			 echo "gagal";
		$db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
		 
		 
		 
	/*************************************************************************************
	 * FUNGSI UNTUK MELIHAT  DAFTAR DAN KODE VENDOR
	 *************************************************************************************/
   
	public function getVendorListAll() {
       
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_rekanan,n_rekanan FROM e_ast_vendor_0_tr ORDER BY n_rekanan');
	     $jmlResult = count($result);
		 if($jmlResult > 0){
 		  for ($j = 0; $j < $jmlResult; $j++) {
           $hasilAkhir[$j] = array("i_rekanan"           =>(string)$result[$j]->i_rekanan,
	 					           "n_rekanan"           =>(string)$result[$j]->n_rekanan);
								 	
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function getVendorByID($Ivendor){
	$registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT n_rekanan FROM e_ast_vendor_0_tr Where i_rekanan=?',$Ivendor);
	     $jmlResult = count($result);
		 if($jmlResult > 0){
 		  for ($j = 0; $j < $jmlResult; $j++) {
		  	   //echo $result[$j]->n_rekanan;
           $hasilAkhir[$j] = array("n_rekanan" =>(string)$result[$j]->n_rekanan);
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function getMatauangListAll(){
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT c_matauang,c_matauang_simbol,n_matauang FROM e_keu_matauang_0_tr ORDER BY n_matauang');
	     $jmlResult = count($result);
		 if($jmlResult > 0){
 		  for ($j = 0; $j < $jmlResult; $j++) {
           $hasilAkhir[$j] = array("c_matauang"           =>(string)$result[$j]->c_matauang,
	 					           "c_matauang_simbol"           =>(string)$result[$j]->c_matauang_simbol,
								   "n_matauang"           =>(string)$result[$j]->n_matauang);
								 	
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
  
    public function getSoftwareByID($idsw){
	    $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT * FROM e_ast_software_0_tr WHERE i_sw=?',$idsw);
	     $jmlResult = count($result);
		 if($jmlResult > 0){
 		  for ($j = 0; $j < $jmlResult; $j++) {
	
           $hasilAkhir[$j] = array("i_sw"                =>(string)$result[$j]->i_sw,
	 					           "n_sw"                =>(string)$result[$j]->n_sw,
								   "n_sw_kelompok"       =>(string)$result[$j]->n_sw_kelompok,
								   "i_sw_versi"          =>(string)$result[$j]->i_sw_versi,
	 					           "d_sw_releasepublish" =>(string)$result[$j]->d_sw_releasepublish,
								   "c_matauang"          =>(string)$result[$j]->c_matauang,
							       "v_sw_harga"          =>(string)$result[$j]->v_sw_harga,
								   "d_sw_lastupdate"     =>(string)$result[$j]->d_sw_lastupdate,
								   "e_sw_reqsystem"      =>(string)$result[$j]->e_sw_reqsystem,
	 					           "e_sw_platform"       =>(string)$result[$j]->e_sw_platform,
								   "d_sw_expiregaransi"  =>(string)$result[$j]->d_sw_expiregaransi,
								   "i_rekanan"           =>(string)$result[$j]->i_rekanan,
								   "d_sw_peroleh"        =>(string)$result[$j]->d_sw_peroleh,
	 					           "d_sw_expirelicensi"  =>(string)$result[$j]->d_sw_expirelicensi,
								   "i_sw_licensi"        =>(string)$result[$j]->i_sw_licensi,
							       "q_sw_licensi"        =>(string)$result[$j]->q_sw_licensi,
								   "c_sw_tipelicensi"    =>(string)$result[$j]->c_sw_tipelicensi,
								   "i_sw_nomorlicensi"   =>(string)$result[$j]->i_sw_nomorlicensi,
	 					           "c_sw_sertifikat"     =>(string)$result[$j]->c_sw_sertifikat,
								   "i_sw_sertifikat"     =>(string)$result[$j]->i_sw_sertifikat,
								   "i_entry"             =>(string)$result[$j]->i_entry,
								   "d_entry"             =>(string)$result[$j]->d_entry);
								 	
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	
	

	 /* fungsi untuk merubah data software  ke tabel 'e_ast_software_0_tr'
	 */
	public function updateDataSoftware(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $update_data_SW = array("i_sw"                =>$data['txti_sw'],
		                         "n_sw"                =>$data['txtn_sw'],
				                 "n_sw_kelompok"       =>$data['txtn_sw_kelompok'],
				                 "i_sw_versi"          =>$data['txti_sw_versi'],
							     "d_sw_releasepublish" =>$data['txtd_sw_releasepublish'],
				                 "c_matauang"          =>$data['txtc_matauang'],
							     "v_sw_harga"          =>$data['txtv_sw_harga'],
				                 "d_sw_lastupdate"     =>$data['txtd_sw_lastupdate'],
							     "e_sw_reqsystem"      =>$data['txte_sw_reqsystem'],
				                 "e_sw_platform"       =>$data['txte_sw_platform'],
				                 "d_sw_expiregaransi"  =>$data['txtd_sw_expiregaransi'],
							     "i_rekanan"           =>$data['txti_rekanan'],
				                 "d_sw_peroleh"        =>$data['txtd_sw_peroleh'],
							     "d_sw_expirelicensi"  =>$data['txtd_sw_expirelicensi'],
							     "i_sw_licensi"        =>$data['txti_sw_licensi'],
							     "q_sw_licensi"        =>$data['txtq_sw_licensi'],
							     "c_sw_tipelicensi"    =>$data['txtc_sw_tipelicensi'],
							     "i_sw_nomorlicensi"   =>$data['txti_sw_nomorlicensi'],
							     "c_sw_sertifikat"     =>$data['txtc_sw_sertifikat'],
							     "i_sw_sertifikat"     =>$data['txti_sw_sertifikat'],
							     "i_entry"             =>$data['i_entry'],
							     "d_entry"             =>$data['d_entry']);
		 $where[] = "i_sw = '".$data['txti_sw']."'";
	     $db->update('e_ast_software_0_tr',$update_data_SW, $where);
		 $db->commit();
	     return 'sukses <br>';
	    } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
} 
?>