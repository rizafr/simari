function sum_rekening (idtarget,jumlah,harga,idtotal,idkeseluruhan){
	var value,qty,qtyr,hargar,jumlahr,sumqty,seqqty,idqty;
	if (harga == '') { hargar = '1'; }
	else {hargar = harga;}
	if (jumlah == '') { jumlahr ='1'; }
	else {jumlahr = toNumber(jumlah);}
	//alert(hargar);
	//alert(jumlahr);
	//alert(toNumber(hargar));
	//qty = hargar * jumlahr;
	qty = toNumber(hargar) * jumlahr;
	//alert(qty);
	if (qty == '') value = 0;
	else value = qty;
	
	qtyr =  addSeparatorsNF(value, '.', ',', '.');
	//alert(qtyr);
	document.getElementById(idtarget).value = qtyr;
	sumqty =0;
	for (var j=1; j < idtotal; j++){
		idqty = 'hrg_total_' + j;
		
		seqqty = toNumber(document.getElementById(idqty).value);
		//alert(seqqty);
		if (seqqty == '') seqqty =0;
		sumqty = sumqty + eval(seqqty);
	
	}
	if (idkeseluruhan != '') {
		document.getElementById(idkeseluruhan).value = addSeparatorsNF(sumqty, '.', ',', '.');
	}	
	
	
}

function Selectcheckbox(f){
	
   for (var i in f) {
		f[i].checked=true;
   }
}
function selectByValue(sel, value) {
	var i = 0;
  var end = sel.options.length;
  while(i < end) {
	//alert(sel.options[i].value + '==' + value);
    if (sel.options[i].value == value) {
      sel.options[i].selected = true;
      sel.selectedIndex = i;
      return;
    }
    i++;
  }
}
function justmoney(e)
{
   
	var evtobj=window.event? event : e //distinguish between IE's explicit event object (window.event) and Firefox's implicit.
	var unicode = evtobj.keyCode? evtobj.keyCode : evtobj.charCode;
	   if (!((unicode >= 48 && unicode <= 57) || (unicode==97)  || (unicode==99)  || (unicode==44) || (unicode==46)  || (unicode==8) || (unicode==9) || (unicode==35)  || (unicode==36) || (unicode==37) || (unicode==39) || (unicode==118)))
    {
        return false;
    }
}
function justnumberanddotdash(e)
{
     var evtobj=window.event? event : e //distinguish between IE's explicit event object (window.event) and Firefox's implicit.
	var unicode = evtobj.keyCode? evtobj.keyCode : evtobj.charCode;
	//alert(unicode);	
	if (!((unicode >= 45 && unicode <= 57) || (unicode==97)  || (unicode==99)   || (unicode==8) || (unicode==9) || (unicode==35)  || (unicode==36) || (unicode==37) || (unicode==39) || (unicode==118)))
    {
        return false;
    }

}
function justnumberanddot(e)
{
     var evtobj=window.event? event : e //distinguish between IE's explicit event object (window.event) and Firefox's implicit.
	var unicode = evtobj.keyCode? evtobj.keyCode : evtobj.charCode;
	//alert(unicode);	
	if (!((unicode >= 48 && unicode <= 57) || (unicode==97)  || (unicode==99)  || (unicode==46)  || (unicode==8) || (unicode==9) || (unicode==35)  || (unicode==36) || (unicode==37) || (unicode==39) || (unicode==118)))
    {
        return false;
    }

}



