<?php

class Adm_Admaplikasi_Service {
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

	public function aplikasiList($data) {
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
			$pageNumber 	= $data['pageNumber'];
			$itemPerPage 	= $data['itemPerPage'];
			$kategoriCari 	= $data['kategoriCari'];
			$katakunciCari  = $data['katakunciCari'];
			$sortBy			= $data['sortBy'];
			$sortOrder		= $data['sortOrder'];

			if(trim($kategoriCari) != 'semua')	
			{	$whereOpt	= " UPPER(a.$kategoriCari) like '%$katakunciCari%' ";}
			else
			{	$whereOpt	= " 1=1 ";}
			 
			if(($pageNumber == 0) && ($itemPerPage == 0))
			{
				$hasilAkhir = $db->fetchOne("select count(a.i_aplikasi) 
										FROM adm.tm_aplikasi a
										where $whereOpt");
										
			}
			else if (($pageNumber == 99) && ($itemPerPage == 99))
			{
				$hasilAkhir = $db->fetchAll("select a.i_aplikasi, a.c_aplikasi, a.n_aplikasi, a.e_aplikasi,
													a.i_urut_aplikasi
											FROM adm.tm_aplikasi a
											where $whereOpt 
											order by a.$sortBy $sortOrder");
										
			}
			else
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;		
				$result = $db->fetchAll("select  a.i_aplikasi, a.c_aplikasi, a.n_aplikasi, a.e_aplikasi,
												 a.i_urut_aplikasi 
										FROM adm.tm_aplikasi a
										where $whereOpt 
										order by $sortBy $sortOrder limit $xLimit offset $xOffset");
					         $jmlResult = count($result);
				 //echo "==========$jmlResult=========";
				 for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_aplikasi"  =>(string)$result[$j]->i_aplikasi,
											"c_aplikasi"  =>(string)$result[$j]->c_aplikasi,
											"n_aplikasi"  =>(string)$result[$j]->n_aplikasi,
											"e_aplikasi"  =>(string)$result[$j]->e_aplikasi,
											"i_urut_aplikasi"  =>(string)$result[$j]->i_urut_aplikasi);
				 }	
			}
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data Tidak Ada <br>';
	   }
	}
	
	public function getAplikasiId($cAplikasi) {
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
			$result = $db->fetchOne("select i_aplikasi 
						FROM adm.tm_aplikasi 
						where c_aplikasi = '$cAplikasi'");
		
		         
			return $result;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data Tidak Ada <br>';
	   }
	}
	
	
	public function admaplikasiTambah(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			$paramInput = array("c_aplikasi"	=> $dataMasukan['c_aplikasi'],
								"n_aplikasi"    => $dataMasukan['n_aplikasi'],                          
								"e_aplikasi"    => $dataMasukan['e_aplikasi'],                          
								"i_urut_aplikasi"    => $dataMasukan['i_urut_aplikasi']);
					
					//var_dump($paramInput);
			$db->insert('adm.tm_aplikasi',$paramInput); 
			$db->commit();
			
			unset($paramInput);
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
	
	public function aplikasiDetail($data) {
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
			$i_aplikasi		= $data['i_aplikasi'];
					
			$result = $db->fetchRow("select a.i_aplikasi, a.c_aplikasi, a.n_aplikasi, a.e_aplikasi,
												 a.i_urut_aplikasi  
						FROM adm.tm_aplikasi a
						where i_aplikasi = '$i_aplikasi'");
		
			 $jmlResult = count($result);
			 //echo "==========$jmlResult=========";
			 
			 if($jmlResult){
				$hasilAkhir	= array("i_aplikasi"  =>(string)$result->i_aplikasi,
									"c_aplikasi"  =>(string)$result->c_aplikasi,
									"n_aplikasi"  =>(string)$result->n_aplikasi,
									"e_aplikasi"  =>(string)$result->e_aplikasi,
									"i_urut_aplikasi"  =>(string)$result->i_urut_aplikasi);
			}
			 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data Tidak Ada <br>';
	   }
	}
	
	public function getMaxNourutAplikasi() {
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 

			$sql = "select max(a.i_urut_aplikasi)+1 
			from adm.tm_aplikasi a";
			$noUrutAplikasi   = $db->fetchOne("$sql");

			if(!$noUrutAplikasi){
				$noUrutAplikasi = 1;
			}
			return $noUrutAplikasi;
		} catch (Exception $e) {
			//echo $e->getMessage().'<br>';
			return 'Data Tidak Ada <br>';
		}
	}
	
	public function noUrutAplikasi($i_aplikasi) {
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 

			$sql = "select a.i_urut_aplikasi 
					from adm.tm_aplikasi a
					where a.i_aplikasi = '".$i_aplikasi."'";
			$noUrutAplikasi   = $db->fetchOne("$sql");

			if(!$noUrutAplikasi){
				$noUrutAplikasi = 1;
			}
			return $noUrutAplikasi;
		} catch (Exception $e) {
			//echo $e->getMessage().'<br>';
			return 'Data Tidak Ada <br>';
		}
	}
	
	public function admaplikasiUbah(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			$paramInput = array("c_aplikasi"	=> $dataMasukan['c_aplikasi'],
								"n_aplikasi"    => $dataMasukan['n_aplikasi'],                          
								"e_aplikasi"    => $dataMasukan['e_aplikasi'],                          
								"i_urut_aplikasi"    => $dataMasukan['i_urut_aplikasi']);
			
			$where[] = "i_aplikasi = '".$dataMasukan['i_aplikasi']."'";
			
					//var_dump($paramInput);
			$db->update('adm.tm_aplikasi',$paramInput, $where); 
			$db->commit();
			
			unset($paramInput);
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
	
	public function admaplikasiHapus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$where[] = "i_aplikasi = '".$dataMasukan['i_aplikasi']."'";
			
					//var_dump($paramInput);
			$db->delete('adm.tm_aplikasi', $where); 
			$db->commit();
			
			unset($paramInput);
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
	
	public function getMaxIaplikasi(){
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	        $db->setFetchMode(Zend_Db::FETCH_OBJ); 

			$sqlMenu = "select max(a.i_aplikasi)
						from adm.tm_aplikasi a";
//echo $sqlMenu;

			$maxIaplikasi = $db->fetchOne("$sqlMenu");
			
			if(!$maxIaplikasi){
				$maxIaplikasi = '1';
			}
			//echo "xxx= $cMenuLevel";
			return $maxIaplikasi;						  
	   } catch (Exception $e) {
	     return ;
	   }	
	
	}
	public function writeToLogAksesaplikasi($data) {
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$userid		= $data['userid'];
			$cAplikasi	= $data['cAplikasi'];
			
			$iAplikasi = $this->getAplikasiId($cAplikasi);
			
			$paramInput = array("userid"	=> $userid,
								"i_aplikasi"    => $iAplikasi);
								
			$db->insert('adm.tm_log_aksesaplikasi',$paramInput);
			
			$db->commit();
			
			//unset($iAplikasi);
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
	
	public function deleteLogAksesaplikasi($data) {
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$userid		= $data['userid'];
				
			$where[] = "userid = '$userid'";
								
			$db->delete('adm.tm_log_aksesaplikasi',$where);
			
			$db->commit();
			
			//unset($iAplikasi);
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
	
	public function getUserOnline(){
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$sql = "select b.i_aplikasi, b.n_aplikasi, y.user_online
					from
					(
						select a.i_aplikasi, count(*) as user_online
						from adm.tm_log_aksesaplikasi a
						group by a.i_aplikasi
					) y
					right join adm.tm_aplikasi b on (y.i_aplikasi = b.i_aplikasi)
					order by b.i_aplikasi";
//echo $sql;
			$hasil = $db->fetchAll("$sql");
			
			if(count($hasil)){
				for($a=0; $a<count($hasil); $a++){
					$data[$a] = array("i_aplikasi" => (string)$hasil[$a]->i_aplikasi,
									  "n_aplikasi" => (string)$hasil[$a]->n_aplikasi,
									  "user_online" => (string)$hasil[$a]->user_online);
				}
			} 
			//echo "xxx= $cMenuLevel";
			return $data;						  
	   } catch (Exception $e) {
	     return ;
	   }	
	}

	
	public function writeToLogAksesaplikasiOrg($data) {
		
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$userid		= $data['userid'];
			$cAplikasi	= $data['cAplikasi'];
			
			$iAplikasi = $this->getAplikasiId($cAplikasi);
			$jmlUserAksesAplikasi = $db->fetchOne("select max(a.q_jumlah_akses) + 1
												   from adm.tm_log_aksesaplikasi a
												   where a.userid = '$userid'
												     and a.i_aplikasi = $iAplikasi");
			 
			$paramInput = array("userid"	=> $userid,
								"i_aplikasi"    => $iAplikasi);
								
			if(!$jmlUserAksesAplikasi){
				
				$jmlUserAksesAplikasi = 1;
				$paramInput['q_jumlah_akses'] = $jmlUserAksesAplikasi;
				
				$db->insert('adm.tm_log_aksesaplikasi',$paramInput);
			} else {
				$paramInputUpdate['q_jumlah_akses'] = $jmlUserAksesAplikasi;
				$where[] = "userid = '".$userid."'";
				$where[] = "i_aplikasi = $iAplikasi";
				$db->update('adm.tm_log_aksesaplikasi',$paramInputUpdate,$where);
			}
			
			$db->commit();
			
			//unset($iAplikasi);
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
	
}
?>