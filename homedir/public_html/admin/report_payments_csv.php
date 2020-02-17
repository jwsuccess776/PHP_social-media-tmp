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

# Name:         report_payments_csv.php

#

# Description:  destroys affiliate session

#

# Version:      2.3

#

######################################################################



include('../db_connect.php');
include_once('../validation_functions.php'); 
include('../session_handler.inc');

include('permission.php');



if (isset($_POST['lstYear'])) $lstYear=sanitizeData($_POST['lstYear'], 'xss_clean');   



elseif (isset($_GET['lstYear'])) $lstYear=sanitizeData($_GET['lstYear'], 'xss_clean');    



else $lstYear=date('Y');



if (isset($_POST['lstMonth'])) $lstMonth=sanitizeData($_POST['lstMonth'], 'xss_clean');     



elseif (isset($_GET['lstMonth'])) $lstMonth=sanitizeData($_GET['lstMonth'], 'xss_clean');      



else $lstMonth=date('m');



# retrieve the template



$Year=date("Y");



$Year1=$Year-1;



$Year2=$Year1-1;



$Year3=$Year1-2;



$Year4=$Year1-3;



if (isset($lstMonth) and $lstMonth != "-Choose-" and $lstYear != "-Choose-") {



    $query="SELECT *, DATE_FORMAT(pay_date,'%d-%M-%Y') AS paydate



        FROM payments LEFT JOIN members ON (pay_userid=mem_userid)



        WHERE MONTH(pay_date) = '$lstMonth'



        AND YEAR(pay_date) = '$lstYear' AND pay_transid !=''



        ORDER BY pay_date DESC";



    $result=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());



    $qrynum=mysqli_num_rows($result);



}



header("Content-type: application/csv");



header("Content-Disposition: attachment; filename=report.csv");



echo REPORT_PAYMENTS_CSV_TDATE.";".GENERAL_MEMBER.";".REPORT_PAYMENTS_CSV_TCODE.";".REPORT_PAYMENTS_CSV_TSTATUS.";".REPORT_PAYMENTS_CSV_AMOUNT;



?>



<?php   while ($sql_array = mysqli_fetch_object($result) ) {?>



<?php echo date($CONST_FORMAT_DATE_SHORT,strtotime($sql_array->paydate));?>;<?php echo $sql_array->pay_username;?>;<?php echo $sql_array->pay_transid?>;<?php echo $sql_array->pay_transstatus?>;<?php echo "$CONST_SYMBOL$sql_array->pay_samount\r\n"?>



<?php } ?>



