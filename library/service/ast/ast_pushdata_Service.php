<?php
//include ("connect.php");
class ast_pushdata_Service {
   
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
   
 
      public function insertFileUpload(array $data){
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
		 $dataFile = array("n_file"         		=>$data['n_file'],
	                           "c_satker"    	        	=>$data['c_satker'],
						       "c_status"                =>$data['status'],
							   "i_entry"               =>$data['i_entry'],
							   "d_entry"  	            =>$data['d_entry']);
	    
	     $db->insert('aset.tm_file_upload',$dataFile);
		 $db->commit();
		 $db->closeConnection();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
		 $db->closeConnection();
	     return 'gagal';
	   }
    }	
    public function getTMFILEUPLOADDetail($pageNumber,$itemPerPage,$id){
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
		    $sql = "select count(*)
					from aset.tm_file_uploaddetail";
			if(strlen($status)>0){
			$sql = $sql." where id ='".$id."'";
			}
			
			$hasilAkhir = $db->fetchOne($sql); 
            			
		 }
		 else
		 {
		    $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		    $sql = "select id,n_file,n_table,q_data
					from aset.tm_file_uploaddetail
					";
            if(strlen($status)>0){
			$sql = $sql." where id ='".$id."'";
			}					
			$sql=$sql." limit $xLimit offset $xOffset";
			 
		  //echo $sql;
		 $result = $db->fetchAll($sql); 								 
								 

         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("id"           =>(string)$result[$j]->id,
								   "n_file"           =>(string)$result[$j]->n_file,
								   "n_table"           =>(string)$result[$j]->n_table,
								   "q_data"           =>(string)$result[$j]->q_data);
		  }
         }
        }	
        		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }	
	}
	public function getTMFILEUPLOAD($pageNumber,$itemPerPage,$status) {     
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
		    $sql = "select count(*)
					from aset.tm_file_upload";
			if(strlen($status)>0){
			$sql = $sql." where c_status ='".$status."'";
			}
			
			$hasilAkhir = $db->fetchOne($sql); 
            			
		 }
		 else
		 {
		    $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		    $sql = "select id,n_file,c_satker,c_status
					from aset.tm_file_upload
					";
            if(strlen($status)>0){
			$sql = $sql." where c_status ='".$status."'";
			}					
			$sql=$sql." order by i_entry desc 
			            limit $xLimit offset $xOffset";
			 
		  //echo $sql;
		 $result = $db->fetchAll($sql); 								 
								 

         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("id"           =>(string)$result[$j]->id,
								   "n_file"           =>(string)$result[$j]->n_file,
								   "c_satker"           =>(string)$result[$j]->c_satker,
								   "c_status"           =>(string)$result[$j]->c_status);
		  }
         }
        }	
        		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

}	
?>