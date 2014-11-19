<?php

require_once 'Zend/Json.php';

class ast_melihatbsm_Service 
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
    	
//list pertama pd saat klik menu =====================================================
public function getBrgsewaList($pageNumber, $itemPerPage) 
{
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	
	$tahun = date("Y");
	//echo "tahun =".$tahun;
		
	try 
	{
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		
		if(($pageNumber==0) && ($itemPerPage==0))
		{
		$hasilAkhir = $db->fetchOne("SELECT count(*)
			FROM e_ast_barangsewa_0_tm 
			where d_anggaran=?",$tahun);
		}
		else
		{
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;		
			$result = $db->fetchAll("SELECT d_anggaran,
			c_barang,
			i_barang_sewa,
			i_rekanan,
			n_barang,
			n_barang_merktype,
			d_barang_sewaawal,
			d_barang_sewaakhir,
			d_barang_pengembalian
			FROM e_ast_barangsewa_0_tm 
			where d_anggaran=? limit $xLimit offset $xOffset",$tahun); 
		
			$jmlResult = count($result);	
			//echo "jumlah =".$jmlResult;	 
			
			if($jmlResult > 0)
			{
				for ($j = 0; $j < $jmlResult; $j++) 
				{		 
				$hasilAkhir[$j] = array("d_anggaran"	=>(string)$result[$j]-> d_anggaran,
				               "c_barang"           	=>(string)$result[$j]-> c_barang,
				               "i_barang_sewa" 		=>(string)$result[$j]-> i_barang_sewa,
				               "i_rekanan"     		=>(string)$result[$j]-> i_rekanan,
				               "n_barang"   		=>(string)$result[$j]-> n_barang,
				               "n_barang_merktype" 	=>(string)$result[$j]-> n_barang_merktype,
				               "d_barang_sewaawal"	=>(string)$result[$j]-> d_barang_sewaawal,
				               "d_barang_sewaakhir"	=>(string)$result[$j]-> d_barang_sewaakhir,
				               "d_barang_pengembalian"	=>(string)$result[$j]-> d_barang_pengembalian);			
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