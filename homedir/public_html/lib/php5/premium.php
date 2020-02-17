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
# Name:         premium.php
#
# Description:  Page displayed after a user pays for membership
#
# Version:      7.2
#
####################################################################
function payment_activation($pay_paymentid){
    global $CONST_COMPANY,$CONST_AFFMAIL,$CONST_URL;
	$query="SELECT * FROM payments WHERE pay_paymentid='$pay_paymentid'";
	$result=mysql_query($query) or die(mysql_error());
	$_payment = mysql_fetch_object($result);
//print_r($_payment);
	$local_userid=$_payment->pay_userid;
	$samount=$_payment->pay_samount;
	$pay_transid=$_payment->pay_transid;

    $option_manager = &OptionManager::GetInstance();
	# get the current prices to calculate the affiliate payment amount
	$month1=$option_manager->GetValue('1month');
	$month3=$option_manager->GetValue('3month');
	$month6=$option_manager->GetValue('6month');
	$month12=$option_manager->GetValue('12month');
	$query = "
		SELECT
			YEAR(mem_expiredate) AS exp_year,
			MONTH(mem_expiredate) AS exp_month,
			DAYOFMONTH(mem_expiredate) AS exp_day
		FROM members
		WHERE mem_userid = $local_userid AND mem_expiredate > CURDATE()";
//echo $query;
	$sql_result = mysql_query($query);
	if(mysql_num_rows($sql_result) > 0)
	{
		$cur_exp = mysql_fetch_object($sql_result);
		$exp_year = $cur_exp->exp_year;
		$exp_month = $cur_exp->exp_month;
		$exp_day = $cur_exp->exp_day;
	}
	else
	{
		$exp_year = date("Y");
		$exp_month = date("m");
		$exp_day = date("d");
	}
	$months_arr=unserialize($_payment->pay_params);
	$expiredate=mktime (0,0,0,$exp_month+$months_arr['number'] ,$exp_day,$exp_year); 

	$expiredate=date('Y/m/d',$expiredate);
	$query="UPDATE members SET mem_expiredate = '$expiredate' WHERE mem_userid=$local_userid";
	$retval=mysql_query($query) or die(mysql_error());
	$query="UPDATE adverts SET adv_expiredate = '$expiredate' WHERE adv_userid=$local_userid";
	$retval=mysql_query($query) or die(mysql_error());
	# check to see if the member was sent by an affiliate
	$query="SELECT mem_referrer FROM members WHERE mem_userid=$local_userid";
	$result=mysql_query($query) or die(mysql_error());
	$sql_array = mysql_fetch_object($result);
	$affiliateNo=$sql_array->mem_referrer;
	if ($affiliateNo > 0) {
		# calculate the earliet pay date and the last day of that month
		$earliestpay=mktime (0,0,0,date("m")+2 ,date("d"),date("Y"));
		$tlastday=date('t',mktime (0,0,0,date("m")+2 ,date("d"),date("Y")));
		$earliestpay=date('Y/m/d',$earliestpay);
		# find out what due date the affiliate payment will have
		$tyear=substr($earliestpay,0,4);
		$tmonth=substr($earliestpay,5,2);
		$tday=substr($earliestpay,8,2);
		$duedate=$tyear."-".$tmonth."-".$tlastday;
		# calculate the % payable to the affiliate
		$query="SELECT * FROM receipts WHERE rec_memuserid='$local_userid' AND rec_affuserid='$affiliateNo'";
		$result=mysql_query($query) or die(mysql_error());
		$TOTAL = mysql_num_rows($result);
		switch ($samount) {
			case $month1:
				$affamount=$month1;
				break;
			case $month3:
				$affamount=$month3;
				break;
			case $month6:
				$affamount=$month6;
				break;
			case $month12:
				$affamount=$month12;
				break;
		}
		# the affiliate gets 50% of 1st payment and 25% of renewals
		if ($TOTAL < 1) {
			$percentage=$option_manager->GetValue('initialreferal');
			$affamount=$affamount*$percentage/100;
		} else {
			$percentage=$option_manager->GetValue('subsequentreferal');
			$affamount=$affamount*$percentage/100;
		}
		$tempDate=date('Y/m/d');
		# insert the affiliate amount to the receipts table
		$query="INSERT INTO receipts (
							rec_memuserid,
							rec_affuserid,
							rec_buydate,
							rec_earliestpay,
							rec_paydate,
							rec_paid,
							rec_amount,
							rec_transid,
							rec_percentage,
							rec_affamount
				)
				values(
							'$local_userid',
							'$affiliateNo',
							'$tempDate',
							'$earliestpay',
							'$duedate',
							'0',
							'$samount',
							'$pay_transid',
							'$percentage',
							 $affamount)";
		$result=mysql_query($query) or die(mysql_error());
		# inform the affiliate by mail that a member paid
		$affParams="SELECT * FROM affiliates WHERE aff_userid='$affiliateNo'";
		$result=mysql_query($affParams);
		$sql_affiliate = mysql_fetch_object($result);
		$message=sprintf(NOTIFY_TEXT,$sql_affiliate->aff_forename,$sql_affiliate->aff_surname,$CONST_COMPANY,$pay_transid,$samount,$percentage,$affamount,$CONST_URL,$CONST_AFFMAIL);
		send_mail ("$sql_affiliate->aff_email", "$CONST_AFFMAIL", "$CONST_COMPANY ".NOTIFY_SUBJ, "$message","text","ON");
	}

}
?>