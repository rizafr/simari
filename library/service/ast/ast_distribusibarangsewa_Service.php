<?php

require_once 'Zend/Json.php';

class ast_distribusibarangsewa_Service 
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
public function getBrgsewaList($pageNumber,$itemPerPage)  
{
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	
	try 
	{
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		
		if(($pageNumber==0) && ($itemPerPage==0))
		{
			$hasilAkhir = $db->fetchOne("SELECT count(*)
										FROM e_ast_barangsewa_0_tr A,
										e_ast_transaksisewa_0_tm B,
										e_rekanan_prsh_0_tm C
										where B.c_barang_Sewa = A.c_barang_sewa
										and A.i_rekanan = C.i_rekanan
										and B.d_barang_serahsewa is null										
										and B.d_pengembalian_sewa is null"); 
		}
		else
		{
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;	
			$result = $db->fetchAll("Select A.c_barang_sewa, B.i_barang_sewa, A.n_barang,
										B.i_barang_serial, A.n_barang_merktype, C.n_prsh,
										B.d_barang_sewaawal, B.d_barang_sewaakhir,i_orgb
										from e_ast_barangsewa_0_tr A,
										e_ast_transaksisewa_0_tm B,
										e_rekanan_prsh_0_tm C
										where B.c_barang_Sewa = A.c_barang_sewa
										and A.i_rekanan = C.i_rekanan
										and B.d_barang_serahsewa is  null
										and B.d_pengembalian_sewa is null
										order by A.c_barang_sewa, B.i_barang_sewa
										limit $xLimit offset $xOffset"); 
		
			$jmlResult = count($result);	
			//echo "jumlah di services =".$jmlResult; 
			if($jmlResult > 0)
			{
				for ($j = 0; $j < $jmlResult; $j++) 
				{		 
				$hasilAkhir[$j] = array("c_barang_sewa"           	=>(string)$result[$j]-> c_barang_sewa,
							               "i_barang_sewa" 			=>(string)$result[$j]-> i_barang_sewa,				               
							               "n_barang"   			=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "n_barang_merktype" 		=>(string)$result[$j]-> n_barang_merktype,
										   "n_prsh"   				=>(string)$result[$j]-> n_prsh,
							               "d_barang_sewaawal"		=>(string)$result[$j]-> d_barang_sewaawal,
							               "d_barang_sewaakhir"		=>(string)$result[$j]-> d_barang_sewaakhir,
										   "i_orgb"					=>(string)$result[$j]-> i_orgb);			
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

public function getBrgsewaList_Old($pageNumber,$itemPerPage)  
{
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
		
	try 
	{
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		
		if(($pageNumber==0) && ($itemPerPage==0))
		{
			$hasilAkhir = $db->fetchOne("SELECT count(*)
			FROM e_ast_barangsewa_0_tm
			where i_orgb isNull"); 
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
				d_barang_pengembalian,
				i_orgb
				FROM e_ast_barangsewa_0_tm 
				where i_orgb isNull limit $xLimit offset $xOffset"); 
		
			$jmlResult = count($result);	
			//echo "jumlah di services =".$jmlResult; 
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
				               "d_barang_pengembalian"	=>(string)$result[$j]-> d_barang_pengembalian,
				               "i_orgb"			=>(string)$result[$j]-> i_orgb);			
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
public function getCariBrgsewaList($namabarang,$pageNumber,$itemPerPage) 
{
	//echo" +masuk getCariBrgsewaList services";
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	
	$namabarang = strtoupper($namabarang).'%';
	//echo "+namabarang services =".$namabarang;
	$where[]=$namabarang;		   
		
	try 
	{
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		
		if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
										FROM e_ast_barangsewa_0_tr A,
										e_ast_transaksisewa_0_tm B,
										e_rekanan_prsh_0_tm C
										where B.c_barang_Sewa = A.c_barang_sewa
										and A.i_rekanan = C.i_rekanan
										and B.d_barang_serahsewa is  null
										and upper(n_barang) like upper(?)",$where);
		 }
		 else
		 {
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;		
			$result = $db->fetchAll("Select A.c_barang_sewa, B.i_barang_sewa, A.n_barang,
										B.i_barang_serial, A.n_barang_merktype, C.n_prsh,
										B.d_barang_sewaawal, B.d_barang_sewaakhir,i_orgb
										from e_ast_barangsewa_0_tr A,
										e_ast_transaksisewa_0_tm B,
										e_rekanan_prsh_0_tm C
										where B.c_barang_Sewa = A.c_barang_sewa
										and A.i_rekanan = C.i_rekanan
										and B.d_barang_serahsewa is  null
										and upper(n_barang) like upper(?)
										order by A.c_barang_sewa, B.i_barang_sewa
										limit $xLimit offset $xOffset",$where);
		
			$jmlResult = count($result);	
			//echo "+jumlah cari =".$jmlResult;	 
			
			if($jmlResult > 0)
			{
				for ($j = 0; $j < $jmlResult; $j++) 
				{		 
				$hasilAkhir[$j] = array("c_barang_sewa"           	=>(string)$result[$j]-> c_barang_sewa,
							               "i_barang_sewa" 			=>(string)$result[$j]-> i_barang_sewa,				               
							               "n_barang"   			=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "n_barang_merktype" 		=>(string)$result[$j]-> n_barang_merktype,
										   "n_prsh"   				=>(string)$result[$j]-> n_prsh,
							               "d_barang_sewaawal"		=>(string)$result[$j]-> d_barang_sewaawal,
							               "d_barang_sewaakhir"		=>(string)$result[$j]-> d_barang_sewaakhir,
										   "i_orgb"					=>(string)$result[$j]-> i_orgb);				
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
public function getCariBrgsewaList_Old($namabarang,$pageNumber,$itemPerPage) 
{
	//echo" +masuk getCariBrgsewaList services";
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	
	$namabarang = strtoupper($namabarang).'%';
	//echo "+namabarang services =".$namabarang;
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
				d_barang_sewaawal,
				d_barang_sewaakhir,
				d_barang_pengembalian,
				i_orgb
				FROM e_ast_barangsewa_0_tm 
				where upper(n_barang) like upper(?)",$where);
		
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

//====================================================================================
public function getUpdateBrgsewa(array $data) 
{
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');	
	try 
	{
		$db->beginTransaction();
		$BrgsewaArr = array("d_barang_serahsewa"		=>date("Y-m-d"),
							"i_ruang"		=>$data['i_ruang'],
							"i_orgb"		=>$data['i_orgb'],
							"d_barang_pengembalian"   => null,
							"d_pengembalian_sewa"     => null);
		
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
public function getUpdateBrgsewa_Old(array $data) 
{
	//echo "+masuk getUpdateBrgsewa servicers "; 
	//echo "+d_anggaran = ".$data['d_anggaran'];
   	//echo "+c_barang =".$data['c_barang'];
   	//echo "+i_aset =".$data['i_aset'];
   	//echo "+i_orgb =".$data['i_orgb'];
	   
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');	
	try 
	{
		$db->beginTransaction();
		$BrgsewaArr = array("d_anggaran"		=>$data['d_anggaran'],
					"c_barang"		=>$data['c_barang'],
					"i_aset" 		=>$data['i_aset'],
					"i_orgb"		=>$data['i_orgb']);
		
		//parameter getUpdateBrgsewa 		   
		$where[] = "d_anggaran 	=  '".trim($data['d_anggaran'])."'";
		$where[] = "c_barang 	=  '".trim($data['c_barang'])."'";
		$where[] = "i_aset 	=  '".trim($data['i_aset'])."'";
		
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
//=====================  Tambahan JOY 030308 ==============================
	public function getRefBrgsewaList($pageNumber,$itemPerPage) 
	{
		//echo" +masuk getCariBrgsewaList services";
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
			
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
			if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$hasilAkhir = $db->fetchOne("SELECT count(*) from e_ast_barangsewa_0_tr A, 
											e_rekanan_prsh_0_tm B 
											where a.i_rekanan = b.i_rekanan ");
			 }
			 else
			 {
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				$result = $db->fetchAll("select a.c_barang_sewa, a.n_barang, a.n_barang_merktype,
										a.e_keterangan,a.i_rekanan, b.n_prsh 
										from e_ast_barangsewa_0_tr A, e_rekanan_prsh_0_tm B 
										where a.i_rekanan = b.i_rekanan  
										limit $xLimit offset $xOffset");
			
				$jmlResult = count($result);	
				//echo "+jumlah cari =".$jmlResult;	 
				
				if($jmlResult > 0)
				{
					for ($j = 0; $j < $jmlResult; $j++) 
					{		 
					$hasilAkhir[$j] = array("c_barang_sewa"	=>(string)$result[$j]-> c_barang_sewa,
					               "n_barang"           	=>(string)$result[$j]-> n_barang,
								   "n_prsh"           	=>(string)$result[$j]-> n_prsh,
					               "i_rekanan"     		=>(string)$result[$j]-> i_rekanan,
					               "n_barang_merktype"  =>(string)$result[$j]-> n_barang_merktype,
					               "e_keterangan"		=>(string)$result[$j]-> e_keterangan,
					               "q_modul_nomormax"	=>(string)$result[$j]-> q_modul_nomormax);			
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

	public function getRekananList($pageNumber,$itemPerPage) 
	{
		//echo" +masuk getCariBrgsewaList services";
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
			
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
			if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$hasilAkhir = $db->fetchOne("SELECT count(*) from e_rekanan_prsh_0_tm A, e_rekanan_almt_prsh_tm B
										where a.i_rekanan = b.i_rekanan
										and a.c_prsh_levelktr = b.c_prsh_levelktr");
			 }
			 else
			 {
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				$result = $db->fetchAll("select a.i_rekanan, a.n_prsh, b.a_prsh_jalan, 
										b.a_prsh_kota, b.i_prsh_telpon, b.i_prsh_fax
										from e_rekanan_prsh_0_tm A, e_rekanan_almt_prsh_tm B
										where a.i_rekanan = b.i_rekanan
										and a.c_prsh_levelktr = b.c_prsh_levelktr
										limit $xLimit offset $xOffset");
			
				$jmlResult = count($result);	
				//echo "+jumlah cari =".$jmlResult;	 
				
				if($jmlResult > 0)
				{
					for ($j = 0; $j < $jmlResult; $j++) 
					{		 
					$hasilAkhir[$j] = array("i_rekanan"	=>(string)$result[$j]-> i_rekanan,
					               "n_prsh"           	=>(string)$result[$j]-> n_prsh,
					               "a_prsh_jalan" 		=>(string)$result[$j]-> a_prsh_jalan,				              
					               "a_prsh_kota"     		=>(string)$result[$j]-> a_prsh_kota,
					               "i_prsh_telpon"   		=>(string)$result[$j]-> i_prsh_telpon,
					               "i_prsh_fax"	=>(string)$result[$j]-> i_prsh_fax);			
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
	
	public function createKdBrgSewaMasuk() {
	    $registry = Zend_Registry::getInstance();
		$where[] = 'BSM';
		$db = $registry->get('db');
	    try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$result = $db->fetchOne('SELECT gen_nomorbarang(?)',$where);
			return $result;
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return  0;
		}
	}

	public function insertReferensiBarangSewaMasuk(array $data)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		//$where = $data['noPengajuan'];
		try {
			$db->beginTransaction();
			
				$prmInsert = array("c_barang_sewa"		=>$data['c_barang_sewa'],
									"n_barang"			=>$data['n_barang'],
									"n_barang_merktype"	=>$data['n_barang_merktype'],
									"e_keterangan"		=>$data['e_keterangan'],
									"q_modul_nomormax"	=>$data['q_modul_nomormax'],
									"i_rekanan"		=>$data['i_rekanan'],
									"i_entry"		=>$data['i_entry'],
									"d_entry"		=>$data['d_entry']);
				
			$db->insert('e_ast_barangsewa_0_tr',$prmInsert);
			$db->commit();
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			echo $e->getMessage().'<br>';
			return 'gagal';
	   }
	}
	
	public function getDataRekanan($kdrek) 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
			
		try 
		{
			$where[] = $kdrek;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$result = $db->fetchAll("select a.i_rekanan, a.n_prsh, b.a_prsh_jalan, 
									b.a_prsh_kota, b.i_prsh_telpon, b.i_prsh_fax
									from e_rekanan_prsh_0_tm A, e_rekanan_almt_prsh_tm B
									where a.i_rekanan = b.i_rekanan
									and a.c_prsh_levelktr = b.c_prsh_levelktr
									and a.i_rekanan = ?
									",$where);
			
			$jmlResult = count($result);	
			//echo "+jumlah cari =".$jmlResult;	 
			
			if($jmlResult > 0)
			{
				for ($j = 0; $j < $jmlResult; $j++) 
				{		 
				$hasilAkhir[$j] = array("i_rekanan"	=>(string)$result[$j]-> i_rekanan,
							   "n_prsh"           	=>(string)$result[$j]-> n_prsh,
							   "a_prsh_jalan" 		=>(string)$result[$j]-> a_prsh_jalan,				              
							   "a_prsh_kota"     		=>(string)$result[$j]-> a_prsh_kota,
							   "i_prsh_telpon"   		=>(string)$result[$j]-> i_prsh_telpon,
							   "i_prsh_fax"	=>(string)$result[$j]-> i_prsh_fax);			
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
	
	public function updateReferensiBarangSewaMasuk(array $data) 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');	
		try 
		{
			$db->beginTransaction();
			$BrgsewaArr = array("n_barang"		=>$data['n_barang'],
								"n_barang_merktype"		=>$data['n_barang_merktype'],
								"e_keterangan" 		=>$data['e_keterangan'],
								"i_rekanan"		=>$data['i_rekanan']);
			
			//parameter getUpdateBrgsewa 		   
			$where[] = "c_barang_sewa 	=  '".trim($data['c_barang_sewa'])."'";
			
			$db->update('e_ast_barangsewa_0_tr',$BrgsewaArr,$where);
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
	
	public function getDafTransaksiSewaMasukList($pageNumber, $itemPerPage) 
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
	public function getDafTransaksiSewaMasukListMelihat($pageNumber, $itemPerPage) 
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
	
	
	public function getAllBrgsewaListMelihat($pageNumber, $itemPerPage,$unitkr) 
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
											where a.c_barang_sewa=b.c_barang_sewa 
											and b.i_rekanan=c.i_rekanan											
											and d_pengembalian_sewa is null
											"); 
										 
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
											where a.c_barang_sewa=b.c_barang_sewa 
											and b.i_rekanan=c.i_rekanan											
											and d_pengembalian_sewa is null
											order by a.c_barang_sewa,a.i_barang_sewa
										   limit $xLimit offset $xOffset"); 
		
				$jmlResult = count($result);			
			
				if($jmlResult > 0)
				{
				for ($j = 0; $j < $jmlResult; $j++) 
					{		 
						$hasilAkhir[$j] = array("c_barang_sewa"          	=>(string)$result[$j]-> c_barang_sewa,
							               "i_barang_sewa"			=>(string)$result[$j]-> i_barang_sewa,
							               "n_barang" 				=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "i_rekanan"   			=>(string)$result[$j]-> i_rekanan,
										   "n_prsh"   			=>(string)$result[$j]-> n_prsh,
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
											FROM   e_ast_transaksisewa_0_tm  a, e_ast_barangsewa_0_tr b , 
											e_rekanan_prsh_0_tm c
											where a.c_barang_sewa=b.c_barang_sewa 
											and b.i_rekanan=c.i_rekanan
											and (i_orgb is null or i_orgb = '')
											and d_pengembalian_sewa is null
											"); 
										 
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
											where a.c_barang_sewa=b.c_barang_sewa 
											and b.i_rekanan=c.i_rekanan
											and (i_orgb is null or i_orgb = '')
											and d_pengembalian_sewa is null
											order by a.c_barang_sewa,a.i_barang_sewa
										   limit $xLimit offset $xOffset"); 
		
				$jmlResult = count($result);			
			
				if($jmlResult > 0)
				{
				for ($j = 0; $j < $jmlResult; $j++) 
					{		 
						$hasilAkhir[$j] = array("c_barang_sewa"          	=>(string)$result[$j]-> c_barang_sewa,
							               "i_barang_sewa"			=>(string)$result[$j]-> i_barang_sewa,
							               "n_barang" 				=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "i_rekanan"   			=>(string)$result[$j]-> i_rekanan,
										   "n_prsh"   			=>(string)$result[$j]-> n_prsh,
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
	
	public function getTransaksiBrgsewaListMelihat2($pageNumber, $itemPerPage,$unitkr) 
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
											where a.c_barang_sewa=b.c_barang_sewa 
											and b.i_rekanan=c.i_rekanan
											and  i_orgb is not null
											and d_barang_pengembalian is null"); 
										 
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
											n_barang_merktype,
											i_orgb
											FROM   e_ast_transaksisewa_0_tm  a, e_ast_barangsewa_0_tr b , 
											e_rekanan_prsh_0_tm c
											where a.c_barang_sewa=b.c_barang_sewa 
											and b.i_rekanan=c.i_rekanan
											and i_orgb is not null
											and d_barang_pengembalian is null
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
										   "n_prsh"   			=>(string)$result[$j]-> n_prsh,
							               "d_barang_sewaawal" 		=>(string)$result[$j]-> d_barang_sewaawal,
										   "d_barang_sewaakhir" 	=>(string)$result[$j]-> d_barang_sewaakhir,
							               "e_keterangan"			=>(string)$result[$j]-> e_keterangan,
										   "n_barang_merktype"		=>(string)$result[$j]-> n_barang_merktype,
                                            "i_orgb"		         =>(string)$result[$j]-> i_orgb);										   
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
	
	
	
	
	public function getTransaksiBrgsewaListMelihat3($pageNumber, $itemPerPage,$unitkr) 
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
											where a.c_barang_sewa=b.c_barang_sewa 
											and b.i_rekanan=c.i_rekanan
											and d_barang_pengembalian is not null 
											and  i_orgb is null"); 
										 
										 
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
											n_barang_merktype,
											i_orgb
											FROM   e_ast_transaksisewa_0_tm  a, e_ast_barangsewa_0_tr b , 
											e_rekanan_prsh_0_tm c
											where a.c_barang_sewa=b.c_barang_sewa 
											and b.i_rekanan=c.i_rekanan
											and d_barang_pengembalian is not null 
											and i_orgb is null
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
										   "n_prsh"   			=>(string)$result[$j]-> n_prsh,
							               "d_barang_sewaawal" 		=>(string)$result[$j]-> d_barang_sewaawal,
										   "d_barang_sewaakhir" 	=>(string)$result[$j]-> d_barang_sewaakhir,
							               "e_keterangan"			=>(string)$result[$j]-> e_keterangan,
										   "n_barang_merktype"		=>(string)$result[$j]-> n_barang_merktype,
										   "i_orgb"		            =>(string)$result[$j]-> i_orgb);	
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
	
	public function getTransaksiBrgsewaListMelihat4($pageNumber, $itemPerPage,$unitkr) 
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
											where a.c_barang_sewa=b.c_barang_sewa 
											and b.i_rekanan=c.i_rekanan											
											and d_pengembalian_sewa is not null"); 
											
										 
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
											n_barang_merktype,
											i_orgb
											FROM   e_ast_transaksisewa_0_tm  a, e_ast_barangsewa_0_tr b , 
											e_rekanan_prsh_0_tm c
											where a.c_barang_sewa=b.c_barang_sewa 
											and b.i_rekanan=c.i_rekanan
											and d_pengembalian_sewa is not null 											
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
										   "n_prsh"   			=>(string)$result[$j]-> n_prsh,
							               "d_barang_sewaawal" 		=>(string)$result[$j]-> d_barang_sewaawal,
										   "d_barang_sewaakhir" 	=>(string)$result[$j]-> d_barang_sewaakhir,
							               "e_keterangan"			=>(string)$result[$j]-> e_keterangan,
										   "n_barang_merktype"		=>(string)$result[$j]-> n_barang_merktype,
										   "i_orgb"		            =>(string)$result[$j]-> i_orgb);	
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
	
	
	public function getTransaksiBrgsewaListMelihat5($pageNumber, $itemPerPage,$unitkr) 
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
											where a.c_barang_sewa=b.c_barang_sewa 
											and b.i_rekanan=c.i_rekanan
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
											n_barang_merktype,
											i_orgb
											FROM   e_ast_transaksisewa_0_tm  a, e_ast_barangsewa_0_tr b , 
											e_rekanan_prsh_0_tm c
											where a.c_barang_sewa=b.c_barang_sewa 
											and b.i_rekanan=c.i_rekanan
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
										   "n_prsh"   			=>(string)$result[$j]-> n_prsh,
							               "d_barang_sewaawal" 		=>(string)$result[$j]-> d_barang_sewaawal,
										   "d_barang_sewaakhir" 	=>(string)$result[$j]-> d_barang_sewaakhir,
							               "e_keterangan"			=>(string)$result[$j]-> e_keterangan,
										   "n_barang_merktype"		=>(string)$result[$j]-> n_barang_merktype,
										   "i_orgb"		            =>(string)$result[$j]-> i_orgb);	
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
	
	public function getCariDafTransaksiSewaMasukList($namabarang,$pageNumber,$itemPerPage) 
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

//=====================  Akhir Tambahan JOY 030308 ==============================

//add  utk pengembalian sewa unit =======================10 mar 08==============================================================
public function getKembalisewaList($pageNumber,$itemPerPage)  
{
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
		
	try 
	{
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		
		if(($pageNumber==0) && ($itemPerPage==0))
		{
			$hasilAkhir = $db->fetchOne("SELECT count(*)
										FROM e_ast_barangsewa_0_tr A,
										e_ast_transaksisewa_0_tm B,
										e_rekanan_prsh_0_tm C
										where B.c_barang_Sewa = A.c_barang_sewa
										and A.i_rekanan = C.i_rekanan
										and B.d_barang_serahsewa is not null
										and B.d_barang_pengembalian is null "); 
		}
		else
		{
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;	
			$result = $db->fetchAll("select A.c_barang_sewa, B.i_barang_sewa, A.n_barang,
										B.i_barang_serial, A.n_barang_merktype, C.N_prsh,
										B.d_barang_sewaawal, B.d_barang_sewaakhir, B.i_orgb
										from e_ast_barangsewa_0_tr A,
										e_ast_transaksisewa_0_tm B,
										e_rekanan_prsh_0_tm C
										where B.c_barang_Sewa = A.c_barang_sewa
										and A.i_rekanan = C.i_rekanan
										and B.d_barang_serahsewa is not null
										and B.d_barang_pengembalian is null 
										order by A.c_barang_sewa, B.i_barang_sewa
										limit $xLimit offset $xOffset"); 
		
			$jmlResult = count($result);	
			//echo "jumlah di services =".$jmlResult; 
			if($jmlResult > 0)
			{
				for ($j = 0; $j < $jmlResult; $j++) 
				{		 
				$hasilAkhir[$j] = array("c_barang_sewa"           	=>(string)$result[$j]-> c_barang_sewa,
							               "i_barang_sewa" 			=>(string)$result[$j]-> i_barang_sewa,				               
							               "n_barang"   			=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "n_barang_merktype" 		=>(string)$result[$j]-> n_barang_merktype,
										   "n_prsh"   				=>(string)$result[$j]-> n_prsh,
							               "d_barang_sewaawal"		=>(string)$result[$j]-> d_barang_sewaawal,
							               "d_barang_sewaakhir"		=>(string)$result[$j]-> d_barang_sewaakhir,
										   "i_orgb"					=>(string)$result[$j]-> i_orgb);			
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

public function getCariKembalisewaList($namabarang,$pageNumber,$itemPerPage) 
{
	//echo" +masuk getCariBrgsewaList services";
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	
	$namabarang = strtoupper($namabarang).'%';
	//echo "+namabarang services =".$namabarang;
	$where[]=$namabarang;		   
		
	try 
	{
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		
		if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
										FROM e_ast_barangsewa_0_tr A,
										e_ast_transaksisewa_0_tm B,
										e_rekanan_prsh_0_tm C
										where B.c_barang_Sewa = A.c_barang_sewa
										and A.i_rekanan = C.i_rekanan
										and B.d_barang_serahsewa is not null
										and B.d_barang_pengembalian is null 
										and upper(i_orgb) like upper(?)",$where);
		 }
		 else
		 {
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;		
			$result = $db->fetchAll("select A.c_barang_sewa, B.i_barang_sewa, A.n_barang,
										B.i_barang_serial, A.n_barang_merktype, C.N_prsh,
										B.d_barang_sewaawal, B.d_barang_sewaakhir, B.i_orgb
										from e_ast_barangsewa_0_tr A,
										e_ast_transaksisewa_0_tm B,
										e_rekanan_prsh_0_tm C
										where B.c_barang_Sewa = A.c_barang_sewa
										and A.i_rekanan = C.i_rekanan
										and B.d_barang_serahsewa is not null
										and B.d_barang_pengembalian is null 
										and upper(i_orgb) like upper(?)
										order by A.c_barang_sewa, B.i_barang_sewa
										limit $xLimit offset $xOffset",$where);
		
			$jmlResult = count($result);	
			//echo "+jumlah cari =".$jmlResult;	 
			
			if($jmlResult > 0)
			{
				for ($j = 0; $j < $jmlResult; $j++) 
				{		 
				$hasilAkhir[$j] = array("c_barang_sewa"           	=>(string)$result[$j]-> c_barang_sewa,
							               "i_barang_sewa" 			=>(string)$result[$j]-> i_barang_sewa,				               
							               "n_barang"   			=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "n_barang_merktype" 		=>(string)$result[$j]-> n_barang_merktype,
										   "n_prsh"   				=>(string)$result[$j]-> n_prsh,
							               "d_barang_sewaawal"		=>(string)$result[$j]-> d_barang_sewaawal,
							               "d_barang_sewaakhir"		=>(string)$result[$j]-> d_barang_sewaakhir,
										   "i_orgb"					=>(string)$result[$j]-> i_orgb);				
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

public function getUpdateKembalisewaUnit(array $data) 
{
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');	
	try 
	{
		$db->beginTransaction();
		$BrgsewaArr = array("d_barang_pengembalian"		=>date("Y-m-d"),
		                    "i_orgb" 					=> null,
							"d_barang_serahsewa"  		=> null);
		
		                     
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

public function getKembalisewaUnitList($unitkr)  
{
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	
	$unitkr = strtoupper($unitkr).'%';
	$where[]=$unitkr;
	
	try 
	{
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		
			$result = $db->fetchAll("select A.c_barang_sewa, B.i_barang_sewa, A.n_barang,
										B.i_barang_serial, A.n_barang_merktype, C.N_prsh,
										B.d_barang_sewaawal, B.d_barang_sewaakhir, B.i_orgb
										from e_ast_barangsewa_0_tr A,
										e_ast_transaksisewa_0_tm B,
										e_rekanan_prsh_0_tm C
										where B.c_barang_Sewa = A.c_barang_sewa
										and A.i_rekanan = C.i_rekanan
										and B.d_barang_serahsewa is not null
										and B.d_barang_pengembalian is null 
										and upper(i_orgb) like upper(?)
										order by A.c_barang_sewa, B.i_barang_sewa",$where); 
		
			$jmlResult = count($result);	
			//echo "jumlah di services =".$jmlResult; 
			if($jmlResult > 0)
			{
				for ($j = 0; $j < $jmlResult; $j++) 
				{		 
				$hasilAkhir[$j] = array("c_barang_sewa"           	=>(string)$result[$j]-> c_barang_sewa,
							               "i_barang_sewa" 			=>(string)$result[$j]-> i_barang_sewa,				               
							               "n_barang"   			=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "n_barang_merktype" 		=>(string)$result[$j]-> n_barang_merktype,
										   "n_prsh"   				=>(string)$result[$j]-> n_prsh,
							               "d_barang_sewaawal"		=>(string)$result[$j]-> d_barang_sewaawal,
							               "d_barang_sewaakhir"		=>(string)$result[$j]-> d_barang_sewaakhir,
										   "i_orgb"					=>(string)$result[$j]-> i_orgb);			
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

//===============================================================sewa supplier ===========================================================

public function getKembalisewaSuppList($unitkr)  
{
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	
	$unitkr = strtoupper($unitkr).'%';
	$where[]=$unitkr;
	
	try 
	{
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		
			$result = $db->fetchAll("select A.c_barang_sewa, B.i_barang_sewa, A.n_barang,
										B.i_barang_serial, A.n_barang_merktype, C.N_prsh,
										B.d_barang_sewaawal, B.d_barang_sewaakhir, B.i_orgb
										from e_ast_barangsewa_0_tr A,
										e_ast_transaksisewa_0_tm B,
										e_rekanan_prsh_0_tm C
										where B.c_barang_Sewa = A.c_barang_sewa
										and A.i_rekanan = C.i_rekanan
										and i_orgb is null
										and B.d_barang_pengembalian is not null
										and B.d_pengembalian_sewa is null																				
										and upper(C.n_prsh) like upper(?)
										order by A.c_barang_sewa, B.i_barang_sewa",$where); 
		
			$jmlResult = count($result);	
			//echo "jumlah di services =".$jmlResult; 
			if($jmlResult > 0)
			{
				for ($j = 0; $j < $jmlResult; $j++) 
				{		 
				$hasilAkhir[$j] = array("c_barang_sewa"           	=>(string)$result[$j]-> c_barang_sewa,
							               "i_barang_sewa" 			=>(string)$result[$j]-> i_barang_sewa,				               
							               "n_barang"   			=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "n_barang_merktype" 		=>(string)$result[$j]-> n_barang_merktype,
										   "n_prsh"   				=>(string)$result[$j]-> n_prsh,
							               "d_barang_sewaawal"		=>(string)$result[$j]-> d_barang_sewaawal,
							               "d_barang_sewaakhir"		=>(string)$result[$j]-> d_barang_sewaakhir,
										   "i_orgb"					=>(string)$result[$j]-> i_orgb);			
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

public function getUpdateKembalisewaSupp(array $data) 
{
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');	
	try 
	{
		$db->beginTransaction();
		$BrgsewaArr = array("d_pengembalian_sewa"		=>date("Y-m-d"));
		
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

public function getCariKembalisewaSuppList($namabarang,$pageNumber,$itemPerPage) 
{
	//echo" +masuk getCariBrgsewaList services";
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	
	$namabarang = strtoupper($namabarang).'%';
	//echo "+namabarang services =".$namabarang;
	$where[]=$namabarang;		   
		
	try 
	{
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		
		if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
										FROM e_ast_barangsewa_0_tr A,
										e_ast_transaksisewa_0_tm B,
										e_rekanan_prsh_0_tm C
										where B.c_barang_Sewa = A.c_barang_sewa
										and A.i_rekanan = C.i_rekanan
										and B.d_barang_pengembalian is not null
										and B.d_pengembalian_sewa is null
										and upper(C.n_prsh) like upper(?)",$where);
		 }
		 else
		 {
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;		
			$result = $db->fetchAll("select A.c_barang_sewa, B.i_barang_sewa, A.n_barang,
										B.i_barang_serial, A.n_barang_merktype, C.N_prsh,
										B.d_barang_sewaawal, B.d_barang_sewaakhir, B.i_orgb
										from e_ast_barangsewa_0_tr A,
										e_ast_transaksisewa_0_tm B,
										e_rekanan_prsh_0_tm C
										where B.c_barang_Sewa = A.c_barang_sewa
										and A.i_rekanan = C.i_rekanan
										and B.d_barang_pengembalian is not null
										and B.d_pengembalian_sewa is null
										and upper(C.n_prsh) like upper(?)
										order by A.c_barang_sewa, B.i_barang_sewa
										limit $xLimit offset $xOffset",$where);
		
			$jmlResult = count($result);	
			//echo "+jumlah cari =".$jmlResult;	 
			
			if($jmlResult > 0)
			{
				for ($j = 0; $j < $jmlResult; $j++) 
				{		 
				$hasilAkhir[$j] = array("c_barang_sewa"           	=>(string)$result[$j]-> c_barang_sewa,
							               "i_barang_sewa" 			=>(string)$result[$j]-> i_barang_sewa,				               
							               "n_barang"   			=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "n_barang_merktype" 		=>(string)$result[$j]-> n_barang_merktype,
										   "n_prsh"   				=>(string)$result[$j]-> n_prsh,
							               "d_barang_sewaawal"		=>(string)$result[$j]-> d_barang_sewaawal,
							               "d_barang_sewaakhir"		=>(string)$result[$j]-> d_barang_sewaakhir,
										   "i_orgb"					=>(string)$result[$j]-> i_orgb);				
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