<?php
class refrekonsiliasi_Service {
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

	public function getRekonsiliasiList($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
					$result = $db->fetchAll("(select c_satker as satker, c_satker || '-' || replace(replace(replace(n_unitkerja, 'Direktorat Jenderal', 'Ditjen'), 
								'Badan Penelitian dan Pengembangan dan Pendidikan dan Pelatihan Hukum dan Peradilan', 'Balitbangdiklatkumdil'),
								'Ditjen Badan Peradilan Militer dan Tata Usaha Negara', 'Badilmiltun') as nama_satker
									from sdm.tr_unitkerja
									where c_satker is not null
									and c_eselon_ii = '00'
									and c_eselon_iii = '00'
									and c_eselon_iv = '00'
									and c_eselon_v = '00' 
									order by c_eselon_i)
									union all
									(select c_satker, c_satker || '-' || replace(n_unitkerja, 'Pengadilan Tinggi', 'PT')
									from sdm.tr_unitkerja
									where c_satker is not null
									and c_lokasi_unitkerja = '3'
									and c_eselon_iii = '00'
									and c_eselon_iv = '00'
									and c_eselon_v = '00'
									and c_child = '00' 
									order by c_bidang, c_eselon_i, c_eselon_ii)
									union all
									(select c_satker, c_satker || '-' || replace(n_unitkerja, 'Pengadilan Negeri', 'PN')
									from sdm.tr_unitkerja
									where c_satker is not null
									and c_lokasi_unitkerja = '3'
									and c_eselon_iii = '00'
									and c_eselon_iv = '00'
									and c_eselon_v = '00'
									and c_child != '00' 
									order by c_bidang, c_eselon_i, c_eselon_ii)");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("c_satker"=>(string)$result[$j]->c_satker,
									"satker"=>(string)$result[$j]->satker,
									"nama_satker"=>(string)$result[$j]->nama_satker);}
				
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	public function getRekon($cari, $kdsatker, $tahun) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
					$result = $db->fetchAll("select kdsatker , thang , perksai , nmperk6 , rphreal , sum_aset
								from
								(select kdsatker, thang, perksai, nmperk6, rphreal
								from keu.trntglsai a right join keu.tm_perk6 b on (a.perksai = b.kdperk6)
								  --right join aset.tr_perk c on (a.perksai = c.kd_perk)
								where perksai between '115111' and '154112'
								  and kdsatker='$kdsatker'
								  and thang='$tahun'
								  and periode='12'
								) x left join (
								select thn_ang, kd_perk, sum(rph_aset) sum_aset
								from aset.tm_masterhm a right join aset.tm_sskel b using (kd_brg)
								where kd_perk between '115111' and '154112'
								  and thn_ang='$tahun'
								group by thn_ang, kd_perk
								) y
								on (thang=thn_ang and perksai=kd_perk)
								order by 1,2,3,4");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("perksai"=>(string)$result[$j]->perksai,
									"nmperk6"=>(string)$result[$j]->nmperk6,
									"rphreal"=>(string)$result[$j]->rphreal,
									"sum_aset"=>(string)$result[$j]->sum_aset);}
				
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
}?>