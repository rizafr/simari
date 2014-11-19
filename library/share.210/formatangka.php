<?php 
class formatangka {
  /**
	Fungsi yang yang digunakan untuk conversi dari type data String Currency ke Decimal
	Param $nilaiCurrency adalah variabel untuk inputan data bertype String Currency
      */
  public function convertCurToDec($nilaiCurrency) {
	$replstr = array("-"=>"","."=>"",","=>".");
    return strtr($nilaiCurrency,$replstr);
  }

  /**
	Fungsi yang yang digunakan untuk conversi dari type data Decimal ke String Currency
	Param $nilaiCurrency adalah variabel untuk inputan data bertype Decimal
      */
  public function convertDecToCur($nilaiDecimal) {
    return number_format($nilaiDecimal,2,',','.');
  }
  
  /**
	Fungsi yang yang digunakan untuk conversi dari type data Decimal ke String Currency
	Param $nilaiCurrency adalah variabel untuk inputan data bertype Decimal
      */
  public function convertDecToCurNoBehind($nilaiDecimal) {
    return number_format($nilaiDecimal,0,',','.');
  }

 
   public function convertDecToCurversi2($nilaiDecimal) {
    return number_format($nilaiDecimal);
  }

}
?>