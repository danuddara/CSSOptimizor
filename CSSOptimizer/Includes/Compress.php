

<?php
require_once('huffman.php');
require_once('Hashing.php');

class Compress
{
	var $sel_arr = array();// test array
	var $binarycode =array(); // huffman enccodeed binary array "selector=>encoded value"
	var $hashcode = array();// hasing values "selector=> new short selector"
	var $huffman;
	var $hashing;
	var $hashlen=0;
	public function __construct($hashlen=3)
	{
		$this->hashlen = $hashlen; // length of the hash/
	}
	public function setSelarr($css)
	{
	 $this->sel_arr = $css;
	}
	public function parse(array $css)
	{
		if($css!=NULL)
		{
			$this->setSelarr($css); // set the input value.
			$this->huffmanencode();
			$this->hashing();
			return $this->hashcode;
		}
		else
		{
			echo "<p>There are no selectors to compress.</p>";
		}
	}
	
	private function huffmanencode() // huffman coding with values.
	{
		$this->huffman = new huffman(); 
		foreach($this->sel_arr  as $val)
		{
			if(!isset($this->binarycode[$val]))//optmization
			$this->binarycode[$val]= $this->huffman->encode(trim($val)); // one selector at a time
			
		}
		
	
	}
	
	private function hashing() // final compression for selector.
	{
		if($this->binarycode!= NULL)
		{
			$copybinary = $this->binarycode;
			$this->hashing= new hashing($this->hashlen);
			foreach($copybinary as $sel => $val)
			{
				
				
				$this->hashcode[$sel] = $this->hashing->sel_hash(trim($val)); // one selector at a time
			}
			
		}
		else
		{
			if($this->sel_arr!=NULL)
			$this->huffmanencode();
			else
			echo "ERROR: Selector array is empty! in class Compress ";
		}
	}
	
	public function printhuffman()
	{
	echo " <p>Selector=> binary value:<pre> ".print_r($this->binarycode)."</pre></p><br/>";
	//echo "<p>Selector input:<pre>".print_r($this->sel_arr)."</pre></p> <br/>";
	echo "<p>".print_r($this->hashcode)."</p>";
	}
	
}

?>
