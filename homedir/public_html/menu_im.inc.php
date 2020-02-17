
<?php

if (isset($_SESSION['Sess_UserId'])){	
       
	if ($USERPLANE_IM || $option_manager->GetValue('userplane_im_free')) {
		echo '';
	} else {
		echo '<a href="javascript:MDM_openWindow(\''.$CONST_LINK_ROOT.'/messenger/index.php?new_win_launched=TRUE\',\'IM\',\'width=146,height=298\')" >'.$MENU_IM.'</a>';
	}
        
} 

?>

