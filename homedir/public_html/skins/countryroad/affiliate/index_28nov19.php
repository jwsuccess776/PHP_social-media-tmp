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
# Name: 		index.php
#
# Description:  Affiliate home page and login
#
# Version:		7.3
#
######################################################################
include('../db_connect.php');
include('error.php');

# retrieve the template
$area = 'affiliate';

if (isset($_POST['txtUsername'])) {

	$txtUsername=$_POST['txtUsername'];
	$txtPassword=$_POST['txtPassword'];

	# gives basic validation if the javascript fails to catch
	if (empty($txtUsername) || strlen($txtUsername) < 2) {
		$error_message=AFF_INDEX_ERROR1;
		error_page($error_message,GENERAL_USER_ERROR);
	}
	if (empty($txtPassword) || strlen($txtPassword) < 4) {
		$error_message=AFF_INDEX_ERROR2;
		error_page($error_message,GENERAL_USER_ERROR);
	}
	# if the validation is successful, then find the user
	$query="SELECT * FROM affiliates WHERE aff_username = '$txtUsername' AND aff_password = '$txtPassword'";
	$retval=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());
	$result=mysqli_num_rows($retval);
        echo $query;
	if ($result < 1) {
		$error_message=AFF_INDEX_ERROR3;
		error_page($error_message,GENERAL_USER_ERROR);
	} else {
		$arr_row = mysqli_fetch_object($retval);
		if ($arr_row->aff_approved='1') {
			// session_cache_limiter('private, must-revalidate');
			session_start();
			$_SESSION["private"] = "must-revalidate";
			$_SESSION['Sess_AffUserId']=$arr_row->aff_userid;
			$Sess_AffUserId=$_SESSION['Sess_AffUserId'];
			welcome_page($area);

		} else {
			$error_message=AFF_INDEX_ERROR4;
			error_page($error_message,GENERAL_USER_ERROR);
		}
	}
}



// Displayed after successful login
function welcome_page($area) {
	GLOBAL $CONST_LINK_ROOT, $CONST_IMAGE_LANG;
	GLOBAL $CONST_TABLE_WIDTH, $CONST_TABLE_ALIGN, $CONST_TABLE_CELLSPACING, $CONST_TABLE_CELLPADDING;
	$skin =& Skin::GetInstance();
	foreach ($GLOBALS as $name=>$value) {
	    if (preg_match("/^MENU/",$name)) global $$name;
	}
	?>
	<?=$skin->ShowHeader($area)?>

	<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

	    <?
		require('aff_menu.php');
	?>
	  <tr>
	    <td>&nbsp;</td>
	  </tr>
	</table>
	<?
	echo $skin->ShowFooter($area);
    exit;
}
?>
<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <form method="post" action="<?php echo $CONST_LINK_ROOT?>/affiliates/index.php">
       <tr>
      <td class="tdhead"><?php echo AFF_CENTER_SECTION_NAME ?></td>
    </tr>
    <tr>
      <td><table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">
          <tr>
            <td valign="top" align="left" colspan="2"><?php echo AFF_INDEX_WELLCOME?></td>
          </tr>
          <tr>

            <td colspan="2" align="left" valign="top" class="tdhead">&nbsp;
            </td>
          </tr>
          <tr class="tdodd">

            <td align="left" valign="top">
            <?php echo GENERAL_USERNAME?>
            </td>
            <td align="left" valign="top">
            <input type="text" class="input" name="txtUsername" size="20">
            </td>
          </tr>

          <tr class="tdeven">

            <td align="left" valign="top">
            <?php echo GENERAL_PASSWORD?>
            </td>
            <td align="left" valign="top">
            <input name="txtPassword" type="password" class="input" size="20">
            </td>
          </tr>

          <tr align="center">

            <td colspan="2" valign="top" class="tdfoot"><input name="submit" type='submit' class="button"  value='<?= BUTTON_LOGIN ?>'>
            </td>
          </tr>

  </table></td>
    </tr>


  </form>
</table>
<?=$skin->ShowFooter($area)?>