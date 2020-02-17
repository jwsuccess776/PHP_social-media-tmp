<?php
include('../db_connect.php');
include('../session_handler.inc');
include('../error.php');
include('../functions.php');
include('../message.php');
require_once(__INCLUDE_CLASS_PATH.'/class.GroupCategory.php');
require_once(__INCLUDE_CLASS_PATH.'/class.Group.php');
include('permission.php');

$cID = formGet('cID');
$cIDs = formGet('cIDs');
if (!is_array($cIDs)) $cIDs = array();
$gIDs = formGet('gIDs');
if (!is_array($gIDs)) $gIDs = array();
else {
    $group = new Group($gIDs[0]);
    $cID = $group->category;
}
$currentCategory = new GroupCategory($cID);

$mode = formGet('mode');
switch ($mode) {
    case 'add_category':
        $categoryName = formGet('name');
		if (empty($categoryName)) {
			$error_message=GROUP_NAME_ERROR;
			display_page($error_message,GENERAL_USER_ERROR);
		}
		
		if (!$currentCategory->childExists($name))
            $currentCategory->addChild($name);
        break;
    case 'delete_category':
        foreach ($cIDs as $id) {
            $category = new GroupCategory($id);
            $errors = array();
            if ($category->okToDelete() === null)
                $errors = array_merge($errors, $category->error);
        }
        if (count($errors))
            error_page(join('<br>', $errors),GENERAL_USER_ERROR);
        else
            foreach ($cIDs as $id) {
                $category = new GroupCategory($id);
                $category->delete();
            }
        break;
    case 'delete_group':
        foreach ($gIDs as $id) {
            $group = new Group($id);
            $group->delete();
        }
        break;
    case 'approve':
        foreach ($gIDs as $id) {
            $group = new Group($id);
            $group->status = 1;
            $group->save();
        }
        break;
    case 'suspend':
        foreach ($gIDs as $id) {
            $group = new Group($id);
            $group->status = 2;
            $group->save();
        }
        break;
}
$currentCategory = new GroupCategory($cID);
$categories = $currentCategory->getChilds(false);
$groups = $currentCategory->getGroups($pager, false);
$groupStatusOptions = array(
    0 => ADM_GROUPS_STATUS_OPT_NEW,
    1 => ADM_GROUPS_STATUS_OPT_APPROVED,
    2 => ADM_GROUPS_STATUS_OPT_SUSPENDED
);

$area = 'member';
?>
<?=$skin->ShowHeader($area)?>

<script language="javascript">

