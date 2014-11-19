function insertReg(){
	jQuery("#formdaftaronline").ajaxForm(maintain_daftarreg);
}
var maintain_daftarreg = {
	target:'#verifikasi',
	beforeSubmit: function() {		
		aobj= document.formdaftaronline;
		astr= "document.formdaftaronline";
		var cekVal="";
		with(aobj){      
			var Proceed = 1;
			while (Proceed == 1)
			{
				/*sdmValidasiData (navigation.js)*/

                if((!i_ktp.value)){cekVal = sdmValidasiData(astr,"i_ktp","Nomor KTP harus diisi....!");return false;break;}
                if((!n_password.value)){cekVal = sdmValidasiData(astr,"n_password","Password harus diisi....!");return false;break;}
                if((!n_pendaftar.value)){cekVal = sdmValidasiData(astr,"n_pendaftar","Nama harus diisi....!");return false;break;}
                if((!c_golongan_darah.value)){cekVal = sdmValidasiData(astr,"c_golongan_darah","Golongan Darah harus diisi....!");return false;break;}
                if((!c_statusnikah.value)){cekVal = sdmValidasiData(astr,"c_statusnikah","Status Menikah harus diisi....!");return false;break;}
                if((!c_jeniskelamin.value)){cekVal = sdmValidasiData(astr,"c_jeniskelamin","Jenis Kelamin harus diisi....!");return false;break;}
                if((!c_agama.value)){cekVal = sdmValidasiData(astr,"c_agama","Agama harus diisi....!");return false;break;}
                if((!c_pend.value)){cekVal = sdmValidasiData(astr,"c_pend","Pendidikan harus diisi....!");return false;break;}
                if((!c_propinsi_lahir.value)){cekVal = sdmValidasiData(astr,"c_propinsi_lahir","Propinsi Lahir harus diisi....!");return false;break;}
                if((!a_kota_lahir.value)){cekVal = sdmValidasiData(astr,"a_kota_lahir","Kota Lahir harus diisi....!");return false;break;}
                if((!d_lahir.value)){cekVal = sdmValidasiData(astr,"d_lahir","Tanggal Lahir harus diisi....!");return false;break;}
                if((!a_rumah.value)){cekVal = sdmValidasiData(astr,"a_rumah","Alamat Jalan harus diisi....!");return false;break;}
                if((!a_rt.value)){cekVal = sdmValidasiData(astr,"a_rt","Alamat RT harus diisi....!");return false;break;}
                if((!a_rw.value)){cekVal = sdmValidasiData(astr,"a_rw","Alamat RW harus diisi....!");return false;break;}
                if((!a_kelurahan.value)){cekVal = sdmValidasiData(astr,"a_kelurahan","Alamat Keluarahan harus diisi....!");return false;break;}
                if((!a_kecamatan.value)){cekVal = sdmValidasiData(astr,"a_kecamatan","Alamat Kecamatan harus diisi....!");return false;break;}
                if((!a_kota.value)){cekVal = sdmValidasiData(astr,"a_kota","Alamat Kota harus diisi....!");return false;break;}
                if((!a_kodepos.value)){cekVal = sdmValidasiData(astr,"a_kodepos","Alamat Kodepos harus diisi....!");return false;break;}
                if((!verifikasi.value)){cekVal = sdmValidasiData(astr,"verifikasi","Verifikasi harus diisi....!");return false;break;}
                if((!n_password2.value )){cekVal = sdmValidasiData(astr,"n_password2","Konfirmasi Password harus diisi....!");return false;break;}
                if((n_password2.value !=n_password.value)){cekVal = sdmValidasiData(astr,"n_password2","Password1 dan Password2 tidak sesuai....!");return false;break;}
                if((n_password.value)){                
                    var str=n_password.value;
                    if (str.length<=7){cekVal = sdmValidasiData(astr,"n_password","Panjang Karakter Password harus minimal 8 karakter....!");return false;break;}
                }     

                if((n_password2.value)){                
                    var strx=n_password2.value;
                    if (strx.length<=7){cekVal = sdmValidasiData(astr,"n_password","Panjang Karakter Password harus minimal 8 karakter....!");return false;break;}
                } 
                

                
                if((!c_posisi_jabatan.value)){cekVal = sdmValidasiData(astr,"c_posisi_jabatan","Posisi Jabatan harus diisi....!");return false;break;}
                if((!c_wil_pengadilan.value)){cekVal = sdmValidasiData(astr,"c_wil_pengadilan","Wilayah Pengadilan harus diisi....!");return false;break;}
                if((!n_pend_jurusan.value)){cekVal = sdmValidasiData(astr,"n_pend_jurusan","Jurusan Pendidikan harus diisi....!");return false;break;}
                if((!n_pend_lembaga.value)){cekVal = sdmValidasiData(astr,"n_pend_lembaga","Perguruan Tinggi harus diisi....!");return false;break;}
                if((!c_pend_akreditasi.value)){cekVal = sdmValidasiData(astr,"c_pend_akreditasi","Akreditasi harus diisi....!");return false;break;}
                if((!d_pend_mulai.value)){cekVal = sdmValidasiData(astr,"d_pend_mulai","Mulai Pendidikan harus diisi....!");return false;break;}
                if((!d_pend_akhir.value)){cekVal = sdmValidasiData(astr,"d_pend_akhir","Akhir Pendidikan harus diisi....!");return false;break;}
                if((!i_pend_ipk.value)){cekVal = sdmValidasiData(astr,"i_pend_ipk","Nilai IPK harus diisi....!");return false;break;}
                if((!i_pend_ijazah.value)){cekVal = sdmValidasiData(astr,"i_pend_ijazah","Nomor Ijazah harus diisi....!");return false;break;}
                if((!d_pend_ijazah.value)){cekVal = sdmValidasiData(astr,"d_pend_ijazah","Tanggal Ijazah harus diisi....!");return false;break;}  
                
                if((!q_tinggibdn.value)){cekVal = sdmValidasiData(astr,"q_tinggibdn","Tinggi Badan harus diisi....!");return false;break;} 
                if((!q_beratbdn.value)){cekVal = sdmValidasiData(astr,"q_beratbdn","Berat Badan harus diisi....!");return false;break;} 
                if((!n_rambut.value)){cekVal = sdmValidasiData(astr,"n_rambut","Rambut harus diisi....!");return false;break;} 
                if((!n_btkmuka.value)){cekVal = sdmValidasiData(astr,"n_btkmuka","Bentuk Muka harus diisi....!");return false;break;} 
                if((!n_warnakulit.value)){cekVal = sdmValidasiData(astr,"n_warnakulit","Warna Kulit harus diisi....!");return false;break;} 
                
                
                
                
                
                
				break;
			}
			
		}	
	}, 
	success:function(){
		location.href="#top";
		doCounter(5);
	},
	url: 'pendaftaranonlinemodule/pendaftaranonline/maintaindataregistrasi',
	type: 'post',
	resetForm: false
};




