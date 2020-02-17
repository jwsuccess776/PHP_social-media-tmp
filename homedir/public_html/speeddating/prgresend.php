<?php

/****************************************************

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

# Name: 		prgresend.php

#

# Description:  Member tool to resend login details if forgotten

#

# # Version:      8.0

#

######################################################################

include('../db_connect.php');
include_once('../validation_functions.php'); 
include('error.php');



$txtEmail=sanitizeData(trim($_POST['txtEmail']), 'xss_clean');   

# retrieve the template

$area = 'speeddating';

# ensure a mail address has been entered

if (empty($txtEmail) || strlen($txtEmail) < 2) {

	$error_message=PRGRESEND_ERROR1;

	error_page($error_message,GENERAL_USER_ERROR);

}

# validate the email exists

$query="SELECT mem_username, mem_password, mem_email, mem_userid, mem_confirm FROM members WHERE mem_email = '$txtEmail'";

$result=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

$retval=mysqli_num_rows($result);

if ($retval < 1) {

	error_page(PRGRESEND_ERROR2,GENERAL_USER_ERROR);

} else {

		$sql_array = mysqli_fetch_object($result);

		if($sql_array->mem_confirm)

			$message=sprintf(PRGRESEND_TEXT2,$sql_array->mem_username,$sql_array->mem_password);

		else

		{

			$confirm_url = $CONST_LINK_ROOT."/speeddating/confirm.php?id=".md5($sql_array->mem_userid);

			$message = sprintf(PRGRESEND_TEXT4,$sql_array->mem_username,$sql_array->mem_password, $confirm_url);

		}



		send_mail ("$txtEmail", "$CONST_MAIL",PRGRESEND_TEXT3, "$message","text","ON");

}

// mysqli_close($link);

?>

<?=$skin->ShowHeader($area)?>





  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>



    <td class="pageheader"><?php echo RESPONSE_SECTION_NAME?></td>

    </tr>

    <tr>

      <td><table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

        <tr>

          <td valign='top' align='left'></td>

        </tr>

        <tr>

          <td align='left' valign='top' class="tdhead">&nbsp;</td>

        </tr>

        <tr>

          <td valign='top' align='left'></td>

        </tr>

        <tr>

          <td align='left' valign='top' class="tdodd"><?php echo PRGRESEND_TEXT1?></td>

        </tr>

        <tr>

          <td align='left' valign='top' class="tdfoot"> <input type="button" name="continue" value="Continue" class=button onClick="document.location.href='<?php echo $CONST_LINK_ROOT?>/speeddating/login.php'"/>

          </td>

        </tr>

      </table></td>

    </tr>

  </table>



<?=$skin->ShowFooter($area)?>

