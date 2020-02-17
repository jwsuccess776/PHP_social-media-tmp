<?php
/*****************************************************
* � copyright 1999 - 2020 iDateMedia, LLC.
*
* All materials and software are copyrighted by iDateMedia, LLC.
* under British, US and International copyright law. All rights reserved.
* No part of this code may be reproduced, sold, distributed
* or otherwise used in whole or in part without prior written permission.
*
****************************************************/

######################################################################
#
# Name:         eventscalendar.php
#
# Description:  Displays the profile input page (after advert)
#
# Version:      7.3
#
######################################################################
include('db_connect.php');
include('session_handler.inc');
include('error.php');
include_once('validation_functions.php'); 
?>
<script language="javascript">
function submitMonthYear() {
    document.monthYear.method = "post";
    document.monthYear.action = "eventscalendar.php?month=" + document.monthYear.month.value + "&year=" + document.monthYear.year.value;
    document.monthYear.submit();
}
</script>
<?php
function db_to_form($date, $delimiter="." ) {
      $d = array();
      $d[day] = substr($date, 6, 2);
      $d[month] = substr($date, 4, 2);
      $d[year] = substr($date, 0, 4);
      $d[hours] = substr($date, 8, 2);
      $d[minutes] = substr($date, 10, 2);
      return $d[month].$delimiter.$d[day].$delimiter.$d[year]." ".$d[hours].":".$d[minutes];
  }
# Events Calendar Writing Function
$lang['months'] = array(MONTH_F_JAN, MONTH_F_FEB, MONTH_F_MAR, MONTH_F_APR, MONTH_F_MAY, MONTH_F_JUN,
                        MONTH_F_JUL, MONTH_F_AUG, MONTH_F_SEP, MONTH_F_OCT, MONTH_F_NOV, MONTH_F_DEC);
$lang['days'] = array(DAY_SUNDAY, DAY_MONDAY, DAY_TUESDAY, DAY_WEDNESDAY, DAY_THURSDAY, DAY_FRIDAY, DAY_SATURDAY);
$lang['abrvdays'] = array(DAY_SUN, DAY_MON, DAY_TUE, DAY_WED, DAY_THUR, DAY_FRI, DAY_SAT);

