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
# Name: 		error.php
#
# Description:  Handles errors occuring on the member side
#
# Version:		7.3
#
######################################################################


function error_page($e_message,$type) {
	global $Sess_AffUserId;
	global $Sess_UserType;
	global $Sess_Userlevel;
	global $link;
	global $CONST_LINK_ROOT;

	include('../db_connect.php');

    foreach ($GLOBALS as $name=>$value) {
        if (preg_match("/^MENU/",$name)) global $$name;
    }

	# retrieve the template
	$area = 'affiliate';
    $skin =& Skin::GetInstance();

	echo $skin->ShowHeader($area);
	print ("<table width='$CONST_TABLE_WIDTH' align='$CONST_TABLE_ALIGN' border='0' cellspacing='$CONST_TABLE_CELLSPACING' cellpadding='$CONST_TABLE_CELLPADDING'>
  <tr><td class='pageheader'><b class='error'>$type:</b>".AFF_ERROR_TEXT_HEAD."
</td></tr><tr><td><p>$e_message</p><p>".AFF_ERROR_TEXT_TAIL."</p></td></tr></table>
	");
	echo $skin->ShowFooter($area);
	exit;
}
?>