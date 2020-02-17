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
# Name:         tipafriend.php
#
# Description:  Page displays input for tip a friend
#
# # Version:      8.0.0
#
######################################################################

include('db_connect.php');
include('session_handler.inc');
include_once('validation_functions.php');

$handle=sanitizeData(trim($_GET['handle']), 'xss_clean');
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

    <td class="pageheader"><?php echo TIP_MAIN_SECTION_NAME ?></td>
    </tr>
    <tr>
      <td><table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>"><form method='post' action='<?php echo $CONST_LINK_ROOT?>/prgtipmail.php' name="FrmTipMail">
          <input type='hidden' name='handle' value='<?php print("$handle"); ?>'>
          <tr>
            <td align="left" colspan="2" class="tdhead">
              <?=TIPAFRIEND_TIP?>
              <b><?php print(" $handle"); ?> </b></td>
          </tr>
          <tr class="tdodd">
            <td>
              <?=TIPAFRIEND_EMAIL?>
              1:</td>
            <td> <input type="text" class="input" name="txtEmail1" size="50" tabindex="1"></td>
          </tr>
          <tr class="tdeven">
            <td>
              <?=TIPAFRIEND_EMAIL?>
              2:</td>
            <td> <input type="text" class="input" name="txtEmail2" size="50" tabindex="1"></td>
          </tr>
          <tr class="tdodd">
            <td>
              <?=TIPAFRIEND_EMAIL?>
              3:</td>
            <td> <input type="text" class="input" name="txtEmail3" size="50" tabindex="1"></td>
          </tr>
          <tr class="tdeven">
            <td>
              <?=TIPAFRIEND_EMAIL?>
              4:</td>
            <td> <input type="text" class="input" name="txtEmail4" size="50" tabindex="1"></td>
          </tr>
          <tr class="tdodd">
            <td>
              <?=TIPAFRIEND_EMAIL?>
              5:</td>
            <td> <input type="text" class="input" name="txtEmail5" size="50" tabindex="1"></td>
          </tr>
          <tr class="tdeven">
            <td>
              <?=TIPAFRIEND_MESSAGE?>
            </td>
            <td> <textarea  class="inputl" rows="5" name="txtMessage" cols="48" tabindex="2"></textarea></td>
          </tr>
          <tr align="center">
            <td  class="tdfoot" colspan="2" valign="top"> <center>
              </center>
              <input type="submit" name="Submit" value="<?php echo BUTTON_SENDMAIL ?>" class="button">
              <input type="button" name="Submit2" value="<?php echo BUTTON_BACK ?>" class="button" onClick="javascript:history.go(-1);"></td>
          </tr>
        </form>
      </table></td>
    </tr>
  </table>
<?=$skin->ShowFooter($area)?>