<?php
class Sdm_Pegawai_Service {
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
								c_satker,c_parent,c_child
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


	$n_eselon = $db->fetchOne("select n_eselon  from sdm.tr_eselon where c_eselon='$c_eselon'");
	$n_jabatan = $db->fetchOne("select n_jabatan from sdm.tr_jabatan where c_jabatan='$c_jabatan'");
	$n_lokasi_unitkerja = $db->fetchOne("select n_lokasi  from sdm.tr_lokasi where c_lokasi='$c_lokasi_unitkerja'");
	$n_pangkat= $db->fetchOne("SELECT n_peg_pangkat FROM sdm.tr_golongan_pangkat WHERE c_peg_golongan ='$c_golongan'");
	$n_golongan= $db->fetchOne("SELECT n_peg_golongan FROM sdm.tr_golongan_pangkat WHERE c_peg_golongan ='$c_golongan'");
	$n_peg_status= $db->fetchOne("SELECT n_peg_status FROM sdm.tr_status_pegawai WHERE c_peg_status ='$c_peg_status'");
	$n_status_kepegawaian="";
	$c_status_kepegawaian=trim($result[$j]->c_status_kepegawaian);
	$n_status_kepegawaian= $db->fetchOne("SELECT n_status_kepegawaian FROM sdm.tr_status_kepegawaian WHERE c_status_kepegawaian ='$c_status_kepegawaian'");

	

						
if ($c_lokasi_unitkerja=='1'){	
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
}
else{
/* 	$c_lokasi_unitkerja=trim($result[$j]->c_lokasi_unitkerja);
	$c_eselon_i=trim($result[$j]->c_eselon_i);
	$c_eselon_ii=trim($result[$j]->c_eselon_ii);
	$c_eselon_iii=trim($result[$j]->c_eselon_iii);
	$c_eselon_iv=trim($result[$j]->c_eselon_iv);
	$c_eselon_v=trim($result[$j]->c_eselon_v); */
	
	$c_satker=trim($result[$j]->c_satker);
	$c_parent=trim($result[$j]->c_parent);
	$ceselon2 = $db->fetchOne(" SELECT distinct(c_eselon_ii) FROM sdm.tr_unitkerja WHERE c_satker='$c_satker' ");
	if (!$ceselon2){$ceselon2=$c_eselon_ii;}
							
	if ($c_eselon_i){
		//$n_eselon_i = $db->fetchOne("select n_unitkerja from sdm.tr_unitkerja where  c_eselon_i='$c_eselon_i' and c_tkt_esl='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
		$n_eselon_i = $db->fetchOne("SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_tkt_esl='1'");
		if ($n_eselon_i){$nesl1="$n_eselon_i, ";}
		else{$nesl1="";}
	}
	if ($c_eselon_ii){
		//$n_eselon_ii = $db->fetchOne("select n_unitkerja from sdm.tr_unitkerja where c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
		$n_eselon_ii = $db->fetchOne("SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii = '00'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child = '00' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
		if ($n_eselon_ii){$nesl2="$n_eselon_ii, ";}
		else{$nesl2="";}		
	}	
	if ($c_eselon_iii){
		//$n_eselon_iii = $db->fetchOne("select n_unitkerja from sdm.tr_unitkerja where c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
		$n_eselon_iii = $db->fetchOne("SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2' and c_eselon_iii='$c_eselon_iii' and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child <> '00' and c_parent ='$c_parent'  and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
		if ($n_eselon_iii){$nesl3="$n_eselon_iii, ";}
		else{$nesl3="";}
	}
	if ($c_eselon_iv){
		//$n_eselon_iv = $db->fetchOne("select n_unitkerja from sdm.tr_unitkerja where c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
		if ($c_satker=='00')
		{
			$n_eselon_iv = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2' and c_eselon_iv='$c_eselon_iv' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
		}
		else
		{
			$n_eselon_iv = $db->fetchOne("SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2' and c_eselon_iv='$c_eselon_iv' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_satker='$c_satker'");
		}		
		if ($n_eselon_iv){$nesl4="$n_eselon_iv, ";}
		else{$nesl4="";}
	}
	if ($c_eselon_v){
	
		$n_eselon_iv = $db->fetchOne("select n_unitkerja from sdm.tr_unitkerja where c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
		if ($n_eselon_iv){$nesl5=" ,$n_eselon_v";}
		else{$nesl5="";}		
		
	}
}	

	
	
	
	if ((string)$result[$j]->i_peg_nip_new){
		$i_peg_nip=(string)$result[$j]->i_peg_nip_new;
	}
	else{$i_peg_nip=(string)$result[$j]->i_peg_nip;}	


	

	if ($n_eselon_i){$unitkerjalengkap="pada ".$nesl5.$nesl4.$nesl3.$nesl2.$nesl1;}
						
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
			"d_tmt_kgb"=>(string)$result[$j]->d_tmt_kgb);	
	}
			}							
			return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}


	public function maintainData(array $data,$par) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();

	     $maintain_data = array("i_peg_nip"=>$data['i_peg_nip'],
								"i_peg_nrp"=>$data['i_peg_nrp'],
								"i_peg_nip_new"=>$data['i_peg_nip_new'],		 
								"n_peg"=>$data['n_peg'],
								"n_peg_gelardepan"=>$data['n_peg_gelardepan'],
								"n_peg_gelarblkg"=>$data['n_peg_gelarblkg'],
								"c_peg_jeniskelamin"=>$data['c_peg_jeniskelamin'],
								"c_agama"=>$data['c_agama'],
								"c_golongan_darah"=>$data['c_golongan_darah'],
								"c_peg_statusnikah"=>$data['c_peg_statusnikah'],
								"n_peg_hobi"=>$data['n_peg_hobi'],
								"d_peg_lahir"=>$data['d_peg_lahir'],
								"c_peg_propinsi_lahir"=>$data['c_peg_propinsi_lahir'],
								"a_peg_kota_lahir"=>$data['a_peg_kota_lahir'],
								"a_peg_kelurahan_lahir"=>$data['a_peg_kelurahan_lahir'],
								"a_peg_kecamatan_lahir"=>$data['a_peg_kecamatan_lahir'],
								"q_peg_tinggibdn"=>$data['q_peg_tinggibdn'],
								"q_peg_beratbdn"=>$data['q_peg_beratbdn'],
								"n_peg_rambut"=>$data['n_peg_rambut'],
								"n_peg_btkmuka"=>$data['n_peg_btkmuka'],
								"n_peg_warnakulit"=>$data['n_peg_warnakulit'],
								"n_peg_cirikhas"=>$data['n_peg_cirikhas'],
								"a_peg_rumah"=>$data['a_peg_rumah'],
								"a_peg_rt"=>$data['a_peg_rt'],
								"a_peg_rw"=>$data['a_peg_rw'],
								"a_peg_kelurahan"=>$data['a_peg_kelurahan'],
								"a_peg_kecamatan"=>$data['a_peg_kecamatan'],
								"a_peg_kota"=>$data['a_peg_kota'],
								"a_peg_propinsi"=>$data['a_peg_propinsi'],
								"a_peg_kodepos"=>$data['a_peg_kodepos'],
								"i_peg_telponrumah"=>$data['i_peg_telponrumah'],
								"i_peg_telponhp"=>$data['i_peg_telponhp'],
								"i_peg_karpeg"=>$data['i_peg_karpeg'],
								"i_peg_karis"=>$data['i_peg_karis'],
								"i_peg_taspen"=>$data['i_peg_taspen'],
								"i_peg_korpri"=>$data['i_peg_korpri'],
								"i_peg_ktp"=>$data['i_peg_ktp'],
								"i_peg_askes"=>$data['i_peg_askes'],
								"i_peg_npwp"=>$data['i_peg_npwp'],
								"i_peg_rekening"=>$data['i_peg_rekening'],
								"c_peg_bank"=>$data['c_peg_bank'],
								"c_stat_aktivasi"=>$data['c_stat_aktivasi'],
								"d_entry"=>$data['d_entry']);
		if ($par=='insert'){$db->insert('sdm.tm_pegawai',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tm_pegawai',$maintain_data, "i_peg_nip = '".trim($data['i_peg_nip'])."'   ");}	 
		
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function maintainHapusData($i_peg_nip,$i_entry) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
			$maintain_data = array("c_stat_aktivasi"=>"D");
		$db->update('sdm.tm_pegawai',$maintain_data, "i_peg_nip = '".trim($i_peg_nip)."'   ");
		$db->commit();
		return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	

	public function maintainDataCpns(array $data,$par) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();

	     $maintain_data = array("i_peg_nip"=>$data['i_peg_nip'],
								"i_peg_nrp"=>$data['i_peg_nrp'],
								"i_peg_nip_new"=>$data['i_peg_nip_new'],		 
								"n_peg"=>$data['n_peg'],
								"n_peg_gelardepan"=>$data['n_peg_gelardepan'],
								"n_peg_gelarblkg"=>$data['n_peg_gelarblkg'],
								"c_stat_aktivasi"=>$data['c_stat_aktivasi'],
								"c_peg_jeniskelamin"=>$data['c_peg_jeniskelamin'],
								"c_peg_status"=>$data['c_peg_status'],								
								"d_sk_cpns"=>$data['d_sk_cpns'],
								"d_peg_lahir"=>$data['d_peg_lahir'],
								"a_peg_kelurahan_lahir"=>$data['a_peg_kelurahan_lahir'],
								"a_peg_kecamatan_lahir"=>$data['a_peg_kecamatan_lahir'],
								"n_sk_pejabatcpns"=>$data['n_sk_pejabatcpns'],
								"i_sk_cpns"=>$data['i_sk_cpns'],
								"d_tmt_cpns"=>$data['d_tmt_cpns'],
								"d_tmt_kgb"=>$data['d_tmt_kgb'],
								"c_gol_cpns"=>$data['c_gol_cpns'],
								"c_eselon_cpns"=>$data['c_eselon_cpns'],
								"c_lokasi_unitkerja_cpns"=>$data['c_lokasi_unitkerja_cpns'],
								"c_eselon_i_cpns"=>$data['c_eselon_i_cpns'],
								"c_eselon_ii_cpns"=>$data['c_eselon_ii_cpns'],
								"c_eselon_iii_cpns"=>$data['c_eselon_iii_cpns'],
								"c_eselon_iv_cpns"=>$data['c_eselon_iv_cpns'],
								"c_eselon_v_cpns"=>$data['c_eselon_v_cpns'],
								"q_fiktif_cpns_thn"=>$data['q_fiktif_cpns_thn'],
								"q_fiktif_cpns_bln"=>$data['q_fiktif_cpns_bln'],
								"q_honorer_cpns_thn"=>$data['q_honorer_cpns_thn'],
								"q_honorer_cpns_bln"=>$data['q_honorer_cpns_bln'],
								"q_swasta_cpns_thn"=>$data['q_swasta_cpns_thn'],
								"q_swasta_cpns_bln"=>$data['q_swasta_cpns_bln'],
								"q_mktotal_cpns_thn"=>$data['q_mktotal_cpns_thn'],
								"q_mktotal_cpns_bln"=>$data['q_mktotal_cpns_bln'],
								"c_pend_cpns"=>$data['c_pend_cpns'],
								"c_jabatan_cpns"=>$data['c_jabatan_cpns'],
								"e_file_photo"=>$data['e_file_photo'],
								"d_tmt_kerja"=>$data['d_tmt_kerja'],
								"d_spmt_cpns"=>$data['d_spmt_cpns'],
								"i_spmt_cpns"=>$data['i_spmt_cpns'],
								"n_spmt_pejabatcpns"=>$data['n_spmt_pejabatcpns'],	
								"c_status_kepegawaian"=>$data['c_status_kepegawaian'],	
								"c_parent"=>$data['c_parent'],
								"c_satker"=>$data['c_satker'],	
								"d_entry"=>$data['d_entry']);
		if ($par=='insert'){$db->insert('sdm.tm_pegawai',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tm_pegawai',$maintain_data, "i_peg_nip = '".trim($data['i_peg_nip'])."'   ");}	 
		
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function maintainDataPns(array $data,$par) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();

	     $maintain_data = array(
								//"c_eselon"=>$data['c_eselon'],
								"c_lokasi_unitkerja"=>$data['c_lokasi_unitkerja'],
								"c_eselon_i"=>$data['c_eselon_i'],
								"c_eselon_ii"=>$data['c_eselon_ii'],
								"c_eselon_iii"=>$data['c_eselon_iii'],
								"c_eselon_iv"=>$data['c_eselon_iv'],
								"c_eselon_v"=>$data['c_eselon_v'],
								"c_peg_status"=>$data['c_peg_status'],	
								"n_unitkerja_nokode"=>$data['n_unitkerja_nokode'],
								"i_sk_pns"=>$data['i_sk_pns'],
								"d_sk_pns"=>$data['d_sk_pns'],
								"n_sk_pejabatpns"=>$data['n_sk_pejabatpns'],
								"i_kesehatan_pns"=>$data['i_kesehatan_pns'],
								"d_kesehatan_pns"=>$data['d_kesehatan_pns'],
								"n_rumahsakit_pns"=>$data['n_rumahsakit_pns'],
								"n_kesehatan_pejabatpns"=>$data['n_kesehatan_pejabatpns'],
								"i_sk_prajab"=>$data['i_sk_prajab'],
								"d_sk_prajab"=>$data['d_sk_prajab'],
								"n_sk_pejabatprajab"=>$data['n_sk_pejabatprajab'],
								"c_parent"=>$data['c_parent'],
								"c_satker"=>$data['c_satker'],
								"d_entry"=>$data['d_entry']);
		if ($par=='update'){$db->update('sdm.tm_pegawai',$maintain_data, "i_peg_nip = '".trim($data['i_peg_nip'])."'  ");}	 
		
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
 	public function getPegawaiListByNip($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{		
				
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT i_peg_nip,i_peg_nrp,i_peg_nip_new,n_peg,n_peg_gelardepan,n_peg_gelarblkg,c_peg_jeniskelamin,
										c_agama,c_golongan_darah,c_peg_statusnikah,n_peg_hobi,to_char(d_peg_lahir,'dd-mm-yyyy') as d_peg_lahir,c_peg_propinsi_lahir,
										a_peg_kota_lahir,a_peg_kelurahan_lahir,a_peg_kecamatan_lahir,q_peg_tinggibdn,q_peg_beratbdn,n_peg_rambut,n_peg_btkmuka,n_peg_warnakulit,
										n_peg_cirikhas,a_peg_rumah,a_peg_rt,a_peg_rw,a_peg_kelurahan,a_peg_kecamatan,a_peg_kota,
										a_peg_propinsi,a_peg_kodepos,i_peg_telponrumah,i_peg_telponhp,i_peg_karpeg,i_peg_karis,
										i_peg_taspen,i_peg_korpri,i_peg_ktp,i_peg_askes,e_file_photo,c_stat_aktivasi,
										to_char(d_sk_cpns,'dd-mm-yyyy') as d_sk_cpns,n_sk_pejabatcpns,i_sk_cpns,
										to_char(d_tmt_cpns,'dd-mm-yyyy') as d_tmt_cpns,c_gol_cpns,c_eselon_cpns,
										c_lokasi_unitkerja_cpns,c_eselon_i_cpns,c_eselon_ii_cpns,c_eselon_iii_cpns,
										c_eselon_iv_cpns,c_eselon_v_cpns,q_fiktif_cpns_thn,q_fiktif_cpns_bln,q_honorer_cpns_thn,
										q_honorer_cpns_bln,q_swasta_cpns_thn,q_swasta_cpns_bln,q_mktotal_cpns_thn,q_mktotal_cpns_bln,
										c_pend_cpns,c_jabatan_cpns,c_status_kepegawaian,to_char(d_tmt_kerja,'dd-mm-yyyy') as d_tmt_kerja,
										n_unitkerja_nokode,i_sk_pns,to_char(d_sk_pns,'dd-mm-yyyy') as d_sk_pns ,n_sk_pejabatpns,i_kesehatan_pns,
										to_char(d_kesehatan_pns,'dd-mm-yyyy') as d_kesehatan_pns ,n_rumahsakit_pns,n_kesehatan_pejabatpns,i_sk_prajab,
										to_char(d_sk_prajab,'dd-mm-yyyy') as d_sk_prajab ,n_sk_pejabatprajab,c_eselon,
										c_lokasi_unitkerja,c_eselon_i,c_eselon_ii,c_eselon_iii,c_eselon_iv,c_eselon_v,c_peg_status,i_entry,d_entry,
										to_char(d_spmt_cpns,'dd-mm-yyyy') as d_spmt_cpns ,i_spmt_cpns,n_spmt_pejabatcpns,i_peg_npwp,i_peg_rekening,c_peg_bank,
										c_parent,c_satker,c_child,c_parent
										FROM sdm.tm_pegawai where 1=1 $cari");
										
										
									
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 

						$c_golongan=trim($result[$j]->c_golongan);
						$n_pangkat= $db->fetchOne("SELECT n_peg_pangkat FROM sdm.tr_golongan_pangkat WHERE c_peg_golongan ='$c_golongan'");	
						$c_gol_cpns=trim($result[$j]->c_gol_cpns);
						$n_pangkat_cpns= $db->fetchOne("SELECT n_peg_pangkat FROM sdm.tr_golongan_pangkat WHERE c_peg_golongan ='$c_gol_cpns'");
						
						$c_eselon=trim($result[$j]->c_eselon);
						$n_eselon= $db->fetchOne("SELECT n_eselon FROM sdm.tr_eselon WHERE c_eselon ='$c_eselon'");						

						$c_eselon_cpns=trim($result[$j]->c_eselon_cpns);
						$n_eselon_cpns= $db->fetchOne("SELECT n_eselon FROM sdm.tr_eselon WHERE c_eselon ='$c_eselon_cpns'");
						
						$c_jabatan_cpns=trim($result[$j]->c_jabatan_cpns);
						$n_jabatan_cpns= $db->fetchOne("SELECT n_jabatan FROM sdm.tr_jabatan WHERE c_jabatan ='$c_jabatan_cpns'");
						
						$c_lokasi_unitkerja_cpns=trim($result[$j]->c_lokasi_unitkerja_cpns);
						$c_eselon_i_cpns=trim($result[$j]->c_eselon_i_cpns);
						$c_eselon_ii_cpns=trim($result[$j]->c_eselon_ii_cpns);
						$c_eselon_iii_cpns=trim($result[$j]->c_eselon_iii_cpns);
						$c_eselon_iv_cpns=trim($result[$j]->c_eselon_iv_cpns);
						$c_child=trim($result[$j]->c_child);
						$c_parent=trim($result[$j]->c_parent);
						$c_satker=trim($result[$j]->c_satker);
						
						if ($c_lokasi_unitkerja_cpns=='1'){
							$neseloncpns1 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i_cpns' and c_tkt_esl='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja_cpns'");
							$neseloncpns2 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i_cpns' and c_eselon_ii='$c_eselon_ii_cpns' and  c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja_cpns'");							
							$neseloncpns3 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i_cpns' and c_eselon_ii='$c_eselon_ii_cpns' and c_eselon_iii='$c_eselon_iii_cpns' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja_cpns'");
							$neseloncpns4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i_cpns' and c_eselon_ii='$c_eselon_ii_cpns' and c_eselon_iii='$c_eselon_iii_cpns' and c_eselon_iv='$c_eselon_iv_cpns' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja_cpns'");
							$neseloncpns5 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i_cpns' and c_eselon_ii='$c_eselon_ii_cpns' and c_eselon_iii='$c_eselon_iii_cpns' and c_eselon_iv='$c_eselon_iv_cpns' and c_eselon_v='$c_eselon_v_cpns' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja_cpns'");
						}
						else{
							
							$c_satker=trim($result[$j]->c_satker);
							$ceseloncpns2 = $db->fetchOne(" SELECT distinct(c_eselon_ii) FROM sdm.tr_unitkerja WHERE c_satker='$c_satker' ");
							if (!$ceselon2){$ceselon2=$c_eselon_ii;}
							$neseloncpns1 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i_cpns' and c_tkt_esl='1' ");
							$neseloncpns2 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i_cpns' and c_eselon_ii='$c_eselon_ii_cpns' and c_eselon_iii = '00'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child = '00' and c_lokasi_unitkerja='$c_lokasi_unitkerja_cpns'");							
							$neseloncpns3 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i_cpns' and c_eselon_ii='$ceseloncpns2' and c_eselon_iii='$c_eselon_iii_cpns' and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child <> '00' and c_parent ='$c_parent'  and c_lokasi_unitkerja='$c_lokasi_unitkerja_cpns'");
							if ($c_satker=='00'){
								$neseloncpns4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i_cpns' and c_eselon_ii='$ceseloncpns2' and c_eselon_iv='$c_eselon_iv_cpns' and c_lokasi_unitkerja='$c_lokasi_unitkerja_cpns'");
							}else{
								$neseloncpns4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i_cpns' and c_eselon_ii='$ceseloncpns2' and c_eselon_iv='$c_eselon_iv_cpns' and c_lokasi_unitkerja='$c_lokasi_unitkerja_cpns' and c_satker='$c_satker'");
							}
							
												
						}

						$c_lokasi_unitkerja=trim($result[$j]->c_lokasi_unitkerja);
						$c_eselon_i=trim($result[$j]->c_eselon_i);
						$c_eselon_ii=trim($result[$j]->c_eselon_ii);
						$c_eselon_iii=trim($result[$j]->c_eselon_iii);
						$c_eselon_iv=trim($result[$j]->c_eselon_iv);
						$c_eselon_v=trim($result[$j]->c_eselon_v);
						
						if ($c_lokasi_unitkerja=='1'){
							$neselon1 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_tkt_esl='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
							$neselon2 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");							
							$neselon3 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
							$neselon4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
							$neselon5 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_eselon_v='$c_eselon_v' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
						}
						else{
							
							
							$c_satker=trim($result[$j]->c_satker);
							$ceselon2 = $db->fetchOne(" SELECT distinct(c_eselon_ii) FROM sdm.tr_unitkerja WHERE c_satker='$c_satker' ");
							if (!$ceselon2){$ceselon2=$c_eselon_ii;}
							$neselon1 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_tkt_esl='1' ");
							$neselon2 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii = '00'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child = '00' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");							
							$neselon3 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2' and c_eselon_iii='$c_eselon_iii' and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child <> '00' and c_parent ='$c_parent'  and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
							if ($c_satker=='00'){
								$neselon4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2' and c_eselon_iv='$c_eselon_iv' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
							}else{
								$neselon4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2' and c_eselon_iv='$c_eselon_iv' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_satker='$c_satker'");
							}
						
						}

						$data[$j] = array("i_peg_nip"=>(string)$result[$j]->i_peg_nip,
									"i_peg_nrp"=>(string)$result[$j]->i_peg_nrp,						
									"i_peg_nip_new"=>(string)$result[$j]->i_peg_nip_new,					
									"n_peg"=>(string)$result[$j]->n_peg,
									"n_peg_gelardepan"=>(string)$result[$j]->n_peg_gelardepan,
									"n_peg_gelarblkg"=>(string)$result[$j]->n_peg_gelarblkg,
									"c_peg_jeniskelamin"=>(string)$result[$j]->c_peg_jeniskelamin,
									"c_agama"=>(string)$result[$j]->c_agama,
									"c_golongan_darah"=>(string)$result[$j]->c_golongan_darah,
									"c_peg_statusnikah"=>(string)$result[$j]->c_peg_statusnikah,
									"n_peg_hobi"=>(string)$result[$j]->n_peg_hobi,
									"n_peg_hobi"=>(string)$result[$j]->n_peg_hobi,
									"n_pangkat"=>$n_pangkat,
									"d_peg_lahir"=>(string)$result[$j]->d_peg_lahir,
									"c_peg_propinsi_lahir"=>(string)$result[$j]->c_peg_propinsi_lahir,
									"a_peg_kota_lahir"=>(string)$result[$j]->a_peg_kota_lahir,
									"a_peg_kelurahan_lahir"=>(string)$result[$j]->a_peg_kelurahan_lahir,
									"a_peg_kecamatan_lahir"=>(string)$result[$j]->a_peg_kecamatan_lahir,
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
									"a_peg_kota"=>(string)$result[$j]->a_peg_kota,
									"a_peg_propinsi"=>(string)$result[$j]->a_peg_propinsi,
									"a_peg_kodepos"=>(string)$result[$j]->a_peg_kodepos,
									"i_peg_telponrumah"=>(string)$result[$j]->i_peg_telponrumah,
									"i_peg_telponhp"=>(string)$result[$j]->i_peg_telponhp,
									"i_peg_karpeg"=>(string)$result[$j]->i_peg_karpeg,
									"i_peg_karis"=>(string)$result[$j]->i_peg_karis,
									"i_peg_taspen"=>(string)$result[$j]->i_peg_taspen,
									"i_peg_korpri"=>(string)$result[$j]->i_peg_korpri,
									"i_peg_ktp"=>(string)$result[$j]->i_peg_ktp,
									"i_peg_askes"=>(string)$result[$j]->i_peg_askes,
									"e_file_photo"=>(string)$result[$j]->e_file_photo,
									"c_stat_aktivasi"=>(string)$result[$j]->c_stat_aktivasi,
									"c_eselon"=>(string)$result[$j]->c_eselon,
									"n_eselon"=>$n_eselon,
									"d_sk_cpns"=>(string)$result[$j]->d_sk_cpns,
									"n_sk_pejabatcpns"=>(string)$result[$j]->n_sk_pejabatcpns,
									"i_sk_cpns"=>(string)$result[$j]->i_sk_cpns,
									"d_tmt_cpns"=>(string)$result[$j]->d_tmt_cpns,
									"c_gol_cpns"=>(string)$result[$j]->c_gol_cpns,
									"n_pangkat_cpns"=>(string)$result[$j]->n_pangkat_cpns,									
									"c_eselon_cpns"=>(string)$result[$j]->c_eselon_cpns,
									"n_eselon_cpns"=>$n_eselon_cpns,
									"c_lokasi_unitkerja_cpns"=>(string)$result[$j]->c_lokasi_unitkerja_cpns,
									"c_eselon_i_cpns"=>(string)$result[$j]->c_eselon_i_cpns,
									"c_eselon_ii_cpns"=>(string)$result[$j]->c_eselon_ii_cpns,
									"c_eselon_iii_cpns"=>(string)$result[$j]->c_eselon_iii_cpns,
									"c_eselon_iv_cpns"=>(string)$result[$j]->c_eselon_iv_cpns,
									"c_eselon_v_cpns"=>(string)$result[$j]->c_eselon_v_cpns,
									"q_fiktif_cpns_thn"=>(string)$result[$j]->q_fiktif_cpns_thn,
									"q_fiktif_cpns_bln"=>(string)$result[$j]->q_fiktif_cpns_bln,
									"q_honorer_cpns_thn"=>(string)$result[$j]->q_honorer_cpns_thn,
									"q_honorer_cpns_bln"=>(string)$result[$j]->q_honorer_cpns_bln,
									"q_swasta_cpns_thn"=>(string)$result[$j]->q_swasta_cpns_thn,
									"q_swasta_cpns_bln"=>(string)$result[$j]->q_swasta_cpns_bln,
									"q_mktotal_cpns_thn"=>(string)$result[$j]->q_mktotal_cpns_thn,
									"q_mktotal_cpns_bln"=>(string)$result[$j]->q_mktotal_cpns_bln,
									"c_pend_cpns"=>(string)$result[$j]->c_pend_cpns,
									"c_jabatan_cpns"=>(string)$result[$j]->c_jabatan_cpns,
									"d_tmt_kerja"=>(string)$result[$j]->d_tmt_kerja,
									"c_status_kepegawaian"=>(string)$result[$j]->c_status_kepegawaian,
									"n_jabatan_cpns"=>$n_jabatan_cpns,
									"n_unitkerja_nokode"=>(string)$result[$j]->n_unitkerja_nokode,
									"i_sk_pns"=>(string)$result[$j]->i_sk_pns,
									"d_sk_pns"=>(string)$result[$j]->d_sk_pns,
									"n_sk_pejabatpns"=>(string)$result[$j]->n_sk_pejabatpns,
									"i_kesehatan_pns"=>(string)$result[$j]->i_kesehatan_pns,
									"d_kesehatan_pns"=>(string)$result[$j]->d_kesehatan_pns,
									"n_rumahsakit_pns"=>(string)$result[$j]->n_rumahsakit_pns,
									"n_kesehatan_pejabatpns"=>(string)$result[$j]->n_kesehatan_pejabatpns,
									"i_sk_prajab"=>(string)$result[$j]->i_sk_prajab,
									"d_sk_prajab"=>(string)$result[$j]->d_sk_prajab,
									"n_sk_pejabatprajab"=>(string)$result[$j]->n_sk_pejabatprajab,	
									"c_lokasi_unitkerja"=>(string)$result[$j]->c_lokasi_unitkerja,
									"c_eselon_i"=>trim($result[$j]->c_eselon_i),
									"c_eselon_ii"=>trim($result[$j]->c_eselon_ii),
									"c_eselon_iii"=>trim($result[$j]->c_eselon_iii),
									"c_eselon_iv"=>trim($result[$j]->c_eselon_iv),
									"c_eselon_v"=>trim($result[$j]->c_eselon_v),
									"c_peg_status"=>trim($result[$j]->c_peg_status),									
									"neseloncpns1"=>$neseloncpns1,
									"neseloncpns2"=>$neseloncpns2,
									"neseloncpns3"=>$neseloncpns3,
									"neseloncpns4"=>$neseloncpns4,
									"neseloncpns5"=>$neseloncpns5, 
									"ceseloncpns2"=>$ceseloncpns2,
									"neselon1"=>$neselon1,
									"neselon2"=>$neselon2,
									"neselon3"=>$neselon3,
									"neselon4"=>$neselon4,
									"neselon5"=>$neselon5, 	
									"ceselon2"=>$ceselon2,
									"d_spmt_cpns"=>trim($result[$j]->d_spmt_cpns),
									"i_spmt_cpns"=>trim($result[$j]->i_spmt_cpns),
									"n_spmt_pejabatcpns"=>trim($result[$j]->n_spmt_pejabatcpns),
									"i_peg_npwp"=>trim($result[$j]->i_peg_npwp),
									"i_peg_rekening"=>trim($result[$j]->i_peg_rekening),
									"c_peg_bank"=>trim($result[$j]->c_peg_bank),
									"c_parent"=>trim($result[$j]->c_parent),
									"c_satker"=>trim($result[$j]->c_satker),									
									"i_entry"=>(string)$result[$j]->i_entry,
									"d_entry"=>(string)$result[$j]->d_entry);
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