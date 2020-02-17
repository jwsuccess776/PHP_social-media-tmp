<?php
/*****************************************************
* � copyright 1999 - 2020 iDateMedia, LLC.
*
* All materials and software are copyrighted by iDateMedia, LLC.
* under British, US and International copyright law. All rights reserved.
* No part of this code may be reproduced, sold, distributed
* or otherwise used in whole or in part without prior written permission.
*
*****************************************************/
######################################################################
#
# Name:         add2hotlist.php
#
# Description:  Displays the profile input page (after advert)
#
# Version:      7.2
#
######################################################################
include('db_connect.php');
include('session_handler.inc');
include_once('validation_functions.php');

if (isset($_REQUEST['userid']) && isset($Sess_UserId)) {
	$userid=sanitizeData($_REQUEST['userid'], 'xss_clean');  

	$handle=sanitizeData($_REQUEST['handle'], 'xss_clean');   
	$retval=mysqli_query($globalMysqlConn, "SELECT adv_title FROM adverts WHERE adv_userid=$userid");
	$sql_array = mysqli_fetch_object($retval);
	$advtitle=addslashes($sql_array->adv_title);
	$retval=mysqli_query($globalMysqlConn, "SELECT * FROM hotlist WHERE (hot_userid=$Sess_UserId AND hot_advid=$userid)");
	$result=mysqli_num_rows($retval);

	if ($result == 0) {
		$tempDate=date("Y/m/d");
		$query="INSERT INTO hotlist (hot_userid, hot_advid, hot_screenname, hot_dateadded, hot_title) values ('$Sess_UserId', '$userid', '$handle','$tempDate', \"$advtitle\")";
		$result=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());
		$querysave=$query;
	}
} 
?>
<html>
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=<?=$CONST_LANG_CHARSET?>">
<title><?=ADD2HOTLIST_TITLE?></title>
<LINK REL='StyleSheet' type='text/css' href='<?echo $CONST_LINK_ROOT?>/singles.css'>
</head>
<body>
<table width=100% border=0 cellpadding="5" cellspacing="0" class="poptable">
  <tr>
		<td align=center><?=ADD2HOTLIST_TEXT?></td>
	</tr>
	<tr>
		<td align=center><a href="#" onClick="javascript:window.close()"><?php echo GENERAL_CLOSE ?></a></td>
	</tr>
</table>
</body>

</html>
