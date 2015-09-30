<?php
class Node
{
	/*labeling */
	const LEFTlabel = 0;
	const RIGHTLabel = 1;
	/*is leaf,value,frequency, parent,left , right Nodes and path(left or right)*/
	protected $isleaf;
	protected  $value;
	protected $freq;
	protected $parentNode = NULL;
	protected $leftchild = NULL;
	protected $rightchild = NULL;
	protected $path = '';
	
	public function __construct($freq,$isLeaf=false,$value=NULL)
	{
		$this->freq = (int) $freq;
		$this->isleaf = (bool) $isLeaf;
		$this->value =$value;
	} 
	
	public function addtoNode(Node $node)
	{
		if($this->leftchild==NULL)
		{
			$this->leftchild = $node;
			$this->leftchild->setParentNode($this);//set as parent node
			$this->leftchild->setPath(self::LEFTlabel);
		}
		elseif($this->rightchild==NULL)
		{
			$this->rightchild = $node;
			$this->rightchild->setParentNode($this);
			$this->rightchild->setPath(self::RIGHTLabel);
		}
		else
		{
			throw new Exception("left and right both nodes are used");
		}
		return $this;
		
	}
	
	 public function setParentNode(Node $node)
    {
        $this->parentNode = $node;
        return $this;
    }
	
	 public function setPath($pathMarker)
    {
        $this->path = $pathMarker;
    }
	public function getFrequency()
    {
        return $this->freq;
    }

	public function getPath()
	{
		return $this->path;
	}

	public function isleaf()
	{
		return $this->isleaf;
	}
	
	public function getvalue()
	{
		return $this->value;
	}
	public function getParent()
	{
	 	return $this->parentNode;
	}
	
	public function getleftchild()
	{
		return $this->leftchild;
	}
	public function getrightchild()
	{
		return $this->rightchild;
	}

}
?>