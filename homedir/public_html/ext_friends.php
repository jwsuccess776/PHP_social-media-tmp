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
include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";
$sn_list = $network->getNetwork($Sess_UserId,1);
$columns = 3;
$rows = 2;
ob_start();
if (count($sn_list)>0) {
?>

<table width="98%" border="0" align="center" cellpadding="2" cellspacing="0">
	<tr>
<?php
    $i=0;
    foreach ($sn_list as $id) {
        $mem = new Adverts();
        $mem->InitById($id);
        $mem->SetImage('small');
?>
		<td align="center" class=tdodd>
			<a href="<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$mem->mem_userid?>">
				<img border='0' src='<?=$CONST_LINK_ROOT?><?=$mem->adv_picture->Path?>?<?=time()?>' width="<?=$mem->adv_picture->w?>"><br> <?=$mem->mem_username?>
			</a>
		</td>
<?
if (++$i >= $rows*$columns) break;
}
?>
	</tr>
	<tr>
		<td colspan=<?=$i?> align="right" class=tdodd>
			<a href="<?=$CONST_NETWORK_LINK_ROOT?>/network.php?level=0&user_id=<?=$Sess_UserId?>"> View more</a>
		</td>
	</tr>
</table>
<?
}
$content = ob_get_contents();
ob_end_clean();

return $content;

?>
