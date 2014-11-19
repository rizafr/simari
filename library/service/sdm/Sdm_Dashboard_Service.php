<?php
class Sdm_Dashboard_Service {
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

 	public function getPendidikan() 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 

/* 			$result = $db->fetchAll("select count(*) as sd,
			(select count(*) from e_sdm_peg_pendterakhir_vm where c_pend='2' ) as smp,
			( select count(*) from e_sdm_peg_pendterakhir_vm where c_pend='3' ) as sma,
			( select count(*) from e_sdm_peg_pendterakhir_vm where c_pend='4' ) as d1,
			(select count(*) from e_sdm_peg_pendterakhir_vm where c_pend='5' ) as d2,
			(select count(*) from e_sdm_peg_pendterakhir_vm where c_pend='6'  ) as d3,
			( select count(*) from e_sdm_peg_pendterakhir_vm where c_pend='8' ) as s1,
			( select count(*) from e_sdm_peg_pendterakhir_vm where c_pend='9' ) as s2,
			(select count(*) from e_sdm_peg_pendterakhir_vm where c_pend='10'  ) as s3
			 from e_sdm_peg_pendterakhir_vm where c_pend='1' "); */

			$result = $db->fetchAll("select count(*) as sd,
			(select count(*) from sdm_pegawai_tm where c_pend='2' ) as smp,
			( select count(*) from sdm_pegawai_tm where c_pend='3' ) as sma,
			( select count(*) from sdm_pegawai_tm where c_pend='4' ) as d1,
			(select count(*) from sdm_pegawai_tm where c_pend='5' ) as d2,
			(select count(*) from sdm_pegawai_tm where c_pend='6'  ) as d3,
			( select count(*) from sdm_pegawai_tm where c_pend='8' ) as s1,
			( select count(*) from sdm_pegawai_tm where c_pend='9' ) as s2,
			(select count(*) from sdm_pegawai_tm where c_pend='10'  ) as s3
			 from sdm_pegawai_tm where c_pend='1' ");
			 
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) 
			{	
				
				$data[$j] = array("sd"=>(string)$result[$j]->sd,
									"smp"=>(string)$result[$j]->smp,
									"sma"=>(string)$result[$j]->sma,
									"d1"=>(string)$result[$j]->d1,
									"d2"=>(string)$result[$j]->d2,
									"d3"=>(string)$result[$j]->d3,
									"s1"=>(string)$result[$j]->s1,
									"s2"=>(string)$result[$j]->s2,
									"s3"=>(string)$result[$j]->s3);					
							
			}
		     	return $data;
		   } 
		   catch (Exception $e) 
		   {
	         	echo $e->getMessage().'<br>';
		     	return 'Data tidak ada <br>';
		   }
	}

	public function getUsia() 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$result = $db->fetchAll("select  to_char(d_peg_lahir,'yyyy-mm-dd')  as tgllahir from sdm_pegawai_tm ");
			$jml25=0;
			$jml2635=0;
			$jml3645=0;
			$jml4655=0;
			$jml56=0;
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) 
			{	


				$tgllahir = $result[$j]->tgllahir;
				$tglsekarang = date('Y-m-d');
				$lahir=$tgllahir;
				$selisih = ((time() - strtotime($lahir)));
				$tahun = floor ($selisih / 31536000);
				$bulan = floor (($selisih % 31536000) / 2592000);
				$selisih = ($tglsekarang*1 -$tgllahir*1);
				$selisih = number_format ($selisih / 86400, 2);
				if ($tahun*1<=25){
					$jml25=$jml25*1+1;
				}
				if ($tahun*1>=26 && $tahun*1<=35 ){
					$jml2635=$jml2635*1+1;
				}
				if ($tahun*1>=36 && $tahun*1<=45 ){
					$jml3645=$jml3645*1+1;
				}
				if ($tahun*1>=46 && $tahun*1<=55 ){
					$jml4655=$jml4655*1+1;
				}
				if ($tahun*1>=56){
					$jml56=$jml56*1+1;
				}
					
							
			}
				$data[0] = array("jml25"=>(string)$jml25,
								"jml2635"=>(string)$jml2635,
								"jml3645"=>(string)$jml3645,
								"jml4655"=>(string)$jml4655,
								"jml56"=>(string)$jml56);				
		     	return $data;
		   } 
		   catch (Exception $e) 
		   {
	         	echo $e->getMessage().'<br>';
		     	return 'Data tidak ada <br>';
		   }
	}	
	
	public function getGolPangkat() 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 

			$result = $db->fetchAll("select count(*) as gol1,
			(select count(*) from e_sdm_peg_golonganterakhir_vm where max_gol like 'II/%') as gol2,
			(select count(*) from e_sdm_peg_golonganterakhir_vm where max_gol like 'III/%') as gol3,
			(select count(*) from e_sdm_peg_golonganterakhir_vm where max_gol like 'IV/%') as gol4
			 from e_sdm_peg_golonganterakhir_vm where max_gol like 'I/%' ");
			 
/* 			$result = $db->fetchAll("select count(*) as gol1,
			(select count(*) from sdm_pegawai_tm where c_peg_golongan like 'II/%') as gol2,
			(select count(*) from sdm_pegawai_tm where c_peg_golongan like 'III/%') as gol3,
			(select count(*) from sdm_pegawai_tm where c_peg_golongan like 'IV/%') as gol4
			 from sdm_pegawai_tm where c_peg_golongan like 'I/%' ");		 */	 
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) 
			{
				$data[$j] = array("gol1"=>(string)$result[$j]->gol1,
									"gol2"=>(string)$result[$j]->gol2,
									"gol3"=>(string)$result[$j]->gol3,
									"gol4"=>(string)$result[$j]->gol4);
			}
		     	return $data;
		   } 
		   catch (Exception $e) 
		   {
	         	echo $e->getMessage().'<br>';
		     	return 'Data tidak ada <br>';
		   }
	}
	public function getReminderPensiun() 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$result = $db->fetchAll("select  to_char(d_peg_lahir,'yyyy-mm-dd')  as tgllahir from sdm_pegawai_tm ");
			$jmlpensiun=0;
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) 
			{	
				$tgllahir = $result[$j]->tgllahir;
				$tglsekarang = date('Y-m-d');
				$lahir=$tgllahir;
				$selisih = ((time() - strtotime($lahir)));
				$tahun = floor ($selisih / 31536000);
				$bulan = floor (($selisih % 31536000) / 2592000);
				$selisih = ($tglsekarang*1 -$tgllahir*1);
				$selisih = number_format ($selisih / 86400, 2);
				if ($tahun*1==57){
					$jmlpensiun=$jmlpensiun*1+1;
				}	
							
			}
				$data[0] = array("jmlpensiun"=>$jmlpensiun);				
		     	return $data;
		   } 
		   catch (Exception $e) 
		   {
	         	echo $e->getMessage().'<br>';
		     	return 'Data tidak ada <br>';
		   }
	}	

	public function getReminderGolPangkat() 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$result = $db->fetchAll("select  to_char(d_peg_tmtgolongan,'yyyy-mm-dd')  as tgltmtgol,
			to_char(d_peg_tmtgolongan,'yyyy')  as thn ,
			to_char(d_peg_tmtgolongan,'mm')  as bln ,
			to_char(d_peg_tmtgolongan,'dd')  as hrn 
			from e_sdm_peg_golonganterakhir_vm ");
			$jmlgolpangkat=0;
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) 
			{	
				$day= $result[$j]->hrn;
				$month= $result[$j]->bln;
				$year= $result[$j]->thn;
				$tgtmt=$this->getThnKdpn($day, $month, $year,4);
				$tgltmtgol = $tgtmt."-".$month."-".$day;				
				$tglsekarang = date('Y-m-d');
				$tmtgol=$tgltmtgol;
				$selisih = ((time() - strtotime($tmtgol)));
				$tahun = floor ($selisih / 31536000);
				$bulan = floor (($selisih % 31536000) / 2592000);
				$selisih = ($tglsekarang*1 -$tgltmtgol*1);
				$selisih = number_format ($selisih / 86400, 2);
				if ($tahun*1==0 && $bulan*1<=6){
					$jmlgolpangkat=$jmlgolpangkat*1+1;
				}	
				//echo "tahun ".$tahun." Bulan ".$bulan."<br>";
			}
				$data[0] = array("jmlgolpangkat"=>$jmlgolpangkat);				
		     	return $data;
		   } 
		   catch (Exception $e) 
		   {
	         	echo $e->getMessage().'<br>';
		     	return 'Data tidak ada <br>';
		   }
	}

	public function getReminderPensiunList() 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$result = $db->fetchAll("select  i_peg_nip,n_peg,to_char(d_peg_lahir,'yyyy-mm-dd')  as tgllahir,to_char(d_peg_lahir,'dd-mm-yyyy')  as tgllahira from sdm_pegawai_tm ");
			$jmlpensiun=0;
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) 
			{	
				$tgllahir = $result[$j]->tgllahir;
				$tglsekarang = date('Y-m-d');
				$lahir=$tgllahir;
				$selisih = ((time() - strtotime($lahir)));
				$tahun = floor ($selisih / 31536000);
				$bulan = floor (($selisih % 31536000) / 2592000);
				$selisih = ($tglsekarang*1 -$tgllahir*1);
				$selisih = number_format ($selisih / 86400, 2);
				if ($tahun*1==57){
					$data[$j] = array("i_peg_nip"=>$result[$j]->i_peg_nip,
									"n_peg"=>$result[$j]->n_peg,
									"tanggal"=>$result[$j]->tgllahira);	
				}	
							
			}
							
		     	return $data;
		   } 
		   catch (Exception $e) 
		   {
	         	echo $e->getMessage().'<br>';
		     	return 'Data tidak ada <br>';
		   }
	}

	public function getReminderGolPangkatList() 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$result = $db->fetchAll("select i_peg_nip, to_char(d_peg_tmtgolongan,'dd-mm-yyyy')  as tgltmtgola, to_char(d_peg_tmtgolongan,'yyyy-mm-dd')  as tgltmtgol,
			to_char(d_peg_tmtgolongan,'yyyy')  as thn ,
			to_char(d_peg_tmtgolongan,'mm')  as bln ,
			to_char(d_peg_tmtgolongan,'dd')  as hrn 
			from e_sdm_peg_golonganterakhir_vm ");
			$jmlgolpangkat=0;
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) 
			{	
				$day= $result[$j]->hrn;
				$month= $result[$j]->bln;
				$year= $result[$j]->thn;
				$tgtmt=$this->getThnKdpn($day, $month, $year,4);
				$tgltmtgol = $tgtmt."-".$month."-".$day;				
				$tglsekarang = date('Y-m-d');
				$tmtgol=$tgltmtgol;
				$selisih = ((time() - strtotime($tmtgol)));
				$tahun = floor ($selisih / 31536000);
				$bulan = floor (($selisih % 31536000) / 2592000);
				$selisih = ($tglsekarang*1 -$tgltmtgol*1);
				$selisih = number_format ($selisih / 86400, 2);
				if ($tahun*1==0 && $bulan*1<=6){
					$jmlgolpangkat=$jmlgolpangkat*1+1;
					$i_peg_nip=$result[$j]->i_peg_nip;
					$n_peg= $db->fetchOne("select  n_peg from  sdm_pegawai_tm where i_peg_nip='$i_peg_nip'");
					$data[$j] = array("i_peg_nip"=>$result[$j]->i_peg_nip,
									"n_peg"=>$n_peg,
									"tanggal"=>$result[$j]->tgltmtgola);
				}
			}
							
		     	return $data;
		   } 
		   catch (Exception $e) 
		   {
	         	echo $e->getMessage().'<br>';
		     	return 'Data tidak ada <br>';
		   }
	}	

	
	function getThnKdpn($day, $month, $year,$next)
	{
		$jm = (int)$year+(int)$next;
		if((int) $month > date('m')) $jm–;
		elseif(date('m') == $month*1)
		{if((int) $day > date('j')) $jm–;}
		return $jm;
	} 	
}
?>