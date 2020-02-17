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
# Name: 		aff_about.php
#
# Description:  Affiliate information on company
#
# # Version:      8.0
#
######################################################################

include('../db_connect.php');

# retrieve the template
$area = 'affiliate';

?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <?
	require('aff_menu.php');
?>
  <tr>
    <td>
<?php echo AFF_ABOUT_CONTENT?>
	<a href="mailto:<?php print("$CONST_AFFMAIL"); ?>"> <?php print("$CONST_AFFMAIL"); ?></a>
	 </td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>