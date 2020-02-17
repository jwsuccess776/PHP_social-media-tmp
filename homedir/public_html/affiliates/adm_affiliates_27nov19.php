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

# Name:                 adm_affiliates.php

#

# Description:  Administrator can amend affiliates records

#

# Version:                7.2

#

######################################################################



include_once('../db_connect.php');

include_once('../session_handler.inc');

include_once('../message.php');

include_once('../pop_lists.inc');

include_once('error.php');

include_once('../deletes.php');

include_once('../functions.php');

include('../admin/permission.php');



//print_r($_POST);

$txtHandle=form_get("txtHandle");

$searchEmail=form_get("searchEmail");

$mode=form_get("mode");



# retrieve the template

$area = 'member';



switch ($mode) {



        case 'start':

            $password= ""; $surname= ""; $forename= "";  $website=""; $email=""; $address="";

            $street=""; $town=""; $state=""; $zipcode=""; $country=""; $business="";

            $fax=""; $payname=""; $telephone="";

            break;



        case 'fetch':

            $lstUserid=$_POST['lstUsername'];

            break;



        case 'save':



//            restrict_demo();

            $lstUsername=mysqli_real_escape_string($globalMysqlConn, $_POST['lstUsername']);

            if (isset($_POST['txtPassword']) && $_POST['txtPassword'] != '') {
              $password=md5( trim($_POST['txtPassword']) );
            } else {
              $password='';
            }

            $surname=mysqli_real_escape_string($globalMysqlConn, $_POST['txtSurname']);

            $forename=mysqli_real_escape_string($globalMysqlConn, $_POST['txtForename']);

            $website=mysqli_real_escape_string($globalMysqlConn, $_POST['txtWebsite']);

            $email=mysqli_real_escape_string($globalMysqlConn, $_POST['txtEmail']);



            $address=mysqli_real_escape_string($globalMysqlConn, $_POST['txtAddress']);

            $street=mysqli_real_escape_string($globalMysqlConn, $_POST['txtStreet']);

            $town=mysqli_real_escape_string($globalMysqlConn, $_POST['txtTown']);

            $state=mysqli_real_escape_string($globalMysqlConn, $_POST['txtState']);

            $zipcode=mysqli_real_escape_string($globalMysqlConn, $_POST['txtZipcode']);

            $country=mysqli_real_escape_string($globalMysqlConn, $_POST['txtCountry']);

            $business=mysqli_real_escape_string($globalMysqlConn, $_POST['txtBusiness']);

            $fax=mysqli_real_escape_string($globalMysqlConn, $_POST['txtFax']);

            $payname=mysqli_real_escape_string($globalMysqlConn, $_POST['txtPayname']);

            $telephone=mysqli_real_escape_string($globalMysqlConn, $_POST['txtTelephone']);


if($password != '') {
            $query="UPDATE affiliates SET

                    aff_password = '$password',

                    aff_email = '$email',

                    aff_surname = '$surname',

                    aff_forename = '$forename',

                    aff_address = '$address',

                    aff_street = '$street',

                    aff_town = '$town',

                    aff_state = '$state',

                    aff_zipcode = '$zipcode',

                    aff_country = '$country',

                    aff_business = '$business',

                    aff_fax = '$fax',

                    aff_payname = '$payname',

                    aff_telephone = '$telephone',

                    aff_website = '$website'

                    WHERE aff_userid = '$lstUsername'";
} else {
  $query="UPDATE affiliates SET

                    aff_email = '$email',

                    aff_surname = '$surname',

                    aff_forename = '$forename',

                    aff_address = '$address',

                    aff_street = '$street',

                    aff_town = '$town',

                    aff_state = '$state',

                    aff_zipcode = '$zipcode',

                    aff_country = '$country',

                    aff_business = '$business',

                    aff_fax = '$fax',

                    aff_payname = '$payname',

                    aff_telephone = '$telephone',

                    aff_website = '$website'

                    WHERE aff_userid = '$lstUsername'";
}
//            echo $query;

            $retval=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());



            break;



        case 'deleteme':

                restrict_demo();

                $lstUsername=$_POST['lstUsername'];

                delete_affiliate($lstUsername);

                unset ($lstUsername);

                break;

}



if (isset($_POST['get'])) {



        # select advert data

        $result = mysqli_query($globalMysqlConn,"SELECT * FROM affiliates WHERE aff_userid = '$lstUsername'");

        $TOTAL = mysqli_num_rows($result);



        if ($TOTAL  > 0) {

            $cur_affiliate = mysqli_fetch_object($result);

            # place affiliate data into variables for display

            $lstUsername=$cur_affiliate->aff_username;

            $password=$cur_affiliate->aff_password;

            $surname=$cur_affiliate->aff_surname;

            $forename=$cur_affiliate->aff_forename;

            $website=$cur_affiliate->aff_website;

            $email=$cur_affiliate->aff_email;

            $address=$cur_affiliate->aff_address;

            $street=$cur_affiliate->aff_street;

            $town=$cur_affiliate->aff_town;

            $state=$cur_affiliate->aff_state;

            $zipcode=$cur_affiliate->aff_zipcode;

            $country=$cur_affiliate->aff_country;

            $business=$cur_affiliate->aff_business;

            $fax=$cur_affiliate->aff_fax;

            $payname=$cur_affiliate->aff_payname;

            $telephone=$cur_affiliate->aff_telephone;



        } else {

            $password= ""; $surname= ""; $forename= "";  $website=""; $email=""; $address="";

            $street=""; $town=""; $state=""; $zipcode=""; $country=""; $business="";

            $fax=""; $payname=""; $telephone="";

        }

}



