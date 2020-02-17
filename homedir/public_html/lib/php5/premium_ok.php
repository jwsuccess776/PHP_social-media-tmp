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
# Name:         premium_ok.php
#
# Description:  Page displayed after a user pays for membership
#
# Version:      7.2
#
####################################################################
$amount=$payment->pay_samount;
$id=$payment->pay_transid;

$result = mysql_query("SELECT * FROM members WHERE mem_userid=$Sess_UserId",$link);
$arr_member = mysql_fetch_object($result);
$testdate=date("Y-m-d");
if ($arr_member->mem_expiredate < $testdate) {
    $_SESSION['Sess_Userlevel']="silver";
} else {
    $_SESSION['Sess_Userlevel']="gold";
}
$Sess_Userlevel=$_SESSION['Sess_Userlevel'];

switch ($payment->pay_transstatus) {
    case 'Completed':
        $response=sprintf(THANKYOU_COMPLETED,$id,$amount,$arr_member->mem_expiredate);
        break;
    case 'Pending':
        $response=sprintf(THANKYOU_PENDING,$id,$amount,$arr_member->mem_expiredate);
        break;
    case 'Failed':
        $response=THANKYOU_FAILED;
        break;
    case 'Denied':
        $response=THANKYOU_DENIED;
        break;
}
?>
<?=$response?>