function writeCalendar($month, $year)
{
    global $lang, $globalMysqlConn;
    $str = "<table cellpadding=\"1\" cellspacing=\"1\" border=\"0\">\n<tr class=\"tdtoprow\">  \n";
    foreach($lang['abrvdays'] as $day) {
        $str .= "\t<td class=\"tdtoprow\">&nbsp;$day</td>\n";
    }
    $str .= "</tr>\n\n";
    $query = "SELECT DAYOFMONTH(ev_schedule) as d , MONTH(ev_schedule) as m, YEAR(ev_schedule) as y, ev_eventid, ev_eventname FROM events WHERE MONTH(ev_schedule) = $month AND YEAR(ev_schedule) = $year AND ev_approved='1' ORDER BY ev_schedule";
    //echo $query;
    $result = mysqli_query($globalMysqlConn, $query) or die(mysqli_error());
    // create array with title, id, and schedule info for each event
    while($row = mysqli_fetch_assoc($result)) {
        $eventinfo[$row["d"]]["ev_eventid"][] = $row["ev_eventid"];
        $eventinfo[$row["d"]]["ev_eventname"][] = stripslashes($row["ev_eventname"]);
    }
    // get number of days in month
    $days = 31-((($month-(($month<8)?1:0))%2)+(($month==2)?((!($year%((!($year%100))?400:4)))?1:2):0));
    // get week position of first day of month.
    $weekpos = date("w",mktime(0,0,0,$month,1,$year));
    $day = ($weekpos == 0) ? 1 : 0;
    // today's date variables for color change
    $d = date('j'); $m = date('n'); $y = date('Y');
    // loop writes empty cells until it reaches position of 1st day of month ($wPos)
    // it writes the days, then fills the last row with empty cells after last day
    while($day <= $days) {
        $str .="<tr>\n";
        for($i=0;$i < 7; $i++) {
            if($day > 0 && $day <= $days) {
                $str .= "	<td class=\"";
                if (($day == $d) && ($month == $m) && ($year == $y)) {
                    $str .= "today";
                } else {
                    $str .= "day";
                }
                $str .= "_cell\" valign=\"top\"><span class=\"day_number\">";
                    $str .= "$day";
                $str .= "</span><br>";
                // enforce title limit
                // print_r($eventinfo[$day]["ev_eventname"]);
                //if ($titlelimit < $titles) { $titles = $titlelimit; }
                // write title link if posting exists for day
                if($eventinfo[$day]["ev_eventname"][0]) {
                  $titles = count($eventinfo[$day]["ev_eventname"]);
                    for($j=0;$j < $titles;$j++) {
                        $str .= "<span style=\"font-size=10;text-align:center\">-";
                        $str .= "<a href=\"viewevent.php?eventid=". $eventinfo[$day]["ev_eventid"][$j]."\">";
                        $str .= $eventinfo[$day]["ev_eventname"][$j] . "</a></span>";
                    }
                }
                $str .= "</td>\n";
                $day++;
            } elseif($day == 0)  {
                 $str .= "	<td class=\"empty_day_cell\" valign=\"top\">&nbsp;</td>\n";
                $weekpos--;
                if ($weekpos == 0) {$day++;}
             } else {
                $str .= "	<td class=\"empty_day_cell\" valign=\"top\">&nbsp;</td>\n";
            }
         }
        $str .= "</tr>\n\n";
    }
    $str .= "</table>\n\n";
    return $str;
}
###############################################################################
function monthPullDown($month, $montharray)
{
    echo "\n<select name=\"month\"  onchange=\"submitMonthYear()\" class=\"inputs\">\n";
    for($i=0;$i < 12; $i++) {
        if ($i != ($month - 1)) {
            echo "	<option value=\"" . ($i + 1) . "\">$montharray[$i]</option>\n";
        } else {
            echo "	<option value=\"" . ($i + 1) . "\" selected>$montharray[$i]</option>\n";
        }
    }
    echo "</select>\n\n";
}
function yearPullDown($year)
{
    echo "<select name=\"year\" onchange=\"submitMonthYear()\" class=\"inputs\">\n";
    $z = 3;
    for($i=1;$i < 8; $i++) {
        if ($z == 0) {
            echo "	<option value=\"" . ($year - $z) . "\" selected>" . ($year - $z) . "</option>\n";
        } else {
            echo "	<option value=\"" . ($year - $z) . "\">" . ($year - $z) . "</option>\n";
        }
        $z--;
    }
    echo "</select>\n\n";
}
#################
# retrieve the template
	$area = 'member';

# retrieve the events
$query="SELECT * FROM events WHERE ev_approved='1' ORDER by ev_schedule desc";
$retval=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());
$count = mysqli_num_rows($retval);
$month = (empty($_GET["month"]))?0: sanitizeData($_GET['month'], 'xss_clean');    
$year = (empty($_GET["year"]))?0:sanitizeData($_GET['year'], 'xss_clean');  
$m = (!$month) ? date("n") : $month;
$y = (!$year) ? date("Y") : $year;
?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
  </tr>

  <tr>
    <td class="pageheader"><?=HOME_CALENDAR?></td>
  </tr>
  <tr>
    <td> <table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">
        <tr>
         <form name="monthYear">
                  <td align="center" class="tdhead">
                    <? monthPullDown($m, $lang['months']); yearPullDown($y); ?>
                     </td>
                  <!-- form tags must be outside of <td> tags -->
                </form>
        </tr>
        <tr class="tdodd">
          <td> <table cellpadding="0" cellspacing="0" border="0" align="center">

              <tr>
                <td colspan="2"> <? echo writeCalendar($m, $y); ?> </td>
              </tr>
              <tr>
                <td colspan="2" align="center"> </td>
              </tr>
            </table></td>
        </tr>
        <tr align="center">
          <td class='tdfoot'>&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>
