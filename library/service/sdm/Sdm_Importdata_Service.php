<?php

class Sdm_Importdata_Service {

    private static $instance;

    // A private constructor; prevents direct creation of object
    private function __construct() {
        //echo 'I am constructed';
    }

    // The singleton method
    public static function getInstance() {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }

        return self::$instance;
    }

    /* public function maintainData(array $data) {
      $registry = Zend_Registry::getInstance();
      $db = $registry->get('db');

      try {
      $db->beginTransaction();
      $maintain_data = array("i_peg_nip"=>$data['i_peg_nip'],
      "i_peg_nip_new"=>$data['i_peg_nip_new'],
      "n_peg"=>$data['n_peg'],
      "c_status_kepegawaian"=>$data['c_status_kepegawaian'],
      "c_pend"=>$data['c_pend'],
      "d_pend_akhir"=>$data['d_pend_akhir'],
      "a_peg_kota_lahir"=>$data['a_peg_kota_lahir'],
      "d_peg_lahir"=>$data['d_peg_lahir'],
      "c_peg_jeniskelamin"=>$data['c_peg_jeniskelamin'],
      "c_agama"=>$data['c_agama'],
      "c_peg_statusnikah"=>$data['c_peg_statusnikah'],
      "d_tmt_cpns"=>$data['d_tmt_cpns'],
      "c_jenis_naik"=>$data['c_jenis_naik'],
      "c_golongan"=>$data['c_golongan'],
      "d_tmt_golongan"=>$data['d_tmt_golongan'],
      "c_eselon"=>$data['c_eselon'],
      "c_jabatan"=>$data['c_jabatan'],
      "d_mulai_jabat"=>$data['d_mulai_jabat'],
      "c_stat_aktivasi"=>$data['c_stat_aktivasi'],
      "d_entry"=>$data['d_entry'],
      "c_lokasi_unitkerja"=>$data['c_lokasi_unitkerja'],
      "c_eselon_i"=>$data['c_eselon_i'],
      "c_eselon_ii"=>$data['c_eselon_ii'],
      "c_eselon_iii"=>$data['c_eselon_iii'],
      "c_eselon_iv"=>$data['c_eselon_iv'],
      "c_eselon_v"=>$data['c_eselon_v'],
      "c_peg_status"=>$data['c_peg_status'],
      "c_peg_propinsi_lahir"=>$data['c_peg_propinsi_lahir']);
      $db->insert('sdm.tm_pegawai',$maintain_data);
      $db->commit();
      return 'sukses';
      } catch (Exception $e) {
      $db->rollBack();
      echo $e->getMessage().'<br>';
      return 'gagal';
      }
      } */

    public function maintainData(array $data) {
        $registry = Zend_Registry::getInstance();
        $db = $registry->get('db');

        try {
            $db->beginTransaction();
            $maintain_data = array("i_peg_nip" => $data['i_peg_nip'],
                "i_peg_nip_new" => $data['i_peg_nip_new'],
                "n_peg" => $data['n_peg'],
                "c_status_kepegawaian" => $data['c_status_kepegawaian'],
                "c_pend" => $data['c_pend'],
                "d_pend_akhir" => $data['d_pend_akhir'],
                "a_peg_kota_lahir" => $data['a_peg_kota_lahir'],
                "d_peg_lahir" => $data['d_peg_lahir'],
                "c_peg_jeniskelamin" => $data['c_peg_jeniskelamin'],
                "c_agama" => $data['c_agama'],
                "c_peg_statusnikah" => $data['c_peg_statusnikah'],
                "d_tmt_cpns" => $data['d_tmt_cpns'],
                "c_jenis_naik" => $data['c_jenis_naik'],
                "c_golongan" => $data['c_golongan'],
                "d_tmt_golongan" => $data['d_tmt_golongan'],
                "c_eselon" => $data['c_eselon'],
                "c_jabatan" => $data['c_jabatan'],
                "d_mulai_jabat" => $data['d_mulai_jabat'],
                "c_stat_aktivasi" => $data['c_stat_aktivasi'],
                "d_entry" => $data['d_entry'],
                "c_lokasi_unitkerja" => $data['c_lokasi_unitkerja'],
                "c_eselon_i" => $data['c_eselon_i'],
                "c_eselon_ii" => $data['c_eselon_ii'],
                "c_eselon_iii" => $data['c_eselon_iii'],
                "c_eselon_iv" => $data['c_eselon_iv'],
                "c_eselon_v" => $data['c_eselon_v'],
                "c_lokasi_unitkerja_cpns" => $data['c_lokasi_unitkerja_cpns'],
                "c_eselon_i_cpns" => $data['c_eselon_i_cpns'],
                "c_eselon_ii_cpns" => $data['c_eselon_ii_cpns'],
                "c_eselon_iii_cpns" => $data['c_eselon_iii_cpns'],
                "c_eselon_iv_cpns" => $data['c_eselon_iv_cpns'],
                "c_eselon_v_cpns" => $data['c_eselon_v_cpns'],
                "c_lokasi_unitkerja_pns" => $data['c_lokasi_unitkerja_pns'],
                "c_eselon_i_pns" => $data['c_eselon_i_pns'],
                "c_eselon_ii_pns" => $data['c_eselon_ii_pns'],
                "c_eselon_iii_pns" => $data['c_eselon_iii_pns'],
                "c_eselon_iv_pns" => $data['c_eselon_iv_pns'],
                "c_eselon_v_pns" => $data['c_eselon_v_pns'],
                "c_peg_status" => $data['c_peg_status'],
                "c_peg_propinsi_lahir" => $data['c_peg_propinsi_lahir']);
            $db->insert('sdm.tm_pegawai', $maintain_data);
            $db->commit();
            return 'sukses';
        } catch (Exception $e) {
            $db->rollBack();
            echo $e->getMessage() . '<br>';
            return 'gagal';
        }
    }

    public function getPegawaiListByNip($cari) {
        $registry = Zend_Registry::getInstance();
        $db = $registry->get('db');
        try {

            $db->setFetchMode(Zend_Db::FETCH_OBJ);
            $result = $db->fetchAll("SELECT i_peg_nip FROM sdm.tm_pegawai where 1=1 $cari");
            $jmlResult = count($result);
            for ($j = 0; $j < $jmlResult; $j++) {
                $data[$j] = array("i_peg_nip" => (string) $result[$j]->i_peg_nip);
            }
            return $data;
        } catch (Exception $e) {
            echo $e->getMessage() . '<br>';
            return 'Data tidak ada <br>';
        }
    }
    
    public function getPegawaiByNip($cari) {
        $registry = Zend_Registry::getInstance();
        $db = $registry->get('db');
        try {
            $db->setFetchMode(Zend_Db::FETCH_OBJ);
            $result = $db->fetchOne("SELECT i_peg_nip FROM sdm.tm_pegawai where 1=1 $cari");
            return $result;
        } catch (Exception $e) {
            echo $e->getMessage() . '<br>';
            return 'Data tidak ada <br>';
        }
    }

}

?>