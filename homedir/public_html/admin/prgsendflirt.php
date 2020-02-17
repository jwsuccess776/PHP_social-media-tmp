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
# Name:         prgsendflirt.php
#
# Description:  Sends a member flirt message
#
# Version:      7.2
#
######################################################################

include('db_connect.php');
include('session_handler.inc');
include('error.php');
include('functions.php');
include('message.php');
include('permission.php');

$query="SELECT * FROM adverts WHERE adv_userid='$Sess_UserId' AND adv_paused='N' AND adv_approved = 1";
$retval=mysql_query($query,$link) or die(mysql_error());
if (mysql_num_rows($retval) < 1) {
    $error_message=PRGSENDFLIRT_TEXT7;
    display_page($error_message,PRGSENDFLIRT_TEXT6);
}

$userid=formGet('userid');
$handle=formGet('handle');
$SEND=formGet('SEND');

# retrieve the template
$area = 'member';

if ($SEND) {
    $tempdate=date("Y/m/d");
    $query="INSERT INTO notifications (ntf_senderid, ntf_receiverid, ntf_senderhandle, ntf_receiverhandle, ntf_dateadded, ntf_response) VALUES ('$Sess_UserId', '$userid','$Sess_UserName', '$handle','$tempdate','W' )";
    $result=mysql_query($query,$link) or die(mysql_error());
    $query="SELECT mem_username,mem_password, mem_email FROM members WHERE mem_userid = '$userid'";
    $result=mysql_query($query,$link) or die(mysql_error());
    $sql_array = mysql_fetch_object($result);
    $sql_array->mem_username=trim($sql_array->mem_username);
    $data['ReceiverName'] =str_replace(" ","%20",$sql_array->mem_username);
    $data['FlirtMessage'] = formGet('Text');
    $data['SenderName'] = $Sess_UserName;
    $data['CompanyName'] = $CONST_COMPANY;
    $data['ProfileLink'] = "$CONST_LINK_ROOT/prgshowuser.php?txtHandle=$sql_array->mem_username&txtPassword=$sql_array->mem_password&userid=$Sess_UserId";
    $data['ImagePath'] = "$CONST_IMAGE_ROOT/$CONST_IMAGE_LANG/";
    list($type,$message) = getTemplateByName("Flirt_Mail",$data,getDefaultLanguage($userid));
    $message = stripslashes($message);

    # send the mail externally
    send_mail ("$sql_array->mem_email", "$CONST_FLIRTMAIL", PRGSENDFLIRT_TEXT2 , "$message",$type,"ON");

    # send the flirt internally
    $subject=addslashes(PRGSENDFLIRT_TEXT4);
    list($type,$message) = getTemplateByName("Flirt_Message",$data,getDefaultLanguage($userid));
    $message = addslashes($message);
    $query="INSERT INTO messages (msg_senderid, msg_receiverid, msg_senderhandle, msg_title, msg_text, msg_dateadded, msg_read) VALUES ('$Sess_UserId', '$userid', '$Sess_UserName', '$subject', '$message', '$tempdate', 'U')";
    mysql_query($query,$link) or die(mysql_error());
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
    <td><? include("admin_menu.inc.php");?></td>
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
    $res = mysql_query($query);
    while ($flirt = mysql_fetch_object($res)){
?>
                        <option value="<?=$flirt->Text?>" <? if(++$i == 1){?>selected<?}?>><?=$flirt->Text?></option>
                    <?}?>
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
<? } ?>
    <tr>
      <td align="center" class="tdfoot">&nbsp; </td>
    </tr>
  </table>
<?=$skin->ShowFooter($area)?>