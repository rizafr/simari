<?php
class Sdm_Cuti_Service {
    private static $instance;
   
    // A private constructor; prevents direct creation of object
    private function __construct() {
       //echo 'I am constructed';
    }

    // The singleton method
    public static function getInstance() {
       if (!isset(self::$instance)) {
           $c = __CLASS__;
           self::$instance = new $c;
       }

       return self::$instance;
    }

 	public function getCutiList($cari,$currentPage, $numToDisplay) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				if(($currentPage==0) && ($numToDisplay==0))
			{
				$data = $db->fetchOne("select count(*) from  e_sdm_cuti_0_tm where 1=1 $cari");
	
			}
			else		
			{	
				$xLimit=$numToDisplay;
				$xOffset=($currentPage-1)*$numToDisplay;				
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT i_peg_nip,c_cuti_jenis,d_cuti_mulai,d_cuti_akhir,q_cuti,d_cuti_ambil1,q_cuti_2,d_cuti_ambil2,
												a_cuti,a_cuti_rt,a_cuti_rw,a_cuti_kota,a_cuti_propinsi,a_cuti_kodepos,i_cuti_telponrumah,
												i_cuti_telponhp,e_cuti_alasan,e_cuti_catatanatasan,e_cuti_catatansdm,c_cuti_status,i_entry,d_entry
												FROM e_sdm_cuti_0_tm where 1=1 $cari  order by i_peg_nip limit $xLimit offset $xOffset");
											
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$n_peg= $db->FetchOne('SELECT n_peg FROM e_sdm_pegawai_0_tm WHERE i_peg_nip = ?',$result[$j]->i_peg_nip);
						$c_peg_golongan= $db->FetchOne('SELECT c_peg_golongan FROM e_sdm_pegawai_0_tm WHERE i_peg_nip = ?',$result[$j]->i_peg_nip);
						$n_jabatan= $db->FetchOne('SELECT n_peg_pangkat FROM e_sdm_golongan_pangkat_tr WHERE c_peg_golongan = ?',$c_peg_golongan);
						
						$data[$j] = array("i_peg_nip"=>(string)$result[$j]->i_peg_nip,
										"c_cuti_jenis"=>(string)$result[$j]->c_cuti_jenis,
										"d_cuti_mulai"=>(string)$result[$j]->d_cuti_mulai,
										"d_cuti_akhir"=>(string)$result[$j]->d_cuti_akhir,
										"q_cuti"=>(string)$result[$j]->q_cuti,
										"d_cuti_ambil1"=>(string)$result[$j]->d_cuti_ambil1,
										"q_cuti_2"=>(string)$result[$j]->q_cuti_2,
										"d_cuti_ambil2"=>(string)$result[$j]->d_cuti_ambil2,
										"a_cuti"=>(string)$result[$j]->a_cuti,
										"a_cuti_rt"=>(string)$result[$j]->a_cuti_rt,
										"a_cuti_rw"=>(string)$result[$j]->a_cuti_rw,
										"a_cuti_kota"=>(string)$result[$j]->a_cuti_kota,
										"a_cuti_propinsi"=>(string)$result[$j]->a_cuti_propinsi,
										"a_cuti_kodepos"=>(string)$result[$j]->a_cuti_kodepos,
										"i_cuti_telponrumah"=>(string)$result[$j]->i_cuti_telponrumah,
										"i_cuti_telponhp"=>(string)$result[$j]->i_cuti_telponhp,
										"e_cuti_alasan"=>(string)$result[$j]->e_cuti_alasan,
										"e_cuti_catatanatasan"=>(string)$result[$j]->e_cuti_catatanatasan,
										"e_cuti_catatansdm"=>(string)$result[$j]->e_cuti_catatansdm,
										"c_cuti_status"=>(string)$result[$j]->c_cuti_status,
										"i_entry"=>(string)$result[$j]->i_entry,
										"d_entry"=>(string)$result[$j]->d_entry,
										"n_peg"=>$n_peg,
										"n_jabatan"=>$n_jabatan);	
					}
			}							
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}

	public function getSisaCutiList($cari,$currentPage, $numToDisplay) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				if(($currentPage==0) && ($numToDisplay==0))
			{
				$data = $db->fetchOne("select count(*) from  e_sdm_cuti_0_tm where 1=1 $cari");
	
			}
			else		
			{	
				$xLimit=$numToDisplay;
				$xOffset=($currentPage-1)*$numToDisplay;				
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT i_peg_nip,q_cuti_thnskrg,q_cuti_thnlalu,to_char(d_cuti_besar,'dd-mm-yyyy') as d_cuti_besar,c_cuti_ambil,i_entry,d_entry
										FROM e_sdm_cuti_sisa_tm where 1=1 $cari  order by i_peg_nip limit $xLimit offset $xOffset");
									
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$n_peg= $db->FetchOne('SELECT n_peg FROM e_sdm_pegawai_0_tm WHERE i_peg_nip = ?',$result[$j]->i_peg_nip);
						$c_peg_golongan= $db->FetchOne('SELECT c_peg_golongan FROM e_sdm_pegawai_0_tm WHERE i_peg_nip = ?',$result[$j]->i_peg_nip);
						$n_jabatan= $db->FetchOne('SELECT n_peg_pangkat FROM e_sdm_golongan_pangkat_tr WHERE c_peg_golongan = ?',$c_peg_golongan);
						
						$data[$j] = array("i_peg_nip"=>(string)$result[$j]->i_peg_nip,
										"q_cuti_thnskrg"=>(string)$result[$j]->q_cuti_thnskrg,
										"q_cuti_thnlalu"=>(string)$result[$j]->q_cuti_thnlalu,
										"d_cuti_besar"=>(string)$result[$j]->d_cuti_besar,
										"c_cuti_ambil"=>(string)$result[$j]->c_cuti_ambil,
										"i_entry"=>(string)$result[$j]->i_entry,
										"d_entry"=>(string)$result[$j]->d_entry,
										"n_peg"=>$n_peg,
										"n_jabatan"=>$n_jabatan);	
					}
			}							
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}

	public function getCutiRef($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT c_cuti_jenis,n_cuti FROM e_sdm_cuti_0_tr where 1=1 $cari  order by c_cuti_jenis");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{						
						$data[$j] = array("c_cuti_jenis"=>(string)$result[$j]->c_cuti_jenis,
										"n_cuti"=>(string)$result[$j]->n_cuti);	
					}
						
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}	


