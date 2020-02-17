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

# Name: 		aff_authorise.php

#

# Description:  authorise affiliate

#

# # Version:      8.0

#

######################################################################

include('../db_connect.php');

include_once('../validation_functions.php');

include('../session_handler.inc');

include('../admin/permission.php');

# retrieve the template

$area = 'member';

if (isset($_GET['mode'])) $mode=$_GET['mode'];

$rdoApprove=sanitizeData($_POST['rdoApprove'], 'xss_clean');    

if ($mode=='update') {

	$rdoApprove=sanitizeData($_POST['rdoApprove'], 'xss_clean');    

	$txtAffiliate=sanitizeData($_POST['txtAffiliate'], 'xss_clean');     

	$txtUsername=sanitizeData($_POST['txtUsername'], 'xss_clean');   

	//$txtPassword=$_POST['txtPassword'];
  $tempPass=rand(1000,9999);
  $txtPassword1=$tempPass * 9;
  $txtPassword=md5($txtPassword1);


	$txtEmail=sanitizeData($_POST['txtEmail'], 'xss_clean');    

	$reason=mysqli_real_escape_string($globalMysqlConn,$reason);

	if ($rdoApprove=='Approve') {

		# approved will show up in search

		$query="UPDATE affiliates SET aff_password='$txtPassword' , aff_approved=1 WHERE aff_userid=$txtAffiliate";

		if (!mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),"System Error");}

		$message=GENERAL_DEAR." $txtUsername,\n\n".AFF_AUTHORISE_MAIL_BODY.$CONST_COMPANY.AFF_AUTHORISE_MAIL_BODY1." $CONST_URL/affiliates/\n\n".AFF_AUTHORISE_USERNAME.": $txtUsername\n".AFF_AUTHORISE_PASSWORD.": $txtPassword1\n\n".AFF_AUTHORISE_MAIL_BODY2." $CONST_AFFMAIL\n\n".AFF_AUTHORISE_ADMIN;

		send_mail ("$txtEmail", "$CONST_AFFMAIL", "$CONST_COMPANY ".AFF_AUTHORISE_SUBJECT_APPROVAL, "$message","text","ON");

	} else {

		# rejected will not show up in search or for approval until user amends

		$query="UPDATE affiliates SET aff_approved=2 WHERE aff_userid=$txtAffiliate";

		if (!mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),"System Error");}

		$message=GENERAL_DEAR." $txtUsername,\n\n ".AFF_AUTHORISE_MAIL_BODY_UNAPPROVED." $CONST_COMPANY ($CONST_URL) ".AFF_AUTHORISE_MAIL_BODY1_UNAPPROVED." $reason ".AFF_AUTHORISE_MAIL_BODY2_UNAPPROVED." $CONST_AFFMAIL\n\n".AFF_AUTHORISE_ADMIN;

		$message=stripslashes($message);

		send_mail ("$txtEmail", "$CONST_AFFMAIL", "$CONST_COMPANY ".AFF_AUTHORISE_SUBJECT_UNAPPROVAL, "$message","text","ON");

	}

}

# retrieve the affiliate

$query="SELECT *, gcn_name FROM affiliates LEFT JOIN geo_country ON (gcn_countryid=aff_country) WHERE aff_approved=0 LIMIT 1";

$retval=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

$TOTAL = mysqli_num_rows($retval);

# if nothing is returned then show error otherwise get data

if ($TOTAL < 1) {

	header("Location: $CONST_LINK_ROOT/admin/home.php");

	exit;

} else {

	$sql_array = mysqli_fetch_object($retval);

}



// mysqli_close($link);

?>

<?=$skin->ShowHeader($area)?>

      <!-- form begins here -->

