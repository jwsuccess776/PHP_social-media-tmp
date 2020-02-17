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
# Name: 		login.php
#
# Description:  Member login screen
#
# # Version:      8.0
#
######################################################################
include('../db_connect.php');
include(CONST_INCLUDE_ROOT.'/session_handler.inc');
include(CONST_INCLUDE_ROOT.'/error.php');
include_once __INCLUDE_CLASS_PATH."/class.Network.php";
$network = new Network();

$action = formGet('action');
$user_id = $db->escape(formGet('user_id'));
switch ($action) {
    case "request":
                $res = $network->friendshipRequest($Sess_UserId,$user_id);
                if ($res === null) {
                    error_page(join("<br>",$network->error),GENERAL_USER_ERROR);
                }
                header("Location:$CONST_LINK_ROOT/prgretuser.php?userid=$user_id");
                break;
    case "unlink" :
                $res = $network->removeFriend($Sess_UserId,$user_id);
                if ($res === null) {
                    error_page(join("<br>",$network->error),GENERAL_USER_ERROR);
                }
                header("Location:$CONST_LINK_ROOT/prgretuser.php?userid=$user_id");
                break;
    default      : error_page(SOCIAL_NETWORK_APPROVE_ERROR1,GENERAL_USER_ERROR);
}
?>