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
# Name:                 prgregister.php
#
# Description:  creates or updates registration information
#
# Version:                7.3
#
######################################################################

include('db_connect.php');
include_once 'validation_functions.php';
$is_speeddating =sanitizeData($_POST['speeddating'], 'xss_clean');
if($is_speeddating)
        include('speeddating/error.php');
else
        include('pre_error.php');
include('imagesizer.php');
include('message.php');
include('functions.php');


$_SESSION["post"] = $_POST;
$client_ip = $_SERVER["REMOTE_ADDR"]." ".$_SERVER["HTTP_X_FORWARDED_FOR"];

setcookie("lstCountry", sanitizeData(trim($_POST['lstCountry']), 'xss_clean'));
setcookie("lstState",  sanitizeData(trim($_POST['lstState']), 'xss_clean'));
setcookie("lstCity",  sanitizeData(trim($_POST['lstCity']), 'xss_clean'));

$mode=$_GET['mode'];
$txtSurname= sanitizeData(trim($_POST['txtSurname']), 'xss_clean') ; 
$txtForename=sanitizeData(trim($_POST['txtForename']), 'xss_clean') ;    
$lstDay=sanitizeData(trim($_POST['lstDay']), 'xss_clean') ; 
$lstMonth= sanitizeData(trim($_POST['lstMonth']), 'xss_clean') ;  
$lstSex=sanitizeData(trim($_POST['lstSex']), 'xss_clean') ;  
$security=sanitizeData(trim($_POST['security']), 'xss_clean') ;  
$txtEmail=sanitizeData(trim($_POST['txtEmail']), 'xss_clean') ;  
$txtYear=sanitizeData(trim($_POST['txtYear']), 'xss_clean') ;  
if (isset($_POST['chkNews'])) $chkNews=sanitizeData(trim($_POST['chkNews']), 'xss_clean') ; 
if (isset($_POST['chkDisclaimer'])) $chkDisclaimer=sanitizeData(trim($_POST['chkDisclaimer']), 'xss_clean') ; 
$txtConfirm=sanitizeData(trim($_POST['txtConfirm']), 'xss_clean') ;  
$txtPassword=sanitizeData(trim($_POST['txtPassword']), 'xss_clean') ;  
$lstSkypeSettings=sanitizeData(trim($_POST['lstSkypeSettings']), 'xss_clean') ;  
$txtSkypename=sanitizeData(trim($_POST['txtSkypename']), 'xss_clean') ; 

$txtMobile =sanitizeData(trim($_POST['txtMobile']), 'xss_clean') ;   
$lstSmsCarrier =sanitizeData(trim($_POST['lstSmsCarrier']), 'xss_clean') ;  

# retrieve the template
if($is_speeddating){
    $area = 'speeddating';
} elseif ($_SESSION['Sess_JustRegistered']==true) {
    $area = 'guest';
} else {
    $area = 'member';
}
if ($mode!='create') {
        // session_cache_limiter('private, must-revalidate');
        session_id("private");
        session_start();
}

$conStingreg=@mysqli_connect(__CONST_DB_HOST,__CONST_DB_USER, __CONST_DB_PASS,__CONST_DB_NAME);
@mysqli_select_db($conStingreg,__CONST_DB_NAME);

