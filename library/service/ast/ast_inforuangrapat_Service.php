<?php
class ast_setuju_ruangrapat_Service {   

	private static $instance;
	   
// A private constructor; prevents direct creation of object
	private function __construct() {
//  echo 'I am constructed';
	}
 
// The singleton method
	public static function getInstance() {
	   if (!isset(self::$instance)) {
	       $c = __CLASS__;
	       self::$instance = new $c;
	   }	
	   return self::$instance;
	}
	
	
	// fungsi Utama Info Ruang Rapat 
	public function getAllPersetujuanRTList() {        
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$query="SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,A.i_orgb,n_rapat_judul,q_rapat_peserta,i_rapat_mailtgjwb,i_rapat_telptgjwb,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai " .
					" ,i_ruang,c_rapat_konsumsipagi,c_rapat_konsumsisiang,c_rapat_makansiang,c_rapat_tipemakansiang,c_rapat_makanmalam,c_rapat_tipemakanmalam,i_rapat_nippimpin,i_rapat_nippesan,c_rapat_statsetuju " .
					" ,c_rapat_statsetuju1,d_rapat_usul,d_rapat_usuljamawal,d_rapat_usuljamakh,c_rapat_statjwbusul,d_rapat_jwbusul,A.i_entry,A.d_entry,n_peg,n_orgb" .
					" FROM e_ast_ajuan_pakai_ruang_tm A,e_sdm_pegawai_0_tm B,e_org_0_0_tm C" .
					" where i_peg_nip=i_rapat_nippesan and B.i_orgb=C.i_orgb and (c_rapat_statsetuju isnull or c_rapat_statsetuju='' ) ";
			$query=$query." union SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,A.i_orgb,n_rapat_judul,q_rapat_peserta,i_rapat_mailtgjwb,i_rapat_telptgjwb,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai " .
					" ,i_ruang,c_rapat_konsumsipagi,c_rapat_konsumsisiang,c_rapat_makansiang,c_rapat_tipemakansiang,c_rapat_makanmalam,c_rapat_tipemakanmalam,i_rapat_nippimpin,i_rapat_nippesan,c_rapat_statsetuju " .
					" ,c_rapat_statsetuju1,d_rapat_usul,d_rapat_usuljamawal,d_rapat_usuljamakh,c_rapat_statjwbusul,d_rapat_jwbusul,A.i_entry,A.d_entry,n_peg,n_orgb" .
					" FROM e_ast_ajuan_pakai_ruang_tm A,e_sdm_pegawai_0_tm B,e_org_0_0_tm C" .
					" where i_peg_nip=i_rapat_nippesan and B.i_orgb=C.i_orgb and (c_rapat_statsetuju='U') and (c_rapat_statjwbusul isnull or c_rapat_statjwbusul='' ) ";
			$query=$query." union SELECT i_ruang_ajuanpakai,d_ruang_ajuanpakai,A.i_orgb,n_rapat_judul,q_rapat_peserta,i_rapat_mailtgjwb,i_rapat_telptgjwb,d_rapat_pesan,d_rapat_awalpakai,d_rapat_akhirpakai " .
					" ,i_ruang,c_rapat_konsumsipagi,c_rapat_konsumsisiang,c_rapat_makansiang,c_rapat_tipemakansiang,c_rapat_makanmalam,c_rapat_tipemakanmalam,i_rapat_nippimpin,i_rapat_nippesan,c_rapat_statsetuju " .
					" ,c_rapat_statsetuju1,d_rapat_usul,d_rapat_usuljamawal,d_rapat_usuljamakh,c_rapat_statjwbusul,d_rapat_jwbusul,A.i_entry,A.d_entry,n_peg,n_orgb" .
					" FROM e_ast_ajuan_pakai_ruang_tm A,e_sdm_pegawai_0_tm B,e_org_0_0_tm C" .
					" where i_peg_nip=i_rapat_nippesan and B.i_orgb=C.i_orgb and (c_rapat_statsetuju='Y') and (c_rapat_statsetuju1 isnull or c_rapat_statsetuju1='' ) and (c_rapat_konsumsipagi='Y' or c_rapat_konsumsisiang='Y' or c_rapat_makansiang='Y' or c_rapat_makanmalam='Y') ";
			//echo "Querynya :".$query."<br>";
			$result = $db->fetchAll($query);
			$jmlResult = count($result);
			if($jmlResult > 0){
	 			for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("i_ruang_ajuanpakai"=>(string)$result[$j]->i_ruang_ajuanpakai,
						"d_ruang_ajuanpakai"=>(string)$result[$j]->d_ruang_ajuanpakai,
						"i_orgb"=>(string)$result[$j]->i_orgb,
						"n_rapat_judul"=>(string)$result[$j]->n_rapat_judul,
						"n_atk_tipe"=>(string)$result[$j]->n_atk_tipe,
						"q_rapat_peserta"=>(string)$result[$j]->q_rapat_peserta,
						"i_rapat_mailtgjwb"=>(string)$result[$j]->i_rapat_mailtgjwb,
						"i_rapat_telptgjwb"=>(string)$result[$j]->i_rapat_telptgjwb,
						"d_rapat_pesan"=>(string)$result[$j]->d_rapat_pesan,
						"d_rapat_awalpakai"=>(string)$result[$j]->d_rapat_awalpakai,
						"d_rapat_akhirpakai"=>(string)$result[$j]->d_rapat_akhirpakai,

						"i_ruang"=>(string)$result[$j]->i_ruang,
						"c_rapat_konsumsipagi"=>(string)$result[$j]->c_rapat_konsumsipagi,
						"c_rapat_konsumsisiang"=>(string)$result[$j]->c_rapat_konsumsisiang,
						"c_rapat_makansiang"=>(string)$result[$j]->c_rapat_makansiang,
						"c_rapat_tipemakansiang"=>(string)$result[$j]->c_rapat_tipemakansiang,
						"c_rapat_makanmalam"=>(string)$result[$j]->c_rapat_makanmalam,
						"c_rapat_tipemakanmalam"=>(string)$result[$j]->c_rapat_tipemakanmalam,
						"i_rapat_nippimpin"=>(string)$result[$j]->i_rapat_nippimpin,
						"i_rapat_nippesan"=>(string)$result[$j]->i_rapat_nippesan,
						"c_rapat_statsetuju"=>(string)$result[$j]->c_rapat_statsetuju,
						
						"c_rapat_statsetuju1"=>(string)$result[$j]->c_rapat_statsetuju1,
						"d_rapat_usul"=>(string)$result[$j]->d_rapat_usul,
						"d_rapat_usuljamawal"=>(string)$result[$j]->d_rapat_usuljamawal,
						"d_rapat_usuljamakh"=>(string)$result[$j]->d_rapat_usuljamakh,
						"c_rapat_statjwbusul"=>(string)$result[$j]->c_rapat_statjwbusul,
						"d_rapat_jwbusul"=>(string)$result[$j]->d_rapat_jwbusul,
						"i_entry"=>(string)$result[$j]->i_entry,
						"d_entry"=>(string)$result[$j]->d_entry,
						"n_peg"=>(string)$result[$j]->n_peg,
						"n_orgb"=>(string)$result[$j]->n_orgb
						);
	 			}
			}
			return $hasilAkhir;
		}
		catch (Exception $e){
	    		echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}	

}	
?>