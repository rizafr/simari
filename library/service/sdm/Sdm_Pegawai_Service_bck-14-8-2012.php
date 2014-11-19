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
					$sql="SELECT i_peg_nip,i_peg_nrp,i_peg_nip_new, n_peg,c_peg_status,c_status_kepegawaian,c_eselon,to_char(d_tmt_eselon,'yyyy-mm-dd') as d_tmt_eselon,
								c_jabatan,to_char(d_mulai_jabat,'dd-mm-yyyy') as d_mulai_jabat,c_peg_jeniskelamin,
								to_char(d_akhir_jabat,'dd-mm-yyyy') as d_akhir_jabat,c_golongan,d_tmt_golongan,v_gaji_pokok,
								c_lokasi_unitkerja,c_eselon_i,c_eselon_ii,c_eselon_iii,c_eselon_iv,c_eselon_v,c_gol_cpns,
								c_lokasi_unitkerja_cpns,c_eselon_cpns,e_file_photo,c_eselon_cpns,c_lokasi_unitkerja_cpns,c_eselon_i_cpns,c_satker_cpns,c_parent_cpns
								c_eselon_ii_cpns,c_eselon_iii_cpns,c_eselon_iv_cpns,c_eselon_v_cpns,c_jabatan_cpns,to_char(d_tmt_kgb,'dd-mm-yyyy') as d_tmt_kgb,
								c_satker,c_parent,c_child,c_lokasi_unitkerja_pns,c_eselon_i_pns,c_eselon_ii_pns,c_eselon_iii_pns,c_eselon_iv_pns,c_satker_pns,c_parent_pns,
								to_char(d_peg_lahir,'yyyy-mm-dd') as d_peg_lahir
								FROM sdm.tm_pegawai where 1=1 $cari  
								ORDER BY c_golongan ASC,d_tmt_golongan ASC,c_eselon ASC,q_tktfgs,d_tmt_eselon ASC,d_tmt_cpns asc, q_tahun ASC,c_pend ASC,d_pend_akhir ASC,d_peg_lahir ASC
								limit $xLimit offset $xOffset";
					$result = $db->fetchAll($sql);
					//echo $sql;
									
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
	$c_jabatan="";
	if (trim($result[$j]->c_peg_status)!='2CP')
	{
		$c_eselon_i=trim($result[$j]->c_eselon_i);
		if (!$c_eselon_i){$c_eselon_i=trim($result[$j]->c_eselon_i_pns);}
		$c_eselon_ii=trim($result[$j]->c_eselon_ii);
		if (!$c_eselon_ii){$c_eselon_ii=trim($result[$j]->c_eselon_ii_pns);}
		$c_eselon_iii=trim($result[$j]->c_eselon_iii);
		if (!$c_eselon_iii){$c_eselon_iii=trim($result[$j]->c_eselon_iii_pns);}
		$c_eselon_iv=trim($result[$j]->c_eselon_iv);
		if (!$c_eselon_iv){$c_eselon_iv=trim($result[$j]->c_eselon_iv_pns);}
		$c_eselon_v=trim($result[$j]->c_eselon_v);
		if (!$c_eselon_v){$c_eselon_v=trim($result[$j]->c_eselon_v_pns);}
		$c_eselon=trim($result[$j]->c_eselon);
		$c_jabatan=trim($result[$j]->c_jabatan);
		$c_lokasi_unitkerja=trim($result[$j]->c_lokasi_unitkerja);
		if (!$c_lokasi_unitkerja){$c_lokasi_unitkerja=trim($result[$j]->c_lokasi_unitkerja_pns);}
		$c_golongan=trim($result[$j]->c_golongan);
		$c_peg_status=trim($result[$j]->c_peg_status);
		$c_parent=trim($result[$j]->c_parent);
		$c_satker=trim($result[$j]->c_satker);
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
		$c_parent=trim($result[$j]->c_parent_cpns);
		$c_satker=trim($result[$j]->c_satker_cpns);
		
	}

	$n_jabatan="";

	$n_eselon = $db->fetchOne("select n_eselon  from sdm.tr_eselon where c_eselon='$c_eselon'");
	$n_jabatan = $db->fetchOne("select n_jabatan from sdm.tr_jabatan where c_jabatan='$c_jabatan'");
	$n_lokasi_unitkerja = $db->fetchOne("select n_lokasi  from sdm.tr_lokasi where c_lokasi='$c_lokasi_unitkerja'");
	$n_pangkat= $db->fetchOne("SELECT n_peg_pangkat FROM sdm.tr_golongan_pangkat WHERE c_peg_golongan ='$c_golongan'");
	$n_golongan= $db->fetchOne("SELECT n_peg_golongan FROM sdm.tr_golongan_pangkat WHERE c_peg_golongan ='$c_golongan'");
	$n_peg_status= $db->fetchOne("SELECT n_peg_status FROM sdm.tr_status_pegawai WHERE c_peg_status ='$c_peg_status'");
	$n_status_kepegawaian="";
	$c_status_kepegawaian=trim($result[$j]->c_status_kepegawaian);
	$n_status_kepegawaian= $db->fetchOne("SELECT n_status_kepegawaian FROM sdm.tr_status_kepegawaian WHERE c_status_kepegawaian ='$c_status_kepegawaian'");

	

						
