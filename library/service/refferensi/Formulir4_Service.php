<?php
class formulir4_Service {
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
	public function hapusformulir($tahun)
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);
				
				$result = $db->fetchOne("delete from sdm.tm_formasiformulir4 where thn_skrg='$tahun'");
					
				//return $result;
			}catch (Exception $e)
			{
				echo $e->getMessage().'<br>';
				return 'Data tidak ada <br>';
			}
	}
	public function tambahformulir(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$c_golongan = $dataMasukan['c_golongan'];
			
			if($dataMasukan['formasi'] == ""){
				$formasi = 0;
			}else{
				$formasi = $dataMasukan['formasi'];
			}
			
			if($dataMasukan['jum_pegawai'] == ""){
				$jum_pegawai = 0;
			}else{
				$jum_pegawai = $dataMasukan['jum_pegawai'];
			}
			
			
			$thn_skrg = $dataMasukan['thn_skrg'];
			$ket = $dataMasukan['ket'];
			$i_entry = $dataMasukan['i_entry'];
				
			
			$paramInput = array("c_golongan"	=> $c_golongan,
					"thn_skrg"	=> $thn_skrg,
					"jum_pegawai"	=> $jum_pegawai,
					"formasi"	=> $formasi,
					"ket"	=> $ket,
					"i_entry"	=> $i_entry,
					"d_entry"	=> date('Y-m-d H:i:s'));
					//var_dump($paramInput);
			$db->insert('sdm.tm_formasiformulir4',$paramInput);
			$db->commit();
			
			return "sukses";
			
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			
			$errMsg = $errmsgArr[0];

			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				//return "gagal";
				return $e->getMessage();
			}
	   }
	}
	public function getdataformasi($thn_skrg) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchAll("select a.c_golongan, 
						b.n_peg_pangkat,b.n_peg_golongan,
						a.jum_pegawai,
						a.thn_skrg, a.formasi,a.ket
							from sdm.tm_formasiformulir4 a left join sdm.tr_golongan_pangkat b on 
							a.c_golongan = b.c_peg_golongan
								where b.c_peg_tipegolongan='3'
							and a.thn_skrg  = '".$thn_skrg."' order by a.c_golongan" );
							
							
				return $result;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
}?>