<?php
class Ast_Fileupload_Service {
    private static $instance;
   
    // A private constructor; prevents direct creation of object
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
	
//=======================SEARCH HISTORY FILE UPLOADED =============================================
    /**
	 * fungsi untuk menampilkan data Pegawai ke tabel 'e_sabm_upload_0_tm'
	 */
        //public function getFileUploaded($nip,$nama) {
	public function getFileUploaded() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	  
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		// $where[] = $nip;
		// $where[] = $nama;
		//echo "nip--> ".$nip;
		//echo "nama--> ".$nama;
		$result = $db->fetchAll("SELECT  e_sabm_upload_0_tm.d_upload, e_sabm_upload_0_tm.n_table,
                                         q_record_upload,n_path,n_pgm_upload,n_table_oa 
                                         FROM e_sabm_upload_0_tm  ,e_sabm_upload_0_vm  
                                         where e_sabm_upload_0_tm.d_upload= e_sabm_upload_0_vm.d_upload
                                         and e_sabm_upload_0_tm.n_table=e_sabm_upload_0_vm.n_table
                                         order by n_table,d_upload");	
				 
                $jmlResult = count($result);
		 //echo "TEST JML".$jmlResult;
		 for ($j = 0; $j < $jmlResult; $j++) {
		//   $nmUnitKerja = $db->fetchCol('SELECT n_orgb FROM e_org_0_0_tm WHERE i_orgb = ?',$result[$j]->i_orgb);
                $hasilAkhir[$j] = array("d_upload"  =>(string)$result[$j]->d_upload,
	                                "n_table"   =>(string)$result[$j]->n_table,
                                        "q_record_upload" =>(string)$result[$j]->q_record_upload,
					"n_path"    =>(string)$result[$j]->n_path,
					"n_pgm_upload" => (string)$result[$j]->n_pgm_upload,
					"n_table_oa" => (string)$result[$j]->n_table_oa);
								   
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
//=============== Insert/upadte Data dari dbf SABM ke Tabel OA Postgresql =================     
  
   public function getTableOASABM() {
   $registry = Zend_Registry::getInstance();
   $db = $registry->get('db');
  
   try {
       $db->setFetchMode(Zend_Db::FETCH_OBJ); 
       $result  = $db->fetchAll("select distinct n_table   from e_sabm_upload_0_tm");
       
       $jmlResult = count($result);
       echo "<br> jmlResult : ".$jmlResult;
       for ($j = 0; $j < $jmlResult; $j++) {
          $hasilakhir[$j] = array ("n_table"   =>(string)$result[$j]->n_table);
       }
       return $hasilakhir;
   } catch (Exception $e) {
        echo $e->getMessage().'<br>';
     return 'gagal <br>';
   }
}	 

    /**
	 * fungsi untuk merubah data jumlah record dan tgl upload di 'e_sabm_upload_0_tm'
	 */
	public function updateSabmUpload(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 if (is_array($data)) {
		   $hitungData = count($data);
		   for ($i = 0; $i < $hitungData; $i++) {
	         $log = array( "d_upload" => date("Y-m-d"),
                                "q_record_upload" => $data[$i]['q_record_upload']);
                                      
		     $where[] = "d_upload = '".$data[$i]['d_upload']."'";
	             $where[] = "n_table = '".$data[$i]['n_table']."'";
	         $db->update('e_sabm_upload_0_tm',$log, $where);
		   }
		 }
		 $db->commit();
	     return 'sukses <br>';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
      /**
	 * fungsi untuk insert data jumlah record dan tgl upload di 'e_sabm_upload_0_tm'
	 */
	public function insertSabmUpload(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 if (is_array($data)) {
		   $hitungData = count($data);
		   for ($i = 0; $i < $hitungData; $i++) {
	         $log = array( "d_upload" => date("Y-m-d"),
                                      "n_table"  => $data[$i]['n_table'],  
                                      "q_record_upload" => $data[$i]['q_record_upload'],
                                      "n_path" => $data[$i]['n_path'],
                                      "n_pgm_upload" => $data[$i]['n_pgm_upload'],
                                      "n_table_oa" => $data[$i]['n_table_oa'] ); 
 
 	         $db->insert('e_sabm_upload_0_tm',$log);
		   }
		 }
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
	
	
	
	
	
	
	
	
	
	
	
	
	
	
        
	
	
	
	