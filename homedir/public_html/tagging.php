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

include('db_connect.php');
include('session_handler.inc');
include('error.php');
include_once "rating/stars.inc.php";

$area = 'member';
include_once __INCLUDE_CLASS_PATH."/class.Video.php";
include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";
include_once __INCLUDE_CLASS_PATH."/class.Tag.php";

if (!$tag_id = formGet('tag_id')) {
    error_page("Empty tag",GENERAL_USER_ERROR, $mode);
}
$adv = new Adverts();

$video = new Video();

$tag = new Tag();
$tag_ext = "&tag_id=$tag_id";

$videos = (array)$video->getList($pager, $tag_id, 'new');

include_once __INCLUDE_CLASS_PATH."/class.Emoticons.php";
include_once __INCLUDE_CLASS_PATH."/class.CommentManager.php";
include_once __INCLUDE_CLASS_PATH."/class.Tagging.php";


$emotions = new Emoticons();
$user_id = formGet('user_id');
$tagging = new Tagging('blog');

if ($Sess_UserId){
$private_list = $db->get_col("SELECT hot_userid FROM hotlist WHERE hot_advid = $Sess_UserId AND hot_private='Y'");
}
$private_list[] = -1;
$allow_private = join(",",$private_list);

$user_query = ($user_id) ? " AND blg_userid = $user_id" : "";

$where = " blg_approved='Y' AND (blg_private = 'N' OR (blg_private = 'Y' AND blg_userid IN ($allow_private)) OR blg_userid = '$Sess_UserId') $user_query ";

$result = $tagging->getEntList($tag_id, 'blogs', 'blg_id', $pager, $where, "blg_datetime DESC", " INNER JOIN adverts ON (blg_userid=adv_userid)");
$tag->initById($tag_id);

?>
<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right"><?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
  </tr>
  <tr>
    <td class="pageheader"><?=TAG?> : <?=$tag->tag?>
  </td>
  </tr>
  <tr>
    <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
<?if(count($videos)){?>
        <tr>
          <td class="tdhead"><?=VIDEO_LIST?></td>
        </tr>
        <tr>
          <td><?php
        foreach ($videos as $obj) {
        $adv->InitById($obj->vid_userid);
        $frame_info = $obj->getFrameInfo('small');
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
                  <td valign="top" class="resultimage"><a href="<?=CONST_LINK_ROOT?>/show_video.php?vid_id=<?=$obj->vid_id?>" class="imageframe"><img border='0' src="<?=CONST_LINK_ROOT?><?=$frame_info->Path?>" width="<?=$frame_info->w?>"></a> </td>
                  <td valign="top">
                    <span class="resulttitle"><?=BY.": "?></span>
                    <a href="<?CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$adv->adv_userid?>">
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
<?}?>
<?if (count($result)){?>
       <tr>
         <td class="tdhead"><?php echo MYBLOGS_SECTION_NAME ?></td>
       </tr>
  <tr>
    <td> <table width="100%" align="left" border="0"  cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
        <?php foreach($result as $sql_array) {
            $adv = new Adverts();
            $adv->InitById($sql_array->blg_userid);
            $adv->SetImage('small');
            $sql_array->blg_message=$emotions->Parse($sql_array->blg_message);
            $comment_manager = new CommentManager('blog', $sql_array->blg_id);
    ?>
        <tr class="tdhead" align="right">
          <td colspan="2" class="blogdate"><?php echo date("D, j M Y G:i:s",strtotime($sql_array->blg_datetime)); ?>&nbsp;</td>
        </tr>
        <tr class="tdeven">
          <td width="20%" align="center" valign="top"><a href="<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$sql_array->adv_userid?>"><img src='<?= $CONST_LINK_ROOT?><?=$adv->adv_picture->Path?>' width=<?=$adv->adv_picture->w?> name='pic' hspace="5" border=0 id=mainpicture></a><br>
            <em class="small">
            <?=$sql_array->adv_username?>
            </em></td>
          <td width="80%" align="left" valign="top">
            <?=TAGS?>:
            <?foreach ($tagging->getTagsList($sql_array->blg_id, 'array') as $tag) {?>
                <a href="<?=$CONST_BLOG_LINK_ROOT?>/blogs.php?tag_id=<?=$tag->id?>"><?=$tag->tag?></a>
            <?}?>
            </br>
          <?php echo $sql_array->blg_message; ?>
            <!-- do not delete non breaking space -->
            &nbsp;</td>
        </tr>
        <tr class="tdfoot">
          <td align=right colspan="2">
            <a href="blog_comments.php?id=<?=$sql_array->blg_id?> ">
                <?if($comment_manager->count()) {?> <?=SHOW_COMMENTS?> <?} else {?> <?=ADD_COMMENT?> <?}?>
            </a>
          </td>
        </tr>
        <? }?>
      </table>
     </td>
    </tr>
<?}?>
      </table></td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>
