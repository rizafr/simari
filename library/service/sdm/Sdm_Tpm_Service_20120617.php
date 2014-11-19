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
								tvinstansi_kd,periode_text,periode_keterangan
								from sdm.tm_usulan_tpm where 1=1 $cari  order by usulan_id desc");		
					$jmlresult = count($result);
					for ($j = 0; $j < $jmlresult; $j++) 
					{ 

						$data[$j] = array("usulan_id"=>(string)$result[$j]->usulan_id,
								"usulan_nomor"=>(string)$result[$j]->usulan_nomor,
								"usulan_keterangan"=>(string)$result[$j]->usulan_keterangan,
								"tvinstansi_kd"=>(string)$result[$j]->tvinstansi_kd,
								"mod_date"=>(string)$result[$j]->mod_date,
								"periode_text"=>(string)$result[$j]->periode_text,
								"periode_keterangan"=>(string)$result[$j]->periode_keterangan);									
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
				"periode_keterangan"=>$data['periode_keterangan']);

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
				$sql = "select distinct(tpm_id) as tpm_id,a.id_list,a.usulan_id,peg_nip,tvjabatan_kd,a.tvinstansi_kd,tpm_keterangan,								tpm_status,to_char(tpm_tgl,'dd-mm-yyyy') as tpm_tgl,tvinstansi_kd_l,ljabatangol_nm,tpm_jekel,tpm_tgllahir,								tpm_namalengkap,lap_gol_tmt,lap_gol_nm,lap_jab_l_nm,lap_jab_l_kelas,lap_jab_l_instan, 				lap_jab_b_nm,lap_jab_b_kelas,lap_jab_b_instan,tpm_status_1,tpm_status_2,tpm_status_3, b.usulan_keterangan
								from sdm.tm_pegawai_tpm a, sdm.tm_usulan_tpm b where $cari  order by id_list desc";
					//echo $sql;
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
								"tpm_keterangan"=>(string)$result[$j]->tpm_keterangan,
								"tpm_status"=>(string)$result[$j]->tpm_status,
								"tpm_tgl"=>(string)$result[$j]->tpm_tgl,
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
								"nama_list"=>(string)$result[$j]->nama_list);									
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
	     
	     $maintain_data = array("id_list"=>$data['id_list'],
				"usulan_id"=>$data['usulan_id'],
				"peg_nip"=>$data['peg_nip'],
				"tvjabatan_kd"=>$data['tvjabatan_kd'],
				"tvinstansi_kd"=>$data['tvinstansi_kd'],
				"tpm_keterangan"=>$data['tpm_keterangan'],
				"tpm_status"=>$data['tpm_status'],
				"tpm_tgl"=>$data['tpm_tgl'],
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
				"tpm_status_3"=>$data['tpm_status_3']
				);
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
	     
	     $maintain_data = array("tpm_status_2"=>$data['tpm_status_2']);
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
	     
	     $maintain_data = array("tpm_status_3"=>$data['tpm_status_3']);
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
								to_char(tpm_tgl,'dd-mm-yyyy') as tpm_tgl,
								b.usulan_id,b.id_list,
								a.tvinstansi_kd,a.periode_text,a.periode_keterangan,a.usulan_keterangan,
								peg_nip,tpm_namalengkap  
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
					//** $sql = "select distinct(a.usulan_id) as usulan_id ,a.usulan_nomor,to_char(mod_date,'dd-mm-yyyy') as mod_date,to_char(tpm_tgl,'dd-mm-yyyy') as tpm_tgl,b.usulan_id,b.id_list,c.nama_list,c.id_list,a.tvinstansi_kd,a.periode_text,a.periode_keterangan,a.usulan_keterangan	from sdm.tm_usulan_tpm a, sdm.tm_pegawai_tpm b, sdm.tm_list_tpm c  where  b.id_list = c.id_list $cari";
					$sql = "select distinct(a.usulan_id) as usulan_id ,a.usulan_nomor,to_char(mod_date,'dd-mm-yyyy') as mod_date,to_char(tpm_tgl,'dd-mm-yyyy') as tpm_tgl,b.usulan_id,b.id_list,a.tvinstansi_kd,a.periode_text,a.periode_keterangan,a.usulan_keterangan as nama_list,a.usulan_keterangan	from sdm.tm_usulan_tpm a, sdm.tm_pegawai_tpm b  where  $cari";
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