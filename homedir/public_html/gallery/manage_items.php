<?php
/*****************************************************
* � copyright 1999 - 2020 iDateMedia, LLC.
*
* All materials and software are copyrighted by iDateMedia, LLC.
* under British, US and International copyright law. All rights reserved.
* No part of this code may be reproduced, sold, distributed
* or otherwise used in whole or in part without prior written permission.
*
*****************************************************/
######################################################################
#
# Name: 		prgpicadmin.php
#
# Description:  Adds and removes additional photos for members
#
# # Version:      8.0
#
######################################################################

include('../db_connect.php');
include($CONST_INCLUDE_ROOT.'/session_handler.inc');
include($CONST_INCLUDE_ROOT.'/error.php');
include_once __INCLUDE_CLASS_PATH."/class.Gallery.php";
include_once __INCLUDE_CLASS_PATH."/class.GalleryItem.php";
include($CONST_INCLUDE_ROOT.'/message.php');


if (formGet('Gallery_ID')) $_SESSION[$SCRIPT_NAME]['Gallery_ID'] = formGet('Gallery_ID');
$Gallery_ID = $_SESSION[$SCRIPT_NAME]['Gallery_ID'];

$gallery = new Gallery();

if (!$Gallery_ID) die ("Unknown Gallery_ID");

$gallery->Init($Gallery_ID);
if ($gallery->mem_id != $Sess_UserId) die ("Hack attempt");

# retrieve the template
$area = 'member';

if (formGet('ADD')){
    $item = new GalleryItem();
//    dump($_FILES);
    $aType = explode("/",$_FILES['file']['type']);
    $data = array(	'Gallery_ID'	=> $Gallery_ID,
                    'filepath'	    => $_FILES["file"]["tmp_name"],
                    'Type'	        => ucfirst(array_shift($aType)),
                    'SubType'	    => array_shift($aType),
                  );

    $result = $item->Init((object)$data);
    if ($result === null) error_page(join("<br>",$item->error),GENERAL_USER_ERROR);

    $result = $item->Save();
    if ($result === null) error_page(join("<br>",$item->error),GENERAL_USER_ERROR);
}

if (formGet('EDIT')) {

    $chkRemove=formGet('chkRemove');

    if (isset($chkRemove)) {
        foreach ( $chkRemove as $key=>$value) {
            $item = new GalleryItem();
            if (($result = $item->Init($value)) !== null) {
                $item->Delete();
            } else {

                error_page(join("<br>",$item->error),GENERAL_USER_ERROR);
            }
        }
    }
}

$aItems = $gallery->GetItemList($Gallery_ID);

?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right">
        <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
  </tr>
  <tr>
    <td class="pageheader"><?include_once "$CONST_INCLUDE_ROOT/media_menu.inc.php"?></td>
  </tr>

  <tr>
    <td><?php echo PRGPICADMIN_TEXT?>

    </td>
  </tr>
  <tr>
    <td> <table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">
        <form enctype='multipart/form-data' method='post' action="<?php echo $CONST_GALLERY_LINK_ROOT?>/manage_items.php" name="FrmPicture">
          <tr class="tdtoprow" >
            <td colspan=4>
                <input type='hidden' name='MAX_FILE_SIZE' value="2000000" size='20' class='inputf'>
                <input type='file' name='file' size='20' class='inputf'>
                <input type='submit' name='ADD' value='Add picture' class='button' >
            </td>
          </tr>
          <tr>
            <td  colspan="5" align="left" valign="top" class="tdhead"><strong><?php echo PRGPICADMIN_PHOTOS?></strong></td>
          </tr>
          <tr class="tdtoprow" >
            <td align="left">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="center"><b><?php echo PRGPICADMIN_REMOVE?></b></td>
          </tr>
          <?php
$indx=0;
    foreach ($aItems as $item_row){
    $item = new GalleryItem();
    $item->Init($item_row);
    $indx++;
//    dump($item);
    if ($item->Type == 'Image') {
        $src = $item->File->getInfo('small');
        $src_full = $item->File->getInfo();
        $link = "<a rel='lightbox' href=\"$CONST_LINK_ROOT/$src_full->Path\"><img border='0' src='$CONST_LINK_ROOT"."$src->Path' width='$src->w'></a>";
    } elseif ($item->Type == 'Video') {
        $src = $item->File->getInfo();
        $link = "<a href=\"$CONST_LINK_ROOT/$src->Path\"><img border='0' src='$CONST_IMAGE_ROOT"."$CONST_IMAGE_LANG/video.gif' width='60' height='45'></a>";
    }
        print(" <tr class='tdodd'>
                <td align='left'>$indx</td>
                <td>{$item->StatusList[$item->Approved]}<input type='hidden' name='pic_exists[]' value=$item->GalleryItem_ID></td>
                <td align='center'>$link</td>
                <td align='center'><input type='checkbox' name='chkRemove[]' value='{$item->GalleryItem_ID}'></td>
          </tr>");
}
        $result++;
        print(" <tr class='tdeven'>
                <td align='left'>&nbsp;</td>
                <td></td>
                <td align='center'>&nbsp;</td>
                <td align='center'>&nbsp;</td>
          </tr>");

?>
          <tr>
            <td  colspan="5" align="left" class="tdfoot">&nbsp;</td>
          </tr>
          <tr align="center">
            <td valign="top"  colspan="5"> <input type="submit" name="EDIT" value="<?php echo BUTTON_UPDATE ?>" class="button">
            <input type="button" name="BACK" value="<?php echo BUTTON_BACK ?>" class="button" onClick="window.location='<?php echo $CONST_GALLERY_LINK_ROOT?>/manage_gallery.php'">
            </td>
          </tr>
        </form>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>