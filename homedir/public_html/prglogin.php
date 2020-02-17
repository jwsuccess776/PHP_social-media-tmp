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
# Name: 		prglogin.php
#
# Description:  validates username and password then displays search
#
# # Version:      8.0
#
# Added the instant message launch code 24-01-03
#
######################################################################

include ('db_connect.php');
include_once 'validation_functions.php';

if ($_REQUEST['speeddating'] == 1) {
    include ('speeddating/error.php');
} else {
    include ('pre_error.php');
}

# clean up the input
if ($_COOKIE['txtHandle_c']) {
	$txtHandle=trim($_COOKIE['txtHandle_c']);
	$txtPassword=trim($_COOKIE['txtPassword_c']);
}
if ($_POST['txtHandle']){
	$txtHandle=trim($_POST['txtHandle']);
	$txtPassword=trim($_POST['txtPassword']);
        
        $txtHandle = sanitizeData($txtHandle, 'xss_clean') ;
        $txtPassword = sanitizeData($txtPassword, 'xss_clean') ;
}
# gives basic validation if the javascript fails to catch
if (empty($txtHandle) || strlen($txtHandle) < 6) {
	$error_message=AFF_INDEX_ERROR1;
	error_page($error_message,GENERAL_USER_ERROR);
}
if (empty($txtPassword) || strlen($txtPassword) < 6) {
	$error_message=AFF_INDEX_ERROR2;
	error_page($error_message,GENERAL_USER_ERROR);
}

$conSting=@mysqli_connect(__CONST_DB_HOST,__CONST_DB_USER, __CONST_DB_PASS,__CONST_DB_NAME);
@mysqli_select_db($conSting,__CONST_DB_NAME);

$txtHandle=mysqli_real_escape_string($conSting,$txtHandle);
$txtPassword=mysqli_real_escape_string($conSting,$txtPassword);
$txtPassword = md5($txtPassword);

$query="SELECT * FROM members WHERE mem_username = '$txtHandle' AND mem_password = '$txtPassword'";
$retval=mysqli_query($conSting,$query) or die(mysqli_error());
$result=mysqli_num_rows($retval);
# if no user is found then display error else set the cookie
if ($result < 1) {
	$error_message=LOGIN_ERROR;
	error_page($error_message,GENERAL_USER_ERROR);
} else {
	$arr_row = mysqli_fetch_object($retval);

	if ($arr_row->mem_confirm==0) {
		$error_message=sprintf(LOGIN_ERROR2,$CONST_URL);
		error_page($error_message,GENERAL_USER_ERROR);
	}

    if ($arr_row->mem_suspend=="Y") {
        $error_message=sprintf(LOGIN_ERROR4,$CONST_URL);
        error_page($error_message,GENERAL_USER_ERROR);
    }


	if ($_POST['save']){
		setcookie ("txtHandle_c", $txtHandle,time()+3600*24*356);
		setcookie ("txtPassword_c", $txtPassword,time()+3600*24*356);
	}
	else{
		setcookie ("txtHandle_c", $txtHandle,time()-3600);
		setcookie ("txtPassword_c", $txtHandle,time()-3600);
		$_COOKIE["txtHandle_c"] = '';
		$_COOKIE["txtPassword_c"] = '';
	}
//	session_start();
	$_SESSION['Sess_UserType']=$arr_row->mem_type;
	$_SESSION['Sess_UserName']=$arr_row->mem_username;
	$_SESSION['Sess_UserId']=$arr_row->mem_userid;
	$_SESSION['Sess_LastVisit']=$arr_row->mem_lastvisit;

	unset($_SESSION['Sess_JustRegistered']);
	$testdate=date("Y-m-d");

	if ($arr_row->mem_expiredate < $testdate) {
		$_SESSION['Sess_Userlevel']="silver";
	} else {
		$_SESSION['Sess_Userlevel']="gold";
		# write in the instant message code
		#$template->tpl_header=str_replace("<body","<body onLoad=\"javascript:MDM_openWindow('$CONST_LINK_ROOT/messenger/instantmessage.php?action=new','InstantMessaging','width=550,height=350')\"",$template->tpl_header);
		#$query="UPDATE members SET mem_online='Y' WHERE mem_username = '$txtHandle' AND mem_password = $txtPassword";
		#$retval=mysqli_query($conSting,$query) or die(mysqli_error());
	}
	# update the last visit date
	$lastvisit=date('Y/m/d');
	$query="UPDATE members SET mem_lastvisit='$lastvisit' WHERE mem_username = '$txtHandle' AND mem_password = '$txtPassword'";
	$retval=mysqli_query($conSting,$query) or die(mysqli_error());
	# special code for prgcomeback.php
	if (isset ($comeback)) {
		$expiredate=mktime (0,0,0,date("m") ,date("d")+3,date("Y"));
		$expiredate=date('Y/m/d',$expiredate);
		$query="UPDATE members SET mem_expiredate='$expiredate' WHERE mem_username = '$txtHandle' AND mem_password = '$txtPassword'";
		$retval=mysqli_query($conSting,$query) or die(mysqli_error());
	}
}

if($_SESSION['HISTORY_PAGE'][0])
{
	// Redirect to the previous page.
	save_request();
	header('Location: '.get_prev_page_url());
}
elseif ($_REQUEST['speeddating']==1)
{
    header("Location: $CONST_LINK_ROOT/speeddating/home.php");
}
else
{
	$sql_result = mysqli_query($conSting,'SELECT * FROM adverts WHERE adv_approved != 2 AND adv_userid = '.$_SESSION['Sess_UserId']);
	if(mysqli_num_rows($sql_result) > 0){
		header("Location: $CONST_LINK_ROOT/home.php");
	} else {
		header("Location: $CONST_LINK_ROOT/prgamendad.php");
	}
}

?>