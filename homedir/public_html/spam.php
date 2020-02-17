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
# Name: 		spam.php
#
# Description:  Displays a spam message if multiple mails detected
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
    <td class="pageheader"><u><?php echo SPAM_HEADER ?></u></td>
  </tr>
  <tr>
    <td><?php echo SPAM_TEXT ?></td>
  </tr>
</table>
<center>
</center>
<?=$skin->ShowFooter($area)?>