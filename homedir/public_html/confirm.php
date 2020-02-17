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
# Name: 		confirm.php
#
# Description:  accepts the email link to confirm maill address
#
# # Version:      8.0
#
######################################################################

include('db_connect.php');
include('pre_error.php');
include_once('validation_functions.php'); 
$id=sanitizeData($_REQUEST['id'], 'xss_clean') ; 

$query="UPDATE members SET mem_confirm = 1 WHERE MD5(mem_userid) = '$id'";
$result=mysql_query($query,$link) or die(mysql_error());

$query="SELECT * FROM members WHERE MD5(mem_userid) = '$id'";
$result=mysql_query($query,$link) or die(mysql_error());
$sql_array=mysql_fetch_object($result);
if ($sql_array->mem_confirm!=1) {
	$error_message=LOGIN_ERROR3;
	error_page($error_message,GENERAL_USER_ERROR);
	exit;
}
$area = 'guest';
?>
<?=$skin->ShowHeader($area)?>
  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
    </tr>
    <tr>

    <td class="pageheader"><?php echo CONFIRM_SECTION_NAME ?></td>
    </tr>
    <tr><td>
	<table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
        <form method="post" action="<?php echo $CONST_LINK_ROOT?>/prglogin.php" name="FrmLogin" onSubmit="return Validate_FrmLogin()">
          <tr>
            <td colspan="4"  align="left" class="tdhead">&nbsp;</td>
          </tr>
          <tr >
            <td align="left" class="tdodd"><?php echo LOGIN_USERNAME?></td>
            <td align="left" class="tdodd" > <input type="text" class="input" name="txtHandle" size="20" tabindex="1"></td>
            <td valign="top" align="left" rowspan="5"></td>
            <td valign="top" align="left" rowspan="5"><?php echo LOGIN_IF_YOU_MEMBER_HEAD ?>
              <a href="<?php echo $CONST_LINK_ROOT?>/register.php" tabindex="5"><?php echo LOGIN_CLICK_HERE ?></a>
              <?php echo LOGIN_IF_YOU_MEMBER_TAIL ?></td>
          </tr>
          <tr >
            <td align="left" class="tdeven"><?php echo LOGIN_PASSWORD ?></td>
            <td align="left" class="tdeven" > <input class="input" type="password" name="txtPassword" size="20" tabindex="2">
            </td>
          </tr>
          <tr >
            <td colspan="2" align="left" class="tdodd"><?php echo LOGIN_LOG_AUTOMATICALY ?>
              <input type=checkbox name="save"> </td>
          </tr>
          <tr >
            <td colspan="2" align="left" class="tdfoot"> <input type="submit" name="Submit" value="<?php echo BUTTON_LOGIN ?>" class="button">
            </td>
          </tr>
          <tr >
            <td colspan="2" align="left"><?php echo LOGIN_LOST_PASSWORD_HEAD ?>
              <a href="<?php echo $CONST_LINK_ROOT?>/resend.php" tabindex="4"><?php echo LOGIN_CLICK_HERE ?></a>
              <?php echo LOGIN_LOST_PASSWORD_TAIL ?> </td>
          </tr>
        </form>
      </table></td>
    </tr>
  </table>
<?=$skin->ShowFooter($area)?>