<?php
include('../db_connect.php');
if (isset($_SESSION['Sess_UserId'])) include('../session_handler.inc');
include($CONST_INCLUDE_ROOT.'/functions.php');
include($CONST_INCLUDE_ROOT.'/error.php');
include_once __INCLUDE_CLASS_PATH."/class.Group.php";

# retrieve the template
if (isset($_SESSION['Sess_UserId']))
    $area = 'member';
else
    $area = 'guest';

$tID = formGet('tID');
$pID = formGet('pID');

$topic = new GroupPost($tID);

if ($pID) {
    $post = new GroupPost($pID);
    if ($post->topic)
        $topic = new GroupPost($post->topic);
    else
        $topic = $post;
}

if (!$topic->id)
    redirect($CONST_GROUPS_LINK_ROOT.'/groups.php');
$group = new Group($topic->groupid);

$posts = $topic->getPosts($pager);
?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right">
        <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
  </tr>
  <tr><td class="pageheader"><?=GROUPS_TOPIC_TITLE?> <?=$topic->subject?></td></tr>
  <tr><td><b><?=GROUPS_GROUP?> <a href="<?=$CONST_GROUPS_LINK_ROOT?>/group.php?gID=<?=$group->id?>"><?=$group->name?></a></td></tr>
  <tr>
    <td>
    <?php include "../pager.php"; ?>
    <table width="100%" border="0" cellspacing="1" cellpadding="2">
    <tr>
        <td colspan="2" class="tdhead" align="right"><a href="<?=$CONST_GROUPS_LINK_ROOT?>/group_post.php?tID=<?=$topic->id?>" class="forumlinks"><?=GROUPS_REPLY?></a></td>
    </tr>
    <?php
    foreach ($posts as $post) {
        $author = new Adverts($post->author);
        $author->SetImage('small');
    ?>
    <tr>
      <td colspan='2' class='tdtoprow' align='right'>
        <a href="<?=$CONST_GROUPS_LINK_ROOT?>/group_post.php?mode=quote&pID=<?=$post->id?>" class="forumlinks"><?=GROUPS_QUOTE?></a>
      </td>
    </tr>
    <tr onMouseOver='selected(this)' onMouseOut='deselected(this)' bgcolor='#f0f0f0' valign="top">
        <td align="center" width="25%">
            <a href="<?=CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$author->mem_userid?>"><?=$author->mem_username?></a>
            <br><a href="<?=CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$author->mem_userid?>"><img border='0' src='<?=CONST_LINK_ROOT?><?=$author->adv_picture->Path?>?<?=time()?>' width="<?=$author->adv_picture->w?>"></a>
            <br><?=date('m/d/Y', $post->created)?><br><?=date('H:i', $post->created)?>
        </td>
        <td>
            <div class="group_post_subject"><?=$post->subject?></div>
            <div class="group_post_text"><?=nl2br($post->text)?></div>
            <?php if (count($post->images)) { ?>
                <fieldset class="group_post_images"><legend><?=GROUPS_POST_IMAGES?></legend>
                <?php foreach ($post->images as $image) { ?>
                <div class="group_post_image">
                    <a href="<?=$image->URL?>" target="_blank"><img src="<?=$image->thumbURL?>" <?=$image->thumbHtmlSize?> border=""></a>
                </div>
                <?php } ?>
                </fieldset>
            <?php } ?>
        </td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <?php } ?>
    <tr>
        <td colspan="2" class="tdfoot" align="right"><a href="<?=$CONST_GROUPS_LINK_ROOT?>/group_post.php?tID=<?=$topic->id?>" class="forumlinks"><?=GROUPS_REPLY?></a></td>
    </tr>
    </table>
    <?php include "../pager.php"; ?>
    </td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>

