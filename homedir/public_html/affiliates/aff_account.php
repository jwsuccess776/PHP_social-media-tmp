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
# Name: 		aff_account.php
#
# Description:  Affiliate registration information
#
# # Version:      8.0
#
######################################################################


include('../db_connect.php');
include('aff_session_handler.inc');

# retrieve the template
$area = 'affiliate';

$query="SELECT * FROM affiliates WHERE aff_userid='$Sess_AffUserId'";
$retval=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());
//$retval=mysql_query($query,$link) or die(mysql_error());
$sql_array = mysqli_fetch_object($retval);
$country=$sql_array->aff_country;
?>
<?=$skin->ShowHeader($area)?>
      <!-- form begins here -->
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

  <?
	require('aff_menu.php');
?>

  <tr>

    <td>
	<table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

        <form method="post" action="<?php echo $CONST_LINK_ROOT ?>/affiliates/prgaffiliate.php" name="FrmAffiliate">
          <input type=hidden name=mode value=amend>

          <tr>

            <td valign="top" align="left" colspan="2"></td>
          </tr>

          <tr>

            <td colspan="2" align="left" valign="top" class="tdhead"><?php echo AFF_ACCOUNT_FILL_FIELD?></td>
          </tr>

          <tr>

            <td valign="top" align="left"></td>
            <td valign="top" align="left"></td>
          </tr>

          <tr class="tdodd">

            <td align="left" valign="top"><?php echo AFF_ACCOUNT_SURNAME?></td>
            <td align="left" valign="top"><input type="text" class="input" name="txtSurname" size="28" value="<?php print("$sql_array->aff_surname"); ?>"></td>
          </tr>

          <tr class="tdeven">

            <td align="left" valign="top"><?php echo AFF_ACCOUNT_FORENAME?></td>
            <td align="left" valign="top"><input type="text" class="input" name="txtForename" size="28" value="<?php print("$sql_array->aff_forename"); ?>"></td>
          </tr>

          <tr>

            <td valign="top" align="left"></td>
            <td valign="top" align="left"></td>
          </tr>

          <tr class="tdodd">

            <td align="left" valign="top"><?php echo AFF_ACCOUNT_BUSINESS?></td>
            <td align="left" valign="top"><input type="text" class="input" name="txtBusiness" size="28" value="<?php print("$sql_array->aff_business"); ?>"></td>
          </tr>

          <tr class="tdeven">

            <td align="left" valign="top"><?php echo AFF_ACCOUNT_ADDRESS?></td>
            <td align="left" valign="top"><input type="text" class="input" name="txtAddress" size="28" value="<?php print("$sql_array->aff_address"); ?>"></td>
          </tr>

          <tr class="tdodd">

            <td align="left" valign="top"><?php echo AFF_ACCOUNT_STREET?></td>
            <td align="left" valign="top"><input type="text" class="input" name="txtStreet" size="28" value="<?php print("$sql_array->aff_street"); ?>"></td>
          </tr>

          <tr class="tdeven">

            <td align="left" valign="top"><?php echo AFF_ACCOUNT_CITY?></td>
            <td align="left" valign="top"><input type="text" class="input" name="txtTown" size="28" value="<?php print("$sql_array->aff_town"); ?>"></td>
          </tr>

          <tr class="tdodd">

            <td align="left" valign="top"><?php echo AFF_ACCOUNT_STATE?></td>
            <td align="left" valign="top"><input type="text" class="input" name="txtState" size="28" value="<?php print("$sql_array->aff_state"); ?>"></td>
          </tr>

          <tr class="tdeven">

            <td align="left" valign="top"><?php echo AFF_ACCOUNT_ZIP?></td>
            <td align="left" valign="top"><input type="text" class="input" name="txtZip" size="28" value="<?php print("$sql_array->aff_zipcode"); ?>"></td>
          </tr>

          <tr class="tdodd">

            <td align="left" valign="top"><?php echo GENERAL_COUNTRY?></td>
            <td align="left" valign="top">
               <select class="input" name="lstCountry" id="lstCountry" size="1"  tabindex='29'>
                <option value="0" <?php if ($country == "") { print("selected");} ?> >-- <?php echo GENERAL_CHOOSE?> --</option>

                <option value="" ></option>

                <?php

                    include_once __INCLUDE_CLASS_PATH."/class.Geography.php";

                    $CountriesList = Geography::getCountriesList();

                    foreach ($CountriesList as $countryrow)

                    {   
                      echo '<option value="'.$countryrow->gcn_countryid.'"';
                      if ($countryrow->gcn_countryid == $country) { 
                        echo ' selected';
                      }
                      echo '>';
                      echo htmlspecialchars($countryrow->gcn_name);
                      echo '</option>';
                        

                    }

                ?>

        
                  </select>
                </td>
          </tr>

          <tr>

            <td valign="top" align="left"></td>
            <td valign="top" align="left"></td>
          </tr>

          <tr class="tdeven">

            <td align="left" valign="top"><?php echo AFF_ACCOUNT_TELEPHONE?></td>
            <td align="left" valign="top"><input type="text" class="input" name="txtTelephone" size="28" value="<?php print("$sql_array->aff_telephone"); ?>"></td>
          </tr>

          <tr class="tdodd">

            <td align="left" valign="top"><?php echo AFF_ACCOUNT_FAX?></td>
            <td align="left" valign="top"><input type="text" class="input" name="txtFax" size="28"  value="<?php print("$sql_array->aff_fax"); ?>"></td>
          </tr>

          <tr class="tdeven">

            <td align="left" valign="top"><?php echo AFF_ACCOUNT_EMAIL?></td>
            <td align="left" valign="top"><input type="text" class="input" name="txtEmail" size="28" value="<?php print("$sql_array->aff_email"); ?>"></td>
          </tr>

          <tr>

            <td valign="top" align="left"></td>
            <td valign="top" align="left"></td>
          </tr>

          <tr class="tdodd">

            <td align="left" valign="top"><?php echo AFF_ACCOUNT_WEB?></td>
            <td align="left" valign="top"><input type="text" class="input" name="txtWebsite" size="28" value="<?php print("$sql_array->aff_website"); ?>"></td>
          </tr>

          <tr>

            <td valign="top" align="left"></td>
            <td valign="top" align="left"></td>
          </tr>

          <tr class="tdeven">

            <td align="left" valign="top"><?php echo AFF_ACCOUNT_CHEQUE?>:</td>
            <td align="left" valign="top"><input type="text" class="input" name="txtPayable" size="28" value="<?php print("$sql_array->aff_payname"); ?>"></td>
          </tr>

          <tr>

            <td valign="top" align="left"></td>
            <td valign="top" align="left"></td>
          </tr>

          <tr>

            <td colspan="2" align="left" valign="top" class="tdfoot"><center>
                <input type="submit" name="Submit" value="<?php echo BUTTON_UPDATE ?>" class="button">
        </center></td>
          </tr>

        </form>
      </table>
	 </td>
  </tr>

</table>
<?=$skin->ShowFooter($area)?>