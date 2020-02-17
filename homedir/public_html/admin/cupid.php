<?php

/****************************************************

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

# Name:         cupid.php

#

# Description:  Admin tool to send latest matches to members by mail

#

# Version:      7.2

#

######################################################################



ini_set("max_execution_time", "30000");

ini_set("ignore_user_abort","1");



include_once('../db_connect.php');

include_once('../session_handler.inc');

include_once('../functions.php');

include_once('../message.php');

include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";

include_once($CONST_INCLUDE_ROOT."/search_conf.inc.php");

include('permission.php');

include_once('../validation_functions.php');



$adv = new Adverts();



//restrict_demo();

$out .= "";

# retrieve the template

$area = 'member';



$lstNum =sanitizeData($_REQUEST['lstNum'], 'xss_clean');  



if (isset($_REQUEST['SEND'])) {

    # retrieve the date last run from params

    $query="SELECT par_lastcupidrun, par_lastrun FROM params";

    $result = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

    $sql_array2 = mysqli_fetch_object($result);

    # select all the records from the search table

    $query="SELECT *,mem_username, mem_email, mem_emailtype, mem_password

            FROM search

                LEFT JOIN members ON (sea_userid=mem_userid)

            WHERE sea_date <= '$sql_array2->par_lastrun' $lstNum";

    $result = mysqli_query($globalMysqlConn,$query) or die(mysqli_error()." ".$query);

    $tot_searches=mysqli_num_rows($result);



    # advance the lasrun number by 1 to update search sea_date

    $nextrun=$sql_array2->par_lastrun+1;



    #loop through the search table

    $sent_mails=0;

    while ($sql_array = mysqli_fetch_object($result) ) {

        $qryGender=""; $qrySeeking=""; $qryCountry=""; $qryAge="";





        # even if no search is found we update the lastrun to prevent the record being reread

        mysqli_query ($globalMysqlConn,"UPDATE search SET sea_date=$nextrun WHERE sea_userid='".$sql_array->mem_userid."'");



        if ($sql_array->mem_emailtype=='H') {

            $type = 'html';

        } else {

            $type = 'text';

        }

        # create the select query

        switch ($sql_array->sea_seeksex) {

            case "Women seeking men":

                $qryGender=" AND MEM_SEX='F' AND ADV_SEEKMEN='Y'";

                break;

            case "Women seeking women":

                $qryGender=" AND MEM_SEX='F' AND ADV_SEEKWMN='Y' AND adv_userid <> '$sql_array->sea_userid'";

                break;

            case "Women seeking couples":

                $qryGender=" AND MEM_SEX='F' AND ADV_SEEKCPL='Y'";

                break;

            case "Men seeking women":

                $qryGender=" AND MEM_SEX='M' AND ADV_SEEKWMN='Y'";

                break;

            case "Men seeking men":

                $qryGender=" AND MEM_SEX='M' AND ADV_SEEKMEN='Y' AND adv_userid <> '$sql_array->sea_userid'";

                break;

            case "Men seeking couples":

                $qryGender=" AND MEM_SEX='M' AND ADV_SEEKCPL='Y'";

                break;

            case "Couples seeking couples":

                $qryGender=" AND MEM_SEX='C' AND ADV_SEEKCPL='Y' AND adv_userid <> '$sql_array->sea_userid'";

                break;

            case "Couples seeking women":

                $qryGender=" AND MEM_SEX='C' AND ADV_SEEKWMN='Y'";

                break;

            case "Couples seeking men":

                $qryGender=" AND MEM_SEX='C' AND ADV_SEEKMEN='Y'";

                break;

        }

        $tempYear=date("Y"); $tempMonth=date("m"); $tempDay=date("d");

        $qrygeo = '';



        $lstCountry = array();

        $lstState = array();

        $lstCity = array();



        $query="SELECT sar_value FROM sarray WHERE sar_userid='$sql_array->sea_userid' AND sar_type='lstCountry'";

        $country_res = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

        while ($row = mysqli_fetch_array ($country_res)) $lstCountry[] = $row;



        $query="SELECT sar_value FROM sarray WHERE sar_userid='$sql_array->sea_userid' AND sar_type='lstState'";

        $state_res = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

        while ($row = mysqli_fetch_array ($state_res)) $lstState[] = $row;



        $query="SELECT sar_value FROM sarray WHERE sar_userid='$sql_array->sea_userid' AND sar_type='lstCity'";

        $city_res = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

        while ($row = mysqli_fetch_array ($city_res)) $lstCity[] = $row;



        if ((count($lstCountry)&&$lstCountry[0] != "0") || (count($lstState)&&$lstState[0]!="0") || (count($lstCity)&&$lstCity[0] != "0")) $qrygeo=" AND ( 0 ";



        if ($GEOGRAPHY_JAVASCRIPT) {

            if ($lstCity[0] != "0" && count($lstCity)>0) {

                foreach ($lstCity as $value) {



                    $res = mysqli_query($globalMysqlConn,"SELECT * FROM geo_city WHERE gct_cityid='".$value["sar_value"]."'");

                    $row = mysqli_fetch_object($res);

                    if ($row->gct_countryid != 0 ) $lstCountry = del_from_array($lstCountry,$row->gct_countryid);

                    if ($row->gct_stateid != 0 ) $lstState = del_from_array($lstState,$row->gct_stateid);

                    $qrygeo=$qrygeo." OR ADV_CITYID='".$value["sar_value"]."'";

                }

            } else {

                if (count($lstCity)>0){

                    $lstState[0] = 0;

                    $lstCountry[0] = 0;

                }

            }

        }

        if ($lstState[0] != "0" && count($lstState)>0) {

            $count=0;

            foreach ($lstState as $value) {



                $res = mysqli_query($globalMysqlConn,"SELECT * FROM geo_state WHERE gst_stateid='".$value["sar_value"]."'");

                $row = mysqli_fetch_object($res);

                if ($row->gst_countryid != 0 ) {

                    $lstCountry = del_from_array($lstCountry,$row->gst_countryid);

                }



                $qrygeo=$qrygeo." OR ADV_STATEID='".$value["sar_value"]."'";

            }

        } else {

            if (count($lstState)>0) $lstCountry[0] = 0;

        }



        if ($lstCountry[0] != "0" && count($lstCountry)>0) {

            $count=0;

            foreach ($lstCountry as $value) {

                $qrygeo=$qrygeo." OR ADV_COUNTRYID='".$value["sar_value"]."'";

            }

        }

        if ((count($lstCountry)&&$lstCountry[0] != "0") || (count($lstState)&&$lstState[0]!="0") || (count($lstCity)&&$lstCity[0] != "0")) $qrygeo.=" ) ";



        $qrywhere = '';

        foreach ($aSearchFileds as $field) {

            if ($field['table'] == 'adverts'){

                $query="SELECT sar_value FROM sarray WHERE sar_userid='$sql_array->sea_userid' AND sar_type='$field[name]'";

                $data = $db->get_col($query);

                $count=0;

                if (count($data)){

                    foreach ($data as $value) {

                        if ($value !=  $field['empty'] && $value !== '' && $value !== 0) {

                            if ($count==0) {

                                    $qrywhere.=" AND ($field[field]='$value' ";

                            } else {

                                    $qrywhere.=" OR $field[field]='$value' ";

                            }

                            $count++;

                        }

                    }

                    if ($count != 0) $qrywhere.=")";

                }

            }

        }



        # begin age range code

        #######################

        $qryAge=" AND ((YEAR(CURDATE())-YEAR(adv_dob)) - (RIGHT(CURDATE(),5) < RIGHT(adv_dob,5))) BETWEEN $sql_array->sea_agemin AND $sql_array->sea_agemax ";





        # find matching records for current member criteria

        $qryfind=" SELECT *,(YEAR(CURDATE())-YEAR(adv_dob)) - (RIGHT(CURDATE(),5) < RIGHT(adv_dob,5)) AS age, mem_sex

                   FROM adverts

                    LEFT JOIN members

						ON (adv_userid = mem_userid)

					LEFT JOIN geo_country

                        ON (adv_countryid = gcn_countryid)

                    LEFT JOIN geo_state

                        ON (adv_stateid = gst_stateid)

                    LEFT JOIN geo_city

                        ON (adv_cityid = gct_cityid)

                   WHERE adv_paused='N' $qrywhere $qryGender $qrygeo $qryAge

                   AND adv_createdate > '$sql_array2->par_lastcupidrun'

                   AND (adv_height >= '$sql_array->sea_minheight' AND adv_height <= '$sql_array->sea_maxheight' OR adv_height = 'Not stated')

                   AND adv_approved=1

                   ORDER BY adv_createdate DESC limit 20";

//dump($qryfind);

        $result2 = mysqli_query($globalMysqlConn,$qryfind) or die(mysqli_error()." ".$qryfind);

        $TOTAL = mysqli_num_rows($result2);

        # check if any records were found

        if ($TOTAL > 0) {

            $txtSubject=CUPID_MAIL_TITLE;

            if ($type == 'html') {

                $txtMessage="<div align='left'><table border='0' cellpadding='2' width='550'>

                    <tr>

                      <td colspan='3' width='540'><p align='center'><b><font face='Verdana' size='1'>

                    ".CUPID_TEXT1."

                    </font></b></p>

                        <p align='left'><font face='Verdana' size='2'>

                    ".GENERAL_DEAR." $sql_array->mem_username,<br><br>

                    ".CUPID_TEXT2."

                    </font></p>

                      </td>

                    </tr>

                    <tr>

                      <td width='528' colspan='3' height='20'></td>

                    </tr>";

            } else {

                $txtMessage=CUPID_TEXT3;

            }

            # loop through the found records

            while ($sql_array3 = mysqli_fetch_object($result2) ) {

                $adv->InitByObject($sql_array3);

                $adv->SetImage('small');

                $sql_array3 = $adv;

                # calculate the age



                if ($type == 'html') {

                    $sql_array->mem_username=trim($sql_array->mem_username);

                    $sql_array->mem_username=str_replace(" ","%20",$sql_array->mem_username);

                    $sql_array3->adv_title=stripslashes($sql_array3->adv_title);

                    $txtMessage=$txtMessage."<tr>

                        <td width='54' rowspan='3'><font face='Verdana' size='2'>

                        <img border='0' src='$CONST_LINK_ROOT{$sql_array3->adv_picture->Path}?=".time()."' width=\"{$sql_array3->adv_picture->w}\">

                        </font></td>

                        <td width='474' colspan='2'><font face='Verdana' size='2'><b><a href='$CONST_LINK_ROOT/prgretuser.php?userid=$sql_array3->adv_userid'>$sql_array3->adv_username</a></b> - $sql_array3->adv_title</font></td>

                    </tr>

                    <tr>

                      <td width='100'><font face='Verdana' size='2'><b>Age:&nbsp;</b>$sql_array3->age</font></td>

                      <td width='374'><font face='Verdana' size='2'><b>Location: </b>$sql_array3->full_address</font></td>

                    </tr>

                    <tr>

                      <td width='474' colspan='2'><font face='Verdana' size='2'>$sql_array3->adv_comment ...</font></td>

                    </tr>

                    <tr>

                      <td width='532' colspan='3'  ></td>

                    </tr>";

                } else {

                    $txtMessage=$txtMessage.CUPID_SCREEN.": $sql_array3->adv_username\n";

                    $txtMessage=$txtMessage.CUPID_REGION.": $sql_array3->full_address\n";

                    $txtMessage=$txtMessage.CUPID_AGE.": $sql_array3->age\n";

                    $txtMessage=$txtMessage.CUPID_PROFILE.": $sql_array3->adv_title\n";

//                  $txtMessage=$txtMessage.CUPID_PHOTO.": $hasphoto\n\n";

                }

            }

            $sent_mails++;

            # Add no spam statement to mails

            if ($type == 'html') {

                $txtMessage=$txtMessage.sprintf(CUPID_TEXT4,$sql_array->mem_email);

              send_mail ("$sql_array->mem_email", "$CONST_MAIL", "$txtSubject", "$txtMessage","html","ON", 'outside', 'UTF-8', true);

            } else {

                $message=$txtMessage.sprintf(CUPID_TEXT5,$sql_array->mem_username,$sql_array->mem_username,$sql_array->mem_password);

              send_mail ("$sql_array->mem_email", "$CONST_MAIL", "$txtSubject", "$message","text","ON", 'outside', 'UTF-8', true);

            }

            echo "<br>$sql_array->mem_email - $sql_array->mem_username - $type";

            flush();

        }

    }



    $result=mysqli_query($globalMysqlConn,"SELECT COUNT(sea_date) FROM search WHERE sea_date!=$nextrun");

    $total=mysqli_fetch_assoc($result);



    if ($total == 0) {

        # update the lastrun datetime in params

        $tempDate=date("Y-m-d H:i:s");

        $query="update params set par_lastcupidrun='$tempDate', par_lastrun=$nextrun";

        $result = mysqli_query($globalMysqlConn,$query ) or die(mysqli_error());

    }

    printf (CUPID_SENT,$sent_mails,$tot_searches);

exit;

}



?>



<?=$skin->ShowHeader($area)?>



<script language=javascript>



function send_cupid(){

    document.getElementById('progress').style.display = '';

    document.getElementById('log').contentWindow.location = '<?=$CONST_ADMIN_LINK_ROOT?>/cupid.php?SEND=1&lstNum'+document.getElementById('lstNum').value;

}



</script>



<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

  <tr>

    <td align="right">

        <?php

                if ($Sess_UserType == "A") {

                    print("<a href='$CONST_LINK_ROOT/admin/index.php'><img border='0' src='$CONST_IMAGE_ROOT/$CONST_IMAGE_LANG/mem_$Sess_Userlevel.gif' width='$CONST_MEMIMAGE_WIDTH' height='$CONST_MEMIMAGE_HEIGHT'>");

                } else {

                    print("<img border='0' src='$CONST_IMAGE_ROOT/$CONST_IMAGE_LANG/mem_$Sess_Userlevel.gif' width='$CONST_MEMIMAGE_WIDTH' height='$CONST_MEMIMAGE_HEIGHT'>");

                }

           ?>

    </td>

  </tr>

  <tr>

    <td class="pageheader"><?=ADM_CUPID_SECTION_NAME?></td>

  </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

  <tr>

    <td>

    <form action="" name="frmCupid" method="post">

    <?=CUPID_SEND_NUMBER?>&nbsp;<select class="inputf" name="lstNum" id="lstNum">

        <option value="" selected><?=CUPID_UNLIMITED?></option>

        <option value="LIMIT 100">100</option>

        <option value="LIMIT 200">200</option>

        <option value="LIMIT 400">400</option>

        <option value="LIMIT 600">600</option>

        <option value="LIMIT 800">800</option>

        <option value="LIMIT 1000">1000</option>

    </select>&nbsp;<input type="button" name="SEND" onClick="send_cupid();" class="button" value="<?php echo GENERAL_CONTINUE ?>" >

    </form>

     <br><br>

     <div id=progress style="display:none">

        <h3><?=QUEUE_MAILS?></h3>

        <iframe id=log src="" width=100% height=400px></iframe>

     </div>

     <br><br>

    </td>

  </tr>

</table>



<?=$skin->ShowFooter($area)?>