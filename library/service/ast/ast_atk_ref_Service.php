<?php
class ast_atk_ref_Service {
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
	 * fungsi untuk menampilkan data Pagu ke tabel 'e_pgm_0_0_tr'
	  */
	public function getRefAtkListAll() {
	   echo 'I am constructed';
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     //echo "TEST CONN ".$db->getConnection()."<br>";
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchAll('SELECT c_atk,n_atk,n_atk_merek,n_atk_tipe FROM e_ast_barang_atk_tr ORDER BY c_atk');
	     return $result;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	} 
}
?>