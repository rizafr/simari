<?php
class Sdm_Cv_Service {
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
	

 	public function getPegawaiListByPnsNip($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{		
				
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll(" Select n_peg,n_peg_gelardepan,n_peg_gelarblkg,i_peg_nip,i_peg_nip_new,
											c_eselon,to_char(d_tmt_eselon,'dd-mm-yyyy') as d_tmt_eselon,c_status_kepegawaian,
											c_lokasi_unitkerja,c_eselon_i,c_eselon_ii,c_eselon_iii,c_eselon_iv,c_eselon_v,
											to_char(d_tmt_cpns,'dd-mm-yyyy') as d_tmt_cpns,c_peg_status,c_golongan,
											to_char(d_peg_lahir,'dd-mm-yyyy') as d_peg_lahir,c_peg_propinsi_lahir,a_peg_kota_lahir,
											c_peg_jeniskelamin,c_peg_statusnikah,q_peg_tinggibdn,q_peg_beratbdn,n_peg_rambut,
											n_peg_btkmuka,n_peg_warnakulit,n_peg_cirikhas,a_peg_rumah,a_peg_rt,a_peg_rw,a_peg_kelurahan,
											a_peg_kecamatan,a_peg_kota,a_peg_propinsi,a_peg_kodepos,i_peg_telponrumah,i_peg_telponhp,
											c_golongan_darah,n_peg_hobi,i_peg_karpeg,e_file_photo
											FROM sdm.tm_pegawai where 1=1 $cari");
									
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
						$c_eselon=trim($result[$j]->c_eselon);
						$c_status_kepegawaian =trim($result[$j]->c_status_kepegawaian);
						$n_eselon= $db->fetchOne("SELECT n_eselon FROM sdm.tr_eselon WHERE c_eselon ='$c_eselon'");	

						$c_lokasi_unitkerja=trim($result[$j]->c_lokasi_unitkerja);
						$c_eselon_i=trim($result[$j]->c_eselon_i);
						$c_eselon_ii=trim($result[$j]->c_eselon_ii);
						$c_eselon_iii=trim($result[$j]->c_eselon_iii);
						$c_eselon_iv=trim($result[$j]->c_eselon_iv);
						$c_eselon_v=trim($result[$j]->c_eselon_v);						

						$nesl1 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_tkt_esl='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
						$nesl2 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_ii='$c_eselon_ii' and c_eselon_i='$c_eselon_i' and c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");							
						$nesl3 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE  c_eselon_iii='$c_eselon_iii' and c_eselon_ii='$c_eselon_ii' and c_eselon_i='$c_eselon_i' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
						$nesl4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_iv='$c_eselon_iv' and c_eselon_iii='$c_eselon_iii' and c_eselon_ii='$c_eselon_ii' and c_eselon_i='$c_eselon_i' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
						$c_peg_status=trim($result[$j]->c_peg_status);
						$n_peg_status= $db->fetchOne("SELECT n_peg_status FROM sdm.tr_status_pegawai WHERE c_peg_status ='$c_peg_status'");
						$c_golongan=trim($result[$j]->c_golongan);
						$n_pangkat= $db->fetchOne("SELECT n_peg_pangkat FROM sdm.tr_golongan_pangkat WHERE c_peg_golongan ='$c_golongan' and c_peg_tipegolongan='$c_status_kepegawaian'");	
						
						$n_golongan= $db->fetchOne("SELECT n_peg_golongan FROM sdm.tr_golongan_pangkat WHERE c_peg_golongan ='$c_golongan' and c_peg_tipegolongan='$c_status_kepegawaian'");
						
						// if ($nesl1){$nesl1=",$nesl1";}
						// if ($nesl2){$nesl2=",$nesl2";}
						// if ($nesl3){$nesl3=",$nesl3";}
						// if ($nesl4){$nesl4=" $nesl4";}
	
						$n_unit_kerja=$nesl4.($nesl4 && $nesl3 ? ", " :"").$nesl3.($nesl3 && $nesl2 ? ", " :"").$nesl2.($nesl2 && $nesl1 ? ", " :"").$nesl1;
						
						$c_propinsi=trim($result[$j]->a_peg_propinsi);
						$n_propinsi= $db->fetchOne("SELECT n_propinsi FROM sdm.tr_propinsi WHERE c_propinsi ='$c_propinsi'");
						$c_kabupaten=trim($result[$j]->a_peg_kota);
						$n_kabupaten= $db->fetchOne("SELECT n_kabupaten FROM sdm.tr_kabupaten WHERE c_kabupaten ='$c_kabupaten' and c_propinsi ='$c_propinsi' ");
						$c_peg_statusnikah=trim($result[$j]->c_peg_statusnikah);
						switch ($c_peg_statusnikah) {case "M":$statusnikah="Menikah";break;case "B":$statusnikah="Belum Menikah";break;case "J":$statusnikah="Menikah";break;case "D":$statusnikah="Duda";break;default:$statusnikah="-";}
						$c_peg_jeniskelamin="";
						$c_peg_jeniskelamin=trim($result[$j]->c_peg_jeniskelamin);
						if ($c_peg_jeniskelamin=="L"){$c_peg_jeniskelamin="Laki-laki";}
						if ($c_peg_jeniskelamin=="P"){$c_peg_jeniskelamin="Perempuan";}
						
		
						$c_peg_propinsi_lahir=trim($result[$j]->c_peg_propinsi_lahir);
						$n_propinsi_lahir= $db->fetchOne("SELECT n_propinsi FROM sdm.tr_propinsi WHERE c_propinsi ='$c_peg_propinsi_lahir'");
						$a_peg_kota_lahir=trim($result[$j]->a_peg_kota_lahir);
						$n_peg_kota_lahir= $db->fetchOne("SELECT n_kabupaten FROM sdm.tr_kabupaten WHERE c_kabupaten ='$a_peg_kota_lahir' and c_propinsi ='$c_peg_propinsi_lahir' ");
						
						
						$data[$j] = array("i_peg_nip"=>(string)$result[$j]->i_peg_nip,
									"i_peg_nip_new"=>(string)$result[$j]->i_peg_nip_new,					
									"n_peg"=>(string)$result[$j]->n_peg,
									"n_peg_gelardepan"=>(string)$result[$j]->n_peg_gelardepan,
									"n_peg_gelarblkg"=>(string)$result[$j]->n_peg_gelarblkg,
									"n_eselon"=>$n_eselon,
									"c_status_kepegawaian"=>$c_status_kepegawaian,
									"d_tmt_eselon"=>(string)$result[$j]->d_tmt_eselon,
									"n_unit_kerja"=>$n_unit_kerja,
									"n_satker"=>$n_unit_kerja,
									"d_tmt_cpns"=>(string)$result[$j]->d_tmt_cpns, 
									"n_peg_status"=>$n_peg_status,
									"c_golongan"=>$c_golongan,
									"n_golongan"=>$n_golongan,									
									"n_pangkat"=>$n_pangkat,
									"d_peg_lahir"=>(string)$result[$j]->d_peg_lahir, 
									"c_peg_propinsi_lahir"=>(string)$result[$j]->c_peg_propinsi_lahir, 
									"n_propinsi_lahir"=>$n_propinsi_lahir, 									
									"a_peg_kota_lahir"=>(string)$result[$j]->a_peg_kota_lahir,
									"n_peg_kota_lahir"=>$n_peg_kota_lahir,
									"c_peg_jeniskelamin"=>$c_peg_jeniskelamin,
									"c_eselon"=>(string)$result[$j]->c_eselon,
									"d_tmt_eselon"=>(string)$result[$j]->d_tmt_eselon,
									"c_lokasi_unitkerja"=>(string)$result[$j]->c_lokasi_unitkerja,
									"c_eselon_i"=>(string)$result[$j]->c_eselon_i,
									"c_eselon_ii"=>(string)$result[$j]->c_eselon_ii,
									"c_eselon_iii"=>(string)$result[$j]->c_eselon_iii,
									"c_eselon_iv"=>(string)$result[$j]->c_eselon_iv,
									"c_eselon_v"=>(string)$result[$j]->c_eselon_v,
									"d_tmt_cpns"=>(string)$result[$j]->d_tmt_cpns,
									"c_peg_status"=>(string)$result[$j]->c_peg_status,
									"c_golongan"=>(string)$result[$j]->c_golongan,
									"d_peg_lahir"=>(string)$result[$j]->d_peg_lahir,
									"c_peg_propinsi_lahir"=>(string)$result[$j]->c_peg_propinsi_lahir,
									"a_peg_kota_lahir"=>(string)$result[$j]->a_peg_kota_lahir,
									"c_peg_statusnikah"=>$statusnikah,
									"q_peg_tinggibdn"=>(string)$result[$j]->q_peg_tinggibdn,
									"q_peg_beratbdn"=>(string)$result[$j]->q_peg_beratbdn,
									"n_peg_rambut"=>(string)$result[$j]->n_peg_rambut,
									"n_peg_btkmuka"=>(string)$result[$j]->n_peg_btkmuka,
									"n_peg_warnakulit"=>(string)$result[$j]->n_peg_warnakulit,
									"n_peg_cirikhas"=>(string)$result[$j]->n_peg_cirikhas,
									"a_peg_rumah"=>(string)$result[$j]->a_peg_rumah,
									"a_peg_rt"=>(string)$result[$j]->a_peg_rt,
									"a_peg_rw"=>(string)$result[$j]->a_peg_rw,
									"a_peg_kelurahan"=>(string)$result[$j]->a_peg_kelurahan,
									"a_peg_kecamatan"=>(string)$result[$j]->a_peg_kecamatan,
									"a_peg_kota"=>$n_kabupaten,
									"a_peg_propinsi"=>$n_propinsi,
									"a_peg_kodepos"=>(string)$result[$j]->a_peg_kodepos,
									"i_peg_telponrumah"=>(string)$result[$j]->i_peg_telponrumah,
									"i_peg_telponhp"=>(string)$result[$j]->i_peg_telponhp,
									"c_golongan_darah"=>(string)$result[$j]->c_golongan_darah,
									"n_peg_hobi"=>(string)$result[$j]->n_peg_hobi,
									"i_peg_karpeg"=>(string)$result[$j]->i_peg_karpeg,
									"e_file_photo"=>(string)$result[$j]->e_file_photo);	
					}
									
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}	
	
	public function getNip($nip) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
  
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$sql = "select i_peg_nip from sdm.tm_pegawai a  where  i_peg_nip_new='$nip'";
					$data = $db->fetchOne($sql);	
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}	
}
?>