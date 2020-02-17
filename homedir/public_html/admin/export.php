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
# Name:                 export.php
#
# Description:  exports data to the members directory
#
# Version:               7.2
#
######################################################################

//error_reporting(E_ALL);

include('../db_connect.php');
include('../session_handler.inc');
include('../error.php');
include('../message.php');
include('permission.php');

error_reporting(E_ALL);
restrict_demo();

$lstTables=$_POST['lstTables'];
$arcType=$_POST['arcType'];

if (!count($lstTables)) error_page("No tables selected for export.",GENERAL_USER_ERROR);

switch ($arcType) {
    case "gzip":
        $fName=__CONST_DB_NAME."-".date("Ymd").".sql.gz";
        $command="mysqldump --opt -h ".__CONST_DB_HOST." -u".__CONST_DB_USER." -p".__CONST_DB_PASS." ".__CONST_DB_NAME." ".implode(" ",$lstTables)." | gzip -c";
        break;
    default:
        $fName=__CONST_DB_NAME."-".date("Ymd").".sql";
        $command="mysqldump --opt -h ".__CONST_DB_HOST." -u".__CONST_DB_USER." -p".__CONST_DB_PASS." ".__CONST_DB_NAME." ".implode(" ",$lstTables);
}

header("Cache-Control: private");
header("Content-Type: application/octet-stream");
header('Content-Disposition: attachment; filename="'.$fName.'.sql"');
header("Last-Modified: Thu, 19 Nov 1981 08:52:00 GMT");
header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");

passthru($command, $return_val);

exit;
?>