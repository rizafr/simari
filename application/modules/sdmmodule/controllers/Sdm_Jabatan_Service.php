<?php
class Sdm_Jabatan_Service {
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
    
public function cekNamaUk($nama,$cesl)	
{

/* 
00	Mahkamah Agung RI
01	Badan
01	Direktorat Jenderal
01	Sekretariat MA
03	Biro
03	Direktorat
03	Inspektorat
03	Pengadilan
03	Pusat
04	Pengadilan
05	Bagian
05	Pengadilan
05	Sub. Direktorat
07	Seksi
07	Sub. Bagian
07	Sub. Bidang
09	Urusan
14	Kepaniteraan 
05 	Pengadilan Negeri
*/

	$data=strtolower($nama);
	$data=trim($data);
/* 	
	$data=str_replace("badan", "", $data); 
	$data=str_replace("direktorat", "", $data); 
	$data=str_replace("jenderal", "", $data);
	$data=str_replace("sekretariat", "", $data);
	$data=str_replace("ma", "", $data);
	$data=str_replace("biro", "", $data);  
	$data=str_replace("inspektorat", "", $data); 
	$data=str_replace("pusat", "", $data);
	$data=str_replace("pengadilan", "", $data);
	$data=str_replace("negeri", "", $data);
	$data=str_replace("bagian", "", $data); 
	$data=str_replace("sub.", "", $data);
	$data=str_replace("Seksi", "", $data); 
	$data=str_replace("bidang.", "", $data);
	$data=str_replace("urusan", "", $data);
	$data=str_replace("kepaniteraan", "", $data);
*/
	if ($cesl=='01'){$data=str_replace("direktorat", "", $data); $data=str_replace("jenderal", "", $data); $data=str_replace("badan", "", $data); $data=str_replace("sekretariat ma", "", $data);}
	if ($cesl=='03'){$data=str_replace("direktorat", "", $data); $data=str_replace("biro", "", $data); $data=str_replace("badan", "", $data); $data=str_replace("pusat", "", $data);}
	if ($cesl=='05'){$data=str_replace("direktorat", "", $data); $data=str_replace("sub", "", $data); $data=str_replace(".", "", $data); $data=str_replace("bagian", "", $data); $data=str_replace("bidang", "", $data); $data=str_replace("balai", "", $data);}
	if ($cesl=='04'){$data=str_replace("panitera", "", $data); $data=str_replace("muda", "", $data); }
	if ($cesl=='07'){$data=str_replace("direktorat", "", $data); $data=str_replace("sub", "", $data); $data=str_replace(".", "", $data); $data=str_replace("bagian", "", $data); $data=str_replace("bidang", "", $data); $data=str_replace("seksi", "", $data); $data=str_replace("unit", "", $data);}
	return ucwords(trim($data)); 


}
 	public function getJabatanList($cari) 
	{
	
	
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
		
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT  id,i_peg_nip,c_eselon,to_char(d_tmt_eselon,'dd-mm-yyyy') as d_tmt_eselon,
										c_jabatan,n_jabatan_nokode,to_char(d_mulai_jabat,'dd-mm-yyyy') as d_mulai_jabat,
										to_char(d_akhir_jabat,'dd-mm-yyyy') as d_akhir_jabat,
										q_angka_kredit,c_lokasi_unitkerja,c_eselon_i,c_eselon_ii,c_eselon_iii,
										c_eselon_iv,c_eselon_v,a_alamat_kantor,i_sk_jabat,
										to_char(d_sk_jabat,'dd-mm-yyyy') as d_sk_jabat,
										n_sk_pejabat,to_char(d_tmt_lantik,'dd-mm-yyyy') as d_tmt_lantik,
										n_lok_kppn,n_lok_taspen,e_keterangan,i_entry,d_entry,e_file_sk,c_parent,c_satker	
										FROM sdm.tm_jabatan where 1=1 $cari  order by d_mulai_jabat asc");
			
				
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 		
						
						$c_parent=trim($result[$j]->c_parent);
						$c_satker=trim($result[$j]->c_satker);
						
						
						
						
						
						$c_eselon=trim($result[$j]->c_eselon);
						$n_eselon = $db->fetchOne("select n_eselon  from sdm.tr_eselon where c_eselon='$c_eselon'");	
						$c_jabatan=trim($result[$j]->c_jabatan);
						$n_jabatan = $db->fetchOne("select n_jabatan from sdm.tr_jabatan where c_jabatan='$c_jabatan'");					
						$c_lokasi_unitkerja=trim($result[$j]->c_lokasi_unitkerja);
						$c_eselon_i=trim($result[$j]->c_eselon_i);
						$c_eselon_ii=trim($result[$j]->c_eselon_ii);
						$c_eselon_iii=trim($result[$j]->c_eselon_iii);
						$c_eselon_iv=trim($result[$j]->c_eselon_iv);
						$c_eselon_v=trim($result[$j]->c_eselon_v);
						$par='n';
						if ($c_lokasi_unitkerja=='1'){
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
						else{						
							
							$c_satker=trim($result[$j]->c_satker);
							$ceselon2 = $db->fetchOne(" SELECT distinct(c_eselon_ii) FROM sdm.tr_unitkerja WHERE c_satker='$c_satker' ");
							if (!$ceselon2){$ceselon2=$c_eselon_ii;}
							if ($c_satker=='00'){
								$neselon4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2' and c_eselon_iv='$c_eselon_iv' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
							}else{
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
						}	
						// if ($neselon1){$nesl1=" ,$neselon1";}
						// if ($neselon2){$nesl2=" ,$neselon2";}
						// if ($neselon3){$nesl3=" ,$neselon3";}
						// if ($neselon4){$nesl4=",$neselon4";}
						// if ($neselon5){$nesl5=" ,$neselon5";}
						//if ($neselon1){$unitkerjalengkap="pada ".$nesl5.$nesl4.$nesl3.$nesl2.$nesl1;}						
						$unitkerjalengkap=$nesl5.$nesl4.$nesl3.$nesl2.$nesl1;
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
						$data[$j] = array("id"=>(string)$result[$j]->id,
											"i_peg_nip"=>(string)$result[$j]->i_peg_nip,
										"c_eselon"=>(string)$result[$j]->c_eselon,
										"n_eselon"=>$n_eselon,
										"d_tmt_eselon"=>(string)$result[$j]->d_tmt_eselon,
										"c_jabatan"=>(string)$result[$j]->c_jabatan,
										"e_jabatan"=>$e_jabatan,
										"n_jabatan"=>$n_jabatan,
										"n_jabatan_nokode"=>(string)$result[$j]->n_jabatan_nokode,
										"d_mulai_jabat"=>(string)$result[$j]->d_mulai_jabat,
										"d_akhir_jabat"=>(string)$result[$j]->d_akhir_jabat,
										"q_angka_kredit"=>(string)$result[$j]->q_angka_kredit,
										"c_lokasi_unitkerja"=>(string)$result[$j]->c_lokasi_unitkerja,
										"n_lokasi_unitkerja"=>(string)$result[$j]->n_lokasi_unitkerja,
										"unitkerjalengkap"=>$unitkerjalengkap,
										"c_eselon_i"=>trim($result[$j]->c_eselon_i),
										"n_eselon_i"=>$neselon1,
										"c_eselon_ii"=>trim($result[$j]->c_eselon_ii),
										"n_eselon_ii"=>$neselon2,
										"c_eselon_iii"=>trim($result[$j]->c_eselon_iii),
										"n_eselon_iii"=>$neselon3,
										"c_eselon_iv"=>trim($result[$j]->c_eselon_iv),
										"n_eselon_iv"=>$neselon4,
										"c_eselon_v"=>trim($result[$j]->c_eselon_v),
										"n_eselon_v"=>$neselon5,
										"a_alamat_kantor"=>(string)$result[$j]->a_alamat_kantor,
										"i_sk_jabat"=>(string)$result[$j]->i_sk_jabat,
										"d_sk_jabat"=>(string)$result[$j]->d_sk_jabat,
										"n_sk_pejabat"=>(string)$result[$j]->n_sk_pejabat,
										"d_tmt_lantik"=>(string)$result[$j]->d_tmt_lantik,
										"n_lok_kppn"=>(string)$result[$j]->n_lok_kppn,
										"n_lok_taspen"=>(string)$result[$j]->n_lok_taspen,
										"e_keterangan"=>(string)$result[$j]->e_keterangan,
										"e_file_sk"=>(string)$result[$j]->e_file_sk,
										"ceselon2"=>$ceselon2,
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

	public function maintainData(array $data,$par) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	   
	     $db->beginTransaction();
	     $maintain_data = array("i_peg_nip"=>$data['i_peg_nip'],
								"c_eselon"=>$data['c_eselon'],
								"d_tmt_eselon"=>$data['d_tmt_eselon'],
								"c_jabatan"=>$data['c_jabatan'],
								"n_jabatan_nokode"=>$data['n_jabatan_nokode'],
								"d_mulai_jabat"=>$data['d_mulai_jabat'],
								"d_akhir_jabat"=>$data['d_akhir_jabat'],
								"q_angka_kredit"=>$data['q_angka_kredit'],
								"c_lokasi_unitkerja"=>$data['c_lokasi_unitkerja'],
								"c_eselon_i"=>$data['c_eselon_i'],
								"c_eselon_ii"=>$data['c_eselon_ii'],
								"c_eselon_iii"=>$data['c_eselon_iii'],
								"c_eselon_iv"=>$data['c_eselon_iv'],
								"c_eselon_v"=>$data['c_eselon_v'],
								"a_alamat_kantor"=>$data['a_alamat_kantor'],
								"i_sk_jabat"=>$data['i_sk_jabat'],
								"d_sk_jabat"=>$data['d_sk_jabat'],
								"n_sk_pejabat"=>$data['n_sk_pejabat'],
								"d_tmt_lantik"=>$data['d_tmt_lantik'],
								"n_lok_kppn"=>$data['n_lok_kppn'],
								"n_lok_taspen"=>$data['n_lok_taspen'],
								"e_keterangan"=>$data['e_keterangan'],
								"e_file_sk"=>$data['e_file_sk'],
								"c_parent"=>$data['c_parent'],
								"c_satker"=>$data['c_satker'],
								"c_kelfgs"=>$data['c_kelfgs'],
								"q_tktfgs"=>$data['q_tktfgs'],	
								"i_entry"=>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));
  

		if ($par=='insert'){$db->insert('sdm.tm_jabatan',$maintain_data);}
		//if ($par=='update'){$db->update('sdm.tm_jabatan',$maintain_data, "i_peg_nip = '".trim($data['i_peg_nip'])."'  and c_jabatan = '".trim($data['c_jabatan2'])."' and d_mulai_jabat = '".trim($data['d_mulai_jabat2'])."'");}	 
		//if ($par=='delete'){$db->delete('sdm.tm_jabatan', "i_peg_nip = '".trim($data['i_peg_nip'])."'  and c_jabatan = '".trim($data['c_jabatan2'])."' and d_mulai_jabat = '".trim($data['d_mulai_jabat2'])."' ");}
		if ($par=='update'){$db->update('sdm.tm_jabatan',$maintain_data, "id = '".trim($data['id'])."'");}	 
		if ($par=='delete'){$db->delete('sdm.tm_jabatan', "id = '".trim($data['id'])."' ");}
		
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function updateTmPegawai(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	   
	     $db->beginTransaction();
	     $maintain_data = array("c_eselon"=>$data['c_eselon'],
					"d_tmt_eselon"=>$data['d_tmt_eselon'],
					"c_jabatan"=>$data['c_jabatan'],
					"d_mulai_jabat"=>$data['d_mulai_jabat'],
					"d_akhir_jabat"=>$data['d_akhir_jabat'],
					"c_lokasi_unitkerja"=>$data['c_lokasi_unitkerja'],
					"c_eselon_i"=>$data['c_eselon_i'],
					"c_eselon_ii"=>$data['c_eselon_ii'],
					"c_eselon_iii"=>$data['c_eselon_iii'],
					"c_eselon_iv"=>$data['c_eselon_iv'],
					"c_eselon_v"=>$data['c_eselon_v'],
					"c_satker"=>$data['c_satker'],	
					"c_bidang"=>$data['c_bidang'],	
					"c_type"=>$data['c_type'],	
					"c_parent"=>$data['c_parent'],	
					"c_child"=>$data['c_child'],	
					"d_tmt_lantik"=>$data['d_tmt_lantik'],						
					"q_usia_pensiun"=>$data['q_usia_pensiun'],
					"q_tktfgs"=>$data['q_tktfgs'],
					"c_kelfgs"=>$data['c_kelfgs']);
			
		$db->update('sdm.tm_pegawai',$maintain_data, "i_peg_nip = '".trim($data['i_peg_nip'])."'");
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
}
?>