<?php
class Sdm_Absensi_Service {
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
	
	public function getAbsensi($nip) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $db->fetchAll("SELECT * FROM e_sdm_absensi_0_tm				  
				  WHERE i_peg_nip = ? order by d_peg_absensi",$nip);	
		
         $jmlResult = count($result);
		 //echo "TEST JML".$jmlResult;
		 for ($j = 0; $j < $jmlResult; $j++) {
			$nmAbsensi = $db->fetchCol('SELECT n_absensi FROM e_sdm_absensi_0_tr WHERE c_absensi = ?',$result[$j]->c_absensi);
			$hasilAkhir[$j] = array("i_peg_nip"  	=>(string)$result[$j]->i_peg_nip,
								   "tglAbsen"  		=>(string)$result[$j]->d_peg_absensi,
							       "kdAbsen"  		=>(string)$result[$j]->c_absensi,
							       "nmAbsen"  		=>(string)$nmAbsensi[0],
								   "jamMasuk"  		=>(string)$result[$j]->d_peg_jammasuk,
								   "jamKeluar"  	=>(string)$result[$j]->d_peg_jamkeluar,
								   "keterangan"  	=>(string)$result[$j]->e_peg_absensi);
		 }			
		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

//bamris
   public function getAbsensiPegawaiList($pageNumber, $itemPerPage)
   { 
      $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {
         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         if (($pageNumber==0) && ($itemPerPage==0))
         {
            $hasilAkhir = $db->fetchOne("select count(a.i_peg_nip) 
	                          FROM e_sdm_absensi_mesin_ts a, e_sdm_pegawai_0_tm b where a.i_peg_nip=b.i_peg_nip");
         }
         else
         {
            $maks=($pageNumber-1)*$itemPerPage;
	        $result = $db->fetchAll("SELECT a.i_peg_nip,b.n_peg,a.d_peg_absensi,a.d_peg_jam,a.c_absensi_peg 
	                       FROM e_sdm_absensi_mesin_ts a, e_sdm_pegawai_0_tm b ".
						   " where a.i_peg_nip=b.i_peg_nip order by i_peg_nip,d_peg_absensi,c_absensi_peg ".
						   " limit $itemPerPage offset $maks ");
	        $jmlResult = count($result);
	        for ($j = 0; $j < $jmlResult; $j++) 
	        {
               $hasilAkhir[$j] = array("i_peg_nip"  =>(string)$result[$j]->i_peg_nip,
                                       "n_peg"      =>(string)$result[$j]->n_peg,
                                       "d_peg_absensi"  =>(string)$result[$j]->d_peg_absensi,
                                       "d_peg_jam"      =>(string)$result[$j]->d_peg_jam,
									   "c_absensi_peg"  =>(string)$result[$j]->c_absensi_peg);
	        }					 
         }
	     return $hasilAkhir;
      } 
      catch (Exception $e) 
      {
         $errorE = $e->getMessage().'<br>';
	     return $errorE.'gagal <br>';
      }
   }	
    public function getJmlAbsensi($tgl, $nipe)
	{
          
          $nip = $this->getNipBaruLama($nipe);
          /*
          echo "nip : ".$nip."<br>";
          echo "nipe : ".$nipe."<br>";
          */
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try 
	   {
		  $db->setFetchMode(Zend_Db::FETCH_OBJ);
		  $result = $db->fetchAll("SELECT i_peg_nip,d_peg_absensi,d_peg_jam,c_absensi_peg,c_menejmen,i_term 
				  FROM e_sdm_absensi_mesin_ts where i_peg_nip='".$nip."' and to_char(d_peg_absensi,'yyyy-MM-dd')='".
				  $tgl."' order by c_absensi_peg,d_peg_jam");
				  
          $jmlResult = count($result);
		  for ($j = 0; $j < $jmlResult; $j++)
		  {
			$hasilBaca[$j] = array("i_peg_nip"  	=>(string)$result[$j]->i_peg_nip,
							       "d_peg_absensi"  	=>(string)$result[$j]->d_peg_absensi,
							       "d_peg_jam"  	=>(string)$result[$j]->d_peg_jam,
							       "c_absensi_peg"  	=>(string)$result[$j]->c_absensi_peg,
							       "c_menejmen"  	=>(string)$result[$j]->c_menejmen,
							       "i_term"  	=>(string)$result[$j]->i_term);
								   
		  }
	      return $hasilBaca;
	   } 
	   catch (Exception $e) 
	   {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	
	
	public function getAbsensiByPeriode($nip, $tgl1, $tgl2) {
	//echo "masuk getAbsensiByPeriode"."<br>";
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');

	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$where[] = $nip;
		$where[] = $tgl1;
		$where[] = $tgl2;

		$result = $db->fetchAll("select * from e_sdm_absensi_0_tm
								where  i_peg_nip = ?  and d_peg_absensi between ? and ? 
								order by i_peg_nip ",$where);
	   $jmlResult = count($result);
		//echo "JMLResult".$jmlResult."<br>";
		 for ($j = 0; $j < $jmlResult; $j++) {
			$nmAbsensi = $db->fetchCol('SELECT n_absensi FROM e_sdm_absensi_0_tr WHERE c_absensi = ?',$result[$j]->c_absensi);
  			$hasilAkhir[$j] = array("i_peg_nip"  		=>(string)$result[$j]->i_peg_nip,
									"tglAbsen"			=>(string)$result[$j]->d_peg_absensi,
									"kdAbsen"			=>(string)$result[$j]->c_absensi,
									"nmAbsen"  			=>(string)$nmAbsensi[0],
									"jamMasuk"			=>(string)$result[$j]->d_peg_jammasuk,
									"jamKeluar"			=>(string)$result[$j]->d_peg_jamkeluar,
									"keterangan"		=>(string)$result[$j]->e_peg_absensi);
		 }
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	
	public function insertAbsensi(array $data) {
	//echo "masuk insertMutasi";
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   //$user = '930554';
	   //echo "user= ".$data['user'];
	   try {
	     $db->beginTransaction();
	     $absensi_prm = array("i_peg_nip"  					=>$data['nip'],
	                          "d_peg_absensi"  		=>$data['tanggalAbsen'],
	                        //"c_peg_absensi"  		=>$data['jenisAbsen'],
	                          "c_absensi"  			=>$data['jenisAbsen'],
				     "d_peg_jammasuk"         =>$data['jamMasuk'],
				     "d_peg_jamkeluar"		=>$data['jamKeluar'],
				     "e_peg_absensi"			=>$data['keterangan'],
				     "i_entry"       			=>$data['user'],
				     "d_entry"       			=>date("Y-m-d"));
	     $db->insert('e_sdm_absensi_0_tm',$absensi_prm);
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

	public function getAbsensiByKey($nip, $tglAbsensi) {
	//echo "masuk getSetujuMutasiByKey"."<br>";
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');

	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$where[] = $nip;
		$where[] = $tglAbsensi;

		$result = $db->fetchAll("select * from e_sdm_absensi_0_tm
								where  i_peg_nip = ?  and d_peg_absensi = ? 
								order by i_peg_nip ",$where);
	   $jmlResult = count($result);
		//echo "JMLResult".$jmlResult."<br>";
		 for ($j = 0; $j < $jmlResult; $j++) {
 			$nmAbsensi = $db->fetchCol('SELECT n_absensi FROM e_sdm_absensi_0_tr WHERE c_absensi = ?',$result[$j]->c_absensi);
 			$hasilAkhir = array("nip"  					=>(string)$result[$j]->i_peg_nip,
									"tglAbsen"			=>(string)$result[$j]->d_peg_absensi,
									"jenisAbsen"		=>(string)$result[$j]->c_absensi,
									"nmAbsen"  			=>(string)$nmAbsensi[0],
									"jamMasuk"			=>(string)$result[$j]->d_peg_jammasuk,
									"jamKeluar"			=>(string)$result[$j]->d_peg_jamkeluar,
									"keterangan"		=>(string)$result[$j]->e_peg_absensi);
		 }
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function updateAbsensi(array $data) {
	//echo "masuk updateAbsensi";
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $absensi_prm = array("i_peg_nip"  					=>$data['nip'],
	                               "d_peg_absensi"  		=>$data['tanggalAbsen'],
	                               //"c_peg_absensi"  		=>$data['jenisAbsen'],
	                               "c_absensi"  		=>$data['jenisAbsen'],
							       "d_peg_jammasuk"         =>$data['jamMasuk'],
								   "d_peg_jamkeluar"		=>$data['jamKeluar'],
								   "e_peg_absensi"			=>$data['keterangan'],
								   "i_entry"       			=>$data['user'],
								   "d_entry"       			=>date("Y-m-d"));
		 $where[] = "i_peg_nip = '".$data['nip']."'";
	     $where[] = "d_peg_absensi = '".$data['tglAbsenH']."'";
	     $db->update('e_sdm_absensi_0_tm', $absensi_prm, $where);
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

	public function deleteAbsensi(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 $where[] = "i_peg_nip = '".$data['nip']."'";
		 $where[] = "d_peg_absensi = '".$data['tglAbsen']."'";
	     $db->delete('e_sdm_absensi_0_tm', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function getKodeAbsenList() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll('SELECT c_absensi, n_absensi
				  FROM e_sdm_absensi_0_tr order by n_absensi ');
						 
         $jmlResult = count($result);
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
//author bamris
   public function getPegawaiListAbsn(array $data, $pageNumberq, $itemPerPageq)
   { 
      $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {
         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $idNIP = $data['i_peg_nip'];
	     $idNAMA = strtoupper($data['n_peg']);
		 $idOrder = $data['ord'];
         if (($pageNumber==0) && ($itemPerPage==0))
         {
            $hasilAkhir = $db->fetchOne("select count(a.i_peg_nip) 
	                          FROM e_sdm_pegawai_0_tm a left join e_sdm_peg_jabatanterakhir_vm b on a.i_peg_nip=b.i_peg_nip 
							  left join e_org_0_0_tm c on a.i_orgb=c.i_orgb
                           where a.i_peg_nip like '%".
						   $idNIP."%' and upper(a.n_peg) like '%".$idNAMA."%' ");
         }
         else
         {
					  $maks=($pageNumber-1)*$itemPerPage;
	        $result = $db->fetchAll("SELECT a.i_peg_nip as ai_peg_nip, a.n_peg as an_peg, b.n_jabatan as bn_jabatan,
			c.n_orgb as cn_orgb 
	                          FROM e_sdm_pegawai_0_tm a left join e_sdm_peg_jabatanterakhir_vm b on a.i_peg_nip=b.i_peg_nip 
							  left join e_org_0_0_tm c on a.i_orgb=c.i_orgb
                           where a.i_peg_nip like '%".
						   $idNIP."%' and upper(a.n_peg) like '%".$idNAMA."%' order by ".$idOrder." limit $itemPerPage offset $maks ");
	        $jmlResult = count($result);
	        for ($j = 0; $j < $jmlResult; $j++) 
	        {
               $hasilAkhir[$j] = array("i_peg_nip"  =>(string)$result[$j]->ai_peg_nip,
                                 "n_peg"      =>(string)$result[$j]->an_peg,
                                 "n_jabatan"      =>(string)$result[$j]->bn_jabatan,
                                 "n_orgb"      =>(string)$result[$j]->cn_orgb);
	        }					 
         }
	     return $hasilAkhir;
      } 
      catch (Exception $e) 
      {
         $errorE = $e->getMessage().'<br>';
	     return $errorE.'gagal <br>';
      }
   }
   public function getAbsensiListPP(array $data, $pageNumber, $itemPerPage)
   { 
      $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {
         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $idNIP = $data['i_peg_nip'];
	     $idNAMA = strtoupper($data['n_peg']);
		 $idORG = substr($data['c_unit_kerja'],0,3);
		 if (substr($idORG,0,2)=="SK") $idORG = substr($idORG,0,2);
		 $idOrder = $data['ord'];
         if (($pageNumber==0) && ($itemPerPage==0))
         {
            $hasilAkhir = $db->fetchOne("select count(i_peg_nip) 
	                          FROM e_sdm_pegawai_0_tm  
				     where i_peg_nip like '%".$idNIP."%' and upper(n_peg) like '%".
					 $idNAMA."%' and c_unit_kerja like '".$idORG."%' and c_peg_status='3PN' ");
         }
         else
         {  
            $maks=($pageNumber-1)*$itemPerPage;
	        $result = $db->fetchAll("SELECT i_peg_nip, n_peg FROM e_sdm_pegawai_0_tm 
                           where i_peg_nip like '%".$idNIP."%' and upper(n_peg) like '%".$idNAMA."%' 
						   and c_unit_kerja like '".$idORG."%' and c_peg_status='3PN' order by ".$idOrder.
						   " limit ".$itemPerPage." offset ".$maks);
	        $jmlResult = count($result);
	        for ($j = 0; $j < $jmlResult; $j++) 
	        {
               $hasilAkhir[$j] = array("i_peg_nip"  =>(string)$result[$j]->i_peg_nip,
                                 "n_peg"      =>(string)$result[$j]->n_peg);
	        }					 
         }
	     return $hasilAkhir;
      } 
      catch (Exception $e) 
      {
         $errorE = $e->getMessage().'<br>';
	     return $errorE.'gagal <br>';
      }
   }	
   public function getAbsensiListAll(array $data)
   { 
      $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {
         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $idNIP = $data['i_peg_nip'];
	     $idNAMA = strtoupper($data['n_peg']);
		 $idORG = substr($data['c_unit_kerja'],0,3);
		 if (substr($idORG,0,2)=="SK") $idORG = substr($idORG,0,2);
	        $result = $db->fetchAll("SELECT i_peg_nip, n_peg FROM e_sdm_pegawai_0_tm 
                           where i_peg_nip like '%".$idNIP."%' and upper(n_peg) like '%".$idNAMA."%' 
						   and c_unit_kerja like '".$idORG."%' and c_peg_status='3PN' order by n_peg ");
	        $jmlResult = count($result);
	        for ($j = 0; $j < $jmlResult; $j++) 
	        {
               $hasilAkhir[$j] = array("i_peg_nip"  =>(string)$result[$j]->i_peg_nip,
                                 "n_peg"      =>(string)$result[$j]->n_peg);
	        }					 
	     return $hasilAkhir;
      } 
      catch (Exception $e) 
      {
         $errorE = $e->getMessage().'<br>';
	     return $errorE.'gagal <br>';
      }
   }	
   public function getUnitKerjaListAll()
   {
      $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {
         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         $rsSelect = $db->fetchAll("select i_orgb,n_orgb FROM e_org_0_0_tm Where c_orgb_level='2'");
         $jmlResult = count($rsSelect);
	     for ($j = 0; $j < $jmlResult; $j++) 
	     {
            $hasilAkhir[$j] = array("i_orgb"  =>(string)$rsSelect[$j]->i_orgb,"n_orgb" =>(string)$rsSelect[$j]->n_orgb);
	     }					 
	     return $hasilAkhir;
      } 
      catch (Exception $e) 
      {
         $errorE = $e->getMessage().'<br>';
	     return $errorE.'gagal <br>';
      }
   }
   public function getUnitKerjaDesc($i_orgb)
   {
      $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {
         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         $rsSelect = $db->fetchOne("select n_orgb FROM e_org_0_0_tm Where i_orgb='".$i_orgb."'");
	     return $rsSelect;
      } 
      catch (Exception $e) 
      {
         $errorE = $e->getMessage().'<br>';
	     return $errorE.'gagal <br>';
      }
	  
   }
   public function getAbsensiDescListAll()
   {
      $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {
         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         $rsSelect = $db->fetchAll("select c_absensi,n_absensi FROM e_sdm_absensi_0_tr ");
         $jmlResult = count($rsSelect);
	     for ($j = 0; $j < $jmlResult; $j++) 
	     {
            $hasilAkhir[$j] = array("c_absensi"=>(string)$rsSelect[$j]->c_absensi,"n_absensi"=>(string)$rsSelect[$j]->n_absensi);
	     }					 
	     return $hasilAkhir;
      } 
      catch (Exception $e) 
      {
         $errorE = $e->getMessage().'<br>';
	     return $errorE.'gagal <br>';
      }
   }
   //disini
   public function hitungRekapAbsensiAlpa($nipe,$tglawal,$tglakhir)
   {
         $nip = $this->getNipBaruLama($nipe);
	  $btglawal = (string)$tglawal;
	  $btglakhir = (string)$tglakhir;
	  $tgS = substr($btglawal,6,2);
	  $blS = substr($btglawal,4,2);
	  $thS = substr($btglawal,0,4);
	  $tgF = substr($tglakhir,6,2);
	  $blF = substr($tglakhir,4,2);
	  $thF = substr($tglakhir,0,4);
	  if ($blS=="00") $blS = "01";
	  if ($blF=="00") $blF = "12";
	  if ($tgS=="00") $tgS = "01";
	  if ($tgF=="00") 
	  { 
	     $strBetween = " to_char(d_peg_absensi,'yyyymm') between '".$thS.$blS."' and '".$thF.$blF."' ";
	     $strBetween1 = " to_char(d_tgl_kerja,'yyyymm') between '".$thS.$blS."' and '".$thF.$blF."' ";
	  }
	  else
	  { 
	     $strBetween = " to_char(d_peg_absensi,'yyyymmdd') between '".$thS.$blS.$tgS."' and '".$thF.$blF.$tgF."' ";
	     $strBetween1 = " to_char(d_tgl_kerja,'yyyymmdd') between '".$thS.$blS.$tgS."' and '".$thF.$blF.$tgF."' ";
	  }
	  $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {
         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         $rsSelect = $db->fetchOne("select count(distinct(d_peg_absensi)) FROM e_sdm_absensi_mesin_ts Where i_peg_nip='".
		 trim($nip)."' and ".$strBetween); 
         $rsSelect1 = $db->fetchOne("select count(d_tgl_kerja) FROM e_sdm_abs_harikerja_tm Where ".$strBetween1); 
		 $output = (int)$rsSelect1*1 - (int)$rsSelect*1;
		 if ($output<0) $output=0;
	     return $output;
      } 
      catch (Exception $e) 
      {
         $errorE = $e->getMessage().'<br>';
	     return $errorE.'gagal <br>';
      }
   }
   public function getHariKerjaList($tglawal,$tglakhir)
   {
	  $btglawal = (string)$tglawal;
	  $btglakhir = (string)$tglakhir;
	  $tgS = substr($btglawal,6,2);
	  $blS = substr($btglawal,4,2);
	  $thS = substr($btglawal,0,4);
	  $tgF = substr($tglakhir,6,2);
	  $blF = substr($tglakhir,4,2);
	  $thF = substr($tglakhir,0,4);
	  if ($blS=="00") $blS = "01";
	  if ($blF=="00") $blF = "12";
	  if ($tgS=="00") $tgS = "01";
	  if ($tgF=="00") 
	  { 
	     $strBetween = " to_char(d_tgl_kerja,'yyyymm') between '".$thS.$blS."' and '".$thF.$blF."' ";
	  }
	  else
	  { 
	     $strBetween = " to_char(d_tgl_kerja,'yyyymmdd') between '".$thS.$blS.$tgS."' and '".$thF.$blF.$tgF."' ";
	  }
	  $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {
         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         $rsSelect = $db->fetchAll("select d_tgl_kerja,d_jamkerja_mulai,d_jamkerja_selesai,".
		 "d_jamistrht_mulai,d_jamistrht_selesai from e_sdm_abs_harikerja_tm where ".$strBetween);
         //echo "select d_tgl_kerja,d_jamkerja_mulai,d_jamkerja_selesai,".
		 //"d_jamistrht_mulai,d_jamistrht_selesai from e_sdm_abs_harikerja_tm where ".$strBetween;
         $jmlResult = count($rsSelect);
	     for ($j = 0; $j < $jmlResult; $j++) 
	     {
            $output[$j] = array("d_tgl_kerja"=>(string)$rsSelect[$j]->d_tgl_kerja,
			                    "d_jamkerja_mulai"=>(string)$rsSelect[$j]->d_jamkerja_mulai,
			                    "d_jamkerja_selesai"=>(string)$rsSelect[$j]->d_jamkerja_selesai,
			                    "d_jamistrht_mulai"=>(string)$rsSelect[$j]->d_jamistrht_mulai,
			                    "d_jamistrht_selesai"=>(string)$rsSelect[$j]->d_jamistrht_selesai);
	     }					 
	     return $output;
	  }	 
      catch (Exception $e) 
      {
         $errorE = $e->getMessage().'<br>';
	     return $errorE.'gagal <br>';
      }
   }
  
   public function hitungRekapAbsensi($nip,$tglawal,$tglakhir)
   {
         $nipku = getNipBaruLama($nip);
	  $btglawal = (string)$tglawal;
	  $btglakhir = (string)$tglakhir;
	  $tgS = substr($btglawal,6,2);
	  $blS = substr($btglawal,4,2);
	  $thS = substr($btglawal,0,4);
	  $tgF = substr($tglakhir,6,2);
	  $blF = substr($tglakhir,4,2);
	  $thF = substr($tglakhir,0,4);
	  if ($blS=="00") $blS = "01";
	  if ($blF=="00") $blF = "12";
	  if ($tgS=="00") $tgS = "01";
	  if ($tgF=="00") 
	  { 
	     $strBetween = " to_char(d_peg_absensi,'yyyymm') between '".$thS.$blS."' and '".$thF.$blF."' ";
	  }
	  else
	  { 
	     $strBetween = " to_char(d_peg_absensi,'yyyymmdd') between '".$thS.$blS.$tgS."' and '".$thF.$blF.$tgF."' ";
	  }
	  $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {
         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         $rsSelect = $db->fetchAll("select i_peg_nip,d_peg_absensi,d_peg_jam,c_absensi_peg,c_menejmen ".
         " FROM e_sdm_absensi_mesin_ts Where i_peg_nip='".trim($nipku)."' and ".$strBetween." order by d_peg_absensi,c_absensi_peg");
         $jmlResult = count($rsSelect);
	     for ($j = 0; $j < $jmlResult; $j++) 
	     {
            $output[$j] = array("i_peg_nip"=>(string)$rsSelect[$j]->i_peg_nip,
			                    "d_peg_absensi"=>(string)$rsSelect[$j]->d_peg_absensi,
			                    "d_peg_jam"=>(string)$rsSelect[$j]->d_peg_jam,
			                    "c_absensi_peg"=>(string)$rsSelect[$j]->c_absensi_peg,
			                    "c_menejmen"=>(string)$rsSelect[$j]->c_menejmen);
	     }					 
	     return $output;
      } 
      catch (Exception $e) 
      {
         $errorE = $e->getMessage().'<br>';
	     return $errorE.'gagal <br>';
      }
   }
   public function insRekapAbsensi($data)
   {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try 
	   {
	      $db->beginTransaction();
	      $rekapabsen_prm = array("i_peg_nip"       =>$data['i_peg_nip'],
	                              "d_peg_ijin"      =>$data['d_peg_ijin'],
	                              "d_jam_mulai"     =>$data['d_jam_mulai'],
							      "d_jam_selesai"   =>$data['d_jam_selesai'],
								  "c_manajemen"     =>$data['c_manajemen'],
								  "i_no_surat_ijin" =>$data['i_no_surat_ijin'],
								  "i_keterangan"    =>$data['i_keterangan'],
								  "i_entry"         =>$data['i_entry'],
								  "d_entry"         =>date("Y-m-d"));
	      $db->insert('e_sdm_abs_ijin_tm',$rekapabsen_prm);
		  $db->commit();
	      return 'sukses';
	   } 
	   catch (Exception $e) 
	   {
          $db->rollBack();
          echo $e->getMessage().'<br>';
	      return 'gagal';
	   }
   }
   public function getAbsensiVal($nip,$absen,$tglawal,$tglakhir)
   {
	  $btglawal = (string)$tglawal;
	  $btglakhir = (string)$tglakhir;
	  $tgS = substr($btglawal,6,2);
	  $blS = substr($btglawal,4,2);
	  $thS = substr($btglawal,0,4);
	  $tgF = substr($tglakhir,6,2);
	  $blF = substr($tglakhir,4,2);
	  $thF = substr($tglakhir,0,4);
	  if ($blS=="00") $blS = "01";
	  if ($blF=="00") $blF = "12";
	  if ($tgS=="00") $tgS = "01";
	  if ($tgF=="00") 
	  { 
	     $strBetween = " to_char(d_peg_ijin,'yyyymm') between '".$thS.$blS."' and '".$thF.$blF."' ";
	  }
	  else
	  { 
	     $strBetween = " to_char(d_peg_ijin,'yyyymmdd') between '".$thS.$blS.$tgS."' and '".$thF.$blF.$tgF."' ";
	  }
	  $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {
         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         $rsSelect = $db->fetchOne("select count(i_peg_nip) as nip FROM e_sdm_abs_ijin_tm Where i_peg_nip='".
		 trim($nip)."' and c_manajemen='".trim($absen)."' and ".$strBetween);
		 if ($rsSelect==0) { $output = "0"; }
		 else { $output = $rsSelect; }
	     return $output;
      } 
      catch (Exception $e) 
      {
         $errorE = $e->getMessage().'<br>';
	     return $errorE.'gagal <br>';
      }
   }
   public function getTahunSekarang()
   {
	  $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {
         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         $rsSelect = $db->fetchOne("select to_char(now(),'yyyy') ");
	     return $rsSelect;
      } 
      catch (Exception $e) 
      {
         $errorE = $e->getMessage().'<br>';
	     return $errorE.'gagal <br>';
      }
   }	
   public function getTglSekarang()
   {
	  $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {
         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         $rsSelect = $db->fetchOne("select to_char(now(),'ddmmyyyy') ");
	     return $rsSelect;
      } 
      catch (Exception $e) 
      {
         $errorE = $e->getMessage().'<br>';
	     return $errorE.'gagal <br>';
      }
   }	
   public function getPejabatAbsen($c_jabatan)
   {
	  $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {
         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         $rsSelect = $db->fetchOne("select a.i_peg_nip||';'||a.n_jabatan||';'||b.n_peg from e_sdm_jabatan_0_tm a 
		 left join e_sdm_pegawai_0_tm b on a.i_peg_nip=b.i_peg_nip where c_jabatan='".$c_jabatan."'");
	     return $rsSelect;
      } 
      catch (Exception $e) 
      {
         $errorE = $e->getMessage().'<br>';
	     return $errorE.'gagal <br>';
      }
   }
   public function getHariKerja($thnHariKerja,$blnHariKerja)
   {
	  $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {
         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         $rsSelect = $db->fetchAll("select to_char(d_tgl_kerja,'dd-MM-yyyy'),d_jamkerja_mulai,d_jamkerja_selesai,
		 d_jamistrht_mulai,d_jamistrht_selesai from e_sdm_abs_harikerja_tm 
		 where to_char(d_tgl_kerja,'yyyy')='".$thnHariKerja."' and to_char(d_tgl_kerja,'MM')='".
		 $blnHariKerja."'");
         $jmlResult = count($rsSelect);
	     for ($j = 0; $j < $jmlResult; $j++) 
	     {
            $hasilAkhir[$j] = array("d_tgl_kerja"=>(string)$rsSelect[$j]->d_tgl_kerja,
			                        "d_jamkerja_mulai"=>(string)$rsSelect[$j]->d_jamkerja_mulai,
			                        "d_jamkerja_selesai"=>(string)$rsSelect[$j]->d_jamkerja_selesai,
			                        "d_jamistrht_mulai"=>(string)$rsSelect[$j]->d_jamistrht_mulai,
			                        "d_jamistrht_selesai"=>(string)$rsSelect[$j]->d_jamistrht_selesai,
			                        "i_entry"=>(string)$rsSelect[$j]->i_entry,
			                        "d_entry"=>(string)$rsSelect[$j]->d_entry);
	     }					 
	     return $hasilAkhir;
      } 
      catch (Exception $e) 
      {
         $errorE = $e->getMessage().'<br>';
	     return $errorE.'gagal <br>';
      }
   }
    public function getJamHariKerja($tanggal)
	{
	  $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {
         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         $rsSelect = $db->fetchAll("select d_jamkerja_mulai,d_jamkerja_selesai,d_jamistrht_mulai,".
		 "d_jamistrht_selesai from e_sdm_abs_harikerja_tm ".
		 " where to_char(d_tgl_kerja,'ddmmyyyy')='".$tanggal."'");
            $hasilAkhir = array("d_tgl_kerja"=>(string)$rsSelect[0]->d_tgl_kerja,
			                        "d_jamkerja_mulai"=>(string)$rsSelect[0]->d_jamkerja_mulai,
			                        "d_jamkerja_selesai"=>(string)$rsSelect[0]->d_jamkerja_selesai,
			                        "d_jamistrht_mulai"=>(string)$rsSelect[0]->d_jamistrht_mulai,
			                        "d_jamistrht_selesai"=>(string)$rsSelect[0]->d_jamistrht_selesai,
			                        "i_entry"=>(string)$rsSelect[0]->i_entry,
			                        "d_entry"=>(string)$rsSelect[0]->d_entry);
	     return $hasilAkhir;
      } 
      catch (Exception $e) 
      {
	     return 'gagal';
      }
	}
    public function delHariKerja($thnHariKerja,$blnHariKerja)
    {
	   $registry = Zend_Registry::getInstance();
       $db = $registry->get('db');
	   try 
	   {
	     $db->beginTransaction();
		 $where[] = "to_char(d_tgl_kerja,'yyyymm')='".$thnHariKerja.$blnHariKerja."'";
	     $db->delete('e_sdm_abs_harikerja_tm', $where);
		 $db->commit();
	     return 'sukses';
	   } 
	   catch (Exception $e) 
	   {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	public function insHariKerja(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $harikerja_prm = array("d_tgl_kerja"  		  =>$data['d_tgl_kerja'],
	                            "d_jamkerja_mulai"    =>$data['d_jamkerja_mulai'],
	                            "d_jamkerja_selesai"  =>$data['d_jamkerja_selesai'],
							    "d_jamistrht_mulai"   =>$data['d_jamistrht_mulai'],
								"d_jamistrht_selesai" =>$data['d_jamistrht_selesai'],
								"i_entry"       	  =>$data['i_entry'],
								"d_entry"       	  =>date("Y-m-d"));
	     $db->insert('e_sdm_abs_harikerja_tm',$harikerja_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	public function cekJamKerja($hari)
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
       try 
       {
         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         $rsSelect = $db->fetchAll("select d_jamkerja_mulai,d_jamkerja_selesai,d_jamistrht_mulai,".
		 "d_jamistrht_selesai from tr_jamharikerja where c_hari=".$hari);
         $output = array("d_jamkerja_mulai"=>$rsSelect[0]->d_jamkerja_mulai,
						 "d_jamkerja_selesai"=>$rsSelect[0]->d_jamkerja_selesai,
						 "d_jamistrht_mulai"=>$rsSelect[0]->d_jamistrht_mulai,
						 "d_jamistrht_selesai"=>$rsSelect[0]->d_jamistrht_selesai);
	     return $output;
       } 
       catch (Exception $e) 
       {
	     return 'gagal';
       }
	}
	public function cekHariKerja($tahun,$bulan)
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('absendb');
       try 
       {
         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         $rsSelect = $db->fetchOne("select count(*) from e_sdm_abs_harikerja_tm ".
         " where to_char(d_tgl_kerja,'yyyymm')='".$tahun.$bulan."'");
	     return $rsSelect;
       } 
       catch (Exception $e) 
       {
         $errorE = $e->getMessage();
	     return 'gagal '.$errorE;
       }
	}
	public function getAbsensiPegawai($nipe,$tgl,$kode)
	{
          $nip = $this->getNipBaruLama($nipe);
          //echo "nip : ".$nip."<br>";
          //echo "nipe : ".$nipe."<br>";
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
       try 
       {
         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
								
		$rsSelect = $db->fetchAll("select d_peg_jam,c_absensi_peg,i_term ".
		 " from e_sdm_absensi_mesin_ts where i_peg_nip='".$nip."' and to_char(d_peg_absensi,'ddmmyyyy')='".
		 $tgl."' and c_absensi_peg='".$kode."' order by d_peg_jam desc");		 
		 
		 $jmlResult = count($rsSelect);
/*		 
  			$hasilAkhir = array("d_peg_jam"  =>(string)$rsSelect[$jmlResult-1]->d_peg_jam,
								"c_menejmen" =>(string)$rsSelect[$jmlResult-1]->c_absensi_peg,
								"i_term"	 =>(string)$rsSelect[$jmlResult-1]->i_term);							
*/								
  			$hasilAkhir = array("d_peg_jam"  =>(string)$rsSelect[0]->d_peg_jam,
								"c_menejmen" =>(string)$rsSelect[0]->c_absensi_peg,
								"i_term"	 =>(string)$rsSelect[0]->i_term);							
	     return $hasilAkhir;
       } 
       catch (Exception $e) 
       {
	     return 'gagal';
       }
	}
	public function foundAbsensiMesin($nipe,$tgl,$jam)
	{
          $nip = $this->getNipBaruLama($nipe);
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


///Jangan Dihapus lagi ya.....!!!!!	
///==================================IJIN================================	
    public function getIjinByNip($nip,$tgl)
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
       try 
       {
         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         $rsSelect = $db->fetchAll("select i_no_surat_ijin,d_entry ".
		 " from  	e_sdm_abs_ijin_tm where i_peg_nip='".$nip."' and to_char(d_peg_ijin,'ddmmyyyy')='".
		 $tgl."'");
		 //echo "select i_no_surat_ijin,d_entry ".
		 //" from  	e_sdm_abs_ijin_tm where i_peg_nip='".$nip."' and to_char(d_peg_ijin,'ddmmyyyy')='".
		 //$tgl."'";
	     $jmlResult = count($rsSelect);
  			$hasilAkhir = array("i_no_surat_ijin"  =>(string)$rsSelect[$jmlResult-1]->i_no_surat_ijin,
								"d_entry" =>(string)$rsSelect[$jmlResult-1]->d_entry);
								
	     return $hasilAkhir;
       } 
       catch (Exception $e) 
       {
	     return 'gagal';
       }
	}
	
	public function getIjinListPP(array $data, $pageNumber, $itemPerPage) 
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try 
	   {
		   $db->setFetchMode(Zend_Db::FETCH_OBJ);
		   $tgl1 = $data['tgl1'];
		   $tgl2 = $data['tgl2'];
           if (($pageNumber==0) && ($itemPerPage==0))
           {
               $hasilAkhir = $db->fetchOne("select count(a.i_peg_nip) from e_sdm_abs_ijin_tm a,".
			   "e_sdm_pegawai_0_tm b where  a.i_peg_nip = b.i_peg_nip and a.d_peg_ijin between ".
			   "'".$tgl1."' and '".$tgl2."'");
  		    }
           else
           {
			$maks=($pageNumber-1)*$itemPerPage;
		       $result = $db->fetchAll("select a.i_peg_nip,  b.n_peg, a.d_peg_ijin, a.d_jam_mulai,".
               "a.d_jam_selesai,a.c_manajemen, a.i_no_surat_ijin, a.i_keterangan ".
			   " from e_sdm_abs_ijin_tm a, e_sdm_pegawai_0_tm b where  a.i_peg_nip = b.i_peg_nip ".
               " and a.d_peg_ijin between "."'".$tgl1."' and '".$tgl2."' order by a.d_peg_ijin, b.n_peg ".
			   " limit ".$itemPerPage." offset ".$maks);
	           $jmlResult = count($result);
		       for ($j = 0; $j < $jmlResult; $j++) 
			   { 
				$namaJenis = $db->fetchCol('SELECT n_absensi FROM e_sdm_absensi_0_tr WHERE c_absensi = ?',$result[$j]->c_manajemen);
  			      $hasilAkhir[$j] = array("nip"  			=>(string)$result[$j]->i_peg_nip,
											"nama"			=>(string)$result[$j]->n_peg,
											"namaJenis"		=>$namaJenis[0],
											"tanggal"		=>(string)$result[$j]->d_peg_ijin,
											"jamMulai"		=>(string)$result[$j]->d_jam_mulai,
											"jamSelesai"	=>(string)$result[$j]->d_jam_selesai,
											"jenis"			=>(string)$result[$j]->c_manajemen,
											"noSurat"		=>(string)$result[$j]->i_no_surat_ijin,
											"keterangan"	=>(string)$result[$j]->i_keterangan);
		        }
			}	
	        return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	
	public function getIjinListByKey(array $data, $pageNumber, $itemPerPage) 
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try 
	   {
		   $db->setFetchMode(Zend_Db::FETCH_OBJ);
		   $tgl1 = $data['tgl1'];
		   $tgl2 = $data['tgl2'];
		   $nama = $data['nama'];
		   $nip  = $data['nip'];
		   $noSurat = $data['noSurat'];
		   //echo "tgl1= ".$tgl1."<br>"."tgl2= ".$tgl2."<br>"."nama= ".$nama."<br>"."nip= ".$nip."<br>"."noSurat= ".$noSurat."<br>";
			$where[] = "%".$nama."%";
			$where[] = $nip."%";
			$where[] = $noSurat."%";
			$where[] = $tgl1;
			$where[] = $tgl2;
           if (($pageNumber==0) && ($itemPerPage==0))
           {
               $hasilAkhir = $db->fetchOne("select count(*) from e_sdm_abs_ijin_tm a, e_sdm_pegawai_0_tm b 
										where a.i_peg_nip = b.i_peg_nip and upper(b.n_peg) like upper(?)
										and upper(a.i_peg_nip) like upper(?) and upper(a.i_no_surat_ijin) like upper(?) 
										and (a.d_peg_ijin between ? and ?)",$where);
  		    }
           else
           {
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;
		       $result = $db->fetchAll("select a.i_peg_nip,  b.n_peg, a.d_peg_ijin, a.d_jam_mulai,
								a.d_jam_selesai, a.c_manajemen, a.i_no_surat_ijin, a.i_keterangan 
								from e_sdm_abs_ijin_tm a, e_sdm_pegawai_0_tm b 
								where  a.i_peg_nip = b.i_peg_nip and upper(b.n_peg) like upper(?) 
								and upper(a.i_peg_nip) like upper(?) and upper(a.i_no_surat_ijin) like upper(?) 
								and (a.d_peg_ijin between ? and ?) order by a.d_peg_ijin, b.n_peg, a.i_peg_nip, 
								a.i_no_surat_ijin
								limit $xLimit offset $xOffset",$where);
	           $jmlResult = count($result);
		       for ($j = 0; $j < $jmlResult; $j++) 
			   { 
				$namaJenis = $db->fetchCol('SELECT n_absensi FROM e_sdm_absensi_0_tr WHERE c_absensi = ?',$result[$j]->c_manajemen);
  			      $hasilAkhir[$j] = array("nip"  			=>(string)$result[$j]->i_peg_nip,
											"nama"			=>(string)$result[$j]->n_peg,
											"namaJenis"		=>$namaJenis[0],
											"tanggal"		=>(string)$result[$j]->d_peg_ijin,
											"jamMulai"		=>(string)$result[$j]->d_jam_mulai,
											"jamSelesai"	=>(string)$result[$j]->d_jam_selesai,
											"jenis"			=>(string)$result[$j]->c_manajemen,
											"noSurat"		=>(string)$result[$j]->i_no_surat_ijin,
											"keterangan"	=>(string)$result[$j]->i_keterangan);
		        }
			}	
	        return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	
	public function getIjinList($tgl1, $tgl2) {
	//echo "masuk getIjinList"."<br>";
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');

	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$where[] = $tgl1;
		$where[] = $tgl2;

		$result = $db->fetchAll("select a.i_peg_nip,  b.n_peg, a.d_peg_ijin, a.d_jam_mulai, a.d_jam_selesai,
								a.c_manajemen, a.i_no_surat_ijin, a.i_keterangan
								from e_sdm_abs_ijin_tm a, e_sdm_pegawai_0_tm b
								where  a.i_peg_nip = b.i_peg_nip  and a.d_peg_ijin between ? and ? 
								order by a.d_peg_ijin, b.n_peg ",$where);
	   $jmlResult = count($result);
		//echo "JMLResult".$jmlResult."<br>";
		 for ($j = 0; $j < $jmlResult; $j++) {
				$namaJenis = $db->fetchCol('SELECT n_absensi FROM e_sdm_absensi_0_tr WHERE c_absensi = ?',$result[$j]->c_manajemen);
  			$hasilAkhir[$j] = array("nip"  			=>(string)$result[$j]->i_peg_nip,
									"nama"			=>(string)$result[$j]->n_peg,
									"tanggal"		=>(string)$result[$j]->d_peg_ijin,
									"jamMulai"		=>(string)$result[$j]->d_jam_mulai,
									"jamSelesai"	=>(string)$result[$j]->d_jam_selesai,
									"jenis"			=>(string)$result[$j]->c_manajemen,
									"namaJenis"			=>$namaJenis[0],
									"noSurat"		=>(string)$result[$j]->i_no_surat_ijin,
									"keterangan"	=>(string)$result[$j]->i_keterangan);
		 }
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
	
	public function insertIjin(array $data) {	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   //$user = '930554';
	   //echo "user= ".$data['user'];
	   try {
	     $db->beginTransaction();
	     $ijin_prm = array("i_peg_nip"  		=>$data['nip'],
						   "d_peg_ijin"  		=>$data['tanggal'],
						   "i_no_surat_ijin"  	=>$data['noSurat'],
						   "c_manajemen"  		=>$data['jenis'],
						   "d_jam_mulai"        =>$data['jamMulai'],
						   "d_jam_selesai"		=>$data['jamSelesai'],
						   "i_keterangan"		=>$data['keterangan'],
						   "i_entry"       		=>$data['user'],
						   "d_entry"       		=>date("Y-m-d"));
	     $db->insert('e_sdm_abs_ijin_tm',$ijin_prm);
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
	
	public function getIjinByKey($nip, $tanggal, $jamMulai) {
	//echo "masuk getIjinByKey";
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');

	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$where[] = $nip;
		$where[] = $tanggal;
		$where[] = $jamMulai;

		$result = $db->fetchAll("select * from e_sdm_abs_ijin_tm
								where  i_peg_nip = ?  and d_peg_ijin = ? and d_jam_mulai = ?
								order by i_peg_nip ",$where);
	   $jmlResult = count($result);
		//echo "JMLResult".$jmlResult."<br>";
		 for ($j = 0; $j < $jmlResult; $j++) {
 			$nama = $db->fetchCol('SELECT n_peg FROM e_sdm_pegawai_0_tm WHERE i_peg_nip = ?',$result[$j]->i_peg_nip);
 			$hasilAkhir = array("nip"  				=>(string)$result[$j]->i_peg_nip,
								"nama"  			=>(string)$nama[0],
								"tanggal"			=>(string)$result[$j]->d_peg_ijin,
								"noSurat"			=>(string)$result[$j]->d_peg_jammasuk,
								"jenis"				=>(string)$result[$j]->c_manajemen,
								"noSurat"			=>(string)$result[$j]->i_no_surat_ijin,
								"jamMulai"			=>(string)$result[$j]->d_jam_mulai,
								"jamSelesai"		=>(string)$result[$j]->d_jam_selesai,
								"keterangan"		=>(string)$result[$j]->i_keterangan);
		 }
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function updateIjin(array $data) {
	//echo "masuk updateIjin";
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $upd_ijin_prm = array("i_peg_nip"  		=>$data['nip'],
							   "d_peg_ijin"  		=>$data['tanggal'],
							   "i_no_surat_ijin"  	=>$data['noSurat'],
							   "c_manajemen"  		=>$data['jenis'],
							   "d_jam_mulai"        =>$data['jamMulai'],
							   "d_jam_selesai"		=>$data['jamSelesai'],
							   "i_keterangan"		=>$data['keterangan'],
							   "i_entry"       		=>$data['user'],
							   "d_entry"       		=>date("Y-m-d"));
			//echo "mulaiH= ".$data['jamMulaiH']."<br>";
			//echo "selesai= ".$data['jamSelesai']."<br>";
		 $where[] = "i_peg_nip = '".$data['nipH']."'";
	     $where[] = "d_peg_ijin = '".$data['tanggalH']."'";
	     $where[] = "d_jam_mulai = '".$data['jamMulaiH']."'";
	     $db->update('e_sdm_abs_ijin_tm', $upd_ijin_prm, $where);
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
	
	public function deleteIjin(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 $where[] = "i_peg_nip = '".$data['nip']."'";
		 $where[] = "d_peg_ijin = '".$data['tanggal']."'";
		 $where[] = "d_jam_mulai = '".$data['jamMulai']."'";
	     $db->delete('e_sdm_abs_ijin_tm', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	public function hitungHari($tglawal,$tglakhir) 
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try 
	   {
		  $db->setFetchMode(Zend_Db::FETCH_OBJ);
		  $result = $db->fetchOne("SELECT to_date('".$tglakhir."','ddmmyyyy') - to_date('".$tglawal."','ddmmyyyy')");
	      return $result;
	   } 
	   catch (Exception $e) 
	   { return "0"; }
	}	
	public function getNamaPegawai($nip) 
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try 
	   {
		  $db->setFetchMode(Zend_Db::FETCH_OBJ);
		  $result = $db->fetchOne("SELECT n_peg from e_sdm_pegawai_0_tm where i_peg_nip='".$nip."'");
	      return $result;;
	   } 
	   catch (Exception $e) 
	   { return "tidak ketemu"; }
	}	
    public function getPegawaiListAll(array $data)
	{
	  $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {
		 $nip = $data['i_peg_nip'];
		 $nama = $data['n_peg'];
		 $idORG = $data['idorg'];
		 //if (substr($idORG,0,2)=="SK") $idORG = substr($idORG,0,2);
         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         $rsSelect = $db->fetchAll("select i_peg_nip,n_peg,i_orgb,c_unit_kerja from e_sdm_pegawai_0_tm ".
		 " where c_unit_kerja like '".$idORG."%' and i_peg_nip like '".$nip."%' and n_peg like '".$nama."%' and c_peg_status not in ('93P','94P','95B','96B','97M')");
         $jmlResult = count($rsSelect);
	     for ($j = 0; $j < $jmlResult; $j++) 
	     {
            $hasilAkhir[$j] = array("i_peg_nip"=>(string)$rsSelect[$j]->i_peg_nip,
			                        "n_peg"=>(string)$rsSelect[$j]->n_peg,
			                        "i_orgb"=>(string)$rsSelect[$j]->i_orgb,
			                        "c_unit_kerja"=>(string)$rsSelect[$j]->c_unit_kerja);
	     }					 
	     return $hasilAkhir;
      } 
      catch (Exception $e) 
      {
	     return 'gagal';
      }
	}
    public function getPegawaiList(array $data)
	{
	  $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {
		 $nip = $data['i_peg_nip'];
		 $nama = $data['n_peg'];
		 $idORG = $data['idorg'];
		 if (substr($idORG,0,2)=="SK") $idORG = substr($idORG,0,2);
		 else $idORG = substr($idORG,0,3);
         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         $rsSelect = $db->fetchAll("select i_peg_nip,n_peg,i_orgb,c_unit_kerja from e_sdm_pegawai_0_tm ".
		 " where c_unit_kerja like '".$idORG."%' and i_peg_nip like '%".$nip."%' and UPPER(n_peg) like '%".strtoupper($nama)."%' and c_peg_status not in ('93P','94P','95B','96B','97M') ");
		 
		 //$rsSelect = $db->fetchAll("select i_peg_nip,n_peg,i_orgb,c_unit_kerja from e_sdm_pegawai_0_tm ".
		 //" where c_unit_kerja like '".$idORG."%' and i_peg_nip like '%".$nip."%' and UPPER(n_peg) like '%".strtoupper($nama)."%'");
		 
         $jmlResult = count($rsSelect);
	     for ($j = 0; $j < $jmlResult; $j++) 
	     {
            $hasilAkhir[$j] = array("i_peg_nip"=>(string)$rsSelect[$j]->i_peg_nip,
			                        "n_peg"=>(string)$rsSelect[$j]->n_peg,
			                        "i_orgb"=>(string)$rsSelect[$j]->i_orgb,
			                        "c_unit_kerja"=>(string)$rsSelect[$j]->c_unit_kerja);
	     }					 
	     return $hasilAkhir;
      } 
      catch (Exception $e) 
      {
	     return 'gagal';
      }
	}
    public function getPegawaiListPP(array $data, $pageNumber, $itemPerPage)
	{
	//echo "getPegawaiListPP"."<br>";
	
	  $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {
		 $nip = $data['i_peg_nip'];
		 //$nama = $data['n_peg'];
		 $idORG = $data['idorg'];
		 echo "nip= ".$nip."<br>"."idorg= ".$idORG."<br>";
		 if (substr($idORG,0,2)=="SK") $idORG = substr($idORG,0,2);
		 else $idORG = substr($idORG,0,3);
         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         if (($pageNumber==0) && ($itemPerPage==0))
         {
            $hasilAkhir = $db->fetchOne("select count(i_peg_nip) from e_sdm_pegawai_0_tm ".
		    " where c_unit_kerja like '".$idORG."%'  and c_peg_status='3PN' and i_peg_nip like '".$nip."%'");  //and n_peg like '".$nama."%'");
		 }
         else
         {
			$maks=($pageNumber-1)*$itemPerPage;
            $rsSelect = $db->fetchAll("select i_peg_nip,n_peg,i_orgb,c_unit_kerja, i_nip_baru from e_sdm_pegawai_0_tm ".
		    " where c_unit_kerja like '".$idORG."%'  and c_peg_status='3PN'and i_peg_nip like '".$nip."%' limit ".$itemPerPage." offset ".$maks );
			//"%' and n_peg like '".$nama.
            $jmlResult = count($rsSelect);
			echo "jmlResult= ".$jmlResult."<br>";
	        for ($j = 0; $j < $jmlResult; $j++) 
	        {
            $hasilAkhir[$j] = array("i_peg_nip"=>(string)$rsSelect[$j]->i_peg_nip,
			                        "n_peg"=>(string)$rsSelect[$j]->n_peg,
			                        "i_orgb"=>(string)$rsSelect[$j]->i_orgb,
			                        "c_unit_kerja"=>(string)$rsSelect[$j]->c_unit_kerja,
									"i_nip_baru"=>(string)$rsSelect[$j]->i_nip_baru);
	        }					 
         }

	     return $hasilAkhir;
      } 
      catch (Exception $e) 
      {
	     return 'gagal';
      }
	}
	public function getEselonUserid($nip)
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try 
	   {
		  $db->setFetchMode(Zend_Db::FETCH_OBJ);
		  $result = $db->fetchOne("SELECT c_eselon from e_sdm_pegawai_0_tm where i_peg_nip='".$nip."'");
	      return $result;;
	   } 
	   catch (Exception $e) 
	   { return "tidak ketemu"; }
	}
   public function getJmlAbsenperMonth($bulan,$unit)
   {
      $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {
         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if ($unit=='SK')
		 {
         $rsSelect = $db->fetchOne("select count(a.i_peg_nip) from e_sdm_pegawai_0_tm a, ".
		             " e_sdm_absensi_mesin_ts b ".
                     " where a.c_peg_status='3PN' and a.i_peg_nip=b.i_peg_nip and b.c_absensi_peg='01' and ".
                     " to_char(b.d_peg_absensi,'yyyymm')='".$bulan."' and substring(a.c_unit_kerja,1,2)='SK'");
		 }			 
		 else
		 {
         $rsSelect = $db->fetchOne("select count(a.i_peg_nip) from e_sdm_pegawai_0_tm a, ".
		             " e_sdm_absensi_mesin_ts b".
                     " where a.c_peg_status='3PN' and a.i_peg_nip=b.i_peg_nip and b.c_absensi_peg='01' and ".
                     " to_char(b.d_peg_absensi,'yyyymm')='".$bulan."' and substring(a.c_unit_kerja,1,3)='".
					 $unit."'");
		 }			 
	     return $rsSelect;
      } 
      catch (Exception $e) 
      {
         $errorE = $e->getMessage().'<br>';
	     return $errorE.'gagal <br>';
      }
   }
   public function getJmlKarperUnit($unit)
   {
      $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {
         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if ($unit=='SK')
		 {
         $rsSelect = $db->fetchOne("select count(i_peg_nip) from e_sdm_pegawai_0_tm ".
                     " where c_peg_status='3PN' and substring(c_unit_kerja,1,2)='SK'");
		 }			 
		 else
		 {
         $rsSelect = $db->fetchOne("select count(i_peg_nip) from e_sdm_pegawai_0_tm ".
                     " where c_peg_status='3PN' and substring(c_unit_kerja,1,3)='".$unit."'");
		 }			 
	     return $rsSelect;
      } 
      catch (Exception $e) 
      {
         $errorE = $e->getMessage().'<br>';
	     return $errorE.'gagal <br>';
      }
   }
   public function getDataAtasan($i_peg_nip)
   {
      $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {
         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         $rsSelect = $db->fetchAll("select i_peg_nip,n_peg from e_sdm_pegawai_0_tm ".
		 " where c_unit_kerja in (select i_orgb from e_sdm_pegawai_0_tm ".
		 " where i_peg_nip='".$i_peg_nip."') and c_eselon not in ('NE')");
		 $hasilAkhir = array("nip"=>(string)$rsSelect[0]->i_peg_nip,
		                     "nama"=>(string)$rsSelect[0]->n_peg);
	     return $hasilAkhir;
		 /*
		 return "select i_peg_nip,n_peg from e_sdm_pegawai_0_tm ".
		 " where c_unit_kerja in (select i_orgb from e_sdm_pegawai_0_tm ".
		 " where i_peg_nip='".$i_peg_nip."') and c_eselon not in ('NE')";
		 */
      } 
      catch (Exception $e) 
      {
         $errorE = $e->getMessage().'<br>';
	     return $errorE.'gagal <br>';
      }
   }
   //bamris 21 Aug 2010
   public function getNipLamaBaru($i_peg_nip)
   {
      $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {
         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         $rsSelect = $db->fetchAll("select i_peg_niplama,i_peg_nipbaru from e_sdm_nipbarulama_ts where i_peg_niplama='".$i_peg_nip."'");
         //echo "select i_peg_niplama,i_peg_nipbaru from e_sdm_nipbarulama_ts where i_peg_niplama='".$i_peg_nip."'";
         if (count($rsSelect)>0) 
         { $hasilAkhir = $rsSelect[0]->i_peg_nipbaru; }
         else { $hasilAkhir = $i_peg_nip; }
	  return $hasilAkhir;
      } 
      catch (Exception $e) 
      {
         $errorE = $e->getMessage().'<br>';
	     return $errorE.'gagal <br>';
      }
   }
   public function getNipBaruLama($i_peg_nip)
   {
      $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');
      try 
      {
         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         $rsSelect = $db->fetchOne("select i_peg_niplama from e_sdm_nipbarulama_ts where i_peg_nipbaru='".$i_peg_nip."'");
         //echo "select i_peg_niplama from e_sdm_nipbarulama_ts where i_peg_nipbaru='".$i_peg_nip."'<br>";
         //echo "rs : ".$rsSelect."<br>";
         if ($rsSelect!="") 
         { $hasilAkhir = $rsSelect; }
         else { $hasilAkhir = $i_peg_nip; }
         //echo "hasil : ".$hasilAkhir."<br>";
	  return $hasilAkhir;
         //{ $hasilAkhir = $rsSelect[0]->i_peg_niplama; }
         //else { $hasilAkhir = $i_peg_nip; }
      } 
      catch (Exception $e) 
      {
         $errorE = $e->getMessage().'<br>';
	     return $errorE.'gagal <br>';
      }
   }
}
?>