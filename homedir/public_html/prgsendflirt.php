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

# Name:         prgsendflirt.php

#

# Description:  Sends a member flirt message

#

# Version:      7.2

#

######################################################################



include('db_connect.php');

include_once 'validation_functions.php';

include('session_handler.inc');

include('error.php');

include('functions.php');

include('message.php');



$query="SELECT * FROM adverts WHERE adv_userid='$Sess_UserId' AND adv_paused='N' AND adv_approved = 1";

$retval=mysqli_query($globalMysqlConn, $query ) or die(mysqli_error());

if (mysqli_num_rows($retval) < 1) {

    $error_message=PRGSENDFLIRT_TEXT7;

    display_page($error_message,PRGSENDFLIRT_TEXT6);

}



$userid=sanitizeData(formGet('userid'), 'xss_clean'); 

$handle=sanitizeData(formGet('handle'), 'xss_clean'); 

$SEND=sanitizeData(formGet('SEND'), 'xss_clean'); 



# retrieve the template

$area = 'member';



if ($SEND) {

    $tempdate=date("Y/m/d");

    $query="INSERT INTO notifications (ntf_senderid, ntf_receiverid, ntf_senderhandle, ntf_receiverhandle, ntf_dateadded, ntf_response) VALUES ('$Sess_UserId', '$userid','$Sess_UserName', '$handle','$tempdate','W' )";

    $result=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

    $query="SELECT mem_username,mem_password, mem_email FROM members WHERE mem_userid = '$userid'";

    $result=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

    $sql_array = mysqli_fetch_object($result);

    $sql_array->mem_username=trim($sql_array->mem_username);

    $data['ReceiverName'] =str_replace(" ","%20",$sql_array->mem_username);

    $data['FlirtMessage'] =sanitizeData(formGet('Text'), 'xss_clean'); 

    $data['SenderName'] = $Sess_UserName;

    $data['CompanyName'] = $CONST_COMPANY;

    $data['ProfileLink'] = "$CONST_LINK_ROOT/prgretuser.php?userid=$Sess_UserId";

    $data['ImagePath'] = "$CONST_IMAGE_ROOT/$CONST_IMAGE_LANG/";

    list($type,$message) = getTemplateByName("Flirt_Mail",$data,getDefaultLanguage($userid));

    $message = stripslashes($message);



    # send the mail externally

    send_mail ("$sql_array->mem_email", "$CONST_FLIRTMAIL", PRGSENDFLIRT_TEXT2 , "$message",$type,"ON");



    # send the flirt internally

    $subject=addslashes(PRGSENDFLIRT_TEXT4);

    list($type,$message) = getTemplateByName("Flirt_Message",$data,getDefaultLanguage($userid));

    $message = addslashes($message);

    $query="INSERT INTO messages (msg_senderid, msg_receiverid, msg_senderhandle, msg_title, msg_text, msg_dateadded, msg_read, msg_flirt) VALUES ('$Sess_UserId', '$userid', '$Sess_UserName', '$subject', '$message', '$tempdate', 'U', 'Y')";

    mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

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

        <td class="pageheader"><?php echo PRGSENDFLIRT_SEND ?></td>

    </tr>

    <tr>

        <td  class="tdhead">&nbsp;</td>

    </tr>

<?php if ($SEND) {?>

    <tr>

        <td><?php echo PRGSENDFLIRT_TEXT3?> <p><a href='javascript:history.go(-2);'><?php echo GENERAL_CONTINUE?></a></p></td>

    </tr>

<?php } else {?>

    <tr>

        <form name=sendform action="prgsendflirt.php" method=POST>

            <input type=hidden name="userid" value="<?=$userid?>">

            <input type=hidden name="handle" value="<?=$handle?>">

        <td>

            <table width=80% align="center" border="0" cellspacing="0" cellpadding="0">

            <tr>

                <td>

                    <?=PRGSENDFLIRT_TEXT8?>:

                </td>

                <td>

                    <select name="Text" size="1" style="width:auto;" class="input">

<?php

    $query = "SELECT * FROM lang_flirt WHERE lang_id='$language->LangID'";

    $res = mysqli_query($globalMysqlConn,$query);

    while ($flirt = mysqli_fetch_object($res)){

?>

                        <option value="<?=$flirt->Text?>" <?php if(++$i == 1){?>selected<?php }?>><?=$flirt->Text?></option>

                    <?php }?>

                    </select>

                </td>

                <td>

                    <input type=submit name="SEND" value="<?=PRGSENDFLIRT_SEND?>" class="button">

                </td>

            </tr>

            </table>

        </td>

        </form>

    </tr>

<?php } ?>

    <tr>

      <td align="center" class="tdfoot">&nbsp; </td>

    </tr>

  </table>

<?=$skin->ShowFooter($area)?>