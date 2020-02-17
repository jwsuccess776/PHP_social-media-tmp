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

# Name: 		prgtipmail.php

#

# Description:  processes the tipmail addresses

#

# Version:		7,2

#

######################################################################



include('db_connect.php');

include_once 'validation_functions.php';

include('session_handler.inc');



include('error.php');

$txtEmail1=sanitizeData($_POST['txtEmail1'], 'xss_clean'); 

$txtEmail2=sanitizeData($_POST['txtEmail2'], 'xss_clean'); 

$txtEmail3=sanitizeData($_POST['txtEmail3'], 'xss_clean'); 

$txtEmail4=sanitizeData($_POST['txtEmail4'], 'xss_clean'); 

$txtEmail5=sanitizeData($_POST['txtEmail5'], 'xss_clean'); 

$txtMessage=sanitizeData(trim($_POST['txtMessage']), 'xss_clean'); 

$handle=sanitizeData($_POST['handle'], 'xss_clean');  

# retrieve the template

$area = 'member';

if (strlen($txtEmail1) < 2 && strlen($txtEmail2) < 2 &&

    strlen($txtEmail3) < 2 && strlen($txtEmail4) < 2 &&

    strlen($txtEmail5) < 2) {

        $error_message=PRGTIPMAIL_TEXT1;

        error_page($error_message,GENERAL_USER_ERROR);

}

# selects the user details to include in the mail

$query="SELECT mem_forename, mem_surname FROM members WHERE mem_userid=$Sess_UserId";

$result=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

$sql_array = mysqli_fetch_object($result);

$subject=MESSAGE_FROM." $sql_array->mem_forename $sql_array->mem_surname";



$query="SELECT mem_userid, mem_forename, mem_surname FROM members WHERE mem_username='$handle'";

$result=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

$user_array = mysqli_fetch_object($result);



    $data['Message'] = $txtMessage;

    $data['CompanyName'] = $CONST_COMPANY;

    $data['SiteUrl'] = "$CONST_LINK_ROOT";

    $data['ProfileUrl'] = "$CONST_LINK_ROOT/prgretuser.php?userid=".$user_array->mem_userid;

    $mail_template = "Tip_A_Friend";

    list($type,$message) = getTemplateByName($mail_template,$data,getDefaultLanguage($user_array->mem_userid));

/*

if (strlen($txtMessage) > 1) {

    $message=sprintf(PRGTIPMAIL_TEXT2,$sql_array->mem_forename,$txtMessage,$sql_array->mem_forename,$sql_array->mem_surname,$handle);

} else {

    $message=sprintf(PRGTIPMAIL_TEXT3,$sql_array->mem_forename,$sql_array->mem_surname,$handle);

}

*/

if (strlen($txtEmail1) > 5)send_mail ("$txtEmail1", "$CONST_MAIL", "$subject", "$message","html","ON");

if (strlen($txtEmail2) > 5)send_mail ("$txtEmail2", "$CONST_MAIL", "$subject", "$message","html","ON");

if (strlen($txtEmail3) > 5)send_mail ("$txtEmail3", "$CONST_MAIL", "$subject", "$message","html","ON");

if (strlen($txtEmail4) > 5)send_mail ("$txtEmail4", "$CONST_MAIL", "$subject", "$message","html","ON");

if (strlen($txtEmail5) > 5)send_mail ("$txtEmail5", "$CONST_MAIL", "$subject", "$message","html","ON");

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



    <td class="pageheader"><?php echo TIP_MAIL_SECTION_NAME ?></td>

    </tr>

    <tr>



    <td><?php echo PRGTIPMAIL_TEXT4?> <p><a href='javascript:history.go(-2);'><?php echo GENERAL_CONTINUE?></a></p></td>

    </tr>

  </table>

<?=$skin->ShowFooter($area)?>