function kekata($x) {
    $x = abs($x);
    $angka = array("", "satu", "dua", "tiga", "empat", "lima",
    "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($x <12) {
        $temp = " ". $angka[$x];
    } else if ($x <20) {
        $temp = kekata($x - 10). " belas";
    } else if ($x <100) {
        $temp = kekata($x/10)." puluh". kekata($x % 10);
    } else if ($x <200) {
        $temp = " seratus" . kekata($x - 100);
    } else if ($x <1000) {
        $temp = kekata($x/100) . " ratus" . kekata($x % 100);
    } else if ($x <2000) {
        $temp = " seribu" . kekata($x - 1000);
    } else if ($x <1000000) {
        $temp = kekata($x/1000) . " ribu" . kekata($x % 1000);
    } else if ($x <1000000000) {
        $temp = kekata($x/1000000) . " juta" . kekata($x % 1000000);
    } else if ($x <1000000000000) {
        $temp = kekata($x/1000000000) . " milyar" . kekata(fmod($x,1000000000));
    } else if ($x <1000000000000000) {
        $temp = kekata($x/1000000000000) . " trilyun" . kekata(fmod($x,1000000000000));
    }      
        return $temp;
}
function terbilang($x, $style=4) {
    if($x<0) {
        $hasil = "minus ". trim($this->kekata($x));
    } else {
        $hasil = trim($this->kekata($x));
    }      
    switch ($style) {
        case 1:
            $hasil = strtoupper($hasil);
            break;
        case 2:
            $hasil = strtolower($hasil);
            break;
        case 3:
            $hasil = ucwords($hasil);
            break;
        default:
            $hasil = ucfirst($hasil);
            break;
    }      
    return $hasil;
}	
}
?>