<?php
class Sdm_JabatanFungsional_Service {
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

 	public function getJabatanList($cari) 
	{
	
	
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
			
			
		
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT  id,i_peg_nip,c_statusjabatan,c_alasan,c_eselon,c_jabatan,
								to_char(d_mulai_jabat,'dd-mm-yyyy') as d_mulai_jabat,to_char(d_akhir_jabat,'dd-mm-yyyy') as d_akhir_jabat,
								q_angka_kredit,i_sk_jabat,to_char(d_sk_jabat,'dd-mm-yyyy') as d_sk_jabat ,n_sk_pejabat,n_lembaga,
								c_tkfgs,c_kelfgs,q_tktfgs,i_entry,d_entry,e_file_sk	
								FROM sdm.tm_jabatan_fungsional where 1=1 $cari  order by d_entry asc");

							
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 		
						$c_eselon=trim($result[$j]->c_eselon);
						$n_eselon = $db->fetchOne("select n_eselon  from sdm.tr_eselon where c_eselon='$c_eselon'");	
						$c_jabatan=trim($result[$j]->c_jabatan);
						$n_jabatan = $db->fetchOne("select n_jabatan from sdm.tr_jabatan where c_jabatan='$c_jabatan'");						
						
						$c_statusjabatan=(string)$result[$j]->c_statusjabatan;
						if ($c_statusjabatan=="1"){$n_statusjabatan="Pembebasan Sementara";}
						if ($c_statusjabatan=="2"){$n_statusjabatan="Pengangkatan Kembali";}
						if ($c_statusjabatan=="3"){$n_statusjabatan="Pengukuhan";}
						if ($c_statusjabatan=="4"){$n_statusjabatan="Pemberhentian";}
						
						$c_alasan=trim($result[$j]->c_alasan);
						$n_alasan = $db->fetchOne("select n_statusfung from sdm.tr_statfungsional where c_statusfung='$c_alasan'");
		
						$data[$j] = array("id"=>(string)$result[$j]->id,
									"i_peg_nip"=>(string)$result[$j]->i_peg_nip,
								"c_eselon"=>(string)$result[$j]->c_eselon,
								"n_eselon"=>$n_eselon,
								"c_jabatan"=>(string)$result[$j]->c_jabatan,
								"n_jabatan"=>$n_jabatan,
								"d_mulai_jabat"=>(string)$result[$j]->d_mulai_jabat,
								"d_akhir_jabat"=>(string)$result[$j]->d_akhir_jabat,
								"q_angka_kredit"=>(string)$result[$j]->q_angka_kredit,
								"i_sk_jabat"=>(string)$result[$j]->i_sk_jabat,
								"d_sk_jabat"=>(string)$result[$j]->d_sk_jabat,
								"n_sk_pejabat"=>(string)$result[$j]->n_sk_pejabat,
								"c_statusjabatan"=>(string)$result[$j]->c_statusjabatan,
								"n_statusjabatan"=>$n_statusjabatan,								
								"c_alasan"=>(string)$result[$j]->c_alasan,
								"n_alasan"=>$n_alasan,
								"n_lembaga"=>(string)$result[$j]->n_lembaga,
								"c_tkfgs"=>(string)$result[$j]->c_tkfgs,
								"c_kelfgs"=>(string)$result[$j]->c_kelfgs,
								"e_file_sk"=>(string)$result[$j]->e_file_sk,
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
					"c_jabatan"=>$data['c_jabatan'],
					"d_mulai_jabat"=>$data['d_mulai_jabat'],
					"d_akhir_jabat"=>$data['d_akhir_jabat'],
					"q_angka_kredit"=>$data['q_angka_kredit'],
					"i_sk_jabat"=>$data['i_sk_jabat'],
					"d_sk_jabat"=>$data['d_sk_jabat'],
					"n_sk_pejabat"=>$data['n_sk_pejabat'],
					"c_statusjabatan"=>$data['c_statusjabatan'],
					"c_alasan"=>$data['c_alasan'],
					"n_lembaga"=>$data['n_lembaga'],
					"c_tkfgs"=>$data['c_tkfgs'],
					"c_kelfgs"=>$data['c_kelfgs'],
					"q_tktfgs"=>$data['q_tktfgs'],
					"e_file_sk"=>$data['e_file_sk'],					
					"i_entry"=>$data['i_entry'],
					"d_entry"=>date("Y-m-d"));

		if ($par=='insert'){$db->insert('sdm.tm_jabatan_fungsional',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tm_jabatan_fungsional',$maintain_data, "id = '".trim($data['id'])."'");}	 
		if ($par=='delete'){$db->delete('sdm.tm_jabatan_fungsional', "id = '".trim($data['id'])."'");}
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
	     $maintain_data = array("c_kelfgs"=>$data['c_kelfgs'],
				"q_tktfgs"=>$data['q_tktfgs'],
				"c_statusfjabatan"=>$data['c_statusfjabatan'],
				"c_fjabatan_alasan"=>$data['c_fjabatan_alasan']);			
		$db->update('sdm.tm_pegawai',$maintain_data, "i_peg_nip = '".trim($data['i_peg_nip'])."'");
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function updateTmJabatan(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	   
	     $db->beginTransaction();
	     $maintain_data = array("c_kelfgs"=>$data['c_kelfgs'],
				"q_tktfgs"=>$data['q_tktfgs']);			
		$db->update('sdm.tm_jabatan',$maintain_data, "i_peg_nip = '".trim($data['i_peg_nip'])."' and c_jabatan ='".trim($data['c_jabatan'])."' and to_char(d_tmt_lantik,'yyyy-mm-dd')='".$data['d_tmt_lantik']."'");
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}						
 	public function getJabatanTr($cari) 
	{
	
	
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
			
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
					$result = $db->fetchAll("SELECT c_tkfgs,c_kelfgs,c_eselon,q_tktfgs FROM sdm.tr_jabatan where 1=1 $cari");
							
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 		
	
						$data[$j] = array(
								"c_eselon"=>(string)$result[$j]->c_eselon,
								"c_tkfgs"=>(string)$result[$j]->c_tkfgs,
								"c_kelfgs"=>(string)$result[$j]->c_kelfgs);									
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