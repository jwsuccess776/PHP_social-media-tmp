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

# Name:                 adm_export.php

#

# Description:

#

# Version:                7.2

#

######################################################################

include('../db_connect.php');

include('../session_handler.inc');

include('../message.php');

require_once('../error.php');

require_once('../functions.php');

include('permission.php');



$tables=array();

$result=mysqli_query($globalMysqlConn, "SHOW TABLES");

while ($row = mysqli_fetch_array($result)) $tables[]=$row[0];



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

    <td class="pageheader"><?php echo ADM_EXPORT_SECTION_NAME ?></td>

  </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

  <tr>

    <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

        <form action="<?php echo $CONST_LINK_ROOT ?>/admin/export.php" method="post">

        <tr><td colspan="3" align="left" class="tdhead" >&nbsp;</td></tr>

        <tr class="tdodd" valign="top">

            <td><?=ADM_EXPORT_TABLES?></td>

            <td><select class="input" name="lstTables[]" multiple><?php foreach ($tables as $table) echo "<option>".$table; ?></select></td>

            <td width="50%"><input type="submit" value="<?=ADM_EXPORT_EXPORT?>" class="button"></td>

        </tr>

        <tr class="tdeven" valign="top">

            <td><?=ADM_EXPORT_COMPRESSION?></td>

            <td><input type="radio" name="arcType" value="" id="arcTypeNone" checked> <label for="arcTypeNone"><?=ADM_EXPORT_COMPRESSION_NONE?></label>

            <br><input type="radio" name="arcType" value="gzip" id="arcTypeGzip"> <label for="arcTypeGzip"><?=ADM_EXPORT_COMPRESSION_GZIP?></label></td>

            <td>&nbsp;</td>

        </tr>

        <tr><td colspan="3" align="center" class="tdfoot">&nbsp;</td></tr>

        </form>

    </table></td>

  </tr>

</table>

<?php //mysqli_close( $link ); ?>

<?=$skin->ShowFooter($area)?>