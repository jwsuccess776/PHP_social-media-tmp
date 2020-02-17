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

# Name: 		prgtop10.php

#

# Description:  updates the photo rating statistics from retuser.php

#

# # Version:      8.0

#

######################################################################



include('db_connect.php');
include_once('validation_functions.php');


$sex =sanitizeData($_GET['sex'], 'xss_clean') ;   



$query="SELECT *, adv_userid, adv_sex, adv_username, mem_suspend 

        FROM ratings, adverts, members

 		WHERE rte_userid = adv_userid AND mem_userid=adv_userid

         AND adv_sex='$sex'

		 AND mem_suspend='N' 

        ORDER BY rte_average DESC,rte_votes DESC

        LIMIT 10";

$result=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());

/*

if (mysql_num_rows($result) > 0 ) {

} else {

}

*/

// mysql_close($link);

?>

<html>

<head>

<meta http-equiv="Content-Language" content="en">

<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

<LINK REL='StyleSheet' type='text/css' href='<?php echo $CONST_LINK_ROOT?>/singles.css'>

<title><?php echo PRGTOP10_TITLE?></title>

<LINK href="<?=$CONST_LINK_ROOT.$skin->Path?>/singles.css" type=text/css rel=StyleSheet>

</head>

<body>



<table width="260" border="0" align="center" cellpadding="5" cellspacing="0" class="poptable" >

  <tr>

    <td width="260"   class="tdhead"><b><?php echo PRGTOP10_RATING?></b></td>

    <td width="260"  class="tdhead"><b><?php echo PRGTOP10_MEMBER?></b></td>

  </tr>

  <?php

    while ($sql_array=mysqli_fetch_object($result)) {

        print("<tr class='tdodd'><td width='60'>$sql_array->rte_average </td>

                      <td width='200'><a href='javascript: void();' onClick=\"window.opener.location.href='$CONST_LINK_ROOT/prgretuser.php?userid=$sql_array->adv_userid';\">$sql_array->adv_username<a></td></tr>");

    }



?>

  <tr>

    <td width="260" colspan='2' align='center' valign='middle' class="tdfoot">

      <input type="button" class="button" onClick="window.close();" value="<?php echo GENERAL_CLOSE ?>"></td>

  </tr>

</table>

</div>

</body>

</html>

