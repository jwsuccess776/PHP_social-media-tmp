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
# Version:      7.2
#
######################################################################

ini_set("max_execution_time", "3000");
include('db_connect.php');

session_cache_limiter('private, must-revalidate');
session_start();

mysql_query("DELETE FROM zipcodes");
mysql_query("DELETE FROM geo_city WHERE gct_countryid = 13");

$states = array(
   'ACT' => 69,
   'NSW' => 70,
   'NT'  => 71,
   'QLD' => 72,
   'SA'  => 73,
   'TAS' => 74,
   'VIC' => 75,
   'WA'  => 76
);


$fp = fopen ("premiumAUS.csv","r") or die (ZIPCODES_CVSERROR);
$line_no=0;
while ( ! feof($fp)) {
    $line = fgets($fp, 1024);
    $line= addslashes($line);
    $line_array=explode(",",$line);
    $query="REPLACE INTO zipcodes VALUES('$line_array[2]','','$line_array[0]','','','','',$line_array[4],$line_array[5])";
    mysql_query($query,$link) or die(mysql_error().$query);
    $city = $line_array[0];
    $state = $states[$line_array[3]];
    
    mysql_query("INSERT INTO `geo_city` VALUES (NULL,13,'$city',1,'$state'");

    $line_no++;
    print("$line_no<br>");
}
fclose ($fp);
?>