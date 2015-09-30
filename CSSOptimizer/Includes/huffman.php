<?php 
require_once('Node.php');
require_once('TempNode.php');
require_once('Binary.php');
require_once('Tree.php');

class Huffman
{
	var $stringenc =  '';
	var $arrayenc = array();
	var $HuffmanBinary;
	
public function encode($val)
	{
		$this->arrayenc=array();
		$this->setString($val);
		$var=$this->getEncodedString();
		return $var;
		
	
	}

 public function setString($val)
    {
        $this->stringenc = $val;
    }
	
public function getEncodedCharArray()
    {
        if ($this->arrayenc == null)
        {
           	$this->HuffmanBinary= new HuffmanBinary();
            $this->arrayenc = $this->HuffmanBinary->binarycode($this->stringenc);
        }
        return $this->arrayenc;// array['character']=>['encode']
    }
 public function getEncodedString()
    {
        $encodedString = '';
        foreach (str_split($this->stringenc) as $key => $value)//array['character']=>['encode']; splitting the array in to characters.
        {
			//flush($value);
            $arrayenc= $this->getEncodedCharArray();
            $encodedString .= $arrayenc[$value].decbin(ord($value));//get the ascii value and convert it to binary. the hearder and the ecoded text
			// added the value ascii and then converted to binary to avoid the colition between printing only the path value.
        }
		//$encodedString = bindec($encodedString); // conveting the binary code to decimal.
        return $encodedString;
    }
}

?>