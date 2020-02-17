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



include('../db_connect.php');

$is_speeddating = $_GET[speeddating];

if(!$_GET[redir]) $_SESSION['HISTORY_PAGE'] = null;

# retrieve the template

$area = 'speeddating';



if ($_GET[clear]){

           setcookie ("txtHandle_c", $txtHandle,time()-3600);

           setcookie ("txtPassword_c", $txtHandle,time()-3600);

    $_COOKIE[txtHandle_c] = '';

    $_COOKIE[txtPassword_c] = '';

}

/*$query="SELECT * FROM members WHERE mem_username = '$_COOKIE[txtHandle_c]' AND mem_password = '$_COOKIE[txtPassword_c]' AND mem_confirm = '1'";

$retval=mysql_query($query,$link) or die(mysql_error());

if (mysql_num_rows($retval))	{ Header("Location: $CONST_LINK_ROOT/prglogin.php");exit;}*/



// mysqli_close($link);

?>

<?=$skin->ShowHeader($area)?>

<!-- form begins here -->



<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

  <tr>

    <td class="pageheader"><?php echo LOGIN_SECTION_NAME ?></td>

  </tr>

  <tr>

    <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

        <form method="post" action="<?php echo $CONST_LINK_ROOT?>/prglogin.php" name="FrmLogin" onSubmit="return Validate_FrmLogin()">

        <input type="hidden" name="speeddating" value="1">

          <tr>

            <td colspan="3"  align="left" class="tdhead">&nbsp;</td>

          </tr>

          <tr >

            <td align="left" valign="middle" class="tdodd"><?php echo LOGIN_USERNAME?></td>

            <td align="left" valign="middle" class="tdodd"> <input type="text" name="txtHandle" size="20" tabindex="1" value="<?=$_COOKIE[txtHandle_c]?>"></td>

            <td valign="top" align="left" rowspan="5" class="tdeven" ><?php echo LOGIN_IF_YOU_MEMBER_HEAD ?>

              <a href="<?php echo $CONST_LINK_ROOT?>/speeddating/register.php" tabindex="5"><?php echo LOGIN_CLICK_HERE ?></a>

              <?php echo LOGIN_IF_YOU_MEMBER_TAIL ?></td>

          </tr>

          <tr >

            <td align="left" valign="middle" class="tdeven"><?php echo LOGIN_PASSWORD ?></td>

            <td align="left" valign="middle" class="tdeven"> <input type="password" name="txtPassword" size="20" tabindex="2" value="<?=$_COOKIE[txtPassword_c]?>">

            </td>

          </tr>

          <tr >

            <td colspan="2" align="left" valign="middle" class="tdodd"><?php echo LOGIN_LOG_AUTOMATICALY ?>

              <input type=checkbox name="save"<?php if(isset($_COOKIE[txtHandle_c])) echo ' checked' ?>>

            </td>

          </tr>

          <tr >

            <td colspan="2" align="left" valign="middle" class="tdfoot"> <input type="submit" name=login value="Login Now" class=input_button tabindex="3">

            </td>

          </tr>

          <tr >

            <td colspan="2" align="left" valign="middle"><?php echo LOGIN_LOST_PASSWORD_HEAD ?>

              <a href="<?php echo $CONST_LINK_ROOT?>/speeddating/resend.php" tabindex="4">

              <?php echo LOGIN_CLICK_HERE ?></a> <?php echo LOGIN_LOST_PASSWORD_TAIL ?>

            </td>

          </tr>

        </form>

      </table> </td>

  </tr>

</table>



<?=$skin->ShowFooter($area)?>

