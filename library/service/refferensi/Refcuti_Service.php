<?php

class refcuti_Service {

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

    public function getCutiList($cari, $currentPage, $numToDisplay) {
        
        $registry = Zend_Registry::getInstance();
        $db = $registry->get('db');
        try {
            $db->setFetchMode(Zend_Db::FETCH_OBJ);

            $sql = "select c_cuti, n_cuti,max_lama_cuti from sdm.tr_cuti_pegawai where 1=1 $cari";

            if (($currentPage == 0) && ($numToDisplay == 0)) {
                $data = $db->fetchOne("select count(*) from ($sql) a");
            } else {
                $xLimit = $numToDisplay;
                $xOffset = ($currentPage - 1) * $numToDisplay;

                $result = $db->fetchAll("$sql order by c_cuti limit $xLimit offset $xOffset");
                $jmlResult = count($result);
                for ($j = 0; $j < $jmlResult; $j++) {
                    $data[$j] = array(
                        "c_cuti" => (string) $result[$j]->c_cuti,
                        "n_cuti" => (string) $result[$j]->n_cuti,
                        "max_lama_cuti" => (string) $result[$j]->max_lama_cuti    
                    );
                }
            }
            return $data;
        } catch (Exception $e) {
            echo $e->getMessage() . '<br>';
            return 'Data tidak ada <br>';
        }
    }

    public function tambahcuti(array $dataMasukan) {
        $registry = Zend_Registry::getInstance();
        $db = $registry->get('db');

        try {
            $db->beginTransaction();

            $n_cuti = $dataMasukan['n_cuti'];
            $c_cuti = $dataMasukan['c_cuti'];
            $i_entry = $dataMasukan['i_entry'];
            $max_lama_cuti =$dataMasukan['max_lama_cuti'];

            $paramInput = array(
                "n_cuti" => $n_cuti,
                "c_cuti" => $c_cuti,
                "i_entry" => $i_entry,
                "d_entry" => date('Y-m-d'),
                "max_lama_cuti" => $max_lama_cuti
            );
            //var_dump($paramInput);
            $db->insert('sdm.tr_cuti_pegawai', $paramInput);
            $db->commit();

            return "sukses";
        } catch (Exception $e) {
            $db->rollBack();
            $errmsgArr = explode(":", $e->getMessage());

            $errMsg = $errmsgArr[0];

            if ($errMsg == "SQLSTATE[23000]") {
                return "gagal.Data Sudah Ada.";
            } else {
                //return "gagal";
                return $e->getMessage();
            }
        }
    }

    public function detailCuti($masukan) {
        $registry = Zend_Registry::getInstance();
        $db = $registry->get('db');
        try {
            $db->setFetchMode(Zend_Db::FETCH_OBJ);
            $result = $db->fetchRow("select c_cuti, n_cuti,max_lama_cuti from sdm.tr_cuti_pegawai 
							where c_cuti  = '" . $masukan['c_cuti'] . "'");

            /* echo "select c_agama, n_agama from sdm.tr_agama 
              where c_agama  = '".$masukan['c_agama']."'"; */
            $jmlResult = count($result);
            $data = array(
                "c_cuti" => (string) $result->c_cuti,
                "n_cuti" => (string) $result->n_cuti,
                "max_lama_cuti" => (string) $result->max_lama_cuti
            );
            return $data;
        } catch (Exception $e) {
            echo $e->getMessage() . '<br>';
            return 'Data tidak ada <br>';
        }
    }

    public function ubahcuti(array $dataMasukan) {
        $registry = Zend_Registry::getInstance();
        $db = $registry->get('db');

        try {
            $db->beginTransaction();

            $n_cuti = $dataMasukan['n_cuti'];
            $c_cuti = $dataMasukan['c_cuti'];
            $i_entry = $dataMasukan['i_entry'];
            $max_lama_cuti =$dataMasukan['max_lama_cuti'];

            $where[] = "c_cuti = '" . $c_cuti . "'";
            $paramInput = array(
                "c_cuti" => $c_cuti,
                "n_cuti" => $n_cuti,
                "i_entry" => $i_entry,
                "d_entry" => date('Y-m-d'),
                "max_lama_cuti" => $max_lama_cuti
            );
            //var_dump($paramInput);
            $db->update('sdm.tr_cuti_pegawai', $paramInput, $where);
            $db->commit();

            return "sukses";
        } catch (Exception $e) {
            $db->rollBack();
            $errmsgArr = explode(":", $e->getMessage());

            $errMsg = $errmsgArr[0];

            if ($errMsg == "SQLSTATE[23000]") {
                return "gagal.Data Sudah Ada.";
            } else {
                //return "gagal";
                return $e->getMessage();
            }
        }
    }

    public function hapuscuti(array $dataMasukan) {
        $registry = Zend_Registry::getInstance();
        $db = $registry->get('db');

        try {
            $db->beginTransaction();

            $c_cuti = $dataMasukan['c_cuti'];
            $where[] = "c_cuti = '" . $c_cuti . "'";

            //var_dump($where);
            $db->delete('sdm.tr_cuti_pegawai', $where);
            $db->commit();

            return "sukses";
        } catch (Exception $e) {
            $db->rollBack();
            $errmsgArr = explode(":", $e->getMessage());

            $errMsg = $errmsgArr[0];

            if ($errMsg == "SQLSTATE[23000]") {
                return "gagal.Data Sudah Ada.";
            } else {
                //return "gagal";
                return $e->getMessage();
            }
        }
    }

}

?>
