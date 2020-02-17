<?php
include('../db_connect.php');
if (isset($_SESSION['Sess_UserId'])) include('../session_handler.inc');
include($CONST_INCLUDE_ROOT.'/functions.php');
include($CONST_INCLUDE_ROOT.'/error.php');
include_once __INCLUDE_CLASS_PATH."/class.Group.php";
include_once __INCLUDE_CLASS_PATH."/class.GroupCategory.php";

# retrieve the template
if (isset($_SESSION['Sess_UserId']))
    $area = 'member';
else
    $area = 'guest';
$cID = formGet('cID');
$currentCategory = new GroupCategory($cID);
$categories = $currentCategory->getChilds();
$groups = $currentCategory->getGroups($pager);
?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
    <tr>
        <td align="right"><?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?></td>
    </tr>
    <tr>
        <td class="pageheader"><?php echo GROUPS_SECTION_NAME ?></td>
    </tr>
    <tr>
        <td><fieldset><legend><b><?=GROUPS_CATEGORIES?></b></legend>
        <?=GROUPS_CURRENT_CATEGORY?>: <a href="<?=$CONST_GROUPS_LINK_ROOT?>/groups.php"><?=GROUPS_CATEGORY_GENERAL?></a> <?=$currentCategory->getPath()?>
        <table width="100%" border="0">
        <tr valign="top">
            <td width="50%"><ul style="margin:0"><?php
            reset($categories);
            for($st = 0; $st < ceil(count($categories)/2); $st++)
                echo '<li><a href="'.$CONST_GROUPS_LINK_ROOT.'/groups.php?cID='.$categories[$st]->id.'">'.$categories[$st]->name.'</a>';
            ?></ul></td>
            <td width="50%"><ul style="margin:0"><?php
            for($st = ceil(count($categories)/2); $st < count($categories) ; $st++)
                echo '<li><a href="'.$CONST_GROUPS_LINK_ROOT.'/groups.php?cID='.$categories[$st]->id.'">'.$categories[$st]->name.'</a>';
            ?></ul></td>
        </tr>
        <?php if (!empty($Sess_UserId) && $cID) { ?>
        <tr valign="bottom">
            <td align="right" colspan="2"><input type="button" value="<?=GROUPS_ADD?>" onClick="window.location='<?=$CONST_GROUPS_LINK_ROOT?>/my_groups_edit.php?cID=<?=$currentCategory->id?>'" class="button"></td>
        </tr>
        <?php } ?>
        </table>
        </fieldset>
        <p>
        <?php include "../pager.php"; ?>
        <table width="100%" border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
        <?php foreach ($groups as $group) { ?>
            <tr>
                <td colspan="2" class="resulthead"><a href="group.php?gID=<?=$group->id?>"><b><?=$group->name?></b></a></td>
            </tr>
            <tr valign="top">
                <td rowspan="2" class="image" width="10%"><table border="0"><td class="imageframe">
                    <a href="group.php?gID=<?=$group->id?>"><img src="<?=$group->image->URL?>" <?=$group->image->htmlSize?> border="0"></a>
                </td></table></td>
                <td class="resultbody">
                    <div class="group_description"><?=$group->description_short?></div>
                    <div class="group_location"><em><?=GROUPS_LOCATION?>:</em> <?=$group->getLocationString()?></div>
                    <div class="group_members"><em><?=GENERAL_MEMBER?>:</em> <?=$group->getMembersCount() + 1?></div>
                </td>
            </tr>
            <tr valign="bottom">
                <td class="resultbody" align="right">
                    <?php 
                    if ($Sess_UserId) {
                        if ($group->owner == $Sess_UserId)
                            echo '<a href="'.$CONST_GROUPS_LINK_ROOT.'/my_groups_edit.php?mode=edit&gID='.$group->id.'">'.GROUPS_EDIT.'</a> | ';
                        elseif ($group->isMember($Sess_UserId))
                            echo '<a href="'.$CONST_GROUPS_LINK_ROOT.'/my_groups.php?mode=leave&gID='.$group->id.'">'.GROUPS_LEAVE.'</a> | ';
                        else 
                            echo '<a href="'.$CONST_GROUPS_LINK_ROOT.'/my_groups.php?mode=join&gID='.$group->id.'">'.GROUPS_JOIN.'</a> | ';
                    } ?>
                    <a href="group.php?gID=<?=$group->id?>"><?=GROUPS_VIEW?></a></td>
            </tr>
            <tr >
                <td colspan="2" class="resultfoot">&nbsp;</td>
            </tr>
        <?php } ?>
        </table>
        <?php include "../pager.php"; ?>
        </td>
    </tr>
</table>
<?=$skin->ShowFooter($area)?>
