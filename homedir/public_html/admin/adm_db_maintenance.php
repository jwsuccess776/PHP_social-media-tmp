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

# Name:                 adm_db_maintenance.php

#

# Description:

#

# Version:                7.2

#

######################################################################

include('../db_connect.php');

include_once('../validation_functions.php');

include('../session_handler.inc');

include('permission.php');



$lstNotify=sanitizeData($_POST['lstNotify'], 'xss_clean'); 

$lstMessages=sanitizeData($_POST['lstMessages'], 'xss_clean'); 

$lstEncounters=sanitizeData($_POST['lstEncounters'], 'xss_clean'); 



$message = "";



if (isset($_POST["optimize"])) {

	if ($lstNotify > 0) {

		$result=mysqli_query($globalMysqlConn, "DELETE FROM notifications WHERE ntf_dateadded <= DATE_SUB(NOW(), INTERVAL $lstNotify MONTH)");	

		$numNotify=mysqli_affected_rows();

        $query = mysqli_query($globalMysqlConn, "OPTIMIZE TABLE notifications");

		$message .= DB_NOTIFICATIONS.": ".$numNotify."<br>";

	}

	if ($lstMessages > 0) {

		$result=mysqli_query($globalMysqlConn,"DELETE FROM messages WHERE msg_dateadded <= DATE_SUB(NOW(), INTERVAL $lstMessages MONTH)");	

		$numMessages=mysqli_affected_rows();

        $query = mysqli_query($globalMysqlConn,"OPTIMIZE TABLE messages");

		$message .= DB_MESSAGES.": ".$numMessages."<br>";

	}

	if ($lstEncounters > 0) {

		$result=mysqli_query($globalMysqlConn,"DELETE FROM encounters WHERE enc_viewdate <= DATE_SUB(NOW(), INTERVAL $lstEncounters MONTH)");	

		$numEncounters=mysqli_affected_rows();

        $query = mysqli_query($globalMysqlConn,"OPTIMIZE TABLE encounters");

		$message .= DB_ENCOUNTERS.": ".$numEncounters."<br>";

	}

}

// mysqli_close();



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

    <td class="pageheader"><?php echo DB_MAINTENANCE_SECTION_NAME ?></td>

  </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

  <tr>

    <td height="40px"><?php echo DB_MAINTENANCE_TEXT?></td>

  </tr>

  <tr>

    <td>

        <table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

        <form action="<?php echo $CONST_LINK_ROOT?>/admin/adm_db_maintenance.php" method="post" name="frmMaintenance">

        <input type="hidden" name="optimize" value="yes">

        <?

        if (!empty($message)) {

            echo '<tr><td colspan="2" class="tdhead">'.$message.'</td></tr>';

        }

        ?>

          <tr>

            <td class="tdfoot"><?php echo ADM_NOTIFY_CLEAR ?></td>

			<td class="tdfoot">

			<select name="lstNotify" class="input">

                <option value="0" selected>- <?php echo GENERAL_CHOOSE?> -</option>

				<option value=3><?php echo DB_OPTION_3MONTH_LABEL ?></option>

				<option value=6><?php echo DB_OPTION_6MONTH_LABEL ?></option>

				<option value=12><?php echo DB_OPTION_12MONTH_LABEL ?></option>

			</select>

			</td>

          </tr>

          <tr>

            <td class="tdfoot"><?php echo ADM_MESSAGES_CLEAR ?></td>

			<td class="tdfoot">

			<select name="lstMessages" class="input">

                <option value="0" selected>- <?php echo GENERAL_CHOOSE?> -</option>

				<option value="3"><?php echo DB_OPTION_3MONTH_LABEL ?></option>

				<option value="6"><?php echo DB_OPTION_6MONTH_LABEL ?></option>

				<option value="12"><?php echo DB_OPTION_12MONTH_LABEL ?></option>

			</select>

			</td>

          </tr>

          <tr>

            <td class="tdfoot"><?php echo ADM_ENCOUNTERS_CLEAR ?></td>

			<td class="tdfoot">

			<select name="lstEncounters" class="input">

                <option value="0" selected>- <?php echo GENERAL_CHOOSE?> -</option>

				<option value="3"><?php echo DB_OPTION_3MONTH_LABEL ?></option>

				<option value="6"><?php echo DB_OPTION_6MONTH_LABEL ?></option>

				<option value="12"><?php echo DB_OPTION_12MONTH_LABEL ?></option>

			</select>

			</td>

          </tr>

          <tr align="center">

            <td colspan="2" class="tdfoot"> <input name="Submit" type="submit" class="button" onClick="return delete_alert8();" value="<?php echo DB_OPTIMIZE_BUTTON?>">

            </td>

          </tr>

        </form>

      </table></td>

  </tr>

</table>

<?=$skin->ShowFooter($area)?>