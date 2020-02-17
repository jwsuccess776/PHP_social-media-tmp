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

# Name: 		removeme.php

#

# Description:  Admin tool for removing members from the newsletter (those who request by mail)

#

# # Version:      8.0.0

#

######################################################################





include('../db_connect.php');

include('permission.php');



// session_cache_limiter('private, must-revalidate');
setcookie('private', 'must-revalidate');

session_start();

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



    <td class="pageheader"><?php echo REMOVE_SECTION_NAME ?></td>

    </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

    <tr>

      <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

        <form method='post' action='<?php echo $CONST_LINK_ROOT?>/admin/prgremoveme.php'>

          <tr>

            <td valign="top" align="left"  colspan="4" class="help"><?php echo REMOVE_HELP ?></td>

          </tr>

          <tr>

            <td valign="top" align="left"  colspan="4" class="tdhead">&nbsp;</td>

          </tr>

          <tr  class="tdodd">

            <td   align="left"><?php echo REMOVEME_EMAIL?>:</td>

            <td   align="left" colspan="3"> <input type="text" class="input" name="txtRemoveAddress" size="40">

            </td>

          </tr>

          <tr  class="tdeven">

            <td   align="left"><?php echo REMOVEME_BULK?>:</td>

            <td   align="left" colspan="3"> <input type="checkbox" name="chkBulkRemove" value="ON">

              (<?php echo REMOVEME_GET?>)</td>

          </tr>

          <tr class="tdfoot">

            <td valign="top" align="center"  colspan="4" class="tdfoot"> <input type="submit" name="Submit" value="<?php echo BUTTON_UPDATE ?>" class="button">

            </td>

          </tr>

        </form>

      </table></td>

    </tr>

  </table>



<?=$skin->ShowFooter($area)?>

