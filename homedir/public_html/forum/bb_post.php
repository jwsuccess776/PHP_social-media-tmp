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
include_once __INCLUDE_CLASS_PATH."/class.Emoticons.php";

$emotions = new Emoticons();

$post_added = false;
$mode=formGet('mode');
$post_id=$db->escape(formGet('pid'));
$subtopic_id= $db->escape(formGet('stid'));
$forum_id = $db->escape(formGet('fid'));

# retrieve the template
$area = 'member';

switch ($mode) {
    case 'add':
        $txtText = $db->escape(nl2br(strip_tags(formGet('txtText'))));
        $txtTitle = $db->escape(formGet('txtTitle'));
        $approved = $option_manager->GetValue('authorisead_forum');
        if ($_FILES['image']['error'] == 0){
            include_once __INCLUDE_CLASS_PATH."/class.ImageFile.php";
            $File = new ImageFile();
            $ext = $File->getExtFromPath($_FILES['image']['name']);
            $result = $File->setFile($_FILES['image']['tmp_name'],$ext);
            if ($result === null) error_page(join("<br>",$File->error),GENERAL_USER_ERROR);
        }
        if (!$subtopic_id ) {
            $db->query("INSERT INTO bb_subtopics
                        (subtopic_title, subtopic_time, subtopic_status, forum_id)
                            VALUES
                        ('$txtTitle', NOW(), '$approved','$forum_id')");
            $subtopic_id = $db->insert_id;
            $subtopic_added = true;
        }
        $query = "INSERT INTO bb_posts
                    (subtopic_id, poster_id, post_time, post_text,post_approved)
                        VALUES
                    ($subtopic_id,'$Sess_UserId', NOW(), '$txtText','$approved')";
        $db->query($query);
        $id = $db->insert_id;
        if ($_FILES['image']['error'] == 0){
            $File->Init($id,'forum');
            $result = $File->Save();
            if ($result === null) error_page(join("<br>",$File->error),GENERAL_USER_ERROR);
            $db->query("UPDATE bb_posts SET post_ext = '$ext' WHERE post_id = $id");
        }

        $post_added = true;
        $_SESSION['stid'] = $subtopic_id;

        if ($subtopic_added) {
            if ($approved != 1) {
                unset($subtopic_id);
                unset($_SESSION['stid']);
            }
        }

        if ($approved == 1) {
            Header("Location: $CONST_LINK_ROOT/forum/bb_subtopic.php");
            exit;
        }
        break;
    case 'edit':
        $posts_arr = $db->get_row("SELECT * FROM bb_posts WHERE post_id=$post_id");
        break;
    case 'save':
        $txtText = $db->escape(nl2br(strip_tags(formGet('txtText'))));
        $approved = $option_manager->GetValue('authorisead_forum');
        
        include_once __INCLUDE_CLASS_PATH."/class.ImageFile.php";
        $File = new ImageFile();
        $File->Init($post_id,'forum');

        if ($_FILES['image']['error'] == 0){
            $ext = $File->getExtFromPath($_FILES['image']['name']);
            $result = $File->setFile($_FILES['image']['tmp_name'], $ext);
            if ($result === null) error_page(join("<br>",$File->error),GENERAL_USER_ERROR);
            $result = $File->Save();
            if ($result === null) error_page(join("<br>",$File->error),GENERAL_USER_ERROR);
            $db->query("UPDATE bb_posts SET post_ext = '$ext' WHERE post_id = $post_id");
        } else {
            $File->delete();
            $ext = '';
        }

        $query = "UPDATE bb_posts SET
                    poster_id='$Sess_UserId',
                    post_time=NOW(),
                    post_text='$txtText',
                    post_ext='$ext',
                    post_approved='$approved'
                WHERE post_id=$post_id";
        $db->query($query);
        $post_added = true;
        if ($approved == 1) Header("Location: $CONST_LINK_ROOT/forum/bb_subtopic.php");
        break;
}
if ($subtopic_id > 0){
    $cur_topic = $db->get_row(" SELECT *, f.forum_id AS forum_id
                                FROM bb_forum f
                                LEFT JOIN  bb_subtopics st
                                    ON (f.forum_id = st.forum_id)
                                WHERE subtopic_id = '$subtopic_id'");
    $back_script = "bb_subtopic.php";
} else {
    $cur_topic = $db->get_row(" SELECT *
                                FROM bb_forum f
                                WHERE forum_id = '$forum_id'");
    $back_script = "bb.php";
}
$_SESSION['pid'] = $post_id;
$title = BB_TOPIC_POST_ADD_SUB;
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
    <td >
    <a href="<?php echo $CONST_LINK_ROOT ?>/forum/forums.php" class="forumlinkshd"><?=BB_FORUMS_SECTION_NAME?></a> &raquo;
    <a href="<?php echo $CONST_LINK_ROOT ?>/forum/bb.php?fid=<?=$cur_topic->forum_id?>" class="forumlinkshd"><?=$cur_topic->forum_title?></a> &raquo;
<?if ($subtopic_id >0){
    $title= BB_TOPIC_POST_ADD_NEW;
?>
    <a href="<?php echo $CONST_LINK_ROOT ?>/forum/bb_subtopic.php?stid=<?php echo $cur_topic->subtopic_id ?>" class="forumlinkshd"><?=$cur_topic->subtopic_title?></a> &raquo;
<?}?>
    <b><?=$action=($mode=='edit')?BB_TOPIC_POST_EDIT:$title;?></b>
    </td>
  </tr>
<?php
if ($post_added) {
    print("<tr><td>".BB_TOPIC_POST_ALERT."</td></tr>");
} ?>
  <tr>
    <td>
      <form method="post"  enctype="multipart/form-data" action="<?php echo $CONST_LINK_ROOT?>/forum/bb_post.php" name="FrmTList">
        <input type="hidden" name="mode">
        <input type="hidden" name="fid" value='<?=$forum_id?>'>
        <input type="hidden" name="stid" value='<?=$subtopic_id?>'>
        <input type="hidden" name="pid" value='<?=$post_id?>'>
        <input type=hidden name="MAX_FILE_SIZE" value="<?=$option_manager->GetValue('maxpicsize')?>">

      <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
        <tr>
          <td colspan="4" class="tdhead"><?=BB_TOPIC_POST_ADD_NEW?></td>
        </tr>
        <tr>
          <td class="tdeven"><b>Title:</b></td>
          <td colspan=3 class="tdeven">
         <? if ($subtopic_id > 0) {?>
            <? echo $cur_topic->subtopic_title?>
         <?} else {?>
            <input type=text name=txtTitle>
         <?}?>
          </td>
        </tr>
        <tr>
          <td width='60' rowspan="2" class="tdeven">
            <b><?=BB_TOPIC_POST_TEXT?>:</b>
          </td>
          <td rowspan="2" class="tdeven">
            <textarea id="post_text" name="txtText" rows="6" cols="50"><?php echo strip_tags($posts_arr->post_text) ?></textarea>
          </td>
          <td width="7" rowspan="2" class="tdeven">&nbsp;</td>
          <td class="tdeven"><em><?php echo MYBLOGS_EMOTICONS ?></em></td>
        </tr>
        <tr class="tdeven">
            <td ><?echo $emotions->DisplayIcons('post_text')?></td>
        </tr>
        <tr>
          <td width='60' class="tdeven">
            <b><?=SD_ADM_STORIES_IMAGE?>:</b>
          </td>
          <td colspan=3 class="tdeven">
            <input name="image" type="file" class="input">
          </td>
        </tr>
        <tr>
          <td colspan="4" class="tdfoot" align="center">
            <input type='submit' class='button' value='<?=BUTTON_SUBMIT?>' <?php if ($mode=='edit') print("onClick=\"FrmTList.mode.value='save'\""); else  print("onClick=\"FrmTList.mode.value='add'\""); ?>>
            <input type='button' class='button' value='<?=BUTTON_BACK?>' onClick="document.location='<?= $CONST_LINK_ROOT ?>/forum/<?=$back_script?>?';">
          </td>
        </tr>
      </table>
      </form>
    </td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>