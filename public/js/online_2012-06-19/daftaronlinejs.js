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
				if((!c_posisi_jabatan.value)){cekVal = sdmValidasiData(astr,"c_posisi_jabatan","Posisi Jabatan harus diisi....!");return false;break;}
				if((!c_wil_pengadilan.value)){cekVal = sdmValidasiData(astr,"c_wil_pengadilan","Wilayah harus diisi....!");return false;break;}				
				if((!n_pendaftar.value)){cekVal = sdmValidasiData(astr,"n_pendaftar","Nama Pendaftar harus diisi....!");return false;break;}
				if((!c_pend.value)){cekVal = sdmValidasiData(astr,"c_pend","Pendidikan harus diisi....!");return false;break;}				
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
