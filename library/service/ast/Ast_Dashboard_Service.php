<?php
class Ast_Dashboard_Service {
   
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
	public function getPemindahanInventarisBL($tahunanggaran){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ);
	    $sql="select sum(nilai) from aset.v_dashboard_data
		where id_dashboard='2' and jenis='Belum Lengkap' and tahun='$tahunanggaran' 
		group by jenis";			
	     $result = $db->fetchOne($sql);
      $db->closeConnection();	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	  $db->closeConnection();
	  return "Data tidak ada";
	 }
	}
    public function getPemindahanInventarisBD($tahunanggaran){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ);
	    $sql="select sum(nilai) from aset.v_dashboard_data
		where id_dashboard='2' and jenis='belum disetujui' and tahun='$tahunanggaran' 
		group by jenis";			
	     $result = $db->fetchOne($sql);
      $db->closeConnection();	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	  $db->closeConnection();
	  return "Data tidak ada";
	 }
	}
	public function getPemindahanInventarisTD($tahunanggaran){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ);
	    $sql="select sum(nilai) from aset.v_dashboard_data
		where id_dashboard='2' and jenis='tidak disetujui' and tahun='$tahunanggaran' 
		group by jenis";			
	     $result = $db->fetchOne($sql);
      $db->closeConnection();	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	  $db->closeConnection();
	  return "Data tidak ada";
	 }
	}
	public function getPemindahanInventarisSD($tahunanggaran){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ);
	    $sql="select sum(nilai) from aset.v_dashboard_data
		where id_dashboard='2' and jenis='sudah disetujui' and tahun='$tahunanggaran' 
		group by jenis";			
	     $result = $db->fetchOne($sql);
      $db->closeConnection();	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	  $db->closeConnection();
	  return "Data tidak ada";
	 }
	}
    public function getJumlahRpTanah($tahunanggaran){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ);
	    // $sql="select sum(a.rph_aset)
			   // from aset.tm_ktnh a,aset.tm_masterhm b
			   // where 
			   // a.kd_brg = b.kd_brg
			   // and b.thn_ang='$tahunanggaran'";		
         $sql="select sum(a.rph_aset)
			from aset.tm_masterhm a,aset.tm_gol b,aset.tm_sskel c
			where a.kd_brg = c.kd_brg and b.kd_gol = c.kd_gol
			and a.thn_ang <= '$tahunanggaran'
			and b.kd_gol='2'
			";				   
	     $result = $db->fetchOne($sql);
      $db->closeConnection();	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	  $db->closeConnection();
	  return "Data tidak ada";
	 }
	
	}
	public function getJumlahTanah($tahunanggaran){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ);
	    // $sql="select count(a.kd_brg)
			   // from aset.tm_ktnh a,aset.tm_masterhm b
			   // where 
			   // a.kd_brg = b.kd_brg
			   // and b.thn_ang='$tahunanggaran'";		
         $sql="select count(a.kd_brg)
			from aset.tm_masterhm a,aset.tm_gol b,aset.tm_sskel c
			where a.kd_brg = c.kd_brg and b.kd_gol = c.kd_gol
			and a.thn_ang <= '$tahunanggaran'
			and b.kd_gol='2'
			group by b.kd_gol
			order by b.kd_gol";			   
	     $result = $db->fetchOne($sql);
      $db->closeConnection();	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	  $db->closeConnection();
	  return "Data tidak ada";
	 }
	
	}
	public function getJumlahRpPersediaan($tahunanggaran){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ);
	    // $sql="select sum(a.rph_aset)
			   // from aset.tm_ktnh a,aset.tm_masterhm b
			   // where 
			   // a.kd_brg = b.kd_brg
			   // and b.thn_ang='$tahunanggaran'";		
         $sql="select sum(a.rph_aset)
			from aset.tm_masterhm a,aset.tm_gol b,aset.tm_sskel c
			where a.kd_brg = c.kd_brg and b.kd_gol = c.kd_gol
			and a.thn_ang <= '$tahunanggaran'
			and b.kd_gol='1'";				   
	     $result = $db->fetchOne($sql);
      $db->closeConnection();	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	  $db->closeConnection();
	  return "Data tidak ada";
	 }
	
	}
	public function getJumlahPersediaan($tahunanggaran){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ);
	    // $sql="select count(a.kd_brg)
			   // from aset.tm_ktnh a,aset.tm_masterhm b
			   // where 
			   // a.kd_brg = b.kd_brg
			   // and b.thn_ang='$tahunanggaran'";		
         $sql="select count(a.kd_brg)
			from aset.tm_masterhm a,aset.tm_gol b,aset.tm_sskel c
			where a.kd_brg = c.kd_brg and b.kd_gol = c.kd_gol
			and a.thn_ang <= '$tahunanggaran'
			and b.kd_gol='1'
			group by b.kd_gol
			order by b.kd_gol";			   
	     $result = $db->fetchOne($sql);
      $db->closeConnection();	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	  $db->closeConnection();
	  return "Data tidak ada";
	 }
	
	}
	public function getJumlahRpPeralatandanmesin($tahunanggaran){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ);
	    // $sql="select sum(a.rph_aset)
			   // from aset.tm_ktnh a,aset.tm_masterhm b
			   // where 
			   // a.kd_brg = b.kd_brg
			   // and b.thn_ang='$tahunanggaran'";		
         $sql="select sum(a.rph_aset)
			from aset.tm_masterhm a,aset.tm_gol b,aset.tm_sskel c
			where a.kd_brg = c.kd_brg and b.kd_gol = c.kd_gol
			and a.thn_ang <= '$tahunanggaran'
			and b.kd_gol='3'";				   
	     $result = $db->fetchOne($sql);
      $db->closeConnection();	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	  $db->closeConnection();
	  return "Data tidak ada";
	 }
	
	}
	public function getJumlahPeralatandanmesin($tahunanggaran){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ);
	    // $sql="select count(a.kd_brg)
			   // from aset.tm_ktnh a,aset.tm_masterhm b
			   // where 
			   // a.kd_brg = b.kd_brg
			   // and b.thn_ang='$tahunanggaran'";		
         $sql="select count(a.kd_brg)
			from aset.tm_masterhm a,aset.tm_gol b,aset.tm_sskel c
			where a.kd_brg = c.kd_brg and b.kd_gol = c.kd_gol
			and a.thn_ang <= '$tahunanggaran'
			and b.kd_gol='3'
			group by b.kd_gol
			order by b.kd_gol";			   
	     $result = $db->fetchOne($sql);
      $db->closeConnection();	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	  $db->closeConnection();
	  return "Data tidak ada";
	 }
	
	}
	public function getJumlahRpJalan($tahunanggaran){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ);
	    // $sql="select sum(a.rph_aset)
			   // from aset.tm_ktnh a,aset.tm_masterhm b
			   // where 
			   // a.kd_brg = b.kd_brg
			   // and b.thn_ang='$tahunanggaran'";		
         $sql="select sum(a.rph_aset)
			from aset.tm_masterhm a,aset.tm_gol b,aset.tm_sskel c
			where a.kd_brg = c.kd_brg and b.kd_gol = c.kd_gol
			and a.thn_ang <= '$tahunanggaran'
			and b.kd_gol='5'";				   
	     $result = $db->fetchOne($sql);
      $db->closeConnection();	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	  $db->closeConnection();
	  return "Data tidak ada";
	 }
	
	}
	public function getJumlahJalan($tahunanggaran){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ);
	    // $sql="select count(a.kd_brg)
			   // from aset.tm_ktnh a,aset.tm_masterhm b
			   // where 
			   // a.kd_brg = b.kd_brg
			   // and b.thn_ang='$tahunanggaran'";		
         $sql="select count(a.kd_brg)
			from aset.tm_masterhm a,aset.tm_gol b,aset.tm_sskel c
			where a.kd_brg = c.kd_brg and b.kd_gol = c.kd_gol
			and a.thn_ang <= '$tahunanggaran'
			and b.kd_gol='5'
			group by b.kd_gol
			order by b.kd_gol";			   
	     $result = $db->fetchOne($sql);
      $db->closeConnection();	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	  $db->closeConnection();
	  return "Data tidak ada";
	 }
	
	}
	public function getJumlahRpAsetTetapLainnya($tahunanggaran){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ);
	    // $sql="select sum(a.rph_aset)
			   // from aset.tm_ktnh a,aset.tm_masterhm b
			   // where 
			   // a.kd_brg = b.kd_brg
			   // and b.thn_ang='$tahunanggaran'";		
         $sql="select sum(a.rph_aset)
			from aset.tm_masterhm a,aset.tm_gol b,aset.tm_sskel c
			where a.kd_brg = c.kd_brg and b.kd_gol = c.kd_gol
			and a.thn_ang <= '$tahunanggaran'
			and b.kd_gol='6'";				   
	     $result = $db->fetchOne($sql);
      $db->closeConnection();	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	  $db->closeConnection();
	  return "Data tidak ada";
	 }
	
	}
	public function getJumlahAsetTetapLainnya($tahunanggaran){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ);
	    // $sql="select count(a.kd_brg)
			   // from aset.tm_ktnh a,aset.tm_masterhm b
			   // where 
			   // a.kd_brg = b.kd_brg
			   // and b.thn_ang='$tahunanggaran'";		
         $sql="select count(a.kd_brg)
			from aset.tm_masterhm a,aset.tm_gol b,aset.tm_sskel c
			where a.kd_brg = c.kd_brg and b.kd_gol = c.kd_gol
			and a.thn_ang <= '$tahunanggaran'
			and b.kd_gol='6'
			group by b.kd_gol
			order by b.kd_gol";			   
	     $result = $db->fetchOne($sql);
      $db->closeConnection();	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	  $db->closeConnection();
	  return "Data tidak ada";
	 }
	
	}
	public function getJumlahRpKDP($tahunanggaran){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ);
	    // $sql="select sum(a.rph_aset)
			   // from aset.tm_ktnh a,aset.tm_masterhm b
			   // where 
			   // a.kd_brg = b.kd_brg
			   // and b.thn_ang='$tahunanggaran'";		
         $sql="select sum(a.rph_aset)
			from aset.tm_masterhm a,aset.tm_gol b,aset.tm_sskel c
			where a.kd_brg = c.kd_brg and b.kd_gol = c.kd_gol
			and a.thn_ang <= '$tahunanggaran'
			and b.kd_gol='7'";				   
	     $result = $db->fetchOne($sql);
      $db->closeConnection();	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	  $db->closeConnection();
	  return "Data tidak ada";
	 }
	
	}
	public function getJumlahKDP($tahunanggaran){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ);
	    // $sql="select count(a.kd_brg)
			   // from aset.tm_ktnh a,aset.tm_masterhm b
			   // where 
			   // a.kd_brg = b.kd_brg
			   // and b.thn_ang='$tahunanggaran'";		
         $sql="select count(a.kd_brg)
			from aset.tm_masterhm a,aset.tm_gol b,aset.tm_sskel c
			where a.kd_brg = c.kd_brg and b.kd_gol = c.kd_gol
			and a.thn_ang <= '$tahunanggaran'
			and b.kd_gol='7'
			group by b.kd_gol
			order by b.kd_gol";			   
	     $result = $db->fetchOne($sql);
      $db->closeConnection();	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	  $db->closeConnection();
	  return "Data tidak ada";
	 }
	
	}
	public function getJumlahRpATB($tahunanggaran){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ);
	    // $sql="select sum(a.rph_aset)
			   // from aset.tm_ktnh a,aset.tm_masterhm b
			   // where 
			   // a.kd_brg = b.kd_brg
			   // and b.thn_ang='$tahunanggaran'";		
         $sql="select sum(a.rph_aset)
			from aset.tm_masterhm a,aset.tm_gol b,aset.tm_sskel c
			where a.kd_brg = c.kd_brg and b.kd_gol = c.kd_gol
			and a.thn_ang <= '$tahunanggaran'
			and b.kd_gol='8'";				   
	     $result = $db->fetchOne($sql);
      $db->closeConnection();	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	  $db->closeConnection();
	  return "Data tidak ada";
	 }
	
	}
	public function getJumlahATB($tahunanggaran){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ);
	    // $sql="select count(a.kd_brg)
			   // from aset.tm_ktnh a,aset.tm_masterhm b
			   // where 
			   // a.kd_brg = b.kd_brg
			   // and b.thn_ang='$tahunanggaran'";		
         $sql="select count(a.kd_brg)
			from aset.tm_masterhm a,aset.tm_gol b,aset.tm_sskel c
			where a.kd_brg = c.kd_brg and b.kd_gol = c.kd_gol
			and a.thn_ang <= '$tahunanggaran'
			and b.kd_gol='8'
			group by b.kd_gol
			order by b.kd_gol";			   
	     $result = $db->fetchOne($sql);
      $db->closeConnection();	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	  $db->closeConnection();
	  return "Data tidak ada";
	 }
	
	}
     public function getJumlahRpBDG($tahunanggaran){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ);
	  
		  // $sql="select sum(a.rph_aset)
			   // from aset.tm_kbdg a,aset.tm_masterhm b
			   // where 
			   // a.kd_brg = b.kd_brg
			   // and b.thn_ang='$tahunanggaran'";	
            $sql="select sum(a.rph_aset)
			from aset.tm_masterhm a,aset.tm_gol b,aset.tm_sskel c
			where a.kd_brg = c.kd_brg and b.kd_gol = c.kd_gol
			and a.thn_ang <= '$tahunanggaran'
			and b.kd_gol='4'";				   
	     $result = $db->fetchOne($sql);
      $db->closeConnection();	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	  $db->closeConnection();
	  return "Data tidak ada";
	 }
	}
	
	 public function getJumlahBDG($tahunanggaran){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ);
	  
		  // $sql="select count(a.kd_brg)
			   // from aset.tm_kbdg a,aset.tm_masterhm b
			   // where 
			   // a.kd_brg = b.kd_brg
			   // and b.thn_ang='$tahunanggaran'";		
             $sql="select count(a.kd_brg)
			from aset.tm_masterhm a,aset.tm_gol b,aset.tm_sskel c
			where a.kd_brg = c.kd_brg and b.kd_gol = c.kd_gol
			and a.thn_ang <= '$tahunanggaran'
			and b.kd_gol='4'
			group by b.kd_gol
			order by b.kd_gol";				   
	     $result = $db->fetchOne($sql);
      $db->closeConnection();	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	  $db->closeConnection();
	  return "Data tidak ada";
	 }
	}
	public function getJumlahRpAngk($tahunanggaran){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ);
	    
		  $sql="select sum(a.rph_aset)
			   from aset.tm_kangk a,aset.tm_masterhm b
			   where 
			   a.kd_brg = b.kd_brg
			   and b.thn_ang='$tahunanggaran'";			
	     $result = $db->fetchOne($sql);
      $db->closeConnection();	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	  $db->closeConnection();
	  return "Data tidak ada";
	 }
	}
	public function getJumlahAngk($tahunanggaran){
	 $registry = Zend_Registry::getInstance();
	 $db = $registry->get('db');
	 try{
	  $db->setFetchMode(Zend_Db::FETCH_OBJ);
	    
		  $sql="select count(a.kd_brg)
			   from aset.tm_kangk a,aset.tm_masterhm b
			   where 
			   a.kd_brg = b.kd_brg
			   and b.thn_ang='$tahunanggaran'";			
	     $result = $db->fetchOne($sql);
      $db->closeConnection();	  
	  return $result;
	 }catch(Exception $ex){
	  echo $ex->getMessage();
	  $db->closeConnection();
	  return "Data tidak ada";
	 }
	}
}	
?>