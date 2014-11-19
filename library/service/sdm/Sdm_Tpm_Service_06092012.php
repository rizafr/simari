<?php
class Sdm_Tpm_Service {
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

    
  
 	public function getTpmUsulan($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{

				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchall("select usulan_id,usulan_nomor,usulan_keterangan,to_char(mod_date,'dd-mm-yyyy') as mod_date,
								tvinstansi_kd,periode_text,periode_keterangan,c_eselon_i,c_eselon_ii,c_eselon_iii,c_eselon_iv,
								c_parent,c_satker
								from sdm.tm_usulan_tpm where 1=1 $cari  order by usulan_id desc");		
					$jmlresult = count($result);
					for ($j = 0; $j < $jmlresult; $j++) 
					{ 
						$c_jabatan=trim($result[$j]->usulan_keterangan);
						$n_jabatan = $db->fetchOne("select n_jabatan from sdm.tr_jabatan where c_jabatan='$c_jabatan'");
						//$tvinstansi_kd=trim($result[$j]->tvinstansi_kd);
						//$unitkerja = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$tvinstansi_kd' and c_tkt_esl='1'");
						$c_eselon_i=trim($result[$j]->c_eselon_i);
						$c_eselon_ii=trim($result[$j]->c_eselon_ii);
						$c_eselon_iii=trim($result[$j]->c_eselon_iii);
						$c_eselon_iv=trim($result[$j]->c_eselon_iv);
						$c_parent=trim($result[$j]->c_parent);
						$c_satker=trim($result[$j]->c_satker);
						
						if ($c_eselon_i=='03' || $c_eselon_i=='04' || $c_eselon_i=='05'){							
							$c_lokasi_unitkerja="3";
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
							if ($c_satker=='00'){
								$neselon4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2' and c_eselon_iv='$c_eselon_iv' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
							}else{
								$neselon4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2' and c_eselon_iv='$c_eselon_iv' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_satker='$c_satker'");
								//echo " SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2' and c_eselon_iv='$c_eselon_iv' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_satker='$c_satker'";
							}							
						}
						else{
							$c_lokasi_unitkerja="1";
							$neselon1 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_tkt_esl='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
							$neselon2 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");							
							$neselon3 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
							$neselon4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
							$neselon5 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_eselon_v='$c_eselon_v' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
						
						
						}
						
						$data[$j] = array("usulan_id"=>(string)$result[$j]->usulan_id,
								"usulan_nomor"=>(string)$result[$j]->usulan_nomor,
								"usulan_keterangan"=>(string)$result[$j]->usulan_keterangan,
								"n_jabatan"=>$n_jabatan,
								"tvinstansi_kd"=>(string)$result[$j]->tvinstansi_kd,
								"unitkerja"=>$unitkerja,
								"mod_date"=>(string)$result[$j]->mod_date,
								"periode_text"=>(string)$result[$j]->periode_text,
								"periode_keterangan"=>(string)$result[$j]->periode_keterangan,
								"c_eselon_i"=>(string)$result[$j]->c_eselon_i,
								"c_eselon_ii"=>(string)$result[$j]->c_eselon_ii,
								"c_eselon_iii"=>(string)$result[$j]->c_eselon_iii,
								"c_eselon_iv"=>(string)$result[$j]->c_eselon_iv,
								"c_satker"=>(string)$result[$j]->c_satker,
								"c_parent"=>(string)$result[$j]->c_parent,
								"neselon1"=>$neselon1,
								"neselon2"=>$neselon2,
								"neselon3"=>$neselon3,
								"neselon4"=>$neselon4,
								"ceseloncpns2"=>$ceselon2);									
					}
										
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}

	public function maintainDataTpmUsulan(array $data,$par) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     
	     $maintain_data = array("usulan_nomor"=>$data['usulan_nomor'],
				"usulan_keterangan"=>$data['usulan_keterangan'],
				"tvinstansi_kd"=>$data['tvinstansi_kd'],
				"mod_date"=>$data['mod_date'],
				"periode_text"=>$data['periode_text'],
				"periode_keterangan"=>$data['periode_keterangan'],
				"c_eselon_i"=>$data['c_eselon_i'],
				"c_eselon_ii"=>$data['c_eselon_ii'],
				"c_eselon_iii"=>$data['c_eselon_iii'],
				"c_eselon_iv"=>$data['c_eselon_iv'],
				"c_satker"=>$data['c_satker'],
				"c_parent"=>$data['c_parent'] );

		if ($par=='insert'){$db->insert('sdm.tm_usulan_tpm',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tm_usulan_tpm',$maintain_data, "usulan_id = '".trim($data['usulan_id'])."'");}	 
		if ($par=='delete'){$db->delete('sdm.tm_usulan_tpm', "usulan_id = '".trim($data['usulan_id'])."' ");}
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
 //==================================================================================================================================
 	public function getTpmPegawai($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{

				$db->setFetchMode(Zend_Db::FETCH_OBJ);
				//$sql = "select distinct(tpm_id) as tpm_id,a.id_list,usulan_id,peg_nip,tvjabatan_kd,tvinstansi_kd,tpm_keterangan,tpm_status,to_char(tpm_tgl,'dd-mm-yyyy') as tpm_tgl,tvinstansi_kd_l,ljabatangol_nm,tpm_jekel,tpm_tgllahir,tpm_namalengkap,lap_gol_tmt,lap_gol_nm,lap_jab_l_nm,lap_jab_l_kelas,lap_jab_l_instan,lap_jab_b_nm,lap_jab_b_kelas,lap_jab_b_instan,tpm_status_1,tpm_status_2,tpm_status_3,nama_list,nourut,tpm_stts	from sdm.tm_pegawai_tpm a, sdm.tm_list_tpm b where a.id_list=b.id_list $cari  order by id_list desc";
				//$sql = "select distinct(tpm_id) as tpm_id,a.id_list,a.usulan_id,peg_nip,tvjabatan_kd,a.tvinstansi_kd,tpm_keterangan,								tpm_status,to_char(tpm_tgl,'dd-mm-yyyy') as tpm_tgl,tvinstansi_kd_l,ljabatangol_nm,tpm_jekel,tpm_tgllahir,								tpm_namalengkap,lap_gol_tmt,lap_gol_nm,lap_jab_l_nm,lap_jab_l_kelas,lap_jab_l_instan, 				lap_jab_b_nm,lap_jab_b_kelas,lap_jab_b_instan,tpm_status_1,tpm_status_2,tpm_status_3, b.usulan_keterangan from sdm.tm_pegawai_tpm a, sdm.tm_usulan_tpm b where $cari  order by id_list desc";
				$sql = "select tpm_id,a.id_list,usulan_id,peg_nip,a.tvinstansi_kd,ket_pratpm,ket_tpm1,ket_tpm2, tpm_status,tvinstansi_kd_l,
				ljabatangol_nm,tpm_jekel,tpm_tgllahir, tpm_namalengkap,lap_gol_tmt,lap_gol_nm,lap_jab_l_nm,lap_jab_l_kelas,
				lap_jab_l_instan, lap_jab_b_nm,lap_jab_b_kelas,lap_jab_b_instan,tpm_status_1,tpm_status_2,tpm_status_3,
				(select usulan_keterangan from sdm.tm_usulan_tpm where usulan_id=a.usulan_id) as usulan_keterangan,
				to_char(tgl_pratpm,'dd-mm-yyyy') as tgl_pratpm,to_char(tgl_tpm2,'dd-mm-yyyy') as tgl_tpm1,
				to_char(tgl_tpm2,'dd-mm-yyyy') as tgl_tpm2,usulan_rekomendasi_pratpm,usulan_rekomendasi_tpmi,usulan_rekomendasi_tpmii,
				c_eselon_i,c_eselon_ii,c_eselon_iii,c_eselon_iv
				from sdm.tm_pegawai_tpm a where $cari  order by id_list desc";
					//echo $sql."<br>";
					$result = $db->fetchall($sql);		
					$jmlresult = count($result);
					for ($j = 0; $j < $jmlresult; $j++) 
					{ 
					
					

						$data[$j] = array("tpm_id"=>(string)$result[$j]->tpm_id,
								"id_list"=>(string)$result[$j]->id_list,
								"usulan_id"=>(string)$result[$j]->usulan_id,
								"peg_nip"=>(string)$result[$j]->peg_nip,
								"tvjabatan_kd"=>(string)$result[$j]->tvjabatan_kd,
								"tvinstansi_kd"=>(string)$result[$j]->tvinstansi_kd,
								"ket_pratpm"=>(string)$result[$j]->ket_pratpm,
								"ket_tpm1"=>(string)$result[$j]->ket_tpm1,
								"ket_tpm2"=>(string)$result[$j]->ket_tpm2,
								"tpm_status"=>(string)$result[$j]->tpm_status,
								"tgl_pratpm"=>(string)$result[$j]->tgl_pratpm,
								"tgl_tpm1"=>(string)$result[$j]->tgl_tpm1,
								"tgl_tpm2"=>(string)$result[$j]->tgl_tpm2,
								"tvinstansi_kd_l"=>(string)$result[$j]->tvinstansi_kd_l,
								"ljabatangol_nm"=>(string)$result[$j]->ljabatangol_nm,
								"tpm_jekel"=>(string)$result[$j]->tpm_jekel,
								"tpm_tgllahir"=>(string)$result[$j]->tpm_tgllahir,
								"tpm_namalengkap"=>(string)$result[$j]->tpm_namalengkap,
								"lap_gol_tmt"=>(string)$result[$j]->lap_gol_tmt,
								"lap_gol_nm"=>(string)$result[$j]->lap_gol_nm,
								"lap_jab_l_nm"=>(string)$result[$j]->lap_jab_l_nm,
								"lap_jab_l_kelas"=>(string)$result[$j]->lap_jab_l_kelas,
								"lap_jab_l_instan"=>(string)$result[$j]->lap_jab_l_instan,
								"lap_jab_b_nm"=>(string)$result[$j]->lap_jab_b_nm,
								"lap_jab_b_kelas"=>(string)$result[$j]->lap_jab_b_kelas,
								"lap_jab_b_instan"=>(string)$result[$j]->lap_jab_b_instan,
								"tpm_status_1"=>(string)$result[$j]->tpm_status_1,
								"tpm_status_2"=>(string)$result[$j]->tpm_status_2,
								"tpm_status_3"=>(string)$result[$j]->tpm_status_3,
								"nama_list"=>(string)$result[$j]->nama_list,
								"n_jabatan"=>$n_jabatan,
								"unitkerja"=>$unitkerja,
								"usulan_rekomendasi_pratpm"=>(string)$result[$j]->usulan_rekomendasi_pratpm,
								"usulan_rekomendasi_tpmi"=>(string)$result[$j]->usulan_rekomendasi_tpmi,
								"usulan_rekomendasi_tpmii"=>(string)$result[$j]->usulan_rekomendasi_tpmii,
								"c_eselon_i"=>(string)$result[$j]->c_eselon_i,
								"c_eselon_ii"=>(string)$result[$j]->c_eselon_ii,
								"c_eselon_iii"=>(string)$result[$j]->c_eselon_iii,
								"c_eselon_iv"=>(string)$result[$j]->c_eselon_iv	
								);									
					}
										
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}

	public function maintainDataTpmPegawai(array $data,$par) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     if($data['modul']=='pratpm'){
			$tgl_pratpm = $data['tpm_tgl'];
			$ket_pratpm = $data['nama_list'];
			$tpm_status = '0';
			$maintain_data = array("id_list"=>$data['id_list'],
				"usulan_id"=>$data['usulan_id'],
				"peg_nip"=>$data['peg_nip'],
				"tvjabatan_kd"=>$data['tvjabatan_kd'],
				"tvinstansi_kd"=>$data['tvinstansi_kd'],
				"tpm_status"=>$tpm_status,
				"tgl_pratpm"=>$tgl_pratpm,
				"ket_pratpm"=>$ket_pratpm,
				"tvinstansi_kd_l"=>$data['tvinstansi_kd_l'],
				"ljabatangol_nm"=>$data['ljabatangol_nm'],
				"tpm_jekel"=>$data['tpm_jekel'],
				"tpm_tgllahir"=>$data['tpm_tgllahir'],
				"tpm_namalengkap"=>$data['tpm_namalengkap'],
				"lap_gol_tmt"=>$data['lap_gol_tmt'],
				"lap_gol_nm"=>$data['lap_gol_nm'],
				"lap_jab_l_nm"=>$data['lap_jab_l_nm'],
				"lap_jab_l_kelas"=>$data['lap_jab_l_kelas'],
				"lap_jab_l_instan"=>$data['lap_jab_l_instan'],
				"lap_jab_b_nm"=>$data['lap_jab_b_nm'],
				"lap_jab_b_kelas"=>$data['lap_jab_b_kelas'],
				"lap_jab_b_instan"=>$data['lap_jab_b_instan'],
				"tpm_status_1"=>$data['tpm_status_1'],
				"tpm_status_2"=>$data['tpm_status_2'],
				"tpm_status_3"=>$data['tpm_status_3'],
				"usulan_rekomendasi_pratpm"=>$data['usulan_rekomendasi_pratpm'],
				"c_eselon_i"=>$data['c_eselon_i'],
				"c_eselon_ii"=>$data['c_eselon_ii'],
				"c_eselon_iii"=>$data['c_eselon_iii'],
				"c_eselon_iv"=>$data['c_eselon_iv'],
				"c_satker"=>$data['c_satker'],
				"c_parent"=>$data['c_parent']
				);
		 }
		 else  if($data['modul']=='tpmi'){
			$tgl_tpm1 = $data['tpm_tgl'];
			$ket_tpm1 = $data['nama_list'];
			$tpm_status = '1';
			$maintain_data = array("id_list"=>$data['id_list'],
				"usulan_id"=>$data['usulan_id'],
				"peg_nip"=>$data['peg_nip'],
				"tvjabatan_kd"=>$data['tvjabatan_kd'],
				"tvinstansi_kd"=>$data['tvinstansi_kd'],
				"tpm_status"=>$tpm_status,
				"tgl_tpm1"=>$tgl_tpm1,
				"ket_tpm1"=>$ket_tpm1,
				"tvinstansi_kd_l"=>$data['tvinstansi_kd_l'],
				"ljabatangol_nm"=>$data['ljabatangol_nm'],
				"tpm_jekel"=>$data['tpm_jekel'],
				"tpm_tgllahir"=>$data['tpm_tgllahir'],
				"tpm_namalengkap"=>$data['tpm_namalengkap'],
				"lap_gol_tmt"=>$data['lap_gol_tmt'],
				"lap_gol_nm"=>$data['lap_gol_nm'],
				"lap_jab_l_nm"=>$data['lap_jab_l_nm'],
				"lap_jab_l_kelas"=>$data['lap_jab_l_kelas'],
				"lap_jab_l_instan"=>$data['lap_jab_l_instan'],
				"lap_jab_b_nm"=>$data['lap_jab_b_nm'],
				"lap_jab_b_kelas"=>$data['lap_jab_b_kelas'],
				"lap_jab_b_instan"=>$data['lap_jab_b_instan'],
				"tpm_status_1"=>$data['tpm_status_1'],
				"tpm_status_2"=>$data['tpm_status_2'],
				"tpm_status_3"=>$data['tpm_status_3'],
				"usulan_rekomendasi_tpmi"=>$data['usulan_rekomendasi_tpmi']
				);
		 }
		 else {
			$tgl_tpm2 = $data['tpm_tgl'];
			$ket_tpm2 = $data['nama_list'];
			$tpm_status = '2';
			$maintain_data = array("id_list"=>$data['id_list'],
				"usulan_id"=>$data['usulan_id'],
				"peg_nip"=>$data['peg_nip'],
				"tvjabatan_kd"=>$data['tvjabatan_kd'],
				"tvinstansi_kd"=>$data['tvinstansi_kd'],
				"tpm_status"=>$tpm_status,
				"tgl_tpm2"=>$tgl_tpm2,
				"ket_tpm2"=>$ket_tpm2,
				"tvinstansi_kd_l"=>$data['tvinstansi_kd_l'],
				"ljabatangol_nm"=>$data['ljabatangol_nm'],
				"tpm_jekel"=>$data['tpm_jekel'],
				"tpm_tgllahir"=>$data['tpm_tgllahir'],
				"tpm_namalengkap"=>$data['tpm_namalengkap'],
				"lap_gol_tmt"=>$data['lap_gol_tmt'],
				"lap_gol_nm"=>$data['lap_gol_nm'],
				"lap_jab_l_nm"=>$data['lap_jab_l_nm'],
				"lap_jab_l_kelas"=>$data['lap_jab_l_kelas'],
				"lap_jab_l_instan"=>$data['lap_jab_l_instan'],
				"lap_jab_b_nm"=>$data['lap_jab_b_nm'],
				"lap_jab_b_kelas"=>$data['lap_jab_b_kelas'],
				"lap_jab_b_instan"=>$data['lap_jab_b_instan'],
				"tpm_status_1"=>$data['tpm_status_1'],
				"tpm_status_2"=>$data['tpm_status_2'],
				"tpm_status_3"=>$data['tpm_status_3'],
				"usulan_rekomendasi_tpmii"=>$data['usulan_rekomendasi_tpmii']
				);
		 }
	     
//var_dump($maintain_data);
		if ($par=='insert'){$db->insert('sdm.tm_pegawai_tpm',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tm_pegawai_tpm',$maintain_data, "tpm_id = '".trim($data['tpm_id'])."'");}	 
		if ($par=='delete'){$db->delete('sdm.tm_pegawai_tpm', "usulan_id = '".trim($data['usulan_id'])."' ");}
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function maintainDataTpmPegawaiUpdtTpmi(array $data,$par) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $tgl_tpm1 = $data['tpm_tgl'];
		 $ket_tpm1 = $data['nama_list'];
		 $tpm_status = '1';
		 $maintain_data = array("tpm_status_2"=>$data['tpm_status_2'],
				"tgl_tpm1"=>$tgl_tpm1,
				"ket_tpm1"=>$ket_tpm1);
		if ($par=='update'){$db->update('sdm.tm_pegawai_tpm',$maintain_data, "tpm_id = '".trim($data['tpm_id'])."'");}	 
		if ($par=='delete'){$db->delete('sdm.tm_pegawai_tpm', "usulan_id = '".trim($data['usulan_id'])."' ");}
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	public function maintainDataTpmPegawaiUpdtTpmii(array $data,$par) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $tgl_tpm2 = $data['tpm_tgl'];
			$ket_tpm2 = $data['nama_list'];
			$tpm_status = '2';
			
	     $maintain_data = array("tpm_status_3"=>$data['tpm_status_3'],
				"tgl_tpm2"=>$tgl_tpm2,
				"ket_tpm2"=>$ket_tpm2);
		if ($par=='update'){$db->update('sdm.tm_pegawai_tpm',$maintain_data, "tpm_id = '".trim($data['tpm_id'])."'");}	 
		if ($par=='delete'){$db->delete('sdm.tm_pegawai_tpm', "usulan_id = '".trim($data['usulan_id'])."' ");}
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
	public function maintainDataTpmList(array $data,$par) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
 
	     $maintain_data = array("id_list"=>$data['id_list'],
				"nama_list"=>$data['nama_list'],
				"nourut"=>$data['nourut'],
				"tpm_stts"=>$data['tpm_stts']);

		if ($par=='insert'){$db->insert('sdm.tm_list_tpm',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tm_list_tpm',$maintain_data, "id_list = '".trim($data['id_list'])."'");}	 
		if ($par=='delete'){$db->delete('sdm.tm_list_tpm', "id_list = '".trim($data['id_list'])."' ");}
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
 //==================================================================================================================================
  	public function getListTpm($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
  
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					
					/*$sql = "select distinct(a.usulan_id) as usulan_id ,a.usulan_nomor,
								to_char(mod_date,'dd-mm-yyyy') as mod_date,
								to_char(tpm_tgl,'dd-mm-yyyy') as tpm_tgl,
								b.usulan_id,b.id_list,c.nama_list,c.id_list,
								a.tvinstansi_kd,a.periode_text,a.periode_keterangan,a.usulan_keterangan,
								peg_nip,tpm_namalengkap
								from sdm.tm_usulan_tpm a, sdm.tm_pegawai_tpm b, sdm.tm_list_tpm c
								where  b.id_list = c.id_list $cari";*/
					$sql = "select distinct(a.usulan_id) as usulan_id ,a.usulan_nomor,
								to_char(mod_date,'dd-mm-yyyy') as mod_date,
								to_char(tpm_tgl,'dd-mm-yyyy') as tpm_tgl,
								b.usulan_id,b.id_list,
								a.tvinstansi_kd,a.periode_text,a.periode_keterangan,a.usulan_keterangan 
								from sdm.tm_usulan_tpm a, sdm.tm_pegawai_tpm b 
								where  $cari";
					//echo $sql;
					$result = $db->fetchall($sql);	
			
					$jmlresult = count($result);
					for ($j = 0; $j < $jmlresult; $j++) 
					{ 

						$data[$j] = array("usulan_id"=>(string)$result[$j]->usulan_id,
								"usulan_nomor"=>(string)$result[$j]->usulan_nomor,
								"usulan_keterangan"=>(string)$result[$j]->usulan_keterangan,
								"tvinstansi_kd"=>(string)$result[$j]->tvinstansi_kd,
								"mod_date"=>(string)$result[$j]->mod_date,
								"periode_text"=>(string)$result[$j]->periode_text,
								"periode_keterangan"=>(string)$result[$j]->periode_keterangan,
								"nama_list"=>(string)$result[$j]->nama_list,
								"tpm_tgl"=>(string)$result[$j]->tpm_tgl,
								"peg_nip"=>(string)$result[$j]->peg_nip,
								"tpm_namalengkap"=>(string)$result[$j]->tpm_namalengkap);									
					}
										
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
public function getListTpm3($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
  
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					
					/*$sql = "select distinct(a.usulan_id) as usulan_id ,a.usulan_nomor,
								to_char(mod_date,'dd-mm-yyyy') as mod_date,
								to_char(tpm_tgl,'dd-mm-yyyy') as tpm_tgl,
								b.usulan_id,b.id_list,c.nama_list,c.id_list,
								a.tvinstansi_kd,a.periode_text,a.periode_keterangan,a.usulan_keterangan,
								peg_nip,tpm_namalengkap
								from sdm.tm_usulan_tpm a, sdm.tm_pegawai_tpm b, sdm.tm_list_tpm c
								where  b.id_list = c.id_list $cari";*/
$sql = "select distinct(a.usulan_id) as usulan_id ,a.usulan_nomor,
								to_char(mod_date,'dd-mm-yyyy') as mod_date,
								b.usulan_id,b.id_list,
								a.tvinstansi_kd,a.periode_text,a.periode_keterangan,a.usulan_keterangan,
								peg_nip,tpm_namalengkap, tpm_status_1,  tpm_status_2,tpm_status_3
								from sdm.tm_usulan_tpm a, sdm.tm_pegawai_tpm b 
								where  $cari order by a.usulan_id ";
					//echo $sql;
					$result = $db->fetchall($sql);	
			
					$jmlresult = count($result);
					for ($j = 0; $j < $jmlresult; $j++) 
					{ 

						$data[$j] = array("usulan_id"=>(string)$result[$j]->usulan_id,
								"usulan_nomor"=>(string)$result[$j]->usulan_nomor,
								"usulan_keterangan"=>(string)$result[$j]->usulan_keterangan,
								"tvinstansi_kd"=>(string)$result[$j]->tvinstansi_kd,
								"mod_date"=>(string)$result[$j]->mod_date,
								"periode_text"=>(string)$result[$j]->periode_text,
								"periode_keterangan"=>(string)$result[$j]->periode_keterangan,
								"nama_list"=>(string)$result[$j]->nama_list,
								//"tpm_tgl"=>(string)$result[$j]->tpm_tgl,
								"peg_nip"=>(string)$result[$j]->peg_nip,
								"tpm_namalengkap"=>(string)$result[$j]->tpm_namalengkap,
								"tpm_status_1"=>(string)$result[$j]->tpm_status_1,
								"tpm_status_2"=>(string)$result[$j]->tpm_status_2,
								"tpm_status_3"=>(string)$result[$j]->tpm_status_3
							);									
					}
										
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
 	public function cekDataTpm($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{

				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchall("select tpm_id from sdm.tm_pegawai_tpm where 1=1 $cari");		
					$jmlresult = count($result);
					return $jmlresult;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}

	public function setNomorListTpm($cmodul) 
	{
		   $registry = Zend_Registry::getInstance();
		   $db = $registry->get('db');
		    try {
		     $db->setFetchMode(Zend_Db::FETCH_OBJ);
			  $where[] = $cmodul;
			  $result = $db->fetchOne('SELECT sdm.gen_tpmlist(?)',$where);
		     return $result;
		   } catch (Exception $e) {
		 $db->rollBack();
		 echo $e->getMessage().'<br>';
		     return  0;
		   }
	}


  	public function getListTpm2($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
  
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$sql = "select distinct(a.usulan_id) as usulan_id ,a.usulan_nomor,to_char(mod_date,'dd-mm-yyyy') as mod_date,
						a.tvinstansi_kd,a.periode_text,a.periode_keterangan,a.usulan_keterangan,c_eselon_i,c_eselon_ii,c_eselon_iii,c_eselon_iv,
						c_parent,c_satker
						from sdm.tm_usulan_tpm a  where  $cari";
					//echo $sql;
					$result = $db->fetchall($sql);	
			
					$jmlresult = count($result);
					for ($j = 0; $j < $jmlresult; $j++) 
					{ 
					$c_jabatan=trim($result[$j]->usulan_keterangan);
					$n_jabatan = $db->fetchOne("select n_jabatan from sdm.tr_jabatan where c_jabatan='$c_jabatan'");
					$tvinstansi_kd=trim($result[$j]->tvinstansi_kd);
					$unitkerja = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$tvinstansi_kd' and c_tkt_esl='1'");

					
						$c_eselon_i=trim($result[$j]->c_eselon_i);
						$c_eselon_ii=trim($result[$j]->c_eselon_ii);
						$c_eselon_iii=trim($result[$j]->c_eselon_iii);
						$c_eselon_iv=trim($result[$j]->c_eselon_iv);
						$c_parent=trim($result[$j]->c_parent);
						$c_satker=trim($result[$j]->c_satker);
						
						if ($c_eselon_i=='03' || $c_eselon_i=='04' || $c_eselon_i=='05'){							
							$c_lokasi_unitkerja="3";
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
							if ($c_satker=='00'){
								$neselon4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2' and c_eselon_iv='$c_eselon_iv' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
							}else{
								$neselon4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2' and c_eselon_iv='$c_eselon_iv' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_satker='$c_satker'");
								//echo " SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$ceselon2' and c_eselon_iv='$c_eselon_iv' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_satker='$c_satker'";
							}							
						}
						else{
							$c_lokasi_unitkerja="1";
							$neselon1 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_tkt_esl='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
							$neselon2 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_tkt_esl='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");							
							$neselon3 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_tkt_esl='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
							$neselon4 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_tkt_esl='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
							$neselon5 = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_eselon_iii='$c_eselon_iii' and c_eselon_iv='$c_eselon_iv' and c_eselon_v='$c_eselon_v' and c_tkt_esl='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
						
						
						}
					
						$data[$j] = array("usulan_id"=>(string)$result[$j]->usulan_id,
								"usulan_nomor"=>(string)$result[$j]->usulan_nomor,
								"usulan_keterangan"=>(string)$result[$j]->usulan_keterangan,
								"tvinstansi_kd"=>(string)$result[$j]->tvinstansi_kd,
								"mod_date"=>(string)$result[$j]->mod_date,
								"periode_text"=>(string)$result[$j]->periode_text,
								"n_jabatan"=>$n_jabatan,
								"unitkerja"=>$unitkerja,
								"periode_keterangan"=>(string)$result[$j]->periode_keterangan,
								"c_eselon_i"=>(string)$result[$j]->c_eselon_i,
								"c_eselon_ii"=>(string)$result[$j]->c_eselon_ii,
								"c_eselon_iii"=>(string)$result[$j]->c_eselon_iii,
								"c_eselon_iv"=>(string)$result[$j]->c_eselon_iv,
								"c_satker"=>(string)$result[$j]->c_satker,
								"c_parent"=>(string)$result[$j]->c_parent,
								"neselon1"=>$neselon1,
								"neselon2"=>$neselon2,
								"neselon3"=>$neselon3,
								"neselon4"=>$neselon4);									
					}
										
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	public function getListTpm5($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
  
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$sql = "select distinct(a.usulan_id) as usulan_id, ket_pratpm, to_char(tgl_pratpm,'dd-mm-yyyy') as tgl_pratpm, ket_tpm1, to_char(tgl_tpm1,'dd-mm-yyyy') as tgl_tpm1, ket_tpm2, to_char(tgl_tpm2,'dd-mm-yyyy') as tgl_tpm2, tpm_status	from sdm.tm_pegawai_tpm a  where  $cari";
					//echo $sql;
					$result = $db->fetchall($sql);	
			
					$jmlresult = count($result);
					for ($j = 0; $j < $jmlresult; $j++) 
					{ 

						$data[$j] = array("usulan_id"=>(string)$result[$j]->usulan_id,
								"ket_pratpm"=>(string)$result[$j]->ket_pratpm,
								"tgl_pratpm"=>(string)$result[$j]->tgl_pratpm,
								"ket_tpm1"=>(string)$result[$j]->ket_tpm1,
								"tgl_tpm1"=>(string)$result[$j]->tgl_tpm1,
								"ket_tpm2"=>(string)$result[$j]->ket_tpm2,
								"tgl_tpm2"=>(string)$result[$j]->tgl_tpm2,
								"tpm_status"=>(string)$result[$j]->tpm_status
								);									
					}
										
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	public function getListTpm4($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
  
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					//** $sql = "select distinct(a.usulan_id) as usulan_id ,a.usulan_nomor,to_char(mod_date,'dd-mm-yyyy') as mod_date,to_char(tpm_tgl,'dd-mm-yyyy') as tpm_tgl,b.usulan_id,b.id_list,c.nama_list,c.id_list,a.tvinstansi_kd,a.periode_text,a.periode_keterangan,a.usulan_keterangan	from sdm.tm_usulan_tpm a, sdm.tm_pegawai_tpm b, sdm.tm_list_tpm c  where  b.id_list = c.id_list $cari";
					//$sql = "select distinct(a.usulan_id) as usulan_id ,a.usulan_nomor,to_char(mod_date,'dd-mm-yyyy') as mod_date,to_char(tpm_tgl,'dd-mm-yyyy') as tpm_tgl,b.usulan_id,b.id_list,a.tvinstansi_kd,a.periode_text,a.periode_keterangan,a.usulan_keterangan as nama_list,a.usulan_keterangan	from sdm.tm_usulan_tpm a, sdm.tm_pegawai_tpm b  where  $cari";
					$sql = "select distinct(a.usulan_id) as usulan_id ,a.usulan_nomor,to_char(mod_date,'dd-mm-yyyy') as mod_date, to_char(tpm_tgl,'dd-mm-yyyy')  as tpm_tgl,a.tvinstansi_kd,a.periode_text,a.periode_keterangan,a.usulan_keterangan as nama_list,a.usulan_keterangan	from sdm.tm_usulan_tpm a  where  $cari";
					//echo $sql;
					$result = $db->fetchall($sql);	
			
					$jmlresult = count($result);
					for ($j = 0; $j < $jmlresult; $j++) 
					{ 
					$c_jabatan=trim($result[$j]->usulan_keterangan);
					$n_jabatan = $db->fetchOne("select n_jabatan from sdm.tr_jabatan where c_jabatan='$c_jabatan'");
					$tvinstansi_kd=trim($result[$j]->tvinstansi_kd);
					$unitkerja = $db->fetchOne(" SELECT n_unitkerja FROM sdm.tr_unitkerja WHERE c_eselon_i='$tvinstansi_kd' and c_tkt_esl='1'");
					
						$data[$j] = array("usulan_id"=>(string)$result[$j]->usulan_id,
								"usulan_nomor"=>(string)$result[$j]->usulan_nomor,
								"usulan_keterangan"=>(string)$result[$j]->usulan_keterangan,
								"tvinstansi_kd"=>(string)$result[$j]->tvinstansi_kd,
								"mod_date"=>(string)$result[$j]->mod_date,
								"periode_text"=>(string)$result[$j]->periode_text,
								"periode_keterangan"=>(string)$result[$j]->periode_keterangan,
								"nama_list"=>(string)$result[$j]->nama_list,
								"n_jabatan"=>$n_jabatan,
								"unitkerja"=>$unitkerja,
								"tpm_tgl"=>(string)$result[$j]->tpm_tgl);									
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