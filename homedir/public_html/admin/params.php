<?php
/*****************************************************
* © copyright 1999 - 2020 iDateMedia, LLC.
*
* All materials and software are copyrighted by iDateMedia, LLC.
* under British, US and International copyright law. All rights reserved.
* No part of this code may be reproduced, sold, distributed
* or otherwise used in whole or in part without prior written permission.
*
*****************************************************/
######################################################################
#
# Name: 		params.php
#
# Description:  Administrator system parameters management
#
# # Version:      8.0
#
######################################################################

include('../db_connect.php');
include('../session_handler.inc');
include('permission.php');

# retrieve the template
$area = 'member';

# select all parameters from table

$group= FormGet('group');

/**
 * Display correct inpur field for current option
 *
 * @param object $option
 * @return string
 *
 */
function display_option($option) {
    $res = "";
    if($option->type=='list' || $option->type=='skin'|| $option->type=='language'){
        $res .= "<select name=\"options[$option->name]\">";
		foreach ($option->list as $value => $label){
            $selected = ($value == $option->value) ? "SELECTED" : "";
			$res .= "<option value=\"$value\" $selected>$label</option>";
   		}
        $res .= "</select>";
    } else {
        $res = "<input class=\"input\" type=\"text\" name=\"options[$option->name]\" value=\"$option->value\">";
    }
    return $res;
}
$manager = &OptionManager::GetInstance();
$aGroups = $manager->GetGroupList();
if ($group === null && count($aGroups)) {
    $group = $aGroups[0]->Name;
}
$aOptions = ($group !== null) ? $manager->GetListByGroup($group) : array();
?>
<?=$skin->ShowHeader($area)?>
  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
    <tr>
      <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
      </td>
    </tr>
    <tr>

    <td class="pageheader"><?php echo PARAMS_SECTION_NAME ?></td>
    </tr>
  <tr>
    <td><? include("admin_menu.inc.php");?></td>
  </tr>
    <tr><td><table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">
          <tr>
            <td  colspan="3" align="left" valign="top" class="tdhead">&nbsp;</td>
          </tr>
        <form method='post' action='<?php echo $CONST_LINK_ROOT?>/admin/params.php'>
          <tr class="tdodd">
		  <td>
			<select name=group onChange="this.form.submit();">
<?foreach ($aGroups as $oRow) {
    $selected= ($oRow->Name == $group)? "selected" : "";
?>
				<option value="<?=$oRow->Name?>" <?=$selected?>><?=$oRow->Title?></option>
<?}?>
			</select>
		  </td>
		  </tr>
		</form>
        <form method='post' action='<?php echo $CONST_LINK_ROOT?>/admin/prgparams.php'>
        <input type=hidden name=group value="<?=$group?>">
          <tr>
            <td  colspan="3" align="left" valign="top" class="tdhead">&nbsp;</td>
          </tr>
<?
foreach ($aOptions as $option){
$class = ($i++%2) ? "tdeven" : "tdodd";
?>
          <tr class="<?=$class?>">
            <td  width=10% align="left" ><?=$option->label?></td>
            <td  width=10% align="left" > <?=display_option($option)?></td>
            <td  width=80% align="left"><?=$option->comments?></td>
          </tr>
<?}?>
          <tr align="center">
            <td colspan="3" class="tdfoot"><input name="Validate2" type="submit" class="button" value="<?php echo BUTTON_UPDATE ?>"></td>
          </tr>
          <tr>
            <td colspan="3" class="tdhead"  align="left">&nbsp;</td>
          </tr>
        </form>
      </table></td>
    </tr>
  </table>



<?=$skin->ShowFooter($area)?>
