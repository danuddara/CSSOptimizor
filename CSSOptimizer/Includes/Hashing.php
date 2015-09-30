<?php
class hashing 
{
	var $hashlen=0;
	private static $prime =array('1','41','2377','147299','9132313','566201239'); //prime numbers
	
	/*0-25 a-z,26-51 A-Z,52-61 0-9 */
	private static $hashtable = array(
	'0'=>'a','1'=>'b','2'=>'c','3'=>'d','4'=>'e','5'=>'f','6'=>'g','7'=>'h','8'=>'i','9'=>'j',
	'10'=>'k','11'=>'l','12'=>'m','13'=>'n','14'=>'o','15'=>'p','16'=>'q','17'=>'r','18'=>'s',
	'19'=>'t','20'=>'u','21'=>'v','22'=>'w','23'=>'x','24'=>'y','25'=>'z',
	'26'=>'A','27'=>'B','28'=>'C','29'=>'D','30'=>'E','31'=>'F','32'=>'G','33'=>'H','34'=>'I',
	'35'=>'J','36'=>'K','37'=>'L','38'=>'M','39'=>'N','40'=>'O','41'=>'P','42'=>'Q','43'=>'R',
	'44'=>'S','45'=>'T','46'=>'U','47'=>'V','48'=>'W','49'=>'X','50'=>'Y','51'=>'Z',
	'52'=>'0','53'=>'1','54'=>'2','55'=>'3','56'=>'4','57'=>'5','58'=>'6','59'=>'7','60'=>'8',
	'61'=>'9'
	 );
	 
	 public function __construct($hashlen=3)
	 	{
		 	$this->hashlen=$hashlen;
		}
	 public static function modules($val)
	 {
		 $key ="";
		 //$int =0;
		 while(bccomp($val,0)>0) //till the $value <0 
		 {
			 //echo '<b>'.bccomp($val,0).'</b>';
			 $temp = $val;
			 $mod = bcmod($val,62);
			 //$key .= self::$hashtable[$mod];
			 $val = bcdiv($val,62);
			 $t=self::checknum($mod);
			// echo "tetet:::".$t;
				if((bccomp($val,0)==0) && $t==true) //)&&  (true==self::checknum($mod)))//validtion of begin of the selector, cannot be a number.
				{
					$mod = $mod/2;
					$key .= self::$hashtable[$mod];
					
				}
				else
				{
					//echo "<br/>MOD:::".$mod."<br/>";
					$key .= self::$hashtable[$mod];
					//echo "key:".$key;
					//echo '<b>'.bccomp($val,0).'</b>';
					//echo self::checknum($mod);
				}
				
		}
		return strrev($key); // reversing the string.
	 //echo "IM in:".$key;
	}
	private static function checknum($int) // bool true or false
	{
		if(51<$int && $int<62)
		{
			
			return true;
		}
		else{return false;}
	}
	public function hash($num)
	{
		$len= $this->hashlen;
		//echo "Hash function".$num;
	  $basepower = bcpow(62,$len);
	  //$primes = array_keys(self::$prime);
	  $primeno = self::$prime[$len];
	  $modlarge=bcmod(bcmul($num,$primeno),$basepower);
	  $hash = self::modules($modlarge);
	  //echo "<p>Hash function:::::".$hash.'</p>';
	 return str_pad($hash, $len, "s", STR_PAD_LEFT); // deflaut value for hash string
	 //return $hash;
	}
	
	public function sel_hash($huffman)
	{  
		//echo "value".$huffman;
		
		$count = strlen($huffman);
		//echo "Count::::".$count;
		if($count>39)//avoid integer overflow.
		{
			$remove = $count-39;
			$huffman = substr($huffman,0,-$remove); //trim down the encoded value.
		}
		$huffman= bindec($huffman);// binary to decimal
		//$float = (float) number_format ( $huffman,8);// not working for large numbers.
		
		$sel_compres = $this->hash($huffman);
	//	echo "Hash:".$this->hash($huffman);		
		return $sel_compres;
	}
	
	
}

?>