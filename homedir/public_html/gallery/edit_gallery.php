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
# Name:              edit_gallery.php
#
# Description:
#
# Version:              7.2
#
######################################################################
include('../db_connect.php');
include($CONST_INCLUDE_ROOT.'/session_handler.inc');
include($CONST_INCLUDE_ROOT.'/functions.php');
include($CONST_INCLUDE_ROOT.'/error.php');
include_once __INCLUDE_CLASS_PATH."/class.Gallery.php";

$Gallery_ID = formGet('Gallery_ID');
$gallery = new Gallery();
if ($Gallery_ID) {
    $gallery->Init($Gallery_ID);
    if ($gallery->mem_id != $Sess_UserId) die ("Hack attempt");
}

if (formGet('act') == 'save'){
    $data = array(
        "Name"         => formGet('Name'),
        "Description"  => formGet('Description'),
        "Level"        => formGet('Level'),
        "mem_id"	   => $Sess_UserId,
    );

    $result = $gallery->Init((object)$data);
    if ($result === null) {
        error_page(join("<br>",$gallery->error),GENERAL_USER_ERROR);
    }
    $gallery->Save();

    header("Location: $CONST_GALLERY_LINK_ROOT/manage_gallery.php");
}
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
    <td class="pageheader">
      <?= $Gallery_ID ? GALLERY_EDIT : GALLERY_ADD ?>
    </td>
    </tr>
    <tr>
      <td>
      	<table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
        <form method="post" action="<?php echo $CONST_GALLERY_LINK_ROOT?>/edit_gallery.php" enctype="multipart/form-data">
          <input type="hidden" name="act" value="save">
          <input type="hidden" name="Gallery_ID" value="<?=$Gallery_ID?>">
          <tr>
            <td align="center">

	   <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
                <tr class="tdodd">
                  <td>
                    <?=GALLERY_NAME?>
                  </td>
                  <td> <input name="Name" type="text" class="input" value="<?=htmlspecialchars($gallery->Name)?>"></td>
                </tr>
                <tr class="tdodd">
                  <td>
                    <?=GALLERY_LEVEL?>
                  </td>
                  <td>
                  		<select name="Level">
                  			<?foreach ($gallery->LimitList as $key => $value){?>
                  			<option <?if ($key == $gallery->Level) echo "SELECTED"?> value="<?=$key?>"><?=$value?></option>
                  			<?}?>
                  		</select>
                  </td>
                </tr>

                <tr class="tdeven">
                  <td>
                    <?=GALLERY_DESCRIPTION?>
                  </td>
                  <td> <textarea name="Description" cols="50" rows="10" wrap="soft" class="inputl"><?=htmlspecialchars($gallery->Description)?></textarea></td>
                </tr>
                <tr>
                  <td colspan="2" align="center" class="tdfoot"> <input name="submit" type="submit" class="button" value="<?=GENERAL_SAVE?>">
                    <input name="button" type="button" class="button" onclick="window.location = '<?=$CONST_GALLERY_LINK_ROOT?>/manage_gallery.php'" value="<?=GENERAL_CANCEL?>">
                  </td>
                </tr>
              </table></td>
          </tr>
        </form>
      </table></td>
    </tr>
  </table>
<?=$skin->ShowFooter($area)?>