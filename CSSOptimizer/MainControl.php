<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
require_once('Includes/Compress.php');
require_once('Includes/FileHandler.php');//main

/*---Separate control class----*/
class Control
{
	var $css='';
	var $html = '';
	var $optimized= '';
	var $newCss = '';
	var $newHtml = '';
	var $fileHandler =NULL;
	var $compress = NULL;
	
	public function  __construct()
	{
		$this->fileHandler = new FileHandler();
		//echo "DOne";
	}
	
	public function setOld($html='',$css='')
	{
		$this->html = $html;
		$this->css = $css;
		$this->fileHandler->setOld($html,$css); //set old css and html
		
	}
	
	public function setNewCSS($css)
	{
		$this->newCss=$css;
		$this->fileHandler->setCSS($css);
	
	}
	
	public function CompressParse($css='',$len=3)
	{	//$fileHandler = new FileHandler();
	
		$this->optimized=$css;
		$array = array();
		
			$this->fileHandler->filterCssSelectors($css,$len); // selector lengths and optmized code from cssTidy
			$this->compress = new Compress($len);
			$array=$this->compress->parse($this->fileHandler->selectors);
		 //echo print_r($array);
		if($this->optimized!='')
		{
			//echo $this->optimized;
			
			$this->newCss=$this->fileHandler->findAndRelpaceCSS($array,$css)."/*****Selector Replacement*****/".$this->fileHandler->getSelectorHashComment();

		}
		
		if($this->html!='')
		{
			$this->newHtml=$this->fileHandler->findAndRelpaceHTML($array,$this->html)."/*****Selector Replacement*****/".$this->fileHandler->getSelectorHashComment();;
		}	
		
		//echo $this->compress->printhuffman();	
	}
	
	
	public function getstatics()
	{
		//echo $this->compress->printhuffman();
		$str ='';
		if($this->css!='' && $this->html='')
		{
			$str="<p style='clear:both;width:100%;position:relavtive'>CSS Compressed Ratio :".$this->fileHandler->getratiocss(). "%<br/> ";
			$str.="CSS input file size: ".$this->fileHandler->getfilesize('CSS','input')."<br/>";
			$str.="CSS output file size: ".$this->fileHandler->getfilesize()."<br/>";
			$str.="CSS file Difference : ".$this->fileHandler->getdifference()."<br/><br/>";
			
			$str="HTML Compressed Ratio :".$this->fileHandler->getratiocss(). "%<br/> ";
			$str.="HTML input file size: ".$this->fileHandler->getfilesize('HTML','input')."<br/>";
			$str.="HTML output file size: ".$this->fileHandler->getfilesize("HTML","Output")."<br/>";
			$str.="HTML file Difference : ".$this->fileHandler->getdifference('HTML')."<br/>";
			$str.="</p>";
			
		}
		elseif($this->css!='')
		{
			$str="<p style='clear:both;width:100%;position:relavtive'>CSS Compressed Ratio :".$this->fileHandler->getratiocss(). "%<br/> ";
			$str.="CSS input file size: ".$this->fileHandler->getfilesize('CSS','input')."<br/>";
			$str.="CSS output file size: ".$this->fileHandler->getfilesize()."<br/>";
			$str.="CSS file Difference : ".$this->fileHandler->getdifference()."<br/><br/>";
			//$str.=$this->fileHandler->oldhtml;
			$str.="</p>";
			
			
			
		}
		
		return $str;
	
	}
	
	public function getNewCSS()
	{
		return $this->newCss;
	}
	public function getNewHTML()
	{
		return $this->newHtml;
	}
	
	public function writetofiles()
	{
		$downloadlink ='';
		if($this->newCss!='' && $this->newHtml!='')
		{
			 	$cssfilename = md5(mt_rand().time().mt_rand());// randowm file number gerneration.
				$csshandle = fopen('temp/'.$cssfilename.'.css','w');
				if($csshandle) {
					if(fwrite($csshandle,$this->getNewCSS()))
					{
						$file_ok = true;
					}
				}
				fclose($csshandle);
			
				$htmlfilename = md5(mt_rand().time().mt_rand());// randowm file number gerneration.
				$htmlhandle = fopen('temp/'.$htmlfilename.'.html','w');
				if($htmlhandle) {
					if(fwrite($htmlhandle,$this->getNewHTML()))
					{
						$file_ok = true;
					}
				}
				fclose($htmlhandle);
				$downloadlink = '- <a href="temp/'.$cssfilename.'.css">Download CSS file</a>';
				$downloadlink .= '- <a href="temp/'.$htmlfilename.'.html">Download HTML file</a>';
		}
		
		elseif($this->newCss!='')
		{
				$cssfilename = md5(mt_rand().time().mt_rand());// randowm file number gerneration.
				$csshandle = fopen('temp/'.$cssfilename.'.css','w');
				if($csshandle) {
					if(fwrite($csshandle,$this->getNewCSS()))
					{
						$file_ok = true;
					}
				}
				fclose($csshandle);
				
				$downloadlink = '- <a href="temp/'.$cssfilename.'.css">Download CSS file</a>';
		}
		
		
		return $downloadlink;
	}
}

?>
</body>
</html>