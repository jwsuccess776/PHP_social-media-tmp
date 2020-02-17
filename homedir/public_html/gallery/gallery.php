<?
include('../db_connect.php');
include($CONST_INCLUDE_ROOT.'/session_handler.inc');
include($CONST_INCLUDE_ROOT.'/error.php');
include_once __INCLUDE_CLASS_PATH."/class.Gallery.php";
include_once __INCLUDE_CLASS_PATH."/class.GalleryItem.php";
include($CONST_INCLUDE_ROOT.'/message.php');
$area = 'member';
$user_id = formGet('user_id');
$gallery = new Gallery();
$aLevel = array('Public');
$query="SELECT * FROM hotlist WHERE hot_userid='$user_id' AND hot_advid='$Sess_UserId'";
if ($hot_row = $db->get_row($query)) $aLevel[] = 'Hotlist';
if ($user_id == $Sess_UserId) {
    $aLevel[] = 'Private';
    $aLevel[] = 'Hotlist';
}
$aGallery = $gallery->GetListByMember($user_id,$aLevel);
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
        <?=GALLERY?>
    </td>
  </tr>
  <tr>
    <td>
        <table width="100%"  border="0" cellpadding="1" cellspacing="0">
          <tr align="left">
<?
foreach ($aGallery as $gallery_row){
    $aItems = $gallery->GetApprovedItemList($gallery_row->Gallery_ID);
    $src = '';
    if (count($aItems)){
        $item = new GalleryItem();
        $item->Init(array_shift($aItems));
        $src = $item->File->getInfo('small');
?>
        <td width="20%">
        <table width="100%"  border="0" cellpadding="1" cellspacing="0">
          <tr align="left">

          <td align=center> <a href="<?=$CONST_GALLERY_LINK_ROOT?>/item_list.php?Gallery_ID=<?=$gallery_row->Gallery_ID?>">
            <?if ($src) {?>
            </a>
            <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="imageframe"><a href="<?=$CONST_GALLERY_LINK_ROOT?>/item_list.php?Gallery_ID=<?=$gallery_row->Gallery_ID?>"><img border='0' src='<?=$CONST_LINK_ROOT?><?=$src->Path?>' width='<?=$src->w?>'></a></td>
              </tr>
            </table>
            <a href="<?=$CONST_GALLERY_LINK_ROOT?>/item_list.php?Gallery_ID=<?=$gallery_row->Gallery_ID?>">
            <?}?>
            <?= $gallery_row->Name?>
            </a> 
          </td>
          </tr>
        </table>
        </td>
    <? if (++$i % 5 == 0){?>
      </tr><tr>
    <? } ?>
<?}}?>
   </table>
    </td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>