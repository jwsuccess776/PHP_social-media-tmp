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

# Name:         aff_banlist.php

#

# Description:  authorise affiliate

#

# Version:      7.2

#

######################################################################

include('../db_connect.php');

include('../session_handler.inc');

include('../admin/permission.php');



# retrieve the template

$area = 'member';



$mode=$_POST['mode'];

if (isset($_POST['submit'])) {

	switch ($_POST['submit']) {

		case 'Edit Banner':

			header("Location: $CONST_LINK_ROOT/affiliates/aff_banners.php?id=$mode");

			break;

		case 'Add Banner':

			header("Location: $CONST_LINK_ROOT/affiliates/aff_banners.php");

			break;

		case 'Delete Banner':

			$query="SELECT * FROM banners WHERE ban_id = $mode";

			$retval=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

			if (mysqli_num_rows($retval) > 0) {

				$sql_picture=mysqli_fetch_object($retval);

				@unlink("banners/$sql_picture->ban_picture");

			}

			$result=mysqli_query($globalMysqlConn,"DELETE FROM banners WHERE ban_id=$mode") or die(mysqli_error());

			break;

	}

}



$result=mysqli_query($globalMysqlConn,"SELECT * FROM banners") or die(mysqli_error());



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

    <td class="pageheader"><?php echo AFF_BANNERS_SECTION_NAME ?></td>

  </tr>

  <tr>

    <td><? include("../admin/admin_menu.inc.php");?></td>

  </tr>

  <tr>

    <td align="center">

	    <form action="<?php echo $CONST_LINK_ROOT?>/affiliates/aff_banlist.php" method="post" name="frmBanners">

		<input type="hidden" name="mode" value="">

		<?php

			while($sql_banners=mysqli_fetch_object($result)) {

				print("<div class='tdodd'><a href='$CONST_LINK_ROOT/' target='_blank'><img border=0 src='$CONST_LINK_ROOT/affiliates/banners/$sql_banners->ban_picture' alt='$sql_banners->ban_text'><br>$sql_banners->ban_text</a><br><br>");

				print("<input type='submit' value='".EDIT_BANNER."' name='submit' class='button' onClick='document.frmBanners.mode.value=$sql_banners->ban_id'>&nbsp;<input type='submit' value='".DELETE_BANNER."' name='submit' class='button' onClick='document.frmBanners.mode.value=$sql_banners->ban_id'></div><br>");

			}

		?>

		<p>

          <input type="submit" name="submit" value="<?=ADD_BANNER?>" class="button"> </form>

        </p>

     	 </td>

  </tr>



</table>

<?=$skin->ShowFooter($area)?>