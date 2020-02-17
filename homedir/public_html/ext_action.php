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
$userid = formGet('userid');
$network = new Network();
$status = $network->checkRelations($Sess_UserId,$userid);
ob_start();
if ($status !== null && $Sess_UserId != $userid) {
?>

<table width="98%" border="0" align="center" cellpadding="2" cellspacing="0">
<?if ($status == NETWORK_SINGLE_DUAL){?>
	<tr>
		
    <td align="left" class=tdodd> <a href="<?=$CONST_NETWORK_LINK_ROOT?>/change_status.php?action=unlink&user_id=<?=$userid?>"><?=REM_FRIEND_NETWORK?></a></td>
	</tr>
<? } elseif ($status == NETWORK_SINGLE_EMPTY) {?>
	<tr>
		
    <td align="left" class=tdodd> <a href="<?=$CONST_NETWORK_LINK_ROOT?>/change_status.php?action=request&user_id=<?=$userid?>"><?=ADD_FRIEND_NETWORK?></a></td>
	</tr>
<? } ?>
</table>
<?
}
$content = ob_get_contents();
ob_end_clean();
return $content;

?>