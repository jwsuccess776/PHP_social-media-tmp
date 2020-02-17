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
# Name:                 event_list.php
#
# Description:
#
# Version:                7.2
#
######################################################################
include('../db_connect.php');
include('session_handler.inc');
include('../message.php');

function clear_array($arr,$val){
    for($i=0;$i<length($arr);$i++){
        if ($arr[$i] == $val) unset($arr[$i]);
    }
    return $arr;
}

//$sql_condition = " WHERE se.sde_date > now() AND se.sde_is_special = 'no' ";
$sql_condition = " WHERE se.sde_date > now() ";

    if (!empty($search_age)) {
        $ages = explode("\/",$search_age);
//print_r($ages);
        $sql_condition .= " AND(
                                (
                                    se.sde_age_from <= '".$ages[0]."' AND se.sde_age_to >= '".$ages[1]."'
                                    OR
                                    se.sde_age_from >= '".$ages[0]."' AND se.sde_age_from <= '".$ages[1]."'
                                    OR
                                    se.sde_age_to >= '".$ages[0]."' AND se.sde_age_to <= '".$ages[1]."'
                                )
                               )
                            ";
    }
//}

    if (!empty($search_group)) {
        $groups = explode("\/",$search_group);
//print_r($groups);
        if ($groups[0] != $groups[1]){
            $sql_condition .= " AND(
                                        se.sde_gender1 != se.sde_gender2
                                   )
                                ";
        }else{
            $sql_condition .= " AND(
                                        se.sde_gender1 = se.sde_gender2 AND se.sde_gender1 = '".$groups[0]."'
                                   )
                                ";
        }
    }
//echo $sql_condition;
//$search_city = clear_array((array)$search_city,'-1');
$search_temp = array();
foreach ((array)$search_city as $name => $value) {
    if (!empty($value) && $value != '-1') {
        $search_temp[] = $value;
    }
}
$search_city = $search_temp;

//print_r(empty($search_city));
if (!empty($search_city)) {
    $init_search_city = join("', '", $search_city);
    $init_search_city = "'".$init_search_city."'";
    $sql_condition .= " AND sv.vnu_cityid IN (".$init_search_city.")";
}
else {
    $init_search_city = "";
    if (!empty($search_state) && $search_state!=-1) { //we don't have city so we search by state
        $sql_condition .= " AND sv.vnu_stateid = '".$search_state."'";
    }
    else {
        if (!empty($search_country) && $search_country != -1) { //we don't have state so we search by country
            $sql_condition .= " AND sv.vnu_countryid = '".$search_country."'";
        }
    }
}

//print_r($_POST);
//preparing for paging
require_once("dividing.php");

