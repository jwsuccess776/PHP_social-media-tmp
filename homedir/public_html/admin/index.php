<?php

/*****************************************************

* © copyright 1999 - 2020 iDateMedia, LLC

*

* All materials and software are copyrighted by Interactive Arts Ltd.

* under US, British and International copyright law. All rights reserved.

* No part of this code may be reproduced, sold, distributed

* or otherwise used in whole or in part without prior written permission.

*

*****************************************************/

######################################################################

#

# Name:                 admin.php

#

# Description:  Administrators menu screen

#

# Version:                7.3

#

######################################################################
include('../db_connect.php');

include('../session_handler.inc');

require_once(__INCLUDE_CLASS_PATH.'/class.Group.php');

include('permission.php');

include("fusion/FusionCharts.php");

include_once __INCLUDE_CLASS_PATH."/class.Video.php";

require_once(__INCLUDE_CLASS_PATH.'/class.Group.php');

include_once __INCLUDE_CLASS_PATH."/class.Gallery.php";

?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<?php

$area = 'member';



//Time operations

$curr_time = time();

$mod_time = $curr_time - BLOCK_PERIOD_AVAILABLE;

$modified_time = date("Y-m-d H:i:s", $mod_time);

//EOF Time operations



$totAdverts = $db->get_var("SELECT COUNT(mem_expiredate) FROM members");

$totVideos = $db->get_var("SELECT COUNT(vid_id) FROM videos");

$totPhotos = $db->get_var("SELECT COUNT(pic_id) FROM pictures");

$totBlogs = $db->get_var("SELECT COUNT(blg_id) FROM blogs");

$totRatings = $db->get_var("SELECT COUNT(rte_userid) FROM ratings");

$totComments = $db->get_var("SELECT COUNT(id) FROM comments");

$totComments = $db->get_var("SELECT COUNT(id) FROM comments");

$totEvents = $db->get_var("SELECT COUNT(ev_eventid ) FROM events");

$totStandard = $db->get_var("select count(mem_expiredate) as TotOutput from members where mem_expiredate <= NOW()");

$totPremium = $db->get_var("select count(mem_expiredate) as TotOutput from members where mem_expiredate > NOW()");



?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

  <tr>

    <td align="right"><br><?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

    </td>

  </tr>

  <tr>

    <td class="pageheader"><?php echo ADM_SECTION_NAME ?></td>

  </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

  <tr>

    <td valign="top">

   <div class="home_box" style="height:75px; width:890px; " >

		<h2><?=ADM_QUICK_TASKS?></h2>

		<? include("quick_tasks.inc.php");?>

   </div>

	<div class="home_box" style="height:400px;; width:620px; float:left " >

		<h2><?=ADM_SIGNUPS?></h2>

        <?php

	

   //$strXML will be used to store the entire XML document generated

   //Generate the graph element

   // $strXML = "<graph caption='' subCaption='' decimalPrecision='0' showNames='1' rotateNames='1' numberSuffix='' formatNumberScale='0'>";

?>
 
 <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Member']<?php

         for ($day=12; $day >= 0; $day--) {

	  $month = date('m', strtotime("-$day months")); 

	  $year = date('Y', strtotime("-$day months")); 

	  $date2=date('M-Y', strtotime("-$day months"));

         

         $strQuery = "select count(mem_joindate) as TotOutput from members where MONTH(mem_joindate)=" . $month . " AND YEAR(mem_joindate)=" . $year;


         $result2 = mysqli_query($globalMysqlConn,$strQuery) or die(mysqli_error());

         $ors2 = mysqli_fetch_array($result2);

         echo ",['".$date2."', ".$ors2['TotOutput']."]";

         

         //free the resultset

         mysqli_free_result($result2);

      }



?>]);
 
        let options = {
          pieSliceText: 'value',
          legend: 'none'
        };

        let chart = new google.visualization.LineChart(document.getElementById('membersignuppiechart'));

        chart.draw(data, options);
      }
    </script>

<div id="membersignuppiechart" style="width: 100%; height: 80%;"></div>

