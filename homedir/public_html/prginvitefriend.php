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
include('session_handler.inc');

include('error.php');
$txtEmail=formGet('txtEmail');
$txtMessage=formGet('txtMessage');

# retrieve the template
$area = 'member';
foreach ($txtEmail as $email) {
    if (strlen($email) > 0 && strlen($email) < 6 ) error_page(PRGTIPMAIL_TEXT1,GENERAL_USER_ERROR);
}

include_once __INCLUDE_CLASS_PATH."/class.Network.php";
$network = new Network();
$network->saveInvitedFriends($Sess_UserId, $txtEmail);

include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";
$adv = new Adverts();
$adv->InitById($Sess_UserId);
$subject= MESSAGE_FROM." $adv->mem_forename $adv->mem_surname";
$data['Message'] = $txtMessage;
$data['CompanyName'] = $CONST_COMPANY;
$data['SiteUrl'] = "$CONST_LINK_ROOT";
$mail_template = "Tell_A_Friend_Guest";
list($type,$message) = getTemplateByName($mail_template,$data,getDefaultLanguage($Sess_UserId));
foreach ($txtEmail as $email) {
    if (trim($email)) send_mail ($email, "$CONST_MAIL", "$subject", "$message","html","ON");
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

    <td class="pageheader"><?php echo INVITE_MAIL_SECTION_NAME ?></td>
    </tr>
    <tr>

    <td><?php echo PRGTIPMAIL_TEXT4?> <p><a href='javascript:history.go(-2);'><?php echo GENERAL_CONTINUE?></a></p></td>
    </tr>
  </table>
<?=$skin->ShowFooter($area)?>