list($div_cur_page, $div_on_page) = divide_setup();
$div_page_name = "event_list.php";
$result = mysqli_query($globalMysqlConn," SELECT COUNT(se.sde_eventid) as qty
                        FROM sd_events se
                            INNER JOIN sd_venues sv
                                ON (se.sde_venueid  = sv.vnu_venueid )
                            LEFT JOIN geo_city gs
                                ON (sv.vnu_cityid   = gs.gct_cityid ) ".$sql_condition);
$result = mysqli_fetch_object($result);
$div_qty = $result->qty;

$required_param = array("search_gender"=>$search_gender, "search_age"=>$search_age, "search_city"=>$search_city, "search_state"=>$search_state, "search_country"=>$search_country);
$dividing = divide_results($div_cur_page, $div_on_page, $div_qty);
$div_str_top = result_str($div_cur_page, $div_on_page, $div_qty, $required_param, $dividing, "top_");
$div_str_bottom = result_str($div_cur_page, $div_on_page, $div_qty, $required_param, $dividing, "bottom_", false);
//end prepearing

include('../error.php');
# retrieve the template
$area = 'speeddating';

?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
    <tr>

    <td class="pageheader">
      <?=EVENT_LIST_SECTION_NAME?>
    </td>
    </tr>
    <tr>
      <td><table  width="100%" border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
        <tr>
          <td colspan="8" align="left" class="tdhead">
            <?=$div_str_top;?>
          </td>
        </tr>
        <?php
        $sql_query = "  SELECT *,COUNT(if(sdt_gender='Gender1',1,null)) G1_qty, COUNT(if(sdt_gender='Gender2',1,null)) G2_qty
                        FROM sd_events se
                            LEFT JOIN sd_tickets st
                                ON (se.sde_eventid = st.sdt_eventid)
                            INNER JOIN sd_venues sv
                                ON (se.sde_venueid  = sv.vnu_venueid )
                            LEFT JOIN geo_city gs
                                ON (sv.vnu_cityid   = gs.gct_cityid )
                        $sql_condition
                        GROUP BY se.sde_eventid
                        ORDER BY se.sde_date ASC
                        LIMIT ".$dividing["from"].", ".($dividing["till"] - $dividing["from"]);

//echo $sql_query;
        $sql_result = mysqli_query($globalMysqlConn,$sql_query);

        if ($div_qty > 0) {
            ?>
        <form method="post" action="<?php echo $CONST_LINK_ROOT?>/speeddating/events.php" name="frmPremFunc">
          <tr align="left" class="tdtoprow" >
            <td>
              <?=GENERAL_LOCATION?>
            </td>
            <td>
              <?=SD_EVENTS_NAME?>
            </td>
            <td>
              <?=SD_EVENT_LIST_PRICE?>
            </td>
            <td>
              <?=SD_EVENTS_DATE?>
            </td>
            <td>
              <?=SD_EVENT_LIST_AGE?>
            </td>
            <td>
              <?=SD_EVENT_LIST_AVAIBLE?>
            </td>
            <td>
<!--
              <?=SD_EVENT_LIST_SPECIAL?>
-->
            </td>
          </tr>
          <?php
                        while($event = mysqli_fetch_object($sql_result))
                        {
                            $zebra = ($zebra == "evtdodd") ? 'evtdeven' : 'evtdodd';
//                          print_r($event);
                            ?>
          <tr align="left" class="link <?=$zebra?>" style="cursor:pointer" onclick="location.href='<?=$CONST_LINK_ROOT?>/speeddating/event_info.php?sde_eventid=<?=$event->sde_eventid?>';">
            <td >
              <?=$event->gct_name?>
            </td>
            <td >
              <?=$event->sde_name?>
            </td>
            <td >
              <?=$CONST_SYMBOL;?>&nbsp;<?=$event->sde_price;?>
            </td>
            <td >
              <?=date($CONST_FORMAT_DATE_SHORT, strtotime($event->sde_date))?>
            </td>
            <td >
              <?=$event->sde_age_from."-".$event->sde_age_to;?>
            </td>
            <td align=center>
              <?php
                                    $available_gender1 = $event->sde_gender1_places - $event->G1_qty;
                                    $available_gender2 = $event->sde_gender2_places - $event->G2_qty;
                                    if ($event->sde_gender1 == $event->sde_gender2) {
                                        if ($available_gender1 > 0 || $available_gender2 > 0) {
                                            echo  constant("SD_INDEX_GENDER_".$event->sde_gender1);
                                        } else {
                                            echo SD_EVENT_LIST_SOLD;
                                        }
                                    } else {
                                        if ($available_gender1 > 0 && $available_gender2 > 0) {
                                            echo SD_INDEX_GENDER_B;
                                        } elseif ($available_gender1 <= 0 && $available_gender2 > 0) {
                                            echo constant("SD_INDEX_GENDER_".$event->sde_gender2);
                                        } elseif ($available_gender1 > 0 && $available_gender2 <= 0) {
                                            echo constant("SD_INDEX_GENDER_".$event->sde_gender1);
                                        } else {
                                            echo SD_EVENT_LIST_SOLD;
                                        }
                                    }
                                ?>
              &nbsp; </td>
            <td >
<!--
              <?=!empty($event->sde_special) ? $event->sde_special : "&nbsp;" ;?>
-->
            </td>
          </tr>
          <?php } ?>
          <? } else { ?>
          <tr>
            <td align="center" colspan="8" class="tdodd">
              <?=SD_EVENT_LIST_NOT_FOUND?>
            </td>
          </tr>
          <? } ?>
          <tr>
            <td colspan="8" align="left" class="tdfoot">&nbsp;</td>
          </tr>
          <tr>
            <td align="left" colspan="8">
              <?=$div_str_bottom;?>
            </td>
          </tr>
        </form>
      </table></td>
    </tr>
  </table>

<?php //mysqli_close( $link ); ?>
<?=$skin->ShowFooter($area)?>
