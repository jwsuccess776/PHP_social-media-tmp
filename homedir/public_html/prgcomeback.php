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
# Name: 		prgcomeback.php
#
# Description:  Sends offer mails to people who have not visited for a while
#
# # Version:      8.0
#
######################################################################
include('db_connect.inc');

# get a list of standard members who have not visited for 1 month
$query="SELECT mem_username, mem_password, mem_userid, mem_email FROM members WHERE mem_lastvisit < '2002-01-01' AND mem_expiredate < '2002-06-04' AND mem_sex='M' LIMIT 5000,2000";
$result=mysql_query($query,$link) or die(mysql_error());
$recno=0;
while ($sql_array = mysql_fetch_object($result)) {
	$recno++;
	$username=trim($sql_array->mem_username);
	$sql_array->mem_username=trim($sql_array->mem_username);
	$sql_array->mem_username=str_replace(" ","%20",$sql_array->mem_username);
	$message="

	";
		send_mail ("$sql_array->mem_email", "$CONST_FLIRTMAIL", "$CONST_COMPANY- Free 3 Day Premium Pass", "$message","html","ON");
		print("<br>$recno - $sql_array->mem_email");
		flush();
		sleep(4);
		if (is_int($recno/500)) {
			print("<br><br>Sleeping for 1 minutes");
			flush();
			sleep(60);
			print("<br><br>Sleeping for 1 minutes");
			flush();
			sleep(60);
			print("<br><br>Sleeping for 1 minutes");
			flush();
			sleep(60);
		}
	}
	mysql_close( $link );
?>