function checkAll(formID, fieldName) {
    var fields = document.getElementById(formID).elements;
    for (st = 0; st < fields.length; st++) {
        if (fields[st].type == 'checkbox' && fields[st].name == fieldName + '[]')
            fields[st].checked = true;
    }
}
function uncheckAll(formID, fieldName) {
    var fields = document.getElementById(formID).elements;
    for (st = 0; st < fields.length; st++) {
        if (fields[st].type == 'checkbox' && fields[st].name == fieldName + '[]')
            fields[st].checked = false;
    }
}
</script>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
    <tr>
        <td align="right">
        <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
        </td>
    </tr>
    <tr>
        <td class="pageheader"><?=ADM_GROUPS_SECTION_NAME ?></td>
    </tr>
  <tr>
    <td><? include("admin_menu.inc.php");?></td>
  </tr>
    <tr>
        <td><!-- MAIN CONTENT TABLE -->
            <form action="adm_groups.php" method="post" id="categoriesForm">
            <table width="100%"  border="0" cellspacing="<?=$CONST_SUBTABLE_CELLSPACING?>" cellpadding="<?=$CONST_SUBTABLE_CELLPADDING?>">
                <tr>
                    <td><h3><?=ADM_GROUPS_CATEGORIES?></h3></td>
                </tr>
                <tr>
                    <td><?=ADM_GROUPS_CURRENT_CATEGORY?>: <a href="adm_groups.php?cID=0">Categories Root</a> <?=$currentCategory->getPath("adm_groups.php?cID=")?></td>
                </tr>
                <tr>
                    <td>
                    <table width="100%" border="0" cellpadding="3" style="background-color:#f0f0f0; border:1px solid #ccc"><!-- CATEGORIES TABLE -->
                        <?php
                        if (!count($categories)) { ?>
                        <tr><td colspan="2"><?=ADM_GROUPS_NO_SUBCATEGORIES?></td></tr>
                        <?php
                        } else {
                        foreach ($categories as $category) { ?>
                        <tr>
                            <td width="1%"><input type="checkbox" name="cIDs[]" value="<?=$category->id?>"<?=in_array($category->id, $cIDs) ? ' checked' : ''?>></td>
                            <td><a href="<?='adm_groups.php?cID='.$category->id?>"><?=$category->name?></a></td>
                        </tr>
                        <?php } ?>
                        </table>
                    <table width="100%" border="0" cellpadding="3" ><!-- CATEGORIES TABLE -->
						<tr><td>
                            <div style="float:right"><?=ADM_GROUPS_WITH_SELECTED?>:
                                <select name="mode" onChange="this.form.submit()">
                                    <option value="">- <?=GENERAL_CHOOSE?> -
                                    <option value="delete_category"><?=GENERAL_DELETE?>
                                </select>
                            </div>
                            <a href="javascript:checkAll('categoriesForm', 'cIDs')">Check</a> / <a href="javascript:uncheckAll('categoriesForm', 'cIDs')">Uncheck</a> All
                        </td></tr>
                        <?php } ?>
                    </table>
                    </td>
                </tr>
            </table>
            </form>
            <p><form action="adm_groups.php" method="post" id="addCategoryForm">
				<input type="hidden" name="mode" value="add_category">
				<input type="hidden" name="cID" value="<?=$currentCategory->id?>">
            <table width="100%"  border="0" cellspacing="<?=$CONST_SUBTABLE_CELLSPACING?>" cellpadding="<?=$CONST_SUBTABLE_CELLPADDING?>" style="background-color:#f0f0f0; border:1px solid #ccc">
			<tr><td>
                    <?=ADM_GROUPS_ADD_CATEGORY?>&nbsp;<input type="text" name="name" size="40" class="inputf">&nbsp;<input type="submit" class="button" value="<?=ADM_GROUPS_ADD_CATEGORY?>">
				</td></tr>
				</form></p>
				<p>
            <form action="adm_groups.php" method="post" id="groupsForm">
            <table width="100%"  border="0" cellspacing="<?=$CONST_SUBTABLE_CELLSPACING?>" cellpadding="<?=$CONST_SUBTABLE_CELLPADDING?>">
                <tr>
                    <td><h3><?=ADM_GROUPS_GROUPS?></h3></td>
                </tr>
                <tr>
                    <td>
                    <table width="100%" border="0" cellpadding="3"><!-- CATEGORIES TABLE -->
                        <tr class="tdtoprow">
                            <td width="1%"></td>
                            <td><?=ADM_GROUPS_NAME?></td>
                            <td><?=ADM_GROUPS_DESCRIPTION_SHORT?></td>
                            <td><?=ADM_GROUPS_STATUS?></td>
                        </tr>
                        <?php
                        if (!count($groups)) { ?>
                        <tr><td colspan="3"><?=ADM_GROUPS_NO_GROUPS?></td></tr>
                        <?php
                        } else {
                        foreach ($groups as $group) { ?>
                        <tr>
                            <td><input type="checkbox" name="gIDs[]" value="<?=$group->id?>"<?=in_array($group->id, $gIDs) ? ' checked' : ''?>></td>
                            <td><a href="<?='adm_groups_edit.php?gID='.$group->id?>"><?=$group->name?></a></td>
                            <td><?=$group->description_short?></td>
                            <td><?=$groupStatusOptions[$group->status]?></td>
                        </tr>
                        <?php } ?>
                        <tr><td colspan="4">
                            <div style="float:right"><?=ADM_GROUPS_WITH_SELECTED?>:
                                <select name="mode" onChange="this.form.submit()">
                                    <option value="">- <?=GENERAL_CHOOSE?> -
                                    <option value="delete_group"><?=GENERAL_DELETE?>
                                    <option value="approve"><?=ADM_GROUPS_APPROVE?>
                                    <option value="suspend"><?=ADM_GROUPS_SUSPEND?>
                                </select>
                            </div>
                            <a href="javascript:checkAll('groupsForm', 'gIDs')">Check</a> / <a href="javascript:uncheckAll('groupsForm', 'gIDs')">Uncheck</a> All
                        </td></tr>
                        <?php } ?>
                    </table>
                    </td>
                </tr>
            </table>
            </form></p>
        </td>
    </tr>
</table>

<?=$skin->ShowFooter($area)?>