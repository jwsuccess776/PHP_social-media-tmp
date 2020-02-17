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

# Name: 		language.php

#

# Description:

#

# # Version:      8.0

#

######################################################################



include('../db_connect.php');

include('../session_handler.inc');

include('../message.php');

include('../pop_lists.inc');

include('../error.php');

include('permission.php');



$langs=formGet('langs');

$mode=formGet('mode');

# retrieve the template

$area = 'member';



switch ($mode) {

	case 'save':

		restrict_demo();

        $language->DeactivateAll();

        $language->Activate($CONST_DEFAULT_LANGUAGE);

        if (!in_array($CONST_DEFAULT_LANGUAGE,$langs)) error_page("You can't deactivate default language [$CONST_DEFAULT_LANGUAGE]",GENERAL_USER_ERROR);

		foreach ((array)$langs as $lang) {

			$language->Activate($lang);

		}

        if (count($language->error)) error_page(join("<br>",$language->error),GENERAL_USER_ERROR);

		break;

}

?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

  <tr>

    <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

    </td>

  </tr>

  <tr>

    <td class="pageheader"><?php echo LANGUAGE_SECTION_NAME ?></td>

  </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

  <tr>

    <td><?php echo LANGUAGE_HELP ?></td>

  </tr>

  <tr>

    <td><table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

        <form enctype='multipart/form-data' method='post' action='<?php echo $CONST_LINK_ROOT?>/admin/language.php' name="FrmListbox">

          <tr>

            <td colspan="2" align="left" valign="top" class="tdhead">&nbsp; </td>

          </tr>

          <?php

$query="SELECT * FROM langfile";

$res = mysqli_query($globalMysqlConn,$query);

while ($lang = mysqli_fetch_assoc($res)) {

?>

          <tr class='tdodd'>

            <td align='left' valign='top' >

              <?= $lang['lang_name'] ?>

              &nbsp;</td>

            <td align='left' valign='top' > <input border=0 type='checkbox' value="<?= $lang['lang_id'] ?>" name=langs[] <?if ($lang['lang_active']){ ?>checked<?}?>></td>

          </tr>

          <?

									}

?>

          <tr>

            <td colspan="2" align="center" class="tdfoot"> <input name="submit" type='submit' onClick="FrmListbox.mode.value='save';" value='<?= GENERAL_SAVE?>' class='button'>

              <input type='hidden' name='mode' value='save'> </td>

          </tr>

        </form>

      </table></td>

  </tr>

</table>



<?=$skin->ShowFooter($area)?>

