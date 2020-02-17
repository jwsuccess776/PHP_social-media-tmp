<?
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
# Name:         hregister2.php
#
# Description:  Company information page ('About Us')
#
# Version:      7.2
#
######################################################################
include('../db_connect.php');
?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title><?php echo HELP_REGISTER2_TITLE?></title>
<LINK href="<?=$CONST_LINK_ROOT.$skin->Path?>/singles.css" type=text/css rel=StyleSheet>
</head>

<body >
  <table width="98%" align="center" border="0" cellpadding="7" cellspacing="0" class="poptable">
    <tr>
      <td width="240" height="290" valign="top" align="left">
	<?php echo HELP_REGISTER2_TEXT?>
	</td>
    </tr>
    <tr>
      <td width="240" height="25" align="center">
        <center>
        <a href="" onClick="window.close();"><?php echo GENERAL_CLOSE?></a>
</center></td>
    </tr>
  </table>
</div>

</body>

</html>
