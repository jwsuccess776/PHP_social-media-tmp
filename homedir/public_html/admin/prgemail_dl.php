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

# Name: 		prgemail_dl.php

#

# Description:  extracts mails of joiner betwee 2 dates

#

# # Version:      8.0

#

######################################################################



include('../db_connect.php');

include_once('../validation_functions.php'); 

include('../session_handler.inc');

include('permission.php');

# retrieve the template

$area = 'member';



$lstFromYear=sanitizeData($_POST['lstFromYear'], 'xss_clean');   

$lstFromMonth=sanitizeData($_POST['lstFromMonth'], 'xss_clean'); 

$lstFromDay=sanitizeData($_POST['lstFromDay'], 'xss_clean');

$lstToYear=sanitizeData($_POST['lstToYear'], 'xss_clean');  

$lstToMonth=sanitizeData($_POST['lstToMonth'], 'xss_clean');  

$lstToDay=sanitizeData($_POST['lstToDay'], 'xss_clean');

$fromDate=$lstFromYear."-".$lstFromMonth."-".$lstFromDay;

$toDate=$lstToYear."-".$lstToMonth."-".$lstToDay;

$query="SELECT mem_email, mem_joindate FROM members

		WHERE mem_joindate BETWEEN '$fromDate' AND '$toDate'

		ORDER BY mem_joindate ASC";

	$result=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

	$qrynum=mysqli_num_rows($result);

header("Content-type: application/csv");

header("Content-Disposition: attachment; filename=email_report.csv");

?>

<?php	while ($sql_array = mysqli_fetch_object($result) ) {?>

<?php echo $sql_array->mem_email ?><?php echo "\r\n"?>

<?php } ?>

