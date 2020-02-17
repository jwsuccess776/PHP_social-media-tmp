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

# Name:				 adm_payedit.php

#

# Description:

#

# Version:				7.2

#

######################################################################

include('../db_connect.php');

include('../session_handler.inc');

include('../message.php');

include('../functions.php');

include('../error.php');

include('permission.php');



$sde_eventid = $_GET['sde_eventid'];



# retrieve the template

$area = 'member';



?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

  <tr>

    <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

    </td>

  </tr>

  <tr>

    <td class="pageheader"><?php echo ADM_PAYMENTS_EDIT ?></td>

  </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

	<?php

	$sql_result = mysqli_query($globalMysqlConn,"SELECT * FROM  payment_systems WHERE ps_id='$pay_id'");

	$payment = mysqli_fetch_object($sql_result);

	?>

	<form method="post" action="<?php echo $CONST_LINK_ROOT?>/admin/adm_paysystems.php" name="event">

	<input type=hidden name=action value=save>

	<input type=hidden name=pay_id value="<?=$pay_id?>">

   <tr>

		<td>

		<table border="0" width=100% cellpadding="2" cellspacing="0" >

            <tr>

                <td colspan="2" align="left" valign="top" class="tdhead">&nbsp; </td>

            </tr>

			<tr>

				<td><?=ADM_PAYMENTS_TITLE?></td>

				<td><input type=text name=pay_title class="input" value="<?=$payment->ps_title?>"></td>

			</tr>

			<tr>

				<td colspan=2 align=center>&nbsp;</td>

			</tr>

			<tr>

				<td colspan=2 class="tdfoot" align=center><input type=submit class=button name=SAVE value="<?=GENERAL_SAVE?>"></td>

			</tr>

		</table>

		</td>

	</tr>

	</form>

</table>



<?php //mysql_close( $link );
 ?>

<?=$skin->ShowFooter($area)?>