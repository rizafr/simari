<?php

require_once 'Zend/Json.php';

class ast_daftarbrgsewa_Service 
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
public function getCariBrg($pageNumber, $itemPerPage,$noRekan ) 
{
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	
	 
		
	try 
	{
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
		if(($pageNumber==0) && ($itemPerPage==0))
		{
		$hasilAkhir = $db->fetchOne("SELECT count(*)
									 from e_ast_barangsewa_0_tr A,
									 e_ast_transaksisewa_0_tm B
									 where B.c_barang_Sewa = A.c_barang_sewa
									 and A.i_rekanan = ?",$noRekan );
		}
		else
		{
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;		
			$result = $db->fetchAll("select A.c_barang_sewa, B.i_barang_sewa, A.n_barang,
									 B.i_barang_serial, A.n_barang_merktype
									 from e_ast_barangsewa_0_tr A,
									 e_ast_transaksisewa_0_tm B
									 where B.c_barang_Sewa = A.c_barang_sewa
									 and A.i_rekanan = ?
									 order by A.c_barang_sewa, B.i_barang_sewa
									 limit $xLimit offset $xOffset",$noRekan ); 
		
			$jmlResult = count($result);	
			//echo "jumlah =".$jmlResult;	 
			
			if($jmlResult > 0)
			{
				for ($j = 0; $j < $jmlResult; $j++) 
				 
				$hasilAkhir[$j] = array("c_barang_sewa"	=>(string)$result[$j]-> c_barang_sewa,
				                "i_barang_sewa"         =>(string)$result[$j]->i_barang_sewa,
				                "i_barang_serial" 		=>(string)$result[$j]-> i_barang_serial,
				                "n_barang"   		    =>(string)$result[$j]-> n_barang,
				                "n_barang_merktype" 	=>(string)$result[$j]-> n_barang_merktype);			
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
//====================================================================================
public function getRekananList()
{		
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');		
	try 
	{
		$query=("SELECT Rek.i_rekanan,n_prsh,a_prsh_jalan,a_prsh_kota ".
		",i_prsh_telpon,i_prsh_fax ".
		" FROM e_rekanan_prsh_0_tm Rek,e_rekanan_almt_prsh_tm Alm ".
		" where Rek.i_rekanan=Alm.i_rekanan ");
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $db->fetchAll($query);
		$jmlResult = count($result);
		for ($j = 0; $j < $jmlResult; $j++)
		{
		$hasilAkhir[$j] = array("i_rekanan" =>(string)$result[$j]->i_rekanan
				,"n_prsh"  =>(string)$result[$j]->n_prsh
				,"a_prsh_jalan" =>(string)$result[$j]->a_prsh_jalan
				,"a_prsh_kota" =>(string)$result[$j]->a_prsh_kota
				,"i_prsh_telpon" =>(string)$result[$j]->i_prsh_telpon
				,"i_prsh_fax" =>(string)$result[$j]->i_prsh_fax);
		}					 
		return $hasilAkhir;
	} 
	catch (Exception $e)
	{
		echo $e->getMessage().'<br>';
		return 'gagal <br>';
	}
}
//====================================================================================
public function getLaporanbyperiode($tglAwal,$tglAkhir) 
{
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');		
	try 
	{
	    $where[]=$tglAwal;
		$where[]=$tglAkhir;
		 
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $db->fetchAll("select  A.c_barang_sewa, B.i_barang_sewa, A.n_barang,
						B.i_barang_serial, A.n_barang_merktype, C.n_prsh,
						B.d_barang_sewaawal, B.d_barang_sewaakhir, B.i_orgb,
						B.d_barang_pengembalian, B.d_pengembalian_sewa,A.i_rekanan
						from e_ast_barangsewa_0_tr A,
						e_ast_transaksisewa_0_tm B,
						e_rekanan_prsh_0_tm C
						where B.c_barang_Sewa = A.c_barang_sewa
								and A.i_rekanan = C.i_rekanan
								and to_char(B.d_barang_sewaawal,'yyyy-mm-dd') >= ?
								and to_char(B.d_barang_sewaakhir,'yyyy-mm-dd') <= ?
								order by A.c_barang_sewa, B.i_barang_sewa",$where);
		$jmlResult = count($result);
		for ($j = 0; $j < $jmlResult; $j++)
		{
		 
		$hasilAkhir[$j] = array("c_barang_sewa" =>(string)$result[$j]->c_barang_sewa
				,"i_barang_sewa"  =>(string)$result[$j]->i_barang_sewa
				,"n_barang" =>(string)$result[$j]->n_barang
				,"i_barang_serial"  =>(string)$result[$j]->i_barang_serial
				,"n_barang_merktype" =>(string)$result[$j]->n_barang_merktype
				,"n_prsh"  =>(string)$result[$j]->n_prsh
				,"d_barang_sewaawal" =>(string)$result[$j]->d_barang_sewaawal
				,"d_barang_sewaakhir" =>(string)$result[$j]->d_barang_sewaakhir
				,"i_orgb" =>(string)$result[$j]->i_orgb
				,"d_barang_pengembalian" =>(string)$result[$j]->d_barang_pengembalian
				,"d_pengembalian_sewa" =>(string)$result[$j]->d_pengembalian_sewa
				,"i_rekanan" =>(string)$result[$j]->i_rekanan
				);
		}					 
		return $hasilAkhir;
	} 
	catch (Exception $e)
	{
		echo $e->getMessage().'<br>';
		return 'gagal <br>';
	}
}
public function getLaporanbyperiodeBr($tglAwal,$tglAkhir,$nBarang) 
{
    $namaBarang = strtoupper($nBarang);
	$nbrg = '%'.$namaBarang.'%';
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');	
    
	try 
	{
	    $where[]=$tglAwal;
		$where[]=$tglAkhir;
		$where[]=$nbrg;
		 
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $db->fetchAll("select  A.c_barang_sewa, B.i_barang_sewa, A.n_barang,
						B.i_barang_serial, A.n_barang_merktype, C.n_prsh,
						B.d_barang_sewaawal, B.d_barang_sewaakhir, B.i_orgb,
						B.d_barang_pengembalian, B.d_pengembalian_sewa,a.i_rekanan
						from e_ast_barangsewa_0_tr A,
						e_ast_transaksisewa_0_tm B,
						e_rekanan_prsh_0_tm C
						where B.c_barang_Sewa = A.c_barang_sewa
								and A.i_rekanan = C.i_rekanan
								and to_char(B.d_barang_sewaawal,'yyyy-mm-dd') >= ?
								and to_char(B.d_barang_sewaakhir,'yyyy-mm-dd') <= ?
								and upper(A.n_barang) like ?
								order by A.c_barang_sewa, B.i_barang_sewa",$where);
		$jmlResult = count($result);
		for ($j = 0; $j < $jmlResult; $j++)
		{
		 
		$hasilAkhir[$j] = array("c_barang_sewa" =>(string)$result[$j]->c_barang_sewa
				,"i_barang_sewa"  =>(string)$result[$j]->i_barang_sewa
				,"n_barang" =>(string)$result[$j]->n_barang
				,"i_barang_serial"  =>(string)$result[$j]->i_barang_serial
				,"n_barang_merktype" =>(string)$result[$j]->n_barang_merktype
				,"n_prsh"  =>(string)$result[$j]->n_prsh
				,"d_barang_sewaawal" =>(string)$result[$j]->d_barang_sewaawal
				,"d_barang_sewaakhir" =>(string)$result[$j]->d_barang_sewaakhir
				,"i_orgb" =>(string)$result[$j]->i_orgb
				,"d_barang_pengembalian" =>(string)$result[$j]->d_barang_pengembalian
				,"d_pengembalian_sewa" =>(string)$result[$j]->d_pengembalian_sewa
				,"i_rekanan" =>(string)$result[$j]->i_rekanan
				);
		}					 
		return $hasilAkhir;
	} 
	catch (Exception $e)
	{
		echo $e->getMessage().'<br>';
		return 'gagal <br>';
	}
}
public function getLaporanbyBarang($nBarang) 
{
    $namaBarang = strtoupper($nBarang);
	$nbrg = '%'.$namaBarang.'%';
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');		
	try 
	{
	   
		$where[]=$nbrg;
		 
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $db->fetchAll("select  A.c_barang_sewa, B.i_barang_sewa, A.n_barang,
						B.i_barang_serial, A.n_barang_merktype, C.n_prsh,
						B.d_barang_sewaawal, B.d_barang_sewaakhir, B.i_orgb,
						B.d_barang_pengembalian, B.d_pengembalian_sewa,a.i_rekanan
						from e_ast_barangsewa_0_tr A,
						e_ast_transaksisewa_0_tm B,
						e_rekanan_prsh_0_tm C
						where B.c_barang_Sewa = A.c_barang_sewa
								and A.i_rekanan = C.i_rekanan
								and upper(A.n_barang) like ?
								order by A.c_barang_sewa, B.i_barang_sewa",$where);
		$jmlResult = count($result);
		for ($j = 0; $j < $jmlResult; $j++)
		{
		 
		$hasilAkhir[$j] = array("c_barang_sewa" =>(string)$result[$j]->c_barang_sewa
				,"i_barang_sewa"  =>(string)$result[$j]->i_barang_sewa
				,"n_barang" =>(string)$result[$j]->n_barang
				,"i_barang_serial"  =>(string)$result[$j]->i_barang_serial
				,"n_barang_merktype" =>(string)$result[$j]->n_barang_merktype
				,"n_prsh"  =>(string)$result[$j]->n_prsh
				,"d_barang_sewaawal" =>(string)$result[$j]->d_barang_sewaawal
				,"d_barang_sewaakhir" =>(string)$result[$j]->d_barang_sewaakhir
				,"i_orgb" =>(string)$result[$j]->i_orgb
				,"d_barang_pengembalian" =>(string)$result[$j]->d_barang_pengembalian
				,"d_pengembalian_sewa" =>(string)$result[$j]->d_pengembalian_sewa
				,"i_rekanan" =>(string)$result[$j]->i_rekanan
				);
		}					 
		return $hasilAkhir;
	} 
	catch (Exception $e)
	{
		echo $e->getMessage().'<br>';
		return 'gagal <br>';
	}
}
public function getLaporanbyBarangAll() 
{
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');		
	try 
	{
	   
		$where[]=$nbrg;
		 
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $db->fetchAll("select  A.c_barang_sewa, B.i_barang_sewa, A.n_barang,
						B.i_barang_serial, A.n_barang_merktype, C.n_prsh,
						B.d_barang_sewaawal, B.d_barang_sewaakhir, B.i_orgb,
						B.d_barang_pengembalian, B.d_pengembalian_sewa,a.i_rekanan
						from e_ast_barangsewa_0_tr A,
						e_ast_transaksisewa_0_tm B,
						e_rekanan_prsh_0_tm C
						where B.c_barang_Sewa = A.c_barang_sewa
								and A.i_rekanan = C.i_rekanan
								order by A.c_barang_sewa, B.i_barang_sewa");
		$jmlResult = count($result);
		for ($j = 0; $j < $jmlResult; $j++)
		{
		 
		$hasilAkhir[$j] = array("c_barang_sewa" =>(string)$result[$j]->c_barang_sewa
				,"i_barang_sewa"  =>(string)$result[$j]->i_barang_sewa
				,"n_barang" =>(string)$result[$j]->n_barang
				,"i_barang_serial"  =>(string)$result[$j]->i_barang_serial
				,"n_barang_merktype" =>(string)$result[$j]->n_barang_merktype
				,"n_prsh"  =>(string)$result[$j]->n_prsh
				,"d_barang_sewaawal" =>(string)$result[$j]->d_barang_sewaawal
				,"d_barang_sewaakhir" =>(string)$result[$j]->d_barang_sewaakhir
				,"i_orgb" =>(string)$result[$j]->i_orgb
				,"d_barang_pengembalian" =>(string)$result[$j]->d_barang_pengembalian
				,"d_pengembalian_sewa" =>(string)$result[$j]->d_pengembalian_sewa
				,"i_rekanan" =>(string)$result[$j]->i_rekanan
				);
		}					 
		return $hasilAkhir;
	} 
	catch (Exception $e)
	{
		echo $e->getMessage().'<br>';
		return 'gagal <br>';
	}
}
  public function getLaporanbylain() 
   {
   
    /* $namaBarang = strtoupper($nBarang);
	$nbrg = '%'.$namaBarang.'%'; */
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');		
	try 
	{
	   
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $db->fetchAll("select  A.c_barang_sewa, B.i_barang_sewa, A.n_barang,
						B.i_barang_serial, A.n_barang_merktype, C.n_prsh,
						B.d_barang_sewaawal, B.d_barang_sewaakhir, B.i_orgb,
						B.d_barang_pengembalian, B.d_pengembalian_sewa,a.i_rekanan
						from e_ast_barangsewa_0_tr A,
						e_ast_transaksisewa_0_tm B,
						e_rekanan_prsh_0_tm C
						where B.c_barang_Sewa = A.c_barang_sewa
						and A.i_rekanan = C.i_rekanan
						and B.d_pengembalian_sewa is null
						and to_char(B.d_barang_sewaakhir,'yyyy-mm-dd') <=to_char(now(),'yyyy-mm-dd')
						order by A.c_barang_sewa, B.i_barang_sewa");
						
		
		$jmlResult = count($result);
		for ($j = 0; $j < $jmlResult; $j++)
		{
		 
		$hasilAkhir[$j] = array("c_barang_sewa" =>(string)$result[$j]->c_barang_sewa
				,"i_barang_sewa"  =>(string)$result[$j]->i_barang_sewa
				,"n_barang" =>(string)$result[$j]->n_barang
				,"i_barang_serial"  =>(string)$result[$j]->i_barang_serial
				,"n_barang_merktype" =>(string)$result[$j]->n_barang_merktype
				,"n_prsh"  =>(string)$result[$j]->n_prsh
				,"d_barang_sewaawal" =>(string)$result[$j]->d_barang_sewaawal
				,"d_barang_sewaakhir" =>(string)$result[$j]->d_barang_sewaakhir
				,"i_orgb" =>(string)$result[$j]->i_orgb
				,"d_barang_pengembalian" =>(string)$result[$j]->d_barang_pengembalian
				,"d_pengembalian_sewa" =>(string)$result[$j]->d_pengembalian_sewa
				,"i_rekanan" =>(string)$result[$j]->i_rekanan
				);
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