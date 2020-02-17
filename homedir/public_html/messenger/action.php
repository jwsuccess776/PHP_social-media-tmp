<?
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
# Name:         action.php
#
# Description:  Page displayed after a user pays for membership
#
# Version:      7.2
#
####################################################################
include('../db_connect.php');

@extract($_POST);
@extract($_GET);

if (!isset($_SESSION['Sess_UserName'])){
    exit("Incorrect Parameters");
} else {
    $phpuid = $_SESSION['Sess_UserName'];
}

require("function.php");


if (isset($action)) {
    if (!empty($action)) {
        switch ($action) {
            case 'add_friend': {
                addFriends($phpuid, $value);
            }
            break;
            case 'delete_friend': {
                deleteFriends($phpuid, $value);
            }
            break;
            case 'blockUnblock': {
                blockUnblock($phpuid, $value);
            }
            break;
            case 'change_status': {
                changeStatusOn($value);
            }
            break;
            case 'getInfoChat': {
                $aResult = getInfoChat($value);
            }
            break;
            case 'logout': {
                $sResult = logout($phpuid);
                echo $sResult;
            }
            break;
        }
    }
}
?>