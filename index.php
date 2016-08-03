<?php
 header("HTTP/1.0 200 OK"); 
 header("Last-Modified: ".date("d M Y H:i:s")); 
 require_once('config.php');

 if ($_DBCONF['USE_DB']=='true') {
  require_once ('main/sql_lib.php');
 }

 require_once ('main/template.php');
 $action=0;
 GLOBAL $HTTP_POST_VARS;
 $query=$_SERVER['REQUEST_URI'];
 $str_pos=strpos($query,'index/');
 $new_pos=$str_pos+6;
 $action=substr($query,$new_pos);
 $action=str_replace("/eq/","=",$action);
 $query_arrey=explode("env/",$action);
 $HTTP_POST_VARS["action"]=$query_arrey[0];
 $action=$query_arrey[0];
 @$params=$query_arrey[1];
 $col_params=0;
 $query_params=explode("/nex/",$params);
 while (list($key,$value)=each($query_params)) {
  $cur_param=explode("=",$value);
  $name=$cur_param[0];
  @$value=$cur_param[1];
  $new_value=urldecode($value); 
  $new_value=stripslashes ($new_value); 
  $new_value=strip_tags ($new_value); 
  include_once 'main/'."utf.php"; 
  $value=utf_decode ($new_value); 
  $HTTP_POST_VARS[$name]=$value;
 }
 
 $url=$action;
 $url=trim($url);

 if (strpos($action,"session")>-1) {
  session_start();
 }
 if (@$HTTP_POST_VARS['session']=='true') {
  session_start();
 }


 displayMainAll($url);

?>

