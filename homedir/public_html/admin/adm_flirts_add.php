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
# Name:              adm_flirt_add.php
#
# Description:
#
# Version:              7.2
#
######################################################################
include('../db_connect.php');
include('../session_handler.inc');
include('../functions.php');
include('permission.php');

# retrieve the template
$area = 'member';
$Flirt_ID= formGet('Flirt_ID');
$lang_id= $db->escape(formGet('lang'));
$flirt = $db->get_row("SELECT * FROM lang_flirt WHERE Flirt_ID='$Flirt_ID' AND lang_id = '$lang_id'");
//$db->debug();

?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
        <tr>
      <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
      </td>
    </tr>
    <tr>

    <td class="pageheader">
      <?if ($flirt){?> <?= ADM_FLIRTS_ADD_SECTION_NAME ?> <?}else {?><?= ADM_FLIRTS_EDIT_SECTION_NAME ?> <?}?>
    </td>
    </tr>
  <tr>
    <td><? include("admin_menu.inc.php");?></td>
  </tr>
    <tr>
      <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
        <form method="post" action="<?php echo $CONST_LINK_ROOT?>/admin/adm_flirts.php" enctype="multipart/form-data">
          <input type="hidden" name="act" value="save">
          <input type="hidden" name="Flirt_ID" value="<?=$Flirt_ID?>">
          <input type="hidden" name="lang" value="<?=$lang_id?>">
          <tr>
            <td align="center">

	   <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

                <tr>
                  <td colspan="2" class="tdhead">&nbsp;</td>
                </tr>
<?foreach($language->GetActiveList($lang_id) as $lang) {?>
                <tr class="tdodd">
                  <td>
                    <?=ADM_FLIRTS_TEXT?> (<?=$lang->LangID?>)
                  </td>
                  <td> <input name="text[<?=$lang->LangID?>]" type="text" size=60 maxlength=60 class="input" value="<?=htmlspecialchars($flirt->Text)?>"></td>
                </tr>
<?}?>
                <tr>
                  <td colspan="2" align="center" class="tdfoot"> <input name="submit" type="submit" class="button" value="<?=GENERAL_SAVE?>">
                    <input name="button" type="button" class="button" onclick="window.location = '<?=$CONST_LINK_ROOT?>/admin/adm_flirts.php'" value="<?=GENERAL_CANCEL?>">
                  </td>
                </tr>

              </table></td>
          </tr>
        </form>
      </table></td>
    </tr>
  </table>
<?=$skin->ShowFooter($area)?>
