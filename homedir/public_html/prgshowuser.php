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
# Name: 		prgshowuser.php
#
# Description:  displays member adverts sent in cupid mails
#
# # Version:      8.0
#
######################################################################

include('db_connect.php');
include ('error.php');
include ('message.php');
include_once('validation_functions.php');

$txtHandle=sanitizeData($_GET['txtHandle'], 'xss_clean') ;  
$txtPassword=sanitizeData($_GET['txtPassword'], 'xss_clean') ;
$userid=sanitizeData($_GET['userid'], 'xss_clean') ;    

# gives basic validation if the javascript fails to catch
if (empty($txtHandle) || strlen($txtHandle) < 2) {
    $error_message=PRGREGISTER_TEXT33;
    error_page($error_message,GENERAL_USER_ERROR);
}

if (empty($txtPassword) || strlen($txtPassword) < 4) {
    $error_message=AFF_INDEX_ERROR2;
    display_page($error_message,GENERAL_USER_ERROR);
}

# log the user in automatically
$query="SELECT * FROM members WHERE mem_username = '$txtHandle' AND mem_password = '$txtPassword'";
$retval=mysql_query($query,$link) or die(mysql_error()."$query");
$result=mysql_num_rows($retval);

# if no user is found then display error else set the cookie

if ($result < 1) {
    $error_message=AFF_INDEX_ERROR3;
    error_page($error_message,GENERAL_USER_ERROR);
} else {
    $arr_row = mysql_fetch_object($retval);

	if ($arr_row->mem_confirm==0) {
		$error_message=sprintf(LOGIN_ERROR2,$CONST_URL);
		error_page($error_message,GENERAL_USER_ERROR);
	}

    if ($arr_row->mem_suspend=="Y") {
        $error_message=sprintf(LOGIN_ERROR4,$CONST_URL);
        error_page($error_message,GENERAL_USER_ERROR);
    }

    session_start();
    $_SESSION['Sess_UserType']=$arr_row->mem_type;
    $_SESSION['Sess_UserName']=$arr_row->mem_username;
    $_SESSION['Sess_UserId']=$arr_row->mem_userid;
    $_SESSION['Sess_LastVisit']=$arr_row->mem_lastvisit;

    $testdate=date("Y-m-d");
    if ($arr_row->mem_expiredate < $testdate) {
        $_SESSION['Sess_Userlevel']="silver";
    } else {
        $_SESSION['Sess_Userlevel']="gold";
    }	$lastvisit=date('Y/m/d');
    $query="UPDATE members SET mem_lastvisit='$lastvisit' where mem_username = '$txtHandle' AND mem_password = '$txtPassword'";
    $retval=mysql_query($query,$link) or die(mysql_error());

    header("Location: $CONST_LINK_ROOT/prgretuser.php?userid=$userid");
}

?>