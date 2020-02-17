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
# Name:         choice_edit.php
#
# Description:
#
# Version:      5.0
#
######################################################################
include('../db_connect.php');
include('../session_handler.inc');
include_once __INCLUDE_CLASS_PATH."/class.ImageFile.php";
include('permission.php');

include('../error.php');
if (isset($HTTP_POST_VARS['mode'])) $mode=$HTTP_POST_VARS['mode'];
$subtopic_id=formGet('stid');
$post_id=formGet('pid');

$forum_id=$db->get_var("SELECT forum_id FROM bb_subtopics WHERE subtopic_id = '$subtopic_id'");

if (isset($HTTP_POST_VARS['pid'])) $post_id=$HTTP_POST_VARS['pid'];

$cur_post_status = (isset($HTTP_POST_VARS['cps']))?$HTTP_POST_VARS['cps']:"";

# retrieve the template
$area = 'member';

switch ($mode) {
    case 'save':
        $txtTitle = mysql_escape_string($HTTP_POST_VARS['txtTitle']);
        $txtDescription = mysql_escape_string($HTTP_POST_VARS['txtDescription']);

        $lstStatus=$HTTP_POST_VARS['lstStatus'];
        $db->query("UPDATE bb_subtopics SET subtopic_title='$txtTitle', subtopic_time=NOW(), subtopic_status='$lstStatus' 
                    WHERE subtopic_id = '$subtopic_id'");
        header("Location: $CONST_LINK_ROOT/admin/adm_bbtopics.php?fid=$forum_id");
        break;
    case 'approve':
        $db->query("UPDATE bb_posts SET post_approved='1' WHERE post_id = '$post_id'");
        if ($db->get_var("SELECT count(*) cnt FROM bb_posts WHERE  subtopic_id = '$subtopic_id'") == 1) {
            $db->query("UPDATE bb_subtopics SET subtopic_status='1' WHERE subtopic_id = '$subtopic_id'");
        }
        break;
    case 'delete':
        if ($db->get_var("SELECT count(*) cnt FROM bb_posts WHERE  subtopic_id = '$subtopic_id'") == 1) $del = true;
        $row = $db->get_row("SELECT * FROM bb_posts WHERE post_id = '$post_id'");
        if ($row) {
            include_once __INCLUDE_CLASS_PATH."/class.ImageFile.php";
            $File = new ImageFile();
            $File->Init($row->post_id,'forum',$row->post_ext);
            $File->Delete();
            $db->query("DELETE FROM bb_posts WHERE post_id = $row->post_id");
        }
        if ($del == true ){
          $db->query("DELETE FROM bb_subtopics WHERE subtopic_id = '$subtopic_id'");
            header("Location: $CONST_LINK_ROOT/admin/adm_bbtopics.php?fid=$forum_id");
            exit;
        }

        break;
}
$query = "SELECT * FROM bb_subtopics WHERE subtopic_id = '$subtopic_id'";
$result = mysql_query($query,$link) or die(mysql_error());
$cur_topic = mysql_fetch_object($result);

$_where = ($cur_post_status !== "")? " AND post_approved = '$cur_post_status'":"";

$query = "  SELECT *
            FROM bb_posts
                LEFT JOIN members
                    ON (poster_id=mem_userid)
            WHERE subtopic_id = '$subtopic_id' $_where
            ORDER BY post_time";
$posts_arr = mysql_query($query,$link) or die(mysql_error());

?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
  </tr>
  <tr>
    <td class="pageheader"><?=TOPIC_EDIT?></td>
  </tr>
  <tr>
    <td><? include("admin_menu.inc.php");?></td>
  </tr>
  <tr>
    <td>
      <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
      <form enctype='multipart/form-data' method='post' action='<?php echo $CONST_LINK_ROOT?>/admin/bbsubtopics_edit.php' name="FrmTEdit">
        <input type='hidden' name='tid' value='<?=$cur_topic->topic_id?>'>
        <input type='hidden' name='stid' value='<?=$subtopic_id?>'>
        <input type='hidden' name='cps' value='<?=$cur_post_status?>'>
        <input type='hidden' name='mode' value='save'>
        <tr>
          <td class="tdhead" align="right">
            <select class="input"  size="1" name="cps" onChange="FrmTEdit.mode.value='view'; FrmTEdit.submit(); return true;">
                <option <?php if ($cur_post_status==0) print("selected"); ?> value="0"><?=BB_TOPIC_POST_WAIT?></option>
                <option <?php if ($cur_post_status==1) print("selected"); ?> value="1"><?=GENERAL_APPROVE?></option>
                <option <?php if ($cur_post_status==="") print("selected"); ?> value=""><?=BB_TOPIC_POST_ALL?></option>
            </select>
          </td>
        </tr>
        <tr>
            <td align="center">
              <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
                <tr>
                  <td class="tdeven"><b><?=ADVERTISE_TITLE?>:</b></td>
                  <td class="tdeven"><input type="text" name="txtTitle" size="40" class="inputf" value='<?=$cur_topic->subtopic_title?>'></td>
                </tr>
                <tr>
                  <td width="25%" class="tdodd"><b><?=STATUS?>:</b></td>
                  <td width="75%" class="tdodd">
                    <select name="lstStatus" size="1" style="width:auto;" class="input">
                      <option value="1" <?php if ($cur_topic->subtopic_status) print("selected"); ?>><?=ADM_PAYMENTS_DEACTIVATE?></option>
                      <option value="0" <?php if (!$cur_topic->subtopic_status) print("selected"); ?>><?=ADM_PAYMENTS_ACTIVATE?></option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td colspan="2" class="tdfoot" align="center">
                    <input type='submit' class='button' value='<?=GENERAL_SAVE?>'>
                    <input type='button' class='button' value='<?=BUTTON_BACK?>' onClick="document.location='<?= $CONST_LINK_ROOT ?>/admin/adm_bbtopics.php?fid=<?=$cur_topic->forum_id?>';">
                  </td>
                </tr>
              </table>
            </td>
        </tr>
        <tr>
          <td class="tdhead"><?=BB_TOPIC_POSTS_SECTION_NAME?></td>
        </tr>
        <tr>
          <td align="center">
            <table border='0' cellpadding='2' cellspacing='1' width='100%'>
              <tr>
                <td height='30' class='tdtoprow' align='center'><b><?=PRGMAILBLOCK_SENDER?></b></td>
                <td height='30' class='tdtoprow' align='center'><b><?=BB_TOPIC_POST_TEXT?></b></td>
                <td height='30' class='tdtoprow' align='center'><b><?=BB_TOPIC_POST_TIME?></b></td>
                <td height='30' class='tdtoprow' align='center'><b><?=STATUS?></b></td>
                <td height='30' class='tdtoprow'>&nbsp;</td>
              </tr>
<?php
    if (mysql_num_rows($posts_arr)>0) {
        while ($sql_array = mysql_fetch_object($posts_arr)) {
            if ($sql_array->post_ext){
                $File = new ImageFile();
                $File->Init($sql_array->post_id ,'forum',$sql_array->post_ext);
                $image = $File->getInfo('small');
                $image_full = $File->getInfo('');
            }
?>            <tr onMouseOver='selected(this)' onMouseOut='deselected(this)' bgcolor='#DCE8FC'>
                    <td height='30' align='center' valign='middle'><a href='<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$sql_array->poster_id?>'><?=$sql_array->mem_username?></a></td>
                    <td height='30' valign='middle'>
                        <?=$sql_array->post_text?>
<?if ($sql_array->post_ext) {?>                <div style="float:left">
                        <a rel='lightbox' href="<?=$CONST_LINK_ROOT?>/<?=$image_full->Path?>"><img src="<?=$CONST_LINK_ROOT?><?=$image->Path?>" width="<?=$image->w?>" border=0></a>
                    </div>
<?}?>


                    </td>
                    <td height='30' align='center' valign='middle'><?=$sql_array->post_time?></td>
<?
            if ($sql_array->post_approved==1 ) {
                print(" <td height='30' align='center' valign='middle'>Approved</td>");
            } elseif ($sql_array->post_approved==2 ) {
                print(" <td height='30' align='center' valign='middle'>Rejected</td>");
            } elseif ($sql_array->post_approved==0 ) {
                print(" <td height='30' align='center' valign='middle'><a href='$CONST_LINK_ROOT/admin/bbsubtopics_edit.php?mode=approve&stid=$sql_array->subtopic_id&pid=$sql_array->post_id'>".GENERAL_APPROVE."</a></td>");
            }
            print(" <td height='30' align='center' valign='middle'><a href='$CONST_LINK_ROOT/admin/bbsubtopics_edit.php?mode=delete&stid=$sql_array->subtopic_id&pid=$sql_array->post_id'>".GENERAL_DELETE."</a></td>
                  </tr>");
        }
    }
?>
            </table>
          </td>
        </tr>
        <tr>
          <td class="tdfoot" align="center" colspan='5'>
              <input type='button' class='button' value='Back' onClick="document.location='<?= $CONST_LINK_ROOT ?>/admin/adm_bbtopics.php?fid=<?=$cur_topic->forum_id?>';">
          </td>
        </tr>
      </table>
      </form>
    </td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>