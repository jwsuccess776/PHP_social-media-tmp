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
# Name: 		addclub.php
#
# Description:  Displays the profile input page (after advert)
#
# # Version:      8.0
#
######################################################################
include('../db_connect.php');
require_once __INCLUDE_CLASS_PATH."/class.MailQueue.php";
require_once(__INCLUDE_CLASS_PATH."/class.PaLock.php");
include('permission.php');

ini_set("max_execution_time", "600");
ini_set("ignore_user_abort","1");
ini_set("memory_limit","20M");

define("MAIL_QUEUE_LIMIT",60);

$lock             =& new PaLock("Mail_Queue",CONST_INCLUDE_ROOT."/tmp");
if (!$lock->lock()) exit;

$queue             = new MailQueue();
$_mysqlstartTime   = date("Y-m-d H:i:s");

$portion_size      = formGet("count");
if ($portion_size == 0) $portion_size = MAIL_QUEUE_LIMIT;
$sent_queue_data=0;

foreach ($queue->getPortion($portion_size) as $mail) {
    send_mail  ($mail->Email,  $mail->From,  $mail->Subject,  $mail->Body, $mail->Type, 'ON');
    $mail->Delete();
    $sent_queue_data++;
}
$queue->addStat($sent_queue_data, $_mysqlstartTime, "");
$lock->release();

echo ($sent_queue_data != 0) ? $sent_queue_data : 'END';
?>


