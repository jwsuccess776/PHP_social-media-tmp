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

# Name: 		prgrate.php

#

# Description:  updates the photo rating statistics from retuser.php

#

# # Version:      8.0

#

######################################################################



require_once ( 'db_connect.php' );

require_once ( 'session_handler.inc' );
include_once 'validation_functions.php';


$userid=sanitizeData($_GET['userid'], 'xss_clean');  

$vote=sanitizeData($_POST['vote'], 'xss_clean');  

$query="SELECT * FROM ratings WHERE rte_userid=$userid";

$result=mysqli_query($globalMysqlConn, $query) or die(mysqli_error()."$query");

if (mysqli_num_rows($result) > 0 ) {

    $sql_array=mysqli_fetch_object($result);

    $num_value=$sql_array->rte_value+$vote;

    $num_votes=$sql_array->rte_votes+1;

    $num_average=$num_value/$num_votes;

    $query="UPDATE ratings SET rte_votes=$num_votes, rte_value=$num_value, rte_average=$num_average WHERE rte_userid=$userid";

    $result=mysqli_query($globalMysqlConn, $query) or die(mysqli_error()."$query");

} else {

    $query="INSERT INTO ratings (rte_userid, rte_votes, rte_value, rte_average) VALUES ($userid,1,$vote,$vote)";

    $result=mysqli_query($globalMysqlConn, $query) or die(mysqli_error()."$query");

}

# record that this user has rated the advert

$query="INSERT INTO ratedby (rtb_userid, rtb_raterid,rtb_vote) VALUES ($userid,$Sess_UserId,$vote)";

$result=mysqli_query($globalMysqlConn, $query) or die(mysqli_error()."$query");

header("Location: prgretuser.php?userid=$userid");

// mysql_close($link);

?>

