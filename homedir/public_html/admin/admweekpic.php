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
# Name: 		admweekpic.php
#
# Description:  Displays all the photos submitted in a week to choose the
#				pic of the week from
# # Version:      8.0
#
######################################################################

include('../db_connect.php');
include_once('../validation_functions.php');
include('../session_handler.inc');
include('permission.php');

# retrieve the template
$area = 'member';

if (isset($_POST['femalepic'])) {
	$femalepic=sanitizeData($_POST['femalepic'], 'xss_clean');  
	$query="UPDATE picweek SET pic_female='$femalepic'";
	$retval=mysql_query($query,$link) or die(mysql_error());
}
if (isset($_POST['malepic'])) {
	$malepic=sanitizeData($_POST['malepic'], 'xss_clean');   
	$query="UPDATE picweek SET pic_male='$malepic'";
	$retval=mysql_query($query,$link) or die(mysql_error());
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
    <td class="pageheader"><?php echo ADMIN_SECTION_NAME ?></td>
  </tr>
  <tr>
    <td><? include("admin_menu.inc.php");?></td>
  </tr>
  <tr><td><table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
        <form action="<?php echo $CONST_LINK_ROOT?>/admin/admweekpic.php" method="post" name="frmPicweek">
          <tr>

            <td colspan="5" align="left" class="tdhead">&nbsp;</td>

          </tr>
          <?php
$query="SELECT * FROM adverts WHERE YEARWEEK(adv_createdate,3) = YEARWEEK(NOW(),3) AND adv_sex = 'M' AND adv_picture != '/images/<?= $CONST_IMAGE_LANG ?>
          /genericm.jpg' AND adv_approved=1"; $result=mysql_query($query,$link)
          or die(mysql_error()); while ($sql_array = mysql_fetch_object($result))
          { print("
          <tr>"); for ($i=1; $i < 5; $i++) { print("
            <td   align='center' valign='middle'><a href='$CONST_LINK_ROOT$sql_array->adv_picture' target='_blank'><img border='0' src='$CONST_LINK_ROOT$sql_array->adv_picture' width='60' height='80'><br>
              </a>
              <input type='radio' name='malepic' value='$CONST_LINK_ROOT$sql_array->adv_picture'>
            </td>
            "); if (!$sql_array = mysql_fetch_object($result)) { while ($i < 5)
            { print("
            <td   align='left' valign='middle'>&nbsp;</td>
            "); $i++; } break; } } print("</tr>
          "); } print("
          <tr>
            <td  colspan='5'  align='left' valign='middle'><hr></td>
          </tr>
          "); $query="SELECT * FROM adverts WHERE YEARWEEK(adv_createdate,3) =
          YEARWEEK(NOW(),3) AND adv_sex = 'F' AND adv_picture != '/images/
          <?= $CONST_IMAGE_LANG ?>
          /genericf.jpg' AND adv_approved=1"; $result=mysql_query($query,$link)
          or die(mysql_error()); while ($sql_array = mysql_fetch_object($result))
          { print("
          <tr>"); for ($i=1; $i < 5; $i++) { print("
            <td   align='center' valign='middle'><a href='$CONST_LINK_ROOT$sql_array->adv_picture' target='_blank'><img border='0' src='$CONST_LINK_ROOT$sql_array->adv_picture' width='60' height='80'><br>
              </a>
              <input type='radio' name='femalepic' value='$CONST_LINK_ROOT$sql_array->adv_picture'>
            </td>
            "); if (!$sql_array = mysql_fetch_object($result)) { while ($i < 5)
            { print("
            <td   align='left' valign='middle'>&nbsp;</td>
            "); $i++; } break; } } print("</tr>
          "); } ?>
          <tr>
            <td  align="left">&nbsp;</td>
            <td  align="left">&nbsp;</td>
            <td  align="left">&nbsp;</td>
            <td  align="left"></td>
            <td  align="left"></td>
          </tr>
          <tr>
            <td  colspan='5'  align='center' valign='middle' class="tdfoot"><input type="submit" name="Submit" value="<?php echo BUTTON_SUBMIT ?>" class="button"></td>
          </tr>
        </form>
      </table></td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>