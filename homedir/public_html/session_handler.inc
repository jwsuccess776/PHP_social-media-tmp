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
# Name:         session_handler.php
#
# Description:  Sends offer mails to people who have not visited for a while
#
# Version:      7.2
#
######################################################################

if (!isset($_SESSION['Sess_UserId'])
    /* Prevent the login page while registering */
    || (isset($_SESSION['Sess_JustRegistered']) && !preg_match('/[^?]*(prgregister\.php|advertise\.php|about\.php|contact\.php|disclaimer\.php|privacy\.php|prgadvertise\.php|profile\.php|prgprofile\.php)(\?.*)?/i', $_SERVER['PHP_SELF'])))
{
    // Save current request and redirect to the login page.
    save_request();
    header("Location: $CONST_LINK_ROOT/login.php?redir=1");
    exit;
}
$Sess_UserId=$_SESSION['Sess_UserId']; $Sess_UserType=$_SESSION['Sess_UserType'];
$Sess_UserName=$_SESSION['Sess_UserName'];

$sess_exp_date = $db->get_var("SELECT mem_expiredate FROM members WHERE mem_userid = '$Sess_UserId'");
$testdate=date("Y-m-d");

if ($sess_exp_date < $testdate) {
	$_SESSION['Sess_Userlevel']="silver";
} else {
	$_SESSION['Sess_Userlevel']="gold";
}	
$Sess_Userlevel=$_SESSION['Sess_Userlevel'];

require_once ( $CONST_INCLUDE_ROOT.'/prem_func.inc.php' );

?>