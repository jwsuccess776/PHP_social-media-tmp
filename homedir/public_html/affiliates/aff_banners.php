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

# Name:         aff_banners.php

#

# Description:  authorise affiliate

#

# Version:      7.2

#

######################################################################

include('../db_connect.php');

include_once('../validation_functions.php');

include('../session_handler.inc');

include('error.php');

include('../admin/permission.php');



# retrieve the template

$area = 'member';



if (isset($_GET['id'])) {

	$id=sanitizeData(trim($_GET['id']), 'xss_clean'); 

}



if (isset($_POST['submit'])) {



	$submit=$_POST['submit'];



	switch ($submit) {

		case 'Save Banner':

			$endday=sanitizeData($_POST['lstEndDay'], 'xss_clean');     

			$endmonth=sanitizeData($_POST['lstEndMonth'], 'xss_clean');    

			$endyear=sanitizeData($_POST['txtEndYear'], 'xss_clean');   

			$end_date=$endyear."-".$endmonth."-".$endday;



			$startday=sanitizeData($_POST['lstStartDay'], 'xss_clean');   

			$startmonth=sanitizeData($_POST['lstStartMonth'], 'xss_clean');

			$startyear=sanitizeData($_POST['txtStartYear'], 'xss_clean');  

			$start_date=$startyear."-".$startmonth."-".$startday;



			$picture=sanitizeData($_POST['txtPicture'], 'xss_clean'); 

			$text=addslashes(sanitizeData($_POST['txtText'], 'xss_clean'));



			if (isset($id) && $id != "") {

				# update banner record

				if ($_FILES['fupload']['size'] != 0) {

					$query="SELECT * FROM banners WHERE ban_id = $id";

					$retval=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

					if (mysqli_num_rows($retval) > 0 && !empty($picture)) {

						$sql_picture=mysqli_fetch_object($retval);

						@unlink("banners/$sql_picture->ban_picture");

					}

					$targetfile="banners/".$_FILES['fupload']['name'];

					copy($_FILES['fupload']['tmp_name'],"$targetfile");

					$picture=$_FILES['fupload']['name'];

				}

				$query="UPDATE banners SET ban_start='$start_date', ban_end='$end_date', ban_text='$text', ban_picture='$picture' WHERE ban_id = $id";

				mysqli_query($globalMysqlConn,$query) or die(mysqli_error());



			} else {



				# insert banner record



				if ($_FILES['fupload']['size'] != 0) {

					$targetfile="banners/".$_FILES['fupload']['name'];

					copy($_FILES['fupload']['tmp_name'],"$targetfile");

					$picture=$_FILES['fupload']['name'];

				} else {

					error_page(AFF_LOAD_BANNER,GENERAL_USER_ERROR);

				}

				$query="INSERT INTO banners (ban_start, ban_end, ban_text, ban_picture) VALUES ('$start_date', '$end_date', '$text', '$picture')";

				mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

			}

			header("Location: $CONST_LINK_ROOT/affiliates/aff_banlist.php");

			break;

		case 'Banner list':

			header("Location: $CONST_LINK_ROOT/affiliates/aff_banlist.php");

			break;

	}

}



if (isset($id)) {



	$result=mysqli_query($globalMysqlConn,"SELECT * FROM banners WHERE ban_id=$id");

	$sql_array=mysqli_fetch_object($result);



	$startday=trim(substr($sql_array->ban_start,8,2));

	$startmonth=trim(substr($sql_array->ban_start,5,2));

	$startyear=trim(substr($sql_array->ban_start,2,2));



	$endday=trim(substr($sql_array->ban_end,8,2));

	$endmonth=trim(substr($sql_array->ban_end,5,2));

	$endyear=trim(substr($sql_array->ban_end,2,2));



	$text=stripslashes($sql_array->ban_text);

	$picture=$sql_array->ban_picture;



}



// mysqli_close($link);

?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

  <tr>

    <td>

