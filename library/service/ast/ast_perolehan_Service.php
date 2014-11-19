<?php
class ast_perolehan_Service {
   
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
	public function hapusRencanaPengadaan($id){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->beginTransaction();
	  $key[] = "id = '".$id."'";	 
	  $db->delete("aset.tm_pengadaan",$key);
	  $db->commit();
	  $db->closeConnection();
	  return 'sukses';
	 }catch(Exception $ex){
	   echo $e->getMessage().'<br>';
	   $db->closeConnection();
	   return 'gagal <br>';
	 }
	}
    public function updateRencanaPengadaan(array $data){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->beginTransaction();
		  $dataPengajuan = array("c_jenis_aset"         		=>$data['jenisAset'],
	                           "i_nama_aset"    	        	=>$data['namaaset'],
						       "i_keterangan"                =>$data['keterangan'],
							   "i_nilai"               =>$data['nilai'],
							   "d_pengajuan"  	            =>$data['tglpengajuan'],
							   "d_est_perolehan"                 =>$data['tglestimasi'],
							   "c_sdh_realisasi"                =>$data['status'],						       						  
						       "i_entry"       		            =>$data['i_entry'],
						       "d_entry"       		        =>date("Y-m-d"));
	    
	     $where[]="id='".$data['id']."'";	    
	     $db->update('aset.tm_pengadaan',$dataPengajuan,$where);
		 $db->commit();
		 $db->closeConnection();
	     return 'sukses';
	 }catch(Excepotion $ex){
	     $db->rollBack();
         echo $e->getMessage().'<br>';
		 $db->closeConnection();
	     return 'gagal <br>';
	 }
	}
	public function updateRealisasiPengadaan(array $data){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->beginTransaction();
		  $dataPengajuan = array("c_sdh_realisasi"                =>$data['status'],						       						  
						       "i_entry"       		            =>$data['i_entry'],
						       "d_entry"       		        =>date("Y-m-d"));
	    
	     $where[]="id='".$data['id']."'";	    
	     $db->update('aset.tm_pengadaan',$dataPengajuan,$where);
		 $db->commit();
		 $db->closeConnection();
	     return 'sukses';
	 }catch(Excepotion $ex){
	     $db->rollBack();
         echo $e->getMessage().'<br>';
		 $db->closeConnection();
	     return 'gagal <br>';
	 }
	}
    public function insertRencanaPengadaan(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
		 $dataPengajuan = array("c_jenis_aset"         		=>$data['jenisAset'],
	                           "i_nama_aset"    	        	=>$data['namaaset'],
						       "i_keterangan"                =>$data['keterangan'],
							   "i_nilai"               =>$data['nilai'],
							   "d_pengajuan"  	            =>$data['tglpengajuan'],
							   "d_est_perolehan"                 =>$data['tglestimasi'],
							   "c_sdh_realisasi"                =>$data['status'],						       						  
						       "i_entry"       		            =>$data['i_entry'],
						       "d_entry"       		        =>date("Y-m-d"));
	    
	     $db->insert('aset.tm_pengadaan',$dataPengajuan);
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
	public function getDataRencanaPengadaanUbah($id){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ);
	  $sql= "select id,c_jenis_aset,i_nama_aset,i_keterangan,i_nilai,d_pengajuan,
			d_est_perolehan,c_sdh_realisasi
			from aset.tm_pengadaan
			where id='".$id."' and c_sdh_realisasi = 'T'";	  
	  $result = $db->fetchAll($sql);
      $db->closeConnection();	  
	  return $result;
	 }catch(Exception $ex){
	  echo $e->getMessage();
	  $db->closeConnection();
	  return "Data tidak ada";
	 }
	}

	public function getDataRencanaPengadaanPP($pageNumber,$itemPerPage,$where,$order) 
	{
	    $registry = Zend_Registry::getInstance();
	    $db = $registry->get('db');
	    try{
	       $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		   if(($pageNumber==0) && ($itemPerPage==0))
		   {
			  $sql= "select count(id) from aset.tm_pengadaan where c_sdh_realisasi = 'T'";
			  if($where!=""){ $sql .= $where; }
	          $result = $db->fetchAll($sql);
		      $hasilAkhir=count($result);		
		   }			 
		   else
		   {
			  $xLimit=$itemPerPage;
			  $xOffset=($pageNumber-1)*$itemPerPage;	
			  $sql= "select id,c_jenis_aset,i_nama_aset,i_keterangan,i_nilai,d_pengajuan,
				        d_est_perolehan,c_sdh_realisasi
						from aset.tm_pengadaan
						where c_sdh_realisasi = 'T'";
			  if($where!=""){ $sql .= $where; }
			  if($order!=""){ $sql .= " Order by ".$order; }
			  $sql .= " limit $xLimit offset $xOffset";				
	          $result = $db->fetchAll($sql);			  
		      $jmlResult = count($result);
		 
			  for ($j = 0; $j < $jmlResult; $j++) {
				   if((string)$result[$j]->c_sdh_realisasi=="T"){$deskstatus="Belum Terealisasi";}				  
		           $hasilAkhir[$j] = array("c_jenis_aset"           =>(string)$result[$j]->c_jenis_aset,
				                           "i_nama_aset"           =>(string)$result[$j]->i_nama_aset,
				                           "id"           =>(string)$result[$j]->id,
				                           "i_keterangan"           =>(string)$result[$j]->i_keterangan,
				                           "i_nilai"           =>(string)$result[$j]->i_nilai,
				                           "d_pengajuan"           =>(string)$result[$j]->d_pengajuan,
				                           "d_est_perolehan"           =>(string)$result[$j]->d_est_perolehan,
				                           "c_sdh_realisasi"           =>$deskstatus
										   );
			  }	
            }			
            $db->closeConnection();			
	        return $hasilAkhir; 		 
	    }catch(Exception $ex){
	      echo $ex->getMessage().'<br>';
	      $db->closeConnection();
	      return 'gagal <br>';
	    }
	}

	public function getDataRencanaPengadaan($pageNumber,$itemPerPage,$jenisAset,$namaAset,$tanggalAjuan) {
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		   if(($pageNumber==0) && ($itemPerPage==0))
		   {
				 $sql= "select id,c_jenis_aset,i_nama_aset,i_keterangan,i_nilai,d_pengajuan,
				        d_est_perolehan,c_sdh_realisasi
						from aset.tm_pengadaan
						where c_sdh_realisasi = 'T'";
				if($jenisAset!=""){
				 $sql=$sql." and c_jenis_aset='".$jenisAset."'";
				}
				if($namaAset!=""){				 
				 $sql=$sql." and i_nama_aset='".$namaAset."'";
				}
				if($tanggalAjuan!=""){				
				 $sql=$sql." and d_pengajuan='".$tanggalAjuan."'";
				}
				$sql.=" order by d_pengajuan desc";
	            $result = $db->fetchAll($sql);
		        $hasilAkhir=count($result);		
                			
			}			 
			else
			{
			  $xLimit=$itemPerPage;
			  $xOffset=($pageNumber-1)*$itemPerPage;	
			   $sql= "select id,c_jenis_aset,i_nama_aset,i_keterangan,i_nilai,d_pengajuan,
				        d_est_perolehan,c_sdh_realisasi
						from aset.tm_pengadaan
						where c_sdh_realisasi = 'T'";
				if($jenisAset!=""){
				 $sql=$sql." and c_jenis_aset='".$jenisAset."'";
				}
				if($namaAset!=""){				 
				 $sql=$sql." and i_nama_aset='".$namaAset."'";
				}
				if($tanggalAjuan!=""){				
				 $sql=$sql." and d_pengajuan='".$tanggalAjuan."'";
				}
				$sql.=" order by d_pengajuan desc";
				$sql=$sql." limit $xLimit offset $xOffset";				
	            $result = $db->fetchAll($sql);			  
		        $jmlResult = count($result);
		 
				 for ($j = 0; $j < $jmlResult; $j++) {
				   if((string)$result[$j]->c_sdh_realisasi=="T"){$deskstatus="Belum Terealisasi";}				  
		           $hasilAkhir[$j] = array("c_jenis_aset"           =>(string)$result[$j]->c_jenis_aset,
				                           "i_nama_aset"           =>(string)$result[$j]->i_nama_aset,
				                           "id"           =>(string)$result[$j]->id,
				                           "i_keterangan"           =>(string)$result[$j]->i_keterangan,
				                           "i_nilai"           =>(string)$result[$j]->i_nilai,
				                           "d_pengajuan"           =>(string)$result[$j]->d_pengajuan,
				                           "d_est_perolehan"           =>(string)$result[$j]->d_est_perolehan,
				                           "c_sdh_realisasi"           =>$deskstatus
										   );
				 }	
            }			
         $db->closeConnection();			
	     return $hasilAkhir; 		 
	 }catch(Exception $ex){
	  echo $ex->getMessage().'<br>';
	  $db->closeConnection();
	  return 'gagal <br>';
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
				 $sql= "select a.i_peg_nip,a.n_peg,c.n_jabatan
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
                			
			}			 
			else
			{
			  $xLimit=$itemPerPage;
			  $xOffset=($pageNumber-1)*$itemPerPage;	
			  $sql= "select a.i_peg_nip,a.n_peg,c.n_jabatan
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
	public function getKategoriLapRencanaPengadaan(){
	  $registry = Zend_Registry::getInstance();
	  $db = $registry->get('db');
	  try{
	    $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	  $sqlkat = "select distinct c_jenis_aset,count(c_jenis_aset) as baris
				from aset.v_pengadaan
				where c_sdh_realisasi = 'T'
				group by c_jenis_aset";
	  $kategori = $db->fetchAll($sqlkat);
	  $jmlKat = count($kategori);
	  for($i=0;$i<$jmlKat;$i++){
	    if($kategori[$i]->c_jenis_aset == 'TNH'){$jenis = 'Tanah';}
	    if($kategori[$i]->c_jenis_aset == 'BDG'){$jenis = 'Bangunan & Gedung';}
	    if($kategori[$i]->c_jenis_aset == 'LNN'){$jenis = 'Lain-lain';}
	    $hasil[$i]=array("kategorijenisaset"=>(string)$jenis,
		                 "kodejenis"=>(string)$kategori[$i]->c_jenis_aset,
						 "jumlah"=>$kategori[$i]->baris);
		 }
		
		 $db->closeConnection();			
	     return $hasil; 
	  
	  }catch(Exception $ex)
	  {
	     echo $ex->getMessage().'<br>';
	     $db->closeConnection();
	     return 'Data Tidak Ada <br>';
	  }
	}
	public function getKategoriLapRealisasiPengadaan($c_jenis_aset){
	  $registry = Zend_Registry::getInstance();
	  $db = $registry->get('db');
	  try{
	    $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	 $sqlkat = "select distinct c_jenis_aset,count(c_jenis_aset) as baris
				from aset.v_pengadaan
				where c_sdh_realisasi = 'Y'";
		if ($c_jenis_aset != '' ) $sqlkat .=" and c_jenis_aset='$c_jenis_aset'";
				 $sqlkat .= "group by c_jenis_aset";
	  $kategori = $db->fetchAll($sqlkat);
	  $jmlKat = count($kategori);
	  for($i=0;$i<$jmlKat;$i++){
	    if($kategori[$i]->c_jenis_aset == 'TNH'){$jenis = 'Tanah';}
	    if($kategori[$i]->c_jenis_aset == 'BDG'){$jenis = 'Bangunan & Gedung';}
	    if($kategori[$i]->c_jenis_aset == 'LNN'){$jenis = 'Lain-lain';}
	    $hasil[$i]=array("kategorijenisaset"=>(string)$jenis,
		                 "kodejenis"=>(string)$kategori[$i]->c_jenis_aset,
						 "jumlah"=>$kategori[$i]->baris);
		}
		 $db->closeConnection();			
	     return $hasil; 
	  
	  }catch(Exception $ex)
	  {
	     echo $ex->getMessage().'<br>';
	     $db->closeConnection();
	     return 'Data Tidak Ada <br>';
	  }
	}
	public function getLaporanRencanaPengadaan(){
	  $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	  $sql= "select id,c_jenis_aset,i_nama_aset,i_keterangan,i_nilai,d_pengajuan,
			d_est_perolehan,c_sdh_realisasi
			from aset.v_pengadaan
			where c_sdh_realisasi = 'T'";
	  $result = $db->fetchAll($sql);
	  $jmlResult = count($result);
	  for ($j = 0; $j < $jmlResult; $j++) {
				   if((string)$result[$j]->c_sdh_realisasi=="T"){$deskstatus="Belum Terealisasi";}				  
		           $hasilAkhir[$j] = array("c_jenis_aset"           =>(string)$result[$j]->c_jenis_aset,
				                           "i_nama_aset"           =>(string)$result[$j]->i_nama_aset,
				                           "id"           =>(string)$result[$j]->id,
				                           "i_keterangan"           =>(string)$result[$j]->i_keterangan,
				                           "i_nilai"           =>(string)$result[$j]->i_nilai,
				                           "d_pengajuan"           =>(string)$result[$j]->d_pengajuan,
				                           "d_est_perolehan"           =>(string)$result[$j]->d_est_perolehan,
				                           "c_sdh_realisasi"           =>$deskstatus
										   );
				 }	
	     $db->closeConnection();			
	     return $hasilAkhir; 	
	 }catch(Exception $ex){
	  echo $ex->getMessage().'<br>';
	  $db->closeConnection();
	  return 'gagal <br>';
	 }
	}
	public function getLaporanRealisasiPengadaan($c_jenis_aset){
	  $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	  $sql= "select id,c_jenis_aset,i_nama_aset,i_keterangan,i_nilai,d_pengajuan,
			d_est_perolehan,c_sdh_realisasi
			from aset.v_pengadaan
			where c_sdh_realisasi = 'Y'";
			if ($c_jenis_aset != '' ) $sql .=" and c_jenis_aset='$c_jenis_aset'";
	  $result = $db->fetchAll($sql);
	  $jmlResult = count($result);
	  for ($j = 0; $j < $jmlResult; $j++) {
				   if((string)$result[$j]->c_sdh_realisasi=="Y"){$deskstatus="Sudah Terealisasi";}				  
		           $hasilAkhir[$j] = array("c_jenis_aset"           =>(string)$result[$j]->c_jenis_aset,
				                           "i_nama_aset"           =>(string)$result[$j]->i_nama_aset,
				                           "id"           =>(string)$result[$j]->id,
				                           "i_keterangan"           =>(string)$result[$j]->i_keterangan,
				                           "i_nilai"           =>(string)$result[$j]->i_nilai,
				                           "d_pengajuan"           =>(string)$result[$j]->d_pengajuan,
				                           "d_est_perolehan"           =>(string)$result[$j]->d_est_perolehan,
				                           "c_sdh_realisasi"           =>$deskstatus
										   );
				 }	
	     $db->closeConnection();			
	     return $hasilAkhir; 	
	 }catch(Exception $ex){
	  echo $ex->getMessage().'<br>';
	  $db->closeConnection();
	  return 'gagal <br>';
	 }
	}
}		
?>