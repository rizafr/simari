<?php

require_once 'Zend/Controller/Action.php';
require_once "service/sdm/excel_reader2.php";
require_once "service/sdm/Sdm_Importdata_Service.php";
require_once "service/sdm/Sdm_Pangkat_Service.php";
require_once "service/sdm/Sdm_Jabatan_Service.php";
require_once "service/sdm/Sdm_Pendidikan_Service.php";
require_once "service/sdm/Sdm_Pegawai_Service.php";

class Sdmmodule_UploadexcelController extends Zend_Controller_Action {

    public function init() {
        $this->_helper->layout->setLayout('target-column');
        $registry = Zend_Registry::getInstance();
        $this->view->basePath = $registry->get('basepath');
        $this->view->leftMenu = $registry->get('leftMenu');
        $this->view->dPath = $registry->get('photoPath');

        $this->pegawai_serv = Sdm_Importdata_Service::getInstance();
        $this->pangkat_serv = Sdm_Pangkat_Service::getInstance();
        $this->jabatan_serv = Sdm_Jabatan_Service::getInstance();
        $this->pendidikan_serv = Sdm_Pendidikan_Service::getInstance();
        $this->peng_serv = Sdm_Pegawai_Service::getInstance();
        $ssologin = new Zend_Session_Namespace('ssologin');
        $this->view->c_lokasi_unitkerja = $ssologin->c_lokasi_unitkerja;
        $this->view->c_eselon_i = $ssologin->c_eselon_i;
    }

    public function indexAction() {
        
    }

    public function uploadexceljsAction() {
        header('content-type : text/javascript');
        $this->render('uploadexceljs');
    }

    public function uploadexcelAction() {
        
    }

