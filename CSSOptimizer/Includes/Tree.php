<?php
class Tree
{

/* this huffman coding  composed of two steps
: count of character frequencies
:construction of the unique prefixcode which is uesed in the hashing*/

	public function encode($val)
	{
		if($val!="")
		{
			$freqcount = $this->countFrequency($val);
			//ap string have been added inroder to ignor the error of same binary coding appearing for characters that are in closer ratio.
			$temp = $this->createLeaves($freqcount);
			$tree= $this->createTree($temp);
			return  $tree->current();
			
		}
		
	}
/*count of character frequencies*/

	protected function countFrequency($str)// this will give an array with the occurence of each ASCII value. [ASCII]=>[occurnces]
	{
		//echo "in at count";
		$chars= count_chars($str,1);
		$letters = array();
		foreach($chars as $ascii=>$freq)
		{
			$letters[chr($ascii)]=$freq; // chr()->ASCII value of the letter.
		}
		//sort($letters); you don't need to sort because in the tempnode it will takecare of that.
		return $letters; // can be sort and send.
		
	}
	
/*creating leaves, constrution of the tree. leaves gonna be leaves with values.*/

	protected function createLeaves(array $letters)
	{
		//echo "in at leaves";
		$leaves = new TempNode();
		foreach($letters as $char => $freq)
		{
			$leaves->insert(new Node($freq,true,$char),$freq);// (value,priority) priotiy queue
		}
		return $leaves;
	}
	
	/*construction of the tree with tempory node*/
	
	protected function createTree(tempNode $tempNode)
	{
		//echo "in at tree";
		if($tempNode->count()>1)
		{
			$leftNode = $tempNode->extract();/*node extratction one by one*/
			$rightNode = $tempNode->extract();
			
			$parentFreq= $leftNode->getFrequency() + $rightNode->getFrequency(); /*Weight(parent= weight(leftchild)+weight(rightchild))*/
			$parentNode = new Node($parentFreq);
			$parentNode->addtoNode($leftNode)->addtoNode($rightNode);
			$tempNode->insert($parentNode,$parentNode->getFrequency());
			$this->createTree($tempNode); // recursive till tempnode count is one.
			
		}
		return $tempNode;
	}
	
	
}

?>