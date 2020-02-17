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
# Name:         getList.php
#
# Description:  Page displayed after a user pays for membership
#
# Version:      7.2
#
####################################################################
extract($_POST);
extract($_GET);

//unset($phpuid,$output,$phpmaintxt,$phpmaintxt2, $_GET['phpuid']);

//session_cache_limiter('private, must-revalidate');

include('../db_connect.php');
require("function.php");

if (!isset($_SESSION['Sess_UserName'])){
    exit("Incorrect Parameters");
} else {
    getList($_SESSION['Sess_UserName']);
}
?>