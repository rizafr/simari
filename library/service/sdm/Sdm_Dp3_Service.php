<?php
class Sdm_Dp3_Service {
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

	public function getDp3List($cari) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		
		$result = $db->fetchAll("SELECT  id,i_peg_nip,d_peg_pnilai,d_peg_pnilaiawal,d_peg_pnilaiakhir,i_peg_nippnilai,
								i_peg_nipatasanpnilai,q_peg_kesetiaan,q_peg_preskerja,q_peg_tggjawab,
								q_peg_ketaatan,q_peg_kejujuran,q_peg_kerjasama,q_peg_prakarsa,
								q_peg_kpimpinan,q_peg_totalnilai,q_peg_nilairata,e_peg_keberatan,
								e_peg_tgpanpnilai,e_peg_kputusanatasan,i_entry,d_entry,c_peg_golongan,
								n_jabatanpenilai,n_jabatanatasan
								from sdm.tm_penilaian_dp3 where 1=1 $cari");
					
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$id=(string)$result[$j]->id;
						$i_peg_nip=(string)$result[$j]->i_peg_nip;
						$n_peg=$db->fetchOne("select n_peg  from sdm.tm_pegawai where i_peg_nip='$i_peg_nip'");	
						// $c_golongan=(string)$result[$j]->c_peg_golongan;
						// $n_golongan=$db->fetchOne("select n_peg_golongan  from sdm.tr_golongan_pangkat where c_peg_golongan='$c_golongan'");
						// $n_pangkat=$db->fetchOne("select n_peg_pangkat  from sdm.tr_golongan_pangkat where c_peg_golongan='$c_golongan'");
						// $c_jabatan=$db->fetchOne("select c_jabatan  from sdm.tm_pegawai where i_peg_nip='$i_peg_nip'");
						// $c_jabatan=trim($c_jabatan);
						// $n_jabatan=$db->fetchOne("select n_jabatan  from sdm.tr_jabatan where c_jabatan='$c_jabatan'");
						
						$i_peg_nippnilai=(string)$result[$j]->i_peg_nippnilai;
						if(strlen($i_peg_nippnilai) ==18){
							$n_peg_nippnilai=$db->fetchOne("select n_peg  from sdm.tm_pegawai where i_peg_nip_new='$i_peg_nippnilai'");	
							//$c_jabatan_pnilai=$db->fetchOne("select c_jabatan  from sdm.tm_pegawai where i_peg_nip_new='$i_peg_nippnilai'");
							//$c_jabatan_pnilai=trim($c_jabatan_pnilai);
							//$n_jabatan_pnilai=$db->fetchOne("select n_jabatan  from sdm.tr_jabatan where c_jabatan='$c_jabatan_pnilai'");						
						
						} else {
							$n_peg_nippnilai=$db->fetchOne("select n_peg  from sdm.tm_pegawai where i_peg_nip='$i_peg_nippnilai'");	
							//$c_jabatan_pnilai=$db->fetchOne("select c_jabatan  from sdm.tm_pegawai where i_peg_nip='$i_peg_nippnilai'");
							//$c_jabatan_pnilai=trim($c_jabatan_pnilai);
							//$n_jabatan_pnilai=$db->fetchOne("select n_jabatan  from sdm.tr_jabatan where c_jabatan='$c_jabatan_pnilai'");						
						}
						$i_peg_nipatasanpnilai=(string)$result[$j]->i_peg_nipatasanpnilai;
							
						if(strlen($i_peg_nipatasanpnilai) ==18){
							$n_peg_nipatasanpnilai=$db->fetchOne("select n_peg  from sdm.tm_pegawai where i_peg_nip_new='$i_peg_nipatasanpnilai'");	
							//$c_jabatan_atasanpnilai=$db->fetchOne("select c_jabatan  from sdm.tm_pegawai where i_peg_nip_new='$i_peg_nipatasanpnilai'");
							//$c_jabatan_atasanpnilai=trim($c_jabatan_atasanpnilai);
							//$n_jabatan_atasanpnilai=$db->fetchOne("select n_jabatan  from sdm.tr_jabatan where c_jabatan='$c_jabatan_atasanpnilai'");						
					} else {
							$n_peg_nipatasanpnilai=$db->fetchOne("select n_peg  from sdm.tm_pegawai where i_peg_nip='$i_peg_nipatasanpnilai'");	
							//$c_jabatan_atasanpnilai=$db->fetchOne("select c_jabatan  from sdm.tm_pegawai where i_peg_nip='$i_peg_nipatasanpnilai'");
							//$c_jabatan_atasanpnilai=trim($c_jabatan_atasanpnilai);
							//$n_jabatan_atasanpnilai=$db->fetchOne("select n_jabatan  from sdm.tr_jabatan where c_jabatan='$c_jabatan_atasanpnilai'");						
						}
					$data[$j] = array("id"=>$id,
										"i_peg_nip"=>$i_peg_nip,
										"n_peg"=>$n_peg,
										"c_peg_golongan"=>$c_golongan,
										"n_pangkat"=>$n_pangkat,
										"n_golongan"=>$n_golongan,										
										"c_jabatan"=>$c_jabatan,
										"n_jabatan"=>$n_jabatan,
										"d_peg_pnilai"=>(string)$result[$j]->d_peg_pnilai,
										"d_peg_pnilaiawal"=>(string)$result[$j]->d_peg_pnilaiawal,
										"d_peg_pnilaiakhir"=>(string)$result[$j]->d_peg_pnilaiakhir,
										"i_peg_nippnilai"=>(string)$result[$j]->i_peg_nippnilai,
										"n_peg_nippnilai"=>$n_peg_nippnilai,										
										"i_peg_nipatasanpnilai"=>(string)$result[$j]->i_peg_nipatasanpnilai,
										"n_peg_nipatasanpnilai"=>$n_peg_nipatasanpnilai,
										"n_jabatanpenilai"=>(string)$result[$j]->n_jabatanpenilai,
										"n_jabatanatasan"=>(string)$result[$j]->n_jabatanatasan,
										"q_peg_kesetiaan"=>(string)$result[$j]->q_peg_kesetiaan,
										"q_peg_preskerja"=>(string)$result[$j]->q_peg_preskerja,
										"q_peg_tggjawab"=>(string)$result[$j]->q_peg_tggjawab,
										"q_peg_ketaatan"=>(string)$result[$j]->q_peg_ketaatan,
										"q_peg_kejujuran"=>(string)$result[$j]->q_peg_kejujuran,
										"q_peg_kerjasama"=>(string)$result[$j]->q_peg_kerjasama,
										"q_peg_prakarsa"=>(string)$result[$j]->q_peg_prakarsa,
										"q_peg_kpimpinan"=>(string)$result[$j]->q_peg_kpimpinan,
										"q_peg_totalnilai"=>(string)$result[$j]->q_peg_totalnilai,
										"q_peg_nilairata"=>(string)$result[$j]->q_peg_nilairata,
										"e_peg_keberatan"=>(string)$result[$j]->e_peg_keberatan,
										"e_peg_tgpanpnilai"=>(string)$result[$j]->e_peg_tgpanpnilai,
										"e_peg_kputusanatasan"=>(string)$result[$j]->e_peg_kputusanatasan,
										"i_entry"=>(string)$result[$j]->i_entry,
										"d_entry"=>(string)$result[$j]->d_entry);}
										
