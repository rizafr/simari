<?php

require_once 'Zend/Json.php';

class ast_distribusibarangsewati_Service 
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
										FROM e_ast_barangpinjam_0_tr A,
										E_ast_transaksipinjam_0_tm B
										where B.c_barang_pinjam = A.c_barang_pinjam										
										and B.d_barang_serahpinjam is null
										and B.d_pengembalian_pinjam is null"); 
		}
		else
		{
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;	
			$result = $db->fetchAll("SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan, 
										a.n_barang_merktype, i_orgb,										
										f.a_prsh_jalan, 
										f.a_prsh_kota, f.i_prsh_telpon, f.i_prsh_fax										
										FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_rekanan_prsh_0_tm c, e_ast_vendor_0_tr D,
										e_rekanan_prsh_0_tm E, e_rekanan_almt_prsh_tm F
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = D.i_rekanan
										and D.i_rekanan_ref = C.i_rekanan
										and f.i_rekanan = e.i_rekanan
										and D.i_rekanan_ref = e.i_rekanan 
										and f.c_prsh_levelktr = e.c_prsh_levelktr
										and a.d_barang_serahpinjam is  null
										and a.d_pengembalian_pinjam is null
										union
										SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_rekanan as n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan,
										a.n_barang_merktype, i_orgb,
										a_rekanan as  a_prsh_jalan, 
										a_prsh_jalan as a_prsh_kota, i_rekanan_telp as i_prsh_telpon,
										'-' as i_prsh_fax
										FROM  E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_ast_vendor_0_tr C
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = C.i_rekanan
										and C.i_rekanan_ref ='--'
										and a.d_barang_serahpinjam is  null
										and a.d_pengembalian_pinjam is null
										limit $xLimit offset $xOffset"); 
		
			$jmlResult = count($result);	
			
			if($jmlResult > 0)
			{
				for ($j = 0; $j < $jmlResult; $j++) 
				{		 
				$hasilAkhir[$j] = array("c_barang_pinjam"           	=>(string)$result[$j]-> c_barang_pinjam,
							               "i_barang_pinjam" 			=>(string)$result[$j]-> i_barang_pinjam,				               
							               "n_barang"   			=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "n_barang_merktype" 		=>(string)$result[$j]-> n_barang_merktype,
										   "n_prsh"   				=>(string)$result[$j]-> n_prsh,
							               "d_barang_pinjamawal"		=>(string)$result[$j]-> d_barang_pinjamawal,
							               "d_barang_pinjamakhir"		=>(string)$result[$j]-> d_barang_pinjamakhir,
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

//====================================================================================
public function getCariBrgsewaList($namabarang,$pageNumber,$itemPerPage) 
{
	
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	
	$namabarang = strtoupper($namabarang).'%';
	
	$kondisi[]=$namabarang;		   
	$where[]=$namabarang;		   
	$where[]=$namabarang;		   
	try 
	{
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		
		if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
										FROM e_ast_barangpinjam_0_tr A,
										E_ast_transaksipinjam_0_tm B
										where B.c_barang_pinjam = A.c_barang_pinjam										
										and B.d_barang_serahpinjam is null										
										and B.d_pengembalian_pinjam is null
										and upper(n_barang) like upper(?)",$kondisi);
		 }
		 else
		 {
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;		
			$result = $db->fetchAll("SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan, 
										a.n_barang_merktype, i_orgb,										
										f.a_prsh_jalan, 
										f.a_prsh_kota, f.i_prsh_telpon, f.i_prsh_fax										
										FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_rekanan_prsh_0_tm c, e_ast_vendor_0_tr D,
										e_rekanan_prsh_0_tm E, e_rekanan_almt_prsh_tm F
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = D.i_rekanan
										and D.i_rekanan_ref = C.i_rekanan
										and f.i_rekanan = e.i_rekanan
										and D.i_rekanan_ref = e.i_rekanan 
										and f.c_prsh_levelktr = e.c_prsh_levelktr
										and a.d_barang_serahpinjam is  null
										and a.d_pengembalian_pinjam is null
										and upper(n_barang) like upper(?)
										UNION ALL
										SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_rekanan as n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan,
										a.n_barang_merktype, i_orgb,
										a_rekanan as  a_prsh_jalan, 
										a_prsh_jalan as a_prsh_kota, i_rekanan_telp as i_prsh_telpon,
										'-' as i_prsh_fax
										FROM  E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_ast_vendor_0_tr C
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = C.i_rekanan
										and C.i_rekanan_ref ='--'
										and a.d_barang_serahpinjam is  null
										and a.d_pengembalian_pinjam is null
										and upper(n_barang) like upper(?)
										limit $xLimit offset $xOffset",$where);
		
			$jmlResult = count($result);	
			
			
			if($jmlResult > 0)
			{
				for ($j = 0; $j < $jmlResult; $j++) 
				{		 
				$hasilAkhir[$j] = array("c_barang_pinjam"           	=>(string)$result[$j]-> c_barang_pinjam,
							               "i_barang_pinjam" 			=>(string)$result[$j]-> i_barang_pinjam,				               
							               "n_barang"   			=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "n_barang_merktype" 		=>(string)$result[$j]-> n_barang_merktype,
										   "n_prsh"   				=>(string)$result[$j]-> n_prsh,
							               "d_barang_pinjamawal"		=>(string)$result[$j]-> d_barang_pinjamawal,
							               "d_barang_pinjamakhir"		=>(string)$result[$j]-> d_barang_pinjamakhir,
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

//====================================================================================
public function getUpdateBrgsewa(array $data) 
{
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');	
	try 
	{
		$db->beginTransaction();
		$BrgsewaArr = array("d_barang_serahpinjam"		=>date("Y-m-d"),
							"i_ruang"		=>$data['i_ruang'],
							"i_orgb"		=>$data['i_orgb'],
							"i_orgb"		=>$data['i_orgb'],
							"i_peg_nipterima1"		=>$data['i_peg_nipterima1'],
							"i_peg_nipserah1"		=>$data['i_peg_nipserah1'],							
							"d_barang_pengembalian"   => null,
							"d_pengembalian_pinjam"     => null);
		
		//parameter getUpdateBrgsewa 		   
		$where[] = "c_barang_pinjam 	=  '".trim($data['c_barang_pinjam'])."'";
		$where[] = "i_barang_pinjam 	=  '".trim($data['i_barang_pinjam'])."'";
		
		$db->update('e_ast_transaksipinjam_0_tm',$BrgsewaArr,$where);
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
	public function getRefBrgsewaList_Old($pageNumber,$itemPerPage) 
	{
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
			
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
			if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$hasilAkhir = $db->fetchOne("SELECT count(*) from e_ast_barangpinjam_0_tr");
			 }
			 else
			 {
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				$result = $db->fetchAll("select a.c_barang_pinjam, a.n_barang, a.n_barang_merktype,
										a.e_keterangan,a.i_rekanan, b.n_rekanan as n_prsh
										from e_ast_barangpinjam_0_tr A, e_ast_vendor_0_tr b
										where a.i_rekanan = b.i_rekanan 
										and b.i_rekanan_ref ='--'
										union
										select a.c_barang_pinjam, a.n_barang, a.n_barang_merktype,
										a.e_keterangan,a.i_rekanan, b.n_prsh 
										from e_ast_barangpinjam_0_tr A, e_rekanan_prsh_0_tm B,
										e_ast_vendor_0_tr C 
										where a.i_rekanan = C.i_rekanan 
										and b.i_rekanan = c.i_rekanan_ref  
										limit $xLimit offset $xOffset");
			
				$jmlResult = count($result);	
				
				
				if($jmlResult > 0)
				{
					for ($j = 0; $j < $jmlResult; $j++) 
					{		 
					$hasilAkhir[$j] = array("c_barang_pinjam"	=>(string)$result[$j]-> c_barang_pinjam,
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

	 // Ina : 31-10-2008 : Awal : Perubahan : Di referensi barang, rekanan dan type tidak diisi
	 public function getRefBrgsewaList($pageNumber,$itemPerPage) 
	{
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
			
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
			if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$hasilAkhir = $db->fetchOne("SELECT count(*) from e_ast_barangpinjam_0_tr");
			 }
			 else
			 {
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				$result = $db->fetchAll("select a.c_barang_pinjam, a.n_barang, a.n_barang_merktype,
										a.e_keterangan,a.i_rekanan
										from e_ast_barangpinjam_0_tr A										
										limit $xLimit offset $xOffset");
			
				$jmlResult = count($result);	
				
				
				if($jmlResult > 0)
				{
					for ($j = 0; $j < $jmlResult; $j++) 
					{		 
					$hasilAkhir[$j] = array("c_barang_pinjam"	=>(string)$result[$j]-> c_barang_pinjam,
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
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
			
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
			if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$hasilAkhir = $db->fetchOne("SELECT count(*) 
				                        from e_ast_vendor_0_tr");
			 }
			 else
			 {
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				$result = $db->fetchAll("select i_rekanan, n_rekanan as n_prsh, a_rekanan as  a_prsh_jalan, 
										a_prsh_jalan as a_prsh_kota, i_rekanan_telp as i_prsh_telpon,
										'-' as i_prsh_fax
										from e_ast_vendor_0_tr
										where i_rekanan_ref ='--'
										UNION
										select c.i_rekanan, a.n_prsh , b.a_prsh_jalan, 
										b.a_prsh_kota, b.i_prsh_telpon, b.i_prsh_fax
										from e_rekanan_prsh_0_tm A, e_rekanan_almt_prsh_tm B,
										e_ast_vendor_0_tr C
										where b.i_rekanan = a.i_rekanan
										and c.i_rekanan_ref = a.i_rekanan 
										and b.c_prsh_levelktr = a.c_prsh_levelktr
										limit $xLimit offset $xOffset");
			
				$jmlResult = count($result);	
				 
				
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
		$where[] = 'PAI';
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
			
				$prmInsert = array("c_barang_pinjam"		=>$data['c_barang_pinjam'],
									"n_barang"			=>$data['n_barang'],																		
									"q_modul_nomormax"	=>$data['q_modul_nomormax'],									
									"i_entry"		=>$data['i_entry'],
									"d_entry"		=>$data['d_entry']);
				
			$db->insert('e_ast_barangpinjam_0_tr',$prmInsert);
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
			$where[] = $kdrek;
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$result = $db->fetchAll("select i_rekanan, n_rekanan as n_prsh, a_rekanan as  a_prsh_jalan, 
										a_prsh_jalan as a_prsh_kota, i_rekanan_telp as i_prsh_telpon,
										'-' as i_prsh_fax
										from e_ast_vendor_0_tr
										where i_rekanan_ref ='--'
										and i_rekanan = ?
										UNION
										select c.i_rekanan, a.n_prsh , b.a_prsh_jalan, 
										b.a_prsh_kota, b.i_prsh_telpon, b.i_prsh_fax
										from e_rekanan_prsh_0_tm A, e_rekanan_almt_prsh_tm B,
										e_ast_vendor_0_tr C
										where b.i_rekanan = a.i_rekanan
										and c.i_rekanan_ref = a.i_rekanan 
										and b.c_prsh_levelktr = a.c_prsh_levelktr
										and a.i_rekanan = ?",$where);
			
			$jmlResult = count($result);	
			
			
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
			$BrgsewaArr = array("n_barang"		=>$data['n_barang']);
			
			//parameter getUpdateBrgsewa 		   
			$where[] = "c_barang_pinjam 	=  '".trim($data['c_barang_pinjam'])."'";
			
			$db->update('e_ast_barangpinjam_0_tr',$BrgsewaArr,$where);
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
				i_barang_pinjam,
				i_rekanan,
				n_barang,
				n_barang_merktype,
				i_barang_serial,
				d_barang_pinjamawal,
				d_barang_pinjamakhir,
				e_keterangan,
				d_barang_pengembalian
				FROM e_ast_barangsewa_0_tm 
				where d_anggaran=? limit $xLimit offset $xOffset",$tahun); 
			
				$jmlResult = count($result);	
				
				
				if($jmlResult > 0)
				{
					for ($j = 0; $j < $jmlResult; $j++) 
					{		 
					$hasilAkhir[$j] = array("d_anggaran"	=>(string)$result[$j]-> d_anggaran,
					               "c_barang"           	=>(string)$result[$j]-> c_barang,
					                "i_barang_pinjam" 		=>(string)$result[$j]-> i_barang_pinjam,
					               "i_rekanan"     		=>(string)$result[$j]-> i_rekanan,
					               "n_barang"   		=>(string)$result[$j]-> n_barang,
					               "n_barang_merktype" 	=>(string)$result[$j]-> n_barang_merktype,
								   "i_barang_serial" 	=>(string)$result[$j]-> i_barang_serial,
					               "d_barang_pinjamawal"	=>(string)$result[$j]-> d_barang_pinjamawal,
					               "d_barang_pinjamakhir"	=>(string)$result[$j]-> d_barang_pinjamakhir,
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
				i_barang_pinjam,
				i_rekanan,
				n_barang,
				n_barang_merktype,
				i_barang_serial,
				d_barang_pinjamawal,
				d_barang_pinjamakhir,
				e_keterangan,
				d_barang_pengembalian
				FROM e_ast_barangsewa_0_tm 
				where d_anggaran=? limit $xLimit offset $xOffset",$tahun); 
			
				$jmlResult = count($result);	
				 
				
				if($jmlResult > 0)
				{
					for ($j = 0; $j < $jmlResult; $j++) 
					{		 
					$hasilAkhir[$j] = array("d_anggaran"	=>(string)$result[$j]-> d_anggaran,
					               "c_barang"           	=>(string)$result[$j]-> c_barang,
					                "i_barang_pinjam" 		=>(string)$result[$j]-> i_barang_pinjam,
					               "i_rekanan"     		=>(string)$result[$j]-> i_rekanan,
					               "n_barang"   		=>(string)$result[$j]-> n_barang,
					               "n_barang_merktype" 	=>(string)$result[$j]-> n_barang_merktype,
								   "i_barang_serial" 	=>(string)$result[$j]-> i_barang_serial,
					               "d_barang_pinjamawal"	=>(string)$result[$j]-> d_barang_pinjamawal,
					               "d_barang_pinjamakhir"	=>(string)$result[$j]-> d_barang_pinjamakhir,
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
											FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b 
											where a.c_barang_pinjam=b.c_barang_pinjam"); 
										 
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				$result = $db->fetchAll("SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan, 
										a.n_barang_merktype, i_orgb,										
										f.a_prsh_jalan, 
										f.a_prsh_kota, f.i_prsh_telpon, f.i_prsh_fax,
										i_peg_nipterima1, d_barang_serahpinjam, d_barang_pengembalian, d_pengembalian_pinjam
										FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_rekanan_prsh_0_tm c, e_ast_vendor_0_tr D,
										e_rekanan_prsh_0_tm E, e_rekanan_almt_prsh_tm F
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = D.i_rekanan
										and D.i_rekanan_ref = C.i_rekanan
										and f.i_rekanan = e.i_rekanan
										and D.i_rekanan_ref = e.i_rekanan 
										and f.c_prsh_levelktr = e.c_prsh_levelktr										
										union all
										SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_rekanan as n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan,
										a.n_barang_merktype, i_orgb,
										a_rekanan as  a_prsh_jalan, 
										a_prsh_jalan as a_prsh_kota, i_rekanan_telp as i_prsh_telpon,
										'-' as i_prsh_fax,
										i_peg_nipterima1, d_barang_serahpinjam, d_barang_pengembalian, d_pengembalian_pinjam
										FROM  E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_ast_vendor_0_tr C
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = C.i_rekanan
										and C.i_rekanan_ref ='--'										
										limit $xLimit offset $xOffset"); 
		
				$jmlResult = count($result);			
			
				if($jmlResult > 0)
				{
				for ($j = 0; $j < $jmlResult; $j++) 
					{		 
						$hasilAkhir[$j] = array("c_barang_pinjam"          	=>(string)$result[$j]-> c_barang_pinjam,
							               "i_barang_pinjam"			=>(string)$result[$j]-> i_barang_pinjam,
							               "n_barang" 				=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "i_rekanan"   			=>(string)$result[$j]-> i_rekanan,
										   "n_prsh"   			=>(string)$result[$j]-> n_prsh,
							               "d_barang_pinjamawal" 		=>(string)$result[$j]-> d_barang_pinjamawal,
										   "d_barang_pinjamakhir" 	=>(string)$result[$j]-> d_barang_pinjamakhir,
							               "e_keterangan"			=>(string)$result[$j]-> e_keterangan,
										   "i_orgb"					=>(string)$result[$j]-> i_orgb,
										   "d_distribusi"					=>(string)$result[$j]-> d_barang_serahpinjam,
										   "d_pengembalian_unit"					=>(string)$result[$j]-> d_barang_pengembalian,
										   "d_pengembalian_rekanan"					=>(string)$result[$j]-> d_pengembalian_pinjam,
										   "i_peg_nipterima"					=>(string)$result[$j]-> i_peg_nipterima1,
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
											FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b 
											where a.c_barang_pinjam=b.c_barang_pinjam 											
											and (i_orgb is null or i_orgb = '')
											and d_pengembalian_pinjam is null
											"); 
										 
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				$result = $db->fetchAll("SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan, 
										a.n_barang_merktype, i_orgb,										
										f.a_prsh_jalan, 
										f.a_prsh_kota, f.i_prsh_telpon, f.i_prsh_fax										
										FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_rekanan_prsh_0_tm c, e_ast_vendor_0_tr D,
										e_rekanan_prsh_0_tm E, e_rekanan_almt_prsh_tm F
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = D.i_rekanan
										and D.i_rekanan_ref = C.i_rekanan
										and f.i_rekanan = e.i_rekanan
										and D.i_rekanan_ref = e.i_rekanan 
										and f.c_prsh_levelktr = e.c_prsh_levelktr
										and (i_orgb is null or i_orgb = '')
										and d_pengembalian_pinjam is null
										union all
										SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_rekanan as n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan,
										a.n_barang_merktype, i_orgb,
										a_rekanan as  a_prsh_jalan, 
										a_prsh_jalan as a_prsh_kota, i_rekanan_telp as i_prsh_telpon,
										'-' as i_prsh_fax
										FROM  E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_ast_vendor_0_tr C
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = C.i_rekanan
										and C.i_rekanan_ref ='--'
										and (i_orgb is null or i_orgb = '')
										and d_pengembalian_pinjam is null
										limit $xLimit offset $xOffset"); 
		
				$jmlResult = count($result);			
			
				if($jmlResult > 0)
				{
				for ($j = 0; $j < $jmlResult; $j++) 
					{		 
						$hasilAkhir[$j] = array("c_barang_pinjam"          	=>(string)$result[$j]-> c_barang_pinjam,
							               "i_barang_pinjam"			=>(string)$result[$j]-> i_barang_pinjam,
							               "n_barang" 				=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "i_rekanan"   			=>(string)$result[$j]-> i_rekanan,
										   "n_prsh"   			=>(string)$result[$j]-> n_prsh,
							               "d_barang_pinjamawal" 		=>(string)$result[$j]-> d_barang_pinjamawal,
										   "d_barang_pinjamakhir" 	=>(string)$result[$j]-> d_barang_pinjamakhir,
							               "e_keterangan"			=>(string)$result[$j]-> e_keterangan,
										   "i_orgb"					=>(string)$result[$j]-> i_orgb,
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
											FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b
											where a.c_barang_pinjam=b.c_barang_pinjam 											
											and  i_orgb is not null
											and d_barang_pengembalian is null"); 
										 
		}
		else
		{
		    
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;		
			$result = $db->fetchAll("SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan, 
										a.n_barang_merktype, i_orgb,										
										f.a_prsh_jalan, 
										f.a_prsh_kota, f.i_prsh_telpon, f.i_prsh_fax										
										FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_rekanan_prsh_0_tm c, e_ast_vendor_0_tr D,
										e_rekanan_prsh_0_tm E, e_rekanan_almt_prsh_tm F
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = D.i_rekanan
										and D.i_rekanan_ref = C.i_rekanan
										and f.i_rekanan = e.i_rekanan
										and D.i_rekanan_ref = e.i_rekanan 
										and f.c_prsh_levelktr = e.c_prsh_levelktr
										and i_orgb is not null
										and d_barang_pengembalian is null
										union all
										SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_rekanan as n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan,
										a.n_barang_merktype, i_orgb,
										a_rekanan as  a_prsh_jalan, 
										a_prsh_jalan as a_prsh_kota, i_rekanan_telp as i_prsh_telpon,
										'-' as i_prsh_fax
										FROM  E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_ast_vendor_0_tr C
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = C.i_rekanan
										and C.i_rekanan_ref ='--'
										and i_orgb is not null
										and d_barang_pengembalian is null			
										limit $xLimit offset $xOffset"); 
		
			$jmlResult = count($result);	
			
			
			if($jmlResult > 0)
			{
				for ($j = 0; $j < $jmlResult; $j++) 
				{		 
				$hasilAkhir[$j] = array("c_barang_pinjam"          	=>(string)$result[$j]-> c_barang_pinjam,
							               "i_barang_pinjam"			=>(string)$result[$j]-> i_barang_pinjam,
							               "n_barang" 				=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "i_rekanan"   			=>(string)$result[$j]-> i_rekanan,
										   "n_prsh"   			=>(string)$result[$j]-> n_prsh,
							               "d_barang_pinjamawal" 		=>(string)$result[$j]-> d_barang_pinjamawal,
										   "d_barang_pinjamakhir" 	=>(string)$result[$j]-> d_barang_pinjamakhir,
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
											FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b 
											where a.c_barang_pinjam=b.c_barang_pinjam 											
											and d_barang_pengembalian is not null 
											and  i_orgb is null"); 
										 
										 
		}
		else
		{
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;		
			$result = $db->fetchAll("SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan, 
										a.n_barang_merktype, i_orgb,										
										f.a_prsh_jalan, 
										f.a_prsh_kota, f.i_prsh_telpon, f.i_prsh_fax										
										FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_rekanan_prsh_0_tm c, e_ast_vendor_0_tr D,
										e_rekanan_prsh_0_tm E, e_rekanan_almt_prsh_tm F
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = D.i_rekanan
										and D.i_rekanan_ref = C.i_rekanan
										and f.i_rekanan = e.i_rekanan
										and D.i_rekanan_ref = e.i_rekanan 
										and f.c_prsh_levelktr = e.c_prsh_levelktr
										and d_barang_pengembalian is not null 
										and i_orgb is null
										union all
										SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_rekanan as n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan,
										a.n_barang_merktype, i_orgb,
										a_rekanan as  a_prsh_jalan, 
										a_prsh_jalan as a_prsh_kota, i_rekanan_telp as i_prsh_telpon,
										'-' as i_prsh_fax
										FROM  E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_ast_vendor_0_tr C
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = C.i_rekanan
										and C.i_rekanan_ref ='--'
										and d_barang_pengembalian is not null 
										and i_orgb is null			
										limit $xLimit offset $xOffset"); 
		
			$jmlResult = count($result);	
			
			
			if($jmlResult > 0)
			{
				for ($j = 0; $j < $jmlResult; $j++) 
				{		 
				$hasilAkhir[$j] = array("c_barang_pinjam"          	=>(string)$result[$j]-> c_barang_pinjam,
							               "i_barang_pinjam"			=>(string)$result[$j]-> i_barang_pinjam,
							               "n_barang" 				=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "i_rekanan"   			=>(string)$result[$j]-> i_rekanan,
										   "n_prsh"   			=>(string)$result[$j]-> n_prsh,
							               "d_barang_pinjamawal" 		=>(string)$result[$j]-> d_barang_pinjamawal,
										   "d_barang_pinjamakhir" 	=>(string)$result[$j]-> d_barang_pinjamakhir,
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
											FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b 
											where a.c_barang_pinjam=b.c_barang_pinjam 											
											and d_pengembalian_pinjam is not null"); 
											
										 
		}
		else
		{
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;		
			$result = $db->fetchAll("SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan, 
										a.n_barang_merktype, i_orgb,										
										f.a_prsh_jalan, 
										f.a_prsh_kota, f.i_prsh_telpon, f.i_prsh_fax										
										FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_rekanan_prsh_0_tm c, e_ast_vendor_0_tr D,
										e_rekanan_prsh_0_tm E, e_rekanan_almt_prsh_tm F
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = D.i_rekanan
										and D.i_rekanan_ref = C.i_rekanan
										and f.i_rekanan = e.i_rekanan
										and D.i_rekanan_ref = e.i_rekanan 
										and f.c_prsh_levelktr = e.c_prsh_levelktr
										and d_pengembalian_pinjam is not null 											
										union all
										SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_rekanan as n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan,
										a.n_barang_merktype, i_orgb,
										a_rekanan as  a_prsh_jalan, 
										a_prsh_jalan as a_prsh_kota, i_rekanan_telp as i_prsh_telpon,
										'-' as i_prsh_fax
										FROM  E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_ast_vendor_0_tr C
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = C.i_rekanan
										and C.i_rekanan_ref ='--'
										and d_pengembalian_pinjam is not null 
										limit $xLimit offset $xOffset"); 
		
			$jmlResult = count($result);	
			
			
			if($jmlResult > 0)
			{
				for ($j = 0; $j < $jmlResult; $j++) 
				{		 
				$hasilAkhir[$j] = array("c_barang_pinjam"          	=>(string)$result[$j]-> c_barang_pinjam,
							               "i_barang_pinjam"			=>(string)$result[$j]-> i_barang_pinjam,
							               "n_barang" 				=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "i_rekanan"   			=>(string)$result[$j]-> i_rekanan,
										   "n_prsh"   			=>(string)$result[$j]-> n_prsh,
							               "d_barang_pinjamawal" 		=>(string)$result[$j]-> d_barang_pinjamawal,
										   "d_barang_pinjamakhir" 	=>(string)$result[$j]-> d_barang_pinjamakhir,
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
											FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b 
											where a.c_barang_pinjam=b.c_barang_pinjam 											
											and d_pengembalian_pinjam is null"); 
										 
		}
		else
		{
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;		
			$result = $db->fetchAll("SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan, 
										a.n_barang_merktype, i_orgb,										
										f.a_prsh_jalan, 
										f.a_prsh_kota, f.i_prsh_telpon, f.i_prsh_fax										
										FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_rekanan_prsh_0_tm c, e_ast_vendor_0_tr D,
										e_rekanan_prsh_0_tm E, e_rekanan_almt_prsh_tm F
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = D.i_rekanan
										and D.i_rekanan_ref = C.i_rekanan
										and f.i_rekanan = e.i_rekanan
										and D.i_rekanan_ref = e.i_rekanan 
										and f.c_prsh_levelktr = e.c_prsh_levelktr
										and d_pengembalian_pinjam is null
										union all
										SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_rekanan as n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan,
										a.n_barang_merktype, i_orgb,
										a_rekanan as  a_prsh_jalan, 
										a_prsh_jalan as a_prsh_kota, i_rekanan_telp as i_prsh_telpon,
										'-' as i_prsh_fax
										FROM  E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_ast_vendor_0_tr C
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = C.i_rekanan
										and C.i_rekanan_ref ='--'
										and d_pengembalian_pinjam is null			
										limit $xLimit offset $xOffset"); 
		
			$jmlResult = count($result);	
			
			
			if($jmlResult > 0)
			{
				for ($j = 0; $j < $jmlResult; $j++) 
				{		 
				$hasilAkhir[$j] = array("c_barang_pinjam"          	=>(string)$result[$j]-> c_barang_pinjam,
							               "i_barang_pinjam"			=>(string)$result[$j]-> i_barang_pinjam,
							               "n_barang" 				=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "i_rekanan"   			=>(string)$result[$j]-> i_rekanan,
										   "n_prsh"   			=>(string)$result[$j]-> n_prsh,
							               "d_barang_pinjamawal" 		=>(string)$result[$j]-> d_barang_pinjamawal,
										   "d_barang_pinjamakhir" 	=>(string)$result[$j]-> d_barang_pinjamakhir,
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
						i_barang_pinjam,
						i_rekanan,
						n_barang,
						n_barang_merktype,
						i_barang_serial, 
						d_barang_pinjamawal,
						d_barang_pinjamakhir,
						e_keterangan,
						d_barang_pengembalian
						FROM e_ast_barangsewa_0_tm 
						where upper(n_barang) like upper(?) limit $xLimit offset $xOffset",$where);
			
				$jmlResult = count($result);	
				
				
				if($jmlResult > 0)
				{
					for ($j = 0; $j < $jmlResult; $j++) 
					{		 
					$hasilAkhir[$j] = array("d_anggaran"	=>(string)$result[$j]-> d_anggaran,
					               "c_barang"           	=>(string)$result[$j]-> c_barang,
					               "i_barang_pinjam" 		=>(string)$result[$j]-> i_barang_pinjam,
					               "i_rekanan"     		=>(string)$result[$j]-> i_rekanan,
					               "n_barang"   		=>(string)$result[$j]-> n_barang,
					               "n_barang_merktype" 	=>(string)$result[$j]-> n_barang_merktype,
								   "i_barang_serial" 	=>(string)$result[$j]-> i_barang_serial,
					               "d_barang_pinjamawal"	=>(string)$result[$j]-> d_barang_pinjamawal,
					               "d_barang_pinjamakhir"	=>(string)$result[$j]-> d_barang_pinjamakhir,
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
										FROM e_ast_barangpinjam_0_tr A,
										E_ast_transaksipinjam_0_tm B,
										e_ast_vendor_0_tr C
										where B.c_barang_pinjam = A.c_barang_pinjam
										and A.i_rekanan = C.i_rekanan
										and B.d_barang_serahpinjam is not null
										and B.d_barang_pengembalian is null "); 
		}
		else
		{
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;	
			$result = $db->fetchAll("SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, b.i_rekanan,
										c.n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan, 
										n_barang_merktype, i_orgb
										FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_rekanan_prsh_0_tm c, e_ast_vendor_0_tr D
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and B.i_rekanan = D.i_rekanan
										and D.i_rekanan_ref = C.i_rekanan
										and A.d_barang_serahpinjam is not null
										and A.d_barang_pengembalian is null 
										union all
										SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, b.i_rekanan,
										c.n_rekanan as n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan,
										n_barang_merktype, i_orgb
										FROM  E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b ,  e_ast_vendor_0_tr C
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and B.i_rekanan = C.i_rekanan
										and C.i_rekanan_ref ='--'
										and A.d_barang_serahpinjam is not null
										and A.d_barang_pengembalian is null 
										limit $xLimit offset $xOffset"); 
		
			$jmlResult = count($result);	
			
			if($jmlResult > 0)
			{
				for ($j = 0; $j < $jmlResult; $j++) 
				{		 
				$hasilAkhir[$j] = array("c_barang_pinjam"           	=>(string)$result[$j]-> c_barang_pinjam,
							               "i_barang_pinjam" 			=>(string)$result[$j]-> i_barang_pinjam,				               
							               "n_barang"   			=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "n_barang_merktype" 		=>(string)$result[$j]-> n_barang_merktype,
										   "n_prsh"   				=>(string)$result[$j]-> n_prsh,
							               "d_barang_pinjamawal"		=>(string)$result[$j]-> d_barang_pinjamawal,
							               "d_barang_pinjamakhir"		=>(string)$result[$j]-> d_barang_pinjamakhir,
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
	
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	
	$namabarang = strtoupper($namabarang).'%';
	
	$kondisi[]=$namabarang;		   
	$where[]=$namabarang;		   
	$where[]=$namabarang;		   
		
	try 
	{
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		
		if(($pageNumber==0) && ($itemPerPage==0))
		 {
			$hasilAkhir = $db->fetchOne("SELECT count(*)
										FROM e_ast_barangpinjam_0_tr A,
										E_ast_transaksipinjam_0_tm B
										where B.c_barang_pinjam = A.c_barang_pinjam	
										and B.d_barang_serahpinjam is not null
										and B.d_barang_pengembalian is null 
										and upper(i_orgb) like upper(?)",$kondisi);
		 }
		 else
		 {
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;		
			$result = $db->fetchAll("SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan, 
										a.n_barang_merktype, i_orgb,										
										f.a_prsh_jalan, 
										f.a_prsh_kota, f.i_prsh_telpon, f.i_prsh_fax										
										FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_rekanan_prsh_0_tm c, e_ast_vendor_0_tr D,
										e_rekanan_prsh_0_tm E, e_rekanan_almt_prsh_tm F
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = D.i_rekanan
										and D.i_rekanan_ref = C.i_rekanan
										and f.i_rekanan = e.i_rekanan
										and D.i_rekanan_ref = e.i_rekanan 
										and f.c_prsh_levelktr = e.c_prsh_levelktr
										and A.d_barang_serahpinjam is not null
										and A.d_barang_pengembalian is null 
										and upper(i_orgb) like upper(?)
										union all
										SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_rekanan as n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan,
										a.n_barang_merktype, i_orgb,
										a_rekanan as  a_prsh_jalan, 
										a_prsh_jalan as a_prsh_kota, i_rekanan_telp as i_prsh_telpon,
										'-' as i_prsh_fax
										FROM  E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_ast_vendor_0_tr C
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = C.i_rekanan
										and C.i_rekanan_ref ='--'
										and A.d_barang_serahpinjam is not null
										and A.d_barang_pengembalian is null 
										and upper(i_orgb) like upper(?)
										limit $xLimit offset $xOffset",$where);
		
			$jmlResult = count($result);	
				 
			
			if($jmlResult > 0)
			{
				for ($j = 0; $j < $jmlResult; $j++) 
				{		 
				$hasilAkhir[$j] = array("c_barang_pinjam"           	=>(string)$result[$j]-> c_barang_pinjam,
							               "i_barang_pinjam" 			=>(string)$result[$j]-> i_barang_pinjam,				               
							               "n_barang"   			=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "n_barang_merktype" 		=>(string)$result[$j]-> n_barang_merktype,
										   "n_prsh"   				=>(string)$result[$j]-> n_prsh,
							               "d_barang_pinjamawal"		=>(string)$result[$j]-> d_barang_pinjamawal,
							               "d_barang_pinjamakhir"		=>(string)$result[$j]-> d_barang_pinjamakhir,
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
		// Ina : awal : BArang yg sudah dipinjam saat dikembalikan oleh unit tidak bisa dipinjamkan lagi ke unit lain
		/**
		$BrgsewaArr = array("d_barang_pengembalian"		=>date("Y-m-d"),
					     "i_orgb" 					=> null,
					     "d_barang_serahpinjam"  		=> null);
		
		**/
		$BrgsewaArr = array("d_barang_pengembalian"		=>date("Y-m-d"));
		// Ina : awal : BArang yg sudah dipinjam saat dikembalikan oleh unit tidak bisa dipinjamkan lagi ke unit lain					
		                     
		//parameter getUpdateBrgsewa 		   
		$where[] = "c_barang_pinjam 	=  '".trim($data['c_barang_pinjam'])."'";
		$where[] = "i_barang_pinjam 	=  '".trim($data['i_barang_pinjam'])."'";
		
		$db->update('e_ast_transaksipinjam_0_tm',$BrgsewaArr,$where);
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
	$where[]=$unitkr;
	
	try 
	{
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		
			$result = $db->fetchAll("SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan, 
										a.n_barang_merktype, i_orgb,										
										f.a_prsh_jalan, 
										f.a_prsh_kota, f.i_prsh_telpon, f.i_prsh_fax										
										FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_rekanan_prsh_0_tm c, e_ast_vendor_0_tr D,
										e_rekanan_prsh_0_tm E, e_rekanan_almt_prsh_tm F
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = D.i_rekanan
										and D.i_rekanan_ref = C.i_rekanan
										and f.i_rekanan = e.i_rekanan
										and D.i_rekanan_ref = e.i_rekanan 
										and f.c_prsh_levelktr = e.c_prsh_levelktr
										and A.d_barang_serahpinjam is not null
										and A.d_barang_pengembalian is null 
										and upper(i_orgb) like upper(?)
										union all
										SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_rekanan as n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan,
										a.n_barang_merktype, i_orgb,
										a_rekanan as  a_prsh_jalan, 
										a_prsh_jalan as a_prsh_kota, i_rekanan_telp as i_prsh_telpon,
										'-' as i_prsh_fax
										FROM  E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_ast_vendor_0_tr C
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = C.i_rekanan
										and C.i_rekanan_ref ='--'
										and A.d_barang_serahpinjam is not null
										and A.d_barang_pengembalian is null 
										and upper(i_orgb) like upper(?)",$where); 
		
			$jmlResult = count($result);	
			
			if($jmlResult > 0)
			{
				for ($j = 0; $j < $jmlResult; $j++) 
				{		 
				$hasilAkhir[$j] = array("c_barang_pinjam"           	=>(string)$result[$j]-> c_barang_pinjam,
							               "i_barang_pinjam" 			=>(string)$result[$j]-> i_barang_pinjam,				               
							               "n_barang"   			=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "n_barang_merktype" 		=>(string)$result[$j]-> n_barang_merktype,
										   "n_prsh"   				=>(string)$result[$j]-> n_prsh,
							               "d_barang_pinjamawal"		=>(string)$result[$j]-> d_barang_pinjamawal,
							               "d_barang_pinjamakhir"		=>(string)$result[$j]-> d_barang_pinjamakhir,
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

public function getKembalisewaSuppList($namaBarang)  
{
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	
	$namaBarang = strtoupper($namaBarang).'%';
	$where[]=$namaBarang;
	$where[]=$namaBarang;
	
	try 
	{
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		
			$result = $db->fetchAll("SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan, 
										a.n_barang_merktype, i_orgb,										
										f.a_prsh_jalan, 
										f.a_prsh_kota, f.i_prsh_telpon, f.i_prsh_fax										
										FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_rekanan_prsh_0_tm c, e_ast_vendor_0_tr D,
										e_rekanan_prsh_0_tm E, e_rekanan_almt_prsh_tm F
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = D.i_rekanan
										and D.i_rekanan_ref = C.i_rekanan
										and f.i_rekanan = e.i_rekanan
										and D.i_rekanan_ref = e.i_rekanan 
										and f.c_prsh_levelktr = e.c_prsh_levelktr
										and ( (A.d_barang_pengembalian is not null and A.d_pengembalian_pinjam is null) 
										     or  (A.d_barang_serahpinjam is null and A.d_pengembalian_pinjam is null)
											)  																			
										and upper(C.n_prsh) like upper(?)
										union all
										SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_rekanan as n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan,
										a.n_barang_merktype, i_orgb,
										a_rekanan as  a_prsh_jalan, 
										a_prsh_jalan as a_prsh_kota, i_rekanan_telp as i_prsh_telpon,
										'-' as i_prsh_fax
										FROM  E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_ast_vendor_0_tr C
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = C.i_rekanan
										and C.i_rekanan_ref ='--'										
										and ( (A.d_barang_pengembalian is not null and A.d_pengembalian_pinjam is null) 
										     or  (A.d_barang_serahpinjam is null and A.d_pengembalian_pinjam is null)
											)  																			
										and upper(C.n_rekanan) like upper(?)",$where); 
			
			
			
			
			$jmlResult = count($result);	
			
			if($jmlResult > 0)
			{
				for ($j = 0; $j < $jmlResult; $j++) 
				{		 
				$hasilAkhir[$j] = array("c_barang_pinjam"           	=>(string)$result[$j]-> c_barang_pinjam,
							               "i_barang_pinjam" 			=>(string)$result[$j]-> i_barang_pinjam,				               
							               "n_barang"   			=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "n_barang_merktype" 		=>(string)$result[$j]-> n_barang_merktype,
										   "n_prsh"   				=>(string)$result[$j]-> n_prsh,
							               "d_barang_pinjamawal"		=>(string)$result[$j]-> d_barang_pinjamawal,
							               "d_barang_pinjamakhir"		=>(string)$result[$j]-> d_barang_pinjamakhir,
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
	$rekanan = strtoupper($data['n_rekanan_penerima']);
	
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');	
	try 
	{
		$db->beginTransaction();
		$BrgsewaArr = array("n_rekanan_penerima"		=>$rekanan,
		                    "d_pengembalian_pinjam"	=>date("Y-m-d"));
		
		//parameter getUpdateBrgsewa 		   
		$where[] = "c_barang_pinjam 	=  '".trim($data['c_barang_pinjam'])."'";
		$where[] = "i_barang_pinjam 	=  '".trim($data['i_barang_pinjam'])."'";
		
		$db->update('e_ast_transaksipinjam_0_tm',$BrgsewaArr,$where);
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
	
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	
	$namabarang = strtoupper($namabarang).'%';
	
	$kondisi[]=$namabarang;		   
	$where[]=$namabarang;
	$where[]=$namabarang;
		
	try 
	{
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		
		if(($pageNumber==0) && ($itemPerPage==0))
		 {						
			$result = $db->fetchAll("SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan, 
										a.n_barang_merktype, i_orgb,										
										f.a_prsh_jalan, 
										f.a_prsh_kota, f.i_prsh_telpon, f.i_prsh_fax										
										FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_rekanan_prsh_0_tm c, e_ast_vendor_0_tr D,
										e_rekanan_prsh_0_tm E, e_rekanan_almt_prsh_tm F
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = D.i_rekanan
										and D.i_rekanan_ref = C.i_rekanan
										and f.i_rekanan = e.i_rekanan
										and D.i_rekanan_ref = e.i_rekanan 
										and f.c_prsh_levelktr = e.c_prsh_levelktr										
										and (    (A.d_barang_pengembalian is not null and A.d_pengembalian_pinjam is null) 
										     or  (A.d_barang_serahpinjam is null and A.d_pengembalian_pinjam is null )
											)  																			
										and upper(C.n_prsh) like upper(?)
										union all
										SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_rekanan as n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan,
										a.n_barang_merktype, i_orgb,
										a_rekanan as  a_prsh_jalan, 
										a_prsh_jalan as a_prsh_kota, i_rekanan_telp as i_prsh_telpon,
										'-' as i_prsh_fax
										FROM  E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_ast_vendor_0_tr C
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = C.i_rekanan
										and C.i_rekanan_ref ='--'										
										and ( (A.d_barang_pengembalian is not null and A.d_pengembalian_pinjam is null) 
										      or  (A.d_barang_serahpinjam is null and A.d_pengembalian_pinjam is null )
											)  																			
										and upper(C.n_rekanan) like upper(?)",$where);
		
			$hasilAkhir = count($result);				
										
		 }
		 else
		 {
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;		
			$result = $db->fetchAll("SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan, 
										a.n_barang_merktype, i_orgb,										
										f.a_prsh_jalan, 
										f.a_prsh_kota, f.i_prsh_telpon, f.i_prsh_fax										
										FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_rekanan_prsh_0_tm c, e_ast_vendor_0_tr D,
										e_rekanan_prsh_0_tm E, e_rekanan_almt_prsh_tm F
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = D.i_rekanan
										and D.i_rekanan_ref = C.i_rekanan
										and f.i_rekanan = e.i_rekanan
										and D.i_rekanan_ref = e.i_rekanan 
										and f.c_prsh_levelktr = e.c_prsh_levelktr										
										and ( (A.d_barang_pengembalian is not null and A.d_pengembalian_pinjam is null) 
										     or  (A.d_barang_serahpinjam is null and A.d_pengembalian_pinjam is null)
											)  																			
										and upper(C.n_prsh) like upper(?)
										union all
										SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_rekanan as n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan,
										a.n_barang_merktype, i_orgb,
										a_rekanan as  a_prsh_jalan, 
										a_prsh_jalan as a_prsh_kota, i_rekanan_telp as i_prsh_telpon,
										'-' as i_prsh_fax
										FROM  E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_ast_vendor_0_tr C
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = C.i_rekanan
										and C.i_rekanan_ref ='--'										
										and ( (A.d_barang_pengembalian is not null and A.d_pengembalian_pinjam is null) 
										     or  (A.d_barang_serahpinjam is null and A.d_pengembalian_pinjam is null)
											)  																			
										and upper(C.n_rekanan) like upper(?)
										limit $xLimit offset $xOffset",$where);
		
			$jmlResult = count($result);	
			
			
			if($jmlResult > 0)
			{
				for ($j = 0; $j < $jmlResult; $j++) 
				{		 
				$hasilAkhir[$j] = array("c_barang_pinjam"           	=>(string)$result[$j]-> c_barang_pinjam,
							               "i_barang_pinjam" 			=>(string)$result[$j]-> i_barang_pinjam,				               
							               "n_barang"   			=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "n_barang_merktype" 		=>(string)$result[$j]-> n_barang_merktype,
										   "n_prsh"   				=>(string)$result[$j]-> n_prsh,
							               "d_barang_pinjamawal"		=>(string)$result[$j]-> d_barang_pinjamawal,
							               "d_barang_pinjamakhir"		=>(string)$result[$j]-> d_barang_pinjamakhir,
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
	
	
	public function getListPejabatEselon_3_4($pageNumber,$itemPerPage,$nmPegawai) {
		$nmPegawai = strtoupper($nmPegawai);
		$npeg = '%'.$nmPegawai.'%';
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$where[] = $npeg;			
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 if(($pageNumber==0) && ($itemPerPage==0))
			 {
				$hasil = $db->fetchAll("SELECT a.i_peg_nip,a.n_peg,c.n_jabatan,a.c_unit_kerja
										FROM e_sdm_pegawai_0_tm A, e_sdm_jabatan_0_tm B, e_jabatan_0_0_tm C
										where 
										a.i_peg_nip = b.i_peg_nip
										and a.c_unit_kerja = b.c_jabatan
										and c.c_jabatan = b.c_jabatan
										and (a.c_eselon like 'III%' or a.c_eselon like 'IV%')
										and upper(n_peg) like ?",$where);
			 $hasilAkhir=count($hasil);
			  
			 }
			 
			 else
			 {
			
			 $xLimit=$itemPerPage;
			 $xOffset=($pageNumber-1)*$itemPerPage;		
			 $result = $db->fetchAll("SELECT a.i_peg_nip,a.n_peg,c.n_jabatan,a.c_unit_kerja
										FROM e_sdm_pegawai_0_tm A, e_sdm_jabatan_0_tm B, e_jabatan_0_0_tm C
										where 
										a.i_peg_nip = b.i_peg_nip
										and a.c_unit_kerja = b.c_jabatan
										and c.c_jabatan = b.c_jabatan
										and (a.c_eselon like 'III%' or a.c_eselon like 'IV%')
										and upper(n_peg) like ?
									order by n_peg 
									limit $xLimit offset $xOffset",$where); 
			 
			 $jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_peg_nip"         =>(string)$result[$j]->i_peg_nip,
									"n_peg"           	 	=>(string)$result[$j]->n_peg,
									"n_jabatan"          	=>(string)$result[$j]->n_jabatan,
									"c_unit_kerja"			=>(string)$result[$j]->c_unit_kerja);
			 }	
		}	 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	   
	
	// ina : 13-11-2008 :Awal
	public function getDataPejabatByUnit($unitkr) {
       $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try { 
           $where[] = $unitkr;		   
		   $db->setFetchMode(Zend_Db::FETCH_OBJ);
           $result = $db->fetchAll("select b.i_peg_nip, b.n_peg, n_orgb, a.n_jabatan
									from e_sdm_jabatan_0_tm A,  e_sdm_pegawai_0_tm B, e_org_0_0_tm C
									where a.i_peg_nip = b.i_peg_nip
									and  b.c_unit_kerja = a.c_jabatan
									and  b.c_unit_kerja = C.i_orgb
									and b.c_unit_kerja = ?",$where);  
       		
			
			$jmlResult = count($result);
			 
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_peg_nip"         =>(string)$result[$j]->i_peg_nip,
									"n_peg"           	 	=>(string)$result[$j]->n_peg,
									"n_jabatan"          	=>(string)$result[$j]->n_jabatan,
									"n_orgb"				=>(string)$result[$j]->n_orgb);
							
			 }	
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	} 		
	
	public function getNamaPegawaiByNip($nip) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			//$where[] = $nip;
			$query="select B.i_peg_nip, n_peg, n_orgb , A.n_jabatan
					from e_sdm_jabatan_0_tm A,  e_sdm_pegawai_0_tm B, e_org_0_0_tm C
					where a.i_peg_nip = b.i_peg_nip
					and b.c_unit_kerja = a.c_jabatan
					and b.c_unit_kerja = c.i_orgb
					and b.i_peg_nip = '$nip'
					union
					select a.i_peg_nip, n_peg,n_orgb,  NULL 
					from  e_sdm_pegawai_0_tm A,  e_org_0_0_tm C
					where a.c_unit_kerja = c.i_orgb
					and a.i_peg_nip = '$nip'
					and not EXISTS(select * from  e_sdm_jabatan_0_tm B
					               where a.i_peg_nip = b.i_peg_nip
								   and a.i_peg_nip = '$nip')
					";
			$result = $db->fetchAll($query);
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
	  			$hasilAkhir[$j] = array("i_peg_nip"  				=>(string)$result[$j]->i_peg_nip,
									"n_peg"					=>(string)$result[$j]->n_peg,
									"n_jabatan"  				=>(string)$result[$j]->n_jabatan,									
									"n_orgb"  				=>(string)$result[$j]->n_orgb
									);
			}
			return $hasilAkhir;
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}
	// Ina : 13-11-2008 : Akhir
	// Ina : 14-11-2008 : Awal
	public function getMelihatBrgsewaList_BelumDistribusi($pageNumber, $itemPerPage,$unitkr) 
	{
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("SELECT count(*)
											FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b 
											where a.c_barang_pinjam=b.c_barang_pinjam
											and d_barang_serahpinjam is null"); 
										 
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				$result = $db->fetchAll("SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan, 
										a.n_barang_merktype, i_orgb,										
										f.a_prsh_jalan, 
										f.a_prsh_kota, f.i_prsh_telpon, f.i_prsh_fax,
										i_peg_nipterima1, d_barang_serahpinjam, d_barang_pengembalian, d_pengembalian_pinjam
										FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_rekanan_prsh_0_tm c, e_ast_vendor_0_tr D,
										e_rekanan_prsh_0_tm E, e_rekanan_almt_prsh_tm F
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = D.i_rekanan
										and D.i_rekanan_ref = C.i_rekanan
										and f.i_rekanan = e.i_rekanan
										and D.i_rekanan_ref = e.i_rekanan 
										and f.c_prsh_levelktr = e.c_prsh_levelktr
										and d_barang_serahpinjam is null
										union all
										SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_rekanan as n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan,
										a.n_barang_merktype, i_orgb,
										a_rekanan as  a_prsh_jalan, 
										a_prsh_jalan as a_prsh_kota, i_rekanan_telp as i_prsh_telpon,
										'-' as i_prsh_fax,
										i_peg_nipterima1, d_barang_serahpinjam, d_barang_pengembalian, d_pengembalian_pinjam
										FROM  E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_ast_vendor_0_tr C
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = C.i_rekanan
										and C.i_rekanan_ref ='--'
										and d_barang_serahpinjam is null
										limit $xLimit offset $xOffset"); 
		
				$jmlResult = count($result);			
			
				if($jmlResult > 0)
				{
				for ($j = 0; $j < $jmlResult; $j++) 
					{		 
					    
						$n_peg 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nipterima1);	
														
						
						$hasilAkhir[$j] = array("c_barang_pinjam"          	=>(string)$result[$j]-> c_barang_pinjam,
							               "i_barang_pinjam"			=>(string)$result[$j]-> i_barang_pinjam,
							               "n_barang" 				=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "i_rekanan"   			=>(string)$result[$j]-> i_rekanan,
										   "n_prsh"   			=>(string)$result[$j]-> n_prsh,
							               "d_barang_pinjamawal" 		=>(string)$result[$j]-> d_barang_pinjamawal,
										   "d_barang_pinjamakhir" 	=>(string)$result[$j]-> d_barang_pinjamakhir,
							               "e_keterangan"			=>(string)$result[$j]-> e_keterangan,
										   "i_orgb"					=>(string)$result[$j]-> i_orgb,
										   "n_peg"					=>$n_peg[0],
										   "d_distribusi"					=>(string)$result[$j]-> d_barang_serahpinjam,
										   "d_pengembalian_unit"					=>(string)$result[$j]-> d_barang_pengembalian,
										   "d_pengembalian_rekanan"					=>(string)$result[$j]-> d_pengembalian_pinjam,
										   "i_peg_nipterima"					=>(string)$result[$j]-> i_peg_nipterima1,
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
	
	public function getMelihatBrgsewaList_BelumDistribusi_Cetak($unitkr) 
	{
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
				$result = $db->fetchAll("SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan, 
										a.n_barang_merktype, i_orgb,										
										f.a_prsh_jalan, 
										f.a_prsh_kota, f.i_prsh_telpon, f.i_prsh_fax,
										i_peg_nipterima1, d_barang_serahpinjam, d_barang_pengembalian, d_pengembalian_pinjam
										FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_rekanan_prsh_0_tm c, e_ast_vendor_0_tr D,
										e_rekanan_prsh_0_tm E, e_rekanan_almt_prsh_tm F
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = D.i_rekanan
										and D.i_rekanan_ref = C.i_rekanan
										and f.i_rekanan = e.i_rekanan
										and D.i_rekanan_ref = e.i_rekanan 
										and f.c_prsh_levelktr = e.c_prsh_levelktr
										and d_barang_serahpinjam is null
										union all
										SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_rekanan as n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan,
										a.n_barang_merktype, i_orgb,
										a_rekanan as  a_prsh_jalan, 
										a_prsh_jalan as a_prsh_kota, i_rekanan_telp as i_prsh_telpon,
										'-' as i_prsh_fax,
										i_peg_nipterima1, d_barang_serahpinjam, d_barang_pengembalian, d_pengembalian_pinjam
										FROM  E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_ast_vendor_0_tr C
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = C.i_rekanan
										and C.i_rekanan_ref ='--'
										and d_barang_serahpinjam is null"); 
		
				$jmlResult = count($result);			
			
				if($jmlResult > 0)
				{
				for ($j = 0; $j < $jmlResult; $j++) 
					{		 
					    
						$n_peg 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nipterima1);	
														
						
						$hasilAkhir[$j] = array("c_barang_pinjam"          	=>(string)$result[$j]-> c_barang_pinjam,
							               "i_barang_pinjam"			=>(string)$result[$j]-> i_barang_pinjam,
							               "n_barang" 				=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "i_rekanan"   			=>(string)$result[$j]-> i_rekanan,
										   "n_prsh"   			=>(string)$result[$j]-> n_prsh,
							               "d_barang_pinjamawal" 		=>(string)$result[$j]-> d_barang_pinjamawal,
										   "d_barang_pinjamakhir" 	=>(string)$result[$j]-> d_barang_pinjamakhir,
							               "e_keterangan"			=>(string)$result[$j]-> e_keterangan,
										   "i_orgb"					=>(string)$result[$j]-> i_orgb,
										   "n_peg"					=>$n_peg[0],
										   "d_distribusi"					=>(string)$result[$j]-> d_barang_serahpinjam,
										   "d_pengembalian_unit"					=>(string)$result[$j]-> d_barang_pengembalian,
										   "d_pengembalian_rekanan"					=>(string)$result[$j]-> d_pengembalian_pinjam,
										   "i_peg_nipterima"					=>(string)$result[$j]-> i_peg_nipterima1,
										   "n_barang_merktype"		=>(string)$result[$j]-> n_barang_merktype);			
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
	
	public function getMelihatBrgsewaList_BelumKembaliUser($pageNumber, $itemPerPage,$unitkr) 
	{
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("SELECT count(*)
											FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b 
											where a.c_barang_pinjam=b.c_barang_pinjam
											and d_barang_serahpinjam is not null
											and d_barang_pengembalian is null"); 
										 
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				$result = $db->fetchAll("SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan, 
										a.n_barang_merktype, i_orgb,										
										f.a_prsh_jalan, 
										f.a_prsh_kota, f.i_prsh_telpon, f.i_prsh_fax,
										i_peg_nipterima1, d_barang_serahpinjam, d_barang_pengembalian, d_pengembalian_pinjam
										FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_rekanan_prsh_0_tm c, e_ast_vendor_0_tr D,
										e_rekanan_prsh_0_tm E, e_rekanan_almt_prsh_tm F
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = D.i_rekanan
										and D.i_rekanan_ref = C.i_rekanan
										and f.i_rekanan = e.i_rekanan
										and D.i_rekanan_ref = e.i_rekanan 
										and f.c_prsh_levelktr = e.c_prsh_levelktr
										and d_barang_serahpinjam is not null
										and d_barang_pengembalian is null
										union all
										SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_rekanan as n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan,
										a.n_barang_merktype, i_orgb,
										a_rekanan as  a_prsh_jalan, 
										a_prsh_jalan as a_prsh_kota, i_rekanan_telp as i_prsh_telpon,
										'-' as i_prsh_fax,
										i_peg_nipterima1, d_barang_serahpinjam, d_barang_pengembalian, d_pengembalian_pinjam
										FROM  E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_ast_vendor_0_tr C
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = C.i_rekanan
										and C.i_rekanan_ref ='--'
										and d_barang_serahpinjam is not null
										and d_barang_pengembalian is null
										limit $xLimit offset $xOffset"); 
		
				$jmlResult = count($result);			
			
				if($jmlResult > 0)
				{
				for ($j = 0; $j < $jmlResult; $j++) 
					{		 
					    $n_peg 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nipterima1);	
						
						$hasilAkhir[$j] = array("c_barang_pinjam"          	=>(string)$result[$j]-> c_barang_pinjam,
							               "i_barang_pinjam"			=>(string)$result[$j]-> i_barang_pinjam,
							               "n_barang" 				=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "i_rekanan"   			=>(string)$result[$j]-> i_rekanan,
										   "n_prsh"   			=>(string)$result[$j]-> n_prsh,
							               "d_barang_pinjamawal" 		=>(string)$result[$j]-> d_barang_pinjamawal,
										   "d_barang_pinjamakhir" 	=>(string)$result[$j]-> d_barang_pinjamakhir,
							               "e_keterangan"			=>(string)$result[$j]-> e_keterangan,
										   "i_orgb"					=>(string)$result[$j]-> i_orgb,
										   "n_peg"					=>$n_peg[0],
										   "d_distribusi"					=>(string)$result[$j]-> d_barang_serahpinjam,
										   "d_pengembalian_unit"					=>(string)$result[$j]-> d_barang_pengembalian,
										   "d_pengembalian_rekanan"					=>(string)$result[$j]-> d_pengembalian_pinjam,
										   "i_peg_nipterima"					=>(string)$result[$j]-> i_peg_nipterima1,
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
	
	public function getMelihatBrgsewaList_BelumKembaliUser_Cetak($unitkr) 
	{
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
			$result = $db->fetchAll("SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan, 
										a.n_barang_merktype, i_orgb,										
										f.a_prsh_jalan, 
										f.a_prsh_kota, f.i_prsh_telpon, f.i_prsh_fax,
										i_peg_nipterima1, d_barang_serahpinjam, d_barang_pengembalian, d_pengembalian_pinjam
										FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_rekanan_prsh_0_tm c, e_ast_vendor_0_tr D,
										e_rekanan_prsh_0_tm E, e_rekanan_almt_prsh_tm F
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = D.i_rekanan
										and D.i_rekanan_ref = C.i_rekanan
										and f.i_rekanan = e.i_rekanan
										and D.i_rekanan_ref = e.i_rekanan 
										and f.c_prsh_levelktr = e.c_prsh_levelktr
										and d_barang_serahpinjam is not null
										and d_barang_pengembalian is null
										union all
										SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_rekanan as n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan,
										a.n_barang_merktype, i_orgb,
										a_rekanan as  a_prsh_jalan, 
										a_prsh_jalan as a_prsh_kota, i_rekanan_telp as i_prsh_telpon,
										'-' as i_prsh_fax,
										i_peg_nipterima1, d_barang_serahpinjam, d_barang_pengembalian, d_pengembalian_pinjam
										FROM  E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_ast_vendor_0_tr C
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = C.i_rekanan
										and C.i_rekanan_ref ='--'
										and d_barang_serahpinjam is not null
										and d_barang_pengembalian is null"); 
		
				$jmlResult = count($result);			
			
				if($jmlResult > 0)
				{
				for ($j = 0; $j < $jmlResult; $j++) 
					{		 
					    $n_peg 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nipterima1);	
						
						$hasilAkhir[$j] = array("c_barang_pinjam"          	=>(string)$result[$j]-> c_barang_pinjam,
							               "i_barang_pinjam"			=>(string)$result[$j]-> i_barang_pinjam,
							               "n_barang" 				=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "i_rekanan"   			=>(string)$result[$j]-> i_rekanan,
										   "n_prsh"   			=>(string)$result[$j]-> n_prsh,
							               "d_barang_pinjamawal" 		=>(string)$result[$j]-> d_barang_pinjamawal,
										   "d_barang_pinjamakhir" 	=>(string)$result[$j]-> d_barang_pinjamakhir,
							               "e_keterangan"			=>(string)$result[$j]-> e_keterangan,
										   "i_orgb"					=>(string)$result[$j]-> i_orgb,
										   "n_peg"					=>$n_peg[0],
										   "d_distribusi"					=>(string)$result[$j]-> d_barang_serahpinjam,
										   "d_pengembalian_unit"					=>(string)$result[$j]-> d_barang_pengembalian,
										   "d_pengembalian_rekanan"					=>(string)$result[$j]-> d_pengembalian_pinjam,
										   "i_peg_nipterima"					=>(string)$result[$j]-> i_peg_nipterima1,
										   "n_barang_merktype"		=>(string)$result[$j]-> n_barang_merktype);			
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
	
	public function getMelihatBrgsewaList_SudahKembaliUser($pageNumber, $itemPerPage,$unitkr) 
	{
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("SELECT count(*)
											FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b 
											where a.c_barang_pinjam=b.c_barang_pinjam
											and d_barang_pengembalian is not null"); 
										 
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				$result = $db->fetchAll("SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan, 
										a.n_barang_merktype, i_orgb,										
										f.a_prsh_jalan, 
										f.a_prsh_kota, f.i_prsh_telpon, f.i_prsh_fax,
										i_peg_nipterima1, d_barang_serahpinjam, d_barang_pengembalian, d_pengembalian_pinjam
										FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_rekanan_prsh_0_tm c, e_ast_vendor_0_tr D,
										e_rekanan_prsh_0_tm E, e_rekanan_almt_prsh_tm F
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = D.i_rekanan
										and D.i_rekanan_ref = C.i_rekanan
										and f.i_rekanan = e.i_rekanan
										and D.i_rekanan_ref = e.i_rekanan 
										and f.c_prsh_levelktr = e.c_prsh_levelktr
										and d_barang_pengembalian is not null
										union all
										SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_rekanan as n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan,
										a.n_barang_merktype, i_orgb,
										a_rekanan as  a_prsh_jalan, 
										a_prsh_jalan as a_prsh_kota, i_rekanan_telp as i_prsh_telpon,
										'-' as i_prsh_fax,
										i_peg_nipterima1, d_barang_serahpinjam, d_barang_pengembalian, d_pengembalian_pinjam
										FROM  E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_ast_vendor_0_tr C
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = C.i_rekanan
										and C.i_rekanan_ref ='--'
										and d_barang_pengembalian is not null
										limit $xLimit offset $xOffset"); 
		
				$jmlResult = count($result);			
			
				if($jmlResult > 0)
				{
				for ($j = 0; $j < $jmlResult; $j++) 
					{		 
					    $n_peg 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nipterima1);	
														
						$hasilAkhir[$j] = array("c_barang_pinjam"          	=>(string)$result[$j]-> c_barang_pinjam,
							               "i_barang_pinjam"			=>(string)$result[$j]-> i_barang_pinjam,
							               "n_barang" 				=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "i_rekanan"   			=>(string)$result[$j]-> i_rekanan,
										   "n_prsh"   			=>(string)$result[$j]-> n_prsh,
							               "d_barang_pinjamawal" 		=>(string)$result[$j]-> d_barang_pinjamawal,
										   "d_barang_pinjamakhir" 	=>(string)$result[$j]-> d_barang_pinjamakhir,
							               "e_keterangan"			=>(string)$result[$j]-> e_keterangan,
										   "i_orgb"					=>(string)$result[$j]-> i_orgb,
										   "n_peg"					=>$n_peg[0],
										   "d_distribusi"					=>(string)$result[$j]-> d_barang_serahpinjam,
										   "d_pengembalian_unit"					=>(string)$result[$j]-> d_barang_pengembalian,
										   "d_pengembalian_rekanan"					=>(string)$result[$j]-> d_pengembalian_pinjam,
										   "i_peg_nipterima"					=>(string)$result[$j]-> i_peg_nipterima1,
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
	
	public function getMelihatBrgsewaList_SudahKembaliUser_Cetak($unitkr) 
	{
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
			
			
				$result = $db->fetchAll("SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan, 
										a.n_barang_merktype, i_orgb,										
										f.a_prsh_jalan, 
										f.a_prsh_kota, f.i_prsh_telpon, f.i_prsh_fax,
										i_peg_nipterima1, d_barang_serahpinjam, d_barang_pengembalian, d_pengembalian_pinjam
										FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_rekanan_prsh_0_tm c, e_ast_vendor_0_tr D,
										e_rekanan_prsh_0_tm E, e_rekanan_almt_prsh_tm F
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = D.i_rekanan
										and D.i_rekanan_ref = C.i_rekanan
										and f.i_rekanan = e.i_rekanan
										and D.i_rekanan_ref = e.i_rekanan 
										and f.c_prsh_levelktr = e.c_prsh_levelktr
										and d_barang_pengembalian is not null
										union all
										SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_rekanan as n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan,
										a.n_barang_merktype, i_orgb,
										a_rekanan as  a_prsh_jalan, 
										a_prsh_jalan as a_prsh_kota, i_rekanan_telp as i_prsh_telpon,
										'-' as i_prsh_fax,
										i_peg_nipterima1, d_barang_serahpinjam, d_barang_pengembalian, d_pengembalian_pinjam
										FROM  E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_ast_vendor_0_tr C
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = C.i_rekanan
										and C.i_rekanan_ref ='--'
										and d_barang_pengembalian is not null"); 
		
				$jmlResult = count($result);			
			
				if($jmlResult > 0)
				{
				for ($j = 0; $j < $jmlResult; $j++) 
					{		 
					    $n_peg 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nipterima1);	
														
						$hasilAkhir[$j] = array("c_barang_pinjam"          	=>(string)$result[$j]-> c_barang_pinjam,
							               "i_barang_pinjam"			=>(string)$result[$j]-> i_barang_pinjam,
							               "n_barang" 				=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "i_rekanan"   			=>(string)$result[$j]-> i_rekanan,
										   "n_prsh"   			=>(string)$result[$j]-> n_prsh,
							               "d_barang_pinjamawal" 		=>(string)$result[$j]-> d_barang_pinjamawal,
										   "d_barang_pinjamakhir" 	=>(string)$result[$j]-> d_barang_pinjamakhir,
							               "e_keterangan"			=>(string)$result[$j]-> e_keterangan,
										   "i_orgb"					=>(string)$result[$j]-> i_orgb,
										   "n_peg"					=>$n_peg[0],
										   "d_distribusi"					=>(string)$result[$j]-> d_barang_serahpinjam,
										   "d_pengembalian_unit"					=>(string)$result[$j]-> d_barang_pengembalian,
										   "d_pengembalian_rekanan"					=>(string)$result[$j]-> d_pengembalian_pinjam,
										   "i_peg_nipterima"					=>(string)$result[$j]-> i_peg_nipterima1,
										   "n_barang_merktype"		=>(string)$result[$j]-> n_barang_merktype);			
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
	
	public function getMelihatBrgsewaList_BelumKembaliRekanan($pageNumber, $itemPerPage,$unitkr) 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("SELECT count(*)
											FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b 
											where a.c_barang_pinjam=b.c_barang_pinjam
											and d_pengembalian_pinjam is null"); 
										 
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				$result = $db->fetchAll("SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan, 
										a.n_barang_merktype, i_orgb,										
										f.a_prsh_jalan, 
										f.a_prsh_kota, f.i_prsh_telpon, f.i_prsh_fax,
										i_peg_nipterima1, d_barang_serahpinjam, d_barang_pengembalian, d_pengembalian_pinjam
										FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_rekanan_prsh_0_tm c, e_ast_vendor_0_tr D,
										e_rekanan_prsh_0_tm E, e_rekanan_almt_prsh_tm F
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = D.i_rekanan
										and D.i_rekanan_ref = C.i_rekanan
										and f.i_rekanan = e.i_rekanan
										and D.i_rekanan_ref = e.i_rekanan 
										and f.c_prsh_levelktr = e.c_prsh_levelktr
										and d_pengembalian_pinjam is null
										union all
										SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_rekanan as n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan,
										a.n_barang_merktype, i_orgb,
										a_rekanan as  a_prsh_jalan, 
										a_prsh_jalan as a_prsh_kota, i_rekanan_telp as i_prsh_telpon,
										'-' as i_prsh_fax,
										i_peg_nipterima1, d_barang_serahpinjam, d_barang_pengembalian, d_pengembalian_pinjam
										FROM  E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_ast_vendor_0_tr C
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = C.i_rekanan
										and C.i_rekanan_ref ='--'
										and d_pengembalian_pinjam is null
										limit $xLimit offset $xOffset"); 
		
				$jmlResult = count($result);			
			
				if($jmlResult > 0)
				{
				for ($j = 0; $j < $jmlResult; $j++) 
					{		 
					    $n_peg 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nipterima1);	
														
						$hasilAkhir[$j] = array("c_barang_pinjam"          	=>(string)$result[$j]-> c_barang_pinjam,
							               "i_barang_pinjam"			=>(string)$result[$j]-> i_barang_pinjam,
							               "n_barang" 				=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "i_rekanan"   			=>(string)$result[$j]-> i_rekanan,
										   "n_prsh"   			=>(string)$result[$j]-> n_prsh,
							               "d_barang_pinjamawal" 		=>(string)$result[$j]-> d_barang_pinjamawal,
										   "d_barang_pinjamakhir" 	=>(string)$result[$j]-> d_barang_pinjamakhir,
							               "e_keterangan"			=>(string)$result[$j]-> e_keterangan,
										   "i_orgb"					=>(string)$result[$j]-> i_orgb,
										   "n_peg"					=>$n_peg[0],
										   "d_distribusi"					=>(string)$result[$j]-> d_barang_serahpinjam,
										   "d_pengembalian_unit"					=>(string)$result[$j]-> d_barang_pengembalian,
										   "d_pengembalian_rekanan"					=>(string)$result[$j]-> d_pengembalian_pinjam,
										   "i_peg_nipterima"					=>(string)$result[$j]-> i_peg_nipterima1,
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
	
	public function getMelihatBrgsewaList_BelumKembaliRekanan_Cetak($unitkr) 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
				$result = $db->fetchAll("SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan, 
										a.n_barang_merktype, i_orgb,										
										f.a_prsh_jalan, 
										f.a_prsh_kota, f.i_prsh_telpon, f.i_prsh_fax,
										i_peg_nipterima1, d_barang_serahpinjam, d_barang_pengembalian, d_pengembalian_pinjam
										FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_rekanan_prsh_0_tm c, e_ast_vendor_0_tr D,
										e_rekanan_prsh_0_tm E, e_rekanan_almt_prsh_tm F
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = D.i_rekanan
										and D.i_rekanan_ref = C.i_rekanan
										and f.i_rekanan = e.i_rekanan
										and D.i_rekanan_ref = e.i_rekanan 
										and f.c_prsh_levelktr = e.c_prsh_levelktr
										and d_pengembalian_pinjam is null
										union all
										SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_rekanan as n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan,
										a.n_barang_merktype, i_orgb,
										a_rekanan as  a_prsh_jalan, 
										a_prsh_jalan as a_prsh_kota, i_rekanan_telp as i_prsh_telpon,
										'-' as i_prsh_fax,
										i_peg_nipterima1, d_barang_serahpinjam, d_barang_pengembalian, d_pengembalian_pinjam
										FROM  E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_ast_vendor_0_tr C
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = C.i_rekanan
										and C.i_rekanan_ref ='--'
										and d_pengembalian_pinjam is null"); 
		
				$jmlResult = count($result);			
			
				if($jmlResult > 0)
				{
				for ($j = 0; $j < $jmlResult; $j++) 
					{		 
					    $n_peg 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nipterima1);	
														
						$hasilAkhir[$j] = array("c_barang_pinjam"          	=>(string)$result[$j]-> c_barang_pinjam,
							               "i_barang_pinjam"			=>(string)$result[$j]-> i_barang_pinjam,
							               "n_barang" 				=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "i_rekanan"   			=>(string)$result[$j]-> i_rekanan,
										   "n_prsh"   			=>(string)$result[$j]-> n_prsh,
							               "d_barang_pinjamawal" 		=>(string)$result[$j]-> d_barang_pinjamawal,
										   "d_barang_pinjamakhir" 	=>(string)$result[$j]-> d_barang_pinjamakhir,
							               "e_keterangan"			=>(string)$result[$j]-> e_keterangan,
										   "i_orgb"					=>(string)$result[$j]-> i_orgb,
										   "n_peg"					=>$n_peg[0],
										   "d_distribusi"					=>(string)$result[$j]-> d_barang_serahpinjam,
										   "d_pengembalian_unit"					=>(string)$result[$j]-> d_barang_pengembalian,
										   "d_pengembalian_rekanan"					=>(string)$result[$j]-> d_pengembalian_pinjam,
										   "i_peg_nipterima"					=>(string)$result[$j]-> i_peg_nipterima1,
										   "n_barang_merktype"		=>(string)$result[$j]-> n_barang_merktype);			
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
	
	public function getMelihatBrgsewaList_SudahKembaliRekanan($pageNumber, $itemPerPage,$unitkr) 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("SELECT count(*)
											FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b 
											where a.c_barang_pinjam=b.c_barang_pinjam
											and d_pengembalian_pinjam is not null"); 
										 
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				$result = $db->fetchAll("SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan, 
										a.n_barang_merktype, i_orgb,										
										f.a_prsh_jalan, 
										f.a_prsh_kota, f.i_prsh_telpon, f.i_prsh_fax,
										i_peg_nipterima1, d_barang_serahpinjam, d_barang_pengembalian, d_pengembalian_pinjam
										FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_rekanan_prsh_0_tm c, e_ast_vendor_0_tr D,
										e_rekanan_prsh_0_tm E, e_rekanan_almt_prsh_tm F
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = D.i_rekanan
										and D.i_rekanan_ref = C.i_rekanan
										and f.i_rekanan = e.i_rekanan
										and D.i_rekanan_ref = e.i_rekanan 
										and f.c_prsh_levelktr = e.c_prsh_levelktr
										and d_pengembalian_pinjam is not null
										union all
										SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_rekanan as n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan,
										a.n_barang_merktype, i_orgb,
										a_rekanan as  a_prsh_jalan, 
										a_prsh_jalan as a_prsh_kota, i_rekanan_telp as i_prsh_telpon,
										'-' as i_prsh_fax,
										i_peg_nipterima1, d_barang_serahpinjam, d_barang_pengembalian, d_pengembalian_pinjam
										FROM  E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_ast_vendor_0_tr C
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = C.i_rekanan
										and C.i_rekanan_ref ='--'
										and d_pengembalian_pinjam is not null
										limit $xLimit offset $xOffset"); 
		
				$jmlResult = count($result);			
			
				if($jmlResult > 0)
				{
				for ($j = 0; $j < $jmlResult; $j++) 
					{		 
					
					    $n_peg 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nipterima1);	
						
						$hasilAkhir[$j] = array("c_barang_pinjam"          	=>(string)$result[$j]-> c_barang_pinjam,
							               "i_barang_pinjam"			=>(string)$result[$j]-> i_barang_pinjam,
							               "n_barang" 				=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "i_rekanan"   			=>(string)$result[$j]-> i_rekanan,
										   "n_prsh"   			=>(string)$result[$j]-> n_prsh,
							               "d_barang_pinjamawal" 		=>(string)$result[$j]-> d_barang_pinjamawal,
										   "d_barang_pinjamakhir" 	=>(string)$result[$j]-> d_barang_pinjamakhir,
							               "e_keterangan"			=>(string)$result[$j]-> e_keterangan,
										   "i_orgb"					=>(string)$result[$j]-> i_orgb,
										   "n_peg"					=>$n_peg[0],
										   "d_distribusi"					=>(string)$result[$j]-> d_barang_serahpinjam,
										   "d_pengembalian_unit"					=>(string)$result[$j]-> d_barang_pengembalian,
										   "d_pengembalian_rekanan"					=>(string)$result[$j]-> d_pengembalian_pinjam,
										   "i_peg_nipterima"					=>(string)$result[$j]-> i_peg_nipterima1,
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
	
	
	public function getMelihatBrgsewaList_SudahKembaliRekanan_Cetak($unitkr) 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
				$result = $db->fetchAll("SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan, 
										a.n_barang_merktype, i_orgb,										
										f.a_prsh_jalan, 
										f.a_prsh_kota, f.i_prsh_telpon, f.i_prsh_fax,
										i_peg_nipterima1, d_barang_serahpinjam, d_barang_pengembalian, d_pengembalian_pinjam
										FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_rekanan_prsh_0_tm c, e_ast_vendor_0_tr D,
										e_rekanan_prsh_0_tm E, e_rekanan_almt_prsh_tm F
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = D.i_rekanan
										and D.i_rekanan_ref = C.i_rekanan
										and f.i_rekanan = e.i_rekanan
										and D.i_rekanan_ref = e.i_rekanan 
										and f.c_prsh_levelktr = e.c_prsh_levelktr
										and d_pengembalian_pinjam is not null
										union all
										SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_rekanan as n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan,
										a.n_barang_merktype, i_orgb,
										a_rekanan as  a_prsh_jalan, 
										a_prsh_jalan as a_prsh_kota, i_rekanan_telp as i_prsh_telpon,
										'-' as i_prsh_fax,
										i_peg_nipterima1, d_barang_serahpinjam, d_barang_pengembalian, d_pengembalian_pinjam
										FROM  E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_ast_vendor_0_tr C
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = C.i_rekanan
										and C.i_rekanan_ref ='--'
										and d_pengembalian_pinjam is not null"); 
		
				$jmlResult = count($result);			
			
				if($jmlResult > 0)
				{
				for ($j = 0; $j < $jmlResult; $j++) 
					{		 
					
					    $n_peg 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nipterima1);	
						
						$hasilAkhir[$j] = array("c_barang_pinjam"          	=>(string)$result[$j]-> c_barang_pinjam,
							               "i_barang_pinjam"			=>(string)$result[$j]-> i_barang_pinjam,
							               "n_barang" 				=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "i_rekanan"   			=>(string)$result[$j]-> i_rekanan,
										   "n_prsh"   			=>(string)$result[$j]-> n_prsh,
							               "d_barang_pinjamawal" 		=>(string)$result[$j]-> d_barang_pinjamawal,
										   "d_barang_pinjamakhir" 	=>(string)$result[$j]-> d_barang_pinjamakhir,
							               "e_keterangan"			=>(string)$result[$j]-> e_keterangan,
										   "i_orgb"					=>(string)$result[$j]-> i_orgb,
										   "n_peg"					=>$n_peg[0],
										   "d_distribusi"					=>(string)$result[$j]-> d_barang_serahpinjam,
										   "d_pengembalian_unit"					=>(string)$result[$j]-> d_barang_pengembalian,
										   "d_pengembalian_rekanan"					=>(string)$result[$j]-> d_pengembalian_pinjam,
										   "i_peg_nipterima"					=>(string)$result[$j]-> i_peg_nipterima1,
										   "n_barang_merktype"		=>(string)$result[$j]-> n_barang_merktype);			
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
	
	public function getMelihatBrgsewaList_Semua($pageNumber, $itemPerPage,$unitkr) 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$hasilAkhir = $db->fetchOne("SELECT count(*)
											FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b 
											where a.c_barang_pinjam=b.c_barang_pinjam"); 
										 
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				$result = $db->fetchAll("SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan, 
										a.n_barang_merktype, i_orgb,										
										f.a_prsh_jalan, 
										f.a_prsh_kota, f.i_prsh_telpon, f.i_prsh_fax,
										i_peg_nipterima1, d_barang_serahpinjam, d_barang_pengembalian, d_pengembalian_pinjam
										FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_rekanan_prsh_0_tm c, e_ast_vendor_0_tr D,
										e_rekanan_prsh_0_tm E, e_rekanan_almt_prsh_tm F
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = D.i_rekanan
										and D.i_rekanan_ref = C.i_rekanan
										and f.i_rekanan = e.i_rekanan
										and D.i_rekanan_ref = e.i_rekanan 
										and f.c_prsh_levelktr = e.c_prsh_levelktr										
										union all
										SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_rekanan as n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan,
										a.n_barang_merktype, i_orgb,
										a_rekanan as  a_prsh_jalan, 
										a_prsh_jalan as a_prsh_kota, i_rekanan_telp as i_prsh_telpon,
										'-' as i_prsh_fax,
										i_peg_nipterima1, d_barang_serahpinjam, d_barang_pengembalian, d_pengembalian_pinjam
										FROM  E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_ast_vendor_0_tr C
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = C.i_rekanan
										and C.i_rekanan_ref ='--'										
										limit $xLimit offset $xOffset"); 
		
				$jmlResult = count($result);			
			
				if($jmlResult > 0)
				{
				for ($j = 0; $j < $jmlResult; $j++) 
					{		 
					
					    $n_peg 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nipterima1);	
					
						
						$hasilAkhir[$j] = array("c_barang_pinjam"          	=>(string)$result[$j]-> c_barang_pinjam,
							               "i_barang_pinjam"			=>(string)$result[$j]-> i_barang_pinjam,
							               "n_barang" 				=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "i_rekanan"   			=>(string)$result[$j]-> i_rekanan,
										   "n_prsh"   			=>(string)$result[$j]-> n_prsh,
							               "d_barang_pinjamawal" 		=>(string)$result[$j]-> d_barang_pinjamawal,
										   "d_barang_pinjamakhir" 	=>(string)$result[$j]-> d_barang_pinjamakhir,
							               "e_keterangan"			=>(string)$result[$j]-> e_keterangan,
										   "i_orgb"					=>(string)$result[$j]-> i_orgb,
										   "n_peg"					=>$n_peg[0],
										   "d_distribusi"					=>(string)$result[$j]-> d_barang_serahpinjam,
										   "d_pengembalian_unit"					=>(string)$result[$j]-> d_barang_pengembalian,
										   "d_pengembalian_rekanan"					=>(string)$result[$j]-> d_pengembalian_pinjam,
										   "i_peg_nipterima"					=>(string)$result[$j]-> i_peg_nipterima1,
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
	
	public function getMelihatBrgsewaList_Semua_Cetak($unitkr) 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try 
		{
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
				$result = $db->fetchAll("SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan, 
										a.n_barang_merktype, i_orgb,										
										f.a_prsh_jalan, 
										f.a_prsh_kota, f.i_prsh_telpon, f.i_prsh_fax,
										i_peg_nipterima1, d_barang_serahpinjam, d_barang_pengembalian, d_pengembalian_pinjam
										FROM   E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_rekanan_prsh_0_tm c, e_ast_vendor_0_tr D,
										e_rekanan_prsh_0_tm E, e_rekanan_almt_prsh_tm F
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = D.i_rekanan
										and D.i_rekanan_ref = C.i_rekanan
										and f.i_rekanan = e.i_rekanan
										and D.i_rekanan_ref = e.i_rekanan 
										and f.c_prsh_levelktr = e.c_prsh_levelktr										
										union all
										SELECT a.c_barang_pinjam, a.i_barang_pinjam, b.n_barang, a.i_barang_serial, a.i_rekanan,
										c.n_rekanan as n_prsh, a.d_barang_pinjamawal, a.d_barang_pinjamakhir, a.e_keterangan,
										a.n_barang_merktype, i_orgb,
										a_rekanan as  a_prsh_jalan, 
										a_prsh_jalan as a_prsh_kota, i_rekanan_telp as i_prsh_telpon,
										'-' as i_prsh_fax,
										i_peg_nipterima1, d_barang_serahpinjam, d_barang_pengembalian, d_pengembalian_pinjam
										FROM  E_ast_transaksipinjam_0_tm a, e_ast_barangpinjam_0_tr b , 
										e_ast_vendor_0_tr C
										where a.c_barang_pinjam=b.c_barang_pinjam 
										and a.i_rekanan = C.i_rekanan
										and C.i_rekanan_ref ='--'"); 
		
				$jmlResult = count($result);			
			
				if($jmlResult > 0)
				{
				for ($j = 0; $j < $jmlResult; $j++) 
					{		 
					
					    $n_peg 			= $db->fetchCol('select n_peg  
														from  e_sdm_pegawai_0_tm 
														where i_peg_nip = ? ',$result[$j]->i_peg_nipterima1);	
					
						
						$hasilAkhir[$j] = array("c_barang_pinjam"          	=>(string)$result[$j]-> c_barang_pinjam,
							               "i_barang_pinjam"			=>(string)$result[$j]-> i_barang_pinjam,
							               "n_barang" 				=>(string)$result[$j]-> n_barang,
							               "i_barang_serial"     	=>(string)$result[$j]-> i_barang_serial,
							               "i_rekanan"   			=>(string)$result[$j]-> i_rekanan,
										   "n_prsh"   			=>(string)$result[$j]-> n_prsh,
							               "d_barang_pinjamawal" 		=>(string)$result[$j]-> d_barang_pinjamawal,
										   "d_barang_pinjamakhir" 	=>(string)$result[$j]-> d_barang_pinjamakhir,
							               "e_keterangan"			=>(string)$result[$j]-> e_keterangan,
										   "i_orgb"					=>(string)$result[$j]-> i_orgb,
										   "n_peg"					=>$n_peg[0],
										   "d_distribusi"					=>(string)$result[$j]-> d_barang_serahpinjam,
										   "d_pengembalian_unit"					=>(string)$result[$j]-> d_barang_pengembalian,
										   "d_pengembalian_rekanan"					=>(string)$result[$j]-> d_pengembalian_pinjam,
										   "i_peg_nipterima"					=>(string)$result[$j]-> i_peg_nipterima1,
										   "n_barang_merktype"		=>(string)$result[$j]-> n_barang_merktype);			
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
	// Ina : 14-11-2008 : Akhir
}	
?>