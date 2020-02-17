<?
ob_start();

//$dID = $option_manager->GetValue("userplane_freechat_domainid");
if ($dID) {
    ?>
    <form method="post" action="http://www.userplane.com/chatlite/run/login.cfm" target="Userplane_Chatlite_<?=$dID?>" autocomplete="off" id=chatForm>
    <input type="hidden" name="companyID" value="<?=$dID?>">
    <input type="hidden" name="initialRoom" value="">
    <input type="hidden" name="s" value="0">
    <input type="hidden" id="username" name="username" value="<?=$_SESSION['Sess_UserName']?>" tabindex="1" autocomplete="off">
    <a href="#" onClick="document.getElementById('chatForm').submit()"> <?=$MENU_CHAT?></a>
    </form>
<?} else {?>
    <a href="<?=$CONST_LINK_ROOT?>/chat.php"><?=$MENU_CHAT?></a>
    <?
}
$content = ob_get_contents();
ob_end_clean();
return $content;
?>
