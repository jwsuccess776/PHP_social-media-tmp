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

# Name:         prgvideodmin.php

#

# Description:  Adds and removes additional videos for members

#

# Version:      8.0

#

######################################################################



include('../db_connect.php');

include($CONST_INCLUDE_ROOT.'session_handler.inc');

include($CONST_INCLUDE_ROOT.'error.php');

include($CONST_INCLUDE_ROOT.'message.php');

include('permission.php');





set_time_limit(0);



include_once __INCLUDE_CLASS_PATH."/class.Video.php";

$video = new Video();

include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";

$adv = new Adverts();



$mode=$_GET['mode'];



if ($mode=='amend') {



    $chkRemovevid=formGet('chkRemovevid');
    die($chkRemovevid);

    if (count($chkRemovevid)) {

        foreach ( $chkRemovevid as $value) {

            $video->InitById($value);

            $video->Reject();

            $adv->initById($video->vid_userid);

            $data['ReceiverName'] = $adv->adv_username;

            $data['CompanyName'] = $CONST_COMPANY;

            $data['Url'] = $CONST_URL;

            $data['SupportEmail'] = $CONST_SUPPMAIL;

            list($type,$message) = getTemplateByName("Reject_Video",$data,getDefaultLanguage($video->vid_userid));

            send_mail ($adv->mem_email, "$CONST_MAIL", "$CONST_COMPANY ".PRGAUTHVIDEO_REJ, $message,$type,"ON");

        }

    }

//    include("../generate_profile.php");

    $chkKillid=formGet('chkKillid');

    if (count($chkKillid)) {

        foreach ( $chkKillid as $value) {

            $video->InitById($value);

            $video->Delete($video->vid_userid);

        }

    }

    redirect("$CONST_LINK_ROOT/admin/prgauthvideo.php");

} elseif ($mode == 'approve') {

    $video->InitById(formGet('v_id'));

    $video->approve();

    redirect("$CONST_LINK_ROOT/admin/prgauthvideo.php");

}

$aVideos = $video->getListByStatus('new');

if (!count($aVideos)) {

        display_page(PRGAUTHVIDEO_TEXT,PRGAUTHVIDEO_TEXT1);

}



$area = 'member';

?>

<script language=javascript>



function start_conversion(id){

    document.getElementById('progress').style.display = '';

    document.getElementById('table').style.display = 'none';

    document.getElementById('log').contentWindow.location = '<?=$CONST_ADMIN_LINK_ROOT?>/convert_video.php?mode=amend&convert=true&vid_id='+id;
    console.log('<?=$CONST_ADMIN_LINK_ROOT?>/convert_video.php?mode=amend&convert=true&vid_id='+id);

//    document.getElementById('log').contentWindow.onload = function() {alert("ok"); parent.window.location='<?=$CONST_ADMIN_LINK_ROOT?>/prgauthvideo.php';};

}



</script>



<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

  <tr>

    <td align="right">

        <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

    </td>

  </tr>

  <tr>

    <td class="pageheader"><?php echo TITLE_VIDEO_APPROVE?></td>

  </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

  <tr>

    <td align=center> 

        <table width="100%" id=table border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

            <form enctype='multipart/form-data' method='post' action='<?php echo $CONST_LINK_ROOT?>/admin/prgauthvideo.php?mode=amend' name="FrmPicture">

              <td width=20%>&nbsp;</td>

              <td width=20%>&nbsp;</td>

              <td width=30%>&nbsp;</td>

              <td width=10%>&nbsp;</td>

              <td align='center'><b><?=AFF_AUTHORISE_REJECT?></b></td>

              <td align='center'><b>Delete</b></td>

<?

        foreach ($aVideos  as $video) {

            $vid_private="";

            $vid_info = $video->getInfo();

            $frame_info = $video->getFrameInfo('small');

?>

            <tr>

                <td>

                    <?if ($video->vid_video == 'cvid') {?>

                    <a href='<?=$CONST_LINK_ROOT?>/show_video.php?vid_id=<?=$video->vid_id?>'><img border='0' src='<?=$CONST_LINK_ROOT.$frame_info->Path?>' width='<?=$frame_info->w?>'></a>                    

                    <?} else {?>

                    <a href='<?=$CONST_LINK_ROOT.$vid_info->Path?>'><img border='0' src='<?=$CONST_LINK_ROOT.$frame_info->Path?>' width='<?=$frame_info->w?>'></a>                    

                    <?}?>

                </td>

                <td>

                    <?=$video->vid_title?>

                </td>

                <td>

                    <?=$video->vid_description?>

                </td>

                <td>

                    <?if ($video->vid_video == 'cvid') {?>

                    <input class=button type=button onClick="location='<?=$CONST_ADMIN_LINK_ROOT?>/prgauthvideo.php?mode=approve&v_id=<?=$video->vid_id?>)'" value="<?=GENERAL_APPROVE?>">

                    <?} else {?>

                    <input class=button type=button onClick="start_conversion(<?=$video->vid_id?>)" value="<?=BUTTON_CONVERT_VIDEO?>">

                    <?}?>

                </td>

                <td align='center'><input type='checkbox' name='chkRemovevid[]' value="<?=$video->vid_id?>"></td>

                <td align='center'><input type='checkbox' name='chkKillid[]' value="<?=$video->vid_id?>"></td>

            </tr>

<? } ?>

            <tr>

                <td colspan='6' align='center' class='tdfoot'><input type='submit' name='Submit' value="<?=BUTTON_UPDATE?>" class='button'></td>

            </tr>



        </form>

      </table>

     <div id=progress style="display:none">

        <h3>Convertion is in progress. Wait please.</h3>

        <iframe id=log src="" width=100% height=400px></iframe>

     </div>                      <br><br>

     <div id=back style="display:none">

        <input type='button' name='Submit' value="<?=BUTTON_BACK?>" class='button' onClick="parent.window.location='<?=$CONST_ADMIN_LINK_ROOT?>/prgauthvideo.php'">  

     </div>

    </td>

  </tr>

</table>

<?=$skin->ShowFooter($area)?>