<?php

class Adm_Adminuser_Service {
    private static $instance;

    private function __construct() {
    }

    public static function getInstance() {
       if (!isset(self::$instance)) {
           $c = __CLASS__;
           self::$instance = new $c;
       }

       return self::$instance;
    }

	//////////////////////////////////////////////////
	public function findUser(array $data, $pageNumber, $itemPerPage) {
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$i_peg_nip = $data['i_peg_nip'];
			$n_peg = strtoupper($data['n_peg']);
			$i_orgb = strtoupper($data['i_orgb']);
			
			
			$kataKunciCari = strtoupper($data['kataKunciCari']);
			$kategoriCari = $data['kategoriCari'];

			//var_dump($data);
			if($kategoriCari == 'userid') {
				if((!$kataKunciCari) ||($kataKunciCari == 'undefined')){
					$where = "and (UPPER(b.userid) like '%$kataKunciCari%' OR b.userid IS NULL)";
				} else {
					$where = "and UPPER(b.userid) like '%$kataKunciCari%'";
				}
			} else if($kategoriCari == 'i_peg_nip') {
				
				$where = "and (a.i_peg_nip like '%$kataKunciCari%' OR a.i_peg_nip_new like '%$kataKunciCari%')";
			} else if($kategoriCari == 'n_peg') {
				
				$where = "and UPPER(a.n_peg) like '%$kataKunciCari%'";
			} else if($kategoriCari == 'n_group') {
				
				$where = "and UPPER(e.n_group) like '%$kataKunciCari%'";
			} else {
				$where = "";
			}
			
			/* $sql1 = "select a.i_peg_nip, a.i_peg_nip_new, a.n_peg, a.n_peg_gelardepan, a.n_peg_gelarblkg,
						    a.c_golongan, a.c_jabatan, a.c_eselon_i, a.c_eselon_ii, a.c_eselon_iii,
					        a.c_eselon_iv, a.c_eselon_v, a.c_lokasi_unitkerja, b.userid, c.n_unitkerja,
							d.i_group, e.n_group
					from adm.tm_user b, sdm.tm_pegawai a , sdm.tr_unitkerja c, adm.tm_user_group d, adm.tm_group e
					where a.i_peg_nip = b.i_peg_nip
					  and a.c_eselon_i = c.c_eselon_i
					  and a.c_eselon_ii = c.c_eselon_ii
					  and a.c_eselon_iii = c.c_eselon_iii
					  and a.c_eselon_iv = c.c_eselon_iv
					  and a.c_eselon_v = c.c_eselon_v
					  and a.c_lokasi_unitkerja = c.c_lokasi_unitkerja
					  and b.userid = d.userid
					  and d.i_group = e.i_group
					 "; */
					
			$sql1 = "select a.i_peg_nip, a.i_peg_nip_new, a.n_peg, a.n_peg_gelardepan, a.n_peg_gelarblkg,
						    a.c_golongan, a.c_jabatan, a.c_eselon_i, a.c_eselon_ii, a.c_eselon_iii,
					        a.c_eselon_iv, a.c_eselon_v, a.c_lokasi_unitkerja, b.userid, c.n_unitkerja, e.n_group 
					from adm.tm_user b, sdm.tm_pegawai a , sdm.tr_unitkerja c, adm.tm_group e 
					where a.i_peg_nip = b.i_peg_nip 
					  and a.c_eselon_i = c.c_eselon_i 
					  and a.c_eselon_ii = c.c_eselon_ii 
					  and a.c_eselon_iii = c.c_eselon_iii 
					  and a.c_eselon_iv = c.c_eselon_iv 
					  and a.c_eselon_v = c.c_eselon_v 
					  and a.c_lokasi_unitkerja = c.c_lokasi_unitkerja 
					  and ((a.c_eselon_i = e.c_eselon_i
					  and a.c_eselon_ii = e.c_eselon_ii
					  and a.c_eselon_iii = e.c_eselon_iii
					  and a.c_eselon_iv = e.c_eselon_iv
					  and a.c_eselon_v = e.c_eselon_v 
					  and a.c_eselon = e.c_eselon
					  and a.c_jabatan = e.c_jabatan)
					   or (e.i_group = 0))
					 "; 
					 
			if(($pageNumber == 0) && ($itemPerPage == 0)) {
			
				$hasilAkhir = $db->fetchOne("select count(b.i_peg_nip) 
											FROM ($sql1 $where) b");
							
			} else {
				if($itemPerPage == 99999) {
				//echo "$sql1 $where order by b.userid desc limit $itemPerPage offset 1<br>";
				//$itemPerPage=500;
					$result = $db->fetchAll(" $sql1 $where 
											  order by b.userid desc limit $itemPerPage offset 0");

				} else {
					//$xLimit=500; //$itemPerPage;
					$xLimit= $itemPerPage;
			  		$xOffset=($pageNumber-1)*$itemPerPage;		
					
					$result = $db->fetchAll(" $sql1 $where 
											  order by a.n_peg limit $xLimit offset $xOffset");
				} 
		 							 //echo "$sql1 $where"; 
		         $jmlResult = count($result);

				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_peg_nip"  =>(string)$result[$j]->i_peg_nip,
											"i_peg_nip_new"  =>(string)$result[$j]->i_peg_nip_new,
											"n_peg"  =>(string)$result[$j]->n_peg,
											"n_peg_gelardepan"  =>(string)$result[$j]->n_peg_gelardepan,
											"n_peg_gelarblkg"  =>(string)$result[$j]->n_peg_gelarblkg,
											"c_jabatan"  =>(string)$result[$j]->c_jabatan,
											"c_eselon_i" =>(string)$result[$j]->c_eselon_i,
											"c_eselon_ii" =>(string)$result[$j]->c_eselon_ii,
											"c_eselon_iii" =>(string)$result[$j]->c_eselon_iii,
											"c_eselon_iv" =>(string)$result[$j]->c_eselon_iv,
											"c_lokasi_unitkerja" => (string)$result[$j]->c_lokasi_unitkerja,
											"userid" => (string)$result[$j]->userid,
											"n_unitkerja" => (string)$result[$j]->n_unitkerja,
											"i_group" => (string)$result[$j]->i_group,
											"n_group" => (string)$result[$j]->n_group);
				 }	
			}
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data Tidak Ada <br>';
	   }
	}
	
