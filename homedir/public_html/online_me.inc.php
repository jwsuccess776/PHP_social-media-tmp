<?
include_once(CONST_INCLUDE_ROOT.'/messenger/function.php');

if ($sql_array->isOnline && $Sess_UserId != $sql_array->adv_userid) {

    if ($option_manager->GetValue('userplane_im')) {

        print('<a href="#" onClick="up_launchWM( \''.$Sess_UserId.'\', \''.$sql_array->adv_userid.'\' ); return false;" title="Launch IM Now!"><img border="0" src="'.$CONST_IMAGE_ROOT.'online_im.gif" align="absmiddle"></a>');

    } elseif ($option_manager->GetValue('userplane_im_free')) {

        print('<a onClick="up_launchWM_free( \''.$Sess_UserId.'\', \''.$sql_array->adv_userid.'\' ); return false;" title="Launch IM Now!"><img border="0" src="'.$CONST_IMAGE_ROOT.'online_im.gif" align="absmiddle"></a>');

    } elseif (getOnlineImUser ($sql_array->adv_username)=='T') {

        print("<a href='#' onClick=\"MDM_openWindow('$CONST_LINK_ROOT/messenger/index.php?new_win_launched=TRUE','IM','width=146,height=298');window.open('$CONST_LINK_ROOT/immenow.php?userid=$sql_array->adv_userid&handle=$sql_array->adv_username','','toolbar=no,menubar=no,height=400,width=498,left='+(screen.width/2-100)+',top='+(screen.height/2-75)+'');return false;\"><img border='0' src='$CONST_IMAGE_ROOT/online_im.gif' align='absmiddle'></a>");

    }

}
?>
<script type="text/javascript">
	function up_launchWM_free( userID, destinationUserID)
	{
    	up_localUserID = userID;
	    var popupWindowTest = null;
	    popupWindowTest = window.open( '<?=CONST_LINK_ROOT?>/chat/web'+"?to_uid=" + destinationUserID,
	    	 "_blank", "width=920,height=505,toolbar=0,directories=0,menubar=0,status=0,location=0,scrollbars=0,resizable=yes" );
	}
</script>