function insertDaftarOl(){
	jQuery("#formdaftaronline").ajaxForm(maintain_daftarol);
}
var maintain_daftarol = {
	target:'#tableview',
	beforeSubmit: function() {		
		aobj= document.formdaftaronline;
		astr= "document.formdaftaronline";
		var cekVal="";
		with(aobj){      
			var Proceed = 1;
			while (Proceed == 1)
			{
				/*sdmValidasiData (navigation.js)*/
                if((!i_ktp.value)){cekVal = sdmValidasiData(astr,"i_ktp","Nomor KTP harus diisi....!");return false;break;}
                if((!n_pendaftar.value)){cekVal = sdmValidasiData(astr,"n_pendaftar","Nama harus diisi....!");return false;break;}
                if((!c_golongan_darah.value)){cekVal = sdmValidasiData(astr,"c_golongan_darah","Golongan Darah harus diisi....!");return false;break;}
                if((!c_statusnikah.value)){cekVal = sdmValidasiData(astr,"c_statusnikah","Status Menikah harus diisi....!");return false;break;}
                if((!c_jeniskelamin.value)){cekVal = sdmValidasiData(astr,"c_jeniskelamin","Jenis Kelamin harus diisi....!");return false;break;}
                if((!c_agama.value)){cekVal = sdmValidasiData(astr,"c_agama","Agama harus diisi....!");return false;break;}
                if((!c_pend.value)){cekVal = sdmValidasiData(astr,"c_pend","Pendidikan harus diisi....!");return false;break;}
                if((!c_propinsi_lahir.value)){cekVal = sdmValidasiData(astr,"c_propinsi_lahir","Propinsi Lahir harus diisi....!");return false;break;}
                if((!a_kota_lahir.value)){cekVal = sdmValidasiData(astr,"a_kota_lahir","Kota Lahir harus diisi....!");return false;break;}
                if((!d_lahir.value)){cekVal = sdmValidasiData(astr,"d_lahir","Tanggal Lahir harus diisi....!");return false;break;}
                if((!a_rumah.value)){cekVal = sdmValidasiData(astr,"a_rumah","Alamat Jalan harus diisi....!");return false;break;}
                if((!a_rt.value)){cekVal = sdmValidasiData(astr,"a_rt","Alamat RT harus diisi....!");return false;break;}
                if((!a_rw.value)){cekVal = sdmValidasiData(astr,"a_rw","Alamat RW harus diisi....!");return false;break;}
                if((!a_kelurahan.value)){cekVal = sdmValidasiData(astr,"a_kelurahan","Alamat Keluarahan harus diisi....!");return false;break;}
                if((!a_kecamatan.value)){cekVal = sdmValidasiData(astr,"a_kecamatan","Alamat Kecamatan harus diisi....!");return false;break;}
                if((!a_kota.value)){cekVal = sdmValidasiData(astr,"a_kota","Alamat Kota harus diisi....!");return false;break;}
                if((!a_kodepos.value)){cekVal = sdmValidasiData(astr,"a_kodepos","Alamat Kodepos harus diisi....!");return false;break;}
                
                if((!c_posisi_jabatan.value)){cekVal = sdmValidasiData(astr,"c_posisi_jabatan","Posisi Jabatan harus diisi....!");return false;break;}
                if((!c_wil_pengadilan.value)){cekVal = sdmValidasiData(astr,"c_wil_pengadilan","Wilayah Pengadilan harus diisi....!");return false;break;}
                if((!n_pend_jurusan.value)){cekVal = sdmValidasiData(astr,"n_pend_jurusan","Jurusan Pendidikan harus diisi....!");return false;break;}
                if((!n_pend_lembaga.value)){cekVal = sdmValidasiData(astr,"n_pend_lembaga","Perguruan Tinggi harus diisi....!");return false;break;}
                if((!c_pend_akreditasi.value)){cekVal = sdmValidasiData(astr,"c_pend_akreditasi","Akreditasi harus diisi....!");return false;break;}
                if((!d_pend_mulai.value)){cekVal = sdmValidasiData(astr,"d_pend_mulai","Mulai Pendidikan harus diisi....!");return false;break;}
                if((!d_pend_akhir.value)){cekVal = sdmValidasiData(astr,"d_pend_akhir","Akhir Pendidikan harus diisi....!");return false;break;}
                if((!i_pend_ipk.value)){cekVal = sdmValidasiData(astr,"i_pend_ipk","Nilai IPK harus diisi....!");return false;break;}
                if((!i_pend_ijazah.value)){cekVal = sdmValidasiData(astr,"i_pend_ijazah","Nomor Ijazah harus diisi....!");return false;break;}
                if((!d_pend_ijazah.value)){cekVal = sdmValidasiData(astr,"d_pend_ijazah","Tanggal Ijazah harus diisi....!");return false;break;}
                
                 if((!q_tinggibdn.value)){cekVal = sdmValidasiData(astr,"q_tinggibdn","Tinggi Badan harus diisi....!");return false;break;} 
                if((!q_beratbdn.value)){cekVal = sdmValidasiData(astr,"q_beratbdn","Berat Badan harus diisi....!");return false;break;} 
                if((!n_rambut.value)){cekVal = sdmValidasiData(astr,"n_rambut","Rambut harus diisi....!");return false;break;} 
                if((!n_btkmuka.value)){cekVal = sdmValidasiData(astr,"n_btkmuka","Bentuk Muka harus diisi....!");return false;break;} 
                if((!n_warnakulit.value)){cekVal = sdmValidasiData(astr,"n_warnakulit","Warna Kulit harus diisi....!");return false;break;}                
                
				break;
			}
			
		}
		
	
	}, 
	success:function(){
		location.href="#top";
		doCounter(5);
	},
	url: 'pendaftaranonlinemodule/pendaftaranonline/maintaindata',
	type: 'post',
	resetForm: false
};

function cariData(){
	var i_ktp=document.getElementById('i_ktp').value;
	var url = 'pendaftaranonlinemodule/pendaftaranonline/daftaronline';	
	var param = {i_ktp:i_ktp};
		jQuery.get(url, param, function(data) {
			jQuery("#tableview").html(data);
		});
}
 
function setvalueuplfile(v,n,f) { 
	  if (f!="") {
	    var pass=false;
	    var af=f.split("/");
        var nval=eval("document.forms[0]."+n);
		var ext=v.substring(v.lastIndexOf(".")+1,v.length);
		if ((ext==f)||(ext=='png')||(ext=='gif')||(ext=='JPG')||(ext=='GIF')||(ext=='PNG')){ 
		document.forms[0].a_file.value=v; 
		}
		else{
		  alert ("Hanya untuk file berekstensi '"+f+"',jpg,gif,png ");
		  nval.value="";
		  document.forms[0].a_file.value=""; 
		  return;	  
		}
	  }
}
