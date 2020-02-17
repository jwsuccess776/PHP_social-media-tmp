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
include(CONST_INCLUDE_ROOT.'session_handler.inc');
include('permission.php');


include(CONST_INCLUDE_ROOT.'error.php');
include_once CONST_INCLUDE_ROOT."rating/stars.inc.php";

$area = 'member';
include_once __INCLUDE_CLASS_PATH."/class.Video.php";
include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";
include_once __INCLUDE_CLASS_PATH."/class.Tag.php";

$adv = new Adverts();

$video = new Video();

if (formGet('DEL')) {
    $video->InitById(formGet('vid_id'));
    $video->Delete($Sess_UserId,1);
}
$order = (formGet('order')) ? formGet('order') : 'new';

$username = formGet('username');
$videos = (array)$video->getListByName($pager, '', $order, $username);
?>
<?=$skin->ShowHeader($area)?>
  <table width="790" border="0" align="center" cellpadding="0" cellspacing="0" class="pageShadow">
    <tr>
      <td><table width="700" border="0" align="center" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" class="databox">
        <tr>
          <td align="right"><?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>          </td>
        </tr>
        <tr>
          <td class="pageheader"><?=VIDEO_LIST?></td>
        </tr>
		  <tr>
			<td><? include("admin_menu.inc.php");?></td>
		  </tr>
        <tr>
          <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
              <tr >
                <td colspan="3" align="center">
                    <form method=post>
                        <input type=text name=username value="<?=$username?>"> <input type=submit class=button name=SEARCH value="Filter by member">   
                    </form>
                </td>
              </tr>
              <tr >
                <td colspan="3" align="right"><?include $CONST_INCLUDE_ROOT."search_pager.php"?>                </td>
              </tr>
              <?php
foreach ($videos as $obj) {
$adv->InitById($obj->vid_userid);
$frame_info = $obj->getFrameInfo('small');
?>
              <tr>
                <td class="tdodd" width="20%">
                    <a href="<?=CONST_LINK_ROOT?>/show_video.php?vid_id=<?=$obj->vid_id?>"> <img border='0' src="<?=$CONST_MEDIA_LINK_ROOT?><?=$frame_info->Path?>" width="<?=$frame_info->w?>" /> </a> 
                </td>
                <td class="tdeven" ><a href='<?=$CONST_LINK_ROOT?>/show_video.php?vid_id=<?=$obj->vid_id?>'>
                  <?=$obj->vid_title?>
                  </a><br />
                  <?=BY?>
                  : <a href="<?CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$adv->adv_userid?>">
                    <?=$adv->adv_username?>
                    </a></br>
                  <?=ADDED?>
                  :
                  <?=$obj->getTimeShift()?>
                  </br>
                  <?=VIEWS?>
                  :
                  <?=$obj->vid_views?>
                  </br>
                  <?=TAGS?>
                  :
                  <?foreach ($obj->getTags('array') as $tag) {?>
                  <a href="<?=$CONST_LINK_ROOT?>/video_list.php?tag_id=<?=$tag->id?>">
                    <?=$tag->tag?>
                  </a>
                  <?}?>
                  </br>
                  <?show_rating($obj->rating);?>                
                </td>
                <td>
                    <form method=post>
                        <input type=hidden name=vid_id value="<?=$obj->vid_id?>">   
                        <input type=submit class=button name=DEL value="<?=GENERAL_DELETE?>" onClick="return delete_alert_general();">   
                    </form>
                </td>
              </tr>
              <?}?>
              <tr>
                <td colspan="3" align="right"><?include $CONST_INCLUDE_ROOT."search_pager.php"?>                </td>
              </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
  </table>
  <?=$skin->ShowFooter($area)?>
