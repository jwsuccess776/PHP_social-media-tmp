<?php

$option_manager =& OptionManager::GetInstance();

if ($option_manager->getValue('skype')) {

$skype_auth=true;

if ($sql_array->mem_skypeset == "HOTLIST") {

    $skype_test=mysqli_query($globalMysqlConn,"SELECT * FROM hotlist WHERE hot_userid=$sql_array->adv_userid AND hot_advid=$Sess_UserId");

    if (mysqli_num_rows($skype_test) < 1) $skype_auth=false;

}

if (trim($sql_array->mem_skype) !=""){

if ($skype_auth && (!$option_manager->getValue('skype_premium') || $Sess_Userlevel == 'gold')) { ?>

    <script type="text/javascript" src="http://download.skype.com/share/skypebuttons/js/skypeCheck.js"></script>

    <a href="skype:<?php echo $sql_array->mem_skype ?>?chat"><img src="http://mystatus.skype.com/mediumicon/<?php echo $sql_array->mem_skype ?>" align=absmiddle alt="My status" border="0" /></a>

<?php } elseif ($skype_auth == false) {?>

    <img src="<?php echo CONST_IMAGE_ROOT ?>skype_blocked.gif" border=0 title="Favourites Only" align=absmiddle />

<?php } elseif ($option_manager->getValue('skype_premium') && $Sess_Userlevel != 'gold') {?>

    <a href="<?=$CONST_LINK_ROOT?>/get_premium.php"><img src="<?php echo CONST_IMAGE_ROOT ?>skype_premium.gif"  border=0 title="Favourites Only" / align=absmiddle></a>

<?php } ?>

<?}}?>