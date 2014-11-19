<?php
class ast_report_Service {
   
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
   
    public function rencanapemeliharaan($tahun){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ);
	  $sql="select i_inv_ajuanperbaikan,d_inv_ajuanperbaikan,i_orgb,
		       d_anggaran,c_barang,ur_sskel,e_keterangan
			   from aset.v_rencanapemeliharaan
			   where d_anggaran='".$tahun."'
			   and trim(c_inv_statajuanperbaikan)='A'";  
			  // echo $sql;
	  $result = $db->fetchAll($sql);
	  $jmlResult = count($result);
		 
				 for ($j = 0; $j < $jmlResult; $j++) {
				  			  
		           $hasilAkhir[$j] = array("i_inv_ajuanperbaikan"           =>(string)$result[$j]->i_inv_ajuanperbaikan,
				                           "d_inv_ajuanperbaikan"           =>(string)$result[$j]->d_inv_ajuanperbaikan,
				                           "i_orgb"           =>(string)$result[$j]->i_orgb,
				                           "d_anggaran"           =>(string)$result[$j]->d_anggaran,
				                           "c_barang"           =>(string)$result[$j]->c_barang,
				                           "ur_sskel"           =>(string)$result[$j]->ur_sskel,
				                           "e_keterangan"           =>(string)$result[$j]->e_keterangan
										   );
				 }
      $db->closeConnection();	  
	  return $hasilAkhir;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	  $db->closeConnection();
	  return "Data tidak ada";
	 }
	}
    public function realisasipemeliharaan($tahun){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ);
	  $sql="select i_inv_ajuanperbaikan,d_inv_ajuanperbaikan,i_orgb,
		       d_anggaran,c_barang,ur_sskel,e_keterangan,v_inv_biayaperbaikan
			   from aset.v_realisasipemeliharaan
			   where d_anggaran='".$tahun."'
			   and trim(c_inv_statajuanperbaikan)='B'
			   and trim(c_setuju_statustu)='Y'
			   and trim(c_setuju_statuskabag)='Y'
			   and trim(c_barang_serah)='Y'
			   and trim(c_inv_perbaikan)='Y'";  
			  // echo $sql;
	  $result = $db->fetchAll($sql);
	  $jmlResult = count($result);
		 
				 for ($j = 0; $j < $jmlResult; $j++) {
				  			  
		           $hasilAkhir[$j] = array("i_inv_ajuanperbaikan"           =>(string)$result[$j]->i_inv_ajuanperbaikan,
				                           "d_inv_ajuanperbaikan"           =>(string)$result[$j]->d_inv_ajuanperbaikan,
				                           "i_orgb"           =>(string)$result[$j]->i_orgb,
				                           "d_anggaran"           =>(string)$result[$j]->d_anggaran,
				                           "c_barang"           =>(string)$result[$j]->c_barang,
				                           "ur_sskel"           =>(string)$result[$j]->ur_sskel,
				                           "e_keterangan"           =>(string)$result[$j]->e_keterangan,
				                           "v_inv_biayaperbaikan"           =>(string)$result[$j]->v_inv_biayaperbaikan
										   );
				 }
      $db->closeConnection();	  
	  return $hasilAkhir;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	  $db->closeConnection();
	  return "Data tidak ada";
	 }
	}

}	
?>