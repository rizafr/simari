<?php
class Sdm_Absensimesin_Service {
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
	public function insertAbsenMsn(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $absensi_prm = array("i_peg_nip"  			=>$data['i_peg_nip'],
	                          "i_peg_nip_new"  		=>$data['i_peg_nip_new'],
	                          "d_tgl"  		=>$data['d_tgl'],
	                          "i_jamin"  		=>$data['i_jamin'],
				              "i_jamout"              =>$data['i_jamout'],
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
    public function getAbsensipeglst($where)
    {
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 $sql = "select i_peg_nip,i_peg_nipfg,n_peg,c_lokasi_unitkerja,c_eselon_i,c_eselon_ii,c_eselon_iii,c_eselon_iv,c_eselon_v,
               c_terminal,d_peg_absensi,d_peg_jam,c_absensi_peg,i_no_absensi,c_status_absn,i_term 	
               from sdm.v_abspegmesin";
	 if ($where!= '') { $sql .= " where ".$where; }
	 try {
            $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchAll($sql);	
            $jmlResult = count($result);
	     for ($j = 0; $j < $jmlResult; $j++) {
	         $hasilAkhir[$j] = array("i_peg_nip" => (string)$result[$j]->i_peg_nip,
                                        "i_peg_nipfg" => (string)$result[$j]->i_peg_nipfg,
                                        "n_peg" => (string)$result[$j]->n_peg,
                                        "c_lokasi_unitkerja" => (string)$result[$j]->c_lokasi_unitkerja,
                                        "c_eselon_i" => (string)$result[$j]->c_eselon_i,
                                        "c_eselon_ii" => (string)$result[$j]->c_eselon_ii,
                                        "c_eselon_iii" => (string)$result[$j]->c_eselon_iii,
                                        "c_eselon_iv" => (string)$result[$j]->c_eselon_iv,
                                        "c_eselon_v" => (string)$result[$j]->c_eselon_v,
                                        "c_terminal" => (string)$result[$j]->c_terminal,
                                        "d_peg_absensi" => (string)$result[$j]->d_peg_absensi,
                                        "d_peg_jam" => (string)$result[$j]->d_peg_jam,
                                        "c_absensi_peg" => (string)$result[$j]->c_absensi_peg,
                                        "i_no_absensi" => (string)$result[$j]->i_no_absensi,
                                        "c_status_absn" => (string)$result[$j]->c_status_absn,
                                        "i_term" => (string)$result[$j]->i_term);
	     }			
	     return $hasilAkhir;
	 } catch (Exception $e) {
          echo $e->getMessage().'<br>';
	   return 'gagal <br>';
	 }
    }
    public function getJmlIjinV($nip, $periode)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$sql = "select count(distinct(b.d_peg_absensi)) from sdm.tr_absensi_finger a, absensi.e_sdm_absensi_mesin_ts b
          where a.c_terminal=b.c_terminal and a.i_peg_nipfg=b.i_peg_nip 
          and a.i_peg_nip='".$nip."'
          and to_char(b.d_peg_absensi,'yyyy-mm') = '".$periode."'
          and b.d_peg_jam between '04:00' and '08:00'";
		//echo $sql;
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ);			
			$result = $db->fetchOne($sql);
			return $result;
		} catch (Exception $e) 
		{
		    echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
    public function getJmlIjin($nip, $periode, $kodeijin)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$sql = "select count(i_peg_nip) from sdm.tm_absensi_ijin 
          where i_peg_nip='".$nip."'
          and to_char(d_peg_ijin,'yyyy-mm') = '".$periode."'
          and c_ijin='".$kodeijin."'
		  and i_no_surat_ijin<>''";
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ);			
			$result = $db->fetchOne($sql);
			return $result;
		} catch (Exception $e) 
		{
		    echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
    public function getJmlTerlambat($nip, $periode)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$sql = "select count(distinct(b.d_peg_absensi)) from sdm.tr_absensi_finger a, absensi.e_sdm_absensi_mesin_ts b
          where a.c_terminal=b.c_terminal and a.i_peg_nipfg=b.i_peg_nip 
          and a.i_peg_nip='".$nip."'
          and to_char(b.d_peg_absensi,'yyyy-mm') = '".$periode."'
          and b.d_peg_jam between '08:01' and '20:00'";
		//echo $sql;
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ);			
			$result = $db->fetchOne($sql);
			return $result;
		} catch (Exception $e) 
		{
		    echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
    public function getJmlCepatPlg($nip, $periode)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$sql = "select count(distinct(b.d_peg_absensi)) from sdm.tr_absensi_finger a, absensi.e_sdm_absensi_mesin_ts b
          where a.c_terminal=b.c_terminal and a.i_peg_nipfg=b.i_peg_nip 
          and a.i_peg_nip='".$nip."'
          and to_char(b.d_peg_absensi,'yyyy-mm') = '".$periode."'
          and b.d_peg_jam between '08:01' and '15:59'";
		//echo $sql;
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ);			
			$result = $db->fetchOne($sql);
			return $result;
		} catch (Exception $e) 
		{
		    echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
    public function getJmlMasukKerja($nip, $periode)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$sql = "select count(distinct(b.d_peg_absensi)) from sdm.tr_absensi_finger a, absensi.e_sdm_absensi_mesin_ts b
          where a.c_terminal=b.c_terminal and a.i_peg_nipfg=b.i_peg_nip 
          and a.i_peg_nip='".$nip."'
          and to_char(b.d_peg_absensi,'yyyy-mm') = '".$periode."'";
		//echo $sql;
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ);			
			$result = $db->fetchOne($sql);
			return $result;
		} catch (Exception $e) 
		{
		    echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
    public function getHariKerjalst($periode)
    {
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	$sql = "select d_tgl_kerja,d_jamkerja_mulai,d_jamkerja_selesai,d_jamistrht_mulai,d_jamistrht_selesai from sdm.e_sdm_abs_harikerja_tm
       where to_char(d_tgl_kerja,'yyyy-mm') = '".$periode."'";
		//echo $sql;
	try 
	{
	     $db->setFetchMode(Zend_Db::FETCH_OBJ);			
	     $result = $db->fetchAll($sql);
            $jmlResult = count($result);
	     for ($j = 0; $j < $jmlResult; $j++) {
	         $hasilAkhir[$j] = array("d_tgl_kerja" => (string)$result[$j]->d_tgl_kerja,
                                        "d_jamkerja_mulai" => (string)$result[$j]->d_jamkerja_mulai,
                                        "d_jamkerja_selesai" => (string)$result[$j]->d_jamkerja_selesai,
                                        "d_jamistrht_mulai" => (string)$result[$j]->d_jamistrht_mulai,
                                        "d_jamistrht_selesai" => (string)$result[$j]->d_jamistrht_selesai);
	     }			
	     return $hasilAkhir;
	} catch (Exception $e) 
	{
	   echo $e->getMessage().'<br>';
	   return 'Data tidak ada <br>';
	}
    }
    public function getJmlHariKerja($periode)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$sql = "select count(d_tgl_kerja) from sdm.e_sdm_abs_harikerja_tm
          where to_char(d_tgl_kerja,'yyyy-mm') = '".$periode."'";
		//echo $sql;
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ);			
			$result = $db->fetchOne($sql);
			return $result;
		} catch (Exception $e) 
		{
		    echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
    public function getJmlSuratIjin($nip, $periode)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$sql = "select count(i_peg_nip) from sdm.tm_absensi_ijin 
          where i_peg_nip='".$nip."'
          and to_char(d_peg_ijin,'yyyy-mm') = '".$periode."'
		  and i_no_surat_ijin<>''";
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ);			
			$result = $db->fetchOne($sql);
			return $result;
		} catch (Exception $e) 
		{
		    echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}

