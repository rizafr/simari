<?php

class Adm_Admmenu_Service {
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

	public function readAllMenuMap() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	        $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	        $actionList  = $db->fetchAll("select i_menu,      
												cast(lpad(split_part(c_menu_level,'.',1),4,'0') as int) as l1 ,
												cast(lpad(split_part(c_menu_level,'.',2),2,'0') as int) as l2,
												cast(lpad(split_part(c_menu_level,'.',3),2,'0') as int) as l3,
												cast(lpad(split_part(c_menu_level,'.',4),2,'0') as int) as l4,
												a.i_aplikasi,
												b.n_aplikasi,
												c_menu_level,
												n_menu,
												c_menu_statuscb,
												e_menu,
												n_action
											from adm.tm_menu a, adm.tm_aplikasi b
											where a.i_aplikasi = b.i_aplikasi
											order by 6,2,3,4,5");
					
					
			for ($j=0; $j<count($actionList); $j++){
				$data[$j] = array("i_menu" =>(string)$actionList[$j]->i_menu,	
							"l1" =>(string)$actionList[$j]->l1,
							"l2" =>(string)$actionList[$j]->l2,
							"l3" =>(string)$actionList[$j]->l3,
							"l4" =>(string)$actionList[$j]->l4,
							"i_aplikasi" =>(string)$actionList[$j]->i_aplikasi,
							"n_aplikasi" =>(string)$actionList[$j]->n_aplikasi,
							"c_menu_level" =>(string)$actionList[$j]->c_menu_level,
							"n_menu" =>(string)$actionList[$j]->n_menu,
							"c_menu_statuscb" =>(string)$actionList[$j]->c_menu_statuscb,
							"e_menu" =>(string)$actionList[$j]->e_menu,
							"n_action" =>(string)$actionList[$j]->n_action);
			}	
			
			return $data;						  
	   } catch (Exception $e) {
	     return ;
	   }	
	
	}
	
	public function readMenuMapPerLevel($iAplikasi,$levelInduk) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	        $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
			if(!$levelInduk){
				$sqlMenu = "select i_menu,      
								substr(c_menu_level,1,2)  as l1 , 
								substr(c_menu_level,3,2)  as l2 , 
								substr(c_menu_level,5,2)  as l3 , 
								substr(c_menu_level,7,2)  as l4 , b.n_aplikasi,
								c_menu_level,
								n_menu,
								c_menu_statuscb,
								e_menu,
								n_action
							from adm.tm_menu a, adm.tm_aplikasi b
							where a.i_aplikasi = b.i_aplikasi
							and length(c_menu_level) = 2
							and a.i_aplikasi = $iAplikasi
							order by 2,3,4,5";
												
												
			} else {
				$levelIndukLen = strlen($levelInduk)+2;
		        $sqlMenu  = "select i_menu,      
									substr(c_menu_level,1,2)  as l1 , 
									substr(c_menu_level,3,2)  as l2 , 
									substr(c_menu_level,5,2)  as l3 , 
									substr(c_menu_level,7,2)  as l4 , b.n_aplikasi,
									b.n_aplikasi,
									c_menu_level,
									n_menu,
									c_menu_statuscb,
									e_menu,
									n_action
								from adm.tm_menu a, adm.tm_aplikasi b
								where a.i_aplikasi = b.i_aplikasi
								and length(c_menu_level) = $levelIndukLen
								and substr(c_menu_level,1,$levelIndukLen) like '$levelInduk%' 
								and a.i_aplikasi = $iAplikasi
								order by 2,3,4,5";
			}
