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
include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";
$network = new Network();
$sn_list = $network->getRequestList($Sess_UserId);
if (count($sn_list)>0) {
?>
<table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">
<?php
    foreach ($sn_list as $row) {
        $mem = new Adverts();
        $mem->InitById($row->User_ID);
        $mem->SetImage('small');
        if ($mem->mem_userid){?>
	<tr>
        <form method="post" id=form<?=$row->User_ID?> action="<?php echo $CONST_NETWORK_LINK_ROOT?>/approve.php">
	        <input type=hidden name=action value="">
    	    <input type=hidden name=user_id value="<?=$mem->adv_userid?>">
		<td align="left" class=>
			<a href="<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$mem->adv_userid?>">
				<?=$mem->adv_username?>
			</a>
			<?php echo SOCIAL_NETWORK_REQUEST_LINK ?>
        </td>
		<td align="left" class=>
			<input type=button class=button onClick="f=getElementById('form'+<?=$mem->adv_userid?>);f.action.value='approve';f.submit();" value="<?= GENERAL_APPROVE ?>">

        </td>
		<td align="left" class=>
			<input type=button class=button onClick="f=getElementById('form'+<?=$mem->adv_userid?>);f.action.value='reject';f.submit();" value="<?= GALLERY_REJECTED ?>">
		</td>
        </form>
	</tr>
  <tr>
    <td align="left" colspan=3 class="tdfoot">&nbsp;</td>
  </tr>
	<?}?>
<?}?>
</table>
<? } ?>