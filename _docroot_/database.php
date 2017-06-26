<?php
$string = file_get_contents($_SERVER['DOCUMENT_ROOT']."/setting.json");
$json = json_decode($string, true);
$con = mysql_connect($json['database']["database_host"],$json['database']["database_username"],$json['database']["database_password"]) or die("not connect");
$db = mysql_select_db($json['database']["database_name"], $con) or die ("not selected");

?>
