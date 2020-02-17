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

include_once('validation_functions.php');



$mode=$_GET['mode'];

# retrieve the template

include_once __INCLUDE_CLASS_PATH."/class.Picture.php";

$picture = new Picture();



# select advert data

$adv_count =$db->get_var("SELECT count(*) FROM adverts WHERE adv_userid=$Sess_UserId");

# if nothing is returned then show error otherwise get data

if ($adv_count < 1) {

    $error_message=PRGPICADMIN_ERROR1;

    error_page($error_message,GENERAL_USER_ERROR);

}





if(formGet('isCamera')){

    $str = formGet('img');

    $width =sanitizeData($_POST['width'], 'xss_clean') ;    

    $height =sanitizeData($_POST['height'], 'xss_clean') ;   

    $path = createImageFromWebCamera($str, $width, $height, $Sess_UserId);

    $approved=$option_manager->GetValue('authorisepic');

    $picture = new Picture();

    $data = array(

        "pic_userid"    => $Sess_UserId,

        "pic_private"   => 'N',

        "pic_default"   => 'Y',

        "pic_approved"   => $approved,

        "filepath"      => $path,

                );

    $result = $picture->InitForSave($data);

    if ($result === null) {

        error_page(join("<br>",$picture->error),GENERAL_USER_ERROR);

    }

    $picture->Save();

//    unlink($path);

}



if ($mode=='amend') {



    $radDefault=formGet('radDefault');

    $chkRemove=formGet('chkRemove');

    $chkPrivate=formGet('chkPrivate');



    $picture->ClearPrivate($Sess_UserId);

    if (isset($chkPrivate)) {

        foreach ( $chkPrivate as $key=>$value) {

            $picture->InitById($value);

            $picture->SetPrivate($Sess_UserId);

        }

    }



    if ($radDefault) {

        $picture->InitById($radDefault);

        $result = $picture->SetDefault($Sess_UserId);

        if ($result === null) error_page(join("<br>",$picture->error),GENERAL_USER_ERROR);

    }



    if (isset($chkRemove)) {

        foreach ( $chkRemove as $key=>$value) {

            $picture->InitById($value);

            $result = $picture->Delete($Sess_UserId);

            if ($result === null) error_page(join("<br>",$picture->error),GENERAL_USER_ERROR);

        }

    }

    $rownum=0;

    include("generate_profile.php");

}

$aPictures = $picture->GetListByMember($Sess_UserId);

$result = count($aPictures);

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

    <td ><?include_once "media_menu.inc.php"?></td>

  </tr>

  <tr>

    <td><?php echo PRGPICADMIN_TEXT?>



    </td>

  </tr>

  <tr>

    <td> <table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

        <form enctype='multipart/form-data' method='post' action='<?php echo $CONST_LINK_ROOT?>/prgpicadmin.php?mode=amend' name="FrmPicture">

          <tr>

            <td  colspan="6" align="left" valign="top" class="tdhead"><strong><?php echo PRGPICADMIN_PHOTOS?></strong></td>

          </tr>

          <tr class="tdtoprow" >

            <td align="left"><b><?php echo PIC_DEFAULT ?></b></td>

            <td align="left">&nbsp;</td>

            <td>&nbsp;</td>

            <td>&nbsp;</td>

            <td  align="center"><b><?php echo PRGPICADMIN_PRIVATE?></b></td>

            <td align="center"><b><?php echo PRGPICADMIN_REMOVE?></b></td>

          </tr>

          <?php

$indx=0;

foreach ($aPictures as $sql_array) {

    $indx++;

    $pic_private = ($sql_array->pic_private=='Y') ? "checked" : "";

    $pic_default = ($sql_array->pic_default=='Y') ? "checked" : "";

	if ($sql_array->pic_default!='Y') {

		$private_allowed = "<input type='checkbox' name='chkPrivate[]' value=$sql_array->pic_id $pic_private>";

	} else $private_allowed = "";

    if ($sql_array->pic_approved==1) {

		$defstatus="<input type=radio name=radDefault $pic_default value=\"$sql_array->pic_id\">";

	} elseif ($sql_array->pic_approved==2) {

		$defstatus="<strong>".STATUS_REJECTED."</strong>";

	} else {

		$defstatus="<strong>".STATUS_PENDING."</strong>";

	}

	

    $pic_info = $sql_array->getInfo('small');

    print(" <tr class='tdodd'>

            <td align='left'>$defstatus</td>

            <td align='left'>$indx</td>

            <td><input type='file' name='fupload[]' size='20' disabled class='inputf'><input type='hidden' name='pic_exists[]' value=$sql_array->pic_id></td>

            <td align='center'><img border='0' src='{$CONST_LINK_ROOT}$pic_info->Path' width='70'></td>

            <td align='center'>$private_allowed</td>

            <td align='center'><input type='checkbox' name='chkRemove[]' value=$sql_array->pic_id></td>

      </tr>");

}





?>





          <tr>

            <td align='left'  class="tdfoot">&nbsp;</td>

            <td align='left'  class="tdfoot">&nbsp;</td>

            <td class="tdfoot"><?php if ($indx < $CONST_IMAGE_COUNT) { ?>

                <input type='button' name='Cancel' value='<?=BUTTON_ADD_PICTURE?>' class='button' onClick="window.location = 'addpicture.php?'">

				<input type='button' name='Cancel' value='<?php echo BUTTON_WEB_SNAP ?>' class='button' onClick="window.location = 'snapshot.php'">

<?php if ( strtoupper ( $CONST_AVATARS_GALLERY ) == 'Y' ) { ?>

                <input type="button" value="Add Avatar" class="button" onClick="window.open('addavatar.php', 'avatars', 'width=400, height=400, scrollbars=yes')">

<?php } ?>

            <?php } ?>&nbsp;</td>

            <td class="tdfoot">&nbsp;</td>

            <td colspan="2" align='center'  class="tdfoot"><input type="submit" name="Submit" value="<?php echo BUTTON_UPDATE ?>" class="button"></td>

          </tr>



        </form>

      </table>

    </td>

  </tr>

</table>

<?=$skin->ShowFooter($area)?>