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

$gID = formGet('gID');
if (!$gID || !($group = new Group($gID))) {
    if ('name' == formGet('mode')) {
        $info = parse_url(CONST_LINK_ROOT);
        preg_match("'^".$info['path']."/groups/(.*)$'", $_SERVER['REQUEST_URI'], $matches);
        $name = $matches[1];
        if (strpos($name, '?'))
            $name = substr($name, 0, strpos($name, '?'));
        if (strpos($name, '/'))
            $name = substr($name, 0, strpos($name, '/'));
        if (!($group = Group::findByName($name)))
            redirect($CONST_GROUPS_LINK_ROOT.'/groups.php');
    } else
        redirect($CONST_GROUPS_LINK_ROOT.'/groups.php');
}
//$group = new Group($gID);
$topics = $group->getTopics($pager);
$members = $group->getSomeMembers();
?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right">
        <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
  </tr>
  <tr><td class="pageheader"><?=$group->name?></td></tr>
  <tr><td><a href="<?=$CONST_GROUPS_LINK_ROOT?>/<?=$group->url_name?>/"><?=$CONST_GROUPS_LINK_ROOT?>/<?=$group->url_name?>/</a></td></tr>
  <tr>
    <td>
    <table width="100%" border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
    <tr>
        <td><div style="float:right; padding:0 0 1em 1em">
            <?php 
            if ($Sess_UserId) {
                if ($group->owner == $Sess_UserId) {
                    if ($group->getMembersCount(true, 0))
                        echo '<input type="button" class="button" value="'.MYGROUPS_APPROVE_MEMBERS.'" onClick="window.location=\''.$CONST_GROUPS_LINK_ROOT.'/my_groups_approve_members.php?gID='.$group->id.'\'"><p>';
                    if ($group->getTopicsCount(true, 0))
                        echo '<input type="button" class="button" value="'.MYGROUPS_APPROVE_TOPICS.'" onClick="window.location=\''.$CONST_GROUPS_LINK_ROOT.'/my_groups_approve_topics.php?gID='.$group->id.'\'"><p>';
                    echo '<input type="button" class="button" value="'.GROUPS_EDIT.'" onClick="window.location=\''.$CONST_GROUPS_LINK_ROOT.'/my_groups_edit.php?gID='.$group->id.'\'"><p>';
                }
                if ($group->isMember($Sess_UserId))
                    echo '<input type="button" class="button" value="'.GROUPS_START_TOPIC.'" onClick="window.location=\''.$CONST_GROUPS_LINK_ROOT.'/group_post.php?gID='.$group->id.'\'"><p>';

                if ($group->isMember($Sess_UserId) && $group->owner != $Sess_UserId)
                    echo '<input type="button" class="button" value="'.GROUPS_LEAVE.'" onClick="window.location=\''.$CONST_GROUPS_LINK_ROOT.'/my_groups.php?mode=leave&gID='.$group->id.'\'"><p>';

                if ($group->owner != $Sess_UserId && !$group->isMember($Sess_UserId))
                    echo '<input type="button" class="button" value="'.GROUPS_JOIN.'" onClick="window.location=\''.$CONST_GROUPS_LINK_ROOT.'/my_groups.php?mode=join&gID='.$group->id.'\'"><p>';
            }
            ?>
            </div>
            <?=$group->description?>
        </td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr> 
      <td class="tdhead"><?=GROUPS_MEMBERS ?></td>
    </tr>
    <tr> 
      <td class="td2">
        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
          <tr><td>
          <?php 
            foreach ($members as $st => $member) {
            $member->SetImage('small'); ?>
            <div style="float:left; width:20%; text-align:center;">
              <table border="0" cellspacing="0" cellpadding="0" align="center">
                <tr><td class="imageframe"><a href="<?=CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$member->mem_userid?>"><img border='0' src='<?=CONST_LINK_ROOT?><?=$member->adv_picture->Path?>?<?=time()?>' width="<?=$member->adv_picture->w?>"></a></td></tr>
              </table>
              <a href="<?=CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$member->mem_userid?>"><?=$member->mem_username?></a>
            </div>
          <?php } ?>
          </td></tr>
          <?php
            if ($group->getMembersCount() + 1 > count($members)) { ?>
          <tr><td align="right"><input type="button" value="<?=GROUPS_VIEW_ALL?>" class="button" onClick="window.location='group_members.php?gID=<?=$group->id?>'"></td></tr>
          <?php } ?>
        </table>
      </td>
    </tr>
    <tr><td>&nbsp;</td></tr>
  </table>
  <table width="100%" border="0" cellspacing="1" cellpadding="2">
    <tr> 
      <td class="tdhead" colspan="5"><?=GROUPS_TOPICS ?></td>
    </tr>
    <?php if ($group->is_public || $group->isMember($Sess_UserId)) { ?>
    <tr class="tdtoprow" align="center">
      <td colspan="2" align="left"><?=GROUPS_TOPIC_NAME?></td>
      <td><?=GROUPS_TOPIC_AUTHOR?></td>
      <td><?=GROUPS_TOPIC_LAST_POST?></td>
      <td><?=GROUPS_TOPIC_POSTS?></td>
    </tr>
    <?php foreach ($topics as $topic) { ?>
    <tr align="center" onMouseOver="selected(this)" onMouseOut="deselected(this)" bgcolor="#f0f0f0">
    <td width="40" align="center"><img src="<?=$CONST_IMAGE_ROOT?>folder_big.gif"></td>
        <td align="left"><a href="topic.php?tID=<?=$topic->id?>"><?=$topic->subject?></a></td>
        <?php
            $author = new Adverts();
            $author->InitById($topic->author);

        ?>
        <td><?=date('d-M-Y H:i', $topic->created)?><br><a href="<?=CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$author->mem_userid?>"><?=$author->mem_username?></a></td>
        <?php 
            $last_post = $topic->getLastPost();
            $author->InitById($last_post->author);
        ?>
        <td><?=date('d-M-Y H:i', $last_post->created)?><br><a href="<?=CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$author->mem_userid?>"><?=$author->mem_username?></a></td>
        <td><?=$topic->getPostsCount()?></td>
    </tr>
    <?php } ?>
    <tr><td align="right" colspan="5"><?php include "../pager.php"; ?></td></tr>
    <?php } else { ?>
    <tr><td colspan="4"><b><?=GROUPS_TOPICS_NOT_MEMBER?></b></td></tr>
    <?php } ?>
    </table>
    </td>
  </tr>

</table>
<?=$skin->ShowFooter($area)?>

