<?php
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
# Name:         prgvideodmin.php
#
# Description:  Adds and removes additional videos for members
#
# Version:      8.0
#
######################################################################

include('../db_connect.php');
include($CONST_INCLUDE_ROOT.'session_handler.inc');
include($CONST_INCLUDE_ROOT.'error.php');
include($CONST_INCLUDE_ROOT.'message.php');
include('permission.php');

set_time_limit(0);

include_once __INCLUDE_CLASS_PATH."/class.Video.php";
$video = new Video();
include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";
$adv = new Adverts();

if (formGet('convert')) {
?>
<html>
<body>
<?
    $video->InitById(formGet('vid_id'));
    $res = $video->convert();
    if ($res === null) {
        echo "<center>".join("<br>",$video->error)."</center>";
    } else {
        $adv->initById($video->vid_userid);
        $data['ReceiverName'] = $adv->adv_username;
        $data['CompanyName'] = $CONST_COMPANY;
        $data['Url'] = $CONST_URL;
        $data['SupportEmail'] = $CONST_SUPPMAIL;
        list($type,$message) = getTemplateByName("Approve_Video",$data,getDefaultLanguage($video->vid_userid));
        send_mail ($adv->mem_email, "$CONST_MAIL", "$CONST_COMPANY ".PRGAUTHVIDEO_APR, $message,$type,"ON");
    }
}
?>
<script language=javascript>
    parent.document.getElementById('back').style.display="";
</script>
</body>
</html>


