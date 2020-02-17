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
# Name:         bb.php
#
# Description:
#
# Version:      7.2
#
######################################################################
include('../db_connect.php');
if (isset($_SESSION['Sess_UserId'])) include('../session_handler.inc');
include ('../functions.php');
include('../error.php');
include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";
$adv = new Adverts();
# retrieve the template
# retrieve the template
if (isset($_SESSION['Sess_UserId']))
    $area = 'member';
else
    $area = 'guest';


$query="SELECT * FROM bb_forum
        WHERE forum_status=1 ORDER BY forum_title DESC";
$forum_arr = $db->get_results($query);
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
    <td><?php echo BB_FORUM_TIMEZONE ?>&nbsp;<?=date('T');?></td>
  </tr>
  <tr>
    <td>
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <input type="hidden" name="mode">
        <tr>
            <td align="center">
<?php
        foreach ($forum_arr as $sql_array) {
           $all_posts = 0;
           $sql_array->forum_time =  date(" $CONST_FORMAT_DATE_SHORT $CONST_FORMAT_TIME_SHORT",strtotime($sql_array->forum_time));
?>
                <table border='0' cellpadding='3' cellspacing='1' width='100%'>
                <tr>
                  <td colspan='2' class='tdtoprow' align='left'><?=ADVERTISE_TITLE?></td>
                  <td class='tdtoprow' align='center' width='50'><?=BB_TOPICS?></td>
                  <td class='tdtoprow' align='center' width='50'><?=BB_TOPICS_POSTS?></td>
                  <td class='tdtoprow' align='center' width='110'><?=BB_TOPICS_TIME?></td>
                </tr>
<?
          $query="SELECT *, mem_username 
                  FROM bb_posts p LEFT JOIN members ON (p.poster_id = mem_userid)
                  INNER JOIN bb_subtopics st ON (st.subtopic_id = p.subtopic_id)
                  INNER JOIN bb_forum f ON (f.forum_id = st.forum_id)
                  WHERE f.forum_id = '$sql_array->forum_id' ORDER BY post_time DESC LIMIT 1";
          if ($last_post = $db->get_row($query)) {
              		 $last_post->post_time =  myDate(" $CONST_FORMAT_DATE_SHORT $CONST_FORMAT_TIME_SHORT",strtotime($last_post->post_time));
					$line="$last_post->post_time<br><a href='$CONST_LINK_ROOT/prgretuser.php?userid=$last_post->poster_id'>$last_post->mem_username</a>
                                              <a href='$CONST_LINK_ROOT/forum/bb_subtopic.php?stid=$last_post->subtopic_id'><img border=0 src='$CONST_IMAGE_ROOT"."icon_latest_reply.gif'></a>";
          }else {
              $line="";
          }

          $query="SELECT count(*) FROM bb_subtopics
                  WHERE forum_id = '$sql_array->forum_id' AND subtopic_status=1 ";
          $topics = $db->get_var($query);

          $query="SELECT * FROM bb_subtopics
                  WHERE forum_id = '$sql_array->forum_id' AND subtopic_status=1 ORDER BY subtopic_time DESC";

          foreach ($db->get_results($query) as $t_array) {
              $query="SELECT COUNT(*) as all_posts
                      FROM bb_posts
                      WHERE subtopic_id = '$t_array->subtopic_id' AND post_approved = 1";
              $all_posts += $db->get_var($query);

          }
?>
                    <tr onMouseOver='selected(this)' onMouseOut='deselected(this)' bgcolor='#f0f0f0'>
                        <td  valign='middle' width='40' align='center'><img src='<?=$CONST_IMAGE_ROOT?>folder_big.gif'></td>
                        <td valign='middle'><a href='<?=$CONST_LINK_ROOT?>/forum/bb.php?fid=<?=$sql_array->forum_id?>' class='forumlinks'><?=$sql_array->forum_title?></a></td>
                        <td align='center' width='50' valign='middle'><?=$topics?></td>
                        <td align='center' width='50' valign='middle'><?=$all_posts?></td>
                        <td align='center' width='110' valign='middle'><?=$line?></td>
                     </tr>
                <tr>
                  <td colspan='5' class='tdfoot' align='left'>&nbsp;</td>
                </tr>
                <tr>
                  <td colspan='5' align='left'>&nbsp;</td>
                </tr>
               </table>
<?            }?>
            </td>
        </tr>
        <tr>
            <td >&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>