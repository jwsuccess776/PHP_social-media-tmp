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
# Name:                 prgadvertise.php
#
# Description:  Processesadvert input from advertise.php
#
# Version:                7.3
#
######################################################################

include('db_connect.php');
include('session_handler.inc');
include('error.php');
include('imagesizer.php');
include('message.php');
include('functions.php');
include_once('validation_functions.php');

include(__INCLUDE_CLASS_PATH.'/class.StaticProfile.php');

$mode=  $_POST['mode'];
$client_ip = $_SERVER["REMOTE_ADDR"]." ".$_SERVER["HTTP_X_FORWARDED_FOR"];
$txtLocation= sanitizeData($_POST['txtLocation'], 'xss_clean') ;  
$lstSeeking=sanitizeData($_POST['lstSeeking'], 'xss_clean') ;  
if (!$GEOGRAPHY_JAVASCRIPT){
    $aCountry= explode(";",$_POST['lstCountry']);
    $lstCountry=$aCountry[0];
    $lstState=$aCountry[1];
} else {
    $lstCountry=sanitizeData($_POST['lstCountry'], 'xss_clean') ;   
    $lstState= sanitizeData($_POST['lstState'], 'xss_clean') ;   
}
$lstCity=sanitizeData($_POST['lstCity'], 'xss_clean') ;  
$lstSmoker=sanitizeData($_POST['lstSmoker'], 'xss_clean') ;
$lstDrink=sanitizeData($_POST['lstDrink'], 'xss_clean') ;  
$lstBodyType=sanitizeData($_POST['lstBodyType'], 'xss_clean') ; 
$lstChildren=sanitizeData($_POST['lstChildren'], 'xss_clean') ; 
$lstMarital=sanitizeData($_POST['lstMarital'], 'xss_clean') ;  
$lstReligion=sanitizeData($_POST['lstReligion'], 'xss_clean') ; 
$lstEthnicity=sanitizeData($_POST['lstEthnicity'], 'xss_clean') ;
$lstEyecolor=sanitizeData($_POST['lstEyecolor'], 'xss_clean') ;  
$lstHaircolor=sanitizeData($_POST['lstHaircolor'], 'xss_clean') ;
$lstEducation=sanitizeData($_POST['lstEducation'], 'xss_clean') ;
$lstHeight=sanitizeData($_POST['lstHeight'], 'xss_clean') ; 
$lstEmployment=sanitizeData($_POST['lstEmployment'], 'xss_clean') ;  
$lstIncome=sanitizeData($_POST['lstIncome'], 'xss_clean') ; 
$txtTitle=strip_tags(sanitizeData($_POST['txtTitle'], 'xss_clean'));
$txtComment=strip_tags(sanitizeData($_POST['txtComment'], 'xss_clean'));
$txtComment=one_wordwrap($txtComment,'30');

if ($CONST_ZIPCODES=='Y') {
        $txtZipcode=sanitizeData($_POST['txtZipcode'], 'xss_clean') ; 
} else {
        $txtZipcode="";
}
if (isset($_POST['chkSeekmen'])) $chkSeekmen=sanitizeData($_POST['chkSeekmen'], 'xss_clean') ;   
if (isset($_POST['chkSeekwmn'])) $chkSeekwmn=sanitizeData($_POST['chkSeekwmn'], 'xss_clean') ;  
if (isset($_POST['chkSeekcpl'])) $chkSeekcpl=sanitizeData($_POST['chkSeekcpl'], 'xss_clean') ;   
$chkDelPhoto =sanitizeData($_POST['chkDelPhoto'], 'xss_clean') ;   

# My match variables
$lstMySex= sanitizeData($_POST['lstMySex'], 'xss_clean') ;  
$lstMySmoker=sanitizeData($_POST['lstMySmoker'], 'xss_clean') ;
$txtMyFromAge=sanitizeData($_POST['txtMyFromAge'], 'xss_clean') ; 
$txtMyToAge=sanitizeData($_POST['txtMyToAge'], 'xss_clean') ;  
$lstMyMinHeight=sanitizeData($_POST['lstMyMinHeight'], 'xss_clean') ;  
$lstMyMaxHeight=sanitizeData($_POST['lstMyMaxHeight'], 'xss_clean') ;  
$lstMySeeking=sanitizeData($_POST['lstMySeeking'], 'xss_clean') ;  
$txtMyComment=strip_tags(sanitizeData($_POST['txtMyComment'], 'xss_clean')) ; 
$txtMyComment=one_wordwrap($txtMyComment,'30');
$lstMyBodyType=sanitizeData($_POST['lstMyBodyType'], 'xss_clean') ;  

