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
function prem_page($e_message1, $e_message2, $type) {

    global $Sess_UserId;
    global $Sess_UserType;
    global $Sess_Userlevel;
	global $CONST_TABLE_WIDTH;
	global $CONST_TABLE_CELLSPACING;
	global $CONST_TABLE_CELLPADDING;
    global $CONST_INCLUDE_ROOT;
    global $CONST_LINK_ROOT;
    global $CONST_IMAGE_LANG;
    global $link;
    foreach ($GLOBALS as $name=>$value) {
        if (preg_match("/^MENU/",$name)) global $$name;
    }
        $skin = &Skin::GetInstance();

        # retrieve the template
		$area = 'member';

		# get the premium functions
        $query="SELECT prf_name, prf_uri FROM premium_func WHERE prf_active = 1";
        $retval=mysql_query($query,$link) or die(mysql_error());

        echo $skin->ShowHeader($area);
        print ("<table width='$CONST_TABLE_WIDTH' border='0' cellspacing='$CONST_TABLE_CELLSPACING' cellpadding='$CONST_TABLE_CELLPADDING'>
				<tr><td>&nbsp;</td></tr><tr><td>"."<tr><td>".$e_message1."</td></tr><tr><td>");
				while($p_funcs = mysql_fetch_object($retval)) {
					print("<ul type='square' style='margin-bottom:0px; line-height:10px'><li>$p_funcs->prf_name</li></ul>");
				}
		print("</td></tr><tr><td>".$e_message2."</td></tr><tr><td>&nbsp;</td></tr></table>");
        echo     $skin->ShowFooter($area);
        exit;
}
?>