    public function prosesuploadexcelAction() {
        
        $data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
        
        $baris = $data->rowcount($sheet_index=0);
        $hitBrs = 0;
        $datagagal = 0;
//	echo "baris".$baris;
        
        $lokasi_unitkerja = $data->val(1, 2);
        $c_lokasi_unitkerja = substr($data->val(1, 3), 0, 1);
        
        $unit_kerja = explode("~", $data->val(1, 3));
        $c_eselon_i = trim($unit_kerja[1]);
        $c_eselon_ii = trim($unit_kerja[2]);
        $c_eselon_iii = trim($unit_kerja[3]);
        $c_eselon_iv = trim($unit_kerja[4]);
        $c_eselon_v = trim($unit_kerja[5]);
        
        for ($i=5;$i<=$baris;$i++) {
            
            $i_peg_nip = trim($data->val($i, 2)); // nip lama
            $i_peg_nip_new = trim($data->val($i, 3)); // nip baru
            $n_peg = $data->val($i, 4); // nama
            //echo $i_peg_nip;
            $c_status_kepegawaian = ""; // status kepegawaian
            if ($data->val($i, 5) != "") {
                $c_status_kepegawaian = $this->getData($data->val($i, 5));
            }
            
            $c_pend = ""; //  pendidikan
            if ($data->val($i, 6) != "") {
                $c_pend = $this->getData($data->val($i, 6));
            }
            
            $d_pend_akhir = NULL;
            if ($data->val($i, 7) != "") {
                $d_pend_akhir = $data->val($i, 7);
            }
            
            $a_peg_kota_lahir = "";
            if ($data->val($i, 8) != "") {
                $a_peg_kota_lahir = $this->getData($data->val($i, 8));
            }
            
            $c_peg_propinsi_lahir = "";
            if ($a_peg_kota_lahir != ""):
                $c_peg_propinsi_lahir = substr($a_peg_kota_lahir, 0, 2); // Tambahan Dez
            endif;
            
            $d_peg_lahir = NULL;
            if ($data->val($i, 9) != "") {
                $pecahDL = explode("/",$data->val($i, 9));
                $d_peg_lahir = $pecahDL[2]."-".$pecahDL[1]."-".$pecahDL[0];
            }
            
            $c_peg_jeniskelamin = "";
            if ($data->val($i, 10) != ""):
                $c_peg_jeniskelamin = trim($data->val($i, 10));
                if ($c_peg_jeniskelamin == 'Laki-Laki') {
                    $c_peg_jeniskelamin = 'L';
                }
                if ($c_peg_jeniskelamin == 'Perempuan') {
                    $c_peg_jeniskelamin = 'P';
                }
            endif;
            
            $c_agama = "";
            if ($data->val($i, 11) != "") {
                $c_agama = $this->getData($data->val($i, 11));
            }
            
            $c_peg_statusnikah = "";
            if ($data->val($i, 12) != ""):
                $c_peg_statusnikah = $data->val($i, 12);
                if ($c_peg_statusnikah == "Kawin") {
                    $c_peg_statusnikah = "K";
                }
                if ($c_peg_statusnikah == "Belum Kawin") {
                    $c_peg_statusnikah = "B";
                }
                if ($c_peg_statusnikah == "Janda") {
                    $c_peg_statusnikah = "J";
                }
                if ($c_peg_statusnikah == "Duda") {
                    $c_peg_statusnikah = "D";
                }
            endif;
            
            $d_tmt_cpns = "";
            if ($data->val($i, 13) != "") {
                $pecahDL = explode("/",$data->val($i, 13));
                $d_tmt_cpns = $pecahDL[2]."-".$pecahDL[1]."-".$pecahDL[0];
            }
            
            $c_jenis_naik = "";
            if ($data->val($i, 14) != "") {
                $c_jenis_naik = $this->getData($data->val($i, 14));
            }
            
            $c_golongan = "";
            if ($data->val($i, 15) != ""):
                if ($c_golongan) {
                    $c_golongan = $this->getData($data->val($i, 15));
                }
                /*
                if ($c_golongan == "") {
                    $c_peg_status = '2CP';
                }
                if ($c_golongan != "") {
                    $c_peg_status = '3PN';
                }*/
            endif;
            
            $d_tmt_golongan = NULL;
            if ($data->val($i, 16) != "") {
                $pecahDL = explode("/",$data->val($i, 16));
                $d_tmt_golongan = $pecahDL[2]."-".$pecahDL[1]."-".$pecahDL[0];
            }
            //yg kosong
            //	$d_sk_golongan= $data->val($i, 17);
            //yg kosong
            //	$i_sk_golongan= trim($data->val($i, 18));
            $c_eselon = "";
            if ($data->val($i, 17) != "") {
                $c_eselon = $this->getDataUnderScore($data->val($i, 17));
            }
            
            $c_jabatan = "";
            if ($data->val($i, 18) != "") {
                $c_jabatan = $this->getData($data->val($i, 18));
            }
            
            //kosong
            $d_mulai_jabat = NULL;
            if ($data->val($i, 19) != "") {
                $pecahDL = explode("/",$data->val($i, 19));
                $d_mulai_jabat = $pecahDL[2]."-".$pecahDL[1]."-".$pecahDL[0];
            }
            //kosong
            //kosong

            $cari = " and (i_peg_nip ='$i_peg_nip' or i_peg_nip_new='$i_peg_nip_new')";
            $i_peg_lama = $this->pegawai_serv->getNipPegawai($cari);
            //$checkdata1 = $checkdata['i_peg_nip'];
            
            //exit;
            if (empty($i_peg_lama)){
                $pgeLama = $i_peg_nip_new;
            } else {
                $pgeLama = $i_peg_lama;
            }
            
            if ($i_peg_lama == "") {
//			echo $d_tmt_golongan;

                /*  	echo "$i_peg_nip
                  $i_peg_nip_new
                  $n_peg
                  $c_status_kepegawaian
                  $c_pend
                  $d_pend_akhir
                  $a_peg_kota_lahir
                  $d_peg_lahir
                  $c_peg_jeniskelamin
                  $c_agama
                  $c_peg_statusnikah
                  $d_tmt_cpns
                  $c_jenis_naik
                  $c_golongan
                  $d_tmt_golongan
                  $c_eselon
                  $c_jabatan
                  $d_mulai_jabat"; */
                
                $MaintainData = array("i_peg_nip" => $pgeLama,
                    "i_peg_nip_new" => $i_peg_nip_new,
                    "n_peg" => $n_peg,
                    "c_status_kepegawaian" => $c_status_kepegawaian,
                    "c_pend" => $c_pend,
                    "d_pend_akhir" => $d_pend_akhir,
                    "a_peg_kota_lahir" => $a_peg_kota_lahir,
                    "d_peg_lahir" => $d_peg_lahir,
                    "c_peg_jeniskelamin" => $c_peg_jeniskelamin,
                    "c_agama" => $c_agama,
                    "c_peg_statusnikah" => $c_peg_statusnikah,
                    "d_tmt_cpns" => $d_tmt_cpns,
                    "c_jenis_naik" => $c_jenis_naik,
                    "c_golongan" => $c_golongan,
                    "d_tmt_golongan" => $d_tmt_golongan,
                    "c_eselon" => $c_eselon,
                    "c_jabatan" => $c_jabatan,
                    "d_mulai_jabat" => $d_mulai_jabat,
                    "c_stat_aktivasi" => "A",
                    "i_entry" => "import",
                    "d_entry" => date("Y-m-d"),
                    "c_lokasi_unitkerja" => $c_lokasi_unitkerja,
                    "c_eselon_i" => $c_eselon_i,
                    "c_eselon_ii" => $c_eselon_ii,
                    "c_eselon_iii" => $c_eselon_iii,
                    "c_eselon_iv" => $c_eselon_iv,
                    "c_eselon_v" => $c_eselon_v,
                    //"c_peg_status" => $c_peg_status,
                    "c_peg_propinsi_lahir" => $c_peg_propinsi_lahir);
                
                if ($i_peg_nip) {
                    $queryInsert = $this->pegawai_serv->maintainData($MaintainData);
                    if ($queryInsert){
                        $dataPangkat = array(
                            "i_peg_nip" => $pgeLama,
                            "c_golongan" => $_golongan,
                            "d_tmt_golongan" => $d_tmt_golongan,
                            "c_jenis_naik" => "16",
                            "i_entry" => "online",
                            "d_entry" => date("Y-m-d")
                        );
                        $insertPangkat = $this->pangkat_serv->maintainData($dataPangkat,"insert");
                        if ($insertPangkat){
                            $dataJabatan = array(
                                "i_peg_nip" => $pgeLama,
                                "c_eselon" => $c_eselon,
                                "c_jabatan" => $c_jabatan,
                                "d_mulai_jabat" => $d_mulai_jabat,
                                "c_lokasi_unitkerja" => $c_lokasi_unitkerja,
                                "c_eselon_i" => $c_eselon_i,
                                "c_eselon_ii" => $c_eselon_ii,
                                "c_eselon_iii" => $c_eselon_iii,
                                "c_eselon_iv" => $c_eselon_iv,
                                "c_eselon_v" => $c_eselon_v,
                                "i_entry" => "online",
                                "d_entry" => date("Y-m-d")
                            );
                            $insertJabatan = $this->jabatan_serv->maintainData($dataJabatan,'insert');                            
                            if ($insertJabatan){
                                $dataPendidikan = array(
                                    "i_peg_nip" => $pgeLama,
                                    "c_pend" => $c_pend, // 
                                    "d_pend_akhir" => $d_pend_akhir,
                                    "i_entry" => "online",
                                    "d_entry" => date("Y-m-d")
                                );
                                $insertPendidikan = $this->pendidikan_serv->maintainData($dataPendidikan,"insert");
                            }
                        }
                    }
                    $hasil = "sukses";
                } else {
                    $hasil = 'gagal';
                }
            } else {
                $MaintainData = array(
                    "i_peg_nipb" => $i_peg_lama,
                    "i_peg_nip_new" => $i_peg_nip_new,
                    "n_peg" => $n_peg,
                    "c_status_kepegawaian" => $c_status_kepegawaian,
                    "c_pend" => $c_pend,
                    "d_pend_akhir" => $d_pend_akhir,
                    "a_peg_kota_lahir" => $a_peg_kota_lahir,
                    "d_peg_lahir" => $d_peg_lahir,
                    "c_peg_jeniskelamin" => $c_peg_jeniskelamin,
                    "c_agama" => $c_agama,
                    "c_peg_statusnikah" => $c_peg_statusnikah,
                    "d_tmt_cpns" => $d_tmt_cpns,
                    "c_jenis_naik" => $c_jenis_naik,
                    "c_golongan" => $c_golongan,
                    "d_tmt_golongan" => $d_tmt_golongan,
                    "c_eselon" => $c_eselon,
                    "c_jabatan" => $c_jabatan,
                    "d_mulai_jabat" => $d_mulai_jabat,
                    "c_stat_aktivasi" => "A",
                    "i_entry" => "import",
                    "d_entry" => date("Y-m-d"),
                    "c_lokasi_unitkerja" => $c_lokasi_unitkerja,
                    "c_eselon_i" => $c_eselon_i,
                    "c_eselon_ii" => $c_eselon_ii,
                    "c_eselon_iii" => $c_eselon_iii,
                    "c_eselon_iv" => $c_eselon_iv,
                    "c_eselon_v" => $c_eselon_v,
                    //"c_peg_status" => $c_peg_status,
                    "c_peg_propinsi_lahir" => $c_peg_propinsi_lahir);
                
                $query = $this->peng_serv->maintainData($MaintainData, "update");
                
                if ($query){
                    $hasil = "sukses";
                } else {
                    $hasil = "gagal";
                }
            } // akhir loop
            if (!$i_peg_nip) {
                $hitBrs++;
                if ($hitBrs == 5) {
                    $i = $baris;
                }
                $hasil = 'gagal';
                $gagalx++;
            }

            if ($hasil == 'sukses')
                $sukses++;
            else
                $gagal++;
        }
        
        $gagal = $gagal * 1 - $gagalx * 1;
        echo "<table> <tr>";
        echo "<td><h2>Proses import data selesai.</h2></td>";
        echo "<tr><td>Jumlah data yang sukses diimport<br></td> <td>:</td><td>" . $sukses . "</td><tr>";
        echo "<tr><td>Jumlah data yang gagal diimport, karena nip lama atau nip baru sudah ada</td> <td>:</td><td>" . $gagal . "</td><tr></table>";

        $this->render('uploadexcel');
    }

