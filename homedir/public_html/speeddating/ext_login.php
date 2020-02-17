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
# Name:                 ext_login.php
#
# Description:
#
# Version:                7.2
#
######################################################################
ob_start();
?>
<?php if ($_SESSION['Sess_UserId']) {?>
    <a href="<?=$CONST_LINK_ROOT;?>/speeddating/logoff.php" class="memlogin"><?=$MENU_LOGOUT?></a>
<?php } else {?>
    <a href="<?=$CONST_LINK_ROOT;?>/speeddating/login.php" class="memlogin"><?=$MENU_LOGIN?></a>
<?php } ?>

<?php
$content = ob_get_contents();
ob_end_clean();

return $content;
?>