//disini
    public function getJmlDataIjin($nip, $periode1, $periode2, $kdijin)
    {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   $sql = "select count(c_ijin) from sdm.tm_absensi_ijin where i_peg_nip='".$nip
          ."' and (to_char(d_peg_ijin,'yyyy-mm') between '".$periode1."' and '".$periode2."') and c_ijin='".$kdijin."'";
	   //echo $sql;
	   try 
	   {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);			
		$result = $db->fetchOne($sql);
		return $result;
	   } catch (Exception $e) 
	   {
		echo $e->getMessage().'<br>';
		return 'Data tidak ada <br>';
	   }
    }
    public function getJmlSuratIjin1($nip, $periode, $periode2, $kodeijin)
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   $sql = "select count(i_peg_nip) from sdm.tm_absensi_ijin 
          where i_peg_nip='".$nip."'
          and (to_char(d_peg_ijin,'yyyy-mm') between '".$periode1."' and '".$periode2."')
          and c_ijin='".$kodeijin."'
	   and i_no_surat_ijin<>''";
	   try 
	   {
	      $db->setFetchMode(Zend_Db::FETCH_OBJ);			
	      $result = $db->fetchOne($sql);
	      return $result;
	   } catch (Exception $e) 
	   {
		echo $e->getMessage().'<br>';
		return 'Data tidak ada <br>';
	   }
	}

    public function getKodeIjinLst()
    {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   $sql = "select c_ijin, n_ijin from sdm.tr_kode_ijin";
	   try 
	   {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);			
		$result = $db->fetchAll($sql);
		$jmlResult = count($result);
		for ($j = 0; $j < $jmlResult; $j++) 
		{ 
		   $data[$j] = array("c_ijin"  			=>(string)$result[$j]->c_ijin,
	                           "n_ijin"  		    =>(string)$result[$j]->n_ijin);
		}
		return $data;
	   } catch (Exception $e) 
	   {
		echo $e->getMessage().'<br>';
		return 'Data tidak ada <br>';
	   }
    }
