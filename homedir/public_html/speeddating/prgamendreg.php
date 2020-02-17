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
# Name: 		prgamendreg.php
#
# Description:  Displays member registration for editing
#
# # Version:      8.0
#
######################################################################

include('../db_connect.php');
include('session_handler.inc');
include('error.php');
# retrieve the template
$area = 'speeddating';
$result = mysqli_query($globalMysqlConn,"SELECT * FROM members WHERE mem_userid=$Sess_UserId");
$TOTAL = mysqli_num_rows($result);
# if there are no records display error otherwise get data
if ($TOTAL < 1) {
    $error_message=PRGAMENDREG_ERROR;
    error_page($error_message,GENERAL_USER_ERROR);
} else {
    $sql_array = mysqli_fetch_object($result);
}
# copy data to variables for display
$surname=$sql_array->mem_surname;
$forename=$sql_array->mem_forename;
$password=$sql_array->mem_password;
$email=$sql_array->mem_email;
$sex=$sql_array->mem_sex;
$day=trim(substr($sql_array->mem_dob,8,2));
$month=trim(substr($sql_array->mem_dob,5,2));
$year=trim(substr($sql_array->mem_dob,2,2));
if ($sql_array->mem_newsletter == 1) {
    $newsletter='checked';
} else {
    $newsletter='';
}
// mysqli_close( $link );
?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td class="pageheader"><?php echo REGISTRATION_UPDATE_SECTION_NAME ?></td>
  </tr>
  <tr>
    <td><table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">
        <form method="post" action="<?php echo $CONST_LINK_ROOT?>/prgregister.php?mode=update" name="FrmRegister" onSubmit="return Validate_FrmRegister('update')" >
          <input type="hidden" name="speeddating" value="1">
          <tr>
            <td colspan="3" align="left" valign="top" class="tdhead">&nbsp;</td>
          </tr>
          <tr >
            <td  align="left" class="tdodd"><?php echo GENERAL_PASSWORD?></td>
            <td align="left" class="tdodd" > <input name="txtPassword" type="password" class="input" id="txtPassword2" value="<?php print("$password"); ?>" size="20" maxlength="10"></td>
            <td  rowspan="8" align="left" valign="top" class="tdeven"><?php echo PRGAMENDREG_TEXT?></td>
          </tr>
          <tr >
            <td  align="left" class="tdeven"><?php echo REGISTER_CONFIRM?></td>
            <td  align="left" class="tdeven"> <input name="txtConfirm" type="password" class="input" id="txtConfirm2" value="<?php print("$password"); ?>" size="20" maxlength="10"></td>
          </tr>
          <tr >
            <td  align="left" class="tdodd"><?php echo REGISTER_LAST_NAME?></td>
            <td align="left" class="tdodd" > <input type="text" class="input" name="txtSurname" size="20" maxlength='25' value="<?php print("$surname"); ?>"></td>
          </tr>
          <tr >
            <td  align="left" class="tdeven"><?php echo REGISTER_FIRST_NAME?></td>
            <td align="left" class="tdeven" > <input type="text" class="input" name="txtForename" size="20" maxlength='25' value="<?php print("$forename"); ?>"></td>
          </tr>
          <tr >
            <td  align="left" nowrap class="tdodd"><?php echo REGISTER_BIRTHDAY?></td>
            <td align="left" nowrap class="tdodd" > <select name="lstDay" size="1" class="inputf">
                <option <?php if ($day == "01") { print("selected");} ?> value="01">01</option>
                <option <?php if ($day == "02") { print("selected");} ?> value="02">02</option>
                <option <?php if ($day == "03") { print("selected");} ?> value="03">03</option>
                <option <?php if ($day == "04") { print("selected");} ?> value="04">04</option>
                <option <?php if ($day == "05") { print("selected");} ?> value="05">05</option>
                <option <?php if ($day == "06") { print("selected");} ?> value="06">06</option>
                <option <?php if ($day == "07") { print("selected");} ?> value="07">07</option>
                <option <?php if ($day == "08") { print("selected");} ?> value="08">08</option>
                <option <?php if ($day == "09") { print("selected");} ?> value="09">09</option>
                <option <?php if ($day == "10") { print("selected");} ?> value="10">10</option>
                <option <?php if ($day == "11") { print("selected");} ?> value="11">11</option>
                <option <?php if ($day == "12") { print("selected");} ?> value="12">12</option>
                <option <?php if ($day == "13") { print("selected");} ?> value="13">13</option>
                <option <?php if ($day == "14") { print("selected");} ?> value="14">14</option>
                <option <?php if ($day == "15") { print("selected");} ?> value="15">15</option>
                <option <?php if ($day == "16") { print("selected");} ?> value="16">16</option>
                <option <?php if ($day == "17") { print("selected");} ?> value="17">17</option>
                <option <?php if ($day == "18") { print("selected");} ?> value="18">18</option>
                <option <?php if ($day == "19") { print("selected");} ?> value="19">19</option>
                <option <?php if ($day == "20") { print("selected");} ?> value="20">20</option>
                <option <?php if ($day == "21") { print("selected");} ?> value="21">21</option>
                <option <?php if ($day == "22") { print("selected");} ?> value="22">22</option>
                <option <?php if ($day == "23") { print("selected");} ?> value="23">23</option>
                <option <?php if ($day == "24") { print("selected");} ?> value="24">24</option>
                <option <?php if ($day == "25") { print("selected");} ?> value="25">25</option>
                <option <?php if ($day == "26") { print("selected");} ?> value="26">26</option>
                <option <?php if ($day == "27") { print("selected");} ?> value="27">27</option>
                <option <?php if ($day == "28") { print("selected");} ?> value="28">28</option>
                <option <?php if ($day == "29") { print("selected");} ?> value="29">29</option>
                <option <?php if ($day == "30") { print("selected");} ?> value="30">30</option>
                <option <?php if ($day == "31") { print("selected");} ?> value="31">31</option>
              </select> <select name="lstMonth" size="1" class="inputf" >
                <option <?php if ($month == "01") { print("selected");} ?> value="01">
                <?php echo MONTH_JAN?></option>
                <option <?php if ($month == "02") { print("selected");} ?> value="02"><?php echo MONTH_FEB?></option>
                <option <?php if ($month == "03") { print("selected");} ?> value="03"><?php echo MONTH_MAR?></option>
                <option <?php if ($month == "04") { print("selected");} ?> value="04"><?php echo MONTH_APR?></option>
                <option <?php if ($month == "05") { print("selected");} ?> value="05"><?php echo MONTH_MAY?></option>
                <option <?php if ($month == "06") { print("selected");} ?> value="06"><?php echo MONTH_JUN?></option>
                <option <?php if ($month == "07") { print("selected");} ?> value="07"><?php echo MONTH_JUL?></option>
                <option <?php if ($month == "08") { print("selected");} ?> value="08"><?php echo MONTH_AUG?></option>
                <option <?php if ($month == "09") { print("selected");} ?> value="09"><?php echo MONTH_SEP?></option>
                <option <?php if ($month == "10") { print("selected");} ?> value="10"><?php echo MONTH_OCT?></option>
                <option <?php if ($month == "11") { print("selected");} ?> value="11"><?php echo MONTH_NOV?></option>
                <option <?php if ($month == "12") { print("selected");} ?> value="12"><?php echo MONTH_DEC?></option>
              </select>
              19
              <input type="text" class="inputf" name="txtYear" size="3"  value="<?php print("$year"); ?>"></td>
          </tr>
          <tr >
            <td  align="left" class="tdeven"><?php echo SEX?></td>
            <td align="left" class="tdeven" > <select name="lstSex" size="1" class="input" >
                <option <?php if ($sex == "M") { print("selected");} ?> value="M"><?php echo SEX_MALE?></option>
                <option <?php if ($sex == "F") { print("selected");} ?> value="F"><?php echo SEX_FEMALE?></option>
                <option <?php if ($sex == "C") { print("selected");} ?> value="C"><?php echo SEX_COUPLE?></option>
              </select></td>
          </tr>
          <tr >
            <td  align="left" class="tdodd"><?php echo AFF_CONTACT_EMAIL?></td>
            <td align="left" class="tdodd" > <input type="text" class="input" name="txtEmail" size="25" maxlength='70' value="<?php print("$email"); ?>"></td>
          </tr>
          <tr >
            <td  align="left" valign="top" class="tdeven"><?php echo REGISTER_NEWSLETER?></td>
            <td  align="left" class="tdeven"> <input type="checkbox" name="chkNews" value="1" <?php echo $newsletter ?> >
            </td>
          </tr>
          <tr align="center">
            <td colspan="3" class="tdfoot"> <input type="submit" name="Submit" value="<?php echo BUTTON_SUBMIT ?>" class="button">
            </td>
          </tr>
        </form>
      </table></td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>
