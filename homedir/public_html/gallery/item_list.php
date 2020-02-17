<?
include('../db_connect.php');
include($CONST_INCLUDE_ROOT.'/session_handler.inc');
include($CONST_INCLUDE_ROOT.'/error.php');
include_once __INCLUDE_CLASS_PATH."/class.Gallery.php";
include_once __INCLUDE_CLASS_PATH."/class.GalleryItem.php";
include($CONST_INCLUDE_ROOT.'/message.php');

$area = 'member';

$Gallery_ID = formGet('Gallery_ID');

$gallery = new Gallery();
if (($result = $gallery->Init($Gallery_ID)) === null) die("Incorrect Gallery_ID");
$aItems = $gallery->GetApprovedItemList($Gallery_ID);

?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right">
        <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
  </tr>
  <tr>
    <td align=left   class='tdhead'>
        <a href="<?=$CONST_GALLERY_LINK_ROOT?>/gallery.php?user_id=<?=$gallery->mem_id?>"><?=GALLERY?></a> > <?= $gallery->Name?>
    </td>
  </tr>
  <tr>
  <td>
  <table width="100%" border="0" cellspacing="0<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
     <tr>
        <?
        $i=0;
        foreach ($aItems as $item_row){
            $item = new GalleryItem();
            $item->Init($item_row);

            if ($item->Type == 'Image') {
                $src = $item->File->getInfo('medium');
                $src_full = $item->File->getInfo();
                $link = "<a rel='lightbox' href=\"$CONST_LINK_ROOT/$src_full->Path\"><img border='0' src='$CONST_LINK_ROOT"."$src->Path' width='$src->w'></a>";
            } elseif ($item->Type == 'Video') {
                $src = $item->File->getInfo();
                $link = "<a href=\"{$CONST_LINK_ROOT}{$src->Path}\"><img border='0' src='$CONST_IMAGE_ROOT"."$CONST_IMAGE_LANG/video.gif' width='60' height='45'></a>";
            }
        ?>
            <td>
                <?if ($src) {?>
                    <?=$link?>
                <?}?>
            </td>
            <? if (++$i%$gallery->ImagePerLine == 0){?></tr><tr><?}?>
      <?}?>
        </tr>
    </table>
    </td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>