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

# Name: 		prgaffiliate.php

#

# Description:  Processes the affiliate application

#

# # Version:      8.0

#

######################################################################



include('../db_connect.php');
include_once('../validation_functions.php');


include('error.php');



if(isset($_GET['mode'])) $mode=$_GET['mode'];

if(isset($_POST['mode'])) $mode=$_POST['mode'];



if ($mode!='create') include('aff_session_handler.inc');



$txtUsername=sanitizeData($_POST['txtUsername'], 'xss_clean') ;

$txtSurname=sanitizeData($_POST['txtSurname'], 'xss_clean') ;

$txtForename=sanitizeData($_POST['txtForename'], 'xss_clean') ;

$txtBusiness=sanitizeData($_POST['txtBusiness'], 'xss_clean') ;

$txtAddress=sanitizeData($_POST['txtAddress'], 'xss_clean') ;

$txtStreet=sanitizeData($_POST['txtStreet'], 'xss_clean') ;

$txtTown=sanitizeData($_POST['txtTown'], 'xss_clean') ; 

$txtState=sanitizeData($_POST['txtState'], 'xss_clean') ;

$txtZip=sanitizeData($_POST['txtZip'], 'xss_clean') ;

$lstCountry=sanitizeData($_POST['lstCountry'], 'xss_clean') ;

$txtEmail=sanitizeData($_POST['txtEmail'], 'xss_clean') ;

$txtWebsite=sanitizeData($_POST['txtWebsite'], 'xss_clean') ;

$txtPayable=sanitizeData($_POST['txtPayable'], 'xss_clean') ; 



$txtTelephone=sanitizeData($_POST['txtTelephone'], 'xss_clean') ; 

$txtFax=sanitizeData($_POST['txtFax'], 'xss_clean') ; 





# retrieve the template

$area = 'guest';



// gives basic validation if the javascript fails to catch

if ($mode == 'create') {



	$txtUsername=trim($txtUsername);

	if (empty($txtUsername) || strlen($txtUsername) < 6) {

		$error_message=AFF_PRGAFF_ERROR1;

		error_page($error_message,GENERAL_USER_ERROR);

	}

	if (strlen($txtUsername) > 15 ) {

			$error_message=AFF_PRGAFF_ERROR2;

			error_page($error_message,GENERAL_USER_ERROR);

	}

}

$txtSurname=trim($txtSurname);

if (empty($txtSurname) || strlen($txtSurname) < 2) {

	$error_message=AFF_PRGAFF_ERROR3;

	error_page($error_message,GENERAL_USER_ERROR);

}

if (strlen($txtSurname) > 25 ) {

		$error_message=AFF_PRGAFF_ERROR4;

		error_page($error_message,GENERAL_USER_ERROR);

}

$txtForename=trim($txtForename);

if (empty($txtForename) || strlen($txtForename) < 2) {

	$error_message=AFF_PRGAFF_ERROR5;

	error_page($error_message,GENERAL_USER_ERROR);

}

if (strlen($txtForename) > 25 ) {

		$error_message=AFF_PRGAFF_ERROR6;

		error_page($error_message,GENERAL_USER_ERROR);

}

$txtBusiness=trim($txtBusiness);

if (empty($txtBusiness) || strlen($txtBusiness) < 2) {

	$error_message=AFF_PRGAFF_ERROR7;

	error_page($error_message,GENERAL_USER_ERROR);

}

if (strlen($txtBusiness) > 50 ) {

		$error_message=AFF_PRGAFF_ERROR8;

		error_page($error_message,GENERAL_USER_ERROR);

}

$txtAddress=trim($txtAddress);

if (empty($txtAddress) || strlen($txtAddress) < 2) {

	$error_message=AFF_PRGAFF_ERROR9;

	error_page($error_message,GENERAL_USER_ERROR);

}

if (strlen($txtAddress) > 50 ) {

		$error_message=AFF_PRGAFF_ERROR10;

		error_page($error_message,GENERAL_USER_ERROR);

}

$txtStreet=trim($txtStreet);

if (empty($txtStreet) || strlen($txtStreet) < 2) {

	$error_message=AFF_PRGAFF_ERROR11;

	error_page($error_message,GENERAL_USER_ERROR);

}

if (strlen($txtStreet) > 50 ) {

		$error_message=AFF_PRGAFF_ERROR12;

		error_page($error_message,GENERAL_USER_ERROR);

}

$txtTown=trim($txtTown);

if (empty($txtTown) || strlen($txtTown) < 2) {

	$error_message=AFF_PRGAFF_ERROR13;

	error_page($error_message,GENERAL_USER_ERROR);

}

if (strlen($txtTown) > 50 ) {

		$error_message=AFF_PRGAFF_ERROR14;

		error_page($error_message,GENERAL_USER_ERROR);

}

$txtState=trim($txtState);

if (empty($txtState) || strlen($txtState) < 2) {

	$error_message=AFF_PRGAFF_ERROR15;

	error_page($error_message,GENERAL_USER_ERROR);

}