<?php

   // echo renderChart("fusion/charts/FCF_Line.swf", "", $strXML, "crtRegister", 600, 350); ?>

      </div>

	  <div class="home_box" style="height:400px; width:250px; float:left;margin-left:17px;" >

		<h2><?=ADM_SITE_USAGE?></h2>

		<table width="100%" cellpadding="5">

			<tr>

				<td width="70%"><img  hspace="3" src="<?php echo $CONST_IMAGE_ROOT?>icons/icons_03.gif" /> <?=ADM_TOTAL_MEMBERS?>:</td>

				<td width="30%"><?=$totAdverts?></td>

			</tr>

			<tr>

				<td width="70%"><img  hspace="3" src="<?php echo $CONST_IMAGE_ROOT?>icons/icons_21.gif" / height="16px" width="16px"> <?=SEARCH_PREMIUM?>:</td>

				<td width="30%"><?=$totPremium?></td>

			</tr>

			<tr>

				<td width="70%"><img  hspace="3" src="<?php echo $CONST_IMAGE_ROOT?>icons/icons_34.gif" / height="16px" width="16px"> <?=ADM_STANDARD_MEMBERS?>:</td>

				<td width="30%"><?=$totStandard?></td>

			</tr>

			<tr>

				<td width="70%"><img  hspace="3" src="<?php echo $CONST_IMAGE_ROOT?>/<?=$CONST_IMAGE_LANG?>/video.gif" / height="16px" width="16px"> <?=ADM_TOTAL_VIDEOS?>:</td>

				<td width="30%"><?=$totVideos?></td>

			</tr>

			<tr>

				<td width="70%"><img  hspace="3" src="<?php echo $CONST_IMAGE_ROOT?>/icons/icons_14.gif" / > <?=ADM_TOTAL_PHOTOS?>:</td>

				<td width="30%"><?=$totPhotos?></td>

			</tr>

			<tr>

				<td width="70%"><img  hspace="3" src="<?php echo $CONST_IMAGE_ROOT?>/icons/blog.gif" / > <?=ADM_TOTAL_BLOGS?>:</td>

				<td width="30%"><?=$totBlogs?></td>

			</tr>

			<tr>

				<td width="70%"><img  hspace="3" src="<?php echo $CONST_IMAGE_ROOT?>/<?=$CONST_IMAGE_LANG?>/regular_smile.gif" height="16px" width="16px"/ > <?=ADM_TOTAL_RATINGS?>:</td>

				<td width="30%"><?=$totRatings?></td>

			</tr>

			<tr>

				<td width="70%"><img  hspace="3" src="<?php echo $CONST_IMAGE_ROOT?>/icons/icons_10.gif" / > <?=ADM_TOTAL_EVENTS?>:</td>

				<td width="30%"><?=$totComments?></td>

			</tr>

			<tr>

				<td width="70%"><img  hspace="3" src="<?php echo $CONST_IMAGE_ROOT?>/icons/icons_22.gif" / > <?=ADM_TOTAL_EVENTS?>:</td>

				<td width="30%"><?=$totEvents?></td>

			</tr>

			</table> 	

	  </div></td>

  </tr>

  <tr>

    <td><div class="home_box" style="height:400px; width:620px; float:left " >

		<h2><?=ADM_MEM_COUNTRY?></h2>

        <?php

	$query="SELECT DISTINCT(adv_countryid), gcn_name FROM adverts 

		LEFT JOIN geo_country ON (gcn_countryid = adv_countryid)";

         $result = mysqli_query($globalMysqlConn, $query) or die(mysqli_error($query));

   //$strXML will be used to store the entire XML document generated

   //Generate the graph element

   // $strXML = "<graph caption='' pieRadius='150' subCaption='' decimalPrecision='0' showNames='1' rotateNames='1' numberSuffix='' formatNumberScale='0'>";



   //Iterate through each factory


// Google chart

?>
<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        let data = google.visualization.arrayToDataTable([
          ['Task', 'Membership by Country'],
<?php

      $otherC=0;

	  while($ors = mysqli_fetch_object($result)) {



         //Now create a second query to get details for this factory€

         $strQuery = "select count(adv_countryid) as TotOutput from adverts where adv_countryid=".$ors->adv_countryid;

         $result2 = mysqli_query($globalMysqlConn,$strQuery) or die(mysqli_error($strQuery));

         $ors2 = mysqli_fetch_array($result2);

		 

		 $pcntge=(($ors2['TotOutput'] / $totAdverts)*100);

		 if ($pcntge < 5) {

		 	$otherC=$otherC+$ors2['TotOutput'];

			continue;

		 }

		$chartdata .= "['".$ors->gcn_name."', ".$ors2['TotOutput']."],";

		 //Generate <set name='..' value='..'/>

         // $strXML .= "<set name='" . $ors->gcn_name . "' value='" . $ors2['TotOutput'] . "' />";

         //free the resultset

         mysqli_free_result($result2);

      }

      $chartdata .= "['Other', ".$otherC."]";

      echo $chartdata;

?>

	]);

        let options = {
        	pieSliceText: 'value',
        };

        let chart = new google.visualization.PieChart(document.getElementById('membershipbyCountrypiechart'));

        chart.draw(data, options);
      }
    </script>

<div id="membershipbyCountrypiechart" style="width: 100%; height: 80%;"></div>

<?php

      // $strXML .= "<set name='Other' value='" . $otherC . "' />";



   //Finally, close <graph> element

   // $strXML .= "</graph>";



   //Create the chart - Pie 3D Chart with data from $strXML

     // echo renderChart("fusion/charts/FCF_Pie3D.swf", "", $strXML, "crtCountry", 600, 350); 
