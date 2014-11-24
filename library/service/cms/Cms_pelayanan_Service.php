<?php
class Cms_pelayanan_Service {
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

	public function getPelayananPubList($cari)
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$xLimit=6;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $strQuery="SELECT c_pelayanan,n_judul,n_detil,d_pelayanan,n_sumber,c_status  from portal.tmpelayanan where c_status=1 $cari order by d_pelayanan desc limit $xLimit offset 0";
         //echo $strQuery;
		 //$result = $db->fetchAll("SELECT c_pelayanan,n_judul,n_detil,d_pelayanan,n_sumber,c_status  from portal.tmpelayanan order by d_pelayanan $cari order by d_pelayanan desc");
         $result = $db->fetchAll("$strQuery");
		 $jmlResult = count($result);
		 for ($i=0; $i<$jmlResult; $i++)
		 {
		   $output[$i] = array("c_pelayanan"=>(string)$result[$i]->c_pelayanan,
		                       "n_judul"=>(string)$result[$i]->n_judul,
		                       "n_detil"=>(string)$result[$i]->n_detil,
		                       "d_pelayanan"=>(string)$result[$i]->d_pelayanan,
		                       "n_sumber"=>(string)$result[$i]->n_sumber,
		                       "c_status"=>(string)$result[$i]->c_status);
		 }
	     return $output;
	   } catch (Exception $e) {
	     return 'gagal'.$e->getMessage();
	   }
	}
 	public function getPelayananList($cari,$currentPage, $numToDisplay,$orderby) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				if(($currentPage==0) && ($numToDisplay==0))
			{
				$data = $db->fetchOne("select count(*) from  portal.tmpelayanan  $cari");
	
			}
			else		
			{	
				$xLimit=$numToDisplay;
				$xOffset=($currentPage-1)*$numToDisplay;				
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				    $strQuery="SELECT c_pelayanan,n_judul,n_detil,d_pelayanan,n_sumber,c_status  from portal.tmpelayanan $cari $orderby limit $xLimit offset $xOffset";
					$result = $db->fetchAll("$strQuery");
									
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
						$data[$j] = array("c_pelayanan"=>(string)$result[$j]->c_pelayanan,
		                       "n_judul"=>(string)$result[$j]->n_judul,
		                       "n_detil"=>(string)$result[$j]->n_detil,
		                       "d_pelayanan"=>(string)$result[$j]->d_pelayanan,
		                       "n_sumber"=>(string)$result[$j]->n_sumber,
		                       "c_status"=>(string)$result[$j]->c_status);	
					}
			}							
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}

    public function findPelayananByKey($id) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         $result = $db->fetchAll("SELECT c_pelayanan,n_judul,n_detil,d_pelayanan,n_sumber,c_status  from portal.tmpelayanan where c_pelayanan='$id' ");
		 
	     $output[] = array("c_pelayanan"=>(string)$result[0]->c_pelayanan,
		                       "n_judul"=>(string)$result[0]->n_judul,
		                       "n_detil"=>(string)$result[0]->n_detil,
		                       "d_pelayanan"=>(string)$result[0]->d_pelayanan,
		                       "n_sumber"=>(string)$result[0]->n_sumber,
		                       "c_status"=>(string)$result[0]->c_status);
	   return $output;
	   } catch (Exception $e) {
	     return 'gagal'.$e->getMessage();
	   }
	}
	
	public function maintainData(array $data,$par) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();

	     $maintain_data = array("n_judul"=>$data['n_judul'],		 
								"n_detil"=>$data['n_detil'],
								"n_sumber"=>$data['n_sumber'],
								"d_pelayanan"=>$data['d_pelayanan'],
								"c_status"=>$data['c_status'],
								"i_entri"=>$data['i_entri'],
								"d_entri"=>$data['d_entri']);
		if ($par=='insert'){$db->insert('portal.tmpelayanan',$maintain_data);}
		if ($par=='update'){$db->update('portal.tmpelayanan',$maintain_data, "c_pelayanan = '".trim($data['c_pelayanan'])."' ");}	 
		if ($par=='delete'){$db->delete('portal.tmpelayanan', "c_pelayanan = '".trim($data['c_pelayanan'])."'  and i_entri = '".trim($data['i_entri'])."' ");}
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
	public function maintainHapusData($idpelayanan,$userlogin) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
			$maintain_data = array("c_stat_aktivasi"=>"D");
		//$db->fetchOne("delete from  portal.tmpelayanan  where c_pelayanan='$idpelayanan' and i_entri='$userlogin'");
		$where[] = " c_pelayanan = '".$idpelayanan."'";
		$db->delete('portal.tmpelayanan',$where);
		$db->commit();
		return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
 	public function getMaxId() 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{		
				
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$data = $db->fetchOne("select count(*) from  portal.tmpelayanan ");
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	
}
?>