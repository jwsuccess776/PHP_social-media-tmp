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
# Name:         add2im.php
#
# Description:  Displays the profile input page (after advert)
#
# Version:      7.2
#
######################################################################
include('db_connect.php');
include('session_handler.inc');
include_once('validation_functions.php');

if (isset($_REQUEST['userid']) ) {
	$userid =sanitizeData($_REQUEST['userid'], 'xss_clean');   
	$handle=sanitizeData($_REQUEST['handle'], 'xss_clean');   
	$retval=mysql_query("SELECT * FROM members WHERE mem_userid=$Sess_UserId",$link);
	$my = mysql_fetch_object($retval);

	$retval=mysql_query("SELECT * FROM my_friends WHERE (uid='$my->mem_username' AND friend_uid='$handle')",$link);
	$result=mysql_num_rows($retval);

	if ($result == 0) {
		$query = "INSERT INTO my_friends (uid, friend_uid, status) VALUES ('$my->mem_username', '$handle', 'A')";
		$result=mysql_query($query,$link) or die(mysql_error());
	}
} 
?>
<html>
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=<?=$CONST_LANG_CHARSET?>">
<title><?=ADD2FRIENDS_TITLE?></title>
<LINK REL='StyleSheet' type='text/css' href='<?echo $CONST_LINK_ROOT?>/singles.css'>
</head>
<body>
<table width=100% border=0 cellpadding="5" cellspacing="0" class="poptable">
  <tr >
		<td align=center> <?=ADD2FRIENDS_TEXT?> </center></td>
	</tr>
	<tr >
		<td align=center><a href="#" onClick="javascript:window.close()"><?php echo GENERAL_CLOSE ?></a></td>
	</tr>
</table>
</body>

</html>
