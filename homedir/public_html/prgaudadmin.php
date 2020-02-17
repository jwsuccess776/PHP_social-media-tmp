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



include('db_connect.php');

include('session_handler.inc');

include('error.php');

include('imagesizer.php');

include('message.php');



$mode=$_GET['mode'];



include_once __INCLUDE_CLASS_PATH."/class.Audio.php";

$audio = new Audio();



# select advert data

$result = mysqli_query($globalMysqlConn,"SELECT * FROM adverts WHERE adv_userid=$Sess_UserId");

$TOTAL = mysqli_num_rows($result);

# if nothing is returned then show error otherwise get data

if ($TOTAL < 1) {

    $error_message=PRGPICADMIN_ERROR1;

    error_page($error_message,GENERAL_USER_ERROR);

}

if ($mode=='amend') {



    $chkRemoveaud=formGet('chkRemoveaud');

    $chkPrivateaud=formGet('chkPrivateaud');



    if (isset($chkRemoveaud)) {

        foreach ( $chkRemoveaud as $key=>$value) {

            $audio->InitById($value);

            $audio->Delete($Sess_UserId);

        }

    }



    $aAudios = $audio->GetListByMember($Sess_UserId);

    $no_of_audios=isset($aAudios);

    $rownum=0;

    if (isset($_FILES['fuploadaud'])) {

        $count=0;

        foreach($_FILES['fuploadaud']['size'] as $key => $val)

            if ($val != 0) $count++;

        while ($rownum <= $count-1 && $count+$no_of_audios <= 1) {

            if ($_FILES['fuploadaud']['name'][$rownum] != "") {

//                if ($_FILES['fuploadaud']['type'][$rownum] == "audio/wav" || $_FILES['fuploadaud']['type'][$rownum] == "audio/mpeg") {

                    $data = array(

                        "aud_userid"	=> $Sess_UserId,

                        "aud_private"	=> 'N',

                        "filepath"	    => $_FILES['fuploadaud']['tmp_name'][$rownum] ,

                        'filename'		=> $_FILES['fuploadaud']['name'][$rownum]

                    );

                    $result = $audio->InitForSave($data);

                    if ($result === null) error_page(join("<br>",$audio->error),GENERAL_USER_ERROR);



                    $result = $audio->Save();

                    if ($result === null) error_page(join("<br>",$audio->error),GENERAL_USER_ERROR);



                    # check whether immediate authorisation

                    $approved=$option_manager->GetValue('authorisead');

                    $db->query("UPDATE adverts SET adv_approved = '$approved' WHERE adv_userid = '$Sess_UserId'");

//                } else {

//                    error_page(PRGPICADMIN_ERROR7,GENERAL_USER_ERROR);

//                }

            }

            $rownum++;

        }

    }

    // end audio

    $audio->ClearPrivate($Sess_UserId);

    if (isset($chkPrivateaud)) {

        foreach ( $chkPrivateaud as $key=>$value) {

            $audio->InitById($value);

            $audio->SetPrivate($Sess_UserId);

        }

    }

	include("generate_profile.php");

}

$aAudios = $audio->GetListByMember($Sess_UserId);

$resultaud = count($aAudios);

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

    <td><?include_once "media_menu.inc.php"?></td>

  </tr>

  <tr>

    <td><?php echo PRGPICADMIN_TEXT?></td>

  </tr>

  <tr>

    <td> <table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

        <form enctype='multipart/form-data' method='post' action='<?php echo $CONST_LINK_ROOT?>/prgaudadmin.php?mode=amend' name="FrmPicture" onsubmit="showUploadingPopup();">

          <?php

    if ($CONST_AUDIOS == 'Y') {

        print("<tr>

            <td class='tdhead' align='left' colspan='5'>".PRGPICADMIN_AUDIO."</td>

            </tr>

            <tr class='tdtoprow'>

              <td align='left'>&nbsp;</td>

              <td>&nbsp;</td>

              <td>&nbsp;</td>

              <td align='center'><b>".PRGPICADMIN_PRIVATE."</b></td>

              <td align='center'><b>".PRGPICADMIN_REMOVE."</b></td>

            </tr>

        ");

        $indx=0;

        while ($indx < $resultaud) {

            $indx++;

            $sql_array = array_shift($aAudios);

                $aud_private="";

                if($sql_array->aud_private=='Y') $aud_private="checked";

                $aud_info = $sql_array->getInfo('small');



                 print("<tr class='tdodd'>

                        <td align='left'>$indx</td>

                        <td><input type='file' name='fuploadaud[]' size='20' disabled class='inputf'><input type='hidden' name='aud_exists[]' value=$sql_array->aud_id></td>

                        <td align='center'><a href='$CONST_LINK_ROOT$aud_info->Path'><img border='0' src='$CONST_LINK_ROOT$sql_array->title_file' ></a></td>

                        <td align='center'><input type='checkbox' name='chkPrivateaud[]' value=$sql_array->aud_id $aud_private></td>

                        <td align='center'><input type='checkbox' name='chkRemoveaud[]' value=$sql_array->aud_id></td>

                  </tr>");

        }

        while ($resultaud < 1) {

            $resultaud++;

                 print("<tr class='tdeven'>

                        <td align='left'>$resultaud</td>

                        <td><input type='file' name='fuploadaud[]' size='20' class='inputf'></td>

                        <td align='center'>&nbsp;</td>

                        <td align='center'><input type='checkbox' name='chkPrivateaud[]' disabled></td>

                        <td align='center'><input type='checkbox' name='chkRemoveaud[]' disabled></td>

                  </tr>");

        }

        print("

        <tr><td colspan='3' class='tdfoot'>&nbsp;</td>

          <td colspan='2' align='center' class='tdfoot'><input type='submit' name='Submit' value=".BUTTON_UPDATE." class='button'></td>

        </tr>

        ");

    }

?>

         

        </form>

      </table>

   </td>

  </tr>

</table>

<?=$skin->ShowFooter($area)?>