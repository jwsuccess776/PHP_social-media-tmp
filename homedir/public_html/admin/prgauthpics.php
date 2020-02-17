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

# Name:                 prgauthads.php

#

# Description:  Administrator advert authorisation processing

#

# Version:                7.2

#

######################################################################

include('../db_connect.php');

include('../session_handler.inc');

include('../error.php');

include('../functions.php');

include('../message.php');

include_once __INCLUDE_CLASS_PATH."/class.Picture.php";

include('permission.php');



$picture = new Picture();



$mode=$_GET['mode'];

# process an authorisation

if ($mode=='next') {



    $hiddenuserid=$_POST['hiddenuserid'];

 	

	$query="SELECT * FROM pictures WHERE pic_userid=$hiddenuserid AND pic_approved=0";

	$result=mysqli_query($globalMysqlConn,$query);

	

	while ($pic_array=mysqli_fetch_object($result)) {

		

		$picstatus=$_POST['rdo'.$pic_array->pic_id];

		

		switch($picstatus){

			case '3':

   				$picture->InitById($pic_array->pic_id);

				$pic_result = $picture->Delete($hiddenuserid);

				if ($pic_result === null) error_page(join("<br>",$picture->error),GENERAL_USER_ERROR);

 				break;

 			default:

				$pic_result=mysqli_query($globalMysqlConn,"UPDATE pictures SET pic_approved=$picstatus WHERE pic_id=$pic_array->pic_id");

				break;

		}



	}

	   

	include("../generate_profile.php");

}



$result = mysqli_query($globalMysqlConn,"SELECT DISTINCT(pic_userid), mem_username

                       FROM pictures

					   LEFT JOIN members ON (mem_userid=pic_userid)

                           WHERE pic_approved=0 LIMIT 1");

$TOTAL = mysqli_num_rows($result);

# if nothing is returned then show error otherwise get data

if ($TOTAL < 1) {

        $error_message=PRGAUTHADS_TEXT;

        display_page($error_message,PRGAUTHADS_TEXT1);

} else {

        $sql_array = mysqli_fetch_object($result);

}

$end_blocking = mysqli_query($globalMysqlConn,"UNLOCK TABLES"); //Unlock Table

# place advert data into variables for display

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



    <td class="pageheader"><?=DB_OPTION_AUTHORISEPIC_LABEL?></td>

    </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

    <tr><td>

        <table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

        <form enctype='multipart/form-data' method='post' action='<?php echo $CONST_LINK_ROOT?>/admin/prgauthpics.php?mode=next' name="FrmAuthorise">

            <input type='hidden' name="hiddenuserid" value="<?php print("$sql_array->pic_userid"); ?>">

          <tr class="tdodd">

            <td align="left"><span style="margin-left: 30px; "><strong><?=$sql_array->mem_username?></strong></span></td>

            <td><strong><?=VALIDATE_APPROVE?></strong></td>

			<td><strong><?=VALIDATE_REJECT?></strong></td>

			<td><strong><?=BUTTON_REMOVE?></strong></td>

		  </tr>

              <?php

					$aPicture=$picture->GetListByMember($sql_array->pic_userid, 'approve');

					foreach ($aPicture as $pic_array) {

						$small = $pic_array->GetInfo('small');

						$full = $pic_array->GetInfo('');

						print("<tr><td align='left' class='tdfoot'><a href='$CONST_LINK_ROOT$full->Path' target=_blank><img border='0' src='$CONST_LINK_ROOT$small->Path' width='$small->w' style='padding-left:30px;'><a></td>");

						print("<td  align='left' class='tdfoot'><input type='radio' name='rdo$pic_array->pic_id' value='1' checked></td>");

						print("<td  align='left' class='tdfoot'><input type='radio' name='rdo$pic_array->pic_id' value='2'></td>");

						print("<td  align='left' class='tdfoot'><input type='radio' name='rdo$pic_array->pic_id' value='3'></td></tr>");

					}

               ?>

          <tr align="center">

            <td colspan="4" class="tdfoot"> <input type="submit" name="Submit" value="<?php echo BUTTON_SUBMIT ?>" class="button">

            </td>

          </tr>

        </form>

      </table></td>

    </tr>

  </table>

<?=$skin->ShowFooter($area)?>