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
function display_network_friends($user_id){
    include_once __INCLUDE_CLASS_PATH."/class.Network.php";
    $network = new Network();
    include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";
    $sn_list = $network->getNetwork($user_id,1);
    $columns = 2.5;
    $rows = 2;
    $width = 100/($rows*$columns);
    ob_start();
    if (count($sn_list)>0) {
    ?>


<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
  <tr>
    <?php
        $i=0;
        foreach ($sn_list as $id) {
            $mem = new Adverts();
            $mem->InitById($id);
            $mem->SetImage('small');
    ?>

    <td width="<?=$width?>%" align="center">
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="imageframe"><a href="<?=CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$mem->mem_userid?>"><img border='0' src='<?=CONST_LINK_ROOT?><?=$mem->adv_picture->Path?>?<?=time()?>' width="<?=$mem->adv_picture->w?>"></a></td>
        </tr>
      </table>
      <a href="<?=CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$mem->mem_userid?>">
      <?=$mem->mem_username?>
      </a></td>
    <?
            if (++$i >= $rows*$columns) break;
        }
        if ($i%($rows*$columns) !=0){
    ?>
        <td colspan=<?=$rows*$columns-$i?> width="<?=$width*($rows*$columns-$i)?>%" >&nbsp;</td>
        <?}?>
        </tr>
        <tr>
            <td colspan=<?=$columns*$rows?> align="right">
                <a href="<?=CONST_NETWORK_LINK_ROOT?>/network.php?level=0&user_id=<?=$user_id?>"><?=MYGROUPS_VIEW_ALL?></a>
            </td>
        </tr>
    </table>
    <?
    }
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}
?>