<table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

        <form action="<?php echo $CONST_LINK_ROOT?>/affiliates/aff_banners.php?id=<?php echo $id ?>" enctype='multipart/form-data' method="post" name="frmBanners" >

          <tr align="left">

            <td  colspan="2" class="help"><?php echo AFF_HELP_BANNER ?></td>

          </tr>

          <tr align="left">

            <td  colspan="2" class="tdhead">&nbsp;</td>

          </tr>

          <tr align="left" class="tdodd" >

            <td  >Banner Link Text </td>

            <td >

				  <input name="txtText" type="text" class="input" size="35" value="<?php echo $text ?>">

        <input type="hidden" name="mode" value="">

        	<input type="hidden" name="txtPicture" value="<?php echo $picture ?>">			</td>

          </tr>

          <tr align="left" class="tdeven" >

            <td  >From Date </td>

            <td > <select class="inputf" size="1" name="lstStartDay" >

                <option <?php if ($startday == "00") { print("selected");} ?> value="00">--</option>

                <option <?php if ($startday == "01") { print("selected");} ?> value="01">01</option>

                <option <?php if ($startday == "02") { print("selected");} ?> value="02">02</option>

                <option <?php if ($startday == "03") { print("selected");} ?> value="03">03</option>

                <option <?php if ($startday == "04") { print("selected");} ?> value="04">04</option>

                <option <?php if ($startday == "05") { print("selected");} ?> value="05">05</option>

                <option <?php if ($startday == "06") { print("selected");} ?> value="06">06</option>

                <option <?php if ($startday == "07") { print("selected");} ?> value="07">07</option>

                <option <?php if ($startday == "08") { print("selected");} ?> value="08">08</option>

                <option <?php if ($startday == "09") { print("selected");} ?> value="09">09</option>

                <option <?php if ($startday == "10") { print("selected");} ?> value="10">10</option>

                <option <?php if ($startday == "11") { print("selected");} ?> value="11">11</option>

                <option <?php if ($startday == "12") { print("selected");} ?> value="12">12</option>

                <option <?php if ($startday == "13") { print("selected");} ?> value="13">13</option>

                <option <?php if ($startday == "14") { print("selected");} ?> value="14">14</option>

                <option <?php if ($startday == "15") { print("selected");} ?> value="15">15</option>

                <option <?php if ($startday == "16") { print("selected");} ?> value="16">16</option>

                <option <?php if ($startday == "17") { print("selected");} ?> value="17">17</option>

                <option <?php if ($startday == "18") { print("selected");} ?> value="18">18</option>

                <option <?php if ($startday == "19") { print("selected");} ?> value="19">19</option>

                <option <?php if ($startday == "20") { print("selected");} ?> value="20">20</option>

                <option <?php if ($startday == "21") { print("selected");} ?> value="21">21</option>

                <option <?php if ($startday == "22") { print("selected");} ?> value="22">22</option>

                <option <?php if ($startday == "23") { print("selected");} ?> value="23">23</option>

                <option <?php if ($startday == "24") { print("selected");} ?> value="24">24</option>

                <option <?php if ($startday == "25") { print("selected");} ?> value="25">25</option>

                <option <?php if ($startday == "26") { print("selected");} ?> value="26">26</option>

                <option <?php if ($startday == "27") { print("selected");} ?> value="27">27</option>

                <option <?php if ($startday == "28") { print("selected");} ?> value="28">28</option>

                <option <?php if ($startday == "29") { print("selected");} ?> value="29">29</option>

                <option <?php if ($startday == "30") { print("selected");} ?> value="30">30</option>

                <option <?php if ($startday == "31") { print("selected");} ?> value="31">31</option>

                  </select> <select class="inputf" size="1" name="lstStartMonth" >

                <option <?php if ($startmonth == "00") { print("selected");} ?> value="00">--</option>

                <option <?php if ($startmonth == "01") { print("selected");} ?> value="01"><?=MONTH_JAN?></option>

                <option <?php if ($startmonth == "02") { print("selected");} ?> value="02"><?=MONTH_FEB?></option>

                <option <?php if ($startmonth == "03") { print("selected");} ?> value="03"><?=MONTH_MAR?></option>

                <option <?php if ($startmonth == "04") { print("selected");} ?> value="04"><?=MONTH_APR?></option>

                <option <?php if ($startmonth == "05") { print("selected");} ?> value="05"><?=MONTH_MAY?></option>

                <option <?php if ($startmonth == "06") { print("selected");} ?> value="06"><?=MONTH_JUN?></option>

                <option <?php if ($startmonth == "07") { print("selected");} ?> value="07"><?=MONTH_JUL?></option>

                <option <?php if ($startmonth == "08") { print("selected");} ?> value="08"><?=MONTH_AUG?></option>

                <option <?php if ($startmonth == "09") { print("selected");} ?> value="09"><?=MONTH_SEP?></option>

                <option <?php if ($startmonth == "10") { print("selected");} ?> value="10"><?=MONTH_OCT?></option>

                <option <?php if ($startmonth == "11") { print("selected");} ?> value="11"><?=MONTH_NOV?></option>

                <option <?php if ($startmonth == "12") { print("selected");} ?> value="12"><?=MONTH_DEC?></option>

                  </select>

				  <input type="text" class="inputf" name="txtStartYear" size="5" value="<?php echo $startyear ?>">

				</td>

          </tr>

          <tr align="left" class="tdodd" >



            <td  >To Date </td>

            <td > <select class="inputf" size="1" name="lstEndDay" >

                <option <?php if ($endday == "00") { print("selected");} ?> value="00">--</option>

                <option <?php if ($endday == "01") { print("selected");} ?> value="01">01</option>

                <option <?php if ($endday == "02") { print("selected");} ?> value="02">02</option>

                <option <?php if ($endday == "03") { print("selected");} ?> value="03">03</option>

                <option <?php if ($endday == "04") { print("selected");} ?> value="04">04</option>

                <option <?php if ($endday == "05") { print("selected");} ?> value="05">05</option>

                <option <?php if ($endday == "06") { print("selected");} ?> value="06">06</option>

                <option <?php if ($endday == "07") { print("selected");} ?> value="07">07</option>

                <option <?php if ($endday == "08") { print("selected");} ?> value="08">08</option>

                <option <?php if ($endday == "09") { print("selected");} ?> value="09">09</option>

                <option <?php if ($endday == "10") { print("selected");} ?> value="10">10</option>

                <option <?php if ($endday == "11") { print("selected");} ?> value="11">11</option>

                <option <?php if ($endday == "12") { print("selected");} ?> value="12">12</option>

                <option <?php if ($endday == "13") { print("selected");} ?> value="13">13</option>



                <option <?php if ($endday == "14") { print("selected");} ?> value="14">14</option>



                <option <?php if ($endday == "15") { print("selected");} ?> value="15">15</option>



                <option <?php if ($endday == "16") { print("selected");} ?> value="16">16</option>



                <option <?php if ($endday == "17") { print("selected");} ?> value="17">17</option>



                <option <?php if ($endday == "18") { print("selected");} ?> value="18">18</option>



                <option <?php if ($endday == "19") { print("selected");} ?> value="19">19</option>



                <option <?php if ($endday == "20") { print("selected");} ?> value="20">20</option>



                <option <?php if ($endday == "21") { print("selected");} ?> value="21">21</option>



                <option <?php if ($endday == "22") { print("selected");} ?> value="22">22</option>



                <option <?php if ($endday == "23") { print("selected");} ?> value="23">23</option>



                <option <?php if ($endday == "24") { print("selected");} ?> value="24">24</option>



                <option <?php if ($endday == "25") { print("selected");} ?> value="25">25</option>



                <option <?php if ($endday == "26") { print("selected");} ?> value="26">26</option>



                <option <?php if ($endday == "27") { print("selected");} ?> value="27">27</option>



                <option <?php if ($endday == "28") { print("selected");} ?> value="28">28</option>



                <option <?php if ($endday == "29") { print("selected");} ?> value="29">29</option>



                <option <?php if ($endday == "30") { print("selected");} ?> value="30">30</option>



                <option <?php if ($endday == "31") { print("selected");} ?> value="31">31</option>

                  </select> <select class="inputf" size="1" name="lstEndMonth" >



                <option <?php if ($endmonth == "00") { print("selected");} ?> value="00">--</option>



                <option <?php if ($endmonth == "01") { print("selected");} ?> value="01"><?=MONTH_JAN?></option>



                <option <?php if ($endmonth == "02") { print("selected");} ?> value="02"><?=MONTH_FEB?></option>



                <option <?php if ($endmonth == "03") { print("selected");} ?> value="03"><?=MONTH_MAR?></option>



                <option <?php if ($endmonth == "04") { print("selected");} ?> value="04"><?=MONTH_APR?></option>



                <option <?php if ($endmonth == "05") { print("selected");} ?> value="05"><?=MONTH_MAY?></option>



                <option <?php if ($endmonth == "06") { print("selected");} ?> value="06"><?=MONTH_JUN?></option>



                <option <?php if ($endmonth == "07") { print("selected");} ?> value="07"><?=MONTH_JUL?></option>



                <option <?php if ($endmonth == "08") { print("selected");} ?> value="08"><?=MONTH_AUG?></option>



                <option <?php if ($endmonth == "09") { print("selected");} ?> value="09"><?=MONTH_SEP?></option>



                <option <?php if ($endmonth == "10") { print("selected");} ?> value="10"><?=MONTH_OCT?></option>



                <option <?php if ($endmonth == "11") { print("selected");} ?> value="11"><?=MONTH_NOV?></option>



                <option <?php if ($endmonth == "12") { print("selected");} ?> value="12"><?=MONTH_DEC?></option>

                  </select>

                  <input type="text" class="inputf" name="txtEndYear" size="5"value="<?php echo $endyear ?>">

				</td>

          </tr>

          <tr align="left" class="tdeven" >

            <td  >Banner</td>

            <td >

			      <input name="fupload" type="file" class="inputf" id="fupload2" size="30">

			    </td>

          </tr>

          <tr align="left"  class="tdodd">

            <td   colspan="2">

              <?php if (!empty($picture)) print("<img src='$CONST_LINK_ROOT/affiliates/banners/$picture'>");?>

      </td>

          </tr>

          <tr align="center" >

            <td  colspan="2" class="tdfoot">

<input type='submit' value='<?=SAVE_BANNER?>' name='submit' class='button'>

			      <input type='submit' value='<?=LIST_BANNER?>' name='submit' class='button'>

			    </td>

          </tr>

        </form>

      </table>

	 </td>

  </tr>

</table>

<?=$skin->ShowFooter($area)?>