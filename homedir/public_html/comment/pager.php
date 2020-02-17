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
# Name:                 pager.php
#
# Description:  Home page destination for traffic sent by affiliates
#
# Version:               7.2
#
######################################################################
?>
<form method="post" action="<?=$pager->BASICURL?>">
<?if ($pager->TOTAL > 0){?>
        <?=PRGSEARCH_RESULTS?> : <?=$pager->FIRSTPOS?> - <?=$pager->ENDPOS?> <?=PRGSEARCH_TOTAL?> :  <?=$pager->TOTAL?> | Results per page:&nbsp;
      <select name="lstShownum" size="1" class="inputf" onChange="getComments('<?=$pager->BASICURL?>page=1&SHOWNUM='+this.value)">
        <?for ($i=8;$i<=56;$i+=8){?>
        <option value="<?=$i?>" <?if($pager->PAGESIZE == $i) echo "SELECTED"?> > <?=$i?></option>
        <?}?>
      </select> <br>

        <?if ($pager->FIRSTPAGE!=$pager->PAGE) {?>
            <A HREF="#" onClick="getComments('<?=$pager->BASICURL?>page=<?=$pager->FIRSTPAGE?>&SHOWNUM=<?=$pager->PAGESIZE?>');return false"><?=PRGSEARCH_FIRST_PAGE?></A>
        <?}?>
        <?if ($pager->FIRSTPAGE!=$pager->PAGE) {?>
            <A HREF="#" onClick="getComments('<?=$pager->BASICURL?>page=<?=$pager->PREVPAGE?>&SHOWNUM=<?=$pager->PAGESIZE?>');return false"><?=PRGSEARCH_PREV_PAGE?></A>
        <?}?>
        <?if (count($pager->LIST)>1)
         foreach($pager->LIST as $page) {?>
            <?if ($page!=$pager->PAGE) {?><A HREF="#" onClick="getComments('<?=$pager->BASICURL?>page=<?=$page?>&SHOWNUM=<?=$pager->PAGESIZE?>');return false;" ><?}?>
                <?=$page?>
            <?if ($page!=$pager->PAGE) {?></A><?}?>
        <?}?>
        <?if ($pager->NEXTPAGE!=$pager->PAGE) {?>
            <A HREF="#" onClick="getComments('<?=$pager->BASICURL?>page=<?=$pager->NEXTPAGE?>&SHOWNUM=<?=$pager->PAGESIZE?>');return false;"><?=PRGSEARCH_NEXT_PAGE?></A>
        <?}?>

        <?if ($pager->LASTPAGE!=$pager->PAGE) {?>
            <A HREF="#" onClick="getComments('<?=$pager->BASICURL?>page=<?=$pager->LASTPAGE?>&SHOWNUM=<?=$pager->PAGESIZE?>');return false;"><?=PRGSEARCH_LAST_PAGE?></A>
        <?}?>
        &nbsp;
<?}?>
</form>