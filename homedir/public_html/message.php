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
# Name: 		message.php
#
# Description:  Handles errors occuring on the member side
#
# # Version:      8.0
#
######################################################################
function display_page($e_message,$type) {
    global $Sess_UserId;
    global $Sess_UserType;
    global $Sess_Userlevel;
    global $CONST_INCLUDE_ROOT;
    global $CONST_LINK_ROOT;
    global $CONST_IMAGE_LANG;
	global $CONST_TABLE_WIDTH;
	global $CONST_TABLE_CELLSPACING;
	global $CONST_TABLE_CELLPADDING;
	global $CONST_COMPANY;
	global $link;
    foreach ($GLOBALS as $name=>$value) {
        if (preg_match("/^MENU/",$name)) global $$name;
    }
    foreach ($GLOBALS as $name=>$value) {
        if (preg_match("/^CONSTS/",$name)) global $$name;
    }
	# retrieve the template

    $skin =& Skin::GetInstance();

	$area='member';
    echo $skin->ShowHeader($area);
	print ("<table width='$CONST_TABLE_WIDTH' border='0' cellspacing='$CONST_TABLE_CELLSPACING' cellpadding='$CONST_TABLE_CELLPADDING'>
    <tr>
      <td colspan='2'>&nbsp;</td>
    </tr>
    <tr>
      <td colspan='2' class='pageheader'>".$type."</td>
    </tr>
  <tr>
    <td width='95px' align='left' valign='top'><img src='$CONST_LINK_ROOT/additional_images/information.png' hspace='5px' width='85' height='85'>&nbsp;</td>
    <td>
	".INFO_HEAD."
	<p><b>$e_message</b></p>
	<p>".INFO_TEXT."</p></td>
  </tr>
  <tr>
    <td colspan='2'>&nbsp;</td>
  </tr>
</table>");
    echo $skin->ShowFooter($area);
	exit;
}

function restrict_demo()
{
	global $DEMO;
	if($DEMO)
	{
		display_page(GENERAL_DEMO_RESTRICTION_ERROR_DESCRIPTION,GENERAL_DEMO_RESTRICTION_ERROR);
		die;
	}
}
?>