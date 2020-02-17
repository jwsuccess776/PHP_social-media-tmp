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

# Name: 		cancelled.php

#

# Description:  Page displayed after a user pays for membership

#

# Version:		5.0

#

######################################################################

include('../db_connect.php');

include('../session_handler.inc');



$result = mysqli_query($globalMysqlConn,"SELECT * FROM payments WHERE pay_userid=$Sess_UserId ORDER BY pay_date DESC LIMIT 1");

$payment = mysqli_fetch_object($result);



# retrieve the template

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

    <td class="pageheader"><?php echo CANCEL_SECTION_NAME ?></td>

  </tr>

  <tr>

    <td valign='top' align='left' width='634'>

       <?php include_once __INCLUDE_CLASS_PATH.'/'.$payment->pay_service."_cancel.php";?>

    </td>

  </tr>

</table>

<?=$skin->ShowFooter($area)?>