	public function findPegawai(array $data, $pageNumber, $itemPerPage) {
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$i_peg_nip = $data['i_peg_nip'];
			$n_peg = strtoupper($data['n_peg']);
			$i_orgb = strtoupper($data['i_orgb']);
			
			
			$kataKunciCari = strtoupper($data['kataKunciCari']);
			$kategoriCari = $data['kategoriCari'];

			//var_dump($data);
			if($kategoriCari == 'userid') {
				if((!$kataKunciCari) ||($kataKunciCari == 'undefined')){
					$where = "where (UPPER(b.userid) like '%$kataKunciCari%' OR b.userid IS NULL)";
				} else {
					$where = "where UPPER(b.userid) like '%$kataKunciCari%'";
				}
			} else if($kategoriCari == 'i_peg_nip') {
				
				$where = "where (a.i_peg_nip like '%$kataKunciCari%' OR a.i_peg_nip_new like '%$kataKunciCari%')";
			} else if($kategoriCari == 'n_peg') {
				
				$where = "where UPPER(a.n_peg) like '%$kataKunciCari%'";
			} else {
				$where = "";
			}
			
			/*
			if($i_peg_nip)	
			{
				if($n_peg) {
					$where = "a.i_peg_nip like '%$i_peg_nip%' AND UPPER(a.n_peg) like '%$n_peg%'";
				} else {
					$where = "a.i_peg_nip like '%$i_peg_nip%'";
				}
			}
			else
			{
				if($n_peg){
					$where = "UPPER(a.n_peg) like '%$n_peg%'";
				} else {
					$where = "a.i_peg_nip like '%%' AND UPPER(a.n_peg) like '%%'";
				}
			}
			 	
*/
				
			$sql1 = "select a.i_peg_nip, a.i_peg_nip_new, a.n_peg, a.n_peg_gelardepan, a.n_peg_gelarblkg,
						    a.c_golongan, a.c_jabatan, a.c_eselon_i, a.c_eselon_ii, a.c_eselon_iii,
					        a.c_eselon_iv, a.c_eselon_v, a.c_lokasi_unitkerja, b.userid
					 from adm.tm_user b 
					 left join sdm.tm_pegawai a on (a.i_peg_nip = b.i_peg_nip)
					 ";
						
			if(($pageNumber == 0) && ($itemPerPage == 0)) {
			
				$hasilAkhir = $db->fetchOne("select count(b.i_peg_nip) 
											FROM ($sql1 $where) b");
							
			} else {
				if($itemPerPage == 20) {
				//echo "$sql1 $where order by b.userid desc limit $itemPerPage offset 1<br>";
					$itemPerPage=20;
					$result = $db->fetchAll(" $sql1 $where 
											  order by b.userid desc limit $itemPerPage offset 0");

				} else {
					$xLimit=20; //$itemPerPage;
			  		$xOffset=($pageNumber-1)*$itemPerPage;		
					
					$result = $db->fetchAll(" $sql1 $where 
											  order by a.n_peg limit $xLimit offset $xOffset");
				} 
		 		//echo "$sql1 $where"; 
		         $jmlResult = count($result);

				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_peg_nip"  =>(string)$result[$j]->i_peg_nip,
											"i_peg_nip_new"  =>(string)$result[$j]->i_peg_nip_new,
											"n_peg"  =>(string)$result[$j]->n_peg,
											"n_peg_gelardepan"  =>(string)$result[$j]->n_peg_gelardepan,
											"n_peg_gelarblkg"  =>(string)$result[$j]->n_peg_gelarblkg,
											"c_jabatan"  =>(string)$result[$j]->c_jabatan,
											"c_eselon_i" =>(string)$result[$j]->c_eselon_i,
											"c_eselon_ii" =>(string)$result[$j]->c_eselon_ii,
											"c_eselon_iii" =>(string)$result[$j]->c_eselon_iii,
											"c_eselon_iv" =>(string)$result[$j]->c_eselon_iv,
											"c_lokasi_unitkerja" => (string)$result[$j]->c_lokasi_unitkerja,
											"userid" => (string)$result[$j]->userid);
				 }	
			}
	     return $hasilAkhir;
	   } catch (Exception $e) {
         //echo $e->getMessage().'<br>';
	     return 'Data Tidak Ada <br>';
	   }
	}
	public function getPegawaiList(array $data, $pageNumber, $itemPerPage) {
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$i_peg_nip = $data['i_peg_nip'];
			$n_peg = strtoupper($data['n_peg']);
			$i_orgb = strtoupper($data['i_orgb']);
			
			
			$kataKunciCari = strtoupper($data['kataKunciCari']);
			$kategoriCari = $data['kategoriCari'];

			//var_dump($data);
			if($kategoriCari == 'userid') {
				if((!$kataKunciCari) ||($kataKunciCari == 'undefined')){
					$where = "where (UPPER(b.userid) like '%$kataKunciCari%' OR b.userid IS NULL)";
				} else {
					$where = "where UPPER(b.userid) like '%$kataKunciCari%'";
				}
			} else if($kategoriCari == 'i_peg_nip') {
				
				$where = "where (a.i_peg_nip like '%$kataKunciCari%' OR a.i_peg_nip_new like '%$kataKunciCari%')";
			} else if($kategoriCari == 'n_peg') {
				
				$where = "where UPPER(a.n_peg) like '%$kataKunciCari%'";
			} else {
				$where = "";
			}
			
			/*
			if($i_peg_nip)	
			{
				if($n_peg) {
					$where = "a.i_peg_nip like '%$i_peg_nip%' AND UPPER(a.n_peg) like '%$n_peg%'";
				} else {
					$where = "a.i_peg_nip like '%$i_peg_nip%'";
				}
			}
			else
			{
				if($n_peg){
					$where = "UPPER(a.n_peg) like '%$n_peg%'";
				} else {
					$where = "a.i_peg_nip like '%%' AND UPPER(a.n_peg) like '%%'";
				}
			}
			 	
*/
				
			$sql1 = "select a.i_peg_nip, a.i_peg_nip_new, a.n_peg, a.n_peg_gelardepan, a.n_peg_gelarblkg,
						    a.c_golongan, a.c_jabatan, a.c_eselon_i, a.c_eselon_ii, a.c_eselon_iii,
					        a.c_eselon_iv, a.c_eselon_v, a.c_lokasi_unitkerja
					 from sdm.tm_pegawai a
					 ";
						
			if(($pageNumber == 0) && ($itemPerPage == 0)) {
			
				$hasilAkhir = $db->fetchOne("select count(b.i_peg_nip) 
											FROM ($sql1 $where) b");
							
			} else {
				if($itemPerPage == 20) {
				//echo "$sql1 $where order by b.userid desc limit $itemPerPage offset 1<br>";
					$result = $db->fetchAll(" $sql1 $where 
											  order by b.userid desc limit $itemPerPage offset 0");

				} else {
					$xLimit=$itemPerPage;
			  		$xOffset=($pageNumber-1)*$itemPerPage;		
					
					$result = $db->fetchAll(" $sql1 $where 
											  order by a.n_peg limit $xLimit offset $xOffset");
				} 
		 							// echo "xxx $sql1 $where <br>"; 
		         $jmlResult = count($result);

				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_peg_nip"  =>(string)$result[$j]->i_peg_nip,
											"i_peg_nip_new"  =>(string)$result[$j]->i_peg_nip_new,
											"n_peg"  =>(string)$result[$j]->n_peg,
											"n_peg_gelardepan"  =>(string)$result[$j]->n_peg_gelardepan,
											"n_peg_gelarblkg"  =>(string)$result[$j]->n_peg_gelarblkg,
											"c_jabatan"  =>(string)$result[$j]->c_jabatan,
											"c_eselon_i" =>(string)$result[$j]->c_eselon_i,
											"c_eselon_ii" =>(string)$result[$j]->c_eselon_ii,
											"c_eselon_iii" =>(string)$result[$j]->c_eselon_iii,
											"c_eselon_iv" =>(string)$result[$j]->c_eselon_iv,
											"c_lokasi_unitkerja" => (string)$result[$j]->c_lokasi_unitkerja);
				 }	
			}
	     return $hasilAkhir;
	   } catch (Exception $e) {
         //echo $e->getMessage().'<br>';
	     return 'Data Tidak Ada <br>';
	   }
	}
	
