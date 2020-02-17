<?php

include('../db_connect.php');

include('../session_handler.inc');

include('../error.php');

include('../functions.php');

include('../message.php');

require_once(__INCLUDE_CLASS_PATH.'/class.GroupCategory.php');

require_once(__INCLUDE_CLASS_PATH.'/class.Group.php');

include('permission.php');



$mode = formGet('mode');

switch ($mode) {

    case 'process':

        $gID = formGet('gID');

        $group = new Group($gID);

        $data = formGet('data');

        if ($group->initByArray($data) === null) {

            error_page(join('<br>', $group->error), 'USER ERROR');

        }

        if ($group->status == -1) {

            $group->delete();

            $user = new Adverts($group->owner);

            $data = array(

                'OwnerName' => $user->mem_username,

                'GroupName' => $group->name,

                'Reason' => formGet('reason')

            );

            $op = new OptionManager;
            $option_manager = $op->GetInstance();

            list($type,$message) = getTemplateByName("Reject_Group",$data,getDefaultLanguage($user->mem_userid));

            send_mail ($user->mem_email, $option_manager->GetValue('mail'), GROUP_REJECTED_SUBJECT, $message ,$type,"ON");

        } else {

            $group->save();

            $user = new Adverts($group->owner);

            $data = array(

                'OwnerName' => $user->mem_username,

                'GroupName' => $group->name

            );

            $ops = new OptionManager;
            $option_manager = $ops->GetInstance();

            list($type,$message) = getTemplateByName("Approve_Group",$data,getDefaultLanguage($user->mem_userid));

            send_mail ($user->mem_email, $option_manager->GetValue('mail'), GROUP_APPROVED_SUBJECT, $message ,$type,"ON");

        }

        break;

}

$area = 'member';


$gp = new Group;
$group = $gp->find('status = 0', 1);

$category = new GroupCategory($group->category);

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

        <?php if ($group) { ?>

            <form action="adm_groups_approve.php" method="post" name="groupForm" id="groupForm">

            <input type="hidden" name="mode" value="process">

            <input type="hidden" name="gID" value="<?=$group->id?>">

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

                    <td><?=ADM_GROUPS_CATEGORY?></td>

                    <td><?=$category->getPath()?></td>

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

                            echo '<img src="'.$group->image->URL.'" alt="'.ADM_GROUP_IMAGE_VIEW.'">';

                        }

                    ?></td>

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

$gc = new Geography;
$CountriesList = $gc->getCountriesList();

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

                <tr class="tdodd" align="center">

                    <td colspan="2">

                        <input type="radio" name="data[status]" value="1"> Approve &nbsp;

                        <input type="radio" name="data[status]" value="-1"> Reject &nbsp; <br>

                        Reason <input name='reason' type='text' class="inputl" size='30'>

                    </td>

                </tr>

                <tr>

                  <td colspan="2" align="center" class="tdfoot"> <input name="submit" type="submit" class="button" value="<?=ADM_GROUPS_PROCESS?>"></td>

                </tr>

            </table>

            </form>

        <?php } else echo ADM_GROUPS_ALL_APPROVED; ?>

        </td>

    </tr>

</table>



<?=$skin->ShowFooter($area)?>