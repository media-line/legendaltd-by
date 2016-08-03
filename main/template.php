<?php
require_once('template.class');
define("PATH_html",'html');
define("DEFAULT_html_PATH",PATH_html);

if (!file_exists(DEFAULT_html_PATH."/map.cfg")) die("˜˜˜˜˜˜˜˜ ˜˜˜˜ ".DEFAULT_html_PATH."/map.cfg ˜˜ ˜˜˜˜˜˜!");

function getPath($pathAr,$maxId) {
	$path='';
	$i=0;
	while ($i<=$maxId) {
		if (@substr($pathAr[$i], -1)=='/') {
	        	@$path.=$pathAr[$i];
	        } else {
		        @$path.=$pathAr[$i].'/';
		}
        	$i=$i+1;
	}
	#echo '<br>getPath='.$path.'<br>';
	return $path;
	$path='';
}

function displayMainAll($action=0) {
$first_action=$action;
	if ($action) {                  
		$new_action=explode('/',$action);
	}
	if (substr($action, -1)=='/') {
		$i=count($new_action)-2;    
	} else {
		@$i=count($new_action)-1;    
	}
	if ($i>=0) {
        while ($i>=0) {
		$mPath=getPath($new_action,$i);
		#echo '<br>mPath='.$mPath;
		$mapPath=DEFAULT_html_PATH.'/'.$mPath."map.cfg";
        	if (file_exists($mapPath)) {
			return displayAll($mapPath,$first_action);
		}
		$i=$i-1;
        }
    }
    if ($i==-1) {
        $mapPath=DEFAULT_html_PATH."/map.cfg";
        return displayAll($mapPath,$first_action);
    }
}
function displayAll($pathMap,$action) {

	#echo '<br>pathMap='.$pathMap;
	#echo '<br>action_displayAll='.$action;
	if (substr($action, -1)=='/') {
		$action=explode('/',$action);
		$i=count($action)-2;    
	} else {
		$action=explode('/',$action);
		$i=count($action)-1;    
	}

	$globalPath=DEFAULT_html_PATH.'/'.getPath($action,$i);
	#echo '<br>globalPath='.$globalPath;

	$globalPath=preg_replace("/\/$/i",'',$globalPath);
	if (!file_exists($pathMap)) die("˜˜˜˜ $pathMap ˜˜ ˜˜˜˜˜˜!");
	if (!$fp=fopen($pathMap,'r')) die("˜˜ ˜˜˜˜ ˜˜˜˜˜˜˜ ".$pathMap."!");
	$fileContent=fread($fp,filesize($pathMap));
	$filesArray=explode("\n",$fileContent);
	for ($i=0;$i<count($filesArray);$i++) {
		if (empty($filesArray[$i])) continue;
		#echo '<br>globalPath='.$globalPath;
		$f=getPathFile($globalPath,$filesArray[$i]);

		#echo '<br>filesArray='.$filesArray[$i];
		#echo  '<br>file='.$f;
	        parseDynhtml($f);
   	}
}
function getPathFile($globalPath,$fileName) {
	#echo '<br>fileName='.$fileName;
	if (substr($globalPath, -1)=='/') {
		$f=trim($globalPath.$fileName);
	} else {
		$f=trim($globalPath.'/'.$fileName);
	}
	if (empty($globalPath)) {
		if (!file_exists(trim($f))) { die("˜˜˜˜ $fileName ˜˜ ˜˜˜˜˜˜ ˜ $globalPath !"); }
	}
	if (file_exists($f)) {
		#echo '<br>f='.$f;
		return $f;
	} else {
        	$globalPath=preg_replace("/\/([^\/]*)$/","",$globalPath);
		#echo '<br>globalPath='.$globalPath;
		return getPathFile($globalPath,$fileName);
	}
}
function parseDynhtml($f) {
GLOBAL $_CONF;

    if (!file_exists($f)) die("˜˜˜˜ $f ˜˜ ˜˜˜˜˜˜!");
    if (!$fp_=fopen($f,'r')) die("˜˜ ˜˜˜˜ ˜˜˜˜˜˜˜ $f!");
    while (!feof($fp_)) {

        $str=fgets($fp_);

        if (preg_match_all("/\{INSERT_FILE \x22(.*)\x22\}/i",$str,$M)) {
            $insert_file='services/'.$M[1][0];
            include_once($insert_file);
        } else {

         $docs='href="docs/';
         if (strstr($str,$docs)) {
          $new_doc=' href="'.$_CONF['PATH_HTTP'].'/docs/';
          $str=str_replace($docs,$new_doc,$str);
         }

         $urls='href="';
         $href='" class="';
         $external_link='href="http://';
         $java='javascript';
	 $style='href="styles/';
         $mailto='href="mailto:';
         if ((strstr($str,$urls)) AND (!strstr($str,$external_link)) AND (!strstr($str,$style) AND (!strstr($str,$mailto))) AND (!strstr($str,$java))) {
          $new_url='href="'.$_CONF['BASE_URL'];
          $str=str_replace($urls,$new_url,$str);
         }

         $docs_js="openWin('docs/";
         if (strstr($str,$docs_js)) {
          $new_doc_js="openWin('".$_CONF['PATH_HTTP']."/docs/";
          $str=str_replace($docs_js,$new_doc_js,$str);
         }

         $forms='form action="';
         if (strstr($str,$forms)) {
          $new_form='form action="'.$_CONF['PATH_HTTP'].'/index/';
          $str=str_replace($forms,$new_form,$str);
         }

         $background_style='background:url(images';
         if (strstr($str,$background_style)) {
          $new_background_style='background:url('.$_CONF['PATH_HTTP'].'/images';
          $str=str_replace($background_style,$new_background_style,$str);
         }

         $background='background="images';
         if (strstr($str,$background)) {
          $new_bg='background="'.$_CONF['PATH_HTTP'].'/images';
          $str=str_replace($background,$new_bg,$str);
         }

         $images='src="images/';
         if (strstr($str,$images)) {
          $new_image='src="'.$_CONF['PATH_HTTP'].'/images/';
          $str=str_replace($images,$new_image,$str);
         }

         $styles="javascript:openNew('images";
         if (strstr($str,$styles)) {
          $new_style="javascript:openNew('".$_CONF['PATH_HTTP']."/images";
          $str=str_replace($styles,$new_style,$str);
         }

	 $jscript='src="js/';
         if (strstr($str,$jscript)) {
          $new_jscript='src="'.$_CONF['PATH_HTTP'].'/js/';
          $str=str_replace($jscript,$new_jscript,$str);
         }

         $css='url(../';
         if (strstr($str,$css)) {
          $new_css='url('.$_CONF['PATH_HTTP'].'/';
          $str=str_replace($css,$new_css,$str);
         }

	 $style='href="styles/';
         if (strstr($str,$style)) {
          $new_style=' href="'.$_CONF['PATH_HTTP'].'/';
          $str=str_replace($urls,$new_style,$str);
         }
 
        echo $str;

        }
    }

}

?>