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
# Name:         sd_ticket.php
#
# Description:  Page displayed after a user pays for membership
#
# Version:      7.2
#
####################################################################
#############################
#For correct work you need pass 2 parameters
#
#eventid - id of event that member is buying
#gender - members gender
##############################
function payment_activation($paymentid)
{
	$sql_result = mysql_query("SELECT * FROM payments WHERE pay_paymentid = $paymentid");
	$payment = mysql_fetch_object($sql_result);
	$userid = $payment->pay_userid;
	$params = unserialize($payment->pay_params);
    settype($params,'object');
	$sql_result = mysql_query("SELECT * FROM sd_events WHERE sde_eventid = $params->eventid");
	$event = mysql_fetch_object($sql_result);
	if($event->sde_gender1 == $params->gender || $event->sde_gender2 == $params->gender)
	{
        $gender = ($event->sde_gender1 == $params->gender) ? 'Gender1' : 'Gender2';
        $sql_result = mysql_query(" SELECT max(sdt_ticket_num) num
                                    FROM sd_tickets 
                                    WHERE sdt_eventid = $params->eventid");
        $num = mysql_result($sql_result,'num') + 1;

        $sql_result = mysql_query(" SELECT * 
                            FROM sd_tickets 
                            WHERE sdt_eventid='$params->eventid'
                            AND sdt_userid = '$userid'"
                          ) or die(mysql_error());
        $is_bought = mysql_fetch_object($sql_result);
        if (!$is_bought){
    		mysql_query(
    			"INSERT INTO sd_tickets
    			SET
    				sdt_userid = $userid,
    				sdt_eventid = '$params->eventid',
    				sdt_gender = '$gender',
    				sdt_paymentid = '$paymentid',
                    sdt_ticket_num = '$num'"
    			) or die(mysql_error());
        
		    
			
			# send the mail externally
    		send_mail ("$sql_array->mem_email", "$CONST_MAIL", PRGSENDFLIRT_TEXT2 , "$message",$type,"ON");

		
		
		
		} 
	}
	else
		die(INVALID_GENDER);
}
?>