<?php

require_once 'Zend/Json.php';

class ast_barangsewa_Service 
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
    	
//list pertama pd saat klik menu ============transaksi=========================================
	public function getTransaksiBrgsewaList($pageNumber, $itemPerPage) 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
		if(($pageNumber==0) && ($itemPerPage==0))
		{
			$hasilAkhir = $db->fetchOne("SELECT count(*)
											FROM   e_ast_transaksisewa_0_tm  a, e_ast_barangsewa_0_tr b , 
											e_rekanan_prsh_0_tm c
											where a.c_barang_sewa=b.c_barang_sewa and b.i_rekanan=c.i_rekanan											
											and d_pengembalian_sewa is null");
		}
		else
		{
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;		
			$result = $db->fetchAll("SELECT a.c_barang_sewa,
											a.i_barang_sewa,
											b.n_barang,
											a.i_barang_serial,
											b.i_rekanan,
											c.n_prsh,
											d_barang_sewaawal,
											d_barang_sewaakhir,
											a.e_keterangan,
											n_barang_merktype
											FROM   e_ast_transaksisewa_0_tm  a, e_ast_barangsewa_0_tr b , 
											e_rekanan_prsh_0_tm c
											where a.c_barang_sewa=b.c_barang_sewa and b.i_rekanan=c.i_rekanan											
											and d_pengembalian_sewa is null
											order by a.c_barang_sewa,a.i_barang_sewa
										limit $xLimit offset $xOffset"); 
		
			$jmlResult = count($result);	
			//echo "jumlah =".$jmlResult;	 
			
			if($jmlResult > 0)
			{
				for ($j = 0; $j < $jmlResult; $j++) 
				{		 
				$hasilAkhir[$j] = array("c_barang_sewa"          	=>(string)$result[$j]-> c_barang_sewa,
							               "i_barang_sewa"			=>(string)$result[$j]-> i_barang_sewa,
							               "n_barang" 				=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "i_rekanan"   			=>(string)$result[$j]-> i_rekanan,
										   "n_rekanan"   			=>(string)$result[$j]-> n_prsh,
							               "d_barang_sewaawal" 		=>(string)$result[$j]-> d_barang_sewaawal,
										   "d_barang_sewaakhir" 	=>(string)$result[$j]-> d_barang_sewaakhir,
							               "e_keterangan"			=>(string)$result[$j]-> e_keterangan,
										   "n_barang_merktype"		=>(string)$result[$j]-> n_barang_merktype);			
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
	public function getTransaksiBrgsewaListMelihat($pageNumber, $itemPerPage,$unitkr) 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
		if(($pageNumber==0) && ($itemPerPage==0))
		{
			$hasilAkhir = $db->fetchOne("SELECT count(*)
											FROM  e_ast_transaksisewa_0_tm  
											where i_orgb is null or i_orgb = '' ");
		}
		else
		{
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;		
			$result = $db->fetchAll("SELECT a.c_barang_sewa,
											a.i_barang_sewa,
											b.n_barang,
											a.i_barang_serial,
											b.i_rekanan,
											c.n_prsh,
											d_barang_sewaawal,
											d_barang_sewaakhir,
											a.e_keterangan,
											n_barang_merktype
											FROM   e_ast_transaksisewa_0_tm  a, e_ast_barangsewa_0_tr b , 
											e_rekanan_prsh_0_tm c
											where a.c_barang_sewa=b.c_barang_sewa and b.i_rekanan=c.i_rekanan
											and (i_orgb is null or i_orgb = '')
											order by a.c_barang_sewa,a.i_barang_sewa
										limit $xLimit offset $xOffset"); 
		
			$jmlResult = count($result);	
			//echo "jumlah =".$jmlResult;	 
			
			if($jmlResult > 0)
			{
				for ($j = 0; $j < $jmlResult; $j++) 
				{		 
				$hasilAkhir[$j] = array("c_barang_sewa"          	=>(string)$result[$j]-> c_barang_sewa,
							               "i_barang_sewa"			=>(string)$result[$j]-> i_barang_sewa,
							               "n_barang" 				=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "i_rekanan"   			=>(string)$result[$j]-> i_rekanan,
										   "n_rekanan"   			=>(string)$result[$j]-> n_prsh,
							               "d_barang_sewaawal" 		=>(string)$result[$j]-> d_barang_sewaawal,
										   "d_barang_sewaakhir" 	=>(string)$result[$j]-> d_barang_sewaakhir,
							               "e_keterangan"			=>(string)$result[$j]-> e_keterangan,
										   "n_barang_merktype"		=>(string)$result[$j]-> n_barang_merktype);			
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
	public function getCariTransaksiBrgsewaList($pageNumber, $itemPerPage, $namabarang, $tglAwal, $tglAkhir) 
	{
		$namabarang = '%'.strtoupper($namabarang).'%';
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{
			$where[]=$namabarang;	
			$where[]=$tglAwal;
			$where[]=$tglAkhir;	
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("SELECT count(*)
												FROM  e_ast_transaksisewa_0_tm  a, e_ast_barangsewa_0_tr b , 
												e_rekanan_prsh_0_tm c
												where a.c_barang_sewa=b.c_barang_sewa and b.i_rekanan=c.i_rekanan
												and (i_orgb is null or i_orgb = '') and upper(n_barang) like upper(?)
												and d_barang_sewaawal >= ? and d_barang_sewaakhir <= ?",$where);
			}
			else
			{
					$xLimit=$itemPerPage;
					$xOffset=($pageNumber-1)*$itemPerPage;		
					$result = $db->fetchAll("SELECT a.c_barang_sewa,
											a.i_barang_sewa,
											b.n_barang,
											a.i_barang_serial,
											b.i_rekanan,
											c.n_prsh,
											d_barang_sewaawal,
											d_barang_sewaakhir,
											a.e_keterangan,
											n_barang_merktype
											FROM   e_ast_transaksisewa_0_tm  a, e_ast_barangsewa_0_tr b , 
											e_rekanan_prsh_0_tm c
											where a.c_barang_sewa=b.c_barang_sewa and b.i_rekanan=c.i_rekanan
											and (i_orgb is null or i_orgb = '') and upper(n_barang) like upper(?)
											and d_barang_sewaawal >= ? and d_barang_sewaakhir <= ?
											order by a.c_barang_sewa,a.i_barang_sewa
										limit $xLimit offset $xOffset",$where); 
		
			$jmlResult = count($result);	
			//echo "jumlah =".$jmlResult;	 
			
			if($jmlResult > 0)
			{
				for ($j = 0; $j < $jmlResult; $j++) 
				{		 
				$hasilAkhir[$j] = array("c_barang_sewa"          	=>(string)$result[$j]-> c_barang_sewa,
							               "i_barang_sewa"			=>(string)$result[$j]-> i_barang_sewa,
							               "n_barang" 				=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "i_rekanan"   			=>(string)$result[$j]-> i_rekanan,
										   "n_rekanan"   			=>(string)$result[$j]-> n_prsh,
							               "d_barang_sewaawal" 		=>(string)$result[$j]-> d_barang_sewaawal,
										   "d_barang_sewaakhir" 	=>(string)$result[$j]-> d_barang_sewaakhir,
							               "e_keterangan"			=>(string)$result[$j]-> e_keterangan,
										   "n_barang_merktype"		=>(string)$result[$j]-> n_barang_merktype);			
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
public function getlistBrgSewa($pageNumber,$itemPerPage,$namaBarang) 
	{
		//echo "+masuk services getBrgBergerak";
		$namaBarang = strtoupper($namaBarang);
		$nbrg 	= '%'.$namaBarang.'%';
		
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{
		     $where[] =$nbrg;
		 
	         $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("select count(*) FROM e_ast_barangsewa_0_tr 
									    where upper(n_barang) like ? ",$where);
		 }
		 else
		 {
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;					 
			 $result = $db->fetchAll("SELECT c_barang_sewa,n_barang,n_barang_merktype,e_keterangan,i_rekanan
									  FROM e_ast_barangsewa_0_tr
									  where upper(n_barang) like ?
									  order by c_barang_sewa
									  limit $xLimit offset $xOffset",$where); 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) 
			 {
				$hasilAkhir[$j] = array("c_barang_sewa"			=>(string)$result[$j]->c_barang_sewa,
										"n_barang" 				=>(string)$result[$j]->n_barang,
										"n_barang_merktype" 	=>(string)$result[$j]->n_barang_merktype,
										"i_rekanan" 			=>(string)$result[$j]->i_rekanan,
										"e_keterangan" 			=>(string)$result[$j]->e_keterangan);
			 }	
		}	 
	     	return $hasilAkhir;
	   } 
	   catch (Exception $e) 
	   {
         	echo $e->getMessage().'<br>';
	     	return 'Data tidak ada <br>';
	   }
	}
	
	public function createKdBarangSewa() {
	    $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	    try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ);
		 $result = $db->fetchOne('SELECT gen_nosewa()');
		 echo 'hasil kd barang'.$result;
		 return $result;
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return  0;
	   }
 
	} 
	
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
			i_barang_serial,
			d_barang_sewaawal,
			d_barang_sewaakhir,
			e_keterangan,
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
							   "i_barang_serial" 	=>(string)$result[$j]-> i_barang_serial,
				               "d_barang_sewaawal"	=>(string)$result[$j]-> d_barang_sewaawal,
				               "d_barang_sewaakhir"	=>(string)$result[$j]-> d_barang_sewaakhir,
							    "e_keterangan"	=>(string)$result[$j]-> e_keterangan,
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
public function dataBrgSewaList()
{
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');		
	try 
	{
		$query="select kd_gol, kd_bid, kd_kel, kd_skel, kd_sskel , satuan ,ur_sskel".
		" FROM e_ast_sskel_0_tr limit 100 "; 
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $db->fetchAll($query);
		$jmlResult = count($result);
		for ($j = 0; $j < $jmlResult; $j++)
		{
		$hasilAkhir[$j] = array("kd_gol" =>(string)$result[$j]->kd_gol
				,"kd_bid"  =>(string)$result[$j]->kd_bid
				,"kd_kel" =>(string)$result[$j]->kd_kel
				,"kd_skel"  =>(string)$result[$j]->kd_skel
				,"kd_sskel" =>(string)$result[$j]->kd_sskel
				,"satuan"  =>(string)$result[$j]->satuan
				,"ur_sskel" =>(string)$result[$j]->ur_sskel);
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
public function getCekNomorBarangSewa($tahun,$kdbrg) 
{
	//echo "masuk ke getnomorbarangsewa sevices";
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	$result=0;
	try 
	{
		$where[]=$tahun;
		$where[]=$kdbrg;
	   	//echo "+tahun services =".$tahun;
	   	//echo "+kdbrg services =".$kdbrg;	   	
	   	
	   	$db->setFetchMode(Zend_Db::FETCH_OBJ); 
	   	$result = $db->fetchAll('SELECT d_anggaran,c_barang,i_aset_akhir 
				FROM e_ast_nomorbarang_sewa_tr 
				where d_anggaran=? and c_barang=?',$where);
		
         	 $jmlResult = count($result);   
         	 //echo "+jumlah data services =".$jmlResult ;
         	 
         	 if($jmlResult > 0)
		 {
			 for ($j = 0; $j < $jmlResult; $j++) 
			 {			 
			 	//field field yg akan ditampilkan aja di gui 
	           		$hasilAkhir[$j] = array("d_anggaran" 		=>(string)$result[$j]->d_anggaran,
							"c_barang"  		=>(string)$result[$j]->c_barang,
							"i_aset_akhir" 		=>(string)$result[$j]->i_aset_akhir);
			 }
        	 }		 
	     	 
	     	  return $hasilAkhir;
		
	} 
	catch (Exception $e) 
	{
		echo $e->getMessage().'<br>';
		$asetAwal = 1;
		return asetAwal;
	}						
}
//====================================================================================
public function getInsertTransaksiBrgSewa($data) 
{	
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');	
	try 
	{
		$db->beginTransaction();
		$BrgsewaArr = array("c_barang_sewa"			=>$data['c_barang_sewa']
							,"i_barang_sewa" 		=>$data['i_barang_sewa']
							,"i_barang_serial" 	    =>$data['i_barang_serial']
							,"d_barang_sewaawal"	=>$data['d_barang_sewaawal']
							,"d_barang_sewaakhir"	=>$data['d_barang_sewaakhir']
							,"e_keterangan"     	=>$data['e_keterangan']
							,"i_entry"				=>$data['userid']
							,"d_entry"				=>date("Y-m-d"));
									   
		$db->insert('e_ast_transaksisewa_0_tm',$BrgsewaArr);
		$db->commit();
		return 'sukses';
	}
	catch (Exception $e) 
	{
		$db->rollBack();
		echo $e->getMessage().'<br>';
		return 'gagal';
	}
}

public function getUpdateTransaksiBrgSewa($data) 
{
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');	
	try 
	{
		$db->beginTransaction();
		$BrgsewaArr = array("i_barang_serial" 	    =>$data['i_barang_serial']
							,"d_barang_sewaawal"	=>$data['d_barang_sewaawal']
							,"d_barang_sewaakhir"	=>$data['d_barang_sewaakhir']
							,"e_keterangan"     	=>$data['e_keterangan']
							,"i_entry"				=>$data['userid']
							,"d_entry"				=>date("Y-m-d"));
	     
		 //parameter getUpdateBrgsewa 		   
	     $where[] = "c_barang_sewa 	=  '".trim($data['c_barang_sewa'])."'";
		 $where[] = "i_barang_sewa 	=  '".trim($data['i_barang_sewa'])."'";
	     
	     $db->update('e_ast_transaksisewa_0_tm',$BrgsewaArr,$where);
	     $db->commit();
	     return 'sukses';
	   } 
	   catch (Exception $e) 
	   {
         	$db->rollBack();
         	echo $e->getMessage().'<br>';
	     	return 'gagal';
	   }	     
}

public function getInsertBrgSewa2($data) 
{	
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');	
	try 
	{
		$db->beginTransaction();
		$BrgsewaArr = array("d_anggaran"			=>$data['d_anggaran']
							,"c_barang"				=>$data['c_barang']
							,"i_barang_sewa" 		=>$data['i_barang_sewa']
							,"i_rekanan"     		=>$data['i_rekanan']
							,"n_barang"   			=>$data['n_barang']
							,"n_barang_merktype"	=>$data['n_barang_merktype']
							,"i_barang_serial" 	    =>$data['i_barang_serial']
							,"d_barang_sewaawal"	=>$data['d_barang_sewaawal']
							,"d_barang_sewaakhir"	=>$data['d_barang_sewaakhir']
							,"e_keterangan"     	=>$data['e_keterangan']
							,"d_barang_pengembalian"=>null
							,"i_entry"		=>$data['userid']
							,"d_entry"		=>date("Y-m-d"));
									   
		$db->insert('e_ast_barangsewa_0_tm',$BrgsewaArr);
		$db->commit();
		return 'sukses';
	}
	catch (Exception $e) 
	{
		$db->rollBack();
		echo $e->getMessage().'<br>';
		return 'gagal';
	}
}
//====================================================================================
public function getInsertNomorBarangSewa(array $data) 
{
	//echo "+masuk getInsertNomorBarangSewa servicers "; 
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');	
	try 
	{
		$db->beginTransaction();
		$NoBrgsewa = array("d_anggaran"		=>$data['d_anggaran']
				,"c_barang"		=>$data['c_barang']
				,"i_aset_akhir"		=>$data['i_aset_akhir']
				,"i_entry"		=>'ast'
				,"d_entry"		=>date("Y-m-d"));
											   
		$db->insert('e_ast_nomorbarang_sewa_tr',$NoBrgsewa);
		$db->commit();
		return 'sukses';
	}
	catch (Exception $e) 
	{
		$db->rollBack();
		echo $e->getMessage().'<br>';
		return 'gagal';
	}
}
//====================================================================================
public function getUpdateBrgsewa($data) 
{
	//echo "+masuk getUpdateBrgsewa servicers "; 
	//echo "i-barang-sewa =".$data['i_barang_sewa'];
					      
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');	
	try 
	{
		$db->beginTransaction();
		$BrgsewaArr = array("d_anggaran"	=>$data['d_anggaran'],
					"c_barang"		        =>$data['c_barang'],
					"i_rekanan"     	    =>$data['i_rekanan'],
					"n_barang"   		    =>$data['n_barang'],
					"n_barang_merktype"	    =>$data['n_barang_merktype'],
					"i_barang_serial" 	    =>$data['i_barang_serial'],
					"d_barang_sewaawal"	    =>$data['d_barang_sewaawal'],
					"d_barang_sewaakhir"	=>$data['d_barang_sewaakhir'],
					"e_keterangan"	        =>$data['e_keterangan'],
					"i_entry"	            =>$data['userid'],
					"d_entry"		        =>date("Y-m-d"));
	     
	     //parameter getUpdateBrgsewa 		   
	     $where[] = "i_barang_sewa 	=  '".trim($data['i_barang_sewa'])."'";
	     
	     $db->update('e_ast_barangsewa_0_tm',$BrgsewaArr,$where);
	     $db->commit();
	     return 'sukses';
	   } 
	   catch (Exception $e) 
	   {
         	$db->rollBack();
         	echo $e->getMessage().'<br>';
	     	return 'gagal';
	   }	     
}
//====================================================================================
public function getUpdateNomorBarangSewa($data) 
{
	//echo "+masuk getUpdateNomorBarangSewa servicers "; 
        //echo "+i_aset_akhir services =".$data['i_aset_akhir'];				      
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');	
	try 
	{
		$db->beginTransaction();
		$NoBrgsewa = array("d_anggaran"		=>$data['d_anggaran'],
				"c_barang"		=>$data['c_barang'],
				"i_aset_akhir"		=>$data['i_aset_akhir']);
											   
		$db->update('e_ast_nomorbarang_sewa_tr',$NoBrgsewa);
	     	$db->commit();
	    	return 'sukses';
	   } 
	   catch (Exception $e) 
	   {
         	$db->rollBack();
         	echo $e->getMessage().'<br>';
	     	return 'gagal';
	   }
}
//====================================================================================
public function DeleteNomorBarangSewa($kdsewa,$nosewa) 
{
	//echo "+masuk getUpdateNomorBarangSewa servicers "; 
        //echo "+i_aset_akhir services =".$data['i_aset_akhir'];				      
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');	
	try 
	{
		 $db->beginTransaction();
		 $where[] = "trim(c_barang_sewa) = '". trim($kdsewa) ."'";
		 $where[] = "trim(i_barang_sewa) = '". trim($nosewa) ."'";
		 
		 $db->delete('e_ast_transaksisewa_0_tm', $where);
		 $db->commit();
	     return 'sukses';
	   
	   } 
	   catch (Exception $e) 
	   {
         	$db->rollBack();
         	echo $e->getMessage().'<br>';
	     	return 'gagal';
	   }
}
public function DeleteNomorBarangSewa_Old($nosewa) 
{
	//echo "+masuk getUpdateNomorBarangSewa servicers "; 
        //echo "+i_aset_akhir services =".$data['i_aset_akhir'];				      
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');	
	try 
	{
		 $db->beginTransaction();
		 $where[] = "trim(i_barang_sewa) = '". trim($nosewa) ."'";
		 $db->delete('e_ast_barangsewa_0_tm', $where);
		 $db->commit();
	     return 'sukses';
	   
	   } 
	   catch (Exception $e) 
	   {
         	$db->rollBack();
         	echo $e->getMessage().'<br>';
	     	return 'gagal';
	   }
}
//====================================================================================
public function getCariBrgsewaList($namabarang,$pageNumber,$itemPerPage) 
{
	//echo" +masuk getCariBrgsewaList services";
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	
	$namabarang = '%'.strtoupper($namabarang).'%';
	 
	$where[]=$namabarang;		   
		
	try 
	{
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		
		if(($pageNumber==0) && ($itemPerPage==0))
		{
			$hasilAkhir = $db->fetchOne("SELECT count(*)
			FROM e_ast_barangsewa_0_tm
			where upper(n_barang) like upper(?)",$where);
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
					i_barang_serial, 
					d_barang_sewaawal,
					d_barang_sewaakhir,
					e_keterangan,
					d_barang_pengembalian
					FROM e_ast_barangsewa_0_tm 
					where upper(n_barang) like upper(?) limit $xLimit offset $xOffset",$where);
		
			$jmlResult = count($result);	
			//echo "+jumlah cari =".$jmlResult;	 
			
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
							   "i_barang_serial" 	=>(string)$result[$j]-> i_barang_serial,
				               "d_barang_sewaawal"	=>(string)$result[$j]-> d_barang_sewaawal,
				               "d_barang_sewaakhir"	=>(string)$result[$j]-> d_barang_sewaakhir,
							   "e_keterangan"   	=>(string)$result[$j]-> e_keterangan,
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
//====================================================================================
// untuk menggenarate call gen_nomorbarang di barangsewainsert.phtml

public function queryNourutmax($kode) 
{   
   $registry = Zend_Registry::getInstance();
   $db = $registry->get('db');
   try 
   {
     	$db->setFetchMode(Zend_Db::FETCH_OBJ);
     	
	$where[] = $kode;
	
	$result = $db->fetchOne('SELECT gen_nobarangsewa(?)',$where);		
     	return $result;
   } 
   catch (Exception $e) 
   {
 	$db->rollBack();
 	echo $e->getMessage().'<br>';
     	return  0;
   }
}
public function queryNourutmax2($kode) 
{   
   //echo "masuk queryNourutmax";	
   //echo "kode = ".$kode;	
   $registry = Zend_Registry::getInstance();
   $db = $registry->get('db');
   try 
   {
     	$db->setFetchMode(Zend_Db::FETCH_OBJ);
     	
	$where[] = $kode;
	
	$result = $db->fetchOne('SELECT gen_nomorbarang(?)',$where);		
     	return $result;
   } 
   catch (Exception $e) 
   {
 	$db->rollBack();
 	echo $e->getMessage().'<br>';
     	return  0;
   }
}  
public function getjabatan($unitkr) {
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try { 
           $where[] = $unitkr;
		   $where[] = $unitkr;
		   $db->setFetchMode(Zend_Db::FETCH_OBJ);
           $hasilAkhir = $db->fetchOne("select count(*)  
										from e_sdm_jabatan_0_tm A,  e_sdm_pegawai_0_tm B
										where a.i_peg_nip = b.i_peg_nip
										and (b.c_unit_kerja = ? or b.i_orgb = ?)
										and b.c_unit_kerja = a.c_jabatan",$where);  
       		
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	} 		
 public function getTU($unitkr) {
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
         $TU 	= $db->fetchCol("SELECT i_orgb_tu FROM e_org_0_0_tm where i_orgb = ? and i_orgb_tu is not null",$unitkr); 
        return $TU;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	} 
}	
?>