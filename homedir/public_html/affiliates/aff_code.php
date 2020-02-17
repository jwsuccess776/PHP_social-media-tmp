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
# Name: 		aff_code.php
#
# Description:  Affiliate code for links
#
# # Version:      8.0
#
######################################################################

include('../db_connect.php');
include('aff_session_handler.inc');


$snippet="$Sess_AffUserId";

# retrieve the template
$area = 'affiliate';

$result=mysqli_query($globalMysqlConn, "SELECT * FROM banners") or die(mysqli_error());
mysqli_close($globalMysqlConn);

?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
<?
    require('aff_menu.php');
?>
  <tr>
    <td align="center"><?php echo AFF_CODE_TEXT ?></td>
  </tr>
  <tr>
    <td align="center"><p>&nbsp;</p>
      <?php

		print(htmlspecialchars("<a href='$CONST_LINK_ROOT/index.php?referid=$Sess_AffUserId'>LINK TEXT HERE</a>", ENT_QUOTES));		
		print("<br><br>");
		print(htmlspecialchars("<a href='$CONST_LINK_ROOT/index.php?referid=$Sess_AffUserId' target='_blank'>LINK TEXT HERE</a>", ENT_QUOTES));
		print("<br><br>");		
		print(htmlspecialchars("$CONST_LINK_ROOT/index.php?referid=$Sess_AffUserId", ENT_QUOTES));
		print("<br><br>");
			
		while($sql_banners=mysqli_fetch_object($result)) {
			print("<div class='tdodd'><p><a href='$CONST_LINK_ROOT/' target='_blank'><img border=0 src='$CONST_LINK_ROOT/affiliates/banners/$sql_banners->ban_picture' alt='$sql_banners->ban_text'><br>$sql_banners->ban_text</a></p>");
			print("<p><textarea  class='input' style='width:300px' cols='50' rows='5'><p align='center'><a href='$CONST_LINK_ROOT/index.php?referid=$Sess_AffUserId'><img src='$CONST_LINK_ROOT/affiliates/banners/$sql_banners->ban_picture' ALT='$sql_banners->ban_text' border='0'><br>$sql_banners->ban_text</a></p></textarea></p></div><br>");
		}
?>
           </td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>