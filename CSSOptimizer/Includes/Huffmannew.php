<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
include('Node.php');
include('TempNode.php');
include('Binary.php');
include('Tree.php');


class hashing 
{
	public function sel_hash($huffman)
	{
		$sel_compres = "*".strtoupper($huffman);
		return $sel_compres;
	}
	
}

class HuffmanEncoder
{

    /**
     * @var string 
     */
    protected $stringToEncode = '';
    /**
     * @var \Spiechu\PHPHuffmanEncoder\Node 
     */
    protected $binaryTree = null;
    protected $encodedCharArray = array();

    public function setString($string)
    {
        $this->stringToEncode = $string;
    }

    public function getEncodedCharArray()
    {
        if ($this->encodedCharArray == null)
        {
            $charEncoder = new HuffmanBinary();
            $this->encodedCharArray = $charEncoder->binarycode($this->stringToEncode);
        }
        return $this->encodedCharArray;
    }

    public function getBinaryTree()
    {
        if ($this->binaryTree == null)
        {
            $binaryTree = new Tree();
            $this->binaryTree = $binaryTree->encode($this->stringToEncode);
        }
        return $this->binaryTree;
    }

    public function getEncodedString()
    {
        $encodedString = '';
        foreach (str_split($this->stringToEncode) as $key => $value)
        {
            $encCharArray = $this->getEncodedCharArray();
            $encodedString .= $encCharArray[$value];
        }
        return $encodedString;
    }

    public function decodeString($string)
    {
        $encodedStringArray = array_flip($this->getEncodedCharArray());
        $decodedString = '';
        $splittedString = str_split($string);
        $partialCode = '';
        while (count($splittedString) > 0)
        {
            $partialCode .= array_shift($splittedString);
            if (array_key_exists($partialCode, $encodedStringArray))
            {
                $decodedString .= $encodedStringArray[$partialCode];
                $partialCode = '';
            }
        }
        return $decodedString;
    }

}

$huffman = new HuffmanEncoder();
$huffman->setString('hello');
//var_dump($huffman->getBinaryTree());
var_dump($huffman->getEncodedString());
$x=$huffman->getEncodedString();

$huffman->setString('olleh');
var_dump($huffman->getEncodedString());
$y=$huffman->getEncodedString();

if($x==$y)
{
	echo "<br/>same";
}
else
{
	echo "<br/>not same";}

/*class Compress
{
	var $sel_arr = array('hello','olleh');// test array
	var $binarycode =array(); // huffman enccodeed binary array "selector=>encoded value"
	var $hashcode = array();// hasing values "selector=> new short selector"
	var $huffman;
	var $hashing;
	
	public function parse(array $css)
	{
		if($css!=NULL)
		{
			$this->sel_arr= $css; // set the input value.
			$this->huffmanencode();
			//$this->hashing();
			return $this->hashcode;
		}
		else
		{
			echo "<p>there are not selectors to compress all are shorter than 4 charactors.</p>";
		}
	}
	
	private function huffmanencode() // huffman coding with values.
	{
		$this->huffman = new huffman(); 
		foreach($this->sel_arr  as $val)
		{
			$this->binarycode[$val]= $this->huffman->encode($val);
			
		}
		
	
	}
	
	private function hashing() // final compression for selector.
	{
		if($this->binarycode!= NULL)
		{
			$this->hashing= new hashing();
			foreach($this->binarycode as $sel => $val)
			{
				$this->hashcode[$sel] = $this->hashing->sel_hash($val);
			}
			
		}
		else
		{
			$this->huffmanencode();
		}
	}
	
	public function printhuffman()
	{
	echo " <p>Selector=> binary value:<pre> ".print_r($this->binarycode)."</pre></p><br/>";
	echo "<p>Selector input:<pre>".print_r($this->sel_arr)."</pre></p> <br/>";
	echo "<p>".print_r($this->hashcode)."</p>";
	}
	
}

/*$compress = new Compress();
$sel_arr = array('hello');
$compress->parse($sel_arr);
$compress->printhuffman();*/
 //$huff = new Huffman();
 //var_dump($huff->encode('hello'));


?>
</body>
</html>