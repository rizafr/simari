<?php

class Adm_Admgroup_Service {
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

	public function getGrouplist($dataMasukan) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	        $db->setFetchMode(Zend_Db::FETCH_OBJ); 

			$pageNumber = $dataMasukan['pageNumber'];
			$itemPerPage = $dataMasukan['itemPerPage'];
			$kategoriCari	=  $dataMasukan['kategoriCari'];
			$kataKunciCari	=  $dataMasukan['kataKunciCari'];
			$sortBy			=  $dataMasukan['sortBy'];
			$sortOrder		=  $dataMasukan['sortOrder'];
			$userid			=  $dataMasukan['userid'];
			
			if($kategoriCari == 'i_group'){
				$whereOpt = " where $kategoriCari = '$kataKunciCari'";
			} else if($kategoriCari == 'n_group'){
				$whereOpt = " where $kategoriCari like '%$kataKunciCari%'";
			}
			if(strtolower($userid) != 'administrator'){
				if($whereOpt){
					$whereOpt = $whereOpt." and n_group_owner like '$userid%'";
				} else {
					$whereOpt = "where n_group_owner like '$userid%'";
				}
			}
			
			$sql1 = "select i_group, n_group, n_group_owner
					 from adm.tm_group
					 $whereOpt";
			$sql2 = "select i_group, n_group, n_group_owner
					 from adm.tm_group";
					 //echo $sql1."<br>";
					 //echo $sql2."<br>";
			if(($pageNumber == 0) && ($itemPerPage == 0)){
			//echo "xxx";
				$hasilAkhir = $db->fetchOne("select count(b.i_group) from ($sql1) b");
			} else if(($pageNumber == 9999) && ($itemPerPage == 9999)){
			//echo "yyy";
				$result = $db->fetchAll($sql2." order by $sortBy $sortOrder");
			} else {
			//echo "$sql1";
				$result = $db->fetchAll($sql1." order by $sortBy $sortOrder");
			}
			
			
			for ($j=0; $j<count($result); $j++){
				$hasilAkhir[$j] = array("i_group" 		=>(string)$result[$j]->i_group,	
										"n_group" 		=>(string)$result[$j]->n_group,	
										"n_group_owner" =>(string)$result[$j]->n_group_owner);
			}	
			return $hasilAkhir;						  
	   } catch (Exception $e) {
	     return ;
	   }	
	
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
											order by 2,3,4,5");
					
					
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
	
	
	
	public function groupTambah(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$n_group = $dataMasukan['n_group'];
			$n_group_owner = $dataMasukan['n_group_owner'];
			$c_eselon = $dataMasukan['c_eselon'];
			$c_jabatan = $dataMasukan['c_jabatan'];
			$c_lokasi_unitkerja = $dataMasukan['c_lokasi_unitkerja'];
			$c_eselon_i = $dataMasukan['c_eselon_i'];
			$c_eselon_ii = $dataMasukan['c_eselon_ii'];
			$c_eselon_iii = $dataMasukan['c_eselon_iii'];
			$c_eselon_iv = $dataMasukan['c_eselon_iv'];
			$n_eselon_v = $dataMasukan['c_eselon_v']; //di controller nya yg dicatat nama eselon 5 nya
			/*
if ($c_lokasi_unitkerja == '3'){
				$c_eselon_v = $db->fetchOne("SELECT c_eselon_v 
											 FROM sdm.tr_unitkerja 
											 WHERE c_lokasi_unitkerja='$c_lokasi_unitkerja' 
											   and c_eselon_i='$c_eselon_i' 
											   and c_eselon_ii='$c_eselon_ii' 
											   and c_eselon_iii='$c_eselon_iii' 
											   and c_eselon_iv='$c_eselon_iv' 
											   and n_unitkerja='$n_eselon_v'
											");
			} else {
*/
				$c_eselon_v = $dataMasukan['c_eselon_v'];
			//}
			/* echo "<br>n_eselon_v = $n_eselon_v | SELECT c_eselon_v 
										 FROM sdm.tr_unitkerja 
										 WHERE c_lokasi_unitkerja='$c_lokasi_unitkerja' 
										   and c_eselon_i='$c_eselon_i' 
										   and c_eselon_ii='$c_eselon_ii' 
										   and c_eselon_iii='$c_eselon_iii' 
										   and c_eselon_iv='$c_eselon_iv' 
										   and n_unitkerja='$n_eselon_v'<br>"; */
			$c_wewenang = $dataMasukan['c_wewenang'];
			$c_sektoral = $dataMasukan['c_sektoral'];
			$c_parent = $dataMasukan['c_parent'];
			$c_satker = $dataMasukan['c_satker'];
			
			
			$menuArr = $dataMasukan['menuArr'];
			$i_entry = $dataMasukan['i_entry'];
			
			$paramInputGroup = array("n_group"		=> $n_group,
									 "n_group_owner" =>$n_group_owner,
									 "c_eselon" 	=>$c_eselon,
									 "c_jabatan" 	=>$c_jabatan,
									 "c_lokasi_unitkerja" =>$c_lokasi_unitkerja,
									 "c_eselon_i" 	=>$c_eselon_i,
									 "c_eselon_ii" 	=>$c_eselon_ii,
									 "c_eselon_iii" =>$c_eselon_iii,
									 "c_eselon_iv" 	=>$c_eselon_iv,
									 "c_eselon_v" 	=>$c_eselon_v,
									 "c_wewenang" 	=>$c_wewenang,
									 "c_sektoral" 	=>$c_sektoral,
									 "c_parent" 	=>$c_parent,
									 "c_satker" 	=>$c_satker,
									 "i_entry"		=> $i_entry);
									 //var_dump($paramInputGroup);
			$db->insert('adm.tm_group',$paramInputGroup);
			$i_group = $db->fetchOne("select i_group
									  from adm.tm_group
									  where n_group = '$n_group'");
									  //echo "i_group = $i_group";
			if(count($menuArr)){
				for($x=0;$x<count($menuArr); $x++){
					$Aplikasi_Level = $menuArr[$x];
					$Aplikasi_LevelArr = explode('_',$Aplikasi_Level);
					$i_aplikasi = $Aplikasi_LevelArr[0];
					$c_menu_level = $Aplikasi_LevelArr[1];
					$paramInputGroupMenu = array("i_group"		=> $i_group,
												 "i_aplikasi"	=> $i_aplikasi,
												 "c_menu_level"	=> $c_menu_level);
					//var_dump($paramInputGroupMenu);							 
					$db->insert('adm.tm_group_menu',$paramInputGroupMenu);	

					unset($paramInputGroupMenu);
				}
			} 
			 
			$db->commit();
			
			return "sukses";
			
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			
			$errMsg = $errmsgArr[0];

			if(($errMsg == "SQLSTATE[23000]") ||($errMsg == "SQLSTATE[23505]"))
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
	
	public function detailGroupMenu($data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	        $db->setFetchMode(Zend_Db::FETCH_OBJ); 

			$i_group 	 = $data['i_group'];
			$i_aplikasi 	 = $data['i_aplikasi'];
			
			$sqlMenu = "select a.i_group, b.i_aplikasi, b.c_menu_level, a.n_group 
						from adm.tm_group a
						left join adm.tm_group_menu b on (a.i_group = b.i_group) 
						where b.i_aplikasi = '$i_aplikasi'
						  and a.i_group = '$i_group'
						";
//echo "<br>$sqlMenu";
			$result = $db->fetchAll("$sqlMenu");
			
			for ($j=0; $j<count($result); $j++){
				$data[] = array("i_group" =>(string)$result[$j]->i_group,	
							"n_group" =>(string)$result[$j]->n_group,	
							"i_aplikasi" =>(string)$result[$j]->i_aplikasi,
							"c_menu_level" =>(string)$result[$j]->c_menu_level);
			}	
			//var_dump($data);
			return $data;						  
	   } catch (Exception $e) {
	     return ;
	   }	
	
	}
	
	public function detailGroup($data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	        $db->setFetchMode(Zend_Db::FETCH_OBJ); 

			$i_group 	 = $data['i_group'];
			
			$sqlMenu = "select a.*, b.n_jabatan 
						from adm.tm_group a left join sdm.tr_jabatan b on (a.c_jabatan = b.c_jabatan)
						where a.i_group = '$i_group'
						";
//echo "<br>$sqlMenu";
			$result = $db->fetchRow("$sqlMenu");
			if($result->c_lokasi_unitkerja == '1'){
			$n_eselon_i = $db->fetchOne("select n_unitkerja 
										 from sdm.tr_unitkerja 
										 where c_lokasi_unitkerja = '".$result->c_lokasi_unitkerja."'
										   and c_eselon_i		  = '".$result->c_eselon_i."'
										   and c_eselon_ii		  = '000'
										   and c_eselon_iii		  = '00'
										   and c_eselon_iv		  = '00'
										   and c_eselon_v		  = '00'
										   ");
			/* echo "select n_unitkerja 
										 from sdm.tr_unitkerja 
										 where c_lokasi_unitkerja = '".$result->c_lokasi_unitkerja."'
										   and c_eselon_i		  = '".$result->c_eselon_i."'
										   and c_eselon_ii		  = '000'
										   and c_eselon_iii		  = '00'
										   and c_eselon_iv		  = '00'
										   and c_eselon_v		  = '00'
										   "; */
			$n_eselon_ii = $db->fetchOne("select n_unitkerja 
										 from sdm.tr_unitkerja 
										 where c_lokasi_unitkerja = '".$result->c_lokasi_unitkerja."'
										   and c_eselon_i		  = '".$result->c_eselon_i."'
										   and c_eselon_ii		  = '".$result->c_eselon_ii."'
										   and c_eselon_iii		  = '00'
										   and c_eselon_iv		  = '00'
										   and c_eselon_v		  = '00'
										   ");
										   
			$n_eselon_iii = $db->fetchOne("select n_unitkerja 
										 from sdm.tr_unitkerja 
										 where c_lokasi_unitkerja = '".$result->c_lokasi_unitkerja."'
										   and c_eselon_i		  = '".$result->c_eselon_i."'
										   and c_eselon_ii		  = '".$result->c_eselon_ii."'
										   and c_eselon_iii		  = '".$result->c_eselon_iii."'
										   and c_eselon_iv		  = '00'
										   and c_eselon_v		  = '00'
										   ");
										   
			$n_eselon_iv = $db->fetchOne("select n_unitkerja 
										 from sdm.tr_unitkerja 
										 where c_lokasi_unitkerja = '".$result->c_lokasi_unitkerja."'
										   and c_eselon_i		  = '".$result->c_eselon_i."'
										   and c_eselon_ii		  = '".$result->c_eselon_ii."'
										   and c_eselon_iii		  = '".$result->c_eselon_iii."'
										   and c_eselon_iv		  = '".$result->c_eselon_iv."'
										   and c_eselon_v		  = '00'
										   ");
			} else {
				$c_satker=trim($result->c_satker);
				$c_eselon_i = $result->c_eselon_i;
				$c_eselon_ii = $result->c_eselon_ii;
				$c_eselon_iii = $result->c_eselon_iii;
				$c_eselon_iv = $result->c_eselon_iv;
				$c_eselon_v = $result->c_eselon_v;
				$c_parent = $result->c_parent;
				$c_lokasi_unitkerja = $result->c_lokasi_unitkerja;
				$c_jabatan = $result->c_jabatan;
				
				$ceselon2 = $db->fetchOne("SELECT distinct(c_eselon_ii) FROM sdm.tr_unitkerja WHERE c_satker='$c_satker' ");
				//echo "SELECT distinct(c_eselon_ii) FROM sdm.tr_unitkerja WHERE c_satker='$c_satker'";
				//echo "<br>ceselon2 = $ceselon2";
				if (!$ceselon2){$ceselon2=$c_eselon_ii;}
				
				$neselon5 = $db->fetchOne(" SELECT n_unitkerja 
										   FROM sdm.tr_unitkerja 
										   WHERE c_eselon_i='$c_eselon_i' 
										     and c_eselon_ii='$ceselon2' 
											 and c_eselon_iv='$c_eselon_iv' 
											 and c_eselon_v='$c_eselon_v' 
											 and c_lokasi_unitkerja='$c_lokasi_unitkerja' 
											 and c_satker='$c_satker'");
				if ($c_satker=='00'){
					$neselon4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2' and c_eselon_iv='$c_eselon_iv' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
				}else{
				//echo "<br>SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2' and c_eselon_iv='$c_eselon_iv' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_satker='$c_satker'<br>";
					$neselon4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2' and c_eselon_iv='$c_eselon_iv' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_satker='$c_satker'");
				}
				
				$neselon3 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2' and c_eselon_iii='$c_eselon_iii' and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child <> '00' and c_parent ='$c_parent'  and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
				//echo "xxx ".$neselon3;
				
				if ($neselon3){
					$ceseloniix = $db->fetchOne(" SELECT c_eselon_ii FROM sdm.tr_unitkerja WHERE c_level ='2' and c_eselon_i='$c_eselon_i' and c_parent='$c_parent' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
					$neselon2 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceseloniix' and c_eselon_iii = '00'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child = '00' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			
				}
				else{
					$neselon2 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii = '00'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child = '00' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
					//echo "xxxx".$neselon2;
				//	echo " SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii = '00'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child = '00' and c_lokasi_unitkerja='$c_lokasi_unitkerja'";
				}
				
				$neselon1 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_tkt_esl='1' ");

				if ($c_jabatan=='048' || $c_jabatan=='050')
				{
					$nama= strtolower($neselon2);
					$nama=ucwords(str_replace("pengadilan tinggi", "", $nama));
					$nesl2=$nama;
					$nesl1="";
					$nesl3="";
					$nesl4="";
				}
				elseif ($c_jabatan=='049' || $c_jabatan=='051')
				{
					$nama= strtolower($neselon3);
					$nama=ucwords(str_replace("pengadilan", "", $nama));
					$nesl2="";
					$nesl1="";
					$nesl3=$nama;
					$nesl4="";
				}
				elseif ($c_jabatan=='052')
				{
					$nama= strtolower($neselon2);
					$nesl2=ucwords($nama);
					$nesl1="";
					$nesl3="";
					$nesl4="";
				}
				else{
					$nama= strtolower($neselon3);							
					$nesl2="";
					$nesl1="";
					$nesl3=ucwords($nama);
					$nesl4="";
				}	 
				//echo "<br>c_jabatan = $c_jabatan | neselon1 = $neselon1 | ceseloniix = $ceseloniix | neselon2 = $neselon2 | neselon3 = $neselon3";
				
				$n_eselon_i = $neselon1;
				$n_eselon_ii = $neselon2;
				$n_eselon_iii = $neselon3;
				$n_eselon_iv = $neselon4;
				$n_eselon_v = $neselon5;
			}
			//for ($j=0; $j<count($result); $j++){
				$hasil = array("i_group" =>trim((string)$result->i_group),	
							"n_group" =>trim((string)$result->n_group),	
							"n_group_owner" =>trim((string)$result->n_group_owner),
							"c_eselon" =>trim((string)$result->c_eselon),
							"c_jabatan" =>trim((string)$result->c_jabatan),
							"n_jabatan" =>trim((string)$result->n_jabatan),
							"c_lokasi_unitkerja" =>trim((string)$result->c_lokasi_unitkerja),
							"c_eselon_i" =>trim((string)$result->c_eselon_i),
							"n_eselon_i" =>trim($n_eselon_i),
							"c_eselon_ii" =>trim((string)$result->c_eselon_ii),
							"n_eselon_ii" =>trim($n_eselon_ii),
							"c_eselon_iii" =>trim((string)$result->c_eselon_iii),
							"n_eselon_iii" =>trim($n_eselon_iii),
							"c_eselon_iv" =>trim((string)$result->c_eselon_iv),
							"n_eselon_iv" =>trim($n_eselon_iv),
							"c_eselon_v" =>trim((string)$result->c_eselon_v),
							"n_eselon_v" =>trim($n_eselon_v),
							"c_wewenang" =>trim((string)$result->c_wewenang),
							"c_sektoral" =>trim((string)$result->c_sektoral),
							"c_parent" => trim((string)$result->c_parent),
							"ceselon2"=>$ceselon2,
							"c_satker" => trim((string)$result->c_satker));
			//}	
			//var_dump($data);
			return $hasil;						  
	   } catch (Exception $e) {
	     return ;
	   }	
	
	}
	
	public function groupUbah(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$i_group = $dataMasukan['i_group'];
			$n_group = $dataMasukan['n_group'];
			$menuArr = $dataMasukan['menuArr'];
			$n_group_owner = $dataMasukan['n_group_owner'];
			/* $c_eselon = $dataMasukan['c_eselon'];
			$c_jabatan = $dataMasukan['c_jabatan'];
			$c_lokasi_unitkerja = $dataMasukan['c_lokasi_unitkerja'];
			$c_eselon_i = $dataMasukan['c_eselon_i'];
			$c_eselon_ii = $dataMasukan['c_eselon_ii'];
			$c_eselon_iii = $dataMasukan['c_eselon_iii'];
			$c_eselon_iv = $dataMasukan['c_eselon_iv'];
			$c_eselon_v = $dataMasukan['c_eselon_v']; */
			$c_wewenang = $dataMasukan['c_wewenang'];
			$c_sektoral = $dataMasukan['c_sektoral'];
			
			$c_satker = $dataMasukan['c_satker'];
			$c_parent = $dataMasukan['c_parent'];
			
			$i_entry = $dataMasukan['i_entry'];
			
			$paramInputGroup = array("n_group"	=> $n_group,
									 "n_group_owner" =>$n_group_owner,
									 "c_wewenang" 	=>$c_wewenang,
									 "c_sektoral" 	=>$c_sektoral,
									 "c_satker"		=> $c_satker,
									 "c_parent"		=> $c_parent,
									 "i_entry"	=> $i_entry);
									 
									/*  "c_eselon" 	=>$c_eselon,
									 "c_jabatan" 	=>$c_jabatan,
									 "c_lokasi_unitkerja" =>$c_lokasi_unitkerja,
									 "c_eselon_i" 	=>$c_eselon_i,
									 "c_eselon_ii" 	=>$c_eselon_ii,
									 "c_eselon_iii" =>$c_eselon_iii,
									 "c_eselon_iv" 	=>$c_eselon_iv,
									 "c_eselon_v" 	=>$c_eselon_v, */
			$whereGroup[] = "i_group = $i_group";
			$db->update('adm.tm_group',$paramInputGroup, $whereGroup);
			
			//var_dump($paramInputGroup);
			//delete di group_menu yg i_group nya dari masukan
			$db->delete('adm.tm_group_menu', $whereGroup);
			
			if(count($menuArr)){
				for($x=0;$x<count($menuArr); $x++){
					$Aplikasi_Level = $menuArr[$x];
					$Aplikasi_LevelArr = explode('_',$Aplikasi_Level);
					$i_aplikasi = $Aplikasi_LevelArr[0];
					$c_menu_level = $Aplikasi_LevelArr[1];
					$paramInputGroupMenu = array("i_group"		=> $i_group,
												 "i_aplikasi"	=> $i_aplikasi,
												 "c_menu_level"	=> $c_menu_level);
					//var_dump($paramInputGroupMenu);							 
					//echo "<br>$i_group | $i_aplikasi | $c_menu_level";
					$db->insert('adm.tm_group_menu',$paramInputGroupMenu);	

					unset($paramInputGroupMenu);
				}
			} 
			 
			$db->commit();
			
			return "sukses";
			
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			
			$errMsg = $errmsgArr[0];

			if(($errMsg == "SQLSTATE[23000]") ||($errMsg == "SQLSTATE[23505]"))
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				echo $e->getMessage();
				return $e->getMessage();
			}
	   }
	}
	
	public function groupHapus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
			
			$i_group = $dataMasukan['i_group'];
			
			$whereGroup[] = "i_group = $i_group";
			$db->delete('adm.tm_group', $whereGroup);
			
			//delete di group_menu yg i_group nya dari masukan
			$db->delete('adm.tm_group_menu', $whereGroup);
			
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
	
	public function getNamaGroup($data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	        $db->setFetchMode(Zend_Db::FETCH_OBJ); 

			$i_group 	 = $data['i_group'];
			$sqlNamaGroup = "select a.n_group
						from adm.tm_group a
						where a.i_group = '$i_group'";
//echo "<br>$level | $cMenuLevel1 | $cMenuLevel2 |$cMenuLevel3 | $cMenuLevel |$$sqlMenu";
			$nGroup = $db->fetchOne("$sqlNamaGroup");
			return $nGroup;						  
	   } catch (Exception $e) {
	     return ;
	   }	
	
	}
}
?>