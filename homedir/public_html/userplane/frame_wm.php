<?
include "../db_connect.php";
include($CONST_INCLUDE_ROOT.'/session_handler.inc');
$strDestinationUserID= formGet('strDestinationUserID');
?>
<frameset rows="*,108" framespacing="0" frameborder="no" border="0">
	<frame src="<?=$CONST_USERPLANE_LINK_ROOT?>/wm.php?strDestinationUserID=<?=$strDestinationUserID?>" name="Webmessenger_Frame"  scrolling="NO" noresize>
	<frame src="http://subtracts.userplane.com/mmm/bannerstorage/ch_int_frameset.html?app=wm&zoneID=<?=$option_manager->GetValue('userplane_full_banner_id')?>&textZoneID=<?=$option_manager->GetValue('userplane_text_zone_id')?>" name="Ad_Frame" scrolling="NO" noresize>
</frameset>
