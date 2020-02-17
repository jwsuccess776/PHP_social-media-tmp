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
# Name:              adm_news_edit.php
#
# Description:
#
# Version:              7.2
#
######################################################################
include('../db_connect.php');
include('../session_handler.inc');
include('../error.php');
require_once __INCLUDE_CLASS_PATH."/class.SMS.php";
include('permission.php');

$id = formGet('id');

# retrieve the template
$area = 'member';

$sms = new SMS($id);

?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
        <tr>
      <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
      </td>
    </tr>
    <tr>

    <td class="pageheader">
      <?= $id ? "Edit carrier" : "Add carrier"?>
    </td>
    </tr>
  <tr>
    <td><? include("admin_menu.inc.php");?></td>
  </tr>
    <tr>
      <td>
          <form method="post" action="<?php echo $CONST_LINK_ROOT?>/admin/adm_sms.php" enctype="multipart/form-data">
          <input type="hidden" name="act" value="save">
          <input type="hidden" name="id" value="<?=$id?>">
		<table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
              <tr class="tdodd">
                  <td width=40% align=right><?=ADVERTISE_TITLE?></td>
                  <td> <input name="title" type="text" class="inputl" value="<?=$sms->title?>"></td>
              </tr>
              <tr class="tdeven">
                  <td  align=right>Address</td>
                  <td><input name="email" type="text" class="inputl" value="<?=$sms->email?>">
              </tr>
              <tr>
                  <td colspan="2" align="center" class="tdfoot">&nbsp; </td>
              </tr>
              <tr>
                  <td colspan="2" align="center" class="tdfoot"> <input name="submit" type="submit" class="button" value="<?=GENERAL_SAVE?>">
                      <input name="button" type="button" class="button" onclick="window.location = '<?=$CONST_ADMIN_LINK_ROOT?>/adm_sms.php'" value="<?=GENERAL_CANCEL?>">
                  </td>
              </tr>
        </table>
          </form>
	</td>
    </tr>
  </table>
<?=$skin->ShowFooter($area)?>
