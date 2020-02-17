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
# Name: 		prgaddreview.php
#
# Description:  creates or updates profile information from profile.php
#
# Version:		5.0
#
######################################################################

include('../db_connect.php');
include('../session_handler.inc');
include('../error.php');
include('permission.php');

include_once('../validation_functions.php');

$id =sanitizeData($_REQUEST['id'], 'xss_clean');   
$type = sanitizeData($_REQUEST['type'], 'xss_clean'); 
$txtApprove =sanitizeData($_REQUEST['txtApprove'], 'xss_clean');  
if ($txtApprove<> "")
	{
	if ($txtApprove=="1")
		{
		$query="UPDATE reviews set review_text = '$txtReview',
					review_approved = '1' WHERE review_recid = '$recId'";
		if (!mysql_query($query,$link)) {error_page(mysql_error(),"System Error");}
		header("Location: approvereview.php");
		exit;
		}
	elseif ($txtApprove =='0')
		{
		$query = "DELETE FROM reviews WHERE review_recid = '$recId'";
		if (!mysql_query($query,$link)) {error_page(mysql_error(),"System Error");}
		header("Location: approvereview.php");
		exit;
		}
	}
# validate the txtReview field and add it to the adverts table is the field is fine.
$txtReview = formGet('txtReview');
if (strlen($txtReview) < 3 )
	{
	$error_message="Please enter between 3 and 100 characters in the Review field";
	error_page($error_message,"User Error");
	}
$query="INSERT INTO reviews (review_id, review_userid,
						 review_type, review_text, review_createdate)
					VALUES ('$id','$Sess_UserId', '$type','$txtReview',NOW())";
					$retval=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());
					
header("Location: $CONST_LINK_ROOT/viewevent.php?eventid=$id");
exit;
?>