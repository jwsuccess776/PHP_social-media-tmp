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

# Name:         prgencounters.php

#

# Description:  Displays which members have visited the members' profile

#

# Version:      7.2

#

######################################################################



include('db_connect.php');

include('session_handler.inc');

?>

<html>

<head>

<meta http-equiv='Content-Language' content='en'>

<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>

<meta name='ROBOTS' content='NOINDEX, NOFOLLOW'>

<meta name='Author' content='Dylan Fox 2000 - 2002'>

<title><?php echo $CONST_COMPANY ?></title>

<script language="JavaScript" src="/jscript_lib.js.php"></script>

<link rel='stylesheet' type='text/css' href='<?=$CONST_LINK_ROOT.$skin->Path?>template.css' />

<link rel='stylesheet' type='text/css' href='<?=$CONST_LINK_ROOT.$skin->Path?>core.css' />

</head>

<body>



<table width="520" class="poptable" border="0" align="center" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

  <tr align="left" >

    <td colspan="5" class="pageheader"><?php echo ENCOUNTER_SECTION_NAME?></td>

  </tr>

  <tr align="left" >

    <td colspan="5"> <div class="smalltext"><?php echo ENCOUNTER_TEXT?></div></td>

  </tr>

  <tr class="tdtoprow">

    <td ><strong><?php echo ENCOUNTER_DATE?></strong></td>

    <td ><strong><?php echo ENCOUNTER_USERNAME?></strong></td>

    <td align='center'><strong><?php echo ENCOUNTER_AGE?></strong></td>

    <td align='left' ><strong><?php echo ENCOUNTER_SEX?></strong></td>

    <td align='left' ><strong><?php echo ENCOUNTER_REGION?></strong></td>

  </tr>

  <?php

			 

			  $result=mysqli_query($globalMysqlConn, "SELECT enc_viewdate, enc_viewerid, enc_userid, adv_dob, adv_username, adv_sex, gcn_name 

                                    FROM encounters 

                                        INNER JOIN adverts ON (enc_viewerid=adv_userid) 

                                        LEFT JOIN geo_country ON (gcn_countryid=adv_countryid) 

                                    WHERE enc_userid='$Sess_UserId' AND enc_viewdate >= DATE_SUB(CURDATE(),INTERVAL 10 DAY)

                                    ORDER BY enc_viewdate DESC") or die(mysqli_error());

									



                  while ($sql_array=mysqli_fetch_array($result)) {

                        $viewdate=$sql_array[0];

                        $tempyear=date("Y");

                        $tempmonth=date("m");

                        $tempday=date("d");

                        $dobyear=trim(substr($sql_array[3],0,4));

                        $dobmonth=trim(substr($sql_array[3],5,2));

                        $dobday=trim(substr($sql_array[3],8,2));

                        $age=$tempyear-$dobyear;

                        if ($tempmonth < $dobmonth) {

                            $age=$age-1;

                        } elseif (($tempmonth == $dobmonth) && ($tempday < $dobday)) {

                            $age=$age-1;

                        }



                        if ($sql_array[5]=='M') $mygender= SEX_MALE;

                        elseif ($sql_array[5]=='F') $mygender= SEX_FEMALE;

                        elseif ($sql_array[5]=='C') $mygender= SEX_COUPLE;



                        $advcheck=mysqli_query($globalMysqlConn, "SELECT * FROM adverts WHERE adv_userid='$sql_array[1]'");

                        $TOTAL = mysqli_num_rows($advcheck);

                        if ($TOTAL > 0) {

                          print("<tr class='tdeven'>

                              <td width='20%' height='20'>".date($CONST_FORMAT_DATE_SHORT,strtotime($viewdate))."</td>

                              <td width='30%' height='20'><a href='javascript: void(0)' onClick=\"window.opener.location.href='$CONST_LINK_ROOT/prgretuser.php?userid=$sql_array[1]';\">$sql_array[4]<a></td>

                              <td align='center' width='10%' height='20'>$age</td>

                              <td align='left' width='10%'>$mygender</td>

                              <td align='left' width='30%'>$sql_array[6]</td>

                          </tr>");

                      } else {

                          print("<tr class='tdeven'>

                              <td width='20%' height='20'>$viewdate</td>

                              <td width='30%' height='20'>$sql_array[4]</td>

                              <td align='center' width='10%' height='20'>$age</td>

                              <td align='left' width='10%'>$mygender</td>

                              <td align='left' width='30%'>$sql_array[6]</td>

                          </tr>");

                      }

                  }

  ?>

  <tr align="center" >

    <td colspan="5" class="tdfoot"><a href="" onClick="window.close();"><?php echo GENERAL_CLOSE?></a></td>

  </tr>

</table>

</body>

</html>