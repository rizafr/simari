<?php
class Sdm_Absenfingger_Service {
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
	

	public function maintainDataAbsen(array $data,$par) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();

	     $maintain_data = array("i_peg_nipfg"=>$data['i_peg_nipfg'],								
					"c_terminal"=>$data['c_terminal'],
					"i_peg_nipfg"=>$data['i_peg_nipfg'],
					"i_peg_nip"=>$data['i_peg_nip'],
					"i_entry"=>$data['i_entry'],
					"d_entry"=>$data['d_entry']);
		if ($par=='insert'){$db->insert('sdm.tr_absensi_finger',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tr_absensi_finger',$maintain_data, "i_peg_nip = '".trim($data['i_peg_nip'])."'");}	 
		
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

 	public function cekAbsen($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{		
				
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT i_peg_nipfg,i_peg_nip,c_terminal FROM sdm.tr_absensi_finger where 1=1 $cari");									
					$jmlResult = count($result);									
					return $jmlResult;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}	
}
?>