if (strlen($txtState) > 50 ) {

		$error_message=AFF_PRGAFF_ERROR16;

		error_page($error_message,GENERAL_USER_ERROR);

}

$txtZip=trim($txtZip);

if (empty($txtZip) || strlen($txtZip) < 2) {

	$error_message=AFF_PRGAFF_ERROR17;

	error_page($error_message,GENERAL_USER_ERROR);

}

if (strlen($txtZip) > 10 ) {

		$error_message=AFF_PRGAFF_ERROR18;

		error_page($error_message,GENERAL_USER_ERROR);

}

if ($lstCountry == "- Choose -") {

	$error_message=AFF_PRGAFF_ERROR19;

	error_page($error_message,GENERAL_USER_ERROR);

}

$txtEmail=trim($txtEmail);

if (empty($txtEmail) || strlen($txtEmail) < 2) {

	$error_message=AFF_PRGAFF_ERROR20;

	error_page($error_message,GENERAL_USER_ERROR);

}

$txtWebsite=trim($txtWebsite);

if (empty($txtWebsite) || strlen($txtWebsite) < 11) {

	$error_message=AFF_PRGAFF_ERROR21;

	error_page($error_message,GENERAL_USER_ERROR);

}

if (strlen($txtWebsite) > 100 ) {

		$error_message=AFF_PRGAFF_ERROR22;

		error_page($error_message,GENERAL_USER_ERROR);

}

$txtPayable=trim($txtPayable);

if (empty($txtPayable) || strlen($txtPayable) < 2) {

	$error_message=AFF_PRGAFF_ERROR23;

	error_page($error_message,GENERAL_USER_ERROR);

}

if (strlen($txtPayable) > 50 ) {

		$error_message=AFF_PRGAFF_ERROR24;

		error_page($error_message,GENERAL_USER_ERROR);

}



if ($mode == 'create') {

	$query="SELECT aff_username FROM affiliates WHERE aff_username='$txtUsername'";
	$result=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());

	//$retval=mysql_query($query,$link) or die(mysql_error());

	//$result=mysql_num_rows($retval);

	if (mysqli_num_rows($result) > 0) {

		$error_message=AFF_PRGAFF_ERROR25;

		error_page($error_message,GENERAL_USER_ERROR);

	}

	$tempDate=date("Y/m/d");

	srand((double)microtime()*1000000);

	//$tempPass=rand(1000,9999);

	//$tempPass=md5($tempPass * 9);

	$tempPass='';

	$query="INSERT INTO affiliates (aff_username,

									aff_password,

									aff_surname,

									aff_forename,

									aff_email,

									aff_address,

									aff_street,

									aff_town,

									aff_zipcode,

									aff_country,

									aff_joindate,

									aff_business,

									aff_payname,

									aff_website,

									aff_approved,

									aff_clickthru,

									aff_fax,

									aff_telephone,

									aff_state)

							VALUES('$txtUsername',

									'$tempPass',

									'$txtSurname',

									'$txtForename',

									'$txtEmail',

									'$txtAddress',

									'$txtStreet',

									'$txtTown',

									'$txtZip',

									'$lstCountry',

									'$tempDate',

									'$txtBusiness',

									'$txtPayable',

									'$txtWebsite',

									'0',

									0,

									'$txtFax',

									'$txtTelephone',

									'$txtState')";

	/*$retval=mysql_query($query,$link) or die(mysql_error());*/
	$result=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());
	send_mail ("$CONST_AFFMAIL", "$CONST_AFFMAIL", AFF_PRGAFF_MAIL_SUBJ, AFF_PRGAFF_MAIL_BODY,"text","ON");

} else {

	// amend affiliate code here

	$query="UPDATE affiliates SET aff_surname = '$txtSurname',

									aff_forename = '$txtForename',

									aff_email ='$txtEmail',

									aff_address ='$txtAddress',

									aff_street ='$txtStreet',

									aff_town ='$txtTown',

									aff_zipcode ='$txtZip',

									aff_country ='$lstCountry',

									aff_business ='$txtBusiness',

									aff_payname ='$txtPayable',

									aff_website ='$txtWebsite',

									aff_fax ='$txtFax',

									aff_telephone ='$txtTelephone',

									aff_state ='$txtState' WHERE aff_userid = $Sess_AffUserId";

	//$retval=mysql_query($query,$link) or die(mysql_error());
	$result=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());
	header("Location: $CONST_LINK_ROOT/affiliates/aff_summary.php");

	exit;

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



    <td class="pageheader"><?php echo AFF_RESPONSE_SECTION_NAME ?></td>

  </tr>



  <tr>



    <td>



	      <?php echo AFF_PRGAFF_TEXT?>

              <p><a href='<?php echo $CONST_LINK_ROOT?>/affiliates/index.php'><?php echo GENERAL_CONTINUE?></a></p>	 </td>

  </tr>



</table>

<?=$skin->ShowFooter($area)?>