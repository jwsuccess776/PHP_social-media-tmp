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
# Name:         main.php
#
# Description:  Page displayed after a user pays for membership
#
# Version:      7.2
#
####################################################################
session_start();
/*
        $handle = fopen("action.txt", 'x+');
        fwrite($handle, "ses=".$_SESSION['Sess_UserName']);
        fclose($handle);

if (!isset($_SESSION['Sess_UserName'])){
        echo "exit";
//    exit("Incorrect Parameters");
} else {
    $phpuid = $_SESSION['Sess_UserName'];
}
*/

//$path = "http://www.angel.ace/Flash/DatingSoftware/1/";
//$user_name = "angel";

if (!isset($_SESSION['Sess_UserName'])){
    exit("Timed out, please close and restart.");
}

?>
<script language="JavaScript">
    function MDM_openWindow(theURL,winName,features,curleft,curTop) {
        if (!curleft) {
            curleft = 50;
        }
        if (!curTop) {
            curTop = 50;
        }
            var _W=window.open(theURL,winName,features);
            _W.focus();
            _W.moveTo(curleft,curTop);
    }
//    flasent = "F";
    document.flasent = "F";
    function windowClose() {
//        flasent = "T";
        document.flasent = "T";
        window.close();
    }

    function closeAlert() {
//        alert(document.flasent);
        if(document.flasent == "F"){
            var aMsg = 'WARNING:\n\nIf you do not click \"Logout\", you will still\nappear to be online.\n\nPlease re-login, then logout properly.';
            alert(aMsg);
        }
    }

</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=koi8-r" />
<title>main</title>
</head>
<body bgcolor="#ffffff" MARGINHEIGHT="0" MARGINWIDTH="0" LEFTMARGIN="0" RIGHTMARGIN="0" TOPMARGIN="0" BOTTOMMARGIN="0" onUnload="closeAlert();">
<!--url's used in the movie-->
<!--text used in the movie-->
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="146" height="298" id="main" align="middle">
<param name="allowScriptAccess" value="sameDomain" />
<param name="movie" value="index.swf?fuid=<?=$_SESSION['Sess_UserName']?>" />
<param name="quality" value="high" />
<param name="bgcolor" value="#ffffff" />
<PARAM NAME=menu VALUE=false>
<embed src="index.swf?fuid=<?=$_SESSION['Sess_UserName']?>" quality="high" bgcolor="#ffffff" width="146" height="298" name="main" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>
</body>
</html>