<?php
/*****************************************************
* � copyright 1999 - 2020 iDateMedia, LLC.
*
* All materials and software are copyrighted by iDateMedia, LLC.
* under British, US and International copyright law. All rights reserved.
* No part of this code may be reproduced, sold, distributed
* or otherwise used in whole or in part without prior written permission.
*
****************************************************/

######################################################################
#
# Name:         immenow.php
#
# Description:  Displays the profile input page (after advert)
#
# Version:      7.2
#
######################################################################

include('db_connect.php');
include('session_handler.inc');
include_once('validation_functions.php');

if (isset($_REQUEST['userid']) ) {
    $userid =sanitizeData($_REQUEST['userid'], 'xss_clean');
    $handle=sanitizeData($_REQUEST['handle'], 'xss_clean'); 
    $my = $db->get_row("SELECT * FROM members WHERE mem_userid=$Sess_UserId");

    $result = $db->get_var("SELECT count(*) cnt FROM my_friends WHERE uid='$my->mem_username' AND friend_uid='$handle'");

    if ($result == 0) {
        $db->query("INSERT INTO my_friends (uid, friend_uid, status) VALUES ('$my->mem_username', '$handle', 'A')");
    }
    $status = "F";
    $phpTimer = time();
    $sSQL = "INSERT INTO iwannatalk (cid, uid, status, tstamp) VALUES ('".$handle."', '".$my->mem_username."', '".$status."', '".$phpTimer."')";
    $db->query($sSQL);

    header("Location: $CONST_LINK_ROOT/messenger/chat.php?frienduid=$handle");
    exit;

}
?>
<html>
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=<?=$CONST_LANG_CHARSET?>">
<title><?=ADD2FRIENDS_TITLE?></title>
<LINK REL='StyleSheet' type='text/css' href='<?echo $CONST_LINK_ROOT?>/singles.css'>
</head>
<body>
<script language="JavaScript" type="text/javascript">
  window.open('<?=$CONST_LINK_ROOT?>/messenger/chat.php?frienduid=<?=$handle?>','','toolbar=no,menubar=no,height=150,width=200,left='+(screen.width/2-100)+',top='+(screen.height/2-75)+'');
  'window.close();
</script>
</body>
</html>
