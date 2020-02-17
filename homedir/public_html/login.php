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
# Name: 		login.php
#
# Description:  Member login screen
#
# # Version:      8.0
#
######################################################################
include('db_connect.php');

$is_speeddating = (isset($_GET['speeddating']))?$_GET['speeddating']:0;
if(!$_GET['redir']) $_SESSION['HISTORY_PAGE'] = null;
# retrieve the template
if ($is_speeddating)
    $area = 'speeddating';
else
    $area = 'guest';

if ($_GET['clear']){
           setcookie ("txtHandle_c", $txtHandle,time()-3600);
           setcookie ("txtPassword_c", $txtPassword,time()-3600);
    $_COOKIE['txtHandle_c'] = '';
    $_COOKIE['txtPassword_c'] = '';
}
/*$query="SELECT * FROM members WHERE mem_username = '$_COOKIE[txtHandle_c]' AND mem_password = '$_COOKIE[txtPassword_c]' AND mem_confirm = '1'";
$retval=mysql_query($query,$link) or die(mysql_error());
if (mysql_num_rows($retval))	{ Header("Location: $CONST_LINK_ROOT/prglogin.php");exit;}*/
?>
<?php echo $skin->ShowHeader($area); ?>
<table class="tblLogin" width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td class="pageheader"><?php echo LOGIN_SECTION_NAME ?></td>
  </tr>
  <tr>
    <td><table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">
        <form method="post" action="<?php echo $CONST_LINK_ROOT?>/prglogin.php" name="FrmLogin" onSubmit="return Validate_FrmLogin()">
          <?php if ($is_speeddating) { ?>
          <input type="hidden" name="speeddating" value="1">
          <?php } ?>
          <tr >
            <td colspan="3" align="left" class="tdhead"><?php echo LOGIN_IF_YOU_MEMBER_HEAD ?>&nbsp;<a href="<?php echo $CONST_LINK_ROOT?>/register.php" ><?php echo LOGIN_CLICK_HERE ?></a>
            </td>
          </tr>
          <tr >
            <td align="left" class="tdodd"><?php echo LOGIN_USERNAME?></td>
            <td align="left" class="tdodd" > <input type="text" class="input" name="txtHandle" size="20"  value="<?php echo $_COOKIE['txtHandle_c']; ?>">
            </td>
            <td rowspan="3" align="left" valign="top" class="tdeven" > <p><?php echo LOGIN_IF_YOU_MEMBER_TAIL ?></p></td>
          </tr>
          <tr >
            <td align="left" class="tdeven"><?php echo LOGIN_PASSWORD ?></td>
            <td align="left" class="tdeven" > <input name="txtPassword" type="password" class="input"  value="<?php echo $_COOKIE['txtPassword_c']; ?>" size="20">
            </td>
          </tr>
          <tr >
            <td colspan="2" align="left" class="tdodd"><?php echo LOGIN_LOG_AUTOMATICALY ?>
              <input type=checkbox name="save"<?php if(isset($_COOKIE['txtHandle_c'])) echo ' checked'; ?>>
            </td>
          </tr>
          <tr >
            <td colspan="3" align="center" class="tdfoot"> <input name="submit" type='submit' class="button"  value='<?php echo BUTTON_LOGIN ?>'>
            </td>
          </tr>
          <tr >
            <td colspan="3" align="left" ><?php echo LOGIN_LOST_PASSWORD_HEAD ?>
              <a href="<?php echo $CONST_LINK_ROOT?>/resend.php" ><br>
              <?php echo LOGIN_CLICK_HERE ?></a> <?php echo LOGIN_LOST_PASSWORD_TAIL ?>
            </td>
          </tr>
        </form>
      </table></td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>