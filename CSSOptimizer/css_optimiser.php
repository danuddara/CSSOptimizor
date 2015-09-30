<?php
header('Content-Type:text/html; charset=utf-8');
require('class.csstidy.php');
require_once('MainControl.php');
require('lang.inc.php');
$dir;


if (isset($_REQUEST['css_text']) && get_magic_quotes_gpc()) {
 	$_REQUEST['css_text'] = stripslashes($_REQUEST['css_text']);
}

function rmdirr($dirname,$oc=0) /*Remove files in the temp directory*/
{
	// Sanity check
	if (!file_exists($dirname)) {
	  return false;
	}
	// Simple delete for a file
	if (is_file($dirname) && (time()-filemtime($dirname))>3600) {// the time of the file has been modified
	   return unlink($dirname);
	}
	// Loop through the folder
	if(is_dir($dirname))
	{
	$dir = dir($dirname);
	while (false !== $entry = $dir->read()) {
	   // Skip pointers
	   if ($entry == '.' || $entry == '..') {
		   continue;
	   }
	   // Recurse
	   rmdirr("$dirname/$entry",$oc);
	}
	$dir->close();
	}
	// Clean up
	if ($oc==1)
	{
		return rmdir($dirname);
	}
}

function options($options, $selected = null, $labelIsValue = false)
{
    $html = '';

    settype($selected, 'array');
    settype($options, 'array');

    foreach ($options as $value=>$label)
    {
        if (is_array($label)) {
            $value = $label[0];
            $label = $label[1];
        }
        $label = htmlspecialchars($label, ENT_QUOTES, "utf-8");
        $value = $labelIsValue ? $label
                               : htmlspecialchars($value, ENT_QUOTES, "utf-8");

        $html .= '<option value="'.$value.'"';
        if (in_array($value, $selected)) {
            $html .= ' selected="selected"';
        }
        $html .= '>'.$label.'</option>';
    }
    if (!$html) {
        $html .= '<option value="0">---</option>';
    }

    return $html;
}

$css = new csstidy();
$control = new Control();
if(isset($_REQUEST['custom']) && !empty($_REQUEST['custom']))
{
    setcookie ('custom_template', $_REQUEST['custom'], time()+360000);
}
rmdirr('temp');

