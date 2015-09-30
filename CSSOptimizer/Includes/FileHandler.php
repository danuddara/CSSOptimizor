<?php
class FileHandler
{
	var $oldcss ='';
	var $oldhtml ='';
	var $css = '';
	var $html = '';
	var $hash = array();
	var $selectors = array();
/*
	filter class selectors and id selectors 
	that are more than 4,5 or n characters and
	 put them in to an array and pass it to the compression
*/
public function filterCssSelectors($css,$len=3)
{
	//filter id and class selector posistions
	//$css='%start% '.$css.' %end%'; // avoiding the error on strpos at the start and end.
	$pattern = array(".","#");
	$start=0;
	$array = new SplStack();
	
	$strlen = strlen($css);
	$css = $this->explodeSelectors($css);// getting the selectors only.
	trim($css);
	$css = str_replace("\n", ' ',$css);
	$css= '%start%  '.$css.'  %end%';//fix error in strpos
	$css = str_replace(',',' ',$css); // all the selectors are filterd by spaces at the end.
	$css = str_replace(':',' ',$css);
	//$css = str_replace('{',' ',$css); // replace css :hover , :active, states
	//$css = str_replace('}',' ',$css);
	foreach( $pattern as $token => $value)
	{
		$start=0;
		while(($newLine = strpos($css, $pattern[$token], $start)) != false)//looping through ids and classes.
		{
			$nextspace = strpos($css,' ',$newLine);
						
					$stringlen = $nextspace-($newLine+1);
					if($stringlen>$len)
					{
						$array->push(trim(substr($css,$newLine+1,$stringlen+1)));
					}
					
				
				$start = $nextspace + 1;
			//$css= '%start%  '.$css;
			
		//echo "New line:".$newLine;
		 //echo "next space:".$nextspace;
		}
		//echo print_r($array);
		
	}
	
	//echo print_r($array);
	//read selectors that are more than the specfied length and filter the posisitions.
	//get an array
	$newarray= array();
	$i=0;
	foreach($array as $val)
	{ 
	
	$newarray[$i]=$array->pop();
	$i++;
	}
	arsort($newarray); //sorting the array to adjust replacements.
	//echo print_r($newarray);
	$this->selectors = $newarray;
	return $this->selectors; 

}

public function explodeSelectors($css)
{
	$css='%start% } '.$css.' { %end%';// avodidance of eroor in str pos.
	$css = str_replace("\n", ' ',$css);
	$pattern = '}';
	$selectors = '';
	//$array = new SplStack();
	$start=0;
	
		while(($newLine = strpos($css, $pattern, $start)) != false)//looping through ids and classes.
		{
			$nextspace = strpos($css,'{',$newLine);
						
					$stringlen = $nextspace-($newLine+1);
					
					$selectors.=" ".(substr($css,$newLine+1,$stringlen))." ";
						
					// substrin(srting,startposition,length)
				
				$start = $nextspace + 1;
			
			
		//echo "New line:".$newLine;
		 //echo "next space:".$nextspace;
		}
			
	//return $selectors;
	//echo $selectors;
	return $selectors;
}
/*
get the compressed selector array. [selector]=>[hash].
Replace the String with the newly compressed selectors to
 1. HTML file (find and replace selectors HTML)
 return string
 and 
 2.CSS file(find and replace  selectors CSS)
 return string
 */

/**/
public function setOld($html,$css)
{
	if($css!='')
	{
		$this->oldcss=$css;
	}
	if($html!='')
	{
		$this->oldhtml=$html;
	}
	
	
	

}

public function setHTML($html)
{
	$this->html= $html;
}

public function setCSS($css)
{
	$this->css=$css;

}
public function findAndRelpaceHTML($array,$html)
{
	$this->hash=$array;
	$this->setHTML($html);
	$str='';
	foreach($array as $selector => $hash)
	{
	$selector=trim($selector);
	//$str =str_replace($selector,$hash,$this->html);
	$str=str_replace('id="'.$selector,'id="'.$hash." ",$this->html);
	$str=str_replace('class="'.$selector,'class="'.$hash." ",$str);
	$this->setHTML($str);
	}
	return $str;
	
}

public function findAndRelpaceCSS($array,$css)
{
	$this->hash=$array;
	$this->setCSS($css);
	$str='';
	if($array!=NULL)
	{
		foreach($array as $selector => $hash)
		{
		$selector=trim($selector);
		$str=str_replace('#'.$selector,'#'.$hash,$this->css);
		$str=str_replace('.'.$selector,'.'.$hash,$str);
		$this->setCSS($str);
		}
	}
	else
	{
	$str=$css;
	}
	return $str;
	
}
/*Old selector names and new selector names as a comment.*/
public function getSelectorHashComment()
{
	if($this->hash!=NULL){
		
		$array=$this->hash;
		$comment ='<br/>/*';
		foreach($array as $selector => $hash)
		{
		
		$comment.=' '.$selector.' : '.$hash.',';
		
		}
		$comment.='*/';
		return $comment;
	}
	
}

/*ratio,size and the difference of the size.*/

public function getratiocss()
{	$ratio=0;
	if($this->css!='')
	{
		//echo $this->oldcss;
		$ratio=round((strlen($this->oldcss)-strlen($this->css))/strlen($this->oldcss),3)*100;
	}
	return $ratio;	
}

public function getratioHTML()
{	$ratio=0;

	if($this->html!='')
	{
		//echo $this->oldhtml;
		$ratio=round((strlen($this->oldhtml)-strlen($this->html))/strlen($this->oldhtml),3)*100;
	}
	return $ratio;	
}


public function getfilesize($file='CSS',$loc='Output')
{
	$size=0;
	if($this->css!='' && $this->html!='' )
	{
	if($file=='CSS' && $loc=='input'){ $size=(strlen($this->oldcss));	}
	elseif($file=='CSS' && $loc=='Output'){ $size=(strlen($this->css));}
	
	elseif($file=='HTML' && $loc=='input'){	$size=(strlen($this->oldhtml));	}
	elseif($file=='HTML' && $loc=='Output'){	$size=(strlen($this->html));	}
	$size = $this->getunits($size);
	
	}
	
	elseif($this->css!='')
	{
	if($file=='CSS' && $loc=='input'){ $size=(strlen($this->oldcss));	}
	elseif($file=='CSS' && $loc=='Output'){ $size=(strlen($this->css));}
	$size = $this->getunits($size);
	}
	return $size;
	
}

public function getdifference($file='CSS')
{
	$diff=0;
	if($this->css!='' || $this->html!='' )
	{
	if($file=='CSS'){ $diff= (strlen($this->oldcss)-strlen($this->css));}
	elseif($file=='HTML'){$diff= (strlen($this->oldhtml)-strlen($this->html));}
	$diff=$this->getunits($diff);
	}
	
	
	return $diff;
}
private function getunits($size)
{
	if($size<1024)
		{return ($size).' bytes';}
	elseif($size>1048576)
		{ return ($size/1024).' Mb';}
	else
		{return ($size/1024).' KB';}
}
}

?>