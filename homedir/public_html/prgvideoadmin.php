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

# Name:         prgvideodmin.php

#

# Description:  Adds and removes additional videos for members

#

# Version:      8.0

#

######################################################################



include('db_connect.php');

include('session_handler.inc');

include('error.php');

include('imagesizer.php');

include('message.php');



include_once __INCLUDE_CLASS_PATH."/class.Video.php";

$video = new Video();



$mode=$_GET['mode'];



$result = mysqli_query($globalMysqlConn, "SELECT * FROM adverts WHERE adv_userid=$Sess_UserId");

$TOTAL = mysqli_num_rows($result);

# if nothing is returned then show error otherwise get data

if ($TOTAL < 1) {

    $error_message=PRGPICADMIN_ERROR1;

    error_page($error_message,GENERAL_USER_ERROR);

}

if ($mode=='amend') {

    $chkRemovevid=formGet('chkRemovevid');

    $chkPrivatevid=formGet('chkPrivatevid');



    if (isset($chkRemovevid)) {

        foreach ( $chkRemovevid as $value) {

            $video->InitById($value);

            $video->Delete($Sess_UserId);

        }

    }



    $video->ClearPrivate($Sess_UserId);

    if (isset($chkPrivatevid)) {

        foreach ( $chkPrivatevid as $key=>$value) {

            $video->InitById($value);

            $video->SetPrivate($Sess_UserId);

        }

    }

    include("generate_profile.php");

}

$aVideos = $video->GetListByMember($Sess_UserId);

$area = 'member';

?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

  <tr>

    <td align="right"><?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

    </td>

  </tr>

  <tr>

    <td><?include_once "media_menu.inc.php"?></td>

  </tr>

  <tr>

    <td><?php echo PRGPICADMIN_TEXT?></td>

  </tr>

  <tr>

    <td><table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

        <form enctype='multipart/form-data' method='post' action='<?php echo $CONST_LINK_ROOT?>/prgvideoadmin.php?mode=amend' name="FrmPicture">

          <tr  class="tdtoprow" >

            <td width=20%>&nbsp;</td>

            <td width=50%>&nbsp;</td>

            <td width=10%>&nbsp;</td>

            <td align='center'><b>

              <?=GENERAL_PRIVATE?>

              </b></td>

            <td align='center'><b>

              <?=PRGPICADMIN_REMOVE?>

              </b></td>

            <?

        foreach ($aVideos  as $video) {

            $vid_private="";

            if($video->vid_private=='Y') $vid_private="checked";

            $vid_info = $video->getInfo();

            $frame_info = $video->getFrameInfo('small');

            $ratedItem = $video->rating;

?>

          </tr>

          <tr class='tdodd'>

            <td><a href='<?=$CONST_LINK_ROOT?>/show_video.php?vid_id=<?=$video->vid_id?>'><img border='0' src='<?=$CONST_LINK_ROOT.$frame_info->Path?>' width='<?=$frame_info->w?>'></a> </td>

            <td><?=$video->vid_title?>

              Tags:

              <?foreach ($video->getTags('array') as $tag) {?>

              <a href="<?=$CONST_LINK_ROOT?>/video_list.php?tag_id=<?=$tag->id?>">

              <?=$tag->tag?>

              </a>

              <?}?>

            </td>

            <td><a href="<?=$CONST_LINK_ROOT?>/addvideo.php?vid_id=<?=$video->vid_id?>">[

              <?=BUTTON_EDIT?>

              ]</a> </td>

            <td align='center'><input type='checkbox' name='chkPrivatevid[]' <?=$vid_private?> value="<?=$video->vid_id?>"></td>

            <td align='center'><input type='checkbox' name='chkRemovevid[]' value="<?=$video->vid_id?>"></td>

          </tr>

          <? } ?>

          <tr>

            <td colspan='2' class='tdfoot'><input type='button' name='Cancel' value='<?=BUTTON_ADD_VIDEO?>' class='button' onClick="window.location = 'addvideo.php?'"></td>

            <td colspan='2' align='center' class='tdfoot'><input type='submit' name='Submit' value="<?=BUTTON_UPDATE?>" class='button'></td>

          </tr>

        </form>

      </table></td>

  </tr>

</table>

<?=$skin->ShowFooter($area)?>

