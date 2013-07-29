<?php
/**** PHP Web Crawler ****/
/** DB connect for rss.php **/
/**** Tyler Normile ****/ 
$server = "SERVER";
$username = "USERNAME";
$password = "PASSWORD";
$database = "DB_NAME";
$link = mysql_connect($server,$username,$password);
mysql_selectdb($database,$link);
mysql_query("set names utf8");
?>
