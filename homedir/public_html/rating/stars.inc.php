<?
function show_rating($ratedItem,$allow_rate = false) {
?>
    <style>

    img.star{
     background-image: url('<?=CONST_IMAGE_ROOT?>/icons/bg_rating_star.gif');
     background-position: left center; 
    }

    img.halfstar{
     background-image: url('<?=CONST_IMAGE_ROOT?>/icons/bg_rating_star.gif');
     background-position: center center; 
    }

    img.emptystar{
     background-image: url('<?=CONST_IMAGE_ROOT?>/icons/bg_rating_star.gif');
     background-position: right center; 
    }
    div.rating{
     width: 110px;
     height: 16px;
     padding-top: 4px;
    }

    </style>
    <?
    //dump($ratedItem);
    $rate_id = $ratedItem->_id;
    $scale = $ratedItem->getScale();
    $result = $ratedItem->getRating();
    ?>
    <script language="javascript">
        rating[<?=$rate_id?>] = <?=$result->rating?>;
    </script>


    <div id="rate_block_<?=$rate_id?>"> 
    <?
    for ($i=1;$i <= $scale; $i++){
    ?>
        <?if ($allow_rate){ ?>
            <a href="#" onClick="sendRatingRequest('<?=$ratedItem->rate_url?><?=$i?>',<?=$rate_id?>,<?=$scale?>);return false;" onMouseOver="putStar(<?=$rate_id?>,<?=$i?>, <?=$scale?>)" onMouseOut="clearStar(<?=$rate_id?>,rating[<?=$rate_id?>], <?=$scale?>)"><img id="rating_line_<?=$rate_id?>_<?=$i?>" border=0 class=emptystar src="<?=CONST_IMAGE_ROOT?>/spacer.gif" width="20px" height="20px" align=absmiddle></a>
        <?} else {?>
            <img id="rating_line_<?=$rate_id?>_<?=$i?>" border=0 class=emptystar src="<?=CONST_IMAGE_ROOT?>/spacer.gif" width="20px" height="20px" align=absmiddle>
        <?}?>
    <?}?>
     Voted <div id="rate_voted_<?=$rate_id?>" style="display:inline;"><?=$result->voted;?></div>
    </div>
    <div class=rating align=center id="rate_progress_<?=$rate_id?>" style="display:none;">
        <img src="<?=CONST_IMAGE_ROOT?>/ajax-loader.gif" align="absmiddle" border=0>
    </div>
    <script language="javascript">
       setupStar(<?=$rate_id?>, rating[<?=$rate_id?>], <?=$scale?>);
    </script>
<?}?>