# gives basic validation if the javascript fails to catch the error
if ($lstCountry == "0") {
        $error_message=PRGADVERTISE_TEXT1;
        error_page($error_message,GENERAL_USER_ERROR);
}
if (trim($lstCountry) == "") {
        $error_message=PRGADVERTISE_TEXT1;
        error_page($error_message,GENERAL_USER_ERROR);
}
$conSting=@mysqli_connect(__CONST_DB_HOST,__CONST_DB_USER, __CONST_DB_PASS,__CONST_DB_NAME);

if ($CONST_ZIPCODES=='Y') {
        if (trim($txtZipcode) != "") {
                // Check for valid areacode
                $sql = "SELECT zip_latitude,zip_longitude FROM zipcodes WHERE zip_zipcode = '$txtZipcode' LIMIT 1";
                //echo $sql;
                $result=mysqli_query($conSting,$sql);
                if (mysqli_num_rows($result) < 1) {
                        $error_message=PRGADVERTISE_TEXT4;
                        error_page($error_message,GENERAL_USER_ERROR);
                }
        }
}
if ($lstSeeking == "- Choose -") {
        $error_message=PRGADVERTISE_TEXT5;
        error_page($error_message,GENERAL_USER_ERROR);
}

if (!$GEOGRAPHY_JAVASCRIPT && (strlen($txtLocation) < 2 || strlen($txtLocation) > 30)) {
        $error_message=PRGADVERTISE_TEXT2;
        error_page($error_message,GENERAL_USER_ERROR);
}

if ($lstBodyType == "- Choose -") {
        $error_message=PRGADVERTISE_TEXT6;
        error_page($error_message,GENERAL_USER_ERROR);
}
if ($lstHeight == "- Choose -") {
        $error_message=PRGADVERTISE_TEXT7;
        error_page($error_message,GENERAL_USER_ERROR);
}
if ($lstChildren == "- Choose -") {
        $error_message=PRGADVERTISE_TEXT8;
        error_page($error_message,GENERAL_USER_ERROR);
}
if ($lstSmoker == "- Choose -") {
        $error_message=PRGADVERTISE_TEXT9;
        error_page($error_message,GENERAL_USER_ERROR);
}
if ($lstDrink == "- Choose -") {
    $error_message=PRGADVERTISE_TEXT10;
    error_page($error_message,GENERAL_USER_ERROR);
}
if ($lstReligion == "- Choose -") {
        $error_message=PRGADVERTISE_TEXT11;
        error_page($error_message,GENERAL_USER_ERROR);
}
if ($lstMarital == "- Choose -") {
        $error_message=PRGADVERTISE_TEXT12;
        error_page($error_message,GENERAL_USER_ERROR);
}
if ($lstEthnicity == "- Choose -") {
        $error_message=PRGADVERTISE_TEXT13;
        error_page($error_message,GENERAL_USER_ERROR);
}
if ($lstEyecolor == "- Choose -") {
    $error_message=PRGADVERTISE_TEXT31;
    error_page($error_message,GENERAL_USER_ERROR);
}
if ($lstHaircolor == "- Choose -") {
    $error_message=PRGADVERTISE_TEXT32;
    error_page($error_message,GENERAL_USER_ERROR);
}
if ($lstEducation == "- Choose -") {
        $error_message=PRGADVERTISE_TEXT14;
        error_page($error_message,GENERAL_USER_ERROR);
}
if ($lstEmployment == "- Choose -") {
        $error_message=PRGADVERTISE_TEXT15;
        error_page($error_message,GENERAL_USER_ERROR);
}
if ($lstIncome == "- Choose -") {
        $error_message=PRGADVERTISE_TEXT16;
        error_page($error_message,GENERAL_USER_ERROR);
}
if ((! isset($chkSeekmen)) && (! isset($chkSeekwmn)) && (! isset($chkSeekcpl))) {
        $error_message=PRGADVERTISE_TEXT17;
        error_page($error_message,GENERAL_USER_ERROR);
}
if (strlen($txtTitle) < 5 || strlen($txtTitle) > 30) {
        $error_message=PRGADVERTISE_TEXT18;
        error_page($error_message,GENERAL_USER_ERROR);
}
if (strlen($txtComment) < 120) {
        $error_message=PRGADVERTISE_TEXT19;
        error_page($error_message,GENERAL_USER_ERROR);
}
if (strlen($txtComment) > 4000) {
        $error_message=PRGADVERTISE_TEXT20;
        error_page($error_message,GENERAL_USER_ERROR);
}

