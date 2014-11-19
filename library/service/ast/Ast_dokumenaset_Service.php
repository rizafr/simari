<?php
class Ast_dokumenaset_Service {
   
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
	//public function getRekonsiliasi($pageNumber,$itemPerPage,$thnang,$satker) {  
    public function insertDokumenAset(array $data){
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 
	   try {
	     $db->beginTransaction();
		 $dataDokumen = array("thn_ang"         		=>$data['thn_ang'],
	                           "periode"    	        	=>$data['periode'],
						       "kd_lokasi"                =>$data['kd_lokasi'],
							   "no_sppa"               =>$data['no_sppa'],
							   "kd_brg"  	            =>$data['kd_brg'],
							   "no_aset"                 =>$data['no_aset'],
							   "filename"                =>$data['filename'],
						       "i_keterangan"  	        =>$data['i_keterangan']);
	    
	     $db->insert('aset.tm_aset_dok',$dataDokumen);
		 $db->commit();
		 $db->closeConnection();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
		 $db->closeConnection();
	     return 'gagal';
	   }
    }	
	public function updateDokumenAset(array $data){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->beginTransaction();
		 $dataDokumen = array(
	                           "thn_ang"    	        	=>$data['thn_ang'],
						       "periode"                =>$data['periode'],
							   "kd_lokasi"               =>$data['kd_lokasi'],
							   "no_sppa"  	            =>$data['no_sppa'],
							   "kd_brg"                 =>$data['kd_brg'],
							   "no_aset"                =>$data['no_aset'],						       							  
						       "filename"       		=>$data['filename'],
						       "i_keterangan"       	=>$data['i_keterangan']);
	     $where[]="id='".$data['id']."'";	     
	     $db->update('aset.tm_aset_dok',$dataDokumen,$where);
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
	public function hapusDokumenAset($kode){
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try{
		$db->beginTransaction();
		$key[] = "id = '".$kode."'";			
		$db->delete("aset.tm_aset_dok",$key);
		$db->commit();
		$db->closeConnection();
		return 'sukses';
		}catch(Exception $ex){
		echo $e->getMessage().'<br>';
		$db->closeConnection();
		return 'gagal <br>';
		}
	}
	public function getDataMasterHM($pageNumber,$itemPerPage,$thnang,$periode,$kdlokasi,$nosppa,$kdbrg,$noaset) {     
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
		    $sql = "select count(*)
					from aset.tm_masterhm
					where thn_ang = '$thnang'";
			if(strlen($periode)>0){
			  $sql=$sql." and periode='$periode'";
			}
			if(strlen($kdlokasi)>0){
			  $sql=$sql." and kd_lokasi='$kdlokasi'";
			}
			if(strlen($nosppa)>0){
			  $sql=$sql." and no_sppa='$nosppa'";
			}
			if(strlen($kd_brg)>0){
			  $sql=$sql." and kd_brg='$kdbrg'";
			}
			if(strlen($noaset)>0){
			  $sql=$sql." and no_aset='$noaset'";
			}
			$hasilAkhir = $db->fetchOne($sql); 
            			
		 }
		 else
		 {
		    $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		    $sql = "select thn_ang,periode,kd_lokasi,no_sppa,kd_brg,no_aset
					from aset.tm_masterhm
					where thn_ang = '$thnang'";
			 
			if(strlen($periode)>0){
			  $sql=$sql." and periode='$periode'";
			}
			if(strlen($kdlokasi)>0){
			  $sql=$sql." and kd_lokasi='$kdlokasi'";
			}
			if(strlen($nosppa)>0){
			  $sql=$sql." and no_sppa='$nosppa'";
			}
			if(strlen($kd_brg)>0){
			  $sql=$sql." and kd_brg='$kdbrg'";
			}
			if(strlen($noaset)>0){
			  $sql=$sql." and no_aset='$noaset'";
			}
			$sql=$sql." order by thn_ang 
			            limit $xLimit offset $xOffset";
			 
		  //echo $sql;
		 $result = $db->fetchAll($sql); 								 
								 

         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("thn_ang"           =>(string)$result[$j]->thn_ang,
								   "periode"           =>(string)$result[$j]->periode,
								   "kd_lokasi"           =>(string)$result[$j]->kd_lokasi,
								   "no_sppa"           =>(string)$result[$j]->no_sppa,
								   "kd_brg"           =>(string)$result[$j]->kd_brg,
								   "no_aset"           =>(string)$result[$j]->no_aset);
		  }
         }
        }	
        		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getDataDokumenById($kode){
	 $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	    $sql = "select id,thn_ang,periode,kd_lokasi,no_sppa,kd_brg,no_aset,id,filename,i_keterangan
				from aset.tm_aset_dok
				where id = '$kode'";
		$result = $db->fetchAll($sql); 
		return $result;
	   }catch(Exception $ex){
	    echo $e->getMessage().'<br>';
	    return 'Data Tidak Ada <br>';
	   }
	}
	public function getDataDokumen($pageNumber,$itemPerPage,$thnang,$periode,$kdlokasi,$nosppa,$kdbrg,$noaset) {     
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
		    $sql = "select count(*)
					from aset.tm_aset_dok
					where thn_ang = '$thnang'";
			if(strlen($periode)>0){
			  $sql=$sql." and periode='$periode'";
			}
			if(strlen($kdlokasi)>0){
			  $sql=$sql." and kd_lokasi='$kdlokasi'";
			}
			if(strlen($nosppa)>0){
			  $sql=$sql." and no_sppa='$nosppa'";
			}
			if(strlen($kd_brg)>0){
			  $sql=$sql." and kd_brg='$kdbrg'";
			}
			if(strlen($noaset)>0){
			  $sql=$sql." and no_aset='$noaset'";
			}
			$hasilAkhir = $db->fetchOne($sql); 
            			
		 }
		 else
		 {
		    $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;	
		    $sql = "select thn_ang,periode,kd_lokasi,no_sppa,kd_brg,no_aset,id,filename,i_keterangan
					from aset.tm_aset_dok
					where thn_ang = '$thnang'";
			 
			if(strlen($periode)>0){
			  $sql=$sql." and periode='$periode'";
			}
			if(strlen($kdlokasi)>0){
			  $sql=$sql." and kd_lokasi='$kdlokasi'";
			}
			if(strlen($nosppa)>0){
			  $sql=$sql." and no_sppa='$nosppa'";
			}
			if(strlen($kd_brg)>0){
			  $sql=$sql." and kd_brg='$kdbrg'";
			}
			if(strlen($noaset)>0){
			  $sql=$sql." and no_aset='$noaset'";
			}
			$sql=$sql." order by thn_ang 
			            limit $xLimit offset $xOffset";
			 
		  //echo $sql;
		 $result = $db->fetchAll($sql); 								 
								 

         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("thn_ang"           =>(string)$result[$j]->thn_ang,
								   "periode"           =>(string)$result[$j]->periode,
								   "kd_lokasi"           =>(string)$result[$j]->kd_lokasi,
								   "no_sppa"           =>(string)$result[$j]->no_sppa,
								   "kd_brg"           =>(string)$result[$j]->kd_brg,
								   "no_aset"           =>(string)$result[$j]->no_aset,
								   "id"                =>(string)$result[$j]->id,
								   "filename"           =>(string)$result[$j]->filename,
								   "keterangan"           =>(string)$result[$j]->i_keterangan);
		  }
         }
        }	
        		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
	
	public function getJenisTransaksi(){
	$registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ);
	  $sql="select jns_trn,ur_trn from aset.tm_croleh order by jns_trn";
	  $result = $db->fetchAll($sql);
	  $jmlresult = count($result);
	  //print_r($result);
	  for($i=0;$i<$jmlresult;$i++){
	    $datasatker[$i]=array("jns_trn"=>(string)$result[$i]->jns_trn,
		                      "ur_trn"=>(string)$result[$i]->ur_trn);
	  }
	  
	  return $datasatker;
	 }catch(Exception $ex){
	  echo $ex->getMessage().'<br>';
	 }
	}
	public function getJenisTransaksiByKode($kodejnstrans){
	$registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ);
	  $sql="select ur_trn from aset.tm_croleh where jns_trn='$kodejnstrans'";
	  $result = $db->fetchOne($sql);
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage().'<br>';
	 }
	}
	
	public function getDataSPM($thnang,$satker,$jnstrn) {     
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $sql = "select a.kd_lokasi,a.no_sppa,a.no_sp2d,a.bkpk,a.tgl_sp2d,a.jml_spm,a.kd_brg,
		            a.no_aset,a.jns_trn,a.tgl_buku,b.ur_sskel,c.ur_trn
					from aset.tm_spm a,aset.tm_sskel b,aset.tm_croleh c
					where a.kd_brg = b.kd_brg
					and substr(a.kd_brg,1,1) = b.kd_gol
					and substr(a.kd_brg,2,2) = b.kd_bid 
					and substr(a.kd_brg,4,2) = b.kd_kel
					and substr(a.kd_brg,6,2) = b.kd_skel
					and substr(a.kd_brg,8,3) = b.kd_sskel
					and a.jns_trn = c.jns_trn
					and a.thn_ang = '$thnang'";
			if(strlen($satker)>0){
			  $sql=$sql." and a.kd_lokasi like '_________$satker%'";
			}
			if(strlen($jnstrn)>0){
			  $sql=$sql." and a.jns_trn = '$jnstrn'";
			}
			$sql=$sql."	order by a.jns_trn";
		 //echo "sql : ".$sql;
		 $result = $db->fetchAll($sql); 								 
								 

         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("kd_lokasi"           =>(string)$result[$j]->kd_lokasi,
								   "no_sppa"           =>(string)$result[$j]->no_sppa,
								   "no_sp2d"           =>(string)$result[$j]->no_sp2d,
								   "bkpk"           =>(string)$result[$j]->bkpk,
								   "tgl_sp2d"           =>(string)$result[$j]->tgl_sp2d,
								   "jml_spm"           =>(string)$result[$j]->jml_spm,
								   "kd_brg"           =>(string)$result[$j]->kd_brg,
								   "no_aset"           =>(string)$result[$j]->no_aset,
								   "jns_trn"           =>(string)$result[$j]->jns_trn,
								   "tgl_buku"           =>(string)$result[$j]->tgl_buku,
								   "ur_sskel"           =>(string)$result[$j]->ur_sskel,
								   "ur_trn"           =>(string)$result[$j]->ur_trn,
								   );
		  }
         //}
        }	
         		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
}	
?>