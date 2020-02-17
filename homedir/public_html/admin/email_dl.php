<?php
/*****************************************************
* © copyright 1999 - 2020 iDateMedia, LLC.
*
* All materials and software are copyrighted by iDateMedia, LLC.
* under British, US and International copyright law. All rights reserved.
* No part of this code may be reproduced, sold, distributed
* or otherwise used in whole or in part without prior written permission.
*
****************************************************/

######################################################################
#
# Name:         email_dl.php
#
# Description:  Displays the profile input page (after advert)
#
# Version:      7.2
#
######################################################################

include('../db_connect.php');
include('../session_handler.inc');
include('permission.php');

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
    <td class="pageheader"><?php echo EMAIL_SECTION_NAME ?></td>
  </tr>
  <tr>
    <td><? include("admin_menu.inc.php");?></td>
  </tr>
  <tr>
    <td><?php echo EMAIL_DL_TEXT?></td>
  </tr>
  <tr>
    <td>
    <table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">
        <form action="<?php echo $CONST_LINK_ROOT?>/admin/prgemail_dl.php" method="post" name="frmEmails">
          <tr>
            <td colspan="2" class="tdhead">&nbsp;</td>
          </tr>
          <tr class="tdodd">
            <td><?php echo GENERAL_FROM?></td>
            <td> <select class="inputf" name="lstFromDay" size="1" id="select">
                <option selected>01</option>
                <option>02</option>
                <option>03</option>
                <option>04</option>
                <option>05</option>
                <option>06</option>
                <option>07</option>
                <option>08</option>
                <option>09</option>
                <option>10</option>
                <option>11</option>
                <option>12</option>
                <option>13</option>
                <option>14</option>
                <option>15</option>
                <option>16</option>
                <option>17</option>
                <option>18</option>
                <option>19</option>
                <option>20</option>
                <option>21</option>
                <option>22</option>
                <option>23</option>
                <option>24</option>
                <option>25</option>
                <option>26</option>
                <option>27</option>
                <option>28</option>
                <option>29</option>
                <option>30</option>
                <option>31</option>
              </select> <select class="inputf" name="lstFromMonth" size="1" id="select2">
                <option value="01" selected><?php echo MONTH_JAN?></option>
                <option value="02"><?php echo MONTH_FEB?></option>
                <option value="03"><?php echo MONTH_MAR?></option>
                <option value="04"><?php echo MONTH_APR?></option>
                <option value="05"><?php echo MONTH_MAY?></option>
                <option value="06"><?php echo MONTH_JUN?></option>
                <option value="07"><?php echo MONTH_JUL?></option>
                <option value="08"><?php echo MONTH_AUG?></option>
                <option value="09"><?php echo MONTH_SEP?></option>
                <option value="10"><?php echo MONTH_OCT?></option>
                <option value="11"><?php echo MONTH_NOV?></option>
                <option value="12"><?php echo MONTH_DEC?></option>
              </select> <select class="inputf" name="lstFromYear" id="select3">
                <?php
            $curryear=$year=date('Y');
            while ($year > $curryear-20) {
                if (isset($lstYear) && $lstYear == $year)
                    print("<option selected>$year</option>");
                else
                    print("<option>$year</option>");
                $year=$year-1;
            }
        ?>
              </select>
            </td>
          </tr>
          <tr class="tdeven">
            <td><?php echo GENERAL_TO?></td>
            <td> <select class="inputf" name="lstToDay" size="1" id="select4">
                <option selected>01</option>
                <option>02</option>
                <option>03</option>
                <option>04</option>
                <option>05</option>
                <option>06</option>
                <option>07</option>
                <option>08</option>
                <option>09</option>
                <option>10</option>
                <option>11</option>
                <option>12</option>
                <option>13</option>
                <option>14</option>
                <option>15</option>
                <option>16</option>
                <option>17</option>
                <option>18</option>
                <option>19</option>
                <option>20</option>
                <option>21</option>
                <option>22</option>
                <option>23</option>
                <option>24</option>
                <option>25</option>
                <option>26</option>
                <option>27</option>
                <option>28</option>
                <option>29</option>
                <option>30</option>
                <option>31</option>
              </select> <select class="inputf" name="lstToMonth" size="1" id="select5">
                <option value="01" selected><?php echo MONTH_JAN?></option>
                <option value="02"><?php echo MONTH_FEB?></option>
                <option value="03"><?php echo MONTH_MAR?></option>
                <option value="04"><?php echo MONTH_APR?></option>
                <option value="05"><?php echo MONTH_MAY?></option>
                <option value="06"><?php echo MONTH_JUN?></option>
                <option value="07"><?php echo MONTH_JUL?></option>
                <option value="08"><?php echo MONTH_AUG?></option>
                <option value="09"><?php echo MONTH_SEP?></option>
                <option value="10"><?php echo MONTH_OCT?></option>
                <option value="11"><?php echo MONTH_NOV?></option>
                <option value="12"><?php echo MONTH_DEC?></option>
              </select> <select class="inputf" name="lstToYear" id="select6">
                <?php
            $curryear=$year=date('Y');
            while ($year > $curryear-20) {
                if (isset($lstYear) && $lstYear == $year)
                    print("<option selected>$year</option>");
                else
                    print("<option>$year</option>");
                $year=$year-1;
            }
        ?>
              </select>
            </td>
          </tr>
          <tr align="center">
            <td colspan="2" class="tdfoot"> <input name="Submit" type="submit" class="button" value="<?php echo EMAIL_DL_BUTTON?>">
            </td>
          </tr>
        </form>
      </table></td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>
