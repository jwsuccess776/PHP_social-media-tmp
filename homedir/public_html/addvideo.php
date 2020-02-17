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
# Name:         prgpicadmin.php
#
# Description:  Adds and removes additional photos for members
#
# Version:      7.2
#
######################################################################

include('db_connect.php');
include('session_handler.inc');
include('error.php');
include('imagesizer.php');
include('message.php');

# retrieve the template
$area = 'member';

include_once __INCLUDE_CLASS_PATH."/class.Video.php";
$video = new Video();

$root_path=$CONST_INCLUDE_ROOT."/";
$max_vidsize=$option_manager->GetValue('maxvidsize');

# select advert data
$result = mysqli_query($globalMysqlConn,"SELECT * FROM adverts WHERE adv_userid=$Sess_UserId");
$TOTAL = mysqli_num_rows($result);
# if nothing is returned then show error otherwise get data
if ($TOTAL < 1) {
    $error_message=PRGPICADMIN_ERROR1;
    error_page($error_message,GENERAL_USER_ERROR);
}

if (formGet('SAVE')){
    $vid_status = ($option_manager->getValue('video_conversion')) ? 'new':'converted' ;
    $data = array(
        "vid_userid"    => $Sess_UserId,
        "vid_title"     => formGet('title'),
        "vid_description"   => formGet('description'),
        "vid_private"   => 'N',
        "vid_status"    => $vid_status,
        "tags"          => preg_split("/,|;|\s/", $tags),
        "filepath"      => $_FILES['fupload']['tmp_name'],
        "filename"      => $_FILES['fupload']['name'] ,
                );
    if ($vid_id = formGet('vid_id')) $video->initById($vid_id);

    $result = $video->InitForSave($data);
    if ($result === null) {
        error_page(join("<br>",$video->error),GENERAL_USER_ERROR);
    }
    $result = $video->Save();
    if ($result === null) {
        error_page(join("<br>",$video->error),GENERAL_USER_ERROR);
    }

    # check whether immediate authorisation
    $approved=$option_manager->GetValue('authorisead');
    $db->query("UPDATE adverts SET adv_approved = '$approved' WHERE adv_userid = '$Sess_UserId'");
    redirect("$CONST_LINK_ROOT/prgvideoadmin.php");
}
if ($vid_id = formGet('vid_id')) {
    $video->initById($vid_id);
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
    <td class="pageheader"><?php echo VIDEOADMIN_SECTION_NAME ?></td>
  </tr>
  <tr>
    <td><?php echo PRGPICADMIN_TEXT?></td>
  </tr>
  <tr>
    <td> <table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">
        <form enctype='multipart/form-data' method='post' action='<?php echo $CONST_LINK_ROOT?>/addvideo.php' name="FrmPicture" onsubmit="if (Validate_VideoUpload()) {showUploadingPopup()} else {return false;}">
        <input type=hidden name="vid_id" value="<?=$video->vid_id?>">
          <tr>
            <td align="left" colspan=2 valign="top" class="tdhead"><strong><?php echo PRGPICADMIN_VIDEO?></strong></td>
          </tr>
          <tr class='tdodd'>
            <td><?php echo PRGPICADMIN_VIDEO?></td>
            <td><input type='file' name='fupload' class='inputf' size='20'></td>
          </tr>
          <tr class='tdodd'>
            <td><?php echo ADVERTISE_TITLE?></td>
            <td><input type='text' name='title' class='inputf' value="<?=$video->vid_title?>"  ></td>
          </tr>
          <tr class='tdodd'>
            <td><?php echo GENERAL_DESCRIPTION?></td>
            <td><textarea name='description' class='inputf' cols=80 rows=5><?=$video->vid_description?></textarea></td>
          </tr>
          <tr class='tdodd'>
            <td><?php echo GENERAL_TAGS?></td>
            <td><input type='text' name='tags' class='inputl' value="<?=$video->getTags('string')?>"  ></td>
          </tr>
          <tr>
            <td colspan="2" align="left" class="tdfoot">&nbsp;</td>
          </tr>
          <tr align="center">
            <td valign="top"  colspan="2"> <input type="submit" name="SAVE" value="Upload" class="button">
            </td>
          </tr>
        </form>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>