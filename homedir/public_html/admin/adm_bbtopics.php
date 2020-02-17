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
# Name:         adm_bbtopics.php
#
# Description:
#
# Version:      7.2
#
######################################################################
include('../db_connect.php');
include('../session_handler.inc');
include ('../functions.php');
include('../error.php');
include('permission.php');

if (isset($HTTP_GET_VARS['mode'])) $mode=$HTTP_GET_VARS['mode'];
if (isset($HTTP_POST_VARS['mode'])) $mode=$HTTP_POST_VARS['mode'];
if (isset($HTTP_GET_VARS['recid'])) $recid=$db->escape($HTTP_GET_VARS['recid']);
if (isset($_REQUEST['fid'])) $_SESSION['forum_id'] = $db->escape($HTTP_GET_VARS['fid']);
$forum_id = $_SESSION['forum_id'];
$txtTitle = $db->escape($HTTP_POST_VARS['txtTitle']);
$txtDescription = $db->escape($HTTP_POST_VARS['txtDescription']);

$db = & db::getInstance();

# retrieve the template
$area = 'member';

switch ($mode) {
    case 'save':
        $query="UPDATE bb_forum SET forum_title = '$txtTitle' WHERE forum_id = '$forum_id'";
        $db->query($query);
        break;
    case 'delete':
        $arr = $db->get_results("SELECT * FROM bb_posts WHERE subtopic_id = '$recid'");
        foreach ($arr as $row) {
            include_once __INCLUDE_CLASS_PATH."/class.ImageFile.php";
            $File = new ImageFile();
            $File->Init($row->post_id,'forum',$row->post_ext);
            $File->Delete();
            $db->query("DELETE FROM bb_posts WHERE post_id = $row->post_id");
        }

        $query="DELETE FROM bb_subtopics WHERE subtopic_id = '$recid'";
        $db->query($query);
        break;
}
$oForum = $db->get_row("SELECT * FROM bb_forum WHERE forum_id = '$forum_id'");



$query="SELECT t.subtopic_id, subtopic_title, subtopic_time, subtopic_status,
                COUNT(p1.post_id) as all_posts
        FROM bb_subtopics t
            LEFT JOIN bb_posts p1 ON (t.subtopic_id=p1.subtopic_id)
        WHERE forum_id = '$forum_id'
        GROUP BY t.subtopic_id, subtopic_title, subtopic_time, subtopic_status
        ORDER BY subtopic_time DESC";

$topics_arr = $db->get_results($query);
?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
  </tr>
  <tr>
    <td class="pageheader"><?=BB_TOPICS_SECTION_NAME?></td>
  </tr>
  <tr>
    <td>
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <form method="post" action="<?php echo $CONST_LINK_ROOT ?>/admin/adm_bbtopics.php" name="FrmEdit">
        <input type="hidden" name="mode">
        <tr>
           <td align="center">
              <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
                <tr>
                  <td colspan="2" class="tdhead">&nbsp;</td>
                </tr>
                <tr>
                  <td class="tdeven"><b><?=ADVERTISE_TITLE?>:</b></td>
                  <td class="tdeven"><input type="text" name="txtTitle" size="40" value="<?=$oForum->forum_title?>" class="inputf"></td>
                </tr>
                <tr>
                  <td colspan="2" class="tdfoot" align="center">
                    <input type='submit' class='button' value='<?=GENERAL_SAVE?>' onClick="FrmEdit.mode.value='save';">
                    <input type='button' class='button' value='<?=BUTTON_BACK?>' onClick="document.location='<?= $CONST_LINK_ROOT ?>/admin/adm_bbforums.php?';">
                  </td>
                </tr>
              </table>
            </td>
        </tr>
      </form>
      <form method="post" action="<?php echo $CONST_LINK_ROOT ?>/admin/adm_bbtopics.php" name="FrmTList">
        <input type="hidden" name="mode">

        <tr>
            <td class="tdhead">&nbsp;</td>
        </tr>
        <tr>
            <td align="center">
              <table border='0' cellpadding='2' cellspacing='1' width='100%'>
                <tr>
                  <td height='30' class="tdtoprow" align='center'><b><?=ADVERTISE_TITLE?></b></td>
                  <td height='30' class="tdtoprow" align='center'><b><?=BB_TOPICS_TIME?></b></td>
                  <td height='30' class="tdtoprow" align='center'><b><?=BB_TOPICS_POSTS?></b></td>
                  <td height='30' class="tdtoprow" align='center'><b><?=BB_TOPICS_POSTS_WAIT?></b></td>
<!--                  <td height='30' class="tdtoprow" align='center'><b><?=STATUS?></b></td>-->
                  <td height='30' width='60' class="tdtoprow">&nbsp;</td>
                  <td height='30' width='60' class="tdtoprow">&nbsp;</td>
                </tr>
<?php
        foreach ($topics_arr as $sql_array) {
           $query="SELECT COUNT(post_id) as wait_posts FROM bb_posts WHERE post_approved=0 AND subtopic_id='$sql_array->subtopic_id'";
           $wait_res = mysql_query($query,$link) or die(mysql_error());
           $wait_posts = mysql_fetch_object($wait_res);
           $sql_array->topic_time =  date(" $CONST_FORMAT_DATE_SHORT $CONST_FORMAT_TIME_SHORT",strtotime($sql_array->subtopic_time));
           print("<tr onMouseOver='selected(this)' onMouseOut='deselected(this)' bgcolor='#DCE8FC'>
                    <td height='30' valign='middle'>$sql_array->subtopic_title</td>
                    <td height='30' align='center' valign='middle'>$sql_array->subtopic_time</td>
                    <td height='30' align='center' valign='middle'>$sql_array->all_posts</td>
                    <td height='30' align='center' valign='middle'>$wait_posts->wait_posts</td>
           ");
/*
if ($sql_array->topic_status) {
             print("<td height='30' align='center' valign='middle'><a href='$CONST_LINK_ROOT/admin/adm_bbtopics.php?mode=switchoff&recid=$sql_array->subtopic_id' title='Change status'>".PREMIUM_FUNC_ACTIVE."</a></td>");
} else {
             print("<td height='30' align='center' valign='middle'><a href='$CONST_LINK_ROOT/admin/adm_bbtopics.php?mode=switchon&recid=$sql_array->subtopic_id' title='Change status'>".ADM_PAYMENTS_ACTIVATE."</a></td>");
}
*/
           print("
                    <td height='30' align='center' valign='middle'><a href='$CONST_LINK_ROOT/admin/bbsubtopics_edit.php?stid=$sql_array->subtopic_id'>".GENERAL_DETAILS."</a></td>
                    <td height='30' align='center' valign='middle'><a href='$CONST_LINK_ROOT/admin/adm_bbtopics.php?mode=delete&recid=$sql_array->subtopic_id'>".GENERAL_DELETE."</a></td>
                  </tr>");
        }
?>
              </table>
            </td>
        </tr>
        <tr>
            <td class="tdfoot">&nbsp;</td>
        </tr>
      </table>
      </form>
    </td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>