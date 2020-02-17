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

# Name:                 adm_picturegallery.php

#

# Description:

#

# Version:                7.3

#

######################################################################

//ADM_STANDART_PICTURE_GALLERY_SECTION_NAME

include('../db_connect.php');

include('../session_handler.inc');

include('../error.php');

include('../functions.php');

include('../imagesizer.php');

include('../message.php');

if ($Sess_UserType != "A") {

    exit;

}



if (isset($_POST['SUBMIT'])) {

//    echo '<pre>';

//    print_r($_POST);

//    print_r($_FILES);



    $max_size=$option_manager->GetValue('maxpicsize');



    if ($_FILES['picture']['size'] != 0) {

                if ($_FILES['picture']['size'] > $max_size) {

                $max_size=$max_size/1000;

                        error_page(sprintf(PRGADVERTISE_TEXT22,$max_size),GENERAL_USER_ERROR);

                }

                if ($_FILES['picture']['type'] != "image/pjpeg" && $_FILES['picture']['type'] != "image/jpeg" && $_FILES['picture']['type'] != "image/gif") {

                        error_page(PRGADVERTISE_TEXT23.$_FILES['picture']['type'],GENERAL_USER_ERROR);

                }



                $query_avatar = "INSERT INTO avatars

                                SET

                                    pic_id = '0'

                                ";

                $result_avatar = mysqli_query($globalMysqlConn, $query_avatar);

                $last_insert_id = mysqli_insert_id($globalMysqlConn);

//            $oAvatar = mysql_fetch_array($result_lastid);

            if($_FILES['picture']['type'] == "image/pjpeg" || $_FILES['picture']['type'] == "image/jpeg")

				$extension=".jpg";

			else

				$extension=".gif";

				

            $filename=$last_insert_id.$extension;

            $targetfile="$CONST_INCLUDE_ROOT/members/avatar_".$filename;

            if (copy($_FILES['picture']['tmp_name'],$targetfile)) {

                $query_picture = "INSERT INTO pictures

                    SET

                        pic_picture  = '/members/avatar_$last_insert_id$extension'";

						

                $result_picture = mysqli_query($globalMysqlConn, $query_picture);

                $last_pic_id = mysqli_insert_id($globalMysqlConn);

                $query_avatar = "UPDATE avatars

                                    SET

                                        pic_id = '$last_pic_id'

                                 WHERE

                                        avatar_id = '$last_insert_id'

                                ";

                $result_avatar = mysqli_query($globalMysqlConn,$query_avatar);

            }



            if ($CONST_THUMBS == 'Y') {

                                $thumbfile=str_replace("members/", "members/", $targetfile);

                                $new_w=60;

                                $new_h=66;

                                createthumb($targetfile, $thumbfile,$new_w,$new_h);

                                $thumbfile=str_replace("members/", "members/large-", $targetfile);

                                $new_w=120;

                                $new_h=160;

                                createthumb($targetfile, $thumbfile,$new_w,$new_h);

            }

    }

} elseif(isset($_POST['DELETE'])) {

    if ( isset($_POST['avatar']) ) { // the message variable is a list of msg_ids to delete from the email



            foreach ($_POST['avatar'] as $key => $value) {

                    $arr_value = explode("/",$value);

                    $query_av     = "SELECT pic_id FROM avatars WHERE avatar_id = $arr_value[0]";

                    $result_av    = mysqli_query($globalMysqlConn, $query_av) or die(mysqli_error());

                    $oPic         = mysqli_fetch_object($result_av);

                    $query        = "DELETE FROM pictures WHERE pic_id = '".$oPic->pic_id."' AND pic_userid = 0";

                    $result       = mysqli_query($globalMysqlConn, $query) or die(mysqli_error());

                    $query        = "DELETE FROM avatars WHERE avatar_id = $arr_value[0]";

                    $result       = mysqli_query($globalMysqlConn, $query) or die(mysqli_error());

            }

        }

}



# retrieve the template

$area = 'member';



$query_avat = "SELECT * FROM avatars AS a

                    INNER JOIN pictures AS p

                        ON (a.pic_id = p.pic_id)

                    WHERE

                        a.avatar_id > 0

              ";

$sql_result = mysqli_query($globalMysqlConn,$query_avat);

?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

        <tr>

      <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>

      <td class="pageheader"><?php echo ADM_STANDART_PICTURE_GALLERY_SECTION_NAME ?></td>

    </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

    <form method="POST" action="<?php echo $CONST_LINK_ROOT?>/admin/adm_picturegallery.php"  enctype="multipart/form-data">

    <tr>

      <td align="center">

        Upload new picture: <input type="file" name="picture"  class="input1">&nbsp;&nbsp;<input type="submit" name="SUBMIT" value="Submit" class="button">

      </td>

    </tr>

    </form>

    <tr>

      <td>



	<table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

    <tr class="tdtoprow" align="left">

      <td>

        <?=ADM_PICTURE?>

      </td>

      <td >

        <?=BUTTON_REMOVE?>

      </td>

     </tr>

     <form method="POST" action="<?php echo $CONST_LINK_ROOT?>/admin/adm_picturegallery.php">

    <?php

      while($avatar = mysqli_fetch_object($sql_result)) {

    ?>



        <tr align=left class="tdodd" >

          <td>

          <?

          //Scale Picture

          $standart_width = '300';

          $standart_height = '200';



          $image = @getimagesize($CONST_LINK_ROOT.$avatar->pic_picture);

          $curr_width = $image['0'];

          $curr_height = $image['1'];



          if ($curr_width > $standart_width) {

              $koef = $curr_width / $standart_width;

              $curr_width = $curr_width / $koef;



              $curr_height =  $curr_height / $koef;

          }



          if ($curr_height > $standart_height) {

              $koef = $curr_height / $standart_height;

              $curr_height = $curr_height / $koef;



              $curr_width =  $curr_width / $koef;

          }

          ?>



            <img src="<?=$CONST_LINK_ROOT.$avatar->pic_picture?>" border="0" width="<?=$curr_width?>" height="<?=$curr_height?>">

          </td>

          <td>

            <input type="checkbox" name="avatar[]" id="avatar" value="<?=$avatar->avatar_id?>">

          </td>

        </tr>

    <?php

      }

    ?>

	<tr>

      <td colspan="2" align="right" class="tdfoot"><input type="submit" name="DELETE" value="Delete" class="button"></td>

    </tr>

    </form>

	<tr>

      <td colspan="2" align="center" class="tdfoot">&nbsp; </td>

    </tr>



  </table>



	  </td>

    </tr>

  </table>

<?=$skin->ShowFooter($area)?>

