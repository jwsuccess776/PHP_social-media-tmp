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
# Name:         sd_ticket_ok.php
#
# Description:  Page displayed after a user pays for membership
#
# Version:      7.2
#
####################################################################
$userid = $payment->pay_userid;
$amount=$payment->pay_samount;
$params = unserialize($payment->pay_params);
settype($params,'object');
$sql_result = mysql_query("SELECT * FROM sd_events WHERE sde_eventid = $params->eventid");
$event = mysql_fetch_object($sql_result);
$sql_result = mysql_query("SELECT * FROM sd_tickets WHERE sdt_userid='$userid' AND sdt_eventid = $params->eventid");
$ticket = mysql_fetch_object($sql_result);
?>
<?=SD_YOU_PAID?>  <?=$amount?> <?=SD_FOR_EVENT?> <b><?=$event->sde_name?></b>
<?=SD_TICKET_NO?> <?=$ticket->sdt_ticket_num?>

<?=$response?>
