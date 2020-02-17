<?php
/*****************************************************
* � copyright 1999 - 2020 iDateMedia, LLC.
*
* All materials and software are copyrighted by iDateMedia, LLC.
* under British, US and International copyright law. All rights reserved.
* No part of this code may be reproduced, sold, distributed
* or otherwise used in whole or in part without prior written permission.
*
****************************************************/

######################################################################
#
# Name:         ext_userplain.php
#
# Description:  Displays the profile input page (after advert)
#
# Version:      7.2
#
######################################################################
ob_start();
?>
<?if ($USERPLANE_IM || $USERPLANE_IM_FREE){?>
<script src="<?=$CONST_USERPLANE_LINK_ROOT?>/functions.js.php" type="text/javascript" language="javascript"></script>
<?}?>
<?if ($option_manager->GetValue('userplane_chat') || $option_manager->GetValue('userplane_chat_free')){?>
<script language="javascript">
<!--
function up_launchChat()
{
<?if ($option_manager->GetValue('userplane_chat')){?>
window.open("<?=$CONST_USERPLANE_LINK_ROOT?>/chat/ch.php", "UserplaneChatWindow", "width=720,height=660,toolbar=0,directories=0,menubar=0,status=0,location=0,scrollbars=0,resizable=1" );
<?} elseif ($option_manager->GetValue('userplane_chat_free')) {?>
window.open("<?=$CONST_USERPLANE_LINK_ROOT?>/chat/frame_ch.php", "UserplaneChatWindow", "width=720,height=660,toolbar=0,directories=0,menubar=0,status=0,location=0,scrollbars=0,resizable=1" );
<?}?>
}
//-->
</script>
<?}?>
<?
$content = ob_get_contents();
ob_end_clean();

return $content;


?>