function justphoneanddot(e)
{
	var evtobj=window.event? event : e //distinguish between IE's explicit event object (window.event) and Firefox's implicit.

	var unicode = evtobj.keyCode? evtobj.keyCode : evtobj.charCode;
	   if (!((unicode >= 48 && unicode <= 57) || (unicode==97)  || (unicode==99)  || (unicode==46) || (unicode==45) || (unicode==44) || (unicode==32) || (unicode==8) || (unicode==9) || (unicode==35)  || (unicode==36) || (unicode==37) || (unicode==39) || (unicode==118)))
    {
        return false;

    }

	
}
function justphone(e)
{
	var evtobj=window.event? event : e //distinguish between IE's explicit event object (window.event) and Firefox's implicit.

	var unicode = evtobj.keyCode? evtobj.keyCode : evtobj.charCode;
	   if (!((unicode >= 48 && unicode <= 57) || (unicode==97)  || (unicode==99)  || (unicode==46) || (unicode==45) || (unicode==44) || (unicode==32) || (unicode==8) || (unicode==9) || (unicode==35)  || (unicode==36) || (unicode==37) || (unicode==39) || (unicode==118)))
    {
        return false;

    }

	
}
function justphoneminus(e)
{
	var evtobj=window.event? event : e //distinguish between IE's explicit event object (window.event) and Firefox's implicit.

	var unicode = evtobj.keyCode? evtobj.keyCode : evtobj.charCode;
	   if (!((unicode >= 48 && unicode <= 57) ||  (unicode==97)  || (unicode==99)  || (unicode==44) || (unicode==32) || (unicode==8) || (unicode==9) || (unicode==35)  || (unicode==36) || (unicode==37) || (unicode==39) || (unicode==118)))
    {
        return false;

    }
}
function justnumberandkoma(e)
{
    var evtobj=window.event? event : e //distinguish between IE's explicit event object (window.event) and Firefox's implicit.

	var unicode = evtobj.keyCode? evtobj.keyCode : evtobj.charCode;
	   if (!((unicode >= 48 && unicode <= 57) || (unicode==97)  || (unicode==99)  || (unicode==46) || (unicode==45) || (unicode==44) || (unicode==32) || (unicode==8) || (unicode==9) || (unicode==35)  || (unicode==36) || (unicode==37) || (unicode==39) || (unicode==118)))
    {
        return false;

    }

}
function justnumberandash(e)
{
    var evtobj=window.event? event : e //distinguish between IE's explicit event object (window.event) and Firefox's implicit.

	var unicode = evtobj.keyCode? evtobj.keyCode : evtobj.charCode;
	   if (!((unicode >= 48 && unicode <= 57) || (unicode==97)  || (unicode==99)  || (unicode==46) || (unicode==45) || (unicode==8) || (unicode==9) || (unicode==35)  || (unicode==36) || (unicode==37) || (unicode==39) || (unicode==118)))
    {
        return false;
    }

}
function justnumberandgareng(e)
{
    var evtobj=window.event? event : e //distinguish between IE's explicit event object (window.event) and Firefox's implicit.

	var unicode = evtobj.keyCode? evtobj.keyCode : evtobj.charCode;

	   if (!((unicode >= 48 && unicode <= 57) || (unicode==97)  || (unicode==99)  || (unicode==47) || (unicode==32) || (unicode==8) || (unicode==9) || (unicode==35)  || (unicode==36) || (unicode==37) || (unicode==39) || (unicode==118)))
    {
        return false;

    }

}
function justnumber(e)
{
    var evtobj=window.event? event : e //distinguish between IE's explicit event object (window.event) and Firefox's implicit.
	var unicode = evtobj.keyCode? evtobj.keyCode : evtobj.charCode;
	
	   if (!((unicode >= 48 && unicode <= 57) || (unicode==97)  || (unicode==99)  || (unicode==8) || (unicode==9) || (unicode==35)  || (unicode==36) || (unicode==37) || (unicode==39) || (unicode==118)))
    {
        return false;
    }

}
function justromawi(e)
{
     var evtobj=window.event? event : e //distinguish between IE's explicit event object (window.event) and Firefox's implicit.
	var unicode = evtobj.keyCode? evtobj.keyCode : evtobj.charCode;
	   if (!((unicode==8) || (unicode==9) || (unicode==73)  || (unicode==76) || (unicode==67) || (unicode==88)  || (unicode==86)))
    {
        return false;
    }

}
function cekLength(f,lmt,psn,op){
	if (f.length < lmt){
		window.alert(psn + " harus " + op + ' ' + lmt + " digit");
		f.focus();
	}

}
function isEmail(string){
  var validFormatRegExp = /([A-Za-z0-9_\-.]+)\@([A-Za-z0-9_\-.]+)\.([A-Za-z][A-Za-z\-.]{2,5})/;
  var isValid = validFormatRegExp.test(string);
  return isValid;
}
function except_blank()
{
    if (event.keyCode==32)
    {
        event.returnValue = false;
    }
}

function replace(s, t, u) {
  /*
  http://www.rgagnon.com/jsdetails/js-0043.html
  **  Replace a token in a string
  **    s  string to be processed
  **    t  token to be found and removed
  **    u  token to be inserted
  **  returns new String
  */
  i = s.indexOf(t);
  r = "";
  if (i == -1) return s;
  r += s.substring(0,i) + u;
  if ( i + t.length < s.length)
    r += replace(s.substring(i + t.length, s.length), t, u);
  return r;
  }

