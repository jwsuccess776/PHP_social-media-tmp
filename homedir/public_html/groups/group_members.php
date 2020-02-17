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
# Name:                 whoson.php
#
# Description:  Main search processing program
#
# Version:               7.2
#
######################################################################

include('../db_connect.php');
include('../session_handler.inc');
include('../error.php');
include('../functions.php');
include_once __INCLUDE_CLASS_PATH."/class.Group.php";
require_once ( __INCLUDE_CLASS_PATH . '/class.RadiusAssistant.php' );
save_request();

include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";
$adv = new Adverts();

# retrieve the template
$area = 'member';

$gid = formGet('gID');

$group = new Group($gid);
$pager->SetUrl("$CONST_LINK_ROOT/groups/group_members.php?gID=$gid");
$owner = $group->owner;
$members = $group->getMembers(&$pager);
?>
<?=$skin->ShowHeader($area)?>
  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
    <tr>
      <td align="right">
          <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
      </td>
    </tr>
    <tr>
    <td class="pageheader"><?=GROUPS_MEMBERS_SECTION_NAME?></td>
    </tr>
<tr class="tdodd">
    <td ><b><?=GROUPS_MEMBERS_OWNER?></b></td>
    </tr>
    <tr>
      <td>
      
      <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
          <?php
# insert the line code here
$adv->InitById($owner);
//var_dump($owner);
$adv->SetImage('small');
$sql_array = $adv;
include("../user_list.inc.php");
?>
      </table>
      </td>
    </tr>
<tr class="tdodd">
    <td ><b><?=GROUPS_MEMBERS_MEMBERS?></b></td>
    </tr>

    <tr>
      <td>
      
      <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
          <tr >
            <td colspan="5" align="right">
                <?include "../search_pager.php"?>
            </td>
          </tr>
          <?php
# insert the line code here
foreach ($members as $sql_array) {

$adv->InitByObject($sql_array);
$adv->SetImage('small');
$sql_array = $adv;
include("../user_list.inc.php");
}
?>
      <tr>
        <td colspan="5" align=right>
            <?include "../search_pager.php"?>
        </td>
      </tr>
      </table>
      </td>
    </tr>
  </table>
<?=$skin->ShowFooter($area)?>