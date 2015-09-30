<?php
class HuffmanBinary
{
public function binarycode($str)
	{
		$arryenc= array();
		$stack = new SplStack();
		$tree = new Tree(); 
		$stack->push($tree->encode((string) $str));
		$curNode = NULL;
		while(!$stack->isEmpty())
		{
			
			$curNode = $stack->pop();
			if($curNode->isleaf())
			{
				$arryenc[$curNode->getvalue()]=$this->extractPath($curNode);
				//$arryenc[$curNode->getvalue()]=1;array['character']=> ['00121']
				
			}
	
			else
			{
				//$arryenc[$curNode->getvalue()]=0;
				
				if($curNode->getleftchild()!=NULL)
				{
					$stack->push($curNode->getleftchild());
				}
				if($curNode->getrightchild()!=NULL)
				{
					$stack->push($curNode->getrightchild());
				}
			}
		}
		return $arryenc;
	}
	
	 protected function extractPath( Node $node)
    {
		//echo "I was here";
        $path = array();
        $curNode = $node;
        while (true)
        {
			//echo "And went in too";
            array_unshift($path, $curNode->getPath());
            if ($curNode->getParent() != null)
            {
				//echo "this works well ofcourse";
                $curNode = $curNode->getParent();
                continue;
            } else
            {
                break;
            }
        }
        return implode('', $path);
		
    }
}


?>