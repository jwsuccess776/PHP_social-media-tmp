<?
include('../db_connect.php');
include($CONST_INCLUDE_ROOT.'/session_handler.inc');
include($CONST_INCLUDE_ROOT.'/error.php');
include_once __INCLUDE_CLASS_PATH."/class.Gallery.php";
include_once __INCLUDE_CLASS_PATH."/class.GalleryItem.php";
include($CONST_INCLUDE_ROOT.'/message.php');
include('permission.php');

$area = 'member';

$user_id = formGet('user_id');

$gallery = new Gallery();
$gallery_item = new GalleryItem();

if (FormGet('DONE')){
    $items = formGet('item');
    foreach ($items as $id => $status){
        $gallery_item->Init($id);
        $gallery_item->Approve($status);
    }
    header("Location: $CONST_ADMIN_LINK_ROOT/$SCRIPT_NAME");
    exit;

}

$user_id = $gallery->GetMemberForApprove();
if (!$user_id) {
        $error_message=PRGAUTHGALLERY_TEXT;
        display_page($error_message,PRGAUTHGALLERY_TEXT1);
}
$aGallery = $gallery->GetPendingGallery($user_id);
?>
<?=$skin->ShowHeader($area)?>
<form enctype='multipart/form-data' method='post' action='<?php echo $CONST_LINK_ROOT?>/admin/prgauthgallery.php'>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right">
		<?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
  </tr>
  <tr>
    <td align=left   class='pageheader'>
		<?=ADM_GALLERY_APPROVE_SECTION_NAME?>
    </td>
  </tr>
  <tr>
    <td><? include("admin_menu.inc.php");?></td>
  </tr>
  <tr>
    <td align=left   class='tdhead'>&nbsp;</td>
  </tr>

<?
foreach ($aGallery as $gallery_row){
?>
  <tr>
    <td>
    	<?= $gallery_row->Name?>
	  <table width="100%" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
		 <tr>
			<?
			$i=0;
            $aItems = $gallery->GetPendingItemList($gallery_row->Gallery_ID);
			foreach ($aItems as $item_row){
				$item = new GalleryItem();
			    $item->Init($item_row);
			if ($item->Type == 'Image') {
			    $src = $item->File->getInfo('medium');
			    $src_full = $item->File->getInfo();
		        $link = "<a rel='lightbox' href=\"$CONST_LINK_ROOT/$src_full->Path\"><img border='0' src='$CONST_LINK_ROOT$src->Path' width='$src->w'></a>";
			} elseif ($item->Type == 'Video') {
		        $src = $item->File->getInfo();
		        $link = "<a href=\"{$CONST_LINK_ROOT}{$src->Path}\"><img border='0' src='$CONST_IMAGE_ROOT$CONST_IMAGE_LANG/video.gif' width='60' height='45'></a>";
			}
			?>
			    <td>
			    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
			    	<tr><td>
			    	<?if ($src) {?>
			        	<?=$link?>
			    	<?}?>
			    	</td></tr>
			    	<tr><td>
			    	<select name="item[<?=$item->GalleryItem_ID?>]">
					<?foreach ($item->StatusList as $key => $value){?>
					<?if ($key!= 'Pending'){?><option value="<?=$key?>"> <?=$value?></option><?}?>
					<?}?>
					</select>
					</td></tr>
					</table>
			    </td>
			    <? if (++$i%$gallery->ImagePerLine == 0){?></tr><tr><?}?>
	      <?}?>
			</tr>
		</table>
    </td>
  </tr>
<?}?>
  <tr>
    <td>
    	<input type=submit name="DONE" value="<?=GENERAL_SAVE?>">
    </td>
  </tr>
</table>
</form>
<?=$skin->ShowFooter($area)?>
