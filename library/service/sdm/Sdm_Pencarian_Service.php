<?php
class Sdm_Pencarian_Service {
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
	

 	public function getPegawaiList($cari,$currentPage, $numToDisplay,$orderby) 
	{
	
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				if(($currentPage==0) && ($numToDisplay==0))
			{
				$data = $db->fetchOne("select count(*) from  sdm.tm_pegawai where 1=1 $cari");
	
			}
			else		
			{	
				$xLimit=$numToDisplay;
				$xOffset=($currentPage-1)*$numToDisplay;				
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT i_peg_nip,i_peg_nrp,i_peg_nip_new, n_peg,c_peg_status,c_status_kepegawaian,c_eselon,to_char(d_tmt_eselon,'dd-mm-yyyy') as d_tmt_eselon,
								c_jabatan,to_char(d_mulai_jabat,'dd-mm-yyyy') as d_mulai_jabat,c_peg_jeniskelamin,
								to_char(d_akhir_jabat,'dd-mm-yyyy') as d_akhir_jabat,c_golongan,d_tmt_golongan,v_gaji_pokok,
								c_lokasi_unitkerja,c_eselon_i,c_eselon_ii,c_eselon_iii,c_eselon_iv,c_eselon_v,c_gol_cpns,
								c_lokasi_unitkerja_cpns,c_eselon_cpns,e_file_photo,c_eselon_cpns,c_lokasi_unitkerja_cpns,c_eselon_i_cpns,
								c_eselon_ii_cpns,c_eselon_iii_cpns,c_eselon_iv_cpns,c_eselon_v_cpns,c_jabatan_cpns,to_char(d_tmt_kgb,'dd-mm-yyyy') as d_tmt_kgb,
								to_char(d_tmt_golongan,'dd-mm-yyyy') as d_tmt_golongan,
								to_char(d_mulai_jabat,'dd-mm-yyyy') as d_mulai_jabat,
								to_char(d_tmt_cpns,'dd-mm-yyyy') as d_tmt_cpns,
								to_char(d_peg_lahir,'dd-mm-yyyy') as d_peg_lahir
								FROM sdm.tm_pegawai where 1=1 $cari  $orderby   
								limit $xLimit offset $xOffset");
									
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
	$c_eselon_i="";
	$c_eselon_ii="";
	$c_eselon_iii="";
	$c_eselon_iv="";
	$c_eselon_v="";
	$c_eselon="";
	
$nesl1="";
$nesl2="";
$nesl3="";
$nesl4="";
$nesl5="";
$unitkerjalengkap="";


$c_eselon_i="";
$c_eselon_ii="";
$c_eselon_iii="";
$c_eselon_iv="";
$c_eselon_v="";
$c_eselon="";
$c_jabatan="";
$c_lokasi_unitkerja="";
$c_golongan="";
$c_peg_status="";
$n_eselon_i="";		

	if (trim($result[$j]->c_peg_status)!='2CP')
	{
		$c_eselon_i=trim($result[$j]->c_eselon_i);
		$c_eselon_ii=trim($result[$j]->c_eselon_ii);
		$c_eselon_iii=trim($result[$j]->c_eselon_iii);
		$c_eselon_iv=trim($result[$j]->c_eselon_iv);
		$c_eselon_v=trim($result[$j]->c_eselon_v);
		$c_eselon=trim($result[$j]->c_eselon);
		$c_jabatan=trim($result[$j]->c_jabatan);
		$c_lokasi_unitkerja=trim($result[$j]->c_lokasi_unitkerja);
		$c_golongan=trim($result[$j]->c_golongan);
		$c_peg_status=trim($result[$j]->c_peg_status);
	}
	else
	{
	
		$c_eselon_i=trim($result[$j]->c_eselon_i_cpns);
		$c_eselon_ii=trim($result[$j]->c_eselon_ii_cpns);
		$c_eselon_iii=trim($result[$j]->c_eselon_iii_cpns);
		$c_eselon_iv=trim($result[$j]->c_eselon_iv_cpns);
		$c_eselon_v=trim($result[$j]->c_eselon_v_cpns);
		$c_eselon=trim($result[$j]->c_eselon_cpns);
		$c_jabatan=trim($result[$j]->c_jabatan_cpns);
		$c_lokasi_unitkerja=trim($result[$j]->c_lokasi_unitkerja_cpns);
		$c_golongan=trim($result[$j]->c_gol_cpns);
		$c_peg_status=trim($result[$j]->c_peg_status);
	}


	$c_status_kepegawaian=trim($result[$j]->c_status_kepegawaian);
	$n_eselon = $db->fetchOne("select n_eselon  from sdm.tr_eselon where c_eselon='$c_eselon'");
	$n_jabatan = $db->fetchOne("select n_jabatan from sdm.tr_jabatan where c_jabatan='$c_jabatan'");
	$n_lokasi_unitkerja = $db->fetchOne("select n_lokasi  from sdm.tr_lokasi where c_lokasi='$c_lokasi_unitkerja'");
	$n_pangkat= $db->fetchOne("SELECT n_peg_pangkat FROM sdm.tr_golongan_pangkat WHERE c_peg_golongan ='$c_golongan'  and c_peg_tipegolongan='$c_status_kepegawaian'");
	$n_golongan= $db->fetchOne("SELECT n_peg_golongan FROM sdm.tr_golongan_pangkat WHERE c_peg_golongan ='$c_golongan'  and c_peg_tipegolongan='$c_status_kepegawaian'");
	$n_peg_status= $db->fetchOne("SELECT n_peg_status FROM sdm.tr_status_pegawai WHERE c_peg_status ='$c_peg_status'");
	$n_status_kepegawaian="";
	$n_status_kepegawaian= $db->fetchOne("SELECT n_status_kepegawaian FROM sdm.tr_status_kepegawaian WHERE c_status_kepegawaian ='$c_status_kepegawaian'");

	
	if ($c_eselon_i){
		$n_eselon_i = $db->fetchOne("select n_unitkerja from sdm.tr_unitkerja where  c_eselon_i='$c_eselon_i' and c_tkt_esl='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
		if ($n_eselon_i){$nesl1=" ,$n_eselon_i";}
		else{$nesl1="";}
	}
	if ($c_eselon_ii){
		$n_eselon_ii = $db->fetchOne("select n_unitkerja from sdm.tr_unitkerja where c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
		if ($n_eselon_ii){$nesl2=" ,$n_eselon_ii";}
		else{$nesl2="";}		
	}	
	if ($c_eselon_iii){
		$n_eselon_iii = $db->fetchOne("select n_unitkerja from sdm.tr_unitkerja where c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
		if ($n_eselon_iii){$nesl3=" ,$n_eselon_iii";}
		else{$nesl3="";}
		
	}
	if ($c_eselon_iv){
		$n_eselon_iv = $db->fetchOne("select n_unitkerja from sdm.tr_unitkerja where c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
		if ($n_eselon_iv){$nesl4=" ,$n_eselon_iv";}
		else{$nesl4="";}
		
	}
	if ($c_eselon_v){
		$n_eselon_iv = $db->fetchOne("select n_unitkerja from sdm.tr_unitkerja where c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
		if ($n_eselon_iv){$nesl5=" ,$n_eselon_v";}
		else{$nesl5="";}		
		
	}
	
	
	
	if ((string)$result[$j]->i_peg_nip_new){
		$i_peg_nip=(string)$result[$j]->i_peg_nip_new;
	}
	else{$i_peg_nip=(string)$result[$j]->i_peg_nip;}
	

	if ($n_eselon_i){$unitkerjalengkap="pada ".$nesl5.$nesl4.$nesl3.$nesl2.$nesl1;}
	
	$d_peg_lahir=(string)$result[$j]->d_peg_lahir;
	if ($d_peg_lahir){
		$usiatahun=$db->fetchOne("SELECT EXTRACT(years from AGE(NOW(), '$d_peg_lahir')) as age");
		$usiabulan=$db->fetchOne("SELECT EXTRACT(month from AGE(NOW(), '$d_peg_lahir')) as age");
	}
	else{
		$usiatahun="";
		$usiabulan="";
	}	
						
		$data[$j] = array("i_peg_nip"=>(string)$result[$j]->i_peg_nip,
			"i_peg_nip_new"=>(string)$result[$j]->i_peg_nip_new,		
			"i_peg_nrp"=>(string)$result[$j]->i_peg_nrp,
			"n_peg"=>(string)$result[$j]->n_peg,
			"c_peg_status"=>(string)$result[$j]->c_peg_status,
			"n_status_kepegawaian"=>$n_status_kepegawaian,			
			"n_peg_status"=>$n_peg_status,											
			"n_jabatan"=>$n_jabatan,
			"n_eselon"=>$n_eselon,
			"n_eselon_cpns"=>$n_eselon_cpns,
			"n_lokasi_unitkerja"=>$n_lokasi_unitkerja,
			"c_golongan"=>$c_golongan,
			"n_golongan"=>$n_golongan,
			"n_pangkat"=>$n_pangkat,
			"c_peg_jeniskelamin"=>(string)$result[$j]->c_peg_jeniskelamin,
			"e_file_photo"=>(string)$result[$j]->e_file_photo,
			"unitkerjalengkap"=>$unitkerjalengkap,
			"n_pangkat_cpns"=>$n_pangkat_cpns,
			"d_tmt_kgb"=>(string)$result[$j]->d_tmt_kgb,
			"d_tmt_golongan"=>(string)$result[$j]->d_tmt_golongan,
			"d_mulai_jabat"=>(string)$result[$j]->d_mulai_jabat,
			"d_tmt_cpns"=>(string)$result[$j]->d_tmt_cpns,
			"d_peg_lahir"=>(string)$result[$j]->d_peg_lahir,
			"usiatahun"=>$usiatahun,
			"usiabulan"=>$usiabulan
			
			
			
			
			);	
	}
			}							
			return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}

	
}
?>