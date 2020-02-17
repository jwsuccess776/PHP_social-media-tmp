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
# Name:                 error.php
#
# Description:  Handles errors occuring on the member side
#
# Version:                7.2
#
######################################################################

if ($_REQUEST['speeddating'] == 1) {
    include_once('db_connect.php');
} else {
    include_once('../db_connect.php');
}
function error_page($e_message,$type) {
    global $Sess_UserId;
    global $Sess_UserType;
    global $Sess_Userlevel;
    global $CONST_SD_URL;
    global $CONST_INCLUDE_ROOT;
    global $CONST_LINK_ROOT;
    global $CONST_IMAGE_LANG;
    global $link;
    global $extensionsList;

    foreach ($extensionsList as $extension=>$generator)
        global ${$extension};

    $skin =& Skin::GetInstance();

    $area = 'speeddating';
    echo $skin->ShowHeader($area);
        print ("<table width='$CONST_TABLE_WIDTH' border='0' cellspacing='$CONST_TABLE_CELLSPACING' cellpadding='$CONST_TABLE_CELLPADDING'>
    <tr>
      <td class='pageheader'>".ERROR_SECTION_NAME."</td>
    </tr>
    <tr>
      <td><span class='error'>$type:</span>
      <p>The following error occurred when you
          tried submitting the form.</p>
        <p><b>$e_message</b></p>
        <p>".ERROR_TEXT."</td>
    </tr>
  </table>");
echo $skin->ShowFooter($area);
        exit;
}
?>