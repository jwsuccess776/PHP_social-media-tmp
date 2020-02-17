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

# Name: 		replymail.php

#

# Description:  member page for replying to e-mails

#

# # Version:      8.0

#

######################################################################



include('db_connect.php');

include('error.php');

include_once('validation_functions.php');

include('session_handler.inc');

$mailid=sanitizeData($_GET['mailid'], 'xss_clean') ;  

# retrieve the template

$area = 'member';

$query="SELECT * FROM messages WHERE msg_id = '$mailid'";

$result=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

$sql_array = mysqli_fetch_object($result);

$sql_array->msg_text=stripslashes($sql_array->msg_text);

$sql_array->msg_title=stripslashes($sql_array->msg_title);

// mysqli_close( $link );

?>

<?=$skin->ShowHeader($area)?>

  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>

      <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>



    <td class="pageheader"><?php echo REPLY_MAIL_SECTION_NAME ?></td>

    </tr>

    <tr>

      <td>

<table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

        <form method='post' action='<?php echo $CONST_LINK_ROOT?>/prgmysendmail.php' name="FrmSendMail" onSubmit="return Validate_FrmSendMail()" >

		<input type="hidden" name="reply_flag" value="<?php echo $mailid ?>">

          <tr>

            <td colspan="2" class="tdhead" >&nbsp;</td>

          </tr>

          <tr class="tdodd">

            <td ><b><?php echo GENERAL_TO?>:</b></td>

            <td ><b>

              <input type="text" class="input" name="txtTo" size="25" value="<?php print("$sql_array->msg_senderhandle"); ?>" tabindex="1" DISABLED>

              <input type='hidden' name='userid' value='<?php print("$sql_array->msg_senderid"); ?>'>

              <input type='hidden' name='myhandle' value='<?php print("$Sess_UserName"); ?>'>

              </b></td>

          </tr>

          <tr class="tdeven">

            <td ><b><?php echo ADMINMAIL_SUB?>:</b></td>

            <td ><b>

              <input type="text" class="input" name="txtSubject" size="50" tabindex="2" value="Re: <?php print("$sql_array->msg_title"); ?>">

              </b></td>

          </tr>

          <tr class="tdodd">

            <td  valign="top"><b><?php echo ADMINMAIL_MESS?>:</b></td>

            <td ><b>

              <textarea  class="inputl"rows="15" name="txtMessage" cols="54" tabindex="3"><?php print("\n\n\n>>Previous:\n$sql_array->msg_text"); ?></textarea>

              </b></td>

          </tr>

          <tr>

            <td colspan="2" align="center"  valign="top" class="tdfoot"> <input type="submit" name="Submit" value="<?php echo BUTTON_SENDMAIL ?>" class="button">

              <a href='<?php echo $CONST_LINK_ROOT?>/myemail.php'><br>

              <b><?php echo GENERAL_BACKTOLIST ?></b></a></td>

          </tr>

        </form>

      </table></td>

    </tr>

  </table>

<?=$skin->ShowFooter($area)?>