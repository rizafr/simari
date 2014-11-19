<?php
class Sdm_Absensihitung_Service {
    private static $instance;

    private function __construct() {
       //echo 'I am constructed';
    }

    public static function getInstance() {
       if (!isset(self::$instance)) {
           $c = __CLASS__;
           self::$instance = new $c;
       }
       return self::$instance;
    }

    public function getIP() 
	{
       if( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '' ) 
	   { 
          $ipStrings = explode( ',',$_SERVER['HTTP_X_FORWARDED_FOR']); 
          foreach($ipStrings as $k => $v) 
		  { 
             if( empty($v) ) unset( $ipStrings[$k] ); 
             else if (!isset($ipString)) $ipString = $v1; 
          } 
        } 
        if( isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '' ) 
		{ $ipStrings[] = $_SERVER['REMOTE_ADDR']; } 
        return $ipStrings[0];
    } 
    public function getNamaPegawai($nip)
    {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
          $sql = "select n_peg from sdm.tm_pegawai where i_peg_nip='".$nip."' or i_peg_nip_new='".$nip."'";
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchOne($sql);	
	        return $result;
	   } catch (Exception $e) {
            echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
    }	
    public function getDataJamAbsensiMesin($nip,$tgl)
    {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   $sql = "Select d_peg_jam from absensi.e_sdm_absensi_mesin_ts ".
          " where i_peg_nip='".$nip."' and to_char(d_tgl_kerja,'yyyy-mm-dd')='".$tgl."' ".
          " order by d_peg_jam ";
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll($sql);	
               $jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++) {
			$hasilAkhir[$j] = array("d_peg_jam" =>(string)$result[$j]->d_peg_jam);
		 }			
		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
    }	
    public function getHarikerjalst($bulan,$tahun)
    {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   $sql = "Select d_tgl_kerja,d_jamkerja_mulai,d_jamkerja_selesai from sdm.e_sdm_abs_harikerja_tm ".
          " where to_char(d_tgl_kerja,'yyyy-mm')='".$tahun."-".$bulan."' ".
          " order by d_tgl_kerja,d_jamkerja_mulai";
//echo "sql : ".$sql;
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll($sql);	
               $jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++) {
			$hasilAkhir[$j] = array("d_tgl_kerja"  	       =>(string)$result[$j]->d_tgl_kerja,
						   "d_jamkerja_mulai"  	=>(string)$result[$j]->d_jamkerja_mulai,
						   "d_jamkerja_selesai"  	=>(string)$result[$j]->d_jamkerja_selesai);
		 }			
		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
    }	
 	public function getDataAbsenPegawai($where, $order) 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{
			$sql = "select c_terminal,i_peg_nip,i_peg_nipfg,n_peg,c_jabatan,c_status_kepegawaian,c_lokasi_unitkerja,c_eselon_i,c_eselon_ii,
			c_eselon_iii,c_eselon_iv,c_eselon_v from sdm.v_trabsensimesin ";
			if ($where != '') { $sql .= " where ".$where; }
			if ($order != '') { $sql .= " order by ".$order; }  
                     //echo "sql : ".$sql;
			$db->setFetchMode(Zend_Db::FETCH_OBJ);			
			$result = $db->fetchAll($sql); 
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) 
			{ 
			   $data[$j] = array("c_terminal" => (string)$result[$j]->c_terminal,
                                          "i_peg_nip" => (string)$result[$j]->i_peg_nip,
				              "i_peg_nipfg" => (string)$result[$j]->i_peg_nipfg,
						"n_peg" => (string)$result[$j]->n_peg,
						"c_jabatan" => (string)$result[$j]->c_jabatan,
						"c_status_kepegawaian" => (string)$result[$j]->c_status_kepegawaian);
			}
			return $data;
		} catch (Exception $e) 
		{
		    echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	public function insertAbsensi(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $absensi_prm = array("i_peg_nip"  		=>$data['i_peg_nip'],
	                          "i_peg_nip_new"  		=>$data['i_peg_nip_new'],
	                          "d_tgl"  		=>$data['d_tgl'],
				     "i_jamin"       =>$data['i_jamin'],
				     "i_jamout"	=>$data['i_jamout'],
				     "i_status"		=>$data['i_status']);
	     $db->insert('sdm.tm_absensi',$absensi_prm);
		 $db->commit();
		 $_hasil = array("rcNumber"=>"1",
		                 "rcDesc"  =>"Proses Sukses");
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
   public function getAbsnMasukPegawai($terminal,$nip,$tgl)
   {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   $sql = "select min(d_peg_jam) from absensi.e_sdm_absensi_mesin_ts where c_terminal='".$terminal."' and i_peg_nip='".
	   $nip."' and to_char(d_peg_absensi,'yyyy-mm-dd')='".$tgl."'";   // and c_status_absn='Verify OK'";
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $db->fetchOne($sql);	
	       return $result;
	   } catch (Exception $e) {
	     return '00:00:00';
	   }
   }
   public function getAbsnPulangPegawai($terminal,$nip,$tgl)
   {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   $sql = "select max(d_peg_jam) from absensi.e_sdm_absensi_mesin_ts where c_terminal='".$terminal."' and i_peg_nip='".
	   $nip."' and to_char(d_peg_absensi,'yyyy-mm-dd')='".$tgl."'";   // and c_status_absn='Verify OK'";
	   //echo $sql."pulang<br>";
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $db->fetchOne($sql);	
		 //echo "TEST JML".$jmlResult;
	     return $result;
	   } catch (Exception $e) {
	     return '00:00:00';
	   }
   }

}
?>