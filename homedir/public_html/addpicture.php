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

# retrieve the template
$area = 'member';

$root_path=$CONST_INCLUDE_ROOT."/";
$max_size=$option_manager->GetValue('maxpicsize');
$max_vidsize=$option_manager->GetValue('maxvidsize');
$max_audsize=$option_manager->GetValue('maxaudsize');

# select advert data
//$result = mysql_query("SELECT * FROM adverts WHERE adv_userid=$Sess_UserId",$link);
//$TOTAL = mysql_num_rows($result);
$result = $db->get_results("SELECT * FROM adverts WHERE adv_userid=$Sess_UserId");
# if nothing is returned then show error otherwise get data
//if ($TOTAL < 1) {
if(is_null($result)) {
    $error_message=PRGPICADMIN_ERROR1;
    error_page($error_message,GENERAL_USER_ERROR);
}

if (isset($_FILES['fupload'])) {
    # check whether immediate authorisation
    if ($_FILES['fupload']['name'] != "") {
        if ($_FILES['fupload']['size'] > $max_size) {
            $max_size=round($max_size/1024);
            error_page(sprintf(PRGPICADMIN_ERROR2,$max_size),GENERAL_USER_ERROR);
        }
        $tempusername="temp_".$Sess_UserId."_".time();
        $filename="$tempusername.jpg";
        $targetfile=$root_path."members/"."$filename";
        copy($_FILES['fupload']['tmp_name'], "$targetfile");
        //header("Location: crop.php?thePic=members/".$filename);
         header("Location: process.php?file=members/".$filename);
/*

        if ($_FILES['fupload']['type'] == "image/pjpeg" ||
            $_FILES['fupload']['type'] == "image/jpeg") {
            if ( $_FILES['fupload']['type'] == "image/pjpeg" ) { $extension=".jpg"; }
            if ( $_FILES['fupload']['type'] == "image/jpeg" ) { $extension=".jpg"; }
            $tempusername="temp_".$Sess_UserId."_".time();
            $filename="$tempusername"."$extension";
            $targetfile=$root_path."members/"."$filename";
            copy($_FILES['fupload']['tmp_name'], "$targetfile");
            header("Location: crop.php?thePic=members/".$filename);
        } elseif ($_FILES['fupload']['type'] == "image/gif" &&
                  function_exists('imagecreatefromgif')) {
            $tempusername="temp_".$Sess_UserId."_".time();
            $filename="$tempusername"."$extension";
            $targetfile=$root_path."members/"."$filename";
            imagejpeg(imagecreatefromgif($_FILES['fupload']['tmp_name']), $targetfile);
            header("Location: crop.php?thePic=members/".$filename);
        } else {
            error_page(PRGPICADMIN_ERROR3,GENERAL_USER_ERROR);
        }
*/
    }
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
    <td class="pageheader"><?php echo PICADMIN_SECTION_NAME ?></td>
  </tr>
  <tr>
    <td><?php echo PRGPICADMIN_TEXT?></td>
  </tr>
  <tr>
    <td> <table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">
        <form enctype='multipart/form-data' method='post' action='<?php echo $CONST_LINK_ROOT?>/addpicture.php' name="FrmPicture">
          <tr>
            <td align="left" valign="top" class="tdhead"><strong><?php echo PRGPICADMIN_PHOTOS?></strong></td>
          </tr>
          <tr class='tdodd'>
            <td><input type='file' name='fupload' class='inputf' size='20'></td>
            </tr>
          <tr>
            <td  colspan="5" align="left" class="tdfoot">&nbsp;</td>
          </tr>
          <tr align="center">
            <td valign="top"  colspan="5"> <input type="submit" name="Submit" value="Upload" class="button">
            </td>
          </tr>
        </form>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>