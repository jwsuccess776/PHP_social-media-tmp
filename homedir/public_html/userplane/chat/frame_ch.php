<?
include "../../db_connect.php";
include($CONST_INCLUDE_ROOT.'/session_handler.inc');
?>
<frameset rows="*,145" framespacing="0" frameborder="no" border="0">
	<frame src="<?=$CONST_USERPLANE_LINK_ROOT?>/chat/ch.php" name="Webchat_Frame" scrolling="NO" noresize>
	<frame src="http://subtracts.userplane.com/mmm/bannerstorage/ch_int_frameset.html?app=wc&zoneID=<?=$option_manager->GetValue('userplane_leader_board_id')?>&textZoneID=<?=$option_manager->GetValue('userplane_text_zone_id')?>" name="Ad_Frame" scrolling="NO" noresize>
</frameset>