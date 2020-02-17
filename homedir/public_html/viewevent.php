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

# Name: 		viewevent.php

#

# Description:  Displays the profile input page (after advert)

#

# Version:		7.3

#

######################################################################

include('db_connect.php');

include('session_handler.inc');

include('error.php');

include_once('validation_functions.php'); 

save_request();

$eventid =sanitizeData($_REQUEST['eventid'], 'xss_clean') ;   

function db_to_form($date, $delimiter="." ) {

      $d = array();

      $d['day'] = substr($date, 6, 2);

      $d['month'] = substr($date, 4, 2);

      $d['year'] = substr($date, 0, 4);

      $d['hours'] = substr($date, 8, 2);

      $d['minutes'] = substr($date, 10, 2);

      return $d['month'].$delimiter.$d['day'].$delimiter.$d['year']." ".$d['hours'].":".$d['minutes'];

  }

# retrieve the template

$area = 'member';



# retrieve the events

$query="SELECT *

        FROM events

            LEFT JOIN geo_country

                ON (gcn_countryid = ev_country)

            LEFT JOIN geo_state

                ON (gst_stateid = ev_state)

            LEFT JOIN geo_city

                ON (gct_cityid = ev_city)

        WHERE ev_eventid='$eventid'";

$retval=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

$row = mysqli_fetch_array($retval);

$address = array();

if ($row['gcn_name']) $address[] = $row['gct_name'];

if ($row['gst_name']) $address[] = $row['gst_name'];

if ($row['gct_name']) $address[] = $row['gcn_name'];

$address = join(', ',$address);

?>

<?=$skin->ShowHeader($area)?>

  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>

      <td align="right">

            <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>



    <td class="pageheader"><?php echo VIEWEVENTS_SECTION_NAME ?></td>

    </tr>

    <tr>

      <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

        <tr>

          <td  colspan="2" align="left" class="tdhead" >

            <?= $row['ev_eventname']?>

          </td>

        </tr>

        <tr >

          <td align="left" class="tdodd">

            <?= $row['ev_address']?>

            <br>

            <?= $address;?>

          </td>

          <td align="left" class="tdodd">

            <?php if (trim($row['ev_picture'])<>"") echo "<a rel='lightbox' id='bigpicture' href='$CONST_LINK_ROOT".$row['ev_picture']."'><img src='$CONST_LINK_ROOT".$row['ev_picture']."' width=\"151\"  border=\"0\"></a>" ?>

          </td>

        </tr>

        <tr >

          <td  colspan="2" align="left" class="tdeven">

            <?= $row['ev_phone']?>

          </td>

        </tr>

        <tr >

          <td  colspan="2" align="left" class="tdfoot"><a href="<?= $row['ev_website']?>" target="_blank"><?= $row['ev_website']?></a>&nbsp;</td>

       </tr> <td  colspan="2" align="left" >&nbsp;

           </td><tr>

          <td  colspan="2" align="left" class="tdhead"><?php echo GENERAL_DESCRIPTION ?></td>

        </tr>

        <tr>

        </tr>

        <tr >

          <td  colspan="2" align="left" class="tdodd">

            <?= $row['ev_description']?>

          </td>

        </tr>

        <tr >

          <td  colspan="2" align="left" class="tdfoot"><a href="<?php echo $CONST_LINK_ROOT ?>/addreview.php?id=<?= $row['ev_eventid']?>&type=event"><?php echo GENERAL_ADREVIEW ?></a></td>

        </tr>

        <?php

    # get all the review for the current event and list them

    $query="SELECT *

            FROM reviews

                INNER JOIN members

                    ON (review_userid = mem_userid)

                INNER JOIN `events`

                    ON (review_id = ev_eventid AND ev_eventid = $eventid  )

            WHERE review_approved = '1'

            ORDER BY review_createdate DESC";

//echo $query;

    $result=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

    while ($mysql_array=mysqli_fetch_object($result))

    {

    ?>

    <tr>

          <td  colspan="2" align="left" valign="top">&nbsp;</td>

        </tr>

     <tr>

          <td  colspan="2" align="left" class="tdhead"> <?php echo GENERAL_REVIEWS ?></td>

        </tr>

        <tr >

          <td  colspan="2" align="left" class="tdodd">

            <?= $mysql_array->review_text ?>

            <br/>

            <?php echo GENERAL_POSTED ?>: <a href="<?php echo $CONST_LINK_ROOT ?>/prgretuser.php?userid=<?= $mysql_array->mem_userid ?>">

            <?= $mysql_array->mem_username ?>

            </a></td>

        </tr>



        <tr>

          <td  colspan="2" align="left" valign="top" class="tdfoot">&nbsp;</td>

        </tr>

        <?php } ?>



      </table></td>

    </tr>

  </table>

<?=$skin->ShowFooter($area)?>