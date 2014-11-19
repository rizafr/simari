<?php
class refbarang_Service {
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

	public function getBarangList($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
					$result = $db->fetchAll("(select c_satker as satker, c_satker || '-' || replace(replace(n_unitkerja, 'Direktorat Jenderal', 'Ditjen'), 
								'Badan Penelitian dan Pengembangan dan Pendidikan dan Pelatihan Hukum dan Peradilan', 'Balitbangdiklatkumdil') as nama_satker
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
	
	public function getRekon($cari, $kdsatker, $tahun, $currentPage, $numToDisplay)  
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
					$sql="select *
						from aset.tm_masterhm
						where substr(kd_lokasi,10,6) = '$kdsatker'
						and thn_ang <= '$tahun'";
					
					if(($currentPage == 0) && ($numToDisplay == 0)){
					$data = $db->fetchOne("select count(*) from ($sql) a");
					} else {
					$xLimit=$numToDisplay;
			  		$xOffset=($currentPage-1)*$numToDisplay;
					
					$result = $db->fetchAll("select *
								from aset.tm_masterhm
								where substr(kd_lokasi,10,6) = '$kdsatker'
								  and thn_ang <= '$tahun' limit $xLimit offset $xOffset");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{
						$data[$j] = array("merk_type"=>(string)$result[$j]->merk_type,
									"keterangan"=>(string)$result[$j]->keterangan,
									"no_aset"=>(string)$result[$j]->no_aset,
									"tgl_perlh"=>(string)$result[$j]->tgl_perlh,
									"asal_perlh"=>(string)$result[$j]->asal_perlh,
									"rph_aset"=>(string)$result[$j]->rph_aset);}
					}
				
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
}?>