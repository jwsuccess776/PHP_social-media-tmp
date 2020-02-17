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
# Name:         resend.php
#
# Description:  displays the form for resending password details
#
# # Version:      8.0
#
######################################################################

include('db_connect.php');
# retrieve the template
$area = 'guest';

?>
<?=$skin->ShowHeader($area)?>
  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
    <tr>

    <td align="right">&nbsp;
      </td>
    </tr>
    <tr>

    <td class="pageheader"><?php echo RESEND_SECTION_NAME ?></td>
    </tr>
    <tr>
      <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
        <form method="post" action="<?php echo $CONST_LINK_ROOT?>/prgresend.php" name="FrmResend">
          <tr>
            <td colspan="2" class="tdhead">&nbsp;</td>
          </tr>
          <tr class="tdodd" >
            <td>
              <?=RESEND_EMAIL?>
            </td>
            <td> <input type="text" class="input" name="txtEmail" size="28" tabindex="1">
              <a href="javascript:MDM_openWindow('<?php echo $CONST_LINK_ROOT?>/help/hresend1.php','Help','width=250,height=375')"><img border='0' src='<?=$CONST_IMAGE_ROOT?><?php echo $CONST_IMAGE_LANG ?>/help_but.gif'></a></td>
          </tr>
          <tr>
            <td colspan="2" align="center" class="tdfoot"> <input type="submit" name="Submit" value="<?php echo BUTTON_RESEND ?>" class="button"></td>
          </tr>
          <tr>
            <td colspan="2">
              <?=RESEND_NOTE?>

                          </td>
          </tr>
        </form>
      </table></td>
    </tr>
  </table>
<?=$skin->ShowFooter($area)?>