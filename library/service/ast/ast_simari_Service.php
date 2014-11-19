<?php
//include ("connect.php");
class ast_simari_Service {
   
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
   
   
    public function deleteTable($namaTable){
	  $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     
	     $db->beginTransaction();	    
		 $db->delete($namaTable, '');		
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	   
	}
	public function deleteTableLogPushData($namaTable,$satker,$namatabel){
	  $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
	     $db->beginTransaction();
         $key[] = "n_table = '".$namatabel."'";		 
         $key[] = "i_entry = '".$satker."'";		 
		 $db->delete($namaTable, $key);		
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	   
	}
    public function insertTMGOL($userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $query = "select * from t_gol";	 
		 $result=mysql_query($query);
		 $numrows = mysql_num_rows($result);
		 
		 $idx=0;
		if($numrows>0){
		   $this->deleteTable('aset.tm_gol');
		}
		 $db->beginTransaction();
		 while($data[$i]=mysql_fetch_array($result)){
		   
	       $hasil[$idx]=array("kd_gol"=>$row[kd_gol],
		                      "ur_gol"=>$row[ur_gol],
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   
		   $db->insert('aset.tm_gol',$hasil[$idx]);
		   $idx++;
		 }
	    
		 $db->commit();
		 $x=array("namatabel"=>"tm_gol","jumlah"=>$numrows);
	     return $x;
	     //return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function insertTMDIL($userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $query = "select * from t_dil";	 
		 $result=mysql_query($query);
		 $numrows = mysql_num_rows($result);
		 
		 $idx=0;
		if($numrows>0){
		   $this->deleteTable('aset.tm_dil');
		}
		 $db->beginTransaction();
		 while($row=mysql_fetch_array($result)){
		  
	       $hasil[$idx]=array("kd_lokasi"=>$row[kd_lokasi],
		                      "kd_pemilik"=>$row[kd_pemilik],
		                      "kd_brg"=>$row[kd_brg],
		                      "no_aset"=>$row[no_aset],
		                      "lok_fisik"=>$row[lok_fisik],
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   
		   $db->insert('aset.tm_dil',$hasil[$idx]);
		   $idx++;
		 }
	     
		 $db->commit();
		 $x=array("namatabel"=>"tm_dil","jumlah"=>$numrows);
	     return $x;
	     //return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function insertTMDIR($userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $query = "select * from t_dir";	 
		 $result=mysql_query($query);
		 $numrows = mysql_num_rows($result);
		 $idx=0;
		if($numrows>0){
		   $this->deleteTable('aset.tm_dir');
		}
		 $db->beginTransaction();
		 while($row=mysql_fetch_array($result)){
		   
	       $hasil[$idx]=array("kd_lokasi"=>$row[kd_lokasi],
		                      "kd_pemilik"=>$row[kd_pemillik],
		                      "kd_brg"=>$row[kd_brg],
		                      "no_aset"=>$row[no_aset],
		                      "kd_ruang"=>$row[kd_ruang],
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   
		   $db->insert('aset.tm_dir',$hasil[$idx]);
		   $idx++;
		 }
	    
		 $db->commit();
		 $x=array("namatabel"=>"tm_dir","jumlah"=>$numrows);
	     return $x;
	     //return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function insertTMMASTERHM($userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $query = "select * from t_masterhm";	 
		 $result=mysql_query($query);
		 $numrows = mysql_num_rows($result);
		 
		if($numrows>0){
		   $this->deleteTable('aset.tm_masterhm');
		}
		 $db->beginTransaction();
		 //echo "row : ".$numrows;
		//print_r(mysql_fetch_array($result));
		$idx=0;
		 while($row=mysql_fetch_array($result)){
		 //echo "record ke - : ".$idx;
		  if($row[tgl_dsr_mts]=='0000-00-00'){
		   
		   $row[tgl_dsr_mts]=null;
		   }
		   if($row[tgl_perlh]=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $row[tgl_perlh]=null;
		   }
		   if($row[tgl_buku]=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $row[tgl_buku]=null;
		   }
		   
	       $hasil[$idx]=array("thn_ang"=>$row[thn_ang],
		                      "periode"=>$row[periode],
		                      "kd_lokasi"=>$row[kd_lokasi],
		                      "no_sppa"=>$row[no_sppa],
		                      "kd_brg"=>$row[kd_brg],
		                      "no_aset"=>$row[no_aset],
		                      "tgl_perlh"=>$row[tgl_perlh],
		                      "tercatat"=>$row[tercatat],
		                      "kondisi"=>$row[kondisi],
		                      "tgl_buku"=>$row[tgl_buku],
		                      "jns_trn"=>$row[jns_trn],
		                      "dsr_hrg"=>$row[dsr_hrg],
		                      "kd_data"=>$row[kd_data],
		                      "flag_sap"=>$row[flag_sap],
		                      "kuantitas"=>$row[kuantitas],
		                      "rph_sat"=>$row[rph_sat],
		                      "rph_aset"=>$row[rph_aset],
		                      "flag_kor"=>$row[flag_kor],
		                      "keterangan"=>$row[keterangan],
		                      "merk_type"=>$row[merk_type],
		                      "asal_perlh"=>$row[asal_perlh],
		                      "no_bukti"=>$row[no_bukti],
		                      "no_dsr_mts"=>$row[no_dsr_mts],
		                      "tgl_dsr_mts"=>$row[tgl_dsr_mts],
		                      "flag_ttp"=>$row[flag_ttp],
		                      "flag_krm"=>$row[flag_krm],
		                      "kdblu"=>$row[kdblu],
		                      "setatus"=>$row[setatus],
		                      "noreg"=>$row[noreg],
		                      "kdbapel"=>$row[kdbapel],
		                      "kdkpknl"=>$row[kdkpknl],
		                      "umeko"=>$row[umeko],
		                      "rph_res"=>$row[rph_res],
		                      "kdkppn"=>$row[kdkppn],
		                      "no_asetlm"=>$row[no_asetlm],
		                      "kd_brglm"=>$row[kd_brglm],
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   
		   $db->insert('aset.tm_masterhm',$hasil[$idx]);
		   $idx++;
		 }
	    
		 $db->commit();
		 $x=array("namatabel"=>"tm_masterhm","jumlah"=>$numrows);
	     return $x;
	     //return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function insertTMPBI($userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $query = "select * from t_pbi";	 
		 $result=mysql_query($query);
		 $numrows = mysql_num_rows($result);
		 $idx=0;
		if($numrows>0){
		   $this->deleteTable('aset.tm_pbi');
		}
		 $db->beginTransaction();
		 while($row=mysql_fetch_array($result)){
		   
	       $hasil[$idx]=array("kd_pebin"=>$row[kd_pebin],
		                      "kd_pbi"=>$row[kd_pbi],
		                      "ur_pbi"=>$row[ur_pbi],		                      
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   
		   $db->insert('aset.tm_pbi',$hasil[$idx]);
		   $idx++;
		 }
	    
		 $db->commit();
		 $x=array("namatabel"=>"tm_pbi","jumlah"=>$numrows);
	     return $x;
	     //return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function insertTMPPBI($userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $query = "select * from t_ppbi";	 
		 $result=mysql_query($query);
		 $numrows = mysql_num_rows($result);
		 $idx=0;
		if($numrows>0){
		   $this->deleteTable('aset.tm_ppbi');
		}
		 $db->beginTransaction();
		 while($row=mysql_fetch_array($result)){
		   
	       $hasil[$idx]=array("kd_pebin"=>$row[kd_pebin],
		                      "kd_pbi"=>$row[kd_pbi],
		                      "kd_ppbi"=>$row[kd_ppbi],
		                      "ur_ppbi"=>$row[ur_ppbi],		                      
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   
		   $db->insert('aset.tm_ppbi',$hasil[$idx]);
		   $idx++;
		 }
	    
		 $db->commit();
		 $x=array("namatabel"=>"tm_ppbi","jumlah"=>$numrows);
	     return $x;
	     //return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function insertTMKBDG($userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $query = "select * from t_kbdg";	 
		 $result=mysql_query($query);
		 $numrows = mysql_num_rows($result);
		 $idx=0;
		if($numrows>0){
		   $this->deleteTable('aset.tm_kbdg');
		}
		 $db->beginTransaction();
		 //echo "row : ".$numrows;
		//print_r(mysql_fetch_array($result));
		$idx=1;
		 while($row=mysql_fetch_array($result)){
		  if($row[tgl_imb]=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $row[tgl_imb]=null;
		   }
		   if($row[tgl_dana]=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $row[tgl_dana]=null;
		   }
		   if($row[tgl_buku]=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $row[tgl_buku]=null;
		   }
		   
	       $hasil[$idx]=array("kd_lokasi"=>$row[kd_lokasi],
		                      "kd_brg"=>$row[kd_brg],
		                      "no_aset"=>$row[no_aset],
		                      "kuantitas"=>$row[kuantitas],
		                      "rph_aset"=>$row[rph_aset],
		                      "no_kib"=>$row[no_kib],
		                      "luas_bdg"=>$row[luas_bdg],
		                      "jml_lt"=>$row[jml_lt],
		                      "tipe"=>$row[tipe],
		                      "thn_sls"=>$row[thn_sls],
		                      "thn_pakai"=>$row[thn_pakai],
		                      "no_imb"=>$row[no_imb],
		                      "tgl_imb"=>$row[tgl_imb],
		                      "kd_prov"=>$row[kd_prov],
		                      "kd_kab"=>$row[kd_kab],
		                      "kd_kec"=>$row[kd_kec],
		                      "kd_kel"=>$row[kd_kel],
		                      "alamat"=>$row[alamat],
		                      "kd_rtrw"=>$row[kd_rtrw],
		                      "no_kibtnh"=>$row[no_kibtnh],
		                      "jns_trn"=>$row[jns_trn],
		                      "dari"=>$row[dari],
		                      "tgl_prl"=>$row[tgl_prl],
		                      "kondisi"=>$row[kondisi],
		                      "rph"=>$row[rph],
		                      "dasar_hrg"=>$row[dasar_hrg],
		                      "sumber"=>$row[sumber],
		                      "no_dana"=>$row[no_dana],
		                      "tgl_dana"=>$row[tgl_dana],
		                      "unit_pmk"=>$row[unit_pmk],
		                      "alm_pmk"=>$row[alm_pmk],
		                      "catatan"=>$row[catatan],
		                      "tgl_buku"=>$row[tgl_buku],
		                      "rphwajar"=>$row[rphwajar],
		                      "rphnjop"=>$row[rphnjop],
		                      "status"=>$row[status],
		                      "cad1"=>$row[cad1],
		                      "luas_dsr"=>$row[luas_dsr],
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   
		   $db->insert('aset.tm_kbdg',$hasil[$idx]);
		   $idx++;
		 }
	    
		 $db->commit();
		 $x=array("namatabel"=>"tm_kbdg","jumlah"=>$numrows);
	     return $x;
	     //return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function insertTMBID($userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $query = "select * from t_bid";	 
		 $result=mysql_query($query);
		 $numrows = mysql_num_rows($result);
		 $idx=0;
		if($numrows>0){
		   $this->deleteTable('aset.tm_bid');
		}
		 $db->beginTransaction();
		 while($row=mysql_fetch_array($result)){
		   
	       $hasil[$idx]=array("kd_gol"=>$row[kd_gol],
		                      "kd_bid"=>$row[kd_bid],
		                      "ur_bid"=>$row[ur_bid],
		                      "kd_bidbrg"=>$row[kd_bidbrg],		                      
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   
		   $db->insert('aset.tm_bid',$hasil[$idx]);
		   $idx++;
		 }
	    
		 $db->commit();
		 $x=array("namatabel"=>"tm_bid","jumlah"=>$numrows);
	     return $x;
	     //return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function insertTMKTNH($userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $query = "select * from t_ktnh";	 
		 $result=mysql_query($query);
		 $numrows = mysql_num_rows($result);
		 $idx=0;
		if($numrows>0){
		   $this->deleteTable('aset.tm_ktnh');
		}
		 $db->beginTransaction();
		 //echo "row : ".$numrows;
		//print_r(mysql_fetch_array($result));
		$idx=1;
		 while($row=mysql_fetch_array($result)){
		   if($row[tgl_dana]=='0000-00-00'){
		  
		   $row[tgl_dana]=null;
		   }
		    if($row[tgl_buku]=='0000-00-00'){
		   
		   $row[tgl_buku]=null;
		   }
		   
	       $hasil[$idx]=array("kd_lokasi"=>$row[kd_lokasi],
		                      "kd_brg"=>$row[kd_brg],
		                      "no_aset"=>$row[no_aset],
		                      "kuantitas"=>$row[kuantitas],
		                      "rph_aset"=>$row[rph_aset],
		                      "no_kib"=>$row[no_kib],
		                      "luas_tnhs"=>$row[luas_tnhs],
		                      "luas_tnhb"=>$row[luas_tnhb],
		                      "luas_tnhl"=>$row[luas_tnhl],
		                      "luas_tnhk"=>$row[luas_tnhk],
		                      "kd_prov"=>$row[kd_prov],
		                      "kd_kab"=>$row[kd_kab],
		                      "kd_kec"=>$row[kd_kec],
		                      "kd_kel"=>$row[kd_kel],
		                      "kd_rtrw"=>$row[kd_rtrw],
		                      "alamat"=>$row[alamat],
		                      "batas_u"=>$row[batas_u],
		                      "batas_s"=>$row[batas_s],
		                      "batas_t"=>$row[batas_t],
		                      "batas_b"=>$row[batas_b],
		                      "jns_trn"=>$row[jns_trn],
		                      "sumber"=>$row[sumber],
		                      "dari"=>$row[dari],
		                      "dasar_hrg"=>$row[dasar_hrg],
		                      "no_dana"=>$row[no_dana],
		                      "tgl_dana"=>$row[tgl_dana],
		                      "surat1"=>$row[surat1],
		                      "surat2"=>$row[surat2],
		                      "surat3"=>$row[surat3],
		                      "rph_m2"=>$row[rph_m2],
		                      "unit_pmk"=>$row[unit_pmk],
		                      "alm_pmk"=>$row[alm_pmk],
		                      "catatan"=>$row[catatan],
		                      "tgl_prl"=>$row[tgl_prl],
		                      "tgl_buku"=>$row[tgl_buku],
		                      "rphwajar"=>$row[rphwajar],
		                      "rphnjop"=>$row[rphnjop],
		                      "status"=>$row[status],
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   
		   $db->insert('aset.tm_ktnh',$hasil[$idx]);
		   $idx++;
		 }
	    
		 $db->commit();
		 $x=array("namatabel"=>"tm_ktnh","jumlah"=>$numrows);
	     return $x;
	     //return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function insertTMKEL($userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $query = "select * from t_kel";	 
		 $result=mysql_query($query);
		 $numrows = mysql_num_rows($result);
		 $idx=0;
		if($numrows>0){
		   $this->deleteTable('aset.tm_kel');
		}
		 $db->beginTransaction();
		 while($row=mysql_fetch_array($result)){
		   
	       $hasil[$idx]=array("kd_gol"=>$row[kd_gol],
		                      "kd_bid"=>$row[kd_bid],
		                      "kd_kel"=>$row[kd_kel],
		                      "ur_kel"=>$row[ur_kel],		                      
		                      "kd_kelbrg"=>$row[kd_kelbrg],		                      
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   
		   $db->insert('aset.tm_kel',$hasil[$idx]);
		   $idx++;
		 }
	    
		 $db->commit();
		 $x=array("namatabel"=>"tm_kel","jumlah"=>$numrows);
	     return $x;
	     //return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function insertTMPOSTAS($userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $query = "select * from t_postas";	 
		 $result=mysql_query($query);
		 $numrows = mysql_num_rows($result);
		 $idx=0;
		if($numrows>0){
		   $this->deleteTable('aset.tm_postas');
		}
		 $db->beginTransaction();
		 while($row=mysql_fetch_array($result)){
		   
	       $hasil[$idx]=array("kdbkpk"=>$row[kdbkpk],
		                      "kddk"=>$row[kddk],
		                      "kdperk"=>$row[kdperk],
		                      "nmperk"=>$row[nmperk],		
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   
		   $db->insert('aset.tm_postas',$hasil[$idx]);
		   $idx++;
		 }
	    
		 $db->commit();
		 $x=array("namatabel"=>"tm_postas","jumlah"=>$numrows);
	     return $x;
	     //return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function insertTMCROLEH($userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $query = "select * from t_croleh";	 
		 $result=mysql_query($query);
		 $numrows = mysql_num_rows($result);
		 $idx=0;
		if($numrows>0){
		   $this->deleteTable('aset.tm_croleh');
		}
		 $db->beginTransaction();
		 while($row=mysql_fetch_array($result)){
		   
	       $hasil[$idx]=array("jns_trn"=>$row[jns_trn],
		                      "ur_trn"=>$row[ur_trn],		                     	
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   
		   $db->insert('aset.tm_croleh',$hasil[$idx]);
		   $idx++;
		 }
	    
		 $db->commit();
		 $x=array("namatabel"=>"tm_croleh","jumlah"=>$numrows);
	     return $x;
	     //return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function insertTMTTD($userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $query = "select * from t_ttd";	 
		 $result=mysql_query($query);
		 $numrows = mysql_num_rows($result);
		 $idx=0;
		if($numrows>0){
		   $this->deleteTable('aset.tm_ttd');
		}
		 $db->beginTransaction();
		 while($row=mysql_fetch_array($result)){
		   
	       $hasil[$idx]=array("kd_lokasi"=>$row[kd_lokasi],
		                      "kota"=>$row[kota],		                     	
		                      "tanggal"=>$row[tanggal],		                     	
		                      "nip"=>$row[nip],		                     	
		                      "nama"=>$row[nama],		                     	
		                      "jabatan"=>$row[jabatan],		                     	
		                      "nip2"=>$row[nip2],		                     	
		                      "nama2"=>$row[nama2],		                     	
		                      "jabatan2"=>$row[jabatan2],		                     	
		                      "tgl_isi"=>$row[tgl_isi],		                     	
		                      "tgl_setuju"=>$row[tgl_setuju],		                     	
		                      "unit"=>$row[unit],		                     	
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   
		   $db->insert('aset.tm_ttd',$hasil[$idx]);
		   $idx++;
		 }
	    
		 $db->commit();
		 $x=array("namatabel"=>"tm_ttd","jumlah"=>$numrows);
	     return $x;
	     //return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function insertTMSSKEL($userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $query = "select * from t_sskel";	 
		 $result=mysql_query($query);
		 $numrows = mysql_num_rows($result);
		 $idx=0;
		if($numrows>0){
		   $this->deleteTable('aset.tm_sskel');
		}
		 $db->beginTransaction();
		 while($row=mysql_fetch_array($result)){
		   
	       $hasil[$idx]=array("kd_gol"=>$row[kd_gol],
		                      "kd_bid"=>$row[kd_bid],		                     	
		                      "kd_kel"=>$row[kd_kel],		                     	
		                      "kd_skel"=>$row[kd_skel],		                     	
		                      "kd_sskel"=>$row[kd_sskel],		                     	
		                      "satuan"=>$row[satuan],		                     	
		                      "ur_sskel"=>$row[ur_sskel],		                     	
		                      "kd_perk"=>$row[kd_perk],		                     	
		                      "kd_brg"=>$row[kd_brg],	
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   
		   $db->insert('aset.tm_sskel',$hasil[$idx]);
		   $idx++;
		 }
	    
		 $db->commit();
		 $x=array("namatabel"=>"tm_sskel","jumlah"=>$numrows);
	     return $x;
	     //return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function insertTMKANGK($userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $query = "select * from t_kangk";	 
		 $result=mysql_query($query);
		 $numrows = mysql_num_rows($result);
		 $idx=0;
		if($numrows>0){
		   $this->deleteTable('aset.tm_kangk');
		}
		 $db->beginTransaction();
		 //echo "row : ".$numrows;
		//print_r(mysql_fetch_array($result));
		$idx=1;
		 while($row=mysql_fetch_array($result)){
		   if($row[tgl_dana]=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $row[tgl_dana]=null;
		   }
		   if($row[tgl_prl]=='0000-00-00'){
		     $row[tgl_prl]=null;
		   }
		   
		   if($row[tgl_buku]=='0000-00-00'){
		     $row[tgl_buku]=null;
		   }
		   
	       $hasil[$idx]=array("kd_lokasi"=>$row[kd_lokasi],
		                      "kd_brg"=>$row[kd_brg],
		                      "no_aset"=>$row[no_aset],
		                      "kuantitas"=>$row[kuantitas],		                      
		                      "no_kib"=>$row[no_kib],
		                      "merk"=>$row[merk],
		                      "tipe"=>$row[tipe],
		                      "pabrik"=>$row[pabrik],
		                      "thn_rakit"=>$row[thn_rakit],
		                      "thn_buat"=>$row[thn_buat],
		                      "negara"=>$row[negara],
		                      "muat"=>$row[muat],
		                      "bobot"=>$row[bobot],
		                      "daya"=>$row[daya],
		                      "msn_gerak"=>$row[msn_gerak],
		                      "jml_msn"=>$row[jml_msn],
		                      "bhn_bakar"=>$row[bhn_bakar],
		                      "no_mesin"=>$row[no_mesin],
		                      "no_rangka"=>$row[no_rangka],
		                      "no_polisi"=>$row[no_polisi],
		                      "no_bpkb"=>$row[no_bpkb],
		                      "lengkap1"=>$row[lengkap1],
		                      "lengkap2"=>$row[lengkap2],
		                      "lengkap3"=>$row[lengkap3],
		                      "jns_trn"=>$row[jns_trn],
		                      "dari"=>$row[dari],
		                      "tgl_prl"=>$row[tgl_prl],
		                      "rph_aset"=>$row[rph_aset],
		                      "dasar_hrg"=>$row[dasar_hrg],
		                      "sumber"=>$row[sumber],
		                      "no_dana"=>$row[no_dana],
		                      "tgl_dana"=>$row[tgl_dana],
		                      "unit_pmk"=>$row[unit_pmk],
		                      "alm_pmk"=>$row[alm_pmk],
		                      "catatan"=>$row[catatan],
		                      "kondisi"=>$row[kondisi],
		                      "tgl_buku"=>$row[tgl_buku],
		                      "rphwajar"=>$row[rphwajar],
		                      "status"=>$row[status],
		                      "cad1"=>$row[cad1],
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   
		   $db->insert('aset.tm_kangk',$hasil[$idx]);
		   $idx++;
		 }
	    
		 $db->commit();
		 $x=array("namatabel"=>"tm_kangk","jumlah"=>$numrows);
	     return $x;
	     //return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
		public function insertTMPEBIN($userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $query = "select * from t_pebin";	 
		 $result=mysql_query($query);
		 $numrows = mysql_num_rows($result);
		 $idx=0;
		if($numrows>0){
		   $this->deleteTable('aset.tm_pebin');
		}
		 $db->beginTransaction();
		 while($row=mysql_fetch_array($result)){
		   
	       $hasil[$idx]=array("kd_pebin"=>$row[kd_pebin],
		                      "ur_pebin"=>$row[ur_pebin],
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   
		   $db->insert('aset.tm_pebin',$hasil[$idx]);
		   $idx++;
		 }
	    
		 $db->commit();
		 $x=array("namatabel"=>"tm_pebin","jumlah"=>$numrows);
	     return $x;
	     //return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function insertTMSKEL($userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $query = "select * from t_skel";	 
		 $result=mysql_query($query);
		 $numrows = mysql_num_rows($result);
		 $idx=0;
		if($numrows>0){
		   $this->deleteTable('aset.tm_skel');
		}
		 $db->beginTransaction();
		 while($row=mysql_fetch_array($result)){
		   
	       $hasil[$idx]=array("kd_gol"=>$row[kd_gol],
		                      "kd_bid"=>$row[kd_bid],
		                      "kd_kel"=>$row[kd_kel],
		                      "kd_skel"=>$row[kd_skel],
		                      "ur_skel"=>$row[ur_skel],
		                      "kd_skelbrg"=>$row[kd_skelbrg],		                    
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   
		   $db->insert('aset.tm_skel',$hasil[$idx]);
		   $idx++;
		 }
	    
		 $db->commit();
		 $x=array("namatabel"=>"tm_skel","jumlah"=>$numrows);
	     return $x;
	     //return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function insertTMKSENJ($userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $query = "select * from t_ksenj";	 
		 $result=mysql_query($query);
		 $numrows = mysql_num_rows($result);
		 $idx=0;
		if($numrows>0){
		   $this->deleteTable('aset.tm_ksenj');
		}
		 $db->beginTransaction();
		 //echo "row : ".$numrows;
		//print_r(mysql_fetch_array($result));
		$idx=1;
		 while($row=mysql_fetch_array($result)){
		   if($row[tgl_surat]=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $row[tgl_surat]=null;
		   }
		   if($row[tgl_dana]=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $row[tgl_dana]=null;
		   }
		   
		   
	       $hasil[$idx]=array("kd_lokasi"=>$row[kd_lokasi],
		                      "kd_brg"=>$row[kd_brg],
		                      "no_aset"=>$row[no_aset],
		                      "rph_aset"=>$row[rph_aset],
							  "no_kib"=>$row[no_kib],
		                      "kuantitas"=>$row[kuantitas],	
		                      "nama"=>$row[nama],
		                      "merk"=>$row[merk],
		                      "tipe"=>$row[tipe],
		                      "kaliber"=>$row[kaliber],
		                      "no_pabrik"=>$row[no_pabrik],
		                      "thn_buat"=>$row[thn_buat],
		                      "surat"=>$row[surat],
		                      "tgl_surat"=>$row[tgl_surat],
		                      "lengkap1"=>$row[lengkap1],
		                      "lengkap2"=>$row[lengkap2],
		                      "lengkap3"=>$row[lengkap3],
		                      "jns_trn"=>$row[jns_trn],
		                      "dari"=>$row[dari],
		                      "tgl_prl"=>$row[tgl_prl],
		                      "kondisi"=>$row[kondisi],
		                      "dasar_hrg"=>$row[dasar_hrg],
		                      "sumber"=>$row[sumber],
		                      "no_dana"=>$row[no_dana],
		                      "tgl_dana"=>$row[tgl_dana],
		                      "unit_pmk"=>$row[unit_pmk],
		                      "alm_pmk"=>$row[alm_pmk],
		                      "catatan"=>$row[catatan],
		                      "tgl_buku"=>$row[tgl_buku],
		                      "rphwajar"=>$row[rphwajar],
		                      "status"=>$row[status],		                      
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   
		   $db->insert('aset.tm_ksenj',$hasil[$idx]);
		   $idx++;
		 }
	    
		 $db->commit();
		 $x=array("namatabel"=>"tm_ksenj","jumlah"=>$numrows);
	     return $x;
	     //return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function insertTMWILAYAH($userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $query = "select * from t_wilayah";	 
		 $result=mysql_query($query);
		 $numrows = mysql_num_rows($result);
		 $idx=0;
		if($numrows>0){
		   $this->deleteTable('aset.tm_wilayah');
		}
		 $db->beginTransaction();
		 while($row=mysql_fetch_array($result)){
		   
	       $hasil[$idx]=array("kd_wilayah"=>$row[kd_wilayah],
		                      "ur_wilayah"=>$row[ur_wilayah],		                      		                    
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   
		   $db->insert('aset.tm_wilayah',$hasil[$idx]);
		   $idx++;
		 }
	    
		 $db->commit();
		 $x=array("namatabel"=>"tm_wilayah","jumlah"=>$numrows);
	     return $x;
	     //return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function insertTMLOGIN($userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $query = "select * from login";	 
		 $result=mysql_query($query);
		 $numrows = mysql_num_rows($result);
		 $idx=0;
		if($numrows>0){
		   $this->deleteTable('aset.tm_login');
		}
		 $db->beginTransaction();
		 //echo "row : ".$numrows;
		//print_r(mysql_fetch_array($result));
		$idx=1;
		 while($row=mysql_fetch_array($result)){
		 
	       $hasil[$idx]=array("nama"=>$row[nama],
		                      "nama_id"=>$row[nama_id],
		                      "password"=>$row[password],
		                      "level"=>$row[level],
							  "unit"=>$row[unit],
		                      "status"=>$row[status],	
		                      "kd_pebin"=>$row[kd_pebin],
		                      "kd_pbi"=>$row[kd_pbi],
		                      "kd_ppbi"=>$row[kd_ppbi],
		                      "kd_upb"=>$row[kd_upb],
		                      "kd_sub_upb"=>$row[kd_sub_upb],
		                      "kd_jk"=>$row[kd_jk],
		                      "kdlok"=>$row[kdlok],
		                      "kd_bapel"=>$row[kd_bapel],
		                      "kd_es1pel"=>$row[kd_es1pel],
		                      "kd_blu"=>$row[kd_blu],
		                      "kd_kpknl"=>$row[kd_kpknl],		                                         
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   
		   $db->insert('aset.tm_login',$hasil[$idx]);
		   $idx++;
		 }
	    
		 $db->commit();
		 $x=array("namatabel"=>"tm_login","jumlah"=>$numrows);
	     return $x;
	     //return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
	
	public function insertTMUPB($userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $query = "select * from t_upb";	 
		 $result=mysql_query($query);
		 $numrows = mysql_num_rows($result);
		 $idx=0;
		if($numrows>0){
		   $this->deleteTable('aset.tm_upb');
		}
		 $db->beginTransaction();
		 //echo "row : ".$numrows;
		//print_r(mysql_fetch_array($result));
		$idx=1;
		 while($row=mysql_fetch_array($result)){
		 
	       $hasil[$idx]=array("kd_pebin"=>$row[kd_pebin],
		                      "kd_pbi"=>$row[kd_pbi],
		                      "kd_ppbi"=>$row[kd_ppbi],
		                      "kd_upb"=>$row[kd_upb],
							  "kd_subupb"=>$row[kd_subupb],
		                      "kd_jk"=>$row[kd_jk],	
		                      "ur_upb"=>$row[ur_upb],
		                      "kdlok"=>$row[kdlok],		                      	                                         
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   
		   $db->insert('aset.tm_upb',$hasil[$idx]);
		   $idx++;
		 }
	    
		 $db->commit();
		 $x=array("namatabel"=>"tm_upb","jumlah"=>$numrows);
	     return $x;
	     //return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function insertTMSPM($userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $query = "select * from t_spm";	 
		 $result=mysql_query($query);
		 $numrows = mysql_num_rows($result);
		 $idx=0;
		if($numrows>0){
		   $this->deleteTable('aset.tm_spm');
		}
		 $db->beginTransaction();
		 //echo "row : ".$numrows;
		//print_r(mysql_fetch_array($result));
		$idx=1;
		 while($row=mysql_fetch_array($result)){
		 
	       $hasil[$idx]=array("kd_lokasi"=>$row[kd_lokasi],
		                      "no_sppa"=>$row[no_sppa],
		                      "no_sp2d"=>$row[no_sp2d],
		                      "bkpk"=>$row[bkpk],
							  "tgl_sp2d"=>$row[tgl_sp2d],
		                      "jml_spm"=>$row[jml_spm],	
		                      "kd_brg"=>$row[kd_brg],
		                      "no_aset"=>$row[no_aset],		                      	                                         
		                      "jns_trn"=>$row[jns_trn],		                      	                                         
		                      "tgl_buku"=>$row[tgl_buku],		                      	                                         
		                      "thn_ang"=>$row[thn_ang],	                                         
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   
		   $db->insert('aset.tm_spm',$hasil[$idx]);
		   $idx++;
		 }
	    
		 $db->commit();
		 $x=array("namatabel"=>"tm_spm","jumlah"=>$numrows);
	     return $x;
	     //return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function insertTMPERK($userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $query = "select * from t_perk";	 
		 $result=mysql_query($query);
		 $numrows = mysql_num_rows($result);
		 $idx=0;
		if($numrows>0){
		   $this->deleteTable('aset.tm_perk');
		}
		 $db->beginTransaction();
		 //echo "row : ".$numrows;
		//print_r(mysql_fetch_array($result));
		$idx=1;
		 while($row=mysql_fetch_array($result)){
		 
	       $hasil[$idx]=array("kd_perkd"=>$row[kd_perkd],
		                      "ur_perkd"=>$row[ur_perkd],
		                      "kd_perkk"=>$row[kd_perkk],
		                      "ur_perkk"=>$row[ur_perkk],							                                          
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   
		   $db->insert('aset.tm_perk',$hasil[$idx]);
		   $idx++;
		 }
	    
		 $db->commit();
		 $x=array("namatabel"=>"tm_perk","jumlah"=>$numrows);
	     return $x;
	     //return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function insertTMRUANG($userid) {
		set_time_limit(900);
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $query = "select * from t_ruang";	 
		 $result=mysql_query($query);
		 $numrows = mysql_num_rows($result);
		 $idx=0;
		if($numrows>0){
		   $this->deleteTable('simak.tm_ruang');
		}
		 $db->beginTransaction();
		 //echo "row : ".$numrows;
		//print_r(mysql_fetch_array($result));
		$idx=1;
		 while($row=mysql_fetch_array($result)){
		 
	       $hasil[$idx]=array("kd_lokasi"=>$row[kd_lokasi],
		                      "kd_ruang"=>$row[kd_ruang],
		                      "ur_ruang"=>$row[ur_ruang],
		                      "pj_ruang"=>$row[pj_ruang],							                                          
		                      "nip_pjrug"=>$row[nip_pjrug],							                                          
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   
		   $db->insert('simak.tm_ruang',$hasil[$idx]);
		   $idx++;
		 }
	    
		 $db->commit();
		 $x=array("namatabel"=>"tm_ruang","jumlah"=>$numrows);
	     return $x;
	     //return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
	
	    public function insertTMMASTERU($userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $query = "select * from t_masteru";	 
		 $result=mysql_query($query);
		 $numrows = mysql_num_rows($result);
		 
		 $idx=0;
		if($numrows>0){
		   $this->deleteTable('aset.tm_masteru');
		}
		 $db->beginTransaction();
		 $id=1;
		  //$name = htmlspecialchars("Istisna� (Manufacturing Con   ",ENT_QUOTES);
		  //$var="Istisna� (Manufacturing Con   ";
		  //$name = str_replace('\&#12287;', '', $var);
		  //$name = $this->htmlallentities($var);
           //echo "hasil : ".$name."<br/>";
		 while($row=mysql_fetch_array($result)){
		   //echo "Index ke - : ".$id."<br/>";
		   if($row[tgl_dsr_mts]=='0000-00-00'){		  
		   $row[tgl_dsr_mts]=null;
		   }
		   if($row[tgl_buku]=='0000-00-00'){		  
		   $row[tgl_buku]=null;
		   }
		   if($row[tgl_perlh]=='0000-00-00'){		  
		   $row[tgl_perlh]=null;
		   }
		   //$name = $db->quoteInto($row[keterangan],"�");
		   //$name = $db->quote($row[keterangan],"�");
		  //$name = str_replace('\’', '', $row[keterangan]);
		  $keterangan = $this->htmlallentities($row[keterangan]);
		  $merk_type = $this->htmlallentities($row[merk_type]);
		  //echo "hasil : ".$name."<br/>";
	       $hasil[$idx]=array("thn_ang"=>$row[thn_ang],
		                      "periode"=>$row[periode],
		                      "kd_lokasi"=>$row[kd_lokasi],
		                      "no_sppa"=>$row[no_sppa],
		                      "kd_brg"=>$row[kd_brg],
		                      "no_aset"=>$row[no_aset],
		                      "tgl_perlh"=>$row[tgl_perlh],
		                      "tercatat"=>$row[tercatat],
		                      "kondisi"=>$row[kondisi],
		                      "tgl_buku"=>$row[tgl_buku],		                      
		                      "jns_trn"=>$row[jns_trn],		                      
		                      "dsr_hrg"=>$row[dsr_hrg],
		                      "kd_data"=>$row[kd_data],
		                      "flag_sap"=>$row[flag_sap],
		                      "kuantitas"=>$row[kuantitas],
		                      "rph_sat"=>$row[rph_sat],
		                      "rph_aset"=>$row[rph_aset],
		                      "flag_kor"=>$row[flag_kor],
		                      "keterangan"=>$keterangan,
		                      "merk_type"=>$merk_type,
		                      "asal_perlh"=>$row[asal_perlh],
		                      "no_bukti"=>$row[no_bukti],
		                      "no_dsr_mts"=>$row[no_dsr_mts],
		                      "tgl_dsr_mts"=>$row[tgl_dsr_mts],
		                      "flag_ttp"=>$row[flag_ttp],
		                      "flag_krm"=>$row[flag_krm],
		                      "kdblu"=>$row[kdblu],
		                      "setatus"=>$row[setatus],
		                      "noreg"=>$row[noreg],
		                      "kdbapel"=>$row[kdbapel],
		                      "kdkpknl"=>$row[kdkpknl],
		                      "umeko"=>$row[umeko],
		                      "rph_res"=>$row[rph_res],
		                      "kdkppn"=>$row[kdkppn],
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   
		   $db->insert('aset.tm_masteru',$hasil[$idx]);
		   $idx++;
		   $id++;
		 }
	    
		 $db->commit();
		 $x=array("namatabel"=>"tm_masteru","jumlah"=>$numrows);
	     return $x;
	     //return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function cekTMDIL($kdlok,$kdpem,$kdbrg,$noaset){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  //$db->setFetchMode(Zend_Db::FETCH_OBJ); 
	 $sql = "select count(*) from aset.tm_dil where kd_lokasi='".$kdlok."'
	          and kd_pemilik='".$kdpem."' and kd_brg='".$kdbrg."' and no_aset='".$noaset."' ";
	  //echo $sql."\n";
	  $result = $db->fetchOne($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
	}
	public function insertTMDILfromDBF(array $data,$userid) {
		//echo "Insert tm dil";
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $idx=0;
		 $jml=0;
		// if(count($data)>0){
		   // $this->deleteTable('aset.tm_dil');
		// }
		 $db->beginTransaction();
		 for($i=0;$i<count($data);$i++){
		  if(strlen(trim($data[$i]['no_aset']))==0){
		     $data[$i]['no_aset']=0;
		   }
		    unset($where);
		 $cektmdil=$this->cekTMDIL($data[$i]['kd_lokasi'],$data[$i]['kd_pemilik'],$data[$i]['kd_brg'],$data[$i]['no_aset']);
	       $hasil=array("kd_pemilik"=>$data[$i]['kd_pemilik'],
		                      "kd_lokasi"=>$data[$i]['kd_lokasi'],
		                      "kd_brg"=>$data[$i]['kd_brg'],
		                      "no_aset"=>$data[$i]['no_aset'],
		                      "lok_fisik"=>$data[$i]['lok_fisik'],
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   
		   
		   if($cektmdil==0){
		   //echo "Insert\n";
		   $db->insert('aset.tm_dil',$hasil);
		   $jml++;
		   }
		   if($cektmdil>0){
            //echo "Update\n";
             //echo "Data user id : ".$hasil['i_entry']."\n";			
		     $where[]="kd_lokasi='".$data[$i]['kd_lokasi']."'";	     
		     $where[]="kd_pemilik='".$data[$i]['kd_pemilik']."'";
		     $where[]="kd_brg='".$data[$i]['kd_brg']."'";	     
		     $where[]="no_aset='".$data[$i]['no_aset']."'";	    
             //print_r($where);			 
	         $db->update('aset.tm_dil',$hasil,$where);
			 $jml++;
		   }
		   $idx++;
		 }
	     
		 $db->commit();
		 // $x=array("namatabel"=>"tm_dil","jumlah"=>count($data));
	     // return $x;
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
		 $this->tuliskelog($e->getMessage().'\n');
	     return 'gagal';
	   }
	}
	public function cekTMDIR($kdlok,$kdpem,$kdbrg,$noaset){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  //$db->setFetchMode(Zend_Db::FETCH_OBJ); 
	 $sql = "select count(*) from aset.tm_dir where kd_lokasi='".$kdlok."'
	          and kd_pemilik='".$kdpem."' and kd_brg='".$kdbrg."' and no_aset='".$noaset."' ";
	  //echo $sql."\n";
	  $result = $db->fetchOne($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
	}	
      public function insertTMDIRfromDBF(array $data,$userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $idx=0;
		 $jml=0;
		// if(count($data)>0){
		   // $this->deleteTable('aset.tm_dir');
		// }
		 $db->beginTransaction();
		 for($i=0;$i<count($data);$i++){
		  if(strlen(trim($data[$i]['no_aset']))==0){
		     $data[$i]['no_aset']=0;
		   }
		   unset($where);
		  $cektmdir=$this->cekTMDIR($data[$i]['kd_lokasi'],$data[$i]['kd_pemilik'],$data[$i]['kd_brg'],$data[$i]['no_aset']);
	       $hasil=array("kd_lokasi"=>$data[$i]['kd_lokasi'],
		                      "kd_pemilik"=>$data[$i]['kd_pemillik'],
		                      "kd_brg"=>$data[$i]['kd_brg'],
		                      "no_aset"=>$data[$i]['no_aset'],
		                      "kd_ruang"=>$data[$i]['kd_ruang'],
							  "i_entry"=>"system",
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   
		   
           if($cektmdil==0){
		   //echo "Insert\n";
		   $db->insert('aset.tm_dir',$hasil);
		   $jml++;
		   }
		   if($cektmdil>0){
            //echo "Update\n";
             //echo "Data user id : ".$hasil['i_entry']."\n";			
		     $where[]="kd_lokasi='".$data[$i]['kd_lokasi']."'";	     
		     $where[]="kd_pemilik='".$data[$i]['kd_pemilik']."'";
		     $where[]="kd_brg='".$data[$i]['kd_brg']."'";	     
		     $where[]="no_aset='".$data[$i]['no_aset']."'";	    
             //print_r($where);			 
	         $db->update('aset.tm_dir',$hasil,$where);
			 $jml++;
		   }		   
		   $idx++;
		 }
	     
		 $db->commit();
		 // $x=array("namatabel"=>"tm_dir","jumlah"=>count($data));
	     // return $x;
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
		 $this->tuliskelog($e->getMessage().'\n');
	     return 'gagal';
	   }
	}
	public function cekTMMASTERHM($thnang,$kdlokasi,$nosppa,$kdbrg,$noaset){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  //$db->setFetchMode(Zend_Db::FETCH_OBJ); 
	   if($no_aset==""){
		     $no_aset=0;
	    }
	  $sql = "select count(*) from aset.tm_masterhm where thn_ang='".$thnang."'
	          and kd_lokasi='".$kdlokasi."' and no_sppa='".$nosppa."' and kd_brg='".$kdbrg."' and no_aset='".$noaset."' ";
	  //echo $sql."\n";
	  $result = $db->fetchOne($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
	}
	  public function insertTMMASTERHMfromDBF(array $data,$userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	  
        //print_r($data);	   
	   try {
	    
		 $idx=0;
		 $jml=0;
		// if(count($data)>0){
		   // $this->deleteTable('aset.tm_masterhm');
		// }
		 $db->beginTransaction();
		 //print_r($data);
		
		 for($i=0;$i<count($data);$i++){
		 //for($i=0;$i<3;$i++){
		 unset($where);
		 
		 //echo "cek tm masterhm : ".$cektmmasterhm."\n";
	      if($data[$i]['tgl_dsr_mts']=='0000-00-00'){
		   
		   $data[$i]['tgl_dsr_mts']=null;
		   }
		    if(strlen(trim($data[$i]['tgl_dsr_mts']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_dsr_mts']=null;
		   }
		   if($data[$i]['tgl_perlh']=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_perlh']=null;
		   }
		    if(strlen(trim($data[$i]['tgl_perlh']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_perlh']=null;
		   }
		   if($data[$i]['tgl_buku']=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_buku']=null;
		   }
		    if(strlen(trim($data[$i]['tgl_buku']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_buku']=null;
		   }
		   if(strlen(trim($data[$i]['no_aset']))==0){
		     $data[$i]['no_aset']=0;
		   }
		   if(strlen(trim($data[$i]['kuantitas']))==0){
		     $data[$i]['kuantitas']=0;
		   }
		   if(strlen(trim($data[$i]['rph_aset']))==0){
		     $data[$i]['rph_aset']=0;
		   }
		   if(strlen(trim($data[$i]['rph_sat']))==0){
		     $data[$i]['rph_sat']=0;
		   }
		   if(strlen(trim($data[$i]['umeko']))==0){
		     $data[$i]['umeko']=0;
		   }
		   if(strlen(trim($data[$i]['rph_res']))==0){
		    $data[$i]['rph_res']=0;
		   }
		   if(strlen(trim($data[$i]['no_asetlm']))==0){
		     $data[$i]['no_asetlm']=0;
		   }
		   $cektmmasterhm=$this->cekTMMASTERHM($data[$i]['thn_ang'],$data[$i]['kd_lokasi'],$data[$i]['no_sppa'],$data[$i]['kd_brg'],$data[$i]['no_aset']);
	       $hasil=array("thn_ang"=>$data[$i]['thn_ang'],
		                      "periode"=>$data[$i]['periode'],
		                      "kd_lokasi"=>$data[$i]['kd_lokasi'],
		                      "no_sppa"=>$data[$i]['no_sppa'],
		                      "kd_brg"=>$data[$i]['kd_brg'],
		                      "no_aset"=>$data[$i]['no_aset'],
		                      "tgl_perlh"=>$data[$i]['tgl_perlh'],
		                      "tercatat"=>$data[$i]['tercatat'],
		                      "kondisi"=>$data[$i]['kondisi'],
		                      "tgl_buku"=>$data[$i]['tgl_buku'],
		                      "jns_trn"=>$data[$i]['jns_trn'],
		                      "dsr_hrg"=>$data[$i]['dsr_hrg'],
		                      "kd_data"=>$data[$i]['kd_data'],
		                      "flag_sap"=>$data[$i]['flag_sap'],
		                      "kuantitas"=>$data[$i]['kuantitas'],
		                      "rph_sat"=>$data[$i]['rph_sat'],
		                      "rph_aset"=>$data[$i]['rph_aset'],
		                      "flag_kor"=>$data[$i]['flag_kor'],
		                      "keterangan"=>$data[$i]['keterangan'],
		                      "merk_type"=>$data[$i]['merk_type'],
		                      "asal_perlh"=>$data[$i]['asal_perlh'],
		                      "no_bukti"=>$data[$i]['no_bukti'],
		                      "no_dsr_mts"=>$data[$i]['no_dsr_mts'],
		                      "tgl_dsr_mts"=>$data[$i]['tgl_dsr_mts'],
		                      "flag_ttp"=>$data[$i]['flag_ttp'],
		                      "flag_krm"=>$data[$i]['flag_krm'],
		                      "kdblu"=>$data[$i]['kdblu'],
		                      "setatus"=>$data[$i]['setatus'],
		                      "noreg"=>$data[$i]['noreg'],
		                      "kdbapel"=>$data[$i]['kdbapel'],
		                      "kdkpknl"=>$data[$i]['kdkpknl'],
		                      "umeko"=>$data[$i]['umeko'],
		                      "rph_res"=>$data[$i]['rph_res'],
		                      "kdkppn"=>$data[$i]['kdkppn'],
		                      "no_asetlm"=>$data[$i]['no_asetlm'],
		                      "kd_brglm"=>$data[$i]['kd_brglm'],
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
		   //echo "Cekt master : ".$cetmmasterhm."\n";
		   //print_r($hasil);
		   if($cektmmasterhm==0){
		  // echo "Insert\n";
		   $db->insert('aset.tm_masterhm',$hasil);
		   $jml++;
		   }
		   if($cektmmasterhm>0){
            //echo "Update\n";
             //echo "Data user id : ".$hasil['i_entry']."\n";			
		     $where[]="thn_ang='".$data[$i]['thn_ang']."'";	     
		     $where[]="kd_lokasi='".$data[$i]['kd_lokasi']."'";	     
		     $where[]="no_sppa='".$data[$i]['no_sppa']."'";	     
		     $where[]="kd_brg='".$data[$i]['kd_brg']."'";	     
		     $where[]="no_aset='".$data[$i]['no_aset']."'";	    
             //print_r($where);			 
	         $db->update('aset.tm_masterhm',$hasil,$where);
			 $jml++;
		   }
		   $idx++;
		 }
	     
		   
		 $db->commit();
		 
		 // $x=array("namatabel"=>"tm_masterhm","jumlah"=>$jml);
	     // return $x;
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
		 $this->tuliskelog($e->getMessage().'\n');
	     return 'gagal';
	   }
	}
	public function cekTMGOL($kdgol){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  //$db->setFetchMode(Zend_Db::FETCH_OBJ); 
	  $sql = "select count(*) from aset.tm_gol where kd_gol='".$kdgol."'";
	  //echo $sql."\n";
	  $result = $db->fetchOne($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
	}
	  public function insertTMGOLfromDBF(array $data,$userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $idx=0;
		 $jml=0;
		// if(count($data)>0){
		   // $this->deleteTable('aset.tm_masterhm');
		// }
		 $db->beginTransaction();
		 //print_r($data);
		 for($i=0;$i<count($data);$i++){
		 //for($i=0;$i<3;$i++){
		 unset($where);
		 $cektmgol=$this->cekTMGOL($data[$i]['kd_gol']);
	       $hasil=array("kd_gol"=>$data[$i]['kd_gol'],
		                      "ur_gol"=>$data[$i]['ur_gol'],
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
		   //echo "Cekt master : ".$cetmmasterhm."\n";
		   //print_r($hasil);
		   if($cektmgol==0){
		   //echo "Insert\n";
		   $db->insert('aset.tm_gol',$hasil);
		   $jml++;
		   }
		   if($cektmgol>0){
            //echo "Update\n";
             //echo "Data user id : ".$hasil['i_entry']."\n";			
		     $where[]="kd_gol='".$data[$i]['kd_gol']."'";	    
             //print_r($where);			 
	         $db->update('aset.tm_gol',$hasil,$where);
			 $jml++;
		   }
		   $idx++;
		 }
	     
		   
		 $db->commit();
		 
		 // $x=array("namatabel"=>"tm_gol","jumlah"=>$jml);
	     // return $x;
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
		 $this->tuliskelog($e->getMessage().'\n');
	     return 'gagal';
	   }
	}	
	public function cekTMBID($kdgol,$kdbid){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  //$db->setFetchMode(Zend_Db::FETCH_OBJ); 
	  $sql = "select count(*) from aset.tm_bid where kd_gol='".$kdgol."' and kd_bid = '".$kdbid."'";
	  //echo $sql."\n";
	  $result = $db->fetchOne($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
	}
	  public function insertTMBIDfromDBF(array $data,$userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $idx=0;
		 $jml=0;
		// if(count($data)>0){
		   // $this->deleteTable('aset.tm_masterhm');
		// }
		 $db->beginTransaction();
		 //print_r($data);
		 for($i=0;$i<count($data);$i++){
		 //for($i=0;$i<3;$i++){
		 unset($where);
		 $cektmbid=$this->cekTMBID($data[$i]['kd_gol'],$data[$i]['kd_bid']);
	       $hasil=array("kd_gol"=>$data[$i]['kd_gol'],
					    "kd_bid"=>$data[$i]['kd_bid'],
					    "ur_bid"=>$data[$i]['ur_bid'],
					    "kd_bidbrg"=>$data[$i]['kd_bidbrg'],
					    "i_entry"=>$userid,
					    "d_entry"=>date("Y-m-d")							  
		                      ); 
		   //echo "Cekt master : ".$cetmmasterhm."\n";
		   //print_r($hasil);
		   if($cektmbid==0){
		   //echo "Insert\n";
		   $db->insert('aset.tm_bid',$hasil);
		   $jml++;
		   }
		   if($cektmbid>0){
            //echo "Update\n";
             //echo "Data user id : ".$hasil['i_entry']."\n";			
		     $where[]="kd_gol='".$data[$i]['kd_gol']."'";	    
		     $where[]="kd_bid='".$data[$i]['kd_bid']."'";	    
             //print_r($where);			 
	         $db->update('aset.tm_bid',$hasil,$where);
			 $jml++;
		   }
		   $idx++;
		 }
	     
		   
		 $db->commit();
		 
		 // $x=array("namatabel"=>"tm_bid","jumlah"=>$jml);
	     // return $x;
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
		 $this->tuliskelog($e->getMessage().'\n');
	     return 'gagal';
	   }
	}	
	public function cekTMCROLEH($jnstrn){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  //$db->setFetchMode(Zend_Db::FETCH_OBJ); 
	  $sql = "select count(*) from aset.tm_croleh where jns_trn='".$jnstrn."'";
	  //echo $sql."\n";
	  $result = $db->fetchOne($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
	}
	  public function insertTMCROLEHfromDBF(array $data,$userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $idx=0;
		 $jml=0;
		// if(count($data)>0){
		   // $this->deleteTable('aset.tm_masterhm');
		// }
		 $db->beginTransaction();
		 //print_r($data);
		 for($i=0;$i<count($data);$i++){
		 //for($i=0;$i<3;$i++){
		 unset($where);
		 $cektmcroleh=$this->cekTMCROLEH($data[$i]['jns_trn']);
	       $hasil=array("jns_trn"=>$data[$i]['jns_trn'],
					    "ur_trn"=>$data[$i]['ur_trn'],
					    "i_entry"=>$userid,
					    "d_entry"=>date("Y-m-d")							  
		                      ); 
		   //echo "Cekt master : ".$cetmmasterhm."\n";
		   //print_r($hasil);
		   if($cektmcroleh==0){
		   //echo "Insert\n";
		   $db->insert('aset.tm_croleh',$hasil);
		   $jml++;
		   }
		   if($cektmcroleh>0){
            //echo "Update\n";
             //echo "Data user id : ".$hasil['i_entry']."\n";			
		     $where[]="jns_trn='".$data[$i]['jns_trn']."'";	 
             //print_r($where);			 
	         $db->update('aset.tm_croleh',$hasil,$where);
			 $jml++;
		   }
		   $idx++;
		 }
	     
		   
		 $db->commit();
		 
		 // $x=array("namatabel"=>"tm_croleh","jumlah"=>$jml);
	     // return $x;
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
		 $this->tuliskelog($e->getMessage().'\n');
	     return 'gagal';
	   }
	}		
	public function cekTMKBDG($kdlok,$kdbrg,$noaset){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  //$db->setFetchMode(Zend_Db::FETCH_OBJ); 
	  $sql = "select count(*) from aset.tm_kbdg where kd_lokasi='".$kdlok."'
	          and kd_brg='".$kdbrg."' and no_aset='".$noaset."' ";
	  //echo $sql."\n";
	  $result = $db->fetchOne($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
	}
	public function insertTMKBDGfromDBF(array $data,$userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $idx=0;
		 $jml=0;
		// if(count($data)>0){
		   // $this->deleteTable('aset.tm_kbdg');
		// }
		 $db->beginTransaction();
		 for($i=0;$i<count($data);$i++){
		  unset($where);
		  
	      if($data[$i]['tgl_imb']=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_imb']=null;
		   }
		    if(strlen(trim($data[$i]['tgl_imb']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_imb']=null;
		   }
		   if($data[$i]['tgl_dana']=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_dana']=null;
		   }
		    if(strlen(trim($data[$i]['tgl_dana']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_dana']=null;
		   }
		   if($data[$i]['tgl_buku']=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_buku']=null;
		   }
		    if(strlen(trim($data[$i]['tgl_buku']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_buku']=null;
		   }
		     if(strlen(trim($data[$i]['no_aset']))==0){
		     $data[$i]['no_aset']=0;
		   }
		   if(strlen(trim($data[$i]['rph_aset']))==0){
		     $data[$i]['rph_aset']=0;
		   }
		   if(strlen(trim($data[$i]['kuantitas']))==0){
		     $data[$i]['kuantitas']=0;
		   }
		   if(strlen(trim($data[$i]['no_kib']))==0){
		     $data[$i]['no_kib']=0;
		   } 
		   if(strlen(trim($data[$i]['luas_bdg']))==0){
		     $data[$i]['luas_bdg']=0;
		   }		   
		   if(strlen(trim($data[$i]['jml_lt']))==0){
		     $data[$i]['jml_lt']=0;
		   }
		   if(strlen(trim($data[$i]['no_kibtnh']))==0){
		     $data[$i]['no_kibtnh']=0;
		   }
		   if(strlen(trim($data[$i]['rph']))==0){
		     $data[$i]['rph']=0;
		   }
		   if(strlen(trim($data[$i]['rphwajar']))==0){
		     $data[$i]['rphwajar']=0;
		   }
		   if(strlen(trim($data[$i]['rphnjop']))==0){
		     $data[$i]['rphnjop']=0;
		   }
		   if(strlen(trim($data[$i]['luas_dsr']))==0){
		     $data[$i]['luas_dsr']=0;
		   }
		   $cektmkbdg=$this->cekTMKBDG($data[$i]['kd_lokasi'],$data[$i]['kd_brg'],$data[$i]['no_aset']);
	       $hasil=array("kd_lokasi"=>$data[$i]['kd_lokasi'],
		                      "kd_brg"=>$data[$i]['kd_brg'],
		                      "no_aset"=>$data[$i]['no_aset'],
		                      "kuantitas"=>$data[$i]['kuantitas'],
		                      "rph_aset"=>$data[$i]['rph_aset'],
		                      "no_kib"=>$data[$i]['no_kib'],
		                      "luas_bdg"=>$data[$i]['luas_bdg'],
		                      "jml_lt"=>$data[$i]['jml_lt'],
		                      "tipe"=>$data[$i]['tipe'],
		                      "thn_sls"=>$data[$i]['thn_sls'],
		                      "thn_pakai"=>$data[$i]['thn_pakai'],
		                      "no_imb"=>$data[$i]['no_imb'],
		                      "tgl_imb"=>$data[$i]['tgl_imb'],
		                      "kd_prov"=>$data[$i]['kd_prov'],
		                      "kd_kab"=>$data[$i]['kd_kab'],
		                      "kd_kec"=>$data[$i]['kd_kec'],
		                      "kd_kel"=>$data[$i]['kd_kel'],
		                      "alamat"=>$data[$i]['alamat'],
		                      "kd_rtrw"=>$data[$i]['kd_rtrw'],
		                      "no_kibtnh"=>$data[$i]['no_kibtnh'],
		                      "jns_trn"=>$data[$i]['jns_trn'],
		                      "dari"=>$data[$i]['dari'],
		                      "tgl_prl"=>$data[$i]['tgl_prl'],
		                      "kondisi"=>$data[$i]['kondisi'],
		                      "rph"=>$data[$i]['rph'],
		                      "dasar_hrg"=>$data[$i]['dasar_hrg'],
		                      "sumber"=>$data[$i]['sumber'],
		                      "no_dana"=>$data[$i]['no_dana'],
		                      "tgl_dana"=>$data[$i]['tgl_dana'],
		                      "unit_pmk"=>$data[$i]['unit_pmk'],
		                      "alm_pmk"=>$data[$i]['alm_pmk'],
		                      "catatan"=>$data[$i]['catatan'],
		                      "tgl_buku"=>$data[$i]['tgl_buku'],
		                      "rphwajar"=>$data[$i]['rphwajar'],
		                      "rphnjop"=>$data[$i]['rphnjop'],
		                      "status"=>$data[$i]['status'],
		                      "cad1"=>$data[$i]['cad1'],
		                      "luas_dsr"=>$data[$i]['luas_dsr'],
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		  if($cektmkbdg==0){ 
		   $db->insert('aset.tm_kbdg',$hasil);
		  $jml++;
		   }
		   if($cektmkbdg>0){
            //echo "Update\n";
             //echo "Data user id : ".$hasil['i_entry']."\n";			
		     $where[]="kd_lokasi='".$data[$i]['kd_lokasi']."'";	 
		     $where[]="kd_brg='".$data[$i]['kd_brg']."'";	 
		     $where[]="no_aset='".$data[$i]['no_aset']."'";	 
             //print_r($where);			 
	         $db->update('aset.tm_kbdg',$hasil,$where);
			 $jml++;
		   }
		   $idx++;
		 }
	     
		 $db->commit();
		 // $x=array("namatabel"=>"tm_kbdg","jumlah"=>$jml);
	     // return $x;
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
		 $this->tuliskelog($e->getMessage().'\n');
	     return 'gagal';
	   }
	}
	public function cekTMKTNH($kdlok,$kdbrg,$noaset){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  //$db->setFetchMode(Zend_Db::FETCH_OBJ); 
	  $sql = "select count(*) from aset.tm_ktnh where kd_lokasi='".$kdlok."'
	          and kd_brg='".$kdbrg."' and no_aset='".$noaset."' ";
	  //echo $sql."\n";
	  $result = $db->fetchOne($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
	}
    public function insertTMKTNHfromDBF(array $data,$userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $idx=0;
		 $jml=0;
		// if(count($data)>0){
		   // $this->deleteTable('aset.tm_ktnh');
		// }
		//print_r($data);
		// echo "Jumlah data : ".count($data);
		 $db->beginTransaction();
		 for($i=0;$i<count($data);$i++){
		  unset($where);
		  
	      if($data[$i]['tgl_dana']=='0000-00-00'){
		  
		   $data[$i]['tgl_dana']=null;
		   }
		    if(strlen(trim($data[$i]['tgl_dana']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_dana']=null;
		   }
		    if($data[$i]['tgl_buku']=='0000-00-00'){
		   
		   $data[$i]['tgl_buku']=null;
		   }
		    if(strlen(trim($data[$i]['tgl_buku']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_buku']=null;
		   }
		   if(strlen(trim($data[$i]['no_aset']))==0){
		     $data[$i]['no_aset']=0;
		   }
		   if(strlen(trim($data[$i]['kuantitas']))==0){
		     $data[$i]['kuantitas']=0;
		   }
		   if(strlen(trim($data[$i]['rph_aset']))==0){
		     $data[$i]['rph_aset']=0;
		   }
		   if(strlen(trim($data[$i]['no_kib']))==0){
		     $data[$i]['no_kib']=0;
		   }
		   if(strlen(trim($data[$i]['luas_tnhs']))==0){
		     $data[$i]['luas_tnhs']=0;
		   }
		   if(strlen(trim($data[$i]['luas_tnhb']))==0){
		     $data[$i]['luas_tnhb']=0;
		   }
		   if(strlen(trim($data[$i]['luas_tnhl']))==0){
		     $data[$i]['luas_tnhl']=0;
		   }
		   if(strlen(trim($data[$i]['luas_tnhk']))==0){
		     $data[$i]['luas_tnhk']=0;
		   }
		   if(strlen(trim($data[$i]['rphwajar']))==0){
		     $data[$i]['rphwajar']=0;
		   }
		   if(strlen(trim($data[$i]['rphnjop']))==0){
		     $data[$i]['rphnjop']=0;
		   }
	       $hasil=array("kd_lokasi"=>$data[$i]['kd_lokasi'],
		                      "kd_brg"=>$data[$i]['kd_brg'],
		                      "no_aset"=>$data[$i]['no_aset'],
		                      "kuantitas"=>$data[$i]['kuantitas'],
		                      "rph_aset"=>$data[$i]['rph_aset'],
		                      "no_kib"=>$data[$i]['no_kib'],
		                      "luas_tnhs"=>$data[$i]['luas_tnhs'],
		                      "luas_tnhb"=>$data[$i]['luas_tnhb'],
		                      "luas_tnhl"=>$data[$i]['luas_tnhl'],
		                      "luas_tnhk"=>$data[$i]['luas_tnhk'],
		                      "kd_prov"=>$data[$i]['kd_prov'],
		                      "kd_kab"=>$data[$i]['kd_kab'],
		                      "kd_kec"=>$data[$i]['kd_kec'],
		                      "kd_kel"=>$data[$i]['kd_kel'],
		                      "kd_rtrw"=>$data[$i]['kd_rtrw'],
		                      "alamat"=>$data[$i]['alamat'],
		                      "batas_u"=>$data[$i]['batas_u'],
		                      "batas_s"=>$data[$i]['batas_s'],
		                      "batas_t"=>$data[$i]['batas_t'],
		                      "batas_b"=>$data[$i]['batas_b'],
		                      "jns_trn"=>$data[$i]['jns_trn'],
		                      "sumber"=>$data[$i]['sumber'],
		                      "dari"=>$data[$i]['dari'],
		                      "dasar_hrg"=>$data[$i]['dasar_hrg'],
		                      "no_dana"=>$data[$i]['no_dana'],
		                      "tgl_dana"=>$data[$i]['tgl_dana'],
		                      "surat1"=>$data[$i]['surat1'],
		                      "surat2"=>$data[$i]['surat2'],
		                      "surat3"=>$data[$i]['surat3'],
		                      "rph_m2"=>$data[$i]['rph_m2'],
		                      "unit_pmk"=>$data[$i]['unit_pmk'],
		                      "alm_pmk"=>$data[$i]['alm_pmk'],
		                      "catatan"=>$data[$i]['catatan'],
		                      "tgl_prl"=>$data[$i]['tgl_prl'],
		                      "tgl_buku"=>$data[$i]['tgl_buku'],
		                      "rphwajar"=>$data[$i]['rphwajar'],
		                      "rphnjop"=>$data[$i]['rphnjop'],
		                      "status"=>$data[$i]['status'],
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   if($cektmktnh==0){
		   $db->insert('aset.tm_ktnh',$hasil);
		   $jml++;
		   }
		   if($cektmktnh>0){
		     //echo "Update\n";
             //echo "Data user id : ".$hasil['i_entry']."\n";			
		     $where[]="kd_lokasi='".$data[$i]['kd_lokasi']."'";	 
		     $where[]="kd_brg='".$data[$i]['kd_brg']."'";	 
		     $where[]="no_aset='".$data[$i]['no_aset']."'";	 
             //print_r($where);			 
	         $db->update('aset.tm_ktnh',$hasil,$where);
			 $jml++;
		   }
		   
		   $idx++;
		 }
	     
		 $db->commit();
		 // $x=array("namatabel"=>"tm_ktnh","jumlah"=>$jml);
	     // return $x;
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
		 $this->tuliskelog($e->getMessage().'\n');
	     return 'gagal';
	   }
	}
	public function cekTMKANGK($kdlok,$kdbrg,$noaset){
	  $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $sql = "select count(*) from aset.tm_kangk where kd_lokasi='".$kdlok."'
	          and kd_brg='".$kdbrg."' and no_aset='".$noaset."' ";
	  $result = $db->fetchOne($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
	}
	public function insertTMKANGKfromDBF(array $data,$userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $idx=0;
		 $jml=0;
		// if(count($data)>0){
		   // $this->deleteTable('aset.tm_kangk');
		// }
		 $db->beginTransaction();
		 for($i=0;$i<count($data);$i++){
		  //echo "tgl dana : ".$data['tgl_dana'];
		 unset($where);
		 $cektmkangk=$this->cekTMKANGK($data[$i]['kd_lokasi'],$data[$i]['kd_brg'],$data[$i]['no_aset']);
	      if($data[$i]['tgl_dana']=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_dana']=null;
		   }
		   if(strlen(trim($data[$i]['tgl_dana']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_dana']=null;
		   }
		   if($data[$i]['tgl_prl']=='0000-00-00'){
		     $data[$i]['tgl_prl']=null;
		   }
		    if(strlen(trim($data[$i]['tgl_prl']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_prl']=null;
		   }
		   if($data[$i]['tgl_buku']=='0000-00-00'){
		     $data[$i]['tgl_buku']=null;
		   }
		    if(strlen(trim($data[$i]['tgl_buku']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_buku']=null;
		   }
		   if(strlen(trim($data[$i]['no_aset']))==0){
		     $data[$i]['no_aset']=0;
		   }
		   if(strlen(trim($data[$i]['kuantitas']))==0){
		     $data[$i]['kuantitas']=0;
		   }
		   if(strlen(trim($data[$i]['no_kib']))==0){
		     $data[$i]['no_kib']=0;
		   } 
		   if(strlen(trim($data[$i]['jml_msn']))==0){
		     $data[$i]['jml_msn']=0;
		   }
		   if(strlen(trim($data[$i]['rph_aset']))==0){
		     $data[$i]['rph_aset']=0;
		   }
		   if(strlen(trim($data[$i]['rphwajar']))==0){
		     $data[$i]['rphwajar']=0;
		   }
		   $cektmktnh=$this->cekTMKTNH($data[$i]['kd_lokasi'],$data[$i]['kd_brg'],$data[$i]['no_aset']);
	       $hasil=array("kd_lokasi"=>$data[$i]['kd_lokasi'],
		                      "kd_brg"=>$data[$i]['kd_brg'],
		                      "no_aset"=>$data[$i]['no_aset'],
		                      "kuantitas"=>$data[$i]['kuantitas'],		                      
		                      "no_kib"=>$data[$i]['no_kib'],
		                      "merk"=>$data[$i]['merk'],
		                      "tipe"=>$data[$i]['tipe'],
		                      "pabrik"=>$data[$i]['pabrik'],
		                      "thn_rakit"=>$data[$i]['thn_rakit'],
		                      "thn_buat"=>$data[$i]['thn_buat'],
		                      "negara"=>$data[$i]['negara'],
		                      "muat"=>$data[$i]['muat'],
		                      "bobot"=>$data[$i]['bobot'],
		                      "daya"=>$data[$i]['daya'],
		                      "msn_gerak"=>$data[$i]['msn_gerak'],
		                      "jml_msn"=>$data[$i]['jml_msn'],
		                      "bhn_bakar"=>$data[$i]['bhn_bakar'],
		                      "no_mesin"=>$data[$i]['no_mesin'],
		                      "no_rangka"=>$data[$i]['no_rangka'],
		                      "no_polisi"=>$data[$i]['no_polisi'],
		                      "no_bpkb"=>$data[$i]['no_bpkb'],
		                      "lengkap1"=>$data[$i]['lengkap1'],
		                      "lengkap2"=>$data[$i]['lengkap2'],
		                      "lengkap3"=>$data[$i]['lengkap3'],
		                      "jns_trn"=>$data[$i]['jns_trn'],
		                      "dari"=>$data[$i]['dari'],
		                      "tgl_prl"=>$data[$i]['tgl_prl'],
		                      "rph_aset"=>$data[$i]['rph_aset'],
		                      "dasar_hrg"=>$data[$i]['dasar_hrg'],
		                      "sumber"=>$data[$i]['sumber'],
		                      "no_dana"=>$data[$i]['no_dana'],
		                      "tgl_dana"=>$data[$i]['tgl_dana'],
		                      "unit_pmk"=>$data[$i]['unit_pmk'],
		                      "alm_pmk"=>$data[$i]['alm_pmk'],
		                      "catatan"=>$data[$i]['catatan'],
		                      "kondisi"=>$data[$i]['kondisi'],
		                      "tgl_buku"=>$data[$i]['tgl_buku'],
		                      "rphwajar"=>$data[$i]['rphwajar'],
		                      "status"=>$data[$i]['status'],
		                      "cad1"=>$data[$i]['cad1'],
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   
		   //echo "cektmkangk : ".$cektmkangk."\n";
		   if($cektmkangk==0){
		   //echo "Insert\n";
		   $db->insert('aset.tm_kangk',$hasil);
		   $jml++;
		   }
		   if($cektmkangk>0){
            //echo "Update\n";
             //echo "Data user id : ".$hasil['i_entry']."\n";	            			 
		     $where[]="kd_lokasi='".$data[$i]['kd_lokasi']."'";	 
		     $where[]="kd_brg='".$data[$i]['kd_brg']."'";	 
		     $where[]="no_aset='".$data[$i]['no_aset']."'";	 
             //print_r($where);			 
	         $db->update('aset.tm_kangk',$hasil,$where);
			 $jml++;
		   }
		   $idx++;
		}
	     
		 $db->commit(); 
		 // $x=array("namatabel"=>"tm_kangk","jumlah"=>$jml);
	     // return $x;
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
		 $this->tuliskelog($e->getMessage().'\n');
	     return 'gagal';
	   }
	}
	public function cekTMMASTERU($thnang,$kdlokasi,$nosppa,$kdbrg,$noaset){
	   $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	 $sql = "select count(*) from aset.tm_masteru where thn_ang='".$thnang."'
	          and kd_lokasi='".$kdlokasi."' and no_sppa='".$nosppa."' and kd_brg='".$kdbrg."' and no_aset='".$noaset."' ";
	  echo $sql;
	  $result = $db->fetchOne($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
	}
    public function insertTMSPMKDPfromDBF1($data,$userid){
	  print_r($data);
	}
    public function insertTMMASTERUfromDBF(array $data,$userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $idx=0;
		 $jml=0;
		// if(count($data)>0){
		   // $this->deleteTable('aset.tm_masteru');
		// }
		//echo "servis tmmasteru";
		 $db->beginTransaction();
		 for($i=0;$i<count($data);$i++){
		  unset($where);
		  
		 
	     if($data[$i]['tgl_dsr_mts']=='0000-00-00'){		  
		   $data[$i]['tgl_dsr_mts']=null;
		   }
		    if(strlen(trim($data[$i]['tgl_dsr_mts']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_dsr_mts']=null;
		   }
		   if($data[$i]['tgl_buku']=='0000-00-00'){		  
		   $data[$i]['tgl_buku']=null;
		   }
		    if(strlen(trim($data[$i]['tgl_buku']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_buku']=null;
		   }
		   if($data[$i]['tgl_perlh']=='0000-00-00'){		  
		   $data[$i]['tgl_perlh']=null;
		   }
		    if(strlen(trim($data[$i]['tgl_perlh']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_perlh']=null;
		   }
		    if(strlen(trim($data[$i]['no_aset']))==0){
		     $data[$i]['no_aset']=0;
		   }		  
		   if(strlen(trim($data[$i]['rph_aset']))==0){
		     $data[$i]['rph_aset']=0;
		   }
		   if(strlen(trim($data[$i]['rph_sat']))==0){
		     $data[$i]['rph_sat']=0;
		   }
		   if(strlen(trim($data[$i]['umeko']))==0){
		     $data[$i]['umeko']=0;
		   }
		   if(strlen(trim($data[$i]['rph_res']))==0){
		    $data[$i]['rph_res']=0;
		   }
		   $cektmmasteru=$this->cekTMMASTERU($data[$i]['thn_ang'],$data[$i]['kd_lokasi'],$data[$i]['no_sppa'],$data[$i]['kd_brg'],$data[$i]['no_aset']);
		   //$name = $db->quoteInto($row[keterangan],"�");
		   //$name = $db->quote($row[keterangan],"�");
		  //$name = str_replace('\’', '', $row[keterangan]);
		  $keterangan = $this->htmlallentities($data[$i]['keterangan']);
		  $merk_type = $this->htmlallentities($data[$i]['merk_type']);
		  //echo "hasil : ".$name."<br/>";
	       $hasil=array("thn_ang"=>$data[$i]['thn_ang'],
		                      "periode"=>$data[$i]['periode'],
		                      "kd_lokasi"=>$data[$i]['kd_lokasi'],
		                      "no_sppa"=>$data[$i]['no_sppa'],
		                      "kd_brg"=>$data[$i]['kd_brg'],
		                      "no_aset"=>$data[$i]['no_aset'],
		                      "tgl_perlh"=>$data[$i]['tgl_perlh'],
		                      "tercatat"=>$data[$i]['tercatat'],
		                      "kondisi"=>$data[$i]['kondisi'],
		                      "tgl_buku"=>$data[$i]['tgl_buku'],		                      
		                      "jns_trn"=>$data[$i]['jns_trn'],		                      
		                      "dsr_hrg"=>$data[$i]['dsr_hrg'],
		                      "kd_data"=>$data[$i]['kd_data'],
		                      "flag_sap"=>$data[$i]['flag_sap'],
		                      "kuantitas"=>$data[$i]['kuantitas'],
		                      "rph_sat"=>$data[$i]['rph_sat'],
		                      "rph_aset"=>$data[$i]['rph_aset'],
		                      "flag_kor"=>$data[$i]['flag_kor'],
		                      "keterangan"=>$keterangan,
		                      "merk_type"=>$merk_type,
		                      "asal_perlh"=>$data[$i]['asal_perlh'],
		                      "no_bukti"=>$data[$i]['no_bukti'],
		                      "no_dsr_mts"=>$data[$i]['no_dsr_mts'],
		                      "tgl_dsr_mts"=>$data[$i]['tgl_dsr_mts'],
		                      "flag_ttp"=>$data[$i]['flag_ttp'],
		                      "flag_krm"=>$data[$i]['flag_krm'],
		                      "kdblu"=>$data[$i]['kdblu'],
		                      "setatus"=>$data[$i]['setatus'],
		                      "noreg"=>$data[$i]['noreg'],
		                      "kdbapel"=>$data[$i]['kdbapel'],
		                      "kdkpknl"=>$data[$i]['kdkpknl'],
		                      "umeko"=>$data[$i]['umeko'],
		                      "rph_res"=>$data[$i]['rph_res'],
		                      "kdkppn"=>$data[$i]['kdkppn'],
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   if($cektmmasteru==0){
		   $db->insert('aset.tm_masteru',$hasil);
		   $jml++;
		   }
		   if($cektmmasteru>0){
		   //echo "Update\n";
             //echo "Data user id : ".$hasil['i_entry']."\n";			
		     $where[]="thn_ang='".$data[$i]['thn_ang']."'";	     
		     $where[]="kd_lokasi='".$data[$i]['kd_lokasi']."'";	     
		     $where[]="no_sppa='".$data[$i]['no_sppa']."'";	     
		     $where[]="kd_brg='".$data[$i]['kd_brg']."'";	     
		     $where[]="no_aset='".$data[$i]['no_aset']."'";	    
             //print_r($where);			 
	         $db->update('aset.tm_masteru',$hasil,$where);
		   $jml++;
		   }
		   $idx++;
		   
		 }
	     
		 $db->commit();
		 // $x=array("namatabel"=>"tm_masteru","jumlah"=>$jml);
	     // return $x;
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
		 $this->tuliskelog($e->getMessage().'\n');
	     return 'gagal';
	   }
	}
	public function cekTMRUANG($kdlok,$kdruang){
	   $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	 $sql = "select count(*) from aset.tm_ruang where kd_lokasi='".$kdlok."'
	          and kd_ruang='".$kdruang."'";
	  $result = $db->fetchOne($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
	}
    public function insertTMRUANGfromDBF(array $data,$userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    //print_r($data);
		 $idx=0;
		 $jml=0;
		// if(count($data)>0){
		   // $this->deleteTable('aset.tm_ruang');
		// }
		 $db->beginTransaction();
		 for($i=0;$i<count($data);$i++){
          unset($where);
		 $cektmruang=$this->cekTMRUANG($data[$i]['kd_lokasi'],$data[$i]['kd_ruang']);		 
	      $hasil=array("kd_lokasi"=>$data[$i]['kd_lokasi'],
		                      "kd_ruang"=>$data[$i]['kd_ruang'],
		                      "ur_ruang"=>$data[$i]['ur_ruang'],
		                      "pj_ruang"=>$data[$i]['pj_ruang'],							                                          
		                      "nip_pjrug"=>$data[$i]['nip_pjrug'],							                                          
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
			
		   if($cektmruang==0){
		   $db->insert('aset.tm_ruang',$hasil);
		   $jml++;
		   }
		   if($cektmruang>0){
		    //echo "Update\n";
             //echo "Data user id : ".$hasil['i_entry']."\n";					     
		     $where[]="kd_lokasi='".$data[$i]['kd_lokasi']."'";	     
		     $where[]="kd_ruang='".$data[$i]['kd_ruang']."'";	     
		    
	         $db->update('aset.tm_ruang',$hasil,$where);
			 $jml++;
		   }
		   $idx++;
		   
		 }
	     
		 $db->commit();
		 // $x=array("namatabel"=>"tm_ruang","jumlah"=>$jml);
	     // return $x;
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
		 $this->tuliskelog($e->getMessage().'\n');
	     return 'gagal';
	   }
	}
	public function cekTMKBAIR($kdlok,$kdbrg,$noaset){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  //$db->setFetchMode(Zend_Db::FETCH_OBJ); 
	  $sql = "select count(*) from aset.tm_kbair where kd_lokasi='".$kdlok."'
	          and kd_brg='".$kdbrg."' and no_aset='".$noaset."' ";
	  //echo $sql."\n";
	  $result = $db->fetchOne($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
	}
	  public function insertTMKBAIRfromDBF(array $data,$userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $idx=0;
		 $jml=0;
		// if(count($data)>0){
		   // $this->deleteTable('aset.tm_masterhm');
		// }
		 $db->beginTransaction();
		 //print_r($data);
		 for($i=0;$i<count($data);$i++){
		 //for($i=0;$i<3;$i++){
		 unset($where);
		 
		   if($data[$i]['tgl_imb']=='0000-00-00'){
		   
		   $data[$i]['tgl_imb']=null;
		   }
	       if($data[$i]['tgl_buku']=='0000-00-00'){
		   
		   $data[$i]['tgl_buku']=null;
		   }
		   if($data[$i]['tgl_dana']=='0000-00-00'){
		   
		   $data[$i]['tgl_dana']=null;
		   }
		   if($data[$i]['tgl_prl']=='0000-00-00'){
		   
		   $data[$i]['tgl_prl']=null;
		   }
		   if(strlen(trim($data[$i]['noaset']))==0){
		     $data[$i]['noaset']=0;
		   }
		   if(strlen(trim($data[$i]['kuantitas']))==0){
		     $data[$i]['kuantitas']=0;
		   }
		   if(strlen(trim($data[$i]['rph_aset']))==0){
		     $data[$i]['rph_aset']=0;
		   }
		    if(strlen(trim($data[$i]['no_kib']))==0){
		     $data[$i]['no_kib']=0;
		   }
		    if(strlen(trim($data[$i]['luas_bdg']))==0){
		     $data[$i]['luas_bdg']=0;
		   }
		    if(strlen(trim($data[$i]['luas_dsr']))==0){
		     $data[$i]['luas_dsr']=0;
		   }
		    if(strlen(trim($data[$i]['no_kibtnh']))==0){
		     $data[$i]['no_kibtnh']=0;
		   }
		    if(strlen(trim($data[$i]['rph_wajar']))==0){
		     $data[$i]['rph_wajar']=0;
		   }
		    if(strlen(trim($data[$i]['rphwajar']))==0){
		     $data[$i]['rphwajar']=0;
		   }
		  $cektmkbair=$this->cekTMKBAIR($data[$i]['kd_lokasi'],$data[$i]['kd_brg'],$data[$i]['no_aset']);
		 //echo "cek tm masterhm : ".$cektmmasterhm."\n";
		  
	  $hasil=array("kd_lokasi"=>$data[$i]['kd_lokasi'],
				  "kd_brg"=>$data[$i]['kd_brg'],
				  "no_aset"=>$data[$i]['no_aset'],
				  "kuantitas"=>$data[$i]['kuantitas'],
				  "rph_aset"=>$data[$i]['rph_aset'],
				  "no_kib"=>$data[$i]['no_kib'],
				  "luas_bdg"=>$data[$i]['luas_bdg'],
				  "luas_dsr"=>$data[$i]['luas_dsr'],
				  "kapasitas"=>$data[$i]['kapasitas'],
				  "thn_sls"=>$data[$i]['thn_sls'],
				  "thn_pakai"=>$data[$i]['thn_pakai'],
				  "no_imb"=>$data[$i]['no_imb'],
				  "tgl_imb"=>$data[$i]['tgl_imb'],
				  "kd_prov"=>$data[$i]['kd_prov'],
				  "kd_kab"=>$data[$i]['kd_kab'],
				  "kd_kec"=>$data[$i]['kd_kec'],
				  "kd_kel"=>$data[$i]['kd_kel'],
				  "alamt"=>$data[$i]['alamat'],
				  "kd_rtrw"=>$data[$i]['kd_rtrw'],
				  "no_kibtnh"=>$data[$i]['no_kibtnh'],
				  "jns_trn"=>$data[$i]['jns_trn'],
				  "dari"=>$data[$i]['dari'],
				  "tgl_prl"=>$data[$i]['tgl_prl'],
				  "kondisi"=>$data[$i]['kondisi'],
				  "rph_wajar"=>$data[$i]['rph_wajar'],
				  "dasar_hrg"=>$data[$i]['dasar_hrg'],
				  "sumber"=>$data[$i]['sumber'],
				  "no_dana"=>$data[$i]['no_dana'],
				  "tgl_dana"=>$data[$i]['tgl_dana'],
				  "unit_pmk"=>$data[$i]['unit_pmk'],		                     
				  "alm_pmk"=>$data[$i]['alm_pmk'],
				  "catatan"=>$data[$i]['catatan'],		                      
				  "tgl_buku"=>$data[$i]['tgl_buku'],
				  "kons_sist"=>$data[$i]['kons_sist'],
				  "rphwajar"=>$data[$i]['rphwajar'],
				  "status"=>$data[$i]['status'],
				  "i_entry"=>$userid,
				  "d_entry"=>date("Y-m-d")							  
				  );
		   //echo "Cekt master : ".$cetmmasterhm."\n";
		   //print_r($hasil);
		   if($cektmkbair==0){
		   //echo "Insert\n";
		   $db->insert('aset.tm_kbair',$hasil);
		   $jml++;
		   }
		   if($cektmkbair>0){
            //echo "Update\n";
             //echo "Data user id : ".$hasil['i_entry']."\n";
		     $where[]="kd_lokasi='".$data[$i]['kd_lokasi']."'";	
		     $where[]="kd_brg='".$data[$i]['kd_brg']."'";	     
		     $where[]="no_aset='".$data[$i]['no_aset']."'";	    
             //print_r($where);			 
	         $db->update('aset.tm_kbair',$hasil,$where);
			 $jml++;
		   }
		   $idx++;
		 }
	     
		   
		 $db->commit();
		 
		 // $x=array("namatabel"=>"tm_kbair","jumlah"=>$jml);
	     // return $x;
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
		 $this->tuliskelog($e->getMessage().'\n');
	     return 'gagal';
	   }
	}
	public function cekTMKALB($kdlok,$kdbrg,$noaset){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  //$db->setFetchMode(Zend_Db::FETCH_OBJ); 
	 $sql = "select count(*) from aset.tm_kalb where kd_lokasi='".$kdlok."'
	          and kd_brg='".$kdbrg."' and no_aset='".$noaset."' ";
	  //echo $sql."\n";
	  $result = $db->fetchOne($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
	}
	  public function insertTMKALBfromDBF(array $data,$userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	  
        // echo "kode lokasi : ".$data['kd_lokasi']."<br/>";   
        // echo "tanggal buku : ".$data['tgl_buku']."<br/>";   
        // echo "tanggal dana : ".$data['tgl_dana']."<br/>";   
        // echo "tanggal prl : ".$data['tgl_prl']."<br/>";   
	   try {
	    
		 $idx=0;
		 $jml=0;
		// if(count($data)>0){
		   // $this->deleteTable('aset.tm_masterhm');
		// }
		 $db->beginTransaction();
		 //print_r($data);
		 for($i=0;$i<count($data);$i++){
		 //for($i=0;$i<3;$i++){
		 unset($where);
		 
	     if($data[$i]['tgl_buku']=='0000-00-00'){
		   
		   $data[$i]['tgl_buku']=null;
		   }
		    if(strlen($data[$i]['tgl_buku'])==0){		   
		     $data[$i]['tgl_buku']=null;
		   }
		   if($data[$i]['tgl_dana']=='0000-00-00'){
		   
		   $data[$i]['tgl_dana']=null;
		   }
		   if(strlen($data[$i]['tgl_dana'])==0){		   
		     $data[$i]['tgl_dana']=null;
		   }
		   if($data[$i]['tgl_prl']=='0000-00-00'){
		   
		   $data[$i]['tgl_prl']=null;
		   }
		    if(strlen($data[$i]['tgl_prl'])==0){		   
		     $data[$i]['tgl_prl']=null;
		   }
		   if(strlen(trim($data[$i]['no_aset']))==0){
		     $data[$i]['no_aset']=0;
		   }
		   if(strlen(trim($data[$i]['kuantitas']))==0){
		     $data[$i]['kuantitas']=0;
		   }
		   if(strlen(trim($data[$i]['no_kib']))==0){
		     $data[$i]['no_kib']=0;
		   }
		   if(strlen(trim($data[$i]['rph_aset']))==0){
		     $data[$i]['rph_aset']=0;
		   }
		   if(strlen(trim($data[$i]['rphwajar']))==0){
		     $data[$i]['rphwajar']=0;
		   }
		   if(strlen($data[$i]['jns_trn'])==0){
		     $data[$i]['jns_trn']='-';
		   }
		   // echo "no aset : ".$data[$i]['no_aset']."<br/>";
		   // echo "kuantitas : ".$data[$i]['kuantitas']."<br/>";
		   // echo "no_kib : ".$data[$i]['no_kib']."<br/>";
		   // echo "rph_aset : ".$data[$i]['rph_aset']."<br/>";
		   // echo "rphwajar : ".$data[$i]['rphwajar']."<br/>";
		   $cektmkalb=$this->cekTMKALB($data[$i]['kd_lokasi'],$data[$i]['kd_brg'],$data[$i]['no_aset']);
		 //echo "cek tm masterhm : ".$cektmmasterhm."\n";
	  $hasil=array("kd_lokasi"=>$data[$i]['kd_lokasi'],
		                      "kd_brg"=>$data[$i]['kd_brg'],
		                      "no_aset"=>$data[$i]['no_aset'],
		                      "kuantitas"=>$data[$i]['kuantitas'],
							  "no_kib"=>$data[$i]['no_kib'],
							  "merk"=>$data[$i]['merk'],
							  "type"=>$data[$i]['type'],							  
		                      "pabrik"=>$data[$i]['pabrik'],		                      
		                      "thn_rakit"=>$data[$i]['thn_rakit'],
		                      "thn_buat"=>$data[$i]['thn_buat'],
		                      "negara"=>$data[$i]['negara'],
		                      "kapasitas"=>$data[$i]['kapasitas'],
		                      "sis_opr"=>$data[$i]['sis_opr'],
		                      "sis_dingin"=>$data[$i]['sis_dingin'],
		                      "sis_bakar"=>$data[$i]['sis_bakar'],
		                      "duk_alat"=>$data[$i]['duk_alat'],
		                      "pwr_train"=>$data[$i]['pwr_train'],
		                      "no_mesin"=>$data[$i]['no_mesin'],
		                      "no_rangka"=>$data[$i]['no_rangka'],
		                      "lengkap1"=>$data[$i]['lengkap1'],
		                      "lengkap2"=>$data[$i]['lengkap2'],
		                      "lengkap3"=>$data[$i]['lengkap3'],
		                      "jns_trn"=>$data[$i]['jns_trn'],
							  "dari"=>$data[$i]['dari'],
		                      "tgl_prl"=>$data[$i]['tgl_prl'],
		                      "rph_aset"=>$data[$i]['rph_aset'],
							  "dasar_hrg"=>$data[$i]['dasar_hrg'],		                      
		                      "sumber"=>$data[$i]['sumber'],
		                      "no_dana"=>$data[$i]['no_dana'],
		                      "tgl_dana"=>$data[$i]['tgl_dana'],
		                      "unit_pmk"=>$data[$i]['unit_pmk'],		                     
		                      "alm_pmk"=>$data[$i]['alm_pmk'],
		                      "catatan"=>$data[$i]['catatan'],
                              "kondisi"=>$data[$i]['kondisi'],		                      
		                      "tgl_buku"=>$data[$i]['tgl_buku'],
							  "rphwajar"=>$data[$i]['rphwajar'],
							  "status"=>$data[$i]['status'],
		                      "cad1"=>$data[$i]['cad1'],
							  "i_entry"=>"system",
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
		   //echo "Cek tmkalb : ".$cektmkalb."<br/>";
		   //print_r($hasil);
		   if($cektmkalb==0){
		   //echo "Insert\n";
		   $db->insert('aset.tm_kalb',$hasil);
		   $jml++;
		   }
		   if($cektmkalb>0){
            //echo "Update\n";
             //echo "Data user id : ".$hasil['i_entry']."\n";
		     $where[]="kd_lokasi='".$data[$i]['kd_lokasi']."'";	
		     $where[]="kd_brg='".$data[$i]['kd_brg']."'";	     
		     $where[]="no_aset='".$data[$i]['no_aset']."'";	    
             //print_r($where);			 
	         $db->update('aset.tm_kalb',$hasil,$where);
			 $jml++;
		   }
		   $idx++;
		 }
	     
		   
		 $db->commit();
		 
		 // $x=array("namatabel"=>"tm_kalb","jumlah"=>$jml);
	     // return $x;
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
		 $this->tuliskelog($e->getMessage().'\n');
	     return 'gagal';
	   }
	}
	public function cekTMCMP($kdlok,$kdbrg,$noaset){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  //$db->setFetchMode(Zend_Db::FETCH_OBJ); 
	$sql = "select count(*) from aset.tm_cmp where kd_lokasi='".$kdlok."'
	          and kd_brg='".$kdbrg."' and no_aset='".$noaset."' ";
	  //echo $sql."\n";
	  $result = $db->fetchOne($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
	}
	  public function insertTMCMPfromDBF(array $data,$userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   //echo "kode lokasi : ".$data['kd_lokasi'];
	   try {
	    
		 $idx=0;
		 $jml=0;
		// if(count($data)>0){
		   // $this->deleteTable('aset.tm_masterhm');
		// }
		 $db->beginTransaction();
		 //print_r($data);
		for($i=0;$i<count($data);$i++){
		 //for($i=0;$i<3;$i++){
		 unset($where);
		
	     if($data[$i]['tgl_buku']=='0000-00-00'){
		   
		   $data[$i]['tgl_buku']=null;
		   }
		  if(strlen($data[$i]['tgl_buku'])==0){
		   
		   $data[$i]['tgl_buku']=null;
		   }
		 if(strlen(trim($data[$i]['no_aset']))==0){
		   $data[$i]['no_aset']=0;
		 }
		 if(strlen(trim($data[$i]['kuantitas']))==0){
		   $data[$i]['kuantitas']=0;
		 }
		 if(strlen(trim($data[$i]['luas_bdg']))==0){
		   $data[$i]['luas_bdg']=0;
		 }
		 if(strlen(trim($data[$i]['rph_aset']))==0){
		   $data[$i]['rph_aset']=0;
		 }
		   $cektmcmp=$this->cekTMCMP($data[$i]['kd_lokasi'],$data[$i]['kd_brg'],$data[$i]['no_aset']);
		 //echo "cek tm masterhm : ".$cektmmasterhm."\n";
	   $hasil=array("kd_lokasi"=>$data[$i]['kd_lokasi'],
		                      "kd_brg"=>$data[$i]['kd_brg'],
		                      "no_aset"=>$data[$i]['no_aset'],
		                      "kuantitas"=>$data[$i]['kuantitas'],							  
		                      "jns_trn"=>$data[$i]['jns_trn'],
		                      "no_sppa"=>$data[$i]['no_sppa'],
		                      "kuantitas"=>$data[$i]['kuantitas'],
		                      "luas_bdg"=>$data[$i]['luas_bdg'],
		                      "jml_lt"=>$data[$i]['jml_lt'],		                      
		                      "rph_aset"=>$data[$i]['rph_aset'],
		                      "tgl_buku"=>$data[$i]['tgl_buku'],
		                      "keterangan"=>$data[$i]['keterangan'],
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
		   //echo "Cekt cmp : ".$cektmcmp."\n";
		   //print_r($hasil);
		   if($cektmcmp==0){
		   //echo "Insert\n";
		   $db->insert('aset.tm_cmp',$hasil);
		   $jml++;
		   }
		   if($cektmcmp>0){
            //echo "Update\n";
             //echo "Data user id : ".$hasil['i_entry']."\n";
		     $where[]="kd_lokasi='".$data[$i]['kd_lokasi']."'";	
		     $where[]="kd_brg='".$data[$i]['kd_brg']."'";	     
		     $where[]="no_aset='".$data[$i]['no_aset']."'";	    
             //print_r($where);			 
	         $db->update('aset.tm_cmp',$hasil,$where);
			 $jml++;
		   }
		   $idx++;
		 }
	     
		   
		 $db->commit();
		 
		 // $x=array("namatabel"=>"tm_cmp","jumlah"=>$jml);
	     // return $x;
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
		 $this->tuliskelog($e->getMessage().'\n');
	     return 'gagal';
	   }
	}	
	public function cekTMKSENJ($kdlok,$kdbrg,$noaset){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  //$db->setFetchMode(Zend_Db::FETCH_OBJ); 
	 $sql = "select count(*) from aset.tm_ksenj where kd_lokasi='".$kdlok."'
	          and kd_brg='".$kdbrg."' and no_aset='".$noaset."'";
	  //echo $sql."\n";
	  $result = $db->fetchOne($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
	}
	  public function insertTMKSENJfromDBF(array $data,$userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $idx=0;
		 $jml=0;
		// if(count($data)>0){
		   // $this->deleteTable('aset.tm_masterhm');
		// }
		 $db->beginTransaction();
		 //print_r($data);
		 for($i=0;$i<count($data);$i++){
		 //for($i=0;$i<3;$i++){
		 unset($where);
		 
	     if($data[$i]['tgl_surat']=='0000-00-00'){
		   
		   $data[$i]['tgl_surat']=null;
		   }
		 if($data[$i]['tgl_dana']=='0000-00-00'){
		   
		   $data[$i]['tgl_dana']=null;
		   }
		 if($data[$i]['tgl_prl']=='0000-00-00'){
		   
		   $data[$i]['tgl_prl']=null;
		   }		 
		 if($data[$i]['tgl_buku']=='0000-00-00'){
		   
		   $data[$i]['tgl_buku']=null;
		   }
		   if(strlen(trim($data[$i]['no_aset']))==0){
		   $data[$i]['no_aset']=0;
		 }
		 if(strlen(trim($data[$i]['rph_aset']))==0){
		   $data[$i]['rph_aset']=0;
		 }
		 if(strlen(trim($data[$i]['no_kib']))==0){
		   $data[$i]['no_kib']=0;
		 }
		 if(strlen(trim($data[$i]['rphwajar']))==0){
		   $data[$i]['rphwajar']=0;
		 }
		 $cektmksenj=$this->cekTMKSENJ($data[$i]['kd_lokasi'],$data[$i]['kd_brg'],$data[$i]['no_aset']);
		 //echo "cek tm masterhm : ".$cektmmasterhm."\n";
		  
	   $hasil=array("kd_lokasi"=>$data[$i]['kd_lokasi'],
		                      "kd_brg"=>$data[$i]['kd_brg'],
		                      "no_aset"=>$data[$i]['no_aset'],
		                      "rph_aset"=>$data[$i]['rph_aset'],
		                      "no_kib"=>$data[$i]['no_kib'],
		                      "kuantitas"=>$data[$i]['kuantitas'],							  
		                      "nama"=>$data[$i]['nama'],
		                      "merk"=>$data[$i]['merk'],
		                      "tipe"=>$data[$i]['tipe'],
		                      "kaliber"=>$data[$i]['kaliber'],
		                      "no_pabrik"=>$data[$i]['no_pabrik'],
		                      "thn_buat"=>$data[$i]['thn_buat'],
		                      "surat"=>$data[$i]['surat'],
		                      "tgl_surat"=>$data[$i]['tgl_surat'],
		                      "lengkap1"=>$data[$i]['lengkap1'],
		                      "lengkap2"=>$data[$i]['lengkap2'],
		                      "lengkap3"=>$data[$i]['lengkap3'],
		                      "jns_trn"=>$data[$i]['jns_trn'],
		                      "dari"=>$data[$i]['dari'],
		                      "tgl_prl"=>$data[$i]['tgl_prl'],
		                      "kondisi"=>$data[$i]['kondisi'],
		                      "dasar_hrg"=>$data[$i]['dasar_hrg'],
		                      "sumber"=>$data[$i]['sumber'],
		                      "no_dana"=>$data[$i]['no_dana'],
		                      "tgl_dana"=>$data[$i]['tgl_dana'],
		                      "unit_pmk"=>$data[$i]['unit_pmk'],
		                      "alm_pmk"=>$data[$i]['alm_pmk'],
		                      "catatan"=>$data[$i]['catatan'],
		                      "tgl_buku"=>$data[$i]['tgl_buku'],
		                      "rphwajar"=>$data[$i]['rphwajar'],
		                      "status"=>$data[$i]['status'],
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
		   //echo "Cekt master : ".$cetmmasterhm."\n";
		   //print_r($hasil);
		   if($cektmksenj==0){
		   //echo "Insert\n";
		   $db->insert('aset.tm_ksenj',$hasil);
		   $jml++;
		   }
		   if($cektmksenj>0){
            //echo "Update\n";
             //echo "Data user id : ".$hasil['i_entry']."\n";
		     $where[]="kd_lokasi='".$data[$i]['kd_lokasi']."'";	
		     $where[]="kd_brg='".$data[$i]['kd_brg']."'";	     
		     $where[]="no_aset='".$data[$i]['no_aset']."'";	    
             //print_r($where);			 
	         $db->update('aset.tm_ksenj',$hasil,$where);
			 $jml++;
		   }
		   $idx++;
		 }
	     
		   
		 $db->commit();
		 
		 // $x=array("namatabel"=>"tm_ksenj","jumlah"=>$jml);
	     // return $x;
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
		 $this->tuliskelog($e->getMessage().'\n');
	     return 'gagal';
	   }
	}		
	public function cekTMMNOT($thnang,$kdlokasi,$nosppa,$kdbrg,$noaset){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  //$db->setFetchMode(Zend_Db::FETCH_OBJ); 
	  $sql = "select thn_ang from aset.tm_mnot where thn_ang='".$thnang."'
	          and kd_lokasi='".$kdlokasi."' and no_sppa='".$nosppa."' and kd_brg='".$kdbrg."' and no_aset='".$noaset."' ";
	  //echo $sql."\n";
	  $result = $db->fetchOne($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
	}
	  public function insertTMMNOTfromDBF(array $data,$userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $idx=0;
		 $jml=0;
		// if(count($data)>0){
		   // $this->deleteTable('aset.tm_masterhm');
		// }
		 $db->beginTransaction();
		 //print_r($data);
		 for($i=0;$i<count($data);$i++){
		 //for($i=0;$i<3;$i++){
		 unset($where);
		
	      if($data[$i]['tgl_dsr_mts']=='0000-00-00'){
		   
		   $data[$i]['tgl_dsr_mts']=null;
		   }
		    if(strlen(trim($data[$i]['tgl_dsr_mts']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_dsr_mts']=null;
		   }
		   if($data[$i]['tgl_perlh']=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_perlh']=null;
		   }
		    if(strlen(trim($data[$i]['tgl_perlh']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_perlh']=null;
		   }
		   if($data[$i]['tgl_buku']=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_buku']=null;
		   }
		    if(strlen(trim($data[$i]['tgl_buku']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_buku']=null;
		   }
		   if(strlen(trim($data[$i]['no_aset']))==0){
		     $data[$i]['no_aset']=0;
		   }
		   if(strlen(trim($data[$i]['kuantitas']))==0){
		     $data[$i]['kuantitas']=0;
		   }
		   if(strlen(trim($data[$i]['rph_aset']))==0){
		     $data[$i]['rph_aset']=0;
		   }
		   if(strlen(trim($data[$i]['rph_sat']))==0){
		     $data[$i]['rph_sat']=0;
		   }
		   if(strlen(trim($data[$i]['umeko']))==0){
		     $data[$i]['umeko']=0;
		   }
		   if(strlen(trim($data[$i]['rph_res']))==0){
		    $data[$i]['rph_res']=0;
		   }
		   $cektmmnot=$this->cekTMMNOT($data[$i]['thn_ang'],$data[$i]['kd_lokasi'],$data[$i]['no_sppa'],$data[$i]['kd_brg'],$data[$i]['no_aset']);
		 //echo "cek tm masterhm : ".$cektmmasterhm."\n";
	       $hasil=array("thn_ang"=>$data[$i]['thn_ang'],
		                      "periode"=>$data[$i]['periode'],
		                      "kd_lokasi"=>$data[$i]['kd_lokasi'],
		                      "no_sppa"=>$data[$i]['no_sppa'],
		                      "kd_brg"=>$data[$i]['kd_brg'],
							  "kd_brgnew"=>$data[$i]['kd_brgnew'],
		                      "no_aset"=>$data[$i]['no_aset'],
		                      "tgl_perlh"=>$data[$i]['tgl_perlh'],
		                      "tercatat"=>$data[$i]['tercatat'],
		                      "kondisi"=>$data[$i]['kondisi'],
		                      "tgl_buku"=>$data[$i]['tgl_buku'],
		                      "jns_trn"=>$data[$i]['jns_trn'],
		                      "dsr_hrg"=>$data[$i]['dsr_hrg'],
		                      "kd_data"=>$data[$i]['kd_data'],
		                      "flag_sap"=>$data[$i]['flag_sap'],
		                      "kuantitas"=>$data[$i]['kuantitas'],
		                      "rph_sat"=>$data[$i]['rph_sat'],
		                      "rph_aset"=>$data[$i]['rph_aset'],
		                      "flag_kor"=>$data[$i]['flag_kor'],
		                      "keterangan"=>$data[$i]['keterangan'],
		                      "merk_type"=>$data[$i]['merk_type'],
		                      "asal_perlh"=>$data[$i]['asal_perlh'],
		                      "no_bukti"=>$data[$i]['no_bukti'],
		                      "no_dsr_mts"=>$data[$i]['no_dsr_mts'],
		                      "tgl_dsr_mts"=>$data[$i]['tgl_dsr_mts'],
		                      "flag_ttp"=>$data[$i]['flag_ttp'],
		                      "flag_krm"=>$data[$i]['flag_krm'],
		                      "kdblu"=>$data[$i]['kdblu'],
		                      "setatus"=>$data[$i]['setatus'],
		                      "noreg"=>$data[$i]['noreg'],
		                      "kdbapel"=>$data[$i]['kdbapel'],
		                      "kdkpknl"=>$data[$i]['kdkpknl'],
		                      "umeko"=>$data[$i]['umeko'],
		                      "rph_res"=>$data[$i]['rph_res'],
							   "perk_new"=>$data[$i]['perk_new'],
		                      "kdkppn"=>$data[$i]['kdkppn'],		                   
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
		   //echo "Cekt master : ".$cetmmasterhm."\n";
		   //print_r($hasil);
		   if($cektmmnot==0){
		   //echo "Insert\n";
		   $db->insert('aset.tm_mnot',$hasil);
		   $jml++;
		   }
		   if($cektmmnot>0){
            //echo "Update\n";
             //echo "Data user id : ".$hasil['i_entry']."\n";			
		     $where[]="thn_ang='".$data[$i]['thn_ang']."'";	     
		     $where[]="kd_lokasi='".$data[$i]['kd_lokasi']."'";	     
		     $where[]="no_sppa='".$data[$i]['no_sppa']."'";	     
		     $where[]="kd_brg='".$data[$i]['kd_brg']."'";	     
		     $where[]="no_aset='".$data[$i]['no_aset']."'";	    
             //print_r($where);			 
	         $db->update('aset.tm_mnot',$hasil,$where);
			 $jml++;
		   }
		   $idx++;
		 }
	     
		   
		 $db->commit();
		 
		 // $x=array("namatabel"=>"tm_mnot","jumlah"=>$jml);
	     // return $x;
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
		 $this->tuliskelog($e->getMessage().'\n');
	     return 'gagal';
	   }
	}	
	public function cekTMMPINDAH($thnang,$kdlokasi,$nosppa,$kdbrg,$noaset){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  //$db->setFetchMode(Zend_Db::FETCH_OBJ); 
	   $sql = "select thn_ang from aset.tm_mpindah where thn_ang='".$thnang."'
	          and kd_lokasi='".$kdlokasi."' and no_sppa='".$nosppa."' and kd_brg='".$kdbrg."' and no_aset='".$noaset."' ";
	  //echo $sql."\n";
	  $result = $db->fetchOne($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
	}
	  public function insertTMMPINDAHfromDBF(array $data,$userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $idx=0;
		 $jml=0;
		// if(count($data)>0){
		   // $this->deleteTable('aset.tm_masterhm');
		// }
		 $db->beginTransaction();
		 //print_r($data);
		 for($i=0;$i<count($data);$i++){
		 //for($i=0;$i<3;$i++){
		 unset($where);
		
	      if($data[$i]['tgl_dsr_mts']=='0000-00-00'){
		   
		   $data[$i]['tgl_dsr_mts']=null;
		   }
		    if(strlen(trim($data[$i]['tgl_dsr_mts']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_dsr_mts']=null;
		   }
		   if($data[$i]['tgl_perlh']=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_perlh']=null;
		   }
		    if(strlen(trim($data[$i]['tgl_perlh']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_perlh']=null;
		   }
		   if($data[$i]['tgl_buku']=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_buku']=null;
		   }
		    if(strlen(trim($data[$i]['tgl_buku']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_buku']=null;
		   }
		   if(strlen(trim($data[$i]['no_aset']))==0){
		     $data[$i]['no_aset']=0;
		   }
		   if(strlen(trim($data[$i]['kuantitas']))==0){
		     $data[$i]['kuantitas']=0;
		   }
		   if(strlen(trim($data[$i]['rph_aset']))==0){
		     $data[$i]['rph_aset']=0;
		   }
		   if(strlen(trim($data[$i]['rph_sat']))==0){
		     $data[$i]['rph_sat']=0;
		   }
		   if(strlen(trim($data[$i]['umeko']))==0){
		     $data[$i]['umeko']=0;
		   }
		   if(strlen(trim($data[$i]['rph_res']))==0){
		    $data[$i]['rph_res']=0;
		   }
		   $cektmmpindah=$this->cekTMMPINDAH($data[$i]['thn_ang'],$data[$i]['kd_lokasi'],$data[$i]['no_sppa'],$data[$i]['kd_brg'],$data[$i]['no_aset']);
		 //echo "cek tm masterhm : ".$cektmmasterhm."\n";
	       $hasil=array("thn_ang"=>$data[$i]['thn_ang'],
		                      "periode"=>$data[$i]['periode'],
		                      "kd_lokasi"=>$data[$i]['kd_lokasi'],
		                      "no_sppa"=>$data[$i]['no_sppa'],
		                      "kd_brg"=>$data[$i]['kd_brg'],
							  "kd_brgnew"=>$data[$i]['kd_brgnew'],
		                      "no_aset"=>$data[$i]['no_aset'],
		                      "tgl_perlh"=>$data[$i]['tgl_perlh'],
		                      "tercatat"=>$data[$i]['tercatat'],
		                      "kondisi"=>$data[$i]['kondisi'],
		                      "tgl_buku"=>$data[$i]['tgl_buku'],
		                      "jns_trn"=>$data[$i]['jns_trn'],
		                      "dsr_hrg"=>$data[$i]['dsr_hrg'],
		                      "kd_data"=>$data[$i]['kd_data'],
		                      "flag_sap"=>$data[$i]['flag_sap'],
		                      "kuantitas"=>$data[$i]['kuantitas'],
		                      "rph_sat"=>$data[$i]['rph_sat'],
		                      "rph_aset"=>$data[$i]['rph_aset'],
		                      "flag_kor"=>$data[$i]['flag_kor'],
		                      "keterangan"=>$data[$i]['keterangan'],
		                      "merk_type"=>$data[$i]['merk_type'],
		                      "asal_perlh"=>$data[$i]['asal_perlh'],
		                      "no_bukti"=>$data[$i]['no_bukti'],
		                      "no_dsr_mts"=>$data[$i]['no_dsr_mts'],
		                      "tgl_dsr_mts"=>$data[$i]['tgl_dsr_mts'],
		                      "flag_ttp"=>$data[$i]['flag_ttp'],
		                      "flag_krm"=>$data[$i]['flag_krm'],
		                      "kdblu"=>$data[$i]['kdblu'],
		                      "setatus"=>$data[$i]['setatus'],
		                      "noreg"=>$data[$i]['noreg'],
		                      "kdbapel"=>$data[$i]['kdbapel'],
		                      "kdkpknl"=>$data[$i]['kdkpknl'],
		                      "umeko"=>$data[$i]['umeko'],
		                      "rph_res"=>$data[$i]['rph_res'],
							   "kdkppn"=>$data[$i]['kdkppn'],
		                      "perklm"=>$data[$i]['perklm'],		                   
		                      "perkbr"=>$data[$i]['perkbr'],		                   
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
		   //echo "Cekt master : ".$cetmmasterhm."\n";
		   //print_r($hasil);
		   if($cektmmpindah==0){
		   //echo "Insert\n";
		   $db->insert('aset.tm_mpindah',$hasil);
		   $jml++;
		   }
		   if($cektmmpindah>0){
            //echo "Update\n";
             //echo "Data user id : ".$hasil['i_entry']."\n";			
		     $where[]="thn_ang='".$data[$i]['thn_ang']."'";	     
		     $where[]="kd_lokasi='".$data[$i]['kd_lokasi']."'";	     
		     $where[]="no_sppa='".$data[$i]['no_sppa']."'";	     
		     $where[]="kd_brg='".$data[$i]['kd_brg']."'";	     
		     $where[]="no_aset='".$data[$i]['no_aset']."'";	    
             //print_r($where);			 
	         $db->update('aset.tm_mpindah',$hasil,$where);
			 $jml++;
		   }
		   $idx++;
		 }
	     
		   
		 $db->commit();
		 
		 // $x=array("namatabel"=>"tm_mpindah","jumlah"=>$jml);
	     // return $x;
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
		 $this->tuliskelog($e->getMessage().'\n');
	     return 'gagal';
	   }
	}	
	public function cekTMMASTERT($kdlokasi,$kdbrg,$noaset){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  //$db->setFetchMode(Zend_Db::FETCH_OBJ); 
	   $sql = "select kd_lokasi from aset.tm_mastert where kd_lokasi='".$kdlokasi."' 
	  and kd_brg='".$kdbrg."' and no_aset='".$noaset."' ";
	  //echo $sql."\n";
	  $result = $db->fetchOne($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
	}
	  public function insertTMMASTERTfromDBF(array $data,$userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $idx=0;
		 $jml=0;
		// if(count($data)>0){
		   // $this->deleteTable('aset.tm_masterhm');
		// }
		 $db->beginTransaction();
		 //print_r($data);
		 for($i=0;$i<count($data);$i++){
		 //for($i=0;$i<3;$i++){
		 unset($where);
		
		    if(strlen(trim($data[$i]['no_aset']))==0){
		   $data[$i]['no_aset']=0;
		 }
		  $cektmmastert=$this->cekTMMASTERT($data[$i]['kd_lokasi'],$data[$i]['kd_brg'],$data[$i]['no_aset']);
		 //echo "cek tm masterhm : ".$cektmmasterhm."\n";
	       $hasil=array(
		                      "kd_lokasi"=>$data[$i]['kd_lokasi'],		                      
		                      "kd_brg"=>$data[$i]['kd_brg'],
							  "no_aset"=>$data[$i]['no_aset'],
		                      "dsr_hrg"=>$data[$i]['dsr_hrg'],
		                      "keterangan"=>$data[$i]['keterangan'],
		                      "merk_type"=>$data[$i]['merk_type'],
		                      "asal_perlh"=>$data[$i]['asal_perlh'],	                   
		                      "tercatat"=>$data[$i]['tercatat'],		                   
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
		   //echo "Cekt master : ".$cetmmasterhm."\n";
		   //print_r($hasil);
		   if($cektmmastert==0){
		   //echo "Insert\n";
		   $db->insert('aset.tm_mastert',$hasil);
		   $jml++;
		   }
		   if($cektmmastert>0){
            //echo "Update\n";
             //echo "Data user id : ".$hasil['i_entry']."\n";    
		     $where[]="kd_lokasi='".$data[$i]['kd_lokasi']."'";  
		     $where[]="kd_brg='".$data[$i]['kd_brg']."'";	     
		     $where[]="no_aset='".$data[$i]['no_aset']."'";	    
             //print_r($where);			 
	         $db->update('aset.tm_mastert',$hasil,$where);
			 $jml++;
		   }
		   $idx++;
		 }
	     
		   
		 $db->commit();
		 
		 // $x=array("namatabel"=>"tm_mastert","jumlah"=>$jml);
	     // return $x;
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
		 $this->tuliskelog($e->getMessage().'\n');
	     return 'gagal';
	   }
	}		
	public function cekTMPHK3($kdlokasi,$kdbrg,$noaset){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  //$db->setFetchMode(Zend_Db::FETCH_OBJ); 
	  $sql = "select kd_lokasi from aset.tm_phk3 where kd_lokasi='".$kdlokasi."' 
	  and kd_brg='".$kdbrg."' and no_aset='".$noaset."' ";
	  //echo $sql."\n";
	  $result = $db->fetchOne($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
	}
	  public function insertTMPHK3fromDBF(array $data,$userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $idx=0;
		 $jml=0;
		// if(count($data)>0){
		   // $this->deleteTable('aset.tm_masterhm');
		// }
		 $db->beginTransaction();
		 //print_r($data);
		 for($i=0;$i<count($data);$i++){
		 //for($i=0;$i<3;$i++){
		 unset($where);
		
		 if($data[$i]['tgl_buku']=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_buku']=null;
		   }
		    if(strlen(trim($data[$i]['tgl_buku']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_buku']=null;
		   }
		    if(strlen(trim($data[$i]['no_aset']))==0){
		   $data[$i]['no_aset']=0;
		 }
		    if(strlen(trim($data[$i]['kuantitas']))==0){
		   $data[$i]['kuantitas']=0;
		 }
		  if(strlen(trim($data[$i]['rphwajar']))==0){
		   $data[$i]['rphwajar']=0;
		 }
		  $cektmphk3=$this->cekTMPHK3($data[$i]['kd_lokasi'],$data[$i]['kd_brg'],$data[$i]['no_aset']);
		 //echo "cek tm masterhm : ".$cektmmasterhm."\n";
	       $hasil=array(
		                      "kd_lokasi"=>$data[$i]['kd_lokasi'],		                      
		                      "kd_brg"=>$data[$i]['kd_brg'],
							  "no_aset"=>$data[$i]['no_aset'],
							  "kuantitas"=>$data[$i]['kuantitas'],
							  "no_SPPA"=>$data[$i]['no_SPPA'],
		                      "dsr_hrg"=>$data[$i]['dsr_hrg'],
							  "merk_type"=>$data[$i]['merk_type'],
		                      "asal_perlh"=>$data[$i]['asal_perlh'],
		                      "no_bukti"=>$data[$i]['no_bukti'],
		                      "no_dsr_mts"=>$data[$i]['no_dsr_mts'],
		                      "tgl_buku"=>$data[$i]['tgl_buku'],
		                      "keterangan"=>$data[$i]['keterangan'],		                     	                   
		                      "kondisi"=>$data[$i]['kondisi'],		                     	                   
		                      "tercatat"=>$data[$i]['tercatat'],		                   
		                      "jns_trn"=>$data[$i]['jns_trn'],		                   
		                      "thn_ang"=>$data[$i]['thn_ang'],		                   
		                      "kdbapel"=>$data[$i]['kdbapel'],		                   
		                      "kdkpknl"=>$data[$i]['kdkpknl'],		                   
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
		   //echo "Cekt master : ".$cetmmasterhm."\n";
		   //print_r($hasil);
		   if($cektmphk3==0){
		   //echo "Insert\n";
		   $db->insert('aset.tm_phk3',$hasil);
		   $jml++;
		   }
		   if($cektmphk3>0){
            //echo "Update\n";
             //echo "Data user id : ".$hasil['i_entry']."\n";    
		     $where[]="kd_lokasi='".$data[$i]['kd_lokasi']."'";  
		     $where[]="kd_brg='".$data[$i]['kd_brg']."'";	     
		     $where[]="no_aset='".$data[$i]['no_aset']."'";	    
             //print_r($where);			 
	         $db->update('aset.tm_phk3',$hasil,$where);
			 $jml++;
		   }
		   $idx++;
		 }
	     
		   
		 $db->commit();
		 
		 // $x=array("namatabel"=>"tm_phk3","jumlah"=>$jml);
	     // return $x;
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
		 $this->tuliskelog($e->getMessage().'\n');
	     return 'gagal';
	   }
	}	
	
	public function cekTMPPBI($kdpebin,$kdpbi,$kdppbi){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  //$db->setFetchMode(Zend_Db::FETCH_OBJ); 
	  $sql = "select kd_pebin from aset.tm_ppbi where kd_pebin='".$kdpebin."' 
	  and kd_pbi='".$kdpbi."' and kd_ppbi='".$kdppbi."' ";
	  //echo $sql."\n";
	  $result = $db->fetchOne($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
	}
	  public function insertTMPPBIfromDBF(array $data,$userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $idx=0;
		 $jml=0;
		// if(count($data)>0){
		   // $this->deleteTable('aset.tm_masterhm');
		// }
		 $db->beginTransaction();
		 //print_r($data);
		 for($i=0;$i<count($data);$i++){
		 //for($i=0;$i<3;$i++){
		 unset($where);
		 $cektmppbi=$this->cekTMPPBI($data[$i]['kd_pebin'],$data[$i]['kd_pbi'],$data[$i]['kd_ppbi']);
		   
	       $hasil=array(
		                      "kd_pebin"=>$data[$i]['kd_pebin'],		                      
		                      "kd_pbi"=>$data[$i]['kd_pbi'],
							  "kd_ppbi"=>$data[$i]['kd_ppbi'],
							  "ur_ppbi"=>$data[$i]['ur_ppbi'],		                   
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
		   //echo "Cekt master : ".$cetmmasterhm."\n";
		   //print_r($hasil);
		   if($cektmppbi==0){
		   //echo "Insert\n";
		   $db->insert('aset.tm_ppbi',$hasil);
		   $jml++;
		   }
		   if($cektmppbi>0){
            //echo "Update\n";
             //echo "Data user id : ".$hasil['i_entry']."\n";    
		     $where[]="kd_pebin='".$data[$i]['kd_pebin']."'";  
		     $where[]="kd_pbi='".$data[$i]['kd_pbi']."'";	     
		     $where[]="kd_ppbi='".$data[$i]['kd_ppbi']."'";	    
             //print_r($where);			 
	         $db->update('aset.tm_ppbi',$hasil,$where);
			 $jml++;
		   }
		   $idx++;
		 }
	     
		   
		 $db->commit();
		 
		 // $x=array("namatabel"=>"tm_ppbi","jumlah"=>$jml);
	     // return $x;
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
		 $this->tuliskelog($e->getMessage().'\n');
	     return 'gagal';
	   }
	}		
	public function cekTMSPM($kdlok,$nosp2d,$nosppa,$kdbrg,$jnstrn){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  //$db->setFetchMode(Zend_Db::FETCH_OBJ); 
	  $sql = "select kd_lokasi from aset.tm_spm where kd_lokasi='".$kdlok."' 
	  and no_sp2d='".$nosp2d."' and no_sppa='".$nosppa."' and kd_brg='".$kd_brg."' 
	  and jns_trn='".$jnstrn."'";
	  //echo $sql."\n";
	  $result = $db->fetchOne($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
	}
	  public function insertTMSPMfromDBF(array $data,$userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $idx=0;
		 $jml=0;
		// if(count($data)>0){
		   // $this->deleteTable('aset.tm_masterhm');
		// }
		 $db->beginTransaction();
		 //print_r($data);
		 for($i=0;$i<count($data);$i++){
		 //for($i=0;$i<3;$i++){
		 unset($where);
		
		  if($data[$i]['tgl_buku']=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_sp2d']=null;
		   }
		    if(strlen(trim($data[$i]['tgl_sp2d']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_sp2d']=null;
		   }
		 if($data[$i]['tgl_buku']=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_buku']=null;
		   }
		    if(strlen(trim($data[$i]['tgl_buku']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_buku']=null;
		   }
		    if(strlen(trim($data[$i]['jml_spm']))==0){
		   $data[$i]['jml_spm']=0;
		 }
		   if(strlen(trim($data[$i]['no_aset']))==0){
		   $data[$i]['no_aset']=0;
		 }
		  $cektmspm=$this->cekTMSPM($data[$i]['kd_lokasi'],$data[$i]['no_sp2d'],$data[$i]['no_sppa'],$data[$i]['kd_brg'],$data[$i]['jns_trn']);
	       $hasil=array(
		                      "kd_lokasi"=>$data[$i]['kd_lokasi'],		                      
		                      "no_sppa"=>$data[$i]['no_sppa'],
							  "no_sp2d"=>$data[$i]['no_sp2d'],
							  "bkpk"=>$data[$i]['bkpk'],		                   
							  "tgl_sp2d"=>$data[$i]['tgl_sp2d'],		                   
							  "jml_spm"=>$data[$i]['jml_spm'],		                   
							  "kd_brg"=>$data[$i]['kd_brg'],		                   
							  "no_aset"=>$data[$i]['no_aset'],		                   
							  "jns_trn"=>$data[$i]['jns_trn'],		                   
							  "tgl_buku"=>$data[$i]['tgl_buku'],		                   
							  "thn_ang"=>$data[$i]['thn_ang'],		                   
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
		   //echo "Cekt master : ".$cetmmasterhm."\n";
		   //print_r($hasil);
		   if($cektmspm==0){
		   //echo "Insert\n";
		   $db->insert('aset.tm_spm',$hasil);
		   $jml++;
		   }
		   if($cektmspm>0){
            //echo "Update\n";
             //echo "Data user id : ".$hasil['i_entry']."\n";    
			 $where[]="kd_lokasi='".$data[$i]['kd_lokasi']."'";  
		     $where[]="no_sp2d='".$data[$i]['no_sp2d']."'";	     
		     $where[]="no_sppa='".$data[$i]['no_sppa']."'";	    
		     $where[]="kd_brg='".$data[$i]['kd_brg']."'";	    
		     $where[]="jns_trn='".$data[$i]['jns_trn']."'";	    
             //print_r($where);			 
	         $db->update('aset.tm_spm',$hasil,$where);
			 $jml++;
		   }
		   $idx++;
		 }
	     
		   
		 $db->commit();
		 
		 // $x=array("namatabel"=>"tm_spm","jumlah"=>$jml);
	     // return $x;
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
		 $this->tuliskelog($e->getMessage().'\n');
	     return 'gagal';
	   }
	}		
	public function cekTMTTD($kdlok){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  //$db->setFetchMode(Zend_Db::FETCH_OBJ); 
	  $sql = "select kd_lokasi from aset.tm_ttd where kd_lokasi='".$kdlok."'";
	  //echo $sql."\n";
	  $result = $db->fetchOne($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
	}
	  public function insertTMTTDfromDBF(array $data,$userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $idx=0;
		 $jml=0;
		// if(count($data)>0){
		   // $this->deleteTable('aset.tm_masterhm');
		// }
		 $db->beginTransaction();
		 //print_r($data);
		 for($i=0;$i<count($data);$i++){
		 //for($i=0;$i<3;$i++){
		 unset($where);
		
		  if($data[$i]['tanggal']=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tanggal']=null;
		   }
		    if(strlen(trim($data[$i]['tanggal']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tanggal']=null;
		   }
		  if($data[$i]['tgl_isi']=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_isi']=null;
		   }
		    if(strlen(trim($data[$i]['tgl_isi']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_isi']=null;
		   }
		   if($data[$i]['tgl_setuju']=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_setuju']=null;
		   }
		    if(strlen(trim($data[$i]['tgl_setuju']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_setuju']=null;
		   }
		    $cektmttd=$this->cekTMTTD($data[$i]['kd_lokasi']);
	       $hasil=array(
		                      "kd_lokasi"=>$data[$i]['kd_lokasi'],		                      
		                      "kota"=>$data[$i]['kota'],
							  "tanggal"=>$data[$i]['tanggal'],
							  "nip"=>$data[$i]['nip'],		                   
							  "nama"=>$data[$i]['nama'],		                   
							  "jabatan"=>$data[$i]['jabatan'],		                   
							  "nip2"=>$data[$i]['nip2'],		                   
							  "nama2"=>$data[$i]['nama2'],		                   
							  "jabatan2"=>$data[$i]['jabatan2'],		                   
							  "tgl_isi"=>$data[$i]['tgl_isi'],		                   
							  "tgl_setuju"=>$data[$i]['tgl_setuju'],		                   
							  "unit"=>$data[$i]['unit'],		                   
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
		   //echo "Cekt master : ".$cetmmasterhm."\n";
		   //print_r($hasil);
		   if($cektmttd==0){
		   //echo "Insert\n";
		   $db->insert('aset.tm_ttd',$hasil);
		   $jml++;
		   }
		   if($cektmttd>0){
            //echo "Update\n";
             //echo "Data user id : ".$hasil['i_entry']."\n";    
			 $where[]="kd_lokasi='".$data[$i]['kd_lokasi']."'"; 
             //print_r($where);			 
	         $db->update('aset.tm_ttd',$hasil,$where);
			 $jml++;
		   }
		   $idx++;
		 }
	     
		   
		 $db->commit();
		 
		 // $x=array("namatabel"=>"tm_ttd","jumlah"=>$jml);
	     // return $x;
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
		 $this->tuliskelog($e->getMessage().'\n');
	     return 'gagal';
	   }
	}			
	public function cekTMUPB($kdlok){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  //$db->setFetchMode(Zend_Db::FETCH_OBJ); 
	 $sql = "select kdlok from aset.tm_upb where kdlok='".$kdlok."'";
	  //echo $sql."\n";
	  $result = $db->fetchOne($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
	}
	  public function insertTMUPBfromDBF(array $data,$userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $idx=0;
		 $jml=0;
		// if(count($data)>0){
		   // $this->deleteTable('aset.tm_masterhm');
		// }
		 $db->beginTransaction();
		 //print_r($data);
		 for($i=0;$i<count($data);$i++){
		 //for($i=0;$i<3;$i++){
		 unset($where);
		 $cektmupb=$this->cekTMUPB($data[$i]['kdlok']);
		
	       $hasil=array(      "kd_pebin"=>$data[$i]['kd_pebin'],		                      
		                      "kd_pbi"=>$data[$i]['kd_pbi'],
							  "kd_ppbi"=>$data[$i]['kd_ppbi'],
							  "kd_upb"=>$data[$i]['kd_upb'],
							  "kd_subupb"=>$data[$i]['kd_subupb'],
							  "kd_jk"=>$data[$i]['kd_jk'],
							  "ur_upb"=>$data[$i]['ur_upb'],		                   
							  "kdlok"=>$data[$i]['kdlok'],		                   
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
		   //echo "Cekt master : ".$cetmmasterhm."\n";
		   //print_r($hasil);
		   if($cektmupb==0){
		   //echo "Insert\n";
		   $db->insert('aset.tm_upb',$hasil);
		   $jml++;
		   }
		   if($cektmupb>0){
            //echo "Update\n";
             //echo "Data user id : ".$hasil['i_entry']."\n";    
		     $where[]="kdlok='".$data[$i]['kdlok']."'";   
             //print_r($where);			 
	         $db->update('aset.tm_upb',$hasil,$where);
			 $jml++;
		   }
		   $idx++;
		 }
	     
		   
		 $db->commit();
		 
		 // $x=array("namatabel"=>"tm_upb","jumlah"=>$jml);
	     // return $x;
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
		 $this->tuliskelog($e->getMessage().'\n');
	     return 'gagal';
	   }
	}	
	public function cekTMSSKEL($kdbrg,$kdgol,$kdbid,$kdkel,$kdskel,$kdsskel){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  //$db->setFetchMode(Zend_Db::FETCH_OBJ); 
	 $sql = "select kd_brg from aset.tm_sskel where kd_brg='".$kdbrg."'
	          and kd_gol='".$kdgol."' and kd_bid='".$kdbid."' and kd_kel='".$kdkel."'
			  and kd_skel='".$kdskel."' and kd_sskel='".$kdsskel."'";
	  //echo $sql."\n";
	  $result = $db->fetchOne($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
	}
	  public function insertTMSSKELfromDBF(array $data,$userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $idx=0;
		 $jml=0;
		// if(count($data)>0){
		   // $this->deleteTable('aset.tm_masterhm');
		// }
		 $db->beginTransaction();
		 //print_r($data);
		 for($i=0;$i<count($data);$i++){
		 //for($i=0;$i<3;$i++){
		 unset($where);
		 $cektmsskel=$this->cekTMSSKEL($data[$i]['kd_brg'],$data[$i]['kd_gol'],$data[$i]['kd_bid'],$data[$i]['kd_kel'],$data[$i]['kd_skel'],$data[$i]['kd_sskel']);
		
	       $hasil=array(      "kd_gol"=>$data[$i]['kd_gol'],		                      
		                      "kd_bid"=>$data[$i]['kd_bid'],
							  "kd_kel"=>$data[$i]['kd_kel'],
							  "kd_skel"=>$data[$i]['kd_skel'],
							  "kd_sskel"=>$data[$i]['kd_sskel'],
							  "satuan"=>$data[$i]['satuan'],
							  "ur_sskel"=>$data[$i]['ur_sskel'],		                   
							  "kd_perk"=>$data[$i]['kd_perk'],		                   
							  "kd_brg"=>$data[$i]['kd_brg'],		                   
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
		   //echo "Cekt master : ".$cetmmasterhm."\n";
		   //print_r($hasil);
		   if($cektmsskel==0){
		   //echo "Insert\n";
		   $db->insert('aset.tm_sskel',$hasil);
		   $jml++;
		   }
		   if($cektmsskel>0){
            //echo "Update\n";
             //echo "Data user id : ".$hasil['i_entry']."\n";    
		     $where[]="kd_brg='".$data[$i]['kd_brg']."'";   
		     $where[]="kd_gol='".$data[$i]['kd_gol']."'";   
		     $where[]="kd_bid='".$data[$i]['kd_bid']."'";   
		     $where[]="kd_kel='".$data[$i]['kd_kel']."'";   
		     $where[]="kd_skel='".$data[$i]['kd_skel']."'";   
		     $where[]="kd_sskel='".$data[$i]['kd_sskel']."'";   
             //print_r($where);			 
	         $db->update('aset.tm_sskel',$hasil,$where);
			 $jml++;
		   }
		   $idx++;
		 }
	     
		   
		 $db->commit();
		 
		 // $x=array("namatabel"=>"tm_sskel","jumlah"=>$jml);
	     // return $x;
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
		 $this->tuliskelog($e->getMessage().'\n');
	     return 'gagal';
	   }
	}
	public function cekTMSKEL($kdskelbrg,$kdgol,$kdbid,$kdkel,$kdskel){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  //$db->setFetchMode(Zend_Db::FETCH_OBJ); 
	 $sql = "select kd_skelbrg from aset.tm_skel where kd_skelbrg='".$kdskelbrg."'
	          and kd_gol='".$kdgol."' and kd_bid='".$kdbid."' and kd_kel='".$kdkel."'
			  and kd_skel='".$kdskel."'";
	  //echo $sql."\n";
	  $result = $db->fetchOne($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
	}
	  public function insertTMSKELfromDBF(array $data,$userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $idx=0;
		 $jml=0;
		// if(count($data)>0){
		   // $this->deleteTable('aset.tm_masterhm');
		// }
		 $db->beginTransaction();
		 //print_r($data);
		 for($i=0;$i<count($data);$i++){
		 //for($i=0;$i<3;$i++){
		 unset($where);
		 $cektmskel=$this->cekTMSKEL($data[$i][$i]['kd_skelbrg'],$data[$i]['kd_gol'],$data[$i]['kd_bid'],$data[$i]['kd_kel'],$data[$i]['kd_skel']);
		
	       $hasil=array(      "kd_gol"=>$data[$i]['kd_gol'],		                      
		                      "kd_bid"=>$data[$i]['kd_bid'],
							  "kd_kel"=>$data[$i]['kd_kel'],
							  "kd_skel"=>$data[$i]['kd_skel'],							 
							  "ur_sskel"=>$data[$i]['ur_sskel'],		                   
							  "kd_skelbrg"=>$data[$i]['kd_skelbrg'],	                   
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
		   //echo "Cekt master : ".$cetmmasterhm."\n";
		   //print_r($hasil);
		   if($cektmskel==0){
		   //echo "Insert\n";
		   $db->insert('aset.tm_skel',$hasil);
		   $jml++;
		   }
		   if($cektmskel>0){
            //echo "Update\n";
             //echo "Data user id : ".$hasil['i_entry']."\n";    
		     $where[]="kd_skelbrg='".$data[$i]['kd_skelbrg']."'";   
		     $where[]="kd_gol='".$data[$i]['kd_gol']."'";   
		     $where[]="kd_bid='".$data[$i]['kd_bid']."'";   
		     $where[]="kd_kel='".$data[$i]['kd_kel']."'";   
		     $where[]="kd_skel='".$data[$i]['kd_skel']."'";   
             //print_r($where);			 
	         $db->update('aset.tm_skel',$hasil,$where);
			 $jml++;
		   }
		   $idx++;
		 }
	     
		   
		 $db->commit();
		 
		 // $x=array("namatabel"=>"tm_skel","jumlah"=>$jml);
	     // return $x;
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
		 $this->tuliskelog($e->getMessage().'\n');
	     return 'gagal';
	   }
	}	
	 public function tuliskelog($isilog){
	    try{
		    
		    $myFile = "D:/Project/MA/ma_project/public/data/aset/LOG/LogPushDataSimak.log";			
		    //$myFile = "/opt/ma_project/public/data/aset/LOG/LogPushDataSimak.log";			
			$fh = fopen($myFile, 'a') or die("can't open file");
			$stringData = $isilog;
			fwrite($fh, $stringData);			
			fclose($fh);
		}catch(Exception $ex){
		  $ex->getMessage();
		}
	  }

	public function cekTMKDP($kdlok,$kdbrg,$noaset,$nosppa,$tglbuku){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  //$db->setFetchMode(Zend_Db::FETCH_OBJ); 
	 if($tglbuku=='null'){
		    $tglbuku1="null";
		   }
		   if($tglbuku!='null'){
		    $tglbuku1="'".$tglbuku."'";
		   }
	  $sql = "select kd_lokasi from aset.tm_kdp where kd_lokasi='".$kdlok."'
	          and kd_brg='".$kdbrg."' and no_aset='".$noaset."' and no_sppa='".$nosppa."' and tgl_buku=".$tglbuku1."";
	  //echo $sql."\n";
	  $result = $db->fetchOne($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
	}
	  public function insertTMKDPfromDBF(array $data,$userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $idx=0;
		 $jml=0;
		// if(count($data)>0){
		   // $this->deleteTable('aset.tm_masterhm');
		// }
		 $db->beginTransaction();
		 //print_r($data);
		 for($i=0;$i<count($data);$i++){
		 //for($i=0;$i<3;$i++){
		 unset($where);
		
		   if($data[$i]['tgl_buku']=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_buku']=null;
		   }
		   if(strlen(trim($data[$i]['tgl_buku']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_buku']=null;
		   }
		    if($data[$i]['tgl_mulai']=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_mulai']=null;
		   }
		   if(strlen(trim($data[$i]['tgl_mulai']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_mulai']=null;
		   }
		    if($data[$i]['tgl_akhir']=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_akhir']=null;
		   }
		   if(strlen(trim($data[$i]['tgl_akhir']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_akhir']=null;
		   }
		    if($data[$i]['tgl_perlh']=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_perlh']=null;
		   }
		   if(strlen(trim($data[$i]['tgl_perlh']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_perlh']=null;
		   }
		   
		    if(strlen(trim($data[$i]['no_aset']))==0){
		   $data[$i]['no_aset']=0;
		 }
		  if(strlen(trim($data[$i]['rph_kdp']))==0){
		   $data[$i]['rph_kdp']=0;
		 }
		  if(strlen(trim($data[$i]['rph_kontrak']))==0){
		   $data[$i]['rph_kontrak']=0;
		 }
		  if(strlen(trim($data[$i]['valas_kdp']))==0){
		   $data[$i]['valas_kdp']=0;
		 }
		  if(strlen(trim($data[$i]['persen']))==0){
		   $data[$i]['persen']=0;
		 }
		  $cektmkdp=$this->cekTMKDP($data[$i]['kd_lokasi'],$data[$i]['kd_brg'],$data[$i]['no_aset'],$data[$i]['no_sppa'],$data[$i]['tgl_buku']);
	       $hasil=array("kd_lokasi"=>$data[$i]['kd_lokasi'],
		                      "kd_brg"=>$data[$i]['kd_brg'],
		                      "no_aset"=>$data[$i]['no_aset'],
		                      "no_sppa"=>$data[$i]['no_sppa'],							  
		                      "tgl_buku"=>$data[$i]['tgl_buku'],
		                      "rph_kdp"=>$data[$i]['rph_kdp'],		                      
		                      "fungsi"=>$data[$i]['fungsi'],
		                      "sfungsi"=>$data[$i]['sfungsi'],		                      
		                      "kdprog"=>$data[$i]['kdprog'],
		                      "kdgiat"=>$data[$i]['kdgiat'],
		                      "no_kontrak"=>$data[$i]['no_kontrak'],
		                      "no_bukti"=>$data[$i]['no_bukti'],
		                      "keterangan"=>$data[$i]['keterangan'],
		                      "nm_kontrak"=>$data[$i]['nm_kontrak'],
		                      "alm_kontrak"=>$data[$i]['alm_kontrak'],
		                      "cr_bangun"=>$data[$i]['cr_bangun'],
		                      "tgl_mulai"=>$data[$i]['tgl_mulai'],
		                      "tgl_akhir"=>$data[$i]['tgl_akhir'],
		                      "rph_kontrak"=>$data[$i]['rph_kontrak'],
		                      "valas_kdp"=>$data[$i]['valas_kdp'],
		                      "kondisi"=>$data[$i]['kondisi'],
		                      "flag_krm"=>$data[$i]['flag_krm'],
		                      "thp_kdp"=>$data[$i]['thp_kdp'],
		                      "jns_trn"=>$data[$i]['jns_trn'],
		                      "thn_ang"=>$data[$i]['thn_ang'],
		                      "sppa_aset"=>$data[$i]['sppa_aset'],
		                      "persen"=>$data[$i]['persen'],
		                      "tgl_perlh"=>$data[$i]['tgl_perlh'],
		                      "lokasi"=>$data[$i]['lokasi'],
		                      "kdblu"=>$data[$i]['kdblu'],
		                      "kdbapel"=>$data[$i]['kdbapel'],
		                      "kdkpknl"=>$data[$i]['kdkpknl'],
		                      "kdkppn"=>$data[$i]['kdkppn'],
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
		   //echo "Cekt master : ".$cetmmasterhm."\n";
		   //print_r($hasil);
		   if($cektmkdp==0){
		   //echo "Insert\n";
		   $db->insert('aset.tm_kdp',$hasil);
		   $jml++;
		   }
		   if($cektmkdp>0){
            //echo "Update\n";
             //echo "Data user id : ".$hasil['i_entry']."\n";
             
		     $where[]="kd_lokasi='".$data[$i]['kd_lokasi']."'";   
		     $where[]="kd_brg='".$data[$i]['kd_brg']."'";   
		     $where[]="no_aset='".$data[$i]['no_aset']."'";   
		     $where[]="no_sppa='".$data[$i]['no_sppa']."'";   
		     $where[]="tgl_buku='".$data[$i]['tgl_buku']."'";   
             //print_r($where);			 
	         $db->update('aset.tm_kdp',$hasil,$where);
			 $jml++;
		   }
		   $idx++;
		 }
	     
		   
		 $db->commit();
		 
		 // $x=array("namatabel"=>"tm_kdp","jumlah"=>$jml);
	     // return $x;
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
		 $this->tuliskelog($e->getMessage().'\n');
	     return 'gagal';
	   }
	}
	public function cekTMKONDISI($nosppa,$kdlok,$kdbrg,$noaset){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  //$db->setFetchMode(Zend_Db::FETCH_OBJ); 
	$sql = "select no_sppa from aset.tm_kondisi where no_sppa='".$nosppa."' and kd_lokasi='".$kdlok."'
	          and kd_brg='".$kdbrg."' and no_aset='".$noaset."'";
	  //echo $sql."\n";
	  $result = $db->fetchOne($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
	}
	  public function insertTMKONDISIfromDBF(array $data,$userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $idx=0;
		 $jml=0;
		// if(count($data)>0){
		   // $this->deleteTable('aset.tm_masterhm');
		// }
		 $db->beginTransaction();
		 //print_r($data);
		 for($i=0;$i<count($data);$i++){
		 //for($i=0;$i<3;$i++){
		 unset($where);
		 
		   if($data[$i]['tgl_buku']=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_buku']=null;
		   }
		   if(strlen(trim($data[$i]['tgl_buku']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_buku']=null;
		   }
		    if(strlen(trim($data[$i]['no_aset']))==0){
		   $data[$i]['no_aset']=0;
		 }
		 $cektmkondisi=$this->cekTMKONDISI($data[$i]['no_sppa'],$data[$i]['kd_lokasi'],$data[$i]['kd_brg'],$data[$i]['no_aset']);
	       $hasil=array("no_sppa"=>$data[$i]['no_sppa'],
		                      "kd_lokasi"=>$data[$i]['kd_lokasi'],
		                      "kd_brg"=>$data[$i]['kd_brg'],
		                      "no_aset"=>$data[$i]['no_aset'],							  
		                      "kondisi"=>$data[$i]['kondisi'],
		                      "flag_kor"=>$data[$i]['flag_kor'],
		                      "kd_data"=>$data[$i]['kd_data'],
		                      "tgl_buku"=>$data[$i]['tgl_buku'],
		                      "kdbapel"=>$data[$i]['kdbapel'],
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
		   //echo "Cekt master : ".$cetmmasterhm."\n";
		   //print_r($hasil);
		   if($cektmkondisi==0){
		   //echo "Insert\n";
		   $db->insert('aset.tm_kondisi',$hasil);
		   $jml++;
		   }
		   if($cektmkondisi>0){
            //echo "Update\n";
             //echo "Data user id : ".$hasil['i_entry']."\n";
            
		     $where[]="no_sppa='".$data[$i]['no_sppa']."'";   
		     $where[]="kd_lokasi='".$data[$i]['kd_lokasi']."'";   
		     $where[]="kd_brg='".$data[$i]['kd_brg']."'";   
		     $where[]="no_aset='".$data[$i]['no_aset']."'";    
             //print_r($where);			 
	         $db->update('aset.tm_kondisi',$hasil,$where);
			 $jml++;
		   }
		   $idx++;
		 }
	     
		   
		 $db->commit();
		 
		 // $x=array("namatabel"=>"tm_kondisi","jumlah"=>$jml);
	     // return $x;
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
		 $this->tuliskelog($e->getMessage().'\n');
	     return 'gagal';
	   }
	}	
	public function cekTMSEDIA($thnang,$kdlok,$kdbrg,$periode){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  //$db->setFetchMode(Zend_Db::FETCH_OBJ); 
	$sql = "select thn_ang from aset.tm_sedia where thn_ang='".$thnang."' 
	  and kd_lokasi='".$kdlok."' and kd_brg='".$kdbrg."' and periode='".$periode."' ";
	  //echo $sql."\n";
	  $result = $db->fetchOne($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
	}
	  public function insertTMSEDIAfromDBF(array $data,$userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $idx=0;
		 $jml=0;
		// if(count($data)>0){
		   // $this->deleteTable('aset.tm_masterhm');
		// }
		 $db->beginTransaction();
		 //print_r($data);
		 for($i=0;$i<count($data);$i++){
		 //for($i=0;$i<3;$i++){
		 unset($where);
		
		   if($data[$i]['tgl_buku']=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_buku']=null;
		   }
		   if(strlen(trim($data[$i]['tgl_buku']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_buku']=null;
		   }
		    if(strlen(trim($data[$i]['kuantitas']))==0){
		   $data[$i]['kuantitas']=0;
		 }
		  if(strlen(trim($data[$i]['rph_sat']))==0){
		   $data[$i]['rph_sat']=0;
		 }
		  if(strlen(trim($data[$i]['rph_aset']))==0){
		   $data[$i]['rph_aset']=0;
		 }
		  if(strlen(trim($data[$i]['periode']))==0){
		   $data[$i]['periode']=0;
		 }
		  $cektmsedia=$this->cekTMSEDIA($data[$i]['thn_ang'],$data[$i]['kd_lokasi'],$data[$i]['kd_brg'],$data[$i]['periode']);
	       $hasil=array("thn_ang"=>$data[$i]['thn_ang'],
		                      "kd_lokasi"=>$data[$i]['kd_lokasi'],
		                      "kd_brg"=>$data[$i]['kd_brg'],
		                      "kuantitas"=>$data[$i]['kuantitas'],							  
		                      "rph_sat"=>$data[$i]['rph_sat'],
		                      "rph_aset"=>$data[$i]['rph_aset'],
		                      "periode"=>$data[$i]['periode'],
		                      "jns_krm"=>$data[$i]['jns_krm'],
		                      "tgl_buku"=>$data[$i]['tgl_buku'],
		                      "kdblu"=>$data[$i]['kdblu'],
		                      "kdbapel"=>$data[$i]['kdbapel'],
		                      "kdkpknl"=>$data[$i]['kdkpknl'],
		                      "kdkppn"=>$data[$i]['kdkppn'],
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
		   //echo "Cekt master : ".$cetmmasterhm."\n";
		   //print_r($hasil);
		   if($cektmsedia==0){
		   //echo "Insert\n";
		   $db->insert('aset.tm_sedia',$hasil);
		   $jml++;
		   }
		   if($cektmsedia>0){
            //echo "Update\n";
             //echo "Data user id : ".$hasil['i_entry']."\n";            
		     $where[]="thn_ang='".$data[$i]['thn_ang']."'";   
		     $where[]="kd_lokasi='".$data[$i]['kd_lokasi']."'";   
		     $where[]="kd_brg='".$data[$i]['kd_brg']."'";   
		     $where[]="periode='".$data[$i]['periode']."'";    
             //print_r($where);			 
	         $db->update('aset.tm_sedia',$hasil,$where);
			 $jml++;
		   }
		   $idx++;
		 }
	     
		   
		 $db->commit();
		 
		 // $x=array("namatabel"=>"tm_sedia","jumlah"=>$jml);
	     // return $x;
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
		 $this->tuliskelog($e->getMessage().'\n');
	     return 'gagal';
	   }
	}	
	public function cekTMSEJARAH($kdlok,$kdbrg,$noaset,$jnstrn,$nosppa){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  //$db->setFetchMode(Zend_Db::FETCH_OBJ); 
	  $sql = "select kd_lokasi from aset.tm_sejarah where kd_lokasi='".$kdlok."' 
	  and kd_brg='".$kdbrg."' and no_aset='".$noaset."' and jns_trn='".$noaset."'
	  and no_sppa='".$nosppa."'";
	  //echo $sql."\n";
	  $result = $db->fetchOne($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
	}
	  public function insertTMSEJARAHfromDBF(array $data,$userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $idx=0;
		 $jml=0;
		// if(count($data)>0){
		   // $this->deleteTable('aset.tm_masterhm');
		// }
		 $db->beginTransaction();
		 //print_r($data);
		 for($i=0;$i<count($data);$i++){
		 //for($i=0;$i<3;$i++){
		 unset($where);
		
		   if($data[$i]['tgl_perlh']=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_perlh']=null;
		   }
		   if(strlen(trim($data[$i]['tgl_perlh']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_perlh']=null;
		   }
		    if($data[$i]['tgl_buku']=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_buku']=null;
		   }
		   if(strlen(trim($data[$i]['tgl_buku']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_buku']=null;
		   }
		    if($data[$i]['tgl_dsr_mts']=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_dsr_mts']=null;
		   }
		   if(strlen(trim($data[$i]['tgl_dsr_mts']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_dsr_mts']=null;
		   }
		    if(strlen(trim($data[$i]['no_aset']))==0){
		   $data[$i]['no_aset']=0;
		 }
		  if(strlen(trim($data[$i]['kuantitas']))==0){
		   $data[$i]['kuantitas']=0;
		 }
		  if(strlen(trim($data[$i]['rph_aset']))==0){
		   $data[$i]['rph_aset']=0;
		 }
		  $cektmsejarah=$this->cekTMSEJARAH($data[$i]['kd_lokasi'],$data[$i]['kd_brg'],$data[$i]['no_aset'],$data[$i]['jns_trn'],$data[$i]['no_sppa']);
	       $hasil=array("thn_ang"=>$data[$i]['thn_ang'],
		                      "kd_lokasi"=>$data[$i]['kd_lokasi'],
		                      "kd_brg"=>$data[$i]['kd_brg'],
		                      "no_aset"=>$data[$i]['no_aset'],
		                      "no_sppa"=>$data[$i]['no_sppa'],
		                      "tgl_perlh"=>$data[$i]['tgl_perlh'],
		                      "tgl_buku"=>$data[$i]['tgl_buku'],
		                      "kondisi"=>$data[$i]['kondisi'],
		                      "keterangan"=>$data[$i]['keterangan'],
		                      "jns_trn"=>$data[$i]['jns_trn'],
		                      "asal_perlh"=>$data[$i]['asal_perlh'],		                      
		                      "no_bukti"=>$data[$i]['no_bukti'],		                      
		                      "no_dsr_mts"=>$data[$i]['no_dsr_mts'],		                      
		                      "tgl_dsr_mts"=>$data[$i]['tgl_dsr_mts'],			                      
		                      "merk_type"=>$data[$i]['merk_type'],			                      
		                      "kuantitas"=>$data[$i]['kuantitas'],	
		                      "rph_aset"=>$data[$i]['rph_aset'],		                      
		                      "kd_data"=>$data[$i]['kd_data'],		                      
		                      "flag_kor"=>$data[$i]['flag_kor'],		                      
		                      "lokasi"=>$data[$i]['lokasi'],		                      
		                      "tercatat"=>$data[$i]['tercatat'],		                      
		                      "kdbapel"=>$data[$i]['kdbapel'],		                      
		                      "kdkpknl"=>$data[$i]['kdkpknl'],		                      
		                      "kdkppn"=>$data[$i]['kdkppn'],		                      
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      );
		   //echo "Cekt master : ".$cetmmasterhm."\n";
		   //print_r($hasil);
		   if($cektmsedia==0){
		   //echo "Insert\n";
		   $db->insert('aset.tm_sejarah',$hasil);
		   $jml++;
		   }
		   if($cektmsedia>0){
            //echo "Update\n";
             //echo "Data user id : ".$hasil['i_entry']."\n"; 
		     $where[]="kd_lokasi='".$data[$i]['kd_lokasi']."'";   
		     $where[]="kd_brg='".$data[$i]['kd_brg']."'";   
		     $where[]="no_aset='".$data[$i]['no_aset']."'";    
		     $where[]="jns_trn='".$data[$i]['jns_trn']."'";    
		     $where[]="no_sppa='".$data[$i]['no_sppa']."'";    
             //print_r($where);			 
	         $db->update('aset.tm_sejarah',$hasil,$where);
			 $jml++;
		   }
		   $idx++;
		 }
	     
		   
		 $db->commit();
		 
		 // $x=array("namatabel"=>"tm_sejarah","jumlah"=>$jml);
	     // return $x;
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
		 $this->tuliskelog($e->getMessage().'\n');
	     return 'gagal';
	   }
	}
	public function cekTMSPMKDP($thnang,$kdlok,$kdbrg,$nosp2d,$bkpk){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  //$db->setFetchMode(Zend_Db::FETCH_OBJ); 
	
	  $sql = "select thn_ang from aset.tm_spmkdp where thn_ang='".$thnang."' 
	  and kd_lokasi='".$kdlok."' and kd_brg='".$kdbrg."' and no_sp2d='".$nosp2d."'
	  and bkpk='".$bkpk."'";
	  //echo $sql."\n";
	  $result = $db->fetchOne($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
	}
	  public function insertTMSPMKDPfromDBF(array $data,$userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $idx=0;
		 $jml=0;
		// if(count($data)>0){
		   // $this->deleteTable('aset.tm_masterhm');
		// }
		  
		 $db->beginTransaction();
		 //print_r($data);
		 for($i=0;$i<count($data);$i++){
		 //for($i=0;$i<3;$i++){
		 unset($where);
		
		   if($data[$i]['tgl_sp2d']=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_sp2d']=null;
		   }
		   if(strlen(trim($data[$i]['tgl_sp2d']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_sp2d']=null;
		   }
		    if($data[$i]['tgl_buku']=='0000-00-00'){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_buku']=null;
		   }
		   if(strlen(trim($data[$i]['tgl_buku']))==0){
		   //echo "record ke - : ".$idx;
		   $data[$i]['tgl_buku']=null;
		   }
		   if(strlen(trim($data[$i]['no_sp2d']))==0){
		     $data[$i]['no_sp2d']="00000";
		   }
		   if(strlen(trim($data[$i]['bkpk']))==0){
		     $data[$i]['bkpk']="0000";
		   }
		    if(strlen(trim($data[$i]['no_aset']))==0){
		     $data[$i]['no_aset']=0;
		   }
		   if(strlen(trim($data[$i]['jml_spm']))==0){
		     $data[$i]['jml_spm']=0;
		   }
		     if(strlen(trim($data[$i]['rph_kdp']))==0){
		     $data[$i]['rph_kdp']=0;
		   }
		   $cektmspmkdp=$this->cekTMSPMKDP($data[$i]['thn_ang'],$data[$i]['kd_lokasi'],$data[$i]['kd_brg'],$data[$i]['no_sp2d'],$data[$i]['bkpk']);
	       $hasil=array("thn_ang"=>$data[$i]['thn_ang'],
		                      "kd_lokasi"=>$data[$i]['kd_lokasi'],
		                      "kd_brg"=>$data[$i]['kd_brg'],
		                      "no_aset"=>$data[$i]['no_aset'],
		                      "no_sppa"=>$data[$i]['no_sppa'],
		                      "no_sp2d"=>$data[$i]['no_sp2d'],
		                      "tgl_sp2d"=>$data[$i]['tgl_sp2d'],
		                      "bkpk"=>$data[$i]['bkpk'],
		                      "jml_spm"=>$data[$i]['jml_spm'],
		                      "rph_kdp"=>$data[$i]['rph_kdp'],
		                      "jns_trn"=>$data[$i]['jns_trn'],		                      
		                      "tgl_buku"=>$data[$i]['tgl_buku'],		                      
		                      "keterangan"=>$data[$i]['keterangan'],		                      
		                      "cad1"=>$data[$i]['cad1'],			                      
							  "i_entry"=>$userid,
                              "d_entry"=>date("Y-m-d")							  
		                      ); 
		   //echo "Cekt master : ".$cetmmasterhm."\n";
		   //print_r($hasil);
		   if($cektmspmkdp==0){
		   //echo "Insert\n";
		   $db->insert('aset.tm_spmkdp',$hasil);
		   $jml++;
		   }
		   if($cektmspmkdp>0){
            //echo "Update\n";
             //echo "Data user id : ".$hasil['i_entry']."\n"; 
		     $where[]="thn_ang='".$data[$i]['thn_ang']."'";   
		     $where[]="kd_lokasi='".$data[$i]['kd_lokasi']."'";   
		     $where[]="kd_brg='".$data[$i]['kd_brg']."'";    
		     $where[]="no_sp2d='".$data[$i]['no_sp2d']."'";    
		     $where[]="bkpk='".$data[$i]['bkpk']."'";    
             //print_r($where);			 
	         $db->update('aset.tm_spmkdp',$hasil,$where);
			 $jml++;
		   }
		   $idx++;
		 }
	     
		   
		 $db->commit();
		 
		 // $x=array("namatabel"=>"tm_spmkdp","jumlah"=>$jml);
	     // return $x;
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         //echo $e->getMessage().'<br>';
		 $this->tuliskelog($e->getMessage().'\n');
	     return 'gagal';
	   }
	}	
	public function deletetabelpushdata($namatbl,$kdsatker){
	    $registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try{
		$db->beginTransaction();
		$key[] = "n_table = '".$namatbl."'";			
		$key[] = "i_entry = '".$kdsatker."'";			
		$db->delete("aset.tm_pushdata",$key);
		$db->commit();
		$db->closeConnection();
		return 'sukses';
		}catch(Exception $ex){
		echo $e->getMessage().'<br>';
		$db->closeConnection();
		return 'gagal <br>';
		}
	 }
	 public function cekPushData($namatabel,$kodesatker){
	  $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $sql = "select n_table from aset.tm_pushdata where n_table='".$namatabel."' 
	  and i_entry='".$kodesatker."' and c_setuju_korwil is null and c_setuju_pusat is null";
	  $result = $db->fetchOne($sql);
      if($result>0){
	    $this->deletetabelpushdata($namatabel,$kodesatker);
      }	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
     }	 	
     public function inserttmuploaddetail($data){
	     $registry = Zend_Registry::getInstance();
	     $db = $registry->get('db');
        try{		 
		 $db->beginTransaction();
		 $datatmuploaddetail=array("id"=>$data['id'],"n_file"=>$data['n_file'],"n_table"=>$data['n_table'],"q_data"=>$data['q_data'],
		                  "i_entry"=>$data['i_entry'],"d_entry"=>date('Y-m-d'));	
         //print_r($datatmkangk);						  
		  $db->insert('aset.tm_file_uploaddetail',$datatmuploaddetail);
		  $db->commit();
		 }catch(Exception $ex){
		  echo $ex->getMessage();
		 }
	 }
	 public function inserttmpushdata($data){
	   $this->deletetabelpushdata($data['n_table'],$data['i_entry'],substr($data['n_table'],5,strlen($data['n_table'])));	   
         $registry = Zend_Registry::getInstance();
	     $db = $registry->get('db');
        try{		 
		 $db->beginTransaction();
		 $datatmpush=array("n_table"=>$data['n_table'],"q_data"=>$data['q_data'],
		                  "i_entry"=>$data['i_entry'],"d_entry"=>date('Y-m-d'));	
         //print_r($datatmkangk);						  
		  $db->insert('aset.tm_pushdata',$datatmpush);
		  $db->commit();
		 }catch(Exception $ex){
		  echo $ex->getMessage();
		 }
	 }
	 public function getTMPUSHDATA($satker){
	 $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	    $sql = "select n_table,q_data
				from aset.tm_pushdata
				where i_entry = '$satker'";
		$result = $db->fetchAll($sql);
        $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("n_table"           =>(string)$result[$j]->n_table,
								   "q_data"           =>(string)$result[$j]->q_data);
		  }	
        }		  
		return $hasilAkhir;
	   }catch(Exception $ex){
	    echo $e->getMessage().'<br>';
	    return 'Data Tidak Ada <br>';
	   }
	 }
    public function insertPostgrefromDBF($perintah,$pageNumber,$itemPerPage,$namatabel,$data,$userid){
	 
	 //if($perintah=='PROSES PUSH DATA'){
	 //echo "jumlah data : ".count($data);
	 //print_r($data);
	 
	  if($namatabel=="aset.tm_gol"){
        //echo "nama table : ".$namatabel."<br>";	  
	    //$hasilTMGOL=$this->insertTMGOLfromDBF($data,$userid); 
	    $hasil=$this->insertTMGOLfromDBF($data,$userid); 
        //echo "jumlah tm dil : ".count($hasilTMDIL)."<br>";
		 // if(count($hasilTMGOL)>0){
         // $this->deleteTableLogPushData('aset.tm_pushdata',$userid,"tm_gol");		
         // $registry = Zend_Registry::getInstance();
	     // $db = $registry->get('db');		
		 // $db->beginTransaction();
		 // $datatmgol=array("n_table"=>$hasilTMGOL['namatabel'],"q_data"=>$hasilTMGOL['jumlah'],
		                  // "i_entry"=>$userid,"d_entry"=>date('Y-m-d'));		 
		 // $db->insert('aset.tm_pushdata',$datatmgol);
		 // $db->commit();
		 // }	  
	  }
	  if($namatabel=="aset.tm_bid"){
        //echo "nama table : ".$namatabel."<br>";	  
	    //$hasilTMBID=$this->insertTMBIDfromDBF($data,$userid); 
	    $hasil=$this->insertTMBIDfromDBF($data,$userid); 
        //echo "jumlah tm dil : ".count($hasilTMDIL)."<br>";
		 // if(count($hasilTMBID)>0){
         // $this->deleteTableLogPushData('aset.tm_pushdata',$userid,"tm_bid");		
         // $registry = Zend_Registry::getInstance();
	     // $db = $registry->get('db');		
		 // $db->beginTransaction();
		 // $datatmbid=array("n_table"=>$hasilTMBID['namatabel'],"q_data"=>$hasilTMBID['jumlah'],
		                  // "i_entry"=>$userid,"d_entry"=>date('Y-m-d'));		 
		 // $db->insert('aset.tm_pushdata',$datatmbid);
		 // $db->commit();
		 // }	  
	  }
	  if($namatabel=="aset.tm_croleh"){
        //echo "nama table : ".$namatabel."<br>";	  
	    //$hasilTMCROLEH=$this->insertTMCROLEHfromDBF($data,$userid); 
	    $hasil=$this->insertTMCROLEHfromDBF($data,$userid); 
        //echo "jumlah tm dil : ".count($hasilTMDIL)."<br>";
		 // if(count($hasilTMCROLEH)>0){
         // $this->deleteTableLogPushData('aset.tm_pushdata',$userid,"tm_croleh");		
         // $registry = Zend_Registry::getInstance();
	     // $db = $registry->get('db');		
		 // $db->beginTransaction();
		 // $datatmcroleh=array("n_table"=>$hasilTMCROLEH['namatabel'],"q_data"=>$hasilTMCROLEH['jumlah'],
		                  // "i_entry"=>$userid,"d_entry"=>date('Y-m-d'));		 
		 // $db->insert('aset.tm_pushdata',$datatmcroleh);
		 // $db->commit();
		 // }	  
	  }
	  if($namatabel=="aset.tm_dil"){	
        //echo "nama table : ".$namatabel."<br>";	  
	    //$hasilTMDIL=$this->insertTMDILfromDBF($data,$userid); 
	    $hasil=$this->insertTMDILfromDBF($data,$userid); 
        //echo "jumlah tm dil : ".count($hasilTMDIL)."<br>";
		 // if(count($hasilTMDIL)>0){
         // $this->deleteTableLogPushData('aset.tm_pushdata',$userid,"tm_dil");		
         // $registry = Zend_Registry::getInstance();
	     // $db = $registry->get('db');		
		 // $db->beginTransaction();
		 // $datatmdil=array("n_table"=>$hasilTMDIL['namatabel'],"q_data"=>$hasilTMDIL['jumlah'],
		                  // "i_entry"=>$userid,"d_entry"=>date('Y-m-d'));		 
		 // $db->insert('aset.tm_pushdata',$datatmdil);
		 // $db->commit();
		 // }
        		
	  }
	  if($namatabel=="aset.tm_dir"){
	   // echo "nama table : ".$namatabel."<br>";
	    //$hasilTMDIR=$this->insertTMDIRfromDBF($data,$userid);	
	    $hasil=$this->insertTMDIRfromDBF($data,$userid);	
       // echo "jumlah hasilTMDIR : ".count($hasilTMDIR)."<br>";
	   // if(count($hasilTMDIR)>0){
         // $this->deleteTableLogPushData('aset.tm_pushdata',$userid,"tm_dir");
         // $registry = Zend_Registry::getInstance();
	     // $db = $registry->get('db');		
		 // $db->beginTransaction();
		 // $datatmdir=array("n_table"=>$hasilTMDIR['namatabel'],"q_data"=>$hasilTMDIR['jumlah'],
		                  // "i_entry"=>$userid,"d_entry"=>date('Y-m-d'));		 
		 // $db->insert('aset.tm_pushdata',$datatmdir);
		 // $db->commit();
		 // }
        				
	  }
	  if($namatabel=="aset.tm_kangk"){
	    //echo "nama table : ".$namatabel."<br>";
	    //$hasilTMKANGK=$this->insertTMKANGKfromDBF($data,$userid);
	    $hasil=$this->insertTMKANGKfromDBF($data,$userid);
         //echo "jumlah hasilTMKANGK : ".count($hasilTMKANGK)."<br>";	
		 // if(count($hasilTMKANGK)>0){
         // $this->deleteTableLogPushData('aset.tm_pushdata',$userid,"tm_kangk");
         // $registry = Zend_Registry::getInstance();
	     // $db = $registry->get('db');			 
		 // $db->beginTransaction();
		 // $datatmkangk=array("n_table"=>$hasilTMKANGK['namatabel'],"q_data"=>$hasilTMKANGK['jumlah'],
		                  // "i_entry"=>$userid,"d_entry"=>date('Y-m-d'));	
        					  
		  // $db->insert('aset.tm_pushdata',$datatmkangk);
		  // $db->commit();
         // }	
	  }
	  if($namatabel=="aset.tm_kbdg"){
	    //echo "nama table : ".$namatabel."<br>";
	    //$hasilTMKBDG=$this->insertTMKBDGfromDBF($data,$userid);
	    $hasil=$this->insertTMKBDGfromDBF($data,$userid);
        //echo "jumlah hasilTMKBDG : ".count($hasilTMKBDG)."<br>";
        // if(count($hasilTMKBDG)>0){
         // $this->deleteTableLogPushData('aset.tm_pushdata',$userid,"tm_kbdg");		
         // $registry = Zend_Registry::getInstance();
	     // $db = $registry->get('db');			
		 // $db->beginTransaction();
		 // $datatmkbdg=array("n_table"=>$hasilTMKBDG['namatabel'],"q_data"=>$hasilTMKBDG['jumlah'],
		                  // "i_entry"=>$userid,"d_entry"=>date('Y-m-d'));		 
		 // $db->insert('aset.tm_pushdata',$datatmkbdg);
		 // $db->commit();
         // }				
	  }
	  if($namatabel=="aset.tm_ktnh"){
	    //echo "nama table : ".$namatabel."<br>";
	    //$hasilTMKTNH=$this->insertTMKTNHfromDBF($data,$userid);	
	    $hasil=$this->insertTMKTNHfromDBF($data,$userid);	
         //echo "jumlah hasilTMKTNH : ".count($hasilTMKTNH)."<br>";
		  // if(count($hasilTMKTNH)>0){
         // $this->deleteTableLogPushData('aset.tm_pushdata',$userid,"tm_ktnh");	
         // $registry = Zend_Registry::getInstance();
	     // $db = $registry->get('db');			 
		 // $db->beginTransaction();
		 // $datatmktnh=array("n_table"=>$hasilTMKTNH['namatabel'],"q_data"=>$hasilTMKTNH['jumlah'],
		                  // "i_entry"=>$userid,"d_entry"=>date('Y-m-d'));
        					  
		  // $db->insert('aset.tm_pushdata',$datatmktnh);
		  // $db->commit();
       	 // }
	  }
	  if($namatabel=="aset.tm_masterhm"){
	    //echo "nama table : ".$namatabel."<br>";
	    //$hasilTMMASTERHM=$this->insertTMMASTERHMfromDBF($data,$userid);		
	    $hasil=$this->insertTMMASTERHMfromDBF($data,$userid);		
		//echo "jumlah hasilTMMASTERHM : ".count($hasilTMMASTERHM)."<br>";
		 // if(count($hasilTMMASTERHM)>0){
         // $this->deleteTableLogPushData('aset.tm_pushdata',$userid,"tm_masterhm");
         // $registry = Zend_Registry::getInstance();
	     // $db = $registry->get('db');			 
		 // $db->beginTransaction();
		 // $datatmmasterhm=array("n_table"=>$hasilTMMASTERHM['namatabel'],"q_data"=>$hasilTMMASTERHM['jumlah'],
		                  // "i_entry"=>$userid,"d_entry"=>date('Y-m-d'));		 
		
		 // $cektmpushdata=$this->cekPushData($hasilTMMASTERHM['namatabel'],$hasilTMMASTERHM['i_entry']);
		 // $db->insert('aset.tm_pushdata',$datatmmasterhm);
		 // $db->commit();
         // }		
	  }
	  if($namatabel=="aset.tm_masteru"){
	    //echo "nama table : ".$namatabel."<br>";
	    //$hasilTMMASTERU=$this->insertTMMASTERUfromDBF($data,$userid);
	    $hasil=$this->insertTMMASTERUfromDBF($data,$userid);
	    //$hasilTMMASTERU=$this->insertTMMASTERUfromDBF1($data,$userid);
         // if(count($hasilTMMASTERU)>0){
         // $this->deleteTableLogPushData('aset.tm_pushdata',$userid,"tm_masteru");
         // $registry = Zend_Registry::getInstance();
	     // $db = $registry->get('db');			
		 // $db->beginTransaction();
		 // $datatmmasteru=array("n_table"=>$hasilTMMASTERU['namatabel'],"q_data"=>$hasilTMMASTERU['jumlah'],
		                  // "i_entry"=>$userid,"d_entry"=>date('Y-m-d'));	
         //print_r($datatmmasteru);						  
		  //$db->insert('aset.tm_pushdata',$datatmmasteru);
		  //$db->commit();
       	// }		
	  }
	  if($namatabel=="aset.tm_ruang"){
	  //echo "nama table : ".$namatabel."<br>";
	    //$hasilTMRU=$this->insertTMRUANGfromDBF($data,$userid);	
	    $hasil=$this->insertTMRUANGfromDBF($data,$userid);	
        //echo "jumlah hasilTMRU : ".count($hasilTMRU)."<br>";	
		 // if(count($hasilTMRU)>0){
		 // $this->deleteTableLogPushData('aset.tm_pushdata',$userid,"tm_ruang");	
         // $registry = Zend_Registry::getInstance();
	     // $db = $registry->get('db');			
		 // $db->beginTransaction();
		 // $datatmru=array("n_table"=>$hasilTMRU['namatabel'],"q_data"=>$hasilTMRU['jumlah'],
		                  // "i_entry"=>$userid,"d_entry"=>date('Y-m-d'));		 
		 // $db->insert('aset.tm_pushdata',$datatmru);
		 // $db->commit();
		 // }
        				
	  }
	  
	  if($namatabel=="aset.tm_spmkdp"){
	 
	    //$hasilTMSPKDP=$this->insertTMSPMKDPfromDBF($data,$userid);	
	    $hasil=$this->insertTMSPMKDPfromDBF($data,$userid);	
	    //$hasilTMSPKDP=$this->insertTMSPMKDPfromDBF1($data,$userid);	
        //echo "jumlah hasilTMRU : ".count($hasilTMRU)."<br>";	
		 // if(count($hasilTMSPKDP)>0){
		 // $this->deleteTableLogPushData('aset.tm_pushdata',$userid,"tm_spmkdp");	
         // $registry = Zend_Registry::getInstance();
	     // $db = $registry->get('db');			
		 // $db->beginTransaction();
		 // $datatmspmkdp=array("n_table"=>$hasilTMSPKDP['namatabel'],"q_data"=>$hasilTMSPKDP['jumlah'],
		                  // "i_entry"=>$userid,"d_entry"=>date('Y-m-d'));		 
		 //$db->insert('aset.tm_pushdata',$datatmspmkdp);
		 //$db->commit();
		 }
        if($namatabel=="aset.tm_sejarah"){
		  //$hasilTMSEJARAH=$this->insertTMSEJARAHfromDBF($data,$userid);
		  $hasil=$this->insertTMSEJARAHfromDBF($data,$userid);
        }
        if($namatabel=="aset.tm_sedia"){
		  //$hasilTMSEDIA=$this->insertTMSEDIAfromDBF($data,$userid);
		  $hasil=$this->insertTMSEDIAfromDBF($data,$userid);
        }	
         if($namatabel=="aset.tm_kondisi"){
		  //$hasilTMKONDISI=$this->insertTMKONDISIfromDBF($data,$userid);
		  $hasil=$this->insertTMKONDISIfromDBF($data,$userid);
        }	
        if($namatabel=="aset.tm_kdp"){
		  //$hasilTMKDP=$this->insertTMKDPfromDBF($data,$userid);
		  $hasil=$this->insertTMKDPfromDBF($data,$userid);
        }
        if($namatabel=="aset.tm_skel"){
		  //$hasilTMSKEL=$this->insertTMSKELfromDBF($data,$userid);
		  $hasil=$this->insertTMSKELfromDBF($data,$userid);
        }
        if($namatabel=="aset.tm_sskel"){
		  //$hasilTMSSKEL=$this->insertTMSSKELfromDBF($data,$userid);
		  $hasil=$this->insertTMSSKELfromDBF($data,$userid);
        }	
        if($namatabel=="aset.tm_upb"){
		  //$hasilTMUPB=$this->insertTMUPBfromDBF($data,$userid);
		  $hasil=$this->insertTMUPBfromDBF($data,$userid);
        }
        if($namatabel=="aset.tm_ttd"){
		  //$hasilTMTTD=$this->insertTMTTDfromDBF($data,$userid);
		  $hasil=$this->insertTMTTDfromDBF($data,$userid);
        }
        if($namatabel=="aset.tm_spm"){
		  //$hasilTMSPM=$this->insertTMSPMfromDBF($data,$userid);
		  $hasil=$this->insertTMSPMfromDBF($data,$userid);
        }
        if($namatabel=="aset.tm_ppbi"){
		  //$hasilTMPPBI=$this->insertTMPPBIfromDBF($data,$userid);
		  $hasil=$this->insertTMPPBIfromDBF($data,$userid);
        }
        if($namatabel=="aset.tm_phk3"){
		  //$hasilTMPHK3=$this->insertTMPHK3fromDBF($data,$userid);
		  $hasil=$this->insertTMPHK3fromDBF($data,$userid);
        }
        if($namatabel=="aset.tm_mastert"){
		  //$hasilTMMASTERT=$this->insertTMMASTERTfromDBF($data,$userid);
		  $hasil=$this->insertTMMASTERTfromDBF($data,$userid);
        }
        if($namatabel=="aset.tm_mpindah"){
		  //$hasilTMMPINDAH=$this->insertTMMPINDAHfromDBF($data,$userid);
		  $hasil=$this->insertTMMPINDAHfromDBF($data,$userid);
        }
        if($namatabel=="aset.tm_mnot"){
		  //$hasilTMMNOT=$this->insertTMMNOTfromDBF($data,$userid);
		  $hasil=$this->insertTMMNOTfromDBF($data,$userid);
        }
        if($namatabel=="aset.tm_ksenj"){
		  //$hasilTMKSENJ=$this->insertTMKSENJfromDBF($data,$userid);
		  $hasil=$this->insertTMKSENJfromDBF($data,$userid);
        }
        if($namatabel=="aset.tm_cmp"){
		  //$hasilTMCMP=$this->insertTMCMPfromDBF($data,$userid);
		  $hasil=$this->insertTMCMPfromDBF($data,$userid);
        }
        if($namatabel=="aset.tm_kalb"){
		  //$hasilTMKALB=$this->insertTMKALBfromDBF($data,$userid);
		  $hasil=$this->insertTMKALBfromDBF($data,$userid);
        }
        if($namatabel=="aset.tm_kbair"){
		  //$hasilTMKBAIR=$this->insertTMKBAIRfromDBF($data,$userid);
		  $hasil=$this->insertTMKBAIRfromDBF($data,$userid);
        }
        if($namatabel=="aset.tm_ruang"){
		  //$hasilTMRUANG=$this->insertTMRUANGfromDBF($data,$userid);
		  $hasil=$this->insertTMRUANGfromDBF($data,$userid);
        }
        if($namatabel=="aset.tm_kdp"){
		  //$hasilTMKDP=$this->insertTMKDPfromDBF($data,$userid);
		  $hasil=$this->insertTMKDPfromDBF($data,$userid);
        }		
		
	  //}
	  //}
	  $registry = Zend_Registry::getInstance();
	  $db = $registry->get('db');	   
	    try {	    
		 //if($perintah=='PROSES PUSH DATA'){
		 // if(sizeof($hasilTMGOL)>0||sizeof($hasilTMDIL)>0||sizeof($hasilTMMASTER)>0||sizeof($hasilTMDIR)>0||sizeof($hasilTMPBI)>0
		    // ||sizeof($hasilTMPPBI)>0||sizeof($hasilTMKBDG)>0||sizeof($hasilTMBID)>0||sizeof($hasilTMKTNH)>0||sizeof($hasilTMKEL)>0
			// ||sizeof($hasilTMPOSTAS)>0||sizeof($hasilTMCROLEH)>0||sizeof($hasilTMTTD)>0||sizeof($hasilTMSSKEL)>0||sizeof($hasilTMKANGK)>0
			// ||sizeof($hasilTMPEBIN)>0||sizeof($hasilTMSKEL)>0||sizeof($hasilTMKSENJ)>0||sizeof($hasilTMWILAYAH)>0||sizeof($hasilTMLOGIN)>0
			// ||sizeof($hasilTMUPB)>0||sizeof($hasilTMSPM)>0||sizeof($hasilTMPERK)>0||sizeof($hasilTMRUANG)>0
			// ){			
		 // if(sizeof($hasilTMDIL)>0||sizeof($hasilTMDIR)>0||sizeof($hasilTMKANGK)>0||sizeof($hasilTMKBDG)>0
		    // ||sizeof($hasilTMKTNH)>0||sizeof($hasilTMMASTERHM)>0||sizeof($hasilTMMASTERU)>0||sizeof($hasilTMRUANG)>0
			// ){	
		 // $this->deleteTableLogPushData('aset.tm_pushdata');
		 // $db->beginTransaction();
         // $datatmgol=array("n_table"=>$hasilTMGOL['namatabel'],"q_data"=>$hasilTMGOL['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmgol);		  
		 	
		 // $datatmdil=array("n_table"=>$hasilTMDIL['namatabel'],"q_data"=>$hasilTMDIL['jumlah'],
		                  // "i_entry"=>$userid,"d_entry"=>date('Y-m-d'));
		 
		 // $db->insert('aset.tm_pushdata',$datatmdil);
		 // $datatmdir=array("n_table"=>$hasilTMDIR['namatabel'],"q_data"=>$hasilTMDIR['jumlah'],
		                  // "i_entry"=>$userid,"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmdir);
		 // $datatmkangk=array("n_table"=>$hasilTMKANGK['namatabel'],"q_data"=>$hasilTMKANGK['jumlah'],
		                  // "i_entry"=>$userid,"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmkangk);
         // $datatmkbdg=array("n_table"=>$hasilTMKBDG['namatabel'],"q_data"=>$hasilTMKBDG['jumlah'],
		                  // "i_entry"=>$userid,"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmkbdg);
         // $datatmktnh=array("n_table"=>$hasilTMKTNH['namatabel'],"q_data"=>$hasilTMKTNH['jumlah'],
		                  // "i_entry"=>$userid,"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmktnh);		 
         // $datatmmasterhm=array("n_table"=>$hasilTMMASTER['namatabel'],"q_data"=>$hasilTMMASTER['jumlah'],
		                  // "i_entry"=>$userid,"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmmasterhm);
         // $datatmmasteru=array("n_table"=>$hasilTMMASTERU['namatabel'],"q_data"=>$hasilTMMASTERU['jumlah'],
		                  // "i_entry"=>$userid,"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmmasteru);
         // $datatmruang=array("n_table"=>$hasilTMRUANG['namatabel'],"q_data"=>$hasilTMRUANG['jumlah'],
		                  // "i_entry"=>$userid,"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmruang);		 
		 // $datatmdir=array("n_table"=>$hasilTMDIR['namatabel'],"q_data"=>$hasilTMDIR['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmdir);
		 
		 // $datatmpbi=array("n_table"=>$hasilTMPBI['namatabel'],"q_data"=>$hasilTMPBI['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmpbi);
		 
		 // $datatmppbi=array("n_table"=>$hasilTMPPBI['namatabel'],"q_data"=>$hasilTMPPBI['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmppbi);
		 
		 // $datatmkbdg=array("n_table"=>$hasilTMKBDG['namatabel'],"q_data"=>$hasilTMKBDG['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmkbdg);
		 
		 // $datatmbid=array("n_table"=>$hasilTMBID['namatabel'],"q_data"=>$hasilTMBID['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmbid);
		 
		 // $datatmktnh=array("n_table"=>$hasilTMKTNH['namatabel'],"q_data"=>$hasilTMKTNH['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmktnh);
		 
		 // $datatmkel=array("n_table"=>$hasilTMKEL['namatabel'],"q_data"=>$hasilTMKEL['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmkel);
		 
		 // $datatmpostas=array("n_table"=>$hasilTMPOSTAS['namatabel'],"q_data"=>$hasilTMPOSTAS['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmpostas);
		 
		 // $datatmcroleh=array("n_table"=>$hasilTMCROLEH['namatabel'],"q_data"=>$hasilTMCROLEH['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmcroleh);
		 
		 // $datatmttd=array("n_table"=>$hasilTMTTD['namatabel'],"q_data"=>$hasilTMTTD['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmttd);
		 
		 // $datatmsskel=array("n_table"=>$hasilTMSSKEL['namatabel'],"q_data"=>$hasilTMSSKEL['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmsskel);
		 
		 // $datatmkangk=array("n_table"=>$hasilTMKANGK['namatabel'],"q_data"=>$hasilTMKANGK['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmkangk);
		 
		 // $datatmpebin=array("n_table"=>$hasilTMPEBIN['namatabel'],"q_data"=>$hasilTMPEBIN['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmpebin);
		 
		 // $datatmskel=array("n_table"=>$hasilTMSKEL['namatabel'],"q_data"=>$hasilTMSKEL['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmskel);
		 
		 // $datatmksenj=array("n_table"=>$hasilTMKSENJ['namatabel'],"q_data"=>$hasilTMKSENJ['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmksenj);
		 
		 // $datatmwilayah=array("n_table"=>$hasilTMWILAYAH['namatabel'],"q_data"=>$hasilTMWILAYAH['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmwilayah);
		 
		 // $datatmlogin=array("n_table"=>$hasilTMLOGIN['namatabel'],"q_data"=>$hasilTMLOGIN['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmlogin);
		 
		 // $datatmupb=array("n_table"=>$hasilTMUPB['namatabel'],"q_data"=>$hasilTMUPB['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmupb);
		 
		 // $datatmspm=array("n_table"=>$hasilTMSPM['namatabel'],"q_data"=>$hasilTMSPM['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmspm);
		 
		 // $datatmperk=array("n_table"=>$hasilTMPERK['namatabel'],"q_data"=>$hasilTMPERK['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmperk);
		 
		 // $datatmruang=array("n_table"=>$hasilTMRUANG['namatabel'],"q_data"=>$hasilTMRUANG['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmruang);
		 // $datatmmasteru=array("n_table"=>$hasilTMMASTERU['namatabel'],"q_data"=>$hasilTMMASTERU['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmmasteru);
		 //$db->commit();	
		 // }
		 // else{
	     // return 'gagal<br/>';
	     // }
		 
		 // }
		  // if(($pageNumber==0) && ($itemPerPage==0))
		 // {
	         // $result = $db->fetchOne("select count(*)  
											// from aset.tm_pushdata where i_entry='$userid'											
											// ","");
		 // }
		 // else{
		     // $xLimit=$itemPerPage;
			 // $xOffset=($pageNumber-1)*$itemPerPage;	
			 // $sql = "select n_table,q_data  
					// from aset.tm_pushdata
					// where i_entry='$userid'";
			 //echo $sql;
			 //$result = $db->fetchAll($sql);
          //}											
	     //return "sukses";
	     return $hasil;
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
		 
	   }
	}
	
	public function insertPostgre($perintah,$pageNumber,$itemPerPage,$userid){
	if($perintah=='PROSES PUSH DATA'){
	 // $hasilTMGOL=$this->insertTMGOL($userid);
	 // $hasilTMDIL=$this->insertTMDIL($userid);
	 // $hasilTMMASTER=$this->insertTMMASTERHM($userid);
	  // $hasilTMDIR=$this->insertTMDIR($userid);
	 // $hasilTMPBI=$this->insertTMPBI($userid);
	 // $hasilTMPPBI=$this->insertTMPPBI($userid);
	 // $hasilTMKBDG=$this->insertTMKBDG($userid);
	 // $hasilTMBID=$this->insertTMBID($userid);
	  // $hasilTMKTNH=$this->insertTMKTNH($userid);
	 // $hasilTMKEL=$this->insertTMKEL($userid);
	 // $hasilTMPOSTAS=$this->insertTMPOSTAS($userid);
	 // $hasilTMCROLEH=$this->insertTMCROLEH($userid);
	 // $hasilTMTTD=$this->insertTMTTD($userid);
	 // $hasilTMSSKEL=$this->insertTMSSKEL($userid);
	 // $hasilTMKANGK=$this->insertTMKANGK($userid);
	 // $hasilTMPEBIN=$this->insertTMPEBIN($userid);
	 // $hasilTMSKEL=$this->insertTMSKEL($userid);
	 // $hasilTMKSENJ=$this->insertTMKSENJ($userid);
	 // $hasilTMWILAYAH=$this->insertTMWILAYAH($userid);
	 // $hasilTMLOGIN=$this->insertTMLOGIN($userid);
	 // $hasilTMUPB=$this->insertTMUPB($userid);
	 // $hasilTMSPM=$this->insertTMSPM($userid);
	 // $hasilTMPERK=$this->insertTMPERK($userid);
	  $hasilTMRUANG=$this->insertTMRUANG($userid);
	  //$hasilTMMASTERU=$this->insertTMMASTERU($userid);
	 }
	  $registry = Zend_Registry::getInstance();
	  $db = $registry->get('db');	   
	    try {	    
		 if($perintah=='PROSES PUSH DATA'){
		 // if(sizeof($hasilTMGOL)>0||sizeof($hasilTMDIL)>0||sizeof($hasilTMMASTER)>0||sizeof($hasilTMDIR)>0||sizeof($hasilTMPBI)>0
		    // ||sizeof($hasilTMPPBI)>0||sizeof($hasilTMKBDG)>0||sizeof($hasilTMBID)>0||sizeof($hasilTMKTNH)>0||sizeof($hasilTMKEL)>0
			// ||sizeof($hasilTMPOSTAS)>0||sizeof($hasilTMCROLEH)>0||sizeof($hasilTMTTD)>0||sizeof($hasilTMSSKEL)>0||sizeof($hasilTMKANGK)>0
			// ||sizeof($hasilTMPEBIN)>0||sizeof($hasilTMSKEL)>0||sizeof($hasilTMKSENJ)>0||sizeof($hasilTMWILAYAH)>0||sizeof($hasilTMLOGIN)>0
			// ||sizeof($hasilTMUPB)>0||sizeof($hasilTMSPM)>0||sizeof($hasilTMPERK)>0||sizeof($hasilTMRUANG)>0
			// ){
		 if(sizeof($hasilTMRUANG)>0
			){	
		 //$this->deleteTableLogPushData('aset.tm_pushdata');
		 $db->beginTransaction();
         // $datatmgol=array("n_table"=>$hasilTMGOL['namatabel'],"q_data"=>$hasilTMGOL['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmgol);		  
		 	
		 $datatmdil=array("n_table"=>$hasilTMDIL['namatabel'],"q_data"=>$hasilTMDIL['jumlah'],
		                  "i_entry"=>$userid,"d_entry"=>date('Y-m-d'));
		 $db->insert('aset.tm_pushdata',$datatmdil);
         
         // $datatmmasterhm=array("n_table"=>$hasilTMMASTER['namatabel'],"q_data"=>$hasilTMMASTER['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmmasterhm);
		 
		 // $datatmdir=array("n_table"=>$hasilTMDIR['namatabel'],"q_data"=>$hasilTMDIR['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmdir);
		 
		 // $datatmpbi=array("n_table"=>$hasilTMPBI['namatabel'],"q_data"=>$hasilTMPBI['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmpbi);
		 
		 // $datatmppbi=array("n_table"=>$hasilTMPPBI['namatabel'],"q_data"=>$hasilTMPPBI['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmppbi);
		 
		 // $datatmkbdg=array("n_table"=>$hasilTMKBDG['namatabel'],"q_data"=>$hasilTMKBDG['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmkbdg);
		 
		 // $datatmbid=array("n_table"=>$hasilTMBID['namatabel'],"q_data"=>$hasilTMBID['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmbid);
		 
		 // $datatmktnh=array("n_table"=>$hasilTMKTNH['namatabel'],"q_data"=>$hasilTMKTNH['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmktnh);
		 
		 // $datatmkel=array("n_table"=>$hasilTMKEL['namatabel'],"q_data"=>$hasilTMKEL['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmkel);
		 
		 // $datatmpostas=array("n_table"=>$hasilTMPOSTAS['namatabel'],"q_data"=>$hasilTMPOSTAS['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmpostas);
		 
		 // $datatmcroleh=array("n_table"=>$hasilTMCROLEH['namatabel'],"q_data"=>$hasilTMCROLEH['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmcroleh);
		 
		 // $datatmttd=array("n_table"=>$hasilTMTTD['namatabel'],"q_data"=>$hasilTMTTD['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmttd);
		 
		 // $datatmsskel=array("n_table"=>$hasilTMSSKEL['namatabel'],"q_data"=>$hasilTMSSKEL['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmsskel);
		 
		 // $datatmkangk=array("n_table"=>$hasilTMKANGK['namatabel'],"q_data"=>$hasilTMKANGK['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmkangk);
		 
		 // $datatmpebin=array("n_table"=>$hasilTMPEBIN['namatabel'],"q_data"=>$hasilTMPEBIN['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmpebin);
		 
		 // $datatmskel=array("n_table"=>$hasilTMSKEL['namatabel'],"q_data"=>$hasilTMSKEL['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmskel);
		 
		 // $datatmksenj=array("n_table"=>$hasilTMKSENJ['namatabel'],"q_data"=>$hasilTMKSENJ['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmksenj);
		 
		 // $datatmwilayah=array("n_table"=>$hasilTMWILAYAH['namatabel'],"q_data"=>$hasilTMWILAYAH['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmwilayah);
		 
		 // $datatmlogin=array("n_table"=>$hasilTMLOGIN['namatabel'],"q_data"=>$hasilTMLOGIN['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmlogin);
		 
		 // $datatmupb=array("n_table"=>$hasilTMUPB['namatabel'],"q_data"=>$hasilTMUPB['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmupb);
		 
		 // $datatmspm=array("n_table"=>$hasilTMSPM['namatabel'],"q_data"=>$hasilTMSPM['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmspm);
		 
		 // $datatmperk=array("n_table"=>$hasilTMPERK['namatabel'],"q_data"=>$hasilTMPERK['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmperk);
		 
		 $datatmruang=array("n_table"=>$hasilTMRUANG['namatabel'],"q_data"=>$hasilTMRUANG['jumlah'],
		                  "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 $db->insert('aset.tm_pushdata',$datatmruang);
		 // $datatmmasteru=array("n_table"=>$hasilTMMASTERU['namatabel'],"q_data"=>$hasilTMMASTERU['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmmasteru);
		 $db->commit();	
		 }
		 else{
	     return 'gagal<br/>';
	     }
		 
		 }
		  if(($pageNumber==0) && ($itemPerPage==0))
		 {
	         $result = $db->fetchOne("select count(*)  
											from aset.tm_pushdata											
											","");
		 }
		 else{
		     $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
			 $result = $db->fetchAll("select n_table,q_data  
												from aset.tm_pushdata
												limit $xLimit offset $xOffset
												","");
          }											
	     return $result;
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
		 
	   }
	}
	public function cekfiledidb($nmfile){
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	        $sql = "SELECT id
				    FROM aset.tm_file_upload
					where n_file = '$nmfile' and c_status='1'";
			$result = $db->fetchOne($sql);  
		
		 return $result;
	    } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'Data Tidak Ada <br>';
		 
	   }
    }	
	public function updatestatus(array $data){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->beginTransaction();
		 $dataFile = array("c_status" =>$data['c_status']);
	     $where[]="id='".$data['id']."'";	     
	     $db->update('aset.tm_file_upload',$dataFile,$where);
		 $db->commit();
		 $db->closeConnection();
	     return 'sukses';
	 }catch(Excepotion $ex){
	     $db->rollBack();
         echo $e->getMessage().'<br>';
		 $db->closeConnection();
	     return 'gagal';
	 }
	}
	public function getTMPUSHPaging($pageNumber,$itemPerPage){
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	      if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$result = $db->fetchOne("SELECT count(*)
							   	 FROM aset.tm_pushdata ","");  
		 }
		 else
		 {			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
			 $result = $db->fetchAll("select n_table,q_data  
										from aset.tm_pushdata
										limit $xLimit offset $xOffset
										","");
			 
		 }
		 return $result;
	    } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'Data Tidak Ada <br>';
		 
	   }
    }	
	public function tesInsert(array $data){
	  try{
	   for($i=0;$i<count($data);$i++){
	     echo "Data : ".$data[$i]['thn_ang']."\n";
	   }
	  }catch(Exception $ex){
	   echo $ex->getMessage();
	  }
	  
	}
	function htmlallentities($str){
  $res = '';
  $strlen = strlen($str);
  for($i=0; $i<$strlen; $i++){
    $byte = ord($str[$i]);
    if($byte < 128) // 1-byte char
      $res .= $str[$i];
    elseif($byte < 192); // invalid utf8
    elseif($byte < 224) // 2-byte char
      $res .= '&#'.((63&$byte)*64 + (63&ord($str[++$i]))).';';
    elseif($byte < 240) // 3-byte char
      $res .= '&#'.((15&$byte)*4096 + (63&ord($str[++$i]))*64 + (63&ord($str[++$i]))).';';
    elseif($byte < 248) // 4-byte char
      $res .= '&#'.((15&$byte)*262144 + (63&ord($str[++$i]))*4096 + (63&ord($str[++$i]))*64 + (63&ord($str[++$i]))).';';
  }
  return $res;
}
  public function insertformcsv($file,$namatabel){
     $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  //$db->setFetchMode(Zend_Db::FETCH_OBJ); 
	$nmfile = "D:/Project/MA/ma_project/public/data/aset/ekstrak/CSV/tm_masterhm121711";
	$sql = "copy aset.tm_masterhm('thn_ang','periode','kd_lokasi','no_sppa','kd_brg','no_aset','tgl_perlh','tercatat', ".
	       "'kondisi','tgl_buku','jns_trn','dsr_hrg','kd_data','flag_sap','kuantitas','rph_sat','rph_aset','flag_kor', ".
		   "'keterangan','merk_type','asal_perlh','no_bukti','no_dsr_mts','tgl_dsr_mts','flag_ttp','flag_krm','kdblu','setatus', ".
		   "'noreg','kdbapel','kdkpknl','umeko','rph_res','kdkppn','no_asetlm','kd_brglm','i_entry','d_entry') from '".$nmfile."' ".
		   "WITH DELIMITER AS ','";
	  //echo $sql."\n";
	  $result = $db->query($sql);	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	 }
  }
 
  public function insertMultiRows($x){
   $registry = Zend_Registry::getInstance();
   $db = $registry->get('db');  
   //echo $x;
   try{
     $db->beginTransaction();	
     $db->query(trim($x));
	 $db->commit();
	 return 'sukses';
   }catch(Exception $ex){
    $db->rollBack();
    //echo $ex->getMessage();
	$this->tuliskelog($ex->getMessage()."\n");
	return 'gagal';
   }
   
  }
}	
?>