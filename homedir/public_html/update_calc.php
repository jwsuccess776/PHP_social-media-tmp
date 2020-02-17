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
# Name:         update_calc.php
#
# Description:  Sends offer mails to people who have not visited for a while
#
# Version:      7.2
#
######################################################################
		
		$query = "
			SELECT
				YEAR(mem_expiredate) AS exp_year,
				MONTH(mem_expiredate) AS exp_month,
				DAYOFMONTH(mem_expiredate) AS exp_day
			FROM members
			WHERE mem_userid = $local_userid AND mem_expiredate > CURDATE()";
		$sql_result = mysql_query($query, $link);
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
		if ($local_period == '1') {$expiredate=mktime (0,0,0,$exp_month+1 ,$exp_day,$exp_year);}
		if ($local_period == '3') {$expiredate=mktime (0,0,0,$exp_month+3 ,$exp_day,$exp_year);}
		if ($local_period == '6') {$expiredate=mktime (0,0,0,$exp_month+6 ,$exp_day,$exp_year);}
		if ($local_period == '12') {$expiredate=mktime (0,0,0,$exp_month ,$exp_day,$exp_year+1);}
		$expiredate=date('Y/m/d',$expiredate);

?>