if(isset($_REQUEST['case_properties'])) $css->set_cfg('case_properties',$_REQUEST['case_properties']);
if(isset($_REQUEST['lowercase'])) $css->set_cfg('lowercase_s',true);
if(!isset($_REQUEST['compress_c']) && isset($_REQUEST['post'])) $css->set_cfg('compress_colors',false);
if(!isset($_REQUEST['compress_fw']) && isset($_REQUEST['post'])) $css->set_cfg('compress_font-weight',false);
if(isset($_REQUEST['merge_selectors'])) $css->set_cfg('merge_selectors', $_REQUEST['merge_selectors']);
if(isset($_REQUEST['optimise_shorthands'])) $css->set_cfg('optimise_shorthands',$_REQUEST['optimise_shorthands']);
if(!isset($_REQUEST['rbs']) && isset($_REQUEST['post'])) $css->set_cfg('remove_bslash',false);
if(isset($_REQUEST['preserve_css'])) $css->set_cfg('preserve_css',true);
if(isset($_REQUEST['sort_sel'])) $css->set_cfg('sort_selectors',true);
if(isset($_REQUEST['sort_de'])) $css->set_cfg('sort_properties',true);
if(isset($_REQUEST['remove_last_sem'])) $css->set_cfg('remove_last_;',true);
if(isset($_REQUEST['discard'])) $css->set_cfg('discard_invalid_properties',true);
if(isset($_REQUEST['css_level'])) $css->set_cfg('css_level',$_REQUEST['css_level']);
if(isset($_REQUEST['timestamp'])) $css->set_cfg('timestamp',true);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>
      <?php echo $lang[$l][0]; echo $css->version; ?>)
    </title>
    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
    <link rel="stylesheet" href="cssparse.css" type="text/css" />
    <script type="text/javascript">
    function enable_disable_preserve()
    {
        var inputs =   new Array('sort_sel', 'sort_de', 'optimise_shorthands', 'merge_selectors', 'none');
        var inputs_v = new Array( true,       true,      true,                  true,              false);
        for(var i = 0; i < inputs.length; i++)
        {
            if(document.getElementById('preserve_css').checked)  {
                document.getElementById(inputs[i]).disabled = inputs_v[i];
            } else {
                document.getElementById(inputs[i]).disabled = !inputs_v[i];
            }
        }
    }
    function ClipBoard()
    {
        window.clipboardData.setData('Text',document.getElementById("copytext").innerText);
    }
    </script>
  </head>
  <body onload="enable_disable_preserve()">
    <div><h1 style="display:inline">
     CSS Selector Compressor & Optimizer
    </h1>
    
   



    <form method="post" action="css_optimiser.php" enctype="multipart/form-data">
      <div>
        <fieldset id="field_input">
          <legend><?php echo $lang[$l][8]; ?></legend> <label for="css_text"
          class="block"><?php echo $lang[$l][9]; ?></label><textarea id="css_text" name="css_text" rows="20" cols="35"><?php if(isset($_REQUEST['css_text'])) echo htmlspecialchars($_REQUEST['css_text']); ?></textarea><br/><br/>
            <label for="url"><?php echo $lang[$l][10]; ?></label> <input type="text"
          name="url" id="url" <?php if(isset($_REQUEST['url']) &&
          !empty($_REQUEST['url'])) echo 'value="'.$_REQUEST['url'].'"'; ?>
          size="35" /><br/>
          <label for="htmlurl">URL-HTML:  </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="htmlurl" name="htmlurl" type="text" style="width:225px;"/><br/><br/><br/>
          <label for="file_upload">file upload- CSS</label>
          <input  name="file_upload" type="file"/><!--new input from file--><br />
          <label for="html_upload">file upload - HTML</label><input name="html_upload" type="file"/><br/>
         <input class="btn" name="submit" type="submit" value="<?php echo $lang[$l][35]; ?>" id="submit" />
         <?php
         if(isset($_POST['submit']) && $_FILES["file_upload"]["tmp_name"]!="")
			{
				//echo "<p>Im in</p>";
			if ($_FILES["file_upload"]["error"] > 0 )
			  {
			  echo "Error: " . $_FILES["file"]["error"] . "<br>";
			  }
			else
			  {
			  echo "Upload: " . $_FILES["file_upload"]["name"] . "<br>";
			  echo "Type: " . $_FILES["file_upload"]["type"] . "<br>";
			  echo "Size: " . ($_FILES["file_upload"]["size"] / 1024) . " kB<br>";
			  echo "Stored in: " . $_FILES["file_upload"]["tmp_name"];
			  // echo "Stored in: " . $_FILES["html_upload"]["tmp_name"];
			  $dir=$_FILES["file_upload"]["tmp_name"];// temp is the derectory that when a file upload holds in the server.
			  }
			  
			/*  $content = file_get_contents($dir);
				$homepage=str_replace("\r\n","\n",$content).'';
				echo $homepage;
				echo '<hr>';
				echo $content;*/
			}
			
			
		 ?>
        
        </fieldset>
        <div id="rightcol">
          <fieldset id="code_layout">
            <legend><?php echo $lang[$l][11]; ?></legend> <label for="template"
            class="block"><?php echo $lang[$l][12]; ?></label> <select
            id="template" name="template" style="margin-bottom:1em;">
              <?php
                $num = ($_REQUEST['template']) ? intval($_REQUEST['template']) : 1;
                echo options(array(3 => $lang[$l][13], 2 => $lang[$l][14], 1 => $lang[$l][15], 0 => $lang[$l][16]), $num);
              ?>
            </select><br />
            
          </fieldset>
          <fieldset id="options">
         <legend><?php echo $lang[$l][19]; ?></legend>
			<input type="checkbox" name="sel_compress" id="sel_compress" <?php if(isset($_REQUEST['sel_compress'])){ echo 'checked=checked'; }?> /> <label for="sel_compress"> Selector Compression</label>
            <select id="len_compress" name="len_compress">
            	<option>3</option>
                <option>4</option>
                <option>5</option>
            </select>
            <br/>
            <input onchange="enable_disable_preserve()" type="checkbox" name="preserve_css" id="preserve_css"
                   <?php if($css->get_cfg('preserve_css')) echo 'checked="checked"'; ?> />
            <label for="preserve_css" title="<?php echo $lang[$l][52]; ?>" class="help"><?php echo $lang[$l][51]; ?></label><br />


            <input type="checkbox" name="sort_sel" id="sort_sel"
                   <?php if($css->get_cfg('sort_selectors')) echo 'checked="checked"'; ?> />
            <label for="sort_sel" title="<?php echo $lang[$l][41]; ?>" class="help"><?php echo $lang[$l][20]; ?></label><br />


            <input type="checkbox" name="sort_de" id="sort_de"
                   <?php if($css->get_cfg('sort_properties')) echo 'checked="checked"'; ?> />
            <label for="sort_de"><?php echo $lang[$l][21]; ?></label><br />


            <label for="merge_selectors"><?php echo $lang[$l][22]; ?></label>
            <select style="width:15em;" name="merge_selectors" id="merge_selectors">
              <?php echo options(array('0' => $lang[$l][47], '1' => $lang[$l][48], '2' => $lang[$l][49]), $css->get_cfg('merge_selectors')); ?>
            </select><br />

            <label for="optimise_shorthands"><?php echo $lang[$l][23]; ?></label>
            <select name="optimise_shorthands" id="optimise_shorthands">
            <?php echo options(array($lang[$l][54], $lang[$l][55], $lang[$l][56]), $css->get_cfg('optimise_shorthands')); ?>
            </select><br />


            <input type="checkbox" name="compress_c" id="compress_c"
                   <?php if($css->get_cfg('compress_colors')) echo 'checked="checked"';?> />
            <label for="compress_c"><?php echo $lang[$l][24]; ?></label><br />


            <input type="checkbox" name="compress_fw" id="compress_fw"
                   <?php if($css->get_cfg('compress_font-weight')) echo 'checked="checked"';?> />
            <label for="compress_fw"><?php echo $lang[$l][45]; ?></label><br />


            <input type="checkbox" name="lowercase" id="lowercase" value="lowercase"
                   <?php if($css->get_cfg('lowercase_s')) echo 'checked="checked"'; ?> />
            <label title="<?php echo $lang[$l][30]; ?>" class="help" for="lowercase"><?php echo $lang[$l][25]; ?></label><br />


            <?php echo $lang[$l][26]; ?><br />
            <input type="radio" name="case_properties" id="none" value="0"
                   <?php if($css->get_cfg('case_properties') == 0) echo 'checked="checked"'; ?> />
            <label for="none"><?php echo $lang[$l][53]; ?></label>
            <input type="radio" name="case_properties" id="lower_yes" value="1"
                   <?php if($css->get_cfg('case_properties') == 1) echo 'checked="checked"'; ?> />
            <label for="lower_yes"><?php echo $lang[$l][27]; ?></label>
            <input type="radio" name="case_properties" id="upper_yes" value="2"
                   <?php if($css->get_cfg('case_properties') == 2) echo 'checked="checked"'; ?> />
            <label for="upper_yes"><?php echo $lang[$l][29]; ?></label><br />

            <input type="checkbox" name="rbs" id="rbs"
                   <?php if($css->get_cfg('remove_bslash')) echo 'checked="checked"'; ?> />
            <label for="rbs"><?php echo $lang[$l][31]; ?></label><br />


            <input type="checkbox" id="remove_last_sem" name="remove_last_sem"
                   <?php if($css->get_cfg('remove_last_;')) echo 'checked="checked"'; ?> />
   			<label for="remove_last_sem"><?php echo $lang[$l][42]; ?></label><br />


            <input type="checkbox" id="discard" name="discard"
                   <?php if($css->get_cfg('discard_invalid_properties')) echo 'checked="checked"'; ?> />
            <label for="discard"><?php echo $lang[$l][43]; ?></label>
            <select name="css_level"><?php echo options(array('CSS2.1','CSS2.0','CSS1.0'),$css->get_cfg('css_level'), true); ?></select><br />


            <input type="checkbox" id="timestamp" name="timestamp"
                   <?php if($css->get_cfg('timestamp')) echo 'checked="checked"'; ?> />
   			<label for="timestamp"><?php echo $lang[$l][57]; ?></label><br />


            <input type="checkbox" name="file_output" id="file_output" value="file_output"
                   <?php if(isset($_REQUEST['file_output'])) echo 'checked="checked"'; ?> />
            <label class="help" title="<?php echo $lang[$l][34]; ?>" for="file_output">
				<strong><?php echo $lang[$l][33]; ?></strong>
			</label><br />

          </fieldset>
        <input type="hidden" name="post" />
        </div>
      </div>
    </form>
    <?php

    $file_ok = false;
    $result = false;
