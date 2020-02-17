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

# Name: 		prgmyinfo.php

#

# Description:  Processes the requests from member control panel (myinfo.php)

#

# # Version:      8.0

#

# Update: 		12/02/2003 Fixes missing removal of hotlist references & profile

#

######################################################################



include('db_connect.php');

include('session_handler.inc');

include('error.php');

include('deletes.php');

include('message.php');



$mode=$_GET['mode'];

# retrieve the template

$area = 'member';



# Find what is to be done

if ($mode == 'deletematch') {



	$query="SELECT sea_userid FROM search WHERE sea_userid = '$Sess_UserId'";

	if (! $result=mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);}

	if (mysqli_num_rows($result) <> 1) {

		$result_title = PRGMYINFO_MATCH_DEL;

		$result_message = PRGMYINFO_MATCH;

	} else {

		delete_match($Sess_UserId);

		$result_title = PRGMYINFO_MATCH_DEL;

		$result_message = PRGMYINFO_MATCH_MAIL;

	}

}

# delete members advertisement

###############################

elseif ($mode == 'deletead') {

	$query="SELECT adv_userid, adv_picture FROM adverts WHERE adv_userid = '$Sess_UserId'";

	if (! $result=mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);}

	if (mysqli_num_rows($result) <> 1) {

		$result_title = PRGMYINFO_PROFILE;

		$result_message = PRGMYINFO_ADVERT;

	} else {

		delete_advert($Sess_UserId);

		$result_title = PRGMYINFO_PROFILE;

		$result_message = PRGMYINFO_YOUR ;

	}

# delete member

###############################

} elseif ($mode== 'deleteme') {

	restrict_demo();

	delete_advert($Sess_UserId);

	delete_match($Sess_UserId);

	delete_me($Sess_UserId);

	session_destroy();

	header("Location: $CONST_LINK_ROOT");

	exit;

}

// mysqli_close($link);

?>

<?=$skin->ShowHeader($area)?>



  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>

      <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>



    <td class="pageheader"><?php echo RESPONSE_SECTION_NAME ?></td>

    </tr>

    <tr>

    <td><b><?php print("$result_title"); ?></b><p><?php print("$result_message"); ?></p>

      <p><?php echo PRGMYINFO_TO?></p>

      <a href='<?php echo $CONST_LINK_ROOT?>/myinfo.php'><?php echo GENERAL_CONTINUE?></a></td>

    </tr>

  </table>

<?=$skin->ShowFooter($area)?>