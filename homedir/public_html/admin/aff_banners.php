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
# Name:                 aff_banners.php
#
# Description:  Members create advert page ('Place Advert')
#
# Version:                7.2
#
######################################################################

include('../db_connect.php');
include_once('../validation_functions.php');
include('../session_handler.inc');
include('permission.php');
include_once('../validation_functions.php'); 

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
			$start_date=startyear."-".$startmonth."-".$startday;

			$text=addslashes(sanitizeData($_POST['text'], 'xss_clean'));

			if (isset($id) && $id != "") {
				$query="UPDATE banners SET ban_start='$start_date', ban_end='$end_date', ban_text='$text', WHERE ban_id = $id";
				mysql_query($query,$link) or die(mysql_error());
			} else {
				$query="INSERT INTO banners (ban_start, ban_end, ban_text, ban_sponsor) VALUES ('$start_date', '$end_date', '$text', '$sponsor')";
				mysql_query($query,$link) or die(mysql_error());
			}
			 header("Location: $CONST_LINK_ROOT/admin/banner_maint.php?id=$id");
			break;
		case 'Banner list':
			 header("Location: $CONST_LINK_ROOT/admin/banners.php");
			break;
		case 'Delete banner':
			$result=mysql_query("DELETE FROM banners WHERE ban_id=$mode",$link) or die(mysql_error());
			break;
	}
}

if (isset($id)) {

	$result=mysql_query("SELECT * FROM banners WHERE ban_id=$id",$link);
	$sql_array=mysql_fetch_object($result);

	$startday=trim(substr($sql_array->ban_start,8,2));
	$startmonth=trim(substr($sql_array->ban_start,5,2));
	$startyear=trim(substr($sql_array->ban_start,2,2));

	$endday=trim(substr($sql_array->ban_end,8,2));
	$endmonth=trim(substr($sql_array->ban_end,5,2));
	$endyear=trim(substr($sql_array->ban_end,2,2));

	$sponsor=$sql_array->ban_sponsor;

	if ($sponsor=='Y') {$Y_selected='checked';}
	elseif ($sponsor=='N') {$N_selected='checked';}
	else {$N_selected='checked';}

	$text=stripslashes($sql_array->ban_text);

}

# retrieve the template
$area = 'member';

?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
  </tr>
  <tr>
    <td class="pageheader"><?php echo AFF_BANNERS_SECTION_NAME?></td>
  </tr>
  <tr>
    <td><? include("admin_menu.inc.php");?></td>
  </tr>
  <tr>
    <td><?php echo $text ?></td>
  </tr>
  <tr>
    <td><table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">
        <form action="<?php echo $CONST_LINK_ROOT?>/admin/banner_maint.php?id=<?php echo $id ?>" method="post" name="frmBanners">
          <tr align="left" valign="top">
            <td colspan="2" class="tdhead">&nbsp;</td>
          </tr>
          <tr align="left" valign="top" class="tdodd">
            <td>Banner Link Text </td>
            <td>
              <textarea  class="inputl"name="text" cols="50" rows="8"><?php echo $text ?></textarea>
              <input type="hidden" name="mode" value=""> </td>
          </tr>
          <tr align="left" valign="top" class="tdeven">
            <td>From Date </td>
            <td>
              <select class="inputs" size="1" name="lstStartDay">
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
              </select> <select class="inputs" size="1" name="lstStartMonth">
                <option <?php if ($startmonth == "00") { print("selected");} ?> value="00">--</option>
                <option <?php if ($startmonth == "01") { print("selected");} ?> value="01">Jan</option>
                <option <?php if ($startmonth == "02") { print("selected");} ?> value="02">Feb</option>
                <option <?php if ($startmonth == "03") { print("selected");} ?> value="03">Mar</option>
                <option <?php if ($startmonth == "04") { print("selected");} ?> value="04">Apr</option>
                <option <?php if ($startmonth == "05") { print("selected");} ?> value="05">May</option>
                <option <?php if ($startmonth == "06") { print("selected");} ?> value="06">Jun</option>
                <option <?php if ($startmonth == "07") { print("selected");} ?> value="07">Jul</option>
                <option <?php if ($startmonth == "08") { print("selected");} ?> value="08">Aug</option>
                <option <?php if ($startmonth == "09") { print("selected");} ?> value="09">Sep</option>
                <option <?php if ($startmonth == "10") { print("selected");} ?> value="10">Oct</option>
                <option <?php if ($startmonth == "11") { print("selected");} ?> value="11">Nov</option>
                <option <?php if ($startmonth == "12") { print("selected");} ?> value="12">Dec</option>
              </select> <input type="text" class="inputs" name="txtStartYear" size="5" value="<?php echo $startyear ?>">
            </td>
          </tr>
          <tr align="left" valign="top" class="tdodd">
            <td>To Date </td>
            <td>
              <select class="inputs" size="1" name="lstEndDay">
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
              </select> <select class="inputs" size="1" name="lstEndMonth">
                <option <?php if ($endmonth == "00") { print("selected");} ?> value="00">--</option>
                <option <?php if ($endmonth == "01") { print("selected");} ?> value="01">Jan</option>
                <option <?php if ($endmonth == "02") { print("selected");} ?> value="02">Feb</option>
                <option <?php if ($endmonth == "03") { print("selected");} ?> value="03">Mar</option>
                <option <?php if ($endmonth == "04") { print("selected");} ?> value="04">Apr</option>
                <option <?php if ($endmonth == "05") { print("selected");} ?> value="05">May</option>
                <option <?php if ($endmonth == "06") { print("selected");} ?> value="06">Jun</option>
                <option <?php if ($endmonth == "07") { print("selected");} ?> value="07">Jul</option>
                <option <?php if ($endmonth == "08") { print("selected");} ?> value="08">Aug</option>
                <option <?php if ($endmonth == "09") { print("selected");} ?> value="09">Sep</option>
                <option <?php if ($endmonth == "10") { print("selected");} ?> value="10">Oct</option>
                <option <?php if ($endmonth == "11") { print("selected");} ?> value="11">Nov</option>
                <option <?php if ($endmonth == "12") { print("selected");} ?> value="12">Dec</option>
              </select> <input type="text" class="inputs" name="txtEndYear" size="5"value="<?php echo $endyear ?>">
            </td>
          </tr>
          <tr align="left" valign="top" class="tdeven">
            <td>Sponsor</td>
            <td>
              <input name="radiobutton" type="radio" value="Y" <?php echo $Y_selected ?>>
              Yes
              <input name="radiobutton" type="radio" value="N" <?php echo $N_selected ?>>
              No</td>
          </tr>
          <tr align="left" valign="top" class="tdodd">
            <td>Category</td>
            <td>
              <select class="inputl" name="categories[]" size="10" multiple>
                <?php
				 	$result=mysql_query("SELECT * FROM categories", $link);
					while($sql_categories = mysql_fetch_object($result)) {
						foreach ($sql_catarray as $value) {
							print("$sql_categories->cat_id - $value");
							if ($sql_categories->cat_id==$value) {
								$selected="selected"; break;
							} else {
								$selected="";
							}
						}
						print("<option value='$sql_categories->cat_id' $selected>$sql_categories->cat_parent : $sql_categories->cat_child</option>\n");
					}
				 ?>
              </select> </td>
          </tr>
          <tr align="center" valign="top">
            <td colspan="2" class="tdfoot">
              <input type='submit' value='Save Banner' name='submit' class='button'>
              &nbsp; <input type='submit' value='Banner list' name='submit' class='button'>
            </td>
          </tr>
        </form>
      </table> </td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>