$html="";
    $url = (isset($_REQUEST['url']) && !empty($_REQUEST['url'])) ? $_REQUEST['url'] : false;
	/*$dir=(isset($dir))? true: false;
	
	echo '<h1>'.$dir.'</h1>';*/
	
 if(isset($_REQUEST['template']))
	{
		switch($_REQUEST['template'])
		{
			case 4:
			if(isset($_REQUEST['custom']) && !empty($_REQUEST['custom']))
			{
				$css->load_template($_REQUEST['custom'],false);
			}
			break;

			case 3:
			$css->load_template('highest_compression');
			break;

			case 2:
			$css->load_template('high_compression');
			break;

			case 0:
			$css->load_template('low_compression');
			break;
		}
	}

    if($url)
    {
    	if(substr($_REQUEST['url'],0,7) != 'http://')
		{
			$_REQUEST['url'] = 'http://'.$_REQUEST['url'];
		}
        $result = $css->parse_from_url($_REQUEST['url'],0);
		
		
		
    }
    elseif(isset($_REQUEST['css_text']) && strlen($_REQUEST['css_text'])>5)
    {
        $result = $css->parse($_REQUEST['css_text']);
    }
	elseif(isset($_POST['submit'])  && $_FILES["file_upload"]["tmp_name"]!="")/*newly added on file submit*/
	{
 				if ($_FILES["file_upload"]["error"] > 0)
				  {
				  echo "Error: " . $_FILES["file"]["error"] . "<br>";
				  }
				else
				  {
				
				  $ddir=$_FILES["file_upload"]["tmp_name"];
				  $result = $css->parse_from_url($ddir);
				  						
				 // echo '<pre>'.($result).'</pre>';
				  }
						
				
	}

    if($result)
    {
		 $htmlurl = (isset($_REQUEST['htmlurl']) && !empty($_REQUEST['htmlurl'])) ? $_REQUEST['htmlurl'] : false;
		 if($htmlurl)
		 {
			if(substr($_REQUEST['htmlurl'],0,7) != 'http://')
			{
				$_REQUEST['htmlurl'] = 'http://'.$_REQUEST['htmlurl'];
			}
		
        	if(isset($_REQUEST['htmlurl'])){  $html = $css->parse_from_url_html($_REQUEST['htmlurl']);}
		 }
		else if(isset($_POST['submit'])  && $_FILES["html_upload"]["tmp_name"]!="") 
		{
			$ff=$_FILES["html_upload"]["tmp_name"];
			//echo "htmlir::::::".$htmlur;
			$html =$css->parse_from_url_file($ff);
		}
		//echo "heeeeelo:::::::: ".$html;
		$cssoptimized =$css->print->plain();
		//echo $css->print->input_css;
		$control->setOld($html,$css->print->input_css);
			if(isset($_REQUEST['sel_compress']) && isset($_REQUEST['len_compress']) )
				$control->CompressParse($cssoptimized,$_REQUEST['len_compress']);//compression if set.
			else
				$control->setNewCSS($cssoptimized);
		//$control->CompressParse($cssoptimized);
        
  		$downlodlink='';
			if(isset($_REQUEST['file_output']))
			{
			   $downlodlink=$control->writetofiles();
			}
		$statics= $control->getstatics();
        if($statics!='') echo "<p style='color:#f60;font-family:arial,font-size:16px;'>".$statics."</p>";
        if(count($css->log) > 0): ?>
        <fieldset id="messages"><legend>Messages</legend>
			<div><dl><?php
			foreach($css->log as $line => $array)
			{
				echo '<dt>'.$line.'</dt>';
				for($i = 0; $i < count($array); $i++)
				{
					echo '<dd class="'.$array[$i]['t'].'">'.$array[$i]['m'].'</dd>';
				}
			}
			?></dl></div>
        </fieldset>
        <?php endif;
      
        if($downlodlink!='')
        {
            echo $downlodlink;
        }
        echo ' - <a href="javascript:ClipBoard()">Copy to clipboard</a>';
        echo '</legend>';
		
        echo '<pre><code id="copytext">';
		echo '<div style="width:100%;height:400px;overflow:auto;border:2px soild #000;postition:relative;background-color:#00f;color:#fff;">';
     	echo $control->getNewCSS();
		echo '<div>';
        echo '</code></pre>';
		
        echo '</fieldset><p><a href="javascript:scrollTo(0,0)">&#8593; Back to top</a></p>';
     }
     elseif(isset($_REQUEST['css_text']) || isset($_REQUEST['url'])) {
        echo '<p class="important">'.$lang[$l][28].'</p>';
     }
     ?>
   
  </body>
</html>