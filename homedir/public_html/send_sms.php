<?
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
# Name:         send_sms.php
#
# Description:  
#
# Version:      7.2
#
######################################################################
include('db_connect.php');
include('session_handler.inc');

include_once __INCLUDE_CLASS_PATH."/class.SMS.php";
include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";

$userid = formGet('userid');
$message = formGet('message');
$user = new Adverts($userid);

if ($user->mem_sms != 1) die("You can't send sms to this member");

if (formGet('OK')) {
	$sms = new SMS($user->mem_carrier);
	if ($user->mem_userid && $sms->id && strlen($message) >0) {
		send_mail  ($user->mem_mobile."@".$sms->email,  $CONST_MAIL,  SMS_SUBJECT,  $message, 'text', 'ON');
	} 
# retrieve the template
$area = 'popup';
?>

<?=$skin->ShowHeader($area)?>
<table width=100% border=0 cellpadding="5" cellspacing="0" class="poptable">
	<tr>
		<td align=center><?=SENDSMS_TEXT?></td>
	</tr>
	<tr>
		<td align=center><input type=button onClick="javascript:window.close()" value="<?=GENERAL_CLOSE ?>"></td>
	</tr>
</table>
<?=$skin->ShowFooter($area)?>
<?} else {?>
<form method="post" enctype='multipart/form-data' action="<?php echo $CONST_LINK_ROOT?>/send_sms.php" name="FrmSMS" onSubmit="return Validate_FrmSMS()">
<table width=100% border=0 cellpadding="5" cellspacing="0" class="poptable">
<input type=hidden name=userid value="<?=$userid?>">
	<tr>
		<td ><?php echo ADMINMAIL_MESS?></td>
		<td><textarea class="inputl" name="message"></textarea></td>
	</tr>
	<tr>
		<td align=center><input type=submit name="OK" value="<?=BUTTON_SEND_SMS?>"></td>
		<td align=center><input type=button onClick="javascript:window.close()" value="<?=GENERAL_CLOSE ?>"></td>
	</tr>
</table>
</form>
<?}?>
