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



# Name:                 my_interests.php



#



# Description:  Main search processing program



#



# Version:                7.2



#



######################################################################



include('db_connect.php');



include('session_handler.inc');



include('error.php');



include('functions.php');



require_once ( __INCLUDE_CLASS_PATH . '/class.RadiusAssistant.php' );



save_request();







# retrieve the template



$area = 'member';







include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";



$adv = new Adverts();











if ( $chkDelete= formGet('chkDelete') ) {



    if ($mode == 0) {



        foreach ( $chkDelete as $value) {



            $db->query("DELETE FROM notifications WHERE ntf_receiverid=$value AND ntf_senderid=$Sess_UserId");



        }



    } else {



        foreach ( $chkDelete as $value) {



                $db->query("DELETE FROM notifications WHERE ntf_senderid=$value AND ntf_receiverid=$Sess_UserId");



        }



    }



}







if ($mode == 0) {







$query="SELECT *, (YEAR(CURDATE())-YEAR(adv_dob)) - (RIGHT(CURDATE(),5) < RIGHT(adv_dob,5)) AS age, unix_timestamp(mem_timeout) AS session_active,



            mem_timeout



        FROM adverts







            LEFT JOIN members



                ON (adv_userid=mem_userid)



            LEFT JOIN geo_country



                ON (adv_countryid = gcn_countryid)



            LEFT JOIN geo_state



                ON (adv_stateid = gst_stateid)



            LEFT JOIN geo_city



                ON (adv_cityid = gct_cityid)



            LEFT JOIN notifications



                ON (adv_userid=ntf_receiverid)



        WHERE (adv_approved=1)



            AND adv_paused='N' AND ntf_senderid = $Sess_UserId



        ORDER BY ntf_dateadded desc";



} else {



$query="SELECT *, (YEAR(CURDATE())-YEAR(adv_dob)) - (RIGHT(CURDATE(),5) < RIGHT(adv_dob,5)) AS age, unix_timestamp(mem_timeout) AS session_active,



            mem_timeout



        FROM adverts







            LEFT JOIN members



                ON (adv_userid=mem_userid)



            LEFT JOIN geo_country



                ON (adv_countryid = gcn_countryid)



            LEFT JOIN geo_state



                ON (adv_stateid = gst_stateid)



            LEFT JOIN geo_city



                ON (adv_cityid = gct_cityid)



            LEFT JOIN notifications



                ON (adv_userid=ntf_senderid)



        WHERE (adv_approved=1)



            AND adv_paused='N' AND ntf_receiverid = $Sess_UserId



        ORDER BY ntf_dateadded desc";



}



$result=mysqli_query($globalMysqlConn, $query);







?>

<?=$skin->ShowHeader($area)?>



<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

  <tr>

    <td align="right"><?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

    </td>

  </tr>

  <tr>

    <td ><div id="mail">

        <ul id="mailnav">

          <li><a href='<?php echo $CONST_LINK_ROOT?>/my_interests.php' <?if ($mode == 0){?>id='current'<?}?>> <?php echo HOME_MY_INTERESTS ?></a> </li>

          <li><a href='<?php echo $CONST_LINK_ROOT?>/my_interests.php?mode=1' <?if ($mode == 1){?>id='current'<?}?>> <?php echo HOME_INTERESTED_IN_ME ?></a> </li>

        </ul>

      </div>

  </tr>

  <tr>

    <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

        <form method="post" action="<?php echo $CONST_LINK_ROOT ?>/my_interests.php" name="FrmMyIntList">

          <tr >

            <td align="right"><input type="button" value="<?=MYEMAIL_SELECT_ALL?>" class="button" onclick="selectAll('FrmMyIntList','chkDelete[]',true);">

              <input type="submit" name="submit" value="<?=BUTTON_REMOVE?>" class="button">

              &nbsp; </td>

          </tr>

          <tr>

            <td><?php



# insert the line code here



$curr_row_num = 0;



$row_count = mysqli_num_rows($result);



while ($sql_array = mysqli_fetch_object($result) ) {



    $adv->InitByObject($sql_array);



    $adv->SetImage('small');



    $sql_array = $adv;



    include("user_list.inc.php");



}



?>

            </td>

          </tr>

        </form>

      </table></td>

  </tr>

</table>

<?=$skin->ShowFooter($area)?>

