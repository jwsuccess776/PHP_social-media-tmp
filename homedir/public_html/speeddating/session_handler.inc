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
# Name:         session_handler.php
#
# Description:
#
# Version:      7.2
#
######################################################################
if (!isset($_SESSION['Sess_UserId'])
    /* Prevent the login page while registering */
    || (isset($_SESSION['Sess_JustRegistered'])
        && !preg_match('/[^?]*(prgregister\.php|advertise\.php|prgadvertise\.php|profile\.php|prgprofile\.php)(\?.*)?/i', $_SERVER['PHP_SELF'])
       )
    )
{
    // Save current request and redirect to the login page.
    save_request();
    //header("Location: $CONST_LINK_ROOT/speeddating/login.php?redir=1");
    header("Location: $CONST_LINK_ROOT/login.php?redir=1&speeddating=1");
    exit;
}
$Sess_UserId=$_SESSION['Sess_UserId']; $Sess_UserType=$_SESSION['Sess_UserType'];
$Sess_Userlevel=$_SESSION['Sess_Userlevel']; $Sess_UserName=$_SESSION['Sess_UserName'];

include('../prem_func.inc.php');
?>