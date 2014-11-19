<?php
class formulir2_Service {
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
	public function getdatagol()
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);
				
					$result = $db->fetchAll("select trim(c_peg_golongan) as c_peg_golongan,trim(n_peg_golongan) as n_peg_golongan from 
								sdm.tr_golongan_pangkat
								where c_peg_tipegolongan='3'  order by c_peg_golongan asc
								");
					
				return $result;
			}catch (Exception $e)
			{
				echo $e->getMessage().'<br>';
				return 'Data tidak ada <br>';
			}
	}
	
	public function hapusformulir($tahun)
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);
				
				$result = $db->fetchOne("delete from sdm.tm_formasiformulir2 where thn_skrg='$tahun'");
					
				//return $result;
			}catch (Exception $e)
			{
				echo $e->getMessage().'<br>';
				return 'Data tidak ada <br>';
			}
	}
	
	
	public function getdataformasi($thn_skrg) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$result = $db->fetchAll("select a.c_golongan, b.n_peg_golongan,a.bezetting, a.thn_skrg, a.knaikn_pngkt, a.pengangkt_pgwai_br,
							a.pndah_dr_instasi_lain, a.pndah_ke_instasi_lain,  a.ket
							from sdm.tm_formasiformulir2 a left join sdm.tr_golongan_pangkat b on 
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
	public function tambahformulir(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->beginTransaction();
					
			$c_golongan = $dataMasukan['c_golongan'];
			if($dataMasukan['bezetting'] == ""){
			$bezetting = 0;
			}else{
			$bezetting = $dataMasukan['bezetting'];
			}
			
			$thn_skrg = $dataMasukan['thn_skrg'];
			
			if($dataMasukan['knaikn_pngkt'] == ""){
			$knaikn_pngkt = 0;
			}else{
			$knaikn_pngkt = $dataMasukan['knaikn_pngkt'];
			}
			
			
			
			if($dataMasukan['pengangkt_pgwai_br'] == ""){
			$pengangkt_pgwai_br = 0;
			}else{
			$pengangkt_pgwai_br = $dataMasukan['pengangkt_pgwai_br'];
			}
			
			if($dataMasukan['pndah_dr_instasi_lain'] == ""){
			$pndah_dr_instasi_lain = 0;
			}else{
			$pndah_dr_instasi_lain = $dataMasukan['pndah_dr_instasi_lain'];
			}
			
			if($dataMasukan['pndah_ke_instasi_lain'] == ""){
			$pndah_ke_instasi_lain = 0;
			}else{
			$pndah_ke_instasi_lain = $dataMasukan['pndah_ke_instasi_lain'];
			}
			
			
			
			$ket = $dataMasukan['ket'];
			$i_entry = $dataMasukan['i_entry'];
						
			$paramInput = array("c_golongan"	=> $c_golongan,
					"bezetting"	=> $bezetting,
					"thn_skrg"	=> $thn_skrg,
					"knaikn_pngkt"	=> $knaikn_pngkt,
					"pndah_dr_instasi_lain"	=> $pndah_dr_instasi_lain,
					"pndah_ke_instasi_lain"	=> $pndah_ke_instasi_lain,
					"pengangkt_pgwai_br"	=> $pengangkt_pgwai_br,
					"ket"	=> $ket,
					"i_entry"	=> $i_entry,
					"d_entry"	=> date('Y-m-d H:i:s'));
					//var_dump($paramInput);
			$db->insert('sdm.tm_formasiformulir2',$paramInput);
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
	
}?>