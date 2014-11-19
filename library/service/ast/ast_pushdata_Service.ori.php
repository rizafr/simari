<?php
include ("connect.php");
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
	public function deleteTableLogPushData($namaTable){
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
		 while($row=mysql_fetch_array($result)){
		   
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
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	   
	   try {
	    
		 $query = "select * from t_ruang";	 
		 $result=mysql_query($query);
		 $numrows = mysql_num_rows($result);
		 $idx=0;
		if($numrows>0){
		   $this->deleteTable('aset.tm_ruang');
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
			
		   
		   $db->insert('aset.tm_ruang',$hasil[$idx]);
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
		  //$name = htmlspecialchars("Istisna’ (Manufacturing Con   ",ENT_QUOTES);
		  //$var="Istisna’ (Manufacturing Con   ";
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
		   //$name = $db->quoteInto($row[keterangan],"’");
		   //$name = $db->quote($row[keterangan],"’");
		  //$name = str_replace('\â€™', '', $row[keterangan]);
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
		 $this->deleteTableLogPushData('aset.tm_pushdata');
		 $db->beginTransaction();
         // $datatmgol=array("n_table"=>$hasilTMGOL['namatabel'],"q_data"=>$hasilTMGOL['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmgol);		  
		 	
		 // $datatmdil=array("n_table"=>$hasilTMDIL['namatabel'],"q_data"=>$hasilTMDIL['jumlah'],
		                  // "i_entry"=>'TESTING',"d_entry"=>date('Y-m-d'));
		 // $db->insert('aset.tm_pushdata',$datatmdil);
         
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

}	
?>