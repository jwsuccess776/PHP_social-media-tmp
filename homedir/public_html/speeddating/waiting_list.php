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
# Name:         waiting_list.php
#
# Description:
#
# Version:      7.2
#
######################################################################
include('../db_connect.php');
include('../session_handler.inc');
include_once('../validation_functions.php'); 
// include('../message.php');
// include('../error.php');

//if ($_REQUEST["sde_eventid"] && $Sess_UserId) {

 $sde_eventid=sanitizeData($_REQUEST['sde_eventid'], 'xss_clean') ; 
    mysqli_query($globalMysqlConn,"INSERT IGNORE INTO sd_waiting
                                SET swt_userid='$Sess_UserId',
                                    swt_eventid='".$sde_eventid."'");
    $eventName=mysqli_fetch_array(mysqli_query($globalMysqlConn,"SELECT sde_name FROM sd_events WHERE sde_eventid='18'"), MYSQLI_ASSOC);
    $userName=mysqli_fetch_array(mysqli_query($globalMysqlConn,"SELECT CONCAT(mem_forename,' ',mem_surname) AS name FROM members WHERE mem_userid='$Sess_UserId'"), MYSQLI_ASSOC);
    send_mail($CONST_MAIL,$CONST_MAIL,SD_WAITING_LIST_MAIL_SUBJECT,sprintf(SD_WAITING_LIST_MAIL,$userName['name'],$Sess_UserId,$eventName['sde_name']),"text","ON");
    display_page(sprintf(SD_WAITING_LIST_MESSAGE,$eventName['sde_name']),SD_WAITING_LIST_SECTION_NAME);
//} else header("Location: ".$CONST_LINK_ROOT."/speeddating/");
?>