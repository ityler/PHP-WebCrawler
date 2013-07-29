<?php
/**** PHP Web Crawler - news-image ****/
/** DB connect **/
/**** Tyler Normile ****/

$server = "SERVER";
$username = "USERNAME";
$password = 'PASSWORD';
$database = "DB_NAME";
$dblink = mysqli_connect($server,$username,$password,$database);

/* check connection */
if (mysqli_connect_errno())	{
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
?>