$max_size=$option_manager->GetValue('maxpicsize');

# checks to see if a member exists and extracts certain info for use in the advert
$tempDate=date("Y-m-d H:i:s"); // this is used as the create/update date of the advert
$query="SELECT mem_userid, mem_dob, mem_username, mem_sex, mem_expiredate FROM members WHERE mem_userid = '$Sess_UserId'";
if (! $result=mysqli_query($conSting,$query)) {
        error_page(mysqli_error($conSting),GENERAL_SYSTEM_ERROR);
}
if (mysqli_num_rows($result) < 1) {
        error_page(PRGADVERTISE_TEXT21,GENERAL_SYSTEM_ERROR);
} else {
        # extract member information from members table
        $sql_array=mysqli_fetch_object($result);
        $tempdob=$sql_array->mem_dob;
        $tempusername=$sql_array->mem_username;
        $tempsex=$sql_array->mem_sex;
        $tempid=$sql_array->mem_userid;
        $tempexpire=$sql_array->mem_expiredate;
        $tempseekmen='N'; $tempseekwmn='N'; $tempseekcpl='N';
        if (isset($chkSeekmen)) {$tempseekmen='Y';}
        if (isset($chkSeekwmn)) {$tempseekwmn='Y';}
        if (isset($chkSeekcpl)) {$tempseekcpl='Y';}
}

# check whether immediate authorisation
$approved=$option_manager->GetValue('authorisead');
$txtComment= mysqli_escape_string ($conSting,$txtComment);
$txtTitle=mysqli_escape_string($conSting,$txtTitle);
$expiredate=mktime (0,0,0,date("m") ,date("d")-1,date("Y"));
$expiredate=date('Y-m-d',$expiredate);
if ($tempexpire > $expiredate) {
        $expiredate=$tempexpire;
}

