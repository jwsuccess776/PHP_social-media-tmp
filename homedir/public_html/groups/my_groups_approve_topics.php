<?php
include('../db_connect.php');
include($CONST_INCLUDE_ROOT.'/session_handler.inc');
include($CONST_INCLUDE_ROOT.'/functions.php');
include($CONST_INCLUDE_ROOT.'/error.php');
include_once __INCLUDE_CLASS_PATH."/class.Group.php";

$mode = formGet('mode');
$gID = formGet('gID');
$group = new Group($gID);
if ($group->owner != $Sess_UserId)
    redirect($CONST_GROUPS_LINK_ROOT.'/my_groups.php');

switch ($mode) {
    case 'process':
        $tID = formGet('tID');
        $topic = new GroupPost($tID);
        $data = formGet('topic');
        if ($topic->initByArray($data) === null) {
            error_page(join('<br>', $post->error), 'USER ERROR');
        }
        if ($topic->status == -1) {
            $topic->delete();
            $user = new Adverts($topic->author);
            $data = array(
                'AuthorName' => $user->mem_username,
                'GroupName' => $group->name,
                'Topic' => $topic->subject,
                'Reason' => formGet('reason')
            );
            $option_manager =& OptionManager::GetInstance();
            list($type,$message) = getTemplateByName("Reject_Group_Topic",$data,getDefaultLanguage($user->mem_userid));
            send_mail ($user->mem_email, $option_manager->GetValue('mail'), GROUP_TOPIC_REJECTED_SUBJECT, $message ,$type,"ON");
        } else
            $topic->save();
        break;
}

$db = & db::getInstance();
$tID = $db->get_var("SELECT id FROM group_posts WHERE groupid = '".Main::_prepareData($gID)."' && topic = 0 && status = 0 LIMIT 1");
$topic = new GroupPost($tID);

$area = 'member';
?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right">
        <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
  </tr>
  <tr><td class="pageheader"><?= $topic->id ? GROUPS_REPLY_TITLE : GROUPS_NEW_TOPIC_TITLE?></td></tr>
  <tr>
    <td>
    <?php if ($tID) { ?>
    <form action="<?=$CONST_GROUPS_LINK_ROOT?>/my_groups_approve_topics.php" method="post">
    <input type="hidden" name="mode" value="process" id="actionField">
    <input type="hidden" name="gID" value="<?=$topic->groupid?>">
    <input type="hidden" name="tID" value="<?=$topic->id?>">
    <table width="100%" border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
    <tr><td colspan="2" class="tdhead" >&nbsp;</td></tr>
    <tr class="tdodd">
        <td><?=GROUPS_POST_SUBJECT?></td>
        <td><input type="text" name="topic[subject]" value="<?=$topic->subject?>" size="50"></td>
    </tr>
    <tr class="tdodd">
        <td><?=GROUPS_POST_TEXT?></td>
        <td><textarea name="topic[text]" rows="10" cols="50"><?=$topic->text?></textarea></td>
    </td>
    <?php if (count($topic->images)) { ?>
    <tr class="tdodd">
        <td><?=GROUPS_POST_IMAGES?></td>
        <td><?php foreach ($topic->images as $image) { ?>
            <div class="group_post_image">
                <a href="<?=$image->URL?>" target="_blank"><img src="<?=$image->thumbURL?>" <?=$image->thumbHtmlSize?> border=""></a>
            </div>
        <?php } ?></td>
    </tr>
    <?php } ?>
    <tr class="tdodd" align="center">
        <td colspan="2">
            <input type="radio" name="topic[status]" value="1"> Approve &nbsp;
            <input type="radio" name="topic[status]" value="-1"> Reject &nbsp; <br>
            Reason <input name='reason' type='text' class="inputl" size='30'>
        </td>
    </tr>
    <tr><td colspan="2" align="center" class="tdfoot">
        <input type="submit" value="<?=GENERAL_PROCESS?>" class="button">
    </td></tr>
    </table>
    </form>
    <?php } else echo MYGROUPS_ALL_TOPICS_APPROVED; ?>
    </td>
  </tr>
</table>

<?=$skin->ShowFooter($area)?>