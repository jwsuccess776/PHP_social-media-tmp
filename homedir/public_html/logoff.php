<?php

/*****************************************************

* � copyright 1999 - 2003 Interactive Arts Ltd.

*

* All materials and software are copyrighted by Interactive Arts Ltd.

* under British, US and International copyright law. All rights reserved.

* No part of this code may be reproduced, sold, distributed

* or otherwise used in whole or in part without prior written permission.

*

*****************************************************/

######################################################################

#

# Name:                 logoff.php

#

# Description:  Home page destination for traffic sent by affiliates

#

# Version:               7.2

#

######################################################################

include('db_connect.php');

// Initialize the session.

// If you are using session_name("something"), don't forget it now!



if (is_array($_SESSION['old_data'])) {

    $temp = $_SESSION['old_data'];

    $_SESSION = $temp;

    redirect($CONST_ADMIN_LINK_ROOT);

}

if (isset($_SESSION['Sess_UserId'])) {

	$Sess_UserId=$_SESSION['Sess_UserId'];

	//$db->query("UPDATE members SET mem_timeout='unix_timestamp(NOW())-".(ONLINE_TIMEOUT_PERIOD*60+1)."' WHERE mem_userid='$Sess_UserId'");
        
        $db->query("UPDATE members SET mem_timeout='0000-00-00 00:00:00' WHERE mem_userid='$Sess_UserId'");
	$db->query("DELETE FROM members_opentok_chat WHERE uid='$Sess_UserId'");
	$db->query("DELETE FROM members_videochat WHERE uid='$Sess_UserId'");
	session_unset();

	session_destroy();

}

redirect($CONST_LINK_ROOT);

?>