?>

		</div>

	  <div class="home_box" style="height:200px; width:250px; float:left;margin-left:17px;" >

		<h2><?=ADM_GENDER_STATS?></h2>

        <?php

	$query="SELECT DISTINCT(mem_sex) FROM members";

         $result = mysqli_query($globalMysqlConn,$query) or die(mysqli_error($query));

   //$strXML will be used to store the entire XML document generated

   //Generate the graph element

   // $strXML = "<graph caption='' pieRadius='40' subCaption='' decimalPrecision='0' showNames='1' rotateNames='1' numberSuffix='' formatNumberScale='0'>";



   //Iterate through each factory


?>
<script type="text/javascript">
     google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        let data = google.visualization.arrayToDataTable([
          ['Task', 'Gender Statistics'],
<?php


	  while($ors = mysqli_fetch_object($result)) {



         //Now create a second query to get details for this factory€

         $strQuery = "select count(mem_sex) as TotOutput from members where mem_sex='".$ors->mem_sex."'";

         $result2 = mysqli_query($globalMysqlConn,$strQuery) or die(mysqli_error($strQuery));

         $ors2 = mysqli_fetch_array($result2);

		 

		if ($ors->mem_sex=='M') $mygender= PRGSTATS_MALES;

		elseif ($ors->mem_sex=='F') $mygender= PRGSTATS_FEMALES;

		elseif ($ors->mem_sex=='C') $mygender= PRGSTATS_COUPLE;

		 		 

		 //Generate <set name='..' value='..'/>

		echo "['".$mygender."', ".$ors2['TotOutput']."],";

         // $strXML .= "<set name='" . $mygender . "' value='" . $ors2['TotOutput'] . "' />";

         //free the resultset

         mysqli_free_result($result2);

      }

?>

  ]);

        let options = {
          pieSliceText: 'value',
          legend: 'none'
        };

        let chart = new google.visualization.PieChart(document.getElementById('genderStatisticspiechart'));

        chart.draw(data, options);
      }
    </script>

<div id="genderStatisticspiechart" style="width: 100%; height: 80%;"></div>

<?php

   //Finally, close <graph> element

   // $strXML .= "</graph>";



   //Create the chart - Pie 3D Chart with data from $strXML

     // echo renderChart("fusion/charts/FCF_Pie2D.swf", "", $strXML, "crtGender", 240, 150); ?>

		</div>

	  <div class="home_box" style="height:190px; width:250px; float:left;margin-left:17px;" >

		<h2><?=ADM_MEMBER_STATUS?></h2>

        <?php

   //$strXML will be used to store the entire XML document generated

   //Generate the graph element

   // $strXML = "<graph caption='' pieRadius='40' subCaption='' decimalPrecision='0' showNames='1' rotateNames='1' numberSuffix='' formatNumberScale='0'>";

?>
<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        let data = google.visualization.arrayToDataTable([
          ['Task', 'Member Status'],
<?php

	 //Now create a second query to get details for this factory€

	 $strQuery = "select count(mem_expiredate) as TotOutput from members where mem_expiredate > NOW()";

	 $result2 = mysqli_query($globalMysqlConn,$strQuery) or die(mysqli_error($strQuery));

	 $ors2 = mysqli_fetch_array($result2);

	 

	 //Generate <set name='..' value='..'/>

	 echo "['Premium', ".$ors2['TotOutput']."],";

	 // $strXML .= "<set color='663300' name='Premium' value='" . $ors2['TotOutput'] . "' />";

	 //free the resultset

	 mysqli_free_result($result2);

	 

	 //Now create a second query to get details for this factory€

	 $strQuery = "select count(mem_expiredate) as TotOutput from members where mem_expiredate <= NOW()";

	 $result2 = mysqli_query($globalMysqlConn,$strQuery) or die(mysqli_error($strQuery));

	 $ors2 = mysqli_fetch_array($result2);

	 

	 //Generate <set name='..' value='..'/>

	 echo "['Standard', ".$ors2['TotOutput']."],";

	 // $strXML .= "<set color='F7941C' name='Standard' value='" . $ors2['TotOutput'] . "' />";

	 //free the resultset

	 mysqli_free_result($result2);



   //Finally, close <graph> element

   // $strXML .= "</graph>";



   //Create the chart - Pie 3D Chart with data from $strXML


?>

  ]);

        let options = {
          pieSliceText: 'value',
          legend: 'none',
        };

        let chart = new google.visualization.PieChart(document.getElementById('memberStatuspiechart'));

        chart.draw(data, options);
      }
    </script>

<div id="memberStatuspiechart" style="width: 100%; height: 80%;"></div>

<?php

     // echo renderChart("fusion/charts/FCF_Pie2D.swf", "", $strXML, "crtGender2", 240, 150); ?>

	 		</div>

		</td>

  </tr>

</table>

<?=$skin->ShowFooter($area)?>