<b></b>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

  <tr>

    <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

    </td>

  </tr>

  <tr>

    <td class="pageheader" ><?php echo AFF_AUTHORISE_CHECK?></td>

  </tr>

  <tr>

    <td><? include("../admin/admin_menu.inc.php");?></td>

  </tr>

  <tr>

    <td><table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

        <form method="post" action="<?php echo $CONST_LINK_ROOT?>/affiliates/aff_authorise.php?mode=update" name="FrmAffiliate">

      	  <input type='hidden' name='txtAffiliate' value="<?php print("$sql_array->aff_userid"); ?>">

      	  <input type='hidden' name='txtUsername' value="<?php print("$sql_array->aff_username"); ?>">

      	 <!--  <input type='hidden' name='txtPassword' value="<?php print("$sql_array->aff_password"); ?>"> -->

      	  <input type='hidden' name='txtEmail' value="<?php print("$sql_array->aff_email"); ?>">

          <tr>

            <td colspan="2" align="left" valign="top" class="tdhead">&nbsp;</td>

          </tr>

          <tr class="tdodd">

            <td align="left" valign="top"><?php echo GENERAL_USERNAME?></td>

            <td align="left" valign="top"><?php print("$sql_array->aff_username"); ?></td>

          </tr>

          <tr class="tdeven">

            <td align="left" valign="top"><?php echo AFF_ACCOUNT_SURNAME?></td>

            <td align="left" valign="top"><?php print("$sql_array->aff_surname"); ?></td>

          </tr>

          <tr class="tdodd">

            <td align="left" valign="top"><?php echo AFF_ACCOUNT_FORENAME?></td>

            <td align="left" valign="top"><?php print("$sql_array->aff_forename"); ?></td>

          </tr>

          <tr>

            <td colspan="2" align="left" valign="top" class="tdfoot">&nbsp;</td>

          </tr>

          <tr>

            <td colspan="2" align="left" valign="top" class="tdhead">&nbsp;</td>

          </tr>

          <tr class="tdodd">

            <td align="left" valign="top"><?php echo AFF_ACCOUNT_BUSINESS?></td>

            <td align="left" valign="top"><?php print("$sql_array->aff_business"); ?></td>

          </tr>

          <tr class="tdeven">

            <td align="left" valign="top"><?php echo AFF_ACCOUNT_ADDRESS?></td>

            <td align="left" valign="top"><?php print("$sql_array->aff_address"); ?></td>

          </tr>

          <tr class="tdodd">

            <td align="left" valign="top"><?php echo AFF_ACCOUNT_STREET?></td>

            <td align="left" valign="top"><?php print("$sql_array->aff_street"); ?></td>

          </tr>

          <tr class="tdeven">

            <td align="left" valign="top"><?php echo AFF_ACCOUNT_CITY?></td>

            <td align="left" valign="top"><?php print("$sql_array->aff_town"); ?></td>

          </tr>

          <tr class="tdodd">

            <td align="left" valign="top"><?php echo AFF_ACCOUNT_STATE?></td>

            <td align="left" valign="top"><?php print("$sql_array->aff_state"); ?></td>

          </tr>

          <tr class="tdeven">

            <td align="left" valign="top"><?php echo AFF_ACCOUNT_ZIP?></td>

            <td align="left" valign="top"><?php print("$sql_array->aff_zipcode"); ?></td>

          </tr>

          <tr class="tdodd">

            <td align="left" valign="top"><?php echo GENERAL_COUNTRY?></td>

            <td align="left" valign="top"><?php print("$sql_array->gcn_name"); ?></td>

          </tr>

          <tr>

            <td colspan="2" align="left" valign="top" class="tdfoot">&nbsp;</td>

          </tr>

          <tr>

            <td colspan="2" align="left" valign="top" class="tdhead">&nbsp;</td>

          </tr>

          <tr class="tdodd">

            <td align="left" valign="top"><?php echo AFF_ACCOUNT_TELEPHONE?></td>

            <td align="left" valign="top"><?php print("$sql_array->aff_telephone"); ?></td>

          </tr>

          <tr class="tdeven">

            <td align="left" valign="top"><?php echo AFF_ACCOUNT_FAX?></td>

            <td align="left" valign="top"><?php print("$sql_array->aff_fax"); ?></td>

          </tr>

          <tr class="tdodd">

            <td align="left" valign="top"><?php echo AFF_ACCOUNT_EMAIL?></td>

            <td align="left" valign="top"><?php print("$sql_array->aff_email"); ?></td>

          </tr>

          <tr class="tdeven">

            <td align="left" valign="top"></td>

            <td align="left" valign="top"></td>

          </tr>

          <tr class="tdodd">

            <td align="left" valign="top"><?php echo AFF_ACCOUNT_WEB?></td>

            <td align="left" valign="top"><a href="<?php print("$sql_array->aff_website"); ?>" target='_blank'><?php print("$sql_array->aff_website"); ?></a></td>

          </tr>

          <tr class="tdeven">

            <td align="left" valign="top"></td>

            <td align="left" valign="top"></td>

          </tr>

          <tr class="tdodd">

            <td align="left" valign="top"><?php echo AFF_ACCOUNT_CHEQUE?>:</td>

            <td align="left" valign="top"><?php print("$sql_array->aff_payname"); ?></td>

          </tr>



          <tr align="center">

            <td colspan="2" valign="top" class="tdfoot">

		<input type="radio" name="rdoApprove" value="Approve" checked>

        <?php echo AFF_AUTHORISE_APPROVE?>

<input type="radio" name="rdoApprove" value="Reject">

        <?php echo AFF_AUTHORISE_REJECT?>&nbsp;<?php echo AFF_AUTHORISE_REASON?>

        <input type='text' name='reason' class="inputl" size='30'></td>

          </tr>

          <tr>

            <td colspan="2" align="left" valign="top"><center>

                <input type="submit" name="Submit" value="<?php echo BUTTON_UPDATE ?>" class="button">

        </center></td>

          </tr>

        </form>

      </table>

	 </td>

  </tr>

</table>

<?=$skin->ShowFooter($area)?>