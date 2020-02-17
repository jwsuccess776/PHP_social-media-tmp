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
# Name: 		prgunsubscribe.php
#
# Description:  removes members from the cupid report
#
# # Version:      8.0
#
######################################################################

include('db_connect.php');
include('error.php');
$query="SELECT mem_username, mem_email, mem_userid FROM members WHERE mem_email='$txtRemoveAddress'";
if (! $result=mysql_query($query,$link)) {error_page(mysql_error(),GENERAL_SYSTEM_ERROR);}
$sql_array = mysql_fetch_object($result);
$query="SELECT sea_userid FROM search WHERE sea_userid = '$sql_array->mem_userid'";
if (! $result=mysql_query($query,$link)) {error_page(mysql_error(),GENERAL_SYSTEM_ERROR);}
if (mysql_num_rows($result) <> 1) {
	error_page(PRGUNSUBSCRIBE_ERROR,GENERAL_USER_ERROR);
} else {
	$query="delete from search where sea_userid = '$sql_array->mem_userid'";
	if (! $result=mysql_query($query,$link)) {error_page(mysql_error(),GENERAL_SYSTEM_ERROR);}
	$query="delete from sarray where sar_userid = '$sql_array->mem_userid'";
	if (! $result=mysql_query($query,$link)) {error_page(mysql_error(),GENERAL_SYSTEM_ERROR);}
}
mysql_close($link);
?>
<html>
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title><?php echo PRGREMOVE_REMOVE?></title>
<link rel="stylesheet" href="singles.css" type="text/css">
</head>
<body>
<table width="500" border="0" align="center" cellpadding="5" cellspacing="0" class="poptable">
  <tr> 
    <td class="tdhead"><b><font size="3"><?php echo PRGREMOVE_REMOVE?>&nbsp;<?php print("$txtRemoveAddress"); ?></font></b></td>
  </tr>
  <tr> 
    <td class="tdodd"> <p align="center"><font size="2"><?php echo PRGREMOVE_TEXT1?></font></p>
      <p align="center"><font size="2"><?php echo PRGUNSUBSCRIBE_TEXT?></font></p>
      <p>&nbsp;</p>
      </td>
  </tr>
  <tr> 
    <td class="tdfoot">&nbsp;</td>
  </tr>
</table>

</body>
</html>