function addSeparatorsNF(nStr, inD, outD, sep)
{
	
	//http://www.mredkj.com/javascript/nfbasic.html
	nStr += '';
	var dpos = nStr.indexOf(inD);
	var nStrEnd = '';
	if (dpos != -1) {
		nStrEnd = outD + nStr.substring(dpos + 1, nStr.length);
		nStr = nStr.substring(0, dpos);
	}
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(nStr)) {
		nStr = nStr.replace(rgx, '$1' + sep + '$2');
	}
	return nStr + nStrEnd;
	
	
	
}
function formatNumber(value){

	/**
	expired
	for backward compatibility only
	*/
	
	if (isNaN(value)) value = 0;
	value = replace(value,'.', '');
	//value = value * 1;
	//value = value.toFixed(2);
	
	//return 
	addSeparatorsNF(value, '.', ',', '.');
}
function toFloat(value){
	//current edit
	var str
	str = replace(value,',','.')
	return str
}
function toNumber(value){
	var str
	str = replace(value,'.','');
	str = replace(str,',','.')
	//alert(str);
	return str
}
function checkEmail(theEmail) {
	//private
	var rejectedDomain=new Array()
	var index=0;
	//rejectedDomain[index++]="hotmail"
	//rejectedDomain[index++]="rocketmail"
	//rejectedDomain[index++]="yahoo"
	//rejectedDomain[index++]="zdnetmail"

	var rejected=false
	var testresults=true
	var str=theEmail.value
	var filter=/^.+@.+\..{2,3}$/

	if (filter.test(str)){
		var tempstring = str.split("@")
		tempstring = tempstring[1].split(".")
		for (i=0; i<rejectedDomain.length; i++) {
			if (tempstring[0]==rejectedDomain[i])
			rejected=true
		}
		if (rejected) {
			var message="Please input a more official email address!\n"
			message += "The following addresses are not allowed:\n"
			for (i=0; i<rejectedDomain.length; i++) {
				message += "\t" + rejectedDomain[i] + "\n"
			}
			alert(message);
			testresults=false
		}
	} else {
		message="Format email harus benar!"		
		alert(message);
		testresults=false
	}
	return (testresults)
}

function isEmail(theEmail){
	//public
	if (theEmail.value != ''){
		if (!checkEmail(theEmail)) {
			//theEmail.focus();
			return false;	
		}else return true;
	}else	return true;
}
function isFloat(string){
  var validFormatRegExp = /^[\d*|\,]$/;
  var isValid = validFormatRegExp.test(string);
  return isValid;
}
function isInt(string){
  var validFormatRegExp = /^\d*$/;
  var isValid = validFormatRegExp.test(string);
  return isValid;
}
function ByteIsFloat(bite, mozila_bite){
	if (mozila_bite != null){
		return mozila_bite == 0 || mozila_bite == 8 || isFloat(byteToChar(mozila_bite));
	}else
		return isFloat(byteToChar(bite));
}
function ByteIsInt(bite, mozila_bite){
	if (mozila_bite != null){
		return mozila_bite == 0 || mozila_bite == 8 || isInt(byteToChar(mozila_bite));
	}else	
		return isInt(byteToChar(bite));
}
function ByteIsUserId(bite, mozila_bite){
	if (mozila_bite != null){
		return mozila_bite == 0 || mozila_bite == 8 || isUserId(byteToChar(mozila_bite));
	}else	
		return isUserId(byteToChar(bite));
}
function ByteIsNPWP(bite, mozila_bite){
	if (mozila_bite != null){
		return mozila_bite == 0 || mozila_bite == 8 || isNPWP(byteToChar(mozila_bite));
	}else	
		return isNPWP(byteToChar(bite));
}
function ByteIsKTP(bite, mozila_bite){
	if (mozila_bite != null){
		return mozila_bite == 0 || mozila_bite == 8 || isKTP(byteToChar(mozila_bite));
	}else	
		return isKTP(byteToChar(bite));
}
function ByteIsSAHAM(bite, mozila_bite){
	if (mozila_bite != null){
		return mozila_bite == 0 || mozila_bite == 8 || isSAHAM(byteToChar(mozila_bite));
	}else	
		return isSAHAM(byteToChar(bite));
}

function elementFormatNumber(){
	
	/**
	expired
	for backward compatibility only
	*/
	
	for (var i=0; i<arguments.length; i++){
		if (document.all[arguments[i]] != null){
			var elmnt = document.all[arguments[i]];
			//var elmnt_value = (elmnt.value * 1)
			//elmnt.value = formatNumber(elmnt_value.toFixed(2));
			elmnt.value = formatNumber(elmnt.value);
		}
	}
}
function toMoney(nilai,currc)  {
	var creg=/\./gi;
	if(nilai !=""){
        mystring = new String(nilai);
        var st = mystring.replace(creg, '');
		
		st += "";
		theValue=st;
		pos=theValue.search(',');
		tailSt="";
		if(pos!=-1) {
			tailSt=theValue.substr(pos,theValue.length-pos);
			theValue=theValue.substr(0,pos);
		}
		var finalString = '';

		if(theValue.length < 4) return st;

		var modulus = theValue.length % 3;
		var count = 0;
		finalString = theValue.substring(0, modulus);

		if(modulus != 0){
			if(currc=="idr")finalString += '.';
			if(currc=="usd")finalString += ',';
			if(currc=="")finalString += '.';
		}
		for(z = modulus; z < theValue.length; z++) {
			if(count == 3){

				if(currc=="idr")finalString += '.';
				if(currc=="usd")finalString += ',';
				
				count = 0;
			}
			finalString += theValue.charAt(z);
			count++;
		}
		finalString+=tailSt;
		return finalString;
	} else {
		return '';
	}
}