//batas perbaikan

	public function insertIjin(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $absensi_prm = array("i_peg_nip"  			=>$data['i_peg_nip'],
	                          "d_peg_ijin"  		=>$data['d_peg_ijin'],
	                          "d_jam_mulai"  		=>$data['d_jam_mulai'],
	                          "d_jam_selesai"  		=>$data['d_jam_selesai'],
				      "c_ijin"              =>$data['c_ijin'],
				      "i_no_surat_ijin"		=>$data['i_no_surat_ijin'],
				      "i_entry"       		=>$data['i_entry'],
				      "d_entry"       		=>date("Y-m-d"));
	     $db->insert('sdm.tm_absensi_ijin',$absensi_prm);
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
	public function foundIjin($nip,$tgl) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
          $sql = "Select count(c_ijin) from sdm.tm_absensi_ijin where i_peg_nip='".$nip."' and to_char(d_peg_ijin,'yyyy-mm-dd')='".$tgl."'";
	   try 
          {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);			
		$result = $db->fetchOne($sql);
              if ($result>0) { $hasil = 1; }
              else { $hasil = 0; }

	       return $hasil;
	   } catch (Exception $e) {
            $db->rollBack();
           //echo $e->getMessage().'<br>';
	     return 0;
	   }
	}
	public function delIjin($nip,$tgl) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try 
          {
	      $db->beginTransaction();
             $where[] = "i_peg_nip='".$nip."'";
             $where[] = " to_char(d_peg_ijin,'yyyy-mm-dd')='".$tgl."'";
	      $db->delete('sdm.tm_absensi_ijin', $where); 
	      $db->commit();
	   } catch (Exception $e) {
            $db->rollBack();
	     return 0;
	   }
	}
	public function delIjinGroup($nip,$thnbln) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try 
          {
	      $db->beginTransaction();
             $where[] = "i_peg_nip='".$nip."'";
             $where[] = " to_char(d_peg_ijin,'yyyy-mm')='".$thnbln."'";
             $where[] = "i_no_surat_ijin=''";
	      $db->delete('sdm.tm_absensi_ijin', $where); 
	      $db->commit();
	   } catch (Exception $e) {
            $db->rollBack();
	     return 0;
	   }
	}
	public function updateIjin(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $absensi_prm = array("i_peg_nip"  			=>$data['i_peg_nip'],
	                          "d_peg_ijin"  		=>$data['d_peg_ijin'],
	                          "d_jam_mulai"  		=>$data['d_jam_mulai'],
	                          "d_jam_selesai"  		=>$data['d_jam_selesai'],
				      "c_ijin"              =>$data['c_ijin'],
				      "i_no_surat_ijin"		=>$data['i_no_surat_ijin'],
				      "i_entry"       		=>$data['i_entry'],
				      "d_entry"       		=>date("Y-m-d"));
							 // var_dump($absensi_prm);
		 $where[] = "i_peg_nip = '".$data['i_peg_nip']."'";
		 $where[] = "to_char(d_peg_ijin,'yyyy-mm-dd') = '".$data['d_peg_ijin']."'";
		 //$where[] = "d_jam_mulai = '".$data['d_jam_mulai']."'";
		 
							  var_dump($where);
	     $db->update('sdm.tm_absensi_ijin', $absensi_prm, $where);
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
	public function getdataIjin($where) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$sql = "select i_peg_nip,d_peg_ijin,d_jam_mulai,d_jam_selesai,c_ijin,i_no_surat_ijin,i_entry,d_entry from sdm.tm_absensi_ijin ";
        if ($where != '') { $sql .= " where ".$where; }
		//echo $sql;
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ);			
			$result = $db->fetchAll($sql);
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) 
			{ 
				$data[0] = array("i_peg_nip"  			=>(string)$result[0]->i_peg_nip,
	                             "d_peg_ijin"  		    =>(string)$result[0]->d_peg_ijin,
	                             "d_jam_mulai"  		=>(string)$result[0]->d_jam_mulai,
	                             "d_jam_selesai"  		=>(string)$result[0]->d_jam_selesai,
				                 "c_ijin"              =>(string)$result[0]->c_ijin,
				                 "i_no_surat_ijin"		=>(string)$result[0]->i_no_surat_ijin,
				                 "i_entry"       		=>(string)$result[0]->i_entry);
			}
			return $data;
		} catch (Exception $e) 
		{
		    echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
	public function getTerminallist($where) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$sql = "select distinct(c_terminal) as c_terminal from absensi.e_sdm_absensi_mesin_ts ";
        if ($where != '') { $sql .= " where ".$where; }
		//echo $sql;
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ);			
			$result = $db->fetchAll($sql);
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) 
			{ 
				$data[$j] = array("c_terminal" =>(string)$result[$j]->c_terminal);
			}
			return $data;
		} catch (Exception $e) 
		{
		    echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}
       public function delAbsensiFinger($server,$nip,$tgl,$jam)
       {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{
	          $db->beginTransaction();
                 $where[] = "c_terminal='".$server."'";
                 $where[] = "i_peg_nip='".$nip."'";
                 $where[] = "to_char(d_peg_absensi,'yyyy-mm-dd')='".$tgl."'";
                 $where[] = "d_peg_jam='".$jam."'";
		   $db->delete('absensi.e_sdm_absensi_mesin_ts', $where); 
		   $db->commit();
		} catch (Exception $e) 
		{
		    echo $e->getMessage().'<br>';
			return 'Data tidak ada';
		}
       }
       public function getNipInFinger($finger)
       {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$sql = "select i_peg_nip from sdm.tr_absensi_finger where i_peg_nipfg='".$finger."'";
		//echo $sql;
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ);			
			$result = $db->fetchOne($sql);
			return $result;
		} catch (Exception $e) 
		{
		    echo $e->getMessage().'<br>';
			return 'Data tidak ada';
		}
       }
       public function getFingerInNip($i_peg_nip)
       {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$sql = "select i_peg_nipfg from sdm.tr_absensi_finger where i_peg_nip='".$i_peg_nip."'";
		//echo $sql;
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ);			
			$result = $db->fetchOne($sql);
			return $result;
		} catch (Exception $e) 
		{
		    echo $e->getMessage().'<br>';
			return 'Data tidak ada';
		}
       }
 	public function getDataAbsensimsnList($where, $currentPage, $numToDisplay, $order) 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{
			if(($currentPage==0) && ($numToDisplay==0))
			{
				$sql = "select count(c_terminal) from  absensi.e_sdm_absensi_mesin_ts ";
                if ($where != '') { $sql .= " where ".$where; }
				$data = $db->fetchOne($sql); 
			}
			else		
			{	
			      //$sql = "select c_terminal,fingernip,i_peg_nip,d_peg_absensi,d_peg_jam,c_absensi_peg,i_no_absensi,c_status_absn,i_term from sdm.v_absensinip ";
			    $sql = "select c_terminal,i_peg_nip,d_peg_absensi,d_peg_jam,c_absensi_peg,i_no_absensi,c_status_absn,i_term from absensi.e_sdm_absensi_mesin_ts ";
				if ($where != '') { $sql .= " where ".$where; }
				if ($order != '') { $sql .= " order by ".$order; }
				//echo $sql;
				$xLimit=$numToDisplay;
				$xOffset=($currentPage-1)*$numToDisplay;				
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll($sql . " limit $xLimit offset $xOffset");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
						$data[$j] = array("c_terminal" => (string)$result[$j]->c_terminal,
						                  "i_peg_nip" => (string)$result[$j]->i_peg_nip,
										  "d_peg_absensi" => (string)$result[$j]->d_peg_absensi,
										  "d_peg_jam" => (string)$result[$j]->d_peg_jam,
										  "c_absensi_peg" => (string)$result[$j]->c_absensi_peg,
										  "i_no_absensi" => (string)$result[$j]->i_no_absensi,
										  "c_status_absn" => (string)$result[$j]->c_status_absn,
										  "i_term" => (string)$result[$j]->i_term);
					}
			}							
			return $data;
		} catch (Exception $e) 
		{
		    echo $e->getMessage().'<br>';
			return 'Data tidak ada <br>';
		}
	}

}
?>