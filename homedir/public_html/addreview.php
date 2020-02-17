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
# Name: 		addrewiev.php
#
# Description:  Displays the profile input page (after advert)
#
# # Version:      8.0
#
######################################################################

include('db_connect.php');
include('session_handler.inc');
include('error.php');
include_once('validation_functions.php');

$type=sanitizeData($_REQUEST['type'], 'xss_clean');
$id=sanitizeData($_REQUEST['id'], 'xss_clean');  

$area = 'member';
?>
<?=$skin->ShowHeader($area)?>
 <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
    <tr>
    <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>       </td>
    </tr>
    <tr>

    <td class="pageheader"><?php echo ADDREVIEW_SECTION_NAME ?></td>
    </tr>
    <tr><td>
	<table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
        <form enctype='multipart/form-data' method='post' action='<?php echo $CONST_LINK_ROOT ?>/admin/prgaddreview.php' name="FrmEvent" onSubmit="">
          <input type="hidden" name="type" value="<?= $type ?>">
          <input type="hidden" name="id" value="<?= $id ?>">
          <tr align="left" >
            <td colspan="2" class="tdhead"><?=GENERAL_ADREVIEW?> </td>
          </tr>
          <tr align="left" class="tdodd" >
            <td valign="top">&nbsp;</td>
            <td valign="top"> <textarea  class="inputl" name="txtReview" cols="45" rows="8" id="textarea2"><?php echo $txtEventName?></textarea></td>
          </tr>
          <tr align="center">
            <td colspan="2" class="tdfoot" >
              <input type="submit" name="Submit" value="<?php echo BUTTON_SUBMIT ?>" class="button">
            </td>
          </tr>
        </form>
      </table></td>
    </tr>
  </table>
<?=$skin->ShowFooter($area)?>

