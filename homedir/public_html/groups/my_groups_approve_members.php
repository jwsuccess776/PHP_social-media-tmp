<?php
include('../db_connect.php');
include($CONST_INCLUDE_ROOT.'/session_handler.inc');
include($CONST_INCLUDE_ROOT.'/error.php');
include($CONST_INCLUDE_ROOT.'/functions.php');
include($CONST_INCLUDE_ROOT.'/message.php');
require_once(__INCLUDE_CLASS_PATH.'/class.GroupCategory.php');
require_once(__INCLUDE_CLASS_PATH.'/class.Group.php');

$gID = formGet('gID');
if (!$gID)
    redirect('my_groups.php');
$group = new Group($gID);
if ($group->id && $group->owner != $Sess_UserId) // not an owner of this group
    redirect('my_groups.php');

$mode = formGet('mode');

if ($mode == 'process') {
    $mID = formGet('mID');
    if (is_array($mID)) {
        foreach ($mID as $id) {
            $action = formGet('m'.$id);
            if ($action == 'approve') {
                $group->setMemberStatus($id, 1);
                $user = new Adverts($id);
                $data = array(
                    'MemberName' => $user->mem_username,
                    'GroupName' => $group->name
                );
                $option_manager =& OptionManager::GetInstance();
                list($type,$message) = getTemplateByName("Approve_Group_Membership",$data,getDefaultLanguage($user->mem_userid));
                send_mail ($user->mem_email, $option_manager->GetValue('mail'), GROUP_MEMBERSHIP_APPROVED_SUBJECT, $message ,$type,"ON");
            } elseif ($action == 'reject') {
                $group->setMemberStatus($id, 2);
                $user = new Adverts($id);
                $data = array(
                    'MemberName' => $user->mem_username,
                    'GroupName' => $group->name
                );
                $option_manager =& OptionManager::GetInstance();
                list($type,$message) = getTemplateByName("Reject_Group_Membership",$data,getDefaultLanguage($user->mem_userid));
                send_mail ($user->mem_email, $option_manager->GetValue('mail'), GROUP_MEMBERSHIP_REJECTED_SUBJECT, $message ,$type,"ON");
            }
        }
    }
}
$members = $group->getMembers($pager, true, 0); // get unauthorized members only

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
    <td class="pageheader"><?=MYGROUPS_MEMBERS_SECTION_NAME ?></td>
    </tr>
    <tr>
      <td>
      <form action="<?=$CONST_GROUPS_LINK_ROOT?>/my_groups_approve_members.php" method="post">
      <input type="hidden" name="gID" value="<?=$group->id?>">
      <input type="hidden" name="mode" value="process">
      <table width="100%" border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
        <tr><td align="right" colspan="4"><?php include "../pager.php"; ?></td></tr>
        <tr class="tdtoprow" align="center">
          <td colspan="2"><?=GENERAL_MEMBER?></td>
          <td width="10%"><?=MYGROUPS_MEMBER_APPROVE?></td>
          <td width="10%"><?=MYGROUPS_MEMBER_REJECT?></td>
        </tr>
        <?php foreach ($members as $member) {
            $member->setImage('small');?>
        <tr align="center" class="tdodd">
            <td valign="top"><table border='0' cellpadding='0' cellspacing='0'><tr>
                <td class='imageframe'>
                    <a href='<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$member->adv_userid?>' target="_blank"><img border='0' src='<?=$CONST_LINK_ROOT?><?=$member->adv_picture->Path?>?<?=time()?>' width=<?=$member->adv_picture->w?>></a>
                </td></tr></table>
                <a href='<?=$const_link_root?>/prgretuser.php?userid=<?=$member->adv_userid?>' target="_blank"><?=$member->adv_username?></a>
            </td>
            <td align="left" valign="top">
                <p><span class='searchage'><?=$member->age?> <?=SEARCH_AGELOCALITY?> <?=$member->full_address?></span>
                <p><?=$member->statustext?>, <?=$member->online?>
                <p><?=$member->adv_comment?>
            </td>
            <td style="background-color:#eeffee">
                <input type="hidden" name="mID[]" value="<?=$member->mem_userid?>">
                <input type="radio" name="m<?=$member->mem_userid?>" value="approve">
            </td>
            <td style="background-color:#ffeeee">
                <input type="radio" name="m<?=$member->mem_userid?>" value="reject">
            </td>
        </tr>
        <?php }
        if (!count($members))
            echo '<tr><td colspan="4" class="tdfoot">'.MYGROUPS_ALL_MEMBERS_APPROVED.'</td></tr>';
        ?>
        <tr>
            <td colspan="4" class="tdfoot" align="center">
                <input type="button" value="<?=BUTTON_BACK?>" onClick="document.location='<?=$CONST_GROUPS_LINK_ROOT?>/group.php?gID=<?=$gID?>'" class="button">
                <input type="submit" value="<?=MYGROUPS_PROCESS?>" class="button">
            </td>
        </tr>
        <tr><td align="right" colspan="4"><?php include "../pager.php"; ?></td></tr>
      </table>
      </td>
    </tr>
  </table>

<?=$skin->ShowFooter($area)?>