# if mode is create then there are extra fields to validate
if ($mode=='create') {
        $txtHandle=sanitizeData(trim($_POST['txtHandle']), 'xss_clean') ;  
        if (isset ($_POST['chkDisclaimer'])) $chkDisclaimer=sanitizeData(trim($_POST['chkDisclaimer']), 'xss_clean') ; 

        if ($security != $_SESSION['securityCode'] && $SECURITY_REGISTRATION) {
                $error_message=PRGREGISTER_TEXT39;
                error_page($error_message,GENERAL_USER_ERROR, $mode);
        }

        if (strstr($txtHandle," ")) {
                $error_message=PRGREGISTER_TEXT32;
                error_page($error_message,GENERAL_USER_ERROR, $mode);
        }
        if ((empty($txtHandle) || strlen($txtHandle) < 6)) {
                        $error_message=PRGREGISTER_TEXT33;
                        error_page($error_message,GENERAL_USER_ERROR, $mode);
        }
        if (strlen($txtHandle) > 25 && $mode=='create' ) {
                        $error_message=PRGREGISTER_TEXT34;
                        error_page($error_message,GENERAL_USER_ERROR, $mode);
        }
        if ($txtHandle == 'genericc' || $txtHandle == 'genericm' || $txtHandle == 'genericf') {
                        $error_message=PRGREGISTER_TEXT35;
                        error_page($error_message,GENERAL_USER_ERROR, $mode);
        }
}
# for create and update these fields require validation
$txtPassword=trim($txtPassword);
if (empty($txtPassword) || strlen($txtPassword) < 6) {
        $error_message=PRGREGISTER_TEXT1;
        error_page($error_message,GENERAL_USER_ERROR, $mode);
}
$txtConfirm=trim($txtConfirm);
if (empty($txtConfirm) || strlen($txtConfirm) < 6) {
        $error_message=PRGREGISTER_TEXT2;
        error_page($error_message,GENERAL_USER_ERROR, $mode);
}
$txtConfirm=trim($txtConfirm);
if ($txtPassword != $txtConfirm) {
        $error_message=PRGREGISTER_TEXT3 ;
        error_page($error_message,GENERAL_USER_ERROR, $mode);
}
$txtSurname=trim($txtSurname);
if (empty($txtSurname) || strlen($txtSurname) < 2) {
        $error_message=PRGREGISTER_TEXT4;
        error_page($error_message,GENERAL_USER_ERROR, $mode);
}
if (strlen($txtSurname) > 25 ) {
                $error_message=PRGREGISTER_TEXT5;
                error_page($error_message,GENERAL_USER_ERROR, $mode);
}
$txtForename=trim($txtForename);
if (empty($txtForename) || strlen($txtForename) < 2) {
        $error_message=PRGREGISTER_TEXT6;
        error_page($error_message,GENERAL_USER_ERROR, $mode);
}
if (strlen($txtForename) > 25 ) {
                $error_message=PRGREGISTER_TEXT7;
                error_page($error_message,GENERAL_USER_ERROR, $mode);
}
if ($lstDay == "...") {
        $error_message=PRGREGISTER_TEXT8;
        error_page($error_message,GENERAL_USER_ERROR, $mode);
}
if ($lstMonth == "...") {
                $error_message=PRGREGISTER_TEXT9;
                error_page($error_message,GENERAL_USER_ERROR, $mode);
}
if ($txtYear == "...") {
        $error_message=PRGREGISTER_TEXT10;
        error_page($error_message,GENERAL_USER_ERROR, $mode);
}
// Calculate age
$testdate=date("Ymd");
$dobdate=$txtYear.$lstMonth.$lstDay;
$testage = (int) (( $testdate - $dobdate ) / 10000);
if ($testage < 18 ) {
    $error_message=PRGREGISTER_TEXT11;
    error_page($error_message,GENERAL_USER_ERROR, $mode);
}