//echo $sqlMenu;
			$actionList = $db->fetchAll("$sqlMenu");
			//echo "$levelInduk - $levelIndukLen<br>$sqlMenu";
												
			for ($j=0; $j<count($actionList); $j++){
				$data[$j] = array("i_menu" =>(string)$actionList[$j]->i_menu,	
							"l1" =>(string)$actionList[$j]->l1,
							"l2" =>(string)$actionList[$j]->l2,
							"l3" =>(string)$actionList[$j]->l3,
							"l4" =>(string)$actionList[$j]->l4,
							"n_aplikasi" =>(string)$actionList[$j]->n_aplikasi,
							"c_menu_level" =>(string)$actionList[$j]->c_menu_level,
							"n_menu" =>(string)$actionList[$j]->n_menu,
							"c_menu_statuscb" =>(string)$actionList[$j]->c_menu_statuscb,
							"e_menu" =>(string)$actionList[$j]->e_menu,
							"n_action" =>(string)$actionList[$j]->n_action);
			}	
			
			return $data;						  
	   } catch (Exception $e) {
	     return ;
	   }	
	
	}
	
	public function readAllMenuMapPerAplikasi($dataMasukan) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	        $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$iAplikasi = $dataMasukan['i_aplikasi'];
	        $actionList  = $db->fetchAll("select i_menu,      
												cast(lpad(split_part(c_menu_level,'.',1),4,'0') as int) as l1 ,
												cast(lpad(split_part(c_menu_level,'.',2),2,'0') as int) as l2,
												cast(lpad(split_part(c_menu_level,'.',3),2,'0') as int) as l3,
												cast(lpad(split_part(c_menu_level,'.',4),2,'0') as int) as l4,
												a.i_aplikasi,
												b.n_aplikasi,
												c_menu_level,
												n_menu,
												c_menu_statuscb,
												e_menu,
												n_action
											from adm.tm_menu a, adm.tm_aplikasi b
											where a.i_aplikasi = b.i_aplikasi
											  and a.i_aplikasi = $iAplikasi
											order by 8");
					
			 /*echo "select i_menu,      
												cast(lpad(split_part(c_menu_level,'.',1),4,'0') as int) as l1 ,
												cast(lpad(split_part(c_menu_level,'.',2),2,'0') as int) as l2,
												cast(lpad(split_part(c_menu_level,'.',3),2,'0') as int) as l3,
												cast(lpad(split_part(c_menu_level,'.',4),2,'0') as int) as l4,
												a.i_aplikasi,
												b.n_aplikasi,
												c_menu_level,
												n_menu,
												c_menu_statuscb,
												e_menu,
												n_action
											from adm.tm_menu a, adm.tm_aplikasi b
											where a.i_aplikasi = b.i_aplikasi
											  and a.i_aplikasi = $iAplikasi
											order by 6,2,3,4,5"; */
			for ($j=0; $j<count($actionList); $j++){
				$data[$j] = array("i_menu" =>(string)$actionList[$j]->i_menu,	
							"l1" =>(string)$actionList[$j]->l1,
							"l2" =>(string)$actionList[$j]->l2,
							"l3" =>(string)$actionList[$j]->l3,
							"l4" =>(string)$actionList[$j]->l4,
							"i_aplikasi" =>(string)$actionList[$j]->i_aplikasi,
							"n_aplikasi" =>(string)$actionList[$j]->n_aplikasi,
							"c_menu_level" =>(string)$actionList[$j]->c_menu_level,
							"n_menu" =>(string)$actionList[$j]->n_menu,
							"c_menu_statuscb" =>(string)$actionList[$j]->c_menu_statuscb,
							"e_menu" =>(string)$actionList[$j]->e_menu,
							"n_action" =>(string)$actionList[$j]->n_action);
			}	
			
			//var_dump($data);
			return $data;						  
	   } catch (Exception $e) {
	     return ;
	   }	
	
	}
	
	public function getMenuLevel($data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	        $db->setFetchMode(Zend_Db::FETCH_OBJ); 

			$iAplikasi 	 = $data['i_aplikasi'];
			$cMenuLevel1 = $data['c_menu_level1'];
			$cMenuLevel2 = $data['c_menu_level2'];
			$cMenuLevel3 = $data['c_menu_level3'];
			$level	     = $data['level'];
			if ($level == 1) { $cMenuLevel = $cMenuLevel1; }
			if ($level == 2) { $cMenuLevel = $cMenuLevel1.$cMenuLevel2; }
			if ($level == 3) { $cMenuLevel = $cMenuLevel1.$cMenuLevel2.$cMenuLevel3; }
			
			$panjangCMenuLevel = 2*$level;
			
			$sqlMenu = "select a.n_menu
						from adm.tm_menu a
						where a.c_menu_level = '$cMenuLevel'
						and a.i_aplikasi = $iAplikasi
						and length(a.c_menu_level) = $panjangCMenuLevel ";
//echo "<br>$level | $cMenuLevel1 | $cMenuLevel2 |$cMenuLevel3 | $cMenuLevel |$$sqlMenu";
			$nMenu = $db->fetchOne("$sqlMenu");
			return $nMenu;						  
	   } catch (Exception $e) {
	     return ;
	   }	
	
	}
	
	public function getMaxLevel($data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	        $db->setFetchMode(Zend_Db::FETCH_OBJ); 

			$iAplikasi 	 = $data['i_aplikasi'];
			$cMenuLevelInduk = $data['c_menu_levelinduk'];
			
			if(!$cMenuLevelInduk){
				$sqlMenu = "select to_char(cast(max(a.c_menu_level) as int) +1, '09') as cMenuLevelbaru
							from adm.tm_menu a
							where length(c_menu_level) = 2
							  and a.i_aplikasi = $iAplikasi";
			} else {
				$levelIndukLen = strlen($cMenuLevelInduk)+2;
				$sqlMenu = "select cast(max(a.c_menu_level) as int) +1 as cMenuLevelbaru
							from adm.tm_menu a
							where a.c_menu_level like '$cMenuLevelInduk%'
							and length(c_menu_level) = $levelIndukLen
							and a.i_aplikasi = $iAplikasi";
			}
//echo $sqlMenu;

			$cMenuLevel = $db->fetchOne("$sqlMenu");
			
			if(!$cMenuLevel){
				$cMenuLevel = '01';
			}
			//echo "xxx= $cMenuLevel";
			return $cMenuLevel;						  
	   } catch (Exception $e) {
	     return ;
	   }	
	
	}
	
	public function menuTambah(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			
			$paramInput = array("i_aplikasi"	=> $dataMasukan['i_aplikasi'],
								"c_menu_level"    => $dataMasukan['c_menu_level'],                          
								"n_menu"    => $dataMasukan['n_menu'],                          
								"e_menu"    => $dataMasukan['e_menu'],                          
								"c_menu_statuscb"    => $dataMasukan['c_menu_statuscb'],                          
								"n_action"    => $dataMasukan['n_action']);
					
					//var_dump($paramInput);
			$db->insert('adm.tm_menu',$paramInput); 
			
			$paramInput2 = array("c_menu_statuscb"    => "N",
								 "n_action"    => "");
			$where2[] = "i_aplikasi = '".$dataMasukan['i_aplikasi']."'";
			$where2[] = "c_menu_level = '".substr($dataMasukan['c_menu_level'], 0, strlen($dataMasukan['c_menu_level'])-2)."'";

			//var_dump($where2);
			$db->update('adm.tm_menu',$paramInput2, $where2); 
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
	
	public function menuUbah(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			
			$paramInput = array("n_menu"    => $dataMasukan['n_menu'],                          
								"e_menu"    => $dataMasukan['e_menu'],                          
								"c_menu_statuscb"    => $dataMasukan['c_menu_statuscb'],                          
								"n_action"    => $dataMasukan['n_action']);
			$where1[] = "i_aplikasi = '".$dataMasukan['i_aplikasi']."'";
			$where1[] = "c_menu_level = '".$dataMasukan['c_menu_level']."'";				
					//var_dump($where1);
			$db->update('adm.tm_menu',$paramInput, $where1); 
			
			/* $paramInput2 = array("c_menu_statuscb"    => "N",
								 "n_action"    => "");
			$where2[] = "i_aplikasi = '".$dataMasukan['i_aplikasi']."'";
			$where2[] = "c_menu_level = '".substr($dataMasukan['c_menu_level'], 0, strlen($dataMasukan['c_menu_level'])-2)."'";
 */
			//var_dump($where2);
			//$db->update('adm.tm_menu',$paramInput2, $where2); 
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
	
	public function menuHapus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$where1[] = "i_aplikasi = '".$dataMasukan['i_aplikasi']."'";
			$where1[] = "c_menu_level = '".$dataMasukan['c_menu_level']."'";				
					var_dump($where1);
			$db->delete('adm.tm_menu', $where1); 
			
			// check apakah level induk masih punya cabang atau tidak, jika tidal punya cabang maka statuscd di update menjadi 'Y'
			//=====================================================================================================
			$cMenuLevelInduk = substr($dataMasukan['c_menu_level'],0,strlen($dataMasukan['c_menu_level'])-2);
			
			if(strlen($dataMasukan['c_menu_level']) > 2){
				$jmlCabang = $db->fetchOne("select count(a.c_menu_level) 
											from adm.tm_menu a
											where a.c_menu_level like '$cMenuLevelInduk%'
											and length(a.c_menu_level) > ".strlen($cMenuLevelInduk)."
											and a.i_aplikasi = '".$dataMasukan['i_aplikasi']."'");
				echo "<br>select count(a.c_menu_level) 
											from adm.tm_menu a
											where a.c_menu_level like '$cMenuLevelInduk%'
											and length(a.c_menu_level) > ".strlen($cMenuLevelInduk)."
											and a.i_aplikasi = '".$dataMasukan['i_aplikasi']."'<br>";
				if($jmlCabang == 0){
					$paramUpdate = array("c_menu_statuscb" => "Y",
										 "n_action" => "rencanamodule/development/dev,rencanamodule/development/devjs");
					$where2[] = "i_aplikasi = '".$dataMasukan['i_aplikasi']."'";
					$where2[] = "c_menu_level = '".$cMenuLevelInduk."'";
					var_dump($where2);
					$db->update('adm.tm_menu', $paramUpdate, $where2); 
				}
			
			}
			
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

			$iAplikasi 	 = $data['i_aplikasi'];
			$cMenuLevel1 = $data['c_menu_level1'];
			$cMenuLevel2 = $data['c_menu_level2'];
			$cMenuLevel3 = $data['c_menu_level3'];
			$cMenuLevel4 = $data['c_menu_level4'];
			$levelInduk	 = $data['levelInduk'];
			
			$sqlMenu1 = "select a.i_menu, a.i_aplikasi, b.n_aplikasi, a.c_menu_level, a.n_menu
							from adm.tm_menu a, adm.tm_aplikasi b
							where a.i_aplikasi = b.i_aplikasi
							  and a.c_menu_level = '$cMenuLevel1'
							  and a.i_aplikasi = $iAplikasi";

			$result1 = $db->fetchRow("$sqlMenu1");
			
			$hasilAkhir = array("i_menu"		=> $result1->i_menu,
								"i_aplikasi"	=> $result1->i_aplikasi,
								"n_aplikasi"	=> $result1->n_aplikasi,
								"c_menu_level1"	=> $result1->c_menu_level,
								"n_menu1"		=> $result1->n_menu);
			
			
			$sqlMenu2 = "select a.c_menu_level, a.n_menu
						from adm.tm_menu a, adm.tm_aplikasi b
						where a.i_aplikasi = b.i_aplikasi
						  and a.c_menu_level = '$cMenuLevel2'
						  and a.i_aplikasi = $iAplikasi";

			$result2 = $db->fetchRow("$sqlMenu2");
			
			$hasilAkhir["c_menu_level2"]= $result2->c_menu_level;
			$hasilAkhir["n_menu2"] 		= $result2->n_menu;
			
			$sqlMenu3 = "select a.c_menu_level, a.n_menu
						from adm.tm_menu a, adm.tm_aplikasi b
						where a.i_aplikasi = b.i_aplikasi
						  and a.c_menu_level = '$cMenuLevel3'
						  and a.i_aplikasi = $iAplikasi";

			$result3 = $db->fetchRow("$sqlMenu3");
			
			$hasilAkhir["c_menu_level3"]= $result3->c_menu_level;
			$hasilAkhir["n_menu3"] 		= $result3->n_menu;
			
			$sqlMenu4 = "select a.c_menu_level, a.n_menu
						from adm.tm_menu a, adm.tm_aplikasi b
						where a.i_aplikasi = b.i_aplikasi
						  and a.c_menu_level = '$cMenuLevel4'
						  and a.i_aplikasi = $iAplikasi";

			$result4 = $db->fetchRow("$sqlMenu4");
			
			$hasilAkhir["c_menu_level4"]= $result4->c_menu_level;
			$hasilAkhir["n_menu4"] 		= $result4->n_menu;
			
			if(!$cMenuLevel4){
				if(!$cMenuLevel3){
					if(!$cMenuLevel2){
						$cMenuLevel = $cMenuLevel1;
					} else {
						$cMenuLevel = $cMenuLevel2;
					}
				} else {
					$cMenuLevel = $cMenuLevel3;
				}
			} else {
				$cMenuLevel = $cMenuLevel4;
			}
			
			$sqlMenu5 = "select a.e_menu, a.c_menu_statuscb, a.n_action
						from adm.tm_menu a, adm.tm_aplikasi b
						where a.i_aplikasi = b.i_aplikasi
						  and a.c_menu_level = '$cMenuLevel'
						  and a.i_aplikasi = $iAplikasi";

		  $result5 = $db->fetchRow("$sqlMenu5");
		
			$hasilAkhir["e_menu"] 			= $result5->e_menu;
			$hasilAkhir["c_menu_statuscb"] 	= $result5->c_menu_statuscb;
			$hasilAkhir["n_action"]			= $result5->n_action; 
			
			//var_dump($hasilAkhir);		
				//echo "xxx= $cMenuLevel";
			return $hasilAkhir;						  
	   } catch (Exception $e) {
	     return ;
	   }	
	
	}
	
	
	
	
}
?>