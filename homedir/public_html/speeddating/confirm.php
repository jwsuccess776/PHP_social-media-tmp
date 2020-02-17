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

include('../db_connect.php');

include('error.php');

include_once('../validation_functions.php');


$id=sanitizeData(trim($_GET['id']), 'xss_clean');  



$query="UPDATE members SET mem_confirm = 1 WHERE MD5(mem_userid) = '$id'";

$result=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());



$query="SELECT * FROM members WHERE MD5(mem_userid) = '$id'";

$result=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

$sql_array=mysqli_fetch_object($result);

if ($sql_array->mem_confirm!=1) {

	$error_message=LOGIN_ERROR3;

	error_page($error_message,GENERAL_USER_ERROR);

	exit;

}

$area = 'speeddating';



?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">



    <tr>



    <td class="pageheader"> &lt;tr&gt; <br> &lt;td align=&quot;right&quot;&gt;

      <br> &lt;?php<br>

      if ($Sess_UserType == &quot;A&quot;) {<br>

      print(&quot;&lt;a href='$CONST_LINK_ROOT/admin/index.php'&gt;&lt;img

      border='0' src='$CONST_IMAGE_ROOT/$CONST_IMAGE_LANG/mem_$Sess_Userlevel.gif'

      width='$CONST_MEMIMAGE_WIDTH' height='$CONST_MEMIMAGE_HEIGHT'&gt;&quot;);<br>

      } else {<br>

      print(&quot;&lt;img border='0' src='$CONST_IMAGE_ROOT/$CONST_IMAGE_LANG/mem_$Sess_Userlevel.gif'

      width='$CONST_MEMIMAGE_WIDTH' height='$CONST_MEMIMAGE_HEIGHT'&gt;&quot;);<br>

      }<br>

      ?&gt;<br> &lt;/td&gt;<br> &lt;/tr&gt;<?php echo CONFIRM_SECTION_NAME ?></td>

    </tr>

    <tr>

      <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

        <form method="post" action="<?php echo $CONST_LINK_ROOT?>/prglogin.php" name="FrmLogin" onSubmit="return Validate_FrmLogin()">

          <input type="hidden" name="speeddating" value="1">

          <tr >

            <td colspan="4" align="left" valign="middle" class="tdhead">&nbsp;</td>

          </tr>

          <tr >

            <td align="left" valign="middle" class="tdodd"><?php echo LOGIN_USERNAME?></td>

            <td align="left" valign="middle" class="tdodd"> <input name="txtHandle" type="text" class="input" tabindex="1" size="20"></td>

            <td valign="top" align="left">&nbsp;</td>

            <td rowspan="5" align="left" valign="top" class="info" ><?php echo LOGIN_IF_YOU_MEMBER_HEAD ?>

              <a href="<?php echo $CONST_LINK_ROOT?>/register.php" tabindex="5"><?php echo LOGIN_CLICK_HERE ?></a>

              <?php echo LOGIN_IF_YOU_MEMBER_TAIL ?></td>

          </tr>

          <tr >

            <td align="left" valign="middle" class="tdeven"><?php echo LOGIN_PASSWORD ?></td>

            <td align="left" valign="middle" class="tdeven"> <input name="txtPassword" type="password" class="input" tabindex="2" size="20">

            </td>

            <td valign="top" align="left">&nbsp;</td>

          </tr>

          <tr >

            <td colspan="2" align="left" valign="middle" class="tdodd"><?php echo LOGIN_LOG_AUTOMATICALY ?>

              <input type=checkbox name="save"> </td>

            <td valign="top" align="left">&nbsp;</td>

          </tr>

          <tr >

            <td colspan="2" align="center" valign="middle" class="tdfoot"> <input type="submit" name=login value="Login Now" class=button tabindex="3">

            </td>

            <td valign="top" align="left">&nbsp;</td>

          </tr>

          <tr >

            <td colspan="2" align="left" valign="middle"><?php echo LOGIN_LOST_PASSWORD_HEAD ?>

              <a href="<?php echo $CONST_LINK_ROOT?>/speeddating/resend.php" tabindex="4"><?php echo LOGIN_CLICK_HERE ?></a>

              <?php echo LOGIN_LOST_PASSWORD_TAIL ?> </td>

            <td valign="top" align="left">&nbsp;</td>

          </tr>

        </form>

      </table></td>

    </tr>

  </table>



<?=$skin->ShowFooter($area)?>