?>

<?=$skin->ShowHeader($area)?>

  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>

      <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>



    <td class="pageheader"><?php echo AFF_ADMIN_SECTION_NAME ?></td>

    </tr>

  <tr>

    <td><? include("../admin/admin_menu.inc.php");?></td>

  </tr>

    <tr><td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

        <form enctype='multipart/form-data' method='post' action='<?php echo $CONST_LINK_ROOT?>/affiliates/adm_affiliates.php' name="FrmAffiliates">

          <tr>

            <td colspan="3" align="left" class="tdhead" >&nbsp;</td>

          </tr>

          <tr class="tdodd">

            <td align="left" ><?php echo REGISTER_EMAIL ?></td>

            <td colspan="2"> <input type="text" class="input" name="searchEmail" value='<?php echo $searchEmail; ?>'>

            </td>

          </tr>

          <tr class="tdodd">

            <td align="left" ><?php echo PRGMEMBERS_SEARCH ?></td>

            <td> <input type="text" class="input" name="txtHandle" value='<?php echo $txtHandle ?>'>

            </td>

            <td align="left"> <input name='submit' type='submit' class='button' id="submit" value='<?php echo BUTTON_SEARCH ?>'>

            </td>

          </tr>

          <tr align="left" valign="top" class="tdeven">

            <td  > <input type='hidden' name="hiddenemail" value="<?php print("$email"); ?>">

              <input type="hidden" name="mode" value="fetch"> <?php echo PRGAFFILIATES_USERID?>

            </td>

            <td > <select name="lstUsername" size="6" class="input">

                <option value='0' <?php if (!isset($lstUsername)) print("selected"); ?>>--

                <?php echo PRGMEMBERS_SELECT?> --</option>

                <?php

                        if (isset($_POST['submit']) || isset($_POST['get'])) {

                                $query="SELECT * FROM affiliates WHERE 1";

                                if (!empty($txtHandle)) {

                                    $query .= " AND aff_username LIKE '%$txtHandle%' ";

                                }

                                if (!empty($searchEmail)) {

                                    $query .= " AND aff_email LIKE '%$searchEmail%' ";

                                }



                                $query .= " ORDER BY aff_username ASC";



                                $result=mysqli_query($globalMysqlConn,$query);

                                while ($sql_username = mysqli_fetch_object($result)) {

                                        if (isset($lstUsername) && $lstUsername==$sql_username->mem_userid) {

                                                print("<option value='$sql_username->aff_userid' selected>$sql_username->aff_username ($sql_username->aff_email)</option>\n");

                                        } else {

                                                print("<option value='$sql_username->aff_userid'>$sql_username->aff_username ($sql_username->aff_email)</option>\n");

                                        }

                                }

                        }

                    ?>

              </select></td>

            <td  valign="bottom"> <input type='submit' name='get' value='<?php echo BUTTON_GETAFFILIATE ?>' class='button'></td>

          </tr>

          </form>



        <form enctype='multipart/form-data' method='post' action='<?php echo $CONST_LINK_ROOT?>/affiliates/adm_affiliates.php' name="FrmCurAffiliate">

        <input type="hidden" name="lstUsername" value="<?=$cur_affiliate->aff_userid;?>">

        <input type="hidden" name="searchEmail" value="<?=$searchEmail;?>">

        <input type="hidden" name="txtHandle" value="<?=$txtHandle;?>">

        <input type="hidden" name="get" value="<?php echo BUTTON_GETAFFILIATE ?>">

        <input type="hidden" name="mode" value="">

          <tr>

            <td colspan="3" align="left" class="tdfoot">&nbsp;</td>

          </tr>

          <tr>

            <td colspan="3" align="left" class="tdhead">&nbsp;</td>

          </tr>

          <tr>

            <td colspan="3" align="left">

              <table width="100%"  border="0" cellspacing="0" cellpadding="0">

                  <tr class="tdodd">

                    <td align="left"  ><?php echo PRGAFFILIATES_SURNAME?></td>

                    <td align="left"> <input name="txtSurname" type="text" class="input" size="12" maxlength="10" value="<?php print("$surname"); ?>">

                    </td>

                    <td align="left"  ><?php echo PRGAFFILIATES_ADDRESS?></td>

                    <td align="left" > <input name="txtAddress" type="text" class="input" size="2" maxlength="40" value="<?php print("$address"); ?>">

                    </td>

                  </tr>

                  <tr class="tdeven"  >

                    <td align="left"><?php echo PRGAFFILIATES_FORENAME?></td>

                    <td align="left"> <input name="txtForename" type="text" class="input" value="<?php print("$forename"); ?>" size="12" maxlength="10">

                    </td>

                    <td align="left"  ><?php echo PRGAFFILIATES_STREET?></td>

                    <td align="left" > <input name="txtStreet" type="text" class="input" size="2" maxlength="40" value="<?php print("$street"); ?>">

                    </td>

                  </tr>

                  <tr class="tdodd">

                    <td align="left"  ><?php echo PRGAFFILIATES_PASSWORD?></td>

                    <td align="left" > <input name="txtPassword" type="text" class="input" value="" size="12" maxlength="10">
                      <?php if ($password){ ?>
                      <br>
                      <small>If want to change password then fill <br> new password other-wise leave blank</small>
                      <?php } ?>

                    </td>

                    <td align="left"  ><?php echo PRGAFFILIATES_TOWN?></td>

                    <td align="left" > <input name="txtTown" type="text" class="input" size="2" maxlength="40" value="<?php print("$town"); ?>">

                    </td>

                  </tr>

                  <tr class="tdeven">

                    <td align="left"  ><?php echo PRGAFFILIATES_WEBSITE?></td>

                    <td align="left" > <input name="txtWebsite" type="text" class="input" size="2" maxlength="40" value="<?php print("$website"); ?>">

                    </td>

                    <td align="left"  ><?php echo PRGAFFILIATES_STATE?></td>

                    <td align="left" > <input name="txtState" type="text" class="input" size="2" maxlength="40" value="<?php print("$state"); ?>">

                    </td>

                  </tr>

                  <tr class="tdeven">

                    <td align="left"  ><?php echo PRGAFFILIATES_BUSINESS?></td>

                    <td align="left" > <input name="txtBusiness" type="text" class="input" size="2" maxlength="40" value="<?php print("$business"); ?>">

                    </td>

                    <td align="left"  ><?php echo PRGAFFILIATES_COUNTRY?></td>

                    <td align="left" > <input name="txtCountry" type="text" class="input" size="2" maxlength="40" value="<?php print("$country"); ?>">

                    </td>

                  </tr>

                  <tr class="tdeven">

                    <td align="left"  ><?php echo PRGAFFILIATES_FAX?></td>

                    <td align="left" > <input name="txtFax" type="text" class="input" size="2" maxlength="40" value="<?php print("$fax"); ?>">

                    </td>

                    <td align="left"  ><?php echo PRGAFFILIATES_ZIPCODE?></td>

                    <td align="left" > <input name="txtZipcode" type="text" class="input" size="2" maxlength="40" value="<?php print("$zipcode"); ?>">

                    </td>

                  </tr>

                  <tr class="tdeven">

                    <td align="left"  ><?php echo PRGAFFILIATES_TELEPHONE?></td>

                    <td align="left" > <input name="txtTelephone" type="text" class="input" size="2" maxlength="40" value="<?php print("$telephone"); ?>">

                    </td>

                    <td align="left"  ><?php echo PRGAFFILIATES_PAYNAME?></td>

                    <td align="left" > <input name="txtPayname" type="text" class="input" size="2" maxlength="40" value="<?php print("$payname"); ?>">

                    </td>

                  </tr>

                  <tr class="tdodd">

                    <td align="left"  ><?php echo PRGAFFILIATES_EMAIL?></td>

                    <td align="left" ><input class="input" type="text" name="txtEmail" value="<?php echo $email ?>"></td>

                    <td align="left" >&nbsp;</td>

                    <td align="left" > <?if ($TOTAL > 0) {?><input type='submit' name='submit' value='<?php echo BUTTON_UPDATE ?>' class='button' onClick="FrmCurAffiliate.mode.value='save';"><?}?></td>

                  </tr>

              </table>

            </td>

          </tr>

          <tr>

            <td colspan="3" align="center" class="tdfoot">&nbsp;</td>

          </tr>

          <tr>

            <td colspan="3" align="left" valign="top" class="tdhead"><?php echo PRGAFFILIATES_CAUTION?></td>

          </tr>

          <tr class="tdodd">

            <td align="left" valign="top">&nbsp;</td>

            <td colspan="2" align="left" valign="top"><?php echo PRGAFFILIATES_TEXT?></td>

          </tr>

          <tr class="tdeven">

            <td align="left" valign="top">&nbsp;</td>

            <td colspan="2" align="left" valign="top"> <?if ($TOTAL > 0) {?><input type='submit' name='deleteaffiliate' value='<?php echo BUTTON_DELETEAFFILIATE ?>' class='button' onClick="FrmCurAffiliate.mode.value='deleteme'; return delete_alert4(); "> <?}?></td>

          </tr>

          <tr>

            <td colspan="3" align="left" valign="top" class="tdfoot"> <center>

              </center></td>

          </tr>

        </form>

      </table></td>

    </tr>

  </table>

</div>

<?=$skin->ShowFooter($area)?>