	public function checkLogin($dataMasukan) 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);

			$userid = $dataMasukan['userid'];
			$password = $dataMasukan['password'];

			 $result = $db->fetchOne("select count(*)
								 from adm.tm_user a
								 where a.userid = '$userid'
								   and a.n_password = '$password'"); 
			//$ldap_service = new ldap_services();
			//$dataMasukanLDAP = array("userid" 	=> $userid,
			//				 "password" => $password);
							 
			//$result = $ldap_service->checkLoginLdap($dataMasukanLDAP);
		
			//echo "xxxxxxxxxxxxxx = $result";
			return $result;
		} catch (Exception $e) {
			//echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}
	
	public function getDetailUser($dataMasukan) 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		
		$userid = $dataMasukan['userid'];
		$password = $dataMasukan['password'];
		
		/* $sqlDetailUser = "select a.userid,a.n_password, a.i_peg_nip, b.i_peg_nip_new, b.n_peg, b.n_peg_gelardepan,
										b.n_peg_gelarblkg, b.c_jabatan, b.c_lokasi_unitkerja, 
										b.c_eselon_i, b.c_eselon_ii, b.c_eselon_iii, b.c_eselon_iv,
										b.c_eselon_v, b.c_golongan
								from adm.tm_user a, v_pegawai b
								where a.i_peg_nip = b.i_peg_nip
								  and a.userid 		= '$userid'
								  and a.n_password 	= '$password'"; */
		$sqlDetailUser = "select a.userid,a.n_password, a.i_peg_nip, b.i_peg_nip_new, b.n_peg, b.n_peg_gelardepan,
										b.n_peg_gelarblkg, b.c_jabatan, b.c_lokasi_unitkerja, b.c_lokasi_unitkerja, 
										b.c_eselon_i, b.c_eselon_ii, b.c_eselon_iii, b.c_eselon_iv, 
										b.c_eselon_v, b.c_golongan, b.c_eselon
								from adm.tm_user a, sdm.tm_pegawai b
								where a.i_peg_nip = b.i_peg_nip
								  and a.userid 		= '$userid'";
		
		//echo "xxxxxxxxxxxxxxxxx".$sqlDetailUser;
								  
		$result = $db->fetchRow($sqlDetailUser);
		
		if($result->c_eselon_ii){
			if($result->c_eselon_iii){
				if($result->c_eselon_iv){
					if($result->c_eselon_v){
						$whereSatker .= " and trim(b.c_eselon_ii) 	= '".trim($result->c_eselon_ii)."' and
								  trim(b.c_eselon_iii) 	= '".trim($result->c_eselon_iii)."' and
								  trim(b.c_eselon_iv) 	= '".trim($result->c_eselon_iv)."' and
								  trim(b.c_eselon_v)	 	= '".trim($result->c_eselon_v)."'";
					} else {
						$whereSatker .= " and trim(b.c_eselon_ii) 	= '".trim($result->c_eselon_ii)."' and
								  trim(b.c_eselon_iii) 	= '".trim($result->c_eselon_iii)."' and
								  trim(b.c_eselon_iv) 	= '".trim($result->c_eselon_iv)."'";
					}
				} else {
					$whereSatker .= " and trim(b.c_eselon_ii) 	= '".trim($result->c_eselon_ii)."' and
								  trim(b.c_eselon_iii) 	= '".trim($result->c_eselon_iii)."'";
				}
			} else {
				$whereSatker .= " and trim(b.c_eselon_ii) 	= '".trim($result->c_eselon_ii)."'";
			}
		}
		
		$sqlCsatker = "select b.c_satker
					  from sdm.tr_unitkerja b
					  where trim(b.c_eselon_i) = '".trim($result->c_eselon_i)."'
					    $whereSatker
						and trim(b.c_lokasi_unitkerja) = '".trim($result->c_lokasi_unitkerja)."'";
		
		//echo $sqlCsatker;
		$cSatker = $db->fetchOne($sqlCsatker);
		
		$sqlNsatker = "select nmsatker
					  from v_satker
					  where kdsatker = '$cSatker'";
					  
		$nSatker = $db->fetchOne($sqlNsatker);
				
		
		// Ambil data  id_bpp dari keu.tm_bandahara 
		//-------------------------------------------------------
		$idBpp = $db->fetchOne("select id_bpp 
								from keu.tmbendahara 
								where i_nipbpp = '".$result->i_peg_nip_new."'
								  and d_tahunbpp = '".date('Y')."'
								  and c_status_aktif = 'Y'");
					   
		$sqlParent = "select b.c_parent
					  from sdm.tr_unitkerja b
					  where trim(b.c_eselon_i) = '".trim($result->c_eselon_i)."'
					    $whereSatker
						and trim(b.c_lokasi_unitkerja) = '".trim($result->c_lokasi_unitkerja)."'";

		$parent= $db->fetchOne($sqlParent);						  
		
		$sqlChild = "select b.c_child
					  from sdm.tr_unitkerja b
					  where trim(b.c_eselon_i) = '".trim($result->c_eselon_i)."'
					    $whereSatker
						and trim(b.c_lokasi_unitkerja) = '".trim($result->c_lokasi_unitkerja)."'";
	   
		$child= $db->fetchOne($sqlChild); 
		
		$n_eselon_i = $db->fetchOne("select a.n_unitkerja
								  from sdm.tr_unitkerja a
								  where a.c_lokasi_unitkerja = '".$result->c_lokasi_unitkerja."'
								    and a.c_eselon_i = '".$result->c_eselon_i."'
								    and a.c_eselon_ii = '000'
									and a.c_eselon_iii = '00'
									and a.c_eselon_iv = '00'
									and a.c_eselon_v = '00'
								 ");
		if(!$n_eselon_i){ $n_eselon_i = '';}
						
		if(trim($result->c_eselon_ii) != '000'){
			$n_eselon_ii = $db->fetchOne("select a.n_unitkerja
									  from sdm.tr_unitkerja a
									  where a.c_lokasi_unitkerja = '".$result->c_lokasi_unitkerja."'
									    and a.c_eselon_i = '".$result->c_eselon_i."'
									    and a.c_eselon_ii = '".$result->c_eselon_ii."'
										and a.c_eselon_iii = '00'
										and a.c_eselon_iv = '00'
										and a.c_eselon_v = '00'
									 ");
		} else { $n_eselon_ii = '';}
		
		if(trim($result->c_eselon_iii) != '00'){
			$n_eselon_iii = $db->fetchOne("select a.n_unitkerja
									  from sdm.tr_unitkerja a
									  where a.c_lokasi_unitkerja = '".$result->c_lokasi_unitkerja."'
									    and a.c_eselon_i = '".$result->c_eselon_i."'
									    and a.c_eselon_ii = '".$result->c_eselon_ii."'
										and a.c_eselon_iii = '".$result->c_eselon_iii."'
										and a.c_eselon_iv = '00'
										and a.c_eselon_v = '00'
									 ");
		} else { $n_eselon_iii = '';}
		
		if(trim($result->c_eselon_iv) != '00'){
			$n_eselon_iv = $db->fetchOne("select a.n_unitkerja
									  from sdm.tr_unitkerja a
									  where a.c_lokasi_unitkerja = '".$result->c_lokasi_unitkerja."'
									    and a.c_eselon_i = '".$result->c_eselon_i."'
									    and a.c_eselon_ii = '".$result->c_eselon_ii."'
										and a.c_eselon_iii = '".$result->c_eselon_iii."'
										and a.c_eselon_iv = '".$result->c_eselon_iv."'
										and a.c_eselon_v = '00'
									 ");
		} else { $n_eselon_iv = '';}
		
		if(trim($result->c_eselon_v) != '00'){
			$n_eselon_v = $db->fetchOne("select a.n_unitkerja
									  from sdm.tr_unitkerja a
									  where a.c_lokasi_unitkerja = '".$result->c_lokasi_unitkerja."'
									    and a.c_eselon_i = '".$result->c_eselon_i."'
									    and a.c_eselon_ii = '".$result->c_eselon_ii."'
										and a.c_eselon_iii = '".$result->c_eselon_iii."'
										and a.c_eselon_iv = '".$result->c_eselon_iv."'
										and a.c_eselon_v = '".$result->c_eselon_v."'
									 ");
		} else { $n_eselon_v = '';}
							 
		$hasilAkhir = array("userid"  =>(string)$result->userid,
							"n_password"  =>(string)$result->n_password,
							"i_peg_nip"  =>(string)$result->i_peg_nip,
							"i_peg_nip_new"  =>(string)$result->i_peg_nip_new,
							"n_peg"  =>(string)$result->n_peg,
							"n_peg_gelardepan"  =>(string)$result->n_peg_gelardepan,
							"n_peg_gelarblkg"  =>(string)$result->n_peg_gelarblkg,
							"c_jabatan"  =>(string)$result->c_jabatan,
							"c_golongan"  =>(string)$result->c_golongan,
							"c_eselon" =>(string)$result->c_eselon,
							"c_eselon_i" =>(string)$result->c_eselon_i,
							"c_eselon_ii" =>(string)$result->c_eselon_ii,
							"c_eselon_iii" =>(string)$result->c_eselon_iii,
							"c_eselon_iv" =>(string)$result->c_eselon_iv,
							"c_eselon_v" =>(string)$result->c_eselon_v,
							"c_lokasi_unitkerja" => (string)$result->c_lokasi_unitkerja,
							"n_eselon_i"	=> $n_eselon_i,
							"n_eselon_ii"	=> $n_eselon_ii,
							"n_eselon_iii"	=> $n_eselon_iii,
							"n_eselon_iv"	=> $n_eselon_iv,
							"n_eselon_v"	=> $n_eselon_v,
							"c_satker" => $cSatker,
							"n_satker" => $nSatker,
							"id_bpp"	=> $idBpp,
							"c_parent" => $parent,
							"c_child" => $child);

							//var_dump($hasilAkhir);
		 return $hasilAkhir;
		} catch (Exception $e) {
		 //echo $e->getMessage().'<br>';
		 return $e->getMessage().'gagal <br>';
		}
	}
	
	public function getOtoritasAplikasi($dataMasukan) 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		
		$userid = $dataMasukan['userid'];
		$password = $dataMasukan['password'];
		
		/* $sqlOtoritasAplikasi = "select distinct d.i_aplikasi
							  from adm.tm_user a, 
								   adm.tm_user_group b, 
								   adm.tm_group_menu c, 
								   adm.tm_menu d
							  where a.userid = b.userid 
								and b.i_group = c.i_group 
								and c.c_menu_level = d.c_menu_level
								and c.i_aplikasi = d.i_aplikasi
							    and a.userid 		= '$userid'
								and a.n_password 	= '$password'"; */
								
		/* $sqlOtoritasAplikasi = "select distinct d.i_aplikasi
							  from adm.tm_user a, 
								   adm.tm_user_group b, 
								   adm.tm_group_menu c, 
								   adm.tm_menu d
							  where a.userid = b.userid 
								and b.i_group = c.i_group 
								and c.c_menu_level = d.c_menu_level
								and c.i_aplikasi = d.i_aplikasi
							    and a.userid 		= '$userid'"; */
		$kategoriUser = $db->fetchOne("select a.c_kategori_user
									   from adm.tm_user a
									   where a.userid = '$userid'");
									 //  print_r($kategoriUser);
	if ($kategoriUser == 'U'){	//USER	
		$sqlOtoritasAplikasi = "select distinct c.i_aplikasi,c_wewenang,c_sektoral
									from adm.tm_user a, 
									     adm.tm_group b, 
									     adm.tm_group_menu c, 
									     sdm.tm_pegawai e
									where a.i_peg_nip = e.i_peg_nip
									  and b.i_group = c.i_group 
									  and ((e.c_eselon = b.c_eselon
									  and e.c_jabatan = b.c_jabatan
									  and e.c_eselon_i = b.c_eselon_i
									  and e.c_eselon_ii = b.c_eselon_ii
									  and e.c_eselon_iii = b.c_eselon_iii 	
									  and e.c_eselon_iv = b.c_eselon_iv
									  and e.c_eselon_v = b.c_eselon_v)
									  )
									  and c.i_aplikasi='1'
									  and a.userid 		= '$userid'";
									 
		$resultpeg = $db->fetchAll($sqlOtoritasAplikasi);
		if(count($resultpeg) > 0){
			$sqlOtoritasAplikasi = "select distinct c.i_aplikasi,c_wewenang,c_sektoral
									from adm.tm_user a, 
									     adm.tm_group b, 
									     adm.tm_group_menu c, 
									     sdm.tm_pegawai e
									where a.i_peg_nip = e.i_peg_nip
									  and b.i_group = c.i_group 
									  and ((e.c_eselon = b.c_eselon
									  and e.c_jabatan = b.c_jabatan
									  and e.c_eselon_i = b.c_eselon_i
									  and e.c_eselon_ii = b.c_eselon_ii
									  and e.c_eselon_iii = b.c_eselon_iii 	
									  and e.c_eselon_iv = b.c_eselon_iv
									  and e.c_eselon_v = b.c_eselon_v)
									  )
									  and a.userid 		= '$userid'";
		} else {
			$sqlOtoritasAplikasi = "select distinct c.i_aplikasi,c_wewenang,c_sektoral
									from adm.tm_user a, 
									     adm.tm_group b, 
									     adm.tm_group_menu c, 
									     sdm.tm_pegawai e
									where a.i_peg_nip = e.i_peg_nip
									  and b.i_group = c.i_group 
									  and ((e.c_eselon = b.c_eselon
									  and e.c_jabatan = b.c_jabatan
									  and e.c_eselon_i = b.c_eselon_i
									  and e.c_eselon_ii = b.c_eselon_ii
									  and e.c_eselon_iii = b.c_eselon_iii 	
									  and e.c_eselon_iv = b.c_eselon_iv
									  and e.c_eselon_v = b.c_eselon_v)
									 or (b.i_group = 0) )
									  and a.userid 		= '$userid'";
									  //print_r($sqlOtoritasAplikasi);exit;
		}
		$result = $db->fetchAll($sqlOtoritasAplikasi);
		for($j=0; $j<count($result); $j++){
			$i_aplikasi = $result[$j]->i_aplikasi;
			$hasilAkhir[$j] = array("i_aplikasi"=>(string)$result[$j]->i_aplikasi,
					"c_wewenang"=>(string)$result[$j]->c_wewenang,
					"c_sektoral"=>(string)$result[$j]->c_sektoral
					);
		}						
	}	else if ($kategoriUser == 'A') {
			$sqlOtoritasAplikasi = "select distinct c.i_aplikasi
									from adm.tm_user a, 
									     adm.tm_group b, 
									     adm.tm_group_menu c, 
									     sdm.tm_pegawai e
									where a.i_peg_nip = e.i_peg_nip
									  and b.i_group = c.i_group 
									  and n_group in ('admin_aplikasi', 'admin_portal')";
			$result = $db->fetchCol($sqlOtoritasAplikasi);
			for($x=0; $x<count($result); $x++){
				$hasilAkhir[$x] = array("i_aplikasi"=>(string)$result[$x],
									"c_wewenang"=>'E',
									"c_sektoral"=>'S');
				//$hasilAkhir[$x] = $result[$x];
				
			}
		} else if ($kategoriUser == 'SU') {
			$sqlOtoritasAplikasi = "select distinct d.i_aplikasi
									from adm.tm_group b, 
									     adm.tm_group_menu c, 
									     adm.tm_menu d
									where b.i_group = c.i_group 
									  and c.c_menu_level = d.c_menu_level
									  and c.i_aplikasi = d.i_aplikasi";
			$result = $db->fetchCol($sqlOtoritasAplikasi);
			for($x=0; $x<count($result); $x++){
				$hasilAkhir[$x] = array("i_aplikasi"=>(string)$result[$x],
									"c_wewenang"=>'O',
									"c_sektoral"=>'S');
				//$hasilAkhir[$x] = $result[$x];
				
			}
		}
		
		/*
		if ($kategoriUser == 'U'){	//USER	
			$sqlOtoritasAplikasi = "select distinct d.i_aplikasi
									from adm.tm_user a, 
									     adm.tm_group b, 
									     adm.tm_group_menu c, 
									     adm.tm_menu d,
									     sdm.tm_pegawai e
									where a.i_peg_nip = e.i_peg_nip
									  and b.i_group = c.i_group 
									  and c.c_menu_level = d.c_menu_level
									  and c.i_aplikasi = d.i_aplikasi
									  and ((e.c_eselon = b.c_eselon
									  and e.c_jabatan = b.c_jabatan
									  and e.c_eselon_i = b.c_eselon_i
									  and e.c_eselon_ii = b.c_eselon_ii
									  and e.c_eselon_iii = b.c_eselon_iii 	
									  and e.c_eselon_iv = b.c_eselon_iv
									  and e.c_eselon_v = b.c_eselon_v)
									   or (b.i_group = 0))
									  and a.userid 		= '$userid'";
		} else if ($kategoriUser == 'A') {
			$sqlOtoritasAplikasi = "select distinct d.i_aplikasi
									from adm.tm_user a, 
									     adm.tm_group b, 
									     adm.tm_group_menu c, 
									     adm.tm_menu d,
									     sdm.tm_pegawai e
									where a.i_peg_nip = e.i_peg_nip
									  and b.i_group = c.i_group 
									  and c.c_menu_level = d.c_menu_level
									  and c.i_aplikasi = d.i_aplikasi
									  and n_group in ('admin_aplikasi', 'admin_portal')";
		} else if ($kategoriUser == 'SU') {
			$sqlOtoritasAplikasi = "select distinct d.i_aplikasi
									from adm.tm_group b, 
									     adm.tm_group_menu c, 
									     adm.tm_menu d
									where b.i_group = c.i_group 
									  and c.c_menu_level = d.c_menu_level
									  and c.i_aplikasi = d.i_aplikasi";
		}
		*/						  
		 
		
		 return $hasilAkhir;
		} catch (Exception $e) {
		 //echo $e->getMessage().'<br>';
		 return $e->getMessage().'gagal <br>';
		}
	}
	
	public function getUserIzin($dataMasukan) 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		
		$userid = $dataMasukan['userid'];
		$password = $dataMasukan['password'];
		
		$sqlUserIzin = "select b.c_izin, c.n_izin
							  from adm.tm_user a, 
								   adm.tm_user_izin b,
								   adm.tr_izin c
							  where a.userid = b.userid 
							    and b.c_izin = c.c_izin
								and a.userid 		= '$userid'
								and a.n_password 	= '$password'";
								  
		$result = $db->fetchAll($sqlUserIzin);
		for($a=0; $a<count($result); $a++){
			$hasilAkhir[$a] = array("c_izin"	=> $result[$a]->c_izin,
								"n_izin"	=> $result[$a]->n_izin);
		}
		
		 return $hasilAkhir;
		} catch (Exception $e) {
		 //echo $e->getMessage().'<br>';
		 return $e->getMessage().'gagal <br>';
		}
	}
	
	public function getUserWewenang($dataMasukan) 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		
		$userid = $dataMasukan['userid'];
		$password = $dataMasukan['password'];
		
		$kategoriUser = $db->fetchOne("select a.c_kategori_user
									   from adm.tm_user a
									   where a.userid = '$userid'");
									   
	   if($kategoriUser == 'U'){
		   $sqlUserIzin = "select distinct b.c_wewenang, b.c_sektoral
							from adm.tm_user a, 
								 adm.tm_group b, 
								 adm.tm_group_menu c, 
								 adm.tm_menu d,
								 sdm.tm_pegawai e
							where a.i_peg_nip = e.i_peg_nip
							  and b.i_group = c.i_group 
							  and c.c_menu_level = d.c_menu_level
							  and c.i_aplikasi = d.i_aplikasi
							  and trim(e.c_eselon) = trim(b.c_eselon)
							  and trim(e.c_jabatan) = trim(b.c_jabatan)
							  and trim(e.c_eselon_i) = trim(b.c_eselon_i)
							  and trim(e.c_eselon_ii) = trim(b.c_eselon_ii)
							  and trim(e.c_eselon_iii) = trim(b.c_eselon_iii) 	
							  and trim(e.c_eselon_iv) = trim(b.c_eselon_iv)
							  and trim(e.c_eselon_v) = trim(b.c_eselon_v)
							  and a.userid 		= '$userid'";
									  
								  
								  
			$result = $db->fetchAll($sqlUserIzin);
			if(count($result) == 0){
				$sqlUserIzin = "select distinct b.c_wewenang, b.c_sektoral
							from adm.tm_user a, 
								 adm.tm_group b, 
								 adm.tm_group_menu c, 
								 adm.tm_menu d,
								 sdm.tm_pegawai e
							where a.i_peg_nip = e.i_peg_nip
							  and b.i_group = c.i_group 
							  and c.c_menu_level = d.c_menu_level
							  and c.i_aplikasi = d.i_aplikasi
							  and b.i_group = 0
							  and a.userid 		= '$userid'";
				//echo $sqlUserIzin;exit();
				$result = $db->fetchAll($sqlUserIzin);
			} 
			for($a=0; $a<count($result); $a++){
				$Izin = $db->fetchRow("select a.c_izin, a.n_izin
									   from adm.tr_izin a
									   where a.c_wewenang = '".$result[$a]->c_wewenang."'
										 and a.c_sektoral = '".$result[$a]->c_sektoral."'");
										 
				$hasilAkhir[$a] = array("c_wewenang"	=> $result[$a]->c_wewenang,
										"c_sektoral"	=> $result[$a]->c_sektoral,
										"c_izin"		=> $Izin->c_izin,
										"n_izin"		=> $Izin->n_izin);
			}
	   } else {
			$hasilAkhir[0] = array("c_wewenang"	=> "O",
								   "c_sektoral"	=> "S",
								   "c_izin"		=> "000005",
								   "n_izin"		=> "PENGELOLA_SEMUA_SEKTORAL");
	   
	   }
		
	    
		 return $hasilAkhir;
		} catch (Exception $e) {
		 //echo $e->getMessage().'<br>';
		 return $e->getMessage().'gagal <br>';
		}
	}
	
	public function getOtoritasMenu($dataMasukan) 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		
		$userid = $dataMasukan['userid'];
		$password = $dataMasukan['password'];
		$iAplikasi = $dataMasukan['iAplikasi'];
		
		/* $sqlOtoritasMenu = "select c.c_menu_level
							  from adm.tm_user a, 
								   adm.tm_user_group b, 
								   adm.tm_group_menu c, 
								   adm.tm_menu d
							  where a.userid = b.userid 
								and b.i_group = c.i_group 
								and c.c_menu_level = d.c_menu_level
								and c.i_aplikasi = d.i_aplikasi
							    and a.userid 		= '$userid'
								and a.n_password 	= '$password'
								and d.i_aplikasi	= '$iAplikasi'"; */
								
		/* $sqlOtoritasMenu = "select c.c_menu_level
							  from adm.tm_user a, 
								   adm.tm_user_group b, 
								   adm.tm_group_menu c, 
								   adm.tm_menu d
							  where a.userid = b.userid 
								and b.i_group = c.i_group 
								and c.c_menu_level = d.c_menu_level
								and c.i_aplikasi = d.i_aplikasi
							    and a.userid 		= '$userid'
								and a.n_password 	= '$password'
								and d.i_aplikasi	= '$iAplikasi'"; */
		$kategoriUser = $db->fetchOne("select a.c_kategori_user
									   from adm.tm_user a
									   where a.userid = '$userid'");
		
		//echo "kategori user = $kategoriUser";
		if ($kategoriUser == 'U'){	
			$sqlOtoritasMenu = "select c.c_menu_level
								from adm.tm_user a, 
								     adm.tm_group b, 
								     adm.tm_group_menu c, 
								     adm.tm_menu d,
								     sdm.tm_pegawai e
								 where a.i_peg_nip = e.i_peg_nip
								   and b.i_group = c.i_group 
								   and c.c_menu_level = d.c_menu_level
								   and c.i_aplikasi = d.i_aplikasi
								   and ((trim(e.c_eselon) = trim(b.c_eselon)
								   and trim(e.c_jabatan) = trim(b.c_jabatan)
								   and trim(e.c_eselon_i) = trim(b.c_eselon_i)
								   and trim(e.c_eselon_ii) = trim(b.c_eselon_ii)
								   and trim(e.c_eselon_iii) = trim(b.c_eselon_iii) 	
								   and trim(e.c_eselon_iv) = trim(b.c_eselon_iv)
								   and trim(e.c_eselon_v) = trim(b.c_eselon_v))
								    or (b.i_group = 0))
								   and a.userid 		= '$userid'
									and d.i_aplikasi	= '$iAplikasi'";
									//and a.n_password 	= '$password'
									
		} else if ($kategoriUser == 'A'){
			$sqlOtoritasMenu = "select distinct c.c_menu_level
									from adm.tm_user a, 
									     adm.tm_group b, 
									     adm.tm_group_menu c, 
									     adm.tm_menu d,
									     sdm.tm_pegawai e
									where a.i_peg_nip = e.i_peg_nip
									  and b.i_group = c.i_group 
									  and c.c_menu_level = d.c_menu_level
									  and c.i_aplikasi = d.i_aplikasi
									  and n_group in ('admin_aplikasi', 'admin_portal')";
		} else if ($kategoriUser == 'SU'){
			$sqlOtoritasMenu = "select distinct c.c_menu_level
									from adm.tm_group b, 
									     adm.tm_group_menu c, 
									     adm.tm_menu d
									where b.i_group = c.i_group 
									  and c.c_menu_level = d.c_menu_level
									  and c.i_aplikasi = d.i_aplikasi";
		}
		
								  //echo $sqlOtoritasMenu;
		$result = $db->fetchCol($sqlOtoritasMenu);
		
		 return $result;
		} catch (Exception $e) {
		 //echo $e->getMessage().'<br>';
		 return $e->getMessage().'gagal <br>';
		}
	}
	
	//================================
	public function getNamaFungsi($cFungsi) 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);

			$n_fungsi = $db->fetchOne("select a.n_fungsi 
									from rencana.tr_fungsi a
									where a.c_fungsi = '$cFungsi'");
									
			return $n_fungsi;
		} catch (Exception $e) {
		 //echo $e->getMessage().'<br>';
		 return 'gagal <br>';
		}
	}
	
	public function getNamaSfungsi($cFungsi, $cSfung) 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);

			$n_sfung = $db->fetchOne("select distinct(a.n_sfung)
									from rencana.tr_sfung a
									where a.c_fungsi = '$cFungsi' AND
										  a.c_sfung = '$cSfung'");
										  
			return $n_sfung;
		} catch (Exception $e) {
		 //echo $e->getMessage().'<br>';
		 return 'gagal <br>';
		}
	}
	
	public function getNamaPrioritas($c_jnsgiat, $c_priorits) 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);

			$n_priorits = $db->fetchOne("select distinct(a.n_priorits)
									from rencana.tr_prioritas a
									where a.c_jnsgiat = '$c_jnsgiat' AND
										  a.c_priorits = '$c_priorits'");
										  
			return $n_priorits;
		} catch (Exception $e) {
		 //echo $e->getMessage().'<br>';
		 return 'gagal <br>';
		}
	}
	
	public function getListIzin() 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		
		$sqlIzin = "select a.c_izin, a.n_izin
					from adm.tr_izin a
					where length(a.c_izin) = 6
					order by a.c_izin";
								  //echo $sqlOtoritasMenu;
		$result = $db->fetchAll($sqlIzin);
		$jmlResult = count($result);
		for ($j = 0; $j < $jmlResult; $j++) {
			$hasilAkhir[$j] = array("c_izin"  =>(string)$result[$j]->c_izin,
									"n_izin"  =>(string)$result[$j]->n_izin);
		}	
		 return $hasilAkhir;
		} catch (Exception $e) {
		 //echo $e->getMessage().'<br>';
		 return $e->getMessage().'gagal <br>';
		}
	}
	
	public function userTambah(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			// $dataMasukanTambah = array("userid" => $userid,
							 // "i_peg_nip" => $iPegNip,
							 // "n_password" => $userPasswd);
			
			$userid = $dataMasukan['userid'];
			$i_peg_nip = $dataMasukan['i_peg_nip'];
			$n_password = $dataMasukan['n_password'];
			$i_entry = $dataMasukan['i_entry'];
			
			$paramInputUser = array("userid"	=> $userid,
									"i_peg_nip" => $i_peg_nip,
									"n_password"=> $n_password,
									"i_entry"	=> $i_entry,
									"d_entry"	=> date('Y-m-d'));
			
			$db->insert('adm.tm_user',$paramInputUser);
			
			// insert data ke m_user_group
			//-------------------------------------
			foreach ($dataMasukan as $key => $val): 
				if(substr($key,0,6) == 'group_'){
					if($dataMasukan[$key]){
						$iGroupArr = explode("_",$key);
						$iGroup = $iGroupArr[1];
						$paramInputUserGroup = array("userid"	=> $userid,
													 "i_group"	=> $iGroup,
													 "i_entry"	=> $i_entry,
													 "d_entry"	=> date('Y-m-d'));
							
// var_dump($paramInputUserGroup);
// echo "<br>";
						//noel kayaknya ngk kepakai
						$db->insert("adm.tm_user_group", $paramInputUserGroup);
					}
				}
			endforeach;
			
			if($c_izin){
				$c_izin = $dataMasukan['c_izin'];
				$paramInputUserIzin = array("userid"	=> $userid,
										"c_izin"	=> $c_izin,
									    "i_entry"	=> $i_entry,
									    "d_entry"	=> date('Y-m-d'));
				//noel kayaknya ngk kepakai			
				$db->insert("adm.tm_user_izin", $paramInputUserIzin);
			} 
			// insert menu ke tm_user_menu
			//-----------------------------------------
			if(count($dataMasukan['menu']) > 0){
				for($x=0; $x<count($dataMasukan['menu']);$x++)
				{
					$menuArr = explode('_',$dataMasukan['menu'][$x]);
					$iAplikasiPilih = $menuArr[0];
					$cMenuLevelPilih = $menuArr[1];
					$paramInputUserMenu = array("userid"	=> $userid,
												 "i_aplikasi"	=> $iAplikasiPilih,
												 "c_menu_level"	=> $cMenuLevelPilih,
												 "i_entry"	=> $i_entry,
												 "d_entry"	=> date('Y-m-d'));
						

						// var_dump($paramInputUserMenu);
					//noel kayaknya ngk kepakai
					$db->insert("adm.tm_user_menu", $paramInputUserMenu);
				}
			}
			$db->commit();
			
			return "sukses";
			
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			
			$errMsg = $errmsgArr[0];

			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				//return "gagal";
				return $e->getMessage();
			}
	   }
	}
	
	public function userTambahVLDAP(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			// $dataMasukanTambah = array("userid" => $userid,
							 // "i_peg_nip" => $iPegNip,
							 // "n_password" => $userPasswd);
			
			$userid		= $dataMasukan['userid'];
			$i_peg_nip	= $dataMasukan['i_peg_nip'];
			$n_password	= $dataMasukan['n_password'];
			$c_kategori_user = $dataMasukan['c_kategori_user'];
			$i_entry	= $dataMasukan['i_entry'];
			$dirsimpan	= $dataMasukan['dirsimpan'];
			
			$ldap_service = new ldap_services();
			$dataMasukanLDAP = array("username"	=> $userid,
									 "password"	=> $n_password,
									 "i_entry"	=> $i_entry,
									 "dirsimpan"=> $dirsimpan);
			$tambahUserLDAP = $ldap_service->addUser($dataMasukanLDAP);
			
			//echo "xxxxxxxxxxxxxx  $tambahUserLDAP";
			if (stripos($tambahUserLDAP, "Can't contact LDAP server") !== FALSE){
				$pesan	=	"Tidak Terhubung ke server LDAP";
			} else if (stripos($tambahUserLDAP, "Invalid credentials")  !== FALSE){
				$pesan	=	"Userid / password manager LDAP salah";
			} else if (stripos($tambahUserLDAP, "Already exists")  !== FALSE){
				$pesan	=	"Userid Baru Sudah Terdaftar";
			} else {
				$pesan	=	"sukses";
			}

			if($pesan == 'sukses'){
				//tambah user dan hak akses ke db
				//-----------------------------------------
				$paramInputUser = array("userid"	=> $userid,
										"i_peg_nip" => $i_peg_nip,
										"n_password"=> $n_password,
										"c_kategori_user"=> $c_kategori_user,
										"i_entry"	=> $i_entry,
										"d_entry"	=> date('Y-m-d'));
				//var_dump($paramInputUser);
				$db->insert('adm.tm_user',$paramInputUser);
				
				// insert data ke m_user_group
				//-------------------------------------
				/* foreach ($dataMasukan as $key => $val): 
					if(substr($key,0,6) == 'group_'){
						if($dataMasukan[$key]){
							$iGroupArr = explode("_",$key);
							$iGroup = $iGroupArr[1];
							$paramInputUserGroup = array("userid"	=> $userid,
														 "i_group"	=> $iGroup,
														 "i_entry"	=> $i_entry,
														 "d_entry"	=> date('Y-m-d'));
								
 //var_dump($paramInputUserGroup);
// echo "<br>";
							$db->insert("adm.tm_user_group", $paramInputUserGroup);
						}
					}
				endforeach;
				
				$c_izin = $dataMasukan['c_izin'];
				$paramInputUserIzin = array("userid"	=> $userid,
											"c_izin"	=> $c_izin,
										    "i_entry"	=> $i_entry,
										    "d_entry"	=> date('Y-m-d'));
								
				$db->insert("adm.tm_user_izin", $paramInputUserIzin);
				 
				// insert menu ke tm_user_menu
				//-----------------------------------------
				for($x=0; $x<count($dataMasukan['menu']);$x++)
				{
					$menuArr = explode('_',$dataMasukan['menu'][$x]);
					$iAplikasiPilih = $menuArr[0];
					$cMenuLevelPilih = $menuArr[1];
					$paramInputUserMenu = array("userid"	=> $userid,
												 "i_aplikasi"	=> $iAplikasiPilih,
												 "c_menu_level"	=> $cMenuLevelPilih,
												 "i_entry"	=> $i_entry,
												 "d_entry"	=> date('Y-m-d'));
						
// echo "menu<br>";
						// var_dump($paramInputUserMenu);
// echo "<br>";				
					$db->insert("adm.tm_user_menu", $paramInputUserMenu);
				}
				 */
				$db->commit();
			
				return "sukses";
			} else {
				return "gagal. tidak tersimpan di LDAP. $pesan.";
			} 
			//return $tambahUserLDAP;
			
			
			
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			
			$userid = $dataMasukan['userid'];
			$dataMasukan = array("username"	=> $userid);
			$deleteUserLDAP = $ldap_service->deleteUser($dataMasukan);
			$errMsg = $errmsgArr[0];

			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				//return "gagal";
				return $e->getMessage();
			}
	   }
	}
	
	
	public function detailUserOlahData($dataMasukan) 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		
		$userid = $dataMasukan['userid'];
		$i_peg_nip = $dataMasukan['i_peg_nip'];
		
		$sqlDetailUser = "select a.userid,a.n_password, a.i_peg_nip, b.n_peg, b.n_peg_gelardepan,
						         b.n_peg_gelarblkg, b.c_jabatan, b.c_lokasi_unitkerja, 
						         b.c_eselon_i, b.c_eselon_ii, b.c_eselon_iii, b.c_eselon_iv,
						         b.c_eselon_v, b.c_golongan, d.n_jabatan, a.c_kategori_user
						  from adm.tm_user a, sdm.tm_pegawai b
						  left join v_jabatan d on (b.c_jabatan = d.c_jabatan)
						  where a.i_peg_nip = b.i_peg_nip
						    and a.userid 		= '$userid'
							and a.i_peg_nip 	= '$i_peg_nip'";
								  //echo $sqlDetailUser;
								  
		$result = $db->fetchRow($sqlDetailUser);
		
		if($result->c_eselon_ii){
			if($result->c_eselon_iii){
				if($result->c_eselon_iv){
					if($result->c_eselon_v){
						$whereSatker .= " and b.c_eselon_ii 	= '".$result->c_eselon_ii."' and
								  b.c_eselon_iii 	= '".$result->c_eselon_iii."' and
								  b.c_eselon_iv 	= '".$result->c_eselon_iv."' and
								  b.c_eselon_v	 	= '".$result->c_eselon_v."'";
					} else {
						$whereSatker .= " and b.c_eselon_ii 	= '".$result->c_eselon_ii."' and
								  b.c_eselon_iii 	= '".$result->c_eselon_iii."' and
								  b.c_eselon_iv 	= '".$result->c_eselon_iv."'";
					}
				} else {
					$whereSatker .= " and b.c_eselon_ii 	= '".$result->c_eselon_ii."' and
								  b.c_eselon_iii 	= '".$result->c_eselon_iii."'";
				}
			} else {
				$whereSatker .= " and b.c_eselon_ii 	= '".$result->c_eselon_ii."'";
			}
		}
		
		$sqlCsatker = "select b.c_satker
					  from v_unitkerja b
					  where b.c_eselon_i = '".$result->c_eselon_i."'
					     $whereSatker
						and b.c_lokasi_unitkerja = '".$result->c_lokasi_unitkerja."'";
		
		$cSatker = $db->fetchOne($sqlCsatker);
		
		$sqlNsatker = "select nmsatker
					  from v_satker
					  where kdsatker = '$cSatker'";
					  
		$nSatker = $db->fetchOne($sqlNsatker);
		/* 
		$sqlOtoritasGroup = "select a.i_group
							from adm.tm_user_group a, adm.tm_user b
							where a.userid = b.userid
							  and a.userid = '$userid' 
							  and b.i_peg_nip = '$i_peg_nip' 
							order by a.i_group";
		
//echo "----------".$sqlOtoritasMenu;
				
		$resultOtoritasGroup = $db->fetchCol($sqlOtoritasGroup);
		
		for($i=0; $i<count($resultOtoritasGroup); $i++){
			$hasilOtoritasGroup[$i] = (string)$resultOtoritasGroup[$i];
		}
		//var_dump($hasilOtoritasGroup);
		
		$sqlIzin = "select a.c_izin
					from adm.tm_user_izin a, adm.tr_izin b, adm.tm_user c
					where a.c_izin = b.c_izin
					  and a.userid = c.userid
					  and a.userid = '$userid' 
					  and c.i_peg_nip = '$i_peg_nip' 
					order by a.c_izin";
		
//echo "----------".$sqlOtoritasMenu;
				
		$resultIzin = $db->fetchCol($sqlIzin);
		
		for($i=0; $i<count($resultIzin); $i++){
			$hasilIzin[$i] = (string)$resultIzin[$i];
		}
		
		// otoritas ke Menu
		//----------------------
		 $sqlOtoritasMenu = "select a.i_aplikasi, a.c_menu_level
					from adm.tm_user_menu a, adm.tm_user b
					where a.userid = b.userid
					  and a.userid = '$userid' 
					  and b.i_peg_nip = '$i_peg_nip' 
					order by a.i_aplikasi, a.c_menu_level";
		
//echo "----------".$sqlOtoritasMenu;
				
		$resultOtoritasMenu = $db->fetchAll($sqlOtoritasMenu); 
		
		for($i=0; $i<count($resultOtoritasMenu); $i++){
			$hasilOtoritasMenu[$i] = array("i_aplikasi" => $resultOtoritasMenu[$i]->i_aplikasi, 
										   "c_menu_level" => $resultOtoritasMenu[$i]->c_menu_level);
		}
		 */
		//end otoritas ke menu
		
		$nUnitkerja = $db->fetchOne("select a.n_unitkerja
									 from v_unitkerja a
									 where a.c_lokasi_unitkerja = '".$result->c_lokasi_unitkerja."'");
									 
										
		$hasilAkhir = array("userid"  =>(string)$result->userid,
							"n_password"  =>(string)$result->n_password,
							"i_peg_nip"  =>(string)$result->i_peg_nip,
							"n_peg"  =>(string)$result->n_peg,
							"n_peg_gelardepan"  =>(string)$result->n_peg_gelardepan,
							"n_peg_gelarblkg"  =>(string)$result->n_peg_gelarblkg,
							"c_jabatan"  =>(string)$result->c_jabatan,
							"n_jabatan"  =>(string)$result->n_jabatan,
							"c_golongan"  =>(string)$result->c_golongan,
							"c_eselon_i" =>(string)$result->c_eselon_i,
							"c_eselon_ii" =>(string)$result->c_eselon_ii,
							"c_eselon_iii" =>(string)$result->c_eselon_iii,
							"c_eselon_iv" =>(string)$result->c_eselon_iv,
							"c_eselon_v" =>(string)$result->c_eselon_v,
							"c_lokasi_unitkerja" => (string)$result->c_lokasi_unitkerja,
							"n_unitkerja" => $nUnitkerja,
							"c_satker" => $cSatker,
							"n_satker" => $nSatker,
							"c_kategori_user" => (string)$result->c_kategori_user);
							/* "otoritasGroupArr" => $hasilOtoritasGroup,
							"cIzinArr" => $hasilIzin,
							"otoritasMenuArr" => $hasilOtoritasMenu); */

		 return $hasilAkhir;
		} catch (Exception $e) {
		 //echo $e->getMessage().'<br>';
		 return $e->getMessage().'gagal <br>';
		}
	}
	
	public function userUbah(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			/* $dataMasukanTambah = array("userid" => $userid,
							 "i_peg_nip" => $iPegNip,
							 "n_password" => $userPasswd); */
			
			$userid = $dataMasukan['userid'];
			$i_peg_nip = $dataMasukan['i_peg_nip'];
			//$n_password = $dataMasukan['n_password'];
			$c_kategori_user = $dataMasukan['c_kategori_user'];
			$i_entry = $dataMasukan['i_entry'];
			
			$paramInputUser = array("i_peg_nip" => $i_peg_nip,
									"i_entry"	=> $i_entry,
									"c_kategori_user" => $c_kategori_user,
									"d_entry"	=> date('Y-m-d'));
//			var_dump($paramInputUser);
			$whereUser[] = "userid = '".$userid."'";
//			var_dump($whereUser);
			$db->update('adm.tm_user',$paramInputUser, $whereUser);
			
			// delete + insert data ke m_user_group
			//------------------------------------------------
			/* 
			$db->delete("adm.tm_user_group", $whereUser);
			
			foreach ($dataMasukan as $key => $val): 
				if(substr($key,0,6) == 'group_'){
					if($dataMasukan[$key]){
						$iGroupArr = explode("_",$key);
						$iGroup = $iGroupArr[1];
						$paramInputUserGroup = array("userid"	=> $userid,
													 "i_group"	=> $iGroup,
													 "i_entry"	=> $i_entry,
													 "d_entry"	=> date('Y-m-d'));
							
// var_dump($paramInputUserGroup);
// echo "<br>";
						$db->insert("adm.tm_user_group", $paramInputUserGroup);
					}
				}
			endforeach;
			
			//delete +insert ke user izin
			//---------------------------------
			$db->delete("adm.tm_user_izin", $whereUser);
			
			$c_izin = $dataMasukan['c_izin'];
			$paramInputUserIzin = array("userid"	=> $userid,
										"c_izin"	=> $c_izin,
									    "i_entry"	=> $i_entry,
									    "d_entry"	=> date('Y-m-d'));
							
			$db->insert("adm.tm_user_izin", $paramInputUserIzin);
			 
			// delete + insert menu ke tm_user_menu
			//-------------------------------------------------
			$db->delete("adm.tm_user_menu", $whereUser);
			
			for($x=0; $x<count($dataMasukan['menu']);$x++)
			{
				$menuArr = explode('_',$dataMasukan['menu'][$x]);
				$iAplikasiPilih = $menuArr[0];
				$cMenuLevelPilih = $menuArr[1];
				$paramInputUserMenu = array("userid"	=> $userid,
											 "i_aplikasi"	=> $iAplikasiPilih,
											 "c_menu_level"	=> $cMenuLevelPilih,
											 "i_entry"	=> $i_entry,
											 "d_entry"	=> date('Y-m-d'));
					
// echo "menu<br>";
					// var_dump($paramInputUserMenu);
// echo "<br>";				
				$db->insert("adm.tm_user_menu", $paramInputUserMenu);
			}
			 */
			$db->commit();
			
			return "sukses";
			
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			
			$errMsg = $errmsgArr[0];

			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				//return "gagal";
				return $e->getMessage();
			}
	   }
	}
	
	public function userUbahPassword(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			/* $dataMasukanTambah = array("userid" => $userid,
							 "i_peg_nip" => $iPegNip,
							 "n_password" => $userPasswd); */
			
			$userid = $dataMasukan['userid'];
			$i_peg_nip = $dataMasukan['i_peg_nip'];
			$n_password = $dataMasukan['n_password'];
			$i_entry = $dataMasukan['i_entry'];
			
			$paramInputUser = array("n_password" => $n_password,
									"i_entry"	=> $i_entry,
									"d_entry"	=> date('Y-m-d'));
			//var_dump($paramInputUser);
			$whereUser[] = "userid = '".$userid."'";
			$whereUser[] = "i_peg_nip = '".$i_peg_nip."'";
			//var_dump($whereUser);
			$db->update('adm.tm_user', $paramInputUser, $whereUser);
			$db->commit();
			
			return "sukses";
			
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			
			$errMsg = $errmsgArr[0];

			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				//return "gagal";
				return $e->getMessage();
			}
	   }
	}
	
	public function userResetPasswordVLDAP(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			$ldap_service = new ldap_services();
			
			$userid 	= $dataMasukan['userid'];
			$n_password = $dataMasukan['n_password'];
			$i_entry 	= $dataMasukan['i_entry'];
			$dirsimpan	= $dataMasukan['dirsimpan'];
			
			$paramInputUser = array("userid" 	=> $userid,
									"n_password"=> $n_password,
									"i_entry"	=> $i_entry,
									"dirsimpan"=> $dirsimpan);
			
			$userResetPassword = $ldap_service->resPwd($paramInputUser);
			//echo $userResetPassword;
			if (stripos($userResetPassword, "Can't contact LDAP server") !== FALSE){
				$pesan	=	"Tidak Terhubung ke server LDAP";
			} else if (stripos($userResetPassword, "Invalid credentials")  !== FALSE){
				$pesan	=	"Userid / password manager LDAP salah";
			} else if (stripos($userResetPassword, "Already exists")  !== FALSE){
				$pesan	=	"Userid Baru Sudah Terdaftar";
			} else {
				$pesan	=	"sukses";
			}
			//echo "pesan = $pesan";
			return $pesan;
			
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			
			$errMsg = $errmsgArr[0];

			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				//return "gagal";
				return $e->getMessage();
			}
	   }
	}
	
	public function userGantiPasswordVLDAP(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			$ldap_service = new ldap_services();
			
			$userid 	= $dataMasukan['userid'];
			$userPasswd	= $dataMasukan['userPasswd'];
			$passwordLama = $dataMasukan['passwordLama'];
			$i_entry 	= $dataMasukan['i_entry'];
			$dirsimpan	= $dataMasukan['dirsimpan'];
			
			$paramInputUser = array("userid" 	=> $userid,
						    "userPasswd"	=> $userPasswd,
						    "passwordLama"=> $userPasswd,
						    "i_entry"	=> $i_entry,
						    "dirsimpan"=> $dirsimpan);
			
			$userResetPassword = $ldap_service->chPwd($paramInputUser);
			//echo $userResetPassword;
			if (stripos($userResetPassword, "Can't contact LDAP server") !== FALSE){
				$pesan	=	"Tidak Terhubung ke server LDAP";
			} else if (stripos($userResetPassword, "Invalid credentials")  !== FALSE){
				$pesan	=	"Userid / password manager LDAP salah";
			} else if (stripos($userResetPassword, "Already exists")  !== FALSE){
				$pesan	=	"Userid Baru Sudah Terdaftar";
			} else {
				$pesan	=	"sukses";
			}
			//echo "pesan = $pesan";
			//return $pesan;
return $userResetPassword ;

			
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			
			$errMsg = $errmsgArr[0];

			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				//return "gagal";
				return $e->getMessage();
			}
	   }
	}
	
	
	public function deleteUserVLDAP(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			$ldap_service = new ldap_services();
			
			
			$userid = $dataMasukan['userid'];
			$i_peg_nip = $dataMasukan['i_peg_nip'];
			$dirsimpan	= $dataMasukan['dirsimpan'];
			
			
			
			
			$paramInputUser = array("userid" 	=> $userid,
						    "i_entry"	=> $i_entry,
						    "dirsimpan"=> $dirsimpan);
			
			if(trim($userid) != '-'){
				$userDelete = $ldap_service->deleteUser($paramInputUser);
				//echo $userResetPassword;
				if (stripos($userDelete, "Can't contact LDAP server") !== FALSE){
					$pesan	=	"Tidak Terhubung ke server LDAP";
				} else if (stripos($userDelete, "Invalid credentials")  !== FALSE){
					$pesan	=	"Userid / password manager LDAP salah";
				} else if (stripos($userDelete, "Already exists")  !== FALSE){
					$pesan	=	"Userid Baru Sudah Terdaftar";
				} else {
					$pesan	=	"sukses";
				}
			} else {
				$pesan	=	"sukses";
			}
			
			
			if($pesan == 'sukses'){
				$userdb = $db->fetchOne("select userid from adm.tm_user where i_peg_nip = '".$i_peg_nip."'");
				$whereUser[] = "userid = '".$userdb."'";
				$db->delete("adm.tm_user", $whereUser);  
				
				/* $whereUser2[] = "userid = '".$userdb."'";
				
				$db->delete("adm.tm_user_group", $whereUser2);
				$db->delete("adm.tm_user_izin", $whereUser2);
				$db->delete("adm.tm_user_menu", $whereUser2); */
				
				$db->commit();				
				return "sukses";
			}
			
			
			
			//var_dump($whereUser);
			//$db->delete("adm.tm_user", $whereUser);
			
			
			 /* if($db->delete("adm.tm_user", $whereUser)){
				if ($db->delete("adm.tm_user_group", $whereUser)){
					if($db->delete("adm.tm_user_izin", $whereUser)){
						if($db->delete("adm.tm_user_menu", $whereUser)){
							$paramInputUser = array("username" => $userid);
							
							$deletePassword = $ldap_service->deleteUser($paramInputUser);
							
							$deletePasswordArr = explode('~',$deletePassword);
							var_dump($deletePasswordArr );
							if(trim($deletePasswordArr[0]) == 'OK'){
								$db->commit();
								return "sukses";
							} else {
								return "gagal hapus di LDAP";
							}
						} else {
							return "gagal hapus table user menu";	
						}
					} else {
						return "gagal hapus table user izin";	
					}
				} else {
					return "gagal hapus table user group";	
				}
			} else {
				return "gagal hapus table user";	
			}
			  */
			/* $db->delete("adm.tm_user", $whereUser);  
			$db->delete("adm.tm_user_group", $whereUser);
			$db->delete("adm.tm_user_izin", $whereUser);
			$db->delete("adm.tm_user_menu", $whereUser);
			
			$userid = $dataMasukan['userid'];
			$paramInputUser = array("username" => $userid);
			
			$deletePassword = $ldap_service->deleteUser($paramInputUser);

			$deletePasswordArr = explode('~',$deletePassword);
						
			if(trim($deletePasswordArr[0]) == 'OK'){
				$db->commit();
				return "sukses";
			} else {
				return "gagal hapus di LDAP";
			} */
			
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			
			$errMsg = $errmsgArr[0];

			
				//return "gagal";
			return $e->getMessage();
	   }
	}
}
?>