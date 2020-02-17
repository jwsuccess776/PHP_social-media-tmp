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

$fp = fopen ("premiumUK.csv","r") or die (ZIPCODES_CVSERROR);
$line_no=0;
while ( ! feof($fp)) {
    $line = fgets($fp, 1024);
    $line= addslashes($line);
    $line_array=explode(",",$line);
	$query="INSERT INTO zipcodes VALUES('$line_array[0]','','','','','','',$line_array[3],$line_array[4])";
    mysql_query($query,$link) or die(mysql_error().$query);
    $line_no++;
    print("$line_no<br>");
}
fclose ($fp);
?>