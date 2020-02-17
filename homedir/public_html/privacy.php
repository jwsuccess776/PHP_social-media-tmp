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
# Name: 		privacy.php
#
# Description:  Privacy statement
#
# # Version:      8.0
#
######################################################################

include('db_connect.php');
// include('session_handler.inc');

# retrieve the template
if (isset($_SESSION['Sess_UserId'])){
	$area = 'member';
}	else {
    $area = 'guest';
}
?>
<?=$skin->ShowHeader($area)?>
  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
    <tr>
      <td align="right">
      </td>
    </tr>
    <tr>

    <td class="pageheader"><?php echo PRIVACY_SECTION_NAME ?></td>
    </tr>
    <tr>

    <td><?php $privacy = getPageTemplate('aff_privacy_text');?>
      <?php 
        eval("\$privacy = \"$privacy\";");
        echo $privacy; ?>
    </td>
    </tr>
  </table>
<?=$skin->ShowFooter($area)?>