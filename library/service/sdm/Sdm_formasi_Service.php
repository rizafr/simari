<?php
class Sdm_formasi_Service {
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
	public function getpegawaiin($c_gol,$tahun)
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);
				$sql = "select count(distinct(i_peg_nip)) from sdm.tm_pegawai
						where c_golongan='$c_gol' and c_status_kepegawaian='2' and  to_char(d_mulai_jabat,'YYYY') ='$tahun'";
				$data = $db->fetchOne($sql);
					
				return $data;
			}catch (Exception $e)
			{
				echo $e->getMessage().'<br>';
				return 'Data tidak ada <br>';
			}
	}
	public function getpegawaiout($c_gol,$tahun)
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);
				$sql = "select count(distinct(i_peg_nip)) from sdm.tm_pegawai
						where c_golongan='$c_gol' and c_lokasi_unitkerja='5' and  to_char(d_mulai_jabat,'YYYY') ='$tahun'";
				$data = $db->fetchOne($sql);
					
				return $data;
			}catch (Exception $e)
			{
				echo $e->getMessage().'<br>';
				return 'Data tidak ada <br>';
			}
	}
	public function getpegawaipensiun($c_gol,$tahun)
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);
				$sql = "select count(distinct(i_peg_nip)) from sdm.tm_pegawai
						where c_golongan='$c_gol' and c_eselon='17' and  to_char(d_mulai_jabat,'YYYY') ='$tahun'";
				$data = $db->fetchOne($sql);
					
				return $data;
			}catch (Exception $e)
			{
				echo $e->getMessage().'<br>';
				return 'Data tidak ada <br>';
			}
	}
	public function getpegawainew($c_gol,$tahun)
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);
				$sql = "select count(distinct(i_peg_nip)) from sdm.tm_pegawai
						where c_golongan='$c_gol' and c_peg_status='2CP' and  to_char(d_tmt_cpns,'YYYY') ='$tahun'";
				$data = $db->fetchOne($sql);
					
				return $data;
			}catch (Exception $e)
			{
				echo $e->getMessage().'<br>';
				return 'Data tidak ada <br>';
			}
	}
	public function getBezeting($c_gol,$tahun,$par=null)
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);
				$sql = "select count(distinct(i_peg_nip)) from sdm.tm_pegawai 
						where c_golongan='$c_gol' ";
				if($par= 'current')	$sql .= " and to_char(d_tmt_golongan,'YYYY') ='$tahun'";
				else if($par= 'old') $sql .= " and to_char(d_tmt_golongan,'YYYY') < '$tahun'";
				
				$data = $db->fetchOne($sql);
					
				return $data;
			}catch (Exception $e)
			{
				echo $e->getMessage().'<br>';
				return 'Data tidak ada <br>';
			}
	}
	public function getKP($c_gol,$tahun)
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try
			{
				$db->setFetchMode(Zend_Db::FETCH_OBJ);
				$sql = "select count(distinct(a.i_peg_nip)) from sdm.tm_golongan_pangkat a ,sdm.tm_pegawai b
						where a.i_peg_nip = b.i_peg_nip and a.c_golongan='$c_gol' 
						and to_char(a.d_tmt_golongan,'YYYY') ='$tahun'";
				$data = $db->fetchOne($sql);
					
				return $data;
			}catch (Exception $e)
			{
				echo $e->getMessage().'<br>';
				return 'Data tidak ada <br>';
			}
	}
	
}?>