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
# Name: 		message.php
#
# Description:  Handles errors occuring on the member side
#
# # Version:      8.0
#
######################################################################

function display_page($e_message,$type) {

	global $Sess_AffUserId;
	global $Sess_UserType;
	global $Sess_Userlevel;
	global $link;
	global $CONST_LINK_ROOT;
	include('../db_connect.php');

	# retrieve the template
    $skin =& Skin::GetInstance();

	$area = 'affiliate';
	echo $skin->ShowHeader($area);

	print ("<table width='$CONST_TABLE_WIDTH' align='$CONST_TABLE_ALIGN' border='0' cellspacing='$CONST_TABLE_CELLSPACING' cellpadding='$CONST_TABLE_CELLPADDING'>
 <tr><td class='pageheader'>$type</td></tr><tr><td>$e_message</td></tr></table>");
	echo $skin->ShowFooter($area);
	exit;
}

?>