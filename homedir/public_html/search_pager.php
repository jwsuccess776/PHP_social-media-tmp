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

<form method="post" id="search_pager_myForm" action="<?=$pager->BASICURL?>">

<input type="hidden" name="lstDatingCountry" value="<?=$lstDatingCountry?>">

<input type="hidden" name="lstDatingTo" value="<?=$lstDatingTo?>">

<input type="hidden" name="lstDatingFrom" value="<?=$lstDatingFrom?>">
<input type="hidden" name="page" value="1">
<input type="hidden" name="SHOWNUM" id="shownum" value="">
<?php 
$cl=(array)$pager->TOTAL->field_count;
// print_r($cl[0]);
// die;
?>
<?if ($cl[0] > 0){?>

<?php $pager=(array)$pager; ?>

		<?=PRGSEARCH_RESULTS?> : <?=$pager->FIRSTPOS?> - <?=$pager->ENDPOS?> <?=PRGSEARCH_TOTAL?> :  <?=$cl[0]?> | <?=SEARCH_RESULT_PERPAGE?>:&nbsp;

      <select name="lstShownum" id="lstShownum" size="1" class="inputf" onChange="submitearchform();">

		<?for ($i=8;$i<=56;$i+=8){?>

        <option value="<?=$i?>" <?if($pager->PAGESIZE == $i) echo "SELECTED"?> > <?=$i?></option>

        <?}?>

      </select> <br>



        <?if ($pager->FIRSTPAGE!=$pager->PAGE) {?>

			<A HREF="<?=$pager->BASICURL?>page=<?=$pager->FIRSTPAGE?>&SHOWNUM=<?=$pager->PAGESIZE?>"><?=PRGSEARCH_FIRST_PAGE?></A>

		<?}?>

        <?if ($pager->FIRSTPAGE!=$pager->PAGE) {?>

			<A HREF="<?=$pager->BASICURL?>page=<?=$pager->PREVPAGE?>&SHOWNUM=<?=$pager->PAGESIZE?>"><?=PRGSEARCH_PREV_PAGE?></A>

		<?}?>

		<?if ( ($pager->LIST)[0] >1)

         foreach($pager->LIST as $page) {?>

	        <?if ($page!=$pager->PAGE) {?><A HREF="<?=$pager->BASICURL?>page=<?=$page?>&SHOWNUM=<?=$pager->PAGESIZE?>" ><?}?>

				<?=$page?>

			<?if ($page!=$pager->PAGE) {?></A><?}?>

		<?}?>

        <?if ($pager->NEXTPAGE!=$pager->PAGE) {?>

        	<A HREF="<?=$pager->BASICURL?>page=<?=$pager->NEXTPAGE?>&SHOWNUM=<?=$pager->PAGESIZE?>"><?=PRGSEARCH_NEXT_PAGE?></A>

        <?}?>



        <?if ($pager->LASTPAGE!=$pager->PAGE) {?>

        	<A HREF="<?=$pager->BASICURL?>page=<?=$pager->LASTPAGE?>&SHOWNUM=<?=$pager->PAGESIZE?>"><?=PRGSEARCH_LAST_PAGE?></A>

		<?}?>

		&nbsp;

<?}?>

</form>