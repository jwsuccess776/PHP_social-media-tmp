<?php
include('../db_connect.php');
include('../session_handler.inc');
include($CONST_INCLUDE_ROOT.'/functions.php');
include($CONST_INCLUDE_ROOT.'/error.php');
include_once __INCLUDE_CLASS_PATH."/class.Group.php";

$mode = formGet('mode');
$gID = formGet('gID');
$tID = formGet('tID');
$pID = formGet('pID');

//Group::clearDrafts();

switch ($mode) {
    case 'upload':
        $data = formGet('post');
        $post = new GroupPost($data['id']);
        if ($post->initByArray($data) === null || $post->addImage('attach') === null) {
            error_page(join('<br>', $post->error), 'USER ERROR');
        }
        $post->status = 3;
        $post->save();
        redirect($CONST_GROUPS_LINK_ROOT.'/group_post.php?pID='.$post->id);
        break;
    case 'save':
        $data = formGet('post');
        $post = new GroupPost($data['id']);
        if ($post->initByArray($data) === null) {
            error_page(join('<br>', $post->error), 'USER ERROR');
        }
        $post->status = $post->autoApprove();
        $post->save();
        redirect($CONST_GROUPS_LINK_ROOT.'/topic.php?pID='.$post->id);
        break;

    case 'quote':
        $pID = formGet('pID');
        if ($pID) {
            $quoted = new GroupPost($pID);
            $post = new GroupPost();
            $post->author = $Sess_UserId;
            $post->groupid = $quoted->groupid;
            $post->topic = $quoted->topic ? $quoted->topic : $quoted->id;
            $post->build();
            
            $post->subject = 'Re: '.$quoted->subject;
            $post->text = ">> ".preg_replace("'\n'm", "\n>> ", $quoted->text)."\r\n\r\n";
            break;
        }
    default: 
        if ($tID) { // reply to topic
            $topic = new GroupPost($tID);
            $post = new GroupPost();
            $post->author = $Sess_UserId;
            $post->groupid = $topic->groupid;
            $post->topic = $topic->id;
            $post->build();

            $post->subject = 'Re: '.$topic->subject;
        } elseif ($gID) { // new post
            $post = new GroupPost();
            $post->author = $Sess_UserId;
            $post->groupid = $gID;
            $post->build();
        } elseif ($pID) {
            $post = new GroupPost($pID);
        }
}
if (!$post->id) // post wasn't built for some reason
    redirect($CONST_GROUPS_LINK_ROOT.'/groups.php');

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
    <form action="<?=$CONST_GROUPS_LINK_ROOT?>/group_post.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="mode" value="save" id="actionField">
    <input type="hidden" name="post[id]" value="<?=$post->id?>">
    <table width="100%" border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
    <tr><td colspan="2" class="tdhead" >&nbsp;</td></tr>
    <tr class="tdodd">
        <td><?=GROUPS_POST_SUBJECT?></td>
        <td><input type="text" name="post[subject]" value="<?=$post->subject?>" size="50"></td>
    </tr>                                                                      
    <tr class="tdodd">
        <td><?=GROUPS_POST_TEXT?></td>
        <td><textarea name="post[text]" rows="10" cols="50"><?=$post->text?></textarea></td>
    </td>
    <?php if (count($post->images)) { ?>
    <tr class="tdodd">
        <td><?=GROUPS_POST_IMAGES?></td>
        <td><?php foreach ($post->images as $image) { ?>
            <div class="group_post_image">
                <a href="<?=$image->URL?>" target="_blank"><img src="<?=$image->thumbURL?>" <?=$image->thumbHtmlSize?> border=""></a>
            </div>
        <?php } ?></td>
    </tr>
    <?php } ?>
    <input type="hidden" name="MAX_FILE_SIZE" value="1000000">
    <tr class="tdodd">
        <td><?=GROUPS_POST_IMAGE?></td>
        <td><input type="file" name="attach"> <input type="button" value="<?=GROUPS_UPLOAD?>" class="button" onClick="document.getElementById('actionField').value='upload'; this.form.submit()"></td>
    </tr>
    <tr><td colspan="2" align="center" class="tdfoot">
        <input type="button" class="button" value="<?=GENERAL_CANCEL?>" onClick="window.location='<?=$CONST_GROUPS_LINK_ROOT?>/<?= $post->topic ? 'topic.php?tID='.$post->topic : 'group.php?gID='.$post->groupid ?>'"> <input type="submit" value="<?=GROUPS_POST?>" class="button">
    </td></tr>
    </table>
    </form>
    <script language="javascript">document.getElementById('actionField').value='save';</script>
    </td>
  </tr>
</table>

<?=$skin->ShowFooter($area)?>