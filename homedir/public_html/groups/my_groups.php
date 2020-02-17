<?php
include('../db_connect.php');
include($CONST_INCLUDE_ROOT.'/session_handler.inc');
include($CONST_INCLUDE_ROOT.'/error.php');
include($CONST_INCLUDE_ROOT.'/message.php');
include_once __INCLUDE_CLASS_PATH."/class.Group.php";

$mode = formGet('mode');
switch ($mode) {
    case 'join':
        $gID = formGet('gID');
        if ($group = new Group($gID)) {
            $result = $group->addMember($Sess_UserId);
            switch ($result) {
                case 0: // pending
                    $title = GROUPS_JOIN_PENDING_TITLE;
                    $message = GROUPS_JOIN_PENDING_TEXT;
                    break;
                case 1: // joined
                    $title = GROUPS_JOIN_SUCCESS_TITLE;
                    $message = GROUPS_JOIN_SUCCESS_TEXT;
                    break;
                case 2: // rejected or baned
                    $title = GROUPS_JOIN_FAIL_TITLE;
                    $message = GROUPS_JOIN_FAIL_TEXT;
                    break;
            }
            $message = sprintf($message, $group->name, $CONST_GROUPS_LINK_ROOT.'/'.$group->url_name);
            display_page($message, $title);
        } else
            redirect($CONST_GROUPS_LINK_ROOT.'/groups.php');
    case 'leave':
        $gID = formGet('gID');
        if ($group = new Group($gID)) {
            $group->removeMember($Sess_UserId);
            display_page(sprintf(GROUPS_LEAVE_TEXT, $group->name, $CONST_GROUPS_LINK_ROOT."/groups.php"), GROUPS_LEAVE_TITLE);
        } else
            redirect($CONST_GROUPS_LINK_ROOT.'/groups.php');
}

$groups = Group::findByMember($Sess_UserId);

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
    <td class="pageheader"><?php echo MYGROUPS_SECTION_NAME ?></td>
    </tr>
    <tr>
      <td>
      <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
        <?php
        if (count($groups))
            foreach ($groups as $group) { ?>
            <tr>
                <td colspan="2" class="resulthead"><a href="<?=$CONST_GROUPS_LINK_ROOT?>/group.php?gID=<?=$group->id?>"><b><?=$group->name?></b></a> <?php if ($group->status != 1) { ?><?=GROUPS_PENDING?><?php } ?></td>
            </tr>
            <tr valign="top">
                <td rowspan="2" class="image" width="10%"><table border="0"><td class="imageframe">
                    <a href="<?=$CONST_GROUPS_LINK_ROOT?>/group.php?gID=<?=$group->id?>"><img src="<?=$group->image->URL?>" <?=$group->image->htmlSize?> border="0"></a>
                </td></table></td>
                <td class="resultbody">
                    <div class="group_description"><?=$group->description_short?></div>
                    <div class="group_location"><em><?=GROUPS_LOCATION?>:</em> <?=$group->getLocationString()?></div>
                    <div class="group_members"><em><?=GENERAL_MEMBER?>:</em> <?=$group->getMembersCount() + 1?></div>
                </td>
            </tr>
            <tr valign="bottom">
                <td class="resultbody" align="right">
                <?php if ($group->owner == $Sess_UserId) { ?>
                    <a href="<?=$CONST_GROUPS_LINK_ROOT?>/my_groups_edit.php?gID=<?=$group->id?>"><?=GROUPS_EDIT?></a> |
                <?php } elseif ($group->isMember($Sess_UserId)) { ?>
                    <a href="<?=$CONST_GROUPS_LINK_ROOT?>/groups.php?mode=leave&gID=<?=$group->id?>"><?=GROUPS_LEAVE?></a> |
                <?php } else { ?>
                    <a href="<?=$CONST_GROUPS_LINK_ROOT?>/groups.php?mode=join&gID=<?=$group->id?>"><?=GROUPS_JOIN?></a> |
                <?php } ?>
                <?php if ($group->isMember($Sess_UserId) || $group->is_public) { ?>
                <a href="<?=$CONST_GROUPS_LINK_ROOT?>/group.php?gID=<?=$group->id?>"><?=GROUPS_VIEW?></a>
                <?php } else { ?>
                    <?=GROUPS_PRIVATE?>
                <?php } ?>
                </td>
            </tr>
            <tr >
                <td colspan="2" class="resultfoot">&nbsp;</td>
            </tr>
        <?php }
        else echo GROUPS_NO_GROUPS; ?>
      </table>
      </td>
    </tr>
  </table>

<?=$skin->ShowFooter($area)?>