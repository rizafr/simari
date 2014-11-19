<?php
class ast_laporan_inv_Service{
   
	private static $instance;
   
    // A private constructor; prevents direct creation of object
    private function __construct() {
      
    }
 
    // The singleton method
     public static function getInstance() {
       if (!isset(self::$instance)) {
           $c = __CLASS__;
           self::$instance = new $c;
       }

       return self::$instance;
    }

	/* ************
	 * fungsi untuk   e_ast_ajuanpindahinv_0_tm
	 ***************************/
	//=================================== 12 Nop 07=============================================================================
	
	public function queryUnitKerja($unitkr) {
	   
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = "i_orgb = '".$data['unitkr']."'";
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('SELECT i_orgb,n_orgb FROM e_org_0_0_tm where i_orgb=?',$unitkr);
         $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("i_orgb"           =>(string)$result[$j]->i_orgb,
								   "n_orgb"          =>(string)$result[$j]->n_orgb);
								  
							       
		
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
		
	public function getLaporanPengadaan($tglAwal,$tglAkhir) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
	     $where[] = $tglAwal;
		 $where[] = $tglAwal;
		 $where[] = $tglAkhir;
		 $where[] = $tglAkhir;
		 $where[] = $tglAwal;
		 $where[] = $tglAwal;
		 $where[] = $tglAkhir;
		 $where[] = $tglAkhir;
		 $where[] = $tglAwal;
		 $where[] = $tglAwal;
		 $where[] = $tglAkhir;
		 $where[] = $tglAkhir;
		 
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
		 $result = $db->fetchAll('select a.c_atk,b.n_atk,a.c_atk_ctgr,n_atk_satuan, q_atk_stock,v_atk_hrgsatuan,
									sum(CASE WHEN d_atk_ajuan < ? and d_atk_kirim < ?  THEN  (cast(q_atk_setujubeli as float)- cast(q_atk_kirim as float)) + cast(q_atk_stock as float)  END) as jumlah,
									sum(CASE WHEN d_atk_ajuan < ? and d_atk_kirim < ?  THEN (cast(q_atk_setujubeli as float) - cast(q_atk_kirim as float)) + cast(q_atk_stock as float) END) as jumlah2,
									sum(CASE WHEN d_atk_ajuan < ? and d_atk_kirim < ?  THEN ((cast(q_atk_setujubeli as float) - cast(q_atk_kirim as float)) + cast(q_atk_stock as float)) * v_atk_hrgsatuan END) as nilai,
									sum(CASE WHEN d_atk_ajuan < ? and d_atk_kirim < ?  THEN ((cast(q_atk_setujubeli as float) - cast(q_atk_kirim as float)) + cast(q_atk_stock as float)) * v_atk_hrgsatuan END) as nilai2,
									sum(CASE WHEN d_atk_ajuan < ? and d_atk_kirim < ?  THEN cast(q_atk_setujubeli as float) END) as mutasi1,
									sum(CASE WHEN d_atk_ajuan < ? and d_atk_kirim < ?  THEN cast(q_atk_kirim as float) END) as mutasi2
					                from(select c.c_atk,c.c_atk_ctgr,q_atk_kirim ,d_atk_kirim,q_atk_setujubeli,d_atk_ajuan   
					                       from e_ast_distribusi_atk_tm b,e_ast_distribusi_itematk_tm c, 
					                       e_ast_ajuanbeli_atk_tm  d,e_ast_ajuanbeli_itematk_tm e
					                       where b.i_atk_kirim =c.i_atk_kirim and d.i_atk_ajuan =e.i_atk_ajuan  
					                             and c.c_atk=e.c_atk) a, 
                			        e_ast_barang_atk_tr  b	where   a.c_atk=b.c_atk  and a.c_atk_ctgr=b.c_atk_ctgr	
									group by a.c_atk,b.n_atk,a.c_atk_ctgr,n_atk_satuan,q_atk_stock,v_atk_hrgsatuan order by b.n_atk',$where);
         
		 $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		   $hasilAkhir[$j] = array("c_atk"            	=>(string)$result[$j]->c_atk,
								   "c_atk_ctgr"       	=>(string)$result[$j]->c_atk_ctgr,
								   "n_atk"            	=>(string)$result[$j]->n_atk,
								   "n_atk_satuan"     	=>(string)$result[$j]->n_atk_satuan,
								   "jumlah"           	=>(string)$result[$j]->jumlah,
								   "jumlah2"          	=>(string)$result[$j]->jumlah2,
								   "nilai"            	=>(string)$result[$j]->nilai,
								   "nilai2"          	=>(string)$result[$j]->nilai2,
	                               "mutasi1"         	=>(string)$result[$j]->mutasi1,
								   "mutasi2"         	=>(string)$result[$j]->mutasi2,
								   "v_atk_hrgsatuan"    =>(string)$result[$j]->v_atk_hrgsatuan,
								   "d_atk_kirim"        =>(string)$result[$j]->d_atk_kirim,
								   "q_atk_stock"        =>(string)$result[$j]->q_atk_stock);
								  
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	//=================================== 15 Nop 07============================================================================= 
	 public function getKodeBarang() {
      
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('select kd_gol, ur_gol from e_ast_gol_aset_tr 
							  	order by kd_gol');
								
         $jmlResult = count($result);
		 
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("kd_gol"           	=>(string)$result[$j]->kd_gol,
								   "ur_gol"   =>(string)$result[$j]->ur_gol);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

public function getBidangAset($KdGol) {
      
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	    
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('select kd_bid, ur_bid, kd_perk from e_ast_bid_aset_tr 
								  where kd_gol = ? 
								  order by kd_bid',$KdGol);
								
         $jmlResult = count($result);
		 
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("kd_bid"           	=>(string)$result[$j]->kd_bid,
								   "ur_bid"   =>(string)$result[$j]->ur_bid,
								   "kd_perk"   =>(string)$result[$j]->kd_perk);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
	
	public function getKelAset($KdGol,$KdBid) {
      
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $KdGol;
		 $where[] = $KdBid;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('select kd_kel, ur_kel from e_ast_kel_aset_tr 
								  where kd_gol = ?
								  and kd_bid   = ?
								  order by kd_kel',$where); 
								
         $jmlResult = count($result);
		 
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("kd_kel"           	=>(string)$result[$j]->kd_kel,
								   "ur_kel"   =>(string)$result[$j]->ur_kel);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	
	public function getNamaBarang($kodeBrg) {
      
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     
		 
		 $gol = substr($kodeBrg,0,1);
		 $bid = substr($kodeBrg,1,2);
		 $kel = substr($kodeBrg,3,2);
		 $skel = substr($kodeBrg,5,2);
		 $sskel = substr($kodeBrg,7,3);
		 
		 
										
	     $where[] = $gol;
		 $where[] = $bid;
		 $where[] = $kel;
		 $where[] = $skel;
		 $where[] = $sskel;
		 
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchOne('select ur_sskel from e_ast_sskel_0_tr 
								  where kd_gol = ?
								  and kd_bid   = ?
								  and kd_kel   = ?
							      and kd_skel  = ?
								  and kd_sskel  = ?
								  order by kd_sskel',$where);
								
         	 
	     return $result;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getSKelAset($KdGol,$KdBid,$Kdkel) {
      
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $KdGol;
		 $where[] = $KdBid;
		 $where[] = $Kdkel;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('select kd_skel, ur_skel from e_ast_skel_0_tr 
								where kd_gol = ?
								and kd_bid = ?
								and kd_kel = ?
								order by kd_skel',$where);
								
         $jmlResult = count($result);
		 
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("kd_skel"           	=>(string)$result[$j]->kd_skel,
								   "ur_skel"   =>(string)$result[$j]->ur_skel);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getSSKelAset($KdGol,$KdBid,$Kdkel,$Kdskel) {
      
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $where[] = $KdGol;
		 $where[] = $KdBid;
		 $where[] = $Kdkel;
		 $where[] = $Kdskel;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $result = $db->fetchAll('select kd_sskel, ur_sskel from e_ast_sskel_0_tr 
								  where kd_gol = ?
								  and kd_bid   = ?
								  and kd_kel   = ?
							      and kd_skel  = ?								  
								  order by kd_sskel',$where);
								
         $jmlResult = count($result);
		 
		 
		 if($jmlResult > 0){
		 for ($j = 0; $j < $jmlResult; $j++) {
		 
           $hasilAkhir[$j] = array("kd_sskel"           	=>(string)$result[$j]->kd_sskel,
								   "ur_sskel"   =>(string)$result[$j]->ur_sskel);
								  
								  
							       
		
		 }
        }		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	

	
//=================================== 15 Nop 07=============================================================================

   // Ina : 12-05-2008 : Awal
   public function getInventarisintra($kdBarang, $tglAwal, $tglAkhir) {
        //echo"kdBarang".$kdBarang;
		//echo"tglAwal".$tglAwal;
		//echo"tglAkhir".$tglAkhir;
	    $registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
	     		 	 		 		 
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
		 if ($kdBarang== '' && ($tglAwal =='' || $tglAkhir ==''))
		 {
		    
			$result = $db->fetchAll("select A.kd_brg, B.ur_sskel, C.kd_perk, A.tgl_buku, A.Kondisi, A.tgl_perlh, A.merk_type,
										A.jns_trn, A.no_dsr_mts, A.kd_sat,
										A.rph_sat, sum(A.kuantitas) as kuantitas,(A.rph_sat*sum(A.kuantitas)) as rph_aset,
										case when A.kondisi = '1' then sum(A.kuantitas) END as Baik,
										case when A.kondisi = '2' then sum(A.kuantitas) END as RusakRingan,
										case when A.kondisi = '3' then sum(A.kuantitas) END as RusakBerat,
										case when A.jns_trn in ('100','101','102','103','104','105','106','107','202')  then 'T' END as kodet,
										case when A.jns_trn in ('201','301','302','303','304') then 'K' END as kodek,
										case when A.jns_trn in ('204','305') then 'Ka' END as kodeka,
										case when A.jns_trn in ('203') then 'Kk' END  as kodekk
										from e_sabm_t_master_tm A,e_ast_sskel_0_tr B, e_ast_bid_aset_tr C
										where substr(A.kd_brg,1,1) = B.kd_gol
										and substr(A.kd_brg,2,2) = B.kd_bid 
										and substr(A.kd_brg,4,2) = B.kd_kel
										and substr(A.kd_brg,6,2) = B.kd_skel
										and substr(A.kd_brg,8,3) = B.kd_sskel 
										and substr(A.kd_brg,1,1) = C.kd_gol
							            and substr(A.kd_brg,2,2) = C.kd_bid 										
										group by A.kd_brg, B.ur_sskel, C.kd_perk, A.tgl_buku, A.Kondisi, A.tgl_perlh,  A.merk_type,
										A.jns_trn, A.no_dsr_mts, A.kd_sat,A.rph_sat,A.jns_trn  order by  A.tgl_perlh");																						
		 } else if ($kdBarang != '' && ($tglAwal =='' || $tglAkhir ==''))
		 {
		   
		   $where[] = $kdBarang;
		   $result = $db->fetchAll("select A.kd_brg, B.ur_sskel, C.kd_perk,A.tgl_buku, A.Kondisi, A.tgl_perlh, A.merk_type,
										A.jns_trn, A.no_dsr_mts, A.kd_sat,
										A.rph_sat, sum(A.kuantitas) as kuantitas,(A.rph_sat*sum(A.kuantitas)) as rph_aset,
										case when A.kondisi = '1' then sum(A.kuantitas) END as Baik,
										case when A.kondisi = '2' then sum(A.kuantitas) END as RusakRingan,
										case when A.kondisi = '3' then sum(A.kuantitas) END as RusakBerat,
										case when A.jns_trn in ('100','101','102','103','104','105','106','107','202')  then 'T' END as kodet,
										case when A.jns_trn in ('201','301','302','303','304') then 'K' END as kodek,
										case when A.jns_trn in ('204','305') then 'Ka' END as kodeka,
										case when A.jns_trn in ('203') then 'Kk' END  as kodekk
										from e_sabm_t_master_tm A,e_ast_sskel_0_tr B, e_ast_bid_aset_tr C
										where substr(A.kd_brg,1,1) = B.kd_gol
										and substr(A.kd_brg,2,2) = B.kd_bid 
										and substr(A.kd_brg,4,2) = B.kd_kel
										and substr(A.kd_brg,6,2) = B.kd_skel
										and substr(A.kd_brg,8,3) = B.kd_sskel 	
										and substr(A.kd_brg,1,1) = C.kd_gol
							            and substr(A.kd_brg,2,2) = C.kd_bid 										
										and a.kd_brg = ?
										group by A.kd_brg, B.ur_sskel, C.kd_perk,A.tgl_buku, A.Kondisi, A.tgl_perlh,  A.merk_type,
										A.jns_trn, A.no_dsr_mts, A.kd_sat,A.rph_sat,A.jns_trn  order by A.tgl_perlh",$where);	
		} else if ($kdBarang == '' && $tglAwal !='' && $tglAkhir !='')		
		{
		
		     
			 $where[] = $tglAwal;
			 $where[] = $tglAkhir;
		     $result = $db->fetchAll("select A.kd_brg, B.ur_sskel, C.kd_perk,A.tgl_buku, A.Kondisi, A.tgl_perlh, A.merk_type,
										A.jns_trn, A.no_dsr_mts, A.kd_sat,
										A.rph_sat, sum(A.kuantitas) as kuantitas,(A.rph_sat*sum(A.kuantitas)) as rph_aset,
										case when A.kondisi = '1' then sum(A.kuantitas) END as Baik,
										case when A.kondisi = '2' then sum(A.kuantitas) END as RusakRingan,
										case when A.kondisi = '3' then sum(A.kuantitas) END as RusakBerat,
										case when A.jns_trn in ('100','101','102','103','104','105','106','107','202')  then 'T' END as kodet,
										case when A.jns_trn in ('201','301','302','303','304') then 'K' END as kodek,
										case when A.jns_trn in ('204','305') then 'Ka' END as kodeka,
										case when A.jns_trn in ('203') then 'Kk' END  as kodekk
										from e_sabm_t_master_tm A,e_ast_sskel_0_tr B, e_ast_bid_aset_tr C
										where substr(A.kd_brg,1,1) = B.kd_gol
										and substr(A.kd_brg,2,2) = B.kd_bid 
										and substr(A.kd_brg,4,2) = B.kd_kel
										and substr(A.kd_brg,6,2) = B.kd_skel
										and substr(A.kd_brg,8,3) = B.kd_sskel 	
										and substr(A.kd_brg,1,1) = C.kd_gol
							            and substr(A.kd_brg,2,2) = C.kd_bid 
										and a.tgl_perlh between ? and ?
										group by A.kd_brg, B.ur_sskel, C.kd_perk,A.tgl_buku, A.Kondisi, A.tgl_perlh,  A.merk_type,
										A.jns_trn, A.no_dsr_mts, A.kd_sat,A.rph_sat,A.jns_trn  order by  A.tgl_perlh",$where);											
		} else if ($kdBarang != '' && $tglAwal !='' && $tglAkhir !='')				
		{
			
			$where[] = $kdBarang;
			$where[] = $tglAwal;
			$where[] = $tglAkhir;
		    $result = $db->fetchAll("select A.kd_brg, B.ur_sskel, C.kd_perk, A.tgl_buku, A.Kondisi, A.tgl_perlh, A.merk_type,
										A.jns_trn, A.no_dsr_mts, A.kd_sat,
										A.rph_sat, sum(A.kuantitas) as kuantitas,(A.rph_sat*sum(A.kuantitas)) as rph_aset,
										case when A.kondisi = '1' then sum(A.kuantitas) END as Baik,
										case when A.kondisi = '2' then sum(A.kuantitas) END as RusakRingan,
										case when A.kondisi = '3' then sum(A.kuantitas) END as RusakBerat,
										case when A.jns_trn in ('100','101','102','103','104','105','106','107','202')  then 'T' END as kodet,
										case when A.jns_trn in ('201','301','302','303','304') then 'K' END as kodek,
										case when A.jns_trn in ('204','305') then 'Ka' END as kodeka,
										case when A.jns_trn in ('203') then 'Kk' END  as kodekk
										from e_sabm_t_master_tm A,e_ast_sskel_0_tr B, e_ast_bid_aset_tr C
										where substr(A.kd_brg,1,1) = B.kd_gol
										and substr(A.kd_brg,2,2) = B.kd_bid 
										and substr(A.kd_brg,4,2) = B.kd_kel
										and substr(A.kd_brg,6,2) = B.kd_skel
										and substr(A.kd_brg,8,3) = B.kd_sskel 
										and substr(A.kd_brg,1,1) = C.kd_gol
							            and substr(A.kd_brg,2,2) = C.kd_bid 										
										and a.kd_brg = ?
										and a.tgl_perlh between ? and ?
										group by A.kd_brg, B.ur_sskel, C.kd_perk, A.tgl_buku, A.Kondisi, A.tgl_perlh,  A.merk_type,
										A.jns_trn, A.no_dsr_mts, A.kd_sat,A.rph_sat,A.jns_trn  order by  A.tgl_perlh",$where);											
		}
		 $jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		   
		   $hasilAkhir[$j] = array("tgl_buku"           =>(string)$result[$j]->tgl_buku,
								   "tgl_perlh"       	=>(string)$result[$j]->tgl_perlh,
								   "ur_sskel"       	=>(string)$result[$j]->ur_sskel,
								   "kd_perk"       		=>(string)$result[$j]->kd_perk,
								   "no_aset"            =>(string)$result[$j]->no_aset,
								   "merk_type"     		=>(string)$result[$j]->merk_type,
								   "jns_trn"           	=>(string)$result[$j]->jns_trn,
								   "no_dsr_mts"         =>(string)$result[$j]->no_dsr_mts,
								   "kuantitas"          =>(string)$result[$j]->kuantitas,
								   "kd_sat"          	=>(string)$result[$j]->kd_sat,
	                               "rph_sat"         	=>(string)$result[$j]->rph_sat,
								   "rph_aset"         	=>(string)$result[$j]->rph_aset,
								   "Baik"   			=>(string)$result[$j]->baik,
								   "RusakRingan"        =>(string)$result[$j]->rusakringan,								   
								   "RusakBerat"        	=>(string)$result[$j]->rusakberat,
								   "kodet"        		=>(string)$result[$j]->kodet,
								   "kodek"        		=>(string)$result[$j]->kodek,
								   "kodeka"        		=>(string)$result[$j]->kodeka,
								   "kodekk"        		=>(string)$result[$j]->kodekk
								   );
								   
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
   // InaL 12-05-2008 : Akhir
	
	//erna 20/08/08
	
	public function getInventarisintrakomp($pageNumber, $itemPerPage,$kdBarang, $tglAwal, $tglAkhir){
	
	  $registry = Zend_Registry::getInstance();
	  $db = $registry->get('db');
        try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		
		
		if(($pageNumber==0) && ($itemPerPage==0))
		 {
		    
			if ($kdBarang== '' && ($tglAwal =='' || $tglAkhir ==''))
			{
		     
		
			
			$result = $db->fetchAll("select A.kd_brg, B.ur_sskel, C.kd_perk, A.tgl_buku, A.Kondisi, A.tgl_perlh, A.merk_type,
										A.jns_trn, A.no_dsr_mts, A.kd_sat,
										A.rph_sat, sum(A.kuantitas) as kuantitas,(A.rph_sat*sum(A.kuantitas)) as rph_aset,
										case when A.kondisi = '1' then sum(A.kuantitas) END as Baik,
										case when A.kondisi = '2' then sum(A.kuantitas) END as RusakRingan,
										case when A.kondisi = '3' then sum(A.kuantitas) END as RusakBerat,
										case when A.jns_trn in ('100','101','102','103','104','105','106','107','202')  then 'T' END as kodet,
										case when A.jns_trn in ('201','301','302','303','304') then 'K' END as kodek,
										case when A.jns_trn in ('204','305') then 'Ka' END as kodeka,
										case when A.jns_trn in ('203') then 'Kk' END  as kodekk
										from e_sabm_t_master_tm A, e_ast_sskel_0_tr B, e_ast_bid_aset_tr C
										where substr(A.kd_brg,1,1) = B.kd_gol
										and substr(A.kd_brg,2,2) = B.kd_bid 
										and substr(A.kd_brg,4,2) = B.kd_kel
										and substr(A.kd_brg,6,2) = B.kd_skel
										and substr(A.kd_brg,8,3) = B.kd_sskel 
										and substr(A.kd_brg,1,1) = C.kd_gol
							            and substr(A.kd_brg,2,2) = C.kd_bid 										
										group by A.kd_brg, B.ur_sskel, C.kd_perk, A.tgl_buku, A.Kondisi, A.tgl_perlh,  A.merk_type,
										A.jns_trn, A.no_dsr_mts, A.kd_sat,A.rph_sat,A.jns_trn  order by  A.tgl_perlh");																						
			
			
			} else if ($kdBarang != '' && ($tglAwal =='' || $tglAkhir ==''))
			{
			$where[] = $kdBarang;
			$result = $db->fetchAll("select A.kd_brg, B.ur_sskel, C.kd_perk,A.tgl_buku, A.Kondisi, A.tgl_perlh, A.merk_type,
										A.jns_trn, A.no_dsr_mts, A.kd_sat,
										A.rph_sat, sum(A.kuantitas) as kuantitas,(A.rph_sat*sum(A.kuantitas)) as rph_aset,
										case when A.kondisi = '1' then sum(A.kuantitas) END as Baik,
										case when A.kondisi = '2' then sum(A.kuantitas) END as RusakRingan,
										case when A.kondisi = '3' then sum(A.kuantitas) END as RusakBerat,
										case when A.jns_trn in ('100','101','102','103','104','105','106','107','202')  then 'T' END as kodet,
										case when A.jns_trn in ('201','301','302','303','304') then 'K' END as kodek,
										case when A.jns_trn in ('204','305') then 'Ka' END as kodeka,
										case when A.jns_trn in ('203') then 'Kk' END  as kodekk
										from e_sabm_t_master_tm A,e_ast_sskel_0_tr B, e_ast_bid_aset_tr C
										where substr(A.kd_brg,1,1) = B.kd_gol
										and substr(A.kd_brg,2,2) = B.kd_bid 
										and substr(A.kd_brg,4,2) = B.kd_kel
										and substr(A.kd_brg,6,2) = B.kd_skel
										and substr(A.kd_brg,8,3) = B.kd_sskel 	
										and substr(A.kd_brg,1,1) = C.kd_gol
							            and substr(A.kd_brg,2,2) = C.kd_bid 										
										and a.kd_brg = ?
										group by A.kd_brg, B.ur_sskel, C.kd_perk,A.tgl_buku, A.Kondisi, A.tgl_perlh,  A.merk_type,
										A.jns_trn, A.no_dsr_mts, A.kd_sat,A.rph_sat,A.jns_trn  order by A.tgl_perlh",$where);	
			
			
			} else if ($kdBarang == '' && $tglAwal !='' && $tglAkhir !='')		
			{
		
			 $where[] = $tglAwal;
			 $where[] = $tglAkhir;
		     $result = $db->fetchAll("select A.kd_brg, B.ur_sskel, C.kd_perk,A.tgl_buku, A.Kondisi, A.tgl_perlh, A.merk_type,
										A.jns_trn, A.no_dsr_mts, A.kd_sat,
										A.rph_sat, sum(A.kuantitas) as kuantitas,(A.rph_sat*sum(A.kuantitas)) as rph_aset,
										case when A.kondisi = '1' then sum(A.kuantitas) END as Baik,
										case when A.kondisi = '2' then sum(A.kuantitas) END as RusakRingan,
										case when A.kondisi = '3' then sum(A.kuantitas) END as RusakBerat,
										case when A.jns_trn in ('100','101','102','103','104','105','106','107','202')  then 'T' END as kodet,
										case when A.jns_trn in ('201','301','302','303','304') then 'K' END as kodek,
										case when A.jns_trn in ('204','305') then 'Ka' END as kodeka,
										case when A.jns_trn in ('203') then 'Kk' END  as kodekk
										from e_sabm_t_master_tm A,e_ast_sskel_0_tr B, e_ast_bid_aset_tr C
										where substr(A.kd_brg,1,1) = B.kd_gol
										and substr(A.kd_brg,2,2) = B.kd_bid 
										and substr(A.kd_brg,4,2) = B.kd_kel
										and substr(A.kd_brg,6,2) = B.kd_skel
										and substr(A.kd_brg,8,3) = B.kd_sskel 	
										and substr(A.kd_brg,1,1) = C.kd_gol
							            and substr(A.kd_brg,2,2) = C.kd_bid 
										and a.tgl_perlh between ? and ?
										group by A.kd_brg, B.ur_sskel, C.kd_perk,A.tgl_buku, A.Kondisi, A.tgl_perlh,  A.merk_type,
										A.jns_trn, A.no_dsr_mts, A.kd_sat,A.rph_sat,A.jns_trn  order by  A.tgl_perlh",$where);											
			
			} else if ($kdBarang != '' && $tglAwal !='' && $tglAkhir !='')				
			{
		
			$where[] = $kdBarang;
			$where[] = $tglAwal;
			$where[] = $tglAkhir;
		    $result = $db->fetchAll("select A.kd_brg, B.ur_sskel, C.kd_perk, A.tgl_buku, A.Kondisi, A.tgl_perlh, A.merk_type,
										A.jns_trn, A.no_dsr_mts, A.kd_sat,
										A.rph_sat, sum(A.kuantitas) as kuantitas,(A.rph_sat*sum(A.kuantitas)) as rph_aset,
										case when A.kondisi = '1' then sum(A.kuantitas) END as Baik,
										case when A.kondisi = '2' then sum(A.kuantitas) END as RusakRingan,
										case when A.kondisi = '3' then sum(A.kuantitas) END as RusakBerat,
										case when A.jns_trn in ('100','101','102','103','104','105','106','107','202')  then 'T' END as kodet,
										case when A.jns_trn in ('201','301','302','303','304') then 'K' END as kodek,
										case when A.jns_trn in ('204','305') then 'Ka' END as kodeka,
										case when A.jns_trn in ('203') then 'Kk' END  as kodekk
										from e_sabm_t_master_tm A,e_ast_sskel_0_tr B, e_ast_bid_aset_tr C
										where substr(A.kd_brg,1,1) = B.kd_gol
										and substr(A.kd_brg,2,2) = B.kd_bid 
										and substr(A.kd_brg,4,2) = B.kd_kel
										and substr(A.kd_brg,6,2) = B.kd_skel
										and substr(A.kd_brg,8,3) = B.kd_sskel 
										and substr(A.kd_brg,1,1) = C.kd_gol
							            and substr(A.kd_brg,2,2) = C.kd_bid 										
										and a.kd_brg = ?
										and a.tgl_perlh between ? and ?
										group by A.kd_brg, B.ur_sskel, C.kd_perk, A.tgl_buku, A.Kondisi, A.tgl_perlh,  A.merk_type,
										A.jns_trn, A.no_dsr_mts, A.kd_sat,A.rph_sat,A.jns_trn  order by  A.tgl_perlh",$where);											
			
			}
	         
			$hasilAkhir = count($result);
			return $hasilAkhir;
		}
		//end count==
		else
		{
		     
		     $xLimit=$itemPerPage;
	  		 $xOffset=($pageNumber-1)*$itemPerPage;	
	     
							
		 if ($kdBarang== '' && ($tglAwal =='' || $tglAkhir ==''))
			{
			$result = $db->fetchAll("select A.kd_brg, B.ur_sskel, C.kd_perk, A.tgl_buku, A.Kondisi, A.tgl_perlh, A.merk_type,
										A.jns_trn, A.no_dsr_mts, A.kd_sat,
										A.rph_sat, sum(A.kuantitas) as kuantitas,(A.rph_sat*sum(A.kuantitas)) as rph_aset,
										case when A.kondisi = '1' then sum(A.kuantitas) END as Baik,
										case when A.kondisi = '2' then sum(A.kuantitas) END as RusakRingan,
										case when A.kondisi = '3' then sum(A.kuantitas) END as RusakBerat,
										case when A.jns_trn in ('100','101','102','103','104','105','106','107','202')  then 'T' END as kodet,
										case when A.jns_trn in ('201','301','302','303','304') then 'K' END as kodek,
										case when A.jns_trn in ('204','305') then 'Ka' END as kodeka,
										case when A.jns_trn in ('203') then 'Kk' END  as kodekk
										from e_sabm_t_master_tm A, e_ast_sskel_0_tr B, e_ast_bid_aset_tr C
										where substr(A.kd_brg,1,1) = B.kd_gol
										and substr(A.kd_brg,2,2) = B.kd_bid 
										and substr(A.kd_brg,4,2) = B.kd_kel
										and substr(A.kd_brg,6,2) = B.kd_skel
										and substr(A.kd_brg,8,3) = B.kd_sskel 
										and substr(A.kd_brg,1,1) = C.kd_gol
							            and substr(A.kd_brg,2,2) = C.kd_bid 										
										group by A.kd_brg, B.ur_sskel, C.kd_perk, A.tgl_buku, A.Kondisi, A.tgl_perlh,  A.merk_type,
										A.jns_trn, A.no_dsr_mts, A.kd_sat,A.rph_sat,A.jns_trn  order by  A.tgl_perlh limit $xLimit offset $xOffset");																						
			} else if ($kdBarang != '' && ($tglAwal =='' || $tglAkhir ==''))
			{
			$where[] = $kdBarang;
			$result = $db->fetchAll("select A.kd_brg, B.ur_sskel, C.kd_perk,A.tgl_buku, A.Kondisi, A.tgl_perlh, A.merk_type,
										A.jns_trn, A.no_dsr_mts, A.kd_sat,
										A.rph_sat, sum(A.kuantitas) as kuantitas,(A.rph_sat*sum(A.kuantitas)) as rph_aset,
										case when A.kondisi = '1' then sum(A.kuantitas) END as Baik,
										case when A.kondisi = '2' then sum(A.kuantitas) END as RusakRingan,
										case when A.kondisi = '3' then sum(A.kuantitas) END as RusakBerat,
										case when A.jns_trn in ('100','101','102','103','104','105','106','107','202')  then 'T' END as kodet,
										case when A.jns_trn in ('201','301','302','303','304') then 'K' END as kodek,
										case when A.jns_trn in ('204','305') then 'Ka' END as kodeka,
										case when A.jns_trn in ('203') then 'Kk' END  as kodekk
										from e_sabm_t_master_tm A,e_ast_sskel_0_tr B, e_ast_bid_aset_tr C
										where substr(A.kd_brg,1,1) = B.kd_gol
										and substr(A.kd_brg,2,2) = B.kd_bid 
										and substr(A.kd_brg,4,2) = B.kd_kel
										and substr(A.kd_brg,6,2) = B.kd_skel
										and substr(A.kd_brg,8,3) = B.kd_sskel 	
										and substr(A.kd_brg,1,1) = C.kd_gol
							            and substr(A.kd_brg,2,2) = C.kd_bid 										
										and a.kd_brg = ?
										group by A.kd_brg, B.ur_sskel, C.kd_perk,A.tgl_buku, A.Kondisi, A.tgl_perlh,  A.merk_type,
										A.jns_trn, A.no_dsr_mts, A.kd_sat,A.rph_sat,A.jns_trn  order by A.tgl_perlh limit $xLimit offset $xOffset",$where);	
			} else if ($kdBarang == '' && $tglAwal !='' && $tglAkhir !='')		
			{
			 $where[] = $tglAwal;
			 $where[] = $tglAkhir;
		     $result = $db->fetchAll("select A.kd_brg, B.ur_sskel, C.kd_perk,A.tgl_buku, A.Kondisi, A.tgl_perlh, A.merk_type,
										A.jns_trn, A.no_dsr_mts, A.kd_sat,
										A.rph_sat, sum(A.kuantitas) as kuantitas,(A.rph_sat*sum(A.kuantitas)) as rph_aset,
										case when A.kondisi = '1' then sum(A.kuantitas) END as Baik,
										case when A.kondisi = '2' then sum(A.kuantitas) END as RusakRingan,
										case when A.kondisi = '3' then sum(A.kuantitas) END as RusakBerat,
										case when A.jns_trn in ('100','101','102','103','104','105','106','107','202')  then 'T' END as kodet,
										case when A.jns_trn in ('201','301','302','303','304') then 'K' END as kodek,
										case when A.jns_trn in ('204','305') then 'Ka' END as kodeka,
										case when A.jns_trn in ('203') then 'Kk' END  as kodekk
										from e_sabm_t_master_tm A,e_ast_sskel_0_tr B, e_ast_bid_aset_tr C
										where substr(A.kd_brg,1,1) = B.kd_gol
										and substr(A.kd_brg,2,2) = B.kd_bid 
										and substr(A.kd_brg,4,2) = B.kd_kel
										and substr(A.kd_brg,6,2) = B.kd_skel
										and substr(A.kd_brg,8,3) = B.kd_sskel 	
										and substr(A.kd_brg,1,1) = C.kd_gol
							            and substr(A.kd_brg,2,2) = C.kd_bid 
										and a.tgl_perlh between ? and ?
										group by A.kd_brg, B.ur_sskel, C.kd_perk,A.tgl_buku, A.Kondisi, A.tgl_perlh,  A.merk_type,
										A.jns_trn, A.no_dsr_mts, A.kd_sat,A.rph_sat,A.jns_trn  order by  A.tgl_perlh limit $xLimit offset $xOffset",$where);											
			} else if ($kdBarang != '' && $tglAwal !='' && $tglAkhir !='')				
			{
			
			$where[] = $kdBarang;
			$where[] = $tglAwal;
			$where[] = $tglAkhir;
		    $result = $db->fetchAll("select A.kd_brg, B.ur_sskel, C.kd_perk, A.tgl_buku, A.Kondisi, A.tgl_perlh, A.merk_type,
										A.jns_trn, A.no_dsr_mts, A.kd_sat,
										A.rph_sat, sum(A.kuantitas) as kuantitas,(A.rph_sat*sum(A.kuantitas)) as rph_aset,
										case when A.kondisi = '1' then sum(A.kuantitas) END as Baik,
										case when A.kondisi = '2' then sum(A.kuantitas) END as RusakRingan,
										case when A.kondisi = '3' then sum(A.kuantitas) END as RusakBerat,
										case when A.jns_trn in ('100','101','102','103','104','105','106','107','202')  then 'T' END as kodet,
										case when A.jns_trn in ('201','301','302','303','304') then 'K' END as kodek,
										case when A.jns_trn in ('204','305') then 'Ka' END as kodeka,
										case when A.jns_trn in ('203') then 'Kk' END  as kodekk
										from e_sabm_t_master_tm A,e_ast_sskel_0_tr B, e_ast_bid_aset_tr C
										where substr(A.kd_brg,1,1) = B.kd_gol
										and substr(A.kd_brg,2,2) = B.kd_bid 
										and substr(A.kd_brg,4,2) = B.kd_kel
										and substr(A.kd_brg,6,2) = B.kd_skel
										and substr(A.kd_brg,8,3) = B.kd_sskel 
										and substr(A.kd_brg,1,1) = C.kd_gol
							            and substr(A.kd_brg,2,2) = C.kd_bid 										
										and a.kd_brg = ?
										and a.tgl_perlh between ? and ?
										group by A.kd_brg, B.ur_sskel, C.kd_perk, A.tgl_buku, A.Kondisi, A.tgl_perlh,  A.merk_type,
										A.jns_trn, A.no_dsr_mts, A.kd_sat,A.rph_sat,A.jns_trn  order by  A.tgl_perlh limit $xLimit offset $xOffset",$where);											
			}	
         } 			
			$jmlResult = count($result);
			
			if($jmlResult>0){
		    for ($j = 0; $j < $jmlResult; $j++) {
            $hasilAkhir[$j] = array("tgl_buku"           =>(string)$result[$j]->tgl_buku,
								   "tgl_perlh"       	=>(string)$result[$j]->tgl_perlh,
								   "ur_sskel"       	=>(string)$result[$j]->ur_sskel,
								   "kd_perk"       		=>(string)$result[$j]->kd_perk,
								   "no_aset"            =>(string)$result[$j]->no_aset,
								   "merk_type"     		=>(string)$result[$j]->merk_type,
								   "jns_trn"           	=>(string)$result[$j]->jns_trn,
								   "no_dsr_mts"         =>(string)$result[$j]->no_dsr_mts,
								   "kuantitas"          =>(string)$result[$j]->kuantitas,
								   "kd_sat"          	=>(string)$result[$j]->kd_sat,
	                               "rph_sat"         	=>(string)$result[$j]->rph_sat,
								   "rph_aset"         	=>(string)$result[$j]->rph_aset,
								   "Baik"   			=>(string)$result[$j]->baik,
								   "RusakRingan"        =>(string)$result[$j]->rusakringan,								   
								   "RusakBerat"        	=>(string)$result[$j]->rusakberat,
								   "kodet"        		=>(string)$result[$j]->kodet,
								   "kodek"        		=>(string)$result[$j]->kodek,
								   "kodeka"        		=>(string)$result[$j]->kodeka,
								   "kodekk"        		=>(string)$result[$j]->kodekk);
			}
			return $hasilAkhir;
        }

      
	     // return $hasilAkhir;
	    }catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	
	
}		
?>