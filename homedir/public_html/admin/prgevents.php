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

# Name: 		prgevents.php

#

# Description:  creates or updates profile information from profile.php

#

# Version:		7.3

#

######################################################################



include('../db_connect.php');

include_once('../validation_functions.php'); 

include('../session_handler.inc');

include('../error.php');

include('../message.php');

include('permission.php');



function form_to_db($date) {

      if (preg_match ("/([0-9]{1,2}):([0-9]{1,2}) ([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})/", $date, $regs)) {

    if(strlen($regs[1])<2) $regs[1]= "0$regs[1]";

    if(strlen($regs[2])<2) $regs[2]= "0$regs[2]";

    if(strlen($regs[3])<2) $regs[3]= "0$regs[3]";

    if(strlen($regs[4])<2) $regs[4]= "0$regs[4]";

    if(strlen($regs[5])<4) $regs[5]= "20$regs[5]";

          return "$regs[5]$regs[3]$regs[4]$regs[1]$regs[2]00";

      } else {

          return FALSE;

      }

  }

$txtEventId = sanitizeData($_POST['txtEventId'], 'xss_clean');    

$txtEventName=sanitizeData($_POST['txtEventName'], 'xss_clean');  

$txtAddress=sanitizeData($_POST['txtAddress'], 'xss_clean');    



$lstCity=sanitizeData($_POST['lstCity'], 'xss_clean');   

$lstState=sanitizeData($_POST['lstState'], 'xss_clean'); 

$lstCountry=sanitizeData($_POST['lstCountry'], 'xss_clean'); 



$txtWebsite=sanitizeData($_POST['lstFromYear'], 'xss_clean');  

$txtSchedule=sanitizeData($_POST['lstFromYear'], 'xss_clean').
        "/".sanitizeData($_POST['txtMonth'], 'xss_clean')."/".
        sanitizeData($_POST['txtDay'], 'xss_clean')." ".
        sanitizeData($_POST['txtHours'], 'xss_clean').":".
        sanitizeData($_POST['txtMinutes'], 'xss_clean').":00";

$txtDesc = sanitizeData($_POST['txtDesc'], 'xss_clean');    

$txtPhone = sanitizeData($_POST['txtPhone'], 'xss_clean');    

$txtApprove = sanitizeData($_POST['txtApprove'], 'xss_clean');  



$max_size=$option_manager->GetValue('maxpicsize');



if ($txtApprove<> "")

    {

    if ($txtApprove=="1")

        {

        $query="UPDATE events set ev_eventname = '$txtEventName',

                    ev_address = '$txtAddress',

                    ev_city = '$lstCity',

                    ev_state = '$lstState',

                    ev_country = '$lstCountry',

                    ev_schedule = '$txtSchedule',

                    ev_phone = '$txtPhone',

                    ev_website = '$txtWebsite',

                    ev_description = '$txtDesc',

                    ev_approved = '1' WHERE ev_eventid = '$txtEventId'";

        if (!mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),"System Error");}

        header("Location: ".$CONST_LINK_ROOT."/admin/approveevent.php");

        exit;

        }

    elseif ($txtApprove =='0')

        {

        $query = "DELETE FROM events WHERE ev_eventid = '$txtEventId'";

        if (!mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),"System Error");}

        header("Location: ".$CONST_LINK_ROOT."/admin/approveevent.php");

        exit;

        }

    }



# validate the txtTitle field and add it to the adverts table is the field is fine.



if (strlen($txtEventName) < 3 )

    {

    $error_message="Please enter between 3 and 30 characters in the Event Name field";

    error_page($error_message,"User Error");

    }

elseif (strlen($txtAddress) < 3)

    {

    $error_message="Please enter between 3 and 50 characters in the Address Name field";

    error_page($error_message,"User Error");

    }

elseif (!$lstCity)

    {

    $error_message="Please select City";

    error_page($error_message,"User Error");

    }

elseif (!$lstCountry)

    {

    $error_message="Please select Country";

    error_page($error_message,"User Error");

    }

elseif (strlen($txtDesc) < 3)

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

            $filename=str_replace(" ","","$txtEventName")."$extension";

            $targetfile=$CONST_INCLUDE_ROOT."/events/"."$filename";

            copy($_FILES['mainfupload']['tmp_name'],stripslashes($targetfile));

            $targetfile="/events/"."$filename";

        } else {

            error_page("Pictures must be either GIF or JPG format.","User Error");

        }

        }



$query="INSERT INTO events (ev_eventname,

                        ev_address, ev_city, ev_state, ev_country,

                        ev_schedule, ev_phone, ev_website, ev_description, ev_picture,ev_approved)

                    VALUES ('$txtEventName','$txtAddress','$lstCity','$lstState','$lstCountry','$txtSchedule','$txtPhone',

                        '$txtWebsite','$txtDesc',

                        '$targetfile','1')";



        if (!mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),"System Error");}

//mysqli_close( $link );

if ($Sess_UserType != 'A') {

    header("Location: ".$CONST_LINK_ROOT."/admin/home.php");

}

else {

    header("Location: ".$CONST_LINK_ROOT."/admin/approveevent.php");

}



exit;



?>