function sum_hitungdata(idtarget,totdata,iddiv){
	var idtemp,seqagr,sumqty,sumall;
	sumqty =0;
	
	for (var j=1; j <= totdata; j++){
		idtemp = iddiv + j;
		seqagr = toNumber(document.getElementById(idtemp).value);
		
		if (seqagr == '') seqagr =0;
		sumqty = sumqty + parseInt(seqagr);
	}
	//alert(sumqty);
	document.getElementById(idtarget).value = addSeparatorsNF(sumqty, '.', ',', '.');
}
function sum_hitungpotongan(idtarget,bbt,tjg,jum_pot,neto){
	var sumpotong,sumneto, tjgbobot;
		tjgbobot = (bbt * 0.01) * tjg;
		if (jum_pot < 100){
			sumpotong = parseInt(tjgbobot) * (jum_pot * 0.01);
		} else{
			sumpotong = parseInt(tjgbobot);
		}
	document.getElementById(idtarget).value = addSeparatorsNF(sumpotong, '.', ',', '.');
	
		sumneto = parseInt(tjgbobot) - parseInt(sumpotong);
		//alert(sumneto + " <> " + tjg+ " <> " + sumqty + "<>" + jum_pot);
		document.getElementById(neto).value = addSeparatorsNF(sumneto, '.', ',', '.');
	
}
function sum_hitungdatacheckbox(idtarget,totdata,iddiv,idchekbox){
	var idtemp,idcb,seqagr,sumqty,sumall;
	sumqty =0;
	
	for (var j=1; j <= totdata; j++){
		idtemp = iddiv + j;
		idcb = idchekbox + j;
		if (document.getElementById(idcb).checked == true){
			seqagr = toNumber(document.getElementById(idtemp).value);
			
			if (seqagr == '') seqagr =0;
			sumqty = sumqty + parseInt(seqagr);
		}
	}
	document.getElementById(idtarget).value = addSeparatorsNF(sumqty, '.', ',', '.');
}
function checkTanggal(field)
  {
    var allowBlank = true;
    var minYear = 1902;
    var maxYear = (new Date()).getFullYear();

    var errorMsg = "";

    //re = /^(\d{1,2})\/(\d{1,2})\/(\d{4})$/;
	re = /^(\d{1,2})\-(\d{1,2})\-(\d{4})$/;
    if(field.value != '') {
      if(regs = field.value.match(re)) {
        if(regs[1] < 1 || regs[1] > 31) {
          errorMsg = "Invalid value for day: " + regs[1];
        } else if(regs[2] < 1 || regs[2] > 12) {
          errorMsg = "Invalid value for month: " + regs[2];
        } 
      } else {
        errorMsg = "Invalid date format: " + field.value;
      }
    } 
	//alert(errorMsg);
	//else if(!allowBlank) {
    //  errorMsg = "Empty date not allowed!";
    //}

    if(errorMsg != "") {
      return false;
    } else {
		return true;
	}
  }
 function checkTanggalAkhir(field,Yearpembanding)
  {
    //re = /^(\d{1,2})\/(\d{1,2})\/(\d{4})$/;
	re = /^(\d{1,2})\-(\d{1,2})\-(\d{4})$/;
	
	var allowBlank = true;
    var minYear = 1902;
	var dateakhir;
    //var maxYear = (new Date()).getFullYear();
	dateakhir = Yearpembanding.value.match(re);
    var errorMsg  = "";
	 var maxYear  = dateakhir[3];
	 var maxmonth = dateakhir[2];
	//alert(dateakhir[2] + " <> " + dateakhir[3] + ' ' + regs[3] + ' ' + maxYear);
    
    if(field.value != '') {
      if(regs = field.value.match(re)) {
        if(regs[1] < 1 || regs[1] > 31) {
          errorMsg = "Invalid value for day: " + regs[1];
        } else if(regs[2] < 1 || regs[2] > 12) {
          errorMsg = "Invalid value for month: " + regs[2];
        } else if(regs[3] < maxYear) {
          errorMsg = "Invalid value for year: " + regs[3] + " - must be between " + minYear + " and " + maxYear;
        } else if(regs[3] == maxYear && (regs[2] <= maxmonth)) {
          errorMsg = "Invalid value for year: " + regs[3] + " - must be between " + minYear + " and " + maxYear;
        }
      } else {
        errorMsg = "Invalid date format: " + field.value;
      }
    } 
	//alert(errorMsg);
	//else if(!allowBlank) {
    //  errorMsg = "Empty date not allowed!";
    //}

    if(errorMsg != "") {
      return false;
    } else {
		return true;
	}
  }