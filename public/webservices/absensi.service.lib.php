<?
include "condb.php";

class absensiservice{
	private static $instance;
   
    // The singleton method
    public static function getInstance() {
       if (!isset(self::$instance)) {
           $c = __CLASS__;
           self::$instance = new $c;
       }

       return self::$instance;
    }
	
	public function foundAbsensiMesin($nip,$tgl,$jam)
	{
	   $output = false;
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('absendb');
       try 
       {
         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         $rsSelect = $db->fetchOne("select i_term from e_sdm_absensi_mesin_ts ".
         " where i_peg_nip='".$nip."' and to_char(d_peg_absensi,'yyyyMMdd')='".$tgl."' and d_peg_jam='".
		 $jam."'");
		 if ($rsSelect!="") $output = true;
		 else $output = false;
	     return $output;
       } 
       catch (Exception $e) 
       {
         $errorE = $e->getMessage();
	     return 'gagal '.$errorE;
       }
	}
	public function insAbsensiMesin(array $data)
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('absendb');
	   try 
	   {
	      $db->beginTransaction();
	      $absensi_prm = array(
          "i_peg_nip" => $data['i_peg_nip'],
          "d_peg_absensi" => $data['d_peg_absensi'],
          "d_peg_jam" => $data['d_peg_jam'],
          "c_absensi_peg" => $data['c_absensi_peg'],
          "c_menejmen" => $data['c_menejmen'],
          "i_term" => $data['i_term'],
          "i_entry" => $data['i_entry'],
          "d_entry" => date("Y-m-d"));
	      $db->insert('e_sdm_absensi_mesin_ts',$absensi_prm);
		  $db->commit();
	      return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage();
	     return 'gagal';
	   }
    }
	public function updAbsensiMesin(array $data)
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('absendb');
	   try 
	   {
	      $db->beginTransaction();
	      $absensi_prm = array(
          "i_peg_nip" => $data['i_peg_nip'],
          "d_peg_absensi" => $data['d_peg_absensi'],
          "d_peg_jam" => $data['d_peg_jam'],
          "c_absensi_peg" => $data['c_absensi_peg'],
          "c_menejmen" => $data['c_menejmen'],
          "i_term" => $data['i_term'],
          "i_entry" => $data['i_entry'],
          "d_entry" => date("Y-m-d"));
		  $where[] = "i_peg_nip = '".$data['i_peg_nip']."'";
	      $where[] = "to_char(d_peg_absensi,'dd-MM-yyyy') = '".$data['d_peg_absensi']."'";
		  $where[] = "d_peg_jam = '".$data['d_peg_jam']."'";
	      $db->update('e_sdm_absensi_mesin_ts', $absensi_prm, $where);
		  $db->commit();
	      return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
    }
}

?>
