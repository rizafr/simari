<?php
class Simak_Service {
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
	
//=======================SEARCH HISTORY FILE DOWNLOADED =============================================
    /**
	 */
 	public function getFileInserttxt() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	  
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
               // $erkakl = $db->fetchOne('SELECT entry_renja()'); //jalankan fungsi utk ngisi table te
		// $where[] = $nip;
		// $where[] = $nama;
		//echo "nip--> ".$nip;
		//echo "nama--> ".$nama;
		$result = $db->fetchAll("SELECT  e_ref_download_0_vm.d_instxt, e_ref_download_0_vm.n_file_txt,
                                         n_table_oa,q_record_instxt,n_path,n_pgm_instxt 
                                         FROM e_ref_download_0_tr, e_ref_download_0_vm
                                         where e_ref_download_0_tr.d_instxt = e_ref_download_0_vm.d_instxt
                                         and e_ref_download_0_tr.n_file_txt = e_ref_download_0_vm.n_file_txt
                                         and e_ref_download_0_vm.n_file_txt like '%txt'
                                         order by n_file_txt,d_instxt");	
				 
                $jmlResult = count($result);
		 //echo "TEST JML".$jmlResult;
		 for ($j = 0; $j < $jmlResult; $j++) {
		  $ntableoa = (string)$result[$j]->n_table_oa;
		  $rectableoa = $db->fetchAll("SELECT * FROM  $ntableoa");
		  $jrectableoa = count($rectableoa);
                  $hasilAkhir[$j] = array("d_instxt"  =>(string)$result[$j]->d_instxt,
	                                "n_file_txt"   =>(string)$result[$j]->n_file_txt,
	                                "n_table_oa"   => $ntableoa,
                                        "q_record_instxt" =>(string)$result[$j]->q_record_instxt,
					"n_path"    =>(string)$result[$j]->n_path,
					"n_pgm_instxt" => (string)$result[$j]->n_pgm_instxt,
					"q_rec_tableoa" => $jrectableoa);
								   
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
//======================= END SEARCH HISTORY FILE DOWNLOADED =============================================

	
}
?>