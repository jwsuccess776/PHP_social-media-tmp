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
# Name: 		prgremoveme.php
#
# Description:  Admin tool to removes newsletter by address or bulk address (bounced addresses)
#
# # Version:      8.0
#
######################################################################

include('../db_connect.php');
include_once('../validation_functions.php'); 
include('../session_handler.inc');
include('../error.php');
include('permission.php');

$txtRemoveAddress=sanitizeData($_POST['txtRemoveAddress'], 'xss_clean');  
if (isset($chkBulkRemove)) $chkBulkRemove=sanitizeData($_POST['chkBulkRemove'], 'xss_clean');  
# retrieve the root path for the email file
$root_path=$CONST_INCLUDE_ROOT."/";
$recno=0;
if (strlen($txtRemoveAddress) > 1) {
    $query="UPDATE members SET mem_newsletter=0 where mem_email='$txtRemoveAddress'";
    $result=mysql_query($query,$link) or die(mysql_error());
    $recno++;
    flush();
    sleep(0);
    print("$recno) $txtRemoveAddress - removed.<br>");
} elseif (isset($chkBulkRemove)) {
    $fp = @fopen ($CONST_INCLUDE_ROOT."/remove.txt", "r");
    if (!$fp) error_page("Can't find ".$CONST_INCLUDE_ROOT."remove.txt",GENERAL_SYSTEM_ERROR);
    while(!feof($fp)) {
        $txtRemoveAddress = fgets($fp,200);
        $query="UPDATE members SET mem_newsletter=0 where mem_email='$txtRemoveAddress'";
        $result=mysql_query($query,$link) or die(mysql_error());
        $recno++;
        flush();
        sleep(0);
        print("$recno) $txtRemoveAddress - removed.<br>");
    }
    fclose($fp);
}
mysql_close($link);
?>
