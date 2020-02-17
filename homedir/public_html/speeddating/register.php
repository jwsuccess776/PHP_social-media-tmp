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

# Name: 		register.php

#

# Description:  member registration form

#

# # Version:      8.0

#

######################################################################

include('../db_connect.php');

include('../pop_lists.inc');



# retrieve the template

$area = 'speeddating';



?>

<?=$skin->ShowHeader($area)?>



<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

   <tr>



    <td class="pageheader"><?php echo REGISTER_SECTION_NAME ?></td>

    </tr>

    <tr>

      <td><table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

        <form method="post" enctype='multipart/form-data' action="<?php echo $CONST_LINK_ROOT?>/prgregister.php?mode=create" name="FrmRegister" onSubmit="return Validate_FrmRegister('create')" >

          <input type="hidden" name="speeddating" value="1">

          <tr >

            <td colspan="3" align="left" valign="top" class="tdhead">&nbsp;</td>

          </tr>

          <tr>

            <td align="left" valign="middle" class="tdodd" ><?php echo REGISTER_USERNAME?></td>

            <td align="left" valign="middle" class="tdodd">

              <input type="text" name="txtHandle" size="20" maxlength='25' class="input">

              <a href="javascript:MDM_openWindow('<?php echo $CONST_LINK_ROOT?>/help/hregister1.php','<?php echo REGISTER_HELP?>','width=250,height=375')"><img border='0' src='<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/help_but.gif'></a>

            </td>

            <td rowspan="10" align="left" valign="top" class="tdeven"><b><?php echo REGISTER_IF_YOU_MEMBER?><br>

              <a href="<?php echo $CONST_LINK_ROOT?>/login.php"><?php echo REGISTER_LOG_IN_HERE?></a>.</b>

              <?php echo REGISTER_DESCRIPTION?> </td>

          </tr>

          <tr >

            <td align="left" valign="top" class="tdeven"><?php echo REGISTER_PASSWORD?></td>

            <td  align="left" valign="middle" class="tdeven"> <input name="txtPassword" type="password" class="input" id="txtPassword2" size="20" maxlength="10"></td>

          </tr>

          <tr >

            <td align="left" valign="top" class="tdodd"><?php echo REGISTER_CONFIRM?></td>

            <td  align="left" valign="middle" class="tdodd"> <input name="txtConfirm" type="password" class="input" id="txtConfirm3" size="20" maxlength="15"></td>

          </tr>

          <tr >

            <td align="left" valign="top" class="tdeven"><?php echo REGISTER_LAST_NAME?></td>

            <td  align="left" valign="middle" class="tdeven">

              <input name="txtSurname" type="text" class="input" size="20" maxlength='25'></td>

          </tr>

          <tr >

            <td align="left" valign="top" class="tdodd"><?php echo REGISTER_FIRST_NAME?></td>

            <td  align="left" valign="middle" class="tdodd">

              <input name="txtForename" type="text" class="input" size="20" maxlength='25'></td>

          </tr>

          <tr >

            <td  align="left" valign="middle" class="tdeven"><?php echo REGISTER_BIRTHDAY?></td>

            <td  align="left" valign="middle" class="tdeven">

              <select name="lstDay" size="1" class="inputf">

                <option selected>...</option>

                <option>01</option>

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

              </select> <select name="lstMonth" size="1" class="inputf">

                <option selected>...</option>

                <option value="01"><?php echo MONTH_JAN?></option>

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

              </select>

              19

              <input name="txtYear" type="text" class="inputf" size="3"> </td>

          </tr>

          <tr >

            <td  align="left" valign="middle" class="tdodd"><?php echo REGISTER_SEX?></td>

            <td  align="left" valign="middle" class="tdodd">

              <select name="lstSex" size="1" class="inputf">

                <option selected>- <?php echo GENERAL_CHOOSE?> -</option>

                <option value="M"><?php echo SEX_MALE ?></option>

                <option value="F"><?php echo SEX_FEMALE ?></option>

                <!--<option value="C"><?php echo SEX_COUPLE ?></option>-->

              </select> </td>

          </tr>

          <tr >

            <td  align="left" valign="middle" class="tdeven"><?php echo REGISTER_EMAIL?></td>

            <td  align="left" valign="middle" class="tdeven">

              <input name="txtEmail" type="text" class="input" size="25" maxlength='70'>

            </td>

          </tr>

          <tr >

            <td  align="left" valign="middle" class="tdodd"><?php echo REGISTER_NEWSLETER?></td>

            <td  align="left" valign="middle" class="tdodd">

              <input type="checkbox" name="chkNews" value="1" checked>

            </td>

          </tr>

          <tr >

            <td  align="left" valign="middle" class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/p_disclaimer.php" target="_blank"><?php echo REGISTER_DISCLAIMER?></a></td>

            <td  align="left" valign="middle" class="tdeven">

              <input type="checkbox" name="chkDisclaimer" value="1">

              <?php echo REGISTER_I_AGREE?></td>

          </tr>

          <tr >

            <td  align="center" valign="middle" class="tdfoot">&nbsp; </td>

            <td valign="middle" class="tdfoot"> <input type="submit" class="button" name="I3" value="Register Now"></td>

            <td  align="center" valign="middle" class="tdfoot">&nbsp;</td>

          </tr>

        </form>

      </table></td>

    </tr>

  </table>





<?=$skin->ShowFooter($area)?>

<?php //mysqli_close($link); ?>

