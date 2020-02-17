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
# Name: 		p_privacy.php
#
# Description:  Privacy statement before login
#
# # Version:      8.0
#
######################################################################
include('../db_connect.php');
include("../languages/big_text_$CONST_FILE_LANG.php");
# retrieve the template
$area = 'speeddating';

?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

  <tr>
    <td class="pageheader">
      <?php echo PRIVACY_SECTION_NAME ?>
    </td>
  </tr>
  <tr>
    <td>
    <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
        <tr>
          <td height="20" align="left" class="tdhead" >&nbsp;</td>
        </tr>
        <tr>
          <td height="20" align="left" valign="top" class="tdodd">
            <?=$div_str_bottom;?>
          </td>
        </tr>
        <tr>
          <td height="20" align="left" class="tdeven" ><?php echo AFF_PRIVACY_TEXT?></td>
        </tr>
        <tr>
          <td height="20" align="left" class="tdfoot">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>

<?=$skin->ShowFooter($area)?>

