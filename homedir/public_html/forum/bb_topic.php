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
# Name: 		bb.php
#
# Description:
#
# # Version:      8.0
#
######################################################################
include('../db_connect.php');
if (isset($_SESSION['Sess_UserId'])) include('../session_handler.inc');

include ('../functions.php');
include('../error.php');
unset($_SESSION['stid']);
$topic_id = $db->escape(formGet('tid'));
$_SESSION['tid'] = $topic_id;
$db = & db::getInstance();
# retrieve the template
$area = 'member';

$query="SELECT *
        FROM bb_subtopics
        WHERE subtopic_status=1 AND topic_id = '$topic_id'
        ORDER BY subtopic_time DESC";
$subtopics_arr = $db->get_results($query);


$oTopic = $db->get_row("	SELECT *
                            FROM bb_forum f
                            LEFT JOIN  bb_topics t
                                ON (f.forum_id = t.forum_id)
                            WHERE topic_id = '$topic_id'");

?>

<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
  </tr>
  <tr>
    <td class="pageheader"><?=BB_FORUMS_SECTION_NAME?></td>
  </tr>
  <tr>
    <td>
    <a href="<?php echo $CONST_LINK_ROOT ?>/forum/forums.php" class="forumlinkshd"><?=BB_FORUMS_SECTION_NAME?></a> &raquo;
    <a href="<?php echo $CONST_LINK_ROOT ?>/forum/bb.php?fid=<?php echo $oTopic->forum_id ?>" class="forumlinkshd"><?=$oTopic->forum_title?></a> &raquo;
    <b><?=$oTopic->topic_title?></b>
    </td>
  </tr>
    <tr>
        <td class="tdhead" valign="middle" align="right" height="30"><a href='<?php echo $CONST_LINK_ROOT ?>/forum/bb_post.php?tid=<?php echo $oTopic->topic_id ?>' class='forumlinks'><?php echo BB_TOPIC_POST_ADD_SUB ?></a></td>
    </tr>
  <tr>
    <td>
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <input type="hidden" name="mode">
        <tr>
            <td class="tdhead">&nbsp;</td>
        </tr>
        <tr>

          <td align="center"><table border='0' cellpadding='2' cellspacing='1' width='100%'>
                <tr>
                  <td colspan="2" class="tdtoprow" align='left'><?=BB_TOPICS_TITLE?></td>
                  <td class="tdtoprow" align='center' width='110'><?=BB_TOPICS_TIME?></td>
                  <td class="tdtoprow" align='center' width='50'><?=BB_TOPICS_POSTS?></td>
                </tr>
<?php
        foreach ($subtopics_arr as $sql_array) {
            $query="SELECT COUNT(*) as all_posts
                    FROM bb_posts
                    WHERE subtopic_id = '$sql_array->subtopic_id' AND post_approved = 1";
            $all_posts = $db->get_row($query);

            $query="SELECT *, 
			YEAR(post_time) as year, MONTH(post_time) as month, DAY(post_time) as day,
			HOUR(post_time) as hrs, MINUTE(post_time) as mins, SECOND(post_time) as sec,
			mem_username FROM bb_posts LEFT JOIN members ON (poster_id = mem_userid)
                   WHERE topic_id = '$sql_array->topic_id' AND subtopic_id = '$sql_array->subtopic_id' ORDER BY post_time DESC LIMIT 1"; 
            if ($last_post = $db->get_row($query)) {
                $gmtime = gmdate("M d Y H:i:s", mktime($last_post->hrs,$last_post->mins,$last_post->sec,$last_post->month,$last_post->day,$last_post->year));
                $line="$gmtime<br><a href='$CONST_LINK_ROOT/prgretuser.php?userid=$last_post->poster_id'>$last_post->mem_username</a><a href='$CONST_LINK_ROOT/forum/bb_topic.php?tid=$sql_array->topic_id'><img border=0 src='$CONST_IMAGE_ROOT"."icon_latest_reply.gif'></a>";
            }else {
                $line="";
            }
            print("
            <tr onMouseOver='selected(this)' onMouseOut='deselected(this)' bgcolor='#f0f0f0'>
                <td  valign='middle' width='40' align='center'><img src='$CONST_IMAGE_ROOT"."folder_big.gif'></td>
                <td valign='middle'><a href='$CONST_LINK_ROOT/forum/bb_subtopic.php?stid=$sql_array->subtopic_id'  class='forumlinks'>$sql_array->subtopic_title</a></td>
                <td align='center' width='110' valign='middle'>$line</td>
                <td align='center' width='50' valign='middle'>$all_posts->all_posts</td>
              </tr>");
         }
        print("<tr>
          <td colspan='4' class='tdfoot' align='left'>&nbsp;</td>
        </tr>
         ");
?>
              </table></td>
        </tr>
        <tr>
            <td ><?php echo BB_FORUM_TIMEZONE ?>&nbsp;GMT</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>