    /* download excel kantor pusat MA */
    public function downloadexcelkantorpusatmaAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $dfolder = $this->view->dPath . "/sdm/templateexcel/templateKantorPusat.xls";
        $ndokumen = file_get_contents($dfolder);
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=import_pegawai_kantor_pusat_ma.xls");
        echo $ndokumen;
    }
    
    /* download excel kantor pengadilan dibawah ditjen umum */
    public function downloadexcelpengadilanditjenumumAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $dfolder = $this->view->dPath . "/sdm/templateexcel/templatePengadilanDitjenUmum.xls";
        $ndokumen = file_get_contents($dfolder);
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=import_pegawai_pengadilan_ditjen_umum.xls");
        echo $ndokumen;
    }
    
    /* download excel kantor pengadilan dibawah ditjen agama */
    public function downloadexcelpengadilanditjenagamaAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $dfolder = $this->view->dPath . "/sdm/templateexcel/templatePengadilanDitjenAgama.xls";
        $ndokumen = file_get_contents($dfolder);
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=import_pegawai_pengadilan_ditjen_agama.xls");
        echo $ndokumen;
    }
    
    /* download excel kantor pengadilan dibawah ditjen agama */
    public function downloadexcelpengadilanditjentuAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $dfolder = $this->view->dPath . "/sdm/templateexcel/templatePengadilanDitjenTU.xls";
        $ndokumen = file_get_contents($dfolder);
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=import_pegawai_pengadilan_ditjen_tu.xls");
        echo $ndokumen;
    }

    public function downloadpdfAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $dfolder = $this->view->dPath . "/sdm/templateexcel/manual.doc";
        $ndokumen = file_get_contents($dfolder);
        header("Content-Type: application/doc");
        header("Content-Disposition: attachment; filename=user_manual.doc");
        echo $ndokumen;
    }

    function getData($string) {
        $expesl1 = explode("~", $string);
        $data = $expesl1[0];

        return $data;
    }
    
    function getDataUnderScore($string) {
        $expesl1 = explode("_", $string);
        $data = $expesl1[0];

        return $data;
    }

    function checkPanjang($string) {
        
    }

}

?>