if ($c_lokasi_unitkerja=='1')
{	
	$neselon5x = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_eselon_v='$c_eselon_v' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	$neselon5=trim($neselon5x[0]->n_unitkerja);
	$n_eselon_v=trim($neselon5x[0]->n_unitkerja);
	$c_esl_v=trim($neselon5x[0]->c_esl);		
	if ($c_eselon_v && $c_eselon!='15'){$n_eselon_iv=$this->cekNamaUk($n_eselon_v,$c_esl_v);  if ($n_eselon_v){$nesl5="$c_esl_v $n_eselon_v pada";} }
	else{ if ($n_eselon_v){ if ($c_eselon=='15'){$nesl5=" pada $n_eselon_v,";  $par='y';} else {$nesl5=" $n_eselon_v,";}} else {$nesl5="";}}
	
	
	$neselon4x = $db->fetchAll(" SELECT n_unitkerja,c_esl FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	$neselon4=trim($neselon4x[0]->n_unitkerja);
	$n_eselon_iv=trim($neselon4x[0]->n_unitkerja);
	$c_esl_iv=trim($neselon4x[0]->c_esl);		
	if ($c_eselon_iv && (!$c_eselon_v || $c_eselon_v=='00') && $c_eselon!='15'){$n_eselon_iv=$this->cekNamaUk($n_eselon_iv,$c_esl_iv);  if ($n_eselon_iv){$nesl4="$n_eselon_iv pada";} }
	else{ if ($n_eselon_iv){ if ($c_eselon=='15'){$nesl4=" pada $n_eselon_iv,";  $par='y';} else {$nesl4=" $n_eselon_iv,";}} else {$nesl4="";}}
	
	
	$neselon3x = $db->fetchAll(" SELECT n_unitkerja,c_esl FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	$neselon3=trim($neselon3x[0]->n_unitkerja);
	$n_eselon_iii=trim($neselon3x[0]->n_unitkerja);
	$c_esl_iii=trim($neselon3x[0]->c_esl);		
	if ($c_eselon_iii && (!$c_eselon_iv || $c_eselon_iv=='00') && (!$c_eselon_v || $c_eselon_v=='00') && $c_eselon!='15'){$n_eselon_iii=$this->cekNamaUk($n_eselon_iii,$c_esl_iii);  if ($n_eselon_iii){$nesl3="$n_eselon_iii pada";} }
	else{ if ($n_eselon_iii){ if ($c_eselon=='15' && $par=='n'){$nesl3=" pada $n_eselon_iii,";  $par='y';} else {$nesl3=" $n_eselon_iii,";}} else {$nesl3="";}}
	
	$neselon2x = $db->fetchAll(" SELECT n_unitkerja,c_esl FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
	$neselon2=trim($neselon2x[0]->n_unitkerja);
	$n_eselon_ii=trim($neselon2x[0]->n_unitkerja);
	$c_esl_ii=trim($neselon2x[0]->c_esl);		
	if ($c_eselon_ii && (!$c_eselon_iii || $c_eselon_iii=='00') && (!$c_eselon_iv || $c_eselon_iv=='00') && (!$c_eselon_v || $c_eselon_v=='00') && $c_eselon!='15'){$n_eselon_ii=$this->cekNamaUk($n_eselon_ii,$c_esl_ii);  if ($n_eselon_ii){$nesl2="$n_eselon_ii pada";} }
	else{ if ($n_eselon_ii){ if ($c_eselon=='15'&& $par=='n'){$nesl2=" pada $n_eselon_ii,";  $par='y';} else {$nesl2=" $n_eselon_ii,";}} else {$nesl2="";}}
	
	$neselon1x = $db->fetchAll(" SELECT n_unitkerja,c_esl FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_tkt_esl='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	$neselon1=trim($neselon1x[0]->n_unitkerja);
	$n_eselon_i=trim($neselon1x[0]->n_unitkerja);
	$c_esl_i=trim($neselon1x[0]->c_esl);		
	if ($c_eselon_i && (!$c_eselon_ii || $c_eselon_ii=='00')  && (!$c_eselon_iii || $c_eselon_iii=='00') && (!$c_eselon_iv || $c_eselon_iv=='00') && (!$c_eselon_v || $c_eselon_v=='00') && $c_eselon!='15'){$n_eselon_i=$this->cekNamaUk($n_eselon_i,$c_esl_i);  if ($n_eselon_i){$nesl1="$n_eselon_i";} }
	else{$nesl1=" $n_eselon_i";}


}
else
{
	

	$ceselon2 = $db->fetchOne(" SELECT distinct(c_eselon_ii) FROM sdm.tr_unitkerja WHERE c_satker='$c_satker' ");

	if (!$ceselon2){$ceselon2=$c_eselon_ii;}
	if ($c_satker=='00'){
		$neselon4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2' and c_eselon_iv='$c_eselon_iv' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	}else{
		$neselon4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2' and c_eselon_iv='$c_eselon_iv' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_satker='$c_satker'");
	}
	$neselon3 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2' and c_eselon_iii='$c_eselon_iii' and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child <> '00' and c_parent ='$c_parent'  and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	$neselon2 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii = '00'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child = '00' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
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
		//echo "xxxxx ".$nama;
		$nama= strtolower($neselon3);	
		$nesl2="";
		$nesl1="";
		$nesl3=ucwords($nama);
		$nesl4="";
	}

				}	
	
	////////////////// add noer ////////////////
	$e_eselon = '';$e_eselon_i ='';$e_eselon_ii ='';$e_eselon_iii ='';$e_eselon_iv ='';$e_eselon_v ='';$e_eselon='';
	if ($c_eselon_v  && $c_eselon_v != '00') {
		$e_eselon_v = trim($db->fetchOne("SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_eselon_v='$c_eselon_v' "));
		$e_eselon = $e_eselon_v;
	}
	if ($c_eselon_iv  && $c_eselon_iv != '00') {
		$e_eselon_iv = trim($db->fetchOne("SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='4'"));
		$e_eselon = $e_eselon_iv;
	}
	///// untuk pusat pas, kl prngadilan blm
	if ($c_eselon_iii  && $c_eselon_iii != '00') {
		$e_eselon_iii = trim($db->fetchOne("SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_tkt_esl='3'"));
		$e_eselon = $e_eselon . ($e_eselon_iv ? ", " :"") . $e_eselon_iii;
	}
	if ($c_eselon_ii != '' && $c_eselon_ii != '000') {
		$e_eselon_ii = trim($db->fetchOne("SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='2'"));
		$e_eselon = $e_eselon . ($e_eselon_iii ? "," :"") . $e_eselon_ii;
	}
	///////////////
	if ($c_eselon_i != '' && $c_eselon_i != '00') {
		$e_eselon_i = trim($db->fetchOne("SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_eselon_i='$c_eselon_i' and c_tkt_esl='1'"));
		$e_eselon = $e_eselon . ($e_eselon_ii ? "," :"") . $e_eselon_i;
	}
	$e_jabatan='';$c_split =1;
	if ($c_jabatan != '') $c_split = $db->fetchOne("select c_split from sdm.tr_jabatan where c_jabatan='$c_jabatan'");
	
	if ($c_eselon == '13'){ //fungsional
		$e_jabatan = $n_jabatan . ($e_eselon != '' ? " pada $e_eselon":"");
	} else if ($c_eselon =='01' || $c_eselon =='02'){ // eselon I
		$r_unit1 = '';
		if ($e_eselon_i) $r_unitr = split(' ',$e_eselon_i,$c_split);
		$e_jabatan = $n_jabatan . " ". ($c_split > 1 ? $r_unitr[$c_split-1] :'') . '';
	} else if ($c_eselon =='03' || $c_eselon =='04'){ // eselon II
		$r_unit2 = '';
		//if ($e_eselon_ii) list($awalan2,$r_unit2) = split(' ',$e_eselon_ii,2);
		//$e_jabatan = $n_jabatan . ' '. $r_unit2.  ($e_eselon_i ? " pada $e_eselon_i":"");
		if ($e_eselon_ii) $r_unitr = split(' ',$e_eselon_ii,$c_split);
		$e_jabatan = $n_jabatan . " ". ($c_split > 1 ? $r_unitr[$c_split-1] :'') . ($e_eselon_i ? " pada $e_eselon_i":"");
	} else if ($c_eselon =='05' || $c_eselon =='06'){ // eselon III
		$r_unit3 = '';
		if ($e_eselon_iii) list($awalan3,$r_unit3) = split(' ',$e_eselon_iii,2);
		$e_jabatan = $n_jabatan . ' '. $r_unit3. ($e_eselon_ii ? " pada $e_eselon_ii":"") . ($e_eselon_i ? ", $e_eselon_i":"");
	}  else if ($c_eselon =='07' || $c_eselon =='08'){ // eselon IV
		$r_unit4 = '';
		if ($e_eselon_iii) list($awalan41,$awalan42,$r_unit4) = split(' ',$e_eselon_iv,3);
		$e_jabatan = $n_jabatan . ' '. $r_unit4. ($e_eselon_iii ? " pada $e_eselon_iii":"") . ($e_eselon_ii ? ", $e_eselon_ii":"") . ($e_eselon_i ? ", $e_eselon_i":"");
	} else if ($c_eselon =='15'){ // staff
		//$r_units = '';
		//if ($e_eselon_iii) list($awalan41,$awalan42,$r_unit4) = split(' ',$e_eselon_iv,3);
		if ($c_lokasi_unitkerja == '1') $e_jabatan = $n_jabatan . ' pada '. ($e_eselon_iv ? " $e_eselon_iv,":"") . ($e_eselon_iii ? " $e_eselon_iii,":"") . ($e_eselon_ii ? " $e_eselon_ii,":"") . ($e_eselon_i ? " $e_eselon_i":"");
		else $e_jabatan = $n_jabatan . ' pada '. ($e_eselon_v ? " $e_eselon_v,":"") . ($e_eselon_iii ? " $e_eselon_iii,":"") . ($e_eselon_ii ? " $e_eselon_ii,":"") . ($e_eselon_i ? " $e_eselon_i":"");
		
	} else if ($c_eselon =='14'){ // TTP
		$e_jabatan = $n_jabatan . ' pada '. ($e_eselon_iv ? " $e_eselon_iv,":"") . ($e_eselon_iii ? " $e_eselon_iii,":"") . ($e_eselon_ii ? " $e_eselon_ii,":"") . ($e_eselon_i ? " $e_eselon_i":"");
		
	} else if ($c_eselon =='12'){ // pejabat
		if ($c_jabatan == '048') list($n_jabatan,$n_jabatan2) = split(' ',$n_jabatan,2);
		else if ($c_jabatan == '050') { list($n_jabatan1,$n_jabatan2,$n_jabatan3) = split(' ',$n_jabatan,3);$n_jabatan = $n_jabatan1. ' '.$n_jabatan2;}
		
		$e_eselon =  ($e_eselon_iii ? " $e_eselon_iii":"") . ($e_eselon_iii && $e_eselon_ii ? ",":"") .($e_eselon_ii ? " $e_eselon_ii":"") . ($e_eselon_ii && $e_eselon_i ? ",":"") .($e_eselon_i ? " $e_eselon_i":"");
		if ($e_eselon) $r_unitr = split(' ',$e_eselon,$c_split);
		$e_jabatan = $n_jabatan . ' '.$e_eselon; //($c_split > 1 ? $r_unitr[$c_split-1] :'').'' ;
		
	}
	
	/////////////////////////////////////////////////////////////////////////////////////
	
	if ((string)$result[$j]->i_peg_nip_new){$i_peg_nip=(string)$result[$j]->i_peg_nip_new;}
	else{$i_peg_nip=(string)$result[$j]->i_peg_nip;}	

	//if ($n_eselon_i){$unitkerjalengkap="pada ".$nesl5.$nesl4.$nesl3.$nesl2.$nesl1;}
	$unitkerjalengkap=$nesl5.$nesl4.$nesl3.$nesl2.$nesl1;
	
			list($ylahir,$mlahir,$dlahir) = split ('-',$result[$j]->d_peg_lahir);
			$d_peg_lahir = "$dlahir-$mlahir-$ylahir";	
		$data[$j] = array("i_peg_nip"=>(string)$result[$j]->i_peg_nip,
			"i_peg_nip_new"=>(string)$result[$j]->i_peg_nip_new,		
			"i_peg_nrp"=>(string)$result[$j]->i_peg_nrp,
			"n_peg"=>(string)$result[$j]->n_peg,
			"c_peg_status"=>(string)$result[$j]->c_peg_status,
			"n_status_kepegawaian"=>$n_status_kepegawaian,			
			"n_peg_status"=>$n_peg_status,											
			"n_jabatan"=>$n_jabatan,
			"e_jabatan"=>$e_jabatan,
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
			"d_peg_lahir"=>$d_peg_lahir,			
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
								"i_peg_nipfg"=>$data['i_peg_nipfg'],								
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
		if ($par=='update'){$db->update('sdm.tm_pegawai',$maintain_data, "i_peg_nip = '".trim($data['i_peg_nipb'])."'");}	 
		
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
if($par=='update'){
	     $maintain_data = array("i_peg_nip"=>$data['i_peg_nip'],
								"i_peg_nrp"=>$data['i_peg_nrp'],
								"i_peg_nip_new"=>$data['i_peg_nip_new'],		 
								"n_peg"=>$data['n_peg'],
								"n_peg_gelardepan"=>$data['n_peg_gelardepan'],
								"n_peg_gelarblkg"=>$data['n_peg_gelarblkg'],
								"c_peg_jeniskelamin"=>$data['c_peg_jeniskelamin'],					
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
								"c_parent_cpns"=>$data['c_parent_cpns'],
								"c_satker_cpns"=>$data['c_satker_cpns'],	
								"d_entry"=>$data['d_entry']);
}
else{
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
}								
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
								"c_lokasi_unitkerja_pns"=>$data['c_lokasi_unitkerja'],
								"c_eselon_i_pns"=>$data['c_eselon_i'],
								"c_eselon_ii_pns"=>$data['c_eselon_ii'],
								"c_eselon_iii_pns"=>$data['c_eselon_iii'],
								"c_eselon_iv_pns"=>$data['c_eselon_iv'],
								"c_eselon_v_pns"=>$data['c_eselon_v'],
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
								"c_parent_pns"=>$data['c_parent_pns'],
								"c_satker_pns"=>$data['c_satker_pns'],
								"d_entry"=>$data['d_entry']);
		if ($par=='update'){$db->update('sdm.tm_pegawai',$maintain_data, "i_peg_nip = '".trim($data['i_peg_nip'])."'   ");}	 
		
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
										c_parent,c_satker,c_child,c_parent,i_peg_nipfg,c_golongan,d_tmt_golongan,to_char(d_tmt_cpns,'yyyy-mm-dd') as d_tmt_cpnsb,
										c_lokasi_unitkerja_pns,c_eselon_i_pns,c_eselon_ii_pns,c_eselon_iii_pns,c_eselon_iv_pns,c_parent_cpns,c_satker_cpns
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
						$c_child=trim($result[$j]->c_child);
						$c_parent=trim($result[$j]->c_parent);
						$c_satker=trim($result[$j]->c_satker);
						$c_parent_cpns=trim($result[$j]->c_parent_cpns);
						$c_satker_cpns=trim($result[$j]->c_satker_cpns);
						
						if ($c_lokasi_unitkerja_cpns=='1'){
							$neseloncpns1 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i_cpns' and c_tkt_esl='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja_cpns'");
							$neseloncpns2 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i_cpns' and c_eselon_ii='$c_eselon_ii_cpns' and  c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja_cpns'");							
							$neseloncpns3 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i_cpns' and c_eselon_ii='$c_eselon_ii_cpns' and c_eselon_iii='$c_eselon_iii_cpns' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja_cpns'");
							$neseloncpns4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i_cpns' and c_eselon_ii='$c_eselon_ii_cpns' and c_eselon_iii='$c_eselon_iii_cpns' and c_eselon_iv='$c_eselon_iv_cpns' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja_cpns'");
							$neseloncpns5 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i_cpns' and c_eselon_ii='$c_eselon_ii_cpns' and c_eselon_iii='$c_eselon_iii_cpns' and c_eselon_iv='$c_eselon_iv_cpns' and c_eselon_v='$c_eselon_v_cpns' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja_cpns'");
						}
						else{
							
							$c_satker_cpns=trim($result[$j]->c_satker_cpns);
							
							$ceseloncpns2 = $db->fetchOne(" SELECT distinct(c_eselon_ii) FROM sdm.tr_unitkerja WHERE c_satker='$c_satker_cpns' ");
							
							if (!$ceseloncpns2){$ceseloncpns2=$c_eselon_ii_cpns;}
							$neseloncpns1 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i_cpns' and c_tkt_esl='1' ");
							$neseloncpns3 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i_cpns' and c_eselon_ii='$ceseloncpns2' and c_eselon_iii='$c_eselon_iii_cpns' and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child <> '00' and c_parent ='$c_parent_cpns'  and c_lokasi_unitkerja='$c_lokasi_unitkerja_cpns'");
							if ($neseloncpns3){
								$ceseloniicpnsx = $db->fetchOne(" SELECT c_eselon_ii FROM sdm.tr_unitkerja WHERE c_level ='2' and c_eselon_i='$c_eselon_i_cpns' and c_parent='$c_parent_cpns' and c_lokasi_unitkerja='$c_lokasi_unitkerja_cpns'");
								
								$neseloncpns2 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i_cpns' and c_eselon_ii='$ceseloniicpnsx' and c_eselon_iii = '00'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child = '00' and c_lokasi_unitkerja='$c_lokasi_unitkerja_cpns'");
						
							}
							else{
								$neseloncpns2 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i_cpns' and c_eselon_ii='$c_eselon_ii_cpns' and c_eselon_iii = '00'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child = '00' and c_lokasi_unitkerja='$c_lokasi_unitkerja_cpns'");														
							}
							
							if ($c_parent_cpns=='00'){
								$neseloncpns4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_level ='5' and c_eselon_i='$c_eselon_i_cpns' and c_eselon_ii='$ceseloncpns2' and c_eselon_iv='$c_eselon_iv_cpns' and c_lokasi_unitkerja='$c_lokasi_unitkerja_cpns'");
							}else{
								$neseloncpns4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_level ='5' and c_eselon_i='$c_eselon_i_cpns' and c_eselon_ii='$ceseloncpns2' and c_eselon_iv='$c_eselon_iv_cpns' and c_lokasi_unitkerja='$c_lokasi_unitkerja_cpns' and c_satker='$c_satker_cpns'");
							}
							
							
/* 							if ($c_parent_cpns=='00'){
								$neselon4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2' and c_eselon_iv='$c_eselon_iv' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
							}else{
								$neselon4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2' and c_eselon_iv='$c_eselon_iv' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_satker='$c_satker'");
							} */							
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
						
						//tambahan untuk ambil fingger//
						if ((string)$result[$j]->i_peg_nip_new){$i_peg_nip=(string)$result[$j]->i_peg_nip_new;}
						$i_peg_nipfg = $db->fetchOne(" SELECT i_peg_nipfg FROM sdm.tr_absensi_finger WHERE i_peg_nip='$i_peg_nip' ");
						
						$data[$j] = array("i_peg_nip"=>(string)$result[$j]->i_peg_nip,
						//"i_peg_nipfg"=>(string)$result[$j]->i_peg_nipfg,
									"i_peg_nipfg"=>$i_peg_nipfg,						
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
									"c_parent_cpns"=>trim($result[$j]->c_parent_cpns),
									"c_satker_cpns"=>trim($result[$j]->c_satker_cpns),
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
									"c_golongan"=>trim($result[$j]->c_golongan),
									"d_tmt_golongan"=>trim($result[$j]->d_tmt_golongan),
									"d_tmt_cpnsb"=>trim($result[$j]->d_tmt_cpnsb),									
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

///tambahan untuk update data nip////

	public function setUpdateDataNip($nipawal,$nipup) 
	{
		   $registry = Zend_Registry::getInstance();
		   $db = $registry->get('db');
		    try {
		     $db->setFetchMode(Zend_Db::FETCH_OBJ);
			  $where[] = $nipawal;
			  $where[] = $nipup;

			  $result = $db->fetchOne('SELECT sdm.updatedata(?,?)',$where);
		     return $result;
		   } catch (Exception $e) {
		 $db->rollBack();
		 echo $e->getMessage().'<br>';
		     return  0;
		   }
	}
	
	
/////=============================================================== baru ==========================================================================================

 	public function getPegawaiListPnsByNip($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{		
				
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT i_peg_nip,i_peg_nrp,i_peg_nip_new,n_peg,n_peg_gelardepan,n_peg_gelarblkg,c_peg_jeniskelamin,
									n_unitkerja_nokode,to_char(d_kesehatan_pns,'dd-mm-yyyy') as d_kesehatan_pns,
									to_char(d_sk_pns,'dd-mm-yyyy') as d_sk_pns,to_char(d_sk_prajab,'dd-mm-yyyy') as d_sk_prajab,
									i_kesehatan_pns,i_sk_pns,i_sk_prajab,n_kesehatan_pejabatpns,n_rumahsakit_pns,n_sk_pejabatpns,n_sk_pejabatprajab,
									c_lokasi_unitkerja_pns,c_eselon_i_pns,c_eselon_ii_pns,c_eselon_iii_pns,c_eselon_iv_pns,c_eselon_v_pns,c_satker_pns,c_parent_pns,e_file_photo,
									c_lokasi_unitkerja_cpns,c_eselon_i_cpns,c_eselon_ii_cpns,c_eselon_iii_cpns,c_eselon_iv_cpns,c_eselon_v_cpns,c_satker_cpns,c_parent_cpns
									FROM sdm.tm_pegawai where 1=1 $cari");
								
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 

						$c_lokasi_unitkerja=trim($result[$j]->c_lokasi_unitkerja_pns);						
						if (!$c_lokasi_unitkerja){$c_lokasi_unitkerja=trim($result[$j]->c_lokasi_unitkerja_cpns);}
						$c_eselon_i=trim($result[$j]->c_eselon_i_pns);
						if (!$c_eselon_i){$c_eselon_i=trim($result[$j]->c_eselon_i_cpns);}
						$c_eselon_ii=trim($result[$j]->c_eselon_ii_pns);
						if (!$c_eselon_ii){$c_eselon_ii=trim($result[$j]->c_eselon_ii_cpns);}
						$c_eselon_iii=trim($result[$j]->c_eselon_iii_pns);
						if (!$c_eselon_iii){$c_eselon_iii=trim($result[$j]->c_eselon_iii_cpns);}
						$c_eselon_iv=trim($result[$j]->c_eselon_iv_pns);
						if (!$c_eselon_iv){$c_eselon_iv=trim($result[$j]->c_eselon_iv_cpns);}
						$c_eselon_v=trim($result[$j]->c_eselon_v_pns);
						if (!$c_eselon_v){$c_eselon_v=trim($result[$j]->c_eselon_v_cpns);}
						$c_parent=trim($result[$j]->c_parent_pns);
						if (!$c_parent){$c_parent=trim($result[$j]->c_parent_cpns);}
						$c_satker=trim($result[$j]->c_satker_pns);
						if (!$c_satker){$c_satker=trim($result[$j]->c_satker_cpns);}
						
						if ($c_lokasi_unitkerja=='1'){
							$neselon1 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_tkt_esl='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
							$neselon2 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");							
							$neselon3 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
							$neselon4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
							$neselon5 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_eselon_v='$c_eselon_v' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
						}
						else{
							
							
							
							//echo " SELECT distinct(c_eselon_ii) FROM sdm.tr_unitkerja WHERE c_satker='$c_satker' ";
							$ceselon2 = $db->fetchOne(" SELECT distinct(c_eselon_ii) FROM sdm.tr_unitkerja WHERE c_satker='$c_satker' ");
							if (!$ceselon2){$ceselon2=$c_eselon_ii;}
							$neselon1 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_tkt_esl='1' ");
							$neselon3 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2' and c_eselon_iii='$c_eselon_iii' and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child <> '00' and c_parent ='$c_parent'  and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
							
							if ($neselon3){
								$ceseloniix = $db->fetchOne(" SELECT c_eselon_ii FROM sdm.tr_unitkerja WHERE c_level ='2' and c_eselon_i='$c_eselon_i' and c_parent='$c_parent' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
								$neselon2 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceseloniix' and c_eselon_iii = '00'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child = '00' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
						
							}
							else{
								$neselon2 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii = '00'  and c_eselon_iv = '00'  and c_eselon_v = '00' and c_child = '00' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");							
							}
							//echo "xxx ".$neselon2;
							
							
							
							if ($c_satker=='00'){
								$neselon4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2' and c_eselon_iv='$c_eselon_iv' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
							}else{
								$neselon4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2' and c_eselon_iv='$c_eselon_iv' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_satker='$c_satker'");
							}
						
						}					
					

						$data[$j] = array("i_peg_nip"=>(string)$result[$j]->i_peg_nip,
									"i_peg_nipfg"=>(string)$result[$j]->i_peg_nipfg,						
									"i_peg_nrp"=>(string)$result[$j]->i_peg_nrp,						
									"i_peg_nip_new"=>(string)$result[$j]->i_peg_nip_new,					
									"n_peg"=>(string)$result[$j]->n_peg,
									"n_peg_gelardepan"=>(string)$result[$j]->n_peg_gelardepan,
									"n_peg_gelarblkg"=>(string)$result[$j]->n_peg_gelarblkg,
									"c_peg_jeniskelamin"=>(string)$result[$j]->c_peg_jeniskelamin,
									"n_unitkerja_nokode"=>(string)$result[$j]->n_unitkerja_nokode,
									"neselon1"=>$neselon1,
									"neselon2"=>$neselon2,
									"neselon3"=>$neselon3,
									"neselon4"=>$neselon4,
									"neselon5"=>$neselon5, 
									"ceseloncpns2"=>$ceselon2, 
									"d_kesehatan_pns"=>trim($result[$j]->d_kesehatan_pns),
									"d_sk_pns"=>trim($result[$j]->d_sk_pns),
									"d_sk_prajab"=>trim($result[$j]->d_sk_prajab),
									"i_kesehatan_pns"=>trim($result[$j]->i_kesehatan_pns),
									"i_sk_pns"=>trim($result[$j]->i_sk_pns),
									"i_sk_prajab"=>trim($result[$j]->i_sk_prajab),
									"jabat_lengkap"=>trim($result[$j]->jabat_lengkap),
									"n_kesehatan_pejabatpns"=>trim($result[$j]->n_kesehatan_pejabatpns),
									"n_rumahsakit_pns"=>trim($result[$j]->n_rumahsakit_pns),
									"n_sk_pejabatpns"=>trim($result[$j]->n_sk_pejabatpns),
									"n_sk_pejabatprajab"=>trim($result[$j]->n_sk_pejabatprajab),
									"c_lokasi_unitkerja"=>$c_lokasi_unitkerja,
									"c_eselon_i"=>$c_eselon_i,
									"c_eselon_ii"=>$c_eselon_ii,
									"c_eselon_iii"=>$c_eselon_iii,
									"c_eselon_iv"=>$c_eselon_iv,
									"c_eselon_v"=>$c_eselon_v,
									"c_satker"=>$c_satker,
									"c_parent"=>$c_parent,
									/* "c_lokasi_unitkerja"=>trim($result[$j]->c_lokasi_unitkerja_pns),
									"c_eselon_i"=>trim($result[$j]->c_eselon_i_pns),
									"c_eselon_ii"=>trim($result[$j]->c_eselon_ii_pns),
									"c_eselon_iii"=>trim($result[$j]->c_eselon_iii_pns),
									"c_eselon_iv"=>trim($result[$j]->c_eselon_iv_pns),
									"c_eselon_v"=>trim($result[$j]->c_eselon_v_pns),
									"c_satker"=>trim($result[$j]->c_satker_pns),
									"c_parent"=>trim($result[$j]->c_parent_pns), */
									"e_file_photo"=>trim($result[$j]->e_file_photo),									
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

public function cekNamaUk($nama,$cesl)	
{
	$data=strtolower($nama);
	$data=trim($data);
	if ($cesl=='01'){$data=str_replace("direktorat", "", $data); $data=str_replace("jenderal", "", $data); $data=str_replace("badan", "", $data); $data=str_replace("sekretariat ma", "", $data);}
	if ($cesl=='03'){$data=str_replace("direktorat", "", $data); $data=str_replace("biro", "", $data); $data=str_replace("badan", "", $data); $data=str_replace("pusat", "", $data);}
	if ($cesl=='05'){$data=str_replace("direktorat", "", $data); $data=str_replace("sub", "", $data); $data=str_replace(".", "", $data); $data=str_replace("bagian", "", $data); $data=str_replace("bidang", "", $data); $data=str_replace("balai", "", $data);}
	if ($cesl=='04'){$data=str_replace("panitera", "", $data); $data=str_replace("muda", "", $data); }
	if ($cesl=='07'){$data=str_replace("direktorat", "", $data); $data=str_replace("sub", "", $data); $data=str_replace(".", "", $data); $data=str_replace("bagian", "", $data); $data=str_replace("bidang", "", $data); $data=str_replace("seksi", "", $data); $data=str_replace("unit", "", $data);}
	return ucwords(trim($data));

}




public function getPegawaiListPopup($cari,$currentPage, $numToDisplay) 
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
					$result = $db->fetchAll("SELECT i_peg_nip,i_peg_nrp,i_peg_nip_new, n_peg,
								to_char(d_peg_lahir,'dd-mm-yyyy') as d_peg_lahir,c_peg_propinsi_lahir,
								a_peg_kota_lahir,a_peg_kelurahan_lahir,a_peg_kecamatan_lahir,c_peg_jeniskelamin
								FROM sdm.tm_pegawai where 1=1 $cari  order by c_eselon asc   
								limit $xLimit offset $xOffset");
									
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 	
						$c_propinsi=trim((string)$result[$j]->c_peg_propinsi_lahir);
						$n_propinsi = $db->fetchOne(" SELECT n_propinsi FROM sdm.tr_propinsi WHERE c_propinsi='$c_propinsi'");
						$c_kabupaten=trim((string)$result[$j]->a_peg_kota_lahir);
						$n_kabupaten = $db->fetchOne(" SELECT n_kabupaten FROM sdm.tr_kabupaten WHERE c_kabupaten='$c_kabupaten'");
					
						if ($n_kabupaten && $n_propinsi){
							$tempatlahir=$n_kabupaten." propinsi ".$n_propinsi;
						}
						
					$data[$j] = array("i_peg_nip"=>(string)$result[$j]->i_peg_nip,
							"i_peg_nip_new"=>(string)$result[$j]->i_peg_nip_new,		
							"i_peg_nrp"=>(string)$result[$j]->i_peg_nrp,
							"n_peg"=>(string)$result[$j]->n_peg,
							"d_peg_lahir"=>(string)$result[$j]->d_peg_lahir,
							"c_peg_jeniskelamin"=>(string)$result[$j]->c_peg_jeniskelamin,
							"tempatlahir"=>$tempatlahir);	
	}
			}							
			return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
public function getPegawaiList2($masukan, $pageNumber, $itemPerPage) 
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');

		$kategoriCari 	= $masukan['kategoriCari'];
		$katakunciCari 	= $masukan['katakunciCari'];
		$lokasi			= $masukan['lokasi'];
		$eselon_i		= $masukan['eselon_i'];
		$eselon_ii		= $masukan['eselon_ii'];
		$eselon_iii		= $masukan['eselon_iii'];
		$eselon_iv		= $masukan['eselon_iv'];
		$c_parent		= $masukan['c_parent'];
		$c_child        = $masukan['c_child'];

		if($lokasi == "" || $lokasi == "-") {$lokasi	="-";}	
		if($eselon_i == "" || $eselon_i == "-") {$eselon_i	="-";}	
		if($eselon_ii == "" || $eselon_ii == "-") {$eselon_ii	="-";}	
		if($eselon_iii == "" || $eselon_iii == "-") {$eselon_iii	="-";}	
		if($c_parent == "" || $c_parent == "-") {$c_parent	="-";}	
		if($c_child == "" || $c_child == "-") {$c_child	="-";}	
		if($eselon_iv == "" || $eselon_iv == "-") {$eselon_iv	="-";}	
		
		if($kategoriCari == "") { $kategoriCari ="n_unitkerja";}	
		$xLimit=$itemPerPage;
		$xOffset=($pageNumber-1)*$itemPerPage;
		
		try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
		$whereBase = " where 1=1 ";
		if($katakunciCari){$whereOptCar = " and lower($kategoriCari) like '%$katakunciCari%' ";}
		
		$whereOpt0 = " c_lokasi_unitkerja = '$lokasi' ";
		$whereOpt1 = " c_eselon_i = '$eselon_i' ";
		if($lokasi == '1'){
		$whereOpt2 = " c_eselon_ii = '$eselon_ii' ";
		$whereOpt3 = " c_eselon_iii = '$eselon_iii' ";
		} else {
		$whereOpt2 = " c_parent ='$c_parent' ";
		$whereOpt3 = " c_child ='$c_child' ";
		}
		$whereOpt4 = " c_eselon_iv = '$eselon_iv' ";

		
		if( $lokasi != "-"){$whereBase= $whereBase." and ".$whereOpt0;} else {$whereBase= $whereBase;}
		if( $eselon_i != "-"){$whereBase= $whereBase." and ".$whereOpt1;} else {$whereBase= $whereBase;}
		if( $eselon_ii != "-"){$whereBase= $whereBase." and ".$whereOpt2;} else {$whereBase= $whereBase;}
		if( $eselon_iii != "-"){$whereBase= $whereBase." and ".$whereOpt3;} else {$whereBase= $whereBase;}
		if( $eselon_iv != "-"){$whereBase= $whereBase." and ".$whereOpt4;} else {$whereBase= $whereBase;}
		
		$whereOpt =$whereBase.$whereOptCar;
		//echo $katakunciCari;
		if(($pageNumber == 0) && ($itemPerPage == 0)){
			$sql1 = "select count(*) from  sdm.tm_pegawai $whereOpt";
			$hasilAkhir = $db->fetchOne("$sql1");	
		} else {
			$sql2 = "SELECT i_peg_nip,i_peg_nrp,i_peg_nip_new, n_peg,c_peg_status,c_status_kepegawaian,c_eselon,to_char(d_tmt_eselon,'dd-mm-yyyy') as d_tmt_eselon,
								c_jabatan,to_char(d_mulai_jabat,'dd-mm-yyyy') as d_mulai_jabat,c_peg_jeniskelamin,
								to_char(d_akhir_jabat,'dd-mm-yyyy') as d_akhir_jabat,c_golongan,d_tmt_golongan,v_gaji_pokok,
								c_lokasi_unitkerja,c_eselon_i,c_eselon_ii,c_eselon_iii,c_eselon_iv,c_eselon_v,c_gol_cpns,
								c_lokasi_unitkerja_cpns,c_eselon_cpns,e_file_photo,c_eselon_cpns,c_lokasi_unitkerja_cpns,c_eselon_i_cpns,
								c_eselon_ii_cpns,c_eselon_iii_cpns,c_eselon_iv_cpns,c_eselon_v_cpns,c_jabatan_cpns,to_char(d_tmt_kgb,'dd-mm-yyyy') as d_tmt_kgb,
								c_satker,c_parent,c_child,c_lokasi_unitkerja_pns,c_eselon_i_pns,c_eselon_ii_pns,c_eselon_iii_pns,c_eselon_iv_pns
								FROM sdm.tm_pegawai $whereOpt  order by c_eselon asc   
								limit $xLimit offset $xOffset";
			$result = $db->fetchAll("$sql2");	
		}
		//echo $sql2;
		 $jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++) {
														
			$hasilAkhir[$j] = array("i_peg_nip"=>(string)$result[$j]->i_peg_nip,
			"i_peg_nip_new"=>(string)$result[$j]->i_peg_nip_new,		
			"i_peg_nrp"=>(string)$result[$j]->i_peg_nrp,
			"n_peg"=>(string)$result[$j]->n_peg,
			"c_peg_status"=>(string)$result[$j]->c_peg_status,
			"n_status_kepegawaian"=>$n_status_kepegawaian,			
			"n_peg_status"=>$n_peg_status,											
			"e_jabatan"=>$e_jabatan,
			"n_jabatan"=>$n_jabatan,
			"n_eselon"=>$n_eselon,
			"n_eselon_cpns"=>$n_eselon_cpns,
			"n_lokasi_unitkerja"=>$n_lokasi_unitkerja,
			"c_golongan"=>$c_golongan,
			"n_golongan"=>$n_golongan,
			"n_pangkat"=>$n_pangkat,
			"c_peg_jeniskelamin"=>(string)$result[$j]->c_peg_jeniskelamin,
			"e_file_photo"=>(string)$result[$j]->e_file_photo,
			"n_pangkat_cpns"=>$n_pangkat_cpns,
			"d_tmt_kgb"=>(string)$result[$j]->d_tmt_kgb
									);
		 }			
		 
		 return $hasilAkhir;
		} catch (Exception $e) {
		 echo $e->getMessage().'<br>';
		 return 'gagal <br>';
		}
	}

}
?>