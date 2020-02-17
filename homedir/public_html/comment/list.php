<?

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

# Name:         prgvideodmin.php

#

# Description:  Adds and removes additional videos for members

#

# Version:      8.0

#

######################################################################



include('../db_connect.php');

include(CONST_INCLUDE_ROOT.'/session_handler.inc');

include(CONST_INCLUDE_ROOT.'/error.php');

include_once __INCLUDE_CLASS_PATH.'/class.Json.php';

require(CONST_INCLUDE_ROOT.'/comment/functions.php');



$ent_id = formGet('ent_id');

$ent_type = formGet('ent_type');



include_once __INCLUDE_CLASS_PATH."/class.CommentManager.php";

include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";

$pager->PAGESIZE = ($SHOWNUM) ? $SHOWNUM : 8;

$pager->BASICURL = "ent_id=$ent_id&ent_type=$ent_type&";



$comment_manager = new CommentManager($ent_type, $ent_id);

$comments = $comment_manager->getList($pager);

$can_delete =  (checkEntOwner($ent_type, $ent_id)) ? true : false;

ob_start();

?>

<div class="pageheader">
  <?=COMMENTS?>
</div>

<?php foreach($comments as $row) {
            $adv = new Adverts($row->user_id);
            $adv->SetImage('small');
    		?>
<div class="resulthead">
  <?=$adv->adv_username?>
</div>
<div class="vidshow_resultbody">
  <table>
    <tr>
      <td valign="top" class="resultimage"><a href="<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$adv->adv_userid?>" class="imageframe"><img src='<?= $CONST_LINK_ROOT?><?=$adv->adv_picture->Path?>' width=<?=$adv->adv_picture->w?> name='pic' border=0 ></a></td>
      <td  valign="top"><div class="resultaddress">
          <?=getTimeShift($row->date)?>
        </div>
        <div class="resultcomment"><?php echo wordwrap($row->text, 15, " ", true) ; ?> </div>
        <? if ($can_delete){ ?>
        <div style="text-align:right"><a href="#" onClick="if (delete_alert_general()) deleteComment('ent_type=<?=$ent_type?>&ent_id=<?=$ent_id?>', '<?=$row->id?>');return false;">
          <?=DELETE_COMMENT?>
          </a> </div>
        <? } ?>
      </td>
    </tr>
  </table>
</div>
<? }?>
<div style="text-align:right">
  <?include CONST_INCLUDE_ROOT."/comment/pager.php"?>
</div>
<?

$list = ob_get_contents();

ob_end_clean();

echo Json::php2Javascript( array ('list' => $list));

?>
