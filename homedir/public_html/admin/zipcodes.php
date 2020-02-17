<?php
/*****************************************************
* © copyright 1999 - 2020 iDateMedia, LLC.
*
* All materials and software are copyrighted by iDateMedia, LLC.
* under British, US and International copyright law. All rights reserved.
* No part of this code may be reproduced, sold, distributed
* or otherwise used in whole or in part without prior written permission.
*
*****************************************************/
######################################################################
#
# Name:             zipcodes.php
#
# Description:      Upload Zipcode data
#
# # Version:      8.0
#
######################################################################

ini_set("max_execution_time", "3000");
include('../db_connect.php');

session_cache_limiter('private, must-revalidate');
session_start();
$query="DELETE FROM zipcodes";
mysql_query($query,$link) or die(mysql_error());
$fp = fopen ("premium.csv","r") or die (ZIPCODES_CVSERROR);
$line_no=0;
while ( ! feof($fp)) {
    $line = fgets($fp, 1024);
    $line= addslashes($line);
    $line_array=explode(",",$line);
    if (is_numeric($line_array[0])) $query="REPLACE INTO zipcodes VALUES('$line_array[0]','$line_array[1]','$line_array[2]','$line_array[3]','$line_array[4]','$line_array[5]','$line_array[7]',$line_array[14],$line_array[15])";
	else $query="REPLACE INTO zipcodes VALUES('$line_array[0]','','$line_array[1]','$line_array[4]','$line_array[2]','$line_array[3]','',$line_array[5],$line_array[6])";
//echo $query."<br>";
    mysql_query($query,$link) or die(mysql_error().$query);
    $line_no++;
}
echo $line_no." zipcodes added";


$result=mysql_query('show tables');
while($tables = mysql_fetch_array($result)) {
	foreach ($tables as $key => $value) {
		mysql_query("ALTER TABLE $value COLLATE utf8_general_ci");
	}
}
echo "<br>The collation of your database has been successfully changed";


fclose ($fp);
?>