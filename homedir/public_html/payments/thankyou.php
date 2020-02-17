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
# Name: 		thankyou.php
#
# Description:  Page displayed after a user pays for membership
#
# Version:		5.0
#
######################################################################
include('../db_connect.php');
include('../session_handler.inc');
# retrieve the template
# check the result of the payment
$result = mysql_query("SELECT * FROM payments WHERE pay_userid=$Sess_UserId ORDER BY pay_date DESC LIMIT 1",$link);
$payment = mysql_fetch_object($result);

switch ($payment->pay_service) {
    case 'premium' : $area = 'member'; break;
    case 'sd_ticket': $area = 'speeddating'; break;
}
?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
  </tr>
  <tr>
    <td class="pageheader"><?php echo PAYMENTS_SUCCESS_SECTION_NAME?></td>
  </tr>
  <tr>
    <td valign='top' align='left' width='100%' height='222'>
        <? include_once __INCLUDE_CLASS_PATH.'/'.$payment->pay_service."_ok.php";?>
    </td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>