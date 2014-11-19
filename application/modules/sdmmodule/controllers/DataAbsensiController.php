<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/sdm/sdm_refferensi_Service.php";
require_once "service/sdm/Sdm_Absensi_Service.php";
require_once "service/sdm/Sdm_Absensimesin_Service.php";
require_once "service/sdm/Sdm_Absensihitung_Service.php";

class Sdmmodule_DataabsensiController extends Zend_Controller_Action {
		
    public function init() {

		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->absensi_serv = Sdm_Absensi_Service::getInstance();
		
		$this->absensi_serv = Sdm_Absensi_Service::getInstance();
		$this->view->absensi_serv = Sdm_Absensi_Service::getInstance();
		$this->reff_serv = sdm_refferensi_Service::getInstance();
		$this->view->reff_serv = sdm_refferensi_Service::getInstance();
		$this->absensimsn_serv = Sdm_Absensimesin_Service::getInstance();
		$this->view->absensimsn_serv = Sdm_Absensimesin_Service::getInstance();
		$this->absensihtg_serv = Sdm_Absensihitung_Service::getInstance();
		$this->view->absensihtg_serv = Sdm_Absensihitung_Service::getInstance();
		$this->dataPerPage = $registry->get('dataPerPage');
		
		$ssologin = new Zend_Session_Namespace('ssologin');
		$this->view->c_lokasi_unitkerja=$ssologin->c_lokasi_unitkerja;
		$this->view->ceseloni=$ssologin->c_eselon_i;	
		$this->view->userid=$ssologin->userid;
		$this->view->c_izin=$ssologin->c_izin[0]['c_izin'];		
		$this->view->wewenang=$ssologin->wewenang;
              $this->view->i_peg_nip = $ssologin->i_peg_nip_new;
	       $this->bc_eselon_i = $ssologin->c_eselon_i;
	       $this->bc_eselon_ii = $ssologin->c_eselon_ii;
	       $this->bc_eselon_iii = $ssologin->c_eselon_iii;
	       $this->bc_eselon_iv = $ssologin->c_eselon_iv;
	       $this->bc_eselon_v = $ssologin->c_eselon_v;
	       $this->nc_eselon_i = $ssologin->n_eselon_i;
	       $this->nc_eselon_ii = $ssologin->n_eselon_ii;
	       $this->nc_eselon_iii = $ssologin->n_eselon_iii;
	       $this->nc_eselon_iv = $ssologin->n_eselon_iv;
	       $this->nc_eselon_v = $ssologin->n_eselon_v;
//echo "i_peg_nip : ".$ssologin->i_peg_nip;

		$this->gi_peg_nip  		= $ssologin->i_peg_nip;
		$this->gi_peg_nip_new	= $ssologin->i_peg_nip_new;
		$this->gn_peg  			= $ssologin->n_peg;
		$this->view->sektoral			= $ssologin->arrayc_sektoral[1]; 
		$this->view->wewenang			= $ssologin->arrayc_wewenang[1]; 
		if($this->view->wewenang == 'O'){$this->view->c_izin='000002';}
		//$this->view->c_izin=$ssologin->c_izin[0]['c_izin'];	
		//if ($this->view->c_izin=='000002' || $this->view->c_izin=='000003'){$this->view->jdl="Kelola ";}
		//else{$this->view->jdl="Melihat ";}		
             //echo "c_lokasi_unitkerja : ".$this->view->c_lokasi_unitkerja;
    }
    public function indexAction() {
	   
    }
	public function dataabsensijsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('dataabsensijs');
    }
	public function lstdataabsensijsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('lstdataabsensijs');
    }
	public function dataabsensimesinjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('dataabsensimesinjs');
    }
	public function dataabsensi1jsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('dataabsensi1js');
    }

       public function insertabsensikuAction()
       {
           $this->_helper->viewRenderer->setNoRender(true);
	    $c_lokasi_unitkerja = $_REQUEST['kd_lokasi'];
	    $c_eselon_i = $_REQUEST['c_eselon_i'];
	    $c_eselon_ii = $_REQUEST['c_eselon_ii'];
	    $c_eselon_iii = $_REQUEST['c_eselon_iii'];
	    $c_eselon_iv = $_REQUEST['c_eselon_iv'];
           $bulan = $_REQUEST['bulan'];
           $tahun = $_REQUEST['tahun'];
           if ((!$bulan) || ($bulan == 'undefined')) { $bulan = date('m'); }
           if ((!$tahun) || ($tahun == 'undefined')) { $tahun = date('Y'); }

           $harikerja = $this->absensihtg_serv->getHarikerjalst($bulan,$tahun);
           $jmlharikerja = count($harikerja);


           $wheredata = '';
           if (trim($c_lokasi_unitkerja)=='1')
           {
                 $c_eselon_iArr = explode(' ',$c_eselon_i); 
                 $c_eselon_iiArr = explode(' ',$c_eselon_ii); 
                 $c_eselon_iiiArr = explode(' ',$c_eselon_iii); 
                 $c_eselon_ivArr = explode(' ',$c_eselon_iv); 
                 if ($c_eselon_iArr[0]!="")
                 {
                      if ($wheredata=="") { $wheredata .= " c_eselon_i='".$c_eselon_iArr[0]."'"; }
                      else { $wheredata .= " and c_eselon_i='".$c_eselon_iArr[0]."'"; }
                 }
                 if ($c_eselon_iiArr[0]!="")
                 {
                      if ($wheredata=="") { $wheredata .= " c_eselon_ii='".$c_eselon_iiArr[0]."'"; }
                      else { $wheredata .= " and c_eselon_ii='".$c_eselon_iiArr[0]."'"; }
                 }
                 if ($c_eselon_iiiArr[0]!="")
                 {
                      if ($wheredata=="") { $wheredata .= " c_eselon_iii='".$c_eselon_iiiArr[0]."'"; }
                      else { $wheredata .= " and c_eselon_iii='".$c_eselon_iiiArr[0]."'"; }
                 }
                 if ($c_eselon_ivArr[0]!="")
                 {
                      if ($wheredata=="") { $wheredata .= " c_eselon_iv='".$c_eselon_ivArr[0]."'"; }
                      else { $wheredata .= " and c_eselon_iv='".$c_eselon_ivArr[0]."'"; }
                 }
           }
           else
           {
                 $c_eselon_iArr = explode(';',$c_eselon_i); 
                 $c_eselon_iiArr = explode(';',$c_eselon_ii); 
                 $c_eselon_iiiArr = explode(';',$c_eselon_iii); 
                 $c_eselon_ivArr = explode(';',$c_eselon_iv); 
                 if ($c_eselon_iArr[0]!='') { $wheredata .= " c_eselon_i='".$c_eselon_iArr[0]."'"; }
                 if ($c_eselon_iiArr[0]!='') 
                 {
                    if ($wheredata!='') { $wheredata .= " and "; }
                    $wheredata .= " c_eselon_ii='".$c_eselon_iiArr[0]."' and c_parent='".$c_eselon_iiArr[1]."'"; 
                 }
                 if ($c_eselon_iiiArr[0]!='') 
                 {
                    if ($wheredata!='') { $wheredata .= " and "; }
                    $wheredata .= " c_eselon_ii='".$c_eselon_iiiArr[0]."' and c_child='".$c_eselon_iiiArr[1]."' and c_satker='".$c_eselon_iiiArr[2]."'"; 
                 }
                 if ($c_eselon_ivArr[0]!='') 
                 {
                    if ($wheredata!='') { $wheredata .= " and "; }
                    $wheredata .= " c_eselon_iv='".$c_eselon_ivArr[0]."' and c_eselon_iii='00'";
                 }
           }
  
           //echo "jml data : ".$jmlharikerja;
           echo "<table>";
           for ($i=0; $i<$jmlharikerja; $i++)
           {
              $databsensipegawai = $this->absensihtg_serv->getDataAbsenPegawai($wheredata,'');
              $databsensipegawaijml = count($databsensipegawai);
              //echo "jml databsensipegawai : ".$databsensipegawaijml."<br>";
              $tgl = $harikerja[$i]['d_tgl_kerja'];
              //echo "tgl : ".$tgl."<br>";
              for ($j=0; $j<$databsensipegawaijml; $j++)
              {
                 $terminal = $databsensipegawai[$j]['c_terminal'];
                 $i_peg_nip = $databsensipegawai[$j]['i_peg_nip'];
                 $i_peg_nip_new = $databsensipegawai[$j]['i_peg_nipfg'];
                 $i_jamin = $this->absensihtg_serv->getAbsnMasukPegawai($terminal,$i_peg_nip_new,$tgl);
                 if ((!$i_jamin) || ($i_jamin!='')) { $i_jamin='00:00:00'; }
                 $i_jamout = $this->absensihtg_serv->getAbsnPulangPegawai($terminal,$i_peg_nip_new,$tgl);
                 if ((!$i_jamout) || ($i_jamout!='')) { $i_jamout='00:00:00'; }
	          $absensi_prm = array("i_peg_nip"  	=>$i_peg_nip,
                                      "i_peg_nip_new"  =>$i_peg_nip_new,
	                               "d_tgl"  	       =>$tgl,
				          "i_jamin"         =>$i_jamin,  
				          "i_jamout"	       =>$i_jamout,
				          "i_status" 	=>'1');
                 echo "<tr>";
                 echo "<td>".$j."</td>";
                 echo "<td>".$i_peg_nip."</td>";
                 echo "<td>".$i_peg_nip_new."</td>";
                 echo "<td>".$harikerja[$i]['d_tgl_kerja']."</td>";
                 echo "<td>".$i_jamin."</td>";
                 echo "<td>".$i_jamout."</td>";
                 echo "</tr>";
                 $rtkode = $this->absensihtg_serv->insertAbsensi($absensi_prm);
              }
           }
           echo "</table>";
       }
       public function insertabsensijinAction()
       {
           $this->_helper->viewRenderer->setNoRender(true);
	    $kd_lokasi = $_REQUEST['kd_lokasi'];
	    $c_eselon_i = $_REQUEST['eseloni'];
	    $c_eselon_ii = $_REQUEST['eselonii'];
	    $c_eselon_iii = $_REQUEST['eseloniii'];
	    $c_eselon_iv = $_REQUEST['eseloniv'];
	    $nippegawai = $_REQUEST['i_peg_nip'];
           $tahunabsen = $_REQUEST['tahunabsen'];
           $bulanabsen = $_REQUEST['bulanabsen'];

           if ($kd_lokasi=='1')
           {
              $c_eselon_iArr = explode(' ',$c_eselon_i); 
              $c_eselon_iiArr = explode(' ',$c_eselon_ii); 
              $c_eselon_iiiArr = explode(' ',$c_eselon_iii); 
              $c_eselon_ivArr = explode(' ',$c_eselon_iv); 
              if ($c_eselon_iArr[0]!="")
              {
                   if ($wheredata=="") { $wheredata .= " c_eselon_i='".$c_eselon_iArr[0]."'"; }
                   else { $wheredata .= " and c_eselon_i='".$c_eselon_iArr[0]."'"; }
              }
              if ($c_eselon_iiArr[0]!="")
              {
                   if ($wheredata=="") { $wheredata .= " c_eselon_ii='".$c_eselon_iiArr[0]."'"; }
                   else { $wheredata .= " and c_eselon_ii='".$c_eselon_iiArr[0]."'"; }
              }
              if ($c_eselon_iiiArr[0]!="")
              {
                   if ($wheredata=="") { $wheredata .= " c_eselon_iii='".$c_eselon_iiiArr[0]."'"; }
                   else { $wheredata .= " and c_eselon_iii='".$c_eselon_iiiArr[0]."'"; }
              }
              if ($c_eselon_ivArr[0]!="")
              {
                   if ($wheredata=="") { $wheredata .= " c_eselon_iv='".$c_eselon_ivArr[0]."'"; }
                   else { $wheredata .= " and c_eselon_iv='".$c_eselon_ivArr[0]."'"; }
              }
           }
           else
           {
              $c_eselon_iArr = explode(';',$c_eselon_i); 
              $c_eselon_iiArr = explode(';',$c_eselon_ii); 
              $c_eselon_iiiArr = explode(';',$c_eselon_iii); 
              $c_eselon_ivArr = explode(';',$c_eselon_iv); 
              if ($c_eselon_iArr[0]!='') { $wheredata .= " c_eselon_i='".$c_eselon_iArr[0]."'"; }
              if ($c_eselon_iiArr[0]!='') 
              {
                 if ($wheredata!='') { $wheredata .= " and "; }
                 $wheredata .= " c_eselon_ii='".$c_eselon_iiArr[0]."' and c_parent='".$c_eselon_iiArr[1]."'"; 
              }
              if ($c_eselon_iiiArr[0]!='') 
              {
                 if ($wheredata!='') { $wheredata .= " and "; }
                 $wheredata .= " c_eselon_ii='".$c_eselon_iiiArr[0]."' and c_child='".$c_eselon_iiiArr[1]."' and c_satker='".$c_eselon_iiiArr[2]."'"; 
              }
              if ($c_eselon_ivArr[0]!='') 
              {
                 if ($wheredata!='') { $wheredata .= " and "; }
                 $wheredata .= " c_eselon_iv='".$c_eselon_ivArr[0]."' and c_eselon_iii='00'";
              }
           }
           $lstkodeIjin = $this->absensimsn_serv->getKodeIjinLst();
           $jmlRslt = count($lstkodeIjin);
           for ($i=0; $i<$jmlRslt; $i++)
           {
               $absensilst[$lstkodeIjin[$i]['c_ijin']] = $lstkodeIjin[$i]['n_ijin'];
           }
           $pegawaidatalst = $this->absensi_serv->getDataAbsenPegawai($wheredata, '');
           $listharikerja = $this->absensimsn_serv->getHariKerjalst($tahunabsen.'-'.$bulanabsen); 
           $jmlpegawaidatalst = count($pegawaidatalst);
           $jmllistharikerja = count($listharikerja);
           for ($i=0; $i<$jmlpegawaidatalst; $i++)
           {
               $i_peg_nip = $pegawaidatalst[$i]['i_peg_nip'];
               $c_terminal = $pegawaidatalst[$i]['c_terminal'];
               $i_peg_nipfg = $pegawaidatalst[$i]['i_peg_nipfg'];
               //echo "i_peg_nipfg : ".$i_peg_nipfg."<br>";
               $this->absensimsn_serv->delIjinGroup($i_peg_nip,$tahunabsen.'-'.$bulanabsen);
               for ($j=0; $j<$jmllistharikerja; $j++)
               {
                   $d_peg_ijin = $listharikerja[$j]['d_tgl_kerja'];
                   $d_peg_ijinArr = explode('-',$d_peg_ijin);
                   $jammasukok = $listharikerja[$j]['d_jamkerja_mulai'];
                   $jampulangok = $listharikerja[$j]['d_jamkerja_selesai'];
		     $jammasuk = $this->absensi_serv->getAbsnMasukPegawai($c_terminal,$i_peg_nipfg,$d_peg_ijinArr[0].$d_peg_ijinArr[1].$d_peg_ijinArr[2]);
                   //echo "masuk : ".$jammasuk."::"; 
		     $jamkeluar = $this->absensi_serv->getAbsnPulangPegawai($c_terminal,$i_peg_nipfg,$d_peg_ijinArr[0].$d_peg_ijinArr[1].$d_peg_ijinArr[2]);  
                   //echo "keluar : ".$jammasuk."::"; 
                   if (($jammasuk>$jammasukok) || ($jammasuk==''))
                   { 
                      if ($jamkeluar<$jampulangok) 
                      { $dataijin = "LE"; }
                      else
                      { $dataijin = "LV"; }
                   }
                   else
                   { 
                      if ($jamkeluar<$jampulangok) 
                      { $dataijin = "VE"; }
                      else
                      { $dataijin = "V"; }
                   }

                   if (($jammasuk=='') && ($jamkeluar=='')) { $dataijin = "TK"; }
                   $suratijin = "";
                   if ($dataijin == "V")
                   {
                      $jammasukok = $jammasuk;
                      $jampulangok = $jamkeluar;
                   }
	            $absensi_prm = array("i_peg_nip" 		=> $i_peg_nip,
	                                 "d_peg_ijin"  		=>$d_peg_ijin,
	                                 "d_jam_mulai"  	=>$jammasukok,
	                                 "d_jam_selesai"  	=>$jampulangok,
				            "c_ijin"              =>$dataijin,
				            "i_no_surat_ijin"	=>$suratijin,
				            "i_entry"       	=>$this->view->userid,
				            "d_entry"       	=>date("Y-m-d"));
                   $prssimpan = $this->absensimsn_serv->insertIjin($absensi_prm);
               }
           }
       }
       public function deldataabsensimesinAction()
       {
           $this->_helper->viewRenderer->setNoRender(true);
           $lokserver = $_REQUEST['lokserver'];
           $nip = $_REQUEST['nip'];
           $tgl = $_REQUEST['tgl'];
           $jam = $_REQUEST['jam'];
           $this->absensimsn_serv->delAbsensiFinger($lokserver,$nip,$tgl,$jam);
       }
      
       public function insertabsensiAction()
       {
           //$this->_helper->viewRenderer->setNoRender(true);
	    $c_lokasi_unitkerja = $_REQUEST['kd_lokasi'];
	    $c_eselon_i = $_REQUEST['c_eselon_i'];
	    $c_eselon_ii = $_REQUEST['c_eselon_ii'];
	    $c_eselon_iii = $_REQUEST['c_eselon_iii'];
	    $c_eselon_iv = $_REQUEST['c_eselon_iv'];
           $bulan = $_REQUEST['bulan'];
           $tahun = $_REQUEST['tahun'];
           if ((!$bulan) || ($bulan == 'undefined')) { $bulan = date('m'); }
           if ((!$tahun) || ($tahun == 'undefined')) { $tahun = date('Y'); }
	    $this->view->lokasiList = $this->reff_serv->getLokasi("");
           if ((!$c_lokasi_unitkerja) || ($c_lokasi_unitkerja=='undefined')) 
           {   
              $c_lokasi_unitkerja=$this->view->c_lokasi_unitkerja;  
              $c_eselon_i =  $this->view->ceseloni;
           }   
/*
	    if ($c_lokasi_unitkerja=='1'){
		$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	    }
	    else{
		$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	    }
*/
           $wheredata = " c_lokasi_unitkerja='".$c_lokasi_unitkerja."' and c_eselon_i='".$ceseloni.
           "' and to_char(d_peg_absensi,'yyyy')='".$tahun."' and to_char(d_peg_absensi,'mm')='".$bulan."'";

           $this->view->blnSekarang = $bulan;
           $this->view->thnSekarang = $tahun;
           $this->view->c_lokasi_unitkerja = trim($c_lokasi_unitkerja);

       }
	public function viewabsensipegAction()
	{ 
	   $c_lokasi_unitkerja = $_REQUEST['kd_lokasi'];
	   $c_eselon_i = $_REQUEST['c_eselon_i'];
	   $c_eselon_ii = $_REQUEST['c_eselon_ii'];
	   $c_eselon_iii = $_REQUEST['c_eselon_iii'];
	   $c_eselon_iv = $_REQUEST['c_eselon_iv'];
          $tahunabsen = $_REQUEST['th'];
          $bulanabsen = $_REQUEST['bl'];
          $tahunabsen2 = $_REQUEST['th2'];
          $bulanabsen2 = $_REQUEST['bl2'];
          $i_peg_nip = $_REQUEST['v1'];
          $n_peg = $_REQUEST['v2'];
	   $this->view->i_peg_nip = $i_peg_nip;
	   $this->view->n_peg = $n_peg;

	   $this->view->c_eselon_i = $c_eselon_i;
	   $this->view->c_eselon_ii = $c_eselon_ii;
	   $this->view->c_eselon_iii = $c_eselon_iii;
	   $this->view->c_eselon_iv = $c_eselon_iv;

           $c_eselon = $_REQUEST['param4']; 
           if ($c_eselon)
           { 
              $c_eselonArr = explode('::',$c_eselon);
              $c_eselon_i = $c_eselonArr[0];
              $c_eselon_ii = $c_eselonArr[1];
              $c_eselon_iii = $c_eselonArr[2];
              $c_eselon_iv = $c_eselonArr[3];
           }

	   if ((!$bulanabsen) || ($bulanabsen == 'undefined')) { $_REQUEST['param2']; }
	   else { $this->view->bulanabsen = $bulanabsen; }
	   if ((!$tahunabsen) || ($tahunabsen == 'undefined')) { $_REQUEST['param3']; }
	   else { $this->view->tahunabsen = $tahunabsen; }

	   if ((!$tahunabsen) || ($tahunabsen == 'undefined')) { $this->view->tahunabsen = date('Y'); }
	   else { $this->view->tahunabsen = $tahunabsen; }
	   if ((!$bulanabsen) || ($bulanabsen == 'undefined')) { $this->view->bulanabsen = date('m'); }
	   else { $this->view->bulanabsen = $bulanabsen; }
	   if ((!$tahunabsen2) || ($tahunabsen2 == 'undefined')) { $this->view->tahunabsen2 = date('Y'); }
	   else { $this->view->tahunabsen2 = $tahunabsen2; }
	   if ((!$bulanabsen2) || ($bulanabsen2 == 'undefined')) { $this->view->bulanabsen2 = date('m'); }
	   else { $this->view->bulanabsen2 = $bulanabsen2; }
           $wheredata = "";
		if ((!$c_lokasi_unitkerja) || ($c_lokasi_unitkerja== 'undefined')) { $c_lokasi_unitkerja= $_REQUEST['param1']; }
		$c_eselon_i=trim($this->view->ceseloni);
              if ($c_lokasi_unitkerja=='1')
              {
                 $c_eselon_iArr = explode(' ',$c_eselon_i); 
                 $c_eselon_iiArr = explode(' ',$c_eselon_ii); 
                 $c_eselon_iiiArr = explode(' ',$c_eselon_iii); 
                 $c_eselon_ivArr = explode(' ',$c_eselon_iv); 
                 if ($c_eselon_iArr[0]!="")
                 {
                      if ($wheredata=="") { $wheredata .= " c_eselon_i='".$c_eselon_iArr[0]."'"; }
                      else { $wheredata .= " and c_eselon_i='".$c_eselon_iArr[0]."'"; }
                 }
                 if ($c_eselon_iiArr[0]!="")
                 {
                      if ($wheredata=="") { $wheredata .= " c_eselon_ii='".$c_eselon_iiArr[0]."'"; }
                      else { $wheredata .= " and c_eselon_ii='".$c_eselon_iiArr[0]."'"; }
                 }
                 if ($c_eselon_iiiArr[0]!="")
                 {
                      if ($wheredata=="") { $wheredata .= " c_eselon_iii='".$c_eselon_iiiArr[0]."'"; }
                      else { $wheredata .= " and c_eselon_iii='".$c_eselon_iiiArr[0]."'"; }
                 }
                 if ($c_eselon_ivArr[0]!="")
                 {
                      if ($wheredata=="") { $wheredata .= " c_eselon_iv='".$c_eselon_ivArr[0]."'"; }
                      else { $wheredata .= " and c_eselon_iv='".$c_eselon_ivArr[0]."'"; }
                 }
              }
              else
              {
                 $c_eselon_iArr = explode(';',$c_eselon_i); 
                 $c_eselon_iiArr = explode(';',$c_eselon_ii); 
                 $c_eselon_iiiArr = explode(';',$c_eselon_iii); 
                 $c_eselon_ivArr = explode(';',$c_eselon_iv); 
                 if ($c_eselon_iArr[0]!='') 
                 { 
                    if ($wheredata!='') { $wheredata .= " and "; }
                    $wheredata .= " c_eselon_i='".$c_eselon_iArr[0]."'"; 
                 }
                 if ($c_eselon_iiArr[0]!='') 
                 {
                    if ($wheredata!='') { $wheredata .= " and "; }
                    $wheredata .= " c_eselon_ii='".$c_eselon_iiArr[0]."' and c_parent='".$c_eselon_iiArr[1]."'"; 
                 }
                 if ($c_eselon_iiiArr[0]!='') 
                 {
                    if ($wheredata!='') { $wheredata .= " and "; }
                    $wheredata .= " c_eselon_ii='".$c_eselon_iiiArr[0]."' and c_child='".$c_eselon_iiiArr[1]."' and c_satker='".$c_eselon_iiiArr[2]."'"; 
                 }
                 if ($c_eselon_ivArr[0]!='') 
                 {
                    if ($wheredata!='') { $wheredata .= " and "; }
                    $wheredata .= " c_eselon_iv='".$c_eselon_ivArr[0]."' and c_eselon_iii='00'";
                 }
		   //$wheredata .= " c_eselon_i='".$c_eselon_iArr[0]."' and c_eselon_ii='".$c_eselon_iiArr[0]."' and c_eselon_iii='".$c_eselon_iiiArr[0]."' and c_eselon_iv='".$c_eselon_ivArr[0]."' ";
              }

		if((!$currentPage) || ($currentPage == 'undefined')) { $currentPage = 1; }
		$this->view->numToDisplay = $this->dataPerPage;
		$this->view->c_lokasi_unitkerja=$c_lokasi_unitkerja;		
              $this->view->c_eselon = $c_eselon_i."::".$c_eselon_ii."::".$c_eselon_iii."::".$c_eselon_iv;

              $pegawaidatalstJml = $this->absensi_serv->getPegawaiDataList($wheredata,0,0,'');
	       $this->view->currentPage = $currentPage;   
		$this->view->pegawaidatalstJml = $pegawaidatalstJml;
              $this->view->pegawaidatalst = $this->absensi_serv->getPegawaiDataList($wheredata, $this->view->currentPage, $this->view->numToDisplay,'');
	}

	public function listdataabsensikuAction()
	{ 
	   $c_lokasi_unitkerja = $_REQUEST['c_lokasi_unitkerja'];
	   $c_eselon_i = $_REQUEST['c_eselon_i'];
	   $c_eselon_ii = $_REQUEST['c_eselon_ii'];
	   $c_eselon_iii = $_REQUEST['c_eselon_iii'];
	   $c_eselon_iv = $_REQUEST['c_eselon_iv'];
          $tahunabsen = $_REQUEST['tahunabsen'];
          $bulanabsen = $_REQUEST['bulanabsen'];
          $tahunabsen2 = $_REQUEST['tahunabsen2'];
          $bulanabsen2 = $_REQUEST['bulanabsen2'];
          $currentPage = $_REQUEST['currentPage'];

           $c_eselon = $_REQUEST['param4']; 
           if ($c_eselon)
           { 
              $c_eselonArr = explode('::',$c_eselon);
              if ((!$c_eselon_i) || ($c_eselon_i=='undefined')) { $c_eselon_i = $c_eselonArr[0]; }
              if ((!$c_eselon_ii) || ($c_eselon_ii=='undefined')) { $c_eselon_ii = $c_eselonArr[1]; }
              if ((!$c_eselon_iii) || ($c_eselon_iii=='undefined')) { $c_eselon_iii = $c_eselonArr[2]; }
              if ((!$c_eselon_iv) || ($c_eselon_iv=='undefined')) { $c_eselon_iv = $c_eselonArr[3]; }
           }
          $periodeabsen = $_REQUEST['param2'];
          $periodeabsenArr = explode('-',$periodeabsen);
          $periodeabsen2 = $_REQUEST['param3'];
          $periodeabsen2Arr = explode('-',$periodeabsen2);

          //$periodeabsen2 = $_REQUEST['param3'];
	   if ((!$bulanabsen) || ($bulanabsen == 'undefined')) { $this->view->bulanabsen = $periodeabsenArr[0]; }
	   else { $this->view->bulanabsen = $bulanabsen; }
	   if ((!$tahunabsen) || ($tahunabsen == 'undefined')) { $this->view->tahunabsen = $periodeabsenArr[1]; }
	   else { $this->view->tahunabsen = $tahunabsen; }
	   if ((!$bulanabsen2) || ($bulanabsen2 == 'undefined')) { $this->view->bulanabsen2 = $periodeabsen2Arr[0]; }
	   else { $this->view->bulanabsen2 = $bulanabsen2; }
	   if ((!$tahunabsen2) || ($tahunabsen2 == 'undefined')) { $this->view->tahunabsen2 = $periodeabsen2Arr[1]; }
	   else { $this->view->tahunabsen2 = $tahunabsen2; }

	   if ((!$tahunabsen) || ($tahunabsen == 'undefined')) { $this->view->tahunabsen = date('Y'); }
	   else { $this->view->tahunabsen = $tahunabsen; }
	   if ((!$bulanabsen) || ($bulanabsen == 'undefined')) { $this->view->bulanabsen = date('m'); }
	   else { $this->view->bulanabsen = $bulanabsen; }
	   if ((!$tahunabsen2) || ($tahunabsen2 == 'undefined')) { $this->view->tahunabsen2 = date('Y'); }
	   else { $this->view->tahunabsen2 = $tahunabsen2; }
	   if ((!$bulanabsen2) || ($bulanabsen2 == 'undefined')) { $this->view->bulanabsen2 = date('m'); }
	   else { $this->view->bulanabsen2 = $bulanabsen2; }
           $wheredata = "";
		if ((!$c_lokasi_unitkerja) || ($c_lokasi_unitkerja== 'undefined')) { $c_lokasi_unitkerja= $_REQUEST['param1']; }
		//if ((!$c_eselon_i) || ($c_eselon_i== 'undefined')) { $c_eselon_i= $this->view->ceseloni; }
		//$c_eselon_i=trim($this->view->ceseloni);
              if ($c_lokasi_unitkerja=='1')
              {
                 $c_eselon_iArr = explode(' ',$c_eselon_i); 
                 $c_eselon_iiArr = explode(' ',$c_eselon_ii); 
                 $c_eselon_iiiArr = explode(' ',$c_eselon_iii); 
                 $c_eselon_ivArr = explode(' ',$c_eselon_iv); 
                 if ($c_eselon_iArr[0]!="")
                 {
                      if ($wheredata=="") { $wheredata .= " c_eselon_i='".$c_eselon_iArr[0]."'"; }
                      else { $wheredata .= " and c_eselon_i='".$c_eselon_iArr[0]."'"; }
                 }
                 if ($c_eselon_iiArr[0]!="")
                 {
                      if ($wheredata=="") { $wheredata .= " c_eselon_ii='".$c_eselon_iiArr[0]."'"; }
                      else { $wheredata .= " and c_eselon_ii='".$c_eselon_iiArr[0]."'"; }
                 }
                 if ($c_eselon_iiiArr[0]!="")
                 {
                      if ($wheredata=="") { $wheredata .= " c_eselon_iii='".$c_eselon_iiiArr[0]."'"; }
                      else { $wheredata .= " and c_eselon_iii='".$c_eselon_iiiArr[0]."'"; }
                 }
                 if ($c_eselon_ivArr[0]!="")
                 {
                      if ($wheredata=="") { $wheredata .= " c_eselon_iv='".$c_eselon_ivArr[0]."'"; }
                      else { $wheredata .= " and c_eselon_iv='".$c_eselon_ivArr[0]."'"; }
                 }
              //echo "where : ".$wheredata."<br>";
              }
              else
              {
                 $c_eselon_iArr = explode(';',$c_eselon_i); 
                 $c_eselon_iiArr = explode(';',$c_eselon_ii); 
                 $c_eselon_iiiArr = explode(';',$c_eselon_iii); 
                 $c_eselon_ivArr = explode(';',$c_eselon_iv); 
                 if ($c_eselon_iArr[0]!='') 
                 { 
                    if ($wheredata!='') { $wheredata .= " and "; }
                    $wheredata .= " c_eselon_i='".$c_eselon_iArr[0]."'"; 
                 }
                 if ($c_eselon_iiArr[0]!='') 
                 {
                    if ($wheredata!='') { $wheredata .= " and "; }
                    $wheredata .= " c_eselon_ii='".$c_eselon_iiArr[0]."' and c_parent='".$c_eselon_iiArr[1]."'"; 
                 }
                 if ($c_eselon_iiiArr[0]!='') 
                 {
                    if ($wheredata!='') { $wheredata .= " and "; }
                    $wheredata .= " c_eselon_ii='".$c_eselon_iiiArr[0]."' and c_child='".$c_eselon_iiiArr[1]."' and c_satker='".$c_eselon_iiiArr[2]."'"; 
                 }
                 if ($c_eselon_ivArr[0]!='') 
                 {
                    if ($wheredata!='') { $wheredata .= " and "; }
                    $wheredata .= " c_eselon_iv='".$c_eselon_ivArr[0]."' and c_eselon_iii='00'";
                 }
		   //$wheredata .= " c_eselon_i='".$c_eselon_iArr[0]."' and c_eselon_ii='".$c_eselon_iiArr[0]."' and c_eselon_iii='".$c_eselon_iiiArr[0]."' and c_eselon_iv='".$c_eselon_ivArr[0]."' ";
              }

		if((!$currentPage) || ($currentPage == 'undefined')) { $currentPage = 1; }
		$this->view->numToDisplay = $this->dataPerPage;
		$this->view->c_lokasi_unitkerja=$c_lokasi_unitkerja;		
              $this->view->c_eselon = $c_eselon_i."::".$c_eselon_ii."::".$c_eselon_iii."::".$c_eselon_iv;
              $pegawaidatalstJml = $this->absensi_serv->getPegawaiDataList($wheredata,0,0,'');
	       $this->view->currentPage = $currentPage;   
		$this->view->pegawaidatalstJml = $pegawaidatalstJml;
              $this->view->pegawaidatalst = $this->absensi_serv->getPegawaiDataList($wheredata, $this->view->currentPage, $this->view->numToDisplay,'');
	}
	public function listdataabsensiAction()
	{ 
          //var par = { kd_lokasi:kd_lokasi, mode:mode, c_eselon_i:c_eselon_i, c_eselon_ii:c_eselon_ii, c_eselon_iii:c_eselon_iii, c_eselon_iv:c_eselon_iv, bulanabsen:bulanabsen, tahunabsen:tahunabsen, i_peg_nip:i_peg_nip  };
	   $c_lokasi_unitkerja = $_REQUEST['c_lokasi_unitkerja'];
          $mode = $_REQUEST['mode'];
	   $c_eselon_i = $_REQUEST['c_eselon_i'];
	   $c_eselon_ii = $_REQUEST['c_eselon_ii'];
	   $c_eselon_iii = $_REQUEST['c_eselon_iii'];
	   $c_eselon_iv = $_REQUEST['c_eselon_iv'];
          $tahunabsen = $_REQUEST['tahunabsen'];
          $bulanabsen = $_REQUEST['bulanabsen'];
          $tahunabsen2 = $_REQUEST['tahunabsen2'];
          $bulanabsen2 = $_REQUEST['bulanabsen2'];
          $currentPage = $_REQUEST['currentPage'];

           $c_eselon = $_REQUEST['param4']; 
           if  ($c_eselon)
           { 
              $c_eselonArr = explode('::',$c_eselon);
              $c_eselon_i = $c_eselonArr[0];
              $c_eselon_ii = $c_eselonArr[1];
              $c_eselon_iii = $c_eselonArr[2];
              $c_eselon_iv = $c_eselonArr[3];
           }

	   if ((!$tahunabsen) || ($tahunabsen == 'undefined')) { $this->view->tahunabsen = date('Y'); }
	   else { $this->view->tahunabsen = $tahunabsen; }
	   if ((!$bulanabsen) || ($bulanabsen == 'undefined')) { $this->view->bulanabsen = date('m'); }
	   else { $this->view->bulanabsen = $bulanabsen; }
	   if ((!$tahunabsen2) || ($tahunabsen2 == 'undefined')) { $this->view->tahunabsen2 = date('Y'); }
	   else { $this->view->tahunabsen2 = $tahunabsen2; }
	   if ((!$bulanabsen2) || ($bulanabsen2 == 'undefined')) { $this->view->bulanabsen2 = date('m'); }
	   else { $this->view->bulanabsen2 = $bulanabsen2; }
           $wheredata = '';

	   	$this->view->eselonList = $this->reff_serv->getEselon('');
	   	$c_lokasi_unitkerja=trim($this->view->c_lokasi_unitkerja);
		//if ((!$c_eselon_i) || ($c_eselon_i== 'undefined')) { $c_eselon_i= $this->view->ceseloni; }
		$c_eselon_i=trim($this->view->ceseloni);
		if ($c_eselon_i!='01'){
			$this->view->lokasiList = $this->reff_serv->getLokasi(" and c_lokasi='$c_lokasi_unitkerja'");
			if ($c_lokasi_unitkerja=='1'){
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}
			else{
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}
		}
		else{
			$this->view->lokasiList = $this->reff_serv->getLokasi("");
			if ($c_lokasi_unitkerja=='1'){
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}
			else{
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}		
		
		}

		if ((!$c_lokasi_unitkerja) || ($c_lokasi_unitkerja== 'undefined')) { $c_lokasi_unitkerja= $_REQUEST['param1']; }
              if ($c_lokasi_unitkerja=='1')
              {
                 $c_eselon_iArr = explode(' ',$c_eselon_i); 
                 $c_eselon_iiArr = explode(' ',$c_eselon_ii); 
                 $c_eselon_iiiArr = explode(' ',$c_eselon_iii); 
                 $c_eselon_ivArr = explode(' ',$c_eselon_iv); 
                 if ($c_eselon_iArr[0]!="")
                 {
                      if ($wheredata=="") { $wheredata .= " c_eselon_i='".$c_eselon_iArr[0]."'"; }
                      else { $wheredata .= " and c_eselon_i='".$c_eselon_iArr[0]."'"; }
                 }
                 if ($c_eselon_iiArr[0]!="")
                 {
                      if ($wheredata=="") { $wheredata .= " c_eselon_ii='".$c_eselon_iiArr[0]."'"; }
                      else { $wheredata .= " and c_eselon_ii='".$c_eselon_iiArr[0]."'"; }
                 }
                 if ($c_eselon_iiiArr[0]!="")
                 {
                      if ($wheredata=="") { $wheredata .= " c_eselon_iii='".$c_eselon_iiiArr[0]."'"; }
                      else { $wheredata .= " and c_eselon_iii='".$c_eselon_iiiArr[0]."'"; }
                 }
                 if ($c_eselon_ivArr[0]!="")
                 {
                      if ($wheredata=="") { $wheredata .= " c_eselon_iv='".$c_eselon_ivArr[0]."'"; }
                      else { $wheredata .= " and c_eselon_iv='".$c_eselon_ivArr[0]."'"; }
                 }
                 $eseloniDesc = $c_eselon_iArr[1];
//echo "where1 : ".$wheredata."<br>";
              }
              else
              {
                 $c_eselon_iArr = explode(';',$c_eselon_i); 
                 $c_eselon_iiArr = explode(';',$c_eselon_ii); 
                 $c_eselon_iiiArr = explode(';',$c_eselon_iii); 
                 $c_eselon_ivArr = explode(';',$c_eselon_iv); 
                 if ($c_eselon_iArr[0]!='') 
                 { 
                     if ($wheredata=="") { $wheredata .= " c_eselon_i='".$c_eselon_iArr[0]."'"; }
                     else { $wheredata .= " and c_eselon_i='".$c_eselon_iArr[0]."'"; }
                     $wheredata .= " c_eselon_i='".$c_eselon_iArr[0]."'"; 
                 }
                 if ($c_eselon_iiArr[0]!='') 
                 {
                    if ($wheredata!='') { $wheredata .= " and "; }
                    $wheredata .= " c_eselon_ii='".$c_eselon_iiArr[0]."' and c_parent='".$c_eselon_iiArr[1]."'"; 
                 }
                 if ($c_eselon_iiiArr[0]!='') 
                 {
                    if ($wheredata!='') { $wheredata .= " and "; }
                    $wheredata .= " c_eselon_ii='".$c_eselon_iiiArr[0]."' and c_child='".$c_eselon_iiiArr[1]."' and c_satker='".$c_eselon_iiiArr[2]."'"; 
                 }
                 if ($c_eselon_ivArr[0]!='') 
                 {
                    if ($wheredata!='') { $wheredata .= " and "; }
                    $wheredata .= " c_eselon_iv='".$c_eselon_ivArr[0]."' and c_eselon_iii='00'";
                 }
                 $eseloniDesc = $c_eselon_iArr[1];
		   //$wheredata .= " c_eselon_i='".$c_eselon_iArr[0]."' and c_eselon_ii='".$c_eselon_iiArr[0]."' and c_eselon_iii='".$c_eselon_iiiArr[0]."' and c_eselon_iv='".$c_eselon_ivArr[0]."' ";
              }
		if((!$currentPage) || ($currentPage == 'undefined')) { $currentPage = 1; }
		$this->view->numToDisplay = $this->dataPerPage;
		$this->view->c_lokasi_unitkerja=$c_lokasi_unitkerja;		
		$this->view->c_eselon_i=$c_eselon_i;
              $this->view->eseloniDesc = $eseloniDesc; 
              $this->view->c_eselon = $c_eselon_i."::".$c_eselon_ii."::".$c_eselon_iii."::".$c_eselon_iv;
              //$pegawaidatalstJml = $this->absensi_serv->getPegawaiDataList($wheredata,0,0,'');
	       $this->view->currentPage = $currentPage;   
		$this->view->pegawaidatalstJml = $pegawaidatalstJml;
              //$this->view->pegawaidatalst = $this->absensi_serv->getPegawaiDataList($wheredata, $this->view->currentPage, $this->view->numToDisplay,'');
	}
	public function listdatapegawaiAction()
	{

           //var par = { kd_lokasi:kd_lokasi, mode:mode, c_eselon_i:c_eselon_i, c_eselon_ii:c_eselon_ii, c_eselon_iii:c_eselon_iii, c_eselon_iv:c_eselon_iv  };
           //$this->_helper->viewRenderer->setNoRender(true);
	    $unitkerja = $_REQUEST['kd_lokasi'];
	    $mode = $_REQUEST['mode'];
           $c_eselon_i = $_REQUEST['c_eselon_i'];
           $c_eselon_ii = $_REQUEST['c_eselon_ii'];
           $c_eselon_iii = $_REQUEST['c_eselon_iii'];
           $c_eselon_iv = $_REQUEST['c_eselon_iv'];

	    //if ((!$c_eselon_i) || ($c_eselon_i == 'undefined') || (!$c_eselon_ii) || ($c_eselon_ii == 'undefined') || 
           //(!$c_eselon_iii) || ($c_eselon_iii == 'undefined') || (!$c_eselon_iv) || ($c_eselon_iv == 'undefined'))
           $c_eselon = $_REQUEST['param4']; 
           if  ($c_eselon)
           { 
              $c_eselonArr = explode('::',$c_eselon);
              $c_eselon_i = $c_eselonArr[0];
              $c_eselon_ii = $c_eselonArr[1];
              $c_eselon_iii = $c_eselonArr[2];
              $c_eselon_iv = $c_eselonArr[3];
           }

	    $numToDisplay = $this->dataPerPage;
           $wheredata = '';
           //$wheredata = ' 1=1 ';
		if ((!$unitkerja) || ($unitkerja == 'undefined')) { $unitkerja = $_REQUEST['param1']; }
              if ($unitkerja=='1')
              {
                 $c_eselon_iArr = explode(' ',$c_eselon_i); 
                 $c_eselon_iiArr = explode(' ',$c_eselon_ii); 
                 $c_eselon_iiiArr = explode(' ',$c_eselon_iii); 
                 $c_eselon_ivArr = explode(' ',$c_eselon_iv); 
                 if ($c_eselon_iArr[0]!="")
                 {
                      if ($wheredata=="") { $wheredata .= " c_eselon_i='".$c_eselon_iArr[0]."'"; }
                      else { $wheredata .= " and c_eselon_i='".$c_eselon_iArr[0]."'"; }
                 }
                 if ($c_eselon_iiArr[0]!="")
                 {
                      if ($wheredata=="") { $wheredata .= " c_eselon_ii='".$c_eselon_iiArr[0]."'"; }
                      else { $wheredata .= " and c_eselon_ii='".$c_eselon_iiArr[0]."'"; }
                 }
                 if ($c_eselon_iiiArr[0]!="")
                 {
                      if ($wheredata=="") { $wheredata .= " c_eselon_iii='".$c_eselon_iiiArr[0]."'"; }
                      else { $wheredata .= " and c_eselon_iii='".$c_eselon_iiiArr[0]."'"; }
                 }
                 if ($c_eselon_ivArr[0]!="")
                 {
                      if ($wheredata=="") { $wheredata .= " c_eselon_iv='".$c_eselon_ivArr[0]."'"; }
                      else { $wheredata .= " and c_eselon_iv='".$c_eselon_ivArr[0]."'"; }
                 }
		   //$wheredata .= " c_eselon_i='".$c_eselon_iArr[0]."' and c_eselon_ii='".$c_eselon_iiArr[0]."' and c_eselon_iii='".$c_eselon_iiiArr[0]."' and c_eselon_iv='".$c_eselon_ivArr[0]."' ";
                 //echo "where1 : ".$wheredata."<br>";
              }
              else
              {
                 $c_eselon_iArr = explode(';',$c_eselon_i); 
                 $c_eselon_iiArr = explode(';',$c_eselon_ii); 
                 $c_eselon_iiiArr = explode(';',$c_eselon_iii); 
                 $c_eselon_ivArr = explode(';',$c_eselon_iv); 
                 if ($c_eselon_iArr[0]!='') { $wheredata .= " c_eselon_i='".$c_eselon_iArr[0]."'"; }
                 if ($c_eselon_iiArr[0]!='') 
                 {
                    if ($wheredata!='') { $wheredata .= " and "; }
                    $wheredata .= " c_eselon_ii='".$c_eselon_iiArr[0]."' and c_parent='".$c_eselon_iiArr[1]."'"; 
                 }
                 if ($c_eselon_iiiArr[0]!='') 
                 {
                    if ($wheredata!='') { $wheredata .= " and "; }
                    $wheredata .= " c_eselon_ii='".$c_eselon_iiiArr[0]."' and c_child='".$c_eselon_iiiArr[1]."' and c_satker='".$c_eselon_iiiArr[2]."'"; 
                 }
                 if ($c_eselon_ivArr[0]!='') 
                 {
                    if ($wheredata!='') { $wheredata .= " and "; }
                    $wheredata .= " c_eselon_iv='".$c_eselon_ivArr[0]."' and c_eselon_iii='00'";
                 }
		   //$wheredata .= " c_eselon_i='".$c_eselon_iArr[0]."' and c_eselon_ii='".$c_eselon_iiArr[0]."' and c_eselon_iii='".$c_eselon_iiiArr[0]."' and c_eselon_iv='".$c_eselon_ivArr[0]."' ";
              }

		$pilfilter = $_REQUEST['pilfilter'];
		if ((!$pilfilter) || ($pilfilter == 'undefined')) { $pilfilter = $_REQUEST['param2']; }
		
		$filterval = $_REQUEST['filterval'];   
		if ((!$filterval) || ($filterval == 'undefined')) { $filterval = $_REQUEST['param3']; }

		if ($filterval != '')
		{  
		   if ($wheredata=="") { $wheredata .= "  ".$pilfilter." like '%".strtoupper($filterval)."%'";}
		   else {$wheredata .= " and ".$pilfilter." like '%".strtoupper($filterval)."%'";}
         }   
//echo "where : ".$wheredata."<br>";
	       $currentPage = $_REQUEST['currentPage']; 
		if((!$currentPage) || ($currentPage == 'undefined')) { $currentPage = 1; }
		$this->view->unitkerja = $unitkerja;
		$this->view->pilfilter = $pilfilter;
		$this->view->filterval = $filterval;
		$this->view->numToDisplay = $numToDisplay;
              $this->view->c_eselon = $c_eselon_i."::".$c_eselon_ii."::".$c_eselon_iii."::".$c_eselon_iv;
             $pegawaidatalstJml = $this->absensi_serv->getPegawaiDataList($wheredata,0,0,'');
	      $this->view->currentPage = $currentPage;   
	      $this->view->pegawaidatalstJml = $pegawaidatalstJml;
             $this->view->pegawaidatalst = $this->absensi_serv->getPegawaiDataList($wheredata, $this->view->currentPage, $this->view->numToDisplay,'');
	}
	public function dataabsensikuAction()
	{ 
	   $kd_lokasi = $_REQUEST['c_lokasi_unitkerja'];
         // $mode = $_REQUEST['mode'];
	   $c_eselon_i = $_REQUEST['eseloni'];
	   $c_eselon_ii = $_REQUEST['eselonii'];
	   $c_eselon_iii = $_REQUEST['eseloniii'];
	   $c_eselon_iv = $_REQUEST['eseloniv'];
	   $nippegawai = $_REQUEST['i_peg_nip'];
          $tahunabsen = $_REQUEST['tahunabsen'];
          $bulanabsen = $_REQUEST['bulanabsen'];
/*
	   $kd_lokasi = $_REQUEST['kd_lokasi'];
          $mode = $_REQUEST['mode'];
	   $c_eselon_i = $_REQUEST['c_eselon_i'];
	   $c_eselon_ii = $_REQUEST['c_eselon_ii'];
	   $c_eselon_iii = $_REQUEST['c_eselon_iii'];
	   $c_eselon_iv = $_REQUEST['c_eselon_iv'];
	   $nippegawai = $_REQUEST['i_peg_nip'];
          $tahunabsen = $_REQUEST['tahunabsen'];
          $bulanabsen = $_REQUEST['bulanabsen']; */

	   $this->view->i_peg_nip = $nippegawai;

	   if ((!$tahunabsen) || ($tahunabsen == 'undefined')) { $this->view->tahunabsen = date('Y'); }
	   else { $this->view->tahunabsen = $tahunabsen; }
	   if ((!$bulanabsen) || ($bulanabsen == 'undefined')) { $this->view->bulanabsen = date('m'); }
	   else { $this->view->bulanabsen = $bulanabsen; }

	   	$this->view->eselonList = $this->reff_serv->getEselon('');
		//$this->view->lokasiList = $this->reff_serv->getLokasi('');
	   	$c_lokasi_unitkerja=trim($this->view->c_lokasi_unitkerja);
		$c_eselon_i=trim($this->view->ceseloni);
		if ($c_eselon_i!='01'){
			$this->view->lokasiList = $this->reff_serv->getLokasi(" and c_lokasi='$c_lokasi_unitkerja'");
			if ($c_lokasi_unitkerja=='1'){
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}
			else{
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}
		}
		else{
			$this->view->lokasiList = $this->reff_serv->getLokasi("");
			if ($c_lokasi_unitkerja=='1'){
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}
			else{
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}		
		
		}
              $this->view->nippegawai = $nippegawai;
		$this->view->c_eselon_i=$c_eselon_i;
		$this->view->c_lokasi_unitkerja=$c_lokasi_unitkerja;		
		$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai("and c_peg_tipegolongan='3'");
		$this->view->statusKePegRef = $this->reff_serv->getStatusKepegawaian(" and c_status_kepegawaian in ('3','4','5','6')");
              $lstkodeIjin = $this->absensimsn_serv->getKodeIjinLst();
              $jmlRslt = count($lstkodeIjin);
              for ($i=0; $i<$jmlRslt; $i++)
              {
                  $absensilst[$lstkodeIjin[$i]['c_ijin']] = $lstkodeIjin[$i]['n_ijin'];
              }
              $this->view->absensilst = $absensilst;
	}
	public function dataabsensiAction()
	{ 
          //var par = { kd_lokasi:kd_lokasi, mode:mode, c_eselon_i:c_eselon_i, c_eselon_ii:c_eselon_ii, c_eselon_iii:c_eselon_iii, c_eselon_iv:c_eselon_iv, bulanabsen:bulanabsen, tahunabsen:tahunabsen, i_peg_nip:i_peg_nip  };
	   $kd_lokasi = $_REQUEST['kd_lokasi'];
          $mode = $_REQUEST['mode'];
	   $c_eselon_i = $_REQUEST['c_eselon_i'];
	   $c_eselon_ii = $_REQUEST['c_eselon_ii'];
	   $c_eselon_iii = $_REQUEST['c_eselon_iii'];
	   $c_eselon_iv = $_REQUEST['c_eselon_iv'];
	   $nippegawai = $_REQUEST['i_peg_nip'];
          $tahunabsen = $_REQUEST['tahunabsen'];
          $bulanabsen = $_REQUEST['bulanabsen'];

          if ($this->view->wewenang!='A')
          {
	       $this->view->c_eselon_i = $this->bc_eselon_i;
	       $this->view->c_eselon_ii = $this->bc_eselon_ii;
	       $this->view->c_eselon_iii = $this->bc_eselon_iii;
	       $this->view->c_eselon_iv = $this->bc_eselon_iv;
	       $this->view->c_eselon_v = $this->bc_eselon_v;
	       $this->view->n_eselon_i = $this->nc_eselon_i;
	       $this->view->n_eselon_ii = $this->nc_eselon_ii;
	       $this->view->n_eselon_iii = $this->nc_eselon_iii;
	       $this->view->n_eselon_iv = $this->nc_eselon_iv;
	       $this->view->n_eselon_v = $this->nc_eselon_v;
              $this->view->i_peg_nip = $this->gi_peg_nip.". ".$this->gn_peg;
          }
	   else { 
              $this->view->i_peg_nip = $nippegawai; 
              $this->view->nippegawai = $nippegawai;
          }
	   if ((!$tahunabsen) || ($tahunabsen == 'undefined')) { $this->view->tahunabsen = date('Y'); }
	   else { $this->view->tahunabsen = $tahunabsen; }
	   if ((!$bulanabsen) || ($bulanabsen == 'undefined')) { $this->view->bulanabsen = date('m'); }
	   else { $this->view->bulanabsen = $bulanabsen; }

	   	$this->view->eselonList = $this->reff_serv->getEselon('');
		//$this->view->lokasiList = $this->reff_serv->getLokasi('');
	   	$c_lokasi_unitkerja=trim($this->view->c_lokasi_unitkerja);
		$c_eselon_i=trim($this->view->ceseloni);
		if ($c_eselon_i!='01'){
			$this->view->lokasiList = $this->reff_serv->getLokasi(" and c_lokasi='$c_lokasi_unitkerja'");
			if ($c_lokasi_unitkerja=='1'){
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}
			else{
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}
		}
		else{
			$this->view->lokasiList = $this->reff_serv->getLokasi("");
			if ($c_lokasi_unitkerja=='1'){
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}
			else{
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}		
		
		}
		$this->view->c_eselon_i=$c_eselon_i;
		$this->view->c_lokasi_unitkerja=$c_lokasi_unitkerja;		
		$this->view->statusGolRef = $this->reff_serv->getGolonganPegawai("and c_peg_tipegolongan='3'");
		$this->view->statusKePegRef = $this->reff_serv->getStatusKepegawaian(" and c_status_kepegawaian in ('3','4','5','6')");
              $lstkodeIjin = $this->absensimsn_serv->getKodeIjinLst();
              $jmlRslt = count($lstkodeIjin);
              for ($i=0; $i<$jmlRslt; $i++)
              {
                  $absensilst[$lstkodeIjin[$i]['c_ijin']] = $lstkodeIjin[$i]['n_ijin'];
              }
              $this->view->absensilst = $absensilst;
	}
	public function dataabsensimesinAction()
	{ 
          $pil = $_REQUEST['pil'];
          $pil_val = $_REQUEST['pil_val'];
	   $kd_lokasi = $_REQUEST['kd_lokasi'];
	   $currentPage = $_REQUEST['currentPage'];
	   $numToDisplay = $this->dataPerPage;

	   $terminallist = $this->absensimsn_serv->getTerminallist('');
	   $this->view->terminallist = $terminallist;
	   if ((!$kd_lokasi) || ($kd_lokasi == 'undefined')) { $kd_lokasi = $_REQUEST['param1']; }
	   if ((!$kd_lokasi) || ($kd_lokasi == 'undefined')) { $this->view->kd_lokasi = $terminallist[0]->kd_lokasi; }
	   else { $this->view->kd_lokasi=$kd_lokasi; }

	   $tglcek = $_REQUEST['tglcek'];
	   if ((!$tglcek) || ($tglcek == 'undefined')) { $tglcek = $_REQUEST['param2']; }
	   $tglcekar = explode('-',$tglcek);
		
	   $tanggal = $tglcekar[0];
	   $bulan = $tglcekar[1];
	   $tahun = $tglcekar[2];

	   if ((!$pil) || ($pil == 'undefined')) { $pil = $_REQUEST['param3']; }
	   if ((!$pil_val) || ($pil_val == 'undefined')) { $pil_val = $_REQUEST['param4']; }

	   if ((!$tahun) || ($tahun == 'undefined')) { $this->view->tahun = date('Y'); }
	   else { $this->view->tahun = $tahun; }
		
	   if ((!$bulan) || ($bulan == 'undefined')) { $this->view->bulan = date('m'); }
	   else { $this->view->bulan = $bulan; }
		
          $bulanatrList = array("01"=>"Januari","02"=>"Februari","03"=>"Maret","04"=>"April","05"=>"Mei","06"=>"Juni",
                      "07"=>"Juli","08"=>"Agustus","09"=>"September","10"=>"Oktober","11"=>"November","12"=>"Desember");
          $this->view->pillist = array("i_peg_nip"=>"Kode Finger","i_term"=>"Nomor Mesin");
          //$this->view->pillist = array("i_peg_nip"=>"Kode Finger","i_term"=>"Nomor Mesin","d_peg_jam"=>"Jam");
	   $this->view->bulanatrList = $bulanatrList;			  
          $jmlTgawlLst = cal_days_in_month(CAL_GREGORIAN, $this->view->bulan, $this->view->tahun); 
          for ($i=0; $i<$jmlTgawlLst; $i++)
          {
		if ($i<9) { $tglarr = '0'.($i+1); }
		else {  $tglarr = $i+1; }
              $tgllst[$tglarr] = $tglarr;
          }
	   $this->view->tgllst = $tgllst;
	   if ((!$tanggal) || ($tanggal == 'undefined')) { $this->view->tanggal = $tgllst[0]; }
	   else { $this->view->tanggal = $tanggal; }
          $tgl_report = $this->view->tanggal.'-'.$this->view->bulan.'-'.$this->view->tahun;	   
	   
	   if((!$currentPage) || ($currentPage == 'undefined')) { $currentPage = 1; }
	   $wheredata = " c_terminal='".$this->view->kd_lokasi."' and to_char(d_peg_absensi,'dd-mm-yyyy')='".$tgl_report."'";
//echo "wewenang : ".$this->view->wewenang."<br>";
          if ($this->view->wewenang!='A') 
          { 
             $nip_finger = $this->absensimsn_serv->getFingerInNip($this->view->i_peg_nip);
             $wheredata .= " and i_peg_nip='".$nip_finger."'"; 
          }
          if ($pil_val!="") { $wheredata .= " and ".$pil."='".$pil_val."'"; }
          $this->view->pil = $pil;
          $this->view->pil_val = $pil_val;
	   $this->view->numToDisplay = $numToDisplay;
          $dataabsensipegJml = $this->absensimsn_serv->getDataAbsensimsnList($wheredata,0,0,'');
	   $this->view->currentPage = $currentPage;   
	   $this->view->dataabsensipegJml = $dataabsensipegJml;
          $this->view->dataabsensipeg = $this->absensimsn_serv->getDataAbsensimsnList($wheredata, $this->view->currentPage, $this->view->numToDisplay,'i_peg_nip,d_peg_absensi,d_peg_jam');
	}
    public function simpanijinAction()
	{
          $this->_helper->viewRenderer->setNoRender(true);
	   $i_peg_nip = $_REQUEST['i_peg_nip'];
	   if ($i_peg_nip!='') 
	   { 
          $kd_lokasi = $_REQUEST['kd_lokasi'];
	   $c_eselon_i = $_REQUEST['c_eselon_i'];
	   $c_eselon_ii = $_REQUEST['c_eselon_ii'];
	   $bulanabsen = $_REQUEST['bulanabsen'];
	   $tahunabsen = $_REQUEST['tahunabsen'];
	   $nip = explode('.',$i_peg_nip);
          $kodeijin = $_REQUEST['kodeijin'];
	   $suratijin = $_REQUEST['suratijin'];
          $tglijin = $_REQUEST['tglijin'];
          echo $tglijin;
	   $d_jam_mulai = '07:30';
	   $d_jam_selesai = '16:45';
	   $user = $this->view->userid;
	   //echo "kodeijin : ".$kodeijin;
	   $absensi_prm = array("i_peg_nip"  			=>$nip[0],
	                          "d_peg_ijin"  		=>$tglijin,
	                          "d_jam_mulai"  		=>$d_jam_mulai,
	                          "d_jam_selesai"  		=>$d_jam_selesai,
				      "c_ijin"              =>$kodeijin,
				      "i_no_surat_ijin"		=>$suratijin,
				      "i_entry"       		=>$user,
				      "d_entry"       		=>date("Y-m-d"));
		//var_dump($absensi_prm);   
           //$prssimpan = $this->absensimsn_serv->insertIjin($absensi_prm);
	    //echo "hasil 1".$prssimpan;
	    //if ($prssimpan=='gagal')
	    //{ 
		$prssimpan = $this->absensimsn_serv->updateIjin($absensi_prm); 
		echo "hasil 2".$prssimpan;
	    //}
	   }
	}

       public function listcomboabsensiAction() {
	$jabatanlengkap="";
	$this->view->c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);
	$c_lokasi_unitkerja=$this->view->c_lokasi_unitkerja;
	$c_eselon_i=trim($this->view->ceseloni);	
	$this->view->c_eselon_i =$_GET['eseloni'];
	if ($c_eselon_i=='01')
	{
		$this->view->lokasiList = $this->reff_serv->getLokasi("");
		if ($c_lokasi_unitkerja=='3'){
		$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
		}
		else{
		$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
		}
	}
	else
	{	
		$this->view->lokasiList = $this->reff_serv->getLokasi(" and c_lokasi='$c_lokasi_unitkerja'");
		if ($c_lokasi_unitkerja=='3'){
		$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='2' and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
		}
		else{
		$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
		}		
	}
	$c_eselon_i=$_GET['eseloni'];
	$c_eselon_i=substr($c_eselon_i,0,2);
	$this->view->c_eselon_ii =$_GET['eselonii'];
	$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i' and trim(c_tkt_esl)='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");	
	if ($_GET['eseloni']){$nesl1=$this->left($_GET['eseloni']); $nesl1=",".$nesl1;}
	
	$c_eselon_ii=$_GET['eselonii'];
	$c_eselon_ii=substr($c_eselon_ii,0,3);
	$this->view->c_eselon_iii =$_GET['eseloniii'];
	$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii' and trim(c_tkt_esl)='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	if ($_GET['eselonii']){$nesl2=$this->left($_GET['eselonii']); $nesl2=",".$nesl2;}
	$c_eselon_iii=$_GET['eseloniii'];
	$c_eselon_iii=substr($c_eselon_iii,0,2);
	$this->view->c_eselon_iv =$_GET['eseloniv'];
	$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii' and trim(c_tkt_esl)='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	if ($_GET['eseloniii']){$nesl3=$this->left($_GET['eseloniii']); $nesl3=",".$nesl3;}
	$c_eselon_iv=$_GET['eseloniv'];
	$c_eselon_iv=substr($c_eselon_iv,0,2);	
	$this->view->eselonvList = $this->reff_serv->getTrUnitKerja(" and c_eselon_i='$c_eselon_i'  and c_eselon_ii='$c_eselon_ii'  and c_eselon_iii='$c_eselon_iii'  and c_eselon_iv='$c_eselon_iv' and trim(c_tkt_esl)='5' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
	if ($_GET['eseloniv']){$nesl4=$this->left($_GET['eseloniv']); $nesl4=",".$nesl4;}
       }

       public function listcomboabsensi2Action() {
	$jabatanlengkap="";
	$this->view->c_lokasi_unitkerja=trim($_GET['c_lokasi_unitkerja']);

	$c_lokasi_unitkerja=$this->view->c_lokasi_unitkerja;
	$c_eselon_i=trim($this->view->ceseloni);
	if ($c_eselon_i=='01'){
		$this->view->lokasiList = $this->reff_serv->getLokasi("");
		$c_eselon_i=trim($this->view->c_eselon_i);
		if ($c_lokasi_unitkerja=='1'){
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}
		else{
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}	
	}
	else
	{
		$this->view->lokasiList = $this->reff_serv->getLokasi(" and c_lokasi='$c_lokasi_unitkerja'");
		$c_eselon_i=trim($this->view->c_eselon_i);
		if ($c_lokasi_unitkerja=='1'){
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerja(" and trim(c_tkt_esl)='1' and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}
		else{
			$this->view->eseloniList = $this->reff_serv->getTrUnitKerjaPngl(" and c_eselon_i='$c_eselon_i' and c_lokasi_unitkerja='$c_lokasi_unitkerja'");
			}	

	}
	
	$c_eselon_i=$_GET['eseloni'];
	$expesl1 = explode(";",$c_eselon_i);
	$c_eselon_i=$expesl1[0];
	$this->view->c_eselon_i =trim($_GET['eseloni']);
	

	$c_eselon_ii=$_GET['eselonii'];
	$expesl2 = explode(";",$c_eselon_ii);
	$c_eselon_ii=$expesl2[0];
	$c_parent=$expesl2[1];
	$this->view->c_eselon_ii =trim($_GET['eselonii']);
	
	
	$c_eselon_iii=$_GET['eseloniii'];
	if ($c_eselon_iii){
	$expesl3 = explode(";",$c_eselon_iii);	
	$c_eselon_ii=$expesl3[0];
	$this->view->c_eselon_iii =trim($_GET['eseloniii']);	
	}

	$this->view->eseloniiList = $this->reff_serv->getTrUnitKerja(" and c_level ='2' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_eselon_i='$c_eselon_i' ");
	if ($_GET['eseloni']){$nesl1=$expesl1[1]; $nesl1=$nesl1;}

	$this->view->eseloniiiList = $this->reff_serv->getTrUnitKerja(" and  c_level ='3' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_eselon_i='$c_eselon_i'  and c_parent ='$c_parent'");
	if ($_GET['eselonii']){$nesl2=$expesl2[2]; $nesl2=$nesl2.",";}	
		
	$this->view->eselonivList = $this->reff_serv->getTrUnitKerja(" and c_level ='4' and c_lokasi_unitkerja='$c_lokasi_unitkerja' and c_eselon_i='$c_eselon_i' and c_eselon_ii='$c_eselon_ii' and c_parent ='$c_parent'");
	if ($_GET['eseloniii']){$nesl3=$expesl3[3]; $nesl3=$nesl3.",";}


	$this->view->c_eselon_iv =trim($_GET['eseloniv']);
	if ($_GET['eseloniv']){$nesl4=$this->left($_GET['eseloniv']); $nesl4=$nesl4.",";}	
	$jabatanlengkap=$nesl4.$nesl3.$nesl2.$nesl1;
	$this->view->jabat_lengkap=$jabatanlengkap;
	$this->view->c_eselon=$_GET['c_eselon'];
	$this->view->eselonList = $this->reff_serv->getEselon('');
       }

}
?>