<?php
 switch($_DBCONF['DB_TYPE']){
  case "MySQL":
   include_once ('mysql.class');
    break;
 }
 $DB=new DB_class($_DBCONF['SQL_HOST'],$_DBCONF['SQL_LOGIN'],$_DBCONF['SQL_PASSWD'],$_DBCONF['SQL_DATABASE']);
 if (!$DB->db_conn_id) {
  die (mysql_error());
 }
?>