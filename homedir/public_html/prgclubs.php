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
# Name: 		prgclub.php
#
# Description:  creates or updates profile information from profile.php
#
# # Version:      8.0
#
######################################################################

include('db_connect.php');
include('session_handler.inc');
include('error.php');
include('permission.php');
include_once('validation_functions.php');

$txtClubId =sanitizeData($_POST['txtClubId'], 'xss_clean') ;    
$txtClubName=sanitizeData($_POST['txtClubName'], 'xss_clean') ; 
$txtAddress=sanitizeData($_POST['txtAddress'], 'xss_clean') ;  
$txtCity=sanitizeData($_POST['txtCity'], 'xss_clean') ;    
$txtCountry=sanitizeData($_POST['txtCountry'], 'xss_clean') ;
$txtWebsite=sanitizeData($_POST['txtWebsite'], 'xss_clean') ;
$txtDesc =sanitizeData($_POST['txtDesc'], 'xss_clean') ;    
$txtPhone =sanitizeData($_POST['txtPhone'], 'xss_clean') ;  
$txtApprove =sanitizeData($_POST['txtApprove'], 'xss_clean') ;
if ($txtApprove<> "")
	{
	if ($txtApprove=="1")
		{
		$query="UPDATE clubs set cl_clubname = '$txtClubName',
					cl_address = '$txtAddress',
					cl_city = '$txtCity',
					cl_country = '$txtCountry',
					cl_phone = '$txtPhone',
					cl_website = '$txtWebsite',
					cl_description = '$txtDesc',
					cl_approved = '1' WHERE cl_clubid = '$txtClubId'";
		if (!mysql_query($query,$link)) {error_page(mysql_error(),"System Error");}
		header("Location: approveclub.php");
		exit;
		}
	elseif ($txtApprove =='0')
		{
		$query = "DELETE FROM clubs WHERE cl_clubid = '$txtClubId'";
		if (!mysql_query($query,$link)) {error_page(mysql_error(),"System Error");}
		header("Location: approveclub.php");
		exit;
		}
	}

$max_size=$option_manager->GetValue('maxpicsize');
# validate the txtTitle field and add it to the adverts table is the field is fine.
if (strlen($txtClubName) < 3 )
	{
	$error_message="Please enter between 3 and 30 characters in the Club Name field";
	error_page($error_message,"User Error");
	}
elseif (strlen(txtAddress) < 3)
	{
	$error_message="Please enter between 3 and 50 characters in the Address Name field";
	error_page($error_message,"User Error");
	}
elseif (strlen(txtCity) < 3)
	{
	$error_message="Please enter between 3 and 20 characters in the City Name field";
	error_page($error_message,"User Error");
	}
elseif (strlen(txtDescription) < 3)
	{
	$error_message="Please enter between 3 and 50 characters in the Description field";
	error_page($error_message,"User Error");
	}
//--- Check the File Upload for the Main image..
	if ($_FILES['mainfupload']['size'] != 0) {
		if ($_FILES['mainfupload']['size'] > $max_size) {
	        $max_size=$max_size/1000;
			error_page("Pictures must be less than $max_size Kb. Please click back and select a smaller picture.","User Error");
		}
		if ($_FILES['mainfupload']['type'] == "image/gif" || $_FILES['mainfupload']['type'] == "image/pjpeg" || $_FILES['mainfupload']['type'] == "image/jpeg") {
			if ( $_FILES['mainfupload']['type'] == "image/gif" ) { $extension=".gif"; }
			if ( $_FILES['mainfupload']['type'] == "image/pjpeg" ) { $extension=".jpg"; }
			if ( $_FILES['mainfupload']['type'] == "image/jpeg" ) { $extension=".jpg"; }
			$filename=str_replace(" ","","$txtClubName")."$extension";
			$targetfile=$CONST_INCLUDE_ROOT."/clubs/"."$filename";
			copy($_FILES['mainfupload']['tmp_name'],"$targetfile");
			$targetfile="clubs/"."$filename";
		} else {
			error_page("Pictures must be either GIF or JPG format.","User Error");
		}
		}
$query="INSERT INTO clubs (cl_clubname,
						cl_address, cl_city, cl_country,
						cl_phone, cl_website, cl_description, cl_picture)
					VALUES ('$txtClubName','$txtAddress','$txtCity','$txtCountry','$txtPhone',
						'$txtWebsite','$txtDesc',
						'$targetfile')";
		if (!mysql_query($query,$link)) {error_page(mysql_error(),"System Error");}
mysql_close( $link );
header("Location: clubs.php");
exit;
?>