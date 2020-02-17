<?php
/*****************************************************
* � copyright 1999 - 2020 iDateMedia, LLC.
*
* All materials and software are copyrighted by iDateMedia, LLC.
* under British, US and International copyright law. All rights reserved.
* No part of this code may be reproduced, sold, distributed
* or otherwise used in whole or in part without prior written permission.
*
*****************************************************/
######################################################################
#
# Name:         prgdisplaypic.php
#
# Description:  Sends offer mails to people who have not visited for a while
#
# Version:      7.4
#
######################################################################
include('db_connect.php');
include_once('validation_functions.php'); 
$thePic=sanitizeData($_GET['thePic'], 'xss_clean') ; 
?>
<html>
<head>
<title>Picture</title>
<script language="JavaScript">
function BrowserCheck() {
    var browser = navigator.appName
    if (browser=="Netscape") this.browser = "ns"
    else if (browser=="Microsoft Internet Explorer") this.browser = "ie"
    else this.browser = browser
    this.v = navigator.appVersion
    this.version = parseInt(this.v)
    this.ns = (this.browser=="ns" && this.version>=4)
    this.ns4 = (this.browser=="ns" && this.version==4)
    this.ns5 = (this.browser=="ns" && this.version==5)
    this.ie = (this.browser=="ie" && this.version>=4)
    this.ie4 = (this.v.indexOf('MSIE 4')>0)
    this.ie5 = (this.v.indexOf('MSIE 5')>0)
    this.mac = (this.v.indexOf("Mac")>0)
    this.oldWin = (this.v.indexOf("3.1")>0)
    this.min = (this.ns||this.ie)
}
is = new BrowserCheck();
function window_onload() {
    AdditHeight = 80;
    AdditWidth = 20;
    PicHeight = document.thePic.height + AdditHeight;
    PicWidth = document.thePic.width + AdditWidth;
    MaxWidth = screen.availWidth;
    MaxHeight = screen.availHeight;
    MaxWidth = 800;
    MaxHeight = 600;
    if (MaxHeight < PicHeight || MaxWidth < PicWidth) {
        LevelHeight = PicHeight/MaxHeight;
        LevelWidth = PicWidth/MaxWidth;
        LevelMax = Math.max(LevelHeight, LevelWidth);
        NewHeight = Math.round(PicHeight/LevelMax);
        NewWidth = Math.round(PicWidth/LevelMax);
        PicHeight = NewHeight;
        PicWidth = NewWidth;
        document.thePic.height = PicHeight - AdditHeight;
        document.thePic.width = PicWidth - AdditWidth;
    }
//    alert(is.ie);
    window.focus();
    window.resizeTo(0,0);
    if (is.ie) {
            Grow = window.setInterval("window_resizerIE(15,15,PicWidth,PicHeight)",1)
    } else if (is.ns) {
            Grow = window.setInterval("window_resizerNS(20,20,PicWidth,PicHeight)",1)
    }
}

function window_resizerIE(x,y) {
    if (document.body.clientWidth < PicWidth) {
        window.resizeBy(x,0);
    } else if (document.body.clientHeight < PicHeight) {
        window.resizeBy(0,y);
    } else {
        window.clearInterval(Grow);
    }
}
function window_resizerNS(x,y) {
    if (window.innerWidth < PicWidth) {
        window.resizeBy(x,0);
    } else if (window.innerHeight < PicHeight-10) {
        window.resizeBy(0,y);
    } else {
        window.clearInterval(Grow);
    }
}
</script>
<LINK href="<?=$CONST_LINK_ROOT.$skin->Path?>/singles.css" type=text/css rel=StyleSheet>
</head>
<body language="javascript" onload="return window_onload();" class="poptable">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
  <tr>
    <td align="center"><a href="" onClick="window.close();"><img src=<?php echo $CONST_LINK_ROOT ?><?php echo stripslashes($thePic); ?> name="thePic" border="0">
     <br><?php echo GENERAL_CLOSE?></a></td>
  </tr>

</table>

</body>
</html>
