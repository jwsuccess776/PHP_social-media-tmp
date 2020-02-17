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

# Name: 		prgtipmail.php

#

# Description:  processes the tipmail addresses

#

# # Version:      8.0

#

######################################################################



include('../db_connect.php');

include('session_handler.inc');



include('error.php');

$txtEmail1=$_POST['txtEmail1'];

$txtEmail2=$_POST['txtEmail2'];

$txtEmail3=$_POST['txtEmail3'];

$txtEmail4=$_POST['txtEmail4'];

$txtEmail5=$_POST['txtEmail5'];

$txtMessage=$_POST['txtMessage'];

$handle=$_POST['handle'];

# retrieve the template

$area = 'speeddating';



if (strlen($txtEmail1) < 2 && strlen($txtEmail2) < 2 &&

	strlen($txtEmail3) < 2 && strlen($txtEmail4) < 2 &&

	strlen($txtEmail5) < 2) {

		$error_message=SD_PRGTIPMAIL_TEXT1;

		error_page($error_message,GENERAL_USER_ERROR);

}

# selects the user details to include in the mail

$query="SELECT mem_forename, mem_surname FROM members WHERE mem_userid=$Sess_UserId";

$result=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

$sql_array = mysqli_fetch_object($result);

$subject="Message from $sql_array->mem_forename $sql_array->mem_surname";

if (strlen($txtMessage) > 1) {

	$message=sprintf(SD_PRGTIPMAIL_TEXT2,$txtMessage,$handle);

} else {

	$message=sprintf(SD_PRGTIPMAIL_TEXT3,$handle);

}

if (strlen($txtEmail1) > 5)send_mail ("$txtEmail1", "$CONST_MAIL", "$subject", "$message","text","ON");

if (strlen($txtEmail2) > 5)send_mail ("$txtEmail2", "$CONST_MAIL", "$subject", "$message","text","ON");

if (strlen($txtEmail3) > 5)send_mail ("$txtEmail3", "$CONST_MAIL", "$subject", "$message","text","ON");

if (strlen($txtEmail4) > 5)send_mail ("$txtEmail4", "$CONST_MAIL", "$subject", "$message","text","ON");

if (strlen($txtEmail5) > 5)send_mail ("$txtEmail5", "$CONST_MAIL", "$subject", "$message","text","ON");

// mysqli_close($link);

?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

  <tr>

    <td class="pageheader"><?php echo SD_TIP_SECTION_NAME ?></td>

  </tr>

  <tr>

    <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

        <tr>

          <td class="tdhead">&nbsp;</td>

        </tr>

        <tr>

          <td class="tdodd"><?php echo SD_PRGTIPMAIL_TEXT4?></td>

        </tr>

        <tr>

          <td align="center" class="tdfoot"> <input name="button" type=button class=button onClick="history.go(-2);" value="<?=GENERAL_CONTINUE?>">

          </td>

        </tr>

      </table></td>

  </tr>

</table>



<?=$skin->ShowFooter($area)?>

