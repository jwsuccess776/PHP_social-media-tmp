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

# Name: 		prgshowmail.php

#

# Description:  displays members received mail

#

# # Version:      8.0

#

######################################################################

include('db_connect.php');

include('session_handler.inc');

$mailid=$_GET['mailid'];

if (isset($_GET['showmode'])) $showmode=$_GET['showmode'];

# retrieve the template

$area = 'member';

$query="SELECT * FROM messages WHERE msg_id = '$mailid' AND (msg_senderid='$Sess_UserId' OR msg_receiverid='$Sess_UserId')";

$result=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());

$TOTAL = mysqli_num_rows($result);

if ($TOTAL < 1) {

        header("Location: $CONST_LINK_ROOT/myemail.php");

        exit;

}

$sql_array = mysqli_fetch_object($result);

$sql_array->msg_text=stripslashes($sql_array->msg_text);

$sql_array->msg_title=stripslashes($sql_array->msg_title);

if ($showmode != 'sent') {

	$query2="update messages set msg_read='R' where msg_id = '$mailid'";

	$result2=mysqli_query($globalMysqlConn, $query2);

}
// mysql_close( $link );

?>

<?=$skin->ShowHeader($area)?>

  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>

      <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>

    <td class="pageheader"><?php echo SHOW_MAIL_SECTION_NAME ?></td>

    </tr>

    <tr>

      <td>

	 <table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

        <form method='post' action='<?php echo $CONST_LINK_ROOT?>/prgsendmail.php' name="FrmSendMail" onSubmit="return Validate_FrmSendMail()" >

          <input type='hidden' name='myhandle' value='<?php print("$Sess_UserName"); ?>'>

          <input type="hidden" name="txtSubject" size="50" value="<?php print("$sql_array->msg_title"); ?>">

          <input type='hidden' name="txtMessage" value="<?=htmlspecialchars(strip_tags($sql_array->msg_text));?>">

          <input type="hidden" name="txtTo" size="25" value="<?php print("$sql_array->msg_senderhandle"); ?>">

          <tr>

            <td colspan="2" class="tdhead"><?php print("$sql_array->msg_title"); ?></td>

          </tr>

          <tr class="tdodd">

            <td><b><?php echo GENERAL_FROM?>:</b></td>

            <td align="left"> <?php print("$sql_array->msg_senderhandle"); ?></td>

          </tr>

          <tr class="tdodd">

            <td valign="top"><b><?php echo ADMINMAIL_MESS?>:</b></td>

            <td align="left" valign="top">

              <?

//                echo nl2br(htmlspecialchars($sql_array->msg_text));

                echo nl2br($sql_array->msg_text);

              ?>

            </td>

          </tr>

          <tr align="center">

            <td colspan="2" class="tdfoot">

              <?php if((isset($showmode) && $showmode=='received') && ($sql_array->msg_flirt == 'N')) print("<a href='$CONST_LINK_ROOT/replymail.php?mailid=$sql_array->msg_id'>".EMAIL_REPLY."</a> | "); ?>

              <a href='<?php echo $CONST_LINK_ROOT ?>/myemail.php'><?php echo BUTTON_BACK?></a></td>

          </tr>

        </form>

      </table></td>

    </tr>

  </table>

<?=$skin->ShowFooter($area)?>