<?php

if (isset($_SESSION['Sess_UserId'])){	
       
    if ($option_manager->GetValue('userplane_chat') || $option_manager->GetValue('userplane_chat_free')) {
		echo '<a href="#" onClick="up_launchChat(); return false;">'.$MENU_CHAT.'</a>';
	} else {
		echo $generatedChat;    
    }  
        
} else { 

echo '<a href="'.$CONST_LINK_ROOT.'/login.php">'.$MENU_CHAT.'</a>';

}	

?>