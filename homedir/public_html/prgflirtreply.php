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
# Name: 		prgflirtreply.php
#
# Description:  Sends the responses from member flirts
#
# # Version:      8.0
#
######################################################################

include('db_connect.php');
include('session_handler.inc');
$tempdate=date("Y/m/d");
include('error.php');
# retrieve the template
$area = 'member';

# select the recipient of the reply
$query="SELECT mem_username, mem_password, mem_email FROM members WHERE mem_userid = $userid";
$result=mysql_query($query,$link) or die(mysql_error());
$sql_array = mysql_fetch_object($result);
# if the reply was no send this text
if ($rdoFlirtResponse[0] == 'N') {
	$FlirtResponse='N';
	$message="<div align='left'>
  <table border='0' cellpadding='0' cellspacing='0' width='450'>
    <tr>
      <td align='center' height='60' valign='middle'>
        <p align='center'><img border='0' src='$CONST_IMAGE_ROOT/$CONST_IMAGE_LANG/broken_heart.gif'></td>
    </tr>
    <tr>
      <td align='center'>".
	sprintf(PRGFLIRTREPLY_TEXT1,$handle,$Sess_UserName,$CONST_LINK_ROOT,$sql_array->mem_username,$sql_array->mem_password,$Sess_UserId,$Sess_UserName)
	."</td> </tr> <tr>
      <td height='60' valign='middle' align='center'>
        <p align='center'><img border='0' src='$CONST_IMAGE_ROOT/$CONST_IMAGE_LANG/broken_heart.gif'></td>
    </tr>
  </table>
</div>
";
# if the reply was yes then send this text
} else {
	$FlirtResponse='Y';
		$message="<div align='left'>
  <table border='0' cellpadding='0' cellspacing='0' width='450'>
    <tr>
      <td align='center' height='60' valign='middle'>
        <p align='center'><img border='0' src='$CONST_IMAGE_ROOT/$CONST_IMAGE_LANG/happy_heart.gif'></td>
    </tr>
    <tr>
      <td align='center'>"
       .sprintf(PRGFLIRTREPLY_TEXT2,$handle,$Sess_UserName,$CONST_LINK_ROOT,$sql_array->mem_username,$sql_array->mem_password,$Sess_UserId,$Sess_UserName,$Sess_UserName,$CONST_LINK_ROOT).
" </td>
    </tr>
    <tr>
      <td height='60' valign='middle' align='center'>
        <p align='center'><img border='0' src='$CONST_IMAGE_ROOT/$CONST_IMAGE_LANG/happy_heart.gif'></td>
    </tr>
  </table>
</div>
";
}
send_mail ("$sql_array->mem_email", "$CONST_FLIRTMAIL", PRGFLIRTREPLY_TEXT3, "$message","html","ON");
# update the flirt record with the reply
$query="UPDATE notifications SET ntf_response='$FlirtResponse' WHERE ntf_senderid=$userid and ntf_receiverid=$Sess_UserId";
$result=mysql_query($query,$link) or die(mysql_error());
mysql_close( $link );
?>
<?=$skin->ShowHeader($area)?>

  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
    <tr>
      <td align="right">
            <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
      </td>
    </tr>
    <tr>

    <td class="pageheader"><?echo FLIRTREPLY_SECTION_NAME?></td>
    </tr>
    <tr>
    <td><?echo PRGFLIRTREPLY_TEXT4?><br><br><a href='javascript:history.go(-1);'><?php echo GENERAL_CONTINUE?></a></td>
    </tr>
  </table>
<?=$skin->ShowFooter($area)?>