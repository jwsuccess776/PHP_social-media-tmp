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
# Name:         myblogs.php
#
# Description:  Returns individual member blogs
#
# Version:      7.2
#
######################################################################
include('../db_connect.php');
if (isset($_SESSION['Sess_UserId'])) include('../session_handler.inc');
include($CONST_INCLUDE_ROOT.'/functions.php');
include($CONST_INCLUDE_ROOT.'/error.php');
include_once __INCLUDE_CLASS_PATH."/class.Emoticons.php";
include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";
include_once __INCLUDE_CLASS_PATH."/class.CommentManager.php";
include_once __INCLUDE_CLASS_PATH."/class.Tagging.php";
include_once('../validation_functions.php');

$db = & db::getInstance();
$emotions = new Emoticons();
$user_id = formGet('user_id');
$tagging = new Tagging('blog');

if ((isset($_REQUEST['type']) && $_REQUEST['type']=='delete') && $Sess_UserType == "A") {
	if (isset($_REQUEST['id'])) {
		$id=sanitizeData($_REQUEST['id'], 'xss_clean');  
		$result=$db->get_results("DELETE FROM blogs WHERE blg_id=$id");
		$result=$db->get_results("DELETE FROM comments WHERE ent_id=$id AND type='blog'");
	}
}

if ($Sess_UserId){
$private_list = $db->get_col("SELECT hot_userid FROM hotlist WHERE hot_advid = $Sess_UserId AND hot_private='Y'");
}
$private_list[] = -1;
$allow_private = join(",",$private_list);

$user_query = ($user_id) ? " AND blg_userid = $user_id" : "";

$where = " blg_approved='Y' AND (blg_private = 'N' OR (blg_private = 'Y' AND blg_userid IN ($allow_private)) OR blg_userid = '$Sess_UserId') $user_query ";

if ($tag_id = formGet('tag_id')) {
    $result = $tagging->getEntList($tag_id, 'blogs', 'blg_id', $pager, $where, "blg_datetime DESC", " INNER JOIN adverts ON (blg_userid=adv_userid)");
    $tag = new Tag();
    $tag->initById($tag_id);
} else {

    $query ="
        SELECT count(*)
        FROM blogs
            INNER JOIN adverts ON (blg_userid=adv_userid)
        WHERE
            $where
        ";

    $limit = $pager->GetLimit($db->get_var($query));
    $pager->SetUrl("$CONST_LINK_ROOT/blog/blogs.php?user_id=$user_id");

    $result=$db->get_results("
        SELECT *
        FROM blogs
            INNER JOIN adverts ON (blg_userid=adv_userid)
        WHERE
            $where
        ORDER BY blg_datetime DESC $limit");
}
# retrieve the template
if (isset($_SESSION['Sess_UserId']))
    $area = 'member';
else
    $area = 'guest';
?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
  </tr>
  <tr>
    <td class="pageheader"><?php echo BLOGS_SECTION_NAME ?></td>
  </tr>
	<?php if ($tag_id) {?>
  <tr>
	    <td class=""><?=TAG?> : <?=$tag->tag?> <a href="<?=$CONST_BLOG_LINK_ROOT?>/blogs.php"><?=GENERAL_ALL?></a></td>
  </tr>
	<?php }?>
  <tr>
    <td> <table width="100%" align="left" border="0"  cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
      <tr >
        <td colspan="2" align="right">
            <?include CONST_INCLUDE_ROOT."/search_pager.php"?>
        </td>
      </tr>
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
                <? if($Sess_UserType == "A") {?> <a href='<?=$CONST_BLOG_LINK_ROOT?>/blogs.php?id=<?=$sql_array->blg_id?>&type=delete'><?=MYDELBLOGS_SECTION_NAME?></a> <? }?>
            &nbsp;&nbsp;<a href="blog_comments.php? ">
				<? if($comment_manager->count()) {?> <?=SHOW_COMMENTS?> <?} else {?> <?=ADD_COMMENT?> <?}?>
            </a>
          </td>
        </tr>
        <?php }?>
      <tr >
        <td colspan="2" align="right">
            <?include CONST_INCLUDE_ROOT."/search_pager.php"?>
        </td>
      </tr>
      </table>
     </td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>