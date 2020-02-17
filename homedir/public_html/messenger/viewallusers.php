<?
/*****************************************************
* © copyright 1999 - 2020 iDateMedia, LLC.
*
* All materials and software are copyrighted by iDateMedia, LLC.
* under British, US and International copyright law. All rights reserved.
* No part of this code may be reproduced, sold, distributed
* or otherwise used in whole or in part without prior written permission.
*
*****************************************************/
######################################################################
#
# Name:         viewallusers.php
#
# Description:  Page displayed after a user pays for membership
#
# Version:      7.2
#
####################################################################
include('../db_connect.php');
if (!isset($_SESSION['Sess_UserName'])){
    exit("Timed out, please close and restart.");
}

?>
<script language=javascript>
function pageReload() {
    window.opener.document.flasent="T";
    window.opener.location.reload();
}
</script>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=koi8-r" />
<title>viewallusers</title>
</head>
<body bgcolor="#ffffff" MARGINHEIGHT="0" MARGINWIDTH="0" LEFTMARGIN="0" RIGHTMARGIN="0" TOPMARGIN="0" BOTTOMMARGIN="0">
<!--url's used in the movie-->
<!--text used in the movie-->
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="265" height="298" id="viewallusers" align="middle">
<param name="allowScriptAccess" value="sameDomain" />
<param name="movie" value="viewallusers.swf" />
<param name="quality" value="high" />
<param name="bgcolor" value="#ffffff" />
<embed src="viewallusers.swf" quality="high" bgcolor="#ffffff" width="265" height="298" name="viewallusers" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>
</body>
</html>
