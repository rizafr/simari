<?php
class Sdm_Angkakredit_Service {
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

	public function getAngkaKreditList($cari) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("SELECT  id,i_peg_nip,d_peg_pnilai,q_utama,q_pendidikan,
								q_keg_teknis,q_profesi,q_penunjang,q_totalnilai,
								a_lembaga,i_sk,to_char(d_sk,'dd-mm-yyyy') as d_sk,n_sk_pejabat,i_entry,d_entry
								from sdm.tm_penilaian_ak where 1=1 $cari");
					
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						
					$data[$j] = array("id"=>(string)$result[$j]->id,
										"i_peg_nip"=>(string)$result[$j]->i_peg_nip,
										"d_peg_pnilai"=>(string)$result[$j]->d_peg_pnilai,
										"q_utama"=>(string)$result[$j]->q_utama,
										"q_pendidikan"=>(string)$result[$j]->q_pendidikan,
										"q_keg_teknis"=>(string)$result[$j]->q_keg_teknis,
										"q_profesi"=>(string)$result[$j]->q_profesi,						
										"q_penunjang"=>(string)$result[$j]->q_penunjang,
										"q_totalnilai"=>(string)$result[$j]->q_totalnilai,
										"a_lembaga"=>(string)$result[$j]->a_lembaga,
										"i_sk"=>(string)$result[$j]->i_sk,
										"d_sk"=>(string)$result[$j]->d_sk,
										"n_sk_pejabat"=>(string)$result[$j]->n_sk_pejabat,
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
								"d_peg_pnilai"=>$data['d_peg_pnilai'],
								"q_utama"=>$data['q_utama'],
								"q_pendidikan"=>$data['q_pendidikan'],
								"q_keg_teknis"=>$data['q_keg_teknis'],
								"q_profesi"=>$data['q_profesi'],					
								"q_penunjang"=>$data['q_penunjang'],
								"q_totalnilai"=>$data['q_totalnilai'],
								"a_lembaga"=>$data['a_lembaga'],
								"i_sk"=>$data['i_sk'],
								"d_sk"=>$data['d_sk'],
								"n_sk_pejabat"=>$data['n_sk_pejabat'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));
		if ($par=='insert'){$db->insert('sdm.tm_penilaian_ak',$maintain_data);}
		if ($par=='update'){$db->update('sdm.tm_penilaian_ak',$maintain_data, "id = '".trim($data['id'])."'");}	 
		if ($par=='delete'){$db->delete('sdm.tm_penilaian_ak', " id = '".trim($data['id'])."' ");}
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