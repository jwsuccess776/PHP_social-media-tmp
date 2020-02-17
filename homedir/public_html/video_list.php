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

# Name:                 whoson.php

#

# Description:  Main search processing program

#

# Version:               7.2

#

######################################################################



include('db_connect.php');

include('session_handler.inc');

include('error.php');

include_once "rating/stars.inc.php";



$area = 'member';

include_once __INCLUDE_CLASS_PATH."/class.Video.php";
include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";
include_once __INCLUDE_CLASS_PATH."/class.Tag.php";
$adv = new Adverts();
$video = new Video();

if ($tag_id = formGet('tag_id')) {
    $tag = new Tag();
    $oTag =  $tag->initById($tag_id);
    $tag_ext = "&tag_id=$tag_id";
}

$order = (formGet('order')) ? formGet('order') : 'new';
$userid = (formGet('userid')) ? formGet('userid') : 0;

$pager->PAGESIZE = ($pager->PAGESIZE == 20) ? 8 : $pager->PAGESIZE;
$pager->SetUrl("$CONST_LINK_ROOT/video_list.php?tag_id=$tag_id&order=$order&userid=$userid");

$videos = (array)$video->getList($pager, $tag_id, $order,$userid);

?>
<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right"><?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
  </tr>
  <tr>
    <td class="pageheader"><?=VIDEO_LIST?></td>
  </tr>
<?if (!$userid){?>
  <tr>
    <td><div id="mail">
        <ul id="mailnav">
          <li><a href="<?=$CONST_LINK_ROOT?>/video_list.php?order=new<?=$tag_ext?>" <?if ($order == 'new'){?>id='current'<?}?>>
            <?=VIDEO_LIST_NEW?>
            </a></li>
          <li><a href="<?=$CONST_LINK_ROOT?>/video_list.php?order=rated<?=$tag_ext?>" <?if ($order == 'rated'){?>id='current'<?}?>>
            <?=VIDEO_LIST_RATE?>
            </a></li>
          <li><a href="<?=$CONST_LINK_ROOT?>/video_list.php?order=view<?=$tag_ext?>" <?if ($order == 'view'){?>id='current'<?}?>>
            <?=VIDEO_LIST_VIEW?>
            </a></li>
        </ul>
      </div></td>
  </tr>
<?}?>
  <?if ($tag_id) {?>
  <tr>
    <td class="tdhead"><?=TAG?>
      :
      <?=$tag->tag?>
      <a href="<?=CONST_LINK_ROOT?>/video_list.php?order=<?=$order?>">
      <?=GENERAL_ALL?>
      </a></td>
  </tr>
  <?}?>
  <tr>
    <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
        <tr >
          <td align="right"><?include "search_pager.php"?>
          </td>
        </tr>
        <tr>
          <td><?php
        foreach ($videos as $obj) {
        $adv->InitById($obj->vid_userid);
        $frame_info = $obj->getFrameInfo('medium');
        ?>
            <div class="vid_search_result">
             <div class="resulthead">
             <a href='<?=$CONST_LINK_ROOT?>/show_video.php?vid_id=<?=$obj->vid_id?>'>
                    <?=$obj->vid_title?>
                    </a>
             </div>
             <div class="vid_resultbody">
              <table>
                <tr>
                  <td valign="top" class="resultimage"><a href="<?=CONST_LINK_ROOT?>/show_video.php?vid_id=<?=$obj->vid_id?>"><img style="margin-right:5px; margin-top:3px;" border='0' src="<?=CONST_LINK_ROOT?><?=$frame_info->Path?>" width="<?=$frame_info->w?>"></a> </td>
                  <td valign="top">
                    <span class="resulttitle"><?=BY.": "?></span>
                    <a href="<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$adv->adv_userid?>">
                    <?=$adv->adv_username?>
                    </a></br>
                    <span class="resulttitle"><?=ADDED.": "?></span>
                    
                    <?=$obj->getTimeShift()?>
                    </br>
                    <span class="resulttitle"><?=VIEWS.": "?></span>
                    
                    <?=$obj->vid_views?>
                    </br>
                    <span class="resulttitle"><?=TAGS.": "?></span>
                    
                    <?foreach ($obj->getTags('array') as $tag) {?>
                    <a href="<?=$CONST_LINK_ROOT?>/video_list.php?tag_id=<?=$tag->id?>">
                    <?=$tag->tag?>
                    </a> 
                    <? } ?>
                    </br>
                    <span class="resulttitle"><?show_rating($obj->rating);?></span>
                    </td>
                </tr>
              </table>
            </div> </div>
            <? } ?></td>
        </tr>
        <tr>
          <td colspan="2" align=right><?include "search_pager.php"?>
          </td>
        </tr>
      </table></td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>
