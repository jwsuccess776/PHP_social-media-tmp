<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
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
# Name: 		about.php
#
# Description:  Company information page ('About Us')
#
# # Version:      8.0
#
######################################################################
include('db_connect.php');

if ($_REQUEST['speeddating'] == 1) {
    $area = 'speeddating';
} else {
    if (isset($_SESSION['Sess_UserId']))
        $area = 'member';
    else
        $area = 'guest';
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
      <td class="pageheader"><?php echo ABOUT_SECTION_NAME ?></td>
    </tr>
    <tr>
      <td>
         <?php echo $CONST_COMPANY ?>
        <br />
        <?php echo $GLOBALS['CONST_MAIL'] ?>
        <br />
        <?php echo $GLOBALS['CONST_URL']; ?>
        <?php $about = getPageTemplate('about_content');?>
        <?php 
        eval("\$about = \"$about\";");
        echo $about; ?>

        </td>
    </tr>
  </table>
<?=$skin->ShowFooter($area)?>