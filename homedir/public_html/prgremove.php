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
# Name: 		prgremove.php
#
# Description:  removes people from the newsletter via a click in the mail
#
# # Version:      8.0
#
######################################################################

include('db_connect.php');
$txtRemoveAddress = formGet('txtRemoveAddress');
if (strlen($txtRemoveAddress) > 1) {
	$db->query("UPDATE members SET mem_newsletter=0 WHERE mem_email='$txtRemoveAddress'");
}
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
    <td class="tdhead"><font size="3"><?php echo PRGREMOVE_REMOVE?> <?php print("$txtRemoveAddress"); ?></font></td>
  </tr>
  <tr>
    <td class="tdodd"> <p><font size="2"><?php echo PRGREMOVE_TEXT1?></font></p>
      <p><font size="2"><?php echo PRGREMOVE_TEXT2?></font></p></td>
  </tr>
  <tr>
    <td class="tdfoot">&nbsp;</td>
  </tr>
</table>
</body>
</html>
