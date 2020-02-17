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
include($CONST_INCLUDE_ROOT.'/session_handler.inc');
include($CONST_INCLUDE_ROOT.'/functions.php');
include($CONST_INCLUDE_ROOT.'/error.php');
include_once __INCLUDE_CLASS_PATH."/class.Emoticons.php";
include_once __INCLUDE_CLASS_PATH."/class.Tagging.php";
include_once __INCLUDE_CLASS_PATH."/class.CommentManager.php";
include_once('../validation_functions.php');

$db = & db::getInstance();
$emotions = new Emoticons();
$tagging = new Tagging('blog');

if (isset($_REQUEST['action']) && $_REQUEST['action']=='delete') {
    $blg_id= sanitizeData($_REQUEST['blogid'], 'xss_clean'); 
    $db->query("
                DELETE FROM blogs
                WHERE blg_userid = '$Sess_UserId'
                AND blg_id = $blg_id"
            );
    include_once __INCLUDE_CLASS_PATH."/class.CommentManager.php";
    $comment_manager = new CommentManager('blog', $blg_id);
    foreach ($comment_manager->getList(new StdClass) as $comment)
        $comment->delete();

    $tagging->delete($blg_id);
}
if ($tag_id = formGet('tag_id')) {
    $result = $tagging->getEntList($tag_id, 'blogs', 'blg_id', $p = new stdClass(), "blg_userid = '$Sess_UserId'", "blg_datetime DESC");
    $tag = new Tag();
    $tag->initById($tag_id);
} else {
$result = $db->get_results("
                SELECT *
                FROM blogs
                WHERE blg_userid = '$Sess_UserId'
                ORDER BY blg_datetime DESC"
            );
}
# retrieve the template
$area = 'member';

?>

<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
      </td>
  </tr>
  <tr>
    <td><?include_once "blog_menu.inc.php"?></td>
  </tr>
  <tr>
    <td class="pageheader"><?php echo MYBLOGS_SECTION_NAME ?></td>
  </tr>
    <?if ($tag_id) {?>
    <tr>
        <td class=""><?=TAG?> : <?=$tag->tag?> <a href="<?=$CONST_BLOG_LINK_ROOT?>/myblogs.php"><?=GENERAL_ALL?></a></td>
    </tr>
    <?}?>
  <tr>
    <td>
       <table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">
    <?php foreach($result as $sql_array) {
            $comment_manager = new CommentManager('blog', $sql_array->blg_id);
            $sql_array->blg_message=$emotions->Parse($sql_array->blg_message);
    ?>
            <tr class="tdhead" >

          <td align="left" class="blogdate">
            <?php $text=($sql_array->blg_private=='Y')?GENERAL_PRIVATE:GENERAL_PUBLIC; echo $text;  ?>
            &nbsp;</td>
          <td align="right" class="blogdate"><?php echo date("D, j M Y G:i:s",strtotime($sql_array->blg_datetime)); ?>&nbsp;</td>
            </tr>
            <tr class="tdeven">

          <td colspan="2">
            <?=TAGS?>:
            <?php foreach ($tagging->getTagsList($sql_array->blg_id, 'array') as $tag) {?>
                <a href="<?=$CONST_BLOG_LINK_ROOT?>/myblogs.php?tag_id=<?=$tag->id?>"><?=$tag->tag?></a>
            <?php }?>
			</br>

          	<?php echo $sql_array->blg_message; ?>
            <!-- do not delete non breaking space -->&nbsp;
          </td>
            </tr>
            <tr align="right" class="tdfoot">
                <td colspan="2" >
                    <a href='<?php echo $CONST_BLOG_LINK_ROOT?>/myaddblog.php?action=edit&blogid=<?php echo $sql_array->blg_id ?>' ><?php echo MYEDITBLOGS_SECTION_NAME ?></a>&nbsp;|&nbsp;<a href='<?php echo $CONST_BLOG_LINK_ROOT?>/myblogs.php?action=delete&blogid=<?php echo $sql_array->blg_id ?>' onClick="return delete_alert5();" ><?php echo MYDELBLOGS_SECTION_NAME ?></a>&nbsp;|&nbsp;<a href="blog_comments.php?id=<?=$sql_array->blg_id?> "><?if($comment_manager->count()) {?> <?=SHOW_COMMENTS?><?}?></a>
                </td>
            </tr>
            <tr align="right"><td colspan="2">&nbsp;</td></tr>
    <?php }?>
        </table>
  </td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>