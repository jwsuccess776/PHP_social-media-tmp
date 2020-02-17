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
# Name:         sendmail.php
#
# Description:  Displays the page that a member uses to send mail
#
# # Version:      8.0
#
######################################################################

include('db_connect.php');
include('session_handler.inc');
include('error.php');
include_once('validation_functions.php');

$userid=sanitizeData($_GET['userid'], 'xss_clean') ;  
$handle=sanitizeData($_GET['handle'], 'xss_clean') ;    
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

    <td class="pageheader"><?php echo SEND_MAIL_SECTION_NAME ?></td>
    </tr>
    <tr>
      <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
        <form method='post' action='<?php echo $CONST_LINK_ROOT?>/prgsendmail.php' name="FrmSendMail" onSubmit="return Validate_FrmSendMail()" >
          <tr>
            <td colspan="2" class="tdhead" >
              <?=SENDMAIL_TO?>
              : <?php echo $handle ?> <input type='hidden' name='userid' value='<?php print("$userid"); ?>'>
              <input type='hidden' name='myhandle' value='<?php print("$Sess_UserName"); ?>'>
              <input type="hidden" name="txtTo" size="25" value="<?php print("$handle"); ?>">
            </td>
          </tr>
          <tr class="tdodd">
            <td ><b>
              <?=SENDMAIL_SUBJECT?>
              :</b></td>
            <td > <input type="text" class="input" name="txtSubject" size="50" tabindex="1"></td>
          </tr>
          <tr class="tdeven" >
            <td valign="top" ><b>
              <?=SENDMAIL_MESSAGE?>
              :</b></td>
            <td > <textarea  class="inputl"rows="15" name="txtMessage" cols="54" tabindex="2"></textarea></td>
          </tr>
          <tr >
            <td colspan="2" align="center" valign="top" class="tdfoot" > <input name="Validate2" type="submit" class="button" value="<?php echo BUTTON_SENDMAIL ?>">

              </td>
          </tr>
          <tr >
            <td colspan="2" valign="top" >&nbsp;</td>
          </tr>
          <tr >
            <td colspan="2" valign="top" ><a href='javascript:history.go(-1);'>
              <?=BUTTON_BACK?>
              </a></td>
          </tr>
          <tr >
            <td colspan="2" valign="top" >&nbsp;</td>
          </tr>
          <tr >
            <td colspan="2" valign="top" ><font face="Verdana" size="1">
              <?=SENDMAIL_NOTE?>
              </font></td>
          </tr>
        </form>
      </table></td>
    </tr>
  </table>
<?=$skin->ShowFooter($area)?>