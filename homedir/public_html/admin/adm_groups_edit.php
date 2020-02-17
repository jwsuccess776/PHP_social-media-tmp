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
$gID = formGet('gID');

$group = new Group($gID);
if ($cID) $group->category = $cID;

$mode = formGet('mode');
switch ($mode) {
    case 'save':
        $data = formGet('data');
        setcookie("lstCountry", $data['country']);
        setcookie("lstState", $data['state']);
        setcookie("lstCity", $data['city']);
        if ($group->initByArray($data) === null || $group->uploadImage('groupImage') === null) {
            error_page(join('<br>', $group->error), 'USER ERROR');
        }
        if (!$group->owner)
            $group->owner = $Sess_UserId;
        $group->save();
        if (count($group->error)) {
            error_page(join('<br>', $group->error), 'USER ERROR');
        } else {
            setcookie("lstCountry", "");
            setcookie("lstState", "");
            setcookie("lstCity", "");
            redirect('adm_groups.php?cID='.$group->category);
        }
        break;
}
$option_manager = &OptionManager::GetInstance();

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
        <td class="pageheader"><?=ADM_GROUPS_ADD_SECTION_NAME ?></td>
    </tr>
  <tr>
    <td><? include("admin_menu.inc.php");?></td>
  </tr>
    <tr>
        <td><!-- MAIN CONTENT TABLE -->
            <form action="adm_groups_edit.php" method="post" name="groupForm" id="groupForm" enctype="multipart/form-data">
            <input type="hidden" name="mode" value="save">
            <input type="hidden" name="data[id]" value="<?=$group->id?>">
            <input type="hidden" name="data[category]" value="<?=$group->category?>">
            <table width="100%"  border="0" cellspacing="<?=$CONST_SUBTABLE_CELLSPACING?>" cellpadding="<?=$CONST_SUBTABLE_CELLPADDING?>">
                <tr><td class="tdhead" colspan="2">&nbsp;</td>
                <tr class="tdodd">
                    <td><?=ADM_GROUPS_NAME?></td>
                    <td><input type="text" name="data[name]" value="<?=$group->name?>"></td>
                </tr>
                <tr class="tdodd">
                    <td><?=ADM_GROUPS_DESCRIPTION_SHORT?></td>
                    <td><textarea name="data[description_short]" rows="5" cols="30"><?=$group->description_short?></textarea></td>
                </tr>
          <tr class="tdodd">
                    <td><?=ADM_GROUPS_DESCRIPTION?></td>
                    <td><textarea name="data[description]" rows="10" cols="30"><?=$group->description?></textarea></td>
                </tr>
                <tr class="tdodd">
                    <td><?=ADM_GROUPS_URL?></td>
                    <td><input type="text" name="data[url_name]" value="<?=$group->url_name?>"></td>
                </tr>
                <input type="hidden" name="MAX_FILE_SIZE" value="100000">
                <tr class="tdodd">
                    <td><?=ADM_GROUP_IMAGE?></td>
                    <td><?php
                        if ($group->image) {
                            echo '<a href="'.$group->image->URL.'">'.ADM_GROUP_IMAGE_VIEW.'</a> ';
                        }
                    ?><input type="file" name="groupImage"> <?=sprintf(ADM_GROUPS_IMAGE_COMMENT, $option_manager->getValue('groups_image_width'), $option_manager->getValue('groups_image_height'))?></td>
                </tr>
          <? if ($GEOGRAPHY_JAVASCRIPT) {
                 if ($GEOGRAPHY_AJAX) { ?>
<script src="<?=CONST_LINK_ROOT?>/moo.ajax/prototype.lite.js"></script>
<script src="<?=CONST_LINK_ROOT?>/moo.ajax/moo.ajax.js"></script>
<script src="<?=CONST_LINK_ROOT?>/ajax_lib.js.php"></script>
          <tr class="tdodd" >
            <td  align="left"><?php echo GENERAL_COUNTRY?></td>
            <td > <select class="input" name="data[country]" id="lstCountry" size="1"  tabindex='13' onchange="sendStateRequest(this.options[this.selectedIndex].value);sendCityRequest(this.options[this.selectedIndex].value,0); return false;">
                <option value="0">-- <?php echo GENERAL_CHOOSE?> --</option>
                <option value=""></option>
<?php
include_once __INCLUDE_CLASS_PATH."/class.Geography.php";
$CountriesList = Geography::getCountriesList();
foreach ($CountriesList as $countryrow)
{
    $selected = ($group->country == $countryrow->gcn_countryid)?' selected':'';
    echo '<option value='.$countryrow->gcn_countryid.$selected.'>'.htmlspecialchars($countryrow->gcn_name).'</option>';
}
?>
                    </select> </td>
                </tr>
                <tr class="tdodd">
            <td  align="left"><?php echo GENERAL_STATE?></td>
            <td>
<?php
$result = "";
if ($group->country) {
    $StatesList = Geography::getStatesList($group->country);
    foreach ($StatesList as $staterow)
    {
        $selected = ($group->state == $staterow->gst_stateid)?' selected':'';
        $result .= "<OPTION value=".$staterow->gst_stateid.$selected.">".htmlspecialchars($staterow->gst_name)."</OPTION>";
    }
}
$disabled = ($result != "")?"":" disabled";
?>
            <select <?=$disabled?> class="input" name="data[state]" id="lstState" size="1"  tabindex='14' onchange="sendCityRequest(document.getElementById('lstCountry').value,this.value); return false;">
                <option value="0">- <?php echo GENERAL_CHOOSE?> -</option>
                <?=$result?>
            </select></td>
                </tr>
                <tr class="tdodd" >
            <td  align="left"><?php echo GENERAL_CITY?></td>
            <td colspan="3">
<?php
$result = "";
if ($group->country || $group->state) {
    $CitiesList = Geography::getCitiesList($group->country,$group->state);
    foreach ($CitiesList as $cityrow)
    {
        $selected = ($group->city == $cityrow->gct_cityid)?' selected':'';
        $result .= "<OPTION value=".$cityrow->gct_cityid.$selected.">".htmlspecialchars($cityrow->gct_name)."</OPTION>";
    }
}
$disabled = ($result != "")?"":" disabled";
?>
            <select <?=$disabled?> class="input" name="data[city]" id="lstCity" size="1" tabindex='15'>
                <option value="0">- <?php echo GENERAL_CHOOSE?> -</option>
                <?=$result?>
            </select></td>
          </tr>
<? if (empty($group->id)) { ?>
<script language="javascript">
country = getCookie('lstCountry');
state = getCookie('lstState');
city = getCookie('lstCity');
function initialize(SelectedCountry, SelectedState, SelectedCity)
{
    var lstCountry = new getObj('lstCountry').obj;
    if(SelectedCountry)
    {
        for(iOption = 0; iOption < lstCountry.options.length; iOption++)
            if(lstCountry.options[iOption].value == SelectedCountry) {
                lstCountry.options[iOption].selected = true;
            }
        sendStateRequest(SelectedCountry,SelectedState);
        sendCityRequest(SelectedCountry,SelectedState,SelectedCity);
    }
}
initialize(country, state, city);
</script>
<? } ?>
              <? } else { ?>
                <script language="javascript" src="../geography.js"></script>
                <tr class="tdodd">
                  <td><?php echo GENERAL_COUNTRY?></td>
                  <td><select class="input" name="data[country]" id="lstCountry" size="1"  tabindex='13' onchange="onCountryListChange('groupForm', 'lstCountry', 'lstState', 'lstCity');">
                      <option value="0" selected>- <?php echo GENERAL_CHOOSE?> -</option>
                      <option value=""></option>
                    </select> </td>
                </tr>
                <tr class="tdodd">
                  <td><?php echo GENERAL_STATE?></td>
                  <td><select class="input" name="data[state]" id="lstState" size="1" onchange="onStateListChange('groupForm', 'lstCountry', 'lstState', 'lstCity');">
                      <option value="0" selected></option>
                    </select> </td>
                </tr>
                <tr class="tdodd" >
                  <td><?php echo GENERAL_CITY?></td>
                  <td><select class="input" name="data[city]" id="lstCity" size="1" onchange="onCityListChange('groupForm', 'lstCity');">
                      <option value="0" selected></option>
                    </select> </td>
                </tr>
                <script language="javascript">
                initialize('groupForm', 'lstCountry', 'lstState', 'lstCity', new Array('<?=$group->country?>'), new Array('<?=$group->state?>'), new Array('<?=$group->city?>'));
                </script>
              <? } ?>
                <? } else { ?>
                <tr class="tdodd" >
                  <td><?php echo GENERAL_COUNTRY?>/<?php echo GENERAL_STATE?></td>
                  <td><select class="input" name="data[country]" id="lstCountry" size="1" style="width:auto;">
                      <option value="0" selected>- <?php echo GENERAL_CHOOSE?> -</option>
                      <?= country_state_list($group->country);?>
                    </select> </td>
                  <td><?php echo GENERAL_CITY?></td>
                  <td><input type=text class="input" name="data[city]" value="<?=$group->city?>">
                  </td>
                </tr>
                <? } ?>
                <tr class="tdodd">
                    <td><?=ADM_GROUPS_OPEN?></td>
                    <td><select name="data[is_open]">
                        <option value="1"><?=ADM_GROUPS_OPEN_OPT_OPEN?>
                        <option value=""<?=$group->is_open ? '' : 'selected'?>><?=ADM_GROUPS_OPEN_OPT_CLOSED?>
                    </select></td>
                </tr>
                <tr class="tdodd">
                    <td><?=ADM_GROUPS_PUBLIC?></td>
                    <td><select name="data[is_public]">
                        <option value="1"><?=ADM_GROUPS_PUBLIC_OPT_PUBLIC?>
                        <option value=""<?=$group->is_public ? '' : 'selected'?>><?=ADM_GROUPS_PUBLIC_OPT_PRIVATE?>
                    </select></td>
                </tr>
                <tr class="tdodd">
                    <td><?=ADM_GROUPS_IMAGES?></td>
                    <td><select name="data[images_allowed]">
                        <option value="1"><?=ADM_GROUPS_IMAGES_OPT_ALLOWED?>
                        <option value=""<?=$group->images_allowed ? '' : 'selected'?>><?=ADM_GROUPS_IMAGES_OPT_FORBIDDEN?>
                    </select></td>
                </tr>
                <tr class="tdodd">
                    <td><?=ADM_GROUPS_TOPICS_AUTOAPPROVE?></td>
                    <td><select name="data[topics_autoapprove]">
                        <option value="1"><?=GENERAL_ON?>
                        <option value=""<?=$group->topics_autoapprove ? '' : 'selected'?>><?=GENERAL_OFF?>
                    </select></td>
                </tr>
                <tr class="tdodd">
                    <td><?=ADM_GROUPS_STATUS?></td>
                    <td><select name="data[status]">
                        <option value="0"><?=ADM_GROUPS_STATUS_OPT_NEW?>
                        <option value="1"<?=($group->status == 1) ? ' selected' : ''?>><?=ADM_GROUPS_STATUS_OPT_APPROVED?>
                        <option value="2"<?=($group->status == 2) ? ' selected' : ''?>><?=ADM_GROUPS_STATUS_OPT_SUSPENDED?>
                    </select></td>
                </tr>
                <tr>
                  <td colspan="2" align="center" class="tdfoot"> <input name="submit" type="submit" class="button" value="<?=GENERAL_SAVE?>">
                    <input name="button" type="button" class="button" onclick="window.location = 'adm_groups.php'" value="<?=GENERAL_CANCEL?>">
                  </td>
                </tr>
            </table>
            </form>
        </td>
    </tr>
</table>

<?=$skin->ShowFooter($area)?>