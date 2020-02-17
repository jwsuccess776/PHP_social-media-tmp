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
# Name: 		sendflirt.php
#
# Description:  Allows a sender to send a flirt if they have an advert.
#
# # Version:      8.0
#
######################################################################

include('db_connect.php');
include('session_handler.inc');
# retrieve the template
$area = 'member';

# check to see if the sender has an advert (required to send flirts)
$query = "SELECT adv_userid, adv_approved FROM adverts WHERE adv_userid=$Sess_UserId";
$retval=mysql_query($query,$link) or die(mysql_error());
$result=mysql_num_rows($retval);
if ($result > 0) {
	$sql_array = mysql_fetch_object($retval);
	if ($sql_array->adv_approved==0) {$isenabled="DISABLED"; $hasadvert=SENDFLIRT_STATUS1;}
	if ($sql_array->adv_approved==1) {$isenabled="ENABLED"; $hasadvert=SENDFLIRT_STATUS2;}
	if ($sql_array->adv_approved==2) {$isenabled="DISABLED"; $hasadvert=SENDFLIRT_STATUS3;}
} else {
	$hasadvert="No advert present";
	$isenabled="DISABLED";
}
mysql_close($link);
?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
  </tr>
  <tr>
    <td class="pageheader"><?echo FLIRT_SECTION_NAME?></td>
  </tr>
  <tr>  <form method="post" action="<?php echo $CONST_LINK_ROOT?>/prgsendflirt.php?<?php print("&userid=$userid&handle=$handle"); ?>">
 <td>
<?echo SENDFLIRT_TEXT1?> <p><font color="#B40000"><?echo SENDFLIRT_TEXT2?>:
        <?php print("$hasadvert"); ?></font></p>
      <p><?echo SENDFLIRT_TEXT3?></p>
      <p>
        <input class='button' type="submit" value="<?printf("Flirt with %s Now!",$handle);?>" name="btnNotify" <?php print("$isenabled") ?>>
      </p>
      </td></form>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>