if ($lstSex == "- Choose -") {
        $error_message=PRGREGISTER_TEXT12;
        error_page($error_message,GENERAL_USER_ERROR, $mode);
}
$txtEmail=trim($txtEmail);
if (empty($txtEmail) || strlen($txtEmail) < 2) {
        $error_message=PRGREGISTER_TEXT36;
        error_page($error_message,GENERAL_USER_ERROR, $mode);
}
if ($mode=='create') {
        if (! isset ($chkDisclaimer))
        {
           $error_message=PRGREGISTER_TEXT15;
           error_page($error_message,GENERAL_USER_ERROR, $mode);
        }
}
# if mode is create and it is not speeddating registration
# then there are advert fields to validate
if ($mode=='create' && !$is_speeddating) {

        $txtLocation=sanitizeData(trim($_POST['txtLocation']), 'xss_clean') ;  
        $lstSeeking=sanitizeData(trim($_POST['lstSeeking']), 'xss_clean') ;   

if (!$GEOGRAPHY_JAVASCRIPT){
    $aCountry= explode(";",$_POST['lstCountry']);
    $lstCountry=sanitizeData($aCountry[0], 'xss_clean') ;  
    $lstState= sanitizeData($aCountry[1], 'xss_clean') ;
} else {
    $lstCountry=sanitizeData(trim($_POST['lstCountry']), 'xss_clean') ;   
    $lstState=sanitizeData(trim($_POST['lstState']), 'xss_clean') ;   
}
        $lstCity=sanitizeData(trim($_POST['lstCity']), 'xss_clean') ;     
        $lstSmoker=sanitizeData(trim($_POST['lstSmoker']), 'xss_clean') ;  
        $lstDrink=sanitizeData(trim($_POST['lstDrink']), 'xss_clean') ;  
        $lstBodyType=sanitizeData(trim($_POST['lstBodyType']), 'xss_clean') ;  
        $lstChildren=sanitizeData(trim($_POST['lstChildren']), 'xss_clean') ;  
        $lstMarital=sanitizeData(trim($_POST['lstMarital']), 'xss_clean') ;  
        $lstReligion=sanitizeData(trim($_POST['lstReligion']), 'xss_clean') ;
        $lstEthnicity=sanitizeData(trim($_POST['lstEthnicity']), 'xss_clean') ;
        $lstEducation=sanitizeData(trim($_POST['lstEducation']), 'xss_clean') ; 
        $lstHeight=sanitizeData(trim($_POST['lstHeight']), 'xss_clean') ;  
        $lstEyecolor=sanitizeData(trim($_POST['lstEyecolor']), 'xss_clean') ;   
        $lstHaircolor=sanitizeData(trim($_POST['lstHaircolor']), 'xss_clean') ;   
        $lstEmployment=sanitizeData(trim($_POST['lstEmployment']), 'xss_clean') ; 
        $lstIncome=sanitizeData(trim($_POST['lstIncome']), 'xss_clean') ; 
        $txtTitle= sanitizeData(trim($_POST['txtTitle']), 'xss_clean') ;   
        $txtTitle=strip_tags($txtTitle);
        $txtComment=  sanitizeData(trim($_POST['txtComment']), 'xss_clean') ;   
        $txtComment=strip_tags($txtComment);
		$txtComment=one_wordwrap($txtComment,'30');

        if ($CONST_ZIPCODES=='Y') {
                $txtZipcode=sanitizeData(trim($_POST['txtZipcode']), 'xss_clean') ;   
        } else {
                $txtZipcode="";
        }
        if (isset($_POST['chkSeekmen'])) $chkSeekmen=sanitizeData(trim($_POST['chkSeekmen']), 'xss_clean') ;
        if (isset($_POST['chkSeekwmn'])) $chkSeekwmn=sanitizeData(trim($_POST['chkSeekwmn']), 'xss_clean') ;
        if (isset($_POST['chkSeekcpl'])) $chkSeekcpl=sanitizeData(trim($_POST['chkSeekcpl']), 'xss_clean') ;

        # My match variables
        $lstMySex=sanitizeData(trim($_POST['lstMySex']), 'xss_clean') ; 
        $txtMyComment=sanitizeData(trim($_POST['txtMyComment']), 'xss_clean') ;
        $lstMySmoker=sanitizeData(trim($_POST['lstMySmoker']), 'xss_clean') ; 
        $txtMyFromAge=sanitizeData(trim($_POST['txtMyFromAge']), 'xss_clean') ; 
        $txtMyToAge=sanitizeData(trim($_POST['txtMyToAge']), 'xss_clean') ; 
        $lstMyMinHeight=sanitizeData(trim($_POST['lstMyMinHeight']), 'xss_clean') ; 
        $lstMyMaxHeight=sanitizeData(trim($_POST['lstMyMaxHeight']), 'xss_clean') ; 
        $lstMySeeking=sanitizeData(trim($_POST['lstMySeeking']), 'xss_clean') ; 
        $lstMyBodyType=sanitizeData(trim($_POST['lstMyBodyType']), 'xss_clean') ; 
        $txtMyComment=sanitizeData(trim($_POST['txtMyComment']), 'xss_clean') ; 
		$txtMyComment=strip_tags($txtMyComment);
		$txtMyComment=one_wordwrap($txtMyComment,'30');

        if ($lstCountry == "0") {
                $error_message=PRGADVERTISE_TEXT1;
                error_page($error_message,GENERAL_USER_ERROR);
        }
        if (trim($lstCountry) == "") {
                $error_message=PRGADVERTISE_TEXT1;
                error_page($error_message,GENERAL_USER_ERROR);
        }

        if($GEOGRAPHY_JAVASCRIPT || $GEOGRAPHY_AJAX){
            if ($lstCity <= "0") {
                    $error_message=PRGADVERTISE_TEXT2;
                    error_page($error_message,GENERAL_USER_ERROR);
            }
            if (trim($lstCity) == "") {
                    $error_message=PRGADVERTISE_TEXT2;
                    error_page($error_message,GENERAL_USER_ERROR);
            }
        } else {
            if (strlen($txtLocation) < 2 || strlen($txtLocation) > 30) {
                    $error_message=PRGADVERTISE_TEXT2;
                    error_page($error_message,GENERAL_USER_ERROR);
            }
        }
        if ($CONST_ZIPCODES=='Y') {
                if (trim($txtZipcode) != "" && strlen($txtZipcode) > 5) {
                        $error_message=PRGADVERTISE_TEXT3;
                        error_page($error_message,GENERAL_USER_ERROR);
                }
                if (trim($txtZipcode) != "") {
                        // Check for valid areacode
                        $sql = "SELECT zip_latitude,zip_longitude FROM zipcodes WHERE zip_zipcode = '$txtZipcode' LIMIT 1";
                        $result=mysqli_query($conStingreg,$sql);
                        if (mysqli_num_rows($result) < 1) {
                                $error_message=PRGADVERTISE_TEXT4;
                                error_page($error_message,GENERAL_USER_ERROR);
                        }
                }
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

}

# if the validation is successful, this registers the user
# removed 23/04/2002 email is now default to HTML
# if ($rdoEmailType[0] == 'H') { $EmailType='H';} else {$EmailType='T';}
$EmailType='H';
$txtPassword = md5($txtPassword);
if ($mode=='create') {
        $tempDate=date("Y/m/d");
        $dob=$txtYear.'/'.$lstMonth.'/'.$lstDay;
        if (! isset($chkNews)) {
                $chkNews=0;
        }
        # check for duplicate username
        $query="SELECT * FROM members WHERE mem_username = '$txtHandle'";
        $retval=mysqli_query($conStingreg,$query) or die(mysqli_error());
        $result=mysqli_num_rows($retval);
        if ($result > 0) {
                $error_message=PRGREGISTER_TEXT16;
                error_page($error_message,GENERAL_USER_ERROR, $mode);
        }
        # check for duplicate email address
        $query="SELECT * FROM members WHERE mem_email = '$txtEmail'";
        $retval=mysqli_query($conStingreg,$query) or die(mysqli_error());
        $result=mysqli_num_rows($retval);
        if ($result > 0) {
                $error_message=PRGREGISTER_TEXT17;
                error_page($error_message,GENERAL_USER_ERROR, $mode);
        }
        # check who referred and action offers

        $freetime = $option_manager->GetValue('freetime');
        $trial_gender = $option_manager->GetValue('trial_gender');
        if ($freetime != -1 && ($trial_gender == 'B' || $trial_gender == $lstSex))
                $expiredate=mktime (0,0,0,date("m") ,date("d")+$freetime,date("Y"));
        else
                $expiredate=mktime (0,0,0,date("m") ,date("d")-1,date("Y"));

        $expiredate=date('Y/m/d',$expiredate);
        if (isset($referrer)) {
                if ($referrer == '101005') {
                        $expiredate=mktime (0,0,0,date("m") ,date("d")+5,date("Y"));
                        $cleandate=date('d/M/Y',$expiredate);
                        $expiredate=date('Y/m/d',$expiredate);
                        $paragraph=PRGREGISTER_TEXT18;
                }
                $paragraph="$expiredate".PRGREGISTER_TEXT20;
        } else {
                $referrer='';
                $paragraph=PRGREGISTER_TEXT20;
        }
        # insert the new member
        $query="INSERT INTO members
           (mem_username,
            mem_password,
            mem_expiredate,
            mem_surname,
            mem_forename,
            mem_email,
            mem_joindate,
            mem_sex,
            mem_dob,
            mem_lastvisit,
            mem_newsletter,
            mem_emailtype,
            mem_update,
            mem_type,
            mem_referrer,
            mem_skype,
            mem_skypeset,
            mem_mobile ,
            mem_carrier,
			mem_sms,
            mem_ip,
            lang_id,
            mem_confirm)
            VALUES
            ('$txtHandle',
            '$txtPassword',
            '$expiredate',
            '$txtSurname',
            '$txtForename',
            '$txtEmail',
            '$tempDate',
            '$lstSex',
            '$dob',
            '$tempDate',
            '$chkNews',
            '$EmailType',
            0,
            'U',
            '$HTTP_COOKIE_VARS[referrer]',
            '$txtSkypename',
            '$lstSkypeSettings',
            '$txtMobile',
            '$lstSmsCarrier',
			'".((strlen($txtMobile) > 7 && $lstSmsCarrier) ? 1:0)."',
            '$client_ip',
            '".$_SESSION['lang_id']."',
            ".($CONST_EMAIL_CONFIRM == 'Y' ? 0 : 1).")";
        if (!mysqli_query($conStingreg,$query)){
                error_page("Failed ".mysqli_error(),GENERAL_SYSTEM_ERROR, $mode);
        } else {
                $mem_id=mysqli_insert_id($conStingreg);
                setcookie("NetSingles","$mem_id",0);
                // session_cache_limiter('private, must-revalidate');
                session_id("private");
                session_start();
                $_SESSION['Sess_JustRegistered']=true;
                $_SESSION['Sess_UserType']="U";
                $Sess_UserName = $_SESSION['Sess_UserName']=$txtHandle;
                $Sess_UserId = $_SESSION['Sess_UserId']=$mem_id;
                $_SESSION['Sess_Userlevel']="silver";

                include_once __INCLUDE_CLASS_PATH."/class.Network.php";
                $network = new Network();
                $network->addInvitedUser($Sess_UserId, $txtEmail);

                //###### Save the advertise information ##########
                # checks to see if a member exists and extracts certain info for use in the advert
                $tempDate=date("Y-m-d H:i:s"); // this is used as the create/update date of the advert
                $query="SELECT mem_userid, mem_dob, mem_username, mem_sex, mem_expiredate FROM members WHERE mem_userid = '$Sess_UserId'";
                if (! $result=mysqli_query($conStingreg,$query)) {
                        error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);
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
                # Insert the new advert
                $txtComment=mysqli_real_escape_string($conStingreg,$txtComment);
                $txtTitle=mysqli_real_escape_string($conStingreg,$txtTitle);
                $expiredate=mktime (0,0,0,date("m") ,date("d")-1,date("Y"));
                $expiredate=date('Y-m-d',$expiredate);
                if ($tempexpire > $expiredate) {
                        $expiredate=$tempexpire;
                }

                $query="INSERT INTO adverts
                        (adv_zipcode, adv_userid, adv_username, adv_smoker, adv_drink, adv_children, adv_dob, adv_comment,  adv_countryid, adv_stateid, adv_cityid, adv_location,adv_height, adv_marital, adv_bodytype, adv_ethnicity, adv_religion, adv_sex, adv_seeking, adv_picture, adv_createdate, adv_seekmen, adv_seekwmn,adv_seekcpl, adv_approved, adv_title, adv_profession,adv_income,adv_education, adv_expiredate, adv_views, adv_eyecolor, adv_haircolor, adv_ip)
                        VALUES
                        ('$txtZipcode', '$tempid','$tempusername','$lstSmoker','$lstDrink','$lstChildren','$tempdob','$txtComment', '$lstCountry','$lstState','$lstCity','$txtLocation', '$lstHeight', '$lstMarital','$lstBodyType','$lstEthnicity','$lstReligion','$tempsex','$lstSeeking', '$targetfile', '$tempDate', '$tempseekmen', '$tempseekwmn','$tempseekcpl', '$approved', '$txtTitle' ,'$lstEmployment','$lstIncome','$lstEducation','$expiredate',0,'$lstEyecolor','$lstHaircolor','$client_ip')";

                if (!mysqli_query($conStingreg,$query)) {
                        if (mysqli_errno($conStingreg) == 1062) {
                                $query="UPDATE adverts  SET
                                adv_zipcode='$txtZipcode',
                                adv_title = '$txtTitle',
								adv_dob = '$tempdob',
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
                                if (!mysqli_query($conStingreg,$query)) {error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);}

                        }else{
                                error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);
                        }
                }

                include("generate_profile.php");

                // $txtMyComment=mysqli_real_escape_string($conStingreg,$txtMyComment);

                $query="INSERT INTO mymatch (mym_userid, mym_gender, mym_smoker, mym_comment, mym_minheight, mym_maxheight, mym_bodytype, mym_agemin, mym_agemax, mym_relationship) VALUES ($Sess_UserId, '$lstMySex', '$lstMySmoker', '$txtMyComment', '$lstMyMinHeight', '$lstMyMaxHeight', '$lstMyBodyType','$txtMyFromAge','$txtMyToAge', '$lstMySeeking')";

                mysqli_query($conStingreg, $query);
                
                mysqli_close($conStingreg);

                unset($_SESSION["post"]);

                if($is_speeddating)
                        header("Location: $CONST_LINK_ROOT/prgprofile.php?speeddating=1");
                else
                        header("Location: $CONST_LINK_ROOT/profile.php");
                exit;
        }
} else {

        if ($Sess_UserName == 'manager') {
            restrict_demo();
        }

        include ('session_handler.inc');
        if (! isset($chkNews)) {
                $chkNews=0;
        }
        $dob=$txtYear.$lstMonth.$lstDay;
        # check for duplicate email address
        $query="SELECT * FROM members WHERE mem_email = '$txtEmail' AND mem_userid != '$Sess_UserId'";
        $retval=mysqli_query($conStingreg,$query) or die(mysqli_error());
        $result=mysqli_num_rows($retval);
        if ($result > 0) {
                $error_message=PRGREGISTER_TEXT24;
                error_page($error_message,GENERAL_USER_ERROR, $mode);
        }
        # update the member
        $query="UPDATE
                members
                SET mem_password='$txtPassword',
                mem_surname='$txtSurname',
                mem_forename='$txtForename',
                mem_email='$txtEmail',
                mem_ip = '$client_ip',
                mem_sex='$lstSex',
                mem_dob='$dob',
                mem_newsletter='$chkNews',
                mem_emailtype='$EmailType',
                mem_skype='$txtSkypename',
                mem_skypeset='$lstSkypeSettings',
                mem_mobile='$txtMobile',
                mem_carrier='$lstSmsCarrier',
                mem_update=0
                where mem_userid = '$Sess_UserId'";
        if (!mysqli_query($conStingreg,$query)){
                error_page("Failed",GENERAL_SYSTEM_ERROR, $mode);
        }
		if(!$is_speeddating){
				# update the advert
				$query="SELECT adv_userid, adv_picture FROM adverts WHERE adv_userid = '$Sess_UserId'";
				$retval=mysqli_query($conStingreg,$query) or die(mysqli_error());
				$result=mysqli_num_rows($retval);
				# if the sex has changed and default photo exists then the photo need updating
				if ($result > 0) {
						$sql_array = mysqli_fetch_object($retval);
						$query="UPDATE adverts SET adv_dob='$dob', adv_sex='$lstSex' where adv_userid = '$Sess_UserId'";
						if (!mysqli_query($conStingreg,$query)){
								error_page("Failed",GENERAL_SYSTEM_ERROR, $mode);
						}
				}
		}
}

unset($_SESSION["post"]);

$regtitle=PRGREGISTER_TEXT27;
$regtext=PRGREGISTER_TEXT28;
if($is_speeddating)
    $reglink="<input type=button class=input_button name=ok value='".GENERAL_CONTINUE."' onClick=\"location.href='$CONST_LINK_ROOT/speeddating/home.php'\">";
else
    $reglink="<input type=button class=button name=ok value='".GENERAL_CONTINUE."' onClick=\"location.href='$CONST_LINK_ROOT/myinfo.php'\">";
$paragraph='';
// mysqli_close($conStingreg);
?>
<?=$skin->ShowHeader($area)?>
  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
    <tr>
      <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
      </td>
    </tr>
    <tr>

    <td class="pageheader"><?php echo REGISTER_SECTION_NAME ?></td>
    </tr>
    <tr>
    <td><b><?php print("$regtitle"); ?></b> <p><?php print("$regtext"); ?></p>
      <p><?php print("$paragraph"); ?></p>
      <?php print("$reglink"); ?></td>
    </tr>
  </table>
<?=$skin->ShowFooter($area)?>