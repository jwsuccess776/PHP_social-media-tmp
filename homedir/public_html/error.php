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
# Name:                 error.php
#
# Description:  Handles errors occuring on the member side
#
# Version:                7.2
#
######################################################################
function error_page($e_message,$type) {

    global $Sess_UserId;
    global $Sess_UserType;
    global $Sess_Userlevel;
	global $CONST_LINK_ROOT;
	
    foreach ($GLOBALS as $name=>$value) {
        if (preg_match("/^MENU/",$name)) global $$name;
    }
    foreach ($GLOBALS as $name=>$value) {
        if (preg_match("/^CONSTS/",$name)) global $$name;
    }
    $area = 'member';

    $skin =& Skin::GetInstance();
    echo $skin->ShowHeader($area);
    print ("<table width='$CONST_TABLE_WIDTH' border='0' cellspacing='$CONST_TABLE_CELLSPACING' cellpadding='$CONST_TABLE_CELLPADDING'>
    <tr>
      <td colspan='2'>&nbsp;</td>
    </tr>
    <tr>
      <td colspan='2' class='pageheader'>".$type."</td>
    </tr>
    <tr>
    <td width='95px' align='left' valign='top'><img src='$CONST_LINK_ROOT/additional_images/warning.png' hspace='5px' width='85' height='85'>&nbsp;</td>
      <td>".AFF_ERROR_TEXT_HEAD."
        <p><b>$e_message</b></p>
        <p>".ERROR_TEXT."</p></td>
    </tr>
  <tr>
    <td colspan='2'>&nbsp;</td>
  </tr>
  </table>");
     echo $skin->ShowFooter($area);
        exit;
}
?>