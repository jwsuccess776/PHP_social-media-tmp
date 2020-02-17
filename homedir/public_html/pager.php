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
<?if ($pager->TOTAL > 0){?>
		<?=PRGSEARCH_RESULTS?> : <?=$pager->FIRSTPOS?>-<?=$pager->ENDPOS?> <?=PRGSEARCH_TOTAL?> :  <?=$pager->TOTAL?><br>
        <font class=boldpink>
        <?if ($pager->FIRSTPAGE!=$pager->PAGE) {?>
			<A HREF="<?=$pager->BASICURL?>page=<?=$pager->FIRSTPAGE?>"><?=PRGSEARCH_FIRST_PAGE?></A>
		<?}?>
        <?if ($pager->FIRSTPAGE!=$pager->PAGE) {?>
			<A HREF="<?=$pager->BASICURL?>page=<?=$pager->PREVPAGE?>"><?=PRGSEARCH_PREV_PAGE?></A>
		<?}?>
		<?if (count($pager->LIST)>1)
         foreach($pager->LIST as $page) {?>
	        <?if ($page!=$pager->PAGE) {?><A HREF="<?=$pager->BASICURL?>page=<?=$page?>" ><?}?>
				<?=$page?>
			<?if ($page!=$pager->PAGE) {?></A><?}?>
		<?}?>
        <?if ($pager->NEXTPAGE!=$pager->PAGE) {?>
        	<A HREF="<?=$pager->BASICURL?>page=<?=$pager->NEXTPAGE?>"><?=PRGSEARCH_NEXT_PAGE?></A>
        <?}?>

        <?if ($pager->LASTPAGE!=$pager->PAGE) {?>
        	<A HREF="<?=$pager->BASICURL?>page=<?=$pager->LASTPAGE?>"><?=PRGSEARCH_LAST_PAGE?></A>
		<?}?>
		&nbsp;
	</font>
<?}?>
