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
# Name:         login.php
#
# Description:  Member login screen
#
# Version:      7.2
#
######################################################################
include('../db_connect.php');
include(CONST_INCLUDE_ROOT.'/error.php');
include_once __INCLUDE_CLASS_PATH."/class.Network.php";
include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";
include(CONST_INCLUDE_ROOT.'/session_handler.inc');

$network = new Network();
$level = formGet('level');
$user_id = formGet('user_id');
if (empty($user_id))$user_id=$_SESSION['Sess_UserId'];
$area = 'member';
$list = $network->getNetwork($user_id,1);
if ($list === null) error_page("Incorrect user ID",GENERAL_USER_ERROR);

?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
  </tr>
  <tr>
    <td class="pageheader"><?php echo SOCIAL_NETWORK_SECTION_NAME ?></td>
  </tr><tr>
  <td> <table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">
      <?
    $row_count = count($list);
    foreach ($list as $id){
        $adv = new Adverts();
        $adv->InitById($id);
        $adv->SetImage('medium');
        $curr_row_num++;
?>
      <?  if($curr_row_num == 1){?>
      <tr class="td2">
        <td colspan='3' align='left' > <table border='0' width='100%' cellpadding='0' celspacing='0'>
            <tr>
              <?}?>
              <td align='center' valign='middle' width='33%' > <table width='100%' align='center' border="0" cellpadding='1' celspacing='0'>
                  <tr>
                    <td align='center'><table  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="imageframe"><a href="<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$adv->adv_userid?>"><img border='0' src="<?=$CONST_LINK_ROOT?><?=$adv->adv_picture->Path?>?<?=time()?>" width=<?=$adv->adv_picture->w?>></a>
</td>
  </tr>
</table>
                    </td>
                  </tr>
                  <tr>
                    <td align='center'> <a href="<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$adv->adv_userid?>">
                      <?=$adv->adv_username?>
                      </a> </td>
                  </tr>
                </table></td>
              <? if($curr_row_num == $row_count){
                while($curr_row_num < 3) {?>
              <td width='33%'>&nbsp;</td>
              <? $curr_row_num++;
                }?>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td class='tdfoot' colspan='3'>&nbsp;</td>
      </tr>
      <? } elseif($curr_row_num % 3 == 0){?></tr>
      <tr>
        <?}?>
        <?}?>
    </table></td></tr>
</table>
<?=$skin->ShowFooter($area)?>
