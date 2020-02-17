<?
include('../db_connect.php');
include($CONST_INCLUDE_ROOT.'/session_handler.inc');
include($CONST_INCLUDE_ROOT.'/error.php');
include_once __INCLUDE_CLASS_PATH."/class.Gallery.php";
include_once __INCLUDE_CLASS_PATH."/class.GalleryItem.php";
include($CONST_INCLUDE_ROOT.'/message.php');

$area = 'member';
$gallery = new Gallery();

if (formGet('DELETE')){
    $gallery->Init(formGet('gallery_id'));
    $gallery->Delete($Sess_UserId);
}

$aGallery = $gallery->GetListByMember($Sess_UserId);
?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <form enctype='multipart/form-data' method='post' action='<?php echo $CONST_GALLERY_LINK_ROOT?>/manage_gallery.php'>
    <input type=hidden name="DELETE" value="DELETE">
    <input type=hidden  name="gallery_id" value="">
    <tr>
      <td colspan="5" align="right">
        <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
      </td>
    </tr>
    <tr>
      <td colspan="5" >
        <?include_once "$CONST_INCLUDE_ROOT/media_menu.inc.php"?>
      </td>
    </tr>
    <tr>
      <td colspan="5" align=left   class='tdhead'>
        <?=GALLERY?>
      </td>
    </tr>
    <tr><td>
<table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">
          <?foreach ($aGallery as $gallery_row){?>
          <tr class="tdodd">
            <td><a href="<?=$CONST_GALLERY_LINK_ROOT?>/manage_items.php?Gallery_ID=<?=$gallery_row->Gallery_ID?>">
              <?= $gallery_row->Name?>
              </a></td>
            <td width="45%">
              <?= $gallery->LimitList[$gallery_row->Level]?>
            </td>
            <td align="right"> <input name="button" type=button class=button onClick="document.location.href='<?=$CONST_GALLERY_LINK_ROOT."/edit_gallery.php"?>?Gallery_ID=<?=$gallery_row->Gallery_ID?>';" value="<?=GALLERY_EDIT?>">
            </td>
            <td align="right"> <input name="button" type=button class=button onClick="document.location.href='<?=$CONST_GALLERY_LINK_ROOT."/manage_items.php"?>?Gallery_ID=<?=$gallery_row->Gallery_ID?>';" value="<?=GALLERY_PIC_EDIT?>">
            </td>
            <td align="right"> <input name="button" type=button class=button onClick="if (delete_alert7()){this.form.gallery_id.value='<?=$gallery_row->Gallery_ID?>'; this.form.submit();}" value="<?=BUTTON_REMOVE?>">
            </td>
          </tr>
          <?}?>
          <tr align="center">
            <td colspan="5" class="tdfoot">&nbsp;</td>
          </tr>
          <tr align="center">
            <td colspan="5" > <input name="button2" type=button class=button onClick="document.location.href='<?=$CONST_GALLERY_LINK_ROOT."/edit_gallery.php"?>';" value="<?=GALLERY_ADD?>">
            </td>
          </tr>
        </table>
    </td></tr>
  </form>
</table>
<?=$skin->ShowFooter($area)?>