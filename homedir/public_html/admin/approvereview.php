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

# Name: 		approvereview.php

#

# Description:  Displays the profile input page (after advert)

#

# # Version:      8.0

#

######################################################################



include('../db_connect.php');

include('../session_handler.inc');

include('../error.php');

include('permission.php');

include_once('../validation_functions.php');



$type=sanitizeData($_REQUEST['type'], 'xss_clean');

$id=sanitizeData($_REQUEST['id'], 'xss_clean');



# retrieve the template

$area = 'member';



# retrieve the first un-approved event

$query = "SELECT * FROM reviews WHERE review_approved='0'";

$retval=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

if (mysqli_num_rows($retval)==0)

{

 header("Location: $CONST_LINK_ROOT/admin/events.php");

exit();

}

$row = mysqli_fetch_object($retval);

$id=$row->review_id;

$recId = $row->review_recid;

$txtReview=$row->review_text;

$type = $row->review_type;

?>

<?=$skin->ShowHeader($area)?>

   <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>

      <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>

    <td class="pageheader"><?php echo APPROVEREVIEW_SECTION_NAME ?></td>

    </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

    <tr><td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

        <form enctype='multipart/form-data' method='post' action='<?php echo $CONST_LINK_ROOT ?>/admin/prgaddreview.php' name="FrmEvent" onSubmit="">

          <tr >

            <td align="left" class="tdhead"><?php echo APPROVEREVIEW_TEXT ?>

              <?=$type?>

            </td>

          </tr>

          <tr class="tdodd" >

            <td align="left"> <input name="txtApprove" type="radio" value="1" checked>

              <?php echo GENERAL_APPROVE ?> <input type="radio" name="txtApprove" value="0">

              <?php echo GENERAL_DELETE ?> <input type="hidden" name="recId" value="<?=$recId?>">

              <input type="hidden" name="type" value="<?=$type?>"> 

			  <input type="hidden" name="id" value="<?=$id?>"></td>

          </tr>

          <tr class="tdeven" >

            <td align="left"> <textarea  class="inputl" name="txtReview" cols="45" rows="8" id="textarea"><?php echo $txtReview?></textarea>

            </td>

          </tr>

          <tr >

            <td align="center" class="tdfoot"> <input type="submit" name="Submit" value="<?php echo BUTTON_SUBMIT ?>" class="button"></td>

          </tr>

        </form>

      </table></td>

    </tr>

  </table>

<?=$skin->ShowFooter($area)?>

