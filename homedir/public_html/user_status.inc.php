<?php
if ($Sess_UserId) {
    if ($Sess_UserType == "A") {
        print("<img src='$CONST_IMAGE_ROOT"."icons/icons_26.gif'> <a href='$CONST_LINK_ROOT/admin/index.php'>".HOME_ADMIN."</a>");
    } else {
        if($_SESSION['Sess_Userlevel'] == 'silver') {		
			print("<a href='$CONST_LINK_ROOT/get_premium.php'><img border='0' src='$CONST_IMAGE_ROOT"."$CONST_IMAGE_LANG/mem_$_SESSION[Sess_Userlevel].gif' width='$CONST_MEMIMAGE_WIDTH' height='$CONST_MEMIMAGE_HEIGHT'></a>");
		} else {
			print("<img border='0' src='$CONST_IMAGE_ROOT"."$CONST_IMAGE_LANG/mem_$_SESSION[Sess_Userlevel].gif' width='$CONST_MEMIMAGE_WIDTH' height='$CONST_MEMIMAGE_HEIGHT'>");
    	}
	}
}
?>