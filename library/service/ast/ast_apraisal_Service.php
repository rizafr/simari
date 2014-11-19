<?php
class ast_apraisal_Service {
   
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
   
    public function apraisallist($pageNumber,$itemPerPage,$jenisApraisal){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ);
	    if(($pageNumber==0) && ($itemPerPage==0))
		{
		  $sql="select a.d_appraisal,a.i_jenis_appraisal,a.i_oleh_appraisal,
		       a.kd_brg,b.ur_sskel,a.v_thn_berjalan,a.v_hasil_investasi,a.v_hasil_pemeliharaan,
			   a.v_hasil_penghapusan,c.n_peg
			   from aset.tm_appraisal a,aset.tm_sskel b,sdm.tm_pegawai c
			   where 
			   substr(a.kd_brg, 1, 1) = b.kd_gol
			   AND substr(a.kd_brg, 2, 2) = b.kd_bid 
			   AND substr(a.kd_brg, 4, 2) = b.kd_kel 
			   AND substr(a.kd_brg, 6, 2) = b.kd_skel
			   AND substr(a.kd_brg, 8, 3) = b.kd_sskel
			   AND a.i_oleh_appraisal = c.i_peg_nip";
			if($jenisApraisal!=""){
			   $sql=$sql." and a.i_jenis_appraisal='".$jenisApraisal."'";
			}
			$sql = $sql." order by a.d_appraisal asc"; 
			  // echo $sql;
	     $result = $db->fetchAll($sql);
	     $hasilAkhir = count($result);
		}
		else{
	        $xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;
		    $sql="select a.id,a.d_appraisal,a.i_jenis_appraisal,a.i_oleh_appraisal,
		       a.kd_brg,b.ur_sskel,a.v_thn_berjalan,a.v_hasil_investasi,a.v_hasil_pemeliharaan,
			   a.v_hasil_penghapusan,c.n_peg
			   from aset.tm_appraisal a,aset.tm_sskel b,sdm.tm_pegawai c 
			   where 
			   substr(a.kd_brg, 1, 1) = b.kd_gol
			   AND substr(a.kd_brg, 2, 2) = b.kd_bid 
			   AND substr(a.kd_brg, 4, 2) = b.kd_kel 
			   AND substr(a.kd_brg, 6, 2) = b.kd_skel
			   AND substr(a.kd_brg, 8, 3) = b.kd_sskel
			   AND a.i_oleh_appraisal = c.i_peg_nip";
			if($jenisApraisal!=""){
			   $sql=$sql." and a.i_jenis_appraisal='".$jenisApraisal."'";
			}
			$sql = $sql." order by a.d_appraisal asc"; 
			$sql=$sql." limit $xLimit offset $xOffset";	
			  // echo $sql;
	        $result = $db->fetchAll($sql);
		    $jmlResult = count($result);
				 for ($j = 0; $j < $jmlResult; $j++) {
				   $ja = $result[$j]->i_jenis_appraisal;
				   if($ja=="BA"){$jenapraisal = "Beli Awal";}
				   if($ja=="TB"){$jenapraisal = "Tahun Berjalan";}
				   if($ja=="PB"){$jenapraisal = "Perbaikan";}
				   if($ja=="PH"){$jenapraisal = "Penghapusan";}
		           $hasilAkhir[$j] = array("id"           =>(string)$result[$j]->id,
				                           "d_apraisal"           =>(string)$result[$j]->d_appraisal,
				                           "i_jenis_appraisal"           =>$jenapraisal,
				                           "i_oleh_appraisal"           =>(string)$result[$j]->i_oleh_appraisal,
				                           "n_peg"           =>(string)$result[$j]->n_peg,
				                           "kd_brg"           =>(string)$result[$j]->kd_brg,
				                           "ur_sskel"           =>(string)$result[$j]->ur_sskel,
				                           "v_thn_berjalan"           =>(string)$result[$j]->v_thn_berjalan,
				                           "v_hasil_investasi"           =>(string)$result[$j]->v_hasil_investasi,
				                           "v_hasil_pemeliharaan"           =>(string)$result[$j]->v_hasil_pemeliharaan,
				                           "v_hasil_penghapusan"           =>(string)$result[$j]->v_hasil_penghapusan
										   );
				 }
			}
      $db->closeConnection();	  
	  return $hasilAkhir;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	  $db->closeConnection();
	  return "Data tidak ada";
	 }
	}
    
    public function getDataPegawai($pageNumber,$itemPerPage,$nmPegawai,$satker) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     if($nmPegawai==""){
		    $nmpegawai="'%%'";
		 }
		 else{
		     $nmpegawai="'%".strtoupper($nmpegawai)."%'";
		 }
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		   if(($pageNumber==0) && ($itemPerPage==0))
		   {
				 $sql= "select distinct a.i_peg_nip,a.n_peg,c.n_jabatan
						from sdm.tm_pegawai a,sdm.tr_unitkerja b,sdm.tr_jabatan c
						where a.c_lokasi_unitkerja = b.c_lokasi_unitkerja
						and a.c_eselon_i = b.c_eselon_i
						and a.c_eselon_ii = b.c_eselon_ii
						and a.c_eselon_iii = b.c_eselon_iii
						and a.c_eselon_iv = b.c_eselon_iv
						and a.c_eselon_v = b.c_eselon_v
						and b.c_satker='".$satker."'
						and a.n_peg like ".$nmpegawai."
						and a.c_jabatan = c.c_jabatan";
	            $result = $db->fetchAll($sql);
		        $hasilAkhir=count($result);		
                //echo $sql;			
			}			 
			else
			{
			  $xLimit=$itemPerPage;
			  $xOffset=($pageNumber-1)*$itemPerPage;	
			  $sql= "select distinct a.i_peg_nip,a.n_peg,c.n_jabatan
						from sdm.tm_pegawai a,sdm.tr_unitkerja b,sdm.tr_jabatan c
						where a.c_lokasi_unitkerja = b.c_lokasi_unitkerja
						and a.c_eselon_i = b.c_eselon_i
						and a.c_eselon_ii = b.c_eselon_ii
						and a.c_eselon_iii = b.c_eselon_iii
						and a.c_eselon_iv = b.c_eselon_iv
						and a.c_eselon_v = b.c_eselon_v
						and b.c_satker='".$satker."'
						and a.n_peg like ".$nmpegawai."
						and a.c_jabatan = c.c_jabatan
						limit $xLimit offset $xOffset";
				
	            $result = $db->fetchAll($sql);			  
		        $jmlResult = count($result);
		 
				 for ($j = 0; $j < $jmlResult; $j++) {
				 
		           $hasilAkhir[$j] = array("nip"           =>(string)$result[$j]->i_peg_nip,
				                           "n_peg"           =>(string)$result[$j]->n_peg,
				                           "jabatan"           =>(string)$result[$j]->n_jabatan);
				 }	
            }				 
         $db->closeConnection();		
	     return $hasilAkhir;        	
	     
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
		 $db->closeConnection();
	     return 'gagal <br>';
	   }
	}
	public function getSql($kodebarang){
	  if($kodebarang=="TNH"){
		 $sql= "select a.kd_lokasi,a.kd_brg,b.ur_sskel,a.no_aset
			   from aset.tm_ktnh a,aset.tm_sskel b
				where 
				substr(a.kd_brg, 1, 1) = b.kd_gol
				AND substr(a.kd_brg, 2, 2) = b.kd_bid 
				AND substr(a.kd_brg, 4, 2) = b.kd_kel 
				AND substr(a.kd_brg, 6, 2) = b.kd_skel
				AND substr(a.kd_brg, 8, 3) = b.kd_sskel";
		}
	  if($kodebarang=="BGD"){
		 $sql= "select a.kd_lokasi,a.kd_brg,b.ur_sskel,a.no_aset
			   from aset.tm_kbdg a,aset.tm_sskel b
				where 
				substr(a.kd_brg, 1, 1) = b.kd_gol
				AND substr(a.kd_brg, 2, 2) = b.kd_bid 
				AND substr(a.kd_brg, 4, 2) = b.kd_kel 
				AND substr(a.kd_brg, 6, 2) = b.kd_skel
				AND substr(a.kd_brg, 8, 3) = b.kd_sskel";
		}
	  if($kodebarang=="ANG"){
		 $sql= "select a.kd_lokasi,a.kd_brg,b.ur_sskel,a.no_aset
			   from aset.tm_kangk a,aset.tm_sskel b
				where 
				substr(a.kd_brg, 1, 1) = b.kd_gol
				AND substr(a.kd_brg, 2, 2) = b.kd_bid 
				AND substr(a.kd_brg, 4, 2) = b.kd_kel 
				AND substr(a.kd_brg, 6, 2) = b.kd_skel
				AND substr(a.kd_brg, 8, 3) = b.kd_sskel";
		}
		return $sql;
	}
	public function getDataBarang($pageNumber,$itemPerPage,$nmbarang,$kodebarang) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     if($nmbarang==""){
		    $nmbarang="'%%'";
		 }
		 else{
		     $nmbarang="'%".strtoupper($nmbarang)."%'";
		 }
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		   if(($pageNumber==0) && ($itemPerPage==0))
		   {
		       $sql=$this->getSql($kodebarang);
			   $sql=$sql." and b.ur_sskel like ".$nmbarang;
			   //echo $sql;
	            $result = $db->fetchAll($sql);
		        $hasilAkhir=count($result);		
                			
			}			 
			else
			{
			  $xLimit=$itemPerPage;
			  $xOffset=($pageNumber-1)*$itemPerPage;	
			  $sql=$this->getSql($kodebarang);
			  $sql=$sql." and b.ur_sskel like ".$nmbarang;
			  $sql=$sql."limit $xLimit offset $xOffset";
				
	            $result = $db->fetchAll($sql);			  
		        $jmlResult = count($result);
		 
				 for ($j = 0; $j < $jmlResult; $j++) {
				 
		           $hasilAkhir[$j] = array("kd_lokasi"           =>(string)$result[$j]->kd_lokasi,
				                           "kd_brg"           =>(string)$result[$j]->kd_brg,
				                           "ur_sskel"           =>(string)$result[$j]->ur_sskel,
				                           "no_aset"           =>(string)$result[$j]->no_aset);
				 }	
            }				 
         $db->closeConnection();		
	     return $hasilAkhir;        	
	     
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
		 $db->closeConnection();
	     return 'gagal <br>';
	   }
	}
	public function insertApraisal($data){
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
		 $data = array("d_appraisal"=>$data['d_appraisal'],
	                "i_jenis_appraisal"=>$data['i_jenis_appraisal'],
					"i_oleh_appraisal"=>$data['i_oleh_appraisal'],
					"kd_brg"=>$data['kd_brg'],
					"v_thn_berjalan"=>$data['v_thn_berjalan'],
					"v_hasil_investasi"=>$data['v_hasil_investasi'],
					"v_hasil_pemeliharaan"=>$data['v_hasil_pemeliharaan'],
					"v_hasil_penghapusan"=>$data['v_hasil_penghapusan'],
					"c_jenis_barang"=>$data['c_jenis_barang'],
					"i_entry"=>$data['i_entry'],
					"d_entry"        =>date("Y-m-d"));		 
	    
	     $db->insert('aset.tm_appraisal',$data);
		 $db->commit();
		 $db->closeConnection();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
		 $db->closeConnection();
	     return 'gagal <br>';
	   }
	}
	public function updateApraisal($data){
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
		 $dataapraisal = array("d_appraisal"=>$data['d_appraisal'],
	                "i_jenis_appraisal"=>$data['i_jenis_appraisal'],
					"i_oleh_appraisal"=>$data['i_oleh_appraisal'],
					"kd_brg"=>$data['kd_brg'],
					"v_thn_berjalan"=>$data['v_thn_berjalan'],
					"v_hasil_investasi"=>$data['v_hasil_investasi'],
					"v_hasil_pemeliharaan"=>$data['v_hasil_pemeliharaan'],
					"v_hasil_penghapusan"=>$data['v_hasil_penghapusan'],
					"c_jenis_barang"=>$data['c_jenis_barang'],
					"i_entry"=>$data['i_entry'],
					"d_entry"        =>date("Y-m-d"));	
         					
	     $where[] = "id = '".$data['id']."'";
	     $db->update('aset.tm_appraisal',$dataapraisal,$where);
		 $db->commit();
		 $db->closeConnection();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
		 $db->closeConnection();
	     return 'gagal <br>';
	   }
	}
	public function getDataApraisal($id){
	 $registry= Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	   $db->setFetchMode(Zend_Db::FETCH_OBJ);
	    $sql="select a.d_appraisal,a.i_jenis_appraisal,a.i_oleh_appraisal,
		       a.kd_brg,b.ur_sskel,a.v_thn_berjalan,a.v_hasil_investasi,a.v_hasil_pemeliharaan,
			   a.v_hasil_penghapusan,c.n_peg,a.c_jenis_barang,d.n_jabatan
			   from aset.tm_appraisal a,aset.tm_sskel b,sdm.tm_pegawai c,sdm.tr_jabatan d
			   where 
			   substr(a.kd_brg, 1, 1) = b.kd_gol
			   AND substr(a.kd_brg, 2, 2) = b.kd_bid 
			   AND substr(a.kd_brg, 4, 2) = b.kd_kel 
			   AND substr(a.kd_brg, 6, 2) = b.kd_skel
			   AND substr(a.kd_brg, 8, 3) = b.kd_sskel
			   AND a.i_oleh_appraisal = c.i_peg_nip
			   AND c.c_jabatan = d.c_jabatan
			   AND a.id='$id'";
		
		$result=$db->fetchAll($sql);				
		$db->closeConnection();	  
	    return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	  $db->closeConnection();
	  return 'Data Tidak Ada';
	 }
	}
	public function hapusApraisal($id){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->beginTransaction();
	  $key[] = "id = '".$id."'";	 
	  $db->delete("aset.tm_appraisal",$key);
	  $db->commit();
	  $db->closeConnection();
	  return 'sukses';
	 }catch(Exception $ex){
	   echo $e->getMessage().'<br>';
	   $db->closeConnection();
	   return 'gagal <br>';
	 }
	}
}	
?>