if ($mode=='update') {
		$query="UPDATE adverts SET
				adv_zipcode='$txtZipcode',
				adv_title = '$txtTitle',
				adv_smoker = '$lstSmoker',
				adv_drink = '$lstDrink',
				adv_children = '$lstChildren',
				adv_comment = '$txtComment',
				adv_countryid = '$lstCountry',
				adv_stateid = '$lstState',
                adv_cityid = '$lstCity',
                adv_location = '$txtLocation',
				adv_height = '$lstHeight',
				adv_marital = '$lstMarital',
				adv_bodytype = '$lstBodyType',
				adv_approved = '$approved',
				adv_createdate = '$tempDate',
				adv_ethnicity = '$lstEthnicity',
				adv_religion = '$lstReligion',
				adv_education = '$lstEducation',
				adv_Income = '$lstIncome',
				adv_profession= '$lstEmployment',
				adv_seeking = '$lstSeeking',
				adv_picture = '$targetfile',
				adv_expiredate = '$expiredate',
				adv_ip = '$client_ip',
				adv_seekmen = '$tempseekmen',
				adv_seekwmn = '$tempseekwmn',
				adv_seekcpl = '$tempseekcpl',
				adv_eyecolor = '$lstEyecolor',
				adv_haircolor = '$lstHaircolor'
				WHERE adv_userid = '$Sess_UserId'";
	if (!mysqli_query($conSting,$query)) {error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);}

	$txtMyComment=mysqli_escape_string($conSting,$txtMyComment);
	$query="REPLACE mymatch SET mym_gender='$lstMySex',
				mym_smoker='$lstMySmoker',
				mym_comment='$txtMyComment',
				mym_minheight='$lstMyMinHeight',
				mym_maxheight='$lstMyMaxHeight',
				mym_bodytype='$lstMyBodyType',
				mym_agemin='$txtMyFromAge',
				mym_agemax='$txtMyToAge',
				mym_relationship='$lstMySeeking',
				mym_userid=$Sess_UserId";

	mysqli_query($conSting,$query) or die(mysqli_error());
} else {
	$query="INSERT INTO adverts
			(adv_zipcode, adv_userid, adv_username, adv_smoker, adv_drink, adv_children, adv_dob, adv_comment,  adv_countryid, adv_stateid, adv_cityid, adv_location, adv_height, adv_marital, adv_bodytype, adv_ethnicity, adv_religion, adv_sex, adv_seeking, adv_picture, adv_createdate, adv_seekmen, adv_seekwmn,adv_seekcpl, adv_approved, adv_title, adv_profession,adv_income,adv_education, adv_expiredate, adv_views, adv_eyecolor, adv_haircolor, adv_ip)
			VALUES
			('$txtZipcode', '$tempid','$tempusername','$lstSmoker','$lstDrink','$lstChildren','$tempdob','$txtComment', '$lstCountry','$lstState','$lstCity', '$txtLocation','$lstHeight', '$lstMarital','$lstBodyType','$lstEthnicity','$lstReligion','$tempsex','$lstSeeking', '$targetfile', '$tempDate', '$tempseekmen', '$tempseekwmn','$tempseekcpl', '$approved', '$txtTitle' ,'$lstEmployment','$lstIncome','$lstEducation','$expiredate',0,'$lstEyecolor','$lstHaircolor','$client_ip')";

	if (!mysqli_query($conSting,$query)) {
			if (mysqli_errno($conSting) == 1062) {
					$query="UPDATE adverts  SET
					adv_zipcode='$txtZipcode',
					adv_title = '$txtTitle',
					adv_smoker = '$lstSmoker',
					adv_drink = '$lstDrink',
					adv_children = '$lstChildren',
					adv_comment = '$txtComment',
					adv_countryid = '$lstCountry',
					adv_stateid = '$lstState',
					adv_cityid = '$lstCity',
                    adv_location = '$txtLocation',
					adv_height = '$lstHeight',
					adv_marital = '$lstMarital',
					adv_bodytype = '$lstBodyType',
					adv_approved = '$approved',
					adv_createdate = '$tempDate',
					adv_ethnicity = '$lstEthnicity',
					adv_religion = '$lstReligion',
					adv_education = '$lstEducation',
					adv_Income = '$lstIncome',
					adv_profession= '$lstEmployment',
					adv_seeking = '$lstSeeking',
					adv_picture = '$targetfile',
					adv_expiredate = '$expiredate',
					adv_seekmen = '$tempseekmen',
					adv_ip = '$client_ip',
					adv_seekwmn = '$tempseekwmn',
				adv_seekcpl = '$tempseekcpl',
					adv_eyecolor = '$lstEyecolor',
					adv_haircolor = '$lstHaircolor'
					WHERE adv_userid = '$Sess_UserId'";
					if (!mysqli_query($conSting,$query)) {error_page(mysqli_error($conSting),GENERAL_SYSTEM_ERROR);}

			}else{
					error_page(mysqli_error($conSting),GENERAL_SYSTEM_ERROR);
			}
	}

	$txtMyComment=mysqli_escape_string($conSting,$txtMyComment);

	$query="INSERT INTO mymatch (mym_userid, mym_gender, mym_smoker, mym_comment, mym_minheight, mym_maxheight, mym_bodytype, mym_agemin, mym_agemax, mym_relationship)
					VALUES ($Sess_UserId, '$lstMySex', '$lstMySmoker', '$txtMyComment', '$lstMyMinHeight', '$lstMyMaxHeight', '$lstMyBodyType','$txtMyFromAge','$txtMyToAge', '$lstMySeeking')";
	mysqli_query($conSting,$query) or die(mysqli_error($conSting));
}
	include("generate_profile.php");

header("Location: $CONST_LINK_ROOT/profile.php");
exit;
?>