					return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	

	public function maintainData(array $data,$par) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();

	     $maintain_data = array("i_peg_nip"=>$data['i_peg_nip'],
								"c_peg_golongan"=>$data['c_peg_golongan'],
								"d_peg_pnilai"=>$data['d_peg_pnilai'],
								"d_peg_pnilaiawal"=>$data['d_peg_pnilaiawal'],
								"d_peg_pnilaiakhir"=>$data['d_peg_pnilaiakhir'],
								"i_peg_nippnilai"=>$data['i_peg_nippnilai'],
								"i_peg_nipatasanpnilai"=>$data['i_peg_nipatasanpnilai'],
								"n_jabatanpenilai"=>$data['n_jabatanpenilai'],
								"n_jabatanatasan"=>$data['n_jabatanatasan'],
								"q_peg_kesetiaan"=>$data['q_peg_kesetiaan'],
								"q_peg_preskerja"=>$data['q_peg_preskerja'],
								"q_peg_tggjawab"=>$data['q_peg_tggjawab'],
								"q_peg_ketaatan"=>$data['q_peg_ketaatan'],
								"q_peg_kejujuran"=>$data['q_peg_kejujuran'],
								"q_peg_kerjasama"=>$data['q_peg_kerjasama'],
								"q_peg_prakarsa"=>$data['q_peg_prakarsa'],
								"q_peg_kpimpinan"=>$data['q_peg_kpimpinan'],
								"q_peg_totalnilai"=>$data['q_peg_totalnilai'],
								"q_peg_nilairata"=>$data['q_peg_nilairata'],
								"e_peg_keberatan"=>$data['e_peg_keberatan'],
								"e_peg_tgpanpnilai"=>$data['e_peg_tgpanpnilai'],
								"e_peg_kputusanatasan"=>$data['e_peg_kputusanatasan'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));
		if ($par=='insert'){$db->insert('sdm.tm_penilaian_dp3',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tm_penilaian_dp3',$maintain_data, " id = '".trim($data['id'])."'");}	 
		if ($par=='delete'){$db->delete('sdm.tm_penilaian_dp3', " id = '".trim($data['id'])."' ");}
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