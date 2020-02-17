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
# Name: 		e_export.php
#
# Description:  Admin table export table selection page ('Export')
#
# # Version:      8.0
#
######################################################################

include('../db_connect.php');
include('../session_handler.inc');
include('permission.php');
# retrieve the template
$area = 'member';

# retrieve a list of database tables
$db=mysql_db_name ($link);
$result=mysql_list_tables($db);
mysql_close($link);
?>
<?=$skin->ShowHeader($area)?>
  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
    <tr>
      <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
      </td>
    </tr>
    <tr>
    <td class="pageheader"><?php echo EXPORT_SECTION_NAME ?></td>
    </tr>
  <tr>
    <td><? include("admin_menu.inc.php");?></td>
  </tr>
    <tr><td><table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">
        <form method="POST" action="<?php echo $CONST_LINK_ROOT ?>/admin/export.php">
          <tr>
            <td align="left" valign="top" class="tdhead">&nbsp;</td>
          </tr>
          <tr>
            <td align="left" valign="top" class="tdodd"> <select class="input" size="1" name="lstTables">
                <?php
				while ($tables = mysql_fetch_row($result)) {
					print("<option>$tables[0]</option>");
				}
			?>
              </select></td>
          </tr>
          <tr>
            <td align="left" valign="top" class="tdfoot">
              <input type="submit" name="Submit" value="<?php echo BUTTON_SUBMIT ?>" class="button">
            </td>
          </tr>
        </form>
      </table></td>
    </tr>
  </table>
<?=$skin->ShowFooter($area)?>
