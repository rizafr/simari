<?php
class ast_referensisatuan_services 
{   
	private static $instance;
        // A private constructor; prevents direct creation of object
        
    	private function __construct() 
    	{
      	//  echo 'I am constructed';
    	} 
    	// The singleton method
    	
     	public static function getInstance() 
     	{
	        if (!isset(self::$instance)) 
	        {
	           $c = __CLASS__;
	           self::$instance = new $c;
	       	}
	       	return self::$instance;
    	}
	
	public function getsatuan($pageNumber,$itemPerPage) 
	{
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try 
	   {
	   	$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		if(($pageNumber==0) && ($itemPerPage==0))
		{
			$hasilAkhir = $db->fetchOne("select count(*) from e_satuan_0_0_tr");
		}
		else
		{
		$xLimit=$itemPerPage;
		$xOffset=($pageNumber-1)*$itemPerPage;	
		
		$result = $db->fetchAll("SELECT c_satuan, e_satuan  
				FROM e_satuan_0_0_tr ORDER BY c_satuan
				limit $xLimit offset $xOffset"); 
		 
		$jmlResult = count($result);
		
		for ($j = 0; $j < $jmlResult; $j++) 
		{
		$hasilAkhir[$j] = array("c_satuan"  	=>(string)$result[$j]->c_satuan,
					"e_satuan"  	=>(string)$result[$j]->e_satuan);
		}
		}	 
	     	return $hasilAkhir;
	 } catch (Exception $e) 
	 {
         	echo $e->getMessage().'<br>';
	     	return 'Data tidak ada <br>';
	 }
	}
	
	public function getsatuan1() 
	{
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try 
	   {
	   	
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll('SELECT c_satuan, e_satuan
				  FROM e_satuan_0_0_tr order by c_satuan');
						 
         	 $jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++) 
		 {
			$hasilAkhir[$j] = array("c_satuan"  	=>(string)$result[$j]->c_satuan,
						"e_satuan"  	=>(string)$result[$j]->e_satuan);
		 }					 
	         return $hasilAkhir;
	   } 
	   catch (Exception $e) 
	   {
             echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function getsatuaninsert($data) 
	{	   
	   echo "masuk services getsatuaninsert";
	   $ambil = strtoupper($data['c_satuan']);
	   
	   $registry = Zend_Registry::getInstance();
   	   $db = $registry->get('db');
   	   try 
   	   {
   	   	$satuan = $data['c_satuan'];
   	   	$where[] = $satuan;
   	   	
   	   	$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchOne('SELECT c_satuan
				  FROM e_satuan_0_0_tr where c_satuan =?',$where);
		
		if (($result == null) || ($result == ''))
		{		  
	     	$db->beginTransaction();
		$insertsatuan = array("c_satuan"  	=>$ambil,
					"e_satuan"  	=>$data['e_satuan'],	
					"i_entry"       =>$data['nuser'],
					"d_entry"       =>date("Y-m-d"));
									
		$db->insert("e_satuan_0_0_tr", $insertsatuan);
		$db->commit();
		$_hasil = array("rcNumber"=>"1","rcDesc"  =>"Proses Sukses");
	     	return 'sukses';
		}
		else
		{
		$_hasil = array("rcNumber"=>"0","rcDesc"  =>"Proses Gagal");
	     	return 'gagal';	
		}	
		
   	   }
	   catch (Exception $e) 
   	   {
         	$db->rollBack();
         	echo $e->getMessage().'<br>';
	     	return 'gagal <br>';
   	   }
	}
	
	public function getsatuanedit($data) 
	{	   
	   $registry = Zend_Registry::getInstance();
   	   $db = $registry->get('db');
   	   try 
   	   {
	     	$db->beginTransaction();
		$updatesatuan = array("c_satuan"  	=>$data['c_satuan'],
					"e_satuan"  	=>$data['e_satuan'],	
					"i_entry"       =>$data['nuser'],
					"d_entry"       =>date("Y-m-d"));
		                      
	         
	        $where[] = "c_satuan 	=  '".trim($data['c_satuan'])."'";	
		$db->update('e_satuan_0_0_tr',$updatesatuan,$where);
		$db->commit();
		return 'sukses';
	   } 
	   catch (Exception $e) 
	   {
         	$db->rollBack();
         	echo $e->getMessage().'<br>';
	     	return 'gagal <br>';
	   }
	}
 
		
	
	public function getcarisatuan($pageNumber,$itemPerPage,$id)
	{
	   //echo "/masuk services getcarisatuan";
	   $namaSatuan = strtoupper($id);
	   $nbrg = $namaSatuan.'%';   
	   $where[]=$nbrg;	
	   //echo "/namaSatuan =".$namaSatuan;	
	   //echo "/nbrg =".$nbrg;	
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try 
	   {  	        
	   	$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_satuan_0_0_tr where c_satuan like ? ",$where);
		 	//$Result = count($hasilAkhir);  
		 	//echo "/Result Service = ".$Result;
		 }
		 else
		 {
	   	 $xLimit=$itemPerPage;
		 $xOffset=($pageNumber-1)*$itemPerPage;		
			 	
		 $result = $db->fetchAll("SELECT c_satuan,e_satuan 
					FROM e_satuan_0_0_tr 
					where c_satuan like ? order by c_satuan
					limit $xLimit offset $xOffset",$where); 
				
         	 $jmlResult = count($result);         	 
         	 //echo "/jml cari Service = ".$jmlResult;
		 if($jmlResult > 0)
		 {
		 for ($j = 0; $j < $jmlResult; $j++) 
		 {	
           		$hasilAkhir[$j] = array("c_satuan"  	=>(string)$result[$j]->c_satuan,
           					  "e_satuan"  	=>(string)$result[$j]->e_satuan);
		 }
        	 }
        	 }		 
	     	 return $hasilAkhir;
	   } 
	   catch (Exception $e) 
	   {
         	echo $e->getMessage().'<br>';
	     	return 'gagal <br>';
	   }
	}
	
	
}	
?>