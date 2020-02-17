<?php
/*****************************************************
* © copyright 1999 - 2020 iDateMedia, LLC.
*
* All materials and software are copyrighted by iDateMedia, LLC.
* under British, US and International copyright law. All rights reserved.
* No part of this code may be reproduced, sold, distributed
* or otherwise used in whole or in part without prior written permission.
*
****************************************************/

######################################################################
#
# Name:         ext_stories.php
#
# Description:  Displays the profile input page (after advert)
#
# Version:      7.2
#
######################################################################
if (!$Sess_UserId) return;
include_once __INCLUDE_CLASS_PATH."/class.Network.php";
$network = new Network();
$sn_list = $network->getRequestList($Sess_UserId);

ob_start();
if (count($sn_list)>0) {
?>
<div id=ToolTip></div>
<table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">
	<tr>
		<td align="left" class=tdhead><?php echo SOCIAL_NETWORK_SECTION_NAME ?></td>
	</tr>
<?php
    foreach ($sn_list as $row) {
        $mem = $db->get_row("SELECT *, adv_comment FROM members LEFT JOIN adverts ON (mem_userid = adv_userid) WHERE mem_userid = '$row->User_ID'");
?>
	<tr>
		<td align="left" class=>
			<a href="<?=$CONST_NETWORK_LINK_ROOT?>/approve.php?user_id=<?=$row->User_ID?>&action=show" onmouseover="javascript:showToolTip(event,'Anton Zamov')" onmouseout="javascript:hideToolTip()"> <?=$mem->mem_username?></a><?php echo SOCIAL_NETWORK_REQUEST_LINK ?>
		</td>
	</tr>
<?}?>
</table>
<?
}
$content = ob_get_contents();
ob_end_clean();

return $content;

?>