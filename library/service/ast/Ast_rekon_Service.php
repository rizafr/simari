<?php
class Ast_rekon_Service {
   
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
	public function getRekonsiliasi($thnang,$satker) {     
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 // if(($pageNumber==0) && ($itemPerPage==0))
		 // {
		    // $sql = "select distinct b.kd_perkd,b.ur_perkd,sum(c.rph_aset) as jumlahaset
					// from aset.tm_sskel a,aset.tm_perk b,aset.tm_masterhm c
					// where a.kd_perk = b.kd_perkd
					// and a.kd_brg = c.kd_brg
					// and substr(c.kd_brg,1,1) = a.kd_gol
					// and substr(c.kd_brg,2,2) = a.kd_bid 
					// and substr(c.kd_brg,4,2) = a.kd_kel
					// and substr(c.kd_brg,6,2) = a.kd_skel
					// and substr(c.kd_brg,8,3) = a.kd_sskel
					// and c.thn_ang = '$thnang'					
					// group by b.kd_perkd,b.ur_perkd
					// order by b.kd_perkd";
			// $hasilAkhir = $db->fetchAll($sql); 
            // $hasilAkhir = count($hasilAkhir);			
		 // }
		 // else
		 // {
			
			 // $xLimit=$itemPerPage;
			 // $xOffset=($pageNumber-1)*$itemPerPage;	
		  $sql = "select distinct b.kd_perkd,b.ur_perkd,sum(c.rph_aset) as jumlahaset
					from aset.tm_sskel a,aset.tm_perk b,aset.tm_masterhm c
					where a.kd_perk = b.kd_perkd
					and a.kd_brg = c.kd_brg
					and substr(c.kd_brg,1,1) = a.kd_gol
					and substr(c.kd_brg,2,2) = a.kd_bid 
					and substr(c.kd_brg,4,2) = a.kd_kel
					and substr(c.kd_brg,6,2) = a.kd_skel
					and substr(c.kd_brg,8,3) = a.kd_sskel
					and c.thn_ang = '$thnang'";
			if(strlen($satker)>0){
			  $sql=$sql." and c.kd_lokasi like '_________$satker%'";
			}
			$sql=$sql." group by b.kd_perkd,b.ur_perkd
					order by b.kd_perkd";
		 //echo "sql : ".$sql;
		 $result = $db->fetchAll($sql); 								 
								 

         $jmlResult = count($result);
		
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("kd_perkd"           =>(string)$result[$j]->kd_perkd,
								   "ur_perkd"           =>(string)$result[$j]->ur_perkd,
								   "jumlahaset"           =>(string)$result[$j]->jumlahaset);
		  }
         //}
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